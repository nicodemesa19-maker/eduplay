@extends('layouts.app')
@section('content')
<style>
:root {
  --pg-bg: #0b0d12;
  --card-bg: #12151c;
  --card-bg-raised: #161a22;
  --card-border: rgba(255,255,255,.07);
  --card-border2: rgba(255,255,255,.06);
  --text: #e6e8ec;
  --text-muted: rgba(230,232,236,.52);
  --text-faint: rgba(230,232,236,.34);
  --head-bg: #0e1015;
  --head-border: rgba(255,255,255,.07);
  --shadow: 0 1px 2px rgba(0,0,0,.4);
  --accent: #4f7cff;
  --accent-soft: rgba(79,124,255,.12);
  --accent-border: rgba(79,124,255,.28);
  --success: #2fb673;
  --success-soft: rgba(47,182,115,.12);
  --success-border: rgba(47,182,115,.28);
  --warning: #d99a3d;
  --warning-soft: rgba(217,154,61,.12);
  --warning-border: rgba(217,154,61,.28);
  --danger: #e2645f;
  --danger-soft: rgba(226,100,95,.12);
  --danger-border: rgba(226,100,95,.28);
}
[data-bs-theme="light"] {
  --pg-bg: #f6f7f9;
  --card-bg: #ffffff;
  --card-bg-raised: #ffffff;
  --card-border: #e6e8ec;
  --card-border2: #edeef1;
  --text: #171a21;
  --text-muted: rgba(23,26,33,.58);
  --text-faint: rgba(23,26,33,.38);
  --head-bg: #ffffff;
  --head-border: #e6e8ec;
  --shadow: 0 1px 2px rgba(16,24,40,.06);
  --accent: #3358e0;
  --accent-soft: rgba(51,88,224,.08);
  --accent-border: rgba(51,88,224,.22);
  --success: #1f9d63;
  --success-soft: rgba(31,157,99,.09);
  --success-border: rgba(31,157,99,.22);
  --warning: #b97417;
  --warning-soft: rgba(185,116,23,.1);
  --warning-border: rgba(185,116,23,.24);
  --danger: #d0433d;
  --danger-soft: rgba(208,67,61,.09);
  --danger-border: rgba(208,67,61,.22);
}

.admin-page { background: var(--pg-bg); min-height: calc(100vh - 58px); padding: 0 0 60px; }
.page-header { background: var(--head-bg); border-bottom: 1px solid var(--head-border); padding: 28px 0; margin-bottom: 28px; }
.t-eyebrow { font-size: .72rem; font-weight: 700; color: var(--text-faint); letter-spacing: .3px; margin-bottom: 6px; }
.t-title-main { font-family: 'Space Grotesk', sans-serif; font-size: 1.65rem; font-weight: 700; margin: 0; letter-spacing: -.2px; color: var(--text); }
.t-muted { color: var(--text-muted); font-size: .85rem; margin-top: 4px; }

.back-link { display: inline-flex; align-items: center; gap: 7px; color: var(--text-muted); font-size: .82rem; font-weight: 600; text-decoration: none; background: var(--card-bg-raised); border: 1px solid var(--card-border); border-radius: 8px; padding: 7px 13px; transition: color .15s, border-color .15s; }
.back-link:hover { color: var(--text); border-color: var(--accent-border); }
.back-link i { color: var(--accent); }

.a-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 10px; overflow: hidden; box-shadow: var(--shadow); }
.a-card-head { padding: 15px 20px; border-bottom: 1px solid var(--card-border2); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.a-card-head .lbl { font-size: .8rem; font-weight: 700; color: var(--text-muted); display: flex; align-items: center; }
.a-card-head .lbl i { color: var(--warning); }
.count-badge { background: var(--warning-soft); border: 1px solid var(--warning-border); color: var(--warning); border-radius: 99px; padding: 3px 10px; font-size: .72rem; font-weight: 700; }

.a-table { width: 100%; border-collapse: collapse; }
.a-table th { padding: 11px 20px; font-size: .68rem; font-weight: 700; color: var(--text-faint); text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid var(--card-border); background: var(--card-bg); }
.a-table td { padding: 13px 20px; border-bottom: 1px solid var(--card-border2); font-size: .85rem; color: var(--text); vertical-align: middle; }
.a-table tr:last-child td { border-bottom: none; }
.a-table tr:hover td { background: var(--card-bg-raised); }

.a-avatar { width: 32px; height: 32px; border-radius: 8px; background: var(--accent-soft); border: 1px solid var(--accent-border); display: flex; align-items: center; justify-content: center; font-size: .72rem; font-weight: 700; color: var(--accent); flex-shrink: 0; }

.view-id-link { color: var(--accent); font-size: .78rem; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; }
.view-id-link:hover { text-decoration: underline; }

.btn-approve { background: var(--success-soft); border: 1px solid var(--success-border); color: var(--success); border-radius: 7px; padding: 6px 13px; font-size: .78rem; font-weight: 600; cursor: pointer; transition: background .15s, border-color .15s; }
.btn-approve:hover { background: var(--success-soft); border-color: var(--success); }
.btn-reject { background: var(--danger-soft); border: 1px solid var(--danger-border); color: var(--danger); border-radius: 7px; padding: 6px 13px; font-size: .78rem; font-weight: 600; cursor: pointer; transition: background .15s, border-color .15s; }
.btn-reject:hover { background: var(--danger-soft); border-color: var(--danger); }

.alert-success { display: flex; align-items: center; gap: 9px; background: var(--success-soft); border: 1px solid var(--success-border); color: var(--success); border-radius: 8px; padding: 12px 16px; font-size: .85rem; }

.empty-state { text-align: center; padding: 48px 0; }
.empty-state i { font-size: 2rem; color: var(--success); display: block; margin-bottom: 12px; }
.empty-state .title { color: var(--text); font-weight: 700; font-size: .95rem; margin-bottom: 3px; }
.empty-state .sub { color: var(--text-muted); font-size: .85rem; }
</style>

<div class="admin-page">
  <div class="page-header">
    <div class="container-lg px-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
          <div class="t-eyebrow">ADMIN PANEL</div>
          <h1 class="t-title-main">Pending Teachers</h1>
          <p class="t-muted">Review and approve teacher registrations</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="back-link">
          <i class="bi bi-house-fill"></i> Dashboard
        </a>
      </div>
    </div>
  </div>

  <div class="container-lg px-4">

    @if(session('success'))
    <div class="alert-success mb-4">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    @if(count($teachers) === 0)
    <div class="a-card">
      <div class="empty-state">
        <i class="bi bi-check-circle-fill"></i>
        <p class="title">All caught up!</p>
        <p class="sub">No pending teacher approvals.</p>
      </div>
    </div>
    @else
    <div class="a-card">
      <div class="a-card-head">
        <span class="lbl"><i class="bi bi-hourglass-split me-2"></i>Awaiting Review</span>
        <span class="count-badge">{{ count($teachers) }} pending</span>
      </div>
      <div style="overflow-x:auto;">
        <table class="a-table">
          <thead>
            <tr>
              <th>Teacher</th><th>Email</th><th>Faculty ID</th><th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($teachers as $teacher)
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="a-avatar">{{ strtoupper(substr($teacher->name,0,1)) }}</div>
                  <span style="font-weight:600;">{{ $teacher->name }}</span>
                </div>
              </td>
              <td style="color:var(--text-muted);">{{ $teacher->email }}</td>
              <td>
                <a href="{{ Storage::url($teacher->faculty_id_path) }}" target="_blank" class="view-id-link">
                  <i class="bi bi-file-earmark-person-fill"></i>View ID
                </a>
              </td>
              <td>
                <div class="d-flex gap-2 flex-wrap">
                  <form method="POST" action="{{ route('admin.approve-teacher', $teacher->id) }}">
                    @csrf
                    <button type="submit" class="btn-approve">
                      <i class="bi bi-check-lg me-1"></i> Approve
                    </button>
                  </form>
                  <form method="POST" action="{{ route('admin.reject-teacher', $teacher->id) }}" onsubmit="event.preventDefault(); confirmAction('Reject this teacher registration?', 'Reject Teacher').then(result => { if(result) this.submit(); });">
                    @csrf
                    <button type="submit" class="btn-reject">
                      <i class="bi bi-x-lg me-1"></i> Reject
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

  </div>
</div>
@endsection