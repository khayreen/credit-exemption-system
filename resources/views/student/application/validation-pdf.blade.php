<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Course Validation - {{ $application->matric_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #000;
        }

        .container {
            padding: 15px 25px;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        .header-top {
            position: relative;
            margin-bottom: 5px;
        }

        .form-number {
            position: absolute;
            right: 0;
            top: 0;
            font-size: 9px;
        }

        .logo-section {
            display: inline-block;
            text-align: center;
        }

        .university-name {
            font-size: 14px;
            font-weight: bold;
            color: #4a148c;
            margin-bottom: 3px;
        }

        .department-name {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .address {
            font-size: 8px;
        }

        /* Title Section */
        .title-section {
            background-color: #e8e8e8;
            padding: 8px;
            margin-bottom: 12px;
            text-align: center;
        }

        .title {
            font-size: 11px;
            font-weight: bold;
        }

        /* Info Tables */
        .info-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 3px 5px;
            vertical-align: top;
            font-size: 10px;
        }

        .info-table .label {
            font-weight: bold;
            width: 120px;
        }

        .info-table .separator {
            width: 10px;
        }

        .info-table .value {
            border-bottom: 1px dotted #666;
        }

        /* Two Column Info Section */
        .info-section {
            margin-bottom: 10px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .info-left, .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-item {
            margin-bottom: 4px;
        }

        .info-label {
            font-weight: bold;
            display: inline-block;
            min-width: 100px;
        }

        .info-value {
            display: inline;
        }

        /* Course Table */
        .course-table-container {
            margin: 15px 0;
        }

        .course-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .course-table th {
            background-color: #d4d4d4;
            border: 1px solid #000;
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
        }

        .course-table td {
            border: 1px solid #000;
            padding: 5px 4px;
            text-align: center;
        }

        .course-table td.course-name {
            text-align: left;
            padding-left: 6px;
        }

        .course-table .no-col {
            width: 30px;
        }

        .course-table .course-col {
            width: auto;
        }

        .course-table .credit-col {
            width: 70px;
        }

        .course-table .taken-col {
            width: 70px;
        }

        .course-table .group-col {
            width: 80px;
        }

        /* Footer Section */
        .footer-info {
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .footer-row {
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            width: 50%;
        }

        .footer-right {
            display: table-cell;
            width: 50%;
            text-align: right;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #000;
        }

        /* Declaration Section */
        .declaration {
            margin: 15px 0;
            padding: 10px;
            background-color: #f5f5f5;
            font-size: 8px;
            text-align: justify;
            line-height: 1.4;
            font-style: italic;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 20px;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            height: 50px;
            margin-bottom: 5px;
        }

        .signature-label {
            font-size: 9px;
            font-weight: bold;
        }

        .student-name {
            font-size: 9px;
            text-transform: uppercase;
            margin-top: 3px;
        }

        /* Summary Box */
        .summary-box {
            border: 2px solid #000;
            padding: 10px;
            margin: 10px 0;
            background-color: #f9f9f9;
        }

        .summary-title {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 5px;
            text-align: center;
        }

        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }

        .summary-label {
            display: table-cell;
            width: 70%;
            font-size: 9px;
        }

        .summary-value {
            display: table-cell;
            width: 30%;
            text-align: right;
            font-size: 9px;
            font-weight: bold;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* Exemption Status */
        .exempted {
            color: #28a745;
            font-weight: bold;
        }

        .not-exempted {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <span class="form-number">HEA/PP-2003-1</span>
            </div>
            <div class="logo-section">
                <div class="university-name">UNIVERSITI TEKNOLOGI MARA</div>
                <div class="department-name">BAHAGIAN HAL EHWAL AKADEMIK</div>
                <div class="address">40450 SHAH ALAM, SELANGOR DARUL EHSAN</div>
            </div>
        </div>

        <!-- Title -->
        <div class="title-section">
            <div class="title">COURSE VALIDATION - {{ date('Y') }}4 - Sesi {{ $session }}</div>
        </div>

        <!-- Student Information -->
        <table class="info-table">
            <tr>
                <td class="label">Student ID :</td>
                <td class="value">{{ $application->matric_no }}</td>
            </tr>
            <tr>
                <td class="label">Name :</td>
                <td class="value">{{ strtoupper($application->student_name) }}</td>
            </tr>
            <tr>
                <td class="label">IC/Passport No :</td>
                <td class="value">{{ $application->ic_number }}</td>
            </tr>
        </table>

        <!-- Academic Information -->
        <table class="info-table" style="margin-top: 10px;">
            <tr>
                <td class="label">Campus :</td>
                <td class="value" colspan="3">{{ $application->current_campus }}</td>
            </tr>
            <tr>
                <td class="label">Faculty :</td>
                <td class="value" colspan="3">FACULTY OF COMPUTER AND MATHEMATICAL SCIENCES</td>
            </tr>
            <tr>
                <td class="label">Programme :</td>
                <td class="value" colspan="3">{{ $application->current_program_code }} - {{ strtoupper($application->current_program) }}</td>
            </tr>
            <tr>
                <td class="label">Program Level :</td>
                <td class="value" colspan="3">I - BACHELOR DEGREE</td>
            </tr>
            <tr>
                <td class="label">Study Mode :</td>
                <td class="value">S - Full Time</td>
                <td class="label" style="width: 80px;">Part :</td>
                <td class="value">{{ $application->current_semester ?? 1 }}</td>
            </tr>
            <tr>
                <td class="label">Process Status :</td>
                <td class="value">{{ $processStatus }}</td>
                <td class="label" style="width: 80px;">Academic Fees :</td>
                <td class="value">Y{{ $application->current_semester ?? 1 }}</td>
            </tr>
            <tr>
                <td class="label">Semester Intake :</td>
                <td class="value" colspan="3">{{ date('Y') }}4 - Session {{ $session }}</td>
            </tr>
            <tr>
                <td class="label">Advisor :</td>
                <td class="value" colspan="3">{{ $advisor ? strtoupper($advisor->name) : '-' }}</td>
            </tr>
        </table>

        <!-- Courses Table -->
        <div class="course-table-container">
            <table class="course-table">
                <thead>
                    <tr>
                        <th class="no-col">NO</th>
                        <th class="course-col">COURSES</th>
                        <th class="credit-col">CREDIT UNIT</th>
                        <th class="taken-col">NO. TAKEN</th>
                        <th class="group-col">GROUP</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $courseNumber = 1;
                        $totalCredits = 0;

                        // Define correct credit hours for degree courses
                        $degreeCreditHours = [
                            'CSC402' => 3,
                            'CSC413' => 3,
                            'CSC429' => 3,
                            'CSC435' => 3,
                            'CSC404' => 3,
                            'ICT450' => 3,
                            'STA416' => 3,
                            'ITT400' => 3,
                            'MAT406' => 3,
                            'MAT421' => 3,
                            'CSC574' => 3,
                        ];
                    @endphp
                    @foreach($exemptedSubjects as $subject)
                        @php
                            $notes = $subject->notes ? json_decode($subject->notes, true) : null;
                            $equivalentCourse = $notes['equivalent_course'] ?? null;

                            // Skip co-curricular courses (starting with HXX)
                            if ($equivalentCourse && (str_starts_with($equivalentCourse, 'HXX') || str_starts_with($equivalentCourse, 'HXXXXX'))) {
                                continue;
                            }

                            $degreeCourse = $notes['degree_course_name'] ?? $equivalentCourse;

                            // Use correct credit hour from mapping, fallback to 3 if not found
                            $creditHour = $degreeCreditHours[$equivalentCourse] ?? 3;
                            $totalCredits += $creditHour;
                        @endphp
                        <tr>
                            <td>{{ $courseNumber++ }}.</td>
                            <td class="course-name">{{ $equivalentCourse }} - {{ strtoupper($degreeCourse ?? $subject->course_name) }} - {{ $statusText }}</td>
                            <td>{{ number_format($creditHour, 2) }}</td>
                            <td>1</td>
                            <td>{{ $application->student_group ?? 'M1BA2421B' }}</td>
                        </tr>
                    @endforeach
                    @php
                        // If no courses after filtering co-curricular
                        $hasAcademicCourses = false;
                        foreach($exemptedSubjects as $subject) {
                            $notes = $subject->notes ? json_decode($subject->notes, true) : null;
                            $equivalentCourse = $notes['equivalent_course'] ?? null;
                            if (!$equivalentCourse || (!str_starts_with($equivalentCourse, 'HXX') && !str_starts_with($equivalentCourse, 'HXXXXX'))) {
                                $hasAcademicCourses = true;
                                break;
                            }
                        }
                    @endphp
                    @if($exemptedSubjects->isEmpty() || !$hasAcademicCourses)
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">No exempted courses available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Footer Info -->
        <div class="footer-info">
            <div class="footer-row">
                <div class="footer-left">
                    <strong>Total Credit Unit :</strong> {{ number_format($totalCredits, 1) }}
                </div>
                <div class="footer-right">
                    <strong>Date Printed :</strong> {{ date('d/m/Y h:i A') }}
                </div>
            </div>
            <div style="margin-top: 5px;">
                <strong>Status :</strong> <span class="status-badge">{{ $processStatus }}</span>
            </div>
        </div>

        <!-- Declaration -->
        <div class="declaration">
            I hereby declare that I have read, fully understood and agree to abide by the rules as stipulated in the terms and conditions for all students of Universiti Teknologi MARA according to the rules of the Senate and the Academic Regulations. I shall attend all meetings scheduled for each of the courses registered above and fulfill the academic requirement therein. I also declare registration for all the courses above is correct and I have registered all the courses that I am taking this semester.
        </div>

        <!-- Signature Section -->
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">Student Signature</div>
                        <div class="student-name">{{ strtoupper($application->student_name) }}</div>
                    </div>
                </td>
                <td>
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">Approval by Campus/Faculty</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
