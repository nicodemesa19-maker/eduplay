@extends('layouts.app')
@section('hide-nav', true)

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700;800;900&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{width:100%;height:100%;overflow:hidden;background:#04020a;font-family:'Inter',sans-serif;color:#e8eef5;-webkit-text-size-adjust:100%;}
button,a{touch-action:manipulation;-webkit-tap-highlight-color:transparent;}

/* #gw is the scroll container so tall layouts (phones) can scroll; children use margin:auto to stay centered */
#gw{position:fixed;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;
    overflow-x:hidden;overflow-y:auto;-webkit-overflow-scrolling:touch;overscroll-behavior:contain;
    padding:max(10px,env(safe-area-inset-top)) max(10px,env(safe-area-inset-right)) max(10px,env(safe-area-inset-bottom)) max(10px,env(safe-area-inset-left));
    background:radial-gradient(ellipse 900px 500px at 18% 5%,rgba(124,58,237,.25),transparent 60%),
               radial-gradient(ellipse 700px 500px at 82% 92%,rgba(34,211,238,.14),transparent 60%),
               radial-gradient(ellipse 600px 400px at 52% 50%,rgba(244,114,182,.08),transparent 65%),#04020a;z-index:10;}
#gw::before,#gw::after{content:'';position:fixed;inset:0;pointer-events:none;z-index:0;}
#gw::before{background-image:radial-gradient(1.5px 1.5px at 6% 11%,rgba(255,255,255,.85),transparent),radial-gradient(1px 1px at 16% 67%,rgba(255,255,255,.6),transparent),radial-gradient(1.5px 1.5px at 24% 33%,rgba(200,220,255,.9),transparent),radial-gradient(1px 1px at 48% 17%,rgba(255,255,255,.75),transparent),radial-gradient(1.5px 1.5px at 66% 89%,rgba(255,255,255,.8),transparent),radial-gradient(1px 1px at 83% 61%,rgba(255,255,255,.55),transparent);animation:tw1 4.5s ease-in-out infinite alternate;}
#gw::after{background-image:radial-gradient(1px 1px at 3% 46%,rgba(255,255,255,.5),transparent),radial-gradient(1.5px 1.5px at 43% 37%,rgba(255,255,255,.8),transparent),radial-gradient(1px 1px at 86% 57%,rgba(220,255,220,.6),transparent);animation:tw2 6s ease-in-out infinite alternate;}
@keyframes tw1{0%{opacity:.35}50%{opacity:1}100%{opacity:.55}}
@keyframes tw2{0%{opacity:.65}60%{opacity:.3}100%{opacity:.9}}
.neb{position:fixed;border-radius:50%;pointer-events:none;filter:blur(80px);z-index:0;}
.nb1{width:500px;height:380px;background:radial-gradient(#7c3aed,transparent);top:-120px;left:-150px;opacity:.22;animation:nd 20s ease-in-out infinite alternate;}
.nb2{width:420px;height:320px;background:radial-gradient(#1d4ed8,transparent);bottom:5%;right:-100px;opacity:.18;animation:nd 25s ease-in-out infinite alternate-reverse;}
.nb3{width:300px;height:240px;background:radial-gradient(#be185d,transparent);bottom:28%;left:4%;opacity:.14;animation:nd 17s ease-in-out infinite alternate;}
@keyframes nd{from{transform:translate(0,0) scale(1)}to{transform:translate(28px,18px) scale(1.1)}}
#gw>*:not(.neb){position:relative;z-index:1;}
#gw>.neb{position:fixed;z-index:0;}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes flt{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}


/* ============================================================
   FLASH CARD SYSTEM — ALWAYS ON TOP
   ============================================================ */
.fc-overlay {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    height: 100dvh !important;
    background: rgba(4, 2, 12, 0.94) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    z-index: 999999 !important;
    display: flex !important;
    align-items: flex-start !important;
    justify-content: center !important;
    padding: 16px !important;
    padding-top: max(16px, env(safe-area-inset-top)) !important;
    padding-bottom: max(16px, env(safe-area-inset-bottom)) !important;
    animation: fadeUp 0.25s cubic-bezier(.34,1.56,.64,1);
    overflow-x: hidden !important;
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch;
}
.fc-overlay.hidden {
    display: none !important;
}

.fc-deck {
    position: relative;
    width: 100%;
    max-width: 780px;
    margin: auto;
}

.fc-deck-stack-1 {
    position: absolute;
    inset: 10px 18px -10px;
    background: rgba(124, 58, 237, 0.22);
    border: 1.5px solid rgba(167, 139, 250, 0.25);
    border-radius: 26px;
    transform: rotate(-2.8deg);
    z-index: 1;
    pointer-events: none;
    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.6);
}
.fc-deck-stack-2 {
    position: absolute;
    inset: 5px 8px -5px;
    background: rgba(15, 6, 40, 0.88);
    border: 1.5px solid rgba(167, 139, 250, 0.35);
    border-radius: 26px;
    transform: rotate(2deg);
    z-index: 2;
    pointer-events: none;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.7);
}

.fc-card {
    position: relative;
    z-index: 3;
    background: linear-gradient(165deg, rgba(20, 8, 50, 0.98) 0%, rgba(8, 4, 24, 0.99) 100%);
    border: 2px solid rgba(167, 139, 250, 0.55);
    border-radius: 26px;
    padding: 30px 34px 26px;
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.85), 0 0 40px rgba(124, 58, 237, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    overflow: hidden;
    text-align: center;
}
.fc-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3.5px;
    background: linear-gradient(90deg, #c4b5fd 0%, #818cf8 35%, #38bdf8 70%, #ec4899 100%);
}

.fc-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 12px;
}
.fc-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(124, 58, 237, 0.22);
    border: 1px solid rgba(167, 139, 250, 0.45);
    border-radius: 99px;
    padding: 4px 12px;
    font-size: 0.9rem;
    font-weight: 800;
    color: #c4b5fd;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    text-align: left;
}
.fc-quick-action-btn {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.16);
    color: rgba(220, 210, 255, 0.9);
    border-radius: 99px;
    padding: 4px 12px;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 0.9rem;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s ease;
    flex-shrink: 0;
    white-space: nowrap;
}
.fc-quick-action-btn:hover {
    background: rgba(124, 58, 237, 0.35);
    border-color: rgba(167, 139, 250, 0.6);
    color: #fff;
}
.fc-quick-action-btn.is-close {
    width: 36px;
    height: 36px;
    padding: 0;
    justify-content: center;
    font-size: 1.4rem;
    border-radius: 10px;
}
.fc-quick-action-btn.is-close:hover {
    background: rgba(239, 68, 68, 0.25);
    border-color: rgba(239, 68, 68, 0.5);
    color: #fff;
}

.fc-slide {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 245px;
    animation: fcSlideIn 0.25s cubic-bezier(.34,1.56,.64,1);
}
.fc-slide.hidden {
    display: none !important;
}
@keyframes fcSlideIn {
    from { opacity: 0; transform: scale(0.96) translateY(6px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}

.fc-title {
    font-family: 'Space Grotesk', sans-serif !important;
    font-size: 1.42rem !important;
    font-weight: 900 !important;
    margin: 0 0 6px !important;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 8px;
    justify-content: center;
    color: #c4b5fd;
}
.fc-desc {
    font-size: 1.05rem;
    color: rgba(220, 210, 255, 0.85);
    line-height: 1.4;
    margin: 0 auto 12px;
    max-width: 440px;
}

.fc-box {
    background: rgba(0, 0, 0, 0.35);
    border: 1.5px solid rgba(167, 139, 250, 0.25);
    border-radius: 16px;
    padding: 12px 14px;
    margin-bottom: 12px;
    width: 100%;
}
.fc-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    width: 100%;
}
.fc-item {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.09);
    border-radius: 12px;
    padding: 10px 10px;
    text-align: center;
    font-family: inherit;
    width: 100%;
}
.fc-item-ico { font-size: 1.5rem; margin-bottom: 2px; }
.fc-item-lbl { font-size: 0.88rem; font-weight: 800; color: #a78bfa; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 2px; }
.fc-item-math { font-family: 'Space Grotesk', sans-serif; font-size: 1.05rem; font-weight: 900; color: #fff; }
.fc-item-sub { font-size: 0.88rem; color: rgba(220, 210, 255, 0.75); margin-top: 3px; line-height: 1.3; }

.fc-tip {
    background: rgba(124, 58, 237, 0.15);
    border: 1px solid rgba(167, 139, 250, 0.35);
    border-radius: 10px;
    padding: 6px 12px;
    font-size: 0.95rem;
    color: #e9d5ff;
    width: 100%;
    text-align: center;
    line-height: 1.35;
}
.fc-tip b { color: #c4b5fd; }

.fc-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding-top: 14px;
    margin-top: 6px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
}
.fc-nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.16);
    color: #fff;
    padding: 8px 16px;
    border-radius: 11px;
    font-family: 'Space Grotesk', sans-serif;
    font-size: 1.0rem;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.fc-nav-btn:hover:not(:disabled) {
    background: rgba(255, 255, 255, 0.14);
    transform: translateY(-1px);
}
.fc-nav-btn:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}
.fc-nav-btn.primary {
    background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
    border: none;
    box-shadow: 0 4px 14px rgba(124, 58, 237, 0.45);
}
.fc-nav-btn.primary:hover {
    box-shadow: 0 6px 20px rgba(124, 58, 237, 0.65);
    transform: translateY(-2px);
}

.fc-dots {
    display: flex;
    align-items: center;
    gap: 6px;
}
.fc-dot {
    width: 8px;
    height: 8px;
    border-radius: 99px;
    background: rgba(255, 255, 255, 0.22);
    border: none;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(.34,1.56,.64,1);
    padding: 0;
}
.fc-dot.active {
    width: 22px;
    background: #a78bfa;
    box-shadow: 0 0 10px rgba(167, 139, 250, 0.7);
}

/* ─────────────────────────────────────────────
   TRIAL SCREEN
───────────────────────────────────────────── */
#trialScreen{
    display:none;
    width:100%;max-width:760px;margin:auto;
    background:rgba(10,4,30,.9);
    border:1px solid rgba(34,211,238,.35);
    border-radius:28px;padding:24px 22px 20px;
    text-align:center;
    backdrop-filter:blur(28px);
    box-shadow:0 0 70px rgba(8,145,178,.3),0 0 0 1px rgba(34,211,238,.1);
    animation:fadeUp .5s cubic-bezier(.34,1.56,.64,1);
}
.trial-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(34,211,238,.15);border:1.5px solid rgba(34,211,238,.4);border-radius:99px;padding:5px 16px;font-size:15px;font-weight:800;color:#67e8f9;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:10px;}
.trial-title{font-family:'Space Grotesk',sans-serif;font-size:1.35rem;font-weight:900;color:#fff;margin-bottom:4px;}
.trial-sub{font-size:16px;color:rgba(200,230,255,.5);margin-bottom:16px;line-height:1.5;}

/* Trial HUD */
.trial-hud{display:flex;align-items:center;justify-content:center;gap:24px;background:rgba(15,10,40,.7);border:1px solid rgba(34,211,238,.25);border-radius:12px;padding:10px 20px;margin-bottom:14px;}
.trial-hud-item{display:flex;flex-direction:column;align-items:center;gap:1px;}
.trial-hud-lbl{font-size:8px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:rgba(103,232,249,.5);}
.trial-hud-val{font-size:20px;font-weight:900;font-family:'Space Grotesk',sans-serif;color:#fff;}
.trial-hud-val.teal{color:#2dd4bf;}
.trial-hud-val.gold{color:#fbbf24;}

/* Trial board */
#trialBoard{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;max-width:360px;margin:0 auto 14px;}
@media(max-width:480px){#trialBoard{grid-template-columns:repeat(2,1fr);max-width:320px;}}

/* Hint pulse tooltip */
.hint-pulse{position:relative;}
.hint-pulse::after{
    content:'👆 Flip me!';
    position:absolute;bottom:calc(100% + 8px);left:50%;transform:translateX(-50%);
    background:linear-gradient(135deg,#7c3aed,#4f46e5);
    color:#fff;font-size:11px;font-weight:800;white-space:nowrap;
    padding:5px 12px;border-radius:8px;
    box-shadow:0 4px 16px rgba(124,58,237,.5);
    animation:hintBounce 1s ease-in-out infinite;
    pointer-events:none;
    font-family:'Inter',sans-serif;
}
.hint-pulse::before{
    content:'';position:absolute;bottom:calc(100% + 2px);left:50%;transform:translateX(-50%);
    border:6px solid transparent;border-top-color:#4f46e5;
    pointer-events:none;
}
@media (hover:none){ .hint-pulse::after{content:'👆 Tap me!';} }
@keyframes hintBounce{0%,100%{transform:translateX(-50%) translateY(0)}50%{transform:translateX(-50%) translateY(-5px)}}

/* Trial progress bar */
#trialTrack{width:100%;height:7px;background:rgba(255,255,255,.09);border-radius:99px;overflow:hidden;margin-bottom:6px;}
#trialFill{height:100%;width:0%;background:linear-gradient(90deg,#0891b2,#22d3ee);border-radius:99px;transition:width .5s cubic-bezier(.34,1.56,.64,1);}
.trial-meta{display:flex;justify-content:space-between;font-size:10px;color:rgba(255,255,255,.35);font-weight:600;margin-bottom:12px;}

.trial-skip-wrap{text-align:center;}
.trial-skip-link{font-size:11px;color:rgba(167,139,250,.4);cursor:pointer;background:none;border:none;font-family:'Inter',sans-serif;text-decoration:underline;text-underline-offset:3px;transition:color .15s;padding:10px 12px;}
.trial-skip-link:hover{color:rgba(167,139,250,.75);}

/* ─────────────────────────────────────────────
   READY MODAL (overlay on trial screen)
───────────────────────────────────────────── */
#readyOverlay{
    display:none;
    position:fixed;inset:0;
    background:rgba(4,2,10,.75);
    backdrop-filter:blur(6px);
    z-index:500;
    align-items:center;justify-content:center;
    padding:16px;overflow-y:auto;
}
#readyOverlay.show{display:flex;animation:fadeIn .3s ease;}
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes popIn{from{transform:scale(.8);opacity:0}to{transform:scale(1);opacity:1}}
.ready-box{
    background:linear-gradient(135deg,rgba(15,8,40,.97),rgba(12,24,52,.97));
    border:1.5px solid rgba(139,92,246,.5);
    border-radius:24px;padding:36px 32px;
    text-align:center;max-width:420px;width:100%;margin:auto;
    box-shadow:0 0 80px rgba(88,28,135,.5),0 0 0 1px rgba(139,92,246,.15);
    animation:popIn .45s cubic-bezier(.34,1.56,.64,1);
}
.ready-ico{font-size:64px;line-height:1;margin-bottom:12px;animation:flt 3s ease-in-out infinite;}
.ready-title{font-family:'Space Grotesk',sans-serif;font-size:1.6rem;font-weight:900;color:#fff;margin-bottom:6px;text-shadow:0 0 20px rgba(167,139,250,.5);}
.ready-sub{font-size:13.5px;color:rgba(200,210,255,.65);line-height:1.6;margin-bottom:10px;}
.ready-score-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(251,191,36,.12);border:1.5px solid rgba(251,191,36,.35);border-radius:99px;padding:6px 18px;margin-bottom:22px;font-size:13px;font-weight:800;color:#fde68a;}
.ready-buttons{display:flex;flex-direction:column;gap:10px;}
.ready-btn-start{background:linear-gradient(135deg,#7c3aed,#4f46e5);color:#fff;font-size:15.5px;font-weight:900;padding:14px 32px;border-radius:14px;border:none;cursor:pointer;box-shadow:0 8px 30px rgba(124,58,237,.55);transition:transform .18s cubic-bezier(.34,1.56,.64,1),box-shadow .18s;font-family:'Space Grotesk',sans-serif;letter-spacing:.4px;min-height:48px;}
.ready-btn-start:hover{transform:translateY(-3px);box-shadow:0 16px 40px rgba(124,58,237,.7);}
.ready-btn-start:active{transform:scale(.97);}
.ready-btn-practice{background:rgba(34,211,238,.1);color:#67e8f9;border:1.5px solid rgba(34,211,238,.35);padding:11px 24px;border-radius:12px;font-size:13px;font-weight:700;cursor:pointer;transition:all .15s;font-family:'Inter',sans-serif;min-height:44px;}
.ready-btn-practice:hover{background:rgba(34,211,238,.18);color:#a5f3fc;}
.ready-stars{font-size:28px;letter-spacing:6px;margin-bottom:8px;}

/* ─────────────────────────────────────────────
   GAME SCREEN
───────────────────────────────────────────── */
#gameScreen{display:none;width:100%;min-height:100%;margin:auto;flex-direction:column;align-items:center;justify-content:center;padding:8px 14px;}
.gi{width:100%;max-width:840px;display:flex;flex-direction:column;gap:7px;}
#hud{display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;background:rgba(10,4,30,.8);border:1.5px solid rgba(139,92,246,.35);border-radius:16px;padding:9px 20px;backdrop-filter:blur(20px);}
.hp{display:flex;flex-direction:column;align-items:center;gap:1px;}
.hl{font-size:8px;font-weight:800;letter-spacing:2.5px;text-transform:uppercase;color:rgba(196,181,253,.55);background:linear-gradient(90deg,#a78bfa,#38bdf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.hv{font-size:22px;font-weight:900;color:#fff;line-height:1;font-family:'Space Grotesk',sans-serif;}
.hv.gold{color:#fbbf24;text-shadow:0 0 16px rgba(251,191,36,.65);}
.hv.teal{color:#2dd4bf;text-shadow:0 0 14px rgba(45,212,191,.5);}
.hv.rose{color:#fb7185;text-shadow:0 0 14px rgba(251,113,133,.5);}
.hc{flex:1;display:flex;flex-direction:column;align-items:center;gap:3px;}
.lvlbadge{display:inline-flex;align-items:center;gap:6px;background:rgba(139,92,246,.28);border:1.5px solid rgba(167,139,250,.55);border-radius:99px;padding:5px 16px;font-size:13px;font-weight:800;color:#ddd6fe;}
#ptrack{width:100%;height:8px;background:rgba(255,255,255,.09);border-radius:99px;overflow:hidden;}
#pfill{height:100%;width:0%;background:linear-gradient(90deg,#7c3aed,#ec4899,#f97316);border-radius:99px;transition:width .5s cubic-bezier(.34,1.56,.64,1);box-shadow:0 0 10px rgba(236,72,153,.5);}
#pmeta{display:flex;justify-content:space-between;font-size:10px;color:rgba(255,255,255,.4);margin-top:3px;font-weight:600;}
.legend{display:flex;gap:18px;justify-content:center;}
.leg-item{display:flex;align-items:center;gap:6px;font-size:11.5px;color:rgba(255,255,255,.6);font-weight:600;}
.swatch{width:13px;height:13px;border-radius:3px;}
.sw-prob{background:#6d28d9;border:1.5px solid #a78bfa;}
.sw-ans{background:#0e7490;border:1.5px solid #22d3ee;}
#board{display:grid;grid-template-columns:repeat(4,1fr);gap:9px;}
.card{position:relative;aspect-ratio:5/6;perspective:900px;cursor:pointer;border-radius:13px;transition:transform .15s;}
.card:not(.matched):not(.locked):hover{transform:translateY(-4px) scale(1.03);}
.card.locked,.card.matched{cursor:default;}
.card-inner{position:relative;width:100%;height:100%;transition:transform .5s cubic-bezier(.4,0,.2,1);transform-style:preserve-3d;}
.card.flipped .card-inner,.card.matched .card-inner{transform:rotateY(180deg);}
.face{position:absolute;inset:0;border-radius:13px;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:8px;text-align:center;backface-visibility:hidden;-webkit-backface-visibility:hidden;overflow:hidden;}
.face-back{
    background:repeating-linear-gradient(135deg,#3b0764 0px,#3b0764 7px,#4c0789 7px,#4c0789 14px);
    border:2.5px solid rgba(167,139,250,.65);
    box-shadow:inset 0 0 0 2px rgba(196,181,253,.12),0 8px 28px rgba(0,0,0,.65);
}
.face-back::before{content:'';position:absolute;inset:5px;border-radius:8px;border:1.5px solid rgba(196,181,253,.2);pointer-events:none;}
.face-back::after{content:"🃏";font-size:36px;position:relative;z-index:1;filter:drop-shadow(0 2px 10px rgba(0,0,0,.9));}
.card:not(.matched):not(.locked):hover .face-back{border-color:rgba(196,181,253,.9);box-shadow:0 0 22px rgba(124,58,237,.7),inset 0 0 0 2px rgba(196,181,253,.2);}
.face-front{transform:rotateY(180deg);flex-direction:column;gap:5px;}
.card.type-problem .face-front{background:linear-gradient(160deg,#1e1058 0%,#130b3a 100%);border:2px solid rgba(139,92,246,.7);box-shadow:inset 0 1px 0 rgba(167,139,250,.2),0 8px 28px rgba(88,28,135,.6);}
.card.type-problem .face-front .ftype{font-size:14px;margin-bottom:4px;filter:drop-shadow(0 0 6px rgba(167,139,250,.8));}
.card.type-problem .face-front .ftext{font-size:11.5px;font-weight:700;background:linear-gradient(160deg,#ffffff 0%,#ddd6fe 60%,#c4b5fd 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1.5;letter-spacing:.1px;filter:drop-shadow(0 1px 4px rgba(0,0,0,.8));}
.card.type-answer .face-front{background:linear-gradient(160deg,#0c3050 0%,#061a30 100%);border:2px solid rgba(34,211,238,.65);box-shadow:inset 0 1px 0 rgba(103,232,249,.2),0 8px 28px rgba(8,145,178,.5);}
.card.type-answer .face-front .ftype{font-size:14px;margin-bottom:4px;filter:drop-shadow(0 0 6px rgba(34,211,238,.8));}
.card.type-answer .face-front .ftext{font-size:26px;font-weight:900;font-family:'Space Grotesk',sans-serif;background:linear-gradient(160deg,#ffffff 0%,#a5f3fc 50%,#22d3ee 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;filter:drop-shadow(0 0 14px rgba(34,211,238,.75)) drop-shadow(0 2px 6px rgba(0,0,0,.9));letter-spacing:.8px;animation:ansGlow 2.5s ease-in-out infinite alternate;}
@keyframes ansGlow{from{filter:drop-shadow(0 0 10px rgba(34,211,238,.5)) drop-shadow(0 2px 6px rgba(0,0,0,.9))}to{filter:drop-shadow(0 0 20px rgba(34,211,238,.95)) drop-shadow(0 2px 6px rgba(0,0,0,.9))}}
.card.matched .face-front{background:linear-gradient(160deg,#064e3b 0%,#022c22 100%) !important;border:2px solid rgba(52,211,153,.8) !important;box-shadow:0 0 28px rgba(16,185,129,.6),inset 0 1px 0 rgba(52,211,153,.25) !important;}
.card.matched .face-front .ftype{content:'✅';}
.card.matched .face-front .ftext{color:#a7f3d0 !important;text-shadow:0 0 10px rgba(52,211,153,.6) !important;}
.card.wrong .face-front{background:linear-gradient(160deg,#4c0519 0%,#2d0311 100%) !important;border:2px solid rgba(248,113,133,.8) !important;animation:shk .4s ease;}
@keyframes shk{0%,100%{transform:rotateY(180deg) translateX(0)}25%{transform:rotateY(180deg) translateX(-6px)}75%{transform:rotateY(180deg) translateX(6px)}}
.card.flipped:not(.matched) .face-front{box-shadow:0 0 28px rgba(251,191,36,.7),inset 0 1px 0 rgba(255,255,255,.18) !important;border-color:rgba(251,191,36,.9) !important;}
.burst{position:fixed;pointer-events:none;z-index:200;font-size:28px;animation:bpop .7s ease forwards;transform:translate(-50%,-50%);}
@keyframes bpop{0%{opacity:1;transform:translate(-50%,-50%) scale(.4)}40%{opacity:1;transform:translate(-50%,-80%) scale(1.3)}100%{opacity:0;transform:translate(-50%,-130%) scale(.7)}}
#ctrl{display:flex;justify-content:space-between;align-items:center;gap:8px;background:rgba(10,4,30,.65);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:7px 12px;}
.cbtn{padding:7px 14px;border-radius:9px;font-size:12px;font-weight:700;border:none;cursor:pointer;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:5px;}
.cbtn:active{transform:scale(.95);}
.cbtn-restart{background:rgba(251,191,36,.14);color:#fde68a;border:1px solid rgba(251,191,36,.35);}
.cbtn-restart:hover{background:rgba(251,191,36,.24);}
.cbtn-exit{background:rgba(255,255,255,.06);color:rgba(255,255,255,.55);border:1px solid rgba(255,255,255,.1);}
.cbtn-exit:hover{background:rgba(255,255,255,.14);color:#fff;}
.cbtn-fs{background:rgba(139,92,246,.14);color:#c4b5fd;border:1px solid rgba(139,92,246,.32);}
.cbtn-fs:hover{background:rgba(139,92,246,.24);}
#endModal{display:none;width:100%;overflow:hidden;}
#endModal.show{display:block;animation:bannerDrop .5s cubic-bezier(.34,1.56,.64,1);}
@keyframes bannerDrop{from{opacity:0;transform:translateY(-24px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}
.mbox{background:linear-gradient(135deg,rgba(15,8,40,.96),rgba(12,24,52,.96));border:1.5px solid rgba(139,92,246,.45);border-radius:18px;padding:16px 20px;display:flex;align-items:center;gap:16px;box-shadow:0 8px 40px rgba(0,0,0,.7),0 0 0 1px rgba(139,92,246,.1),inset 0 1px 0 rgba(255,255,255,.06);position:relative;overflow:hidden;}
.mbox::before{content:'';position:absolute;inset:0;background:linear-gradient(90deg,rgba(124,58,237,.08),transparent 40%,rgba(34,211,238,.06) 100%);pointer-events:none;}
.m-left{display:flex;flex-direction:column;align-items:center;gap:2px;flex-shrink:0;}
.m-ico{font-size:36px;line-height:1;animation:popIn .4s cubic-bezier(.34,1.56,.64,1);}
.m-stars{font-size:18px;letter-spacing:3px;}
.m-mid{flex:1;min-width:0;}
.m-title{font-family:'Space Grotesk',sans-serif;font-size:1.1rem;font-weight:900;color:#fff;margin-bottom:3px;text-shadow:0 0 16px rgba(167,139,250,.6);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.m-msg{font-size:.78rem;color:rgba(255,255,255,.55);line-height:1.4;}
.mbrow{display:flex;gap:8px;flex-shrink:0;}
.mbtn{padding:9px 16px;border-radius:10px;font-size:.8rem;font-weight:700;border:none;cursor:pointer;text-align:center;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:5px;transition:all .2s;white-space:nowrap;}
.mbtn:active{transform:scale(.97);}
.mbtn-p{background:linear-gradient(135deg,#7c3aed,#4f46e5);color:#fff;box-shadow:0 4px 14px rgba(124,58,237,.5);}
.mbtn-p:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(124,58,237,.7);}
.mbtn-s{background:rgba(255,255,255,.08);color:rgba(255,255,255,.75);border:1px solid rgba(255,255,255,.14);}
.mbtn-s:hover{background:rgba(255,255,255,.15);color:#fff;}
.pause-overlay {
    display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
    z-index: 9998; align-items: center; justify-content: center; flex-direction: column;
    padding: 16px; overflow-y: auto;
}
.pause-overlay.active { display: flex; }
.pause-card {
    background: linear-gradient(135deg, rgba(20, 10, 45, 0.96) 0%, rgba(10, 5, 25, 0.96) 100%);
    border: 2px solid rgba(167, 139, 250, 0.5); border-radius: 24px;
    padding: 36px 32px; text-align: center; max-width: 420px; width: 100%; margin: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,0.8), 0 0 30px rgba(139, 92, 246, 0.25); color: #fff;
}
.pause-card h2 { color: #c4b5fd; font-size: 1.8rem; font-weight: 800; margin: 0 0 8px; font-family: 'Space Grotesk', sans-serif; }
.pause-card p { color: #94a3b8; margin: 0 0 20px; font-size: 0.95rem; }

/* ============================================================
   RESPONSIVE / MOBILE-ADAPTIVE
   ============================================================ */

/* Touch devices: no hover lift, subtle tap feedback */
@media (hover:none) and (pointer:coarse){
    .card:not(.matched):not(.locked):hover{transform:none;}
    .card:not(.matched):not(.locked):hover .face-back{border-color:rgba(167,139,250,.65);box-shadow:inset 0 0 0 2px rgba(196,181,253,.12),0 8px 28px rgba(0,0,0,.65);}
    .card:not(.matched):not(.locked):active{transform:scale(.97);}
    .fc-nav-btn:hover:not(:disabled),.fc-nav-btn.primary:hover,.ready-btn-start:hover,.mbtn-p:hover{transform:none;}
}

@media (prefers-reduced-motion:reduce){
    #gw::before,#gw::after,.neb,.ready-ico,.card.type-answer .face-front .ftext{animation:none;}
}

/* Phones */
@media (max-width:620px){
    #gw{padding-left:max(8px,env(safe-area-inset-left));padding-right:max(8px,env(safe-area-inset-right));}
    #gameScreen{padding:4px 0;}
    .gi{gap:8px;}

    /* HUD → 4-column grid: level row on top, stats row, nav row */
    #hud{display:grid;grid-template-columns:repeat(4,1fr);gap:10px 4px;padding:10px 8px;border-radius:14px;}
    #hud .hc{grid-column:1/-1;order:-1;flex-direction:row;justify-content:space-between;gap:8px;}
    #hud .eduplay-game-nav{grid-column:1/-1;}
    .hv{font-size:19px;}
    .hl{font-size:7.5px;letter-spacing:1.5px;text-align:center;}
    #starGoalHint{display:block;font-size:8px !important;letter-spacing:0 !important;}
    .lvlbadge{font-size:12px;padding:5px 12px;}

    /* Game nav buttons (classes come from the shared layout) */
    .eduplay-game-nav{display:flex !important;gap:8px;width:100%;}
    .eduplay-game-nav .eduplay-nav-btn{flex:1;min-height:44px;display:inline-flex;align-items:center;justify-content:center;gap:6px;font-size:.75rem;padding:8px 6px;}
    .eduplay-game-nav .eduplay-nav-btn span{font-size:.72rem;}

    .legend{gap:14px;}
    .leg-item{font-size:10.5px;}

    /* 3-column board with taller, narrower cards so word problems fit */
    #board{grid-template-columns:repeat(3,1fr);gap:8px;}
    .card{aspect-ratio:4/5;border-radius:11px;}
    .face{padding:5px;border-radius:11px;}
    .face-back::after{font-size:28px;}
    .face-front{gap:3px;}
    .card.type-problem .face-front .ftype,.card.type-answer .face-front .ftype{font-size:12px;margin-bottom:2px;}
    .card.type-problem .face-front .ftext{font-size:10px;line-height:1.35;overflow-wrap:anywhere;}
    .card.type-answer .face-front .ftext{font-size:21px;letter-spacing:.4px;}

    #ctrl{padding:6px 8px;}
    .cbtn{min-height:44px;font-size:12.5px;padding:8px 14px;flex:1;justify-content:center;}

    /* Pause dialog */
    .pause-card{padding:26px 20px;border-radius:20px;}
    .pause-card h2{font-size:1.5rem;}
    .pause-card .eduplay-game-nav{flex-direction:column;}

    /* Trial */
    #trialScreen{padding:18px 14px 14px;border-radius:22px;}
    .trial-title{font-size:1.2rem;}
    .trial-sub{font-size:14px;margin-bottom:12px;}
    .trial-hud{gap:16px;padding:8px 14px;}
    #trialBoard{margin-top:22px;}
    #trialBoard .card{aspect-ratio:5/6;}
    #trialBoard .card.type-problem .face-front .ftext{font-size:15px;}
    #trialBoard .card.type-answer .face-front .ftext{font-size:28px;}

    /* Ready modal */
    .ready-box{padding:26px 18px;border-radius:20px;}
    .ready-ico{font-size:52px;}
    .ready-title{font-size:1.4rem;}

    /* Flash-card tutorial */
    .fc-overlay{padding:10px !important;}
    .fc-card{padding:18px 14px 14px;border-radius:20px;}
    .fc-deck-stack-1{inset:8px 12px -8px;border-radius:20px;}
    .fc-deck-stack-2{inset:4px 6px -4px;border-radius:20px;}
    .fc-top{margin-bottom:10px;align-items:flex-start;}
    .fc-badge{font-size:.68rem;letter-spacing:.6px;padding:5px 10px;line-height:1.3;}
    .fc-quick-action-btn{font-size:.8rem;padding:7px 11px;min-height:36px;}
    .fc-slide{min-height:0;padding:4px 0 8px;}
    .fc-title{font-size:1.18rem !important;flex-wrap:wrap;}
    .fc-desc{font-size:.92rem;margin-bottom:10px;}
    .fc-box{padding:10px;}
    .fc-row-2{gap:8px;}
    .fc-item{padding:8px 6px;}
    .fc-item-ico{font-size:1.3rem;}
    .fc-item-lbl{font-size:.76rem;letter-spacing:.4px;}
    .fc-item-sub{font-size:.8rem;}
    .fc-item-math{font-size:.95rem;}
    .fc-tip{font-size:.85rem;}
    .fc-footer{gap:8px;padding-top:12px;}
    .fc-nav-btn{padding:10px 12px;font-size:.9rem;min-height:44px;}
    .fc-dots{gap:5px;}
    .fc-dot{width:7px;height:7px;}
    .fc-dot.active{width:16px;}

    /* End-of-level result becomes a centered dialog */
    #endModal.show{position:fixed;inset:0;z-index:9990;display:flex;align-items:center;justify-content:center;padding:16px;background:rgba(4,2,12,.82);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);overflow-y:auto;}
    #endModal .mbox{flex-direction:column;text-align:center;gap:12px;width:100%;max-width:420px;margin:auto;padding:22px 18px;}
    .m-title{white-space:normal;font-size:1.25rem;}
    .m-msg{font-size:.85rem;}
    #endModal .mbrow{flex-direction:column;width:100%;gap:8px;}
    #endModal .mbrow .mbtn,#endModal .mbrow .eduplay-nav-btn{width:100%;min-height:46px;justify-content:center;}
}

/* Small phones */
@media (max-width:380px){
    #board{gap:6px;}
    .card.type-problem .face-front .ftext{font-size:9.5px;}
    .card.type-answer .face-front .ftext{font-size:19px;}
    .eduplay-game-nav .eduplay-nav-btn span{display:none;}
    .fc-row-2{grid-template-columns:1fr;}
    .fc-nav-btn span{font-size:.85rem;}
    .hv{font-size:17px;}
}

/* Landscape phones: wide board (6 × 2), compact chrome */
@media (max-height:520px) and (orientation:landscape){
    #gameScreen{padding:2px 0;}
    .gi{max-width:none;gap:6px;}
    #hud{padding:6px 12px;gap:10px;}
    .hv{font-size:17px;}
    .legend{display:none;}
    #board{grid-template-columns:repeat(6,1fr);gap:6px;}
    .card{aspect-ratio:4/5;}
    .card.type-problem .face-front .ftext{font-size:10px;line-height:1.3;}
    .card.type-answer .face-front .ftext{font-size:20px;}
    .face-back::after{font-size:26px;}
    .eduplay-game-nav .eduplay-nav-btn{min-height:40px;}
    .fc-overlay{padding:8px !important;}
    .fc-slide{min-height:0;}
    .fc-card{padding:14px 18px 12px;}
    #trialScreen{padding:14px 16px;}
    #trialBoard{grid-template-columns:repeat(4,1fr);max-width:420px;}
    #endModal.show{position:fixed;inset:0;z-index:9990;display:flex;align-items:center;justify-content:center;padding:10px;background:rgba(4,2,12,.82);overflow-y:auto;}
    #endModal .mbox{max-width:640px;margin:auto;}
}
</style>

<div id="gw">
    <div class="neb nb1"></div>
    <div class="neb nb2"></div>
    <div class="neb nb3"></div>

    <!-- ══════════════════════════════════════════
         FLASH CARD INSTRUCTIONS & TUTORIAL DECK
    ══════════════════════════════════════════ -->
    <div id="mcmTutOverlay" class="fc-overlay" role="dialog" aria-modal="true" aria-label="Math Card Match Instructions">
        <div class="fc-deck">
            <!-- Physical Stack Layers -->
            <div class="fc-deck-stack-1"></div>
            <div class="fc-deck-stack-2"></div>

            <!-- Active Front Flash Card -->
            <div class="fc-card">
                <div class="fc-top">
                    <span class="fc-badge" id="fcBadge">🃏 HOW TO PLAY • CARD 1 OF 4</span>
                    <button class="fc-quick-action-btn" id="fcQuickActionBtn" type="button" onclick="closeTut(true)" title="Skip to Play">
                        <span>Skip to Play</span> <i class="bi bi-arrow-right-short"></i>
                    </button>
                </div>

                <!-- CARD 1: Word Problem & Answer Pairs -->
                <div id="fcSlide1" class="fc-slide">
                    <h3 class="fc-title"><span>🃏</span> Match Problems to Answers</h3>
                    <p class="fc-desc">Hidden on the board are <b>6 Word Problem cards (📖)</b> and <b>6 Answer cards (💡)</b>. Find all matching pairs!</p>
                    <div class="fc-row-2 fc-box">
                        <div class="fc-item">
                            <div class="fc-item-ico">📖</div>
                            <div class="fc-item-lbl">Word Problem</div>
                            <div class="fc-item-sub">"Maya had 5 space rocks and found 3 more."</div>
                        </div>
                        <div class="fc-item">
                            <div class="fc-item-ico">💡</div>
                            <div class="fc-item-lbl">Matching Answer</div>
                            <div class="fc-item-math" style="color:#2dd4bf;margin-top:6px;">8 Space Rocks</div>
                        </div>
                    </div>
                    <div class="fc-tip">
                        💡 <b>Concept:</b> One card states the story problem, and the other shows the answer!
                    </div>
                </div>

                <!-- CARD 2: How to Flip & Match -->
                <div id="fcSlide2" class="fc-slide hidden">
                    <h3 class="fc-title" style="color:#38bdf8;"><span>👆</span> Flip Two Cards at a Time</h3>
                    <p class="fc-desc">Tap (or click) any card to flip it over, then tap a second card to look for its match!</p>
                    <div class="fc-row-2 fc-box">
                        <div class="fc-item">
                            <div class="fc-item-ico">✅</div>
                            <div class="fc-item-lbl" style="color:#34d399;">Cards Match!</div>
                            <div class="fc-item-sub">They stay revealed, glow green, and you score <b>+10 points</b>!</div>
                        </div>
                        <div class="fc-item">
                            <div class="fc-item-ico">❌</div>
                            <div class="fc-item-lbl" style="color:#f87171;">No Match</div>
                            <div class="fc-item-sub">Both cards flip back. <b>Remember</b> where they are!</div>
                        </div>
                    </div>
                    <div class="fc-tip">
                        💡 <b>Memory Strategy:</b> Memorize the locations of cards you've already seen!
                    </div>
                </div>

                <!-- CARD 3: Speed & Few Tries = 3 Stars! -->
                <div id="fcSlide3" class="fc-slide hidden">
                    <h3 class="fc-title" style="color:#fbbf24;"><span>⭐</span> Speed &amp; Fewer Tries = ⭐⭐⭐</h3>
                    <p class="fc-desc">Complete the board quickly with as few tries as possible to earn top honors:</p>
                    <div class="fc-box" style="text-align:center;border-color:rgba(251,191,36,0.35);">
                        <div style="font-family:'Space Grotesk',sans-serif;font-size:1.15rem;font-weight:900;color:#fbbf24;margin-bottom:4px;">Star Goals</div>
                        <div style="display:flex;justify-content:space-around;flex-wrap:wrap;gap:6px;font-size:0.8rem;font-weight:700;">
                            <div>⭐⭐⭐ &lt; 1:30</div>
                            <div>⭐⭐ &lt; 2:30</div>
                            <div>⭐ 3:30+</div>
                        </div>
                    </div>
                    <div class="fc-tip">
                        💡 <b>Need a breather?</b> Tap <b>Pause</b> or press <b>P</b> anytime!
                    </div>
                </div>

                <!-- CARD 4: Clear Board & Practice Option -->
                <div id="fcSlide4" class="fc-slide hidden">
                    <h3 class="fc-title" style="color:#ec4899;"><span>🚀</span> Ready to Match!</h3>
                    <p class="fc-desc">Match all <b>6 pairs</b> to clear the level and advance! You can do a quick practice round or jump into the real game:</p>
                    <div class="fc-row-2 fc-box">
                        <button type="button" class="fc-item" onclick="startPracticeFromTut()" style="cursor:pointer;background:rgba(34,211,238,0.12);border-color:rgba(34,211,238,0.35);color:#fff;">
                            <div class="fc-item-ico">🎮</div>
                            <div class="fc-item-lbl" style="color:#38bdf8;">Practice Round</div>
                            <div class="fc-item-sub">Try 2 pairs first</div>
                        </button>
                        <button type="button" class="fc-item" onclick="closeTut(true)" style="cursor:pointer;background:rgba(124,58,237,0.18);border-color:rgba(167,139,250,0.45);color:#fff;">
                            <div class="fc-item-ico">🏆</div>
                            <div class="fc-item-lbl" style="color:#c4b5fd;">Real Game</div>
                            <div class="fc-item-sub">Start 6 pairs now</div>
                        </button>
                    </div>
                    <div class="fc-tip">
                        💡 <b>Goal:</b> Find all 6 matches before time runs out!
                    </div>
                </div>

                <!-- Flashcard Footer Navigation -->
                <div class="fc-footer">
                    <button class="fc-nav-btn" id="fcPrevBtn" type="button" onclick="stepFlashCard(-1)">
                        <i class="bi bi-arrow-left"></i> Prev
                    </button>

                    <div class="fc-dots" id="fcDots" role="tablist" aria-label="Flash Cards">
                        <button class="fc-dot active" type="button" onclick="goFlashCard(1)" title="Card 1"></button>
                        <button class="fc-dot" type="button" onclick="goFlashCard(2)" title="Card 2"></button>
                        <button class="fc-dot" type="button" onclick="goFlashCard(3)" title="Card 3"></button>
                        <button class="fc-dot" type="button" onclick="goFlashCard(4)" title="Card 4"></button>
                    </div>

                    <button class="fc-nav-btn primary" id="fcNextBtn" type="button" onclick="stepFlashCard(1)">
                        <span>Next</span> <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         TRIAL SCREEN
    ══════════════════════════════════════════ -->
    <div id="trialScreen">
        <div class="trial-badge">🎮 Practice Round</div>
        <h2 class="trial-title">Trial Game</h2>
        <p class="trial-sub">Match the <strong>2 pairs</strong> below to practice.<br>Find a problem card 📖 and its answer 💡!</p>

        <div class="trial-hud">
            <div class="trial-hud-item">
                <span class="trial-hud-lbl">Matched</span>
                <span class="trial-hud-val teal"><span id="trialMatchedVal">0</span><span style="font-size:13px;color:rgba(255,255,255,.25);">/2</span></span>
            </div>
            <div style="width:1px;height:30px;background:rgba(255,255,255,.1);"></div>
            <div class="trial-hud-item">
                <span class="trial-hud-lbl">Tries</span>
                <span class="trial-hud-val gold" id="trialTriesVal">0</span>
            </div>
        </div>

        <div id="trialTrack"><div id="trialFill"></div></div>
        <div class="trial-meta">
            <span id="trialLabel">0 / 2 matched</span>
            <span id="trialPct">0%</span>
        </div>

        <div id="trialBoard"></div>

        <div class="trial-skip-wrap">
            <button class="trial-skip-link" onclick="skipToRealGame()">Skip practice &amp; play now</button>
        </div>
    </div>


    <!-- ══════════════════════════════════════════
         GAME SCREEN
    ══════════════════════════════════════════ -->
    <div id="gameScreen">
        <div class="gi">
            <div id="hud">
                <div class="hp">
                    <span class="hl">Matched</span>
                    <span class="hv teal"><span id="matchedVal">0</span><span style="font-size:13px;color:rgba(255,255,255,.22)">/6</span></span>
                </div>
                <div class="hc">
                    <div class="lvlbadge">🌌 <span id="lvlBadge">LVL {{ $progress->current_level }}</span></div>
                    <div style="font-size:9px;color:rgba(255,255,255,.25);font-weight:700;letter-spacing:1px;text-transform:uppercase;">Best: <span id="hsVal">{{ $progress->high_score ?? 0 }}</span></div>
                </div>
                <div class="hp"><span class="hl">Score</span><span class="hv gold"><span id="scoreVal">0</span></span></div>
                <div class="hp"><span class="hl">Tries</span><span class="hv"><span id="triesVal">0</span></span></div>
                <div class="hp">
                    <span class="hl">Time <span id="starGoalHint" style="font-size:9px;color:rgba(251,191,36,.55);letter-spacing:0;">⭐⭐⭐ &lt;1:30</span></span>
                    <span class="hv rose" id="timeVal" style="font-variant-numeric:tabular-nums;">0:00</span>
                </div>
                <div class="eduplay-game-nav">
                    <button type="button" class="eduplay-nav-btn eduplay-btn-help" id="helpNavBtn" title="How to Play" onclick="openTut(true)">
                        <i class="bi bi-question-circle-fill"></i> <span>Tutorial</span>
                    </button>
                    <button type="button" class="eduplay-nav-btn eduplay-btn-pause" id="pauseBtn" title="Pause Game" onclick="togglePause()">
                        <i class="bi bi-pause-fill"></i> <span>Pause</span>
                    </button>
                    <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
                    <a href="{{ route('student.games') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Game Arena"><i class="bi bi-box-arrow-right"></i> <span>Exit</span></a>
                </div>
            </div>
            <div><div id="ptrack"><div id="pfill"></div></div><div id="pmeta"><span id="plabel">0 / 6 matched</span><span id="ppct">0%</span></div></div>
            <div class="legend">
                <div class="leg-item"><span class="swatch sw-prob"></span> Word Problem</div>
                <div class="leg-item"><span class="swatch sw-ans"></span> Answer</div>
            </div>
            <!-- PAUSE OVERLAY -->
            <div class="pause-overlay" id="pauseOverlay">
                <div class="pause-card">
                    <div style="font-size:3rem;margin-bottom:0.5rem;">⏸</div>
                    <h2>Game Paused</h2>
                    <p>Take your time! The timer has been paused. Resume when you're ready to find more matches.</p>
                    <button class="mbtn mbtn-p" style="width:100%;padding:12px 28px;font-size:1.05rem;min-height:48px;" onclick="togglePause()">▶ Resume Game</button>
                    <div class="eduplay-game-nav" style="margin-top:16px;display:flex;gap:10px;justify-content:center;">
                        <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
                        <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit"><i class="bi bi-box-arrow-right"></i> <span>Exit</span></a>
                    </div>
                </div>
            </div>

            <div id="endModal">
                <div class="mbox">
                    <div class="m-left">
                        <div class="m-ico" id="endIco">🏆</div>
                        <div class="m-stars" id="endStars"></div>
                    </div>
                    <div class="m-mid">
                        <h2 class="m-title" id="endTitle"></h2>
                        <p class="m-msg" id="endMsg"></p>
                    </div>
                    <div class="mbrow" style="display:flex;align-items:center;gap:10px;">
                        <button class="mbtn mbtn-p" id="modalBtn">Play Again</button>
                        <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
                        <a href="{{ route('student.myProgress') }}" class="eduplay-nav-btn eduplay-btn-exit"><i class="bi bi-box-arrow-right"></i> <span>Exit</span></a>
                    </div>
                </div>
            </div>
            <div id="board"></div>
            <div id="ctrl">
                <button class="cbtn cbtn-restart" id="restartBtn"><i class="bi bi-arrow-clockwise"></i> Restart</button>
                <button class="cbtn cbtn-fs" id="fsBtn"><i class="bi bi-fullscreen"></i> Fullscreen</button>
            </div>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════
     READY MODAL (outside #gw for z-index)
══════════════════════════════════════════ -->
<div id="readyOverlay">
    <div class="ready-box">
        <div class="ready-ico">🎉</div>
        <div class="ready-stars" id="readyStars">⭐⭐⭐</div>
        <h2 class="ready-title">Trial Complete!</h2>
        <p class="ready-sub">You matched all pairs in the practice round.<br>You're ready for the real game!</p>
        <div class="ready-score-badge" id="readyScoreBadge">🎯 Matched in <span id="readyTriesCount">0</span> tries</div>
        <div class="ready-buttons">
            <button class="ready-btn-start" id="readyStartBtn">▶ Start Real Game!</button>
            <button class="ready-btn-practice" id="readyPracticeBtn">🔁 Practice Again</button>
        </div>
    </div>
</div>

<script>
(function(){
'use strict';

/* Ensure a mobile viewport exists (in case layouts.app doesn't define one) */
if(!document.querySelector('meta[name="viewport"]')){
    const m=document.createElement('meta');
    m.name='viewport';
    m.content='width=device-width, initial-scale=1, viewport-fit=cover';
    document.head.appendChild(m);
}

/* ═══════════════════════════════════════════════════════
   CONSTANTS & BLADE DATA
═══════════════════════════════════════════════════════ */
const CURR_LVL={{ $progress->current_level }};
const GAME_ID={{ $game->id }};
const GRADE={{ auth()->user()->grade_level ?? 4 }};
const MAX_LVL={{ $game->total_levels }};
const CSRF='{{ csrf_token() }}';
const WIN_PAIRS=6;
const TRIAL_PAIRS=2;
const TUTORIAL_KEY='mcm_tutorial_done';

/* ═══════════════════════════════════════════════════════
   DOM REFS
═══════════════════════════════════════════════════════ */
const tutorialScreen=document.getElementById('tutorialScreen'); // legacy (not in DOM) – guarded below
const trialScreen=document.getElementById('trialScreen');
const instrScreen=document.getElementById('instrScreen');       // legacy (not in DOM) – guarded below
const gameScreen=document.getElementById('gameScreen');
const readyOverlay=document.getElementById('readyOverlay');

const startBtn=document.getElementById('startBtn');
const restartBtn=document.getElementById('restartBtn');
const fsBtn=document.getElementById('fsBtn');
const boardEl=document.getElementById('board');
const trialBoardEl=document.getElementById('trialBoard');

const matchedValEl=document.getElementById('matchedVal');
const scoreValEl=document.getElementById('scoreVal');
const triesValEl=document.getElementById('triesVal');
const timeValEl=document.getElementById('timeVal');
const lvlBadgeEl=document.getElementById('lvlBadge');
const hsValEl=document.getElementById('hsVal');
const pfillEl=document.getElementById('pfill');
const plabelEl=document.getElementById('plabel');
const ppctEl=document.getElementById('ppct');
const endModal=document.getElementById('endModal');
const endIco=document.getElementById('endIco');
const endStars=document.getElementById('endStars');
const endTitle=document.getElementById('endTitle');
const endMsg=document.getElementById('endMsg');
const modalBtn=document.getElementById('modalBtn');

const trialMatchedValEl=document.getElementById('trialMatchedVal');
const trialTriesValEl=document.getElementById('trialTriesVal');
const trialFillEl=document.getElementById('trialFill');
const trialLabelEl=document.getElementById('trialLabel');
const trialPctEl=document.getElementById('trialPct');

const gwEl=document.getElementById('gw');
function scrollTop0(){ if(gwEl) gwEl.scrollTop=0; }

/* ═══════════════════════════════════════════════════════
   PAUSE CONTROLLER
═══════════════════════════════════════════════════════ */
function togglePause(){
    if(!gameActive)return;
    isPaused = !isPaused;
    const overlay = document.getElementById('pauseOverlay');
    const btn = document.getElementById('pauseBtn');
    if(isPaused){
        clearInterval(timerInt);
        lockBoard = true;
        if(overlay) overlay.classList.add('active');
        if(btn){
            btn.classList.add('paused');
            btn.innerHTML = '<i class="bi bi-play-fill"></i> <span>Resume</span>';
        }
    } else {
        lockBoard = false;
        if(overlay) overlay.classList.remove('active');
        if(btn){
            btn.classList.remove('paused');
            btn.innerHTML = '<i class="bi bi-pause-fill"></i> <span>Pause</span>';
        }
        clearInterval(timerInt);
        timerInt = setInterval(()=>{
            seconds++;
            timeValEl.textContent = fmtTime(seconds);
            if(seconds<=90)       timeValEl.style.color = '#34d399';
            else if(seconds<=150) timeValEl.style.color = '#fbbf24';
            else                  timeValEl.style.color = '#f87171';
            const hint = document.getElementById('starGoalHint');
            if(hint){
                if(seconds<=90)       hint.textContent = '⭐⭐⭐ <1:30';
                else if(seconds<=150) hint.textContent = '⭐⭐ <2:30';
                else                  hint.textContent = '⭐ 3:30+';
            }
        },1000);
    }
}
window.togglePause = togglePause;

/* Auto-pause when the tab / app goes to the background (mobile friendly) */
document.addEventListener('visibilitychange',()=>{
    if(document.hidden && gameActive && !isPaused && !endModal.classList.contains('show')){
        togglePause();
    }
});

/* ═══════════════════════════════════════════════════════
   FLASHCARD DECK CONTROLLER (Instructions & In-game Tutorial)
═══════════════════════════════════════════════════════ */
let tutOpenedFromGame = false;
let currentFcCard = 1;
const TOTAL_FC_CARDS = 4;

window.openTut = function(fromGame = false){
    tutOpenedFromGame = fromGame;
    const ov = document.getElementById('mcmTutOverlay');
    const topActionBtn = document.getElementById('fcQuickActionBtn');

    if (fromGame){
        if (gameActive && !isPaused) window.togglePause();
        if (topActionBtn){
            topActionBtn.className = 'fc-quick-action-btn is-close';
            topActionBtn.innerHTML = '&times;';
            topActionBtn.title = 'Close Tutorial';
            topActionBtn.onclick = () => closeTut(false);
        }
    } else {
        if (topActionBtn){
            topActionBtn.className = 'fc-quick-action-btn';
            topActionBtn.innerHTML = '<span>Skip to Play</span> <i class="bi bi-arrow-right-short"></i>';
            topActionBtn.title = 'Skip Instructions';
            topActionBtn.onclick = () => closeTut(true);
        }
    }

    if (ov){ ov.classList.remove('hidden'); ov.scrollTop = 0; }
    goFlashCard(1);
};

window.goFlashCard = function(cardNum){
    if (cardNum < 1) cardNum = 1;
    if (cardNum > TOTAL_FC_CARDS) cardNum = TOTAL_FC_CARDS;
    currentFcCard = cardNum;

    for (let i = 1; i <= TOTAL_FC_CARDS; i++){
        const slide = document.getElementById('fcSlide' + i);
        if (slide) slide.classList.toggle('hidden', i !== currentFcCard);
    }

    const dots = document.querySelectorAll('#fcDots .fc-dot');
    dots.forEach((dot, idx) => {
        dot.classList.toggle('active', idx === currentFcCard - 1);
    });

    const badge = document.getElementById('fcBadge');
    if (badge){
        const tags = ['THE BOARD', 'HOW TO MATCH', 'STAR GOALS', 'READY TO PLAY'];
        const prefix = tutOpenedFromGame ? 'TUTORIAL' : 'HOW TO PLAY';
        badge.textContent = '🃏 ' + prefix + ' • CARD ' + currentFcCard + ' OF ' + TOTAL_FC_CARDS + ' • ' + tags[currentFcCard - 1];
    }

    const prevBtn = document.getElementById('fcPrevBtn');
    if (prevBtn){
        prevBtn.disabled = (currentFcCard === 1);
    }

    const nextBtn = document.getElementById('fcNextBtn');
    if (nextBtn){
        if (currentFcCard === TOTAL_FC_CARDS){
            if (tutOpenedFromGame){
                nextBtn.innerHTML = '<span>▶ Return to Game</span>';
            } else {
                nextBtn.innerHTML = '<span>🚀 Start Matching!</span>';
            }
        } else {
            nextBtn.innerHTML = '<span>Next</span> <i class="bi bi-arrow-right"></i>';
        }
    }
};

window.stepFlashCard = function(delta){
    if (currentFcCard === TOTAL_FC_CARDS && delta > 0){
        closeTut(true);
        return;
    }
    goFlashCard(currentFcCard + delta);
};

window.startPracticeFromTut = function(){
    const ov = document.getElementById('mcmTutOverlay');
    if (ov) ov.classList.add('hidden');
    showTrialScreen();
};

window.closeTut = function(andStartGame = false){
    const ov = document.getElementById('mcmTutOverlay');
    if (ov) ov.classList.add('hidden');

    if (tutOpenedFromGame){
        if (isPaused) window.togglePause();
    } else {
        // User closed the initial instructions -> proceed to real game!
        localStorage.setItem(TUTORIAL_KEY, '1');
        if (trialScreen) trialScreen.style.display = 'none';
        if (gameScreen) gameScreen.style.display = 'flex';
        initGame();
    }
};

/* Swipe left/right on the tutorial deck to change cards (touch screens) */
(function(){
    const ov = document.getElementById('mcmTutOverlay');
    if(!ov) return;
    let sx=0, sy=0;
    ov.addEventListener('touchstart',e=>{sx=e.touches[0].clientX;sy=e.touches[0].clientY;},{passive:true});
    ov.addEventListener('touchend',e=>{
        const dx=e.changedTouches[0].clientX-sx, dy=e.changedTouches[0].clientY-sy;
        if(Math.abs(dx)>60 && Math.abs(dx)>Math.abs(dy)*1.5){
            if(dx<0 && currentFcCard<TOTAL_FC_CARDS) goFlashCard(currentFcCard+1);
            else if(dx>0 && currentFcCard>1) goFlashCard(currentFcCard-1);
        }
    },{passive:true});
})();

/* ═══════════════════════════════════════════════════════
   TRIAL GAME
═══════════════════════════════════════════════════════ */
// Fixed simple trial pairs — no word problems so focus is on mechanics
const TRIAL_DATA=[
    {pid:'t0',problem:'5 − 2 = ?',answer:'3'},
    {pid:'t1',problem:'4 × 2 = ?',answer:'8'},
];

let trialDeck=[],trialFlipped=[],trialMatched=0,trialTries=0,trialLock=false,trialHintRemoved=false;

function showTrialScreen(){
    if(tutorialScreen) tutorialScreen.style.display='none';
    trialScreen.style.display='block';
    trialScreen.style.animation='fadeUp .5s cubic-bezier(.34,1.56,.64,1)';
    scrollTop0();
    startTrial();
}

function startTrial(){
    trialDeck=[];trialFlipped=[];trialMatched=0;trialTries=0;trialLock=false;trialHintRemoved=false;
    updateTrialHUD();
    trialTriesValEl.textContent='0';
    // Build shuffled 4-card deck
    const cards=[];
    TRIAL_DATA.forEach(p=>{
        cards.push({pid:p.pid,type:'problem',label:p.problem,matched:false});
        cards.push({pid:p.pid,type:'answer',label:p.answer,matched:false});
    });
    trialDeck=shuffle(cards);
    renderTrialBoard();
}

function renderTrialBoard(){
    trialBoardEl.innerHTML='';
    trialDeck.forEach((cd,idx)=>{
        const el=document.createElement('div');
        el.className='card type-'+cd.type;
        if(idx===0&&!trialHintRemoved)el.classList.add('hint-pulse');
        el.dataset.idx=idx;
        const typeIcon=cd.type==='problem'?'📖':'💡';
        el.innerHTML='<div class="card-inner"><div class="face face-back"></div><div class="face face-front"><span class="ftype">'+typeIcon+'</span><span class="ftext">'+cd.label+'</span></div></div>';
        el.addEventListener('click',()=>onTrialCardClick(idx,el));
        trialBoardEl.appendChild(el);
        el.style.opacity='0';el.style.transform='scale(.85) translateY(12px)';
        el.style.transition='opacity .35s,transform .35s';
        setTimeout(()=>{el.style.opacity='1';el.style.transform='scale(1) translateY(0)';},idx*80+60);
    });
}

function onTrialCardClick(idx,cardEl){
    if(trialLock)return;
    const cd=trialDeck[idx];
    if(cd.matched||trialFlipped.some(f=>f.idx===idx))return;
    // Remove hint on first click
    if(!trialHintRemoved){
        trialBoardEl.querySelectorAll('.hint-pulse').forEach(e=>e.classList.remove('hint-pulse'));
        trialHintRemoved=true;
    }
    cardEl.classList.add('flipped');
    trialFlipped.push({idx,cardEl,cd});
    if(trialFlipped.length===2){
        trialTries++;trialTriesValEl.textContent=trialTries;trialLock=true;
        const[first,second]=trialFlipped;
        const isMatch=first.cd.pid===second.cd.pid&&first.cd.type!==second.cd.type;
        if(isMatch){
            setTimeout(()=>{
                first.cd.matched=true;second.cd.matched=true;
                first.cardEl.classList.add('matched');second.cardEl.classList.add('matched');
                trialMatched++;updateTrialHUD();
                spawnBurst('🎉',first.cardEl);spawnBurst('✨',second.cardEl);
                trialFlipped=[];trialLock=false;
                if(trialMatched>=TRIAL_PAIRS)setTimeout(()=>showReadyModal(),600);
            },380);
        }else{
            first.cardEl.classList.add('wrong');second.cardEl.classList.add('wrong');
            setTimeout(()=>{
                first.cardEl.classList.remove('flipped','wrong');
                second.cardEl.classList.remove('flipped','wrong');
                trialFlipped=[];trialLock=false;
            },780);
        }
    }
}

function updateTrialHUD(){
    trialMatchedValEl.textContent=trialMatched;
    const pct=Math.round((trialMatched/TRIAL_PAIRS)*100);
    trialFillEl.style.width=pct+'%';
    trialLabelEl.textContent=trialMatched+' / '+TRIAL_PAIRS+' matched';
    trialPctEl.textContent=pct+'%';
}

/* ═══════════════════════════════════════════════════════
   READY MODAL
═══════════════════════════════════════════════════════ */
function showReadyModal(){
    const stars=trialTries<=2?3:trialTries<=4?2:1;
    document.getElementById('readyStars').textContent='⭐'.repeat(stars);
    document.getElementById('readyTriesCount').textContent=trialTries;
    readyOverlay.classList.add('show');
}

document.getElementById('readyStartBtn').addEventListener('click',()=>{
    readyOverlay.classList.remove('show');
    localStorage.setItem(TUTORIAL_KEY,'1');
    trialScreen.style.display='none';
    gameScreen.style.display='flex';
    scrollTop0();
    initGame();
});

document.getElementById('readyPracticeBtn').addEventListener('click',()=>{
    readyOverlay.classList.remove('show');
    startTrial();
});

window.skipToRealGame=function(){
    localStorage.setItem(TUTORIAL_KEY,'1');
    trialScreen.style.display='none';
    gameScreen.style.display='flex';
    scrollTop0();
    initGame();
};

/* ═══════════════════════════════════════════════════════
   MAIN GAME
═══════════════════════════════════════════════════════ */
let currentLevel=CURR_LVL;
let highScore={{ $progress->high_score ?? 0 }};
let deck=[],flipped=[],matchedCount=0,tries=0,score=0,seconds=0,timerInt=null,lockBoard=false,gameActive=false,isPaused=false;

function rand(a,b){return Math.floor(Math.random()*(b-a+1))+a;}
function pick(a){return a[rand(0,a.length-1)];}
function shuffle(a){const r=a.slice();for(let i=r.length-1;i>0;i--){const j=rand(0,i);[r[i],r[j]]=[r[j],r[i]];}return r;}

const NAMES=['Maya','Liam','Ava','Noah','Zoe','Ethan','Luna','Kai','Sofia','Diego','Mia','Owen','Ella','Leo'];
function genProblem(g,b){
    const r=Math.random();
    let op;
    if(g<=4)op=r<.5?'+':r<.8?'-':'x';
    else if(g===5)op=r<.25?'+':r<.5?'-':r<.75?'x':'d';
    else op=r<.2?'+':r<.4?'-':r<.68?'x':'d';
    const n1=pick(NAMES);let n2=pick(NAMES);while(n2===n1)n2=pick(NAMES);
    let a,bv,ans,q,lbl;
    if(op==='+'){
        if(g<=4){a=rand(10,40+b*4);bv=rand(2,18+b*2);}
        else if(g===5){a=rand(20,70+b*4);bv=rand(8,35+b*4);}
        else{a=rand(50,130+b*8);bv=rand(18,70+b*8);}
        ans=a+bv;
        q=pick([
            n1+' collected '+a+' space rocks on Monday and '+bv+' more on Tuesday. Total?',
            'A spaceship carries '+a+' crew and picks up '+bv+' more. Total crew?',
            n1+' baked '+a+' cookies in the morning and '+bv+' in the afternoon. Total?',
            'There are '+a+' red planets and '+bv+' blue planets. Total planets?',
            n1+' scored '+a+' in round 1 and '+bv+' in round 2. Total score?',
            'A bin had '+a+' apples. '+n2+' added '+bv+' more. How many apples now?'
        ]);lbl=''+ans;
    }else if(op==='-'){
        if(g<=4){a=rand(15,50+b*4);bv=rand(1,a);}
        else if(g===5){a=rand(30,80+b*4);bv=rand(8,a);}
        else{a=rand(60,150+b*8);bv=rand(20,a);}
        ans=a-bv;
        q=pick([
            n1+' had '+a+' fuel cells. Used '+bv+'. How many remain?',
            'There were '+a+' asteroids. '+bv+' destroyed. How many remain?',
            n1+' packed '+a+' snacks and ate '+bv+'. How many are left?',
            'A bag had '+a+' marbles. '+bv+' were lost. How many remain?',
            n1+' had P'+a+'. Spent P'+bv+'. How much is left?',
            'A tree had '+a+' leaves. '+bv+' fell. Leaves remaining?'
        ]);lbl=''+ans;
    }else if(op==='x'){
        if(g<=4){a=rand(2,8);bv=rand(2,12+b);}
        else if(g===5){a=rand(2,11);bv=rand(2,14+b);}
        else{a=rand(3,13+b);bv=rand(3,13+b);}
        ans=a*bv;
        q=pick([
            'There are '+a+' spaceships, each with '+bv+' astronauts. Total?',
            n1+' fills '+a+' crates with '+bv+' apples each. Total apples?',
            a+' rows of seats, '+bv+' seats per row. Total seats?',
            'Each planet has '+bv+' moons. '+a+' planets exist. Total moons?',
            n1+' earns P'+bv+' per hour. Works '+a+' hours. Total earnings?',
            a+' boxes each hold '+bv+' books. Total books?'
        ]);lbl=''+ans;
    }else{
        if(g<=4){bv=rand(2,5);ans=rand(2,9);a=bv*ans;}
        else if(g===5){bv=rand(2,8);ans=rand(2,10+b);a=bv*ans;}
        else{bv=rand(2,11);ans=rand(3,13+b);a=bv*ans;}
        q=pick([
            a+' snacks shared equally among '+bv+' friends. How many each?',
            a+' oxygen tanks split among '+bv+' crew. Tanks per person?',
            'P'+a+' shared equally among '+bv+' students. Each gets?',
            a+' apples in '+bv+' equal baskets. Per basket?',
            a+' students in '+bv+' equal teams. Per team?',
            a+' candies divided into '+bv+' bags equally. Per bag?'
        ]);lbl=''+ans;
    }
    return{question:q,answer:lbl,numAns:ans};
}

function buildDeck(){
    const boost=Math.floor(currentLevel/3);
    const problems=[];const usedAns=new Set();let att=0;
    while(problems.length<WIN_PAIRS&&att<300){
        att++;
        const p=genProblem(GRADE,boost);
        if(!usedAns.has(p.numAns)){usedAns.add(p.numAns);problems.push(p);}
    }
    const d=[];
    problems.forEach((p,i)=>{
        const pid='p'+i;
        d.push({pid,type:'problem',label:p.question,matched:false});
        d.push({pid,type:'answer',label:p.answer,matched:false});
    });
    return shuffle(d);
}

function renderBoard(){
    boardEl.innerHTML='';
    deck.forEach((cd,idx)=>{
        const el=document.createElement('div');
        el.className='card type-'+cd.type;
        el.dataset.idx=idx;
        const typeIcon=cd.type==='problem'?'📖':'💡';
        el.innerHTML='<div class="card-inner"><div class="face face-back"></div><div class="face face-front"><span class="ftype">'+typeIcon+'</span><span class="ftext">'+cd.label+'</span></div></div>';
        el.addEventListener('click',()=>onCardClick(idx,el));
        boardEl.appendChild(el);
        el.style.opacity='0';el.style.transform='scale(.85) translateY(12px)';el.style.transition='opacity .35s,transform .35s';
        setTimeout(()=>{el.style.opacity='1';el.style.transform='scale(1) translateY(0)';},idx*55+80);
    });
}

function onCardClick(idx,cardEl){
    if(!gameActive||lockBoard||isPaused)return;
    const cd=deck[idx];
    if(cd.matched||flipped.some(f=>f.idx===idx))return;
    cardEl.classList.add('flipped');
    flipped.push({idx,cardEl,cd});
    if(flipped.length===2){
        tries++;triesValEl.textContent=tries;lockBoard=true;
        const[first,second]=flipped;
        const isMatch=first.cd.pid===second.cd.pid&&first.cd.type!==second.cd.type;
        if(isMatch){
            setTimeout(()=>{
                first.cd.matched=true;second.cd.matched=true;
                first.cardEl.classList.add('matched');second.cardEl.classList.add('matched');
                matchedCount++;score+=10;updateHUD();
                spawnBurst('🎉',first.cardEl);spawnBurst('✨',second.cardEl);
                flipped=[];lockBoard=false;
                if(matchedCount>=WIN_PAIRS)setTimeout(()=>endGame(true),500);
            },380);
        }else{
            first.cardEl.classList.add('wrong');second.cardEl.classList.add('wrong');
            setTimeout(()=>{
                first.cardEl.classList.remove('flipped','wrong');
                second.cardEl.classList.remove('flipped','wrong');
                flipped=[];lockBoard=false;
            },780);
        }
    }
}

function updateHUD(){
    matchedValEl.textContent=matchedCount;scoreValEl.textContent=score;
    const pct=Math.round((matchedCount/WIN_PAIRS)*100);
    pfillEl.style.width=pct+'%';plabelEl.textContent=matchedCount+' / '+WIN_PAIRS+' matched';ppctEl.textContent=pct+'%';
    lvlBadgeEl.textContent='LVL '+currentLevel;
}

function fmtTime(s){
    const m=Math.floor(s/60), ss=s%60;
    return m+':'+(ss<10?'0':'')+ss;
}

function startTimer(){
    clearInterval(timerInt);seconds=0;
    timeValEl.textContent='0:00';
    timeValEl.style.color='';
    timerInt=setInterval(()=>{
        seconds++;
        timeValEl.textContent=fmtTime(seconds);
        // colour urgency: green → amber → red
        if(seconds<=90)      timeValEl.style.color='#34d399';   // 3-star zone
        else if(seconds<=150) timeValEl.style.color='#fbbf24';  // 2-star zone
        else                  timeValEl.style.color='#f87171';  // 1-star zone
        // update star goal hint
        const hint=document.getElementById('starGoalHint');
        if(seconds<=90)       hint.textContent='⭐⭐⭐ <1:30';
        else if(seconds<=150) hint.textContent='⭐⭐ <2:30';
        else                  hint.textContent='⭐ 3:30+';
    },1000);
}

function spawnBurst(emoji,refEl){
    const r=refEl.getBoundingClientRect();
    const el=document.createElement('div');el.className='burst';el.textContent=emoji;
    el.style.left=(r.left+r.width/2)+'px';el.style.top=(r.top+r.height/2)+'px';
    document.body.appendChild(el);setTimeout(()=>el.remove(),750);
}

function endGame(won){
    clearInterval(timerInt);gameActive=false;
    // ── Time-based star rating ──
    // ≤ 90s  (1:30) → 3 stars  → score 100
    // ≤ 150s (2:30) → 2 stars  → score 70
    // > 150s        → 1 star   → score 40
    const st = seconds <= 90 ? 3 : seconds <= 150 ? 2 : 1;
    const starScore = st === 3 ? 100 : st === 2 ? 70 : 40;
    endStars.textContent='⭐'.repeat(st);
    if(won){
        if(currentLevel<MAX_LVL){
            currentLevel++;
            saveProgress(currentLevel, starScore);
            endIco.textContent='🏆';endTitle.textContent='Level '+(currentLevel-1)+' Complete!';
            endMsg.textContent=
                'Finished in '+fmtTime(seconds)+' with '+tries+' tries — '+
                (st===3?'🌟 3-star time!':st===2?'⭐⭐ 2-star time!':'⭐ Keep practising to beat 1:30!')+
                ' Advancing to Level '+currentLevel+'.';
            modalBtn.textContent='▶ Play Level '+currentLevel;
        }else{
            saveProgress(currentLevel, starScore);
            endIco.textContent='🎉';endTitle.textContent='You Won!';
            endMsg.textContent='Champion! All '+MAX_LVL+' levels cleared in '+fmtTime(seconds)+'!';
            modalBtn.textContent='🔄 Play Again';
        }
    }else{
        endIco.textContent='🃏';endTitle.textContent='Good Try!';
        endMsg.textContent='You matched '+matchedCount+' / '+WIN_PAIRS+' pairs. Try again!';
        modalBtn.textContent='🔄 Try Again';
    }
    endModal.classList.add('show');
    scrollTop0();
}

function saveProgress(lvl, starScore){
    const scoreToSave = starScore ?? score;
    if(scoreToSave > highScore){ highScore = scoreToSave; hsValEl.textContent = highScore; }
    fetch('/student/update-progress',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded','X-CSRF-TOKEN':CSRF},
        body:new URLSearchParams({game_id:GAME_ID, level:lvl, score:scoreToSave, _token:CSRF})
    });
}

function initGame(){
    clearInterval(timerInt);
    isPaused=false;
    const overlay=document.getElementById('pauseOverlay');
    if(overlay)overlay.classList.remove('active');
    const pauseBtn=document.getElementById('pauseBtn');
    if(pauseBtn){
        pauseBtn.classList.remove('paused');
        pauseBtn.innerHTML='<i class="bi bi-pause-fill"></i> <span>Pause</span>';
    }
    deck=buildDeck();flipped=[];matchedCount=0;tries=0;score=0;lockBoard=false;gameActive=true;
    updateHUD();renderBoard();startTimer();
}

/* ═══════════════════════════════════════════════════════
   FULLSCREEN & CONTROL BUTTONS
═══════════════════════════════════════════════════════ */
if(fsBtn){
    // iPhone Safari doesn't support the Fullscreen API on elements — hide the button there
    const fsSupported = !!(gwEl && (gwEl.requestFullscreen || gwEl.webkitRequestFullscreen));
    if(!fsSupported){ fsBtn.style.display='none'; }
    fsBtn.addEventListener('click',()=>{
        if(!document.fullscreenElement){
            if(gwEl.requestFullscreen) gwEl.requestFullscreen();
            else if(gwEl.webkitRequestFullscreen) gwEl.webkitRequestFullscreen();
        }else{
            if(document.exitFullscreen) document.exitFullscreen();
        }
    });
    document.addEventListener('fullscreenchange',()=>{fsBtn.innerHTML=document.fullscreenElement?'<i class="bi bi-fullscreen-exit"></i> Exit Full':'<i class="bi bi-fullscreen"></i> Fullscreen';});
}
if(startBtn) startBtn.addEventListener('click',()=>{if(instrScreen)instrScreen.style.display='none';if(gameScreen)gameScreen.style.display='flex';initGame();});
if(restartBtn) restartBtn.addEventListener('click',()=>{endModal.classList.remove('show');initGame();});
if(modalBtn) modalBtn.addEventListener('click',()=>{endModal.classList.remove('show');if(currentLevel>MAX_LVL)currentLevel=CURR_LVL;initGame();});

window.addEventListener('keydown', (e) => {
    if(e.key === 'p' || e.key === 'P' || e.key === 'Escape'){
        if(gameActive && !endModal.classList.contains('show')){
            window.togglePause();
        }
    }
});

/* ═══════════════════════════════════════════════════════
   ENTRY POINT
═══════════════════════════════════════════════════════ */
(function init(){
    openTut(false);
})();

/* ═══ TUTORIAL REOPEN ═══ */
window.reopenTutorial = function(){
    readyOverlay.classList.remove('show');
    openTut(true);
};

})();
</script>
@endsection