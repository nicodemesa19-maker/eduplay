@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2:family=Bungee&family=Baloo+2:wght@500;600;700;800&family=Space+Mono:wght@400;700&display=swap');

:root{
  --bg:#0E0F26;
  --bg2:#171A3D;
  --cyan:#00E5FF;
  --pink:#FF3DAE;
  --lime:#B6FF3D;
  --purple:#B026FF;
  --text:#EAF6FF;
  --text2:#ffffff;
  --muted:rgba(234,246,255,.55);
  --card-bg:rgba(255,255,255,.045);
  --card-border:rgba(0,229,255,.16);
  --glow-cyan:0 0 22px rgba(0,229,255,.35);
}

[data-bs-theme="light"]{
  --bg:#EEF1FB;
  --bg2:#E4E9FB;
  --text:#1B1D3D;
  --text2:#12132A;
  --muted:rgba(27,29,61,.6);
  --card-bg:#ffffff;
  --card-border:rgba(124,58,237,.15);
}

*{box-sizing:border-box;}
body{background:var(--bg);font-family:'Baloo 2',sans-serif;color:var(--text);
  background-image:repeating-linear-gradient(180deg,rgba(255,255,255,.015) 0 2px,transparent 2px 4px);}
[data-bs-theme="light"] body{background-image:none;}
h1,h2,h3{font-family:'Bungee',sans-serif;}
.mono{font-family:'Space Mono',monospace;}

.hero{
  background:radial-gradient(circle at 20% 20%,rgba(176,38,255,.18),transparent 55%),
             radial-gradient(circle at 85% 30%,rgba(0,229,255,.16),transparent 55%),
             var(--bg2);
  border-bottom:3px solid var(--cyan);
  padding:38px 0 32px;
  box-shadow:0 4px 0 rgba(0,229,255,.15);
}

.hero-title{font-size:2.5rem;color:var(--cyan);text-shadow:var(--glow-cyan);margin:0;}
.hero-sub{color:var(--muted);font-size:1rem;margin-top:8px;}

.timeline{position:relative;padding:20px 0;}
.timeline-item{
  position:relative;
  padding:20px;
  margin-bottom:20px;
  background:var(--card-bg);
  border:2px solid var(--card-border);
  border-radius:12px;
  box-shadow:0 4px 12px rgba(0,0,0,.2);
}

.timeline-badge{
  display:inline-block;
  padding:4px 12px;
  background:rgba(0,229,255,.15);
  border:1px solid var(--cyan);
  border-radius:20px;
  font-size:0.85rem;
  color:var(--cyan);
  font-weight:600;
}

.timeline-badge-teacher{
  background:rgba(255,61,174,.15);
  border-color:var(--pink);
  color:var(--pink);
}

.timeline-time{
  color:var(--muted);
  font-size:0.9rem;
  margin-top:8px;
}

.change-detail{
  margin-top:12px;
  padding:12px;
  background:rgba(255,255,255,.05);
  border-radius:8px;
}

.change-label{color:var(--muted);font-size:0.85rem;}
.change-value{color:var(--text2);font-weight:600;}
.change-arrow{color:var(--lime);margin:0 8px;}

@media(max-width:768px){
  .hero-title{font-size:1.8rem;}
}
</style>

<div class="hero">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h1 class="hero-title">🕒 Change History</h1>
        <p class="hero-sub">Track all your profile updates</p>
      </div>
      <a href="{{ route('student.profile.edit') }}" class="btn btn-neon">
        <i class="bi bi-arrow-left"></i> Back to Profile
      </a>
    </div>
  </div>
</div>

<div class="container py-5">
  @if($logs->count() > 0)
    <div class="timeline">
      @foreach($logs as $log)
        <div class="timeline-item">
          <div class="d-flex justify-content-between align-items-start flex-wrap">
            <div>
              <span class="timeline-badge {{ $log->changed_by === 'teacher' ? 'timeline-badge-teacher' : '' }}">
                {{ $log->changed_by === 'teacher' ? '👨‍🏫 Teacher' : '👤 You' }}
              </span>
              <h5 class="mt-2" style="color:var(--text2);">
                Changed {{ ucfirst(str_replace('_', ' ', $log->field_name)) }}
              </h5>
            </div>
            <div class="timeline-time">
              <i class="bi bi-clock"></i> {{ $log->getFormattedDate() }}
            </div>
          </div>
          
          <div class="change-detail">
            <span class="change-label">From:</span>
            <span class="change-value">{{ $log->getFormattedOldValue() }}</span>
            <span class="change-arrow">→</span>
            <span class="change-label">To:</span>
            <span class="change-value">{{ $log->getFormattedNewValue() }}</span>
          </div>

          @if($log->reason)
            <div class="mt-2">
              <span class="change-label">Reason:</span>
              <span class="change-value">{{ $log->reason }}</span>
            </div>
          @endif
        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
      {{ $logs->links() }}
    </div>
  @else
    <div class="text-center py-5">
      <div style="font-size:4rem;margin-bottom:20px;">📝</div>
      <h3 style="color:var(--muted);">No Changes Yet</h3>
      <p style="color:var(--muted);">Your profile change history will appear here.</p>
      <a href="{{ route('student.profile.edit') }}" class="btn btn-neon mt-3">
        Edit Profile
      </a>
    </div>
  @endif
</div>

<style>
.btn-neon{
  background:linear-gradient(135deg,var(--cyan),var(--purple));
  border:none;
  color:#fff;
  font-weight:700;
  padding:12px 28px;
  border-radius:8px;
  font-size:1rem;
  transition:all .3s;
  box-shadow:0 4px 14px rgba(0,229,255,.3);
}
.btn-neon:hover{
  transform:translateY(-2px);
  box-shadow:0 6px 20px rgba(0,229,255,.5);
  color:#fff;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
