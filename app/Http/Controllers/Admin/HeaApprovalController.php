<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\HeaPersonnel;
use App\Models\AuditTrail;
use App\Mail\UserApprovedMail;
use App\Mail\UserRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HeaApprovalController extends Controller
{
    /**
     * Display pending HEA registrations
     */
    public function index()
    {
        $pendingHeaUsers = User::where('approval_status', 'pending_admin')
                              ->where('requested_role', 'hea_personnel')
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('admin.hea-approvals', compact('pendingHeaUsers'));
    }

    /**
     * Approve HEA registration
     */
    public function approve(Request $request, User $user)
    {
        if ($user->approval_status !== 'pending_admin' || $user->requested_role !== 'hea_personnel') {
            return back()->withErrors(['error' => 'Invalid approval request.']);
        }

        DB::transaction(function() use ($user) {
            // Update user
            $user->update([
                'role' => 'hea_personnel', // Legacy field for backward compatibility
                'current_role' => 'hea_personnel',
                'approval_status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Create HEA personnel record
            HeaPersonnel::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
            ]);

            // Create audit trail
            AuditTrail::create([
                'id' => Str::uuid(),
                'user_id' => Auth::id(),
                'action' => 'hea_user_approved',
                'details' => json_encode([
                    'approved_user_id' => $user->id,
                    'approved_user_email' => $user->email,
                    'approved_by_admin' => Auth::user()->email,
                ]),
            ]);

            // Send email verification notification for approved HEA user
            $user->sendEmailVerificationNotification();
        });

        return back()->with('success', "HEA user {$user->name} approved successfully!");
    }

    /**
     * Reject HEA registration
     */
    public function reject(Request $request, User $user)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $user->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by' => Auth::id(),
        ]);

        // Create audit trail
        AuditTrail::create([
            'id' => Str::uuid(),
            'user_id' => Auth::id(),
            'action' => 'hea_user_rejected',
            'details' => json_encode([
                'rejected_user_id' => $user->id,
                'rejected_user_email' => $user->email,
                'rejection_reason' => $validated['rejection_reason'],
                'rejected_by_admin' => Auth::user()->email,
            ]),
        ]);

        // Send rejection email
        Mail::to($user->email)->queue(new UserRejectedMail($user, $validated['rejection_reason']));

        return back()->with('success', "HEA registration rejected.");
    }

    /**
     * Quick approve via email link
     */
    public function quickApprove(Request $request, User $user, string $token)
    {
        // Verify token
        if (!$user->verifyApprovalToken($token)) {
            abort(403, 'Invalid or expired approval link.');
        }

        if ($user->approval_status !== 'pending_admin' || $user->requested_role !== 'hea_personnel') {
            abort(403, 'Invalid approval request.');
        }

        DB::transaction(function() use ($user) {
            $user->update([
                'role' => 'hea_personnel', // Legacy field for backward compatibility
                'current_role' => 'hea_personnel',
                'approval_status' => 'approved',
                'approved_at' => now(),
            ]);

            HeaPersonnel::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
            ]);

            // Create audit trail
            AuditTrail::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'action' => 'hea_user_quick_approved',
                'details' => json_encode([
                    'approved_user_id' => $user->id,
                    'approved_user_email' => $user->email,
                    'approval_method' => 'quick_approve_link',
                ]),
            ]);

            // Send email verification notification for approved HEA user
            $user->sendEmailVerificationNotification();
        });

        return view('admin.hea-quick-approve-success', compact('user'));
    }
}
