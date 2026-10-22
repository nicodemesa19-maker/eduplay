@extends('layouts.app')
@section('content')
<style>
:root {
  --pg-bg: #0a0b1e;
  --pg-bg2: #0d0f2e;
  --card-bg: rgba(255,255,255,.04);
  --card-border: rgba(255,255,255,.08);
  --card-border2: rgba(255,255,255,.05);
  --text: #e2e8f0;
  --text-muted: rgba(255,255,255,.45);
  --input-bg: rgba(255,255,255,.06);
  --input-border: rgba(255,255,255,.12);
  --input-color: #fff;
  --head-bg: linear-gradient(135deg,#0d0f2e,#1a0e4a);
  --head-border: rgba(99,102,241,.2);
}
[data-bs-theme="light"] {
  --pg-bg: #f0f2f8;
  --pg-bg2: #e8eaf6;
  --card-bg: rgba(255,255,255,.9);
  --card-border: rgba(99,102,241,.15);
  --card-border2: rgba(99,102,241,.1);
  --text: #1e1b4b;
  --text-muted: rgba(30,27,75,.55);
  --input-bg: #fff;
  --input-border: rgba(99,102,241,.25);
  --input-color: #1e1b4b;
  --head-bg: linear-gradient(135deg,#e0e7ff,#c7d2fe);
  --head-border: rgba(99,102,241,.2);
}
.t-page { background: var(--pg-bg); min-height: calc(100vh - 58px); padding: 0 0 60px; transition: background .3s; font-size: 1.2rem; }
.t-header { background: var(--head-bg); border-bottom: 1px solid var(--head-border); padding: 26px 0; margin-bottom: 28px; }
.t-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 18px; overflow: hidden; transition: background .3s, border-color .3s; }
.t-card-head { padding: 16px 22px; border-bottom: 1px solid var(--card-border2); }
.t-card-body { padding: 20px 22px; }
.t-input { background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 10px; color: var(--input-color); padding: 10px 14px; font-size: 1.2rem; width: 100%; outline: none; transition: border-color .2s, box-shadow .2s; }
.t-input::placeholder { color: var(--text-muted); }
.t-input:focus { border-color: rgba(99,102,241,.55); box-shadow: 0 0 0 3px rgba(99,102,241,.12); }
.t-input option { background-color: var(--pg-bg); color: var(--text); }
.t-label { font-size: 0.98rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .7px; display: block; margin-bottom: 6px; }
.t-text { color: var(--text); }
.t-muted { color: var(--text-muted); font-size: 1.1rem; }
.t-divider { border-top: 1px solid var(--card-border2); }
.btn-primary-t { background: linear-gradient(135deg,#6366f1,#7c3aed); border: none; color: #fff; border-radius: 10px; padding: 9px 18px; font-size: 1.15rem; font-weight: 700; cursor: pointer; transition: all .2s; box-shadow: 0 4px 14px rgba(99,102,241,.3); }
.btn-primary-t:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(99,102,241,.45); }
.btn-outline-t { background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text); border-radius: 10px; padding: 9px 18px; font-size: 1.15rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all .2s; }
.btn-outline-t:hover { border-color: rgba(99,102,241,.4); color: #a5b4fc; }
.btn-danger-t { background: linear-gradient(135deg,#ef4444,#f87171); border: none; color: #fff; border-radius: 10px; padding: 7px 14px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all .2s; }
.btn-danger-t:hover { transform: translateY(-1px); }
.code-pill { font-family: monospace; background: rgba(99,102,241,.1); border: 1px solid rgba(99,102,241,.2); color: #a5b4fc; border-radius: 6px; padding: 2px 8px; font-size: 1.1rem; }
.classroom-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 14px; padding: 20px; transition: all .3s; }
.classroom-card:hover { border-color: rgba(99,102,241,.3); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.2); }
.classroom-icon { width: 56px; height: 56px; border-radius: 12px; background: linear-gradient(135deg,#6366f1,#7c3aed); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; }
.student-count { background: rgba(16,185,129,.15); border: 1px solid rgba(16,185,129,.3); color: #34d399; border-radius: 99px; padding: 4px 12px; font-size: 0.95rem; font-weight: 700; }
/* Navigation Tabs */
.nav-tabs-wrapper { display: flex; gap: 8px; align-items: center; margin-top: 20px; overflow-x: auto; }
.nav-tab { background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08); color: var(--text-muted); text-decoration: none; padding: 10px 20px; border-radius: 10px; font-size: 1.05rem; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all .2s; white-space: nowrap; }
.nav-tab:hover { border-color: rgba(99,102,241,.3); color: #a5b4fc; background: rgba(99,102,241,.1); }
.nav-tab.active { background: linear-gradient(135deg,#6366f1,#7c3aed); border-color: transparent; color: #fff; box-shadow: 0 4px 12px rgba(99,102,241,.3); }
.nav-tab i { font-size: 1.1rem; }
[data-bs-theme="light"] .nav-tab { background: rgba(99,102,241,.08); border-color: rgba(99,102,241,.15); color: rgba(30,27,75,.65); }
[data-bs-theme="light"] .nav-tab:hover { background: rgba(99,102,241,.15); color: #6366f1; }
[data-bs-theme="light"] .nav-tab.active { background: linear-gradient(135deg,#6366f1,#7c3aed); color: #fff; }
</style>

<div class="t-page">
  <div class="t-header">
    <div class="container-lg px-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
          <div style="font-size:1.05rem;font-weight:700;color:#818cf8;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:6px;">
            <i class="bi bi-building me-1"></i> Classroom Management
          </div>
          <h1 style="font-family:'Space Grotesk',sans-serif;font-size:2.4rem;font-weight:900;margin:0;" class="t-text">My Classrooms</h1>
          <p class="t-muted mt-1">Create and manage your virtual classrooms</p>
        </div>
        <span style="background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.3);color:#a5b4fc;border-radius:99px;padding:6px 16px;font-size:1.1rem;font-weight:700;">
          <i class="bi bi-person-fill me-1"></i> {{ Auth::user()->name }}
        </span>
      </div>
      
      {{-- Navigation Tabs --}}
      <div class="nav-tabs-wrapper">
        <a href="{{ route('teacher.dashboard') }}" class="nav-tab">
          <i class="bi bi-house-fill"></i> Dashboard
        </a>
        <a href="{{ route('teacher.classrooms') }}" class="nav-tab active">
          <i class="bi bi-building"></i> Classrooms
        </a>
        <a href="{{ route('teacher.students') }}" class="nav-tab">
          <i class="bi bi-people-fill"></i> Students
        </a>
        <a href="{{ route('teacher.export-progress') }}" class="nav-tab">
          <i class="bi bi-download"></i> Export Data
        </a>
      </div>
    </div>
  </div>

  <div class="container-lg px-4">

    @if(session('success'))
    <div class="d-flex align-items-center gap-2 mb-4" style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);color:#6ee7b7;border-radius:12px;padding:12px 18px;font-size:.98rem;">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Create New Classroom --}}
    <div class="t-card mb-4">
      <div class="t-card-head">
        <div class="t-text fw-bold" style="font-size:1.35rem;">
          <i class="bi bi-plus-circle-fill me-2" style="color:#34d399;"></i>Create New Classroom
        </div>
        <div class="t-muted mt-1">Set up a new virtual classroom with a unique code for student enrollment</div>
      </div>
      <div class="t-card-body">
        <form method="POST" action="{{ route('teacher.create-classroom') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="t-label">Classroom Name</label>
              <input type="text" name="name" placeholder="e.g. Math Grade 4 - Section A" required class="t-input" value="{{ old('name') }}">
              @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="t-label">Description (Optional)</label>
              <input type="text" name="description" placeholder="e.g. Morning class, Room 201" class="t-input" value="{{ old('description') }}">
            </div>
            <div class="col-12">
              <button type="submit" class="btn-primary-t">
                <i class="bi bi-plus-lg me-1"></i>Create Classroom
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- Classrooms Grid --}}
    <div class="mb-3">
      <h3 class="t-text mb-3" style="font-size:1.4rem;font-weight:700;">
        Your Classrooms <span class="t-muted" style="font-size:1.1rem;font-weight:400;">({{ $classrooms->count() }} total)</span>
      </h3>
    </div>

    @if($classrooms->isEmpty())
    <div class="t-card">
      <div class="t-card-body text-center py-5">
        <i class="bi bi-building" style="font-size:4rem;color:var(--text-muted);"></i>
        <h4 class="t-text mt-3 mb-2">No Classrooms Yet</h4>
        <p class="t-muted mb-0">Create your first classroom using the form above.</p>
      </div>
    </div>
    @else
    <div class="row g-4">
      @foreach($classrooms as $class)
      <div class="col-md-6 col-lg-4">
        <div class="classroom-card">
          <div class="d-flex align-items-start gap-3 mb-3">
            <div class="classroom-icon">
              🏫
            </div>
            <div class="flex-grow-1">
              <h4 class="t-text mb-1" style="font-size:1.3rem;font-weight:700;">{{ $class->name }}</h4>
              @if($class->description)
              <p class="t-muted mb-2" style="font-size:0.95rem;">{{ $class->description }}</p>
              @endif
              <div class="d-flex align-items-center gap-2 mb-2">
                <span class="t-muted" style="font-size:0.9rem;">Class Code:</span>
                <span class="code-pill">{{ $class->class_code }}</span>
              </div>
            </div>
          </div>

          <div class="t-divider mb-3"></div>

          <div class="d-flex align-items-center justify-content-between">
            <span class="student-count">
              <i class="bi bi-people-fill me-1"></i>
              {{ $class->students->count() }} student{{ $class->students->count() != 1 ? 's' : '' }}
            </span>
            <div class="d-flex gap-2">
              <a href="{{ route('teacher.classroom.show', $class->id) }}" class="btn-outline-t" style="padding:6px 12px;font-size:1rem;">
                <i class="bi bi-eye"></i> View
              </a>
              <form method="POST" action="{{ route('teacher.delete-classroom', $class->id) }}" onsubmit="event.preventDefault(); confirmAction('Delete classroom \'{{ $class->name }}\'? All student enrollments will be removed.', 'Delete Classroom').then(result => { if(result) this.submit(); });" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger-t" style="padding:6px 12px;">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @endif

    {{-- Classroom Aid Game --}}
    <div class="mb-3 mt-5">
      <h3 class="t-text mb-3" style="font-size:1.4rem;font-weight:700;">
        Classroom Aid
      </h3>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-4">
        <div class="arcade-card">
          <div class="arcade-content">
            <div class="d-flex align-items-start gap-3 mb-3">
              <div class="arcade-icon">
                <div class="arcade-icon-glow"></div>
                🎓
              </div>
              <div class="flex-grow-1">
                <h4 class="arcade-title">JEOPARDY</h4>
                <p class="arcade-subtitle">⚡ GAME SHOW QUIZ ⚡</p>
                <p class="arcade-desc">Interactive quiz board for classroom activities</p>
              </div>
            </div>

            <div class="arcade-divider">
              <span class="arcade-divider-dot">●</span>
              <span class="arcade-divider-dot">●</span>
              <span class="arcade-divider-dot">●</span>
            </div>

            <div class="d-flex gap-2">
              <a href="{{ route('teacher.jeopardy.play') }}" class="arcade-btn arcade-btn-play">
                <span class="arcade-btn-icon">▶</span> PLAY
              </a>
              <a href="{{ route('teacher.jeopardy.edit') }}" class="arcade-btn arcade-btn-edit">
                <span class="arcade-btn-icon">✎</span> EDIT
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <style>
    @keyframes arcadeGlow {
      0%, 100% { opacity: 0.6; }
      50% { opacity: 1; }
    }
    
    @keyframes arcadeFloat {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-2px); }
    }
    
    @keyframes dotBlink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.3; }
    }
    
    .arcade-card {
      background: linear-gradient(135deg, rgba(88,28,135,0.12), rgba(67,20,117,0.08));
      border: 2px solid;
      border-image: linear-gradient(135deg, #a855f7, #ec4899, #a855f7) 1;
      border-radius: 14px;
      padding: 20px;
      transition: all .4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(168,85,247,0.1);
    }
    
    .arcade-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(236,72,153,0.1), transparent);
      transition: left 0.5s ease;
    }
    
    .arcade-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 24px rgba(168,85,247,0.25), 0 0 20px rgba(236,72,153,0.15);
      border-image: linear-gradient(135deg, #c084fc, #f472b6, #c084fc) 1;
    }
    
    .arcade-card:hover::before {
      left: 100%;
    }
    
    .arcade-content {
      position: relative;
      z-index: 1;
    }
    
    .arcade-icon {
      width: 64px;
      height: 64px;
      border-radius: 12px;
      background: linear-gradient(135deg, #a855f7, #ec4899);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      box-shadow: 0 4px 14px rgba(168,85,247,0.4), inset 0 2px 0 rgba(255,255,255,0.2);
      border: 2px solid rgba(255,255,255,0.2);
      position: relative;
      animation: arcadeFloat 3s ease-in-out infinite;
    }
    
    .arcade-icon-glow {
      position: absolute;
      inset: -4px;
      border-radius: 14px;
      background: linear-gradient(135deg, #a855f7, #ec4899);
      filter: blur(8px);
      opacity: 0.5;
      animation: arcadeGlow 2s ease-in-out infinite;
      z-index: -1;
    }
    
    .arcade-title {
      font-family: 'Courier New', monospace;
      font-size: 1.5rem;
      font-weight: 900;
      letter-spacing: 2px;
      color: var(--text);
      margin: 0 0 4px 0;
      text-shadow: 2px 2px 0 rgba(168,85,247,0.3);
    }
    
    .arcade-subtitle {
      font-family: 'Courier New', monospace;
      font-size: 0.8rem;
      font-weight: 700;
      letter-spacing: 1px;
      background: linear-gradient(90deg, #a855f7, #ec4899, #a855f7);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin: 0 0 8px 0;
    }
    
    .arcade-desc {
      font-size: 0.95rem;
      color: var(--text-muted);
      margin: 0;
    }
    
    .arcade-divider {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      margin: 16px 0;
    }
    
    .arcade-divider::before,
    .arcade-divider::after {
      content: '';
      flex: 1;
      height: 2px;
      background: linear-gradient(90deg, transparent, rgba(168,85,247,0.3), transparent);
    }
    
    .arcade-divider-dot {
      color: #a855f7;
      font-size: 0.6rem;
      animation: dotBlink 1.5s ease-in-out infinite;
    }
    
    .arcade-divider-dot:nth-child(2) {
      animation-delay: 0.3s;
    }
    
    .arcade-divider-dot:nth-child(3) {
      animation-delay: 0.6s;
    }
    
    .arcade-btn {
      flex: 1;
      padding: 11px 20px;
      border: 2px solid;
      border-radius: 8px;
      font-family: 'Courier New', monospace;
      font-size: 0.95rem;
      font-weight: 900;
      letter-spacing: 1px;
      text-decoration: none;
      text-align: center;
      transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }
    
    .arcade-btn-icon {
      display: inline-block;
      transition: transform .3s ease;
    }
    
    .arcade-btn:hover .arcade-btn-icon {
      transform: scale(1.2);
    }
    
    .arcade-btn-play {
      background: linear-gradient(135deg, #10b981, #059669);
      border-color: #10b981;
      color: #fff;
      box-shadow: 0 4px 0 #047857, 0 6px 12px rgba(16,185,129,0.3);
      text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    
    .arcade-btn-play:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 0 #047857, 0 8px 16px rgba(16,185,129,0.4);
      color: #fff;
    }
    
    .arcade-btn-play:active {
      transform: translateY(2px);
      box-shadow: 0 2px 0 #047857, 0 4px 8px rgba(16,185,129,0.3);
    }
    
    .arcade-btn-edit {
      background: linear-gradient(135deg, #a855f7, #9333ea);
      border-color: #a855f7;
      color: #fff;
      box-shadow: 0 4px 0 #7e22ce, 0 6px 12px rgba(168,85,247,0.3);
      text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    
    .arcade-btn-edit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 0 #7e22ce, 0 8px 16px rgba(168,85,247,0.4);
      color: #fff;
    }
    
    .arcade-btn-edit:active {
      transform: translateY(2px);
      box-shadow: 0 2px 0 #7e22ce, 0 4px 8px rgba(168,85,247,0.3);
    }
    
    /* Light theme adjustments */
    [data-bs-theme="light"] .arcade-card {
      background: linear-gradient(135deg, rgba(168,85,247,0.08), rgba(236,72,153,0.05));
    }
    </style>

  </div>
</div>

@endsection
