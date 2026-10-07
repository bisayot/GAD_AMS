<template>

      <main class="flex-1 overflow-y-auto">
        <div class="max-w-7xl mx-auto">
          <div class="stats-container">
            <div class="stat-card-purple">
              <div class="stat-card-inner">
                <div class="stat-icon-wrapper purple">
                  <span class="material-symbols-outlined">inventory</span>
                </div>
                <div class="stat-content">
                  <h3 class="stat-number-purple">{{ totalArchived }}</h3>
                  <p class="stat-label-purple">TOTAL ARCHIVED</p>
                </div>
              </div>
            </div>

            <div class="stat-card">
              <div class="stat-card-inner">
                <div class="stat-icon-wrapper green">
                  <span class="material-symbols-outlined">check_circle</span>
                </div>
                <div class="stat-content">
                  <h3 class="stat-number">{{ approvedDesigns }}</h3>
                  <p class="stat-label">Approved Designs</p>
                </div>
              </div>
            </div>

            <div class="stat-card">
              <div class="stat-card-inner">
                <div class="stat-icon-wrapper blue">
                  <span class="material-symbols-outlined">celebration</span>
                </div>
                <div class="stat-content">
                  <h3 class="stat-number">{{ completedReports }}</h3>
                  <p class="stat-label">Completed Reports</p>
                </div>
              </div>
            </div>
          </div><br>

          <div class="tabs-container">
            <div class="tabs-header">
              <button 
                @click="activeTab = 'designs'" 
                class="tab-btn"
                :class="{ 'tab-active': activeTab === 'designs', 'tab-inactive': activeTab !== 'designs' }"
              >
                Activity Designs
                <span class="tab-badge">{{ totalDesigns }}</span>
              </button>
              <button 
                @click="activeTab = 'reports'" 
                class="tab-btn"
                :class="{ 'tab-active': activeTab === 'reports', 'tab-inactive': activeTab !== 'reports' }"
              >
                Accomplishment Reports
                <span class="tab-badge">{{ totalReports }}</span>
              </button>
            </div>
          </div>

          <div class="filter-card">
            <div class="filter-inline">

              <div class="filter-item">
                <label class="filter-label">FISCAL YEAR</label>
                <select v-model="filters.fiscalYear" class="filter-select-custom" @change="applyFilters">
                  <option value="all">All Years</option>
                  <option v-for="year in availableFiscalYears" :key="year" :value="year">{{ year }}</option>
                </select>
              </div>

              <div class="filter-item">
                <label class="filter-label">SORT BY</label>
                <select v-model="filters.sort" class="filter-select-custom" @change="applyFilters">
                  <option value="date_desc">Newest First</option>
                  <option value="date_asc">Oldest First</option>
                  <option value="control_asc">Control A-Z</option>
                  <option value="control_desc">Control Z-A</option>
                </select>
              </div>

              <div class="filter-search">
                <label class="filter-label">SEARCH</label>
                <div class="search-box-wrapper">
                  <span class="search-icon">🔍</span>
                  <input 
                    type="text" 
                    v-model="filters.search" 
                    placeholder="Search by title or control number..." 
                    class="search-input"
                    @keyup.enter="applyFilters"
                  >
                </div>
              </div>

            </div>

            <div class="filter-footer">
              <div class="record-count">
                <span class="count-number">{{ filteredItems.length }}</span> record(s) found
              </div>
            </div>
          </div>

          <div v-if="loading" class="loading-state">
            <div class="loading-spinner"></div>
            <p>Loading archive records...</p>
          </div>

          <div v-else class="data-table">
            <div class="overflow-x-auto">
              <table class="data-table-inner">
                <thead>
                  <tr class="table-header-row">
                    <th class="table-header-cell">TYPE</th>
                    <th class="table-header-cell">CONTROL NUMBER</th>
                    <th class="table-header-cell">ACTIVITY TITLE</th>
                    <th class="table-header-cell">DATE ARCHIVED</th>
                    <th class="table-header-cell">STATUS</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="paginatedItems.length === 0" class="empty-row">
                    <td colspan="5" class="empty-cell">
                      <div class="empty-content">
                        <span class="empty-emoji">📭</span>
                        <p>No archived records found</p>
                        <button class="btn-secondary-custom" @click="resetFilters">Clear Filters</button>
                      </div>
                    </td>
                  </tr>
                  <tr 
                    v-for="item in paginatedItems" 
                    :key="item.id"
                    class="clickable-row"
                    @click="viewItem(item)"
                  >
                    <td class="table-cell">
                      <span class="type-badge" :class="item.type === 'design' ? 'type-design' : 'type-report'">
                        {{ item.type === 'design' ? 'Activity Design' : 'Accomplishment Report' }}
                      </span>
                    </td>
                    <td class="table-cell">
                      <div class="control-number">{{ item.control }}</div>
                      <div class="item-date">{{ item.dateArchived }}</div>
                    </td>
                    <td class="table-cell">
                      <div class="item-title">
                        {{ item.title }}
                        <span v-if="item.is_modified == 1" class="status-badge" style="background: #7c3aed; color: #ffffff; border: 1px solid #6d28d9; margin-left: 8px; font-size: 0.7rem; padding: 0.15rem 0.5rem; border-radius: 4px; font-weight: 700;">MODIFIED</span>
                      </div>
                    </td>
                    <td class="table-cell">
                      <div class="item-date">{{ item.dateArchived }}</div>
                    </td>
                    <td class="table-cell">
                      <span class="status-badge" :class="item.statusClass">
                        {{ item.statusText }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div v-if="totalPages > 1" class="pagination-container">
              <div class="pagination-info">
                Showing <span class="info-highlight">{{ startIndex + 1 }}</span> - 
                <span class="info-highlight">{{ Math.min(startIndex + itemsPerPage, filteredItems.length) }}</span> 
                of <span class="info-total">{{ filteredItems.length }}</span> records
              </div>
              <div class="pagination-buttons">
                <button class="page-btn" :class="{ disabled: currentPage === 1 }" @click="changePage(currentPage - 1)" :disabled="currentPage === 1">← Prev</button>
                
                <button 
                  v-for="page in visiblePages" 
                  :key="page"
                  class="page-btn" 
                  :class="{ active: page === currentPage }"
                  @click="changePage(page)"
                >
                  {{ page }}
                </button>
                
                <button class="page-btn" :class="{ disabled: currentPage === totalPages }" @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages">Next →</button>
              </div>
            </div>
          </div>
        </div>
      </main>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import api from '../../api';

const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const archivedDesigns = ref([]);
const archivedReports = ref([]);
const loading = ref(false);
const activeTab = ref('designs');

const filters = ref({
  status: 'all',
  sort: 'date_desc',
  search: '',
  fiscalYear: 'all'
});

const currentPage = ref(1);
const itemsPerPage = 10;

const totalArchived = computed(() => {
  return archivedDesigns.value.length + archivedReports.value.length;
});

const approvedDesigns = computed(() => {
  return archivedDesigns.value.length;
});

const completedReports = computed(() => {
  return archivedReports.value.length;
});

const totalDesigns = computed(() => {
  return archivedDesigns.value.length;
});

const totalReports = computed(() => {
  return archivedReports.value.length;
});

const pendingCount = computed(() => {
  return 0;
});

const currentSourceData = computed(() => {
  return activeTab.value === 'designs' ? archivedDesigns.value : archivedReports.value;
});

const availableFiscalYears = computed(() => {
  const years = new Set();
  currentSourceData.value.forEach(item => {
    if (item.dateRaw) {
      years.add(new Date(item.dateRaw).getFullYear().toString());
    }
  });
  return Array.from(years).sort((a, b) => b - a);
});

const filteredItems = computed(() => {
  let items = [...currentSourceData.value];
  
  if (filters.value.status !== 'all') {
    items = items.filter(item => item.status === filters.value.status);
  }
  
  if (filters.value.search.trim()) {
    const searchTerm = filters.value.search.toLowerCase();
    items = items.filter(item => 
      item.title.toLowerCase().includes(searchTerm) || 
      item.control.toLowerCase().includes(searchTerm)
    );
  }
  
  if (filters.value.fiscalYear !== 'all') {
    items = items.filter(item => {
      if (!item.dateRaw) return false;
      const year = new Date(item.dateRaw).getFullYear().toString();
      return year === filters.value.fiscalYear;
    });
  }
  
  const sorted = [...items];
  switch (filters.value.sort) {
    case 'control_asc':
      sorted.sort((a, b) => a.control.localeCompare(b.control));
      break;
    case 'control_desc':
      sorted.sort((a, b) => b.control.localeCompare(a.control));
      break;
    case 'date_asc':
      sorted.sort((a, b) => new Date(a.dateRaw) - new Date(b.dateRaw));
      break;
    default: // date_desc
      sorted.sort((a, b) => new Date(b.dateRaw) - new Date(a.dateRaw));
  }
  
  return sorted;
});

const totalPages = computed(() => Math.ceil(filteredItems.value.length / itemsPerPage));
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const paginatedItems = computed(() => {
  return filteredItems.value.slice(startIndex.value, startIndex.value + itemsPerPage);
});

const visiblePages = computed(() => {
  const maxVisible = 5;
  let startPage = Math.max(1, currentPage.value - Math.floor(maxVisible / 2));
  let endPage = Math.min(totalPages.value, startPage + maxVisible - 1);
  
  if (endPage - startPage + 1 < maxVisible) {
    startPage = Math.max(1, endPage - maxVisible + 1);
  }
  
  const pages = [];
  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }
  return pages;
});

const fetchArchives = async () => {
  loading.value = true;
  try {
    const response = await api.get(`archives?user_id=${user.value.id}&role=${user.value.role}`);
    const allData = (response.data.data || []).map(item => ({
      ...item,
      id: item.original_id,
      dateArchived: item.dateRaw ? new Date(item.dateRaw).toLocaleDateString() : 'N/A',
      statusText: item.status,
      statusClass: (item.status === 'Approved' || item.status === 'Verified') ? 'status-approved' : 'status-cancelled'
    }));
    archivedDesigns.value = allData.filter(item => item.type === 'design');
    archivedReports.value = allData.filter(item => item.type === 'report');
  } catch (error) {
    console.error('Error fetching archive records:', error);
  } finally {
    loading.value = false;
  }
};

const applyFilters = () => {
  currentPage.value = 1;
};

const resetFilters = () => {
  filters.value = {
    status: 'all',
    sort: 'date_desc',
    search: '',
    fiscalYear: 'all'
  };
  currentPage.value = 1;
};

const changePage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const viewItem = (item) => {
  if (item.type === 'design') {
    router.push(`/admin/ad-view/${item.id}`);
  } else {
    router.push(`/admin/ar-view/${item.id}`);
  }
};

const handleTrash = async (item) => {
  const result = await Swal.fire({
    title: 'Move to Trash?',
    text: `"${item.title}" will be moved to trash. You can restore it within 30 days.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Yes, move to trash'
  });
  if (!result.isConfirmed) return;
  try {
    const res = await api.delete(`activity-designs/trash/${item.id}`, {
      headers: { 'X-User-Id': user.value.id }
    });
    if (res.data.success) {
      Swal.fire({ icon: 'success', title: 'Moved to Trash', timer: 1500, showConfirmButton: false });
      fetchArchives();
    } else {
      Swal.fire({ icon: 'error', title: 'Error', text: res.data.message || 'Failed to trash.' });
    }
  } catch (err) {
    Swal.fire({ icon: 'error', title: 'Error', text: 'Server error.' });
  }
};

const handleLogout = async () => {
  try {
    await api.get('logout');
    localStorage.removeItem('user');
    router.push('/login');
  } catch (err) {
    localStorage.removeItem('user');
    router.push('/login');
  }
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'admin') {
    router.push('/login');
  }
  fetchArchives();
});
</script>

<style scoped>
.stats-container {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
}

@media (min-width: 768px) {
  .stats-container {
    grid-template-columns: repeat(4, 1fr);
  }
}

.stat-card-purple {
  background: var(--color-surface);
  padding: 1.5rem;
  border-radius: 1rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
  border: 1px solid var(--color-outline-variant);
  transition: all 0.3s;
}

.stat-card-purple:hover,
.stat-card:hover {
  transform: translateY(-4px);
}

.stat-card {
  background-color: var(--color-surface);
  padding: 1.5rem;
  border-radius: 1rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
  border: 1px solid var(--color-outline-variant);
  transition: all 0.3s;
}

.stat-card-inner {
  display: flex;
  align-items: center;
  gap: 1rem;
}

/* Icon Wrapper */
.stat-icon-wrapper {
  padding: 0.75rem;
  border-radius: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stat-icon-wrapper.purple {
  background-color: #f3e8ff;
}

.stat-icon-wrapper.purple .material-symbols-outlined {
  color: #9333ea;
}

:global(.dark) .stat-icon-wrapper.purple {
  background-color: rgba(147, 51, 234, 0.2);
}

:global(.dark) .stat-icon-wrapper.purple .material-symbols-outlined {
  color: #c084fc;
}

.stat-icon-wrapper.blue {
  background-color: #eff6ff;
}

.stat-icon-wrapper.blue .material-symbols-outlined {
  color: #2563eb;
}

:global(.dark) .stat-icon-wrapper.blue {
  background-color: rgba(37, 99, 235, 0.2);
}

:global(.dark) .stat-icon-wrapper.blue .material-symbols-outlined {
  color: #60a5fa;
}

.stat-icon-wrapper.green {
  background-color: #ecfdf5;
}

.stat-icon-wrapper.green .material-symbols-outlined {
  color: #059669;
}

:global(.dark) .stat-icon-wrapper.green {
  background-color: rgba(34, 197, 94, 0.2);
}

:global(.dark) .stat-icon-wrapper.green .material-symbols-outlined {
  color: #4ade80;
}

.stat-icon-wrapper.amber {
  background-color: #fffbeb;
}

.stat-icon-wrapper.amber .material-symbols-outlined {
  color: #d97706;
}

:global(.dark) .stat-icon-wrapper.amber {
  background-color: rgba(245, 158, 11, 0.2);
}

:global(.dark) .stat-icon-wrapper.amber .material-symbols-outlined {
  color: #fbbf24;
}

/* Material Icons */
.material-symbols-outlined {
  font-size: 1.5rem;
}

/* Stat Content */
.stat-content {
  flex: 1;
}

.stat-number {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--color-on-background);
  margin: 0;
  line-height: 1.2;
}

.stat-label {
  font-size: 12px;
  font-weight: 800;
  color: var(--color-on-surface-variant);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin: 0.25rem 0 0 0;
}

.stat-number-purple {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--color-on-background);
  margin: 0;
  line-height: 1.2;
}

.stat-label-purple {
  font-size: 12px;
  font-weight: 800;
  color: #7e22ce;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin: 0.25rem 0 0 0;
}

:global(.dark) .stat-label-purple {
  color: #d4a3e3;
}

.stat-sub {
  font-size: 1rem;
  color: var(--color-on-surface-variant);
  opacity: 0.7;
  margin: 0.25rem 0 0 0;
}

.stat-sub-purple {
  font-size: 1rem;
  color: var(--color-primary-text);
  opacity: 0.7;
  margin: 0.25rem 0 0 0;
}

/* Tabs */
.tabs-container {
  margin-bottom: 1.5rem;
  border-bottom: 1px solid var(--color-outline-variant);
}

.tabs-header {
  display: flex;
  gap: 0.5rem;
}

.tab-btn {
  transition: all 0.25s ease;
  border-radius: 12px 12px 0 0;
  padding: 0.75rem 1.5rem;
  font-weight: 700;
  font-size: 0.95rem;
  background: none;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.tab-active {
  border-bottom: 3px solid #990dd1;
  color: #71009e;
  background: rgba(153, 13, 209, 0.04);
}

:global(.dark) .tab-active {
  color: #e9d5ff;
  background: rgba(153, 13, 209, 0.15);
}

.tab-inactive {
  border-bottom: 3px solid transparent;
  color: var(--color-on-surface-variant);
}

.tab-inactive:hover {
  border-bottom: 3px solid #b979cc;
  color: #990dd1;
  background: rgba(153, 13, 209, 0.02);
}

:global(.dark) .tab-inactive:hover {
  color: #d8b4fe;
}

.tab-badge {
  background: var(--color-surface-variant);
  color: var(--color-on-surface-variant);
  padding: 0.125rem 0.5rem;
  border-radius: 30px;
  font-size: 0.85rem;
  font-weight: 700;
  border: 1px solid var(--color-outline-variant);
}

.tab-active .tab-badge {
  background: rgba(153, 13, 209, 0.15);
  color: #7e22ce;
  border-color: rgba(153, 13, 209, 0.3);
}

:global(.dark) .tab-active .tab-badge {
  background: rgba(153, 13, 209, 0.3);
  color: #e9d5ff;
}

/* Filter Card */
.filter-card {
  background: var(--color-surface);
  border-radius: 1.25rem;
  padding: 1rem 1.5rem;
  margin-bottom: 1.5rem;
  border: 1px solid var(--color-outline-variant);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
}

.filter-inline {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-end;
  gap: 1rem;
}

.filter-item {
  flex: 1;
  min-width: 140px;
}

.filter-search {
  flex: 2;
  min-width: 200px;
}

.filter-label {
  display: block;
  font-size: 0.85rem;
  font-weight: 800;
  color: var(--color-primary-text);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 0.375rem;
}

.filter-select-custom {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border-radius: 0.75rem;
  border: 1px solid var(--color-outline-variant);
  background: var(--color-surface-variant);
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--color-on-background);
  cursor: pointer;
}

.filter-select-custom:focus {
  outline: none;
  border-color: #990dd1;
  box-shadow: 0 0 0 2px rgba(153, 13, 209, 0.1);
}

.filter-select-custom option {
  background-color: var(--color-surface);
  color: var(--color-on-background);
}

.search-box-wrapper {
  position: relative;
}

.search-icon {
  position: absolute;
  left: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.95rem;
  opacity: 0.7;
  color: var(--color-on-surface-variant);
}

.search-input {
  width: 100%;
  padding: 0.5rem 0.75rem 0.5rem 2.25rem;
  border-radius: 0.75rem;
  border: 1px solid var(--color-outline-variant);
  background: var(--color-surface-variant);
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--color-on-background);
}

.search-input::placeholder {
  color: var(--color-on-surface-variant);
}

.search-input:focus {
  outline: none;
  border-color: #990dd1;
  box-shadow: 0 0 0 2px rgba(153, 13, 209, 0.1);
}

.filter-actions {
  display: flex;
  gap: 0.5rem;
}

.btn-primary-custom {
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
  color: white;
  padding: 0.5rem 1.25rem;
  border-radius: 0.75rem;
  font-size: 0.95rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-primary-custom:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(153, 13, 209, 0.3);
}

.btn-secondary-custom {
  padding: 0.5rem 1.25rem;
  border-radius: 0.75rem;
  border: 1px solid var(--color-outline-variant);
  background: var(--color-surface-variant);
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--color-on-background);
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-block;
}

.btn-secondary-custom:hover {
  background: rgba(185, 121, 204, 0.15);
  border-color: #b979cc;
  color: var(--color-primary-text);
}

.filter-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 1rem;
  padding-top: 0.75rem;
  border-top: 1px dashed var(--color-outline-variant);
}

.record-count {
  font-size: 0.95rem;
  color: var(--color-on-surface-variant);
  font-weight: 600;
}

.count-number {
  font-weight: 800;
  color: var(--color-primary-text);
  font-size: 0.95rem;
}

/* Loading State */
.loading-state {
  background: var(--color-surface);
  border-radius: 1.25rem;
  padding: 3rem;
  text-align: center;
  border: 1px solid var(--color-outline-variant);
}

.loading-state p {
  color: var(--color-on-surface-variant);
  margin-top: 1rem;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 3px solid var(--color-outline-variant);
  border-top-color: #990dd1;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Data Table */
.data-table {
  background: var(--color-surface);
  border-radius: 1.25rem;
  overflow: hidden;
  border: 1px solid var(--color-outline-variant);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
}

.data-table-inner {
  width: 100%;
  border-collapse: collapse;
}

.table-header-row {
  background: var(--color-surface-variant);
  border-bottom: 1px solid var(--color-outline-variant);
}

.table-header-cell {
  padding: 1rem 1.5rem;
  text-align: left;
  font-size: 0.85rem;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: var(--color-primary-text);
}

.clickable-row {
  cursor: pointer;
  transition: all 0.2s ease;
  border-bottom: 1px solid var(--color-outline-variant);
  background: var(--color-surface);
}

.clickable-row:hover {
  background: rgba(185, 121, 204, 0.08);
}

.table-cell {
  padding: 1rem 1.5rem;
}

/* Badges */
.type-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.8rem;
  border-radius: 30px;
  font-size: 0.85rem;
  font-weight: 700;
}

.type-design {
  background: rgba(147, 51, 234, 0.12);
  color: #7e22ce;
  border: 1px solid rgba(147, 51, 234, 0.25);
}

:global(.dark) .type-design {
  background: rgba(147, 51, 234, 0.25);
  color: #d8b4fe;
}

.type-report {
  background: rgba(3, 105, 161, 0.12);
  color: #0369a1;
  border: 1px solid rgba(3, 105, 161, 0.25);
}

:global(.dark) .type-report {
  background: rgba(3, 105, 161, 0.25);
  color: #7dd3fc;
}

.form-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.2rem 0.6rem;
  border-radius: 30px;
  font-size: 0.85rem;
  font-weight: 600;
  background: var(--color-surface-variant);
  border: 1px solid var(--color-outline-variant);
  color: var(--color-on-surface-variant);
}

.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.3rem 0.8rem;
  border-radius: 30px;
  font-size: 0.85rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.status-badge.status-approved {
  background: rgba(34, 197, 94, 0.15);
  color: #15803d;
  border: 1px solid rgba(34, 197, 94, 0.4);
}

:global(.dark) .status-badge.status-approved {
  background: rgba(34, 197, 94, 0.25);
  color: #4ade80;
}

.status-badge.status-completed {
  background: rgba(2, 132, 199, 0.15);
  color: #0369a1;
  border: 1px solid rgba(2, 132, 199, 0.4);
}

:global(.dark) .status-badge.status-completed {
  background: rgba(2, 132, 199, 0.25);
  color: #38bdf8;
}

.status-badge.status-cancelled {
  background: rgba(220, 38, 38, 0.15);
  color: #b91c1c;
  border: 1px solid rgba(220, 38, 38, 0.4);
}

:global(.dark) .status-badge.status-cancelled {
  background: rgba(220, 38, 38, 0.25);
  color: #f87171;
}

.control-number {
  font-family: monospace;
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--color-primary-text);
  letter-spacing: 0.05em;
}

.item-date {
  font-size: 0.95rem;
  color: var(--color-on-surface-variant);
  font-weight: 600;
  margin-top: 0.35rem;
}

.item-title {
  font-weight: 800;
  color: var(--color-on-background);
  font-size: 1.05rem;
  line-height: 1.4;
  transition: color 0.2s ease;
}

.clickable-row:hover .item-title {
  color: var(--color-primary-text);
}

/* Empty State */
.empty-row {
  border-bottom: none;
}

.empty-cell {
  padding: 3rem 1.5rem;
  text-align: center;
}

.empty-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.empty-emoji {
  font-size: 3rem;
}

.empty-content p {
  color: var(--color-on-surface-variant);
  font-size: 0.95rem;
  font-weight: 500;
}

/* Pagination */
.pagination-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--color-outline-variant);
  background: var(--color-surface-variant);
  flex-wrap: wrap;
  gap: 1rem;
}

.pagination-info {
  font-size: 1rem;
  color: var(--color-on-surface-variant);
  font-weight: 500;
}

.info-highlight {
  font-weight: 700;
  color: var(--color-primary-text);
}

.info-total {
  font-weight: 700;
  color: var(--color-on-background);
}

.pagination-buttons {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.page-btn {
  padding: 0.4rem 0.9rem;
  border: 1px solid var(--color-outline-variant);
  border-radius: 0.6rem;
  background: var(--color-surface);
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  color: var(--color-on-surface-variant);
  transition: all 0.2s ease;
}

.page-btn:hover:not(:disabled) {
  background: rgba(185, 121, 204, 0.1);
  border-color: #990dd1;
  color: var(--color-primary-text);
}

.page-btn.active {
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
  color: white;
  border-color: #990dd1;
}

.page-btn.disabled {
  opacity: 0.3;
  cursor: not-allowed;
}

/* Responsive */
@media (max-width: 1024px) {
  .stats-container {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .stats-container {
    grid-template-columns: 1fr;
  }
  
  .filter-inline {
    flex-direction: column;
    align-items: stretch;
  }
  
  .filter-actions {
    justify-content: flex-end;
  }
  
  .pagination-container {
    flex-direction: column;
  }
  
  .table-header-cell,
  .table-cell {
    padding: 0.75rem 1rem;
  }
}
</style>
