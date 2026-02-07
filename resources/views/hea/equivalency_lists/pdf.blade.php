<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengecualian Kredit (PC) - {{ $list->program_code }} - {{ $list->semester }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.2;
            padding: 15px 25px;
            color: #000;
            max-width: 210mm;
            margin: 0 auto;
            background: #f5f5f5;
        }
        /* Container for portrait A4 page */
        .page-container {
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: 20px auto;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 13pt;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 11pt;
            font-weight: normal;
            margin-bottom: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }
        th {
            background: #fff;
            font-weight: bold;
            text-align: center;
            font-size: 10pt;
        }
        td {
            font-size: 10pt;
        }
        .col-no {
            width: 5%;
            text-align: center;
        }
        .col-course {
            width: 30%;
        }
        .col-credit {
            width: 8%;
            text-align: center;
        }
        .col-replacement {
            width: 42%;
        }
        .col-credit-replacement {
            width: 8%;
            text-align: center;
        }
        .course-code {
            font-weight: bold;
        }
        .course-name {
            font-size: 9pt;
            margin-top: 1px;
        }
        .summary-row {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .footer-section {
            margin-top: 10px;
            font-size: 9pt;
        }
        .footer-section h3 {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .university-courses {
            margin-top: 8px;
        }
        .notes {
            margin-top: 10px;
            padding: 8px;
            border: 1px solid #000;
            font-size: 8.5pt;
            line-height: 1.4;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 12px;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
                background: white;
                max-width: 100%;
            }
            .page-container {
                box-shadow: none;
                margin: 0;
                padding: 15px 25px;
            }
            .no-print { display: none; }
        }
        @page {
            size: A4 portrait;
            margin: 15mm;
        }
        /* Prevent page breaks inside tables */
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">Print / Save as PDF</button>

    <div class="page-container">
    <div class="header">
        <h1>PENGECUALIAN KREDIT (PC) KURSUS BAGI PROGRAM {{ strtoupper($list->program_code) }}</h1>
        <h2>(Kemaskini {{ $list->published_at?->format('d F Y') ?? now()->format('d F Y') }})</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">Bil</th>
                <th class="col-course">Kursus {{ $list->program_code }} yang layak dipohon pengecualian</th>
                <th class="col-credit">Jam<br>kredit</th>
                <th class="col-replacement">Kursus diploma CS110 yang setara<br>{{ $list->isInternal() ? '(Pelan 70067350/7351)' : '('.$list->source_institution.')' }}</th>
                <th class="col-credit-replacement">Jam<br>kredit</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalDegreeCredits = 0;
                $totalDiplomaCredits = 0;
                // Filter out university courses (HXXX) from main table
                $eligibleCourses = $list->courseEquivalencies->filter(function($eq) {
                    return $eq->is_eligible &&
                           !str_starts_with($eq->diploma_course_code, 'HXXX') &&
                           !str_contains(strtolower($eq->diploma_course_name), 'ko kurikulum');
                })->values();
            @endphp
            @foreach($eligibleCourses as $index => $eq)
            @php
                $totalDegreeCredits += $eq->degree_credit_hour;
                $totalDiplomaCredits += $eq->diploma_credit_hour;
            @endphp
            <tr>
                <td class="col-no">{{ $index + 1 }}</td>
                <td class="col-course">
                    <div class="course-code">{{ $eq->degree_course_code }}</div>
                    <div class="course-name">{{ $eq->degree_course_name }}</div>
                </td>
                <td class="col-credit">{{ $eq->degree_credit_hour }}</td>
                <td class="col-replacement">
                    <div class="course-code">{{ $eq->diploma_course_code }}</div>
                    <div class="course-name">{{ $eq->diploma_course_name }}</div>
                </td>
                <td class="col-credit-replacement">{{ $eq->diploma_credit_hour }}</td>
            </tr>
            @endforeach
            <tr class="summary-row">
                <td colspan="2">Jumlah kredit</td>
                <td>36</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    @php
        // Separate university courses (HXXX - Ko Kurikulum) - only eligible ones
        $universityCourses = $list->courseEquivalencies->filter(function($eq) {
            return $eq->is_eligible && (
                str_starts_with($eq->diploma_course_code, 'HXXX') ||
                str_contains(strtolower($eq->diploma_course_name), 'ko kurikulum')
            );
        })->values(); // Reset array keys for sequential numbering
        $totalUniversityCredits = $universityCourses->sum('degree_credit_hour');
    @endphp

    @if($universityCourses->count() > 0)
    <div class="university-courses">
        <table>
            <thead>
                <tr>
                    <th colspan="5" style="text-align: center; font-size: 10pt;">Kursus Universiti</th>
                </tr>
            </thead>
            <tbody>
                @foreach($universityCourses as $index => $eq)
                <tr>
                    <td class="col-no">{{ $eligibleCourses->count() + $index + 1 }}</td>
                    <td class="col-course">
                        <div class="course-code">{{ $eq->degree_course_code }}</div>
                        <div class="course-name">{{ $eq->degree_course_name }}</div>
                    </td>
                    <td class="col-credit">{{ $eq->degree_credit_hour }}</td>
                    <td class="col-replacement">
                        <div class="course-code">{{ $eq->diploma_course_code }}</div>
                        <div class="course-name">{{ $eq->diploma_course_name }}</div>
                    </td>
                    <td class="col-credit-replacement">{{ $eq->diploma_credit_hour }}</td>
                </tr>
                @endforeach
                <tr class="summary-row">
                    <td colspan="2">Jumlah kredit</td>
                    <td>{{ $totalUniversityCredits }}</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    @php
        $maxCredits = 36;
        $actualTotal = $totalDegreeCredits + $totalUniversityCredits;
    @endphp
    <div class="footer-section">
        <p><strong>Jumlah Pengecualian Kredit maksimum yang dibenarkan ialah {{ $maxCredits }} jam sahaja</strong></p>
        <p style="font-size: 8pt; margin-top: 3px;">(Mengikut Peraturan Akademik Program Diploma dan Sarjana Muda Pindaan 2017 (rujuk para 2.5.1 Pengecualian Kredit), pengecualian kredit adalah pemindahan kredit tanpa markah atau/dan gred secara menegak (vertical) daripada tahap pengajian yang rendah ke tahap pengajian yang lebih tinggi dan tidak melebihi 30% daripada jumlah kredit bagi program pengajian yang sedang diikuti.)</p>
    </div>

    <div class="notes">
        <p style="margin-bottom: 8px;"><strong>Kemasukan dari Diploma Sains Komputer (CS110)</strong> : Part 3</p>
        <p style="margin-bottom: 8px;"><strong>Kemasukan dari Diploma FSKM (Selain CS110)</strong> : Part 2</p>
        <p style="margin-bottom: 10px;"><strong>Kemasukan dari diploma lain UiTM & IPT lain</strong> : Part 1</p>

        <p style="margin-bottom: 5px;"><strong>Nota:</strong></p>
        <p style="margin-left: 15px; margin-bottom: 3px;">Co-Curiculum I diberi PC kepada pelajar Lepasan Matrikulasi sekiranya pelajar:-</p>
        <p style="margin-left: 25px; margin-bottom: 2px;">a) Telah mengikuti Kokurikulum Badan Beruniform semasa matrikulasi atau</p>
        <p style="margin-left: 25px; margin-bottom: 5px;">b) Mempunyai Sijil PLKN</p>
        <p style="margin-left: 15px;">Co-Curriculum I, II dan III diberi PC secara automatik untuk Lepasan Diploma UiTM.</p>
    </div>

    @php
        // For legacy lists: if endorsed_by is null but published, use publisher as endorser
        $effectiveEndorser = $list->endorser ?? $list->publisher;
        $effectiveEndorserName = $effectiveEndorser->name ?? 'N/A';
        $effectiveEndorsementDate = $list->endorsed_at ?? $list->published_at;
    @endphp

    <div class="footer-section" style="margin-top: 12px; text-align: right; font-size: 8.5pt;">
        <p>Disediakan: {{ $list->creator->name ?? 'N/A' }} | Disahkan: {{ $effectiveEndorserName }} | {{ $effectiveEndorsementDate?->format('d/m/Y') ?? now()->format('d/m/Y') }}</p>
    </div>
    </div><!-- End page-container -->
</body>
</html>
