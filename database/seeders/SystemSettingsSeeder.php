<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Statutory Rates
            [
                'key' => 'sss_rate',
                'value' => '0.045',
                'type' => 'decimal',
                'label' => 'SSS Employee Rate',
                'group' => 'statutory',
            ],
            [
                'key' => 'sss_max_contribution',
                'value' => '1125',
                'type' => 'decimal',
                'label' => 'SSS Max Employee Contribution',
                'group' => 'statutory',
            ],
            [
                'key' => 'philhealth_rate',
                'value' => '0.02',
                'type' => 'decimal',
                'label' => 'PhilHealth Employee Rate',
                'group' => 'statutory',
            ],
            [
                'key' => 'philhealth_max_contribution',
                'value' => '1000',
                'type' => 'decimal',
                'label' => 'PhilHealth Max Contribution',
                'group' => 'statutory',
            ],
            [
                'key' => 'pagibig_fixed_amount',
                'value' => '200',
                'type' => 'decimal',
                'label' => 'Pag-IBIG Monthly Contribution',
                'group' => 'statutory',
            ],
            [
                'key' => 'pagibig_threshold',
                'value' => '1500',
                'type' => 'decimal',
                'label' => 'Pag-IBIG Salary Threshold',
                'group' => 'statutory',
            ],

            // Tax Brackets (Monthly)
            [
                'key' => 'tax_threshold_1',
                'value' => '20833',
                'type' => 'decimal',
                'label' => 'Tax Free Threshold (Monthly)',
                'group' => 'tax',
            ],
            [
                'key' => 'tax_rate_2',
                'value' => '0.15',
                'type' => 'decimal',
                'label' => 'Tax Rate Bracket 2 (15%)',
                'group' => 'tax',
            ],
            [
                'key' => 'tax_rate_3',
                'value' => '0.20',
                'type' => 'decimal',
                'label' => 'Tax Rate Bracket 3 (20%)',
                'group' => 'tax',
            ],

            // Institutional
            [
                'key' => 'currency_symbol',
                'value' => '₱',
                'type' => 'string',
                'label' => 'Currency Symbol',
                'group' => 'institutional',
            ],
            [
                'key' => 'institution_name',
                'value' => 'AISAT',
                'type' => 'string',
                'label' => 'Institution Name',
                'group' => 'institutional',
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
