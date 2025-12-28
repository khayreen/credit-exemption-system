<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equivalency List Under Review</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #ffc107; color: #333; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f8f9fa; padding: 20px; border: 1px solid #dee2e6; }
        .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #ffc107; }
        .info-row { margin: 8px 0; }
        .label { font-weight: bold; color: #666; }
        .btn { display: inline-block; background: #6c757d; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        .footer { text-align: center; padding: 15px; color: #666; font-size: 12px; }
        .status-badge { display: inline-block; background: #ffc107; color: #333; padding: 5px 10px; border-radius: 3px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Your List is Under Review</h2>
        <p>Status Update</p>
    </div>

    <div class="content">
        <p>Dear {{ $list->creator->name ?? 'Resource Person' }},</p>

        <p>Your submitted course equivalency list is now being reviewed by the HEA unit.</p>

        <div class="info-box">
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="status-badge">UNDER REVIEW</span>
            </div>
            <div class="info-row">
                <span class="label">Category:</span>
                {{ $list->isInternal() ? 'CS110 (UiTM Diploma)' : 'External: ' . $list->source_institution }}
            </div>
            <div class="info-row">
                <span class="label">Target Program:</span>
                {{ $list->program_code }} - {{ $list->program_name }}
            </div>
            <div class="info-row">
                <span class="label">Semester:</span>
                {{ $list->semester }}
            </div>
            <div class="info-row">
                <span class="label">Reviewer:</span>
                {{ $reviewerName }}
            </div>
            <div class="info-row">
                <span class="label">Review Started:</span>
                {{ now()->format('d M Y, H:i') }}
            </div>
        </div>

        <p>You will receive another notification once the review is complete. The reviewer may either:</p>
        <ul>
            <li><strong>Endorse & Publish</strong> - Your list will become the official reference for students</li>
            <li><strong>Request Changes</strong> - You will be asked to revise and resubmit</li>
        </ul>

        <center>
            <a href="{{ $statusUrl }}" class="btn">View Submission Status</a>
        </center>
    </div>

    <div class="footer">
        <p>This is an automated notification from the UiTM Credit Exemption System.</p>
        <p>Please do not reply to this email.</p>
    </div>
</body>
</html>
