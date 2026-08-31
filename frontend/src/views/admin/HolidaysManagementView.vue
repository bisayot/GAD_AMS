<template>
  <div class="p-6 bg-slate-900 text-slate-200 rounded-xl">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h2 class="text-xl font-bold text-white">Holidays & Working Days</h2>
        <p class="text-sm text-slate-400">Manage non-working holidays to accurately calculate activity lead times.</p>
      </div>
      <div class="flex gap-2">
        <select v-model="selectedYear" class="px-4 py-2 bg-slate-800 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none w-32" @change="fetchHolidays">
          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
        </select>
        <button @click="syncHolidays" class="flex items-center gap-2 px-4 py-2 bg-slate-800 border border-slate-700 text-slate-200 rounded-lg hover:bg-slate-700 transition-colors shadow-sm" :disabled="syncing">
          <span class="material-symbols-outlined text-sm" :class="{'animate-spin': syncing}">sync</span>
          {{ syncing ? 'Syncing...' : 'Auto-Sync' }}
        </button>
        <button @click="openModal()" class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
          <span class="material-symbols-outlined text-sm">add</span>
          Add Holiday
        </button>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-slate-800 rounded-xl shadow-sm border border-slate-700 overflow-hidden">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr class="bg-slate-800/50 border-b border-slate-700">
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Date</th>
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Holiday Name</th>
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Type</th>
            <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading" class="border-b border-slate-700">
            <td colspan="4" class="p-8 text-center text-slate-500">Loading holidays...</td>
          </tr>
          <tr v-else-if="holidays.length === 0" class="border-b border-slate-700">
            <td colspan="4" class="p-8 text-center text-slate-500">No holidays found for this year.</td>
          </tr>
          <tr v-for="holiday in holidays" :key="holiday.id" class="border-b border-slate-700 hover:bg-slate-700/50 transition-colors">
            <td class="p-4 text-sm font-medium text-slate-200">
              {{ new Date(holiday.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', weekday: 'short' }) }}
            </td>
            <td class="p-4 text-sm text-slate-300">{{ holiday.name }}</td>
            <td class="p-4">
              <span class="px-2.5 py-1 rounded-full text-xs font-medium" :class="getTypeBadgeClass(holiday.type)">
                {{ holiday.type }}
              </span>
            </td>
            <td class="p-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <button @click="openModal(holiday)" class="w-8 h-8 flex items-center justify-center text-white hover:text-purple-400 hover:bg-purple-900/50 rounded-lg transition-colors">
                  <span class="material-symbols-outlined text-[18px] text-white">edit</span>
                </button>
                <button @click="deleteHoliday(holiday.id)" class="w-8 h-8 flex items-center justify-center text-white hover:text-red-400 hover:bg-red-900/50 rounded-lg transition-colors">
                  <span class="material-symbols-outlined text-[18px] text-white">delete</span>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4">
      <div class="bg-slate-800 rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-fade-in-up border border-slate-700">
        <div class="px-6 py-4 border-b border-slate-700 flex justify-between items-center bg-slate-800/50">
          <h3 class="text-lg font-bold text-white">{{ isEditing ? 'Edit Holiday' : 'Add Holiday' }}</h3>
          <button @click="closeModal" class="text-slate-400 hover:text-slate-200 transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        
        <form @submit.prevent="saveHoliday" class="p-6">
          <div class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Holiday Date</label>
              <VueDatePicker v-model="form.date" dark :disabled-dates="isDisabledDate" model-type="yyyy-MM-dd" :enable-time-picker="false" format="MM/dd/yyyy" auto-apply required input-class-name="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all text-sm">
                <template #dp-input="{ value }">
                  <input type="text" :value="value ? String(value).replace(',', '').trim().split(' ')[0] : ''" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all text-sm" readonly placeholder="Select Date" required />
                </template>
              </VueDatePicker>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Holiday Name</label>
              <input type="text" v-model="form.name" required placeholder="e.g. Independence Day" class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all text-sm placeholder-slate-500">
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Type</label>
              <select v-model="form.type" required class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 text-white rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all text-sm">
                <option value="public">Public / National</option>
                <option value="school">School / Local</option>
                <option value="custom">Custom / Special Non-Working</option>
              </select>
            </div>
          </div>
          
          <div class="mt-8 flex gap-3">
            <button type="button" @click="closeModal" class="flex-1 px-4 py-2.5 border border-slate-700 text-slate-300 rounded-lg hover:bg-slate-700 transition-colors font-medium text-sm">Cancel</button>
            <button type="submit" class="flex-1 px-4 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium text-sm shadow-sm" :disabled="saving">
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
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import api from '../../api';

const holidays = ref([]);
const loading = ref(false);
const syncing = ref(false);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);

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
      await api.put(/holidays/, form.value);
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
    confirmButtonText: 'Yes, delete it',
    background: '#1e293b',
    color: '#fff'
  });

  if (result.isConfirmed) {
    try {
      await api.delete(/holidays/);
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
    case 'public': return 'bg-blue-500/20 text-blue-400';
    case 'school': return 'bg-purple-500/20 text-purple-400';
    case 'custom': return 'bg-emerald-500/20 text-emerald-400';
    default: return 'bg-slate-500/20 text-slate-400';
  }
};

onMounted(() => {
  fetchHolidays();
});
</script>
