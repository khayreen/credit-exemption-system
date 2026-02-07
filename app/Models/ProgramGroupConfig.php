<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramGroupConfig extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'program_code',
        'semester',
        'group_letter',
        'group_code',
        'is_active',
        'assigned_user_id',
        'academic_year',
        'intake',
        'created_by',
    ];

    protected $casts = [
        'semester' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->group_code)) {
                $model->group_code = static::generateGroupCode(
                    $model->program_code,
                    $model->semester,
                    $model->group_letter
                );
            }
        });
    }

    public static function generateGroupCode(string $programCode, int $semester, string $groupLetter): string
    {
        return $programCode . $semester . $groupLetter;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_code', 'code');
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_user_id');
    }

    public function scopeAssigned($query)
    {
        return $query->whereNotNull('assigned_user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForProgram($query, string $programCode)
    {
        return $query->where('program_code', $programCode);
    }

    public function scopeForSemester($query, int $semester)
    {
        return $query->where('semester', $semester);
    }

    public static function getSupportedPrograms(): array
    {
        return [
            'CDCS230' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN)',
            'CDCS251' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) PENGKOMPUTERAN NETSENTRIK',
            'CDCS253' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) PENGKOMPUTERAN MULTIMEDIA',
            'CDCS255' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) RANGKAIAN KOMPUTER',
            'CDCS266' => 'SARJANA MUDA SISTEM MAKLUMAT (KEPUJIAN) KEJURUTERAAN SISTEM MAKLUMAT',
        ];
    }

    public static function getGroupLetters(): array
    {
        return ['A', 'B', 'C', 'D', 'E'];
    }

    public static function getSemesters(): array
    {
        return [1, 2, 3, 4, 5, 6, 7];
    }

    public static function getActiveGroupsForProgram(string $programCode): array
    {
        return static::active()
            ->forProgram($programCode)
            ->orderBy('semester')
            ->orderBy('group_letter')
            ->pluck('group_code')
            ->toArray();
    }

    public function scopeForSession($query, string $academicYear, string $intake)
    {
        return $query->where('academic_year', $academicYear)
                     ->where('intake', $intake);
    }

    /**
     * Get available sessions (SESI I and SESI II) with month ranges
     */
    public static function getSessions(): array
    {
        return [
            'sesi_1' => 'SESI I (OKT – FEB)',
            'sesi_2' => 'SESI II (MAC – OGOS)',
        ];
    }

    /**
     * Get session details with date ranges
     */
    public static function getSessionDetails(): array
    {
        return [
            'sesi_1' => [
                'label' => 'SESI I',
                'period' => 'OKTOBER – FEBRUARI',
                'months' => [10, 11, 12, 1, 2], // October to February
            ],
            'sesi_2' => [
                'label' => 'SESI II',
                'period' => 'MAC – OGOS',
                'months' => [3, 4, 5, 6, 7, 8], // March to August
            ],
        ];
    }

    /**
     * Format session display name (e.g., "SESI I 2025/2026 (OKTOBER 2025 – FEBRUARI 2026)")
     */
    public static function formatSessionName(string $session, string $academicYear): string
    {
        $sessions = static::getSessionDetails();
        $sessionInfo = $sessions[$session] ?? null;

        if (!$sessionInfo) {
            return ucfirst(str_replace('_', ' ', $session)) . ' ' . $academicYear;
        }

        $years = explode('/', $academicYear);
        $startYear = $years[0] ?? date('Y');
        $endYear = $years[1] ?? (date('Y') + 1);

        if ($session === 'sesi_1') {
            // SESI I: OKTOBER {startYear} – FEBRUARI {endYear}
            return "{$sessionInfo['label']} {$academicYear} (OKTOBER {$startYear} – FEBRUARI {$endYear})";
        } else {
            // SESI II: MAC {endYear} – OGOS {endYear}
            return "{$sessionInfo['label']} {$academicYear} (MAC {$endYear} – OGOS {$endYear})";
        }
    }

    /**
     * Get short session name (e.g., "SESI I 2025/2026")
     */
    public static function formatSessionShort(string $session, string $academicYear): string
    {
        $sessions = static::getSessions();
        $label = $sessions[$session] ?? ucfirst(str_replace('_', ' ', $session));
        return "{$label} {$academicYear}";
    }

    // Keep old method name for backward compatibility
    public static function getIntakes(): array
    {
        return static::getSessions();
    }

    public static function getAcademicYears(): array
    {
        $currentYear = (int) date('Y');
        $month = (int) date('m');
        $years = [];

        // Determine the starting academic year based on current date
        // If we're in Oct-Dec, current academic year starts this year
        // If we're in Jan-Sep, current academic year started last year
        $startAcademicYear = ($month >= 10) ? $currentYear : $currentYear - 1;

        // Generate current year and 2 years forward (no past years)
        for ($i = $startAcademicYear; $i <= $startAcademicYear + 2; $i++) {
            $year = $i . '/' . ($i + 1);
            $years[$year] = $year;
        }

        return $years;
    }

    public static function getCurrentAcademicYear(): string
    {
        $month = (int) date('m');
        $year = (int) date('Y');

        // Academic year:
        // SESI I (Oct-Feb): starts in October of year, so academic year is year/year+1
        // SESI II (Mar-Aug): continues same academic year
        if ($month >= 10) {
            // October-December: new academic year starts
            return $year . '/' . ($year + 1);
        } elseif ($month >= 3 && $month <= 8) {
            // March-August (SESI II): same academic year as previous SESI I
            return ($year - 1) . '/' . $year;
        } else {
            // January-February (still SESI I): same academic year
            return ($year - 1) . '/' . $year;
        }
    }

    public static function getCurrentSession(): string
    {
        $month = (int) date('m');
        // SESI I: October-February (months 10, 11, 12, 1, 2)
        // SESI II: March-August (months 3, 4, 5, 6, 7, 8)
        // September is transition month, treat as upcoming SESI I
        if ($month >= 3 && $month <= 8) {
            return 'sesi_2';
        } else {
            return 'sesi_1';
        }
    }

    // Keep old method name for backward compatibility
    public static function getCurrentIntake(): string
    {
        return static::getCurrentSession();
    }

    /**
     * Get the current session formatted for display
     */
    public static function getCurrentSessionFormatted(): string
    {
        return static::formatSessionName(
            static::getCurrentSession(),
            static::getCurrentAcademicYear()
        );
    }

    public static function getGroupsForSession(
        string $programCode,
        int $semester,
        string $academicYear,
        string $intake
    ): array {
        $groups = static::forProgram($programCode)
            ->forSemester($semester)
            ->forSession($academicYear, $intake)
            ->pluck('is_active', 'group_letter')
            ->toArray();

        return $groups;
    }
}
