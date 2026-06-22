<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of administrators and moderators.
     */
    public function index()
    {
        $admins = User::whereIn('role', ['admin', 'moderator'])
                      ->orderByRaw("CASE role WHEN 'admin' THEN 0 ELSE 1 END")
                      ->paginate(10);

        return view('admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new administrator or moderator.
     */
    public function create()
    {
        return view('admins.create');
    }

    /**
     * Store a newly created administrator or moderator in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users',
            'employee_id' => 'required|string|unique:users',
            'role'        => 'required|in:admin,moderator',
        ]);

        $password = 'AISAT-' . $validated['employee_id'];

        $user = User::create([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'employee_id' => $validated['employee_id'],
            'password'    => Hash::make($password),
            'role'        => $validated['role'],
        ]);

        $label = $user->role === 'admin' ? 'Administrator' : 'Moderator';

        return redirect()->route('admins.index')
            ->with('success', "{$label} '{$user->name}' created successfully! Default password is: {$password}");
    }

    /**
     * Show the form for editing an administrator or moderator.
     */
    public function edit(string $id)
    {
        $admin = User::whereIn('role', ['admin', 'moderator'])->findOrFail($id);
        return view('admins.edit', compact('admin'));
    }

    /**
     * Update an administrator's or moderator's credentials and role.
     */
    public function update(Request $request, string $id)
    {
        $admin = User::whereIn('role', ['admin', 'moderator'])->findOrFail($id);

        $isSelf = Auth::id() == $id;

        $rules = [
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users,email,' . $id,
            'employee_id' => 'required|string|unique:users,employee_id,' . $id,
        ];

        // Prevent the currently logged-in admin from changing their own role
        if (!$isSelf) {
            $rules['role'] = 'required|in:admin,moderator';
        }

        $validated = $request->validate($rules);

        $payload = [
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'employee_id' => $validated['employee_id'],
        ];

        if (!$isSelf && isset($validated['role'])) {
            $payload['role'] = $validated['role'];
        }

        $admin->update($payload);

        return redirect()->route('admins.index')
            ->with('success', "Account '{$admin->name}' updated successfully.");
    }

    /**
     * Remove an administrator or moderator from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::id() == $id) {
            return redirect()->route('admins.index')
                ->with('error', 'Security Alert: You cannot delete your own account.');
        }

        $admin = User::whereIn('role', ['admin', 'moderator'])->findOrFail($id);
        $label = $admin->role === 'admin' ? 'Administrator' : 'Moderator';
        $name  = $admin->name;
        $admin->delete();

        return redirect()->route('admins.index')
            ->with('success', "{$label} '{$name}' removed from the system.");
    }
}
