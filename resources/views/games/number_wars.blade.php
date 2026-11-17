@extends('layouts.app')
@section('hide-nav', true)

@section('content')
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
html, body { width: 100%; height: 100%; overflow: hidden; background: #08090f; -webkit-text-size-adjust: 100%; }
button, a { touch-action: manipulation; -webkit-tap-highlight-color: transparent; }

#gameWrapper {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0; margin: 0;
    display: flex; flex-direction: column; align-items: center; justify-content: flex-start;
    padding: 16px 20px 24px;
    padding-top: max(16px, env(safe-area-inset-top));
    padding-bottom: max(24px, env(safe-area-inset-bottom));
    padding-left: max(20px, env(safe-area-inset-left));
    padding-right: max(20px, env(safe-area-inset-right));
    font-family: 'Inter', sans-serif;
    overflow-x: hidden; overflow-y: auto;
    -webkit-overflow-scrolling: touch; overscroll-behavior: contain;
    background:
        radial-gradient(ellipse 120% 60% at 50% -10%, rgba(88,28,135,0.55) 0%, transparent 60%),
        radial-gradient(ellipse 80% 40% at 20% 80%, rgba(30,64,175,0.35) 0%, transparent 55%),
        radial-gradient(ellipse 60% 30% at 85% 60%, rgba(220,38,38,0.18) 0%, transparent 50%),
        #05050f;
    z-index: 10;
}
#gameWrapper::before, #gameWrapper::after {
    content: ''; position: fixed; inset: 0; pointer-events: none; z-index: 0;
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
.nebula { position:fixed; border-radius:50%; pointer-events:none; filter:blur(70px); opacity:.18; z-index:0; }
.nebula-1 { width:400px; height:300px; background:radial-gradient(#7c3aed,transparent); top:-80px; left:-100px; animation:drift 18s ease-in-out infinite alternate; }
.nebula-2 { width:350px; height:250px; background:radial-gradient(#1d4ed8,transparent); bottom:10%; right:-80px; animation:drift 22s ease-in-out infinite alternate-reverse; }
.nebula-3 { width:250px; height:200px; background:radial-gradient(#be185d,transparent); bottom:30%; left:5%; animation:drift 15s ease-in-out infinite alternate; }
@keyframes drift { from{transform:translate(0,0) scale(1)} to{transform:translate(30px,20px) scale(1.12)} }
#gameWrapper > *:not(.nebula) { position: relative; z-index: 1; }
#gameWrapper > .nebula { position: fixed; z-index: 0; }
#gameWrapper > #tutorialScreen { position: fixed; z-index: 10005; }

#gameWrap { width: 100%; max-width: 1320px; height: 100%; max-height: 960px; display: flex; flex-direction: column; gap: 14px; margin: 0 auto; }
#gameScreen { width: 100%; height: 100%; margin-top: auto; margin-bottom: auto; display: flex; flex-direction: column; align-items: center; justify-content: center; }

#hud { width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 10px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 18px; padding: 14px 28px; backdrop-filter: blur(16px); box-shadow: 0 0 0 1px rgba(139,92,246,0.1), inset 0 1px 0 rgba(255,255,255,0.06); }
.hud-pill { display: flex; flex-direction: column; align-items: center; gap: 2px; }
.hud-label { font-size: 14px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: rgba(255,255,255,0.3); }
.hud-val   { font-size: 32px; font-weight: 800; color: #fff; line-height: 1; }
.hud-val.red    { color: #f87171; }
.hud-val.yellow { color: #fbbf24; text-shadow: 0 0 12px rgba(251,191,36,0.5); }
.hud-val.teal   { color: #2dd4bf; }
.hud-center { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 5px; }
.level-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(139,92,246,0.18); border: 1px solid rgba(139,92,246,0.4); border-radius: 99px; padding: 5px 18px; font-size: 18px; font-weight: 800; color: #c4b5fd; letter-spacing: .5px; }
.hs-label { font-size: 14px; color: rgba(255,255,255,0.25); font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }

#progressWrap { width: 100%; }
#progressTrack { width:100%; height:8px; background:rgba(255,255,255,0.07); border-radius:99px; overflow:hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,.4); }
#progressFill  { height:100%; background:linear-gradient(90deg,#7c3aed,#ef4444,#f97316); border-radius:99px; transition:width .5s cubic-bezier(.34,1.56,.64,1); box-shadow: 0 0 10px rgba(239,68,68,0.5); }
#progressMeta  { display:flex; justify-content:space-between; font-size:15px; color:rgba(255,255,255,0.28); margin-top:4px; }

#arenaWrap { position: relative; width: 100%; flex: 1; display: flex; flex-direction: column; border-radius: 24px; overflow: hidden; border: 1px solid rgba(139,92,246,0.25); box-shadow: 0 0 0 1px rgba(139,92,246,0.08), 0 0 60px rgba(139,92,246,0.12), 0 0 120px rgba(220,38,38,0.06), inset 0 1px 0 rgba(255,255,255,0.06); background: radial-gradient(ellipse 80% 40% at 50% 0%, rgba(88,28,135,0.3) 0%, transparent 60%), radial-gradient(ellipse 60% 30% at 10% 100%, rgba(30,64,175,0.2) 0%, transparent 50%), radial-gradient(1.5px 1.5px at 5% 12%, rgba(255,255,255,.6), transparent), radial-gradient(1.5px 1.5px at 18% 68%, rgba(255,255,255,.5), transparent), radial-gradient(1.5px 1.5px at 33% 28%, rgba(255,255,255,.7), transparent), radial-gradient(1.5px 1.5px at 47% 85%, rgba(255,255,255,.4), transparent), radial-gradient(1.5px 1.5px at 62% 18%, rgba(255,255,255,.6), transparent), radial-gradient(1.5px 1.5px at 76% 55%, rgba(255,255,255,.5), transparent), radial-gradient(1.5px 1.5px at 91% 35%, rgba(255,255,255,.7), transparent), radial-gradient(1.5px 1.5px at 85% 80%, rgba(255,255,255,.4), transparent), radial-gradient(1.5px 1.5px at 55% 48%, rgba(200,180,255,.4), transparent), #07070f; padding: 28px 32px 32px; }

#timerWrap { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.07); border-radius: 12px; padding: 10px 16px; }
#timerIcon { font-size: 18px; }
#timerTrack { flex:1; height:11px; background:rgba(255,255,255,0.08); border-radius:99px; overflow:hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,.5); }
#timerFill { height:100%; width:100%; background:linear-gradient(90deg,#2dd4bf,#38bdf8); border-radius:99px; transition:width 1s linear; box-shadow: 0 0 8px rgba(45,212,191,0.6); }
#timerNum { font-size: 22px; font-weight: 800; color: #fff; min-width: 40px; text-align: right; font-variant-numeric: tabular-nums; }
#timerLabel { font-size: 14px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: rgba(255,255,255,0.3); min-width: 44px; }

#problemCard { background: linear-gradient(135deg, rgba(139,92,246,0.1) 0%, rgba(220,38,38,0.08) 100%); border: 1px solid rgba(139,92,246,0.25); border-radius: 18px; padding: 30px 34px; text-align: center; margin-bottom: 26px; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 130px; box-shadow: inset 0 1px 0 rgba(255,255,255,0.07), 0 0 30px rgba(139,92,246,0.1); position: relative; overflow: hidden; }
#problemCard::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: conic-gradient(from 0deg, transparent 0deg, rgba(139,92,246,0.05) 60deg, transparent 120deg); animation: rotateSlow 12s linear infinite; pointer-events: none; }
@keyframes rotateSlow { to { transform: rotate(360deg); } }
#problemEyebrow { font-size: 15px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #a78bfa; margin-bottom: 12px; opacity: .8; display: flex; align-items: center; gap: 6px; }
#problemEyebrow::before, #problemEyebrow::after { content: ''; display: inline-block; width: 24px; height: 1px; background: rgba(167,139,250,0.4); }
#problemText { font-size: 26px; font-weight: 600; color: #fff; line-height: 1.6; max-width: 760px; position: relative; z-index: 1; text-shadow: 0 0 30px rgba(139,92,246,0.3); }
#problemCard.flashIn { animation: problemFlash .45s cubic-bezier(.34,1.56,.64,1); }
@keyframes problemFlash { 0%{opacity:0;transform:scale(.95) translateY(8px)} 100%{opacity:1;transform:scale(1) translateY(0)} }

#shipGrid { flex: 1; display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; align-content: center; }
.ship { position: relative; background: linear-gradient(160deg, rgba(88,28,135,0.22) 0%, rgba(15,8,40,0.7) 100%); border: 1.5px solid rgba(139,92,246,0.3); border-radius: 22px; padding: 26px 14px 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; cursor: pointer; min-height: 160px; transition: transform .18s cubic-bezier(.34,1.56,.64,1), border-color .18s, box-shadow .18s, background .18s; overflow: hidden; }
.ship::before { content: ''; position: absolute; inset: 0; border-radius: 22px; background: radial-gradient(ellipse at 50% 0%, rgba(167,139,250,0.15), transparent 60%); pointer-events: none; }
.ship::after { content: '🔥'; position: absolute; bottom: -24px; font-size: 24px; opacity: 0; transition: bottom .2s, opacity .2s; filter: blur(2px); }
@media (hover: hover) { .ship:hover { transform: translateY(-6px); border-color: rgba(167,139,250,0.7); box-shadow: 0 12px 30px rgba(88,28,135,0.45), 0 0 0 1px rgba(167,139,250,0.25), inset 0 1px 0 rgba(255,255,255,0.1); background: linear-gradient(160deg, rgba(109,40,217,0.3) 0%, rgba(30,10,60,0.7) 100%); } .ship:hover::after { bottom: -6px; opacity: 1; } }
.ship:active { transform: scale(0.95); }
.ship-num { position: absolute; top: 12px; left: 12px; width: 26px; height: 26px; border-radius: 8px; background: rgba(167,139,250,0.2); border: 1px solid rgba(167,139,250,0.5); color: #c4b5fd; font-size: 12px; font-weight: 900; display: flex; align-items: center; justify-content: center; }
.ship-lottie { width: 90px; height: 90px; display: flex; align-items: center; justify-content: center; pointer-events: none; transition: transform .3s ease, opacity .2s ease; }
.ship-lottie svg { width: 100% !important; height: 100% !important; transform: scale(1.35); transform-origin: center center; }
.ship-icon { font-size: 48px; line-height: 1; filter: drop-shadow(0 4px 14px rgba(139,92,246,0.5)); animation: shipHover 3s ease-in-out infinite; }
.ship:nth-child(2) .ship-icon { animation-delay: .75s; }
.ship:nth-child(3) .ship-icon { animation-delay: 1.5s; }
.ship:nth-child(4) .ship-icon { animation-delay: 2.25s; }
@keyframes shipHover { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-4px)} }
.ship-answer { font-size: 38px; font-weight: 900; color: #fff; text-shadow: 0 0 16px rgba(167,139,250,0.6); letter-spacing: -.5px; }
.ship-key { font-size: 14px; color: rgba(255,255,255,0.25); font-weight: 700; letter-spacing: 1px; background: rgba(255,255,255,0.06); border-radius: 5px; padding: 3px 10px; }
.ship.correct { border-color: #34d399; background: linear-gradient(160deg, rgba(16,185,129,0.35) 0%, rgba(6,60,40,0.85) 100%); box-shadow: 0 0 35px rgba(52,211,153,0.6); transform: translateY(-4px) scale(1.03); z-index: 20; }
.ship.wrong { border-color: #f87171; background: linear-gradient(160deg, rgba(239,68,68,0.25) 0%, rgba(60,10,10,0.7) 100%); box-shadow: 0 0 30px rgba(239,68,68,0.3); animation: shakeShip .4s ease-in-out; }
.ship.dim { opacity: .3; pointer-events: none; filter: grayscale(.5); }
@keyframes shakeShip { 0%,100%{ transform: translateX(0); } 20%{ transform: translateX(-8px); } 60%{ transform: translateX(8px); } }

#flashOverlay { position: absolute; inset: 0; pointer-events: none; opacity: 0; transition: opacity .12s; border-radius: 24px; z-index: 5; }
#controls { width: 100%; display: flex; justify-content: space-between; align-items: center; gap: 8px; background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.06); border-radius: 14px; padding: 10px 18px; }
.ctrl-btn { padding: 8px 17px; border-radius: 10px; font-size: 16px; font-weight: 700; border: none; cursor: pointer; transition: all .15s; text-decoration: none; display: inline-block; }
.ctrl-btn:active { transform: scale(0.95); }
.btn-exit    { background:rgba(255,255,255,0.05); color:rgba(255,255,255,0.45); border:1px solid rgba(255,255,255,0.08); }
.btn-exit:hover { background:rgba(255,255,255,0.1); color:#fff; }
.btn-fs      { background:rgba(139,92,246,0.1); color:#a78bfa; border:1px solid rgba(139,92,246,0.25); }
.btn-fs:hover { background:rgba(139,92,246,0.2); }
.hint-keys { font-size: 15px; color: rgba(255,255,255,0.2); text-align: center; }
kbd { background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.18); border-radius:4px; padding:1px 5px; font-family:monospace; font-size:14px; color:rgba(255,255,255,0.5); }

#pauseOverlay { display: none; position: absolute; inset: 0; background: rgba(5,5,15,0.82); backdrop-filter: blur(10px); border-radius: 24px; align-items: center; justify-content: center; flex-direction: column; z-index: 50; text-align: center; padding: 16px; }
#pauseOverlay.active { display: flex; }
#pauseOverlay h2 { color: #fff; font-size: 2.4rem; font-weight: 900; margin-bottom: 6px; text-shadow: 0 0 30px rgba(167,139,250,0.8); }
#pauseOverlay p  { color: rgba(255,255,255,0.4); font-size: 1.1rem; margin-bottom: 28px; }
.resume-btn { background: linear-gradient(135deg,#7c3aed,#4f46e5); color: #fff; font-size: 1rem; font-weight: 800; padding: 14px 44px; border-radius: 14px; border: none; cursor: pointer; box-shadow: 0 6px 24px rgba(124,58,237,.45); transition: all .2s; letter-spacing: .5px; min-height: 48px; }
.resume-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(124,58,237,.6); }

#instructionScreen { width: 100%; max-width: 760px; margin: auto; color: #fff; }
.instr-card { background: linear-gradient(165deg, #0e0b1e 0%, #111533 100%); border: 2px solid rgba(139,92,246,.45); border-radius: 26px; box-shadow: 0 35px 80px rgba(0,0,0,.85), 0 0 50px rgba(139,92,246,.2); overflow: hidden; position: relative; display: flex; flex-direction: column; }
.instr-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #7c3aed, #818cf8, #38bdf8, #34d399); }
.instr-header { display: flex; justify-content: space-between; align-items: center; padding: 20px 24px 16px; border-bottom: 1px solid rgba(255,255,255,.07); }
.instr-tag { display: inline-flex; align-items: center; gap: 8px; font-size: .78rem; font-weight: 800; text-transform: uppercase; letter-spacing: .8px; color: #a78bfa; background: rgba(139,92,246,.12); border: 1px solid rgba(139,92,246,.35); padding: 5px 14px; border-radius: 50px; }
.instr-slide-counter { font-size: .75rem; font-weight: 700; color: #64748b; letter-spacing: .5px; }
.instr-slide-body { padding: 26px 30px 8px; flex: 1; }
.instr-hero { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; }
.instr-hero-icon { font-size: 2.1rem; width: 56px; height: 56px; border-radius: 14px; flex-shrink: 0; background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); display: flex; align-items: center; justify-content: center; }
.instr-hero h3 { font-size: 1.3rem; font-weight: 900; color: #fff; margin: 0; }
.instr-hero p  { font-size: .9rem; color: #94a3b8; margin: 3px 0 0; }
.instr-callout { background: rgba(251,191,36,.08); border: 1px solid rgba(251,191,36,.25); border-radius: 10px; padding: 9px 13px; font-size: .81rem; color: #fde68a; display: flex; gap: 9px; align-items: flex-start; margin-bottom: 12px; line-height: 1.5; }
.instr-dots { display: flex; gap: 7px; justify-content: center; margin: 12px 0 10px; }
.instr-dot { width: 8px; height: 8px; border-radius: 50%; background: rgba(139,92,246,.25); border: 1.5px solid rgba(139,92,246,.35); transition: all .25s; cursor: pointer; }
.instr-dot.active { background: #a78bfa; border-color: #a78bfa; width: 22px; border-radius: 4px; box-shadow: 0 0 10px rgba(167,139,250,.5); }
.instr-actions { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 0 24px 22px; flex-wrap: wrap; }
.instr-btn-group { display: flex; gap: 8px; }
.instr-btn-prev { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15); color: #cbd5e1; padding: 8px 18px; border-radius: 50px; font-size: .85rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: all .15s; }
.instr-btn-prev:hover { background: rgba(255,255,255,.14); color: #fff; }
.instr-btn-prev:disabled { opacity: .3; cursor: default; }
.instr-btn-next { background: linear-gradient(135deg,#7c3aed,#4f46e5); border: none; color: #fff; padding: 8px 22px; border-radius: 50px; font-size: .85rem; font-weight: 800; cursor: pointer; box-shadow: 0 4px 14px rgba(124,58,237,.45); font-family: inherit; transition: transform .18s, box-shadow .18s; }
.instr-btn-next:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(124,58,237,.65); }
.start-btn { background: linear-gradient(135deg,#10b981,#059669); border: none; color: #fff; padding: 8px 24px; border-radius: 50px; font-size: .85rem; font-weight: 800; cursor: pointer; box-shadow: 0 4px 14px rgba(16,185,129,.4); font-family: inherit; display: none; align-items: center; gap: 7px; transition: transform .18s, box-shadow .18s; }
.start-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(16,185,129,.55); }
.instr-skip-lnk { font-size: .78rem; color: rgba(167,139,250,.45); cursor: pointer; text-decoration: underline; text-underline-offset: 3px; background: none; border: none; font-family: inherit; transition: color .15s; }
.instr-skip-lnk:hover { color: rgba(167,139,250,.85); }

#endModal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(12px); align-items: center; justify-content: center; z-index: 9999; padding: 16px; overflow-y: auto; }
#endModal.show { display: flex; }
.modal-box { background: linear-gradient(145deg,#12082a,#0f172a); border: 1px solid rgba(139,92,246,0.25); border-radius: 28px; padding: 44px 36px; text-align: center; max-width: 460px; width: 100%; margin: auto; box-shadow: 0 28px 80px rgba(0,0,0,0.8), 0 0 0 1px rgba(139,92,246,0.1), 0 0 60px rgba(88,28,135,0.25); animation: modalPop .4s cubic-bezier(.34,1.56,.64,1); }
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

@keyframes tutFadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
@keyframes tutPopIn{from{transform:scale(0)}to{transform:scale(1)}}
@keyframes tutFlt{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
#tutorialScreen{position:fixed;inset:0;background:rgba(4,6,18,.92);backdrop-filter:blur(14px);z-index:10005;display:flex;align-items:flex-start;justify-content:center;padding:20px;overflow-y:auto;animation:tutFadeUp .28s ease;}
.nw-tut-card{background:linear-gradient(165deg,#0e0b1e 0%,#111533 100%);border:2px solid rgba(139,92,246,.45);border-radius:26px;max-width:820px;width:100%;margin:auto;box-shadow:0 35px 80px rgba(0,0,0,.85),0 0 50px rgba(139,92,246,.2);overflow:hidden;max-height:94vh;max-height:94dvh;display:flex;flex-direction:column;padding:30px 36px 26px;position:relative;}
.nw-tut-card::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#7c3aed,#818cf8,#38bdf8,#34d399);}
.nw-tut-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid rgba(255,255,255,.08);flex-shrink:0;}
.nw-tut-tag{display:inline-flex;align-items:center;gap:8px;font-size:.82rem;font-weight:800;text-transform:uppercase;letter-spacing:.8px;color:#a78bfa;background:rgba(139,92,246,.12);border:1px solid rgba(139,92,246,.35);padding:5px 14px;border-radius:50px;}
.nw-tut-close{background:transparent;border:none;color:#94a3b8;font-size:1.8rem;cursor:pointer;line-height:1;transition:color .18s;min-width:44px;min-height:44px;}
.nw-tut-close:hover{color:#fff;}
.nw-tut-body{display:flex;flex-direction:column;flex:1;min-height:0;overflow-y:auto;-webkit-overflow-scrolling:touch;overscroll-behavior:contain;}
.nw-tut-meta{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;gap:8px;}
.nw-tut-indicator{font-size:.72rem;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;color:#818cf8;}
.nw-tut-slide-num{font-size:.75rem;font-weight:700;color:#64748b;letter-spacing:.5px;white-space:nowrap;}
.nw-tut-content{flex:1;}
.nw-tut-hero{display:flex;align-items:center;gap:12px;margin-bottom:10px;}
.nw-tut-hero-icon{font-size:2.1rem;width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);flex-shrink:0;}
.nw-tut-hero h3{font-size:1.35rem;font-weight:900;color:#fff;margin:0;}
.nw-tut-hero p{font-size:.92rem;color:#94a3b8;margin:3px 0 0;}
.nw-tut-callout{background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.25);border-radius:10px;padding:9px 13px;font-size:.82rem;color:#fde68a;display:flex;gap:9px;align-items:flex-start;margin-bottom:12px;line-height:1.5;}
.nw-tut-dots{display:flex;gap:7px;justify-content:center;margin:14px 0 12px;}
.nw-tut-dot{width:8px;height:8px;border-radius:50%;background:rgba(139,92,246,.25);border:1.5px solid rgba(139,92,246,.35);transition:all .25s;cursor:pointer;}
.nw-tut-dot.active{background:#a78bfa;border-color:#a78bfa;width:22px;border-radius:4px;box-shadow:0 0 10px rgba(167,139,250,.5);}
.nw-tut-actions{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
.nw-tut-skip{background:rgba(255,255,255,.07);border:1px solid rgba(251,191,36,.28);color:#fde68a;padding:8px 16px;border-radius:50px;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .15s;font-family:inherit;}
.nw-tut-skip:hover{background:rgba(255,255,255,.12);color:#fff;}
.nw-tut-btn-group{display:flex;gap:8px;}
.nw-tut-prev{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);color:#cbd5e1;padding:8px 18px;border-radius:50px;font-size:.85rem;font-weight:700;cursor:pointer;transition:all .15s;font-family:inherit;}
.nw-tut-prev:hover{background:rgba(255,255,255,.14);color:#fff;}
.nw-tut-prev:disabled{opacity:.3;cursor:default;}
.nw-tut-next{background:linear-gradient(135deg,#7c3aed,#4f46e5);border:none;color:#fff;padding:8px 22px;border-radius:50px;font-size:.85rem;font-weight:800;cursor:pointer;box-shadow:0 4px 14px rgba(124,58,237,.45);transition:transform .18s cubic-bezier(.34,1.56,.64,1),box-shadow .18s;font-family:inherit;}
.nw-tut-next:hover{transform:translateY(-2px);box-shadow:0 10px 26px rgba(124,58,237,.65);}
.nw-tut-start{background:linear-gradient(135deg,#10b981,#059669);border:none;color:#fff;padding:8px 22px;border-radius:50px;font-size:.85rem;font-weight:800;cursor:pointer;box-shadow:0 4px 14px rgba(16,185,129,.4);transition:transform .18s,box-shadow .18s;font-family:inherit;display:none;}
.nw-tut-start:hover{transform:translateY(-2px);box-shadow:0 10px 26px rgba(16,185,129,.55);}

.nw-visual-row { display: flex; align-items: center; justify-content: center; gap: 14px; margin: 20px 0; flex-wrap: wrap; }
.nw-visual-tile { display: flex; flex-direction: column; align-items: center; gap: 8px; padding: 18px 14px; min-width: 100px; flex: 1; max-width: 160px; background: rgba(255,255,255,.04); border: 1.5px solid rgba(139,92,246,.3); border-radius: 16px; animation: nwTileBob 2.6s ease-in-out infinite; }
.nw-visual-tile:nth-child(2) { animation-delay: -.9s; }
.nw-visual-tile:nth-child(3) { animation-delay: -1.8s; }
.nw-visual-ico { font-size: 2.4rem; line-height: 1; filter: drop-shadow(0 4px 12px currentColor); }
.nw-visual-lbl { font-size: .85rem; font-weight: 800; color: #f8fafc; text-transform: uppercase; letter-spacing: .5px; }
.nw-visual-sub { font-size: .7rem; color: #94a3b8; font-weight: 700; }
@keyframes nwTileBob { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }

.nw-flow { display: flex; align-items: center; justify-content: center; gap: 10px; margin: 24px 0; }
.nw-flow-step { display: flex; flex-direction: column; align-items: center; gap: 6px; animation: nwTileBob 2.2s ease-in-out infinite; }
.nw-flow-step:nth-child(3) { animation-delay: -.7s; }
.nw-flow-step:nth-child(5) { animation-delay: -1.4s; }
.nw-flow-ico { width: 76px; height: 76px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; background: linear-gradient(160deg, rgba(139,92,246,.18), rgba(15,8,40,.7)); border: 2px solid rgba(167,139,250,.5); box-shadow: 0 8px 22px rgba(0,0,0,.4); }
.nw-flow-lbl { font-family: inherit; font-weight: 800; font-size: .85rem; color: #f1f5f9; text-transform: uppercase; }
.nw-flow-arrow { font-size: 1.4rem; color: #818cf8; animation: nwArrow 1.4s ease-in-out infinite; }
@keyframes nwArrow { 0%, 100% { transform: translateX(0); opacity: .5; } 50% { transform: translateX(4px); opacity: 1; } }

.nw-example-visual { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 14px 16px; background: rgba(56,189,248,.08); border: 1.5px solid rgba(56,189,248,.35); border-radius: 14px; margin: 12px 0; font-family: inherit; flex-wrap: wrap; }
.nw-example-chip { display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; padding: 0 12px; border-radius: 10px; background: rgba(56,189,248,.18); border: 1.5px solid rgba(56,189,248,.5); color: #a5f3fc; font-family: inherit; font-weight: 900; font-size: 1.1rem; }
.nw-example-chip.nw-example-ans { background: rgba(52,211,153,.2); border-color: rgba(52,211,153,.55); color: #a7f3d0; }

.nw-pick-row { display: flex; align-items: center; justify-content: center; gap: 10px; margin: 18px 0; }
.nw-pick-ship { display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 12px 8px; min-width: 60px; border-radius: 14px; background: rgba(255,255,255,.04); border: 1.5px solid rgba(139,92,246,.3); opacity: .45; }
.nw-pick-ship.nw-pick-right { opacity: 1; background: rgba(52,211,153,.18); border: 2px solid #34d399; box-shadow: 0 0 20px rgba(52,211,153,.5); animation: nwPickPulse 1.6s ease-in-out infinite; }
@keyframes nwPickPulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.08); } }
.nw-pick-ico { font-size: 1.8rem; line-height: 1; }
.nw-pick-num { font-family: inherit; font-size: 1.15rem; font-weight: 900; color: #fff; }

.nw-status-strip { display: flex; align-items: center; justify-content: center; gap: 14px; margin: 14px 0; flex-wrap: wrap; }
.nw-status-chip { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 10px; background: rgba(0,0,0,.3); border: 1px solid rgba(255,255,255,.1); font-family: inherit; font-weight: 800; font-size: .82rem; color: #cbd5e1; }
.nw-status-chip.nw-chip-teal { color: #2dd4bf; border-color: rgba(45,212,191,.4); }
.nw-status-chip.nw-chip-gold { color: #fbbf24; border-color: rgba(251,191,36,.4); }
.nw-status-chip.nw-chip-rose { color: #f87171; border-color: rgba(248,113,113,.4); }

.nw-timer-strip { display: flex; flex-direction: column; gap: 6px; margin: 14px 0; }
.nw-timer-minirow { display: flex; align-items: center; gap: 10px; font-size: .78rem; color: #cbd5e1; font-weight: 700; }
.nw-timer-minibar { height: 8px; border-radius: 4px; flex: 1; }

#nwTrialScreen{display:none;width:100%;max-width:680px;margin:auto;background:rgba(12,5,30,.94);border:1px solid rgba(139,92,246,.38);border-radius:28px;padding:28px 24px 22px;text-align:center;backdrop-filter:blur(28px);box-shadow:0 0 70px rgba(88,28,135,.35);animation:tutFadeUp .5s cubic-bezier(.34,1.56,.64,1);color:#fff;position:relative;z-index:1;}
.trial-badge-nw{display:inline-flex;align-items:center;gap:7px;background:rgba(139,92,246,.18);border:1.5px solid rgba(167,139,250,.38);border-radius:99px;padding:5px 16px;font-size:11px;font-weight:800;color:#c4b5fd;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:8px;}
.trial-title-nw{font-size:1.2rem;font-weight:900;color:#fff;margin-bottom:4px;}
.trial-sub-nw{font-size:12.5px;color:rgba(200,180,255,.5);margin-bottom:16px;line-height:1.5;}
.trial-prob-card{background:linear-gradient(135deg,rgba(139,92,246,.12),rgba(220,38,38,.08));border:1px solid rgba(139,92,246,.28);border-radius:16px;padding:20px 24px;margin-bottom:20px;position:relative;overflow:hidden;}
.trial-prob-eye{font-size:10px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:#a78bfa;margin-bottom:10px;display:flex;align-items:center;gap:6px;justify-content:center;}
.trial-prob-eye::before,.trial-prob-eye::after{content:'';display:inline-block;width:24px;height:1px;background:rgba(167,139,250,.4);}
.trial-prob-txt{font-size:17px;font-weight:600;color:#fff;line-height:1.6;}
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

@media (max-width: 900px), (max-height: 700px) {
    #gameScreen { height: auto !important; min-height: 100%; }
    #gameWrap   { height: auto; max-height: none; }
    #arenaWrap  { flex: none; }
}
@media (hover: none) and (pointer: coarse) { .ship-key { display: none; } .hint-keys { display: none; } }
@media (prefers-reduced-motion: reduce) {
    #gameWrapper::before, #gameWrapper::after, .nebula, #problemCard::before, .ship-icon, .nw-ready-ico,
    .nw-visual-tile, .nw-flow-step, .nw-flow-arrow, .nw-pick-ship.nw-pick-right { animation: none; }
}
@media (max-width: 640px) {
    #gameWrapper { padding: 10px 10px 16px; padding-top: max(10px, env(safe-area-inset-top)); padding-bottom: max(16px, env(safe-area-inset-bottom)); padding-left: max(10px, env(safe-area-inset-left)); padding-right: max(10px, env(safe-area-inset-right)); }
    #gameWrap { gap: 10px; }
    #hud { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 10px 8px; padding: 12px 12px; border-radius: 16px; }
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
    #controls { padding: 8px 10px; justify-content: center; }
    .ctrl-btn { min-height: 44px; padding: 10px 18px; font-size: 14px; }
    #pauseOverlay { position: fixed; z-index: 9998; border-radius: 0; }
    #pauseOverlay h2 { font-size: 1.8rem; }
    #pauseOverlay p { font-size: .95rem; margin-bottom: 20px; }
    #pauseOverlay .eduplay-game-nav { flex-direction: column; width: 100%; max-width: 260px; }
    .modal-box { padding: 28px 20px; border-radius: 22px; }
    .modal-end-icon { font-size: 54px; }
    .modal-box h2 { font-size: 1.5rem; }
    .modal-box p { font-size: 1rem; margin-bottom: 20px; }
    .modal-btn-row { flex-direction: column !important; align-items: stretch !important; }
    .modal-btn-row .modal-btn, .modal-btn-row .eduplay-nav-btn { width: 100%; min-height: 46px; justify-content: center; }
    .instr-card { border-radius: 20px; }
    .instr-header { padding: 14px 16px 12px; }
    .instr-slide-body { padding: 16px 16px 4px; }
    .instr-hero { gap: 12px; margin-bottom: 12px; }
    .instr-hero-icon { width: 44px; height: 44px; font-size: 1.6rem; }
    .instr-hero h3 { font-size: 1.1rem; }
    .instr-hero p { font-size: .82rem; }
    .instr-actions { padding: 0 14px 16px; }
    .instr-btn-prev, .instr-btn-next, .start-btn { min-height: 44px; }
    .instr-skip-lnk { padding: 10px 6px; }
    #tutorialScreen { padding: 0; align-items: stretch; }
    .nw-tut-card { max-width: none; max-height: none; height: 100%; height: 100dvh; border-radius: 0; border-width: 0; margin: 0; padding: 16px 14px calc(12px + env(safe-area-inset-bottom)); }
    .nw-tut-tag { font-size: .72rem; padding: 5px 11px; }
    .nw-tut-hero { gap: 10px; }
    .nw-tut-hero-icon { width: 44px; height: 44px; font-size: 1.7rem; }
    .nw-tut-hero h3 { font-size: 1.12rem; }
    .nw-tut-hero p { font-size: .82rem; }
    .nw-tut-actions { position: sticky; bottom: 0; background: #0f1330; padding: 10px 0 4px; }
    .nw-tut-skip { min-height: 44px; padding: 10px 12px; font-size: .75rem; }
    .nw-tut-prev, .nw-tut-next, .nw-tut-start { min-height: 44px; padding: 10px 16px; font-size: .85rem; }
    .nw-visual-tile { min-width: 84px; padding: 14px 10px; }
    .nw-visual-ico { font-size: 2rem; }
    .nw-visual-lbl { font-size: .75rem; }
    .nw-visual-sub { font-size: .64rem; }
    .nw-flow-ico { width: 62px; height: 62px; font-size: 1.8rem; }
    .nw-flow-lbl { font-size: .75rem; }
    .nw-flow-arrow { font-size: 1.1rem; }
    .nw-pick-ship { min-width: 48px; padding: 10px 6px; }
    .nw-pick-ico { font-size: 1.5rem; }
    .nw-pick-num { font-size: 1rem; }
    .nw-example-chip { height: 34px; min-width: 34px; font-size: 1rem; padding: 0 9px; }
    #nwTrialScreen { padding: 18px 14px 14px; border-radius: 22px; }
    .trial-prob-card { padding: 14px 14px; margin-bottom: 14px; }
    .trial-prob-txt { font-size: 15px; }
    #nwTrialShipGrid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .trial-ship { padding: 14px 8px 12px; }
    .trial-ship .ship-lottie { width: 64px; height: 64px; }
    .trial-ship-ans { font-size: 22px; }
    .nw-ready-box { padding: 26px 18px; border-radius: 20px; }
    .nw-ready-ico { font-size: 50px; }
    .nw-ready-title { font-size: 1.3rem; }
}
@media (max-width: 380px) {
    .hud-val { font-size: 21px; }
    .eduplay-game-nav .eduplay-nav-btn span { display: none; }
    #problemText { font-size: 15px; }
    .ship-answer { font-size: 23px; }
    .ship-lottie { width: 56px; height: 56px; }
    .nw-visual-tile { min-width: 72px; padding: 12px 6px; }
    .nw-visual-ico { font-size: 1.6rem; }
    .nw-flow-ico { width: 52px; height: 52px; font-size: 1.5rem; }
    .nw-pick-ship { min-width: 40px; padding: 8px 4px; }
}
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
    .nw-visual-tile, .nw-flow-step { animation: none; }
}

/* ── Burger Menu ───────────────────────────────── */
#game-music-controls { display:none !important; }
.eduplay-game-nav { display:none !important; }
.nw-burger-btn { width:44px; height:44px; border-radius:12px; background:rgba(139,92,246,.15); border:2px solid rgba(139,92,246,.4); color:#c4b5fd; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:5px; cursor:pointer; transition:all .2s; padding:0; flex-shrink:0; position:relative; z-index:100; }
.nw-burger-btn:hover { background:rgba(139,92,246,.28); transform:scale(1.05); }
.nw-burger-line { width:20px; height:2px; background:currentColor; border-radius:2px; transition:all .3s; display:block; }
.nw-burger-btn.active .nw-burger-line:nth-child(1) { transform:translateY(7px) rotate(45deg); }
.nw-burger-btn.active .nw-burger-line:nth-child(2) { opacity:0; }
.nw-burger-btn.active .nw-burger-line:nth-child(3) { transform:translateY(-7px) rotate(-45deg); }

@media (max-width: 768px) {
  .nw-burger-btn { position: fixed; top: 12px; right: 12px; width: 40px; height: 40px; border-radius: 10px; }
}

@media (max-width: 480px) {
  .nw-burger-btn { position: fixed; top: 10px; right: 10px; width: 36px; height: 36px; border-radius: 8px; gap: 3px; }
  .nw-burger-line { width: 18px; }
}
.nw-burger-overlay { position:fixed; inset:0; background:rgba(0,0,0,.75); backdrop-filter:blur(4px); z-index:9999; opacity:0; pointer-events:none; transition:opacity .3s; }
.nw-burger-overlay.active { opacity:1; pointer-events:all; }
.nw-burger-menu { position:fixed; top:0; right:-100%; width:min(320px,85vw); height:100vh; height:100dvh; background:linear-gradient(160deg,rgba(15,23,42,.98),rgba(30,41,59,.98)); border-left:2px solid rgba(139,92,246,.4); backdrop-filter:blur(20px); z-index:10000; transition:right .35s ease; box-shadow:-10px 0 40px rgba(0,0,0,.8); display:flex; flex-direction:column; padding:20px; padding-top:70px; overflow-y:auto; }
.nw-burger-menu.active { right:0; }
.nw-burger-header { margin-bottom:24px; padding-bottom:16px; border-bottom:1px solid rgba(255,255,255,.1); }
.nw-burger-title { font-size:1.3rem; font-weight:900; color:white; display:flex; align-items:center; gap:10px; }
.nw-burger-subtitle { font-size:.8rem; color:#64748b; margin-top:6px; font-weight:700; text-transform:uppercase; letter-spacing:1px; }
.nw-burger-nav { display:flex; flex-direction:column; gap:12px; }
.nw-burger-nav .eduplay-nav-btn { width:100% !important; padding:16px 20px; min-height:56px; display:flex !important; align-items:center; gap:12px; font-size:1rem; font-weight:700; border-radius:14px; text-decoration:none; transition:all .2s; justify-content:flex-start; }
.nw-burger-nav .eduplay-nav-btn span { display:inline !important; flex:1; text-align:left; }
.nw-burger-nav .eduplay-nav-btn i { font-size:1.3rem; }
.nw-burger-nav .eduplay-btn-pause { background:rgba(251,191,36,.15); border:2px solid rgba(251,191,36,.4); color:#fbbf24; }
.nw-burger-nav .eduplay-btn-pause:hover { background:rgba(251,191,36,.25); transform:translateX(4px); }
.nw-burger-nav .eduplay-btn-map { background:rgba(52,211,153,.15); border:2px solid rgba(52,211,153,.4); color:#34d399; }
.nw-burger-nav .eduplay-btn-map:hover { background:rgba(52,211,153,.25); transform:translateX(4px); }
.nw-burger-nav .eduplay-btn-exit { background:rgba(239,68,68,.15); border:2px solid rgba(239,68,68,.4); color:#ef4444; }
.nw-burger-nav .eduplay-btn-exit:hover { background:rgba(239,68,68,.25); transform:translateX(4px); }
.nw-music-row { display:flex; align-items:center; gap:10px; padding:12px 16px; background:rgba(139,92,246,.1); border:1.5px solid rgba(139,92,246,.35); border-radius:14px; }
.nw-mute-btn { width:40px; height:40px; border-radius:10px; background:rgba(139,92,246,.2); border:1.5px solid rgba(139,92,246,.5); color:#c4b5fd; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:1.1rem; flex-shrink:0; transition:all .2s; }
.nw-mute-btn:hover { background:rgba(139,92,246,.35); }
.nw-mute-btn.muted { background:rgba(239,68,68,.15); border-color:rgba(239,68,68,.45); color:#f87171; }
.nw-vol-slider { flex:1; height:5px; border-radius:3px; background:rgba(255,255,255,.12); appearance:none; cursor:pointer; accent-color:#8b5cf6; }
.nw-vol-slider::-webkit-slider-thumb { appearance:none; width:15px; height:15px; border-radius:50%; background:#8b5cf6; cursor:pointer; }
.nw-vol-slider::-moz-range-thumb { width:15px; height:15px; border-radius:50%; background:#8b5cf6; cursor:pointer; border:none; }
.nw-vol-value { font-size:.8rem; font-weight:700; color:#a78bfa; min-width:34px; text-align:right; }
</style>

<div id="gameWrapper">
    <div class="nebula nebula-1"></div>
    <div class="nebula nebula-2"></div>
    <div class="nebula nebula-3"></div>

{{-- ============================================================
     TUTORIAL (4 VISUAL SLIDES)
     ============================================================ --}}
<div id="tutorialScreen">
  <div class="nw-tut-card">
    <div class="nw-tut-header">
      <div class="nw-tut-tag">
        <i class="bi bi-rocket-takeoff-fill"></i> How to Play
      </div>
      <button type="button" class="nw-tut-close" onclick="nwSkipTutorial()" title="Skip Tutorial" aria-label="Skip tutorial">&times;</button>
    </div>

    <div class="nw-tut-body">
      <div class="nw-tut-meta">
        <span class="nw-tut-indicator" id="nwTutIndicator">WELCOME</span>
        <span class="nw-tut-slide-num" id="nwTutSlideNum">SLIDE 1 OF 4</span>
      </div>

      {{-- Slide 0 --}}
      <div class="nw-tut-content" id="nwTutStep0">
        <div class="nw-tut-hero">
          <div class="nw-tut-hero-icon" style="color:#a78bfa;">🎮</div>
          <div>
            <h3>Welcome, Commander!</h3>
            <p>Solve problems. Launch the right ship. Win the galaxy!</p>
          </div>
        </div>
        <div class="nw-visual-row">
          <div class="nw-visual-tile">
            <div class="nw-visual-ico" style="color:#38bdf8;">🎯</div>
            <div class="nw-visual-lbl">Play</div>
            <div class="nw-visual-sub">15 levels</div>
          </div>
          <div class="nw-visual-tile">
            <div class="nw-visual-ico" style="color:#fbbf24;">⏱️</div>
            <div class="nw-visual-lbl">Solve</div>
            <div class="nw-visual-sub">60s each</div>
          </div>
          <div class="nw-visual-tile">
            <div class="nw-visual-ico" style="color:#34d399;">🏆</div>
            <div class="nw-visual-lbl">Win</div>
            <div class="nw-visual-sub">10 correct</div>
          </div>
        </div>
        <div class="nw-tut-callout">
          <i class="bi bi-heart-fill" style="flex-shrink:0;"></i>
          <span>You have <strong>3 lives</strong>. Wrong answers and timeouts cost a life.</span>
        </div>
      </div>

      {{-- Slide 1 --}}
      <div class="nw-tut-content" id="nwTutStep1" style="display:none;">
        <div class="nw-tut-hero">
          <div class="nw-tut-hero-icon" style="color:#38bdf8;">📡</div>
          <div>
            <h3>Read → Solve → Launch</h3>
            <p>Every round is a quick 3-step mission.</p>
          </div>
        </div>
        <div class="nw-flow">
          <div class="nw-flow-step">
            <div class="nw-flow-ico">📖</div>
            <div class="nw-flow-lbl">Read</div>
          </div>
          <div class="nw-flow-arrow">➔</div>
          <div class="nw-flow-step">
            <div class="nw-flow-ico">🧮</div>
            <div class="nw-flow-lbl">Solve</div>
          </div>
          <div class="nw-flow-arrow">➔</div>
          <div class="nw-flow-step">
            <div class="nw-flow-ico">🚀</div>
            <div class="nw-flow-lbl">Launch</div>
          </div>
        </div>
        <div class="nw-example-visual">
          <span class="nw-example-chip">3</span>
          <span style="color:#cbd5e1;font-weight:700;">ships ×</span>
          <span class="nw-example-chip">2</span>
          <span style="color:#cbd5e1;font-weight:700;">astronauts =</span>
          <span class="nw-example-chip nw-example-ans">6</span>
        </div>
        <div class="nw-tut-callout">
          <i class="bi bi-lightbulb-fill" style="flex-shrink:0;"></i>
          <span><strong>Tip:</strong> Solve the math first, then look for the answer on a ship.</span>
        </div>
      </div>

      {{-- Slide 2 --}}
      <div class="nw-tut-content" id="nwTutStep2" style="display:none;">
        <div class="nw-tut-hero">
          <div class="nw-tut-hero-icon" style="color:#34d399;">🚀</div>
          <div>
            <h3>Pick the Right Ship</h3>
            <p>Four ships. Only one is correct.</p>
          </div>
        </div>
        <div class="nw-pick-row">
          <div class="nw-pick-ship">
            <div class="nw-pick-ico">🚀</div>
            <div class="nw-pick-num">4</div>
          </div>
          <div class="nw-pick-ship nw-pick-right">
            <div class="nw-pick-ico">🛸</div>
            <div class="nw-pick-num">6</div>
          </div>
          <div class="nw-pick-ship">
            <div class="nw-pick-ico">🛰️</div>
            <div class="nw-pick-num">8</div>
          </div>
          <div class="nw-pick-ship">
            <div class="nw-pick-ico">🔭</div>
            <div class="nw-pick-num">5</div>
          </div>
        </div>
        <div class="nw-status-strip">
          <div class="nw-status-chip nw-chip-teal">✅ +1 point</div>
          <div class="nw-status-chip nw-chip-rose">❌ −1 life</div>
        </div>
        <div class="nw-tut-callout">
          <i class="bi bi-keyboard-fill" style="flex-shrink:0;"></i>
          <span>Tap a ship or press <kbd>1</kbd>–<kbd>4</kbd> on your keyboard.</span>
        </div>
      </div>

      {{-- Slide 3 --}}
      <div class="nw-tut-content" id="nwTutStep3" style="display:none;">
        <div class="nw-tut-hero">
          <div class="nw-tut-hero-icon" style="color:#f87171;">⏱️</div>
          <div>
            <h3>Beat the Timer!</h3>
            <p>60 seconds per problem. Timeout = −1 life.</p>
          </div>
        </div>
        <div class="nw-timer-strip">
          <div class="nw-timer-minirow">
            <span>🟢</span>
            <div class="nw-timer-minibar" style="background:linear-gradient(90deg,#38bdf8,#06b6d4);width:85%;"></div>
            <span>Safe</span>
          </div>
          <div class="nw-timer-minirow">
            <span>🟡</span>
            <div class="nw-timer-minibar" style="background:linear-gradient(90deg,#f59e0b,#d97706);width:45%;"></div>
            <span>Warning</span>
          </div>
          <div class="nw-timer-minirow">
            <span>🔴</span>
            <div class="nw-timer-minibar" style="background:linear-gradient(90deg,#ef4444,#dc2626);width:15%;"></div>
            <span>Hurry!</span>
          </div>
        </div>
        <div class="nw-status-strip">
          <div class="nw-status-chip nw-chip-rose">❤️❤️❤️ Lives</div>
          <div class="nw-status-chip nw-chip-gold">⭐ Score 0/10</div>
        </div>
        <div class="nw-tut-callout">
          <i class="bi bi-rocket-takeoff-fill" style="flex-shrink:0;"></i>
          <span><strong>You're ready!</strong> A quick practice round is next!</span>
        </div>
      </div>

      <div class="nw-tut-dots" id="nwTutDots">
        <div class="nw-tut-dot active" onclick="nwGoToStep(0)"></div>
        <div class="nw-tut-dot" onclick="nwGoToStep(1)"></div>
        <div class="nw-tut-dot" onclick="nwGoToStep(2)"></div>
        <div class="nw-tut-dot" onclick="nwGoToStep(3)"></div>
      </div>

      <div class="nw-tut-actions">
        <button type="button" class="nw-tut-skip" onclick="nwSkipTutorial()">Skip ⏩</button>
        <div class="nw-tut-btn-group">
          <button type="button" class="nw-tut-prev" id="nwTutBack" onclick="nwTutNav(-1)" disabled>◀️ Back</button>
          <button type="button" class="nw-tut-next" id="nwTutNext" onclick="nwTutNav(1)">Continue ▶️</button>
          <button type="button" class="nw-tut-start" id="nwTutStart" onclick="nwShowTrialScreen()">Start Practice! 🚀</button>
        </div>
      </div>
    </div>
  </div>
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
    <div class="instr-header">
      <div class="instr-tag"><i class="bi bi-rocket-takeoff-fill"></i> Number Wars</div>
      <span class="instr-slide-counter" id="instrSlideCounter">SLIDE 1 OF 3</span>
    </div>

    <div class="instr-slide-body" id="instrSlide0">
      <div class="instr-hero">
        <div class="instr-hero-icon" style="color:#a78bfa;">🌌</div>
        <div>
          <h3>Mission Briefing</h3>
          <p>Quick refresher before launch.</p>
        </div>
      </div>
      <div class="nw-visual-row">
        <div class="nw-visual-tile">
          <div class="nw-visual-ico" style="color:#38bdf8;">🎯</div>
          <div class="nw-visual-lbl">Play</div>
          <div class="nw-visual-sub">15 levels</div>
        </div>
        <div class="nw-visual-tile">
          <div class="nw-visual-ico" style="color:#fbbf24;">⏱️</div>
          <div class="nw-visual-lbl">Solve</div>
          <div class="nw-visual-sub">60s each</div>
        </div>
        <div class="nw-visual-tile">
          <div class="nw-visual-ico" style="color:#34d399;">🏆</div>
          <div class="nw-visual-lbl">Win</div>
          <div class="nw-visual-sub">10 correct</div>
        </div>
      </div>
      <div class="instr-callout">
        <i class="bi bi-heart-fill" style="flex-shrink:0;"></i>
        <span>You have <strong>3 lives</strong>. Wrong answers and timeouts each cost a life.</span>
      </div>
    </div>

    <div class="instr-slide-body" id="instrSlide1" style="display:none;">
      <div class="instr-hero">
        <div class="instr-hero-icon" style="color:#fbbf24;">⌨️</div>
        <div>
          <h3>Controls</h3>
          <p>Tap any ship — or use the keyboard.</p>
        </div>
      </div>
      <div class="nw-pick-row">
        <div class="nw-pick-ship nw-pick-right">
          <div class="nw-pick-ico">🚀</div>
          <div class="nw-pick-num">1</div>
        </div>
        <div class="nw-pick-ship nw-pick-right">
          <div class="nw-pick-ico">🛸</div>
          <div class="nw-pick-num">2</div>
        </div>
        <div class="nw-pick-ship nw-pick-right">
          <div class="nw-pick-ico">🛰️</div>
          <div class="nw-pick-num">3</div>
        </div>
        <div class="nw-pick-ship nw-pick-right">
          <div class="nw-pick-ico">🔭</div>
          <div class="nw-pick-num">4</div>
        </div>
      </div>
      <div class="nw-status-strip">
        <div class="nw-status-chip">⌨️ 1-4 = pick</div>
        <div class="nw-status-chip">⏸ P = pause</div>
        <div class="nw-status-chip">⛶ F = fullscreen</div>
      </div>
      <div class="instr-callout">
        <i class="bi bi-lightbulb-fill" style="flex-shrink:0;"></i>
        <span>On phones? Just <strong>tap</strong> any ship.</span>
      </div>
    </div>

    <div class="instr-slide-body" id="instrSlide2" style="display:none;">
      <div class="instr-hero">
        <div class="instr-hero-icon" style="color:#34d399;">⚡</div>
        <div>
          <h3>Quick Rules</h3>
          <p>Everything at a glance.</p>
        </div>
      </div>
      <div class="nw-timer-strip">
        <div class="nw-timer-minirow">
          <span>🟢</span>
          <div class="nw-timer-minibar" style="background:linear-gradient(90deg,#38bdf8,#06b6d4);width:85%;"></div>
          <span>Safe</span>
        </div>
        <div class="nw-timer-minirow">
          <span>🟡</span>
          <div class="nw-timer-minibar" style="background:linear-gradient(90deg,#f59e0b,#d97706);width:45%;"></div>
          <span>Warning</span>
        </div>
        <div class="nw-timer-minirow">
          <span>🔴</span>
          <div class="nw-timer-minibar" style="background:linear-gradient(90deg,#ef4444,#dc2626);width:15%;"></div>
          <span>Hurry!</span>
        </div>
      </div>
      <div class="nw-status-strip">
        <div class="nw-status-chip nw-chip-rose">❤️❤️❤️ 3 Lives</div>
        <div class="nw-status-chip nw-chip-gold">⭐ 0/10 score</div>
        <div class="nw-status-chip nw-chip-teal">🏆 Win = 10 correct</div>
      </div>
      <div class="instr-callout">
        <i class="bi bi-rocket-takeoff-fill" style="flex-shrink:0;"></i>
        <span><strong>You're all set.</strong> Launch when ready!</span>
      </div>
    </div>

    <div class="instr-dots">
      <div class="instr-dot active" onclick="instrGoToSlide(0)"></div>
      <div class="instr-dot" onclick="instrGoToSlide(1)"></div>
      <div class="instr-dot" onclick="instrGoToSlide(2)"></div>
    </div>

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
            <div class="hs-label">Best: <span id="highScore">{{ $progress->high_score ?? 0 }}</span></div>
        </div>
        <div class="hud-right-group" style="display:flex;align-items:center;gap:14px;">
            <div class="hud-pill" style="text-align:right">
                <span class="hud-label">Score</span>
                <span class="hud-val yellow"><span id="score">0</span><span style="font-size:13px;color:rgba(255,255,255,0.2)">/10</span></span>
            </div>
            <div class="eduplay-game-nav">
                <button type="button" class="eduplay-nav-btn eduplay-btn-pause" id="topPauseBtn" title="Pause Game" onclick="togglePause()" data-burger-menu="pause">
                    <i class="bi bi-pause-fill"></i> <span>Pause</span>
                </button>
                <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map" data-burger-menu="map"><i class="bi bi-map-fill"></i> <span>Game Map</span></a>
                <a href="{{ route('student.games') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Game Arena" data-burger-menu="exit"><i class="bi bi-box-arrow-right"></i> <span>Exit</span></a>
            </div>
            <!-- Burger button -->
            <button type="button" class="nw-burger-btn" id="burgerBtn" onclick="toggleBurgerMenu()" aria-label="Game menu">
              <span class="nw-burger-line"></span><span class="nw-burger-line"></span><span class="nw-burger-line"></span>
            </button>
        </div>
    </div>

    <div id="progressWrap">
        <div id="progressTrack"><div id="progressFill" style="width:0%"></div></div>
        <div id="progressMeta"><span id="progressLabel">0 / 10 solved</span><span id="progressPct">0%</span></div>
    </div>

    <div id="arenaWrap">
        <div id="flashOverlay"></div>
        <div id="timerWrap">
            <span id="timerLabel">Time</span>
            <span id="timerIcon"><i class="bi bi-clock-fill"></i></span>
            <div id="timerTrack"><div id="timerFill"></div></div>
            <span id="timerNum">60s</span>
        </div>
        <div id="problemCard">
            <div id="problemEyebrow">Mission Briefing</div>
            <div id="problemText">Initialising mission…</div>
        </div>
        <div id="shipGrid"></div>
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

    <div id="controls">
        <div class="hint-keys"><kbd>1-4</kbd> select &nbsp;·&nbsp; <kbd>P</kbd> pause</div>
    </div>
</div>
</div>

</div>{{-- end gameWrapper --}}

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

<!-- BURGER MENU OVERLAY -->
<div class="nw-burger-overlay" id="burgerOverlay" onclick="toggleBurgerMenu()"></div>
<div class="nw-burger-menu" id="burgerMenu">
  <div class="nw-burger-header">
    <div class="nw-burger-title"><i class="bi bi-menu-button-wide-fill"></i> Game Menu</div>
    <div class="nw-burger-subtitle">Number Wars</div>
  </div>
  <nav class="nw-burger-nav">
    <button type="button" class="eduplay-nav-btn eduplay-btn-pause" onclick="toggleBurgerMenu();" title="Resume Game">
      <i class="bi bi-play-fill"></i> <span>Resume</span>
    </button>
    <a href="{{ route('student.map', $game->name) }}" class="eduplay-nav-btn eduplay-btn-map" title="Game Map">
      <i class="bi bi-map-fill"></i> <span>Game Map</span>
    </a>
    <div class="nw-music-row">
      <button type="button" class="nw-mute-btn" id="muteBtn" onclick="toggleMute()" title="Mute / Unmute">
        <i class="bi bi-volume-up-fill" id="muteIcon"></i>
      </button>
      <input type="range" class="nw-vol-slider" id="volumeSlider" min="0" max="100" value="50" oninput="setVolume(this.value)">
      <span class="nw-vol-value" id="volumeValue">50%</span>
    </div>
    <a href="{{ route('student.games') }}" class="eduplay-nav-btn eduplay-btn-exit" title="Exit to Game Arena">
      <i class="bi bi-box-arrow-right"></i> <span>Exit</span>
    </a>
  </nav>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>
<script src="{{ asset('js/game-feedback.js') }}"></script>
<script src="{{ asset('js/game-music-player.js') }}"></script>
<script src="{{ asset('js/game-burger-menu.js') }}"></script>
<script>
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
</script>

<script>
/* Ensure viewport meta */
(function(){
    if(!document.querySelector('meta[name="viewport"]')){
        const m=document.createElement('meta');
        m.name='viewport';
        m.content='width=device-width, initial-scale=1, viewport-fit=cover';
        document.head.appendChild(m);
    }
})();

/* ==========================================================================
   GLOBAL STATE
   ========================================================================== */
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
const NW_TOTAL_STEPS=4;
const NW_TRIAL_PROBLEM={text:'There are 3 spaceships, each carrying 2 astronauts. How many astronauts in total?',answer:6};
const NW_TRIAL_CHOICES=[6,4,8,5];
let nwTrialAnswering=false;

/* Game state */
let lives = 3;
let correctCount = 0;
let gameRunning = false;
let isPaused = false;
let currentLevel = {{ $progress->current_level }};
const gameId     = {{ $game->id }};
const gradeLevel = {{ auth()->user()->grade_level ?? 4 }};
const WIN_POINTS = 10;
const MAX_LEVEL  = {{ $game->total_levels }};
const HEARTS     = ['&#x2764;&#xFE0F;&#x2764;&#xFE0F;&#x2764;&#xFE0F;','&#x2764;&#xFE0F;&#x2764;&#xFE0F;&#x1F5A4;','&#x2764;&#xFE0F;&#x1F5A4;&#x1F5A4;','&#x1F5A4;&#x1F5A4;&#x1F5A4;'];
const CSRF_TOKEN = '{{ csrf_token() }}';
const QUESTION_TIME = 60;

let timeLeft = QUESTION_TIME;
let timerInterval = null;
let currentProblem = null;
let answering = false;

const RECENT_LIMIT = 40;
let recentKeys = [];
let lastOp = null;

/* ==========================================================================
   HELPERS
   ========================================================================== */
function rand(min, max) { return Math.floor(Math.random() * (max - min + 1)) + min; }
function pick(arr) { return arr[rand(0, arr.length - 1)]; }
function shuffle(arr) { const a = arr.slice(); for (let i = a.length - 1; i > 0; i--) { const j = rand(0, i); [a[i], a[j]] = [a[j], a[i]]; } return a; }

const NAMES = ['Maya','Liam','Ava','Noah','Zoe','Ethan','Luna','Kai','Sofia','Diego','Mia','Owen'];

/* ==========================================================================
   TUTORIAL CONTROLLER
   ========================================================================== */
function nwRenderTutSlide(idx){
    for(let i=0;i<NW_TOTAL_STEPS;i++){
        const el=document.getElementById('nwTutStep'+i);
        if(el) el.style.display=(i===idx)?'':'none';
    }
    const dots=document.querySelectorAll('.nw-tut-dot');
    dots.forEach((d,i)=>d.classList.toggle('active',i===idx));
    const labels=['WELCOME','READ → SOLVE → LAUNCH','PICK A SHIP','TIMER & LIVES'];
    const indicator=document.getElementById('nwTutIndicator');
    const slideNum=document.getElementById('nwTutSlideNum');
    if(indicator) indicator.textContent=labels[idx]||'NUMBER WARS BRIEFING';
    if(slideNum) slideNum.textContent='SLIDE '+(idx+1)+' OF '+NW_TOTAL_STEPS;
    const backBtn=document.getElementById('nwTutBack');
    const nextBtn=document.getElementById('nwTutNext');
    const startBtn=document.getElementById('nwTutStart');
    if(backBtn) backBtn.disabled=(idx===0);
    if(nextBtn) nextBtn.style.display=(idx===NW_TOTAL_STEPS-1)?'none':'inline-block';
    if(startBtn) startBtn.style.display=(idx===NW_TOTAL_STEPS-1)?'inline-block':'none';
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

/* Swipe tutorial */
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

/* ==========================================================================
   INSTRUCTION CONTROLLER
   ========================================================================== */
const INSTR_TOTAL = 3;
let instrSlide = 0;

function instrGoToSlide(idx){
    if(idx < 0 || idx >= INSTR_TOTAL) return;
    document.getElementById('instrSlide' + instrSlide).style.display = 'none';
    document.getElementById('instrSlide' + idx).style.display = '';
    document.querySelectorAll('.instr-dot').forEach((d,i) => d.classList.toggle('active', i === idx));
    const counter = document.getElementById('instrSlideCounter');
    if(counter) counter.textContent = 'SLIDE ' + (idx+1) + ' OF ' + INSTR_TOTAL;
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

/* ==========================================================================
   TRIAL SCREEN
   ========================================================================== */
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
        try {
            const anim = lottie.loadAnimation({ container: lottieBox, renderer: 'svg', loop: false, autoplay: false, path: '/lottie/rocket-launch.json' });
            anim.addEventListener('DOMLoaded', () => anim.goToAndStop(20, true));
            activeTrialLotties[shipIdx] = anim;
        } catch(e) {}
    });
}

function nwTrialSelect(el,val,choices,shipIdx){
    if(nwTrialAnswering)return;
    nwTrialAnswering=true;
    const isCorrect=val===NW_TRIAL_PROBLEM.answer;
    if (window.showGameFeedback) {
        const nextAction = () => {
            if(isCorrect){
                if (activeTrialLotties[shipIdx]) {
                    activeTrialLotties[shipIdx].goToAndPlay(0, true);
                    activeTrialLotties[shipIdx].addEventListener('complete', () => {
                        const lottieEl = document.getElementById(`trialLottie-${shipIdx}`);
                        if (lottieEl) lottieEl.style.opacity = '0';
                        nwShowReadyModal();
                    });
                } else nwShowReadyModal();
            } else nwRenderTrialShips();
        };
        if (isCorrect) window.showGameFeedback(true, NW_TRIAL_PROBLEM.answer, 2000, nextAction);
        else           window.showGameFeedback(false, NW_TRIAL_PROBLEM.answer, 3000, nextAction);
    }
    document.querySelectorAll('.trial-ship').forEach(s=>{if(s!==el)s.classList.add('dim');});
    el.classList.add(isCorrect?'correct':'wrong');
}

function nwShowReadyModal(){
    const overlay=document.createElement('div');
    overlay.id='nwReadyOverlay';
    overlay.style.cssText='display:flex;position:fixed;inset:0;background:rgba(0,0,0,.82);backdrop-filter:blur(8px);z-index:500;align-items:center;justify-content:center;padding:16px;overflow-y:auto;';
    overlay.innerHTML=`
        <div class="nw-ready-box">
            <div class="nw-ready-ico">🎉</div>
            <div class="nw-ready-stars">⭐⭐⭐</div>
            <h2 class="nw-ready-title">Trial Complete!</h2>
            <p class="nw-ready-sub">You launched the correct ship!<br>Ready for the real mission?</p>
            <button class="nw-ready-start" onclick="nwStartRealGame()">🚀 Launch Real Mission!</button>
            <button class="nw-ready-practice" onclick="nwPracticeAgain(this.closest('#nwReadyOverlay'))">🔄 Practice Again</button>
        </div>`;
    document.body.appendChild(overlay);
}

function nwStartRealGame(){
    localStorage.setItem(NW_TUTORIAL_KEY,'1');
    document.querySelectorAll('#nwReadyOverlay').forEach(e=>e.remove());
    document.getElementById('nwTrialScreen').style.display='none';
    startGame();
}
function nwPracticeAgain(overlay){ if(overlay) overlay.remove(); nwRenderTrialShips(); }
function nwSkipToRealGame(){
    localStorage.setItem(NW_TUTORIAL_KEY,'1');
    document.getElementById('nwTrialScreen').style.display='none';
    startGame();
}
function nwShuffle(a){for(let i=a.length-1;i>0;i--){const j=Math.floor(Math.random()*(i+1));[a[i],a[j]]=[a[j],a[i]];}return a;}

/* ==========================================================================
   PROBLEM GENERATION
   ========================================================================== */
function generateWordProblem() {
    const g = gradeLevel, r = Math.random(), lvlBoost = Math.floor(currentLevel / 3);
    let op;
    if      (g <= 4) op = r < 0.60 ? '+' : r < 0.90 ? '-' : '×';
    else if (g === 5) op = r < 0.25 ? '+' : r < 0.50 ? '-' : r < 0.75 ? '×' : '÷';
    else              op = r < 0.20 ? '+' : r < 0.40 ? '-' : r < 0.70 ? '×' : '÷';
    if (op === lastOp && Math.random() < 0.6) {
        const ops = g <= 4 ? ['+','-','×'] : ['+','-','×','÷'];
        op = pick(ops.filter(o => o !== lastOp));
    }
    lastOp = op;
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
    return { text, answer, key: `${op}|${num1}|${num2}` };
}

function generateChoices(answer) {
    const set = new Set([answer]);
    let attempts = 0;
    // First pass: nearby numbers (small delta)
    while (set.size < 4 && attempts < 60) {
        attempts++;
        const off = rand(1, Math.max(3, Math.floor(answer * 0.4)));
        const sign = Math.random() < 0.5 ? 1 : -1;
        const wrong = answer + sign * off;
        if (wrong > 0 && wrong !== answer) set.add(wrong);
    }
    // Second pass: wider range
    attempts = 0;
    while (set.size < 4 && attempts < 60) {
        attempts++;
        const wrong = rand(1, Math.max(20, Math.floor(answer * 1.5)));
        if (wrong !== answer) set.add(wrong);
    }
    return shuffle([...set]);
}

/* ==========================================================================
   HUD & GAME RENDERING
   ========================================================================== */
function renderHUD() {
    const livesIdx = Math.max(0, Math.min(3, 3 - lives));
    document.getElementById('lives').innerHTML = HEARTS[livesIdx];
    document.getElementById('score').textContent = correctCount;
    document.getElementById('levelBadge').textContent = 'LVL ' + currentLevel;
    const pct = Math.round((correctCount / WIN_POINTS) * 100);
    document.getElementById('progressFill').style.width = pct + '%';
    document.getElementById('progressLabel').textContent = correctCount + ' / ' + WIN_POINTS + ' solved';
    document.getElementById('progressPct').textContent = pct + '%';
}

function renderShips(problem) {
    Object.values(activeLotties).forEach(inst => { try { inst.destroy(); } catch(e){} });
    activeLotties = {};

    const grid = document.getElementById('shipGrid');
    grid.innerHTML = '';

    const choices = generateChoices(problem.answer);
    const palettes = shuffle(ROCKET_PALETTES.slice());

    choices.forEach((val, i) => {
        const shipNum = i + 1;
        const div = document.createElement('div');
        div.className = 'ship';
        div.dataset.value = val;
        div.onclick = () => handleShipClick(div, val);
        div.innerHTML = `
            <div class="ship-num">${shipNum}</div>
            <div class="ship-lottie" id="shipLottie-${shipNum}"></div>
            <div class="ship-answer">${val}</div>
            <div class="ship-key">Key [${shipNum}]</div>
        `;
        grid.appendChild(div);

        const lottieBox = div.querySelector('#shipLottie-' + shipNum);
        lottieBox.style.filter = palettes[i % palettes.length].filter;

        try {
            const anim = lottie.loadAnimation({
                container: lottieBox,
                renderer: 'svg',
                loop: false,
                autoplay: false,
                path: '/lottie/rocket-launch.json'
            });
            anim.addEventListener('DOMLoaded', () => anim.goToAndStop(20, true));
            activeLotties[shipNum] = anim;
        } catch(e) {}
    });
}

function renderProblem() {
    let problem;
    let attempts = 0;
    do {
        problem = generateWordProblem();
        attempts++;
    } while (recentKeys.includes(problem.key) && attempts < 20);

    recentKeys.push(problem.key);
    if (recentKeys.length > RECENT_LIMIT) recentKeys.shift();

    currentProblem = problem;
    answering = false;

    const card = document.getElementById('problemCard');
    document.getElementById('problemText').textContent = problem.text;
    card.classList.remove('flashIn');
    void card.offsetWidth;
    card.classList.add('flashIn');

    renderShips(problem);
    startTimer();
}

/* ==========================================================================
   TIMER
   ========================================================================== */
function updateTimerUI() {
    const pct = Math.max(0, (timeLeft / QUESTION_TIME) * 100);
    const fill = document.getElementById('timerFill');
    fill.style.width = pct + '%';
    document.getElementById('timerNum').textContent = Math.max(0, timeLeft) + 's';
    if (timeLeft <= 10)      fill.style.background = 'linear-gradient(90deg,#ef4444,#dc2626)';
    else if (timeLeft <= 30) fill.style.background = 'linear-gradient(90deg,#f59e0b,#d97706)';
    else                     fill.style.background = 'linear-gradient(90deg,#2dd4bf,#38bdf8)';
}

function startTimer() {
    stopTimer();
    timeLeft = QUESTION_TIME;
    updateTimerUI();
    timerInterval = setInterval(() => {
        if (isPaused) return;
        timeLeft--;
        updateTimerUI();
        if (timeLeft <= 0) {
            stopTimer();
            handleTimeout();
        }
    }, 1000);
}

function stopTimer() {
    if (timerInterval) { clearInterval(timerInterval); timerInterval = null; }
}

function handleTimeout() {
    if (answering || !gameRunning) return;
    answering = true;
    lives--;
    renderHUD();
    const ships = document.querySelectorAll('.ship');
    ships.forEach(s => {
        s.style.pointerEvents = 'none';
        if (parseInt(s.dataset.value, 10) === currentProblem.answer) s.classList.add('correct');
        else s.classList.add('dim');
    });
    setTimeout(() => {
        if (lives <= 0) endGame(false);
        else renderProblem();
    }, 1400);
}

/* ==========================================================================
   ANSWER HANDLING
   ========================================================================== */
function handleShipClick(el, val) {
    if (answering || !gameRunning || isPaused) return;
    answering = true;
    stopTimer();

    const isCorrect = (val === currentProblem.answer);
    const ships = document.querySelectorAll('.ship');
    ships.forEach(s => s.style.pointerEvents = 'none');

    // Fire lottie on the picked ship
    const idx = Array.from(ships).indexOf(el) + 1;
    if (activeLotties[idx]) {
        try { activeLotties[idx].goToAndPlay(0, true); } catch(e) {}
    }

    if (isCorrect) {
        el.classList.add('correct');
        ships.forEach(s => { if (s !== el) s.classList.add('dim'); });
        correctCount++;
        renderHUD();
        
        // Show success feedback with Continue button
        if (window.showGameFeedback) {
            const nextAction = () => {
                if (lives <= 0) endGame(false);
                else if (correctCount >= WIN_POINTS) endGame(true);
                else renderProblem();
            };
            window.showGameFeedback(true, currentProblem.answer, 1400, nextAction, null, '🚀');
        }
    } else {
        el.classList.add('wrong');
        ships.forEach(s => {
            if (s !== el) {
                if (parseInt(s.dataset.value, 10) === currentProblem.answer) s.classList.add('correct');
                else s.classList.add('dim');
            }
        });
        lives--;
        renderHUD();
        
        // Show wrong answer feedback with Continue button
        if (window.showGameFeedback) {
            const nextAction = () => {
                if (lives <= 0) endGame(false);
                else if (correctCount >= WIN_POINTS) endGame(true);
                else renderProblem();
            };
            window.showGameFeedback(false, currentProblem.answer, 1400, nextAction, null, '🚀');
        }
    }

    // Fallback if showGameFeedback doesn't exist
    if (!window.showGameFeedback) {
        setTimeout(() => {
            if (lives <= 0)         endGame(false);
            else if (correctCount >= WIN_POINTS) endGame(true);
            else                    renderProblem();
        }, 1400);
    }
}

/* ==========================================================================
   GAME FLOW
   ========================================================================== */
function startGame() {
    const tut = document.getElementById('tutorialScreen');
    const instr = document.getElementById('instructionScreen');
    const trial = document.getElementById('nwTrialScreen');
    if (tut) tut.style.display = 'none';
    if (instr) instr.style.display = 'none';
    if (trial) trial.style.display = 'none';
    document.getElementById('gameScreen').style.display = 'flex';

    lives = 3;
    correctCount = 0;
    gameRunning = true;
    isPaused = false;
    timeLeft = QUESTION_TIME;
    recentKeys = [];
    lastOp = null;

    const ov = document.getElementById('pauseOverlay');
    if (ov) ov.classList.remove('active');
    const modal = document.getElementById('endModal');
    if (modal) modal.classList.remove('show');

    renderHUD();
    renderProblem();
}

function endGame(won) {
    gameRunning = false;
    stopTimer();

    const icon  = document.getElementById('endIcon');
    const title = document.getElementById('endTitle');
    const msg   = document.getElementById('endMessage');
    const btn   = document.getElementById('modalActionBtn');

    if (won) {
        const nextLvl = Math.min(currentLevel + 1, MAX_LEVEL);
        const score = correctCount * 10 + Math.max(0, lives) * 5;
        try {
            fetch('/student/update-progress', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: new URLSearchParams({ game_id: gameId, level: nextLvl, score: score, _token: CSRF_TOKEN })
            }).catch(()=>{});
        } catch(e) {}

        icon.textContent = '🏆';
        title.textContent = currentLevel >= MAX_LEVEL ? 'Galaxy Conquered!' : 'Level ' + currentLevel + ' Complete!';
        msg.textContent = 'You reached ' + correctCount + ' correct answers with ' + Math.max(0, lives) + ' lives left!';
        btn.textContent = currentLevel >= MAX_LEVEL ? '🔄 Play Again' : '▶️ Next Level';
        btn.dataset.nextLevel = currentLevel >= MAX_LEVEL ? '1' : String(currentLevel + 1);
    } else {
        icon.textContent = '💥';
        title.textContent = 'Mission Failed';
        msg.textContent = 'You got ' + correctCount + ' correct before running out of lives.';
        btn.textContent = '🔄 Try Again';
        btn.dataset.nextLevel = String(currentLevel);
    }

    document.getElementById('endModal').classList.add('show');
}

function restartFromModal() {
    document.getElementById('endModal').classList.remove('show');
    const btn = document.getElementById('modalActionBtn');
    const next = parseInt(btn.dataset.nextLevel, 10);
    if (!isNaN(next)) currentLevel = next;
    startGame();
}

function togglePause() {
    if (!gameRunning) return;
    isPaused = !isPaused;
    const ov = document.getElementById('pauseOverlay');
    if (isPaused) { if (ov) ov.classList.add('active'); }
    else          { if (ov) ov.classList.remove('active'); }
}

/* ==========================================================================
   KEYBOARD HANDLERS
   ========================================================================== */
document.addEventListener('keydown', (e) => {
    const tut = document.getElementById('tutorialScreen');
    const instr = document.getElementById('instructionScreen');
    const tutVisible = tut && tut.style.display !== 'none';
    const instrVisible = instr && instr.style.display !== 'none';

    if (tutVisible) {
        if (e.key === 'ArrowRight' || e.key === 'Enter') {
            e.preventDefault();
            const nextBtn = document.getElementById('nwTutNext');
            const startBtn = document.getElementById('nwTutStart');
            if (nextBtn && nextBtn.style.display !== 'none') nwTutNav(1);
            else if (startBtn && startBtn.style.display !== 'none') nwShowTrialScreen();
        } else if (e.key === 'ArrowLeft') {
            e.preventDefault();
            nwTutNav(-1);
        } else if (e.key === 'Escape') {
            e.preventDefault();
            nwSkipTutorial();
        }
        return;
    }

    if (instrVisible) {
        if (e.key === 'ArrowRight' || e.key === 'Enter') {
            e.preventDefault();
            const nextBtn = document.getElementById('instrNextBtn');
            const startBtn = document.getElementById('instrStartBtn');
            if (nextBtn && nextBtn.style.display !== 'none') instrNav(1);
            else if (startBtn && startBtn.style.display !== 'none') startGame();
        } else if (e.key === 'ArrowLeft') {
            e.preventDefault();
            instrNav(-1);
        }
        return;
    }

    if (!gameRunning) return;

    if (e.key === 'p' || e.key === 'P' || e.key === 'Escape') {
        e.preventDefault(); togglePause();
    } else if (['1','2','3','4'].includes(e.key)) {
        const ships = document.querySelectorAll('.ship');
        const idx = parseInt(e.key, 10) - 1;
        if (ships[idx]) ships[idx].click();
    }
});

/* ==========================================================================
   ENTRY POINT
   ========================================================================== */
(function nwInit(){
    if(!localStorage.getItem(NW_TUTORIAL_KEY)){
        document.getElementById('tutorialScreen').style.display='';
        document.getElementById('instructionScreen').style.display='none';
    } else {
        document.getElementById('tutorialScreen').style.display='none';
        document.getElementById('instructionScreen').style.display='';
    }
    document.getElementById('gameScreen').style.display='none';
    nwRenderTutSlide(0);
    instrGoToSlide(0);
})();
</script>
@endsection