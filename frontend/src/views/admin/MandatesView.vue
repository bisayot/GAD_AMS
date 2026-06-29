<template>
  <main class="main-viewport flex-1 overflow-y-auto" style="background: linear-gradient(135deg, #0f172a 0%, #1e1e2f 100%); min-height: 100vh; padding: 2rem;">
        <div class="mx-auto">

          <div class="hero-section">
            <div class="hero-content">
              <div class="hero-text">
                <h1>Office of Student Services - GAD Mandates</h1>
                <p>Gender and Development mandates where the Office of Student Services (OSS) is the responsible unit, as outlined in the BSU GAD Plan and Budget (GPB) 2026.</p>
                <div class="hero-stats">
                  <div class="stat-badge">
                    <div class="number">{{ totalMandates }}</div>
                    <div class="label">Total Mandates</div>
                  </div>
                  <div class="stat-badge">
                    <div class="number">{{ activeMandates }}</div>
                    <div class="label">Active</div>
                  </div>
                  <div class="stat-badge">
                    <div class="number">0</div>
                    <div class="label">Total Budget</div>
                  </div>
                </div>
              </div>
              
              <router-link to="/admin/gad-plan-budget" class="gad-plan-card">
                <div class="gad-plan-icon">📜</div>
                <h3>GAD Plan & Budget</h3>
                <p>View GPB 2026 document</p>
                <span class="gad-plan-arrow">→</span>
              </router-link>
            </div>
          </div>
 
          <div class="filter-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div style="display: flex; gap: 15px;">
              <div class="filter-group">
                <span class="filter-label">Filter:</span>
                <select v-model="filters.status" class="filter-select" @change="applyFilters">
                  <option value="all">All Mandates</option>
                  <option value="active">In Progress</option>
                  <option value="completed">Completed</option>
                  <option value="upcoming">Upcoming</option>
                </select>
              </div>
              <div class="filter-group">
                <div class="search-box">
                  <span>🔍</span>
                  <input 
                    type="text" 
                    v-model="filters.search" 
                    placeholder="Search mandates..." 
                    @keyup.enter="applyFilters"
                  >
                </div>
                <button class="btn-reset btn-primary-custom" @click="applyFilters">Apply</button>
                <button class="btn-reset" @click="resetFilters">Reset</button>
              </div>
            </div>
            
            <button class="btn-primary-custom" @click="openMandateModal(null)" style="padding: 10px 20px; font-weight: bold; background: #b979cc; color: #16213e; border: none; border-radius: 8px; cursor: pointer;">
              + Add New Mandate
            </button>
          </div>

          <div v-if="loading" class="loading-state">
            <div class="loading-spinner"></div>
            <p>Loading mandates...</p>
          </div>

          <div v-else class="table-wrapper">
            <div class="table-header" style="display: grid; grid-template-columns: 50px 1fr 200px 150px; gap: 20px; color: #cbd5e1; font-size: 13px; font-weight: 600; padding: 12px 16px; background: rgba(255,255,255,0.05); border-radius: 8px;">
              <div>#</div>
              <div>Gender Issue / GAD Mandate</div>
              <div style="text-align: center;">Responsible Unit</div>
              <div>Actions</div>
            </div>

            <div v-if="paginatedMandates.length === 0" class="empty-state">
              <span class="empty-icon">📭</span>
              <p>No OSS mandates found matching your criteria</p>
              <button @click="resetFilters" class="btn-reset">Clear all filters</button>
            </div>

            <div 
              v-for="(item, idx) in paginatedMandates" 
              :key="item.id"
              class="mandate-row-container"
              style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 15px 0;"
            >
              <div class="mandate-row" style="display: grid; grid-template-columns: 50px 1fr 200px 150px; gap: 20px; align-items: center; text-decoration: none; cursor: pointer; padding: 0 16px;" @click="toggleExpand(item.id)">
                <div class="mandate-number" style="color: #64748b; font-weight: 500;">{{ String(startIndex + idx + 1).padStart(2, '01') }}</div>
                <div class="mandate-title" style="color: #fff; font-weight: 600;">
                  {{ item.code }} - {{ item.title }}
                  <div class="mandate-meta" style="margin-top: 6px; display: flex; align-items: center; gap: 10px;">
                    <span class="status-badge" :class="getStatusClass(item.status)">{{ getStatusText(item.status) }}</span>
                    <span class="budget-text" style="color: #64748b; font-size: 12px;">Budget: {{ item.budget }}</span>
                    <span style="font-size: 11px; color: #b979cc;">{{ item.gender_issues?.length || 0 }} Gender Issues {{ expandedMandates.includes(item.id) ? '▲' : '▼' }}</span>
                  </div>
                </div>
                <div class="mandate-office" style="color: #cbd5e1; font-size: 14px; text-align: center;">{{ item.responsible_unit }}</div>
                <div class="mandate-actions" style="display: flex; gap: 10px;" @click.stop>
                  <button @click="openMandateModal(item)" style="background: transparent; color: #b979cc; border: 1px solid #b979cc; border-radius: 4px; padding: 6px 12px; cursor: pointer; font-size: 12px;">Edit</button>
                  <button @click="deleteMandate(item.id)" style="background: transparent; color: #ff4d4f; border: 1px solid #ff4d4f; border-radius: 4px; padding: 6px 12px; cursor: pointer; font-size: 12px;">Delete</button>
                </div>
              </div>
              
              <!-- Expanded Gender Issues -->
              <div v-if="expandedMandates.includes(item.id)" class="gender-issues-container" style="padding: 15px 15px 15px 70px; background: rgba(0,0,0,0.2); margin-top: 15px; border-radius: 8px; margin-left: 16px; margin-right: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                  <h4 style="margin: 0; color: #fff; font-size: 14px;">Associated Gender Issues</h4>
                  <button @click="openIssueModal(null, item.id)" style="background: #b979cc; color: #16213e; border: none; border-radius: 4px; padding: 6px 12px; cursor: pointer; font-size: 12px; font-weight: bold;">+ Add Issue</button>
                </div>
                <div v-if="!item.gender_issues || item.gender_issues.length === 0" style="color: #64748b; font-size: 13px;">No gender issues found.</div>
                <ul v-else style="list-style: none; padding: 0; margin: 0; font-size: 13px;">
                  <li v-for="issue in item.gender_issues" :key="issue.id" style="display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed rgba(255,255,255,0.1);">
                    <div style="flex: 1; padding-right: 20px;">
                      <strong style="color: #cbd5e1; display: block; margin-bottom: 4px;">{{ issue.title }}</strong>
                      <p style="margin: 0; color: #64748b; line-height: 1.4;">{{ issue.gad_objective }}</p>
                    </div>
                    <div style="display: flex; gap: 8px; align-items: flex-start;">
                      <span class="status-badge" :class="getStatusClass(issue.status)" style="font-size: 11px; padding: 3px 8px;">{{ getStatusText(issue.status) }}</span>
                      <button @click="openIssueModal(issue, item.id)" style="background: none; border: none; color: #b979cc; cursor: pointer; font-size: 13px; padding: 0 4px; text-decoration: underline;">Edit</button>
                      <button @click="deleteIssue(issue.id)" style="background: none; border: none; color: #ff4d4f; cursor: pointer; font-size: 13px; padding: 0 4px; text-decoration: underline;">Delete</button>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="pagination" v-if="!loading && totalPages > 1">
            <div class="pagination-info">
              Showing {{ startIndex + 1 }} - {{ Math.min(startIndex + itemsPerPage, filteredMandates.length) }} of {{ filteredMandates.length }} OSS mandates
            </div>
            <div class="pagination-buttons">
              <button class="page-btn" :class="{ disabled: currentPage === 1 }" @click="changePage(currentPage - 1)" :disabled="currentPage === 1">←</button>
              
              <button 
                v-for="page in visiblePages" 
                :key="page"
                class="page-btn" 
                :class="{ active: page === currentPage }"
                @click="changePage(page)"
              >
                {{ page }}
              </button>
              
              <button class="page-btn" :class="{ disabled: currentPage === totalPages }" @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages">→</button>
            </div>
          </div>
          
          <div class="footer-note">
            <p>Showing OSS (Office of Student Services) mandates as outlined in the BSU GAD Plan and Budget (GPB) 2026 | Last updated: {{ lastUpdated }}</p>
          </div>

          <!-- Mandate Modal -->
          <div v-if="showMandateModal" class="modal-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); display: flex; justify-content: center; align-items: center; z-index: 1000;">
            <div class="modal-content" style="background: #1a1a2e; padding: 30px; border-radius: 12px; border: 1px solid #b979cc; width: 500px; max-width: 90%;">
              <h3 style="margin-top: 0; color: #fff; font-size: 18px; margin-bottom: 20px;">{{ editingMandate ? 'Edit Mandate' : 'Add New Mandate' }}</h3>
              
              <div class="input-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; color: #cbd5e1; font-size: 13px;">Mandate Code *</label>
                <input v-model="mandateForm.code" type="text" class="custom-input-field" style="width: 100%; padding: 10px; border-radius: 6px; background: rgba(15,23,42,0.5); border: 1px solid rgba(255,255,255,0.1); color: #fff; box-sizing: border-box;" required placeholder="e.g. GAD-IEC">
              </div>
              
              <div class="input-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; color: #cbd5e1; font-size: 13px;">Mandate Title *</label>
                <textarea v-model="mandateForm.title" rows="3" class="custom-input-field" style="width: 100%; padding: 10px; border-radius: 6px; background: rgba(15,23,42,0.5); border: 1px solid rgba(255,255,255,0.1); color: #fff; box-sizing: border-box;" required placeholder="Enter mandate title..."></textarea>
              </div>

              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div class="input-group">
                  <label style="display: block; margin-bottom: 6px; color: #cbd5e1; font-size: 13px;">Status</label>
                  <select v-model="mandateForm.status" class="custom-input-field" style="width: 100%; padding: 10px; border-radius: 6px; background: rgba(15,23,42,0.5); border: 1px solid rgba(255,255,255,0.1); color: #fff; box-sizing: border-box;">
                    <option value="active" class="dark-option">In Progress</option>
                    <option value="completed" class="dark-option">Completed</option>
                    <option value="upcoming" class="dark-option">Upcoming</option>
                  </select>
                </div>
                <div class="input-group">
                  <label style="display: block; margin-bottom: 6px; color: #cbd5e1; font-size: 13px;">Budget</label>
                  <input v-model="mandateForm.budget" type="text" class="custom-input-field" style="width: 100%; padding: 10px; border-radius: 6px; background: rgba(15,23,42,0.5); border: 1px solid rgba(255,255,255,0.1); color: #fff; box-sizing: border-box;" placeholder="₱0.00">
                </div>
              </div>

              <div class="input-group" style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 6px; color: #cbd5e1; font-size: 13px;">Responsible Unit</label>
                <input v-model="mandateForm.responsible_unit" type="text" class="custom-input-field" style="width: 100%; padding: 10px; border-radius: 6px; background: rgba(15,23,42,0.5); border: 1px solid rgba(255,255,255,0.1); color: #fff; box-sizing: border-box;" placeholder="e.g. OSS">
              </div>
              
              <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button @click="showMandateModal = false" style="padding: 10px 18px; background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; cursor: pointer;">Cancel</button>
                <button @click="saveMandate" style="padding: 10px 18px; background: #b979cc; color: #16213e; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">Save Mandate</button>
              </div>
            </div>
          </div>

          <!-- Issue Modal -->
          <div v-if="showIssueModal" class="modal-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); display: flex; justify-content: center; align-items: center; z-index: 1000;">
            <div class="modal-content" style="background: #1a1a2e; padding: 30px; border-radius: 12px; border: 1px solid #b979cc; width: 500px; max-width: 90%;">
              <h3 style="margin-top: 0; color: #fff; font-size: 18px; margin-bottom: 20px;">{{ editingIssue ? 'Edit Gender Issue' : 'Add New Gender Issue' }}</h3>
              
              <div class="input-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; color: #cbd5e1; font-size: 13px;">Issue Title *</label>
                <textarea v-model="issueForm.title" rows="2" class="custom-input-field" style="width: 100%; padding: 10px; border-radius: 6px; background: rgba(15,23,42,0.5); border: 1px solid rgba(255,255,255,0.1); color: #fff; box-sizing: border-box;" required placeholder="Enter issue title..."></textarea>
              </div>
              
              <div class="input-group" style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 6px; color: #cbd5e1; font-size: 13px;">GAD Objective</label>
                <textarea v-model="issueForm.gad_objective" rows="3" class="custom-input-field" style="width: 100%; padding: 10px; border-radius: 6px; background: rgba(15,23,42,0.5); border: 1px solid rgba(255,255,255,0.1); color: #fff; box-sizing: border-box;" placeholder="Enter objective..."></textarea>
              </div>

              <div class="input-group" style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 6px; color: #cbd5e1; font-size: 13px;">Status</label>
                <select v-model="issueForm.status" class="custom-input-field" style="width: 100%; padding: 10px; border-radius: 6px; background: rgba(15,23,42,0.5); border: 1px solid rgba(255,255,255,0.1); color: #fff; box-sizing: border-box;">
                  <option value="active" class="dark-option">In Progress</option>
                  <option value="completed" class="dark-option">Completed</option>
                  <option value="upcoming" class="dark-option">Upcoming</option>
                </select>
              </div>
              
              <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button @click="showIssueModal = false" style="padding: 10px 18px; background: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; cursor: pointer;">Cancel</button>
                <button @click="saveIssue" style="padding: 10px 18px; background: #b979cc; color: #16213e; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">Save Issue</button>
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

const mandates = ref([]);
const loading = ref(false);
const expandedMandates = ref([]);

const showMandateModal = ref(false);
const editingMandate = ref(false);
const mandateForm = ref({ id: null, code: '', title: '', status: 'active', budget: '', responsible_unit: 'OSS' });

const showIssueModal = ref(false);
const editingIssue = ref(false);
const issueForm = ref({ id: null, mandate_id: null, title: '', gad_objective: '', status: 'active' });

const filters = ref({
  status: 'all',
  search: ''
});

const currentPage = ref(1);
const itemsPerPage = 10;

const totalMandates = computed(() => mandates.value.length);
const activeMandates = computed(() => mandates.value.filter(m => m.status === 'active').length);

const filteredMandates = computed(() => {
  let result = [...mandates.value];
  
  if (filters.value.status !== 'all') {
    result = result.filter(m => m.status === filters.value.status);
  }
  
  if (filters.value.search.trim()) {
    const searchTerm = filters.value.search.toLowerCase();
    result = result.filter(m => m.title.toLowerCase().includes(searchTerm));
  }
  
  return result;
});

const totalPages = computed(() => Math.ceil(filteredMandates.value.length / itemsPerPage));
const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage);
const paginatedMandates = computed(() => {
  return filteredMandates.value.slice(startIndex.value, startIndex.value + itemsPerPage);
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

const fetchMandates = async () => {
  loading.value = true;
  try {
    const response = await api.get('mandates');
    if (response.data && response.data.success) {
      mandates.value = response.data.data;
    }
  } catch (error) {
    console.error('Error fetching mandates:', error);
  } finally {
    loading.value = false;
  }
};

const toggleExpand = (id) => {
  if (expandedMandates.value.includes(id)) {
    expandedMandates.value = expandedMandates.value.filter(mId => mId !== id);
  } else {
    expandedMandates.value.push(id);
  }
};

const openMandateModal = (mandate = null) => {
  if (mandate) {
    editingMandate.value = true;
    mandateForm.value = { ...mandate };
  } else {
    editingMandate.value = false;
    mandateForm.value = { id: null, code: '', title: '', status: 'active', budget: '', responsible_unit: 'OSS' };
  }
  showMandateModal.value = true;
};

const saveMandate = async () => {
  try {
    if (editingMandate.value) {
      await api.put(`mandates/${mandateForm.value.id}`, mandateForm.value);
    } else {
      await api.post('mandates', mandateForm.value);
    }
    showMandateModal.value = false;
    fetchMandates();
  } catch (error) {
    console.error('Error saving mandate:', error);
    alert('Failed to save mandate');
  }
};

const deleteMandate = async (id) => {
  if (confirm('Are you sure you want to delete this mandate and all its associated gender issues?')) {
    try {
      await api.delete(`mandates/${id}`);
      fetchMandates();
    } catch (error) {
      console.error('Error deleting mandate:', error);
      alert('Failed to delete mandate');
    }
  }
};

const openIssueModal = (issue = null, mandateId) => {
  if (issue) {
    editingIssue.value = true;
    issueForm.value = { ...issue };
  } else {
    editingIssue.value = false;
    issueForm.value = { id: null, mandate_id: mandateId, title: '', gad_objective: '', status: 'active' };
  }
  showIssueModal.value = true;
};

const saveIssue = async () => {
  try {
    if (editingIssue.value) {
      await api.put(`gender-issues/${issueForm.value.id}`, issueForm.value);
    } else {
      await api.post('gender-issues', issueForm.value);
    }
    showIssueModal.value = false;
    fetchMandates();
  } catch (error) {
    console.error('Error saving issue:', error);
    alert('Failed to save gender issue');
  }
};

const deleteIssue = async (id) => {
  if (confirm('Are you sure you want to delete this gender issue?')) {
    try {
      await api.delete(`gender-issues/${id}`);
      fetchMandates();
    } catch (error) {
      console.error('Error deleting issue:', error);
      alert('Failed to delete gender issue');
    }
  }
};

const applyFilters = () => {
  currentPage.value = 1;
};

const resetFilters = () => {
  filters.value = {
    status: 'all',
    search: ''
  };
  currentPage.value = 1;
};

const changePage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const getStatusClass = (status) => {
  const classes = {
    active: 'status-active',
    completed: 'status-completed',
    upcoming: 'status-upcoming'
  };
  return classes[status] || 'status-active';
};

const getStatusText = (status) => {
  const texts = {
    active: 'In Progress',
    completed: 'Completed',
    upcoming: 'Upcoming'
  };
  return texts[status] || 'In Progress';
};

const lastUpdated = computed(() => {
  const date = new Date();
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
});

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
  fetchMandates();
});
</script>

<style scoped>
.max-w-7xl {
  max-width: 80rem;
  margin: 0 auto;
}

.hero-section {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border-radius: 1.75rem;
  padding: 2rem 2.5rem;
  margin-bottom: 2rem;
  position: relative;
  overflow: hidden;
  border: 1px solid rgba(185, 121, 204, 0.3);
}

.hero-section::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -20%;
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, rgba(185, 121, 204, 0.15) 0%, rgba(185, 121, 204, 0) 70%);
  border-radius: 50%;
  pointer-events: none;
}

.hero-content {
  position: relative;
  z-index: 10;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1.5rem;
}

.hero-text {
  flex: 1;
}

.hero-text h1 {
  font-size: 2rem;
  font-weight: 800;
  color: #ffffff;
  margin-bottom: 0.5rem;
  letter-spacing: -0.02em;
}

.hero-text p {
  font-size: 0.95rem;
  color: #cbd5e1;
  max-width: 550px;
  line-height: 1.5;
}

.hero-stats {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}

.stat-badge {
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
  padding: 0.75rem 1.5rem;
  text-align: center;
  border: 1px solid rgba(185, 121, 204, 0.3);
}

.stat-badge .number {
  font-size: 1.75rem;
  font-weight: 800;
  color: #b979cc;
}

.stat-badge .label {
  font-size: 0.95rem;
  color: #a0aec0;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  margin-top: 0.25rem;
}

.gad-plan-card {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  border-radius: 1.25rem;
  padding: 1.25rem 1.5rem;
  text-align: center;
  border: 1px solid rgba(185, 121, 204, 0.3);
  transition: all 0.3s ease;
  cursor: pointer;
  text-decoration: none;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  min-width: 180px;
}

.gad-plan-card:hover {
  background: rgba(185, 121, 204, 0.15);
  transform: translateY(-2px);
  border-color: #b979cc;
}

.gad-plan-icon {
  width: 48px;
  height: 48px;
  background: rgba(185, 121, 204, 0.2);
  border-radius: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
}

.gad-plan-card h3 {
  font-size: 1rem;
  font-weight: 700;
  color: #ffffff;
  margin: 0;
}

.gad-plan-card p {
  font-size: 1rem;
  color: #a0aec0;
  margin: 0;
}

.gad-plan-arrow {
  font-size: 1.25rem;
  color: #b979cc;
}

/* Filter Bar */
.filter-bar {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border-radius: 1rem;
  padding: 1rem 1.5rem;
  margin-bottom: 1.75rem;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.filter-label {
  font-size: 1rem;
  font-weight: 700;
  text-transform: uppercase;
  color: #b979cc;
  letter-spacing: 0.03em;
}

.filter-select {
  padding: 0.5rem 1rem;
  border-radius: 0.6rem;
  border: 1px solid rgba(255, 255, 255, 0.15);
  font-size: 0.8rem;
  font-family: inherit;
  cursor: pointer;
  color: #ffffff;
}

.filter-select option {
  background: #1a1a2e;
  color: #e2e8f0;
}

.filter-select:focus {
  outline: none;
  border-color: #b979cc;
  box-shadow: 0 0 0 2px rgba(185, 121, 204, 0.2);
}

.search-box {
  position: relative;
}

.search-box input {
  padding: 0.5rem 1rem 0.5rem 2.25rem;
  width: 260px;
  border-radius: 0.6rem;
  border: 1px solid rgba(255, 255, 255, 0.15);
  font-size: 0.8rem;
  font-family: inherit;
  color: #ffffff;
}

.search-box input::placeholder {
  color: #ffffff;
}

.search-box input:focus {
  outline: none;
  border-color: #b979cc;
  box-shadow: 0 0 0 2px rgba(185, 121, 204, 0.2);
}

.search-box span {
  position: absolute;
  left: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.85rem;
  opacity: 0.6;
  color: #ffffff;
}

.btn-reset {
  padding: 0.5rem 1.25rem;
  border-radius: 0.6rem;
  border: 1px solid rgba(255, 255, 255, 0.15);
  background: rgba(0, 0, 0, 0.3);
  font-size: 0.8rem;
  font-weight: 600;
  color: #cbd5e1;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  display: inline-block;
}

.btn-reset:hover {
  background: rgba(185, 121, 204, 0.15);
  border-color: #b979cc;
  color: #b979cc;
}

.btn-primary-custom {
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
  color: white;
  border: none;
}

.btn-primary-custom:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(153, 13, 209, 0.4);
  color: white;
}

/* Loading State */
.loading-state {
  background: rgba(255, 255, 255, 0.04);
  border-radius: 1.25rem;
  padding: 4rem;
  text-align: center;
  border: 1px solid rgba(185, 121, 204, 0.15);
}

.loading-state p {
  color: #cbd5e1;
  margin-top: 1rem;
  font-size: 0.9rem;
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 3px solid rgba(185, 121, 204, 0.2);
  border-top-color: #b979cc;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Table */
.table-wrapper {
  background: rgba(255, 255, 255, 0.04);
  border-radius: 1.25rem;
  overflow: hidden;
  border: 1px solid rgba(185, 121, 204, 0.15);
}

.table-header {
  display: grid;
  grid-template-columns: 60px 1fr 180px;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  padding: 1rem 1.5rem;
  border-bottom: 1px solid rgba(185, 121, 204, 0.15);
  font-size: 1rem;
  font-weight: 800;
  text-transform: uppercase;
  color: #cbd5e1;
  letter-spacing: 0.03em;
}

.mandate-row {
  display: grid;
  grid-template-columns: 60px 1fr 180px;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  transition: all 0.2s;
  cursor: pointer;
  text-decoration: none;
}

.mandate-row:hover {
  background: rgba(185, 121, 204, 0.08);
}

.mandate-number {
  font-weight: 700;
  color: #b979cc;
  font-size: 0.9rem;
}

.mandate-title {
  font-weight: 500;
  color: #f1f5f9;
  font-size: 0.9rem;
  line-height: 1.4;
}

.mandate-meta {
  margin-top: 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.status-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 30px;
  font-size: 0.95rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.02em;
}

.status-active {
  background: rgba(245, 124, 0, 0.2);
  color: #ff9800;
  border: 1px solid rgba(245, 124, 0, 0.3);
}

.status-completed {
  background: rgba(46, 125, 50, 0.2);
  color: #66bb6a;
  border: 1px solid rgba(46, 125, 50, 0.3);
}

.status-upcoming {
  background: rgba(21, 101, 192, 0.2);
  color: #64b5f6;
  border: 1px solid rgba(21, 101, 192, 0.3);
}

.budget-text {
  font-size: 1rem;
  color: #cbd5e1;
}

.mandate-office {
  font-size: 0.85rem;
  color: #cbd5e1;
  font-weight: 500;
}

/* Empty State */
.empty-state {
  background: #cbd5e1;
  padding: 3rem;
  text-align: center;
  color: #16213e;
}

.empty-icon {
  font-size: 3rem;
  display: block;
  margin-bottom: 1rem;
}

.empty-reset {
  color: #b979cc;
  margin-top: 0.75rem;
  display: inline-block;
  text-decoration: none;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 0.9rem;
}

.empty-reset:hover {
  color: #990dd1;
}

/* Pagination */
.pagination {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.5rem;
  background: rgba(255, 255, 255, 0.04);
  border-top: 1px solid rgba(185, 121, 204, 0.15);
  border-radius: 0 0 1.25rem 1.25rem;
}

.pagination-info {
  font-size: 0.8rem;
  color: #cbd5e1;
}

.pagination-buttons {
  display: flex;
  gap: 0.5rem;
}

.page-btn {
  width: 2rem;
  height: 2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.5rem;
  border: 1px solid rgba(255, 255, 255, 0.15);
  background: rgba(0, 0, 0, 0.3);
  font-size: 0.8rem;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
  color: #cbd5e1;
}

.page-btn:hover {
  background: rgba(185, 121, 204, 0.15);
  border-color: #b979cc;
  color: #b979cc;
}

.page-btn.active {
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
  border-color: #990dd1;
  color: white;
}

.page-btn.disabled {
  opacity: 0.4;
  cursor: not-allowed;
  pointer-events: none;
}

/* Footer */
.footer-note {
  text-align: center;
  margin-top: 1.5rem;
  font-size: 1rem;
  color: #cbd5e1;
}

/* Responsive */
@media (max-width: 1024px) {
  .hero-content {
    flex-direction: column;
    text-align: center;
  }
  .hero-text p {
    max-width: 100%;
  }
  .gad-plan-card {
    width: 100%;
    max-width: 250px;
    margin: 0 auto;
  }
}

@media (max-width: 768px) {
  .hero-section {
    padding: 1.5rem;
  }
  .hero-text h1 {
    font-size: 1.5rem;
  }
  .table-header, .mandate-row {
    grid-template-columns: 50px 1fr 120px;
    padding: 1rem;
  }
  .filter-bar {
    flex-direction: column;
    align-items: stretch;
  }
  .search-box input {
    width: 100%;
  }
  .pagination {
    flex-direction: column;
    gap: 1rem;
  }
  .hero-stats {
    flex-wrap: wrap;
    justify-content: center;
  }
}
</style>
