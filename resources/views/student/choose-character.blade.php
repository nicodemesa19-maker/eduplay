@extends('layouts.app')
@section('content')

{{-- ═══════════════════════════════════════════════════════════
     CHOOSE CHARACTER  —  Arcade Theme
     ═══════════════════════════════════════════════════════════ --}}

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
@import url('https://fonts.googleapis.com/css2?family=Bungee&family=Baloo+2:wght@500;600;700;800&family=Space+Mono:wght@400;700&display=swap');

/* Arcade Theme - Matching Dashboard */
*, *::before, *::after { box-sizing: border-box; }

body {
  background: #0E0F26 !important;
  font-family: 'Baloo 2', sans-serif !important;
  color: #EAF6FF !important;
  overflow-x: hidden;
  background-image: repeating-linear-gradient(180deg,rgba(255,255,255,.015) 0 2px,transparent 2px 4px);
}
body > main.container { padding: 0 !important; max-width: 100% !important; }

/* ── Arcade Variables ── */
:root {
  --bg: #0E0F26;
  --bg2: #171A3D;
  --surface: rgba(255,255,255,.04);
  --border: rgba(0,229,255,.22);
  --border2: rgba(255,255,255,.08);
  --cyan: #00E5FF;
  --pink: #FF3DAE;
  --lime: #B6FF3D;
  --purple: #B026FF;
  --yellow: #FFD23D;
  --text: #EAF6FF;
  --text2: #ffffff;
  --muted: rgba(234,246,255,.55);
  --muted2: rgba(234,246,255,.3);
  --card-bg: rgba(255,255,255,.045);
  --card-border: rgba(0,229,255,.16);
  --glow-cyan: 0 0 22px rgba(0,229,255,.35);
  --glow-pink: 0 0 22px rgba(255,61,174,.32);
  --glow-lime: 0 0 22px rgba(182,255,61,.3);
  --glow-purple: 0 0 22px rgba(176,38,255,.3);
}

/* ═══════════════════════════════════════
   FLOATING MATH SYMBOLS
   ═══════════════════════════════════════ */
.math-bg {
  position: fixed;
  inset: 0;
  pointer-events: none;
  overflow: hidden;
  z-index: 0;
  user-select: none;
}

.sym-float {
  position: fixed;
  font-family: 'Space Mono', monospace;
  font-weight: 700;
  pointer-events: none;
  user-select: none;
  z-index: 0;
  line-height: 1;
  opacity: .18;
}

@keyframes drift1 {
  0%,100% { transform: translateY(0px) rotate(0deg) scale(1); }
  25% { transform: translateY(-48px) rotate(18deg) scale(1.08); }
  50% { transform: translateY(-22px) rotate(-12deg) scale(0.93); }
  75% { transform: translateY(-60px) rotate(24deg) scale(1.10); }
}
@keyframes drift2 {
  0%,100% { transform: translateY(0px) rotate(0deg) scale(1); }
  33% { transform: translateY(-52px) rotate(-22deg) scale(1.07); }
  66% { transform: translateY(-28px) rotate(16deg) scale(0.95); }
}
@keyframes drift3 {
  0%,100% { transform: translateY(0px) rotate(0deg) scale(1); }
  20% { transform: translateY(-32px) rotate(10deg) scale(1.05); }
  60% { transform: translateY(-58px) rotate(-18deg) scale(1.09); }
  80% { transform: translateY(-24px) rotate(9deg) scale(0.96); }
}
@keyframes spinDrift {
  0% { transform: translateY(0px) rotate(0deg); }
  100% { transform: translateY(-42px) rotate(360deg); }
}
@keyframes bounceDrift {
  0%,100% { transform: translateY(0px) scale(1); }
  50% { transform: translateY(-55px) scale(1.12); }
}

/* ═══════════════════════════════════════
   PAGE HEADER — Arcade style
   ═══════════════════════════════════════ */
.page-header {
  position: relative;
  overflow: hidden;
  padding: 38px 0 0;
  background: radial-gradient(circle at 20% 20%, rgba(176,38,255,.18), transparent 55%),
              radial-gradient(circle at 85% 30%, rgba(0,229,255,.16), transparent 55%),
              var(--bg2);
  border-bottom: 3px solid var(--cyan);
  box-shadow: 0 4px 0 rgba(0,229,255,.15);
}

.page-header-eyebrow {
  font-family: 'Space Mono', monospace;
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--cyan);
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 12px;
}

.page-header-title {
  font-family: 'Bungee', sans-serif;
  font-size: clamp(2rem, 5.5vw, 3.2rem);
  color: var(--text2);
  line-height: 1.2;
  text-shadow: 0 0 18px rgba(0,229,255,.4), 3px 3px 0 rgba(255,61,174,.35);
  margin: 8px 0;
}

.page-header-sub {
  font-family: 'Baloo 2', sans-serif;
  font-size: 1.05rem;
  color: var(--muted);
  margin-top: 10px;
  line-height: 1.6;
  font-weight: 700;
}

/* Scrolling equation ticker */
.equation-strip {
  background: rgba(0,0,0,.3);
  border-top: 2px solid var(--purple);
  margin-top: 28px;
  padding: 11px 0;
  overflow: hidden;
  white-space: nowrap;
}

.equation-track {
  display: inline-block;
  animation: marqueeScroll 25s linear infinite;
  font-family: 'Space Mono', monospace;
  font-size: 0.84rem;
  color: var(--lime);
  letter-spacing: 1px;
  font-weight: 700;
}

@keyframes marqueeScroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

/* ═══════════════════════════════════════
   CONTENT AREA & PAGE WRAPPER
   ═══════════════════════════════════════ */
.page-wrap { position: relative; z-index: 1; }
.content-area { background: var(--bg); padding: 44px 0 75px; }

/* Back button - arcade style */
.back-btn {
  background: rgba(0,0,0,.3);
  border: 2px solid var(--lime);
  border-radius: 8px;
  padding: 6px 16px;
  font-family: 'Space Mono', monospace;
  font-size: 0.78rem;
  font-weight: 700;
  color: var(--lime);
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: opacity .3s;
  box-shadow: var(--glow-lime);
}

.back-btn:hover {
  opacity: .8;
  color: var(--lime);
  text-decoration: none;
}

/* ═══════════════════════════════════════
   CARD ENTRANCE ANIMATIONS (stagger)
   ═══════════════════════════════════════ */
@keyframes fadeUpIn {
  from { opacity: 0; transform: translateY(36px) scale(0.96); }
  to   { opacity: 1; transform: translateY(0px)  scale(1);    }
}
.char-col {
  opacity: 0;
  animation: fadeUpIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}
.char-col:nth-child(1)  { animation-delay: 0.05s; }
.char-col:nth-child(2)  { animation-delay: 0.12s; }
.char-col:nth-child(3)  { animation-delay: 0.19s; }
.char-col:nth-child(4)  { animation-delay: 0.26s; }
.char-col:nth-child(5)  { animation-delay: 0.33s; }
.char-col:nth-child(6)  { animation-delay: 0.40s; }
.char-col:nth-child(7)  { animation-delay: 0.47s; }
.char-col:nth-child(8)  { animation-delay: 0.54s; }
.char-col:nth-child(9)  { animation-delay: 0.61s; }
.char-col:nth-child(10) { animation-delay: 0.68s; }

/* ═══════════════════════════════════════
   CHARACTER CARDS - Arcade style
   ═══════════════════════════════════════ */
.char-card {
  background: var(--card-bg);
  border: 2px solid var(--card-border);
  border-radius: 16px;
  overflow: hidden;
  height: 100%;
  cursor: pointer;
  transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.3s, box-shadow 0.3s;
  will-change: transform;
}

/* Per-color hover glow - arcade colors */
.char-card.color-yellow:hover { transform: translateY(-6px) scale(1.01); border-color: var(--yellow); box-shadow: 0 0 0 2px var(--yellow), 0 16px 36px rgba(255,210,61,0.25); }
.char-card.color-blue:hover { transform: translateY(-6px) scale(1.01); border-color: var(--cyan); box-shadow: 0 0 0 2px var(--cyan), 0 16px 36px rgba(0,229,255,0.25); }
.char-card.color-green:hover { transform: translateY(-6px) scale(1.01); border-color: var(--lime); box-shadow: 0 0 0 2px var(--lime), 0 16px 36px rgba(182,255,61,0.25); }
.char-card.color-purple:hover { transform: translateY(-6px) scale(1.01); border-color: var(--purple); box-shadow: 0 0 0 2px var(--purple), 0 16px 36px rgba(176,38,255,0.25); }
.char-card.color-pink:hover { transform: translateY(-6px) scale(1.01); border-color: var(--pink); box-shadow: 0 0 0 2px var(--pink), 0 16px 36px rgba(255,61,174,0.25); }
.char-card.color-red:hover { transform: translateY(-6px) scale(1.01); border-color: #EF4444; box-shadow: 0 0 0 2px #EF4444, 0 16px 36px rgba(239,68,68,0.25); }

/* Selected state with pulsing border - arcade style */
@keyframes selPulseYellow { 0%,100% { box-shadow: 0 0 0 3px var(--yellow), var(--glow-lime); } 50% { box-shadow: 0 0 0 5px var(--yellow), 0 0 40px rgba(255,210,61,0.5); } }
@keyframes selPulseBlue { 0%,100% { box-shadow: 0 0 0 3px var(--cyan), var(--glow-cyan); } 50% { box-shadow: 0 0 0 5px var(--cyan), 0 0 40px rgba(0,229,255,0.5); } }
@keyframes selPulseGreen { 0%,100% { box-shadow: 0 0 0 3px var(--lime), var(--glow-lime); } 50% { box-shadow: 0 0 0 5px var(--lime), 0 0 40px rgba(182,255,61,0.5); } }
@keyframes selPulsePurple { 0%,100% { box-shadow: 0 0 0 3px var(--purple), var(--glow-purple); } 50% { box-shadow: 0 0 0 5px var(--purple), 0 0 40px rgba(176,38,255,0.5); } }
@keyframes selPulsePink { 0%,100% { box-shadow: 0 0 0 3px var(--pink), var(--glow-pink); } 50% { box-shadow: 0 0 0 5px var(--pink), 0 0 40px rgba(255,61,174,0.5); } }
@keyframes selPulseRed { 0%,100% { box-shadow: 0 0 0 3px #EF4444, 0 0 22px rgba(239,68,68,.3); } 50% { box-shadow: 0 0 0 5px #EF4444, 0 0 40px rgba(239,68,68,0.5); } }

.char-card.is-selected.color-yellow { border-color: var(--yellow) !important; animation: selPulseYellow 2s ease-in-out infinite; }
.char-card.is-selected.color-blue { border-color: var(--cyan) !important; animation: selPulseBlue 2s ease-in-out infinite; }
.char-card.is-selected.color-green { border-color: var(--lime) !important; animation: selPulseGreen 2s ease-in-out infinite; }
.char-card.is-selected.color-purple { border-color: var(--purple) !important; animation: selPulsePurple 2s ease-in-out infinite; }
.char-card.is-selected.color-pink { border-color: var(--pink) !important; animation: selPulsePink 2s ease-in-out infinite; }
.char-card.is-selected.color-red { border-color: #EF4444 !important; animation: selPulseRed 2s ease-in-out infinite; }

/* ── Card color header tops - arcade gradients ── */
.card-header-zone {
  position: relative;
  text-align: center;
  padding-bottom: 16px;
}

.card-header-zone::before {
  content: '✦ ✦ ✦';
  position: absolute;
  top: 10px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 0.55rem;
  color: rgba(255,255,255,0.28);
  letter-spacing: 6px;
  pointer-events: none;
}

.card-header-zone::after {
  content: '';
  position: absolute;
  inset: 0;
  background-image: repeating-linear-gradient(180deg, rgba(0,0,0,.08) 0 2px, transparent 2px 4px);
  pointer-events: none;
}

.ctop-yellow { background: linear-gradient(160deg, #92400e 0%, #b45309 40%, #d97706 100%); }
.ctop-blue { background: linear-gradient(160deg, #0E3A5F 0%, #006BA6 50%, var(--cyan) 100%); }
.ctop-green { background: linear-gradient(160deg, #3F6212 0%, #65A30D 50%, var(--lime) 100%); }
.ctop-purple { background: linear-gradient(160deg, #4c1d95 0%, #6d28d9 50%, var(--purple) 100%); }
.ctop-pink { background: linear-gradient(160deg, #831843 0%, #be185d 50%, var(--pink) 100%); }
.ctop-red { background: linear-gradient(160deg, #7f1d1d 0%, #b91c1c 50%, #dc2626 100%); }

/* ── Character image button ── */
.char-img-btn {
  background: none;
  border: none;
  cursor: pointer;
  padding: 30px 20px 14px;
  display: block;
  width: 100%;
  position: relative;
}

.char-img-btn img {
  width: 195px;
  height: 195px;
  object-fit: contain;
  transition: transform 0.38s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.3s;
  filter: drop-shadow(0 12px 28px rgba(0,0,0,0.65));
  display: block;
  margin: 0 auto;
}

.char-card:hover .char-img-btn img {
  transform: scale(1.12) translateY(-8px);
  filter: drop-shadow(0 18px 38px rgba(0,0,0,0.85)) brightness(1.1);
}

/* Click-to-select overlay hint */
.select-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.2s;
  pointer-events: none;
}

.select-overlay span {
  background: rgba(0,0,0,0.72);
  color: #fff;
  font-family: 'Space Mono', monospace;
  font-size: 0.75rem;
  font-weight: 700;
  padding: 7px 18px;
  border-radius: 50px;
  backdrop-filter: blur(5px);
  letter-spacing: 0.3px;
  border: 1px solid var(--cyan);
  box-shadow: var(--glow-cyan);
}

.char-card:hover .select-overlay { opacity: 1; }

/* ── Selected checkmark badge ── */
.check-badge {
  position: absolute;
  top: 14px;
  right: 14px;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.05rem;
  color: #0E0F26;
  z-index: 5;
  box-shadow: 0 4px 18px rgba(0,0,0,0.45);
  font-weight: 900;
}

.badge-yellow { background: var(--yellow); }
.badge-blue { background: var(--cyan); }
.badge-green { background: var(--lime); }
.badge-purple { background: var(--purple); }
.badge-pink { background: var(--pink); }
.badge-red { background: #EF4444; }

/* ── Name area below image ── */
.char-name-area { padding: 0 18px 18px; }

.char-name { 
  font-family: 'Bungee', sans-serif;
  font-size: 1.05rem;
  color: #fff;
  margin-bottom: 3px;
  line-height: 1.2;
}

.char-spec { 
  font-family: 'Baloo 2', sans-serif;
  font-size: 0.8rem;
  color: var(--muted);
  font-weight: 600;
}

.selected-pill {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  border-radius: 50px;
  padding: 4px 13px;
  font-family: 'Space Mono', monospace;
  font-size: 0.7rem;
  font-weight: 700;
  color: #0E0F26;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  margin-top: 7px;
}

.pill-yellow { background: var(--yellow); }
.pill-blue { background: var(--cyan); }
.pill-green { background: var(--lime); }
.pill-purple { background: var(--purple); }
.pill-pink { background: var(--pink); }
.pill-red { background: #EF4444; }

/* ── Info section ── */
.char-info { 
  padding: 18px 20px;
  border-top: 1px solid rgba(255,255,255,0.07);
  background: rgba(0,0,0,.15);
}

.info-row {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-family: 'Baloo 2', sans-serif;
  font-size: 0.82rem;
  color: var(--muted);
  margin-bottom: 10px;
  line-height: 1.45;
}

.info-row:last-of-type { margin-bottom: 0; }
.info-row i { font-size: 0.92rem; flex-shrink: 0; margin-top: 1px; }
.info-row strong { color: var(--text); font-weight: 700; }

.char-quote {
  font-family: 'Baloo 2', sans-serif;
  font-style: italic;
  font-size: 0.8rem;
  color: var(--muted);
  border-left: 3px solid var(--cyan);
  padding-left: 10px;
  margin-top: 13px;
  margin-bottom: 0;
  line-height: 1.55;
}

/* Success alert */
.success-alert {
  background: rgba(182,255,61,0.13);
  border: 2px solid var(--lime);
  color: var(--lime);
  border-radius: 12px;
  padding: 16px 22px;
  margin-bottom: 30px;
  display: flex;
  align-items: center;
  gap: 12px;
  animation: alertFadeIn 0.5s ease-out;
  box-shadow: var(--glow-lime);
  font-family: 'Baloo 2', sans-serif;
  font-weight: 700;
}

@keyframes alertFadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>

{{-- ═══════════════════════════════════════
     FLOATING MATH SYMBOLS — PROMINENT
     ═══════════════════════════════════════ --}}
<div class="math-bg" aria-hidden="true"></div>

{{-- Left column symbols --}}
<span class="sym-float" style="color:var(--purple);font-size:3.8rem;top:7%;left:1.5%;animation:drift1 7s ease-in-out infinite;animation-delay:0s;">π</span>
<span class="sym-float" style="color:var(--cyan);font-size:3rem;top:19%;left:3%;animation:drift2 9s ease-in-out infinite;animation-delay:1s;">∑</span>
<span class="sym-float" style="color:var(--lime);font-size:4.2rem;top:37%;left:0.8%;animation:drift3 8s ease-in-out infinite;animation-delay:2s;">√</span>
<span class="sym-float" style="color:var(--yellow);font-size:3.4rem;top:55%;left:2%;animation:bounceDrift 6s ease-in-out infinite;animation-delay:0.5s;">∞</span>
<span class="sym-float" style="color:var(--pink);font-size:2.6rem;top:72%;left:1%;animation:drift1 10s ease-in-out infinite;animation-delay:3s;">÷</span>
<span class="sym-float" style="color:#F97316;font-size:3.2rem;top:85%;left:3%;animation:drift2 7.5s ease-in-out infinite;animation-delay:1.5s;">+</span>
<span class="sym-float" style="color:var(--pink);font-size:2.8rem;top:95%;left:2%;animation:drift3 9s ease-in-out infinite;animation-delay:4s;">×</span>

{{-- Right column symbols --}}
<span class="sym-float" style="color:var(--purple);font-size:3.4rem;top:4%;right:2%;animation:drift2 8.5s ease-in-out infinite;animation-delay:1.5s;">×</span>
<span class="sym-float" style="color:var(--cyan);font-size:4rem;top:21%;right:0.8%;animation:drift1 7.5s ease-in-out infinite;animation-delay:2.5s;">∫</span>
<span class="sym-float" style="color:var(--lime);font-size:3rem;top:39%;right:2.5%;animation:bounceDrift 9s ease-in-out infinite;animation-delay:0s;">±</span>
<span class="sym-float" style="color:var(--yellow);font-size:3.6rem;top:56%;right:1%;animation:drift3 6.5s ease-in-out infinite;animation-delay:4s;">∆</span>
<span class="sym-float" style="color:var(--pink);font-size:2.6rem;top:73%;right:2%;animation:drift1 8s ease-in-out infinite;animation-delay:1s;">≠</span>
<span class="sym-float" style="color:#F97316;font-size:3.2rem;top:87%;right:1.5%;animation:drift2 9.5s ease-in-out infinite;animation-delay:2.2s;">−</span>

{{-- Mid-left symbols --}}
<span class="sym-float" style="color:var(--purple);font-size:2.4rem;top:13%;left:10%;animation:spinDrift 8s ease-in-out infinite;animation-delay:2s;">²</span>
<span class="sym-float" style="color:var(--cyan);font-size:2.8rem;top:46%;left:8%;animation:drift3 9s ease-in-out infinite;animation-delay:3.5s;">³</span>
<span class="sym-float" style="color:var(--lime);font-size:2.2rem;top:79%;left:7%;animation:drift1 7s ease-in-out infinite;animation-delay:1.5s;">x²</span>
<span class="sym-float" style="color:var(--yellow);font-size:2.6rem;top:30%;left:14%;animation:bounceDrift 8s ease-in-out infinite;animation-delay:0.8s;">=</span>
<span class="sym-float" style="color:var(--pink);font-size:2.2rem;top:63%;left:11%;animation:drift2 6.5s ease-in-out infinite;animation-delay:2.8s;">0</span>

{{-- Mid-right symbols --}}
<span class="sym-float" style="color:var(--pink);font-size:2.4rem;top:11%;right:12%;animation:drift3 7.5s ease-in-out infinite;animation-delay:1.2s;">μ</span>
<span class="sym-float" style="color:var(--purple);font-size:2.8rem;top:33%;right:9%;animation:drift1 8.5s ease-in-out infinite;animation-delay:0.3s;">−</span>
<span class="sym-float" style="color:var(--cyan);font-size:2.2rem;top:60%;right:7%;animation:bounceDrift 9.5s ease-in-out infinite;animation-delay:3s;">7</span>
<span class="sym-float" style="color:var(--lime);font-size:2.4rem;top:91%;right:9%;animation:drift2 7s ease-in-out infinite;animation-delay:4.5s;">9</span>
<span class="sym-float" style="color:var(--yellow);font-size:2.2rem;top:49%;right:14%;animation:drift3 10s ease-in-out infinite;animation-delay:1.8s;">∂</span>
<span class="sym-float" style="color:#F97316;font-size:2.6rem;top:23%;right:16%;animation:drift1 9s ease-in-out infinite;animation-delay:0.6s;">θ</span>

<div class="page-wrap">

{{-- ═══════════════════════════════════════
     PAGE HEADER
     ═══════════════════════════════════════ --}}
<div class="page-header">
  <div class="container-lg px-4">
    <div class="text-center">
      <div class="page-header-eyebrow">
        <i class="bi bi-stars me-2"></i>Math Warriors Academy<i class="bi bi-stars ms-2"></i>
      </div>
      <h1 class="page-header-title">
        <i class="bi bi-person-badge-fill me-3" style="color:var(--cyan);"></i>
        Choose Your Hero
      </h1>
      <p class="page-header-sub">
        Pick the math warrior that matches your superpower style.<br>
        <span style="font-size:0.87rem;color:var(--cyan);">
          <i class="bi bi-cursor-fill me-1"></i>
          Click a portrait to claim your champion!
        </span>
      </p>
    </div>
  </div>

  {{-- Scrolling equation ticker --}}
  <div class="equation-strip">
    <span class="equation-track">
      &nbsp;&nbsp;&nbsp;f(x) = ax² + bx + c &nbsp;&bull;&nbsp; E = mc² &nbsp;&bull;&nbsp; a² + b² = c² &nbsp;&bull;&nbsp; P(A) = n(A)/n(S) &nbsp;&bull;&nbsp; ∑(n) = n(n+1)/2 &nbsp;&bull;&nbsp; x = (-b ± √Δ) / 2a &nbsp;&bull;&nbsp; V = πr²h &nbsp;&bull;&nbsp; sin²θ + cos²θ = 1 &nbsp;&bull;&nbsp; A = ½bh &nbsp;&bull;&nbsp; C = 2πr &nbsp;&bull;&nbsp; F = ma &nbsp;&bull;&nbsp; d = rt &nbsp;&bull;&nbsp; A = πr² &nbsp;&bull;&nbsp;
      &nbsp;&nbsp;&nbsp;f(x) = ax² + bx + c &nbsp;&bull;&nbsp; E = mc² &nbsp;&bull;&nbsp; a² + b² = c² &nbsp;&bull;&nbsp; P(A) = n(A)/n(S) &nbsp;&bull;&nbsp; ∑(n) = n(n+1)/2 &nbsp;&bull;&nbsp; x = (-b ± √Δ) / 2a &nbsp;&bull;&nbsp; V = πr²h &nbsp;&bull;&nbsp; sin²θ + cos²θ = 1 &nbsp;&bull;&nbsp; A = ½bh &nbsp;&bull;&nbsp; C = 2πr &nbsp;&bull;&nbsp; F = ma &nbsp;&bull;&nbsp; d = rt &nbsp;&bull;&nbsp; A = πr² &nbsp;&bull;&nbsp;
    </span>
  </div>
</div>

{{-- ═══════════════════════════════════════
     CONTENT
     ═══════════════════════════════════════ --}}
<div class="content-area">
  <div class="container-lg px-4">

    {{-- Back button - arcade style --}}
    <div class="mb-4">
      <a href="{{ route('student.dashboard') }}" class="back-btn">
        <i class="bi bi-arrow-left-circle-fill" style="font-size:1.1rem;"></i>
        Back to Dashboard
      </a>
    </div>

    @if(session('success'))
    <div class="success-alert">
      <i class="bi bi-check-circle-fill" style="font-size:1.3rem;flex-shrink:0;"></i>
      <span>{{ session('success') }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('student.choose-character') }}" id="char-form">
      @csrf
      <input type="hidden" name="character" id="selected-char" value="">

      <div class="row g-4 justify-content-center align-items-start">
        @foreach($characters as $key => $char)
        @php
          $isSelected = auth()->user()->character_avatar === $key;
          $col        = $char['color'];
        @endphp

        <div class="col-sm-6 col-lg-4 char-col">
          <div class="char-card color-{{ $col }} {{ $isSelected ? 'is-selected' : '' }}" id="card-{{ $key }}">

            {{-- Coloured header zone with portrait --}}
            <div class="card-header-zone ctop-{{ $col }}">

              {{-- Selected checkmark --}}
              @if($isSelected)
              <div class="check-badge badge-{{ $col }}">
                <i class="bi bi-check-lg"></i>
              </div>
              @endif

              {{-- Portrait — click submits form --}}
              <button type="button"
                      class="char-img-btn"
                      onclick="selectCharacter('{{ $key }}')"
                      aria-label="Select {{ $char['nickname'] ?? $char['name'] }}">
                <img src="{{ asset("images/characters/{$key}.png") }}"
                     alt="{{ $char['name'] }}"
                     onerror="this.parentElement.innerHTML='<div style=\'font-size:4.5rem;padding:44px 0;color:rgba(255,255,255,0.7);\'><i class=\'bi bi-person-circle\'></i></div>'">
                <div class="select-overlay">
                  <span><i class="bi bi-cursor-fill me-1"></i>Click to select</span>
                </div>
              </button>

              <div class="char-name-area">
                <div class="char-name">{{ $char['name'] }}</div>
                <div class="char-spec">{{ $char['specialty'] }}</div>
                @if($isSelected)
                <div class="selected-pill pill-{{ $col }}">
                  <i class="bi bi-check-circle-fill"></i> Selected
                </div>
                @endif
              </div>
            </div>

            {{-- Info section (only flavour text, no stats) --}}
            <div class="char-info">
              <div class="info-row">
                <i class="bi bi-lightning-charge-fill" style="color:#a78bfa;"></i>
                <span><strong>Power:</strong> {{ $char['power'] }}</span>
              </div>
              <div class="info-row">
                <i class="bi bi-cpu-fill" style="color:#60a5fa;"></i>
                <span><strong>Trait:</strong> {{ $char['trait'] }}</span>
              </div>
              <div class="info-row">
                <i class="bi bi-shield-fill" style="color:#34d399;"></i>
                <span><strong>Weapon:</strong> {{ $char['weapon'] }}</span>
              </div>
              <p class="char-quote">"{{ $char['quote'] }}"</p>
            </div>
            {{-- No stats toggle or stats panel --}}
          </div>
        </div>
        @endforeach
      </div>
    </form>

  </div>
</div>
</div>{{-- /page-wrap --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
  "use strict";
  window.selectCharacter = function (key) {
    var input = document.getElementById('selected-char');
    var form  = document.getElementById('char-form');
    if (input && form) {
      input.value = key;
      form.submit();
    }
  };

  // Optional: add subtle 3D tilt on hover (excludes selected card to avoid conflict)
  document.querySelectorAll('.char-card').forEach(function (card) {
    card.addEventListener('mousemove', function (e) {
      if (card.classList.contains('is-selected')) return;
      var r  = card.getBoundingClientRect();
      var dx = (e.clientX - (r.left + r.width  / 2)) / (r.width  / 2);
      var dy = (e.clientY - (r.top  + r.height / 2)) / (r.height / 2);
      card.style.transform =
        'translateY(-9px) perspective(900px) rotateX(' + (-dy * 6) + 'deg) rotateY(' + (dx * 6) + 'deg) scale(1.01)';
    });
    card.addEventListener('mouseleave', function () {
      card.style.transform = '';
    });
  });
})();
</script>
@endsection