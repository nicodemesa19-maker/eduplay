@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Bungee&family=Baloo+2:wght@500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
    /* Learning Stats Page — Retro Arcade theme, matched to the main dashboard */
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
        --muted2:rgba(234,246,255,.3);
        --card-bg:rgba(255,255,255,.045);
        --card-border:rgba(0,229,255,.16);
        --glow-cyan:0 0 22px rgba(0,229,255,.35);
        --glow-pink:0 0 22px rgba(255,61,174,.32);
        --glow-lime:0 0 22px rgba(182,255,61,.3);
    }
    *{box-sizing:border-box;}

    .stats-page {
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
        font-family:'Baloo 2',sans-serif;
        color: var(--text);
    }

    .stats-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 1;
    }

    .stats-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .stats-header h1 {
        font-family: 'Bungee', sans-serif;
        font-size: 2.4rem;
        color: var(--text2);
        margin: 0;
        text-shadow: 0 0 18px rgba(0,229,255,.4), 3px 3px 0 rgba(255,61,174,.3);
    }

    .stats-header p {
        font-family: 'Baloo 2', sans-serif;
        font-size: 1.05rem;
        margin-top: 8px;
        color: var(--muted);
        font-weight: 700;
    }

    /* Stat Cards Grid - KPI Style */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--card-bg);
        border: 2px solid var(--card-border);
        border-radius: 14px;
        padding: 1.5rem;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color .25s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--kpi-color), transparent);
        opacity: 0.6;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        border-color: var(--kpi-color);
        box-shadow: 0 8px 24px rgba(0,0,0,.3);
    }

    .stat-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-card-icon {
        font-size: 1.8rem;
    }

    .stat-card-title {
        font-family: 'Space Mono', monospace;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 0;
        flex-grow: 1;
        margin-left: 0.8rem;
    }

    /* KPI Badge */
    .kpi-badge {
        background: rgba(255,255,255,.08);
        border: 1px solid var(--kpi-color);
        color: var(--kpi-color);
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        font-family: 'Space Mono', monospace;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .stat-card-value {
        font-family: 'Space Mono', sans-serif;
        font-size: 2.8rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.3rem;
        color: var(--kpi-color);
    }

    .stat-card-target {
        font-family: 'Baloo 2', sans-serif;
        font-size: 0.85rem;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: 0.8rem;
    }

    .stat-card-target strong {
        color: var(--text);
        font-weight: 700;
    }

    /* KPI Progress */
    .kpi-progress {
        margin-bottom: 0.8rem;
    }

    .kpi-progress-label {
        display: flex;
        justify-content: space-between;
        font-family: 'Space Mono', monospace;
        font-size: 0.7rem;
        color: var(--muted);
        margin-bottom: 0.4rem;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .kpi-progress-bar {
        width: 100%;
        height: 8px;
        background: rgba(255,255,255,.08);
        border-radius: 6px;
        overflow: hidden;
        position: relative;
    }

    .kpi-progress-fill {
        height: 100%;
        border-radius: 6px;
        background: var(--kpi-color);
        transition: width 1s cubic-bezier(.4,0,.2,1);
        position: relative;
        overflow: hidden;
    }

    .kpi-progress-fill::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, 
            transparent 0%, 
            rgba(255,255,255,.25) 50%, 
            transparent 100%);
        animation: shimmer 2s infinite;
    }

    /* KPI Trend Indicator */
    .kpi-trend {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: 'Space Mono', monospace;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .kpi-trend.up {
        color: var(--lime);
    }

    .kpi-trend.down {
        color: var(--pink);
    }

    .kpi-trend.stable {
        color: var(--cyan);
    }

    .kpi-trend-icon {
        font-size: 1.1rem;
    }

    .kpi-trend-value {
        font-size: 0.95rem;
    }

    /* KPI Status Badge */
    .kpi-status {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-family: 'Space Mono', monospace;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-top: 0.5rem;
    }

    .kpi-status.on-track {
        background: rgba(182,255,61,.15);
        color: var(--lime);
        border: 1px solid rgba(182,255,61,.4);
    }

    .kpi-status.exceeding {
        background: rgba(255,210,61,.15);
        color: var(--yellow);
        border: 1px solid rgba(255,210,61,.4);
        animation: pulse 2s ease-in-out infinite;
    }

    .kpi-status.needs-attention {
        background: rgba(255,61,174,.15);
        color: var(--pink);
        border: 1px solid rgba(255,61,174,.4);
    }

    .kpi-status.excellent {
        background: rgba(0,229,255,.15);
        color: var(--cyan);
        border: 1px solid rgba(0,229,255,.4);
    }

    /* Progress Bars */
    .progress-bar-container {
        width: 100%;
        height: 10px;
        background: rgba(255,255,255,.08);
        border-radius: 6px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-bar-fill {
        height: 100%;
        border-radius: 6px;
        transition: width 0.8s cubic-bezier(.4,0,.2,1);
    }

    /* Large Cards */
    .large-card {
        background: var(--card-bg);
        border: 2px solid var(--card-border);
        border-radius: 16px;
        padding: 1.8rem;
        margin-bottom: 1.8rem;
    }

    .large-card-title {
        font-family: 'Bungee', sans-serif;
        font-size: 1.15rem;
        color: var(--text2);
        margin-bottom: 1.4rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        letter-spacing: .3px;
    }

    /* Leaderboard - Enhanced with podium style */
    .leaderboard-bar {
        margin-bottom: 1.5rem;
    }

    .leaderboard-label {
        font-family: 'Baloo 2', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 0.6rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .leaderboard-bar-container {
        width: 100%;
        height: 50px;
        background: rgba(255,255,255,.06);
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        box-shadow: inset 0 2px 8px rgba(0,0,0,.2);
    }

    .leaderboard-bar-fill {
        height: 100%;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.2rem;
        color: #0E0F26;
        font-family: 'Space Mono', monospace;
        font-weight: 700;
        font-size: 1.05rem;
        transition: width 0.9s cubic-bezier(.4,0,.2,1);
        position: relative;
        overflow: hidden;
    }

    .leaderboard-bar-fill::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, 
            transparent 0%, 
            rgba(255,255,255,.3) 50%, 
            transparent 100%);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .leaderboard-bar-fill.top {
        background: linear-gradient(90deg, var(--yellow), #FFE066);
        box-shadow: 0 4px 20px rgba(255,210,61,.4), inset 0 2px 0 rgba(255,255,255,.3);
    }

    .leaderboard-bar-fill.average {
        background: linear-gradient(90deg, var(--cyan), #33ECFF);
        box-shadow: 0 4px 20px rgba(0,229,255,.4), inset 0 2px 0 rgba(255,255,255,.3);
    }

    .leaderboard-bar-fill.you {
        background: linear-gradient(90deg, var(--purple), #C64FFF);
        color: #fff;
        box-shadow: 0 4px 24px rgba(176,38,255,.5), inset 0 2px 0 rgba(255,255,255,.2);
        animation: youPulse 2s ease-in-out infinite;
    }

    @keyframes youPulse {
        0%, 100% { box-shadow: 0 4px 24px rgba(176,38,255,.5), inset 0 2px 0 rgba(255,255,255,.2); }
        50% { box-shadow: 0 6px 30px rgba(176,38,255,.7), inset 0 2px 0 rgba(255,255,255,.3); }
    }

    .leaderboard-icon {
        font-size: 1.5rem;
        margin-right: 0.5rem;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,.3));
    }

    .leaderboard-message {
        background: linear-gradient(135deg, rgba(176,38,255,.12), rgba(255,61,174,.12));
        border: 2px solid rgba(176,38,255,.35);
        color: var(--text);
        padding: 1.2rem 1.6rem;
        border-radius: 12px;
        font-family: 'Baloo 2', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        margin-top: 1.2rem;
        text-align: center;
    }

    /* Skills Grid */
    .skills-grid {
        display: grid;
        gap: 1rem;
    }

    .skill-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.2rem 1.3rem;
        background: rgba(255,255,255,.03);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .skill-row::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: var(--skill-progress, 0%);
        background: linear-gradient(90deg, rgba(182,255,61,.12), rgba(0,229,255,.08));
        transition: width 0.8s cubic-bezier(.4,0,.2,1);
        z-index: 0;
    }

    .skill-row:hover {
        background: rgba(255,255,255,.06);
        transform: translateX(4px);
        border-color: var(--lime);
    }

    .skill-content {
        position: relative;
        z-index: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .skill-name {
        font-family: 'Baloo 2', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text);
    }

    .skill-stars {
        font-size: 1.4rem;
        letter-spacing: 3px;
        display: flex;
        gap: 2px;
    }

    .skill-star {
        display: inline-block;
        animation: starPop 0.4s cubic-bezier(.34,1.56,.64,1);
    }

    @keyframes starPop {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Badges */
    .badges-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(168px, 1fr));
        gap: 0.9rem;
    }

    .badge-tile {
        position: relative;
        border: 2px solid var(--card-border);
        color: var(--text2);
        padding: 1rem;
        border-radius: 12px;
        text-align: center;
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease, filter .2s ease, opacity .2s ease;
    }

    /* Unlocked badges: full color, glowing, celebratory */
    .badge-tile.unlocked {
        background: linear-gradient(135deg, rgba(176,38,255,.18), rgba(0,229,255,.12));
    }

    .badge-tile.unlocked:hover{
        transform: translateY(-3px);
        border-color: var(--lime);
        box-shadow: var(--glow-lime);
    }

    .badge-check {
        position: absolute;
        top: 8px;
        right: 10px;
        color: var(--lime);
        font-size: 1rem;
        text-shadow: 0 0 8px rgba(182,255,61,.6);
    }

    .badge-unlocked-tag {
        font-family: 'Space Mono', monospace;
        font-size: 0.68rem;
        font-weight: 700;
        color: var(--lime);
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-top: 6px;
    }

    /* Locked badges: dimmed, with a padlock and a progress readout so kids know exactly
       how close they are and what to do next — never a dead end, always a next step. */
    .badge-tile.locked {
        background: rgba(255,255,255,.03);
        filter: grayscale(.65) brightness(.75);
        opacity: .82;
    }

    .badge-tile.locked:hover{
        filter: grayscale(.3) brightness(.9);
        opacity: 1;
        transform: translateY(-2px);
        border-color: var(--cyan);
    }

    .badge-lock {
        position: absolute;
        top: 8px;
        right: 10px;
        color: var(--muted);
        font-size: 0.95rem;
    }

    .badge-icon {
        font-size: 2.1rem;
        margin-bottom: 0.5rem;
    }

    .badge-name {
        font-family: 'Baloo 2', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .badge-requirement {
        font-family: 'Baloo 2', sans-serif;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--muted);
        margin-top: 4px;
        line-height: 1.3;
        min-height: 2.4em;
    }

    .badge-progress-track {
        width: 100%;
        height: 7px;
        background: rgba(255,255,255,.08);
        border-radius: 5px;
        overflow: hidden;
        margin-top: 8px;
    }

    .badge-progress-fill {
        height: 100%;
        background: var(--cyan);
        border-radius: 5px;
        transition: width 0.8s cubic-bezier(.4,0,.2,1);
    }

    .badge-progress-pct {
        font-family: 'Space Mono', monospace;
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--cyan);
        margin-top: 4px;
    }

    .badges-legend {
        display: flex;
        gap: 1.4rem;
        flex-wrap: wrap;
        font-family: 'Baloo 2', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--muted);
        margin-bottom: 1.2rem;
    }
    .badges-legend span { display: inline-flex; align-items: center; gap: 6px; }

    /* Improvement Stats - Circular Progress Style */
    .improvement-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 1.6rem;
    }

    .improvement-item {
        text-align: center;
        position: relative;
    }

    .improvement-label {
        font-family: 'Space Mono', monospace;
        font-size: 0.78rem;
        color: var(--muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 1rem;
    }

    /* Circular Progress */
    .circular-progress {
        position: relative;
        width: 140px;
        height: 140px;
        margin: 0 auto 1rem;
    }

    .circular-progress svg {
        transform: rotate(-90deg);
        width: 100%;
        height: 100%;
    }

    .circular-progress circle {
        fill: none;
        stroke-width: 10;
    }

    .circular-progress .bg-circle {
        stroke: rgba(255,255,255,.08);
    }

    .circular-progress .progress-circle {
        stroke-linecap: round;
        transition: stroke-dashoffset 1.2s cubic-bezier(.4,0,.2,1);
    }

    .circular-progress .center-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .circular-progress .center-value {
        font-family: 'Space Mono', sans-serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text2);
        line-height: 1;
    }

    .circular-progress .center-arrow {
        font-size: 0.9rem;
        color: var(--muted);
        margin: 0.2rem 0;
    }

    .circular-progress .center-new {
        font-family: 'Space Mono', sans-serif;
        font-size: 1.4rem;
        font-weight: 700;
        line-height: 1;
    }

    .improvement-values {
        display: none; /* Hide old style */
    }

    .improvement-arrow {
        display: none; /* Hide old arrow */
    }

    .improvement-change {
        font-family: 'Space Mono', monospace;
        font-size: 1.1rem;
        font-weight: 700;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        display: inline-block;
        min-width: 80px;
    }

    .improvement-change.positive {
        background: rgba(182,255,61,.15);
        color: var(--lime);
        border: 2px solid rgba(182,255,61,.4);
        animation: pulse 2s ease-in-out infinite;
    }

    .improvement-change.neutral {
        background: rgba(255,255,255,.06);
        color: var(--muted);
        border: 2px solid var(--card-border);
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* Encouragement Card */
    .encouragement-card {
        background: linear-gradient(135deg, rgba(255,61,174,.16), rgba(176,38,255,.16));
        border: 2px solid var(--pink);
        color: var(--text2);
        padding: 2rem;
        border-radius: 16px;
        text-align: center;
        box-shadow: var(--glow-pink);
        margin-bottom: 2rem;
    }

    .encouragement-icon {
        font-size: 3.4rem;
        margin-bottom: 1rem;
    }

    .encouragement-title {
        font-family: 'Bungee', sans-serif;
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .encouragement-message {
        font-family: 'Baloo 2', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: var(--text);
    }

    .encouragement-btn {
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

    .encouragement-btn:hover {
        transform: scale(1.04);
        box-shadow: var(--glow-lime);
        color: #0E0F26;
        text-decoration: none;
    }

    /* No Data State */
    .no-data {
        text-align: center;
        padding: 2.5rem;
        color: var(--muted);
    }

    .no-data-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .no-data-message {
        font-family: 'Baloo 2', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
    }

    /* Colors (mapped to arcade accents) */
    .color-fire { color: var(--yellow); }
    .color-success { color: var(--lime); }
    .color-blue { color: var(--cyan); }
    .color-purple { color: var(--purple); }
    .color-orange { color: var(--yellow); }
    .color-pink { color: var(--pink); }

    /* Line Chart Container */
    .chart-container {
        position: relative;
        width: 100%;
        height: 350px;
        margin: 1rem 0;
        background: rgba(255,255,255,.02);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--card-border);
    }

    .leaderboard-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .leaderboard-stat-card {
        background: rgba(255,255,255,.03);
        border: 1px solid var(--card-border);
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .leaderboard-stat-card:hover {
        background: rgba(255,255,255,.06);
        border-color: var(--cyan);
    }

    .leaderboard-stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .leaderboard-stat-label {
        font-family: 'Space Mono', monospace;
        font-size: 0.75rem;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 0.4rem;
    }

    .leaderboard-stat-value {
        font-family: 'Space Mono', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }

    @media (max-width: 768px) {
        .chart-container {
            height: 280px;
            padding: 1rem;
        }
    }
</style>

<div class="stats-page">
    <div class="stats-container">
        <!-- Back to Dashboard Button -->
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('student.dashboard') }}" style="background: var(--card-bg); border: 2px solid var(--card-border); border-radius: 10px; padding: 0.7rem 1.2rem; font-family: 'Baloo 2', sans-serif; font-size: 0.95rem; font-weight: 700; color: var(--text); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s ease;">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Header -->
        <div class="stats-header">
            <h1>📊 My Learning Journey</h1>
            <p>See how awesome you're doing!</p>
        </div>

        <!-- Encouragement Card -->
        @if($encouragement)
        <div class="encouragement-card">
            <div class="encouragement-icon">{{ $encouragement['icon'] }}</div>
            <div class="encouragement-title">{{ $encouragement['title'] }}</div>
            <div class="encouragement-message">{{ $encouragement['message'] }}</div>
            <a href="{{ $encouragement['actionUrl'] }}" class="encouragement-btn">
                {{ $encouragement['action'] }}
            </a>
        </div>
        @endif

        <!-- KPI Dashboard Grid -->
        <div class="stats-grid">
            <!-- KPI 1: Learning Streak -->
            <div class="stat-card" style="--kpi-color: var(--yellow);">
                <div class="stat-card-header">
                    <span class="stat-card-icon">🔥</span>
                    <h3 class="stat-card-title">Learning Streak</h3>
                    @if($stats['streak']['current'] >= $stats['streak']['nextMilestone'] * 0.8)
                        <span class="kpi-badge">Exceeding</span>
                    @else
                        <span class="kpi-badge">Active</span>
                    @endif
                </div>
                
                <div class="stat-card-value">{{ $stats['streak']['current'] }}</div>
                <div class="stat-card-target">
                    Target: <strong>{{ $stats['streak']['nextMilestone'] }} days</strong>
                </div>

                <div class="kpi-progress">
                    <div class="kpi-progress-label">
                        <span>Progress to Target</span>
                        <span>{{ round(($stats['streak']['current'] / $stats['streak']['nextMilestone']) * 100) }}%</span>
                    </div>
                    <div class="kpi-progress-bar">
                        <div class="kpi-progress-fill" style="width: {{ min(100, ($stats['streak']['current'] / $stats['streak']['nextMilestone']) * 100) }}%; background: var(--yellow);"></div>
                    </div>
                </div>

                <div class="kpi-trend up">
                    <span class="kpi-trend-icon">↗</span>
                    <span class="kpi-trend-value">Consistent Daily Activity</span>
                </div>

                @if($stats['streak']['current'] >= $stats['streak']['nextMilestone'])
                    <div class="kpi-status exceeding">
                        <span>⭐</span> Target Achieved!
                    </div>
                @elseif($stats['streak']['current'] >= $stats['streak']['nextMilestone'] * 0.8)
                    <div class="kpi-status on-track">
                        <span>✓</span> On Track
                    </div>
                @else
                    <div class="kpi-status needs-attention">
                        <span>!</span> Keep Going
                    </div>
                @endif
            </div>

            <!-- KPI 2: Performance Score -->
            <div class="stat-card" style="--kpi-color: var(--lime);">
                <div class="stat-card-header">
                    <span class="stat-card-icon">⭐</span>
                    <h3 class="stat-card-title">Performance Score</h3>
                    @php
                        $performanceTarget = 1000;
                        $performancePercent = ($stats['totalScore'] / $performanceTarget) * 100;
                    @endphp
                    @if($performancePercent >= 100)
                        <span class="kpi-badge">Exceeding</span>
                    @elseif($performancePercent >= 75)
                        <span class="kpi-badge">On Track</span>
                    @else
                        <span class="kpi-badge">Active</span>
                    @endif
                </div>
                
                <div class="stat-card-value">{{ $stats['totalScore'] }}</div>
                <div class="stat-card-target">
                    Target: <strong>{{ $performanceTarget }} points</strong>
                </div>

                <div class="kpi-progress">
                    <div class="kpi-progress-label">
                        <span>Progress to Target</span>
                        <span>{{ round($performancePercent) }}%</span>
                    </div>
                    <div class="kpi-progress-bar">
                        <div class="kpi-progress-fill" style="width: {{ min(100, $performancePercent) }}%; background: var(--lime);"></div>
                    </div>
                </div>

                <div class="kpi-trend up">
                    <span class="kpi-trend-icon">↗</span>
                    <span class="kpi-trend-value">+{{ $improvement['points']['change'] }}% This Week</span>
                </div>

                @if($performancePercent >= 100)
                    <div class="kpi-status exceeding">
                        <span>🏆</span> Target Exceeded!
                    </div>
                @elseif($performancePercent >= 75)
                    <div class="kpi-status on-track">
                        <span>✓</span> On Track
                    </div>
                @else
                    <div class="kpi-status needs-attention">
                        <span>💪</span> Keep Practicing
                    </div>
                @endif
            </div>

            <!-- KPI 3: Completion Rate -->
            <div class="stat-card" style="--kpi-color: var(--cyan);">
                <div class="stat-card-header">
                    <span class="stat-card-icon">🎯</span>
                    <h3 class="stat-card-title">Completion Rate</h3>
                    @php
                        $completionTarget = 30;
                        $completionPercent = ($stats['levelsCompleted'] / $completionTarget) * 100;
                    @endphp
                    @if($completionPercent >= 100)
                        <span class="kpi-badge">Exceeding</span>
                    @elseif($completionPercent >= 60)
                        <span class="kpi-badge">On Track</span>
                    @else
                        <span class="kpi-badge">Active</span>
                    @endif
                </div>
                
                <div class="stat-card-value">{{ $stats['levelsCompleted'] }}</div>
                <div class="stat-card-target">
                    Target: <strong>{{ $completionTarget }} levels</strong>
                </div>

                <div class="kpi-progress">
                    <div class="kpi-progress-label">
                        <span>Progress to Target</span>
                        <span>{{ round($completionPercent) }}%</span>
                    </div>
                    <div class="kpi-progress-bar">
                        <div class="kpi-progress-fill" style="width: {{ min(100, $completionPercent) }}%; background: var(--cyan);"></div>
                    </div>
                </div>

                <div class="kpi-trend up">
                    <span class="kpi-trend-icon">↗</span>
                    <span class="kpi-trend-value">{{ $stats['gamesPlayed'] }} Games Played</span>
                </div>

                @if($completionPercent >= 100)
                    <div class="kpi-status exceeding">
                        <span>🌟</span> Outstanding!
                    </div>
                @elseif($completionPercent >= 60)
                    <div class="kpi-status on-track">
                        <span>✓</span> Great Progress
                    </div>
                @else
                    <div class="kpi-status needs-attention">
                        <span>🎮</span> More Levels Await
                    </div>
                @endif
            </div>

            <!-- KPI 4: Mastery Level -->
            <div class="stat-card" style="--kpi-color: var(--purple);">
                <div class="stat-card-header">
                    <span class="stat-card-icon">🏆</span>
                    <h3 class="stat-card-title">Mastery Level</h3>
                    @php
                        $masteryTarget = 75;
                        $masteryPercent = ($stats['totalStars'] / $masteryTarget) * 100;
                    @endphp
                    @if($masteryPercent >= 100)
                        <span class="kpi-badge">Exceeding</span>
                    @elseif($masteryPercent >= 70)
                        <span class="kpi-badge">Expert</span>
                    @else
                        <span class="kpi-badge">Growing</span>
                    @endif
                </div>
                
                <div class="stat-card-value">{{ $stats['totalStars'] }}</div>
                <div class="stat-card-target">
                    Target: <strong>{{ $masteryTarget }} stars</strong>
                </div>

                <div class="kpi-progress">
                    <div class="kpi-progress-label">
                        <span>Progress to Mastery</span>
                        <span>{{ round($masteryPercent) }}%</span>
                    </div>
                    <div class="kpi-progress-bar">
                        <div class="kpi-progress-fill" style="width: {{ min(100, $masteryPercent) }}%; background: var(--purple);"></div>
                    </div>
                </div>

                <div class="kpi-trend up">
                    <span class="kpi-trend-icon">↗</span>
                    <span class="kpi-trend-value">Best Day: {{ $stats['patterns']['bestDay'] }}</span>
                </div>

                @if($masteryPercent >= 100)
                    <div class="kpi-status exceeding">
                        <span>👑</span> Master Level!
                    </div>
                @elseif($masteryPercent >= 70)
                    <div class="kpi-status excellent">
                        <span>⭐</span> Expert Status
                    </div>
                @else
                    <div class="kpi-status on-track">
                        <span>📚</span> Learning Well
                    </div>
                @endif
            </div>
        </div>

        <!-- Class Leaderboard -->
        @if($leaderboard)
        <div class="large-card">
            <h2 class="large-card-title">
                <span>🏆</span> Class Explorers Leaderboard
            </h2>

            <!-- Stats Overview Grid -->
            <div class="leaderboard-stats-grid">
                <div class="leaderboard-stat-card">
                    <div class="leaderboard-stat-icon">🥇</div>
                    <div class="leaderboard-stat-label">Top Score</div>
                    <div class="leaderboard-stat-value" style="color: var(--yellow);">{{ $leaderboard['topScore'] }}</div>
                </div>
                <div class="leaderboard-stat-card">
                    <div class="leaderboard-stat-icon">📊</div>
                    <div class="leaderboard-stat-label">Class Average</div>
                    <div class="leaderboard-stat-value" style="color: var(--cyan);">{{ $leaderboard['classAverage'] }}</div>
                </div>
                <div class="leaderboard-stat-card">
                    <div class="leaderboard-stat-icon">⭐</div>
                    <div class="leaderboard-stat-label">Your Score</div>
                    <div class="leaderboard-stat-value" style="color: var(--purple);">{{ $leaderboard['yourScore'] }}</div>
                </div>
                <div class="leaderboard-stat-card">
                    <div class="leaderboard-stat-icon">🎯</div>
                    <div class="leaderboard-stat-label">Your Rank</div>
                    <div class="leaderboard-stat-value" style="color: var(--lime);">#{{ $leaderboard['yourRank'] }}</div>
                </div>
            </div>

            <!-- Line Graph -->
            <div class="chart-container">
                <canvas id="leaderboardChart"></canvas>
            </div>

            <div class="leaderboard-message">
                {{ $leaderboard['message'] }}
            </div>
        </div>
        @else
        <div class="large-card">
            <h2 class="large-card-title">
                <span>🏆</span> Class Leaderboard
            </h2>
            <div class="no-data">
                <div class="no-data-icon">📚</div>
                <div class="no-data-message">Join a classroom to see the leaderboard!</div>
            </div>
        </div>
        @endif

        <!-- Skills Progress -->
        @if(count($stats['skills']) > 0)
        <div class="large-card">
            <h2 class="large-card-title">
                <span>⭐</span> Skills Unlocked!
            </h2>
            <div class="skills-grid">
                @foreach($stats['skills'] as $index => $skill)
                <div class="skill-row" style="--skill-progress: {{ ($skill['stars'] / 5) * 100 }}%;">
                    <div class="skill-content">
                        <div class="skill-name">{{ $skill['gameTitle'] }}</div>
                        <div class="skill-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $skill['stars'])
                                    <span class="skill-star" style="animation-delay: {{ ($i - 1) * 0.1 }}s;">⭐</span>
                                @else
                                    <span style="opacity: 0.3;">☆</span>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Weekly Improvement -->
        <div class="large-card">
            <h2 class="large-card-title">
                <span>📈</span> Your Growth This Week!
            </h2>
            <div class="improvement-grid">
                <!-- Points -->
                <div class="improvement-item">
                    <div class="improvement-label">Points Earned</div>
                    <div class="circular-progress">
                        <svg viewBox="0 0 160 160">
                            <circle class="bg-circle" cx="80" cy="80" r="70"></circle>
                            <circle class="progress-circle" cx="80" cy="80" r="70" 
                                    stroke="var(--lime)" 
                                    stroke-dasharray="439.6" 
                                    stroke-dashoffset="{{ 439.6 - (439.6 * min(100, max(0, $improvement['points']['current'])) / 100) }}"
                                    style="stroke-dashoffset: 439.6;">
                            </circle>
                        </svg>
                        <div class="center-content">
                            <div class="center-value" style="color: var(--lime);">{{ $improvement['points']['current'] }}</div>
                            <div class="center-arrow">↑</div>
                            <div class="center-new" style="color: var(--muted); font-size: 0.9rem;">from {{ $improvement['points']['previous'] }}</div>
                        </div>
                    </div>
                    <div class="improvement-change {{ $improvement['points']['change'] >= 0 ? 'positive' : 'neutral' }}">
                        {{ $improvement['points']['change'] >= 0 ? '+' : '' }}{{ $improvement['points']['change'] }}%
                    </div>
                </div>

                <!-- Levels -->
                <div class="improvement-item">
                    <div class="improvement-label">Levels Beat</div>
                    <div class="circular-progress">
                        <svg viewBox="0 0 160 160">
                            <circle class="bg-circle" cx="80" cy="80" r="70"></circle>
                            <circle class="progress-circle" cx="80" cy="80" r="70" 
                                    stroke="var(--cyan)" 
                                    stroke-dasharray="439.6" 
                                    stroke-dashoffset="{{ 439.6 - (439.6 * min(100, max(0, $improvement['levels']['current'] * 10)) / 100) }}"
                                    style="stroke-dashoffset: 439.6;">
                            </circle>
                        </svg>
                        <div class="center-content">
                            <div class="center-value" style="color: var(--cyan);">{{ $improvement['levels']['current'] }}</div>
                            <div class="center-arrow">↑</div>
                            <div class="center-new" style="color: var(--muted); font-size: 0.9rem;">from {{ $improvement['levels']['previous'] }}</div>
                        </div>
                    </div>
                    <div class="improvement-change {{ $improvement['levels']['change'] >= 0 ? 'positive' : 'neutral' }}">
                        {{ $improvement['levels']['change'] >= 0 ? '+' : '' }}{{ $improvement['levels']['change'] }}%
                    </div>
                </div>

                <!-- Stars -->
                <div class="improvement-item">
                    <div class="improvement-label">Stars Earned</div>
                    <div class="circular-progress">
                        <svg viewBox="0 0 160 160">
                            <circle class="bg-circle" cx="80" cy="80" r="70"></circle>
                            <circle class="progress-circle" cx="80" cy="80" r="70" 
                                    stroke="var(--yellow)" 
                                    stroke-dasharray="439.6" 
                                    stroke-dashoffset="{{ 439.6 - (439.6 * min(100, max(0, $improvement['stars']['current'] * 5)) / 100) }}"
                                    style="stroke-dashoffset: 439.6;">
                            </circle>
                        </svg>
                        <div class="center-content">
                            <div class="center-value" style="color: var(--yellow);">{{ $improvement['stars']['current'] }}</div>
                            <div class="center-arrow">↑</div>
                            <div class="center-new" style="color: var(--muted); font-size: 0.9rem;">from {{ $improvement['stars']['previous'] }}</div>
                        </div>
                    </div>
                    <div class="improvement-change {{ $improvement['stars']['change'] >= 0 ? 'positive' : 'neutral' }}">
                        {{ $improvement['stars']['change'] >= 0 ? '+' : '' }}{{ $improvement['stars']['change'] }}%
                    </div>
                </div>

                <!-- Time -->
                <div class="improvement-item">
                    <div class="improvement-label">Time Played</div>
                    <div class="circular-progress">
                        <svg viewBox="0 0 160 160">
                            <circle class="bg-circle" cx="80" cy="80" r="70"></circle>
                            <circle class="progress-circle" cx="80" cy="80" r="70" 
                                    stroke="var(--pink)" 
                                    stroke-dasharray="439.6" 
                                    stroke-dashoffset="{{ 439.6 - (439.6 * min(100, max(0, $improvement['minutes']['current'])) / 100) }}"
                                    style="stroke-dashoffset: 439.6;">
                            </circle>
                        </svg>
                        <div class="center-content">
                            <div class="center-value" style="color: var(--pink);">{{ $improvement['minutes']['current'] }}</div>
                            <div class="center-arrow">min</div>
                            <div class="center-new" style="color: var(--muted); font-size: 0.9rem;">from {{ $improvement['minutes']['previous'] }}m</div>
                        </div>
                    </div>
                    <div class="improvement-change {{ $improvement['minutes']['change'] >= 0 ? 'positive' : 'neutral' }}">
                        {{ $improvement['minutes']['change'] >= 0 ? '+' : '' }}{{ $improvement['minutes']['change'] }}%
                    </div>
                </div>
            </div>

            @if(array_sum([$improvement['points']['change'], $improvement['levels']['change'], $improvement['stars']['change']]) > 0)
            <div class="leaderboard-message" style="margin-top: 1.5rem;">
                🌟 WOW! You're improving! Your hard work is really paying off!
            </div>
            @endif
        </div>

    </div>
</div>

<script>
    // Animate circular progress charts on load
    document.addEventListener('DOMContentLoaded', function() {
        // Animate all circular progress circles
        const progressCircles = document.querySelectorAll('.progress-circle');
        
        setTimeout(() => {
            progressCircles.forEach(circle => {
                const targetOffset = circle.getAttribute('stroke-dashoffset');
                circle.style.strokeDashoffset = targetOffset;
            });
        }, 300);

        // Animate skill rows
        const skillRows = document.querySelectorAll('.skill-row');
        skillRows.forEach((row, index) => {
            setTimeout(() => {
                const progress = row.style.getPropertyValue('--skill-progress');
                row.style.setProperty('--skill-progress', progress);
            }, 500 + (index * 100));
        });

        // Initialize Leaderboard Chart
        @if($leaderboard)
        initializeLeaderboardChart();
        @endif
    });

    @if($leaderboard)
    function initializeLeaderboardChart() {
        // Generate last 7 days labels
        const labels = [];
        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        }

        // Simulate weekly progression data
        const topScore = {{ $leaderboard['topScore'] }};
        const classAvg = {{ $leaderboard['classAverage'] }};
        const yourScore = {{ $leaderboard['yourScore'] }};

        // Generate realistic progression data
        const topScoreData = [];
        const classAvgData = [];
        const yourScoreData = [];

        // Generate data points showing progression over 7 days
        for (let i = 0; i < 7; i++) {
            const dayFactor = i / 6; // 0 to 1 progression
            
            // Top score grows steadily
            topScoreData.push(Math.round(topScore * (0.7 + dayFactor * 0.3)));
            
            // Class average grows moderately
            classAvgData.push(Math.round(classAvg * (0.75 + dayFactor * 0.25)));
            
            // Your score grows (showing improvement)
            yourScoreData.push(Math.round(yourScore * (0.65 + dayFactor * 0.35)));
        }

        const ctx = document.getElementById('leaderboardChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '🥇 Top Explorer',
                    data: topScoreData,
                    borderColor: '#FFD23D',
                    backgroundColor: 'rgba(255, 210, 61, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#FFD23D',
                    pointBorderColor: '#0E0F26',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBorderWidth: 4
                }, {
                    label: '📊 Class Average',
                    data: classAvgData,
                    borderColor: '#00E5FF',
                    backgroundColor: 'rgba(0, 229, 255, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#00E5FF',
                    pointBorderColor: '#0E0F26',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBorderWidth: 4
                }, {
                    label: '⭐ YOU! (Rank #{{ $leaderboard["yourRank"] }})',
                    data: yourScoreData,
                    borderColor: '#B026FF',
                    backgroundColor: 'rgba(176, 38, 255, 0.15)',
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#B026FF',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    pointRadius: 7,
                    pointHoverRadius: 10,
                    pointHoverBorderWidth: 4,
                    borderDash: [0],
                    shadowOffsetX: 0,
                    shadowOffsetY: 4,
                    shadowBlur: 10,
                    shadowColor: 'rgba(176, 38, 255, 0.5)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: '#EAF6FF',
                            font: {
                                family: 'Baloo 2',
                                size: 13,
                                weight: 'bold'
                            },
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 8,
                            boxHeight: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(14, 15, 38, 0.95)',
                        titleColor: '#EAF6FF',
                        bodyColor: '#EAF6FF',
                        borderColor: 'rgba(0, 229, 255, 0.5)',
                        borderWidth: 2,
                        padding: 14,
                        cornerRadius: 10,
                        titleFont: {
                            family: 'Baloo 2',
                            size: 15,
                            weight: 'bold'
                        },
                        bodyFont: {
                            family: 'Space Mono',
                            size: 14,
                            weight: 'bold'
                        },
                        displayColors: true,
                        boxWidth: 12,
                        boxHeight: 12,
                        boxPadding: 6,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed.y + ' pts';
                                return label;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Class Performance Over Time',
                        color: 'rgba(234, 246, 255, 0.7)',
                        font: {
                            family: 'Bungee',
                            size: 16
                        },
                        padding: {
                            top: 0,
                            bottom: 20
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.06)',
                            drawBorder: false,
                            lineWidth: 1
                        },
                        ticks: {
                            color: 'rgba(234, 246, 255, 0.65)',
                            font: {
                                family: 'Baloo 2',
                                size: 12,
                                weight: 'bold'
                            },
                            padding: 8
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.06)',
                            drawBorder: false,
                            lineWidth: 1
                        },
                        ticks: {
                            color: 'rgba(234, 246, 255, 0.65)',
                            font: {
                                family: 'Space Mono',
                                size: 11,
                                weight: 'bold'
                            },
                            padding: 8,
                            callback: function(value) {
                                return value + ' pts';
                            }
                        },
                        title: {
                            display: true,
                            text: 'Points',
                            color: 'rgba(234, 246, 255, 0.55)',
                            font: {
                                family: 'Space Mono',
                                size: 11,
                                weight: 'bold'
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                animation: {
                    duration: 1800,
                    easing: 'easeInOutQuart',
                    onProgress: function(animation) {
                        // Add subtle pulse effect to "YOU" line during animation
                        if (animation.currentStep / animation.numSteps > 0.5) {
                            const meta = this.getDatasetMeta(2); // Your score dataset
                            if (meta && meta.data) {
                                meta.data.forEach(point => {
                                    if (point) {
                                        const progress = (animation.currentStep / animation.numSteps - 0.5) * 2;
                                        point.options.radius = 7 + Math.sin(progress * Math.PI) * 1.5;
                                    }
                                });
                            }
                        }
                    }
                }
            }
        });
    }
    @endif
</script>
@endsection