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

        return redirect()->route('settings.index')->with('success', 'System settings updated successfully.');
    }
}
