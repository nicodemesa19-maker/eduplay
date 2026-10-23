@extends('layouts.app')
@section('hide-nav', true)

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
html, body { width: 100%; height: 100%; overflow: hidden; background: #08090f; -webkit-text-size-adjust: 100%; }
button, a { touch-action: manipulation; -webkit-tap-highlight-color: transparent; }

/* --- Starfield --------------------------------------------- */
/* #gameWrapper is the scroll container so tall layouts (phones) can scroll.
   Its children use margin:auto to stay centered when there is spare room. */
#gameWrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 16px 20px 24px;
    padding-top: max(16px, env(safe-area-inset-top));
    padding-bottom: max(24px, env(safe-area-inset-bottom));
    padding-left: max(20px, env(safe-area-inset-left));
    padding-right: max(20px, env(safe-area-inset-right));
    font-family: 'Inter', sans-serif;
    overflow-x: hidden;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: contain;
    background:
        radial-gradient(ellipse 120% 60% at 50% -10%, rgba(88,28,135,0.55) 0%, transparent 60%),
        radial-gradient(ellipse 80% 40% at 20% 80%, rgba(30,64,175,0.35) 0%, transparent 55%),
        radial-gradient(ellipse 60% 30% at 85% 60%, rgba(220,38,38,0.18) 0%, transparent 50%),
        #05050f;
    z-index: 10;
}

/* Animated twinkling stars (fixed so they stay put while the content scrolls) */
#gameWrapper::before,
#gameWrapper::after {
    content: '';
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
}
#gameWrapper::before {
    background-image:
        radial-gradient(1px 1px at 5% 8%, rgba(255,255,255,.8), transparent),
        radial-gradient(1px 1px at 12% 55%, rgba(255,255,255,.6), transparent),
        radial-gradient(1.5px 1.5px at 18% 22%, rgba(255,255,255,.9), transparent),
        radial-gradient(1px 1px at 28% 75%, rgba(255,255,255,.5), transparent),
        radial-gradient(1.5px 1.5px at 35% 40%, rgba(200,220,255,.8), transparent),
        radial-gradient(1px 1px at 45% 10%, rgba(255,255,255,.6), transparent),
        radial-gradient(1.5px 1.5px at 52% 88%, rgba(255,255,255,.7), transparent),
        radial-gradient(1px 1px at 60% 30%, rgba(255,220,200,.6), transparent),
        radial-gradient(1px 1px at 68% 65%, rgba(255,255,255,.8), transparent),
        radial-gradient(1.5px 1.5px at 75% 20%, rgba(220,200,255,.7), transparent),
        radial-gradient(1px 1px at 82% 50%, rgba(255,255,255,.5), transparent),
        radial-gradient(1.5px 1.5px at 90% 80%, rgba(255,255,255,.8), transparent),
        radial-gradient(1px 1px at 95% 35%, rgba(255,255,255,.6), transparent),
        radial-gradient(1px 1px at 3% 92%, rgba(255,255,255,.4), transparent),
        radial-gradient(1.5px 1.5px at 50% 50%, rgba(255,255,255,.3), transparent);
    animation: twinkle1 4s ease-in-out infinite alternate;
}
#gameWrapper::after {
    background-image:
        radial-gradient(1px 1px at 8% 45%, rgba(255,255,255,.5), transparent),
        radial-gradient(1.5px 1.5px at 20% 10%, rgba(255,255,255,.7), transparent),
        radial-gradient(1px 1px at 32% 90%, rgba(200,230,255,.6), transparent),
        radial-gradient(1px 1px at 42% 60%, rgba(255,255,255,.4), transparent),
        radial-gradient(1.5px 1.5px at 55% 25%, rgba(255,255,255,.8), transparent),
        radial-gradient(1px 1px at 65% 78%, rgba(255,200,220,.5), transparent),
        radial-gradient(1.5px 1.5px at 78% 42%, rgba(255,255,255,.7), transparent),
        radial-gradient(1px 1px at 88% 15%, rgba(255,255,255,.5), transparent),
        radial-gradient(1px 1px at 97% 65%, rgba(255,255,255,.6), transparent),
        radial-gradient(1.5px 1.5px at 14% 70%, rgba(200,255,220,.5), transparent);
    animation: twinkle2 5.5s ease-in-out infinite alternate;
}
@keyframes twinkle1 { 0%{opacity:.4} 50%{opacity:1} 100%{opacity:.6} }
@keyframes twinkle2 { 0%{opacity:.7} 60%{opacity:.3} 100%{opacity:.9} }

/* Floating nebula blobs */
.nebula { position:fixed; border-radius:50%; pointer-events:none; filter:blur(70px); opacity:.18; z-index:0; }
.nebula-1 { width:400px; height:300px; background:radial-gradient(#7c3aed,transparent); top:-80px; left:-100px; animation:drift 18s ease-in-out infinite alternate; }
.nebula-2 { width:350px; height:250px; background:radial-gradient(#1d4ed8,transparent); bottom:10%; right:-80px; animation:drift 22s ease-in-out infinite alternate-reverse; }
.nebula-3 { width:250px; height:200px; background:radial-gradient(#be185d,transparent); bottom:30%; left:5%; animation:drift 15s ease-in-out infinite alternate; }
@keyframes drift { from{transform:translate(0,0) scale(1)} to{transform:translate(30px,20px) scale(1.12)} }

/* All content above starfield — nebulas must stay out of the flex flow */
#gameWrapper > *:not(.nebula) { position: relative; z-index: 1; }
#gameWrapper > .nebula { position: fixed; z-index: 0; }
/* The tutorial is a true full-screen overlay (the rule above would otherwise make it relative) */
#gameWrapper > #tutorialScreen { position: fixed; z-index: 10005; }

/* --- Game-area container ----------------------------------- */
#gameWrap {
    width: 100%;
    max-width: 1320px;
    height: 100%;
    max-height: 960px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin: 0 auto;
}

/* #gameScreen is shown via JS as display:flex — force column + center */
#gameScreen {
    width: 100%;
    height: 100%;
    margin-top: auto;
    margin-bottom: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* --- HUD --------------------------------------------------- */
#hud {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 18px;
    padding: 14px 28px;
    backdrop-filter: blur(16px);
    box-shadow: 0 0 0 1px rgba(139,92,246,0.1), inset 0 1px 0 rgba(255,255,255,0.06);
}
.hud-pill { display: flex; flex-direction: column; align-items: center; gap: 2px; }
.hud-label { font-size: 14px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.3); }
.hud-val   { font-size: 32px; font-weight: 800; color: #fff; line-height: 1; }
.hud-val.red    { color: #f87171; }
.hud-val.yellow { color: #fbbf24; text-shadow: 0 0 12px rgba(251,191,36,0.5); }
.hud-val.teal   { color: #2dd4bf; }
.hud-center { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 5px; }
.level-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(139,92,246,0.18);
    border: 1px solid rgba(139,92,246,0.4);
    border-radius: 99px; padding: 5px 18px;
    font-size: 18px; font-weight: 800; color: #c4b5fd;
    letter-spacing: .5px;
}
.hs-label { font-size: 14px; color: rgba(255,255,255,0.25); font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }

/* --- Progress bar ------------------------------------------ */
#progressWrap { width: 100%; }
#progressTrack { width:100%; height:8px; background:rgba(255,255,255,0.07); border-radius:99px; overflow:hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,.4); }
#progressFill  { height:100%; background:linear-gradient(90deg,#7c3aed,#ef4444,#f97316); border-radius:99px; transition:width .5s cubic-bezier(.34,1.56,.64,1); box-shadow: 0 0 10px rgba(239,68,68,0.5); }
#progressMeta  { display:flex; justify-content:space-between; font-size:15px; color:rgba(255,255,255,0.28); margin-top:4px; }

/* --- Arena ------------------------------------------------- */
#arenaWrap {
    position: relative;
    width: 100%;
    flex: 1;
    display: flex;
    flex-direction: column;
    border-radius: 24px;
    overflow: hidden;
    border: 1px solid rgba(139,92,246,0.25);
    box-shadow:
        0 0 0 1px rgba(139,92,246,0.08),
        0 0 60px rgba(139,92,246,0.12),
        0 0 120px rgba(220,38,38,0.06),
        inset 0 1px 0 rgba(255,255,255,0.06);
    background:
        radial-gradient(ellipse 80% 40% at 50% 0%, rgba(88,28,135,0.3) 0%, transparent 60%),
        radial-gradient(ellipse 60% 30% at 10% 100%, rgba(30,64,175,0.2) 0%, transparent 50%),
        radial-gradient(1.5px 1.5px at 5% 12%, rgba(255,255,255,.6), transparent),
        radial-gradient(1.5px 1.5px at 18% 68%, rgba(255,255,255,.5), transparent),
        radial-gradient(1.5px 1.5px at 33% 28%, rgba(255,255,255,.7), transparent),
        radial-gradient(1.5px 1.5px at 47% 85%, rgba(255,255,255,.4), transparent),
        radial-gradient(1.5px 1.5px at 62% 18%, rgba(255,255,255,.6), transparent),
        radial-gradient(1.5px 1.5px at 76% 55%, rgba(255,255,255,.5), transparent),
        radial-gradient(1.5px 1.5px at 91% 35%, rgba(255,255,255,.7), transparent),
        radial-gradient(1.5px 1.5px at 85% 80%, rgba(255,255,255,.4), transparent),
        radial-gradient(1.5px 1.5px at 55% 48%, rgba(200,180,255,.4), transparent),
        #07070f;
    padding: 28px 32px 32px;
}

/* --- Timer ------------------------------------------------- */
#timerWrap {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 20px;
    background: rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 12px;
    padding: 10px 16px;
}
#timerIcon { font-size: 18px; }
#timerTrack { flex:1; height:11px; background:rgba(255,255,255,0.08); border-radius:99px; overflow:hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,.5); }
#timerFill { height:100%; width:100%; background:linear-gradient(90deg,#2dd4bf,#38bdf8); border-radius:99px; transition:width 1s linear; box-shadow: 0 0 8px rgba(45,212,191,0.6); }
#timerNum { font-size: 22px; font-weight: 800; color: #fff; min-width: 40px; text-align: right; font-variant-numeric: tabular-nums; }
#timerLabel { font-size: 14px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: rgba(255,255,255,0.3); min-width: 44px; }

/* --- Problem card ------------------------------------------ */
#problemCard {
    background: linear-gradient(135deg, rgba(139,92,246,0.1) 0%, rgba(220,38,38,0.08) 100%);
    border: 1px solid rgba(139,92,246,0.25);
    border-radius: 18px;
    padding: 30px 34px;
    text-align: center;
    margin-bottom: 26px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 130px;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.07), 0 0 30px rgba(139,92,246,0.1);
    position: relative;
    overflow: hidden;
}
#problemCard::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: conic-gradient(from 0deg, transparent 0deg, rgba(139,92,246,0.05) 60deg, transparent 120deg);
    animation: rotateSlow 12s linear infinite;
    pointer-events: none;
}
@keyframes rotateSlow { to { transform: rotate(360deg); } }
#problemEyebrow {
    font-size: 15px; font-weight: 800; letter-spacing: 2px;
    text-transform: uppercase; color: #a78bfa;
    margin-bottom: 12px; opacity: .8;
    display: flex; align-items: center; gap: 6px;
}
#problemEyebrow::before,
#problemEyebrow::after { content: ''; display: inline-block; width: 24px; height: 1px; background: rgba(167,139,250,0.4); }
#problemText {
    font-size: 26px; font-weight: 600; color: #fff;
    line-height: 1.6; max-width: 760px;
    position: relative; z-index: 1;
    text-shadow: 0 0 30px rgba(139,92,246,0.3);
}
#problemCard.flashIn { animation: problemFlash .45s cubic-bezier(.34,1.56,.64,1); }
@keyframes problemFlash { 0%{opacity:0;transform:scale(.95) translateY(8px)} 100%{opacity:1;transform:scale(1) translateY(0)} }

/* --- Ship grid --------------------------------------------- */
#shipGrid {
    flex: 1;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    align-content: center;
}

.ship {
    position: relative;
    background: linear-gradient(160deg, rgba(88,28,135,0.22) 0%, rgba(15,8,40,0.7) 100%);
    border: 1.5px solid rgba(139,92,246,0.3);
    border-radius: 22px;
    padding: 26px 14px 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    cursor: pointer;
    min-height: 160px;
    transition: transform .18s cubic-bezier(.34,1.56,.64,1), border-color .18s, box-shadow .18s, background .18s;
    overflow: hidden;
}
/* Subtle inner glow ring */
.ship::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 22px;
    background: radial-gradient(ellipse at 50% 0%, rgba(167,139,250,0.15), transparent 60%);
    pointer-events: none;
}
/* Exhaust flame at bottom */
.ship::after {
    content: '🔥';
    position: absolute;
    bottom: -24px;
    font-size: 24px;
    opacity: 0;
    transition: bottom .2s, opacity .2s;
    filter: blur(2px);
}
/* Hover effects only on devices that actually hover (not touch) */
@media (hover: hover) {
    .ship:hover {
        transform: translateY(-6px);
        border-color: rgba(167,139,250,0.7);
        box-shadow: 0 12px 30px rgba(88,28,135,0.45), 0 0 0 1px rgba(167,139,250,0.25), inset 0 1px 0 rgba(255,255,255,0.1);
        background: linear-gradient(160deg, rgba(109,40,217,0.3) 0%, rgba(30,10,60,0.7) 100%);
    }
    .ship:hover::after { bottom: -6px; opacity: 1; }
}
.ship:active { transform: scale(0.95); }

.ship-num {
    position: absolute; top: 12px; left: 12px;
    width: 26px; height: 26px; border-radius: 8px;
    background: rgba(167,139,250,0.2);
    border: 1px solid rgba(167,139,250,0.5);
    color: #c4b5fd; font-size: 12px; font-weight: 900;
    display: flex; align-items: center; justify-content: center;
}
.ship-lottie {
    width: 90px;
    height: 90px;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    transition: transform .3s ease, opacity .2s ease;
}
.ship-lottie svg {
    width: 100% !important;
    height: 100% !important;
    transform: scale(1.35);
    transform-origin: center center;
}
.ship-icon {
    font-size: 48px; line-height: 1;
    filter: drop-shadow(0 4px 14px rgba(139,92,246,0.5));
    animation: shipHover 3s ease-in-out infinite;
}
.ship:nth-child(2) .ship-icon { animation-delay: .75s; }
.ship:nth-child(3) .ship-icon { animation-delay: 1.5s; }
.ship:nth-child(4) .ship-icon { animation-delay: 2.25s; }
@keyframes shipHover { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-4px)} }

.ship-answer {
    font-size: 38px; font-weight: 900; color: #fff;
    text-shadow: 0 0 16px rgba(167,139,250,0.6);
    letter-spacing: -.5px;
}
.ship-key {
    font-size: 14px; color: rgba(255,255,255,0.25);
    font-weight: 700; letter-spacing: 1px;
    background: rgba(255,255,255,0.06);
    border-radius: 5px; padding: 3px 10px;
}

/* Ship states */
.ship.correct {
    border-color: #34d399;
    background: linear-gradient(160deg, rgba(16,185,129,0.35) 0%, rgba(6,60,40,0.85) 100%);
    box-shadow: 0 0 35px rgba(52,211,153,0.6);
    transform: translateY(-4px) scale(1.03);
    z-index: 20;
}
.ship.wrong {
    border-color: #f87171;
    background: linear-gradient(160deg, rgba(239,68,68,0.25) 0%, rgba(60,10,10,0.7) 100%);
    box-shadow: 0 0 30px rgba(239,68,68,0.3);
    animation: shakeShip .4s ease-in-out;
}
.ship.dim { opacity: .3; pointer-events: none; filter: grayscale(.5); }
@keyframes shakeShip {
    0%,100%{ transform: translateX(0); }
    20%    { transform: translateX(-8px); }
    60%    { transform: translateX(8px); }
}

/* --- Flash overlay ----------------------------------------- */
#flashOverlay {
    position: absolute; inset: 0; pointer-events: none;
    opacity: 0; transition: opacity .12s; border-radius: 24px; z-index: 5;
}

/* --- Controls bar ------------------------------------------ */
#controls {
    width: 100%;
    display: flex; justify-content: space-between; align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.025);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 14px;
    padding: 10px 18px;
}
.ctrl-btn {
    padding: 8px 17px; border-radius: 10px; font-size: 16px; font-weight: 700;
    border: none; cursor: pointer; transition: all .15s;
    text-decoration: none; display: inline-block;
}
.ctrl-btn:active { transform: scale(0.95); }
.btn-exit    { background:rgba(255,255,255,0.05); color:rgba(255,255,255,0.45); border:1px solid rgba(255,255,255,0.08); }
.btn-exit:hover { background:rgba(255,255,255,0.1); color:#fff; }
.btn-fs      { background:rgba(139,92,246,0.1); color:#a78bfa; border:1px solid rgba(139,92,246,0.25); }
.btn-fs:hover { background:rgba(139,92,246,0.2); }
.hint-keys { font-size: 15px; color: rgba(255,255,255,0.2); text-align: center; }
kbd { background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.18); border-radius:4px; padding:1px 5px; font-family:monospace; font-size:14px; color:rgba(255,255,255,0.5); }

/* --- Pause overlay ----------------------------------------- */
#pauseOverlay {
    display: none; position: absolute; inset: 0;
    background: rgba(5,5,15,0.82); backdrop-filter: blur(10px);
    border-radius: 24px; align-items: center; justify-content: center;
    flex-direction: column; z-index: 50;
    text-align: center; padding: 16px;
}
#pauseOverlay.active { display: flex; }
#pauseOverlay h2 { color: #fff; font-size: 2.4rem; font-weight: 900; margin-bottom: 6px; text-shadow: 0 0 30px rgba(167,139,250,0.8); }
#pauseOverlay p  { color: rgba(255,255,255,0.4); font-size: 1.1rem; margin-bottom: 28px; }
.resume-btn {
    background: linear-gradient(135deg,#7c3aed,#4f46e5);
    color: #fff; font-size: 1rem; font-weight: 800;
    padding: 14px 44px; border-radius: 14px; border: none; cursor: pointer;
    box-shadow: 0 6px 24px rgba(124,58,237,.45); transition: all .2s;
    letter-spacing: .5px; min-height: 48px;
}
.resume-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(124,58,237,.6); }

/* --- Instruction screen ------------------------------------ */
#instructionScreen {
    width: 100%; max-width: 760px; margin: auto;
    color: #fff;
}
.instr-card {
    background: linear-gradient(165deg, #0e0b1e 0%, #111533 100%);
    border: 2px solid rgba(139,92,246,.45);
    border-radius: 26px;
    box-shadow: 0 35px 80px rgba(0,0,0,.85), 0 0 50px rgba(139,92,246,.2);
    overflow: hidden; position: relative;
    display: flex; flex-direction: column;
}
.instr-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 4px;
    background: linear-gradient(90deg, #7c3aed, #818cf8, #38bdf8, #34d399);
}
/* Header */
.instr-header {
    display: flex; justify-content: space-between; align-items: center;
    padding: 20px 24px 16px;
    border-bottom: 1px solid rgba(255,255,255,.07);
}
.instr-tag {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: .78rem; font-weight: 800; text-transform: uppercase; letter-spacing: .8px;
    color: #a78bfa; background: rgba(139,92,246,.12);
    border: 1px solid rgba(139,92,246,.35); padding: 5px 14px; border-radius: 50px;
}
.instr-slide-counter {
    font-size: .75rem; font-weight: 700; color: #64748b; letter-spacing: .5px;
}
/* Slide body */
.instr-slide-body { padding: 26px 30px 8px; flex: 1; }
/* Slide hero */
.instr-hero {
    display: flex; align-items: center; gap: 16px; margin-bottom: 16px;
}
.instr-hero-icon {
    font-size: 2.1rem; width: 56px; height: 56px; border-radius: 14px; flex-shrink: 0;
    background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
    display: flex; align-items: center; justify-content: center;
}
.instr-hero h3 { font-size: 1.3rem; font-weight: 900; color: #fff; margin: 0; }
.instr-hero p  { font-size: .9rem; color: #94a3b8; margin: 3px 0 0; }
/* Mini boxes */
.instr-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 11px; margin-bottom: 14px;
}
@media(max-width:500px){ .instr-grid { grid-template-columns: 1fr; } }
.instr-box {
    background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
    border-radius: 14px; padding: 14px 16px;
}
.instr-box h5 {
    font-size: .92rem; font-weight: 800; margin: 0 0 5px;
    display: flex; align-items: center; gap: 6px;
}
.instr-box p { font-size: .85rem; color: #94a3b8; line-height: 1.5; margin: 0; }
.instr-box strong { color: #e9d5ff; }
.instr-box kbd {
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
    border-radius: 4px; padding: 1px 5px; font-size: .72rem; color: #c4b5fd; font-family: monospace;
}
/* Callout */
.instr-callout {
    background: rgba(251,191,36,.08); border: 1px solid rgba(251,191,36,.25);
    border-radius: 10px; padding: 9px 13px; font-size: .81rem; color: #fde68a;
    display: flex; gap: 9px; align-items: flex-start; margin-bottom: 12px; line-height: 1.5;
}
/* Demo ships */
.instr-demo-ships { display: flex; gap: 9px; justify-content: center; margin: 10px 0; }
.instr-demo-ship {
    background: linear-gradient(160deg,rgba(88,28,135,.3),rgba(15,8,40,.7));
    border: 1.5px solid rgba(139,92,246,.4); border-radius: 12px;
    padding: 10px 8px; display: flex; flex-direction: column;
    align-items: center; gap: 5px; min-width: 58px;
}
.instr-demo-ship.ok  { border-color:#34d399; background:linear-gradient(160deg,rgba(16,185,129,.28),rgba(6,60,40,.7)); box-shadow:0 0 14px rgba(52,211,153,.3); }
.instr-demo-ship.bad { border-color:#f87171; background:linear-gradient(160deg,rgba(239,68,68,.22),rgba(60,10,10,.7)); }
.instr-demo-ship span:first-child { font-size: 1.4rem; }
.instr-demo-ship .num { font-size: 1rem; font-weight: 900; color: #fff; }
.instr-demo-ship .lbl { font-size: .65rem; font-weight: 700; color: rgba(255,255,255,.35); }
/* Timer demo */
.instr-timer-demo { display: flex; flex-direction: column; gap: 6px; margin: 10px 0; }
.instr-timer-row   { display: flex; align-items: center; gap: 10px; font-size: .79rem; color: #cbd5e1; }
.instr-timer-bar   { height: 7px; border-radius: 4px; flex: 1; }
/* Dots */
.instr-dots { display: flex; gap: 7px; justify-content: center; margin: 12px 0 10px; }
.instr-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: rgba(139,92,246,.25); border: 1.5px solid rgba(139,92,246,.35);
    transition: all .25s; cursor: pointer;
}
.instr-dot.active {
    background: #a78bfa; border-color: #a78bfa;
    width: 22px; border-radius: 4px;
    box-shadow: 0 0 10px rgba(167,139,250,.5);
}
/* Actions */
.instr-actions {
    display: flex; align-items: center; justify-content: space-between;
    gap: 10px; padding: 0 24px 22px; flex-wrap: wrap;
}
.instr-btn-group { display: flex; gap: 8px; }
.instr-btn-prev {
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15);
    color: #cbd5e1; padding: 8px 18px; border-radius: 50px;
    font-size: .85rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: all .15s;
}
.instr-btn-prev:hover { background: rgba(255,255,255,.14); color: #fff; }
.instr-btn-prev:disabled { opacity: .3; cursor: default; }
.instr-btn-next {
    background: linear-gradient(135deg,#7c3aed,#4f46e5); border: none;
    color: #fff; padding: 8px 22px; border-radius: 50px;
    font-size: .85rem; font-weight: 800; cursor: pointer;
    box-shadow: 0 4px 14px rgba(124,58,237,.45); font-family: inherit;
    transition: transform .18s, box-shadow .18s;
}
.instr-btn-next:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(124,58,237,.65); }
.start-btn {
    background: linear-gradient(135deg,#10b981,#059669); border: none;
    color: #fff; padding: 8px 24px; border-radius: 50px;
    font-size: .85rem; font-weight: 800; cursor: pointer;
    box-shadow: 0 4px 14px rgba(16,185,129,.4); font-family: inherit;
    display: none; align-items: center; gap: 7px;
    transition: transform .18s, box-shadow .18s;
}
.start-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(16,185,129,.55); }
.instr-skip-lnk {
    font-size: .78rem; color: rgba(167,139,250,.45); cursor: pointer;
    text-decoration: underline; text-underline-offset: 3px;
    background: none; border: none; font-family: inherit; transition: color .15s;
}
.instr-skip-lnk:hover { color: rgba(167,139,250,.85); }

/* --- End modal --------------------------------------------- */
#endModal {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.85); backdrop-filter: blur(12px);
    align-items: center; justify-content: center; z-index: 9999; padding: 16px;
    overflow-y: auto;
}
#endModal.show { display: flex; }
.modal-box {
    background: linear-gradient(145deg,#12082a,#0f172a);
    border: 1px solid rgba(139,92,246,0.25);
    border-radius: 28px; padding: 44px 36px; text-align: center;
    max-width: 460px; width: 100%; margin: auto;
    box-shadow: 0 28px 80px rgba(0,0,0,0.8), 0 0 0 1px rgba(139,92,246,0.1), 0 0 60px rgba(88,28,135,0.25);
    animation: modalPop .4s cubic-bezier(.34,1.56,.64,1);
}
@keyframes modalPop { from{opacity:0;transform:scale(.8) translateY(20px)} to{opacity:1;transform:scale(1) translateY(0)} }
.modal-end-icon { font-size: 68px; line-height: 1; margin-bottom: 12px; }
.modal-box h2 { font-size: 1.9rem; font-weight: 900; color: #fff; margin-bottom: 8px; text-shadow: 0 0 20px rgba(167,139,250,0.5); }
.modal-box p  { font-size: 1.1rem; color: rgba(255,255,255,0.45); margin-bottom: 28px; line-height: 1.6; }
.modal-btn-row { display:flex; gap:12px; }
.modal-btn { flex:1; padding:14px; border-radius:14px; font-size:.95rem; font-weight:700; border:none; cursor:pointer; text-align:center; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; gap:6px; transition:all .2s; }
.modal-btn:active { transform:scale(0.97); }
.modal-btn-primary { background:linear-gradient(135deg,#7c3aed,#4f46e5); color:#fff; box-shadow:0 6px 20px rgba(124,58,237,.4); }
.modal-btn-primary:hover { transform:translateY(-2px); box-shadow:0 12px 28px rgba(124,58,237,.55); }
.modal-btn-secondary { background:rgba(255,255,255,0.07); color:rgba(255,255,255,0.7); border:1px solid rgba(255,255,255,0.12); }
.modal-btn-secondary:hover { background:rgba(255,255,255,0.12); color:#fff; }

/* --- Tutorial System --------------------------------------- */
@keyframes tutFadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes tutPopIn{from{transform:scale(0)}to{transform:scale(1)}}
@keyframes tutFlt{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}

/* Overlay */
#tutorialScreen{
    position:fixed;inset:0;
    background:rgba(4,6,18,.92);
    backdrop-filter:blur(14px);
    z-index:10005;
    display:flex;align-items:flex-start;justify-content:center;padding:20px;
    overflow-y:auto;
    animation:tutFadeUp .28s ease;
}
/* Card */
.nw-tut-card{
    background:linear-gradient(165deg,#0e0b1e 0%,#111533 100%);
    border:2px solid rgba(139,92,246,.45);
    border-radius:26px;max-width:820px;width:100%;margin:auto;
    box-shadow:0 35px 80px rgba(0,0,0,.85),0 0 50px rgba(139,92,246,.2);
    overflow:hidden;max-height:94vh;max-height:94dvh;
    display:flex;flex-direction:column;padding:30px 36px 26px;
    position:relative;
}
.nw-tut-card::before{
    content:'';position:absolute;top:0;left:0;right:0;height:4px;
    background:linear-gradient(90deg,#7c3aed,#818cf8,#38bdf8,#34d399);
}
/* Header */
.nw-tut-header{
    display:flex;justify-content:space-between;align-items:center;
    margin-bottom:12px;padding-bottom:12px;
    border-bottom:1px solid rgba(255,255,255,.08);
    flex-shrink:0;
}
.nw-tut-tag{
    display:inline-flex;align-items:center;gap:8px;
    font-size:.82rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;
    color:#a78bfa;background:rgba(139,92,246,.12);
    border:1px solid rgba(139,92,246,.35);padding:5px 14px;border-radius:50px;
}
.nw-tut-close{
    background:transparent;border:none;color:#94a3b8;font-size:1.8rem;
    cursor:pointer;line-height:1;transition:color .18s;min-width:44px;min-height:44px;
}
.nw-tut-close:hover{color:#fff;}
/* Slide body */
.nw-tut-body{display:flex;flex-direction:column;flex:1;min-height:0;overflow-y:auto;-webkit-overflow-scrolling:touch;overscroll-behavior:contain;}
.nw-tut-meta{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;gap:8px;}
.nw-tut-indicator{font-size:.72rem;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;color:#818cf8;}
.nw-tut-slide-num{font-size:.75rem;font-weight:700;color:#64748b;letter-spacing:.5px;white-space:nowrap;}
/* Content area */
.nw-tut-content{flex:1;}
/* Hero row */
.nw-tut-hero{display:flex;align-items:center;gap:12px;margin-bottom:10px;}
.nw-tut-hero-icon{
    font-size:2.1rem;width:52px;height:52px;border-radius:14px;
    display:flex;align-items:center;justify-content:center;
    background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);flex-shrink:0;
}
.nw-tut-hero h3{font-size:1.35rem;font-weight:900;color:#fff;margin:0;}
.nw-tut-hero p{font-size:.92rem;color:#94a3b8;margin:3px 0 0;}
/* Description */
.nw-tut-desc{font-size:1rem;line-height:1.6;color:#cbd5e1;margin:0 0 14px;}
/* Mini boxes grid */
.nw-tut-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;}
@media(max-width:560px){.nw-tut-grid{grid-template-columns:1fr;}}
.nw-tut-box{
    background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);
    border-radius:14px;padding:14px 16px;
}
.nw-tut-box h5{font-size:.95rem;font-weight:800;margin:0 0 6px;display:flex;align-items:center;gap:6px;}
.nw-tut-box p{font-size:.87rem;color:#94a3b8;line-height:1.5;margin:0;}
/* Callout tip */
.nw-tut-callout{
    background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.25);
    border-radius:10px;padding:9px 13px;font-size:.82rem;color:#fde68a;
    display:flex;gap:9px;align-items:flex-start;margin-bottom:12px;line-height:1.5;
}
/* Example card */
.nw-tut-example{
    background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.25);
    border-radius:12px;padding:11px 15px;margin-bottom:12px;
    font-size:.83rem;color:#d1fae5;line-height:1.5;
}
.nw-tut-example b{color:#34d399;}
/* Demo ships (reused) */
.demo-ships{display:flex;gap:10px;justify-content:center;margin:10px 0;}
.demo-ship{background:linear-gradient(160deg,rgba(88,28,135,.3),rgba(15,8,40,.7));border:1.5px solid rgba(139,92,246,.4);border-radius:14px;padding:12px 10px;display:flex;flex-direction:column;align-items:center;gap:6px;min-width:64px;}
.demo-ship.correct-ship{border-color:#34d399;background:linear-gradient(160deg,rgba(16,185,129,.28),rgba(6,60,40,.7));box-shadow:0 0 16px rgba(52,211,153,.35);}
.demo-ship.wrong-ship{border-color:#f87171;background:linear-gradient(160deg,rgba(239,68,68,.22),rgba(60,10,10,.7));}
.demo-ship-icon{font-size:26px;}
.demo-ship-num{font-size:18px;font-weight:900;color:#fff;}
.demo-ship-lbl{font-size:9px;font-weight:700;color:rgba(255,255,255,.35);}
/* Timer bar demo */
.nw-timer-demo{display:flex;flex-direction:column;gap:6px;margin:8px 0;}
.nw-timer-row{display:flex;align-items:center;gap:10px;font-size:.8rem;color:#cbd5e1;}
.nw-timer-bar{height:8px;border-radius:4px;flex:1;}
/* Dots */
.nw-tut-dots{display:flex;gap:7px;justify-content:center;margin:14px 0 12px;}
.nw-tut-dot{
    width:8px;height:8px;border-radius:50%;
    background:rgba(139,92,246,.25);border:1.5px solid rgba(139,92,246,.35);
    transition:all .25s;cursor:pointer;
}
.nw-tut-dot.active{
    background:#a78bfa;border-color:#a78bfa;
    width:22px;border-radius:4px;
    box-shadow:0 0 10px rgba(167,139,250,.5);
}
/* Actions */
.nw-tut-actions{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
.nw-tut-skip{
    background:rgba(255,255,255,.07);border:1px solid rgba(251,191,36,.28);
    color:#fde68a;padding:8px 16px;border-radius:50px;
    font-size:.8rem;font-weight:700;cursor:pointer;transition:all .15s;
    font-family:inherit;
}
.nw-tut-skip:hover{background:rgba(255,255,255,.12);color:#fff;}
.nw-tut-btn-group{display:flex;gap:8px;}
.nw-tut-prev{
    background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);
    color:#cbd5e1;padding:8px 18px;border-radius:50px;
    font-size:.85rem;font-weight:700;cursor:pointer;transition:all .15s;font-family:inherit;
}
.nw-tut-prev:hover{background:rgba(255,255,255,.14);color:#fff;}
.nw-tut-prev:disabled{opacity:.3;cursor:default;}
.nw-tut-next{
    background:linear-gradient(135deg,#7c3aed,#4f46e5);border:none;
    color:#fff;padding:8px 22px;border-radius:50px;
    font-size:.85rem;font-weight:800;cursor:pointer;
    box-shadow:0 4px 14px rgba(124,58,237,.45);
    transition:transform .18s cubic-bezier(.34,1.56,.64,1),box-shadow .18s;font-family:inherit;
}
.nw-tut-next:hover{transform:translateY(-2px);box-shadow:0 10px 26px rgba(124,58,237,.65);}
.nw-tut-start{
    background:linear-gradient(135deg,#10b981,#059669);border:none;
    color:#fff;padding:8px 22px;border-radius:50px;
    font-size:.85rem;font-weight:800;cursor:pointer;
    box-shadow:0 4px 14px rgba(16,185,129,.4);
    transition:transform .18s,box-shadow .18s;font-family:inherit;display:none;
}
.nw-tut-start:hover{transform:translateY(-2px);box-shadow:0 10px 26px rgba(16,185,129,.55);}

/* Trial Screen */
#nwTrialScreen{display:none;width:100%;max-width:680px;margin:auto;background:rgba(12,5,30,.94);border:1px solid rgba(139,92,246,.38);border-radius:28px;padding:28px 24px 22px;text-align:center;backdrop-filter:blur(28px);box-shadow:0 0 70px rgba(88,28,135,.35);animation:tutFadeUp .5s cubic-bezier(.34,1.56,.64,1);color:#fff;position:relative;z-index:1;}
.trial-badge-nw{display:inline-flex;align-items:center;gap:7px;background:rgba(139,92,246,.18);border:1.5px solid rgba(167,139,250,.38);border-radius:99px;padding:5px 16px;font-size:11px;font-weight:800;color:#c4b5fd;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:8px;}
.trial-title-nw{font-size:1.2rem;font-weight:900;color:#fff;margin-bottom:4px;}
.trial-sub-nw{font-size:12.5px;color:rgba(200,180,255,.5);margin-bottom:16px;line-height:1.5;}
/* trial problem card */
.trial-prob-card{background:linear-gradient(135deg,rgba(139,92,246,.12),rgba(220,38,38,.08));border:1px solid rgba(139,92,246,.28);border-radius:16px;padding:20px 24px;margin-bottom:20px;position:relative;overflow:hidden;}
.trial-prob-eye{font-size:10px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#a78bfa;margin-bottom:10px;display:flex;align-items:center;gap:6px;justify-content:center;}
.trial-prob-eye::before,.trial-prob-eye::after{content:'';display:inline-block;width:24px;height:1px;background:rgba(167,139,250,.4);}
.trial-prob-txt{font-size:17px;font-weight:600;color:#fff;line-height:1.6;}
/* trial ships */
#nwTrialShipGrid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:14px;}
.trial-ship{position:relative;background:linear-gradient(160deg,rgba(88,28,135,.22),rgba(15,8,40,.7));border:1.5px solid rgba(139,92,246,.3);border-radius:18px;padding:18px 10px 14px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;cursor:pointer;transition:transform .18s cubic-bezier(.34,1.56,.64,1),border-color .18s,box-shadow .18s;overflow:hidden;}
.trial-ship::after{content:'🔥';position:absolute;bottom:-20px;font-size:20px;opacity:0;transition:bottom .2s,opacity .2s;}
@media (hover:hover){
    .trial-ship:hover{transform:translateY(-5px);border-color:rgba(167,139,250,.7);box-shadow:0 10px 28px rgba(88,28,135,.4);}
    .trial-ship:hover::after{bottom:-4px;opacity:1;}
}
.trial-ship:active{transform:scale(.95);}
.trial-ship.correct{border-color:#34d399;background:linear-gradient(160deg,rgba(16,185,129,.35),rgba(6,60,40,.85));box-shadow:0 0 24px rgba(52,211,153,.5);transform:translateY(-4px) scale(1.03);}
.trial-ship.wrong{border-color:#f87171;background:linear-gradient(160deg,rgba(239,68,68,.25),rgba(60,10,10,.7));box-shadow:0 0 24px rgba(239,68,68,.3);animation:nwShake .4s ease-in-out;}
.trial-ship.dim{opacity:.3;pointer-events:none;filter:grayscale(.5);}
@keyframes nwShake{0%,100%{transform:translateX(0)}20%{transform:translateX(-7px)}60%{transform:translateX(7px)}}
.trial-ship-icon{font-size:36px;line-height:1;}
.trial-ship-ans{font-size:26px;font-weight:900;color:#fff;text-shadow:0 0 14px rgba(167,139,250,.5);}
.trial-skip-nw{font-size:11px;color:rgba(167,139,250,.4);cursor:pointer;background:none;border:none;text-decoration:underline;text-underline-offset:3px;transition:color .15s;padding:10px 12px;}
.trial-skip-nw:hover{color:rgba(167,139,250,.75);}

/* Ready Modal */
#nwReadyOverlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.8);backdrop-filter:blur(8px);z-index:500;align-items:center;justify-content:center;}
#nwReadyOverlay.show{display:flex;animation:tutFadeUp .3s ease;}
.nw-ready-box{background:linear-gradient(135deg,rgba(12,5,30,.97),rgba(10,20,50,.97));border:1.5px solid rgba(139,92,246,.5);border-radius:24px;padding:34px 30px;text-align:center;max-width:400px;width:100%;margin:auto;box-shadow:0 0 80px rgba(88,28,135,.5);animation:tutPopIn .45s cubic-bezier(.34,1.56,.64,1);}
.nw-ready-ico{font-size:60px;line-height:1;margin-bottom:10px;animation:tutFlt 3s ease-in-out infinite;}
.nw-ready-stars{font-size:26px;letter-spacing:6px;margin-bottom:8px;}
.nw-ready-title{font-size:1.5rem;font-weight:900;color:#fff;margin-bottom:5px;text-shadow:0 0 18px rgba(167,139,250,.5);}
.nw-ready-sub{font-size:13px;color:rgba(200,180,255,.6);line-height:1.6;margin-bottom:16px;}
.nw-ready-start{background:linear-gradient(135deg,#7c3aed,#4f46e5);color:#fff;font-size:15px;font-weight:900;padding:13px 30px;border-radius:13px;border:none;cursor:pointer;box-shadow:0 8px 28px rgba(124,58,237,.5);transition:transform .18s cubic-bezier(.34,1.56,.64,1),box-shadow .18s;display:block;width:100%;margin-bottom:9px;min-height:48px;}
.nw-ready-start:hover{transform:translateY(-3px);box-shadow:0 16px 38px rgba(124,58,237,.65);}
.nw-ready-practice{background:rgba(139,92,246,.1);color:#c4b5fd;border:1.5px solid rgba(139,92,246,.3);padding:10px 22px;border-radius:12px;font-size:13px;font-weight:700;cursor:pointer;transition:all .15s;display:block;width:100%;min-height:44px;}
.nw-ready-practice:hover{background:rgba(139,92,246,.18);color:#e9d5ff;}

/* ============================================================
   RESPONSIVE / MOBILE-ADAPTIVE
   ============================================================ */

/* Tablets, small windows and short screens: let the game area grow and scroll
   instead of squashing into 100% height */
@media (max-width: 900px), (max-height: 700px) {
    #gameScreen { height: auto !important; min-height: 100%; }
    #gameWrap   { height: auto; max-height: none; }
    #arenaWrap  { flex: none; }
}

/* Touch devices: hide keyboard-only hints */
@media (hover: none) and (pointer: coarse) {
    .ship-key   { display: none; }
    .hint-keys  { display: none; }
}

@media (prefers-reduced-motion: reduce) {
    #gameWrapper::before, #gameWrapper::after, .nebula, #problemCard::before, .ship-icon, .nw-ready-ico { animation: none; }
}

/* Phones */
@media (max-width: 640px) {
    #gameWrapper {
        padding: 10px 10px 16px;
        padding-top: max(10px, env(safe-area-inset-top));
        padding-bottom: max(16px, env(safe-area-inset-bottom));
        padding-left: max(10px, env(safe-area-inset-left));
        padding-right: max(10px, env(safe-area-inset-right));
    }
    #gameWrap { gap: 10px; }

    /* HUD — compact grid: Lives | Level | Score, then nav row */
    #hud {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        align-items: center;
        gap: 10px 8px;
        padding: 12px 12px;
        border-radius: 16px;
    }
    .hud-right-group { display: contents !important; }
    #hud .hud-pill { align-items: center; }
    #hud .hud-center { flex: none; gap: 3px; }
    .hud-label { font-size: 11px; letter-spacing: 1px; }
    .hud-val   { font-size: 24px; }
    .level-badge { font-size: 14px; padding: 4px 12px; }
    .hs-label { font-size: 10px; }
    #hud .eduplay-game-nav { grid-column: 1 / -1; display: flex !important; gap: 8px; width: 100%; }
    .eduplay-game-nav .eduplay-nav-btn { flex: 1; min-height: 44px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: .75rem; padding: 8px 6px; }
    .eduplay-game-nav .eduplay-nav-btn span { font-size: .72rem; }

    #progressMeta { font-size: 11px; }

    /* Arena */
    #arenaWrap { padding: 14px 12px 16px; border-radius: 18px; }
    #flashOverlay { border-radius: 18px; }

    #timerWrap { gap: 8px; margin-bottom: 12px; padding: 8px 10px; }
    #timerLabel { font-size: 11px; min-width: 0; }
    #timerIcon { font-size: 15px; }
    #timerTrack { height: 9px; }
    #timerNum { font-size: 17px; min-width: 34px; }

    #problemCard { padding: 16px 14px; margin-bottom: 14px; min-height: 96px; border-radius: 14px; }
    #problemEyebrow { font-size: 11px; margin-bottom: 8px; letter-spacing: 1.5px; }
    #problemEyebrow::before, #problemEyebrow::after { width: 14px; }
    #problemText { font-size: 16px; line-height: 1.5; }

    #shipGrid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .ship { min-height: 130px; padding: 22px 8px 12px; gap: 6px; border-radius: 18px; }
    .ship::before { border-radius: 18px; }
    .ship-num { top: 8px; left: 8px; width: 22px; height: 22px; font-size: 11px; border-radius: 7px; }
    .ship-lottie { width: 64px; height: 64px; }
    .ship-icon { font-size: 36px; }
    .ship-answer { font-size: 26px; }

    /* Controls bar */
    #controls { padding: 8px 10px; justify-content: center; }
    .ctrl-btn { min-height: 44px; padding: 10px 18px; font-size: 14px; }

    /* Pause becomes a full-screen dialog so it's centered in the viewport, not the (scrolled) arena */
    #pauseOverlay { position: fixed; z-index: 9998; border-radius: 0; }
    #pauseOverlay h2 { font-size: 1.8rem; }
    #pauseOverlay p { font-size: .95rem; margin-bottom: 20px; }
    #pauseOverlay .eduplay-game-nav { flex-direction: column; width: 100%; max-width: 260px; }

    /* End modal */
    .modal-box { padding: 28px 20px; border-radius: 22px; }
    .modal-end-icon { font-size: 54px; }
    .modal-box h2 { font-size: 1.5rem; }
    .modal-box p { font-size: 1rem; margin-bottom: 20px; }
    .modal-btn-row { flex-direction: column !important; align-items: stretch !important; }
    .modal-btn-row .modal-btn, .modal-btn-row .eduplay-nav-btn { width: 100%; min-height: 46px; justify-content: center; }

    /* Instruction screen */
    .instr-card { border-radius: 20px; }
    .instr-header { padding: 14px 16px 12px; }
    .instr-slide-body { padding: 16px 16px 4px; }
    .instr-hero { gap: 12px; margin-bottom: 12px; }
    .instr-hero-icon { width: 44px; height: 44px; font-size: 1.6rem; }
    .instr-hero h3 { font-size: 1.1rem; }
    .instr-hero p { font-size: .82rem; }
    .instr-demo-ships { gap: 6px; }
    .instr-demo-ship { min-width: 0; flex: 1; padding: 8px 4px; }
    .instr-actions { padding: 0 14px 16px; }
    .instr-btn-prev, .instr-btn-next, .start-btn { min-height: 44px; }
    .instr-skip-lnk { padding: 10px 6px; }

    /* Tutorial — full-screen sheet */
    #tutorialScreen { padding: 0; align-items: stretch; }
    .nw-tut-card {
        max-width: none; max-height: none; height: 100%; height: 100dvh;
        border-radius: 0; border-width: 0; margin: 0;
        padding: 16px 14px calc(12px + env(safe-area-inset-bottom));
    }
    .nw-tut-tag { font-size: .72rem; padding: 5px 11px; }
    .nw-tut-hero { gap: 10px; }
    .nw-tut-hero-icon { width: 44px; height: 44px; font-size: 1.7rem; }
    .nw-tut-hero h3 { font-size: 1.12rem; }
    .nw-tut-hero p { font-size: .82rem; }
    .nw-tut-desc { font-size: .92rem; margin-bottom: 12px; }
    .nw-tut-box { padding: 12px 13px; }
    .nw-tut-box h5 { font-size: .9rem; }
    .nw-tut-box p { font-size: .84rem; }
    .demo-ships { gap: 6px; }
    .demo-ship { min-width: 0; flex: 1; padding: 10px 4px; }
    .nw-tut-actions { position: sticky; bottom: 0; background: #0f1330; padding: 10px 0 4px; }
    .nw-tut-skip { min-height: 44px; padding: 10px 12px; font-size: .75rem; }
    .nw-tut-prev, .nw-tut-next, .nw-tut-start { min-height: 44px; padding: 10px 16px; font-size: .85rem; }

    /* Trial */
    #nwTrialScreen { padding: 18px 14px 14px; border-radius: 22px; }
    .trial-prob-card { padding: 14px 14px; margin-bottom: 14px; }
    .trial-prob-txt { font-size: 15px; }
    #nwTrialShipGrid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .trial-ship { padding: 14px 8px 12px; }
    .trial-ship .ship-lottie { width: 64px; height: 64px; }
    .trial-ship-ans { font-size: 22px; }

    /* Ready modal */
    .nw-ready-box { padding: 26px 18px; border-radius: 20px; }
    .nw-ready-ico { font-size: 50px; }
    .nw-ready-title { font-size: 1.3rem; }
}

/* Small phones */
@media (max-width: 380px) {
    .hud-val { font-size: 21px; }
    .eduplay-game-nav .eduplay-nav-btn span { display: none; }
    #problemText { font-size: 15px; }
    .ship-answer { font-size: 23px; }
    .ship-lottie { width: 56px; height: 56px; }
}

/* Landscape phones: 4-across ships, compact chrome */
@media (max-height: 520px) and (orientation: landscape) {
    #gameWrapper { padding: 8px 12px; }
    #gameWrap { gap: 8px; }
    #hud { padding: 6px 14px; }
    .hud-val { font-size: 20px; }
    .hud-label { font-size: 10px; }
    .level-badge { font-size: 13px; padding: 3px 12px; }
    .eduplay-game-nav .eduplay-nav-btn { min-height: 38px; }
    #progressMeta { display: none; }
    #arenaWrap { padding: 12px 16px 14px; border-radius: 16px; }
    #timerWrap { margin-bottom: 8px; padding: 6px 10px; }
    #problemCard { padding: 12px 16px; margin-bottom: 10px; min-height: 0; }
    #problemEyebrow { margin-bottom: 4px; font-size: 11px; }
    #problemText { font-size: 15px; line-height: 1.4; }
    #shipGrid { gap: 10px; }
    .ship { min-height: 108px; padding: 16px 6px 10px; gap: 4px; }
    .ship-lottie { width: 52px; height: 52px; }
    .ship-answer { font-size: 22px; }
    .ship-key { display: none; }
    #controls { display: none; }
    #pauseOverlay { position: fixed; z-index: 9998; border-radius: 0; }
    .nw-tut-card { height: auto; max-height: 100dvh; border-radius: 18px; padding: 14px 18px; }
    #tutorialScreen { padding: 8px; }
}
</style>

{{-- -- GAME WRAPPER -- --}}
<div id="gameWrapper">
    <div class="nebula nebula-1"></div>
    <div class="nebula nebula-2"></div>
    <div class="nebula nebula-3"></div>

{{-- TUTORIAL SCREEN --}}
<div id="tutorialScreen">
  <div class="nw-tut-card">

    {{-- Header --}}
    <div class="nw-tut-header">
      <div class="nw-tut-tag">
        <i class="bi bi-rocket-takeoff-fill"></i> How to Play
      </div>
      <button type="button" class="nw-tut-close" onclick="nwSkipTutorial()" title="Skip Tutorial" aria-label="Skip tutorial">&times;</button>
    </div>

    {{-- Slide body --}}
    <div class="nw-tut-body">

      <div class="nw-tut-meta">
        <span class="nw-tut-indicator" id="nwTutIndicator">NUMBER WARS BRIEFING</span>
        <span class="nw-tut-slide-num" id="nwTutSlideNum">SLIDE 1 OF 5</span>
      </div>

      {{-- -- Slide 0: Welcome -- --}}
      <div class="nw-tut-content" id="nwTutStep0">
        <div class="nw-tut-hero">
          <div class="nw-tut-hero-icon" style="color:#a78bfa;">🎮</div>
          <div>
            <h3>Welcome to Number Wars!</h3>
            <p>Solve space math problems and launch the correct ship to win!</p>
          </div>
        </div>
        <p class="nw-tut-desc">
          You are a Space Commander on a mission. Each round gives you a <strong style="color:#c4b5fd;">math word problem</strong> and four ships carrying different answers — only one is right. Pick wisely!
        </p>
        <div class="nw-tut-grid">
          <div class="nw-tut-box">
            <h5 style="color:#a78bfa;"><i class="bi bi-layers-fill"></i> 15 Levels</h5>
            <p>Work your way through levels with increasingly tricky word problems.</p>
          </div>
          <div class="nw-tut-box">
            <h5 style="color:#38bdf8;"><i class="bi bi-controller"></i> Simple Controls</h5>
            <p>Tap (or click) any ship <strong style="color:#fff;">or</strong> press <kbd style="background:rgba(255,255,255,.12);border-radius:4px;padding:1px 5px;font-size:.75rem;">1</kbd>–<kbd style="background:rgba(255,255,255,.12);border-radius:4px;padding:1px 5px;font-size:.75rem;">4</kbd> on your keyboard.</p>
          </div>
          <div class="nw-tut-box">
            <h5 style="color:#34d399;"><i class="bi bi-trophy-fill"></i> Goal</h5>
            <p>Reach <strong style="color:#fff;">10 correct answers</strong> to complete each level and advance.</p>
          </div>
          <div class="nw-tut-box">
            <h5 style="color:#fbbf24;"><i class="bi bi-heart-fill"></i> 3 Lives</h5>
            <p>You have <strong style="color:#fff;">3 lives</strong>. Wrong answers and timeouts cost you one life each.</p>
          </div>
        </div>
      </div>

      {{-- -- Slide 1: Read the Mission -- --}}
      <div class="nw-tut-content" id="nwTutStep1" style="display:none;">
        <div class="nw-tut-hero">
          <div class="nw-tut-hero-icon" style="color:#38bdf8;">📡</div>
          <div>
            <h3>Read the Mission Briefing</h3>
            <p>A space-themed math word problem appears every round.</p>
          </div>
        </div>
        <p class="nw-tut-desc">
          Read the problem carefully and <strong style="color:#c4b5fd;">solve it in your head</strong> before looking at the ships. The problem tells you everything you need!
        </p>
        <div class="nw-tut-example">
          <b>📝 Example Mission:</b><br>
          "There are <b>3 spaceships</b>, each carrying <b>2 astronauts</b>. How many astronauts in total?"<br><br>
          Step 1: Read the numbers — 3 ships × 2 astronauts<br>
          Step 2: Solve it — 3 × 2 = <b>6</b><br>
          Step 3: Find the ship with <b>6</b> and launch it! 🚀
        </div>
        <div class="nw-tut-callout">
          <i class="bi bi-lightbulb-fill" style="flex-shrink:0;"></i>
          <span><strong>Tip:</strong> Solve the math first, then search for your answer — don't just guess from the ship numbers!</span>
        </div>
      </div>

      {{-- -- Slide 2: Pick a Ship -- --}}
      <div class="nw-tut-content" id="nwTutStep2" style="display:none;">
        <div class="nw-tut-hero">
          <div class="nw-tut-hero-icon" style="color:#34d399;">🚀</div>
          <div>
            <h3>Pick the Correct Ship</h3>
            <p>Four ships appear below the mission — only one is right.</p>
          </div>
        </div>
        <p class="nw-tut-desc">Each ship has a number on it. Find the one that matches your answer and tap it (or press its number key).</p>
        <div class="demo-ships" style="margin-bottom:12px;">
          <div class="demo-ship"><div class="demo-ship-icon">🚢</div><div class="demo-ship-num">4</div><div class="demo-ship-lbl">Key [1]</div></div>
          <div class="demo-ship correct-ship"><div class="demo-ship-icon">🚢</div><div class="demo-ship-num">6</div><div class="demo-ship-lbl" style="color:#34d399;">✅ Correct!</div></div>
          <div class="demo-ship"><div class="demo-ship-icon">⛵</div><div class="demo-ship-num">8</div><div class="demo-ship-lbl">Key [3]</div></div>
          <div class="demo-ship"><div class="demo-ship-icon">🚢</div><div class="demo-ship-num">5</div><div class="demo-ship-lbl">Key [4]</div></div>
        </div>
        <div class="nw-tut-grid">
          <div class="nw-tut-box" style="border-color:rgba(52,211,153,.25);">
            <h5 style="color:#34d399;"><i class="bi bi-check-circle-fill"></i> Correct Ship</h5>
            <p>Glows <b style="color:#34d399;">green</b> and launches! You earn <b style="color:#fff;">+1 point</b>.</p>
          </div>
          <div class="nw-tut-box" style="border-color:rgba(248,113,113,.25);">
            <h5 style="color:#f87171;"><i class="bi bi-x-circle-fill"></i> Wrong Ship</h5>
            <p>Shakes <b style="color:#f87171;">red</b> and you lose <b style="color:#fff;">1 life ❤️</b>. The correct ship is revealed.</p>
          </div>
        </div>
      </div>

      {{-- -- Slide 3: Timer -- --}}
      <div class="nw-tut-content" id="nwTutStep3" style="display:none;">
        <div class="nw-tut-hero">
          <div class="nw-tut-hero-icon" style="color:#f59e0b;">⏱️</div>
          <div>
            <h3>Beat the 60-Second Timer</h3>
            <p>A progress bar at the top shows how much time is left.</p>
          </div>
        </div>
        <p class="nw-tut-desc">Solve and answer before the bar runs out. The color changes as time gets low — it's your warning signal!</p>
        <div class="nw-timer-demo">
          <div class="nw-timer-row">
            <span>🟢 Plenty of time</span>
            <div class="nw-timer-bar" style="background:linear-gradient(90deg,#38bdf8,#06b6d4);width:85%;"></div>
          </div>
          <div class="nw-timer-row">
            <span>🟡 Getting close</span>
            <div class="nw-timer-bar" style="background:linear-gradient(90deg,#f59e0b,#d97706);width:45%;"></div>
          </div>
          <div class="nw-timer-row">
            <span>🔴 Hurry! (&lt;10s)</span>
            <div class="nw-timer-bar" style="background:linear-gradient(90deg,#ef4444,#dc2626);width:15%;"></div>
          </div>
        </div>
        <div class="nw-tut-callout">
          <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0;"></i>
          <span>If the timer reaches <strong>0</strong>, you automatically <strong>lose 1 life</strong> — just like a wrong answer!</span>
        </div>
      </div>

      {{-- -- Slide 4: Lives & Score -- --}}
      <div class="nw-tut-content" id="nwTutStep4" style="display:none;">
        <div class="nw-tut-hero">
          <div class="nw-tut-hero-icon" style="color:#f87171;">❤️</div>
          <div>
            <h3>Lives &amp; Scoring</h3>
            <p>Manage your lives and rack up 10 points to win the level.</p>
          </div>
        </div>
        <div class="nw-tut-grid">
          <div class="nw-tut-box" style="border-color:rgba(52,211,153,.25);">
            <h5 style="color:#34d399;"><i class="bi bi-check2-circle"></i> Correct Answer</h5>
            <p>+1 point added to your score. The mission continues to the next problem!</p>
          </div>
          <div class="nw-tut-box" style="border-color:rgba(248,113,113,.25);">
            <h5 style="color:#f87171;"><i class="bi bi-heartbreak-fill"></i> Wrong or Timeout</h5>
            <p>You lose 1 life. Lose all 3 lives and the game ends early.</p>
          </div>
          <div class="nw-tut-box" style="border-color:rgba(251,191,36,.25);">
            <h5 style="color:#fbbf24;"><i class="bi bi-star-fill"></i> Win Condition</h5>
            <p>Reach <strong style="color:#fff;">10 correct answers</strong> to complete the level and unlock the next one!</p>
          </div>
          <div class="nw-tut-box" style="border-color:rgba(167,139,250,.25);">
            <h5 style="color:#a78bfa;"><i class="bi bi-graph-up-arrow"></i> High Score</h5>
            <p>Your <strong style="color:#fff;">best score</strong> per level is saved. Try to beat it on your next run!</p>
          </div>
        </div>
        <div class="nw-tut-callout">
          <i class="bi bi-rocket-takeoff-fill" style="flex-shrink:0;"></i>
          <span>You're ready, Commander! A quick <strong>practice round</strong> is next — launch the correct ship to prove it!</span>
        </div>
      </div>

      {{-- Dots --}}
      <div class="nw-tut-dots" id="nwTutDots">
        <div class="nw-tut-dot active" onclick="nwGoToStep(0)"></div>
        <div class="nw-tut-dot" onclick="nwGoToStep(1)"></div>
        <div class="nw-tut-dot" onclick="nwGoToStep(2)"></div>
        <div class="nw-tut-dot" onclick="nwGoToStep(3)"></div>
        <div class="nw-tut-dot" onclick="nwGoToStep(4)"></div>
      </div>

      {{-- Actions --}}
      <div class="nw-tut-actions">
        <button type="button" class="nw-tut-skip" onclick="nwSkipTutorial()">Skip to Game ⏩</button>
        <div class="nw-tut-btn-group">
          <button type="button" class="nw-tut-prev" id="nwTutBack" onclick="nwTutNav(-1)" disabled>◀️ Back</button>
          <button type="button" class="nw-tut-next" id="nwTutNext" onclick="nwTutNav(1)">Continue ▶️</button>
          <button type="button" class="nw-tut-start" id="nwTutStart" onclick="nwShowTrialScreen()">Start Practice! 🚀</button>
        </div>
      </div>

    </div>{{-- /.nw-tut-body --}}
  </div>{{-- /.nw-tut-card --}}
</div>

{{-- TRIAL SCREEN --}}
<div id="nwTrialScreen">
    <div class="trial-badge-nw">🎮 Practice Round</div>
    <h2 class="trial-title-nw">Trial Mission</h2>
    <p class="trial-sub-nw">Read the problem below and tap the ship with the correct answer!</p>
    <div class="trial-prob-card">
        <div class="trial-prob-eye">Mission Briefing</div>
        <div class="trial-prob-txt" id="nwTrialProbText">Loading...</div>
    </div>
    <div id="nwTrialShipGrid"></div>
    <button class="trial-skip-nw" onclick="nwSkipToRealGame()">Skip practice &amp; play now</button>
</div>

{{-- INSTRUCTION SCREEN --}}
<div id="instructionScreen" style="display:none;">
  <div class="instr-card">

    {{-- Header --}}
    <div class="instr-header">
      <div class="instr-tag"><i class="bi bi-rocket-takeoff-fill"></i> Number Wars</div>
      <span class="instr-slide-counter" id="instrSlideCounter">SLIDE 1 OF 4</span>
    </div>

    {{-- -- Slide 0: Welcome -- --}}
    <div class="instr-slide-body" id="instrSlide0">
      <div class="instr-hero">
        <div class="instr-hero-icon" style="color:#a78bfa;">🌌</div>
        <div>
          <h3>Welcome, Space Commander!</h3>
          <p>A quick briefing before your mission begins.</p>
        </div>
      </div>
      <div class="instr-grid">
        <div class="instr-box">
          <h5 style="color:#38bdf8;"><i class="bi bi-layers-fill"></i> 15 Levels</h5>
          <p>Work through levels with increasingly tricky space math problems.</p>
        </div>
        <div class="instr-box">
          <h5 style="color:#34d399;"><i class="bi bi-trophy-fill"></i> Win Condition</h5>
          <p>Get <strong>10 correct answers</strong> to complete each level and advance.</p>
        </div>
        <div class="instr-box">
          <h5 style="color:#f87171;"><i class="bi bi-heart-fill"></i> 3 Lives</h5>
          <p>Wrong answers and timeouts each cost <strong>1 life</strong>. Lose all 3 and it's over!</p>
        </div>
        <div class="instr-box">
          <h5 style="color:#fbbf24;"><i class="bi bi-graph-up-arrow"></i> High Score</h5>
          <p>Your <strong>best score</strong> per level is saved. Challenge yourself to beat it!</p>
        </div>
      </div>
    </div>

    {{-- -- Slide 1: Read & Solve -- --}}
    <div class="instr-slide-body" id="instrSlide1" style="display:none;">
      <div class="instr-hero">
        <div class="instr-hero-icon" style="color:#38bdf8;">📡</div>
        <div>
          <h3>Read the Mission Briefing</h3>
          <p>Every round starts with a space-themed math word problem.</p>
        </div>
      </div>
      <div class="instr-grid" style="grid-template-columns:1fr;">
        <div class="instr-box" style="border-color:rgba(56,189,248,.25);">
          <h5 style="color:#38bdf8;"><i class="bi bi-chat-quote-fill"></i> Example Mission</h5>
          <p style="font-size:.82rem; line-height:1.55;">
            "There are <strong>3 spaceships</strong>, each carrying <strong>2 astronauts</strong>. How many astronauts in total?"<br><br>
            ➊ Read the numbers &nbsp;→&nbsp; 3 ships, 2 astronauts each<br>
            ➋ Solve it &nbsp;→&nbsp; 3 × 2 = <strong style="color:#34d399;">6</strong><br>
            ➌ Find Ship 6 and launch it! 🚀
          </p>
        </div>
      </div>
      <div class="instr-callout">
        <i class="bi bi-lightbulb-fill" style="flex-shrink:0;margin-top:1px;"></i>
        <span><strong>Tip:</strong> Solve the math <em>first</em> before looking at the ships — don't guess from the numbers!</span>
      </div>
    </div>

    {{-- -- Slide 2: Ships & Timer -- --}}
    <div class="instr-slide-body" id="instrSlide2" style="display:none;">
      <div class="instr-hero">
        <div class="instr-hero-icon" style="color:#34d399;">🚀</div>
        <div>
          <h3>Pick a Ship &amp; Beat the Clock</h3>
          <p>Four ships appear — tap yours before time runs out!</p>
        </div>
      </div>
      <div class="instr-demo-ships">
        <div class="instr-demo-ship"><span>🚀</span><div class="num">4</div><div class="lbl">Key [1]</div></div>
        <div class="instr-demo-ship ok"><span>🛸</span><div class="num">6</div><div class="lbl" style="color:#34d399;">✅ Correct</div></div>
        <div class="instr-demo-ship"><span>🛰️</span><div class="num">8</div><div class="lbl">Key [3]</div></div>
        <div class="instr-demo-ship"><span>🔭</span><div class="num">5</div><div class="lbl">Key [4]</div></div>
      </div>
      <div class="instr-timer-demo">
        <div class="instr-timer-row"><span>🟢 Plenty of time</span><div class="instr-timer-bar" style="background:linear-gradient(90deg,#38bdf8,#06b6d4);width:85%;"></div></div>
        <div class="instr-timer-row"><span>🟡 Getting close</span><div class="instr-timer-bar" style="background:linear-gradient(90deg,#f59e0b,#d97706);width:45%;"></div></div>
        <div class="instr-timer-row"><span>🔴 Hurry! (&lt;10s)</span><div class="instr-timer-bar" style="background:linear-gradient(90deg,#ef4444,#dc2626);width:15%;"></div></div>
      </div>
      <div class="instr-grid" style="margin-top:10px;">
        <div class="instr-box" style="border-color:rgba(52,211,153,.25);">
          <h5 style="color:#34d399;"><i class="bi bi-check-circle-fill"></i> Correct Ship</h5>
          <p>Glows green &amp; launches! You earn <strong>+1 point</strong>.</p>
        </div>
        <div class="instr-box" style="border-color:rgba(248,113,113,.25);">
          <h5 style="color:#f87171;"><i class="bi bi-x-circle-fill"></i> Wrong Ship</h5>
          <p>Shakes red. You <strong>lose 1 life ❤️</strong>. Answer is revealed.</p>
        </div>
      </div>
    </div>

    {{-- -- Slide 3: Controls -- --}}
    <div class="instr-slide-body" id="instrSlide3" style="display:none;">
      <div class="instr-hero">
        <div class="instr-hero-icon" style="color:#fbbf24;">⌨️</div>
        <div>
          <h3>Controls &amp; Quick Tips</h3>
          <p>Everything you need to play like a pro.</p>
        </div>
      </div>
      <div class="instr-grid">
        <div class="instr-box" style="border-color:rgba(251,191,36,.25);">
          <h5 style="color:#fbbf24;"><i class="bi bi-keyboard-fill"></i> Keyboard &amp; Touch</h5>
          <p>Tap a ship, or press <kbd>1</kbd>–<kbd>4</kbd> to select one. Press <kbd>P</kbd> or <kbd>Esc</kbd> to pause. Press <kbd>F</kbd> for fullscreen.</p>
        </div>
        <div class="instr-box" style="border-color:rgba(139,92,246,.25);">
          <h5 style="color:#a78bfa;"><i class="bi bi-pause-circle-fill"></i> Pause</h5>
          <p>Use the <strong>Pause</strong> button in the top bar or press <kbd>P</kbd> anytime during a mission.</p>
        </div>
        <div class="instr-box" style="border-color:rgba(56,189,248,.25);">
          <h5 style="color:#38bdf8;"><i class="bi bi-map-fill"></i> Game Map</h5>
          <p>Use the <strong>Game Map</strong> button to see all levels and track your progress.</p>
        </div>
        <div class="instr-box" style="border-color:rgba(52,211,153,.25);">
          <h5 style="color:#34d399;"><i class="bi bi-star-fill"></i> Pro Tip</h5>
          <p>Solve the math <em>before</em> reading ship answers — it's faster and avoids confusion!</p>
        </div>
      </div>
    </div>

    {{-- Dots --}}
    <div class="instr-dots">
      <div class="instr-dot active" onclick="instrGoToSlide(0)"></div>
      <div class="instr-dot" onclick="instrGoToSlide(1)"></div>
      <div class="instr-dot" onclick="instrGoToSlide(2)"></div>
      <div class="instr-dot" onclick="instrGoToSlide(3)"></div>
    </div>

    {{-- Actions --}}
    <div class="instr-actions">
      <button type="button" class="instr-skip-lnk" onclick="startGame()">Skip &amp; Play ⏩</button>
      <div class="instr-btn-group">
        <button type="button" class="instr-btn-prev" id="instrPrevBtn" onclick="instrNav(-1)" disabled>← Back</button>
        <button type="button" class="instr-btn-next" id="instrNextBtn" onclick="instrNav(1)">Continue →</button>
        <button type="button" class="start-btn" id="instrStartBtn" onclick="startGame()">
          <i class="bi bi-rocket-takeoff-fill"></i> Launch Mission
        </button>
      </div>
    </div>

  </div>
</div>

{{-- GAME SCREEN --}}
<div id="gameScreen" style="display:none;width:100%;">
<div id="gameWrap">

    {{-- HUD --}}
    <div id="hud">
        <div class="hud-pill">
            <span class="hud-label">Lives</span>
            <span class="hud-val red" id="lives">&#x2764;&#xFE0F;&#x2764;&#xFE0F;&#x2764;&#xFE0F;</span>
        </div>
        <div class="hud-center">
            <div class="level-badge">
                <span>⚔️</span>
                <span id="levelBadge">LVL {{ $progress->current_level }}</span>
            </div>
            <div class="hs-label">Best: <span id="highScore">{{ $progress->high_score }}</span></div>
        </div>
        <div class="hud-right-group" style="display:flex;align-items:center;gap:14px;">
            <div class="hud-pill" style="text-align:right">
                <span class="hud-label">Score</span>
                <span class="hud-val yellow"><span id="score">0</span><span style="font-size:13px;color:rgba(255,255,255,0.2)">/10</span></span>
            </div>
            <div class="eduplay-game-nav">
                <button type="button" class="eduplay-nav-btn eduplay-btn-pause" id="topPauseBtn" title="Pause Game" onclick="togglePause()">
                    <i class="bi bi-pause-fill"></i> <span>Pause</span>
                </button>
                <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
                <a href="{{ route('student.games') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Game Arena"><i class="bi bi-box-arrow-right"></i> <span>Exit</span></a>
            </div>

        </div>
    </div>

    {{-- Progress --}}
    <div id="progressWrap">
        <div id="progressTrack"><div id="progressFill" style="width:0%"></div></div>
        <div id="progressMeta"><span id="progressLabel">0 / 10 solved</span><span id="progressPct">0%</span></div>
    </div>

    {{-- Arena --}}
    <div id="arenaWrap">
        <div id="flashOverlay"></div>

        {{-- Timer --}}
        <div id="timerWrap">
            <span id="timerLabel">Time</span>
            <span id="timerIcon">⏱️</span>
            <div id="timerTrack"><div id="timerFill"></div></div>
            <span id="timerNum">60s</span>
        </div>

        {{-- Problem --}}
        <div id="problemCard">
            <div id="problemEyebrow">Mission Briefing</div>
            <div id="problemText">Initialising mission…</div>
        </div>

        {{-- Ships --}}
        <div id="shipGrid"></div>

        {{-- Pause overlay --}}
        <div id="pauseOverlay">
            <div style="font-size:60px;margin-bottom:10px;filter:drop-shadow(0 0 20px rgba(167,139,250,.8));">⏸</div>
            <h2>Mission Paused</h2>
            <p>Press <kbd style="background:rgba(255,255,255,0.15);border-radius:5px;padding:2px 8px;">P</kbd> or tap Resume to continue</p>
            <button class="resume-btn" onclick="togglePause()">▶ Resume Mission</button>
            <div class="eduplay-game-nav" style="margin-top:14px;display:flex;gap:10px;justify-content:center;">
                <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
                <a href="{{ route('student.games') }}" class="eduplay-nav-btn eduplay-btn-exit"><i class="bi bi-box-arrow-right"></i> <span>Exit</span></a>
            </div>
        </div>
    </div>

    {{-- Controls --}}
    <div id="controls">
        <div class="hint-keys"><kbd>1-4</kbd> select &nbsp;·&nbsp; <kbd>F</kbd> fullscreen &nbsp;·&nbsp; <kbd>P</kbd> pause</div>
        <button class="ctrl-btn btn-fs" id="fsBtn" onclick="toggleFullscreen()">⛶ Fullscreen</button>
    </div>

</div>{{-- end gameWrap --}}
</div>{{-- end gameScreen --}}


</div>{{-- end gameWrapper --}}

{{-- End Modal (outside gameWrapper for proper centering) --}}
<div id="endModal">
    <div class="modal-box">
        <div class="modal-end-icon" id="endIcon"></div>
        <h2 id="endTitle"></h2>
        <p id="endMessage"></p>
        <div class="modal-btn-row" style="display:flex;align-items:center;gap:10px;">
            <button class="modal-btn modal-btn-primary" id="modalActionBtn" onclick="restartFromModal()">Play Again</button>
            <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map">
                <i class="bi bi-map-fill"></i> <span>Game Map</span>
            </a>
            <a href="{{ route('student.games') }}" class="eduplay-nav-btn eduplay-btn-exit">
                <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
            </a>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>

<script>
/* Ensure a mobile viewport exists (in case layouts.app doesn't define one) */
(function(){
    if(!document.querySelector('meta[name="viewport"]')){
        const m=document.createElement('meta');
        m.name='viewport';
        m.content='width=device-width, initial-scale=1, viewport-fit=cover';
        document.head.appendChild(m);
    }
})();

const ROCKET_PALETTES = [
    { name: 'Cyan Explorer', filter: 'drop-shadow(0 0 16px rgba(56, 189, 248, 0.6)) hue-rotate(0deg)' },
    { name: 'Cosmic Violet', filter: 'drop-shadow(0 0 16px rgba(168, 85, 247, 0.7)) hue-rotate(55deg) saturate(1.3)' },
    { name: 'Crimson Nova', filter: 'drop-shadow(0 0 16px rgba(244, 63, 94, 0.7)) hue-rotate(125deg) saturate(1.3)' },
    { name: 'Solar Gold', filter: 'drop-shadow(0 0 16px rgba(234, 179, 8, 0.7)) hue-rotate(175deg) saturate(1.4) brightness(1.1)' },
    { name: 'Emerald Plasma', filter: 'drop-shadow(0 0 16px rgba(16, 185, 129, 0.7)) hue-rotate(240deg) saturate(1.3)' },
    { name: 'Royal Indigo', filter: 'drop-shadow(0 0 16px rgba(99, 102, 241, 0.7)) hue-rotate(295deg) saturate(1.2)' }
];
let activeLotties = {};
let activeTrialLotties = {};

const NW_TUTORIAL_KEY='nw_tutorial_done';
let nwTutStep=0;
const NW_TOTAL_STEPS=5;
const NW_SLIDE_LABELS=['NUMBER WARS BRIEFING','STEP 1 OF 4','STEP 2 OF 4','STEP 3 OF 4','STEP 4 OF 4'];
const NW_TRIAL_PROBLEM={text:'There are 3 spaceships, each carrying 2 astronauts. How many astronauts in total?',answer:6};
const NW_TRIAL_CHOICES=[6,4,8,5];
const NW_SHIP_ICONS=['🚀','🛸','🛰️','🔭'];
let nwTrialAnswering=false;

function nwRenderTutSlide(idx){
    for(let i=0;i<NW_TOTAL_STEPS;i++){
        const el=document.getElementById('nwTutStep'+i);
        if(el) el.style.display=(i===idx)?'':'none';
    }
    // Dots
    const dots=document.querySelectorAll('.nw-tut-dot');
    dots.forEach((d,i)=>d.classList.toggle('active',i===idx));
    // Slide num label
    const labels=['WELCOME','READ THE MISSION','PICK A SHIP','BEAT THE TIMER','LIVES & SCORE'];
    const indicator=document.getElementById('nwTutIndicator');
    const slideNum=document.getElementById('nwTutSlideNum');
    if(indicator) indicator.textContent=labels[idx]||'NUMBER WARS BRIEFING';
    if(slideNum) slideNum.textContent='SLIDE '+(idx+1)+' OF '+NW_TOTAL_STEPS;
    // Buttons
    const backBtn=document.getElementById('nwTutBack');
    const nextBtn=document.getElementById('nwTutNext');
    const startBtn=document.getElementById('nwTutStart');
    if(backBtn) backBtn.disabled=(idx===0);
    if(nextBtn) nextBtn.style.display=(idx===NW_TOTAL_STEPS-1)?'none':'inline-block';
    if(startBtn) startBtn.style.display=(idx===NW_TOTAL_STEPS-1)?'inline-block':'none';
    // Scroll the slide body back to the top on small screens
    const body=document.querySelector('.nw-tut-body');
    if(body) body.scrollTop=0;
    nwTutStep=idx;
}

function nwGoToStep(idx){
    if(idx<0||idx>=NW_TOTAL_STEPS)return;
    nwRenderTutSlide(idx);
}

function nwTutNav(dir){
    const next=nwTutStep+dir;
    if(next<0)return;
    if(next>=NW_TOTAL_STEPS){nwShowTrialScreen();return;}
    nwGoToStep(next);
}

function nwSkipTutorial(){
    localStorage.setItem(NW_TUTORIAL_KEY,'1');
    document.getElementById('tutorialScreen').style.display='none';
    document.getElementById('instructionScreen').style.display='';
}

/* Swipe left/right on the tutorial to change slides (touch screens) */
(function(){
    const ov=document.getElementById('tutorialScreen');
    if(!ov) return;
    let sx=0, sy=0;
    ov.addEventListener('touchstart',e=>{sx=e.touches[0].clientX;sy=e.touches[0].clientY;},{passive:true});
    ov.addEventListener('touchend',e=>{
        const dx=e.changedTouches[0].clientX-sx, dy=e.changedTouches[0].clientY-sy;
        if(Math.abs(dx)>60 && Math.abs(dx)>Math.abs(dy)*1.5){
            if(dx<0 && nwTutStep<NW_TOTAL_STEPS-1) nwGoToStep(nwTutStep+1);
            else if(dx>0 && nwTutStep>0) nwGoToStep(nwTutStep-1);
        }
    },{passive:true});
})();

/* -- Instruction screen slide logic --------------------------------------- */
const INSTR_TOTAL = 4;
let instrSlide = 0;

function instrGoToSlide(idx){
    if(idx < 0 || idx >= INSTR_TOTAL) return;
    // Hide current, show next
    document.getElementById('instrSlide' + instrSlide).style.display = 'none';
    document.getElementById('instrSlide' + idx).style.display = '';
    // Dots
    document.querySelectorAll('.instr-dot').forEach((d,i) => d.classList.toggle('active', i === idx));
    // Counter
    const counter = document.getElementById('instrSlideCounter');
    if(counter) counter.textContent = 'SLIDE ' + (idx+1) + ' OF ' + INSTR_TOTAL;
    // Buttons
    document.getElementById('instrPrevBtn').disabled = (idx === 0);
    const nextBtn  = document.getElementById('instrNextBtn');
    const startBtn = document.getElementById('instrStartBtn');
    if(idx === INSTR_TOTAL - 1){
        nextBtn.style.display  = 'none';
        startBtn.style.display = 'inline-flex';
    } else {
        nextBtn.style.display  = 'inline-flex';
        startBtn.style.display = 'none';
    }
    instrSlide = idx;
}

function instrNav(dir){
    instrGoToSlide(instrSlide + dir);
}

function nwShowTrialScreen(){
    document.getElementById('tutorialScreen').style.display='none';
    const trial=document.getElementById('nwTrialScreen');
    trial.style.display='block';
    nwRenderTrialShips();
}

function nwRenderTrialShips(){
    Object.values(activeTrialLotties).forEach(inst => { try { inst.destroy(); } catch(e){} });
    activeTrialLotties = {};
    nwTrialAnswering=false;
    document.getElementById('nwTrialProbText').textContent=NW_TRIAL_PROBLEM.text;
    const grid=document.getElementById('nwTrialShipGrid');
    grid.innerHTML='';
    const choices=nwShuffle(NW_TRIAL_CHOICES.slice());
    const palettes=nwShuffle(ROCKET_PALETTES.slice());

    choices.forEach((val,i)=>{
        const shipIdx = i + 1;
        const div=document.createElement('div');
        div.className='trial-ship';
        div.onclick=()=>nwTrialSelect(div,val,choices,shipIdx);
        div.innerHTML=`<div class="ship-lottie" id="trialLottie-${shipIdx}"></div><div class="trial-ship-ans">${val}</div>`;
        grid.appendChild(div);

        const lottieBox = div.querySelector(`#trialLottie-${shipIdx}`);
        lottieBox.style.filter = palettes[i % palettes.length].filter;

        const anim = lottie.loadAnimation({
            container: lottieBox,
            renderer: 'svg',
            loop: false,
            autoplay: false,
            path: '/lottie/rocket-launch.json'
        });

        anim.addEventListener('DOMLoaded', () => {
            anim.goToAndStop(20, true);
        });

        activeTrialLotties[shipIdx] = anim;
    });
}

function nwTrialSelect(el,val,choices,shipIdx){
    if(nwTrialAnswering)return;
    nwTrialAnswering=true;
    const isCorrect=val===NW_TRIAL_PROBLEM.answer;
    document.querySelectorAll('.trial-ship').forEach(s=>{if(s!==el)s.classList.add('dim');});
    el.classList.add(isCorrect?'correct':'wrong');
    if (isCorrect && activeTrialLotties[shipIdx]) {
        activeTrialLotties[shipIdx].goToAndPlay(0, true);
        activeTrialLotties[shipIdx].addEventListener('complete', () => {
            const lottieEl = document.getElementById(`trialLottie-${shipIdx}`);
            if (lottieEl) lottieEl.style.opacity = '0';
            nwShowReadyModal();
        });
    } else {
        setTimeout(()=>{
            if(isCorrect){nwShowReadyModal();}
            else{nwRenderTrialShips();}
        },isCorrect?1200:900);
    }
}

function nwShowReadyModal(){
    const overlay=document.createElement('div');
    overlay.id='nwReadyOverlay';overlay.className='nwReadyOverlay';
    overlay.style.cssText='display:flex;position:fixed;inset:0;background:rgba(0,0,0,.82);backdrop-filter:blur(8px);z-index:500;align-items:center;justify-content:center;padding:16px;overflow-y:auto;animation:tutFadeUp .3s ease;';
    overlay.innerHTML=`
        <div class="nw-ready-box">
            <div class="nw-ready-ico">🎉</div>
            <div class="nw-ready-stars">⭐⭐⭐</div>
            <h2 class="nw-ready-title">Trial Complete!</h2>
            <p class="nw-ready-sub">You launched the correct ship!<br>Ready for the real mission?</p>
            <button class="nw-ready-start" onclick="nwStartRealGame()">🚀 Launch Real Mission!</button>
            <button class="nw-ready-practice" onclick="nwPracticeAgain(this.closest('.nwReadyOverlay'))">🔄 Practice Again</button>
        </div>`;
    document.body.appendChild(overlay);
}

function nwStartRealGame(){
    localStorage.setItem(NW_TUTORIAL_KEY,'1');
    document.querySelectorAll('.nwReadyOverlay').forEach(e=>e.remove());
    document.getElementById('nwTrialScreen').style.display='none';
    startGame();
}

function nwPracticeAgain(overlay){
    overlay.remove();
    nwRenderTrialShips();
}

function nwSkipToRealGame(){
    localStorage.setItem(NW_TUTORIAL_KEY,'1');
    document.getElementById('nwTrialScreen').style.display='none';
    startGame();
}

function nwShuffle(a){for(let i=a.length-1;i>0;i--){const j=Math.floor(Math.random()*(i+1));[a[i],a[j]]=[a[j],a[i]];}return a;}

// -- Entry point --------------------------------------------------------------
(function nwInit(){
    if(!localStorage.getItem(NW_TUTORIAL_KEY)){
        document.getElementById('tutorialScreen').style.display='';
        document.getElementById('instructionScreen').style.display='none';
    }else{
        document.getElementById('tutorialScreen').style.display='none';
        document.getElementById('instructionScreen').style.display='';
    }
})();

let lives = 3, correctCount = 0, gameRunning = false, isPaused = false;
let currentLevel = {{ $progress->current_level }};
const gameId       = {{ $game->id }};
const gradeLevel   = {{ auth()->user()->grade_level ?? 4 }};
const WIN_POINTS   = 10;
const MAX_LEVEL    = {{ $game->total_levels }};
const HEARTS       = ['&#x2764;&#xFE0F;&#x2764;&#xFE0F;&#x2764;&#xFE0F;','&#x2764;&#xFE0F;&#x2764;&#xFE0F;&#x1F5A4;','&#x2764;&#xFE0F;&#x1F5A4;&#x1F5A4;','&#x1F5A4;&#x1F5A4;&#x1F5A4;'];
const QUESTION_TIME = 60; // seconds per problem

let timeLeft = QUESTION_TIME;
let timerInterval = null;
let currentProblem = null;
let answering = false;

// -- Helpers -------------------------------------------------------------------
function rand(min, max) { return Math.floor(Math.random() * (max - min + 1)) + min; }
function pick(arr) { return arr[rand(0, arr.length - 1)]; }
function shuffle(arr) { const a = arr.slice(); for (let i = a.length - 1; i > 0; i--) { const j = rand(0, i); [a[i], a[j]] = [a[j], a[i]]; } return a; }

const NAMES = ['Maya','Liam','Ava','Noah','Zoe','Ethan','Luna','Kai','Sofia','Diego','Mia','Owen'];

// -- Word problem generation --------------------------------------------------
function generateWordProblem() {
    const g = gradeLevel, r = Math.random(), lvlBoost = Math.floor(currentLevel / 3);
    let op;
    if      (g <= 4) op = r < 0.60 ? '+' : r < 0.90 ? '-' : '×';
    else if (g === 5) op = r < 0.25 ? '+' : r < 0.50 ? '-' : r < 0.75 ? '×' : '÷';
    else              op = r < 0.20 ? '+' : r < 0.40 ? '-' : r < 0.70 ? '×' : '÷';

    const name1 = pick(NAMES);
    let name2 = pick(NAMES); while (name2 === name1) name2 = pick(NAMES);

    let num1, num2, answer, text;

    if (op === '+') {
        if (g <= 4) { num1 = rand(10, 50 + lvlBoost * 5); num2 = rand(1, 20 + lvlBoost * 3); }
        else if (g === 5) { num1 = rand(20, 80 + lvlBoost * 5); num2 = rand(10, 40 + lvlBoost * 5); }
        else { num1 = rand(50, 150 + lvlBoost * 10); num2 = rand(20, 80 + lvlBoost * 10); }
        answer = num1 + num2;
        text = pick([
            `${name1} has ${num1} stickers. ${name2} gives ${name1} ${num2} more. How many stickers does ${name1} have now?`,
            `A spaceship carries ${num1} crew members. It picks up ${num2} more at a space station. How many crew members are on board now?`,
            `${name1} collected ${num1} space rocks on Monday and ${num2} more on Tuesday. How many space rocks in total?`,
            `There are ${num1} stars in one galaxy and ${num2} stars in another. How many stars are there altogether?`
        ]);
    } else if (op === '-') {
        if (g <= 4) { num1 = rand(10, 50 + lvlBoost * 5); num2 = rand(1, num1); }
        else if (g === 5) { num1 = rand(30, 90 + lvlBoost * 5); num2 = rand(10, num1); }
        else { num1 = rand(60, 160 + lvlBoost * 10); num2 = rand(20, num1); }
        answer = num1 - num2;
        text = pick([
            `${name1} had ${num1} fuel cells. The ship used ${num2} of them on the journey. How many fuel cells are left?`,
            `There were ${num1} asteroids in the field. ${num2} of them were destroyed. How many asteroids remain?`,
            `${name1} packed ${num1} snacks for the mission and ate ${num2} of them. How many snacks are left?`,
            `A fleet had ${num1} spaceships. ${num2} of them returned to base. How many are still out on patrol?`
        ]);
    } else if (op === '×') {
        if (g <= 4) { num1 = rand(2, 9); num2 = rand(2, 12 + lvlBoost); }
        else if (g === 5) { num1 = rand(2, 12); num2 = rand(2, 15 + lvlBoost); }
        else { num1 = rand(3, 15 + lvlBoost); num2 = rand(3, 15 + lvlBoost); }
        answer = num1 * num2;
        text = pick([
            `There are ${num1} spaceships, each carrying ${num2} astronauts. How many astronauts are there in total?`,
            `${name1} arranges star badges into ${num1} rows with ${num2} badges in each row. How many badges are there altogether?`,
            `A cargo ship has ${num1} containers, and each container holds ${num2} supply crates. How many supply crates in total?`,
            `${name1} visits ${num1} planets and collects ${num2} samples on each. How many samples in total?`
        ]);
    } else {
        if (g <= 4) { num2 = rand(2, 5); answer = rand(2, 9); num1 = num2 * answer; }
        else if (g === 5) { num2 = rand(2, 9); answer = rand(2, 10 + lvlBoost); num1 = num2 * answer; }
        else { num2 = rand(2, 12); answer = rand(3, 15 + lvlBoost); num1 = num2 * answer; }
        text = pick([
            `${num1} space snacks are shared equally among ${num2} astronauts. How many snacks does each astronaut get?`,
            `${name1} has ${num1} crystals to split evenly into ${num2} storage boxes. How many crystals go in each box?`,
            `A crew of ${num2} needs to split ${num1} oxygen tanks evenly. How many tanks does each crew member get?`,
            `${num1} planets are grouped evenly into ${num2} star systems. How many planets are in each star system?`
        ]);
    }

    return { text, answer };
}

function generateChoices(answer) {
    const offsets = shuffle([-1, 1, -2, 2, -3, 3, -5, 5, -10, 10].filter(o => answer + o >= 0));
    const wrongSet = new Set();
    for (const o of offsets) {
        if (wrongSet.size >= 3) break;
        const v = answer + o;
        if (v !== answer && v >= 0) wrongSet.add(v);
    }
    while (wrongSet.size < 3) {
        const v = Math.max(0, answer + rand(-15, 15));
        if (v !== answer) wrongSet.add(v);
    }
    const choices = shuffle([answer, ...Array.from(wrongSet).slice(0, 3)]);
    return choices;
}

// -- Init ----------------------------------------------------------------------
function startGame() {
    document.getElementById('instructionScreen').style.display = 'none';
    document.getElementById('gameScreen').style.display = 'flex';
    initGame();
}

function initGame() {
    lives = 3; correctCount = 0; gameRunning = true; isPaused = false; answering = false;
    const overlay = document.getElementById('pauseOverlay');
    if (overlay) overlay.classList.remove('active');
    updateHUD();
    nextProblem();
}

// -- HUD -----------------------------------------------------------------------
function updateHUD() {
    document.getElementById('lives').innerHTML   = HEARTS[Math.max(0, 3 - lives)];
    document.getElementById('score').textContent   = correctCount;
    document.getElementById('levelBadge').textContent = `LVL ${currentLevel}`;
    const pct = Math.round((correctCount / WIN_POINTS) * 100);
    document.getElementById('progressFill').style.width  = pct + '%';
    document.getElementById('progressLabel').textContent = `${correctCount} / ${WIN_POINTS} solved`;
    document.getElementById('progressPct').textContent   = pct + '%';
}

// -- Problem flow --------------------------------------------------------------
function nextProblem() {
    if (!gameRunning) return;
    answering = false;
    currentProblem = generateWordProblem();
    const choices = generateChoices(currentProblem.answer);
    renderProblem(choices);
    startTimer();
    // On phones the page scrolls — bring the new problem back into view
    const gw = document.getElementById('gameWrapper');
    if (gw && gw.scrollTop > 0) {
        try { gw.scrollTo({ top: 0, behavior: 'smooth' }); } catch (e) { gw.scrollTop = 0; }
    }
}

function renderProblem(choices) {
    Object.values(activeLotties).forEach(inst => { try { inst.destroy(); } catch(e){} });
    activeLotties = {};

    const card = document.getElementById('problemCard');
    document.getElementById('problemText').textContent = currentProblem.text;
    card.classList.remove('flashIn');
    void card.offsetWidth;
    card.classList.add('flashIn');

    const grid = document.getElementById('shipGrid');
    grid.innerHTML = '';
    const palettes = shuffle(ROCKET_PALETTES.slice());

    choices.forEach((val, i) => {
        const shipNum = i + 1;
        const div = document.createElement('div');
        div.className = 'ship';
        div.id = `ship-${shipNum}`;
        div.onclick = () => selectShip(shipNum, val);
        div.innerHTML = `
            <div class="ship-num">${shipNum}</div>
            <div class="ship-lottie" id="shipLottie-${shipNum}"></div>
            <div class="ship-answer">${val}</div>
            <div class="ship-key">PRESS ${shipNum}</div>
        `;
        div.dataset.value = val;
        grid.appendChild(div);

        const lottieBox = div.querySelector(`#shipLottie-${shipNum}`);
        lottieBox.style.filter = palettes[i % palettes.length].filter;

        const anim = lottie.loadAnimation({
            container: lottieBox,
            renderer: 'svg',
            loop: false,
            autoplay: false,
            path: '/lottie/rocket-launch.json'
        });

        anim.addEventListener('DOMLoaded', () => {
            anim.goToAndStop(20, true);
        });

        activeLotties[shipNum] = anim;
    });
}

function startTimer() {
    clearInterval(timerInterval);
    timeLeft = QUESTION_TIME;
    updateTimerUI();
    timerInterval = setInterval(() => {
        if (isPaused) return;
        timeLeft--;
        updateTimerUI();
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            handleTimeout();
        }
    }, 1000);
}

function updateTimerUI() {
    const fill = document.getElementById('timerFill');
    const num  = document.getElementById('timerNum');
    const pct  = Math.max(0, (timeLeft / QUESTION_TIME) * 100);
    fill.style.width = pct + '%';
    num.textContent = timeLeft + 's';
    if (timeLeft <= 10) fill.style.background = 'linear-gradient(90deg,#f87171,#ef4444)';
    else if (timeLeft <= 25) fill.style.background = 'linear-gradient(90deg,#fbbf24,#f97316)';
    else fill.style.background = 'linear-gradient(90deg,#2dd4bf,#38bdf8)';
}

// -- Answering -----------------------------------------------------------------
function selectShip(shipNum, value) {
    if (!gameRunning || isPaused || answering) return;
    answering = true;
    clearInterval(timerInterval);

    const chosenEl = document.getElementById(`ship-${shipNum}`);
    const isCorrect = value === currentProblem.answer;

    document.querySelectorAll('.ship').forEach(el => { if (el !== chosenEl) el.classList.add('dim'); });

    if (isCorrect) {
        chosenEl.classList.add('correct');

        correctCount++;
        updateHUD();
        flashOverlay('#10b981');

        if (activeLotties[shipNum]) {
            activeLotties[shipNum].goToAndPlay(0, true);
            activeLotties[shipNum].addEventListener('complete', () => {
                const lottieEl = document.getElementById(`shipLottie-${shipNum}`);
                if (lottieEl) lottieEl.style.opacity = '0';
                
                if (correctCount >= WIN_POINTS) {
                    endGame(true);
                } else if (gameRunning) {
                    nextProblem();
                }
            });
        } else {
            if (correctCount >= WIN_POINTS) { setTimeout(() => endGame(true), 1200); return; }
            setTimeout(() => { if (gameRunning) nextProblem(); }, 1400);
        }
    } else {
        chosenEl.classList.add('wrong');
        loseLife();
    }
}

function handleTimeout() {
    if (!gameRunning || isPaused || answering) return;
    answering = true;
    document.querySelectorAll('.ship').forEach(el => el.classList.add('dim'));
    flashOverlay('#ef4444');
    loseLife();
}

function loseLife() {
    lives--;
    updateHUD();
    if (lives <= 0) { setTimeout(() => endGame(false), 500); return; }
    setTimeout(() => { if (gameRunning) nextProblem(); }, 700);
}

function flashOverlay(color) {
    const el = document.getElementById('flashOverlay');
    el.style.background = color; el.style.opacity = '0.16';
    setTimeout(() => { el.style.opacity = '0'; }, 180);
}

// -- Pause ---------------------------------------------------------------------
function togglePause() {
    if (!gameRunning) return;
    isPaused = !isPaused;
    const overlay = document.getElementById('pauseOverlay');
    const topPauseBtn = document.getElementById('topPauseBtn');
    if (isPaused) {
        if (overlay) overlay.classList.add('active');
        if (topPauseBtn) {
            topPauseBtn.classList.add('paused');
            topPauseBtn.innerHTML = '<i class="bi bi-play-fill"></i> <span>Resume</span>';
            topPauseBtn.title = 'Resume Game';
        }
    } else {
        if (overlay) overlay.classList.remove('active');
        if (topPauseBtn) {
            topPauseBtn.classList.remove('paused');
            topPauseBtn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
            topPauseBtn.title = 'Pause Game';
        }
    }
}

// Auto-pause when the tab / app goes to the background (mobile friendly)
document.addEventListener('visibilitychange', () => {
    if (document.hidden && gameRunning && !isPaused && !answering) togglePause();
});


// -- End -----------------------------------------------------------------------
function endGame(won) {
    gameRunning = false;
    clearInterval(timerInterval);

    // Advance level FIRST so saveProgress sends the next level to the server
    if (won && currentLevel < MAX_LEVEL) currentLevel++;

    // Save progress immediately (sends incremented level on win, or current level on loss)
    saveProgress(currentLevel, won);

    updateHUD();

    const btn = document.getElementById('modalActionBtn');
    if (won) {
        document.getElementById('endIcon').textContent    = currentLevel > MAX_LEVEL ? '🎉' : '🎊';
        document.getElementById('endTitle').textContent   = currentLevel > MAX_LEVEL ? 'All Levels Complete!' : `Level ${currentLevel - 1} Complete!`;
        document.getElementById('endMessage').textContent = currentLevel > MAX_LEVEL
            ? `Incredible! You finished all ${MAX_LEVEL} levels!`
            : `Outstanding! All missions solved! Advancing to Level ${currentLevel}!`;
        btn.textContent = currentLevel > MAX_LEVEL ? 'Play Again' : `▶️ Start Level ${currentLevel}`;
    } else {
        document.getElementById('endIcon').textContent    = '💀';
        document.getElementById('endTitle').textContent   = 'Game Over';
        document.getElementById('endMessage').textContent = `You solved ${correctCount} / ${WIN_POINTS}. Give it another shot!`;
        btn.textContent = '🔄 Try Again';
    }

    document.getElementById('endModal').classList.add('show');
}

function resetGame() {
    document.getElementById('endModal').classList.remove('show');
    initGame();
}
function restartFromModal() { resetGame(); }

function saveProgress(levelToSave, won) {
    const scoreValue = correctCount * 10;
    $.post('/student/update-progress', {
        game_id: gameId,
        level: levelToSave,
        score: scoreValue,
        _token: '{{ csrf_token() }}'
    }).done(() => {
        const hs = document.getElementById('highScore');
        hs.textContent = Math.max(scoreValue, parseInt(hs.textContent) || 0);
    });
}

// -- Fullscreen ----------------------------------------------------------------
function toggleFullscreen() {
    if (!document.fullscreenElement) {
        const de = document.documentElement;
        const req = de.requestFullscreen || de.webkitRequestFullscreen;
        if (req) { const p = req.call(de); if (p && p.catch) p.catch(() => {}); }
    } else {
        document.exitFullscreen();
    }
}

// iPhone Safari has no Fullscreen API for pages — hide the button (and the bar if it would be empty)
(function(){
    const de = document.documentElement;
    const supported = !!(de.requestFullscreen || de.webkitRequestFullscreen);
    if (!supported) {
        const fsBtn = document.getElementById('fsBtn');
        if (fsBtn) fsBtn.style.display = 'none';
        if (window.matchMedia && window.matchMedia('(hover: none)').matches) {
            const bar = document.getElementById('controls');
            if (bar) bar.style.display = 'none';
        }
    }
})();

document.addEventListener('fullscreenchange', () => {
    const wrapper = document.getElementById('gameWrapper');
    if (document.fullscreenElement) {
        wrapper.style.position  = 'fixed';
        wrapper.style.top       = '0';
        wrapper.style.left      = '0';
        wrapper.style.width     = '100vw';
        wrapper.style.height    = '100vh';
        wrapper.style.zIndex    = '9999';
        wrapper.style.overflowY = 'auto';
    } else {
        wrapper.style.position  = '';
        wrapper.style.top       = '';
        wrapper.style.left      = '';
        wrapper.style.width     = '';
        wrapper.style.height    = '';
        wrapper.style.zIndex    = '';
        wrapper.style.overflowY = '';
    }
});

// -- Keys ----------------------------------------------------------------------
document.addEventListener('keydown', e => {
    if (!gameRunning) return;
    if (['1','2','3','4'].includes(e.key) && !isPaused && !answering) {
        const shipNum = parseInt(e.key);
        const el = document.getElementById(`ship-${shipNum}`);
        if (el) selectShip(shipNum, parseInt(el.dataset.value));
    }
    if (e.key === 'f' || e.key === 'F') toggleFullscreen();
    if (e.key === 'p' || e.key === 'P' || e.key === 'Escape') togglePause();
});

// -- Tutorial Reopen -----------------------------------------------------------
function nwReopenTutorial() {
    // Reset to step 0
    nwRenderTutSlide(0);
    // Pause the game if running
    if (gameRunning && !isPaused) togglePause();
    // Show tutorial
    document.querySelectorAll('.nwReadyOverlay').forEach(e => e.remove());
    document.getElementById('nwTrialScreen').style.display = 'none';
    document.getElementById('tutorialScreen').style.display = '';
}
</script>
@endsection