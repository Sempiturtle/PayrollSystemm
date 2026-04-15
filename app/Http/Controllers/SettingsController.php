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
            'settings.*' => 'required',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $setting = SystemSetting::where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value]);
                Cache::forget("setting_{$key}");
            }
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
}
