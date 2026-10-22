@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════════════
   🎨 KID-FRIENDLY COLOR PALETTE FOR GRADES 4-6
═══════════════════════════════════════════════════════ */
:root {
  /* Bright, cheerful main colors */
  --primary-blue: #4A90E2;
  --primary-green: #7ED321;
  --primary-orange: #FF9500;
  --primary-purple: #9B59B6;
  --primary-pink: #FF6B9D;
  --primary-yellow: #FFD93D;
  
  /* Soft backgrounds */
  --bg-main: #F7F9FC;
  --bg-card: #FFFFFF;
  --bg-light: #E8F4F8;
  
  /* Text colors */
  --text-dark: #2C3E50;
  --text-medium: #5A6C7D;
  --text-light: #8B9CAE;
  
  /* Game category colors */
  --math-green: #10B981;
  --math-red: #EF4444;
  --math-blue: #3B82F6;
  --math-purple: #8B5CF6;
  
  /* Status colors */
  --success: #22C55E;
  --warning: #F59E0B;
  --info: #06B6D4;
  
  /* Shadows for depth */
  --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
  --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
  --shadow-lg: 0 8px 32px rgba(0,0,0,0.16);
}

/* ═══════════════════════════════════════════════════════
   🎯 BASE STYLES
═══════════════════════════════════════════════════════ */
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'Nunito', sans-serif;
  background: var(--bg-main);
  color: var(--text-dark);
  font-size: 16px;
  line-height: 1.6;
}

h1, h2, h3, h4 {
  font-family: 'Fredoka', sans-serif;
  font-weight: 600;
}

/* ═══════════════════════════════════════════════════════
   🌟 WELCOME HERO SECTION (Kid-Friendly!)
═══════════════════════════════════════════════════════ */
.hero-section {
  background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
  padding: 32px 24px 24px;
  border-radius: 0 0 32px 32px;
  position: relative;
  overflow: hidden;
  box-shadow: var(--shadow-md);
}

.hero-section::before {
  content: '✨';
  position: absolute;
  top: 20px;
  right: 20px;
  font-size: 48px;
  animation: sparkle 2s ease-in-out infinite;
}

@keyframes sparkle {
  0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.8; }
  50% { transform: scale(1.2) rotate(15deg); opacity: 1; }
}

.welcome-greeting {
  color: white;
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 8px;
  text-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.welcome-message {
  color: rgba(255,255,255,0.95);
  font-size: 18px;
  font-weight: 500;
  margin-bottom: 20px;
}

.hero-stats {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.stat-badge {
  background: rgba(255,255,255,0.2);
  backdrop-filter: blur(10px);
  border: 2px solid rgba(255,255,255,0.3);
  padding: 12px 20px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
}

.stat-badge:hover {
  background: rgba(255,255,255,0.3);
  transform: translateY(-2px);
}

.stat-icon {
  font-size: 28px;
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.stat-info {
  text-align: left;
}

.stat-label {
  font-size: 12px;
  color: rgba(255,255,255,0.85);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.stat-value {
  font-size: 24px;
  color: white;
  font-weight: 800;
  font-family: 'Fredoka', sans-serif;
  line-height: 1;
}

/* ═══════════════════════════════════════════════════════
   🎮 QUICK ACTIONS (Big, Colorful Buttons!)
═══════════════════════════════════════════════════════ */
.quick-actions {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 16px;
  padding: 24px;
  max-width: 1200px;
  margin: -40px auto 0;
  position: relative;
  z-index: 10;
}

.action-btn {
  background: white;
  border-radius: 20px;
  padding: 24px 16px;
  text-align: center;
  text-decoration: none;
  color: var(--text-dark);
  box-shadow: var(--shadow-md);
  transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  border: 3px solid transparent;
}

.action-btn:hover {
  transform: translateY(-6px);
  box-shadow: var(--shadow-lg);
  border-color: var(--primary-blue);
}

.action-btn .icon {
  font-size: 48px;
  margin-bottom: 8px;
  display: block;
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
}

.action-btn .label {
  font-size: 16px;
  font-weight: 700;
  color: var(--text-dark);
}

.action-games { border-left: 4px solid var(--math-blue); }
.action-progress { border-left: 4px solid var(--math-green); }
.action-character { border-left: 4px solid var(--math-purple); }
.action-class { border-left: 4px solid var(--primary-orange); }

/* ═══════════════════════════════════════════════════════
   📚 MY GAMES SECTION
═══════════════════════════════════════════════════════ */
.section-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  padding: 0 24px;
}

.section-icon {
  font-size: 32px;
}

.section-title {
  font-size: 24px;
  font-weight: 700;
  color: var(--text-dark);
  font-family: 'Fredoka', sans-serif;
}

.games-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
  padding: 0 24px 24px;
  max-width: 1200px;
  margin: 0 auto;
}

.game-card {
  background: white;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: var(--shadow-md);
  transition: all 0.3s ease;
  text-decoration: none;
  display: block;
  border: 3px solid transparent;
}

.game-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: var(--shadow-lg);
}

.game-card.game-math-card { border-top: 6px solid var(--math-green); }
.game-card.game-number-wars { border-top: 6px solid var(--math-red); }
.game-card.game-rocket { border-top: 6px solid var(--math-purple); }
.game-card.game-factor { border-top: 6px solid var(--math-blue); }

.game-header {
  padding: 20px;
  position: relative;
}

.game-icon-big {
  font-size: 56px;
  margin-bottom: 12px;
  display: block;
  filter: drop-shadow(0 4px 8px rgba(0,0,0,0.15));
}

.game-name {
  font-size: 22px;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 8px;
  font-family: 'Fredoka', sans-serif;
}

.game-meta {
  display: flex;
  gap: 16px;
  font-size: 14px;
  color: var(--text-medium);
  font-weight: 600;
  margin-bottom: 16px;
}

.game-meta i {
  margin-right: 4px;
}

.progress-bar-wrap {
  background: #E5E7EB;
  height: 12px;
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 12px;
}

.progress-bar-fill {
  height: 100%;
  border-radius: 10px;
  transition: width 1s ease;
  background: linear-gradient(90deg, var(--math-green), var(--primary-green));
  box-shadow: inset 0 2px 4px rgba(255,255,255,0.3);
}

.level-display {
  font-size: 14px;
  font-weight: 700;
  color: var(--text-medium);
  text-align: center;
}

.play-button {
  background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
  color: white;
  padding: 14px 24px;
  border-radius: 16px;
  font-size: 18px;
  font-weight: 700;
  border: none;
  width: 100%;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
  font-family: 'Fredoka', sans-serif;
  box-shadow: 0 4px 12px rgba(74,144,226,0.4);
}

.play-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(74,144,226,0.6);
}

.play-button i {
  margin-right: 8px;
  font-size: 20px;
}

/* ═══════════════════════════════════════════════════════
   🏆 ACHIEVEMENT SHOWCASE
═══════════════════════════════════════════════════════ */
.achievement-card {
  background: linear-gradient(135deg, #FFD93D 0%, #FF9500 100%);
  border-radius: 24px;
  padding: 24px;
  margin: 24px;
  box-shadow: var(--shadow-lg);
  display: flex;
  align-items: center;
  gap: 20px;
  color: white;
}

.achievement-icon {
  font-size: 64px;
  animation: bounce 2s ease-in-out infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

.achievement-content h3 {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 8px;
  text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.achievement-content p {
  font-size: 16px;
  opacity: 0.95;
}

/* ═══════════════════════════════════════════════════════
   🎯 CHARACTER SHOWCASE
═══════════════════════════════════════════════════════ */
.character-showcase {
  background: white;
  border-radius: 24px;
  padding: 24px;
  margin: 24px;
  box-shadow: var(--shadow-md);
}

.character-display {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-bottom: 20px;
}

.character-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: 5px solid var(--primary-blue);
  padding: 10px;
  background: linear-gradient(135deg, #E8F4F8, white);
  box-shadow: var(--shadow-md);
}

.character-avatar img {
  width: 100%;
  height: 100%;
  object-fit: contain;
}

.character-info h3 {
  font-size: 24px;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 8px;
}

.character-stats {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.stat-pill {
  background: var(--bg-light);
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: 700;
  color: var(--text-medium);
}

/* ═══════════════════════════════════════════════════════
   📱 RESPONSIVE DESIGN
═══════════════════════════════════════════════════════ */
@media (max-width: 768px) {
  .welcome-greeting { font-size: 24px; }
  .welcome-message { font-size: 16px; }
  .quick-actions { grid-template-columns: repeat(2, 1fr); margin-top: -30px; }
  .games-grid { grid-template-columns: 1fr; }
  .section-title { font-size: 20px; }
  .achievement-card { flex-direction: column; text-align: center; }
}

/* ═══════════════════════════════════════════════════════
   ✨ FUN ANIMATIONS
═══════════════════════════════════════════════════════ */
.fade-in {
  animation: fadeIn 0.6s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.slide-up {
  animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ═══════════════════════════════════════════════════════
   🎈 FLOATING ELEMENTS
═══════════════════════════════════════════════════════ */
.floating-emoji {
  position: fixed;
  font-size: 32px;
  pointer-events: none;
  z-index: 1;
  opacity: 0.3;
  animation: float linear infinite;
}

@keyframes float {
  0% { transform: translateY(100vh) rotate(0deg); }
  100% { transform: translateY(-100vh) rotate(360deg); }
}
</style>

<!-- Floating Math Emojis for Fun -->
<div id="floatingEmojis"></div>

<!-- Main Dashboard Content -->
<div class="dashboard-wrapper">

  <!-- 🌟 WELCOME HERO SECTION -->
  <div class="hero-section">
    <h1 class="welcome-greeting">Hi {{ Auth::user()->name }}! 👋</h1>
    <p class="welcome-message">Ready to play and learn today?</p>
    
    @php
      $totalScore   = $progress->sum('high_score');
      $totalLevels  = $progress->sum('current_level') - $progress->count();
      $gamesPlayed  = $progress->where('high_score', '>', 0)->count();
    @endphp
    
    <div class="hero-stats">
      <div class="stat-badge fade-in">
        <span class="stat-icon">🏆</span>
        <div class="stat-info">
          <div class="stat-label">Total Score</div>
          <div class="stat-value">{{ number_format($totalScore) }}</div>
        </div>
      </div>
      
      <div class="stat-badge fade-in" style="animation-delay: 0.1s">
        <span class="stat-icon">⭐</span>
        <div class="stat-info">
          <div class="stat-label">Levels Beat</div>
          <div class="stat-value">{{ $totalLevels }}</div>
        </div>
      </div>
      
      <div class="stat-badge fade-in" style="animation-delay: 0.2s">
        <span class="stat-icon">🎮</span>
        <div class="stat-info">
          <div class="stat-label">Games Played</div>
          <div class="stat-value">{{ $gamesPlayed }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- 🎯 QUICK ACTION BUTTONS -->
  <div class="quick-actions">
    <a href="{{ route('student.games') }}" class="action-btn action-games slide-up">
      <span class="icon">🎮</span>
      <span class="label">Play Games</span>
    </a>
    
    <a href="{{ route('student.myProgress') }}" class="action-btn action-progress slide-up" style="animation-delay: 0.1s">
      <span class="icon">📊</span>
      <span class="label">My Progress</span>
    </a>
    
    <a href="{{ route('student.choose-character') }}" class="action-btn action-character slide-up" style="animation-delay: 0.2s">
      <span class="icon">🦸</span>
      <span class="label">My Hero</span>
    </a>
    
    <a href="#" class="action-btn action-class slide-up" style="animation-delay: 0.3s" data-bs-toggle="modal" data-bs-target="#classroomModal">
      <span class="icon">👥</span>
      <span class="label">Join Class</span>
    </a>
  </div>

  <!-- 📚 MY GAMES SECTION -->
  <div style="margin-top: 40px;">
    <div class="section-header">
      <span class="section-icon">🎮</span>
      <h2 class="section-title">My Math Games</h2>
    </div>

    @if($games->isEmpty())
    <div style="text-align: center; padding: 60px 24px;">
      <div style="font-size: 80px; margin-bottom: 20px;">😴</div>
      <h3 style="font-size: 24px; color: var(--text-medium);">No games yet!</h3>
      <p style="color: var(--text-light);">Ask your teacher to add some math games.</p>
    </div>
    @else
    <div class="games-grid">
      @foreach($games as $game)
        @php
          $sp = $progress[$game->id] ?? null;
          $cl = $sp->current_level ?? 1;
          $hi = $sp->high_score ?? 0;
          $pct = $hi > 0 ? round(($cl / $game->total_levels) * 100) : 0;
          
          $gameIcons = [
            'math_card_match' => '🃏',
            'number_wars' => '⚔️',
            'rocket_math' => '🚀',
            'factor_pond' => '🐸'
          ];
          $icon = $gameIcons[$game->name] ?? '🎮';
          
          $gameClasses = [
            'math_card_match' => 'game-math-card',
            'number_wars' => 'game-number-wars',
            'rocket_math' => 'game-rocket',
            'factor_pond' => 'game-factor'
          ];
          $cardClass = $gameClasses[$game->name] ?? '';
        @endphp
        
        <a href="{{ route('student.map', $game->name) }}" class="game-card {{ $cardClass }} fade-in" style="animation-delay: {{ $loop->index * 0.1 }}s">
          <div class="game-header">
            <span class="game-icon-big">{{ $icon }}</span>
            <h3 class="game-name">{{ $game->display_name }}</h3>
            
            <div class="game-meta">
              <span><i class="bi bi-layers-fill"></i> Level {{ $cl }}</span>
              <span><i class="bi bi-trophy-fill"></i> {{ number_format($hi) }}</span>
            </div>
            
            @if($hi > 0)
            <div class="progress-bar-wrap">
              <div class="progress-bar-fill" style="width: 0%" data-width="{{ $pct }}"></div>
            </div>
            <div class="level-display">{{ $pct }}% Complete</div>
            @else
            <div style="text-align: center; padding: 12px; background: var(--bg-light); border-radius: 12px; margin-bottom: 12px;">
              <span style="font-size: 14px; color: var(--text-medium); font-weight: 600;">🌟 New Game - Start Playing!</span>
            </div>
            @endif
            
            <button class="play-button">
              <i class="bi bi-play-circle-fill"></i>
              @if($hi > 0) Continue @else Start Playing @endif
            </button>
          </div>
        </a>
      @endforeach
    </div>
    @endif
  </div>

  <!-- 🏆 CHARACTER SHOWCASE (if selected) -->
  @if(Auth::user()->character_avatar)
  <div class="character-showcase fade-in">
    <div class="section-header" style="padding: 0; margin-bottom: 16px;">
      <span class="section-icon">🦸</span>
      <h2 class="section-title">My Math Hero</h2>
    </div>
    
    <div class="character-display">
      <div class="character-avatar">
        <img src="{{ asset('images/characters/' . Auth::user()->character_avatar . '.png') }}" 
             alt="My Hero"
             onerror="this.style.display='none'">
      </div>
      <div class="character-info">
        <h3>{{ ucfirst(str_replace('_', ' ', Auth::user()->character_avatar)) }}</h3>
        <div class="character-stats">
          <span class="stat-pill">⚡ Your Math Hero</span>
          @if(Auth::user()->grade_level)
          <span class="stat-pill">📚 Grade {{ Auth::user()->grade_level }}</span>
          @endif
        </div>
      </div>
    </div>
    
    <a href="{{ route('student.choose-character') }}" style="display: inline-block; color: var(--primary-blue); font-weight: 700; text-decoration: none; font-size: 16px;">
      <i class="bi bi-arrow-repeat"></i> Change Hero
    </a>
  </div>
  @endif

  <!-- 🎯 ACHIEVEMENT (if they've done well) -->
  @if($totalScore >= 500)
  <div class="achievement-card fade-in">
    <div class="achievement-icon">🌟</div>
    <div class="achievement-content">
      <h3>Awesome Progress!</h3>
      <p>You've earned {{ number_format($totalScore) }} points! Keep up the amazing work!</p>
    </div>
  </div>
  @endif

</div>

<!-- Classroom Join Modal -->
<div class="modal fade" id="classroomModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 24px; border: none;">
      <div class="modal-header" style="border-bottom: none; padding: 24px;">
        <h5 class="modal-title" style="font-family: 'Fredoka', sans-serif; font-size: 24px; color: var(--text-dark);">
          👥 Join Your Class
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="padding: 0 24px 24px;">
        <form method="POST" action="{{ route('student.enroll-classroom') }}">
          @csrf
          <div class="mb-3">
            <label style="font-size: 16px; font-weight: 700; margin-bottom: 8px; color: var(--text-dark);">Class Code</label>
            <input type="text" name="class_code" class="form-control" 
                   style="border-radius: 12px; border: 2px solid #E5E7EB; padding: 14px; font-size: 16px;"
                   placeholder="Enter code from your teacher" required>
          </div>
          <button type="submit" class="play-button" style="margin: 0;">
            <i class="bi bi-check-circle-fill"></i> Join Class
          </button>
        </form>
        
        @if(session('success'))
        <div style="margin-top: 16px; padding: 12px; background: #D1FAE5; border-radius: 12px; color: #065F46; font-weight: 600;">
          ✅ {{ session('success') }}
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Animate progress bars
document.addEventListener('DOMContentLoaded', function() {
  setTimeout(function() {
    document.querySelectorAll('.progress-bar-fill').forEach(function(bar) {
      bar.style.width = bar.dataset.width + '%';
    });
  }, 300);
  
  // Create floating math emojis
  const emojis = ['➕', '➖', '✖️', '➗', '🔢', '📐', '📏', '🎯'];
  const container = document.getElementById('floatingEmojis');
  
  for (let i = 0; i < 8; i++) {
    const emoji = document.createElement('div');
    emoji.className = 'floating-emoji';
    emoji.textContent = emojis[Math.floor(Math.random() * emojis.length)];
    emoji.style.left = Math.random() * 100 + '%';
    emoji.style.animationDuration = (15 + Math.random() * 10) + 's';
    emoji.style.animationDelay = Math.random() * 5 + 's';
    container.appendChild(emoji);
  }
});
</script>
@endsection
