<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equivalency List Published</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #28a745; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f8f9fa; padding: 20px; border: 1px solid #dee2e6; }
        .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #28a745; }
        .info-row { margin: 8px 0; }
        .label { font-weight: bold; color: #666; }
        .btn { display: inline-block; background: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        .footer { text-align: center; padding: 15px; color: #666; font-size: 12px; }
        .success-icon { font-size: 48px; }
        .status-badge { display: inline-block; background: #28a745; color: white; padding: 5px 10px; border-radius: 3px; font-weight: bold; }
        .notes-box { background: #d4edda; padding: 15px; border-radius: 5px; margin: 15px 0; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="header">
        <div class="success-icon">&#10004;</div>
        <h2>Congratulations!</h2>
        <p>Your Equivalency List Has Been Published</p>
    </div>

    <div class="content">
        <p>Dear {{ $list->creator->name ?? 'Resource Person' }},</p>

        <p>Great news! Your course equivalency list has been endorsed by the HEA unit and is now <strong>officially published</strong>.</p>

        <div class="info-box">
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="status-badge">PUBLISHED</span>
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
                <span class="label">Total Mappings:</span>
                {{ $list->total_equivalencies }} courses
            </div>
            <div class="info-row">
                <span class="label">Eligible Courses:</span>
                {{ $list->eligible_count }}
            </div>
            <div class="info-row">
                <span class="label">Endorsed By:</span>
                {{ $endorserName }}
            </div>
            <div class="info-row">
                <span class="label">Published On:</span>
                {{ $list->published_at?->format('d M Y, H:i') }}
            </div>
        </div>

        @if($endorsementNotes)
        <div class="notes-box">
            <strong>Endorsement Notes:</strong><br>
            {{ $endorsementNotes }}
        </div>
        @endif

        <p><strong>What happens next?</strong></p>
        <ul>
            <li>This list is now the official reference for {{ $list->program_code }} students</li>
            <li>Students can view and download this list from their portal</li>
            <li>Any previous active list for this category has been archived</li>
        </ul>

        <center>
            <a href="{{ $viewUrl }}" class="btn">View Published List</a>
        </center>
    </div>

    <div class="footer">
        <p>Thank you for your contribution to the credit exemption system.</p>
        <p>This is an automated notification from the UiTM Credit Exemption System.</p>
    </div>
</body>
</html>
