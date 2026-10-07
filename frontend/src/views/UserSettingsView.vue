<template>
  <div class="settings-container">
    <!-- Header Banner -->
    <div class="settings-header">
      <div class="flex items-center gap-3">
        <div class="header-icon-box">
          <span class="material-symbols-outlined text-2xl">manage_accounts</span>
        </div>
        <div>
          <h1 class="settings-title">Account Settings</h1>
          <p class="settings-subtitle">Manage your profile details and security credentials.</p>
        </div>
      </div>
    </div>

    <div class="settings-content mt-6">
      <!-- Profile Information -->
      <div class="settings-card">
        <div class="card-header">
          <div class="card-icon-pill icon-purple">
            <span class="material-symbols-outlined text-lg">badge</span>
          </div>
          <h2 class="card-title">Profile Information</h2>
        </div>
        
        <!-- Name Form -->
        <form @submit.prevent="updateName" class="form-group mb-8 pb-8 form-divider">
          <div class="input-wrapper mb-2">
            <label class="input-label">Current Name</label>
            <div class="readonly-field">
              <span class="font-medium">{{ user.full_name || 'N/A' }}</span>
              <span class="material-symbols-outlined text-slate-400 text-sm">lock</span>
            </div>
          </div>
          
          <div class="input-wrapper">
            <label class="input-label">Display Name</label>
            <input 
              type="text" 
              v-model="nameForm.full_name" 
              class="custom-input" 
              required
              placeholder="Enter your new name"
            />
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn-primary" :disabled="isUpdatingName">
              <span v-if="isUpdatingName" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isUpdatingName ? 'Updating...' : 'Update Name' }}
            </button>
          </div>
          <div v-if="nameSuccess" class="success-msg">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <span>{{ nameSuccess }}</span>
          </div>
          <div v-if="nameError" class="error-msg">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ nameError }}</span>
          </div>
        </form>

        <!-- Email Form -->
        <form @submit.prevent="updateEmail" class="form-group">
          <div class="input-wrapper mb-2">
            <label class="input-label">Current Email</label>
            <div class="readonly-field">
              <span class="font-medium">{{ user.email || 'Loading...' }}</span>
              <span class="material-symbols-outlined text-slate-400 text-sm">lock</span>
            </div>
          </div>
          
          <div class="input-wrapper">
            <label class="input-label">Email Address</label>
            <input 
              type="email" 
              v-model="emailForm.email" 
              class="custom-input" 
              required
              placeholder="Enter your new email"
            />
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn-primary" :disabled="isUpdatingEmail">
              <span v-if="isUpdatingEmail" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isUpdatingEmail ? 'Updating...' : 'Update Email' }}
            </button>
          </div>
          <div v-if="emailSuccess" class="success-msg">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <span>{{ emailSuccess }}</span>
          </div>
          <div v-if="emailError" class="error-msg">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ emailError }}</span>
          </div>
        </form>
      </div>

      <!-- Security -->
      <div class="settings-card">
        <div class="card-header">
          <div class="card-icon-pill icon-pink">
            <span class="material-symbols-outlined text-lg">lock</span>
          </div>
          <h2 class="card-title">Security & Password</h2>
        </div>
        
        <form @submit.prevent="updatePassword" class="form-group">
          <div class="input-wrapper">
            <label class="input-label">Current Password</label>
            <div class="relative">
              <input 
                :type="showCurrentPassword ? 'text' : 'password'" 
                v-model="passwordForm.currentPassword" 
                class="custom-input pr-10 w-full" 
                required
                placeholder="Enter current password"
              />
              <button 
                type="button" 
                @click="showCurrentPassword = !showCurrentPassword" 
                class="password-toggle-btn"
                tabindex="-1"
                aria-label="Toggle password visibility"
              >
                <span class="material-symbols-outlined text-lg">{{ showCurrentPassword ? 'visibility_off' : 'visibility' }}</span>
              </button>
            </div>
          </div>

          <div class="input-wrapper">
            <label class="input-label">New Password</label>
            <div class="relative">
              <input 
                :type="showNewPassword ? 'text' : 'password'" 
                v-model="passwordForm.newPassword" 
                class="custom-input pr-10 w-full" 
                required
                placeholder="Enter new password"
              />
              <button 
                type="button" 
                @click="showNewPassword = !showNewPassword" 
                class="password-toggle-btn"
                tabindex="-1"
                aria-label="Toggle password visibility"
              >
                <span class="material-symbols-outlined text-lg">{{ showNewPassword ? 'visibility_off' : 'visibility' }}</span>
              </button>
            </div>
          </div>

          <div class="input-wrapper">
            <label class="input-label">Confirm New Password</label>
            <div class="relative">
              <input 
                :type="showConfirmPassword ? 'text' : 'password'" 
                v-model="passwordForm.confirmPassword" 
                class="custom-input pr-10 w-full" 
                required
                placeholder="Confirm new password"
              />
              <button 
                type="button" 
                @click="showConfirmPassword = !showConfirmPassword" 
                class="password-toggle-btn"
                tabindex="-1"
                aria-label="Toggle password visibility"
              >
                <span class="material-symbols-outlined text-lg">{{ showConfirmPassword ? 'visibility_off' : 'visibility' }}</span>
              </button>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn-primary" :disabled="isUpdatingPassword">
              <span v-if="isUpdatingPassword" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isUpdatingPassword ? 'Updating...' : 'Update Password' }}
            </button>
          </div>
          <div v-if="passwordSuccess" class="success-msg">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <span>{{ passwordSuccess }}</span>
          </div>
          <div v-if="passwordError" class="error-msg">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ passwordError }}</span>
          </div>
        </form>
      </div>

      <!-- Data Retention (Admin / Staff Only) -->
      <div v-if="isAdminOrStaff" class="settings-card">
        <div class="card-header">
          <div class="card-icon-pill icon-blue">
            <span class="material-symbols-outlined text-lg">auto_delete</span>
          </div>
          <h2 class="card-title">Data Retention Policies</h2>
        </div>
        
        <p class="section-description mb-4">
          Configure how long deleted items and historical data are kept before being permanently purged. Set to 0 to disable automated deletion.
        </p>
        
        <form @submit.prevent="updateRetentionSettings" class="form-group">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="input-wrapper">
              <label class="input-label">Trashbin TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.trash_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="field-helper">All data in trashbin</span>
            </div>
            
            <div class="input-wrapper">
              <label class="input-label">Messages TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.messages_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="field-helper">Auto-move to trash</span>
            </div>
            
            <div class="input-wrapper">
              <label class="input-label">Main Logs TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.activity_logs_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="field-helper">System activity logs</span>
            </div>

            <div class="input-wrapper">
              <label class="input-label">Operational Logs TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.operational_logs_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="field-helper">Logins, logouts, user management</span>
            </div>

            <div class="input-wrapper">
              <label class="input-label">Archived Documents TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.archived_documents_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="field-helper">Applies to Activity Designs (Accomplishment reports are permanent)</span>
            </div>

            <div class="input-wrapper">
              <label class="input-label">Drafts TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.drafts_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="field-helper">Pending, revision, disapproved documents</span>
            </div>
          </div>
          
          <div class="form-actions mt-4">
            <button type="submit" class="btn-primary" :disabled="isUpdatingRetention">
              <span v-if="isUpdatingRetention" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isUpdatingRetention ? 'Saving...' : 'Save Retention Policies' }}
            </button>
          </div>
          <div v-if="retentionSuccess" class="success-msg">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <span>{{ retentionSuccess }}</span>
          </div>
          <div v-if="retentionError" class="error-msg">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ retentionError }}</span>
          </div>
        </form>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '../api';
import Swal from 'sweetalert2';

const user = ref({});

// Check if user is admin or staff to show system settings
const isAdminOrStaff = computed(() => {
  const role = user.value.role ? user.value.role.toLowerCase() : '';
  return role === 'admin' || role === 'gad_staff' || role === 'superadmin' || role === 'director';
});

const nameForm = ref({ full_name: '' });
const isUpdatingName = ref(false);
const nameSuccess = ref('');
const nameError = ref('');

const emailForm = ref({ email: '' });
const isUpdatingEmail = ref(false);
const emailSuccess = ref('');
const emailError = ref('');

const passwordForm = ref({
  currentPassword: '',
  newPassword: '',
  confirmPassword: ''
});
const isUpdatingPassword = ref(false);
const passwordSuccess = ref('');
const passwordError = ref('');

// Password visibility toggles
const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const retentionForm = ref({
  trash_ttl_days: 30,
  messages_ttl_days: 365,
  activity_logs_ttl_days: 365,
  operational_logs_ttl_days: 90,
  archived_documents_ttl_days: 1825,
  drafts_ttl_days: 365
});
const isUpdatingRetention = ref(false);
const retentionSuccess = ref('');
const retentionError = ref('');

// Helper to provide Swal theme matching current dark/light mode
const getSwalTheme = () => {
  const isDark = typeof document !== 'undefined' && document.documentElement.classList.contains('dark');
  return {
    background: isDark ? '#1e1b4b' : '#ffffff',
    color: isDark ? '#f8fafc' : '#0f172a'
  };
};

onMounted(async () => {
  const storedUser = JSON.parse(localStorage.getItem('user') || '{}');
  user.value = storedUser;
  
  try {
    const res = await api.get('/users/profile');
    if (res.data.success) {
      user.value.full_name = res.data.user.full_name;
      nameForm.value.full_name = res.data.user.full_name;
      user.value.email = res.data.user.email;
      emailForm.value.email = res.data.user.email;
      
      // Update local storage to have the email cached
      storedUser.email = res.data.user.email;
      localStorage.setItem('user', JSON.stringify(storedUser));
    }
  } catch (error) {
    console.error("Failed to fetch profile", error);
  }

  // Fetch system settings if admin
  if (isAdminOrStaff.value) {
    try {
      const res = await api.get('/settings/system');
      if (res.data) {
        retentionForm.value = {
          trash_ttl_days: res.data.trash_ttl_days ?? 30,
          messages_ttl_days: res.data.messages_ttl_days ?? 365,
          activity_logs_ttl_days: res.data.activity_logs_ttl_days ?? 365,
          operational_logs_ttl_days: res.data.operational_logs_ttl_days ?? 90,
          archived_documents_ttl_days: res.data.archived_documents_ttl_days ?? 1825,
          drafts_ttl_days: res.data.drafts_ttl_days ?? 365
        };
      }
    } catch (error) {
      console.error("Failed to fetch system settings", error);
    }
  }
});

const updateEmail = async () => {
  if (emailForm.value.email === user.value.email) {
    emailError.value = 'New email is the same as the current email.';
    return;
  }

  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "Do you want to update your email address?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#9333ea',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Yes, update it!',
    ...getSwalTheme()
  });

  if (!result.isConfirmed) return;

  isUpdatingEmail.value = true;
  emailSuccess.value = '';
  emailError.value = '';
  
  try {
    const res = await api.post('/users/profile/update', { email: emailForm.value.email });
    if (res.data.success) {
      emailSuccess.value = 'Email updated successfully.';
      // Update local storage and reactive state
      const storedUser = JSON.parse(localStorage.getItem('user') || '{}');
      storedUser.email = emailForm.value.email;
      localStorage.setItem('user', JSON.stringify(storedUser));
      user.value.email = emailForm.value.email;
    } else {
      emailError.value = res.data.message || 'Failed to update email.';
    }
  } catch (err) {
    emailError.value = err.response?.data?.message || 'An error occurred while updating email.';
  } finally {
    isUpdatingEmail.value = false;
  }
};

const updateName = async () => {
  if (nameForm.value.full_name === user.value.full_name) {
    nameError.value = 'New name is the same as the current name.';
    return;
  }

  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "Do you want to update your display name?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#9333ea',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Yes, update it!',
    ...getSwalTheme()
  });

  if (!result.isConfirmed) return;

  isUpdatingName.value = true;
  nameSuccess.value = '';
  nameError.value = '';
  
  try {
    const res = await api.post('/users/profile/update', { full_name: nameForm.value.full_name });
    if (res.data.success) {
      nameSuccess.value = 'Name updated successfully.';
      const storedUser = JSON.parse(localStorage.getItem('user') || '{}');
      storedUser.full_name = nameForm.value.full_name;
      localStorage.setItem('user', JSON.stringify(storedUser));
      user.value.full_name = nameForm.value.full_name;
    } else {
      nameError.value = res.data.message || 'Failed to update name.';
    }
  } catch (err) {
    nameError.value = err.response?.data?.message || 'An error occurred while updating name.';
  } finally {
    isUpdatingName.value = false;
  }
};

const updatePassword = async () => {
  if (passwordForm.value.newPassword !== passwordForm.value.confirmPassword) {
    passwordError.value = 'New passwords do not match.';
    return;
  }

  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "Do you want to update your password?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#9333ea',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Yes, update it!',
    ...getSwalTheme()
  });

  if (!result.isConfirmed) return;

  isUpdatingPassword.value = true;
  passwordSuccess.value = '';
  passwordError.value = '';
  
  try {
    const res = await api.post('/users/profile/update', { 
      current_password: passwordForm.value.currentPassword,
      new_password: passwordForm.value.newPassword 
    });
    if (res.data.success) {
      passwordSuccess.value = 'Password updated successfully.';
      passwordForm.value = { currentPassword: '', newPassword: '', confirmPassword: '' };
    } else {
      passwordError.value = res.data.message || 'Failed to update password.';
    }
  } catch (err) {
    passwordError.value = err.response?.data?.message || 'An error occurred while updating password.';
  } finally {
    isUpdatingPassword.value = false;
  }
};

const updateRetentionSettings = async () => {
  isUpdatingRetention.value = true;
  retentionSuccess.value = '';
  retentionError.value = '';
  
  try {
    const res = await api.post('/settings/system', retentionForm.value);
    if (res.status === 200 || res.status === 201 || (res.data && res.data.message)) {
      retentionSuccess.value = 'Data retention policies updated successfully.';
      
      // Trigger background cleanup
      try {
        api.post('/settings/trigger-cleanup').catch(e => console.log('Silent cleanup notice', e));
      } catch (e) {}
      
    } else {
      retentionError.value = 'Failed to update retention policies.';
    }
  } catch (err) {
    retentionError.value = err.response?.data?.message || 'An error occurred while updating policies.';
  } finally {
    isUpdatingRetention.value = false;
  }
};
</script>

<style scoped>
.settings-container {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-bottom: 2.5rem;
}

/* ==========================================================================
   Header Banner
   ========================================================================== */
.settings-header {
  width: 100%;
  max-width: 800px;
  background: linear-gradient(135deg, #ffffff 0%, #faf5ff 55%, #f3e8ff 100%);
  padding: 1.75rem 2rem;
  border-radius: 1.25rem;
  border: 1px solid #e9d5ff;
  box-shadow: 0 10px 25px -5px rgba(147, 51, 234, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.settings-header::before {
  content: '';
  position: absolute;
  top: -50px;
  right: -50px;
  width: 150px;
  height: 150px;
  background: rgba(147, 51, 234, 0.08);
  border-radius: 50%;
  filter: blur(25px);
  pointer-events: none;
}

.header-icon-box {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f3e8ff;
  color: #7e22ce;
  border: 1px solid #e9d5ff;
  flex-shrink: 0;
  transition: all 0.3s ease;
}

.settings-title {
  font-size: 1.75rem;
  font-weight: 800;
  color: #1e1b4b;
  margin: 0;
  letter-spacing: -0.02em;
  transition: color 0.3s ease;
}

.settings-subtitle {
  font-size: 0.95rem;
  color: #64748b;
  margin-top: 0.25rem;
  font-weight: 500;
  transition: color 0.3s ease;
}

/* ==========================================================================
   Content & Cards
   ========================================================================== */
.settings-content {
  width: 100%;
  max-width: 800px;
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
}

.settings-card {
  border-radius: 1.25rem;
  border: 1px solid #e2e8f0;
  background: #ffffff;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
  padding: 2rem;
  transition: all 0.3s ease;
}

.card-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
  border-bottom: 1px solid #f1f5f9;
  padding-bottom: 1rem;
  transition: border-color 0.3s ease;
}

.card-icon-pill {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.icon-purple {
  background: #f3e8ff;
  color: #7e22ce;
  border: 1px solid #e9d5ff;
}

.icon-pink {
  background: #fdf2f8;
  color: #db2777;
  border: 1px solid #fbcfe8;
}

.icon-blue {
  background: #eff6ff;
  color: #2563eb;
  border: 1px solid #dbeafe;
}

.card-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
  transition: color 0.3s ease;
}

.section-description {
  color: #64748b;
  font-size: 0.875rem;
  line-height: 1.5;
  transition: color 0.3s ease;
}

/* ==========================================================================
   Form Elements
   ========================================================================== */
.form-group {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.form-divider {
  border-color: #f1f5f9;
  transition: border-color 0.3s ease;
}

.input-wrapper {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}

.input-label {
  font-size: 0.8rem;
  font-weight: 700;
  color: #475569;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  transition: color 0.3s ease;
}

.readonly-field {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #f8fafc;
  color: #1e293b;
  border: 1px solid #e2e8f0;
  padding: 0.75rem 1rem;
  border-radius: 0.625rem;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.custom-input {
  width: 100%;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  color: #0f172a;
  padding: 0.75rem 1rem;
  border-radius: 0.625rem;
  font-size: 0.95rem;
  transition: all 0.2s ease;
  outline: none;
}

.custom-input::placeholder {
  color: #94a3b8;
}

.custom-input:focus {
  border-color: #9333ea;
  box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.12);
  background: #ffffff;
}

.password-toggle-btn {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  padding: 0.25rem;
  background: none;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s ease;
}

.password-toggle-btn:hover {
  color: #475569;
}

.field-helper {
  font-size: 0.75rem;
  color: #64748b;
  margin-top: 0.25rem;
  transition: color 0.3s ease;
}

.form-actions {
  margin-top: 0.5rem;
}

/* ==========================================================================
   Buttons
   ========================================================================== */
.btn-primary {
  background: linear-gradient(135deg, #9333ea, #7e22ce);
  color: white;
  font-weight: 600;
  font-size: 0.925rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0.625rem;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(147, 51, 234, 0.25);
  transition: all 0.2s ease;
}

.btn-primary:hover:not(:disabled) {
  background: linear-gradient(135deg, #a855f7, #9333ea);
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(147, 51, 234, 0.35);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

/* ==========================================================================
   Alert Messages
   ========================================================================== */
.success-msg {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: #dcfce7;
  color: #15803d;
  border: 1px solid #bbf7d0;
  padding: 0.625rem 0.875rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  margin-top: 0.5rem;
}

.error-msg {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: #fee2e2;
  color: #b91c1c;
  border: 1px solid #fecaca;
  padding: 0.625rem 0.875rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  margin-top: 0.5rem;
}

/* ==========================================================================
   Dark Mode Overrides
   ========================================================================== */
:global(.dark) .settings-header,
.dark .settings-header {
  background: linear-gradient(135deg, #2e1065, #1e1b4b);
  border-color: rgba(168, 85, 247, 0.25);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4), 0 8px 10px -6px rgba(168, 85, 247, 0.15);
}

:global(.dark) .settings-header::before,
.dark .settings-header::before {
  background: rgba(168, 85, 247, 0.15);
}

:global(.dark) .header-icon-box,
.dark .header-icon-box {
  background: rgba(147, 51, 234, 0.25);
  color: #d8b4fe;
  border-color: rgba(168, 85, 247, 0.35);
}

:global(.dark) .settings-title,
.dark .settings-title {
  color: #ffffff;
}

:global(.dark) .settings-subtitle,
.dark .settings-subtitle {
  color: #d8b4fe;
}

:global(.dark) .settings-card,
.dark .settings-card {
  background: linear-gradient(135deg, #0f172a, #020617);
  border-color: rgba(147, 51, 234, 0.2);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

:global(.dark) .card-header,
.dark .card-header {
  border-bottom-color: rgba(255, 255, 255, 0.08);
}

:global(.dark) .card-title,
.dark .card-title {
  color: #f8fafc;
}

:global(.dark) .icon-purple,
.dark .icon-purple {
  background: rgba(147, 51, 234, 0.25);
  color: #d8b4fe;
  border-color: rgba(168, 85, 247, 0.35);
}

:global(.dark) .icon-pink,
.dark .icon-pink {
  background: rgba(236, 72, 153, 0.25);
  color: #f472b6;
  border-color: rgba(236, 72, 153, 0.35);
}

:global(.dark) .icon-blue,
.dark .icon-blue {
  background: rgba(37, 99, 235, 0.25);
  color: #93c5fd;
  border-color: rgba(37, 99, 235, 0.35);
}

:global(.dark) .section-description,
.dark .section-description {
  color: #94a3b8;
}

:global(.dark) .form-divider,
.dark .form-divider {
  border-color: rgba(51, 65, 85, 0.6);
}

:global(.dark) .input-label,
.dark .input-label {
  color: #94a3b8;
}

:global(.dark) .readonly-field,
.dark .readonly-field {
  background: rgba(30, 41, 59, 0.5);
  border-color: rgba(51, 65, 85, 0.6);
  color: #f8fafc;
}

:global(.dark) .custom-input,
.dark .custom-input {
  background: rgba(15, 23, 42, 0.6);
  border-color: rgba(147, 51, 234, 0.25);
  color: #ffffff;
}

:global(.dark) .custom-input::placeholder,
.dark .custom-input::placeholder {
  color: #64748b;
}

:global(.dark) .custom-input:focus,
.dark .custom-input:focus {
  border-color: #c084fc;
  background: rgba(15, 23, 42, 0.85);
  box-shadow: 0 0 0 3px rgba(192, 132, 252, 0.2);
}

:global(.dark) .password-toggle-btn,
.dark .password-toggle-btn {
  color: #64748b;
}

:global(.dark) .password-toggle-btn:hover,
.dark .password-toggle-btn:hover {
  color: #cbd5e1;
}

:global(.dark) .field-helper,
.dark .field-helper {
  color: #94a3b8;
}

:global(.dark) .success-msg,
.dark .success-msg {
  background: rgba(34, 197, 94, 0.12);
  color: #4ade80;
  border-color: rgba(34, 197, 94, 0.25);
}

:global(.dark) .error-msg,
.dark .error-msg {
  background: rgba(239, 68, 68, 0.12);
  color: #f87171;
  border-color: rgba(239, 68, 68, 0.25);
}
</style>
