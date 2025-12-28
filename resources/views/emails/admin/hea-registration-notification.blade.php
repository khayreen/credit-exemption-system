<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
        .content { background: #f9f9f9; padding: 30px; border: 1px solid #ddd; }
        .user-details { background: white; padding: 20px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .detail-row { margin: 10px 0; }
        .detail-label { font-weight: bold; color: #dc3545; }
        .approval-methods { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
        .method { background: white; padding: 15px; margin: 10px 0; border: 1px solid #ddd; }
        .code-block { background: #2d2d2d; color: #f8f8f2; padding: 10px; font-family: monospace; overflow-x: auto; }
        .button { display: inline-block; padding: 12px 30px; background: #dc3545; color: white; text-decoration: none; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 NEW HEA REGISTRATION REQUEST</h1>
            <p>Immediate Action Required</p>
        </div>

        <div class="content">
            <h2>System Administrator Alert</h2>

            <p>A new user has requested HEA personnel access to the Credit Exemption System.</p>

            <div class="user-details">
                <h3>👤 Applicant Details</h3>

                <div class="detail-row">
                    <span class="detail-label">Name:</span><br>
                    {{ $user->name }}
                </div>

                <div class="detail-row">
                    <span class="detail-label">Email:</span><br>
                    {{ $user->email }}
                </div>

                <div class="detail-row">
                    <span class="detail-label">Requested Role:</span><br>
                    HEA Personnel
                </div>

                <div class="detail-row">
                    <span class="detail-label">Registration Date:</span><br>
                    {{ $user->created_at->format('F j, Y g:i A') }}
                </div>
            </div>

            <div class="approval-methods">
                <h3>⚡ Quick Approval Methods</h3>

                <div class="method">
                    <h4>Method 1: Web Interface (Recommended)</h4>
                    <p>Login as system admin and approve directly:</p>
                    <center>
                        <a href="{{ url('/admin/hea-approvals') }}" class="button">
                            Review HEA Request
                        </a>
                    </center>
                </div>

                <div class="method">
                    <h4>Method 2: Artisan Command (SSH Access)</h4>
                    <p>Run this command via SSH:</p>
                    <div class="code-block">
php artisan hea:approve {{ $user->email }}
                    </div>
                </div>

                <div class="method">
                    <h4>Method 3: Auto-Approve Link (Quick & Easy)</h4>
                    <p>Click to automatically approve this HEA registration:</p>
                    <center>
                        <a href="{{ route('admin.hea.quick-approve', ['user' => $user->id, 'token' => $user->generateApprovalToken()]) }}" class="button">
                            ✅ Quick Approve
                        </a>
                    </center>
                    <small style="color: #666;">This link is valid for 24 hours</small>
                </div>
            </div>

            <h3>⚠️ Security Reminder</h3>
            <ul>
                <li>Verify the applicant's identity before approval</li>
                <li>HEA personnel have full system access</li>
                <li>Contact the applicant if you don't recognize them</li>
                <li>Reject suspicious registration requests</li>
            </ul>

            <p><strong>Action Required:</strong> Please review and approve/reject this request within 24 hours.</p>

            <p>System Administrator<br>
            UiTM Credit Exemption System</p>
        </div>
    </div>
</body>
</html>
