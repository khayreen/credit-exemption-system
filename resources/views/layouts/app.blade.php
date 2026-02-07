<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'UiTM Credit Exemption') }}</title>
    
    <!-- Fonts - IBM Plex Sans & Mono for Industrial Design -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    @stack('styles')

    <style>
        /* ============================================
           INDUSTRIAL DESIGN SYSTEM - CSS VARIABLES
           ============================================ */
        :root {
            /* UiTM Color Palette */
            --uitm-primary: #1e3a8a;
            --uitm-primary-dark: #1e293b;
            --uitm-primary-light: #3b82f6;
            --uitm-red: #dc2626;
            --uitm-amber: #f59e0b;
            --uitm-green: #10b981;
            --uitm-purple: #7c3aed;

            /* Neutral Scale */
            --neutral-900: #171717;
            --neutral-800: #262626;
            --neutral-700: #404040;
            --neutral-600: #525252;
            --neutral-500: #737373;
            --neutral-400: #a3a3a3;
            --neutral-300: #d4d4d4;
            --neutral-200: #e5e5e5;
            --neutral-100: #f5f5f5;
            --neutral-50: #fafafa;

            /* Sidebar Dimensions */
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 0px;
        }

        /* ============================================
           BASE STYLES
           ============================================ */
        body {
            background: var(--neutral-100);
            font-family: 'IBM Plex Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--neutral-800);
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        /* ============================================
           INDUSTRIAL SIDEBAR STYLES
           ============================================ */
        #sidebar.industrial-sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--uitm-primary-dark) 0%, var(--uitm-primary) 100%);
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
            position: fixed;
            height: 100vh;
            z-index: 999;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        #sidebar.industrial-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        #sidebar.industrial-sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        #sidebar.industrial-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        #sidebar.industrial-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        #sidebar.active {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        /* Sidebar Brand Header */
        .sidebar-brand {
            position: relative;
            padding: 1.5rem;
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .brand-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 20px 20px;
            pointer-events: none;
        }

        .brand-content {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--uitm-amber) 0%, #d97706 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-title {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            letter-spacing: -0.02em;
            line-height: 1.1;
        }

        .logo-subtitle {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 2px;
        }

        /* Sidebar Profile Section */
        .sidebar-profile {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }

        .profile-avatar-wrapper {
            position: relative;
            flex-shrink: 0;
        }

        .profile-avatar {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .profile-status-indicator {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 14px;
            height: 14px;
            background: var(--uitm-green);
            border-radius: 50%;
            border: 3px solid var(--uitm-primary-dark);
            box-shadow: 0 2px 6px rgba(16, 185, 129, 0.5);
        }

        .profile-info {
            flex: 1;
            min-width: 0;
        }

        .profile-name {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            color: white;
            margin: 0 0 0.375rem 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-role {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 6px;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .profile-role i {
            font-size: 0.6rem;
        }

        /* Role-specific colors */
        .profile-role.role-student {
            background: linear-gradient(135deg, var(--uitm-primary-light), #2563eb);
            color: white;
        }

        .profile-role.role-advisor {
            background: linear-gradient(135deg, var(--uitm-green), #059669);
            color: white;
        }

        .profile-role.role-coordinator {
            background: linear-gradient(135deg, var(--uitm-amber), #d97706);
            color: white;
        }

        .profile-role.role-resource {
            background: linear-gradient(135deg, var(--uitm-purple), #6d28d9);
            color: white;
        }

        .profile-role.role-external {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
        }

        .profile-role.role-hea {
            background: linear-gradient(135deg, var(--uitm-red), #b91c1c);
            color: white;
        }

        .profile-role.role-admin {
            background: linear-gradient(135deg, var(--neutral-700), var(--neutral-900));
            color: white;
        }

        /* Sidebar Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        /* Section Headers */
        .nav-section {
            padding: 0.75rem 1.25rem 0.5rem;
        }

        .nav-section-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--uitm-amber);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .nav-section-title i {
            font-size: 0.6rem;
            opacity: 0.8;
        }

        /* Navigation Items */
        .nav-item {
            margin: 2px 0.75rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none !important;
            border-radius: 10px;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--uitm-amber);
            transform: scaleY(0);
            transition: transform 0.2s ease;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .nav-link:hover::before {
            transform: scaleY(1);
        }

        .nav-item.active .nav-link {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .nav-item.active .nav-link::before {
            transform: scaleY(1);
        }

        .nav-icon {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .nav-link:hover .nav-icon,
        .nav-item.active .nav-link .nav-icon {
            background: var(--uitm-amber);
            color: var(--uitm-primary-dark);
            box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3);
        }

        .nav-text {
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            flex: 1;
        }

        .nav-external {
            font-size: 0.65rem;
            opacity: 0.5;
            margin-left: auto;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1rem 1.25rem;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .system-version {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .version-label {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.6rem;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .version-number {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.7rem;
            color: var(--uitm-amber);
            font-weight: 600;
            background: rgba(245, 158, 11, 0.15);
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
        }

        .footer-divider {
            width: 1px;
            height: 16px;
            background: rgba(255, 255, 255, 0.2);
        }

        .copyright {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.5);
        }

        /* ============================================
           CONTENT AREA STYLES
           ============================================ */
        #content {
            width: 100%;
            padding: 0;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-left: var(--sidebar-width);
            background: var(--neutral-100);
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

        .stat-card .stat-icon.icon-blue { background-color: #eef5ff; color: var(--uitm-primary); }
        .stat-card .stat-icon.icon-green { background-color: #e6f9f1; color: var(--uitm-green); }
        .stat-card .stat-icon.icon-orange { background-color: #fff4e6; color: var(--uitm-amber); }
        .stat-card .stat-icon.icon-red { background-color: #fdeeee; color: var(--uitm-red); }
        .stat-card .stat-icon.icon-purple { background-color: #f3e8ff; color: var(--uitm-purple); }
        
        /* Enhanced Top Navbar */
        .top-navbar {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 2px solid #e2e8f0;
            backdrop-filter: blur(10px);
        }
        
        #sidebarCollapse {
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-dark));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        #sidebarCollapse:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 58, 138, 0.3);
        }
        
        /* Enhanced Profile Section */
        .navbar-nav .dropdown-toggle {
            background: rgba(30, 58, 138, 0.1);
            border-radius: 25px;
            padding: 8px 15px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .navbar-nav .dropdown-toggle:hover {
            border-color: var(--uitm-primary);
            background: rgba(30, 58, 138, 0.15);
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
            background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }
        
        /* Enhanced Buttons - UiTM Theme */
        .btn-primary {
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-dark));
            border: none;
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--uitm-primary-light), var(--uitm-primary));
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 58, 138, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid var(--uitm-primary);
            color: var(--uitm-primary);
            border-radius: 12px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--uitm-primary), var(--uitm-primary-dark));
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 58, 138, 0.3);
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
            border-top: 4px solid var(--uitm-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        /* Responsive Enhancements */
        /* ============================================
           RESPONSIVE SIDEBAR STYLES
           ============================================ */
        @media (max-width: 991px) {
            :root {
                --sidebar-width: 260px;
            }

            .sidebar-brand {
                padding: 1.25rem;
            }

            .logo-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .logo-title {
                font-size: 1.25rem;
            }

            .sidebar-profile {
                padding: 1.25rem;
            }

            .profile-avatar {
                width: 44px;
                height: 44px;
            }

            .profile-name {
                font-size: 0.875rem;
            }
        }

        @media (max-width: 768px) {
            #sidebar.industrial-sidebar {
                transform: translateX(-100%);
                box-shadow: none;
            }

            #sidebar.industrial-sidebar.active {
                transform: translateX(0);
                box-shadow: 4px 0 30px rgba(0, 0, 0, 0.3);
            }

            #content {
                margin-left: 0;
            }

            .top-navbar {
                padding: 10px 15px;
            }

            /* Mobile sidebar overlay */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 998;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        @media (max-width: 576px) {
            .sidebar-brand {
                padding: 1rem;
            }

            .logo-icon {
                width: 36px;
                height: 36px;
                font-size: 0.9rem;
                border-radius: 10px;
            }

            .logo-title {
                font-size: 1.125rem;
            }

            .logo-subtitle {
                font-size: 0.6rem;
            }

            .sidebar-profile {
                padding: 1rem;
            }

            .profile-avatar {
                width: 40px;
                height: 40px;
                border-radius: 10px;
            }

            .profile-status-indicator {
                width: 12px;
                height: 12px;
            }

            .profile-name {
                font-size: 0.8rem;
            }

            .profile-role {
                font-size: 0.6rem;
                padding: 0.2rem 0.5rem;
            }

            .nav-section {
                padding: 0.625rem 1rem 0.375rem;
            }

            .nav-section-title {
                font-size: 0.6rem;
            }

            .nav-item {
                margin: 2px 0.5rem;
            }

            .nav-link {
                padding: 0.625rem 0.875rem;
            }

            .nav-icon {
                width: 28px;
                height: 28px;
                font-size: 0.75rem;
            }

            .nav-text {
                font-size: 0.8rem;
            }

            .sidebar-footer {
                padding: 0.875rem 1rem;
            }

            .footer-content {
                gap: 0.75rem;
            }

            .version-label {
                font-size: 0.55rem;
            }

            .version-number {
                font-size: 0.6rem;
                padding: 0.15rem 0.4rem;
            }

            .copyright {
                font-size: 0.6rem;
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
            background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
        }

        .sidebar-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.4);
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
            border-color: var(--uitm-primary);
            background: #eff6ff;
        }

        .notification-btn i {
            font-size: 1.15rem;
            color: #6b7280;
            transition: all 0.3s ease;
        }

        .notification-btn:hover i {
            color: var(--uitm-primary);
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
            border-color: var(--uitm-primary);
            background: #eff6ff;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.15);
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
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.08) 0%, rgba(30, 41, 59, 0.08) 100%);
            color: var(--uitm-primary);
        }

        .navbar-dropdown .dropdown-item i {
            width: 20px;
            margin-right: 10px;
            color: #9ca3af;
        }

        .navbar-dropdown .dropdown-item:hover i {
            color: var(--uitm-primary);
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
            background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
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
            border-left: 3px solid var(--uitm-primary);
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
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: var(--uitm-primary);
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
            background: linear-gradient(135deg, var(--uitm-primary) 0%, var(--uitm-primary-dark) 100%);
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
            color: var(--uitm-primary);
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .notification-dropdown-footer a:hover {
            color: var(--uitm-primary-dark);
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
