<template>
  <main class="main-viewport">
    <div class="page-container">
      <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-black text-[#16213e] tracking-tight drop-shadow-sm">User Management</h1>
      </div>

      <div class="layout-stacked">
        
        <!-- TWG Users Card -->
        <section class="user-card glass-card">
          <div class="card-header">
            <div class="flex items-center justify-between w-full flex-wrap gap-4">
              <div class="flex items-center gap-3">
                <div class="icon-box bg-purple-500/20 text-purple-400 border border-purple-500/30">
                  <span class="material-symbols-outlined">group</span>
                </div>
                <h2 class="text-xl font-bold text-white">TWG Users <span class="badge ml-2 bg-purple-500/20 text-purple-300 border border-purple-500/30">{{ twgUsers.length }}</span></h2>
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
                  <div class="user-meta text-purple-300/80 mt-1">{{ user.office_name || 'No Office' }}</div>
                </div>
                <div class="user-actions mt-auto">
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
                <div class="icon-box bg-purple-500/20 text-purple-400 border border-purple-500/30">
                  <span class="material-symbols-outlined">person</span>
                </div>
                <h2 class="text-xl font-bold text-white">Non-TWG Users <span class="badge ml-2 bg-purple-500/20 text-purple-300 border border-purple-500/30">{{ nonTwgUsers.length }}</span></h2>
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
          <div class="card-body custom-scrollbar">
            <div v-if="nonTwgUsers.length === 0" class="empty-state">No Non-TWG users found.</div>
            <div v-else class="user-grid">
              <div v-for="user in nonTwgUsers" :key="user.id" class="user-item">
                <div class="user-info">
                  <div class="user-name">{{ user.full_name || 'N/A' }}</div>
                  <div class="user-meta">{{ user.email }}</div>
                  <div class="user-meta text-purple-300/80 mt-1">{{ user.office_name || 'No Office' }}</div>
                </div>
                <div class="user-actions mt-auto">
                  <button @click="suspendUser(user.id)" class="btn-suspend" title="Suspend User">
                    <span class="material-symbols-outlined text-sm">block</span> Suspend
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- Suspended Accounts Card -->
        <section class="user-card glass-card border-red-500/30">
          <div class="card-header">
            <div class="flex items-center justify-between w-full flex-wrap gap-4">
              <div class="flex items-center gap-3">
                <div class="icon-box bg-red-500/20 text-red-400 border border-red-500/30">
                  <span class="material-symbols-outlined">no_accounts</span>
                </div>
                <h2 class="text-xl font-bold text-white">Suspended Accounts <span class="badge ml-2 bg-red-500/20 text-red-300 border border-red-500/30">{{ suspendedUsers.length }}</span></h2>
              </div>
              <div class="flex gap-3 flex-1 md:flex-none justify-end">
                <div class="relative w-full md:w-64">
                  <input v-model="searchSuspended" type="text" placeholder="Search..." class="search-input !border-red-500/30 focus:!border-red-500/60">
                </div>
                <select v-model="filterSuspendedOffice" class="filter-select !border-red-500/30 focus:!border-red-500/60">
                  <option value="">All Offices</option>
                  <option v-for="office in offices" :key="office.unit_id" :value="office.unit_name">{{ office.unit_name }}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="card-body custom-scrollbar relative">
            <div class="warning-note mb-4">
              <span class="material-symbols-outlined text-sm">warning</span>
              Note: Suspended accounts will be automatically deleted after 30 days.
            </div>
            <div v-if="suspendedUsers.length === 0" class="empty-state !text-red-400/50">No suspended users.</div>
            <div v-else class="user-grid">
              <div v-for="user in suspendedUsers" :key="user.id" class="user-item !border-red-500/20 hover:!border-red-500/40 hover:!bg-red-500/10">
                <div class="user-info">
                  <div class="user-name text-red-200">{{ user.full_name || 'N/A' }}</div>
                  <div class="user-meta">{{ user.email }}</div>
                  <div class="user-meta text-red-300/80 mt-1">{{ user.office_name || 'No Office' }}</div>
                  <div class="user-meta mt-2 text-xs italic text-red-400">Suspended on: {{ formatDate(user.deleted_at) }}</div>
                </div>
                <div class="user-actions mt-auto">
                  <button @click="restoreUser(user.id)" class="btn-restore" title="Restore User">
                    <span class="material-symbols-outlined text-sm">restore</span> Restore
                  </button>
                  <button @click="deleteUser(user.id)" class="btn-delete" title="Permanently Delete">
                    <span class="material-symbols-outlined text-sm">delete_forever</span> Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>

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

// Individual search/filter states
const searchTwg = ref('');
const filterTwgOffice = ref('');

const searchNonTwg = ref('');
const filterNonTwgOffice = ref('');

const searchSuspended = ref('');
const filterSuspendedOffice = ref('');

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
    if (user.role === 'admin' || user.user_role === 'Director') return false;

    const matchesSearch = !search || 
      (user.full_name?.toLowerCase().includes(search.toLowerCase()) || 
       user.email?.toLowerCase().includes(search.toLowerCase()) ||
       user.user_role?.toLowerCase().includes(search.toLowerCase()));
       
    const matchesOffice = !office || user.office_name === office;

    return matchesSearch && matchesOffice;
  });
};

const twgUsers = computed(() => {
  const baseList = users.value.filter(u => !u.deleted_at && u.user_role === 'TWG');
  return filterUserList(baseList, searchTwg.value, filterTwgOffice.value);
});

const nonTwgUsers = computed(() => {
  const baseList = users.value.filter(u => !u.deleted_at && u.user_role === 'Non-TWG');
  return filterUserList(baseList, searchNonTwg.value, filterNonTwgOffice.value);
});

const suspendedUsers = computed(() => {
  const baseList = users.value.filter(u => !!u.deleted_at);
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
    title: 'Permanently Delete?',
    text: "You won't be able to revert this! All user data will be permanently removed.",
    icon: 'error',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#475569',
    confirmButtonText: 'Yes, delete forever!'
  });

  if (result.isConfirmed) {
    try {
      const res = await api.delete(`users/delete/${id}`);
      if (res.data.success) {
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'User has been permanently deleted.',
          timer: 1500,
          showConfirmButton: false
        });
        fetchUsers();
      } else {
        throw new Error(res.data.message || 'Failed to delete');
      }
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Error', text: err.message || 'Failed to delete user.' });
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
  if (!user.id || user.role !== 'admin') {
    router.push('/login');
    return;
  }
  fetchOffices();
  fetchUsers();
});
</script>

<style scoped>
.main-viewport { flex: 1; overflow-y: auto; background: transparent; }
.page-container { min-height: 100vh; padding: 1rem; max-width: 1200px; margin: 0 auto; }
.glass-card { 
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); 
  backdrop-filter: blur(24px); 
  border-radius: 1.5rem; 
  border: 1px solid rgba(185, 121, 204, 0.25); 
  box-shadow: 0 20px 40px rgba(0,0,0,0.15), 0 0 30px rgba(185, 121, 204, 0.05); 
}

.layout-stacked {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.user-card {
  display: flex;
  flex-direction: column;
  min-height: 250px;
  max-height: 500px;
  overflow: hidden;
}

.card-header {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid rgba(185, 121, 204, 0.15);
  background: rgba(0, 0, 0, 0.2);
}

.search-input {
  width: 100%;
  background: rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(185, 121, 204, 0.3);
  border-radius: 0.5rem;
  padding: 0.5rem 1rem;
  color: white;
  font-size: 0.875rem;
  outline: none;
  transition: all 0.2s;
}
.search-input:focus {
  border-color: rgba(185, 121, 204, 0.6);
  background: rgba(0, 0, 0, 0.4);
}

.filter-select {
  background: rgba(0, 0, 0, 0.3) url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%23b979cc' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E") no-repeat right 1rem center/1.25rem 1.25rem;
  appearance: none;
  border: 1px solid rgba(185, 121, 204, 0.3);
  border-radius: 0.5rem;
  padding: 0.5rem 2.5rem 0.5rem 1rem;
  color: white;
  font-size: 0.875rem;
  outline: none;
  transition: all 0.2s;
  cursor: pointer;
}
.filter-select:focus {
  border-color: rgba(185, 121, 204, 0.6);
  background: rgba(0, 0, 0, 0.4);
}
.filter-select option {
  background: #1e293b;
  color: white;
}

.icon-box {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.badge {
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 800;
}

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
  color: #b979cc;
  opacity: 0.7;
  font-size: 0.875rem;
  font-weight: 500;
  text-align: center;
  padding: 2rem;
}

.user-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1rem;
}

.user-item {
  background: rgba(0, 0, 0, 0.25);
  border: 1px solid rgba(185, 121, 204, 0.15);
  border-radius: 1rem;
  padding: 1.25rem;
  display: flex;
  flex-direction: column;
  transition: all 0.2s ease;
  min-height: 140px;
}

.user-item:hover {
  background: rgba(0, 0, 0, 0.4);
  border-color: rgba(185, 121, 204, 0.4);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.user-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.user-name {
  font-weight: 700;
  color: #f8fafc;
  font-size: 1rem;
}

.user-meta {
  color: #94a3b8;
  font-size: 0.75rem;
  font-weight: 500;
}

.user-actions {
  display: flex;
  gap: 0.5rem;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid rgba(185, 121, 204, 0.15);
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
  background: rgba(245, 158, 11, 0.1);
  color: #f59e0b;
  border-color: rgba(245, 158, 11, 0.2);
}

.btn-suspend:hover {
  background: rgba(245, 158, 11, 0.2);
  border-color: rgba(245, 158, 11, 0.4);
}

.btn-restore {
  background: rgba(34, 197, 94, 0.1);
  color: #22c55e;
  border-color: rgba(34, 197, 94, 0.2);
}

.btn-restore:hover {
  background: rgba(34, 197, 94, 0.2);
  border-color: rgba(34, 197, 94, 0.4);
}

.btn-delete {
  background: rgba(239, 68, 68, 0.1);
  color: #ef4444;
  border-color: rgba(239, 68, 68, 0.2);
}

.btn-delete:hover {
  background: rgba(239, 68, 68, 0.2);
  border-color: rgba(239, 68, 68, 0.4);
}

.warning-note {
  background: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.2);
  color: #fca5a5;
  padding: 0.75rem;
  border-radius: 0.75rem;
  font-size: 0.75rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(255,255,255,0.02);
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(185, 121, 204, 0.3);
  border-radius: 10px;
}
</style>
