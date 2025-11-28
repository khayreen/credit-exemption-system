<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\AcademicAdvisor;
use App\Models\Coordinator;
use App\Models\ResourcePerson;
use App\Models\HeaPersonnel;
use App\Models\ExternalLecturer;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get the redirect path based on user role
     */
    protected function redirectTo()
    {
        $user = auth()->user();
        
        if ($user) {
            switch ($user->role) {
                case 'student':
                    return '/student/dashboard';
                case 'academic_advisor':
                    return '/academic-advisor/dashboard';
                case 'coordinator':
                    return '/coordinator/dashboard';
                case 'resource_person':
                    return '/resource-person/dashboard';
                case 'external_lecturer':
                    return '/external-lecturer/dashboard';
                case 'hea_personnel':
                    return '/hea/dashboard';
            }
        }
        
        return '/home';
    }

    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:student,academic_advisor,coordinator,resource_person,hea_personnel,external_lecturer'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        // Add validation for assigned_programs when role is resource_person
        if (isset($data['role']) && $data['role'] === 'resource_person') {
            $rules['assigned_programs'] = ['required', 'array', 'min:1'];
            $rules['assigned_programs.*'] = ['string', 'in:CDCS251,CDCS255,CDCS266'];
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        // FIX: Make all placeholder values unique by appending the current time
        $timestamp = time();

        switch ($data['role']) {
            case 'student':
                Student::create([
                    'user_id' => $user->id,
                    'matric_no' => 'TEMP-' . $timestamp,
                    'program_name' => 'Not Set',
                    'ic_number' => '000000-00-' . $timestamp, // Made unique
                    'campus' => 'Not Set',
                    'intake_semester' => 'Not Set',
                ]);
                break;
            case 'academic_advisor':
                AcademicAdvisor::create([
                    'user_id' => $user->id,
                    'staff_id' => 'TEMP-AA-' . $timestamp, // Made unique
                    'department' => 'Not Set',
                ]);
                break;
            case 'coordinator':
                Coordinator::create([
                    'user_id' => $user->id,
                    'staff_id' => 'TEMP-C-' . $timestamp, // Made unique
                    'department' => 'Not Set',
                ]);
                break;
            case 'resource_person':
                ResourcePerson::create([
                    'user_id' => $user->id,
                    'staff_id' => 'TEMP-RP-' . $timestamp, // Made unique
                    'department' => 'Not Set',
                    'expertise_area' => 'Not Set',
                    'assigned_programs' => $data['assigned_programs'] ?? [],
                ]);
                break;
            case 'hea_personnel':
                HeaPersonnel::create([
                    'user_id' => $user->id,
                    'staff_id' => 'TEMP-HEA-' . $timestamp, // Made unique
                    'unit' => 'Not Set',
                ]);
                break;
            case 'external_lecturer':
                ExternalLecturer::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'institution_name' => 'Not Set',
                    'phone_number' => $user->phone_number,
                ]);
                break;
        }

        return $user;
    }
    
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        // Send email verification - this is required for multi-factor authentication
        try {
            event(new Registered($user));
            \Illuminate\Support\Facades\Log::info('Verification email sent successfully to: ' . $user->email);
            $message = 'Registration successful! Please check your email (' . $user->email . ') to verify your account before logging in.';
        } catch (\Exception $e) {
            // Log the detailed error for debugging
            \Illuminate\Support\Facades\Log::error('Failed to send verification email: ' . $e->getMessage(), [
                'user_email' => $user->email,
                'error_trace' => $e->getTraceAsString()
            ]);

            // Still allow registration but warn about email issue
            $message = 'Registration successful! However, we could not send the verification email. Please contact support or try registering again.';
        }

        $this->guard()->logout();
        return redirect('/login')->with('status', $message);
    }
}
