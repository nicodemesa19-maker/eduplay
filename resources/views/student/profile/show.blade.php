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
body{background:var(--bg);font-family:'Baloo 2',sans-serif;color:var(--text);
  background-image:repeating-linear-gradient(180deg,rgba(255,255,255,.015) 0 2px,transparent 2px 4px);}
[data-bs-theme="light"] body{background-image:none;}
h1,h2,h3{font-family:'Bungee',sans-serif;}

.hero{
  background:radial-gradient(circle at 20% 20%,rgba(176,38,255,.18),transparent 55%),
             radial-gradient(circle at 85% 30%,rgba(0,229,255,.16),transparent 55%),
             var(--bg2);
  border-bottom:3px solid var(--cyan);
  padding:38px 0 32px;
  box-shadow:0 4px 0 rgba(0,229,255,.15);
}

.hero-title{font-size:2.5rem;color:var(--cyan);text-shadow:var(--glow-cyan);margin:0;}

.profile-card{
  background:var(--card-bg);
  border:2px solid var(--card-border);
  border-radius:16px;
  padding:24px;
  margin-bottom:24px;
}

.profile-avatar{
  width:120px;
  height:120px;
  border-radius:16px;
  border:3px solid var(--cyan);
  object-fit:cover;
}

.profile-emoji{
  width:120px;
  height:120px;
  border-radius:16px;
  background:linear-gradient(135deg,var(--cyan),var(--purple));
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:5rem;
}

.profile-name{font-size:2rem;color:var(--text2);margin:12px 0 4px;}
.profile-username{font-size:1.2rem;color:var(--cyan);}

.info-row{
  padding:14px;
  background:rgba(255,255,255,.03);
  border-radius:10px;
  margin-bottom:10px;
}

.info-label{font-size:0.9rem;color:var(--muted);font-weight:600;}
.info-value{font-size:1.1rem;color:var(--text2);font-weight:600;margin-top:4px;}

.btn-neon{
  background:linear-gradient(135deg,var(--cyan),var(--purple));
  border:none;
  color:#fff;
  font-weight:700;
  padding:12px 28px;
  border-radius:8px;
  transition:all .3s;
  box-shadow:0 4px 14px rgba(0,229,255,.3);
  text-decoration:none;
  display:inline-block;
}

.btn-neon:hover{
  transform:translateY(-2px);
  box-shadow:0 6px 20px rgba(0,229,255,.5);
  color:#fff;
}

@media(max-width:768px){
  .hero-title{font-size:1.8rem;}
  .profile-avatar,.profile-emoji{width:90px;height:90px;}
  .profile-emoji{font-size:3.5rem;}
}
</style>

<div class="hero">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
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
      <p style="color:var(--muted);font-size:1.1rem;margin-top:16px;">
        "{{ $user->bio }}"
      </p>
    @endif
    
    @if($user->display_badge && is_array($user->display_badge) && count($user->display_badge) > 0)
      <div class="mt-3" style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
        @foreach($user->badges()->whereIn('id', $user->display_badge)->get() as $badge)
          <span style="background:rgba(182,255,61,.15);border:2px solid var(--lime);padding:8px 20px;border-radius:20px;color:var(--lime);font-weight:700;display:inline-flex;align-items:center;gap:8px;">
            <span style="font-size:1.5rem;">{{ $badge->badge_icon }}</span>
            <span>{{ $badge->badge_name }}</span>
          </span>
        @endforeach
      </div>
    @endif
  </div>

  <div class="row g-4">
    <!-- Basic Info -->
    <div class="col-md-6">
      <div class="profile-card">
        <h3 style="color:var(--lime);margin-bottom:20px;">📋 Basic Info</h3>
        
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
        <h3 style="color:var(--purple);margin-bottom:20px;">📚 Learning Preferences</h3>
        
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
    <h3 style="color:var(--cyan);margin-bottom:20px;">🔊 Sound Settings</h3>
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
    <h3 style="color:var(--pink);margin-bottom:20px;">🔔 Notification Preferences</h3>
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
    <a href="{{ route('student.profile.history') }}" class="btn" style="background:rgba(255,255,255,.08);color:var(--text);padding:12px 28px;border-radius:8px;text-decoration:none;">
      <i class="bi bi-clock-history me-2"></i>View Change History
    </a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
