# Backend Security Checklist

A practical security reference for Laravel backend systems. Read this when implementing authentication, authorization, or handling sensitive data.

## Table of Contents

1. [Authentication](#authentication)
2. [Authorization](#authorization)
3. [Input Validation](#input-validation)
4. [SQL Injection Prevention](#sql-injection-prevention)
5. [XSS Prevention](#xss-prevention)
6. [CSRF Protection](#csrf-protection)
7. [Rate Limiting](#rate-limiting)
8. [Secrets Management](#secrets-management)
9. [Logging and Monitoring](#logging-and-monitoring)
10. [Security Headers](#security-headers)

---

## Authentication

### JWT / Sanctum Token Setup
```php
// Use Laravel Sanctum for SPA + API authentication
// config/sanctum.php
'expiration' => 60 * 24, // Token expires after 24 hours

// Issue token with scoped abilities
$token = $user->createToken('api-access', ['payroll:read', 'payroll:write']);

// Verify ability on request
if ($request->user()->tokenCan('payroll:write')) {
    // Process write operation
}
```

### Password Security
```php
// In validation rules — enforce strong passwords
'password' => [
    'required',
    'confirmed',
    Password::min(8)
        ->mixedCase()
        ->numbers()
        ->symbols()
        ->uncompromised(), // Checks against breached password databases
],

// Never log passwords
Log::info('User logged in', ['user_id' => $user->id]); // Good
Log::info('Login attempt', ['password' => $password]);  // NEVER
```

### Session Security
```php
// config/session.php
'secure' => true,           // HTTPS only
'http_only' => true,        // Not accessible via JavaScript
'same_site' => 'lax',       // CSRF protection
'lifetime' => 120,          // 2 hour session
```

---

## Authorization

### Policy-Based Authorization
```php
class PayrollPolicy
{
    // Only payroll admins can view any payroll
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('payroll.view');
    }

    // Employees can view their own, admins can view all
    public function view(User $user, Payroll $payroll): bool
    {
        return $user->id === $payroll->employee->user_id
            || $user->hasPermission('payroll.view');
    }

    // Cannot approve own payroll (segregation of duties)
    public function approve(User $user, Payroll $payroll): bool
    {
        return $user->hasPermission('payroll.approve')
            && $user->employee_id !== $payroll->employee_id
            && $payroll->status === 'draft';
    }

    // Only super-admins can delete payroll records
    public function delete(User $user, Payroll $payroll): bool
    {
        return $user->hasRole('super-admin')
            && $payroll->status === 'draft';
    }
}
```

### Route-Level Authorization
```php
// In controller
public function show(Payroll $payroll)
{
    $this->authorize('view', $payroll);
    return new PayrollResource($payroll);
}

// In route definition
Route::get('/payrolls/{payroll}', [PayrollController::class, 'show'])
    ->middleware('can:view,payroll');
```

### Principle of Least Privilege
- Default to denying access
- Grant specific permissions, not broad roles
- Separate read and write permissions
- Implement segregation of duties for financial operations

---

## Input Validation

### Validate Everything
```php
class StoreEmployeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email:rfc,dns', 'unique:employees'],
            'department_id' => ['required', 'exists:departments,id'],
            'hourly_rate'   => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'hire_date'     => ['required', 'date', 'before_or_equal:today'],
            'phone'         => ['nullable', 'regex:/^[\d\s\-\+\(\)]+$/', 'max:20'],
        ];
    }
}
```

### File Upload Validation
```php
'document' => [
    'required',
    'file',
    'mimes:pdf,doc,docx,xlsx',  // Whitelist allowed types
    'max:10240',                 // 10MB max
],

// Additional server-side check — don't trust file extension alone
$mimeType = $file->getMimeType();
$allowedMimes = ['application/pdf', 'application/msword'];
if (!in_array($mimeType, $allowedMimes)) {
    throw new InvalidFileTypeException();
}
```

---

## SQL Injection Prevention

```php
// SAFE — Eloquent parameterizes automatically
Employee::where('department_id', $request->department_id)->get();

// SAFE — Query builder parameterizes
DB::table('employees')->where('email', $email)->first();

// DANGEROUS — Raw SQL with concatenation
DB::select("SELECT * FROM employees WHERE email = '$email'"); // NEVER

// SAFE — Raw SQL with bindings
DB::select("SELECT * FROM employees WHERE email = ?", [$email]);

// SAFE — Raw expressions with bindings
Employee::whereRaw('LOWER(email) = ?', [strtolower($email)])->first();
```

---

## XSS Prevention

```php
// Blade auto-escapes by default
{{ $user->name }}  // Safe — HTML entities are escaped

// DANGEROUS — Raw output
{!! $user->bio !!}  // Only use with sanitized content

// Sanitize HTML if you must allow it
use HTMLPurifier;
$clean = (new HTMLPurifier())->purify($request->input('bio'));
```

---

## CSRF Protection

```php
// Laravel includes CSRF middleware by default for web routes
// Always include in forms:
<form method="POST" action="/payroll">
    @csrf
    ...
</form>

// For AJAX (Laravel reads from X-XSRF-TOKEN header automatically)
// Axios and jQuery handle this if you set the token in a meta tag:
<meta name="csrf-token" content="{{ csrf_token() }}">
```

---

## Rate Limiting

```php
// Protect sensitive endpoints
RateLimiter::for('login', function (Request $request) {
    return [
        Limit::perMinute(5)->by($request->input('email') . '|' . $request->ip()),
        Limit::perMinute(30)->by($request->ip()),
    ];
});

// Apply to routes
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');

// API rate limiting with headers
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)
        ->by($request->user()?->id ?: $request->ip())
        ->response(function () {
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
            ], 429);
        });
});
```

---

## Secrets Management

```php
// .env — NEVER commit to version control
DB_PASSWORD=supersecret
API_KEY=sk-live-xxx

// Access via config, not env() directly in code
// config/services.php
'payment_gateway' => [
    'key' => env('PAYMENT_API_KEY'),
    'secret' => env('PAYMENT_API_SECRET'),
],

// In code — use config()
$apiKey = config('services.payment_gateway.key');

// Encrypt sensitive database columns
protected $casts = [
    'ssn' => 'encrypted',
    'bank_account' => 'encrypted',
];
```

### .gitignore Essentials
```
.env
.env.backup
.env.production
*.pem
*.key
credentials.json
token.json
```

---

## Logging and Monitoring

### Structured Logging
```php
// Log security events with context
Log::channel('security')->warning('Failed login attempt', [
    'email'      => $request->input('email'),
    'ip'         => $request->ip(),
    'user_agent' => $request->userAgent(),
    'timestamp'  => now()->toIso8601String(),
]);

// Log authorization failures
Log::channel('security')->alert('Unauthorized access attempt', [
    'user_id'  => auth()->id(),
    'resource' => 'payroll',
    'action'   => 'delete',
    'target_id' => $payroll->id,
]);
```

### What to Log
- ✅ Authentication events (login, logout, failed attempts)
- ✅ Authorization failures
- ✅ Data modifications on sensitive records
- ✅ Admin actions
- ✅ API errors and unusual patterns
- ❌ Passwords or secrets
- ❌ Full credit card numbers
- ❌ Personal health information (unless compliant)

---

## Security Headers

```php
// Middleware: SecurityHeaders.php
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        if (app()->isProduction()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
```

---

## Quick Security Audit Checklist

Before deploying, verify:

- [ ] All user input is validated via Form Requests
- [ ] Authentication is required on all non-public routes
- [ ] Authorization checks exist for every data-modifying endpoint
- [ ] Sensitive data is encrypted at rest (SSN, bank details)
- [ ] Passwords meet complexity requirements
- [ ] Rate limiting is active on login and sensitive endpoints
- [ ] CORS is configured (not wildcard in production)
- [ ] Debug mode is OFF in production (`APP_DEBUG=false`)
- [ ] `.env` is in `.gitignore`
- [ ] Error pages don't leak stack traces in production
- [ ] SQL queries use parameterized bindings
- [ ] File uploads validate MIME type server-side
- [ ] Admin actions are logged to an audit trail
