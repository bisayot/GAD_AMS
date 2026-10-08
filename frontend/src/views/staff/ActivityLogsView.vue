<template>
  <div class="page-container">
    <div class="header-section">
      <h1 class="page-title">Activity Logs</h1>
      <p class="page-subtitle">Track and monitor recent activities from all staff and users.</p>
    </div>

    <!-- Stats / Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
      <div class="stat-card">
        <div class="stat-icon stat-icon-purple">
          <span class="material-symbols-outlined">history</span>
        </div>
        <div class="stat-content">
          <h3 class="stat-label">Total Activities Recorded</h3>
          <p class="stat-value">{{ allLogs.length }}</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon stat-icon-blue">
          <span class="material-symbols-outlined">person_search</span>
        </div>
        <div class="stat-content">
          <h3 class="stat-label">Recent Active Users</h3>
          <p class="stat-value">{{ uniqueActiveUsers }}</p>
        </div>
      </div>
    </div>

    <div class="layout-grid">
      <!-- Left Column: Activity Logs List -->
      <section class="flex-06 glass-card">
        <div class="card-header flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
          <h2 class="card-title flex items-center gap-2">
            <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">list_alt</span>
            Detailed Activity Logs
          </h2>
          
          <div class="filters-container w-full md:w-auto flex flex-col sm:flex-row gap-3">
            <select v-model="roleFilter" class="filter-select">
              <option value="">All Roles</option>
              <option value="gad_staff">GAD Staff</option>
              <option value="twg">TWG</option>
              <option value="non_twg">Proponents</option>
            </select>
            
            <div class="relative flex-grow" v-if="roleFilter === 'twg' || roleFilter === 'non_twg'">
              <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
              <input 
                type="text" 
                v-model="emailSearch"
                placeholder="Search by email..."
                class="search-input"
              />
            </div>
          </div>
        </div>
        
        <!-- Tabs -->
        <div class="log-tabs-bar flex">
          <button 
            @click="activeTab = 'main'"
            class="log-tab-btn"
            :class="{ 'active-main': activeTab === 'main' }"
          >
            Main Logs <br>
            <span class="text-xs font-normal opacity-70">(Retained for 1 Year)</span>
          </button>
          <button 
            @click="activeTab = 'operational'"
            class="log-tab-btn"
            :class="{ 'active-operational': activeTab === 'operational' }"
          >
            Operational Logs <br>
            <span class="text-xs font-normal opacity-70">(Retained for 90 Days)</span>
          </button>
        </div>
        
        <div class="p-6">
          <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
          </div>
          
          <div v-else-if="filteredLogs.length === 0" class="text-center py-12 text-slate-400 empty-state-box">
            <span class="material-symbols-outlined text-4xl mb-2 opacity-50">inbox</span>
            <p>No activity logs found matching your filters.</p>
          </div>
          
          <div v-else class="space-y-4 max-h-[600px] overflow-y-auto custom-scrollbar pr-2">
            <div v-for="log in filteredLogs" :key="log.id" class="log-item">
              <div class="log-icon" :class="getActionColor(log.action)">
                <span class="material-symbols-outlined text-sm">{{ getActionIcon(log.action) }}</span>
              </div>
              <div class="log-details flex-grow">
                <p class="log-description">
                  <span class="log-email">{{ log.email || 'System' }}</span> 
                  <span class="log-action-text"> {{ log.description }}</span>
                </p>
                <div class="log-meta flex items-center gap-4 mt-1">
                  <span class="log-time flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                    {{ formatDateTime(log.created_at) }}
                  </span>
                  <span class="role-badge">
                    {{ formatRole(log.custom_role || log.system_role) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Right Column: Recent Activities -->
      <section class="flex-04">
        <div class="glass-card h-full">
          <div class="card-header">
            <h2 class="card-title flex items-center gap-2">
              <span class="material-symbols-outlined text-pink-500">bolt</span>
              Recent Activity
            </h2>
            <p class="card-subtitle text-xs mt-1">Latest system actions across all users.</p>
          </div>
          
          <div class="p-6">
            <div class="timeline-container relative space-y-6">
              <div v-for="log in recentLogs.slice(0, 10)" :key="'recent-'+log.id" class="relative pl-6">
                <div class="timeline-dot absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full"></div>
                <div class="timeline-time mb-0.5">{{ formatTimeAgo(log.created_at) }}</div>
                <div class="timeline-user mb-1">{{ log.email || 'Unknown User' }}</div>
                <div class="timeline-action">{{ log.action }}</div>
              </div>
              <div v-if="recentLogs.length === 0 && !loading" class="empty-state-text text-sm pl-6 py-4">
                No recent activity.
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../api';

const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));
const allLogs = ref([]);
const loading = ref(true);

const roleFilter = ref('');
const emailSearch = ref('');
const activeTab = ref('main');
const operationalActions = ['Login', 'Logout', 'Register User', 'Suspend User', 'Restore User', 'Delete User'];

const fetchLogs = async () => {
  loading.value = true;
  try {
    const res = await api.get('/activity-logs');
    if (res.data.success) {
      allLogs.value = res.data.data;
    }
  } catch (err) {
    console.error('Error fetching logs', err);
  } finally {
    loading.value = false;
  }
};

const recentLogs = computed(() => {
  return [...allLogs.value].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

const filteredLogs = computed(() => {
  let logs = recentLogs.value.filter(log => {
    const isOp = operationalActions.includes(log.action);
    return activeTab.value === 'operational' ? isOp : !isOp;
  });
  
  if (roleFilter.value) {
    if (roleFilter.value === 'twg') {
      logs = logs.filter(l => l.custom_role === 'TWG');
    } else if (roleFilter.value === 'non_twg') {
      logs = logs.filter(l => l.custom_role === 'Non-TWG');
    } else {
      logs = logs.filter(l => l.system_role?.toLowerCase() === roleFilter.value);
    }
  }
  
  if (emailSearch.value && (roleFilter.value === 'twg' || roleFilter.value === 'non_twg')) {
    const q = emailSearch.value.toLowerCase();
    logs = logs.filter(l => l.email?.toLowerCase().includes(q));
  }
  
  return logs;
});

const uniqueActiveUsers = computed(() => {
  const cutoff = new Date(Date.now() - 24 * 60 * 60 * 1000);
  const users = new Set();
  allLogs.value.forEach(log => {
    if (new Date(log.created_at) > cutoff && log.user_id) {
      users.add(log.user_id);
    }
  });
  return users.size;
});

const formatRole = (role) => {
  if (!role) return 'Unknown';
  const r = role.toLowerCase().replace('_', '-');
  if (r === 'non-twg') return 'Proponent';
  if (role === 'admin') return 'Admin';
  if (role === 'director') return 'Director';
  if (role === 'gad_staff') return 'Staff';
  return role;
};

const formatDateTime = (dateStr) => {
  if (!dateStr) return '';
  const utcDateStr = dateStr.endsWith('Z') ? dateStr : dateStr.replace(' ', 'T') + 'Z';
  const d = new Date(utcDateStr);
  return d.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true, timeZone: 'Asia/Manila' });
};

const formatTimeAgo = (dateStr) => {
  if (!dateStr) return '';
  const utcDateStr = dateStr.endsWith('Z') ? dateStr : dateStr.replace(' ', 'T') + 'Z';
  const date = new Date(utcDateStr);
  const now = new Date();
  const seconds = Math.floor((now - date) / 1000);
  
  if (seconds < 60) return 'Just now';
  const minutes = Math.floor(seconds / 60);
  if (minutes < 60) return `${minutes}m ago`;
  const hours = Math.floor(minutes / 60);
  if (hours < 24) return `${hours}h ago`;
  const days = Math.floor(hours / 24);
  if (days < 7) return `${days}d ago`;
  return formatDateTime(dateStr);
};

const getActionIcon = (action) => {
  const map = {
    'Login': 'login',
    'Submit Document': 'post_add',
    'Approve Document': 'check_circle',
    'Update Status': 'edit_note',
    'Update Deadline': 'event',
    'Trash Document': 'delete',
    'Cancel Document': 'cancel',
    'Send Message': 'send',
    'Trash Message': 'delete_outline',
    'Suspend User': 'block',
    'Restore User': 'settings_backup_restore',
    'Delete User': 'person_remove',
    'Register User': 'person_add'
  };
  return map[action] || 'history';
};

const getActionColor = (action) => {
  const map = {
    'Login': 'action-green',
    'Submit Document': 'action-blue',
    'Approve Document': 'action-emerald',
    'Trash Document': 'action-red',
    'Cancel Document': 'action-orange',
    'Send Message': 'action-indigo',
    'Register User': 'action-teal',
    'Delete User': 'action-red'
  };
  return map[action] || 'action-slate';
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'gad_staff') {
    router.push('/login');
  } else {
    fetchLogs();
  }
});
</script>

<style scoped>
.page-container {
  padding: 1rem;
  max-width: 1400px;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 2rem;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  color: #0f172a;
}

.page-subtitle {
  font-size: 1rem;
  color: #64748b;
  margin-top: 0.25rem;
}

/* Stat Cards */
.stat-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 1rem;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}
.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
}

.stat-icon {
  width: 3.5rem;
  height: 3.5rem;
  border-radius: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
}
.stat-icon span {
  font-size: 1.75rem;
}

.stat-icon-purple {
  background: rgba(147, 51, 234, 0.1);
  color: #7e22ce;
  border: 1px solid rgba(147, 51, 234, 0.2);
}
.stat-icon-blue {
  background: rgba(59, 130, 246, 0.1);
  color: #2563eb;
  border: 1px solid rgba(59, 130, 246, 0.2);
}

.stat-label {
  color: #64748b;
  font-size: 0.8125rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-weight: 700;
  margin-bottom: 0.25rem;
}

.stat-value {
  color: #0f172a;
  font-size: 1.875rem;
  font-weight: 800;
  line-height: 1;
}

/* Layout */
.layout-grid {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

@media (min-width: 1024px) {
  .layout-grid {
    flex-direction: row;
  }
  .flex-06 { flex: 0.65; }
  .flex-04 { flex: 0.35; }
}

/* Glass Card */
.glass-card {
  background: #ffffff;
  border-radius: 1.25rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  transition: all 0.3s ease;
}

.card-header {
  border-bottom: 1px solid #e2e8f0;
  padding: 1.5rem;
}

.card-title {
  color: #0f172a;
  font-weight: 800;
  font-size: 1.25rem;
}

.card-subtitle {
  color: #64748b;
}

/* Filters & Search */
.filter-select {
  background: #f8fafc url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%237e22ce' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E") no-repeat right 1rem center/1.25rem 1.25rem;
  appearance: none;
  border: 1px solid #cbd5e1;
  border-radius: 0.75rem;
  padding: 0.6rem 2.5rem 0.6rem 1rem;
  color: #0f172a;
  font-size: 0.875rem;
  font-weight: 600;
  outline: none;
  transition: all 0.2s;
  cursor: pointer;
}
.filter-select:focus {
  border-color: #9333ea;
  background-color: #ffffff;
  box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.15);
}
.filter-select option {
  background: #ffffff;
  color: #0f172a;
}

.search-input {
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  color: #0f172a;
  padding: 0.6rem 1rem 0.6rem 2.5rem;
  border-radius: 0.75rem;
  font-size: 0.875rem;
  width: 100%;
  outline: none;
  transition: all 0.2s;
}
.search-input:focus {
  border-color: #9333ea;
  background-color: #ffffff;
  box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.15);
}

/* Sub-tabs */
.log-tabs-bar {
  border-bottom: 1px solid #e2e8f0;
  background: #f8fafc;
}

.log-tab-btn {
  flex: 1;
  padding: 1rem;
  text-align: center;
  font-weight: 700;
  font-size: 0.875rem;
  transition: all 0.2s;
  border-bottom: 2px solid transparent;
  color: #64748b;
  cursor: pointer;
}
.log-tab-btn:hover {
  background: rgba(0, 0, 0, 0.03);
  color: #0f172a;
}
.log-tab-btn.active-main {
  color: #7e22ce;
  border-bottom-color: #7e22ce;
  background: #ffffff;
}
.log-tab-btn.active-operational {
  color: #2563eb;
  border-bottom-color: #2563eb;
  background: #ffffff;
}

/* Log Item */
.log-item {
  display: flex;
  gap: 1rem;
  padding: 1.25rem;
  background: #f8fafc;
  border-radius: 1rem;
  border: 1px solid #e2e8f0;
  transition: all 0.2s;
}
.log-item:hover {
  background: #ffffff;
  border-color: #cbd5e1;
  transform: translateX(4px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}

.log-icon {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 0.75rem;
  border: 1px solid;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.log-description {
  font-size: 0.95rem;
  line-height: 1.4;
}
.log-email {
  font-weight: 700;
  color: #0f172a;
}
.log-action-text {
  color: #334155;
}

.log-time {
  font-size: 0.75rem;
  color: #64748b;
}

.role-badge {
  font-size: 0.75rem;
  font-weight: 600;
  padding: 0.125rem 0.625rem;
  border-radius: 9999px;
  background: #f3e8ff;
  color: #7e22ce;
  border: 1px solid #e9d5ff;
}

/* Action Icons Color Schemes */
.action-green {
  background: rgba(34, 197, 94, 0.12);
  color: #16a34a;
  border-color: rgba(34, 197, 94, 0.25);
}
.action-blue {
  background: rgba(59, 130, 246, 0.12);
  color: #2563eb;
  border-color: rgba(59, 130, 246, 0.25);
}
.action-emerald {
  background: rgba(16, 185, 129, 0.12);
  color: #059669;
  border-color: rgba(16, 185, 129, 0.25);
}
.action-red {
  background: rgba(239, 68, 68, 0.12);
  color: #dc2626;
  border-color: rgba(239, 68, 68, 0.25);
}
.action-orange {
  background: rgba(249, 115, 22, 0.12);
  color: #ea580c;
  border-color: rgba(249, 115, 22, 0.25);
}
.action-indigo {
  background: rgba(99, 102, 241, 0.12);
  color: #4f46e5;
  border-color: rgba(99, 102, 241, 0.25);
}
.action-teal {
  background: rgba(20, 184, 166, 0.12);
  color: #0d9488;
  border-color: rgba(20, 184, 166, 0.25);
}
.action-slate {
  background: rgba(100, 116, 139, 0.12);
  color: #475569;
  border-color: rgba(100, 116, 139, 0.25);
}

/* Timeline */
.timeline-container {
  border-left: 2px solid #e2e8f0;
  margin-left: 0.75rem;
}
.timeline-dot {
  background: #ffffff;
  border: 2px solid #ec4899;
}
.timeline-time {
  color: #64748b;
  font-size: 0.75rem;
}
.timeline-user {
  color: #0f172a;
  font-weight: 600;
  font-size: 0.875rem;
}
.timeline-action {
  color: #475569;
  font-size: 0.75rem;
}

.empty-state-text {
  color: #64748b;
}

/* Custom Scrollbars */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 8px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 8px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>

<style>
/* ==========================================================================
   Dark Mode Overrides for Activity Logs
   Outer background remains white; ONLY the cards darken!
   ========================================================================== */
html.dark .page-title,
.dark .page-title {
  color: #0f172a !important;
}

html.dark .page-subtitle,
.dark .page-subtitle {
  color: #64748b !important;
}

html.dark .stat-card,
.dark .stat-card {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border-color: rgba(185, 121, 204, 0.25) !important;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25) !important;
}

html.dark .stat-label,
.dark .stat-label {
  color: #cbd5e1 !important;
}

html.dark .stat-value,
.dark .stat-value {
  color: #ffffff !important;
}

html.dark .stat-icon-purple,
.dark .stat-icon-purple {
  background: rgba(168, 85, 247, 0.2) !important;
  color: #c084fc !important;
  border-color: rgba(168, 85, 247, 0.3) !important;
}

html.dark .stat-icon-blue,
.dark .stat-icon-blue {
  background: rgba(59, 130, 246, 0.2) !important;
  color: #60a5fa !important;
  border-color: rgba(59, 130, 246, 0.3) !important;
}

html.dark .glass-card,
.dark .glass-card {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border-color: rgba(185, 121, 204, 0.25) !important;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25) !important;
}

html.dark .card-header,
.dark .card-header {
  border-bottom: 1px solid rgba(185, 121, 204, 0.2) !important;
  background: rgba(0, 0, 0, 0.2) !important;
}

html.dark .card-title,
.dark .card-title {
  color: #ffffff !important;
}

html.dark .card-subtitle,
.dark .card-subtitle {
  color: #94a3b8 !important;
}

html.dark .filter-select,
.dark .filter-select {
  background: rgba(0, 0, 0, 0.4) url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%23b979cc' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E") no-repeat right 1.25rem center/1.25rem 1.25rem !important;
  border: 1px solid rgba(185, 121, 204, 0.5) !important;
  color: white !important;
}
html.dark .filter-select:focus,
.dark .filter-select:focus {
  border-color: rgba(185, 121, 204, 0.8) !important;
  background-color: rgba(0, 0, 0, 0.6) !important;
}
html.dark .filter-select option,
.dark .filter-select option {
  background: #1e293b !important;
  color: white !important;
}

html.dark .search-input,
.dark .search-input {
  background: rgba(0, 0, 0, 0.3) !important;
  border-color: rgba(185, 121, 204, 0.4) !important;
  color: white !important;
}
html.dark .search-input:focus,
.dark .search-input:focus {
  border-color: rgba(185, 121, 204, 0.8) !important;
  background-color: rgba(0, 0, 0, 0.5) !important;
}

html.dark .log-tabs-bar,
.dark .log-tabs-bar {
  border-bottom: 1px solid rgba(185, 121, 204, 0.2) !important;
  background: rgba(0, 0, 0, 0.25) !important;
}

html.dark .log-tab-btn,
.dark .log-tab-btn {
  color: #94a3b8 !important;
}
html.dark .log-tab-btn:hover,
.dark .log-tab-btn:hover {
  background: rgba(255, 255, 255, 0.05) !important;
  color: #ffffff !important;
}
html.dark .log-tab-btn.active-main,
.dark .log-tab-btn.active-main {
  color: #ffffff !important;
  border-bottom-color: #c084fc !important;
  background: rgba(168, 85, 247, 0.25) !important;
}
html.dark .log-tab-btn.active-operational,
.dark .log-tab-btn.active-operational {
  color: #ffffff !important;
  border-bottom-color: #60a5fa !important;
  background: rgba(59, 130, 246, 0.25) !important;
}

html.dark .log-item,
.dark .log-item {
  background: rgba(0, 0, 0, 0.25) !important;
  border-color: rgba(185, 121, 204, 0.15) !important;
}
html.dark .log-item:hover,
.dark .log-item:hover {
  background: rgba(0, 0, 0, 0.4) !important;
  border-color: rgba(185, 121, 204, 0.4) !important;
}

html.dark .log-email,
.dark .log-email {
  color: #ffffff !important;
}
html.dark .log-action-text,
.dark .log-action-text {
  color: #cbd5e1 !important;
}
html.dark .log-time,
.dark .log-time {
  color: #94a3b8 !important;
}

html.dark .role-badge,
.dark .role-badge {
  background: rgba(168, 85, 247, 0.2) !important;
  color: #d8b4fe !important;
  border-color: rgba(168, 85, 247, 0.3) !important;
}

html.dark .action-green, .dark .action-green {
  background: rgba(34, 197, 94, 0.2) !important;
  color: #4ade80 !important;
  border-color: rgba(34, 197, 94, 0.4) !important;
}
html.dark .action-blue, .dark .action-blue {
  background: rgba(59, 130, 246, 0.2) !important;
  color: #60a5fa !important;
  border-color: rgba(59, 130, 246, 0.4) !important;
}
html.dark .action-emerald, .dark .action-emerald {
  background: rgba(16, 185, 129, 0.2) !important;
  color: #34d399 !important;
  border-color: rgba(16, 185, 129, 0.4) !important;
}
html.dark .action-red, .dark .action-red {
  background: rgba(239, 68, 68, 0.2) !important;
  color: #f87171 !important;
  border-color: rgba(239, 68, 68, 0.4) !important;
}
html.dark .action-orange, .dark .action-orange {
  background: rgba(249, 115, 22, 0.2) !important;
  color: #fb923c !important;
  border-color: rgba(249, 115, 22, 0.4) !important;
}
html.dark .action-indigo, .dark .action-indigo {
  background: rgba(99, 102, 241, 0.2) !important;
  color: #818cf8 !important;
  border-color: rgba(99, 102, 241, 0.4) !important;
}
html.dark .action-teal, .dark .action-teal {
  background: rgba(20, 184, 166, 0.2) !important;
  color: #2dd4bf !important;
  border-color: rgba(20, 184, 166, 0.4) !important;
}
html.dark .action-slate, .dark .action-slate {
  background: rgba(100, 116, 139, 0.2) !important;
  color: #94a3b8 !important;
  border-color: rgba(100, 116, 139, 0.4) !important;
}

html.dark .timeline-container,
.dark .timeline-container {
  border-left-color: rgba(185, 121, 204, 0.3) !important;
}
html.dark .timeline-dot,
.dark .timeline-dot {
  background: #1a1a2e !important;
  border-color: #f472b6 !important;
}
html.dark .timeline-time,
.dark .timeline-time {
  color: #94a3b8 !important;
}
html.dark .timeline-user,
.dark .timeline-user {
  color: #ffffff !important;
}
html.dark .timeline-action,
.dark .timeline-action {
  color: #cbd5e1 !important;
}

html.dark .empty-state-text,
.dark .empty-state-text {
  color: #94a3b8 !important;
}

html.dark .custom-scrollbar::-webkit-scrollbar-track,
.dark .custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.2) !important;
}
html.dark .custom-scrollbar::-webkit-scrollbar-thumb,
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(185, 121, 204, 0.3) !important;
}
html.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover,
.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(185, 121, 204, 0.5) !important;
}
</style>
