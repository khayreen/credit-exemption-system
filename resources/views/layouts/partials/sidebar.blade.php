<nav id="sidebar" class="industrial-sidebar">
    <!-- Sidebar Header with Industrial Branding -->
    <div class="sidebar-brand">
        <div class="brand-pattern"></div>
        <div class="brand-content">
            <div class="brand-logo">
                <div class="logo-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div class="logo-text">
                    <span class="logo-title">UiTM</span>
                    <span class="logo-subtitle">Credit Exemption</span>
                </div>
            </div>
        </div>
    </div>

    <!-- User Profile Section -->
    <div class="sidebar-profile">
        <div class="profile-avatar-wrapper">
            @if (Auth::user()->profile_photo_path)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile Photo" class="profile-avatar">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=100&background=1e3a8a&color=fff&bold=true" alt="Default Profile Photo" class="profile-avatar">
            @endif
            <div class="profile-status-indicator"></div>
        </div>
        <div class="profile-info">
            <h5 class="profile-name">{{ Auth::user()->name }}</h5>
            @php
                $roleDisplay = Auth::user()->role;
                $roleMap = [
                    'student' => ['label' => 'Student', 'icon' => 'fa-user-graduate', 'color' => 'student'],
                    'academic_advisor' => ['label' => 'Academic Advisor', 'icon' => 'fa-chalkboard-teacher', 'color' => 'advisor'],
                    'program_coordinator' => ['label' => 'Program Coordinator', 'icon' => 'fa-sitemap', 'color' => 'coordinator'],
                    'resource_person' => ['label' => 'Resource Person', 'icon' => 'fa-user-cog', 'color' => 'resource'],
                    'external_lecturer' => ['label' => 'External Lecturer', 'icon' => 'fa-user-tie', 'color' => 'external'],
                    'hea_personnel' => ['label' => 'HEA Personnel', 'icon' => 'fa-user-shield', 'color' => 'hea'],
                    'admin' => ['label' => 'Administrator', 'icon' => 'fa-crown', 'color' => 'admin']
                ];
                $roleInfo = $roleMap[$roleDisplay] ?? ['label' => ucwords(str_replace('_', ' ', $roleDisplay)), 'icon' => 'fa-user', 'color' => 'student'];
            @endphp
            <div class="profile-role role-{{ $roleInfo['color'] }}">
                <i class="fas {{ $roleInfo['icon'] }}"></i>
                <span>{{ $roleInfo['label'] }}</span>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-nav">
        <ul class="nav-list">
            <!-- Main Menu Section -->
            <li class="nav-section">
                <span class="nav-section-title">
                    <i class="fas fa-th-large"></i>
                    Main Menu
                </span>
            </li>

            {{-- Dashboard link based on role --}}
            @if(Auth::user()->current_role == 'admin')
                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-tachometer-alt"></i></div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
            @elseif(Auth::user()->role == 'hea_personnel')
                <li class="nav-item {{ request()->routeIs('hea.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('hea.dashboard') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-tachometer-alt"></i></div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
            @elseif(in_array(Auth::user()->role, ['student', 'academic_advisor', 'resource_person']))
                <li class="nav-item {{ request()->routeIs(Auth::user()->role . '.dashboard') ? 'active' : '' }}">
                    <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-tachometer-alt"></i></div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
            @elseif(Auth::user()->role == 'program_coordinator')
                <li class="nav-item {{ request()->routeIs('program_coordinator.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('program_coordinator.dashboard') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-tachometer-alt"></i></div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
            @else
                <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-home"></i></div>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
            @endif

            {{-- Student Menu --}}
            @if(Auth::user()->role == 'student')
                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-file-alt"></i>
                        Applications
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('student.application.create') ? 'active' : '' }}">
                    <a href="{{ route('student.application.create') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-plus-circle"></i></div>
                        <span class="nav-text">New Application</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('student.application.status') ? 'active' : '' }}">
                    <a href="{{ route('student.application.status') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-search"></i></div>
                        <span class="nav-text">Application Status</span>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-exchange-alt"></i>
                        Course Equivalency
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('student.course_equivalencies.*') ? 'active' : '' }}">
                    <a href="{{ route('student.course_equivalencies.index') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-clipboard-list"></i></div>
                        <span class="nav-text">Equivalency Lists</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('student.equivalency.request.*') ? 'active' : '' }}">
                    <a href="{{ route('student.equivalency.request.create') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-file-signature"></i></div>
                        <span class="nav-text">Equivalency Request</span>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-book"></i>
                        Resources
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('student.terms') ? 'active' : '' }}">
                    <a href="{{ route('student.terms') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-file-contract"></i></div>
                        <span class="nav-text">Terms & Conditions</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="https://hea.uitm.edu.my/index.php/calendars/academic-calendar" target="_blank" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-calendar-alt"></i></div>
                        <span class="nav-text">Academic Calendar</span>
                        <i class="fas fa-external-link-alt nav-external"></i>
                    </a>
                </li>
            @endif

            {{-- Academic Advisor Menu --}}
            @if(Auth::user()->role == 'academic_advisor')
                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-users"></i>
                        Advisees
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('academic_advisor.my_students') ? 'active' : '' }}">
                    <a href="{{ route('academic_advisor.my_students') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-users"></i></div>
                        <span class="nav-text">My Students</span>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-sync-alt"></i>
                        Re-evaluations
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('academic_advisor.reevaluations.*') ? 'active' : '' }}">
                    <a href="{{ route('academic_advisor.reevaluations.index') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-sync-alt"></i></div>
                        <span class="nav-text">Pending Re-evaluations</span>
                        @php
                            $pendingCount = \App\Models\PendingReevaluation::pending()->notExpired()->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-clipboard-check"></i>
                        Equivalencies
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('academic_advisor.equivalency_lists.*') ? 'active' : '' }}">
                    <a href="{{ route('academic_advisor.equivalency_lists.index') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-clipboard-list"></i></div>
                        <span class="nav-text">Equivalency Lists</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('academic_advisor.course_equivalencies.*') ? 'active' : '' }}">
                    <a href="{{ route('academic_advisor.course_equivalencies.view') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-list-alt"></i></div>
                        <span class="nav-text">All Course Mappings</span>
                    </a>
                </li>
            @endif

            {{-- Program Coordinator Menu --}}
            @if(Auth::user()->role == 'program_coordinator')
                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-inbox"></i>
                        Requests
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('program_coordinator.equivalency_requests.*') || request()->routeIs('program_coordinator.course_requests') ? 'active' : '' }}">
                    <a href="{{ route('program_coordinator.equivalency_requests.index') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-inbox"></i></div>
                        <span class="nav-text">Equivalency Requests</span>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-cogs"></i>
                        Management
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('program_coordinator.equivalency_lists.index') ? 'active' : '' }}">
                    <a href="{{ route('program_coordinator.equivalency_lists.index') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-clipboard-check"></i></div>
                        <span class="nav-text">Published Lists</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('program_coordinator.course_equivalencies.*') ? 'active' : '' }}">
                    <a href="{{ route('program_coordinator.course_equivalencies.view') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-search"></i></div>
                        <span class="nav-text">All Course Mappings</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('program_coordinator.history') || request()->routeIs('program_coordinator.show_request') ? 'active' : '' }}">
                    <a href="{{ route('program_coordinator.history') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-history"></i></div>
                        <span class="nav-text">Decision History</span>
                    </a>
                </li>
            @endif

            {{-- Resource Person Menu --}}
            @if(Auth::user()->role == 'resource_person')
                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-inbox"></i>
                        Requests
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('resource_person.equivalency_requests.*') ? 'active' : '' }}">
                    <a href="{{ route('resource_person.equivalency_requests.index') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-inbox"></i></div>
                        <span class="nav-text">Equivalency Requests</span>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-file-pdf"></i>
                        Syllabus
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('resource_person.syllabi.*') ? 'active' : '' }}">
                    <a href="{{ route('resource_person.syllabi.index') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-file-pdf"></i></div>
                        <span class="nav-text">Degree Syllabus</span>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-cogs"></i>
                        Management
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('resource_person.equivalency_lists.published') || request()->routeIs('resource_person.equivalency_lists.index') || request()->routeIs('resource_person.equivalency_lists.edit') ? 'active' : '' }}">
                    <a href="{{ route('resource_person.equivalency_lists.published') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-clipboard-list"></i></div>
                        <span class="nav-text">Equivalency Lists</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('resource_person.course_equivalencies.*') ? 'active' : '' }}">
                    <a href="{{ route('resource_person.course_equivalencies.view') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-search"></i></div>
                        <span class="nav-text">All Course Mappings</span>
                    </a>
                </li>
            @endif

            {{-- HEA Personnel Menu --}}
            @if(Auth::user()->role == 'hea_personnel')
                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-stamp"></i>
                        Endorsement
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('hea.equivalency_lists.pending') || request()->routeIs('hea.equivalency_lists.review') ? 'active' : '' }}">
                    <a href="{{ route('hea.equivalency_lists.pending') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-inbox"></i></div>
                        <span class="nav-text">Pending Endorsements</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('hea.equivalency_lists.published_view') ? 'active' : '' }}">
                    <a href="{{ route('hea.equivalency_lists.published_view') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-clipboard-list"></i></div>
                        <span class="nav-text">Equivalency Lists</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('hea.course_equivalencies.view') ? 'active' : '' }}">
                    <a href="{{ route('hea.course_equivalencies.view') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-list-alt"></i></div>
                        <span class="nav-text">All Course Mappings</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('hea.semester_reminder') ? 'active' : '' }}">
                    <a href="{{ route('hea.semester_reminder') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-bell"></i></div>
                        <span class="nav-text">Semester Reminder</span>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-users-cog"></i>
                        User Management
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('hea.users.pending') ? 'active' : '' }}">
                    <a href="{{ route('hea.users.pending') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-user-clock"></i></div>
                        <span class="nav-text">Pending Approvals</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('hea.users.active') ? 'active' : '' }}">
                    <a href="{{ route('hea.users.active') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-user-check"></i></div>
                        <span class="nav-text">Active Staff</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('hea.users.index') ? 'active' : '' }}">
                    <a href="{{ route('hea.users.index') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-users-cog"></i></div>
                        <span class="nav-text">User Overview</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('hea.program_groups.index') || request()->routeIs('hea.program_groups.save') || request()->routeIs('hea.staff_assignments.edit') ? 'active' : '' }}">
                    <a href="{{ route('hea.program_groups.index') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-layer-group"></i></div>
                        <span class="nav-text">Program Groups</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('hea.program_groups.assignments') ? 'active' : '' }}">
                    <a href="{{ route('hea.program_groups.assignments') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-clipboard-list"></i></div>
                        <span class="nav-text">Group Assignments</span>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-server"></i>
                        System
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('hea.applications.index') ? 'active' : '' }}">
                    <a href="{{ route('hea.applications.index') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-file-signature"></i></div>
                        <span class="nav-text">All Applications</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('hea.logs.index') ? 'active' : '' }}">
                    <a href="{{ route('hea.logs.index') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-history"></i></div>
                        <span class="nav-text">System Logs</span>
                    </a>
                </li>
            @endif

            {{-- Admin Menu --}}
            @if(Auth::user()->current_role == 'admin')
                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-users-cog"></i>
                        User Management
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.hea.approvals') ? 'active' : '' }}">
                    <a href="{{ route('admin.hea.approvals') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-user-check"></i></div>
                        <span class="nav-text">HEA Approvals</span>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-shield-alt"></i>
                        Security
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.security.login-attempts') ? 'active' : '' }}">
                    <a href="{{ route('admin.security.login-attempts') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-sign-in-alt"></i></div>
                        <span class="nav-text">Login Attempts</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.security.sessions') ? 'active' : '' }}">
                    <a href="{{ route('admin.security.sessions') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-desktop"></i></div>
                        <span class="nav-text">Active Sessions</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.security.all-users') ? 'active' : '' }}">
                    <a href="{{ route('admin.security.all-users') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-users"></i></div>
                        <span class="nav-text">All Users</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.security.locked-accounts') ? 'active' : '' }}">
                    <a href="{{ route('admin.security.locked-accounts') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-lock"></i></div>
                        <span class="nav-text">Locked Accounts</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.security.access-logs') ? 'active' : '' }}">
                    <a href="{{ route('admin.security.access-logs') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-door-open"></i></div>
                        <span class="nav-text">Access Logs</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.security.security-events') ? 'active' : '' }}">
                    <a href="{{ route('admin.security.security-events') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-shield-virus"></i></div>
                        <span class="nav-text">Security Events</span>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="nav-section-title">
                        <i class="fas fa-edit"></i>
                        Content
                    </span>
                </li>
                <li class="nav-item {{ request()->routeIs('admin.content.terms.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.content.terms.index') }}" class="nav-link">
                        <div class="nav-icon"><i class="fas fa-file-contract"></i></div>
                        <span class="nav-text">Terms & Conditions</span>
                    </a>
                </li>
            @endif

            {{-- Common Profile Link --}}
            <li class="nav-section">
                <span class="nav-section-title">
                    <i class="fas fa-user-circle"></i>
                    Account
                </span>
            </li>
            <li class="nav-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                <a href="{{ route('profile.show') }}" class="nav-link">
                    <div class="nav-icon"><i class="fas fa-user-edit"></i></div>
                    <span class="nav-text">My Profile</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="footer-content">
            <div class="system-version">
                <span class="version-label">Version</span>
                <span class="version-number">2.0.0</span>
            </div>
            <div class="footer-divider"></div>
            <div class="copyright">
                <span>&copy; {{ date('Y') }} UiTM</span>
            </div>
        </div>
    </div>
</nav>
