<nav id="sidebar">
    <div class="sidebar-header text-center">
        <h4 class="mt-2">UiTM Credit Exemption</h4>
        <hr class="mx-3">
        @if (Auth::user()->profile_photo_path)
            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile Photo" class="img-fluid rounded-circle my-3" style="width: 100px; height: 100px; object-fit: cover;">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=100&background=random" alt="Default Profile Photo" class="img-fluid rounded-circle my-3">
        @endif
        <h5>{{ Auth::user()->name }}</h5>
        <p class="text-muted">
            @php
                $roleDisplay = Auth::user()->role;
                // Format role for display
                $roleMap = [
                    'student' => 'Student',
                    'academic_advisor' => 'Academic Advisor',
                    'coordinator' => 'Coordinator',
                    'program_coordinator' => 'Program Coordinator',
                    'resource_person' => 'Resource Person',
                    'external_lecturer' => 'External Lecturer',
                    'hea_personnel' => 'HEA Personnel'
                ];
                echo $roleMap[$roleDisplay] ?? ucwords(str_replace('_', ' ', $roleDisplay));
            @endphp
        </p>
    </div>

    <ul class="list-unstyled components">
        <p class="px-4 text-muted"><small>MAIN MENU</small></p>
        
        {{-- FIX: Added a special case for the HEA Personnel role --}}
        @if(Auth::user()->role == 'hea_personnel')
            <li class="{{ request()->routeIs('hea.dashboard') ? 'active' : '' }}">
                <a href="{{ route('hea.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
        @elseif(in_array(Auth::user()->role, ['student', 'academic_advisor', 'coordinator', 'program_coordinator', 'resource_person']))
            <li class="{{ request()->routeIs(Auth::user()->role . '.dashboard') ? 'active' : '' }}">
                <a href="{{ route(Auth::user()->role . '.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
        @else
             <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> Dashboard</a>
            </li>
        @endif


        @if(Auth::user()->role == 'student')
            <li class="{{ request()->routeIs('student.application.create') ? 'active' : '' }}">
                <a href="{{ route('student.application.create') }}">
                    <i class="fas fa-plus-circle"></i> New Application
                </a>
            </li>
            <li class="{{ request()->routeIs('student.application.status') ? 'active' : '' }}">
                <a href="{{ route('student.application.status') }}">
                    <i class="fas fa-search"></i> Application Status
                </a>
            </li>
            <li class="{{ request()->routeIs('student.course_equivalencies.*') ? 'active' : '' }}">
                <a href="{{ route('student.course_equivalencies.index') }}">
                    <i class="fas fa-clipboard-list"></i> Course Equivalency Lists
                </a>
            </li>
            <li class="{{ request()->routeIs('student.equivalency.request.*') ? 'active' : '' }}">
                <a href="{{ route('student.equivalency.request.create') }}">
                    <i class="fas fa-file-signature"></i> Course Equivalency Request
                </a>
            </li>

            <p class="px-4 text-muted mt-4"><small>ACADEMIC RESOURCES</small></p>
            <li class="{{ request()->routeIs('student.terms') ? 'active' : '' }}">
                <a href="{{ route('student.terms') }}">
                    <i class="fas fa-file-contract"></i> Terms & Conditions
                </a>
            </li>
            <li>
                <a href="{{ \App\Models\SystemSetting::get('academic_calendar_url', 'https://uitm.edu.my/index.php/en/academic-calendar') }}" target="_blank">
                    <i class="fas fa-calendar-alt"></i> Academic Calendar
                </a>
            </li>
        @endif
        
        @if(Auth::user()->role == 'academic_advisor')
            <li class="{{ request()->routeIs('academic_advisor.my_students') ? 'active' : '' }}">
                <a href="{{ route('academic_advisor.my_students') }}">
                    <i class="fas fa-users"></i> My Students
                </a>
            </li>
            <li class="{{ request()->routeIs('academic_advisor.equivalency_lists.*') ? 'active' : '' }}">
                <a href="{{ route('academic_advisor.equivalency_lists.index') }}">
                    <i class="fas fa-clipboard-list"></i> Equivalency Lists
                </a>
            </li>
            <li class="{{ request()->routeIs('academic_advisor.course_equivalencies.*') ? 'active' : '' }}">
                <a href="{{ route('academic_advisor.course_equivalencies.view') }}">
                    <i class="fas fa-list-alt"></i> All Course Mappings
                </a>
            </li>
        @endif

        @if(Auth::user()->role == 'program_coordinator')
            <li class="{{ request()->routeIs('program_coordinator.dashboard') ? 'active' : '' }}">
                <a href="{{ route('program_coordinator.dashboard') }}">
                    <i class="fas fa-inbox"></i> Equivalency Requests
                </a>
            </li>

            <p class="px-4 text-muted mt-4"><small>EQUIVALENCY MANAGEMENT</small></p>
            <li class="{{ request()->routeIs('program_coordinator.equivalency_lists.index') ? 'active' : '' }}">
                <a href="{{ route('program_coordinator.equivalency_lists.index') }}">
                    <i class="fas fa-clipboard-check"></i> Published Lists
                </a>
            </li>
            <li class="{{ request()->routeIs('program_coordinator.equivalency_lists.drafts') ? 'active' : '' }}">
                <a href="{{ route('program_coordinator.equivalency_lists.drafts') }}">
                    <i class="fas fa-file-alt"></i> Draft Management
                </a>
            </li>
            <li class="{{ request()->routeIs('program_coordinator.pending_mappings.*') ? 'active' : '' }}">
                <a href="{{ route('program_coordinator.pending_mappings.index') }}">
                    <i class="fas fa-inbox"></i> Pending Mappings
                    @php
                        $pendingCount = \App\Models\PendingEquivalencyMapping::pending()->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-danger ms-2">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>
            <li class="{{ request()->routeIs('program_coordinator.course_equivalencies.*') ? 'active' : '' }}">
                <a href="{{ route('program_coordinator.course_equivalencies.view') }}">
                    <i class="fas fa-search"></i> All Course Mappings
                </a>
            </li>
        @endif

        @if(Auth::user()->role == 'resource_person')
            <li class="{{ request()->routeIs('resource_person.equivalency_lists.published') ? 'active' : '' }}">
                <a href="{{ route('resource_person.equivalency_lists.published') }}">
                    <i class="fas fa-clipboard-list"></i> Equivalency Lists
                </a>
            </li>
            <li class="{{ request()->routeIs('resource_person.equivalency_lists.index') || request()->routeIs('resource_person.equivalency_lists.edit') ? 'active' : '' }}">
                <a href="{{ route('resource_person.equivalency_lists.index') }}">
                    <i class="fas fa-list-alt"></i> My CS110 Lists
                </a>
            </li>
            <li class="{{ request()->routeIs('resource_person.equivalency_mappings.*') ? 'active' : '' }}">
                <a href="{{ route('resource_person.equivalency_mappings.create') }}">
                    <i class="fas fa-paper-plane"></i> Forward Mapping
                </a>
            </li>
        @endif

        @if(Auth::user()->role == 'hea_personnel')
             <p class="px-4 text-muted mt-4"><small>ENDORSEMENT & PUBLISHING</small></p>
             <li class="{{ request()->routeIs('hea.equivalency_lists.pending') || request()->routeIs('hea.equivalency_lists.review') ? 'active' : '' }}"><a href="{{ route('hea.equivalency_lists.pending') }}"><i class="fas fa-inbox"></i> Pending Endorsements</a></li>
             <li class="{{ request()->routeIs('hea.equivalency_lists.published_view') ? 'active' : '' }}"><a href="{{ route('hea.equivalency_lists.published_view') }}"><i class="fas fa-clipboard-list"></i> Equivalency Lists</a></li>
             <li class="{{ request()->routeIs('hea.course_equivalencies.view') ? 'active' : '' }}"><a href="{{ route('hea.course_equivalencies.view') }}"><i class="fas fa-clipboard-list"></i> All Course Mappings</a></li>

             <p class="px-4 text-muted mt-4"><small>SYSTEM MANAGEMENT</small></p>
             <li class="{{ request()->routeIs('hea.users.index') ? 'active' : '' }}"><a href="{{ route('hea.users.index') }}"><i class="fas fa-users-cog"></i> User Management</a></li>
             <li class="{{ request()->routeIs('hea.applications.index') ? 'active' : '' }}"><a href="{{ route('hea.applications.index') }}"><i class="fas fa-file-signature"></i> All Applications</a></li>
             <li class="{{ request()->routeIs('hea.logs.index') ? 'active' : '' }}"><a href="{{ route('hea.logs.index') }}"><i class="fas fa-history"></i> System Logs</a></li>
        @endif

        <li class="{{ request()->routeIs('profile.show') ? 'active' : '' }}">
            <a href="{{ route('profile.show') }}"><i class="fas fa-user-edit"></i> My Profile</a>
        </li>
    </ul>
</nav>
