@extends('layouts.app')
@section('hide-nav', true)
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@500;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
  /* ==========================================================================
     MDAS MAZE — BALANCED CYBER ARCADE (30 Tiles, adaptive 6x5 / 3x10 grid)
     ========================================================================== */
  :root {
    --bg-dark: #070b19;
    --card-bg: rgba(15, 23, 42, 0.85);
    --card-border: rgba(99, 102, 241, 0.35);
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
    margin: 0; padding: 0; min-height: 100vh;
    background:
      radial-gradient(circle at 12% 18%, rgba(99, 102, 241, 0.22) 0%, transparent 40%),
      radial-gradient(circle at 88% 82%, rgba(56, 189, 248, 0.18) 0%, transparent 45%),
      radial-gradient(circle at 50% 50%, rgba(168, 85, 247, 0.12) 0%, transparent 55%),
      var(--bg-dark);
    color: #e2e8f0; font-family: var(--font-body); font-weight: 600; overflow-x: hidden;
  }
  #sym-layer { position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden; }
  .sym {
    position: absolute; font-family: 'Space Grotesk', monospace; font-weight: 800;
    pointer-events: none; user-select: none; will-change: transform, opacity;
    animation: floatDrift linear infinite; filter: drop-shadow(0 0 10px currentColor);
  }
  @keyframes floatDrift {
    0% { transform: translateY(0) rotate(0deg); opacity: 0; }
    8% { opacity: 0.42; } 90% { opacity: 0.42; }
    100% { transform: translateY(-115vh) rotate(360deg); opacity: 0; }
  }
  @media (prefers-reduced-motion: reduce) { .sym { animation: none; opacity: 0.25; } }
  .cyber-grid-bg {
    position: fixed; inset: 0;
    background-image:
      linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px);
    background-size: 38px 38px; pointer-events: none; z-index: 0;
  }
  .app-container {
    position: relative; z-index: 1; width: 97%; max-width: 1720px;
    margin: 0 auto; padding: 8px 14px 16px; display: flex; flex-direction: column;
  }
  .masthead {
    display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;
    gap: 10px; margin-bottom: 8px; padding-bottom: 8px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }
  .masthead-left { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
  .title-group h1 {
    font-family: var(--font-heading); font-weight: 900; font-size: clamp(1.6rem, 2.5vw, 2.2rem);
    background: linear-gradient(135deg, #c084fc 0%, #38bdf8 50%, #34d399 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    margin: 0; line-height: 1.1; letter-spacing: -0.5px;
    filter: drop-shadow(0 3px 12px rgba(99, 102, 241, 0.28));
  }
  .title-group p { color: #94a3b8; font-weight: 600; font-size: 0.84rem; margin: 2px 0 0; letter-spacing: 0.2px; }
  .level-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: linear-gradient(135deg, #f59e0b, #d97706); color: #1e1b02;
    padding: 5px 14px; border-radius: 50px; font-weight: 800; font-size: 0.82rem;
    letter-spacing: 0.4px; box-shadow: 0 0 16px rgba(245, 158, 11, 0.35);
  }
  .coins-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(245, 158, 11, 0.16); border: 1.5px solid rgba(245, 158, 11, 0.5);
    color: #fbbf24; padding: 5px 12px; border-radius: 50px; font-weight: 800; font-size: 0.88rem;
    box-shadow: 0 0 12px rgba(245, 158, 11, 0.25); cursor: default; transition: transform 0.2s;
  }
  .coins-pill:hover { transform: scale(1.04); }
  .eduplay-game-nav { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
  .eduplay-nav-btn {
    display: inline-flex; align-items: center; gap: 6px; padding: 6px 13px;
    border-radius: 11px; font-family: var(--font-body); font-size: 0.82rem; font-weight: 700;
    color: #e2e8f0; background: rgba(255, 255, 255, 0.06);
    border: 1.5px solid rgba(255, 255, 255, 0.12); cursor: pointer; text-decoration: none;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .eduplay-nav-btn:hover {
    background: rgba(99, 102, 241, 0.22); border-color: #818cf8; color: #fff;
    transform: translateY(-2px); box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
  }
  .eduplay-btn-pause { background: rgba(245, 158, 11, 0.14); border-color: rgba(245, 158, 11, 0.35); color: #fde68a; }
  .eduplay-btn-pause.paused { background: rgba(16, 185, 129, 0.25); border-color: #34d399; color: #6ee7b7; }
  .eduplay-btn-help { background: rgba(168, 85, 247, 0.16); border-color: rgba(168, 85, 247, 0.38); color: #e9d5ff; }
  .eduplay-btn-sound { background: rgba(56, 189, 248, 0.14); border-color: rgba(56, 189, 248, 0.35); color: #bae6fd; }
  .eduplay-btn-sound.muted { opacity: 0.6; color: #94a3b8; }
  .mdas-order-ribbon {
    background: linear-gradient(90deg, rgba(244, 63, 94, 0.14) 0%, rgba(56, 189, 248, 0.14) 35%, rgba(16, 185, 129, 0.14) 70%, rgba(168, 85, 247, 0.14) 100%);
    border: 1.5px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 6px 14px;
    margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 8px; box-shadow: 0 4px 18px rgba(0, 0, 0, 0.28);
  }
  .ribbon-label { display: flex; align-items: center; gap: 6px; font-size: 0.82rem; font-weight: 800; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.8px; }
  .ribbon-steps { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
  .ribbon-step { display: inline-flex; align-items: center; gap: 5px; background: rgba(0, 0, 0, 0.4); padding: 3px 10px; border-radius: 7px; font-size: 0.8rem; font-weight: 800; border: 1px solid rgba(255, 255, 255, 0.12); }
  .ribbon-step.p1 { border-color: rgba(244, 63, 94, 0.55); color: #fb7185; }
  .ribbon-step.p2 { border-color: rgba(56, 189, 248, 0.55); color: #38bdf8; }
  .ribbon-step.p3 { border-color: rgba(16, 185, 129, 0.55); color: #34d399; }
  .ribbon-step.p4 { border-color: rgba(245, 158, 11, 0.55); color: #fbbf24; }
  .ribbon-arrow { color: #64748b; font-size: 0.76rem; }
  .ribbon-tip { font-size: 0.95rem; color: #a5b4fc; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
  .game-layout { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 14px; align-items: stretch; }
  @media (max-width: 1040px) { .game-layout { grid-template-columns: 1fr; } }
  .board-panel {
    background: linear-gradient(165deg, rgba(13, 21, 46, 0.92) 0%, rgba(10, 16, 36, 0.94) 100%);
    border: 1.5px solid rgba(99, 102, 241, 0.4); border-radius: 22px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.65), 0 0 40px rgba(99, 102, 241, 0.14);
    padding: 14px 16px 16px; position: relative; backdrop-filter: blur(14px);
    display: flex; flex-direction: column; justify-content: space-between;
  }
  .board-toolbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: nowrap; gap: 12px; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); }
  .path-key { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; font-size: 0.76rem; color: #cbd5e1; font-weight: 700; }
  .path-key span { display: inline-flex; align-items: center; gap: 6px; background: rgba(255, 255, 255, 0.05); padding: 4px 11px; border-radius: 50px; border: 1px solid rgba(255, 255, 255, 0.09); white-space: nowrap; }
  .path-key i { width: 9px; height: 9px; border-radius: 50%; display: inline-block; box-shadow: 0 0 8px currentColor; }
  .mdas-rules-btn {
    display: inline-flex; align-items: center; gap: 8px; padding: 7px 18px; border-radius: 12px;
    font-family: var(--font-body); font-size: 0.86rem; font-weight: 800; color: #e9d5ff;
    background: linear-gradient(135deg, rgba(168, 85, 247, 0.28), rgba(99, 102, 241, 0.28));
    border: 1.5px solid rgba(168, 85, 247, 0.55); cursor: pointer; white-space: nowrap; flex-shrink: 0;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 4px 14px rgba(168, 85, 247, 0.25);
  }
  .mdas-rules-btn:hover { background: linear-gradient(135deg, rgba(168, 85, 247, 0.45), rgba(99, 102, 241, 0.45)); border-color: #c084fc; color: #ffffff; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(168, 85, 247, 0.45); }
  .maze-grid { display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 9px; position: relative; flex: 1; align-content: stretch; }
  .cell {
    position: relative; min-width: 0; min-height: clamp(75px, 10.8vh, 98px); border-radius: 14px;
    display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 6px 4px;
    border: 2px solid rgba(255, 255, 255, 0.09); background: rgba(255, 255, 255, 0.035);
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1); user-select: none; overflow: hidden;
  }
  .cell:hover { transform: translateY(-2px) scale(1.02); border-color: rgba(255, 255, 255, 0.35); box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5); }
  .cell-num { position: absolute; top: 5px; left: 7px; font-size: 0.9rem; font-family: var(--font-mono); color: rgba(255, 255, 255, 0.45); font-weight: 800; }
  .cell-dir-indicator { position: absolute; bottom: 4px; right: 6px; font-size: 0.95rem; color: rgba(255, 255, 255, 0.22); }
  .cell .op-label { font-family: var(--font-heading); font-weight: 900; font-size: clamp(0.95rem, 1.6vw, 1.35rem); text-align: center; letter-spacing: 0.5px; line-height: 1.2; z-index: 1; }
  .cell .op-sub { font-size: 0.68rem; color: rgba(255, 255, 255, 0.6); font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; margin-top: 2px; text-align: center; }
  .cell.start { background: radial-gradient(circle, rgba(16, 185, 129, 0.35) 0%, rgba(16, 185, 129, 0.12) 100%); border-color: rgba(16, 185, 129, 0.8); box-shadow: inset 0 0 16px rgba(16, 185, 129, 0.25), 0 0 16px rgba(16, 185, 129, 0.25); }
  .cell.start .op-label { color: #34d399; font-size: 1.05rem; }
  .cell.finish { background: radial-gradient(circle, rgba(245, 158, 11, 0.38) 0%, rgba(245, 158, 11, 0.14) 100%); border-color: rgba(245, 158, 11, 0.85); box-shadow: inset 0 0 20px rgba(245, 158, 11, 0.3), 0 0 20px rgba(245, 158, 11, 0.3); }
  .cell.finish .op-label { color: #fbbf24; font-size: 1.05rem; }
  .cell.red { background: rgba(244, 63, 94, 0.16); border-color: rgba(244, 63, 94, 0.55); }
  .cell.red .op-label { color: #fb7185; }
  .cell.blue { background: rgba(56, 189, 248, 0.16); border-color: rgba(56, 189, 248, 0.55); }
  .cell.blue .op-label { color: #38bdf8; }
  .cell.green { background: rgba(52, 211, 153, 0.16); border-color: rgba(52, 211, 153, 0.55); }
  .cell.green .op-label { color: #34d399; }
  .cell.gold { background: linear-gradient(135deg, rgba(168, 85, 247, 0.22), rgba(245, 158, 11, 0.22)); border-color: rgba(168, 85, 247, 0.65); box-shadow: 0 0 14px rgba(168, 85, 247, 0.22); }
  .cell.gold .op-label { background: linear-gradient(135deg, #c084fc, #fbbf24); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
  .cell.neutral { background: rgba(255, 255, 255, 0.025); border-style: dashed; border-color: rgba(255, 255, 255, 0.18); }
  .cell.neutral .op-label { color: #94a3b8; font-size: 0.88rem; }
  .cell.active-tile { border-color: #f59e0b !important; box-shadow: 0 0 25px rgba(245, 158, 11, 0.6) !important; animation: pulseBorder 1.5s infinite alternate; }
  @keyframes pulseBorder { from { box-shadow: 0 0 14px rgba(245, 158, 11, 0.45); } to { box-shadow: 0 0 30px rgba(245, 158, 11, 0.85); } }
  .token-well { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; pointer-events: none; z-index: 5; }
  .token { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; background: linear-gradient(135deg, #f59e0b, #ec4899); border: 3px solid #ffffff; box-shadow: 0 0 22px rgba(236, 72, 153, 0.9), 0 4px 12px rgba(0, 0, 0, 0.6); transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); }
  .token.hop { animation: cyberHop 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
  @keyframes cyberHop { 0% { transform: translateY(0) scale(1); } 40% { transform: translateY(-24px) scale(1.25); filter: brightness(1.35); } 100% { transform: translateY(0) scale(1); } }
  .sidebar { display: flex; flex-direction: column; gap: 12px; justify-content: flex-start; }
  .sidebar-panel { background: linear-gradient(165deg, rgba(15, 23, 42, 0.94) 0%, rgba(10, 16, 32, 0.94) 100%); backdrop-filter: blur(14px); border: 1.5px solid rgba(255, 255, 255, 0.1); border-radius: 18px; padding: 16px; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.45); position: relative; }
  .sidebar-panel h3 { font-family: var(--font-heading); font-weight: 800; font-size: 1rem; margin: 0 0 10px; color: #f1f5f9; border-bottom: 1px solid rgba(255, 255, 255, 0.08); padding-bottom: 7px; display: flex; align-items: center; justify-content: space-between; }
  .player-card { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
  .player-avatar { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.45rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); border: 2px solid rgba(255, 255, 255, 0.25); box-shadow: 0 0 16px rgba(99, 102, 241, 0.45); flex-shrink: 0; }
  .player-details { flex: 1; min-width: 0; }
  .player-name { font-weight: 800; font-size: 0.92rem; color: #f8fafc; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .player-meta { font-size: 0.74rem; color: #94a3b8; }
  .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
  .stat-box { background: rgba(0, 0, 0, 0.32); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 12px; padding: 7px 10px; text-align: center; }
  .stat-label { font-size: 0.68rem; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 2px; }
  .stat-value { font-family: var(--font-heading); font-size: 1.2rem; font-weight: 900; line-height: 1; }
  .stat-value.score { background: linear-gradient(135deg, #38bdf8, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
  .stat-value.streak { color: #f59e0b; display: flex; align-items: center; justify-content: center; gap: 4px; }
  .progress-wrap { margin-top: 10px; padding-top: 8px; border-top: 1px solid rgba(255, 255, 255, 0.06); }
  .progress-header { display: flex; justify-content: space-between; font-size: 0.9rem; color: #94a3b8; font-weight: 700; margin-bottom: 4px; gap: 8px; }
  .progress-bar-bg { width: 100%; height: 7px; background: rgba(255, 255, 255, 0.08); border-radius: 50px; overflow: hidden; }
  .progress-bar-fill { height: 100%; width: 0%; background: linear-gradient(90deg, #6366f1, #a855f7, #ec4899); border-radius: 50px; transition: width 0.4s ease; box-shadow: 0 0 10px rgba(168, 85, 247, 0.5); }
  .dice-panel { text-align: center; }
  .dice-stage { width: 96px; height: 96px; margin: 6px auto 14px; perspective: 800px; }
  .die { width: 100%; height: 100%; position: relative; transform-style: preserve-3d; transform: rotateX(-15deg) rotateY(15deg); transition: transform 1.05s cubic-bezier(0.22, 1.6, 0.36, 1); }
  .face { position: absolute; width: 96px; height: 96px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 2px solid rgba(99, 102, 241, 0.6); border-radius: 18px; display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(3, 1fr); padding: 12px; gap: 4px; backface-visibility: hidden; box-shadow: inset 0 0 18px rgba(99, 102, 241, 0.25), 0 8px 24px rgba(0, 0, 0, 0.5); }
  .pip { visibility: hidden; align-self: center; justify-self: center; width: 14px; height: 14px; border-radius: 50%; background: #38bdf8; box-shadow: 0 0 10px rgba(56, 189, 248, 0.9); }
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
  .roll-btn { border: none; border-radius: 50px; font-family: var(--font-body); cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); width: 100%; padding: 13px 0; font-size: 1rem; font-weight: 800; letter-spacing: 0.6px; color: #ffffff; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%); box-shadow: 0 8px 24px rgba(99, 102, 241, 0.45); margin-bottom: 5px; display: flex; align-items: center; justify-content: center; gap: 8px; }
  .roll-btn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(99, 102, 241, 0.65); filter: brightness(1.08); }
  .roll-btn:active:not(:disabled) { transform: translateY(1px); }
  .roll-btn:disabled { opacity: 0.45; filter: grayscale(0.5); cursor: not-allowed; }
  .roll-hint { font-size: 0.74rem; color: #64748b; font-weight: 600; }
  .challenge-overlay { position: fixed; inset: 0; background: rgba(4, 8, 22, 0.88); backdrop-filter: blur(16px); z-index: 9990; display: none; align-items: center; justify-content: center; padding: 20px; }
  .challenge-overlay.active { display: flex; animation: modalPopIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
  @keyframes modalPopIn { from { opacity: 0; transform: scale(0.92); } to { opacity: 1; transform: scale(1); } }
  .challenge-card { background: linear-gradient(165deg, #0e172e 0%, #111e3d 100%); border: 2px solid rgba(56, 189, 248, 0.45); border-radius: 28px; padding: 28px; max-width: 480px; width: 100%; box-shadow: 0 30px 80px rgba(0, 0, 0, 0.85), 0 0 50px rgba(56, 189, 248, 0.22); position: relative; overflow: hidden; }
  .challenge-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); }
  .challenge-badge { display: inline-flex; align-items: center; gap: 6px; font-size: 0.82rem; font-weight: 800; color: #38bdf8; background: rgba(56, 189, 248, 0.12); padding: 5px 12px; border-radius: 50px; border: 1px solid rgba(56, 189, 248, 0.3); }
  .expr-box { font-family: var(--font-heading); font-weight: 800; font-size: clamp(1.8rem, 4vw, 2.3rem); text-align: center; background: rgba(0, 0, 0, 0.45); border: 1.5px solid rgba(56, 189, 248, 0.35); border-radius: 18px; padding: 18px 14px; margin-bottom: 12px; letter-spacing: 1.2px; color: #ffffff; box-shadow: inset 0 0 25px rgba(56, 189, 248, 0.12); }
  .expr-box .op-m { color: #f43f5e; font-weight: 900; }
  .expr-box .op-d { color: #38bdf8; font-weight: 900; }
  .expr-box .op-a { color: #34d399; font-weight: 900; }
  .expr-box .op-s { color: #fbbf24; font-weight: 900; }
  .challenge-rule-strip { background: rgba(255, 255, 255, 0.04); border-radius: 10px; padding: 6px 10px; font-size: 0.76rem; color: #94a3b8; text-align: center; margin-bottom: 12px; font-weight: 700; }
  .challenge-rule-strip b { color: #38bdf8; }
  .coin-hint-bar { display: flex; justify-content: space-between; align-items: center; gap: 10px; background: rgba(245, 158, 11, 0.08); border: 1px dashed rgba(245, 158, 11, 0.35); border-radius: 14px; padding: 10px 14px; margin-bottom: 14px; }
  .coin-hint-info { font-size: 0.8rem; color: #cbd5e1; font-weight: 600; line-height: 1.3; }
  .coin-hint-info span { color: #fbbf24; font-weight: 800; }
  .btn-coin-hint { background: linear-gradient(135deg, #f59e0b, #d97706); color: #1e1b02; border: none; border-radius: 10px; padding: 7px 14px; font-family: var(--font-body); font-size: 0.82rem; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35); transition: all 0.2s; white-space: nowrap; display: inline-flex; align-items: center; justify-content: center; gap: 6px; }
  .btn-coin-hint:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(245, 158, 11, 0.55); }
  .btn-coin-hint:disabled { opacity: 0.5; cursor: not-allowed; filter: grayscale(0.5); }
  .hint-reveal-box { display: none; background: rgba(16, 185, 129, 0.1); border: 1.5px solid rgba(16, 185, 129, 0.4); border-radius: 14px; padding: 12px 14px; margin-bottom: 14px; font-size: 0.86rem; color: #e2e8f0; line-height: 1.45; animation: fadeIn 0.25s ease; }
  .hint-reveal-box.active { display: block; }
  .hint-reveal-title { font-weight: 800; color: #34d399; margin-bottom: 4px; display: flex; align-items: center; gap: 6px; }
  .challenge-form { display: flex; flex-direction: column; gap: 10px; }
  .input-row { display: flex; gap: 10px; }
  .challenge-input { flex: 1; min-width: 0; border: 2px solid rgba(255, 255, 255, 0.16); border-radius: 16px; padding: 12px; font-size: 1.4rem; font-weight: 900; text-align: center; background: rgba(0, 0, 0, 0.35); color: #ffffff; font-family: var(--font-heading); transition: all 0.2s; }
  .challenge-input:focus { outline: none; border-color: #38bdf8; box-shadow: 0 0 20px rgba(56, 189, 248, 0.45); }
  .submit-btn { border: none; border-radius: 16px; padding: 0 28px; font-family: var(--font-body); font-weight: 800; font-size: 1rem; background: linear-gradient(135deg, #10b981, #059669); color: #ffffff; box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45); cursor: pointer; transition: all 0.2s; }
  .submit-btn:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 8px 25px rgba(16, 185, 129, 0.65); }
  .submit-btn:disabled { opacity: 0.5; cursor: not-allowed; }
  .keypad-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 6px; margin-top: 4px; }
  .key-btn { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #e2e8f0; border-radius: 10px; padding: 8px 0; font-family: var(--font-heading); font-weight: 700; font-size: 1rem; cursor: pointer; transition: all 0.15s; touch-action: manipulation; }
  .key-btn:hover { background: rgba(255, 255, 255, 0.15); color: #fff; transform: translateY(-1px); }
  .key-btn:active { transform: scale(0.95); }
  .key-btn.del { background: rgba(244, 63, 94, 0.15); border-color: rgba(244, 63, 94, 0.35); color: #fb7185; }
  .key-btn.clear { background: rgba(148, 163, 184, 0.15); border-color: rgba(148, 163, 184, 0.35); color: #94a3b8; }
  .challenge-feedback { margin-top: 12px; font-size: 0.92rem; line-height: 1.4; color: #94a3b8; min-height: 2.6em; text-align: center; font-weight: 700; padding: 8px 12px; border-radius: 12px; background: rgba(0, 0, 0, 0.2); }
  .challenge-feedback.good { color: #34d399; background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); }
  .challenge-feedback.bad { color: #f87171; background: rgba(244, 63, 94, 0.15); border: 1px solid rgba(244, 63, 94, 0.4); }
  .step-breakdown { text-align: left; margin-top: 8px; font-size: 0.82rem; color: #e2e8f0; background: rgba(0, 0, 0, 0.3); padding: 10px 12px; border-radius: 10px; line-height: 1.5; }
  .fc-overlay { position: fixed; inset: 0; background: rgba(4, 7, 20, 0.88); backdrop-filter: blur(14px); z-index: 9995; display: none; align-items: center; justify-content: center; padding: 16px; }
  .fc-overlay.active { display: flex; animation: fadeIn 0.25s ease; }
  .fc-deck { position: relative; max-width: 820px; width: 100%; }
  .fc-deck-stack-1 { position: absolute; inset: 8px -8px -8px 8px; background: rgba(30, 41, 59, 0.6); border-radius: 28px; border: 1.5px solid rgba(255, 255, 255, 0.05); z-index: 1; pointer-events: none; }
  .fc-deck-stack-2 { position: absolute; inset: 16px -16px -16px 16px; background: rgba(15, 23, 42, 0.4); border-radius: 28px; border: 1.5px solid rgba(255, 255, 255, 0.03); z-index: 0; pointer-events: none; }
  .fc-card { position: relative; z-index: 2; background: linear-gradient(165deg, #0e172e 0%, #0d152a 100%); border: 2px solid rgba(99, 102, 241, 0.4); border-radius: 28px; padding: 36px 40px 32px; box-shadow: 0 30px 80px rgba(0, 0, 0, 0.85), 0 0 50px rgba(99, 102, 241, 0.2); min-height: auto; display: flex; flex-direction: column; justify-content: space-between; }
  .fc-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid rgba(255, 255, 255, 0.08); gap: 8px; }
  .fc-badge { font-size: 0.92rem; font-weight: 800; color: #a78bfa; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 6px; }
  .fc-quick-action-btn { background: transparent; border: none; color: #94a3b8; font-family: var(--font-body); font-weight: 700; font-size: 0.96rem; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; padding: 4px 8px; border-radius: 8px; transition: all 0.2s; white-space: nowrap; }
  .fc-quick-action-btn:hover { color: #ffffff; background: rgba(255, 255, 255, 0.08); }
  .fc-slide { flex: 1; display: flex; flex-direction: column; animation: fadeInSlide 0.25s ease; }
  .fc-slide.hidden { display: none; }
  @keyframes fadeInSlide { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
  .fc-title { font-family: var(--font-heading); font-weight: 800; font-size: 1.7rem; margin: 0 0 8px; display: flex; align-items: center; gap: 10px; }
  .fc-desc { color: #cbd5e1; font-size: 1.08rem; line-height: 1.5; margin: 0 0 16px; }
  .fc-box { background: rgba(0, 0, 0, 0.35); border: 1.5px solid rgba(255, 255, 255, 0.08); border-radius: 18px; padding: 20px 22px; margin-bottom: 14px; }
  .fc-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
  @media (max-width: 520px) { .fc-row-2 { grid-template-columns: 1fr; } }
  .fc-item { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 14px; padding: 16px; text-align: center; }
  .fc-item-ico { font-size: 1.5rem; margin-bottom: 4px; }
  .fc-item-lbl { font-weight: 800; font-size: 1.0rem; color: #f8fafc; margin-bottom: 2px; }
  .fc-item-sub { font-size: 0.92rem; color: #94a3b8; }
  .fc-item-badge { margin-top: 6px; display: inline-block; padding: 3px 8px; border-radius: 6px; font-size: 0.88rem; font-weight: 800; }
  .fc-item-badge.good { background: rgba(16, 185, 129, 0.2); color: #34d399; }
  .fc-item-badge.bad { background: rgba(244, 63, 94, 0.2); color: #fb7185; }
  .fc-tip { background: rgba(99, 102, 241, 0.12); border-left: 3px solid #818cf8; border-radius: 8px; padding: 10px 14px; font-size: 0.98rem; color: #e0e7ff; line-height: 1.45; }
  .fc-flow { display: flex; align-items: center; justify-content: center; gap: 10px; margin: 22px 0; }
  .fc-flow-step { display: flex; flex-direction: column; align-items: center; gap: 8px; animation: fcBob 2.2s ease-in-out infinite; }
  .fc-flow-step:nth-child(3) { animation-delay: -0.7s; }
  .fc-flow-step:nth-child(5) { animation-delay: -1.4s; }
  .fc-flow-ico { width: 78px; height: 78px; border-radius: 20px; background: linear-gradient(160deg, rgba(56, 189, 248, 0.16), rgba(99, 102, 241, 0.16)); border: 2px solid rgba(99, 102, 241, 0.5); display: flex; align-items: center; justify-content: center; font-size: 2.3rem; box-shadow: 0 8px 22px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.1); }
  .fc-flow-lbl { font-family: var(--font-heading); font-weight: 800; font-size: 1rem; color: #f1f5f9; text-transform: uppercase; letter-spacing: 0.5px; }
  .fc-flow-arrow { font-size: 1.6rem; color: #64748b; animation: fcArrow 1.4s ease-in-out infinite; }
  @keyframes fcBob { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
  @keyframes fcArrow { 0%, 100% { transform: translateX(0); opacity: 0.5; } 50% { transform: translateX(4px); opacity: 1; } }
  .fc-priority-row { display: flex; align-items: center; justify-content: center; gap: 14px; margin: 22px 0; flex-wrap: wrap; }
  .fc-priority-card { flex: 1; min-width: 130px; max-width: 190px; padding: 18px 14px; border-radius: 18px; text-align: center; animation: fcBob 2.4s ease-in-out infinite; }
  .fc-priority-card:nth-child(3) { animation-delay: -1.2s; }
  .fc-priority-first { background: linear-gradient(160deg, rgba(244, 63, 94, 0.22), rgba(56, 189, 248, 0.22)); border: 2px solid rgba(244, 63, 94, 0.6); box-shadow: 0 0 22px rgba(244, 63, 94, 0.28); }
  .fc-priority-second { background: linear-gradient(160deg, rgba(16, 185, 129, 0.18), rgba(245, 158, 11, 0.18)); border: 2px solid rgba(16, 185, 129, 0.5); }
  .fc-priority-rank { font-family: var(--font-heading); font-size: 0.85rem; font-weight: 900; letter-spacing: 1.5px; color: #fbbf24; text-transform: uppercase; margin-bottom: 8px; }
  .fc-priority-first .fc-priority-rank { color: #fb7185; }
  .fc-priority-ops { font-family: var(--font-heading); font-size: 2.6rem; font-weight: 900; line-height: 1; color: #ffffff; margin-bottom: 8px; text-shadow: 0 0 18px currentColor; }
  .fc-priority-first .fc-priority-ops { color: #fda4af; }
  .fc-priority-second .fc-priority-ops { color: #6ee7b7; }
  .fc-priority-lbl { font-size: 0.85rem; color: #cbd5e1; font-weight: 700; line-height: 1.25; }
  .fc-priority-vs { font-family: var(--font-heading); font-size: 0.8rem; font-weight: 900; color: #64748b; letter-spacing: 1.5px; text-transform: uppercase; padding: 4px 0; }
  .fc-zone-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin: 18px 0; }
  .fc-zone-chip { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-radius: 14px; background: rgba(255, 255, 255, 0.04); border: 1.5px solid rgba(255, 255, 255, 0.14); }
  .fc-zone-dot { width: 18px; height: 18px; border-radius: 50%; flex-shrink: 0; box-shadow: 0 0 12px currentColor; }
  .fc-zone-ops { font-family: var(--font-heading); font-size: 1.2rem; font-weight: 900; color: #ffffff; }
  .fc-action-row { display: flex; gap: 10px; justify-content: center; margin-top: 8px; }
  .fc-action-btn { flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 14px 20px; border-radius: 14px; font-family: var(--font-body); font-weight: 800; font-size: 1.05rem; border: none; cursor: pointer; min-height: 56px; transition: transform 0.18s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.18s; touch-action: manipulation; }
  .fc-action-play { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #ffffff; box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45); }
  .fc-action-play:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(99, 102, 241, 0.65); }
  @media (max-width: 680px) {
    .fc-flow-ico { width: 62px; height: 62px; font-size: 1.8rem; }
    .fc-flow-lbl { font-size: 0.85rem; }
    .fc-flow-arrow { font-size: 1.2rem; }
    .fc-priority-card { padding: 14px 10px; min-width: 110px; }
    .fc-priority-ops { font-size: 2rem; }
    .fc-priority-lbl { font-size: 0.74rem; }
    .fc-zone-chip { padding: 10px 12px; gap: 10px; }
    .fc-zone-dot { width: 16px; height: 16px; }
    .fc-zone-ops { font-size: 1rem; }
    .fc-action-btn { font-size: 0.95rem; min-height: 52px; }
  }
  .fc-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding-top: 14px; border-top: 1px solid rgba(255, 255, 255, 0.08); }
  .fc-nav-btn { border: 1.5px solid rgba(255, 255, 255, 0.15); background: rgba(255, 255, 255, 0.05); color: #e2e8f0; border-radius: 50px; padding: 8px 18px; font-family: var(--font-body); font-weight: 800; font-size: 1.0rem; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px; }
  .fc-nav-btn:hover { background: rgba(255, 255, 255, 0.12); color: #fff; }
  .fc-nav-btn.primary { background: linear-gradient(135deg, #6366f1, #8b5cf6); border-color: transparent; color: #fff; box-shadow: 0 4px 14px rgba(99, 102, 241, 0.45); }
  .fc-nav-btn.primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(99, 102, 241, 0.65); }
  .fc-dots { display: flex; gap: 8px; }
  .fc-dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(255, 255, 255, 0.18); border: none; cursor: pointer; transition: all 0.2s; padding: 0; }
  .fc-dot.active { background: #38bdf8; transform: scale(1.3); box-shadow: 0 0 10px rgba(56, 189, 248, 0.8); }
  .pause-overlay { position: fixed; inset: 0; background: rgba(4, 7, 20, 0.88); backdrop-filter: blur(14px); z-index: 9998; display: none; align-items: center; justify-content: center; padding: 20px; }
  .pause-overlay.active { display: flex; animation: fadeIn 0.2s ease; }
  .pause-box { background: linear-gradient(165deg, #0f172a, #1e293b); border: 1.5px solid rgba(99, 102, 241, 0.45); padding: 38px 44px; border-radius: 26px; text-align: center; box-shadow: 0 30px 80px rgba(0, 0, 0, 0.8), 0 0 45px rgba(99, 102, 241, 0.25); max-width: 440px; width: 100%; }
  .pause-box h2 { font-family: var(--font-heading); font-size: 2rem; font-weight: 900; color: #fff; margin: 0 0 10px; }

  /* ============================================================
     BURGER MENU
     ============================================================ */
  .game-burger-btn { width:44px; height:44px; border-radius:12px; background:rgba(99,102,241,.15); border:2px solid rgba(99,102,241,.4); color:#818cf8; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; cursor:pointer; transition:all .2s; position:relative; z-index:100; }
  .game-burger-btn:hover { background:rgba(99,102,241,.25); transform:scale(1.05); }
  .game-burger-line { width:22px; height:2.5px; background:currentColor; border-radius:2px; transition:all .3s; }
  .game-burger-btn.active .game-burger-line:nth-child(1) { transform:translateY(6.5px) rotate(45deg); }
  .game-burger-btn.active .game-burger-line:nth-child(2) { opacity:0; }
  .game-burger-btn.active .game-burger-line:nth-child(3) { transform:translateY(-6.5px) rotate(-45deg); }

  .game-burger-menu { position:fixed; top:0; right:-100%; width:min(320px, 85vw); height:100vh; height:100dvh; background:linear-gradient(160deg,rgba(15,23,42,0.98),rgba(30,41,59,0.98)); border-left:2px solid rgba(99,102,241,.4); backdrop-filter:blur(20px); z-index:10000; transition:right .35s ease; box-shadow:-10px 0 40px rgba(0,0,0,.8); display:flex; flex-direction:column; padding:20px; padding-top:70px; }
  .game-burger-menu.active { right:0; }
  .game-burger-overlay { position:fixed; inset:0; background:rgba(0,0,0,.75); backdrop-filter:blur(4px); z-index:9999; opacity:0; pointer-events:none; transition:opacity .3s; }
  .game-burger-overlay.active { opacity:1; pointer-events:all; }

  .game-burger-header { margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid rgba(255,255,255,.1); }
  .game-burger-title { font-size:1.3rem; font-weight:900; color:white; display:flex; align-items:center; gap:10px; font-family:var(--font-heading); }
  .game-burger-subtitle { font-size:.8rem; color:#64748b; margin-top:6px; font-weight:700; text-transform:uppercase; letter-spacing:1px; }

  .game-burger-nav { display:flex; flex-direction:column; gap:12px; }
  .game-burger-nav .eduplay-nav-btn { width:100%; padding:16px 20px; min-height:56px; display:flex !important; align-items:center; gap:12px; font-size:1rem; font-weight:700; border-radius:14px; text-decoration:none; transition:all .2s; justify-content:flex-start; }
  .game-burger-nav .eduplay-btn-sound { background:rgba(56,189,248,.15); border:2px solid rgba(56,189,248,.4); color:#38bdf8; }
  .game-burger-nav .eduplay-btn-sound:hover { background:rgba(56,189,248,.25); transform:translateX(4px); }
  .game-burger-nav .eduplay-btn-pause { background:rgba(251,191,36,.15); border:2px solid rgba(251,191,36,.4); color:#fbbf24; }
  .game-burger-nav .eduplay-btn-pause:hover { background:rgba(251,191,36,.25); transform:translateX(4px); }
  .game-burger-nav .eduplay-btn-map { background:rgba(52,211,153,.15); border:2px solid rgba(52,211,153,.4); color:#34d399; }
  .game-burger-nav .eduplay-btn-map:hover { background:rgba(52,211,153,.25); transform:translateX(4px); }
  .game-burger-nav .eduplay-btn-exit { background:rgba(239,68,68,.15); border:2px solid rgba(239,68,68,.4); color:#ef4444; }
  .game-burger-nav .eduplay-btn-exit:hover { background:rgba(239,68,68,.25); transform:translateX(4px); }
  .game-burger-nav .eduplay-nav-btn i { font-size:1.3rem; }
  .game-burger-nav .eduplay-nav-btn span { flex:1; text-align:left; display:inline !important; }

  /* Inline music row in burger nav */
  #game-music-controls { display:none !important; }
  .game-burger-music-row { display:flex; align-items:center; gap:10px; padding:12px 16px; background:rgba(139,92,246,.1); border:1.5px solid rgba(139,92,246,.35); border-radius:14px; }
  .game-mute-btn { width:40px; height:40px; border-radius:10px; background:rgba(139,92,246,.2); border:1.5px solid rgba(139,92,246,.5); color:#c4b5fd; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:1.1rem; flex-shrink:0; transition:all .2s; }
  .game-mute-btn:hover { background:rgba(139,92,246,.35); }
  .game-mute-btn.muted { background:rgba(239,68,68,.15); border-color:rgba(239,68,68,.45); color:#f87171; }
  .game-vol-slider { flex:1; height:5px; border-radius:3px; background:rgba(255,255,255,.12); appearance:none; cursor:pointer; accent-color:#8b5cf6; }
  .game-vol-slider::-webkit-slider-thumb { appearance:none; width:15px; height:15px; border-radius:50%; background:#8b5cf6; cursor:pointer; }
  .game-vol-slider::-moz-range-thumb { width:15px; height:15px; border-radius:50%; background:#8b5cf6; cursor:pointer; border:none; }
  .game-vol-value { font-size:.8rem; font-weight:700; color:#a78bfa; min-width:34px; text-align:right; }
  
  /* Hide original inline nav */
  .eduplay-game-nav { display:none !important; }

  .pause-box p { color: #94a3b8; font-size: 0.95rem; margin: 0 0 24px; }
  .resume-btn { background: linear-gradient(135deg, #10b981, #059669); border: none; padding: 13px 36px; border-radius: 50px; font-weight: 800; font-family: var(--font-body); font-size: 1rem; color: #fff; cursor: pointer; box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45); transition: all 0.2s; }
  .resume-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(16, 185, 129, 0.65); }
  .winner-overlay { position: fixed; inset: 0; background: rgba(4, 7, 20, 0.9); backdrop-filter: blur(16px); z-index: 9999; display: none; align-items: center; justify-content: center; padding: 20px; }
  .winner-overlay.active { display: flex; animation: fadeIn 0.25s ease; }
  .winner-card { background: linear-gradient(165deg, #0e172e 0%, #172554 100%); border: 2px solid rgba(245, 158, 11, 0.5); border-radius: 30px; padding: 38px 32px; max-width: 460px; width: 100%; text-align: center; box-shadow: 0 40px 90px rgba(0, 0, 0, 0.85), 0 0 60px rgba(245, 158, 11, 0.3); animation: modalPopIn 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
  .winner-card .trophy { font-size: 4rem; margin-bottom: 10px; filter: drop-shadow(0 0 25px rgba(245, 158, 11, 0.7)); animation: floatTrophy 2s ease-in-out infinite; }
  @keyframes floatTrophy { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
  .winner-card h2 { font-family: var(--font-heading); margin: 0 0 8px; font-size: 2.1rem; font-weight: 900; color: #fff; }
  .winner-card .final-score { font-family: var(--font-heading); font-size: 1.8rem; font-weight: 900; color: #38bdf8; margin: 12px 0 16px; }
  .winner-card .play-again { width: 100%; border: none; border-radius: 50px; padding: 14px; font-family: var(--font-body); font-weight: 800; font-size: 1.05rem; background: linear-gradient(135deg, #f59e0b, #d97706); color: #1e1b02; box-shadow: 0 6px 20px rgba(245, 158, 11, 0.45); cursor: pointer; transition: 0.2s; }
  .winner-card .play-again:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(245, 158, 11, 0.65); }
  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  @media (max-width: 680px) {
    .app-container { width: 100%; padding: 6px 10px calc(130px + env(safe-area-inset-bottom, 0px)); }
    .masthead { gap: 8px; margin-bottom: 6px; padding-bottom: 6px; }
    .masthead-left { gap: 8px; width: 100%; }
    .title-group { order: 3; width: 100%; }
    .title-group h1 { font-size: 1.5rem; }
    .title-group p { display: none; }
    .level-badge, .coins-pill { font-size: 0.74rem; padding: 4px 10px; }
    .eduplay-game-nav { width: 100%; justify-content: space-between; gap: 6px; }
    .eduplay-nav-btn { flex: 1; justify-content: center; padding: 8px 6px; font-size: 1rem; }
    .eduplay-nav-btn span { display: none; }
    .mdas-order-ribbon { padding: 6px 8px; margin-bottom: 8px; justify-content: center; }
    .ribbon-label, .ribbon-tip { display: none; }
    .ribbon-steps { justify-content: center; gap: 4px; }
    .ribbon-step { font-size: 0.68rem; padding: 3px 6px; }
    .ribbon-arrow small { display: none; }
    .board-panel { padding: 10px; border-radius: 18px; }
    .board-toolbar { flex-wrap: wrap; gap: 8px; }
    .path-key { gap: 5px; font-size: 0.68rem; flex: 1; }
    .path-key span { padding: 3px 8px; }
    .mdas-rules-btn { padding: 6px 12px; font-size: 0.78rem; }
    .maze-grid { gap: 7px; }
    .cell { min-height: 74px; border-radius: 12px; border-width: 1.5px; }
    .cell:hover { transform: none; }
    .cell-num { font-size: 0.72rem; top: 4px; left: 6px; }
    .cell .op-label { font-size: 0.95rem; }
    .cell.start .op-label, .cell.finish .op-label { font-size: 0.9rem; }
    .cell.neutral .op-label { font-size: 0.8rem; }
    .cell .op-sub { font-size: 0.58rem; }
    .token { width: 34px; height: 34px; font-size: 1.2rem; border-width: 2px; }
    .sidebar { gap: 10px; }
    .sidebar-panel { padding: 12px; }
    .progress-header { font-size: 0.78rem; }
    .dice-panel { position: fixed; left: 0; right: 0; bottom: 0; z-index: 60; display: flex; align-items: center; gap: 14px; padding: 8px 16px calc(8px + env(safe-area-inset-bottom, 0px)); border-radius: 18px 18px 0 0; border-bottom: none; }
    .dice-panel h3, .dice-panel .roll-hint { display: none; }
    .dice-stage { flex-shrink: 0; margin: -19px; transform: scale(0.6); }
    .roll-btn { margin: 0; padding: 15px 0; font-size: 1.05rem; flex: 1; width: auto; }
    .challenge-overlay { padding: 10px; align-items: flex-start; overflow-y: auto; }
    .challenge-card { padding: 16px; border-radius: 22px; margin: auto; max-height: 96dvh; overflow-y: auto; }
    .expr-box { font-size: 1.6rem; padding: 14px 8px; letter-spacing: 0.5px; }
    .coin-hint-bar { flex-direction: column; align-items: stretch; text-align: center; }
    .challenge-input { font-size: 1.2rem; padding: 10px; }
    .submit-btn { padding: 0 20px; }
    .key-btn { padding: 12px 0; font-size: 1.1rem; }
    .fc-overlay { padding: 10px; overflow-y: auto; }
    .fc-deck-stack-1, .fc-deck-stack-2 { display: none; }
    .fc-card { padding: 18px 16px; border-radius: 22px; max-height: 96dvh; overflow-y: auto; }
    .fc-badge { font-size: 0.74rem; letter-spacing: 0.4px; }
    .fc-quick-action-btn { font-size: 0.82rem; }
    .fc-title { font-size: 1.25rem; }
    .fc-desc { font-size: 0.92rem; }
    .fc-box { padding: 12px; }
    .fc-item { padding: 10px; }
    .fc-tip { font-size: 0.86rem; }
    .fc-nav-btn { padding: 8px 12px; font-size: 0.88rem; }
    .fc-dots { gap: 5px; }
    .pause-box, .winner-card { padding: 26px 20px; }
    .winner-card h2 { font-size: 1.7rem; }
  }
  @media (max-width: 380px) {
    .cell { min-height: 66px; }
    .cell .op-label { font-size: 0.85rem; }
    .ribbon-step { font-size: 0.62rem; }
  }
</style>

<div id="sym-layer" aria-hidden="true"></div>
<div class="cyber-grid-bg"></div>

<div class="app-container">
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
    <button type="button" class="game-burger-btn" id="burgerBtn" onclick="toggleBurgerMenu()" aria-label="Open menu">
      <div class="game-burger-line"></div>
      <div class="game-burger-line"></div>
      <div class="game-burger-line"></div>
    </button>
  </header>

  <div class="mdas-order-ribbon">
    <div class="ribbon-label"><i class="bi bi-diagram-3-fill text-warning"></i> MDAS Order:</div>
    <div class="ribbon-steps">
      <div class="ribbon-step p1"><i class="bi bi-1-circle-fill"></i> Multiply (×)</div>
      <span class="ribbon-arrow">&amp;</span>
      <div class="ribbon-step p2"><i class="bi bi-1-circle-fill"></i> Divide (÷)</div>
      <span class="ribbon-arrow">➔ <small>Left to Right</small> ➔</span>
      <div class="ribbon-step p3"><i class="bi bi-2-circle-fill"></i> Add (+)</div>
      <span class="ribbon-arrow">&amp;</span>
      <div class="ribbon-step p4"><i class="bi bi-2-circle-fill"></i> Subtract (−)</div>
    </div>
    <div class="ribbon-tip"><i class="bi bi-lightbulb-fill text-warning"></i> <span>Multiply &amp; Divide BEFORE you Add &amp; Subtract!</span></div>
  </div>

  <main class="game-layout" id="gameScreen">
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
      <div class="maze-grid" id="mazeGrid" role="grid" aria-label="Maze Grid"></div>
    </section>

    <aside class="sidebar">
      <div class="sidebar-panel">
        <h3>
          <span>Mission Stats</span>
          <span class="badge" style="font-size:0.75rem; background:rgba(99,102,241,0.25); color:#c4b5fd; border:1px solid rgba(99,102,241,0.4); padding:3px 8px; border-radius:50px;">Stage {{ $progress->current_level ?? 1 }}</span>
        </h3>
        <div class="player-card">
          @php
            $charAvatars = [
              'nova' => '⭐','quantum' => '⚡','vortex' => '🌀','titan' => '🛡️',
              'circuit' => '💡','cryptos' => '🔐','apex' => '🏆'
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
          <div class="stat-box"><div class="stat-label">Score</div><div class="stat-value score" id="scoreDisplay">0 pts</div></div>
          <div class="stat-box"><div class="stat-label">Streak</div><div class="stat-value streak" id="streakDisplay">🔥 0x</div></div>
        </div>
        <div class="progress-wrap">
          <div class="progress-header"><span>Progress to Finish</span><span id="progressText">Tile 1 of 30 (0%)</span></div>
          <div class="progress-bar-bg"><div class="progress-bar-fill" id="progressBar"></div></div>
        </div>
      </div>

      <div class="sidebar-panel dice-panel">
        <h3><span>3D Cyber Dice</span><span style="font-size:0.75rem; color:#94a3b8;"><i class="bi bi-keyboard"></i> Space</span></h3>
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
        <button class="roll-btn" id="rollBtn" type="button"><i class="bi bi-dice-6-fill"></i> ROLL &amp; MOVE</button>
        <div class="roll-hint" id="statusHint">Press Space or click Roll to navigate the maze!</div>
      </div>
    </aside>
  </main>
</div>

<div class="challenge-overlay" id="challengeOverlay" role="dialog" aria-modal="true" aria-labelledby="challengeBadge">
  <div class="challenge-card">
    <div class="challenge-header">
      <div class="challenge-badge" id="challengeBadge"><i class="bi bi-lightning-charge-fill"></i> MDAS Challenge</div>
      <div style="font-size:0.8rem; color:#94a3b8; font-weight:700;" id="challengePointsTag">+10 pts</div>
    </div>
    <div class="expr-box" id="exprBox" aria-live="polite">—</div>
    <div class="challenge-rule-strip">Rule: <b>Multiplication &amp; Division</b> first (Left to Right), then <b>Addition &amp; Subtraction</b>!</div>
    <div class="coin-hint-bar" id="coinHintBar">
      <div class="coin-hint-info">Need help? <span>Spend 5 🪙</span> for a <b>How to Solve It</b> hint!</div>
      <button type="button" class="btn-coin-hint" id="btnCoinHint" onclick="useCoinHint()">🪙 Hint (5 🪙)</button>
    </div>
    <div class="hint-reveal-box" id="hintRevealBox">
      <div class="hint-reveal-title"><i class="bi bi-lightbulb-fill"></i> How to Solve this MDAS Expression:</div>
      <div id="hintRevealContent"></div>
    </div>
    <form class="challenge-form" id="answerForm" onsubmit="return false;">
      <div class="input-row">
        <input type="number" class="challenge-input" id="answerInput" inputmode="numeric" placeholder="Your Answer" autocomplete="off" aria-label="Enter numerical answer">
        <button type="submit" class="submit-btn" id="submitBtn">Check</button>
      </div>
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
    <div class="challenge-feedback" id="feedback" aria-live="polite">Solve the expression following the Order of Operations!</div>
  </div>
</div>

<div id="tutorialOverlay" class="fc-overlay" role="dialog" aria-modal="true" aria-label="MDAS Maze Instructions">
  <div class="fc-deck">
    <div class="fc-deck-stack-1"></div>
    <div class="fc-deck-stack-2"></div>
    <div class="fc-card">
      <div class="fc-top">
        <span class="fc-badge" id="fcBadge">🃏 HOW TO PLAY • CARD 1 OF 3</span>
        <button class="fc-quick-action-btn" id="fcQuickActionBtn" type="button" onclick="closeTut(true)" title="Skip to Play">
          <span>Skip to Play</span> <i class="bi bi-arrow-right-short"></i>
        </button>
      </div>

      <!-- CARD 1: Roll → Hop → Solve -->
      <div id="fcSlide1" class="fc-slide">
        <h3 class="fc-title" style="color:#38bdf8;"><span>🎯</span> Roll, Hop, Solve!</h3>
        <div class="fc-flow">
          <div class="fc-flow-step">
            <div class="fc-flow-ico">🎲</div>
            <div class="fc-flow-lbl">Roll</div>
          </div>
          <div class="fc-flow-arrow">➔</div>
          <div class="fc-flow-step">
            <div class="fc-flow-ico">🚶</div>
            <div class="fc-flow-lbl">Hop</div>
          </div>
          <div class="fc-flow-arrow">➔</div>
          <div class="fc-flow-step">
            <div class="fc-flow-ico">🧮</div>
            <div class="fc-flow-lbl">Solve</div>
          </div>
        </div>
        <div class="fc-tip"><i class="bi bi-keyboard"></i> Press <b>Space</b> or tap the button to roll!</div>
      </div>

      <!-- CARD 2: MDAS Order -->
      <div id="fcSlide2" class="fc-slide hidden">
        <h3 class="fc-title" style="color:#c084fc;"><span>📚</span> MDAS Order</h3>
        <div class="fc-priority-row">
          <div class="fc-priority-card fc-priority-first">
            <div class="fc-priority-rank">1st</div>
            <div class="fc-priority-ops">× ÷</div>
            <div class="fc-priority-lbl">Multiply<br>&amp; Divide</div>
          </div>
          <div class="fc-priority-vs">before</div>
          <div class="fc-priority-card fc-priority-second">
            <div class="fc-priority-rank">2nd</div>
            <div class="fc-priority-ops">+ −</div>
            <div class="fc-priority-lbl">Add<br>&amp; Subtract</div>
          </div>
        </div>
        <div class="fc-tip">💡 <b>× and ÷</b> always come first!</div>
      </div>

      <!-- CARD 3: Color Zones + Let's Play -->
      <div id="fcSlide3" class="fc-slide hidden">
        <h3 class="fc-title" style="color:#34d399;"><span>🗺️</span> Color Zones</h3>
        <div class="fc-zone-row">
          <div class="fc-zone-chip"><div class="fc-zone-dot" style="background:#f43f5e;color:#f43f5e;"></div><div class="fc-zone-ops">× +</div></div>
          <div class="fc-zone-chip"><div class="fc-zone-dot" style="background:#38bdf8;color:#38bdf8;"></div><div class="fc-zone-ops">÷ −</div></div>
          <div class="fc-zone-chip"><div class="fc-zone-dot" style="background:#34d399;color:#34d399;"></div><div class="fc-zone-ops">× ÷</div></div>
          <div class="fc-zone-chip"><div class="fc-zone-dot" style="background:#a855f7;color:#a855f7;"></div><div class="fc-zone-ops">★ MDAS</div></div>
        </div>
        <div class="fc-tip">🪙 Stuck? Spend <b>5 coins</b> for a hint — <b>never the answer</b>!</div>
        <div class="fc-action-row">
          <button type="button" class="fc-action-btn fc-action-play" onclick="closeTut(true)">🚀 Let's Play!</button>
        </div>
      </div>

      <div class="fc-footer">
        <button class="fc-nav-btn" id="fcPrevBtn" type="button" onclick="stepFlashCard(-1)"><i class="bi bi-arrow-left"></i> Prev</button>
        <div class="fc-dots" id="fcDots" role="tablist" aria-label="Tutorial Cards">
          <button class="fc-dot active" type="button" onclick="goFlashCard(1)" title="Card 1"></button>
          <button class="fc-dot" type="button" onclick="goFlashCard(2)" title="Card 2"></button>
          <button class="fc-dot" type="button" onclick="goFlashCard(3)" title="Card 3"></button>
        </div>
        <button class="fc-nav-btn primary" id="fcNextBtn" type="button" onclick="stepFlashCard(1)"><span>Next</span> <i class="bi bi-arrow-right"></i></button>
      </div>
    </div>
  </div>
</div>

<div class="pause-overlay" id="pauseOverlay">
  <div class="pause-box">
    <h2>Game Paused</h2>
    <p>Take a breather! Ready to jump back into the MDAS Maze?</p>
    <button class="resume-btn" id="resumeBtn" type="button" onclick="togglePause()">Resume Game</button>
    <div class="eduplay-game-nav" style="display:flex;gap:10px;width:100%;justify-content:center;margin-top:14px;">
      <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" style="flex:1;justify-content:center;"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
      <a href="{{ route('student.games') }}" class="eduplay-nav-btn eduplay-btn-exit" style="flex:1;justify-content:center;"><i class="bi bi-box-arrow-right"></i> <span>Exit</span></a>
    </div>
  </div>
</div>

<div class="winner-overlay" id="winnerOverlay">
  <div class="winner-card" id="winnerCard"></div>
</div>

<!-- BURGER MENU OVERLAY -->
<div class="game-burger-overlay" id="burgerOverlay" onclick="toggleBurgerMenu()"></div>

<!-- BURGER MENU -->
<div class="game-burger-menu" id="burgerMenu">
  <div class="game-burger-header">
    <div class="game-burger-title">
      <i class="bi bi-menu-button-wide-fill"></i>
      Game Menu
    </div>
    <div class="game-burger-subtitle">MDAS Maze</div>
  </div>
  
  <nav class="game-burger-nav">
    <!-- Music Settings Button (with collapsible controls) - Replaces Sound button -->
    <button type="button" class="eduplay-nav-btn eduplay-btn-help" onclick="toggleBurgerMenu(); openTut();" title="How to Play & Tutorial" style="background:rgba(56,189,248,.15);border-color:rgba(56,189,248,.4);color:#38bdf8;">
      <i class="bi bi-question-circle-fill"></i> <span>Tutorial</span>
    </button>
    <button type="button" class="eduplay-nav-btn eduplay-btn-pause" id="pauseBtnBurger" onclick="toggleBurgerMenu();" title="Resume Game">
      <i class="bi bi-play-fill"></i> <span>Resume</span>
    </button>
    <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map">
      <i class="bi bi-map-fill"></i> <span>Game Map</span>
    </a>
    <div class="game-burger-music-row">
      <button type="button" class="game-mute-btn" id="muteBtn" onclick="toggleMute()" title="Mute / Unmute">
        <i class="bi bi-volume-up-fill" id="muteIcon"></i>
      </button>
      <input type="range" class="game-vol-slider" id="volumeSlider" min="0" max="100" value="50" oninput="setVolume(this.value)">
      <span class="game-vol-value" id="volumeValue">50%</span>
    </div>
    <a href="{{ route('student.games') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Game Arena">
      <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
    </a>
  </nav>
</div>

<script src="{{ asset('js/game-feedback.js') }}"></script>
<script src="{{ asset('js/game-music-player.js') }}"></script>
<script src="{{ asset('js/game-burger-menu.js') }}"></script>
<script>
let musicPlayer;
if (window.GameMusicPlayer) {
    musicPlayer = new window.GameMusicPlayer("{{ asset('sounds/Joystick_Rhapsody.mp3') }}");
}

// Toggle music settings panel
function toggleMusicSettings() {}

// Burger menu toggle — auto-pause on open, auto-resume on close
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
    document.body.style.overflow = 'hidden';
    if (typeof togglePause === 'function') togglePause();
  } else {
    document.body.style.overflow = '';
    if (typeof togglePause === 'function') togglePause();
  }
}

// Music controls
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

// Volume control
document.addEventListener('DOMContentLoaded', function() {
  const volumeSlider = document.getElementById('volumeSlider');
  if (volumeSlider) volumeSlider.addEventListener('input', function() { setVolume(this.value); });
  if (musicPlayer) {
    setTimeout(function() {
      const icon = document.getElementById('muteIcon');
      const btn = document.getElementById('muteBtn');
      if (musicPlayer.isPlaying) {
        if (icon) icon.className = 'bi bi-volume-up-fill';
        if (btn) btn.classList.remove('muted');
      }
    }, 500);
  }
});
</script>
<script>
(function(){
  "use strict";

  const CURRENT_LEVEL = {{ $progress->current_level ?? 1 }};
  const GAME_ID = {{ $game->id ?? 0 }};
  const CSRF_TOKEN = '{{ csrf_token() }}';
  const MAX_LEVELS = 10;
  const USER_ID = '{{ auth()->id() ?? 0 }}';

  const COINS_KEY = 'eduplay_mdas_coins_' + USER_ID;
  const SOUND_KEY = 'eduplay_mdas_muted_' + USER_ID;

  const BOARD_SIZE = 30;
  const mobileMQ = window.matchMedia('(max-width: 680px)');
  let COLS = mobileMQ.matches ? 3 : 6;

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

  /* ------------------------------------------------------------------ */
  /* FLOATING MATH SIGNS                                                */
  /* ------------------------------------------------------------------ */
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
    const count = window.matchMedia('(max-width: 680px)').matches ? 30 : 65;
    for(let i = 0; i < count; i++){
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

  /* ------------------------------------------------------------------ */
  /* AUDIO                                                              */
  /* ------------------------------------------------------------------ */
  var audioCtx = null;
  var isMuted = localStorage.getItem(SOUND_KEY) === 'true';

  function initAudio() {
    if (!audioCtx) {
      const AC = window.AudioContext || window.webkitAudioContext;
      if (AC) audioCtx = new AC();
    }
    if (audioCtx && audioCtx.state === 'suspended') audioCtx.resume();
  }
  function playTone(freq, duration, type, gainVal) {
    type = type || 'sine'; gainVal = gainVal == null ? 0.15 : gainVal;
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
      osc.connect(gain); gain.connect(audioCtx.destination);
      osc.start(); osc.stop(audioCtx.currentTime + duration);
    } catch(e) {}
  }
  function soundRoll() {
    if (isMuted) return;
    for (let i = 0; i < 6; i++) setTimeout(() => playTone(180 + Math.random() * 120, 0.08, 'triangle', 0.1), i * 90);
  }
  function soundHop() { playTone(420, 0.12, 'sine', 0.2); }
  function soundCoin() { playTone(987, 0.1, 'triangle', 0.25); setTimeout(() => playTone(1318, 0.2, 'triangle', 0.25), 100); }
  function soundCorrect() {
    playTone(523.25, 0.1, 'sine', 0.2);
    setTimeout(() => playTone(659.25, 0.1, 'sine', 0.2), 90);
    setTimeout(() => playTone(783.99, 0.15, 'sine', 0.25), 180);
    setTimeout(() => playTone(1046.50, 0.35, 'sine', 0.3), 270);
  }
  function soundWrong() { playTone(280, 0.18, 'sawtooth', 0.15); setTimeout(() => playTone(220, 0.25, 'sawtooth', 0.15), 150); }
  function soundWin() {
    const notes = [523, 659, 783, 1046, 1318];
    notes.forEach((f, idx) => setTimeout(() => playTone(f, 0.25, 'triangle', 0.25), idx * 120));
  }

  /* ------------------------------------------------------------------ */
  /* COINS                                                              */
  /* ------------------------------------------------------------------ */
  function getStoredCoins() {
    const val = localStorage.getItem(COINS_KEY);
    if (!val) { localStorage.setItem(COINS_KEY, '50'); return 50; }
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
      if (player.coins < 5) { btnHint.disabled = true; btnHint.title = 'Need 5 coins for hint'; }
      else { btnHint.disabled = false; btnHint.title = 'Spend 5 coins for a How to Solve It hint'; }
    }
  }

  /* ------------------------------------------------------------------ */
  /* GAME STATE                                                         */
  /* ------------------------------------------------------------------ */
  var player = {
    position: 0, score: 0, streak: 0,
    coins: getStoredCoins(), color: '#f59e0b',
    avatar: @json($avatarEmoji ?? '🚀')
  };

  var boardTiles = [];
  var questionsAnswered = 0;      // Track actual questions answered
  var questionsCorrect = 0;       // Track correct answers
  var gameActive = true;
  var isPaused = false;
  var busy = false;
  var awaitingAnswer = false;
  var currentChallenge = null;
  var rotX = 0, rotY = 0;
  var hintRevealed = false;
  var tutorialOpen = false;       // referenced by triggerRoll()

  /* ------------------------------------------------------------------ */
  /* EXPRESSION GENERATOR                                               */
  /* ------------------------------------------------------------------ */
  function gcd(a, b) { return b === 0 ? a : gcd(b, a % b); }

  function generateMDASExpression(type) {
    const maxVal = Math.min(24, CURRENT_LEVEL * 2 + 5);
    while (true) {
      if (type === 'gold') {
        const gp = Math.floor(Math.random() * 3);
        if (gp === 0) {
          const b = Math.floor(Math.random() * 6) + 2;
          const c = Math.floor(Math.random() * 4) + 2;
          const mult = Math.floor(Math.random() * 4) + 1;
          const a = (c * mult) / gcd(b, c);
          const d = Math.floor(Math.random() * maxVal) + 2;
          const e = Math.floor(Math.random() * 8) + 1;
          const p1 = (a * b) / c;
          const ans = p1 + d - e;
          if (ans > 0 && Number.isInteger(ans) && ans < 300) {
            return {
              expr: `${a} × ${b} ÷ ${c} + ${d} − ${e}`, answer: ans, zone: type,
              howToSolve: `
                <div><b>1️⃣ Priority 1 (Left to Right):</b> Multiply and Divide from left to right!</div>
                <div style="margin-top:4px;">👉 Multiply first: <b>${a} × ${b} = ${a * b}</b></div>
                <div>👉 Next, divide by ${c}: <b>${a * b} ÷ ${c} = ${p1}</b></div>
                <div style="margin-top:4px;"><b>2️⃣ Priority 2:</b> Now <code>${p1} + ${d} − ${e}</code> from left to right.</div>
              `
            };
          }
        } else if (gp === 1) {
          const c = Math.floor(Math.random() * 6) + 2;
          const b = Math.floor(Math.random() * 6) + 2;
          const e = Math.floor(Math.random() * 4) + 2;
          const dm = Math.floor(Math.random() * 4) + 1;
          const d = e * dm;
          const a = Math.floor(Math.random() * 10) + 1;
          const mv = b * c, dv = d / e;
          const ans = a + mv - dv;
          if (ans > 0 && Number.isInteger(ans) && ans < 300) {
            return {
              expr: `${a} + ${b} × ${c} − ${d} ÷ ${e}`, answer: ans, zone: type,
              howToSolve: `
                <div><b>1️⃣ Priority 1 (× &amp; ÷):</b> Solve × and ÷ before + and −!</div>
                <div style="margin-top:4px;">👉 <b>${b} × ${c} = ${mv}</b></div>
                <div>👉 <b>${d} ÷ ${e} = ${dv}</b></div>
                <div style="margin-top:4px;"><b>2️⃣ Priority 2:</b> Now <code>${a} + ${mv} − ${dv}</code> from left to right.</div>
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
            return {
              expr: `${a} − ${b} × ${c} ÷ ${d} + ${e}`, answer: ans, zone: type,
              howToSolve: `
                <div><b>1️⃣ Priority 1 (× &amp; ÷):</b> Do × and ÷ first, left to right.</div>
                <div style="margin-top:4px;">👉 <b>${b} × ${c} = ${b * c}</b></div>
                <div>👉 <b>${b * c} ÷ ${d} = ${p1}</b></div>
                <div style="margin-top:4px;"><b>2️⃣ Priority 2:</b> Now <code>${a} − ${p1} + ${e}</code> left to right.</div>
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
          if (ans < 250) return { expr: `${a} × ${b} + ${c}`, answer: ans, zone: type, howToSolve: `
            <div><b>1️⃣ MDAS:</b> Multiplication BEFORE Addition.</div>
            <div style="margin-top:4px;">👉 <b>${a} × ${b} = ${a * b}</b>, then + ${c}.</div>` };
        } else {
          const ans = a + b * c;
          if (ans < 250) return { expr: `${a} + ${b} × ${c}`, answer: ans, zone: type, howToSolve: `
            <div><b>1️⃣ MDAS:</b> Multiply first — do NOT add ${a} + ${b}!</div>
            <div style="margin-top:4px;">👉 <b>${b} × ${c} = ${b * c}</b>, then + ${a}.</div>` };
        }
      } else if (type === 'blue') {
        const isFirst = Math.random() < 0.5;
        const c = Math.floor(Math.random() * 6) + 2;
        const mult = Math.floor(Math.random() * 6) + 2;
        const b = c * mult;
        if (isFirst) {
          const dv = b / c;
          const d = Math.floor(Math.random() * (dv - 1)) + 1;
          const ans = dv - d;
          if (ans > 0) return { expr: `${b} ÷ ${c} − ${d}`, answer: ans, zone: type, howToSolve: `
            <div><b>1️⃣ MDAS:</b> Division BEFORE Subtraction.</div>
            <div style="margin-top:4px;">👉 <b>${b} ÷ ${c} = ${dv}</b>, then − ${d}.</div>` };
        } else {
          const dv = b / c;
          const a = dv + Math.floor(Math.random() * 12) + 2;
          const ans = a - dv;
          if (ans > 0) return { expr: `${a} − ${b} ÷ ${c}`, answer: ans, zone: type, howToSolve: `
            <div><b>1️⃣ MDAS:</b> Divide first — do NOT do ${a} − ${b}!</div>
            <div style="margin-top:4px;">👉 <b>${b} ÷ ${c} = ${dv}</b>, then ${a} − ${dv}.</div>` };
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
            return { expr: `${a} × ${b} ÷ ${c}`, answer: ans, zone: type, howToSolve: `
              <div><b>1️⃣ × and ÷ have EQUAL priority.</b> Solve strictly LEFT to RIGHT.</div>
              <div style="margin-top:4px;">👉 <b>${a} × ${b} = ${a * b}</b>, then ÷ ${c}.</div>` };
          }
        } else {
          const b = Math.floor(Math.random() * 5) + 2;
          const mult = Math.floor(Math.random() * 6) + 2;
          const a = b * mult;
          const c = Math.floor(Math.random() * 6) + 2;
          const dv = a / b;
          const ans = dv * c;
          if (ans > 0 && Number.isInteger(ans) && ans < 150) {
            return { expr: `${a} ÷ ${b} × ${c}`, answer: ans, zone: type, howToSolve: `
              <div><b>1️⃣ ÷ and × have EQUAL priority.</b> LEFT to RIGHT.</div>
              <div style="margin-top:4px;">👉 <b>${a} ÷ ${b} = ${dv}</b>, then × ${c}.</div>` };
          }
        }
      }
    }
  }

  /* ------------------------------------------------------------------ */
  /* DOM REFS                                                           */
  /* ------------------------------------------------------------------ */
  const mazeGrid = document.getElementById("mazeGrid");
  const scoreDisplay = document.getElementById("scoreDisplay");
  const streakDisplay = document.getElementById("streakDisplay");
  const progressBar = document.getElementById("progressBar");
  const progressText = document.getElementById("progressText");
  const dieEl = document.getElementById("die");
  const rollBtn = document.getElementById("rollBtn");
  const statusHint = document.getElementById("statusHint");

  /* ------------------------------------------------------------------ */
  /* BOARD                                                              */
  /* ------------------------------------------------------------------ */
  function buildBoard() {
    COLS = mobileMQ.matches ? 3 : 6;
    mazeGrid.style.gridTemplateColumns = 'repeat(' + COLS + ', minmax(0, 1fr))';
    boardTiles = [];
    for (var i = 0; i < BOARD_SIZE; i++) {
      var type;
      if (i === 0) type = "start";
      else if (i === BOARD_SIZE - 1) type = "finish";
      else if (i === 5 || i === 12 || i === 23) type = "neutral";
      else if (i % 7 === 0 && i !== 0 && i !== 29) type = "gold";
      else type = ZONES[(i + CURRENT_LEVEL) % 3];
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

      var num = document.createElement("span");
      num.className = "cell-num";
      num.textContent = (idx + 1);
      cell.appendChild(num);

      var label = document.createElement("span");
      label.className = "op-label";
      var sub = document.createElement("span");
      sub.className = "op-sub";
      if (type2 === "start") { label.innerHTML = "<i class='bi bi-play-fill'></i> START"; sub.textContent = "Portal"; }
      else if (type2 === "finish") { label.innerHTML = "<i class='bi bi-trophy-fill'></i> FINISH"; sub.textContent = "Victory"; }
      else if (type2 === "neutral") { label.innerHTML = "<i class='bi bi-cup-hot-fill'></i> REST"; sub.textContent = "Safe Spot"; }
      else if (type2 === "gold") { label.innerHTML = "★ MDAS"; sub.textContent = "Master"; }
      else { label.textContent = ZONE_CONFIG[type2].label; sub.textContent = type2 === "red" ? "× and +" : (type2 === "blue" ? "÷ and −" : "× and ÷"); }
      cell.appendChild(label);
      cell.appendChild(sub);

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
    if (mobileMQ.matches) {
      var cell = mazeGrid.querySelector('.cell[data-index="' + index + '"]');
      if (cell && cell.scrollIntoView) cell.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }
  function updateStatsUI() {
    scoreDisplay.textContent = player.score + " pts";
    streakDisplay.textContent = "🔥 " + player.streak + "x";
    var pct = Math.round((player.position / (BOARD_SIZE - 1)) * 100);
    progressBar.style.width = pct + "%";
    progressText.textContent = "Tile " + (player.position + 1) + " of " + BOARD_SIZE + " (" + pct + "%)";
  }

  /* ------------------------------------------------------------------ */
  /* DICE & MOVEMENT                                                    */
  /* ------------------------------------------------------------------ */
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
    if (stepsLeft <= 0 || player.position >= BOARD_SIZE - 1) { afterMove(); return; }
    player.position = Math.min(player.position + 1, BOARD_SIZE - 1);
    moveTokenTo(player.position, true);
    updateStatsUI();
    if (player.position >= BOARD_SIZE - 1) { setTimeout(function(){ afterMove(); }, 200); return; }
    setTimeout(function(){ stepPlayer(stepsLeft - 1); }, 280);
  }

  /* ------------------------------------------------------------------ */
  /* AFTER MOVE                                                         */
  /* ------------------------------------------------------------------ */
  function afterMove() {
    if (player.position >= BOARD_SIZE - 1) {
      player.score += 30;
      const finishCoins = 25 + (player.streak * 5);
      saveCoins(player.coins + finishCoins);
      showWinner();
      return;
    }
    var tileType = boardTiles[player.position];
    if (tileType === 'neutral') {
      busy = false;
      rollBtn.disabled = false;
      statusHint.textContent = "Resting… Ready to roll!";
      return;
    }
    if (tileType === 'start') {
      busy = false;
      rollBtn.disabled = false;
      return;
    }
    openChallenge(tileType);
  }

  /* ------------------------------------------------------------------ */
  /* CHALLENGE MODAL                                                    */
  /* ------------------------------------------------------------------ */
  function formatExprHTML(expr) {
    return expr
      .replace(/×/g, '<span class="op-m">×</span>')
      .replace(/÷/g, '<span class="op-d">÷</span>')
      .replace(/\+/g, '<span class="op-a">+</span>')
      .replace(/−/g, '<span class="op-s">−</span>');
  }

  function openChallenge(zone) {
    awaitingAnswer = true;
    busy = false;
    rollBtn.disabled = true;
    currentChallenge = generateMDASExpression(zone);
    hintRevealed = false;

    document.getElementById('exprBox').innerHTML = formatExprHTML(currentChallenge.expr);
    document.getElementById('challengePointsTag').textContent =
      zone === 'gold' ? '+20 pts' : '+10 pts';
    document.getElementById('answerInput').value = '';
    document.getElementById('feedback').textContent = 'Solve the expression following the Order of Operations!';
    document.getElementById('feedback').className = 'challenge-feedback';
    document.getElementById('hintRevealBox').classList.remove('active');
    document.getElementById('hintRevealContent').innerHTML = '';

    var btnHint = document.getElementById('btnCoinHint');
    btnHint.innerHTML = '🪙 Hint (5 🪙)';
    updateCoinsUI();

    document.getElementById('challengeOverlay').classList.add('active');
    setTimeout(function(){ document.getElementById('answerInput').focus(); }, 120);
  }

  function submitAnswer() {
    if (!currentChallenge || !awaitingAnswer) return;
    
    // Immediately set awaitingAnswer to false to prevent double submission
    awaitingAnswer = false;
    
    var input = document.getElementById('answerInput');
    var val = parseInt(input.value, 10);
    var fb = document.getElementById('feedback');
    if (isNaN(val)) {
      fb.textContent = 'Please type a number first!';
      fb.className = 'challenge-feedback bad';
      // Re-enable awaiting answer since this wasn't a valid submission
      awaitingAnswer = true;
      return;
    }
    
    // Track that a question was answered
    questionsAnswered++;
    
    var points = currentChallenge.zone === 'gold' ? 20 : 10;
    if (val === currentChallenge.answer) {
      soundCorrect();
      questionsCorrect++;  // Track correct answer
      player.score += points;
      player.streak++;
      var bonus = currentChallenge.zone === 'gold' ? 3 : 1;
      saveCoins(player.coins + bonus);
      updateStatsUI();
      fb.innerHTML = '<b>✓ Correct!</b> +' + points + ' pts, +' + bonus + ' coin' + (bonus > 1 ? 's' : '') +
        (player.streak > 1 ? ' • 🔥 ' + player.streak + 'x streak!' : '');
      fb.className = 'challenge-feedback good';
      setTimeout(closeChallengeAndContinue, 1400);
    } else {
      soundWrong();
      player.streak = 0;
      updateStatsUI();
      fb.innerHTML = '<b>✗ Not quite.</b> The answer was <b>' + currentChallenge.answer + '</b>. Keep moving!';
      fb.className = 'challenge-feedback bad';
      setTimeout(closeChallengeAndContinue, 2200);
    }
  }

  function closeChallengeAndContinue() {
    document.getElementById('challengeOverlay').classList.remove('active');
    awaitingAnswer = false;
    currentChallenge = null;
    busy = false;
    rollBtn.disabled = false;
    statusHint.textContent = "Ready to roll again!";
  }

  window.pressKey = function(key) {
    var input = document.getElementById('answerInput');
    if (key === 'clear') input.value = '';
    else if (key === 'backspace') input.value = input.value.slice(0, -1);
    else input.value += key;
    input.focus();
  };

  window.useCoinHint = function() {
    if (!currentChallenge || hintRevealed) return;
    if (player.coins < 5) return;
    saveCoins(player.coins - 5);
    hintRevealed = true;
    document.getElementById('hintRevealContent').innerHTML = currentChallenge.howToSolve;
    document.getElementById('hintRevealBox').classList.add('active');
    var btn = document.getElementById('btnCoinHint');
    btn.disabled = true;
    btn.innerHTML = '✓ Hint Used';
    soundCoin();
  };

  /* ------------------------------------------------------------------ */
  /* PAUSE                                                              */
  /* ------------------------------------------------------------------ */
  function togglePause() {
    if (!gameActive) return;
    isPaused = !isPaused;
    var ov = document.getElementById('pauseOverlay');
    var btn = document.getElementById('topPauseBtn');
    if (isPaused) {
      if (ov) ov.classList.add('active');
      if (btn) {
        btn.classList.add('paused');
        btn.innerHTML = '<i class="bi bi-play-fill"></i> <span>Resume</span>';
      }
      rollBtn.disabled = true;
    } else {
      if (ov) ov.classList.remove('active');
      if (btn) {
        btn.classList.remove('paused');
        btn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
      }
      if (!awaitingAnswer && !busy) rollBtn.disabled = false;
    }
  }
  window.togglePause = togglePause;

  /* ------------------------------------------------------------------ */
  /* SOUND TOGGLE                                                       */
  /* ------------------------------------------------------------------ */
  function toggleSound() {
    isMuted = !isMuted;
    localStorage.setItem(SOUND_KEY, isMuted ? 'true' : 'false');
    var btn = document.getElementById('soundBtn');
    var icon = document.getElementById('soundIcon');
    var text = document.getElementById('soundText');
    if (isMuted) {
      if (btn) btn.classList.add('muted');
      if (icon) icon.className = 'bi bi-volume-mute-fill';
      if (text) text.textContent = 'Muted';
    } else {
      if (btn) btn.classList.remove('muted');
      if (icon) icon.className = 'bi bi-volume-up-fill';
      if (text) text.textContent = 'Sound';
    }
  }

  /* ------------------------------------------------------------------ */
  /* TUTORIAL CONTROLLER (3 visual cards)                              */
  /* ------------------------------------------------------------------ */
  var currentFcCard = 1;
  const TOTAL_FC_CARDS = 3;

  window.goFlashCard = function(cardNum) {
    if (cardNum < 1) cardNum = 1;
    if (cardNum > TOTAL_FC_CARDS) cardNum = TOTAL_FC_CARDS;
    currentFcCard = cardNum;
    for (var i = 1; i <= TOTAL_FC_CARDS; i++) {
      var slide = document.getElementById('fcSlide' + i);
      if (slide) slide.classList.toggle('hidden', i !== currentFcCard);
    }
    document.querySelectorAll('#fcDots .fc-dot').forEach(function(dot, idx) {
      dot.classList.toggle('active', idx === currentFcCard - 1);
    });
    var badge = document.getElementById('fcBadge');
    if (badge) {
      var tags = ['ROLL & SOLVE', 'MDAS ORDER', 'COLOR ZONES'];
      badge.textContent = '🃏 HOW TO PLAY • CARD ' + currentFcCard + ' OF ' + TOTAL_FC_CARDS + ' • ' + tags[currentFcCard - 1];
    }
    var prevBtn = document.getElementById('fcPrevBtn');
    if (prevBtn) prevBtn.disabled = (currentFcCard === 1);
    var nextBtn = document.getElementById('fcNextBtn');
    if (nextBtn) {
      if (currentFcCard === TOTAL_FC_CARDS) nextBtn.innerHTML = "<span>🚀 Let's Play!</span>";
      else nextBtn.innerHTML = '<span>Next</span> <i class="bi bi-arrow-right"></i>';
    }
  };

  window.stepFlashCard = function(delta) {
    if (currentFcCard === TOTAL_FC_CARDS && delta > 0) { closeTut(true); return; }
    goFlashCard(currentFcCard + delta);
  };

  window.openTut = function() {
    tutorialOpen = true;
    document.getElementById('tutorialOverlay').classList.add('active');
    goFlashCard(1);
    rollBtn.disabled = true;
  };

  window.closeTut = function(andPlay) {
    document.getElementById('tutorialOverlay').classList.remove('active');
    tutorialOpen = false;
    if (andPlay && gameActive && !isPaused) rollBtn.disabled = false;
    statusHint.textContent = andPlay ? "Ready to roll! Press Space or tap Roll." : "Ready when you are!";
  };

  /* ------------------------------------------------------------------ */
  /* WINNER                                                             */
  /* ------------------------------------------------------------------ */
  function showWinner() {
    gameActive = false;
    
    var card = document.getElementById('winnerCard');
    if (!card) return;
    
    // Calculate percentage based on questions actually answered
    const scorePercentage = questionsAnswered > 0
      ? Math.round((questionsCorrect / questionsAnswered) * 100)
      : 0;
    
    // Determine stars based on percentage
    let stars = 0;
    let passed = false;
    if (scorePercentage >= 95) {
      stars = 3;
      passed = true;
    } else if (scorePercentage >= 85) {
      stars = 2;
      passed = true;
    } else if (scorePercentage >= 75) {
      stars = 1;
      passed = true;
    } else {
      stars = 0;
      passed = false;
    }
    
    // Play appropriate sound
    if (passed) {
      soundWin();
      if (window.playCorrectSound) {
        window.playCorrectSound();
      }
    } else {
      soundWrong();
      if (window.playWrongSound) {
        window.playWrongSound();
      }
    }
    
    var isFinal = CURRENT_LEVEL >= MAX_LEVELS;
    var nextLevel = passed ? (isFinal ? 1 : CURRENT_LEVEL + 1) : CURRENT_LEVEL;
    
    // Build star display
    let starDisplay = '';
    if (passed) {
      for (let i = 0; i < 3; i++) {
        starDisplay += i < stars ? '⭐' : '☆';
      }
    }

    card.innerHTML =
      '<div class="trophy">' + (passed ? '🏆' : '😔') + '</div>' +
      '<h2>' + (passed ? (isFinal ? 'Master of the MDAS Maze!' : 'Level Complete!') : 'Level Failed') + '</h2>' +
      '<p style="color:#94a3b8;margin:8px 0;">' +
        (passed 
          ? (isFinal ? 'You cleared all ' + MAX_LEVELS + ' levels!' : 'Stage ' + CURRENT_LEVEL + ' cleared!') 
          : 'You need 75% or higher to pass') +
      '</p>' +
      (passed ? '<div style="font-size:2rem;margin:8px 0;">' + starDisplay + '</div>' : '') +
      '<div class="final-score">' + questionsCorrect + ' / ' + questionsAnswered + ' correct (' + scorePercentage + '%)</div>' +
      '<p style="color:#cbd5e1;font-size:0.85rem;margin:4px 0;">Score: ' + player.score + ' pts</p>' +
      '<p style="color:#94a3b8;font-size:0.9rem;margin-bottom:16px;">🪙 ' + player.coins + ' coins saved</p>' +
      '<button class="play-again" id="playAgainBtn">' +
        (passed 
          ? (isFinal ? '🔄 Play Again' : '▶️ Next Level')
          : '🔄 Retry Level') +
      '</button>' +
      '<div style="margin-top:12px;display:flex;gap:8px;justify-content:center;">' +
        '<a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" style="flex:1;justify-content:center;text-decoration:none;">' +
          '<i class="bi bi-map-fill"></i> <span>Game Map</span></a>' +
        '<a href="{{ route('student.games') }}" class="eduplay-nav-btn eduplay-btn-exit" style="flex:1;justify-content:center;text-decoration:none;">' +
          '<i class="bi bi-box-arrow-right"></i> <span>Exit</span></a>' +
      '</div>';

    document.getElementById('winnerOverlay').classList.add('active');

    // Only update progress if passed
    if (passed) {
      fetch('/student/update-progress', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: new URLSearchParams({ game_id: GAME_ID, level: nextLevel, score: player.score, _token: CSRF_TOKEN })
      }).catch(function(){});
    }

    var btn = document.getElementById('playAgainBtn');
    if (btn) btn.addEventListener('click', function(){ window.location.reload(); });
  }

  /* ------------------------------------------------------------------ */
  /* KEYBOARD                                                           */
  /* ------------------------------------------------------------------ */
  document.addEventListener('keydown', function(e) {
    if (tutorialOpen) {
      if (e.key === ' ' || e.key === 'Enter' || e.key === 'ArrowRight') {
        e.preventDefault();
        stepFlashCard(1);
      } else if (e.key === 'ArrowLeft') {
        e.preventDefault();
        stepFlashCard(-1);
      } else if (e.key === 'Escape') {
        e.preventDefault();
        closeTut(true);
      }
      return;
    }
    if (awaitingAnswer) {
      if (e.key === 'Enter') { e.preventDefault(); submitAnswer(); }
      return;
    }
    if (e.key === ' ' || e.key === 'Spacebar') {
      e.preventDefault();
      triggerRoll();
    } else if (e.key === 'p' || e.key === 'P' || e.key === 'Escape') {
      e.preventDefault();
      togglePause();
    } else if (e.key === 'm' || e.key === 'M') {
      toggleSound();
    }
  });

  /* ------------------------------------------------------------------ */
  /* RESIZE                                                             */
  /* ------------------------------------------------------------------ */
  mobileMQ.addEventListener('change', function(){ buildBoard(); renderToken(); });
  window.addEventListener('resize', function(){
    var newCols = mobileMQ.matches ? 3 : 6;
    if (newCols !== COLS) { buildBoard(); renderToken(); }
  });

  /* ------------------------------------------------------------------ */
  /* ENTRY POINT                                                        */
  /* ------------------------------------------------------------------ */
  (function init() {
    buildBoard();
    renderToken();
    updateStatsUI();
    updateCoinsUI();

    if (isMuted) {
      var sb = document.getElementById('soundBtn');
      var si = document.getElementById('soundIcon');
      var st = document.getElementById('soundText');
      if (sb) sb.classList.add('muted');
      if (si) si.className = 'bi bi-volume-mute-fill';
      if (st) st.textContent = 'Muted';
    }

    document.getElementById('soundBtn')?.addEventListener('click', toggleSound);
    document.getElementById('topPauseBtn')?.addEventListener('click', togglePause);
    document.getElementById('tutorialBtn')?.addEventListener('click', openTut);
    document.getElementById('boardRulesBtn')?.addEventListener('click', openTut);
    document.getElementById('answerForm').addEventListener('submit', function(e) {
      e.preventDefault();
      submitAnswer();
    });

    // Open the tutorial automatically on first load
    setTimeout(function(){ openTut(); }, 350);
  })();

})();
</script>
@endsection