<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supported Degree Programs
    |--------------------------------------------------------------------------
    |
    | The list of bachelor's degree programs supported by the Credit Exemption
    | System. Only students enrolled in these programs can apply for exemptions.
    |
    */
    'supported_programs' => [
        'CDCS230' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN)',
        'CDCS251' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) PENGKOMPUTERAN NETSENTRIK',
        'CDCS253' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) PENGKOMPUTERAN MULTIMEDIA',
        'CDCS255' => 'SARJANA MUDA SAINS KOMPUTER (KEPUJIAN) RANGKAIAN KOMPUTER',
        'CDCS266' => 'SARJANA MUDA SISTEM MAKLUMAT (KEPUJIAN) KEJURUTERAAN SISTEM MAKLUMAT',
    ],

    /*
    |--------------------------------------------------------------------------
    | Program Groups (for Academic Advisors)
    |--------------------------------------------------------------------------
    |
    | Each program has student groups that academic advisors can be assigned to.
    | Groups follow the pattern: {PROGRAM_CODE}{YEAR}{GROUP_LETTER}
    | e.g., CDCS2301B = CDCS230, Year 1, Group B
    |
    | To add new groups:
    | 1. Add the group code to the appropriate program array below
    | 2. Clear config cache: php artisan config:clear
    |
    */
    'program_groups' => [
        'CDCS230' => [
            'name' => 'Bachelor of Computer Science (Hons.)',
            'groups' => ['CDCS2301B', 'CDCS2303B', 'CDCS2303C'],
        ],
        'CDCS251' => [
            'name' => 'Bachelor of Computer Science (Hons.) Netcentric Computing',
            'groups' => ['CDCS2513A'],
        ],
        'CDCS253' => [
            'name' => 'Bachelor of Computer Science (Hons.) Multimedia Computing',
            'groups' => ['CDCS2531A', 'CDCS2533B'],
        ],
        'CDCS255' => [
            'name' => 'Bachelor of Computer Science (Hons.) Computer Networks',
            'groups' => ['CDCS2551A', 'CDCS2553B'],
        ],
        'CDCS266' => [
            'name' => 'Bachelor of Information Systems (Hons.) Information Systems Engineering',
            'groups' => ['CDCS2663A'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Program Coordinator Categories
    |--------------------------------------------------------------------------
    |
    | Program Coordinators are assigned to manage specific categories of programs.
    | This allows one coordinator to oversee multiple related programs.
    |
    */
    'coordinator_categories' => [
        'category_1' => [
            'label' => 'Category 1',
            'programs' => ['CDCS230', 'CDCS251', 'CDCS253'],
            'description' => 'Computer Science Programs (General, Netcentric, Multimedia)',
        ],
        'category_2' => [
            'label' => 'Category 2',
            'programs' => ['CDCS255', 'CDCS266'],
            'description' => 'Network & Information Systems Programs',
        ],
    ],
];
