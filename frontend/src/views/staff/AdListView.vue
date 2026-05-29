<template>
      <main class="main-content">
        <div class="content-wrapper">
          
          <div class="page-header">
            <h1 class="page-title">Activity Designs Tracker</h1>
            <p class="page-subtitle">Review, monitor compliance status, and manage submitted institutional activity plan frameworks.</p>
          </div>

          <section class="stats-section">
            <div 
              v-for="stat in metricsStats" 
              :key="stat.label" 
              class="stat-card"
            >
              <div class="stat-card-inner">
                <div :class="['stat-icon', stat.bgClass]">
                  <span class="material-symbols-outlined stat-icon-symbol" :class="stat.iconColor">
                    {{ stat.icon }}
                  </span>
                </div>
                <div class="stat-info">
                  <h3 class="stat-value">
                    {{ stat.value }}
                  </h3>
                  <p class="stat-label">
                    {{ stat.label }}
                  </p>
                </div>
              </div>
            </div>
          </section>

          <section class="filter-section">
            <div class="filter-controls">
              <div class="search-wrapper">
                <span class="search-icon">🔍</span>
                <input 
                  v-model="filters.search"
                  type="text" 
                  placeholder="Search control identifier or title..." 
                  class="search-input"
                />
              </div>

              <div class="select-wrapper">
                <select 
                  v-model="filters.office"
                  class="filter-select"
                >
                  <option value="all">All Offices & Units</option>
                  <option v-for="off in officeOptions" :key="off" :value="off">{{ off }}</option>
                </select>
                <span class="select-arrow">▼</span>
              </div>

              <div class="select-wrapper">
                <select 
                  v-model="filters.status"
                  class="filter-select"
                >
                  <option value="all">All Statuses</option>
                  <option value="Pending">Pending Review</option>
                  <option value="Approved">Approved</option>
                  <option value="Revision Required">Revision Required</option>
                </select>
                <span class="select-arrow">▼</span>
              </div>
            </div>

            <div class="per-page-controls">
              <span class="per-page-label">Show</span>
              <select 
                v-model="perPage"
                class="per-page-select"
              >
                <option :value="5">5</option>
                <option :value="10">10</option>
                <option :value="25">25</option>
              </select>
              <span class="per-page-label">records</span>
            </div>
          </section>

          <div class="table-container">
            <div class="table-wrapper">
              <table class="data-table">
                <thead>
                  <tr class="table-header-row">
                    <th class="table-header-cell">Control No.</th>
                    <th class="table-header-cell">Activity Title</th>
                    <th class="table-header-cell">Submitted By</th>
                    <th class="table-header-cell">Office / Unit</th>
                    <th class="table-header-cell">Form Type</th>
                    <th class="table-header-cell">Date Submitted</th>
                    <th class="table-header-cell">Status</th>
                    <th class="table-header-cell">Actions</th>
                  </tr>
                </thead>
                <tbody class="table-body">
                  <tr v-if="pagedDesigns.length === 0">
                    <td colspan="8" class="empty-state">
                      {{ loading ? 'Loading records…' : 'No matching activity design submissions found.' }}
                    </td>
                  </tr>

                  <tr
                    v-else
                    v-for="item in pagedDesigns"
                    :key="item.ad_id"
                    class="table-row"
                  >
                    <td class="table-cell control-cell">
                      <span :class="item.control_number === 'PENDING' ? 'control-pending' : ''">
                        {{ item.control_number }}
                      </span>
                    </td>
                    <td class="table-cell title-cell">{{ item.act_title }}</td>
                    <td class="table-cell office-cell">{{ item.submitter_name || '—' }}</td>
                    <td class="table-cell office-cell">{{ item.office_unit || '—' }}</td>
                    <td class="table-cell">
                      <span class="mandate-badge">{{ formTypeLabel(item.form_type) }}</span>
                    </td>
                    <td class="table-cell date-cell">{{ formatDate(item.created_at) }}</td>
                    <td class="table-cell">
                      <span class="status-badge" :class="statusBadgeClass(item.status_name)">
                        {{ item.status_name }}
                      </span>
                    </td>
                    <td class="table-cell" @click.stop>
                      <button
                        v-if="item.status_name === 'Approved'"
                        class="archive-btn"
                        :disabled="archiving === item.ad_id"
                        @click="archiveDesign(item)"
                      >
                        {{ archiving === item.ad_id ? '…' : 'Archive' }}
                      </button>
                      <button
                        v-else-if="item.status_name === 'Revision Required' && String(item.user_id) === String(user.id)"
                        class="revise-btn"
                        @click="openReviseModal(item, 'design')"
                      >
                        ✏️ Revise
                      </button>
                      <span v-else class="text-10px text-slate-500">—</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="pagination-container">
              <p class="pagination-info">
                Showing <span class="pagination-highlight">{{ paginationMeta.from }}</span> to
                <span class="pagination-highlight">{{ paginationMeta.to }}</span> of
                <span class="pagination-highlight">{{ paginationMeta.total }}</span> design records
              </p>
              <div class="pagination-controls">
                <button :disabled="currentPage === 1" @click="currentPage--" class="pagination-btn">←</button>
                <button
                  v-for="page in totalPages"
                  :key="page"
                  @click="currentPage = page"
                  :class="['pagination-page', currentPage === page && 'pagination-page-active']"
                >{{ page }}</button>
                <button :disabled="currentPage === totalPages" @click="currentPage++" class="pagination-btn">→</button>
              </div>
            </div>
          </div>

        </div>
      </main>

      <!-- Revise Modal -->
      <div v-if="reviseModal.open" class="modal-overlay" @click.self="reviseModal.open = false">
        <div class="modal-box">
          <h2 class="modal-title">Revise Activity Design</h2>
          <p class="modal-sub">Upload a corrected PDF to resubmit "{{ reviseModal.item?.act_title }}".</p>
          <div class="upload-dropzone-box modal-dropzone" @click="$refs.reviseFileInput.click()">
            <input ref="reviseFileInput" type="file" accept=".pdf" class="hidden" @change="onReviseFile" />
            <span class="text-3xl mb-1">📤</span>
            <p class="text-xs font-semibold text-white">{{ reviseFile ? reviseFile.name : 'Click to select new PDF' }}</p>
            <p class="text-10px text-slate-400 mt-1">PDF only · Max 10 MB</p>
          </div>
          <p v-if="reviseError" class="inline-error mt-2">{{ reviseError }}</p>
          <div class="modal-actions">
            <button class="modal-cancel" @click="reviseModal.open = false">Cancel</button>
            <button class="submit-action-btn" :disabled="revising || !reviseFile" @click="submitRevise">
              {{ revising ? 'Submitting…' : 'Resubmit →' }}
            </button>
          </div>
        </div>
      </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../api';

const router = useRouter();
const user   = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const filters = ref({ search: '', office: 'all', status: 'all' });
const officeOptions   = ref([]);
const activityDesigns = ref([]);
const currentPage     = ref(1);
const perPage         = ref(10);
const loading         = ref(false);
const archiving       = ref(null); 

// ─── Stats ────────────────────────────────────────────────────────────────
const metricsStats = computed(() => [
  { label: 'Total Designs',      value: activityDesigns.value.length,                                                       icon: 'description',       iconColor: 'text-purple-400', bgClass: 'bg-purple-500/10' },
  { label: 'Pending Reviews',    value: activityDesigns.value.filter(i => i.status_name === 'Pending').length,              icon: 'schedule',          iconColor: 'text-amber-400',  bgClass: 'bg-amber-500/10'  },
  { label: 'Approved Plans',     value: activityDesigns.value.filter(i => i.status_name === 'Approved').length,             icon: 'verified',          iconColor: 'text-green-400',  bgClass: 'bg-green-500/10'  },
  { label: 'Revision Required',  value: activityDesigns.value.filter(i => i.status_name === 'Revision Required').length,    icon: 'assignment_return', iconColor: 'text-red-400',    bgClass: 'bg-red-500/10'    },
]);

// ─── Filter + Pagination ──────────────────────────────────────────────────
const filteredDesigns = computed(() => {
  let records = activityDesigns.value;
  if (filters.value.search) {
    const q = filters.value.search.toLowerCase();
    records = records.filter(i =>
      (i.control_number || '').toLowerCase().includes(q) ||
      (i.act_title      || '').toLowerCase().includes(q) ||
      (i.submitter_name || '').toLowerCase().includes(q)
    );
  }
  if (filters.value.office !== 'all') records = records.filter(i => i.office_unit === filters.value.office);
  if (filters.value.status !== 'all') records = records.filter(i => i.status_name === filters.value.status);
  return records;
});

const totalPages = computed(() => Math.max(1, Math.ceil(filteredDesigns.value.length / perPage.value)));

const pagedDesigns = computed(() => {
  const start = (currentPage.value - 1) * perPage.value;
  return filteredDesigns.value.slice(start, start + perPage.value);
});

const paginationMeta = computed(() => {
  const total = filteredDesigns.value.length;
  const start = (currentPage.value - 1) * perPage.value;
  return {
    total,
    from: total === 0 ? 0 : start + 1,
    to:   Math.min(start + perPage.value, total),
  };
});

// ─── Helpers ──────────────────────────────────────────────────────────────
const formTypeLabel = (type) => {
  return { inset: 'INSET Training', extension: 'Extension Program', employee: 'Employee Training' }[type] || type || '—';
};

const formatDate = (dt) => {
  if (!dt) return '—';
  return new Date(dt).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: '2-digit' });
};

const statusBadgeClass = (status) => {
  if (status === 'Approved')          return 'status-badge-approved';
  if (status === 'Revision Required') return 'status-badge-revision';
  if (status === 'Archived')          return 'status-badge-archived';
  return 'status-badge-pending';
};

// ─── Revise ─────────────────────────────────────────────────────────────────
const reviseModal = ref({ open: false, item: null });
const reviseFile  = ref(null);
const reviseError = ref('');
const revising    = ref(false);
const reviseFileInput = ref(null);

const openReviseModal = (item) => {
  reviseModal.value = { open: true, item };
  reviseFile.value  = null;
  reviseError.value = '';
};

const onReviseFile = (e) => {
  const f = e.target.files[0];
  if (!f) return;
  if (f.type !== 'application/pdf') { reviseError.value = 'Only PDF files are allowed.'; reviseFile.value = null; return; }
  if (f.size > 10 * 1024 * 1024)   { reviseError.value = 'File must not exceed 10 MB.';  reviseFile.value = null; return; }
  reviseError.value = '';
  reviseFile.value  = f;
};

const submitRevise = async () => {
  if (!reviseFile.value) return;
  revising.value = true;
  try {
    const fd = new FormData();
    fd.append('type',        'design');
    fd.append('id',          reviseModal.value.item.ad_id);
    fd.append('user_id',     user.value.id);
    fd.append('design_file', reviseFile.value);
    const res = await api.post('update-submission', fd, { headers: { 'Content-Type': 'multipart/form-data' } });
    if (res.data.success) {
      reviseModal.value.open = false;
      await fetchDesigns();
    }
  } catch (err) {
    reviseError.value = err.response?.data?.message || 'Submission failed.';
  } finally {
    revising.value = false;
  }
};

// ─── Archive ─────────────────────────────────────────────────────────────────
const fetchDesigns = async () => {
  loading.value = true;
  try {
    const response = await api.get('activity-designs');
    if (response.data.success) {
      activityDesigns.value = response.data.data;
      officeOptions.value   = [...new Set(response.data.data.map(i => i.office_unit).filter(Boolean))];
    }
  } catch (err) {
    console.error('Failed to load activity designs:', err);
  } finally {
    loading.value = false;
  }
};

const archiveDesign = async (item) => {
  if (!confirm(`Archive "${item.act_title}"? This will move it to the archive.`)) return;
  archiving.value = item.ad_id;
  try {
    const res = await api.post('archive', { type: 'design', id: item.ad_id });
    if (res.data.success) {
      await fetchDesigns(); 
    }
  } catch (err) {
    alert(err.response?.data?.message || 'Failed to archive. Only Approved records can be archived.');
  } finally {
    archiving.value = null;
  }
};

const handleLogout = async () => {
  try { await api.get('logout'); } catch (_) {}
  localStorage.removeItem('user');
  router.push('/login');
};

onMounted(() => {
  if (!user.value.id || !['Staff', 'gad_staff'].includes(user.value.role)) {
    router.push('/login');
  } else {
    fetchDesigns();
  }
});
</script>

<style scoped>
.main-container {
  flex-grow: 1;
  margin-left: 256px;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.app-header {
  position: fixed;
  top: 0;
  left: 256px;
  right: 0;
  height: 80px;
  background: rgba(26, 26, 46, 0.4);
  backdrop-filter: blur(24px);
  z-index: 40;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 40px;
  border-bottom: 1px solid rgba(185, 121, 204, 0.1);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.header-title {
  font-size: 1.5rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  background: linear-gradient(135deg, white, #cbd5e1, #b979cc);
  background-clip: text;
  -webkit-background-clip: text;
  color: transparent;
}

.header-subtitle-wrapper {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 0.125rem;
}

.header-subtitle {
  font-size: 0.5625rem;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: #b979cc;
  font-weight: 800;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.notification-wrapper {
  position: relative;
}

.notification-btn {
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.75rem;
  background: rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(185, 121, 204, 0.2);
  font-size: 1.125rem;
  cursor: pointer;
}


.main-content {
  padding-left: 0;
  flex-grow: 1;
}

.content-wrapper {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.page-header {
  padding: 0 0.25rem;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  color: #16213e;
}

.page-subtitle {
  font-size: 0.75rem;
  color: #94a3b8;
  margin-top: 0.25rem;
}

.stats-section {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
}

.stat-card {
  padding: 1rem;
  border-radius: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(8px);
  transition: all 0.3s;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
}

.stat-card:hover {
  transform: translateY(-4px);
}

.stat-card-inner {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.stat-icon {
  width: 40px;
  height: 40px;
  border-radius: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.stat-icon-symbol {
  font-weight: 500;
  font-size: 1.125rem;
}

.text-purple-400 { color: #c084fc; }
.text-amber-400 { color: #fbbf24; }
.text-green-400 { color: #4ade80; }
.text-red-400 { color: #f87171; }

.bg-purple-500\/10 { background: rgba(168, 85, 247, 0.1); }
.bg-amber-500\/10 { background: rgba(245, 158, 11, 0.1); }
.bg-green-500\/10 { background: rgba(34, 197, 94, 0.1); }
.bg-red-500\/10 { background: rgba(239, 68, 68, 0.1); }

.stat-info {
  min-width: 0;
}

.stat-value {
  font-size: 1.25rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  color: white;
  line-height: 1.25;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.stat-label {
  font-size: 0.625rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: rgba(203, 213, 225, 0.7);
  margin-top: 0.125rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.filter-section {
  padding: 1rem;
  border-radius: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25);
  backdrop-filter: blur(8px);
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
}

.filter-controls {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 0.75rem;
  width: auto;
}

.search-wrapper {
  position: relative;
  width: 288px;
}

.search-icon {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  font-size: 0.75rem;
}

.search-input {
  width: 100%;
  padding: 0.5rem 1rem 0.5rem 2.25rem;
  border-radius: 0.75rem;
  background: rgba(0, 0, 0, 0.4);
  border: 1px solid rgba(185, 121, 204, 0.2);
  font-size: 0.75rem;
  font-weight: 600;
  color: white;
  transition: all 0.3s;
}

.search-input:focus {
  outline: none;
  border-color: rgba(185, 121, 204, 0.5);
}

.search-input::placeholder {
  color: #64748b;
}

.select-wrapper {
  position: relative;
  width: 176px;
}

.filter-select {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border-radius: 0.75rem;
  background: rgba(0, 0, 0, 0.4);
  border: 1px solid rgba(185, 121, 204, 0.2);
  font-size: 0.75rem;
  font-weight: 600;
  color: white;
  appearance: none;
  cursor: pointer;
  transition: all 0.3s;
}

.filter-select:focus {
  outline: none;
  border-color: rgba(185, 121, 204, 0.5);
}

.select-arrow {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  font-size: 0.625rem;
  pointer-events: none;
}

.per-page-controls {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.per-page-label {
  font-size: 0.6875rem;
  color: #94a3b8;
  font-weight: 500;
}

.per-page-select {
  padding: 0.375rem 0.625rem;
  border-radius: 0.5rem;
  background: rgba(0, 0, 0, 0.4);
  border: 1px solid rgba(185, 121, 204, 0.2);
  font-size: 0.75rem;
  font-weight: 700;
  color: white;
  cursor: pointer;
  transition: all 0.3s;
}

.per-page-select:focus {
  outline: none;
  border-color: rgba(185, 121, 204, 0.5);
}

.table-container {
  border-radius: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25);
  overflow: hidden;
  backdrop-filter: blur(8px);
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
}

.table-wrapper {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  text-align: left;
  border-collapse: collapse;
}

.table-header-row {
  border-bottom: 1px solid rgba(185, 121, 204, 0.1);
  background: rgba(0, 0, 0, 0.3);
}

.table-header-cell {
  padding: 1rem 1.5rem;
  font-size: 0.625rem;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: #b979cc;
}

.table-body {
  display: table-row-group;
}

.empty-state {
  padding: 3rem 1.5rem;
  text-align: center;
  font-size: 0.75rem;
  color: #94a3b8;
  font-weight: 500;
}

.table-row {
  transition: all 0.3s;
  border-bottom: 1px solid rgba(185, 121, 204, 0.05);
  cursor: pointer;
}

.table-row:hover {
  background: rgba(255, 255, 255, 0.05);
}

.table-cell {
  padding: 1rem 1.5rem;
}

.control-cell {
  font-family: monospace;
  font-size: 0.75rem;
  font-weight: 700;
  color: #b979cc;
}

.title-cell {
  font-weight: 600;
  color: #e2e8f0;
  transition: color 0.3s;
}

.table-row:hover .title-cell {
  color: #b979cc;
}

.office-cell {
  color: rgba(203, 213, 225, 0.8);
}

.date-cell {
  color: #94a3b8;
  font-family: monospace;
  font-size: 0.75rem;
}

.mandate-badge {
  padding: 0.25rem 0.625rem;
  border-radius: 0.5rem;
  font-size: 0.5625rem;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: #cbd5e1;
}

.status-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 0.5rem;
  font-size: 0.5625rem;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.status-badge-pending {
  background: rgba(245, 158, 11, 0.2);
  color: #fbbf24;
  border: 1px solid rgba(245, 158, 11, 0.3);
}

.status-badge-approved {
  background: rgba(34, 197, 94, 0.2);
  color: #4ade80;
  border: 1px solid rgba(34, 197, 94, 0.3);
}

.status-badge-revision {
  background: rgba(239, 68, 68, 0.2);
  color: #f87171;
  border: 1px solid rgba(239, 68, 68, 0.3);
}

.pagination-container {
  padding: 1rem 1.5rem;
  border-top: 1px solid rgba(185, 121, 204, 0.1);
  background: rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}

.pagination-info {
  font-size: 0.75rem;
  color: #94a3b8;
  font-weight: 500;
}

.pagination-highlight {
  font-weight: 700;
  color: white;
}

.pagination-controls {
  display: flex;
  align-items: center;
  gap: 0.375rem;
}

.pagination-btn {
  width: 32px;
  height: 32px;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 700;
  color: white;
  border: 1px solid rgba(185, 121, 204, 0.1);
  background: rgba(0, 0, 0, 0.3);
  cursor: pointer;
  transition: all 0.2s;
}

.pagination-btn:hover:not(:disabled) {
  border-color: rgba(185, 121, 204, 0.4);
}

.pagination-btn:disabled {
  opacity: 0.3;
  cursor: not-allowed;
}

.pagination-page {
  width: 32px;
  height: 32px;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 700;
  transition: all 0.2s;
  cursor: pointer;
  color: #94a3b8;
  border: 1px solid rgba(185, 121, 204, 0.1);
  background: rgba(0, 0, 0, 0.2);
}

.pagination-page:hover {
  border-color: rgba(185, 121, 204, 0.3);
}

.pagination-page-active {
  color: white;
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  border: none;
}

::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.1);
}

::-webkit-scrollbar-thumb {
  background: rgba(185, 121, 204, 0.3);
  border-radius: 99px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(153, 13, 209, 0.5);
}

@media (max-width: 1024px) {
  .main-container {
    margin-left: 0;
  }
  
  .app-header {
    left: 0;
  }
  
  .stats-section {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .filter-section {
    flex-direction: column;
    align-items: stretch;
  }
  
  .filter-controls {
    width: 100%;
    flex-direction: column;
  }
  
  .search-wrapper,
  .select-wrapper {
    width: 100%;
  }
  
  .pagination-container {
    flex-direction: column;
  }
}

@media (max-width: 768px) {
  .content-wrapper {
    padding: 20px;
  }
  
  .stats-section {
    grid-template-columns: 1fr;
  }
}

.control-pending {
  color: #fbbf24;
  font-style: italic;
  opacity: 0.8;
}

.status-badge-archived {
  background: rgba(100, 116, 139, 0.2);
  color: #94a3b8;
  border: 1px solid rgba(100, 116, 139, 0.3);
}

.archive-btn {
  padding: 0.25rem 0.75rem;
  border-radius: 0.5rem;
  font-size: 0.625rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  background: rgba(153, 13, 209, 0.15);
  border: 1px solid rgba(153, 13, 209, 0.4);
  color: #b979cc;
  cursor: pointer;
  transition: all 0.2s;
}

.archive-btn:hover:not(:disabled) {
  background: rgba(153, 13, 209, 0.3);
  border-color: #b979cc;
  color: white;
}

.archive-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.revise-btn {
  padding: 0.25rem 0.75rem;
  border-radius: 0.5rem;
  font-size: 0.625rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  background: rgba(251, 191, 36, 0.15);
  border: 1px solid rgba(251, 191, 36, 0.4);
  color: #fbbf24;
  cursor: pointer;
  transition: all 0.2s;
}

.revise-btn:hover {
  background: rgba(251, 191, 36, 0.3);
  color: white;
}

/* Modal */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
  backdrop-filter: blur(4px);
}

.modal-box {
  background: linear-gradient(145deg, #1a1a2e 0%, #16213e 100%);
  border: 1px solid rgba(185,121,204,0.25);
  border-radius: 20px;
  padding: 2rem;
  max-width: 480px;
  width: 100%;
  box-shadow: 0 25px 50px rgba(0,0,0,0.5);
}

.modal-title {
  font-size: 1.125rem;
  font-weight: 900;
  color: #e2e8f0;
  margin-bottom: 0.375rem;
}

.modal-sub {
  font-size: 0.75rem;
  color: #94a3b8;
  margin-bottom: 1.25rem;
}

.modal-dropzone {
  margin-bottom: 0.5rem;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  margin-top: 1.5rem;
}

.modal-cancel {
  padding: 0.75rem 1.5rem;
  border-radius: 0.75rem;
  font-size: 0.75rem;
  font-weight: 700;
  color: #94a3b8;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.1);
  cursor: pointer;
  transition: all 0.2s;
}

.modal-cancel:hover {
  color: white;
  border-color: rgba(255,255,255,0.2);
}

.hidden { display: none; }
.text-10px { font-size: 10px; }
.text-3xl { font-size: 26px; }
</style>