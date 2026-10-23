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
  --input-bg: rgba(255,255,255,.06);
  --input-border: rgba(255,255,255,.12);
  --cyan: #00E5FF;
  --pink: #FF3DAE;
  --lime: #B6FF3D;
  --purple: #a5b4fc;
}
[data-bs-theme="light"] {
  --pg-bg: #f0f2f8;
  --pg-bg2: #e8eaf6;
  --card-bg: rgba(255,255,255,.9);
  --card-border: rgba(99,102,241,.15);
  --text: #1e1b4b;
  --text-muted: rgba(30,27,75,.55);
  --input-bg: #fff;
  --input-border: rgba(99,102,241,.25);
}

.t-page { background: var(--pg-bg); min-height: 100vh; padding: 0 0 60px; }
.t-header { background: linear-gradient(135deg,#0d0f2e,#1a0e4a); border-bottom: 1px solid rgba(99,102,241,.2); padding: 26px 0; margin-bottom: 28px; }
.t-card { background: var(--card-bg); border: 1px solid var(--card-border); border-radius: 18px; overflow: hidden; margin-bottom: 20px; }
.t-card-head { padding: 16px 22px; border-bottom: 1px solid var(--card-border); }
.t-card-body { padding: 20px 22px; }
.t-text { color: var(--text); }
.t-muted { color: var(--text-muted); }

.profile-avatar { width: 100px; height: 100px; border-radius: 16px; border: 3px solid var(--purple); object-fit: cover; }
.profile-name { font-size: 1.8rem; font-weight: 700; color: var(--text); margin: 0; }
.profile-detail { padding: 12px; background: rgba(255,255,255,.02); border-radius: 8px; margin-bottom: 12px; }
.profile-label { font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }
.profile-value { font-size: 1.1rem; color: var(--text); font-weight: 600; margin-top: 4px; }

.action-btn { background: linear-gradient(135deg,#6366f1,#7c3aed); border: none; color: #fff; border-radius: 10px; padding: 10px 20px; font-weight: 700; cursor: pointer; transition: all .2s; }
.action-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(99,102,241,.4); color: #fff; }
.action-btn-danger { background: linear-gradient(135deg,#ef4444,#dc2626); }
.action-btn-warning { background: linear-gradient(135deg,#f59e0b,#d97706); }

.log-item { padding: 16px; background: rgba(255,255,255,.03); border: 1px solid var(--card-border); border-radius: 10px; margin-bottom: 12px; }
.log-badge { display: inline-block; padding: 4px 10px; background: rgba(0,229,255,.15); border: 1px solid var(--cyan); border-radius: 16px; font-size: 0.8rem; color: var(--cyan); font-weight: 600; }
.log-badge-teacher { background: rgba(255,61,174,.15); border-color: var(--pink); color: var(--pink); }
.log-time { font-size: 0.9rem; color: var(--text-muted); }

.form-control { background: var(--input-bg); border: 1px solid var(--input-border); color: var(--text); border-radius: 8px; padding: 10px 14px; }
.form-control:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12); background: rgba(255,255,255,.08); color: var(--text); }
.form-label { color: var(--text-muted); font-weight: 600; margin-bottom: 6px; }
</style>

<div class="t-page">
  <div class="t-header">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h1 style="margin:0;font-size:2rem;color:var(--purple);">👤 Student Profile</h1>
          <p class="t-muted mb-0">View and manage student information</p>
        </div>
        <a href="{{ route('teacher.students') }}" class="action-btn">
          <i class="bi bi-arrow-left"></i> Back to Students
        </a>
      </div>
    </div>
  </div>

  <div class="container">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="row">
      <!-- Left Column - Student Info -->
      <div class="col-lg-8">
        <!-- Basic Info Card -->
        <div class="t-card">
          <div class="t-card-head">
            <h3 class="t-text" style="margin:0;">📋 Basic Information</h3>
          </div>
          <div class="t-card-body">
            <div class="d-flex align-items-center gap-3 mb-4">
              @if($student->avatar_upload_path)
                <img src="{{ $student->getAvatarUrl() }}" alt="Avatar" class="profile-avatar">
              @else
                <div class="profile-avatar" style="display:flex;align-items:center;justify-content:center;font-size:3rem;background:linear-gradient(135deg,#6366f1,#7c3aed);">
                  {{ $student->avatar_character ? $student->getCharacterEmoji() : '🧑‍🎓' }}
                </div>
              @endif
              <div>
                <h2 class="profile-name">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</h2>
                <p class="t-muted mb-0">@{{ $student->username }}</p>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-md-4">
                <div class="profile-detail">
                  <div class="profile-label">Student ID</div>
                  <div class="profile-value">{{ $student->student_id_number ?? 'Not assigned' }}</div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="profile-detail">
                  <div class="profile-label">Grade Level</div>
                  <div class="profile-value">Grade {{ $student->grade_level }}</div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="profile-detail">
                  <div class="profile-label">Nickname</div>
                  <div class="profile-value">{{ $student->nickname ?? 'Not set' }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Profile Details Card -->
        <div class="t-card">
          <div class="t-card-head">
            <h3 class="t-text" style="margin:0;">✨ Profile Details</h3>
          </div>
          <div class="t-card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <div class="profile-detail">
                  <div class="profile-label">Birthdate</div>
                  <div class="profile-value">{{ $student->birthdate ? $student->birthdate->format('M d, Y') : 'Not set' }}</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="profile-detail">
                  <div class="profile-label">Favorite Math Topic</div>
                  <div class="profile-value">{{ $student->favorite_math_topic ?? 'Not set' }}</div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="profile-detail">
                  <div class="profile-label">Bio / Motto</div>
                  <div class="profile-value">{{ $student->bio ?? 'Not set' }}</div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="profile-detail">
                  <div class="profile-label">Hobbies</div>
                  <div class="profile-value">
                    @if($student->hobbies && count($student->hobbies) > 0)
                      {{ implode(', ', $student->hobbies) }}
                    @else
                      Not set
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="profile-detail">
                  <div class="profile-label">Game Mode Preference</div>
                  <div class="profile-value">{{ $student->game_mode_preference ?? 'Not set' }}</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="profile-detail">
                  <div class="profile-label">Display Badge</div>
                  <div class="profile-value">{{ $student->display_badge ?? 'Not set' }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Changes Card -->
        <div class="t-card">
          <div class="t-card-head">
            <div class="d-flex justify-content-between align-items-center">
              <h3 class="t-text" style="margin:0;">🕒 Recent Changes</h3>
              <a href="{{ route('teacher.student-profile.changelog', $student->id) }}" class="btn btn-sm action-btn">
                View All
              </a>
            </div>
          </div>
          <div class="t-card-body">
            @if($changeLogs->count() > 0)
              @foreach($changeLogs as $log)
                <div class="log-item">
                  <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="log-badge {{ $log->changed_by_role === 'teacher' ? 'log-badge-teacher' : '' }}">
                      {{ $log->changed_by_role === 'teacher' ? '👨‍🏫 Teacher' : '👤 Student' }}
                    </span>
                    <span class="log-time"><i class="bi bi-clock"></i> {{ $log->getFormattedDate() }}</span>
                  </div>
                  <div class="t-text">
                    <strong>{{ ucfirst(str_replace('_', ' ', $log->field_name)) }}</strong>: 
                    {{ $log->getFormattedOldValue() }} → {{ $log->getFormattedNewValue() }}
                  </div>
                  @if($log->reason)
                    <div class="t-muted mt-2"><em>Reason: {{ $log->reason }}</em></div>
                  @endif
                </div>
              @endforeach
            @else
              <p class="t-muted mb-0">No recent changes</p>
            @endif
          </div>
        </div>
      </div>

      <!-- Right Column - Teacher Actions -->
      <div class="col-lg-4">
        <!-- Username Management Card -->
        <div class="t-card">
          <div class="t-card-head">
            <h3 class="t-text" style="margin:0;font-size:1.3rem;">🎮 Username Management</h3>
          </div>
          <div class="t-card-body">
            <div class="mb-3">
              <div class="profile-label">Current Username</div>
              <div class="profile-value">@{{ $student->username }}</div>
            </div>

            @if($student->original_username)
              <div class="mb-3">
                <div class="profile-label">Original Username</div>
                <div class="profile-value">@{{ $student->original_username }}</div>
              </div>
            @endif

            <div class="mb-3">
              <div class="profile-label">Changes Today</div>
              <div class="profile-value">{{ $student->username_changes_count ?? 0 }} / 3</div>
            </div>

            <!-- Revert Username -->
            @if($student->original_username)
              <button class="action-btn action-btn-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#revertUsernameModal">
                <i class="bi bi-arrow-counterclockwise"></i> Revert to Original
              </button>
            @endif

            <!-- Edit Username -->
            <button class="action-btn w-100 mb-2" data-bs-toggle="modal" data-bs-target="#editUsernameModal">
              <i class="bi bi-pencil-fill"></i> Edit Username
            </button>
          </div>
        </div>

        <!-- Field Management Card -->
        <div class="t-card">
          <div class="t-card-head">
            <h3 class="t-text" style="margin:0;font-size:1.3rem;">🛡️ Field Management</h3>
          </div>
          <div class="t-card-body">
            <p class="t-muted" style="font-size:0.9rem;">Clear inappropriate content from profile fields</p>
            
            <button class="action-btn action-btn-danger w-100" data-bs-toggle="modal" data-bs-target="#clearFieldModal">
              <i class="bi bi-trash-fill"></i> Clear a Field
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Revert Username Modal -->
<div class="modal fade" id="revertUsernameModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="background:var(--pg-bg2);color:var(--text);">
      <div class="modal-header" style="border-bottom:1px solid var(--card-border);">
        <h5 class="modal-title">Revert Username</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('teacher.student-profile.revert-username', $student->id) }}">
        @csrf
        <div class="modal-body">
          <p>This will revert the username to: <strong>@{{ $student->original_username }}</strong></p>
          <div class="mb-3">
            <label class="form-label">Reason (required)</label>
            <textarea name="reason" class="form-control" rows="3" required 
                      placeholder="e.g. Inappropriate username, violates guidelines"></textarea>
          </div>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--card-border);">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="action-btn action-btn-warning">Revert Username</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Username Modal -->
<div class="modal fade" id="editUsernameModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="background:var(--pg-bg2);color:var(--text);">
      <div class="modal-header" style="border-bottom:1px solid var(--card-border);">
        <h5 class="modal-title">Edit Username</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('teacher.student-profile.edit-username', $student->id) }}">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">New Username</label>
            <input type="text" name="username" class="form-control" 
                   value="{{ $student->username }}" required maxlength="255">
          </div>
          <div class="mb-3">
            <label class="form-label">Reason (required)</label>
            <textarea name="reason" class="form-control" rows="3" required 
                      placeholder="e.g. Correcting typo, parent request"></textarea>
          </div>
          <p class="t-muted" style="font-size:0.85rem;">This will reset the student's daily change counter.</p>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--card-border);">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="action-btn">Update Username</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Clear Field Modal -->
<div class="modal fade" id="clearFieldModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="background:var(--pg-bg2);color:var(--text);">
      <div class="modal-header" style="border-bottom:1px solid var(--card-border);">
        <h5 class="modal-title">Clear Field</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('teacher.student-profile.clear-field', $student->id) }}">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Field to Clear</label>
            <select name="field_name" class="form-control" required>
              <option value="">-- Select Field --</option>
              <option value="nickname">Nickname</option>
              <option value="bio">Bio / Motto</option>
              <option value="hobbies">Hobbies</option>
              <option value="display_badge">Display Badge</option>
              <option value="avatar_character">Avatar Character</option>
              <option value="avatar_upload_path">Avatar Upload</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Reason (required)</label>
            <textarea name="reason" class="form-control" rows="3" required 
                      placeholder="e.g. Inappropriate content, violates community guidelines"></textarea>
          </div>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--card-border);">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="action-btn action-btn-danger">Clear Field</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
