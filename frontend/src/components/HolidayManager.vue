<template>
  <div class="settings-card mt-6">
    <div class="card-header flex justify-between items-center">
      <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-orange-400">event</span>
        <h2 class="card-title">Holiday Management</h2>
      </div>
      
      <button @click="syncHolidays" class="btn-primary py-2 px-4 text-sm" :disabled="isSyncing">
        <span v-if="isSyncing" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
        <span v-else class="material-symbols-outlined text-sm mr-2">sync</span>
        {{ isSyncing ? 'Syncing...' : `Sync ${currentYear} Holidays` }}
      </button>
    </div>
    
    <p class="text-slate-400 text-sm mb-6">
      Manage holidays to exclude them from date selections. Weekends are automatically excluded.
    </p>

    <!-- Add Custom Holiday Form -->
    <form @submit.prevent="addHoliday" class="flex gap-4 items-end mb-6 bg-slate-800/50 p-4 rounded-lg border border-slate-700/50">
      <div class="input-wrapper flex-1">
        <label class="input-label text-xs">Date</label>
        <input type="date" v-model="newHoliday.date" class="custom-input py-2" required />
      </div>
      <div class="input-wrapper flex-[2]">
        <label class="input-label text-xs">Holiday Name</label>
        <input type="text" v-model="newHoliday.name" class="custom-input py-2" placeholder="e.g. Foundation Day" required />
      </div>
      <div class="input-wrapper flex-1">
        <label class="input-label text-xs">Type</label>
        <select v-model="newHoliday.type" class="custom-input py-2" required>
          <option value="public">Public</option>
          <option value="school">School</option>
          <option value="custom">Custom</option>
        </select>
      </div>
      <button type="submit" class="btn-primary py-2 px-4 whitespace-nowrap" :disabled="isAdding">
        {{ isAdding ? 'Adding...' : 'Add Holiday' }}
      </button>
    </form>

    <!-- Holidays Table -->
    <div class="overflow-x-auto rounded-lg border border-slate-700">
      <table class="w-full text-left text-sm text-slate-300">
        <thead class="bg-slate-800 text-xs uppercase text-slate-400">
          <tr>
            <th class="px-4 py-3">Date</th>
            <th class="px-4 py-3">Name</th>
            <th class="px-4 py-3">Type</th>
            <th class="px-4 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="isLoading" class="border-b border-slate-700">
            <td colspan="4" class="px-4 py-8 text-center text-slate-500">Loading holidays...</td>
          </tr>
          <tr v-else-if="holidays.length === 0" class="border-b border-slate-700">
            <td colspan="4" class="px-4 py-8 text-center text-slate-500">No holidays found. Click Sync to fetch public holidays.</td>
          </tr>
          <tr v-else v-for="holiday in holidays" :key="holiday.id" class="border-b border-slate-700 hover:bg-slate-800/50 transition-colors">
            <td class="px-4 py-3 font-medium text-slate-200">
              {{ formatDate(holiday.date) }}
            </td>
            <td class="px-4 py-3">{{ holiday.name }}</td>
            <td class="px-4 py-3">
              <span class="px-2 py-1 rounded-full text-xs font-medium" 
                :class="{
                  'bg-green-500/20 text-green-400': holiday.type === 'public',
                  'bg-blue-500/20 text-blue-400': holiday.type === 'school',
                  'bg-purple-500/20 text-purple-400': holiday.type === 'custom'
                }">
                {{ holiday.type }}
              </span>
            </td>
            <td class="px-4 py-3 text-right">
              <button @click="deleteHoliday(holiday.id)" class="text-red-400 hover:text-red-300 hover:bg-red-400/10 p-1.5 rounded transition-colors" title="Delete">
                <span class="material-symbols-outlined text-sm">delete</span>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../api';
import Swal from 'sweetalert2';
import { useHolidays } from '../utils/useHolidays';

const currentYear = new Date().getFullYear();
const { fetchHolidays: fetchHolidaysGlobal } = useHolidays();

const holidays = ref([]);
const isLoading = ref(true);
const isSyncing = ref(false);
const isAdding = ref(false);

const newHoliday = ref({
  date: '',
  name: '',
  type: 'school'
});

const fetchHolidays = async () => {
  isLoading.value = true;
  try {
    const res = await api.get(`/holidays?year=${currentYear}`);
    if (res.data.status === 'success') {
      holidays.value = res.data.data;
    }
  } catch (error) {
    console.error("Failed to fetch holidays", error);
  } finally {
    isLoading.value = false;
  }
};

const syncHolidays = async () => {
  isSyncing.value = true;
  try {
    const res = await api.post('/holidays/sync', { year: currentYear });
    if (res.data.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: 'Synced!',
        text: res.data.message,
        background: '#1e293b',
        color: '#fff',
        confirmButtonColor: '#9333ea'
      });
      await fetchHolidays();
      await fetchHolidaysGlobal(currentYear); // Update global state
    }
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Sync Failed',
      text: error.response?.data?.message || 'An error occurred during sync.',
      background: '#1e293b',
      color: '#fff'
    });
  } finally {
    isSyncing.value = false;
  }
};

const addHoliday = async () => {
  isAdding.value = true;
  try {
    const res = await api.post('/holidays/create', newHoliday.value);
    if (res.data.status === 'success') {
      newHoliday.value = { date: '', name: '', type: 'school' };
      await fetchHolidays();
      await fetchHolidaysGlobal(currentYear);
    }
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: error.response?.data?.message || 'Failed to add holiday. Check if date already exists.',
      background: '#1e293b',
      color: '#fff'
    });
  } finally {
    isAdding.value = false;
  }
};

const deleteHoliday = async (id) => {
  const result = await Swal.fire({
    title: 'Delete Holiday?',
    text: "This action cannot be undone.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Yes, delete it',
    background: '#1e293b',
    color: '#fff'
  });

  if (result.isConfirmed) {
    try {
      await api.delete(`/holidays/delete/${id}`);
      await fetchHolidays();
      await fetchHolidaysGlobal(currentYear);
    } catch (error) {
      Swal.fire('Error', 'Failed to delete holiday.', 'error');
    }
  }
};

const formatDate = (dateStr) => {
  const options = { weekday: 'short', month: 'short', day: 'numeric' };
  return new Date(dateStr).toLocaleDateString('en-PH', options);
};

onMounted(() => {
  fetchHolidays();
});
</script>

<style scoped>
.custom-input {
  background: rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(147, 51, 234, 0.2);
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  transition: all 0.2s ease;
  outline: none;
  width: 100%;
}
.custom-input:focus {
  border-color: #c084fc;
  box-shadow: 0 0 0 2px rgba(192, 132, 252, 0.2);
}
</style>
