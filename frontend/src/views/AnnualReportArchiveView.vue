<template>
  <main class="flex-1 overflow-y-auto bg-slate-900 h-full min-h-screen text-slate-100">
    <div class="max-w-7xl mx-auto p-6">
      <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-slate-100">Annual GAD Accomplishment Report Archives</h1>
        <button @click="$router.back()" class="bg-slate-800 text-slate-300 px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors">
          &larr; Back to Reports
        </button>
      </div>

      <div class="bg-slate-800 rounded-xl shadow-md border border-slate-700 p-6 mb-6">
        <div class="flex flex-wrap gap-4 items-end">
          <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-purple-400 mb-1 uppercase tracking-wider">Fiscal Year</label>
            <select v-model="filters.fiscalYear" class="w-full p-2 border border-slate-600 rounded-lg bg-slate-900 text-slate-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
              <option value="all">All Years</option>
              <option v-for="year in availableFiscalYears" :key="year" :value="year">{{ year }}</option>
            </select>
          </div>
        </div>
      </div>

      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500 mx-auto"></div>
        <p class="mt-4 text-slate-400">Loading archived reports...</p>
      </div>

      <div v-else class="bg-slate-800 rounded-xl shadow-md border border-slate-700 overflow-hidden">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-900/50 border-b border-slate-700">
              <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">ID</th>
              <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Fiscal Year</th>
              <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Archived Date</th>
              <th class="p-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="filteredItems.length === 0" class="border-b border-slate-700">
              <td colspan="4" class="p-8 text-center text-slate-400">
                <span class="text-4xl mb-2 block">📭</span>
                No archived reports found.
              </td>
            </tr>
            <tr v-for="item in filteredItems" :key="item.id" class="border-b border-slate-700 hover:bg-slate-700/50 transition-colors">
              <td class="p-4 text-sm text-slate-200">#{{ item.id }}</td>
              <td class="p-4">
                <span class="bg-purple-900/50 text-purple-300 text-xs font-bold px-2 py-1 rounded-full">{{ item.fiscal_year }}</span>
              </td>
              <td class="p-4 text-sm text-slate-400">{{ new Date(item.created_at).toLocaleString() }}</td>
              <td class="p-4">
                <button @click="viewReport(item.id)" class="text-purple-400 hover:text-purple-300 font-medium text-sm bg-purple-900/30 hover:bg-purple-900/50 px-3 py-1 rounded transition-colors">
                  View Report
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';

const router = useRouter();
const loading = ref(true);
const reports = ref([]);
const filters = ref({
  fiscalYear: 'all'
});

const availableFiscalYears = computed(() => {
  const years = new Set(reports.value.map(r => r.fiscal_year));
  return Array.from(years).sort((a, b) => b - a);
});

const filteredItems = computed(() => {
  let items = [...reports.value];
  if (filters.value.fiscalYear !== 'all') {
    items = items.filter(r => r.fiscal_year === filters.value.fiscalYear);
  }
  return items;
});

const fetchArchives = async () => {
  loading.value = true;
  try {
    const response = await api.get('annual-reports/archive');
    if (response.data && response.data.success) {
      reports.value = response.data.data;
    }
  } catch (error) {
    console.error('Error fetching archives:', error);
  } finally {
    loading.value = false;
  }
};

const viewReport = (id) => {
  router.push(`${router.currentRoute.value.path.includes('/admin') ? '/admin' : '/staff'}/annual-report-view/${id}`);
};

onMounted(() => {
  fetchArchives();
});
</script>
