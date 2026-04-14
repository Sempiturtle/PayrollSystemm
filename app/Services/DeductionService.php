<?php

namespace App\Services;

use App\Models\SystemSetting;

/**
 * DeductionService handles the computation of Philippine statutory contributions and taxes.
 * Based on current Phil-Gov standards (Dynamic via SystemSettings).
 */
class DeductionService
{
    /**
     * SSS Contribution
     */
    public function calculateSSS(float $grossPay): float
    {
        if ($grossPay <= 0) return 0;
        
        $sssRate = SystemSetting::get('sss_rate', 0.045); 
        $maxContribution = SystemSetting::get('sss_max_contribution', 1125);
        
        $contribution = $grossPay * $sssRate;
        
        return min($maxContribution, $contribution);
    }

    /**
     * PhilHealth Contribution
     */
    public function calculatePhilHealth(float $grossPay): float
    {
        if ($grossPay <= 0) return 0;
        
        $phRate = SystemSetting::get('philhealth_rate', 0.02); 
        $maxContribution = SystemSetting::get('philhealth_max_contribution', 1000);
        
        $contribution = $grossPay * $phRate;
        
        return min($maxContribution, $contribution);
    }

    /**
     * Pag-IBIG Contribution
     */
    public function calculatePagIBIG(float $grossPay): float
    {
        if ($grossPay <= 0) return 0;
        
        $fixedAmount = SystemSetting::get('pagibig_fixed_amount', 200);
        $threshold = SystemSetting::get('pagibig_threshold', 1500);
        
        return ($grossPay > $threshold) ? (float)$fixedAmount : (float)($fixedAmount / 2);
    }

    /**
     * Withholding Tax (WHT) based on TRAIN Law Monthly Brackets
     */
    public function calculateTax(float $grossPay, float $statutoryTotal): float
    {
        $taxableIncome = $grossPay - $statutoryTotal;
        $threshold1 = SystemSetting::get('tax_threshold_1', 20833);
        
        if ($taxableIncome <= $threshold1) {
            return 0; // Below threshold
        }

        $rate2 = SystemSetting::get('tax_rate_2', 0.15);
        if ($taxableIncome <= 33333) {
            return ($taxableIncome - $threshold1) * $rate2;
        }

        $rate3 = SystemSetting::get('tax_rate_3', 0.20);
        if ($taxableIncome <= 66667) {
            return 1875 + ($taxableIncome - 33333) * $rate3;
        }

        if ($taxableIncome <= 166667) {
            return 8541.67 + ($taxableIncome - 66667) * 0.25;
        }

        return 33541.67 + ($taxableIncome - 166667) * 0.30;
    }

    /**
     * Wrapper to get all deductions in one pass
     */
    public function computeAll(float $grossPay): array
    {
        $sss = $this->calculateSSS($grossPay);
        $ph = $this->calculatePhilHealth($grossPay);
        $pi = $this->calculatePagIBIG($grossPay);
        
        $statTotal = $sss + $ph + $pi;
        $tax = $this->calculateTax($grossPay, $statTotal);
        
        return [
            'sss' => round($sss, 2),
            'philhealth' => round($ph, 2),
            'pagibig' => round($pi, 2),
            'tax' => round($tax, 2),
            'total' => round($statTotal + $tax, 2)
        ];
    }
}
