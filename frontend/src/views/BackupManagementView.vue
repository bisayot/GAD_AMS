<template>
  <section class="backup-management-page max-w-7xl mx-auto space-y-8 rounded-3xl bg-slate-50 p-1 text-slate-900 transition-colors dark:bg-white dark:text-slate-900">
    <header class="backup-management-header flex flex-col gap-5 bg-slate-50 md:flex-row md:items-end md:justify-between dark:bg-white">
      <div>
        <div class="flex items-center gap-3 text-purple-600 mb-3">
          <span class="material-symbols-outlined text-3xl">cloud_sync</span>
          <span class="text-sm font-black uppercase tracking-[0.18em]">System & Controls</span>
        </div>
        <h1 class="text-3xl md:text-4xl font-black text-slate-900">Backup Management</h1>
        <p class="mt-2 max-w-2xl text-slate-500">Create and manage MySQL database dumps and uploaded documents stored in the restricted GAD-AMS Google Drive backup folder.</p>
      </div>
      <div class="flex items-center gap-3">
      <button type="button" class="backup-action-button inline-flex items-center gap-2 rounded-xl border border-purple-200 bg-white px-4 py-3 font-bold text-purple-700 shadow-sm transition hover:bg-purple-50 disabled:cursor-not-allowed" :disabled="operationLoading" @click="openLocalRestore">
        <span class="material-symbols-outlined">upload_file</span>
        Restore local backup
      </button>
      <button
        class="backup-action-button inline-flex items-center justify-center gap-2 rounded-xl bg-purple-600 px-5 py-3 font-bold text-white shadow-lg shadow-purple-600/20 transition hover:bg-purple-700 disabled:cursor-not-allowed"
        :disabled="creating || operationLoading"
        @click="createBackup"
      >
        <span class="material-symbols-outlined" :class="{ 'animate-spin': creating }">{{ creating ? 'progress_activity' : 'backup' }}</span>
        {{ creating ? 'Creating backup...' : 'Create backup now' }}
      </button>
      </div>
    </header>

    <div v-if="errorMessage" class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-200">{{ errorMessage }}</div>
    <div v-if="successMessage" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-200">{{ successMessage }}</div>

    <div class="grid gap-5 md:grid-cols-3">
      <div class="backup-stat-card rounded-2xl border border-purple-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-[#111827]">
        <span class="material-symbols-outlined rounded-xl bg-purple-100 p-3 text-purple-700 dark:bg-purple-950/70 dark:text-purple-300">cloud_done</span>
        <p class="mt-5 text-sm font-bold text-slate-500 dark:text-slate-400">Connection</p>
        <p class="mt-1 text-xl font-black text-slate-900 dark:text-white">{{ loading ? 'Checking...' : 'Google Drive' }}</p>
      </div>
      <div class="backup-stat-card rounded-2xl border border-purple-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-[#111827]">
        <span class="material-symbols-outlined rounded-xl bg-emerald-100 p-3 text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-300">backup</span>
        <p class="mt-5 text-sm font-bold text-slate-500 dark:text-slate-400">Available backups</p>
        <p class="mt-1 text-xl font-black text-slate-900 dark:text-white">{{ backups.length }}</p>
      </div>
      <div class="backup-stat-card rounded-2xl border border-purple-100 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-[#111827]">
        <span class="material-symbols-outlined rounded-xl bg-amber-100 p-3 text-amber-700 dark:bg-amber-950/70 dark:text-amber-300">schedule</span>
        <p class="mt-5 text-sm font-bold text-slate-500 dark:text-slate-400">Latest backup</p>
        <p class="mt-1 text-xl font-black text-slate-900 dark:text-white">{{ latestBackup ? formatDate(latestBackup.createdTime) : 'None yet' }}</p>
      </div>
    </div>

    <div class="backup-list-card overflow-hidden rounded-2xl border border-purple-100 bg-white shadow-sm dark:border-slate-800 dark:bg-[#111827]">
      <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 dark:border-slate-700">
        <div>
          <h2 class="text-xl font-black text-slate-900 dark:text-white">Google Drive backups</h2>
          <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">ZIP backups include the MySQL dump and uploaded files. Legacy JSON backups remain available.</p>
        </div>
        <button class="rounded-lg p-2 text-slate-500 transition hover:bg-purple-50 hover:text-purple-700 disabled:cursor-not-allowed disabled:opacity-50 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-purple-300" title="Refresh backups" :disabled="operationLoading" @click="loadBackups">
          <span class="material-symbols-outlined" :class="{ 'animate-spin': loading }">refresh</span>
        </button>
      </div>
      <div v-if="loading" class="p-10 text-center text-slate-500 dark:text-slate-400">Loading backups...</div>
      <div v-else-if="backups.length === 0" class="p-12 text-center">
        <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600">cloud_off</span>
        <p class="mt-3 font-bold text-slate-600 dark:text-slate-300">No backups yet</p>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Create the first snapshot to connect this system to Google Drive.</p>
      </div>
      <div v-else class="divide-y divide-slate-100 dark:divide-slate-700">
        <div v-for="backup in backups" :key="backup.id" class="flex flex-col gap-4 px-6 py-5 md:flex-row md:items-center md:justify-between dark:bg-[#111827] dark:hover:bg-[#172033]">
          <div class="flex items-center gap-4">
            <span class="material-symbols-outlined rounded-xl bg-purple-50 p-3 text-purple-600 dark:bg-purple-950/70 dark:text-purple-300">description</span>
            <div>
              <p class="font-bold text-slate-800 dark:text-slate-100">{{ backup.name }}</p>
              <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ formatDate(backup.createdTime) }} · {{ formatSize(backup.size) }}</p>
              <p v-if="backup.created_by_email" class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                Created by {{ backup.created_by_email }}<span v-if="backup.created_by_role"> · {{ backup.created_by_role }}</span>
              </p>
            </div>
          </div>
          <div class="backup-row-actions flex flex-wrap items-center gap-2 rounded-xl dark:bg-slate-800/80 dark:p-1">
            <button class="backup-row-action backup-row-action-download rounded-lg px-3 py-2 text-sm font-bold text-slate-900 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-800" :aria-disabled="operationLoading" @click="!operationLoading && downloadBackup(backup)">Download</button>
            <button v-if="backup.name.toLowerCase().endsWith('.zip')" class="backup-row-action backup-row-action-restore rounded-lg px-3 py-2 text-sm font-bold text-slate-900 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-800" :aria-disabled="operationLoading" @click="!operationLoading && openRestore(backup)">Restore</button>
            <a v-if="backup.webViewLink" :href="operationLoading ? undefined : backup.webViewLink" target="_blank" rel="noopener" class="backup-row-action backup-row-action-drive rounded-lg px-3 py-2 text-sm font-bold text-slate-900 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-800" :aria-disabled="operationLoading" @click="operationLoading && $event.preventDefault()">Open in Drive</a>
            <button class="backup-row-action backup-row-action-delete rounded-lg px-3 py-2 text-sm font-bold text-slate-900 hover:bg-slate-100 dark:text-white dark:hover:bg-slate-800" :aria-disabled="operationLoading" @click="!operationLoading && removeBackup(backup)">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="restoreBackup" class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-950/60 p-4" @click.self="closeRestore">
      <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-900">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white">Restore backup</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ restoreBackup.name }}</p>
          </div>
          <button class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-100" @click="closeRestore"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div v-if="restorePreview" class="mt-5 rounded-xl bg-purple-50 p-4 text-sm text-slate-700 dark:bg-purple-950/40 dark:text-slate-200">
          <p><strong>Backup:</strong> {{ restorePreview.created_at }}</p>
          <p><strong>Database:</strong> {{ formatSize(restorePreview.database_size) }}</p>
          <p><strong>Files:</strong> {{ restorePreview.file_count }}</p>
        </div>
        <div v-else class="mt-5 rounded-xl bg-slate-50 p-4 text-sm text-slate-500 dark:bg-slate-800 dark:text-slate-300">{{ restoreLoading ? 'Validating backup...' : 'Review the strict replacement, then click Go to validate this backup.' }}</div>
        <div class="mt-5 space-y-3">
          <label v-for="option in restoreOptions" :key="option.value" class="flex cursor-pointer items-start gap-3 rounded-xl border p-3" :class="restoreMode === option.value ? 'border-purple-500 bg-purple-50 dark:bg-purple-950/40' : 'border-slate-200 dark:border-slate-700 dark:bg-slate-800/50'">
            <input v-model="restoreMode" type="radio" class="mt-1" :value="option.value" />
            <span><strong class="block text-sm text-slate-800 dark:text-slate-100">{{ option.label }}</strong><small class="text-slate-500 dark:text-slate-400">{{ option.description }}</small></span>
          </label>
        </div>
        <input v-if="requiresRestoreConfirmation" v-model="restoreConfirmation" class="mt-4 w-full rounded-xl border border-red-300 px-4 py-3 text-sm dark:border-red-800 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-400" placeholder="Type STRICT RESTORE to confirm" autocomplete="off" />
        <p v-if="requiresRestoreConfirmation && !confirmationValid" class="mt-2 text-xs font-semibold text-red-600 dark:text-red-300">
          Type {{ requiredRestoreConfirmation }} exactly. Lowercase or mixed-case text is not accepted.
        </p>
        <button v-if="!restorePreview" class="mt-5 w-full rounded-xl bg-purple-600 px-4 py-3 font-bold text-white disabled:cursor-not-allowed disabled:opacity-50" :disabled="restoreLoading || !confirmationValid" @click="previewSelected">
          {{ restoreLoading ? 'Working...' : 'Go' }}
        </button>
        <button v-else class="mt-5 w-full rounded-xl bg-purple-600 px-4 py-3 font-bold text-white disabled:opacity-50" :disabled="restoreLoading || !confirmationValid" @click="restoreSelected">
          {{ restoreLoading ? 'Working...' : 'Restore selected data' }}
        </button>
      </div>
    </div>

    <div v-if="localRestoreOpen" class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-950/60 p-4" @click.self="closeLocalRestore">
      <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-900">
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white">Restore local backup</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Select a ZIP backup saved on this computer.</p>
          </div>
          <button class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-100" :disabled="localRestoreLoading" @click="closeLocalRestore"><span class="material-symbols-outlined">close</span></button>
        </div>
        <input class="mt-5 block w-full rounded-xl border border-slate-200 p-3 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200" type="file" accept=".zip,application/zip" :disabled="localRestoreLoading" @change="selectLocalBackup" />
        <p v-if="localFile" class="mt-3 rounded-xl bg-purple-50 p-3 text-sm font-semibold text-purple-800 dark:bg-purple-950/40 dark:text-purple-200">{{ localFile.name }}</p>
        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-900 dark:bg-red-950/30 dark:text-red-200">
          Strict replacement deletes current database tables and managed uploaded files before restoring the selected ZIP. An automatic safety backup is created in Google Drive first.
        </div>
        <input v-model="localRestoreConfirmation" class="mt-4 w-full rounded-xl border border-red-300 px-4 py-3 text-sm dark:bg-slate-800 dark:text-white" placeholder="Type STRICT RESTORE" autocomplete="off" />
        <p v-if="localRestoreConfirmation !== requiredRestoreConfirmation" class="mt-2 text-xs font-semibold text-red-600 dark:text-red-300">Type STRICT RESTORE exactly.</p>
        <button class="mt-5 w-full rounded-xl bg-purple-600 px-4 py-3 font-bold text-white disabled:cursor-not-allowed disabled:opacity-50" :disabled="localRestoreLoading || !localFile || localRestoreConfirmation !== requiredRestoreConfirmation" @click="restoreLocalBackup">
          {{ localRestoreLoading ? 'Restoring local backup...' : 'Restore local backup' }}
        </button>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import api from '../api';
import Swal from 'sweetalert2';

const backups = ref([]);
const loading = ref(true);
const creating = ref(false);
const operationLoading = ref(false);
const operationMessage = ref('');
const errorMessage = ref('');
const successMessage = ref('');
const latestBackup = computed(() => backups.value[0] || null);
const restoreBackup = ref(null);
const restorePreview = ref(null);
const restoreLoading = ref(false);
const restoreMode = ref('strict-both');
const restoreConfirmation = ref('');
const localRestoreOpen = ref(false);
const localFile = ref(null);
const localRestoreConfirmation = ref('');
const localRestoreLoading = ref(false);
const requiresRestoreConfirmation = computed(() => true);
const requiredRestoreConfirmation = 'STRICT RESTORE';
const confirmationValid = computed(() => restoreConfirmation.value.trim() === requiredRestoreConfirmation);
const restoreOptions = [
  { value: 'strict-both', label: 'Database + files (strict replacement)', description: 'Delete all current database tables and managed upload files, then replace them from the backup. Type STRICT RESTORE.' }
];

const openLocalRestore = () => {
  localRestoreOpen.value = true;
  localFile.value = null;
  localRestoreConfirmation.value = '';
};

const closeLocalRestore = () => {
  if (!localRestoreLoading.value) localRestoreOpen.value = false;
};

const selectLocalBackup = (event) => {
  localFile.value = event.target.files?.[0] || null;
};

const restoreLocalBackup = async () => {
  localRestoreLoading.value = true;
  operationLoading.value = true;
  operationMessage.value = 'Restoring local backup';
  try {
    const formData = new FormData();
    formData.append('backup', localFile.value);
    formData.append('confirmation', localRestoreConfirmation.value);
    await api.post('backups/restore-local', formData, { headers: { 'Content-Type': 'multipart/form-data' } });
    localRestoreOpen.value = false;
    await Swal.fire({ icon: 'success', title: 'Restore complete', text: 'The local backup was restored.', confirmButtonColor: '#9333ea' });
    await loadBackups();
  } catch (error) {
    await Swal.fire({ icon: 'error', title: 'Local restore failed', text: getErrorMessage(error, 'Unable to restore the local backup.'), confirmButtonColor: '#9333ea' });
  } finally {
    localRestoreLoading.value = false;
    operationLoading.value = false;
    operationMessage.value = '';
  }
};

const getErrorMessage = (error, fallback) => {
  if (typeof error === 'string' && error.trim()) return error;
  if (error?.message && error.message !== 'Internal Server Error') return error.message;
  if (error?.error && error.error !== 'Internal Server Error') return error.error;
  if (error?.messages && typeof error.messages === 'object') {
    const message = Object.values(error.messages).find(value => typeof value === 'string' && value.trim());
    if (message) return message;
  }
  return fallback;
};

const loadBackups = async () => {
  loading.value = true;
  operationLoading.value = true;
  operationMessage.value = 'Loading backups from Google Drive';
  errorMessage.value = '';
  try {
    const response = await api.get('backups');
    backups.value = response.data?.data || [];
  } catch (error) {
    errorMessage.value = error?.message || 'Unable to load Google Drive backups.';
  } finally {
    loading.value = false;
    operationLoading.value = false;
    operationMessage.value = '';
  }
};

const createBackup = async () => {
  creating.value = true;
  operationLoading.value = true;
  operationMessage.value = 'Creating and uploading backup';
  errorMessage.value = '';
  successMessage.value = '';
  try {
    const response = await api.post('backups');
    backups.value = [response.data.data, ...backups.value];
    successMessage.value = 'Backup created successfully in Google Drive.';
  } catch (error) {
    errorMessage.value = error?.message || 'Unable to create the backup.';
  } finally {
    creating.value = false;
    operationLoading.value = false;
    operationMessage.value = '';
  }
};

const removeBackup = async (backup) => {
  const result = await Swal.fire({
    title: 'Delete backup?',
    text: `${backup.name} will be permanently deleted from Google Drive.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Yes, delete it',
    cancelButtonText: 'Cancel',
    background: '#ffffff',
    color: '#0f172a'
  });
  if (!result.isConfirmed) return;

  operationLoading.value = true;
  operationMessage.value = 'Deleting backup from Google Drive';
  try {
    await api.delete(`backups/${backup.id}`);
    backups.value = backups.value.filter(item => item.id !== backup.id);
    await Swal.fire({
      icon: 'success',
      title: 'Deleted',
      text: 'The backup was deleted from Google Drive.',
      timer: 1800,
      showConfirmButton: false,
      background: '#ffffff',
      color: '#0f172a'
    });
  } catch (error) {
    await Swal.fire({
      icon: 'error',
      title: 'Delete failed',
      text: error?.message || 'Unable to delete the backup.',
      confirmButtonColor: '#9333ea',
      background: '#ffffff',
      color: '#0f172a'
    });
  } finally {
    operationLoading.value = false;
    operationMessage.value = '';
  }
};

const downloadBackup = async (backup) => {
  operationLoading.value = true;
  operationMessage.value = 'Downloading backup';
  try {
    const response = await api.get(`backups/${backup.id}/download`, { responseType: 'blob' });
    const url = URL.createObjectURL(response.data);
    const link = document.createElement('a');
    link.href = url;
    link.download = backup.name;
    link.click();
    URL.revokeObjectURL(url);
  } catch (error) {
    await Swal.fire({ icon: 'error', title: 'Download failed', text: error?.message || 'Unable to download the backup.', confirmButtonColor: '#9333ea' });
  } finally {
    operationLoading.value = false;
    operationMessage.value = '';
  }
};

const openRestore = async (backup) => {
  restoreBackup.value = backup;
  restorePreview.value = null;
  restoreMode.value = 'strict-both';
  restoreConfirmation.value = '';
  restoreLoading.value = false;
};

const previewSelected = async () => {
  restoreLoading.value = true;
  operationLoading.value = true;
  operationMessage.value = 'Validating backup';
  try {
    const response = await api.get(`backups/${restoreBackup.value.id}/preview`);
    restorePreview.value = response.data?.data;
  } catch (error) {
    restoreBackup.value = null;
    await Swal.fire({ icon: 'error', title: 'Preview failed', text: getErrorMessage(error, 'This backup cannot be restored.'), confirmButtonColor: '#9333ea' });
  } finally {
    restoreLoading.value = false;
    operationLoading.value = false;
    operationMessage.value = '';
  }
};

const closeRestore = () => {
  if (!restoreLoading.value) restoreBackup.value = null;
};

const restoreSelected = async () => {
  restoreLoading.value = true;
  operationLoading.value = true;
  operationMessage.value = 'Restoring backup data';
  try {
    await api.post(`backups/${restoreBackup.value.id}/restore`, { mode: restoreMode.value, confirmation: restoreConfirmation.value });
    restoreBackup.value = null;
    await Swal.fire({ icon: 'success', title: 'Restore complete', text: 'The selected backup data was restored.', confirmButtonColor: '#9333ea' });
  } catch (error) {
    await Swal.fire({ icon: 'error', title: 'Restore failed', text: getErrorMessage(error, 'Unable to restore this backup.'), confirmButtonColor: '#9333ea' });
  } finally {
    restoreLoading.value = false;
    operationLoading.value = false;
    operationMessage.value = '';
  }
};

const formatDate = (value) => value ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(value)) : 'Unknown';
const formatSize = (value) => value ? `${(Number(value) / 1024 / 1024).toFixed(2)} MB` : 'Size unavailable';

onMounted(loadBackups);
</script>

<style>
.dark .backup-management-page .backup-stat-card,
html.dark .backup-management-page .backup-stat-card,
.dark .backup-management-page .backup-list-card,
html.dark .backup-management-page .backup-list-card {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border-color: rgba(185, 121, 204, 0.25) !important;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25) !important;
}

.dark .backup-management-page .backup-row-actions,
html.dark .backup-management-page .backup-row-actions {
  background: rgba(0, 0, 0, 0.2);
}

.dark .backup-management-page .backup-stat-card p,
html.dark .backup-management-page .backup-stat-card p,
.dark .backup-management-page .backup-list-card h2,
html.dark .backup-management-page .backup-list-card h2,
.dark .backup-management-page .backup-list-card p,
html.dark .backup-management-page .backup-list-card p,
.dark .backup-management-page .backup-list-card .backup-row-action,
html.dark .backup-management-page .backup-list-card .backup-row-action {
  color: #ffffff !important;
  -webkit-text-fill-color: #ffffff !important;
}

.dark .backup-management-page .backup-stat-card .material-symbols-outlined,
html.dark .backup-management-page .backup-stat-card .material-symbols-outlined,
.dark .backup-management-page .backup-list-card .material-symbols-outlined,
html.dark .backup-management-page .backup-list-card .material-symbols-outlined {
  -webkit-text-fill-color: currentColor !important;
}

html.dark .backup-management-page .backup-management-header h1,
html.dark .backup-management-page .backup-management-header p,
html.dark .backup-management-page .backup-management-header .backup-action-button,
html.dark .backup-management-page .backup-management-header .backup-action-button * {
  color: #0f172a !important;
  -webkit-text-fill-color: #0f172a !important;
}

html.dark .backup-management-page .backup-management-header > div:first-child > div:first-child,
html.dark .backup-management-page .backup-management-header > div:first-child > div:first-child * {
  color: #9333ea !important;
  -webkit-text-fill-color: #9333ea !important;
}

.dark .backup-management-page .backup-row-action-download,
html.dark .backup-management-page .backup-row-action-download,
.dark .backup-management-page .backup-row-action-restore,
html.dark .backup-management-page .backup-row-action-restore,
.dark .backup-management-page .backup-row-action-delete,
html.dark .backup-management-page .backup-row-action-delete {
  color: #ffffff !important;
  -webkit-text-fill-color: #ffffff !important;
}

html.dark .backup-management-page .backup-management-header .backup-action-button:first-child {
  background: #111827;
  border-color: rgba(185, 121, 204, 0.35);
  color: #ffffff !important;
  -webkit-text-fill-color: #ffffff !important;
}

html.dark .backup-management-page .backup-management-header .backup-action-button:first-child * {
  color: #ffffff !important;
  -webkit-text-fill-color: #ffffff !important;
}

html.dark .backup-management-page .backup-management-header .backup-action-button:last-child {
  background: #111827;
  color: #ffffff !important;
  -webkit-text-fill-color: #ffffff !important;
}

html.dark .backup-management-page .backup-management-header .backup-action-button:last-child * {
  color: #ffffff !important;
  -webkit-text-fill-color: #ffffff !important;
}

html.dark .backup-management-page .backup-list-card > div:first-child > button {
  color: #ffffff !important;
  -webkit-text-fill-color: #ffffff !important;
}

html.dark .backup-management-page .backup-list-card > div:first-child > button .material-symbols-outlined {
  color: #ffffff !important;
  -webkit-text-fill-color: #ffffff !important;
}
</style>
