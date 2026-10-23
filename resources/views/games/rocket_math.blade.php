@extends('layouts.app')
@section('hide-nav', true)
@section('content')
{{-- Bootstrap CSS & Icons already loaded by layouts.app --}}
{{-- NOTE: layouts.app <head> must contain:
     <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"> --}}
<style>
        :root {
            --safe-top: env(safe-area-inset-top, 0px);
            --safe-bottom: env(safe-area-inset-bottom, 0px);
            --safe-left: env(safe-area-inset-left, 0px);
            --safe-right: env(safe-area-inset-right, 0px);
        }

        html { -webkit-text-size-adjust: 100%; }

        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
            background-attachment: fixed;
            min-height: 100vh;
            min-height: 100dvh;
            position: relative;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        /* Faster taps, no double-tap zoom on interactive game elements */
        button, a, .canister, .rm-trial-can, .rm-fc-dot { touch-action: manipulation; }
        .canister, .rm-trial-can { -webkit-user-select: none; user-select: none; }

        /* ANIMATED STARS */
        .stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }
        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: white;
            border-radius: 50%;
            animation: twinkle 3s infinite;
        }
        @keyframes twinkle {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

        .game-container {
            position: relative;
            z-index: 1;
            margin: 2rem auto;
            max-width: 1000px;
            padding-left: max(12px, var(--safe-left));
            padding-right: max(12px, var(--safe-right));
        }

        .game-card {
            background: rgba(26, 40, 70, 0.95);
            border-radius: 1.5rem;
            border: 3px solid #4a5a8a;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 30px rgba(100, 200, 255, 0.2);
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .title-section {
            background: linear-gradient(135deg, rgba(100, 181, 246, 0.2) 0%, rgba(66, 165, 245, 0.1) 100%);
            border-bottom: 2px solid #4a5a8a;
            border-radius: 1.3rem 1.3rem 0 0;
        }

        .title-text {
            color: #64b5f6;
            text-shadow: 0 0 20px rgba(100, 181, 246, 0.5);
        }

        .score-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            white-space: nowrap;
        }

        .btn-new-round {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        .btn-new-round:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        /* PROBLEM SECTION */
        .problem-section {
            background: linear-gradient(135deg, rgba(100, 181, 246, 0.15) 0%, rgba(66, 165, 245, 0.1) 100%);
            border-radius: 1rem;
            border: 2px solid rgba(100, 181, 246, 0.3);
            animation: slideInLeft 0.6s ease-out;
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .problem-label {
            color: #64b5f6;
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .problem-text {
            color: #e8f0ff;
            font-size: 1.2rem;
            line-height: 1.7;
        }

        .problem-highlight {
            background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
            color: #1a1a2e;
            padding: 0.2rem 0.5rem;
            border-radius: 0.3rem;
            font-weight: 700;
        }

        .target-hint {
            color: #ffd866;
            font-style: italic;
        }

        /* ===== ROCKET ANIMATION CONTAINER (LOTTIE) ===== */
        .rocket-animation-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 220px;
            height: 220px;
            filter: drop-shadow(0 0 25px rgba(56, 189, 248, 0.45));
            transition: all 0.3s ease;
        }

        .rocket-animation-container svg {
            width: 100% !important;
            height: 100% !important;
            transform: scale(1.3);
            transform-origin: center center;
        }

        /* The old SVG rocket is hidden but kept as a fallback */
        .rocket-container {
            display: none;
        }

        .rocket-container.shake {
            animation: rocketShake 0.4s infinite;
        }

        @keyframes rocketShake {
            0%, 100% { transform: translateX(0) rotate(0deg); }
            25% { transform: translateX(-8px) rotate(-8deg); }
            50% { transform: translateX(8px) rotate(8deg); }
            75% { transform: translateX(-8px) rotate(-8deg); }
        }

        .rocket-container.flying {
            animation: rocketLaunch 2.5s cubic-bezier(0.45, 0.05, 0.55, 0.95) forwards;
        }

        @keyframes rocketLaunch {
            0% { transform: translateY(0) translateX(0) rotate(-3deg) scale(1); opacity: 1; }
            15% { transform: translateY(-50px) translateX(0px) rotate(0deg) scale(1.1); }
            30% { transform: translateY(-150px) translateX(100px) rotate(20deg) scale(1.15); }
            50% { transform: translateY(-400px) translateX(250px) rotate(35deg) scale(1.1); opacity: 1; }
            75% { transform: translateY(-700px) translateX(450px) rotate(40deg) scale(0.6); opacity: 0.6; }
            100% { transform: translateY(-1000px) translateX(600px) rotate(45deg) scale(0.2); opacity: 0; }
        }

        .rocket-container.returning {
            animation: rocketReturn 2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes rocketReturn {
            0% { transform: translateY(-1000px) translateX(-600px) rotate(-135deg) scale(0.2); opacity: 0; }
            30% { opacity: 1; transform: translateY(-400px) translateX(-250px) rotate(-100deg) scale(0.6); }
            60% { transform: translateY(-100px) translateX(-80px) rotate(-45deg) scale(0.9); }
            85% { transform: translateY(-20px) translateX(-10px) rotate(-10deg) scale(1.05); }
            100% { transform: translateY(0) translateX(0) rotate(-3deg) scale(1); opacity: 1; }
        }

        .rocket-svg {
            width: 120px;
            height: 120px;
            filter: drop-shadow(0 0 15px rgba(255, 150, 50, 0.4));
        }

        .rocket-fire {
            animation: fireFlicker 0.2s ease-in-out infinite;
        }

        @keyframes fireFlicker {
            0%, 100% { opacity: 0.8; transform: translateY(0) scaleY(1); }
            50% { opacity: 1; transform: translateY(2px) scaleY(1.1); }
        }

        .rocket-container.flying .rocket-fire {
            animation: fireBoost 0.1s ease-in-out infinite;
            transform: scaleY(1.5);
        }

        @keyframes fireBoost {
            0%, 100% { opacity: 1; transform: scaleY(1.5); }
            50% { opacity: 0.9; transform: scaleY(1.7); }
        }

        /* GAUGE */
        .gauge-box {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 1rem;
            border: 2px solid #4a5a8a;
            height: 180px;
            position: relative;
            overflow: hidden;
        }

        .gauge-fill {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, #42a5f5, #64b5f6);
            transition: height 0.6s cubic-bezier(0.4, 0.0, 0.2, 1), background 0.3s ease;
            box-shadow: 0 0 20px rgba(100, 181, 246, 0.5);
        }

        .gauge-fill.overweight {
            background: linear-gradient(to top, #ff4444, #ff6b6b);
            animation: pulseDanger 1s infinite;
            box-shadow: 0 0 20px rgba(255, 68, 68, 0.8);
        }

        .gauge-fill.success {
            background: linear-gradient(to top, #4caf50, #8bc34a);
            animation: pulseSuccess 1s ease-in-out infinite;
            box-shadow: 0 0 20px rgba(76, 175, 80, 0.8);
        }

        @keyframes pulseDanger {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        @keyframes pulseSuccess {
            0%, 100% { box-shadow: 0 0 20px rgba(76, 175, 80, 0.8); }
            50% { box-shadow: 0 0 30px rgba(76, 175, 80, 1); }
        }

        .gauge-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: 700;
            font-size: 1.3rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            z-index: 2;
            white-space: nowrap;
        }

        .gauge-label {
            color: #8899bb;
            font-weight: 600;
            text-align: center;
        }

        /* CANISTERS */
        .panel-card {
            background: rgba(26, 40, 70, 0.5);
            border-radius: 1rem;
            border: 2px solid #4a5a8a;
        }

        .canister {
            background: rgba(100, 181, 246, 0.2);
            border: 3px solid #4a5a8a;
            border-radius: 1rem;
            padding: 1.2rem 0.8rem;
            text-align: center;
            font-size: 1.3rem;
            font-weight: 700;
            color: #64b5f6;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        .canister::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(100, 181, 246, 0.4), transparent);
            transition: left 0.5s ease;
        }

        .canister:hover:not(.used)::before { left: 100%; }

        .canister:hover:not(.used) {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(100, 181, 246, 0.4);
            border-color: #64b5f6;
        }

        .canister.attached {
            background: linear-gradient(135deg, #4caf50 0%, #8bc34a 100%);
            color: white;
            border-color: #4caf50;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.5);
            animation: canisterPulse 2s ease-in-out infinite;
        }

        @keyframes canisterPulse {
            0%, 100% { box-shadow: 0 4px 15px rgba(76, 175, 80, 0.5); }
            50% { box-shadow: 0 6px 20px rgba(76, 175, 80, 0.8); }
        }

        .canister.used { opacity: 0.3; cursor: not-allowed; }

        /* MESSAGE AREA */
        .message-area {
            background: rgba(100, 181, 246, 0.1);
            border-radius: 1rem;
            color: #64b5f6;
            font-weight: 600;
        }

        /* WARNING */
        .warning-box {
            background: rgba(255, 68, 68, 0.2);
            border: 2px solid #ff4444;
            border-radius: 1rem;
            color: #ff6b6b;
            font-weight: 700;
            animation: warningBlink 1s infinite;
        }

        @keyframes warningBlink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        /* ─── Premium Space Modal Styles ─────────────────────────── */
        .modal-backdrop.show {
            background-color: rgba(2, 6, 23, 0.78);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        #gameModal .modal-dialog { max-width: 480px; }

        .space-modal-content {
            background: linear-gradient(145deg, rgba(15, 23, 42, 0.96) 0%, rgba(30, 41, 59, 0.98) 50%, rgba(15, 23, 42, 0.96) 100%);
            border: 1.5px solid rgba(56, 189, 248, 0.4);
            border-radius: 1.5rem !important;
            color: #e2e8f0;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.85), 0 0 50px rgba(56, 189, 248, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.15);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .modal-top-bar {
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg, #38bdf8, #818cf8, #c084fc, #38bdf8);
            background-size: 200% 100%;
            animation: modalGlowBar 4s linear infinite;
        }

        @keyframes modalGlowBar {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }

        .modal-icon-badge {
            width: 76px;
            height: 76px;
            margin: 0 auto;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.22) 0%, rgba(99, 102, 241, 0.12) 70%, transparent 100%);
            border: 1.5px solid rgba(56, 189, 248, 0.5);
            box-shadow: 0 0 25px rgba(56, 189, 248, 0.35), inset 0 0 15px rgba(56, 189, 248, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: modalIconFloat 3s ease-in-out infinite;
        }

        #modalEmoji {
            font-size: 2.5rem;
            line-height: 1;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.5));
        }

        @keyframes modalIconFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-6px) scale(1.05); }
        }

        #modalTitleText {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.65rem;
            font-weight: 900;
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.02em;
            text-shadow: 0 0 25px rgba(56, 189, 248, 0.4);
        }

        .modal-desc-box {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(56, 189, 248, 0.2);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        #modalDesc {
            font-size: 1.05rem;
            color: rgba(226, 232, 240, 0.9);
            line-height: 1.65;
            font-weight: 500;
        }

        .modal-score-badge {
            background: linear-gradient(135deg, rgba(56, 189, 248, 0.15) 0%, rgba(99, 102, 241, 0.12) 100%);
            border: 1.5px solid rgba(56, 189, 248, 0.38);
            border-radius: 1rem;
            padding: 0.85rem 1.25rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        #modalScoreText {
            color: #38bdf8;
            font-weight: 700;
            font-size: 0.98rem;
            letter-spacing: 0.2px;
        }

        .modal-btn-primary {
            background: linear-gradient(135deg, #38bdf8 0%, #4f46e5 100%);
            color: #ffffff !important;
            border: none;
            border-radius: 0.85rem;
            padding: 0.85rem 1.75rem;
            font-size: 1.05rem;
            font-weight: 800;
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: 0.4px;
            box-shadow: 0 8px 25px rgba(56, 189, 248, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.3);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
        }

        .modal-btn-primary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 12px 35px rgba(56, 189, 248, 0.65), inset 0 1px 0 rgba(255, 255, 255, 0.4);
            color: #ffffff !important;
        }

        .modal-btn-primary:active { transform: scale(0.98); }

        .modal-btn-secondary {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1.5px solid rgba(255, 255, 255, 0.2) !important;
            color: #e2e8f0 !important;
            border-radius: 0.85rem;
            padding: 0.85rem 1.5rem;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .modal-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.16) !important;
            border-color: rgba(255, 255, 255, 0.4) !important;
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        .how-to-play {
            background: rgba(100, 181, 246, 0.1);
            border-radius: 1rem;
            border: 2px solid rgba(100, 181, 246, 0.3);
        }

        /* PAUSE */
        .btn-pause-rocket {
            background: rgba(56, 189, 248, 0.15);
            border: 2px solid rgba(56, 189, 248, 0.4);
            color: #38bdf8;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-pause-rocket:hover {
            background: rgba(56, 189, 248, 0.3);
            border-color: #38bdf8;
            color: #fff;
        }

        .pause-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 9998;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 16px;
        }
        .pause-overlay.active { display: flex; }
        .pause-card {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
            border: 3px solid #4a5a8a;
            border-radius: 1.5rem;
            padding: 2.5rem 3rem;
            text-align: center;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6), 0 0 30px rgba(100,200,255,0.15);
        }
        .pause-card h2 { color: #64b5f6; font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem; text-shadow: 0 0 20px rgba(100,181,246,0.5); }
        .pause-card p  { color: rgba(255,255,255,0.45); margin-bottom: 1.5rem; font-size: 0.95rem; }
        .btn-resume {
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            color: #fff;
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 2.5rem;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(56,189,248,0.4);
            transition: all 0.2s ease;
        }
        .btn-resume:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(56,189,248,0.55); }

        /* ─── Tutorial Overlay System ─────────────────────────── */
        @keyframes rmFadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
        @keyframes rmFlt{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
        @keyframes rmPopIn{from{transform:scale(0)}to{transform:scale(1)}}
        #rmTutOverlay{display:none;position:fixed;inset:0;background:rgba(10,20,50,.92);backdrop-filter:blur(10px);z-index:3000;align-items:center;justify-content:center;padding:16px;}
        #rmTutOverlay.show{display:flex;animation:rmFadeUp .4s ease;}

/* ============================================================
   ROCKET MATH FLASH CARD SYSTEM — ALWAYS ON TOP
   ============================================================ */
#rmTutOverlay.fc-overlay {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    height: 100dvh !important;
    background: rgba(2, 6, 20, 0.94) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    z-index: 999999 !important;
    display: none !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 16px !important;
    animation: rmFadeUp 0.25s cubic-bezier(.34,1.56,.64,1);
    overflow-x: hidden !important;
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch;
}
#rmTutOverlay.fc-overlay.show { display: flex !important; }

.rm-fc-deck {
    position: relative;
    width: 100%;
    max-width: 780px;
    margin: auto;
}

.rm-fc-deck-stack-1 {
    position: absolute;
    inset: 10px 18px -10px;
    background: rgba(56, 189, 248, 0.18);
    border: 1.5px solid rgba(100, 181, 246, 0.25);
    border-radius: 26px;
    transform: rotate(-2.8deg);
    z-index: 1;
    pointer-events: none;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.6);
}
.rm-fc-deck-stack-2 {
    position: absolute;
    inset: 5px 8px -5px;
    background: rgba(15, 25, 55, 0.9);
    border: 1.5px solid rgba(100, 181, 246, 0.35);
    border-radius: 26px;
    transform: rotate(2deg);
    z-index: 2;
    pointer-events: none;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.7);
}

.rm-fc-card {
    position: relative;
    z-index: 3;
    background: linear-gradient(165deg, rgba(16, 28, 60, 0.98) 0%, rgba(8, 14, 32, 0.99) 100%);
    border: 2px solid rgba(100, 181, 246, 0.55);
    border-radius: 26px;
    padding: 30px 34px 26px;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.85), 0 0 40px rgba(56, 189, 248, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    overflow: hidden;
    text-align: center;
}
.rm-fc-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3.5px;
    background: linear-gradient(90deg, #38bdf8 0%, #818cf8 35%, #4caf50 70%, #f59e0b 100%);
}

.rm-fc-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 12px;
}
.rm-fc-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(100, 181, 246, 0.18);
    border: 1px solid rgba(100, 181, 246, 0.45);
    border-radius: 99px;
    padding: 4px 12px;
    font-size: 1.1rem;
    font-weight: 800;
    color: #64b5f6;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}
.rm-fc-quick-action-btn {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.16);
    color: rgba(200, 230, 255, 0.9);
    border-radius: 99px;
    padding: 4px 12px;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.74rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.rm-fc-quick-action-btn:hover {
    background: rgba(100, 181, 246, 0.35);
    border-color: rgba(100, 181, 246, 0.6);
    color: #fff;
}
.rm-fc-quick-action-btn.is-close {
    width: 32px;
    height: 32px;
    padding: 0;
    justify-content: center;
    font-size: 1.2rem;
    border-radius: 10px;
}
.rm-fc-quick-action-btn.is-close:hover {
    background: rgba(239, 68, 68, 0.25);
    border-color: rgba(239, 68, 68, 0.5);
    color: #fff;
}

.rm-fc-slide {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 245px;
    animation: rmFcSlideIn 0.25s cubic-bezier(.34,1.56,.64,1);
}
.rm-fc-slide.hidden { display: none !important; }
@keyframes rmFcSlideIn {
    from { opacity: 0; transform: scale(0.96) translateY(6px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.rm-fc-title {
    font-family: 'Space Grotesk', sans-serif !important;
    font-size: 1.42rem !important;
    font-weight: 900 !important;
    margin: 0 0 6px !important;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
    color: #64b5f6;
}
.rm-fc-desc {
    font-size: 0.88rem;
    color: rgba(220, 235, 255, 0.85);
    line-height: 1.4;
    margin: 0 auto 12px;
    max-width: 440px;
}

.rm-fc-box {
    background: rgba(0, 0, 0, 0.35);
    border: 1.5px solid rgba(100, 181, 246, 0.25);
    border-radius: 16px;
    padding: 12px 14px;
    margin-bottom: 12px;
    width: 100%;
}
.rm-fc-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    width: 100%;
}
.rm-fc-item {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.09);
    border-radius: 12px;
    padding: 10px 10px;
    text-align: center;
}
.rm-fc-item-ico { font-size: 1.5rem; margin-bottom: 2px; }
.rm-fc-item-lbl { font-size: 0.88rem; font-weight: 800; color: #64b5f6; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 2px; }
.rm-fc-item-math { font-family: 'Space Grotesk', sans-serif; font-size: 1.05rem; font-weight: 900; color: #fff; }
.rm-fc-item-sub { font-size: 0.88rem; color: rgba(200, 225, 255, 0.75); margin-top: 3px; line-height: 1.3; }

.rm-fc-tip {
    background: rgba(100, 181, 246, 0.12);
    border: 1px solid rgba(100, 181, 246, 0.35);
    border-radius: 10px;
    padding: 6px 12px;
    font-size: 0.95rem;
    color: #e0f2fe;
    width: 100%;
    text-align: center;
    line-height: 1.35;
}
.rm-fc-tip b { color: #64b5f6; }

.rm-fc-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-top: 14px;
    margin-top: 6px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}
.rm-fc-nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.16);
    color: #fff;
    padding: 8px 16px;
    border-radius: 11px;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.84rem;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.rm-fc-nav-btn:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.14);
    transform: translateY(-1px);
}
.rm-fc-nav-btn:disabled { opacity: 0.35; cursor: not-allowed; }
.rm-fc-nav-btn.primary {
    background: linear-gradient(135deg, #0284c7 0%, #0891b2 100%);
    border: none;
    box-shadow: 0 4px 14px rgba(2, 132, 199, 0.45);
}
.rm-fc-nav-btn.primary:hover {
    box-shadow: 0 6px 20px rgba(2, 132, 199, 0.65);
    transform: translateY(-2px);
}

.rm-fc-dots { display: flex; align-items: center; gap: 6px; }
.rm-fc-dot {
    width: 8px;
    height: 8px;
    border-radius: 99px;
    background: rgba(255, 255, 255, 0.22);
    border: none;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(.34,1.56,.64,1);
    padding: 0;
}
.rm-fc-dot.active {
    width: 22px;
    background: #38bdf8;
    box-shadow: 0 0 10px rgba(56, 189, 248, 0.7);
}

        .rm-tut-box{background:linear-gradient(135deg,#1a1a2e,#0f3460);border:2.5px solid rgba(100,181,246,.45);border-radius:24px;padding:36px 38px 30px;width:100%;max-width:780px;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.5),0 0 30px rgba(100,200,255,.12);overflow-y:auto;max-height:94vh;}
        .rm-tut-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(100,181,246,.15);border:1.5px solid rgba(100,181,246,.38);border-radius:99px;padding:5px 16px;font-size:11px;font-weight:800;color:#64b5f6;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:10px;}
        .rm-tut-title{font-size:1.9rem;font-weight:900;color:#64b5f6;text-shadow:0 0 20px rgba(100,181,246,.4);line-height:1.2;margin-bottom:4px;}
        .rm-tut-sub{font-size:12.5px;color:rgba(200,220,255,.5);font-style:italic;margin-bottom:16px;}
        .rm-steps-wrap{position:relative;overflow:hidden;min-height:260px;}
        .rm-step{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;gap:12px;opacity:0;transform:translateX(60px);transition:opacity .38s cubic-bezier(.4,0,.2,1),transform .38s cubic-bezier(.4,0,.2,1);pointer-events:none;color:#e8f0ff;}
        .rm-step.active{opacity:1;transform:translateX(0);pointer-events:auto;position:relative;}
        .rm-step.exit-left{opacity:0;transform:translateX(-60px);}
        .rm-step-ico{font-size:50px;line-height:1;filter:drop-shadow(0 0 20px rgba(100,181,246,.7));animation:rmFlt 3s ease-in-out infinite;}
        .rm-step-title{font-size:1.25rem;font-weight:800;color:#64b5f6;}
        .rm-step-body{background:rgba(100,181,246,.08);border:1px solid rgba(100,181,246,.22);border-radius:12px;padding:13px 16px;text-align:left;width:100%;}
        .rm-step-body p{font-size:15px;color:rgba(220,235,255,.8);line-height:1.6;}
        .rm-step-body strong{color:#e8f0ff;font-weight:800;}
        .rm-step-hl{display:inline-flex;align-items:center;gap:4px;background:rgba(100,181,246,.2);border-radius:6px;padding:1px 8px;font-weight:700;color:#64b5f6;}
        /* Rocket Skin Button & Filters */
        .btn-skin {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.22), rgba(56, 189, 248, 0.22));
            border: 1.5px solid rgba(168, 85, 247, 0.5);
            color: #e9d5ff;
            font-weight: 700;
            font-size: 0.82rem;
            border-radius: 8px;
            padding: 5px 14px;
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }
        .btn-skin:hover {
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.4), rgba(56, 189, 248, 0.4));
            border-color: #c084fc;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(168, 85, 247, 0.45);
        }

        /* Coin Hint Button */
        .btn-hint-coin {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 1.0rem;
            border: 1px solid rgba(251, 191, 36, 0.4);
            border-radius: 0.65rem;
            padding: 0.45rem 1rem;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
            transition: all 0.22s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }
        .btn-hint-coin:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.55);
            background: linear-gradient(135deg, #fbbf24, #d97706);
            color: #ffffff !important;
        }
        .btn-hint-coin:disabled {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
            color: rgba(255, 255, 255, 0.45) !important;
            box-shadow: none;
            cursor: default;
        }

        /* demo gauge */
        .demo-gauge-wrap{display:flex;gap:12px;align-items:center;justify-content:center;margin:10px 0;}
        .demo-gauge{width:28px;height:70px;background:rgba(0,0,0,.3);border-radius:8px;border:2px solid #4a5a8a;position:relative;overflow:hidden;}
        .demo-gauge-fill{position:absolute;bottom:0;left:0;right:0;border-radius:6px;transition:height .3s;}
        .demo-gauge-fill.low{height:35%;background:linear-gradient(to top,#42a5f5,#64b5f6);}
        .demo-gauge-fill.full{height:100%;background:linear-gradient(to top,#4caf50,#8bc34a);}
        .demo-gauge-fill.over{height:100%;background:linear-gradient(to top,#ff4444,#ff6b6b);}
        /* demo canister */
        .demo-canisters{display:flex;gap:8px;justify-content:center;margin:8px 0;}
        .demo-can{background:rgba(100,181,246,.18);border:2px solid #4a5a8a;border-radius:8px;padding:8px 12px;font-size:14px;font-weight:700;color:#64b5f6;transition:all .2s;}
        .demo-can.attached{background:linear-gradient(135deg,#4caf50,#8bc34a);color:#fff;border-color:#4caf50;}
        /* dots */
        .rm-dots{display:flex;gap:8px;justify-content:center;margin:14px 0 12px;}
        .rm-dot{width:9px;height:9px;border-radius:50%;background:rgba(100,181,246,.2);border:1.5px solid rgba(100,181,246,.3);transition:all .25s;cursor:pointer;}
        .rm-dot.active{background:#64b5f6;border-color:#64b5f6;transform:scale(1.25);box-shadow:0 0 8px rgba(100,181,246,.5);}
        .rm-tut-nav{display:flex;align-items:center;justify-content:space-between;gap:10px;}
        .rm-tut-back{background:rgba(255,255,255,.07);color:rgba(255,255,255,.5);border:1px solid rgba(255,255,255,.12);padding:10px 20px;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;transition:all .15s;}
        .rm-tut-back:hover{background:rgba(255,255,255,.13);color:#fff;}
        .rm-tut-back:disabled{opacity:.3;cursor:default;}
        .rm-tut-next{background:linear-gradient(135deg,#38bdf8,#0891b2);color:#fff;padding:10px 24px;border-radius:10px;font-size:13.5px;font-weight:800;border:none;cursor:pointer;box-shadow:0 6px 22px rgba(56,189,248,.4);transition:transform .18s cubic-bezier(.34,1.56,.64,1),box-shadow .18s;}
        .rm-tut-next:hover{transform:translateY(-2px);box-shadow:0 12px 30px rgba(56,189,248,.6);}
        .rm-tut-skip{font-size:11.5px;color:rgba(100,181,246,.4);cursor:pointer;text-decoration:underline;text-underline-offset:3px;background:none;border:none;transition:color .15s;}
        .rm-tut-skip:hover{color:rgba(100,181,246,.8);}

        /* Trial overlay */
        #rmTrialOverlay{display:none;position:fixed;inset:0;background:rgba(10,20,50,.92);backdrop-filter:blur(10px);z-index:3000;align-items:center;justify-content:center;padding:16px;overflow-y:auto;}
        #rmTrialOverlay.show{display:flex;animation:rmFadeUp .4s ease;}
        .rm-trial-box{background:linear-gradient(135deg,#1a1a2e,#0f3460);border:2.5px solid rgba(100,181,246,.4);border-radius:24px;padding:34px 34px 28px;width:100%;max-width:720px;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.5);margin:auto;}
        .rm-trial-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(100,181,246,.14);border:1.5px solid rgba(100,181,246,.35);border-radius:99px;padding:5px 16px;font-size:11px;font-weight:800;color:#64b5f6;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:8px;}
        .rm-trial-title{font-size:1.2rem;font-weight:900;color:#64b5f6;margin-bottom:3px;}
        .rm-trial-sub{font-size:12.5px;color:rgba(200,220,255,.5);margin-bottom:14px;line-height:1.5;}
        /* trial gauge + canisters */
        .rm-trial-layout{display:flex;gap:16px;align-items:flex-start;margin-bottom:14px;}
        .rm-trial-gauge-col{display:flex;flex-direction:column;align-items:center;gap:6px;flex-shrink:0;}
        .rm-trial-gauge{width:36px;height:120px;background:rgba(0,0,0,.35);border-radius:10px;border:2px solid #4a5a8a;position:relative;overflow:hidden;}
        .rm-trial-gfill{position:absolute;bottom:0;left:0;right:0;border-radius:8px;transition:height .4s cubic-bezier(.4,0,.2,1),background .3s;}
        .rm-trial-gtext{font-size:16px;font-weight:700;color:rgba(200,220,255,.7);}
        .rm-trial-can-col{flex:1;}
        .rm-trial-can-title{font-size:9px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#64b5f6;margin-bottom:8px;}
        .rm-trial-cans{display:grid;grid-template-columns:repeat(2,1fr);gap:8px;}
        .rm-trial-can{background:rgba(100,181,246,.18);border:2px solid #4a5a8a;border-radius:10px;padding:10px 8px;text-align:center;font-size:16px;font-weight:700;color:#64b5f6;cursor:pointer;transition:all .2s;}
        .rm-trial-can:hover:not(.used){transform:translateY(-2px);box-shadow:0 4px 14px rgba(100,181,246,.3);border-color:#64b5f6;}
        .rm-trial-can.attached{background:linear-gradient(135deg,#4caf50,#8bc34a);color:#fff;border-color:#4caf50;box-shadow:0 4px 14px rgba(76,175,80,.4);}
        .rm-trial-can.used{opacity:.3;cursor:not-allowed;}
        .rm-trial-mission{background:rgba(100,181,246,.1);border:1px solid rgba(100,181,246,.22);border-radius:10px;padding:10px 14px;margin-bottom:14px;font-size:14px;color:#e8f0ff;line-height:1.5;}
        .rm-trial-status{font-size:13px;color:rgba(200,220,255,.6);margin-bottom:10px;min-height:20px;}
        .rm-trial-skip{font-size:11px;color:rgba(100,181,246,.4);cursor:pointer;background:none;border:none;text-decoration:underline;text-underline-offset:3px;transition:color .15s;padding:8px;}
        .rm-trial-skip:hover{color:rgba(100,181,246,.75);}

        /* Ready modal */
        #rmReadyOverlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.82);backdrop-filter:blur(8px);z-index:3100;align-items:center;justify-content:center;padding:16px;overflow-y:auto;}
        #rmReadyOverlay.show{display:flex;animation:rmFadeUp .3s ease;}
        .rm-ready-box{background:linear-gradient(135deg,#1a1a2e,#0f3460);border:2.5px solid rgba(100,181,246,.5);border-radius:24px;padding:34px 28px;text-align:center;max-width:380px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.6);animation:rmPopIn .45s cubic-bezier(.34,1.56,.64,1);color:#e8f0ff;margin:auto;}
        .rm-ready-ico{font-size:56px;line-height:1;margin-bottom:8px;animation:rmFlt 3s ease-in-out infinite;}
        .rm-ready-stars{font-size:24px;letter-spacing:6px;margin-bottom:8px;}
        .rm-ready-title{font-size:1.4rem;font-weight:900;color:#64b5f6;margin-bottom:4px;text-shadow:0 0 16px rgba(100,181,246,.4);}
        .rm-ready-sub{font-size:13px;color:rgba(200,220,255,.6);line-height:1.6;margin-bottom:16px;}
        .rm-ready-start{background:linear-gradient(135deg,#38bdf8,#0891b2);color:#fff;font-size:14.5px;font-weight:900;padding:13px 28px;border-radius:12px;border:none;cursor:pointer;box-shadow:0 8px 26px rgba(56,189,248,.4);transition:transform .18s cubic-bezier(.34,1.56,.64,1),box-shadow .18s;display:block;width:100%;margin-bottom:8px;}
        .rm-ready-start:hover{transform:translateY(-3px);box-shadow:0 16px 36px rgba(56,189,248,.6);}
        .rm-ready-practice{background:rgba(100,181,246,.1);color:#64b5f6;border:1.5px solid rgba(100,181,246,.3);padding:10px 22px;border-radius:11px;font-size:13px;font-weight:700;cursor:pointer;transition:all .15s;display:block;width:100%;}
        .rm-ready-practice:hover{background:rgba(100,181,246,.18);color:#90caf9;}

        /* =====================================================================
           ADAPTIVE / MOBILE-FIRST OVERRIDES
           Breakpoints: ≤991 tablet · ≤767 phone · ≤575 small phone · landscape
           ===================================================================== */

        /* Touch devices: remove sticky hover lifts, give bigger tap feedback */
        @media (hover: none) {
            .canister:hover:not(.used),
            .btn-new-round:hover,
            .modal-btn-primary:hover,
            .modal-btn-secondary:hover,
            .btn-resume:hover,
            .btn-hint-coin:hover:not(:disabled),
            .rm-fc-nav-btn:hover:not(:disabled),
            .rm-trial-can:hover:not(.used),
            .rm-ready-start:hover { transform: none; }
            .canister:active:not(.used),
            .rm-trial-can:active:not(.used) { transform: scale(0.95); }
            .btn-hint-coin:active, .rm-fc-nav-btn:active, .btn-resume:active { transform: scale(0.96); }
        }

        /* ---------- Tablet (≤ 991px) ---------- */
        @media (max-width: 991.98px) {
            .game-container { margin: 1rem auto; }
            .rocket-animation-container { width: 190px; height: 190px; }
            .gauge-box { height: 160px; }
        }

        /* ---------- Phone (≤ 767px) ---------- */
        @media (max-width: 767.98px) {
            body { background-attachment: scroll; }

            .game-container {
                margin: 0 auto;
                padding-top: calc(8px + var(--safe-top));
                padding-bottom: calc(12px + var(--safe-bottom));
            }
            .game-card {
                border-width: 2px;
                border-radius: 1.1rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            }
            .title-section { padding: 0.75rem !important; border-radius: 1rem 1rem 0 0; }
            .game-card .p-4 { padding: 0.75rem !important; }
            .game-card .p-4.pt-0 { padding-top: 0 !important; }
            .title-text { font-size: 1.35rem; text-align: center; }

            /* Header: title on top, badges in a compact wrapping row */
            .title-section .col-md-7 { justify-content: center !important; gap: 6px !important; }
            .score-badge { padding: 0.3rem 0.6rem; font-size: 0.8rem; border-radius: 0.6rem; }

            /* Nav buttons: horizontal row of icon-only buttons */
            .title-section .eduplay-game-nav {
                display: flex;
                width: 100%;
                gap: 6px;
                justify-content: center;
                margin: 4px 0 0 !important;
            }
            .title-section .eduplay-game-nav .eduplay-nav-btn {
                flex: 1 1 0;
                justify-content: center;
                padding: 8px 6px;
                min-height: 40px;
            }
            .title-section .eduplay-game-nav .eduplay-nav-btn span { display: none; }

            /* Mission briefing */
            .problem-section { padding: 0.85rem !important; border-width: 1.5px; }
            .problem-label { font-size: 0.8rem; margin-bottom: 0.4rem !important; }
            .problem-text { font-size: 1rem; line-height: 1.55; margin-bottom: 0.6rem !important; }
            .problem-highlight { padding: 0.1rem 0.35rem; }
            .target-hint .d-flex { justify-content: center !important; }
            .btn-hint-coin { width: 100%; justify-content: center; font-size: 0.92rem; min-height: 44px; }

            /* Rocket + gauge stack vertically & centre */
            .panel-card { border-width: 1.5px; }
            .panel-card.p-4 { padding: 0.75rem !important; }
            .panel-card .row.align-items-center { flex-direction: column; text-align: center; margin-bottom: 0.5rem !important; }
            .panel-card .row.align-items-center > .col-auto,
            .panel-card .row.align-items-center > .col { width: 100%; flex: 0 0 100%; max-width: 100%; }
            .rocket-animation-container { width: 150px; height: 150px; margin: 0 auto 0.5rem; }
            .gauge-box { height: 90px; border-radius: 0.75rem; }
            .gauge-text { font-size: 1.05rem; }
            .gauge-label { font-size: 0.85rem; }
            .warning-box { font-size: 0.85rem; padding: 0.5rem !important; }

            /* Canisters: 4 per row is too tight, keep 2 per row but large tap targets */
            #canisterGrid { --bs-gutter-x: 0.5rem; --bs-gutter-y: 0.5rem; }
            .canister {
                padding: 0.9rem 0.4rem;
                font-size: 1.15rem;
                min-height: 56px;
                border-width: 2px;
                border-radius: 0.85rem;
            }

            .message-area { font-size: 0.9rem; padding: 0.6rem !important; border-radius: 0.8rem; }

            /* Results modal: bottom-sheet style */
            #gameModal .modal-dialog { margin: 0.75rem; max-width: none; }
            .space-modal-content { border-radius: 1.2rem !important; }
            .modal-icon-badge { width: 60px; height: 60px; }
            #modalEmoji { font-size: 2rem; }
            #modalTitleText { font-size: 1.35rem; }
            #modalDesc { font-size: 0.95rem; }
            #gameModal .modal-footer { flex-direction: column; align-items: stretch !important; }
            #gameModal .modal-footer > * { width: 100%; justify-content: center; margin: 0; }
            .modal-btn-primary { padding: 0.8rem 1rem; font-size: 1rem; min-height: 48px; }

            /* Pause card */
            .pause-card { padding: 1.5rem 1.25rem; border-width: 2px; }
            .pause-card h2 { font-size: 1.6rem; }
            .pause-card kbd { display: none; }
            .pause-card p { font-size: 0.85rem; }
            .btn-resume { width: 100%; min-height: 48px; }

            /* Flash-card tutorial deck */
            #rmTutOverlay.fc-overlay { padding: 10px !important; align-items: flex-start !important; padding-top: calc(10px + var(--safe-top)) !important; padding-bottom: calc(10px + var(--safe-bottom)) !important; }
            .rm-fc-deck-stack-1, .rm-fc-deck-stack-2 { display: none; }
            .rm-fc-card { padding: 20px 14px 16px; border-radius: 20px; }
            .rm-fc-top { flex-wrap: wrap; justify-content: center; }
            .rm-fc-badge { font-size: 0.68rem; letter-spacing: 0.4px; padding: 4px 10px; text-align: center; }
            .rm-fc-slide { min-height: 0; }
            .rm-fc-title { font-size: 1.15rem !important; }
            .rm-fc-desc { font-size: 0.84rem; }
            .rm-fc-box { padding: 8px; }
            .rm-fc-row-2 { gap: 8px; }
            .rm-fc-item { padding: 8px 6px; }
            .rm-fc-item-lbl { font-size: 0.72rem; letter-spacing: 0.4px; }
            .rm-fc-item-sub { font-size: 0.74rem; }
            .rm-fc-item-math { font-size: 0.95rem; }
            .rm-fc-tip { font-size: 0.82rem; }
            .rm-fc-footer { gap: 6px; }
            .rm-fc-nav-btn { padding: 10px 12px; min-height: 44px; font-size: 0.8rem; }
            .rm-fc-dot { width: 7px; height: 7px; }
            .rm-fc-dot.active { width: 16px; }

            /* Trial + ready overlays */
            #rmTrialOverlay { align-items: flex-start; padding-top: calc(12px + var(--safe-top)); padding-bottom: calc(12px + var(--safe-bottom)); }
            .rm-trial-box { padding: 20px 14px 16px; border-radius: 20px; }
            .rm-trial-layout { flex-direction: column; align-items: center; gap: 10px; }
            .rm-trial-gauge { width: 100%; height: 22px; }
            .rm-trial-gfill { top: 0; bottom: 0; right: auto; width: 0; height: 100% !important; }
            .rm-trial-gauge-col { width: 100%; }
            .rm-trial-can-col { width: 100%; }
            .rm-trial-can { min-height: 48px; }
            .rm-ready-box { padding: 24px 18px; }
            .rm-ready-start, .rm-ready-practice { min-height: 48px; }
        }

        /* ---------- Small phones (≤ 575px) ---------- */
        @media (max-width: 575.98px) {
            .title-text { font-size: 1.2rem; }
            .score-badge { font-size: 0.74rem; padding: 0.28rem 0.5rem; }
            .problem-text { font-size: 0.95rem; }
            .rocket-animation-container { width: 130px; height: 130px; }
            .canister { font-size: 1.05rem; min-height: 52px; }
            .rm-fc-badge { font-size: 0.62rem; }
        }

        /* ---------- Landscape phones (short viewport): side-by-side layout ---------- */
        @media (max-height: 500px) and (orientation: landscape) {
            .game-container { margin: 0 auto; }
            .rocket-animation-container { width: 110px; height: 110px; }
            .gauge-box { height: 70px; }
            .panel-card .row.align-items-center { flex-direction: row; }
            .panel-card .row.align-items-center > .col-auto { width: auto; flex: 0 0 auto; }
            .panel-card .row.align-items-center > .col { flex: 1 1 0; width: auto; }
            .canister { min-height: 44px; padding: 0.5rem 0.3rem; }
            .rm-fc-slide { min-height: 0; }
            .modal-icon-badge { display: none; }
        }

        /* Respect reduced-motion preferences (also saves battery on phones) */
        @media (prefers-reduced-motion: reduce) {
            .star, .canister.attached, .gauge-fill.overweight, .gauge-fill.success,
            .warning-box, .modal-icon-badge, .modal-top-bar { animation: none !important; }
        }
</style>

<!-- STARS BACKGROUND -->
<div class="stars" id="starsContainer"></div>

    <div class="game-container">
        <div class="game-card">
            <!-- HEADER -->
            <div class="title-section p-4">
                <div class="row align-items-center g-3">
                    <div class="col-md-5">
                        <h1 class="title-text mb-0">
                            <i class="bi bi-rocket-takeoff"></i> Rocket Math
                        </h1>
                    </div>
                    <div class="col-md-7 text-md-end d-flex align-items-center justify-content-md-end justify-content-start flex-wrap gap-2">
                        <span class="score-badge" style="background: linear-gradient(135deg, #f59e0b, #d97706); box-shadow: 0 4px 15px rgba(245, 158, 11, 0.35);">
                            🪙 <span id="coinDisplay">50</span>
                        </span>
                        <span class="score-badge">
                            <i class="bi bi-rocket-takeoff"></i> Launched: <span id="scoreDisplay">0</span><span style="opacity:.5;font-size:.85em"> / 10</span>
                        </span>
                        <span class="score-badge" id="sessionTimerDisplay">
                            <i class="bi bi-clock"></i> <span id="sessionTimerText">5:00</span>
                        </span>
                        <span class="score-badge" id="levelDisplay" style="background:linear-gradient(135deg,#10b981,#059669);">
                            <i class="bi bi-layers"></i> Level <span id="levelText">{{ $progress->current_level }}</span>
                        </span>
                        <div class="eduplay-game-nav ms-1">
                            <button type="button" class="eduplay-nav-btn eduplay-btn-help" id="helpNavBtn" title="How to Play" onclick="rmOpenTut(true)">
                                <i class="bi bi-question-circle-fill"></i> <span>Tutorial</span>
                            </button>
                            <button type="button" class="eduplay-nav-btn eduplay-btn-pause" id="pauseBtn" title="Pause Game" onclick="togglePause()">
                                <i class="bi bi-pause-fill"></i> <span>Pause</span>
                            </button>
                            <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
                            <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Progress"><i class="bi bi-box-arrow-right"></i> <span>Exit</span></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PROBLEM SECTION -->
            <div class="p-4">
                <div class="problem-section p-4">
                    <div class="problem-label mb-2">
                        <i class="bi bi-journal-text"></i> MISSION BRIEFING
                    </div>
                    <div class="problem-text mb-3" id="problemText">
                        Loading mission...
                    </div>
                    <div class="target-hint" id="targetHint">
                        <!-- Loaded dynamically: Hidden by default, reveals on coin spend -->
                    </div>
                </div>
            </div>

            <!-- MAIN GAME AREA -->
            <div class="p-4 pt-0">
                <div class="row g-3">
                    <!-- LEFT: ROCKET & GAUGE -->
                    <div class="col-lg-7">
                        <div class="panel-card p-4">
                            <div class="row align-items-center mb-3">
                                <div class="col-auto">
                                    {{-- === LOTTIE ROCKET ANIMATION CONTAINER === --}}
                                    <div class="rocket-animation-container" id="rocketAnimation">
                                        {{-- The Lottie animation will be injected here by lottie-web --}}
                                    </div>

                                    {{-- OLD SVG ROCKET (hidden, kept as fallback) --}}
                                    <div class="rocket-container" id="rocketContainer">
                                        <svg class="rocket-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                            <!-- Rocket Body -->
                                            <path d="M50 10 L60 70 L40 70 Z" fill="#64b5f6" stroke="#42a5f5" stroke-width="2"/>
                                            <!-- Rocket Nose -->
                                            <circle cx="50" cy="10" r="8" fill="#ff6b6b"/>
                                            <!-- Windows -->
                                            <circle cx="50" cy="30" r="5" fill="#fff" opacity="0.8"/>
                                            <circle cx="50" cy="45" r="5" fill="#fff" opacity="0.8"/>
                                            <!-- Wings -->
                                            <path d="M40 60 L25 80 L40 70 Z" fill="#42a5f5"/>
                                            <path d="M60 60 L75 80 L60 70 Z" fill="#42a5f5"/>
                                            <!-- Fire (animated) -->
                                            <g class="rocket-fire">
                                                <path d="M45 70 L40 85 L50 78 Z" fill="#ff6b6b" opacity="0.9"/>
                                                <path d="M50 70 L45 90 L55 90 L50 78 Z" fill="#ffa500" opacity="0.9"/>
                                                <path d="M55 70 L60 85 L50 78 Z" fill="#ff6b6b" opacity="0.9"/>
                                                <path d="M48 78 L50 88 L52 78 Z" fill="#ffeb3b" opacity="0.9"/>
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="gauge-box">
                                        <div class="gauge-fill" id="gaugeFill"></div>
                                        <div class="gauge-text" id="totalDisplay">0.00 / 10.00</div>
                                    </div>
                                    <div class="gauge-label mt-2" id="fuelStatus">
                                        <i class="bi bi-fuel-pump"></i> Load fuel to match target
                                    </div>
                                </div>
                            </div>
                            <div class="warning-box p-3 text-center" id="warningArea" style="display:none;">
                                <i class="bi bi-exclamation-triangle"></i> OVERWEIGHT! Remove some fuel canisters!
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: CANISTERS -->
                    <div class="col-lg-5">
                        <div class="panel-card p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span style="color: #8899bb; font-weight: 600;">
                                    <i class="bi bi-droplet"></i> Fuel Canisters
                                </span>
                                <span style="color: #6aaa7a; font-weight: 600;" id="attachedCount">
                                    Attached: 0
                                </span>
                            </div>
                            <div class="row g-2" id="canisterGrid">
                                <!-- Dynamically generated -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MESSAGE AREA -->
            <div class="p-4 pt-0">
                <div class="message-area p-3 text-center" id="messageArea">
                    Click a canister to attach it to the rocket
                </div>
            </div>
        </div>
    </div>

    <!-- PAUSE OVERLAY -->
    <div class="pause-overlay" id="pauseOverlay">
        <div class="pause-card">
            <div style="font-size:3rem;margin-bottom:0.5rem;">⏸</div>
            <h2>Game Paused</h2>
            <p>Press <kbd style="background:rgba(255,255,255,0.15);border-radius:5px;padding:2px 8px;font-size:13px;">P</kbd> or click Resume to continue</p>
            <button class="btn-resume" onclick="togglePause()">▶ Resume Mission</button>
            <div class="eduplay-game-nav" style="display:flex;gap:10px;width:100%;justify-content:center;margin-top:14px;">
                <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" style="flex:1;">
                    <i class="bi bi-map-fill"></i> <span>Game Map</span>
                </a>
                <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit" style="flex:1;">
                    <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
                </a>
            </div>
        </div>
    </div>

    <!-- FLASH CARD INSTRUCTIONS & TUTORIAL DECK -->
    <div id="rmTutOverlay" class="fc-overlay" role="dialog" aria-modal="true" aria-label="Rocket Math Instructions">
        <div class="rm-fc-deck">
            <!-- Physical Stack Layers -->
            <div class="rm-fc-deck-stack-1"></div>
            <div class="rm-fc-deck-stack-2"></div>

            <!-- Active Front Flash Card -->
            <div class="rm-fc-card">
                <div class="rm-fc-top">
                    <span class="rm-fc-badge" id="rmFcBadge">🃏 HOW TO PLAY • CARD 1 OF 4</span>
                    <button class="rm-fc-quick-action-btn" id="rmFcQuickActionBtn" type="button" onclick="rmCloseTut(true)" title="Skip to Play">
                        <span>Skip to Play</span> <i class="bi bi-arrow-right-short"></i>
                    </button>
                </div>

                <!-- CARD 1: Read Mission Briefing -->
                <div id="rmFcSlide1" class="rm-fc-slide">
                    <h3 class="rm-fc-title"><span>📖</span> Read Mission Briefing</h3>
                    <p class="rm-fc-desc">Each mission features a story problem at the top. Calculate the math answer to find your rocket's target fuel!</p>
                    <div class="rm-fc-row-2 rm-fc-box">
                        <div class="rm-fc-item">
                            <div class="rm-fc-item-ico">📜</div>
                            <div class="rm-fc-item-lbl">Story Problem</div>
                            <div class="rm-fc-item-sub">"5L for takeoff + 3L for landing. Total fuel?"</div>
                        </div>
                        <div class="rm-fc-item">
                            <div class="rm-fc-item-ico">🎯</div>
                            <div class="rm-fc-item-lbl">Target Fuel</div>
                            <div class="rm-fc-item-math" style="color:#38bdf8;margin-top:6px;">8.0 Liters</div>
                        </div>
                    </div>
                    <div class="rm-fc-tip">
                        💡 <b>Concept:</b> Solve the word problem to know the exact fuel target!
                    </div>
                </div>

                <!-- CARD 2: Load Fuel Canisters -->
                <div id="rmFcSlide2" class="rm-fc-slide hidden">
                    <h3 class="rm-fc-title" style="color:#34d399;"><span>⛽</span> Load Fuel Canisters</h3>
                    <p class="rm-fc-desc">Tap fuel canisters to attach them to the rocket. Tap again to remove them!</p>
                    <div class="rm-fc-row-2 rm-fc-box">
                        <div class="rm-fc-item">
                            <div class="rm-fc-item-ico">⛽</div>
                            <div class="rm-fc-item-lbl" style="color:#34d399;">Canister 3.0L</div>
                            <div class="rm-fc-item-sub">Attached ✓ (Glows green)</div>
                        </div>
                        <div class="rm-fc-item">
                            <div class="rm-fc-item-ico">⛽</div>
                            <div class="rm-fc-item-lbl" style="color:#34d399;">Canister 5.0L</div>
                            <div class="rm-fc-item-sub">Attached ✓ (3.0 + 5.0 = 8.0L)</div>
                        </div>
                    </div>
                    <div class="rm-fc-tip">
                        💡 <b>Loading Strategy:</b> Combine canisters to equal the target fuel amount!
                    </div>
                </div>

                <!-- CARD 3: Watch the Fuel Gauge -->
                <div id="rmFcSlide3" class="rm-fc-slide hidden">
                    <h3 class="rm-fc-title" style="color:#fbbf24;"><span>⚡</span> Hit Exact Fuel Level</h3>
                    <p class="rm-fc-desc">The fuel gauge must be filled to <b>exactly</b> the target amount:</p>
                    <div class="rm-fc-box" style="display:flex;justify-content:space-around;text-align:center;">
                        <div>
                            <div style="font-size:1.4rem;">🔵</div>
                            <div style="font-size:0.75rem;font-weight:700;color:#64b5f6;">Too Low</div>
                            <div style="font-size:0.68rem;color:rgba(200,225,255,0.6);">Needs more</div>
                        </div>
                        <div>
                            <div style="font-size:1.4rem;">🚀</div>
                            <div style="font-size:0.75rem;font-weight:800;color:#34d399;">Exact Match!</div>
                            <div style="font-size:0.68rem;color:#a7f3d0;">Auto Blastoff!</div>
                        </div>
                        <div>
                            <div style="font-size:1.4rem;">🔴</div>
                            <div style="font-size:0.75rem;font-weight:700;color:#f87171;">Overloaded</div>
                            <div style="font-size:0.68rem;color:rgba(200,225,255,0.6);">Detach a can</div>
                        </div>
                    </div>
                    <div class="rm-fc-tip">
                        💡 <b>Careful:</b> Don't overload! When fuel hits the exact number, launch begins!
                    </div>
                </div>

                <!-- CARD 4: Session Timer & Goals -->
                <div id="rmFcSlide4" class="rm-fc-slide hidden">
                    <h3 class="rm-fc-title" style="color:#f59e0b;"><span>⏱️</span> 5-Min Timer &amp; Goals</h3>
                    <p class="rm-fc-desc">Launch as many rockets as you can in 5 minutes! Launch <b>8–10 rockets</b> to pass the level.</p>
                    <div class="rm-fc-row-2 rm-fc-box">
                        <button type="button" class="rm-fc-item" onclick="rmStartPracticeFromTut()" style="cursor:pointer;background:rgba(56,189,248,0.14);border-color:rgba(56,189,248,0.4);color:#fff;">
                            <div class="rm-fc-item-ico">🎮</div>
                            <div class="rm-fc-item-lbl" style="color:#38bdf8;">Practice Round</div>
                            <div class="rm-fc-item-sub">Test 5.0L target first</div>
                        </button>
                        <button type="button" class="rm-fc-item" onclick="rmCloseTut(true)" style="cursor:pointer;background:rgba(16,185,129,0.16);border-color:rgba(52,211,153,0.4);color:#fff;">
                            <div class="rm-fc-item-ico">🚀</div>
                            <div class="rm-fc-item-lbl" style="color:#34d399;">Real Mission</div>
                            <div class="rm-fc-item-sub">Start 5-min session</div>
                        </button>
                    </div>
                    <div class="rm-fc-tip">
                        💡 <b>Pro Tip:</b> Spend coins for hints, or click "New Round" if you want a fresh mission!
                    </div>
                </div>

                <!-- Flashcard Footer Navigation -->
                <div class="rm-fc-footer">
                    <button class="rm-fc-nav-btn" id="rmFcPrevBtn" type="button" onclick="rmStepFlashCard(-1)">
                        <i class="bi bi-arrow-left"></i> Prev
                    </button>

                    <div class="rm-fc-dots" id="rmFcDots" role="tablist" aria-label="Flash Cards">
                        <button class="rm-fc-dot active" type="button" onclick="rmGoFlashCard(1)" title="Card 1"></button>
                        <button class="rm-fc-dot" type="button" onclick="rmGoFlashCard(2)" title="Card 2"></button>
                        <button class="rm-fc-dot" type="button" onclick="rmGoFlashCard(3)" title="Card 3"></button>
                        <button class="rm-fc-dot" type="button" onclick="rmGoFlashCard(4)" title="Card 4"></button>
                    </div>

                    <button class="rm-fc-nav-btn primary" id="rmFcNextBtn" type="button" onclick="rmStepFlashCard(1)">
                        <span>Next</span> <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- RESULTS MODAL -->
    <div class="modal fade" id="gameModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content space-modal-content">
                <div class="modal-top-bar"></div>
                <div class="modal-header border-0 pt-4 pb-1 d-flex flex-column align-items-center">
                    <div class="modal-icon-badge mb-3">
                        <span id="modalEmoji">🎉</span>
                    </div>
                    <h4 class="modal-title m-0 text-center" id="modalTitle">
                        <span id="modalTitleText">Launch Successful!</span>
                    </h4>
                </div>
                <div class="modal-body text-center px-4 py-3">
                    <div class="modal-desc-box">
                        <p id="modalDesc" class="m-0">You fueled the rocket perfectly!</p>
                    </div>
                    <div id="modalScoreInfo" class="modal-score-badge mt-3" style="display:none;">
                        <span id="modalScoreText"></span>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-1 d-flex gap-2 align-items-center">
                    <button type="button" class="btn modal-btn-primary flex-grow-1" id="modalBtn">
                        <i class="bi bi-arrow-clockwise"></i> Next Mission
                    </button>
                    <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" id="mapBtn" style="display:none;">
                        <i class="bi bi-map-fill"></i> <span>Game Map</span>
                    </a>
                    <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit">
                        <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- TRIAL OVERLAY -->
    <div id="rmTrialOverlay">
        <div class="rm-trial-box">
            <div class="rm-trial-badge">🎮 Practice Round</div>
            <h2 class="rm-trial-title">Trial Mission</h2>
            <p class="rm-trial-sub">Load exactly <strong id="rmTrialTarget">5</strong> liters of fuel by tapping canisters!</p>
            <div class="rm-trial-mission" id="rmTrialMissionText">The alien captain needs <strong>2 liters</strong> for takeoff and <strong>3 liters</strong> for landing. How much fuel in total?</div>
            <div class="rm-trial-layout">
                <div class="rm-trial-gauge-col">
                    <div class="rm-trial-gauge"><div class="rm-trial-gfill" id="rmTrialGaugeFill"></div></div>
                    <div class="rm-trial-gtext"><span id="rmTrialTotal">0.0</span> / 5.0L</div>
                </div>
                <div class="rm-trial-can-col">
                    <div class="rm-trial-can-title">⛽ Fuel Canisters</div>
                    <div class="rm-trial-cans" id="rmTrialCans"></div>
                </div>
            </div>
            <div class="rm-trial-status" id="rmTrialStatus">Tap canisters to load fuel into the gauge!</div>
            <button class="rm-trial-skip" onclick="rmSkipToRealGame()">Skip practice &amp; start now</button>
        </div>
    </div>

    <!-- READY OVERLAY -->
    <div id="rmReadyOverlay">
        <div class="rm-ready-box">
            <div class="rm-ready-ico">🚀</div>
            <div class="rm-ready-stars">⭐⭐⭐</div>
            <h2 class="rm-ready-title">Trial Complete!</h2>
            <p class="rm-ready-sub">You loaded the perfect amount of fuel!<br>Ready to start the real mission?</p>
            <button class="rm-ready-start" onclick="rmStartRealGame()">🚀 Start Real Mission!</button>
            <button class="rm-ready-practice" onclick="rmPracticeAgain()">🔁 Practice Again</button>
        </div>
    </div>

    {{-- Bootstrap JS already loaded by layouts.app --}}

    {{-- === LOTTIE-WEB LIBRARY (CDN) === --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>

    <script>
        const RM_TUTORIAL_KEY = 'rm_tutorial_done';

        // ===== PRACTICE ROUND CONFIG (these were referenced but never defined) =====
        const RM_TRIAL_TARGET = 5.0;
        const RM_TRIAL_CANS   = [1.0, 2.0, 3.0, 4.0];
        let rmTrialAttached   = new Set();
        let rmTrialTotal      = 0;
        let rmTutStep         = 0;

        // ===== WORD PROBLEMS DATABASE =====
        const WORD_PROBLEMS = {
            easy: [
                { text: "The alien captain needs <span class='problem-highlight'>5 liters</span> for takeoff and <span class='problem-highlight'>3 more liters</span> for landing. How much fuel in total?", answer: 8.0 },
                { text: "Your spaceship has <span class='problem-highlight'>12 liters</span> of fuel. You use <span class='problem-highlight'>6 liters</span> to escape Earth's gravity. How much fuel remains?", answer: 6.0 },
                { text: "A space station delivers <span class='problem-highlight'>4 liters</span> of fuel in the morning and <span class='problem-highlight'>5 liters</span> in the evening. How much fuel was delivered?", answer: 9.0 },
                { text: "The rocket needs <span class='problem-highlight'>15 liters</span> to reach Mars, but you already have <span class='problem-highlight'>8 liters</span> loaded. How much more fuel do you need?", answer: 7.0 },
                { text: "Each of <span class='problem-highlight'>3 fuel tanks</span> holds <span class='problem-highlight'>2 liters</span>. How many liters in total?", answer: 6.0 },
                { text: "Mission Control says you need <span class='problem-highlight'>10 liters</span> for launch. You've loaded <span class='problem-highlight'>4 liters</span>. How much more?", answer: 6.0 },
            ],
            medium: [
                { text: "Your rocket needs <span class='problem-highlight'>3.5 liters</span> per thruster and has <span class='problem-highlight'>2 thrusters</span>. How much fuel total?", answer: 7.0 },
                { text: "The fuel gauge shows <span class='problem-highlight'>9.25 liters</span>. You burn <span class='problem-highlight'>2.5 liters</span> during takeoff. How much remains?", answer: 6.75 },
                { text: "Each alien crew member needs <span class='problem-highlight'>1.25 liters</span> for their escape pod. There are <span class='problem-highlight'>5 crew members</span>. How much fuel needed?", answer: 6.25 },
                { text: "You have <span class='problem-highlight'>15 liters</span> of fuel. The journey to the moon uses <span class='problem-highlight'>8.75 liters</span>. How much is left?", answer: 6.25 },
                { text: "Three cargo ships each need <span class='problem-highlight'>2.5 liters</span>. How many liters total?", answer: 7.5 },
                { text: "Your ship starts with <span class='problem-highlight'>12.5 liters</span>. After using <span class='problem-highlight'>5.25 liters</span>, how much remains?", answer: 7.25 },
            ],
            hard: [
                { text: "A rocket burns <span class='problem-highlight'>1.5 liters per minute</span> for <span class='problem-highlight'>4 minutes</span>, then needs <span class='problem-highlight'>1.25 liters</span> for landing. Total fuel?", answer: 7.25 },
                { text: "Four satellites each need <span class='problem-highlight'>1.75 liters</span> for deployment. How much fuel total?", answer: 7.0 },
                { text: "Your mission needs <span class='problem-highlight'>18.5 liters</span> total. The main tank holds <span class='problem-highlight'>11.75 liters</span>. How much more from reserve tanks?", answer: 6.75 },
                { text: "Each of <span class='problem-highlight'>8 boosters</span> uses <span class='problem-highlight'>0.875 liters</span>. How much fuel total?", answer: 7.0 },
                { text: "Starting with <span class='problem-highlight'>20 liters</span>, you use <span class='problem-highlight'>7.5 liters</span> for orbit, then <span class='problem-highlight'>5.75 liters</span> for maneuvers. How much remains?", answer: 6.75 },
                { text: "A space shuttle consumes <span class='problem-highlight'>2.25 liters</span> per stage for <span class='problem-highlight'>3 stages</span>. Total fuel needed?", answer: 6.75 },
            ]
        };

        // ===== GAME STATE =====
        const WIN_SCORE    = 8;   // minimum correct launches to pass
        const MAX_SCORE    = 10;  // launches cap per session
        const SESSION_SECS = 300; // 5 minutes
        const MAX_LEVEL    = {{ $game->total_levels }};
        const GAME_ID      = {{ $game->id }};
        let currentLevel   = {{ $progress->current_level }};

        // ===== COINS SYSTEM (50 initial coins, 5 per hint, Metric Quest reward logic) =====
        const COINS_KEY = 'eduplay_rocket_coins_' + '{{ auth()->id() ?? 0 }}';

        function getStoredCoins() {
            const stored = localStorage.getItem(COINS_KEY);
            if (stored === null) {
                localStorage.setItem(COINS_KEY, '50');
                return 50;
            }
            return parseInt(stored, 10) || 0;
        }

        function saveCoins() {
            localStorage.setItem(COINS_KEY, GameState.coins.toString());
            updateCoinsDisplay();
        }

        function updateCoinsDisplay() {
            const el = document.getElementById('coinDisplay');
            if (el) el.textContent = GameState.coins;
        }

        // Function to determine difficulty based on current level
        function getDifficultyForLevel(level) {
            if (level <= 5) return 'easy';        // Levels 1-5: Easy
            else if (level <= 10) return 'medium'; // Levels 6-10: Medium
            else return 'hard';                    // Levels 11+: Hard
        }

        const GameState = {
            target: 8.0,
            targetRevealed: false,
            coins: getStoredCoins(),
            total: 0,
            maxFuel: 10.0,
            canisters: [],
            attached: new Set(),
            used: new Set(),
            difficulty: getDifficultyForLevel({{ $progress->current_level }}),
            isGameOver: false,
            success: false,
            isPaused: false,
            sessionTimer: SESSION_SECS,
            sessionInterval: null,
            score: 0,
            gameStarted: false,
            sessionEnded: false,
            currentProblem: null,
        };

        // ===== LOTTIE ROCKET ANIMATION SETUP =====
        let rocketAnimation = null;

        function initRocketAnimation() {
            rocketAnimation = lottie.loadAnimation({
                container: document.getElementById('rocketAnimation'),
                renderer: 'svg',
                loop: false,
                autoplay: false,
                path: '/lottie/rocket-launch.json'
            });

            rocketAnimation.addEventListener('DOMLoaded', () => {
                rocketAnimation.goToAndStop(20, true);
                applyRocketSkin(currentSkinIdx);
            });

            rocketAnimation.addEventListener('complete', () => {
                rocketAnimation.goToAndStop(20, true);
            });
        }

        // ===== ROCKET SHIP SKINS (COLOR PALETTES) =====
        const ROCKET_SKINS = [
            { id: 'cyan', name: 'Cyan Explorer', filter: 'drop-shadow(0 0 25px rgba(56, 189, 248, 0.45)) hue-rotate(0deg)' },
            { id: 'violet', name: 'Cosmic Violet', filter: 'drop-shadow(0 0 25px rgba(168, 85, 247, 0.55)) hue-rotate(55deg) saturate(1.3)' },
            { id: 'crimson', name: 'Crimson Nova', filter: 'drop-shadow(0 0 25px rgba(244, 63, 94, 0.55)) hue-rotate(125deg) saturate(1.3)' },
            { id: 'gold', name: 'Solar Gold', filter: 'drop-shadow(0 0 25px rgba(234, 179, 8, 0.55)) hue-rotate(175deg) saturate(1.4) brightness(1.1)' },
            { id: 'emerald', name: 'Emerald Plasma', filter: 'drop-shadow(0 0 25px rgba(16, 185, 129, 0.55)) hue-rotate(240deg) saturate(1.3)' },
            { id: 'indigo', name: 'Royal Indigo', filter: 'drop-shadow(0 0 25px rgba(99, 102, 241, 0.55)) hue-rotate(295deg) saturate(1.2)' }
        ];

        let currentSkinIdx = 0;

        function applyRocketSkin(idx) {
            currentSkinIdx = idx % ROCKET_SKINS.length;
            const skin = ROCKET_SKINS[currentSkinIdx];
            const rocketContainer = document.getElementById('rocketAnimation');
            const svgFallback = document.getElementById('rocketContainer');

            if (rocketContainer) rocketContainer.style.filter = skin.filter;
            if (svgFallback) svgFallback.style.filter = skin.filter;

            const nameEl = document.getElementById('skinName');
            if (nameEl) nameEl.textContent = skin.name;
        }

        function cycleRocketSkin() {
            applyRocketSkin(currentSkinIdx + 1);
        }

        function setSkinForLevel(lvl) {
            const defaultSkinIdx = (lvl - 1) % ROCKET_SKINS.length;
            applyRocketSkin(defaultSkinIdx);
        }

        // ===== PAUSE =====
        window.togglePause = function() {
            if (GameState.sessionEnded) return;
            GameState.isPaused = !GameState.isPaused;
            const overlay = document.getElementById('pauseOverlay');
            const pauseBtn = document.getElementById('pauseBtn');
            if (GameState.isPaused) {
                if (overlay) overlay.classList.add('active');
                if (pauseBtn) {
                    pauseBtn.classList.add('paused');
                    pauseBtn.innerHTML = '<i class="bi bi-play-fill"></i> <span>Resume</span>';
                    pauseBtn.title = "Resume Game";
                }
                // Freeze session timer
                if (GameState.sessionInterval) {
                    clearInterval(GameState.sessionInterval);
                    GameState.sessionInterval = null;
                }
            } else {
                if (overlay) overlay.classList.remove('active');
                if (pauseBtn) {
                    pauseBtn.classList.remove('paused');
                    pauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
                    pauseBtn.title = "Pause Game";
                }
                // Resume session timer
                if (!GameState.sessionEnded) {
                    startSessionTimer();
                }
            }
        };
        const togglePause = window.togglePause;

        // Auto-pause when the phone locks / user switches app or tab
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && GameState.gameStarted && !GameState.isPaused && !GameState.sessionEnded) {
                togglePause();
            }
        });

        // ===== SESSION TIMER =====
        function formatTime(secs) {
            const m = Math.floor(secs / 60);
            const s = secs % 60;
            return m + ':' + (s < 10 ? '0' : '') + s;
        }

        function startSessionTimer() {
            if (GameState.sessionInterval) clearInterval(GameState.sessionInterval);
            GameState.sessionInterval = setInterval(() => {
                if (GameState.isPaused) return;
                GameState.sessionTimer--;
                document.getElementById('sessionTimerText').textContent = formatTime(GameState.sessionTimer);

                // Urgency colour when < 60 s
                const timerEl = document.getElementById('sessionTimerDisplay');
                if (GameState.sessionTimer <= 60) {
                    timerEl.style.background = 'linear-gradient(135deg,#ef4444,#b91c1c)';
                } else if (GameState.sessionTimer <= 120) {
                    timerEl.style.background = 'linear-gradient(135deg,#f97316,#c2410c)';
                }

                if (GameState.sessionTimer <= 0) {
                    clearInterval(GameState.sessionInterval);
                    GameState.sessionInterval = null;
                    endSession();
                }
            }, 1000);
        }

        // ===== END SESSION (time up) =====
        function endSession() {
            GameState.sessionEnded = true;
            GameState.isGameOver = true;
            const won = GameState.score >= WIN_SCORE;
            showSessionResult(won);
        }

        // ===== LEVEL COMPLETE (score hit 10) =====
        function triggerLevelComplete() {
            GameState.sessionEnded = true;
            if (GameState.sessionInterval) {
                clearInterval(GameState.sessionInterval);
                GameState.sessionInterval = null;
            }
            showSessionResult(true);
        }

        // ===== SHOW WIN / LOSE MODAL =====
        function showSessionResult(won) {
            // Coin Earning Logic (Same logic as Metric Quest: 10 per correct + 50 for 3-star / 25 for 2-star)
            const accuracy = Math.round((GameState.score / MAX_SCORE) * 100);
            let stars = 0;
            if (accuracy >= 90) stars = 3;
            else if (accuracy >= 70) stars = 2;
            else if (accuracy >= 50) stars = 1;

            const coinsEarned = GameState.score * 10 + (stars === 3 ? 50 : stars === 2 ? 25 : 0);
            GameState.coins += coinsEarned;
            saveCoins();

            if (won) {
                // Advance level FIRST, then save so the server receives the next level
                if (currentLevel < MAX_LEVEL) currentLevel++;
                document.getElementById('levelText').textContent = currentLevel;

                const scoreVal = GameState.score * 10;
                $.post('/student/update-progress', {
                    game_id: GAME_ID,
                    level: currentLevel,
                    score: scoreVal,
                    _token: '{{ csrf_token() }}'
                });

                document.getElementById('modalEmoji').textContent = GameState.score >= MAX_SCORE ? '🎉' : '🏆';
                document.getElementById('modalTitleText').textContent = 'Level Complete!';
                document.getElementById('modalDesc').textContent =
                    `Outstanding! You launched ${GameState.score} / ${MAX_SCORE} rockets correctly.`;
                document.getElementById('modalScoreText').innerHTML =
                    `✅ ${GameState.score} launches — Level passed! Advancing to Level ${Math.min(currentLevel, MAX_LEVEL)}.<br><span class="badge mt-2" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.4); font-size: 0.95rem; font-weight: 700; padding: 6px 12px;">🪙 +${coinsEarned} Coins Earned! Total: ${GameState.coins} 🪙</span>`;
                document.getElementById('modalScoreInfo').style.display = 'block';
                document.getElementById('modalBtn').innerHTML = '<i class="bi bi-arrow-clockwise"></i> Play Next Level';
                document.getElementById('mapBtn').style.display = 'inline-block';
            } else {
                document.getElementById('modalEmoji').textContent = '⏰';
                document.getElementById('modalTitleText').textContent = "Time's Up!";
                document.getElementById('modalDesc').textContent =
                    `You launched ${GameState.score} / ${WIN_SCORE} rockets needed. You need at least ${WIN_SCORE} to pass!`;
                document.getElementById('modalScoreText').innerHTML =
                    `❌ Only ${GameState.score} correct — need ${WIN_SCORE} to advance.<br><span class="badge mt-2" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.4); font-size: 0.95rem; font-weight: 700; padding: 6px 12px;">🪙 +${coinsEarned} Coins Earned! Total: ${GameState.coins} 🪙</span>`;
                document.getElementById('modalScoreInfo').style.display = 'block';
                document.getElementById('modalBtn').innerHTML = '🔄 Try Again';
                document.getElementById('mapBtn').style.display = 'none';
            }

            setTimeout(() => {
                const gameModal = new bootstrap.Modal(document.getElementById('gameModal'));
                gameModal.show();
                if (won) createConfetti();
            }, won ? 2200 : 400);
        }

        // ===== INITIALIZE STARS (fewer on small/low-power screens) =====
        function initStars() {
            const container = document.getElementById('starsContainer');
            const count = window.innerWidth < 768 ? 45 : 100;
            for (let i = 0; i < count; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                star.style.animationDelay = Math.random() * 3 + 's';
                container.appendChild(star);
            }
        }

        // ===== GAME LOGIC =====
        function generateCanisters(diff, target) {
            const count = 8;
            let decimals;
            if (diff === 'easy') {
                decimals = [0, 0.5];
            } else if (diff === 'medium') {
                decimals = [0, 0.25, 0.5, 0.75];
            } else {
                decimals = [0, 0.1, 0.2, 0.25, 0.3, 0.4, 0.5, 0.6, 0.7, 0.75, 0.8, 0.9];
            }

            target = target || 8.0;
            const cans = [];

            // 1. Build a guaranteed solution set of 2 or 3 canisters summing to target
            const solCount = (target > 5.0 && Math.random() < 0.6) ? 3 : 2;
            let solCans = [];

            let attempts = 0;
            while (attempts < 200) {
                attempts++;
                solCans = [];

                if (solCount === 2) {
                    const min1 = 1.0;
                    const max1 = Math.min(5.0, target - 0.5);
                    const whole1 = Math.floor(Math.random() * Math.max(1, max1 - min1 + 1)) + Math.floor(min1);
                    const dec1 = decimals[Math.floor(Math.random() * decimals.length)];
                    let c1 = Math.round((whole1 + dec1) * 100) / 100;
                    if (c1 >= target || c1 > 5.0 || c1 <= 0) continue;

                    let c2 = Math.round((target - c1) * 100) / 100;
                    if (c2 > 0 && c2 <= 5.0) {
                        solCans = [c1, c2];
                        break;
                    }
                } else {
                    // 3 canisters
                    const c1 = Math.round((1.0 + Math.floor(Math.random() * 3) + decimals[Math.floor(Math.random() * decimals.length)]) * 100) / 100;
                    if (c1 >= target - 1.0 || c1 > 5.0 || c1 <= 0) continue;

                    const rem = Math.round((target - c1) * 100) / 100;
                    const c2 = Math.round((0.5 + Math.floor(Math.random() * Math.max(1, Math.floor(rem - 0.5))) + decimals[Math.floor(Math.random() * decimals.length)]) * 100) / 100;
                    if (c2 >= rem || c2 > 5.0 || c2 <= 0) continue;

                    const c3 = Math.round((rem - c2) * 100) / 100;
                    if (c3 > 0 && c3 <= 5.0) {
                        solCans = [c1, c2, c3];
                        break;
                    }
                }
            }

            // Fallback if loop didn't find split
            if (solCans.length === 0) {
                if (target <= 5.0) {
                    solCans = [target];
                } else {
                    const c1 = Math.round((target / 2) * 100) / 100;
                    const c2 = Math.round((target - c1) * 100) / 100;
                    solCans = [c1, c2];
                }
            }

            cans.push(...solCans);

            // 2. Fill remaining slots with random distractor canisters matching the difficulty's decimal step
            while (cans.length < count) {
                const whole = Math.floor(Math.random() * 5) + 1;
                const dec = decimals[Math.floor(Math.random() * decimals.length)];
                let val = Math.round((whole + dec) * 100) / 100;
                if (val > 5.0) val = 5.0;
                cans.push(val);
            }

            // 3. Shuffle all 8 canisters so solution canisters are in random grid positions
            return shuffleArray(cans);
        }

        // Shuffled-deck queues per difficulty — guarantees no consecutive repeats
        const problemQueues = { easy: [], medium: [], hard: [] };
        let lastProblemText = '';

        function shuffleArray(arr) {
            const a = arr.slice();
            for (let i = a.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [a[i], a[j]] = [a[j], a[i]];
            }
            return a;
        }

        function refillQueue(difficulty) {
            const shuffled = shuffleArray(WORD_PROBLEMS[difficulty]);
            // If the first item after reshuffle matches the last shown, move it to end
            if (shuffled.length > 1 && shuffled[0].text === lastProblemText) {
                shuffled.push(shuffled.shift());
            }
            problemQueues[difficulty] = shuffled;
        }

        function getRandomProblem(difficulty) {
            if (problemQueues[difficulty].length === 0) refillQueue(difficulty);
            const problem = problemQueues[difficulty].shift();
            lastProblemText = problem.text;
            return problem;
        }

        function renderCanisters() {
            const grid = document.getElementById('canisterGrid');
            grid.innerHTML = '';
            GameState.canisters.forEach((val, idx) => {
                const col = document.createElement('div');
                col.className = 'col-6';

                const el = document.createElement('div');
                el.className = 'canister';
                if (GameState.attached.has(idx)) el.classList.add('attached');
                if (GameState.used.has(idx)) el.classList.add('used');
                el.textContent = val.toFixed(2);
                el.dataset.index = idx;
                el.addEventListener('click', () => handleCanisterClick(idx));

                col.appendChild(el);
                grid.appendChild(col);
            });
            document.getElementById('attachedCount').textContent = `Attached: ${GameState.attached.size}`;
        }

        function updateGauge() {
            const pct = Math.min(100, (GameState.total / GameState.maxFuel) * 100);
            const fill = document.getElementById('gaugeFill');
            fill.style.height = pct + '%';
            document.getElementById('totalDisplay').textContent = GameState.total.toFixed(2) + ' / ' + GameState.maxFuel.toFixed(2);

            fill.classList.remove('overweight', 'success');
            const rocket = document.getElementById('rocketContainer');

            if (GameState.success) {
                fill.classList.add('success');
            } else if (GameState.total > GameState.target) {
                fill.classList.add('overweight');
                rocket.classList.add('shake');
                document.getElementById('warningArea').style.display = 'block';
            } else {
                rocket.classList.remove('shake');
                document.getElementById('warningArea').style.display = 'none';
            }

            const status = document.getElementById('fuelStatus');
            if (GameState.success) {
                status.innerHTML = '<i class="bi bi-check-circle"></i> Ready for launch!';
                status.style.color = '#7aff7a';
            } else if (GameState.total > GameState.target) {
                status.innerHTML = '<i class="bi bi-exclamation-triangle"></i> OVERWEIGHT! Remove fuel!';
                status.style.color = '#ff4a4a';
            } else if (Math.abs(GameState.total - GameState.target) < 0.001 && GameState.total > 0) {
                status.innerHTML = '<i class="bi bi-bullseye"></i> Perfect! Launch!';
                status.style.color = '#ffd866';
            } else {
                if (GameState.targetRevealed) {
                    status.innerHTML = `<i class="bi bi-fuel-pump"></i> ${GameState.total.toFixed(2)} / ${GameState.target.toFixed(2)}`;
                } else {
                    status.innerHTML = `<i class="bi bi-fuel-pump"></i> ${GameState.total.toFixed(2)} / ??? (Target Hidden)`;
                }
                status.style.color = '#8899bb';
            }
        }

        function setMessage(msg) {
            document.getElementById('messageArea').textContent = msg;
        }

        function loadProblem() {
            GameState.currentProblem = getRandomProblem(GameState.difficulty);
            GameState.target = GameState.currentProblem.answer;
            GameState.targetRevealed = false;
            document.getElementById('problemText').innerHTML = GameState.currentProblem.text;
            renderTargetHint();
        }

        function renderTargetHint() {
            const hintContainer = document.getElementById('targetHint');
            if (!hintContainer) return;

            if (GameState.targetRevealed) {
                hintContainer.innerHTML = `
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 w-100">
                        <div class="d-flex align-items-center gap-2">
                            <span style="color: #94a3b8; font-weight: 600;"><i class="bi bi-bullseye text-warning"></i> Target fuel:</span>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1.5px solid rgba(16, 185, 129, 0.4); font-size: 1.05rem; font-weight: 800; padding: 4px 12px; border-radius: 8px;">
                                ${GameState.target.toFixed(2)} liters
                            </span>
                            <span style="font-size: 1.0rem; color: #4ade80;"><i class="bi bi-check-circle-fill"></i> Revealed</span>
                        </div>
                        <button type="button" class="btn-hint-coin" disabled style="opacity: 0.65; cursor: default;">
                            <i class="bi bi-check2"></i> Target Revealed
                        </button>
                    </div>
                `;
            } else {
                hintContainer.innerHTML = `
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 w-100">
                        <div class="d-flex align-items-center gap-2">
                            <span style="color: #94a3b8; font-weight: 600;"><i class="bi bi-bullseye text-warning"></i> Target fuel:</span>
                            <span class="badge" style="background: rgba(148, 163, 184, 0.15); color: #cbd5e1; border: 1px dashed rgba(148, 163, 184, 0.4); font-family: monospace; font-size: 0.95rem; padding: 4px 10px; border-radius: 8px; letter-spacing: 2px;">
                                ??? LITERS
                            </span>
                        </div>
                        <button type="button" class="btn-hint-coin" id="hintBtn" onclick="useTargetHint()">
                            <i class="bi bi-lightbulb-fill"></i> Reveal Target (-5 🪙)
                        </button>
                    </div>
                `;
            }
        }

        function useTargetHint() {
            if (GameState.targetRevealed) return;
            if (GameState.coins < 5) {
                setMessage('⚠️ Not enough coins! You need 5 🪙 to reveal the target fuel.');
                alert('Not enough coins! You need 5 🪙 to reveal the target fuel.');
                return;
            }
            GameState.coins -= 5;
            GameState.targetRevealed = true;
            saveCoins();
            renderTargetHint();
            updateGauge();
            setMessage(`💡 Target fuel revealed: ${GameState.target.toFixed(2)} liters (-5 🪙)`);
        }

        function resetRound() {
            if (GameState.sessionEnded) return; // don't reset after session ends
            GameState.total = 0;
            GameState.attached = new Set();
            GameState.used = new Set();
            GameState.success = false;
            GameState.isGameOver = false;
            // Update difficulty & automatically change rocket skin for every question
            GameState.difficulty = getDifficultyForLevel(currentLevel);
            cycleRocketSkin();
            loadProblem();
            GameState.canisters = generateCanisters(GameState.difficulty, GameState.target);

            renderCanisters();
            updateGauge();
            setMessage('Tap canisters to attach fuel');

            // Reset the Lottie animation to the standing frame
            if (rocketAnimation) {
                rocketAnimation.goToAndStop(20, true);
            }

            // Also reset the old SVG rocket (hidden, but just in case)
            const rocket = document.getElementById('rocketContainer');
            rocket.classList.remove('shake', 'flying');
            rocket.classList.add('returning');

            setTimeout(() => {
                rocket.classList.remove('returning');
            }, 2000);

            document.getElementById('warningArea').style.display = 'none';
            document.getElementById('scoreDisplay').textContent = GameState.score;
        }

        function handleCanisterClick(idx) {
            if (!GameState.gameStarted || GameState.isGameOver || GameState.success || GameState.isPaused) return;
            if (GameState.used.has(idx)) {
                setMessage('This canister has been used');
                return;
            }

            if (GameState.attached.has(idx)) {
                // Detach
                GameState.attached.delete(idx);
                GameState.total = Math.round((GameState.total - GameState.canisters[idx]) * 100) / 100;
                setMessage(`Removed ${GameState.canisters[idx].toFixed(2)} liters`);
                renderCanisters();
                updateGauge();
                return;
            }

            // Attach
            const newTotal = Math.round((GameState.total + GameState.canisters[idx]) * 100) / 100;
            if (newTotal > GameState.maxFuel) {
                setMessage(`Would exceed ${GameState.maxFuel.toFixed(2)}L! Choose smaller canisters`);
                return;
            }

            GameState.attached.add(idx);
            GameState.total = newTotal;
            setMessage(`Added ${GameState.canisters[idx].toFixed(2)}L · Total: ${GameState.total.toFixed(2)}L`);
            renderCanisters();
            updateGauge();

            // Check win
            if (Math.abs(GameState.total - GameState.target) < 0.001) {
                GameState.success = true;
                GameState.score++;
                document.getElementById('scoreDisplay').textContent = GameState.score;
                setMessage(`🚀 Perfect fuel! Rockets launched: ${GameState.score} / ${MAX_SCORE}`);

                // === PLAY THE LOTTIE LAUNCH ANIMATION ===
                if (rocketAnimation) {
                    rocketAnimation.goToAndPlay(0, true);
                }

                // Keep the old particle effects
                const rocket = document.getElementById('rocketContainer');
                rocket.classList.remove('shake');
                rocket.classList.add('flying');

                createLaunchEffects();
                updateGauge();

                // On phones the rocket is above the canisters — bring it into view
                if (window.innerWidth < 768) {
                    document.getElementById('rocketAnimation').scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                // Check if player hit the max score cap (10)
                if (GameState.score >= MAX_SCORE) {
                    setTimeout(() => triggerLevelComplete(), 2200);
                } else {
                    // Show quick per-launch success then auto-next-round
                    setTimeout(() => {
                        document.getElementById('modalEmoji').textContent = '🚀';
                        document.getElementById('modalTitleText').textContent = 'Launch Successful!';
                        document.getElementById('modalDesc').textContent =
                            `You fueled exactly ${GameState.target.toFixed(2)} liters! Keep going — ${GameState.score} / ${WIN_SCORE} needed.`;
                        document.getElementById('modalScoreInfo').style.display = 'none';
                        document.getElementById('modalBtn').innerHTML = '<i class="bi bi-arrow-clockwise"></i> Next Mission';
                        document.getElementById('mapBtn').style.display = 'none';
                        const gameModal = new bootstrap.Modal(document.getElementById('gameModal'));
                        gameModal.show();
                        createConfetti();
                    }, 2200);
                }
            }
        }

        // ===== VISUAL EFFECTS =====
        function createLaunchEffects() {
            const container = document.body;
            const rocketRect = document.querySelector('.rocket-animation-container').getBoundingClientRect();
            const particleCount = window.innerWidth < 768 ? 16 : 30; // lighter on phones

            // Fire trail particles
            const fireColors = ['#ff4500', '#ff6347', '#ffa500', '#ffff00', '#ff8c00'];
            for (let i = 0; i < particleCount; i++) {
                setTimeout(() => {
                    const particle = document.createElement('div');
                    particle.style.position = 'fixed';
                    particle.style.left = rocketRect.left + rocketRect.width / 2 + 'px';
                    particle.style.top = rocketRect.bottom + 'px';
                    particle.style.width = (5 + Math.random() * 8) + 'px';
                    particle.style.height = (5 + Math.random() * 8) + 'px';
                    particle.style.borderRadius = '50%';
                    particle.style.backgroundColor = fireColors[Math.floor(Math.random() * fireColors.length)];
                    particle.style.pointerEvents = 'none';
                    particle.style.zIndex = '9999';
                    particle.style.boxShadow = '0 0 10px currentColor';

                    container.appendChild(particle);

                    let startTime = Date.now();
                    const angle = Math.PI / 2 + (Math.random() - 0.5) * Math.PI / 4;
                    const velocity = 3 + Math.random() * 3;
                    const life = 600 + Math.random() * 400;

                    function animate() {
                        const elapsed = Date.now() - startTime;
                        if (elapsed > life) {
                            particle.remove();
                            return;
                        }

                        const progress = elapsed / life;
                        const x = parseFloat(particle.style.left) + Math.cos(angle) * velocity;
                        const y = parseFloat(particle.style.top) + Math.sin(angle) * velocity;

                        particle.style.left = x + 'px';
                        particle.style.top = y + 'px';
                        particle.style.opacity = 1 - progress;
                        particle.style.transform = `scale(${1 - progress * 0.5})`;

                        requestAnimationFrame(animate);
                    }
                    animate();
                }, i * 30);
            }
        }

        function createConfetti() {
            const container = document.body;
            const colors = ['#64b5f6', '#42a5f5', '#ffd866', '#ffeb3b', '#4caf50', '#8bc34a', '#ff6b6b'];
            const pieces = window.innerWidth < 768 ? 28 : 50; // lighter on phones

            for (let i = 0; i < pieces; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.left = Math.random() * 100 + '%';
                    confetti.style.top = '-10px';
                    confetti.style.width = '10px';
                    confetti.style.height = '10px';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.pointerEvents = 'none';
                    confetti.style.zIndex = '9999';
                    confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';

                    container.appendChild(confetti);

                    let startTime = Date.now();
                    const rotation = Math.random() * 360;
                    const duration = 2000 + Math.random() * 1000;
                    const drift = (Math.random() - 0.5) * 100;

                    function animate() {
                        const elapsed = Date.now() - startTime;
                        if (elapsed > duration) {
                            confetti.remove();
                            return;
                        }

                        const progress = elapsed / duration;
                        const y = progress * (window.innerHeight + 20);
                        const x = parseFloat(confetti.style.left) + (drift * progress * 0.01);
                        const rotate = rotation * progress * 4;

                        confetti.style.top = y + 'px';
                        confetti.style.left = x + '%';
                        confetti.style.transform = `rotate(${rotate}deg)`;
                        confetti.style.opacity = 1 - progress;

                        requestAnimationFrame(animate);
                    }
                    animate();
                }, i * 30);
            }
        }

        // ===== UI SETUP =====
        function setupUI() {
            const newRound = document.getElementById('newRoundBtn');
            if (newRound) {
                newRound.addEventListener('click', () => {
                    if (!GameState.sessionEnded) resetRound();
                });
            }

            document.getElementById('modalBtn').addEventListener('click', () => {
                const gameModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('gameModal'));
                gameModal.hide();
                if (GameState.sessionEnded) {
                    // Re-start a brand new session
                    GameState.gameStarted  = true;
                    GameState.isPaused     = false;
                    GameState.sessionEnded = false;
                    GameState.isGameOver   = false;
                    GameState.score        = 0;
                    GameState.sessionTimer = SESSION_SECS;
                    document.getElementById('scoreDisplay').textContent = '0';
                    document.getElementById('sessionTimerText').textContent = formatTime(SESSION_SECS);
                    document.getElementById('sessionTimerDisplay').style.background = '';
                    startSessionTimer();
                }
                resetRound();
            });

            // Keyboard shortcut: P or Escape to pause/resume
            document.addEventListener('keydown', (e) => {
                if (e.key === 'p' || e.key === 'P' || e.key === 'Escape') {
                    if (document.getElementById('pauseOverlay').classList.contains('active') ||
                        (GameState.gameStarted && !GameState.sessionEnded)) {
                        togglePause();
                    }
                }
            });
        }

        // ===== FLASH CARD INSTRUCTIONS & TUTORIAL CONTROLLER =====
        let rmTutOpenedFromGame = false;
        let rmCurrentFcCard = 1;
        const RM_TOTAL_FC_CARDS = 4;

        window.rmOpenTut = rmOpenTut;
        window.rmCloseTut = rmCloseTut;
        window.rmGoFlashCard = rmGoFlashCard;
        window.rmStepFlashCard = rmStepFlashCard;
        window.rmStartPracticeFromTut = rmStartPracticeFromTut;
        function rmOpenTut(fromGame = false) {
            rmTutOpenedFromGame = fromGame;
            const ov = document.getElementById('rmTutOverlay');
            const topActionBtn = document.getElementById('rmFcQuickActionBtn');

            if (fromGame) {
                if (GameState.gameStarted && !GameState.isPaused && !GameState.sessionEnded) {
                    togglePause();
                }
                if (topActionBtn) {
                    topActionBtn.className = 'rm-fc-quick-action-btn is-close';
                    topActionBtn.innerHTML = '&times;';
                    topActionBtn.title = 'Close Tutorial';
                    topActionBtn.onclick = () => rmCloseTut(false);
                }
            } else {
                if (topActionBtn) {
                    topActionBtn.className = 'rm-fc-quick-action-btn';
                    topActionBtn.innerHTML = '<span>Skip to Play</span> <i class="bi bi-arrow-right-short"></i>';
                    topActionBtn.title = 'Skip Instructions';
                    topActionBtn.onclick = () => rmCloseTut(true);
                }
            }

            if (ov) ov.classList.add('show');
            rmGoFlashCard(1);
        }

        function rmGoFlashCard(cardNum) {
            if (cardNum < 1) cardNum = 1;
            if (cardNum > RM_TOTAL_FC_CARDS) cardNum = RM_TOTAL_FC_CARDS;
            rmCurrentFcCard = cardNum;

            for (let i = 1; i <= RM_TOTAL_FC_CARDS; i++) {
                const slide = document.getElementById('rmFcSlide' + i);
                if (slide) slide.classList.toggle('hidden', i !== rmCurrentFcCard);
            }

            const dots = document.querySelectorAll('#rmFcDots .rm-fc-dot');
            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === rmCurrentFcCard - 1);
            });

            const badge = document.getElementById('rmFcBadge');
            if (badge) {
                const tags = ['MISSION BRIEFING', 'CANISTERS', 'FUEL GAUGE', 'TIMER & GOALS'];
                const prefix = rmTutOpenedFromGame ? 'TUTORIAL' : 'HOW TO PLAY';
                badge.textContent = '🃏 ' + prefix + ' • CARD ' + rmCurrentFcCard + ' OF ' + RM_TOTAL_FC_CARDS + ' • ' + tags[rmCurrentFcCard - 1];
            }

            const prevBtn = document.getElementById('rmFcPrevBtn');
            if (prevBtn) {
                prevBtn.disabled = (rmCurrentFcCard === 1);
            }

            const nextBtn = document.getElementById('rmFcNextBtn');
            if (nextBtn) {
                if (rmCurrentFcCard === RM_TOTAL_FC_CARDS) {
                    if (rmTutOpenedFromGame) {
                        nextBtn.innerHTML = '<span>▶ Return to Game</span>';
                    } else {
                        nextBtn.innerHTML = '<span>🚀 Start Mission!</span>';
                    }
                } else {
                    nextBtn.innerHTML = '<span>Next</span> <i class="bi bi-arrow-right"></i>';
                }
            }

            // Keep the card at the top when the overlay scrolls on small screens
            const ov = document.getElementById('rmTutOverlay');
            if (ov) ov.scrollTop = 0;
        }

        function rmStepFlashCard(delta) {
            if (rmCurrentFcCard === RM_TOTAL_FC_CARDS && delta > 0) {
                rmCloseTut(true);
                return;
            }
            rmGoFlashCard(rmCurrentFcCard + delta);
        }

        // Swipe left/right to flip flash cards on touch screens
        (function enableFlashCardSwipe() {
            let startX = 0, startY = 0;
            document.addEventListener('touchstart', (e) => {
                const ov = document.getElementById('rmTutOverlay');
                if (!ov || !ov.classList.contains('show')) return;
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            }, { passive: true });
            document.addEventListener('touchend', (e) => {
                const ov = document.getElementById('rmTutOverlay');
                if (!ov || !ov.classList.contains('show')) return;
                const dx = e.changedTouches[0].clientX - startX;
                const dy = e.changedTouches[0].clientY - startY;
                if (Math.abs(dx) > 60 && Math.abs(dx) > Math.abs(dy) * 1.5) {
                    if (dx < 0 && rmCurrentFcCard < RM_TOTAL_FC_CARDS) rmGoFlashCard(rmCurrentFcCard + 1);
                    if (dx > 0 && rmCurrentFcCard > 1) rmGoFlashCard(rmCurrentFcCard - 1);
                }
            }, { passive: true });
        })();

        function rmStartPracticeFromTut() {
            const ov = document.getElementById('rmTutOverlay');
            if (ov) ov.classList.remove('show');
            rmShowTrialOverlay();
        }

        function rmCloseTut(andStartGame = false) {
            const ov = document.getElementById('rmTutOverlay');
            if (ov) ov.classList.remove('show');

            if (rmTutOpenedFromGame) {
                if (GameState.isPaused) togglePause();
            } else {
                // User closed the initial instruction deck -> proceed directly to real mission!
                rmStartRealGame();
            }
        }

        function rmShowTrialOverlay() {
            document.getElementById('rmTutOverlay').classList.remove('show');
            rmTrialAttached = new Set(); rmTrialTotal = 0;
            rmRenderTrialCans();
            rmUpdateTrialGauge();
            document.getElementById('rmTrialStatus').textContent = 'Tap canisters to load fuel into the gauge!';
            document.getElementById('rmTrialOverlay').classList.add('show');
        }

        function rmRenderTrialCans() {
            const grid = document.getElementById('rmTrialCans');
            grid.innerHTML = '';
            RM_TRIAL_CANS.forEach(val => {
                const div = document.createElement('div');
                div.className = 'rm-trial-can';
                div.textContent = val.toFixed(1) + 'L';
                div.dataset.val = val;
                div.onclick = () => rmTrialToggleCan(div, val);
                grid.appendChild(div);
            });
        }

        function rmTrialToggleCan(el, val) {
            if (rmTrialAttached.has(val)) {
                rmTrialAttached.delete(val);
                el.classList.remove('attached');
            } else {
                rmTrialAttached.add(val);
                el.classList.add('attached');
            }
            rmTrialTotal = [...rmTrialAttached].reduce((a, b) => a + b, 0);
            rmUpdateTrialGauge();
            if (Math.abs(rmTrialTotal - RM_TRIAL_TARGET) < 0.001) {
                document.getElementById('rmTrialStatus').textContent = '🚀 Perfect! The rocket is ready to launch!';
                setTimeout(() => {
                    document.getElementById('rmTrialOverlay').classList.remove('show');
                    document.getElementById('rmReadyOverlay').classList.add('show');
                }, 700);
            } else if (rmTrialTotal > RM_TRIAL_TARGET) {
                document.getElementById('rmTrialStatus').textContent = '⚠️ Overloaded! Remove some fuel.';
            } else {
                document.getElementById('rmTrialStatus').textContent = 'Keep adding fuel... target is 5.0L';
            }
        }

        function rmUpdateTrialGauge() {
            const fill = document.getElementById('rmTrialGaugeFill');
            const totalEl = document.getElementById('rmTrialTotal');
            totalEl.textContent = rmTrialTotal.toFixed(1);
            const pct = Math.min(100, (rmTrialTotal / RM_TRIAL_TARGET) * 100);
            // Vertical gauge on desktop/tablet, horizontal bar on phones (see CSS)
            if (window.innerWidth < 768) {
                fill.style.width = pct + '%';
                fill.style.height = '100%';
            } else {
                fill.style.height = pct + '%';
                fill.style.width = '';
            }
            if (rmTrialTotal > RM_TRIAL_TARGET) fill.style.background = 'linear-gradient(to top,#ff4444,#ff6b6b)';
            else if (Math.abs(rmTrialTotal - RM_TRIAL_TARGET) < 0.001) fill.style.background = 'linear-gradient(to top,#4caf50,#8bc34a)';
            else fill.style.background = 'linear-gradient(to top,#42a5f5,#64b5f6)';
        }

        function rmStartRealGame() {
            localStorage.setItem(RM_TUTORIAL_KEY, '1');
            document.getElementById('rmReadyOverlay').classList.remove('show');
            // Start the actual game session
            GameState.gameStarted  = true;
            GameState.isPaused     = false;
            GameState.sessionEnded = false;
            GameState.score        = 0;
            GameState.sessionTimer = SESSION_SECS;
            document.getElementById('scoreDisplay').textContent = '0';
            document.getElementById('sessionTimerText').textContent = formatTime(SESSION_SECS);
            document.getElementById('sessionTimerDisplay').style.background = '';
            const pb1 = document.getElementById('pauseBtn');
            if (pb1) pb1.style.display = 'inline-flex';
            startSessionTimer();
            resetRound();
        }

        function rmPracticeAgain() {
            document.getElementById('rmReadyOverlay').classList.remove('show');
            rmTrialAttached = new Set(); rmTrialTotal = 0;
            rmRenderTrialCans();
            rmUpdateTrialGauge();
            document.getElementById('rmTrialStatus').textContent = 'Tap canisters to load fuel into the gauge!';
            document.getElementById('rmTrialOverlay').classList.add('show');
        }

        function rmSkipToRealGame() {
            localStorage.setItem(RM_TUTORIAL_KEY, '1');
            document.getElementById('rmTrialOverlay').classList.remove('show');
            // Start the actual game session
            GameState.gameStarted  = true;
            GameState.isPaused     = false;
            GameState.sessionEnded = false;
            GameState.score        = 0;
            GameState.sessionTimer = SESSION_SECS;
            document.getElementById('scoreDisplay').textContent = '0';
            document.getElementById('sessionTimerText').textContent = formatTime(SESSION_SECS);
            document.getElementById('sessionTimerDisplay').style.background = '';
            const pb2 = document.getElementById('pauseBtn');
            if (pb2) pb2.style.display = 'inline-flex';
            startSessionTimer();
            resetRound();
        }

        document.addEventListener('DOMContentLoaded', init);

        // ===== INIT (override to check localStorage) =====
        function init() {
            initStars();
            // === INIT LOTTIE ROCKET ANIMATION ===
            initRocketAnimation();

            GameState.difficulty = getDifficultyForLevel(currentLevel);
            updateCoinsDisplay();
            setupUI();
            setSkinForLevel(currentLevel);
            loadProblem();
            GameState.canisters = generateCanisters(GameState.difficulty, GameState.target);
            renderCanisters();
            updateGauge();
            document.getElementById('sessionTimerText').textContent = formatTime(SESSION_SECS);

            // Hide pause button until mission actually begins
            const pb = document.getElementById('pauseBtn');
            if (pb) pb.style.display = 'none';

            // ALWAYS show instructions flashcard deck before proceeding to the game
            rmOpenTut(false);
        }

        // ===== TUTORIAL REOPEN =====
        function rmReopenTutorial() {
            // Pause the session timer if game is running
            if (GameState.gameStarted && !GameState.isPaused && !GameState.sessionEnded) {
                togglePause();
            }
            // Reset to step 0
            rmTutStep = 0;
            document.querySelectorAll('.rm-step').forEach((el, i) => {
                el.classList.toggle('active', i === 0);
                el.classList.remove('exit-left', 'enter-right');
            });
            document.querySelectorAll('.rm-dot').forEach((d, i) => d.classList.toggle('active', i === 0));
            const back = document.getElementById('rmTutBack');
            const next = document.getElementById('rmTutNext');
            if (back) back.disabled = true;
            if (next) next.textContent = 'Next →';
            // Hide trial/ready overlays, show tutorial
            document.getElementById('rmTrialOverlay').classList.remove('show');
            document.getElementById('rmReadyOverlay').classList.remove('show');
            document.getElementById('rmTutOverlay').classList.add('show');
        }
    </script>
@endsection