<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | LISTRINDO JAYA ELEKTRIK</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        :root {
            --brand-orange: #f97316;
            --brand-blue: #025cca;
            --main-bg: #f1f5f9;
            --panel-bg: #ffffff;
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --danger: #dc2626;
            --danger-bg: #fee2e2;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--main-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-image: 
                radial-gradient(at 0% 0%, rgba(2, 92, 202, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(249, 115, 22, 0.05) 0px, transparent 50%);
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
        }

        .auth-card {
            background: var(--panel-bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo-text {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .logo-text span { color: var(--brand-orange); }

        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .auth-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 8px;
        }
        .auth-subtitle {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* Forms */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
            background: #fff;
            color: var(--text-main);
        }
        .form-control:focus {
            border-color: var(--brand-blue);
            box-shadow: 0 0 0 3px rgba(2, 92, 202, 0.1);
        }
        .form-control::placeholder { color: #cbd5e1; }

        .password-wrapper { position: relative; }
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            cursor: pointer;
            font-size: 14px;
        }

        /* Buttons */
        .btn-volt {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: 0.2s;
            font-family: inherit;
        }
        .btn-primary {
            background: var(--brand-blue);
            color: white;
            border-color: var(--brand-blue);
        }
        .btn-primary:hover {
            background: #02469c;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(2, 92, 202, 0.2);
        }
        .btn-primary:active { transform: translateY(0); }

        .auth-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }
        .auth-footer a {
            color: var(--brand-blue);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-footer a:hover { text-decoration: underline; }

        .error-msg {
            color: var(--danger);
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.4s ease-out both; }
        
        .copy-footer {
            margin-top: 32px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="auth-container fade-up">
        <div class="auth-logo" style="display:flex; justify-content:center;">
            <img src="{{ asset('images/logo.png') }}" alt="LISTRINDO JAYA ELEKTRIK" style="height:60px; width:auto; object-fit:contain;">
        </div>

        <div class="auth-card">
            @yield('content')
        </div>

        <div class="copy-footer">
            © {{ date('Y') }} LISTRINDO JAYA ELEKTRIK Enterprise · All Rights Reserved
        </div>
    </div>
</body>
</html>

