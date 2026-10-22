@extends('layouts.app')
@section('hide-nav', true)
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@500;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
  /* ==========================================================================
     MDAS MAZE — BALANCED CYBER ARCADE (30 Tiles, 6x5 Grid, 96px 3D Dice)
     ========================================================================== */
  :root {
    --bg-dark: #070b19;
    --card-bg: rgba(15, 23, 42, 0.85);
    --card-border: rgba(99, 102, 241, 0.35);

    /* MDAS Zone Accents */
    --coral: #f43f5e;
    --sky: #38bdf8;
    --mint: #10b981;
    --violet: #a855f7;
    --gold: #f59e0b;
    --amber: #fbbf24;
    --cyan: #06b6d4;

    --font-heading: 'Space Grotesk', -apple-system, sans-serif;
    --font-body: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
    --font-mono: 'Space Grotesk', monospace;
  }

  * { box-sizing: border-box; }

  html, body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    background:
      radial-gradient(circle at 12% 18%, rgba(99, 102, 241, 0.22) 0%, transparent 40%),
      radial-gradient(circle at 88% 82%, rgba(56, 189, 248, 0.18) 0%, transparent 45%),
      radial-gradient(circle at 50% 50%, rgba(168, 85, 247, 0.12) 0%, transparent 55%),
      var(--bg-dark);
    color: #e2e8f0;
    font-family: var(--font-body);
    font-weight: 600;
    overflow-x: hidden;
  }

  /* ── FLOATING MATH SYMBOLS LAYER (From Welcome/Index Page) ── */
  #sym-layer {
    position: fixed; inset: 0;
    pointer-events: none; z-index: 0; overflow: hidden;
  }
  .sym {
    position: absolute;
    font-family: 'Space Grotesk', monospace;
    font-weight: 800;
    pointer-events: none;
    user-select: none;
    will-change: transform, opacity;
    animation: floatDrift linear infinite;
    filter: drop-shadow(0 0 10px currentColor);
  }
  @keyframes floatDrift {
    0% {
      transform: translateY(0) rotate(0deg);
      opacity: 0;
    }
    8% {
      opacity: 0.42;
    }
    90% {
      opacity: 0.42;
    }
    100% {
      transform: translateY(-115vh) rotate(360deg);
      opacity: 0;
    }
  }
  @media (prefers-reduced-motion: reduce) {
    .sym { animation: none; opacity: 0.25; }
  }

  /* Subtle Cyber Grid */
  .cyber-grid-bg {
    position: fixed; inset: 0;
    background-image:
      linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
    background-size: 38px 38px;
    pointer-events: none;
    z-index: 0;
  }

  /* ── APPLICATION CONTAINER (Fits Viewport Nicely) ── */
  .app-container {
    position: relative;
    z-index: 1;
    width: 97%;
    max-width: 1720px;
    margin: 0 auto;
    padding: 8px 14px 16px;
    display: flex;
    flex-direction: column;
  }

  /* ============ MASTHEAD ============ */
  .masthead {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 8px;
    padding-bottom: 8px;
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
    font-size: clamp(1.6rem, 2.5vw, 2.2rem);
    background: linear-gradient(135deg, #c084fc 0%, #38bdf8 50%, #34d399 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 0;
    line-height: 1.1;
    letter-spacing: -0.5px;
    filter: drop-shadow(0 3px 12px rgba(99, 102, 241, 0.28));
  }
  .title-group p {
    color: #94a3b8;
    font-weight: 600;
    font-size: 0.84rem;
    margin: 2px 0 0;
    letter-spacing: 0.2px;
  }

  .level-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #1e1b02;
    padding: 5px 14px;
    border-radius: 50px;
    font-weight: 800;
    font-size: 0.82rem;
    letter-spacing: 0.4px;
    box-shadow: 0 0 16px rgba(245, 158, 11, 0.35);
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
  }
  .coins-pill:hover { transform: scale(1.04); }

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
    background: rgba(99, 102, 241, 0.22);
    border-color: #818cf8;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
  }
  .eduplay-btn-pause {
    background: rgba(245, 158, 11, 0.14);
    border-color: rgba(245, 158, 11, 0.35);
    color: #fde68a;
  }
  .eduplay-btn-pause.paused {
    background: rgba(16, 185, 129, 0.25);
    border-color: #34d399;
    color: #6ee7b7;
  }
  .eduplay-btn-help {
    background: rgba(168, 85, 247, 0.16);
    border-color: rgba(168, 85, 247, 0.38);
    color: #e9d5ff;
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

  /* ============ MDAS ORDER RIBBON ============ */
  .mdas-order-ribbon {
    background: linear-gradient(90deg, rgba(244, 63, 94, 0.14) 0%, rgba(56, 189, 248, 0.14) 35%, rgba(16, 185, 129, 0.14) 70%, rgba(168, 85, 247, 0.14) 100%);
    border: 1.5px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 6px 14px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.28);
  }
  .ribbon-label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.82rem;
    font-weight: 800;
    color: #cbd5e1;
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }
  .ribbon-steps {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
  }
  .ribbon-step {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(0, 0, 0, 0.4);
    padding: 3px 10px;
    border-radius: 7px;
    font-size: 0.8rem;
    font-weight: 800;
    border: 1px solid rgba(255, 255, 255, 0.12);
  }
  .ribbon-step.p1 { border-color: rgba(244, 63, 94, 0.55); color: #fb7185; }
  .ribbon-step.p2 { border-color: rgba(56, 189, 248, 0.55); color: #38bdf8; }
  .ribbon-step.p3 { border-color: rgba(16, 185, 129, 0.55); color: #34d399; }
  .ribbon-step.p4 { border-color: rgba(245, 158, 11, 0.55); color: #fbbf24; }
  .ribbon-arrow { color: #64748b; font-size: 0.76rem; }
  .ribbon-tip {
    font-size: 0.95rem;
    color: #a5b4fc;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }

  /* ============ MAIN GAME LAYOUT ============ */
  .game-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 14px;
    align-items: stretch;
  }
  @media (max-width: 1040px) {
    .game-layout { grid-template-columns: 1fr; }
  }

  /* ============ BOARD PANEL ============ */
  .board-panel {
    background: linear-gradient(165deg, rgba(13, 21, 46, 0.92) 0%, rgba(10, 16, 36, 0.94) 100%);
    border: 1.5px solid rgba(99, 102, 241, 0.4);
    border-radius: 22px;
    box-shadow:
      0 20px 50px rgba(0, 0, 0, 0.65),
      0 0 40px rgba(99, 102, 241, 0.14);
    padding: 14px 16px 16px;
    position: relative;
    backdrop-filter: blur(14px);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  /* ── BOARD TOOLBAR WITH SPACIOUS MDAS RULES BUTTON ── */
  .board-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: nowrap;
    gap: 12px;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }
  .path-key {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
    font-size: 0.76rem;
    color: #cbd5e1;
    font-weight: 700;
  }
  .path-key span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.05);
    padding: 4px 11px;
    border-radius: 50px;
    border: 1px solid rgba(255, 255, 255, 0.09);
    white-space: nowrap;
  }
  .path-key i {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 8px currentColor;
  }

  /* Dedicated Uncramped MDAS Rules Button */
  .mdas-rules-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 18px;
    border-radius: 12px;
    font-family: var(--font-body);
    font-size: 0.86rem;
    font-weight: 800;
    color: #e9d5ff;
    background: linear-gradient(135deg, rgba(168, 85, 247, 0.28), rgba(99, 102, 241, 0.28));
    border: 1.5px solid rgba(168, 85, 247, 0.55);
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 14px rgba(168, 85, 247, 0.25);
  }
  .mdas-rules-btn:hover {
    background: linear-gradient(135deg, rgba(168, 85, 247, 0.45), rgba(99, 102, 241, 0.45));
    border-color: #c084fc;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(168, 85, 247, 0.45);
  }

  /* ── 30-TILE MAZE GRID (6 cols x 5 rows) ── */
  .maze-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 9px;
    position: relative;
    flex: 1;
    align-content: stretch;
  }
  @media (max-width: 680px) {
    .maze-grid {
      grid-template-columns: repeat(3, 1fr);
      gap: 7px;
    }
  }

  /* ── BALANCED 30 TILES (Fits Viewport Seamlessly) ── */
  .cell {
    position: relative;
    min-height: clamp(75px, 10.8vh, 98px);
    border-radius: 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 6px 4px;
    border: 2px solid rgba(255, 255, 255, 0.09);
    background: rgba(255, 255, 255, 0.035);
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    user-select: none;
    overflow: hidden;
  }
  .cell:hover {
    transform: translateY(-2px) scale(1.02);
    border-color: rgba(255, 255, 255, 0.35);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
  }
  .cell-num {
    position: absolute;
    top: 5px;
    left: 7px;
    font-size: 0.9rem;
    font-family: var(--font-mono);
    color: rgba(255, 255, 255, 0.45);
    font-weight: 800;
  }
  .cell-dir-indicator {
    position: absolute;
    bottom: 4px;
    right: 6px;
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.22);
  }
  .cell .op-label {
    font-family: var(--font-heading);
    font-weight: 900;
    font-size: clamp(0.95rem, 1.6vw, 1.35rem);
    text-align: center;
    letter-spacing: 0.5px;
    line-height: 1.2;
    z-index: 1;
  }
  .cell .op-sub {
    font-size: 0.68rem;
    color: rgba(255, 255, 255, 0.6);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-top: 2px;
  }

  /* Vibrant Neon Maze Cells */
  .cell.start {
    background: radial-gradient(circle, rgba(16, 185, 129, 0.35) 0%, rgba(16, 185, 129, 0.12) 100%);
    border-color: rgba(16, 185, 129, 0.8);
    box-shadow: inset 0 0 16px rgba(16, 185, 129, 0.25), 0 0 16px rgba(16, 185, 129, 0.25);
  }
  .cell.start .op-label { color: #34d399; font-size: 1.05rem; }

  .cell.finish {
    background: radial-gradient(circle, rgba(245, 158, 11, 0.38) 0%, rgba(245, 158, 11, 0.14) 100%);
    border-color: rgba(245, 158, 11, 0.85);
    box-shadow: inset 0 0 20px rgba(245, 158, 11, 0.3), 0 0 20px rgba(245, 158, 11, 0.3);
  }
  .cell.finish .op-label { color: #fbbf24; font-size: 1.05rem; }

  .cell.red {
    background: rgba(244, 63, 94, 0.16);
    border-color: rgba(244, 63, 94, 0.55);
  }
  .cell.red .op-label { color: #fb7185; }

  .cell.blue {
    background: rgba(56, 189, 248, 0.16);
    border-color: rgba(56, 189, 248, 0.55);
  }
  .cell.blue .op-label { color: #38bdf8; }

  .cell.green {
    background: rgba(52, 211, 153, 0.16);
    border-color: rgba(52, 211, 153, 0.55);
  }
  .cell.green .op-label { color: #34d399; }

  .cell.gold {
    background: linear-gradient(135deg, rgba(168, 85, 247, 0.22), rgba(245, 158, 11, 0.22));
    border-color: rgba(168, 85, 247, 0.65);
    box-shadow: 0 0 14px rgba(168, 85, 247, 0.22);
  }
  .cell.gold .op-label {
    background: linear-gradient(135deg, #c084fc, #fbbf24);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  .cell.neutral {
    background: rgba(255, 255, 255, 0.025);
    border-style: dashed;
    border-color: rgba(255, 255, 255, 0.18);
  }
  .cell.neutral .op-label { color: #94a3b8; font-size: 0.88rem; }

  /* Current Active Tile Glow */
  .cell.active-tile {
    border-color: #f59e0b !important;
    box-shadow: 0 0 25px rgba(245, 158, 11, 0.6) !important;
    animation: pulseBorder 1.5s infinite alternate;
  }
  @keyframes pulseBorder {
    from { box-shadow: 0 0 14px rgba(245, 158, 11, 0.45); }
    to { box-shadow: 0 0 30px rgba(245, 158, 11, 0.85); }
  }

  /* Token Well & Token */
  .token-well {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    z-index: 5;
  }
  .token {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    background: linear-gradient(135deg, #f59e0b, #ec4899);
    border: 3px solid #ffffff;
    box-shadow:
      0 0 22px rgba(236, 72, 153, 0.9),
      0 4px 12px rgba(0, 0, 0, 0.6);
    transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  .token.hop {
    animation: cyberHop 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }
  @keyframes cyberHop {
    0% { transform: translateY(0) scale(1); }
    40% { transform: translateY(-24px) scale(1.25); filter: brightness(1.35); }
    100% { transform: translateY(0) scale(1); }
  }

  /* ============ SIDEBAR ============ */
  .sidebar {
    display: flex;
    flex-direction: column;
    gap: 12px;
    justify-content: flex-start;
  }
  .sidebar-panel {
    background: linear-gradient(165deg, rgba(15, 23, 42, 0.94) 0%, rgba(10, 16, 32, 0.94) 100%);
    backdrop-filter: blur(14px);
    border: 1.5px solid rgba(255, 255, 255, 0.1);
    border-radius: 18px;
    padding: 16px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.45);
    position: relative;
  }
  .sidebar-panel h3 {
    font-family: var(--font-heading);
    font-weight: 800;
    font-size: 1rem;
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
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border: 2px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 0 16px rgba(99, 102, 241, 0.45);
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
  .player-meta {
    font-size: 0.74rem;
    color: #94a3b8;
  }

  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
  }
  .stat-box {
    background: rgba(0, 0, 0, 0.32);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 7px 10px;
    text-align: center;
  }
  .stat-label {
    font-size: 0.68rem;
    color: #94a3b8;
    text-transform: uppercase;
    font-weight: 700;
    margin-bottom: 2px;
  }
  .stat-value {
    font-family: var(--font-heading);
    font-size: 1.2rem;
    font-weight: 900;
    line-height: 1;
  }
  .stat-value.score {
    background: linear-gradient(135deg, #38bdf8, #818cf8);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  .stat-value.streak {
    color: #f59e0b;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
  }

  /* Progress Track */
  .progress-wrap {
    margin-top: 10px;
    padding-top: 8px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
  }
  .progress-header {
    display: flex;
    justify-content: space-between;
    font-size: 0.9rem;
    color: #94a3b8;
    font-weight: 700;
    margin-bottom: 4px;
  }
  .progress-bar-bg {
    width: 100%;
    height: 7px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50px;
    overflow: hidden;
  }
  .progress-bar-fill {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #6366f1, #a855f7, #ec4899);
    border-radius: 50px;
    transition: width 0.4s ease;
    box-shadow: 0 0 10px rgba(168, 85, 247, 0.5);
  }

  /* ── 3D CYBER DICE CONTAINER (Classic 96px Size) ── */
  .dice-panel { text-align: center; }
  .dice-stage {
    width: 96px;
    height: 96px;
    margin: 6px auto 14px;
    perspective: 800px;
  }
  .die {
    width: 100%;
    height: 100%;
    position: relative;
    transform-style: preserve-3d;
    transform: rotateX(-15deg) rotateY(15deg);
    transition: transform 1.05s cubic-bezier(0.22, 1.6, 0.36, 1);
  }
  .face {
    position: absolute;
    width: 96px;
    height: 96px;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border: 2px solid rgba(99, 102, 241, 0.6);
    border-radius: 18px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(3, 1fr);
    padding: 12px;
    gap: 4px;
    backface-visibility: hidden;
    box-shadow: inset 0 0 18px rgba(99, 102, 241, 0.25), 0 8px 24px rgba(0, 0, 0, 0.5);
  }
  .pip {
    visibility: hidden;
    align-self: center;
    justify-self: center;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: #38bdf8;
    box-shadow: 0 0 10px rgba(56, 189, 248, 0.9);
  }
  .face-1 { transform: rotateY(0deg) translateZ(48px); }
  .face-2 { transform: rotateY(90deg) translateZ(48px); }
  .face-3 { transform: rotateY(180deg) translateZ(48px); }
  .face-4 { transform: rotateY(-90deg) translateZ(48px); }
  .face-5 { transform: rotateX(90deg) translateZ(48px); }
  .face-6 { transform: rotateX(-90deg) translateZ(48px); }
  .face-1 .pip:nth-child(5){ visibility:visible; }
  .face-2 .pip:nth-child(3), .face-2 .pip:nth-child(7){ visibility:visible; }
  .face-3 .pip:nth-child(3), .face-3 .pip:nth-child(5), .face-3 .pip:nth-child(7){ visibility:visible; }
  .face-4 .pip:nth-child(1), .face-4 .pip:nth-child(3), .face-4 .pip:nth-child(7), .face-4 .pip:nth-child(9){ visibility:visible; }
  .face-5 .pip:nth-child(1), .face-5 .pip:nth-child(3), .face-5 .pip:nth-child(5), .face-5 .pip:nth-child(7), .face-5 .pip:nth-child(9){ visibility:visible; }
  .face-6 .pip:nth-child(1), .face-6 .pip:nth-child(4), .face-6 .pip:nth-child(7), .face-6 .pip:nth-child(3), .face-6 .pip:nth-child(6), .face-6 .pip:nth-child(9){ visibility:visible; }

  .roll-btn {
    border: none;
    border-radius: 50px;
    font-family: var(--font-body);
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    width: 100%;
    padding: 13px 0;
    font-size: 1rem;
    font-weight: 800;
    letter-spacing: 0.6px;
    color: #ffffff;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
    box-shadow: 0 8px 24px rgba(99, 102, 241, 0.45);
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }
  .roll-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(99, 102, 241, 0.65);
    filter: brightness(1.08);
  }
  .roll-btn:active:not(:disabled) { transform: translateY(1px); }
  .roll-btn:disabled { opacity: 0.45; filter: grayscale(0.5); cursor: not-allowed; }
  .roll-hint {
    font-size: 0.74rem;
    color: #64748b;
    font-weight: 600;
  }

  /* ==========================================================================
     CHALLENGE MODAL & COIN HINT SYSTEM
     ========================================================================== */
  .challenge-overlay {
    position: fixed; inset: 0;
    background: rgba(4, 8, 22, 0.88);
    backdrop-filter: blur(16px);
    z-index: 9990;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .challenge-overlay.active {
    display: flex;
    animation: modalPopIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  }
  @keyframes modalPopIn {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
  }

  .challenge-card {
    background: linear-gradient(165deg, #0e172e 0%, #111e3d 100%);
    border: 2px solid rgba(56, 189, 248, 0.45);
    border-radius: 28px;
    padding: 28px;
    max-width: 480px;
    width: 100%;
    box-shadow:
      0 30px 80px rgba(0, 0, 0, 0.85),
      0 0 50px rgba(56, 189, 248, 0.22);
    position: relative;
    overflow: hidden;
  }
  .challenge-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }
  .challenge-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.82rem;
    font-weight: 800;
    color: #38bdf8;
    background: rgba(56, 189, 248, 0.12);
    padding: 5px 12px;
    border-radius: 50px;
    border: 1px solid rgba(56, 189, 248, 0.3);
  }

  /* Big Expression Box */
  .expr-box {
    font-family: var(--font-heading);
    font-weight: 800;
    font-size: clamp(1.8rem, 4vw, 2.3rem);
    text-align: center;
    background: rgba(0, 0, 0, 0.45);
    border: 1.5px solid rgba(56, 189, 248, 0.35);
    border-radius: 18px;
    padding: 18px 14px;
    margin-bottom: 12px;
    letter-spacing: 1.2px;
    color: #ffffff;
    box-shadow: inset 0 0 25px rgba(56, 189, 248, 0.12);
  }
  .expr-box .op-m { color: #f43f5e; font-weight: 900; }
  .expr-box .op-d { color: #38bdf8; font-weight: 900; }
  .expr-box .op-a { color: #34d399; font-weight: 900; }
  .expr-box .op-s { color: #fbbf24; font-weight: 900; }

  /* MDAS Quick Reminder Strip inside Challenge */
  .challenge-rule-strip {
    background: rgba(255, 255, 255, 0.04);
    border-radius: 10px;
    padding: 6px 10px;
    font-size: 0.76rem;
    color: #94a3b8;
    text-align: center;
    margin-bottom: 12px;
    font-weight: 700;
  }
  .challenge-rule-strip b { color: #38bdf8; }

  /* Coin Hint Bar */
  .coin-hint-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    background: rgba(245, 158, 11, 0.08);
    border: 1px dashed rgba(245, 158, 11, 0.35);
    border-radius: 14px;
    padding: 10px 14px;
    margin-bottom: 14px;
  }
  .coin-hint-info {
    font-size: 0.8rem;
    color: #cbd5e1;
    font-weight: 600;
    line-height: 1.3;
  }
  .coin-hint-info span {
    color: #fbbf24;
    font-weight: 800;
  }
  .btn-coin-hint {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #1e1b02;
    border: none;
    border-radius: 10px;
    padding: 7px 14px;
    font-family: var(--font-body);
    font-size: 0.82rem;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
    transition: all 0.2s;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .btn-coin-hint:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(245, 158, 11, 0.55);
  }
  .btn-coin-hint:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    filter: grayscale(0.5);
  }

  /* Revealed Hint Box */
  .hint-reveal-box {
    display: none;
    background: rgba(16, 185, 129, 0.1);
    border: 1.5px solid rgba(16, 185, 129, 0.4);
    border-radius: 14px;
    padding: 12px 14px;
    margin-bottom: 14px;
    font-size: 0.86rem;
    color: #e2e8f0;
    line-height: 1.45;
    animation: fadeIn 0.25s ease;
  }
  .hint-reveal-box.active { display: block; }
  .hint-reveal-title {
    font-weight: 800;
    color: #34d399;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  /* Challenge Form & Mini Keypad */
  .challenge-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .input-row {
    display: flex;
    gap: 10px;
  }
  .challenge-input {
    flex: 1;
    min-width: 0;
    border: 2px solid rgba(255, 255, 255, 0.16);
    border-radius: 16px;
    padding: 12px;
    font-size: 1.4rem;
    font-weight: 900;
    text-align: center;
    background: rgba(0, 0, 0, 0.35);
    color: #ffffff;
    font-family: var(--font-heading);
    transition: all 0.2s;
  }
  .challenge-input:focus {
    outline: none;
    border-color: #38bdf8;
    box-shadow: 0 0 20px rgba(56, 189, 248, 0.45);
  }
  .submit-btn {
    border: none;
    border-radius: 16px;
    padding: 0 28px;
    font-family: var(--font-body);
    font-weight: 800;
    font-size: 1rem;
    background: linear-gradient(135deg, #10b981, #059669);
    color: #ffffff;
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
    cursor: pointer;
    transition: all 0.2s;
  }
  .submit-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.65);
  }
  .submit-btn:disabled { opacity: 0.5; cursor: not-allowed; }

  /* Cyber Keypad for Quick Click/Touch */
  .keypad-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 6px;
    margin-top: 4px;
  }
  .key-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #e2e8f0;
    border-radius: 10px;
    padding: 8px 0;
    font-family: var(--font-heading);
    font-weight: 700;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.15s;
  }
  .key-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #fff;
    transform: translateY(-1px);
  }
  .key-btn:active { transform: scale(0.95); }
  .key-btn.del { background: rgba(244, 63, 94, 0.15); border-color: rgba(244, 63, 94, 0.35); color: #fb7185; }
  .key-btn.clear { background: rgba(148, 163, 184, 0.15); border-color: rgba(148, 163, 184, 0.35); color: #94a3b8; }

  /* Feedback Area */
  .challenge-feedback {
    margin-top: 12px;
    font-size: 0.92rem;
    line-height: 1.4;
    color: #94a3b8;
    min-height: 2.6em;
    text-align: center;
    font-weight: 700;
    padding: 8px 12px;
    border-radius: 12px;
    background: rgba(0, 0, 0, 0.2);
  }
  .challenge-feedback.good {
    color: #34d399;
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.4);
  }
  .challenge-feedback.bad {
    color: #f87171;
    background: rgba(244, 63, 94, 0.15);
    border: 1px solid rgba(244, 63, 94, 0.4);
  }

  .step-breakdown {
    text-align: left;
    margin-top: 8px;
    font-size: 0.82rem;
    color: #e2e8f0;
    background: rgba(0, 0, 0, 0.3);
    padding: 10px 12px;
    border-radius: 10px;
    line-height: 1.5;
  }

  /* ==========================================================================
     FLASH CARD DECK MODAL (Instructions & In-Game Tutorial)
     ========================================================================== */
  .fc-overlay {
    position: fixed; inset: 0;
    background: rgba(4, 7, 20, 0.88);
    backdrop-filter: blur(14px);
    z-index: 9995;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
  }
  .fc-overlay.active { display: flex; animation: fadeIn 0.25s ease; }

  .fc-deck {
    position: relative;
    max-width: 820px;
    width: 100%;
  }
  .fc-deck-stack-1 {
    position: absolute;
    inset: 8px -8px -8px 8px;
    background: rgba(30, 41, 59, 0.6);
    border-radius: 28px;
    border: 1.5px solid rgba(255, 255, 255, 0.05);
    z-index: 1;
    pointer-events: none;
  }
  .fc-deck-stack-2 {
    position: absolute;
    inset: 16px -16px -16px 16px;
    background: rgba(15, 23, 42, 0.4);
    border-radius: 28px;
    border: 1.5px solid rgba(255, 255, 255, 0.03);
    z-index: 0;
    pointer-events: none;
  }

  .fc-card {
    position: relative;
    z-index: 2;
    background: linear-gradient(165deg, #0e172e 0%, #0d152a 100%);
    border: 2px solid rgba(99, 102, 241, 0.4);
    border-radius: 28px;
    padding: 36px 40px 32px;
    box-shadow:
      0 30px 80px rgba(0, 0, 0, 0.85),
      0 0 50px rgba(99, 102, 241, 0.2);
    min-height: auto;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .fc-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }
  .fc-badge {
    font-size: 0.92rem;
    font-weight: 800;
    color: #a78bfa;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .fc-quick-action-btn {
    background: transparent;
    border: none;
    color: #94a3b8;
    font-family: var(--font-body);
    font-weight: 700;
    font-size: 0.96rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 8px;
    transition: all 0.2s;
  }
  .fc-quick-action-btn:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.08);
  }

  .fc-slide {
    flex: 1;
    display: flex;
    flex-direction: column;
    animation: fadeInSlide 0.25s ease;
  }
  .fc-slide.hidden { display: none; }
  @keyframes fadeInSlide {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .fc-title {
    font-family: var(--font-heading);
    font-weight: 800;
    font-size: 1.7rem;
    margin: 0 0 8px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .fc-desc {
    color: #cbd5e1;
    font-size: 1.08rem;
    line-height: 1.5;
    margin: 0 0 16px;
  }

  .fc-box {
    background: rgba(0, 0, 0, 0.35);
    border: 1.5px solid rgba(255, 255, 255, 0.08);
    border-radius: 18px;
    padding: 20px 22px;
    margin-bottom: 14px;
  }
  .fc-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }
  @media (max-width: 520px) {
    .fc-row-2 { grid-template-columns: 1fr; }
  }

  .fc-item {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.06);
    border-radius: 14px;
    padding: 16px;
    text-align: center;
  }
  .fc-item-ico { font-size: 1.5rem; margin-bottom: 4px; }
  .fc-item-lbl { font-weight: 800; font-size: 1.0rem; color: #f8fafc; margin-bottom: 2px; }
  .fc-item-sub { font-size: 0.92rem; color: #94a3b8; }
  .fc-item-badge {
    margin-top: 6px;
    display: inline-block;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 0.88rem;
    font-weight: 800;
  }
  .fc-item-badge.good { background: rgba(16, 185, 129, 0.2); color: #34d399; }
  .fc-item-badge.bad { background: rgba(244, 63, 94, 0.2); color: #fb7185; }

  .fc-tip {
    background: rgba(99, 102, 241, 0.12);
    border-left: 3px solid #818cf8;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 0.98rem;
    color: #e0e7ff;
    line-height: 1.45;
  }

  .fc-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 18px;
    padding-top: 14px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
  }
  .fc-nav-btn {
    border: 1.5px solid rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.05);
    color: #e2e8f0;
    border-radius: 50px;
    padding: 8px 18px;
    font-family: var(--font-body);
    font-weight: 800;
    font-size: 1.0rem;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .fc-nav-btn:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
  }
  .fc-nav-btn.primary {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-color: transparent;
    color: #fff;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.45);
  }
  .fc-nav-btn.primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(99, 102, 241, 0.65);
  }
  .fc-dots { display: flex; gap: 8px; }
  .fc-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.18);
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    padding: 0;
  }
  .fc-dot.active {
    background: #38bdf8;
    transform: scale(1.3);
    box-shadow: 0 0 10px rgba(56, 189, 248, 0.8);
  }

  /* ============ PAUSE OVERLAY ============ */
  .pause-overlay {
    position: fixed; inset: 0;
    background: rgba(4, 7, 20, 0.88);
    backdrop-filter: blur(14px);
    z-index: 9998;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .pause-overlay.active { display: flex; animation: fadeIn 0.2s ease; }
  .pause-box {
    background: linear-gradient(165deg, #0f172a, #1e293b);
    border: 1.5px solid rgba(99, 102, 241, 0.45);
    padding: 38px 44px;
    border-radius: 26px;
    text-align: center;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.8), 0 0 45px rgba(99, 102, 241, 0.25);
    max-width: 440px;
    width: 100%;
  }
  .pause-box h2 {
    font-family: var(--font-heading);
    font-size: 2rem;
    font-weight: 900;
    color: #fff;
    margin: 0 0 10px;
  }
  .pause-box p { color: #94a3b8; font-size: 0.95rem; margin: 0 0 24px; }
  .resume-btn {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    padding: 13px 36px;
    border-radius: 50px;
    font-weight: 800;
    font-family: var(--font-body);
    font-size: 1rem;
    color: #fff;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
    transition: all 0.2s;
  }
  .resume-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.65);
  }

  /* ============ WINNER OVERLAY ============ */
  .winner-overlay {
    position: fixed; inset: 0;
    background: rgba(4, 7, 20, 0.9);
    backdrop-filter: blur(16px);
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .winner-overlay.active { display: flex; animation: fadeIn 0.25s ease; }
  .winner-card {
    background: linear-gradient(165deg, #0e172e 0%, #172554 100%);
    border: 2px solid rgba(245, 158, 11, 0.5);
    border-radius: 30px;
    padding: 38px 32px;
    max-width: 460px;
    width: 100%;
    text-align: center;
    box-shadow: 0 40px 90px rgba(0, 0, 0, 0.85), 0 0 60px rgba(245, 158, 11, 0.3);
    animation: modalPopIn 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }
  .winner-card .trophy {
    font-size: 4rem;
    margin-bottom: 10px;
    filter: drop-shadow(0 0 25px rgba(245, 158, 11, 0.7));
    animation: floatTrophy 2s ease-in-out infinite;
  }
  @keyframes floatTrophy {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
  }
  .winner-card h2 {
    font-family: var(--font-heading);
    margin: 0 0 8px;
    font-size: 2.1rem;
    font-weight: 900;
    color: #fff;
  }
  .winner-card .final-score {
    font-family: var(--font-heading);
    font-size: 1.8rem;
    font-weight: 900;
    color: #38bdf8;
    margin: 12px 0 16px;
  }
  .winner-card .play-again {
    width: 100%;
    border: none;
    border-radius: 50px;
    padding: 14px;
    font-family: var(--font-body);
    font-weight: 800;
    font-size: 1.05rem;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #1e1b02;
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.45);
    cursor: pointer;
    transition: 0.2s;
  }
  .winner-card .play-again:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.65);
  }

  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<!-- ── FLOATING MATH SYMBOLS LAYER (From Index Page) ── -->
<div id="sym-layer" aria-hidden="true"></div>

<div class="cyber-grid-bg"></div>

<div class="app-container">
  <!-- ============ MASTHEAD ============ -->
  <header class="masthead">
    <div class="masthead-left">
      <div class="level-badge" id="levelBadge">
        <i class="bi bi-star-fill"></i> Level {{ $progress->current_level ?? 1 }} of 10
      </div>
      <div class="coins-pill" id="coinsPill" title="Your Coins Bank">
        🪙 <span id="coinDisplay">50</span> Coins
      </div>
      <div class="title-group">
        <h1>MDAS Maze</h1>
        <p>Order of Operations: Multiplication &amp; Division first, then Addition &amp; Subtraction!</p>
      </div>
    </div>

    <!-- Eduplay Standard Navigation Buttons -->
    <div class="eduplay-game-nav">
      <button type="button" class="eduplay-nav-btn eduplay-btn-sound" id="soundBtn" title="Toggle Sound">
        <i class="bi bi-volume-up-fill" id="soundIcon"></i> <span id="soundText">Sound</span>
      </button>
      <button type="button" class="eduplay-nav-btn eduplay-btn-help" id="tutorialBtn" title="How to Play &amp; Tutorial">
        <i class="bi bi-question-circle-fill"></i> <span>Tutorial</span>
      </button>
      <button type="button" class="eduplay-nav-btn eduplay-btn-pause" id="topPauseBtn" title="Pause Game (P or Esc)">
        <i class="bi bi-pause-fill"></i> <span>Pause</span>
      </button>
      <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map">
        <i class="bi bi-map-fill"></i> <span>Game Map</span>
      </a>
      <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Progress">
        <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
      </a>
    </div>
  </header>

  <!-- ============ MDAS ORDER OF OPERATIONS RIBBON ============ -->
  <div class="mdas-order-ribbon">
    <div class="ribbon-label">
      <i class="bi bi-diagram-3-fill text-warning"></i> MDAS Order:
    </div>
    <div class="ribbon-steps">
      <div class="ribbon-step p1"><i class="bi bi-1-circle-fill"></i> Multiply (×)</div>
      <span class="ribbon-arrow">&amp;</span>
      <div class="ribbon-step p2"><i class="bi bi-1-circle-fill"></i> Divide (÷)</div>
      <span class="ribbon-arrow">➔ <small>Left to Right</small> ➔</span>
      <div class="ribbon-step p3"><i class="bi bi-2-circle-fill"></i> Add (+)</div>
      <span class="ribbon-arrow">&amp;</span>
      <div class="ribbon-step p4"><i class="bi bi-2-circle-fill"></i> Subtract (−)</div>
    </div>
    <div class="ribbon-tip">
      <i class="bi bi-lightbulb-fill text-warning"></i> <span>Multiply &amp; Divide BEFORE you Add &amp; Subtract!</span>
    </div>
  </div>

  <!-- ============ MAIN GAME LAYOUT ============ -->
  <main class="game-layout" id="gameScreen">
    <!-- BOARD PANEL -->
    <section class="board-panel" aria-label="MDAS Maze Board">
      <div class="board-toolbar">
        <div class="path-key">
          <span><i style="background:var(--coral)"></i> × +</span>
          <span><i style="background:var(--sky)"></i> ÷ −</span>
          <span><i style="background:var(--mint)"></i> × ÷</span>
          <span><i style="background:var(--violet)"></i> ★ MDAS</span>
        </div>
        <button class="mdas-rules-btn" id="boardRulesBtn" type="button" title="View MDAS Rules &amp; How to Play">
          <i class="bi bi-book-half"></i> <span>MDAS Rules</span>
        </button>
      </div>

      <!-- 30-Tile Serpent Grid (6 cols x 5 rows) -->
      <div class="maze-grid" id="mazeGrid" role="grid" aria-label="Maze Grid"></div>
    </section>

    <!-- SIDEBAR -->
    <aside class="sidebar">
      <!-- Player & Live Stats Panel -->
      <div class="sidebar-panel">
        <h3>
          <span>Mission Stats</span>
          <span class="badge" style="font-size:0.75rem; background:rgba(99,102,241,0.25); color:#c4b5fd; border:1px solid rgba(99,102,241,0.4); padding:3px 8px; border-radius:50px;">Stage {{ $progress->current_level ?? 1 }}</span>
        </h3>
        <div class="player-card">
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
            $avatarEmoji = $charAvatars[auth()->user()->character_avatar ?? ''] ?? '🚀';
          @endphp
          <div class="player-avatar" id="avatarDisplay">{{ $avatarEmoji }}</div>
          <div class="player-details">
            <div class="player-name">{{ auth()->user()->name ?? 'Player' }}</div>
            <div class="player-meta">Cyber Mathematician</div>
          </div>
        </div>

        <div class="stats-grid">
          <div class="stat-box">
            <div class="stat-label">Score</div>
            <div class="stat-value score" id="scoreDisplay">0 pts</div>
          </div>
          <div class="stat-box">
            <div class="stat-label">Streak</div>
            <div class="stat-value streak" id="streakDisplay">🔥 0x</div>
          </div>
        </div>

        <!-- Progress to Finish -->
        <div class="progress-wrap">
          <div class="progress-header">
            <span>Progress to Finish</span>
            <span id="progressText">Tile 1 of 30 (0%)</span>
          </div>
          <div class="progress-bar-bg">
            <div class="progress-bar-fill" id="progressBar"></div>
          </div>
        </div>
      </div>

      <!-- 3D Cyber Dice Stage Panel (Classic 96px Size) -->
      <div class="sidebar-panel dice-panel">
        <h3>
          <span>3D Cyber Dice</span>
          <span style="font-size:0.75rem; color:#94a3b8;"><i class="bi bi-keyboard"></i> Space</span>
        </h3>
        <div class="dice-stage">
          <div class="die" id="die">
            <div class="face face-1"><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span></div>
            <div class="face face-2"><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span></div>
            <div class="face face-3"><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span></div>
            <div class="face face-4"><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span></div>
            <div class="face face-5"><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span></div>
            <div class="face face-6"><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span><span class="pip"></span></div>
          </div>
        </div>

        <button class="roll-btn" id="rollBtn" type="button">
          <i class="bi bi-dice-6-fill"></i> ROLL &amp; MOVE
        </button>
        <div class="roll-hint" id="statusHint">Press Space or click Roll to navigate the maze!</div>
      </div>
    </aside>
  </main>
</div>

<!-- ==========================================================================
     CHALLENGE MODAL WITH COIN HINT SYSTEM
     ========================================================================== */
-->
<div class="challenge-overlay" id="challengeOverlay" role="dialog" aria-modal="true" aria-labelledby="challengeBadge">
  <div class="challenge-card">
    <div class="challenge-header">
      <div class="challenge-badge" id="challengeBadge">
        <i class="bi bi-lightning-charge-fill"></i> MDAS Challenge
      </div>
      <div style="font-size:0.8rem; color:#94a3b8; font-weight:700;" id="challengePointsTag">
        +10 pts
      </div>
    </div>

    <div class="expr-box" id="exprBox" aria-live="polite">—</div>

    <div class="challenge-rule-strip">
      Rule: <b>Multiplication &amp; Division</b> first (Left to Right), then <b>Addition &amp; Subtraction</b>!
    </div>

    <!-- COIN HINT BAR -->
    <div class="coin-hint-bar" id="coinHintBar">
      <div class="coin-hint-info">
        Need help? <span>Spend 5 🪙</span> for a <b>How to Solve It</b> hint!
      </div>
      <button type="button" class="btn-coin-hint" id="btnCoinHint" onclick="useCoinHint()">
        🪙 Hint (5 🪙)
      </button>
    </div>

    <!-- REVEALED HINT BOX (Shows HOW to solve, not the answer!) -->
    <div class="hint-reveal-box" id="hintRevealBox">
      <div class="hint-reveal-title">
        <i class="bi bi-lightbulb-fill"></i> How to Solve this MDAS Expression:
      </div>
      <div id="hintRevealContent"></div>
    </div>

    <!-- ANSWER FORM -->
    <form class="challenge-form" id="answerForm" onsubmit="return false;">
      <div class="input-row">
        <input type="number" class="challenge-input" id="answerInput" inputmode="numeric" placeholder="Your Answer" autocomplete="off" aria-label="Enter numerical answer">
        <button type="submit" class="submit-btn" id="submitBtn">Check</button>
      </div>

      <!-- Quick Virtual Keypad -->
      <div class="keypad-grid">
        <button type="button" class="key-btn" onclick="pressKey('1')">1</button>
        <button type="button" class="key-btn" onclick="pressKey('2')">2</button>
        <button type="button" class="key-btn" onclick="pressKey('3')">3</button>
        <button type="button" class="key-btn" onclick="pressKey('4')">4</button>
        <button type="button" class="key-btn" onclick="pressKey('5')">5</button>
        <button type="button" class="key-btn" onclick="pressKey('6')">6</button>
        <button type="button" class="key-btn" onclick="pressKey('7')">7</button>
        <button type="button" class="key-btn" onclick="pressKey('8')">8</button>
        <button type="button" class="key-btn" onclick="pressKey('9')">9</button>
        <button type="button" class="key-btn" onclick="pressKey('0')">0</button>
      </div>
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px;">
        <button type="button" class="key-btn clear" onclick="pressKey('clear')">Clear</button>
        <button type="button" class="key-btn del" onclick="pressKey('backspace')">⌫ Delete</button>
      </div>
    </form>

    <div class="challenge-feedback" id="feedback" aria-live="polite">
      Solve the expression following the Order of Operations!
    </div>
  </div>
</div>

<!-- ==========================================================================
     FLASH CARD DECK MODAL (Instructions & In-Game Tutorial)
     ========================================================================== -->
<div id="tutorialOverlay" class="fc-overlay" role="dialog" aria-modal="true" aria-label="MDAS Maze Instructions">
  <div class="fc-deck">
    <div class="fc-deck-stack-1"></div>
    <div class="fc-deck-stack-2"></div>

    <div class="fc-card">
      <div class="fc-top">
        <span class="fc-badge" id="fcBadge">🃏 HOW TO PLAY • CARD 1 OF 5</span>
        <button class="fc-quick-action-btn" id="fcQuickActionBtn" type="button" onclick="closeTut(true)" title="Skip to Play">
          <span>Skip to Play</span> <i class="bi bi-arrow-right-short"></i>
        </button>
      </div>

      <!-- CARD 1: Mission Overview -->
      <div id="fcSlide1" class="fc-slide">
        <h3 class="fc-title" style="color:#38bdf8;"><span>🎲</span> Welcome to MDAS Maze!</h3>
        <p class="fc-desc">Roll the 3D dice, navigate the 30-tile cyber maze trail, and solve Order of Operations challenges to reach the golden Finish Portal!</p>
        <div class="fc-row-2 fc-box">
          <div class="fc-item">
            <div class="fc-item-ico">🎲</div>
            <div class="fc-item-lbl">Roll &amp; Move</div>
            <div class="fc-item-sub">Roll 1–6 and watch your token hop cell-by-cell forward!</div>
          </div>
          <div class="fc-item">
            <div class="fc-item-ico">⚡</div>
            <div class="fc-item-lbl">Solve Challenges</div>
            <div class="fc-item-sub">Land on colored tiles and solve expressions strictly using MDAS!</div>
          </div>
        </div>
        <div class="fc-tip">
          💡 <b>Keyboard Tip:</b> You can press the <b>Spacebar</b> anytime to roll the dice and move!
        </div>
      </div>

      <!-- CARD 2: The MDAS Golden Rule -->
      <div id="fcSlide2" class="fc-slide hidden">
        <h3 class="fc-title" style="color:#c084fc;"><span>🧠</span> The MDAS Golden Rule</h3>
        <p class="fc-desc">In mathematics, operations must follow a strict priority sequence:</p>
        <div class="fc-box" style="border-color:rgba(168, 85, 247, 0.4);">
          <div style="display:flex; flex-direction:column; gap:8px;">
            <div style="display:flex; align-items:center; gap:10px;">
              <span class="fc-item-badge good" style="font-size:1.0rem; padding:4px 10px;">Priority 1</span>
              <span style="font-weight:800; color:#fb7185;">Multiply (×)</span> &amp; <span style="font-weight:800; color:#38bdf8;">Divide (÷)</span>
              <span style="color:#94a3b8; font-size:0.8rem;">(Solve from Left to Right!)</span>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
              <span class="fc-item-badge" style="background:rgba(245,158,11,0.2); color:#fbbf24; font-size:1.0rem; padding:4px 10px;">Priority 2</span>
              <span style="font-weight:800; color:#34d399;">Add (+)</span> &amp; <span style="font-weight:800; color:#fbbf24;">Subtract (−)</span>
              <span style="color:#94a3b8; font-size:0.8rem;">(Solve from Left to Right!)</span>
            </div>
          </div>
        </div>
        <div class="fc-tip">
          💡 <b>Remember:</b> Always calculate Multiplication and Division <b>BEFORE</b> you touch Addition and Subtraction!
        </div>
      </div>

      <!-- CARD 3: Step-by-Step Walkthrough & Common Traps -->
      <div id="fcSlide3" class="fc-slide hidden">
        <h3 class="fc-title" style="color:#34d399;"><span>🔍</span> Step-by-Step Walkthrough</h3>
        <p class="fc-desc">Let's walk through an example expression: <b style="color:#fff;">10 − 2 × 4 + 3</b></p>
        <div class="fc-box" style="font-size:0.88rem; line-height:1.6;">
          <div><b style="color:#fb7185;">Step 1 (Multiply first):</b> <code>2 × 4 = 8</code> ➔ The problem is now <code>10 − 8 + 3</code></div>
          <div><b style="color:#34d399;">Step 2 (Left-to-Right Add/Sub):</b> <code>10 − 8 = 2</code></div>
          <div><b style="color:#fbbf24;">Step 3 (Final addition):</b> <code>2 + 3 = 5</code> ➔ <b>Correct Answer is 5!</b></div>
        </div>
        <div class="fc-tip" style="background:rgba(244,63,94,0.12); border-color:#f43f5e; color:#fecdd3;">
          ⚠️ <b>Common Trap:</b> If you mistakenly did <code>10 − 2 = 8</code> first, you would get <code>8 × 4 = 32 + 3 = 35</code>, which is <b>INCORRECT</b>!
        </div>
      </div>

      <!-- CARD 4: Coin Hints ("How to Solve It") -->
      <div id="fcSlide4" class="fc-slide hidden">
        <h3 class="fc-title" style="color:#fbbf24;"><span>🪙</span> Coin Hints — How to Solve It!</h3>
        <p class="fc-desc">Stuck on a tricky problem? Spend <b>5 🪙 Coins</b> to get instant guidance!</p>
        <div class="fc-row-2 fc-box">
          <div class="fc-item">
            <div class="fc-item-ico">💡</div>
            <div class="fc-item-lbl">Never Gives the Answer</div>
            <div class="fc-item-sub">It explains <b>which operation to do first</b> and simplifies step 1!</div>
          </div>
          <div class="fc-item">
            <div class="fc-item-ico">🪙</div>
            <div class="fc-item-lbl">Earn Coins Easily</div>
            <div class="fc-item-sub">Earn coins for correct answers, streak combos, and clearing levels!</div>
          </div>
        </div>
        <div class="fc-tip">
          💡 You start with <b>50 Coins</b>! Use them wisely when facing complex multi-operation tiles.
        </div>
      </div>

      <!-- CARD 5: Board Zones & Controls -->
      <div id="fcSlide5" class="fc-slide hidden">
        <h3 class="fc-title" style="color:#f43f5e;"><span>🏆</span> Zones, Scoring &amp; Controls</h3>
        <p class="fc-desc">Master the 30 board tiles and clear all 10 stages:</p>
        <div class="fc-row-2 fc-box">
          <div class="fc-item">
            <div class="fc-item-lbl" style="color:#fb7185;">🔴 Red Tile: (×) (+)</div>
            <div class="fc-item-sub">Multiply before Adding (+10 pts)</div>
          </div>
          <div class="fc-item">
            <div class="fc-item-lbl" style="color:#38bdf8;">🔵 Blue Tile: (÷) (−)</div>
            <div class="fc-item-sub">Divide before Subtracting (+10 pts)</div>
          </div>
          <div class="fc-item">
            <div class="fc-item-lbl" style="color:#34d399;">🟢 Green: (×) (÷)</div>
            <div class="fc-item-sub">Equal priority! Left-to-right!</div>
          </div>
          <div class="fc-item">
            <div class="fc-item-lbl" style="color:#c084fc;">🟣 Gold: All Four</div>
            <div class="fc-item-sub">Master challenge (+20 pts &amp; bonus coins!)</div>
          </div>
        </div>
        <div class="fc-tip">
          💡 <b>Keyboard Shortcuts:</b> <b>Space</b> to Roll • <b>Enter</b> to Submit • <b>P / Esc</b> to Pause • <b>M</b> to Mute.
        </div>
      </div>

      <!-- Footer Navigation -->
      <div class="fc-footer">
        <button class="fc-nav-btn" id="fcPrevBtn" type="button" onclick="stepFlashCard(-1)">
          <i class="bi bi-arrow-left"></i> Prev
        </button>

        <div class="fc-dots" id="fcDots" role="tablist" aria-label="Tutorial Cards">
          <button class="fc-dot active" type="button" onclick="goFlashCard(1)" title="Card 1"></button>
          <button class="fc-dot" type="button" onclick="goFlashCard(2)" title="Card 2"></button>
          <button class="fc-dot" type="button" onclick="goFlashCard(3)" title="Card 3"></button>
          <button class="fc-dot" type="button" onclick="goFlashCard(4)" title="Card 4"></button>
          <button class="fc-dot" type="button" onclick="goFlashCard(5)" title="Card 5"></button>
        </div>

        <button class="fc-nav-btn primary" id="fcNextBtn" type="button" onclick="stepFlashCard(1)">
          <span>Next</span> <i class="bi bi-arrow-right"></i>
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ============ PAUSE OVERLAY ============ -->
<div class="pause-overlay" id="pauseOverlay">
  <div class="pause-box">
    <h2>Game Paused</h2>
    <p>Take a breather! Ready to jump back into the MDAS Maze?</p>
    <button class="resume-btn" id="resumeBtn" type="button" onclick="togglePause()">Resume Game</button>
    <div class="eduplay-game-nav" style="display:flex;gap:10px;width:100%;justify-content:center;margin-top:14px;">
      <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" style="flex:1;justify-content:center;">
        <i class="bi bi-map-fill"></i> <span>Game Map</span>
      </a>
      <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit" style="flex:1;justify-content:center;">
        <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
      </a>
    </div>
  </div>
</div>

<!-- ============ WINNER OVERLAY ============ -->
<div class="winner-overlay" id="winnerOverlay">
  <div class="winner-card" id="winnerCard"></div>
</div>

<script>
(function(){
  "use strict";

  /* ==========================================================================
     GLOBAL CONFIG & CONSTANTS
     ========================================================================== */
  const CURRENT_LEVEL = {{ $progress->current_level ?? 1 }};
  const GAME_ID = {{ $game->id ?? 0 }};
  const CSRF_TOKEN = '{{ csrf_token() }}';
  const MAX_LEVELS = 10;
  const USER_ID = '{{ auth()->id() ?? 0 }}';

  const COINS_KEY = 'eduplay_mdas_coins_' + USER_ID;
  const SOUND_KEY = 'eduplay_mdas_muted_' + USER_ID;

  /* 30 Tiles Configuration: 6 columns x 5 rows */
  const COLS = 6, ROWS = 5, BOARD_SIZE = 30; // Exactly 30 tiles
  const ZONES = ["red", "blue", "green", "gold"];
  const ZONE_CONFIG = {
    red:   { label: "× +", name: "Multiplication & Addition" },
    blue:  { label: "÷ −", name: "Division & Subtraction" },
    green: { label: "× ÷", name: "Multiplication & Division" },
    gold:  { label: "MDAS", name: "MDAS Master Challenge" }
  };

  const FACE_TARGET = {
    1:{x:0,y:0}, 2:{x:0,y:270}, 3:{x:0,y:180}, 4:{x:0,y:90}, 5:{x:270,y:0}, 6:{x:90,y:0}
  };

  /* ==========================================================================
     FLOATING MATH SIGNS (Prominent, Visible Immediately across Screen)
     ========================================================================== */
  (function initFloatingMathSigns(){
    const syms = [
      '÷','×','+','−','=','MDAS','( )','12 ÷ 3 = 4','7 × 8 = 56','4 × 5 = 20',
      '15 − 5 = 10','9 + 6 = 15','24 ÷ 6 = 4','36 ÷ 9 = 4','6 × 7 = 42','8 + 12 = 20',
      'π','∑','√','²','³','%','±','3 × 3 = 9','8 ÷ 2 = 4','10 + 5 = 15','20 − 8 = 12',
      'a² + b² = c²','x² − 4 = 0','lim x→0','≠','≈','≤','≥','∆','∫'
    ];
    const colors = ['#818cf8','#60a5fa','#34d399','#fbbf24','#f87171','#a78bfa','#38bdf8','#ec4899','#c084fc'];
    const layer = document.getElementById('sym-layer');
    if(!layer) return;

    for(let i = 0; i < 65; i++){
      const s = document.createElement('span');
      s.className = 'sym';
      const t = syms[Math.floor(Math.random() * syms.length)];
      s.textContent = t;
      s.style.left = (Math.random() * 96 + 2) + 'vw';
      s.style.top = (Math.random() * 95) + 'vh';
      s.style.fontSize = (t.length > 3 ? 1.0 + Math.random() * 0.6 : 1.3 + Math.random() * 1.8) + 'rem';
      s.style.animationDelay = -(Math.random() * 20) + 's';
      s.style.animationDuration = (12 + Math.random() * 14) + 's';
      s.style.color = colors[Math.floor(Math.random() * colors.length)];
      layer.appendChild(s);
    }
  })();

  /* ==========================================================================
     WEB AUDIO SYNTHESIZER
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

  function soundRoll() {
    if (isMuted) return;
    for (let i = 0; i < 6; i++) {
      setTimeout(() => {
        playTone(180 + Math.random() * 120, 0.08, 'triangle', 0.1);
      }, i * 90);
    }
  }

  function soundHop() {
    playTone(420, 0.12, 'sine', 0.2);
  }

  function soundCoin() {
    playTone(987, 0.1, 'triangle', 0.25);
    setTimeout(() => playTone(1318, 0.2, 'triangle', 0.25), 100);
  }

  function soundCorrect() {
    playTone(523.25, 0.1, 'sine', 0.2);
    setTimeout(() => playTone(659.25, 0.1, 'sine', 0.2), 90);
    setTimeout(() => playTone(783.99, 0.15, 'sine', 0.25), 180);
    setTimeout(() => playTone(1046.50, 0.35, 'sine', 0.3), 270);
  }

  function soundWrong() {
    playTone(280, 0.18, 'sawtooth', 0.15);
    setTimeout(() => playTone(220, 0.25, 'sawtooth', 0.15), 150);
  }

  function soundWin() {
    const notes = [523, 659, 783, 1046, 1318];
    notes.forEach((f, idx) => {
      setTimeout(() => playTone(f, 0.25, 'triangle', 0.25), idx * 120);
    });
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
    player.coins = Math.max(0, newAmount);
    localStorage.setItem(COINS_KEY, player.coins.toString());
    updateCoinsUI();
  }

  function updateCoinsUI() {
    const coinEl = document.getElementById('coinDisplay');
    if (coinEl) coinEl.textContent = player.coins;
    const btnHint = document.getElementById('btnCoinHint');
    if (btnHint) {
      if (player.coins < 5) {
        btnHint.disabled = true;
        btnHint.title = 'Need 5 coins for hint';
      } else {
        btnHint.disabled = false;
        btnHint.title = 'Spend 5 coins for a How to Solve It hint';
      }
    }
  }

  /* ==========================================================================
     GAME STATE
     ========================================================================== */
  var player = {
    position: 0,
    score: 0,
    streak: 0,
    coins: getStoredCoins(),
    color: '#f59e0b',
    avatar: '{{ $avatarEmoji }}'
  };

  var boardTiles = [];
  var gameActive = true;
  var isPaused = false;
  var busy = false;
  var awaitingAnswer = false;
  var currentChallenge = null;
  var rotX = 0, rotY = 0;
  var hintRevealed = false;

  /* ==========================================================================
     EXPRESSION GENERATOR (Strict MDAS & Clean Math)
     ========================================================================== */
  function generateMDASExpression(type) {
    const maxVal = Math.min(24, CURRENT_LEVEL * 2 + 5);

    while (true) {
      if (type === 'gold') {
        const goldPattern = Math.floor(Math.random() * 3);

        if (goldPattern === 0) {
          const b = Math.floor(Math.random() * 6) + 2;
          const c = Math.floor(Math.random() * 4) + 2;
          const mult = Math.floor(Math.random() * 4) + 1;
          const a = (c * mult) / gcd(b, c);
          const d = Math.floor(Math.random() * maxVal) + 2;
          const e = Math.floor(Math.random() * 8) + 1;

          const p1 = (a * b) / c;
          const ans = p1 + d - e;
          if (ans > 0 && Number.isInteger(ans) && ans < 300) {
            const exprDisplay = `${a} × ${b} ÷ ${c} + ${d} − ${e}`;
            return {
              expr: exprDisplay,
              answer: ans,
              zone: type,
              firstOp: `${a} × ${b}`,
              firstOpResult: a * b,
              howToSolve: `
                <div><b>1️⃣ Priority 1 (Left to Right):</b> Multiplication and Division share top priority. Solve from left to right!</div>
                <div style="margin-top:4px;">👉 Multiply first: <b>${a} × ${b} = ${a * b}</b></div>
                <div>👉 Next, divide by ${c}: <b>${a * b} ÷ ${c} = ${p1}</b></div>
                <div style="margin-top:4px;"><b>2️⃣ Priority 2 (Add &amp; Subtract):</b> The expression is now <code>${p1} + ${d} − ${e}</code>.</div>
                <div>👉 Calculate <b>${p1} + ${d}</b>, then subtract <b>${e}</b> to find your final answer!</div>
              `
            };
          }
        } else if (goldPattern === 1) {
          const c = Math.floor(Math.random() * 6) + 2;
          const b = Math.floor(Math.random() * 6) + 2;
          const e = Math.floor(Math.random() * 4) + 2;
          const divMult = Math.floor(Math.random() * 4) + 1;
          const d = e * divMult;
          const a = Math.floor(Math.random() * 10) + 1;

          const multVal = b * c;
          const divVal = d / e;
          const ans = a + multVal - divVal;
          if (ans > 0 && Number.isInteger(ans) && ans < 300) {
            const exprDisplay = `${a} + ${b} × ${c} − ${d} ÷ ${e}`;
            return {
              expr: exprDisplay,
              answer: ans,
              zone: type,
              firstOp: `${b} × ${c} & ${d} ÷ ${e}`,
              firstOpResult: multVal,
              howToSolve: `
                <div><b>1️⃣ Priority 1 (Multiply &amp; Divide):</b> Solve × and ÷ before + and −!</div>
                <div style="margin-top:4px;">👉 Calculate <b>${b} × ${c} = ${multVal}</b></div>
                <div>👉 Calculate <b>${d} ÷ ${e} = ${divVal}</b></div>
                <div style="margin-top:4px;"><b>2️⃣ Priority 2 (Add &amp; Subtract):</b> The problem simplifies to <code>${a} + ${multVal} − ${divVal}</code>.</div>
                <div>👉 Now add and subtract from left to right to get your answer!</div>
              `
            };
          }
        } else {
          const c = Math.floor(Math.random() * 5) + 2;
          const d = Math.floor(Math.random() * 4) + 2;
          const mult = Math.floor(Math.random() * 3) + 1;
          const b = (d * mult) / gcd(c, d);
          const p1 = (b * c) / d;
          const a = p1 + Math.floor(Math.random() * 10) + 2;
          const e = Math.floor(Math.random() * 10) + 1;

          const ans = a - p1 + e;
          if (ans > 0 && Number.isInteger(ans) && ans < 300) {
            const exprDisplay = `${a} − ${b} × ${c} ÷ ${d} + ${e}`;
            return {
              expr: exprDisplay,
              answer: ans,
              zone: type,
              firstOp: `${b} × ${c}`,
              firstOpResult: b * c,
              howToSolve: `
                <div><b>1️⃣ Priority 1:</b> Do multiplication and division first!</div>
                <div style="margin-top:4px;">👉 Multiply: <b>${b} × ${c} = ${b * c}</b></div>
                <div>👉 Divide by ${d}: <b>${b * c} ÷ ${d} = ${p1}</b></div>
                <div style="margin-top:4px;"><b>2️⃣ Priority 2:</b> Now solve <code>${a} − ${p1} + ${e}</code> from left to right!</div>
              `
            };
          }
        }
      } else if (type === 'red') {
        const isFirst = Math.random() < 0.5;
        const b = Math.floor(Math.random() * maxVal) + 2;
        const c = Math.floor(Math.random() * 8) + 2;
        const a = Math.floor(Math.random() * maxVal) + 2;

        if (isFirst) {
          const ans = a * b + c;
          if (ans < 250) {
            return {
              expr: `${a} × ${b} + ${c}`,
              answer: ans,
              zone: type,
              firstOp: `${a} × ${b}`,
              firstOpResult: a * b,
              howToSolve: `
                <div><b>1️⃣ MDAS Rule:</b> Multiplication comes BEFORE Addition!</div>
                <div style="margin-top:4px;">👉 Calculate <b>${a} × ${b} = ${a * b}</b> first.</div>
                <div style="margin-top:4px;">👉 Then add ${c}: <b>${a * b} + ${c} = ?</b></div>
              `
            };
          }
        } else {
          const ans = a + b * c;
          if (ans < 250) {
            return {
              expr: `${a} + ${b} × ${c}`,
              answer: ans,
              zone: type,
              firstOp: `${b} × ${c}`,
              firstOpResult: b * c,
              howToSolve: `
                <div><b>1️⃣ MDAS Rule:</b> Multiplication has higher priority than Addition!</div>
                <div style="margin-top:4px;">👉 Do NOT add ${a} + ${b} first!</div>
                <div>👉 Instead, solve <b>${b} × ${c} = ${b * c}</b> first.</div>
                <div style="margin-top:4px;">👉 Finally, calculate <b>${a} + ${b * c} = ?</b></div>
              `
            };
          }
        }
      } else if (type === 'blue') {
        const isFirst = Math.random() < 0.5;
        const c = Math.floor(Math.random() * 6) + 2;
        const mult = Math.floor(Math.random() * 6) + 2;
        const b = c * mult;

        if (isFirst) {
          const divVal = b / c;
          const d = Math.floor(Math.random() * (divVal - 1)) + 1;
          const ans = divVal - d;
          if (ans > 0) {
            return {
              expr: `${b} ÷ ${c} − ${d}`,
              answer: ans,
              zone: type,
              firstOp: `${b} ÷ ${c}`,
              firstOpResult: divVal,
              howToSolve: `
                <div><b>1️⃣ MDAS Rule:</b> Division comes BEFORE Subtraction!</div>
                <div style="margin-top:4px;">👉 Divide first: <b>${b} ÷ ${c} = ${divVal}</b>.</div>
                <div style="margin-top:4px;">👉 Then subtract ${d}: <b>${divVal} − ${d} = ?</b></div>
              `
            };
          }
        } else {
          const divVal = b / c;
          const a = divVal + Math.floor(Math.random() * 12) + 2;
          const ans = a - divVal;
          if (ans > 0) {
            return {
              expr: `${a} − ${b} ÷ ${c}`,
              answer: ans,
              zone: type,
              firstOp: `${b} ÷ ${c}`,
              firstOpResult: divVal,
              howToSolve: `
                <div><b>1️⃣ MDAS Rule:</b> Division has higher priority than Subtraction!</div>
                <div style="margin-top:4px;">👉 Do NOT calculate ${a} − ${b} first!</div>
                <div>👉 First, divide: <b>${b} ÷ ${c} = ${divVal}</b>.</div>
                <div style="margin-top:4px;">👉 Now calculate: <b>${a} − ${divVal} = ?</b></div>
              `
            };
          }
        }
      } else {
        const isMultFirst = Math.random() < 0.5;
        if (isMultFirst) {
          const b = Math.floor(Math.random() * 6) + 2;
          const c = Math.floor(Math.random() * 4) + 2;
          const mult = Math.floor(Math.random() * 5) + 1;
          const a = (c * mult) / gcd(b, c);
          const ans = (a * b) / c;
          if (ans > 0 && Number.isInteger(ans) && ans < 150) {
            return {
              expr: `${a} × ${b} ÷ ${c}`,
              answer: ans,
              zone: type,
              firstOp: `${a} × ${b}`,
              firstOpResult: a * b,
              howToSolve: `
                <div><b>1️⃣ MDAS Rule:</b> Multiplication and Division have EQUAL priority!</div>
                <div style="margin-top:4px;">👉 When both appear, solve strictly from <b>LEFT to RIGHT</b>.</div>
                <div style="margin-top:4px;">👉 Leftmost operation is: <b>${a} × ${b} = ${a * b}</b>.</div>
                <div>👉 Finally, divide: <b>${a * b} ÷ ${c} = ?</b></div>
              `
            };
          }
        } else {
          const b = Math.floor(Math.random() * 5) + 2;
          const mult = Math.floor(Math.random() * 6) + 2;
          const a = b * mult;
          const c = Math.floor(Math.random() * 6) + 2;
          const divVal = a / b;
          const ans = divVal * c;
          if (ans > 0 && Number.isInteger(ans) && ans < 150) {
            return {
              expr: `${a} ÷ ${b} × ${c}`,
              answer: ans,
              zone: type,
              firstOp: `${a} ÷ ${b}`,
              firstOpResult: divVal,
              howToSolve: `
                <div><b>1️⃣ MDAS Rule:</b> Division and Multiplication have EQUAL priority!</div>
                <div style="margin-top:4px;">👉 Solve strictly from <b>LEFT to RIGHT</b>.</div>
                <div style="margin-top:4px;">👉 Leftmost operation is Division: <b>${a} ÷ ${b} = ${divVal}</b>.</div>
                <div>👉 Now multiply: <b>${divVal} × ${c} = ?</b></div>
              `
            };
          }
        }
      }
    }
  }

  function gcd(a, b) {
    return b === 0 ? a : gcd(b, a % b);
  }

  /* ==========================================================================
     BOARD INITIALIZATION & RENDERING (30 TILES, 6 COLS x 5 ROWS)
     ========================================================================== */
  const mazeGrid = document.getElementById("mazeGrid");
  const scoreDisplay = document.getElementById("scoreDisplay");
  const streakDisplay = document.getElementById("streakDisplay");
  const progressBar = document.getElementById("progressBar");
  const progressText = document.getElementById("progressText");
  const dieEl = document.getElementById("die");
  const rollBtn = document.getElementById("rollBtn");
  const statusHint = document.getElementById("statusHint");

  function buildBoard() {
    boardTiles = [];
    for (var i = 0; i < BOARD_SIZE; i++) {
      var type;
      if (i === 0) type = "start";
      else if (i === BOARD_SIZE - 1) type = "finish";
      else if (i === 5 || i === 12 || i === 23) type = "neutral";
      else {
        if (i % 7 === 0 && i !== 0 && i !== 29) type = "gold";
        else {
          const zoneIdx = (i + CURRENT_LEVEL) % 3;
          type = ZONES[zoneIdx];
        }
      }
      boardTiles.push(type);
    }

    mazeGrid.innerHTML = "";
    for (var idx = 0; idx < BOARD_SIZE; idx++) {
      var row = Math.floor(idx / COLS);
      var colInRow = idx % COLS;
      var col = (row % 2 === 0) ? colInRow : (COLS - 1 - colInRow);
      var cell = document.createElement("div");
      var type2 = boardTiles[idx];
      cell.className = "cell " + type2;
      cell.setAttribute("data-index", idx);
      cell.style.gridRowStart = row + 1;
      cell.style.gridColumnStart = col + 1;

      // Cell Number Badge
      var num = document.createElement("span");
      num.className = "cell-num";
      num.textContent = (idx + 1);
      cell.appendChild(num);

      // Label
      var label = document.createElement("span");
      label.className = "op-label";
      var sub = document.createElement("span");
      sub.className = "op-sub";

      if (type2 === "start") {
        label.innerHTML = "<i class='bi bi-play-fill'></i> START";
        sub.textContent = "Portal";
      } else if (type2 === "finish") {
        label.innerHTML = "<i class='bi bi-trophy-fill'></i> FINISH";
        sub.textContent = "Victory";
      } else if (type2 === "neutral") {
        label.innerHTML = "<i class='bi bi-cup-hot-fill'></i> REST";
        sub.textContent = "Safe Spot";
      } else if (type2 === "gold") {
        label.innerHTML = "★ MDAS";
        sub.textContent = "Master";
      } else {
        label.textContent = ZONE_CONFIG[type2].label;
        sub.textContent = type2 === "red" ? "× and +" : (type2 === "blue" ? "÷ and −" : "× and ÷");
      }
      cell.appendChild(label);
      cell.appendChild(sub);

      // Directional Flow Indicator
      var dirIco = document.createElement("span");
      dirIco.className = "cell-dir-indicator";
      if (idx < BOARD_SIZE - 1) {
        var nextRow = Math.floor((idx + 1) / COLS);
        if (nextRow !== row) dirIco.innerHTML = "<i class='bi bi-arrow-down-short'></i>";
        else if (row % 2 === 0) dirIco.innerHTML = "<i class='bi bi-arrow-right-short'></i>";
        else dirIco.innerHTML = "<i class='bi bi-arrow-left-short'></i>";
      }
      cell.appendChild(dirIco);

      var well = document.createElement("div");
      well.className = "token-well";
      cell.appendChild(well);
      mazeGrid.appendChild(cell);
    }

    highlightActiveTile(player.position);
  }

  function highlightActiveTile(index) {
    document.querySelectorAll(".cell").forEach(c => c.classList.remove("active-tile"));
    var cell = mazeGrid.querySelector('.cell[data-index="' + index + '"]');
    if (cell) cell.classList.add("active-tile");
  }

  function cellWell(index) {
    var cell = mazeGrid.querySelector('.cell[data-index="' + index + '"]');
    return cell ? cell.querySelector(".token-well") : null;
  }

  function renderToken() {
    var well = cellWell(player.position);
    if (!well) return;
    var tok = document.createElement("span");
    tok.className = "token";
    tok.id = "playerToken";
    tok.textContent = player.avatar || "🚀";
    well.appendChild(tok);
  }

  function moveTokenTo(index, hop) {
    var old = document.getElementById("playerToken");
    if (old) old.remove();
    var well = cellWell(index);
    if (!well) return;
    var tok = document.createElement("span");
    tok.className = "token" + (hop ? " hop" : "");
    tok.id = "playerToken";
    tok.textContent = player.avatar || "🚀";
    well.appendChild(tok);
    highlightActiveTile(index);
    if (hop) soundHop();
  }

  function updateStatsUI() {
    scoreDisplay.textContent = player.score + " pts";
    streakDisplay.textContent = "🔥 " + player.streak + "x";

    var pct = Math.round((player.position / (BOARD_SIZE - 1)) * 100);
    progressBar.style.width = pct + "%";
    progressText.textContent = "Tile " + (player.position + 1) + " of " + BOARD_SIZE + " (" + pct + "%)";
  }

  /* ==========================================================================
     DICE ROLLING PHYSICS (Classic 96px Size)
     ========================================================================== */
  function mod360(v){ return ((v % 360) + 360) % 360; }

  function rollDie(callback) {
    soundRoll();
    var value = Math.floor(Math.random() * 6) + 1;
    var target = FACE_TARGET[value];
    var curXNorm = mod360(rotX), curYNorm = mod360(rotY);
    var deltaX = mod360(target.x - curXNorm);
    var deltaY = mod360(target.y - curYNorm);
    var spins = 2 + Math.floor(Math.random() * 2);
    rotX += spins * 360 + deltaX;
    rotY += spins * 360 + deltaY;
    dieEl.style.transition = "transform 1.05s cubic-bezier(.22,1.6,.36,1)";
    dieEl.style.transform = "rotateX(" + rotX + "deg) rotateY(" + rotY + "deg)";
    setTimeout(function(){ callback(value); }, 1080);
  }

  rollBtn.addEventListener("click", triggerRoll);

  function triggerRoll() {
    if (!gameActive || busy || awaitingAnswer || isPaused || tutorialOpen) return;
    initAudio();
    busy = true;
    rollBtn.disabled = true;
    statusHint.textContent = "Rolling the 3D dice…";

    rollDie(function(value){
      statusHint.textContent = "You rolled a " + value + "! Advancing…";
      stepPlayer(value);
    });
  }

  function stepPlayer(stepsLeft) {
    if (stepsLeft <= 0 || player.position >= BOARD_SIZE - 1) {
      afterMove();
      return;
    }
    player.position = Math.min(player.position + 1, BOARD_SIZE - 1);
    moveTokenTo(player.position, true);
    updateStatsUI();

    if (player.position >= BOARD_SIZE - 1) {
      setTimeout(function(){ afterMove(); }, 200);
      return;
    }
    setTimeout(function(){ stepPlayer(stepsLeft - 1); }, 280);
  }

  /* ==========================================================================
     AFTER MOVE & CHALLENGE LOGIC
     ========================================================================== */
  function afterMove() {
    if (player.position >= BOARD_SIZE - 1) {
      player.score += 30;
      const finishCoins = 25 + (player.streak * 5);
      saveCoins(player.coins + finishCoins);
      updateStatsUI();
      gameActive = false;
      rollBtn.disabled = true;

      soundWin();
      saveProgress(CURRENT_LEVEL + 1, player.score);
      showWinnerOverlay(finishCoins);
      busy = false;
      return;
    }

    var type = boardTiles[player.position];
    if (type === "neutral") {
      statusHint.textContent = "☕ Safe Rest Tile! Take a breath and roll again.";
      busy = false;
      setTimeout(function(){ rollBtn.disabled = false; }, 800);
      return;
    }

    openChallenge(type);
  }

  const challengeOverlay = document.getElementById("challengeOverlay");
  const challengeBadge = document.getElementById("challengeBadge");
  const challengePointsTag = document.getElementById("challengePointsTag");
  const exprBox = document.getElementById("exprBox");
  const answerForm = document.getElementById("answerForm");
  const answerInput = document.getElementById("answerInput");
  const submitBtn = document.getElementById("submitBtn");
  const feedback = document.getElementById("feedback");
  const btnCoinHint = document.getElementById("btnCoinHint");
  const hintRevealBox = document.getElementById("hintRevealBox");
  const hintRevealContent = document.getElementById("hintRevealContent");

  function openChallenge(type) {
    currentChallenge = generateMDASExpression(type);
    hintRevealed = false;
    hintRevealBox.classList.remove("active");
    hintRevealContent.innerHTML = "";

    var formattedExpr = currentChallenge.expr
      .replace(/×/g, '<span class="op-m">×</span>')
      .replace(/÷/g, '<span class="op-d">÷</span>')
      .replace(/\+/g, '<span class="op-a">+</span>')
      .replace(/−/g, '<span class="op-s">−</span>');

    exprBox.innerHTML = formattedExpr;

    if (type === 'gold') {
      challengeBadge.innerHTML = '<i class="bi bi-star-fill text-warning"></i> MDAS Master Challenge';
      challengeBadge.style.color = '#c084fc';
      challengeBadge.style.borderColor = 'rgba(192, 132, 252, 0.4)';
      challengePointsTag.textContent = '+20 pts + Bonus 🪙';
    } else {
      challengeBadge.innerHTML = '<i class="bi bi-lightning-charge-fill"></i> ' + ZONE_CONFIG[type].name;
      challengeBadge.style.color = '#38bdf8';
      challengeBadge.style.borderColor = 'rgba(56, 189, 248, 0.3)';
      challengePointsTag.textContent = '+10 pts';
    }

    feedback.className = "challenge-feedback";
    feedback.textContent = "Calculate the expression following the Order of Operations!";
    answerInput.value = "";
    answerInput.disabled = false;
    submitBtn.disabled = false;
    awaitingAnswer = true;
    busy = false;

    updateCoinsUI();
    challengeOverlay.classList.add("active");
    setTimeout(function(){ answerInput.focus(); }, 120);
  }

  /* Coin Hint Functionality */
  window.useCoinHint = function() {
    if (!currentChallenge || hintRevealed) return;
    if (player.coins < 5) {
      feedback.className = "challenge-feedback bad";
      feedback.textContent = "⚠️ Not enough coins! You need 5 🪙 for a hint.";
      return;
    }

    saveCoins(player.coins - 5);
    soundCoin();
    hintRevealed = true;
    hintRevealContent.innerHTML = currentChallenge.howToSolve;
    hintRevealBox.classList.add("active");

    feedback.className = "challenge-feedback";
    feedback.innerHTML = "💡 <b>Hint Unlocked:</b> Follow the prioritized step shown above, then calculate your answer!";
  };

  /* Keypad Interaction */
  window.pressKey = function(key) {
    if (!awaitingAnswer) return;
    if (key === 'clear') {
      answerInput.value = '';
    } else if (key === 'backspace') {
      answerInput.value = answerInput.value.slice(0, -1);
    } else {
      answerInput.value += key;
    }
    answerInput.focus();
  };

  answerForm.addEventListener("submit", function(e){
    e.preventDefault();
    if (!awaitingAnswer || !currentChallenge) return;

    var valStr = answerInput.value.trim();
    if (valStr === "") {
      feedback.className = "challenge-feedback bad";
      feedback.textContent = "Please enter your answer before checking.";
      return;
    }

    var userVal = parseInt(valStr, 10);
    if (isNaN(userVal)) {
      feedback.className = "challenge-feedback bad";
      feedback.textContent = "Please enter a valid whole number.";
      return;
    }

    var isCorrect = (userVal === currentChallenge.answer);
    awaitingAnswer = false;
    answerInput.disabled = true;
    submitBtn.disabled = true;

    if (isCorrect) {
      soundCorrect();
      player.streak++;
      var earnedPoints = (currentChallenge.zone === 'gold' ? 20 : 10) + (player.streak > 1 ? 5 : 0);
      var earnedCoins = (currentChallenge.zone === 'gold' ? 8 : 4) + (player.streak >= 3 ? 3 : 0);

      player.score += earnedPoints;
      saveCoins(player.coins + earnedCoins);
      updateStatsUI();

      feedback.className = "challenge-feedback good";
      feedback.innerHTML = `
        🎉 <b>Correct! Amazing job!</b><br>
        <span>+${earnedPoints} pts &bull; +${earnedCoins} 🪙 Coins earned!</span>
      `;

      setTimeout(function(){
        closeChallengeModal();
        statusHint.textContent = "Great work! Roll the dice to continue.";
        rollBtn.disabled = false;
      }, 1600);
    } else {
      soundWrong();
      player.streak = 0;
      updateStatsUI();

      feedback.className = "challenge-feedback bad";
      feedback.innerHTML = `
        ❌ <b>Not quite! The correct answer was ${currentChallenge.answer}.</b>
        <div class="step-breakdown">
          <div><b>Here is how to solve it with MDAS:</b></div>
          ${currentChallenge.howToSolve}
        </div>
      `;

      setTimeout(function(){
        closeChallengeModal();
        statusHint.textContent = "Review the rule and try your best on the next roll!";
        rollBtn.disabled = false;
      }, 3400);
    }
  });

  function closeChallengeModal() {
    challengeOverlay.classList.remove("active");
    answerInput.value = "";
    currentChallenge = null;
    hintRevealed = false;
    hintRevealBox.classList.remove("active");
  }

  /* ==========================================================================
     FLASH CARD TUTORIAL CONTROLLER
     ========================================================================== */
  var tutCard = 1;
  const TUT_TOTAL = 5;
  const tutOverlay = document.getElementById("tutorialOverlay");

  var tutorialOpen = false; // tracks whether tutorial overlay is blocking play

  function openTut(fromGame = false) {
    tutCard = 1;
    tutorialOpen = true;
    renderFlashCard(tutCard);
    tutOverlay.classList.add("active");
    // Block rolling while tutorial is open
    if (rollBtn) rollBtn.disabled = true;
  }
  window.openTut = openTut;

  function closeTut(markAsDone = false) {
    tutorialOpen = false;
    tutOverlay.classList.remove("active");
    // Re-enable rolling only if the game is in a rollable state
    if (rollBtn && gameActive && !isPaused && !busy && !awaitingAnswer) {
      rollBtn.disabled = false;
    }
  }
  window.closeTut = closeTut;

  function goFlashCard(cardNum) {
    tutCard = Math.max(1, Math.min(TUT_TOTAL, cardNum));
    renderFlashCard(tutCard);
  }
  window.goFlashCard = goFlashCard;

  function stepFlashCard(delta) {
    var target = tutCard + delta;
    if (target < 1) target = 1;
    if (target > TUT_TOTAL) {
      closeTut(true);
      return;
    }
    tutCard = target;
    renderFlashCard(tutCard);
  }
  window.stepFlashCard = stepFlashCard;

  function renderFlashCard(num) {
    for (var i = 1; i <= TUT_TOTAL; i++) {
      var slide = document.getElementById("fcSlide" + i);
      if (slide) slide.classList.toggle("hidden", i !== num);
    }

    var badge = document.getElementById("fcBadge");
    if (badge) badge.textContent = "🃏 HOW TO PLAY • CARD " + num + " OF " + TUT_TOTAL;

    var dots = document.querySelectorAll(".fc-dot");
    dots.forEach((dot, idx) => {
      dot.classList.toggle("active", (idx + 1) === num);
    });

    var prevBtn = document.getElementById("fcPrevBtn");
    var nextBtn = document.getElementById("fcNextBtn");
    if (prevBtn) prevBtn.style.visibility = (num === 1) ? "hidden" : "visible";
    if (nextBtn) {
      if (num === TUT_TOTAL) {
        nextBtn.innerHTML = '<span>Start Playing!</span> <i class="bi bi-play-fill"></i>';
      } else {
        nextBtn.innerHTML = '<span>Next</span> <i class="bi bi-arrow-right"></i>';
      }
    }
  }

  document.getElementById("tutorialBtn").addEventListener("click", function(){ openTut(true); });
  document.getElementById("boardRulesBtn").addEventListener("click", function(){ openTut(true); });

  // Always show instructions when the game loads — every visit
  setTimeout(function(){ openTut(false); }, 300);

  /* ==========================================================================
     PAUSE SYSTEM
     ========================================================================== */
  const pauseOverlay = document.getElementById("pauseOverlay");
  const topPauseBtn = document.getElementById("topPauseBtn");
  var wasBusy = false, wasAwaiting = false;

  function togglePause() {
    if (!gameActive) return;
    isPaused = !isPaused;

    if (isPaused) {
      wasBusy = busy;
      wasAwaiting = awaitingAnswer;
      busy = true;
      pauseOverlay.classList.add("active");
      if (topPauseBtn) {
        topPauseBtn.classList.add("paused");
        topPauseBtn.innerHTML = '<i class="bi bi-play-fill"></i> <span>Resume</span>';
      }
    } else {
      pauseOverlay.classList.remove("active");
      if (topPauseBtn) {
        topPauseBtn.classList.remove("paused");
        topPauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
      }
      busy = wasBusy;
      if (wasAwaiting) {
        answerInput.disabled = false;
        submitBtn.disabled = false;
        answerInput.focus();
      }
    }
  }
  window.togglePause = togglePause;
  topPauseBtn.addEventListener("click", togglePause);

  /* Sound Toggle */
  const soundBtn = document.getElementById("soundBtn");
  const soundIcon = document.getElementById("soundIcon");
  const soundText = document.getElementById("soundText");

  function updateSoundUI() {
    if (isMuted) {
      soundBtn.classList.add("muted");
      soundIcon.className = "bi bi-volume-mute-fill";
      soundText.textContent = "Muted";
    } else {
      soundBtn.classList.remove("muted");
      soundIcon.className = "bi bi-volume-up-fill";
      soundText.textContent = "Sound";
    }
  }
  updateSoundUI();

  soundBtn.addEventListener("click", function(){
    isMuted = !isMuted;
    localStorage.setItem(SOUND_KEY, isMuted ? 'true' : 'false');
    updateSoundUI();
    if (!isMuted) soundCoin();
  });

  /* Keyboard Shortcuts */
  document.addEventListener("keydown", function(e){
    if (e.target && e.target.tagName === 'INPUT') {
      if (e.key === "Escape") togglePause();
      return;
    }
    // If tutorial is open, Escape closes it
    if (tutorialOpen) {
      if (e.key === "Escape") closeTut(true);
      return;
    }
    if (e.code === "Space") {
      e.preventDefault();
      triggerRoll();
    } else if (e.key === "p" || e.key === "P" || e.key === "Escape") {
      togglePause();
    } else if (e.key === "m" || e.key === "M") {
      isMuted = !isMuted;
      localStorage.setItem(SOUND_KEY, isMuted ? 'true' : 'false');
      updateSoundUI();
    } else if (tutOverlay.classList.contains("active")) {
      if (e.key >= "1" && e.key <= "5") goFlashCard(parseInt(e.key, 10));
    }
  });

  /* ==========================================================================
     SAVE PROGRESS & WINNER DISPLAY
     ========================================================================== */
  function saveProgress(nextLevel, score) {
    if (!GAME_ID) return;
    fetch('/student/update-progress', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF_TOKEN
      },
      body: JSON.stringify({
        game_id: GAME_ID,
        level: nextLevel,
        score: score
      })
    }).then(res => res.json()).then(data => {
      console.log("Progress saved", data);
    }).catch(e => console.error("Error saving progress", e));
  }

  function showWinnerOverlay(coinsEarned) {
    var overlay = document.getElementById("winnerOverlay");
    var card = document.getElementById("winnerCard");

    var nextLvlMsg = "";
    var btnText = "Next Level";
    if (CURRENT_LEVEL < MAX_LEVELS) {
      nextLvlMsg = `<p style="color:#34d399; font-weight:800; font-size:1.05rem;">Level ${CURRENT_LEVEL} Completed! Advancing to Level ${CURRENT_LEVEL + 1}.</p>`;
    } else {
      nextLvlMsg = `<p style="color:#fbbf24; font-weight:800; font-size:1.05rem;">🎉 Incredible! You've conquered all ${MAX_LEVELS} levels of MDAS Maze!</p>`;
      btnText = "Play Again";
    }

    card.innerHTML = `
      <div class="trophy">🏆</div>
      <h2>Maze Conquered!</h2>
      ${nextLvlMsg}
      <div class="final-score">Final Score: ${player.score} pts</div>
      <div style="background:rgba(245,158,11,0.15); border:1.5px solid rgba(245,158,11,0.4); border-radius:14px; padding:10px; margin-bottom:20px; font-weight:800; color:#fbbf24; font-size:1.05rem;">
        🪙 +${coinsEarned} Coins Earned! Total: ${player.coins} 🪙
      </div>
      <button class="play-again" id="playAgainBtn">${btnText}</button>
      <div class="eduplay-game-nav" style="display:flex; gap:10px; width:100%; justify-content:center; margin-top:14px;">
        <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" style="flex:1; justify-content:center;">
          <i class="bi bi-map-fill"></i> <span>Game Map</span>
        </a>
        <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit" style="flex:1; justify-content:center;">
          <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
        </a>
      </div>
    `;

    overlay.classList.add("active");
    card.querySelector("#playAgainBtn").addEventListener("click", function(){
      window.location.reload();
    });
  }

  /* ==========================================================================
     STARTUP
     ========================================================================== */
  window.scrollTo(0, 0);
  buildBoard();
  renderToken();
  updateStatsUI();
  updateCoinsUI();

})();
</script>
@endsection