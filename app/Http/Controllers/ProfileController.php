<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show');
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */ // This docblock tells the editor what type $user is.
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;

        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            // Store new photo in storage/app/public/profile-photos
            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->save(); // The red line under 'save' should now be gone.

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}
