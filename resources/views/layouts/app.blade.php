<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام إدارة العيادة')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-color: #f8fafc;
            --text-color: #1e293b;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Segoe UI', 'Cairo', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background-color: var(--primary-color);
            color: white;
            position: sticky;
            top: 0;
        }

        .sidebar h4 {
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 12px 16px;
            margin: 4px 0;
            border-radius: 8px;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            background-color: var(--bg-color);
            min-height: 100vh;
            padding-bottom: 2rem;
        }

        /* Navbar */
        .navbar {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            margin-bottom: 1.5rem;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
        }

        /* Cards */
        .card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: white;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 1.5rem;
            border-bottom: none;
            font-weight: 600;
        }

        .card-header h5 {
            margin: 0;
            font-size: 1.1rem;
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
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        }

        .btn:disabled,
        .btn.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-hover) 0%, #1e40af 100%);
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
            background: rgba(37, 99, 235, 0.1);
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
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-label {
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        /* Tables */
        .table {
            border: 1px solid var(--border-color);
        }

        .table thead {
            background-color: #f1f5f9;
        }

        .table thead th {
            border-bottom: 2px solid var(--border-color);
            padding: 0.75rem;
            font-weight: 600;
            color: var(--text-color);
            font-size: 0.875rem;
        }

        .table tbody tr {
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
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
            background-color: #f8fafc;
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
        }

        .card.text-white.bg-primary {
            background-color: var(--primary-color) !important;
        }

        .card.text-white.bg-success {
            background-color: #10b981 !important;
        }

        .card.text-white.bg-danger {
            background-color: #ef4444 !important;
        }

        .card.text-white.bg-warning {
            background-color: #f59e0b !important;
        }

        .card.text-white.bg-info {
            background-color: #06b6d4 !important;
        }

        /* Pagination */
        .pagination {
            margin-top: 1.5rem;
        }

        .page-link {
            border-radius: 6px;
            margin: 0 2px;
            border: 1px solid var(--border-color);
            color: var(--primary-color);
            padding: 0.5rem 0.75rem;
        }

        .page-link:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
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
                display: block;
            }

            .navbar-brand {
                font-size: 1.25rem;
            }

            .navbar .d-flex {
                flex-wrap: wrap;
                gap: 0.5rem !important;
            }

            .navbar .bg-light.rounded-pill {
                padding: 0.5rem 1rem !important;
            }

            .navbar .bg-light.rounded-pill div {
                font-size: 0.875rem;
            }

            .navbar .bg-light.rounded-pill small {
                font-size: 0.75rem;
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
                padding: 0.75rem 1rem;
            }

            .navbar-brand {
                font-size: 1.1rem;
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
                padding: 0.5rem 0.75rem;
            }

            .navbar-brand {
                font-size: 1rem;
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
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
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

            .card-header .d-flex {
                flex-direction: column;
                gap: 0.5rem;
            }

            .card-header .btn {
                width: 100%;
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
            <nav class="col-md-3 col-lg-2 sidebar p-0" id="sidebar">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">
                            <i class="fas fa-hospital"></i> نظام إدارة العيادة
                        </h4>
                        <button class="btn-close btn-close-white d-md-none" id="closeSidebar" aria-label="Close"></button>
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
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}" href="{{ route('doctors.index') }}">
                                <i class="fas fa-user-md"></i> الأطباء
                            </a>
                        </li>
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
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-user-cog"></i> إدارة المستخدمين
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.role-permissions.*') ? 'active' : '' }}" href="{{ route('admin.role-permissions.index') }}">
                                <i class="fas fa-user-shield"></i> إدارة الأدوار والصلاحيات
                            </a>
                            <a class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}" href="{{ route('admin.departments.index') }}">
                                <i class="fas fa-building"></i> إدارة الأقسام
                            </a>
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
            <main class="col-md-9 col-lg-10 main-content">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light mb-4">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center gap-3">
                            <button class="mobile-menu-toggle" id="mobileMenuToggle" type="button">
                                <i class="fas fa-bars"></i>
                            </button>
                            <span class="navbar-brand mb-0 h1 fw-bold">@yield('page-title', 'لوحة التحكم')</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('profile.show') }}" class="text-decoration-none">
                                <div class="d-flex align-items-center bg-light rounded-pill px-3 py-2">
                                    <i class="fas fa-user-circle text-primary me-2" style="font-size: 1.25rem;"></i>
                                    <div>
                                        <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                        <small class="text-muted">
                                            @php
                                                $roleNames = [
                                                    'admin' => 'مدير',
                                                    'doctor' => 'طبيب',
                                                    'receptionist' => 'موظف استقبال',
                                                    'call_center' => 'مركز اتصال',
                                                    'accountant' => 'محاسب',
                                                    'storekeeper' => 'مخزن'
                                                ];
                                            @endphp
                                            {{ $roleNames[auth()->user()->role] ?? auth()->user()->role }}
                                        </small>
                                    </div>
                                </div>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج
                                </button>
                            </form>
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
        });
    </script>
    @endauth
    
    @stack('scripts')
</body>
</html>

