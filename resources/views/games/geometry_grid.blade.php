@extends('layouts.app')
@section('title', 'Geometry Grid Explorer')
@section('hide-nav', true)

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">

<style>
  *, *::before, *::after { box-sizing: border-box; }
  .geo-root { font-family:'Outfit','Segoe UI',sans-serif; background:#080c1a; min-height:100vh; padding:10px; position:relative; overflow:hidden; }
  .geo-root::before { content:''; position:fixed; inset:0; background:
    radial-gradient(1px 1px at 10% 20%,rgba(255,255,255,.8) 0%,transparent 100%),
    radial-gradient(1px 1px at 30% 60%,rgba(255,255,255,.6) 0%,transparent 100%),
    radial-gradient(1px 1px at 55% 15%,rgba(255,255,255,.9) 0%,transparent 100%),
    radial-gradient(1px 1px at 75% 45%,rgba(255,255,255,.5) 0%,transparent 100%),
    radial-gradient(1px 1px at 85% 80%,rgba(255,255,255,.7) 0%,transparent 100%),
    radial-gradient(1px 1px at 20% 75%,rgba(255,255,255,.6) 0%,transparent 100%),
    radial-gradient(1px 1px at 60% 88%,rgba(255,255,255,.7) 0%,transparent 100%),
    radial-gradient(1px 1px at 40% 50%,rgba(255,255,255,.4) 0%,transparent 100%);
    pointer-events:none; z-index:0; }
  .geo-root::after { content:''; position:fixed; top:-30%; left:-20%; width:70%; height:70%; background:radial-gradient(circle,rgba(99,102,241,.1) 0%,transparent 70%); pointer-events:none; z-index:0; animation:floatOrb 18s ease-in-out infinite; }
  @keyframes floatOrb { 0%,100%{transform:translate(0,0)} 50%{transform:translate(7%,5%)} }
  .geo-wrap { position:relative; z-index:1; max-width:1600px; margin:0 auto; }

  /* TOP */
  .geo-topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:12px; }
  .geo-title { font-weight:900; font-size:clamp(1.5rem,3vw,2.3rem); background:linear-gradient(135deg,#818cf8,#38bdf8,#34d399); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
  .geo-top-meta { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
  .geo-badge { background:linear-gradient(135deg,#f59e0b,#d97706); color:#1e1b02; font-weight:800; font-size:.85rem; letter-spacing:1px; padding:7px 20px; border-radius:50px; box-shadow:0 0 20px rgba(245,158,11,.35); }
  .geo-timer { background:linear-gradient(135deg,#6366f1,#4f46e5); color:white; box-shadow:0 0 20px rgba(99,102,241,.35); transition:all .3s; }
  .geo-timer.warning { background:linear-gradient(135deg,#f59e0b,#d97706); color:#1e1b02; animation:timerPulse 1s infinite; }
  .geo-timer.danger { background:linear-gradient(135deg,#ef4444,#dc2626); color:white; animation:timerPulse .5s infinite; }
  @keyframes timerPulse { 0%,100%{transform:scale(1);} 50%{transform:scale(1.05);} }
  .geo-control-btn { width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1rem; border:2px solid; cursor:pointer; transition:all .2s; text-decoration:none; }
  .geo-pause-btn { background:rgba(251,191,36,.15); border-color:rgba(251,191,36,.4); color:#fbbf24; }
  .geo-pause-btn:hover { background:rgba(251,191,36,.3); transform:scale(1.05); }
  .geo-pause-btn.paused { background:rgba(52,211,153,.15); border-color:rgba(52,211,153,.4); color:#34d399; }
  .geo-exit-btn { background:rgba(239,68,68,.15); border-color:rgba(239,68,68,.4); color:#ef4444; }
  .geo-exit-btn:hover { background:rgba(239,68,68,.3); transform:scale(1.05); }
  .geo-shape-tag { padding:7px 16px; border-radius:50px; font-weight:700; font-size:.82rem; }
  .geo-q-type-tag { padding:5px 14px; border-radius:50px; font-weight:700; font-size:.78rem; letter-spacing:.5px; }
  .qtag-computation { background:rgba(52,211,153,.15); color:#34d399; border:1px solid rgba(52,211,153,.3); }
  .qtag-visual      { background:rgba(251,191,36,.15); color:#fbbf24; border:1px solid rgba(251,191,36,.3); }
  .qtag-fact        { background:rgba(129,140,248,.15); color:#818cf8; border:1px solid rgba(129,140,248,.3); }
  .shape-tag-triangle    { background:rgba(16,185,129,.2); color:#34d399; border:1px solid rgba(52,211,153,.3); }
  .shape-tag-parallelogram { background:rgba(59,130,246,.2); color:#60a5fa; border:1px solid rgba(96,165,250,.3); }
  .shape-tag-trapezoid   { background:rgba(245,158,11,.2); color:#fbbf24; border:1px solid rgba(251,191,36,.3); }

  /* PROGRESS */
  .geo-progress-row { margin-bottom:20px; }
  .geo-progress-info { display:flex; justify-content:space-between; margin-bottom:7px; }
  .geo-progress-label { color:#94a3b8; font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; }
  .geo-progress-count { color:white; font-weight:800; font-size:.85rem; }
  .geo-bar-bg { height:8px; background:rgba(255,255,255,.08); border-radius:10px; overflow:hidden; }
  .geo-bar-fill { height:100%; background:linear-gradient(90deg,#6366f1,#38bdf8,#34d399); border-radius:10px; transition:width .5s; box-shadow:0 0 10px rgba(99,102,241,.5); }

  /* GAME GRID */
  .geo-game-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:stretch; min-height:calc(100vh - 220px); }
  @media(max-width:950px){ .geo-game-grid{grid-template-columns:1fr} }

  /* CANVAS PANEL */
  .geo-canvas-panel { background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08); border-radius:26px; padding:24px; backdrop-filter:blur(10px); display:flex; flex-direction:column; height:100%; }
  .geo-canvas-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
  .canvas-meta-title { font-weight:700; color:#e2e8f0; font-size:.95rem; }
  .canvas-meta-sub { color:#64748b; font-size:.75rem; margin-top:2px; }
  .canvas-icon { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; }
  .icon-t { background:rgba(16,185,129,.15); }
  .icon-p { background:rgba(59,130,246,.15); }
  .icon-tr { background:rgba(245,158,11,.15); }

  canvas#gc { width:100%; height:auto; max-height:500px; display:block; border-radius:16px; background:#040711; box-shadow:inset 0 0 40px rgba(0,0,0,.8),0 0 25px rgba(99,102,241,.08); border:1px solid rgba(255,255,255,.05); flex-shrink:0; }

  .geo-canvas-legend { display:flex; gap:16px; margin-top:16px; flex-wrap:wrap; }
  .legend-item { display:flex; align-items:center; gap:6px; font-size:.8rem; color:#64748b; font-weight:600; }
  .ldot { width:10px; height:10px; border-radius:50%; }

  /* SCORE */
  .geo-score-card { background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.06); border-radius:18px; padding:20px; display:flex; gap:14px; margin-top:auto; }
  .score-item { flex:1; text-align:center; }
  .score-val { font-weight:900; font-size:1.8rem; color:white; }
  .score-lbl { font-size:.75rem; color:#64748b; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-top:4px; }
  .s-div { width:1px; background:rgba(255,255,255,.07); align-self:stretch; }

  /* QUESTION PANEL */
  .geo-q-panel { display:flex; flex-direction:column; gap:16px; height:100%; }
  .geo-q-card { background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1); border-radius:22px; padding:26px; backdrop-filter:blur(10px); position:relative; overflow:hidden; flex:1; display:flex; flex-direction:column; }
  .geo-q-card::before { content:''; position:absolute; top:0;left:0;right:0; height:3px; background:linear-gradient(90deg,#6366f1,#38bdf8,#34d399); }
  .q-meta { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
  .q-num { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:1.5px; color:#6366f1; }
  .q-text { font-size:1.15rem; font-weight:700; color:#f1f5f9; line-height:1.55; margin-bottom:22px; }

  /* MCQ */
  .ans-grid { display:flex; flex-direction:column; gap:10px; flex:1; }
  .ans-btn { background:rgba(255,255,255,.05); border:1.5px solid rgba(255,255,255,.12); color:#cbd5e1; font-family:'Outfit',sans-serif; font-weight:600; font-size:.95rem; padding:15px 20px; border-radius:13px; text-align:left; cursor:pointer; transition:all .18s; display:flex; align-items:center; gap:11px; }
  .ans-btn:hover:not(:disabled) { background:rgba(99,102,241,.15); border-color:rgba(99,102,241,.5); color:#e2e8f0; transform:translateX(4px); }
  .ans-btn.correct { background:rgba(16,185,129,.2); border-color:#10b981; color:#34d399; box-shadow:0 0 18px rgba(16,185,129,.2); }
  .ans-btn.wrong { background:rgba(239,68,68,.15); border-color:#ef4444; color:#f87171; }
  .ans-btn:disabled { cursor:not-allowed; }
  .ans-letter { width:32px; height:32px; border-radius:7px; background:rgba(255,255,255,.1); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.85rem; flex-shrink:0; transition:all .18s; }
  .ans-btn.correct .ans-letter { background:#10b981; color:white; }
  .ans-btn.wrong .ans-letter { background:#ef4444; color:white; }

  /* FEEDBACK */
  .geo-fb { border-radius:13px; padding:14px 18px; font-weight:700; font-size:.95rem; display:none; align-items:flex-start; gap:10px; line-height:1.4; margin-top:16px; }
  .geo-fb.show { display:flex; }
  .geo-fb.correct { background:rgba(16,185,129,.15); border:1px solid rgba(16,185,129,.3); color:#34d399; }
  .geo-fb.wrong { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.25); color:#f87171; }

  /* NEXT BTN */
  .geo-next { background:linear-gradient(135deg,#6366f1,#4f46e5); border:none; color:white; font-family:'Outfit',sans-serif; font-weight:800; font-size:1.05rem; padding:16px 32px; border-radius:13px; cursor:pointer; width:100%; transition:all .2s; box-shadow:0 0 18px rgba(99,102,241,.3); display:none; margin-top:auto; }
  .geo-next:hover { transform:translateY(-2px); box-shadow:0 0 28px rgba(99,102,241,.5); }
  .geo-next.show { display:block; }

  /* ==========================================================================
     GEOMETRY GRID FIELD GUIDE & INSTRUCTION OVERLAY
     ========================================================================== */
  .tut-overlay {
    position: fixed; inset: 0;
    background: rgba(4, 8, 22, 0.92);
    backdrop-filter: blur(14px);
    z-index: 10005;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.28s ease, transform 0.28s ease;
  }
  .tut-overlay.show {
    opacity: 1;
    pointer-events: all;
  }
  .tut-card {
    background: linear-gradient(165deg, #0e172e 0%, #111e3d 100%);
    border: 2px solid rgba(99, 102, 241, 0.45);
    border-radius: 26px;
    max-width: 820px;
    width: 100%;
    box-shadow: 0 35px 80px rgba(0, 0, 0, 0.85), 0 0 50px rgba(99, 102, 241, 0.2);
    position: relative;
    overflow: hidden;
    max-height: 94vh;
    display: flex;
    flex-direction: column;
    padding: 28px 34px 24px;
  }
  .tut-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #6366f1, #38bdf8, #34d399);
  }
  .tut-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  }
  .tut-tag-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 1.0rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #38bdf8;
    background: rgba(56, 189, 248, 0.12);
    border: 1px solid rgba(56, 189, 248, 0.35);
    padding: 5px 14px;
    border-radius: 50px;
  }
  .tut-close-btn {
    background: transparent;
    border: none;
    color: #94a3b8;
    font-size: 1.5rem;
    cursor: pointer;
    line-height: 1;
    transition: color 0.18s;
  }
  .tut-close-btn:hover { color: #ffffff; }

  /* Topic Tabs */
  .tut-topic-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 14px;
    overflow-x: auto;
    padding-bottom: 4px;
    scrollbar-width: thin;
  }
  .tut-topic-tabs::-webkit-scrollbar { height: 4px; }
  .tut-topic-tabs::-webkit-scrollbar-thumb { background: rgba(99,102,241,0.4); border-radius: 10px; }
  .tut-tab-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #94a3b8;
    font-family: 'Outfit', sans-serif;
    font-size: 1.0rem;
    font-weight: 700;
    padding: 7px 14px;
    border-radius: 50px;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.18s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .tut-tab-btn:hover {
    background: rgba(99, 102, 241, 0.15);
    color: #cbd5e1;
    border-color: rgba(99, 102, 241, 0.35);
  }
  .tut-tab-btn.active {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #ffffff;
    border-color: #818cf8;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
  }

  /* Slide Body */
  .tut-slide-body {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-height: 0;
    overflow-y: auto;
  }
  .tut-slide-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }
  .tut-topic-indicator {
    font-size: 1.1rem;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #818cf8;
  }
  .tut-slide-num {
    font-size: 0.95rem;
    font-weight: 700;
    color: #64748b;
    letter-spacing: 0.5px;
  }

  /* Card Content Details */
  .tut-card-hero {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
  }
  .tut-hero-icon {
    font-size: 1.8rem;
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    flex-shrink: 0;
  }
  .tut-card-title {
    font-size: 1.4rem;
    font-weight: 900;
    color: #ffffff;
    margin: 0;
  }
  .tut-card-desc {
    font-size: 1rem;
    line-height: 1.6;
    color: #cbd5e1;
    margin: 0 0 14px;
  }

  /* Visual Blocks */
  .tut-visual-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 14px;
  }
  @media(max-width: 600px) {
    .tut-visual-grid { grid-template-columns: 1fr; }
  }
  .tut-mini-box {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 14px 16px;
  }
  .tut-mini-box h5 {
    font-size: 1.15rem;
    font-weight: 800;
    color: #38bdf8;
    margin: 0 0 6px;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .tut-mini-box p {
    font-size: 1.05rem;
    color: #94a3b8;
    line-height: 1.5;
    margin: 0;
  }

  /* Formula Box */
  .tut-formula-pill {
    background: rgba(99, 102, 241, 0.12);
    border: 1.5px solid rgba(99, 102, 241, 0.4);
    border-radius: 14px;
    padding: 13px 18px;
    margin-bottom: 14px;
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .tut-formula-title {
    font-size: 1.0rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #a5b4fc;
  }
  .tut-formula-eq {
    font-family: var(--font-mono, monospace);
    font-size: 1.35rem;
    font-weight: 800;
    color: #38bdf8;
    letter-spacing: 0.5px;
  }
  .tut-formula-sub {
    font-size: 1.05rem;
    color: #94a3b8;
  }

  /* Example Problem Card */
  .tut-example-card {
    background: rgba(16, 185, 129, 0.08);
    border: 1px solid rgba(16, 185, 129, 0.25);
    border-radius: 14px;
    padding: 13px 17px;
    margin-bottom: 14px;
    font-size: 1.1rem;
    color: #d1fae5;
    line-height: 1.55;
  }
  .tut-example-card b { color: #34d399; }

  /* Callout Tip */
  .tut-callout {
    background: rgba(251, 191, 36, 0.08);
    border: 1px solid rgba(251, 191, 36, 0.25);
    border-radius: 12px;
    padding: 11px 15px;
    font-size: 1.1rem;
    color: #fde68a;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
  }

  /* Dots & Actions */
  .tut-dots {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin: 8px 0 14px;
  }
  .tut-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    cursor: pointer;
    transition: all 0.2s;
  }
  .tut-dot.active {
    background: #38bdf8;
    width: 22px;
    border-radius: 4px;
    box-shadow: 0 0 10px rgba(56, 189, 248, 0.5);
  }

  .tut-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
  }
  .tut-skip-btn {
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(251, 191, 36, 0.28);
    color: #fde68a;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: 1.0rem;
    padding: 8px 16px;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.2s;
  }
  .tut-skip-btn:hover {
    background: rgba(251, 191, 36, 0.16);
    color: #ffffff;
    border-color: rgba(251, 191, 36, 0.5);
  }
  .tut-prev {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #cbd5e1;
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    padding: 8px 18px;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 1.0rem;
  }
  .tut-prev:hover { background: rgba(255, 255, 255, 0.15); color: #ffffff; }
  .tut-next {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    border: none;
    color: #ffffff;
    font-family: 'Outfit', sans-serif;
    font-weight: 800;
    padding: 8px 22px;
    border-radius: 50px;
    cursor: pointer;
    font-size: 1.05rem;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
    transition: all 0.2s;
  }
  .tut-next:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6); }
  .tut-start-btn {
    background: linear-gradient(135deg, #10b981, #059669);
    border: none;
    color: #ffffff;
    font-family: 'Outfit', sans-serif;
    font-weight: 800;
    padding: 8px 22px;
    border-radius: 50px;
    cursor: pointer;
    font-size: 1.05rem;
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
    transition: all 0.2s;
  }
  .tut-start-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(16, 185, 129, 0.6); }

  /* COMPLETE */
  .complete-overlay { position:fixed; inset:0; background:rgba(0,0,0,.92); backdrop-filter:blur(15px); z-index:9999; display:flex; align-items:center; justify-content:center; padding:20px; opacity:0; pointer-events:none; transition:opacity .3s; }
  .complete-overlay.show { opacity:1; pointer-events:all; }
  .complete-card { background:linear-gradient(160deg,#0f172a,#1e293b); border:1px solid rgba(255,255,255,.1); border-radius:30px; max-width:460px; width:100%; padding:44px 38px; text-align:center; box-shadow:0 40px 80px rgba(0,0,0,.6); }
  .complete-icon { font-size:4.5rem; margin-bottom:14px; display:block; animation:bounceIn .5s ease; }
  @keyframes bounceIn { 0%{transform:scale(0)} 70%{transform:scale(1.15)} 100%{transform:scale(1)} }
  .complete-title { font-weight:900; font-size:1.9rem; color:white; margin-bottom:7px; }
  .complete-sub { color:#64748b; font-size:.97rem; margin-bottom:28px; }
  .complete-stats { display:flex; gap:10px; justify-content:center; margin-bottom:28px; flex-wrap:wrap; }
  .complete-stat { background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.1); border-radius:14px; padding:14px 20px; min-width:90px; }
  .complete-stat-val { font-weight:900; font-size:1.5rem; color:white; }
  .complete-stat-lbl { font-size:.7rem; color:#64748b; text-transform:uppercase; letter-spacing:1px; font-weight:700; }
  .complete-btn { background:linear-gradient(135deg,#6366f1,#4f46e5); border:none; color:white; font-family:'Outfit',sans-serif; font-weight:800; font-size:1.05rem; padding:15px 38px; border-radius:14px; cursor:pointer; width:100%; transition:all .2s; box-shadow:0 0 28px rgba(99,102,241,.4); }
  .complete-btn:hover { transform:translateY(-3px); box-shadow:0 0 45px rgba(99,102,241,.6); }

  /* PAUSE OVERLAY */
  .pause-overlay { position:fixed; inset:0; background:rgba(0,0,0,.85); backdrop-filter:blur(12px); z-index:9998; display:flex; align-items:center; justify-content:center; padding:20px; opacity:0; pointer-events:none; transition:opacity .3s; }
  .pause-overlay.show { opacity:1; pointer-events:all; }
  .pause-card { background:linear-gradient(160deg,#0f172a,#1e293b); border:1px solid rgba(251,191,36,.3); border-radius:30px; max-width:400px; width:100%; padding:40px; text-align:center; box-shadow:0 40px 80px rgba(0,0,0,.6); }
  .pause-icon { font-size:4rem; margin-bottom:16px; display:block; animation:bounceIn .4s ease; }
  .pause-title { font-weight:900; font-size:1.8rem; color:white; margin-bottom:10px; }
  .pause-text { color:#94a3b8; font-size:.95rem; margin-bottom:28px; }
  .pause-btn { background:linear-gradient(135deg,#fbbf24,#f59e0b); border:none; color:#1e1b02; font-family:'Outfit',sans-serif; font-weight:800; font-size:1rem; padding:14px 32px; border-radius:14px; cursor:pointer; width:100%; transition:all .2s; box-shadow:0 0 25px rgba(251,191,36,.4); margin-bottom:12px; display:flex; align-items:center; justify-content:center; text-decoration:none; }
  .pause-btn:hover { transform:translateY(-2px); box-shadow:0 0 35px rgba(251,191,36,.6); text-decoration:none; }
  .pause-btn-secondary { background:rgba(255,255,255,.08); color:#e2e8f0; box-shadow:none; margin-bottom:0; }
  .pause-btn-secondary:hover { background:rgba(255,255,255,.15); color:#f1f5f9; }

  /* EXIT CONFIRMATION OVERLAY */
  .confirm-overlay { position:fixed; inset:0; background:rgba(0,0,0,.85); backdrop-filter:blur(12px); z-index:10001; display:flex; align-items:center; justify-content:center; padding:20px; opacity:0; pointer-events:none; transition:opacity .3s; }
  .confirm-overlay.show { opacity:1; pointer-events:all; }
  .confirm-card { background:linear-gradient(160deg,#0f172a,#1e293b); border:1px solid rgba(239,68,68,.3); border-radius:30px; max-width:400px; width:100%; padding:40px; text-align:center; box-shadow:0 40px 80px rgba(0,0,0,.6); }
  .confirm-icon { font-size:3.5rem; margin-bottom:16px; display:block; animation:bounceIn .4s ease; }
  .confirm-title { font-weight:900; font-size:1.6rem; color:white; margin-bottom:10px; }
  .confirm-text { color:#94a3b8; font-size:.92rem; line-height:1.55; margin-bottom:28px; }
  .confirm-actions { display:flex; gap:10px; }
  .confirm-btn { flex:1; border:none; font-family:'Outfit',sans-serif; font-weight:800; font-size:.95rem; padding:14px 18px; border-radius:14px; cursor:pointer; transition:all .2s; }
  .confirm-btn-cancel { background:rgba(255,255,255,.08); color:#e2e8f0; }
  .confirm-btn-cancel:hover { background:rgba(255,255,255,.15); }
  .confirm-btn-exit { background:linear-gradient(135deg,#ef4444,#dc2626); color:white; box-shadow:0 0 20px rgba(239,68,68,.35); }
  .confirm-btn-exit:hover { transform:translateY(-2px); box-shadow:0 0 30px rgba(239,68,68,.5); }

  /* CONFETTI */
  .confetti-piece { position:fixed; width:10px; height:10px; top:-10px; z-index:10000; animation:cfall linear forwards; border-radius:2px; }
  @keyframes cfall { to{transform:translateY(105vh) rotate(720deg)} }
</style>

<div class="geo-root">
  <div class="geo-wrap">
    <!-- TOP BAR -->
    <div class="geo-topbar">
      <div class="geo-title"><i class="fas fa-shapes me-2"></i>Geometry Grid</div>
      <div class="geo-top-meta">
        <span class="geo-shape-tag shape-tag-triangle" id="shapeTag">▲ TRIANGLE</span>
        <span class="geo-q-type-tag qtag-computation" id="qTypeTag">📐 COMPUTATION</span>
        <span class="geo-badge geo-timer" id="timerBadge">⏱ 5:00</span>
        <span class="geo-badge" id="levelBadge">LEVEL 1</span>
        <div class="eduplay-game-nav">
          <button type="button" class="eduplay-nav-btn eduplay-btn-guide" id="guideBtn" onclick="openTutorialGuide()" title="Field Guide &amp; Instructions">
            <i class="bi bi-book-half"></i> <span>Field Guide</span>
          </button>
          <button type="button" class="eduplay-nav-btn eduplay-btn-pause" id="pauseBtn" onclick="togglePause()" title="Pause Game">
            <i class="bi bi-pause-fill"></i> <span>Pause</span>
          </button>
          <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map">
            <i class="bi bi-map-fill"></i> <span>Game Map</span>
          </a>
          <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Progress">
            <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
          </a>
        </div>
      </div>

    </div>

    <!-- PROGRESS -->
    <div class="geo-progress-row">
      <div class="geo-progress-info">
        <span class="geo-progress-label">Overall Progress</span>
        <span class="geo-progress-count" id="progressCount">0 / 15 levels</span>
      </div>
      <div class="geo-bar-bg"><div class="geo-bar-fill" id="progressFill" style="width:0%"></div></div>
    </div>

    <!-- GAME GRID -->
    <div class="geo-game-grid">

      <!-- LEFT: Canvas -->
      <div class="geo-canvas-panel">
        <div class="geo-canvas-header">
          <div>
            <div class="canvas-meta-title" id="canvasTitle">Triangle</div>
            <div class="canvas-meta-sub" id="canvasSub">Refer to the shape</div>
          </div>
          <div class="canvas-icon icon-t" id="canvasIconEl">🔺</div>
        </div>
        <canvas id="gc" width="520" height="520"></canvas>
        <div class="geo-canvas-legend">
          <div class="legend-item"><div class="ldot" style="background:#34d399"></div> Base</div>
          <div class="legend-item"><div class="ldot" style="background:#f43f5e"></div> Height</div>
          <div class="legend-item"><div class="ldot" style="background:#fbbf24"></div> Special Point</div>
          <div class="legend-item"><div class="ldot" style="background:rgba(255,255,255,.2)"></div> 1 dot = 1 cm</div>
        </div>
        <!-- Score -->
        <div class="geo-score-card">
          <div class="score-item"><div class="score-val" id="scoreVal">0</div><div class="score-lbl">Score</div></div>
          <div class="s-div"></div>
          <div class="score-item"><div class="score-val" id="streakVal">0</div><div class="score-lbl">Streak 🔥</div></div>
          <div class="s-div"></div>
          <div class="score-item"><div class="score-val" id="correctVal">0</div><div class="score-lbl">Correct</div></div>
        </div>
      </div>

      <!-- RIGHT: Question -->
      <div class="geo-q-panel">
        <div class="geo-q-card">
          <div class="q-meta">
            <span class="q-num" id="qNum">QUESTION 1 OF 5</span>
          </div>
          <div class="q-text" id="qText">Loading...</div>
          <div class="ans-grid" id="answersDiv"></div>
          <div class="geo-fb" id="feedbackDiv"></div>
        </div>
        <button class="geo-next" id="nextBtn">Next Question →</button>
      </div>

    </div>
  </div>
</div>

<!-- TUTORIAL OVERLAY -->
<div class="tut-overlay" id="tutOverlay" role="dialog" aria-modal="true" aria-labelledby="tutTagText">
  <div class="tut-card">
    <div class="tut-header">
      <div class="tut-tag-badge" id="tutTagText">
        <i class="fas fa-shapes"></i> Field Guide &amp; Instructions
      </div>
      <button type="button" class="tut-close-btn" onclick="closeTutorial()" title="Close Guide">&times;</button>
    </div>

    <!-- Topic Selector Tabs -->
    <div class="tut-topic-tabs">
      <button type="button" class="tut-tab-btn active" onclick="switchTutTopic('guide')" id="tab-guide">
        <i class="fas fa-gamepad"></i> <span>How to Play</span>
      </button>
      <button type="button" class="tut-tab-btn" onclick="switchTutTopic('triangle')" id="tab-triangle">
        <span>🔺 Triangle</span>
      </button>
      <button type="button" class="tut-tab-btn" onclick="switchTutTopic('parallelogram')" id="tab-parallelogram">
        <span>◈ Parallelogram</span>
      </button>
      <button type="button" class="tut-tab-btn" onclick="switchTutTopic('trapezoid')" id="tab-trapezoid">
        <span>🏔️ Trapezoid</span>
      </button>
      <button type="button" class="tut-tab-btn" onclick="switchTutTopic('formulas')" id="tab-formulas">
        <span>📐 Formulas</span>
      </button>
    </div>

    <!-- Active Topic Slide Content -->
    <div class="tut-slide-body">
      <div class="tut-slide-meta">
        <span class="tut-topic-indicator" id="tutTopicIndicator">GAMEPLAY BASICS</span>
        <span class="tut-slide-num" id="tutSlideNum">SLIDE 1 OF 3</span>
      </div>

      <div class="tut-content-area" id="tutContentArea">
        <!-- Rendered dynamically -->
      </div>

      <div class="tut-dots" id="tutDots"></div>

      <div class="tut-actions">
        <button type="button" class="tut-skip-btn" id="tutSkipBtn" onclick="closeTutorial()">Skip to Challenge ⏩</button>
        <div style="display:flex; gap:10px; margin-left:auto;">
          <button type="button" class="tut-prev" id="tutPrevBtn" style="display:none;" onclick="tutPrev()">← Back</button>
          <button type="button" class="tut-next" id="tutNextBtn" onclick="tutNext()">Continue →</button>
          <button type="button" class="tut-start-btn" id="tutStartBtn" onclick="closeTutorial()" style="display:none;">Let's Play! 🚀</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- LEVEL COMPLETE OVERLAY -->
<div class="complete-overlay" id="completeOverlay">
  <div class="complete-card">
    <span class="complete-icon" id="cIcon">🏆</span>
    <div class="complete-title" id="cTitle">Level Complete!</div>
    <div class="complete-sub" id="cSub">Keep exploring!</div>
    <div class="complete-stats">
      <div class="complete-stat"><div class="complete-stat-val" id="cCorrect">-</div><div class="complete-stat-lbl">Correct</div></div>
      <div class="complete-stat"><div class="complete-stat-val" id="cScore">-</div><div class="complete-stat-lbl">Points</div></div>
      <div class="complete-stat"><div class="complete-stat-val" id="cAcc">-</div><div class="complete-stat-lbl">Accuracy</div></div>
    </div>
    <button class="complete-btn" id="cBtn">Continue →</button>
  </div>
</div>

<!-- PAUSE OVERLAY -->
<div class="pause-overlay" id="pauseOverlay">
  <div class="pause-card">
    <span class="pause-icon">⏸️</span>
    <div class="pause-title">Game Paused</div>
    <div class="pause-text">Timer is paused. Take your time!</div>
    <button class="pause-btn" onclick="togglePause()">
      <i class="fas fa-play me-2"></i>Resume Game
    </button>
    <div class="eduplay-game-nav" style="display:flex;gap:10px;width:100%;justify-content:center;margin-top:6px;">
      <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" style="flex:1;">
        <i class="bi bi-map-fill"></i> <span>Game Map</span>
      </a>
      <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit" style="flex:1;">
        <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
      </a>
    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  const GAME_ID = {{ $game->id ?? 0 }};
  const CSRF    = '{{ csrf_token() }}';
  const USER_ID = '{{ auth()->id() ?? 0 }}';

  // Read URL query parameters and storage flags
  const urlParams = new URLSearchParams(window.location.search);
  const paramLevel = parseInt(urlParams.get('level'), 10);
  const paramTutorial = urlParams.get('tutorial') === '1' || urlParams.get('show_instruction') === '1';
  const storageTutorial = localStorage.getItem('gg_open_tutorial') === 'true' || sessionStorage.getItem('gg_open_tutorial') === 'true';

  try {
    localStorage.removeItem('gg_open_tutorial');
    sessionStorage.removeItem('gg_open_tutorial');
  } catch(e) {}

  let initialLvl = Math.max(0, {{ $progress->current_level ?? 1 }} - 1);
  if (!isNaN(paramLevel) && paramLevel >= 1 && paramLevel <= 15) {
    initialLvl = paramLevel - 1;
  }
  let gProgress = Math.min({{ $progress->current_level ?? 1 }}, 15);

  const canvas = document.getElementById('gc');
  const ctx    = canvas.getContext('2d');
  const GRID   = 10, CELL = canvas.width / GRID;

  /* ═══════════════════════════════════════════════════
     CANVAS UTILITIES
  ═══════════════════════════════════════════════════ */
  function gx(x){ return x * CELL; }
  function gy(y){ return canvas.height - y * CELL; }

  function clearAndGrid(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
    ctx.fillStyle='rgba(255,255,255,0.1)';
    for(let x=0;x<=GRID;x++) for(let y=0;y<=GRID;y++){ctx.beginPath();ctx.arc(gx(x),gy(y),2.2,0,Math.PI*2);ctx.fill();}
    ctx.lineWidth=0.4; ctx.strokeStyle='rgba(255,255,255,0.04)';
    for(let i=0;i<=GRID;i++){
      ctx.beginPath();ctx.moveTo(gx(i),0);ctx.lineTo(gx(i),canvas.height);ctx.stroke();
      ctx.beginPath();ctx.moveTo(0,gy(i));ctx.lineTo(canvas.width,gy(i));ctx.stroke();
    }
  }

  function ha(hex,a){const r=parseInt(hex.slice(1,3),16),g=parseInt(hex.slice(3,5),16),b=parseInt(hex.slice(5,7),16);return `rgba(${r},${g},${b},${a})`;}

  function drawPoly(pts,stroke){
    ctx.beginPath();
    pts.forEach(([x,y],i)=>{i?ctx.lineTo(gx(x),gy(y)):ctx.moveTo(gx(x),gy(y));});
    ctx.closePath();
    const g=ctx.createLinearGradient(0,0,canvas.width,canvas.height);
    g.addColorStop(0,ha(stroke,0.48));g.addColorStop(1,ha(stroke,0.15));
    ctx.fillStyle=g;ctx.fill();
    ctx.shadowColor=stroke;ctx.shadowBlur=18;ctx.strokeStyle=stroke;ctx.lineWidth=4;ctx.stroke();ctx.shadowBlur=0;
  }

  function drawVerts(pts,color='white'){
    pts.forEach(([x,y])=>{
      ctx.beginPath();ctx.arc(gx(x),gy(y),7,0,Math.PI*2);
      ctx.fillStyle=color;ctx.fill();ctx.strokeStyle='#0f172a';ctx.lineWidth=2.5;ctx.stroke();
    });
  }

  function drawLine(x1,y1,x2,y2,color,dash=[],lw=3.5){
    ctx.save();ctx.setLineDash(dash);ctx.beginPath();ctx.moveTo(gx(x1),gy(y1));ctx.lineTo(gx(x2),gy(y2));
    ctx.strokeStyle=color;ctx.lineWidth=lw;ctx.shadowColor=color;ctx.shadowBlur=10;ctx.stroke();ctx.restore();
  }

  function drawLabel(text,x,y,color,ox=8,oy=0){
    ctx.save();ctx.shadowColor='#000';ctx.shadowBlur=8;ctx.font='bold 15px "Outfit",sans-serif';ctx.fillStyle=color;
    ctx.fillText(text,gx(x)+ox,gy(y)+oy);ctx.restore();
  }

  function drawArrow(fromX,fromY,toX,toY,color){
    const sx=gx(fromX),sy=gy(fromY),ex=gx(toX),ey=gy(toY);
    const angle=Math.atan2(ey-sy,ex-sx);
    const headLen=14;
    ctx.save();
    ctx.shadowColor=color;ctx.shadowBlur=10;
    ctx.strokeStyle=color;ctx.lineWidth=2.5;ctx.fillStyle=color;
    ctx.beginPath();ctx.moveTo(sx,sy);ctx.lineTo(ex,ey);ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(ex,ey);
    ctx.lineTo(ex-headLen*Math.cos(angle-Math.PI/7),ey-headLen*Math.sin(angle-Math.PI/7));
    ctx.lineTo(ex-headLen*Math.cos(angle+Math.PI/7),ey-headLen*Math.sin(angle+Math.PI/7));
    ctx.closePath();ctx.fill();
    ctx.restore();
  }

  function drawSpecialPoint(px,py,color,label,labelOx=10,labelOy=0){
    // Pulsing ring
    ctx.save();
    ctx.beginPath();ctx.arc(gx(px),gy(py),14,0,Math.PI*2);
    ctx.strokeStyle=color;ctx.lineWidth=2.5;ctx.setLineDash([4,3]);
    ctx.shadowColor=color;ctx.shadowBlur=15;ctx.stroke();
    ctx.restore();
    // Fill dot
    ctx.save();
    ctx.beginPath();ctx.arc(gx(px),gy(py),7,0,Math.PI*2);
    ctx.fillStyle=color;ctx.shadowColor=color;ctx.shadowBlur=20;ctx.fill();
    ctx.strokeStyle='white';ctx.lineWidth=2;ctx.stroke();
    ctx.restore();
    // Label (kept optional/blank for questions where naming the point IS the answer)
    if(label){
      ctx.save();
      ctx.font='bold 14px "Outfit",sans-serif';ctx.fillStyle=color;
      ctx.shadowColor='#000';ctx.shadowBlur=6;
      ctx.fillText(label,gx(px)+labelOx,gy(py)+labelOy);
      ctx.restore();
    }
  }

  function drawVertLabel(text,gxv,gyv,ox,oy){
    ctx.save();
    ctx.font='bold 16px "Outfit",sans-serif';ctx.fillStyle='white';
    ctx.shadowColor='#000';ctx.shadowBlur=6;
    ctx.fillText(text,gx(gxv)+ox,gy(gyv)+oy);
    ctx.restore();
  }

  /* ═══════════════════════════════════════════════════
     CANVAS DRAW FUNCTIONS (keyed by name)
     All functions take no args, use fixed triangle/shape
     NOTE: none of these draw the correct-answer text on
     the canvas — labels are limited to given measurements
     (base/height/side lengths) that the question requires
     to be solved, never the classification/name being asked.
  ═══════════════════════════════════════════════════ */
  // Standard triangle A(2,2) B(8,2) C(5,9) — isosceles
  const TRI_STD = [[2,2],[8,2],[5,9]];
  // Right triangle A(2,2) B(8,2) C(2,8)
  const TRI_RT  = [[2,2],[8,2],[2,8]];
  // Parallelogram
  const PARA_PTS = [[1,2],[7,2],[9,8],[3,8]];
  // Trapezoid
  const TRAP_PTS = [[2,2],[8,2],[7,7],[3,7]];

  const CANVAS_FNS = {
    // ── Standard shape views ──
    triDefault(){ drawDefault('triangle'); },
    paraDefault(){ drawDefault('parallelogram'); },
    trapDefault(){ drawDefault('trapezoid'); },

    // ── Triangle computation with labeled dims ──
    triArea_6_4(){
      clearAndGrid();
      const pts=[[0.5,1],[9.5,1],[5,9]];
      drawPoly(pts,'#34d399');
      drawLine(0.5,1,9.5,1,'#34d399',[],5);
      drawLine(5,9,5,1,'#f43f5e',[8,6],3);
      drawVerts(pts);
      drawLabel('b = 6 cm',3.5,0.2,'#34d399');
      drawLabel('h = 5 cm',5.3,5,'#f43f5e');
    },
    triArea_8_6(){
      clearAndGrid();
      const pts=[[0.5,1.5],[9.5,1.5],[5,9]];
      drawPoly(pts,'#34d399');
      drawLine(0.5,1.5,9.5,1.5,'#34d399',[],5);
      drawLine(5,9,5,1.5,'#f43f5e',[8,6],3);
      drawVerts(pts);
      drawLabel('b = 8 cm',3.5,0.7,'#34d399');
      drawLabel('h = 6 cm',5.3,5.5,'#f43f5e');
    },
    triArea_10_4(){
      clearAndGrid();
      const pts=[[0.5,2],[9.5,2],[5,8]];
      drawPoly(pts,'#34d399');
      drawLine(0.5,2,9.5,2,'#34d399',[],5);
      drawLine(5,8,5,2,'#f43f5e',[8,6],3);
      drawVerts(pts);
      drawLabel('b = 10 cm',3.5,1.2,'#34d399');
      drawLabel('h = 4 cm',5.3,5,'#f43f5e');
    },
    triArea_5_8(){
      clearAndGrid();
      const pts=[[2,0.5],[8,0.5],[5,9.5]];
      drawPoly(pts,'#34d399');
      drawLine(2,0.5,8,0.5,'#34d399',[],5);
      drawLine(5,9.5,5,0.5,'#f43f5e',[8,6],3);
      drawVerts(pts);
      drawLabel('b = 5 cm',3.5,-0.3,'#34d399');
      drawLabel('h = 8 cm',5.3,5,'#f43f5e');
    },
    triPerim(){
      // Sides labeled only with lengths — the shape's classification
      // (equilateral) is what the question asks for, so it's never
      // written on the canvas itself.
      clearAndGrid();
      const pts=[[1.5,1.5],[8.5,1.5],[5,8.5]];
      drawPoly(pts,'#34d399');
      drawVerts(pts);
      drawLabel('5 cm',4.2,0.7,'#fbbf24');   // base
      drawLabel('5 cm',0.8,5,'#fbbf24'); // left side
      drawLabel('5 cm',8.2,5,'#fbbf24');   // right side
    },

    // ── Parallelogram computations ──
    paraArea_6_4(){
      clearAndGrid();
      const pts=[[0.5,2.5],[7.5,2.5],[9,8],[2,8]];
      drawPoly(pts,'#60a5fa');
      drawLine(0.5,2.5,7.5,2.5,'#34d399',[],5);
      drawLine(2,8,2,2.5,'#f43f5e',[8,6],3);
      drawVerts(pts);
      drawLabel('b = 6 cm',2.5,1.7,'#34d399');
      drawLabel('h = 4 cm',2.3,5.5,'#f43f5e');
    },
    paraArea_8_5(){
      clearAndGrid();
      const pts=[[0.5,2],[8.5,2],[9.5,8.5],[1.5,8.5]];
      drawPoly(pts,'#60a5fa');
      drawLine(0.5,2,8.5,2,'#34d399',[],5);
      drawLine(1.5,8.5,1.5,2,'#f43f5e',[8,6],3);
      drawVerts(pts);
      drawLabel('b = 8 cm',3.5,1.2,'#34d399');
      drawLabel('h = 5 cm',1.8,5.5,'#f43f5e');
    },
    paraArea_7_6(){
      clearAndGrid();
      const pts=[[0.5,1.5],[8,1.5],[9.5,9],[2,9]];
      drawPoly(pts,'#60a5fa');
      drawLine(0.5,1.5,8,1.5,'#34d399',[],5);
      drawLine(2,9,2,1.5,'#f43f5e',[8,6],3);
      drawVerts(pts);
      drawLabel('b = 7 cm',2.5,0.7,'#34d399');
      drawLabel('h = 6 cm',2.3,5.5,'#f43f5e');
    },

    // ── Trapezoid computations ──
    trapArea_6_4_5(){
      clearAndGrid();
      const pts=[[1.5,1.5],[8.5,1.5],[7.5,8.5],[2.5,8.5]];
      drawPoly(pts,'#fbbf24');
      drawLine(1.5,1.5,8.5,1.5,'#34d399',[],5);
      drawLine(2.5,8.5,7.5,8.5,'#818cf8',[],5);
      drawLine(2.5,8.5,2.5,1.5,'#f43f5e',[8,6],3);
      drawVerts(pts);
      drawLabel('b₁ = 6 cm',3.5,0.7,'#34d399');
      drawLabel('b₂ = 4 cm',3.5,9.3,'#818cf8');
      drawLabel('h = 5 cm',2.8,5,'#f43f5e');
    },
    trapArea_8_4_6(){
      clearAndGrid();
      const pts=[[0.5,1.5],[9.5,1.5],[7.5,9],[2.5,9]];
      drawPoly(pts,'#fbbf24');
      drawLine(0.5,1.5,9.5,1.5,'#34d399',[],5);
      drawLine(2.5,9,7.5,9,'#818cf8',[],5);
      drawLine(2.5,9,2.5,1.5,'#f43f5e',[8,6],3);
      drawVerts(pts);
      drawLabel('b₁ = 8 cm',3.5,0.7,'#34d399');
      drawLabel('b₂ = 4 cm',3.5,9.8,'#818cf8');
      drawLabel('h = 6 cm',2.8,5.5,'#f43f5e');
    },
    trapArea_10_6_4(){
      clearAndGrid();
      const pts=[[0.5,2.5],[9.5,2.5],[8,7.5],[2,7.5]];
      drawPoly(pts,'#fbbf24');
      drawLine(0.5,2.5,9.5,2.5,'#34d399',[],5);
      drawLine(2,7.5,8,7.5,'#818cf8',[],5);
      drawLine(2,7.5,2,2.5,'#f43f5e',[8,6],3);
      drawVerts(pts);
      drawLabel('b₁=10 cm',3.5,1.7,'#34d399');
      drawLabel('b₂=6 cm',3.5,8.3,'#818cf8');
      drawLabel('h=4 cm',2.3,5,'#f43f5e');
    },

    // ── Visual: Triangle special points ──
    // Using right triangle with larger scale for better visibility
    drawCentroid(){
      clearAndGrid();
      const A=[1,1],B=[9,1],C=[1,9];
      // Medians
      const mAB=[(A[0]+B[0])/2,(A[1]+B[1])/2]; // midpoint of AB
      const mBC=[(B[0]+C[0])/2,(B[1]+C[1])/2]; // midpoint of BC
      const mAC=[(A[0]+C[0])/2,(A[1]+C[1])/2]; // midpoint of AC
      // Draw medians (dashed)
      drawLine(C[0],C[1],mAB[0],mAB[1],'rgba(129,140,248,0.6)',[6,4],2.5);
      drawLine(A[0],A[1],mBC[0],mBC[1],'rgba(129,140,248,0.6)',[6,4],2.5);
      drawLine(B[0],B[1],mAC[0],mAC[1],'rgba(129,140,248,0.6)',[6,4],2.5);
      drawPoly([A,B,C],'#34d399');
      drawVerts([A,B,C]);
      // Centroid = average of vertices
      const cx=(A[0]+B[0]+C[0])/3, cy=(A[1]+B[1]+C[1])/3;
      drawArrow(cx+2.5, cy+1.5, cx+0.2, cy+0.15,'#fbbf24');
      drawSpecialPoint(cx,cy,'#fbbf24','',0,0);
      // Midpoint dots
      [mAB,mBC,mAC].forEach(([x,y])=>{
        ctx.beginPath();ctx.arc(gx(x),gy(y),5,0,Math.PI*2);
        ctx.fillStyle='#818cf8';ctx.fill();
      });
      drawVertLabel('A',A[0],A[1],-18,-2);
      drawVertLabel('B',B[0],B[1],6,-2);
      drawVertLabel('C',C[0],C[1],-18,5);
    },
    drawOrthocenter(){
      clearAndGrid();
      const A=[1,1],B=[9,1],C=[1,9];
      // Altitudes from each vertex to opposite side
      drawLine(C[0],C[1],C[0],A[1],'rgba(244,63,94,0.55)',[6,4],2.5);
      drawLine(B[0],B[1],A[0],B[1],'rgba(244,63,94,0.55)',[6,4],2.5);
      drawLine(A[0],A[1],5,5,'rgba(244,63,94,0.55)',[6,4],2.5);
      drawPoly([A,B,C],'#34d399');
      drawVerts([A,B,C]);
      // Orthocenter of right triangle = right angle vertex
      const ox=1,oy=1;
      drawArrow(ox+3, oy+2.5, ox+0.3, oy+0.3,'#fbbf24');
      drawSpecialPoint(ox,oy,'#fbbf24','',0,0);
      drawVertLabel('A',A[0],A[1],6,14);
      drawVertLabel('B',B[0],B[1],6,-5);
      drawVertLabel('C',C[0],C[1],-22,5);
    },
    drawCircumcenter(){
      clearAndGrid();
      const A=[1,1],B=[9,1],C=[1,9];
      // Perpendicular bisectors
      drawLine(5,-0.5,5,10,'rgba(56,189,248,0.5)',[6,4],2.5);
      drawLine(-0.5,5,10,5,'rgba(56,189,248,0.5)',[6,4],2.5);
      drawLine(2,2,8,8,'rgba(56,189,248,0.5)',[6,4],2.5);
      drawPoly([A,B,C],'#34d399');
      drawVerts([A,B,C]);
      // Circumcenter of right triangle = midpoint of hypotenuse
      const cx=5,cy=5;
      drawArrow(cx+2.5,cy+2,cx+0.3,cy+0.3,'#fbbf24');
      drawSpecialPoint(cx,cy,'#fbbf24','',0,0);
      drawVertLabel('A',A[0],A[1],-18,-2);
      drawVertLabel('B',B[0],B[1],6,-2);
      drawVertLabel('C',C[0],C[1],-22,5);
    },
    drawIncenter(){
      clearAndGrid();
      // Use equilateral-ish triangle for clearer bisectors
      const A=[1,1],B=[9,1],C=[5,9];
      // Compute incenter: I = (a*A + b*B + c*C)/(a+b+c)
      const dist=(p,q)=>Math.sqrt((p[0]-q[0])**2+(p[1]-q[1])**2);
      const a=dist(B,C), b=dist(A,C), c=dist(A,B);
      const ix=(a*A[0]+b*B[0]+c*C[0])/(a+b+c);
      const iy=(a*A[1]+b*B[1]+c*C[1])/(a+b+c);
      // Draw angle bisectors from each vertex to incenter (extended)
      drawLine(A[0],A[1],ix+(ix-A[0])*0.2,iy+(iy-A[1])*0.2,'rgba(168,85,247,0.6)',[6,4],2.5);
      drawLine(B[0],B[1],ix+(ix-B[0])*0.2,iy+(iy-B[1])*0.2,'rgba(168,85,247,0.6)',[6,4],2.5);
      drawLine(C[0],C[1],ix+(ix-C[0])*0.2,iy+(iy-C[1])*0.2,'rgba(168,85,247,0.6)',[6,4],2.5);
      drawPoly([A,B,C],'#34d399');
      drawVerts([A,B,C]);
      drawArrow(ix+2.5,iy-1.5,ix+0.2,iy-0.1,'#fbbf24');
      drawSpecialPoint(ix,iy,'#fbbf24','',0,0);
      drawVertLabel('A',A[0],A[1],-18,-2);
      drawVertLabel('B',B[0],B[1],6,-2);
      drawVertLabel('C',C[0],C[1],-4,8);
    },
    drawMedians(){
      // Same as centroid but highlight medians more prominently
      this.drawCentroid();
    },
    drawHypotenuse(){
      clearAndGrid();
      const A=[1,1],B=[9,1],C=[1,9];
      drawPoly([A,B,C],'#34d399');
      // Highlight hypotenuse
      ctx.save();
      ctx.beginPath();ctx.moveTo(gx(B[0]),gy(B[1]));ctx.lineTo(gx(C[0]),gy(C[1]));
      ctx.strokeStyle='#fbbf24';ctx.lineWidth=5;ctx.shadowColor='#fbbf24';ctx.shadowBlur=18;ctx.stroke();
      ctx.restore();
      drawVerts([A,B,C]);
      drawArrow(6,7,5,5.8,'#fbbf24');
      drawVertLabel('A',A[0],A[1],-18,-2);
      drawVertLabel('B',B[0],B[1],6,-2);
      drawVertLabel('C',C[0],C[1],-22,5);
    },
    drawTriTypes(){
      // Three triangles drawn with distinct proportions (equal-sided,
      // two-equal-sided, and no-equal-sided) — no text names, so the
      // student has to identify the scalene one visually.
      clearAndGrid();
      // Roughly equilateral (left)
      drawPoly([[0.5,3],[3,3],[1.75,6]],'#34d399');
      // Isosceles (center)
      drawPoly([[3.8,2.5],[6.2,2.5],[5,7]],'#60a5fa');
      // Scalene (right)
      drawPoly([[7,3],[9.5,3],[9,7.5]],'#fbbf24');
    },
    drawParaFamily(){
      // Four shapes drawn with distinguishing geometry only (right
      // angles vs. equal sides vs. slanted parallel sides) — no text
      // names, so the student identifies each shape visually.
      clearAndGrid();
      // Rectangle
      drawPoly([[0.5,6],[3.5,6],[3.5,9],[0.5,9]],'#60a5fa');
      // Rhombus
      drawPoly([[5,6],[6.5,8],[5,10],[3.5,8]],'#f43f5e');
      // Square
      drawPoly([[7.5,6],[9.5,6],[9.5,8],[7.5,8]],'#34d399');
      // Standard (slanted) parallelogram
      drawPoly([[1,2],[7,2],[8.5,5],[2.5,5]],'#818cf8');
    },
    drawParaDiagonals(){
      clearAndGrid();
      const pts=[[1,2],[9,2],[8,8],[2,8]];
      drawPoly(pts,'#60a5fa');
      // Diagonals
      drawLine(1,2,8,8,'#fbbf24',[6,4],3);
      drawLine(9,2,2,8,'#fbbf24',[6,4],3);
      drawVerts(pts);
      // Intersection point of the diagonals — left unlabeled since
      // naming what happens there is the question being asked.
      drawSpecialPoint(4.5,5,'#fbbf24','',8,-10);
      drawArrow(6,7,4.7,5.2,'#fbbf24');
    },
    drawTrapMidSegment(){
      clearAndGrid();
      const pts=[[1,2],[9,2],[7,7],[3,7]];
      drawPoly(pts,'#fbbf24');
      // Midsegment: midpoints of legs
      const ms1=[(1+3)/2,(2+7)/2]; // left leg midpoint
      const ms2=[(9+7)/2,(2+7)/2]; // right leg midpoint
      drawLine(ms1[0],ms1[1],ms2[0],ms2[1],'#a855f7',[],4);
      ctx.save();ctx.beginPath();ctx.arc(gx(ms1[0]),gy(ms1[1]),5,0,Math.PI*2);ctx.fillStyle='#a855f7';ctx.fill();ctx.restore();
      ctx.save();ctx.beginPath();ctx.arc(gx(ms2[0]),gy(ms2[1]),5,0,Math.PI*2);ctx.fillStyle='#a855f7';ctx.fill();ctx.restore();
      drawVerts(pts);
      drawLabel('b₁ = 8 cm',4,1.3,'#34d399');
      drawLabel('b₂ = 4 cm',3.5,7.8,'#818cf8');
      drawArrow(6,5,ms2[0]+0.2,ms2[1]-0.1,'#a855f7');
      // No "midsegment" text on canvas — naming the purple segment is the answer.
    },
    drawTrapIsosc(){
      clearAndGrid();
      const pts=[[2,2],[8,2],[7,7],[3,7]];
      drawPoly(pts,'#fbbf24');
      drawVerts(pts);
      // Highlight equal legs with tick marks
      const markLine=(x1,y1,x2,y2,color)=>{
        const mx=(x1+x2)/2,my=(y1+y2)/2;
        const dx=x2-x1,dy=y2-y1;
        const len=Math.sqrt(dx*dx+dy*dy);
        const px=-dy/len*0.3,py=dx/len*0.3;
        drawLine(mx+px,my-py,mx-px,my+py,color,[],3);
      };
      markLine(2,2,3,7,'#f43f5e');
      markLine(8,2,7,7,'#f43f5e');
      drawLabel('Equal legs →',0.3,4.5,'#f43f5e');
      drawLabel('← Equal legs',5.5,4.5,'#f43f5e',60,0);
      // No "isosceles trapezoid" text — naming the shape is the answer.
    },
    drawRightTrapezoid(){
      clearAndGrid();
      // Right trapezoid: one leg perpendicular to the bases
      const ptsR=[[2,2],[8,2],[8,7],[2,8]];
      drawPoly(ptsR,'#fbbf24');
      drawVerts(ptsR);
      // Show right angle mark at B(8,2)
      ctx.save();
      ctx.strokeStyle='#f43f5e';ctx.lineWidth=2.5;
      ctx.beginPath();
      ctx.moveTo(gx(8)-16,gy(2));
      ctx.lineTo(gx(8)-16,gy(2)-16);
      ctx.lineTo(gx(8),gy(2)-16);
      ctx.stroke();
      ctx.restore();
      drawArrow(6,4.5,8.2,4.5,'#f43f5e');
      drawLabel('Right angle leg →',3,5,'#f43f5e',-20,0);
      // No "right trapezoid" text — naming the shape is the answer.
    },
  };

  function drawDefault(shape){
    clearAndGrid();
    if(shape==='triangle'){
      drawPoly(TRI_STD,'#34d399');
      drawLine(2,2,8,2,'#34d399',[],5);
      drawLine(5,9,5,2,'#f43f5e',[8,6],3);
      drawVerts(TRI_STD);
      drawLabel('b = 6 cm',3,1.3,'#34d399');
      drawLabel('h = 7 cm',5.2,5.5,'#f43f5e');
    } else if(shape==='parallelogram'){
      drawPoly(PARA_PTS,'#60a5fa');
      drawLine(1,2,7,2,'#34d399',[],5);
      drawLine(3,8,3,2,'#f43f5e',[8,6],3);
      drawVerts(PARA_PTS);
      drawLabel('b = 6 cm',2.5,1.3,'#34d399');
      drawLabel('h = 6 cm',3.2,5,'#f43f5e');
    } else {
      drawPoly(TRAP_PTS,'#fbbf24');
      drawLine(2,2,8,2,'#34d399',[],5);
      drawLine(3,7,7,7,'#818cf8',[],5);
      drawLine(3,7,3,2,'#f43f5e',[8,6],3);
      drawVerts(TRAP_PTS);
      drawLabel('b₁=6 cm',3.5,1.3,'#34d399');
      drawLabel('b₂=4 cm',3.5,7.8,'#818cf8');
      drawLabel('h=5 cm',3.2,4.5,'#f43f5e');
    }
  }

  function runCanvasFn(fnName, shape){
    clearAndGrid();
    if(fnName && CANVAS_FNS[fnName]){
      CANVAS_FNS[fnName]();
    } else {
      drawDefault(shape);
    }
  }

  /* ═══════════════════════════════════════════════════
     TUTORIALS & FIELD GUIDE SLIDES (Rich Pedagogical Content)
  ═══════════════════════════════════════════════════ */
  const TUTORIALS = {
    guide: [
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#38bdf8;">🎮</div>
            <div>
              <h3 class="tut-card-title">Welcome to Geometry Grid Explorer!</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">Master calculating areas, perimeters, and geometric properties across 15 levels!</p>
            </div>
          </div>
          <p class="tut-card-desc">
            Explore coordinate shapes to calculate dimensions for <b>Triangles</b>, <b>Parallelograms</b>, and <b>Trapezoids</b> using formulas and interactive visual clues.
          </p>
          <div class="tut-visual-grid">
            <div class="tut-mini-box">
              <h5 style="color:#38bdf8;"><i class="fas fa-border-all"></i> The 1-cm Dot Grid</h5>
              <p>Each grid dot represents exactly <b>1 cm</b> (1 grid unit = 1 cm). Count grid steps along the base and height to verify lengths!</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#34d399;"><i class="fas fa-palette"></i> Canvas Color Codes</h5>
              <p><b style="color:#34d399;">Solid Green</b> = Base (b)<br><b style="color:#f43f5e;">Dashed Rose</b> = Height (h)<br><b style="color:#fbbf24;">Yellow</b> = Vertices &amp; Special Points</p>
            </div>
          </div>
          <div class="tut-callout">
            <i class="fas fa-lightbulb"></i>
            <span><b>Crucial Tip:</b> Always use the <b>perpendicular (straight-up) height</b>, NEVER the slanted side!</span>
          </div>
        `
      },
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#a78bfa;">🎯</div>
            <div>
              <h3 class="tut-card-title">The 3 Question Missions &amp; Hotkeys</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">Each level tests your knowledge with 3 different question types.</p>
            </div>
          </div>
          <div class="tut-visual-grid">
            <div class="tut-mini-box" style="border-color:rgba(52,211,153,0.3);">
              <h5 style="color:#34d399;">📐 Computation</h5>
              <p>Calculate Area, Perimeter, or missing side dimensions using geometric formulas and grid measurements.</p>
            </div>
            <div class="tut-mini-box" style="border-color:rgba(251,191,36,0.3);">
              <h5 style="color:#fbbf24;">👁 Visual Inspection</h5>
              <p>Identify geometric features on the canvas (e.g. altitudes, medians, centroids, midsegments, hypotenuses).</p>
            </div>
            <div class="tut-mini-box" style="border-color:rgba(129,140,248,0.3);">
              <h5 style="color:#818cf8;">📚 Fact &amp; Theory</h5>
              <p>Test your understanding of angle sums, polygon properties, theorems, and famous mathematical discoveries.</p>
            </div>
            <div class="tut-mini-box" style="border-color:rgba(56,189,248,0.3);">
              <h5 style="color:#38bdf8;">⌨️ Keyboard Shortcuts</h5>
              <p>Press keys <b>[1]</b>, <b>[2]</b>, <b>[3]</b>, <b>[4]</b> or <b>[A]</b>–<b>[D]</b> to answer! Press <b>[Enter]</b> to advance.</p>
            </div>
          </div>
        `
      },
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#f59e0b;">⏱️</div>
            <div>
              <h3 class="tut-card-title">Timer, Scoring &amp; Level Passing</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">Score high to earn 3 stars and unlock the next geometry expedition!</p>
            </div>
          </div>
          <div class="tut-visual-grid">
            <div class="tut-mini-box">
              <h5 style="color:#f59e0b;"><i class="fas fa-stopwatch"></i> 5:00 Level Timer</h5>
              <p>You have 5 minutes per level. The timer <b>freezes automatically</b> whenever this Field Guide is open or paused!</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#34d399;"><i class="fas fa-trophy"></i> Passing Requirement</h5>
              <p>Answer at least <b>8 questions correctly</b> to pass the level and unlock the next stage on your map.</p>
            </div>
          </div>
          <div class="tut-example-card">
            <b>⭐ Star Rating System:</b><br>
            • <b>3 Stars (⭐⭐⭐):</b> 8+ correct answers with high accuracy<br>
            • <b>2 Stars (⭐⭐):</b> 4–7 correct answers<br>
            • <b>1 Star (⭐):</b> 1–3 correct answers
          </div>
        `
      }
    ],
    triangle: [
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#34d399;">🔺</div>
            <div>
              <h3 class="tut-card-title">Triangle Anatomy &amp; Core Rules</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">3 sides, 3 vertices, interior angles always sum to 180°!</p>
            </div>
          </div>
          <p class="tut-card-desc">
            The triangle is the strongest shape in architecture and engineering, found in bridges, roofs, and cranes.
          </p>
          <div class="tut-visual-grid">
            <div class="tut-mini-box">
              <h5 style="color:#34d399;">Base (b)</h5>
              <p>The chosen reference side at the bottom (shown in solid green on the canvas).</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#f43f5e;">Perpendicular Height (h)</h5>
              <p>The straight-up distance at a <b>90° right angle</b> from the base to the opposite top vertex.</p>
            </div>
          </div>
          <div class="tut-callout">
            <i class="fas fa-exclamation-triangle text-warning"></i>
            <span><b>Crucial Rule:</b> Never measure along the slanted leg for height! Height must be perpendicular (⟂) to the base.</span>
          </div>
        `
      },
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#38bdf8;">🧮</div>
            <div>
              <h3 class="tut-card-title">Triangle Area Formula &amp; Example</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">Why divide by 2? Because every triangle is exactly half of a parallelogram!</p>
            </div>
          </div>
          <div class="tut-formula-pill">
            <span class="tut-formula-title">Triangle Area Formula</span>
            <span class="tut-formula-eq">Area = ½ × base × height</span>
            <span class="tut-formula-sub">or written as: (b × h) ÷ 2</span>
          </div>
          <div class="tut-example-card">
            <b>📝 Worked Example:</b><br>
            • Given a triangle with <b>base = 6 cm</b> and <b>height = 4 cm</b>:<br>
            • Step 1: Multiply base × height: 6 × 4 = 24<br>
            • Step 2: Divide by 2: 24 ÷ 2 = <b>12 cm²</b>
          </div>
        `
      },
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#818cf8;">📐</div>
            <div>
              <h3 class="tut-card-title">Triangle Classifications</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">Triangles are classified by their sides and by their angles.</p>
            </div>
          </div>
          <div class="tut-visual-grid">
            <div class="tut-mini-box">
              <h5 style="color:#34d399;">By Side Lengths</h5>
              <p>• <b>Equilateral:</b> All 3 sides equal (all angles = 60°)<br>• <b>Isosceles:</b> Exactly 2 sides equal<br>• <b>Scalene:</b> No sides equal</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#60a5fa;">By Angle Sizes</h5>
              <p>• <b>Right:</b> Exactly one 90° angle (a² + b² = c²)<br>• <b>Acute:</b> All 3 angles &lt; 90°<br>• <b>Obtuse:</b> One angle &gt; 90°</p>
            </div>
          </div>
        `
      },
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#fbbf24;">⭐</div>
            <div>
              <h3 class="tut-card-title">The 4 Special Centers of a Triangle</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">These special points appear frequently in visual inspection questions!</p>
            </div>
          </div>
          <div class="tut-visual-grid">
            <div class="tut-mini-box">
              <h5 style="color:#fbbf24;">Centroid</h5>
              <p>Intersection of the 3 <b>Medians</b> (lines from vertices to midpoints of opposite sides). It is the center of gravity!</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#f43f5e;">Orthocenter</h5>
              <p>Intersection of the 3 <b>Altitudes</b> (perpendicular lines from vertices to opposite sides).</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#38bdf8;">Circumcenter</h5>
              <p>Intersection of the 3 <b>Perpendicular Bisectors</b> of the sides.</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#34d399;">Incenter</h5>
              <p>Intersection of the 3 <b>Angle Bisectors</b> of the vertices.</p>
            </div>
          </div>
        `
      }
    ],
    parallelogram: [
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#60a5fa;">◈</div>
            <div>
              <h3 class="tut-card-title">Parallelogram Key Properties</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">4-sided polygon where opposite sides are parallel and equal.</p>
            </div>
          </div>
          <div class="tut-visual-grid">
            <div class="tut-mini-box">
              <h5 style="color:#60a5fa;">Opposite Sides &amp; Angles</h5>
              <p>Opposite sides are <b>parallel and equal</b>. Opposite interior angles are <b>equal</b>.</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#a78bfa;">Diagonals Bisect</h5>
              <p>The two diagonals <b>bisect each other</b> (they cut each other exactly in half where they cross).</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#fbbf24;">Angle Sum = 360°</h5>
              <p>All 4 interior angles add up to <b>360°</b>. Consecutive angles (next to each other) always add up to <b>180°</b>.</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#34d399;">The Family</h5>
              <p>Rectangles, Rhombuses, and Squares are all special types of parallelograms!</p>
            </div>
          </div>
        `
      },
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#38bdf8;">🧮</div>
            <div>
              <h3 class="tut-card-title">Parallelogram Area &amp; Perimeter</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">Area = base × height (No division by 2!)</p>
            </div>
          </div>
          <div class="tut-formula-pill">
            <span class="tut-formula-title">Parallelogram Formulas</span>
            <span class="tut-formula-eq">Area = base × height</span>
            <span class="tut-formula-sub">Perimeter = 2 × (side a + side b)</span>
          </div>
          <div class="tut-example-card">
            <b>📝 Worked Example:</b><br>
            • Given a parallelogram with <b>base = 8 cm</b> and <b>height = 5 cm</b>:<br>
            • Calculation: Area = 8 × 5 = <b>40 cm²</b><br>
            • If sides are 8 cm and 5 cm: Perimeter = 2 × (8 + 5) = <b>26 cm</b>
          </div>
        `
      },
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#f472b6;">👨‍👩‍👧‍👦</div>
            <div>
              <h3 class="tut-card-title">The Parallelogram Family</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">Rectangles, Rhombuses, and Squares</p>
            </div>
          </div>
          <div class="tut-visual-grid">
            <div class="tut-mini-box">
              <h5 style="color:#38bdf8;">Rectangle</h5>
              <p>Parallelogram with <b>4 right angles (90°)</b>. Diagonals are equal in length.</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#f472b6;">Rhombus</h5>
              <p>Parallelogram with <b>all 4 sides equal</b>. Diagonals intersect at perpendicular right angles (90°)!</p>
            </div>
            <div class="tut-mini-box" style="grid-column: span 2;">
              <h5 style="color:#fbbf24;">Square</h5>
              <p>The ultimate shape: both a Rectangle AND a Rhombus! (4 equal sides + 4 right angles).</p>
            </div>
          </div>
        `
      }
    ],
    trapezoid: [
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#fbbf24;">🏔️</div>
            <div>
              <h3 class="tut-card-title">Trapezoid Anatomy &amp; Types</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">Exactly one pair of parallel sides (the bases)!</p>
            </div>
          </div>
          <div class="tut-visual-grid">
            <div class="tut-mini-box">
              <h5 style="color:#34d399;">Bases (b₁ and b₂)</h5>
              <p>The two parallel horizontal sides (one long base, one short base).</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#a78bfa;">Legs</h5>
              <p>The two non-parallel slanted sides connecting the bases.</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#60a5fa;">Isosceles Trapezoid</h5>
              <p>Both legs are <b>equal in length</b>. Base angles are equal, and diagonals are equal!</p>
            </div>
            <div class="tut-mini-box">
              <h5 style="color:#f59e0b;">Right Trapezoid</h5>
              <p>One leg is perpendicular (90°) to both bases. That leg is exactly equal to the height!</p>
            </div>
          </div>
        `
      },
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#38bdf8;">🧮</div>
            <div>
              <h3 class="tut-card-title">Trapezoid Area &amp; Midsegment</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">Average the two bases, then multiply by the height!</p>
            </div>
          </div>
          <div class="tut-formula-pill">
            <span class="tut-formula-title">Trapezoid Formulas</span>
            <span class="tut-formula-eq">Area = ½ × (b₁ + b₂) × height</span>
            <span class="tut-formula-sub">Midsegment = ½ × (b₁ + b₂)</span>
          </div>
          <div class="tut-example-card">
            <b>📝 Worked Example:</b><br>
            • Given bases <b>b₁ = 6 cm</b>, <b>b₂ = 4 cm</b>, and <b>height = 5 cm</b>:<br>
            • Step 1: Average the bases: (6 + 4) ÷ 2 = 5 cm (Midsegment)<br>
            • Step 2: Multiply by height: 5 × 5 = <b>25 cm²</b>!
          </div>
        `
      }
    ],
    formulas: [
      {
        html: `
          <div class="tut-card-hero">
            <div class="tut-hero-icon" style="color:#38bdf8;">📐</div>
            <div>
              <h3 class="tut-card-title">Geometry Master Formula Quick-Sheet</h3>
              <p style="color:#94a3b8; margin:0; font-size:1.0rem;">All your essential geometry formulas in one easy reference table!</p>
            </div>
          </div>
          <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:12px; font-size:1.0rem;">
            <div style="background:rgba(52,211,153,0.1); border:1px solid rgba(52,211,153,0.3); border-radius:10px; padding:10px 14px; display:flex; justify-content:space-between; align-items:center;">
              <div><b style="color:#34d399;">🔺 Triangle</b><br><small style="color:#94a3b8;">Angles = 180°</small></div>
              <div style="font-family:var(--font-mono, monospace); font-weight:800; color:#ffffff; text-align:right;">Area = ½ × b × h<br><small style="color:#94a3b8;">P = a + b + c</small></div>
            </div>
            <div style="background:rgba(96,165,250,0.1); border:1px solid rgba(96,165,250,0.3); border-radius:10px; padding:10px 14px; display:flex; justify-content:space-between; align-items:center;">
              <div><b style="color:#60a5fa;">◈ Parallelogram</b><br><small style="color:#94a3b8;">Angles = 360°</small></div>
              <div style="font-family:var(--font-mono, monospace); font-weight:800; color:#ffffff; text-align:right;">Area = b × h<br><small style="color:#94a3b8;">P = 2(a + b)</small></div>
            </div>
            <div style="background:rgba(251,191,36,0.1); border:1px solid rgba(251,191,36,0.3); border-radius:10px; padding:10px 14px; display:flex; justify-content:space-between; align-items:center;">
              <div><b style="color:#fbbf24;">🏔️ Trapezoid</b><br><small style="color:#94a3b8;">Angles = 360°</small></div>
              <div style="font-family:var(--font-mono, monospace); font-weight:800; color:#ffffff; text-align:right;">Area = ½(b₁ + b₂)h<br><small style="color:#94a3b8;">Midsegment = ½(b₁ + b₂)</small></div>
            </div>
          </div>
        `
      }
    ]
  };

  /* ═══════════════════════════════════════════════════
     ALL QUESTIONS
     type: 'computation' | 'visual' | 'fact'
     canvasFn: key into CANVAS_FNS (or null = default shape)
  ═══════════════════════════════════════════════════ */
  const ALL_Q = [
    // ════ TRIANGLE (indices 0–19) ════
    // Mix of computation, visual, and fact questions
    {q:"A triangle has a base of 6 cm and a height of 4 cm. What is its area?",correct:"12 cm²",opts:["12 cm²","24 cm²","10 cm²","8 cm²"],type:'computation',canvasFn:'triArea_6_4'},
    {q:"Look at the shape. The arrow points to the intersection of the three MEDIANS. What is this special point called?",correct:"The centroid",opts:["The incenter","The circumcenter","The orthocenter","The centroid"],type:'visual',canvasFn:'drawCentroid'},
    {q:"What is the sum of the interior angles of any triangle?",correct:"180°",opts:["90°","180°","270°","360°"],type:'fact',canvasFn:null},
    {q:"A right triangle has a base of 8 cm and a height of 6 cm. What is its area?",correct:"24 cm²",opts:["24 cm²","48 cm²","14 cm²","36 cm²"],type:'computation',canvasFn:'triArea_8_6'},
    {q:"The dashed lines show the three ALTITUDES of this triangle. Where do they all meet?",correct:"The orthocenter",opts:["The centroid","The circumcenter","The incenter","The orthocenter"],type:'visual',canvasFn:'drawOrthocenter'},
    {q:"In an equilateral triangle, each angle measures exactly how many degrees?",correct:"60°",opts:["45°","60°","90°","120°"],type:'fact',canvasFn:null},
    {q:"What is the area of a triangle with base 10 cm and height 4 cm?",correct:"20 cm²",opts:["20 cm²","40 cm²","14 cm²","25 cm²"],type:'computation',canvasFn:'triArea_10_4'},
    {q:"The dashed lines show the PERPENDICULAR BISECTORS of each side. Where do they all meet?",correct:"The circumcenter",opts:["The centroid","The circumcenter","The incenter","The orthocenter"],type:'visual',canvasFn:'drawCircumcenter'},
    {q:"Which ancient mathematician is credited with a² + b² = c²?",correct:"Pythagoras",opts:["Euclid","Archimedes","Pythagoras","Thales"],type:'fact',canvasFn:null},
    {q:"A triangular sail has a base of 5 cm and a height of 8 cm. What is its area?",correct:"20 cm²",opts:["20 cm²","13 cm²","40 cm²","32 cm²"],type:'computation',canvasFn:'triArea_5_8'},
    {q:"The dashed lines show the ANGLE BISECTORS of each vertex. Where do they all meet?",correct:"The incenter",opts:["The centroid","The circumcenter","The incenter","The orthocenter"],type:'visual',canvasFn:'drawIncenter'},
    {q:"What is the sum of the EXTERIOR angles of any triangle?",correct:"360°",opts:["180°","270°","360°","540°"],type:'fact',canvasFn:null},
    {q:"A triangle with all sides equal to 5 cm. What type of triangle is it?",correct:"Equilateral triangle",opts:["Scalene triangle","Isosceles triangle","Right triangle","Equilateral triangle"],type:'computation',canvasFn:'triPerim'},
    {q:"The highlighted side is the longest side of this right triangle. What is it called?",correct:"The hypotenuse",opts:["The base","The leg","The median","The hypotenuse"],type:'visual',canvasFn:'drawHypotenuse'},
    {q:"Which mathematician wrote 'Elements,' formally organizing triangle geometry?",correct:"Euclid",opts:["Pythagoras","Archimedes","Euclid","Thales"],type:'fact',canvasFn:null},
    {q:"Which triangle in this image has NO equal sides?",correct:"Scalene triangle",opts:["Equilateral triangle","Isosceles triangle","Right triangle","Scalene triangle"],type:'visual',canvasFn:'drawTriTypes'},
    {q:"The Exterior Angle Theorem states that an exterior angle of a triangle equals the sum of which two angles?",correct:"The two non-adjacent interior angles",opts:["The two adjacent interior angles","The two non-adjacent interior angles","The two exterior angles","The two supplementary angles"],type:'fact',canvasFn:null},
    {q:"Which mathematician developed a formula using the semi-perimeter to find a triangle's area from only its three side lengths?",correct:"Heron of Alexandria",opts:["Euclid","Pythagoras","Heron of Alexandria","Archimedes"],type:'computation',canvasFn:null},
    {q:"Pascal's Triangle is a triangular arrangement of numbers. Who is it named after?",correct:"Blaise Pascal",opts:["Leonhard Euler","Blaise Pascal","Carl Gauss","Isaac Newton"],type:'fact',canvasFn:null},
    {q:"A triangle with all angles less than 90° is called ______.",correct:"Acute triangle",opts:["Right triangle","Obtuse triangle","Acute triangle","Equilateral triangle"],type:'fact',canvasFn:null},

    // ════ PARALLELOGRAM (indices 20–34) ════
    {q:"A parallelogram has a base of 6 cm and a height of 4 cm. What is its area?",correct:"24 cm²",opts:["24 cm²","12 cm²","48 cm²","20 cm²"],type:'computation',canvasFn:'paraArea_6_4'},
    {q:"What is the area of a parallelogram with base 8 cm and height 5 cm?",correct:"40 cm²",opts:["13 cm²","20 cm²","40 cm²","80 cm²"],type:'computation',canvasFn:'paraArea_8_5'},
    {q:"A floor tile is shaped like a parallelogram with base 7 cm and height 6 cm. What is its area?",correct:"42 cm²",opts:["42 cm²","13 cm²","21 cm²","84 cm²"],type:'computation',canvasFn:'paraArea_7_6'},
    {q:"Look at the image. The arrows point to a special property of the diagonals. They ______ each other.",correct:"bisect",opts:["double","bisect","triple","perpendicular"],type:'visual',canvasFn:'drawParaDiagonals'},
    {q:"Which shapes in the image are ALL types of parallelograms?",correct:"Rectangle, Rhombus, and Square",opts:["Only Rectangle","Rectangle and Square only","Rectangle, Rhombus, and Square","Rhombus and Trapezoid"],type:'visual',canvasFn:'drawParaFamily'},
    {q:"What is the sum of the interior angles of a parallelogram?",correct:"360°",opts:["180°","270°","360°","540°"],type:'fact',canvasFn:null},
    {q:"In a parallelogram, consecutive angles (next to each other) always add up to ______.",correct:"180°",opts:["90°","180°","270°","360°"],type:'fact',canvasFn:null},
    {q:"True or False: Every rectangle is a parallelogram.",correct:"True",opts:["True","False","Only sometimes","Only if it's a square"],type:'fact',canvasFn:null},
    {q:"True or False: Every parallelogram is a rectangle.",correct:"False",opts:["True","False","Only if angles are acute","Only sometimes"],type:'fact',canvasFn:null},
    {q:"What is the formula for the perimeter of a parallelogram with sides a and b?",correct:"2(a + b)",opts:["a + b","2(a + b)","4a","a × b"],type:'fact',canvasFn:null},
    {q:"In a rhombus (a special parallelogram), what is true about the diagonals?",correct:"They are perpendicular to each other",opts:["They are equal in length","They are perpendicular to each other","They bisect the perimeter","They are parallel"],type:'fact',canvasFn:null},
    {q:"What do you call a parallelogram with four right angles?",correct:"Rectangle",opts:["Rhombus","Square","Rectangle","Trapezoid"],type:'fact',canvasFn:null},
    {q:"What is the name of a parallelogram with all four sides equal?",correct:"Rhombus",opts:["Rectangle","Rhombus","Square","Kite"],type:'fact',canvasFn:null},
    {q:"A parallelogram has sides of 5 cm and 8 cm. What is its perimeter?",correct:"26 cm",opts:["13 cm","26 cm","40 cm","30 cm"],type:'computation',canvasFn:'paraDefault'},
    {q:"Which branch of geometry, developed by Euclid, formally studies parallelograms?",correct:"Euclidean geometry",opts:["Non-Euclidean geometry","Analytic geometry","Euclidean geometry","Projective geometry"],type:'fact',canvasFn:null},

    // ════ TRAPEZOID (indices 35–49) ════
    {q:"A trapezoid has bases of 6 cm and 4 cm, and a height of 5 cm. What is its area?",correct:"25 cm²",opts:["25 cm²","50 cm²","10 cm²","30 cm²"],type:'computation',canvasFn:'trapArea_6_4_5'},
    {q:"Find the area of a trapezoid with bases 8 cm and 4 cm, and height 6 cm.",correct:"36 cm²",opts:["36 cm²","72 cm²","24 cm²","48 cm²"],type:'computation',canvasFn:'trapArea_8_4_6'},
    {q:"A highway sign is trapezoidal with bases 10 cm and 6 cm, and height 4 cm. What is its area?",correct:"32 cm²",opts:["32 cm²","64 cm²","16 cm²","40 cm²"],type:'computation',canvasFn:'trapArea_10_6_4'},
    {q:"The purple segment in the image connects the midpoints of the two legs. What is it called?",correct:"The midsegment",opts:["The altitude","The diagonal","The midsegment","The base"],type:'visual',canvasFn:'drawTrapMidSegment'},
    {q:"The image shows a trapezoid with equal legs and tick marks. What type of trapezoid is this?",correct:"Isosceles trapezoid",opts:["Right trapezoid","Isosceles trapezoid","Scalene trapezoid","Equilateral trapezoid"],type:'visual',canvasFn:'drawTrapIsosc'},
    {q:"The image shows a trapezoid with one leg forming a right angle with the bases. What is it called?",correct:"Right trapezoid",opts:["Right trapezoid","Isosceles trapezoid","Scalene trapezoid","Obtuse trapezoid"],type:'visual',canvasFn:'drawRightTrapezoid'},
    {q:"What is the defining feature of a trapezoid?",correct:"Exactly one pair of parallel sides",opts:["Two pairs of parallel sides","Four equal sides","Exactly one pair of parallel sides","Four right angles"],type:'fact',canvasFn:null},
    {q:"What are the non-parallel sides of a trapezoid called?",correct:"The legs",opts:["The bases","The medians","The legs","The diagonals"],type:'fact',canvasFn:null},
    {q:"How do you calculate the length of the midsegment of a trapezoid?",correct:"½ × (b₁ + b₂)",opts:["b₁ × b₂","b₁ + b₂","½ × (b₁ + b₂)","(b₁ - b₂) ÷ 2"],type:'fact',canvasFn:null},
    {q:"True or False: A parallelogram can be considered a special type of trapezoid under the inclusive definition.",correct:"True",opts:["True","False","Only if it is a rectangle","Only sometimes"],type:'fact',canvasFn:null},
    {q:"What is the sum of the interior angles of a trapezoid?",correct:"360°",opts:["180°","270°","360°","540°"],type:'fact',canvasFn:null},
    {q:"In a trapezoid, the two angles along the same leg always add up to ______.",correct:"180°",opts:["90°","180°","270°","360°"],type:'fact',canvasFn:null},
    {q:"In an isosceles trapezoid, what is true about the diagonals?",correct:"They are equal in length",opts:["They bisect each other","They are perpendicular","They are equal in length","They are parallel"],type:'fact',canvasFn:null},
    {q:"A trapezoid has four sides: 8 cm, 5 cm, 6 cm, and 5 cm. What is its perimeter?",correct:"24 cm",opts:["24 cm","13 cm","48 cm","18 cm"],type:'computation',canvasFn:'trapDefault'},
    {q:"Which ancient Greek mathematician's 'Elements' included early formal studies of trapezoids?",correct:"Euclid",opts:["Pythagoras","Archimedes","Euclid","Thales"],type:'fact',canvasFn:null},
  ];

  /* Question pool slices */
  function poolForLevel(idx){
    if(idx<5)  return ALL_Q.slice(0,20);
    if(idx<10) return ALL_Q.slice(20,35);
    return ALL_Q.slice(35,50);
  }

  /* Build questions with all available from pool, no two consecutive facts */
  function buildLevelQuestions(lvlIdx){
    const pool = poolForLevel(lvlIdx);
    // Split by type
    const facts = pool.filter(q=>q.type==='fact');
    const nonFacts = pool.filter(q=>q.type!=='fact');
    // Shuffle each
    shuffle(facts); shuffle(nonFacts);
    // Interleave: start with nonFact, then alternate
    const result=[];
    let fi=0,ni=0;
    while(fi<facts.length || ni<nonFacts.length){
      // Add a nonFact if available
      if(ni<nonFacts.length) result.push(nonFacts[ni++]);
      // Add a fact if available
      if(fi<facts.length) result.push(facts[fi++]);
    }
    return result; // Return all questions from the pool
  }

  function shuffle(arr){for(let i=arr.length-1;i>0;i--){const j=Math.floor(Math.random()*(i+1));[arr[i],arr[j]]=[arr[j],arr[i]];}}

  function getShape(idx){ return idx<5?'triangle':idx<10?'parallelogram':'trapezoid'; }

  /* ═══════════════════════════════════════════════════
     SHAPE CONFIG
  ═══════════════════════════════════════════════════ */
  const SHAPE_CFG = {
    triangle:      {tag:'▲ TRIANGLE',      tagCls:'shape-tag-triangle',      icon:'🔺',iconCls:'icon-t',label:'Triangle'},
    parallelogram: {tag:'▰ PARALLELOGRAM', tagCls:'shape-tag-parallelogram', icon:'◈', iconCls:'icon-p',label:'Parallelogram'},
    trapezoid:     {tag:'⌂ TRAPEZOID',     tagCls:'shape-tag-trapezoid',     icon:'🏔️',iconCls:'icon-tr',label:'Trapezoid'},
  };
  const QTYPE_CFG = {
    computation:{cls:'qtag-computation',label:'📐 COMPUTATION'},
    visual:     {cls:'qtag-visual',     label:'👁 VISUAL'},
    fact:       {cls:'qtag-fact',       label:'📚 FACT'},
  };

  /* ═══════════════════════════════════════════════════
     STATE
  ═══════════════════════════════════════════════════ */
  const S = {
    lvl: Math.max(0, gProgress-1),
    questions:[], qi:0, score:0, streak:0, correct:0, answered:false,
    tutIdx:0, tutSlides:[], tutShown:{triangle:false,parallelogram:false,trapezoid:false},
    timeLeft:300, timerInterval:null, timerStarted:false, // 5 minutes = 300 seconds
    paused:false
  };

  /* ═══════════════════════════════════════════════════
     UI
  ═══════════════════════════════════════════════════ */
  function updateTop(){
    const shape=getShape(S.lvl), sc=SHAPE_CFG[shape];
    document.getElementById('levelBadge').textContent=`LEVEL ${S.lvl+1}`;
    const st=document.getElementById('shapeTag');
    st.textContent=sc.tag; st.className=`geo-shape-tag ${sc.tagCls}`;
    document.getElementById('canvasTitle').textContent=sc.label;
    const ci=document.getElementById('canvasIconEl');
    ci.textContent=sc.icon; ci.className=`canvas-icon ${sc.iconCls}`;
  }

  function updateQTypeTag(type){
    const tc=QTYPE_CFG[type];
    const el=document.getElementById('qTypeTag');
    el.textContent=tc.label; el.className=`geo-q-type-tag ${tc.cls}`;
  }

  function updateProgress(){
    document.getElementById('progressCount').textContent=`${S.lvl} / 15 levels`;
    document.getElementById('progressFill').style.width=`${(S.lvl/15)*100}%`;
  }

  function updateScores(){
    document.getElementById('scoreVal').textContent=S.score;
    document.getElementById('streakVal').textContent=S.streak;
    document.getElementById('correctVal').textContent=S.correct;
  }

  /* ═══════════════════════════════════════════════════
     TIMER
  ═══════════════════════════════════════════════════ */
  function startTimer(){
    if(S.timerStarted || !S.gameStarted || S.paused) return;
    const tutEl = document.getElementById('tutOverlay');
    if(tutEl && tutEl.classList.contains('show')) return;

    S.timerStarted = true;
    updateTimerDisplay();
    
    clearInterval(S.timerInterval);
    S.timerInterval = setInterval(()=>{
      if(S.paused || !S.gameStarted) return;
      S.timeLeft--;
      updateTimerDisplay();
      
      if(S.timeLeft <= 0){
        clearInterval(S.timerInterval);
        timeUp();
      }
    }, 1000);
  }
  
  function stopTimer(){
    if(S.timerInterval){
      clearInterval(S.timerInterval);
      S.timerInterval = null;
    }
    S.timerStarted = false;
  }
  
  function updateTimerDisplay(){
    const minutes = Math.floor(S.timeLeft / 60);
    const seconds = S.timeLeft % 60;
    const display = `⏱ ${minutes}:${seconds.toString().padStart(2,'0')}`;
    const badge = document.getElementById('timerBadge');
    badge.textContent = display;
    
    // Update styling based on time left
    badge.classList.remove('warning','danger');
    if(S.timeLeft <= 30) badge.classList.add('danger'); // Last 30 seconds
    else if(S.timeLeft <= 60) badge.classList.add('warning'); // Last minute
  }
  
  function timeUp(){
    // Force end level when time runs out
    document.querySelectorAll('.ans-btn').forEach(b=>b.disabled=true);
    const fb=document.getElementById('feedbackDiv');
    fb.className='geo-fb wrong show';
    fb.innerHTML='⏰ Time\'s up! Level ended.';
    setTimeout(()=>endLevel(), 2000);
  }
  
  /* ═══════════════════════════════════════════════════
     PAUSE
  ═══════════════════════════════════════════════════ */
  window.togglePause = function(){
    S.paused = !S.paused;
    const overlay = document.getElementById('pauseOverlay');
    const btn = document.getElementById('pauseBtn');
    const icon = btn.querySelector('i');
    
    if(S.paused){
      // Pause the game
      stopTimer();
      overlay.classList.add('show');
      btn.classList.add('paused');
      icon.className = 'bi bi-play-fill';
      btn.title = 'Resume Game';
    } else {
      // Resume the game
      if(S.timerStarted) startTimer();
      overlay.classList.remove('show');
      btn.classList.remove('paused');
      icon.className = 'bi bi-pause-fill';
      btn.title = 'Pause Game';
    }

  };

  /* ═══════════════════════════════════════════════════
     EXIT CONFIRMATION
     Shown whenever the player tries to leave the game
     (either the top-bar exit button or the pause menu's
     "Exit to Map" option) instead of navigating away
     immediately.
  ═══════════════════════════════════════════════════ */
  let exitTargetUrl = null;
  let wasPlayingBeforeExitConfirm = false;

  window.requestExit = function(url, dest){
    if (typeof window.requestEduplayExit === 'function') {
      window.requestEduplayExit(url, dest);
    } else {
      window.location.href = url;
    }
  };

  /* ═══════════════════════════════════════════════════
     TUTORIAL & FIELD GUIDE CONTROLLER
  ═══════════════════════════════════════════════════ */
  window.openTutorialGuide = function(topic = null){
    stopTimer();
    if(topic && TUTORIALS[topic]){
      S.currentTopic = topic;
    } else {
      const shape = getShape(S.lvl);
      S.currentTopic = shape || 'guide';
    }
    S.tutIdx = 0;
    updateTopicTabsUI();
    renderTutSlide();
    document.getElementById('tutOverlay').classList.add('show');
  };

  window.closeTutorial = function(){
    localStorage.setItem('gg_tutorial_done_' + USER_ID, 'true');
    document.getElementById('tutOverlay').classList.remove('show');
    S.gameStarted = true;
    if(!S.paused && S.questions.length > 0 && !S.answered){
      startTimer();
    }
  };

  window.switchTutTopic = function(topic){
    if(!TUTORIALS[topic]) return;
    S.currentTopic = topic;
    S.tutIdx = 0;
    updateTopicTabsUI();
    renderTutSlide();
  };

  function updateTopicTabsUI(){
    document.querySelectorAll('.tut-tab-btn').forEach(btn => {
      btn.classList.toggle('active', btn.id === 'tab-' + S.currentTopic);
    });
  }

  function renderTutSlide(){
    const topic = S.currentTopic;
    const slides = TUTORIALS[topic] || [];
    if(slides.length === 0) return;
    if(S.tutIdx >= slides.length) S.tutIdx = 0;

    const sl = slides[S.tutIdx];
    const tot = slides.length;

    const topicNames = {
      guide: 'GAMEPLAY & GRID BASICS',
      triangle: 'TRIANGLE MASTERY',
      parallelogram: 'PARALLELOGRAM MASTERY',
      trapezoid: 'TRAPEZOID MASTERY',
      formulas: 'FORMULA QUICK SHEET'
    };

    document.getElementById('tutTopicIndicator').textContent = topicNames[topic] || 'FIELD GUIDE';
    document.getElementById('tutSlideNum').textContent = `SLIDE ${S.tutIdx + 1} OF ${tot}`;

    // Render HTML content of the slide
    document.getElementById('tutContentArea').innerHTML = sl.html;

    // Render dots
    let dots = '';
    for(let i = 0; i < tot; i++){
      dots += `<div class="tut-dot${i === S.tutIdx ? ' active' : ''}" onclick="goTutSlide(${i})"></div>`;
    }
    document.getElementById('tutDots').innerHTML = dots;

    // Prev / Next / Start buttons
    document.getElementById('tutPrevBtn').style.display = S.tutIdx > 0 ? 'inline-block' : 'none';
    const nextBtn = document.getElementById('tutNextBtn');
    const startBtn = document.getElementById('tutStartBtn');

    if(S.tutIdx === tot - 1){
      nextBtn.style.display = 'none';
      startBtn.style.display = 'inline-block';
    } else {
      nextBtn.style.display = 'inline-block';
      startBtn.style.display = 'none';
    }
  }

  window.goTutSlide = function(idx){
    const slides = TUTORIALS[S.currentTopic] || [];
    if(idx >= 0 && idx < slides.length){
      S.tutIdx = idx;
      renderTutSlide();
    }
  };

  window.tutPrev = function(){
    if(S.tutIdx > 0){
      S.tutIdx--;
      renderTutSlide();
    }
  };

  window.tutNext = function(){
    const slides = TUTORIALS[S.currentTopic] || [];
    if(S.tutIdx < slides.length - 1){
      S.tutIdx++;
      renderTutSlide();
    } else {
      closeTutorial();
    }
  };

  /* ═══════════════════════════════════════════════════
     QUESTIONS
  ═══════════════════════════════════════════════════ */
  function loadQ(){
    // Only start timer if game has officially started and tutorial is not open
    const tutEl = document.getElementById('tutOverlay');
    const isTutOpen = tutEl && tutEl.classList.contains('show');
    if(S.qi === 0 && !S.timerStarted && S.gameStarted && !S.paused && !isTutOpen) {
      startTimer();
    }
    
    const q=S.questions[S.qi], tot=S.questions.length;
    S.answered=false;
    document.getElementById('qNum').textContent=`QUESTION ${S.qi+1} OF ${tot}`;
    document.getElementById('qText').textContent=q.q;
    updateQTypeTag(q.type);

    // Update canvas based on question
    runCanvasFn(q.canvasFn, getShape(S.lvl));

    // Canvas subtitle
    const subMap={
      computation:'Refer to the labeled shape',
      visual:'Observe the highlighted elements',
      fact:'General reference shape',
    };
    document.getElementById('canvasSub').textContent=subMap[q.type];

    // Reset
    document.getElementById('feedbackDiv').className='geo-fb';
    document.getElementById('feedbackDiv').textContent='';
    document.getElementById('nextBtn').className='geo-next';

    // Build MCQ
    const div=document.getElementById('answersDiv'); div.innerHTML='';
    const letters=['A','B','C','D'];
    const opts=[...q.opts]; shuffle(opts);
    opts.forEach((opt,i)=>{
      const btn=document.createElement('button');
      btn.className='ans-btn';
      btn.innerHTML=`<span class="ans-letter">${letters[i]}</span><span>${opt}</span><span style="margin-left:auto; font-size:0.95rem; color:#64748b; font-weight:700;">[${i+1}]</span>`;
      btn.onclick=()=>checkAns(opt,btn,q.correct);
      div.appendChild(btn);
    });
  }

  function checkAns(selected,btn,correct){
    if(S.answered) return;
    S.answered=true;
    document.querySelectorAll('.ans-btn').forEach(b=>b.disabled=true);
    const fb=document.getElementById('feedbackDiv');
    const nb=document.getElementById('nextBtn');
    if(selected===correct){
      btn.classList.add('correct'); S.correct++; S.streak++;
      const pts=10+Math.min(S.streak,5)*3;
      S.score+=pts;
      fb.className='geo-fb correct show';
      fb.innerHTML=`✅ Correct! +${pts} points${S.streak>=3?' 🔥 '+S.streak+' streak!':''}`;
      if(S.streak>=3) fireConfetti(18);
    } else {
      btn.classList.add('wrong'); S.streak=0;
      document.querySelectorAll('.ans-btn').forEach(b=>{
        if(b.querySelectorAll('span')[1].textContent===correct) b.classList.add('correct');
      });
      fb.className='geo-fb wrong show';
      fb.innerHTML=`❌ Not quite! Correct answer: <strong>${correct}</strong>`;
    }
    updateScores();
    nb.className='geo-next show';
    nb.onclick=advanceQ;
  }

  function advanceQ(){S.qi++;if(S.qi>=S.questions.length) endLevel();else loadQ();}

  /* ═══════════════════════════════════════════════════
     LEVEL
  ═══════════════════════════════════════════════════ */
  function startLevel(idx, showInstruction = false){
    if(idx>=15) idx=14;
    S.lvl=idx; S.questions=buildLevelQuestions(idx);
    S.qi=0; S.score=0; S.streak=0; S.correct=0;
    S.timeLeft=300;
    stopTimer();
    updateTop(); updateProgress(); updateScores(); updateTimerDisplay();

    loadQ();

    if(showInstruction){
      S.gameStarted = false;
      const shape = getShape(idx);
      openTutorialGuide(idx === 0 ? 'guide' : shape);
    } else {
      S.gameStarted = true;
      startTimer();
    }
  }

  function endLevel(){
    stopTimer();
    const tot=S.questions.length;
    const stars = S.correct >= 8 ? 3 : S.correct >= 4 ? 2 : 1;
    const isWin = S.correct >= 8; // Need 8+ correct to pass
    
    document.getElementById('cCorrect').textContent=`${S.correct}/${tot}`;
    document.getElementById('cScore').textContent=S.score;
    document.getElementById('cAcc').textContent=`${Math.round((S.correct/tot)*100)}%`;
    
    // Star display
    let starHtml = '';
    for(let i=1; i<=3; i++){
      starHtml += i <= stars ? '⭐' : '☆';
    }
    
    if(S.correct===tot){
      document.getElementById('cIcon').textContent='🏆';
      document.getElementById('cTitle').textContent='Perfect Score!';
      document.getElementById('cSub').textContent=`Absolutely flawless! ${starHtml}`;
      fireConfetti(50);
    }
    else if(stars===3){
      document.getElementById('cIcon').textContent='🎉';
      document.getElementById('cTitle').textContent='Excellent Work!';
      document.getElementById('cSub').textContent=`Amazing! ${S.correct}/${tot} correct! ${starHtml}`;
      fireConfetti(25);
    }
    else if(stars===2){
      document.getElementById('cIcon').textContent='👍';
      document.getElementById('cTitle').textContent='Good Job!';
      document.getElementById('cSub').textContent=`You got ${S.correct}/${tot} correct! ${starHtml}`;
    }
    else if(stars===1 && isWin){
      document.getElementById('cIcon').textContent='✅';
      document.getElementById('cTitle').textContent='Level Passed!';
      document.getElementById('cSub').textContent=`You passed with ${S.correct}/${tot}! ${starHtml}`;
    }
    else{
      document.getElementById('cIcon').textContent='😅';
      document.getElementById('cTitle').textContent='Try Again!';
      document.getElementById('cSub').textContent=`You need at least 8/${tot} correct. You got ${S.correct}/${tot}. ${starHtml}`;
    }
    
    const next=S.lvl+1, btn=document.getElementById('cBtn');
    if(isWin){
      saveProgress(next+1,S.score);
      btn.textContent=next<15?'Next Level →':'🎓 All Levels Complete!';
      btn.onclick=()=>{document.getElementById('completeOverlay').classList.remove('show');startLevel(next<15?next:14);};
    } else {
      btn.textContent='🔄 Retry Level';
      btn.onclick=()=>{document.getElementById('completeOverlay').classList.remove('show');startLevel(S.lvl);};
    }
    document.getElementById('completeOverlay').classList.add('show');
  }

  function saveProgress(nextLevel,score){
    fetch('/student/update-progress',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({game_id:GAME_ID,level:Math.min(nextLevel,15),score})}).catch(console.error);
  }

  function fireConfetti(n){
    const colors=['#6366f1','#34d399','#f43f5e','#fbbf24','#38bdf8','#a855f7'];
    for(let i=0;i<n;i++) setTimeout(()=>{
      const el=document.createElement('div'); el.className='confetti-piece';
      el.style.left=Math.random()*100+'vw';
      el.style.background=colors[Math.floor(Math.random()*colors.length)];
      el.style.animationDuration=(1.5+Math.random()*2)+'s';
      el.style.width=el.style.height=(6+Math.random()*8)+'px';
      document.body.appendChild(el); setTimeout(()=>el.remove(),4000);
    },i*40);
  }

  /* ═══════════════════════════════════════════════════
     KEYBOARD CONTROLS & SHORTCUTS
  ═══════════════════════════════════════════════════ */
  document.addEventListener('keydown', function(e){
    if(e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

    // Escape or P for pause / close tutorial
    if(e.key === 'Escape' || e.key === 'p' || e.key === 'P'){
      const tut = document.getElementById('tutOverlay');
      if(tut && tut.classList.contains('show')){
        closeTutorial();
        return;
      }
      togglePause();
      return;
    }

    // Number keys 1-4 or A-D for answering
    const keyMap = {'1':0, '2':1, '3':2, '4':3, 'a':0, 'b':1, 'c':2, 'd':3, 'A':0, 'B':1, 'C':2, 'D':3};
    if(keyMap.hasOwnProperty(e.key)){
      const idx = keyMap[e.key];
      const btns = document.querySelectorAll('.ans-btn');
      if(btns[idx] && !btns[idx].disabled){
        btns[idx].click();
      }
    }

    // Enter or Space to advance question when nextBtn is shown
    if(e.key === 'Enter' || e.key === ' '){
      const nb = document.getElementById('nextBtn');
      if(nb && nb.classList.contains('show')){
        nb.click();
      }
    }
  });

  /* ═══════════════════════════════════════════════════
     STARTUP
  ═══════════════════════════════════════════════════ */
  const shouldShowTutorial = paramTutorial || storageTutorial || urlParams.has('level') || urlParams.has('show_instruction') || !localStorage.getItem('gg_tutorial_done_' + USER_ID);

  startLevel(initialLvl, shouldShowTutorial);
})();
</script>
@endsection