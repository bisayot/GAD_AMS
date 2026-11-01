<template>
  <div class="min-h-screen bg-slate-50 p-6 text-slate-900 transition-colors dark:bg-slate-950 dark:text-slate-100 md:p-10">
    <div class="mx-auto max-w-6xl">
      <div class="mb-8 flex items-center justify-between">
        <div>
          <p class="text-sm font-semibold uppercase tracking-wide text-purple-600 dark:text-purple-300">Plan &amp; Budget</p>
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Budget History</h1>
          <p class="mt-1 text-slate-600 dark:text-slate-400">Read-only versions retained after reset, import, or restore.</p>
        </div>
        <router-link :to="currentPlanPath" class="rounded-lg bg-purple-600 px-4 py-2 font-semibold text-white">
          Current Plan
        </router-link>
      </div>
      <p v-if="error" class="mb-4 rounded-lg bg-red-50 p-4 text-red-700 dark:bg-red-950/50 dark:text-red-300">{{ error }}</p>
      <div class="mb-5 flex flex-wrap gap-3">
        <input v-model="search" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder:text-slate-400 dark:border-slate-700 dark:bg-slate-900 dark:text-white" placeholder="Search fiscal year or source">
        <select v-model="statusFilter" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white"><option value="">All statuses</option><option value="active">Active</option><option value="closed">Closed</option></select>
      </div>
      <div class="grid gap-4 md:grid-cols-2">
        <button v-for="version in pagedVersions" :key="version.id" class="rounded-xl border border-slate-200 bg-white p-5 text-left shadow-sm transition-colors hover:border-purple-400 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-purple-500" @click="open(version.id)">
          <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">FY {{ version.fiscal_year }} · Version {{ version.version_number }}</h2>
            <span class="rounded-full px-3 py-1 text-xs font-semibold" :class="version.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600'">{{ version.status }}</span>
          </div>
          <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ version.item_count }} GPB items · {{ version.source }}</p>
          <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">{{ formatDate(version.created_at) }}</p>
        </button>
      </div>
      <div v-if="filteredVersions.length" class="mt-5 flex items-center justify-between">
        <button class="rounded border border-slate-300 px-3 py-1 dark:border-slate-700 dark:bg-slate-900 disabled:opacity-40" :disabled="page === 1" @click="page--">Previous</button>
        <span class="text-sm text-slate-500 dark:text-slate-400">Page {{ page }} of {{ pageCount }}</span>
        <button class="rounded border border-slate-300 px-3 py-1 dark:border-slate-700 dark:bg-slate-900 disabled:opacity-40" :disabled="page === pageCount" @click="page++">Next</button>
      </div>
      <div v-if="selected" class="mt-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">FY {{ selected.fiscal_year }} · Version {{ selected.version_number }}</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Historical view only. Restoring creates a new active version and does not modify this record.</p>
        <button class="mt-4 inline-flex items-center gap-2 rounded-lg bg-amber-500 px-5 py-3 font-bold text-white shadow-lg shadow-amber-500/30 ring-2 ring-amber-300 transition hover:bg-amber-600 hover:shadow-amber-500/50 focus:outline-none focus:ring-4 focus:ring-amber-300 dark:bg-amber-400 dark:text-slate-950 dark:ring-amber-200 dark:hover:bg-amber-300 dark:focus:ring-amber-200" @click="restore">
          <span aria-hidden="true">↻</span>
          Restore as New Active Version
        </button>
        <div v-if="selected.monitoring_snapshot" class="mt-5 grid gap-3 sm:grid-cols-4">
          <div class="rounded-lg bg-slate-100 p-3 dark:bg-slate-800"><div class="text-xs text-slate-500 dark:text-slate-400">Total Budget</div><div class="font-bold">{{ money(selected.monitoring_snapshot.total_budget) }}</div></div>
          <div class="rounded-lg bg-slate-100 p-3 dark:bg-slate-800"><div class="text-xs text-slate-500 dark:text-slate-400">Actual Cost</div><div class="font-bold">{{ money(selected.monitoring_snapshot.total_utilized) }}</div></div>
          <div class="rounded-lg bg-slate-100 p-3 dark:bg-slate-800"><div class="text-xs text-slate-500 dark:text-slate-400">Proposed Budget</div><div class="font-bold">{{ money(selected.monitoring_snapshot.total_pending_approved) }}</div></div>
          <div class="rounded-lg bg-slate-100 p-3 dark:bg-slate-800"><div class="text-xs text-slate-500 dark:text-slate-400">Remaining Budget</div><div class="font-bold">{{ money(selected.monitoring_snapshot.remaining_balance) }}</div></div>
        </div>
        <div v-if="selected.linked_documents" class="mt-5 grid gap-4 md:grid-cols-2">
          <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
            <h3 class="font-semibold text-slate-800 dark:text-slate-100">Activity Designs</h3>
            <p v-if="!selected.linked_documents.activity_designs.length" class="mt-2 text-sm text-slate-500 dark:text-slate-400">No linked Activity Designs.</p>
            <div v-for="document in selected.linked_documents.activity_designs" :key="document.act_design_id" class="mt-2 border-t border-slate-200 pt-2 text-sm dark:border-slate-700">
              <a class="font-medium text-purple-700 hover:underline" :href="documentPath('ad', document.act_design_id)">{{ document.control_number || 'No control number' }}</a>
              <div class="dark:text-slate-200">{{ document.activity_title }}</div>
              <div class="text-slate-500 dark:text-slate-400">{{ document.status }} · {{ money(document.proposed_budget) }}</div>
            </div>
          </div>
          <div class="rounded-lg border border-slate-200 p-4 dark:border-slate-700">
            <h3 class="font-semibold text-slate-800 dark:text-slate-100">Accomplishment Reports</h3>
            <p v-if="!selected.linked_documents.accomplishment_reports.length" class="mt-2 text-sm text-slate-500 dark:text-slate-400">No linked Accomplishment Reports.</p>
            <div v-for="document in selected.linked_documents.accomplishment_reports" :key="document.id" class="mt-2 border-t border-slate-200 pt-2 text-sm dark:border-slate-700">
              <a class="font-medium text-purple-700 hover:underline" :href="documentPath('ar', document.id)">{{ document.control_number }}</a>
              <div class="dark:text-slate-200">{{ document.activity_title }}</div>
              <div class="text-slate-500 dark:text-slate-400">{{ document.status }} · {{ money(document.proposed_budget) }}</div>
            </div>
          </div>
        </div>
        <div class="mt-5 overflow-x-auto">
          <table class="min-w-full text-left text-sm">
            <thead><tr class="border-b border-slate-200 text-slate-500 dark:border-slate-700"><th class="p-3">Mandate</th><th class="p-3">Activity</th><th class="p-3">Budget</th><th class="p-3">Office</th></tr></thead>
            <tbody><tr v-for="item in selected.items" :key="item.id" class="border-b border-slate-200 last:border-0 dark:border-slate-700"><td class="p-3 dark:text-slate-200">{{ item.mandate }}</td><td class="p-3 dark:text-slate-200">{{ item.activity }}</td><td class="p-3 dark:text-slate-200">{{ money(item.budget) }}</td><td class="p-3 dark:text-slate-200">{{ item.responsible || item.office }}</td></tr></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import Swal from 'sweetalert2';
import api from '../api';

const versions = ref([]);
const selected = ref(null);
const error = ref('');
const search = ref('');
const statusFilter = ref('');
const page = ref(1);
const pageSize = 6;
const role = (JSON.parse(localStorage.getItem('user') || '{}').role || '').toLowerCase();
const currentPlanPath = role === 'admin' ? '/admin/plan-and-budget' : '/staff/plan-and-budget';
const documentBasePath = role === 'admin' ? '/admin' : '/staff';
const filteredVersions = computed(() => versions.value.filter(version => {
  const query = search.value.trim().toLowerCase();
  const matchesQuery = !query || `${version.fiscal_year} ${version.source}`.toLowerCase().includes(query);
  return matchesQuery && (!statusFilter.value || version.status === statusFilter.value);
}));
const pageCount = computed(() => Math.max(1, Math.ceil(filteredVersions.value.length / pageSize)));
const pagedVersions = computed(() => filteredVersions.value.slice((page.value - 1) * pageSize, page.value * pageSize));
watch([search, statusFilter], () => { page.value = 1; });
const documentPath = (type, id) => `${documentBasePath}/${type === 'ad' ? 'ad-view' : 'ar-view'}/${id}`;

const formatDate = value => value ? new Date(value).toLocaleString() : '';
const money = value => Number(value || 0).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });
const open = async id => {
  try {
    selected.value = (await api.get(`/budget-history/${id}`)).data;
  } catch (e) {
    const message = e.response?.data?.message || 'Unable to load this budget version.';
    error.value = message;
    await Swal.fire('Unable to load version', message, 'error');
  }
};
const restore = async () => {
  if (!selected.value) return;
  const result = await Swal.fire({
    title: 'Restore this version?',
    text: 'This will create a new active version. The historical version will remain unchanged.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Restore as New Version',
    cancelButtonText: 'Cancel',
    reverseButtons: true,
  });
  if (!result.isConfirmed) return;

  try {
    await api.post(`/budget-history/${selected.value.id}/restore`);
    await Swal.fire({
      title: 'Version restored',
      html: '<p>The historical version was restored as a new active version.</p><p style="margin-top: 12px;"><b>Please double-check the fiscal year, Total Budget / GAA of Organization, monitoring values, and all other details before continuing.</b></p>',
      icon: 'success',
      confirmButtonText: 'Open Current Plan',
    });
    window.location.assign(`${currentPlanPath}?fiscal_year=${selected.value.fiscal_year}`);
  } catch (e) {
    const message = e.response?.data?.message || 'Unable to restore this version.';
    error.value = message;
    await Swal.fire('Restore failed', message, 'error');
  }
};
onMounted(async () => {
  try { versions.value = (await api.get('/budget-history')).data; }
  catch (e) {
    const message = e.response?.data?.message || 'Budget History is available only to the Director and GAD Staff.';
    error.value = message;
    await Swal.fire('Budget History unavailable', message, 'error');
  }
});
</script>
