<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $this->authorize('read_users');

        $users = User::with('roles')->latest()->get();
        $roles = Role::whereIn('name', ['admin', 'deo'])->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        try {
            // Validate input first
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'nullable|string|max:15',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:admin,deo',
                'is_active' => 'sometimes|boolean',
            ], [
                'first_name.required' => 'First name is required.',
                'last_name.required' => 'Last name is required.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email address is already taken.',
                'password.required' => 'Password is required.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Password confirmation does not match.',
                'role.required' => 'Role is required.',
                'role.in' => 'Invalid role selected.',
            ]);

            // Create user
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'] ?? null,
                'password' => Hash::make($validated['password']),
                'is_active' => $request->has('is_active') ? (bool) $validated['is_active'] : true,
                'email_verified_at' => now(),
            ]);

            // Assign role
            $user->assignRole($validated['role']);

            return response()->json([
                'success' => true,
                'message' => ucfirst($validated['role']).' user created successfully.',
                'user' => $user->load('roles'),
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action.',
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the user: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the specified user.
     */
    public function show(User $user)
    {
        try {
            $this->authorize('read_users');
            return response()->json([
                'success' => true,
                'user' => $user->load('roles'),
            ]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this user.',
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user data.',
            ], 500);
        }
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        try {
          
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$user->id,
                'phone_number' => 'nullable|string|max:15',
                'role' => 'required|in:admin,deo',
                'is_active' => 'boolean',
            ]);

            $user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'is_active' => $validated['is_active'] ?? $user->is_active,
            ]);

            // Update role if changed
            if (! $user->hasRole($validated['role'])) {
                $user->syncRoles([$validated['role']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'user' => $user->fresh()->load('roles'),
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user.',
            ], 500);
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        // Check permissions based on user role
        if ($user->hasRole('admin')) {
            $this->authorize('delete_admin');
        } elseif ($user->hasRole('deo')) {
            $this->authorize('delete_deo');
        }

        // Prevent deletion of super admin
        if ($user->hasRole('super_admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Super admin cannot be deleted.',
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
        ]);
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request, User $user)
    {
        try {
            // Check permissions based on user role
            if ($user->hasRole('admin')) {
                $this->authorize('update_admin');
            } elseif ($user->hasRole('deo')) {
                $this->authorize('update_deo');
            }

            $validated = $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully.',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while resetting the password.',
            ], 500);
        }
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        // Check permissions based on user role
        if ($user->hasRole('admin')) {
            $this->authorize('update_admin');
        } elseif ($user->hasRole('deo')) {
            $this->authorize('update_deo');
        }

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully.',
            'is_active' => $user->is_active,
        ]);
    }
}
