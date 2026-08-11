<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Jobs\UserSignupJob;
use App\Constants\UserRoles;
use App\Mail\ActiveUserMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginAction(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required',
            'email' => 'required',
        ]);

        if (Auth::attempt($validated, ! empty($request->remember_me))) {
            $user = Auth::user();
            if (! $user->is_active) {
                return redirect()->back()->with(['error' => 'Please verify your email to login'])->withInput($request->only('email'));
            }
            return $this->redirectToRoleDashboard($user);
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput($request->only('email'));
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerUser(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|min:6|max:12',
            'password' => 'required|confirmed|min:8',
        ]);
        $user = User::create($validated);

        UserSignupJob::dispatch($user);
        return redirect()->route('login')->with('success', 'Please verify the email.');

    }

    public function logout()
    {
        session()->forget(['impersonator_id', 'impersonator_name']);

        if (Auth::check()) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        return redirect()->route('home');
    }

    /**
     * Redirect user to role-specific dashboard
     */
    private function redirectToRoleDashboard($user)
    {
        $dashboardRoutes = UserRoles::getDashboardRoutes();

        foreach ($dashboardRoutes as $role => $routeName) {
            if ($user->hasRole($role)) {
                return redirect()->route($routeName);
            }
        }

        // Default fallback
        return redirect()->route('home');
    }

    public function updatePassword(Request $request, string $id)
    {
        if ((int) Auth::id() !== (int) $id) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'You are not authorized.'], 403);
            }

            return redirect()->route('profile')->withErrors(['message' => 'You are not authorized.']);
        }

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::findOrFail($id);

        if (! Hash::check($validated['current_password'], (string) $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Password updated successfully.',
            ]);
        }

        return redirect()->back()->with('status', 'Password updated successfully.');
    }

    public function verifyUser(string $id)
    {
        $user = User::find($id);
        $user->update([
            'is_active' => true,
        ]);

        return redirect()->route('login')->with(['success' => 'Email verified successfully']);
    }
}
