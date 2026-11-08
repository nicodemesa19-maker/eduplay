@extends('layouts.app')
@section('content')
<style>
/* stylelint-disable */
/* Version 2.1 - Forced dark pagination colors */
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
.section-head { padding: 18px 24px; border-bottom: 1px solid var(--card-border2); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
.section-head .lbl { font-size: .82rem; font-weight: 700; color: var(--text-muted); display: flex; align-items: center; gap: 8px; }
.section-head .lbl i { color: var(--accent); }
.count-badge { background: var(--accent-soft); border: 1px solid var(--accent-border); color: var(--accent); border-radius: 99px; padding: 3px 10px; font-size: .72rem; font-weight: 700; }

.admin-table { width: 100%; border-collapse: collapse; }
.admin-table th { padding: 14px 24px; font-size: .7rem; font-weight: 700; color: var(--text-faint); text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid var(--card-border); background: var(--card-bg); }
.admin-table td { padding: 16px 24px; border-bottom: 1px solid var(--card-border2); font-size: .88rem; color: var(--text); vertical-align: middle; }
.admin-table tr:last-child td { border-bottom: none; }
.admin-table tr:hover td { background: var(--card-bg-raised); }

.avatar-circle { width: 36px; height: 36px; border-radius: 9px; background: var(--accent-soft); border: 1px solid var(--accent-border); display: flex; align-items: center; justify-content: center; font-size: .78rem; font-weight: 700; color: var(--accent); flex-shrink: 0; }

.role-badge { border-radius: 99px; padding: 3px 10px; font-size: .7rem; font-weight: 700; display: inline-block; }
.role-admin   { background: var(--danger-soft); border: 1px solid var(--danger-border); color: var(--danger); }
.role-teacher { background: var(--accent-soft); border: 1px solid var(--accent-border); color: var(--accent); }
.role-student { background: var(--success-soft); border: 1px solid var(--success-border); color: var(--success); }

.status-active   { display: inline-flex; align-items: center; gap: 5px; font-size: .75rem; font-weight: 700; color: var(--success); }
.status-inactive { display: inline-flex; align-items: center; gap: 5px; font-size: .75rem; font-weight: 700; color: var(--danger); }

.btn-action { border-radius: 8px; padding: 7px 14px; font-size: .78rem; font-weight: 600; cursor: pointer; transition: background .15s, border-color .15s; }
.btn-deactivate { background: var(--danger-soft); border: 1px solid var(--danger-border); color: var(--danger); }
.btn-deactivate:hover { border-color: var(--danger); }
.btn-activate { background: var(--success-soft); border: 1px solid var(--success-border); color: var(--success); }
.btn-activate:hover { border-color: var(--success); }
.btn-delete { background: var(--danger-soft); border: 1px solid var(--danger-border); color: var(--danger); }
.btn-delete:hover { border-color: var(--danger); }

.pagination-wrap { 
  padding: 24px 20px !important;
  border-top: 1px solid var(--card-border2) !important;
  margin-top: 8px !important;
}

/* Custom pagination styles */
.pagination-wrap nav {
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 100% !important;
}

.pagination-wrap nav > div:first-child {
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  flex-wrap: wrap !important;
  gap: 10px !important;
}

.pagination-wrap p {
  display: none !important; /* Hide "Showing X to Y" text to save space */
}

.pagination-wrap nav a,
.pagination-wrap nav span,
.pagination-wrap nav a.page-link,
.pagination-wrap nav span.page-link {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  min-width: 40px !important;
  height: 40px !important;
  padding: 0 12px !important;
  margin: 0 !important;
  border-radius: 10px !important;
  font-size: 0.9rem !important;
  font-weight: 600 !important;
  border: 1px solid rgba(79, 124, 255, 0.3) !important;
  background: rgba(20, 24, 35, 0.8) !important;
  color: rgba(140, 160, 200, 0.9) !important;
  text-decoration: none !important;
  transition: all 0.15s ease !important;
  box-shadow: none !important;
}

.pagination-wrap nav a:hover,
.pagination-wrap nav a.page-link:hover {
  border-color: rgba(79, 124, 255, 0.6) !important;
  background: rgba(79, 124, 255, 0.15) !important;
  color: #8fb4ff !important;
  transform: translateY(-1px) !important;
  box-shadow: 0 2px 8px rgba(79, 124, 255, 0.25) !important;
}

.pagination-wrap nav span[aria-current="page"],
.pagination-wrap nav span.page-link[aria-current="page"] {
  background: rgba(79, 124, 255, 0.25) !important;
  border-color: rgba(79, 124, 255, 0.5) !important;
  color: #a8c5ff !important;
  font-weight: 700 !important;
  box-shadow: 0 0 0 2px rgba(79, 124, 255, 0.2) !important;
}

.pagination-wrap nav span[aria-disabled="true"],
.pagination-wrap nav span.page-link[aria-disabled="true"] {
  opacity: 0.3 !important;
  cursor: not-allowed !important;
  pointer-events: none !important;
  border-color: rgba(255, 255, 255, 0.08) !important;
  background: rgba(20, 24, 35, 0.4) !important;
  color: rgba(140, 160, 200, 0.4) !important;
}

.pagination-wrap svg {
  width: 16px;
  height: 16px;
}

.filter-select {
  background: var(--card-bg-raised);
  border: 1px solid var(--card-border);
  color: var(--text);
  border-radius: 8px;
  padding: 8px 12px;
  font-size: 0.85rem;
  transition: border-color 0.15s, background 0.15s;
}
.filter-select:focus {
  background: var(--card-bg);
  border-color: var(--accent-border);
  outline: none;
  box-shadow: 0 0 0 3px var(--accent-soft);
}
.filter-select option {
  background: var(--card-bg);
  color: var(--text);
}

/* ---------- Nav tabs ---------- */
.nav-tabs-wrapper { display: flex; align-items: center; gap: 4px; margin-top: 20px; overflow-x: auto; padding-bottom: 0; border-bottom: 1px solid var(--head-border); }
.nav-tabs-wrapper::-webkit-scrollbar { height: 0; }
.nav-tab { display: inline-flex; align-items: center; gap: 7px; padding: 10px 14px; font-size: .86rem; font-weight: 600; color: var(--text-muted); text-decoration: none; border-bottom: 2px solid transparent; white-space: nowrap; transition: color .15s, border-color .15s; }
.nav-tab:hover { color: var(--text); }
.nav-tab.active { color: var(--text); border-bottom-color: var(--accent); }
.nav-tab .tab-badge { background: var(--warning-soft); color: var(--warning); border-radius: 99px; padding: 1px 7px; font-size: .7rem; font-weight: 700; }
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

      <div class="nav-tabs-wrapper">
        <a href="{{ url('/admin/dashboard') }}" class="nav-tab">
          <i class="bi bi-house-fill"></i> Dashboard
        </a>
        <a href="{{ route('admin.pending-teachers') }}" class="nav-tab">
          <i class="bi bi-person-check-fill"></i> Pending Teachers
          @if($pendingCount > 0)
          <span class="tab-badge">{{ $pendingCount }}</span>
          @endif
        </a>
        <a href="{{ route('admin.manage-users') }}" class="nav-tab active">
          <i class="bi bi-people-fill"></i> Manage Users
        </a>
      </div>
    </div>
  </div>

  <div class="container-lg px-4">

    <!-- Filter Section -->
    <div class="section-card mb-4">
      <div class="section-head">
        <span class="lbl"><i class="bi bi-funnel-fill me-2"></i>Filters</span>
      </div>
      <div style="padding: 24px;">
        <form method="GET" action="{{ route('admin.manage-users') }}" id="filter-form">
          <div class="row g-4 align-items-end">
            <!-- Role Filter -->
            <div class="col-md-3">
              <label style="font-size: .75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; display: block;">
                <i class="bi bi-person-badge"></i> User Role
              </label>
              <select name="role" class="form-control filter-select" onchange="document.getElementById('filter-form').submit()">
                <option value="all" {{ request('role', 'all') === 'all' ? 'selected' : '' }}>All Roles</option>
                <option value="teacher" {{ request('role') === 'teacher' ? 'selected' : '' }}>Teachers ({{ $teacherCount }})</option>
                <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Students ({{ $studentCount }})</option>
              </select>
            </div>

            <!-- Status Filter -->
            <div class="col-md-3">
              <label style="font-size: .75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; display: block;">
                <i class="bi bi-toggle-on"></i> Account Status
              </label>
              <select name="status" class="form-control filter-select" onchange="document.getElementById('filter-form').submit()">
                <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
              </select>
            </div>

            <!-- Search -->
            <div class="col-md-4">
              <label style="font-size: .75rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; display: block;">
                <i class="bi bi-search"></i> Search
              </label>
              <input type="text" name="search" class="form-control filter-select" 
                     placeholder="Search by name or email..." 
                     value="{{ request('search') }}">
            </div>

            <!-- Actions -->
            <div class="col-md-2">
              <button type="submit" class="btn btn-action btn-primary w-100" style="background: var(--accent); color: white; border: none; padding: 8px;">
                <i class="bi bi-search"></i> Search
              </button>
              @if(request()->hasAny(['role', 'status', 'search']))
              <a href="{{ route('admin.manage-users') }}" class="btn btn-action w-100 mt-2" 
                 style="background: var(--card-bg-raised); color: var(--text-muted); border: 1px solid var(--card-border); padding: 8px; text-align: center; display: block; text-decoration: none;">
                <i class="bi bi-x-circle"></i> Clear
              </a>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="section-card">
      <div class="section-head">
        <span class="lbl"><i class="bi bi-people-fill me-2"></i>
          @if(request('role') === 'teacher')
            Teachers
          @elseif(request('role') === 'student')
            Students
          @else
            All Users
          @endif
        </span>
        <span class="count-badge">{{ $users->total() }} found</span>
      </div>

      <div style="overflow-x:auto;">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email / Username</th>
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
              <td style="color:var(--text-muted);">
                @if($user->role === 'student')
                  <i class="bi bi-person-badge me-1" style="color:var(--accent);"></i>{{ $user->username ?? 'N/A' }}
                @else
                  {{ $user->email }}
                @endif
              </td>
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