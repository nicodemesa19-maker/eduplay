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
.t-text { color: var(--text); }
.t-muted { color: var(--text-muted); font-size: 1.1rem; }
.t-table th { font-size: 0.98rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .8px; padding: 11px 20px; border-bottom: 1px solid var(--card-border); background: var(--card-bg); }
.t-table td { padding: 13px 20px; border-bottom: 1px solid var(--card-border2); color: var(--text); font-size: 1.15rem; vertical-align: middle; }
.t-table tr:last-child td { border-bottom: none; }
.t-table tr:hover td { background: rgba(99,102,241,.04); }
.btn-outline-t { background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text); border-radius: 10px; padding: 9px 18px; font-size: 1.15rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all .2s; }
.btn-outline-t:hover { border-color: rgba(99,102,241,.4); color: #a5b4fc; }
.code-pill { font-family: monospace; background: rgba(99,102,241,.1); border: 1px solid rgba(99,102,241,.2); color: #a5b4fc; border-radius: 6px; padding: 2px 8px; font-size: 1.1rem; }
.avatar-sm { width: 32px; height: 32px; border-radius: 9px; background: linear-gradient(135deg,#6366f1,#7c3aed); display: flex; align-items: center; justify-content: center; font-size: 1.0rem; font-weight: 800; color: #fff; flex-shrink: 0; }
.lvl-badge { background: rgba(99,102,241,.15); border: 1px solid rgba(99,102,241,.3); color: #a5b4fc; border-radius: 99px; padding: 2px 10px; font-size: 0.98rem; font-weight: 700; }
</style>

<div class="t-page">
  <div class="t-header">
    <div class="container-lg px-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <a href="{{ route('teacher.dashboard') }}" class="btn-outline-t" style="padding: 6px 14px;">
            <i class="bi bi-arrow-left"></i> Back
          </a>
          <div>
            <div style="font-size:1.05rem;font-weight:700;color:#818cf8;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:6px;">
              <i class="bi bi-building me-1"></i> Classroom
            </div>
            <h1 style="font-family:'Space Grotesk',sans-serif;font-size:2.4rem;font-weight:900;margin:0;" class="t-text">{{ $classroom->name }}</h1>
            <p class="t-muted mt-1">Code: <span class="code-pill">{{ $classroom->class_code }}</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container-lg px-4">
    <div class="t-card">
      <div class="t-card-head d-flex align-items-center justify-content-between">
        <div>
          <div class="t-text fw-bold" style="font-size:1.35rem;"><i class="bi bi-people-fill me-2" style="color:#10b981;"></i>Students in this Classroom</div>
          <div class="t-muted mt-1">{{ $classroom->students->count() }} enrolled student(s)</div>
        </div>
      </div>
      <div style="overflow-x:auto;">
        <table class="t-table w-100">
          <thead>
            <tr>
              <th>Student Name (Username)</th>
              <th>ID Number (Password)</th>
              <th>Grade</th>
            </tr>
          </thead>
          <tbody>
            @forelse($classroom->students as $st)
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="avatar-sm" style="background:linear-gradient(135deg,#059669,#10b981);">{{ strtoupper(substr($st->name,0,1)) }}</div>
                  <span class="fw-semibold">{{ $st->name }}</span>
                </div>
              </td>
              <td>
                <span class="code-pill">{{ $st->student_id_number ?? '—' }}</span>
              </td>
              <td><span class="lvl-badge">Grade {{ $st->grade_level ?? '—' }}</span></td>
            </tr>
            @empty
            <tr>
              <td colspan="3" class="text-center py-4 t-muted">
                <i class="bi bi-person-dash d-block mb-2" style="font-size:2rem;"></i>
                No students enrolled in this classroom yet.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
