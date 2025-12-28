<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equivalency List Needs Revision</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f8f9fa; padding: 20px; border: 1px solid #dee2e6; }
        .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #dc3545; }
        .info-row { margin: 8px 0; }
        .label { font-weight: bold; color: #666; }
        .btn { display: inline-block; background: #dc3545; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        .footer { text-align: center; padding: 15px; color: #666; font-size: 12px; }
        .status-badge { display: inline-block; background: #dc3545; color: white; padding: 5px 10px; border-radius: 3px; font-weight: bold; }
        .reason-box { background: #f8d7da; padding: 15px; border-radius: 5px; margin: 15px 0; border: 1px solid #f5c6cb; }
        .reason-box h4 { color: #721c24; margin-top: 0; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Revision Required</h2>
        <p>Your Equivalency List Needs Changes</p>
    </div>

    <div class="content">
        <p>Dear {{ $list->creator->name ?? 'Resource Person' }},</p>

        <p>Your submitted course equivalency list has been reviewed but requires some changes before it can be endorsed.</p>

        <div class="info-box">
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="status-badge">REJECTED</span>
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
                <span class="label">Reviewed By:</span>
                {{ $reviewerName }}
            </div>
        </div>

        <div class="reason-box">
            <h4>Reason for Rejection:</h4>
            <p>{{ $rejectionNotes }}</p>
        </div>

        <p><strong>What to do next:</strong></p>
        <ol>
            <li>Review the feedback provided above</li>
            <li>Edit your equivalency list to address the concerns</li>
            <li>Resubmit the list for endorsement</li>
        </ol>

        <p>Your list has been returned to <strong>Draft</strong> status and can be edited.</p>

        <center>
            <a href="{{ $editUrl }}" class="btn">Edit & Resubmit List</a>
        </center>
    </div>

    <div class="footer">
        <p>If you have questions about the feedback, please contact the HEA unit.</p>
        <p>This is an automated notification from the UiTM Credit Exemption System.</p>
    </div>
</body>
</html>
