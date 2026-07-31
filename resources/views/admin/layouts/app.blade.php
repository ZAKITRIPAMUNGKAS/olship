<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: true }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | LISTRINDO JAYA ELEKTRIK</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    @stack('styles')

    <style>
        /* ── ENTERPRISE ULTRA-PREMIUM ADMIN DESIGN SYSTEM ── */
        :root {
            --brand-primary: #2563eb;
            --brand-primary-hover: #1d4ed8;
            --brand-accent: #f97316;
            --brand-glow: rgba(37, 99, 235, 0.15);
            
            /* Surface & Background Palette */
            --sidebar-bg: #0b0f19;
            --sidebar-hover: #1e293b;
            --sidebar-border: rgba(255, 255, 255, 0.06);
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            
            --main-bg: #f8fafc;
            --panel-bg: #ffffff;
            --surface-2: #f1f5f9;
            --border: #e2e8f0;
            --border-hover: #cbd5e1;
            
            /* Typography Scale */
            --text-main: #0f172a;
            --text-secondary: #334155;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            
            /* Premium Status Colors */
            --success: #10b981; --success-bg: #ecfdf5; --success-border: #a7f3d0;
            --danger: #ef4444; --danger-bg: #fef2f2; --danger-border: #fecaca;
            --warning: #f59e0b; --warning-bg: #fffbeb; --warning-border: #fde68a;
            --info: #3b82f6; --info-bg: #eff6ff; --info-border: #bfdbfe;
            
            --sidebar-w: 260px;
            --sidebar-collapsed-w: 76px;
            --header-h: 68px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        [x-cloak] { display: none !important; }

        body { 
            background: var(--main-bg); 
            color: var(--text-main); 
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 13.5px; 
            line-height: 1.5;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }

        /* --- SCROLLBAR --- */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* --- LAYOUT WRAPPER --- */
        .admin-layout { display: flex; min-height: 100vh; }

        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin-left: var(--sidebar-w);
            transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .main-wrapper.collapsed { margin-left: var(--sidebar-collapsed-w); }
        @media (max-width: 768px) {
            .main-wrapper, .main-wrapper.collapsed { margin-left: 0; }
        }

        .content-area { flex: 1; padding: 32px 40px; max-width: 1440px; margin: 0 auto; width: 100%; }
        
        .page-header { 
            margin-bottom: 28px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            flex-wrap: wrap;
            gap: 16px;
        }
        .page-title { 
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 28px; 
            font-weight: 800; 
            color: var(--text-main); 
            letter-spacing: -0.02em;
            line-height: 1;
        }
        
        .breadcrumb { 
            font-size: 12.5px; 
            color: var(--text-muted); 
            display: flex; 
            gap: 8px; 
            align-items: center;
            margin-top: 6px;
            font-weight: 500;
        }
        .breadcrumb a { color: var(--brand-primary); font-weight: 600; transition: color 0.15s; }
        .breadcrumb a:hover { color: var(--brand-primary-hover); text-decoration: underline; }
        .breadcrumb i { font-size: 9px; opacity: 0.5; }

        /* --- GRID LAYOUTS --- */
        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 28px;
        }
        .grid-2-1 {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
        }

        /* --- STAT WIDGETS --- */
        .stat-widget {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .stat-widget::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 4px; height: 100%;
            background: var(--brand-primary);
            opacity: 0;
            transition: opacity 0.2s;
        }
        .stat-widget:hover {
            border-color: #cbd5e1;
            transform: translateY(-3px);
            box-shadow: 0 12px 28px -4px rgba(0, 0, 0, 0.08);
        }
        .stat-widget:hover::before { opacity: 1; }
        .stat-widget .lbl {
            font-size: 11px;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 8px;
        }
        .stat-widget .val {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 30px;
            font-weight: 800;
            color: var(--text-main);
            line-height: 1;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }
        .stat-widget .stat-trend {
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .stat-trend.trend-up { color: var(--success); }
        .stat-trend.trend-down { color: var(--danger); }

        .stat-widget .icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            box-shadow: inset 0 0 0 1px rgba(0,0,0,0.04);
        }

        @media (max-width: 1200px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-2-1 { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .grid-4 { grid-template-columns: 1fr; }
            .content-area { padding: 20px 16px; }
        }

        /* --- CARDS & PANELS --- */
        .panel, .glass-card { 
            background: #ffffff; 
            border: 1px solid var(--border); 
            border-radius: 16px; 
            padding: 24px;
            margin-bottom: 24px; 
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }
        .panel:hover {
            border-color: #cbd5e1;
        }
        .panel-header { 
            padding-bottom: 16px; 
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border); 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .panel-title { 
            font-size: 16px; 
            font-weight: 800; 
            color: var(--text-main); 
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .panel-title i {
            color: var(--brand-primary);
            font-size: 16px;
        }

        /* --- BUTTONS --- */
        .btn-ag { 
            padding: 10px 20px; 
            border-radius: 10px; 
            font-size: 13px; 
            font-weight: 700; 
            cursor: pointer; 
            border: 1px solid transparent; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center;
            gap: 8px; 
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); 
            font-family: inherit; 
            box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
        }
        .btn-ag:active { transform: scale(0.98); }
        
        .btn-primary { 
            background: var(--brand-primary); 
            color: #ffffff; 
            border-color: var(--brand-primary); 
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
        }
        .btn-primary:hover { 
            background: var(--brand-primary-hover); 
            border-color: var(--brand-primary-hover);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }
        .btn-ghost { 
            background: #ffffff; 
            border-color: var(--border); 
            color: var(--text-secondary); 
        }
        .btn-ghost:hover { 
            background: var(--surface-2); 
            color: var(--text-main);
            border-color: var(--border-hover);
        }
        .btn-danger { 
            background: var(--danger); 
            color: #ffffff; 
            border-color: var(--danger); 
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.25);
        }
        .btn-danger:hover { 
            background: #dc2626; 
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.35);
            transform: translateY(-1px);
        }

        /* --- EMPTY STATE & CONFIRM MODAL --- */
        .empty-state { text-align: center; padding: 48px 24px; color: var(--text-muted); }
        .empty-state i { font-size: 36px; margin-bottom: 14px; opacity: 0.35; display: block; color: var(--brand-primary); }
        .empty-state p { font-size: 13.5px; margin-bottom: 20px; line-height: 1.6; }

        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 999; align-items: center; justify-content: center; backdrop-filter: blur(6px); }
        .modal-overlay.show { display: flex; }
        .modal-box { background: #ffffff; border: 1px solid var(--border); border-radius: 16px; padding: 28px; width: 400px; max-width: 90%; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
        .modal-box h3 { font-size: 17px; font-weight: 800; margin-bottom: 10px; color: var(--text-main); }
        .modal-box p { font-size: 13.5px; color: var(--text-muted); margin-bottom: 24px; line-height: 1.6; }
        .modal-actions { display: flex; gap: 12px; justify-content: flex-end; }

        .btn-sm { padding: 7px 14px; font-size: 12px; border-radius: 8px; }
        .btn-lg { padding: 14px 28px; font-size: 15px; border-radius: 12px; }

        /* --- FORMS & INPUTS --- */
        .form-group { margin-bottom: 22px; }
        .form-label { 
            display: block; 
            font-size: 11.5px; 
            font-weight: 800; 
            color: var(--text-muted); 
            margin-bottom: 8px; 
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .form-input, .form-control, textarea.form-input { 
            width: 100%; 
            padding: 11px 16px; 
            border: 1.5px solid var(--border); 
            border-radius: 10px; 
            font-size: 13.5px; 
            outline: none; 
            transition: all 0.15s ease; 
            background: #ffffff; 
            color: var(--text-main); 
            font-family: inherit;
        }

        /* --- CUSTOM MODERN DROPLIST / SELECT STYLING --- */
        select, .form-select, select.form-input { 
            width: 100%; 
            padding: 11px 40px 11px 16px !important; 
            border: 1.5px solid var(--border); 
            border-radius: 10px; 
            font-size: 13.5px; 
            font-weight: 500;
            outline: none; 
            transition: all 0.15s ease; 
            background-color: #ffffff !important; 
            color: var(--text-main) !important; 
            font-family: inherit;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%232563eb' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 14px center !important;
            background-size: 16px 16px !important;
            cursor: pointer;
        }

        .form-input:hover, select:hover, .form-select:hover {
            border-color: var(--brand-primary);
        }
        .form-input:focus, select:focus, .form-select:focus, textarea.form-input:focus { 
            border-color: var(--brand-primary); 
            box-shadow: 0 0 0 4px var(--brand-glow); 
            background-color: #ffffff;
        }

        select option {
            background-color: #ffffff;
            color: var(--text-main);
            padding: 10px 14px;
            font-size: 13.5px;
        }
        
        /* --- TABLES --- */
        .ag-table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        .ag-table th { 
            background: #f8fafc; 
            padding: 14px 20px; 
            text-align: left; 
            font-weight: 800; 
            color: var(--text-muted); 
            border-bottom: 1px solid var(--border); 
            text-transform: uppercase; 
            font-size: 11px; 
            letter-spacing: 0.06em; 
        }
        .ag-table td { 
            padding: 16px 20px; 
            border-bottom: 1px solid var(--border); 
            vertical-align: middle; 
            color: var(--text-secondary);
        }
        .ag-table tbody tr { transition: background 0.15s ease; }
        .ag-table tbody tr:hover { background: #f8fafc; }

        /* --- BADGES --- */
        .badge-ag { 
            display: inline-flex; 
            align-items: center; 
            gap: 6px; 
            padding: 4px 12px; 
            border-radius: 99px; 
            font-size: 11.5px; 
            font-weight: 700; 
            letter-spacing: 0.02em;
        }
        .badge-success, .badge-completed { background: var(--success-bg); color: var(--success); border: 1px solid var(--success-border); }
        .badge-danger, .badge-cancelled { background: var(--danger-bg); color: var(--danger); border: 1px solid var(--danger-border); }
        .badge-warning, .badge-pending { background: var(--warning-bg); color: #d97706; border: 1px solid var(--warning-border); }
        .badge-info, .badge-processing, .badge-shipped { background: var(--info-bg); color: var(--brand-primary); border: 1px solid var(--info-border); }

        .fade-up { animation: fadeUp 0.25s ease-out both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .delay-1 { animation-delay: 0.08s; }
        .delay-2 { animation-delay: 0.16s; }
    </style>
</head>
<body>

    <div class="admin-layout" x-data="{ sidebarOpen: window.innerWidth > 768 }">

        <!-- SIDEBAR OVERLAY (mobile) -->
        <div class="sidebar-overlay" :class="sidebarOpen && window.innerWidth <= 768 ? 'active' : ''" @click="sidebarOpen = false"></div>

        <!-- SIDEBAR -->
        @include('admin.partials.sidebar')

        <!-- MAIN WRAPPER -->
        <div class="main-wrapper" :class="sidebarOpen ? '' : 'collapsed'">
            
            <!-- TOPBAR -->
            @include('admin.partials.topbar')

            <!-- CONTENT AREA -->
            <main class="content-area">
                
                <div class="page-header">
                    <div>
                        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                        <div class="breadcrumb">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                            @yield('breadcrumb')
                            <i class="fas fa-chevron-right"></i>
                            <span style="color:var(--text-muted)">@yield('page-title', 'Dashboard')</span>
                        </div>
                    </div>
                    <div id="pageActions">
                        @yield('page-actions')
                    </div>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                         class="panel fade-up" style="border-left: 4px solid var(--success); padding: 12px 20px; display:flex; align-items:center; gap:12px; margin-bottom:20px;">
                        <i class="fas fa-check-circle" style="color:var(--success)"></i>
                        <span style="flex:1">{{ session('success') }}</span>
                        <button @click="show = false" style="background:none;border:none;cursor:pointer;color:var(--text-muted)"><i class="fas fa-times"></i></button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" 
                         class="panel fade-up" style="border-left: 4px solid var(--danger); padding: 12px 20px; display:flex; align-items:center; gap:12px; margin-bottom:20px;">
                        <i class="fas fa-exclamation-circle" style="color:var(--danger)"></i>
                        <span style="flex:1">{{ session('error') }}</span>
                        <button @click="show = false" style="background:none;border:none;cursor:pointer;color:var(--text-muted)"><i class="fas fa-times"></i></button>
                    </div>
                @endif

                @yield('content')

            </main>

            <footer style="padding: 20px 24px; border-top: 1px solid var(--border); font-size: 12px; color: var(--text-muted); text-align: center;">
                © {{ date('Y') }} LISTRINDO JAYA ELEKTRIK Admin Center · Enterprise Edition
            </footer>
        </div>
    </div>

    <!-- UX Fix: Confirm Modal -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal-box">
            <h3 id="modalTitle">Konfirmasi Aksi</h3>
            <p id="modalBody">Apakah kamu yakin?</p>
            <div class="modal-actions">
                <button class="btn-ag btn-ghost" onclick="closeModal()">Batal</button>
                <button class="btn-ag btn-danger" id="modalConfirmBtn">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <!-- UX Fix: Toast Container -->
    <div id="toast-container"></div>

    @vite(['resources/js/ui-admin.js'])
    @stack('scripts')
</body>
</html>

