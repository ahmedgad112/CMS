<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام إدارة العيادة')</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #0d9488;
            --primary-hover: #0f766e;
            --primary-dark: #115e59;
            --primary-light: #ccfbf1;
            --primary-soft: #f0fdfa;
            --accent-color: #06b6d4;
            --accent-hover: #0891b2;
            --bg-color: #f0fdfa;
            --surface-color: #ffffff;
            --text-color: #134e4a;
            --text-muted: #64748b;
            --border-color: #d1fae5;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #0ea5e9;
        }

        body {
            font-family: 'Segoe UI', 'Cairo', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #0d9488 0%, #0f766e 60%, #115e59 100%);
            color: white;
            position: sticky;
            top: 0;
            box-shadow: -4px 0 16px rgba(13, 148, 136, 0.15);
        }

        .sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 20% 10%, rgba(255, 255, 255, 0.08) 0%, transparent 40%),
                              radial-gradient(circle at 80% 90%, rgba(255, 255, 255, 0.06) 0%, transparent 40%);
            pointer-events: none;
        }

        .sidebar > div {
            position: relative;
            z-index: 1;
        }

        .sidebar .sidebar-header-row {
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.18);
        }

        .sidebar .sidebar-header-row h4 {
            font-weight: 700;
            font-size: 1.2rem;
            margin: 0;
            padding: 0;
            border: none;
            line-height: 1.3;
            letter-spacing: 0.2px;
        }

        .sidebar .sidebar-logo {
            object-fit: contain;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            padding: 4px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.88);
            padding: 11px 14px;
            margin: 3px 0;
            border-radius: 10px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            font-size: 0.92rem;
            position: relative;
        }

        .sidebar .nav-link i {
            width: 32px;
            height: 32px;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            font-size: 0.92rem;
            transition: all 0.25s ease;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.12);
            color: white;
            transform: translateX(-3px);
        }

        .sidebar .nav-link:hover i {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.18);
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            right: -16px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: #ffffff;
            border-radius: 4px 0 0 4px;
        }

        .sidebar .nav-link.active i {
            background: rgba(255, 255, 255, 0.28);
            color: #ffffff;
        }

        .sidebar .nav-item.mt-3.pt-3.border-top {
            border-color: rgba(255, 255, 255, 0.18) !important;
        }

        /* Main Content */
        .main-content {
            background-color: var(--bg-color);
            min-height: 100vh;
            padding-bottom: 2rem;
        }

        /* Navbar - Modern Design */
        .navbar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 1.5rem;
            margin-bottom: 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .navbar > .container-fluid {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem 0.75rem;
            max-width: 100%;
        }

        .navbar > .container-fluid > .d-flex.align-items-center.gap-3 {
            flex: 1 1 auto;
            min-width: 0;
        }

        .navbar-brand {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .navbar-brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
        }

        .navbar-brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .navbar-brand-text small {
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Mobile Menu Toggle - Enhanced */
        .mobile-menu-toggle {
            display: none;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            font-size: 1.1rem;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
        }

        .mobile-menu-toggle:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(13, 148, 136, 0.35);
        }

        /* Navbar Quick Actions */
        .navbar-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 0.5rem;
            flex: 0 1 auto;
            min-width: 0;
            max-width: 100%;
        }

        .navbar-icon-btn {
            position: relative;
            width: 42px;
            height: 42px;
            background: #f1f5f9;
            border: 1px solid transparent;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .navbar-icon-btn:hover {
            background: white;
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.18);
        }

        .navbar-icon-btn .badge-dot {
            position: absolute;
            top: 8px;
            left: 9px;
            width: 9px;
            height: 9px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }

        /* Clinic Switcher */
        .clinic-switcher {
            position: relative;
        }

        .clinic-switcher-toggle {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem 0.875rem;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1.5px solid #bfdbfe;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--primary-color);
            font-weight: 500;
        }

        .clinic-switcher-toggle:hover,
        .clinic-switcher.show .clinic-switcher-toggle {
            background: white;
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .clinic-switcher-toggle > i:first-child {
            font-size: 1rem;
        }

        .clinic-switcher-text {
            flex-direction: column;
            line-height: 1.2;
            text-align: right;
        }

        .clinic-switcher-label {
            font-size: 0.65rem;
            color: #64748b;
            font-weight: 500;
        }

        .clinic-switcher-value {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-color);
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .clinic-switcher-caret {
            font-size: 0.7rem;
            color: #94a3b8;
            transition: transform 0.2s;
        }

        .clinic-switcher.show .clinic-switcher-caret {
            transform: rotate(180deg);
        }

        .clinic-switcher-menu {
            position: absolute;
            top: calc(100% + 0.5rem);
            left: 0;
            min-width: 280px;
            max-height: 400px;
            overflow-y: auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            border: 1px solid var(--border-color);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
            z-index: 1050;
            padding: 0.5rem;
        }

        .clinic-switcher.show .clinic-switcher-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .clinic-switcher-header {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 0.25rem;
        }

        .clinic-switcher-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.75rem;
            color: #1e293b;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.875rem;
            border: none;
            background: transparent;
            width: 100%;
            text-align: right;
            cursor: pointer;
            transition: all 0.15s;
        }

        .clinic-switcher-item:hover {
            background: #f1f5f9;
            color: var(--primary-color);
        }

        .clinic-switcher-item.active {
            background: #eff6ff;
            color: var(--primary-color);
            font-weight: 600;
        }

        .clinic-switcher-item i:first-child {
            width: 18px;
            text-align: center;
            color: #94a3b8;
        }

        .clinic-switcher-item.active i:first-child,
        .clinic-switcher-item:hover i:first-child {
            color: var(--primary-color);
        }

        /* Clinic Indicator (read-only for branch users) */
        .clinic-indicator {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem 0.875rem;
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
            border: 1.5px solid #5eead4;
            border-radius: 10px;
            color: #0f766e;
        }

        .clinic-indicator > i {
            font-size: 1rem;
        }

        .clinic-indicator-label {
            font-size: 0.65rem;
            color: #14b8a6;
            font-weight: 500;
            line-height: 1.2;
        }

        .clinic-indicator-value {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f766e;
            line-height: 1.2;
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Date Display */
        .navbar-date {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem 0.875rem;
            background: #f1f5f9;
            border-radius: 10px;
            color: #475569;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .navbar-date i {
            color: var(--primary-color);
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.375rem 0.75rem 0.375rem 0.5rem;
            background: #f1f5f9;
            border: 1.5px solid transparent;
            border-radius: 24px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .user-dropdown-toggle:hover,
        .user-dropdown.show .user-dropdown-toggle {
            background: white;
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.18);
        }

        .user-avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(13, 148, 136, 0.3);
            flex-shrink: 0;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: var(--text-color);
            font-size: 0.875rem;
            max-width: 130px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-role {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 500;
        }

        .user-caret {
            color: #94a3b8;
            font-size: 0.75rem;
            transition: transform 0.2s;
            margin-right: 0.25rem;
        }

        .user-dropdown.show .user-caret {
            transform: rotate(180deg);
        }

        .user-dropdown-menu {
            position: absolute;
            top: calc(100% + 0.5rem);
            left: 0;
            min-width: 260px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            border: 1px solid var(--border-color);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1050;
            overflow: hidden;
        }

        .user-dropdown.show .user-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-header {
            padding: 1rem;
            background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-dropdown-header .user-avatar-circle {
            width: 48px;
            height: 48px;
            font-size: 1.1rem;
        }

        .user-dropdown-header-info {
            flex: 1;
            min-width: 0;
        }

        .user-dropdown-header-name {
            font-weight: 700;
            color: var(--text-color);
            font-size: 0.95rem;
            margin-bottom: 0.125rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-dropdown-header-email {
            font-size: 0.78rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-dropdown-body {
            padding: 0.5rem;
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.75rem;
            color: #475569;
            text-decoration: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s;
            border: none;
            background: transparent;
            width: 100%;
            text-align: right;
            cursor: pointer;
        }

        .user-dropdown-item i {
            width: 18px;
            text-align: center;
            color: #94a3b8;
            font-size: 0.95rem;
            transition: color 0.15s;
        }

        .user-dropdown-item:hover {
            background: #f1f5f9;
            color: var(--primary-color);
        }

        .user-dropdown-item:hover i {
            color: var(--primary-color);
        }

        .user-dropdown-item.danger:hover {
            background: #fee2e2;
            color: #dc2626;
        }

        .user-dropdown-item.danger:hover i {
            color: #dc2626;
        }

        .user-dropdown-divider {
            height: 1px;
            background: var(--border-color);
            margin: 0.5rem 0;
        }

        /* Page Title with breadcrumb feel */
        .page-title-wrapper {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .page-title-label {
            font-size: 0.72rem;
            color: #94a3b8;
            font-weight: 500;
            margin-bottom: 0.15rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .page-title-text {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-color);
        }

        /* Cards */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(13, 148, 136, 0.06);
            overflow: hidden;
            transition: box-shadow 0.25s ease, transform 0.25s ease;
        }

        .card:hover {
            box-shadow: 0 6px 18px rgba(13, 148, 136, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-bottom: none;
            font-weight: 600;
            position: relative;
        }

        .card-header h5 {
            margin: 0;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons - Enhanced Design */
        .btn {
            border-radius: 8px;
            padding: 0.625rem 1.25rem;
            font-weight: 500;
            font-size: 0.9375rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .btn:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover:before {
            width: 300px;
            height: 300px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.25);
        }

        .btn:disabled,
        .btn.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-hover) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            color: white;
        }

        .btn-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
            color: white;
        }

        /* Outline Buttons */
        .btn-outline-primary {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            box-shadow: none;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .btn-outline-success {
            background: transparent;
            border: 2px solid #10b981;
            color: #10b981;
            box-shadow: none;
        }

        .btn-outline-success:hover {
            background: #10b981;
            color: white;
            border-color: #10b981;
        }

        .btn-outline-danger {
            background: transparent;
            border: 2px solid #ef4444;
            color: #ef4444;
            box-shadow: none;
        }

        .btn-outline-danger:hover {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
        }

        .btn-outline-warning {
            background: transparent;
            border: 2px solid #f59e0b;
            color: #f59e0b;
            box-shadow: none;
        }

        .btn-outline-warning:hover {
            background: #f59e0b;
            color: white;
            border-color: #f59e0b;
        }

        .btn-outline-info {
            background: transparent;
            border: 2px solid #06b6d4;
            color: #06b6d4;
            box-shadow: none;
        }

        .btn-outline-info:hover {
            background: #06b6d4;
            color: white;
            border-color: #06b6d4;
        }

        .btn-outline-secondary {
            background: transparent;
            border: 2px solid #64748b;
            color: #64748b;
            box-shadow: none;
        }

        .btn-outline-secondary:hover {
            background: #64748b;
            color: white;
            border-color: #64748b;
        }

        /* Button Sizes */
        .btn-sm {
            padding: 0.4rem 0.875rem;
            font-size: 0.8125rem;
            border-radius: 6px;
            gap: 0.375rem;
        }

        .btn-lg {
            padding: 0.875rem 1.75rem;
            font-size: 1.0625rem;
            border-radius: 10px;
            gap: 0.625rem;
        }

        /* Button Groups */
        .btn-group {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .btn-group .btn {
            box-shadow: none;
            border-radius: 0;
            margin: 0;
        }

        .btn-group .btn:first-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .btn-group .btn:last-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .btn-group .btn:hover {
            transform: none;
            z-index: 1;
        }

        /* Icon Buttons */
        .btn i {
            font-size: 0.875em;
        }

        .btn-sm i {
            font-size: 0.75em;
        }

        .btn-lg i {
            font-size: 1.125em;
        }

        /* Link Buttons */
        .btn-link {
            background: transparent;
            color: var(--primary-color);
            box-shadow: none;
            text-decoration: none;
            padding: 0.5rem 0.75rem;
        }

        .btn-link:hover {
            background: rgba(13, 148, 136, 0.1);
            color: var(--primary-hover);
            transform: none;
            box-shadow: none;
        }

        /* Forms */
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid var(--border-color);
            padding: 0.5rem 0.75rem;
            transition: border-color 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
        }

        .form-label {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        /* Tables */
        .table {
            border: 1px solid var(--border-color);
            margin-bottom: 0;
        }

        .table thead {
            background: linear-gradient(180deg, #f0fdfa 0%, #ccfbf1 100%);
        }

        .table thead th {
            border-bottom: 2px solid var(--primary-light);
            padding: 0.85rem 0.75rem;
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.15s ease;
        }

        .table tbody tr:hover {
            background-color: #f0fdfa;
        }

        .table tbody td {
            padding: 0.85rem 0.75rem;
            vertical-align: middle;
            color: #1f2937;
        }

        /* Badges */
        .badge {
            padding: 0.35rem 0.65rem;
            border-radius: 4px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        /* Alerts */
        .alert {
            border-radius: 6px;
            border: 1px solid;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fee2e2;
            border-color: #ef4444;
            color: #991b1b;
        }

        .alert-info {
            background-color: #cffafe;
            border-color: #06b6d4;
            color: #164e63;
        }

        .alert-warning {
            background-color: #fef3c7;
            border-color: #f59e0b;
            color: #92400e;
        }

        /* Input Groups */
        .input-group .input-group-text {
            border: 1px solid var(--border-color);
            background-color: var(--primary-soft);
            color: var(--primary-dark);
        }

        /* Spacing */
        .mb-4 {
            margin-bottom: 2rem !important;
        }

        .mt-4 {
            margin-top: 2rem !important;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .p-4 {
            padding: 2rem !important;
        }

        .px-4 {
            padding-left: 2rem !important;
            padding-right: 2rem !important;
        }

        .pb-4 {
            padding-bottom: 2rem !important;
        }

        /* Gap utilities */
        .gap-2 {
            gap: 0.5rem !important;
        }

        .gap-3 {
            gap: 1rem !important;
        }

        .g-3 {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }

        .g-4 {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 1.5rem;
        }

        /* Dashboard Cards */
        .card.text-white {
            border: none;
            border-radius: 14px;
            overflow: hidden;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 6px 16px rgba(13, 148, 136, 0.12);
        }

        .card.text-white::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 100% 0%, rgba(255, 255, 255, 0.18) 0%, transparent 50%);
            pointer-events: none;
        }

        .card.text-white:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(13, 148, 136, 0.2);
        }

        .card.text-white .card-body {
            position: relative;
            z-index: 1;
        }

        .card.text-white i {
            opacity: 0.85;
            background: rgba(255, 255, 255, 0.18);
            padding: 0.85rem;
            border-radius: 12px;
            backdrop-filter: blur(6px);
        }

        .card.text-white.bg-primary {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%) !important;
        }

        .card.text-white.bg-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        }

        .card.text-white.bg-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        }

        .card.text-white.bg-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
        }

        .card.text-white.bg-info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important;
        }

        /* Pagination */
        .pagination {
            margin-top: 1.5rem;
        }

        .page-link {
            border-radius: 8px;
            margin: 0 3px;
            border: 1px solid var(--border-color);
            color: var(--primary-color);
            padding: 0.5rem 0.85rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .page-link:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 4px 10px rgba(13, 148, 136, 0.2);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            border-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 10px rgba(13, 148, 136, 0.25);
        }
        
        /* Hide pagination arrows */
        .pagination .page-item:first-child,
        .pagination .page-item:last-child {
            display: none !important;
        }
        
        /* Hide large standalone arrow icons */
        svg[width="24"],
        svg[width="32"],
        svg[width="48"] {
            display: none !important;
        }

        /* Login Page */
        .container {
            padding: 2rem;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 1.25rem;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            /* Sidebar becomes offcanvas on tablets */
            .sidebar {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                height: 100vh;
                z-index: 1050;
                transition: right 0.3s ease;
                overflow-y: auto;
            }

            .sidebar.show {
                right: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .main-content {
                width: 100%;
                margin-right: 0;
            }

            .mobile-menu-toggle {
                display: inline-flex;
            }

            .page-title-text {
                font-size: 1.05rem;
            }

            .navbar-actions {
                gap: 0.375rem;
                flex-basis: 100%;
                justify-content: flex-end;
                padding-top: 0.25rem;
                border-top: 1px solid rgba(209, 250, 229, 0.6);
            }
        }

        @media (max-width: 767.98px) {
            /* Mobile phones */
            .container-fluid {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .card-body {
                padding: 1rem;
            }

            .card-header {
                padding: 0.75rem 1rem;
            }

            .card-header h5 {
                font-size: 1rem;
            }

            .navbar {
                padding: 0.625rem 0.875rem;
            }

            .page-title-text {
                font-size: 0.95rem;
            }

            .navbar-icon-btn {
                width: 38px;
                height: 38px;
                font-size: 0.9rem;
            }

            .user-avatar-circle {
                width: 32px;
                height: 32px;
                font-size: 0.85rem;
            }

            .user-dropdown-toggle {
                padding: 0.25rem;
            }

            .user-dropdown-menu {
                min-width: 240px;
                left: -10px;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }

            .table {
                font-size: 0.875rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.5rem;
            }

            .form-control,
            .form-select {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }

            .alert {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }

            /* Dashboard cards */
            .card.text-white .card-body {
                padding: 1rem;
            }

            .card.text-white h2 {
                font-size: 1.5rem !important;
            }

            .card.text-white h6 {
                font-size: 0.75rem !important;
            }

            .card.text-white i {
                font-size: 1.5rem !important;
            }

            /* Hide some columns on mobile */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* Stack form columns */
            .row.g-4 > [class*="col-"] {
                margin-bottom: 1rem;
            }

            /* Pagination */
            .pagination {
                flex-wrap: wrap;
            }

            .page-link {
                padding: 0.375rem 0.5rem;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 575.98px) {
            /* Extra small devices */
            .container-fluid {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .card-body {
                padding: 0.75rem;
            }

            .navbar {
                padding: 0.5rem 0.625rem;
            }

            .page-title-label {
                display: none !important;
            }

            .page-title-text {
                font-size: 0.875rem;
            }

            .mobile-menu-toggle {
                width: 38px;
                height: 38px;
                font-size: 1rem;
            }

            .navbar-icon-btn {
                width: 36px;
                height: 36px;
            }

            .user-dropdown-menu {
                min-width: 220px;
                left: -20px;
            }

            .btn-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-group .btn {
                width: 100%;
                margin: 2px 0;
            }

            .table {
                font-size: 0.75rem;
            }

            .table thead th,
            .table tbody td {
                padding: 0.375rem;
            }

            .card.text-white h2 {
                font-size: 1.25rem !important;
            }

            .card.text-white i {
                font-size: 1.25rem !important;
            }
        }

        @media (min-width: 992px) {
            /* Desktop - ensure sidebar is visible */
            .sidebar {
                position: sticky;
                top: 0;
            }

            .mobile-menu-toggle {
                display: none !important;
            }

            .sidebar-overlay {
                display: none !important;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--primary-soft);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            border-radius: 5px;
            border: 2px solid var(--primary-soft);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--primary-hover) 0%, var(--primary-dark) 100%);
        }

        /* Additional improvements */
        .btn-group .btn {
            margin: 0 2px;
        }

        .text-white-50 {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .fw-semibold {
            font-weight: 600 !important;
        }

        .lead {
            font-size: 1.125rem;
            font-weight: 400;
        }

        /* Responsive Images */
        img {
            max-width: 100%;
            height: auto;
        }

        /* Table Buttons */
        .table .btn {
            margin: 0 2px;
        }

        .table .btn-group .btn {
            margin: 0;
        }

        /* Responsive Buttons */
        @media (max-width: 767.98px) {
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }

            .btn-sm {
                padding: 0.35rem 0.75rem;
                font-size: 0.75rem;
            }

            .btn-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-group .btn {
                width: 100%;
                border-radius: 8px !important;
                margin-bottom: 0.5rem;
            }

            .btn-group .btn:last-child {
                margin-bottom: 0;
            }
        }

        @media (max-width: 575.98px) {
            .btn {
                padding: 0.45rem 0.875rem;
                font-size: 0.8125rem;
            }

            .btn-sm {
                padding: 0.3rem 0.65rem;
                font-size: 0.7rem;
            }

            .btn-group-vertical .btn {
                width: 100%;
            }

            .table .btn {
                padding: 0.3rem 0.5rem;
                font-size: 0.7rem;
            }

            .table .btn i {
                font-size: 0.7rem;
            }
        }

        /* —— Small screens: consistent layout across all pages —— */
        @media (max-width: 767.98px) {
            main.main-content > .container-fluid.px-4 {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            .sidebar .p-4 {
                padding: 1rem !important;
            }

            .card-header.d-flex.justify-content-between,
            .card-header.d-flex {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 0.75rem;
            }

            .card-header.d-flex > h1,
            .card-header.d-flex > h2,
            .card-header.d-flex > h3,
            .card-header.d-flex > h4,
            .card-header.d-flex > h5,
            .card-header.d-flex > h6 {
                min-width: 0;
                width: 100%;
                margin-bottom: 0 !important;
            }

            .card-header.d-flex > .btn,
            .card-header.d-flex > a.btn,
            .card-header.d-flex > .btn-group,
            .card-header.d-flex > form {
                width: 100%;
                margin-top: 0 !important;
            }

            .card-header.d-flex > .btn-group .btn {
                width: 100%;
            }

            /* Nested toolbars inside card headers (title row + actions) */
            .card-header .btn:not(.btn-close),
            .card-header a.btn {
                max-width: 100%;
            }

            /* Icon + title rows: allow wrap; keep horizontal by default */
            .card-header .d-flex.align-items-center {
                flex-wrap: wrap;
                row-gap: 0.35rem;
                column-gap: 0.5rem;
            }

            .card-header .btn,
            .card-header a.btn {
                width: 100%;
            }

            .input-group {
                flex-wrap: wrap;
            }

            .input-group > .form-control,
            .input-group > .form-select {
                min-width: 0;
                flex: 1 1 auto;
            }

            .card-body:has(> table) {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .card-body:has(> table) > table {
                min-width: 520px;
            }

            .row.g-4 > [class*="col-"],
            .row.g-3 > [class*="col-"] {
                min-width: 0;
            }
        }

        @media (max-width: 575.98px) {
            main.main-content > .container-fluid.px-4 {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            .list-group-item.d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0.5rem;
            }

            .badge {
                white-space: normal;
                text-align: center;
                line-height: 1.3;
            }

            .table td,
            .table th {
                word-break: break-word;
                overflow-wrap: anywhere;
            }

            .container:not(.container-fluid) {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }

        /* Mobile: جداول البيانات كبطاقات (يُفعّل عبر JS بإضافة table--mobile-cards) */
        @media (max-width: 767.98px) {
            .table-responsive.table--mobile-cards {
                overflow-x: visible !important;
            }

            table.table.table--mobile-cards {
                border: 0 !important;
                margin-bottom: 0 !important;
            }

            table.table.table--mobile-cards thead {
                position: absolute;
                width: 1px;
                height: 1px;
                padding: 0;
                margin: -1px;
                overflow: hidden;
                clip: rect(0, 0, 0, 0);
                white-space: nowrap;
                border: 0;
            }

            table.table.table--mobile-cards tbody {
                display: block;
            }

            table.table.table--mobile-cards tbody tr {
                display: block;
                background: var(--surface-color);
                border: 1px solid var(--border-color);
                border-radius: 12px;
                margin-bottom: 0.85rem;
                padding: 0.65rem 1rem;
                box-shadow: 0 2px 10px rgba(13, 148, 136, 0.07);
            }

            table.table.table--mobile-cards tbody tr:last-child {
                margin-bottom: 0;
            }

            table.table.table--mobile-cards tbody tr:hover {
                transform: none !important;
                background-color: var(--surface-color) !important;
            }

            table.table.table--mobile-cards tbody td {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                padding: 0.55rem 0 !important;
                border: none !important;
                border-bottom: 1px dashed rgba(148, 163, 184, 0.45) !important;
                vertical-align: middle !important;
            }

            table.table.table--mobile-cards tbody td:last-child {
                border-bottom: none !important;
            }

            table.table.table--mobile-cards tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                font-size: 0.78rem;
                color: #64748b;
                flex: 0 1 42%;
                text-align: right;
                line-height: 1.3;
            }

            table.table.table--mobile-cards tbody td:not([data-label])::before {
                display: none;
            }

            table.table.table--mobile-cards tbody td:not([data-label]) {
                justify-content: center;
                text-align: center;
            }

            table.table.table--mobile-cards tbody td .d-flex {
                justify-content: flex-end !important;
                flex-wrap: wrap !important;
                gap: 0.35rem !important;
            }

            table.table.table--mobile-cards .btn-group {
                flex-direction: row !important;
                width: auto !important;
            }

            table.table.table--mobile-cards .btn-group .btn {
                width: auto !important;
                margin-bottom: 0 !important;
            }
        }

        /* Print Styles */
        @media print {
            .sidebar,
            .navbar,
            .btn,
            .mobile-menu-toggle,
            .sidebar-overlay {
                display: none !important;
            }

            .main-content {
                width: 100% !important;
                margin: 0 !important;
            }

            .card {
                border: 1px solid #000;
                page-break-inside: avoid;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-12 col-md-3 col-lg-2 sidebar p-0" id="sidebar">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center sidebar-header-row">
                        <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0">
                            <img src="{{ asset('images/logo-icon-white.svg') }}" alt="" width="36" height="36" class="sidebar-logo flex-shrink-0" decoding="async">
                            <h4 class="mb-0 text-truncate">نظام إدارة العيادة</h4>
                        </div>
                        <button class="btn-close btn-close-white d-md-none flex-shrink-0" id="closeSidebar" aria-label="إغلاق القائمة"></button>
                    </div>
                    <ul class="nav flex-column">
                        @if(auth()->user()->hasPermission('view_dashboard'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i> لوحة التحكم
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('view_patients'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                                <i class="fas fa-users"></i> المرضى
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('view_appointments'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">
                                <i class="fas fa-calendar"></i> المواعيد
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('view_appointment_requests'))
                        @php
                            $pendingReqQ = \App\Models\AppointmentRequest::query()->where('status', 'pending');
                            if ($cid = \App\Support\ClinicContext::currentId()) {
                                $pendingReqQ->where(function ($q) use ($cid) {
                                    $q->where('preferred_clinic_id', $cid)
                                        ->orWhereHas('appointment', function ($qq) use ($cid) {
                                            $qq->where('clinic_id', $cid);
                                        });
                                });
                            }
                            $pendingRequestsCount = $pendingReqQ->count();
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('appointment-requests.*') ? 'active' : '' }}" href="{{ route('appointment-requests.index') }}">
                                <i class="fas fa-inbox"></i> طلبات الحجز
                                @if($pendingRequestsCount > 0)
                                    <span class="badge bg-danger ms-auto">{{ $pendingRequestsCount }}</span>
                                @endif
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('view_doctors'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}" href="{{ route('doctors.index') }}">
                                <i class="fas fa-user-md"></i> الأطباء
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('view_prescriptions'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctor.prescriptions.*') ? 'active' : '' }}" href="{{ route('doctor.prescriptions.index') }}">
                                <i class="fas fa-prescription"></i> الوصفات الطبية
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('view_invoices'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" href="{{ route('invoices.index') }}">
                                <i class="fas fa-file-invoice"></i> الفواتير
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('view_payments'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                                <i class="fas fa-money-bill-wave"></i> المدفوعات
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('view_users'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-user-cog"></i> إدارة المستخدمين
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('manage_roles'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.role-permissions.*') ? 'active' : '' }}" href="{{ route('admin.role-permissions.index') }}">
                                <i class="fas fa-user-shield"></i> إدارة الأدوار والصلاحيات
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('manage_clinics'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.clinics.*') ? 'active' : '' }}" href="{{ route('admin.clinics.index') }}">
                                <i class="fas fa-hospital"></i> إدارة العيادات
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('manage_departments'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}" href="{{ route('admin.departments.index') }}">
                                <i class="fas fa-building"></i> إدارة الأقسام
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('manage_specializations'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.specializations.*') ? 'active' : '' }}" href="{{ route('admin.specializations.index') }}">
                                <i class="fas fa-stethoscope"></i> إدارة التخصصات
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('view_reports'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-bar"></i> التقارير
                            </a>
                        </li>
                        @endif
                        <li class="nav-item mt-3 pt-3 border-top">
                            <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                                <i class="fas fa-user-circle"></i> الملف الشخصي
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-12 col-md-9 col-lg-10 main-content min-w-0">
                <!-- Top Navbar -->
                @php
                    $roleNames = [
                        'admin' => 'مدير النظام',
                        'doctor' => 'طبيب',
                        'receptionist' => 'موظف استقبال',
                        'call_center' => 'مركز اتصال',
                        'accountant' => 'محاسب',
                        'storekeeper' => 'أمين مخزن'
                    ];
                    $currentUser = auth()->user();
                    $userInitial = mb_substr($currentUser->name, 0, 1);
                    $userRoleLabel = $roleNames[$currentUser->role] ?? $currentUser->role;
                @endphp
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center gap-3">
                            <button class="mobile-menu-toggle" id="mobileMenuToggle" type="button" aria-label="فتح القائمة">
                                <i class="fas fa-bars"></i>
                            </button>
                            <div class="page-title-wrapper">
                                <span class="page-title-label d-none d-md-block">@yield('page-label', 'الصفحة الحالية')</span>
                                <span class="page-title-text">@yield('page-title', 'لوحة التحكم')</span>
                            </div>
                        </div>
                        <div class="navbar-actions">
                            {{-- Clinic Switcher / Indicator --}}
                            @if($canSwitchClinic ?? false)
                                <div class="clinic-switcher" id="clinicSwitcher">
                                    <button type="button" class="clinic-switcher-toggle" id="clinicSwitcherToggle">
                                        <i class="fas fa-hospital"></i>
                                        <div class="clinic-switcher-text d-none d-md-flex">
                                            <span class="clinic-switcher-label">الفرع الحالي</span>
                                            <span class="clinic-switcher-value">
                                                {{ $currentClinic?->name ?? 'كل الفروع' }}
                                            </span>
                                        </div>
                                        <i class="fas fa-chevron-down clinic-switcher-caret"></i>
                                    </button>
                                    <div class="clinic-switcher-menu">
                                        <div class="clinic-switcher-header">
                                            <i class="fas fa-hospital text-primary me-1"></i>
                                            <span>اختر الفرع</span>
                                        </div>
                                        <form method="POST" action="{{ route('clinic.switch') }}" class="m-0">
                                            @csrf
                                            <button type="submit" name="clinic_id" value=""
                                                    class="clinic-switcher-item {{ ! $currentClinic ? 'active' : '' }}">
                                                <i class="fas fa-globe"></i>
                                                <span>كل الفروع</span>
                                                @if(! $currentClinic) <i class="fas fa-check ms-auto text-success"></i> @endif
                                            </button>
                                        </form>
                                        @foreach($availableClinics ?? [] as $c)
                                            <form method="POST" action="{{ route('clinic.switch') }}" class="m-0">
                                                @csrf
                                                <button type="submit" name="clinic_id" value="{{ $c->id }}"
                                                        class="clinic-switcher-item {{ $currentClinic?->id === $c->id ? 'active' : '' }}">
                                                    <i class="fas fa-hospital"></i>
                                                    <div class="text-end flex-grow-1">
                                                        <div class="fw-semibold">
                                                            {{ $c->name }}
                                                            @if($c->is_main)
                                                                <span class="badge bg-warning text-dark ms-1" style="font-size: 0.6rem;">رئيسية</span>
                                                            @endif
                                                        </div>
                                                        @if($c->city)
                                                            <small class="text-muted">{{ $c->city }}</small>
                                                        @endif
                                                    </div>
                                                    @if($currentClinic?->id === $c->id) <i class="fas fa-check ms-auto text-success"></i> @endif
                                                </button>
                                            </form>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif(($currentClinic ?? null))
                                <div class="clinic-indicator" title="أنت تعمل على هذا الفرع فقط">
                                    <i class="fas fa-hospital"></i>
                                    <div class="d-none d-md-flex flex-column">
                                        <span class="clinic-indicator-label">الفرع</span>
                                        <span class="clinic-indicator-value">{{ $currentClinic->name }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="navbar-date d-none d-xl-flex">
                                <i class="fas fa-calendar-day"></i>
                                <span id="currentDate">{{ \Carbon\Carbon::now()->translatedFormat('l، d F Y') }}</span>
                            </div>

                            <a href="{{ route('dashboard') }}" class="navbar-icon-btn d-none d-md-flex" title="لوحة التحكم">
                                <i class="fas fa-home"></i>
                            </a>

                            <div class="user-dropdown" id="userDropdown">
                                <a href="#" class="user-dropdown-toggle" id="userDropdownToggle">
                                    <div class="user-avatar-circle">{{ $userInitial }}</div>
                                    <div class="user-info d-none d-sm-flex">
                                        <span class="user-name">{{ $currentUser->name }}</span>
                                        <span class="user-role">{{ $userRoleLabel }}</span>
                                    </div>
                                    <i class="fas fa-chevron-down user-caret d-none d-sm-block"></i>
                                </a>
                                <div class="user-dropdown-menu">
                                    <div class="user-dropdown-header">
                                        <div class="user-avatar-circle">{{ $userInitial }}</div>
                                        <div class="user-dropdown-header-info">
                                            <div class="user-dropdown-header-name">{{ $currentUser->name }}</div>
                                            <div class="user-dropdown-header-email">{{ $currentUser->email }}</div>
                                        </div>
                                    </div>
                                    <div class="user-dropdown-body">
                                        <a href="{{ route('profile.show') }}" class="user-dropdown-item">
                                            <i class="fas fa-user-circle"></i>
                                            <span>الملف الشخصي</span>
                                        </a>
                                        <a href="{{ route('profile.edit') }}" class="user-dropdown-item">
                                            <i class="fas fa-user-edit"></i>
                                            <span>تعديل البيانات</span>
                                        </a>
                                        <a href="{{ route('profile.edit') }}#password" class="user-dropdown-item">
                                            <i class="fas fa-key"></i>
                                            <span>تغيير كلمة المرور</span>
                                        </a>
                                        <div class="user-dropdown-divider"></div>
                                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                                            @csrf
                                            <button type="submit" class="user-dropdown-item danger">
                                                <i class="fas fa-sign-out-alt"></i>
                                                <span>تسجيل الخروج</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Content -->
                <div class="container-fluid px-4 pb-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <div class="flex-grow-1">{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div class="flex-grow-1">{{ session('error') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>يرجى تصحيح الأخطاء التالية:</strong>
                            </div>
                            <ul class="mb-0 ps-4">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @else
        @yield('content')
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @auth
    <script>
        // Mobile Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const closeSidebar = document.getElementById('closeSidebar');

            function openSidebar() {
                sidebar.classList.add('show');
                sidebarOverlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebarFunc() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', openSidebar);
            }

            if (closeSidebar) {
                closeSidebar.addEventListener('click', closeSidebarFunc);
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebarFunc);
            }

            // Close sidebar when clicking on a link (mobile only)
            const sidebarLinks = sidebar.querySelectorAll('.nav-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        closeSidebarFunc();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    closeSidebarFunc();
                }
            });

            // Clinic Switcher Toggle
            const clinicSwitcher = document.getElementById('clinicSwitcher');
            const clinicSwitcherToggle = document.getElementById('clinicSwitcherToggle');
            if (clinicSwitcher && clinicSwitcherToggle) {
                clinicSwitcherToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    clinicSwitcher.classList.toggle('show');
                });
                document.addEventListener('click', function(e) {
                    if (!clinicSwitcher.contains(e.target)) {
                        clinicSwitcher.classList.remove('show');
                    }
                });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        clinicSwitcher.classList.remove('show');
                    }
                });
            }

            // User Dropdown Toggle
            const userDropdown = document.getElementById('userDropdown');
            const userDropdownToggle = document.getElementById('userDropdownToggle');

            if (userDropdownToggle && userDropdown) {
                userDropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    userDropdown.classList.toggle('show');
                });

                document.addEventListener('click', function(e) {
                    if (!userDropdown.contains(e.target)) {
                        userDropdown.classList.remove('show');
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        userDropdown.classList.remove('show');
                    }
                });
            }

            function applyMobileTableCards() {
                const main = document.querySelector('main.main-content');
                if (!main) return;

                const mobile = window.matchMedia('(max-width: 767.98px)').matches;

                main.querySelectorAll('table.table').forEach(function(table) {
                    if (table.closest('.modal')) return;
                    if (table.classList.contains('no-mobile-cards')) return;
                    if (table.id === 'appointment-schedule-table') return;
                    if (table.classList.contains('schedule-table')) return;

                    const thead = table.querySelector('thead');
                    if (!thead) return;

                    const headerRow = thead.querySelector('tr:last-of-type');
                    if (!headerRow) return;

                    const headerCells = headerRow.querySelectorAll('th');
                    if (!headerCells.length) return;

                    const headers = Array.prototype.map.call(headerCells, function(th) {
                        return th.innerText.replace(/\s+/g, ' ').trim();
                    });

                    let wrap = table.parentElement;
                    if (!wrap || !wrap.classList.contains('table-responsive')) {
                        wrap = null;
                    }

                    if (mobile) {
                        table.classList.add('table--mobile-cards');
                        if (wrap) wrap.classList.add('table--mobile-cards');

                        table.querySelectorAll('tbody tr').forEach(function(tr) {
                            let col = 0;
                            Array.prototype.forEach.call(tr.children, function(cell) {
                                if (cell.tagName !== 'TD') return;

                                var span = parseInt(cell.getAttribute('colspan') || '1', 10);
                                if (isNaN(span) || span < 1) span = 1;

                                var label = headers[col] || '';
                                if (label && !cell.hasAttribute('data-label')) {
                                    cell.setAttribute('data-label', label);
                                    cell.dataset.mobileCardAuto = '1';
                                }

                                col += span;
                            });
                        });
                    } else {
                        table.classList.remove('table--mobile-cards');
                        if (wrap) wrap.classList.remove('table--mobile-cards');

                        table.querySelectorAll('td[data-mobile-card-auto="1"]').forEach(function(td) {
                            td.removeAttribute('data-label');
                            delete td.dataset.mobileCardAuto;
                        });
                    }
                });
            }

            var mobileTableCardTimer;
            applyMobileTableCards();
            window.addEventListener('resize', function() {
                clearTimeout(mobileTableCardTimer);
                mobileTableCardTimer = setTimeout(applyMobileTableCards, 120);
            });
        });
    </script>
    @endauth
    
    @stack('scripts')
</body>
</html>

