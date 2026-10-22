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
.t-text { color: var(--text); }

.back-link { display: inline-flex; align-items: center; gap: 7px; color: var(--text-muted); font-size: .82rem; font-weight: 600; text-decoration: none; background: var(--card-bg-raised); border: 1px solid var(--card-border); border-radius: 8px; padding: 7px 13px; transition: color .15s, border-color .15s; }
.back-link:hover { color: var(--text); border-color: var(--accent-border); }
.back-link i { color: var(--accent); }

.alert-success { display: flex; align-items: center; gap: 9px; background: var(--success-soft); border: 1px solid var(--success-border); color: var(--success); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; font-size: .85rem; }

.section-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 10px; overflow: hidden; box-shadow: var(--shadow); }
.section-head { padding: 15px 20px; border-bottom: 1px solid var(--card-border2); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.section-head .lbl { font-size: .8rem; font-weight: 700; color: var(--text-muted); display: flex; align-items: center; }
.section-head .lbl i { color: var(--accent); }
.count-badge { background: var(--accent-soft); border: 1px solid var(--accent-border); color: var(--accent); border-radius: 99px; padding: 3px 10px; font-size: .72rem; font-weight: 700; }

.admin-table { width: 100%; border-collapse: collapse; }
.admin-table th { padding: 11px 20px; font-size: .68rem; font-weight: 700; color: var(--text-faint); text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid var(--card-border); background: var(--card-bg); }
.admin-table td { padding: 13px 20px; border-bottom: 1px solid var(--card-border2); font-size: .85rem; color: var(--text); vertical-align: middle; }
.admin-table tr:last-child td { border-bottom: none; }
.admin-table tr:hover td { background: var(--card-bg-raised); }

.avatar-circle { width: 32px; height: 32px; border-radius: 8px; background: var(--accent-soft); border: 1px solid var(--accent-border); display: flex; align-items: center; justify-content: center; font-size: .72rem; font-weight: 700; color: var(--accent); flex-shrink: 0; }

.role-badge { border-radius: 99px; padding: 3px 10px; font-size: .7rem; font-weight: 700; display: inline-block; }
.role-admin   { background: var(--danger-soft); border: 1px solid var(--danger-border); color: var(--danger); }
.role-teacher { background: var(--accent-soft); border: 1px solid var(--accent-border); color: var(--accent); }
.role-student { background: var(--success-soft); border: 1px solid var(--success-border); color: var(--success); }

.status-active   { display: inline-flex; align-items: center; gap: 5px; font-size: .75rem; font-weight: 700; color: var(--success); }
.status-inactive { display: inline-flex; align-items: center; gap: 5px; font-size: .75rem; font-weight: 700; color: var(--danger); }

.btn-action { border-radius: 7px; padding: 5px 12px; font-size: .75rem; font-weight: 600; cursor: pointer; transition: background .15s, border-color .15s; }
.btn-deactivate { background: var(--danger-soft); border: 1px solid var(--danger-border); color: var(--danger); }
.btn-deactivate:hover { border-color: var(--danger); }
.btn-activate { background: var(--success-soft); border: 1px solid var(--success-border); color: var(--success); }
.btn-activate:hover { border-color: var(--success); }
.btn-delete { background: var(--danger-soft); border: 1px solid var(--danger-border); color: var(--danger); }
.btn-delete:hover { border-color: var(--danger); }

.pagination-wrap { padding: 16px 20px; border-top: 1px solid var(--card-border2); }
</style>

<div class="admin-page">
  <div class="page-header">
    <div class="container-lg px-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
          <div class="t-eyebrow">ADMIN PANEL</div>
          <h1 class="t-title-main">Manage Users</h1>
          <p class="t-muted">View and manage all registered accounts</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="back-link">
          <i class="bi bi-house-fill"></i> Dashboard
        </a>
      </div>
    </div>
  </div>

  <div class="container-lg px-4">

    <div class="section-card">
      <div class="section-head">
        <span class="lbl"><i class="bi bi-people-fill me-2"></i>All Users</span>
        <span class="count-badge">{{ $users->total() }} total</span>
      </div>

      <div style="overflow-x:auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Activity</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="avatar-circle">{{ strtoupper(substr($user->name,0,1)) }}</div>
                  <span class="t-text" style="font-weight:600;">{{ $user->name }}</span>
                </div>
              </td>
              <td style="color:var(--text-muted);">{{ $user->email }}</td>
              <td>
                @php
                  $roleClass = match($user->role) {
                    'admin'   => 'role-admin',
                    'teacher' => 'role-teacher',
                    default   => 'role-student',
                  };
                @endphp
                <span class="role-badge {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
              </td>
              <td>
                @if($user->is_active)
                  <span class="status-active"><i class="bi bi-check-circle-fill"></i> Active</span>
                @else
                  <span class="status-inactive"><i class="bi bi-x-circle-fill"></i> Inactive</span>
                @endif
              </td>
              <td>
                @if($user->role !== 'admin')
                <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                  @if($user->is_active)
                  <form id="deactivate-form-{{ $user->id }}" method="POST" action="{{ route('admin.deactivate-user', $user->id) }}" style="display:inline;">
                    @csrf
                    <button type="button" class="btn-action btn-deactivate" title="Deactivate user"
                            onclick="handleUserAction('deactivate', {{ $user->id }}, '{{ str_replace("'", "\\'", $user->name) }}')">
                      <i class="bi bi-lock-fill me-1"></i> Deactivate
                    </button>
                  </form>
                  @else
                  <form id="activate-form-{{ $user->id }}" method="POST" action="{{ route('admin.activate-user', $user->id) }}" style="display:inline;">
                    @csrf
                    <button type="button" class="btn-action btn-activate" title="Activate user"
                            onclick="handleUserAction('activate', {{ $user->id }}, '{{ str_replace("'", "\\'", $user->name) }}')">
                      <i class="bi bi-unlock-fill me-1"></i> Activate
                    </button>
                  </form>
                  @endif
                  <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('admin.delete-user', $user->id) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="button" class="btn-action btn-delete" title="Delete user"
                            onclick="handleUserAction('delete', {{ $user->id }}, '{{ str_replace("'", "\\'", $user->name) }}')">
                      <i class="bi bi-trash-fill me-1"></i> Delete
                    </button>
                  </form>
                </div>
                @else
                <span style="font-size:.75rem;color:var(--text-faint);">—</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" style="text-align:center;padding:40px;color:var(--text-faint);">No users found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($users->hasPages())
      <div class="pagination-wrap">
        {{ $users->links() }}
      </div>
      @endif
    </div>

  </div>
</div>

@push('scripts')
<script>
// Debug: Log when script loads
console.log('Manage users script loaded');
console.log('confirmAction available:', typeof window.confirmAction);

function handleUserAction(action, userId, userName) {
    console.log('=== handleUserAction START ===');
    console.log('Action:', action);
    console.log('User ID:', userId);
    console.log('User Name:', userName);
    
    let message, title, formId;
    
    switch(action) {
        case 'activate':
            message = `Activate ${userName}? They will be able to log in.`;
            title = 'Activate User';
            formId = `activate-form-${userId}`;
            break;
        case 'deactivate':
            message = `Deactivate ${userName}? They will not be able to log in.`;
            title = 'Deactivate User';
            formId = `deactivate-form-${userId}`;
            break;
        case 'delete':
            message = `Permanently delete ${userName}? This action cannot be undone.`;
            title = 'Delete User';
            formId = `delete-form-${userId}`;
            break;
    }
    
    console.log('Form ID:', formId);
    console.log('Message:', message);
    console.log('Title:', title);
    
    // Check if form exists
    const form = document.getElementById(formId);
    console.log('Form element:', form);
    if (!form) {
        console.error('ERROR: Form not found!');
        alert('Error: Form not found - ' + formId);
        return;
    }
    
    // Ensure confirmAction exists
    if (typeof window.confirmAction !== 'function') {
        console.error('ERROR: confirmAction is not defined');
        alert('Error: confirmAction function not available');
        if (confirm(message)) {
            form.submit();
        }
        return;
    }
    
    console.log('Calling confirmAction...');
    
    // Call confirmAction and handle result
    const promise = window.confirmAction(message, title);
    console.log('Promise returned:', promise);
    
    promise.then(function(result) {
        console.log('Promise resolved with:', result);
        console.log('Result type:', typeof result);
        console.log('Result === true:', result === true);
        
        if (result === true) {
            console.log('User confirmed! Submitting form...');
            form.submit();
            console.log('Form.submit() called');
        } else {
            console.log('User cancelled or result was false');
        }
    }).catch(function(error) {
        console.error('Promise rejected with error:', error);
    });
    
    console.log('=== handleUserAction END ===');
}
</script>
@endpush
@endsection