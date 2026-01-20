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

        /* IMPORTANT: Disable card hover effects inside modals to prevent flickering */
        .modal .card,
        .modal .card:hover,
        .modal .card:focus,
        .modal .card:active,
        .modal-content .card,
        .modal-content .card:hover,
        .modal-content .card:focus,
        .modal-content .card:active {
            transform: none !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
        }

        /* Disable hover animations on elements inside modal-body only */
        .modal-body .card,
        .modal-body .card:hover {
            transform: none !important;
            transition: none !important;
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

        /* ============================================
           ENHANCED NAVBAR STYLES
           ============================================ */

        .top-navbar {
            background: #ffffff !important;
            border-bottom: 1px solid #e5e7eb !important;
            padding: 0 !important;
            min-height: 70px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0 24px;
            min-height: 70px;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-right .dropdown {
            position: relative;
        }

        .navbar-right .dropdown-menu {
            z-index: 1050 !important;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .sidebar-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .sidebar-toggle:active {
            transform: translateY(0);
        }

        .sidebar-toggle i {
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        /* Page Title Section */
        .page-info {
            display: flex;
            flex-direction: column;
        }

        .page-greeting {
            font-size: 0.8rem;
            color: #9ca3af;
            font-weight: 500;
            line-height: 1.2;
        }

        .page-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.3;
        }

        /* Notification Bell Button */
        .notification-btn {
            position: relative;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none !important;
        }

        .notification-btn:hover {
            border-color: #667eea;
            background: #f8f7ff;
        }

        .notification-btn i {
            font-size: 1.15rem;
            color: #6b7280;
            transition: all 0.3s ease;
        }

        .notification-btn:hover i {
            color: #667eea;
        }

        .notification-btn.has-notifications i {
            animation: bellShake 0.5s ease-in-out;
        }

        @keyframes bellShake {
            0%, 100% { transform: rotate(0); }
            25% { transform: rotate(15deg); }
            50% { transform: rotate(-15deg); }
            75% { transform: rotate(10deg); }
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 10px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        }

        /* User Profile Button */
        .user-profile-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 16px 6px 6px;
            border-radius: 50px;
            border: 2px solid #e5e7eb;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none !important;
        }

        .user-profile-btn:hover {
            border-color: #667eea;
            background: #f8f7ff;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e5e7eb;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1f2937;
            line-height: 1.2;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-role {
            font-size: 0.7rem;
            color: #9ca3af;
            font-weight: 500;
            text-transform: capitalize;
        }

        .dropdown-chevron {
            color: #9ca3af;
            font-size: 0.7rem;
            margin-left: 4px;
            transition: transform 0.3s ease;
        }

        .user-profile-btn[aria-expanded="true"] .dropdown-chevron {
            transform: rotate(180deg);
        }

        /* Enhanced Dropdown Menus */
        .navbar-dropdown {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            padding: 8px;
            margin-top: 12px !important;
            animation: dropdownFadeIn 0.2s ease;
            z-index: 1050 !important;
        }

        @keyframes dropdownFadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .navbar-dropdown .dropdown-item {
            border-radius: 10px;
            padding: 12px 16px;
            font-weight: 500;
            color: #4b5563;
            transition: all 0.2s ease;
        }

        .navbar-dropdown .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            color: #667eea;
        }

        .navbar-dropdown .dropdown-item i {
            width: 20px;
            margin-right: 10px;
            color: #9ca3af;
        }

        .navbar-dropdown .dropdown-item:hover i {
            color: #667eea;
        }

        .navbar-dropdown .dropdown-divider {
            margin: 8px 0;
            border-color: #e5e7eb;
        }

        .dropdown-item-danger:hover {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%) !important;
            color: #dc2626 !important;
        }

        .dropdown-item-danger:hover i {
            color: #dc2626 !important;
        }

        /* Notification Dropdown Styles */
        .notification-dropdown {
            width: 380px;
            max-height: 480px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            padding: 0;
            overflow: hidden;
            z-index: 1050 !important;
        }

        .notification-dropdown-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-dropdown-header h6 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
        }

        .mark-all-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            font-size: 0.75rem;
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .mark-all-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .notification-list {
            max-height: 340px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            padding: 16px 20px;
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.2s ease;
            text-decoration: none !important;
            color: inherit !important;
        }

        .notification-item:hover {
            background: #f9fafb;
        }

        .notification-item.unread {
            background: linear-gradient(135deg, #eff6ff 0%, #f0f5ff 100%);
            border-left: 3px solid #667eea;
        }

        .notification-item.unread:hover {
            background: linear-gradient(135deg, #e0e7ff 0%, #e5e7ff 100%);
        }

        .notification-icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
            flex-shrink: 0;
        }

        .notification-icon-wrapper.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
        }

        .notification-icon-wrapper.info {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #2563eb;
        }

        .notification-icon-wrapper.primary {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #667eea;
        }

        .notification-icon-wrapper.warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #d97706;
        }

        .notification-icon-wrapper.default {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #6b7280;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notification-title .new-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .notification-message {
            font-size: 0.8rem;
            color: #6b7280;
            line-height: 1.4;
            margin-bottom: 6px;
        }

        .notification-time {
            font-size: 0.7rem;
            color: #9ca3af;
        }

        .notification-dropdown-footer {
            padding: 14px 20px;
            background: #f9fafb;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .notification-dropdown-footer a {
            color: #667eea;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .notification-dropdown-footer a:hover {
            color: #764ba2;
        }

        .notification-empty {
            padding: 40px 20px;
            text-align: center;
        }

        .notification-empty i {
            font-size: 3rem;
            color: #d1d5db;
            margin-bottom: 12px;
        }

        .notification-empty p {
            color: #9ca3af;
            margin: 0;
            font-size: 0.9rem;
        }

        /* Divider */
        .nav-divider {
            width: 1px;
            height: 32px;
            background: #e5e7eb;
            margin: 0 8px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-info {
                display: none;
            }

            .user-info {
                display: none;
            }

            .user-profile-btn {
                padding: 6px;
                border-radius: 50%;
            }

            .notification-dropdown {
                width: 320px;
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
            <nav class="navbar navbar-expand-lg top-navbar">
                <div class="navbar-content">
                    <!-- Left Section -->
                    <div class="navbar-left">
                        <button type="button" id="sidebarCollapse" class="sidebar-toggle">
                            <i class="fas fa-bars"></i>
                        </button>

                        <div class="page-info">
                            <span class="page-greeting">
                                @php
                                    $hour = now()->hour;
                                    if ($hour < 12) {
                                        echo 'Good Morning';
                                    } elseif ($hour < 17) {
                                        echo 'Good Afternoon';
                                    } else {
                                        echo 'Good Evening';
                                    }
                                @endphp
                            </span>
                            <span class="page-title">{{ Auth::user()->name }}</span>
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="navbar-right">
                        <!-- Notification Bell -->
                        <div class="dropdown">
                            <a href="#" class="notification-btn {{ $navUnreadCount > 0 ? 'has-notifications' : '' }}" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="notificationDropdown">
                                <i class="fas fa-bell"></i>
                                @if($navUnreadCount > 0)
                                    <span class="notification-badge">{{ $navUnreadCount > 99 ? '99+' : $navUnreadCount }}</span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                                <div class="notification-dropdown-header">
                                    <h6><i class="fas fa-bell me-2"></i>Notifications</h6>
                                    @if($navUnreadCount > 0)
                                        <button class="mark-all-btn" onclick="markAllAsRead(event)">
                                            <i class="fas fa-check-double me-1"></i>Mark all read
                                        </button>
                                    @endif
                                </div>
                                <div class="notification-list">
                                    @forelse($navNotifications as $notification)
                                        <a href="{{ route('notifications.read', $notification) }}" class="notification-item {{ !$notification->is_read ? 'unread' : '' }}">
                                            <div class="notification-icon-wrapper @switch($notification->type) @case('request_reviewed') success @break @case('new_mapping') info @break @case('syllabus_received') primary @break @case('new_request') warning @break @default default @endswitch">
                                                @switch($notification->type)
                                                    @case('request_reviewed')
                                                        <i class="fas fa-check"></i>
                                                        @break
                                                    @case('new_mapping')
                                                        <i class="fas fa-link"></i>
                                                        @break
                                                    @case('syllabus_received')
                                                        <i class="fas fa-file-pdf"></i>
                                                        @break
                                                    @case('new_request')
                                                        <i class="fas fa-inbox"></i>
                                                        @break
                                                    @default
                                                        <i class="fas fa-info"></i>
                                                @endswitch
                                            </div>
                                            <div class="notification-content">
                                                <div class="notification-title">
                                                    {{ $notification->title }}
                                                    @if(!$notification->is_read)
                                                        <span class="new-badge">New</span>
                                                    @endif
                                                </div>
                                                <p class="notification-message">{{ Str::limit($notification->message, 80) }}</p>
                                                <span class="notification-time">
                                                    <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="notification-empty">
                                            <i class="fas fa-bell-slash"></i>
                                            <p>No notifications yet</p>
                                        </div>
                                    @endforelse
                                </div>
                                @if($navNotifications->count() > 0)
                                    <div class="notification-dropdown-footer">
                                        <a href="{{ route('notifications.index') }}">
                                            View All Notifications <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="nav-divider"></div>

                        <!-- User Profile Dropdown -->
                        <div class="dropdown">
                            <a href="#" class="user-profile-btn" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                @if (Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Photo" class="user-avatar">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff" alt="Photo" class="user-avatar">
                                @endif
                                <div class="user-info">
                                    <span class="user-name">{{ Auth::user()->name }}</span>
                                    <span class="user-role">
                                        @php
                                            $roleMap = [
                                                'student' => 'Student',
                                                'academic_advisor' => 'Academic Advisor',
                                                'coordinator' => 'Coordinator',
                                                'program_coordinator' => 'Program Coordinator',
                                                'resource_person' => 'Resource Person',
                                                'external_lecturer' => 'External Lecturer',
                                                'hea_personnel' => 'HEA Personnel',
                                                'admin' => 'Administrator'
                                            ];
                                            echo $roleMap[Auth::user()->role] ?? ucwords(str_replace('_', ' ', Auth::user()->role));
                                        @endphp
                                    </span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-chevron"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end navbar-dropdown">
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user"></i>My Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('notifications.index') }}">
                                    <i class="fas fa-bell"></i>Notifications
                                    @if($navUnreadCount > 0)
                                        <span class="badge bg-danger rounded-pill ms-auto">{{ $navUnreadCount }}</span>
                                    @endif
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item dropdown-item-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>{{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </div>
                        </div>
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

        // Mark all notifications as read
        function markAllAsRead(event) {
            event.preventDefault();
            event.stopPropagation();

            $.ajax({
                url: '{{ route("notifications.mark-all-read") }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Remove unread styling from all notifications
                    $('.notification-item').removeClass('bg-light-blue');
                    $('.notification-item .badge.bg-primary.rounded-pill').remove();

                    // Hide the badge count
                    $('.notification-count-badge').remove();

                    // Hide the "Mark all read" link
                    $('.mark-all-read').hide();
                },
                error: function(xhr) {
                    console.error('Failed to mark notifications as read');
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
