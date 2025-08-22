<?php

namespace App\Http\Controllers\Auth;

use App\Constants\UserRoles;
use App\Http\Controllers\Controller;
use App\Mail\ActiveUserMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

            // Redirect to role-specific dashboard
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
        Mail::to($user->email)->send(new ActiveUserMail($user));

        return redirect()->route('login')->with('success', 'Please verify the email.');

    }

    public function logout()
    {
        auth::check() ? auth::logout() : '';

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
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (Auth::user()->id != $id) {
            return redirect()->route('profile')->withErrors(['message' => 'You are not authorized.']);
        }

        $user = User::findOrFail($id);

        if (! Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

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
