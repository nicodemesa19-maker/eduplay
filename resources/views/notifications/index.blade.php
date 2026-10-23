@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
@import url('https://fonts.googleapis.com/css2?family=Bungee&family=Baloo+2:wght@500;600;700;800&family=Space+Mono:wght@400;700&display=swap');

:root{
  --bg:#0E0F26;
  --bg2:#171A3D;
  --card-bg:rgba(255,255,255,.045);
  --card-border:rgba(0,229,255,.16);
  --cyan:#00E5FF;
  --pink:#FF3DAE;
  --lime:#B6FF3D;
  --purple:#B026FF;
  --text:#EAF6FF;
  --text2:#ffffff;
  --muted:rgba(234,246,255,.55);
  --glow-cyan:0 0 22px rgba(0,229,255,.35);
}

[data-bs-theme="light"]{
  --bg:#EEF1FB;
  --bg2:#E4E9FB;
  --card-bg:#ffffff;
  --card-border:rgba(124,58,237,.15);
  --text:#1B1D3D;
  --text2:#12132A;
  --muted:rgba(27,29,61,.6);
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
.hero-sub{color:var(--muted);font-size:1rem;margin-top:8px;}

.notif-card {
  background: var(--card-bg);
  border: 2px solid var(--card-border);
  border-radius: 16px;
  padding: 20px;
  margin-bottom: 16px;
  transition: all .2s;
  cursor: pointer;
}

.notif-card:hover {
  border-color: var(--cyan);
  box-shadow: var(--glow-cyan);
  transform: translateY(-2px);
}

.notif-card.unread {
  border-left: 4px solid var(--lime);
  background: rgba(182,255,61,.05);
}

.notif-icon {
  width: 52px;
  height: 52px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  flex-shrink: 0;
}

.notif-title {
  font-weight: 700;
  font-size: 1.1rem;
  color: var(--text2);
  margin-bottom: 6px;
}

.notif-message {
  color: var(--muted);
  font-size: 0.95rem;
  margin-bottom: 8px;
  line-height: 1.5;
}

.notif-time {
  font-size: 0.85rem;
  color: var(--muted);
  font-family: 'Space Mono', monospace;
}

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

.btn-action-sm {
  padding: 6px 12px;
  font-size: 0.85rem;
  border-radius: 6px;
  border: 2px solid;
  background: transparent;
  font-weight: 700;
  cursor: pointer;
  transition: all .2s;
}

.btn-action-sm.btn-read {
  color: var(--cyan);
  border-color: var(--cyan);
}

.btn-action-sm.btn-read:hover {
  background: var(--cyan);
  color: #0E0F26;
}

.btn-action-sm.btn-delete {
  color: var(--pink);
  border-color: var(--pink);
}

.btn-action-sm.btn-delete:hover {
  background: var(--pink);
  color: #fff;
}

.empty-state {
  text-align: center;
  padding: 80px 20px;
}

.empty-icon {
  font-size: 5rem;
  margin-bottom: 20px;
  opacity: 0.5;
}

.empty-title {
  font-size: 1.8rem;
  color: var(--text2);
  margin-bottom: 12px;
}

.empty-text {
  color: var(--muted);
  font-size: 1.1rem;
}

.badge-new {
  background: linear-gradient(135deg, var(--pink), var(--purple));
  color: #fff;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
  margin-left: 8px;
}

@media(max-width:768px){
  .hero-title{font-size:1.8rem;}
  .notif-icon{width:42px;height:42px;font-size:1.5rem;}
}
</style>

<div class="hero">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h1 class="hero-title">🔔 Notifications</h1>
        <p class="hero-sub">Stay updated with your latest activities</p>
      </div>
      <div class="d-flex gap-2">
        @if(Auth::user()->unreadNotificationsCount() > 0)
          <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-neon">
              <i class="bi bi-check-all me-2"></i>Mark All Read
            </button>
          </form>
        @endif
        <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : (Auth::user()->role === 'teacher' ? route('teacher.dashboard') : route('student.dashboard')) }}" class="btn btn-neon">
          <i class="bi bi-arrow-left me-2"></i>Back
        </a>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="background:rgba(182,255,61,.15);border:2px solid var(--lime);color:var(--lime);border-radius:12px;">
      <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if($notifications->count() > 0)
    @foreach($notifications as $notif)
      <div class="notif-card {{ $notif->isRead() ? '' : 'unread' }}" 
           onclick="markAsRead({{ $notif->id }}, '{{ $notif->link }}')">
        <div class="d-flex gap-3 align-items-start">
          <div class="notif-icon" style="background: {{ $notif->getColor() }}22; border: 2px solid {{ $notif->getColor() }}; color: {{ $notif->getColor() }};">
            {{ $notif->getIcon() }}
          </div>
          <div class="flex-grow-1">
            <div class="notif-title">
              {{ $notif->title }}
              @if(!$notif->isRead())
                <span class="badge-new">New</span>
              @endif
            </div>
            <div class="notif-message">{{ $notif->message }}</div>
            <div class="notif-time">
              <i class="bi bi-clock"></i> {{ $notif->getTimeAgo() }}
            </div>
          </div>
          <div class="d-flex flex-column gap-2">
            @if(!$notif->isRead())
              <button class="btn-action-sm btn-read" onclick="event.stopPropagation(); markAsRead({{ $notif->id }})" title="Mark as read">
                <i class="bi bi-check"></i>
              </button>
            @endif
            <form method="POST" action="{{ route('notifications.delete', $notif->id) }}" onclick="event.stopPropagation();" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn-action-sm btn-delete" title="Delete">
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
      <div class="empty-icon">🔕</div>
      <h3 class="empty-title">No Notifications</h3>
      <p class="empty-text">You're all caught up! Check back later for updates.</p>
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
