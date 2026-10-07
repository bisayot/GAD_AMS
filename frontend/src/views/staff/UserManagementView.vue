<template>
  <main class="main-viewport">
    <div class="page-container">
      <div class="header-section mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="page-title">User Management</h1>
          <p class="page-subtitle">Manage system users, roles, and office assignments.</p>
        </div>
        <div class="flex items-center gap-3">
          <button @click="toggleSubmissionLimit()" class="btn-secondary flex items-center gap-2" :class="adSubmissionLimitEnabled ? '!bg-green-600 !border-green-700 !text-white hover:!bg-green-700' : '!bg-red-600 !border-red-700 !text-white hover:!bg-red-700'">
            <span class="material-symbols-outlined">{{ adSubmissionLimitEnabled ? 'toggle_on' : 'toggle_off' }}</span>
            Limit AD Submissions (Mon-Fri): {{ adSubmissionLimitEnabled ? 'ON' : 'OFF' }}
          </button>
          <button @click="openModal()" class="btn-primary flex items-center gap-2">
            <span class="material-symbols-outlined">person_add</span>
            Add User
          </button>
        </div>
      </div>

      <div class="layout-stacked">
        
        <!-- System Accounts (No card container, just horizontal scrolling cards) -->
        <div class="flex gap-4 overflow-x-auto custom-scrollbar pb-4" style="flex-wrap: nowrap;">
          <div v-for="user in systemUsers" :key="user.id" class="user-item flex-shrink-0 flex flex-col !bg-white dark:!bg-[#1e293b] !border-slate-200 dark:!border-slate-700" style="width: 280px; min-height: auto; padding: 1.25rem;">
            <div>
              <div class="user-name" style="font-size: 1rem;">{{ user.full_name || 'N/A' }}</div>
              <div class="font-bold text-purple-600 dark:text-purple-400 mt-1" style="font-size: 0.875rem;">{{ user.user_role }}</div>
              <div class="user-meta" style="font-size: 0.875rem; margin-top: 4px;">{{ user.email }}</div>
            </div>
            <div class="flex flex-col items-center justify-center gap-4 mt-6">
              <button @click="openModal(user)" class="transition-colors" title="Edit User">
                <span class="material-symbols-outlined !text-purple-600 dark:!text-purple-400 hover:!text-purple-700 dark:hover:!text-purple-300" style="font-size: 20px;">edit</span>
              </button>
              <button @click="suspendUser(user.id)" class="transition-colors" title="Suspend User">
                <span class="material-symbols-outlined !text-red-500 dark:!text-red-400 hover:!text-red-600 dark:hover:!text-red-300" style="font-size: 20px;">block</span>
              </button>
            </div>
          </div>
        </div>

        <!-- TWG Users Card -->
        <section class="user-card glass-card">
          <div class="card-header">
            <div class="flex items-center justify-between w-full flex-wrap gap-4">
              <div class="flex items-center gap-3">
                <div class="icon-box icon-box-purple">
                  <span class="material-symbols-outlined">group</span>
                </div>
                <h2 class="card-section-title">TWG Users <span class="badge ml-2 badge-purple">{{ twgUsers.length }}</span></h2>
              </div>
              <div class="flex gap-3 flex-1 md:flex-none justify-end">
                <div class="relative w-full md:w-64">
                  <input v-model="searchTwg" type="text" placeholder="Search..." class="search-input">
                </div>
                <select v-model="filterTwgOffice" class="filter-select">
                  <option value="">All Offices</option>
                  <option v-for="office in offices" :key="office.unit_id" :value="office.unit_name">{{ office.unit_name }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-body custom-scrollbar">
            <div v-if="twgUsers.length === 0" class="empty-state">No TWG users found.</div>
            <div v-else class="user-grid">
              <div v-for="user in twgUsers" :key="user.id" class="user-item">
                <div class="user-info">
                  <div class="user-name">{{ user.full_name || 'N/A' }}</div>
                  <div class="user-meta">{{ user.email }}</div>
                  <div class="user-office mt-1">{{ user.office_name || 'No Office' }}</div>
                  <div class="user-meta mt-2 flex flex-wrap items-center gap-2">
                    <span class="user-login flex items-center gap-1"><span class="material-symbols-outlined text-xs" style="font-size: 14px;">login</span> Last login: {{ formatLastLogin(user.last_login) }}</span>
                  </div>
                </div>
                <div class="user-actions mt-auto">
                  <button @click="openModal(user)" class="btn-edit" title="Edit User">
                    <span class="material-symbols-outlined text-sm">edit</span> Edit
                  </button>
                  <button @click="suspendUser(user.id)" class="btn-suspend" title="Suspend User">
                    <span class="material-symbols-outlined text-sm">block</span> Suspend
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Non-TWG Users Card -->
        <section class="user-card glass-card">
          <div class="card-header">
            <div class="flex items-center justify-between w-full flex-wrap gap-4">
              <div class="flex items-center gap-3">
                <div class="icon-box icon-box-purple">
                  <span class="material-symbols-outlined">person</span>
                </div>
                <h2 class="card-section-title">Non-TWG Users <span class="badge ml-2 badge-purple">{{ nonTwgUsers.length }}</span></h2>
              </div>
              <div class="flex gap-3 flex-1 md:flex-none justify-end">
                <div class="relative w-full md:w-64">
                  <input v-model="searchNonTwg" type="text" placeholder="Search..." class="search-input">
                </div>
                <select v-model="filterNonTwgOffice" class="filter-select">
                  <option value="">All Offices</option>
                  <option v-for="office in offices" :key="office.unit_id" :value="office.unit_name">{{ office.unit_name }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-body custom-scrollbar" style="display: flex; flex-direction: column; gap: 2rem;">
            <div v-if="nonTwgUsers.length === 0" class="empty-state">No Non-TWG users found.</div>
            <template v-else>
              <!-- Users With Submissions Section -->
              <div class="sub-section">
                <h3 class="sub-section-title text-purple mb-3 flex items-center gap-2">
                  <span class="material-symbols-outlined text-sm">assignment_turned_in</span> 
                  Users with submissions 
                  <span class="badge badge-purple text-xs">{{ nonTwgWithSubmissions.length }}</span>
                </h3>
                <div v-if="nonTwgWithSubmissions.length === 0" class="text-sm sub-section-empty italic mb-4">No users with submissions.</div>
                <div v-else class="user-grid">
                  <div v-for="user in nonTwgWithSubmissions" :key="user.id" class="user-item">
                    <div class="user-info">
                      <div class="user-name">{{ user.full_name || 'N/A' }}</div>
                      <div class="user-meta">{{ user.email }}</div>
                      <div class="user-office mt-1">{{ user.office_name || 'No Office' }}</div>
                      <div class="user-meta mt-2 flex flex-wrap items-center gap-3">
                        <span class="user-days flex items-center gap-1"><span class="material-symbols-outlined text-xs" style="font-size: 14px;">calendar_today</span> {{ daysOnSystem(user.created_at) }} days on system</span>
                        <span class="user-login flex items-center gap-1"><span class="material-symbols-outlined text-xs" style="font-size: 14px;">login</span> Last login: {{ formatLastLogin(user.last_login) }}</span>
                      </div>
                    </div>
                    <div class="user-actions mt-auto">
                      <button @click="openModal(user)" class="btn-edit" title="Edit User">
                        <span class="material-symbols-outlined text-sm">edit</span> Edit
                      </button>
                      <button @click="suspendUser(user.id)" class="btn-suspend" title="Suspend User">
                        <span class="material-symbols-outlined text-sm">block</span> Suspend
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Users Without Submissions Section -->
              <div class="sub-section">
                <h3 class="sub-section-title text-slate mb-3 flex items-center gap-2">
                  <span class="material-symbols-outlined text-sm">assignment_late</span> 
                  Users without submissions
                  <span class="badge badge-slate text-xs">{{ nonTwgWithoutSubmissions.length }}</span>
                </h3>
                <div v-if="nonTwgWithoutSubmissions.length === 0" class="text-sm sub-section-empty italic">No users without submissions.</div>
                <div v-else class="user-grid">
                  <div v-for="user in nonTwgWithoutSubmissions" :key="user.id" class="user-item">
                    <div class="user-info">
                      <div class="user-name">{{ user.full_name || 'N/A' }}</div>
                      <div class="user-meta">{{ user.email }}</div>
                      <div class="user-office mt-1">{{ user.office_name || 'No Office' }}</div>
                      <div class="user-meta mt-2 flex flex-wrap items-center gap-3">
                        <span class="user-days flex items-center gap-1"><span class="material-symbols-outlined text-xs" style="font-size: 14px;">calendar_today</span> {{ daysOnSystem(user.created_at) }} days on system</span>
                        <span class="user-login flex items-center gap-1"><span class="material-symbols-outlined text-xs" style="font-size: 14px;">login</span> Last login: {{ formatLastLogin(user.last_login) }}</span>
                      </div>
                    </div>
                    <div class="user-actions mt-auto">
                      <button @click="openModal(user)" class="btn-edit" title="Edit User">
                        <span class="material-symbols-outlined text-sm">edit</span> Edit
                      </button>
                      <button @click="suspendUser(user.id)" class="btn-suspend" title="Suspend User">
                        <span class="material-symbols-outlined text-sm">block</span> Suspend
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </section>

        <!-- Suspended Accounts Card -->
        <section class="user-card glass-card suspended-card">
          <div class="card-header">
            <div class="flex items-center justify-between w-full flex-wrap gap-4">
              <div class="flex items-center gap-3">
                <div class="icon-box icon-box-red">
                  <span class="material-symbols-outlined">no_accounts</span>
                </div>
                <h2 class="card-section-title">Suspended Accounts <span class="badge ml-2 badge-red">{{ suspendedUsers.length }}</span></h2>
              </div>
              <div class="flex gap-3 flex-1 md:flex-none justify-end">
                <div class="relative w-full md:w-64">
                  <input v-model="searchSuspended" type="text" placeholder="Search..." class="search-input search-input-red">
                </div>
                <select v-model="filterSuspendedOffice" class="filter-select filter-select-red">
                  <option value="">All Offices</option>
                  <option v-for="office in offices" :key="office.unit_id" :value="office.unit_name">{{ office.unit_name }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-body custom-scrollbar relative">

            <div v-if="suspendedUsers.length === 0" class="empty-state empty-state-red">No suspended users.</div>
            <div v-else class="user-grid">
              <div v-for="user in suspendedUsers" :key="user.id" class="user-item suspended-item">
                <div class="user-info">
                  <div class="user-name suspended-user-name">{{ user.full_name || 'N/A' }}</div>
                  <div class="user-meta">{{ user.email }}</div>
                  <div class="user-office suspended-office mt-1">{{ user.office_name || 'No Office' }}</div>
                  <div class="user-meta mt-2 flex flex-wrap items-center gap-2">
                    <span class="user-login flex items-center gap-1"><span class="material-symbols-outlined text-xs" style="font-size: 14px;">login</span> Last login: {{ formatLastLogin(user.last_login) }}</span>
                  </div>
                  <div class="suspended-date mt-2 text-xs italic">Suspended on: {{ formatDate(user.deleted_at) }}</div>
                </div>
                <div class="user-actions mt-auto flex gap-2">
                  <button @click="restoreUser(user.id)" class="btn-restore flex-1" title="Restore User">
                    <span class="material-symbols-outlined text-sm">restore</span> Restore
                  </button>
                  <button @click="deleteUser(user.id)" class="btn-delete flex-1" title="Delete Credentials">
                    <span class="material-symbols-outlined text-sm">delete_forever</span> Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>

      </div>

    </div>

    <!-- User Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal-container custom-scrollbar">
        <div class="modal-header p-6 flex justify-between items-center">
          <h2 class="modal-title">{{ isEdit ? 'Edit User' : 'Add New User' }}</h2>
          <button @click="closeModal" class="modal-close-btn">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        <div class="modal-body p-6">
          <form @submit.prevent="submitForm" class="flex flex-col gap-4">
            <div class="form-group">
              <label class="form-label">Full Name</label>
              <input type="text" v-model="form.full_name" required class="form-input" placeholder="Enter full name" />
            </div>
            
            <div class="form-group">
              <label class="form-label">Email</label>
              <input type="email" v-model="form.email" required class="form-input" placeholder="Enter email address" />
            </div>
            
            <div class="form-group">
              <label class="form-label">Password</label>
              <input type="password" v-model="form.password" :required="!isEdit" class="form-input" :placeholder="isEdit ? 'Leave blank to keep current' : 'Enter password'" minlength="6" />
            </div>
            
            <div class="form-group">
              <label class="form-label">Role</label>
              <select v-model="form.user_role" required class="form-input">
                <option value="Non-TWG">Non-TWG</option>
                <option value="TWG">TWG</option>
                <option value="Staff">Staff</option>
              </select>
            </div>
            
            <div class="form-group">
              <label class="form-label">Office Unit</label>
              <select v-model="form.office_id" required class="form-input">
                <option value="" disabled>Select Office</option>
                <option v-for="office in offices" :key="office.unit_id" :value="office.unit_id">
                  {{ office.unit_name }}
                </option>
              </select>
            </div>
            
            <div class="modal-actions mt-4 flex justify-end gap-3">
              <button type="button" @click="closeModal" class="btn-secondary">Cancel</button>
              <button type="submit" class="btn-primary" :disabled="isSubmitting">
                {{ isSubmitting ? 'Saving...' : (isEdit ? 'Update User' : 'Create User') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import api from '../../api';

const router = useRouter();
const users = ref([]);
const offices = ref([]);
const adSubmissionLimitEnabled = ref(true);

const toggleSubmissionLimit = async () => {
  try {
    const newVal = !adSubmissionLimitEnabled.value;
    await api.post('settings/system', { ad_submission_limit_enabled: newVal });
    adSubmissionLimitEnabled.value = newVal;
    Swal.fire({
      icon: 'success',
      title: 'Settings Updated',
      text: `AD Submission Mon-Fri Limit is now ${newVal ? 'ON' : 'OFF'}`,
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
  } catch (err) {
    console.error('Failed to update settings:', err);
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to update system settings' });
  }
};

const fetchSettings = async () => {
  try {
    const res = await api.get('settings/system');
    adSubmissionLimitEnabled.value = res.data.ad_submission_limit_enabled ?? true;
  } catch (err) {
    console.error('Failed to fetch system settings:', err);
  }
};

// Individual search/filter states
const searchTwg = ref('');
const filterTwgOffice = ref('');

const searchNonTwg = ref('');
const filterNonTwgOffice = ref('');

const searchSuspended = ref('');
const filterSuspendedOffice = ref('');

const showModal = ref(false);
const isEdit = ref(false);
const isSubmitting = ref(false);
const form = ref({
  id: null,
  full_name: '',
  email: '',
  password: '',
  user_role: 'Non-TWG',
  office_id: ''
});

const openModal = (user = null) => {
  if (user) {
    isEdit.value = true;
    form.value = {
      id: user.id,
      full_name: user.full_name || '',
      email: user.email || '',
      password: '',
      user_role: user.user_role || 'Non-TWG',
      office_id: user.office_id || ''
    };
  } else {
    isEdit.value = false;
    form.value = {
      id: null,
      full_name: '',
      email: '',
      password: '',
      user_role: 'Non-TWG',
      office_id: ''
    };
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const submitForm = async () => {
  isSubmitting.value = true;
  try {
    const url = isEdit.value ? `users/update/${form.value.id}` : `users/create`;
    const res = await api.post(url, form.value);
    
    if (res.data.success) {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: res.data.message,
        timer: 1500,
        showConfirmButton: false
      });
      closeModal();
      fetchUsers();
    } else {
      throw new Error(res.data.message || 'Operation failed');
    }
  } catch (err) {
    let msg = err.response?.data?.message || err.response?.data?.messages || err.message || 'An error occurred';
    if (typeof msg === 'object') {
      msg = Object.values(msg).join(', ');
    }
    Swal.fire({ icon: 'error', title: 'Error', text: msg });
  } finally {
    isSubmitting.value = false;
  }
};

const fetchOffices = async () => {
  try {
    const res = await api.get('office_units');
    offices.value = res.data;
  } catch (err) {
    console.error('Failed to fetch offices:', err);
  }
};

const fetchUsers = async () => {
  try {
    const res = await api.get('users');
    users.value = res.data;
  } catch (err) {
    console.error('Failed to fetch users:', err);
  }
};

const filterUserList = (userList, search, office) => {
  return userList.filter(user => {

    const matchesSearch = !search || 
      (user.full_name?.toLowerCase().includes(search.toLowerCase()) || 
       user.email?.toLowerCase().includes(search.toLowerCase()) ||
       user.user_role?.toLowerCase().includes(search.toLowerCase()));
       
    const matchesOffice = !office || user.office_name === office;

    return matchesSearch && matchesOffice;
  });
};

const twgUsers = computed(() => {
  const baseList = users.value.filter(u => !u.deleted_at && u.user_role === 'TWG' && u.role !== 'deleted');
  return filterUserList(baseList, searchTwg.value, filterTwgOffice.value);
});

const systemUsers = computed(() => {
  return users.value.filter(u => !u.deleted_at && u.role !== 'deleted' && (u.role === 'admin' || u.user_role === 'Director' || u.role === 'gad_staff' || u.user_role === 'Staff'));
});

const nonTwgUsers = computed(() => {
  const baseList = users.value.filter(u => !u.deleted_at && u.user_role === 'Non-TWG' && u.role !== 'deleted');
  return filterUserList(baseList, searchNonTwg.value, filterNonTwgOffice.value);
});

const nonTwgWithSubmissions = computed(() => {
  return nonTwgUsers.value.filter(u => parseInt(u.ad_count || 0) > 0 || parseInt(u.ar_count || 0) > 0);
});

const nonTwgWithoutSubmissions = computed(() => {
  return nonTwgUsers.value.filter(u => parseInt(u.ad_count || 0) === 0 && parseInt(u.ar_count || 0) === 0);
});

const daysOnSystem = (dateString) => {
  if (!dateString) return 0;
  const created = new Date(dateString.endsWith('Z') ? dateString : dateString + 'Z');
  const now = new Date();
  const diffTime = Math.abs(now - created);
  return Math.floor(diffTime / (1000 * 60 * 60 * 24));
};

const formatLastLogin = (dateString) => {
  if (!dateString) return 'Never';
  const login = new Date(dateString.endsWith('Z') ? dateString : dateString + 'Z');
  const now = new Date();
  const diffTime = Math.abs(now - login);
  const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
  
  if (diffDays === 0) {
    const diffHours = Math.floor(diffTime / (1000 * 60 * 60));
    if (diffHours === 0) {
      const diffMins = Math.floor(diffTime / (1000 * 60));
      return diffMins <= 1 ? 'Just now' : `${diffMins} mins ago`;
    }
    return `${diffHours} hours ago`;
  }
  if (diffDays === 1) return 'Yesterday';
  if (diffDays < 7) return `${diffDays} days ago`;
  
  return login.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const suspendedUsers = computed(() => {
  const baseList = users.value.filter(u => !!u.deleted_at && u.role !== 'deleted');
  return filterUserList(baseList, searchSuspended.value, filterSuspendedOffice.value);
});

const suspendUser = async (id) => {
  const result = await Swal.fire({
    title: 'Suspend User?',
    text: "This user will not be able to log in. Their account will be moved to Suspended Accounts.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#f59e0b',
    cancelButtonColor: '#475569',
    confirmButtonText: 'Yes, suspend them!'
  });

  if (result.isConfirmed) {
    try {
      const res = await api.post(`users/suspend/${id}`);
      if (res.data.success) {
        Swal.fire({
          icon: 'success',
          title: 'Suspended!',
          text: 'User has been suspended.',
          timer: 1500,
          showConfirmButton: false
        });
        fetchUsers();
      } else {
        throw new Error(res.data.message || 'Failed to suspend');
      }
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Error', text: err.message || 'Failed to suspend user.' });
    }
  }
};

const restoreUser = async (id) => {
  try {
    const res = await api.post(`users/restore/${id}`);
    if (res.data.success) {
      Swal.fire({
        icon: 'success',
        title: 'Restored!',
        text: 'User account has been restored.',
        timer: 1500,
        showConfirmButton: false
      });
      fetchUsers();
    } else {
      throw new Error(res.data.message || 'Failed to restore');
    }
  } catch (err) {
    Swal.fire({ icon: 'error', title: 'Error', text: err.message || 'Failed to restore user.' });
  }
};

const deleteUser = async (id) => {
  const result = await Swal.fire({
    title: 'Delete Credentials?',
    text: "This will permanently delete the user's login credentials. Their submitted reports will be kept for data privacy and integrity.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#475569',
    confirmButtonText: 'Yes, delete it!'
  });

  if (result.isConfirmed) {
    try {
      const res = await api.post(`users/delete/${id}`);
      if (res.data.success) {
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'User credentials have been deleted.',
          timer: 1500,
          showConfirmButton: false
        });
        fetchUsers();
      } else {
        throw new Error(res.data.message || 'Failed to delete credentials');
      }
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Error', text: err.message || 'Failed to delete credentials.' });
    }
  }
};


const formatDate = (dateString) => {
  if (!dateString) return '---';
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString('en-US', options);
};

onMounted(() => {
  const user = JSON.parse(localStorage.getItem('user') || '{}');
  if (!user.id || (user.role !== 'admin' && user.role !== 'gad_staff')) {
    router.push('/login');
    return;
  }
  fetchOffices();
  fetchUsers();
  fetchSettings();
});
</script>

<style scoped>
.main-viewport { 
  flex: 1; 
  overflow-y: auto; 
  background: transparent; 
}

.page-container { 
  min-height: 100vh; 
  padding: 1.5rem; 
  max-width: 1400px; 
  margin: 0 auto; 
}

.page-title {
  font-size: 1.5rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  color: #0f172a;
}

.page-subtitle {
  font-size: 1rem;
  color: #64748b;
  margin-top: 0.25rem;
}

/* Glass Card / User Cards */
.glass-card { 
  background: #ffffff;
  backdrop-filter: blur(24px); 
  border-radius: 1.5rem; 
  border: 1px solid #e2e8f0; 
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
  transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

.layout-stacked {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  overflow-x: auto;
  padding-bottom: 1rem;
}

.user-card {
  display: flex;
  flex-direction: column;
  min-height: 250px;
  max-height: 520px;
  overflow: hidden;
  min-width: 1000px;
}

.card-header {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #f1f5f9;
  background: #f8fafc;
}

.card-section-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #0f172a;
  display: flex;
  align-items: center;
}

/* Icon Boxes */
.icon-box {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.icon-box-purple {
  background: #f3e8ff;
  color: #7e22ce;
  border: 1px solid #e9d5ff;
}

.icon-box-red {
  background: #fee2e2;
  color: #dc2626;
  border: 1px solid #fecaca;
}

/* Badges */
.badge {
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 800;
}
.badge-purple {
  background: #f3e8ff;
  color: #7e22ce;
  border: 1px solid #e9d5ff;
}

.badge-slate {
  background: #f1f5f9;
  color: #475569;
  border: 1px solid #cbd5e1;
}

.badge-red {
  background: #fee2e2;
  color: #dc2626;
  border: 1px solid #fecaca;
}

/* Search and Filters */
.search-input {
  width: 100%;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  border-radius: 0.5rem;
  padding: 0.5rem 1rem;
  color: #0f172a;
  font-size: 0.875rem;
  outline: none;
  transition: all 0.2s;
}
.search-input::placeholder {
  color: #94a3b8;
}
.search-input:focus {
  border-color: #9333ea;
  box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.15);
}

.search-input-red {
  border-color: #fca5a5;
}
.search-input-red:focus {
  border-color: #ef4444;
  box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.15);
}

.filter-select {
  background: #ffffff url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%237e22ce' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E") no-repeat right 1rem center/1.25rem 1.25rem;
  appearance: none;
  border: 1px solid #cbd5e1;
  border-radius: 0.5rem;
  padding: 0.5rem 2.5rem 0.5rem 1rem;
  color: #0f172a;
  font-size: 0.875rem;
  outline: none;
  transition: all 0.2s;
  cursor: pointer;
}
.filter-select:focus {
  border-color: #9333ea;
  box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.15);
}
.filter-select option {
  background: #ffffff;
  color: #0f172a;
}

.filter-select-red {
  border-color: #fca5a5;
  background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%23dc2626' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E");
}
.filter-select-red:focus {
  border-color: #ef4444;
  box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.15);
}

/* Card Body */
.card-body {
  flex: 1;
  overflow-y: auto;
  padding: 1.5rem;
}

.empty-state {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: #7e22ce;
  opacity: 0.8;
  font-size: 0.875rem;
  font-weight: 500;
  text-align: center;
  padding: 2rem;
}

.empty-state-red {
  color: #dc2626 !important;
  opacity: 0.8 !important;
}

/* User Grid & Items */
.user-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1rem;
}

.user-item {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 1rem;
  padding: 1.25rem;
  display: flex;
  flex-direction: column;
  transition: all 0.2s ease;
  min-height: 140px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
.user-item:hover {
  background: #fdf4ff;
  border-color: #d8b4fe;
  transform: translateY(-2px);
  box-shadow: 0 4px 14px rgba(147, 51, 234, 0.08);
}

.user-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.user-name {
  font-weight: 700;
  color: #0f172a;
  font-size: 1rem;
}

.user-meta {
  color: #64748b;
  font-size: 0.75rem;
  font-weight: 500;
}

.user-office {
  font-size: 0.75rem;
  font-weight: 600;
  color: #7e22ce;
}

.user-login {
  color: #0284c7;
  font-size: 0.75rem;
  font-weight: 500;
}

.user-days {
  color: #059669;
  font-size: 0.75rem;
  font-weight: 500;
}

/* Sub-sections */
.sub-section-title {
  font-size: 1.125rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.sub-section-title.text-purple {
  color: #7e22ce;
}

.sub-section-title.text-slate {
  color: #475569;
}

.sub-section-empty {
  color: #94a3b8;
}

/* Suspended Section Specifics */
.suspended-card {
  border-color: #fecaca;
}

.suspended-item {
  border-color: #fee2e2;
  background: #fff5f5;
}
.suspended-item:hover {
  background: #fee2e2;
  border-color: #fca5a5;
  box-shadow: 0 4px 14px rgba(239, 68, 68, 0.08);
}

.suspended-user-name {
  color: #b91c1c;
}

.suspended-office {
  color: #dc2626;
}

.suspended-date {
  color: #ef4444;
}

/* User Actions */
.user-actions {
  display: flex;
  gap: 0.5rem;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #f1f5f9;
}

.btn-edit {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.25rem;
  flex: 1;
  padding: 0.5rem;
  border-radius: 0.5rem;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  transition: all 0.2s;
  background: #f3e8ff;
  color: #7e22ce;
  border: 1px solid #e9d5ff;
}
.btn-edit:hover {
  background: #e9d5ff;
  border-color: #d8b4fe;
  color: #6b21a8;
}

.btn-suspend, .btn-restore, .btn-delete {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.25rem;
  flex: 1;
  padding: 0.5rem;
  border-radius: 0.5rem;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  transition: all 0.2s;
  border: 1px solid transparent;
}

.btn-suspend {
  background: #fef3c7;
  color: #b45309;
  border-color: #fde68a;
}
.btn-suspend:hover {
  background: #fde68a;
  border-color: #fcd34d;
  color: #92400e;
}

.btn-restore {
  background: #dcfce7;
  color: #15803d;
  border-color: #bbf7d0;
}
.btn-restore:hover {
  background: #bbf7d0;
  border-color: #86efac;
  color: #166534;
}

.btn-delete {
  background: #fee2e2;
  color: #b91c1c;
  border-color: #fecaca;
}
.btn-delete:hover {
  background: #fecaca;
  border-color: #fca5a5;
  color: #991b1b;
}

/* Global Buttons */
.btn-primary {
  background: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%);
  color: white;
  padding: 0.6rem 1.25rem;
  border-radius: 0.75rem;
  font-weight: 600;
  border: none;
  transition: all 0.2s;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(147, 51, 234, 0.25);
}
.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(147, 51, 234, 0.35);
}
.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-secondary {
  background: #ffffff;
  color: #334155;
  padding: 0.6rem 1.25rem;
  border-radius: 0.75rem;
  font-weight: 600;
  border: 1px solid #cbd5e1;
  transition: all 0.2s;
  cursor: pointer;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}
.btn-secondary:hover {
  background: #f8fafc;
  color: #0f172a;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(15, 23, 42, 0.5);
  backdrop-filter: blur(4px);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.modal-container {
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 1.5rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.modal-header {
  border-bottom: 1px solid #e2e8f0;
  background: #f8fafc;
}

.modal-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #0f172a;
}

.modal-close-btn {
  color: #64748b;
  transition: color 0.2s;
  display: flex;
  align-items: center;
}
.modal-close-btn:hover {
  color: #0f172a;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-label {
  color: #334155;
  font-size: 0.875rem;
  font-weight: 600;
}

.form-input {
  background-color: #f8fafc;
  border: 1px solid #cbd5e1;
  color: #0f172a;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  font-size: 0.95rem;
  outline: none;
  transition: all 0.2s;
}
.form-input:focus {
  border-color: #9333ea;
  background-color: #ffffff;
  box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.15);
}
.form-input option {
  background: #ffffff;
  color: #0f172a;
}

select.form-input {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%237e22ce' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 1.25rem 1.25rem;
  padding-right: 2.5rem;
}

/* Custom Scrollbars */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>

<style>
/* ==========================================================================
   Dark Mode Overrides for User Management (Director & Staff)
   Outer background remains unchanged/white; ONLY the cards and modal darken!
   ========================================================================== */
html.dark .user-card.glass-card,
.dark .user-card.glass-card { 
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important; 
  border-color: rgba(185, 121, 204, 0.25) !important; 
  box-shadow: 0 20px 40px rgba(0,0,0,0.25), 0 0 30px rgba(185, 121, 204, 0.05) !important; 
}

html.dark .card-header,
.dark .card-header {
  border-bottom: 1px solid rgba(185, 121, 204, 0.15) !important;
  background: rgba(0, 0, 0, 0.2) !important;
}

html.dark .card-section-title,
.dark .card-section-title {
  color: #ffffff !important;
}

html.dark .page-title,
.dark .page-title {
  color: #0f172a !important;
}

html.dark .page-subtitle,
.dark .page-subtitle {
  color: #64748b !important;
}

/* Icon boxes */
html.dark .icon-box-purple,
.dark .icon-box-purple {
  background: rgba(168, 85, 247, 0.2) !important;
  color: #c084fc !important;
  border-color: rgba(168, 85, 247, 0.3) !important;
}
html.dark .icon-box-red,
.dark .icon-box-red {
  background: rgba(239, 68, 68, 0.2) !important;
  color: #f87171 !important;
  border-color: rgba(239, 68, 68, 0.3) !important;
}

/* Badges */
html.dark .badge-purple,
.dark .badge-purple {
  background: rgba(168, 85, 247, 0.2) !important;
  color: #d8b4fe !important;
  border-color: rgba(168, 85, 247, 0.3) !important;
}
html.dark .badge-slate,
.dark .badge-slate {
  background: rgba(148, 163, 184, 0.2) !important;
  color: #cbd5e1 !important;
  border-color: rgba(148, 163, 184, 0.3) !important;
}
html.dark .badge-red,
.dark .badge-red {
  background: rgba(239, 68, 68, 0.2) !important;
  color: #fca5a5 !important;
  border-color: rgba(239, 68, 68, 0.3) !important;
}

/* Search and Filters */
html.dark .search-input,
.dark .search-input {
  background: rgba(0, 0, 0, 0.3) !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
  color: white !important;
}
html.dark .search-input:focus,
.dark .search-input:focus {
  border-color: rgba(185, 121, 204, 0.6) !important;
  background: rgba(0, 0, 0, 0.4) !important;
}

html.dark .search-input-red,
.dark .search-input-red {
  border-color: rgba(239, 68, 68, 0.3) !important;
}
html.dark .search-input-red:focus,
.dark .search-input-red:focus {
  border-color: rgba(239, 68, 68, 0.6) !important;
}

html.dark .filter-select,
.dark .filter-select {
  background: rgba(0, 0, 0, 0.3) url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%23b979cc' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E") no-repeat right 1rem center/1.25rem 1.25rem !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
  color: white !important;
}
html.dark .filter-select:focus,
.dark .filter-select:focus {
  border-color: rgba(185, 121, 204, 0.6) !important;
  background-color: rgba(0, 0, 0, 0.4) !important;
}
html.dark .filter-select option,
.dark .filter-select option {
  background: #1e293b !important;
  color: white !important;
}

html.dark .filter-select-red,
.dark .filter-select-red {
  border-color: rgba(239, 68, 68, 0.3) !important;
  background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%23f87171' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E") !important;
}
html.dark .filter-select-red:focus,
.dark .filter-select-red:focus {
  border-color: rgba(239, 68, 68, 0.6) !important;
}

/* Empty State */
html.dark .empty-state,
.dark .empty-state {
  color: #b979cc !important;
  opacity: 0.7 !important;
}
html.dark .empty-state-red,
.dark .empty-state-red {
  color: rgba(248, 113, 113, 0.7) !important;
}

/* User Items */
html.dark .user-item,
.dark .user-item {
  background: rgba(0, 0, 0, 0.25) !important;
  border: 1px solid rgba(185, 121, 204, 0.15) !important;
  box-shadow: none !important;
}
html.dark .user-item:hover,
.dark .user-item:hover {
  background: rgba(0, 0, 0, 0.4) !important;
  border-color: rgba(185, 121, 204, 0.4) !important;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
}

html.dark .user-name,
.dark .user-name {
  color: #f8fafc !important;
}

html.dark .user-meta,
.dark .user-meta {
  color: #94a3b8 !important;
}

html.dark .user-office,
.dark .user-office {
  color: rgba(216, 180, 254, 0.85) !important;
}

html.dark .user-login,
.dark .user-login {
  color: #93c5fd !important;
}

html.dark .user-days,
.dark .user-days {
  color: #34d399 !important;
}

/* Sub-sections */
html.dark .sub-section-title.text-purple,
.dark .sub-section-title.text-purple {
  color: #d8b4fe !important;
}
html.dark .sub-section-title.text-slate,
.dark .sub-section-title.text-slate {
  color: #cbd5e1 !important;
}
html.dark .sub-section-empty,
.dark .sub-section-empty {
  color: #64748b !important;
}

/* Suspended Section Specifics */
html.dark .suspended-card,
.dark .suspended-card {
  border-color: rgba(239, 68, 68, 0.3) !important;
}
html.dark .suspended-item,
.dark .suspended-item {
  background: rgba(0, 0, 0, 0.25) !important;
  border-color: rgba(239, 68, 68, 0.2) !important;
}
html.dark .suspended-item:hover,
.dark .suspended-item:hover {
  background: rgba(239, 68, 68, 0.1) !important;
  border-color: rgba(239, 68, 68, 0.4) !important;
}
html.dark .suspended-user-name,
.dark .suspended-user-name {
  color: #fca5a5 !important;
}
html.dark .suspended-office,
.dark .suspended-office {
  color: rgba(252, 165, 165, 0.8) !important;
}
html.dark .suspended-date,
.dark .suspended-date {
  color: #f87171 !important;
}

/* User Actions */
html.dark .user-actions,
.dark .user-actions {
  border-top: 1px solid rgba(185, 121, 204, 0.15) !important;
}

html.dark .btn-edit,
.dark .btn-edit {
  background: rgba(168, 85, 247, 0.1) !important;
  color: #c084fc !important;
  border-color: rgba(168, 85, 247, 0.2) !important;
}
html.dark .btn-edit:hover,
.dark .btn-edit:hover {
  background: rgba(168, 85, 247, 0.2) !important;
  border-color: rgba(168, 85, 247, 0.4) !important;
}

html.dark .btn-suspend,
.dark .btn-suspend {
  background: rgba(245, 158, 11, 0.1) !important;
  color: #f59e0b !important;
  border-color: rgba(245, 158, 11, 0.2) !important;
}
html.dark .btn-suspend:hover,
.dark .btn-suspend:hover {
  background: rgba(245, 158, 11, 0.2) !important;
  border-color: rgba(245, 158, 11, 0.4) !important;
}

html.dark .btn-restore,
.dark .btn-restore {
  background: rgba(34, 197, 94, 0.1) !important;
  color: #22c55e !important;
  border-color: rgba(34, 197, 94, 0.2) !important;
}
html.dark .btn-restore:hover,
.dark .btn-restore:hover {
  background: rgba(34, 197, 94, 0.2) !important;
  border-color: rgba(34, 197, 94, 0.4) !important;
}

html.dark .btn-delete,
.dark .btn-delete {
  background: rgba(239, 68, 68, 0.15) !important;
  color: #fca5a5 !important;
  border-color: rgba(239, 68, 68, 0.3) !important;
}
html.dark .btn-delete:hover,
.dark .btn-delete:hover {
  background: rgba(239, 68, 68, 0.3) !important;
  border-color: rgba(239, 68, 68, 0.5) !important;
}

html.dark .btn-secondary,
.dark .btn-secondary {
  background: rgba(255, 255, 255, 0.1) !important;
  color: #e2e8f0 !important;
  border: 1px solid rgba(255, 255, 255, 0.2) !important;
  box-shadow: none !important;
}
html.dark .btn-secondary:hover,
.dark .btn-secondary:hover {
  background: rgba(255, 255, 255, 0.15) !important;
}

/* Modal */
html.dark .modal-overlay,
.dark .modal-overlay {
  background: rgba(0, 0, 0, 0.6) !important;
}

html.dark .modal-container,
.dark .modal-container {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border: 1px solid rgba(185, 121, 204, 0.25) !important;
  box-shadow: 0 20px 40px rgba(0,0,0,0.4) !important;
}

html.dark .modal-header,
.dark .modal-header {
  border-bottom: 1px solid rgba(185, 121, 204, 0.2) !important;
  background: rgba(0, 0, 0, 0.2) !important;
}

html.dark .modal-title,
.dark .modal-title {
  color: #ffffff !important;
}

html.dark .modal-close-btn,
.dark .modal-close-btn {
  color: #94a3b8 !important;
}
html.dark .modal-close-btn:hover,
.dark .modal-close-btn:hover {
  color: #ffffff !important;
}

html.dark .form-label,
.dark .form-label {
  color: #cbd5e1 !important;
}

html.dark .form-input,
.dark .form-input {
  background-color: rgba(0, 0, 0, 0.3) !important;
  border: 1px solid rgba(185, 121, 204, 0.3) !important;
  color: white !important;
}
html.dark .form-input:focus,
.dark .form-input:focus {
  border-color: #b979cc !important;
  background-color: rgba(0, 0, 0, 0.5) !important;
}
html.dark .form-input option,
.dark .form-input option {
  background: #1e293b !important;
  color: white !important;
}

html.dark select.form-input,
.dark select.form-input {
  background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%23b979cc' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E") !important;
  background-repeat: no-repeat !important;
  background-position: right 1rem center !important;
  background-size: 1.25rem 1.25rem !important;
}

/* Custom Scrollbars */
html.dark .custom-scrollbar::-webkit-scrollbar-track,
.dark .custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(255,255,255,0.02) !important;
}
html.dark .custom-scrollbar::-webkit-scrollbar-thumb,
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(185, 121, 204, 0.3) !important;
}
html.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover,
.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(185, 121, 204, 0.5) !important;
}
</style>
