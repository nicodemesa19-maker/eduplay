<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Log In — EduPlay</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --page:     #07080D;               /* the page behind the card */
      --card:     #10131C;               /* the floating card */
      --bg:       #0A0B11;
      --panel:    #0E1018;
      --surface:  #13161F;
      --line:     #222634;
      --line-2:   #1A1D28;
      --ink:      #E9ECF3;
      --ink-2:    #98A0B2;
      --ink-3:    #656D7F;
      --accent:   #6172F3;
      --accent-2: #8B5CF6;
      --accent-w: rgba(97,114,243,.14);
      --danger:   #F0616F;
      --ok:       #2FBD9C;
      /* math theme */
      --chalk:      #F2C85B;              /* the "answer" colour, used sparingly */
      --grid:       rgba(139,150,190,.075);
      --grid-major: rgba(139,150,190,.15);
    }

    html, body { height: 100%; }
    body {
      font-family: 'Inter', system-ui, sans-serif;
      background: var(--page);
      color: var(--ink);
      -webkit-font-smoothing: antialiased;
      font-size: 15px;
      line-height: 1.55;
    }

    /* page: one centred card, image-style panel inset on the left, form on the right */
    .shell { position: relative; overflow: hidden; min-height: 100vh; display: grid; place-items: center; padding: 32px; }
    .shell::before {
      /* graph paper across the whole page: fine 28px grid, heavier line every fifth square */
      content: ''; position: absolute; z-index: 0; inset: 0; pointer-events: none;
      background-image:
        linear-gradient(var(--grid-major) 1px, transparent 1px),
        linear-gradient(90deg, var(--grid-major) 1px, transparent 1px),
        linear-gradient(var(--grid) 1px, transparent 1px),
        linear-gradient(90deg, var(--grid) 1px, transparent 1px);
      background-size: 140px 140px, 140px 140px, 28px 28px, 28px 28px;
    }
    .bg-math { position: absolute; z-index: 1; inset: 0; pointer-events: none; overflow: hidden; }
    .frame {
      position: relative; z-index: 2; width: 100%; max-width: 1040px; min-height: 700px;
      display: grid; grid-template-columns: 1.05fr 1fr; gap: 8px;
      padding: 16px; border-radius: 28px;
      background-color: rgba(16,19,28,.86);
      -webkit-backdrop-filter: blur(12px); backdrop-filter: blur(12px);
      border: 1px solid var(--line-2);
      box-shadow: 0 40px 90px rgba(0,0,0,.55);
    }

    /* ============ left: logo + headline ============ */
    .aside {
      position: relative;
      background: rgba(8,10,16,.9);
      border: 1px solid var(--line-2);
      border-radius: 20px;
      padding: 34px 40px;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      container-type: inline-size;   /* lets the logo and headline scale with the panel */
      overflow: hidden;
    }
    .aside::before {
      /* soft light from the top-left, keeps the panel from reading flat */
      content: ''; position: absolute; z-index: 0; top: -20%; left: -10%;
      width: 70%; aspect-ratio: 1;
      background: radial-gradient(circle, rgba(97,114,243,.16), transparent 68%);
      pointer-events: none;
    }
    /* minimal details inside the card: registration marks, a dot matrix, one quiet wave, and rings around the logo */
    .aside .marks { position: absolute; inset: 0; z-index: 1; pointer-events: none; }
    .tick {
      position: absolute; width: 11px; height: 11px; color: var(--ink-3); opacity: .6;
      background:
        linear-gradient(currentColor, currentColor) center / 1px 100% no-repeat,
        linear-gradient(currentColor, currentColor) center / 100% 1px no-repeat;
    }
    .tick.tl { top: 16px; left: 16px; }   .tick.tr { top: 16px; right: 16px; }
    .tick.bl { bottom: 16px; left: 16px; } .tick.br { bottom: 16px; right: 16px; }
    .card-dots {
      position: absolute; z-index: 0; top: 0; right: 0; width: 52%; aspect-ratio: 1.5; pointer-events: none;
      background-image: radial-gradient(circle, rgba(152,160,178,.42) 1px, transparent 1.6px);
      background-size: 15px 15px;
      -webkit-mask-image: radial-gradient(circle at 100% 0, #000 10%, transparent 72%);
              mask-image: radial-gradient(circle at 100% 0, #000 10%, transparent 72%);
    }
    .card-wave {
      position: absolute; z-index: 1; left: 0; bottom: 30px; width: 100%; height: 38px; pointer-events: none;
      -webkit-mask-image: linear-gradient(90deg, transparent, #000 22%, #000 78%, transparent);
              mask-image: linear-gradient(90deg, transparent, #000 22%, #000 78%, transparent);
    }
    .card-wave path, .card-wave line { fill: none; vector-effect: non-scaling-stroke; }
    .card-wave .base { stroke: var(--ink-3); stroke-width: 1; stroke-dasharray: 2 5; opacity: .5; }
    .card-wave .wave { stroke: var(--accent); stroke-width: 1.5; stroke-linecap: round; opacity: .6; }

    #sym-layer { position: absolute; inset: 0; pointer-events: none; overflow: hidden; }
    .sym {
      position: absolute; font-family: 'Space Grotesk', serif; font-weight: 600;
      color: var(--ink); opacity: 0; animation: driftUp linear infinite; white-space: nowrap;
    }
    @keyframes driftUp {
      0%   { transform: translateY(20vh); opacity: 0; }
      12%  { opacity: .11; }
      88%  { opacity: .11; }
      100% { transform: translateY(-110vh); opacity: 0; }
    }
    @media (prefers-reduced-motion: reduce) { .sym { animation: none; opacity: .09; } }

    /* plotted curves along the bottom edge of the page */
    .plot {
      position: absolute; left: 0; bottom: 0; width: 100%; height: clamp(110px, 18vh, 190px);
      pointer-events: none;
      -webkit-mask-image: linear-gradient(to top, #000 45%, transparent);
              mask-image: linear-gradient(to top, #000 45%, transparent);
    }
    .plot .axis  { stroke: var(--ink-3); stroke-width: 1; opacity: .55; }
    .plot .ticks { stroke: var(--ink-3); stroke-width: 8; stroke-dasharray: 1.5 73.5; stroke-dashoffset: .75; opacity: .5; }
    .plot text   { font-family: 'Space Grotesk', sans-serif; font-size: 11px; font-style: italic; fill: var(--ink-3); }
    .plot .lbl-sin { fill: var(--accent); }
    .plot .lbl-cos { fill: #B7A4F7; }
    .plot .sine  {
      fill: none; stroke: var(--accent); stroke-width: 2.25; stroke-linecap: round;
      stroke-dasharray: 1; stroke-dashoffset: 1;
      animation: drawCurve 3.4s cubic-bezier(.4,0,.2,1) .5s forwards;
    }
    .plot .cosine {
      fill: none; stroke: var(--accent-2); stroke-width: 1.5; stroke-dasharray: 5 7; opacity: 0;
      animation: fadeCurve 1.2s ease 2.6s forwards;
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

    .aside-mid { position: relative; z-index: 2; }
    .logo { position: relative; display: block; }
    .logo::before, .logo::after {
      content: ''; position: absolute; z-index: -1; left: 50%; top: 50%; pointer-events: none;
      width: 72cqw; aspect-ratio: 1; margin: -36cqw 0 0 -36cqw;
    }
    .logo::before {   /* two concentric rings: a thin solid one and a dashed one inside it */
      border-radius: 50%;
      border: 1px solid rgba(139,150,190,.14);
      outline: 1px dashed rgba(139,150,190,.28); outline-offset: -7cqw;
    }
    .logo::after {    /* one point orbiting the outer ring, like the dot on the sine curve */
      background: radial-gradient(circle at 50% 0, var(--chalk) 0 3.5px, rgba(242,200,91,.22) 3.5px 8px, transparent 8.5px);
      animation: orbit 34s linear infinite;
    }
    @keyframes orbit { to { transform: rotate(360deg); } }
    @media (prefers-reduced-motion: reduce) { .logo::after { animation: none; } }
    .logo img {
      height: clamp(110px, 15vw, 170px); height: clamp(110px, 36cqw, 190px);
      width: auto; max-width: 100%; object-fit: contain; display: block; margin: 0 auto;
    }

    /* protractor-style polar grid fanning out of the top-right corner of the page */
    .polar {
      position: absolute; top: 0; right: 0; width: clamp(260px, 34vw, 560px); aspect-ratio: 1; pointer-events: none;
      background:
        repeating-conic-gradient(from 180deg at 100% 0, rgba(139,150,190,.11) 0 .5deg, transparent .5deg 15deg),
        repeating-radial-gradient(circle at 100% 0, transparent 0 39px, rgba(139,150,190,.15) 39px 40px);
      -webkit-mask-image: radial-gradient(circle at 100% 0, #000 18%, transparent 70%);
              mask-image: radial-gradient(circle at 100% 0, #000 18%, transparent 70%);
    }

    /* constructions and formulas, scattered in the margins around the card */
    .bg-math .fig { position: absolute; display: block; height: auto; overflow: visible; }
    .fig-circle { top: 5vh;  left: 3vw;  width: clamp(150px, 19vw, 300px); }
    .fig-tri    { top: 6vh;  right: 3.5vw; width: clamp(160px, 20vw, 320px); }
    .fig-para   { bottom: calc(clamp(110px, 18vh, 190px) + 4vh); right: 4vw; width: clamp(130px, 14vw, 220px); }
    .fig .ring { fill: none; stroke: var(--ink-3); stroke-width: 1; stroke-dasharray: 3 4; opacity: .75; }
    .fig .ax   { stroke: var(--ink-3); stroke-width: 1; opacity: .5; }
    .fig .rad  { stroke: var(--accent); stroke-width: 1.75; }
    .fig .sinl { stroke: var(--chalk); stroke-width: 1.75; }
    .fig .cosl { stroke: var(--accent-2); stroke-width: 1.75; }
    .fig .ang  { fill: none; stroke: var(--ink-2); stroke-width: 1; opacity: .8; }
    .fig .guide { fill: none; stroke: var(--ink-3); stroke-width: 1; stroke-dasharray: 3 3; opacity: .8; }
    .fig .pt   { fill: var(--accent); }
    .fig .tri  { fill: rgba(97,114,243,.08); stroke: var(--accent); stroke-width: 1.75; stroke-linejoin: round; }
    .fig .curve { fill: none; stroke: var(--accent-2); stroke-width: 1.75; stroke-linecap: round; }
    .fig text  { font-family: 'Space Grotesk', sans-serif; font-size: 10px; font-style: italic; fill: var(--ink-2); }
    .fig .t-sin { fill: var(--chalk); }
    .fig .t-cos { fill: #B7A4F7; }
    .formulas {
      position: absolute; font-family: 'Space Grotesk', sans-serif; font-size: clamp(12px, 1vw, 15px); line-height: 2.05;
      color: var(--ink-3); white-space: nowrap; opacity: .9;
    }
    .formulas span { display: block; }
    .formulas.left  { left: 3.5vw;  top: 47vh; }
    .formulas.right { right: 3.5vw; top: 40vh; text-align: right; }

    /* the only content on the left: a big logo with one headline beneath it */
    .aside-mid {
      flex: 0 0 auto; width: 100%;
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      gap: 27px; gap: 6cqw; text-align: center;
    }
    .tagline {
      font-family: 'Space Grotesk', sans-serif;
      font-size: clamp(28px, 3vw, 40px); font-size: clamp(28px, 8.6cqw, 44px); line-height: 1.12; font-weight: 700;
      letter-spacing: -.03em; color: #fff;
    }

    /* ============ right: form ============ */
    .main { display: flex; flex-direction: column; padding: 14px 28px 26px; }
    .main-top { display: flex; justify-content: flex-end; }
    .home {
      display: inline-flex; align-items: center; gap: 7px;
      font-size: 13px; font-weight: 600; color: var(--ink-2); text-decoration: none;
      border: 1px solid var(--line); border-radius: 9px; padding: 7px 13px;
      background: var(--card);
      transition: color .15s, border-color .15s;
    }
    .home:hover { color: var(--ink); border-color: var(--ink-3); }

    .form-wrap { flex: 1; display: flex; align-items: center; justify-content: center; }
    .form-col { width: 100%; max-width: 392px; }

    .lead { margin-bottom: 30px; text-align: center; }
    .lead h1 {
      font-family: 'Space Grotesk', sans-serif;
      font-size: 27px; font-weight: 700; letter-spacing: -.02em; color: #fff; margin-bottom: 6px;
    }
    .lead p { color: var(--ink-3); font-size: 14px; }

    .field { margin-bottom: 18px; }
    .label { display: block; font-size: 13px; font-weight: 600; color: var(--ink-2); margin-bottom: 7px; }
    .control { position: relative; display: flex; align-items: center; }
    .control > .lead-icon {
      position: absolute; left: 13px; color: var(--ink-3); font-size: 14px; pointer-events: none; z-index: 2;
    }
    .input {
      width: 100%; padding: 12px 14px 12px 38px;
      background: var(--surface); border: 1px solid var(--line); border-radius: 10px;
      color: var(--ink); font-size: 14.5px; font-family: inherit;
      outline: none; transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .input::placeholder { color: var(--ink-3); }
    .input:hover { border-color: #2C3142; }
    .input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-w); background: #151926; }
    .input.is-invalid { border-color: var(--danger); }
    .input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(240,97,111,.14); }
    /* keep browser autofill on-theme instead of the white block */
    .input:-webkit-autofill,
    .input:-webkit-autofill:hover,
    .input:-webkit-autofill:focus {
      -webkit-text-fill-color: var(--ink);
      -webkit-box-shadow: 0 0 0 1000px #151926 inset;
      caret-color: var(--ink);
      transition: background-color 9999s ease-out 0s;
    }
    #pw { padding-right: 44px; letter-spacing: .04em; }

    .toggle-pw {
      position: absolute; right: 6px; background: none; border: 0;
      color: var(--ink-3); cursor: pointer; font-size: 15px;
      width: 32px; height: 32px; border-radius: 7px; z-index: 3;
      display: flex; align-items: center; justify-content: center;
      transition: color .15s, background .15s;
    }
    .toggle-pw:hover { color: var(--ink); background: rgba(255,255,255,.05); }
    .toggle-pw:focus-visible { outline: 2px solid var(--accent); outline-offset: 1px; }

    .hint { font-size: 12.5px; color: var(--ink-3); margin-top: 7px; display: flex; gap: 6px; align-items: flex-start; }
    .hint i { margin-top: 2px; font-size: 12px; }
    .field-error { font-size: 12.5px; color: var(--danger); margin-top: 6px; }

    .row-between { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin: 22px 0; }
    .check { display: inline-flex; align-items: center; gap: 9px; font-size: 13.5px; color: var(--ink-2); cursor: pointer; user-select: none; }
    .check input { accent-color: var(--accent); width: 15px; height: 15px; cursor: pointer; }
    .link { font-size: 13.5px; color: var(--ink-2); text-decoration: none; font-weight: 500; }
    .link:hover { color: var(--accent); text-decoration: underline; text-underline-offset: 3px; }

    .submit {
      width: 100%; padding: 13px; border: 0; border-radius: 10px;
      background: var(--accent); color: #fff;
      font-size: 14.5px; font-weight: 600; font-family: inherit; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: background .15s, transform .08s;
    }
    .submit:hover { background: #7183F5; }
    .submit:active { transform: translateY(1px); }
    .submit:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

    .rule { display: flex; align-items: center; gap: 14px; margin: 26px 0 18px; }
    .rule::before, .rule::after { content: ''; flex: 1; height: 1px; background: var(--line-2); }
    .rule span { font-size: 12.5px; color: var(--ink-3); }

    .secondary {
      display: flex; align-items: center; justify-content: center; gap: 8px;
      width: 100%; padding: 12px; border-radius: 10px; text-decoration: none;
      border: 1px solid var(--line); color: var(--ink); background: var(--card);
      font-size: 14px; font-weight: 600; transition: border-color .15s, background .15s;
    }
    .secondary:hover { border-color: var(--ink-3); background: #151926; color: var(--ink); }
    .footnote { text-align: center; font-size: 12.5px; color: var(--ink-3); margin-top: 12px; }

    /* alerts */
    .alert {
      display: flex; gap: 10px; align-items: flex-start;
      border-radius: 10px; padding: 11px 14px; margin-bottom: 20px; font-size: 13.5px;
      border: 1px solid; line-height: 1.5;
    }
    .alert.bad  { background: rgba(240,97,111,.08); border-color: rgba(240,97,111,.28); color: #F5A3AB; }
    .alert.info { background: var(--accent-w); border-color: rgba(97,114,243,.3); color: #A9B3FA; }
    .alert i { margin-top: 1px; }

    /* modal (student help) */
    .modal-back {
      position: fixed; inset: 0; background: rgba(6,7,12,.78); backdrop-filter: blur(3px);
      z-index: 9999; display: flex; align-items: center; justify-content: center; padding: 20px;
    }
    .modal-box {
      background: var(--panel); border: 1px solid var(--line); border-radius: 16px;
      padding: 28px; max-width: 440px; width: 100%;
      box-shadow: 0 30px 70px rgba(0,0,0,.6);
      animation: modalIn .22s ease both;
    }
    @keyframes modalIn { from { opacity: 0; transform: translateY(12px) scale(.98); } to { opacity: 1; transform: none; } }
    .modal-box h4 { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 700; color: #fff; }
    .note { border: 1px solid var(--line); border-radius: 11px; padding: 15px; text-align: left; }
    .note .t { font-weight: 600; font-size: 14px; margin-bottom: 7px; display: flex; align-items: center; gap: 8px; }
    .note p { color: var(--ink-2); font-size: 13.5px; margin: 0; }

    @media (max-width: 900px) {
      .shell { padding: 16px; }
      .frame { grid-template-columns: 1fr; min-height: 0; padding: 12px; border-radius: 22px; }
      .aside { padding: 22px 24px; }
      .tagline, .polar, .bg-math .fig, .formulas { display: none; }
      .marks, .card-dots, .card-wave, .logo::before, .logo::after { display: none; }
      .aside { flex-direction: row; align-items: center; justify-content: flex-start; }
      .aside-mid { width: auto; gap: 0; align-items: flex-start; }
      .logo img { height: 46px; margin: 0; }
      .main { padding: 26px 12px 28px; }
      .main-top { position: absolute; top: 34px; right: 30px; }
    }
  </style>
</head>
<body>

<div class="shell">

  <!-- ---------- page background: the maths sheet behind the card ---------- -->
  <div class="bg-math" aria-hidden="true">
    <div id="sym-layer"></div>
    <div class="polar"></div>

    <!-- y = sin x (drawn on load, with a point riding along it) and a dashed y = cos x -->
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

<div class="frame">

  <!-- ---------- brand panel (logo lives here) ---------- -->
  <aside class="aside">
    <div class="card-dots" aria-hidden="true"></div>

    <div class="marks" aria-hidden="true">
      <i class="tick tl"></i><i class="tick tr"></i><i class="tick bl"></i><i class="tick br"></i>
    </div>

    <svg class="card-wave" viewBox="0 0 400 40" preserveAspectRatio="none" aria-hidden="true" focusable="false">
      <line class="base" x1="0" y1="20" x2="400" y2="20"/>
      <path class="wave" d="M0 20 C 17 -2, 33 -2, 50 20 S 83 42, 100 20 S 133 -2, 150 20 S 183 42, 200 20 S 233 -2, 250 20 S 283 42, 300 20 S 333 -2, 350 20 S 383 42, 400 20"/>
    </svg>

    <div class="aside-mid">
      <a href="/" class="logo"><img src="{{ asset('images/logo.png') }}" alt="EduPlay"></a>
      <h2 class="tagline">Learn Math Through<br>Epic Games</h2>
    </div>
  </aside>

  <!-- ---------- form ---------- -->
  <main class="main">
    <div class="main-top">
      <a href="/" class="home"><i class="bi bi-house"></i> Home</a>
    </div>

    <div class="form-wrap">
      <div class="form-col">

        <div class="lead">
          <h1>Welcome back</h1>
          <p>Log in to continue where you left off.</p>
        </div>

        @if($errors->any())
        <div class="alert bad">
          <i class="bi bi-exclamation-circle"></i>
          <span>{{ $errors->first() }}</span>
        </div>
        @endif

        @if(session('info'))
        <div class="alert info">
          <i class="bi bi-info-circle"></i>
          <span>{{ session('info') }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
          @csrf

          <div class="field">
            <label class="label" for="loginField">Email or student name</label>
            <div class="control">
              <i class="lead-icon bi bi-person"></i>
              <input type="text" id="loginField" name="login" value="{{ old('login') }}"
                     class="input {{ $errors->has('login') ? 'is-invalid' : '' }}"
                     placeholder="you@school.edu" required autofocus autocomplete="username">
            </div>
            @error('login')<div class="field-error">{{ $message }}</div>@enderror
            <div class="hint"><i class="bi bi-info-circle"></i><span>Teachers and admins use their email. Students use their username or full name.</span></div>
          </div>

          <div class="field">
            <label class="label" for="pw">Password</label>
            <div class="control">
              <i class="lead-icon bi bi-lock"></i>
              <input type="password" name="password" id="pw"
                     class="input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                     placeholder="Enter your password" required autocomplete="current-password">
              <button type="button" class="toggle-pw" onclick="togglePw('pw','pwIcon')" id="pwIcon" aria-label="Show password">
                <i class="bi bi-eye"></i>
              </button>
            </div>
            @error('password')<div class="field-error">{{ $message }}</div>@enderror
            <div class="hint"><i class="bi bi-info-circle"></i><span>Students: your password is your ID number. Forgotten it? Ask your teacher.</span></div>
          </div>

          <div class="row-between">
            <label class="check">
              <input type="checkbox" name="remember">
              Remember me
            </label>
            <a href="#" onclick="handleForgotPassword(); return false;" class="link" title="Forgot Password Help">Forgot password?</a>
          </div>

          <button type="submit" class="submit">
            <i class="bi bi-box-arrow-in-right"></i> Log in
          </button>
        </form>

        <div class="rule"><span>New to EduPlay?</span></div>

        <a href="{{ route('register.form', 'teacher') }}" class="secondary">
          <i class="bi bi-easel3"></i> Create a teacher account
        </a>
        <p class="footnote">Student accounts are created by teachers.</p>

      </div>
    </div>
  </main>

</div>
</div>

<script>
/* quiet math motif behind the brand panel: single symbols and short formulas */
(function(){
  const syms=['π','∑','√','∞','÷','×','±','∆','∫','≠','²','³','=','θ','%','ƒ(x)','n!','y = mx + b','a² + b² = c²','sin θ','x² − 4 = 0','√(x + 1)','∑ n','Δy / Δx','+','−','≈','≤','≥','α','β','λ','∂','lim x→0','log₂ 8 = 3','7 × 8 = 56','2³ = 8','½','¾','0.5','∠','△','⊥','∥','∈','ℝ'];
  const layer=document.getElementById('sym-layer');
  if(!layer) return;
  for(let i=0;i<40;i++){
    const s=document.createElement('span'); s.className='sym';
    const t=syms[Math.floor(Math.random()*syms.length)];
    s.textContent=t;
    s.style.left=Math.random()*90+'%';
    s.style.top=(100+Math.random()*30)+'%';
    s.style.fontSize=(t.length>3 ? .85+Math.random()*.6 : .9+Math.random()*1.9)+'rem';
    s.style.animationDelay=Math.random()*16+'s';
    s.style.animationDuration=(18+Math.random()*16)+'s';
    layer.appendChild(s);
  }
})();

function togglePw(id, btnId) {
  const inp = document.getElementById(id);
  const btn = document.getElementById(btnId);
  const isHidden = inp.type === 'password';
  inp.type = isHidden ? 'text' : 'password';
  btn.innerHTML = isHidden ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
  btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
}

async function handleForgotPassword() {
  const loginInput = document.querySelector('input[name="login"]');
  const loginValue = loginInput.value.trim();

  if (!loginValue) {
    showErrorMessage('Enter your email or student name first.');
    loginInput.focus();
    return;
  }

  showLoadingMessage('Checking account…');

  try {
    const response = await fetch('/api/check-user-type', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
      },
      body: JSON.stringify({ login: loginValue })
    });

    const data = await response.json();
    removeMessage();

    if (data.found) {
      if (data.role === 'student') {
        showStudentHelp();
      } else {
        window.location.href = '{{ route("password.request") }}?email=' + encodeURIComponent(data.email || loginValue);
      }
    } else {
      showErrorMessage('No account matches that email or name. Check the spelling and try again.');
    }
  } catch (error) {
    removeMessage();
    showErrorMessage('The account could not be checked. Try again in a moment.');
  }
}

function showLoadingMessage(text) {
  removeMessage();
  const msg = document.createElement('div');
  msg.id = 'tempMessage';
  msg.className = 'alert info';
  msg.innerHTML = '<i class="bi bi-hourglass-split"></i><span>' + text + '</span>';
  document.querySelector('form').insertAdjacentElement('beforebegin', msg);
}

function showErrorMessage(text) {
  removeMessage();
  const msg = document.createElement('div');
  msg.id = 'tempMessage';
  msg.className = 'alert bad';
  msg.innerHTML = '<i class="bi bi-exclamation-circle"></i><span>' + text + '</span>';
  document.querySelector('form').insertAdjacentElement('beforebegin', msg);
}

function removeMessage() {
  const existing = document.getElementById('tempMessage');
  if (existing) existing.remove();
}

function showStudentHelp() {
  const helpMsg = `
    <div>
      <h4 style="margin-bottom:6px;">Student account</h4>
      <p style="color:var(--ink-3);font-size:13.5px;margin-bottom:18px;">Password reset happens in class, not online.</p>

      <div class="note" style="border-color:rgba(240,97,111,.28);background:rgba(240,97,111,.06);">
        <div class="t" style="color:#F5A3AB;"><i class="bi bi-shield-lock"></i>Your password is your student ID</div>
        <p>Student accounts do not use the online reset form. The password set for you is your Student ID Number.</p>
      </div>

      <div class="note" style="margin-top:12px;border-color:rgba(47,189,156,.28);background:rgba(47,189,156,.06);">
        <div class="t" style="color:#5FD3B8;"><i class="bi bi-person-raised-hand"></i>Ask your teacher</div>
        <p>Your teacher can look up or reset it for you from the Student Management section.</p>
      </div>
    </div>
  `;

  const overlay = document.createElement('div');
  overlay.className = 'modal-back';
  overlay.style.cssText = 'position:fixed;inset:0;';

  const modal = document.createElement('div');
  modal.className = 'modal-box';

  modal.innerHTML = helpMsg + `
    <button onclick="this.closest('[style*=fixed]').remove()" class="submit" style="margin-top:20px;">
      <i class="bi bi-check2"></i> Got it
    </button>
  `;

  overlay.appendChild(modal);
  document.body.appendChild(overlay);

  overlay.addEventListener('click', (e) => { if (e.target === overlay) overlay.remove(); });

  const escHandler = (e) => {
    if (e.key === 'Escape') {
      overlay.remove();
      document.removeEventListener('keydown', escHandler);
    }
  };
  document.addEventListener('keydown', escHandler);
}
</script>
</body>
</html>