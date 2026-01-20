<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * Redirects users to their role-specific dashboard.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        // Redirect based on user's current role
        switch ($user->current_role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'student':
                return redirect('/student/dashboard');
            case 'academic_advisor':
                return redirect('/academic-advisor/dashboard');
            case 'coordinator':
                return redirect('/coordinator/dashboard');
            case 'program_coordinator':
                return redirect('/program-coordinator/dashboard');
            case 'resource_person':
                return redirect('/resource-person/dashboard');
            case 'external_lecturer':
                return redirect('/external-lecturer/dashboard');
            case 'hea_personnel':
                return redirect('/hea/dashboard');
        }

        // Fallback to home view for unknown roles
        return view('home');
    }
}
