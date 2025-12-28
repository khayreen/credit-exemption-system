<?php

namespace App\Http\Controllers;

use App\Models\ProgramChangeRequest;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $student = $user->student;

        // Get pending program change request if exists
        $pendingRequest = null;
        if ($student) {
            $pendingRequest = ProgramChangeRequest::where('student_id', $student->id)
                ->where('status', 'pending')
                ->latest()
                ->first();
        }

        // Get all supported programs for the dropdown
        $supportedPrograms = Program::whereIn('code', ['CDCS230', 'CDCS251', 'CDCS253', 'CDCS255', 'CDCS266'])
            ->get();

        return view('profile.show', compact('student', 'pendingRequest', 'supportedPrograms'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'home_address' => 'nullable|string|max:500',
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

        $user->save();

        // Update student-specific fields if user is a student
        if ($user->student) {
            $student = $user->student;
            if ($request->filled('home_address')) {
                $student->home_address = $request->home_address;
            }
            $student->save();
        }

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    public function requestProgramChange(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('profile.show')->with('error', 'Student profile not found.');
        }

        // Check if there's already a pending request
        $existingRequest = ProgramChangeRequest::where('student_id', $student->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return redirect()->route('profile.show')
                ->with('error', 'You already have a pending program change request. Please wait for review.');
        }

        $request->validate([
            'requested_program_code' => 'required|string|in:CDCS230,CDCS251,CDCS253,CDCS255,CDCS266',
            'reason' => 'required|string|min:50|max:1000',
            'supporting_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'supporting_document.required' => 'Please upload your degree offer letter for verification.',
            'supporting_document.mimes' => 'Offer letter must be a PDF, JPG, JPEG, or PNG file.',
            'supporting_document.max' => 'Offer letter file size must not exceed 5MB.',
        ]);

        // Get program details
        $requestedProgram = Program::where('code', $request->requested_program_code)->first();
        $currentProgram = Program::where('code', $student->program_code)->first();

        $data = [
            'student_id' => $student->id,
            'current_program_code' => $student->program_code,
            'current_program_name' => $currentProgram ? $currentProgram->name : $student->program_name,
            'requested_program_code' => $request->requested_program_code,
            'requested_program_name' => $requestedProgram ? $requestedProgram->name : '',
            'reason' => $request->reason,
            'status' => 'pending',
        ];

        // Handle offer letter upload (required for verification)
        if ($request->hasFile('supporting_document')) {
            $fileName = Str::uuid() . '.' . $request->file('supporting_document')->getClientOriginalExtension();
            $path = $request->file('supporting_document')->storeAs('program-change-requests', $fileName, 'local');
            $data['supporting_document_path'] = $path;
        }

        ProgramChangeRequest::create($data);

        return redirect()->route('profile.show')
            ->with('success', 'Program change request submitted successfully! Your offer letter has been received. Your Academic Advisor will verify the document and review your request shortly.');
    }

    public function changePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
        ], [
            'current_password.required' => 'Please enter your current password.',
            'new_password.required' => 'Please enter a new password.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password.different' => 'New password must be different from current password.',
        ]);

        // Verify current password
        if (!\Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile.show')
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        // Update password
        $user->password = \Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Password changed successfully! Your account is now more secure.');
    }
}
