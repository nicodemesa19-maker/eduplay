@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Bungee&family=Baloo+2:wght@500;600;700;800&family=Space+Mono:wght@400;700&display=swap');

/* Arcade Theme - Matching Dashboard */
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
  --glow-cyan:0 0 22px rgba(0,229,255,.35);
  --glow-pink:0 0 22px rgba(255,61,174,.32);
  --glow-lime:0 0 22px rgba(182,255,61,.3);
}

*{box-sizing:border-box;}
body{background:var(--bg);font-family:'Baloo 2',sans-serif;color:var(--text);overflow-x:hidden;
  background-image:repeating-linear-gradient(180deg,rgba(255,255,255,.015) 0 2px,transparent 2px 4px);}

/* Floating math symbols */
#math-float{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden;}
.math-symbol{position:absolute;font-family:'Space Mono',monospace;font-weight:700;
  opacity:.18;animation:floatUp linear infinite;}
@keyframes floatUp{0%{transform:translateY(110vh);opacity:0;}10%{opacity:.25;}
  90%{opacity:.25;}100%{transform:translateY(-15vh);opacity:0;}}

/* Page header */
.page-header{
  background:radial-gradient(circle at 20% 20%,rgba(176,38,255,.18),transparent 55%),
             radial-gradient(circle at 85% 30%,rgba(0,229,255,.16),transparent 55%),
             var(--bg2);
  border-bottom:3px solid var(--cyan);
  padding:38px 0 32px;
  position:relative;
  overflow:hidden;
  z-index:1;
  box-shadow:0 4px 0 rgba(0,229,255,.15);
}

.back-btn{
  background:rgba(0,0,0,.3);
  border:2px solid var(--lime);
  border-radius:8px;
  padding:6px 16px;
  font-family:'Space Mono',monospace;
  font-size:0.78rem;
  font-weight:700;
  color:var(--lime);
  text-decoration:none;
  display:inline-flex;
  align-items:center;
  gap:6px;
  transition:opacity .3s;
  box-shadow:var(--glow-lime);
}
.back-btn:hover{
  opacity:.8;
  color:var(--lime);
  text-decoration:none;
}

.page-title{
  font-family:'Bungee',sans-serif;
  font-size:2.4rem;
  color:var(--text2);
  line-height:1.2;
  text-shadow:0 0 18px rgba(0,229,255,.4),3px 3px 0 rgba(255,61,174,.35);
  margin:12px 0 6px;
}

.page-subtitle{
  font-family:'Baloo 2',sans-serif;
  font-size:1.05rem;
  color:var(--muted);
  font-weight:700;
  margin:0;
}

/* Game cards */
.game-card{
  border-radius:16px;
  overflow:hidden;
  border:2px solid var(--card-border);
  background:var(--card-bg);
  transition:transform .25s cubic-bezier(.34,1.56,.64,1),box-shadow .25s,border-color .25s;
  text-decoration:none;
  display:block;
  height:100%;
}

.game-card:hover{
  transform:translateY(-6px) scale(1.01);
  text-decoration:none;
}

.game-card.snake:hover{
  box-shadow:0 0 0 2px #5EEAD4,0 16px 36px rgba(13,148,136,.25);
  border-color:#5EEAD4;
}

.game-card.wars:hover{
  box-shadow:0 0 0 2px #FF3DAE,0 16px 36px rgba(194,24,91,.25);
  border-color:#FF3DAE;
}

.game-card.rocket:hover{
  box-shadow:0 0 0 2px var(--purple),0 16px 36px rgba(176,38,255,.25);
  border-color:var(--purple);
}

.card-top{
  padding:20px 20px 16px;
  position:relative;
  overflow:hidden;
}

.card-top::after{
  content:'';
  position:absolute;
  inset:0;
  background-image:repeating-linear-gradient(180deg,rgba(0,0,0,.08) 0 2px,transparent 2px 4px);
  pointer-events:none;
}

.snake-top{background:linear-gradient(135deg,#0B3B36,#0D9488,#5EEAD4);}
.wars-top{background:linear-gradient(135deg,#3B0B24,#C2185B,#FF3DAE);}
.rocket-top{background:linear-gradient(135deg,#1a0e4a,#5b21b6,var(--purple));}

.game-icon{
  width:48px;
  height:48px;
  border-radius:12px;
  background:rgba(255,255,255,.15);
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:1.8rem;
  color:#fff;
  flex-shrink:0;
  animation:icon-bob 3s ease-in-out infinite;
  box-shadow:0 4px 12px rgba(0,0,0,.3);
  line-height:1;
  font-family: "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji", sans-serif;
}

@keyframes icon-bob{
  0%,100%{transform:translateY(0);}
  50%{transform:translateY(-5px);}
}

.lvl-badge{
  background:rgba(0,0,0,.35);
  border:1px solid rgba(255,255,255,.4);
  border-radius:6px;
  padding:4px 12px;
  font-family:'Space Mono',monospace;
  font-size:0.78rem;
  font-weight:700;
  color:#fff;
  text-transform:uppercase;
  display:inline-flex;
  align-items:center;
  gap:4px;
}

.game-title{
  font-family:'Bungee',sans-serif;
  font-size:1rem;
  color:#fff;
  margin:12px 0 4px;
  line-height:1.2;
}

.game-meta{
  font-family:'Space Mono',monospace;
  font-size:0.7rem;
  color:rgba(255,255,255,.6);
  margin:0;
}

.card-body-custom{
  padding:20px;
  background:rgba(0,0,0,.15);
}

.game-desc{
  font-family:'Baloo 2',sans-serif;
  font-size:0.85rem;
  color:var(--muted);
  line-height:1.6;
  margin-bottom:14px;
}

.progress-label{
  font-family:'Space Mono',monospace;
  font-size:0.7rem;
  color:var(--muted2);
  text-transform:uppercase;
  letter-spacing:.5px;
  margin-bottom:6px;
  display:flex;
  justify-content:space-between;
}

.progress-track{
  height:8px;
  border-radius:6px;
  background:rgba(255,255,255,.08);
  overflow:hidden;
  margin-bottom:16px;
}

.progress-fill{
  height:100%;
  border-radius:6px;
  transition:width 1.2s cubic-bezier(.4,0,.2,1);
  width:0;
}

.play-btn{
  border:none;
  border-radius:10px;
  padding:10px 20px;
  color:#0E0F26;
  font-family:'Bungee',sans-serif;
  font-size:0.82rem;
  text-decoration:none;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  transition:all .2s;
  width:100%;
  font-weight:400;
  letter-spacing:.3px;
}

.play-btn:hover{
  transform:translateY(-2px) scale(1.02);
  color:#0E0F26;
  text-decoration:none;
}

.snake-btn{background:var(--lime);box-shadow:var(--glow-lime);}
.wars-btn{background:var(--pink);box-shadow:var(--glow-pink);}
.rocket-btn{background:var(--cyan);box-shadow:var(--glow-cyan);}

.no-games{
  background:var(--card-bg);
  border:2px solid var(--card-border);
  border-radius:16px;
  padding:48px;
  text-align:center;
}

.no-games-icon{
  font-size:3rem;
  color:var(--muted2);
  margin-bottom:1rem;
}

.no-games-text{
  font-family:'Baloo 2',sans-serif;
  font-size:1.1rem;
  font-weight:700;
  color:var(--muted);
}
</style>

<div id="math-float" aria-hidden="true"></div>

<div style="position:relative;z-index:1;">

<div class="page-header">
  <div class="container-lg px-4">
    <a href="{{ route('student.dashboard') }}" class="back-btn mb-3">
      <i class="bi bi-arrow-left"></i>Dashboard
    </a>
    <h1 class="page-title"><i class="bi bi-controller me-2" style="color:var(--cyan);"></i>Game Arena</h1>
    <p class="page-subtitle">Choose a game and start playing!</p>
  </div>
</div>

<div class="container-lg px-4 pt-4 pb-5">
  @if($games->isEmpty())
  <div class="no-games">
    <i class="bi bi-controller no-games-icon"></i>
    <p class="no-games-text">No games available yet.</p>
  </div>
  @else
  <div class="row g-4">
    @foreach($games as $game)
    @php
      $sp      = $progress[$game->id] ?? null;
      $cl      = $sp->current_level ?? 1;
      $hi      = $sp->avg_score ?? 0;
      $pct     = $hi > 0 ? round(($cl / $game->total_levels) * 100) : 0;
      
      // Map game names to emojis and colors
      $gameEmojis = [
        'math_card_match' => '&#x1F0CF;',  // 🃏
        'number_wars' => '&#x2694;&#xFE0F;',  // ⚔️
        'rocket_math' => '&#x1F680;',  // 🚀
        'factor_pond' => '&#x1F438;',  // 🐸
        'time_quest' => '&#x1F30D;',  // 🌍
        'mdas_maze' => '&#x1F3B2;',  // 🎲
        'the_metric_quest' => '&#x1F4CF;',  // 📏
        'geometry_grid' => '&#x1F4D0;'  // 📐
      ];
      
      $gameColors = [
        'math_card_match' => '#B6FF3D',
        'number_wars' => '#FF3DAE',
        'rocket_math' => '#00E5FF',
        'factor_pond' => '#5EEAD4',
        'time_quest' => '#FFD23D',
        'mdas_maze' => '#B026FF',
        'the_metric_quest' => '#FF9800',
        'geometry_grid' => '#4CAF50'
      ];
      
      $gameStyles = [
        'math_card_match' => 'snake',
        'number_wars' => 'wars',
        'rocket_math' => 'rocket',
        'factor_pond' => 'snake',
        'time_quest' => 'wars',
        'mdas_maze' => 'rocket',
        'the_metric_quest' => 'snake',
        'geometry_grid' => 'wars'
      ];
      
      $emoji  = $gameEmojis[$game->name] ?? '🎮';
      $col    = $gameColors[$game->name] ?? '#00E5FF';
      $cls    = $gameStyles[$game->name] ?? 'snake';
      $topCls = $cls . '-top';
      $btnCls = $cls . '-btn';
    @endphp
    <div class="col-md-6">
      <div class="game-card {{ $cls }}">
        <div class="card-top {{ $topCls }}">
          <div class="d-flex align-items-start justify-content-between">
            <div class="game-icon">{!! $emoji !!}</div>
            {{-- Debug: {{ $game->name }} => {{ $emoji }} --}}
            <div class="lvl-badge">
              <i class="bi bi-layers"></i> Level {{ $cl }}
            </div>
          </div>
          <p class="game-title">{{ $game->display_name }}</p>
          <p class="game-meta">
            {{ $game->total_levels }} levels &nbsp;●&nbsp; {{ number_format($hi, 1) }} avg pts
          </p>
        </div>
        <div class="card-body-custom">
          <p class="game-desc">{{ $game->description }}</p>
          <div class="progress-label">
            <span>Progress</span>
            <span>{{ $cl }} / {{ $game->total_levels }}</span>
          </div>
          <div class="progress-track">
            <div class="progress-fill" style="background:{{ $col }};" data-width="{{ $pct }}"></div>
          </div>
          <a href="{{ route('student.map', $game->name) }}" class="play-btn {{ $btnCls }}">
            <i class="bi bi-map-fill"></i> View Map
          </a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  const syms=['π','∑','√','∞','÷','×','±','∆','∫','≠','α','β','θ'];
  const wrap=document.getElementById('math-float');
  for(let i=0;i<45;i++){
    const el=document.createElement('div');
    el.className='math-symbol';
    el.textContent=syms[Math.floor(Math.random()*syms.length)];
    el.style.left=Math.random()*100+'%';
    el.style.fontSize=(.7+Math.random()*1.1)+'rem';
    el.style.animationDelay=Math.random()*12+'s';
    el.style.animationDuration=(8+Math.random()*10)+'s';
    el.style.color = ['#00E5FF', '#FF3DAE', '#B6FF3D', '#B026FF'][Math.floor(Math.random()*4)];
    wrap.appendChild(el);
  }
})();
setTimeout(()=>{
  document.querySelectorAll('[data-width]').forEach(b=>{ b.style.width=b.dataset.width+'%'; });
},500);
</script>
@endsection