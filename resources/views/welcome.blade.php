<!DOCTYPE html>
<html lang="en" id="html-root">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EduPlay — Math Learning Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@700;800;900&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --grid:       rgba(139,150,190,.065);
      --grid-major: rgba(139,150,190,.13);
      --accent:     #6172F3;
      --accent-2:   #8B5CF6;
      --chalk:      #F2C85B;
      --ink-3:      rgba(139,150,190,.7);
    }

    body {
      font-family: 'Inter', sans-serif;
      background: #07080D;
      color: #e2e8f0;
      overflow-x: hidden;
    }

    /* ══════════════════════════════════════════════════════════════
       MATH BACKGROUND — every layer below is position: fixed, so it
       stays put while the page scrolls over it.
       ══════════════════════════════════════════════════════════════ */

    /* ── Page-wide graph-paper grid ── */
    body::before {
      content: ''; position: fixed; z-index: 0; inset: 0; pointer-events: none;
      background-image:
        linear-gradient(var(--grid-major) 1px, transparent 1px),
        linear-gradient(90deg, var(--grid-major) 1px, transparent 1px),
        linear-gradient(var(--grid) 1px, transparent 1px),
        linear-gradient(90deg, var(--grid) 1px, transparent 1px);
      background-size: 140px 140px, 140px 140px, 28px 28px, 28px 28px;
    }

    /* ── Polar / protractor grid (top-right corner) ── */
    .polar {
      position: fixed; top: 0; right: 0;
      width: clamp(260px, 34vw, 560px); aspect-ratio: 1;
      pointer-events: none; z-index: 0;
      background:
        repeating-conic-gradient(from 180deg at 100% 0, rgba(139,150,190,.1) 0 .5deg, transparent .5deg 15deg),
        repeating-radial-gradient(circle at 100% 0, transparent 0 39px, rgba(139,150,190,.14) 39px 40px);
      -webkit-mask-image: radial-gradient(circle at 100% 0, #000 18%, transparent 70%);
              mask-image: radial-gradient(circle at 100% 0, #000 18%, transparent 70%);
    }

    /* ── Fixed layer holding the figures, formulas and the sine / cosine curve ── */
    .bg-figs { position: fixed; inset: 0; pointer-events: none; overflow: hidden; z-index: 0; }

    /* ── Sine / cosine curve along the bottom of the screen ── */
    .plot {
      position: absolute; left: 0; bottom: 0; width: 100%; height: clamp(110px, 18vh, 190px);
      pointer-events: none; z-index: 1;
      -webkit-mask-image: linear-gradient(to top, #000 40%, transparent);
              mask-image: linear-gradient(to top, #000 40%, transparent);
    }
    .plot .axis  { stroke: rgba(139,150,190,.5); stroke-width: 1; opacity: .55; }
    .plot .ticks { stroke: rgba(139,150,190,.5); stroke-width: 8; stroke-dasharray: 1.5 73.5; stroke-dashoffset: .75; opacity: .45; }
    .plot text   { font-family: 'Space Grotesk', sans-serif; font-size: 11px; font-style: italic; fill: rgba(139,150,190,.7); }
    .plot .lbl-sin { fill: var(--accent); }
    .plot .lbl-cos { fill: #B7A4F7; }
    .plot .sine  {
      fill: none; stroke: var(--accent); stroke-width: 2.25; stroke-linecap: round;
      stroke-dasharray: 1; stroke-dashoffset: 1;
      animation: drawCurve 3.4s cubic-bezier(.4,0,.2,1) .8s forwards;
    }
    .plot .cosine {
      fill: none; stroke: var(--accent-2); stroke-width: 1.5; stroke-dasharray: 5 7; opacity: 0;
      animation: fadeCurve 1.2s ease 2.8s forwards;
    }
    .plot .halo { fill: var(--chalk); opacity: .22; }
    .plot .dot  { fill: var(--chalk); }
    @keyframes drawCurve { to { stroke-dashoffset: 0; } }
    @keyframes fadeCurve { to { opacity: .6; } }
    @media (prefers-reduced-motion: reduce) {
      .plot .sine   { animation: none; stroke-dashoffset: 0; }
      .plot .cosine { animation: none; opacity: .6; }
      .plot .mover  { display: none; }
    }

    /* ── SVG geometry figures ── */
    .fig { position: absolute; display: block; height: auto; overflow: visible; }
    .fig-circle { top: 10vh;  left: 2.5vw;  width: clamp(130px, 16vw, 260px); }
    .fig-tri    { top: 8vh;   right: 3vw;   width: clamp(140px, 17vw, 280px); }
    .fig-para   { bottom: calc(clamp(110px,18vh,190px) + 6vh); right: 3.5vw; width: clamp(120px, 12vw, 200px); }
    .fig .ring { fill: none; stroke: rgba(139,150,190,.65); stroke-width: 1; stroke-dasharray: 3 4; }
    .fig .ax   { stroke: rgba(139,150,190,.45); stroke-width: 1; }
    .fig .rad  { stroke: var(--accent); stroke-width: 1.75; }
    .fig .sinl { stroke: var(--chalk); stroke-width: 1.75; }
    .fig .cosl { stroke: var(--accent-2); stroke-width: 1.75; }
    .fig .ang  { fill: none; stroke: rgba(139,150,190,.75); stroke-width: 1; }
    .fig .guide { fill: none; stroke: rgba(139,150,190,.65); stroke-width: 1; stroke-dasharray: 3 3; }
    .fig .pt   { fill: var(--accent); }
    .fig .tri  { fill: rgba(97,114,243,.07); stroke: var(--accent); stroke-width: 1.75; stroke-linejoin: round; }
    .fig .curve { fill: none; stroke: var(--accent-2); stroke-width: 1.75; stroke-linecap: round; }
    .fig text  { font-family: 'Space Grotesk', sans-serif; font-size: 10px; font-style: italic; fill: rgba(139,150,190,.8); }
    .fig .t-sin { fill: var(--chalk); }
    .fig .t-cos { fill: #B7A4F7; }

    /* ── Floating formulas ── */
    .formulas {
      position: absolute; z-index: 1;
      font-family: 'Space Grotesk', sans-serif;
      font-size: clamp(11px, .95vw, 14px); line-height: 2.1;
      color: rgba(139,150,190,.75); white-space: nowrap;
    }
    .formulas span { display: block; }
    .formulas.left  { left: 3vw;  top: 42vh; }
    .formulas.right { right: 3vw; top: 36vh; text-align: right; }
    @media (max-width: 900px) { .fig-circle, .fig-tri, .fig-para, .formulas { display: none; } }

    /* ── Floating symbols ── */
    #sym-layer {
      position: fixed; inset: 0;
      pointer-events: none; z-index: 0; overflow: hidden;
    }
    .sym {
      position: absolute;
      font-family: 'Space Grotesk', monospace;
      font-weight: 600;
      opacity: 0;
      animation: floatSym linear infinite;
    }
    @keyframes floatSym {
      0%   { transform: translateY(105vh) rotate(0deg);   opacity: 0; }
      5%   { opacity: .18; }
      95%  { opacity: .18; }
      100% { transform: translateY(-10vh) rotate(360deg); opacity: 0; }
    }
    @media (prefers-reduced-motion: reduce) { .sym { animation: none; opacity: .15; } }

    /* ── Navbar ── */
    .site-nav {
      position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
      background: rgba(7,8,26,.88);
      backdrop-filter: blur(14px);
      border-bottom: 1px solid rgba(99,102,241,.18);
      height: 62px;
      display: flex; align-items: center;
    }
    .site-nav .inner {
      max-width: 1200px; margin: 0 auto; padding: 0 24px;
      width: 100%; display: flex; align-items: center; justify-content: space-between;
    }
    .brand {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 1.45rem; font-weight: 900; text-decoration: none;
      background: linear-gradient(135deg, #a78bfa, #60a5fa);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      background-clip: text;
      display: flex; align-items: center; gap: 8px;
    }
    .brand img { height: 75px; width: auto; }
    .brand i { -webkit-text-fill-color: #a78bfa; font-size: 1.2rem; }
    .nav-links { display: flex; align-items: center; gap: 10px; }
    .btn-nav-login {
      padding: 8px 20px; border-radius: 10px; font-size: .83rem; font-weight: 700;
      border: 1px solid rgba(99,102,241,.4);
      background: rgba(99,102,241,.1); color: #a5b4fc;
      text-decoration: none; transition: all .2s;
    }
    .btn-nav-login:hover { background: rgba(99,102,241,.22); border-color: rgba(99,102,241,.7); color: #a5b4fc; }
    .btn-nav-register {
      padding: 8px 20px; border-radius: 10px; font-size: .83rem; font-weight: 700;
      background: linear-gradient(135deg, #6366f1, #7c3aed);
      border: none; color: #fff; text-decoration: none; transition: all .2s;
      box-shadow: 0 4px 14px rgba(99,102,241,.35);
    }
    .btn-nav-register:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(99,102,241,.5); color: #fff; }

    /* hamburger */
    .nav-toggler {
      display: none; background: none; border: none; color: rgba(255,255,255,.7);
      font-size: 1.4rem; cursor: pointer;
    }
    @media (max-width: 576px) {
      .nav-links { display: none; flex-direction: column; gap: 8px; }
      .nav-links.open { display: flex; position: absolute; top: 62px; left: 0; right: 0;
        background: rgba(7,8,26,.97); padding: 16px 24px; border-bottom: 1px solid rgba(99,102,241,.2); }
      .nav-toggler { display: block; }
    }

    /* ── Hero ── */
    .hero {
      min-height: 100vh;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      text-align: center;
      padding: 100px 24px 60px;
      position: relative; z-index: 1;
      background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(99,102,241,.16) 0%, transparent 70%);
      overflow: hidden;
    }
    .hero-badge {
      display: inline-flex; align-items: center; gap: 7px;
      background: rgba(99,102,241,.14); border: 1px solid rgba(99,102,241,.35);
      border-radius: 99px; padding: 6px 18px;
      font-size: .72rem; font-weight: 800; color: #a5b4fc;
      text-transform: uppercase; letter-spacing: 1.2px;
      margin-bottom: 28px;
      animation: badgePop .6s cubic-bezier(.34,1.56,.64,1) both;
    }
    @keyframes badgePop { from { opacity:0; transform: scale(.7); } to { opacity:1; transform: scale(1); } }
    .hero-title {
      font-family: 'Space Grotesk', sans-serif;
      font-size: clamp(2.8rem, 7vw, 5.2rem);
      font-weight: 900; color: #fff; line-height: 1.08;
      animation: slideUp .7s .1s cubic-bezier(.34,1.56,.64,1) both;
    }
    .hero-title .grad {
      background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 50%, #34d399 100%);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    @keyframes slideUp { from { opacity:0; transform: translateY(40px); } to { opacity:1; transform: translateY(0); } }
    .hero-sub {
      font-size: clamp(1rem, 2.5vw, 1.2rem);
      color: rgba(255,255,255,.5); max-width: 560px;
      margin: 20px auto 40px; line-height: 1.7;
      animation: slideUp .7s .22s cubic-bezier(.34,1.56,.64,1) both;
    }
    .hero-cta {
      display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;
      animation: slideUp .7s .34s cubic-bezier(.34,1.56,.64,1) both;
    }
    .btn-hero-primary {
      padding: 14px 34px; border-radius: 14px; font-size: 1rem; font-weight: 800;
      background: linear-gradient(135deg, #6366f1, #7c3aed);
      border: none; color: #fff; text-decoration: none;
      box-shadow: 0 6px 24px rgba(99,102,241,.45);
      transition: all .25s; display: inline-flex; align-items: center; gap: 9px;
    }
    .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(99,102,241,.6); color: #fff; }
    .btn-hero-secondary {
      padding: 14px 34px; border-radius: 14px; font-size: 1rem; font-weight: 800;
      background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.15);
      color: rgba(255,255,255,.8); text-decoration: none;
      transition: all .25s; display: inline-flex; align-items: center; gap: 9px;
    }
    .btn-hero-secondary:hover { background: rgba(255,255,255,.12); border-color: rgba(255,255,255,.3); color: #fff; transform: translateY(-3px); }

    /* ticker */
    .ticker-wrap {
      overflow: hidden; white-space: nowrap; margin-top: 60px;
      border-top: 1px solid rgba(255,255,255,.06);
      border-bottom: 1px solid rgba(255,255,255,.06);
      padding: 10px 0;
      animation: slideUp .7s .45s both;
    }
    .ticker-track {
      display: inline-block;
      animation: ticker 30s linear infinite;
      font-family: 'Courier New', monospace;
      font-size: .8rem; color: rgba(129,140,248,.7);
      font-weight: 700; letter-spacing: 1px;
    }
    @keyframes ticker { from { transform: translateX(0); } to { transform: translateX(-50%); } }

    /* ── Stats bar ── (lightly frosted so the fixed background stays calm behind the numbers) */
    .stats-bar {
      position: relative; z-index: 1;
      background: rgba(10,12,20,.62);
      -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
      border-top: 1px solid rgba(255,255,255,.06);
      border-bottom: 1px solid rgba(255,255,255,.06);
      padding: 36px 24px;
    }
    .stat-item { text-align: center; }
    .stat-num {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 2.4rem; font-weight: 900; color: #fff;
      background: linear-gradient(135deg, #a78bfa, #60a5fa);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .stat-lbl { font-size: .75rem; color: rgba(255,255,255,.4); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-top: 4px; }

    /* ── Features ── */
    .section { position: relative; z-index: 1; padding: 90px 24px; }
    .section-title {
      font-family: 'Space Grotesk', sans-serif;
      font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 900; color: #fff;
      text-align: center; margin-bottom: 12px;
    }
    .section-sub { text-align: center; color: rgba(255,255,255,.45); font-size: .95rem; margin-bottom: 56px; }

    .feat-card {
      background: rgba(12,14,22,.72);
      -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,.08);
      border-radius: 22px; padding: 32px 26px;
      transition: transform .3s, border-color .3s, box-shadow .3s;
      height: 100%;
    }
    .feat-card:hover { transform: translateY(-6px); border-color: rgba(99,102,241,.35); box-shadow: 0 18px 48px rgba(99,102,241,.15); }
    .feat-icon {
      width: 52px; height: 52px; border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; margin-bottom: 20px;
    }
    .feat-card h3 { font-size: 1.05rem; font-weight: 800; color: #fff; margin-bottom: 8px; }
    .feat-card p  { font-size: .85rem; color: rgba(255,255,255,.45); line-height: 1.7; margin: 0; }

    /* ── Role cards ── */
    .role-card {
      background: rgba(12,14,22,.72);
      -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,.08);
      border-radius: 24px; padding: 40px 32px;
      text-align: center; transition: transform .3s, box-shadow .3s, border-color .3s;
      height: 100%;
    }
    .role-card:hover { transform: translateY(-8px); box-shadow: 0 22px 56px rgba(0,0,0,.4); }
    .role-card.student:hover { border-color: rgba(16,185,129,.35); box-shadow: 0 22px 56px rgba(16,185,129,.15); }
    .role-card.teacher:hover { border-color: rgba(99,102,241,.35); box-shadow: 0 22px 56px rgba(99,102,241,.15); }
    .role-icon {
      width: 72px; height: 72px; border-radius: 20px;
      margin: 0 auto 22px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.9rem;
    }
    .role-card h3 { font-size: 1.25rem; font-weight: 900; color: #fff; margin-bottom: 10px; }
    .role-card p  { font-size: .87rem; color: rgba(255,255,255,.45); line-height: 1.7; margin-bottom: 28px; }
    .btn-role {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 28px; border-radius: 12px;
      font-size: .88rem; font-weight: 800; text-decoration: none;
      transition: all .22s;
    }
    .btn-role.green  { background: rgba(16,185,129,.15); border: 1px solid rgba(16,185,129,.35); color: #34d399; }
    .btn-role.green:hover  { background: rgba(16,185,129,.28); border-color: #10b981; color: #34d399; transform: translateY(-2px); }
    .btn-role.purple { background: rgba(99,102,241,.15); border: 1px solid rgba(99,102,241,.35); color: #a5b4fc; }
    .btn-role.purple:hover { background: rgba(99,102,241,.28); border-color: #6366f1; color: #a5b4fc; transform: translateY(-2px); }

    /* ── Footer ── */
    .site-footer {
      position: relative; z-index: 1;
      background: rgba(7,8,13,.7);
      -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px);
      border-top: 1px solid rgba(255,255,255,.07);
      padding: 32px 24px; text-align: center;
      color: rgba(255,255,255,.25); font-size: .8rem;
    }
    .site-footer a { color: rgba(255,255,255,.35); text-decoration: none; }
    .site-footer a:hover { color: #a5b4fc; }

    /* scroll reveal */
    .reveal { opacity: 0; transform: translateY(32px); transition: opacity .65s, transform .65s; }
    .reveal.visible { opacity: 1; transform: translateY(0); }
  </style>
</head>
<body>

<!-- Floating symbols -->
<div id="sym-layer" aria-hidden="true"></div>

<!-- Polar / protractor grid -->
<div class="polar" aria-hidden="true"></div>

<!-- Math background: fixed to the screen, so it stays while the page scrolls -->
<div class="bg-figs" aria-hidden="true">

  <!-- sine / cosine wave along the bottom of the screen -->
  <svg class="plot" viewBox="0 0 1200 160" preserveAspectRatio="xMidYMax slice" focusable="false">
    <defs>
      <path id="sinePath" d="M0 90 C 50 37, 100 37, 150 90 S 250 143, 300 90 S 400 37, 450 90 S 550 143, 600 90 S 700 37, 750 90 S 850 143, 900 90 S 1000 37, 1050 90 S 1150 143, 1200 90"/>
    </defs>
    <line class="axis"  x1="0" y1="90" x2="1200" y2="90"/>
    <line class="ticks" x1="0" y1="90" x2="1200" y2="90"/>
    <line class="axis"  x1="40" y1="10" x2="40" y2="160"/>
    <text x="150"  y="112" text-anchor="middle">π</text>
    <text x="300"  y="112" text-anchor="middle">2π</text>
    <text x="450"  y="112" text-anchor="middle">3π</text>
    <text x="600"  y="112" text-anchor="middle">4π</text>
    <text x="750"  y="112" text-anchor="middle">5π</text>
    <text x="900"  y="112" text-anchor="middle">6π</text>
    <text x="1050" y="112" text-anchor="middle">7π</text>
    <path class="cosine" d="M-75 90 C -25 37, 25 37, 75 90 S 175 143, 225 90 S 325 37, 375 90 S 475 143, 525 90 S 625 37, 675 90 S 775 143, 825 90 S 925 37, 975 90 S 1075 143, 1125 90 S 1225 37, 1275 90"/>
    <path class="sine" pathLength="1" d="M0 90 C 50 37, 100 37, 150 90 S 250 143, 300 90 S 400 37, 450 90 S 550 143, 600 90 S 700 37, 750 90 S 850 143, 900 90 S 1000 37, 1050 90 S 1150 143, 1200 90"/>
    <text class="lbl-sin" x="75"   y="40" text-anchor="middle">y = sin x</text>
    <text class="lbl-cos" x="1170" y="40" text-anchor="end">y = cos x</text>
    <g class="mover">
      <circle class="halo" r="11"/>
      <circle class="dot"  r="4.5"/>
      <animateMotion dur="18s" begin="3s" repeatCount="indefinite" calcMode="linear">
        <mpath href="#sinePath"/>
      </animateMotion>
    </g>
  </svg>

  <!-- unit circle -->
  <svg class="fig fig-circle" viewBox="0 0 156 150" focusable="false">
    <line class="ax" x1="8" y1="75" x2="142" y2="75"/>
    <line class="ax" x1="75" y1="8" x2="75" y2="142"/>
    <circle class="ring" cx="75" cy="75" r="58"/>
    <line class="rad"  x1="75" y1="75" x2="120.7" y2="39.3"/>
    <line class="cosl" x1="75" y1="75" x2="120.7" y2="75"/>
    <line class="sinl" x1="120.7" y1="75" x2="120.7" y2="39.3"/>
    <path class="ang" d="M95 75 A20 20 0 0 0 90.8 62.7"/>
    <circle class="pt" cx="120.7" cy="39.3" r="3"/>
    <text x="98" y="71">θ</text>
    <text x="91" y="50">1</text>
    <text class="t-sin" x="125" y="60">sin θ</text>
    <text class="t-cos" x="82" y="90">cos θ</text>
  </svg>

  <!-- right triangle -->
  <svg class="fig fig-tri" viewBox="0 0 160 130" focusable="false">
    <path class="tri" d="M14 112 L138 112 L138 26 Z"/>
    <path class="ang" d="M126 112 V100 H138"/>
    <text x="74" y="126" text-anchor="middle">b</text>
    <text x="146" y="72">a</text>
    <text x="64" y="62">c</text>
  </svg>

  <!-- parabola -->
  <svg class="fig fig-para" viewBox="0 0 160 130" focusable="false">
    <line class="ax" x1="10" y1="110" x2="150" y2="110"/>
    <line class="ax" x1="80" y1="10" x2="80" y2="124"/>
    <path class="curve" d="M20 30 Q80 190 140 30"/>
    <path class="guide" d="M110 110 V90 H80"/>
    <circle class="pt" cx="110" cy="90" r="3"/>
    <text x="80" y="60" text-anchor="middle">y = x²</text>
  </svg>

  <!-- formulas -->
  <div class="formulas left">
    <span>sin²θ + cos²θ = 1</span>
    <span>π ≈ 3.14159</span>
    <span>n! = n × (n − 1)!</span>
  </div>
  <div class="formulas right">
    <span>a² + b² = c²</span>
    <span>x = (−b ± √(b² − 4ac)) / 2a</span>
    <span>Σ n = n(n + 1) / 2</span>
  </div>
</div>

<!-- ── NAVBAR ── -->
<nav class="site-nav">
  <div class="inner">
    <a href="/" class="brand"><img src="{{ asset('images/logo.png') }}" alt="EduPlay Logo"></a>
    <button class="nav-toggler" id="navToggler" onclick="document.getElementById('navLinks').classList.toggle('open')">
      <i class="bi bi-list"></i>
    </button>
    <div class="nav-links" id="navLinks">
      <a href="{{ route('login') }}" class="btn-nav-login"><i class="bi bi-door-open me-1"></i>Log in</a>
      <a href="{{ route('register.form', 'teacher') }}" class="btn-nav-register"><i class="bi bi-easel3-fill me-1"></i>Teacher Registration</a>
    </div>
  </div>
</nav>

<!-- ── HERO ── -->
<section class="hero">
  <div class="hero-badge"><i class="bi bi-lightning-charge-fill"></i> Math Learning Reimagined</div>
  <h1 class="hero-title">Learn Math Through<br><span class="grad">Epic Games</span></h1>
  <p class="hero-sub">EduPlay turns numbers into adventures. Battle enemies, solve puzzles, and climb leaderboards — all while mastering real math.</p>
  <div class="hero-cta">
    <a href="{{ route('login') }}" class="btn-hero-primary">
      <i class="bi bi-controller"></i> Student &amp; Teacher Login
    </a>
    <a href="{{ route('register.form', 'teacher') }}" class="btn-hero-secondary">
      <i class="bi bi-easel3-fill"></i> Register as Teacher
    </a>
  </div>

  <!-- Equation ticker -->
  <div class="ticker-wrap" style="width:100%;max-width:900px;">
    <span class="ticker-track">
      &nbsp;f(x) = ax² + bx + c &nbsp;·&nbsp; E = mc² &nbsp;·&nbsp; a² + b² = c² &nbsp;·&nbsp; ∑n = n(n+1)/2 &nbsp;·&nbsp; sin²θ + cos²θ = 1 &nbsp;·&nbsp; x = (−b ± √Δ) / 2a &nbsp;·&nbsp; A = πr² &nbsp;·&nbsp; P = 2(l+w) &nbsp;·&nbsp; V = ⁴⁄₃πr³ &nbsp;·&nbsp;
      &nbsp;f(x) = ax² + bx + c &nbsp;·&nbsp; E = mc² &nbsp;·&nbsp; a² + b² = c² &nbsp;·&nbsp; ∑n = n(n+1)/2 &nbsp;·&nbsp; sin²θ + cos²θ = 1 &nbsp;·&nbsp; x = (−b ± √Δ) / 2a &nbsp;·&nbsp; A = πr² &nbsp;·&nbsp; P = 2(l+w) &nbsp;·&nbsp; V = ⁴⁄₃πr³ &nbsp;·&nbsp;
    </span>
  </div>
</section>

<!-- ── STATS ── -->
<div class="stats-bar reveal">
  <div class="container" style="max-width:900px;">
    <div class="row g-4 justify-content-center">
      <div class="col-6 col-md-3 stat-item">
        <div class="stat-num">{{ $totalGames }}</div>
        <div class="stat-lbl"><i class="bi bi-joystick-fill me-1"></i>Games</div>
      </div>
      <div class="col-6 col-md-3 stat-item">
        <div class="stat-num">15</div>
        <div class="stat-lbl"><i class="bi bi-trophy-fill me-1"></i>Levels Each</div>
      </div>
      <div class="col-6 col-md-3 stat-item">
        <div class="stat-num">6</div>
        <div class="stat-lbl"><i class="bi bi-person-badge-fill me-1"></i>Heroes</div>
      </div>
      <div class="col-6 col-md-3 stat-item">
        <div class="stat-num">∞</div>
        <div class="stat-lbl"><i class="bi bi-fire me-1"></i>Fun</div>
      </div>
    </div>
  </div>
</div>

<!-- ── FEATURES ── -->
<section class="section">
  <div class="container" style="max-width:1100px;">
    <p class="section-title reveal">Why students love EduPlay</p>
    <p class="section-sub reveal">Real math, real fun — designed to keep you coming back.</p>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3 reveal">
        <div class="feat-card">
          <div class="feat-icon" style="background:rgba(99,102,241,.18);color:#a5b4fc;"><i class="bi bi-controller"></i></div>
          <h3>Game-Based Learning</h3>
          <p>{{ $totalGames }} unique games that turn math problems into exciting challenges with real stakes.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 reveal" style="transition-delay:.1s;">
        <div class="feat-card">
          <div class="feat-icon" style="background:rgba(16,185,129,.18);color:#34d399;"><i class="bi bi-graph-up-arrow"></i></div>
          <h3>Track Your Progress</h3>
          <p>Visual dashboards show your scores, levels cleared, and high scores over time.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 reveal" style="transition-delay:.2s;">
        <div class="feat-card">
          <div class="feat-icon" style="background:rgba(251,191,36,.18);color:#fbbf24;"><i class="bi bi-shield-fill-check"></i></div>
          <h3>Choose Your Hero</h3>
          <p>Pick from 6 unique math warrior characters, each with their own personality and style.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3 reveal" style="transition-delay:.3s;">
        <div class="feat-card">
          <div class="feat-icon" style="background:rgba(244,63,94,.18);color:#f87171;"><i class="bi bi-clipboard2-data-fill"></i></div>
          <h3>Teacher Tools</h3>
          <p>Teachers can create classrooms, assign quizzes, and monitor every student's progress live.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ── ROLES ── -->
<section class="section" style="padding-top:0;">
  <div class="container" style="max-width:900px;">
    <p class="section-title reveal">Get Started</p>
    <p class="section-sub reveal">Students log in with credentials given by their teacher. Teachers can register below.</p>
    <div class="row g-4 justify-content-center">
      <div class="col-md-5 reveal">
        <div class="role-card student">
          <div class="role-icon" style="background:rgba(16,185,129,.18);color:#34d399;">
            <i class="bi bi-joystick"></i>
          </div>
          <h3>I'm a Student</h3>
          <p>Accounts are provided by your teacher. Use your full name and ID number to log in and play!</p>
          <a href="{{ route('login') }}" class="btn-role green">
            <i class="bi bi-door-open-fill"></i> Student Log In
          </a>
        </div>
      </div>
      <div class="col-md-5 reveal" style="transition-delay:.12s;">
        <div class="role-card teacher">
          <div class="role-icon" style="background:rgba(99,102,241,.18);color:#a5b4fc;">
            <i class="bi bi-mortarboard-fill"></i>
          </div>
          <h3>I'm a Teacher</h3>
          <p>Create classrooms, manage student accounts, build quizzes, and watch your students grow in real time.</p>
          <a href="{{ route('register.form', 'teacher') }}" class="btn-role purple">
            <i class="bi bi-easel3-fill"></i> Register as Teacher
          </a>
        </div>
      </div>
    </div>
    <div class="text-center mt-4 reveal" style="transition-delay:.24s;">
      <span style="font-size:.85rem;color:rgba(255,255,255,.3);">Already have an account?</span>
      <a href="{{ route('login') }}" style="font-size:.85rem;font-weight:700;color:#a5b4fc;text-decoration:none;margin-left:8px;">
        <i class="bi bi-door-open me-1"></i>Log in here
      </a>
    </div>
  </div>
</section>

<!-- ── FOOTER ── -->
<footer class="site-footer">
  <p>© {{ date('Y') }} <strong style="color:rgba(255,255,255,.4);">EduPlay</strong> — Math Learning Platform &nbsp;·&nbsp;
    <a href="{{ route('login') }}">Log In</a> &nbsp;·&nbsp;
    <a href="{{ route('register.form', 'teacher') }}">Teacher Registration</a>
  </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Floating math symbols
(function(){
  const syms=['π','∑','√','∞','÷','×','±','∆','∫','≠','²','³','=','θ','%','ƒ(x)','n!','y = mx + b','a² + b² = c²','sin θ','x² − 4 = 0','√(x + 1)','∑ n','Δy / Δx','+','−','≈','≤','≥','α','β','λ','∂','lim x→0','log₂ 8 = 3','7 × 8 = 56','2³ = 8','½','¾','0.5','∠','△','⊥','∥','∈','ℝ'];
  const colors=['#818cf8','#60a5fa','#34d399','#fbbf24','#f87171','#a78bfa'];
  const layer = document.getElementById('sym-layer');
  for(let i = 0; i < 55; i++){
    const s = document.createElement('span');
    s.className = 'sym';
    const t = syms[Math.floor(Math.random() * syms.length)];
    s.textContent = t;
    s.style.left = Math.random() * 100 + '%';
    s.style.top  = (100 + Math.random() * 30) + '%';
    s.style.fontSize = (t.length > 3 ? .8 + Math.random() * .6 : .9 + Math.random() * 1.8) + 'rem';
    s.style.animationDelay    = Math.random() * 14 + 's';
    s.style.animationDuration = (9 + Math.random() * 10) + 's';
    s.style.color = colors[Math.floor(Math.random() * colors.length)];
    layer.appendChild(s);
  }
})();

// Scroll reveal
(function(){
  const els = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => { if(e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
  }, { threshold: 0.12 });
  els.forEach(el => io.observe(el));
})();
</script>
</body>
</html>