<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegistrationStatusController extends Controller
{
    /**
     * Show the status check form
     */
    public function showForm()
    {
        return view('auth.registration-status');
    }

    /**
     * Check registration status by email
     */
    public function checkStatus(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $validated['email'])
            ->whereIn('requested_role', ['academic_advisor', 'coordinator', 'resource_person'])
            ->first();

        if (!$user) {
            return back()
                ->withInput()
                ->with('not_found', true)
                ->with('checked_email', $validated['email']);
        }

        // Build status information
        $statusInfo = $this->buildStatusInfo($user);

        return view('auth.registration-status', [
            'user' => $user,
            'statusInfo' => $statusInfo,
            'checked_email' => $validated['email'],
        ]);
    }

    /**
     * Build comprehensive status information
     */
    private function buildStatusInfo(User $user): array
    {
        $status = $user->approval_status;
        $daysWaiting = $user->created_at->diffInDays(now());

        $info = [
            'status' => $status,
            'status_label' => $this->getStatusLabel($status),
            'status_color' => $this->getStatusColor($status),
            'status_icon' => $this->getStatusIcon($status),
            'registered_at' => $user->created_at,
            'days_waiting' => $daysWaiting,
            'role_requested' => $user->requested_role_label,
            'next_steps' => $this->getNextSteps($user),
        ];

        if ($status === 'approved') {
            $info['approved_at'] = $user->approved_at;
            $info['email_verified'] = $user->hasVerifiedEmail();
        }

        if ($status === 'rejected') {
            $info['rejection_reason'] = $user->rejection_reason;
        }

        return $info;
    }

    /**
     * Get human-readable status label
     */
    private function getStatusLabel(string $status): string
    {
        return match($status) {
            'pending' => 'Pending HEA Approval',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'pending_admin' => 'Pending Admin Approval',
            default => 'Unknown',
        };
    }

    /**
     * Get Bootstrap color for status
     */
    private function getStatusColor(string $status): string
    {
        return match($status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'pending_admin' => 'info',
            default => 'secondary',
        };
    }

    /**
     * Get icon for status
     */
    private function getStatusIcon(string $status): string
    {
        return match($status) {
            'pending' => 'fas fa-clock',
            'approved' => 'fas fa-check-circle',
            'rejected' => 'fas fa-times-circle',
            'pending_admin' => 'fas fa-user-shield',
            default => 'fas fa-question-circle',
        };
    }

    /**
     * Get next steps based on current status
     */
    private function getNextSteps(User $user): array
    {
        $status = $user->approval_status;

        if ($status === 'pending') {
            return [
                'Your registration is being reviewed by HEA personnel.',
                'You will receive an email notification once a decision is made.',
                'This typically takes 1-3 business days.',
            ];
        }

        if ($status === 'approved') {
            if (!$user->hasVerifiedEmail()) {
                return [
                    'Your registration has been approved!',
                    'Please check your email for the verification link.',
                    'Click the link to verify your email and complete registration.',
                    'If you did not receive the email, contact the HEA office.',
                ];
            }

            if (!$user->two_factor_verified_at) {
                return [
                    'Your email has been verified.',
                    'Please log in to set up two-factor authentication.',
                ];
            }

            return [
                'Your account is fully set up.',
                'You can now log in to access the system.',
            ];
        }

        if ($status === 'rejected') {
            return [
                'Your registration was not approved.',
                'Please review the rejection reason below.',
                'If you believe this was an error, contact the HEA office.',
                'You may register again with corrected information.',
            ];
        }

        return ['Please contact the administrator for assistance.'];
    }
}
