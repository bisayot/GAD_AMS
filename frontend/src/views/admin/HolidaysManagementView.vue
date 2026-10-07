<template>
  <div class="holiday-card p-6 rounded-2xl shadow-sm border transition-colors">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <div>
        <h2 class="section-title text-xl font-bold">Holidays & Working Days</h2>
        <p class="section-subtitle text-sm">Manage non-working holidays to accurately calculate activity lead times.</p>
      </div>
      <div class="flex flex-wrap gap-2 w-full sm:w-auto">
        <select v-model="selectedYear" class="year-select px-4 py-2 rounded-lg outline-none w-32 cursor-pointer transition-colors" @change="fetchHolidays">
          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
        </select>
        <button @click="syncHolidays" class="btn-sync flex items-center gap-2 px-4 py-2 rounded-lg transition-colors shadow-sm" :disabled="syncing">
          <span class="material-symbols-outlined text-sm" :class="{'animate-spin': syncing}">sync</span>
          {{ syncing ? 'Syncing...' : 'Auto-Sync' }}
        </button>
        <button @click="openModal()" class="btn-primary flex items-center gap-2 px-4 py-2 rounded-lg shadow-sm">
          <span class="material-symbols-outlined text-sm">add</span>
          Add Holiday
        </button>
      </div>
    </div>

    <!-- Table -->
    <div class="holiday-table-container rounded-xl shadow-sm border overflow-hidden overflow-x-auto">
      <table class="w-full text-left border-collapse custom-table">
        <thead>
          <tr class="table-header-row border-b">
            <th class="th-cell p-4 text-xs font-bold uppercase tracking-wider">Date</th>
            <th class="th-cell p-4 text-xs font-bold uppercase tracking-wider">Holiday Name</th>
            <th class="th-cell p-4 text-xs font-bold uppercase tracking-wider">Type</th>
            <th class="th-cell p-4 text-xs font-bold uppercase tracking-wider text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading" class="table-row border-b">
            <td colspan="4" class="p-8 text-center empty-state">Loading holidays...</td>
          </tr>
          <tr v-else-if="holidays.length === 0" class="table-row border-b">
            <td colspan="4" class="p-8 text-center empty-state">No holidays found for this year.</td>
          </tr>
          <tr v-for="holiday in holidays" :key="holiday.id" class="table-row border-b transition-colors">
            <td class="td-cell date-cell p-4 text-sm font-medium">
              {{ new Date(holiday.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', weekday: 'short' }) }}
            </td>
            <td class="td-cell name-cell p-4 text-sm">{{ holiday.name }}</td>
            <td class="td-cell p-4">
              <span class="px-2.5 py-1 rounded-full text-xs font-medium" :class="getTypeBadgeClass(holiday.type)">
                {{ holiday.type }}
              </span>
            </td>
            <td class="td-cell p-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <button @click="openModal(holiday)" class="btn-icon btn-icon-edit" title="Edit">
                  <span class="material-symbols-outlined text-[18px]">edit</span>
                </button>
                <button @click="deleteHoliday(holiday.id)" class="btn-icon btn-icon-delete" title="Delete">
                  <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
      <div class="modal-container rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up border">
        <div class="modal-header px-6 py-4 border-b flex justify-between items-center">
          <h3 class="modal-title text-lg font-bold">{{ isEditing ? 'Edit Holiday' : 'Add Holiday' }}</h3>
          <button @click="closeModal" class="modal-close-btn transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        
        <form @submit.prevent="saveHoliday" class="p-6">
          <div class="space-y-4">
            <div>
              <label class="form-label block text-xs font-bold uppercase tracking-wider mb-2">Holiday Date</label>
              <VueDatePicker v-model="form.date" :dark="isDarkMode" :disabled-dates="isDisabledDate" model-type="yyyy-MM-dd" :enable-time-picker="false" format="MM/dd/yyyy" auto-apply required input-class-name="form-input w-full px-4 py-2.5 rounded-lg outline-none transition-all text-sm">
                <template #dp-input="{ value }">
                  <input type="text" :value="value ? String(value).replace(',', '').trim().split(' ')[0] : ''" class="form-input w-full px-4 py-2.5 rounded-lg outline-none transition-all text-sm cursor-pointer" readonly placeholder="Select Date" required />
                </template>
              </VueDatePicker>
            </div>
            <div>
              <label class="form-label block text-xs font-bold uppercase tracking-wider mb-2">Holiday Name</label>
              <input type="text" v-model="form.name" required placeholder="e.g. Independence Day" class="form-input w-full px-4 py-2.5 rounded-lg outline-none transition-all text-sm">
            </div>
            <div>
              <label class="form-label block text-xs font-bold uppercase tracking-wider mb-2">Type</label>
              <select v-model="form.type" required class="form-input w-full px-4 py-2.5 rounded-lg outline-none transition-all text-sm cursor-pointer">
                <option value="public">Public / National</option>
                <option value="school">School / Local</option>
                <option value="custom">Custom / Special Non-Working</option>
              </select>
            </div>
          </div>
          
          <div class="mt-8 flex gap-3">
            <button type="button" @click="closeModal" class="btn-cancel flex-1 px-4 py-2.5 border rounded-lg transition-colors text-sm font-medium">Cancel</button>
            <button type="submit" class="btn-primary flex-1 px-4 py-2.5 rounded-lg transition-colors text-sm font-medium shadow-sm" :disabled="saving">
              {{ saving ? 'Saving...' : 'Save Holiday' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useHolidays } from '../../utils/useHolidays';
const { isDisabledDate } = useHolidays();
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import api from '../../api';

const holidays = ref([]);
const loading = ref(false);
const syncing = ref(false);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);

const isDarkMode = computed(() => {
  if (typeof document !== 'undefined') {
    return document.documentElement.classList.contains('dark');
  }
  return false;
});

const currentYear = new Date().getFullYear();
const selectedYear = ref(currentYear);
const years = Array.from({ length: 5 }, (_, i) => currentYear - 2 + i);

const form = ref({
  id: null,
  date: '',
  name: '',
  type: 'public'
});

const fetchHolidays = async () => {
  loading.value = true;
  try {
    const response = await api.get(`/holidays?year=${selectedYear.value}`);
    if (response.data.status === 'success') {
      holidays.value = response.data.data;
    }
  } catch (error) {
    console.error('Failed to fetch holidays:', error);
  } finally {
    loading.value = false;
  }
};

const syncHolidays = async () => {
  syncing.value = true;
  try {
    const response = await api.post('/holidays/sync', { year: selectedYear.value });
    if (response.data.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: 'Synced!',
        text: 'Philippine holidays have been imported successfully.',
        confirmButtonColor: '#b979cc'
      });
      fetchHolidays();
    }
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Sync Failed',
      text: 'Unable to sync holidays from API.',
      confirmButtonColor: '#b979cc'
    });
  } finally {
    syncing.value = false;
  }
};

const openModal = (holiday = null) => {
  if (holiday) {
    isEditing.value = true;
    form.value = { ...holiday };
  } else {
    isEditing.value = false;
    form.value = { id: null, date: '', name: '', type: 'public' };
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveHoliday = async () => {
  saving.value = true;
  try {
    if (isEditing.value) {
      await api.put(`/holidays/${form.value.id}`, form.value);
    } else {
      await api.post('/holidays', form.value);
    }
    closeModal();
    fetchHolidays();
    Swal.fire({
      icon: 'success',
      title: 'Saved',
      text: 'Holiday saved successfully.',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: error.response?.data?.message || 'Failed to save holiday.',
      confirmButtonColor: '#b979cc'
    });
  } finally {
    saving.value = false;
  }
};

const deleteHoliday = async (id) => {
  const result = await Swal.fire({
    title: 'Delete holiday?',
    text: "This will affect lead time calculations for activities covering this date.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#94a3b8',
    confirmButtonText: 'Yes, delete it!'
  });

  if (result.isConfirmed) {
    try {
      await api.delete(`/holidays/${id}`);
      fetchHolidays();
      Swal.fire({
        icon: 'success',
        title: 'Deleted!',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
    } catch (error) {
      console.error(error);
    }
  }
};

const getTypeBadgeClass = (type) => {
  switch (type) {
    case 'public': return 'badge-public';
    case 'school': return 'badge-school';
    case 'custom': return 'badge-custom';
    default: return 'badge-default';
  }
};

onMounted(() => {
  fetchHolidays();
});
</script>

<style scoped>
.holiday-card {
  background: #ffffff;
  border-color: #e2e8f0;
}

.section-title {
  color: #0f172a;
}
.section-subtitle {
  color: #64748b;
}

.year-select {
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  color: #0f172a;
}
.year-select:focus {
  border-color: #9333ea;
  background: #ffffff;
}

.btn-sync {
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  color: #334155;
}
.btn-sync:hover:not(:disabled) {
  background: #f1f5f9;
  color: #0f172a;
}

.btn-primary {
  background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
  color: white;
  border: 1px solid rgba(255,255,255,0.1);
  box-shadow: 0 4px 12px rgba(147, 51, 234, 0.25);
  cursor: pointer;
}
.btn-primary:hover:not(:disabled) {
  opacity: 0.95;
}

.holiday-table-container {
  background: #ffffff;
  border-color: #e2e8f0;
}

.table-header-row {
  background: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}

.th-cell {
  color: #475569;
}

.table-row {
  border-bottom: 1px solid #f1f5f9;
}
.table-row:hover {
  background-color: #f8fafc;
}

.date-cell {
  color: #0f172a;
}

.name-cell {
  color: #334155;
}

.empty-state {
  color: #64748b;
}

/* Badges */
.badge-public {
  background: #dbeafe;
  color: #1d4ed8;
  border: 1px solid #bfdbfe;
}
.badge-school {
  background: #f3e8ff;
  color: #7e22ce;
  border: 1px solid #e9d5ff;
}
.badge-custom {
  background: #d1fae5;
  color: #047857;
  border: 1px solid #a7f3d0;
}
.badge-default {
  background: #f1f5f9;
  color: #475569;
  border: 1px solid #e2e8f0;
}

/* Action Icon Buttons */
.btn-icon {
  width: 2rem;
  height: 2rem;
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

/* Modal */
.modal-container {
  background: #ffffff;
  border-color: #e2e8f0;
}

.modal-header {
  border-bottom-color: #e2e8f0;
  background: #f8fafc;
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
  color: #475569;
}

.form-input {
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  color: #0f172a;
}
.form-input:focus {
  border-color: #9333ea;
  background: #ffffff;
}

.btn-cancel {
  background: #f1f5f9;
  border-color: #cbd5e1;
  color: #475569;
  cursor: pointer;
}
.btn-cancel:hover {
  background: #e2e8f0;
  color: #0f172a;
}
</style>

<style>
/* ==========================================================================
   Dark Mode Overrides for Holidays Management
   Outer background remains white; ONLY the cards and modals darken!
   ========================================================================== */
html.dark .holiday-card,
.dark .holiday-card {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border-color: rgba(185, 121, 204, 0.25) !important;
  box-shadow: 0 20px 40px rgba(0,0,0,0.25) !important;
}

html.dark .section-title,
.dark .section-title {
  color: #ffffff !important;
}

html.dark .section-subtitle,
.dark .section-subtitle {
  color: #94a3b8 !important;
}

html.dark .year-select,
.dark .year-select {
  background: rgba(0, 0, 0, 0.3) !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
  color: #ffffff !important;
}
html.dark .year-select option,
.dark .year-select option {
  background: #1e293b !important;
  color: #ffffff !important;
}

html.dark .btn-sync,
.dark .btn-sync {
  background: rgba(255, 255, 255, 0.08) !important;
  border-color: rgba(255, 255, 255, 0.15) !important;
  color: #e2e8f0 !important;
}
html.dark .btn-sync:hover:not(:disabled),
.dark .btn-sync:hover:not(:disabled) {
  background: rgba(255, 255, 255, 0.15) !important;
}

html.dark .holiday-table-container,
.dark .holiday-table-container {
  background: rgba(0, 0, 0, 0.2) !important;
  border-color: rgba(185, 121, 204, 0.2) !important;
}

html.dark .table-header-row,
.dark .table-header-row {
  background: rgba(0, 0, 0, 0.25) !important;
  border-bottom: 1px solid rgba(185, 121, 204, 0.2) !important;
}

html.dark .th-cell,
.dark .th-cell {
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

html.dark .date-cell,
.dark .date-cell {
  color: #cbd5e1 !important;
}

html.dark .name-cell,
.dark .name-cell {
  color: #f8fafc !important;
}

html.dark .empty-state,
.dark .empty-state {
  color: #94a3b8 !important;
}

html.dark .badge-public,
.dark .badge-public {
  background: rgba(59, 130, 246, 0.2) !important;
  color: #60a5fa !important;
  border-color: rgba(59, 130, 246, 0.3) !important;
}

html.dark .badge-school,
.dark .badge-school {
  background: rgba(168, 85, 247, 0.2) !important;
  color: #c084fc !important;
  border-color: rgba(168, 85, 247, 0.3) !important;
}

html.dark .badge-custom,
.dark .badge-custom {
  background: rgba(16, 185, 129, 0.2) !important;
  color: #34d399 !important;
  border-color: rgba(16, 185, 129, 0.3) !important;
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
</style>
