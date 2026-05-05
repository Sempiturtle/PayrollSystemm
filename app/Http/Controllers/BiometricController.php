<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BiometricController extends Controller
{
    // ESP32 calls this after successful enrollment
    public function confirm(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'slot_id' => 'required|integer',
            'action_id' => 'required|string',
        ]);

        $pending = Cache::get('enroll_action_'.$request->action_id);
        if (! $pending) {
            return response()->json(['status' => 'invalid_action'], 422);
        }

        $user = User::findOrFail($request->user_id);

        // If another user already owns this slot — reject it
        $slotOwner = User::where('fingerprint_slot', $request->slot_id)
            ->where('id', '!=', $request->user_id)
            ->first();
        if ($slotOwner) {
            return response()->json(['status' => 'slot_taken'], 409);
        }

        $user->update([
            'fingerprint_slot' => $request->slot_id,
            'fingerprint_enrolled' => true,
            'fingerprint_enrolled_at' => now(),
        ]);

        Cache::put('enroll_action_'.$request->action_id, [
            'user_id' => $request->user_id,
            'status' => 'success',
        ], now()->addMinutes(2));

        Cache::forget('enroll_pending');

        return response()->json(['status' => 'enrolled']);
    }

    // ESP32 calls this if enrollment fails on the hardware side
    public function fail(Request $request)
    {
        $request->validate([
            'action_id' => 'required|string',
        ]);

        Cache::put('enroll_action_'.$request->action_id, [
            'status' => 'failed',
        ], now()->addMinutes(2));

        Cache::forget('enroll_pending');

        return response()->json(['status' => 'noted']);
    }
}
