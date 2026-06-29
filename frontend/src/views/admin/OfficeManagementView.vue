<template>
  <div class="page-container">
    <div class="header-section">
      <div class="flex justify-between items-center w-full">
        <div>
          <h1 class="page-title">Office / Unit Management</h1>
          <p class="page-subtitle">Add, edit, or remove offices and units in the system.</p>
        </div>
        <button class="btn-primary" @click="openAddModal">
          <span class="material-symbols-outlined">add</span>
          Add Office
        </button>
      </div>
    </div>

    <!-- Filters and Search -->
    <div class="glass-card mb-6 p-4 flex flex-col sm:flex-row gap-4 items-center justify-between">
      <div class="relative w-full sm:w-1/2 md:w-1/3">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
        <input 
          type="text" 
          v-model="searchQuery" 
          placeholder="Search office name..." 
          class="w-full bg-black/20 border border-white/10 rounded-lg py-2 pl-10 pr-4 text-white focus:outline-none focus:border-purple-500 transition-colors"
        >
      </div>
      <div class="flex items-center gap-2 w-full sm:w-auto">
        <span class="text-slate-300 text-sm whitespace-nowrap">Sort by:</span>
        <select 
          v-model="sortOrder"
          class="bg-black/20 border border-white/10 rounded-lg py-2 px-4 text-white focus:outline-none focus:border-purple-500 transition-colors cursor-pointer appearance-none outline-none [&>option]:bg-gray-800"
        >
          <option value="id_asc">Oldest First</option>
          <option value="id_desc">Newest First</option>
          <option value="name_asc">Name (A-Z)</option>
          <option value="name_desc">Name (Z-A)</option>
        </select>
      </div>
    </div>

    <!-- Offices Table -->
    <div class="glass-card table-container">
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
      </div>
      
      <table v-else class="w-full text-left border-collapse custom-table">
        <thead>
          <tr>
            <th class="p-4 font-semibold border-b border-purple-900/30 text-white w-16 text-center">ID</th>
            <th class="p-4 font-semibold border-b border-purple-900/30 text-white">Office Name</th>
            <th class="p-4 font-semibold border-b border-purple-900/30 text-white text-right w-40">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="filteredOffices.length === 0">
            <td colspan="3" class="p-8 text-center text-slate-400">
              No offices found matching your criteria.
            </td>
          </tr>
          <tr v-else v-for="(office, index) in filteredOffices" :key="office.office_id" class="hover:bg-white/5 transition-colors border-b border-white/5">
            <td class="p-4 text-slate-300 text-center">{{ index + 1 }}</td>
            <td class="p-4 text-white font-medium">{{ office.office_name }}</td>
            <td class="p-4 text-right space-x-2">
              <button class="btn-icon text-blue-400 hover:bg-blue-500/20" @click="openEditModal(office)" title="Edit">
                <span class="material-symbols-outlined text-[1.2rem]">edit</span>
              </button>
              <button class="btn-icon text-red-400 hover:bg-red-500/20" @click="confirmDelete(office.office_id)" title="Delete">
                <span class="material-symbols-outlined text-[1.2rem]">delete</span>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal"></div>
      
      <!-- Modal Content -->
      <div class="relative glass-card w-full max-w-md p-6 rounded-2xl shadow-2xl animate-fade-in">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-purple-400">{{ isEditing ? 'edit' : 'add_circle' }}</span>
            {{ isEditing ? 'Edit Office' : 'Add New Office' }}
          </h2>
          <button @click="closeModal" class="text-slate-400 hover:text-white transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>

        <form @submit.prevent="saveOffice" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1">Office / Unit Name</label>
            <input 
              v-model="form.office_name" 
              type="text" 
              required
              class="w-full bg-black/20 border border-purple-500/30 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-purple-500 transition-colors placeholder-slate-500"
              placeholder="e.g., College of Engineering"
            />
          </div>

          <div v-if="errorMessage" class="p-3 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-sm">
            {{ errorMessage }}
          </div>

          <div class="flex justify-end gap-3 mt-8">
            <button type="button" @click="closeModal" class="px-4 py-2 rounded-lg text-slate-300 hover:bg-white/5 transition-colors font-medium">
              Cancel
            </button>
            <button type="submit" class="btn-primary" :disabled="saving">
              <span v-if="saving" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isEditing ? 'Save Changes' : 'Create Office' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import api from '../../api';
import Swal from 'sweetalert2';

const offices = ref([]);
const loading = ref(true);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const errorMessage = ref('');
const form = ref({ office_id: null, office_name: '' });

const searchQuery = ref('');
const sortOrder = ref('id_asc');

const filteredOffices = computed(() => {
  let result = [...offices.value];
  
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    result = result.filter(o => o.office_name.toLowerCase().includes(q));
  }
  
  result.sort((a, b) => {
    if (sortOrder.value === 'id_asc') return a.office_id - b.office_id;
    if (sortOrder.value === 'id_desc') return b.office_id - a.office_id;
    if (sortOrder.value === 'name_asc') return a.office_name.localeCompare(b.office_name);
    if (sortOrder.value === 'name_desc') return b.office_name.localeCompare(a.office_name);
    return 0;
  });
  
  return result;
});

const fetchOffices = async () => {
  loading.value = true;
  try {
    const res = await api.get('offices');
    if (res.data.success) {
      offices.value = res.data.data;
    }
  } catch (err) {
    console.error('Failed to fetch offices:', err);
  } finally {
    loading.value = false;
  }
};

const openAddModal = () => {
  isEditing.value = false;
  form.value = { office_id: null, office_name: '' };
  errorMessage.value = '';
  showModal.value = true;
};

const openEditModal = (office) => {
  isEditing.value = true;
  form.value = { office_id: office.office_id, office_name: office.office_name };
  errorMessage.value = '';
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveOffice = async () => {
  const isEditingNow = isEditing.value;
  const actionText = isEditingNow ? 'update' : 'add';
  
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: `Do you want to ${actionText} this office?`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#10b981',
    cancelButtonColor: '#6b7280',
    confirmButtonText: 'Yes, save it!'
  });

  if (!result.isConfirmed) {
    return;
  }

  saving.value = true;
  errorMessage.value = '';
  
  try {
    if (isEditingNow) {
      const res = await api.put(`offices/${form.value.office_id}`, { office_name: form.value.office_name });
      if (res.data.success) {
        closeModal();
        fetchOffices();
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: 'Office updated successfully.',
          timer: 1500,
          showConfirmButton: false
        });
      }
    } else {
      const res = await api.post('offices', { office_name: form.value.office_name });
      if (res.data.success) {
        closeModal();
        fetchOffices();
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: 'Office added successfully.',
          timer: 1500,
          showConfirmButton: false
        });
      }
    }
  } catch (err) {
    let errorMsg = 'Failed to save office';
    
    // The api.js interceptor rejects with error.response.data directly
    const errorData = err.response?.data || err;
    
    if (errorData) {
      if (typeof errorData.messages === 'string') {
        errorMsg = errorData.messages;
      } else if (errorData.messages?.error) {
        errorMsg = errorData.messages.error;
      } else if (errorData.message) {
        errorMsg = errorData.message;
      } else if (errorData.error) {
        errorMsg = errorData.error;
      } else if (typeof errorData === 'string') {
        errorMsg = errorData;
      } else if (err.message) {
        errorMsg = err.message;
      }
    }
    
    errorMessage.value = errorMsg;
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: errorMsg
    });
  } finally {
    saving.value = false;
  }
};

const confirmDelete = async (id) => {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#9333ea',
    cancelButtonColor: '#475569',
    confirmButtonText: 'Yes, delete it!'
  });

  if (result.isConfirmed) {
    try {
      const res = await api.delete(`offices/${id}`);
      if (res.data.success) {
        fetchOffices();
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'Office has been deleted.',
          timer: 1500,
          showConfirmButton: false
        });
      }
    } catch (err) {
      let errorMsg = 'Failed to delete office';
      
      const errorData = err.response?.data || err;
      
      if (errorData) {
        if (typeof errorData.messages === 'string') {
          errorMsg = errorData.messages;
        } else if (errorData.messages?.error) {
          errorMsg = errorData.messages.error;
        } else if (errorData.message) {
          errorMsg = errorData.message;
        } else if (errorData.error) {
          errorMsg = errorData.error;
        } else if (typeof errorData === 'string') {
          errorMsg = errorData;
        } else if (err.message) {
          errorMsg = err.message;
        }
      }
      
      Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: errorMsg
      });
    }
  }
};

onMounted(() => {
  fetchOffices();
});
</script>

<style scoped>
.page-container {
  padding: 1rem;
  max-width: 1400px;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 2rem;
}

.page-title {
  font-size: 2rem;
  color: #16213e;
  font-weight: 900;
  letter-spacing: -0.025em;
  margin-bottom: 0.5rem;
}

.page-subtitle {
  color: #475569;
  font-size: 0.95rem;
}

.glass-card {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  backdrop-filter: blur(12px);
  border-radius: 1.25rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
  overflow: hidden;
}

.btn-primary {
  background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
  color: white;
  padding: 0.6rem 1.25rem;
  border-radius: 0.75rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s;
  border: 1px solid rgba(255,255,255,0.1);
  box-shadow: 0 4px 12px rgba(147, 51, 234, 0.3);
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(147, 51, 234, 0.4);
}

.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-icon {
  width: 2rem;
  height: 2rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.5rem;
  transition: all 0.2s;
}

.animate-fade-in {
  animation: fadeIn 0.2s ease-out forwards;
}

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
</style>
