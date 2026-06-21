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
            $table->decimal('overtime_hours', 8, 2)->default(0)->after('total_hours');
            $table->decimal('overtime_pay', 10, 2)->default(0)->after('overtime_hours');
            $table->decimal('absence_deduction', 10, 2)->default(0)->after('undertime_deduction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['overtime_hours', 'overtime_pay', 'absence_deduction']);
        });
    }
};
