@extends('layouts.app')
@section('content')
<style>
/* ============================================================
   Teacher Profile — design tokens matching dashboard
   ============================================================ */
:root {
  --nav-h: 58px;

  --bg:        #0B0D13;
  --surface:   #11141C;
  --surface-2: #161A24;
  --surface-3: #1C2130;
  --line:      #232938;
  --line-soft: #1A1F2B;

  --ink:       #E7EAF0;
  --ink-2:     #99A2B4;
  --ink-3:     #626C80;

  --accent:    #5B7CFA;
  --accent-in: #ffffff;
  --accent-wash: rgba(91,124,250,.12);

  --good: #2BB99B;
  --warn: #D8A33C;
  --err:  #E0616E;

  --r: 10px;
}
[data-bs-theme="light"] {
  --bg:        #F6F7F9;
  --surface:   #FFFFFF;
  --surface-2: #FFFFFF;
  --surface-3: #F2F4F7;
  --line:      #E3E6EC;
  --line-soft: #EDEFF3;

  --ink:       #12151D;
  --ink-2:     #5A6577;
  --ink-3:     #8A93A4;

  --accent:    #3D5BDB;
  --accent-wash: rgba(61,91,219,.08);

  --good: #12917A;
  --warn: #B07D12;
  --err:  #C93F4E;
}

.profile-page {
  background: var(--bg);
  min-height: calc(100vh - var(--nav-h));
  padding-bottom: 72px;
  color: var(--ink);
  font-size: 14px;
  line-height: 1.5;
  -webkit-font-smoothing: antialiased;
}

/* Header */
.profile-header {
  background: var(--surface);
  border-bottom: 1px solid var(--line);
  padding: 24px 0;
}
.profile-header h1 {
  font-size: 20px;
  font-weight: 650;
  margin: 0 0 4px;
  letter-spacing: -.015em;
}
.profile-header p {
  color: var(--ink-2);
  margin: 0;
  font-size: 13px;
}

/* Profile Preview */
.profile-preview {
  display: flex;
  align-items: center;
  gap: 20px;
  background: var(--surface-2);
  border: 1px solid var(--line);
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 24px;
}
.profile-avatar {
  position: relative;
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: var(--surface-3);
  border: 2px solid var(--line);
  overflow: hidden;
  flex-shrink: 0;
}
.profile-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.profile-avatar-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  color: var(--ink-3);
}
.profile-info {
  flex: 1;
  min-width: 0;
}
.profile-display-name {
  font-size: 18px;
  font-weight: 650;
  color: var(--ink);
  margin: 0 0 4px;
  letter-spacing: -.015em;
}
.profile-bio {
  font-size: 14px;
  color: var(--ink-2);
  margin: 0;
  font-style: italic;
}
.profile-bio-empty {
  color: var(--ink-3);
  font-style: normal;
}

/* Cards */
.profile-card {
  background: var(--surface);
  border: 1px solid var(--line);
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 20px;
}
.profile-card-title {
  font-size: 15px;
  font-weight: 650;
  color: var(--ink);
  margin: 0 0 16px;
  letter-spacing: -.01em;
}

/* Form Elements */
.form-group {
  margin-bottom: 16px;
}
.form-label {
  display: block;
  font-size: 12.5px;
  font-weight: 600;
  color: var(--ink-2);
  margin-bottom: 6px;
  text-transform: uppercase;
  letter-spacing: .02em;
}
.form-label .required {
  color: var(--err);
  margin-left: 2px;
}
.form-input {
  width: 100%;
  appearance: none;
  background: var(--surface-2);
  border: 1px solid var(--line);
  color: var(--ink);
  border-radius: 8px;
  padding: 10px 12px;
  font-size: 14px;
  font-weight: 500;
  outline: none;
  transition: border-color .12s, box-shadow .12s;
  font-family: inherit;
}
.form-input:hover {
  border-color: var(--ink-3);
}
.form-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px var(--accent-wash);
}
.form-input::placeholder {
  color: var(--ink-3);
}
.form-hint {
  display: block;
  font-size: 12px;
  color: var(--ink-3);
  margin-top: 4px;
}
.form-error {
  display: block;
  font-size: 12px;
  color: var(--err);
  margin-top: 4px;
  font-weight: 500;
}
textarea.form-input {
  resize: vertical;
  min-height: 80px;
  font-family: inherit;
}

/* Upload Button */
.upload-btn {
  position: relative;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 16px;
  background: var(--surface-2);
  border: 1px solid var(--line);
  border-radius: 8px;
  color: var(--ink);
  font-size: 13px;
  font-weight: 550;
  cursor: pointer;
  transition: all .15s;
}
.upload-btn:hover {
  border-color: var(--accent);
  background: var(--accent-wash);
}
.upload-btn input[type="file"] {
  position: absolute;
  opacity: 0;
  pointer-events: none;
}

/* Read-only Section */
.readonly-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 16px;
}
.readonly-item {
  background: var(--surface-2);
  border: 1px solid var(--line-soft);
  border-radius: 8px;
  padding: 12px;
}
.readonly-label {
  font-size: 11px;
  font-weight: 600;
  color: var(--ink-3);
  text-transform: uppercase;
  letter-spacing: .03em;
  margin-bottom: 4px;
}
.readonly-value {
  font-size: 14px;
  color: var(--ink);
  font-weight: 500;
}
.readonly-value.email {
  word-break: break-all;
}

/* Status Badge */
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 10px;
  border-radius: 99px;
  font-size: 12px;
  font-weight: 600;
}
.status-badge.approved {
  background: rgba(43,185,155,.15);
  color: var(--good);
}
.status-badge.pending {
  background: rgba(216,163,60,.15);
  color: var(--warn);
}
.status-badge .dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: currentColor;
}

/* Faculty ID Thumbnail */
.faculty-id-thumb {
  width: 100%;
  max-width: 200px;
  border-radius: 8px;
  border: 1px solid var(--line);
  overflow: hidden;
}
.faculty-id-thumb img {
  width: 100%;
  height: auto;
  display: block;
}

/* Buttons */
.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  background: var(--accent);
  color: var(--accent-in);
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all .15s;
}
.btn-primary:hover {
  opacity: .9;
  transform: translateY(-1px);
}
.btn-primary:active {
  transform: translateY(0);
}
.btn-primary:disabled {
  opacity: .5;
  cursor: not-allowed;
  transform: none;
}

/* Alert */
.alert {
  padding: 12px 16px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 500;
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.alert-success {
  background: rgba(43,185,155,.15);
  color: var(--good);
  border: 1px solid rgba(43,185,155,.3);
}
.alert-error {
  background: rgba(224,97,110,.15);
  color: var(--err);
  border: 1px solid rgba(224,97,110,.3);
}

/* Responsive */
@media (max-width: 768px) {
  .profile-preview {
    flex-direction: column;
    text-align: center;
  }
  .readonly-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<div class="profile-page">
  <div class="profile-header">
    <div class="container">
      <h1>Teacher Profile</h1>
      <p>Manage your profile information and preferences</p>
    </div>
  </div>

  <div class="container" style="max-width: 900px; margin-top: 32px;">
    
    <!-- Alert Messages -->
    <div id="alertContainer"></div>

    <!-- Profile Preview -->
    <div class="profile-preview">
      <div class="profile-avatar" id="previewAvatar">
        @if($teacher->avatar_url)
          <img src="{{ Storage::url($teacher->avatar_url) }}" alt="Profile Picture">
        @else
          <div class="profile-avatar-placeholder">
            <i class="bi bi-person-circle"></i>
          </div>
        @endif
      </div>
      <div class="profile-info">
        <h2 class="profile-display-name" id="previewDisplayName">
          {{ $teacher->display_name ?: $teacher->name }}
        </h2>
        @if($teacher->pronouns)
          <p style="font-size: 13px; color: var(--ink-2); margin: 0 0 4px;">{{ $teacher->pronouns }}</p>
        @endif
        <p class="profile-bio {{ $teacher->bio ? '' : 'profile-bio-empty' }}" id="previewBio">
          {{ $teacher->bio ?: 'No bio yet' }}
        </p>
      </div>
    </div>

    <!-- Editable Section -->
    <div class="profile-card">
      <h3 class="profile-card-title">Profile Information</h3>
      
      <form id="profileForm">
        @csrf
        
        <div class="form-group">
          <label class="form-label">
            Display Name <span class="required">*</span>
          </label>
          <input 
            type="text" 
            name="display_name" 
            id="displayNameInput"
            class="form-input" 
            value="{{ old('display_name', $teacher->display_name) }}"
            placeholder="Enter your display name"
            required
            minlength="1"
            maxlength="40"
          >
          <span class="form-hint">1-40 characters. This name will be visible to students.</span>
          <span class="form-error" id="displayNameError"></span>
        </div>

        <div class="form-group">
          <label class="form-label">Pronouns</label>
          <input 
            type="text" 
            name="pronouns" 
            id="pronounsInput"
            class="form-input" 
            value="{{ old('pronouns', $teacher->pronouns) }}"
            placeholder="e.g., she/her, he/him, they/them"
            maxlength="30"
          >
          <span class="form-hint">Optional, max 30 characters</span>
          <span class="form-error" id="pronounsError"></span>
        </div>

        <div class="form-group">
          <label class="form-label">Bio</label>
          <textarea 
            name="bio" 
            id="bioInput"
            class="form-input" 
            placeholder="Tell students a bit about yourself..."
            maxlength="200"
          >{{ old('bio', $teacher->bio) }}</textarea>
          <span class="form-hint">Optional, max 200 characters</span>
          <span class="form-error" id="bioError"></span>
        </div>

        <button type="submit" class="btn-primary" id="saveBtn">
          <i class="bi bi-check-lg"></i>
          Save Changes
        </button>
      </form>
    </div>

    <!-- Profile Picture Upload Section -->
    <div class="profile-card">
      <h3 class="profile-card-title">Profile Picture</h3>
      
      <div style="background: rgba(91,124,250,.1); border: 1px solid rgba(91,124,250,.25); border-radius: 8px; padding: 12px 16px; margin-bottom: 16px;">
        <p style="color: var(--ink-2); font-size: 13px; margin: 0;">
          <i class="bi bi-info-circle" style="margin-right: 6px; color: var(--accent);"></i>
          <strong>Please choose a decent, professional photo.</strong> This picture will be visible to students and should represent you appropriately as an educator.
        </p>
      </div>

      <div>
        <label class="upload-btn">
          <i class="bi bi-cloud-upload"></i>
          Upload Profile Picture
          <input 
            type="file" 
            id="avatarUpload" 
            accept="image/jpeg,image/jpg,image/png"
            onchange="uploadAvatar(this)"
          >
        </label>
        <span class="form-hint" style="display: block; margin-top: 8px;">
          JPG or PNG format, maximum 1MB file size
        </span>
        <span class="form-error" id="avatarError"></span>
      </div>
    </div>

    <!-- Read-only Section -->
    <div class="profile-card">
      <h3 class="profile-card-title">Account Information (Read-only)</h3>
      
      <div class="readonly-grid">
        <div class="readonly-item">
          <div class="readonly-label">Login Email</div>
          <div class="readonly-value email">{{ $teacher->email }}</div>
        </div>

        <div class="readonly-item">
          <div class="readonly-label">Account Status</div>
          <div class="readonly-value">
            <span class="status-badge {{ $teacher->is_approved ? 'approved' : 'pending' }}">
              <span class="dot"></span>
              {{ $teacher->is_approved ? 'Approved' : 'Pending' }}
            </span>
          </div>
        </div>

        <div class="readonly-item">
          <div class="readonly-label">Approved On</div>
          <div class="readonly-value">
            {{ $teacher->approved_at ? $teacher->approved_at->format('F j, Y') : 'Not yet' }}
          </div>
        </div>

        <div class="readonly-item">
          <div class="readonly-label">Account Created</div>
          <div class="readonly-value">{{ $teacher->created_at->format('F j, Y') }}</div>
        </div>
      </div>

      @if($teacher->faculty_id_path)
        <div style="margin-top: 20px;">
          <div class="readonly-label" style="margin-bottom: 8px;">Faculty ID (For Verification)</div>
          <div class="faculty-id-thumb">
            <img src="{{ Storage::url($teacher->faculty_id_path) }}" alt="Faculty ID">
          </div>
        </div>
      @endif
    </div>

  </div>
</div>

<script>
// Real-time preview updates
const displayNameInput = document.getElementById('displayNameInput');
const pronounsInput = document.getElementById('pronounsInput');
const bioInput = document.getElementById('bioInput');
const previewDisplayName = document.getElementById('previewDisplayName');
const previewBio = document.getElementById('previewBio');

displayNameInput?.addEventListener('input', (e) => {
  previewDisplayName.textContent = e.target.value || '{{ $teacher->name }}';
});

bioInput?.addEventListener('input', (e) => {
  const value = e.target.value;
  previewBio.textContent = value || 'No bio yet';
  previewBio.className = value ? 'profile-bio' : 'profile-bio profile-bio-empty';
});

// Profile form submission
document.getElementById('profileForm')?.addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const saveBtn = document.getElementById('saveBtn');
  saveBtn.disabled = true;
  saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
  
  // Clear previous errors
  document.querySelectorAll('.form-error').forEach(el => el.textContent = '');
  
  const formData = new FormData(e.target);
  // Convert to object and remove _token (it's sent in header instead)
  const data = {};
  for (const [key, value] of formData.entries()) {
    if (key !== '_token') {
      data[key] = value;
    }
  }
  
  try {
    const response = await fetch('{{ route("teacher.profile.update") }}', {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
      },
      body: JSON.stringify(data),
    });
    
    const result = await response.json();
    
    if (response.ok) {
      showAlert('success', result.message || 'Profile updated successfully!');
      // Update preview if pronouns changed
      if (data.pronouns) {
        const pronounsEl = document.querySelector('.profile-info p:first-of-type');
        if (pronounsEl) {
          pronounsEl.textContent = data.pronouns;
        } else {
          const bioEl = document.getElementById('previewBio');
          bioEl.insertAdjacentHTML('beforebegin', `<p style="font-size: 13px; color: var(--ink-2); margin: 0 0 4px;">${data.pronouns}</p>`);
        }
      }
    } else {
      if (response.status === 403) {
        showAlert('error', result.error || 'You are not authorized to perform this action');
      } else if (result.errors) {
        // Validation errors
        Object.keys(result.errors).forEach(field => {
          const errorEl = document.getElementById(field + 'Error');
          if (errorEl) {
            errorEl.textContent = result.errors[field][0];
          }
        });
        showAlert('error', 'Please fix the errors below');
      } else {
        showAlert('error', result.message || 'Failed to update profile');
      }
    }
  } catch (error) {
    console.error('Error:', error);
    showAlert('error', 'An error occurred. Please try again.');
  } finally {
    saveBtn.disabled = false;
    saveBtn.innerHTML = '<i class="bi bi-check-lg"></i> Save Changes';
  }
});

// Profile Picture upload
async function uploadAvatar(input) {
  const file = input.files[0];
  if (!file) return;
  
  // Validate file
  const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
  if (!validTypes.includes(file.type)) {
    showAlert('error', 'Invalid file type. Only JPG and PNG are allowed.');
    input.value = '';
    return;
  }
  
  if (file.size > 1024 * 1024) {
    showAlert('error', 'File too large. Maximum size is 1MB.');
    input.value = '';
    return;
  }
  
  const formData = new FormData();
  formData.append('avatar', file);
  formData.append('_token', document.querySelector('input[name="_token"]').value);
  
  try {
    const response = await fetch('{{ route("teacher.profile.avatar.upload") }}', {
      method: 'POST',
      body: formData,
    });
    
    const result = await response.json();
    
    if (response.ok) {
      // Update preview
      const previewAvatar = document.getElementById('previewAvatar');
      previewAvatar.innerHTML = `<img src="${result.data.avatar_full_url}" alt="Profile Picture">`;
      
      showAlert('success', 'Profile picture uploaded successfully!');
    } else {
      showAlert('error', result.errors?.avatar?.[0] || result.error || 'Failed to upload profile picture');
    }
  } catch (error) {
    console.error('Error:', error);
    showAlert('error', 'An error occurred. Please try again.');
  } finally {
    input.value = '';
  }
}

// Alert helper
function showAlert(type, message) {
  const container = document.getElementById('alertContainer');
  const alert = document.createElement('div');
  alert.className = `alert alert-${type}`;
  alert.innerHTML = `
    <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill'}"></i>
    ${message}
  `;
  container.innerHTML = '';
  container.appendChild(alert);
  
  setTimeout(() => {
    alert.style.opacity = '0';
    alert.style.transition = 'opacity 0.3s';
    setTimeout(() => alert.remove(), 300);
  }, 5000);
  
  // Scroll to top to show alert
  window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

@endsection
