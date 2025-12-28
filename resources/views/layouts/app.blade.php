<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UiTM Credit Exemption') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    @stack('styles')

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            background: #fff;
            color: #333;
            transition: all 0.3s;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            position: fixed;
            height: 100%;
            z-index: 999;
            overflow-y: auto;
        }
        #sidebar.active {
            margin-left: -260px;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: #fff;
            border-bottom: 1px solid #eee;
        }
        #sidebar ul.components {
            padding: 15px 0;
        }
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 0.95em;
            display: block;
            color: #555;
            font-weight: 500;
            border-left: 3px solid transparent;
            text-decoration: none;
        }
        #sidebar ul li a:hover {
            color: #0d6efd;
            background: #f8f9fa;
            text-decoration: none !important;
        }
        #sidebar ul li.active > a, a[aria-expanded="true"] {
            color: #0d6efd;
            background: #eef5ff;
            border-left: 3px solid #0d6efd;
            text-decoration: none !important;
        }
        #sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        #content {
            width: 100%;
            padding: 0;
            min-height: 100vh;
            transition: all 0.3s;
            margin-left: 260px;
        }
        #content.active {
            margin-left: 0;
        }
        .top-navbar {
            padding: 15px 30px;
            background: #fff;
            border: none;
            border-radius: 0;
            margin-bottom: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }
        .profile-photo-sm {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .stat-card {
            padding: 20px;
        }
        .stat-card .stat-icon {
            font-size: 2rem;
            padding: 15px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .stat-card .stat-icon.icon-blue { background-color: #eef5ff; color: #0d6efd; }
        .stat-card .stat-icon.icon-green { background-color: #e6f9f1; color: #198754; }
        .stat-card .stat-icon.icon-orange { background-color: #fff4e6; color: #fd7e14; }
        .stat-card .stat-icon.icon-red { background-color: #fdeeee; color: #dc3545; }
        .stat-card .stat-icon.icon-purple { background-color: #f3e8ff; color: #6f42c1; }
        
        /* Enhanced University Professional Styling */
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        /* Enhanced Sidebar Styling */
        #sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            border-right: 3px solid #e2e8f0;
        }
        
        #sidebar .sidebar-header {
            background: #ffffff;
            color: #333;
            border-bottom: 1px solid #eee;
            margin: 0;
            padding: 20px;
        }
        
        #sidebar .sidebar-header h4 {
            margin-bottom: 0;
            font-weight: 700;
            color: #333;
        }
        
        #sidebar .sidebar-header hr {
            border-color: #eee;
            margin: 15px 0;
        }
        
        #sidebar .sidebar-header h5 {
            font-weight: 600;
            margin-top: 10px;
            color: #333;
        }
        
        #sidebar .sidebar-header p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        /* Enhanced Navigation Links */
        #sidebar ul li a {
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 2px 15px;
            padding: 12px 15px;
            text-decoration: none !important;
        }
        
        #sidebar ul li a:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            text-decoration: none !important;
        }
        
        #sidebar ul li.active > a {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-left: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            text-decoration: none !important;
        }
        
        /* Section Headers in Sidebar */
        #sidebar ul p {
            font-weight: 700;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.75rem;
        }
        
        /* Enhanced Top Navbar */
        .top-navbar {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 2px solid #e2e8f0;
            backdrop-filter: blur(10px);
        }
        
        #sidebarCollapse {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        
        #sidebarCollapse:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        /* Enhanced Profile Section */
        .navbar-nav .dropdown-toggle {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 25px;
            padding: 8px 15px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .dropdown-toggle:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.15);
        }
        
        /* Enhanced Cards */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
        }
        
        /* Notification Badges */
        .notification-badge {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 8px;
            display: inline-block;
            min-width: 20px;
            text-align: center;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        
        /* Enhanced Main Content Area */
        #content {
            background: transparent;
        }
        
        /* University Branding Elements */
        .university-brand {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }
        
        /* Enhanced Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a67d8, #6b46c1);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        /* Loading States */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            display: none;
        }
        
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            border: 4px solid #e2e8f0;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        /* Responsive Enhancements */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.active {
                transform: translateX(0);
            }

            #content {
                margin-left: 0;
            }

            .top-navbar {
                padding: 10px 15px;
            }
        }

        /* Fix for giant pagination arrows */
        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
            line-height: 1.5;
        }

        .pagination .page-link svg {
            width: 1em;
            height: 1em;
            vertical-align: middle;
        }

        /* Ensure pagination icons don't inherit FontAwesome sizing */
        .pagination .page-link i,
        .pagination .page-link .fa,
        .pagination .page-link .fas,
        .pagination .page-link .far {
            font-size: 1rem !important;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        @auth
            @include('layouts.partials.sidebar')
        @endauth
        
        <div id="content">
            @auth
            <nav class="navbar navbar-expand-lg navbar-light bg-white top-navbar">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-light me-3">
                        <i class="fas fa-align-left"></i>
                    </button>
                    
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @if (Auth::user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Photo" class="profile-photo-sm me-2">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="Photo" class="profile-photo-sm me-2">
                                    @endif
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">My Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            @endauth
            
            <main class="p-4">
                 @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar, #content').toggleClass('active');
            });
        });

        // JavaScript functions for sidebar links
        function openAcademicCalendar() {
            window.open('https://hea.uitm.edu.my/v4/index.php/calendars/academic-calendar', '_blank');
        }

        function openAcademicHandbook() {
            window.open('https://www.uitm.edu.my/index.php/en/academic/handbook', '_blank');
        }

        function openLiveChat() {
            // You can integrate with actual chat service like Tawk.to, Zendesk, etc.
            alert('Live chat support will be available soon. Please contact exemption@uitm.edu.my for immediate assistance.');
        }

        function openVideoGuide() {
            // You can link to actual video tutorials
            window.open('https://www.youtube.com/playlist?list=PLexample', '_blank');
        }

        // Enhanced sidebar interactions
        $(document).ready(function() {
            // Add smooth hover effects for sidebar items
            $('#sidebar ul li a').hover(
                function() {
                    $(this).css('transform', 'translateX(5px)');
                },
                function() {
                    $(this).css('transform', 'translateX(0)');
                }
            );

            // Add notification badge functionality
            updateNotificationBadges();
        });

        function updateNotificationBadges() {
            // Example: Add notification badges for pending items
            // This would typically be populated from your backend
            @if(Auth::check() && Auth::user()->role === 'student')
                // You can pass this data from the controller if needed
                const pendingCount = 0; // This should be passed from backend
            @else
                const pendingCount = 0;
            @endif
            
            if (pendingCount > 0) {
                const statusLink = $('a[href*="application.status"]');
                if (statusLink.length && !statusLink.find('.notification-badge').length) {
                    statusLink.append(`<span class="notification-badge">${pendingCount}</span>`);
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
