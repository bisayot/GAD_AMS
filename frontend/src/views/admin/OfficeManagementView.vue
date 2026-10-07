<template>
  <div class="page-container">
    <div v-if="!isNested" class="header-section">
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

    <!-- Action Bar for nested view -->
    <div v-if="isNested" class="flex justify-end mb-6">
      <button class="btn-primary" @click="openAddModal">
        <span class="material-symbols-outlined">add</span>
        Add Office
      </button>
    </div>

    <!-- Filters and Search -->
    <div class="glass-card mb-6 p-4 flex flex-col sm:flex-row gap-4 items-center justify-between filter-bar">
      <div class="relative w-full sm:w-1/2 md:w-1/3">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
        <input 
          type="text" 
          v-model="searchQuery" 
          placeholder="Search office name..." 
          class="search-input w-full rounded-lg py-2 pl-10 pr-4 transition-colors outline-none"
        >
      </div>
      <div class="flex items-center gap-2 w-full sm:w-auto">
        <span class="sort-label text-sm whitespace-nowrap">Sort by:</span>
        <select 
          v-model="sortOrder"
          class="sort-select rounded-lg py-2 px-4 transition-colors cursor-pointer outline-none"
        >
          <option value="id_asc">Oldest First</option>
          <option value="id_desc">Newest First</option>
          <option value="name_asc">Name (A-Z)</option>
          <option value="name_desc">Name (Z-A)</option>
        </select>
      </div>
    </div>

    <!-- Offices Table -->
    <div class="glass-card table-container overflow-x-auto">
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
      </div>
      
      <table v-else class="w-full text-left border-collapse custom-table min-w-[600px]">
        <thead>
          <tr>
            <th class="th-cell w-16 text-center">ID</th>
            <th class="th-cell">Office Name</th>
            <th class="th-cell text-right w-40">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="filteredOffices.length === 0">
            <td colspan="3" class="p-8 text-center empty-state">
              No offices found matching your criteria.
            </td>
          </tr>
          <tr v-else v-for="(office, index) in filteredOffices" :key="office.office_id" class="table-row">
            <td class="td-cell text-center id-cell">{{ index + 1 }}</td>
            <td class="td-cell name-cell">{{ office.office_name }}</td>
            <td class="td-cell">
              <div class="flex justify-end items-center gap-2">
                <button class="btn-icon btn-icon-edit" @click="openEditModal(office)" title="Edit">
                  <span class="material-symbols-outlined text-[1.2rem]">edit</span>
                </button>
                <button class="btn-icon btn-icon-delete" @click="confirmDelete(office.office_id)" title="Delete">
                  <span class="material-symbols-outlined text-[1.2rem]">delete</span>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/60 backdrop-blur-sm modal-backdrop" @click="closeModal"></div>
      
      <!-- Modal Content -->
      <div class="relative modal-container w-full max-w-md p-6 rounded-2xl shadow-2xl animate-fade-in">
        <div class="flex justify-between items-center mb-6">
          <h2 class="modal-title text-xl font-bold flex items-center gap-2">
            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">{{ isEditing ? 'edit' : 'add_circle' }}</span>
            {{ isEditing ? 'Edit Office' : 'Add New Office' }}
          </h2>
          <button @click="closeModal" class="modal-close-btn transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>

        <form @submit.prevent="saveOffice" class="space-y-4">
          <div>
            <label class="form-label block text-sm font-medium mb-1">Office / Unit Name</label>
            <input 
              v-model="form.office_name" 
              type="text" 
              required
              class="form-input w-full rounded-lg px-4 py-2.5 transition-colors"
              placeholder="e.g., College of Engineering"
            />
          </div>

          <div v-if="errorMessage" class="p-3 bg-red-500/10 border border-red-500/20 rounded-lg text-red-500 dark:text-red-400 text-sm">
            {{ errorMessage }}
          </div>

          <div class="flex justify-end gap-3 mt-8">
            <button type="button" @click="closeModal" class="btn-cancel px-4 py-2 rounded-lg transition-colors font-medium">
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

const props = defineProps({
  isNested: {
    type: Boolean,
    default: false
  }
});

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

  if (!result.isConfirmed) {
    return;
  }

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
};

onMounted(() => {
  fetchOffices();
});
</script>

<style scoped>
.page-container {
  padding: v-bind('isNested ? "0" : "32px"');
  max-width: 1400px;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 2rem;
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

/* Glass Card / Card Container */
.glass-card {
  background: #ffffff;
  border-radius: 1.25rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  transition: all 0.3s ease;
}

/* Search and Filters */
.search-input {
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  color: #0f172a;
}
.search-input:focus {
  border-color: #9333ea;
  background: #ffffff;
  box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.15);
}

.sort-label {
  color: #475569;
}

.sort-select {
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  color: #0f172a;
}
.sort-select:focus {
  border-color: #9333ea;
  background: #ffffff;
}
.sort-select option {
  background: #ffffff;
  color: #0f172a;
}

/* Table Styling */
.th-cell {
  padding: 1rem;
  font-weight: 700;
  font-size: 0.875rem;
  border-bottom: 1px solid #e2e8f0;
  background: #f8fafc;
  color: #475569;
}

.table-row {
  border-bottom: 1px solid #f1f5f9;
  transition: background-color 0.15s ease;
}
.table-row:hover {
  background-color: #f8fafc;
}

.td-cell {
  padding: 1rem;
}

.id-cell {
  color: #64748b;
  font-size: 0.875rem;
}

.name-cell {
  color: #0f172a;
  font-weight: 600;
}

.empty-state {
  color: #64748b;
}

/* Primary Button */
.btn-primary {
  background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
  color: white;
  padding: 0.6rem 1.25rem;
  border-radius: 0.75rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s;
  border: 1px solid rgba(255,255,255,0.1);
  box-shadow: 0 4px 12px rgba(147, 51, 234, 0.25);
  cursor: pointer;
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(147, 51, 234, 0.35);
}

.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

/* Action Icon Buttons */
.btn-icon {
  width: 2.25rem;
  height: 2.25rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.5rem;
  transition: all 0.2s;
  border: none;
  cursor: pointer;
}

.btn-icon-edit {
  color: #7e22ce;
  background: rgba(126, 34, 206, 0.08);
}
.btn-icon-edit:hover {
  background: rgba(126, 34, 206, 0.16);
  color: #6b21a8;
}

.btn-icon-delete {
  color: #ef4444;
  background: rgba(239, 68, 68, 0.08);
}
.btn-icon-delete:hover {
  background: rgba(239, 68, 68, 0.16);
  color: #dc2626;
}

/* Modal Styling */
.modal-container {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.modal-title {
  color: #0f172a;
}

.modal-close-btn {
  color: #64748b;
  cursor: pointer;
}
.modal-close-btn:hover {
  color: #0f172a;
}

.form-label {
  color: #334155;
  font-weight: 600;
}

.form-input {
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  color: #0f172a;
}
.form-input:focus {
  border-color: #9333ea;
  background: #ffffff;
  box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.15);
  outline: none;
}

.btn-cancel {
  background: #f1f5f9;
  border: 1px solid #cbd5e1;
  color: #475569;
  cursor: pointer;
}
.btn-cancel:hover {
  background: #e2e8f0;
  color: #1e293b;
}

.animate-fade-in {
  animation: fadeIn 0.2s ease-out forwards;
}

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
</style>

<style>
/* ==========================================================================
   Dark Mode Overrides for Office Management
   Outer background remains white; ONLY the cards and modals darken!
   ========================================================================== */
html.dark .glass-card,
.dark .glass-card {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border-color: rgba(185, 121, 204, 0.25) !important;
  box-shadow: 0 20px 40px rgba(0,0,0,0.25) !important;
}

html.dark .search-input,
.dark .search-input {
  background: rgba(0, 0, 0, 0.3) !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
  color: #ffffff !important;
}
html.dark .search-input:focus,
.dark .search-input:focus {
  border-color: rgba(185, 121, 204, 0.6) !important;
}

html.dark .sort-label,
.dark .sort-label {
  color: #cbd5e1 !important;
}

html.dark .sort-select,
.dark .sort-select {
  background: rgba(0, 0, 0, 0.3) !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
  color: #ffffff !important;
}
html.dark .sort-select option,
.dark .sort-select option {
  background: #1e293b !important;
  color: #ffffff !important;
}

html.dark .th-cell,
.dark .th-cell {
  background: rgba(0, 0, 0, 0.2) !important;
  border-bottom: 1px solid rgba(185, 121, 204, 0.2) !important;
  color: #ffffff !important;
}

html.dark .table-row,
.dark .table-row {
  border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
}
html.dark .table-row:hover,
.dark .table-row:hover {
  background: rgba(255, 255, 255, 0.05) !important;
}

html.dark .id-cell,
.dark .id-cell {
  color: #94a3b8 !important;
}

html.dark .name-cell,
.dark .name-cell {
  color: #f8fafc !important;
}

html.dark .empty-state,
.dark .empty-state {
  color: #94a3b8 !important;
}

html.dark .btn-icon-edit,
.dark .btn-icon-edit {
  color: #c084fc !important;
  background: rgba(168, 85, 247, 0.15) !important;
}
html.dark .btn-icon-edit:hover,
.dark .btn-icon-edit:hover {
  background: rgba(168, 85, 247, 0.3) !important;
}

html.dark .btn-icon-delete,
.dark .btn-icon-delete {
  color: #f87171 !important;
  background: rgba(239, 68, 68, 0.15) !important;
}
html.dark .btn-icon-delete:hover,
.dark .btn-icon-delete:hover {
  background: rgba(239, 68, 68, 0.3) !important;
}

html.dark .modal-container,
.dark .modal-container {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
  box-shadow: 0 20px 40px rgba(0,0,0,0.5) !important;
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
  background: rgba(0, 0, 0, 0.3) !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
  color: #ffffff !important;
}

html.dark .btn-cancel,
.dark .btn-cancel {
  background: rgba(255, 255, 255, 0.1) !important;
  border-color: rgba(255, 255, 255, 0.2) !important;
  color: #cbd5e1 !important;
}
html.dark .btn-cancel:hover,
.dark .btn-cancel:hover {
  background: rgba(255, 255, 255, 0.15) !important;
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
</style>
