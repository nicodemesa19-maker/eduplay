@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Bungee&family=Baloo+2:wght@500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    /* Badge Collection Page - Arcade Theme */
    :root{
        --bg:#0E0F26;
        --bg2:#171A3D;
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
        --glow-lime:0 0 22px rgba(182,255,61,.3);
    }
    
    .badges-page {
        background:
            radial-gradient(circle at 15% 10%, rgba(176,38,255,.16), transparent 55%),
            radial-gradient(circle at 90% 25%, rgba(0,229,255,.14), transparent 55%),
            var(--bg);
        background-image:
            radial-gradient(circle at 15% 10%, rgba(176,38,255,.16), transparent 55%),
            radial-gradient(circle at 90% 25%, rgba(0,229,255,.14), transparent 55%),
            var(--bg),
            repeating-linear-gradient(180deg, rgba(255,255,255,.015) 0 2px, transparent 2px 4px);
        min-height: 100vh;
        padding: 2rem 0;
        font-family: 'Baloo 2', sans-serif;
        color: var(--text);
    }
    
    .badges-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    .badges-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .badges-header h1 {
        font-family: 'Bungee', sans-serif;
        font-size: 2.4rem;
        color: var(--text2);
        margin: 0;
        text-shadow: 0 0 18px rgba(0,229,255,.4), 3px 3px 0 rgba(255,61,174,.3);
        animation: trophy-bounce 2s ease-in-out infinite;
    }
    
    @keyframes trophy-bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .badges-header p {
        font-family: 'Baloo 2', sans-serif;
        font-size: 1.05rem;
        margin-top: 8px;
        color: var(--muted);
        font-weight: 700;
    }
    
    /* Summary Stats */
    .badges-summary {
        background: var(--card-bg);
        border: 2px solid var(--card-border);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        gap: 2rem;
    }
    
    .summary-stat {
        text-align: center;
    }
    
    .summary-value {
        font-family: 'Space Mono', monospace;
        font-size: 3rem;
        font-weight: 700;
        color: var(--cyan);
        line-height: 1;
    }
    
    .summary-label {
        font-family: 'Baloo 2', sans-serif;
        font-size: 0.95rem;
        color: var(--muted);
        font-weight: 700;
        margin-top: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    /* Badge Categories */
    .badge-category {
        background: var(--card-bg);
        border: 2px solid var(--card-border);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .category-header {
        font-family: 'Bungee', sans-serif;
        font-size: 1.15rem;
        color: var(--text2);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        letter-spacing: .3px;
    }
    
    .category-icon {
        font-size: 1.8rem;
    }
    
    /* Badge Grid */
    .badges-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1.5rem;
    }
    
    .badge-card {
        background: rgba(255,255,255,.03);
        border: 2px solid var(--card-border);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        position: relative;
    }
    
    .badge-card.earned {
        background: linear-gradient(135deg, rgba(176,38,255,.18), rgba(0,229,255,.12));
        border-color: var(--lime);
        box-shadow: var(--glow-lime);
    }
    
    .badge-card.earned:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 30px rgba(182,255,61,.5);
    }
    
    .badge-card.locked {
        filter: grayscale(.65) brightness(.75);
        opacity: .82;
    }
    
    .badge-card.locked:hover {
        transform: translateY(-3px);
        filter: grayscale(.3) brightness(.9);
        opacity: 1;
        border-color: var(--cyan);
    }
    
    .badge-icon-large {
        font-size: 3.5rem;
        margin-bottom: 1rem;
    }
    
    .badge-name {
        font-family: 'Baloo 2', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 0.5rem;
    }
    
    .badge-description {
        font-family: 'Baloo 2', sans-serif;
        font-size: 0.8rem;
        color: var(--muted);
        line-height: 1.4;
        min-height: 2.4em;
    }
    
    .badge-check {
        position: absolute;
        top: 8px;
        right: 10px;
        color: var(--lime);
        font-size: 1.1rem;
        text-shadow: 0 0 8px rgba(182,255,61,.6);
    }
    
    .badge-lock {
        position: absolute;
        top: 8px;
        right: 10px;
        color: var(--muted);
        font-size: 1rem;
    }
    
    .badge-earned-date {
        font-family: 'Space Mono', monospace;
        font-size: 0.7rem;
        color: var(--lime);
        margin-top: 0.5rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    
    .badge-progress {
        margin-top: 1rem;
        background: rgba(255,255,255,.08);
        border-radius: 10px;
        height: 7px;
        overflow: hidden;
    }
    
    .badge-progress-fill {
        height: 100%;
        background: var(--cyan);
        border-radius: 10px;
        transition: width 0.8s cubic-bezier(.4,0,.2,1);
    }
    
    .badge-progress-text {
        font-family: 'Space Mono', monospace;
        font-size: 0.7rem;
        color: var(--cyan);
        margin-top: 0.5rem;
        font-weight: 700;
    }
    
    /* Recent Achievement */
    .recent-achievement {
        background: linear-gradient(135deg, rgba(255,61,174,.16), rgba(176,38,255,.16));
        border: 2px solid var(--pink);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 0 22px rgba(255,61,174,.32);
    }
    
    .recent-achievement-icon {
        font-size: 3rem;
        animation: tada 1s ease-in-out infinite;
    }
    
    @keyframes tada {
        0%, 100% { transform: rotate(0deg) scale(1); }
        10%, 20% { transform: rotate(-3deg) scale(1.1); }
        30%, 50%, 70%, 90% { transform: rotate(3deg) scale(1.1); }
        40%, 60%, 80% { transform: rotate(-3deg) scale(1.1); }
    }
    
    .recent-achievement-text {
        flex: 1;
    }
    
    .recent-achievement-title {
        font-family: 'Bungee', sans-serif;
        font-size: 1.15rem;
        color: var(--text2);
        margin-bottom: 0.25rem;
    }
    
    .recent-achievement-desc {
        font-family: 'Baloo 2', sans-serif;
        font-size: 0.9rem;
        color: var(--muted);
        font-weight: 600;
    }
    
    /* No Badges State */
    .no-badges {
        text-align: center;
        padding: 3rem;
    }
    
    .no-badges-icon {
        font-size: 5rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }
    
    .no-badges-message {
        font-family: 'Baloo 2', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 1rem;
    }
    
    .no-badges-cta {
        background: var(--lime);
        color: #0E0F26;
        padding: 0.85rem 1.8rem;
        border-radius: 10px;
        font-family: 'Bungee', sans-serif;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-block;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .no-badges-cta:hover {
        transform: scale(1.04);
        box-shadow: var(--glow-lime);
        color: #0E0F26;
        text-decoration: none;
    }
</style>

<div class="badges-page">
    <div class="badges-container">
        <!-- Back to Dashboard Button -->
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('student.dashboard') }}" style="background: var(--card-bg); border: 2px solid var(--card-border); border-radius: 10px; padding: 0.7rem 1.2rem; font-family: 'Baloo 2', sans-serif; font-size: 0.95rem; font-weight: 700; color: var(--text); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s ease;">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Header -->
        <div class="badges-header">
            <h1>🏆 My Badge Collection</h1>
            <p>Unlock achievements by playing and learning!</p>
        </div>

        <!-- Recent Achievement -->
        @if(isset($recentBadge) && $recentBadge)
        <div class="recent-achievement">
            <div class="recent-achievement-icon">{{ $recentBadge->badge_icon }}</div>
            <div class="recent-achievement-text">
                <div class="recent-achievement-title">🎉 New Badge Unlocked!</div>
                <div class="recent-achievement-desc">{{ $recentBadge->badge_name }} - {{ $recentBadge->description }}</div>
            </div>
        </div>
        @endif

        <!-- Summary Stats -->
        <div class="badges-summary">
            <div class="summary-stat">
                <div class="summary-value">{{ $earnedCount }}</div>
                <div class="summary-label">Badges Earned</div>
            </div>
            <div class="summary-stat">
                <div class="summary-value">{{ $totalBadges }}</div>
                <div class="summary-label">Total Badges</div>
            </div>
            <div class="summary-stat">
                <div class="summary-value">{{ $completionPercent }}%</div>
                <div class="summary-label">Complete</div>
            </div>
        </div>

        <!-- Streak Badges -->
        <div class="badge-category">
            <div class="category-header">
                <span class="category-icon">🔥</span>
                Streak Badges
            </div>
            <div class="badges-grid">
                @foreach($streakBadges as $badge)
                <div class="badge-card {{ $badge['earned'] ? 'earned' : 'locked' }}">
                    @if($badge['earned'])
                        <div class="badge-check"><i class="bi bi-check-circle-fill"></i></div>
                    @else
                        <div class="badge-lock"><i class="bi bi-lock-fill"></i></div>
                    @endif
                    <div class="badge-icon-large">{{ $badge['icon'] }}</div>
                    <div class="badge-name">{{ $badge['name'] }}</div>
                    <div class="badge-description">{{ $badge['description'] }}</div>
                    @if($badge['earned'])
                        <div class="badge-earned-date">
                            Unlocked!
                        </div>
                    @else
                        <div class="badge-progress">
                            <div class="badge-progress-fill" style="width: {{ $badge['progress'] }}%"></div>
                        </div>
                        <div class="badge-progress-text">{{ $badge['current'] }}/{{ $badge['required'] }} days</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Score Badges -->
        <div class="badge-category">
            <div class="category-header">
                <span class="category-icon">⭐</span>
                Score Badges
            </div>
            <div class="badges-grid">
                @foreach($scoreBadges as $badge)
                <div class="badge-card {{ $badge['earned'] ? 'earned' : 'locked' }}">
                    @if($badge['earned'])
                        <div class="badge-check"><i class="bi bi-check-circle-fill"></i></div>
                    @else
                        <div class="badge-lock"><i class="bi bi-lock-fill"></i></div>
                    @endif
                    <div class="badge-icon-large">{{ $badge['icon'] }}</div>
                    <div class="badge-name">{{ $badge['name'] }}</div>
                    <div class="badge-description">{{ $badge['description'] }}</div>
                    @if($badge['earned'])
                        <div class="badge-earned-date">
                            Unlocked!
                        </div>
                    @else
                        <div class="badge-progress">
                            <div class="badge-progress-fill" style="width: {{ $badge['progress'] }}%"></div>
                        </div>
                        <div class="badge-progress-text">{{ number_format($badge['current']) }}/{{ number_format($badge['required']) }} pts</div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Achievement Badges -->
        <div class="badge-category">
            <div class="category-header">
                <span class="category-icon">🎯</span>
                Achievement Badges
            </div>
            <div class="badges-grid">
                @foreach($achievementBadges as $badge)
                <div class="badge-card {{ $badge['earned'] ? 'earned' : 'locked' }}">
                    @if($badge['earned'])
                        <div class="badge-check"><i class="bi bi-check-circle-fill"></i></div>
                    @else
                        <div class="badge-lock"><i class="bi bi-lock-fill"></i></div>
                    @endif
                    <div class="badge-icon-large">{{ $badge['icon'] }}</div>
                    <div class="badge-name">{{ $badge['name'] }}</div>
                    <div class="badge-description">{{ $badge['description'] }}</div>
                    @if($badge['earned'])
                        <div class="badge-earned-date">
                            Unlocked!
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        @if($earnedCount === 0)
        <div class="badge-category">
            <div class="no-badges">
                <div class="no-badges-icon">🎮</div>
                <div class="no-badges-message">Start your badge collection journey!</div>
                <p style="font-family: 'Baloo 2', sans-serif; font-size: 1rem; color: var(--muted); margin-bottom: 1.5rem;">
                    Play games, complete levels, and maintain streaks to unlock awesome badges!
                </p>
                <a href="{{ route('student.games') }}" class="no-badges-cta">
                    Start Playing
                </a>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
