<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use dispatchable;
use App\Jobs\ForgetPasswordJob;

class PasswordController extends Controller
{
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPasswordLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['error' => 'Email not found'], 404);
        }

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        $currentTime = Carbon::now();
        $token = Str::random(60);
        $expiresAt = $currentTime->copy()->addSeconds(60); // Token expires in 1 hour

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => $currentTime,
            'expire_at' => $expiresAt,
        ]);

        try {
            ForgetPasswordJob::dispatch($user, $token);

            return response()->json(['success' => 'Password reset link sent to your email']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send email'], 500);
        }
    }

    public function verifyEmail($token, $email)
    {

        return view('auth.update-password', compact('token', 'email'));
    }

    public function resetPassword(Request $request)
    {
        $token = $request->token ?? '';
        $email = $request->email ?? '';
        $valid_token = DB::table('password_reset_tokens')->where('email', $email)->where('token', $token)->first();
        if (! empty($valid_token) && $valid_token->expire_at < Carbon::now()) {
            $user = User::where('email', $valid_token->email)->first();
            $user->password = $request->password;
            $user->save();
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect()->route('login')->with(['success' => 'Password Update Successfuly!']);
        }

        return redirect()->route('login')->with(['error', 'reset link is expired']);
    }
}
