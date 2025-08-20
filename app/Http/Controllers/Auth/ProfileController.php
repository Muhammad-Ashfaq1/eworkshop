<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;




class ProfileController extends Controller
{
    public function profile()
    {
        $this->authorize('read_profile');

        $user=Auth::user();
        if(!empty($user))
        {
            return view('auth.profile', compact('user'));
        }
        return redirect()->route('login')->withErrors(['message' => 'You must be logged']);
    }
    public function update(Request $request, $id)
    {
        $this->authorize('update_profile');

        $request->validate([
                    "first_name"=>"required|string",
                    "last_name"=>"sometimes|string",
                    "phone_number"=>"sometimes|min:6|max:12",
                ]);
        $user=User::findOrFail($id);
        if(!empty($user))
        {
            $user->update([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'email'=>$request->email,
                'phone_number'=>$request->phone_number]);
                return redirect()->back()->with('success','Profile updated successfully');
        }
        return redirect()->back()->withErrors(['message' => 'You are not authorized to update this profile']);
    }


}



