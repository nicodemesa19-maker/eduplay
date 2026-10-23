@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Bungee&family=Baloo+2:wght@500;600;700;800&family=Space+Mono:wght@400;700&display=swap');

/*
  ============================================================
  DESIGN NOTE FOR THE DEV TEAM
  ------------------------------------------------------------
  Theme: "Retro Arcade" — deep cabinet-navy background, neon
  cyan/magenta/lime accents, glowing high-score displays,
  scanline texture. Deliberately different mood from the
  previous "sunny sticker" pass: less pastel/soft, more
  game-cabinet/marquee, still built for grades 4-6.

  Headings: Bungee (poster/arcade marquee feel).
  Body: Baloo 2 (rounded, still very readable for kids).
  Numeric displays (scores etc): Space Mono, for that
  LED-scoreboard look.

  Same two data-driven components as before:
    1. "My Learning Stats"  -> expects $learningStats
    2. Anonymized Scoreboard -> expects $scoreboard
  Fallbacks via `??` keep the page safe with no data wired up.
  ============================================================
*/

:root{
  --bg:#0E0F26;
  --bg2:#171A3D;
  --surface:rgba(255,255,255,.04);
  --border:rgba(0,229,255,.22);
  --border2:rgba(255,255,255,.08);
  --cyan:#00E5FF;
  --pink:#FF3DAE;
  --lime:#B6FF3D;
  --purple:#B026FF;
  --yellow:#FFD23D;
  --text:#EAF6FF;
  --text2:#ffffff;
  --muted:rgba(234,246,255,.55);
  --muted2:rgba(234,246,255,.3);
  --card-bg:rgba(255,255,255,.045);
  --card-border:rgba(0,229,255,.16);
  --sec-line:rgba(0,229,255,.18);
  --ans-bg:rgba(176,38,255,.14);
  --ans-border:rgba(176,38,255,.4);
  --ans-color:#E0B3FF;
  --cab1-a:#0B3B36; --cab1-b:#0D9488; --cab1-c:#5EEAD4;
  --cab2-a:#3B0B24; --cab2-b:#C2185B; --cab2-c:#FF3DAE;
  --glow-cyan:0 0 22px rgba(0,229,255,.35);
  --glow-pink:0 0 22px rgba(255,61,174,.32);
  --glow-lime:0 0 22px rgba(182,255,61,.3);
}

/* light "arcade daylight" mode — same structure, lighter cabinet plastic look */
[data-bs-theme="light"]{
  --bg:#EEF1FB;
  --bg2:#E4E9FB;
  --surface:#ffffff;
  --border:rgba(124,58,237,.18);
  --border2:rgba(124,58,237,.1);
  --text:#1B1D3D;
  --text2:#12132A;
  --muted:rgba(27,29,61,.6);
  --muted2:rgba(27,29,61,.35);
  --card-bg:#ffffff;
  --card-border:rgba(124,58,237,.15);
  --sec-line:rgba(124,58,237,.2);
  --ans-bg:rgba(176,38,255,.08);
  --ans-border:rgba(176,38,255,.3);
  --ans-color:#7C3AED;
}

*{box-sizing:border-box;}
body{background:var(--bg);font-family:'Baloo 2',sans-serif;color:var(--text);overflow-x:hidden;transition:background .3s,color .3s;
  background-image:repeating-linear-gradient(180deg,rgba(255,255,255,.015) 0 2px,transparent 2px 4px);}
[data-bs-theme="light"] body{background-image:none;}
h1,h2,h3,.font-display{font-family:'Bungee',sans-serif;}
.mono{font-family:'Space Mono',monospace;}

/* floating pixels */
#math-float{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden;}
.math-symbol{position:absolute;font-family:'Space Mono',monospace;font-weight:700;
  opacity:.18;animation:floatUp linear infinite;}
@keyframes floatUp{0%{transform:translateY(110vh);opacity:0;}10%{opacity:.25;}
  90%{opacity:.25;}100%{transform:translateY(-15vh);opacity:0;}}

/* hero — arcade marquee */
.hero{
  background:radial-gradient(circle at 20% 20%,rgba(176,38,255,.18),transparent 55%),
             radial-gradient(circle at 85% 30%,rgba(0,229,255,.16),transparent 55%),
             var(--bg2);
  border-bottom:3px solid var(--cyan); padding:38px 0 32px;
  position:relative; overflow:hidden; z-index:1;
  box-shadow:0 4px 0 rgba(0,229,255,.15);
}
[data-bs-theme="light"] .hero{background:linear-gradient(135deg,#E9E4FF,#E0F7FF);border-bottom-color:#7C3AED;box-shadow:0 4px 0 rgba(124,58,237,.15);}

.hero-tag{display:inline-flex;align-items:center;gap:7px;background:rgba(0,229,255,.1);
  border:2px solid var(--cyan);border-radius:8px;padding:5px 14px;
  font-family:'Space Mono',monospace;font-size:0.85rem;font-weight:700;color:var(--cyan);
  letter-spacing:1px;text-transform:uppercase;margin-bottom:12px;animation:blink 1.6s steps(1) infinite;}
@keyframes blink{0%,60%,100%{opacity:1;}80%{opacity:.4;}}
[data-bs-theme="light"] .hero-tag{color:#7C3AED;border-color:#7C3AED;background:rgba(124,58,237,.08);}

.hero-name{font-family:'Bungee',sans-serif;font-size:2.4rem;color:var(--text2);line-height:1.2;
  text-shadow:0 0 18px rgba(0,229,255,.4),3px 3px 0 rgba(255,61,174,.35);}
.hero-name span{color:var(--lime);text-shadow:0 0 18px rgba(182,255,61,.5);}
[data-bs-theme="light"] .hero-name{text-shadow:none;}
[data-bs-theme="light"] .hero-name span{color:#7C3AED;text-shadow:none;}

.eq-ticker{display:inline-block;background:rgba(0,0,0,.3);border:2px solid var(--lime);
  border-radius:8px;padding:6px 16px;margin-top:12px;font-family:'Space Mono',monospace;
  font-size:1.05rem;color:var(--lime);font-weight:700;transition:opacity .3s;box-shadow:var(--glow-lime);}
[data-bs-theme="light"] .eq-ticker{background:#fff;color:#059669;border-color:#059669;box-shadow:none;}

/* stat chips — LED scoreboard style */
.stat-chip{background:rgba(0,0,0,.35);border:2px solid var(--cyan);border-radius:12px;
  padding:14px 20px;text-align:center;transition:transform .2s,box-shadow .2s;
  box-shadow:0 0 12px rgba(0,229,255,.35), 0 0 24px rgba(0,229,255,.15), inset 0 0 12px rgba(0,229,255,.06);}
[data-bs-theme="light"] .stat-chip{background:#fff;}
.stat-chip:hover{transform:translateY(-3px);box-shadow:0 0 22px rgba(0,229,255,.7), 0 0 44px rgba(0,229,255,.3), inset 0 0 18px rgba(0,229,255,.1);border-color:var(--cyan);}
.stat-chip .v{font-family:'Space Mono',monospace;font-size:2rem;font-weight:700;color:var(--cyan);line-height:1;text-shadow:0 0 14px rgba(0,229,255,.9), 0 0 28px rgba(0,229,255,.4);}
[data-bs-theme="light"] .stat-chip .v{color:#7C3AED;}
.stat-chip .l{font-size:0.78rem;font-weight:700;color:var(--muted);margin-top:5px;letter-spacing:.5px;text-transform:uppercase;}

/* nav pills */
.nav-row{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:28px;}
.npill{background:var(--card-bg);border:2px solid var(--card-border);border-radius:10px;
  padding:10px 18px;font-size:1rem;font-weight:700;color:var(--text);text-decoration:none;
  display:flex;align-items:center;gap:8px;transition:all .2s;}
.npill:hover{border-color:var(--pink);color:var(--pink);box-shadow:var(--glow-pink);transform:translateY(-2px);}
[data-bs-theme="light"] .npill:hover{color:#C2185B;box-shadow:none;}

/* section head */
.sec-head{font-size:1.15rem;font-weight:700;color:var(--text2);font-family:'Bungee',sans-serif;
  display:flex;align-items:center;gap:10px;margin-bottom:18px;letter-spacing:.5px;}
.sec-head i{color:var(--cyan);}
[data-bs-theme="light"] .sec-head i{color:#7C3AED;}
.sec-head::after{content:'';flex:1;height:2px;background:var(--sec-line);}

/* ── LEARNING STATS WIDGET ── */
@php
  $learningStats = $learningStats ?? [];
  $peakDay   = $learningStats['peak_day'] ?? null;
  $streak    = $learningStats['streak'] ?? 0;
  $streakMap = $learningStats['streak_map'] ?? [0,0,0,0,0,0,0];
  $skillList = $learningStats['skills'] ?? [];
@endphp

.stat-card{background:var(--card-bg);border:2px solid var(--card-border);border-radius:14px;
  padding:20px;height:100%;transition:transform .2s,box-shadow .2s;}
.stat-card:hover{transform:translateY(-3px);}
.stat-card .badge-icon{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;
  justify-content:center;font-size:1.3rem;color:#0E0F26;margin-bottom:12px;}
.stat-card .headline{font-family:'Baloo 2',sans-serif;font-size:1.15rem;font-weight:800;color:var(--text2);margin-bottom:4px;}
.stat-card .sub{font-size:0.9rem;color:var(--muted);}

.streak-dots{display:flex;gap:6px;margin-top:12px;}
.streak-dot{width:15px;height:15px;border-radius:3px;background:var(--card-border);}
.streak-dot.on{background:var(--lime);box-shadow:0 0 8px rgba(182,255,61,.6);}

.skill-row{display:flex;align-items:center;gap:10px;margin-bottom:12px;}
.skill-row:last-child{margin-bottom:0;}
.skill-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;
  font-size:0.95rem;color:#0E0F26;flex-shrink:0;}
.skill-bar-track{flex:1;height:10px;border-radius:4px;background:rgba(255,255,255,.08);overflow:hidden;}
[data-bs-theme="light"] .skill-bar-track{background:rgba(0,0,0,.06);}
.skill-bar-fill{height:100%;border-radius:4px;transition:width 1.1s cubic-bezier(.4,0,.2,1) .2s;width:0;}
.skill-pct{font-family:'Space Mono',monospace;font-size:0.82rem;font-weight:700;color:var(--muted);min-width:36px;text-align:right;}

/* ── ANONYMIZED SCOREBOARD ── */
@php
  $scoreboard = $scoreboard ?? [];
  $sbTop      = $scoreboard['top'] ?? null;
  $sbAvg      = $scoreboard['average'] ?? null;
  $sbYou      = $scoreboard['you'] ?? ($totalScore ?? 0);
  $sbRank     = $scoreboard['rank'] ?? null;
  $sbMax      = max([$sbTop ?? 0, $sbAvg ?? 0, $sbYou, 1]);
@endphp

.board-card{background:var(--card-bg);border:2px solid var(--card-border);border-radius:14px;padding:22px;}
.board-row{display:flex;align-items:center;gap:12px;margin-bottom:16px;}
.board-row:last-child{margin-bottom:0;}
.board-label{width:132px;flex-shrink:0;font-size:0.85rem;font-weight:700;color:var(--muted);display:flex;align-items:center;gap:6px;}
.board-track{flex:1;height:24px;border-radius:6px;background:rgba(255,255,255,.06);overflow:hidden;position:relative;}
[data-bs-theme="light"] .board-track{background:rgba(0,0,0,.06);}
.board-fill{height:100%;border-radius:6px;display:flex;align-items:center;justify-content:flex-end;
  padding-right:10px;color:#0E0F26;font-weight:700;font-family:'Space Mono',monospace;font-size:0.82rem;width:0;
  transition:width 1.2s cubic-bezier(.4,0,.2,1) .25s;white-space:nowrap;}
.board-row.you .board-track{box-shadow:0 0 0 2px var(--purple);}
.board-row.you .board-label{color:var(--purple);}
[data-bs-theme="light"] .board-row.you .board-label{color:#7C3AED;}

/* progress rings */
.prog-game-card{background:var(--card-bg);border:2px solid var(--card-border);border-radius:16px;padding:22px;
  transition:transform .25s,border-color .25s,box-shadow .25s;position:relative;overflow:hidden;}
.prog-game-card:hover{transform:translateY(-4px);border-color:var(--cyan);box-shadow:var(--glow-cyan);}
[data-bs-theme="light"] .prog-game-card:hover{box-shadow:none;border-color:#7C3AED;}

.ring-wrap{position:relative;width:100px;height:100px;flex-shrink:0;}
.ring-wrap svg{transform:rotate(-90deg);}
.ring-bg{fill:none;stroke:rgba(255,255,255,.08);stroke-width:9;}
[data-bs-theme="light"] .ring-bg{stroke:rgba(0,0,0,.06);}
.ring-fill{fill:none;stroke-width:9;stroke-linecap:round;stroke-dasharray:283;stroke-dashoffset:283;
  transition:stroke-dashoffset 1.4s cubic-bezier(.4,0,.2,1) .3s;}
.ring-center{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;}
.ring-pct{font-family:'Space Mono',monospace;font-size:1.4rem;font-weight:700;color:var(--text2);line-height:1;}
.ring-lbl{font-size:0.72rem;color:var(--muted);margin-top:2px;font-weight:700;text-transform:uppercase;}

.game-icon-anim{width:38px;height:38px;border-radius:9px;background:rgba(255,255,255,.1);
  display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#fff;flex-shrink:0;
  animation:icon-bob 3s ease-in-out infinite;}
@keyframes icon-bob{0%,100%{transform:translateY(0);}50%{transform:translateY(-5px);}}
.score-val{color:inherit;font-family:'Space Mono',monospace;}

/* game nav — arcade cabinet cards */
.game-nav-card{border-radius:16px;overflow:hidden;border:2px solid var(--card-border);background:var(--card-bg);
  transition:transform .25s cubic-bezier(.34,1.56,.64,1),box-shadow .25s,border-color .25s;text-decoration:none;display:block;}
.game-nav-card:hover{transform:translateY(-6px) scale(1.01);}
.game-nav-card.cab1:hover{box-shadow:0 0 0 2px var(--cab1-c),0 16px 36px rgba(13,148,136,.25);border-color:var(--cab1-c);}
.game-nav-card.cab2:hover{box-shadow:0 0 0 2px var(--cab2-c),0 16px 36px rgba(194,24,91,.25);border-color:var(--cab2-c);}
.game-nav-top{padding:20px 20px 16px;position:relative;overflow:hidden;}
.game-nav-top::after{content:'';position:absolute;inset:0;
  background-image:repeating-linear-gradient(180deg,rgba(0,0,0,.08) 0 2px,transparent 2px 4px);pointer-events:none;}
.cab1-top{background:linear-gradient(135deg,var(--cab1-a),var(--cab1-b),var(--cab1-c));}
.cab2-top{background:linear-gradient(135deg,var(--cab2-a),var(--cab2-b),var(--cab2-c));}
.play-badge{display:inline-flex;align-items:center;gap:5px;background:rgba(0,0,0,.35);
  border:1px solid rgba(255,255,255,.4);border-radius:6px;padding:4px 12px;
  font-family:'Space Mono',monospace;font-size:0.78rem;font-weight:700;color:#fff;text-transform:uppercase;}

/* side card */
.side-card{background:var(--card-bg);border:2px solid var(--card-border);border-radius:14px;padding:20px;margin-bottom:14px;font-size:1rem;}

.char-frame{width:58px;height:58px;border-radius:12px;background:linear-gradient(135deg,rgba(176,38,255,.2),rgba(0,229,255,.15));
  border:2px solid var(--purple);display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0;}

/* math clock — arcade challenge machine */
.math-clock{
  background:rgba(0,0,0,.3);
  border:2px solid var(--purple);
  border-radius:12px;padding:18px;text-align:center;
  box-shadow:0 0 14px rgba(138,92,255,.5), 0 0 30px rgba(138,92,255,.2), inset 0 0 20px rgba(138,92,255,.07);
  animation:clockGlow 2.8s ease-in-out infinite;
}
@keyframes clockGlow{
  0%,100%{ box-shadow:0 0 14px rgba(138,92,255,.5),0 0 30px rgba(138,92,255,.2),inset 0 0 20px rgba(138,92,255,.07); border-color:var(--purple); }
  40%    { box-shadow:0 0 22px rgba(138,92,255,.85),0 0 50px rgba(138,92,255,.4),inset 0 0 30px rgba(138,92,255,.15); border-color:#c084fc; }
  70%    { box-shadow:0 0 10px rgba(138,92,255,.3),0 0 18px rgba(138,92,255,.1),inset 0 0 10px rgba(138,92,255,.04); border-color:rgba(138,92,255,.6); }
}
@keyframes cardPulse{
  0%,100%{ box-shadow:0 0 18px rgba(138,92,255,.25),0 0 40px rgba(138,92,255,.1); border-color:rgba(138,92,255,.5); }
  50%    { box-shadow:0 0 28px rgba(138,92,255,.5),0 0 60px rgba(138,92,255,.2); border-color:rgba(138,92,255,.85); }
}
[data-bs-theme="light"] .math-clock{background:#F9F5FF;animation:none;box-shadow:none;border-color:rgba(124,58,237,.3);}
.clock-q{
  font-family:'Space Mono',monospace;font-size:1.8rem;font-weight:700;color:var(--purple);
  animation:q-pop .35s ease, qFlicker 5s steps(1) infinite;
  text-shadow:0 0 12px rgba(138,92,255,.9), 0 0 28px rgba(138,92,255,.5);
}
@keyframes qFlicker{
  0%,89%,91%,94%,100%{ opacity:1; text-shadow:0 0 12px rgba(138,92,255,.9),0 0 28px rgba(138,92,255,.5); }
  90%    { opacity:.25; text-shadow:none; }
  92%,93%{ opacity:.65; text-shadow:0 0 6px rgba(138,92,255,.5); }
}
[data-bs-theme="light"] .clock-q{color:#7C3AED;animation:q-pop .35s ease;text-shadow:none;}
@keyframes q-pop{from{opacity:0;transform:scale(.9);}to{opacity:1;transform:scale(1);}}
.ans-btn{background:var(--ans-bg);border:2px solid var(--ans-border);border-radius:8px;
  padding:6px 14px;font-family:'Space Mono',monospace;font-size:0.95rem;font-weight:700;color:var(--ans-color);cursor:pointer;transition:all .15s;}
.ans-btn:hover{background:var(--purple);border-color:var(--purple);color:#fff;}
.ans-btn.correct{background:var(--lime);border-color:var(--lime);color:#0E0F26;}
.ans-btn.wrong{background:var(--pink);border-color:var(--pink);color:#fff;}

/* tip */
.tip{display:flex;align-items:flex-start;gap:10px;font-size:0.95rem;color:var(--muted);margin-bottom:10px;}
.tip:last-child{margin-bottom:0;}
.tip i{color:var(--cyan);flex-shrink:0;margin-top:2px;}
[data-bs-theme="light"] .tip i{color:#7C3AED;}
</style>

<div id="math-float" aria-hidden="true"></div>

<div style="position:relative;z-index:1;">

{{-- ── HERO ── --}}
<div class="hero">
  <div class="container-lg px-4">
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-4">
      <div>
        <div class="hero-tag">● Player One Ready</div>
        <h1 class="hero-name">Hi, <span>{{ Auth::user()->name }}</span></h1>
        <p style="font-size:1.05rem;color:var(--muted);margin-top:6px;font-weight:700;">
          Press start on today's math quest.
          @if(Auth::user()->grade_level) &nbsp;·&nbsp; Grade {{ Auth::user()->grade_level }} @endif
        </p>
        <div class="eq-ticker" id="eq-ticker">a² + b² = c²</div>
      </div>
      @php
        $totalScore   = $progress->map(fn($p) => $p->avg_score)->filter(fn($s) => $s > 0)->avg() ?? 0;
        $totalScore   = round($totalScore, 1);
        $totalLevels  = $progress->sum('current_level') - $progress->count();
        $maxedGames   = $progress->where('current_level', 15)->count();
      @endphp
      <div class="d-flex gap-3 flex-wrap">
        <div class="stat-chip"><div class="v" id="sc-score">0</div><div class="l">Score</div></div>
        <div class="stat-chip"><div class="v" id="sc-levels">0</div><div class="l">Levels</div></div>
        <div class="stat-chip"><div class="v" id="sc-maxed">0</div><div class="l">Maxed</div></div>
        <div class="stat-chip"><div class="v mono">{{ $games->count() }}</div><div class="l">Games</div></div>
      </div>
    </div>
  </div>
</div>

{{-- ── CONTENT ── --}}
<div class="container-lg px-4 pt-4 pb-5">

  <div class="nav-row">
    <a href="{{ route('student.games') }}" class="npill"><i class="bi bi-joystick"></i>All Games</a>
    <a href="{{ route('student.learning-stats') }}" class="npill"><i class="bi bi-graph-up-arrow"></i>Learning Stats</a>
    <a href="{{ route('student.badges') }}" class="npill"><i class="bi bi-trophy"></i>My Badges</a>
    <a href="{{ route('student.profile.show') }}" class="npill"><i class="bi bi-person-circle"></i>My Profile</a>
  </div>

  <div class="row g-4">

    {{-- ── LEFT ── --}}
    <div class="col-lg-8">

      <div class="side-card">
        <div class="sec-head"><i class="bi bi-door-open"></i> Join a Classroom</div>
        <form method="POST" action="{{ route('student.enroll-classroom') }}" class="d-flex flex-column gap-2">
          @csrf
          <input type="text" name="class_code" placeholder="Enter your class code" class="form-control" required>
          <button type="submit" class="btn fw-bold" style="background:var(--lime);color:#0E0F26;border-radius:10px;border:none;">Join Classroom</button>
        </form>
        @if(session('success'))
          <div class="alert alert-success mt-3 mb-0">{{ session('success') }}</div>
        @endif
      </div>

      {{-- ── CLASS LEADERBOARD (moved up, replaces Learning Stats) ── --}}
      <div class="sec-head"><i class="bi bi-bar-chart-steps"></i> Class Leaderboard</div>
      <div class="board-card mb-5">
        @if(is_null($sbTop) && is_null($sbAvg))
          <p class="sub mb-0" style="color:var(--muted);">Join a classroom to see how you're doing compared to the class average — no names, ever, just glowing bars!</p>
        @else
          <div class="board-row">
            <div class="board-label"><i class="bi bi-trophy-fill" style="color:var(--yellow);"></i> Top Explorer</div>
            <div class="board-track"><div class="board-fill" data-w="{{ $sbTop ? round(($sbTop/$sbMax)*100) : 0 }}" style="background:var(--yellow);">{{ $sbTop ? number_format($sbTop) : '—' }}</div></div>
          </div>
          <div class="board-row">
            <div class="board-label"><i class="bi bi-people-fill" style="color:var(--cyan);"></i> Class Average</div>
            <div class="board-track"><div class="board-fill" data-w="{{ $sbAvg ? round(($sbAvg/$sbMax)*100) : 0 }}" style="background:var(--cyan);">{{ $sbAvg ? number_format($sbAvg) : '—' }}</div></div>
          </div>
          <div class="board-row you">
            <div class="board-label"><i class="bi bi-person-fill-check"></i> You{{ $sbRank ? " (Rank #$sbRank)" : '' }}</div>
            <div class="board-track"><div class="board-fill" data-w="{{ round(($sbYou/$sbMax)*100) }}" style="background:var(--purple);color:#fff;">{{ number_format($sbYou) }} ★</div></div>
          </div>
        @endif
      </div>

      {{-- PROGRESS SECTION --}}
      <div class="sec-head"><i class="bi bi-bar-chart-line"></i> My Progress</div>

      @if($progress->isEmpty())
      <div class="side-card text-center py-4 mb-4">
        <i class="bi bi-bar-chart-line d-block mb-2" style="font-size:2.5rem;color:var(--muted2);"></i>
        <p style="color:var(--muted);font-size:1.05rem;">Play a game to start tracking your progress!</p>
      </div>
      @else
      <div class="row g-3 mb-4">
        @foreach($progress->take(4) as $p)
        @php
          $isSnake = $p->game->name === 'math_card_match';
          $isWars  = $p->game->name === 'number_wars';
          $col     = $isSnake ? '#0D9488' : ($isWars ? '#C2185B' : '#B026FF');
          $bi      = $isSnake ? 'bi-card-text' : ($isWars ? 'bi-shield-shaded' : 'bi-joystick');
          $total   = $p->game->total_levels ?? 15;
          $pct     = $p->avg_score > 0 ? round(($p->current_level / $total) * 100) : 0;
          $offset  = round(283 - (283 * $pct / 100));
        @endphp
        <div class="col-sm-6">
          <div class="prog-game-card">
            <div class="d-flex align-items-center gap-4">
              <div class="ring-wrap">
                <svg width="100" height="100" viewBox="0 0 110 110">
                  <circle class="ring-bg" cx="55" cy="55" r="45"/>
                  <circle class="ring-fill" cx="55" cy="55" r="45"
                          stroke="{{ $col }}"
                          data-offset="{{ $offset }}"
                          style="stroke-dashoffset:283;filter:drop-shadow(0 0 6px {{ $col }}88);"/>
                </svg>
                <div class="ring-center"><span class="ring-pct">{{ $pct }}%</span><span class="ring-lbl">Done</span></div>
              </div>
              <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <div class="game-icon-anim" style="background:{{ $col }}33;color:{{ $col }};animation-delay:{{ $loop->index * .4 }}s;"><i class="bi {{ $bi }}"></i></div>
                  <p class="fw-bold mb-0" style="font-size:1.05rem;color:var(--text2);">{{ $p->game->display_name }}</p>
                </div>
                <p style="font-size:0.9rem;color:var(--muted);margin-bottom:8px;"><i class="bi bi-layers me-1"></i>Level {{ $p->current_level }} / {{ $total }}</p>
                <p class="score-val fw-bold mb-2" style="font-size:1.45rem;color:{{ $col }};">
                  {{ number_format($p->avg_score, 1) }} <span style="font-size:0.85rem;color:var(--muted);font-weight:400;">avg pts</span>
                </p>
                <a href="{{ route('student.map', $p->game->name) }}" class="btn btn-sm fw-bold text-white" style="border-radius:8px;background:{{ $col }};font-size:0.9rem;padding:8px 20px;">
                  <i class="bi bi-play-fill me-1"></i>Continue
                </a>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      @if($progress->count() > 4)
      <div class="mb-5">
        <a href="{{ route('student.my-progress') }}"
           class="d-flex align-items-center justify-content-center gap-2 py-3"
           style="background:var(--card-bg);border:2px dashed var(--card-border);border-radius:14px;color:var(--muted);font-size:1rem;font-weight:700;text-decoration:none;transition:all .2s;"
           onmouseover="this.style.borderColor='var(--cyan)';this.style.color='var(--cyan)';"
           onmouseout="this.style.borderColor='var(--card-border)';this.style.color='var(--muted)';">
          <i class="bi bi-bar-chart-line"></i> View All {{ $progress->count() }} Games
        </a>
      </div>
      @else
      <div class="mb-5"></div>
      @endif
      @endif

    </div>

    {{-- ── SIDEBAR ── --}}
    <div class="col-lg-4">

      @if(Auth::user()->grade_level)
      <div class="side-card" style="text-align:center;background:rgba(176,38,255,.08);border-color:var(--card-border);">
        <p style="font-size:0.8rem;font-weight:700;color:var(--muted);margin-bottom:4px;text-transform:uppercase;letter-spacing:.5px;"><i class="bi bi-award me-1"></i>Grade Level</p>
        <p class="fw-bold mb-0 mono" style="font-size:2.6rem;color:var(--purple);">GR {{ Auth::user()->grade_level }}</p>
      </div>
      @endif

      {{-- ── STREAK CARD ── --}}
      <div class="side-card" style="text-align:center;">
        <div class="badge-icon" style="background:var(--yellow);width:52px;height:52px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.6rem;color:#0E0F26;margin:0 auto 14px;"><i class="bi bi-fire"></i></div>
        <div style="font-family:'Bungee',sans-serif;font-size:1.45rem;color:var(--text2);margin-bottom:6px;">
          {{ $streak > 0 ? "$streak-Day Streak!" : 'No streak yet!' }}
        </div>
        <div style="font-size:1rem;color:var(--muted);margin-bottom:14px;">
          Keep the fire burning.
        </div>
        <div class="streak-dots" style="display:flex;gap:8px;margin-top:4px;justify-content:center;">
          @foreach($streakMap as $d)<span class="streak-dot {{ $d ? 'on' : '' }}" style="width:18px;height:18px;border-radius:4px;"></span>@endforeach
        </div>
      </div>

      {{-- ── QUICK CHALLENGE (always visible, just for fun) ── --}}
      <div class="side-card" style="border-color:rgba(138,92,255,.5);box-shadow:0 0 18px rgba(138,92,255,.25),0 0 40px rgba(138,92,255,.1);animation:cardPulse 3.5s ease-in-out infinite;">
        <div class="sec-head" style="margin-bottom:12px;color:var(--violet);text-shadow:0 0 10px rgba(138,92,255,.7);"><i class="bi bi-lightning-charge-fill"></i> Quick Challenge</div>
        <div class="math-clock">
          <div class="clock-q" id="clock-prob">—</div>
          <div style="font-size:0.9rem;color:var(--muted);margin:4px 0 8px;font-weight:700;">⏱ <span id="clock-sec">8</span>s left</div>
          <div id="clock-answers" style="display:flex;gap:6px;flex-wrap:wrap;justify-content:center;"></div>
          <p style="font-size:0.8rem;color:var(--muted2);margin-top:10px;margin-bottom:0;">Just for fun — no scores saved!</p>
        </div>
      </div>

      <div class="side-card">
        <div class="sec-head" style="margin-bottom:12px;"><i class="bi bi-lightbulb"></i> Growth Tips</div>
        <div class="tip"><i class="bi bi-lightning-charge-fill"></i><span>Play a little every day for the best results.</span></div>
        <div class="tip"><i class="bi bi-bullseye"></i><span>Try both games to master all operations.</span></div>
        <div class="tip"><i class="bi bi-arrow-repeat"></i><span>Mistakes are how your brain grows stronger.</span></div>
        <div class="tip"><i class="bi bi-trophy"></i><span>Always aim to beat your own high score.</span></div>
      </div>

    </div>
  </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  const syms=['0','1','+','×','#','?','★','▲'];
  const wrap=document.getElementById('math-float');
  const colors=['#00E5FF','#FF3DAE','#B6FF3D','#FFD23D','#B026FF'];
  for(let i=0;i<40;i++){
    const el=document.createElement('div');
    el.className='math-symbol';
    el.textContent=syms[Math.floor(Math.random()*syms.length)];
    el.style.color=colors[Math.floor(Math.random()*colors.length)];
    el.style.left=Math.random()*100+'%';
    el.style.fontSize=(.8+Math.random()*1.2)+'rem';
    el.style.animationDelay=Math.random()*12+'s';
    el.style.animationDuration=(8+Math.random()*10)+'s';
    wrap.appendChild(el);
  }
})();

(function(){
  const eqs=['a² + b² = c²','E = mc²','F = ma','A = πr²','2 + 2 = 4','5 × 5 = 25','½ + ½ = 1'];
  let i=0; const el=document.getElementById('eq-ticker');
  el.style.transition='opacity .3s';
  setInterval(()=>{ el.style.opacity='0'; setTimeout(()=>{ el.textContent=eqs[i++%eqs.length]; el.style.opacity='1'; },300); },2800);
})();

function countUp(id,target,dur){
  const el=document.getElementById(id); if(!el)return;
  let c=0,step=Math.max(1,Math.ceil(target/55));
  const t=setInterval(()=>{ c=Math.min(c+step,target); el.textContent=c.toLocaleString(); if(c>=target)clearInterval(t); },dur/55);
}
countUp('sc-score',{{ $totalScore }},900);
countUp('sc-levels',{{ $totalLevels }},700);
countUp('sc-maxed',{{ $maxedGames }},500);

setTimeout(()=>{
  document.querySelectorAll('.ring-fill').forEach(el=>{ el.style.strokeDashoffset = el.dataset.offset; });
},400);

setTimeout(()=>{
  document.querySelectorAll('.skill-bar-fill, .board-fill').forEach(el=>{ el.style.width = (el.dataset.w || 0) + '%'; });
},400);

(function(){
  const probEl=document.getElementById('clock-prob');
  const ansEl=document.getElementById('clock-answers');
  const secEl=document.getElementById('clock-sec');
  let ans, timer, secs=8;
  function rand(a,b){return Math.floor(Math.random()*(b-a+1))+a;}
  function next(){
    clearInterval(timer);
    const ops=['+','-','×'];
    const op=ops[Math.floor(Math.random()*ops.length)];
    let a,b;
    if(op==='+'){a=rand(2,25);b=rand(2,25);ans=a+b;}
    else if(op==='-'){a=rand(10,35);b=rand(2,a);ans=a-b;}
    else{a=rand(2,12);b=rand(2,12);ans=a*b;}
    probEl.style.animation='none'; void probEl.offsetWidth; probEl.style.animation='';
    probEl.textContent=`${a} ${op} ${b} = ?`;
    const opts=new Set([ans]);
    while(opts.size<4){const w=ans+rand(-8,8);if(w>0&&w!==ans)opts.add(w);}
    const arr=[...opts].sort(()=>Math.random()-.5);
    ansEl.innerHTML='';
    arr.forEach(v=>{
      const btn=document.createElement('button');
      btn.className='ans-btn'; btn.textContent=v;
      btn.onclick=()=>{
        if(v===ans){btn.classList.add('correct');clearInterval(timer);setTimeout(next,700);}
        else{btn.classList.add('wrong');setTimeout(()=>btn.classList.remove('wrong'),400);}
      };
      ansEl.appendChild(btn);
    });
    secs=8; secEl.textContent=secs;
    timer=setInterval(()=>{ secs--; secEl.textContent=secs; if(secs<=0){clearInterval(timer);setTimeout(next,300);} },1000);
  }
  next();
})();
</script>
@endsection