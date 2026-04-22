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
     * SSS Contribution (2025 Rates: 14.5% Total)
     * Employee: 4.5%
     * Employer: 9.5% + 0.5% EC = 10%
     */
    public function calculateSSS(float $grossPay): array
    {
        if ($grossPay <= 0) return ['ee' => 0, 'er' => 0, 'ec' => 0];
        
        $eeRate = SystemSetting::get('sss_ee_rate', 0.045); 
        $erRate = SystemSetting::get('sss_er_rate', 0.095);
        $ecAmount = ($grossPay >= 14750) ? 30 : 10; // Simple EC approximation
        
        $maxEe = SystemSetting::get('sss_ee_max', 1350); 
        $maxEr = SystemSetting::get('sss_er_max', 2850);
        
        $eeContribution = min($maxEe, $grossPay * $eeRate);
        $erContribution = min($maxEr, $grossPay * $erRate);
        
        return [
            'ee' => round($eeContribution, 2),
            'er' => round($erContribution, 2),
            'ec' => round($ecAmount, 2)
        ];
    }

    /**
     * PhilHealth Contribution (2025 Rate: 5% Total)
     * Employee: 2.5%
     * Employer: 2.5%
     */
    public function calculatePhilHealth(float $grossPay): array
    {
        if ($grossPay <= 0) return ['ee' => 0, 'er' => 0];
        
        $totalRate = SystemSetting::get('philhealth_total_rate', 0.05); 
        $minPay = 10000;
        $maxPay = 100000;
        
        $basis = min(max($grossPay, $minPay), $maxPay);
        $totalContribution = $basis * $totalRate;
        $share = $totalContribution / 2;
        
        return [
            'ee' => round($share, 2),
            'er' => round($share, 2)
        ];
    }

    /**
     * Pag-IBIG Contribution (2025 Rate: 400 Total)
     * Employee: 200
     * Employer: 200
     */
    public function calculatePagIBIG(float $grossPay): array
    {
        if ($grossPay <= 0) return ['ee' => 0, 'er' => 0];
        
        $eeFixed = SystemSetting::get('pagibig_ee_fixed', 200);
        $erFixed = SystemSetting::get('pagibig_er_fixed', 200);
        
        // Threshold check (standard is 1500 for full contribution)
        if ($grossPay < 1500) {
            $eeFixed = $eeFixed / 2;
            $erFixed = $erFixed / 2;
        }
        
        return [
            'ee' => (float)$eeFixed,
            'er' => (float)$erFixed
        ];
    }

    /**
     * Withholding Tax (WHT) based on 2023-present TRAIN Law Monthly Brackets
     */
    public function calculateTax(float $grossPay, float $statutoryTotal): float
    {
        $taxableIncome = $grossPay - $statutoryTotal;
        
        if ($taxableIncome <= 20833) {
            return 0;
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

        if ($taxableIncome <= 666667) {
            return 33541.67 + ($taxableIncome - 166667) * 0.30;
        }

        return 183541.67 + ($taxableIncome - 666667) * 0.35;
    }

    /**
     * Wrapper to get all deductions in one pass
     */
    public function computeAll(float $grossPay): array
    {
        $sss = $this->calculateSSS($grossPay);
        $ph = $this->calculatePhilHealth($grossPay);
        $pi = $this->calculatePagIBIG($grossPay);
        
        $statTotalEE = $sss['ee'] + $ph['ee'] + $pi['ee'];
        $tax = $this->calculateTax($grossPay, $statTotalEE);
        
        return [
            'sss' => $sss['ee'],
            'sss_er' => $sss['er'],
            'sss_ec' => $sss['ec'],
            'philhealth' => $ph['ee'],
            'philhealth_er' => $ph['er'],
            'pagibig' => $pi['ee'],
            'pagibig_er' => $pi['er'],
            'tax' => round($tax, 2),
            'total_ee' => round($statTotalEE + $tax, 2),
            'total_er' => round($sss['er'] + $sss['ec'] + $ph['er'] + $pi['er'], 2)
        ];
    }
}
