<!DOCTYPE html>
<html lang="en" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduPlay - Math Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --body-bg: #0a0b1e;
            --body-bg2: #0d0f2e;
            --card-bg: rgba(255,255,255,.04);
            --card-border: rgba(255,255,255,.08);
            --card-border2: rgba(255,255,255,.05);
            --text-main: #e2e8f0;
            --text-muted: rgba(255,255,255,.45);
            --input-bg: rgba(255,255,255,.06);
            --input-border: rgba(255,255,255,.12);
            --input-color: #fff;
            --section-head-bg: linear-gradient(135deg,#0d0f2e,#1a0e4a);
            --section-head-border: rgba(99,102,241,.2);
        }
        [data-bs-theme="light"] {
            --body-bg: #f0f2f8;
            --body-bg2: #e8eaf6;
            --card-bg: rgba(255,255,255,.92);
            --card-border: rgba(99,102,241,.18);
            --card-border2: rgba(99,102,241,.1);
            --text-main: #1e1b4b;
            --text-muted: rgba(30,27,75,.55);
            --input-bg: #fff;
            --input-border: rgba(99,102,241,.28);
            --input-color: #1e1b4b;
            --section-head-bg: linear-gradient(135deg,#e0e7ff,#c7d2fe);
            --section-head-border: rgba(99,102,241,.2);
        }
        body { font-family: 'Inter', sans-serif; background: var(--body-bg) !important; color: var(--text-main); transition: background .3s, color .3s; }

        /* ── Navbar ── */
        .app-nav {
            background: rgba(10,11,30,.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(99,102,241,.2);
            padding: 0;
            position: sticky; top: 0; z-index: 1000;
        }
        .app-nav .nav-inner {
            display: flex; align-items: center; justify-content: space-between;
            height: 58px; padding: 0 24px;
        }

        /* Brand */
        .nav-brand {
            font-size: 1.3rem; font-weight: 900; text-decoration: none;
            background: linear-gradient(135deg, #a78bfa, #60a5fa);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; letter-spacing: -.3px;
            display: flex; align-items: center; gap: 8px;
        }
        .nav-brand img {
            height: 70px;
            width: auto;
        }

        /* Right side */
        .nav-right { display: flex; align-items: center; gap: 10px; }

        /* Theme toggle */
        .theme-btn {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 10px; padding: 7px 12px;
            color: rgba(255,255,255,.5); font-size: .78rem;
            cursor: pointer; transition: all .2s;
            display: flex; align-items: center; gap: 6px;
        }
        .theme-btn:hover {
            background: rgba(99,102,241,.2);
            border-color: rgba(99,102,241,.4);
            color: #a5b4fc;
        }

        /* User pill */
        .user-pill {
            display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 10px; padding: 5px 10px;
        }
        .user-avatar {
            width: 26px; height: 26px; border-radius: 7px;
            background: linear-gradient(135deg, #7c3aed, #3b82f6);
            display: flex; align-items: center; justify-content: center;
            font-size: .7rem; font-weight: 800; color: #fff; flex-shrink: 0;
        }
        .user-name {
            font-size: .75rem; font-weight: 600;
            color: rgba(255,255,255,.65); max-width: 120px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        /* Logout button */
        .logout-btn {
            display: flex; align-items: center; gap: 6px;
            background: rgba(239,68,68,.1);
            border: 1px solid rgba(239,68,68,.25);
            border-radius: 10px; padding: 7px 14px;
            color: #fca5a5; font-size: .78rem; font-weight: 600;
            text-decoration: none; transition: all .2s; cursor: pointer;
        }
        .logout-btn:hover {
            background: rgba(239,68,68,.22);
            border-color: rgba(239,68,68,.45);
            color: #fca5a5; transform: translateY(-1px);
        }
        .logout-btn i { font-size: .85rem; }

        /* Notification bell */
        .notif-bell {
            position: relative;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 10px;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,.65);
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
        }
        .notif-bell:hover {
            background: rgba(99,102,241,.2);
            border-color: rgba(99,102,241,.4);
            color: #a5b4fc;
            transform: scale(1.05);
        }
        .notif-bell i {
            font-size: 1.1rem;
        }
        .notif-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(239,68,68,.4);
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        [data-bs-theme="light"] .notif-bell {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #64748b;
        }
        [data-bs-theme="light"] .notif-bell:hover {
            background: #ede9fe;
            border-color: #a78bfa;
            color: #7c3aed;
        }

        /* Light mode overrides */
        [data-bs-theme="light"] .app-nav {
            background: rgba(255,255,255,.97);
            border-bottom-color: rgba(99,102,241,.15);
        }
        [data-bs-theme="light"] .theme-btn   { color: #64748b; background: #f1f5f9; border-color: #e2e8f0; }
        [data-bs-theme="light"] .theme-btn:hover { background: #ede9fe; border-color: #a78bfa; color: #7c3aed; }
        [data-bs-theme="light"] .user-pill   { background: #f8fafc; border-color: #e2e8f0; }
        [data-bs-theme="light"] .user-name   { color: #374151; }
        [data-bs-theme="light"] .logout-btn  { background: #fff1f2; border-color: #fecaca; color: #dc2626; }
        [data-bs-theme="light"] .logout-btn:hover { background: #fee2e2; }

        /* Main */
        main { min-height: calc(100vh - 58px); }
        main.full-height { min-height: 100vh; height: 100vh; display: flex; flex-direction: column; }
        main.full-height > .px-0 { flex: 1; display: flex; flex-direction: column; height: 100%; }
        main.full-height > .px-0 > * { flex: 1; }

        /* Flash alerts */
        .flash-alert {
            border-radius: 12px; padding: 12px 18px;
            font-size: .85rem; font-weight: 500; margin-bottom: 16px;
            display: flex; align-items: center; gap: 10px;
        }

        /* ── Standardized In-Game Navigation Buttons (Geometry Grid Format) ── */
        .eduplay-game-nav {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .eduplay-nav-btn {
            width: 40px;
            height: 40px;
            min-width: 40px;
            min-height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            line-height: 1;
            border: 2px solid;
            cursor: pointer;
            transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none !important;
            box-sizing: border-box;
            user-select: none;
            padding: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            flex-shrink: 0;
            position: relative;
        }
        .eduplay-nav-btn span {
            display: none; /* Clean circular icon format for in-game headers & toolbars */
        }
        .eduplay-nav-btn i {
            font-size: 1.05rem;
            line-height: 1;
            transition: transform 0.2s ease;
        }
        .eduplay-nav-btn:hover {
            transform: scale(1.1);
        }
        .eduplay-nav-btn:active {
            transform: scale(0.96);
        }
        .eduplay-btn-map {
            background: rgba(56, 189, 248, 0.15);
            border-color: rgba(56, 189, 248, 0.45);
            color: #38bdf8 !important;
        }
        .eduplay-btn-map:hover {
            background: rgba(56, 189, 248, 0.32);
            border-color: #38bdf8;
            color: #ffffff !important;
            box-shadow: 0 0 18px rgba(56, 189, 248, 0.45);
        }
        .eduplay-btn-exit {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.45);
            color: #ef4444 !important;
        }
        .eduplay-btn-exit:hover {
            background: rgba(239, 68, 68, 0.32);
            border-color: #ef4444;
            color: #ffffff !important;
            box-shadow: 0 0 18px rgba(239, 68, 68, 0.45);
        }
        .eduplay-btn-pause {
            background: rgba(251, 191, 36, 0.15);
            border-color: rgba(251, 191, 36, 0.45);
            color: #fbbf24 !important;
        }
        .eduplay-btn-pause:hover {
            background: rgba(251, 191, 36, 0.32);
            border-color: #fbbf24;
            color: #ffffff !important;
            box-shadow: 0 0 18px rgba(251, 191, 36, 0.45);
        }
        .eduplay-btn-pause.paused {
            background: rgba(52, 211, 153, 0.2);
            border-color: rgba(52, 211, 153, 0.5);
            color: #34d399 !important;
        }


        /* Pill variant for modals, end-screens, and banners (Geometry Grid pill badge style) */
        .eduplay-nav-btn.eduplay-btn-pill,
        .modal .eduplay-nav-btn,
        .modal-footer .eduplay-nav-btn,
        .end-screen .eduplay-nav-btn,
        .winner-card .eduplay-nav-btn,
        .pause-card .eduplay-nav-btn,
        .pause-box .eduplay-nav-btn,
        .mbox .eduplay-nav-btn,
        .bnr-box .eduplay-nav-btn,
        .stack .eduplay-nav-btn,
        .btn-row .eduplay-nav-btn {
            width: auto;
            height: auto;
            min-width: unset;
            min-height: unset;
            border-radius: 50px;
            padding: 8px 18px;
            gap: 8px;
            font-size: 0.88rem;
            font-weight: 800;
            letter-spacing: 0.3px;
        }
        .eduplay-nav-btn.eduplay-btn-pill span,
        .modal .eduplay-nav-btn span,
        .modal-footer .eduplay-nav-btn span,
        .end-screen .eduplay-nav-btn span,
        .winner-card .eduplay-nav-btn span,
        .pause-card .eduplay-nav-btn span,
        .pause-box .eduplay-nav-btn span,
        .mbox .eduplay-nav-btn span,
        .bnr-box .eduplay-nav-btn span,
        .stack .eduplay-nav-btn span,
        .btn-row .eduplay-nav-btn span {
            display: inline;
        }

        [data-bs-theme="light"] .eduplay-btn-map {
            background: rgba(14, 165, 233, 0.12);
            border-color: rgba(14, 165, 233, 0.5);
            color: #0284c7 !important;
        }
        [data-bs-theme="light"] .eduplay-btn-map:hover {
            background: #e0f2fe;
            border-color: #0284c7;
            color: #0369a1 !important;
            box-shadow: 0 0 15px rgba(14, 165, 233, 0.3);
        }
        [data-bs-theme="light"] .eduplay-btn-exit {
            background: rgba(239, 68, 68, 0.12);
            border-color: rgba(239, 68, 68, 0.45);
            color: #dc2626 !important;
        }
        [data-bs-theme="light"] .eduplay-btn-exit:hover {
            background: #fee2e2;
            border-color: #dc2626;
            color: #b91c1c !important;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.3);
        }

        /* ── Universal In-Game Navigation Exit Confirmation Modal ── */
        .eduplay-exit-overlay {
            position: fixed;
            inset: 0;
            background: rgba(3, 7, 18, 0.85);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            z-index: 10005;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: eduplayFadeIn .22s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .eduplay-exit-backdrop {
            position: absolute;
            inset: 0;
            cursor: pointer;
        }
        .eduplay-exit-card {
            position: relative;
            background: linear-gradient(160deg, #0d1222 0%, #151d32 50%, #101728 100%);
            border: 1.5px solid rgba(239, 68, 68, 0.35);
            border-radius: 26px;
            padding: 34px 28px 28px;
            max-width: 420px;
            width: 92%;
            text-align: center;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.75), 0 0 40px rgba(239, 68, 68, 0.15);
            animation: eduplayCardPop .26s cubic-bezier(0.34, 1.56, 0.64, 1);
            user-select: none;
        }
        @keyframes eduplayFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes eduplayCardPop {
            from { opacity: 0; transform: scale(0.9) translateY(12px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .eduplay-exit-icon-wrap {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.14);
            border: 2px solid rgba(239, 68, 68, 0.35);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 0 24px rgba(239, 68, 68, 0.25);
        }
        .eduplay-exit-icon {
            font-size: 2.2rem;
            line-height: 1;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.4));
        }
        .eduplay-exit-title {
            font-family: 'Outfit', 'Space Grotesk', sans-serif;
            font-weight: 800;
            font-size: 1.45rem;
            color: #ffffff;
            margin-bottom: 8px;
            letter-spacing: -0.3px;
        }
        .eduplay-exit-message {
            color: #94a3b8;
            font-size: 0.92rem;
            line-height: 1.55;
            margin-bottom: 18px;
            padding: 0 6px;
        }
        .eduplay-exit-dest-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #cbd5e1;
            font-size: 0.84rem;
            font-weight: 700;
            margin-bottom: 24px;
        }
        .eduplay-exit-dest-chip strong {
            color: #38bdf8;
        }
        .eduplay-exit-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        .eduplay-exit-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 18px;
            border-radius: 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 800;
            cursor: pointer;
            border: none;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none !important;
        }
        .eduplay-exit-btn-cancel {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #e2e8f0;
        }
        .eduplay-exit-btn-cancel:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            transform: translateY(-1px);
        }
        .eduplay-exit-btn-confirm {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: #ffffff;
            box-shadow: 0 4px 18px rgba(239, 68, 68, 0.4);
        }
        .eduplay-exit-btn-confirm:hover {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.55);
        }
        [data-bs-theme="light"] .eduplay-exit-card {
            background: #ffffff;
            border-color: rgba(239, 68, 68, 0.3);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
        }
        [data-bs-theme="light"] .eduplay-exit-title {
            color: #0f172a;
        }
        [data-bs-theme="light"] .eduplay-exit-message {
            color: #64748b;
        }
        [data-bs-theme="light"] .eduplay-exit-dest-chip {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #475569;
        }
        [data-bs-theme="light"] .eduplay-exit-btn-cancel {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #334155;
        }
        [data-bs-theme="light"] .eduplay-exit-btn-cancel:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
    </style>
    @stack('styles')
</head>
<body>

    @php
        $hideNav = View::hasSection('hide-nav') || request()->routeIs('student.play') || request()->is('student/play*') || request()->is('games/*');
    @endphp

    <!-- ── NAVBAR (hidden on game pages) ── -->
    @if(!$hideNav)
    <nav class="app-nav">
        <div class="nav-inner" style="max-width:1320px;margin:0 auto;width:100%;">

            <!-- Brand -->
            <a href="{{ url('/') }}" class="nav-brand">
                <img src="{{ asset('images/logo.png') }}" alt="EduPlay Logo">
            </a>

            <!-- Right side -->
            <div class="nav-right">
                @auth
                <!-- Theme toggle -->
                <button class="theme-btn" id="globalThemeToggle" onclick="toggleTheme()">
                    <i class="bi bi-moon-stars-fill" id="globalThemeIcon"></i>
                    <span id="globalThemeText" class="d-none d-sm-inline">Dark</span>
                </button>

                <!-- Notification Bell -->
                <a href="{{ route('notifications.index') }}" class="notif-bell" title="Notifications">
                    <i class="bi bi-bell-fill"></i>
                    @if(Auth::user()->unreadNotificationsCount() > 0)
                        <span class="notif-badge">{{ Auth::user()->unreadNotificationsCount() }}</span>
                    @endif
                </a>

                <!-- User pill -->
                <div class="user-pill d-none d-sm-flex">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</div>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                </div>

                <!-- Logout -->
                <form id="global-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                <a href="#" class="logout-btn" title="Sign out"
                   data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Sign out"
                   onclick="event.preventDefault(); confirmLogout();">
                    <i class="bi bi-power" style="font-size:1rem;"></i>
                    <span class="d-none d-sm-inline">Sign out</span>
                </a>
                @endauth
            </div>
        </div>
    </nav>
    @endif

    <!-- ── MAIN ── -->
    <main style="min-height:{{ $hideNav ? '100vh' : 'calc(100vh - 58px)' }}" class="{{ $hideNav ? 'full-height' : '' }}">
        <div class="px-0" style="{{ $hideNav ? 'height:100%;display:flex;flex-direction:column;' : '' }}">
            @if(session('success'))
            <div class="container-fluid px-4 pt-3">
                <div class="flash-alert" style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);color:#6ee7b7;">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            </div>
            @endif
            @if(session('error'))
            <div class="container-fluid px-4 pt-3">
                <div class="flash-alert" style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#fca5a5;">
                    <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
                </div>
            </div>
            @endif
            @yield('content')
        </div>
    </main>

    <!-- ── Global Confirmation Modal ── -->
    <div id="confirmModal" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;">
      <div id="confirmBackdrop" style="position:absolute;inset:0;background:rgba(4,2,10,.75);backdrop-filter:blur(6px);" onclick="closeConfirmModal()"></div>
      <div style="position:relative;background:linear-gradient(145deg,#0f0c24,#1a1035);border:1px solid rgba(255,255,255,.12);border-radius:20px;padding:36px 32px;max-width:420px;width:90%;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,.6);">
        <div style="width:60px;height:60px;border-radius:50%;background:rgba(251,191,36,.15);border:1.5px solid rgba(251,191,36,.35);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
          <i class="bi bi-exclamation-triangle-fill" style="font-size:1.6rem;color:#fbbf24;"></i>
        </div>
        <h5 id="confirmTitle" style="font-family:'Inter',sans-serif;font-weight:800;color:#fff;margin-bottom:8px;font-size:1.1rem;">Confirm Action</h5>
        <p id="confirmMessage" style="color:rgba(255,255,255,.65);font-size:.9rem;margin-bottom:26px;line-height:1.55;">Are you sure you want to proceed?</p>
        <div style="display:flex;gap:10px;justify-content:center;">
          <button onclick="closeConfirmModal()" style="flex:1;max-width:140px;padding:10px 0;border-radius:12px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06);color:rgba(255,255,255,.6);font-size:.85rem;font-weight:700;cursor:pointer;transition:background .15s;"
            onmouseover="this.style.background='rgba(255,255,255,.12)'" onmouseout="this.style.background='rgba(255,255,255,.06)'">Cancel</button>
          <button id="confirmButton" onclick="confirmModalAction()" style="flex:1;max-width:140px;padding:10px 0;border-radius:12px;border:1px solid rgba(239,68,68,.35);background:rgba(239,68,68,.18);color:#fca5a5;font-size:.85rem;font-weight:700;cursor:pointer;transition:background .15s;"
            onmouseover="this.style.background='rgba(239,68,68,.32)'" onmouseout="this.style.background='rgba(239,68,68,.18)'"><i class="bi bi-check-circle-fill me-1"></i>Confirm</button>
        </div>
      </div>
    </div>

    <!-- ── Logout confirmation modal ── -->
    <div id="logoutModal" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;">
      <div id="logoutBackdrop" style="position:absolute;inset:0;background:rgba(4,2,10,.75);backdrop-filter:blur(6px);" onclick="closeLogoutModal()"></div>
      <div style="position:relative;background:linear-gradient(145deg,#0f0c24,#1a1035);border:1px solid rgba(255,255,255,.12);border-radius:20px;padding:36px 32px;max-width:380px;width:90%;text-align:center;box-shadow:0 24px 60px rgba(0,0,0,.6);">
        <div style="width:60px;height:60px;border-radius:50%;background:rgba(239,68,68,.15);border:1.5px solid rgba(239,68,68,.35);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;">
          <i class="bi bi-power" style="font-size:1.6rem;color:#fca5a5;"></i>
        </div>
        <h5 style="font-family:'Inter',sans-serif;font-weight:800;color:#fff;margin-bottom:8px;font-size:1.1rem;">Sign out?</h5>
        <p style="color:rgba(255,255,255,.45);font-size:.85rem;margin-bottom:26px;line-height:1.55;">Your progress is saved. You can jump back in anytime!</p>
        <div style="display:flex;gap:10px;justify-content:center;">
          <button onclick="closeLogoutModal()" style="flex:1;max-width:140px;padding:10px 0;border-radius:12px;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.06);color:rgba(255,255,255,.6);font-size:.85rem;font-weight:700;cursor:pointer;transition:background .15s;"
            onmouseover="this.style.background='rgba(255,255,255,.12)'" onmouseout="this.style.background='rgba(255,255,255,.06)'">Stay</button>
          <button onclick="document.getElementById('global-logout-form').submit()" style="flex:1;max-width:140px;padding:10px 0;border-radius:12px;border:1px solid rgba(239,68,68,.35);background:rgba(239,68,68,.18);color:#fca5a5;font-size:.85rem;font-weight:700;cursor:pointer;transition:background .15s;"
            onmouseover="this.style.background='rgba(239,68,68,.32)'" onmouseout="this.style.background='rgba(239,68,68,.18)'"><i class="bi bi-power me-1"></i>Sign out</button>
        </div>
      </div>
    </div>

    <!-- ── Universal In-Game Navigation Exit Confirmation Modal ── -->
    <div class="eduplay-exit-overlay" id="eduplayExitModal" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="eduplayExitTitle">
      <div class="eduplay-exit-backdrop" onclick="eduplayCancelExit()"></div>
      <div class="eduplay-exit-card">
        <div class="eduplay-exit-icon-wrap">
          <span class="eduplay-exit-icon" id="eduplayExitIcon">🚪</span>
        </div>
        <h3 class="eduplay-exit-title" id="eduplayExitTitle">Exit to Progress?</h3>
        <p class="eduplay-exit-message" id="eduplayExitMessage">Your current progress in this round or level hasn't been saved yet. Are you sure you want to exit?</p>
        
        <div class="eduplay-exit-dest-chip" id="eduplayExitChip">
          <i class="bi bi-bar-chart-line-fill text-warning"></i> Destination: <strong id="eduplayExitDestName">My Progress</strong>
        </div>

        <div class="eduplay-exit-actions">
          <button type="button" class="eduplay-exit-btn eduplay-exit-btn-cancel" onclick="eduplayCancelExit()">
            <i class="bi bi-arrow-left"></i> Stay &amp; Play
          </button>
          <button type="button" class="eduplay-exit-btn eduplay-exit-btn-confirm" id="eduplayExitConfirmBtn" onclick="eduplayConfirmExit()">
            <i class="bi bi-box-arrow-right"></i> Yes, Exit
          </button>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Init Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            new bootstrap.Tooltip(el);
        });
    });

    // Global theme toggle — syncs with dashboard toggle if present
    function toggleTheme() {
        const html  = document.getElementById('html-root');
        const icon  = document.getElementById('globalThemeIcon');
        const text  = document.getElementById('globalThemeText');
        const isDark = html.getAttribute('data-bs-theme') !== 'light';
        if (isDark) {
            html.setAttribute('data-bs-theme','light');
            icon.className = 'bi bi-sun-fill';
            text.textContent = 'Light';
            localStorage.setItem('theme','light');
        } else {
            html.removeAttribute('data-bs-theme');
            icon.className = 'bi bi-moon-stars-fill';
            text.textContent = 'Dark';
            localStorage.setItem('theme','dark');
        }
        // sync dashboard toggle if present
        const db = document.getElementById('themeToggle');
        if (db) db.dispatchEvent(new Event('sync-theme'));
    }

    // Restore saved theme
    (function(){
        const saved = localStorage.getItem('theme');
        if (saved === 'light') {
            document.getElementById('html-root').setAttribute('data-bs-theme','light');
            const icon = document.getElementById('globalThemeIcon');
            const text = document.getElementById('globalThemeText');
            if(icon) icon.className = 'bi bi-sun-fill';
            if(text) text.textContent = 'Light';
        }
    })();

    // Logout confirmation
    function confirmLogout() {
        document.getElementById('logoutModal').style.display = 'flex';
    }
    function closeLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
    }
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') {
            closeLogoutModal();
            closeConfirmModal();
        }
    });

    // Global confirmation modal
    let confirmModalCallback = null;
    
    function showConfirmModal(message, title = 'Confirm Action') {
        return new Promise((resolve) => {
            document.getElementById('confirmTitle').textContent = title;
            document.getElementById('confirmMessage').textContent = message;
            document.getElementById('confirmModal').style.display = 'flex';
            
            // Store the callback
            confirmModalCallback = resolve;
        });
    }
    
    function closeConfirmModal() {
        document.getElementById('confirmModal').style.display = 'none';
        if (confirmModalCallback) {
            confirmModalCallback(false);
            confirmModalCallback = null;
        }
    }
    
    function confirmModalAction() {
        document.getElementById('confirmModal').style.display = 'none';
        if (confirmModalCallback) {
            confirmModalCallback(true);
            confirmModalCallback = null;
        }
    }
    
    // Override native confirm for forms with onsubmit
    window.confirmAction = function(message, title) {
        return showConfirmModal(message, title || 'Confirm Action');
    };

    // ── Universal In-Game Navigation Exit Confirmation Coordinator ──
    let eduplayPendingExitUrl = null;
    let eduplayWasPlayingBeforeExit = false;

    function isEduplayGamePaused() {
        const pBtn = document.getElementById('pauseBtn') || document.getElementById('topPauseBtn') || document.getElementById('btnPauseGame');
        if (pBtn && pBtn.classList.contains('paused')) return true;

        if (typeof window.isPaused !== 'undefined' && window.isPaused) return true;
        if (typeof window.GameState !== 'undefined' && window.GameState && window.GameState.isPaused) return true;
        if (typeof window.S !== 'undefined' && window.S && window.S.paused) return true;
        if (typeof window.state !== 'undefined' && window.state && (window.state.isPaused || window.state.paused)) return true;

        const pOv = document.getElementById('pauseOverlay');
        if (pOv && (pOv.classList.contains('show') || pOv.classList.contains('active'))) return true;

        return false;
    }

    function eduplayPauseGame() {
        const currentlyPaused = isEduplayGamePaused();
        eduplayWasPlayingBeforeExit = !currentlyPaused;

        if (eduplayWasPlayingBeforeExit) {
            if (typeof window.togglePause === 'function') {
                try { window.togglePause(); } catch (err) { console.warn('togglePause error:', err); }
            } else {
                const pBtn = document.getElementById('pauseBtn') || document.getElementById('topPauseBtn') || document.getElementById('btnPauseGame');
                if (pBtn) pBtn.click();
            }
        }
    }

    function eduplayResumeGame() {
        if (eduplayWasPlayingBeforeExit) {
            if (isEduplayGamePaused()) {
                if (typeof window.togglePause === 'function') {
                    try { window.togglePause(); } catch (err) { console.warn('togglePause error:', err); }
                } else {
                    const pBtn = document.getElementById('pauseBtn') || document.getElementById('topPauseBtn') || document.getElementById('btnPauseGame');
                    if (pBtn) pBtn.click();
                }
            }
        }
        eduplayWasPlayingBeforeExit = false;
    }

    window.requestEduplayExit = function(url, destinationName) {
        if (!url || url === '#' || url.startsWith('javascript:')) return;
        eduplayPendingExitUrl = url;

        const isMap = destinationName ? destinationName.toLowerCase().includes('map') : (url.includes('/map') || url.includes('map'));
        const modal = document.getElementById('eduplayExitModal');
        const titleEl = document.getElementById('eduplayExitTitle');
        const destEl = document.getElementById('eduplayExitDestName');
        const iconEl = document.getElementById('eduplayExitIcon');

        if (titleEl) titleEl.textContent = isMap ? 'Return to Game Map?' : 'Exit to Game Arena?';
        if (destEl) destEl.textContent = isMap ? 'Game Map' : 'Game Arena';
        if (iconEl) iconEl.textContent = isMap ? '🗺️' : '🎮';

        eduplayPauseGame();

        if (modal) {
            modal.style.display = 'flex';
            const cancelBtn = modal.querySelector('.eduplay-exit-btn-cancel');
            if (cancelBtn) cancelBtn.focus();
        } else {
            if (confirm("Are you sure you want to exit? Any unsaved progress in this level will be lost.")) {
                window.location.href = url;
            }
        }
    };

    window.eduplayCancelExit = function() {
        const modal = document.getElementById('eduplayExitModal');
        if (modal) modal.style.display = 'none';
        eduplayResumeGame();
        eduplayPendingExitUrl = null;
    };

    window.eduplayConfirmExit = function() {
        if (eduplayPendingExitUrl) {
            const dest = eduplayPendingExitUrl;
            eduplayPendingExitUrl = null;
            window.location.href = dest;
        }
    };

    // Alias window.requestExit for geometry_grid and other direct callers
    window.requestExit = function(url, destName) {
        window.requestEduplayExit(url, destName);
    };

    // Global Keydown Handler for Exit Confirmation Modal
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('eduplayExitModal');
        if (modal && modal.style.display === 'flex') {
            if (e.key === 'Escape') {
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
                eduplayCancelExit();
                return;
            }
            if (e.key === 'Enter') {
                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
                eduplayConfirmExit();
                return;
            }
        }
    }, true);

    // Global Capture Interceptor for all exit/map navigation buttons
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.eduplay-btn-exit, .eduplay-btn-map, [data-confirm-exit]');
        if (!btn) return;

        // Skip if inside the confirmation modal itself
        if (btn.closest('#eduplayExitModal')) return;

        let href = btn.getAttribute('href') || btn.dataset.href;
        if (href && href !== '#' && !href.startsWith('javascript:')) {
            e.preventDefault();
            e.stopPropagation();
            if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
            
            const isMap = btn.classList.contains('eduplay-btn-map') || href.includes('map');
            let targetUrl = href;
            let destName = 'Game Map';

            if (!isMap || btn.classList.contains('eduplay-btn-exit')) {
                // When exiting, destination is the game arena page
                targetUrl = '{{ route("student.games") }}';
                destName = 'Game Arena';
            }

            window.requestEduplayExit(targetUrl, destName);
        }
    }, true);
    </script>
    @stack('scripts')
</body>
</html>
