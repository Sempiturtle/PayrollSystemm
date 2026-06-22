<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * ModeratorRoleTest
 *
 * Verifies the three-tier RBAC model:
 *   - admin       → full access (super-admin)
 *   - moderator   → operational access (admin + moderator middleware)
 *   - employee    → self-service only
 *
 * Route tiers (from web.php):
 *   - `admin`       middleware  → admin + moderator
 *   - `super_admin` middleware  → admin only
 */
class ModeratorRoleTest extends TestCase
{
    use RefreshDatabase;

    // ─── User Model Helper Tests ────────────────────────────────────────────────

    #[Test]
    public function admin_isAdmin_returns_true(): void
    {
        $admin = User::factory()->admin()->create();
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isModerator());
        $this->assertTrue($admin->isStaffOrAdmin());
    }

    #[Test]
    public function moderator_isModerator_returns_true(): void
    {
        $mod = User::factory()->moderator()->create();
        $this->assertFalse($mod->isAdmin());
        $this->assertTrue($mod->isModerator());
        $this->assertTrue($mod->isStaffOrAdmin());
    }

    #[Test]
    public function employee_has_no_staff_privileges(): void
    {
        $employee = User::factory()->employee()->create();
        $this->assertFalse($employee->isAdmin());
        $this->assertFalse($employee->isModerator());
        $this->assertFalse($employee->isStaffOrAdmin());
    }

    // ─── Moderator Route Access (admin middleware) ──────────────────────────────

    #[Test]
    public function moderator_can_access_employees_index(): void
    {
        $mod = User::factory()->moderator()->create();
        $response = $this->actingAs($mod)->get(route('employees.index'));
        $response->assertOk();
    }

    #[Test]
    public function moderator_can_access_schedules_index(): void
    {
        $mod = User::factory()->moderator()->create();
        $response = $this->actingAs($mod)->get(route('schedules.index'));
        $response->assertOk();
    }

    #[Test]
    public function moderator_can_access_attendance_monitoring(): void
    {
        $mod = User::factory()->moderator()->create();
        $response = $this->actingAs($mod)->get(route('attendance.index'));
        $response->assertOk();
    }

    #[Test]
    public function moderator_can_access_leaves_index(): void
    {
        $mod = User::factory()->moderator()->create();
        $response = $this->actingAs($mod)->get(route('leaves.index'));
        $response->assertOk();
    }

    #[Test]
    public function moderator_can_access_discrepancies_index(): void
    {
        $mod = User::factory()->moderator()->create();
        $response = $this->actingAs($mod)->get(route('admin.discrepancies.index'));
        $response->assertOk();
    }

    // ─── Super-Admin Route Restrictions (super_admin middleware) ────────────────

    #[Test]
    public function moderator_cannot_access_settings(): void
    {
        $mod = User::factory()->moderator()->create();
        $response = $this->actingAs($mod)->get(route('settings.index'));
        $response->assertForbidden();
    }

    #[Test]
    public function moderator_cannot_access_audit_logs(): void
    {
        $mod = User::factory()->moderator()->create();
        $response = $this->actingAs($mod)->get(route('audit-logs.index'));
        $response->assertForbidden();
    }

    #[Test]
    public function moderator_cannot_access_admin_management(): void
    {
        $mod = User::factory()->moderator()->create();
        $response = $this->actingAs($mod)->get(route('admins.index'));
        $response->assertForbidden();
    }

    #[Test]
    public function moderator_cannot_generate_payroll(): void
    {
        $mod = User::factory()->moderator()->create();
        $response = $this->actingAs($mod)->post(route('payrolls.generate'));
        $response->assertForbidden();
    }

    // ─── Employee Route Restrictions (admin middleware) ─────────────────────────

    #[Test]
    public function employee_cannot_access_employees_index(): void
    {
        $employee = User::factory()->employee()->create();
        $response = $this->actingAs($employee)->get(route('employees.index'));
        $response->assertForbidden();
    }

    #[Test]
    public function employee_cannot_access_attendance_monitoring(): void
    {
        $employee = User::factory()->employee()->create();
        $response = $this->actingAs($employee)->get(route('attendance.index'));
        $response->assertForbidden();
    }

    #[Test]
    public function employee_cannot_access_settings(): void
    {
        $employee = User::factory()->employee()->create();
        $response = $this->actingAs($employee)->get(route('settings.index'));
        $response->assertForbidden();
    }

    // ─── Admin Full Access ──────────────────────────────────────────────────────

    #[Test]
    public function admin_can_access_settings(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get(route('settings.index'));
        $response->assertOk();
    }

    #[Test]
    public function admin_can_access_audit_logs(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get(route('audit-logs.index'));
        $response->assertOk();
    }

    #[Test]
    public function admin_can_access_admin_management(): void
    {
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->get(route('admins.index'));
        $response->assertOk();
    }
}
