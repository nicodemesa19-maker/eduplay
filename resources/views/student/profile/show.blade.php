@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Bungee&family=Baloo+2:wght@500;600;700;800&family=Space+Mono:wght@400;700&display=swap');

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
  --text:#1B1D3D;
  --text2:#12132A;
  --muted:rgba(27,29,61,.6);
  --card-bg:#ffffff;
  --card-border:rgba(124,58,237,.15);
}

*{box-sizing:border-box;}
html{ -webkit-text-size-adjust:100%; }
body{
  background:var(--bg);
  font-family:'Baloo 2',sans-serif;
  color:var(--text);
  background-image:repeating-linear-gradient(180deg,rgba(255,255,255,.015) 0 2px,transparent 2px 4px);
  -webkit-tap-highlight-color:transparent;
}
[data-bs-theme="light"] body{background-image:none;}
h1,h2,h3{font-family:'Bungee',sans-serif;}

/* ── Hero ── */
.hero{
  background:radial-gradient(circle at 20% 20%,rgba(176,38,255,.18),transparent 55%),
             radial-gradient(circle at 85% 30%,rgba(0,229,255,.16),transparent 55%),
             var(--bg2);
  border-bottom:3px solid var(--cyan);
  padding:clamp(24px, 4vw, 38px) 0 clamp(20px, 3.5vw, 32px);
  box-shadow:0 4px 0 rgba(0,229,255,.15);
}

.hero-title{
  font-size:clamp(1.5rem, 4.5vw, 2.5rem);
  color:var(--cyan);
  text-shadow:var(--glow-cyan);
  margin:0;
  line-height:1.15;
  overflow-wrap:anywhere;
}

/* ── Buttons ── */
.btn-neon{
  background:linear-gradient(135deg,var(--cyan),var(--purple));
  border:none;
  color:#fff;
  font-weight:700;
  padding:12px 28px;
  border-radius:8px;
  transition:transform .2s, box-shadow .2s, background .2s;
  box-shadow:0 4px 14px rgba(0,229,255,.3);
  text-decoration:none;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:6px;
  min-height:44px;
  touch-action:manipulation;
}
.btn-neon:hover{
  transform:translateY(-2px);
  box-shadow:0 6px 20px rgba(0,229,255,.5);
  color:#fff;
}
.btn-neon i { font-size:1rem; line-height:1; }

/* ── Profile card shell ── */
.profile-card{
  background:var(--card-bg);
  border:2px solid var(--card-border);
  border-radius:16px;
  padding:24px;
  margin-bottom:24px;
}

.profile-card h3{
  font-size:clamp(1rem, 2.6vw, 1.25rem);
  line-height:1.3;
  margin-bottom:20px;
  overflow-wrap:anywhere;
}

/* ── Avatar / emoji ── */
.profile-avatar{
  width:clamp(88px, 26vw, 120px);
  height:clamp(88px, 26vw, 120px);
  border-radius:16px;
  border:3px solid var(--cyan);
  object-fit:cover;
  display:block;
}

.profile-emoji{
  width:clamp(88px, 26vw, 120px);
  height:clamp(88px, 26vw, 120px);
  border-radius:16px;
  background:linear-gradient(135deg,var(--cyan),var(--purple));
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:clamp(3rem, 10vw, 5rem);
  line-height:1;
}

/* ── Name / username ── */
.profile-name{
  font-size:clamp(1.35rem, 5vw, 2rem);
  color:var(--text2);
  margin:12px 0 4px;
  line-height:1.2;
  overflow-wrap:anywhere;
  word-break:break-word;
}
.profile-username{
  font-size:clamp(1rem, 3vw, 1.2rem);
  color:var(--cyan);
  overflow-wrap:anywhere;
  word-break:break-all;
}

/* ── Bio ── */
.profile-bio{
  color:var(--muted);
  font-size:clamp(.95rem, 2.5vw, 1.1rem);
  margin-top:16px;
  line-height:1.5;
  overflow-wrap:anywhere;
}

/* ── Badges ── */
.badge-chip{
  background:rgba(182,255,61,.15);
  border:2px solid var(--lime);
  padding:8px 20px;
  border-radius:20px;
  color:var(--lime);
  font-weight:700;
  display:inline-flex;
  align-items:center;
  gap:8px;
  font-size:clamp(.82rem, 2vw, 1rem);
  max-width:100%;
}
.badge-chip .badge-emoji{ font-size:1.4rem; line-height:1; flex-shrink:0; }
.badge-chip .badge-name{ overflow-wrap:anywhere; }

/* ── Info rows ── */
.info-row{
  padding:14px;
  background:rgba(255,255,255,.03);
  border-radius:10px;
  margin-bottom:10px;
}

.info-label{
  font-size:clamp(.82rem, 2vw, .9rem);
  color:var(--muted);
  font-weight:600;
}

.info-value{
  font-size:clamp(1rem, 2.6vw, 1.1rem);
  color:var(--text2);
  font-weight:600;
  margin-top:4px;
  overflow-wrap:anywhere;
  word-break:break-word;
}

/* ── History button ── */
.btn-history{
  background:rgba(255,255,255,.08);
  color:var(--text);
  padding:12px 28px;
  border-radius:8px;
  text-decoration:none;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  font-weight:600;
  min-height:44px;
  transition:background .2s, transform .12s;
  touch-action:manipulation;
}
.btn-history:hover{ background:rgba(255,255,255,.14); color:var(--text); }

/* ============================================================
   TABLET (≤768px)
   ============================================================ */
@media (max-width:768px){
  .hero-title{ font-size:clamp(1.4rem, 5vw, 1.9rem); }
  .profile-card{ padding:20px; margin-bottom:20px; }
  .info-row{ padding:12px; }
}

/* ============================================================
   PHONE (≤575px)
   ============================================================ */
@media (max-width:575px){
  /* Safe-area hero padding */
  .hero{
    padding:
      max(20px, env(safe-area-inset-top))
      0
      18px;
  }

  /* Hero header stacks */
  .hero .d-flex.justify-content-between {
    flex-direction: column;
    align-items: stretch !important;
    gap: 12px;
  }

  /* Two action buttons become equal-width 2-up row */
  .hero .d-flex.gap-2 {
    width: 100%;
    flex-wrap: nowrap;
  }
  .hero .d-flex.gap-2 > .btn-neon {
    flex: 1 1 0;
    min-width: 0;
    padding: 11px 10px;
    font-size: .82rem;
    gap: 4px;
  }
  .hero .d-flex.gap-2 > .btn-neon i { font-size: .95rem; }
  .hero .d-flex.gap-2 > .btn-neon i.me-2 { margin-right: 0 !important; }

  /* Container padding + safe-area */
  .container.py-5 {
    padding-top: 1.25rem !important;
    padding-bottom: 1.25rem !important;
    padding-left: max(12px, env(safe-area-inset-left));
    padding-right: max(12px, env(safe-area-inset-right));
  }

  /* Profile card */
  .profile-card{
    padding:16px;
    margin-bottom:16px;
    border-radius:14px;
  }
  .profile-card h3{
    margin-bottom:14px;
    font-size:1rem;
  }

  /* Name / username */
  .profile-name{ margin-top:10px; }

  /* Badges */
  .badge-chip{
    padding:6px 14px;
    font-size:.8rem;
    gap:6px;
  }
  .badge-chip .badge-emoji{ font-size:1.15rem; }

  /* Info rows tighter */
  .info-row{ padding:11px; margin-bottom:8px; }
  .info-label{ font-size:.8rem; }
  .info-value{ font-size:1rem; }

  /* Bootstrap column gap tighter */
  .row.g-4 { --bs-gutter-y: 1rem; --bs-gutter-x: .75rem; }
  .row.g-3 { --bs-gutter-y: .75rem; --bs-gutter-x: .75rem; }

  /* History button full width */
  .btn-history{
    width: 100%;
    padding: 12px 16px;
  }

  /* Center the history button wrapper */
  .text-center.mt-4 { padding: 0 12px; }
}

/* ============================================================
   VERY SMALL PHONE (≤400px)
   ============================================================ */
@media (max-width:400px){
  .hero .d-flex.gap-2 > .btn-neon{
    padding: 10px 8px;
    font-size: .76rem;
  }
  .hero .d-flex.gap-2 > .btn-neon i { font-size: .85rem; }

  .profile-card{ padding:14px; }
  .profile-card h3{ font-size:.92rem; }

  .badge-chip{
    padding: 5px 12px;
    font-size: .72rem;
  }
  .badge-chip .badge-emoji{ font-size: 1rem; }

  .info-row{ padding:10px; }
  .info-label{ font-size:.75rem; }
  .info-value{ font-size:.92rem; }

  .btn-history{ font-size:.88rem; }
}

/* ============================================================
   TINY PHONE (≤340px)
   ============================================================ */
@media (max-width:340px){
  .hero .d-flex.gap-2 > .btn-neon{
    padding: 9px 6px;
    font-size: .7rem;
  }
  .profile-card{ padding: 12px; }
  .info-row{ padding: 9px; }
  .info-value{ font-size: .88rem; }
}

/* ============================================================
   Touch feedback (no hover on mobile)
   ============================================================ */
@media (hover: none){
  .btn-neon:active {
    transform: scale(.97);
    box-shadow: 0 2px 8px rgba(0,229,255,.4);
  }
  .btn-history:active {
    background: rgba(255,255,255,.2);
    transform: scale(.98);
  }
  .profile-card:active {
    background: rgba(255,255,255,.055);
  }
}

/* ============================================================
   Reduced motion
   ============================================================ */
@media (prefers-reduced-motion: reduce){
  *, *::before, *::after{
    transition-duration: .001ms !important;
    animation-duration: .001ms !important;
  }
  .btn-neon:hover { transform: none !important; }
}
</style>

<div class="hero">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center gap-3">
      <h1 class="hero-title">👤 My Profile</h1>
      <div class="d-flex gap-2">
        <a href="{{ route('student.profile.edit') }}" class="btn btn-neon">
          <i class="bi bi-pencil-fill me-2"></i>Edit Profile
        </a>
        <a href="{{ route('student.dashboard') }}" class="btn btn-neon">
          <i class="bi bi-arrow-left"></i> Dashboard
        </a>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
  <!-- Profile Header -->
  <div class="profile-card text-center">
    @if($user->avatar_upload_path)
      <img src="{{ $user->getAvatarUrl() }}" alt="Avatar" class="profile-avatar mx-auto">
    @elseif($user->avatar_character && str_starts_with($user->avatar_character, 'avatar'))
      <img src="{{ $user->getAvatarUrl() }}" alt="Avatar" class="profile-avatar mx-auto">
    @else
      <div class="profile-emoji mx-auto">
        {{ $user->avatar_character ? $user->getCharacterEmoji() : '🧑‍🎓' }}
      </div>
    @endif

    <h2 class="profile-name">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</h2>
    <p class="profile-username">{{ '@' . $user->username }}</p>

    @if($user->bio)
      <p class="profile-bio">"{{ $user->bio }}"</p>
    @endif

    @if($user->display_badge && is_array($user->display_badge) && count($user->display_badge) > 0)
      <div class="mt-3" style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
        @foreach($user->badges()->whereIn('id', $user->display_badge)->get() as $badge)
          <span class="badge-chip">
            <span class="badge-emoji">{{ $badge->badge_icon }}</span>
            <span class="badge-name">{{ $badge->badge_name }}</span>
          </span>
        @endforeach
      </div>
    @endif
  </div>

  <div class="row g-4">
    <!-- Basic Info -->
    <div class="col-md-6">
      <div class="profile-card">
        <h3 style="color:var(--lime);">📋 Basic Info</h3>

        <div class="info-row">
          <div class="info-label">Student ID</div>
          <div class="info-value">{{ $user->student_id_number ?? 'Not assigned' }}</div>
        </div>

        <div class="info-row">
          <div class="info-label">Grade Level</div>
          <div class="info-value">Grade {{ $user->grade_level }}</div>
        </div>

        <div class="info-row">
          <div class="info-label">Nickname</div>
          <div class="info-value">{{ $user->nickname ?? 'Not set' }}</div>
        </div>

        <div class="info-row">
          <div class="info-label">Birthdate</div>
          <div class="info-value">{{ $user->birthdate ? $user->birthdate->format('F j, Y') : 'Not set' }}</div>
        </div>
      </div>
    </div>

    <!-- Learning Preferences -->
    <div class="col-md-6">
      <div class="profile-card">
        <h3 style="color:var(--purple);">📚 Learning Preferences</h3>

        <div class="info-row">
          <div class="info-label">Favorite Math Topic</div>
          <div class="info-value">{{ $user->favorite_math_topic ?? 'Not set' }}</div>
        </div>

        <div class="info-row">
          <div class="info-label">Game Mode Preference</div>
          <div class="info-value">{{ $user->game_mode_preference ?? 'Not set' }}</div>
        </div>

        <div class="info-row">
          <div class="info-label">Hobbies</div>
          <div class="info-value">
            @if($user->hobbies && count($user->hobbies) > 0)
              {{ implode(', ', $user->hobbies) }}
            @else
              Not set
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Sound Settings -->
  <div class="profile-card">
    <h3 style="color:var(--cyan);">🔊 Sound Settings</h3>
    <div class="row g-3">
      <div class="col-md-6">
        <div class="info-row">
          <div class="info-label">Sound Effects Volume</div>
          <div class="info-value">{{ $user->sound_effects_volume ?? 80 }}%</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="info-row">
          <div class="info-label">Music Volume</div>
          <div class="info-value">{{ $user->music_volume ?? 60 }}%</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Notifications -->
  <div class="profile-card">
    <h3 style="color:var(--pink);">🔔 Notification Preferences</h3>
    <div class="row g-3">
      <div class="col-md-6">
        <div class="info-row">
          <div class="info-label">Practice Reminders</div>
          <div class="info-value">{{ $user->practice_reminders ? '✅ Enabled' : '❌ Disabled' }}</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="info-row">
          <div class="info-label">Streak Alerts</div>
          <div class="info-value">{{ $user->streak_alerts ? '✅ Enabled' : '❌ Disabled' }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="text-center mt-4">
    <a href="{{ route('student.profile.history') }}" class="btn-history">
      <i class="bi bi-clock-history"></i> View Change History
    </a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection