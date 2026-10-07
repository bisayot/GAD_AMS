<template>
      <main class="flex-1 overflow-y-auto bg-transparent">
        <div class="max-w-7xl mx-auto">

            <div class="stats-container">

            <div class="stat-card-purple">
                <div class="stat-card-inner">
                <div class="stat-icon-wrapper purple">
                    <span class="material-symbols-outlined">description</span>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number-purple">{{ totalActive }}</h3>
                    <p class="stat-label-purple">TOTALLY ACTIVE</p>
                </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-inner">
                <div class="stat-icon-wrapper blue">
                    <span class="material-symbols-outlined">description</span>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ totalDesigns }}</h3>
                    <p class="stat-label">Activity Designs</p>
                </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-inner">
                <div class="stat-icon-wrapper green">
                    <span class="material-symbols-outlined">assessment</span>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ totalReports }}</h3>
                    <p class="stat-label">Accomplishment Reports</p>
                </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-inner">
                <div class="stat-icon-wrapper amber">
                    <span class="material-symbols-outlined">schedule</span>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ pendingCount }}</h3>
                    <p class="stat-label">PENDING REVIEW</p>
                </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-card-inner">
                <div class="stat-icon-wrapper amber" style="background: rgba(220, 38, 38, 0.1);">
                    <span class="material-symbols-outlined" style="color: #ef4444;">cancel</span>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ disapprovedCount }}</h3>
                    <p class="stat-label" style="color: #ef4444;">DISAPPROVED</p>
                </div>
                </div>
            </div>
            </div><br>

          <div class="tabs-container">
            <div class="tabs-header">
              <button 
                @click="activeTab = 'design'" 
                class="tab-btn"
                :class="{ 'tab-active': activeTab === 'design', 'tab-inactive': activeTab !== 'design' }"
              >
                Activity Designs
                <span class="tab-badge">{{ totalDesigns }}</span>
              </button>
              <button 
                @click="activeTab = 'report'" 
                class="tab-btn"
                :class="{ 'tab-active': activeTab === 'report', 'tab-inactive': activeTab !== 'report' }"
              >
                Accomplishment Reports
                <span class="tab-badge">{{ totalReports }}</span>
              </button>
            </div>
          </div>

          <div class="filter-card">
            <div class="filter-inline">
              <div class="filter-item">
                <label class="filter-label">STATUS</label>
                <div class="select-wrapper">
                  <select v-model="filters.status" class="filter-select-custom" @change="applyFilters">
                    <option value="all">All Status</option>
                    <option value="pending">Pending Review</option>
                    <option value="revision">For Revision</option>
                    <option value="disapproved">Disapproved</option>
                  </select>
                  <span class="select-arrow">▼</span>
                </div>
              </div>

              <div class="filter-item">
                <label class="filter-label">SORT BY</label>
                <div class="select-wrapper">
                  <select v-model="filters.sort" class="filter-select-custom" @change="applyFilters">
                    <option value="oldest_submission">Oldest Submission</option>
                    <option value="newest_submission">Newest Submission</option>
                    <option value="earliest_implementation">Earliest Implementation Date</option>
                    <option value="latest_implementation">Furthest Implementation Date</option>
                    <option value="title_asc">Title (A-Z)</option>
                    <option value="title_desc">Title (Z-A)</option>
                  </select>
                  <span class="select-arrow">▼</span>
                </div>
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

              <div class="filter-actions">
                <button class="btn-primary-custom" @click="applyFilters">Apply Filters</button>
                <button class="btn-secondary-custom" @click="resetFilters">Clear</button>
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
            <p>Loading submissions...</p>
          </div>

          <div v-else class="data-table">
            <div class="overflow-x-auto">
              <table class="data-table-inner">
                <thead>
                  <tr class="table-header-row">
                    <th class="table-header-cell">TYPE</th>
                    <th class="table-header-cell">CONTROL NUMBER</th>
                    <th class="table-header-cell">ACTIVITY TITLE</th>
                    <th class="table-header-cell">FORMAT TYPE</th>
                    <th class="table-header-cell">STATUS</th>
                   </tr>
                </thead>
                <tbody>
                  <tr v-if="paginatedItems.length === 0" class="empty-row">
                    <td colspan="5" class="empty-cell">
                      <div class="empty-content">
                        <span class="empty-emoji">📭</span>
                        <p>No records found matching your criteria</p>
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
                      <div class="item-date">{{ item.date }}</div>
                    </td>
                    <td class="table-cell">
                      <div class="item-title" style="display: flex; align-items: center; gap: 0.5rem;">
                        {{ item.title }}
                        <span v-if="isRush(item)" class="rush-badge">RUSH</span>
                      </div>
                    </td>
                    <td class="table-cell">
                      <span class="form-badge" :class="item.formClass">
                        {{ item.formLabel }}
                      </span>
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
import api from '../../api';

const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const submissions = ref([]);
const loading = ref(false);
const activeTab = ref('design');

const filters = ref({
  status: 'all',
  sort: 'oldest_submission',
  search: ''
});

const currentPage = ref(1);
const itemsPerPage = 10;

const filteredItems = computed(() => {
  let items = submissions.value.filter(item => item.type === activeTab.value);
  
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
  
  const sorted = [...items];
  
  sorted.sort((a, b) => {
    switch (filters.value.sort) {
      case 'newest_submission':
        return b.id - a.id;
      case 'oldest_submission':
        return a.id - b.id;
      case 'earliest_implementation': {
        const byDate = new Date(a.dateRaw) - new Date(b.dateRaw);
        return byDate !== 0 ? byDate : a.id - b.id;
      }
      case 'latest_implementation': {
        const byDate = new Date(b.dateRaw) - new Date(a.dateRaw);
        return byDate !== 0 ? byDate : b.id - a.id;
      }
      case 'title_asc':
        return (a.title || '').localeCompare(b.title || '');
      case 'title_desc':
        return (b.title || '').localeCompare(a.title || '');
      default:
        return a.id - b.id;
    }
  });
  
  return sorted;
});

const totalActive = computed(() => {
  return submissions.value.filter(item => item.status === 'pending' || item.status === 'revision' || item.status === 'disapproved').length;
});

const totalDesigns = computed(() => {
  return submissions.value.filter(item => item.type === 'design').length;
});

const totalReports = computed(() => {
  return submissions.value.filter(item => item.type === 'report').length;
});

const pendingCount = computed(() => {
  return submissions.value.filter(item => item.status === 'pending').length;
});

const disapprovedCount = computed(() => {
  return submissions.value.filter(item => item.status === 'disapproved').length;
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

const fetchSubmissions = async () => {
  loading.value = true;
  try {
    const userId = user.value.id;
    if (!userId) throw new Error('No user ID found');

    const [designsRes, reportsRes] = await Promise.all([
      api.get(`activity-designs/${userId}`),
      api.get(`activity-reports/${userId}`)
    ]);

    const mapStatus = (status) => {
      const s = (status || '').toLowerCase();
      if (s === 'revision required') return 'revision';
      return s;
    };

    const designs = (designsRes.data.data || []).map(d => {
      const st = mapStatus(d.status);
      return {
        type: 'design',
        id: d.act_design_id,
        status: st,
        title: d.title || d.activity_title || 'Untitled',
        control: d.control || 'NO CONTROL NUMBER',
        dateRaw: d.date,
        startDateRaw: d.start_date,
        date: d.date,
        formClass: 'badge-purple',
        formLabel: d.formLabel || 'Activity Design',
        statusClass: `status-${st.replace(' ', '-')}`,
        statusText: d.status
      };
    });

    const reports = (reportsRes.data.data || []).map(r => {
      const st = mapStatus(r.status);
      return {
        type: 'report',
        id: r.id,
        status: st,
        title: r.title || r.activity_title || 'Untitled',
        control: r.control || 'NO CONTROL NUMBER',
        dateRaw: r.date,
        date: r.date,
        formClass: 'badge-blue',
        formLabel: 'Accomplishment Report',
        statusClass: `status-${st.replace(' ', '-')}`,
        statusText: r.status
      };
    });

    submissions.value = [...designs, ...reports];
    
  } catch (error) {
    console.error('Error fetching submissions:', error);
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
    sort: 'oldest_submission',
    search: ''
  };
  currentPage.value = 1;
};

const isRush = (item) => {
  if (item.type !== 'design' || item.status !== 'pending') return false;
  const startDate = new Date(item.startDateRaw);
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  startDate.setHours(0, 0, 0, 0);
  const diffTime = startDate - today;
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return diffDays >= 3 && diffDays < 14;
};

const changePage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const viewItem = (item) => {
  if (item.type === 'design') {
    if (item.status === 'revision') {
      router.push(`/college/ad-revision/${item.id}`);
    } else {
      router.push(`/college/ad-view/${item.id}`);
    }
  } else {
    if (item.status === 'revision') {
      router.push(`/college/ar-revision/${item.id}`);
    } else {
      router.push(`/college/ar-view/${item.id}`);
    }
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
  if (!user.value.id || !['twg', 'non-twg'].includes(user.value.role)) {
    router.push('/login');
  }
  fetchSubmissions();
});
</script>

<style scoped src="../../assets/college-submitted-list-styles.css"></style>
