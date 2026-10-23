@extends('layouts.app')
@section('hide-nav', true)
@section('content')

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- MapLibre GL -->
<link rel="stylesheet" href="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css" />
<script src="https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js"></script>

<!-- MapLibre GL Leaflet bridge -->
<script src="https://unpkg.com/@maplibre/maplibre-gl-leaflet@0.0.20/leaflet-maplibre-gl.js"></script>

<!-- Google Fonts: EduPlay Arcade Theme -->
<link href="https://fonts.googleapis.com/css2?family=Bungee&family=Baloo+2:wght@400;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

<style>
  *{box-sizing:border-box}
  html{ -webkit-text-size-adjust:100%; }
  html,body{min-height:100%}
  body{
    margin:0;
    font-family:'Baloo 2','Outfit','Inter',system-ui,-apple-system,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
    background:#0E0F26 !important;
    position:relative;
    color:#EAF6FF !important;
    min-height:100vh;
    min-height:100dvh;
    -webkit-font-smoothing:antialiased;
    -webkit-tap-highlight-color:transparent;
  }
  
  /* Animated arcade background */
  body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: 
      radial-gradient(circle at 20% 80%, rgba(0,229,255,0.08) 0%, transparent 50%),
      radial-gradient(circle at 80% 20%, rgba(255,61,174,0.08) 0%, transparent 50%),
      radial-gradient(circle at 40% 40%, rgba(182,255,61,0.06) 0%, transparent 50%);
    animation: floatBg 20s ease-in-out infinite;
    pointer-events: none;
    z-index: 0;
  }
  
  @keyframes floatBg {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -30px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
  }

  /* ---------- EduPlay Arcade Theme Tokens ---------- */
  .tq{
    --cyan:#00E5FF;
    --lime:#B6FF3D;
    --pink:#FF3DAE;
    --gold:#FFD93D;
    --purple:#A78BFA;
    --mint:#2ddc82;
    --rose:#ff4d6d;
    --ink:#EAF6FF;
    --ink-dim:rgba(234,246,255,.65);
    --surface:rgba(255,255,255,.08);
    --line:rgba(255,255,255,.15);
    --map-h:clamp(450px,78dvh,900px);
    --glow-cyan:0 0 20px rgba(0,229,255,0.4);
    --glow-lime:0 0 20px rgba(182,255,61,0.4);
    --glow-pink:0 0 20px rgba(255,61,174,0.4);
  }
  .tq .hidden{display:none !important}
  .tq :focus-visible{outline:2px solid var(--cyan);outline-offset:2px;border-radius:10px}

  .wrap{
    max-width:1650px;margin:0 auto;position:relative;z-index:1;
    padding:
      max(14px,env(safe-area-inset-top))
      max(16px,env(safe-area-inset-right))
      max(28px,env(safe-area-inset-bottom))
      max(16px,env(safe-area-inset-left));
  }

  /* ---------- Header with Arcade Vibes ---------- */
  .tq-head{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:16px 20px;
    flex-wrap:nowrap;
    margin-bottom:18px;
  }
  .tq-title{display:flex;align-items:center;gap:12px;min-width:0;flex:1}
  
  /* Desktop: Group stats and nav on the right */
  .tq-head-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
  }
  .tq-exit{
    display:inline-flex;align-items:center;gap:6px;flex:none;
    min-height:44px;padding:0 14px 0 10px;border-radius:12px;
    background:var(--surface);border:1px solid var(--line);
    color:var(--ink);text-decoration:none;font-size:.9rem;font-weight:600;
    transition:all .2s;
  }
  .tq-exit:hover{background:rgba(0,229,255,.15);color:var(--cyan);border-color:var(--cyan);box-shadow:var(--glow-cyan)}
  .tq-title h1{
    font-family:'Bungee',sans-serif;
    font-size:1.4rem;margin:0;font-weight:700;line-height:1.2;
    display:flex;flex-direction:column;gap:2px;min-width:0;
    background:linear-gradient(135deg,var(--cyan) 0%,var(--lime) 100%);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    text-shadow:var(--glow-cyan);
    filter:drop-shadow(0 0 10px rgba(0,229,255,0.3));
  }
  .tq-title h1 .sub{
    font-family:'Baloo 2',sans-serif;
    font-size:.78rem;font-weight:600;
    color:var(--ink-dim);
    background:none;
    -webkit-background-clip:unset;
    -webkit-text-fill-color:unset;
    filter:none;
  }

  .stats{
    display:grid;grid-template-columns:repeat(2,minmax(80px,auto));gap:10px;
  }
  
  /* Hide mobile-only stats on desktop */
  .stat.time-mobile, .stat.score-mobile, .stat.streak-mobile, .stat.round-mobile {
    display: none;
  }
  .stat{
    background:linear-gradient(135deg,rgba(0,229,255,.08),rgba(182,255,61,.08));
    border:1.5px solid var(--cyan);
    border-radius:12px;padding:8px 12px;text-align:center;
    backdrop-filter:blur(8px);
    position:relative;
    overflow:hidden;
    transition:all .25s;
  }
  .stat::before {
    content:'';
    position:absolute;
    top:0;left:0;right:0;
    height:2px;
    background:linear-gradient(90deg,var(--cyan),var(--lime),var(--pink));
    opacity:0;
    transition:opacity .25s;
  }
  .stat:hover::before {
    opacity:1;
  }
  .stat .label{display:block;font-size:.7rem;color:var(--ink-dim);margin-bottom:2px;font-weight:600}
  .stat .val{font-weight:800;font-size:1.1rem;font-variant-numeric:tabular-nums;display:inline-block;font-family:'Space Mono',monospace}
  .stat.streak .val{color:var(--gold);text-shadow:0 0 8px rgba(255,217,61,.5)}
  .stat.level .val{color:var(--cyan);text-shadow:0 0 8px rgba(0,229,255,.5)}
  .stat.coins {
    border-color:var(--gold);
    background:linear-gradient(135deg,rgba(255,217,61,.12),rgba(255,217,61,.05));
  }
  .stat.coins .val{color:var(--gold);text-shadow:0 0 8px rgba(255,217,61,.5)}

  /* ---------- Nav buttons with arcade glow ---------- */
  .tq .eduplay-game-nav{
    display:flex;align-items:center;gap:8px;flex-wrap:wrap;
  }
  
  /* Burger menu button */
  .burger-menu-btn {
    display: none;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    border: 2px solid var(--cyan);
    background: linear-gradient(135deg,rgba(0,229,255,.15),rgba(0,229,255,.08));
    color: var(--cyan);
    font-size: 1.5rem;
    cursor: pointer;
    transition: all .2s;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1002;
  }
  .burger-menu-btn:hover {
    background: rgba(0,229,255,.25);
    box-shadow: var(--glow-cyan);
    transform: scale(1.05);
  }
  .burger-menu-btn.active {
    background: transparent;
    border-color: transparent;
    color: transparent;
    pointer-events: none;
  }
  
  /* Mobile menu overlay */
  .mobile-menu-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.85);
    backdrop-filter: blur(8px);
    z-index: 1000;
    opacity: 0;
    transition: opacity .3s;
  }
  .mobile-menu-overlay.active {
    display: block;
    opacity: 1;
  }
  
  /* Mobile menu panel */
  .mobile-menu-panel {
    position: fixed;
    top: 0;
    right: 0;
    width: min(320px, 85vw);
    height: 100%;
    background: linear-gradient(135deg, rgba(14,15,38,.98), rgba(10,12,30,.98));
    border-left: 2px solid var(--cyan);
    box-shadow: -10px 0 40px rgba(0,0,0,.8), 0 0 30px rgba(0,229,255,.2);
    transform: translateX(100%);
    transition: transform .3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1001;
    overflow-y: auto;
    padding: 24px 20px;
  }
  .mobile-menu-overlay.active .mobile-menu-panel {
    transform: translateX(0);
  }
  
  .mobile-menu-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid rgba(0,229,255,.3);
  }
  .mobile-menu-title {
    font-family: 'Bungee', sans-serif;
    font-size: 1.1rem;
    color: var(--cyan);
    text-shadow: 0 0 10px rgba(0,229,255,.5);
  }
  .mobile-menu-close {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    border: 2px solid var(--pink);
    background: rgba(255,61,174,.15);
    color: var(--pink);
    font-size: 1.6rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .2s;
    flex-shrink: 0;
  }
  .mobile-menu-close:hover {
    background: rgba(255,61,174,.3);
    box-shadow: 0 0 15px rgba(255,61,174,.4);
    transform: scale(1.05);
  }
  
  .mobile-menu-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .mobile-menu-items .eduplay-nav-btn {
    width: 100%;
    min-height: 56px;
    font-size: 1.05rem;
    font-weight: 700;
    justify-content: flex-start;
    gap: 14px;
    padding: 0 22px;
  }
  .mobile-menu-items .eduplay-nav-btn i {
    font-size: 1.4rem;
  }
  .mobile-menu-items .eduplay-nav-btn span {
    flex: 1;
    text-align: left;
  }
  .tq .eduplay-nav-btn{
    display:inline-flex;align-items:center;justify-content:center;gap:6px;
    min-height:40px;padding:0 14px;border-radius:10px;
    border:1.5px solid var(--line);background:var(--surface);
    color:var(--ink);text-decoration:none;
    font-family:inherit;font-size:.85rem;font-weight:700;
    cursor:pointer;transition:all .2s;
    white-space:nowrap;touch-action:manipulation;
    position:relative;
    overflow:hidden;
  }
  .tq .eduplay-nav-btn::before {
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(135deg,rgba(255,255,255,.1),transparent);
    opacity:0;
    transition:opacity .2s;
  }
  .tq .eduplay-nav-btn:hover::before {opacity:1}
  .tq .eduplay-nav-btn i{font-size:1rem;line-height:1}
  .tq .eduplay-nav-btn:hover{transform:translateY(-2px)}
  .tq .eduplay-nav-btn.eduplay-btn-help{
    background:linear-gradient(135deg,rgba(167,139,250,.15),rgba(167,139,250,.08));
    border-color:var(--purple);color:var(--purple);
  }
  .tq .eduplay-nav-btn.eduplay-btn-help:hover{background:rgba(167,139,250,.25);color:#fff;box-shadow:0 0 15px rgba(167,139,250,.4)}
  .tq .eduplay-nav-btn.eduplay-btn-pause{
    background:linear-gradient(135deg,rgba(255,217,61,.15),rgba(255,217,61,.08));
    border-color:var(--gold);color:var(--gold);
  }
  .tq .eduplay-nav-btn.eduplay-btn-pause:hover{background:rgba(255,217,61,.25);color:#fff;box-shadow:0 0 15px rgba(255,217,61,.4)}
  .tq .eduplay-nav-btn.eduplay-btn-map{
    background:linear-gradient(135deg,rgba(182,255,61,.15),rgba(182,255,61,.08));
    border-color:var(--lime);color:var(--lime);
  }
  .tq .eduplay-nav-btn.eduplay-btn-map:hover{background:rgba(182,255,61,.25);color:#fff;box-shadow:0 0 15px rgba(182,255,61,.4)}
  .tq .eduplay-nav-btn.eduplay-btn-exit{
    background:linear-gradient(135deg,rgba(255,61,174,.15),rgba(255,61,174,.08));
    border-color:var(--pink);color:var(--pink);
  }
  .tq .eduplay-nav-btn.eduplay-btn-exit:hover{background:rgba(255,61,174,.25);color:#fff;box-shadow:0 0 15px rgba(255,61,174,.4)}

  /* ---------- Layout ---------- */
  main{display:grid;grid-template-columns:minmax(0,1.6fr) minmax(340px,1fr);gap:24px;align-items:stretch}

  /* ---------- Map with neon glow ---------- */
  .map-panel{
    position:relative;isolation:isolate;z-index:0;
    min-height:var(--map-h);
    border-radius:16px;overflow:hidden;
    border:2px solid var(--cyan);
    box-shadow:0 18px 50px -20px rgba(0,0,0,.9),var(--glow-cyan),inset 0 1px 0 rgba(0,229,255,.2);
    background:#061626;
  }
  #map{position:absolute;inset:0;background:#061626}

  .route-chip{
    position:absolute;left:54px;top:10px;z-index:500;max-width:calc(100% - 130px);
    display:flex;align-items:center;gap:8px;
    font-size:.8rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
    background:rgba(5,12,24,.86);border:1px solid var(--line);border-radius:999px;
    padding:6px 12px;backdrop-filter:blur(4px);pointer-events:none;
  }
  .route-chip:empty{display:none}
  .route-chip .diff{color:var(--cyan);font-weight:800;font-variant-numeric:tabular-nums}
  .route-chip .sep{color:var(--ink-dim);font-weight:500}

  .recenter{
    position:absolute;right:10px;top:10px;z-index:500;
    width:44px;height:44px;border-radius:12px;cursor:pointer;
    display:grid;place-items:center;
    background:rgba(5,12,24,.86);border:1px solid var(--line);color:var(--ink);
    backdrop-filter:blur(4px);transition:background .15s;
  }
  .recenter:hover{background:rgba(30,60,100,.9)}

  .map-legend{
    position:absolute;left:10px;bottom:10px;z-index:500;display:flex;gap:12px;flex-wrap:wrap;
    font-size:.72rem;background:rgba(5,12,24,.86);
    border:1px solid var(--line);border-radius:8px;padding:5px 10px;
    backdrop-filter:blur(4px);pointer-events:none;
  }
  .map-legend span{display:flex;align-items:center;gap:5px}
  .dot{width:8px;height:8px;border-radius:50%;display:inline-block}

  /* ---------- Quiz panel with arcade styling ---------- */
  .quiz-panel{
    background:linear-gradient(135deg,rgba(0,229,255,.08) 0%,rgba(182,255,61,.05) 100%);
    border:2px solid var(--cyan);border-radius:20px;padding:28px 32px;
    box-shadow:0 18px 50px -24px rgba(0,0,0,.9),var(--glow-cyan);
    display:flex;flex-direction:column;
    position:relative;
    overflow:hidden;
  }
  .quiz-panel::before {
    content:'';
    position:absolute;
    top:0;left:0;right:0;
    height:3px;
    background:linear-gradient(90deg,var(--cyan) 0%,var(--lime) 50%,var(--pink) 100%);
  }
  .quiz-top{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:24px}
  .q-label{
    font-family:'Bungee',sans-serif;
    font-size:1rem;font-weight:700;
    color:var(--cyan);
    white-space:nowrap;
    text-shadow:0 0 10px rgba(0,229,255,.5);
  }
  .pips{display:grid;grid-auto-flow:column;grid-auto-columns:1fr;gap:6px;flex:1;max-width:240px}
  
  /* Quiz panel stats */
  .quiz-stats {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(0,229,255,.2);
  }
  .quiz-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: 10px 16px;
    background: linear-gradient(135deg,rgba(0,229,255,.08),rgba(182,255,61,.05));
    border: 1.5px solid var(--cyan);
    border-radius: 12px;
    min-width: 90px;
    position: relative;
    transition: all .25s;
  }
  .quiz-stat::before {
    content:'';
    position:absolute;
    top:0;left:0;right:0;
    height:2px;
    background:linear-gradient(90deg,var(--cyan),var(--lime));
    opacity:0;
    transition:opacity .25s;
  }
  .quiz-stat:hover::before {
    opacity:1;
  }
  .quiz-stat-label {
    font-size: .75rem;
    color: var(--ink-dim);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
  }
  .quiz-stat-val {
    font-family: 'Space Mono', monospace;
    font-size: 1.3rem;
    font-weight: 800;
    font-variant-numeric: tabular-nums;
  }
  .quiz-stat.time .quiz-stat-val {
    color: var(--cyan);
    text-shadow: 0 0 8px rgba(0,229,255,.5);
  }
  .quiz-stat.score .quiz-stat-val {
    color: var(--lime);
    text-shadow: 0 0 8px rgba(182,255,61,.5);
  }
  .quiz-stat.streak {
    border-color: var(--gold);
    background: linear-gradient(135deg,rgba(255,217,61,.12),rgba(255,217,61,.05));
  }
  .quiz-stat.streak .quiz-stat-val {
    color: var(--gold);
    text-shadow: 0 0 8px rgba(255,217,61,.5);
  }
  .quiz-stat.round {
    border-color: var(--pink);
    background: linear-gradient(135deg,rgba(255,61,174,.12),rgba(255,61,174,.05));
  }
  .quiz-stat.round .quiz-stat-val {
    color: var(--pink);
    text-shadow: 0 0 8px rgba(255,61,174,.5);
  }
  
  /* Hint button in quiz panel */
  .hint-btn-quiz {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    min-height:40px;
    padding:0 18px;
    border-radius:12px;
    border:2px solid var(--gold);
    background:linear-gradient(135deg,rgba(255,217,61,.18),rgba(255,217,61,.1));
    color:var(--gold);
    font-family:'Baloo 2',sans-serif;
    font-size:.95rem;
    font-weight:800;
    cursor:pointer;
    transition:all .25s;
    white-space:nowrap;
    position:relative;
    overflow:hidden;
  }
  .hint-btn-quiz::before {
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(135deg,rgba(255,217,61,.2),transparent);
    opacity:0;
    transition:opacity .25s;
  }
  .hint-btn-quiz:hover:not(:disabled) {
    background:rgba(255,217,61,.28);
    transform:translateY(-2px);
    box-shadow:0 0 20px rgba(255,217,61,.5);
    color:#fff;
  }
  .hint-btn-quiz:hover:not(:disabled)::before {
    opacity:1;
  }
  .hint-btn-quiz:disabled {
    opacity:.35;
    cursor:not-allowed;
    transform:none;
  }
  .hint-btn-quiz:disabled:hover {
    background:linear-gradient(135deg,rgba(255,217,61,.18),rgba(255,217,61,.1));
    box-shadow:none;
    color:var(--gold);
  }
  .hint-btn-quiz i {
    font-size:1.15rem;
  }
  .hint-cost {
    display:inline-flex;
    align-items:center;
    gap:4px;
    font-size:.85rem;
    opacity:.85;
  }
  .pip{
    height:8px;border-radius:4px;
    background:rgba(255,255,255,.14);
    transition:all .3s;
    position:relative;
  }
  .pip.now{
    background:var(--cyan);
    box-shadow:0 0 10px rgba(0,229,255,.6);
    animation:pipPulse 1.5s ease-in-out infinite;
  }
  .pip.ok{
    background:var(--lime);
    box-shadow:0 0 8px rgba(182,255,61,.5);
  }
  .pip.no{
    background:var(--pink);
    box-shadow:0 0 8px rgba(255,61,174,.5);
  }
  @keyframes pipPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
  }

  .quiz-mid {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: safe center;
  }

  /* clocks with arcade glow */
  .clocks{display:grid;grid-template-columns:minmax(0,1fr) auto minmax(0,1fr);gap:16px;align-items:center;margin-bottom:20px}
  .c-arrow{color:var(--gold);display:grid;place-items:center;filter:drop-shadow(0 0 8px rgba(255,217,61,.5))}
  .c-arrow svg {width: 28px; height: 28px;}
  .clock{
    border-radius:16px;padding:20px 12px 18px;text-align:center;
    background:linear-gradient(135deg,rgba(0,0,0,.35),rgba(0,0,0,.2));
    border:2px solid var(--line);
    border-top:4px solid var(--accent,var(--line));
    position:relative;
    transition:all .3s;
  }
  .clock::before {
    content:'';
    position:absolute;
    top:0;left:0;right:0;bottom:0;
    border-radius:16px;
    background:radial-gradient(circle at top,var(--accent,transparent),transparent 70%);
    opacity:.08;
    pointer-events:none;
  }
  .clock.manila{--accent:var(--gold)}
  .clock.target{--accent:var(--pink)}
  .c-city{
    font-family:'Bungee',sans-serif;
    font-weight:700;font-size:1.15rem;line-height:1.3;overflow-wrap:anywhere;
    text-shadow:0 0 8px rgba(255,255,255,.3);
  }
  .c-off{font-size:.95rem;color:var(--ink-dim);margin-top:2px;font-family:'Space Mono',monospace}
  .c-time{
    font-family:'Space Mono',monospace;
    font-size:clamp(2rem,6vw,2.8rem);font-weight:800;line-height:1.15;
    font-variant-numeric:tabular-nums;margin-top:12px;white-space:nowrap;
  }
  .c-day{font-size:1rem;color:var(--ink-dim);min-height:1.3em;margin-top:4px;font-weight:600}
  .clock.ask{
    box-shadow:inset 0 0 0 2px var(--cyan),0 0 20px rgba(0,229,255,.3);
    animation:clockPulse 2s ease-in-out infinite;
  }
  .clock.ask .c-time{color:var(--cyan);text-shadow:0 0 15px rgba(0,229,255,.6)}
  .clock.ask.revealed{
    box-shadow:inset 0 0 0 2px var(--lime),0 0 25px rgba(182,255,61,.5);
    animation:reveal .4s ease-out;
  }
  .clock.ask.revealed .c-time{color:var(--lime);text-shadow:0 0 15px rgba(182,255,61,.7)}
  @keyframes clockPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
  }
  @keyframes reveal{0%{transform:scale(.96)}60%{transform:scale(1.04)}100%{transform:scale(1)}}

  .q-ask{
    margin:0 0 24px;
    font-size:1.3rem;
    line-height:1.5;
    text-align:center;
    font-weight:600;
  }
  .q-ask b{
    color:var(--cyan);
    font-weight:800;
    text-shadow:0 0 10px rgba(0,229,255,.5);
  }

  /* options with arcade styling */
  #options{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}
  @keyframes slideIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
  .option{
    position:relative;font:inherit;min-height:90px;padding:18px 24px;
    border-radius:16px;cursor:pointer;text-align:left;color:var(--ink);
    background:linear-gradient(135deg,rgba(0,229,255,.08),rgba(182,255,61,.05));
    border:2px solid rgba(0,229,255,.25);
    display:flex;flex-direction:column;justify-content:center;gap:6px;min-width:0;
    transition:all .25s cubic-bezier(0.4,0,0.2,1);
    animation:slideIn .4s cubic-bezier(0.4,0,0.2,1) backwards;
    touch-action:manipulation;
    overflow:hidden;
  }
  .option::before {
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(135deg,rgba(0,229,255,.15),transparent);
    opacity:0;
    transition:opacity .25s;
  }
  .option:nth-child(1){animation-delay:.05s}
  .option:nth-child(2){animation-delay:.1s}
  .option:nth-child(3){animation-delay:.15s}
  .option:nth-child(4){animation-delay:.2s}
  .o-time{
    font-family:'Space Mono',monospace;
    font-size:1.5rem;font-weight:800;
    font-variant-numeric:tabular-nums;line-height:1.25;
    text-shadow:0 0 8px rgba(255,255,255,.2);
  }
  .o-day{font-size:1rem;color:var(--ink-dim);font-weight:600}
  .option .key{
    position:absolute;top:12px;right:14px;
    font-size:.85rem;color:var(--cyan);
    border:1.5px solid var(--cyan);
    border-radius:8px;padding:4px 10px;font-weight:800;line-height:1.5;
    font-family:'Space Mono',monospace;
    background:rgba(0,229,255,.08);
  }
  @media (hover:hover){
    .option:hover:not(:disabled){
      background:linear-gradient(135deg,rgba(0,229,255,.18),rgba(182,255,61,.12));
      border-color:var(--cyan);
      transform:translateY(-3px);
      box-shadow:0 8px 20px rgba(0,229,255,.25),var(--glow-cyan);
    }
    .option:hover:not(:disabled)::before{opacity:1}
  }
  .option:active:not(:disabled){transform:scale(.97);background:rgba(0,229,255,.2)}
  .option:disabled{cursor:default}
  .option:disabled:not(.correct):not(.wrong){opacity:.4}
  .option.correct{
    background:linear-gradient(135deg,rgba(182,255,61,.25),rgba(182,255,61,.15));
    border-color:var(--lime);color:var(--lime);
    box-shadow:0 0 25px rgba(182,255,61,.4),inset 0 0 20px rgba(182,255,61,.15);
    animation:correctPulse .5s ease-out;
  }
  .option.wrong{
    background:linear-gradient(135deg,rgba(255,61,174,.25),rgba(255,61,174,.15));
    border-color:var(--pink);color:var(--pink);
    box-shadow:0 0 25px rgba(255,61,174,.4);
    animation:shake .4s ease-out;
  }
  @keyframes correctPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
  }
  @keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-8px); }
    75% { transform: translateX(8px); }
  }
  .option.correct .o-day,.option.wrong .o-day{color:inherit;opacity:.8}
  .option.correct .key,.option.wrong .key{display:none}
  .option.correct::after,.option.wrong::after{
    position:absolute;top:10px;right:14px;font-weight:800;font-size:1.4rem;
  }
  .option.correct::after{content:"✓";color:var(--lime);filter:drop-shadow(0 0 8px rgba(182,255,61,.8))}
  .option.wrong::after{content:"✗";color:var(--pink);filter:drop-shadow(0 0 8px rgba(255,61,174,.8))}

  .pop-anim{animation:popUp .4s ease-out}
  @keyframes popUp{0%{transform:scale(1)}50%{transform:scale(1.3);color:var(--cyan)}100%{transform:scale(1)}}

  /* feedback */
  #feedback{margin-top:14px;font-size:.92rem;line-height:1.55;display:none;
    padding:12px 14px;border-radius:12px;background:rgba(0,0,0,.2);border:1px solid var(--line)}
  #feedback.show{display:block;animation:fade .25s ease}
  #feedback.good{border-color:rgba(45,220,130,.4)}
  #feedback.bad{border-color:rgba(255,80,110,.4)}
  @keyframes fade{from{opacity:0;transform:translateY(-4px)}to{opacity:1;transform:none}}
  #feedback .verdict{font-weight:800;font-size:1rem;margin-bottom:6px;display:block}
  #feedback .verdict.good{color:var(--mint)}
  #feedback .verdict.bad{color:var(--rose)}
  #feedback .expl{color:rgba(232,238,252,.88)}
  #feedback .expl code{
    background:rgba(255,255,255,.09);border-radius:4px;padding:1px 6px;
    font-family:ui-monospace,SFMono-Regular,Menlo,monospace;font-size:.84em;color:var(--mint-soft);
  }
  #feedback .calc{
    margin-top:8px;padding-top:8px;border-top:1px dashed rgba(255,255,255,.16);
    font-variant-numeric:tabular-nums;
  }
  #feedback .calc small{display:block;color:var(--ink-dim);font-size:.78rem;margin-bottom:2px}
  #feedback .calc b{color:var(--mint-soft)}

  .btn{
    display:inline-flex;align-items:center;justify-content:center;text-decoration:none;
    width:100%;min-height:52px;padding:0 16px;border-radius:12px;border:0;cursor:pointer;
    font:inherit;font-weight:800;font-size:1rem;letter-spacing:.02em;
    background:linear-gradient(135deg,var(--cyan),var(--lime));
    color:#0E0F26;
    box-shadow:0 8px 24px -8px rgba(0,229,255,.6),0 0 20px rgba(0,229,255,.3);
    transition:all .2s;
    touch-action:manipulation;
    position:relative;
    overflow:hidden;
  }
  .btn::before {
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(135deg,rgba(255,255,255,.2),transparent);
    opacity:0;
    transition:opacity .2s;
  }
  .btn:hover{
    filter:brightness(1.15);
    transform:translateY(-2px);
    box-shadow:0 12px 32px -8px rgba(0,229,255,.7),0 0 25px rgba(0,229,255,.4);
  }
  .btn:hover::before{opacity:1}
  .btn:active{transform:scale(.98)}
  .btn.ghost{
    background:transparent;
    box-shadow:none;
    color:var(--ink-dim);
    border:2px solid var(--line);
  }
  .btn.ghost:hover{
    color:var(--ink);
    background:rgba(255,255,255,.08);
    border-color:var(--cyan);
    box-shadow:var(--glow-cyan);
  }
  .btn.go{
    background:linear-gradient(135deg,var(--lime),var(--cyan));
    box-shadow:0 8px 24px -8px rgba(182,255,61,.6),0 0 20px rgba(182,255,61,.3);
  }
  .btn.go:hover{
    box-shadow:0 12px 32px -8px rgba(182,255,61,.7),0 0 25px rgba(182,255,61,.4);
  }

  #next{margin-top:20px;display:none}
  #next.show{display:inline-flex;animation:fade .25s ease}

  .hint{margin-top: auto; padding-top: 24px; font-size:.95rem;color:var(--ink-dim);text-align:center;display:none; letter-spacing: 0.5px;}
  @media (hover:hover) and (pointer:fine){ .hint{display:block} }
  @media (hover:none){ .option .key{display:none} }

  /* ---------- Overlays (start / tutorial / results) ---------- */
  .overlay{
    position:fixed;inset:0;z-index:99999;isolation:isolate;
    display:flex;padding:16px;overflow-y:auto;
    background:rgba(3,8,18,.9);
    -webkit-backdrop-filter:blur(9px);backdrop-filter:blur(9px);
  }
  #tutorialOverlay{z-index:100001}
  .card{
    margin:auto;max-width:460px;width:100%;text-align:center;position:relative;
    background:linear-gradient(180deg,rgba(20,35,60,.98),rgba(10,20,38,.98));
    border:1px solid rgba(120,180,255,.2);border-radius:20px;padding:30px 24px;
    box-shadow:0 30px 80px -20px rgba(0,0,0,.95),inset 0 1px 0 rgba(255,255,255,.1);
    color:var(--ink);
  }
  .card .big{font-size:3.2rem;margin-bottom:6px;line-height:1}
  .card h2{margin:0 0 10px;font-size:1.5rem;line-height:1.25}
  .card p{color:rgba(232,238,252,.78);line-height:1.6;margin:0 0 20px;font-size:.95rem}
  .card .final-score{font-size:2.6rem;font-weight:900;color:var(--cyan);font-variant-numeric:tabular-nums;line-height:1}
  .card .final-label{font-size:.8rem;color:var(--ink-dim);margin-bottom:18px}
  .card .rowline{display:flex;justify-content:center;gap:26px;margin:18px 0 22px}
  .card .rowline .n{font-size:1.4rem;font-weight:800;font-variant-numeric:tabular-nums}
  .card .rowline .l{font-size:.75rem;color:var(--ink-dim)}
  .card .level-note{
    margin:-6px 0 18px;padding:8px 12px;border-radius:10px;font-size:.88rem;
    background:rgba(95,208,255,.1);border:1px solid rgba(95,208,255,.25);color:#cfe4ff;
  }
  .card .level-note.up{background:rgba(45,220,130,.12);border-color:rgba(45,220,130,.35);color:var(--mint-soft)}
  .card .stack{display:grid;gap:10px}

  /* ---------- Enhanced Spacious Instruction & Tutorial System ---------- */
  .tq-instr-card, .tut-card {
    max-width: 900px !important;
    width: 95% !important;
    margin: auto;
    background: linear-gradient(165deg, rgba(13, 24, 48, 0.96) 0%, rgba(5, 12, 24, 0.98) 100%) !important;
    border: 1.5px solid rgba(95, 208, 255, 0.35) !important;
    border-radius: 28px !important;
    padding: 40px 52px 36px !important;
    box-shadow: 0 35px 100px rgba(0, 0, 0, 0.85), 0 0 60px rgba(95, 208, 255, 0.12), inset 0 1px 0 rgba(255, 255, 255, 0.15) !important;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    text-align: center;
  }
  .tq-instr-card::before, .tut-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3.5px;
    background: linear-gradient(90deg, #5fd0ff 0%, #8affc1 35%, #ffd66b 70%, #ff4d6d 100%);
  }

  #resultsOverlay .card {
    max-width: 520px !important;
  }

  .tq-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(95, 208, 255, 0.12);
    border: 1px solid rgba(95, 208, 255, 0.35);
    border-radius: 99px;
    padding: 6px 18px;
    font-size: 0.76rem;
    font-weight: 800;
    color: var(--cyan);
    letter-spacing: 1.5px;
    text-transform: uppercase;
  }

  .tq-instr-title {
    font-family: 'Space Grotesk', sans-serif !important;
    font-size: 2.6rem !important;
    font-weight: 900 !important;
    background: linear-gradient(135deg, #ffffff 0%, #7ee0ff 50%, #ffd66b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 10px 0 8px !important;
    letter-spacing: -0.5px;
  }
  .tq-instr-sub {
    color: rgba(232, 238, 252, 0.82);
    font-size: 1.18rem;
    line-height: 1.6;
    margin: 0 auto 28px;
    max-width: 660px;
  }

  .tq-features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 28px;
    text-align: left;
  }

  .tq-feat-card {
    background: rgba(255, 255, 255, 0.035);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 20px;
    padding: 22px 20px 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: all 0.28s cubic-bezier(0.2, 0.8, 0.2, 1);
    position: relative;
    overflow: hidden;
  }
  .tq-feat-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at top left, var(--card-glow, rgba(95,208,255,0.15)), transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
  }
  .tq-feat-card:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: var(--card-border, rgba(95, 208, 255, 0.35));
    transform: translateY(-3px);
    box-shadow: 0 12px 30px -10px rgba(0, 0, 0, 0.6);
  }
  .tq-feat-card:hover::after { opacity: 1; }
  .tq-feat-card.blue  { --card-glow: rgba(95, 208, 255, 0.15); --card-border: rgba(95, 208, 255, 0.4); }
  .tq-feat-card.gold  { --card-glow: rgba(255, 214, 107, 0.15); --card-border: rgba(255, 214, 107, 0.4); }
  .tq-feat-card.green { --card-glow: rgba(45, 220, 130, 0.15); --card-border: rgba(45, 220, 130, 0.4); }

  .tq-feat-head { display: flex; align-items: center; gap: 12px; }
  .tq-feat-icon {
    width: 44px; height: 44px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
  }
  .tq-feat-card.blue  .tq-feat-icon { background: rgba(95, 208, 255, 0.14); border: 1px solid rgba(95, 208, 255, 0.3); }
  .tq-feat-card.gold  .tq-feat-icon { background: rgba(255, 214, 107, 0.14); border: 1px solid rgba(255, 214, 107, 0.3); }
  .tq-feat-card.green .tq-feat-icon { background: rgba(45, 220, 130, 0.14); border: 1px solid rgba(45, 220, 130, 0.3); }

  .tq-feat-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.05rem; font-weight: 800; color: #ffffff; line-height: 1.25;
  }
  .tq-feat-desc {
    font-size: 0.88rem; color: rgba(232, 238, 252, 0.76); line-height: 1.55; margin: 0;
  }
  .tq-feat-desc b { color: #fff; font-weight: 700; }

  .tq-instr-footer {
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    flex-wrap: wrap;
  }

  .tq-stats-pills { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
  .tq-stat-pill {
    background: rgba(255, 255, 255, 0.045);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 99px; padding: 7px 16px;
    font-size: 1rem; font-weight: 700; color: rgba(232, 238, 252, 0.88);
    display: inline-flex; align-items: center; gap: 6px;
  }
  .tq-stat-pill b { color: var(--cyan); }

  .tq-actions-row {
    display: flex; gap: 12px; align-items: center;
    flex: 1; justify-content: flex-end;
  }

  .tq-tut-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; gap: 12px; }
  .tq-tut-close-btn {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 12px; width: 36px; height: 36px;
    display: flex; align-items: center; justify-content: center;
    color: rgba(232, 238, 252, 0.7); font-size: 1.25rem;
    cursor: pointer; transition: all 0.2s ease;
    touch-action: manipulation;
  }
  .tq-tut-close-btn:hover {
    background: rgba(255, 80, 110, 0.2);
    border-color: rgba(255, 80, 110, 0.4); color: #fff;
  }

  .tq-stepper {
    display: flex; align-items: center; justify-content: center;
    gap: 8px; margin-bottom: 24px;
    background: rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px; padding: 6px; overflow-x: auto;
  }
  .tq-step-tab {
    background: transparent; border: none; border-radius: 10px;
    padding: 8px 14px; font-size: 0.82rem; font-weight: 700;
    color: rgba(232, 238, 252, 0.65); cursor: pointer;
    display: flex; align-items: center; gap: 7px;
    transition: all 0.22s ease; white-space: nowrap;
    text-decoration: none; touch-action: manipulation;
  }
  .tq-step-tab:hover { color: #fff; background: rgba(255, 255, 255, 0.06); }
  .tq-step-tab.active {
    background: linear-gradient(135deg, rgba(95, 208, 255, 0.22), rgba(45, 220, 130, 0.16));
    border: 1px solid rgba(95, 208, 255, 0.45);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(95, 208, 255, 0.2);
  }
  .tq-step-tab .tq-num {
    display: inline-flex; align-items: center; justify-content: center;
    width: 20px; height: 20px; border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    font-size: 0.9rem; font-weight: 800;
  }
  .tq-step-tab.active .tq-num { background: var(--cyan); color: #04121f; }

  .tq-slide-body { min-height: 290px; display: flex; flex-direction: column; justify-content: center; }

  .tq-tut-slide-title {
    font-family: 'Space Grotesk', sans-serif !important;
    font-size: 1.85rem !important; font-weight: 900 !important;
    margin: 0 0 8px !important; letter-spacing: -0.3px;
  }
  .tq-tut-slide-text {
    font-size: 1.05rem; line-height: 1.65;
    color: rgba(232, 238, 252, 0.82);
    max-width: 660px; margin: 0 auto 24px;
  }

  .tq-compare-hero {
    display: flex; align-items: center; justify-content: space-between;
    background: rgba(0, 0, 0, 0.35);
    border: 1.5px solid rgba(255, 255, 255, 0.1);
    border-radius: 22px; padding: 24px 36px;
    margin: 0 auto 22px; max-width: 640px; width: 100%;
  }
  .tq-compare-hero.east {
    border-color: rgba(255, 214, 107, 0.35);
    background: linear-gradient(135deg, rgba(255, 214, 107, 0.08) 0%, rgba(0, 0, 0, 0.4) 100%);
  }
  .tq-compare-hero.west {
    border-color: rgba(255, 143, 163, 0.35);
    background: linear-gradient(135deg, rgba(255, 77, 109, 0.08) 0%, rgba(0, 0, 0, 0.4) 100%);
  }
  .tq-compare-hero.night {
    border-color: rgba(138, 255, 193, 0.35);
    background: linear-gradient(135deg, rgba(45, 220, 130, 0.08) 0%, rgba(0, 0, 0, 0.4) 100%);
  }

  .tq-ch-city { text-align: center; }
  .tq-ch-flag { font-size: 2.5rem; line-height: 1; margin-bottom: 6px; }
  .tq-ch-name { font-size: 0.9rem; font-weight: 700; color: var(--ink-dim); margin-bottom: 4px; }
  .tq-ch-time { font-family: 'Space Grotesk', sans-serif; font-size: 1.7rem; font-weight: 900; color: #fff; }
  .tq-ch-time.gold { color: var(--gold); }
  .tq-ch-time.rose { color: var(--rose-soft); }
  .tq-ch-time.mint { color: var(--mint-soft); }
  .tq-ch-day { font-size: 0.82rem; font-weight: 700; color: var(--cyan); margin-top: 4px; }
  .tq-ch-day.alert { color: var(--mint-soft); font-weight: 800; }

  .tq-ch-mid { display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 0 16px; }
  .tq-ch-badge {
    border-radius: 50px; padding: 6px 18px;
    font-size: 0.88rem; font-weight: 800; letter-spacing: 0.5px; white-space: nowrap;
  }
  .tq-ch-badge.east { background: rgba(255, 214, 107, 0.2); color: var(--gold); border: 1px solid rgba(255, 214, 107, 0.4); }
  .tq-ch-badge.west { background: rgba(255, 77, 109, 0.2); color: var(--rose-soft); border: 1px solid rgba(255, 77, 109, 0.4); }
  .tq-ch-badge.night { background: rgba(45, 220, 130, 0.2); color: var(--mint-soft); border: 1px solid rgba(45, 220, 130, 0.4); }
  .tq-ch-math { font-family: monospace; font-size: 0.9rem; color: rgba(232, 238, 252, 0.65); }

  .tq-flow-card {
    display: flex; align-items: center; justify-content: center;
    gap: 20px; background: rgba(0, 0, 0, 0.35);
    border: 1px solid rgba(95, 208, 255, 0.28);
    border-radius: 22px; padding: 24px 32px;
    margin: 0 auto 22px; max-width: 640px; width: 100%;
  }
  .tq-flow-step { text-align: center; }
  .tq-flow-step.highlight {
    background: rgba(95, 208, 255, 0.12);
    border: 1px solid rgba(95, 208, 255, 0.35);
    border-radius: 16px; padding: 12px 24px;
  }
  .tq-flow-flag { font-size: 2.3rem; display: block; margin-bottom: 5px; }
  .tq-flow-city { font-size: 0.95rem; font-weight: 800; color: #fff; display: block; }
  .tq-flow-offset { font-size: 1rem; font-weight: 700; color: var(--cyan); display: block; font-family: monospace; }
  .tq-flow-arrow { color: var(--gold); font-weight: 800; font-size: 1.1rem; }

  .tq-tips-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 14px; margin: 0 auto 22px; max-width: 680px; width: 100%; text-align: left;
  }
  .tq-tip-box {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 16px; padding: 16px 14px;
    display: flex; flex-direction: column; gap: 8px;
  }
  .tq-tip-ico { font-size: 1.5rem; }
  .tq-tip-tit { font-size: 0.9rem; font-weight: 800; color: #fff; }
  .tq-tip-txt { font-size: 0.82rem; color: rgba(232, 238, 252, 0.75); line-height: 1.5; margin: 0; }

  .tq-takeaway-note {
    background: rgba(95, 208, 255, 0.08);
    border: 1px solid rgba(95, 208, 255, 0.25);
    border-radius: 14px; padding: 12px 20px;
    font-size: 0.92rem; color: rgba(232, 238, 252, 0.9);
    margin: 0 auto 24px; max-width: 640px; width: 100%; text-align: center;
  }

  .tq-tut-footer {
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    margin-top: 8px;
  }
  .tq-tut-footer .btn-row { display: flex; gap: 12px; flex: 1; justify-content: flex-end; }
  .tq-tut-footer .btn-row .btn { min-height: 48px; padding: 0 24px; width: auto; }

  .tq-tut-skip-btn {
    background: none; border: none;
    color: rgba(232, 238, 252, 0.55);
    font-size: 0.88rem; cursor: pointer;
    text-decoration: underline; text-underline-offset: 4px;
    padding: 8px 12px; transition: color 0.18s;
    touch-action: manipulation;
  }
  .tq-tut-skip-btn:hover { color: #fff; }

  /* ---------- Leaflet dark-theme tweaks ---------- */
  .leaflet-container{background:#061626 !important;font-family:inherit}
  .leaflet-control-attribution{background:rgba(4,10,24,.7) !important;color:rgba(255,255,255,.45) !important;font-size:9px !important}
  .leaflet-control-attribution a{color:rgba(120,200,255,.7) !important}
  .leaflet-bar a{background:rgba(10,20,36,.85) !important;color:#cfe4ff !important;border-color:rgba(255,255,255,.1) !important}
  .leaflet-bar a:hover{background:rgba(30,60,100,.9) !important}

  .pulse-marker{position:relative}
  .pulse-marker .core{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);border-radius:50%;border:2px solid #fff}
  .pulse-marker .ring{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);border-radius:50%;animation:pulseRing 1.8s ease-out infinite}
  @keyframes pulseRing{0%{width:10px;height:10px;opacity:.8}100%{width:46px;height:46px;opacity:0}}
  .city-label{
    display:inline-block;background:rgba(4,10,24,.86);border:1px solid rgba(255,255,255,.14);
    border-radius:6px;padding:2px 8px;font-size:16px;font-weight:700;
    white-space:nowrap;color:var(--ink);box-shadow:0 2px 8px rgba(0,0,0,.5);
  }
  .city-label.manila{color:var(--gold)}
  .city-label.target{color:var(--rose-soft)}
  .city-label.left{transform:translateX(-100%)}

  /* ---------- Pause overlay ---------- */
  .pause-overlay {
    display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
    z-index: 9998; align-items: center; justify-content: center; flex-direction: column;
    padding: 16px;
  }
  .pause-overlay.active { display: flex; }
  .pause-card {
    background: linear-gradient(135deg, rgba(10, 25, 50, 0.97) 0%, rgba(5, 15, 30, 0.97) 100%);
    border: 2px solid rgba(95, 208, 255, 0.4); border-radius: 24px;
    padding: 36px 32px; text-align: center; max-width: 420px; width: 100%;
    box-shadow: 0 20px 60px rgba(0,0,0,0.8), 0 0 30px rgba(95, 208, 255, 0.2); color: #fff;
  }
  .pause-card h2 { color: var(--cyan); font-size: 1.8rem; font-weight: 800; margin: 0 0 8px; }
  .pause-card p { color: var(--ink-dim); margin: 0 0 20px; font-size: 0.95rem; }

  /* ============================================================
     MOBILE ADAPTIVE LAYER
     ============================================================ */
  @media (max-width: 940px) {
    /* Header stacks vertically on mobile */
    .tq-head {
      flex-direction: column;
      align-items: stretch;
      gap: 14px;
      margin-bottom: 16px;
      padding-right: 70px; /* Make room for burger button */
    }
    .tq-title { width: 100%; margin-bottom: 4px; }
    .tq-title h1 { font-size: 1.2rem; }
    .tq-title h1 .sub { font-size: .75rem; margin-top: 2px; }
    
    /* Right container becomes full-width and left-aligned on mobile */
    .tq-head-right {
      width: 100%;
      align-items: stretch;
      gap: 10px;
    }

    /* Stats: show all 6 stats on mobile in 3x2 grid */
    .stats {
      width: 100%;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 8px;
    }
    .stat { padding: 10px 12px; border-radius: 12px; }
    .stat .label { font-size: .68rem; font-weight: 700; }
    .stat .val { font-size: 1rem; }
    
    /* Show mobile-only stats in header */
    .stat.time-mobile, .stat.score-mobile, .stat.streak-mobile {
      display: flex;
    }
    .stat.time-mobile .val { color: var(--cyan); text-shadow: 0 0 8px rgba(0,229,255,.5); }
    .stat.score-mobile .val { color: var(--lime); text-shadow: 0 0 8px rgba(182,255,61,.5); }
    .stat.streak-mobile .val { color: var(--gold); text-shadow: 0 0 8px rgba(255,217,61,.5); }
    
    /* Add Round stat to mobile header */
    .stat.round-mobile {
      display: flex;
      border-color: var(--pink);
      background: linear-gradient(135deg,rgba(255,61,174,.12),rgba(255,61,174,.05));
    }
    .stat.round-mobile .val {
      color: var(--pink);
      text-shadow: 0 0 8px rgba(255,61,174,.5);
    }
    
    /* Hide quiz panel stats on mobile */
    .quiz-stats {
      display: none;
    }

    /* Nav: hide desktop buttons, show burger */
    .tq .eduplay-game-nav {
      display: none;
    }
    .burger-menu-btn {
      display: flex;
    }

    /* Map */
    .tq { --map-h: clamp(180px, 28dvh, 280px); }
    .hide-sm { display: none !important; }
    .leaflet-control-zoom { display: none !important; }
    .route-chip {
      left: 10px; top: 8px;
      max-width: calc(100% - 70px);
      font-size: .74rem;
      padding: 5px 10px;
    }
    .recenter { width: 38px; height: 38px; top: 8px; right: 8px; border-radius: 10px; }
    .recenter svg { width: 16px; height: 16px; }
    .map-legend { left: 8px; bottom: 8px; font-size: .66rem; padding: 4px 8px; gap: 8px; }
    .dot { width: 7px; height: 7px; }

    /* Quiz panel */
    .quiz-panel { padding: 16px 16px 20px; border-radius: 16px; }
    .quiz-stats {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 8px;
      margin-bottom: 16px;
      padding-bottom: 12px;
    }
    .quiz-stat {
      min-width: 0;
      padding: 8px 12px;
    }
    .quiz-stat-label { font-size: .7rem; }
    .quiz-stat-val { font-size: 1.1rem; }
    .quiz-top { margin-bottom: 14px; flex-wrap: wrap; }
    .hint-btn-quiz { 
      order: 3; 
      width: 100%; 
      margin-top: 8px;
      min-height: 44px;
    }
    .q-label { order: 1; }
    .pips { order: 2; max-width: 150px; }
    .q-ask { font-size: 1.08rem; margin-bottom: 16px; }

    /* Sticky Next keeps the primary action reachable */
    #next {
      position: sticky;
      bottom: calc(10px + env(safe-area-inset-bottom, 0px));
      z-index: 5;
      box-shadow: 0 10px 30px -6px rgba(0,0,0,.8), 0 8px 24px -8px rgba(60,180,255,.7);
    }

    /* Layout */
    main { grid-template-columns: 1fr; gap: 12px; }

    /* Overlays: full-bleed with scroll */
    .overlay { padding: 10px; }
    .tq-instr-card, .tut-card {
      width: 100% !important;
      max-width: 100% !important;
      padding: 28px 22px 24px !important;
      border-radius: 20px !important;
    }
    #resultsOverlay .card { max-width: 100% !important; }
    .tq-instr-title { font-size: 2rem !important; }
    .tq-instr-sub { font-size: 1.02rem; margin-bottom: 22px; }
    .tq-features-grid { gap: 12px; margin-bottom: 20px; }
    .tq-instr-footer { gap: 12px; }
    .tq-actions-row .btn { min-height: 48px; }

    /* Tutorial stepper becomes horizontally scrollable */
    .tq-stepper {
      justify-content: flex-start;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
    }
    .tq-stepper::-webkit-scrollbar { display: none; }

    /* Results + pause */
    .card .final-score { font-size: 2.2rem; }
    .pause-card { padding: 26px 22px; border-radius: 20px; }
    .pause-card h2 { font-size: 1.5rem; }
  }

  @media (max-width: 640px) {
    .wrap {
      padding:
        max(10px,env(safe-area-inset-top))
        max(10px,env(safe-area-inset-right))
        max(22px,env(safe-area-inset-bottom))
        max(10px,env(safe-area-inset-left));
    }
    .stats {
      gap: 6px;
    }
    .stat { padding: 7px 10px; }
    .stat .label { font-size: .68rem; }
    .stat .val { font-size: .95rem; }
    .quiz-panel { padding: 12px 12px 16px; border-radius: 14px; }
    .quiz-stats {
      grid-template-columns: repeat(2, 1fr);
      gap: 6px;
      margin-bottom: 12px;
      padding-bottom: 10px;
    }
    .quiz-stat {
      padding: 6px 8px;
      min-width: 0;
    }
    .quiz-stat-label { font-size: .65rem; }
    .quiz-stat-val { font-size: 1rem; }
    .quiz-top { gap: 8px; margin-bottom: 10px; }
    .q-label { font-size: .82rem; }
    .pips { max-width: 150px; gap: 3px; }
    .pip { height: 6px; border-radius: 3px; }

    /* Clocks: tighter */
    .clocks { gap: 6px; margin-bottom: 10px; }
    .clock { padding: 12px 6px 10px; border-radius: 12px; border-top-width: 4px; }
    .c-city { font-size: .92rem; }
    .c-off { font-size: .76rem; }
    .c-time { font-size: clamp(1.45rem, 7vw, 2rem); margin-top: 6px; }
    .c-day { font-size: .82rem; }
    .c-arrow svg { width: 18px; height: 18px; }

    .q-ask { font-size: 1rem; margin-bottom: 12px; line-height: 1.4; }

    /* Options */
    #options { gap: 8px; }
    .option { min-height: 66px; padding: 10px 14px; border-radius: 12px; gap: 4px; }
    .o-time { font-size: 1.1rem; }
    .o-day { font-size: .82rem; }

    /* Feedback */
    #feedback { font-size: .86rem; padding: 10px 12px; margin-top: 10px; }
    #feedback .verdict { font-size: .94rem; margin-bottom: 4px; }
    #feedback .calc { margin-top: 6px; padding-top: 6px; }
    #feedback .calc small { font-size: .72rem; }
    #next { min-height: 48px; font-size: .94rem; margin-top: 12px; }

    /* Instruction card */
    .tq-instr-title { font-size: 1.7rem !important; margin: 8px 0 6px !important; }
    .tq-instr-sub { font-size: .94rem; margin-bottom: 18px; line-height: 1.5; }
    .tq-badge { font-size: .7rem; padding: 5px 14px; }

    .tq-features-grid { grid-template-columns: 1fr; gap: 8px; margin-bottom: 16px; }
    .tq-feat-card { padding: 14px 12px; border-radius: 14px; }
    .tq-feat-head { gap: 10px; }
    .tq-feat-icon { width: 38px; height: 38px; font-size: 1.15rem; border-radius: 11px; }
    .tq-feat-title { font-size: .95rem; }
    .tq-feat-desc { font-size: .82rem; line-height: 1.5; }

    .tq-instr-footer { flex-direction: column; align-items: stretch; gap: 12px; padding-top: 16px; }
    .tq-stats-pills { justify-content: center; gap: 6px; }
    .tq-stat-pill { font-size: .82rem; padding: 5px 12px; }
    .tq-actions-row { flex-direction: column; width: 100%; gap: 8px; }
    .tq-actions-row .btn { width: 100%; min-height: 46px !important; font-size: .92rem !important; padding: 0 16px !important; }

    /* Tutorial */
    .tq-tut-top { margin-bottom: 14px; }
    .tq-stepper { padding: 5px; margin-bottom: 16px; border-radius: 12px; }
    .tq-step-tab { padding: 6px 10px; font-size: .72rem; border-radius: 8px; }
    .tq-step-tab .tq-num { width: 18px; height: 18px; font-size: .8rem; }

    .tq-slide-body { min-height: auto; }
    .tq-tut-slide-title { font-size: 1.3rem !important; margin-bottom: 6px !important; }
    .tq-tut-slide-text { font-size: .92rem; line-height: 1.55; margin-bottom: 16px; }

    .tq-compare-hero { padding: 12px 10px; gap: 8px; margin-bottom: 16px; border-radius: 16px; }
    .tq-ch-flag { font-size: 1.8rem; margin-bottom: 4px; }
    .tq-ch-name { font-size: .72rem; }
    .tq-ch-time { font-size: 1.15rem; }
    .tq-ch-day { font-size: .72rem; }
    .tq-ch-badge { padding: 4px 12px; font-size: .72rem; }
    .tq-ch-math { font-size: .72rem; }
    .tq-ch-mid { padding: 0 6px; gap: 5px; }

    .tq-flow-card { padding: 12px 10px; gap: 10px; margin-bottom: 16px; border-radius: 16px; }
    .tq-flow-flag { font-size: 1.6rem; }
    .tq-flow-city { font-size: .78rem; }
    .tq-flow-offset { font-size: .82rem; }
    .tq-flow-arrow { font-size: .76rem; }

    .tq-tips-grid { grid-template-columns: 1fr; gap: 6px; margin-bottom: 16px; }
    .tq-tip-box { padding: 10px 12px; flex-direction: row; align-items: center; gap: 10px; }
    .tq-tip-ico { font-size: 1.2rem; flex-shrink: 0; }
    .tq-tip-tit { font-size: .82rem; }
    .tq-tip-txt { font-size: .76rem; margin: 0; }

    .tq-takeaway-note { font-size: .85rem; padding: 10px 14px; margin-bottom: 18px; }

    .tq-tut-footer { flex-direction: column-reverse; align-items: stretch; gap: 10px; padding-top: 14px; }
    .tq-tut-footer .btn-row { width: 100%; gap: 8px; }
    .tq-tut-footer .btn-row .btn { flex: 1; min-height: 44px; font-size: .86rem; padding: 0 12px; }
    .tq-tut-skip-btn { font-size: .82rem; padding: 6px 8px; }

    /* Results */
    .card .final-score { font-size: 2.1rem; }
    .card .rowline { gap: 18px; margin: 14px 0 16px; }
    .card .rowline .n { font-size: 1.2rem; }
    .card .rowline .l { font-size: .7rem; }

    /* Pause */
    .pause-card { padding: 24px 20px; border-radius: 18px; }
    .pause-card h2 { font-size: 1.4rem; }
    .pause-card p { font-size: .88rem; }
  }

  @media (max-width: 400px) {
    .stats { gap: 4px; }
    .stat { padding: 5px 6px; }
    .stat .label { font-size: .6rem; }
    .stat .val { font-size: .85rem; }
    .quiz-stat { padding: 5px 6px; }
    .quiz-stat-label { font-size: .6rem; }
    .quiz-stat-val { font-size: .9rem; }
    .tq .eduplay-nav-btn { font-size: .66rem; }
    .tq .eduplay-nav-btn i { font-size: .92rem; }
    .c-city { font-size: .82rem; }
    .c-time { font-size: clamp(1.3rem, 6.5vw, 1.7rem); }
    .c-day { font-size: .76rem; }
    .c-arrow svg { width: 16px; height: 16px; }
    .option { min-height: 60px; padding: 8px 12px; }
    .o-time { font-size: 1rem; }
    .tq-instr-title { font-size: 1.5rem !important; }
  }

  @media (max-width: 340px) {
    .burger-menu-btn { width: 44px; height: 44px; font-size: 1.3rem; top: 14px; right: 14px; }
    .tq-head { padding-right: 58px; }
  }

  /* ---------- Reduced motion ---------- */
  @media (prefers-reduced-motion:reduce){
    .tq *,.tq *::before,.tq *::after{animation:none !important;transition:none !important}
  }
</style>

<div class="tq">

<div class="wrap">
  <header class="tq-head">
    <div class="tq-title">
      <h1>🌏 Time Zone Quest <span class="sub">Manila vs. the world</span></h1>
    </div>
    <div class="tq-head-right">
      <div class="stats" role="group" aria-label="Game stats">
        <div class="stat level"><span class="label">Level</span><span class="val" id="uiLevel">1</span></div>
        <div class="stat coins"><span class="label">Coins</span><span class="val" id="uiCoins">50</span></div>
        <!-- Mobile-only stats -->
        <div class="stat time-mobile"><span class="label">Time</span><span class="val" id="uiTimeMobile">05:00</span></div>
        <div class="stat score-mobile"><span class="label">Score</span><span class="val" id="uiScoreMobile">0</span></div>
        <div class="stat streak-mobile"><span class="label">Streak</span><span class="val" id="uiStreakMobile">0</span></div>
        <div class="stat round-mobile"><span class="label">Round</span><span class="val" id="uiRoundMobile">– / 10</span></div>
      </div>
      <div class="eduplay-game-nav">
        <button type="button" class="eduplay-nav-btn eduplay-btn-help" id="helpNavBtn" title="How to Play &amp; Tutorial" onclick="openTut(true)">
          <i class="bi bi-question-circle-fill"></i> <span>Tutorial</span>
        </button>
        <button type="button" class="eduplay-nav-btn eduplay-btn-pause" id="pauseBtn" title="Pause Game" onclick="togglePause()">
          <i class="bi bi-pause-fill"></i> <span>Pause</span>
        </button>
        <a href="{{ route('student.map', 'time_quest') }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
        <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Progress"><i class="bi bi-box-arrow-right"></i> <span>Exit</span></a>
      </div>
    </div>
  </header>

<!-- Burger menu button (mobile only) -->
<button type="button" class="burger-menu-btn" id="burgerMenuBtn" onclick="toggleMobileMenu()" aria-label="Open menu">
  <i class="bi bi-list"></i>
</button>

<!-- Mobile menu overlay -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay" onclick="toggleMobileMenu()">
  <div class="mobile-menu-panel" onclick="event.stopPropagation()">
    <div class="mobile-menu-header">
      <div class="mobile-menu-title">Menu</div>
      <button class="mobile-menu-close" onclick="toggleMobileMenu()" aria-label="Close menu">
        <i class="bi bi-x"></i>
      </button>
    </div>
    <div class="mobile-menu-items">
      <button type="button" class="eduplay-nav-btn eduplay-btn-help" title="How to Play &amp; Tutorial" onclick="openTut(true); toggleMobileMenu();">
        <i class="bi bi-question-circle-fill"></i>
        <span>Tutorial</span>
      </button>
      <button type="button" class="eduplay-nav-btn eduplay-btn-pause" title="Pause Game" onclick="togglePause(); toggleMobileMenu();">
        <i class="bi bi-pause-fill"></i>
        <span>Pause</span>
      </button>
      <a href="{{ route('student.map', 'time_quest') }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map">
        <i class="bi bi-map-fill"></i>
        <span>Game Map</span>
      </a>
      <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Progress">
        <i class="bi bi-box-arrow-right"></i>
        <span>Exit</span>
      </a>
    </div>
  </div>
</div>

  <main>
    <div class="map-panel">
      <div id="map" aria-label="World map showing Manila and the target city" role="img"></div>
      <div class="route-chip" id="routeChip"></div>
      <button class="recenter" id="recenterBtn" type="button" aria-label="Re-center map" title="Re-center map">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>
      </button>
      <div class="map-legend" aria-hidden="true">
        <span><i class="dot" style="background:#ffd66b"></i>Manila</span>
        <span><i class="dot" style="background:#ff4d6d"></i>Target city</span>
        <span class="hide-sm"><i class="dot" style="background:#5fd0ff"></i>Answer highlight</span>
      </div>
    </div>

    <aside class="quiz-panel" aria-label="Quiz">
      <div class="quiz-stats">
        <div class="quiz-stat time">
          <span class="quiz-stat-label">Time</span>
          <span class="quiz-stat-val" id="uiTime">05:00</span>
        </div>
        <div class="quiz-stat score">
          <span class="quiz-stat-label">Score</span>
          <span class="quiz-stat-val" id="uiScore">0</span>
        </div>
        <div class="quiz-stat streak">
          <span class="quiz-stat-label">Streak</span>
          <span class="quiz-stat-val" id="uiStreak">0</span>
        </div>
        <div class="quiz-stat round">
          <span class="quiz-stat-label">Round</span>
          <span class="quiz-stat-val" id="uiRound">– / 10</span>
        </div>
      </div>
      <div class="quiz-top">
        <div class="q-label" id="qLabel">Get ready…</div>
        <div class="pips" id="pips" aria-hidden="true"></div>
        <button type="button" class="hint-btn-quiz" id="hintBtn" onclick="useHint()" title="Use 20 coins to eliminate 2 wrong answers">
          <i class="bi bi-lightbulb-fill"></i>
          <span>Hint</span>
          <span class="hint-cost">(20🪙)</span>
        </button>
      </div>
      <div class="quiz-mid">
        <div id="qText">Loading…</div>
        <div id="options" role="group" aria-label="Answer choices"></div>
        <div id="feedback" role="status" aria-live="polite"></div>
        <button id="next" class="btn" type="button">Next round</button>
      </div>
      <p class="hint">Press 1–4 to answer, Enter for next round.</p>
    </aside>
  </main>
</div>

<!-- Pause overlay -->
<div class="pause-overlay" id="pauseOverlay">
  <div class="pause-card">
    <div style="font-size:3rem;margin-bottom:0.5rem;">⏸</div>
    <h2>Quest Paused</h2>
    <p>The clock has stopped. Take a breather and resume when you’re ready to continue your time zone adventure!</p>
    <button class="btn" style="width:100%;margin-bottom:10px;" onclick="togglePause()">▶ Resume Quest</button>
    <button class="btn ghost" style="width:100%;min-height:46px;font-size:0.92rem;" onclick="openTut(true)">
      <i class="bi bi-mortarboard-fill" style="margin-right:6px;"></i> 📚 How to Play / Tutorial
    </button>
    <div class="eduplay-game-nav" style="margin-top:16px;display:flex;gap:10px;justify-content:center;">
      <a href="{{ route('student.map', 'time_quest') }}" class="eduplay-nav-btn eduplay-btn-map"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
      <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit"><i class="bi bi-box-arrow-right"></i> <span>Exit</span></a>
    </div>
  </div>
</div>

<!-- Start / Instruction overlay -->
<div id="overlay" class="overlay" role="dialog" aria-modal="true" aria-label="Time Zone Quest Instructions">
  <div class="card tq-instr-card">
    <div style="margin-bottom:4px;">
      <span class="tq-badge"><i class="bi bi-compass"></i> Grade 5 Math • Global Time Zones</span>
    </div>
    <h2 class="tq-instr-title">Time Zone Quest</h2>
    <p class="tq-instr-sub">
      Navigate international flight paths from 🇵🇭 <b>Manila (UTC+8)</b>! Calculate world times across 40+ international cities using standard UTC offsets.
    </p>

    <div class="tq-features-grid">
      <div class="tq-feat-card blue">
        <div class="tq-feat-head">
          <div class="tq-feat-icon">🧭</div>
          <div class="tq-feat-title">Read the Clocks</div>
        </div>
        <p class="tq-feat-desc">
          Compare Manila’s reference clock with the target city and its UTC offset to determine the exact destination time.
        </p>
      </div>

      <div class="tq-feat-card gold">
        <div class="tq-feat-head">
          <div class="tq-feat-icon">🌅</div>
          <div class="tq-feat-title">East (+) vs West (−)</div>
        </div>
        <p class="tq-feat-desc">
          Cities <b>East</b> greet sunrise earlier ➔ <b>ADD (+) hours</b>. Cities <b>West</b> see sunset later ➔ <b>SUBTRACT (−) hours</b>.
        </p>
      </div>

      <div class="tq-feat-card green">
        <div class="tq-feat-head">
          <div class="tq-feat-icon">🏆</div>
          <div class="tq-feat-title">Goal &amp; Level Up</div>
        </div>
        <p class="tq-feat-desc">
          You have <b>5 minutes</b> for <b>10 rounds</b>. Score at least <b>8 out of 10</b> correctly to conquer the level!
        </p>
      </div>
    </div>

    <div class="tq-instr-footer">
      <div class="tq-stats-pills">
        <div class="tq-stat-pill">⏱️ <b>5:00</b> Timer</div>
        <div class="tq-stat-pill">🎯 <b>10</b> Rounds</div>
        <div class="tq-stat-pill">🌍 <b>40+</b> Cities</div>
        <div class="tq-stat-pill">⭐ Level <b><span id="instrLevel">{{ $progress->current_level ?? 1 }}</span></b></div>
      </div>

      <div class="tq-actions-row">
        <button id="viewTutBtn" class="btn ghost" type="button" onclick="openTut(false)" style="min-height:50px;font-size:0.95rem;padding:0 22px;">
          <i class="bi bi-mortarboard-fill" style="margin-right:8px;"></i> How to Play &amp; Tutorial
        </button>
        <button id="startBtn" class="btn go" type="button" style="min-height:50px;font-size:1.05rem;padding:0 30px;">
          🚀 Start Mission
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Tutorial overlay (5 spacious interactive slides) -->
<div id="tutorialOverlay" class="overlay hidden" role="dialog" aria-modal="true" aria-label="Time Zone Tutorial">
  <div class="card tut-card">
    <div class="tq-tut-top">
      <span class="tq-badge"><i class="bi bi-mortarboard-fill"></i> TIME ZONE ACADEMY</span>
      <button class="tq-tut-close-btn" type="button" onclick="closeTut()" title="Close Tutorial">&times;</button>
    </div>

    <div class="tq-stepper" id="tutStepper" role="tablist" aria-label="Tutorial Chapters">
      <button class="tq-step-tab active" type="button" onclick="nextTut(1)">
        <span class="tq-num">1</span> <span>UTC Anchor</span>
      </button>
      <button class="tq-step-tab" type="button" onclick="nextTut(2)">
        <span class="tq-num">2</span> <span>Going East (+)</span>
      </button>
      <button class="tq-step-tab" type="button" onclick="nextTut(3)">
        <span class="tq-num">3</span> <span>Going West (−)</span>
      </button>
      <button class="tq-step-tab" type="button" onclick="nextTut(4)">
        <span class="tq-num">4</span> <span>Crossing Midnight</span>
      </button>
      <button class="tq-step-tab" type="button" onclick="nextTut(5)">
        <span class="tq-num">5</span> <span>Pro Tips &amp; Keys</span>
      </button>
    </div>

    <!-- SLIDE 1 -->
    <div id="tutStep1" class="tq-slide-body">
      <h2 class="tq-tut-slide-title" style="color:var(--cyan);">The Globe &amp; UTC Anchor</h2>
      <p class="tq-tut-slide-text">
        The Earth rotates 360° every 24 hours. Global time zones are measured against <b>UTC (Coordinated Universal Time)</b> at 0° longitude in Greenwich, UK.
      </p>

      <div class="tq-flow-card">
        <div class="tq-flow-step">
          <span class="tq-flow-flag">🇬🇧</span>
          <span class="tq-flow-city">UTC Anchor</span>
          <span class="tq-flow-offset">UTC 0:00</span>
        </div>
        <div class="tq-flow-arrow">➔ +8 Hours Ahead ➔</div>
        <div class="tq-flow-step highlight">
          <span class="tq-flow-flag">🇵🇭</span>
          <span class="tq-flow-city">Home Base: Manila</span>
          <span class="tq-flow-offset" style="color:var(--gold);">UTC +8:00</span>
        </div>
      </div>

      <div class="tq-takeaway-note">
        💡 In Time Quest, Manila (UTC+8) is your anchor — every calculation starts from here!
      </div>

      <div class="tq-tut-footer">
        <button class="tq-tut-skip-btn" type="button" onclick="closeTut()">Skip Tutorial</button>
        <div class="btn-row">
          <button class="btn" type="button" onclick="nextTut(2)">Next: Going East →</button>
        </div>
      </div>
    </div>

    <!-- SLIDE 2 -->
    <div id="tutStep2" class="tq-slide-body hidden">
      <h2 class="tq-tut-slide-title" style="color:var(--gold);">Going East = ADD Hours (+)</h2>
      <p class="tq-tut-slide-text">
        Because the Earth rotates to the east, cities east of Manila greet the sunrise earlier. They are <b style="color:var(--gold);">ahead</b> of us in time!
      </p>

      <div class="tq-compare-hero east">
        <div class="tq-ch-city">
          <div class="tq-ch-flag">🇵🇭</div>
          <div class="tq-ch-name">Manila (UTC+8)</div>
          <div class="tq-ch-time">2:00 PM</div>
        </div>
        <div class="tq-ch-mid">
          <span class="tq-ch-badge east">+1 Hour Ahead</span>
          <span class="tq-ch-math">9 − 8 = +1h</span>
        </div>
        <div class="tq-ch-city">
          <div class="tq-ch-flag">🇯🇵</div>
          <div class="tq-ch-name">Tokyo (UTC+9)</div>
          <div class="tq-ch-time gold">3:00 PM</div>
        </div>
      </div>

      <div class="tq-takeaway-note">
        💡 When it's 2:00 PM in Manila, simply add 1 hour: it is <b>3:00 PM</b> in Tokyo!
      </div>

      <div class="tq-tut-footer">
        <button class="tq-tut-skip-btn" type="button" onclick="closeTut()">Skip Tutorial</button>
        <div class="btn-row">
          <button class="btn ghost back" type="button" onclick="nextTut(1)">← Back</button>
          <button class="btn" type="button" onclick="nextTut(3)">Next: Going West →</button>
        </div>
      </div>
    </div>

    <!-- SLIDE 3 -->
    <div id="tutStep3" class="tq-slide-body hidden">
      <h2 class="tq-tut-slide-title" style="color:var(--rose-soft);">Going West = SUBTRACT Hours (−)</h2>
      <p class="tq-tut-slide-text">
        Cities west of Manila experience sunrise and sunset later. They are <b style="color:var(--rose-soft);">behind</b> us in time!
      </p>

      <div class="tq-compare-hero west">
        <div class="tq-ch-city">
          <div class="tq-ch-flag">🇵🇭</div>
          <div class="tq-ch-name">Manila (UTC+8)</div>
          <div class="tq-ch-time">8:00 PM</div>
        </div>
        <div class="tq-ch-mid">
          <span class="tq-ch-badge west">−8 Hours Behind</span>
          <span class="tq-ch-math">0 − 8 = −8h</span>
        </div>
        <div class="tq-ch-city">
          <div class="tq-ch-flag">🇬🇧</div>
          <div class="tq-ch-name">London (UTC+0)</div>
          <div class="tq-ch-time rose">12:00 PM</div>
          <div class="tq-ch-day" style="color:var(--rose-soft);">Noon</div>
        </div>
      </div>

      <div class="tq-takeaway-note">
        💡 When it's 8:00 PM in Manila, subtract 8 hours: it is <b>12:00 PM (noon)</b> in London!
      </div>

      <div class="tq-tut-footer">
        <button class="tq-tut-skip-btn" type="button" onclick="closeTut()">Skip Tutorial</button>
        <div class="btn-row">
          <button class="btn ghost back" type="button" onclick="nextTut(2)">← Back</button>
          <button class="btn" type="button" onclick="nextTut(4)">Next: Crossing Midnight →</button>
        </div>
      </div>
    </div>

    <!-- SLIDE 4 -->
    <div id="tutStep4" class="tq-slide-body hidden">
      <h2 class="tq-tut-slide-title" style="color:var(--mint-soft);">Crossing Midnight Changes the Day!</h2>
      <p class="tq-tut-slide-text">
        Subtracting past 12:00 AM (midnight) moves you into <b>yesterday</b>. Adding past 11:59 PM moves you into <b>tomorrow</b>!
      </p>

      <div class="tq-compare-hero night">
        <div class="tq-ch-city">
          <div class="tq-ch-flag">🇵🇭</div>
          <div class="tq-ch-name">Manila</div>
          <div class="tq-ch-time">10:00 AM</div>
          <div class="tq-ch-day" style="color:var(--gold);">Tuesday</div>
        </div>
        <div class="tq-ch-mid">
          <span class="tq-ch-badge night">−13 Hours</span>
          <span class="tq-ch-math">−5 − (+8) = −13h</span>
        </div>
        <div class="tq-ch-city">
          <div class="tq-ch-flag">🇺🇸</div>
          <div class="tq-ch-name">New York (UTC−5)</div>
          <div class="tq-ch-time mint">9:00 PM</div>
          <div class="tq-ch-day alert">Monday (Yesterday!)</div>
        </div>
      </div>

      <div class="tq-takeaway-note">
        ⚠️ Pro Tip: Always double-check the weekday shown on the 4 answer choices!
      </div>

      <div class="tq-tut-footer">
        <button class="tq-tut-skip-btn" type="button" onclick="closeTut()">Skip Tutorial</button>
        <div class="btn-row">
          <button class="btn ghost back" type="button" onclick="nextTut(3)">← Back</button>
          <button class="btn" type="button" onclick="nextTut(5)">Next: Half-Hours &amp; Tips →</button>
        </div>
      </div>
    </div>

    <!-- SLIDE 5 -->
    <div id="tutStep5" class="tq-slide-body hidden">
      <h2 class="tq-tut-slide-title" style="color:var(--cyan);">Half-Hours &amp; Pro Shortcuts</h2>
      <p class="tq-tut-slide-text">
        Some regions use 30-minute offsets, like India (UTC+5:30) which is 2 hours and 30 minutes behind Manila.
      </p>

      <div class="tq-tips-grid">
        <div class="tq-tip-box">
          <div class="tq-tip-ico">⌨️</div>
          <div class="tq-tip-tit">Keyboard 1–4</div>
          <p class="tq-tip-txt">Press number keys to pick choices instantly without moving your cursor.</p>
        </div>
        <div class="tq-tip-box">
          <div class="tq-tip-ico">⏩</div>
          <div class="tq-tip-tit">Enter or Space</div>
          <p class="tq-tip-txt">Quickly advance to the next round after reviewing your calculation.</p>
        </div>
        <div class="tq-tip-box">
          <div class="tq-tip-ico">🔥</div>
          <div class="tq-tip-tit">Streaks Bonus</div>
          <p class="tq-tip-txt">Consecutive correct answers earn <b>+25 bonus points</b> each time!</p>
        </div>
      </div>

      <div class="tq-takeaway-note" style="border-color:rgba(45,220,130,0.35);background:rgba(45,220,130,0.08);">
        🎯 Target: Score <b>8 out of 10</b> correct answers to unlock the next level!
      </div>

      <div class="tq-tut-footer">
        <button class="tq-tut-skip-btn" type="button" onclick="closeTut(false)">Close Tutorial</button>
        <div class="btn-row">
          <button class="btn ghost back" type="button" onclick="nextTut(4)">← Back</button>
          <button class="btn go" id="tutStartMissionBtn" type="button" onclick="closeTut(true)">🚀 Start Mission!</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Results overlay -->
<div id="resultsOverlay" class="overlay hidden" role="dialog" aria-modal="true" aria-label="Quest Results">
</div>

</div><!-- /.tq -->

<script>
/* ============================================================
   MOBILE MENU
   ============================================================ */
function toggleMobileMenu() {
  const overlay = document.getElementById('mobileMenuOverlay');
  const burgerBtn = document.getElementById('burgerMenuBtn');
  
  if (overlay && burgerBtn) {
    overlay.classList.toggle('active');
    burgerBtn.classList.toggle('active');
    
    // Update icon
    const icon = burgerBtn.querySelector('i');
    if (icon) {
      if (overlay.classList.contains('active')) {
        icon.className = 'bi bi-x';
      } else {
        icon.className = 'bi bi-list';
      }
    }
    
    // Prevent body scroll when menu is open
    if (overlay.classList.contains('active')) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = '';
    }
  }
}

/* ============================================================
   CITY DATA
   ============================================================ */
const CITIES = [
  {name:'Manila',       flag:'🇵🇭', tz:'Asia/Manila',                    lon:120.98, lat:14.60, ref:true},
  {name:'Tokyo',        flag:'🇯🇵', tz:'Asia/Tokyo',                     lon:139.69, lat:35.69, tier:1},
  {name:'Seoul',        flag:'🇰🇷', tz:'Asia/Seoul',                     lon:126.98, lat:37.57, tier:1},
  {name:'Beijing',      flag:'🇨🇳', tz:'Asia/Shanghai',                  lon:116.41, lat:39.90, tier:1},
  {name:'Hong Kong',    flag:'🇭🇰', tz:'Asia/Hong_Kong',                 lon:114.17, lat:22.32, tier:1},
  {name:'Singapore',    flag:'🇸🇬', tz:'Asia/Singapore',                 lon:103.82, lat:1.35,  tier:1},
  {name:'Bangkok',      flag:'🇹🇭', tz:'Asia/Bangkok',                   lon:100.50, lat:13.76, tier:1},
  {name:'Jakarta',      flag:'🇮🇩', tz:'Asia/Jakarta',                   lon:106.85, lat:-6.21, tier:1},
  {name:'Sydney',       flag:'🇦🇺', tz:'Australia/Sydney',               lon:151.21, lat:-33.87,tier:1},
  {name:'Perth',        flag:'🇦🇺', tz:'Australia/Perth',                lon:115.86, lat:-31.95,tier:1},
  {name:'Auckland',     flag:'🇳🇿', tz:'Pacific/Auckland',               lon:174.76, lat:-36.85,tier:1},

  {name:'Dhaka',        flag:'🇧🇩', tz:'Asia/Dhaka',                     lon:90.41,  lat:23.81, tier:2},
  {name:'Dubai',        flag:'🇦🇪', tz:'Asia/Dubai',                     lon:55.27,  lat:25.20, tier:2},
  {name:'Moscow',       flag:'🇷🇺', tz:'Europe/Moscow',                  lon:37.62,  lat:55.76, tier:2},
  {name:'Istanbul',     flag:'🇹🇷', tz:'Europe/Istanbul',                lon:28.98,  lat:41.01, tier:2},
  {name:'Cairo',        flag:'🇪🇬', tz:'Africa/Cairo',                   lon:31.24,  lat:30.04, tier:2},
  {name:'Nairobi',      flag:'🇰🇪', tz:'Africa/Nairobi',                 lon:36.82,  lat:-1.29, tier:2},
  {name:'Cape Town',    flag:'🇿🇦', tz:'Africa/Johannesburg',            lon:18.42,  lat:-33.92,tier:2},
  {name:'Lagos',        flag:'🇳🇬', tz:'Africa/Lagos',                   lon:3.38,   lat:6.52,  tier:2},
  {name:'London',       flag:'🇬🇧', tz:'Europe/London',                  lon:-0.13,  lat:51.51, tier:2},
  {name:'Paris',        flag:'🇫🇷', tz:'Europe/Paris',                   lon:2.35,   lat:48.86, tier:2},
  {name:'Berlin',       flag:'🇩🇪', tz:'Europe/Berlin',                  lon:13.40,  lat:52.52, tier:2},
  {name:'Madrid',       flag:'🇪🇸', tz:'Europe/Madrid',                  lon:-3.70,  lat:40.42, tier:2},
  {name:'Reykjavík',    flag:'🇮🇸', tz:'Atlantic/Reykjavik',             lon:-21.94, lat:64.15, tier:2},
  {name:'New York',     flag:'🇺🇸', tz:'America/New_York',               lon:-74.01, lat:40.71, tier:2},
  {name:'Chicago',      flag:'🇺🇸', tz:'America/Chicago',                lon:-87.63, lat:41.88, tier:2},
  {name:'Los Angeles',  flag:'🇺🇸', tz:'America/Los_Angeles',            lon:-118.24,lat:34.05, tier:2},
  {name:'Vancouver',    flag:'🇨🇦', tz:'America/Vancouver',              lon:-123.12,lat:49.28, tier:2},
  {name:'Toronto',      flag:'🇨🇦', tz:'America/Toronto',                lon:-79.38, lat:43.65, tier:2},
  {name:'Mexico City',  flag:'🇲🇽', tz:'America/Mexico_City',            lon:-99.13, lat:19.43, tier:2},
  {name:'Anchorage',    flag:'🇺🇸', tz:'America/Anchorage',              lon:-149.90,lat:61.22, tier:2},
  {name:'Honolulu',     flag:'🇺🇸', tz:'Pacific/Honolulu',               lon:-157.86,lat:21.31, tier:2},
  {name:'São Paulo',    flag:'🇧🇷', tz:'America/Sao_Paulo',              lon:-46.63, lat:-23.55,tier:2},
  {name:'Buenos Aires', flag:'🇦🇷', tz:'America/Argentina/Buenos_Aires', lon:-58.38, lat:-34.60,tier:2},
  {name:'Lima',         flag:'🇵🇪', tz:'America/Lima',                   lon:-77.04, lat:-12.05,tier:2},
  {name:'Santiago',     flag:'🇨🇱', tz:'America/Santiago',               lon:-70.65, lat:-33.46,tier:2},

  {name:'Casablanca',   flag:'🇲🇦', tz:'Africa/Casablanca',              lon:-7.58,  lat:33.57, tier:2},
  {name:'Riyadh',       flag:'🇸🇦', tz:'Asia/Riyadh',                    lon:46.67,  lat:24.71, tier:2},
  {name:'Rome',         flag:'🇮🇹', tz:'Europe/Rome',                    lon:12.49,  lat:41.90, tier:2},
  {name:'Bogotá',       flag:'🇨🇴', tz:'America/Bogota',                 lon:-74.07, lat:4.71,  tier:2},

  {name:'Yangon',       flag:'🇲🇲', tz:'Asia/Yangon',                    lon:96.16,  lat:16.87, tier:3},
  {name:'Kathmandu',    flag:'🇳🇵', tz:'Asia/Kathmandu',                 lon:85.32,  lat:27.72, tier:3},
  {name:'New Delhi',    flag:'🇮🇳', tz:'Asia/Kolkata',                   lon:77.21,  lat:28.61, tier:3},
  {name:'Mumbai',       flag:'🇮🇳', tz:'Asia/Kolkata',                   lon:72.87,  lat:19.07, tier:3},
  {name:'Tehran',       flag:'🇮🇷', tz:'Asia/Tehran',                    lon:51.39,  lat:35.69, tier:3}
];
const MANILA = CITIES[0];
const TARGETS = CITIES.filter(c => !c.ref);

/* ============================================================
   TIME HELPERS
   ============================================================ */
function partsIn(date, tz){
  const dtf = new Intl.DateTimeFormat('en-US', {
    timeZone: tz, weekday:'short', hour:'numeric', minute:'2-digit', hour12:true
  });
  const o = {};
  for (const p of dtf.formatToParts(date)) o[p.type] = p.value;
  return { weekday:o.weekday, hour:+o.hour, minute:o.minute, period:o.dayPeriod };
}
const fmtTime = p => `${p.hour}:${String(p.minute).padStart(2,'0')} ${p.period}`;
const fmtFull = p => `${fmtTime(p)} · ${p.weekday}`;

function offsetMinutes(date, tz){
  const dtf = new Intl.DateTimeFormat('en-US', {
    timeZone: tz, year:'numeric', month:'2-digit', day:'2-digit',
    hour:'2-digit', minute:'2-digit', second:'2-digit', hourCycle:'h23'
  });
  const o = {};
  for (const p of dtf.formatToParts(date)) o[p.type] = p.value;
  const asUTC = Date.UTC(+o.year, +o.month-1, +o.day, +o.hour, +o.minute, +o.second);
  return Math.round((asUTC - date.getTime()) / 60000);
}
function offsetLabel(mins){
  const sign = mins < 0 ? '−' : '+';
  const a = Math.abs(mins);
  const h = Math.floor(a/60), m = a % 60;
  return `UTC${sign}${h}${m ? ':' + String(m).padStart(2,'0') : ''}`;
}
function fmtDur(m){
  const h = Math.floor(m/60), mm = m % 60, out = [];
  if (h)  out.push(h + (h === 1 ? ' hour' : ' hours'));
  if (mm) out.push(mm + (mm === 1 ? ' minute' : ' minutes'));
  return out.join(' ') || '0 minutes';
}
function fmtShort(m){
  if (m === 0) return '0h';
  const sign = m < 0 ? '−' : '+';
  const a = Math.abs(m), h = Math.floor(a/60), mm = a % 60;
  return sign + (h ? h + 'h' : '') + (mm ? (h ? ' ' : '') + mm + 'm' : '');
}
function shuffle(a){ const b = a.slice(); for (let i = b.length-1; i > 0; i--){ const j = Math.floor(Math.random()*(i+1)); [b[i],b[j]]=[b[j],b[i]]; } return b; }

/* ============================================================
   GAME STATE
   ============================================================ */
const TOTAL_ROUNDS = 10;
let CURRENT_LEVEL = {{ $progress->current_level ?? 1 }};
const isMobileLayout = () => window.matchMedia('(max-width:940px)').matches;
const prefersReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const ARROW_SVG = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';

const state = {
  round: 0, score: 0, streak: 0, best: 0, coins: 50,
  target: null, frozen: null, mode: 'A',
  answerLabel: '', options: [], answered: false, hintUsed: false,
  knownParts: null, answerParts: null, results: [],
  timeRemaining: 300, timerInt: null, isPaused: false
};

/* ============================================================
   LEAFLET MAP SETUP
   ============================================================ */
const map = L.map('map', {
  center: [20, 0],
  zoom: 2,
  minZoom: 1,
  maxZoom: 6,
  zoomControl: true,
  attributionControl: true,
  worldCopyJump: true,
  scrollWheelZoom: false,
  dragging: !L.Browser.mobile
});

L.maplibreGL({
  style: 'https://tiles.openfreemap.org/styles/dark',
  attribution: '© <a href="https://openfreemap.org">OpenFreeMap</a> · © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

const refLayer    = L.layerGroup().addTo(map);
const targetLayer = L.layerGroup().addTo(map);
const manilaLayer = L.layerGroup().addTo(map);
const arcLayer    = L.layerGroup().addTo(map);
const answerLayer = L.layerGroup().addTo(map);

function labelMarker(lat, lon, html, cls, side, z){
  const left = side === 'left';
  return L.marker([lat, lon], {
    icon: L.divIcon({
      className: '',
      html: `<div class="city-label ${cls}${left ? ' left' : ''}">${html}</div>`,
      iconSize: [0,0],
      iconAnchor: left ? [12, 8] : [-12, 8]
    }),
    interactive: false,
    zIndexOffset: z
  });
}

function drawRefCities(){
  refLayer.clearLayers();
  for (const c of CITIES){
    if (c.ref) continue;
    L.circleMarker([c.lat, c.lon], {
      radius: 3,
      color: 'rgba(255,255,255,.35)',
      fillColor: 'rgba(255,255,255,.35)',
      fillOpacity: 1,
      weight: 1,
      interactive: false
    }).addTo(refLayer);
  }
}
drawRefCities();

function drawManila(){
  manilaLayer.clearLayers();
  const html = `
    <div class="pulse-marker" style="width:46px;height:46px;">
      <div class="ring" style="border:2px solid rgba(255,214,107,.7);"></div>
      <div class="core" style="width:11px;height:11px;background:#ffd66b;border-color:#4a3400;"></div>
    </div>`;
  L.marker([MANILA.lat, MANILA.lon], {
    icon: L.divIcon({ className:'', html, iconSize:[46,46], iconAnchor:[23,23] }),
    interactive: false,
    zIndexOffset: 1000
  }).addTo(manilaLayer);

  const targetIsEast = state.target ? state.target.lon > MANILA.lon : false;
  labelMarker(MANILA.lat, MANILA.lon, '🇵🇭 Manila', 'manila', targetIsEast ? 'left' : 'right', 1001).addTo(manilaLayer);
}
drawManila();

function drawTarget(){
  targetLayer.clearLayers();
  if (!state.target) return;
  const t = state.target;

  const html = `
    <div class="pulse-marker" style="width:52px;height:52px;">
      <div class="ring" style="border:2.5px solid rgba(255,77,109,.8);"></div>
      <div class="core" style="width:13px;height:13px;background:#ff4d6d;border-color:#fff;"></div>
    </div>`;
  L.marker([t.lat, t.lon], {
    icon: L.divIcon({ className:'', html, iconSize:[52,52], iconAnchor:[26,26] }),
    interactive: false,
    zIndexOffset: 900
  }).addTo(targetLayer);

  const side = t.lon > MANILA.lon ? 'right' : 'left';
  labelMarker(t.lat, t.lon, `${t.flag} ${t.name}`, 'target', side, 901).addTo(targetLayer);
}

function drawArc(){
  arcLayer.clearLayers();
  if (!state.target) return;
  const t = state.target;
  const bestLon = t.lon;

  const latlngs = [];
  const steps = 60;
  const bow = Math.min(Math.abs(bestLon - MANILA.lon) * 0.18, 32);
  const dir = t.lat > MANILA.lat ? 1 : -1;

  for (let i = 0; i <= steps; i++){
    const f = i / steps;
    const lon = MANILA.lon + (bestLon - MANILA.lon) * f;
    const lat = MANILA.lat + (t.lat - MANILA.lat) * f + dir * bow * Math.sin(Math.PI * f);
    latlngs.push([lat, lon]);
  }

  L.polyline(latlngs, {
    color: 'rgba(255,190,90,.55)',
    weight: 2,
    dashArray: '8 8',
    interactive: false,
    className: 'arc-line'
  }).addTo(arcLayer);
}

const arcStyle = document.createElement('style');
arcStyle.textContent = `
  @keyframes dashMove { to { stroke-dashoffset: -32; } }
  .arc-line { animation: dashMove 1.2s linear infinite; }
  @media (prefers-reduced-motion: reduce){ .arc-line { animation: none; } }
`;
document.head.appendChild(arcStyle);

function drawAnswerMarker(){
  answerLayer.clearLayers();
  if (!state.target) return;
  const t = state.target;
  L.circleMarker([t.lat, t.lon], {
    radius: 18,
    color: '#5fd0ff',
    fillColor: 'rgba(95,208,255,.2)',
    fillOpacity: 1,
    weight: 2,
    interactive: false
  }).addTo(answerLayer);
}

function fitMap(){
  if (!state.target) return;
  const t = state.target;
  const bounds = L.latLngBounds([MANILA.lat, MANILA.lon], [t.lat, t.lon]);
  map.fitBounds(bounds.pad(0.55), { animate: !prefersReducedMotion(), duration: 0.8 });
}

/* ============================================================
   GAME LOGIC
   ============================================================ */
const el = id => document.getElementById(id);

function renderPips(){
  const box = el('pips');
  box.innerHTML = '';
  for (let i = 0; i < TOTAL_ROUNDS; i++){
    const s = document.createElement('span');
    const r = state.results[i];
    s.className = 'pip' + (r === true ? ' ok' : r === false ? ' no' : (state.round > 0 && i === state.round - 1 ? ' now' : ''));
    box.appendChild(s);
  }
}

function updateHUD(){
  el('uiLevel').textContent  = CURRENT_LEVEL;
  el('uiRound').textContent  = `${Math.min(state.round, TOTAL_ROUNDS)} / ${TOTAL_ROUNDS}`;
  el('uiScore').textContent  = state.score;
  el('uiStreak').textContent = state.streak;
  el('uiCoins').textContent  = state.coins;
  
  // Update mobile stats
  const timeMobile = el('uiTimeMobile');
  const scoreMobile = el('uiScoreMobile');
  const streakMobile = el('uiStreakMobile');
  const roundMobile = el('uiRoundMobile');
  if (timeMobile) timeMobile.textContent = el('uiTime').textContent;
  if (scoreMobile) scoreMobile.textContent = state.score;
  if (streakMobile) streakMobile.textContent = state.streak;
  if (roundMobile) roundMobile.textContent = `${Math.min(state.round, TOTAL_ROUNDS)} / ${TOTAL_ROUNDS}`;
  
  renderPips();
  
  // Update hint button state
  const hintBtn = el('hintBtn');
  if (hintBtn) {
    if (state.hintUsed || state.answered || state.coins < 20) {
      hintBtn.disabled = true;
    } else {
      hintBtn.disabled = false;
    }
  }
}

function clockHTML(o){
  return `
    <div class="clock ${o.who}${o.ask ? ' ask' : ''}"${o.ask ? ' id="askClock"' : ''}>
      <div class="c-city">${o.flag} ${o.name}</div>
      <div class="c-off">${o.off}</div>
      <div class="c-time">${o.ask ? '?' : fmtTime(o.parts)}</div>
      <div class="c-day">${o.ask ? '&nbsp;' : o.parts.weekday}</div>
    </div>`;
}

function startRound(){
  state.round++;
  if (state.round > TOTAL_ROUNDS){ endGame(); return; }

  state.answered = false;
  state.hintUsed = false;

  const lastTarget = state.target;

  const d = new Date();
  d.setSeconds(0, 0);
  state.frozen = d;

  const manilaOff = offsetMinutes(d, MANILA.tz);
  const lastOff = lastTarget ? offsetMinutes(d, lastTarget.tz) : null;

  let pool = TARGETS.filter(c => {
    const cOff = offsetMinutes(d, c.tz);
    if (Math.abs(cOff - manilaOff) < 180) return false;
    if (lastOff !== null && Math.abs(cOff - lastOff) < 180) return false;
    return true;
  });

  if (pool.length === 0) pool = TARGETS;

  state.target = pool[Math.floor(Math.random() * pool.length)];
  const t = state.target;

  const manilaParts = partsIn(d, MANILA.tz);
  const cityParts   = partsIn(d, t.tz);

  const manilaOffStr = offsetLabel(offsetMinutes(d, MANILA.tz));
  const cityOffStr   = offsetLabel(offsetMinutes(d, t.tz));

  state.mode = Math.random() < 0.5 ? 'A' : 'B';

  const manilaSide = {who:'manila', flag:MANILA.flag, name:'Manila', off:manilaOffStr};
  const targetSide = {who:'target', flag:t.flag,      name:t.name,   off:cityOffStr};

  let known, ask, answerTz, answerParts;
  if (state.mode === 'A'){
    known = {...manilaSide, parts:manilaParts};
    ask   = {...targetSide, ask:true};
    answerTz = t.tz;
    answerParts = cityParts;
  } else {
    known = {...targetSide, parts:cityParts};
    ask   = {...manilaSide, ask:true};
    answerTz = MANILA.tz;
    answerParts = manilaParts;
  }

  state.knownParts  = known.parts;
  state.answerParts = answerParts;
  state.answerLabel = fmtFull(answerParts);
  state.options = buildOptions(d, answerTz, state.answerLabel);

  const qHTML = `
    <div class="clocks" role="group" aria-label="Right now it's ${fmtFull(known.parts)} in ${known.name} (${known.off}).">
      ${clockHTML(known)}
      <span class="c-arrow">${ARROW_SVG}</span>
      ${clockHTML(ask)}
    </div>
    <p class="q-ask">What time is it in <b>${ask.flag} ${ask.name}</b>?</p>`;

  el('qLabel').textContent = `Round ${state.round} of ${TOTAL_ROUNDS}`;
  el('qText').innerHTML = qHTML;
  el('feedback').className = '';
  el('feedback').innerHTML = '';
  el('next').classList.remove('show');
  el('options').innerHTML = '';
  el('routeChip').innerHTML = `${MANILA.flag} Manila <span class="sep">to</span> ${t.flag} ${t.name}`;

  drawManila();
  drawTarget();
  drawArc();
  answerLayer.clearLayers();
  fitMap();

  renderOptions();
  updateHUD();

  if (isMobileLayout() && state.round > 1){
    window.scrollTo({ top: 0, behavior: prefersReducedMotion() ? 'auto' : 'smooth' });
  }
}

function buildOptions(frozen, answerTz, answerLabel){
  const shifts = [
    1, -1, 2, -2, 3, -3, 4, -4, 5, -5, 6, -6, 7, -7, 8, -8, 9, -9, 10, -10, 12, -12,
    0.5, -0.5, 1.5, -1.5, 2.5, -2.5, 3.5, -3.5, 4.5, -4.5, 5.5, -5.5
  ];
  const seen = new Set([answerLabel]);
  const distractors = [];
  for (const s of shuffle(shifts)){
    if (distractors.length >= 3) break;
    const d2 = new Date(frozen.getTime() + s * 3600000);
    const lbl = fmtFull(partsIn(d2, answerTz));
    if (!seen.has(lbl)){ seen.add(lbl); distractors.push(lbl); }
  }
  return shuffle([answerLabel, ...distractors]);
}

function renderOptions(){
  const box = el('options');
  box.innerHTML = '';
  state.options.forEach((opt, i) => {
    const [time, day] = opt.split(' · ');
    const b = document.createElement('button');
    b.type = 'button';
    b.className = 'option';
    b.innerHTML = `<span class="o-time">${time}</span><span class="o-day">${day || ''}</span><span class="key" aria-hidden="true">${i+1}</span>`;
    b.dataset.value = opt;
    b.setAttribute('aria-label', `Option ${i+1}: ${time}, ${day || ''}`);
    b.onclick = () => answer(opt, b);
    box.appendChild(b);
  });
}

function answer(selected, btn){
  if (state.answered || state.isPaused) return;
  state.answered = true;

  const buttons = [...document.querySelectorAll('.option')];
  buttons.forEach(b => {
    b.disabled = true;
    if (b.dataset.value === state.answerLabel) b.classList.add('correct');
  });

  const correct = selected === state.answerLabel;
  let gained = 0;
  state.results[state.round - 1] = correct;

  if (correct){
    state.streak++;
    state.best = Math.max(state.best, state.streak);
    gained = 100 + Math.min(state.streak - 1, 6) * 25;
    state.score += gained;
    
    // Award coins for correct answers
    const coinsEarned = state.hintUsed ? 5 : 10;
    state.coins += coinsEarned;

    const scoreEl = el('uiScore');
    scoreEl.classList.remove('pop-anim');
    void scoreEl.offsetWidth;
    scoreEl.classList.add('pop-anim');
    
    // Animate coins
    const coinsEl = el('uiCoins');
    coinsEl.classList.remove('pop-anim');
    void coinsEl.offsetWidth;
    coinsEl.classList.add('pop-anim');
  } else {
    btn.classList.add('wrong');
    state.streak = 0;
  }

  drawAnswerMarker();

  const askClock = el('askClock');
  if (askClock){
    askClock.querySelector('.c-time').textContent = fmtTime(state.answerParts);
    askClock.querySelector('.c-day').textContent  = state.answerParts.weekday;
    askClock.classList.add('revealed');
  }

  const cityOff   = offsetMinutes(state.frozen, state.target.tz);
  const manilaOff = offsetMinutes(state.frozen, MANILA.tz);
  const diff = cityOff - manilaOff;

  el('routeChip').innerHTML =
    `${MANILA.flag} Manila <span class="sep">to</span> ${state.target.flag} ${state.target.name} <span class="diff">${fmtShort(diff)}</span>`;

  let rel;
  if (diff === 0)          rel = `${state.target.name} is in the <b>same time zone</b> as Manila.`;
  else if (diff > 0)       rel = `${state.target.name} is <b>${fmtDur(diff)} ahead</b> of Manila (east of us).`;
  else                     rel = `${state.target.name} is <b>${fmtDur(-diff)} behind</b> Manila (west of us).`;

  const shift = state.mode === 'A' ? diff : -diff;
  const known = state.knownParts, ans = state.answerParts;
  let how;
  if (shift === 0){
    how = `Same zone, so the time doesn't change: <b>${fmtFull(ans)}</b>.`;
  } else {
    const dayNote = known.weekday !== ans.weekday
      ? (shift > 0 ? ' The clock passes midnight, so the day moves forward.' : ' You cross midnight backwards, so it is the previous day.')
      : '';
    how = `${fmtTime(known)} ${shift > 0 ? '+' : '−'} ${fmtDur(Math.abs(shift))} = <b>${fmtFull(ans)}</b>.${dayNote}`;
  }

  const fb = el('feedback');
  fb.innerHTML = `
    <span class="verdict ${correct ? 'good' : 'bad'}">
      ${correct ? `Correct! +${gained} points` : 'Not quite.'}
    </span>
    <div class="expl">
      Manila is <code>${offsetLabel(manilaOff)}</code> and
      ${state.target.flag} ${state.target.name} is <code>${offsetLabel(cityOff)}</code>.<br>
      ${rel}
      <div class="calc"><small>How to work it out</small>${how}</div>
    </div>`;
  fb.className = 'show ' + (correct ? 'good' : 'bad');

  el('next').textContent = state.round >= TOTAL_ROUNDS ? 'See results' : 'Next round';
  el('next').classList.add('show');
  updateHUD();

  if (isMobileLayout()){
    fb.scrollIntoView({ block: 'center', behavior: prefersReducedMotion() ? 'auto' : 'smooth' });
  }
}

/* ============================================================
   HINT SYSTEM
   ============================================================ */
function useHint() {
  if (state.answered || state.hintUsed || state.coins < 20 || state.isPaused) return;
  
  state.hintUsed = true;
  state.coins -= 20;
  
  // Animate coins decrease
  const coinsEl = el('uiCoins');
  coinsEl.classList.remove('pop-anim');
  void coinsEl.offsetWidth;
  coinsEl.classList.add('pop-anim');
  
  updateHUD();
  
  // Eliminate 2 wrong answers
  const buttons = [...document.querySelectorAll('.option')];
  const wrongButtons = buttons.filter(b => b.dataset.value !== state.answerLabel);
  
  // Shuffle and pick 2 to eliminate
  const toEliminate = shuffle(wrongButtons).slice(0, 2);
  
  toEliminate.forEach((btn, index) => {
    setTimeout(() => {
      btn.style.opacity = '0.2';
      btn.style.pointerEvents = 'none';
      btn.style.transform = 'scale(0.95)';
      btn.disabled = true;
    }, index * 150);
  });
  
  // Show hint notification
  const fb = el('feedback');
  fb.innerHTML = `
    <span class="verdict" style="color:var(--gold);text-shadow:0 0 10px rgba(255,217,61,0.5);">
      💡 Hint Used! (-20🪙)
    </span>
    <div class="expl">
      Two wrong answers have been eliminated. Choose from the remaining two options!
    </div>`;
  fb.className = 'show';
  fb.style.borderColor = 'rgba(255,217,61,0.4)';
  fb.style.background = 'rgba(255,217,61,0.08)';
  
  setTimeout(() => {
    fb.style.display = 'none';
  }, 3000);
}

function endGame(isTimeout = false){
  if (state.timerInt) clearInterval(state.timerInt);

  const pct = Math.round(state.score / 1375 * 100);
  const correctCount = state.results.filter(Boolean).length;

  const isWin = correctCount >= 8;
  let currentLevel = {{ $progress->current_level ?? 1 }};
  const levelToSave = isWin ? currentLevel + 1 : currentLevel;

  $.post('/student/update-progress', {
      game_id: {{ $game->id }},
      level: levelToSave,
      score: pct,
      _token: '{{ csrf_token() }}'
  });

  let rank = '🌱 Time Traveler in Training';
  if (pct >= 85) rank = '🏆 Grandmaster of Greenwich';
  else if (pct >= 65) rank = '🌟 Seasoned Navigator';
  else if (pct >= 40) rank = '🧭 Junior Explorer';
  else if (pct >= 20) rank = '🗺️ Curious Wanderer';

  const levelNote = isWin
    ? `<div class="level-note up">Level up! Level ${levelToSave} unlocked.</div>`
    : `<div class="level-note">Score at least 8/10 to unlock level ${currentLevel + 1}. You can do it!</div>`;

  const timeoutMsg = isTimeout
    ? `<h2 style="color:var(--rose)">Time's Up!</h2>`
    : `<h2>${rank}</h2>`;

  const resOverlay = el('resultsOverlay');
  if (resOverlay) {
    resOverlay.innerHTML = `
      <div class="card tq-instr-card">
        <div class="big">${isTimeout ? '⏱️' : isWin ? (pct >= 85 ? '🎉' : '🏆') : '🌏'}</div>
        ${timeoutMsg}
        <div class="final-label">Final score</div>
        <div class="final-score">${state.score}</div>
        <div class="rowline">
          <div><div class="n">${state.best}</div><div class="l">Best streak</div></div>
          <div><div class="n">${correctCount}/${TOTAL_ROUNDS}</div><div class="l">Correct</div></div>
          <div><div class="n">${CURRENT_LEVEL}</div><div class="l">Level played</div></div>
        </div>
        ${levelNote}
        <div class="stack">
          <button id="againBtn" class="btn" type="button">Play again</button>
          <button id="resTutBtn" class="btn ghost" type="button" onclick="openTut(false)" style="min-height:46px;font-size:0.92rem;">
            <i class="bi bi-mortarboard-fill" style="margin-right:6px;"></i> 📚 Review Tutorial
          </button>
          <div class="eduplay-game-nav" style="width:100%;display:flex;gap:10px;margin-top:6px;">
            <a href="{{ route('student.map', 'time_quest') }}" class="eduplay-nav-btn eduplay-btn-map" style="flex:1;"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
            <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit" style="flex:1;"><i class="bi bi-box-arrow-right"></i> <span>Exit</span></a>
          </div>
        </div>
      </div>`;
    resOverlay.classList.remove('hidden');
    const againBtn = el('againBtn');
    if (againBtn) {
      againBtn.onclick = newGame;
      againBtn.focus({ preventScroll: true });
    }
  }
}

function newGame(){
  if (state.timerInt) clearInterval(state.timerInt);

  state.round = 0;
  state.score = 0;
  state.streak = 0;
  state.best = 0;
  state.coins = 50;
  state.target = null;
  state.answered = false;
  state.hintUsed = false;
  state.results = [];

  state.timeRemaining = 300;
  state.isPaused = false;
  const pauseOverlay = document.getElementById('pauseOverlay');
  if (pauseOverlay) pauseOverlay.classList.remove('active');
  const pauseBtn = document.getElementById('pauseBtn');
  if (pauseBtn){
    pauseBtn.classList.remove('paused');
    pauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
  }
  el('uiTime').textContent = '05:00';
  el('uiTime').style.color = '';
  state.timerInt = setInterval(() => {
    state.timeRemaining--;
    let m = Math.floor(state.timeRemaining / 60);
    let s = state.timeRemaining % 60;
    el('uiTime').textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;

    if (state.timeRemaining <= 30) {
      el('uiTime').style.color = 'var(--rose)';
    }

    if (state.timeRemaining <= 0) {
      clearInterval(state.timerInt);
      endGame(true);
    }
  }, 1000);

  el('overlay').classList.add('hidden');
  const resOverlay = el('resultsOverlay');
  if (resOverlay) resOverlay.classList.add('hidden');
  el('tutorialOverlay').classList.add('hidden');

  el('feedback').className = '';
  el('feedback').innerHTML = '';
  el('next').classList.remove('show');
  el('options').innerHTML = '';
  el('routeChip').innerHTML = '';

  targetLayer.clearLayers();
  arcLayer.clearLayers();
  answerLayer.clearLayers();
  drawManila();

  map.fitWorld({ animate: !prefersReducedMotion() });
  startRound();
}

/* ---------- Controls ---------- */
el('next').onclick = () => {
  if (!state.answered) return;
  startRound();
};

el('recenterBtn').onclick = () => {
  if (state.target) fitMap();
  else map.fitWorld({ animate: !prefersReducedMotion() });
};

document.addEventListener('keydown', e => {
  const tutOver = el('tutorialOverlay');
  const tutActive = tutOver && !tutOver.classList.contains('hidden');

  if (tutActive) {
    if (e.key === 'Escape') {
      closeTut(false);
      return;
    }
    if (e.key === 'ArrowRight' && currentTutStep < 5) {
      nextTut(currentTutStep + 1);
      return;
    }
    if (e.key === 'ArrowLeft' && currentTutStep > 1) {
      nextTut(currentTutStep - 1);
      return;
    }
    return;
  }

  const instrOver = el('overlay');
  const instrActive = instrOver && !instrOver.classList.contains('hidden');
  if (instrActive) {
    if (e.key === 'Enter') {
      newGame();
      return;
    }
    return;
  }

  if ((e.key === 'p' || e.key === 'P') && state.round > 0 && state.round <= TOTAL_ROUNDS){
    window.togglePause();
    return;
  }
  if (state.isPaused) return;
  if (e.key === 'Enter' || e.key === ' '){
    if (e.target && e.target.closest && e.target.closest('button, a')) return;
    if (state.answered && el('next').classList.contains('show')){
      e.preventDefault();
      el('next').click();
    }
    return;
  }
  const n = parseInt(e.key, 10);
  if (n >= 1 && n <= 4 && !state.answered){
    const btns = [...document.querySelectorAll('.option')];
    if (btns[n-1]) btns[n-1].click();
  }
});

window.togglePause = function(){
  if (state.round === 0 || state.round > TOTAL_ROUNDS) return;
  state.isPaused = !state.isPaused;
  const overlay = document.getElementById('pauseOverlay');
  const btn = document.getElementById('pauseBtn');
  if (state.isPaused){
    if (state.timerInt) clearInterval(state.timerInt);
    if (overlay) overlay.classList.add('active');
    if (btn){
      btn.classList.add('paused');
      btn.innerHTML = '<i class="bi bi-play-fill"></i> <span>Resume</span>';
    }
  } else {
    if (overlay) overlay.classList.remove('active');
    if (btn){
      btn.classList.remove('paused');
      btn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
    }
    state.timerInt = setInterval(() => {
      state.timeRemaining--;
      let m = Math.floor(state.timeRemaining / 60);
      let s = state.timeRemaining % 60;
      el('uiTime').textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
      if (state.timeRemaining <= 30) {
        el('uiTime').style.color = 'var(--rose)';
      }
      if (state.timeRemaining <= 0) {
        clearInterval(state.timerInt);
        endGame(true);
      }
    }, 1000);
  }
};

el('startBtn').onclick = () => {
  newGame();
};

/* ============================================================
   TUTORIAL & INSTRUCTION CONTROLLERS
   ============================================================ */
let tutOpenedFromGame = false;
let currentTutStep = 1;

window.openTut = function(fromGame = false) {
  tutOpenedFromGame = fromGame;

  if (fromGame) {
    if (state.timerInt) {
      clearInterval(state.timerInt);
      state.timerInt = null;
    }
    const skipBtns = document.querySelectorAll('.tq-tut-skip-btn');
    skipBtns.forEach(btn => btn.textContent = '← Return to Game');
    const startMissionBtn = el('tutStartMissionBtn');
    if (startMissionBtn) startMissionBtn.textContent = '▶ Return to Game';
  } else {
    const skipBtns = document.querySelectorAll('.tq-tut-skip-btn');
    skipBtns.forEach(btn => btn.textContent = '← Back to Instructions');
    const startMissionBtn = el('tutStartMissionBtn');
    if (startMissionBtn) startMissionBtn.textContent = '🚀 Start Mission!';
  }

  el('overlay').classList.add('hidden');
  const resOver = el('resultsOverlay');
  if (resOver) resOver.classList.add('hidden');

  el('tutorialOverlay').classList.remove('hidden');
  nextTut(1);
};

window.nextTut = function(step) {
  if (step < 1) step = 1;
  if (step > 5) step = 5;
  currentTutStep = step;

  for (let i = 1; i <= 5; i++) {
    const s = el('tutStep' + i);
    if (s) {
      s.classList.toggle('hidden', i !== step);
    }
  }

  const tabs = document.querySelectorAll('#tutStepper .tq-step-tab');
  tabs.forEach((tab, idx) => {
    tab.classList.toggle('active', idx === step - 1);
  });

  const activeStep = el('tutStep' + step);
  if (activeStep) {
    const primary = activeStep.querySelector('.btn:not(.ghost)');
    if (primary) primary.focus({ preventScroll: true });
  }
};

window.closeTut = function(andStartGame = false) {
  el('tutorialOverlay').classList.add('hidden');

  if (tutOpenedFromGame) {
    if (state.round > 0 && state.round <= TOTAL_ROUNDS && !state.isPaused) {
      state.timerInt = setInterval(() => {
        state.timeRemaining--;
        let m = Math.floor(state.timeRemaining / 60);
        let s = state.timeRemaining % 60;
        el('uiTime').textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        if (state.timeRemaining <= 30) {
          el('uiTime').style.color = 'var(--rose)';
        }
        if (state.timeRemaining <= 0) {
          clearInterval(state.timerInt);
          endGame(true);
        }
      }, 1000);
    }
    tutOpenedFromGame = false;
  } else {
    if (andStartGame) {
      newGame();
    } else {
      el('overlay').classList.remove('hidden');
    }
  }
};

/* Initial HUD */
updateHUD();
el('startBtn').focus({ preventScroll: true });

/* Keep map sized correctly */
setTimeout(() => map.invalidateSize(), 200);
window.addEventListener('resize', () => map.invalidateSize());
if ('ResizeObserver' in window){
  new ResizeObserver(() => map.invalidateSize()).observe(el('map'));
}
</script>
@endsection