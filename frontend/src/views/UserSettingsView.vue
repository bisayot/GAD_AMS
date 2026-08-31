<template>
  <div class="settings-container">
    <div class="settings-header">
      <h1 class="text-3xl font-bold text-slate-900">Account Settings</h1>
      <p class="text-slate-500 mt-2">Manage your email address and update your password.</p>
    </div>

    <div class="settings-content mt-6">
      <!-- Profile Information -->
      <div class="settings-card">
        <div class="card-header">
          <span class="material-symbols-outlined text-purple-400">badge</span>
          <h2 class="card-title">Profile Information</h2>
        </div>
        
        <form @submit.prevent="updateName" class="form-group mb-8 pb-8 border-b border-slate-700/50">
          <div class="input-wrapper mb-2">
            <label class="input-label">Current Name</label>
            <div class="text-white font-medium bg-slate-800/50 p-3 rounded-lg border border-slate-700/50">{{ user.full_name || 'N/A' }}</div>
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
          <p v-if="nameSuccess" class="success-msg">{{ nameSuccess }}</p>
          <p v-if="nameError" class="error-msg">{{ nameError }}</p>
        </form>

        <form @submit.prevent="updateEmail" class="form-group">
          <div class="input-wrapper mb-2">
            <label class="input-label">Current Email</label>
            <div class="text-white font-medium bg-slate-800/50 p-3 rounded-lg border border-slate-700/50">{{ user.email || 'Loading...' }}</div>
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
          <p v-if="emailSuccess" class="success-msg">{{ emailSuccess }}</p>
          <p v-if="emailError" class="error-msg">{{ emailError }}</p>
        </form>
      </div>

      <!-- Security -->
      <div class="settings-card">
        <div class="card-header">
          <span class="material-symbols-outlined text-pink-400">lock</span>
          <h2 class="card-title">Security</h2>
        </div>
        
        <form @submit.prevent="updatePassword" class="form-group">
          <div class="input-wrapper">
            <label class="input-label">Current Password</label>
            <input 
              type="password" 
              v-model="passwordForm.currentPassword" 
              class="custom-input" 
              required
              placeholder="Enter current password"
            />
          </div>
          <div class="input-wrapper">
            <label class="input-label">New Password</label>
            <input 
              type="password" 
              v-model="passwordForm.newPassword" 
              class="custom-input" 
              required
              placeholder="Enter new password"
            />
          </div>
          <div class="input-wrapper">
            <label class="input-label">Confirm New Password</label>
            <input 
              type="password" 
              v-model="passwordForm.confirmPassword" 
              class="custom-input" 
              required
              placeholder="Confirm new password"
            />
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn-primary" :disabled="isUpdatingPassword">
              <span v-if="isUpdatingPassword" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isUpdatingPassword ? 'Updating...' : 'Update Password' }}
            </button>
          </div>
          <p v-if="passwordSuccess" class="success-msg">{{ passwordSuccess }}</p>
          <p v-if="passwordError" class="error-msg">{{ passwordError }}</p>
        </form>
      </div>
      <!-- Data Retention (Admin Only) -->
      <div v-if="isAdminOrStaff" class="settings-card">
        <div class="card-header">
          <span class="material-symbols-outlined text-blue-400">auto_delete</span>
          <h2 class="card-title">Data Retention Policies</h2>
        </div>
        
        <p class="text-slate-400 text-sm mb-4">
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
              <span class="text-xs text-slate-500">All data in trashbin</span>
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
              <span class="text-xs text-slate-500">Auto-move to trash</span>
            </div>
            
            <div class="input-wrapper">
              <label class="input-label">Activity Logs TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.activity_logs_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="text-xs text-slate-500">System activity logs</span>
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
              <span class="text-xs text-slate-500">Applies to Activity Designs (Accomplishment reports are permanent)</span>
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
              <span class="text-xs text-slate-500">Pending, revision, disapproved documents</span>
            </div>
          </div>
          
          <div class="form-actions mt-4">
            <button type="submit" class="btn-primary" :disabled="isUpdatingRetention">
              <span v-if="isUpdatingRetention" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isUpdatingRetention ? 'Saving...' : 'Save Retention Policies' }}
            </button>
          </div>
          <p v-if="retentionSuccess" class="success-msg">{{ retentionSuccess }}</p>
          <p v-if="retentionError" class="error-msg">{{ retentionError }}</p>
        </form>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '../api'; // Adjust path if necessary
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

const retentionForm = ref({
  trash_ttl_days: 30,
  messages_ttl_days: 365,
  activity_logs_ttl_days: 365,
  archived_documents_ttl_days: 1825,
  drafts_ttl_days: 365
});
const isUpdatingRetention = ref(false);
const retentionSuccess = ref('');
const retentionError = ref('');

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
    confirmButtonText: 'Yes, update it!'
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
    confirmButtonText: 'Yes, update it!'
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
    confirmButtonText: 'Yes, update it!'
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
      
      // Also trigger a manual cleanup run since they just updated the settings
      try {
        api.post('/settings/trigger-cleanup').catch(e => console.log('Silent background cleanup error', e));
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
  padding-bottom: 2rem;
}

.settings-header {
  width: 100%;
  max-width: 800px;
  background: white;
  padding: 2rem;
  border-radius: 1rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.settings-content {
  width: 100%;
  max-width: 800px;
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
}

.settings-card {
  border-radius: 1rem;
  border: 1px solid rgba(147, 51, 234, 0.15);
  background: linear-gradient(135deg, #0f172a, #020617);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  padding: 2rem;
}

.card-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  padding-bottom: 1rem;
}

.card-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #f8fafc;
  margin: 0;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.input-wrapper {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.input-label {
  font-size: 0.85rem;
  font-weight: 600;
  color: #cbd5e1;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.custom-input {
  background: rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(147, 51, 234, 0.2);
  color: white;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  font-size: 1rem;
  transition: all 0.2s ease;
  outline: none;
}

.custom-input:focus {
  border-color: #c084fc;
  box-shadow: 0 0 0 2px rgba(192, 132, 252, 0.2);
}

.form-actions {
  margin-top: 0.5rem;
}

.btn-primary {
  background: linear-gradient(135deg, #9333ea, #c084fc);
  color: white;
  font-weight: 600;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: opacity 0.2s ease, transform 0.1s ease;
}

.btn-primary:hover:not(:disabled) {
  opacity: 0.9;
  transform: translateY(-1px);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.success-msg {
  color: #34d399;
  font-size: 0.875rem;
  margin-top: 0.5rem;
}

.error-msg {
  color: #f87171;
  font-size: 0.875rem;
  margin-top: 0.5rem;
}
</style>
