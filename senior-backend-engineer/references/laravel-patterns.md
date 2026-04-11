# Laravel Backend Patterns Reference

Quick-reference guide for senior-level Laravel patterns. Read relevant sections when implementing specific features.

## Table of Contents

1. [Repository Pattern](#repository-pattern)
2. [Service Layer Pattern](#service-layer-pattern)
3. [Action Classes](#action-classes)
4. [DTOs and Value Objects](#dtos-and-value-objects)
5. [Event-Driven Architecture](#event-driven-architecture)
6. [API Versioning](#api-versioning)
7. [Rate Limiting](#rate-limiting)
8. [Caching Strategies](#caching-strategies)
9. [Queue Architecture](#queue-architecture)
10. [Testing Patterns](#testing-patterns)

---

## Repository Pattern

Use repositories to abstract database access from business logic. This decouples your services from Eloquent and makes testing straightforward.

```php
// Interface
interface EmployeeRepositoryInterface
{
    public function findById(int $id): ?Employee;
    public function getActiveByDepartment(int $departmentId): Collection;
    public function create(array $data): Employee;
    public function update(int $id, array $data): Employee;
}

// Implementation
class EloquentEmployeeRepository implements EmployeeRepositoryInterface
{
    public function __construct(private readonly Employee $model) {}

    public function findById(int $id): ?Employee
    {
        return $this->model->find($id);
    }

    public function getActiveByDepartment(int $departmentId): Collection
    {
        return $this->model
            ->active()
            ->inDepartment($departmentId)
            ->with(['department', 'position'])
            ->orderBy('last_name')
            ->get();
    }
}

// Service Provider binding
$this->app->bind(
    EmployeeRepositoryInterface::class,
    EloquentEmployeeRepository::class
);
```

**When to use:** When your service layer needs to be testable without hitting the database, or when you might swap data sources (e.g., from MySQL to an API).

**When to skip:** For simple CRUD operations where the overhead isn't justified. Use Eloquent directly in controllers for read-only pages.

---

## Service Layer Pattern

Services encapsulate business logic. They orchestrate repositories, external APIs, and domain rules.

```php
class PayrollProcessingService
{
    public function __construct(
        private readonly EmployeeRepositoryInterface $employees,
        private readonly AttendanceRepositoryInterface $attendance,
        private readonly TaxCalculator $taxCalculator,
        private readonly PayrollRepositoryInterface $payrolls,
    ) {}

    public function processForPeriod(PayPeriod $period): PayrollBatch
    {
        $employees = $this->employees->getActiveByDepartment($period->department_id);

        return DB::transaction(function () use ($employees, $period) {
            $batch = PayrollBatch::create(['period_id' => $period->id, 'status' => 'processing']);

            foreach ($employees as $employee) {
                $this->processEmployee($employee, $period, $batch);
            }

            $batch->update(['status' => 'completed']);
            return $batch->fresh();
        });
    }

    private function processEmployee(Employee $employee, PayPeriod $period, PayrollBatch $batch): Payroll
    {
        $attendanceRecords = $this->attendance->getForPeriod($employee->id, $period);
        $grossPay = $this->calculateGross($employee, $attendanceRecords);
        $deductions = $this->taxCalculator->compute($employee, $grossPay);

        return $this->payrolls->create([
            'batch_id'     => $batch->id,
            'employee_id'  => $employee->id,
            'gross_pay'    => $grossPay,
            'deductions'   => $deductions->total(),
            'net_pay'      => $grossPay - $deductions->total(),
        ]);
    }
}
```

---

## Action Classes

For one-off operations that don't fit neatly into a service class, use Action classes. Each action does exactly one thing.

```php
class ApprovePayrollAction
{
    public function __construct(
        private readonly NotificationService $notifications,
    ) {}

    public function execute(Payroll $payroll, User $approver): Payroll
    {
        if ($payroll->status !== PayrollStatus::Draft) {
            throw new InvalidPayrollStateException(
                "Cannot approve payroll in '{$payroll->status->value}' state"
            );
        }

        if ($payroll->employee_id === $approver->employee_id) {
            throw new SelfApprovalException('Cannot approve own payroll');
        }

        $payroll->update([
            'status'      => PayrollStatus::Approved,
            'approved_at' => now(),
            'approved_by' => $approver->id,
        ]);

        $this->notifications->send(
            $payroll->employee,
            new PayrollApprovedNotification($payroll)
        );

        return $payroll->fresh();
    }
}
```

**When to use:** Single-purpose operations triggered by user actions (approve, reject, import, export).

---

## DTOs and Value Objects

Use Data Transfer Objects to pass structured data between layers without coupling to Eloquent models.

```php
// Using readonly classes (PHP 8.2+)
readonly class PayrollCalculationResult
{
    public function __construct(
        public float $grossPay,
        public float $totalDeductions,
        public float $netPay,
        public DeductionBreakdown $breakdown,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            grossPay: $data['gross_pay'],
            totalDeductions: $data['total_deductions'],
            netPay: $data['net_pay'],
            breakdown: DeductionBreakdown::fromArray($data['breakdown']),
        );
    }
}

// Value Object for monetary amounts
readonly class Money
{
    public function __construct(
        public int $cents, // Store as cents to avoid floating point issues
        public string $currency = 'PHP',
    ) {}

    public static function fromDecimal(float $amount, string $currency = 'PHP'): self
    {
        return new self(cents: (int) round($amount * 100), currency: $currency);
    }

    public function toDecimal(): float
    {
        return $this->cents / 100;
    }

    public function add(Money $other): self
    {
        return new self($this->cents + $other->cents, $this->currency);
    }

    public function subtract(Money $other): self
    {
        return new self($this->cents - $other->cents, $this->currency);
    }
}
```

---

## Event-Driven Architecture

Use Laravel Events to decouple side effects from core business logic.

```php
// Event
class PayrollApproved
{
    public function __construct(
        public readonly Payroll $payroll,
        public readonly User $approver,
    ) {}
}

// Listeners (registered in EventServiceProvider)
class SendPayrollApprovalNotification
{
    public function handle(PayrollApproved $event): void
    {
        $event->payroll->employee->notify(
            new PayrollApprovedNotification($event->payroll)
        );
    }
}

class UpdatePayrollDashboardCache
{
    public function handle(PayrollApproved $event): void
    {
        Cache::tags(['payroll', 'dashboard'])->flush();
    }
}

class LogPayrollApproval
{
    public function handle(PayrollApproved $event): void
    {
        AuditLog::create([
            'action'    => 'payroll_approved',
            'target_id' => $event->payroll->id,
            'user_id'   => $event->approver->id,
            'metadata'  => ['net_pay' => $event->payroll->net_pay],
        ]);
    }
}
```

**Why events:** Adding new behavior (audit logs, notifications, cache invalidation) never requires modifying the core service. Just register a new listener.

---

## API Versioning

Structure API routes and controllers by version:

```
routes/
├── api_v1.php
└── api_v2.php

app/Http/Controllers/
├── Api/V1/
│   ├── EmployeeController.php
│   └── PayrollController.php
└── Api/V2/
    ├── EmployeeController.php
    └── PayrollController.php
```

```php
// RouteServiceProvider
Route::prefix('api/v1')
    ->middleware(['api', 'auth:sanctum'])
    ->group(base_path('routes/api_v1.php'));

Route::prefix('api/v2')
    ->middleware(['api', 'auth:sanctum'])
    ->group(base_path('routes/api_v2.php'));
```

---

## Rate Limiting

Configure per-endpoint rate limits for security-critical routes:

```php
// AppServiceProvider or RouteServiceProvider
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

RateLimiter::for('payroll-export', function (Request $request) {
    return Limit::perHour(10)->by($request->user()->id);
});
```

---

## Caching Strategies

### Cache-Aside Pattern
```php
$employees = Cache::remember(
    "department:{$deptId}:employees",
    now()->addMinutes(30),
    fn () => Employee::active()->inDepartment($deptId)->get()
);
```

### Write-Through Invalidation
```php
// In an observer or event listener
class EmployeeObserver
{
    public function saved(Employee $employee): void
    {
        Cache::forget("department:{$employee->department_id}:employees");
        Cache::forget("employee:{$employee->id}:profile");
    }
}
```

### Tagged Caching (Redis only)
```php
Cache::tags(['payroll', "dept:{$deptId}"])->remember(
    "payroll:summary:{$deptId}:{$periodId}",
    now()->addHours(1),
    fn () => $this->computeSummary($deptId, $periodId)
);

// Flush all payroll caches at once
Cache::tags(['payroll'])->flush();
```

---

## Queue Architecture

### Job Design
```php
class ProcessPayrollBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60; // seconds between retries
    public int $timeout = 300; // 5 minute max

    public function __construct(
        private readonly int $batchId,
    ) {}

    public function handle(PayrollProcessingService $service): void
    {
        $batch = PayrollBatch::findOrFail($this->batchId);
        $service->processForPeriod($batch->period);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Payroll batch processing failed', [
            'batch_id' => $this->batchId,
            'error'    => $exception->getMessage(),
        ]);

        // Notify admin
        Notification::route('mail', config('payroll.admin_email'))
            ->notify(new PayrollProcessingFailedNotification($this->batchId, $exception));
    }
}
```

### Queue Selection
```php
// Prioritize queues by importance
ProcessPayrollBatch::dispatch($batchId)->onQueue('payroll');
SendPayslipEmail::dispatch($payrollId)->onQueue('notifications');
GenerateMonthlyReport::dispatch($month)->onQueue('reports');
```

---

## Testing Patterns

### Feature Test Structure
```php
class PayrollApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_payroll_for_active_employee(): void
    {
        $admin = User::factory()->admin()->create();
        $employee = Employee::factory()->active()->create();
        $period = PayPeriod::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/payrolls', [
                'employee_id'  => $employee->id,
                'pay_period_id' => $period->id,
            ]);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => ['id', 'gross_pay', 'net_pay', 'status'],
            ]);

        $this->assertDatabaseHas('payrolls', [
            'employee_id' => $employee->id,
            'status'      => 'draft',
        ]);
    }

    public function test_cannot_create_payroll_for_inactive_employee(): void
    {
        $admin = User::factory()->admin()->create();
        $employee = Employee::factory()->inactive()->create();
        $period = PayPeriod::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/v1/payrolls', [
                'employee_id'  => $employee->id,
                'pay_period_id' => $period->id,
            ]);

        $response->assertUnprocessable();
    }
}
```

### Unit Test for Service
```php
class PayrollServiceTest extends TestCase
{
    public function test_calculates_net_pay_correctly(): void
    {
        $mockAttendance = Mockery::mock(AttendanceRepositoryInterface::class);
        $mockAttendance->shouldReceive('getForPeriod')->andReturn(collect([
            new AttendanceRecord(hoursWorked: 160, overtimeHours: 8),
        ]));

        $service = new PayrollProcessingService(
            employees: Mockery::mock(EmployeeRepositoryInterface::class),
            attendance: $mockAttendance,
            taxCalculator: new TaxCalculator(),
            payrolls: Mockery::mock(PayrollRepositoryInterface::class),
        );

        $result = $service->calculateGross($employee, $attendanceRecords);

        $this->assertEquals(25000.00, $result);
    }
}
```
