<?php

namespace App\Services;

/**
 * DeductionService handles the computation of Philippine statutory contributions and taxes.
 * Based on current Phil-Gov standards (simplified for institutional demonstration).
 */
class DeductionService
{
    /**
     * SSS Contribution (approx 4.5% employee share)
     */
    public function calculateSSS(float $grossPay): float
    {
        // Simple approximate calculation based on monthly salary credit
        // In production, this would use a lookup table.
        if ($grossPay <= 0) return 0;
        
        $sssRate = 0.045; 
        $contribution = $grossPay * $sssRate;
        
        return min(1125, $contribution); // Max cap for employee share
    }

    /**
     * PhilHealth Contribution (approx 2% employee share)
     */
    public function calculatePhilHealth(float $grossPay): float
    {
        if ($grossPay <= 0) return 0;
        
        $phRate = 0.02; 
        $contribution = $grossPay * $phRate;
        
        return min(1000, $contribution); // Max cap
    }

    /**
     * Pag-IBIG Contribution (usually fixed 100 or 200)
     */
    public function calculatePagIBIG(float $grossPay): float
    {
        if ($grossPay <= 0) return 0;
        
        // Fixed rate for salaries above 1,500
        return ($grossPay > 1500) ? 200.00 : 100.00;
    }

    /**
     * Withholding Tax (WHT) based on TRAIN Law Monthly Brackets
     */
    public function calculateTax(float $grossPay, float $statutoryTotal): float
    {
        $taxableIncome = $grossPay - $statutoryTotal;
        
        if ($taxableIncome <= 20833) {
            return 0; // Below threshold
        }

        if ($taxableIncome <= 33333) {
            return ($taxableIncome - 20833) * 0.15;
        }

        if ($taxableIncome <= 66667) {
            return 1875 + ($taxableIncome - 33333) * 0.20;
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
