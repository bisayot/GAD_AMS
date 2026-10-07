<template>
  <div class="page-container">
    <div v-if="!isNested" class="header-section">
      <div class="flex justify-between items-center w-full">
        <div>
          <h1 class="page-title">Venues Management</h1>
          <p class="page-subtitle">Add, edit, or remove venues across the campus.</p>
        </div>
        <button class="btn-primary" @click="openAddModal">
          <span class="material-symbols-outlined">add</span>
          Add Venue
        </button>
      </div>
    </div>

    <!-- Action Bar for nested view -->
    <div v-if="isNested" class="flex justify-end mb-6">
      <button class="btn-primary" @click="openAddModal">
        <span class="material-symbols-outlined">add</span>
        Add Venue
      </button>
    </div>

    <!-- Error/Loading states -->
    <div v-if="loading" class="flex justify-center items-center py-12 glass-card">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
    </div>
    <div v-else-if="error" class="glass-card p-8 text-center text-red-500 dark:text-red-400">
      {{ error }}
    </div>
    
    <!-- Venues Table -->
    <div v-else class="glass-card table-container overflow-x-auto">
      <table class="w-full text-left border-collapse custom-table min-w-[600px]">
        <thead>
          <tr>
            <th class="th-cell font-semibold">Venue Name</th>
            <th class="th-cell font-semibold">Location Type</th>
            <th class="th-cell font-semibold text-right w-40">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="venues.length === 0">
            <td colspan="3" class="p-8 text-center empty-state">No venues found.</td>
          </tr>
          <tr v-for="venue in venues" :key="venue.venue_id" class="table-row">
            <td class="td-cell name-cell font-medium">{{ venue.venue_name }}</td>
            <td class="td-cell">
              <span class="badge" :class="venue.is_inside_bsu ? 'badge-inside' : 'badge-outside'">
                {{ venue.is_inside_bsu ? 'Inside BSU' : 'Outside BSU' }}
              </span>
            </td>
            <td class="td-cell">
              <div class="flex justify-end items-center gap-2">
                <button class="btn-icon btn-icon-edit" @click="openEditModal(venue)" title="Edit">
                  <span class="material-symbols-outlined text-[1.2rem]">edit</span>
                </button>
                <button class="btn-icon btn-icon-delete" @click="confirmDelete(venue.venue_id)" title="Delete">
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
      <div class="absolute inset-0 bg-black/60 backdrop-blur-sm modal-backdrop" @click="closeModal"></div>
      
      <div class="relative modal-container w-full max-w-md p-6 rounded-2xl shadow-2xl animate-fade-in">
        <div class="flex justify-between items-center mb-6">
          <h2 class="modal-title text-xl font-bold flex items-center gap-2">
            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">{{ editingVenue ? 'edit' : 'add_circle' }}</span>
            {{ editingVenue ? 'Edit Venue' : 'Add New Venue' }}
          </h2>
          <button @click="closeModal" class="modal-close-btn transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        <form @submit.prevent="saveVenue" class="space-y-4">
          <div>
            <label class="form-label block text-sm font-medium mb-1">Venue Name *</label>
            <input 
              type="text" 
              v-model="form.venue_name" 
              required 
              placeholder="e.g. BSU Gymnasium" 
              class="form-input w-full rounded-lg px-4 py-2.5 transition-colors"
            />
          </div>
          
          <div>
            <label class="checkbox-label flex items-center gap-3 cursor-pointer transition-colors mt-4">
              <input type="checkbox" v-model="form.is_inside_bsu" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500" />
              <span>This venue is inside BSU campus</span>
            </label>
          </div>

          <div v-if="formError" class="p-3 bg-red-500/10 border border-red-500/20 rounded-lg text-red-500 dark:text-red-400 text-sm mt-4">
            {{ formError }}
          </div>

          <div class="flex justify-end gap-3 mt-8">
            <button type="button" @click="closeModal" class="btn-cancel px-4 py-2 rounded-lg transition-colors font-medium" :disabled="saving">Cancel</button>
            <button type="submit" class="btn-primary" :disabled="saving">
              <span v-if="saving" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ editingVenue ? 'Save Changes' : 'Save Venue' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../api';

const props = defineProps({
  isNested: {
    type: Boolean,
    default: false
  }
});

const venues = ref([]);
const loading = ref(true);
const error = ref('');
const showModal = ref(false);
const editingVenue = ref(null);
const saving = ref(false);
const formError = ref('');

const form = ref({
  venue_name: '',
  is_inside_bsu: true
});

const fetchVenues = async () => {
  loading.value = true;
  error.value = '';
  try {
    const res = await api.get('venues');
    if (Array.isArray(res.data)) {
      venues.value = res.data;
    } else if (res.data?.data && Array.isArray(res.data.data)) {
      venues.value = res.data.data;
    } else if (res.data?.status === 'success' && res.data?.data) {
      venues.value = res.data.data;
    } else {
      error.value = res.data?.message || 'Failed to fetch venues';
    }
  } catch (err) {
    error.value = err.response?.data?.message || err.message || 'Error connecting to server';
  } finally {
    loading.value = false;
  }
};

const openAddModal = () => {
  editingVenue.value = null;
  form.value = {
    venue_name: '',
    is_inside_bsu: true
  };
  formError.value = '';
  showModal.value = true;
};

const openEditModal = (venue) => {
  editingVenue.value = venue;
  form.value = {
    venue_name: venue.venue_name,
    is_inside_bsu: Boolean(venue.is_inside_bsu)
  };
  formError.value = '';
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingVenue.value = null;
};

const saveVenue = async () => {
  saving.value = true;
  formError.value = '';
  try {
    let res;
    if (editingVenue.value) {
      res = await api.put(`venues/${editingVenue.value.venue_id}`, form.value);
    } else {
      res = await api.post('venues', form.value);
    }
    
    if (res.status === 200 || res.status === 201 || res.data?.venue_id || res.data?.success || res.data?.status === 'success') {
      closeModal();
      fetchVenues();
    } else {
      formError.value = res.data?.message || 'Failed to save venue';
    }
  } catch (err) {
    formError.value = err.response?.data?.messages || err.response?.data?.message || 'Error saving venue';
  } finally {
    saving.value = false;
  }
};

const confirmDelete = async (id) => {
  if (confirm('Are you sure you want to delete this venue?')) {
    try {
      const res = await api.delete(`venues/${id}`);
      if (res.status === 200 || res.data?.success || res.data?.status === 'success') {
        fetchVenues();
      } else {
        alert(res.data?.message || 'Failed to delete venue');
      }
    } catch (err) {
      alert(err.response?.data?.message || 'Error deleting venue');
    }
  }
};

onMounted(() => {
  fetchVenues();
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

.name-cell {
  color: #0f172a;
  font-weight: 600;
}

.empty-state {
  color: #64748b;
}

/* Badges */
.badge {
  padding: 4px 12px;
  border-radius: 9999px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
}
.badge-inside {
  background: #dcfce7;
  color: #15803d;
  border: 1px solid #bbf7d0;
}
.badge-outside {
  background: #fef3c7;
  color: #b45309;
  border: 1px solid #fde68a;
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

.checkbox-label {
  color: #334155;
  font-weight: 500;
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
   Dark Mode Overrides for Venues Management
   Outer background remains white; ONLY the cards and modals darken!
   ========================================================================== */
html.dark .glass-card,
.dark .glass-card {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border-color: rgba(185, 121, 204, 0.25) !important;
  box-shadow: 0 20px 40px rgba(0,0,0,0.25) !important;
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

html.dark .name-cell,
.dark .name-cell {
  color: #f8fafc !important;
}

html.dark .empty-state,
.dark .empty-state {
  color: #94a3b8 !important;
}

html.dark .badge-inside,
.dark .badge-inside {
  background: rgba(34, 197, 94, 0.15) !important;
  color: #86efac !important;
  border: 1px solid rgba(134, 239, 172, 0.3) !important;
}

html.dark .badge-outside,
.dark .badge-outside {
  background: rgba(245, 158, 11, 0.15) !important;
  color: #fde047 !important;
  border: 1px solid rgba(253, 224, 71, 0.3) !important;
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

html.dark .checkbox-label,
.dark .checkbox-label {
  color: #cbd5e1 !important;
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
