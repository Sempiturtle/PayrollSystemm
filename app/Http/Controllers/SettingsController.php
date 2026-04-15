<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Display the settings management page.
     */
    public function index()
    {
        $settings = SystemSetting::all()->groupBy('group');
        return view('settings.index', compact('settings'));
    }

    /**
     * Update institutional and statutory settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'present',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $setting = SystemSetting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value]);
                Cache::forget("setting_{$key}");
            }
        }
        return redirect()->route('settings.index')->with('success', 'System settings updated successfully.');
    }

    /**
     * Store a new system setting parameter.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|unique:system_settings,key|alpha_dash',
            'label' => 'required',
            'value' => 'required',
            'type' => 'required|in:string,decimal,integer,boolean',
            'group' => 'required|in:statutory,tax,institutional',
        ]);

        SystemSetting::create($validated);
        
        return redirect()->route('settings.index')->with('success', 'New system parameter created successfully.');
    }

    /**
     * Populate standard default slots for easy manual configuration.
     */
    public function syncDefaults()
    {
        $defaults = [
            // Statutory
            ['key' => 'sss_rate', 'label' => 'SSS Employee Rate', 'value' => '0', 'group' => 'statutory', 'type' => 'decimal'],
            ['key' => 'sss_max_contribution', 'label' => 'SSS Max Employee Contribution', 'value' => '0', 'group' => 'statutory', 'type' => 'decimal'],
            ['key' => 'philhealth_rate', 'label' => 'PhilHealth Employee Rate', 'value' => '0', 'group' => 'statutory', 'type' => 'decimal'],
            ['key' => 'philhealth_max_contribution', 'label' => 'PhilHealth Max Contribution', 'value' => '0', 'group' => 'statutory', 'type' => 'decimal'],
            ['key' => 'pagibig_fixed_amount', 'label' => 'Pag-IBIG Monthly Contribution', 'value' => '0', 'group' => 'statutory', 'type' => 'decimal'],
            ['key' => 'pagibig_threshold', 'label' => 'Pag-IBIG Salary Threshold', 'value' => '0', 'group' => 'statutory', 'type' => 'decimal'],
            
            // Tax
            ['key' => 'tax_threshold_1', 'label' => 'Tax Free Threshold (Monthly)', 'value' => '0', 'group' => 'tax', 'type' => 'decimal'],
            ['key' => 'tax_rate_2', 'label' => 'Tax Rate Bracket 2 (15%)', 'value' => '0', 'group' => 'tax', 'type' => 'decimal'],
            ['key' => 'tax_rate_3', 'label' => 'Tax Rate Bracket 3 (20%)', 'value' => '0', 'group' => 'tax', 'type' => 'decimal'],
            
            // Institutional
            ['key' => 'currency_symbol', 'label' => 'Currency Symbol', 'value' => '₱', 'group' => 'institutional', 'type' => 'string'],
            ['key' => 'institution_name', 'label' => 'Institution Name', 'value' => 'AISAT', 'group' => 'institutional', 'type' => 'string'],
        ];

        foreach ($defaults as $data) {
            SystemSetting::firstOrCreate(['key' => $data['key']], $data);
        }

        return redirect()->route('settings.index')->with('success', 'Standard parameter slots loaded successfully! You can now edit the values.');
    }
}
