<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('role', 'staff')->latest()->paginate(10);
        return response()->json($staff);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'permissions' => 'nullable|array',
        ]);
        $staff = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'permissions' => $request->permissions ?? [],
            'status' => 'active',
        ]);
        return response()->json([
            'message' => 'Staff created successfully.',
            'staff' => $staff
        ], 201);
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'permissions' => 'nullable|array',
            'status' => 'nullable|in:active,deactivated',
        ]);
        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'permissions' => $request->permissions ?? [],
            'status' => $request->status ?? $staff->status,
        ]);
        return response()->json([
            'message' => 'Staff updated successfully.',
            'staff' => $staff
        ]);
    }

    public function destroy(User $staff)
    {
        $staff->delete();
        return response()->json([
            'message' => 'Staff deleted successfully.'
        ]);
    }
}
