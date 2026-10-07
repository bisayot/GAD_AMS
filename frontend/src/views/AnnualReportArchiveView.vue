<template>
  <main class="archive-main-container flex-1 overflow-y-auto h-full min-h-screen">
    <div class="max-w-7xl mx-auto p-6">
      <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold archive-title">Annual GAD Accomplishment Report Archives</h1>
        <button @click="$router.back()" class="archive-back-btn px-4 py-2 rounded-lg transition-colors font-semibold text-sm">
          &larr; Back to Reports
        </button>
      </div>

      <div class="archive-card rounded-xl shadow-sm border p-6 mb-6">
        <div class="flex flex-wrap gap-4 items-end">
          <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-bold text-purple-600 dark:text-purple-400 mb-1 uppercase tracking-wider">Fiscal Year</label>
            <select v-model="filters.fiscalYear" class="archive-select w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 font-medium text-sm">
              <option value="all">All Years</option>
              <option v-for="year in availableFiscalYears" :key="year" :value="year">{{ year }}</option>
            </select>
          </div>
        </div>
      </div>

      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500 mx-auto"></div>
        <p class="mt-4 text-slate-500 dark:text-slate-400 text-sm">Loading archived reports...</p>
      </div>

      <div v-else class="archive-card rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="archive-thead-row border-b">
              <th class="p-4 text-xs font-bold uppercase tracking-wider">ID</th>
              <th class="p-4 text-xs font-bold uppercase tracking-wider">Fiscal Year</th>
              <th class="p-4 text-xs font-bold uppercase tracking-wider">Archived Date</th>
              <th class="p-4 text-xs font-bold uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="filteredItems.length === 0" class="border-b border-slate-200 dark:border-slate-700">
              <td colspan="4" class="p-8 text-center text-slate-500 dark:text-slate-400">
                <span class="text-4xl mb-2 block">📭</span>
                No archived reports found.
              </td>
            </tr>
            <tr v-for="item in filteredItems" :key="item.id" class="archive-row border-b transition-colors">
              <td class="p-4 text-sm font-semibold">#{{ item.id }}</td>
              <td class="p-4">
                <span class="bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300 text-xs font-bold px-2.5 py-1 rounded-full">{{ item.fiscal_year }}</span>
              </td>
              <td class="p-4 text-sm text-slate-500 dark:text-slate-400">{{ new Date(item.created_at).toLocaleString() }}</td>
              <td class="p-4">
                <button @click="viewReport(item.id)" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-bold text-sm bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/30 dark:hover:bg-purple-900/50 px-3 py-1.5 rounded-lg transition-colors border border-purple-200 dark:border-transparent">
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

<style scoped>
.archive-main-container {
  background: #ffffff;
  color: #0f172a;
}
.archive-title {
  color: #0f172a;
}
.archive-back-btn {
  background: #ffffff;
  color: #475569;
  border: 1px solid #cbd5e1;
}
.archive-back-btn:hover {
  background: #f8fafc;
  color: #0f172a;
}
.archive-card {
  background: #ffffff;
  border-color: #e2e8f0;
}
.archive-select {
  background: #f8fafc;
  border-color: #cbd5e1;
  color: #0f172a;
}
.archive-thead-row {
  background: #f8fafc;
  border-color: #e2e8f0;
  color: #64748b;
}
.archive-row {
  border-color: #f1f5f9;
  color: #0f172a;
}
.archive-row:hover {
  background: #f8fafc;
}
</style>

<style>
html.dark .archive-main-container,
.dark .archive-main-container {
  background: #ffffff !important; /* Outer page remains white */
}
html.dark .archive-title,
.dark .archive-title {
  color: #0f172a !important;
}
html.dark .archive-back-btn,
.dark .archive-back-btn {
  background: #1e293b !important;
  color: #cbd5e1 !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
}
html.dark .archive-back-btn:hover,
.dark .archive-back-btn:hover {
  background: #334155 !important;
  color: #ffffff !important;
}
html.dark .archive-card,
.dark .archive-card {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border-color: rgba(185, 121, 204, 0.25) !important;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25) !important;
}
html.dark .archive-select,
.dark .archive-select {
  background: rgba(0, 0, 0, 0.4) !important;
  border-color: rgba(185, 121, 204, 0.4) !important;
  color: #ffffff !important;
}
html.dark .archive-thead-row,
.dark .archive-thead-row {
  background: rgba(0, 0, 0, 0.3) !important;
  border-color: rgba(185, 121, 204, 0.2) !important;
  color: #cbd5e1 !important;
}
html.dark .archive-row,
.dark .archive-row {
  border-color: rgba(185, 121, 204, 0.15) !important;
  color: #ffffff !important;
}
html.dark .archive-row:hover,
.dark .archive-row:hover {
  background: rgba(255, 255, 255, 0.05) !important;
}
</style>
