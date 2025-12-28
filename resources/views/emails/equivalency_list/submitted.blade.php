<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Equivalency List Submitted</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #17a2b8; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f8f9fa; padding: 20px; border: 1px solid #dee2e6; }
        .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #17a2b8; }
        .info-row { margin: 8px 0; }
        .label { font-weight: bold; color: #666; }
        .btn { display: inline-block; background: #17a2b8; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        .footer { text-align: center; padding: 15px; color: #666; font-size: 12px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 12px; }
        .badge-internal { background: #007bff; color: white; }
        .badge-external { background: #28a745; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h2>New Equivalency List Submitted</h2>
        <p>Action Required: Review & Endorse</p>
    </div>

    <div class="content">
        <p>Dear HEA Personnel,</p>

        <p>A new course equivalency list has been submitted for your review and endorsement.</p>

        <div class="info-box">
            <div class="info-row">
                <span class="label">Category:</span>
                @if($list->isInternal())
                    <span class="badge badge-internal">CS110 (UiTM Diploma)</span>
                @else
                    <span class="badge badge-external">External: {{ $list->source_institution }}</span>
                @endif
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
                {{ $list->eligible_count }} ({{ $list->total_equivalencies > 0 ? round(($list->eligible_count / $list->total_equivalencies) * 100) : 0 }}%)
            </div>
            <div class="info-row">
                <span class="label">Submitted By:</span>
                {{ $submitterName }}
            </div>
            <div class="info-row">
                <span class="label">Submitted On:</span>
                {{ $list->submitted_at?->format('d M Y, H:i') }}
            </div>
            @if($list->submission_notes)
            <div class="info-row">
                <span class="label">Notes:</span>
                {{ $list->submission_notes }}
            </div>
            @endif
        </div>

        <p>Please review the course mappings and either endorse the list for publication or request changes if needed.</p>

        <center>
            <a href="{{ $reviewUrl }}" class="btn">Review & Endorse List</a>
        </center>
    </div>

    <div class="footer">
        <p>This is an automated notification from the UiTM Credit Exemption System.</p>
        <p>Please do not reply to this email.</p>
    </div>
</body>
</html>
