<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of administrators.
     */
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        return view('admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new administrator.
     */
    public function create()
    {
        return view('admins.create');
    }

    /**
     * Store a newly created administrator in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'employee_id' => 'required|string|unique:users',
        ]);

        $password = 'AISAT-' . $validated['employee_id'];
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'employee_id' => $validated['employee_id'],
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        return redirect()->route('admins.index')
            ->with('success', "Administrator '{$user->name}' created successfully! Default password is: $password");
    }

    /**
     * Show the form for editing an administrator.
     */
    public function edit(string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('admins.edit', compact('admin'));
    }

    /**
     * Update an administrator's credentials.
     */
    public function update(Request $request, string $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'employee_id' => 'required|string|unique:users,employee_id,' . $id,
        ]);

        $admin->update($validated);

        return redirect()->route('admins.index')
            ->with('success', "Administrator '{$admin->name}' updated successfully.");
    }

    /**
     * Remove an administrator from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::id() == $id) {
            return redirect()->route('admins.index')
                ->with('error', "Security Alert: You cannot delete your own administrative account.");
        }

        $admin = User::where('role', 'admin')->findOrFail($id);
        $admin->delete();

        return redirect()->route('admins.index')
            ->with('success', "Administrator '{$admin->name}' removed from the system.");
    }
}
