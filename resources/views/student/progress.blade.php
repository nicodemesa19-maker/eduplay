@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@700;800&display=swap');

*{box-sizing:border-box;}
body{background:#0a0c18;font-family:'Inter',sans-serif;color:#e2e8f0;overflow-x:hidden;}

/* MATH RAIN */
#math-rain{position:fixed;inset:0;pointer-events:none;z-index:0;overflow:hidden;}
.rain-col{
  position:absolute;top:-120px;
  font-family:'Courier New',monospace;font-size:.85rem;font-weight:700;
  color:rgba(99,102,241,.45);text-shadow:0 0 8px rgba(99,102,241,.3);
  animation:rain-fall linear infinite;white-space:nowrap;
  writing-mode:vertical-rl;letter-spacing:6px;user-select:none;
}
@keyframes rain-fall{0%{transform:translateY(-120px);opacity:0;}5%{opacity:1;}90%{opacity:.5;}100%{transform:translateY(110vh);opacity:0;}}

/* FLOATING BUBBLES */
.math-bubble{position:fixed;border-radius:50%;display:flex;align-items:center;justify-content:center;
             font-weight:900;pointer-events:none;z-index:1;animation:bubble-float ease-in-out infinite;}
@keyframes bubble-float{0%,100%{transform:translateY(0) scale(1);}50%{transform:translateY(-20px) scale(1.06);}}

.page-wrap{position:relative;z-index:2;}

/* HERO */
.hero-section{
  background:linear-gradient(135deg,#0d1035 0%,#1a0d45 50%,#0d1035 100%);
  border-bottom:1px solid rgba(99,102,241,.2);
  padding:32px 0 24px;position:relative;overflow:hidden;
}
.hero-section::before{
  content:'';position:absolute;top:-80px;right:-60px;
  width:260px;height:260px;
  background:radial-gradient(circle,rgba(99,102,241,.15) 0%,transparent 70%);
  border-radius:50%;
}

.hero-title{font-family:'Space Grotesk',sans-serif;font-size:1.8rem;font-weight:800;color:#fff;margin:0;
            text-shadow:0 0 30px rgba(99,102,241,.4);}
.hero-sub  {font-size:.85rem;color:rgba(255,255,255,.45);margin:4px 0 0;}

/* STAT CHIP */
.stat-chip{background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.3);
           border-radius:14px;padding:10px 18px;text-align:center;min-width:90px;
           transition:transform .2s,box-shadow .2s;}
.stat-chip:hover{transform:translateY(-3px);box-shadow:0 8px 24px rgba(99,102,241,.3);}
.stat-chip .v{font-size:1.45rem;font-weight:800;color:#fff;line-height:1;}
.stat-chip .l{font-size:.6rem;font-weight:600;color:rgba(255,255,255,.4);
              text-transform:uppercase;letter-spacing:.8px;margin-top:3px;}

/* SEC LABEL */
.sec-lbl{font-size:.65rem;font-weight:700;color:rgba(255,255,255,.3);
         text-transform:uppercase;letter-spacing:1.2px;
         margin-bottom:14px;display:flex;align-items:center;gap:8px;}
.sec-lbl i{color:#818cf8;font-size:.8rem;}
.sec-lbl::after{content:'';flex:1;height:1px;background:rgba(255,255,255,.07);}

/* TOTAL SCORE BANNER */
.score-banner{
  background:linear-gradient(135deg,rgba(99,102,241,.15),rgba(139,92,246,.1));
  border:1px solid rgba(99,102,241,.3);border-radius:20px;padding:24px;
  position:relative;overflow:hidden;margin-bottom:28px;
}
.score-banner::before{
  content:'∑';position:absolute;right:24px;top:50%;transform:translateY(-50%);
  font-size:6rem;font-weight:900;color:rgba(99,102,241,.08);
  font-family:'Courier New',monospace;
}
.score-val{
  font-family:'Space Grotesk',sans-serif;font-size:3rem;font-weight:900;color:#fff;
  text-shadow:0 0 30px rgba(99,102,241,.5);
  animation:score-pulse 3s ease-in-out infinite;
}
@keyframes score-pulse{0%,100%{text-shadow:0 0 20px rgba(99,102,241,.4);}50%{text-shadow:0 0 40px rgba(99,102,241,.7);}}

/* PROG CARD */
.prog-card{
  background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);
  border-radius:20px;overflow:hidden;
  transition:transform .25s,border-color .25s,box-shadow .25s;
}
.prog-card:hover{transform:translateY(-4px);border-color:rgba(99,102,241,.4);
                 box-shadow:0 16px 48px rgba(99,102,241,.18);}

.card-accent{height:4px;}
.acc-green{background:linear-gradient(90deg,#059669,#34d399);}
.acc-red  {background:linear-gradient(90deg,#dc2626,#f87171);}
.acc-ind  {background:linear-gradient(90deg,#4f46e5,#818cf8);}

.game-icon-box{width:44px;height:44px;border-radius:12px;
               display:flex;align-items:center;justify-content:center;
               font-size:1.2rem;flex-shrink:0;}
.ig-green{background:rgba(16,185,129,.15);color:#10b981;border:1px solid rgba(16,185,129,.25);}
.ig-red  {background:rgba(244,63,94,.12);color:#f43f5e;border:1px solid rgba(244,63,94,.2);}
.ig-ind  {background:rgba(99,102,241,.12);color:#818cf8;border:1px solid rgba(99,102,241,.2);}

.progress{height:6px;border-radius:99px;background:rgba(255,255,255,.07);}
.progress-bar{border-radius:99px;transition:width 1.2s ease;}

/* level pips */
.level-pips{display:flex;flex-wrap:wrap;gap:3px;margin-top:8px;}
.pip{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,.08);
     transition:background .3s,box-shadow .3s;}
.pip.done{box-shadow:0 0 4px currentColor;}

/* back link */
.back-btn{
  background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);
  border-radius:8px;padding:7px 14px;font-size:.78rem;font-weight:600;
  color:rgba(255,255,255,.5);text-decoration:none;display:inline-flex;
  align-items:center;gap:6px;transition:all .2s;
}
.back-btn:hover{border-color:rgba(99,102,241,.5);color:#818cf8;}
</style>

<div id="math-rain" aria-hidden="true"></div>
<div id="bubbles" aria-hidden="true"></div>

<div class="page-wrap">

<!-- HERO -->
<div class="hero-section">
  <div class="container-lg px-4">
    <div class="d-flex align-items-start justify-content-between flex-wrap gap-4">
      <div>
        <a href="{{ route('student.dashboard') }}" class="back-btn mb-3">
          <i class="bi bi-arrow-left"></i>Dashboard
        </a>
        <h1 class="hero-title"><i class="bi bi-bar-chart-line me-2" style="color:#818cf8;"></i>My Progress</h1>
        <p class="hero-sub">Your complete performance breakdown</p>
      </div>
      <div class="d-flex gap-3 flex-wrap align-items-center">
        <div class="stat-chip">
          <div class="v" data-target="{{ $totalScore }}" data-count>0</div>
          <div class="l"><i class="bi bi-trophy me-1"></i>Score</div>
        </div>
        <div class="stat-chip">
          <div class="v">{{ $progress->count() }}</div>
          <div class="l"><i class="bi bi-controller me-1"></i>Games</div>
        </div>
        <div class="stat-chip">
          <div class="v" data-target="{{ $progress->sum('current_level') }}" data-count>0</div>
          <div class="l"><i class="bi bi-layers me-1"></i>Levels</div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- CONTENT -->
<div class="container-lg px-4 pt-4 pb-5">

  @if($progress->isEmpty())
  <div class="prog-card p-5 text-center">
    <i class="bi bi-controller d-block mb-3" style="font-size:3rem;color:#475569;"></i>
    <p class="fw-semibold mb-1" style="color:#fff;">No progress yet</p>
    <p style="color:#475569;font-size:.82rem;margin-bottom:20px;">Play a game to start building your record!</p>
    <a href="{{ route('student.dashboard') }}" class="btn btn-sm fw-semibold text-white px-4"
       style="background:#5b5ef4;border-radius:8px;">
      <i class="bi bi-play-fill me-1"></i>Go Play
    </a>
  </div>
  @else

  <!-- Total score banner -->
  <div class="score-banner">
    <div class="d-flex align-items-center gap-4">
      <div style="width:56px;height:56px;border-radius:16px;background:rgba(99,102,241,.2);
                  border:1px solid rgba(99,102,241,.35);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="bi bi-trophy-fill" style="font-size:1.6rem;color:#818cf8;"></i>
      </div>
      <div>
        <p style="font-size:.65rem;font-weight:700;color:rgba(255,255,255,.35);
                  text-transform:uppercase;letter-spacing:1px;margin:0 0 4px;">All-Time Total Score</p>
        <div class="score-val" data-target="{{ $totalScore }}" data-count>0</div>
      </div>
    </div>
  </div>

  <div class="sec-lbl"><i class="bi bi-bar-chart-steps"></i> Game Breakdown</div>

  <div class="row g-3">
    @foreach($progress as $p)
    @php
      $isSnake=$p->game->name==='math_card_match';
      $isWars =$p->game->name==='number_wars';
      $col    =$isSnake?'#10b981':($isWars?'#f43f5e':'#818cf8');
      $accCls =$isSnake?'acc-green':($isWars?'acc-red':'acc-ind');
      $igCls  =$isSnake?'ig-green':($isWars?'ig-red':'ig-ind');
      $biIcon =$isSnake?'bi-card-text':($isWars?'bi-shield-shaded':'bi-joystick');
      $total  =$p->game->total_levels??15;
      // If no score earned yet, show 0% instead of calculating from level 1
      $pct    =$p->avg_score > 0 ? round(($p->current_level/$total)*100) : 0;
      $lvlScores=is_array($p->level_scores)?$p->level_scores:(json_decode($p->level_scores??'{}',true)??[]);
    @endphp
    <div class="col-md-6">
      <div class="prog-card">
        <div class="card-accent {{ $accCls }}"></div>
        <div class="p-4">

          <!-- Header -->
          <div class="d-flex align-items-start gap-3 mb-3">
            <div class="game-icon-box {{ $igCls }}"><i class="bi {{ $biIcon }}"></i></div>
            <div class="flex-grow-1">
              <p class="fw-bold mb-0" style="font-size:.9rem;color:#fff;">{{ $p->game->display_name }}</p>
              <p style="font-size:.72rem;color:#475569;margin:2px 0 0;">
                <i class="bi bi-layers me-1"></i>Level {{ $p->current_level }} / {{ $total }}
              </p>
            </div>
            <div class="text-end">
              <p class="fw-black mb-0" style="font-size:1.4rem;color:{{ $col }};
                 text-shadow:0 0 10px {{ $col }}66;animation:score-pulse 3s ease-in-out infinite;">
                {{ number_format($p->avg_score, 1) }}
              </p>
              <p style="font-size:.6rem;color:#475569;margin:0;">Avg Score</p>
            </div>
          </div>

          <!-- Progress bar -->
          <div class="mb-3">
            <div class="d-flex justify-content-between mb-1" style="font-size:.68rem;color:#475569;">
              <span>Level Progress</span><span>{{ $pct }}%</span>
            </div>
            <div class="progress">
              <div class="progress-bar" style="width:0%;background:{{ $col }};" data-width="{{ $pct }}"></div>
            </div>
          </div>

          <!-- Level pips -->
          <div class="level-pips mb-3">
            @for($i=1;$i<=$total;$i++)
            <div class="pip {{ $i<=$p->current_level?'done':'' }}"
                 style="{{ $i<=$p->current_level?'background:'.$col.';box-shadow:0 0 5px '.$col.';':'' }}">
            </div>
            @endfor
          </div>

          <!-- Level scores -->
          @if(!empty($lvlScores))
          <details class="mb-3">
            <summary style="font-size:.72rem;color:#475569;cursor:pointer;font-weight:600;">
              <i class="bi bi-list-ol me-1"></i>Level scores
            </summary>
            <div class="d-flex flex-wrap gap-1 mt-2">
              @foreach($lvlScores as $lvl => $sc)
              <span style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);
                           border-radius:6px;padding:2px 8px;font-size:.68rem;color:#94a3b8;">
                Lvl {{ $lvl }}: {{ $sc }}
              </span>
              @endforeach
            </div>
          </details>
          @endif

          <a href="{{ route('student.play', $p->game->name) }}"
             class="btn btn-sm fw-semibold w-100 text-white"
             style="border-radius:10px;background:{{ $col }};font-size:.78rem;
                    box-shadow:0 4px 14px {{ $col }}44;">
            <i class="bi bi-play-fill me-1"></i>Continue Playing
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
/* Math rain */
(function(){
  const symbols=['∑','π','√','∞','÷','×','±','∆','∫','≠','²','³','α','β','θ','λ','2+2','5×3','n!'];
  const rain=document.getElementById('math-rain');
  const cols=Math.floor(window.innerWidth/48);
  for(let i=0;i<cols;i++){
    const col=document.createElement('div');
    col.className='rain-col';
    col.style.left=(i*48+Math.random()*16)+'px';
    col.style.animationDuration=(9+Math.random()*14)+'s';
    col.style.animationDelay=-(Math.random()*14)+'s';
    col.style.fontSize=(.55+Math.random()*.45)+'rem';
    col.style.opacity=(.15+Math.random()*.35);
    let txt='';
    for(let j=0;j<16;j++) txt+=symbols[Math.floor(Math.random()*symbols.length)]+' ';
    col.textContent=txt;
    rain.appendChild(col);
  }
})();

/* Floating bubbles */
(function(){
  const items=['π','∑','√','∞','±','∆','∫','α','θ','λ'];
  const wrap=document.getElementById('bubbles');
  items.forEach((sym,i)=>{
    const b=document.createElement('div');
    b.className='math-bubble';
    const size=32+Math.random()*22;
    Object.assign(b.style,{
      width:size+'px',height:size+'px',
      left:(3+Math.random()*90)+'%',top:(8+Math.random()*78)+'%',
      fontSize:(size*.38)+'px',
      color:'rgba('+(99+i*12)+','+(102-i*4)+',241,'+(0.1+Math.random()*.12)+')',
      background:'rgba(99,102,241,'+(0.03+Math.random()*.04)+')',
      border:'1px solid rgba(99,102,241,'+(0.07+Math.random()*.08)+')',
      animationDuration:(6+Math.random()*8)+'s',
      animationDelay:-(Math.random()*8)+'s',
    });
    b.textContent=sym;
    wrap.appendChild(b);
  });
})();

/* Count-up */
document.querySelectorAll('[data-count]').forEach(el=>{
  const target=parseInt(el.dataset.target)||0;
  if(!target){el.textContent='0';return;}
  let cur=0;
  const step=Math.ceil(target/50);
  const t=setInterval(()=>{
    cur=Math.min(cur+step,target);
    el.textContent=cur.toLocaleString();
    if(cur>=target)clearInterval(t);
  },25);
});

/* Animate progress bars */
setTimeout(()=>{
  document.querySelectorAll('[data-width]').forEach(b=>{ b.style.width=b.dataset.width+'%'; });
},500);
</script>
@endsection
