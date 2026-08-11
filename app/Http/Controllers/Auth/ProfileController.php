<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\FileUploadManager;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile()
    {
        $this->authorize('read_profile');

        $user = Auth::user();
        if (! empty($user)) {
            return view('auth.profile', compact('user'));
        }

        return redirect()->route('login')->withErrors(['message' => 'You must be logged']);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update_profile');

        $request->validate([
            'first_name' => 'required|string|max:75',
            'last_name' => 'required|string|max:75',
            'phone_number' => 'nullable|string|max:30',
            'image_url' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'first_name.required' => 'First name is required.',
            'first_name.max' => 'First name may not be greater than 75 characters.',
            'last_name.required' => 'Last name is required.',
            'last_name.max' => 'Last name may not be greater than 75 characters.',
            'phone_number.max' => 'Phone number may not be greater than 30 characters.',
        ]);

        $user = User::findOrFail($id);

        if (! empty($user)) {
            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number,
            ];

            // Handle image upload
            if ($request->hasFile('image_url')) {
                // Delete old image if exists
                if ($user->getRawOriginal('image_url')) {
                    $user->deleteImage();
                }

                $file = FileUploadManager::uploadFile($request->file('image_url'), 'users/profiles/');
                $data['image_url'] = $file['path'];
            }

            $user->update($data);

            return redirect()->back()->with('success', 'Profile updated successfully');
        }

        return redirect()->back()->withErrors(['message' => 'You are not authorized to update this profile']);
    }
}
