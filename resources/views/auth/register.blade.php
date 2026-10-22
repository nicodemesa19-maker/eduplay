<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register — EduPlay</title>
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
      position: relative; z-index: 2; width: 100%; max-width: 1120px; min-height: 700px;
      display: grid; grid-template-columns: .9fr 1.1fr; gap: 8px;
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

    .form-wrap { flex: 1; display: flex; align-items: center; justify-content: center; padding-top: 6px; }
    .form-col { width: 100%; max-width: 560px; }

    .lead { text-align: center; margin-bottom: 26px; }
    .role-badge {
      display: inline-flex; align-items: center; gap: 7px; margin-bottom: 14px;
      border-radius: 999px; padding: 6px 14px; font-size: 13px; font-weight: 600;
    }
    .role-badge.student { background: rgba(47,189,156,.10); border: 1px solid rgba(47,189,156,.30); color: #5FD3B8; }
    .role-badge.teacher { background: var(--accent-w);      border: 1px solid rgba(97,114,243,.30); color: #A9B3FA; }
    .auth-title {
      font-family: 'Space Grotesk', sans-serif; font-size: 27px; font-weight: 700;
      letter-spacing: -.02em; color: #fff; margin-bottom: 6px;
    }
    .auth-sub { color: var(--ink-3); font-size: 14px; }

    .two-col   { display: grid; grid-template-columns: 1fr 1fr; gap: 0 18px; align-items: start; }
    .three-col { display: grid; grid-template-columns: 2fr 1.3fr 2fr; gap: 14px; }
    .form-group { margin-bottom: 16px; }
    .form-label {
      display: block; font-size: 13px; font-weight: 600; color: var(--ink-2);
      margin-bottom: 7px; text-transform: none; letter-spacing: 0;
    }
    .input-wrap { position: relative; display: flex; align-items: center; }
    .input-wrap .icon {
      position: absolute; left: 13px; color: var(--ink-3); font-size: 14px; pointer-events: none; z-index: 2;
    }
    .form-control-dark {
      width: 100%; padding: 12px 14px 12px 38px;
      background: var(--surface); border: 1px solid var(--line); border-radius: 10px;
      color: var(--ink); font-size: 14.5px; font-family: inherit;
      outline: none; transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .form-control-dark.no-icon { padding-left: 14px; }
    .form-control-dark::placeholder { color: var(--ink-3); }
    .form-control-dark:hover { border-color: #2C3142; }
    .form-control-dark:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-w); background: #151926; }
    .form-control-dark.is-invalid { border-color: var(--danger); }
    .form-control-dark.is-invalid:focus { box-shadow: 0 0 0 3px rgba(240,97,111,.14); }
    /* keep browser autofill on-theme instead of the white block */
    .form-control-dark:-webkit-autofill,
    .form-control-dark:-webkit-autofill:hover,
    .form-control-dark:-webkit-autofill:focus {
      -webkit-text-fill-color: var(--ink);
      -webkit-box-shadow: 0 0 0 1000px #151926 inset;
      caret-color: var(--ink);
      transition: background-color 9999s ease-out 0s;
    }
    #pw, #pwc { padding-right: 44px; }

    .toggle-pw {
      position: absolute; right: 6px; background: none; border: 0;
      color: var(--ink-3); cursor: pointer; font-size: 15px; padding: 0;
      width: 32px; height: 32px; border-radius: 7px; z-index: 3;
      display: flex; align-items: center; justify-content: center;
      transition: color .15s, background .15s;
    }
    .toggle-pw:hover { color: var(--ink); background: rgba(255,255,255,.05); }
    .toggle-pw:focus-visible { outline: 2px solid var(--accent); outline-offset: 1px; }
    .field-error { font-size: 12.5px; color: var(--danger); margin-top: 6px; }

    /* password strength meter (the script fills these in) */
    #pw-strength-bar  { height: 4px; border-radius: 4px; margin-top: 9px; background: rgba(255,255,255,.07); overflow: hidden; }
    #pw-strength-fill { height: 100%; width: 0%; border-radius: 4px; transition: width .3s, background .3s; }
    #pw-strength-text { font-size: 12.5px; margin-top: 6px; color: var(--ink-3); min-height: 1.4em; }

    /* select */
    select.form-control-dark {
      appearance: none; -webkit-appearance: none; cursor: pointer; padding-right: 36px;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23656D7F' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
      background-repeat: no-repeat; background-position: right 14px center;
    }
    select.form-control-dark option { background: #151926; color: var(--ink); }

    /* file upload */
    .file-zone {
      display: block; width: 100%; padding: 18px; text-align: center; cursor: pointer;
      background: var(--surface); border: 1px dashed #2C3142; border-radius: 10px;
      color: var(--ink-3); font-size: 13.5px;
      transition: border-color .15s, background .15s, color .15s;
    }
    .file-zone:hover { border-color: var(--accent); background: #151926; color: #A9B3FA; }
    .file-zone input { display: none; }
    .file-zone i { font-size: 1.4rem; display: block; margin-bottom: 6px; color: var(--accent); }
    .file-zone .file-name { font-size: 12.5px; color: #5FD3B8; margin-top: 6px; display: none; }

    /* submit */
    .btn-submit {
      width: 100%; padding: 13px; border: 0; border-radius: 10px; margin-top: 6px;
      color: #fff; font-size: 14.5px; font-weight: 600; font-family: inherit; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      transition: background .15s, transform .08s;
    }
    .btn-submit.student { background: #22A98A; }
    .btn-submit.student:hover { background: #2BBD9B; }
    .btn-submit.teacher { background: var(--accent); }
    .btn-submit.teacher:hover { background: #7183F5; }
    .btn-submit:active { transform: translateY(1px); }
    .btn-submit:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; }

    .login-row { text-align: center; margin-top: 20px; font-size: 13.5px; color: var(--ink-3); }
    .login-row a { color: var(--ink); font-weight: 600; text-decoration: none; }
    .login-row a:hover { color: var(--accent); text-decoration: underline; text-underline-offset: 3px; }

    /* error alert */
    .alert-dark {
      background: rgba(240,97,111,.08); border: 1px solid rgba(240,97,111,.28);
      border-radius: 10px; padding: 11px 14px; margin-bottom: 20px;
      font-size: 13.5px; line-height: 1.5; color: #F5A3AB;
    }
    .alert-dark ul { margin: 6px 0 0 18px; }
    .alert-dark li { margin-bottom: 3px; }

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
    @media (max-width: 480px) { .two-col, .three-col { grid-template-columns: 1fr; } }
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
      <a href="{{ url('/') }}" class="home"><i class="bi bi-house"></i> Home</a>
    </div>

    <div class="form-wrap">
      <div class="form-col">

        <div class="lead">
          <div class="role-badge {{ $role }}">
            <i class="bi {{ $role === 'student' ? 'bi-joystick' : 'bi-easel3-fill' }}"></i>
            {{ ucfirst($role) }} account
          </div>
          <h1 class="auth-title">Create your account</h1>
          <p class="auth-sub">Join EduPlay and start your math adventure.</p>
        </div>

        @if($errors->any())
        <div class="alert-dark">
          <i class="bi bi-exclamation-triangle me-1"></i> Please fix the following:
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="role" value="{{ $role }}">

          {{-- 2-col grid: 3 fields per column --}}
          <div class="two-col">

            {{-- Col 1 --}}
            <div class="form-group">
              <label class="form-label">First name</label>
              <div class="input-wrap">
                <i class="icon bi bi-person-circle"></i>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                       class="form-control-dark {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                       placeholder="First name" autocomplete="given-name">
              </div>
              @error('first_name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Col 2 --}}
            <div class="form-group">
              <label class="form-label">Middle name <span style="color:var(--ink-3);font-weight:400;">(optional)</span></label>
              <div class="input-wrap">
                <i class="icon bi bi-person"></i>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                       class="form-control-dark {{ $errors->has('middle_name') ? 'is-invalid' : '' }}"
                       placeholder="Middle name" autocomplete="additional-name">
              </div>
              @error('middle_name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Col 1 --}}
            <div class="form-group">
              <label class="form-label">Last name</label>
              <div class="input-wrap">
                <i class="icon bi bi-person-circle"></i>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                       class="form-control-dark {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                       placeholder="Last name" autocomplete="family-name">
              </div>
              @error('last_name')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Col 2 --}}
            <div class="form-group">
              <label class="form-label">Email</label>
              <div class="input-wrap">
                <i class="icon bi bi-envelope-fill"></i>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="form-control-dark {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       placeholder="you@example.com" autocomplete="email">
              </div>
              @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            {{-- Col 1 --}}
            <div class="form-group">
              <label class="form-label">Password</label>
              <div class="input-wrap">
                <i class="icon bi bi-lock"></i>
                <input type="password" name="password" id="pw" required
                       class="form-control-dark {{ $errors->has('password') ? 'is-invalid' : '' }}"
                       placeholder="Min. 8 characters">
                <button type="button" class="toggle-pw" onclick="togglePw('pw','pwi')"><i class="bi bi-eye" id="pwi"></i></button>
              </div>
              @error('password')<div class="field-error">{{ $message }}</div>@enderror
              <div id="pw-strength-bar"><div id="pw-strength-fill"></div></div>
              <div id="pw-strength-text"></div>
            </div>

            {{-- Col 2 --}}
            <div class="form-group">
              <label class="form-label">Confirm password</label>
              <div class="input-wrap">
                <i class="icon bi bi-lock-fill"></i>
                <input type="password" name="password_confirmation" id="pwc" required
                       class="form-control-dark" placeholder="Repeat password">
                <button type="button" class="toggle-pw" onclick="togglePw('pwc','pwci')"><i class="bi bi-eye" id="pwci"></i></button>
              </div>
            </div>

          </div>{{-- /two-col --}}

          {{-- File upload: full width --}}
          @if($role === 'student')
          <div class="form-group">
            <label class="form-label">School ID picture</label>
            <label class="file-zone" id="fileZone">
              <input type="file" name="school_id" accept="image/*" required onchange="showFile(this)">
              <i class="bi bi-cloud-arrow-up-fill"></i>
              Click to upload your school ID
              <div class="file-name" id="fileName"></div>
            </label>
            @error('school_id')<div class="field-error">{{ $message }}</div>@enderror
          </div>
          @else
          <div class="form-group">
            <label class="form-label">Faculty ID picture</label>
            <label class="file-zone" id="fileZone">
              <input type="file" name="faculty_id" accept="image/*" required onchange="showFile(this)">
              <i class="bi bi-cloud-arrow-up-fill"></i>
              Click to upload your faculty ID
              <div class="file-name" id="fileName"></div>
            </label>
            @error('faculty_id')<div class="field-error">{{ $message }}</div>@enderror
          </div>
          @endif

          <button type="submit" class="btn-submit {{ $role }}">
            <i class="bi bi-rocket-takeoff-fill"></i> Create account
          </button>
        </form>

        <div class="login-row">
          Already have an account? <a href="{{ route('login') }}">Log in</a>
        </div>

      </div>
    </div>
  </main>

</div>
</div>

<script>
/* quiet math motif behind the page: single symbols and short formulas */
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

function togglePw(id, iconId) {
  const inp = document.getElementById(id);
  const ico = document.getElementById(iconId);
  const hide = inp.type === 'password';
  inp.type = hide ? 'text' : 'password';
  ico.className = hide ? 'bi bi-eye-slash' : 'bi bi-eye';
}

// Password strength
document.getElementById('pw').addEventListener('input', function() {
  const v = this.value;
  let score = 0;
  const checks = [
    v.length >= 8,
    /[A-Z]/.test(v),
    /[a-z]/.test(v),
    /\d/.test(v),
    /[\W_]/.test(v),
  ];
  score = checks.filter(Boolean).length;
  const fill = document.getElementById('pw-strength-fill');
  const text = document.getElementById('pw-strength-text');
  const levels = [
    { w:'0%',   color:'transparent', label:'' },
    { w:'20%',  color:'#ef4444',     label:'Very weak' },
    { w:'40%',  color:'#f97316',     label:'Weak' },
    { w:'60%',  color:'#eab308',     label:'Fair' },
    { w:'80%',  color:'#84cc16',     label:'Strong' },
    { w:'100%', color:'#10b981',     label:'Very strong ✓' },
  ];
  const l = levels[score];
  fill.style.width = l.w;
  fill.style.background = l.color;
  text.textContent = l.label;
  text.style.color = l.color;
});

function showFile(input) {
  const fn = document.getElementById('fileName');
  if(input.files && input.files[0]) {
    fn.textContent = '✓ ' + input.files[0].name;
    fn.style.display = 'block';
  }
}
</script>
</body>
</html>