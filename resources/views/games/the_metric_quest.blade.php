@extends('layouts.app')

@section('title', 'The Metric Quest — Math Learning Platform')
@section('hide-nav', true)

@section('content')
{{-- NOTE: layouts.app <head> must contain:
     <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"> --}}
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@500;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
  /* ==========================================================================
     THE METRIC QUEST — TIME TRAVELER'S MEASUREMENT ODYSSEY THEME
     ========================================================================== */
  :root {
    --bg-dark: #070b19;
    --card-bg: rgba(15, 23, 42, 0.88);
    --card-border: rgba(56, 189, 248, 0.35);

    /* Category Accents */
    --accent-length: #38bdf8;   /* Cyan / Sky for Length */
    --accent-mass: #10b981;     /* Emerald for Mass / Weight */
    --accent-cap: #6366f1;      /* Indigo / Blue for Capacity */
    --accent-time: #f59e0b;     /* Amber / Sun for Chrono / Time */
    --accent-gold: #fbbf24;

    --font-heading: 'Space Grotesk', -apple-system, sans-serif;
    --font-body: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
    --font-mono: 'Space Grotesk', monospace;

    /* Safe-area insets (notches / home bars) */
    --safe-top: env(safe-area-inset-top, 0px);
    --safe-bottom: env(safe-area-inset-bottom, 0px);
    --safe-left: env(safe-area-inset-left, 0px);
    --safe-right: env(safe-area-inset-right, 0px);
  }

  * { box-sizing: border-box; }

  html { -webkit-text-size-adjust: 100%; }

  html, body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    min-height: 100dvh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background:
      radial-gradient(circle at 10% 15%, rgba(56, 189, 248, 0.16) 0%, transparent 40%),
      radial-gradient(circle at 90% 85%, rgba(99, 102, 241, 0.18) 0%, transparent 45%),
      radial-gradient(circle at 50% 50%, rgba(16, 185, 129, 0.08) 0%, transparent 55%),
      var(--bg-dark);
    color: #e2e8f0;
    font-family: var(--font-body);
    font-weight: 600;
    overflow-x: hidden;
    -webkit-tap-highlight-color: transparent;
  }

  /* Faster taps / no double-tap zoom on interactive elements */
  button, a, .answer-card, .tut-dot, .lab-tab-btn { touch-action: manipulation; }
  .answer-card { -webkit-user-select: none; user-select: none; }

  /* -- FLOATING METRIC SYMBOLS & UNITS LAYER -- */
  #sym-layer {
    position: fixed; inset: 0;
    pointer-events: none; z-index: 0; overflow: hidden;
  }
  .sym {
    position: absolute;
    font-family: var(--font-mono);
    font-weight: 800;
    pointer-events: none;
    user-select: none;
    will-change: transform, opacity;
    animation: floatDrift linear infinite;
    filter: drop-shadow(0 0 10px currentColor);
  }
  @keyframes floatDrift {
    0% { transform: translateY(0) rotate(0deg); opacity: 0; }
    8% { opacity: 0.38; }
    90% { opacity: 0.38; }
    100% { transform: translateY(-115vh) rotate(360deg); opacity: 0; }
  }
  @media (prefers-reduced-motion: reduce) {
    .sym { animation: none; opacity: 0.22; }
    .timer-pill.urgent, .victory-icon, .modal-top-bar { animation: none !important; }
  }

  /* Metric Ruler Tape Tick Overlay (Top & Bottom Ambient Accents) */
  .ruler-tape-top, .ruler-tape-bottom {
    position: fixed; left: 0; right: 0; height: 10px;
    background-image: repeating-linear-gradient(90deg, rgba(56, 189, 248, 0.3) 0px, rgba(56, 189, 248, 0.3) 1px, transparent 1px, transparent 10px),
                      repeating-linear-gradient(90deg, rgba(245, 158, 11, 0.45) 0px, rgba(245, 158, 11, 0.45) 2px, transparent 2px, transparent 50px);
    pointer-events: none; z-index: 1; opacity: 0.75;
  }
  .ruler-tape-top { top: 0; border-bottom: 1px solid rgba(56, 189, 248, 0.25); }
  .ruler-tape-bottom { bottom: 0; border-top: 1px solid rgba(56, 189, 248, 0.25); }

  /* -- APP CONTAINER -- */
  .app-container {
    position: relative;
    z-index: 2;
    width: 96%;
    max-width: 1400px;
    margin: 0 auto;
    padding: 100px 14px 28px;
    display: flex;
    flex-direction: column;
  }

  /* ============ MASTHEAD ============ */
  .masthead {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }
  .masthead-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }
  .title-group h1 {
    font-family: var(--font-heading);
    font-weight: 900;
    font-size: clamp(1.5rem, 2.3vw, 2.1rem);
    background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #34d399 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 0;
    line-height: 1.1;
    letter-spacing: -0.5px;
    filter: drop-shadow(0 3px 12px rgba(56, 189, 248, 0.3));
  }
  .title-group p {
    color: #94a3b8;
    font-weight: 600;
    font-size: 0.82rem;
    margin: 2px 0 0;
    letter-spacing: 0.2px;
  }

  .level-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #0ea5e9, #6366f1);
    color: #ffffff;
    padding: 5px 14px;
    border-radius: 50px;
    font-weight: 800;
    font-size: 0.82rem;
    letter-spacing: 0.4px;
    box-shadow: 0 0 16px rgba(14, 165, 233, 0.4);
    white-space: nowrap;
  }

  .coins-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(245, 158, 11, 0.16);
    border: 1.5px solid rgba(245, 158, 11, 0.5);
    color: #fbbf24;
    padding: 5px 12px;
    border-radius: 50px;
    font-weight: 800;
    font-size: 0.88rem;
    box-shadow: 0 0 12px rgba(245, 158, 11, 0.25);
    cursor: default;
    transition: transform 0.2s;
    white-space: nowrap;
  }
  .coins-pill:hover { transform: scale(1.04); }

  .score-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(56, 189, 248, 0.14);
    border: 1.5px solid rgba(56, 189, 248, 0.4);
    color: #38bdf8;
    padding: 5px 12px;
    border-radius: 50px;
    font-weight: 800;
    font-size: 0.88rem;
    white-space: nowrap;
  }

  .streak-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(236, 72, 153, 0.18);
    border: 1.5px solid rgba(236, 72, 153, 0.5);
    color: #f472b6;
    padding: 5px 12px;
    border-radius: 50px;
    font-weight: 800;
    font-size: 0.88rem;
    box-shadow: 0 0 12px rgba(236, 72, 153, 0.25);
    white-space: nowrap;
  }

  /* Eduplay Action Nav Buttons */
  .eduplay-game-nav {
    display: flex;
    align-items: center;
    gap: 7px;
    flex-wrap: wrap;
  }
  .eduplay-nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 13px;
    border-radius: 11px;
    font-family: var(--font-body);
    font-size: 0.82rem;
    font-weight: 700;
    color: #e2e8f0;
    background: rgba(255, 255, 255, 0.06);
    border: 1.5px solid rgba(255, 255, 255, 0.12);
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .eduplay-nav-btn:hover {
    background: rgba(56, 189, 248, 0.2);
    border-color: #38bdf8;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(56, 189, 248, 0.35);
  }
  .eduplay-btn-guide {
    background: linear-gradient(135deg, rgba(168, 85, 247, 0.25), rgba(99, 102, 241, 0.25));
    border-color: rgba(168, 85, 247, 0.5);
    color: #e9d5ff;
  }
  .eduplay-btn-lab {
    background: linear-gradient(135deg, rgba(56, 189, 248, 0.22), rgba(16, 185, 129, 0.22));
    border-color: rgba(56, 189, 248, 0.5);
    color: #bae6fd;
  }
  .eduplay-btn-pause {
    background: rgba(245, 158, 11, 0.14);
    border-color: rgba(245, 158, 11, 0.35);
    color: #fde68a;
  }
  .eduplay-btn-sound {
    background: rgba(56, 189, 248, 0.14);
    border-color: rgba(56, 189, 248, 0.35);
    color: #bae6fd;
  }
  .eduplay-btn-sound.muted {
    opacity: 0.6;
    color: #94a3b8;
  }

  /* ============ DYNAMIC METRIC CONVERSION LADDER BANNER ============ */
  .metric-ladder-banner {
    background: linear-gradient(90deg, rgba(56, 189, 248, 0.14) 0%, rgba(99, 102, 241, 0.14) 40%, rgba(16, 185, 129, 0.14) 75%, rgba(245, 158, 11, 0.14) 100%);
    border: 1.5px solid rgba(255, 255, 255, 0.12);
    border-radius: 14px;
    padding: 7px 16px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.28);
    position: relative;
    overflow: hidden;
  }
  .metric-ladder-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(90deg, rgba(255, 255, 255, 0.03) 0, rgba(255, 255, 255, 0.03) 1px, transparent 1px, transparent 12px);
    pointer-events: none;
  }
  .ladder-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.84rem;
    font-weight: 800;
    color: #cbd5e1;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    z-index: 1;
  }
  .ladder-steps {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
    z-index: 1;
  }
  .ladder-node {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(0, 0, 0, 0.45);
    padding: 3px 10px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 800;
    border: 1.5px solid rgba(255, 255, 255, 0.14);
    color: #e2e8f0;
    transition: all 0.25s;
    white-space: nowrap;
  }
  .ladder-node.active-unit {
    border-color: #38bdf8;
    background: rgba(56, 189, 248, 0.25);
    color: #ffffff;
    box-shadow: 0 0 12px rgba(56, 189, 248, 0.5);
    transform: scale(1.05);
  }
  .ladder-op {
    font-family: var(--font-mono);
    font-size: 0.74rem;
    font-weight: 800;
    color: #38bdf8;
    background: rgba(56, 189, 248, 0.12);
    padding: 2px 7px;
    border-radius: 5px;
    white-space: nowrap;
  }
  .ladder-rule-tip {
    font-size: 0.95rem;
    color: #fde68a;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    z-index: 1;
  }

  /* ============ MAIN QUEST LAYOUT (BALANCED DUAL PANEL) ============ */
  .quest-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 340px;
    gap: 14px;
    align-items: start;
  }
  @media (max-width: 1080px) {
    .quest-layout { grid-template-columns: 1fr; }
  }

  /* ============ QUEST CHALLENGE HUB (LEFT PANEL) ============ */
  .challenge-station {
    background: linear-gradient(165deg, rgba(13, 21, 46, 0.94) 0%, rgba(10, 16, 36, 0.96) 100%);
    border: 1.5px solid rgba(56, 189, 248, 0.35);
    border-radius: 24px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.65), 0 0 40px rgba(56, 189, 248, 0.12);
    padding: 22px 24px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    backdrop-filter: blur(14px);
    overflow: hidden;
  }

  /* Precision ruler ticks inside challenge card */
  .challenge-station::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; height: 5px;
    background-image: repeating-linear-gradient(90deg, #38bdf8 0, #38bdf8 1px, transparent 1px, transparent 8px);
    opacity: 0.55;
  }

  .station-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }
  .q-progress-group {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }
  .q-chip {
    font-size: 0.84rem;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 5px 12px;
    border-radius: 8px;
    white-space: nowrap;
  }
  .topic-category-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 800;
    background: rgba(56, 189, 248, 0.15);
    border: 1px solid rgba(56, 189, 248, 0.4);
    color: #38bdf8;
  }

  /* Timer Component */
  .timer-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .timer-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.88rem;
    font-weight: 800;
    color: #f59e0b;
    background: rgba(245, 158, 11, 0.14);
    border: 1.5px solid rgba(245, 158, 11, 0.45);
    padding: 5px 14px;
    border-radius: 50px;
    box-shadow: 0 0 14px rgba(245, 158, 11, 0.25);
    transition: all 0.3s;
  }
  .timer-pill.urgent {
    background: rgba(239, 68, 68, 0.25);
    border-color: #ef4444;
    color: #fca5a5;
    animation: urgentPulse 0.8s infinite alternate;
  }
  @keyframes urgentPulse {
    from { transform: scale(1); box-shadow: 0 0 10px rgba(239, 68, 68, 0.5); }
    to { transform: scale(1.06); box-shadow: 0 0 22px rgba(239, 68, 68, 0.85); }
  }

  /* Level Question Progress Bar */
  .q-progress-bar-bg {
    width: 100%;
    height: 7px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50px;
    overflow: hidden;
    margin-bottom: 16px;
  }
  .q-progress-bar-fill {
    height: 100%;
    width: 10%;
    background: linear-gradient(90deg, #38bdf8, #818cf8, #34d399);
    border-radius: 50px;
    transition: width 0.4s ease;
    box-shadow: 0 0 12px rgba(56, 189, 248, 0.5);
  }

  /* -- QUESTION DISPLAY BOX -- */
  .question-station {
    background: linear-gradient(145deg, rgba(30, 41, 59, 0.6) 0%, rgba(15, 23, 42, 0.7) 100%);
    border: 1.5px solid rgba(56, 189, 248, 0.3);
    border-radius: 20px;
    padding: 24px 22px;
    margin-bottom: 16px;
    position: relative;
    box-shadow: inset 0 0 25px rgba(56, 189, 248, 0.06);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
  }
  .quest-mode-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.74rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #a5b4fc;
    background: rgba(99, 102, 241, 0.2);
    border: 1px solid rgba(99, 102, 241, 0.35);
    padding: 3px 10px;
    border-radius: 50px;
    margin-bottom: 12px;
  }
  .question-text {
    font-family: var(--font-heading);
    font-size: clamp(1.25rem, 2vw, 1.6rem);
    line-height: 1.45;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: 0.2px;
    max-width: 90%;
    overflow-wrap: anywhere;
  }

  /* -- 4 ANSWER TABLETS GRID -- */
  .answers-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 16px;
  }
  @media (max-width: 650px) {
    .answers-grid { grid-template-columns: 1fr; }
  }

  .answer-card {
    background: rgba(255, 255, 255, 0.04);
    border: 2px solid rgba(255, 255, 255, 0.12);
    color: #f1f5f9;
    padding: 16px 20px;
    border-radius: 16px;
    font-family: var(--font-body);
    font-size: 1.12rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    user-select: none;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
    width: 100%;
    min-width: 0;
  }
  .answer-card:hover:not(:disabled) {
    background: rgba(56, 189, 248, 0.14);
    border-color: #38bdf8;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(56, 189, 248, 0.3);
  }
  .answer-card:active:not(:disabled) { transform: translateY(1px); }
  .ans-badge {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-family: var(--font-mono);
    font-weight: 800;
    color: #94a3b8;
    flex-shrink: 0;
    margin-right: 12px;
  }
  .ans-val {
    flex: 1;
    text-align: left;
    font-weight: 800;
    overflow-wrap: anywhere;
  }
  .ans-key-hint {
    font-size: 0.9rem;
    color: #64748b;
    font-family: var(--font-mono);
    font-weight: 700;
    background: rgba(0, 0, 0, 0.3);
    padding: 2px 6px;
    border-radius: 4px;
    border: 1px solid rgba(255, 255, 255, 0.06);
  }

  .answer-card.correct {
    background: rgba(16, 185, 129, 0.25) !important;
    border-color: #10b981 !important;
    color: #a7f3d0 !important;
    box-shadow: 0 0 25px rgba(16, 185, 129, 0.45) !important;
    animation: correctPulse 0.45s ease;
  }
  .answer-card.correct .ans-badge {
    background: #10b981;
    color: #064e3b;
  }
  .answer-card.wrong {
    background: rgba(239, 68, 68, 0.25) !important;
    border-color: #ef4444 !important;
    color: #fca5a5 !important;
    box-shadow: 0 0 25px rgba(239, 68, 68, 0.45) !important;
    animation: shakeCard 0.4s ease;
  }
  .answer-card.wrong .ans-badge {
    background: #ef4444;
    color: #ffffff;
  }
  .answer-card:disabled { cursor: default; }

  @keyframes correctPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.03); }
  }
  @keyframes shakeCard {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-8px); }
    75% { transform: translateX(8px); }
  }

  /* -- FEEDBACK & HINT SECTION -- */
  .feedback-box {
    padding: 12px 18px;
    border-radius: 12px;
    margin-bottom: 12px;
    font-weight: 700;
    font-size: 0.95rem;
    display: none;
    align-items: center;
    gap: 8px;
    animation: fadeIn 0.25s ease;
  }
  .feedback-box.correct {
    background: rgba(16, 185, 129, 0.18);
    border: 1.5px solid rgba(16, 185, 129, 0.5);
    color: #34d399;
    display: flex;
  }
  .feedback-box.wrong {
    background: rgba(239, 68, 68, 0.18);
    border: 1.5px solid rgba(239, 68, 68, 0.5);
    color: #f87171;
    display: flex;
  }

  .hint-reveal-box {
    background: rgba(245, 158, 11, 0.12);
    border: 1.5px solid rgba(245, 158, 11, 0.45);
    border-radius: 14px;
    padding: 12px 18px;
    margin-bottom: 14px;
    color: #fbbf24;
    font-size: 0.92rem;
    font-weight: 700;
    display: none;
    line-height: 1.45;
  }
  .hint-reveal-box.show {
    display: block;
    animation: fadeIn 0.25s ease;
  }

  .station-bottom-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    padding-top: 10px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
  }
  .hint-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(245, 158, 11, 0.15);
    border: 1.5px solid rgba(245, 158, 11, 0.45);
    color: #fbbf24;
    padding: 8px 18px;
    border-radius: 50px;
    font-family: var(--font-body);
    font-size: 0.88rem;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.2s;
  }
  .hint-btn:hover:not(:disabled) {
    background: rgba(245, 158, 11, 0.28);
    border-color: #fbbf24;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
  }
  .hint-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  .quick-keys-hint {
    font-size: 0.74rem;
    color: #94a3b8;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
  }
  .quick-keys-hint kbd {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    padding: 2px 6px;
    border-radius: 4px;
    font-family: var(--font-mono);
    color: #cbd5e1;
    font-size: 0.9rem;
  }

  /* ============ EXPEDITION SIDEBAR (RIGHT PANEL) ============ */
  .expedition-sidebar {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .sidebar-box {
    background: linear-gradient(165deg, rgba(15, 23, 42, 0.94) 0%, rgba(10, 16, 32, 0.94) 100%);
    backdrop-filter: blur(14px);
    border: 1.5px solid rgba(255, 255, 255, 0.1);
    border-radius: 18px;
    padding: 16px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.45);
    position: relative;
  }
  .sidebar-box h3 {
    font-family: var(--font-heading);
    font-weight: 800;
    font-size: 0.95rem;
    margin: 0 0 10px;
    color: #f1f5f9;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    padding-bottom: 7px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  /* Player Card */
  .player-card {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
  }
  .player-avatar {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.45rem;
    background: linear-gradient(135deg, #0ea5e9, #6366f1);
    border: 2px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 0 16px rgba(14, 165, 233, 0.45);
    flex-shrink: 0;
  }
  .player-details { flex: 1; min-width: 0; }
  .player-name {
    font-weight: 800;
    font-size: 0.92rem;
    color: #f8fafc;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .player-title {
    font-size: 0.74rem;
    color: #38bdf8;
    font-weight: 700;
  }

  /* Mini Converter / Quick Cheat Sheet Widget */
  .mini-cheat-grid {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .cheat-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(0, 0, 0, 0.35);
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 0.95rem;
    border: 1px solid rgba(255, 255, 255, 0.06);
  }
  .cheat-label { color: #94a3b8; font-weight: 700; }
  .cheat-rule { font-family: var(--font-mono); font-weight: 800; color: #38bdf8; }

  /* Open Lab Drawer Action Button */
  .btn-open-lab {
    width: 100%;
    margin-top: 10px;
    padding: 9px 0;
    background: linear-gradient(135deg, rgba(56, 189, 248, 0.25), rgba(99, 102, 241, 0.25));
    border: 1.5px solid rgba(56, 189, 248, 0.5);
    border-radius: 12px;
    color: #e0f2fe;
    font-size: 0.84rem;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    transition: all 0.2s;
  }
  .btn-open-lab:hover {
    background: linear-gradient(135deg, rgba(56, 189, 248, 0.4), rgba(99, 102, 241, 0.4));
    border-color: #38bdf8;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(56, 189, 248, 0.35);
  }

  /* 50-Level Stage Tracker */
  .stage-progress-track {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .stage-track-header {
    display: flex;
    justify-content: space-between;
    font-size: 0.74rem;
    color: #94a3b8;
    font-weight: 700;
    gap: 8px;
  }

  /* ==========================================================================
     ILLUSTRATED FLASHCARD TUTORIAL DECK (3-Card Lean Deck)
     ========================================================================== */
  .tut-overlay {
    position: fixed; inset: 0;
    background: rgba(4, 8, 22, 0.92);
    backdrop-filter: blur(18px);
    z-index: 9995;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
  }
  .tut-overlay.active {
    display: flex;
    animation: modalPopIn 0.32s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  @keyframes modalPopIn {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
  }

  .tut-deck {
    background: linear-gradient(165deg, #0e172e 0%, #111e3d 100%);
    border: 2px solid rgba(56, 189, 248, 0.45);
    border-radius: 28px;
    max-width: 820px;
    width: 100%;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.85), 0 0 50px rgba(56, 189, 248, 0.22);
    position: relative;
    overflow: hidden;
    padding: 36px 44px 32px;
    display: flex;
    flex-direction: column;
    margin: auto;
  }
  .tut-deck-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }
  .tut-deck-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.92rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #38bdf8;
    background: rgba(56, 189, 248, 0.15);
    border: 1px solid rgba(56, 189, 248, 0.35);
    padding: 4px 12px;
    border-radius: 50px;
  }
  .btn-close-tut {
    background: transparent;
    border: none;
    color: #94a3b8;
    font-size: 2.5rem;
    cursor: pointer;
    line-height: 1;
    transition: color 0.2s;
    padding: 0 6px;
  }
  .btn-close-tut:hover { color: #ffffff; }

  /* Flashcard Content */
  .fc-card { display: none; }
  .fc-card.active {
    display: flex;
    flex-direction: column;
    animation: fadeIn 0.25s ease;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .fc-title {
    font-family: var(--font-heading);
    font-size: 1.7rem;
    font-weight: 900;
    color: #ffffff;
    margin: 0 0 8px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .fc-desc {
    font-size: 1.1rem;
    line-height: 1.5;
    color: #cbd5e1;
    margin: 0 0 16px;
  }

  /* Rich Flashcard Visual Blocks */
  .fc-visual-box {
    background: rgba(0, 0, 0, 0.4);
    border: 1.5px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 16px;
  }
  .fc-ladder-visual {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .ladder-rule-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    padding: 10px 14px;
    gap: 10px;
    font-size: 1.05rem;
  }
  .rule-badge {
    padding: 3px 10px;
    border-radius: 6px;
    font-weight: 800;
    font-size: 0.92rem;
    text-transform: uppercase;
    white-space: nowrap;
  }
  .rule-badge.multiply { background: rgba(16, 185, 129, 0.25); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.5); }
  .rule-badge.divide { background: rgba(56, 189, 248, 0.25); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.5); }

  .fc-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
  }
  .fc-mini-card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 16px;
  }
  .fc-mini-card h5 {
    margin: 0 0 6px;
    font-size: 1.0rem;
    font-weight: 800;
    color: #38bdf8;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .fc-mini-card p {
    margin: 0;
    font-size: 0.96rem;
    color: #94a3b8;
    line-height: 1.4;
  }

  /* Deck Navigation */
  .tut-deck-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding-top: 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
  }
  .tut-dots {
    display: flex;
    gap: 6px;
  }
  .tut-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    cursor: pointer;
    transition: all 0.2s;
  }
  .tut-dot.active {
    background: #38bdf8;
    width: 24px;
    border-radius: 6px;
    box-shadow: 0 0 10px rgba(56, 189, 248, 0.6);
  }

  .tut-nav-btns {
    display: flex;
    gap: 8px;
  }
  .btn-tut-nav {
    padding: 8px 18px;
    border-radius: 50px;
    font-family: var(--font-body);
    font-size: 1.0rem;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
  }
  .btn-tut-nav.prev {
    background: rgba(255, 255, 255, 0.08);
    color: #cbd5e1;
    border: 1px solid rgba(255, 255, 255, 0.12);
  }
  .btn-tut-nav.prev:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
  }
  .btn-tut-nav.next {
    background: linear-gradient(135deg, #0ea5e9, #6366f1);
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(14, 165, 233, 0.4);
  }
  .btn-tut-nav.next:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.6);
  }
  .btn-tut-nav.start {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
  }
  .btn-tut-nav.skip {
    background: rgba(255, 255, 255, 0.08);
    color: #fde68a;
    border: 1px solid rgba(251, 191, 36, 0.28);
  }
  .btn-tut-nav.skip:hover {
    background: rgba(251, 191, 36, 0.16);
    color: #ffffff;
    border-color: rgba(251, 191, 36, 0.5);
  }

  /* ===== Lean Field Guide ===== */
  .mq-row2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:14px; }
  .mq-row3 { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:14px; }
  .mq-tile { background:rgba(255,255,255,.05); border:1.5px solid rgba(255,255,255,.12); border-radius:16px; padding:14px 10px; text-align:center; }
  .mq-big { font-size:2.5rem; line-height:1; margin-bottom:6px; animation:mqFloat 2.4s ease-in-out infinite; }
  .mq-tile:nth-child(2) .mq-big { animation-delay:-.6s; }
  .mq-tile:nth-child(3) .mq-big { animation-delay:-1.2s; }
  .mq-tile:nth-child(4) .mq-big { animation-delay:-1.8s; }
  .mq-lbl { font-size:1.15rem; font-weight:800; color:#fff; }
  .mq-units { font-family:var(--font-mono); font-size:.95rem; font-weight:700; color:#94a3b8; margin-top:3px; }
  .mq-tip { background:rgba(251,191,36,.08); border:1px solid rgba(251,191,36,.25); border-radius:12px; padding:10px 14px; font-size:1.05rem; color:#fde68a; text-align:center; }
  .mq-rule { border-radius:14px; padding:12px 10px; text-align:center; border:1.5px solid; }
  .mq-rule .mq-big { font-size:2rem; animation:none; margin-bottom:4px; }
  .mq-rule-lbl { font-weight:800; font-size:1.05rem; color:#fff; }
  .mq-rule-eg { font-size:.92rem; color:#94a3b8; margin-top:2px; }
  .mq-try-q { text-align:center; font-size:1.25rem; font-weight:800; color:#f1f5f9; margin-bottom:10px; }
  .mq-try-row { display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin-bottom:8px; }
  .mq-try-btn { background:rgba(56,189,248,.14); border:2px solid rgba(56,189,248,.5); color:#e2e8f0; font-family:var(--font-body); font-weight:800; font-size:1.2rem; padding:12px 22px; border-radius:14px; cursor:pointer; min-height:48px; transition:transform .15s; }
  .mq-try-btn:hover { transform:scale(1.06); }
  .mq-try-btn.ok { background:rgba(16,185,129,.25); border-color:#10b981; color:#34d399; animation:mqPop .4s ease; }
  .mq-try-btn.no { background:rgba(239,68,68,.18); border-color:#ef4444; color:#f87171; animation:mqShake .4s ease; }
  #mqTryMsg { min-height:32px; text-align:center; font-weight:800; font-size:1.1rem; color:#38bdf8; margin-bottom:12px; }
  .mq-chips { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:14px; }
  .mq-chip { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); border-radius:10px; padding:8px 6px; text-align:center; font-family:var(--font-mono); font-weight:800; font-size:1rem; color:#e2e8f0; }
  @keyframes mqFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-5px)} }
  @keyframes mqPop { 0%,100%{transform:scale(1)} 50%{transform:scale(1.15)} }
  @keyframes mqShake { 0%,100%{transform:translateX(0)} 25%{transform:translateX(-6px)} 75%{transform:translateX(6px)} }
  @media (max-width: 600px) {
    .mq-row3 { gap:6px; }
    .mq-tile { padding:10px 6px; }
    .mq-big { font-size:2rem; }
    .mq-lbl { font-size:.95rem; }
    .mq-units { font-size:.8rem; }
    .mq-tip { font-size:.92rem; }
    .mq-rule-lbl { font-size:.92rem; }
    .mq-rule-eg { font-size:.8rem; }
    .mq-try-q { font-size:1.05rem; }
    .mq-try-btn { font-size:1.05rem; padding:11px 14px; }
    .mq-chips { grid-template-columns:repeat(2,1fr); }
    .mq-chip { font-size:.88rem; }
  }

  /* ==========================================================================
     METRIC LAB REFERENCE DRAWER (Slide-Out Interactive Conversion Hub)
     ========================================================================== */
  .lab-overlay {
    position: fixed; inset: 0;
    background: rgba(4, 8, 22, 0.92);
    backdrop-filter: blur(14px);
    z-index: 9992;
    display: none;
    justify-content: center;
    align-items: center;
  }
  .lab-overlay.active { display: flex; }

  .lab-drawer {
    width: 100%;
    max-width: 98vw;
    height: 98vh;
    height: 98dvh;
    background: linear-gradient(175deg, #0f172a 0%, #0b1120 100%);
    border: 2px solid rgba(56, 189, 248, 0.4);
    border-radius: 16px;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.9);
    padding: 24px 32px;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    animation: slideDrawer 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  }
  @keyframes slideDrawer {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
  }

  .lab-drawer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }
  .lab-drawer-header h3 {
    margin: 0;
    font-family: var(--font-heading);
    font-size: 2.5rem;
    font-weight: 900;
    color: #38bdf8;
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .lab-tabs {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
    overflow-x: auto;
    padding-bottom: 6px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
  }
  .lab-tab-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #cbd5e1;
    padding: 14px 28px;
    border-radius: 12px;
    font-family: var(--font-body);
    font-size: 1.25rem;
    font-weight: 800;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
  }
  .lab-tab-btn.active {
    background: rgba(56, 189, 248, 0.25);
    border-color: #38bdf8;
    color: #ffffff;
  }

  .lab-content-section {
    display: none;
    /* min(100%, 480px) lets cards shrink on phones instead of overflowing */
    grid-template-columns: repeat(auto-fit, minmax(min(100%, 480px), 1fr));
    gap: 20px;
  }
  .lab-content-section.active { display: grid; animation: fadeIn 0.25s ease; }

  .lab-card {
    background: rgba(0, 0, 0, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    padding: 24px 28px;
    min-width: 0;
  }
  .lab-card h4 {
    margin: 0 0 16px;
    font-size: 1.4rem;
    color: #a5b4fc;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .lab-rule-item {
    font-family: var(--font-mono);
    font-size: 1.2rem;
    color: #e2e8f0;
    padding: 10px 0;
    display: flex;
    justify-content: space-between;
    gap: 12px;
    border-bottom: 1px dashed rgba(255, 255, 255, 0.06);
  }
  .lab-rule-item:last-child { border-bottom: none; }

  /* ============ PAUSE OVERLAY ============ */
  .pause-overlay {
    position: fixed; inset: 0;
    background: rgba(4, 8, 22, 0.9);
    backdrop-filter: blur(14px);
    z-index: 9998;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto;
  }
  .pause-overlay.active { display: flex; }
  .pause-card {
    background: linear-gradient(165deg, #0e172e 0%, #111e3d 100%);
    border: 2px solid rgba(245, 158, 11, 0.45);
    border-radius: 24px;
    padding: 32px 28px;
    text-align: center;
    max-width: 420px;
    width: 100%;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8);
    margin: auto;
  }
  .pause-card h2 {
    font-family: var(--font-heading);
    font-size: 1.8rem;
    font-weight: 900;
    color: #fbbf24;
    margin: 12px 0 6px;
  }
  .pause-card p {
    color: #94a3b8;
    font-size: 0.92rem;
    margin: 0 0 20px;
  }

  /* ============================================================
     BURGER MENU
     ============================================================ */
  .game-burger-btn { width:44px; height:44px; border-radius:12px; background:rgba(245,158,11,.15); border:2px solid rgba(245,158,11,.4); color:#f59e0b; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; cursor:pointer; transition:all .2s; }
  .game-burger-btn:hover { background:rgba(245,158,11,.25); transform:scale(1.05); }
  .game-burger-line { width:22px; height:2.5px; background:currentColor; border-radius:2px; transition:all .3s; }
  .game-burger-btn.active .game-burger-line:nth-child(1) { transform:translateY(6.5px) rotate(45deg); }
  .game-burger-btn.active .game-burger-line:nth-child(2) { opacity:0; }
  .game-burger-btn.active .game-burger-line:nth-child(3) { transform:translateY(-6.5px) rotate(-45deg); }
  .game-burger-menu { position:fixed; top:0; right:-100%; width:min(320px, 85vw); height:100vh; height:100dvh; background:linear-gradient(160deg,rgba(14,23,46,0.98),rgba(17,30,61,0.98)); border-left:2px solid rgba(245,158,11,.4); backdrop-filter:blur(20px); z-index:10000; transition:right .35s ease; box-shadow:-10px 0 40px rgba(0,0,0,.8); display:flex; flex-direction:column; padding:20px; padding-top:70px; }
  .game-burger-menu.active { right:0; }
  .game-burger-overlay { position:fixed; inset:0; background:rgba(0,0,0,.75); backdrop-filter:blur(4px); z-index:9999; opacity:0; pointer-events:none; transition:opacity .3s; }
  .game-burger-overlay.active { opacity:1; pointer-events:all; }
  .game-burger-header { margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid rgba(255,255,255,.1); }
  .game-burger-title { font-size:1.3rem; font-weight:900; color:white; display:flex; align-items:center; gap:10px; font-family:var(--font-heading); }
  .game-burger-subtitle { font-size:.8rem; color:#64748b; margin-top:6px; font-weight:700; text-transform:uppercase; letter-spacing:1px; }
  .game-burger-nav { display:flex; flex-direction:column; gap:12px; }
  .game-burger-nav .eduplay-nav-btn { width:100%; padding:16px 20px; min-height:56px; display:flex !important; align-items:center; gap:12px; font-size:1rem; font-weight:700; border-radius:14px; text-decoration:none; transition:all .2s; justify-content:flex-start; }
  .game-burger-nav .eduplay-btn-guide { background:rgba(56,189,248,.15); border:2px solid rgba(56,189,248,.4); color:#38bdf8; }
  .game-burger-nav .eduplay-btn-guide:hover { background:rgba(56,189,248,.25); transform:translateX(4px); }
  .game-burger-nav .eduplay-btn-pause { background:rgba(251,191,36,.15); border:2px solid rgba(251,191,36,.4); color:#fbbf24; }
  .game-burger-nav .eduplay-btn-pause:hover { background:rgba(251,191,36,.25); transform:translateX(4px); }
  .game-burger-nav .eduplay-btn-map { background:rgba(52,211,153,.15); border:2px solid rgba(52,211,153,.4); color:#34d399; }
  .game-burger-nav .eduplay-btn-map:hover { background:rgba(52,211,153,.25); transform:translateX(4px); }
  .game-burger-nav .eduplay-btn-exit { background:rgba(239,68,68,.15); border:2px solid rgba(239,68,68,.4); color:#ef4444; }
  .game-burger-nav .eduplay-btn-exit:hover { background:rgba(239,68,68,.25); transform:translateX(4px); }
  .game-burger-nav .eduplay-nav-btn i { font-size:1.3rem; }
  .game-burger-nav .eduplay-nav-btn span { flex:1; text-align:left; display:inline !important; }
  .game-burger-music { margin-top:auto; padding-top:20px; border-top:1px solid rgba(255,255,255,.1); }
  .game-burger-music-title { font-size:.85rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:12px; }
  .game-music-controls { display:flex; flex-direction:column; gap:10px; }
  .game-music-btn { width:100%; padding:14px 18px; min-height:52px; display:flex; align-items:center; gap:12px; font-size:.95rem; font-weight:700; border-radius:12px; background:rgba(245,158,11,.15); border:2px solid rgba(245,158,11,.4); color:#fbbf24; cursor:pointer; transition:all .2s; }
  .game-music-btn:hover { background:rgba(245,158,11,.25); transform:translateX(4px); }
  .game-music-btn i { font-size:1.2rem; }
  .game-music-btn span { flex:1; text-align:left; }
  .game-volume-control { display:flex; align-items:center; gap:10px; padding:10px 14px; background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08); border-radius:12px; }
  .game-volume-control i { font-size:1.1rem; color:#f59e0b; }
  .game-volume-slider { flex:1; height:6px; border-radius:3px; background:rgba(255,255,255,.1); appearance:none; cursor:pointer; }
  .game-volume-slider::-webkit-slider-thumb { appearance:none; width:16px; height:16px; border-radius:50%; background:#f59e0b; cursor:pointer; }
  .game-volume-slider::-moz-range-thumb { width:16px; height:16px; border-radius:50%; background:#f59e0b; cursor:pointer; border:none; }
  .game-volume-value { font-size:.8rem; font-weight:700; color:#f59e0b; min-width:35px; text-align:right; }
  .eduplay-game-nav { display:none !important; }
  /* ── Burger Menu ── */
  #game-music-controls { display:none !important; }
  .mq-burger-btn { width:44px; height:44px; border-radius:12px; background:rgba(245,158,11,.15); border:2px solid rgba(245,158,11,.4); color:#fbbf24; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:5px; cursor:pointer; transition:all .2s; padding:0; flex-shrink:0; position:relative; z-index:100; }
  .mq-burger-btn:hover { background:rgba(245,158,11,.28); transform:scale(1.05); }
  .mq-burger-line { width:20px; height:2px; background:currentColor; border-radius:2px; transition:all .3s; display:block; }
  .mq-burger-btn.active .mq-burger-line:nth-child(1) { transform:translateY(7px) rotate(45deg); }
  .mq-burger-btn.active .mq-burger-line:nth-child(2) { opacity:0; }
  .mq-burger-btn.active .mq-burger-line:nth-child(3) { transform:translateY(-7px) rotate(-45deg); }
  .mq-burger-overlay { position:fixed; inset:0; background:rgba(0,0,0,.75); backdrop-filter:blur(4px); z-index:9999; opacity:0; pointer-events:none; transition:opacity .3s; }
  .mq-burger-overlay.active { opacity:1; pointer-events:all; }
  .mq-burger-menu { position:fixed; top:0; right:-100%; width:min(320px,85vw); height:100vh; height:100dvh; background:linear-gradient(160deg,rgba(14,23,46,.98),rgba(17,30,61,.98)); border-left:2px solid rgba(245,158,11,.4); backdrop-filter:blur(20px); z-index:10000; transition:right .35s ease; box-shadow:-10px 0 40px rgba(0,0,0,.8); display:flex; flex-direction:column; padding:20px; padding-top:70px; overflow-y:auto; }
  .mq-burger-menu.active { right:0; }
  .mq-burger-header { margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid rgba(255,255,255,.1); }
  .mq-burger-title { font-size:1.3rem; font-weight:900; color:white; display:flex; align-items:center; gap:10px; }
  .mq-burger-subtitle { font-size:.8rem; color:#64748b; margin-top:6px; font-weight:700; text-transform:uppercase; letter-spacing:1px; }
  .mq-burger-nav { display:flex; flex-direction:column; gap:12px; }
  .mq-burger-nav .eduplay-nav-btn { width:100% !important; padding:16px 20px; min-height:56px; display:flex !important; align-items:center; gap:12px; font-size:1rem; font-weight:700; border-radius:14px; text-decoration:none; transition:all .2s; justify-content:flex-start; }
  .mq-burger-nav .eduplay-nav-btn span { display:inline !important; flex:1; text-align:left; }
  .mq-burger-nav .eduplay-nav-btn i { font-size:1.3rem; }
  .mq-burger-nav .eduplay-btn-guide { background:rgba(56,189,248,.15); border:2px solid rgba(56,189,248,.4); color:#38bdf8; }
  .mq-burger-nav .eduplay-btn-guide:hover { background:rgba(56,189,248,.25); transform:translateX(4px); }
  .mq-burger-nav .eduplay-btn-pause { background:rgba(251,191,36,.15); border:2px solid rgba(251,191,36,.4); color:#fbbf24; }
  .mq-burger-nav .eduplay-btn-pause:hover { background:rgba(251,191,36,.25); transform:translateX(4px); }
  .mq-burger-nav .eduplay-btn-map { background:rgba(52,211,153,.15); border:2px solid rgba(52,211,153,.4); color:#34d399; }
  .mq-burger-nav .eduplay-btn-map:hover { background:rgba(52,211,153,.25); transform:translateX(4px); }
  .mq-burger-nav .eduplay-btn-exit { background:rgba(239,68,68,.15); border:2px solid rgba(239,68,68,.4); color:#ef4444; }
  .mq-burger-nav .eduplay-btn-exit:hover { background:rgba(239,68,68,.25); transform:translateX(4px); }
  .mq-music-row { display:flex; align-items:center; gap:10px; padding:12px 16px; background:rgba(245,158,11,.1); border:1.5px solid rgba(245,158,11,.35); border-radius:14px; }
  .mq-mute-btn { width:40px; height:40px; border-radius:10px; background:rgba(245,158,11,.2); border:1.5px solid rgba(245,158,11,.5); color:#fbbf24; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:1.1rem; flex-shrink:0; transition:all .2s; }
  .mq-mute-btn:hover { background:rgba(245,158,11,.35); }
  .mq-mute-btn.muted { background:rgba(239,68,68,.15); border-color:rgba(239,68,68,.45); color:#f87171; }
  .mq-vol-slider { flex:1; height:5px; border-radius:3px; background:rgba(255,255,255,.12); appearance:none; cursor:pointer; accent-color:#f59e0b; }
  .mq-vol-slider::-webkit-slider-thumb { appearance:none; width:15px; height:15px; border-radius:50%; background:#f59e0b; cursor:pointer; }
  .mq-vol-slider::-moz-range-thumb { width:15px; height:15px; border-radius:50%; background:#f59e0b; cursor:pointer; border:none; }
  .mq-vol-value { font-size:.8rem; font-weight:700; color:#fbbf24; min-width:34px; text-align:right; }

  .resume-btn {
    width: 100%;
    padding: 12px 0;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border: none;
    border-radius: 50px;
    color: #1e1b02;
    font-weight: 900;
    font-size: 1rem;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.4);
    transition: all 0.2s;
  }
  .resume-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.6);
  }

  /* ============ VICTORY / LEVEL COMPLETE SCREEN ============ */
  .victory-overlay {
    position: fixed; inset: 0;
    background: rgba(4, 8, 22, 0.92);
    backdrop-filter: blur(18px);
    z-index: 9996;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto;
  }
  .victory-overlay.active {
    display: flex;
    animation: modalPopIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  .victory-card {
    background: linear-gradient(165deg, #0e172e 0%, #111e3d 100%);
    border: 2px solid rgba(56, 189, 248, 0.55);
    border-radius: 28px;
    padding: 34px 30px;
    text-align: center;
    max-width: 480px;
    width: 100%;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.85), 0 0 50px rgba(56, 189, 248, 0.25);
    margin: auto;
  }
  .victory-icon {
    font-size: 4rem;
    margin-bottom: 8px;
    animation: correctPulse 1s infinite alternate;
  }
  .victory-title {
    font-family: var(--font-heading);
    font-size: 2.1rem;
    font-weight: 900;
    background: linear-gradient(135deg, #38bdf8, #818cf8, #34d399);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 0 0 6px;
  }
  .victory-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin: 20px 0;
  }
  .v-stat-box {
    background: rgba(0, 0, 0, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 10px 8px;
  }
  .v-stat-val {
    font-family: var(--font-heading);
    font-size: 1.45rem;
    font-weight: 900;
    color: #38bdf8;
  }
  .v-stat-lbl {
    font-size: 0.9rem;
    color: #94a3b8;
    font-weight: 700;
    text-transform: uppercase;
    margin-top: 2px;
  }
  .v-coins-banner {
    background: rgba(245, 158, 11, 0.15);
    border: 1.5px solid rgba(245, 158, 11, 0.4);
    border-radius: 14px;
    padding: 10px 14px;
    margin-bottom: 20px;
    font-weight: 800;
    color: #fbbf24;
    font-size: 1.02rem;
  }

  .v-actions-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
  }
  .btn-v-primary {
    flex: 1;
    min-width: 140px;
    padding: 12px 20px;
    border-radius: 50px;
    font-family: var(--font-body);
    font-size: 0.95rem;
    font-weight: 900;
    color: #ffffff;
    background: linear-gradient(135deg, #0ea5e9, #6366f1);
    border: none;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
    transition: all 0.2s;
  }
  .btn-v-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(14, 165, 233, 0.6);
  }
  .btn-v-secondary {
    flex: 1;
    min-width: 120px;
    padding: 12px 18px;
    border-radius: 50px;
    font-family: var(--font-body);
    font-size: 0.95rem;
    font-weight: 800;
    color: #cbd5e1;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    cursor: pointer;
    transition: all 0.2s;
  }
  .btn-v-secondary:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
  }

  /* Confetti particle */
  .confetti {
    position: fixed; width: 10px; height: 10px;
    animation: confettiFall 3s linear forwards;
    z-index: 9999; pointer-events: none; top: -20px;
  }
  @keyframes confettiFall {
    0% { transform: translateY(0) rotate(0deg); opacity: 1; }
    100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
  }

  /* ==========================================================================
     ADAPTIVE / MOBILE-FIRST OVERRIDES
     Breakpoints: ≤991 tablet · ≤767 phone · ≤479 small phone · landscape
     ========================================================================== */

  /* Touch devices: no sticky hover lifts, keyboard-hint row hidden */
  @media (hover: none) {
    .eduplay-nav-btn:hover,
    .answer-card:hover:not(:disabled),
    .hint-btn:hover:not(:disabled),
    .btn-open-lab:hover,
    .btn-tut-nav.next:hover,
    .resume-btn:hover,
    .btn-v-primary:hover,
    .coins-pill:hover { transform: none; }
    .answer-card:active:not(:disabled) { transform: scale(0.97); background: rgba(56, 189, 248, 0.2); border-color: #38bdf8; }
    .hint-btn:active:not(:disabled), .btn-tut-nav:active, .btn-v-primary:active,
    .btn-v-secondary:active, .resume-btn:active { transform: scale(0.96); }
    .quick-keys-hint { display: none; }
    .ans-key-hint { display: none; }
  }

  /* ---------- Tablet (≤ 991px) ---------- */
  @media (max-width: 991.98px) {
    .app-container { padding-top: calc(48px + var(--safe-top)); }
    .challenge-station { padding: 18px 18px; }
    .lab-drawer { padding: 20px 22px; }
    .lab-drawer-header h3 { font-size: 2rem; }
    .lab-tab-btn { font-size: 1.05rem; padding: 12px 20px; }
  }

  /* ---------- Phone (≤ 767px) ---------- */
  @media (max-width: 767.98px) {
    html, body { justify-content: flex-start; }

    .ruler-tape-top, .ruler-tape-bottom { height: 6px; }

    .app-container {
      width: 100%;
      padding: calc(14px + var(--safe-top)) calc(10px + var(--safe-right)) calc(20px + var(--safe-bottom)) calc(10px + var(--safe-left));
    }

    /* Masthead: stacked, centered */
    .masthead { flex-direction: column; align-items: stretch; gap: 10px; }

    /* Title on row 1, the 4 stat pills share row 2 (CSS Grid = reliable one-row layout) */
    .masthead .masthead-left {
      display: grid;
      grid-template-columns: 1.5fr 1fr 1fr 1fr;
      gap: 6px;
      width: 100%;
      align-items: stretch;
      text-align: center;
    }
    .masthead .masthead-left .title-group { grid-column: 1 / -1; }
    .title-group h1 { font-size: 1.5rem; }
    .title-group p { font-size: 0.74rem; }

    .masthead .masthead-left .level-badge,
    .masthead .masthead-left .coins-pill,
    .masthead .masthead-left .score-pill,
    .masthead .masthead-left .streak-pill {
      min-width: 0;
      width: 100%;
      justify-content: center;
      font-size: 0.78rem;
      padding: 7px 4px;
      gap: 4px;
      margin: 0;
    }

    /* Nav: horizontal row of icon-only buttons */
    .masthead .eduplay-game-nav {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 6px;
      width: 100%;
    }
    .masthead .eduplay-game-nav .eduplay-nav-btn {
      justify-content: center;
      padding: 10px 4px;
      min-height: 42px;
      font-size: 1rem;
    }
    .masthead .eduplay-game-nav .eduplay-nav-btn span { display: none; }

    /* Ladder banner: compact + horizontally scrollable steps */
    .metric-ladder-banner { padding: 8px 10px; gap: 6px; border-radius: 12px; }
    .ladder-title { font-size: 0.74rem; width: 100%; }
    .ladder-steps {
      flex-wrap: nowrap;
      overflow-x: auto;
      width: 100%;
      padding-bottom: 4px;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
    }
    .ladder-steps::-webkit-scrollbar { display: none; }
    .ladder-node { font-size: 0.76rem; padding: 3px 8px; flex-shrink: 0; }
    .ladder-op { font-size: 0.7rem; flex-shrink: 0; }
    .ladder-rule-tip { font-size: 0.8rem; width: 100%; line-height: 1.3; }

    /* Challenge card */
    .quest-layout { gap: 12px; }
    .challenge-station { padding: 14px 12px; border-radius: 18px; }
    .station-header { margin-bottom: 10px; padding-bottom: 10px; flex-wrap: nowrap; }
    .q-progress-group { flex-wrap: wrap; gap: 6px; flex: 1; min-width: 0; }
    .timer-wrap { flex-shrink: 0; }
    .q-chip { font-size: 0.74rem; padding: 4px 9px; }
    .topic-category-chip { font-size: 0.74rem; padding: 4px 9px; }
    .timer-pill { font-size: 0.82rem; padding: 4px 11px; }
    .q-progress-bar-bg { margin-bottom: 12px; }

    .question-station { padding: 16px 12px; border-radius: 16px; margin-bottom: 12px; }
    .question-text { max-width: 100%; font-size: 1.12rem; line-height: 1.4; }
    .quest-mode-tag { font-size: 0.68rem; margin-bottom: 8px; }

    /* Answer buttons: big, thumb-friendly */
    .answers-grid { gap: 10px; margin-bottom: 12px; }
    .answer-card { padding: 14px 14px; min-height: 58px; font-size: 1.05rem; border-radius: 14px; }
    .ans-badge { width: 26px; height: 26px; margin-right: 10px; }

    .feedback-box { font-size: 0.88rem; padding: 10px 12px; }
    .hint-reveal-box { font-size: 0.86rem; padding: 10px 12px; }

    .station-bottom-bar { justify-content: center; }
    .hint-btn { width: 100%; justify-content: center; min-height: 46px; font-size: 0.95rem; }

    /* Sidebar below the game */
    .sidebar-box { padding: 12px; border-radius: 16px; }
    .cheat-item { font-size: 0.88rem; }
    .btn-open-lab { min-height: 44px; }

    /* Tutorial deck: scrolls, compact */
    .tut-overlay { padding: 10px; align-items: flex-start; padding-top: calc(10px + var(--safe-top)); padding-bottom: calc(10px + var(--safe-bottom)); }
    .tut-deck { padding: 18px 14px 16px; border-radius: 20px; }
    .tut-deck-header { margin-bottom: 10px; padding-bottom: 8px; }
    .tut-deck-tag { font-size: 0.68rem; letter-spacing: 0.3px; padding: 4px 9px; line-height: 1.3; }
    .btn-close-tut { font-size: 2.2rem; min-width: 44px; min-height: 44px; }
    .fc-title { font-size: 1.2rem; flex-wrap: wrap; }
    .fc-desc { font-size: 0.94rem; margin-bottom: 12px; }
    .fc-visual-box { padding: 12px; margin-bottom: 12px; border-radius: 14px; }
    .fc-grid-2 { grid-template-columns: 1fr; gap: 8px; }
    .fc-mini-card { padding: 12px; }
    .fc-mini-card h5 { font-size: 0.94rem; }
    .fc-mini-card p { font-size: 0.86rem; }
    .ladder-rule-row { flex-wrap: wrap; font-size: 0.92rem; padding: 8px 10px; }
    .rule-badge { font-size: 0.78rem; }
    .tut-deck-nav { flex-direction: column; gap: 12px; }
    .tut-nav-btns { width: 100%; }
    .btn-tut-nav { flex: 1; justify-content: center; min-height: 46px; padding: 8px 10px; font-size: 0.94rem; }

    /* Metric Lab: full-screen sheet */
    .lab-overlay { align-items: stretch; }
    .lab-drawer {
      max-width: 100vw;
      height: 100%;
      border-radius: 0;
      border-width: 0;
      padding: calc(14px + var(--safe-top)) 14px calc(14px + var(--safe-bottom));
    }
    .lab-drawer-header { margin-bottom: 12px; padding-bottom: 10px; }
    .lab-drawer-header h3 { font-size: 1.35rem; gap: 8px; }
    .lab-tabs { gap: 8px; margin-bottom: 12px; }
    .lab-tab-btn { font-size: 0.9rem; padding: 10px 14px; border-radius: 10px; }
    .lab-content-section { gap: 12px; }
    .lab-card { padding: 14px 14px; border-radius: 14px; }
    .lab-card h4 { font-size: 1.05rem; margin-bottom: 10px; }
    .lab-rule-item { font-size: 0.88rem; padding: 8px 0; flex-wrap: wrap; row-gap: 2px; }
    .lab-card > div[style*="font-size:1.15rem"] { font-size: 0.95rem !important; line-height: 1.6 !important; }

    /* Pause & victory */
    .pause-card { padding: 24px 18px; border-radius: 20px; }
    .pause-card h2 { font-size: 1.5rem; }
    .resume-btn { min-height: 48px; }
    .victory-card { padding: 24px 16px; border-radius: 22px; }
    .victory-icon { font-size: 3.2rem; }
    .victory-title { font-size: 1.65rem; }
    .victory-stats-grid { gap: 8px; margin: 14px 0; }
    .v-stat-val { font-size: 1.15rem; }
    .v-stat-lbl { font-size: 0.7rem; }
    .v-coins-banner { font-size: 0.9rem; padding: 8px 10px; margin-bottom: 14px; }
    .v-actions-row { flex-direction: column-reverse; }
    .btn-v-primary, .btn-v-secondary { width: 100%; min-height: 48px; }
  }

  /* ---------- Small phones (≤ 479px) ---------- */
  @media (max-width: 479.98px) {
    .title-group h1 { font-size: 1.3rem; }
    .question-text { font-size: 1.05rem; }
    .answer-card { font-size: 1rem; }
    .masthead .eduplay-game-nav { gap: 4px; }
    .tut-deck-tag { font-size: 0.62rem; }
  }

  /* ---------- Landscape phones (short viewport) ---------- */
  @media (max-height: 500px) and (orientation: landscape) {
    .app-container { padding-top: calc(12px + var(--safe-top)); }
    .quest-layout { grid-template-columns: minmax(0, 1fr) 260px; }
    .answers-grid { grid-template-columns: 1fr 1fr; }
    .question-station { padding: 10px; margin-bottom: 8px; }
    .question-text { font-size: 1rem; }
    .answer-card { min-height: 46px; padding: 8px 12px; }
    .masthead { flex-direction: row; }
    .masthead .masthead-left { display: flex; flex-wrap: wrap; text-align: left; }
    .title-group p { display: none; }
    .victory-card, .pause-card { padding: 16px; }
    .victory-icon { font-size: 2.2rem; margin-bottom: 0; }
    .victory-stats-grid { margin: 8px 0; }
  }
</style>

<!-- ==========================================================================
     FLOATING METRIC SIGNS & AMBIENT LAYER
     ========================================================================== -->
<div id="sym-layer"></div>
<div class="ruler-tape-top"></div>
<div class="ruler-tape-bottom"></div>

<div class="app-container">

  <!-- ============ MASTHEAD ============ -->
  <header class="masthead">
    <div class="masthead-left">
      <div class="title-group">
        <h1><i class="bi bi-compass-fill"></i> The Metric Quest</h1>
        <p>A Time Traveler's Odyssey of Units, Length, Mass &amp; Time</p>
      </div>

      <div class="level-badge" id="levelStageBadge">
        <i class="bi bi-flag-fill"></i> Stage {{ $progress->current_level ?? 1 }}
      </div>

      <div class="coins-pill" title="Your Expedition Coins">
        <span>🪙</span> <span id="coinDisplay">50</span>
      </div>

      <div class="score-pill" title="Expedition Score">
        <span>⭐</span> <span id="scoreDisplay">0</span>
      </div>

      <div class="streak-pill" id="streakBadge" title="Consecutive Correct Streak">
        <span>🔥</span> <span id="streakDisplay">0x</span>
      </div>
    </div>

    <!-- Eduplay Action Nav -->
    <div class="eduplay-game-nav">
      <button type="button" class="eduplay-nav-btn eduplay-btn-guide" id="btnOpenTutorial" title="Tutorial &amp; Rules Guide" aria-label="Field Guide">
        <i class="bi bi-book-half"></i> <span>Field Guide</span>
      </button>

      <button type="button" class="eduplay-nav-btn eduplay-btn-lab" id="btnOpenLab" title="Metric Lab Reference" aria-label="Metric Lab">
        <i class="bi bi-rulers"></i> <span>Metric Lab</span>
      </button>

      <button type="button" class="eduplay-nav-btn eduplay-btn-sound" id="btnSoundToggle" title="Sound Effects (M)" aria-label="Toggle sound">
        <i class="bi bi-volume-up-fill" id="soundIcon"></i>
      </button>

      <button type="button" class="eduplay-nav-btn eduplay-btn-pause" id="btnPauseGame" title="Pause Game (P or Esc)" aria-label="Pause">
        <i class="bi bi-pause-fill"></i> <span>Pause</span>
      </button>

      <a href="{{ route('student.map', 'the_metric_quest') }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map" aria-label="Game Map">
        <i class="bi bi-map-fill"></i> <span>Map</span>
      </a>

      <a href="{{ route('student.games') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Game Arena" aria-label="Exit">
        <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
      </a>
    </div>
    <!-- Burger button -->
    <button type="button" class="mq-burger-btn" id="burgerBtn" onclick="toggleBurgerMenu()" aria-label="Game menu">
      <span class="mq-burger-line"></span><span class="mq-burger-line"></span><span class="mq-burger-line"></span>
    </button>
  </header>

  <!-- ============ DYNAMIC METRIC CONVERSION LADDER BANNER ============ -->
  <div class="metric-ladder-banner" id="ladderBanner">
    <div class="ladder-title">
      <i class="bi bi-diagram-3-fill text-info"></i> <span id="ladderRealmName">Metric Ladder:</span>
    </div>
    <div class="ladder-steps" id="ladderSteps">
      <!-- Injected dynamically based on topic -->
    </div>
    <div class="ladder-rule-tip">
      <i class="bi bi-lightbulb-fill text-warning"></i>
      <span id="ladderTipText">Big unit to small unit = MULTIPLY! Small unit to big unit = DIVIDE!</span>
    </div>
  </div>

  <!-- ============ MAIN GAME LAYOUT ============ -->
  <main class="quest-layout">

    <!-- CHALLENGE STATION (LEFT) -->
    <section class="challenge-station" id="challengeStation" aria-label="Current Challenge Station">
      <div>
        <div class="station-header">
          <div class="q-progress-group">
            <span class="q-chip" id="qNumBadge">Question 1 / 10</span>
            <span class="topic-category-chip" id="categoryChip">
              <i class="bi bi-rulers"></i> Length: m &amp; cm
            </span>
          </div>

          <div class="timer-wrap">
            <div class="timer-pill" id="timerPill">
              <i class="bi bi-stopwatch"></i> <span id="timerText">30s</span>
            </div>
          </div>
        </div>

        <!-- Level Question Progress Bar -->
        <div class="q-progress-bar-bg">
          <div class="q-progress-bar-fill" id="progressBar"></div>
        </div>

        <!-- Question Box -->
        <div class="question-station">
          <span class="quest-mode-tag" id="modeTag">
            <i class="bi bi-geo-alt-fill"></i> Measurement Mission
          </span>
          <div class="question-text" id="questionText">Loading challenge…</div>
        </div>

        <!-- Hint Box (Revealed on Request) -->
        <div class="hint-reveal-box" id="hintBox"></div>

        <!-- 4 Answer Option Tablets -->
        <div class="answers-grid" id="answersGrid">
          <!-- Dynamically populated -->
        </div>

        <!-- Feedback alert -->
        <div class="feedback-box" id="feedbackBox"></div>

      </div>

      <!-- Bottom Toolbar inside Challenge Card -->
      <div class="station-bottom-bar">
        <button type="button" class="hint-btn" id="btnHint" onclick="useHint()">
          <span>🪙</span> <span>Hint (5 🪙)</span>
        </button>

        <div class="quick-keys-hint">
          <span>Shortcuts:</span>
          <kbd>1</kbd>–<kbd>4</kbd> <span>Answer</span>
          <kbd>H</kbd> <span>Hint</span>
          <kbd>P</kbd> <span>Pause</span>
        </div>
      </div>
    </section>

    <!-- EXPEDITION SIDEBAR (RIGHT) -->
    <aside class="expedition-sidebar">

      <!-- Surveyor Profile & Stats -->
      <div class="sidebar-box">
        <h3>
          <span>Surveyor Profile</span>
          <span class="badge" style="font-size:0.75rem; background:rgba(56,189,248,0.2); color:#7dd3fc; border:1px solid rgba(56,189,248,0.4); padding:3px 8px; border-radius:50px;">Stage {{ $progress->current_level ?? 1 }}</span>
        </h3>

        @php
          $charAvatars = [
            'nova' => '⭐',
            'quantum' => '⚡',
            'vortex' => '🌀',
            'titan' => '🛡️',
            'circuit' => '💡',
            'cryptos' => '🔐',
            'apex' => '🏆'
          ];
          $avatarEmoji = $charAvatars[auth()->user()->character_avatar ?? ''] ?? '🧭';
        @endphp

        <div class="player-card">
          <div class="player-avatar">{{ $avatarEmoji }}</div>
          <div class="player-details">
            <div class="player-name">{{ auth()->user()->name ?? 'Player' }}</div>
            <div class="player-title">Metric Time Traveler</div>
          </div>
        </div>

        <div class="stage-progress-track">
          <div class="stage-track-header">
            <span>Stage Objective</span>
            <span id="stageObjectiveText">70% Accuracy to Advance</span>
          </div>
          <div class="q-progress-bar-bg" style="margin:4px 0 0; height:6px;">
            <div class="q-progress-bar-fill" id="sidebarProgressFill" style="width:0%;"></div>
          </div>
        </div>
      </div>

      <!-- Quick Conversion Reference Card -->
      <div class="sidebar-box">
        <h3>
          <span>Quick Metric Reference</span>
          <i class="bi bi-speedometer2 text-info"></i>
        </h3>

        <div class="mini-cheat-grid" id="miniCheatGrid">
          <div class="cheat-item">
            <span class="cheat-label">Length</span>
            <span class="cheat-rule">1 km = 1,000 m</span>
          </div>
          <div class="cheat-item">
            <span class="cheat-label">Length</span>
            <span class="cheat-rule">1 m = 100 cm</span>
          </div>
          <div class="cheat-item">
            <span class="cheat-label">Mass</span>
            <span class="cheat-rule">1 kg = 1,000 g</span>
          </div>
          <div class="cheat-item">
            <span class="cheat-label">Capacity</span>
            <span class="cheat-rule">1 L = 1,000 mL</span>
          </div>
          <div class="cheat-item">
            <span class="cheat-label">Time</span>
            <span class="cheat-rule">1 hr = 60 min</span>
          </div>
          <div class="cheat-item">
            <span class="cheat-label">Time</span>
            <span class="cheat-rule">1 day = 24 hrs</span>
          </div>
        </div>

        <button type="button" class="btn-open-lab" id="btnSidebarOpenLab">
          <i class="bi bi-journal-text"></i> Open Full Metric Lab &amp; Tables
        </button>
      </div>

    </aside>
  </main>
</div>

<!-- ==========================================================================
     ILLUSTRATED FLASHCARD TUTORIAL DECK (3-CARD MODAL)
     ========================================================================== -->
<div class="tut-overlay" id="tutOverlay" role="dialog" aria-modal="true" aria-labelledby="tutTag">
  <div class="tut-deck">
    <div class="tut-deck-header">
      <span class="tut-deck-tag" id="tutTag">
        <i class="bi bi-journal-bookmark-fill"></i> Field Guide &amp; Instructions • Card <span id="fcPageNum">1 / 3</span>
      </span>
      <button type="button" class="btn-close-tut" onclick="closeTutorial()" title="Close Instructions" aria-label="Close instructions">&times;</button>
    </div>

    <!-- CARD 1: Realms -->
    <div class="fc-card active" id="fcCard1">
      <h3 class="fc-title">🧭 Master the Metric World!</h3>
      <p class="fc-desc">Pick a realm. Convert units. Win coins!</p>
      <div class="mq-row2">
        <div class="mq-tile"><div class="mq-big">📏</div><div class="mq-lbl">Length</div><div class="mq-units">km · m · cm · mm</div></div>
        <div class="mq-tile"><div class="mq-big">⚖️</div><div class="mq-lbl">Mass</div><div class="mq-units">kg · g · mg</div></div>
        <div class="mq-tile"><div class="mq-big">💧</div><div class="mq-lbl">Capacity</div><div class="mq-units">L · mL</div></div>
        <div class="mq-tile"><div class="mq-big">🕐</div><div class="mq-lbl">Time</div><div class="mq-units">s · min · hr · day</div></div>
      </div>
      <div class="mq-tip">🗺️ 50 expedition levels await!</div>
    </div>

    <!-- CARD 2: Golden Rule + Try It -->
    <div class="fc-card" id="fcCard2">
      <h3 class="fc-title">⚡ Try the Golden Rule!</h3>
      <div class="mq-row2" style="margin-bottom:12px;">
        <div class="mq-rule" style="border-color:rgba(16,185,129,.5);background:rgba(16,185,129,.1);">
          <div class="mq-big">🐘➡️🐜</div>
          <div class="mq-rule-lbl" style="color:#34d399;">BIG ➔ small = ✖️</div>
          <div class="mq-rule-eg">km ➔ m</div>
        </div>
        <div class="mq-rule" style="border-color:rgba(56,189,248,.5);background:rgba(56,189,248,.1);">
          <div class="mq-big">🐜➡️🐘</div>
          <div class="mq-rule-lbl" style="color:#38bdf8;">small ➔ BIG = ➗</div>
          <div class="mq-rule-eg">cm ➔ m</div>
        </div>
      </div>
      <div class="mq-try-q">👆 2 km = ? m</div>
      <div class="mq-try-row">
        <button type="button" class="mq-try-btn" onclick="mqTry(this,0)">20 m</button>
        <button type="button" class="mq-try-btn" onclick="mqTry(this,0)">200 m</button>
        <button type="button" class="mq-try-btn" onclick="mqTry(this,1)">2,000 m</button>
      </div>
      <div id="mqTryMsg">Give it a go! 🧭</div>
    </div>

    <!-- CARD 3: Key numbers + controls -->
    <div class="fc-card" id="fcCard3">
      <h3 class="fc-title">🚀 Ready to Explore?</h3>
      <div class="mq-chips">
        <div class="mq-chip">1 km = 1,000 m</div>
        <div class="mq-chip">1 m = 100 cm</div>
        <div class="mq-chip">1 kg = 1,000 g</div>
        <div class="mq-chip">1 L = 1,000 mL</div>
        <div class="mq-chip">1 hr = 60 min</div>
        <div class="mq-chip">1 day = 24 hr</div>
      </div>
      <div class="mq-row3">
        <div class="mq-tile"><div class="mq-big">🪙</div><div class="mq-lbl">Hint</div><div class="mq-units">-5 coins</div></div>
        <div class="mq-tile"><div class="mq-big">⌨️</div><div class="mq-lbl">Keys 1–4</div><div class="mq-units">or just tap</div></div>
        <div class="mq-tile"><div class="mq-big">📐</div><div class="mq-lbl">Metric Lab</div><div class="mq-units">open anytime</div></div>
      </div>
    </div>

    <!-- Deck Controls Footer -->
    <div class="tut-deck-nav">
      <div class="tut-dots">
        <span class="tut-dot active" onclick="goFlashCard(0)"></span>
        <span class="tut-dot" onclick="goFlashCard(1)"></span>
        <span class="tut-dot" onclick="goFlashCard(2)"></span>
      </div>

      <div class="tut-nav-btns">
        <button type="button" class="btn-tut-nav skip" id="btnTutSkip" onclick="closeTutorial()">Skip ⏩</button>
        <button type="button" class="btn-tut-nav prev" id="btnTutPrev" onclick="stepFlashCard(-1)" style="display:none;">Back</button>
        <button type="button" class="btn-tut-nav next" id="btnTutNext" onclick="stepFlashCard(1)">Next ➔</button>
        <button type="button" class="btn-tut-nav start" id="btnTutStart" onclick="closeTutorial()" style="display:none;">Let's Explore! 🚀</button>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================================================
     METRIC LAB REFERENCE DRAWER
     ========================================================================== -->
<div class="lab-overlay" id="labOverlay" role="dialog" aria-modal="true" aria-labelledby="labTitle">
  <div class="lab-drawer">
    <div class="lab-drawer-header">
      <h3 id="labTitle"><i class="bi bi-rulers"></i> Metric Laboratory</h3>
      <button type="button" class="btn-close-tut" onclick="closeLab()" aria-label="Close Metric Lab">&times;</button>
    </div>

    <!-- Realm Tabs -->
    <div class="lab-tabs">
      <button type="button" class="lab-tab-btn active" onclick="switchLabTab('length')">📏 Length</button>
      <button type="button" class="lab-tab-btn" onclick="switchLabTab('mass')">⚖️ Mass</button>
      <button type="button" class="lab-tab-btn" onclick="switchLabTab('cap')">🥤 Capacity</button>
      <button type="button" class="lab-tab-btn" onclick="switchLabTab('time')">🕐 Time</button>
    </div>

    <!-- Section 1: Length -->
    <div class="lab-content-section active" id="labSec-length">
      <div class="lab-card">
        <h4><i class="bi bi-arrows-expand"></i> 📏 Length Conversions</h4>
        <div class="lab-rule-item"><span>1 Kilometer (km)</span><span>= 1,000 Meters (m)</span></div>
        <div class="lab-rule-item"><span>1 Hectometer (hm)</span><span>= 100 Meters (m)</span></div>
        <div class="lab-rule-item"><span>1 Decameter (dam)</span><span>= 10 Meters (m)</span></div>
        <div class="lab-rule-item"><span>1 Meter (m)</span><span>= 10 Decimeters (dm)</span></div>
        <div class="lab-rule-item"><span>1 Meter (m)</span><span>= 100 Centimeters (cm)</span></div>
        <div class="lab-rule-item"><span>1 Meter (m)</span><span>= 1,000 Millimeters (mm)</span></div>
        <div class="lab-rule-item"><span>1 Decimeter (dm)</span><span>= 10 Centimeters (cm)</span></div>
        <div class="lab-rule-item"><span>1 Centimeter (cm)</span><span>= 10 Millimeters (mm)</span></div>
        <div class="lab-rule-item"><span>1 Millimeter (mm)</span><span>= 1,000 Micrometers (µm)</span></div>
        <div class="lab-rule-item"><span>1 Micrometer (µm)</span><span>= 1,000 Nanometers (nm)</span></div>
      </div>
      <div class="lab-card">
        <h4><i class="bi bi-lightbulb-fill text-warning"></i> Real-World Intuition</h4>
        <div style="font-size:1.15rem; line-height:1.7; color:#cbd5e1;">
          <div>• <b>1 nm:</b> Width of a double-helix DNA strand</div>
          <div>• <b>1 µm:</b> Size of a single microscopic bacterium cell</div>
          <div>• <b>1 mm:</b> Thickness of a plastic credit card or ID card</div>
          <div>• <b>1 cm:</b> Width of your pinky fingernail</div>
          <div>• <b>1 dm:</b> Width of a standard adult hand</div>
          <div>• <b>1 m:</b> Height of a household kitchen counter top</div>
          <div>• <b>1 dam:</b> Length of a standard yellow school bus</div>
          <div>• <b>1 hm:</b> Length of a professional soccer field</div>
          <div>• <b>1 km:</b> A brisk 10 to 12 minute outdoor walk</div>
          <div>• <b>1 Mm (Megameter):</b> Flying distance from New York City to Detroit</div>
        </div>
      </div>
    </div>

    <!-- Section 2: Mass -->
    <div class="lab-content-section" id="labSec-mass">
      <div class="lab-card">
        <h4><i class="bi bi-box-seam"></i> ⚖️ Mass Conversions</h4>
        <div class="lab-rule-item"><span>1 Metric Ton (t)</span><span>= 1,000 Kilograms (kg)</span></div>
        <div class="lab-rule-item"><span>1 Quintal (q)</span><span>= 100 Kilograms (kg)</span></div>
        <div class="lab-rule-item"><span>1 Kilogram (kg)</span><span>= 10 Hectograms (hg)</span></div>
        <div class="lab-rule-item"><span>1 Kilogram (kg)</span><span>= 1,000 Grams (g)</span></div>
        <div class="lab-rule-item"><span>1 Hectogram (hg)</span><span>= 100 Grams (g)</span></div>
        <div class="lab-rule-item"><span>1 Decagram (dag)</span><span>= 10 Grams (g)</span></div>
        <div class="lab-rule-item"><span>1 Gram (g)</span><span>= 10 Decigrams (dg)</span></div>
        <div class="lab-rule-item"><span>1 Gram (g)</span><span>= 100 Centigrams (cg)</span></div>
        <div class="lab-rule-item"><span>1 Gram (g)</span><span>= 1,000 Milligrams (mg)</span></div>
        <div class="lab-rule-item"><span>1 Milligram (mg)</span><span>= 1,000 Micrograms (µg)</span></div>
      </div>
      <div class="lab-card">
        <h4><i class="bi bi-lightbulb-fill text-warning"></i> Real-World Intuition</h4>
        <div style="font-size:1.15rem; line-height:1.7; color:#cbd5e1;">
          <div>• <b>1 µg:</b> Weight of a tiny speck of dust floating in the air</div>
          <div>• <b>1 mg:</b> Weight of a small grain of sand on the beach</div>
          <div>• <b>1 dg:</b> Weight of a small bird feather</div>
          <div>• <b>1 g:</b> Weight of a standard metal paperclip</div>
          <div>• <b>1 dag:</b> Weight of a single fun-size chocolate bar</div>
          <div>• <b>1 hg:</b> Weight of a medium-sized apple</div>
          <div>• <b>1 kg:</b> Weight of a full 1-liter plastic water bottle</div>
          <div>• <b>1 q:</b> Weight of a large commercial sack of rice</div>
          <div>• <b>1 t:</b> Weight of a small compact passenger car</div>
          <div>• <b>1 kt (Kilotonne):</b> Structural mass of a massive cargo cruise ship</div>
        </div>
      </div>
    </div>

    <!-- Section 3: Capacity -->
    <div class="lab-content-section" id="labSec-cap">
      <div class="lab-card">
        <h4><i class="bi bi-droplet-half"></i> 💧 Capacity Conversions</h4>
        <div class="lab-rule-item"><span>1 Megaliter (ML)</span><span>= 1,000,000 Liters (L)</span></div>
        <div class="lab-rule-item"><span>1 Kiloliter (kL)</span><span>= 1,000 Liters (L)</span></div>
        <div class="lab-rule-item"><span>1 Hectoliter (hL)</span><span>= 100 Liters (L)</span></div>
        <div class="lab-rule-item"><span>1 Decaliter (daL)</span><span>= 10 Liters (L)</span></div>
        <div class="lab-rule-item"><span>1 Liter (L)</span><span>= 10 Deciliters (dL)</span></div>
        <div class="lab-rule-item"><span>1 Liter (L)</span><span>= 100 Centiliters (cL)</span></div>
        <div class="lab-rule-item"><span>1 Liter (L)</span><span>= 1,000 Milliliters (mL)</span></div>
        <div class="lab-rule-item"><span>1 Deciliter (dL)</span><span>= 10 Centiliters (cL)</span></div>
        <div class="lab-rule-item"><span>1 Centiliter (cL)</span><span>= 10 Milliliters (mL)</span></div>
        <div class="lab-rule-item"><span>1 Milliliter (mL)</span><span>= 1,000 Microliters (µL)</span></div>
      </div>
      <div class="lab-card">
        <h4><i class="bi bi-lightbulb-fill text-warning"></i> Real-World Intuition</h4>
        <div style="font-size:1.15rem; line-height:1.7; color:#cbd5e1;">
          <div>• <b>1 µL:</b> A tiny single laboratory fluid droplet</div>
          <div>• <b>1 mL:</b> About 20 drops of water inside a small medical syringe</div>
          <div>• <b>1 cL:</b> A small single sip of liquid from a cup</div>
          <div>• <b>1 dL:</b> Volume of about half a glass of juice</div>
          <div>• <b>1 L:</b> Size of a standard reusable sports water bottle</div>
          <div>• <b>1 daL:</b> Fluid volume of a standard large household mop bucket</div>
          <div>• <b>1 hL:</b> A large liquid storage barrel vat used in commercial breweries</div>
          <div>• <b>1 kL:</b> Size of a large residential rooftop water storage tank</div>
          <div>• <b>1 ML:</b> Volume of a small public neighborhood swimming pool</div>
          <div>• <b>1 GL (Gigaliter):</b> Volume of a massive municipal water reservoir lake</div>
        </div>
      </div>
    </div>

    <!-- Section 4: Time -->
    <div class="lab-content-section" id="labSec-time">
      <div class="lab-card">
        <h4><i class="bi bi-clock-history"></i> ⏱️ Time Conversions</h4>
        <div class="lab-rule-item"><span>1 Millennium</span><span>= 1,000 Years</span></div>
        <div class="lab-rule-item"><span>1 Century</span><span>= 100 Years</span></div>
        <div class="lab-rule-item"><span>1 Decade</span><span>= 10 Years</span></div>
        <div class="lab-rule-item"><span>1 Year</span><span>= 365 Days</span></div>
        <div class="lab-rule-item"><span>1 Month</span><span>≈ 30 Days</span></div>
        <div class="lab-rule-item"><span>1 Week</span><span>= 7 Days</span></div>
        <div class="lab-rule-item"><span>1 Day</span><span>= 24 Hours</span></div>
        <div class="lab-rule-item"><span>1 Hour</span><span>= 60 Minutes</span></div>
        <div class="lab-rule-item"><span>1 Minute</span><span>= 60 Seconds</span></div>
        <div class="lab-rule-item"><span>1 Second</span><span>= 1,000 Milliseconds (ms)</span></div>
      </div>
      <div class="lab-card">
        <h4><i class="bi bi-lightbulb-fill text-warning"></i> Real-World Intuition</h4>
        <div style="font-size:1.15rem; line-height:1.7; color:#cbd5e1;">
          <div>• <b>1 ms:</b> Speed of a high-speed camera shutter blink</div>
          <div>• <b>1 second:</b> Duration of a single resting human heartbeat</div>
          <div>• <b>1 minute:</b> Duration of a quick television commercial break</div>
          <div>• <b>1 hour:</b> Time span of a standard school class period</div>
          <div>• <b>1 day:</b> One full rotation of the Earth on its axis</div>
          <div>• <b>1 week:</b> Time span of a standard five-day school week plus a weekend</div>
          <div>• <b>1 month:</b> The time it takes for one full cycle of the moon's phases</div>
          <div>• <b>1 year:</b> Time it takes the Earth to make one full orbit around the Sun</div>
          <div>• <b>1 decade:</b> The time it takes for an elementary school child to grow into a teenager</div>
          <div>• <b>1 century:</b> The average lifetime lifespan of a very long-lived human being</div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- ============ PAUSE OVERLAY ============ -->
<div class="pause-overlay" id="pauseOverlay">
  <div class="pause-card">
    <div style="font-size:3.2rem; filter:drop-shadow(0 0 20px rgba(245,158,11,0.5));">⏸️</div>
    <h2>Expedition Paused</h2>
    <p>Catch your breath! Ready to jump back into the measurement quest?</p>
    <button type="button" class="resume-btn" onclick="togglePause()">▶️ Resume Quest</button>
    <div class="eduplay-game-nav" style="display:flex;gap:10px;width:100%;justify-content:center;margin-top:14px;">
      <a href="{{ route('student.map', 'the_metric_quest') }}" class="eduplay-nav-btn eduplay-btn-map" style="flex:1;justify-content:center;">
        <i class="bi bi-map-fill"></i> <span>Game Map</span>
      </a>
      <a href="{{ route('student.games') }}" class="eduplay-nav-btn eduplay-btn-exit" style="flex:1;justify-content:center;">
        <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
      </a>
    </div>
  </div>
</div>

<!-- ============ VICTORY / LEVEL COMPLETE OVERLAY ============ -->
<div class="victory-overlay" id="victoryOverlay">
  <div class="victory-card">
    <div class="victory-icon" id="vIcon">🏆</div>
    <h2 class="victory-title" id="vTitle">Stage Conquered!</h2>
    <p id="vSubtitle" style="color:#cbd5e1; font-weight:700; margin:0 0 12px;">Outstanding measurement accuracy!</p>

    <div class="v-coins-banner" id="vCoinsBanner">
      🪙 +50 Coins Earned! Total: 100 🪙
    </div>

    <div class="victory-stats-grid">
      <div class="v-stat-box">
        <div class="v-stat-val" id="vScoreVal">120</div>
        <div class="v-stat-lbl">Points</div>
      </div>
      <div class="v-stat-box">
        <div class="v-stat-val" id="vAccuracyVal">100%</div>
        <div class="v-stat-lbl">Accuracy</div>
      </div>
      <div class="v-stat-box">
        <div class="v-stat-val" id="vStreakVal">10x</div>
        <div class="v-stat-lbl">Max Streak</div>
      </div>
    </div>

    <div class="v-actions-row">
      <button type="button" class="btn-v-secondary" onclick="retryStage()">
        <i class="bi bi-arrow-clockwise"></i> Replay Stage
      </button>
      <button type="button" class="btn-v-primary" id="btnNextStage" onclick="advanceStage()">
        Next Stage ➔
      </button>
    </div>
  </div>
</div>

<!-- BURGER MENU -->
<div class="mq-burger-overlay" id="burgerOverlay" onclick="toggleBurgerMenu()"></div>
<div class="mq-burger-menu" id="burgerMenu">
  <div class="mq-burger-header">
    <div class="mq-burger-title"><i class="bi bi-menu-button-wide-fill"></i> Game Menu</div>
    <div class="mq-burger-subtitle">The Metric Quest</div>
  </div>
  <nav class="mq-burger-nav">
    <button type="button" class="eduplay-nav-btn eduplay-btn-guide" onclick="document.getElementById('btnOpenTutorial').click(); toggleBurgerMenu();" title="Field Guide">
      <i class="bi bi-book-half"></i> <span>Field Guide</span>
    </button>
    <button type="button" class="eduplay-nav-btn eduplay-btn-pause" onclick="toggleBurgerMenu();" title="Resume Game">
      <i class="bi bi-play-fill"></i> <span>Resume</span>
    </button>
    <a href="{{ route('student.map', 'the_metric_quest') }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map">
      <i class="bi bi-map-fill"></i> <span>Game Map</span>
    </a>
    <div class="mq-music-row">
      <button type="button" class="mq-mute-btn" id="muteBtn" onclick="toggleMute()" title="Mute / Unmute">
        <i class="bi bi-volume-up-fill" id="muteIcon"></i>
      </button>
      <input type="range" class="mq-vol-slider" id="volumeSlider" min="0" max="100" value="50" oninput="setVolume(this.value)">
      <span class="mq-vol-value" id="volumeValue">50%</span>
    </div>
    <a href="{{ route('student.games') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Game Arena">
      <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
    </a>
  </nav>
</div>

<script src="{{ asset('js/game-feedback.js') }}?v=20250116"></script>
<script src="{{ asset('js/game-music-player.js') }}"></script>
<script>
// Initialize music player
let musicPlayer;
if (window.GameMusicPlayer) {
    musicPlayer = new window.GameMusicPlayer("{{ asset('sounds/Joystick_Rhapsody.mp3') }}");
}
function toggleBurgerMenu() {
  const burgerBtn = document.getElementById('burgerBtn');
  const burgerMenu = document.getElementById('burgerMenu');
  const burgerOverlay = document.getElementById('burgerOverlay');
  if (!burgerMenu) return;
  const opening = !burgerMenu.classList.contains('active');

  if (burgerBtn) burgerBtn.classList.toggle('active');
  burgerMenu.classList.toggle('active');
  if (burgerOverlay) burgerOverlay.classList.toggle('active');

  if (opening) {
    if (typeof togglePause === 'function') togglePause();
  } else {
    if (typeof togglePause === 'function') togglePause();
  }
}
function toggleMute() {
  if (!musicPlayer) return;
  const btn = document.getElementById('muteBtn');
  const icon = document.getElementById('muteIcon');
  if (musicPlayer.isPlaying) {
    musicPlayer.pause();
    if (icon) icon.className = 'bi bi-volume-mute-fill';
    if (btn) btn.classList.add('muted');
  } else {
    musicPlayer.play();
    if (icon) icon.className = 'bi bi-volume-up-fill';
    if (btn) btn.classList.remove('muted');
  }
}
function setVolume(val) {
  const v = document.getElementById('volumeValue');
  if (v) v.textContent = val + '%';
  if (musicPlayer) musicPlayer.setVolume(val / 100);
}
// Verify feedback is loaded
if (typeof window.showGameFeedback === 'undefined') {
    console.error('CRITICAL: game-feedback.js did not load! Feedback will not work.');
} else {
    console.log('✓ game-feedback.js loaded successfully');
}
</script>

<script>
(function(){
  "use strict";

  /* ==========================================================================
     GLOBAL CONSTANTS & PERSISTENCE
     ========================================================================== */
  const CURRENT_LEVEL = {{ $progress->current_level ?? 1 }};
  const GAME_ID = {{ $game->id ?? 0 }};
  const CSRF_TOKEN = '{{ csrf_token() }}';
  const USER_ID = '{{ auth()->id() ?? 0 }}';

  const COINS_KEY = 'eduplay_metric_coins_' + USER_ID;
  const TUTORIAL_KEY = 'eduplay_metric_tut_done_' + USER_ID;
  const SOUND_KEY = 'eduplay_metric_muted_' + USER_ID;

  // Phone / small-screen helper (used for lighter effects + scroll helpers)
  const isSmallScreen = () => window.matchMedia('(max-width: 767.98px)').matches;

  // Query Params & Map Navigation Flags
  const urlParams = new URLSearchParams(window.location.search);
  const paramLevel = parseInt(urlParams.get('level'), 10);
  const paramTutorial = urlParams.get('tutorial') === '1' || urlParams.get('show_instruction') === '1';
  const storageTutorial = localStorage.getItem('mq_show_tutorial') === 'true' || sessionStorage.getItem('mq_show_tutorial') === 'true';

  // Clear transient map triggers
  try {
    localStorage.removeItem('mq_show_tutorial');
    sessionStorage.removeItem('mq_show_tutorial');
  } catch(e) {}

  let initialLevel = CURRENT_LEVEL - 1;
  if (!isNaN(paramLevel) && paramLevel >= 1 && paramLevel <= 50) {
    initialLevel = paramLevel - 1;
  }

  const QUESTIONS_PER_LEVEL = 10;
  const TIME_PER_Q = 30;

  /* ==========================================================================
     FLOATING METRIC SYMBOLS (fewer on phones to save battery / keep it clean)
     ========================================================================== */
  (function initFloatingSigns(){
    const syms = [
      '1 km = 1,000 m', '1 m = 100 cm', '1 cm = 10 mm',
      '1 kg = 1,000 g', '1 g = 1,000 mg', '1 L = 1,000 mL',
      '1 hr = 60 min', '1 min = 60 s', '1 day = 24 hr',
      '1 week = 7 d', '1 yr = 12 mo', '📏', '⚖️', '💧', '⏱️',
      'km', 'm', 'cm', 'mm', 'kg', 'g', 'mg', 'L', 'mL', 'min', 'sec', 'hr'
    ];
    const colors = ['#38bdf8', '#818cf8', '#34d399', '#fbbf24', '#f472b6', '#a78bfa', '#0ea5e9'];
    const layer = document.getElementById('sym-layer');
    if (!layer) return;

    const total = isSmallScreen() ? 24 : 65;
    for (let i = 0; i < total; i++) {
      const s = document.createElement('span');
      s.className = 'sym';
      const t = syms[Math.floor(Math.random() * syms.length)];
      s.textContent = t;
      s.style.left = (Math.random() * 96 + 2) + 'vw';
      s.style.top = (Math.random() * 95) + 'vh';
      const small = isSmallScreen();
      s.style.fontSize = (t.length > 5
        ? (small ? 0.7 + Math.random() * 0.3 : 0.95 + Math.random() * 0.45)
        : (small ? 1 + Math.random() * 0.9 : 1.3 + Math.random() * 1.5)) + 'rem';
      s.style.animationDelay = -(Math.random() * 20) + 's';
      s.style.animationDuration = (12 + Math.random() * 14) + 's';
      s.style.color = colors[Math.floor(Math.random() * colors.length)];
      layer.appendChild(s);
    }
  })();

  /* ==========================================================================
     SYNTHESIZED WEB AUDIO
     ========================================================================== */
  var audioCtx = null;
  var isMuted = localStorage.getItem(SOUND_KEY) === 'true';

  function initAudio() {
    if (!audioCtx) {
      const AudioContext = window.AudioContext || window.webkitAudioContext;
      if (AudioContext) audioCtx = new AudioContext();
    }
    if (audioCtx && audioCtx.state === 'suspended') {
      audioCtx.resume();
    }
  }

  function playTone(freq, duration, type = 'sine', gainVal = 0.15) {
    if (isMuted) return;
    try {
      initAudio();
      if (!audioCtx) return;
      const osc = audioCtx.createOscillator();
      const gain = audioCtx.createGain();
      osc.type = type;
      osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
      gain.gain.setValueAtTime(gainVal, audioCtx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
      osc.connect(gain);
      gain.connect(audioCtx.destination);
      osc.start();
      osc.stop(audioCtx.currentTime + duration);
    } catch(e) {}
  }

  function soundClick() {
    playTone(480, 0.08, 'triangle', 0.12);
  }

  function soundHint() {
    playTone(987, 0.1, 'triangle', 0.2);
    setTimeout(() => playTone(1318, 0.2, 'triangle', 0.2), 100);
  }
  function soundWin() {
    const notes = [523, 659, 783, 1046, 1318];
    notes.forEach((f, idx) => {
      setTimeout(() => playTone(f, 0.22, 'triangle', 0.22), idx * 110);
    });
  }

  function updateSoundUI() {
    const icon = document.getElementById('soundIcon');
    const btn = document.getElementById('btnSoundToggle');
    if (!icon || !btn) return;
    if (isMuted) {
      icon.className = 'bi bi-volume-mute-fill';
      btn.classList.add('muted');
    } else {
      icon.className = 'bi bi-volume-up-fill';
      btn.classList.remove('muted');
    }
  }

  function toggleSound() {
    isMuted = !isMuted;
    localStorage.setItem(SOUND_KEY, isMuted ? 'true' : 'false');
    updateSoundUI();
    if (!isMuted) soundClick();
  }

  /* ==========================================================================
     COINS & PERSISTENCE
     ========================================================================== */
  function getStoredCoins() {
    const val = localStorage.getItem(COINS_KEY);
    if (!val) {
      localStorage.setItem(COINS_KEY, '50');
      return 50;
    }
    return parseInt(val, 10) || 50;
  }

  function saveCoins(newAmount) {
    state.coins = Math.max(0, newAmount);
    localStorage.setItem(COINS_KEY, state.coins.toString());
    updateCoinsUI();
  }

  function updateCoinsUI() {
    const el = document.getElementById('coinDisplay');
    if (el) el.textContent = state.coins;
    const btnHint = document.getElementById('btnHint');
    if (btnHint) {
      if (state.coins < 5) {
        btnHint.disabled = true;
        btnHint.title = 'Need 5 coins for hint';
      } else {
        btnHint.disabled = false;
        btnHint.title = 'Spend 5 coins for a pedagogical hint';
      }
    }
  }

  /* ==========================================================================
     GAME STATE
     ========================================================================== */
  let state = {
    currentLevel: initialLevel, // 0-indexed
    currentQ: 0,
    score: 0,
    coins: getStoredCoins(),
    streak: 0,
    maxStreak: 0,
    questions: [],
    answered: false,
    timer: null,
    timeLeft: TIME_PER_Q,
    hintUsed: false,
    correctCount: 0,
    isPaused: false,
    gameStarted: false
  };

  /* ==========================================================================
     QUESTION GENERATORS (Rich Pedagogical Topics & Ladders)
     ========================================================================== */
  function randInt(min, max) { return Math.floor(Math.random() * (max - min + 1)) + min; }
  function shuffle(arr) {
    const a = [...arr];
    for (let i = a.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
  }

  function buildQ(qText, correct, distractors, hint, realm, ladderInfo) {
    const all = [correct, ...distractors].map(String);
    const unique = [...new Set(all)];
    while (unique.length < 4) {
      const fake = randInt(1, 9999) + ' units';
      if (!unique.includes(fake)) unique.push(fake);
    }
    return {
      question: qText,
      correct: String(correct),
      options: shuffle(unique.slice(0, 4)),
      hint: hint,
      realm: realm,
      ladderInfo: ladderInfo
    };
  }

  // 1. Meter & Centimeter
  function genMeterCm(diff) {
    const isSit = Math.random() < 0.5;
    if (diff === 1) { // m -> cm
      const n = randInt(2, 9);
      let q = isSit ? `A classroom banner is ${n} m long. How long is it in centimeters?` : `Convert ${n} m to centimeters.`;
      return buildQ(q, (n * 100) + ' cm', [(n * 10) + ' cm', (n * 1000) + ' cm', (n + 100) + ' cm'],
        `Rule: 1 m = 100 cm. Since meters is a LARGER unit than centimeters, MULTIPLY by 100: ${n} × 100 = ?`,
        'length', { from: 'm', to: 'cm', text: '1 m = 100 cm (× 100)' }
      );
    } else if (diff === 2) { // cm -> m
      const n = randInt(2, 9);
      let q = isSit ? `A wooden plank is ${n * 100} cm long. How many meters is that?` : `Convert ${n * 100} cm to meters.`;
      return buildQ(q, n + ' m', [(n * 10) + ' m', (n * 100) + ' m', (n + 1) + ' m'],
        `Rule: 100 cm = 1 m. Since centimeters is a SMALLER unit than meters, DIVIDE by 100: ${n * 100} ÷ 100 = ?`,
        'length', { from: 'cm', to: 'm', text: '100 cm = 1 m (÷ 100)' }
      );
    } else { // Mixed
      const m = randInt(1, 5), cm = randInt(5, 95);
      if (Math.random() < 0.5) {
        let q = isSit ? `A flagpole is ${m} m and ${cm} cm tall. What is its total height in centimeters?` : `Convert ${m} m and ${cm} cm to centimeters.`;
        return buildQ(q, (m * 100 + cm) + ' cm', [(m * 10 + cm) + ' cm', (m * 1000 + cm) + ' cm', (m + cm) + ' cm'],
          `Step 1: Convert ${m} m to cm: ${m} × 100 = ${m * 100} cm. Step 2: Add the extra ${cm} cm: ${m * 100} + ${cm} = ?`,
          'length', { from: 'm', to: 'cm', text: 'm & cm ➔ cm' }
        );
      } else {
        let q = isSit ? `A swimming lane is ${m * 100 + cm} cm long. What is this in meters and centimeters?` : `Convert ${m * 100 + cm} cm into meters and centimeters.`;
        return buildQ(q, `${m} m and ${cm} cm`, [`${m} m and ${cm + 10} cm`, `${m * 10} m and ${cm} cm`, `${m + 1} m and ${cm} cm`],
          `Every 100 cm makes 1 meter. Separate the hundreds (${m * 100} cm = ${m} m) from the remainder (${cm} cm).`,
          'length', { from: 'cm', to: 'm', text: 'cm ➔ m & cm' }
        );
      }
    }
  }

  // 2. Kilometer & Meter
  function genKm(diff) {
    const isSit = Math.random() < 0.5;
    if (diff === 1) {
      const n = randInt(2, 9);
      let q = isSit ? `An athlete ran ${n} km across the park. How many meters is that?` : `Convert ${n} km to meters.`;
      return buildQ(q, (n * 1000) + ' m', [(n * 100) + ' m', (n * 10) + ' m', (n + 1000) + ' m'],
        `Rule: 1 km = 1,000 m. Kilo means thousand. MULTIPLY by 1,000: ${n} × 1,000 = ?`,
        'length', { from: 'km', to: 'm', text: '1 km = 1,000 m (× 1,000)' }
      );
    } else if (diff === 2) {
      const n = randInt(2, 9);
      let q = isSit ? `A drone flew ${n * 1000} m into the sky. How many kilometers is that?` : `Convert ${n * 1000} m to kilometers.`;
      return buildQ(q, n + ' km', [(n * 10) + ' km', (n * 100) + ' km', (n + 1) + ' km'],
        `Rule: 1,000 m = 1 km. Converting meters to kilometers means DIVIDING by 1,000: ${n * 1000} ÷ 1,000 = ?`,
        'length', { from: 'm', to: 'km', text: '1,000 m = 1 km (÷ 1,000)' }
      );
    } else {
      const km = randInt(1, 5), m = randInt(50, 950);
      let q = isSit ? `A hiking trail is ${km} km and ${m} m long. What is the total distance in meters?` : `Convert ${km} km and ${m} m to meters.`;
      return buildQ(q, (km * 1000 + m) + ' m', [(km * 100 + m) + ' m', (km * 10 + m) + ' m', (m) + ' m'],
        `Step 1: Convert ${km} km to meters: ${km} × 1,000 = ${km * 1000} m. Step 2: Add ${m} m: ${km * 1000} + ${m} = ?`,
        'length', { from: 'km', to: 'm', text: 'km & m ➔ m' }
      );
    }
  }

  // 3. Kilogram & Gram
  function genKg(diff) {
    const isSit = Math.random() < 0.5;
    if (diff === 1) {
      const n = randInt(2, 9);
      let q = isSit ? `A bag of flour weighs ${n} kg. What is its weight in grams?` : `Convert ${n} kg to grams.`;
      return buildQ(q, (n * 1000) + ' g', [(n * 100) + ' g', (n * 10) + ' g', (n + 1000) + ' g'],
        `Rule: 1 kg = 1,000 g. Kilogram is larger than gram, so MULTIPLY by 1,000: ${n} × 1,000 = ?`,
        'mass', { from: 'kg', to: 'g', text: '1 kg = 1,000 g (× 1,000)' }
      );
    } else if (diff === 2) {
      const n = randInt(2, 9);
      let q = isSit ? `A cat weighs ${n * 1000} g. How many kilograms is that?` : `Convert ${n * 1000} g to kilograms.`;
      return buildQ(q, n + ' kg', [(n * 10) + ' kg', (n * 100) + ' kg', (n + 1) + ' kg'],
        `Rule: 1,000 g = 1 kg. Converting grams to kilograms means DIVIDING by 1,000: ${n * 1000} ÷ 1,000 = ?`,
        'mass', { from: 'g', to: 'kg', text: '1,000 g = 1 kg (÷ 1,000)' }
      );
    } else {
      const kg = randInt(1, 5), g = randInt(50, 950);
      let q = isSit ? `A postal parcel weighs ${kg} kg and ${g} g. What is its total weight in grams?` : `Convert ${kg} kg and ${g} g to grams.`;
      return buildQ(q, (kg * 1000 + g) + ' g', [(kg * 100 + g) + ' g', (kg * 10 + g) + ' g', (g) + ' g'],
        `First convert ${kg} kg to grams: ${kg * 1000} g. Then add ${g} g: ${kg * 1000} + ${g} = ?`,
        'mass', { from: 'kg', to: 'g', text: 'kg & g ➔ g' }
      );
    }
  }

  // 4. Gram & Milligram
  function genMg(diff) {
    const isSit = Math.random() < 0.5;
    if (diff === 1) {
      const n = randInt(2, 9);
      let q = isSit ? `A coin contains ${n} g of silver. How many milligrams is that?` : `Convert ${n} g to milligrams.`;
      return buildQ(q, (n * 1000) + ' mg', [(n * 100) + ' mg', (n * 10) + ' mg', (n + 1000) + ' mg'],
        `Rule: 1 g = 1,000 mg. MULTIPLY by 1,000 to convert grams to milligrams: ${n} × 1,000 = ?`,
        'mass', { from: 'g', to: 'mg', text: '1 g = 1,000 mg (× 1,000)' }
      );
    } else if (diff === 2) {
      const n = randInt(2, 9);
      let q = isSit ? `A vitamin tablet contains ${n * 1000} mg of nutrients. How many grams is that?` : `Convert ${n * 1000} mg to grams.`;
      return buildQ(q, n + ' g', [(n * 10) + ' g', (n * 100) + ' g', (n + 1) + ' g'],
        `Rule: 1,000 mg = 1 g. DIVIDE by 1,000 to convert milligrams to grams: ${n * 1000} ÷ 1,000 = ?`,
        'mass', { from: 'mg', to: 'g', text: '1,000 mg = 1 g (÷ 1,000)' }
      );
    } else {
      const g = randInt(1, 4), mg = randInt(50, 950);
      let q = isSit ? `A chemistry sample weighs ${g} g and ${mg} mg. What is its total weight in milligrams?` : `Convert ${g} g and ${mg} mg to milligrams.`;
      return buildQ(q, (g * 1000 + mg) + ' mg', [(g * 100 + mg) + ' mg', (g * 10 + mg) + ' mg', (mg) + ' mg'],
        `Convert ${g} g to mg (${g * 1000} mg) and add ${mg} mg: ${g * 1000} + ${mg} = ?`,
        'mass', { from: 'g', to: 'mg', text: 'g & mg ➔ mg' }
      );
    }
  }

  // 5. Liter & Milliliter
  function genLiter(diff) {
    const isSit = Math.random() < 0.5;
    if (diff === 1) {
      const n = randInt(2, 9);
      let q = isSit ? `A water cooler jug holds ${n} L. How many milliliters is that?` : `Convert ${n} L to milliliters.`;
      return buildQ(q, (n * 1000) + ' mL', [(n * 100) + ' mL', (n * 10) + ' mL', (n + 1000) + ' mL'],
        `Rule: 1 L = 1,000 mL. Liter is bigger than milliliter, so MULTIPLY by 1,000: ${n} × 1,000 = ?`,
        'capacity', { from: 'L', to: 'mL', text: '1 L = 1,000 mL (× 1,000)' }
      );
    } else if (diff === 2) {
      const n = randInt(2, 9);
      let q = isSit ? `A carton contains ${n * 1000} mL of orange juice. How many liters is that?` : `Convert ${n * 1000} mL to liters.`;
      return buildQ(q, n + ' L', [(n * 10) + ' L', (n * 100) + ' L', (n + 1) + ' L'],
        `Rule: 1,000 mL = 1 L. DIVIDE by 1,000 to convert milliliters to liters: ${n * 1000} ÷ 1,000 = ?`,
        'capacity', { from: 'mL', to: 'L', text: '1,000 mL = 1 L (÷ 1,000)' }
      );
    } else {
      const l = randInt(1, 4), ml = randInt(50, 950);
      let q = isSit ? `A recipe calls for ${l} L and ${ml} mL of soup broth. What is this volume in milliliters?` : `Convert ${l} L and ${ml} mL to milliliters.`;
      return buildQ(q, (l * 1000 + ml) + ' mL', [(l * 100 + ml) + ' mL', (l * 10 + ml) + ' mL', (ml) + ' mL'],
        `Multiply ${l} L by 1,000 (${l * 1000} mL), then add ${ml} mL: ${l * 1000} + ${ml} = ?`,
        'capacity', { from: 'L', to: 'mL', text: 'L & mL ➔ mL' }
      );
    }
  }

  // 6. Seconds & Minutes
  function genSec(diff) {
    const isSit = Math.random() < 0.5;
    if (diff === 1) {
      const n = randInt(2, 6);
      let q = isSit ? `A short animated clip lasts ${n} minutes. How many seconds is that?` : `Convert ${n} minutes to seconds.`;
      return buildQ(q, (n * 60) + ' s', [(n * 100) + ' s', (n * 10) + ' s', (n * 60 + 60) + ' s'],
        `Rule: 1 minute = 60 seconds. MULTIPLY by 60: ${n} × 60 = ?`,
        'time', { from: 'min', to: 's', text: '1 min = 60 s (× 60)' }
      );
    } else if (diff === 2) {
      const n = randInt(2, 6);
      let q = isSit ? `A sprint runner trained for ${n * 60} seconds. How many minutes is that?` : `Convert ${n * 60} seconds to minutes.`;
      return buildQ(q, n + ' min', [(n * 10) + ' min', (n * 100) + ' min', (n + 1) + ' min'],
        `Rule: 60 seconds = 1 minute. DIVIDE by 60: ${n * 60} ÷ 60 = ?`,
        'time', { from: 's', to: 'min', text: '60 s = 1 min (÷ 60)' }
      );
    } else {
      const min = randInt(1, 3), s = randInt(10, 50);
      let q = isSit ? `A speech took ${min} min and ${s} s. How many total seconds was that?` : `Convert ${min} min and ${s} s to seconds.`;
      return buildQ(q, (min * 60 + s) + ' s', [(min * 100 + s) + ' s', (min * 10 + s) + ' s', (s) + ' s'],
        `Multiply ${min} min × 60 = ${min * 60} seconds. Then add ${s} seconds: ${min * 60} + ${s} = ?`,
        'time', { from: 'min', to: 's', text: 'min & s ➔ s' }
      );
    }
  }

  // 7. Minutes & Hours
  function genMin(diff) {
    const isSit = Math.random() < 0.5;
    if (diff === 1) {
      const n = randInt(2, 5);
      let q = isSit ? `A documentary film is ${n} hours long. How many minutes is the film?` : `Convert ${n} hours to minutes.`;
      return buildQ(q, (n * 60) + ' min', [(n * 100) + ' min', (n * 10) + ' min', (n * 60 + 60) + ' min'],
        `Rule: 1 hour = 60 minutes. MULTIPLY by 60: ${n} × 60 = ?`,
        'time', { from: 'hr', to: 'min', text: '1 hr = 60 min (× 60)' }
      );
    } else if (diff === 2) {
      const n = randInt(2, 5);
      let q = isSit ? `A train journey took ${n * 60} minutes. How many hours did it take?` : `Convert ${n * 60} minutes to hours.`;
      return buildQ(q, n + ' hr', [(n * 10) + ' hr', (n * 100) + ' hr', (n + 1) + ' hr'],
        `Rule: 60 minutes = 1 hour. DIVIDE by 60: ${n * 60} ÷ 60 = ?`,
        'time', { from: 'min', to: 'hr', text: '60 min = 1 hr (÷ 60)' }
      );
    } else {
      const hr = randInt(1, 3), min = randInt(10, 50);
      let q = isSit ? `A concert lasted ${hr} hr and ${min} min. What was the total time in minutes?` : `Convert ${hr} hr and ${min} min to minutes.`;
      return buildQ(q, (hr * 60 + min) + ' min', [(hr * 100 + min) + ' min', (hr * 10 + min) + ' min', (min) + ' min'],
        `Calculate ${hr} hr × 60 = ${hr * 60} minutes. Then add ${min} minutes: ${hr * 60} + ${min} = ?`,
        'time', { from: 'hr', to: 'min', text: 'hr & min ➔ min' }
      );
    }
  }

  // 8. Hours & Days
  function genHr(diff) {
    const isSit = Math.random() < 0.5;
    if (diff === 1) {
      const n = randInt(2, 5);
      let q = isSit ? `A weekend festival lasts ${n} days. How many hours is the festival?` : `Convert ${n} days to hours.`;
      return buildQ(q, (n * 24) + ' hr', [(n * 12) + ' hr', (n * 10) + ' hr', (n * 24 + 24) + ' hr'],
        `Rule: 1 day = 24 hours. MULTIPLY by 24: ${n} × 24 = ?`,
        'time', { from: 'days', to: 'hr', text: '1 day = 24 hr (× 24)' }
      );
    } else if (diff === 2) {
      const n = randInt(2, 5);
      let q = isSit ? `A science experiment took ${n * 24} hours to incubate. How many days is that?` : `Convert ${n * 24} hours to days.`;
      return buildQ(q, n + ' days', [(n * 12) + ' days', (n * 10) + ' days', (n + 1) + ' days'],
        `Rule: 24 hours = 1 day. DIVIDE by 24: ${n * 24} ÷ 24 = ?`,
        'time', { from: 'hr', to: 'days', text: '24 hr = 1 day (÷ 24)' }
      );
    } else {
      const d = randInt(1, 2), hr = randInt(4, 18);
      let q = isSit ? `A ferry voyage took ${d} days and ${hr} hours. What is the total travel time in hours?` : `Convert ${d} days and ${hr} hours to hours.`;
      return buildQ(q, (d * 24 + hr) + ' hr', [(d * 12 + hr) + ' hr', (d * 10 + hr) + ' hr', (hr) + ' hr'],
        `First convert ${d} days to hours: ${d} × 24 = ${d * 24} hr. Then add ${hr} hr: ${d * 24} + ${hr} = ?`,
        'time', { from: 'days', to: 'hr', text: 'days & hr ➔ hr' }
      );
    }
  }

  // 9. Days & Weeks
  function genDays(diff) {
    const isSit = Math.random() < 0.5;
    if (diff === 1) {
      const n = randInt(2, 6);
      let q = isSit ? `A summer music camp lasts ${n} weeks. How many days does the camp run?` : `Convert ${n} weeks to days.`;
      return buildQ(q, (n * 7) + ' days', [(n * 5) + ' days', (n * 10) + ' days', (n * 7 + 7) + ' days'],
        `Rule: 1 week = 7 days. MULTIPLY by 7: ${n} × 7 = ?`,
        'time', { from: 'wks', to: 'days', text: '1 week = 7 days (× 7)' }
      );
    } else if (diff === 2) {
      const n = randInt(2, 6);
      let q = isSit ? `A lunar research cycle completed in ${n * 7} days. How many weeks is that?` : `Convert ${n * 7} days to weeks.`;
      return buildQ(q, n + ' weeks', [(n * 5) + ' weeks', (n * 10) + ' weeks', (n + 1) + ' weeks'],
        `Rule: 7 days = 1 week. DIVIDE by 7: ${n * 7} ÷ 7 = ?`,
        'time', { from: 'days', to: 'wks', text: '7 days = 1 week (÷ 7)' }
      );
    } else {
      const w = randInt(1, 3), d = randInt(1, 6);
      let q = isSit ? `A space shuttle quarantine lasts ${w} weeks and ${d} days. How many total days is that?` : `Convert ${w} weeks and ${d} days to days.`;
      return buildQ(q, (w * 7 + d) + ' days', [(w * 5 + d) + ' days', (w * 10 + d) + ' days', (d) + ' days'],
        `Convert ${w} weeks to days: ${w} × 7 = ${w * 7} days. Then add the extra ${d} days: ${w * 7} + ${d} = ?`,
        'time', { from: 'wks', to: 'days', text: 'wks & days ➔ days' }
      );
    }
  }

  // 10. Weeks & Months
  function genWeeks(diff) {
    const isSit = Math.random() < 0.5;
    if (diff === 1) {
      const n = randInt(2, 6);
      let q = isSit ? `A training course runs for ${n} months (using 1 month = 4 weeks). How many weeks is that?` : `Convert ${n} months to weeks (1 month = 4 weeks).`;
      return buildQ(q, (n * 4) + ' weeks', [(n * 5) + ' weeks', (n * 7) + ' weeks', (n * 4 + 4) + ' weeks'],
        `Rule: 1 month = 4 weeks for these challenges. MULTIPLY by 4: ${n} × 4 = ?`,
        'time', { from: 'mo', to: 'wks', text: '1 month = 4 weeks (× 4)' }
      );
    } else if (diff === 2) {
      const n = randInt(2, 6);
      let q = isSit ? `A puppy is ${n * 4} weeks old. How many months is that? (1 month = 4 weeks)` : `Convert ${n * 4} weeks to months (1 month = 4 weeks).`;
      return buildQ(q, n + ' months', [(n * 5) + ' months', (n * 7) + ' months', (n + 1) + ' months'],
        `Rule: 4 weeks = 1 month. DIVIDE by 4: ${n * 4} ÷ 4 = ?`,
        'time', { from: 'wks', to: 'mo', text: '4 weeks = 1 month (÷ 4)' }
      );
    } else {
      const mo = randInt(1, 3), w = randInt(1, 3);
      let q = isSit ? `An expedition takes ${mo} months and ${w} weeks. How many total weeks is that? (1 mo = 4 wks)` : `Convert ${mo} months and ${w} weeks to weeks.`;
      return buildQ(q, (mo * 4 + w) + ' weeks', [(mo * 5 + w) + ' weeks', (mo * 7 + w) + ' weeks', (w) + ' weeks'],
        `Multiply ${mo} months × 4 = ${mo * 4} weeks. Then add ${w} weeks: ${mo * 4} + ${w} = ?`,
        'time', { from: 'mo', to: 'wks', text: 'mo & wks ➔ wks' }
      );
    }
  }

  // 11. Months & Years
  function genMonths(diff) {
    const isSit = Math.random() < 0.5;
    if (diff === 1) {
      const n = randInt(2, 5);
      let q = isSit ? `A young tiger has lived for ${n} years. How many months is that?` : `Convert ${n} years to months.`;
      return buildQ(q, (n * 12) + ' months', [(n * 10) + ' months', (n * 24) + ' months', (n * 12 + 12) + ' months'],
        `Rule: 1 year = 12 months. MULTIPLY by 12: ${n} × 12 = ?`,
        'time', { from: 'yr', to: 'mo', text: '1 yr = 12 mo (× 12)' }
      );
    } else if (diff === 2) {
      const n = randInt(2, 5);
      let q = isSit ? `A spacecraft orbited Mars for ${n * 12} months. How many years is that?` : `Convert ${n * 12} months to years.`;
      return buildQ(q, n + ' years', [(n * 10) + ' years', (n * 24) + ' years', (n + 1) + ' years'],
        `Rule: 12 months = 1 year. DIVIDE by 12: ${n * 12} ÷ 12 = ?`,
        'time', { from: 'mo', to: 'yr', text: '12 mo = 1 yr (÷ 12)' }
      );
    } else {
      const y = randInt(1, 3), mo = randInt(1, 11);
      let q = isSit ? `A history museum artifact was restored over ${y} years and ${mo} months. How many months is that in total?` : `Convert ${y} years and ${mo} months to months.`;
      return buildQ(q, (y * 12 + mo) + ' months', [(y * 10 + mo) + ' months', (y * 24 + mo) + ' months', (mo) + ' months'],
        `Multiply ${y} years × 12 = ${y * 12} months. Then add ${mo} months: ${y * 12} + ${mo} = ?`,
        'time', { from: 'yr', to: 'mo', text: 'yr & mo ➔ mo' }
      );
    }
  }

  const TOPIC_FNS = [
    genMeterCm, genKm, genKg, genMg, genLiter,
    genSec, genMin, genHr, genDays, genWeeks, genMonths
  ];

  const REALM_CONFIG = {
    length: { name: 'Length Realm', icon: '📏', color: '#38bdf8' },
    mass: { name: 'Mass Realm', icon: '⚖️', color: '#10b981' },
    capacity: { name: 'Capacity Realm', icon: '💧', color: '#6366f1' },
    time: { name: 'Chrono Time Realm', icon: '⏰', color: '#f59e0b' }
  };

  /* ==========================================================================
     LEVEL CONFIG (50 Expedition Levels)
     ========================================================================== */
  const LEVEL_DATA = [];
  for (let i = 0; i < 33; i++) {
    const topicIdx = Math.floor(i / 3);
    const diff = (i % 3) + 1;
    let name = "Conversion Challenge";
    let icon = "📏";
    let realm = "length";

    if (topicIdx === 0) { name = "Meter & Centimeter"; icon = "📏"; realm = "length"; }
    else if (topicIdx === 1) { name = "Kilometer & Meter"; icon = "🏔️"; realm = "length"; }
    else if (topicIdx === 2) { name = "Kilogram & Gram"; icon = "⚖️"; realm = "mass"; }
    else if (topicIdx === 3) { name = "Gram & Milligram"; icon = "🧪"; realm = "mass"; }
    else if (topicIdx === 4) { name = "Liter & Milliliter"; icon = "💧"; realm = "capacity"; }
    else if (topicIdx === 5) { name = "Seconds & Minutes"; icon = "⏱️"; realm = "time"; }
    else if (topicIdx === 6) { name = "Minutes & Hours"; icon = "🕐"; realm = "time"; }
    else if (topicIdx === 7) { name = "Hours & Days"; icon = "🌅"; realm = "time"; }
    else if (topicIdx === 8) { name = "Days & Weeks"; icon = "📅"; realm = "time"; }
    else if (topicIdx === 9) { name = "Weeks & Months"; icon = "🗓️"; realm = "time"; }
    else if (topicIdx === 10) { name = "Months & Years"; icon = "🌍"; realm = "time"; }

    LEVEL_DATA.push({
      topicIdx: topicIdx,
      diff: diff,
      name: name + (diff === 1 ? " (Easy)" : diff === 2 ? " (Medium)" : " (Hard)"),
      icon: icon,
      realm: realm
    });
  }

  // Levels 34-50: Grand Mixed Time-Traveler Challenges
  for (let i = 33; i < 50; i++) {
    LEVEL_DATA.push({
      topicIdx: -1,
      diff: 3,
      name: "Grand Expedition " + (i - 32),
      icon: "👑",
      realm: "mixed"
    });
  }

  /* ==========================================================================
     MOBILE HELPERS
     ========================================================================== */
  // On phones the feedback box sits below the answers; keep it visible.
  function scrollFeedbackIntoView() {
    if (!isSmallScreen()) return;
    const fb = document.getElementById('feedbackBox');
    if (fb) fb.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }
  // On phones, bring the new question back into view after each answer.
  function scrollQuestionIntoView() {
    if (!isSmallScreen()) return;
    const st = document.getElementById('challengeStation');
    if (st) {
      const top = st.getBoundingClientRect().top + window.pageYOffset - 8;
      if (Math.abs(window.pageYOffset - top) > 40) window.scrollTo({ top: top, behavior: 'smooth' });
    }
  }

  /* ==========================================================================
     CORE GAME LOOP
     ========================================================================== */
  function startStage(idx, showInstruction = false) {
    if (idx >= 50) idx = 49;
    state.currentLevel = idx;
    state.currentQ = 0;
    state.score = 0;
    state.correctCount = 0;
    state.streak = 0;
    state.maxStreak = 0;
    state.questions = [];
    state.isPaused = false;

    const levelInfo = LEVEL_DATA[idx];

    // Generate 10 questions with spaced repetition
    for (let i = 0; i < QUESTIONS_PER_LEVEL; i++) {
      let q;
      let attempts = 0;
      do {
        if (levelInfo.topicIdx === -1) {
          const rTopic = randInt(0, 10);
          const rDiff = randInt(1, 3);
          q = TOPIC_FNS[rTopic](rDiff);
        } else {
          // 25% chance of spaced repetition from an earlier topic if past topic 0
          if (levelInfo.topicIdx > 0 && Math.random() < 0.25) {
            const oldTopic = randInt(0, levelInfo.topicIdx - 1);
            const rDiff = randInt(1, 3);
            q = TOPIC_FNS[oldTopic](rDiff);
          } else {
            q = TOPIC_FNS[levelInfo.topicIdx](levelInfo.diff);
          }
        }
        attempts++;
      } while (state.questions.some(item => item.question === q.question) && attempts < 20);

      state.questions.push(q);
    }

    // Update Masthead and Badge
    document.getElementById('levelStageBadge').innerHTML = `<i class="bi bi-flag-fill"></i> Stage ${idx + 1}`;
    updateStatsUI();

    if (showInstruction) {
      state.gameStarted = false;
      openTutorial();
    } else {
      state.gameStarted = true;
    }

    loadQuestion();
  }

  function loadQuestion() {
    if (state.currentQ >= state.questions.length) {
      finishStage();
      return;
    }

    state.answered = false;
    state.hintUsed = false;
    const q = state.questions[state.currentQ];

    // Update Progress Chips
    document.getElementById('qNumBadge').textContent = `Question ${state.currentQ + 1} / ${state.questions.length}`;

    // Category and Realm Chip
    const catChip = document.getElementById('categoryChip');
    const realmInfo = REALM_CONFIG[q.realm] || { name: 'Master Quest', icon: '👑', color: '#fbbf24' };
    catChip.innerHTML = `${realmInfo.icon} ${realmInfo.name}`;
    catChip.style.borderColor = realmInfo.color;
    catChip.style.color = realmInfo.color;

    // Update Metric Ladder Banner
    renderLadderBanner(q);

    // Update Question text
    document.getElementById('questionText').textContent = q.question;

    // Reset Hint and Feedback
    const hintBox = document.getElementById('hintBox');
    hintBox.className = 'hint-reveal-box';
    hintBox.innerHTML = '';

    const fb = document.getElementById('feedbackBox');
    fb.className = 'feedback-box';
    fb.innerHTML = '';

    // Reset hint button (it was left disabled/changed after a previous hint)
    const btnHint = document.getElementById('btnHint');
    if (btnHint) {
      btnHint.innerHTML = '<span>🪙</span> <span>Hint (5 🪙)</span>';
    }
    updateCoinsUI();

    // Reset Progress Bar
    const pct = (state.currentQ / state.questions.length) * 100;
    document.getElementById('progressBar').style.width = pct + '%';
    document.getElementById('sidebarProgressFill').style.width = pct + '%';

    // Populate Answer Cards
    const grid = document.getElementById('answersGrid');
    grid.innerHTML = '';
    const keyLabels = ['1', '2', '3', '4'];
    const letterLabels = ['A', 'B', 'C', 'D'];

    q.options.forEach((opt, idx) => {
      const card = document.createElement('button');
      card.type = 'button';
      card.className = 'answer-card';
      card.setAttribute('data-answer', opt);
      card.setAttribute('data-key', keyLabels[idx]);

      card.innerHTML = `
        <div style="display:flex; align-items:center; min-width:0;">
          <span class="ans-badge">${letterLabels[idx]}</span>
          <span class="ans-val">${opt}</span>
        </div>
        <span class="ans-key-hint">[${keyLabels[idx]}]</span>
      `;

      card.onclick = () => selectAnswer(opt, card);
      grid.appendChild(card);
    });

    scrollQuestionIntoView();

    const tutOverlay = document.getElementById('tutOverlay');
    const isTutOpen = tutOverlay && tutOverlay.classList.contains('active');
    if (state.gameStarted && !state.isPaused && !isTutOpen) {
      startTimer();
    }
  }

  /* Render the Dynamic Metric Ladder Banner */
  function renderLadderBanner(q) {
    const realmNameEl = document.getElementById('ladderRealmName');
    const stepsEl = document.getElementById('ladderSteps');
    const tipEl = document.getElementById('ladderTipText');

    if (q.realm === 'length') {
      realmNameEl.textContent = 'Length Ladder:';
      stepsEl.innerHTML = `
        <span class="ladder-node ${q.ladderInfo.from === 'km' || q.ladderInfo.to === 'km' ? 'active-unit' : ''}">km</span>
        <span class="ladder-op">× 1,000</span>
        <span class="ladder-node ${q.ladderInfo.from === 'm' || q.ladderInfo.to === 'm' ? 'active-unit' : ''}">m</span>
        <span class="ladder-op">× 100</span>
        <span class="ladder-node ${q.ladderInfo.from === 'cm' || q.ladderInfo.to === 'cm' ? 'active-unit' : ''}">cm</span>
        <span class="ladder-op">× 10</span>
        <span class="ladder-node">mm</span>
      `;
      tipEl.textContent = 'km ➔ m (× 1,000) • m ➔ cm (× 100) • cm ➔ mm (× 10)';
    } else if (q.realm === 'mass') {
      realmNameEl.textContent = 'Mass Ladder:';
      stepsEl.innerHTML = `
        <span class="ladder-node ${q.ladderInfo.from === 'kg' || q.ladderInfo.to === 'kg' ? 'active-unit' : ''}">kg (Kilogram)</span>
        <span class="ladder-op">× 1,000</span>
        <span class="ladder-node ${q.ladderInfo.from === 'g' || q.ladderInfo.to === 'g' ? 'active-unit' : ''}">g (Gram)</span>
        <span class="ladder-op">× 1,000</span>
        <span class="ladder-node ${q.ladderInfo.from === 'mg' || q.ladderInfo.to === 'mg' ? 'active-unit' : ''}">mg (Milligram)</span>
      `;
      tipEl.textContent = 'kg ➔ g (× 1,000) • g ➔ mg (× 1,000)';
    } else if (q.realm === 'capacity') {
      realmNameEl.textContent = 'Capacity Ladder:';
      stepsEl.innerHTML = `
        <span class="ladder-node ${q.ladderInfo.from === 'L' || q.ladderInfo.to === 'L' ? 'active-unit' : ''}">L (Liter)</span>
        <span class="ladder-op">× 1,000</span>
        <span class="ladder-node ${q.ladderInfo.from === 'mL' || q.ladderInfo.to === 'mL' ? 'active-unit' : ''}">mL (Milliliter)</span>
      `;
      tipEl.textContent = '1 Liter = 1,000 Milliliters (Multiply by 1,000)';
    } else {
      realmNameEl.textContent = 'Chrono Timeline:';
      stepsEl.innerHTML = `
        <span class="ladder-node">Year</span>
        <span class="ladder-op">× 12</span>
        <span class="ladder-node">Month</span>
        <span class="ladder-op">× 4</span>
        <span class="ladder-node">Week</span>
        <span class="ladder-op">× 7</span>
        <span class="ladder-node">Day</span>
        <span class="ladder-op">× 24</span>
        <span class="ladder-node">Hr</span>
        <span class="ladder-op">× 60</span>
        <span class="ladder-node">Min</span>
        <span class="ladder-op">× 60</span>
        <span class="ladder-node">Sec</span>
      `;
      tipEl.textContent = '1 hr = 60 min • 1 min = 60 s • 1 day = 24 hr • 1 week = 7 days';
    }
  }

  /* Timer */
  function startTimer() {
    clearInterval(state.timer);
    const tutOverlay = document.getElementById('tutOverlay');
    if ((tutOverlay && tutOverlay.classList.contains('active')) || !state.gameStarted || state.isPaused) {
      return;
    }
    state.timeLeft = TIME_PER_Q;
    updateTimerUI();

    state.timer = setInterval(() => {
      if (state.isPaused || !state.gameStarted) return;
      state.timeLeft--;
      updateTimerUI();

      if (state.timeLeft <= 0) {
        clearInterval(state.timer);
        if (!state.answered) onTimeOut();
      }
    }, 1000);
  }

  function updateTimerUI() {
    const textEl = document.getElementById('timerText');
    const pill = document.getElementById('timerPill');
    if (!textEl || !pill) return;

    textEl.textContent = state.timeLeft + 's';
    if (state.timeLeft <= 8) {
      pill.classList.add('urgent');
    } else {
      pill.classList.remove('urgent');
    }
  }

  function onTimeOut() {
    state.answered = true;
    state.streak = 0;
    updateStatsUI();
    window.playWrongSound();

    const q = state.questions[state.currentQ];
    document.querySelectorAll('.answer-card').forEach(card => {
      card.disabled = true;
      if (card.getAttribute('data-answer') === q.correct) {
        card.classList.add('correct');
      }
    });

    const fb = document.getElementById('feedbackBox');
    fb.className = 'feedback-box wrong';
    fb.innerHTML = `<i class="bi bi-alarm"></i> <span>Time's up! The correct conversion is <b>${q.correct}</b>.</span>`;
    scrollFeedbackIntoView();

    advanceToNextQuestion(2400);
  }

  function selectAnswer(selected, cardEl) {
    if (state.answered || state.isPaused) return;
    state.answered = true;
    clearInterval(state.timer);

    const q = state.questions[state.currentQ];
    document.querySelectorAll('.answer-card').forEach(b => b.disabled = true);
    const fb = document.getElementById('feedbackBox');

    if (selected === q.correct) {
      cardEl.classList.add('correct');
      
      // Play correct sound
      if (window.playCorrectSound) window.playCorrectSound();
      
      state.correctCount++;
      state.streak++;
      if (state.streak > state.maxStreak) state.maxStreak = state.streak;

      const timeBonus = Math.floor(state.timeLeft / 3);
      const streakBonus = Math.min(state.streak, 5) * 2;
      const points = 10 + timeBonus + streakBonus;
      state.score += points;

      fb.className = 'feedback-box correct';
      fb.innerHTML = `
        <i class="bi bi-check-circle-fill"></i>
        <span><b>Correct!</b> +${points} pts ${state.streak >= 2 ? `(🔥 ${state.streak}x Streak!)` : ''}</span>
      `;

      if (state.streak >= 3) spawnConfetti(isSmallScreen() ? 10 : 18);
      
      // Show success feedback with ruler icon and Continue button
      if (window.showGameFeedback) {
        const nextAction = () => {
          state.currentQ++;
          if (state.currentQ < state.questions.length) {
            loadQuestion();
          } else {
            finishStage();
          }
        };
        window.showGameFeedback(true, q.correct, 2000, nextAction, null, '📏');
      }
      
      // Fallback if showGameFeedback doesn't exist
      if (!window.showGameFeedback) {
        setTimeout(() => {
          state.currentQ++;
          if (state.currentQ < state.questions.length) {
            loadQuestion();
          } else {
            finishStage();
          }
        }, 2000);
      }
    } else {
      cardEl.classList.add('wrong');
      
      // Play wrong sound
      try {
        if (window.playWrongSound) window.playWrongSound();
      } catch(e) {
        console.error('Error playing wrong sound:', e);
      }
      
      state.streak = 0;

      // Show wrong answers
      document.querySelectorAll('.answer-card').forEach(b => {
        if (b.getAttribute('data-answer') === q.correct) {
          b.classList.add('correct');
        }
      });

      fb.className = 'feedback-box wrong';
      fb.innerHTML = `
        <i class="bi bi-x-circle-fill"></i>
        <span><b>Not quite!</b> The correct conversion is <b>${q.correct}</b>.</span>
      `;
      
      // Build conversion explanation
      const conversionExplanation = buildConversionExplanation(q);
      
      // Show feedback modal with Continue button
      if (window.showGameFeedback) {
        const nextAction = () => {
          state.currentQ++;
          if (state.currentQ < state.questions.length) {
            loadQuestion();
          } else {
            finishStage();
          }
        };
        window.showGameFeedback(false, q.correct, 3000, nextAction, conversionExplanation, '📏');
      }
      
      // Fallback if showGameFeedback doesn't exist
      if (!window.showGameFeedback) {
        setTimeout(() => {
          state.currentQ++;
          if (state.currentQ < state.questions.length) {
            loadQuestion();
          } else {
            finishStage();
          }
        }, 3000);
      }
    }

    updateStatsUI();
    scrollFeedbackIntoView();
  }
  
  function buildConversionExplanation(q) {
    // Safety check
    if (!q || (!q.question && !q.text)) {
      return `<div style="margin-top:8px;"><div>The correct answer is: <b>${q?.correct || 'N/A'}</b></div></div>`;
    }
    
    // Extract conversion info from question (use question property, fallback to text)
    const questionText = q.question || q.text || '';
    let explanation = `<div style="margin-top:8px;">`;
    
    // Try to extract the number being converted
    const numberMatch = questionText.match(/(\d+(?:\.\d+)?)\s*(cm|m|km|g|kg|L|mL|ml)/i);
    const fromValue = numberMatch ? parseFloat(numberMatch[1]) : null;
    const fromUnit = numberMatch ? numberMatch[2].toLowerCase() : null;
    
    // Common conversion rules with step-by-step computation
    if (questionText.includes('centimeters') && questionText.includes('meters')) {
      explanation += `<div><b>Remember:</b> 1 meter = 100 centimeters</div>`;
      if (questionText.toLowerCase().includes('cm to m') || questionText.toLowerCase().includes('centimeters to meters')) {
        explanation += `<div><b>To convert cm to m:</b> divide by 100</div>`;
        if (fromValue) {
          explanation += `<div style="margin-top:8px;"><b>Computation:</b></div>`;
          explanation += `<div>${fromValue} cm ÷ 100 = ${fromValue / 100} m</div>`;
          explanation += `<div>✓ Answer: <b>${q.correct}</b></div>`;
        }
      } else {
        explanation += `<div><b>To convert m to cm:</b> multiply by 100</div>`;
        if (fromValue) {
          explanation += `<div style="margin-top:8px;"><b>Computation:</b></div>`;
          explanation += `<div>${fromValue} m × 100 = ${fromValue * 100} cm</div>`;
          explanation += `<div>✓ Answer: <b>${q.correct}</b></div>`;
        }
      }
    } else if (questionText.includes('meters') && questionText.includes('kilometers')) {
      explanation += `<div><b>Remember:</b> 1 kilometer = 1,000 meters</div>`;
      if (questionText.toLowerCase().includes('m to km') || questionText.toLowerCase().includes('meters to k')) {
        explanation += `<div><b>To convert m to km:</b> divide by 1,000</div>`;
        if (fromValue) {
          explanation += `<div style="margin-top:8px;"><b>Computation:</b></div>`;
          explanation += `<div>${fromValue} m ÷ 1,000 = ${fromValue / 1000} km</div>`;
          explanation += `<div>✓ Answer: <b>${q.correct}</b></div>`;
        }
      } else {
        explanation += `<div><b>To convert km to m:</b> multiply by 1,000</div>`;
        if (fromValue) {
          explanation += `<div style="margin-top:8px;"><b>Computation:</b></div>`;
          explanation += `<div>${fromValue} km × 1,000 = ${fromValue * 1000} m</div>`;
          explanation += `<div>✓ Answer: <b>${q.correct}</b></div>`;
        }
      }
    } else if (questionText.includes('grams') && questionText.includes('kilograms')) {
      explanation += `<div><b>Remember:</b> 1 kilogram = 1,000 grams</div>`;
      if (questionText.toLowerCase().includes('g to kg') || questionText.toLowerCase().includes('grams to k')) {
        explanation += `<div><b>To convert g to kg:</b> divide by 1,000</div>`;
        if (fromValue) {
          explanation += `<div style="margin-top:8px;"><b>Computation:</b></div>`;
          explanation += `<div>${fromValue} g ÷ 1,000 = ${fromValue / 1000} kg</div>`;
          explanation += `<div>✓ Answer: <b>${q.correct}</b></div>`;
        }
      } else {
        explanation += `<div><b>To convert kg to g:</b> multiply by 1,000</div>`;
        if (fromValue) {
          explanation += `<div style="margin-top:8px;"><b>Computation:</b></div>`;
          explanation += `<div>${fromValue} kg × 1,000 = ${fromValue * 1000} g</div>`;
          explanation += `<div>✓ Answer: <b>${q.correct}</b></div>`;
        }
      }
    } else if (questionText.includes('liters') && questionText.includes('milliliters')) {
      explanation += `<div><b>Remember:</b> 1 liter = 1,000 milliliters</div>`;
      if (questionText.toLowerCase().includes('ml to l') || questionText.toLowerCase().includes('milliliters to l')) {
        explanation += `<div><b>To convert mL to L:</b> divide by 1,000</div>`;
        if (fromValue) {
          explanation += `<div style="margin-top:8px;"><b>Computation:</b></div>`;
          explanation += `<div>${fromValue} mL ÷ 1,000 = ${fromValue / 1000} L</div>`;
          explanation += `<div>✓ Answer: <b>${q.correct}</b></div>`;
        }
      } else {
        explanation += `<div><b>To convert L to mL:</b> multiply by 1,000</div>`;
        if (fromValue) {
          explanation += `<div style="margin-top:8px;"><b>Computation:</b></div>`;
          explanation += `<div>${fromValue} L × 1,000 = ${fromValue * 1000} mL</div>`;
          explanation += `<div>✓ Answer: <b>${q.correct}</b></div>`;
        }
      }
    } else {
      // Fallback: Show basic explanation with the number extracted
      if (fromValue && fromUnit) {
        explanation += `<div><b>Given:</b> ${fromValue} ${fromUnit}</div>`;
      }
      explanation += `<div><b>Correct Answer:</b> ${q.correct}</div>`;
    }
    
    explanation += `</div>`;
    return explanation;
  }

  function advanceToNextQuestion(delayMs) {
    state.currentQ++;
    setTimeout(() => {
      if (state.currentQ < state.questions.length) {
        loadQuestion();
      } else {
        finishStage();
      }
    }, delayMs);
  }

  function updateStatsUI() {
    document.getElementById('scoreDisplay').textContent = state.score;
    document.getElementById('streakDisplay').textContent = state.streak + 'x';
    updateCoinsUI();
  }

  /* ==========================================================================
     COIN HINT SYSTEM
     ========================================================================== */
  window.useHint = function() {
    if (state.hintUsed || state.answered || state.isPaused) return;
    if (state.coins < 5) {
      alert("You need 5 coins to unlock a hint! Earn more by solving questions.");
      return;
    }

    initAudio();
    soundHint();
    state.hintUsed = true;
    saveCoins(state.coins - 5);

    const q = state.questions[state.currentQ];
    const hintBox = document.getElementById('hintBox');
    hintBox.className = 'hint-reveal-box show';
    hintBox.innerHTML = `
      <div style="display:flex; align-items:center; gap:6px; margin-bottom:4px; color:#ffffff;">
        <i class="bi bi-lightbulb-fill text-warning"></i> <b>How to Solve this Conversion:</b>
      </div>
      <div>${q.hint}</div>
    `;

    const btn = document.getElementById('btnHint');
    btn.disabled = true;
    btn.innerHTML = `<span>✓</span> <span>Hint Revealed</span>`;

    // On phones the hint appears above the answers — make sure it's seen
    if (isSmallScreen()) hintBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
  };

  /* ==========================================================================
     STAGE COMPLETION & VICTORY
     ========================================================================== */
  function finishStage() {
    clearInterval(state.timer);
    const accuracy = Math.round((state.correctCount / state.questions.length) * 100);
    const isWin = accuracy >= 70; // 70% pass threshold

    let stars = 0;
    if (accuracy >= 90) stars = 3;
    else if (accuracy >= 70) stars = 2;
    else if (accuracy >= 50) stars = 1;

    const coinsEarned = state.correctCount * 10 + (stars === 3 ? 50 : stars === 2 ? 25 : 0);
    saveCoins(state.coins + coinsEarned);

    document.getElementById('vScoreVal').textContent = state.score;
    document.getElementById('vAccuracyVal').textContent = accuracy + '%';
    document.getElementById('vStreakVal').textContent = state.maxStreak + 'x';
    document.getElementById('vCoinsBanner').innerHTML = `🪙 +${coinsEarned} Expedition Coins Earned! Total: ${state.coins} 🪙`;

    const icon = document.getElementById('vIcon');
    const title = document.getElementById('vTitle');
    const subtitle = document.getElementById('vSubtitle');
    const nextBtn = document.getElementById('btnNextStage');

    if (isWin) {
      soundWin();
      spawnConfetti(isSmallScreen() ? 32 : 55);
      icon.textContent = stars === 3 ? '🏆' : '🎉';
      title.textContent = stars === 3 ? 'Perfect Mastery!' : 'Stage Conquered!';
      subtitle.textContent = `Great expedition work! You achieved ${accuracy}% accuracy.`;
      nextBtn.style.display = (state.currentLevel < 49) ? 'inline-block' : 'none';

      // Save to Backend
      const nextLevelNum = Math.min(state.currentLevel + 2, 50);
      saveProgressToBackend(nextLevelNum, state.score);
    } else {
      window.playWrongSound();
      icon.textContent = '🧭';
      title.textContent = 'Keep Exploring!';
      subtitle.textContent = `You earned ${accuracy}%. Review the Metric Lab and try again!`;
      nextBtn.style.display = 'none';
    }

    document.getElementById('victoryOverlay').classList.add('active');
  }

  function saveProgressToBackend(nextLevelNum, score) {
    fetch('/student/update-progress', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN
      },
      body: JSON.stringify({
        game_id: GAME_ID,
        level: nextLevelNum,
        score: score
      })
    }).then(res => res.json()).catch(err => console.error(err));
  }

  window.retryStage = function() {
    document.getElementById('victoryOverlay').classList.remove('active');
    startStage(state.currentLevel);
  };

  window.advanceStage = function() {
    document.getElementById('victoryOverlay').classList.remove('active');
    let next = state.currentLevel + 1;
    if (next > 49) next = 49;
    startStage(next);
  };

  /* ==========================================================================
     FLASHCARD TUTORIAL DECK LOGIC
     ========================================================================== */
  let currentCardIdx = 0;
  const TOTAL_CARDS = 3;

  function openTutorial() {
    initAudio();
    soundClick();
    clearInterval(state.timer);
    currentCardIdx = 0;
    resetMqTry();
    updateFlashCardUI();
    const ov = document.getElementById('tutOverlay');
    ov.classList.add('active');
    ov.scrollTop = 0;
  }
  window.openTutorial = openTutorial;

  function closeTutorial() {
    initAudio();
    soundClick();
    localStorage.setItem(TUTORIAL_KEY, 'true');
    document.getElementById('tutOverlay').classList.remove('active');
    state.gameStarted = true;
    if (!state.answered && !state.isPaused && state.currentQ < state.questions.length) {
      startTimer();
    }
  }
  window.closeTutorial = closeTutorial;

  window.goFlashCard = function(idx) {
    if (idx < 0 || idx >= TOTAL_CARDS) return;
    initAudio();
    soundClick();
    currentCardIdx = idx;
    updateFlashCardUI();
    const ov = document.getElementById('tutOverlay');
    if (ov) ov.scrollTop = 0;
  };

  window.stepFlashCard = function(dir) {
    const nextIdx = currentCardIdx + dir;
    if (nextIdx >= 0 && nextIdx < TOTAL_CARDS) {
      window.goFlashCard(nextIdx);
    }
  };

  function updateFlashCardUI() {
    document.querySelectorAll('.fc-card').forEach((c, idx) => {
      c.classList.toggle('active', idx === currentCardIdx);
    });

    document.querySelectorAll('.tut-dot').forEach((d, idx) => {
      d.classList.toggle('active', idx === currentCardIdx);
    });

    document.getElementById('fcPageNum').textContent = `${currentCardIdx + 1} / ${TOTAL_CARDS}`;

    const prevBtn = document.getElementById('btnTutPrev');
    const nextBtn = document.getElementById('btnTutNext');
    const startBtn = document.getElementById('btnTutStart');
    const skipBtn = document.getElementById('btnTutSkip');

    if (prevBtn) prevBtn.style.display = currentCardIdx > 0 ? 'inline-flex' : 'none';

    if (currentCardIdx === TOTAL_CARDS - 1) {
      if (nextBtn) nextBtn.style.display = 'none';
      if (startBtn) startBtn.style.display = 'inline-flex';
      if (skipBtn) skipBtn.style.display = 'none';
    } else {
      if (nextBtn) nextBtn.style.display = 'inline-flex';
      if (startBtn) startBtn.style.display = 'none';
      if (skipBtn) skipBtn.style.display = 'inline-flex';
    }
  }

  /* "Try It" mini question in the Field Guide */
  function resetMqTry() {
    document.querySelectorAll('.mq-try-btn').forEach(b => b.classList.remove('ok', 'no'));
    const m = document.getElementById('mqTryMsg');
    if (m) m.textContent = 'Give it a go! 🧭';
  }
  window.mqTry = function(btn, ok) {
    initAudio();
    const msg = document.getElementById('mqTryMsg');
    btn.classList.remove('ok', 'no');
    void btn.offsetWidth;
    btn.classList.add(ok ? 'ok' : 'no');
    if (ok) { window.playCorrectSound(); } else { soundClick(); }
    if (msg) msg.textContent = ok
      ? '🎉 Yes! Big ➔ small = multiply: 2 × 1,000 = 2,000 m'
      : '❌ Not quite! km ➔ m is Big ➔ small, so multiply by 1,000';
  };

  // Swipe left/right to flip tutorial cards on touch screens
  (function enableTutorialSwipe() {
    let sx = 0, sy = 0;
    const ov = document.getElementById('tutOverlay');
    if (!ov) return;
    ov.addEventListener('touchstart', (e) => {
      sx = e.touches[0].clientX;
      sy = e.touches[0].clientY;
    }, { passive: true });
    ov.addEventListener('touchend', (e) => {
      const dx = e.changedTouches[0].clientX - sx;
      const dy = e.changedTouches[0].clientY - sy;
      if (Math.abs(dx) > 60 && Math.abs(dx) > Math.abs(dy) * 1.5) {
        window.stepFlashCard(dx < 0 ? 1 : -1);
      }
    }, { passive: true });
  })();

  /* ==========================================================================
     METRIC LAB REFERENCE DRAWER LOGIC
     ========================================================================== */
  window.openLab = function() {
    initAudio();
    soundClick();
    document.getElementById('labOverlay').classList.add('active');
    // Pause the question timer while the reference is open (fair on small screens)
    if (state.gameStarted && !state.isPaused && !state.answered) {
      clearInterval(state.timer);
      state.labPaused = true;
    }
  };

  window.closeLab = function() {
    document.getElementById('labOverlay').classList.remove('active');
    if (state.labPaused) {
      state.labPaused = false;
      if (!state.isPaused && !state.answered) {
        // Resume with the remaining time rather than restarting at 30s
        const remaining = state.timeLeft;
        startTimer();
        state.timeLeft = remaining;
        updateTimerUI();
      }
    }
  };

  window.switchLabTab = function(tabName) {
    initAudio();
    soundClick();

    document.querySelectorAll('.lab-tab-btn').forEach(btn => {
      btn.classList.toggle('active', btn.textContent.toLowerCase().includes(tabName));
    });

    document.querySelectorAll('.lab-content-section').forEach(sec => {
      sec.classList.toggle('active', sec.id === 'labSec-' + tabName);
    });
  };

  /* ==========================================================================
     PAUSE OVERLAY LOGIC
     ========================================================================== */
  window.togglePause = function() {
    if (state.currentQ >= state.questions.length) return;
    initAudio();
    soundClick();
    state.isPaused = !state.isPaused;
    const overlay = document.getElementById('pauseOverlay');
    const btn = document.getElementById('btnPauseGame');

    if (state.isPaused) {
      clearInterval(state.timer);
      if (overlay) overlay.classList.add('active');
      if (btn) btn.innerHTML = '<i class="bi bi-play-fill"></i> <span>Resume</span>';
    } else {
      if (overlay) overlay.classList.remove('active');
      if (btn) btn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
      if (!state.answered) startTimer();
    }
  };

  // Auto-pause when the phone locks or the player switches apps/tabs
  document.addEventListener('visibilitychange', function() {
    if (document.hidden && state.gameStarted && !state.isPaused && !state.answered &&
        state.currentQ < state.questions.length) {
      const tut = document.getElementById('tutOverlay');
      if (tut && tut.classList.contains('active')) return;
      window.togglePause();
    }
  });

  /* ==========================================================================
     KEYBOARD CONTROLS & LISTENERS
     ========================================================================== */
  document.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

    // Pause / Escape
    if (e.key === 'p' || e.key === 'P' || e.key === 'Escape') {
      const tut = document.getElementById('tutOverlay');
      const lab = document.getElementById('labOverlay');
      if (tut && tut.classList.contains('active')) {
        closeTutorial();
        return;
      }
      if (lab && lab.classList.contains('active')) {
        closeLab();
        return;
      }
      togglePause();
      return;
    }

    // Audio Mute
    if (e.key === 'm' || e.key === 'M') {
      toggleSound();
      return;
    }

    // Hint Hotkey
    if (e.key === 'h' || e.key === 'H') {
      useHint();
      return;
    }

    // Number keys 1-4 for answering
    if (['1', '2', '3', '4'].includes(e.key)) {
      const card = document.querySelector(`.answer-card[data-key="${e.key}"]`);
      if (card && !card.disabled) {
        card.click();
      }
    }
  });

  /* Confetti */
  function spawnConfetti(count) {
    const colors = ['#38bdf8', '#818cf8', '#34d399', '#fbbf24', '#f472b6', '#a78bfa'];
    for (let i = 0; i < count; i++) {
      setTimeout(() => {
        const c = document.createElement('div');
        c.className = 'confetti';
        c.style.left = (Math.random() * 100) + 'vw';
        c.style.background = colors[Math.floor(Math.random() * colors.length)];
        c.style.animationDuration = (2 + Math.random() * 2) + 's';
        document.body.appendChild(c);
        setTimeout(() => c.remove(), 4000);
      }, i * 30);
    }
  }

  /* Event Handlers */
  document.getElementById('btnOpenTutorial').addEventListener('click', openTutorial);
  document.getElementById('btnOpenLab').addEventListener('click', openLab);
  document.getElementById('btnSidebarOpenLab').addEventListener('click', openLab);
  document.getElementById('btnSoundToggle').addEventListener('click', toggleSound);
  document.getElementById('btnPauseGame').addEventListener('click', togglePause);

  /* Close drawer when clicking outside */
  document.getElementById('labOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeLab();
  });
  document.getElementById('tutOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeTutorial();
  });

  /* ==========================================================================
     STARTUP
     ========================================================================== */
  window.scrollTo(0, 0);
  updateSoundUI();
  updateCoinsUI();

  // Show instruction if requested by tutorial button, level click, or first-time player
  const shouldShowInstruction = paramTutorial || storageTutorial || urlParams.has('level') || urlParams.has('show_instruction') || !localStorage.getItem(TUTORIAL_KEY);

  startStage(state.currentLevel, shouldShowInstruction);

})();
</script>
@endsection