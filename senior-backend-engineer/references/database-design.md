# Database Design Reference

Production-grade database design patterns for MySQL/PostgreSQL with Laravel.

## Table of Contents

1. [Column Type Selection](#column-type-selection)
2. [Indexing Strategy](#indexing-strategy)
3. [Normalization vs Denormalization](#normalization-vs-denormalization)
4. [Soft Deletes and Audit Trails](#soft-deletes-and-audit-trails)
5. [Migration Best Practices](#migration-best-practices)
6. [Query Optimization Checklist](#query-optimization-checklist)
7. [Common Anti-Patterns](#common-anti-patterns)

---

## Column Type Selection

Choose the most restrictive type that fits the data:

| Data Type | Use For | Never Use |
|-----------|---------|-----------|
| `decimal(12,2)` | Money, financial amounts | `float` or `double` for money |
| `unsignedBigInteger` | Foreign keys, IDs | `integer` (runs out at ~2.1B) |
| `enum` / `string` | Status fields with known values | `tinyInteger` with magic numbers |
| `timestamp` | Event times (created_at, approved_at) | `string` for dates |
| `date` | Calendar dates (hire_date, birth_date) | `timestamp` for date-only fields |
| `boolean` | True/false flags | `tinyInteger(1)` without cast |
| `json` | Flexible metadata, settings | Serialized PHP arrays |
| `text` | Long content, notes | `varchar(5000)` |
| `uuid` | Public-facing identifiers | Auto-increment IDs in URLs |

### Money Columns
```php
// Always use decimal with explicit precision
$table->decimal('gross_pay', 12, 2);        // Up to 9,999,999,999.99
$table->decimal('hourly_rate', 8, 2);       // Up to 999,999.99
$table->decimal('tax_percentage', 5, 2);    // Up to 999.99 (percent)
```

### Status Columns
```php
// Option A: String enum (flexible, readable in raw SQL)
$table->string('status', 20)->default('draft');

// Option B: Native enum (strict, but harder to migrate)
$table->enum('status', ['draft', 'approved', 'paid', 'cancelled'])->default('draft');

// Pair with a PHP Enum for type safety
enum PayrollStatus: string
{
    case Draft = 'draft';
    case Approved = 'approved';
    case Paid = 'paid';
    case Cancelled = 'cancelled';
}
```

---

## Indexing Strategy

### When to Add Indexes

1. **WHERE clause columns** — If you filter by it, index it
2. **JOIN columns** — Foreign keys (Laravel adds these automatically with `constrained()`)
3. **ORDER BY columns** — Especially on large tables
4. **Composite queries** — Use composite indexes for multi-column filters

### Composite Index Order

The **leftmost prefix rule**: a composite index on `(A, B, C)` supports queries filtering on:
- `A` alone ✅
- `A` and `B` ✅
- `A`, `B`, and `C` ✅
- `B` alone ❌ (can't skip A)
- `C` alone ❌

```php
// This index supports: WHERE employee_id = ? AND pay_period_id = ?
// Also supports: WHERE employee_id = ?
// Does NOT support: WHERE pay_period_id = ? (alone)
$table->index(['employee_id', 'pay_period_id']);

// For status filtering + date ordering on large tables
$table->index(['status', 'created_at']);
```

### When NOT to Index

- Columns with very low cardinality (e.g., boolean `is_active` on a small table)
- Tables with fewer than 1000 rows
- Columns that are rarely queried
- Write-heavy tables where index maintenance outweighs read benefit

---

## Normalization vs Denormalization

### Default: Normalize to 3NF

```
employees
├── id, first_name, last_name, email
├── department_id → departments.id
├── position_id → positions.id
└── hourly_rate, hire_date, status

departments
├── id, name, code
└── manager_id → employees.id

pay_periods
├── id, start_date, end_date
├── status
└── department_id → departments.id

payrolls
├── id
├── employee_id → employees.id
├── pay_period_id → pay_periods.id
├── gross_pay, total_deductions, net_pay
├── status, approved_at, approved_by
└── timestamps, soft_deletes
```

### When to Denormalize

Denormalize only when you have measured a performance problem:

```php
// Example: Store employee name snapshot on payroll
// Why: Payslips need the name AS IT WAS at time of payment
// (employee might change name or leave)
$table->string('employee_name_snapshot');
$table->decimal('hourly_rate_snapshot', 8, 2);
```

Other valid denormalization reasons:
- **Reporting tables** — Pre-aggregated data for dashboards
- **Search optimization** — Full-text search columns
- **Historical accuracy** — Snapshots of data at a point in time

---

## Soft Deletes and Audit Trails

### Soft Delete Strategy
```php
// Use soft deletes for:
// - Financial records (payrolls, transactions)
// - User accounts (may need to restore)
// - Any data with regulatory retention requirements

Schema::create('payrolls', function (Blueprint $table) {
    $table->softDeletes(); // Adds deleted_at column
});

// Be careful with unique constraints + soft deletes
// Use a partial unique index or include deleted_at
$table->unique(['employee_id', 'pay_period_id', 'deleted_at']);
```

### Audit Trail Pattern
```php
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id();
    $table->string('auditable_type');  // Polymorphic
    $table->unsignedBigInteger('auditable_id');
    $table->string('action');           // created, updated, deleted
    $table->foreignId('user_id')->nullable()->constrained();
    $table->json('old_values')->nullable();
    $table->json('new_values')->nullable();
    $table->string('ip_address', 45)->nullable();
    $table->string('user_agent')->nullable();
    $table->timestamp('created_at');

    $table->index(['auditable_type', 'auditable_id']);
    $table->index(['user_id', 'created_at']);
});
```

---

## Migration Best Practices

### Naming Convention
```
YYYY_MM_DD_HHMMSS_<action>_<table>_table.php

# Creating
2026_01_15_100000_create_employees_table.php
2026_01_15_100001_create_payrolls_table.php

# Altering
2026_02_01_143000_add_overtime_rate_to_employees_table.php
2026_02_05_090000_add_index_to_payrolls_status_column.php
```

### Safe Column Modifications
```php
// Adding a nullable column is always safe
$table->string('middle_name')->nullable()->after('first_name');

// Adding a column with default is safe
$table->boolean('is_exempt')->default(false)->after('status');

// Renaming columns — requires doctrine/dbal
$table->renameColumn('rate', 'hourly_rate');

// Changing column type — always provide down() migration
public function down(): void
{
    Schema::table('employees', function (Blueprint $table) {
        $table->integer('hourly_rate')->change(); // Revert
    });
}
```

### Zero-Downtime Migrations

For production systems with high traffic:

1. **Add new column** (nullable or with default) — no downtime
2. **Backfill data** — run in batches via artisan command
3. **Deploy code** that writes to both old and new columns
4. **Remove old column** — after confirming data integrity

---

## Query Optimization Checklist

Run through this checklist when queries are slow:

- [ ] **Check for N+1**: Use `->with()` eager loading
- [ ] **Check for SELECT ***: Select only needed columns
- [ ] **Check indexes**: Run `EXPLAIN` on slow queries
- [ ] **Check for full table scans**: Add WHERE clause indexes
- [ ] **Check for large IN()**: Consider joins or subqueries instead
- [ ] **Check for unnecessary sorting**: Remove ORDER BY if not needed
- [ ] **Check for aggregate in PHP**: Move SUM/COUNT/AVG to SQL
- [ ] **Check for chunking**: Use `chunk()` for processing large datasets
- [ ] **Check for caching**: Cache expensive, rarely-changing queries

### Using EXPLAIN
```sql
EXPLAIN SELECT * FROM payrolls
WHERE employee_id = 42
AND status = 'approved'
ORDER BY created_at DESC;

-- Look for:
-- type: ALL (bad — full table scan)
-- type: ref or const (good — using index)
-- rows: how many rows examined
-- Extra: "Using filesort" (may need index on ORDER BY column)
```

---

## Common Anti-Patterns

### 1. Float for Money
```php
// WRONG — floating point arithmetic is imprecise
$table->float('salary'); // 0.1 + 0.2 = 0.30000000000000004

// RIGHT
$table->decimal('salary', 12, 2);
```

### 2. Missing Foreign Keys
```php
// WRONG — no referential integrity
$table->unsignedBigInteger('employee_id');

// RIGHT — database enforces the relationship
$table->foreignId('employee_id')->constrained()->cascadeOnDelete();
```

### 3. God Table
```php
// WRONG — one table with 40+ columns for everything
Schema::create('records', function (Blueprint $table) {
    $table->string('type'); // "employee", "payroll", "attendance"
    $table->string('field1');
    $table->string('field2');
    // ... field40
});

// RIGHT — separate tables per domain entity
```

### 4. Storing Calculated Values Without Source Data
```php
// WRONG — only storing the result
$table->decimal('net_pay', 12, 2);

// RIGHT — store inputs so calculations can be verified/recomputed
$table->decimal('gross_pay', 12, 2);
$table->decimal('total_deductions', 12, 2);
$table->decimal('net_pay', 12, 2);
// Also store individual deduction line items in a related table
```

### 5. Polymorphic Overuse
```php
// Be cautious with polymorphic relationships for core business data
// They prevent foreign key constraints and make queries harder to optimize
// Use them for genuinely polymorphic concerns: comments, media, activity logs
// Don't use them to avoid creating proper relationship tables
```
