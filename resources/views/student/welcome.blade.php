<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>EduPlay — Welcome Back!</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=VT323&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#04030c;
    --panel:#0c0b22;
    --panel-2:#141038;
    --ink:#EAF6FF;
    --ink-dim:#8FA3C8;
    --cyan:#00E5FF;
    --magenta:#FF2E97;
    --yellow:#FFE600;
    --green:#39FF14;
    --red:#FF4D4D;
    --violet:#8A5CFF;
    --pixel:'Press Start 2P', monospace;
    --body:'VT323', monospace;
  }
  *,*::before,*::after{ box-sizing:border-box; }
  html,body{ height:100%; }
  body{
    margin:0;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:34px 16px 46px;
    background:var(--bg);
    color:var(--ink);
    font-family:var(--body);
    font-size:20px;
    line-height:1.35;
    overflow-x:hidden;
  }
  
  /* Background effects */
  .bg-layer{
    position:fixed; inset:0; z-index:0; pointer-events:none;
    background-image:
      radial-gradient(ellipse 80% 50% at 50% -5%, rgba(255,46,151,.22), transparent 70%),
      radial-gradient(ellipse 80% 50% at 50% 105%, rgba(0,229,255,.18), transparent 70%),
      linear-gradient(rgba(0,229,255,.07) 1px, transparent 1px),
      linear-gradient(90deg, rgba(0,229,255,.07) 1px, transparent 1px);
    background-size:100% 100%, 100% 100%, 48px 48px, 48px 48px;
    background-position:0 0, 0 0, 0 0, 0 0;
    animation:gridDrift 9s linear infinite;
  }
  @keyframes gridDrift{
    from{ background-position:0 0, 0 0, 0 0, 0 0; }
    to  { background-position:0 0, 0 0, 0 48px, 48px 0; }
  }
  .orbs{ position:fixed; inset:0; z-index:0; pointer-events:none; overflow:hidden; }
  .orbs i{
    position:absolute; width:320px; height:320px; border-radius:50%;
    filter:blur(70px); opacity:.32;
    animation:orbDrift 20s ease-in-out infinite;
  }
  .orbs i:nth-child(1){ background:var(--magenta); top:-100px; left:-80px; }
  .orbs i:nth-child(2){ background:var(--cyan);    bottom:-120px; right:-90px; animation-delay:-7s; }
  .orbs i:nth-child(3){ background:var(--violet);  top:38%; left:62%; animation-delay:-13s; }
  @keyframes orbDrift{
    0%,100%{ transform:translate(0,0) scale(1); }
    50%    { transform:translate(46px,-34px) scale(1.18); }
  }
  #fx{ position:fixed; inset:0; z-index:55; pointer-events:none; }
  .scanlines{
    position:fixed; inset:0; z-index:60; pointer-events:none;
    background:repeating-linear-gradient(to bottom, rgba(0,0,0,.22) 0 1px, transparent 1px 3px);
    opacity:.7;
  }
  .scanlines::after{
    content:''; position:absolute; inset:0;
    background:radial-gradient(ellipse 90% 80% at 50% 50%, transparent 42%, rgba(0,0,0,.68) 100%);
  }
  .scanlines::before{
    content:''; position:absolute; left:0; right:0; height:28%;
    background:linear-gradient(180deg, transparent, rgba(0,229,255,.055), transparent);
    animation:crtRoll 7s linear infinite;
  }
  @keyframes crtRoll{
    from{ top:-30%; }
    to  { top:110%; }
  }

  .stage{
    position:relative; z-index:1;
    width:100%;
    max-width:720px;
  }
  .cabinet{
    position:relative;
    background:linear-gradient(180deg, #0e0c26 0%, #080719 60%, #060513 100%);
    border:2px solid rgba(0,229,255,.35);
    border-radius:10px;
    padding:0 0 16px;
    box-shadow:
      0 0 0 2px var(--bg),
      0 0 0 5px rgba(0,229,255,.12),
      0 0 60px rgba(0,229,255,.18),
      0 26px 70px rgba(0,0,0,.75);
  }
  .cabinet::before,
  .cabinet::after{
    content:''; position:absolute; top:8px; bottom:8px; width:10px;
    background:linear-gradient(180deg, rgba(255,46,151,.35), rgba(0,229,255,.28));
    opacity:.5;
  }
  .cabinet::before{ left:-14px; border-radius:4px 0 0 4px; }
  .cabinet::after { right:-14px; border-radius:0 4px 4px 0; }

  /* Marquee */
  .marquee{
    position:relative;
    padding:22px 20px 18px;
    border-bottom:2px solid rgba(0,229,255,.35);
    border-radius:9px 9px 0 0;
    overflow:hidden;
    background:
      radial-gradient(ellipse 70% 120% at 50% 0%, rgba(255,46,151,.28), transparent 70%),
      linear-gradient(180deg, rgba(138,92,255,.18), transparent);
  }
  .marquee::after{
    content:''; position:absolute; inset:0;
    background:linear-gradient(110deg, transparent 35%, rgba(255,255,255,.14) 50%, transparent 65%);
    transform:translateX(-120%);
    animation:marqueeSheen 5.5s ease-in-out infinite;
    pointer-events:none;
  }
  @keyframes marqueeSheen{
    0%,60%{ transform:translateX(-120%); }
    85%,100%{ transform:translateX(120%); }
  }
  .bulbs{
    position:absolute; left:12px; right:12px; top:7px;
    display:flex; justify-content:space-between; pointer-events:none;
  }
  .bulbs i{
    width:6px; height:6px; border-radius:50%;
    background:var(--yellow);
    box-shadow:0 0 8px var(--yellow);
    animation:bulbChase 1.5s ease-in-out infinite;
    animation-delay:calc(var(--i) * .09s);
  }
  @keyframes bulbChase{
    0%,100%{ opacity:.22; transform:scale(.75); }
    50%    { opacity:1;   transform:scale(1.25); }
  }
  .marquee-inner{
    position:relative; z-index:2;
    display:flex; flex-direction:column; align-items:center; gap:9px;
    text-align:center;
  }
  .logo-img{
    height:60px;
    max-width:100%;
    object-fit:contain;
    filter:drop-shadow(0 0 10px rgba(0,229,255,.5));
    animation:neonFlicker 7s infinite;
  }
  @keyframes neonFlicker{
    0%,18%,20%,50%,52%,100%{ opacity:1; }
    19%{ opacity:.42; }
    51%{ opacity:.6; }
  }
  .tagline{
    font-family:var(--pixel);
    font-size:8px;
    letter-spacing:2px;
    color:var(--ink-dim);
    text-transform:uppercase;
  }

  /* Top bar */
  .topbar{
    display:flex; align-items:center; justify-content:space-between;
    gap:12px; padding:14px 18px 0;
  }
  .topbar-right{ display:flex; align-items:center; gap:9px; }
  .tab{
    display:inline-flex; align-items:center; gap:10px;
    background:linear-gradient(180deg, var(--magenta), #b8005f);
    color:#fff;
    font-family:var(--pixel);
    font-size:10px;
    letter-spacing:.5px;
    text-transform:uppercase;
    padding:10px 16px;
    border:2px solid #fff;
    border-radius:3px;
    box-shadow:0 0 0 2px var(--bg), 0 0 0 4px rgba(255,46,151,.5), 0 0 22px rgba(255,46,151,.5);
  }
  .tab svg{ width:16px; height:16px; flex-shrink:0; }
  .chip{
    font-family:var(--pixel); font-size:8px; letter-spacing:1px;
    color:var(--yellow);
    border:2px solid rgba(255,230,0,.45);
    background:rgba(255,230,0,.08);
    padding:9px 10px;
    border-radius:3px;
    text-transform:uppercase;
    text-shadow:0 0 10px rgba(255,230,0,.7);
  }
  .sound-btn{
    font-family:var(--pixel);
    font-size:8px;
    letter-spacing:.5px;
    color:var(--cyan);
    background:transparent;
    border:2px solid rgba(0,229,255,.5);
    border-radius:3px;
    padding:9px 11px;
    cursor:pointer;
    display:inline-flex; align-items:center; gap:8px;
    transition:background .18s, box-shadow .18s, color .18s, border-color .18s;
  }
  .sound-btn:hover{ background:rgba(0,229,255,.12); box-shadow:0 0 18px rgba(0,229,255,.5); }
  .sound-btn[aria-pressed="false"]{
    color:var(--ink-dim); border-color:rgba(143,163,200,.4); box-shadow:none;
  }
  .eq{ display:inline-flex; align-items:flex-end; gap:2px; height:10px; }
  .eq i{
    width:2px; background:currentColor; height:3px;
    animation:eqBounce .9s ease-in-out infinite;
  }
  .eq i:nth-child(2){ animation-delay:.15s; }
  .eq i:nth-child(3){ animation-delay:.3s; }
  .sound-btn[aria-pressed="false"] .eq i{ animation-play-state:paused; height:3px; }
  @keyframes eqBounce{
    0%,100%{ height:3px; }
    50%    { height:10px; }
  }

  /* Card */
  .card{
    position:relative;
    margin:16px 14px 0;
    background:
      linear-gradient(180deg, rgba(0,229,255,.07), rgba(0,229,255,0) 190px),
      var(--panel);
    border:2px solid rgba(0,229,255,.5);
    border-radius:6px;
    padding:26px 24px 30px;
    box-shadow:
      0 0 0 2px var(--bg),
      0 0 0 5px rgba(0,229,255,.16),
      0 0 52px rgba(0,229,255,.18),
      inset 0 0 90px rgba(0,229,255,.05);
    overflow:hidden;
  }
  .card::before, .card::after{
    content:''; position:absolute; width:24px; height:24px; pointer-events:none; z-index:4;
  }
  .card::before{ top:7px; left:7px; border-top:3px solid var(--magenta); border-left:3px solid var(--magenta); }
  .card::after{ bottom:7px; right:7px; border-bottom:3px solid var(--magenta); border-right:3px solid var(--magenta); }
  .card-sheen{
    position:absolute; inset:0; z-index:1; pointer-events:none;
    background:linear-gradient(115deg, transparent 40%, rgba(0,229,255,.09) 50%, transparent 60%);
    transform:translateX(-130%);
    animation:cardSheen 8s ease-in-out infinite;
  }
  @keyframes cardSheen{
    0%,55%{ transform:translateX(-130%); }
    90%,100%{ transform:translateX(130%); }
  }
  .glitch-flash{
    position:absolute; inset:0; z-index:6; pointer-events:none; opacity:0;
    background:linear-gradient(100deg, rgba(0,229,255,.55), rgba(255,46,151,.55));
    mix-blend-mode:screen;
  }
  .wipe{
    position:absolute; left:0; right:0; height:45%; top:0; z-index:7;
    pointer-events:none; opacity:0;
    background:linear-gradient(180deg, transparent, rgba(0,229,255,.85), rgba(255,46,151,.6), transparent);
    mix-blend-mode:screen;
  }

  /* Card header */
  .card-head{
    position:relative; z-index:3;
    display:flex; align-items:center; gap:14px;
    padding-bottom:18px;
    margin-bottom:18px;
    border-bottom:2px dashed rgba(0,229,255,.2);
  }
  .avatar{
    position:relative;
    width:58px; height:58px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size:26px;
    background:radial-gradient(circle at 50% 35%, rgba(0,229,255,.28), rgba(138,92,255,.14));
    border:2px solid rgba(0,229,255,.55);
    border-radius:4px;
    animation:avatarBob 3.6s ease-in-out infinite;
  }
  @keyframes avatarBob{
    0%,100%{ transform:translateY(0); }
    50%    { transform:translateY(-5px); }
  }
  .avatar .ring{
    position:absolute; inset:-9px;
    border:2px solid transparent;
    border-top-color:var(--cyan);
    border-right-color:rgba(0,229,255,.25);
    border-radius:50%;
    animation:spin 4.5s linear infinite;
  }
  .avatar .ring.r2{
    inset:-15px;
    border-top-color:var(--magenta);
    border-right-color:transparent;
    animation-duration:7s;
    animation-direction:reverse;
  }
  @keyframes spin{ to{ transform:rotate(360deg); } }
  .who{ flex:1; min-width:0; }
  .who-name{
    font-family:var(--pixel); font-size:12px; letter-spacing:.5px;
    color:#fff; margin-bottom:8px;
    text-shadow:0 0 12px rgba(0,229,255,.6);
  }
  .who-sub{
    display:inline-flex; align-items:center; gap:7px;
    font-size:18px; color:var(--ink-dim);
  }
  .live-dot{
    width:8px; height:8px; border-radius:50%;
    background:var(--green); box-shadow:0 0 10px var(--green);
    animation:livePulse 1.6s ease-in-out infinite;
  }
  @keyframes livePulse{
    0%,100%{ opacity:1; transform:scale(1); }
    50%    { opacity:.35; transform:scale(.7); }
  }
  .mini-hud{ display:flex; gap:8px; }
  .mini{
    display:flex; flex-direction:column; align-items:center; gap:5px;
    background:rgba(0,229,255,.07);
    border:1px solid rgba(0,229,255,.3);
    border-radius:3px;
    padding:8px 10px;
    min-width:62px;
  }
  .mini .k{ font-family:var(--pixel); font-size:7px; color:var(--ink-dim); letter-spacing:1px; }
  .mini .v{ font-family:var(--pixel); font-size:10px; color:var(--cyan); text-shadow:0 0 10px rgba(0,229,255,.8); }

  /* Progress dots */
  .dots{
    position:relative; z-index:3;
    display:flex; gap:12px; justify-content:center; margin-bottom:26px;
    transition:opacity .3s ease;
  }
  .dots span{
    width:14px; height:14px;
    background:rgba(0,229,255,.08);
    border:1px solid rgba(0,229,255,.35);
    border-radius:3px;
    transition:background .25s, box-shadow .25s, transform .25s;
  }
  .dots span.done{ background:rgba(0,229,255,.45); }
  .dots span.current{
    background:var(--cyan);
    box-shadow:0 0 16px var(--cyan);
    transform:scale(1.22) rotate(45deg);
  }

  /* Screens */
  .screens{ position:relative; z-index:3; min-height:430px; }
  .screen{ display:none; }
  .screen.is-active{ display:block; }

  /* Typography */
  .title{
    font-family:var(--pixel);
    font-size:clamp(12px, 3.2vw, 16px);
    line-height:1.75;
    letter-spacing:.5px;
    margin:0 0 16px;
    color:#fff;
    text-transform:uppercase;
    text-shadow:0 0 14px rgba(0,229,255,.75), 3px 3px 0 rgba(255,46,151,.42);
  }
  .lede{
    margin:0 0 24px;
    font-size:21px;
    color:var(--ink-dim);
  }

  /* Quest grid */
  .quest-grid{ display:grid; gap:11px; margin-bottom:24px; }
  .quest{
    display:flex; align-items:center; gap:13px;
    background:linear-gradient(90deg, rgba(0,229,255,.09), rgba(138,92,255,.05));
    border:2px solid rgba(0,229,255,.22);
    border-left:4px solid var(--cyan);
    border-radius:4px;
    padding:12px 14px;
    transition:transform .18s, border-color .18s, box-shadow .18s;
  }
  .quest:hover{
    transform:translateX(5px);
    border-color:rgba(0,229,255,.55);
    box-shadow:0 0 24px rgba(0,229,255,.24);
  }
  .quest-icon{ font-size:24px; flex-shrink:0; }
  .quest-body{ flex:1; min-width:0; }
  .quest-name{
    font-family:var(--pixel); font-size:8px; letter-spacing:.5px;
    color:var(--ink); margin-bottom:9px; text-transform:uppercase;
  }
  .quest-bar{
    height:8px; background:rgba(0,229,255,.1);
    border:1px solid rgba(0,229,255,.3); border-radius:2px; overflow:hidden;
  }
  .quest-bar i{
    display:block; height:100%; width:0;
    background:linear-gradient(90deg, var(--cyan), var(--magenta));
    box-shadow:0 0 12px var(--cyan);
    transition:width 1.1s cubic-bezier(.2,.9,.3,1);
  }
  .quest-val{
    font-family:var(--pixel); font-size:9px; color:var(--yellow);
    text-shadow:0 0 10px rgba(255,230,0,.7); flex-shrink:0;
  }
  .coin-slot{
    display:flex; align-items:center; justify-content:center; gap:10px;
    font-family:var(--pixel); font-size:8px; letter-spacing:1.5px;
    color:var(--yellow);
    text-transform:uppercase;
    margin-bottom:22px;
    animation:blink 1.5s steps(1,end) infinite;
  }
  .coin-slot .coin{ font-size:16px; }
  @keyframes blink{ 0%,60%{ opacity:1; } 61%,100%{ opacity:.15; } }

  /* Feature grid */
  .feature-grid{
    display:grid; gap:13px; margin-bottom:26px;
    grid-template-columns:1fr;
  }
  .feature{
    position:relative;
    background:linear-gradient(180deg, rgba(0,229,255,.09), rgba(0,229,255,.02));
    border:2px solid rgba(0,229,255,.25);
    border-radius:5px;
    padding:18px 16px 17px;
    overflow:hidden;
    transition:transform .2s, border-color .2s, box-shadow .2s;
  }
  .feature::after{
    content:''; position:absolute; inset:0;
    background:linear-gradient(115deg, transparent 40%, rgba(255,255,255,.1) 50%, transparent 60%);
    transform:translateX(-130%);
    transition:transform .6s ease;
  }
  .feature:hover{
    transform:translateY(-4px);
    border-color:var(--cyan);
    box-shadow:0 14px 34px rgba(0,229,255,.2);
  }
  .feature:hover::after{ transform:translateX(130%); }
  .feature-icon{
    font-size:30px; margin-bottom:12px;
    filter:drop-shadow(0 0 12px rgba(0,229,255,.6));
  }
  .feature h2{
    font-family:var(--pixel); font-size:10px; letter-spacing:.5px;
    color:var(--yellow); margin:0 0 11px; text-transform:uppercase;
    text-shadow:0 0 12px rgba(255,230,0,.55);
  }
  .feature p{ margin:0; font-size:19px; color:var(--ink-dim); line-height:1.3; }
  .feature .num{
    position:absolute; top:10px; right:12px;
    font-family:var(--pixel); font-size:20px;
    color:rgba(0,229,255,.14);
  }

  /* Stat grid */
  .stat-grid{
    display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:20px;
  }
  .stat{
    position:relative;
    background:linear-gradient(180deg, rgba(0,229,255,.09), rgba(0,229,255,.02));
    border:2px solid rgba(0,229,255,.28);
    border-radius:5px;
    padding:20px 12px 16px;
    text-align:center;
    overflow:hidden;
    transition:transform .2s, border-color .2s, box-shadow .2s;
  }
  .stat:hover{
    transform:translateY(-4px);
    border-color:var(--cyan);
    box-shadow:0 14px 32px rgba(0,229,255,.2);
  }
  .stat::before{
    content:''; position:absolute; top:0; left:0; right:0; height:3px;
    background:linear-gradient(90deg, var(--cyan), var(--magenta));
    opacity:.8;
  }
  .stat-value{
    font-family:var(--pixel);
    font-size:clamp(15px, 4vw, 20px);
    line-height:1.35;
    color:var(--cyan);
    text-shadow:0 0 18px rgba(0,229,255,.85);
    margin-bottom:12px;
  }
  .stat:nth-child(2) .stat-value{ color:var(--yellow); text-shadow:0 0 18px rgba(255,230,0,.8); }
  .stat:nth-child(3) .stat-value{ color:var(--magenta); text-shadow:0 0 18px rgba(255,46,151,.8); }
  .stat:nth-child(4) .stat-value{ color:var(--green); text-shadow:0 0 18px rgba(57,255,20,.8); }
  .stat-label{
    font-family:var(--pixel); font-size:7px; letter-spacing:1px;
    color:var(--ink-dim); text-transform:uppercase;
  }

  /* Quiz */
  .chip-row{ display:flex; flex-wrap:wrap; gap:9px; margin-bottom:18px; }
  .streak-chip, .combo-chip{
    display:inline-flex; align-items:center; gap:8px;
    font-family:var(--pixel);
    font-size:9px;
    letter-spacing:.5px;
    padding:10px 13px;
    border-radius:3px;
    text-transform:uppercase;
  }
  .streak-chip{
    color:var(--yellow);
    background:rgba(255,230,0,.1);
    border:1px solid rgba(255,230,0,.5);
    text-shadow:0 0 10px rgba(255,230,0,.8);
  }
  .combo-chip{
    color:var(--magenta);
    background:rgba(255,46,151,.1);
    border:1px solid rgba(255,46,151,.5);
    text-shadow:0 0 10px rgba(255,46,151,.8);
    opacity:.35;
    transition:opacity .25s, transform .25s, box-shadow .25s;
  }
  .combo-chip.hot{
    opacity:1;
    box-shadow:0 0 22px rgba(255,46,151,.6);
    animation:comboPulse .6s ease-out;
  }
  @keyframes comboPulse{
    0%{ transform:scale(1); }
    45%{ transform:scale(1.18); }
    100%{ transform:scale(1); }
  }
  .progress-row{ display:flex; align-items:center; gap:12px; margin-bottom:24px; }
  .progress-track{
    flex:1; height:14px;
    background:rgba(0,229,255,.08);
    border:1px solid rgba(0,229,255,.35);
    border-radius:3px;
    overflow:hidden;
  }
  .progress-fill{
    height:100%; width:0%;
    background:linear-gradient(90deg, var(--cyan), var(--magenta));
    box-shadow:0 0 16px var(--cyan);
    transition:width .42s cubic-bezier(.2,.9,.3,1);
    position:relative; overflow:hidden;
  }
  .progress-fill::after{
    content:''; position:absolute; inset:0;
    background:linear-gradient(90deg, transparent, rgba(255,255,255,.4), transparent);
    transform:translateX(-100%);
    animation:shimmer 1.8s linear infinite;
  }
  @keyframes shimmer{ to{ transform:translateX(100%); } }
  .progress-label{
    font-family:var(--pixel);
    font-size:8px;
    color:var(--ink-dim);
    white-space:nowrap;
  }
  .q-card{
    position:relative;
    background:linear-gradient(180deg, rgba(255,230,0,.07), rgba(255,230,0,.01));
    border:2px solid rgba(255,230,0,.28);
    border-left:4px solid var(--yellow);
    border-radius:5px;
    padding:20px 18px 18px;
    margin-bottom:20px;
    overflow:hidden;
  }
  .q-card::after{
    content:''; position:absolute; left:0; right:0; top:0; height:2px;
    background:linear-gradient(90deg, transparent, var(--yellow), transparent);
    animation:scanLine 2.6s ease-in-out infinite;
    opacity:.7;
  }
  @keyframes scanLine{
    0%,100%{ transform:translateY(0); opacity:.15; }
    50%    { transform:translateY(70px); opacity:.8; }
  }
  .q-tag{
    display:inline-block;
    font-family:var(--pixel); font-size:7px; letter-spacing:1.5px;
    color:var(--yellow);
    border:1px solid rgba(255,230,0,.45);
    background:rgba(255,230,0,.1);
    padding:5px 8px;
    border-radius:2px;
    margin-bottom:14px;
    text-transform:uppercase;
  }
  .question{
    font-family:var(--body);
    font-size:clamp(21px, 4.6vw, 26px);
    line-height:1.28;
    color:var(--yellow);
    text-shadow:0 0 16px rgba(255,230,0,.55);
    margin:0;
  }
  .options{ display:flex; flex-direction:column; gap:11px; margin-bottom:4px; }
  .opt{
    position:relative;
    display:flex; align-items:center; gap:14px;
    width:100%;
    text-align:left;
    font-family:var(--body);
    font-size:22px;
    color:var(--ink);
    background:rgba(0,229,255,.05);
    border:2px solid rgba(0,229,255,.28);
    border-radius:4px;
    padding:13px 15px;
    cursor:pointer;
    overflow:hidden;
    transition:background .15s, border-color .15s, box-shadow .2s, color .15s, transform .12s;
  }
  .opt .key{
    font-family:var(--pixel);
    font-size:10px;
    color:var(--cyan);
    border:1px solid rgba(0,229,255,.5);
    border-radius:2px;
    padding:6px 9px;
    flex-shrink:0;
    transition:color .15s, border-color .15s, background .15s;
  }
  .opt:hover:not(:disabled){
    border-color:var(--cyan);
    background:rgba(0,229,255,.14);
    box-shadow:0 0 22px rgba(0,229,255,.3);
    transform:translateX(5px);
  }
  .opt:disabled{ cursor:default; }
  .opt.correct{
    border-color:var(--green);
    background:rgba(57,255,20,.14);
    color:var(--green);
    box-shadow:0 0 24px rgba(57,255,20,.45);
  }
  .opt.correct .key{ color:var(--green); border-color:var(--green); background:rgba(57,255,20,.14); }
  .opt.incorrect{
    border-color:var(--red);
    background:rgba(255,77,77,.14);
    color:var(--red);
    box-shadow:0 0 24px rgba(255,77,77,.4);
  }
  .opt.incorrect .key{ color:var(--red); border-color:var(--red); background:rgba(255,77,77,.14); }
  .feedback{
    display:none;
    font-size:21px;
    padding:13px 16px;
    margin:16px 0 18px;
    border-left:4px solid;
    border-radius:0 4px 4px 0;
  }
  .feedback.show{ display:block; }
  .feedback.good{ color:var(--green); background:rgba(57,255,20,.1); border-color:var(--green); }
  .feedback.bad { color:var(--red);   background:rgba(255,77,77,.1); border-color:var(--red); }
  .next-btn{ display:none; }
  .next-btn.show{ display:block; }

  /* Result */
  .result-top{
    display:flex; align-items:center; gap:18px;
    margin-bottom:22px;
  }
  .flame-wrap{
    width:92px; height:92px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size:44px;
    border-radius:6px;
    background:rgba(255,230,0,.12);
    border:2px solid rgba(255,230,0,.6);
    box-shadow:0 0 32px rgba(255,230,0,.42), inset 0 0 26px rgba(255,230,0,.16);
  }
  .flame-wrap.calm{
    background:rgba(0,229,255,.1);
    border-color:rgba(0,229,255,.55);
    box-shadow:0 0 32px rgba(0,229,255,.35), inset 0 0 26px rgba(0,229,255,.12);
  }
  .streak-count{
    font-family:var(--pixel);
    font-size:clamp(15px, 4vw, 21px);
    line-height:1.55;
    color:var(--yellow);
    text-shadow:0 0 20px rgba(255,230,0,.85), 3px 3px 0 rgba(255,46,151,.45);
    margin:0 0 12px;
  }
  .streak-caption{ font-size:19px; color:var(--ink-dim); margin:0; }
  .score-note{
    font-size:20px;
    color:var(--ink);
    background:rgba(0,229,255,.07);
    border-left:3px solid var(--cyan);
    border-radius:0 4px 4px 0;
    padding:12px 15px;
    margin:0 0 18px;
  }
  .result-stats{
    display:grid; grid-template-columns:repeat(3,1fr); gap:10px;
    margin-bottom:18px;
  }
  .rs{
    background:rgba(0,229,255,.06);
    border:2px solid rgba(0,229,255,.24);
    border-radius:4px;
    padding:15px 8px;
    text-align:center;
  }
  .rs-v{
    font-family:var(--pixel); font-size:11px;
    color:var(--cyan); text-shadow:0 0 14px rgba(0,229,255,.8);
    margin-bottom:10px; line-height:1.4;
  }
  .rs-l{ font-family:var(--pixel); font-size:7px; color:var(--ink-dim); letter-spacing:1px; text-transform:uppercase; }

  /* Buttons */
  .btn{
    position:relative;
    font-family:var(--pixel);
    font-size:11px;
    letter-spacing:.5px;
    text-transform:uppercase;
    width:100%;
    padding:17px 18px;
    border:2px solid;
    border-radius:4px;
    background:transparent;
    cursor:pointer;
    overflow:hidden;
    transition:transform .1s, box-shadow .2s, background .2s, color .2s;
  }
  .btn > span{ position:relative; z-index:2; }
  .btn::after{
    content:''; position:absolute; inset:0; z-index:1;
    background:linear-gradient(115deg, transparent 40%, rgba(255,255,255,.45) 50%, transparent 60%);
    transform:translateX(-130%);
    transition:transform .55s ease;
  }
  .btn:hover::after{ transform:translateX(130%); }
  .btn.primary{
    color:#04121a;
    background:var(--cyan);
    border-color:#fff;
    box-shadow:0 0 0 2px var(--bg), 0 0 0 4px var(--cyan), 0 0 26px rgba(0,229,255,.55);
  }
  .btn.primary:hover{
    background:#fff;
    box-shadow:0 0 0 2px var(--bg), 0 0 0 4px #fff, 0 0 38px rgba(0,229,255,.9);
  }
  .btn.primary:active{ transform:translateY(2px); }

  /* Control deck */
  .deck{
    display:flex; align-items:center; justify-content:space-between;
    gap:16px;
    margin:14px 14px 0;
    padding:14px 20px;
    background:linear-gradient(180deg, rgba(0,229,255,.06), rgba(0,0,0,.25));
    border:2px solid rgba(0,229,255,.2);
    border-radius:5px;
  }
  .stick{
    position:relative; width:44px; height:44px; flex-shrink:0;
    border-radius:50%;
    background:radial-gradient(circle at 35% 30%, #2a2450, #100e28);
    border:2px solid rgba(0,229,255,.4);
    display:flex; align-items:center; justify-content:center;
  }
  .stick span{
    width:16px; height:16px; border-radius:50%;
    background:linear-gradient(180deg, var(--magenta), #8c0050);
    box-shadow:0 0 16px rgba(255,46,151,.8);
    animation:stickWobble 3.2s ease-in-out infinite;
  }
  @keyframes stickWobble{
    0%,100%{ transform:translate(0,0); }
    25%    { transform:translate(4px,-3px); }
    50%    { transform:translate(0,4px); }
    75%    { transform:translate(-4px,-2px); }
  }
  .deck-btns{ display:flex; gap:10px; }
  .dbtn{
    width:26px; height:26px; border-radius:50%;
    border:2px solid rgba(255,255,255,.35);
    animation:dbtnGlow 2.4s ease-in-out infinite;
  }
  .dbtn.d1{ background:radial-gradient(circle at 35% 30%, #ff7ab8, #b8005f); animation-delay:0s; }
  .dbtn.d2{ background:radial-gradient(circle at 35% 30%, #7ef1ff, #0088a8); animation-delay:.4s; }
  .dbtn.d3{ background:radial-gradient(circle at 35% 30%, #ffe97a, #b89500); animation-delay:.8s; }
  @keyframes dbtnGlow{
    0%,100%{ box-shadow:0 0 6px rgba(255,255,255,.2); transform:translateY(0); }
    50%    { box-shadow:0 0 18px rgba(255,255,255,.6); transform:translateY(-2px); }
  }
  .vents{ display:flex; gap:5px; }
  .vents i{
    width:4px; height:26px; border-radius:2px;
    background:rgba(0,229,255,.18);
  }

  /* Responsive */
  @media (min-width:560px){
    .feature-grid{ grid-template-columns:repeat(3,1fr); }
  }
  @media (max-width:480px){
    body{ padding:18px 10px 30px; font-size:19px; }
    .card{ margin:12px 8px 0; padding:20px 15px 22px; }
    .cabinet::before, .cabinet::after{ display:none; }
    .card-head{ flex-wrap:wrap; }
    .mini-hud{ width:100%; }
    .mini{ flex:1; }
    .stat-grid{ grid-template-columns:1fr 1fr; }
    .result-top{ flex-direction:column; align-items:flex-start; }
    .result-stats{ grid-template-columns:1fr; }
    .deck{ margin:12px 8px 0; padding:12px 14px; }
    .q-card{ padding:16px 14px; }
  }

  /* Floating math symbols */
  #math-float{ position:fixed; inset:0; pointer-events:none; z-index:0; overflow:hidden; }
  .math-symbol{
    position:absolute; font-family:'Press Start 2P', monospace; font-weight:700;
    opacity:.12; animation:floatUp linear infinite;
  }
  @keyframes floatUp{
    0%  { transform:translateY(110vh); opacity:0; }
    10% { opacity:.18; }
    90% { opacity:.18; }
    100%{ transform:translateY(-15vh); opacity:0; }
  }
</style>
</head>

<body>

<div id="math-float" aria-hidden="true"></div>
<div class="bg-layer" aria-hidden="true"></div>
<div class="orbs" aria-hidden="true"><i></i><i></i><i></i></div>
<canvas id="fx" aria-hidden="true"></canvas>
<div class="scanlines" aria-hidden="true"></div>

<main class="stage">
  <div class="cabinet" id="cabinet">
    <!-- MARQUEE -->
    <div class="marquee">
      <div class="bulbs" id="bulbs" aria-hidden="true"></div>
      <div class="marquee-inner">
        <img src="{{ asset('images/logo.png') }}" alt="EduPlay Logo" class="logo-img">
        <div class="tagline">Insert coin to learn</div>
      </div>
    </div>

    <!-- TOP BAR -->
    <div class="topbar">
      <div class="tab">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M12 3l9 5-9 5-9-5 9-5z" stroke="white" stroke-width="1.6" stroke-linejoin="round"/>
          <path d="M6 11.5V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-4.5" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>EduPlay</span>
      </div>
      <div class="topbar-right">
        <div class="chip">GR <span id="levelChip">{{ $user->grade_level ?? 4 }}</span></div>
        <button class="sound-btn" id="soundBtn" type="button" aria-pressed="true">
          <span class="eq" aria-hidden="true"><i></i><i></i><i></i></span>
          <span id="soundLabel">SFX ON</span>
        </button>
      </div>
    </div>

    <!-- CARD -->
    <div class="card" id="card">
      <div class="card-sheen" aria-hidden="true"></div>
      <div class="glitch-flash" id="glitchFlash" aria-hidden="true"></div>
      <div class="wipe" id="wipe" aria-hidden="true"></div>

      <!-- Card header -->
      <div class="card-head">
        <div class="avatar" aria-hidden="true">
          <span class="ring"></span>
          <span class="ring r2"></span>
          <span class="face">{{ $user->getCharacterEmoji() }}</span>
        </div>
        <div class="who">
          <div class="who-name" id="whoName">{{ $user->name }}</div>
          <div class="who-sub">
            <span class="live-dot" aria-hidden="true"></span>
            <span id="whoSub">Online · ready to play</span>
          </div>
        </div>
        <div class="mini-hud">
          <div class="mini"><span class="k">PTS</span><span class="v" id="miniPoints">{{ number_format($totalScore) }}</span></div>
          <div class="mini"><span class="k">STREAK</span><span class="v" id="miniStreak">{{ $streak }}</span></div>
        </div>
      </div>

      <!-- Progress dots -->
      <div class="dots" id="onboardDots" aria-hidden="true">
        <span></span><span></span><span></span>
      </div>

      <!-- Screens -->
      <div class="screens" id="screens">

        <!-- 1 · GREETING -->
        <section class="screen is-active" id="screen-greet">
          <h1 class="title" id="greeting">Welcome back!</h1>
          <p class="lede">Glad to see you today — let's get you set up before you dive in.</p>

          <div class="quest-grid">
            <div class="quest">
              <div class="quest-icon" aria-hidden="true">🎯</div>
              <div class="quest-body">
                <div class="quest-name">Daily Warm-Up</div>
                <div class="quest-bar"><i data-fill="{{ $icebreakerDone ? '100%' : '0%' }}"></i></div>
              </div>
              <div class="quest-val">{{ $icebreakerDone ? '4' : '0' }}/4</div>
            </div>
            <div class="quest">
              <div class="quest-icon" aria-hidden="true">🔥</div>
              <div class="quest-body">
                <div class="quest-name">Keep Streak Alive</div>
                <div class="quest-bar"><i data-fill="{{ $streak > 0 ? min(100, ($streak / 7) * 100) : 0 }}%"></i></div>
              </div>
              <div class="quest-val">{{ $streak }}/7</div>
            </div>
            <div class="quest">
              <div class="quest-icon" aria-hidden="true">⚡</div>
              <div class="quest-body">
                <div class="quest-name">Total Progress</div>
                <div class="quest-bar"><i data-fill="{{ $totalLevels > 0 ? min(100, ($totalLevels / $totalPossibleLevels) * 100) : 0 }}%"></i></div>
              </div>
              <div class="quest-val">{{ $totalLevels }}/{{ $totalPossibleLevels }}</div>
            </div>
          </div>

          <div class="coin-slot"><span class="coin" aria-hidden="true">🪙</span> Insert coin — press continue</div>
          <button class="btn primary" id="toSummaryBtn" type="button"><span>Continue</span></button>
        </section>

        <!-- 2 · HOW TO PLAY -->
        <section class="screen" id="screen-summary">
          <h1 class="title">How To Play</h1>
          <p class="lede">A quick reminder of how your day works here.</p>

          <div class="feature-grid">
            <article class="feature">
              <span class="num" aria-hidden="true">01</span>
              <div class="feature-icon" aria-hidden="true">🗺️</div>
              <h2>Game Maps</h2>
              <p>Navigate through levels on colorful, themed game maps.</p>
            </article>
            <article class="feature">
              <span class="num" aria-hidden="true">02</span>
              <div class="feature-icon" aria-hidden="true">🔥</div>
              <h2>Streaks</h2>
              <p>Your streak grows every single day you show up.</p>
            </article>
            <article class="feature">
              <span class="num" aria-hidden="true">03</span>
              <div class="feature-icon" aria-hidden="true">⭐</div>
              <h2>Points</h2>
              <p>Points add up as you complete levels and answer correctly.</p>
            </article>
          </div>

          <button class="btn primary" id="toStatusBtn" type="button"><span>Continue</span></button>
        </section>

        <!-- 3 · PLAYER STATUS -->
        <section class="screen" id="screen-status">
          <h1 class="title">Player Status</h1>
          <p class="lede">Here's where you stand right now.</p>

          <div class="stat-grid">
            <div class="stat">
              <div class="stat-value" id="pointsValue">{{ number_format($totalScore) }}</div>
              <div class="stat-label">Points</div>
            </div>
            <div class="stat">
              <div class="stat-value" id="statusStreakValue">{{ $streak }}</div>
              <div class="stat-label">Day Streak</div>
            </div>
            <div class="stat">
              <div class="stat-value">{{ $user->grade_level ?? 4 }}</div>
              <div class="stat-label">Grade Level</div>
            </div>
            <div class="stat">
              <div class="stat-value">{{ $totalLevels }}</div>
              <div class="stat-label">Total Levels</div>
            </div>
          </div>

          @if($icebreakerDone)
            <button class="btn primary" id="startBtn" type="button"><span>Continue To Dashboard</span></button>
          @else
            <button class="btn primary" id="startBtn" type="button"><span>Start Warm-Up</span></button>
          @endif
        </section>

        @if(!$icebreakerDone)
        <!-- 4 · ICEBREAKER QUIZ -->
        <section class="screen" id="screen-quiz">
          <div class="chip-row">
            <div class="streak-chip">🔥 <span id="currentStreakLabel">{{ $streak }}</span>-day streak on the line</div>
            <div class="combo-chip" id="comboChip">⚡ Combo ×<span id="comboValue">0</span></div>
          </div>

          <div class="progress-row">
            <div class="progress-track"><div class="progress-fill" id="progressFill"></div></div>
            <div class="progress-label" id="progressLabel">Q1/4</div>
          </div>

          <div class="q-card">
            <div class="q-tag">Warm-Up Question</div>
            <div class="question" id="questionText">Loading…</div>
          </div>

          <div class="options" id="optionsWrap" data-stagger-skip></div>
          <div class="feedback" id="feedback" data-stagger-skip></div>
          <button class="btn primary next-btn" id="nextBtn" type="button" data-stagger-skip><span>Next</span></button>
        </section>

        <!-- 5 · STREAK RESULT -->
        <section class="screen" id="screen-result">
          <div class="result-top">
            <div class="flame-wrap" id="flameWrap">🔥</div>
            <div>
              <div class="streak-count"><span id="streakNumber">{{ $streak + 1 }}</span>-day streak</div>
              <div class="streak-caption" id="streakCaption">Recorded for today — nice consistency.</div>
            </div>
          </div>

          <div class="score-note" id="scoreNote">You got 0 of 4 right on the warm-up.</div>

          <div class="result-stats">
            <div class="rs">
              <div class="rs-v" id="rsCorrect">0/4</div>
              <div class="rs-l">Correct</div>
            </div>
            <div class="rs">
              <div class="rs-v" id="rsAcc">0%</div>
              <div class="rs-l">Accuracy</div>
            </div>
            <div class="rs">
              <div class="rs-v" id="rsXp">+50</div>
              <div class="rs-l">Persistence</div>
            </div>
          </div>

          <button class="btn primary" id="continueBtn" type="button"><span>Continue To Dashboard</span></button>
        </section>
        @endif

      </div>
    </div>

    <!-- CONTROL DECK -->
    <div class="deck" aria-hidden="true">
      <div class="stick"><span></span></div>
      <div class="deck-btns">
        <div class="dbtn d1"></div>
        <div class="dbtn d2"></div>
        <div class="dbtn d3"></div>
      </div>
      <div class="vents"><i></i><i></i><i></i><i></i><i></i></div>
    </div>
  </div>
</main>

<script>
(() => {
  'use strict';

  const STUDENT_NAME = '{{ $user->name }}';
  const CURRENT_STREAK = {{ $streak }};
  const PASS_MARK = 2;
  const ICEBREAKER_DONE = {{ $icebreakerDone ? 'true' : 'false' }};

  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const dur = (ms) => (prefersReduced ? 1 : ms);

  /* DOM refs — all declared upfront regardless of path */
  const $ = (sel) => document.querySelector(sel);
  const cabinet      = $('#cabinet');
  const glitchFlash  = $('#glitchFlash');
  const wipeEl       = $('#wipe');
  const dotsEl       = $('#onboardDots');
  const dotEls       = [...dotsEl.children];
  const soundBtn     = $('#soundBtn');
  const soundLabel   = $('#soundLabel');
  const bulbsEl      = $('#bulbs');
  const currentStreakLabel = $('#currentStreakLabel');
  const progressFill = $('#progressFill');
  const progressLabel = $('#progressLabel');
  const questionText = $('#questionText');
  const optionsWrap = $('#optionsWrap');
  const feedback = $('#feedback');
  const nextBtn = $('#nextBtn');
  const comboChip = $('#comboChip');
  const comboValue = $('#comboValue');
  const flameWrap = $('#flameWrap');
  const streakNumber = $('#streakNumber');
  const streakCaption = $('#streakCaption');
  const scoreNote = $('#scoreNote');
  const rsCorrect = $('#rsCorrect');
  const rsAcc = $('#rsAcc');
  const rsXp = $('#rsXp');

  /* Animation helpers */
  function staggerIn(container, baseDelay = 0, step = 60) {
    if (prefersReduced || !container) return;
    [...container.children]
      .filter((el) => !el.hasAttribute('data-stagger-skip'))
      .forEach((child, i) => {
        child.animate(
          [
            { opacity: 0, transform: 'translateY(18px)' },
            { opacity: 1, transform: 'translateY(0)' }
          ],
          {
            duration: 380,
            delay: baseDelay + i * step,
            easing: 'cubic-bezier(.2,.9,.3,1.1)',
            fill: 'backwards'
          }
        );
      });
  }

  function glitchSweep() {
    if (prefersReduced) return;
    glitchFlash.animate(
      [
        { opacity: 0, transform: 'translateX(0)' },
        { opacity: .55, transform: 'translateX(-5px)', offset: .15 },
        { opacity: .22, transform: 'translateX(5px)',  offset: .42 },
        { opacity: 0, transform: 'translateX(0)' }
      ],
      { duration: 340, easing: 'ease-out' }
    );
  }

  function screenWipe() {
    if (prefersReduced) return;
    wipeEl.animate(
      [
        { transform: 'translateY(-110%)', opacity: 0 },
        { transform: 'translateY(0%)',    opacity: .9, offset: .45 },
        { transform: 'translateY(110%)',  opacity: 0 }
      ],
      { duration: 560, easing: 'cubic-bezier(.2,.9,.3,1)' }
    );
  }

  function pop(el) {
    if (prefersReduced || !el) return;
    el.animate(
      [
        { transform: 'scale(1)' },
        { transform: 'scale(1.05)', offset: .4 },
        { transform: 'scale(1)' }
      ],
      { duration: 330, easing: 'ease-out' }
    );
  }

  function shake(el) {
    if (prefersReduced || !el) return;
    el.animate(
      [
        { transform: 'translateX(0)' },
        { transform: 'translateX(-10px)' },
        { transform: 'translateX(8px)' },
        { transform: 'translateX(-5px)' },
        { transform: 'translateX(3px)' },
        { transform: 'translateX(0)' }
      ],
      { duration: 400, easing: 'ease-out' }
    );
  }

  /* Confetti canvas */
  const FX = (() => {
    const canvas = document.getElementById('fx');
    const ctx = canvas.getContext('2d');
    let W = 0, H = 0, DPR = 1;
    let particles = [];
    let raf = null;

    function resize() {
      DPR = Math.min(2, window.devicePixelRatio || 1);
      W = window.innerWidth;
      H = window.innerHeight;
      canvas.width = Math.floor(W * DPR);
      canvas.height = Math.floor(H * DPR);
      canvas.style.width = W + 'px';
      canvas.style.height = H + 'px';
      ctx.setTransform(DPR, 0, 0, DPR, 0, 0);
    }

    function step() {
      ctx.clearRect(0, 0, W, H);
      for (let i = particles.length - 1; i >= 0; i--) {
        const p = particles[i];
        p.life++;
        p.vy += p.g;
        p.vx *= p.friction;
        p.vy *= p.friction;
        p.x += p.vx;
        p.y += p.vy;
        p.rot += p.vr;
        const t = p.life / p.lifeMax;
        if (t >= 1 || p.y > H + 60) { particles.splice(i, 1); continue; }
        ctx.save();
        ctx.globalAlpha = Math.max(0, 1 - t * t);
        ctx.translate(p.x, p.y);
        ctx.rotate(p.rot);
        ctx.fillStyle = p.color;
        ctx.shadowColor = p.color;
        ctx.shadowBlur = 10;
        ctx.fillRect(-p.s / 2, -p.h / 2, p.s, p.h);
        ctx.restore();
      }
      if (particles.length) {
        raf = requestAnimationFrame(step);
      } else {
        raf = null;
        ctx.clearRect(0, 0, W, H);
      }
    }

    function start() {
      if (!raf) raf = requestAnimationFrame(step);
    }

    function burst(x, y, colors, count = 26, power = 6) {
      if (prefersReduced) return;
      for (let i = 0; i < count; i++) {
        const a = (Math.PI * 2 * i) / count + Math.random() * .4;
        const sp = power * (.45 + Math.random() * .85);
        particles.push({
          x, y,
          vx: Math.cos(a) * sp,
          vy: Math.sin(a) * sp - 1.4,
          g: .16,
          friction: .965,
          s: 4 + Math.random() * 6,
          h: 4 + Math.random() * 6,
          rot: Math.random() * Math.PI,
          vr: (Math.random() - .5) * .35,
          life: 0,
          lifeMax: 48 + Math.random() * 34,
          color: colors[(Math.random() * colors.length) | 0]
        });
      }
      start();
    }

    function confetti(count = 110) {
      if (prefersReduced) return;
      const colors = ['#00E5FF', '#FF2E97', '#FFE600', '#39FF14', '#8A5CFF', '#ffffff'];
      for (let i = 0; i < count; i++) {
        particles.push({
          x: Math.random() * W,
          y: -30 - Math.random() * H * .5,
          vx: (Math.random() - .5) * 1.9,
          vy: 1.4 + Math.random() * 2.6,
          g: .045,
          friction: 1,
          s: 5 + Math.random() * 7,
          h: 9 + Math.random() * 11,
          rot: Math.random() * Math.PI,
          vr: (Math.random() - .5) * .3,
          life: 0,
          lifeMax: 150 + Math.random() * 100,
          color: colors[(Math.random() * colors.length) | 0]
        });
      }
      start();
    }

    window.addEventListener('resize', resize, { passive: true });
    resize();
    return { burst, confetti };
  })();

  function burstFromEl(el, colors, count, power) {
    if (!el) return;
    const r = el.getBoundingClientRect();
    FX.burst(r.left + r.width / 2, r.top + r.height / 2, colors, count, power);
  }

  /* Sound */
  const Sound = (() => {
    let ctx = null;
    let enabled = true;

    function ensure() {
      if (!ctx) {
        const AC = window.AudioContext || window.webkitAudioContext;
        if (!AC) return null;
        ctx = new AC();
      }
      if (ctx.state === 'suspended') ctx.resume();
      return ctx;
    }

    function blip(freq, length = .08, type = 'square', gain = .05) {
      if (!enabled) return;
      try {
        const ac = ensure();
        if (!ac) return;
        const osc = ac.createOscillator();
        const amp = ac.createGain();
        osc.type = type;
        osc.frequency.setValueAtTime(freq, ac.currentTime);
        amp.gain.setValueAtTime(gain, ac.currentTime);
        amp.gain.exponentialRampToValueAtTime(.0001, ac.currentTime + length);
        osc.connect(amp).connect(ac.destination);
        osc.start();
        osc.stop(ac.currentTime + length);
      } catch (_) { }
    }

    return {
      click:   () => blip(680, .05),
      correct: () => { blip(880, .08); setTimeout(() => blip(1320, .12), 80); },
      wrong:   () => blip(170, .18, 'sawtooth', .045),
      fanfare: () => [523, 659, 784, 1046, 1318].forEach((f, i) => setTimeout(() => blip(f, .1), i * 78)),
      toggle() {
        enabled = !enabled;
        if (enabled) blip(880, .08);
        return enabled;
      }
    };
  })();

  /* Screen navigation */
  const ONBOARD = ['screen-greet', 'screen-summary', 'screen-status'];
  let activeScreen = document.getElementById('screen-greet');
  let locked = false;

  function setDots(id) {
    const idx = ONBOARD.indexOf(id);
    dotsEl.style.opacity = idx === -1 ? '0' : '1';
    dotEls.forEach((dot, i) => {
      dot.classList.toggle('done', idx > -1 && i < idx);
      dot.classList.toggle('current', idx > -1 && i === idx);
    });
  }

  async function showScreen(id, dir = 1) {
    const next = document.getElementById(id);
    if (!next || next === activeScreen || locked) return;
    locked = true;
    const current = activeScreen;
    activeScreen = next;

    if (current && !prefersReduced) {
      const out = current.animate(
        [
          { opacity: 1, transform: 'translateX(0) scale(1)', filter: 'blur(0px)' },
          { opacity: 0, transform: `translateX(${-34 * dir}px) scale(.97)`, filter: 'blur(4px)' }
        ],
        { duration: dur(200), easing: 'ease-in', fill: 'forwards' }
      );
      await out.finished.catch(() => {});
      out.cancel();
    }

    if (current) current.classList.remove('is-active');
    next.classList.add('is-active');
    onEnter(id);
    glitchSweep();
    screenWipe();

    if (!prefersReduced) {
      const enter = next.animate(
        [
          { opacity: 0, transform: `translateX(${40 * dir}px) scale(.97)`, filter: 'blur(4px)' },
          { opacity: 1, transform: 'translateX(0) scale(1)', filter: 'blur(0px)' }
        ],
        { duration: dur(340), easing: 'cubic-bezier(.2,.9,.3,1.05)', fill: 'both' }
      );
      enter.finished.catch(() => {}).then(() => enter.cancel());
    }

    staggerIn(next, 120);
    setDots(id);
    locked = false;
  }

  function onEnter(id) {
    if (id === 'screen-greet') {
      animateQuestBars();
    } else if (id === 'screen-status') {
      [...document.querySelectorAll('#screen-status .stat')].forEach((el, i) => {
        if (prefersReduced) return;
        el.animate(
          [
            { opacity: 0, transform: 'translateY(20px) scale(.94)' },
            { opacity: 1, transform: 'translateY(0) scale(1)' }
          ],
          { duration: 420, delay: 120 + i * 80, easing: 'cubic-bezier(.2,.9,.3,1.1)', fill: 'backwards' }
        );
      });
    } else if (id === 'screen-quiz') {
      startQuiz();
    } else if (id === 'screen-result') {
      celebrate();
    }
  }

  function animateQuestBars() {
    const bars = document.querySelectorAll('#screen-greet .quest-bar i');
    bars.forEach((bar, i) => {
      bar.style.width = '0%';
      setTimeout(() => { bar.style.width = bar.dataset.fill || '0%'; }, 300 + i * 140);
    });
  }

  /* Icebreaker flow */
  let QUESTIONS = [];
  let qIndex = 0;
  let correctCount = 0;
  let answered = false;
  let finalStreak = 0;
  let combo = 0;

  function updateCombo() {
    comboValue.textContent = combo;
    comboChip.classList.toggle('hot', combo >= 2);
    if (combo >= 2 && !prefersReduced) {
      comboChip.animate(
        [
          { transform: 'scale(1)' },
          { transform: 'scale(1.16)' },
          { transform: 'scale(1)' }
        ],
        { duration: 460, easing: 'cubic-bezier(.2,1.2,.4,1)' }
      );
    }
  }

  async function startQuiz() {
    qIndex = 0;
    correctCount = 0;
    combo = 0;
    answered = false;
    updateCombo();

    // Fetch questions from server
    const res = await fetch('{{ route("student.welcome.questions") }}');
    QUESTIONS = await res.json();
    
    renderQuestion();
  }

  function renderQuestion() {
    const q = QUESTIONS[qIndex];
    const pf = $('#progressFill');
    const pl = $('#progressLabel');
    const qt = $('#questionText');
    const ow = $('#optionsWrap');
    const fb = $('#feedback');
    const nb = $('#nextBtn');
    answered = false;
    pl.textContent = `Q${qIndex + 1}/4`;
    pf.style.width = `${(qIndex / 4) * 100}%`;
    qt.textContent = q.text;
    ow.innerHTML = '';

    q.options.forEach((opt, i) => {
      const btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'opt';
      btn.innerHTML = `<span class="key">${'ABCD'[i]}</span><span class="txt"></span>`;
      btn.querySelector('.txt').textContent = opt;
      btn.addEventListener('click', () => handleAnswer(i, btn));
      ow.appendChild(btn);
    });

    fb.className = 'feedback';
    fb.textContent = '';
    nb.classList.remove('show');

    if (!prefersReduced) {
      [...ow.children].forEach((el, i) => {
        el.animate(
          [
            { opacity: 0, transform: 'translateX(-24px)' },
            { opacity: 1, transform: 'translateX(0)' }
          ],
          {
            duration: 300,
            delay: 200 + i * 75,
            easing: 'cubic-bezier(.2,.9,.3,1.1)',
            fill: 'backwards'
          }
        );
      });
    }
  }

  function handleAnswer(choiceIndex, btnEl) {
    if (answered) return;
    answered = true;

    const q = QUESTIONS[qIndex];
    const ow = $('#optionsWrap');
    const fb = $('#feedback');
    const nb = $('#nextBtn');
    const allOpts = [...ow.querySelectorAll('.opt')];
    allOpts.forEach((b) => { b.disabled = true; });

    const isCorrect = choiceIndex === q.correct;

    if (isCorrect) {
      correctCount++;
      combo++;
      btnEl.classList.add('correct');
      fb.textContent = 'Correct — nice work!';
      fb.className = 'feedback show good';
      pop(btnEl);
      burstFromEl(btnEl, ['#39FF14', '#00E5FF', '#FFE600', '#ffffff'], 26, 6);
      Sound.correct();
    } else {
      combo = 0;
      btnEl.classList.add('incorrect');
      allOpts[q.correct].classList.add('correct');
      fb.textContent = 'Not quite — the highlighted answer was correct.';
      fb.className = 'feedback show bad';
      shake(btnEl);
      burstFromEl(btnEl, ['#FF4D4D', '#FF2E97'], 16, 4);
      Sound.wrong();
    }

    updateCombo();
    $('#progressFill').style.width = `${((qIndex + 1) / 4) * 100}%`;

    const isLast = qIndex === 3;
    nb.querySelector('span').textContent = isLast ? 'See My Streak' : 'Next Question';
    nb.classList.add('show');

    if (!prefersReduced) {
      nb.animate(
        [
          { opacity: 0, transform: 'translateY(12px)' },
          { opacity: 1, transform: 'translateY(0)' }
        ],
        { duration: 280, easing: 'cubic-bezier(.2,.9,.3,1.1)', fill: 'backwards' }
      );
    }
  }

  async function finishIcebreaker() {
    // Submit results to server
    const res = await fetch('{{ route("student.welcome.submit") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        correct: correctCount,
        total: 4
      })
    });
    const data = await res.json();
    finalStreak = data.streak;

    const accuracy = Math.round((correctCount / 4) * 100);

    $('#streakNumber').textContent = finalStreak;
    if (currentStreakLabel) currentStreakLabel.textContent = finalStreak;
    $('#miniStreak') && ($('#miniStreak').textContent = finalStreak);

    $('#flameWrap').className = 'flame-wrap';
    $('#flameWrap').textContent = '🔥';

    $('#streakCaption').textContent = 'Recorded for today — nice consistency.';
    $('#scoreNote').textContent = `You got ${correctCount} of 4 right on the warm-up.`;
    $('#rsCorrect').textContent = `${correctCount}/4`;
    $('#rsAcc').textContent = `${accuracy}%`;
    $('#rsXp').textContent = correctCount >= PASS_MARK ? '+100' : '+50';

    showScreen('screen-result');
  }

  function celebrate() {
    const flameWrap = $('#flameWrap');
    if (!flameWrap) return;
    if (!prefersReduced) {
      const entrance = flameWrap.animate(
        [
          { transform: 'scale(0) rotate(-25deg)', opacity: 0 },
          { transform: 'scale(1.28) rotate(7deg)', opacity: 1, offset: .6 },
          { transform: 'scale(1) rotate(0deg)', opacity: 1 }
        ],
        { duration: 600, delay: 140, easing: 'cubic-bezier(.2,1.2,.4,1)', fill: 'backwards' }
      );
      entrance.finished.catch(() => {}).then(() => {
        flameWrap.animate(
          [
            { transform: 'scale(1)' },
            { transform: 'scale(1.08)' },
            { transform: 'scale(1)' }
          ],
          { duration: 1700, iterations: Infinity, easing: 'ease-in-out' }
        );
      });

      setTimeout(() => {
        FX.confetti(120);
        burstFromEl(flameWrap, ['#FFE600', '#FF2E97', '#00E5FF', '#39FF14'], 40, 8);
      }, 220);
    }

    Sound.fanfare();
  }

  /* Boot sequence */
  function buildBulbs() {
    const count = 14;
    const frag = document.createDocumentFragment();
    for (let i = 0; i < count; i++) {
      const b = document.createElement('i');
      b.style.setProperty('--i', i);
      frag.appendChild(b);
    }
    if (bulbsEl) bulbsEl.appendChild(frag);
  }

  function spawnMathSymbols() {
    const syms = [
      '0','1','2','3','4','5','6','7','8','9',
      '+','−','×','÷','=','≠','<','>','≤','≥',
      '%','²','³','√','π','∞','∑','Δ','θ','α',
      '?','!','#','★','▲','◆','●','■','♦','⬡',
      'a²','b²','c²','½','¼','¾','2x','3y'
    ];
    const colors = ['#00E5FF','#FF2E97','#FFE600','#39FF14','#8A5CFF','#FF8C00','#00FFAA'];
    const wrap = document.getElementById('math-float');
    if (!wrap) return;
    for (let i = 0; i < 80; i++) {
      const el = document.createElement('div');
      el.className = 'math-symbol';
      el.textContent = syms[Math.floor(Math.random() * syms.length)];
      const color = colors[Math.floor(Math.random() * colors.length)];
      el.style.color = color;
      el.style.textShadow = `0 0 8px ${color}, 0 0 18px ${color}88, 0 0 30px ${color}44`;
      el.style.left = Math.random() * 100 + '%';
      el.style.fontSize = (0.45 + Math.random() * 1.1) + 'rem';
      el.style.animationDelay = Math.random() * 18 + 's';
      el.style.animationDuration = (8 + Math.random() * 12) + 's';
      wrap.appendChild(el);
    }
  }

  function boot() {
    buildBulbs();
    spawnMathSymbols();
    if (prefersReduced) return;

    cabinet.animate(
      [
        { transform: 'scaleY(.004) scaleX(1.06)', opacity: .15, filter: 'brightness(3)' },
        { transform: 'scaleY(.03) scaleX(1)', opacity: 1, filter: 'brightness(2.4)', offset: .28 },
        { transform: 'scaleY(1) scaleX(1)', opacity: 1, filter: 'brightness(1)' }
      ],
      { duration: 680, easing: 'cubic-bezier(.2,.85,.25,1)' }
    );

    glitchFlash.animate(
      [
        { opacity: .85, transform: 'translateX(-7px)' },
        { opacity: .3,  transform: 'translateX(7px)', offset: .5 },
        { opacity: 0,   transform: 'translateX(0)' }
      ],
      { duration: 540, delay: 190, easing: 'ease-out' }
    );

    staggerIn(document.getElementById('screen-greet'), 440, 72);
    setTimeout(animateQuestBars, 700);
  }

  /* Wiring — all functions are defined above this point */
  $('#greeting').textContent = `Welcome back, ${STUDENT_NAME}!`;

  $('#toSummaryBtn').addEventListener('click', () => {
    Sound.click();
    showScreen('screen-summary', 1);
  });

  $('#toStatusBtn').addEventListener('click', () => {
    Sound.click();
    showScreen('screen-status', 1);
  });

  soundBtn.addEventListener('click', () => {
    const on = Sound.toggle();
    soundBtn.setAttribute('aria-pressed', String(on));
    soundLabel.textContent = on ? 'SFX ON' : 'SFX OFF';
  });

  if (ICEBREAKER_DONE) {
    // Quiz screens not in DOM — go straight to dashboard from status screen
    $('#startBtn').addEventListener('click', () => {
      Sound.click();
      window.location.href = '{{ route("student.dashboard") }}';
    });
  } else {
    // Quiz screens are in DOM — full icebreaker flow
    $('#startBtn').addEventListener('click', () => {
      Sound.click();
      $('#whoSub').textContent = 'Warm-up in progress…';
      showScreen('screen-quiz', 1);
    });

    $('#nextBtn').addEventListener('click', () => {
      Sound.click();
      if (qIndex === 3) {
        finishIcebreaker();
      } else {
        qIndex++;
        renderQuestion();
      }
    });

    $('#continueBtn').addEventListener('click', () => {
      Sound.click();
      window.location.href = '{{ route("student.dashboard") }}';
    });
  }

  setDots('screen-greet');
  boot();
})();
</script>

</body>
</html>
