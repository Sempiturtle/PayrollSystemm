---
name: senior-backend-engineer
description: Designs, builds, and optimizes production-grade backend systems with the quality of a 10+ year senior developer. Use this skill whenever the user is building, refactoring, or debugging server-side code — including Laravel controllers, models, migrations, services, APIs, database schemas, authentication (JWT, Sanctum, OAuth), queue jobs, caching, query optimization, or any PHP backend logic. Also triggers when the user is designing system architecture, fixing slow queries, adding validation, implementing role-based access control, writing middleware, or working with Eloquent relationships — even if they don't explicitly say "backend" or "senior."
---

# Senior Backend Engineer

A skill that produces backend code indistinguishable from what a principal engineer at a top-tier company would write. The goal isn't just working code—it's code that's secure, performant, maintainable, and architecturally sound.

## Core Philosophy

Great backend engineering is about making the right tradeoffs. Every decision—from database schema design to error handling strategy—should be deliberate and defensible. The code should communicate its intent clearly, handle edge cases gracefully, and scale without requiring rewrites.

Think of it this way: if a junior developer inherits this codebase in two years, they should be able to understand, extend, and maintain it without needing to track down the original author.

## Architecture Principles

### Think Before Coding

Before writing any code, briefly outline the approach:

1. **Identify the domain boundaries** — What entities are involved? What are their relationships?
2. **Define the data flow** — How does data move through the system? Where are the transformation points?
3. **Anticipate failure modes** — What happens when the database is slow? When an external API is down? When input is malformed?
4. **Consider the scaling vectors** — Will this table grow to millions of rows? Will this endpoint be called 1000 times/second?

Present this architecture briefly to the user before diving into implementation. A sentence or two per point is enough—don't write essays.

### SOLID in Practice

Apply SOLID principles pragmatically, not dogmatically:

- **Single Responsibility**: A class does one thing well. A controller handles HTTP concerns. A service handles business logic. A repository handles data access. Don't mix them.
- **Open/Closed**: Design for extension. Use interfaces and dependency injection so behavior can be swapped without modifying existing code.
- **Liskov Substitution**: If you define a contract (interface), every implementation must honor it fully. No surprise exceptions or missing behavior.
- **Interface Segregation**: Don't force classes to implement methods they don't use. Prefer small, focused interfaces over large, general ones.
- **Dependency Inversion**: Depend on abstractions, not concretions. Inject dependencies; don't hard-code them.

The key insight: SOLID isn't about following rules mechanically. It's about writing code that's easy to change later because responsibilities are cleanly separated.

---

## Code Quality Standards

### Naming Conventions

Names should be self-documenting:

```php
// Poor — what does this do?
$d = $this->calc($u, $p);

// Professional — intent is immediately clear
$netSalary = $this->payrollCalculator->computeNetSalary($employee, $payPeriod);
```

- **Methods**: Verb phrases that describe the action (`calculateOvertime`, `syncAttendanceLogs`, `resolveScheduleConflict`)
- **Variables**: Noun phrases that describe the data (`$activeEmployees`, `$pendingApprovals`, `$monthlyDeductions`)
- **Boolean variables/methods**: Read as questions (`$isApproved`, `$hasOvertime`, `canSubmitTimesheet()`)
- **Constants**: SCREAMING_SNAKE_CASE with meaningful names (`MAX_OVERTIME_HOURS`, `DEFAULT_TAX_BRACKET`)

### Method Design

Keep methods focused and predictable:

- **Single exit point preferred** — but early returns for guard clauses are fine and encouraged
- **Maximum 20 lines** — if a method is longer, it's probably doing too much
- **No more than 3-4 parameters** — use a DTO or value object if you need more
- **No side effects in getters** — a method named `getUser()` should never modify state

**Example: Guard Clauses**
```php
public function processPayroll(Employee $employee, PayPeriod $period): PayrollResult
{
    if (!$employee->isActive()) {
        throw new InactiveEmployeeException($employee->id);
    }

    if ($period->isAlreadyProcessed()) {
        throw new DuplicatePayrollException($period->id);
    }

    if (!$this->attendanceService->hasCompleteRecords($employee, $period)) {
        throw new IncompleteAttendanceException($employee->id, $period->id);
    }

    return $this->computePayroll($employee, $period);
}
```

### Error Handling

Errors are not exceptional annoyances—they're part of the contract:

- **Use custom exception classes** — `PayrollAlreadyProcessedException` tells you more than `\Exception`
- **Include context in exceptions** — pass IDs, states, and relevant data so debugging doesn't require reproduction
- **Catch at the right level** — catch where you can actually handle the error meaningfully, not everywhere
- **Log with structure** — use context arrays, not string concatenation

```php
// Poor
Log::error("Something went wrong with user " . $id);

// Professional
Log::error('Payroll computation failed', [
    'employee_id' => $employee->id,
    'pay_period'  => $period->toArray(),
    'error'       => $e->getMessage(),
    'trace'       => $e->getTraceAsString(),
]);
```

---

## Laravel-Specific Patterns

### Controller Design

Controllers are thin HTTP adapters. They translate HTTP requests into service calls and service responses into HTTP responses. Nothing more.

```php
class PayrollController extends Controller
{
    public function __construct(
        private readonly PayrollService $payrollService
    ) {}

    public function store(StorePayrollRequest $request): JsonResponse
    {
        $result = $this->payrollService->processPayroll(
            employeeId: $request->validated('employee_id'),
            periodId: $request->validated('pay_period_id'),
        );

        return response()->json(
            new PayrollResource($result),
            Response::HTTP_CREATED
        );
    }
}
```

Key points:
- Inject services via constructor
- Use Form Request classes for validation (never validate in the controller)
- Return API Resources for consistent response formatting
- Use named arguments for clarity when calling services
- Use HTTP status code constants, not magic numbers

### Form Request Validation

Validation rules belong in dedicated Form Request classes:

```php
class StorePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('process-payroll');
    }

    public function rules(): array
    {
        return [
            'employee_id'  => ['required', 'exists:employees,id'],
            'pay_period_id' => ['required', 'exists:pay_periods,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.exists'  => 'The selected employee does not exist in the system.',
            'pay_period_id.exists' => 'The selected pay period is invalid.',
        ];
    }
}
```

### Service Layer

Business logic lives in service classes. This is where the real work happens:

```php
class PayrollService
{
    public function __construct(
        private readonly AttendanceRepository $attendanceRepo,
        private readonly DeductionCalculator $deductionCalc,
        private readonly PayrollRepository $payrollRepo,
    ) {}

    public function processPayroll(int $employeeId, int $periodId): Payroll
    {
        return DB::transaction(function () use ($employeeId, $periodId) {
            $attendance = $this->attendanceRepo->getForPeriod($employeeId, $periodId);
            $grossPay = $this->calculateGrossPay($attendance);
            $deductions = $this->deductionCalc->compute($employeeId, $grossPay);

            return $this->payrollRepo->create([
                'employee_id' => $employeeId,
                'period_id'   => $periodId,
                'gross_pay'   => $grossPay,
                'deductions'  => $deductions->total(),
                'net_pay'     => $grossPay - $deductions->total(),
            ]);
        });
    }
}
```

Key points:
- Wrap multi-step operations in database transactions
- Decompose complex logic into focused private methods
- Inject dependencies rather than using facades in service classes (testability)

### Eloquent Best Practices

- **Use query scopes** for reusable query conditions
- **Eager load relationships** to prevent N+1 queries
- **Use `$casts`** for type safety on model attributes
- **Define `$fillable` or `$guarded`** explicitly—never leave mass assignment unprotected
- **Use accessors and mutators** for computed or transformed attributes

```php
class Employee extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email',
        'department_id', 'position', 'hire_date',
        'hourly_rate', 'status',
    ];

    protected $casts = [
        'hire_date'   => 'date',
        'hourly_rate' => 'decimal:2',
        'status'      => EmployeeStatus::class,
    ];

    // Query Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', EmployeeStatus::Active);
    }

    public function scopeInDepartment(Builder $query, int $departmentId): Builder
    {
        return $query->where('department_id', $departmentId);
    }

    // Relationships
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    // Accessor
    protected function fullName(): Attribute
    {
        return Attribute::get(
            fn () => "{$this->first_name} {$this->last_name}"
        );
    }
}
```

---

## Database Design

### Migration Standards

Migrations should be precise, reversible, and well-indexed:

```php
public function up(): void
{
    Schema::create('payrolls', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
        $table->foreignId('pay_period_id')->constrained();
        $table->decimal('gross_pay', 12, 2);
        $table->decimal('total_deductions', 12, 2)->default(0);
        $table->decimal('net_pay', 12, 2);
        $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');
        $table->timestamp('approved_at')->nullable();
        $table->foreignId('approved_by')->nullable()->constrained('users');
        $table->timestamps();
        $table->softDeletes();

        // Composite index for common query pattern
        $table->index(['employee_id', 'pay_period_id']);
        $table->index(['status', 'created_at']);
    });
}
```

Key points:
- Use appropriate column types and precision (decimal for money, never float)
- Add foreign key constraints for referential integrity
- Add indexes for columns used in WHERE, ORDER BY, and JOIN clauses
- Use soft deletes for audit-sensitive tables
- Include audit columns (created_by, approved_by, etc.) where appropriate

### Query Optimization

- **Index the WHERE clause** — if you query by it, index it
- **Avoid SELECT \*** — select only the columns you need
- **Use chunking for large datasets** — `chunk()`, `chunkById()`, or `lazy()` for memory-efficient processing
- **Profile with EXPLAIN** — check query plans for full table scans
- **Use database-level aggregation** — SUM, COUNT, AVG belong in SQL, not in PHP loops

```php
// Poor — loads entire table into memory, processes in PHP
$total = Employee::all()->sum('hourly_rate');

// Professional — computed at the database level
$total = Employee::active()->sum('hourly_rate');

// Poor — N+1 query
$employees = Employee::all();
foreach ($employees as $employee) {
    echo $employee->department->name; // Separate query per employee
}

// Professional — eager loaded
$employees = Employee::with('department')->active()->get();
```

---

## API Design

### RESTful Conventions

- Use resource-based URLs: `/api/employees/{id}/payrolls`
- Use HTTP verbs correctly: GET reads, POST creates, PUT/PATCH updates, DELETE removes
- Return appropriate status codes: 201 for created, 204 for no content, 422 for validation errors
- Version your API: `/api/v1/employees`
- Use consistent response envelopes

### Response Formatting

Use API Resources for consistent, versionable response structures:

```php
class PayrollResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'employee'         => new EmployeeResource($this->whenLoaded('employee')),
            'gross_pay'        => $this->gross_pay,
            'total_deductions' => $this->total_deductions,
            'net_pay'          => $this->net_pay,
            'status'           => $this->status,
            'approved_at'      => $this->approved_at?->toIso8601String(),
            'created_at'       => $this->created_at->toIso8601String(),
        ];
    }
}
```

---

## Security Practices

Security is not optional and not a feature—it's a baseline:

- **Never trust user input** — validate and sanitize everything
- **Use parameterized queries** — Eloquent does this by default; never use raw `DB::raw()` with user input
- **Implement authorization** — use Laravel Policies and Gates, check permissions at every endpoint
- **Hash sensitive data** — passwords with bcrypt/argon2, API keys with SHA-256
- **Rate limit sensitive endpoints** — login, password reset, API endpoints
- **Use HTTPS everywhere** — force SSL in production
- **Set proper CORS headers** — don't use wildcard origins in production
- **Audit sensitive operations** — log who did what and when

```php
// Policy-based authorization
class PayrollPolicy
{
    public function approve(User $user, Payroll $payroll): bool
    {
        return $user->hasRole('payroll_admin')
            && $payroll->status === 'draft'
            && $payroll->employee_id !== $user->employee_id; // Can't approve own payroll
    }
}
```

---

## Performance and Caching

### Caching Strategy

Cache deliberately, not blindly:

```php
// Cache expensive computations with appropriate TTL
$dashboardStats = Cache::remember(
    "dashboard:stats:{$departmentId}",
    now()->addMinutes(15),
    fn () => $this->analyticsService->computeDepartmentStats($departmentId)
);

// Invalidate when underlying data changes
public function onPayrollApproved(PayrollApproved $event): void
{
    Cache::forget("dashboard:stats:{$event->payroll->employee->department_id}");
}
```

### Queue Heavy Work

Anything that takes more than a second should probably be queued:

- Email/notification sending
- Report generation
- Bulk data processing
- External API calls
- File processing

```php
// Dispatch to queue with retry logic
ProcessPayrollBatch::dispatch($batchId)
    ->onQueue('payroll')
    ->delay(now()->addSeconds(5))
    ->afterCommit();
```

---

## Testing Mindset

While not always writing tests directly, structure code to be testable:

- **Inject dependencies** — never use `new` for services inside other services
- **Keep side effects at the edges** — pure business logic in the middle, I/O at the boundaries
- **Use interfaces for external services** — makes mocking trivial
- **Return data, don't echo** — methods should return values that can be asserted against

---

## Reference Files

The `references/` directory contains deep-dive documentation. Consult the relevant file when working in that domain:

| File | When to Read |
|------|-------------|
| `references/laravel-patterns.md` | Implementing repository pattern, service layer, DTOs, events, API versioning, queue jobs, or writing tests |
| `references/database-design.md` | Designing migrations, choosing column types, adding indexes, optimizing queries, or auditing schema decisions |
| `references/security-checklist.md` | Implementing authentication, authorization policies, input validation, rate limiting, or hardening an endpoint |

---

## Constraints

- Never produce beginner-level code unless the user explicitly requests simplified examples
- Never use `float` for monetary values—always `decimal` with explicit precision
- Never skip input validation, even for "internal" endpoints
- Never store passwords or secrets in plaintext
- Never commit `.env` files or credentials to version control
- Always wrap multi-table writes in transactions
- Always add database indexes for queried columns
- Always use meaningful HTTP status codes, not just 200 for everything
