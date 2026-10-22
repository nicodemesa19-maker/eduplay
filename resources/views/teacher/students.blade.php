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
.t-table th { font-size: 0.98rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .8px; padding: 11px 20px; border-bottom: 1px solid var(--card-border); background: var(--card-bg); }
.t-table td { padding: 13px 20px; border-bottom: 1px solid var(--card-border2); color: var(--text); font-size: 1.15rem; vertical-align: middle; }
.t-table tr:last-child td { border-bottom: none; }
.t-table tr:hover td { background: rgba(99,102,241,.04); }

/* Responsive table adjustments */
@media (max-width: 1400px) {
  .t-table th, .t-table td { padding: 11px 14px; font-size: 1.05rem; }
}
.btn-primary-t { background: linear-gradient(135deg,#6366f1,#7c3aed); border: none; color: #fff; border-radius: 10px; padding: 9px 18px; font-size: 1.15rem; font-weight: 700; cursor: pointer; transition: all .2s; box-shadow: 0 4px 14px rgba(99,102,241,.3); }
.btn-primary-t:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(99,102,241,.45); }
.btn-green-t { background: linear-gradient(135deg,#059669,#10b981); border: none; color: #fff; border-radius: 10px; padding: 9px 18px; font-size: 1.15rem; font-weight: 700; cursor: pointer; transition: all .2s; width: 100%; }
.btn-green-t:hover { transform: translateY(-1px); }
.btn-outline-t { background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text); border-radius: 10px; padding: 9px 18px; font-size: 1.15rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all .2s; }
.btn-outline-t:hover { border-color: rgba(99,102,241,.4); color: #a5b4fc; }
.code-pill { font-family: monospace; background: rgba(99,102,241,.1); border: 1px solid rgba(99,102,241,.2); color: #a5b4fc; border-radius: 6px; padding: 2px 8px; font-size: 1.1rem; }
.avatar-sm { width: 32px; height: 32px; border-radius: 9px; background: linear-gradient(135deg,#6366f1,#7c3aed); display: flex; align-items: center; justify-content: center; font-size: 1.0rem; font-weight: 800; color: #fff; flex-shrink: 0; }
.lvl-badge { background: rgba(99,102,241,.15); border: 1px solid rgba(99,102,241,.3); color: #a5b4fc; border-radius: 99px; padding: 2px 10px; font-size: 0.98rem; font-weight: 700; }
.search-box { max-width: 400px; }
/* Navigation Tabs */
.nav-tabs-wrapper { display: flex; gap: 8px; align-items: center; margin-top: 20px; overflow-x: auto; }
.nav-tab { background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08); color: var(--text-muted); text-decoration: none; padding: 10px 20px; border-radius: 10px; font-size: 1.05rem; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all .2s; white-space: nowrap; }
.nav-tab:hover { border-color: rgba(99,102,241,.3); color: #a5b4fc; background: rgba(99,102,241,.1); }
.nav-tab.active { background: linear-gradient(135deg,#6366f1,#7c3aed); border-color: transparent; color: #fff; box-shadow: 0 4px 12px rgba(99,102,241,.3); }
.nav-tab i { font-size: 1.1rem; }
[data-bs-theme="light"] .nav-tab { background: rgba(99,102,241,.08); border-color: rgba(99,102,241,.15); color: rgba(30,27,75,.65); }
[data-bs-theme="light"] .nav-tab:hover { background: rgba(99,102,241,.15); color: #6366f1; }
[data-bs-theme="light"] .nav-tab.active { background: linear-gradient(135deg,#6366f1,#7c3aed); color: #fff; }

/* Modal backdrop opacity */
.modal-backdrop.show { opacity: 0.6; background-color: rgba(0,0,0,0.6); }
#updateStudentModal .modal-content { position: relative; z-index: 1055; background: #0d0f2e !important; opacity: 1; }
[data-bs-theme="light"] #updateStudentModal .modal-content { background: #ffffff !important; }

/* Fix page height to prevent cards from spreading apart */
.t-page { min-height: auto !important; }
</style>

<div class="t-page">
  <div class="t-header">
    <div class="container-lg px-4">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
          <div style="font-size:1.05rem;font-weight:700;color:#818cf8;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:6px;">
            <i class="bi bi-people-fill me-1"></i> Student Management
          </div>
          <h1 style="font-family:'Space Grotesk',sans-serif;font-size:2.4rem;font-weight:900;margin:0;" class="t-text">Student Accounts</h1>
          <p class="t-muted mt-1">Create and manage student login credentials</p>
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
        <a href="{{ route('teacher.classrooms') }}" class="nav-tab">
          <i class="bi bi-building"></i> Classrooms
        </a>
        <a href="{{ route('teacher.students') }}" class="nav-tab active">
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

    {{-- Center container for cards --}}
    <div style="max-width:1200px;margin:0 auto;">

    {{-- Add New Student --}}
    <div class="t-card mb-4">
      <div class="t-card-head d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
          <div class="t-text fw-bold" style="font-size:1.35rem;">
            <i class="bi bi-person-plus-fill me-2" style="color:#10b981;"></i>Add New Student
          </div>
          <div class="t-muted mt-1">Create login credentials for a new student (Username = Custom Username, Password = ID Number)</div>
        </div>
        <button class="btn-primary-t" type="button" data-bs-toggle="collapse" data-bs-target="#createStudentFormWrap">
          <i class="bi bi-person-plus-fill me-1"></i> New Student
        </button>
      </div>

      {{-- Add Student Form (Collapsible) --}}
      <div class="collapse {{ $errors->has('first_name') || $errors->has('last_name') || $errors->has('username') || $errors->has('student_id_number') ? 'show' : '' }}" id="createStudentFormWrap">
        <div class="p-4" style="background:rgba(16,185,129,.05);border-bottom:1px solid var(--card-border2);">
          <form method="POST" action="{{ route('teacher.create-student') }}">
            @csrf
            
            {{-- Row 1: Personal Information --}}
            <div class="mb-2" style="color:rgba(255,255,255,.35);font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;">
              <i class="bi bi-person-circle me-1"></i> Personal Information
            </div>
            <div class="row g-3 mb-4">
              <div class="col-md-4">
                <label class="t-label">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="e.g. Juan" required class="t-input">
                @error('first_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-4">
                <label class="t-label">Middle Name</label>
                <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="Optional" class="t-input">
                @error('middle_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-4">
                <label class="t-label">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="e.g. Dela Cruz" required class="t-input">
                @error('last_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
              </div>
            </div>
            
            {{-- Row 2: Login Credentials --}}
            <div class="mb-2" style="color:rgba(255,255,255,.35);font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;">
              <i class="bi bi-key-fill me-1"></i> Login Credentials
            </div>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label class="t-label">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="e.g. jdelacruz" required class="t-input">
                @error('username')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                <small class="t-muted" style="font-size:0.8rem;display:block;margin-top:4px;">
                  <i class="bi bi-info-circle me-1"></i>Used for logging in
                </small>
              </div>
              <div class="col-md-6">
                <label class="t-label">Student ID Number (Password)</label>
                <input type="text" name="student_id_number" value="{{ old('student_id_number') }}" placeholder="e.g. 2024-001" required class="t-input">
                @error('student_id_number')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                <small class="t-muted" style="font-size:0.8rem;display:block;margin-top:4px;">
                  <i class="bi bi-info-circle me-1"></i>This will be the student's password
                </small>
              </div>
            </div>
            
            {{-- Row 3: Academic Information --}}
            <div class="mb-2" style="color:rgba(255,255,255,.35);font-size:0.85rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;">
              <i class="bi bi-mortarboard-fill me-1"></i> Academic Information
            </div>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="t-label">Grade Level</label>
                <select name="grade_level" class="t-input" required>
                  @for($g=1;$g<=6;$g++)
                    <option value="{{ $g }}" {{ old('grade_level') == $g ? 'selected' : '' }}>Grade {{ $g }}</option>
                  @endfor
                </select>
              </div>
              <div class="col-md-5">
                <label class="t-label">Assign to Classroom</label>
                <select name="classroom_id" class="t-input" required>
                  @forelse($classrooms as $c)
                    <option value="{{ $c->id }}" {{ old('classroom_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->class_code }})</option>
                  @empty
                    <option value="" disabled selected>Create a classroom first!</option>
                  @endforelse
                </select>
              </div>
              <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn-green-t" style="width:100%;padding:11px;" {{ $classrooms->isEmpty() ? 'disabled' : '' }}>
                  <i class="bi bi-check-circle-fill me-1"></i> Save Student
                </button>
              </div>
            </div>
            
          </form>
        </div>
      </div>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Students List --}}
    <div class="t-card">
      <div class="t-card-head d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
          <div class="t-text fw-bold" style="font-size:1.35rem;">
            All Students <span class="t-muted" style="font-size:1.1rem;font-weight:400;">({{ $students->count() }} total)</span>
          </div>
          <div class="t-muted mt-1">Manage student accounts and credentials</div>
        </div>
        <div class="search-box">
          <input type="text" id="studentSearch" class="t-input" placeholder="🔍 Search students..." style="padding:8px 14px;font-size:1rem;">
        </div>
      </div>

      {{-- Student List Table --}}
      <div style="overflow-x:auto;">
        <table class="t-table w-100" id="studentsTable">
          <thead>
            <tr>
              <th>Student Name</th>
              <th>ID Number (Password)</th>
              <th>Grade</th>
              <th>Classroom</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($students as $st)
            <tr class="student-row">
              <td>
                <div class="d-flex align-items-center gap-2">
                  <div class="avatar-sm" style="background:linear-gradient(135deg,#059669,#10b981);">{{ strtoupper(substr($st->name,0,1)) }}</div>
                  <span class="fw-semibold student-name">{{ $st->name }}</span>
                </div>
              </td>
              <td>
                <span class="code-pill">{{ $st->student_id_number ?? '—' }}</span>
              </td>
              <td><span class="lvl-badge">Grade {{ $st->grade_level ?? '—' }}</span></td>
              <td>
                @foreach($st->classrooms as $cr)
                  <span style="background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.25);color:#a5b4fc;border-radius:6px;padding:2px 8px;font-size:0.95rem;margin-right:4px;">
                    {{ $cr->name }}
                  </span>
                @endforeach
              </td>
              <td class="text-end">
                <div class="d-inline-flex gap-2">
                  <button type="button" class="btn-outline-t" style="padding:4px 10px;font-size:0.95rem;" title="Update Student Info" 
                          onclick="openUpdateModal({{ $st->id }}, '{{ addslashes($st->first_name ?? '') }}', '{{ addslashes($st->middle_name ?? '') }}', '{{ addslashes($st->last_name ?? '') }}', '{{ addslashes($st->username ?? '') }}', '{{ $st->student_id_number }}', {{ $st->grade_level ?? 1 }})">
                    <i class="bi bi-pencil-fill text-info"></i> Update
                  </button>
                  <form method="POST" action="{{ route('teacher.reset-student-password', $st->id) }}" onsubmit="event.preventDefault(); confirmAction('Reset password for {{ $st->name }} back to their ID number?', 'Reset Password').then(result => { if(result) this.submit(); });">
                    @csrf
                    <button type="submit" class="btn-outline-t" style="padding:4px 10px;font-size:0.95rem;" title="Reset Password to ID Number">
                      <i class="bi bi-key-fill text-warning"></i> Reset PW
                    </button>
                  </form>
                  <form method="POST" action="{{ route('teacher.delete-student', $st->id) }}" onsubmit="event.preventDefault(); confirmAction('Delete student account for {{ $st->name }}?', 'Delete Student').then(result => { if(result) this.submit(); });">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-outline-t" style="padding:4px 10px;font-size:0.95rem;border-color:rgba(239,68,68,.3);color:#fca5a5;" title="Delete Student">
                      <i class="bi bi-trash-fill"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center py-4 t-muted">
                <i class="bi bi-person-slash d-block mb-2" style="font-size:2rem;"></i>
                No student accounts created yet. Click "New Student" above to create accounts.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    </div>{{-- end center container --}}

  </div>
</div>

{{-- Update Student Modal --}}
<div class="modal fade" id="updateStudentModal" tabindex="-1" aria-labelledby="updateStudentModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border:2px solid var(--card-border);border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
      <div class="modal-header" style="border-bottom:1px solid var(--card-border2);">
        <h5 class="modal-title t-text fw-bold" id="updateStudentModalLabel">
          <i class="bi bi-pencil-square me-2" style="color:#3b82f6;"></i>Update Student Information
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter:invert(1);"></button>
      </div>
      <form method="POST" id="updateStudentForm" action="">
        @csrf
        @method('PUT')
        <div class="modal-body" style="padding:24px;">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="t-label">First Name</label>
              <input type="text" name="first_name" id="update_first_name" required class="t-input">
            </div>
            <div class="col-md-3">
              <label class="t-label">Middle Name</label>
              <input type="text" name="middle_name" id="update_middle_name" class="t-input">
            </div>
            <div class="col-md-3">
              <label class="t-label">Last Name</label>
              <input type="text" name="last_name" id="update_last_name" required class="t-input">
            </div>
            <div class="col-md-3">
              <label class="t-label">Username</label>
              <input type="text" name="username" id="update_username" required class="t-input">
            </div>
            <div class="col-md-6">
              <label class="t-label">Student ID Number</label>
              <input type="text" name="student_id_number" id="update_student_id_number" required class="t-input">
              <small class="t-muted" style="font-size:0.85rem;">This is also used as the student's password</small>
            </div>
            <div class="col-md-6">
              <label class="t-label">Grade Level</label>
              <select name="grade_level" id="update_grade_level" class="t-input" required>
                @for($g=1;$g<=6;$g++)
                  <option value="{{ $g }}">Grade {{ $g }}</option>
                @endfor
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--card-border2);padding:16px 24px;">
          <button type="button" class="btn-outline-t" data-bs-dismiss="modal" style="padding:8px 16px;">
            <i class="bi bi-x-lg me-1"></i>Cancel
          </button>
          <button type="submit" class="btn-primary-t" style="padding:8px 20px;">
            <i class="bi bi-check-lg me-1"></i>Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Simple client-side search
document.getElementById('studentSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.student-row');
    
    rows.forEach(row => {
        const name = row.querySelector('.student-name').textContent.toLowerCase();
        if (name.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Open update modal and populate with student data
function openUpdateModal(studentId, firstName, middleName, lastName, username, studentIdNumber, gradeLevel) {
    // Set the form action URL
    const form = document.getElementById('updateStudentForm');
    form.action = '{{ route("teacher.update-student", ":id") }}'.replace(':id', studentId);
    
    // Populate the form fields
    document.getElementById('update_first_name').value = firstName || '';
    document.getElementById('update_middle_name').value = middleName || '';
    document.getElementById('update_last_name').value = lastName || '';
    document.getElementById('update_username').value = username || '';
    document.getElementById('update_student_id_number').value = studentIdNumber || '';
    document.getElementById('update_grade_level').value = gradeLevel || 1;
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('updateStudentModal'));
    modal.show();
}
</script>

@endsection
