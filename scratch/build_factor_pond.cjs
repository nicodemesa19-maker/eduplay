const fs = require('fs');

const code = `@extends('layouts.app')
@section('hide-nav', true)
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700;800;900&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{width:100%;height:100%;overflow:hidden;font-family:'Inter',sans-serif;}
#gw{position:fixed;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;overflow:hidden;z-index:10;background:linear-gradient(180deg,#0d5c63 0%,#0a4a52 40%,#052d33 100%);}
#gw::before{content:'';position:absolute;inset:0;pointer-events:none;z-index:0;background:repeating-linear-gradient(90deg,rgba(255,255,255,.025) 0px,rgba(255,255,255,.06) 20px,transparent 40px,transparent 80px);animation:shimmer 14s linear infinite;}
@keyframes shimmer{0%{transform:translateX(-80px)}100%{transform:translateX(80px)}}
.orb{position:absolute;border-radius:50%;pointer-events:none;filter:blur(70px);z-index:0;}
.orb1{width:450px;height:350px;background:radial-gradient(#00c9b1,transparent);top:-80px;left:-100px;opacity:.18;animation:orbD 22s ease-in-out infinite alternate;}
.orb2{width:380px;height:280px;background:radial-gradient(#0891b2,transparent);bottom:0;right:-80px;opacity:.2;animation:orbD 18s ease-in-out infinite alternate-reverse;}
.orb3{width:260px;height:200px;background:radial-gradient(#14b8a6,transparent);bottom:30%;left:10%;opacity:.12;animation:orbD 15s ease-in-out infinite alternate;}
@keyframes orbD{from{transform:translate(0,0)}to{transform:translate(24px,16px)}}

#gw>*:not(.orb):not(.overlay):not(.pause-overlay):not(#wrongOverlay){position:relative;z-index:1;}

@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
@keyframes flt{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
@keyframes popIn{from{transform:scale(0)}to{transform:scale(1)}}

/* ============================================================
   OVERLAY SYSTEM — ALWAYS ON TOP, ZERO SCROLLBARS
   ============================================================ */
.overlay {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(2, 14, 20, 0.94) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    z-index: 99999 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 16px !important;
    animation: fadeUp 0.25s cubic-bezier(.34,1.56,.64,1);
    overflow: hidden !important; /* NO OUTER SCROLLBAR */
}
.overlay.hidden {
    display: none !important;
}

/* Modal Cards — Compact, Non-Scrollable */
.tq-instr-card, .tut-card {
    max-width: 800px !important;
    width: 95% !important;
    margin: auto !important;
    background: linear-gradient(165deg, rgba(8, 38, 46, 0.98) 0%, rgba(4, 20, 26, 0.99) 100%) !important;
    border: 1.5px solid rgba(94, 234, 212, 0.45) !important;
    border-radius: 26px !important;
    padding: 24px 32px 20px !important;
    box-shadow: 0 30px 90px rgba(0, 0, 0, 0.9), 0 0 50px rgba(20, 184, 166, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.15) !important;
    position: relative !important;
    overflow: hidden !important; /* NO INNER SCROLLBAR */
    text-align: center !important;
}
.tq-instr-card::before, .tut-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3.5px;
    background: linear-gradient(90deg, #5eead4 0%, #22d3ee 35%, #fbbf24 70%, #f43f5e 100%);
}

.tq-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(20, 184, 166, 0.18);
    border: 1px solid rgba(94, 234, 212, 0.4);
    border-radius: 99px;
    padding: 4px 14px;
    font-size: 0.74rem;
    font-weight: 800;
    color: #5eead4;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}

.tq-instr-title {
    font-family: 'Space Grotesk', sans-serif !important;
    font-size: 1.95rem !important;
    font-weight: 900 !important;
    background: linear-gradient(135deg, #ffffff 0%, #5eead4 50%, #fbbf24 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 6px 0 4px !important;
    letter-spacing: -0.5px;
}
.tq-instr-sub {
    color: rgba(204, 251, 241, 0.85);
    font-size: 0.95rem;
    line-height: 1.45;
    margin: 0 auto 16px;
    max-width: 600px;
}

/* 3-Column Simple Feature Cards */
.tq-features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 16px;
    text-align: left;
}
.tq-feat-card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.09);
    border-radius: 16px;
    padding: 14px 14px 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.tq-feat-head {
    display: flex;
    align-items: center;
    gap: 8px;
}
.tq-feat-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
}
.tq-feat-card.blue  .tq-feat-icon { background: rgba(34, 211, 238, 0.16); border: 1px solid rgba(34, 211, 238, 0.35); }
.tq-feat-card.gold  .tq-feat-icon { background: rgba(251, 191, 36, 0.16); border: 1px solid rgba(251, 191, 36, 0.35); }
.tq-feat-card.green .tq-feat-icon { background: rgba(52, 211, 153, 0.16); border: 1px solid rgba(52, 211, 153, 0.35); }

.tq-feat-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.95rem;
    font-weight: 800;
    color: #ffffff;
}
.tq-feat-desc {
    font-size: 0.82rem;
    color: rgba(204, 251, 241, 0.8);
    line-height: 1.4;
    margin: 0;
}
.tq-feat-desc b { color: #fff; font-weight: 700; }

/* 2-Column Simple Concept Cards */
.tq-concepts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 16px;
    text-align: left;
}
.tq-concept-card {
    background: rgba(8, 48, 56, 0.65);
    border: 1px solid rgba(94, 234, 212, 0.24);
    border-radius: 14px;
    padding: 11px 14px;
}
.tq-c-tag {
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #5eead4;
    margin-bottom: 2px;
}
.tq-c-title {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.92rem;
    font-weight: 900;
    color: #fff;
    margin-bottom: 2px;
}
.tq-c-desc {
    font-size: 0.8rem;
    color: rgba(204, 251, 241, 0.8);
    line-height: 1.35;
    margin-bottom: 4px;
}
.tq-c-example {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(20, 184, 166, 0.18);
    border: 1px solid rgba(20, 184, 166, 0.3);
    border-radius: 6px;
    padding: 2px 8px;
    font-size: 0.74rem;
    font-weight: 700;
    color: #5eead4;
}

/* Action Footer */
.tq-instr-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-top: 14px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    flex-wrap: wrap;
}
.tq-stats-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}
.tq-stat-pill {
    background: rgba(255, 255, 255, 0.045);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 99px;
    padding: 5px 12px;
    font-size: 0.8rem;
    font-weight: 700;
    color: rgba(204, 251, 241, 0.88);
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.tq-stat-pill b { color: #5eead4; }

.tq-actions-row {
    display: flex;
    gap: 8px;
    align-items: center;
    flex: 1;
    justify-content: flex-end;
}
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-family: 'Space Grotesk', sans-serif;
    font-weight: 800;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(.34,1.56,.64,1);
    text-decoration: none;
}
.btn.go {
    background: linear-gradient(135deg, #0d9488 0%, #0891b2 100%);
    color: #fff;
    box-shadow: 0 5px 18px rgba(13, 148, 136, 0.45);
}
.btn.go:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(13, 148, 136, 0.65);
}
.btn.ghost {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.15);
}
.btn.ghost:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
    transform: translateY(-1px);
}

/* ============================================================
   TUTORIAL MODAL (5 Focused Slides, No Scrollbars)
   ============================================================ */
.tq-tut-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
    gap: 8px;
}
.tq-tut-close-btn {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 10px;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(204, 251, 241, 0.75);
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.2s ease;
}
.tq-tut-close-btn:hover {
    background: rgba(239, 68, 68, 0.25);
    border-color: rgba(239, 68, 68, 0.5);
    color: #fff;
}

/* 5 Tabs with NO HORIZONTAL SCROLLBAR */
.tq-stepper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    margin-bottom: 16px;
    background: rgba(0, 0, 0, 0.35);
    border: 1px solid rgba(94, 234, 212, 0.2);
    border-radius: 13px;
    padding: 4px;
    width: 100%;
    overflow: hidden; /* ABSOLUTELY NO SCROLLBAR */
}
.tq-step-tab {
    flex: 1;
    background: transparent;
    border: none;
    border-radius: 9px;
    padding: 6px 4px;
    font-size: 0.78rem;
    font-weight: 700;
    color: rgba(204, 251, 241, 0.65);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.tq-step-tab:hover {
    color: #fff;
    background: rgba(255, 255, 255, 0.06);
}
.tq-step-tab.active {
    background: linear-gradient(135deg, rgba(20, 184, 166, 0.35), rgba(8, 145, 178, 0.25));
    border: 1px solid rgba(94, 234, 212, 0.5);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(20, 184, 166, 0.25);
}
.tq-step-tab .tq-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    font-size: 0.68rem;
    font-weight: 800;
}
.tq-step-tab.active .tq-num {
    background: #5eead4;
    color: #041f24;
}

.tq-slide-body {
    min-height: 220px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.tq-tut-slide-title {
    font-family: 'Space Grotesk', sans-serif !important;
    font-size: 1.55rem !important;
    font-weight: 900 !important;
    margin: 0 0 4px !important;
    letter-spacing: -0.3px;
}
.tq-tut-slide-text {
    font-size: 0.92rem;
    line-height: 1.45;
    color: rgba(204, 251, 241, 0.85);
    max-width: 580px;
    margin: 0 auto 12px;
}

/* Comparison and flow cards */
.tq-compare-hero {
    display: flex;
    align-items: center;
    justify-content: space-around;
    background: rgba(0, 0, 0, 0.32);
    border: 1.5px solid rgba(94, 234, 212, 0.2);
    border-radius: 16px;
    padding: 12px 18px;
    margin: 0 auto 12px;
    max-width: 580px;
    width: 100%;
}
.tq-compare-box {
    text-align: center;
    flex: 1;
}
.tq-compare-box .c-flag { font-size: 1.7rem; margin-bottom: 2px; }
.tq-compare-box .c-label { font-size: 0.78rem; font-weight: 700; color: rgba(204, 251, 241, 0.7); margin-bottom: 2px; }
.tq-compare-box .c-math { font-family: 'Space Grotesk', sans-serif; font-size: 1.1rem; font-weight: 900; color: #fff; }
.tq-compare-box .c-tag { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 800; margin-top: 3px; }
.tq-compare-box .c-tag.good { background: rgba(52, 211, 153, 0.2); color: #34d399; }
.tq-compare-box .c-tag.bad { background: rgba(248, 113, 113, 0.2); color: #f87171; }
.tq-compare-mid {
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.95rem;
    font-weight: 800;
    color: rgba(255, 255, 255, 0.35);
    padding: 0 12px;
}

.tq-takeaway-note {
    background: rgba(20, 184, 166, 0.1);
    border: 1px solid rgba(94, 234, 212, 0.3);
    border-radius: 10px;
    padding: 8px 14px;
    font-size: 0.85rem;
    color: #ccfbf1;
    margin: 0 auto 12px;
    max-width: 580px;
    line-height: 1.4;
}
.tq-takeaway-note b { color: #5eead4; }

.tq-tips-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    margin: 0 auto 12px;
    max-width: 580px;
    text-align: center;
}
.tq-tip-box {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 10px 8px;
}
.tq-tip-ico { font-size: 1.4rem; margin-bottom: 2px; }
.tq-tip-tit { font-family: 'Space Grotesk', sans-serif; font-size: 0.85rem; font-weight: 800; color: #fff; margin-bottom: 2px; }
.tq-tip-txt { font-size: 0.75rem; color: rgba(204, 251, 241, 0.75); line-height: 1.35; }

.tq-tut-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding-top: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}
.tq-tut-skip-btn {
    background: transparent;
    border: none;
    color: rgba(94, 234, 212, 0.65);
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: underline;
    text-underline-offset: 3px;
    transition: color 0.15s;
}
.tq-tut-skip-btn:hover { color: #5eead4; }

/* Demo Fish in Tutorial */
.demo-strip{display:flex;gap:12px;justify-content:center;margin:8px 0 10px;}
.demo-fish{width:64px;height:64px;border-radius:50%;background:rgba(20,184,166,.15);border:2px solid rgba(94,234,212,.4);display:flex;align-items:center;justify-content:center;font-family:'Space Grotesk',sans-serif;font-size:20px;font-weight:900;color:#67e8f9;flex-direction:column;gap:2px;}
.demo-fish.selected{background:rgba(251,191,36,.22);border-color:rgba(251,191,36,.8);color:#fbbf24;box-shadow:0 0 14px rgba(251,191,36,.45);}
.demo-fish.correct{background:rgba(16,185,129,.22);border-color:rgba(52,211,153,.8);color:#34d399;}
.demo-fish.wrong{background:rgba(239,68,68,.2);border-color:rgba(248,113,113,.75);color:#f87171;}
.demo-fish-lbl{font-size:10px;font-weight:800;color:rgba(255,255,255,.55);text-align:center;margin-top:3px;}
.demo-fish-wrap{display:flex;flex-direction:column;align-items:center;}

/* ============================================================
   GAME SCREEN & HUD (Upper-Right Button Group, No Restart)
   ============================================================ */
#gameScreen{display:none;width:100%;height:100%;flex-direction:column;align-items:center;justify-content:center;padding:12px 18px;}
.gi{width:100%;max-width:1280px;display:flex;flex-direction:column;gap:10px;}

#hud {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    background: linear-gradient(135deg, rgba(8, 48, 56, 0.94), rgba(5, 28, 44, 0.94));
    border: 1.5px solid rgba(94, 234, 212, 0.32);
    border-radius: 18px;
    padding: 10px 20px;
    backdrop-filter: blur(22px);
    box-shadow: 0 10px 32px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.08);
    position: relative;
    overflow: hidden;
}
#hud::before{content:'';position:absolute;top:0;left:0;right:0;height:1.5px;background:linear-gradient(90deg,transparent,rgba(94,234,212,.7),transparent);opacity:.7;}

.hp{display:flex;flex-direction:column;gap:2px;}
.hl{font-size:9px;font-weight:800;letter-spacing:2px;text-transform:uppercase;background:linear-gradient(90deg,#5eead4,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.hv{font-size:20px;font-weight:900;color:#fff;line-height:1;font-family:'Space Grotesk',sans-serif;letter-spacing:-.02em;}
.hv.rose{font-size:18px;letter-spacing:2px;line-height:1.2;}

.hc{display:flex;flex-direction:column;align-items:center;gap:3px;}
.lvlbadge{display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,rgba(20,184,166,.28),rgba(8,145,178,.22));border:1.5px solid rgba(94,234,212,.55);border-radius:99px;padding:4px 16px;font-size:12.5px;font-weight:900;color:#ccfbf1;font-family:'Space Grotesk',sans-serif;letter-spacing:.5px;box-shadow:0 4px 14px rgba(13,148,136,.3),inset 0 1px 0 rgba(255,255,255,.15);text-shadow:0 0 10px rgba(94,234,212,.6);}
.hs-sub{font-size:10px;color:rgba(204,251,241,.65);font-weight:700;letter-spacing:1px;text-transform:uppercase;}
.score-pill-chip{color:#fbbf24;font-weight:900;font-size:11px;margin-left:4px;}

/* Upper-Right Buttons Group */
.eduplay-game-nav {
    display: flex;
    gap: 7px;
    align-items: center;
    flex-shrink: 0;
}
.eduplay-nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 7px 12px;
    border-radius: 11px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid transparent;
    cursor: pointer;
    transition: all .18s cubic-bezier(.34,1.56,.64,1);
    text-decoration: none;
    font-family: 'Inter', sans-serif;
    white-space: nowrap;
}
.eduplay-nav-btn:hover {
    transform: translateY(-2px);
}
.eduplay-nav-btn:active {
    transform: scale(.96);
}
.eduplay-btn-help {
    background: rgba(34, 211, 238, 0.15);
    color: #67e8f9;
    border-color: rgba(34, 211, 238, 0.35);
}
.eduplay-btn-help:hover {
    background: rgba(34, 211, 238, 0.28);
    box-shadow: 0 6px 18px rgba(34, 211, 238, 0.3);
    color: #fff;
}
.eduplay-btn-pause {
    background: rgba(20, 184, 166, 0.15);
    color: #5eead4;
    border-color: rgba(20, 184, 166, 0.35);
}
.eduplay-btn-pause:hover {
    background: rgba(20, 184, 166, 0.28);
    box-shadow: 0 6px 18px rgba(20, 184, 166, 0.3);
    color: #fff;
}
.eduplay-btn-pause.paused {
    background: rgba(251, 191, 36, 0.2);
    color: #fde68a;
    border-color: rgba(251, 191, 36, 0.45);
}
.eduplay-btn-fs {
    background: rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-color: rgba(255, 255, 255, 0.16);
}
.eduplay-btn-fs:hover {
    background: rgba(255, 255, 255, 0.16);
    color: #fff;
}
.eduplay-btn-map {
    background: rgba(255, 255, 255, 0.08);
    color: rgba(255, 255, 255, 0.8);
    border-color: rgba(255, 255, 255, 0.16);
}
.eduplay-btn-map:hover {
    background: rgba(255, 255, 255, 0.16);
    color: #fff;
}
.eduplay-btn-exit {
    background: rgba(239, 68, 68, 0.15);
    color: #fca5a5;
    border-color: rgba(239, 68, 68, 0.35);
}
.eduplay-btn-exit:hover {
    background: rgba(239, 68, 68, 0.28);
    color: #fff;
    box-shadow: 0 6px 18px rgba(239, 68, 68, 0.3);
}

#ptrack{position:relative;width:100%;height:10px;background:rgba(0,0,0,.35);border-radius:99px;overflow:visible;border:1px solid rgba(94,234,212,.16);box-shadow:inset 0 2px 6px rgba(0,0,0,.5);}
#pfill{height:100%;width:0%;background:linear-gradient(90deg,#0d9488,#22d3ee,#67e8f9);border-radius:99px;transition:width .5s cubic-bezier(.34,1.56,.64,1);box-shadow:0 0 14px rgba(34,211,238,.7),inset 0 1px 0 rgba(255,255,255,.4);position:relative;}
#pfill::after{content:'';position:absolute;right:-1px;top:50%;transform:translateY(-50%);width:12px;height:12px;border-radius:50%;background:#67e8f9;box-shadow:0 0 10px rgba(103,232,249,1),0 0 20px rgba(34,211,238,.7);transition:opacity .3s;}
#pmeta{display:flex;justify-content:space-between;font-size:10px;color:rgba(255,255,255,.45);margin-top:4px;font-weight:700;letter-spacing:.5px;padding:0 4px;}

#mission{background:linear-gradient(135deg,rgba(8,48,56,.92),rgba(5,28,44,.92));border:1.5px solid rgba(94,234,212,.32);border-radius:16px;padding:11px 18px;display:flex;align-items:center;justify-content:space-between;gap:14px;backdrop-filter:blur(18px);box-shadow:0 10px 30px rgba(0,0,0,.35),inset 0 1px 0 rgba(255,255,255,.07);position:relative;overflow:hidden;}
#mission::before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;background:linear-gradient(180deg,#22d3ee,#0d9488);opacity:.85;}
.mission-info{display:flex;flex-direction:column;gap:2px;padding-left:6px;}
.mission-tag{font-size:9px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#5eead4;}
.mission-txt{font-size:15.5px;font-weight:800;color:#fff;text-shadow:0 0 16px rgba(94,234,212,.45);font-family:'Space Grotesk',sans-serif;letter-spacing:-.01em;}
#castBtn{background:linear-gradient(135deg,#f97316,#ef4444);color:#fff;font-size:13px;font-weight:900;padding:10px 24px;border-radius:11px;border:none;cursor:pointer;box-shadow:0 5px 18px rgba(249,115,22,.5),inset 0 1px 0 rgba(255,255,255,.25);transition:transform .15s cubic-bezier(.34,1.56,.64,1),box-shadow .15s;font-family:'Space Grotesk',sans-serif;letter-spacing:.3px;white-space:nowrap;flex-shrink:0;}
#castBtn:hover{transform:translateY(-2px) scale(1.03);box-shadow:0 8px 24px rgba(249,115,22,.7),inset 0 1px 0 rgba(255,255,255,.3);}
#castBtn:active{transform:scale(.97);}
#castBtn:disabled{opacity:.5;cursor:not-allowed;transform:none;}

/* ============================================================
   POND & CALIBRATED SWEET-SPOT FISH (A little smaller & graceful)
   ============================================================ */
#pond{position:relative;width:100%;height:clamp(340px, 60vh, 580px);border-radius:24px;overflow:hidden;background:radial-gradient(ellipse at 30% 22%,rgba(120,255,240,.10),transparent 55%),radial-gradient(ellipse at 78% 78%,rgba(0,220,220,.16),transparent 55%),linear-gradient(180deg,#0e9aa6 0%,#0a7682 30%,#065560 60%,#023a44 100%);border:2px solid rgba(94,234,212,.28);box-shadow:0 22px 60px rgba(0,30,30,.7),0 0 0 1px rgba(94,234,212,.08) inset,0 0 90px rgba(34,211,238,.14),inset 0 0 70px rgba(0,0,0,.28),inset 0 8px 30px rgba(255,255,255,.06);}
#pond::before{content:'';position:absolute;inset:0;z-index:0;pointer-events:none;background:radial-gradient(ellipse 160px 50px at 22% 42%,rgba(255,255,255,.09),transparent),radial-gradient(ellipse 110px 40px at 62% 26%,rgba(255,255,255,.07),transparent),radial-gradient(ellipse 140px 45px at 82% 62%,rgba(255,255,255,.09),transparent),radial-gradient(ellipse 130px 40px at 40% 82%,rgba(255,255,255,.06),transparent);animation:caustic 8s ease-in-out infinite alternate;}
#pond::after{content:'';position:absolute;inset:0;z-index:1;pointer-events:none;background:radial-gradient(ellipse at 50% 120%,transparent 45%,rgba(0,10,18,.45) 100%);}

.pond-decor{position:absolute;inset:0;pointer-events:none;z-index:1;overflow:hidden;}
.pond-decor svg{display:block;}
.pond-decor .rays{position:absolute;inset:-10% -10% 0 -10%;background:linear-gradient(118deg, rgba(255,255,255,0) 34%, rgba(255,255,255,.055) 36%, rgba(255,255,255,0) 38%),linear-gradient(118deg, rgba(255,255,255,0) 52%, rgba(255,255,255,.04) 54%, rgba(255,255,255,0) 56%),linear-gradient(118deg, rgba(255,255,255,0) 68%, rgba(255,255,255,.05) 70%, rgba(255,255,255,0) 72%),linear-gradient(118deg, rgba(255,255,255,0) 82%, rgba(255,255,255,.03) 84%, rgba(255,255,255,0) 86%);animation:raysShift 11s ease-in-out infinite alternate;mix-blend-mode:screen;}
@keyframes raysShift{0%{opacity:.55;transform:translateX(-14px);}100%{opacity:1;transform:translateX(14px);}}
.reed{position:absolute;bottom:0;width:6px;border-radius:50% 50% 6px 6px / 40px 40px 2px 2px;transform-origin:bottom center;background:linear-gradient(to top,rgba(6,40,26,.9) 0%,rgba(20,80,48,.85) 40%,rgba(52,130,72,.75) 75%,rgba(90,170,100,.55) 100%);animation:reedSway var(--d,4s) ease-in-out infinite alternate;animation-delay:var(--dl,0s);filter:blur(.3px);pointer-events:none;}
@keyframes reedSway{from{transform:rotate(-7deg) skewX(3deg);}to{transform:rotate(7deg) skewX(-3deg);}}
.lily{position:absolute;pointer-events:none;animation:lilySway var(--ls,7s) ease-in-out infinite alternate;animation-delay:var(--ld,0s);filter:drop-shadow(0 5px 10px rgba(0,0,0,.45)) drop-shadow(0 0 4px rgba(0,0,0,.3));transform-origin:50% 50%;opacity:.97;}
@keyframes lilySway{from{transform:translateY(0) rotate(-3deg);}to{transform:translateY(-4px) rotate(3deg);}}
.pond-decor .rocks{position:absolute;left:0;right:0;bottom:0;width:100%;height:70px;opacity:.9;}

/* Ambient Creatures */
.turtle{position:absolute;width:86px;height:64px;pointer-events:none;z-index:2;opacity:.95;filter:drop-shadow(0 8px 16px rgba(0,0,0,.45)) drop-shadow(0 0 8px rgba(0,0,0,.25));animation:turtleSwim var(--tdur,44s) linear infinite;animation-delay:var(--tdelay,0s);will-change:left,transform;}
.turtle-svg{width:100%;height:100%;display:block;overflow:visible;}
.turtle-flipper{transform-box:fill-box;transform-origin:right center;animation:turtleFlap 2.6s ease-in-out infinite alternate;animation-delay:var(--flapDelay,0s);}
@keyframes turtleFlap{from{transform:rotate(-22deg) scale(.9,1);}to{transform:rotate(14deg) scale(1.05,1);}}
@keyframes turtleSwim{
    0%   {left:-110px;transform:translateY(0) scaleX(1);}
    25%  {transform:translateY(-10px) scaleX(1);}
    49.9%{left:calc(100% + 20px);transform:translateY(0) scaleX(1);}
    50%  {left:calc(100% + 20px);transform:translateY(0) scaleX(-1);}
    75%  {transform:translateY(-10px) scaleX(-1);}
    99.9%{left:-110px;transform:translateY(0) scaleX(-1);}
    100% {left:-110px;transform:translateY(0) scaleX(1);}
}
.dragonfly{position:absolute;width:38px;height:30px;pointer-events:none;z-index:4;filter:drop-shadow(0 4px 8px rgba(0,0,0,.4));animation:dragonflyHover var(--ddur,7s) ease-in-out infinite;animation-delay:var(--ddelay,0s);}
.dragonfly-svg{width:100%;height:100%;display:block;overflow:visible;}
.df-wing{transform-box:fill-box;transform-origin:50% 50%;animation:dfWing .1s linear infinite alternate;}
.df-wing-2,.df-wing-4{animation-delay:-.05s;}
@keyframes dfWing{from{transform:scaleY(.6) scaleX(.94);opacity:.65;}to{transform:scaleY(1.08) scaleX(1.02);opacity:1;}}
@keyframes dragonflyHover{
    0%,100%{transform:translate(0,0) rotate(0deg);}
    20%{transform:translate(28px,-15px) rotate(5deg);}
    40%{transform:translate(-18px,-22px) rotate(-6deg);}
    60%{transform:translate(22px,13px) rotate(7deg);}
    80%{transform:translate(-26px,9px) rotate(-5deg);}
}
.snail{position:absolute;width:48px;height:36px;pointer-events:none;z-index:2;filter:drop-shadow(0 4px 6px rgba(0,0,0,.55));}
.snail-svg{width:100%;height:100%;display:block;overflow:visible;}
.snail-ant{transform-box:fill-box;transform-origin:0% 100%;animation:antWiggle 2.2s ease-in-out infinite alternate;}
@keyframes antWiggle{from{transform:rotate(-8deg);}to{transform:rotate(8deg);}}

/* ═══════════════════════════════════════
   🐟 CALIBRATED FISH: ~125px (A little smaller, graceful & clear)
   ═══════════════════════════════════════ */
.fish {
    position: absolute;
    left: -62px;
    top: -44px;
    width: 125px;
    height: 88px;
    z-index: 2;
    cursor: pointer;
    user-select: none;
    will-change: transform;
    transition: opacity .3s ease;
}
.fish--sm {
    left: -48px;
    top: -34px;
    width: 96px;
    height: 68px;
}
.fish--decor {
    left: -36px;
    top: -26px;
    width: 72px;
    height: 52px;
    z-index: 1;
    cursor: default;
    pointer-events: none;
    opacity: .45;
}
.fish--decor .fish-svg {
    filter: drop-shadow(0 3px 6px rgba(0,0,0,.35)) saturate(.55) brightness(.85);
}
.fish--decor.hidden { display: none; }

.fish-flip {
    position: relative;
    width: 100%;
    height: 100%;
    will-change: transform;
    display: flex;
    align-items: center;
    justify-content: center;
}
.fish-svg {
    width: 100%;
    height: 100%;
    display: block;
    overflow: visible;
    filter: drop-shadow(0 6px 12px rgba(0,0,0,.45)) drop-shadow(0 0 14px rgba(255,255,255,.12));
    transition: filter .22s, transform .22s;
}
.fish:hover .fish-svg {
    filter: drop-shadow(0 10px 20px rgba(0,0,0,.55)) drop-shadow(0 0 24px rgba(255,255,255,.35));
    transform: scale(1.08);
}

.fish-num {
    position: absolute;
    top: 50%;
    left: 47%;
    transform: translate(-50%, -50%);
    font-family: 'Space Grotesk', sans-serif;
    font-size: 24px;
    font-weight: 900;
    color: #ffffff;
    letter-spacing: -0.02em;
    pointer-events: none;
    z-index: 3;
    white-space: nowrap;
    line-height: 1;
    text-shadow: 
        0 0 5px #000,
        0 0 10px rgba(0,0,0,0.95),
        2px 2px 0 #000,
        -2px -2px 0 #000,
        2px -2px 0 #000,
        -2px 2px 0 #000,
        0 2px 0 #000,
        0 -2px 0 #000,
        2px 0 0 #000,
        -2px 0 0 #000;
}
.fish--sm .fish-num {
    font-size: 18px;
}

.fish.picked .fish-svg {
    filter: drop-shadow(0 0 18px rgba(251,191,36,1)) drop-shadow(0 0 10px rgba(251,191,36,.85));
    transform: scale(1.1);
}
.fish.picked .fish-num {
    color: #fef08a;
    text-shadow: 
        0 0 14px rgba(251,191,36,1),
        0 0 5px #000,
        2px 2px 0 #000,
        -2px -2px 0 #000,
        2px -2px 0 #000,
        -2px 2px 0 #000;
}
.fish.correctReveal .fish-svg {
    filter: drop-shadow(0 0 22px rgba(52,211,153,.95));
    animation: correctB .5s ease;
}
.fish.missedReveal  .fish-svg {
    filter: drop-shadow(0 0 18px rgba(239,68,68,.9));
    animation: shakeF .4s ease;
}
.fish.wrongPick     .fish-svg {
    filter: drop-shadow(0 0 18px rgba(239,68,68,.9)) grayscale(.5);
    animation: shakeF .4s ease;
}
@keyframes correctB{0%,100%{transform:translateY(0) scale(1)}40%{transform:translateY(-12px) scale(1.18)}70%{transform:translateY(-4px) scale(1.08)}}
@keyframes shakeF{0%,100%{transform:translateX(0)}25%{transform:translateX(-7px)}75%{transform:translateX(7px)}}

.f-tail{transform-box:fill-box;transform-origin:0% 50%;animation:tailWag var(--wagdur,.45s) cubic-bezier(.45,0,.55,1) infinite alternate;}
@keyframes tailWag{from{transform:rotate(-18deg) skewY(2deg);}to{transform:rotate(18deg) skewY(-2deg);}}
.f-dorsal{transform-box:fill-box;transform-origin:50% 100%;animation:dorsalWave var(--wagdur,.45s) ease-in-out infinite alternate;}
@keyframes dorsalWave{from{transform:rotate(-4deg) scaleY(.94);}to{transform:rotate(4deg) scaleY(1.10);}}
.f-pectoral{transform-box:fill-box;transform-origin:30% 0%;animation:finFlutter .85s ease-in-out infinite alternate;}
@keyframes finFlutter{from{transform:rotate(-14deg) scaleX(.92);}to{transform:rotate(20deg) scaleX(1.06);}}
.f-body{transform-box:fill-box;transform-origin:50% 50%;animation:bodyBreathe 1.4s ease-in-out infinite alternate;}
@keyframes bodyBreathe{from{transform:scaleY(1);}to{transform:scaleY(1.03);}}

.bubble{position:absolute;bottom:0;border-radius:50%;background:radial-gradient(circle at 35% 35%,rgba(255,255,255,.6),rgba(255,255,255,.15));border:1px solid rgba(255,255,255,.3);animation:rise linear infinite;z-index:1;pointer-events:none;}
@keyframes rise{0%{bottom:0;opacity:.7}100%{bottom:100%;opacity:0}}
.ripple{position:absolute;border-radius:50%;border:2.5px solid rgba(255,255,255,.55);width:20px;height:20px;transform:translate(-50%,-50%);animation:rippleO .8s ease forwards;pointer-events:none;z-index:5;}
@keyframes rippleO{0%{width:20px;height:20px;opacity:.8}100%{width:75px;height:75px;opacity:0}}
.splash{position:fixed;pointer-events:none;z-index:300;font-family:'Space Grotesk',sans-serif;font-size:22px;font-weight:900;color:#fbbf24;text-shadow:0 0 18px rgba(251,191,36,.8);animation:splashP .8s ease forwards;transform:translate(-50%,-50%);}
@keyframes splashP{0%{opacity:1;transform:translate(-50%,-50%) scale(.5)}40%{opacity:1;transform:translate(-50%,-80%) scale(1.15)}100%{opacity:0;transform:translate(-50%,-140%) scale(.8)}}

/* Pause Overlay */
.pause-overlay {
    display: none; position: fixed !important; inset: 0 !important; background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
    z-index: 9998 !important; align-items: center; justify-content: center; flex-direction: column;
}
.pause-overlay.active { display: flex !important; }
.pause-card {
    background: linear-gradient(135deg, #082833 0%, #03161c 100%);
    border: 1.5px solid rgba(94, 234, 212, 0.45); border-radius: 24px;
    padding: 32px 28px; text-align: center; max-width: 390px; width: 90%;
    box-shadow: 0 20px 60px rgba(0,0,0,0.7), 0 0 30px rgba(94, 234, 212, 0.2); color: #fff;
}
.pause-card h2 { color: #5eead4; font-size: 1.7rem; font-weight: 800; margin: 0 0 6px; font-family: 'Space Grotesk', sans-serif; }
.pause-card p { color: rgba(204, 251, 241, 0.7); margin: 0 0 16px; font-size: 0.9rem; }

/* End Banner */
#endBanner{display:none;width:100%;}
#endBanner.show{display:block;animation:bannerDrop .5s cubic-bezier(.34,1.56,.64,1);}
@keyframes bannerDrop{from{opacity:0;transform:translateY(-20px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}
.bnr-box{background:linear-gradient(135deg,rgba(5,35,40,.96),rgba(5,25,50,.96));border:1.5px solid rgba(20,184,166,.5);border-radius:18px;padding:14px 18px;display:flex;align-items:center;gap:14px;box-shadow:0 8px 40px rgba(0,0,0,.7),inset 0 1px 0 rgba(255,255,255,.06);position:relative;overflow:hidden;}
.bnr-box::before{content:'';position:absolute;inset:0;background:linear-gradient(90deg,rgba(13,148,136,.08),transparent 40%,rgba(34,211,238,.06) 100%);pointer-events:none;}
.bnr-left{display:flex;flex-direction:column;align-items:center;gap:2px;flex-shrink:0;}
.bnr-ico{font-size:32px;line-height:1;animation:popIn .4s cubic-bezier(.34,1.56,.64,1);}
.bnr-stars{font-size:15px;letter-spacing:3px;}
.bnr-mid{flex:1;min-width:0;}
.bnr-title{font-family:'Space Grotesk',sans-serif;font-size:1.02rem;font-weight:900;color:#fff;margin-bottom:2px;text-shadow:0 0 14px rgba(94,234,212,.6);}
.bnr-msg{font-size:.76rem;color:rgba(255,255,255,.6);line-height:1.4;}
.bnr-btns{display:flex;gap:8px;flex-shrink:0;}
.bbtn{padding:8px 14px;border-radius:10px;font-size:.76rem;font-weight:700;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:5px;transition:all .2s;text-decoration:none;white-space:nowrap;}
.bbtn:active{transform:scale(.97);}
.bbtn-p{background:linear-gradient(135deg,#0d9488,#0891b2);color:#fff;box-shadow:0 4px 14px rgba(13,148,136,.5);}
.bbtn-p:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(13,148,136,.7);}

/* Wrong Answer Modal */
#wrongOverlay{display:none;position:fixed !important;inset:0 !important;background:rgba(2,15,18,.68);z-index:99990 !important;align-items:center;justify-content:center;padding:20px;}
#wrongOverlay.show{display:flex !important;animation:fadeIn .25s ease;}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
.wrong-box{background:linear-gradient(135deg,rgba(28,14,14,.97),rgba(40,18,18,.97));border:1.5px solid rgba(248,113,113,.55);border-radius:24px;padding:22px 24px 18px;text-align:center;max-width:440px;width:100%;box-shadow:0 0 80px rgba(120,20,20,.55),inset 0 1px 0 rgba(255,255,255,.08);animation:wrongPop .42s cubic-bezier(.34,1.56,.64,1);position:relative;overflow:hidden;}
@keyframes wrongPop{from{opacity:0;transform:translateY(-20px) scale(.94)}to{opacity:1;transform:translateY(0) scale(1)}}
.wrong-box::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,rgba(248,113,113,.9),transparent);}
.wrong-ico{font-size:44px;line-height:1;margin-bottom:4px;animation:wrongShake .55s ease;}
@keyframes wrongShake{0%,100%{transform:translateX(0);}20%{transform:translateX(-8px) rotate(-6deg);}40%{transform:translateX(8px) rotate(6deg);}60%{transform:translateX(-5px) rotate(-3deg);}80%{transform:translateX(5px) rotate(3deg);}}
.wrong-title{font-family:'Space Grotesk',sans-serif;font-size:1.35rem;font-weight:900;color:#fecaca;letter-spacing:-.01em;margin-bottom:3px;text-shadow:0 0 16px rgba(239,68,68,.6);}
.wrong-sub{font-size:11.5px;color:rgba(254,202,202,.6);margin-bottom:12px;}
.wrong-reason{display:flex;flex-direction:column;gap:7px;margin-bottom:16px;text-align:left;}
.wr-line{font-size:12.5px;line-height:1.45;padding:9px 12px;border-radius:11px;display:flex;gap:8px;align-items:flex-start;}
.wr-line.wr-bad{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.32);color:rgba(254,202,202,.92);}
.wr-line.wr-miss{background:rgba(251,191,36,.11);border:1px solid rgba(251,191,36,.32);color:rgba(254,243,199,.92);}
.wr-line.wr-info{background:rgba(20,184,166,.10);border:1px solid rgba(20,184,166,.32);color:rgba(204,251,241,.92);}
.wr-line strong{color:#fff;font-weight:900;}
.wr-line em{font-style:normal;color:#5eead4;font-weight:800;}
.wrong-progress{height:4px;width:100%;background:rgba(255,255,255,.08);border-radius:99px;overflow:hidden;margin-bottom:12px;}
.wrong-progress-bar{height:100%;width:100%;background:linear-gradient(90deg,#f87171,#fca5a5);border-radius:99px;box-shadow:0 0 10px rgba(248,113,113,.7);}
.wrong-continue{background:linear-gradient(135deg,#0d9488,#0891b2);color:#fff;font-size:13px;font-weight:900;padding:10px 26px;border-radius:11px;border:none;cursor:pointer;box-shadow:0 6px 22px rgba(13,148,136,.55),inset 0 1px 0 rgba(255,255,255,.25);transition:transform .18s cubic-bezier(.34,1.56,.64,1),box-shadow .18s;font-family:'Space Grotesk',sans-serif;letter-spacing:.3px;width:100%;}
.wrong-continue:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(13,148,136,.75),inset 0 1px 0 rgba(255,255,255,.3);}
.wrong-continue:active{transform:scale(.97);}

/* Responsive */
@media(max-width:900px){
    .tq-instr-card, .tut-card{padding:20px 18px 16px !important;}
    .tq-instr-title{font-size:1.65rem !important;}
    .tq-features-grid{grid-template-columns:1fr;gap:8px;}
    .tq-concepts-grid{grid-template-columns:1fr;gap:8px;}
    .tq-tips-grid{grid-template-columns:1fr;gap:6px;}
    .tq-compare-hero{flex-direction:column;gap:10px;padding:10px 12px;}
    .tq-instr-footer{flex-direction:column;align-items:stretch;}
    .tq-actions-row{width:100%;justify-content:stretch;}
    .tq-actions-row .btn{width:100%;}
    #hud{padding:8px 12px;gap:8px;}
    .hv.rose{font-size:14px;}
    .lvlbadge{font-size:11.5px;padding:4px 10px;}
    .tq-step-tab span:not(.tq-num){display:none;}
}
@media(max-width:600px){
    #pond{height:min(58vh, 420px);border-radius:20px;}
    .fish{width:100px;height:70px;left:-50px;top:-35px;}
    .fish-num{font-size:20px;}
    .eduplay-nav-btn span{display:none;}
}
</style>

<div id="gw">
  <div class="orb orb1"></div><div class="orb orb2"></div><div class="orb orb3"></div>

  <!-- ============================================================
       GAME SCREEN (Upper-Right Button Group, No Restart Button)
       ============================================================ -->
  <div id="gameScreen">
    <div class="gi">
      <div id="hud">
        <!-- Left: Lives -->
        <div class="hp">
          <span class="hl">Lives</span>
          <span class="hv rose" id="livesVal">❤️❤️❤️❤️❤️</span>
        </div>

        <!-- Center: Level & Score Info -->
        <div class="hc">
          <div class="lvlbadge">🌊 <span id="lvlBadge">LVL {{ $progress->current_level }}</span></div>
          <div class="hs-sub">Best: <span id="hsVal">{{ $progress->high_score ?? 0 }}</span> <span class="score-pill-chip">• Score: <span id="scoreVal">0</span>/10</span></div>
        </div>

        <!-- Upper Right Corner: All Action & Navigation Buttons -->
        <div class="eduplay-game-nav">
          <button type="button" class="eduplay-nav-btn eduplay-btn-help" id="helpNavBtn" title="How to Play &amp; Tutorial" onclick="openTut(true)">
            <i class="bi bi-question-circle-fill"></i> <span>Tutorial</span>
          </button>
          <button type="button" class="eduplay-nav-btn eduplay-btn-pause" id="pauseBtn" title="Pause Game" onclick="togglePause()">
            <i class="bi bi-pause-fill"></i> <span>Pause</span>
          </button>
          <button type="button" class="eduplay-nav-btn eduplay-btn-fs" id="fsBtn" title="Toggle Fullscreen" onclick="toggleFullscreen()">
            <i class="bi bi-fullscreen"></i> <span>Full</span>
          </button>
          <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map">
            <i class="bi bi-map-fill"></i> <span>Map</span>
          </a>
          <a href="{{ route('student.dashboard') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Dashboard">
            <i class="bi bi-house-door-fill"></i> <span>Exit</span>
          </a>
        </div>
      </div>

      <!-- Catch Progress Bar -->
      <div>
        <div id="ptrack"><div id="pfill"></div></div>
        <div id="pmeta"><span id="plabel">0 / 10 catches</span><span id="ppct">0%</span></div>
      </div>

      <!-- PAUSE OVERLAY -->
      <div class="pause-overlay" id="pauseOverlay">
        <div class="pause-card">
          <div style="font-size:2.8rem;margin-bottom:0.4rem;">⏸</div>
          <h2>Pond Paused</h2>
          <p>Fish are resting! When you're ready, jump back into the pond.</p>
          <button class="bbtn bbtn-p" style="width:100%;padding:11px 24px;font-size:1rem;" onclick="togglePause()">▶ Resume Fishing</button>
          <div class="eduplay-game-nav" style="margin-top:14px;display:flex;gap:8px;justify-content:center;">
            <button type="button" class="eduplay-nav-btn eduplay-btn-help" onclick="openTut(true)"><i class="bi bi-question-circle-fill"></i> <span>Tutorial</span></button>
            <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
            <a href="{{ route('student.dashboard') }}" class="eduplay-nav-btn eduplay-btn-exit"><i class="bi bi-house-door-fill"></i> <span>Exit</span></a>
          </div>
        </div>
      </div>

      <!-- WIN / GAME OVER BANNER -->
      <div id="endBanner">
        <div class="bnr-box">
          <div class="bnr-left"><div class="bnr-ico" id="bnrIco">🏆</div><div class="bnr-stars" id="bnrStars"></div></div>
          <div class="bnr-mid"><h2 class="bnr-title" id="bnrTitle"></h2><p class="bnr-msg" id="bnrMsg"></p></div>
          <div class="bnr-btns" style="display:flex;align-items:center;gap:8px;">
            <button class="bbtn bbtn-p" id="bnrPlayBtn">Play Again</button>
            <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
            <a href="{{ route('student.dashboard') }}" class="eduplay-nav-btn eduplay-btn-exit"><i class="bi bi-house-door-fill"></i> <span>Exit</span></a>
          </div>
        </div>
      </div>

      <!-- MISSION & CAST NET BAR -->
      <div id="mission">
        <div class="mission-info">
          <div class="mission-tag" id="missionMode">🎯 Factors Mission</div>
          <div class="mission-txt" id="missionText">Catch every FACTOR of 12</div>
        </div>
        <button id="castBtn">🪝 Cast Net!</button>
      </div>

      <!-- POND CANVAS -->
      <div id="pond"></div>
    </div>
  </div>

  <!-- ============================================================
       INSTRUCTION MODAL (Simple, Clear & Easy to Understand)
       ============================================================ -->
  <div id="overlay" class="overlay" role="dialog" aria-modal="true" aria-label="Factor Pond Instructions">
    <div class="card tq-instr-card">
      <div style="margin-bottom:2px;">
        <span class="tq-badge"><i class="bi bi-water"></i> Grade {{ auth()->user()->grade_level ?? 4 }} • Factor Pond</span>
      </div>
      <h2 class="tq-instr-title">Factor Pond</h2>
      <p class="tq-instr-sub">
        Catch all the right fish matching your mission and fill your net!
      </p>

      <!-- 3 Simple Steps -->
      <div class="tq-features-grid">
        <div class="tq-feat-card blue">
          <div class="tq-feat-head">
            <div class="tq-feat-icon">🎯</div>
            <div class="tq-feat-title">1. Check Mission</div>
          </div>
          <p class="tq-feat-desc">
            Look at the top bar to see if you need <b>Factors</b> or <b>Multiples</b> of the target number.
          </p>
        </div>

        <div class="tq-feat-card gold">
          <div class="tq-feat-head">
            <div class="tq-feat-icon">🐟</div>
            <div class="tq-feat-title">2. Click the Fish</div>
          </div>
          <p class="tq-feat-desc">
            Click matching fish — they glow <b style="color:#fbbf24;">gold ✨</b>. Click again to unpick. Avoid wrong numbers!
          </p>
        </div>

        <div class="tq-feat-card green">
          <div class="tq-feat-head">
            <div class="tq-feat-icon">🪝</div>
            <div class="tq-feat-title">3. Cast Net</div>
          </div>
          <p class="tq-feat-desc">
            Hit <b>🪝 Cast Net!</b> when all correct fish are picked. Score <b>10 catches</b> to clear the level!
          </p>
        </div>
      </div>

      <!-- Quick Concept Guide -->
      <div class="tq-concepts-grid">
        <div class="tq-concept-card">
          <div class="tq-c-tag">🔢 WHAT IS A FACTOR?</div>
          <div class="tq-c-title">Divides Evenly (0 Remainder)</div>
          <div class="tq-c-desc">Numbers that divide into the target with nothing left over.</div>
          <div class="tq-c-example">Factors of 12: 1, 2, 3, 4, 6, 12</div>
        </div>
        <div class="tq-concept-card">
          <div class="tq-c-tag">✖️ WHAT IS A MULTIPLE?</div>
          <div class="tq-c-title">Times Table (Skip Counting)</div>
          <div class="tq-c-desc">Multiply the target by 1, 2, 3, 4, 5 and so on.</div>
          <div class="tq-c-example">Multiples of 4: 4, 8, 12, 16, 20...</div>
        </div>
      </div>

      <!-- Bottom Action Bar -->
      <div class="tq-instr-footer">
        <div class="tq-stats-pills">
          <div class="tq-stat-pill">❤️ <b>5</b> Lives</div>
          <div class="tq-stat-pill">🎯 <b>10</b> to Win</div>
          <div class="tq-stat-pill">🌊 Level <b><span id="instrLevel">{{ $progress->current_level ?? 1 }}</span></b></div>
        </div>

        <div class="tq-actions-row">
          <button id="viewTutBtn" class="btn ghost" type="button" onclick="openTut(false)" style="min-height:44px;font-size:0.9rem;padding:0 18px;">
            <i class="bi bi-mortarboard-fill"></i> How to Play &amp; Tutorial
          </button>
          <button id="startBtn" class="btn go" type="button" style="min-height:44px;font-size:0.98rem;padding:0 26px;">
            🎣 Start Fishing!
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ============================================================
       TUTORIAL OVERLAY (5 Focused Slides, Always in Front)
       ============================================================ -->
  <div id="tutorialOverlay" class="overlay hidden" role="dialog" aria-modal="true" aria-label="Factor Pond Tutorial">
    <div class="card tut-card">
      <div class="tq-tut-top">
        <span class="tq-badge"><i class="bi bi-mortarboard-fill"></i> POND ACADEMY</span>
        <button class="tq-tut-close-btn" type="button" onclick="closeTut()" title="Close Tutorial">&times;</button>
      </div>

      <!-- 5 Interactive Stepper Tabs (NO SCROLLBAR) -->
      <div class="tq-stepper" id="tutStepper" role="tablist" aria-label="Tutorial Chapters">
        <button class="tq-step-tab active" type="button" onclick="nextTut(1)">
          <span class="tq-num">1</span> <span>Mission</span>
        </button>
        <button class="tq-step-tab" type="button" onclick="nextTut(2)">
          <span class="tq-num">2</span> <span>Factors</span>
        </button>
        <button class="tq-step-tab" type="button" onclick="nextTut(3)">
          <span class="tq-num">3</span> <span>Multiples</span>
        </button>
        <button class="tq-step-tab" type="button" onclick="nextTut(4)">
          <span class="tq-num">4</span> <span>How to Catch</span>
        </button>
        <button class="tq-step-tab" type="button" onclick="nextTut(5)">
          <span class="tq-num">5</span> <span>Rules &amp; Tips</span>
        </button>
      </div>

      <!-- SLIDE 1: Mission -->
      <div id="tutStep1" class="tq-slide-body">
        <h2 class="tq-tut-slide-title" style="color:#5eead4;">Step 1: Check Your Mission</h2>
        <p class="tq-tut-slide-text">
          Look at the top of the pond before clicking fish. You will hunt for <b>Factors</b> or <b>Multiples</b>:
        </p>

        <div class="tq-compare-hero">
          <div class="tq-compare-box">
            <div class="c-flag">🔢</div>
            <div class="c-label">Factors Mission</div>
            <div class="c-math">Factors of 16</div>
            <div class="c-tag good">Pick: 1, 2, 4, 8, 16</div>
          </div>
          <div class="tq-compare-mid">OR</div>
          <div class="tq-compare-box">
            <div class="c-flag">✖️</div>
            <div class="c-label">Multiples Mission</div>
            <div class="c-math">Multiples of 5</div>
            <div class="c-tag good">Pick: 5, 10, 15, 20...</div>
          </div>
        </div>

        <div class="tq-takeaway-note">
          💡 Every fish carries a number. Read your mission first, then find the matching fish!
        </div>

        <div class="tq-tut-footer">
          <button class="tq-tut-skip-btn" type="button" onclick="closeTut()">Skip Tutorial</button>
          <button class="btn go" type="button" onclick="nextTut(2)" style="padding:8px 20px;">Next: Factors →</button>
        </div>
      </div>

      <!-- SLIDE 2: What is a Factor? -->
      <div id="tutStep2" class="tq-slide-body hidden">
        <h2 class="tq-tut-slide-title" style="color:#22d3ee;">Step 2: What is a Factor?</h2>
        <p class="tq-tut-slide-text">
          A factor divides the target number with <b>0 remainder</b>. If there is a remainder, it is NOT a factor!
        </p>

        <div class="tq-compare-hero">
          <div class="tq-compare-box">
            <div class="c-flag">✅</div>
            <div class="c-label">Target: 12</div>
            <div class="c-math">12 ÷ 4 = 3</div>
            <div class="c-tag good">0 Remainder ➔ Factor!</div>
          </div>
          <div class="tq-compare-mid">VS</div>
          <div class="tq-compare-box">
            <div class="c-flag">❌</div>
            <div class="c-label">Target: 12</div>
            <div class="c-math">12 ÷ 5 = 2 R 2</div>
            <div class="c-tag bad">Remainder 2 ➔ Not a factor!</div>
          </div>
        </div>

        <div class="tq-takeaway-note">
          💡 Quick Rule: Factors are <b>always equal to or smaller</b> than the target number.
        </div>

        <div class="tq-tut-footer">
          <button class="tq-tut-skip-btn" type="button" onclick="closeTut()">Skip Tutorial</button>
          <div style="display:flex;gap:8px;">
            <button class="btn ghost" type="button" onclick="nextTut(1)" style="padding:8px 16px;">← Back</button>
            <button class="btn go" type="button" onclick="nextTut(3)" style="padding:8px 20px;">Next: Multiples →</button>
          </div>
        </div>
      </div>

      <!-- SLIDE 3: What is a Multiple? -->
      <div id="tutStep3" class="tq-slide-body hidden">
        <h2 class="tq-tut-slide-title" style="color:#fbbf24;">Step 3: What is a Multiple?</h2>
        <p class="tq-tut-slide-text">
          Multiples are from your times table or skip counting! Multiply the target by 1, 2, 3, 4, 5…
        </p>

        <div class="tq-compare-hero" style="border-color:rgba(251,191,36,0.35);background:linear-gradient(135deg,rgba(251,191,36,0.08) 0%,rgba(0,0,0,0.4) 100%);">
          <div class="tq-compare-box">
            <div class="c-flag">✖️</div>
            <div class="c-label">Multiples of 6</div>
            <div class="c-math" style="color:#fbbf24;">6, 12, 18, 24, 30...</div>
            <div class="c-tag good">6×1=6, 6×2=12, 6×3=18...</div>
          </div>
        </div>

        <div class="tq-takeaway-note">
          💡 Quick Rule: Multiples are <b>always equal to or bigger</b> than the target number.
        </div>

        <div class="tq-tut-footer">
          <button class="tq-tut-skip-btn" type="button" onclick="closeTut()">Skip Tutorial</button>
          <div style="display:flex;gap:8px;">
            <button class="btn ghost" type="button" onclick="nextTut(2)" style="padding:8px 16px;">← Back</button>
            <button class="btn go" type="button" onclick="nextTut(4)" style="padding:8px 20px;">Next: How to Catch →</button>
          </div>
        </div>
      </div>

      <!-- SLIDE 4: Selecting Fish & Casting Net -->
      <div id="tutStep4" class="tq-slide-body hidden">
        <h2 class="tq-tut-slide-title" style="color:#34d399;">Step 4: Click Fish &amp; Cast Net</h2>
        <p class="tq-tut-slide-text">
          Click matching fish to select them. Selected fish glow <b style="color:#fbbf24;">gold ✨</b>. Click again to unpick.
        </p>

        <div class="demo-strip">
          <div class="demo-fish-wrap"><div class="demo-fish selected">2</div><div class="demo-fish-lbl">✅ Picked</div></div>
          <div class="demo-fish-wrap"><div class="demo-fish">5</div><div class="demo-fish-lbl">Not picked</div></div>
          <div class="demo-fish-wrap"><div class="demo-fish selected">4</div><div class="demo-fish-lbl">✅ Picked</div></div>
        </div>

        <div class="tq-takeaway-note">
          💡 When all matching fish are glowing gold, press <b>🪝 Cast Net!</b> to score a point!
        </div>

        <div class="tq-tut-footer">
          <button class="tq-tut-skip-btn" type="button" onclick="closeTut()">Skip Tutorial</button>
          <div style="display:flex;gap:8px;">
            <button class="btn ghost" type="button" onclick="nextTut(3)" style="padding:8px 16px;">← Back</button>
            <button class="btn go" type="button" onclick="nextTut(5)" style="padding:8px 20px;">Next: Rules &amp; Tips →</button>
          </div>
        </div>
      </div>

      <!-- SLIDE 5: Rules & Tips -->
      <div id="tutStep5" class="tq-slide-body hidden">
        <h2 class="tq-tut-slide-title" style="color:#5eead4;">Step 5: Keep Your Lives &amp; Win!</h2>
        <p class="tq-tut-slide-text">
          You have <b>5 lives ❤️❤️❤️❤️❤️</b>. Think before you cast!
        </p>

        <div class="tq-tips-grid">
          <div class="tq-tip-box">
            <div class="tq-tip-ico">❌</div>
            <div class="tq-tip-tit">Wrong Pick</div>
            <p class="tq-tip-txt">Picking a wrong number loses 1 life.</p>
          </div>
          <div class="tq-tip-box">
            <div class="tq-tip-ico">💨</div>
            <div class="tq-tip-tit">Missed Fish</div>
            <p class="tq-tip-txt">Leaving a matching fish behind loses 1 life.</p>
          </div>
          <div class="tq-tip-box">
            <div class="tq-tip-ico">⌨️</div>
            <div class="tq-tip-tit">Shortcuts</div>
            <p class="tq-tip-txt">Press <b>P</b> to pause, <b>Space</b> to Cast Net.</p>
          </div>
        </div>

        <div class="tq-takeaway-note" style="border-color:rgba(52,211,153,0.35);background:rgba(52,211,153,0.08);">
          🎯 Goal: Make <b>10 perfect catches</b> to clear the level and level up!
        </div>

        <div class="tq-tut-footer">
          <button class="tq-tut-skip-btn" type="button" onclick="closeTut()">Close Tutorial</button>
          <div style="display:flex;gap:8px;">
            <button class="btn ghost" type="button" onclick="nextTut(4)" style="padding:8px 16px;">← Back</button>
            <button class="btn go" id="tutStartMissionBtn" type="button" onclick="closeTut(true)" style="padding:8px 22px;">🚀 Start Fishing!</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- WRONG ANSWER MODAL -->
<div id="wrongOverlay">
  <div class="wrong-box">
    <div class="wrong-ico">🤔</div>
    <h2 class="wrong-title">Not Quite!</h2>
    <p class="wrong-sub">Here's what went wrong with your cast…</p>
    <div class="wrong-reason" id="wrongReason"></div>
    <div class="wrong-progress"><div class="wrong-progress-bar" id="wrongProgress"></div></div>
    <button class="wrong-continue" id="wrongContinueBtn">Got it — Continue →</button>
  </div>
</div>

<script>
(function(){
'use strict';
const CURR_LVL={{ $progress->current_level }};
const GAME_ID={{ $game->id }};
const GRADE={{ auth()->user()->grade_level ?? 4 }};
const GRADE_NUM = Math.max(4, Math.min(6, Number(GRADE) || 4));
const MAX_LVL={{ $game->total_levels }};
const CSRF='{{ csrf_token() }}';
const MAX_LIVES=5,WIN_POINTS=10;
const WRONG_MODAL_MS=5000;

const overlay=document.getElementById('overlay');
const tutorialOverlay=document.getElementById('tutorialOverlay');
const gameScreen=document.getElementById('gameScreen');
const wrongOverlay=document.getElementById('wrongOverlay');
const wrongReasonEl=document.getElementById('wrongReason');
const wrongProgressEl=document.getElementById('wrongProgress');
const wrongContinueBtn=document.getElementById('wrongContinueBtn');
const startBtn=document.getElementById('startBtn');
const castBtn=document.getElementById('castBtn');
const pondEl=document.getElementById('pond');
const lvlBadgeEl=document.getElementById('lvlBadge');
const hsValEl=document.getElementById('hsVal');
const livesEl=document.getElementById('livesVal');
const scoreValEl=document.getElementById('scoreVal');
const pfillEl=document.getElementById('pfill');
const plabelEl=document.getElementById('plabel');
const ppctEl=document.getElementById('ppct');
const missionMode=document.getElementById('missionMode');
const missionText=document.getElementById('missionText');
const endBanner=document.getElementById('endBanner');
const bnrIco=document.getElementById('bnrIco');
const bnrStars=document.getElementById('bnrStars');
const bnrTitle=document.getElementById('bnrTitle');
const bnrMsg=document.getElementById('bnrMsg');
const bnrPlayBtn=document.getElementById('bnrPlayBtn');

function rand(a,b){return Math.floor(Math.random()*(b-a+1))+a;}
function shuffle(a){const r=a.slice();for(let i=r.length-1;i>0;i--){const j=rand(0,i);[r[i],r[j]]=[r[j],r[i]];}return r;}
function getFactors(n){const f=[];for(let i=1;i<=n;i++)if(n%i===0)f.push(i);return f;}

const PAL=[
    ['#ffb84d','#ff8a3d','#ffd9a0','#c96a1f','#ffe6bd'],
    ['#7fd8ff','#3fa8e0','#c7f0ff','#2d7baa','#ddf3ff'],
    ['#ff9ecb','#ff6fa3','#ffd3e6','#cc4f7e','#ffe3ee'],
    ['#c4f26a','#8fd63f','#e2fbb0','#6ba524','#ecffc9'],
    ['#f6d94a','#e8b827','#fcefa3','#c29118','#fff5b8'],
    ['#b8a3ff','#8b6fff','#e0d6ff','#6448d9','#eae0ff']
];

function fishSVG(paletteIdx){
    const [body, fin, hi, dark, finLight] = PAL[paletteIdx % PAL.length];
    const uid = 'f' + paletteIdx + Math.random().toString(36).slice(2,7);
    return \`
<svg class="fish-svg" viewBox="0 0 100 60" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="\${uid}b" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%"   stop-color="\${hi}"/>
      <stop offset="45%"  stop-color="\${body}"/>
      <stop offset="100%" stop-color="\${dark}"/>
    </linearGradient>
    <linearGradient id="\${uid}f" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%"   stop-color="\${finLight}"/>
      <stop offset="100%" stop-color="\${fin}"/>
    </linearGradient>
  </defs>
  <g class="f-tail"><path d="M77,30 Q84,14 90,10 Q86,30 90,50 Q84,46 77,30 Z" fill="url(#\${uid}f)"/></g>
  <g class="f-dorsal"><path d="M34,13 Q46,1 60,12 Q48,16 34,13 Z" fill="url(#\${uid}f)"/></g>
  <g class="f-body">
    <path d="M10,30 C12,17 26,9 44,9 C64,9 76,19 80,30 C76,41 64,51 44,51 C26,51 12,43 10,30 Z" fill="url(#\${uid}b)"/>
    <ellipse cx="42" cy="21" rx="22" ry="6.5" fill="rgba(255,255,255,0.30)"/>
    <ellipse cx="46" cy="42" rx="20" ry="5" fill="rgba(0,0,0,0.10)"/>
    <path d="M32,17 Q27,30 32,43" stroke="rgba(0,0,0,0.18)" stroke-width="1.4" fill="none" stroke-linecap="round"/>
  </g>
  <g class="f-pectoral"><path d="M46,38 Q40,52 54,48 Q50,42 46,38 Z" fill="url(#\${uid}f)" opacity="0.92"/></g>
  <circle cx="24" cy="24" r="6.6" fill="#fff"/>
  <circle cx="23" cy="24" r="3.8" fill="#111"/>
  <circle cx="21.5" cy="22.5" r="1.5" fill="#fff" opacity="0.95"/>
  <path d="M11,31 Q14,34 18,33" stroke="#5a2d00" stroke-width="1.5" fill="none" stroke-linecap="round"/>
</svg>\`;
}

function turtleSVG(){
    return \`
<svg class="turtle-svg" viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg">
  <ellipse cx="30" cy="60" rx="13" ry="6" fill="#3d6e2a" transform="rotate(-30 30 60)"/>
  <ellipse cx="92" cy="60" rx="13" ry="6" fill="#3d6e2a" transform="rotate(30 92 60)"/>
  <ellipse cx="62" cy="46" rx="40" ry="27" fill="#3d6e2a"/>
  <ellipse cx="62" cy="44" rx="36" ry="23" fill="#7a9e4c"/>
  <g stroke="#4a6e28" stroke-width="1.8" fill="none" opacity="0.55">
    <path d="M62,22 L62,66"/>
    <path d="M28,44 L96,44"/>
    <path d="M40,30 L84,58"/>
    <path d="M84,30 L40,58"/>
  </g>
  <ellipse cx="62" cy="44" rx="9" ry="6" fill="#8fb35a" opacity="0.95"/>
  <ellipse cx="48" cy="34" rx="18" ry="4.5" fill="rgba(255,255,255,0.25)"/>
  <ellipse cx="100" cy="46" rx="9" ry="8" fill="#5a8a3a"/>
  <ellipse cx="108" cy="46" rx="9" ry="8" fill="#5a8a3a"/>
  <circle cx="111" cy="43" r="2.2" fill="#111"/>
  <circle cx="111.5" cy="42.3" r="0.8" fill="#fff"/>
  <path d="M114,50 Q111,52 108,51" stroke="#3d5a22" stroke-width="1.1" fill="none" stroke-linecap="round"/>
  <ellipse class="turtle-flipper" cx="28" cy="48" rx="15" ry="7.5" fill="#5a8a3a"/>
</svg>\`;
}

function dragonflySVG(){
    return \`
<svg class="dragonfly-svg" viewBox="0 0 60 50" xmlns="http://www.w3.org/2000/svg">
  <ellipse class="df-wing df-wing-1" cx="16" cy="14" rx="14" ry="5" fill="rgba(200,230,255,.55)" stroke="rgba(120,190,230,.85)" stroke-width="0.7" transform="rotate(-22 16 14)"/>
  <ellipse class="df-wing df-wing-2" cx="44" cy="14" rx="14" ry="5" fill="rgba(200,230,255,.55)" stroke="rgba(120,190,230,.85)" stroke-width="0.7" transform="rotate(22 44 14)"/>
  <ellipse class="df-wing df-wing-3" cx="18" cy="24" rx="12" ry="4" fill="rgba(180,220,250,.45)" stroke="rgba(120,190,230,.75)" stroke-width="0.7" transform="rotate(-12 18 24)"/>
  <ellipse class="df-wing df-wing-4" cx="42" cy="24" rx="12" ry="4" fill="rgba(180,220,250,.45)" stroke="rgba(120,190,230,.75)" stroke-width="0.7" transform="rotate(12 42 24)"/>
  <ellipse cx="30" cy="20" rx="4" ry="6" fill="#3d7a9a"/>
  <ellipse cx="30" cy="33" rx="2.6" ry="10" fill="#3d7a9a"/>
  <ellipse cx="30" cy="33" rx="1.6" ry="8" fill="#5aa8c8"/>
  <line x1="28" y1="28" x2="32" y2="28" stroke="#1e4a6a" stroke-width="1"/>
  <line x1="28" y1="33" x2="32" y2="33" stroke="#1e4a6a" stroke-width="1"/>
  <line x1="28" y1="38" x2="32" y2="38" stroke="#1e4a6a" stroke-width="1"/>
  <ellipse cx="30" cy="11" rx="5" ry="4" fill="#3d7a9a"/>
  <circle cx="27" cy="10" r="2" fill="#111"/>
  <circle cx="33" cy="10" r="2" fill="#111"/>
  <circle cx="26.5" cy="9.5" r="0.7" fill="#fff"/>
  <circle cx="32.5" cy="9.5" r="0.7" fill="#fff"/>
</svg>\`;
}

function snailSVG(){
    return \`
<svg class="snail-svg" viewBox="0 0 60 45" xmlns="http://www.w3.org/2000/svg">
  <path d="M4,38 Q4,32 12,32 L46,32 Q54,32 54,38 Q54,42 46,42 L12,42 Q4,42 4,38 Z" fill="#a08060"/>
  <path d="M4,38 Q4,34 12,34 L46,34 Q54,34 54,38" stroke="rgba(255,255,255,0.22)" stroke-width="1" fill="none"/>
  <circle cx="22" cy="24" r="14" fill="#c9a05a"/>
  <circle cx="22" cy="24" r="11" fill="#b08840"/>
  <path d="M22,14 A10,10 0 0,1 32,24 A8,8 0 0,1 24,32 A6,6 0 0,1 18,26 A4,4 0 0,1 22,22 A2,2 0 0,1 24,24" stroke="#8a6a30" stroke-width="1.3" fill="none" stroke-linecap="round"/>
  <ellipse cx="16" cy="18" rx="5" ry="3" fill="rgba(255,255,255,0.3)" transform="rotate(-30 16 18)"/>
  <ellipse cx="48" cy="34" rx="6" ry="5" fill="#a08060"/>
  <g class="snail-ant">
    <path d="M48,32 Q50,20 54,15" stroke="#a08060" stroke-width="1.5" fill="none" stroke-linecap="round"/>
    <circle cx="54" cy="15" r="1.8" fill="#5a3a1a"/>
  </g>
  <g class="snail-ant" style="animation-delay:-0.7s">
    <path d="M50,34 Q54,24 57,20" stroke="#a08060" stroke-width="1.5" fill="none" stroke-linecap="round"/>
    <circle cx="57" cy="20" r="1.8" fill="#5a3a1a"/>
  </g>
  <circle cx="50" cy="33" r="1.2" fill="#111"/>
</svg>\`;
}

function createFishEl(num, paletteIdx, small){
    const div = document.createElement('div');
    div.className = 'fish' + (small ? ' fish--sm' : '');
    div.dataset.val = num;
    const inner = document.createElement('div');
    inner.className = 'fish-flip';
    inner.innerHTML = fishSVG(paletteIdx);
    div.appendChild(inner);
    const numEl = document.createElement('span');
    numEl.className = 'fish-num';
    numEl.textContent = num;
    div.appendChild(numEl);
    return div;
}
function createDecorFishEl(paletteIdx){
    const div = document.createElement('div');
    div.className = 'fish fish--decor';
    const inner = document.createElement('div');
    inner.className = 'fish-flip';
    inner.innerHTML = fishSVG(paletteIdx);
    div.appendChild(inner);
    return div;
}

/* ═══ POND DECOR ═══ */
function buildPondDecorHTML(){
    const defs = \`
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
      <defs>
        <radialGradient id="lilyGrad" cx="35%" cy="30%">
          <stop offset="0%"   stop-color="#9bd96f"/>
          <stop offset="60%"  stop-color="#4a9038"/>
          <stop offset="100%" stop-color="#2d5a20"/>
        </radialGradient>
        <radialGradient id="lilyGrad2" cx="35%" cy="30%">
          <stop offset="0%"   stop-color="#84c95c"/>
          <stop offset="60%"  stop-color="#3d7f2e"/>
          <stop offset="100%" stop-color="#255019"/>
        </radialGradient>
        <radialGradient id="lilyGrad3" cx="35%" cy="30%">
          <stop offset="0%"   stop-color="#a6df7a"/>
          <stop offset="60%"  stop-color="#559a40"/>
          <stop offset="100%" stop-color="#346226"/>
        </radialGradient>
      </defs>
    </svg>\`;
    const lily = (style, grad, withFlower, rot) => \`
      <svg class="lily" style="\${style};--ls:\${(6+Math.random()*3).toFixed(1)}s;--ld:\${(Math.random()*3).toFixed(1)}s;transform:rotate(\${rot}deg)"
           viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <path d="M50,50 L90.8,31 A45,45 0 1,0 90.8,69 Z" fill="url(#\${grad})"/>
        <path d="M50,50 L90.8,31 A45,45 0 1,0 90.8,69 Z" fill="none" stroke="rgba(0,0,0,.25)" stroke-width="1.2"/>
        <path d="M50,5 A45,45 0 0,0 20,20" fill="none" stroke="rgba(255,255,255,.28)" stroke-width="2.5" stroke-linecap="round"/>
        <g stroke="rgba(0,0,0,.13)" stroke-width="1" fill="none">
          <line x1="50" y1="50" x2="50" y2="6"/>
          <line x1="50" y1="50" x2="6"  y2="50"/>
          <line x1="50" y1="50" x2="50" y2="94"/>
          <line x1="50" y1="50" x2="14" y2="20"/>
          <line x1="50" y1="50" x2="14" y2="80"/>
          <line x1="50" y1="50" x2="72" y2="8"/>
        </g>
        \${withFlower ? \`<g transform="translate(38,32)"><circle r="8" fill="#ffd3e6" opacity=".9"/><circle r="5" fill="#fff"/><circle r="2.4" fill="#ffd966"/></g>\` : ''}
      </svg>\`;
    const reeds = \`
      <div class="reed" style="left:3%;height:74px;--d:4.4s;--dl:0s"></div>
      <div class="reed" style="left:4.8%;height:54px;--d:3.8s;--dl:.5s"></div>
      <div class="reed" style="left:6.4%;height:88px;--d:5.1s;--dl:.2s"></div>
      <div class="reed" style="left:8%;height:60px;--d:4.1s;--dl:.9s"></div>
      <div class="reed" style="right:3.4%;height:70px;--d:4.7s;--dl:.3s"></div>
      <div class="reed" style="right:5.2%;height:92px;--d:5.3s;--dl:.6s"></div>
      <div class="reed" style="right:7%;height:58px;--d:3.9s;--dl:.1s"></div>
      <div class="reed" style="right:8.6%;height:76px;--d:4.5s;--dl:.8s"></div>\`;
    const rocks = \`
      <svg class="rocks" viewBox="0 0 1000 70" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,70 L0,40 Q55,22 130,36 Q185,48 250,30 Q320,14 400,34 Q460,48 530,30 Q600,14 680,34 Q750,50 820,32 Q890,16 950,34 L1000,38 L1000,70 Z" fill="rgba(0,12,20,.55)"/>
        <path d="M0,70 L0,52 Q70,40 150,50 Q230,60 310,46 Q390,32 470,48 Q550,62 630,48 Q710,34 790,50 Q870,62 950,48 L1000,52 L1000,70 Z" fill="rgba(0,8,14,.72)"/>
      </svg>\`;
    const snails = \`
      <div class="snail" style="left:9%;bottom:8px">\${snailSVG()}</div>
      <div class="snail" style="right:16%;bottom:12px;transform:scaleX(-1)">\${snailSVG()}</div>\`;
    const turtles = \`
      <div class="turtle" style="top:58%;--tdur:48s;--tdelay:0s;--flapDelay:0s">\${turtleSVG()}</div>
      <div class="turtle" style="top:30%;--tdur:62s;--tdelay:-18s;--flapDelay:-1.2s">\${turtleSVG()}</div>\`;
    const dragonflies = \`
      <div class="dragonfly" style="top:6%;left:14%;--ddur:7s;--ddelay:0s">\${dragonflySVG()}</div>
      <div class="dragonfly" style="top:11%;left:52%;--ddur:9s;--ddelay:-2s">\${dragonflySVG()}</div>
      <div class="dragonfly" style="top:4%;left:78%;--ddur:8s;--ddelay:-4.5s">\${dragonflySVG()}</div>\`;

    return \`
      \${defs}
      <div class="rays"></div>
      \${reeds}
      \${rocks}
      \${snails}
      \${turtles}
      \${lily('left:4%;top:8%;width:78px',  'lilyGrad',  true,  -18)}
      \${lily('left:14%;bottom:14%;width:56px', 'lilyGrad2', false, 32)}
      \${lily('right:5%;top:14%;width:68px', 'lilyGrad3', true,  22)}
      \${lily('right:11%;bottom:10%;width:60px', 'lilyGrad',  false, -46)}
      \${lily('left:44%;top:5%;width:52px',   'lilyGrad2', false, 12)}
      \${lily('right:42%;bottom:6%;width:48px','lilyGrad3', false, -8)}
      \${dragonflies}
    \`;
}

function setupPondDecor(container){
    if (container.querySelector('.pond-decor')) return;
    const decor = document.createElement('div');
    decor.className = 'pond-decor';
    decor.innerHTML = buildPondDecorHTML();
    container.insertBefore(decor, container.firstChild);
}

/* ═══ FISH ENGINE ═══ */
let fishList = [];
let fishRAF = null;
let lastFrameT = 0;
const pondBounds = { w: 1280, h: 480 };

function refreshBounds(){
    if (pondEl){
        const r = pondEl.getBoundingClientRect();
        if (r.width > 0)  pondBounds.w = r.width;
        if (r.height > 0) pondBounds.h = r.height;
    }
}
window.addEventListener('resize', refreshBounds);

function getFishBounds(container){
    return { w: pondEl.clientWidth || 1280, h: pondEl.clientHeight || 480 };
}

function computeSpawnPositions(count, bw, bh){
    const cols = Math.min(4, count);
    const rows = Math.ceil(count / cols);
    const cellW = bw / cols;
    const cellH = bh / rows;
    const out = [];
    for (let i = 0; i < count; i++){
        const c = i % cols;
        const r = Math.floor(i / cols);
        const cx = cellW * (c + 0.5);
        const cy = cellH * (r + 0.5);
        const jx = (Math.random() - 0.5) * cellW * 0.45;
        const jy = (Math.random() - 0.5) * cellH * 0.45;
        out.push({
            x: Math.max(70, Math.min(bw - 70, cx + jx)),
            y: Math.max(60, Math.min(bh - 60, cy + jy))
        });
    }
    return shuffle(out);
}

function registerFish(el, container, opts){
    opts = opts || {};
    const bounds = getFishBounds(container);
    const cruiseSpeed = opts.speed || (24 + Math.random() * 18);
    const initialDir  = Math.random() < 0.5 ? 1 : -1;
    const sp = opts.spawn || {
        x: 70 + Math.random() * Math.max(40, bounds.w - 140),
        y: 60 + Math.random() * Math.max(40, bounds.h - 120)
    };
    const f = {
        el,
        inner: el.querySelector('.fish-flip'),
        container,
        isDecor: !!opts.isDecor,
        x: sp.x,
        y: sp.y,
        dirX: initialDir,
        cruiseSpeed,
        baseSpeed: cruiseSpeed,
        face: initialDir > 0 ? -1 : 1,
        tilt: 0,
        bobPhase: Math.random() * Math.PI * 2,
        bobAmp: 1.2 + Math.random() * 1.8,
        scale: 0.95 + Math.random() * 0.12,
        homeY: sp.y,
        wanderPhase: Math.random() * Math.PI * 2,
        wanderSpeed: 0.45 + Math.random() * 0.55,
        wanderAmp: 15 + Math.random() * 24,
        sepRadius: opts.sepRadius || 68
    };
    el.style.opacity = '0';
    el.style.transform = \`translate3d(\${f.x}px, \${f.y}px, 0)\`;
    el.style.setProperty('--wagdur', (0.34 + Math.random() * 0.22).toFixed(2) + 's');
    fishList.push(f);
    return f;
}

function stepFish(f, dt, now){
    const bounds = getFishBounds(f.container);
    const bw = bounds.w, bh = bounds.h;

    f.x += f.dirX * f.cruiseSpeed * dt;
    f.wanderPhase += dt * f.wanderSpeed;
    let targetY = f.homeY + Math.sin(f.wanderPhase) * f.wanderAmp;

    let pushY = 0;
    for (let i = 0; i < fishList.length; i++){
        const o = fishList[i];
        if (o === f || o.container !== f.container) continue;
        const dx = f.x - o.x;
        const dy = targetY - o.y;
        if (Math.abs(dx) < 70 && Math.abs(dy) < 52){
            pushY += (dy > 0 ? 22 : -22);
        }
    }
    targetY += pushY;
    targetY = Math.max(60, Math.min(bh - 60, targetY));
    f.y += (targetY - f.y) * Math.min(1, dt * 2.4);

    const padX = 65;
    if (f.x < padX){
        f.x = padX;
        f.dirX = 1;
        f.homeY = 60 + Math.random() * Math.max(20, bh - 120);
    } else if (f.x > bw - padX){
        f.x = bw - padX;
        f.dirX = -1;
        f.homeY = 60 + Math.random() * Math.max(20, bh - 120);
    }

    const targetFace = f.dirX > 0 ? -1 : 1;
    f.face += (targetFace - f.face) * Math.min(1, dt * 5);

    const bobY = Math.sin(now * 0.0022 + f.bobPhase) * f.bobAmp;
    const targetTilt = Math.max(-10, Math.min(10, (targetY - f.y) * 0.45));
    f.tilt += (targetTilt - f.tilt) * Math.min(1, dt * 3.5);

    f.el.style.transform = \`translate3d(\${f.x}px, \${f.y + bobY}px, 0)\`;
    if (f.inner){
        f.inner.style.transform = \`scaleX(\${f.face.toFixed(2)}) rotate(\${f.tilt.toFixed(1)}deg) scale(\${f.scale.toFixed(2)})\`;
    }
}

function fishLoop(t){
    if (!lastFrameT) lastFrameT = t;
    const dt = Math.min(0.05, (t - lastFrameT) / 1000);
    lastFrameT = t;
    if (!isPaused){
        for (let i = 0; i < fishList.length; i++){
            stepFish(fishList[i], dt, t);
        }
    }
    fishRAF = requestAnimationFrame(fishLoop);
}

function startFishEngine(){
    if (!fishRAF){
        lastFrameT = 0;
        fishRAF = requestAnimationFrame(fishLoop);
    }
}
function clearPond(container){
    fishList = fishList.filter(f => f.container !== container);
    container.querySelectorAll('.fish, .bubble').forEach(el => el.remove());
}
function revealFish(delayStep){
    fishList.forEach((f, idx) => {
        setTimeout(() => { f.el.style.opacity = f.isDecor ? '0.45' : '1'; }, idx * (delayStep || 35));
    });
}

function spawnBubbles(container){
    const count = 7;
    for (let i = 0; i < count; i++){
        const b = document.createElement('div');
        b.className = 'bubble';
        const sz = 7 + Math.random() * 12;
        b.style.width = sz + 'px';
        b.style.height = sz + 'px';
        b.style.left = (Math.random() * 92 + 4) + '%';
        b.style.animationDuration = (4.5 + Math.random() * 4) + 's';
        b.style.animationDelay = (Math.random() * 3) + 's';
        container.appendChild(b);
    }
}

function spawnRipple(e, el, container){
    const r = document.createElement('div');
    r.className = 'ripple';
    const rect = container.getBoundingClientRect();
    const x = e ? (e.clientX - rect.left) : (parseFloat(el.style.left) || 0);
    const y = e ? (e.clientY - rect.top)  : (parseFloat(el.style.top)  || 0);
    r.style.left = x + 'px';
    r.style.top  = y + 'px';
    container.appendChild(r);
    setTimeout(() => r.remove(), 800);
}

function spawnSplashText(text, x, y){
    const s = document.createElement('div');
    s.className = 'splash';
    s.textContent = text;
    s.style.left = x + 'px';
    s.style.top  = y + 'px';
    document.body.appendChild(s);
    setTimeout(() => s.remove(), 800);
}

/* ═══ MISSION BUILDER ═══ */
let targetNumber = 12;
let currentMissionMode = 'factors';
let correctSet = new Set();
let selected = new Set();
let lives = MAX_LIVES;
let score = 0;
let highScore = {{ $progress->high_score ?? 0 }};
let currentLevel = CURR_LVL;
let pondLocked = false;
let isPaused = false;
let gameOn = false;
let wrongModalActive = false;
let wrongTimer = null;
let lastMissionTarget = null;
let lastMissionMode = null;

const FACTOR_TARGETS = {
    4: [6, 8, 10, 12, 14, 15, 16, 18, 20, 24],
    5: [12, 16, 18, 20, 24, 28, 30, 32, 36, 40, 42, 48],
    6: [24, 30, 36, 40, 42, 48, 54, 56, 60, 64, 72, 80, 84, 90]
};
const MULTIPLE_TARGETS = {
    4: [2, 3, 4, 5, 6],
    5: [3, 4, 5, 6, 7, 8, 9],
    6: [4, 6, 7, 8, 9, 11, 12, 13, 14, 15]
};

function buildMission(mode, boost){
    const effGrade = Math.min(6, Math.max(4, GRADE_NUM + (boost ? 1 : 0)));
    if (mode === 'factors'){
        const pool = FACTOR_TARGETS[effGrade] || FACTOR_TARGETS[4];
        const target = pool[rand(0, pool.length - 1)];
        const allF = getFactors(target);
        if (allF.length < 2) return null;
        const correctCount = Math.min(allF.length, rand(2, Math.min(4, allF.length)));
        const correct = shuffle(allF).slice(0, correctCount);
        const distractors = [];
        let attempts = 0;
        const maxDist = Math.max(target + 10, 20);
        while (distractors.length < 4 && attempts < 80){
            attempts++;
            const d = rand(2, maxDist);
            if (!allF.includes(d) && !distractors.includes(d)) distractors.push(d);
        }
        return { mode: 'factors', target, correctSet: new Set(correct), nums: correct.concat(distractors) };
    } else {
        const pool = MULTIPLE_TARGETS[effGrade] || MULTIPLE_TARGETS[4];
        const target = pool[rand(0, pool.length - 1)];
        const multCount = rand(2, 4);
        const correct = [];
        for (let i = 0; i < multCount; i++){
            let m;
            do { m = target * rand(1, 10); } while (correct.includes(m));
            correct.push(m);
        }
        const distractors = [];
        let attempts = 0;
        while (distractors.length < 4 && attempts < 80){
            attempts++;
            const d = rand(target + 1, target * 10 + 5);
            if (d % target !== 0 && !distractors.includes(d)) distractors.push(d);
        }
        return { mode: 'multiples', target, correctSet: new Set(correct), nums: correct.concat(distractors) };
    }
}

function generateMission(){
    selected.clear();
    correctSet.clear();
    const boost = (currentLevel - 1) >= 4;
    let mission = null;
    for (let attempts = 0; attempts < 10; attempts++){
        const mode = Math.random() < 0.5 ? 'factors' : 'multiples';
        const m = buildMission(mode, boost);
        if (!m) continue;
        if (m.mode === lastMissionMode && m.target === lastMissionTarget) continue;
        mission = m;
        break;
    }
    if (!mission) mission = buildMission(Math.random() < 0.5 ? 'factors' : 'multiples', boost);
    lastMissionMode = mission.mode;
    lastMissionTarget = mission.target;
    targetNumber = mission.target;
    currentMissionMode = mission.mode;
    mission.correctSet.forEach(v => correctSet.add(v));
    if (mission.mode === 'factors'){
        missionMode.textContent = '🔢 Factors Mission';
        missionText.textContent = 'Catch every FACTOR of ' + targetNumber;
    } else {
        missionMode.textContent = '✖️ Multiples Mission';
        missionText.textContent = 'Catch every MULTIPLE of ' + targetNumber;
    }
    renderPond(shuffle(mission.nums));
    castBtn.disabled = false;
}

function renderPond(nums){
    clearPond(pondEl);
    spawnBubbles(pondEl);
    const b = getFishBounds(pondEl);
    const spawns = computeSpawnPositions(nums.length, b.w, b.h);
    nums.forEach((n, i) => {
        const el = createFishEl(n, rand(0, PAL.length - 1), false);
        el.addEventListener('click', e => { toggleFish(el, n); spawnRipple(e, el, pondEl); });
        pondEl.appendChild(el);
        registerFish(el, pondEl, { spawn: spawns[i] });
    });
    const decorCount = 3 + Math.floor(Math.random() * 2);
    for (let i = 0; i < decorCount; i++){
        const el = createDecorFishEl(rand(0, PAL.length - 1));
        pondEl.appendChild(el);
        registerFish(el, pondEl, { speed: 20 + Math.random() * 14, isDecor: true, sepRadius: 68 });
    }
    revealFish(35);
}

function toggleFish(el, n){
    if (!gameOn || pondLocked || isPaused) return;
    if (selected.has(n)){ selected.delete(n); el.classList.remove('picked'); }
    else                { selected.add(n);    el.classList.add('picked'); }
}

function joinList(arr){
    arr = arr.slice().sort((a,b) => a - b);
    if (arr.length === 1) return String(arr[0]);
    if (arr.length === 2) return arr[0] + ' and ' + arr[1];
    return arr.slice(0, -1).join(', ') + ', and ' + arr[arr.length - 1];
}

function buildWrongExplanation(){
    const modeWord      = currentMissionMode === 'factors' ? 'factor'  : 'multiple';
    const modeWordPlural= currentMissionMode === 'factors' ? 'factors' : 'multiples';
    const correct = Array.from(correctSet).sort((a,b) => a - b);
    const wrongPicks = Array.from(selected).filter(v => !correctSet.has(v)).sort((a,b) => a - b);
    const missed = correct.filter(v => !selected.has(v));
    let html = '';
    if (wrongPicks.length > 0){
        const verb = wrongPicks.length === 1 ? 'is' : 'are';
        html += \`<div class="wr-line wr-bad">❌ <span><strong>\${joinList(wrongPicks)}</strong> \${verb} not a \${modeWord} of <strong>\${targetNumber}</strong>.</span></div>\`;
    }
    if (missed.length > 0){
        const verb = missed.length === 1 ? 'is a' : 'are';
        html += \`<div class="wr-line wr-miss">💡 <span>You missed <strong>\${joinList(missed)}</strong> — \${verb === 'is a' ? 'it' : 'they'} <em>\${verb} \${modeWord}\${missed.length === 1 ? '' : 's'}</em> of \${targetNumber}.</span></div>\`;
    }
    html += \`<div class="wr-line wr-info">✅ <span>Correct \${modeWordPlural}: <strong>\${joinList(correct)}</strong></span></div>\`;
    return html;
}

function showWrongModal(){
    wrongModalActive = true;
    wrongReasonEl.innerHTML = buildWrongExplanation();
    wrongOverlay.classList.add('show');
    wrongProgressEl.style.transition = 'none';
    wrongProgressEl.style.width = '100%';
    void wrongProgressEl.offsetWidth;
    wrongProgressEl.style.transition = 'width ' + (WRONG_MODAL_MS/1000) + 's linear';
    wrongProgressEl.style.width = '0%';
    clearTimeout(wrongTimer);
    wrongTimer = setTimeout(proceedAfterWrong, WRONG_MODAL_MS);
}
function proceedAfterWrong(){
    if (!wrongModalActive) return;
    wrongModalActive = false;
    clearTimeout(wrongTimer);
    wrongOverlay.classList.remove('show');
    if (lives <= 0){ endGame(false); return; }
    pondLocked = false;
    generateMission();
}
wrongContinueBtn.addEventListener('click', proceedAfterWrong);

function castNet(){
    if (!gameOn || pondLocked || isPaused) return;
    pondLocked = true;
    castBtn.disabled = true;
    const fishEls = pondEl.querySelectorAll('.fish:not(.fish--decor)');
    const wrongPicks = [];
    const missed = [];
    fishEls.forEach(el => {
        const val = parseInt(el.dataset.val, 10);
        const isCorrect = correctSet.has(val);
        const isPicked  = selected.has(val);
        if (isPicked && isCorrect)  el.classList.add('correctReveal');
        if (isPicked && !isCorrect){ el.classList.add('wrongPick'); wrongPicks.push(val); }
        if (!isPicked && isCorrect){ el.classList.add('missedReveal'); missed.push(val); }
    });
    const isPerfect = (wrongPicks.length === 0 && missed.length === 0);
    const rect = pondEl.getBoundingClientRect();
    const cx = rect.left + rect.width / 2;
    const cy = rect.top + rect.height / 2;
    if (isPerfect){
        score++;
        spawnSplashText('✨ Perfect Catch! +1', cx, cy);
        updateHUD();
        setTimeout(() => {
            pondLocked = false;
            if (score >= WIN_POINTS){ endGame(true); }
            else { generateMission(); }
        }, 1000);
    } else {
        lives--;
        updateHUD();
        spawnSplashText('💔 -1 Life', cx, cy);
        setTimeout(showWrongModal, 750);
    }
}

function updateHUD(){
    livesEl.textContent = '❤️'.repeat(Math.max(0, lives)) + '🖤'.repeat(Math.max(0, MAX_LIVES - lives));
    scoreValEl.textContent = score;
    const pct = Math.round((score / WIN_POINTS) * 100);
    pfillEl.style.width = pct + '%';
    plabelEl.textContent = score + ' / ' + WIN_POINTS + ' catches';
    ppctEl.textContent = pct + '%';
    lvlBadgeEl.textContent = 'LVL ' + currentLevel;
}

function endGame(won){
    gameOn = false;
    const st = score >= 9 ? 3 : score >= 6 ? 2 : 1;
    bnrStars.textContent = '⭐'.repeat(st);
    if (won){
        if (currentLevel < MAX_LVL){
            currentLevel++;
            saveProgress(currentLevel);
            bnrIco.textContent = '🏆';
            bnrTitle.textContent = 'Level ' + (currentLevel - 1) + ' Complete!';
            bnrMsg.textContent = '10 perfect catches! Moving to Level ' + currentLevel + '.';
            bnrPlayBtn.textContent = '▶ Play Level ' + currentLevel;
        } else {
            saveProgress(currentLevel);
            bnrIco.textContent = '🎉';
            bnrTitle.textContent = 'Master Angler!';
            bnrMsg.textContent = 'You cleared all ' + MAX_LVL + ' levels!';
            bnrPlayBtn.textContent = '🔄 Play Again';
        }
    } else {
        bnrIco.textContent = '😔';
        bnrTitle.textContent = 'Out of Bait!';
        bnrMsg.textContent = 'Scored ' + score + ' catches. Try again!';
        bnrPlayBtn.textContent = '🔄 Try Again';
    }
    endBanner.classList.add('show');
}

function saveProgress(lvl){
    if (score > highScore){ highScore = score; hsValEl.textContent = highScore; }
    fetch('/student/update-progress', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF },
        body: new URLSearchParams({ game_id: GAME_ID, level: lvl, score: score * 10, _token: CSRF })
    });
}

function startGame(){
    lives = MAX_LIVES;
    score = 0;
    gameOn = true;
    isPaused = false;
    const pauseOv = document.getElementById('pauseOverlay');
    if (pauseOv) pauseOv.classList.remove('active');
    const pauseBtn = document.getElementById('pauseBtn');
    if (pauseBtn){
        pauseBtn.classList.remove('paused');
        pauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
    }
    pondLocked = false;
    wrongModalActive = false;
    clearTimeout(wrongTimer);
    wrongOverlay.classList.remove('show');
    endBanner.classList.remove('show');
    castBtn.disabled = false;
    updateHUD();
    refreshBounds();
    setupPondDecor(pondEl);
    generateMission();
    startFishEngine();
}

startBtn.addEventListener('click', () => {
    overlay.classList.add('hidden');
    gameScreen.style.display = 'flex';
    refreshBounds();
    startGame();
});

bnrPlayBtn.addEventListener('click', () => {
    endBanner.classList.remove('show');
    if (currentLevel > MAX_LVL) currentLevel = CURR_LVL;
    startGame();
});

castBtn.addEventListener('click', castNet);

/* Fullscreen Helper */
window.toggleFullscreen = function(){
    const w = document.getElementById('gw') || document.documentElement;
    if (!document.fullscreenElement) {
        if (w.requestFullscreen) w.requestFullscreen();
        else if (w.webkitRequestFullscreen) w.webkitRequestFullscreen();
    } else {
        if (document.exitFullscreen) document.exitFullscreen();
        else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
    }
};
document.addEventListener('fullscreenchange', () => {
    const fsBtn = document.getElementById('fsBtn');
    if (fsBtn){
        fsBtn.innerHTML = document.fullscreenElement
            ? '<i class="bi bi-fullscreen-exit"></i> <span>Exit</span>'
            : '<i class="bi bi-fullscreen"></i> <span>Full</span>';
    }
    setTimeout(refreshBounds, 200);
});

/* Pause Controller */
window.togglePause = function(){
    if (!gameOn) return;
    // Don't toggle pause if tutorial overlay is currently open
    if (!tutorialOverlay.classList.contains('hidden')) return;

    isPaused = !isPaused;
    const pauseOv = document.getElementById('pauseOverlay');
    const btn = document.getElementById('pauseBtn');
    if (isPaused){
        if (pauseOv) pauseOv.classList.add('active');
        if (btn){
            btn.classList.add('paused');
            btn.innerHTML = '<i class="bi bi-play-fill"></i> <span>Resume</span>';
        }
    } else {
        if (pauseOv) pauseOv.classList.remove('active');
        if (btn){
            btn.classList.remove('paused');
            btn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
        }
    }
};

window.addEventListener('keydown', (e) => {
    if (e.key === 'p' || e.key === 'P' || e.key === 'Escape'){
        if (gameOn && !endBanner.classList.contains('show')){
            if (!tutorialOverlay.classList.contains('hidden')){
                window.closeTut();
            } else {
                window.togglePause();
            }
        }
    }
    if ((e.key === ' ' || e.key === 'Enter') && gameOn && !pondLocked && !isPaused){
        if (document.activeElement && document.activeElement.tagName === 'BUTTON') return;
        e.preventDefault();
        castNet();
    }
});

/* ============================================================
   TUTORIAL CONTROLLER (Step-by-Step, Always on top, In-front of Game)
   ============================================================ */
let tutOpenedFromGame = false;
let currentTutStep = 1;

window.openTut = function(fromGame = false){
    tutOpenedFromGame = fromGame;
    if (fromGame){
        isPaused = true; // Pause fish movement
        const pauseOv = document.getElementById('pauseOverlay');
        if (pauseOv) pauseOv.classList.remove('active'); // Hide pause card so it never conflicts
        const skipBtns = document.querySelectorAll('.tq-tut-skip-btn');
        skipBtns.forEach(b => b.textContent = '← Return to Game');
        const startBtn = document.getElementById('tutStartMissionBtn');
        if (startBtn) startBtn.textContent = '▶ Return to Game';
    } else {
        const skipBtns = document.querySelectorAll('.tq-tut-skip-btn');
        skipBtns.forEach(b => b.textContent = '← Back to Instructions');
        const startBtn = document.getElementById('tutStartMissionBtn');
        if (startBtn) startBtn.textContent = '🚀 Start Fishing!';
    }
    overlay.classList.add('hidden');
    tutorialOverlay.classList.remove('hidden');
    nextTut(1);
};

window.nextTut = function(step){
    if (step < 1) step = 1;
    if (step > 5) step = 5;
    currentTutStep = step;
    for (let i = 1; i <= 5; i++){
        const s = document.getElementById('tutStep' + i);
        if (s) s.classList.toggle('hidden', i !== step);
    }
    const tabs = document.querySelectorAll('#tutStepper .tq-step-tab');
    tabs.forEach((tab, idx) => {
        tab.classList.toggle('active', idx === step - 1);
    });
};

window.closeTut = function(andStartGame = false){
    tutorialOverlay.classList.add('hidden');
    if (tutOpenedFromGame){
        isPaused = false; // Resume game
        const pauseBtn = document.getElementById('pauseBtn');
        if (pauseBtn){
            pauseBtn.classList.remove('paused');
            pauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
        }
    } else {
        if (andStartGame){
            overlay.classList.add('hidden');
            gameScreen.style.display = 'flex';
            refreshBounds();
            startGame();
        } else {
            overlay.classList.remove('hidden');
        }
    }
};

(function init(){
    overlay.classList.remove('hidden');
    tutorialOverlay.classList.add('hidden');
    setupPondDecor(pondEl);
    startFishEngine();
})();

})();
</script>
@endsection
`;

fs.writeFileSync('resources/views/games/factor_pond.blade.php', code, 'utf8');
console.log('Successfully written optimized factor_pond.blade.php');
