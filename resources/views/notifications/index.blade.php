@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
:root {
  --pg-bg: #0a0b1e;
  --pg-bg2: #0d0f2e;
  --card-bg: rgba(255,255,255,.04);
  --card-border: rgba(255,255,255,.08);
  --text: #e2e8f0;
  --text-muted: rgba(255,255,255,.45);
  --cyan: #00E5FF;
  --purple: #a5b4fc;
}
[data-bs-theme="light"] {
  --pg-bg: #f0f2f8;
  --pg-bg2: #e8eaf6;
  --card-bg: rgba(255,255,255,.9);
  --card-border: rgba(99,102,241,.15);
  --text: #1e1b4b;
  --text-muted: rgba(30,27,75,.55);
}

body { background: var(--pg-bg); color: var(--text); font-family: 'Inter', sans-serif; min-height: 100vh; }

.notif-header {
  background: linear-gradient(135deg,#0d0f2e,#1a0e4a);
  border-bottom: 1px solid rgba(99,102,241,.2);
  padding: 26px 0;
  margin-bottom: 28px;
}

.notif-card {
  background: var(--card-bg);
  border: 1px solid var(--card-border);
  border-radius: 16px;
  padding: 16px 20px;
  margin-bottom: 12px;
  transition: all .2s;
  cursor: pointer;
}

.notif-card:hover {
  background: rgba(99,102,241,.08);
  border-color: rgba(99,102,241,.3);
}

.notif-card.unread {
  border-left: 4px solid var(--cyan);
  background: rgba(0,229,255,.05);
}

.notif-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  flex-shrink: 0;
}

.notif-title {
  font-weight: 700;
  font-size: 1.05rem;
  color: var(--text);
  margin-bottom: 4px;
}

.notif-message {
  color: var(--text-muted);
  font-size: 0.95rem;
  margin-bottom: 8px;
}

.notif-time {
  font-size: 0.85rem;
  color: var(--text-muted);
}

.btn-action {
  background: linear-gradient(135deg,#6366f1,#7c3aed);
  border: none;
  color: #fff;
  border-radius: 10px;
  padding: 10px 20px;
  font-weight: 700;
  cursor: pointer;
  transition: all .2s;
}

.btn-action:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(99,102,241,.4);
  color: #fff;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
}

.empty-state-icon {
  font-size: 4rem;
  margin-bottom: 16px;
  opacity: 0.5;
}
</style>

<div class="notif-header">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h1 style="margin:0;font-size:2rem;color:var(--purple);">🔔 Notifications</h1>
        <p class="text-muted mb-0">Stay updated with your latest activities</p>
      </div>
      <div class="d-flex gap-2">
        @if(Auth::user()->unreadNotificationsCount() > 0)
          <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-action">
              <i class="bi bi-check-all me-2"></i>Mark All Read
            </button>
          </form>
        @endif
        <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : (Auth::user()->role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard')) }}" class="btn btn-action">
          <i class="bi bi-arrow-left"></i> Back
        </a>
      </div>
    </div>
  </div>
</div>

<div class="container pb-5">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if($notifications->count() > 0)
    @foreach($notifications as $notif)
      <div class="notif-card {{ $notif->isRead() ? '' : 'unread' }}" 
           onclick="markAsRead({{ $notif->id }}, '{{ $notif->link }}')">
        <div class="d-flex gap-3">
          <div class="notif-icon" style="background: {{ $notif->getColor() }}22; border: 2px solid {{ $notif->getColor() }}; color: {{ $notif->getColor() }};">
            {{ $notif->getIcon() }}
          </div>
          <div class="flex-grow-1">
            <div class="notif-title">
              {{ $notif->title }}
              @if(!$notif->isRead())
                <span class="badge bg-primary ms-2">New</span>
              @endif
            </div>
            <div class="notif-message">{{ $notif->message }}</div>
            <div class="notif-time">
              <i class="bi bi-clock"></i> {{ $notif->getTimeAgo() }}
            </div>
          </div>
          <div class="d-flex flex-column gap-2">
            @if(!$notif->isRead())
              <button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); markAsRead({{ $notif->id }})">
                <i class="bi bi-check"></i>
              </button>
            @endif
            <form method="POST" action="{{ route('notifications.delete', $notif->id) }}" onclick="event.stopPropagation();" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    @endforeach

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
      {{ $notifications->links() }}
    </div>
  @else
    <div class="empty-state">
      <div class="empty-state-icon">🔕</div>
      <h3>No Notifications</h3>
      <p class="text-muted">You're all caught up! Check back later for updates.</p>
    </div>
  @endif
</div>

<script>
function markAsRead(notifId, link = null) {
  fetch(`/notifications/${notifId}/read`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  }).then(() => {
    if (link) {
      window.location.href = link;
    } else {
      location.reload();
    }
  });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
