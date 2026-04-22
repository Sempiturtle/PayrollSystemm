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
            // Statutory - SSS 2025
            ['key' => 'sss_ee_rate', 'label' => 'SSS Employee Rate', 'value' => '0.045', 'group' => 'statutory', 'type' => 'decimal'],
            ['key' => 'sss_er_rate', 'label' => 'SSS Employer Rate', 'value' => '0.095', 'group' => 'statutory', 'type' => 'decimal'],
            ['key' => 'sss_ee_max', 'label' => 'SSS Max Employee Contribution', 'value' => '1350', 'group' => 'statutory', 'type' => 'decimal'],
            ['key' => 'sss_er_max', 'label' => 'SSS Max Employer Contribution', 'value' => '2850', 'group' => 'statutory', 'type' => 'decimal'],
            
            // Statutory - PhilHealth 2025
            ['key' => 'philhealth_total_rate', 'label' => 'PhilHealth Total Rate', 'value' => '0.05', 'group' => 'statutory', 'type' => 'decimal'],
            
            // Statutory - Pag-IBIG 2025
            ['key' => 'pagibig_ee_fixed', 'label' => 'Pag-IBIG Employee Fixed', 'value' => '200', 'group' => 'statutory', 'type' => 'decimal'],
            ['key' => 'pagibig_er_fixed', 'label' => 'Pag-IBIG Employer Fixed', 'value' => '200', 'group' => 'statutory', 'type' => 'decimal'],
            
            // Tax - TRAIN Law
            ['key' => 'tax_threshold_1', 'label' => 'Tax Free Threshold (Monthly)', 'value' => '20833', 'group' => 'tax', 'type' => 'decimal'],
            ['key' => 'tax_rate_2', 'label' => 'Tax Rate Bracket 2 (15%)', 'value' => '0.15', 'group' => 'tax', 'type' => 'decimal'],
            
            // Institutional
            ['key' => 'currency_symbol', 'label' => 'Currency Symbol', 'value' => '₱', 'group' => 'institutional', 'type' => 'string'],
            ['key' => 'institution_name', 'label' => 'Institution Name', 'value' => 'AISAT College', 'group' => 'institutional', 'type' => 'string'],
        ];

        foreach ($defaults as $data) {
            SystemSetting::firstOrCreate(['key' => $data['key']], $data);
        }

        return redirect()->route('settings.index')->with('success', 'Standard parameter slots loaded successfully! You can now edit the values.');
    }
}
