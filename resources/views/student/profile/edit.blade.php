@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
@import url('https://fonts.googleapis.com/css2?family=Bungee&family=Baloo+2:wght@500;600;700;800&family=Space+Mono:wght@400;700&display=swap');

:root{
  --bg:#0E0F26;
  --bg2:#171A3D;
  --surface:rgba(255,255,255,.04);
  --border:rgba(0,229,255,.22);
  --cyan:#00E5FF;
  --pink:#FF3DAE;
  --lime:#B6FF3D;
  --purple:#B026FF;
  --yellow:#FFD23D;
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
  --surface:#ffffff;
  --border:rgba(124,58,237,.18);
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
h1,h2,h3,.font-display{font-family:'Bungee',sans-serif;}
.mono{font-family:'Space Mono',monospace;}

/* Hero header */
.hero{
  background:radial-gradient(circle at 20% 20%,rgba(176,38,255,.18),transparent 55%),
             radial-gradient(circle at 85% 30%,rgba(0,229,255,.16),transparent 55%),
             var(--bg2);
  border-bottom:3px solid var(--cyan); padding:38px 0 32px;
  box-shadow:0 4px 0 rgba(0,229,255,.15);
}

.hero-title{font-size:2.5rem;color:var(--cyan);text-shadow:var(--glow-cyan);margin:0;}
.hero-sub{color:var(--muted);font-size:1rem;margin-top:8px;}

/* Card styles */
.profile-card{
  background:var(--card-bg);
  border:2px solid var(--card-border);
  border-radius:16px;
  padding:24px;
  margin-bottom:24px;
  box-shadow:0 8px 24px rgba(0,0,0,.3);
  position:relative;
  z-index:1;
}

.profile-card h3{
  font-size:1.5rem;
  color:var(--lime);
  margin-bottom:20px;
  text-shadow:0 0 12px rgba(182,255,61,.4);
}

.form-label{
  color:var(--text);
  font-weight:600;
  margin-bottom:8px;
  font-size:0.95rem;
}

.form-control, .form-select{
  background:rgba(255,255,255,.08);
  border:2px solid var(--border);
  color:var(--text);
  border-radius:8px;
  padding:10px 14px;
  font-size:0.95rem;
  position:relative;
}

.form-select{
  position:relative;
  z-index:10;
}

.form-select:focus{
  z-index:999;
}

/* Fix dropdown menu visibility */
.form-select option{
  background:var(--bg2);
  color:var(--text);
  padding:8px 12px;
}

[data-bs-theme="light"] .form-select option{
  background:#ffffff;
  color:var(--text);
}

/* Ensure dropdown has proper backdrop */
.form-select{
  cursor:pointer;
}

.form-select::-ms-expand{
  display:none;
}

.profile-card:has(.form-select:focus){
  z-index:100;
  overflow:visible;
}

.form-control:focus, .form-select:focus{
  background:rgba(255,255,255,.12);
  border-color:var(--cyan);
  box-shadow:var(--glow-cyan);
  color:var(--text);
}

.form-control::placeholder{color:var(--muted);opacity:0.7;}

/* Buttons */
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

.btn-neon-pink{
  background:linear-gradient(135deg,var(--pink),var(--purple));
  box-shadow:0 4px 14px rgba(255,61,174,.3);
}

.btn-neon-pink:hover{
  box-shadow:0 6px 20px rgba(255,61,174,.5);
}

.btn-neon-lime{
  background:linear-gradient(135deg,var(--lime),#00E5FF);
  color:#0E0F26;
  box-shadow:0 4px 14px rgba(182,255,61,.3);
}

.btn-neon-lime:hover{
  box-shadow:0 6px 20px rgba(182,255,61,.5);
  color:#0E0F26;
}

/* Alert messages */
.alert{
  border-radius:12px;
  border:2px solid;
  font-weight:600;
}

.alert-success{
  background:rgba(182,255,61,.15);
  border-color:var(--lime);
  color:var(--lime);
}

.alert-danger{
  background:rgba(255,61,174,.15);
  border-color:var(--pink);
  color:var(--pink);
}

.alert-warning{
  background:rgba(255,210,61,.15);
  border-color:var(--yellow);
  color:var(--yellow);
}

/* Read-only section */
.read-only-section{
  background:rgba(176,38,255,.08);
  border:2px solid rgba(176,38,255,.3);
  border-radius:12px;
  padding:20px;
  margin-bottom:24px;
}

.read-only-label{color:var(--muted);font-size:0.85rem;margin:0;}
.read-only-value{color:var(--text2);font-weight:700;font-size:1.1rem;}

/* Hobby selector */
.hobby-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill, minmax(140px,1fr));
  gap:12px;
  margin-top:12px;
}

.hobby-item{
  position:relative;
}

.hobby-checkbox{
  position:absolute;
  opacity:0;
}

.hobby-label{
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  padding:16px 12px;
  background:var(--card-bg);
  border:2px solid var(--border);
  border-radius:12px;
  cursor:pointer;
  transition:all .2s;
  text-align:center;
  min-height:100px;
}

.hobby-icon{
  font-size:2rem;
  margin-bottom:8px;
}

.hobby-name{
  font-size:0.85rem;
  color:var(--text);
}

.hobby-checkbox:checked + .hobby-label{
  background:rgba(0,229,255,.18);
  border-color:var(--cyan);
  box-shadow:var(--glow-cyan);
}

.hobby-checkbox:disabled + .hobby-label{
  opacity:0.5;
  cursor:not-allowed;
}

/* Badge selector */
.badge-grid{
  display:grid;
  grid-template-columns:repeat(auto-fill, minmax(200px,1fr));
  gap:12px;
  margin-top:12px;
}

.badge-item{
  position:relative;
}

.badge-checkbox{
  position:absolute;
  opacity:0;
}

.badge-label{
  display:flex;
  align-items:center;
  gap:12px;
  padding:14px 16px;
  background:var(--card-bg);
  border:2px solid var(--border);
  border-radius:12px;
  cursor:pointer;
  transition:all .2s;
  min-height:80px;
}

.badge-icon{
  font-size:2.5rem;
  flex-shrink:0;
}

.badge-info{
  flex:1;
  text-align:left;
}

.badge-name{
  font-size:0.95rem;
  font-weight:700;
  color:var(--text);
  margin-bottom:4px;
}

.badge-type{
  font-size:0.8rem;
  color:var(--muted);
  text-transform:uppercase;
  letter-spacing:0.5px;
}

.badge-checkbox:checked + .badge-label{
  background:rgba(255,61,174,.18);
  border-color:var(--pink);
  box-shadow:0 0 22px rgba(255,61,174,.35);
}

.badge-checkbox:disabled + .badge-label{
  opacity:0.5;
  cursor:not-allowed;
}

/* Avatar selector */
.avatar-selector{
  display:flex;
  gap:12px;
  margin-top:12px;
  flex-wrap:wrap;
}

.avatar-option{
  position:relative;
}

.avatar-radio{
  position:absolute;
  opacity:0;
}

.avatar-box{
  width:80px;
  height:80px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:3rem;
  background:var(--card-bg);
  border:2px solid var(--border);
  border-radius:12px;
  cursor:pointer;
  transition:all .2s;
}

.avatar-radio:checked + .avatar-box{
  border-color:var(--cyan);
  box-shadow:var(--glow-cyan);
  background:rgba(0,229,255,.12);
}

/* Volume slider */
.volume-slider{
  width:100%;
  height:8px;
  border-radius:4px;
  background:rgba(255,255,255,.1);
  outline:none;
  -webkit-appearance:none;
}

.volume-slider::-webkit-slider-thumb{
  -webkit-appearance:none;
  width:20px;
  height:20px;
  border-radius:50%;
  background:var(--cyan);
  cursor:pointer;
  box-shadow:0 0 8px rgba(0,229,255,.5);
}

.volume-slider::-moz-range-thumb{
  width:20px;
  height:20px;
  border-radius:50%;
  background:var(--cyan);
  cursor:pointer;
  border:none;
  box-shadow:0 0 8px rgba(0,229,255,.5);
}

.volume-value{
  display:inline-block;
  min-width:40px;
  color:var(--cyan);
  font-weight:700;
  font-family:'Space Mono',monospace;
}

/* Responsive */
@media(max-width:768px){
  .hero-title{font-size:1.8rem;}
  .hobby-grid{grid-template-columns:repeat(auto-fill, minmax(110px,1fr));}
  .badge-grid{grid-template-columns:1fr;}
  .avatar-box{width:60px;height:60px;font-size:2rem;}
}
</style>

<!-- Hero Header -->
<div class="hero">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h1 class="hero-title">✏️ Edit Profile</h1>
        <p class="hero-sub">Customize your EduPlay experience!</p>
      </div>
      <a href="{{ route('student.profile.show') }}" class="btn btn-neon">
        <i class="bi bi-arrow-left"></i> Back to My Profile
      </a>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="container py-5" style="position:relative;">
  
  <!-- Success/Error Messages -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>
      <strong>Oops!</strong> Please fix the errors below:
      <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Read-Only Info Section -->
  <div class="read-only-section">
    <h5 style="color:var(--purple);margin-bottom:16px;">📋 Your Basic Info (Read-Only)</h5>
    <div class="row g-3">
      <div class="col-md-4">
        <p class="read-only-label">Full Name</p>
        <p class="read-only-value">{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</p>
      </div>
      <div class="col-md-4">
        <p class="read-only-label">Student ID</p>
        <p class="read-only-value">{{ $user->student_id_number ?? 'Not assigned' }}</p>
      </div>
      <div class="col-md-4">
        <p class="read-only-label">Grade Level</p>
        <p class="read-only-value">Grade {{ $user->grade_level }}</p>
      </div>
    </div>
  </div>

  <!-- Username Card -->
  <div class="profile-card">
    <h3>🎮 Username</h3>
    <p style="color:var(--muted);margin-bottom:20px;">
      Change your display name (spaces allowed). You can change it 
      <strong>{{ $user->getRemainingUsernameChanges() }} more time(s) today</strong>.
    </p>
    
    <form method="POST" action="{{ route('student.profile.update-username') }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">Current Username</label>
        <input type="text" class="form-control" value="{{ $user->username }}" readonly>
      </div>
      
      @if($user->canChangeUsername())
      <div class="mb-3">
        <label class="form-label">New Username</label>
        <input type="text" name="username" class="form-control" 
               placeholder="e.g. Juan Dela Cruz or jdelacruz" 
               maxlength="255" required>
        <small class="text-muted">Can contain spaces, letters, and numbers. Must be kid-friendly!</small>
      </div>
      <button type="submit" class="btn btn-neon-pink">
        <i class="bi bi-pencil-fill me-2"></i>Update Username
      </button>
      @else
      <div class="alert alert-warning">
        <i class="bi bi-clock-fill me-2"></i>You've used all 3 username changes for today. Try again tomorrow!
      </div>
      @endif
    </form>
  </div>

  <!-- General Profile Card -->
  <form method="POST" action="{{ route('student.profile.update') }}">
    @csrf
    
    <!-- Personal Info -->
    <div class="profile-card">
      <h3>👤 Personal Info</h3>
      
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nickname</label>
          <input type="text" name="nickname" class="form-control" 
                 value="{{ old('nickname', $user->nickname) }}" 
                 placeholder="What do friends call you?" maxlength="20">
          <small class="text-muted">Max 20 characters</small>
        </div>
        
        <div class="col-md-6">
          <label class="form-label">Birthdate</label>
          <input type="date" name="birthdate" class="form-control" 
                 value="{{ old('birthdate', $user->birthdate ? $user->birthdate->format('Y-m-d') : '') }}" 
                 min="2011-01-01" max="2019-01-01">
          <small class="text-muted">For students ages 8-13</small>
        </div>
      </div>
      
      <div class="mt-3">
        <label class="form-label">Bio / Motto</label>
        <input type="text" name="bio" class="form-control" 
               value="{{ old('bio', $user->bio) }}" 
               placeholder="Share something cool about yourself!" maxlength="60">
        <small class="text-muted">Max 60 characters</small>
      </div>
    </div>

    <!-- Math Preferences -->
    <div class="profile-card">
      <h3>➕ Math Preferences</h3>
      
      <div class="mb-3">
        <label class="form-label">Favorite Math Topic</label>
        <select name="favorite_math_topic" class="form-select">
          <option value="">-- Choose your favorite --</option>
          @foreach($mathTopics as $topic)
            <option value="{{ $topic }}" {{ old('favorite_math_topic', $user->favorite_math_topic) == $topic ? 'selected' : '' }}>
              {{ $topic }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Game Mode Preference</label>
        <select name="game_mode_preference" class="form-select">
          <option value="">-- Choose your style --</option>
          @foreach($gameModes as $mode)
            <option value="{{ $mode }}" {{ old('game_mode_preference', $user->game_mode_preference) == $mode ? 'selected' : '' }}>
              {{ $mode }}
            </option>
          @endforeach
        </select>
      </div>
    </div>

    <!-- Hobbies -->
    <div class="profile-card">
      <h3>🎨 My Hobbies (Pick up to 5)</h3>
      <p style="color:var(--muted);margin-bottom:12px;">Select the activities you love!</p>
      
      <div class="hobby-grid" id="hobbyGrid">
        @php
          $userHobbies = old('hobbies', $user->hobbies ?? []);
          $hobbyIcons = [
            'Reading Books' => '📚',
            'Drawing & Art' => '🎨',
            'Playing Sports' => '⚽',
            'Video Games' => '🎮',
            'Music & Singing' => '🎵',
            'Dancing' => '💃',
            'Cooking & Baking' => '🍪',
            'Science Experiments' => '🔬',
            'Building & Crafts' => '🔨',
            'Outdoor Adventures' => '🏕️',
          ];
        @endphp
        
        @foreach($hobbies as $hobby)
          <div class="hobby-item">
            <input type="checkbox" name="hobbies[]" value="{{ $hobby }}" 
                   id="hobby_{{ $loop->index }}" class="hobby-checkbox"
                   {{ in_array($hobby, $userHobbies) ? 'checked' : '' }}>
            <label for="hobby_{{ $loop->index }}" class="hobby-label">
              <div class="hobby-icon">{{ $hobbyIcons[$hobby] ?? '🎯' }}</div>
              <div class="hobby-name">{{ $hobby }}</div>
            </label>
          </div>
        @endforeach
      </div>
      <small class="text-muted d-block mt-3">
        <span id="hobbyCount">{{ count($userHobbies) }}</span>/5 hobbies selected
      </small>
    </div>

    <!-- Sound Settings -->
    <div class="profile-card">
      <h3>🔊 Sound Settings</h3>
      
      <div class="mb-4">
        <label class="form-label">Sound Effects Volume</label>
        <div class="d-flex align-items-center gap-3">
          <input type="range" name="sound_effects_volume" class="volume-slider flex-grow-1" 
                 min="0" max="100" value="{{ old('sound_effects_volume', $user->sound_effects_volume ?? 80) }}" 
                 id="soundSlider">
          <span class="volume-value"><span id="soundValue">{{ old('sound_effects_volume', $user->sound_effects_volume ?? 80) }}</span>%</span>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Music Volume</label>
        <div class="d-flex align-items-center gap-3">
          <input type="range" name="music_volume" class="volume-slider flex-grow-1" 
                 min="0" max="100" value="{{ old('music_volume', $user->music_volume ?? 60) }}" 
                 id="musicSlider">
          <span class="volume-value"><span id="musicValue">{{ old('music_volume', $user->music_volume ?? 60) }}</span>%</span>
        </div>
      </div>
    </div>

    <!-- Notifications -->
    <div class="profile-card">
      <h3>🔔 Notifications</h3>
      
      <div class="form-check mb-3">
        <input type="hidden" name="practice_reminders" value="0">
        <input type="checkbox" name="practice_reminders" value="1" 
               class="form-check-input" id="practiceReminders" 
               {{ old('practice_reminders', $user->practice_reminders ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="practiceReminders">
          Practice Reminders — Get reminders to keep up your streak!
        </label>
      </div>

      <div class="form-check">
        <input type="hidden" name="streak_alerts" value="0">
        <input type="checkbox" name="streak_alerts" value="1" 
               class="form-check-input" id="streakAlerts" 
               {{ old('streak_alerts', $user->streak_alerts ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="streakAlerts">
          Streak Alerts — Celebrate your learning streaks!
        </label>
      </div>
    </div>

    <!-- Badge Display -->
    <div class="profile-card">
      <h3>🏆 Badge Display</h3>
      <p style="color:var(--muted);margin-bottom:16px;">Select up to 3 badges to showcase on your profile</p>
      
      @if($earnedBadges->count() > 0)
        <div class="badge-grid" id="badgeGrid">
          @php
            $displayBadges = old('display_badge', $user->display_badge ?? []);
          @endphp
          
          @foreach($earnedBadges as $badge)
            <div class="badge-item">
              <input type="checkbox" name="display_badge[]" value="{{ $badge->id }}" 
                     id="badge_{{ $badge->id }}" class="badge-checkbox"
                     {{ in_array($badge->id, $displayBadges) ? 'checked' : '' }}>
              <label for="badge_{{ $badge->id }}" class="badge-label">
                <div class="badge-icon">{{ $badge->badge_icon }}</div>
                <div class="badge-info">
                  <div class="badge-name">{{ $badge->badge_name }}</div>
                  <div class="badge-type">{{ ucfirst($badge->badge_type) }}</div>
                </div>
              </label>
            </div>
          @endforeach
        </div>
        <small class="text-muted d-block mt-3">
          <span id="badgeCount">{{ count($displayBadges) }}</span>/3 badges selected
        </small>
      @else
        <div style="text-align:center;padding:40px 20px;color:var(--muted);">
          <div style="font-size:3rem;margin-bottom:12px;">🎯</div>
          <p style="margin:0;font-size:1rem;">You haven't earned any badges yet!</p>
          <p style="margin:8px 0 0;font-size:0.9rem;">Play games and complete challenges to earn badges.</p>
        </div>
      @endif
    </div>

    <!-- Save Button -->
    <div class="text-center">
      <button type="submit" class="btn btn-neon-lime btn-lg">
        <i class="bi bi-save-fill me-2"></i>Save All Changes
      </button>
    </div>
  </form>

  <!-- Avatar Upload Card -->
  <div class="profile-card mt-4">
    <h3>🎭 Avatar</h3>
    <p style="color:var(--muted);margin-bottom:20px;">Choose a preset avatar or upload your own picture!</p>
    
    <form method="POST" action="{{ route('student.profile.update-avatar') }}" enctype="multipart/form-data">
      @csrf
      
      <div class="mb-4">
        <label class="form-label">Avatar Type</label>
        <select name="avatar_type" class="form-select" id="avatarType">
          <option value="preset">Preset Character</option>
          <option value="upload">Upload Custom</option>
        </select>
      </div>

      <div id="presetSection" class="mb-4">
        <label class="form-label">Select Character</label>
        <div class="avatar-selector">
          @php
            $avatars = [
              'avatar1' => 'Avatar 1',
              'avatar2' => 'Avatar 2',
              'avatar3' => 'Avatar 3',
              'avatar4' => 'Avatar 4',
              'avatar5' => 'Avatar 5',
              'avatar6' => 'Avatar 6',
              'avatar7' => 'Avatar 7',
              'avatar8' => 'Avatar 8',
            ];
          @endphp
          
          @foreach($avatars as $key => $name)
            <div class="avatar-option">
              <input type="radio" name="avatar_preset" value="{{ $key }}" 
                     id="avatar_{{ $key }}" class="avatar-radio"
                     {{ $user->avatar_character == $key ? 'checked' : '' }}>
              <label for="avatar_{{ $key }}" class="avatar-box">
                <img src="{{ asset('images/avatars/' . $key . '.png') }}" 
                     alt="{{ $name }}" 
                     style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
              </label>
            </div>
          @endforeach
        </div>
      </div>

      <div id="uploadSection" style="display:none;">
        <label class="form-label">Upload Picture</label>
        <input type="file" name="avatar_upload" class="form-control" accept="image/*">
        <small class="text-muted">Max 2MB. JPG, PNG, or GIF only.</small>
        @if($user->avatar_upload_path)
          <div class="mt-3">
            <p class="text-muted">Current uploaded avatar:</p>
            <img src="{{ $user->getAvatarUrl() }}" alt="Current avatar" 
                 style="width:100px;height:100px;object-fit:cover;border-radius:12px;border:2px solid var(--cyan);">
          </div>
        @endif
      </div>

      <button type="submit" class="btn btn-neon-pink mt-3">
        <i class="bi bi-image-fill me-2"></i>Update Avatar
      </button>
    </form>
  </div>

  <!-- Password Change Card -->
  <div class="profile-card">
    <h3>🔒 Change Password</h3>
    <p style="color:var(--muted);margin-bottom:20px;">Keep your account secure!</p>
    
    <form method="POST" action="{{ route('student.profile.update-password') }}">
      @csrf
      
      <div class="mb-3">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="password" class="form-control" required minlength="6">
        <small class="text-muted">At least 6 characters</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-neon">
        <i class="bi bi-shield-lock-fill me-2"></i>Update Password
      </button>
    </form>
  </div>

  <!-- View History Link -->
  <div class="text-center mt-4">
    <a href="{{ route('student.profile.history') }}" class="btn btn-outline-secondary">
      <i class="bi bi-clock-history me-2"></i>View Change History
    </a>
  </div>

</div>

<script>
// Volume sliders
document.getElementById('soundSlider').addEventListener('input', function() {
  document.getElementById('soundValue').textContent = this.value;
});
document.getElementById('musicSlider').addEventListener('input', function() {
  document.getElementById('musicValue').textContent = this.value;
});

// Fix dropdown z-index on focus
const allSelects = document.querySelectorAll('.form-select');
allSelects.forEach(select => {
  select.addEventListener('focus', function() {
    const parentCard = this.closest('.profile-card');
    if (parentCard) {
      parentCard.style.zIndex = '999';
    }
  });
  
  select.addEventListener('blur', function() {
    const parentCard = this.closest('.profile-card');
    if (parentCard) {
      parentCard.style.zIndex = '1';
    }
  });
});

// Hobby limit (max 5)
const hobbyCheckboxes = document.querySelectorAll('.hobby-checkbox');
const hobbyCount = document.getElementById('hobbyCount');

function updateHobbyLimit() {
  const checked = document.querySelectorAll('.hobby-checkbox:checked').length;
  hobbyCount.textContent = checked;
  
  // Disable unchecked boxes if 5 selected
  hobbyCheckboxes.forEach(box => {
    if (!box.checked && checked >= 5) {
      box.disabled = true;
    } else {
      box.disabled = false;
    }
  });
}

hobbyCheckboxes.forEach(box => {
  box.addEventListener('change', updateHobbyLimit);
});
updateHobbyLimit();

// Badge limit (max 3)
const badgeCheckboxes = document.querySelectorAll('.badge-checkbox');
const badgeCount = document.getElementById('badgeCount');

function updateBadgeLimit() {
  const checked = document.querySelectorAll('.badge-checkbox:checked').length;
  if (badgeCount) {
    badgeCount.textContent = checked;
  }
  
  // Disable unchecked boxes if 3 selected
  badgeCheckboxes.forEach(box => {
    if (!box.checked && checked >= 3) {
      box.disabled = true;
    } else {
      box.disabled = false;
    }
  });
}

badgeCheckboxes.forEach(box => {
  box.addEventListener('change', updateBadgeLimit);
});
updateBadgeLimit();

// Avatar type toggle
document.getElementById('avatarType').addEventListener('change', function() {
  const presetSection = document.getElementById('presetSection');
  const uploadSection = document.getElementById('uploadSection');
  
  if (this.value === 'upload') {
    presetSection.style.display = 'none';
    uploadSection.style.display = 'block';
  } else {
    presetSection.style.display = 'block';
    uploadSection.style.display = 'none';
  }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
