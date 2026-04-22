<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Granular Employee Deductions
            $table->decimal('late_deduction', 10, 2)->default(0)->after('late_minutes');
            $table->decimal('undertime_deduction', 10, 2)->default(0)->after('late_deduction');
            
            // Employer Shares (Liability Tracking)
            $table->decimal('sss_employer', 10, 2)->default(0)->after('tax_deduction');
            $table->decimal('philhealth_employer', 10, 2)->default(0)->after('sss_employer');
            $table->decimal('pagibig_employer', 10, 2)->default(0)->after('philhealth_employer');
            $table->decimal('sss_ec', 10, 2)->default(0)->after('pagibig_employer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'late_deduction', 
                'undertime_deduction', 
                'sss_employer', 
                'philhealth_employer', 
                'pagibig_employer', 
                'sss_ec'
            ]);
        });
    }
};
