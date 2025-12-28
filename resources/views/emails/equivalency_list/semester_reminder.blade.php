<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semester Reminder - Update Equivalency Lists</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #6f42c1; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f8f9fa; padding: 20px; border: 1px solid #dee2e6; }
        .info-box { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #6f42c1; }
        .info-row { margin: 8px 0; }
        .label { font-weight: bold; color: #666; }
        .btn { display: inline-block; background: #6f42c1; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        .footer { text-align: center; padding: 15px; color: #666; font-size: 12px; }
        .program-list { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 15px 0; border: 1px solid #ffc107; }
        .program-item { padding: 5px 0; border-bottom: 1px solid #ffeeba; }
        .program-item:last-child { border-bottom: none; }
        .warning-icon { font-size: 48px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 11px; margin-left: 5px; }
        .badge-internal { background: #007bff; color: white; }
        .badge-external { background: #28a745; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="warning-icon">&#128276;</div>
        <h2>Semester Reminder</h2>
        <p>Time to Update Your Equivalency Lists</p>
    </div>

    <div class="content">
        <p>Dear {{ $resourcePersonName }},</p>

        <p>A new semester is approaching! This is a reminder to review and update your course equivalency lists for <strong>{{ $upcomingSemester }}</strong>.</p>

        <div class="info-box">
            <div class="info-row">
                <span class="label">Upcoming Semester:</span>
                {{ $upcomingSemester }}
            </div>
            <div class="info-row">
                <span class="label">Your Assigned Programs:</span>
                {{ count($assignedPrograms) }} program(s)
            </div>
        </div>

        @if(count($programsMissingLists) > 0)
        <div class="program-list">
            <h4 style="margin-top: 0; color: #856404;">Programs Missing Lists for {{ $upcomingSemester }}:</h4>
            @foreach($programsMissingLists as $program)
            <div class="program-item">
                <strong>{{ $program['code'] }}</strong> - {{ $program['name'] }}
                <br>
                <small>
                    Missing:
                    @if($program['missing_internal'])
                        <span class="badge badge-internal">CS110 List</span>
                    @endif
                    @if($program['missing_external'])
                        <span class="badge badge-external">External Lists</span>
                    @endif
                </small>
            </div>
            @endforeach
        </div>
        @else
        <div class="info-box" style="border-left-color: #28a745;">
            <p style="color: #28a745; margin: 0;"><strong>All caught up!</strong> You have equivalency lists for all your assigned programs.</p>
        </div>
        @endif

        <p><strong>Recommended Actions:</strong></p>
        <ol>
            <li>Review your existing equivalency lists</li>
            <li>Create new lists for the upcoming semester (you can copy from previous semester)</li>
            <li>Update any course mappings if curriculum has changed</li>
            <li>Submit lists for HEA endorsement</li>
        </ol>

        <center>
            <a href="{{ $createUrl }}" class="btn">Manage Equivalency Lists</a>
        </center>
    </div>

    <div class="footer">
        <p>Please ensure your lists are submitted before the semester begins.</p>
        <p>This is an automated reminder from the UiTM Credit Exemption System.</p>
    </div>
</body>
</html>
