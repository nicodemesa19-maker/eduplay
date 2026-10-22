@extends('layouts.app')
@section('hide-nav', true)
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700;800;900&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

@php
  $isSnake     = $game->name === 'math_card_match';
  $isRocket    = $game->name === 'rocket_math';
  $totalLevels = $game->total_levels ?? 15;
  $current     = $progress->current_level ?? 1;
  $levelScores = $progress->level_scores ?? [];
  $highScore   = $progress->high_score ?? 0;
  $accent      = $isSnake ? '#22d3ee' : ($isRocket ? '#a78bfa' : '#f472b6');
  $accent2     = $isSnake ? '#67e8f9' : ($isRocket ? '#c4b5fd' : '#f9a8d4');
  $percent     = max(0, min(100, round((($current - 1) / max(1, $totalLevels - 1)) * 100)));
@endphp

<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{background:#04020a;font-family:'Inter',sans-serif;color:#e8eef5;overflow-x:hidden;}

.map-page{height:100vh;min-height:600px;display:flex;flex-direction:column;position:relative;overflow:hidden;background:#04020a;}

/* ── video background ── */
.map-video-bg{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center center;z-index:0;pointer-events:none;}
.map-video-overlay{position:absolute;inset:0;z-index:1;pointer-events:none;background:
  radial-gradient(ellipse 800px 450px at 15% 10%, rgba(167,139,250,.18), transparent 60%),
  radial-gradient(ellipse 700px 450px at 85% 90%, rgba(34,211,238,.12), transparent 60%),
  radial-gradient(ellipse 700px 500px at 50% 50%, rgba(244,114,182,.07), transparent 65%),
  linear-gradient(180deg,rgba(4,2,10,.55) 0%,rgba(10,7,22,.45) 45%,rgba(12,10,30,.5) 75%,rgba(4,2,10,.65) 100%);}

.map-header{position:relative;z-index:100;padding:14px 24px 12px;background:rgba(4,2,10,.75);backdrop-filter:blur(18px);border-bottom:1px solid rgba(255,255,255,.06);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;}
.map-title{font-family:'Space Grotesk',sans-serif;font-size:1.2rem;font-weight:800;color:#fff;display:flex;align-items:center;gap:10px;letter-spacing:-.01em;}
.map-title i{color:{{ $accent }};font-size:1.15rem;}
.map-title small{display:block;font-family:'Inter',sans-serif;font-size:.72rem;font-weight:600;color:rgba(255,255,255,.4);margin-top:1px;}
.stat-pill{display:inline-flex;align-items:center;gap:6px;border-radius:10px;padding:6px 13px;font-size:.78rem;font-weight:700;border:1px solid transparent;}
.sp-accent{background:color-mix(in srgb,{{ $accent }} 16%,transparent);border-color:color-mix(in srgb,{{ $accent }} 32%,transparent);color:{{ $accent }};}
.sp-gold{background:rgba(251,191,36,.14);border-color:rgba(251,191,36,.3);color:#fbbf24;}
.btn-back-map{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:7px 15px;color:rgba(255,255,255,.55);font-size:.8rem;font-weight:700;text-decoration:none;transition:background .15s,color .15s;}
.btn-back-map:hover{background:rgba(255,255,255,.12);color:#fff;}
.btn-mute{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:10px;padding:7px 13px;color:rgba(255,255,255,.55);font-size:.8rem;font-weight:700;cursor:pointer;transition:background .15s,color .15s;user-select:none;}
.btn-mute:hover{background:rgba(255,255,255,.12);color:#fff;}
.btn-mute.muted{background:rgba(244,114,182,.1);border-color:rgba(244,114,182,.25);color:rgba(244,114,182,.7);}
.btn-tutorial{display:inline-flex;align-items:center;gap:6px;background:rgba(251,191,36,.12);border:1.5px solid rgba(251,191,36,.3);border-radius:10px;padding:7px 14px;color:#fde68a;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .18s;user-select:none;text-decoration:none;}
.btn-tutorial:hover{background:rgba(251,191,36,.24);color:#fef3c7;transform:translateY(-1px);box-shadow:0 4px 14px rgba(251,191,36,.2);}

.progress-rail{position:relative;z-index:100;height:5px;background:rgba(255,255,255,.06);}
.progress-fill{height:100%;background:linear-gradient(90deg,{{ $accent }},#fbbf24);width:{{ $percent }}%;transition:width .6s ease;box-shadow:0 0 10px color-mix(in srgb,{{ $accent }} 60%,transparent);}

.map-scroll{position:relative;z-index:2;flex:1;overflow-x:auto;overflow-y:hidden;-webkit-overflow-scrolling:touch;scroll-behavior:smooth;display:flex;align-items:center;background:transparent;}
.map-scroll::-webkit-scrollbar{height:8px;}
.map-scroll::-webkit-scrollbar-track{background:rgba(255,255,255,.03);}
.map-scroll::-webkit-scrollbar-thumb{background:color-mix(in srgb,{{ $accent }} 40%,transparent);border-radius:99px;}
#mapSvg{display:block;height:100%;flex-shrink:0;}

.scroll-cue{position:absolute;top:0;bottom:0;width:70px;pointer-events:none;z-index:5;opacity:0;transition:opacity .3s;}
.scroll-cue.left{left:0;background:linear-gradient(90deg,#04020a,transparent);}
.scroll-cue.right{right:0;background:linear-gradient(270deg,#04020a,transparent);}

/* Character removed for cleaner map view */
</style>

<div class="map-page" id="mapPage">

  <video class="map-video-bg" autoplay loop muted playsinline preload="auto">
    <source src="{{ asset('videos/map-bg.mp4') }}" type="video/mp4">
  </video>
  <div class="map-video-overlay"></div>

  <div class="map-header">
    <div class="map-title">
      <i class="bi {{ $isSnake ? 'bi-card-text' : ($isRocket ? 'bi-rocket-takeoff' : 'bi-shield-shaded') }}"></i>
      <div>
        {{ $game->display_name }}
        <small>Star Map · sail right to advance</small>
      </div>
    </div>
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
      <span class="stat-pill sp-accent"><i class="bi bi-layers-fill"></i> Level {{ $current }}/{{ $totalLevels }}</span>
      <span class="stat-pill sp-gold"><i class="bi bi-trophy-fill"></i> {{ number_format($highScore) }}</span>
      <a href="{{ route('student.dashboard') }}" class="btn-back-map"><i class="bi bi-house-fill"></i> Dashboard</a>
      <button class="btn-tutorial" id="tutorialBtn" title="Replay the tutorial for this game">
        <i class="bi bi-book-half"></i> Tutorial
      </button>
      <button class="btn-mute" id="muteBtn" title="Toggle music"><i class="bi bi-music-note-beamed" id="muteIcon"></i> Music</button>
    </div>
  </div>

  <div class="progress-rail"><div class="progress-fill"></div></div>

  <div class="map-scroll" id="mapScroll">
    {{-- Character removed for cleaner map view --}}
    <svg id="mapSvg" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMid meet"></svg>
  </div>

  <div class="scroll-cue left" id="cueLeft"></div>
  <div class="scroll-cue right" id="cueRight"></div>
</div>

<audio id="lobbyMusic" loop preload="auto">
  <source src="{{ asset('sounds/Beyond_the_Backyard_Sky.mp3') }}" type="audio/mpeg">
</audio>

<script>
(function(){
'use strict';

var TOTAL    = {{ $totalLevels }};
var CURRENT  = {{ $current }};
var PLAY_URL = '{{ route("student.play", $game->name) }}';
var SCORES   = @json($levelScores);
var ACCENT   = '{{ $accent }}';
var ACCENT2  = '{{ $accent2 }}';
var GOLD     = '#fbbf24';
var GAME_NAME = '{{ $game->name }}';

/* ── Tutorial key map ── */
var TUT_KEYS = {
  'math_card_match':  'mcm_tutorial_done',
  'factor_pond':      'fp_tutorial_done',
  'number_wars':      'nw_tutorial_done',
  'rocket_math':      'rm_tutorial_done',
  'the_metric_quest': 'eduplay_metric_tut_done',
  'mdas_maze':        'mdas_maze_tutorial_done',
  'geometry_grid':    'gg_tutorial_shown' // Special handling for Geometry Grid
};

/* ── Tutorial button ── */
document.getElementById('tutorialBtn').addEventListener('click', function() {
  if (GAME_NAME === 'geometry_grid') {
    // For Geometry Grid, set flags to open tutorial on game load
    localStorage.setItem('gg_open_tutorial', 'true');
    sessionStorage.setItem('gg_open_tutorial', 'true');
    var key = TUT_KEYS[GAME_NAME];
    if (key) {
      localStorage.removeItem(key);
      localStorage.removeItem(key + '_{{ auth()->id() ?? 0 }}');
    }
    window.location.href = PLAY_URL + (PLAY_URL.includes('?') ? '&' : '?') + 'tutorial=1&show_instruction=1';
  } else if (GAME_NAME === 'the_metric_quest') {
    // Explicitly set tutorial flags so The Metric Quest always shows tutorial
    localStorage.setItem('mq_show_tutorial', 'true');
    sessionStorage.setItem('mq_show_tutorial', 'true');
    var key = TUT_KEYS[GAME_NAME];
    if (key) {
      localStorage.removeItem(key);
      localStorage.removeItem(key + '_{{ auth()->id() ?? 0 }}');
    }
    window.location.href = PLAY_URL + (PLAY_URL.includes('?') ? '&' : '?') + 'tutorial=1&show_instruction=1';
  } else {
    // For other games, clear the "tutorial done" flag
    var key = TUT_KEYS[GAME_NAME];
    if (key) {
      localStorage.removeItem(key);
      localStorage.removeItem(key + '_{{ auth()->id() ?? 0 }}');
    }
    window.location.href = PLAY_URL;
  }
});

/* ── level base images (planet / island art per level) ── */
var LEVEL_BASES = {
  1:  '{{ asset("images/map-bases/level1-base.png") }}',
  2:  '{{ asset("images/map-bases/level2-base.png") }}',
  3:  '{{ asset("images/map-bases/level3-base.png") }}',
  4:  '{{ asset("images/map-bases/level4-base.png") }}',
  5:  '{{ asset("images/map-bases/level5-base.png") }}',
  6:  '{{ asset("images/map-bases/level6-base.png") }}',
  7:  '{{ asset("images/map-bases/level7-base.png") }}',
  8:  '{{ asset("images/map-bases/level8-base.png") }}',
  9:  '{{ asset("images/map-bases/level9-base.png") }}',
  10: '{{ asset("images/map-bases/level10-base.png") }}',
  11: '{{ asset("images/map-bases/level11-base.png") }}',
  12: '{{ asset("images/map-bases/level12-base.png") }}',
  13: '{{ asset("images/map-bases/level13-base.png") }}',
  14: '{{ asset("images/map-bases/level14-base.png") }}',
  15: '{{ asset("images/map-bases/level15-base.png") }}'
};


/* ── sizing: medium node scale, character deliberately larger than a node ── */
var VH = 400, PAD_X = 80, STEP = 130, NR = 26;
var ROW_T = VH * 0.32, ROW_B = VH * 0.68;

function nodeXY(lvl) {
  var col = Math.floor((lvl - 1) / 2);
  var x   = PAD_X + col * STEP * 2 + (lvl % 2 === 0 ? STEP : 0);
  var y   = (lvl % 2 === 1) ? ROW_T : ROW_B;
  return { x: x, y: y };
}

var positions = [];
for (var i = 1; i <= TOTAL; i++) positions.push(nodeXY(i));
var VW = positions[positions.length - 1].x + PAD_X;

var svgEl = document.getElementById('mapSvg');

function mk(tag, attrs) {
  var e = document.createElementNS('http://www.w3.org/2000/svg', tag);
  if (attrs) Object.keys(attrs).forEach(function(k){ e.setAttribute(k, attrs[k]); });
  return e;
}
function rand(min, max) { return min + Math.random() * (max - min); }

/* ── defs ── */
var defs = mk('defs');
var filt = mk('filter', {id:'glow', x:'-60%', y:'-60%', width:'220%', height:'220%'});
filt.appendChild(mk('feGaussianBlur', {in:'SourceGraphic', stdDeviation:'5', result:'blur'}));
var fm = mk('feMerge'); fm.appendChild(mk('feMergeNode', {in:'blur'})); fm.appendChild(mk('feMergeNode', {in:'SourceGraphic'}));
filt.appendChild(fm); defs.appendChild(filt);

var softBlur = mk('filter', {id:'softBlur', x:'-80%', y:'-80%', width:'260%', height:'260%'});
softBlur.appendChild(mk('feGaussianBlur', {in:'SourceGraphic', stdDeviation:'11'}));
defs.appendChild(softBlur);

var spaceBg = mk('linearGradient', {id:'spaceBg', x1:'0', y1:'0', x2:'1', y2:'0'});
[['0%','#050311'],['50%','#0b0a20'],['100%','#050311']].forEach(function(s){
  var stop = mk('stop'); stop.setAttribute('offset', s[0]); stop.setAttribute('stop-color', s[1]); spaceBg.appendChild(stop);
});
defs.appendChild(spaceBg);
svgEl.appendChild(defs);

/* ── deep space backdrop (transparent — video handles the BG) ── */
svgEl.appendChild(mk('rect', {x:0, y:0, width:VW, height:VH, fill:'transparent'}));

/* nebula glow blobs */
var nebulaColors = ['#a78bfa', '#22d3ee', '#f472b6'];
for (var nb = 0; nb < Math.ceil(VW / 500); nb++) {
  var col = nebulaColors[nb % nebulaColors.length];
  svgEl.appendChild(mk('ellipse', {
    cx: rand(60, VW - 60), cy: rand(60, VH - 60), rx: rand(100, 170), ry: rand(80, 130),
    fill: col, opacity: rand(0.05, 0.09), filter: 'url(#softBlur)'
  }));
}

/* twinkling star field */
for (var s = 0; s < Math.floor(VW / 5); s++) {
  var sx = rand(4, VW - 4), sy = rand(4, VH - 4), sr = rand(0.5, 1.7);
  var star = mk('circle', {cx: sx, cy: sy, r: sr, fill: '#ffffff', opacity: rand(0.25, 0.9)});
  star.appendChild(mk('animate', {
    attributeName: 'opacity',
    values: rand(0.15,0.4) + ';' + rand(0.7,1) + ';' + rand(0.15,0.4),
    dur: rand(1.8, 4.5) + 's', begin: rand(0, 4) + 's', repeatCount: 'indefinite'
  }));
  svgEl.appendChild(star);
}

/* constellations scattered along the route */
var clusterCount = Math.max(3, Math.floor(VW / 380));
for (var cl = 0; cl < clusterCount; cl++) {
  var baseX = rand(60, VW - 60), baseY = rand(60, VH - 60);
  var pointCount = Math.floor(rand(4, 6));
  var pts = [];
  for (var p = 0; p < pointCount; p++) pts.push({ x: baseX + rand(-50, 50), y: baseY + rand(-45, 45) });
  var constGroup = mk('g', {opacity: '0.55'});
  for (var li = 0; li < pts.length - 1; li++) {
    constGroup.appendChild(mk('line', {
      x1: pts[li].x, y1: pts[li].y, x2: pts[li+1].x, y2: pts[li+1].y,
      stroke: 'rgba(180,200,255,0.35)', 'stroke-width': '0.7'
    }));
  }
  pts.forEach(function(pt){
    var cs = mk('circle', {cx: pt.x, cy: pt.y, r: rand(1.3, 2.2), fill: '#dbeafe', filter: 'url(#glow)'});
    cs.appendChild(mk('animate', {attributeName:'opacity', values:'0.5;1;0.5', dur: rand(2,4)+'s', repeatCount:'indefinite'}));
    constGroup.appendChild(cs);
  });
  svgEl.appendChild(constGroup);
}

/* drifting asteroids */
var asteroidCount = Math.max(5, Math.floor(VW / 220));
for (var a = 0; a < asteroidCount; a++) {
  var ax = rand(30, VW - 30), ay = rand(30, VH - 30);
  var baseR = rand(6, 12);
  var sides = Math.floor(rand(6, 9));
  var pathPts = [];
  for (var v = 0; v < sides; v++) {
    var ang = (v / sides) * Math.PI * 2;
    var rr = baseR * rand(0.72, 1.15);
    pathPts.push((Math.cos(ang)*rr).toFixed(1) + ',' + (Math.sin(ang)*rr).toFixed(1));
  }
  var drift = mk('g', {transform:'translate(' + ax + ',' + ay + ')'});
  drift.appendChild(mk('animateTransform', {
    attributeName:'transform', type:'translate', additive:'sum', attributeType:'XML',
    values: '0,0; 0,' + rand(8,15) + '; 0,0', dur: rand(4,7)+'s', repeatCount:'indefinite'
  }));
  var spin = mk('g');
  spin.appendChild(mk('animateTransform', {
    attributeName:'transform', type:'rotate', from:'0', to: (Math.random() < 0.5 ? 360 : -360), dur: rand(14,26)+'s', repeatCount:'indefinite'
  }));
  spin.appendChild(mk('polygon', {points: pathPts.join(' '), fill: 'rgba(148,138,170,' + rand(0.5,0.8).toFixed(2) + ')', stroke:'rgba(220,215,235,0.25)', 'stroke-width':'0.6'}));
  var craterCount = Math.floor(rand(1, 3));
  for (var c = 0; c < craterCount; c++) {
    spin.appendChild(mk('circle', {cx: rand(-baseR*0.4, baseR*0.4), cy: rand(-baseR*0.4, baseR*0.4), r: rand(1.2, 2.4), fill:'rgba(70,64,90,0.55)'}));
  }
  drift.appendChild(spin);
  svgEl.appendChild(drift);
}

/* ── route between nodes ── */
for (var pi = 0; pi < TOTAL - 1; pi++) {
  var na = positions[pi], nb = positions[pi+1];
  var done = (pi + 1) < CURRENT;
  var midx = (na.x + nb.x) / 2;
  var pathD = 'M'+na.x+' '+na.y+' Q'+midx+' '+na.y+' '+midx+' '+((na.y+nb.y)/2)+' Q'+midx+' '+nb.y+' '+nb.x+' '+nb.y;
  /* shadow / outline pass for depth */
  svgEl.appendChild(mk('path', {
    d: pathD,
    stroke: 'rgba(0,0,0,0.55)', 'stroke-width':'10', 'stroke-linecap':'round',
    'stroke-dasharray': done ? 'none' : '2 12',
    fill:'none'
  }));
  /* main coloured trail */
  svgEl.appendChild(mk('path', {
    d: pathD,
    stroke: done ? ACCENT : 'rgba(255,255,255,0.55)',
    'stroke-width':'6', 'stroke-linecap':'round',
    'stroke-dasharray': done ? 'none' : '2 12',
    fill:'none', opacity: done ? '1' : '0.85',
    filter: done ? 'url(#glow)' : ''
  }));
}

function iconLock(g, cx, cy, color) {
  g.appendChild(mk('rect', {x:cx-6, y:cy-1, width:12, height:9, rx:1.5, fill:color}));
  g.appendChild(mk('path', {d:'M'+(cx-4)+' '+(cy-1)+' v-3 a4 4 0 0 1 8 0 v3', stroke:color, 'stroke-width':'2', fill:'none'}));
}
function iconCheck(g, cx, cy, color) {
  g.appendChild(mk('path', {d:'M'+(cx-6)+' '+cy+' l4 4 l8 -9', stroke:color, 'stroke-width':'2.6', 'stroke-linecap':'round', 'stroke-linejoin':'round', fill:'none'}));
}
function iconPlay(g, cx, cy, color) {
  g.appendChild(mk('path', {d:'M'+(cx-4)+' '+(cy-6)+' L'+(cx+6)+' '+cy+' L'+(cx-4)+' '+(cy+6)+' Z', fill:color}));
}

/* ── level base images rendered behind nodes ── */
for (var lvl = 1; lvl <= TOTAL; lvl++) {
  var pos  = positions[lvl - 1];
  
  // Reuse the 15 base images via modulo if levels > 15
  var baseIdx = ((lvl - 1) % 15) + 1;
  var imgSrc = LEVEL_BASES[baseIdx];
  
  var imgSize = NR * 5.2;          /* big planet — clearly larger than the node ring */
  var imgEl = mk('image', {
    href:   imgSrc,
    x:      pos.x - imgSize / 2,
    y:      pos.y - imgSize / 2,
    width:  imgSize,
    height: imgSize,
    preserveAspectRatio: 'xMidYMid meet',
    opacity: '0.82'
  });
  /* gentle slow rotation for the planet */
  imgEl.appendChild(mk('animateTransform', {
    attributeName: 'transform', type: 'rotate',
    from: '0 ' + pos.x + ' ' + pos.y,
    to:   '360 ' + pos.x + ' ' + pos.y,
    dur:  '28s', repeatCount: 'indefinite'
  }));
  svgEl.appendChild(imgEl);
}

/* ── nodes ── */
positions.forEach(function(pos, idx) {
  var lvl      = idx + 1;
  var score    = parseInt(SCORES[lvl] || 0);
  var isPassed = score >= 100;
  var isCur    = lvl === CURRENT;
  var isLocked = lvl > CURRENT;
  var stars    = score >= 100 ? 3 : score >= 60 ? 2 : score > 0 ? 1 : 0;

  var g = mk('g');
  if (!isLocked) {
    g.style.cursor = 'pointer';
    g.addEventListener('click', function(){
      var sep = PLAY_URL.includes('?') ? '&' : '?';
      var targetUrl = PLAY_URL + sep + 'level=' + lvl;
      if (GAME_NAME === 'the_metric_quest') {
        localStorage.setItem('mq_show_tutorial', 'true');
        targetUrl += '&show_instruction=1';
      } else if (GAME_NAME === 'geometry_grid') {
        localStorage.setItem('gg_open_tutorial', 'true');
        sessionStorage.setItem('gg_open_tutorial', 'true');
        targetUrl += '&show_instruction=1';
      }
      window.location.href = targetUrl;
    });
  }

  if (isCur) {
    var bg = mk('circle', {cx:pos.x, cy:pos.y, r:NR+22, fill:'rgba(251,191,36,0.13)', filter:'url(#glow)'});
    bg.appendChild(mk('animate', {attributeName:'r', values:(NR+16)+';'+(NR+24)+';'+(NR+16), dur:'2.2s', repeatCount:'indefinite'}));
    g.appendChild(bg);
    var pr = mk('circle', {cx:pos.x, cy:pos.y, r:NR+19, fill:'none', stroke:GOLD, 'stroke-width':'2', 'stroke-dasharray':'7 9', opacity:'0.8'});
    pr.appendChild(mk('animateTransform', {attributeName:'transform', type:'rotate', from:'0 '+pos.x+' '+pos.y, to:'360 '+pos.x+' '+pos.y, dur:'5s', repeatCount:'indefinite'}));
    g.appendChild(pr);
  } else if (!isLocked) {
    g.appendChild(mk('circle', {cx:pos.x, cy:pos.y, r:NR+11, fill:'rgba(255,255,255,0.05)'}));
  }

  g.appendChild(mk('circle', {cx:pos.x, cy:pos.y, r:NR+6, fill:'none',
    stroke: isLocked ? 'rgba(255,255,255,0.1)' : (isCur ? GOLD : ACCENT),
    'stroke-width':'2', opacity: isLocked ? '0.6' : '1'}));

  g.appendChild(mk('circle', {cx:pos.x, cy:pos.y, r:NR,
    fill: isLocked ? 'rgba(255,255,255,0.05)' : isCur ? 'rgba(251,191,36,0.26)' : 'rgba(148,120,220,0.22)',
    stroke: isLocked ? 'rgba(255,255,255,0.14)' : (isCur ? GOLD : ACCENT),
    'stroke-width': isCur ? '2.8' : '2',
    filter: isLocked ? '' : 'url(#glow)'}));

  /* dark outline pass so the number reads against any background */
  var numShadow = mk('text', {x:pos.x, y:pos.y+7, 'text-anchor':'middle', 'font-family':'Space Grotesk,sans-serif', 'font-size':'22', 'font-weight':'900',
    stroke:'rgba(0,0,0,0.85)', 'stroke-width':'4', 'stroke-linejoin':'round', 'paint-order':'stroke',
    fill:'none'});
  numShadow.textContent = lvl; g.appendChild(numShadow);
  var num = mk('text', {x:pos.x, y:pos.y+7, 'text-anchor':'middle', 'font-family':'Space Grotesk,sans-serif', 'font-size':'22', 'font-weight':'900',
    fill: isLocked ? 'rgba(255,255,255,0.70)' : isCur ? GOLD : ACCENT2});
  num.textContent = lvl; g.appendChild(num);

  var iconColor = isLocked ? 'rgba(255,255,255,0.25)' : (isCur ? GOLD : ACCENT2);
  if (isLocked) iconLock(g, pos.x, pos.y+19, iconColor);
  else if (isPassed) iconCheck(g, pos.x, pos.y+19, iconColor);
  else iconPlay(g, pos.x, pos.y+19, iconColor);

  if (stars > 0) {
    [-14, 0, 14].forEach(function(ox, si) {
      var st = mk('text', {x:pos.x+ox, y:pos.y-NR-9, 'text-anchor':'middle', 'font-size':'12',
        fill: si < stars ? GOLD : 'rgba(255,255,255,0.16)'});
      st.textContent = '★'; g.appendChild(st);
    });
  }

  if (isCur) {
    var pl = mk('text', {x:pos.x, y:pos.y+NR+22, 'text-anchor':'middle', 'font-family':'Space Grotesk,sans-serif', 'font-size':'11', 'font-weight':'900', fill:GOLD});
    pl.textContent = 'PLAY';
    pl.appendChild(mk('animate', {attributeName:'opacity', values:'1;0.35;1', dur:'1.4s', repeatCount:'indefinite'}));
    g.appendChild(pl);
  }

  svgEl.appendChild(g);
});

/* ── layout: real-pixel scale for proper node sizing ── */
var mapScroll = document.getElementById('mapScroll');
var scale, currentStep = 1;

function layout() {
  var h = mapScroll.clientHeight;
  scale = h / VH;
  svgEl.setAttribute('viewBox', '0 0 ' + VW + ' ' + VH);
  svgEl.style.width  = (VW * scale) + 'px';
  svgEl.style.height = h + 'px';
}

function centerOn(lvl) {
  var p = positions[lvl - 1];
  var target = p.x * scale - mapScroll.clientWidth / 2;
  mapScroll.scrollTo({ left: Math.max(0, target), behavior: 'smooth' });
}

window.addEventListener('resize', layout);
requestAnimationFrame(function(){
  layout();
  if (CURRENT > 1) {
    var step = 2;
    (function glide(){
      if (step > CURRENT) { centerOn(CURRENT); return; }
      centerOn(Math.min(step, TOTAL));
      setTimeout(function(){ currentStep = step; step++; glide(); }, 420);
    })();
  } else {
    centerOn(1);
  }
});

/* scroll cue fade */
var cueL = document.getElementById('cueLeft'), cueR = document.getElementById('cueRight');
function updateCues(){
  cueL.style.opacity = mapScroll.scrollLeft > 8 ? '0.9' : '0';
  cueR.style.opacity = (mapScroll.scrollLeft + mapScroll.clientWidth < mapScroll.scrollWidth - 8) ? '0.9' : '0';
}
mapScroll.addEventListener('scroll', updateCues);
window.addEventListener('resize', updateCues);
requestAnimationFrame(updateCues);

})();

/* ── lobby music ── */
(function(){
  var audio   = document.getElementById('lobbyMusic');
  var btn     = document.getElementById('muteBtn');
  var icon    = document.getElementById('muteIcon');
  var muted   = false;
  var started = false;

  audio.volume = 0.15;

  function tryPlay() {
    if (started) return;
    var p = audio.play();
    if (p !== undefined) {
      p.then(function(){ started = true; }).catch(function(){});
    } else {
      started = true;
    }
  }

  /* attempt autoplay immediately */
  tryPlay();

  /* fallback: start on first user interaction if browser blocked autoplay */
  function onInteract() {
    tryPlay();
    document.removeEventListener('click',   onInteract);
    document.removeEventListener('keydown', onInteract);
    document.removeEventListener('touchstart', onInteract);
  }
  document.addEventListener('click',      onInteract);
  document.addEventListener('keydown',   onInteract);
  document.addEventListener('touchstart', onInteract);

  /* mute toggle */
  btn.addEventListener('click', function(e){
    e.stopPropagation();
    tryPlay();
    muted = !muted;
    audio.muted = muted;
    icon.className = muted ? 'bi bi-music-note-beamed' : 'bi bi-music-note-beamed';
    btn.classList.toggle('muted', muted);
    btn.querySelector('i').className = muted ? 'bi bi-volume-mute-fill' : 'bi bi-music-note-beamed';
  });

  /* stop music when navigating away */
  window.addEventListener('beforeunload', function(){ audio.pause(); });
})();
</script>
@endsection