<template>
  <div class="app-wrapper app" style="background: #ffffff; width: 100%; overflow-x: auto;">
    <div class="banner-wrapper">
      <header class="topbar">
        <div class="topbar-brand">
          <span class="topbar-eyebrow">GAD Budget Distribution</span>
          <h1 class="topbar-title">Budget Distribution by Mandate</h1>
        </div>
        <div class="topbar-actions">
          <router-link to="/college/plan-and-budget">
            <button class="topbar-btn outline">← Back to Plan & Budget</button>
          </router-link>
        </div>
      </header>
    </div>

    <div id="mandate-statistics-section" class="card main-card">
      <div class="card-controls">
        <div class="card-title-row">
          <h2 class="card-title">
            GAD Budget Distribution by Mandate
          </h2>
          <div class="filter-group">
             <label class="filter-label">Filter by Classification:</label>
             <select v-model="mandateStatsFilter" class="filter-select">
               <option value="all">All Classifications</option>
               <option value="client">Client-Focused</option>
               <option value="org">Organization-Focused</option>
               <option value="attributed">Attributed Program</option>
             </select>
          </div>
        </div>

        <div class="stats-search-row">
          <div class="stat-pill">
            <div class="stat-icon-wrap">
              <span class="material-symbols-outlined">pie_chart</span>
            </div>
            <div class="stat-meta">
              <div class="stat-title">Total Mandates</div>
              <div class="stat-count">{{ loadingStats ? '—' : mandateStats.length }}</div>
            </div>
          </div>

          <div class="search-wrap" :class="{ 'search-active': searchQuery }">
            <span class="material-symbols-outlined search-icon">search</span>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search mandate, cause, activity..."
              class="search-input"
            />
            <button v-if="searchQuery" @click="searchQuery = ''" class="search-clear-btn">Clear</button>
          </div>
        </div>
      </div>

      <div v-if="loadingStats" class="state-message-box">
        Loading statistics...
      </div>
      <div v-else-if="mandateStats.length === 0" class="empty-state-card">
        <span class="empty-icon">📭</span>
        <h3 class="empty-title">No Mandate Data Available</h3>
        <p class="empty-desc">The statistics are generated from your saved GAD Plan.<br>Please go to <router-link to="/college/plan-and-budget" class="plan-link">Plan & Budget</router-link> and click <b>"Save Plan"</b> first to generate statistics.</p>
      </div>
      <div v-else>
         <div v-if="filteredMandateStats.length === 0" class="state-message-box">
           <span style="font-size: 1.5rem; display: block; margin-bottom: 8px;">🔍</span>
           No mandates match your search or filter criteria.
         </div>
         <div v-else class="mandate-grid">
           <div v-for="(stat, idx) in filteredMandateStats" :key="idx" class="mandate-card">
             
             <div class="mandate-info-col">
               <div class="mandate-block block-mandate">
                 <div class="block-label">Gender Issue / Mandate</div>
                 <div class="block-title">{{ stat.mandate || 'N/A' }}</div>
               </div>
               
               <div class="mandate-block block-cause">
                 <div class="block-label">Cause of Gender Issue</div>
                 <div class="block-desc">{{ stat.cause || 'N/A' }}</div>
               </div>
               
               <div class="mandate-block block-activity">
                 <div class="block-label">GAD Activity</div>
                 <div class="block-desc">{{ stat.activity || 'N/A' }}</div>
               </div>
             </div>
             
             <div class="mandate-budget-box">
               <div class="approved-counts-row">
                 <div class="approved-box">
                   <div class="approved-label">Approved ADs</div>
                   <div class="approved-val">{{ stat.approved_ad_count }}</div>
                 </div>
                 <div class="approved-box">
                   <div class="approved-label">Approved ARs</div>
                   <div class="approved-val">{{ stat.approved_ar_count }}</div>
                 </div>
               </div>
               
               <div class="budget-rows-wrap">
                 <div class="budget-row">
                   <span class="b-lbl">Budget:</span>
                   <span class="b-val">₱{{ Number(stat.budget).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                 </div>
                 <div class="budget-row">
                   <span class="b-lbl">Utilized:</span>
                   <span class="b-val text-green">₱{{ Number(stat.utilized_budget).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                 </div>
                 <div class="budget-row">
                   <span class="b-lbl">Pending (ADs):</span>
                   <span class="b-val text-yellow">₱{{ Number(stat.pending_budget).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                 </div>
                 <div class="budget-row remaining-row">
                    <span class="b-lbl">Remaining:</span>
                    <span :class="stat.remaining_budget < 0 ? 'text-red' : 'text-blue'" class="b-val font-mono">₱{{ Number(stat.remaining_budget).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                 </div>
               </div>

               <div v-if="stat.budget_lines && stat.budget_lines.length > 0" class="budget-breakdown-section">
                 <div class="breakdown-title">Budget Lines Breakdown</div>
                 <div class="breakdown-list">
                   <div v-for="bl in stat.budget_lines" :key="bl.id" class="breakdown-item">
                      <div class="bl-label">{{ bl.label || 'Unnamed Line' }}</div>
                      <div class="bl-row">
                         <span>Original:</span> <span class="font-mono">₱{{ Number(bl.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                      </div>
                      <div class="bl-row text-green">
                         <span>Utilized:</span> <span class="font-mono">₱{{ Number(bl.utilized_budget || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                      </div>
                      <div class="bl-row text-yellow">
                         <span>Pending (AD):</span> <span class="font-mono">₱{{ Number(bl.pending_budget || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                      </div>
                   </div>
                 </div>
               </div>
             </div>
           </div>
         </div>
      </div>
    </div>

    <PdfPreviewModal :isOpen="isPdfModalOpen" :fileUrl="pdfFileUrl" @close="closePdfModal" />
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import Swal from 'sweetalert2';
import api from '../../api';
import PdfPreviewModal from '../../components/PdfPreviewModal.vue';

export default {
  name: 'BudgetDistributionView',
  components: { PdfPreviewModal },
  setup() {
    const mandateStats = ref([]);
    const mandateStatsFilter = ref('all');
    const searchQuery = ref('');
    const filteredMandateStats = computed(() => {
      let list = mandateStats.value;
      if (mandateStatsFilter.value !== 'all') {
        list = list.filter(s => s.classification === mandateStatsFilter.value);
      }
      const q = searchQuery.value.trim().toLowerCase();
      if (q) {
        list = list.filter(s =>
          (s.mandate || '').toLowerCase().includes(q) ||
          (s.cause || '').toLowerCase().includes(q) ||
          (s.activity || '').toLowerCase().includes(q)
        );
      }
      return list;
    });
    const loadingStats = ref(true);
    
    const fetchMandateStats = async () => {
      loadingStats.value = true;
      try {
        const response = await api.get('/plan/mandate-statistics');
        if (response.data.success) {
          mandateStats.value = response.data.data;
        }
      } catch (err) {
        console.error('Failed to fetch mandate stats', err);
      } finally {
        loadingStats.value = false;
      }
    };

    const isPdfModalOpen = ref(false);
    const pdfFileUrl = ref('');

    const openDocumentPreview = (attachment, type) => {
      if (attachment) {
        let fileName = attachment;
        if (typeof attachment === 'string' && attachment.startsWith('[')) {
           try {
               const parsed = JSON.parse(attachment);
               if (parsed.length > 0) fileName = parsed[0];
           } catch(e) {}
        }
        pdfFileUrl.value = `${import.meta.env.VITE_API_BASE_URL.replace(/\/api\/?$/, '')}/api/files/archived/${fileName}`;
        isPdfModalOpen.value = true;
      }
    };
    const closePdfModal = () => {
      isPdfModalOpen.value = false;
      pdfFileUrl.value = '';
    };

    onMounted(async () => {
      fetchMandateStats();
    });

    return {
      mandateStats, mandateStatsFilter, searchQuery, filteredMandateStats, loadingStats,
      fetchMandateStats,
      isPdfModalOpen, pdfFileUrl, openDocumentPreview, closePdfModal
    };
  }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&display=swap');

*, *::before, *::after { box-sizing: border-box; }

.app-wrapper {
  margin: 0;
  padding: 0;
  background: #ffffff;
  color: #0f172a;
  font-size: 15.5px;
  line-height: 1.6;
  min-height: calc(100vh - 80px);
}
.font-mono { font-family: 'IBM Plex Mono', monospace; }
.font-bold { font-weight: 700; }
.text-xs { font-size: 0.75rem; }
.text-muted { color: #64748b; }

/* Top Banner */
.banner-wrapper {
  min-width: 1200px;
  margin: 32px 32px 0 32px;
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
  background: #ffffff;
  display: flex;
  flex-direction: column;
}

.topbar {
  position: sticky;
  top: 0;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 24px;
  background: #ffffff;
  border-bottom: 1px solid #e2e8f0;
}

.topbar-brand {
  display: flex;
  flex-direction: column;
  justify-content: center;
  min-width: 180px;
  margin-right: 6px;
  border-right: 1px solid #e2e8f0;
  padding-right: 18px;
}
.topbar-eyebrow {
  font-size: 0.75rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: #7e22ce;
  font-weight: 700;
  line-height: 1;
  margin-bottom: 4px;
}
.topbar-title {
  font-size: 1.125rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.2;
  white-space: nowrap;
}

.topbar-actions {
  display: flex;
  gap: 10px;
  align-items: center;
  flex-shrink: 0;
}
.topbar-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  border-radius: 8px;
  padding: 8px 16px;
  font-size: 0.8125rem;
  font-weight: 700;
  border: 1px solid #cbd5e1;
  background: #ffffff;
  color: #475569;
  transition: all 0.2s;
  cursor: pointer;
}
.topbar-btn:hover {
  background: #f8fafc;
  border-color: #94a3b8;
  color: #0f172a;
}

/* Main Card */
.main-card {
  min-width: 1200px;
  margin: 24px 32px 32px 32px;
  padding: 24px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
}

.card-title-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 16px;
  margin-bottom: 20px;
}
.card-title {
  color: #0f172a;
  font-size: 1.25rem;
  font-weight: 800;
  margin: 0;
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}
.filter-label {
  color: #64748b;
  font-size: 0.875rem;
  font-weight: 600;
}
.filter-select {
  background: #f8fafc url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%237e22ce' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E") no-repeat right 0.75rem center/1.25rem 1.25rem;
  appearance: none;
  border: 1px solid #cbd5e1;
  color: #0f172a;
  padding: 6px 32px 6px 12px;
  border-radius: 8px;
  font-size: 0.875rem;
  font-weight: 600;
  outline: none;
  cursor: pointer;
  transition: all 0.2s;
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

/* Stats and Search Row */
.stats-search-row {
  display: grid;
  grid-template-columns: 220px 1fr;
  gap: 16px;
  align-items: stretch;
  margin-bottom: 24px;
}
.stat-pill {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 14px 16px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.stat-icon-wrap {
  background: rgba(147, 51, 234, 0.1);
  width: 44px;
  height: 44px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #7e22ce;
  border: 1px solid rgba(147, 51, 234, 0.2);
}
.stat-meta {
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.stat-title {
  font-size: 0.6875rem;
  color: #64748b;
  text-transform: uppercase;
  font-weight: 700;
  letter-spacing: 0.5px;
}
.stat-count {
  font-size: 1.5rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.1;
}

.search-wrap {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 0 16px;
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  border-radius: 12px;
  transition: all 0.2s;
}
.search-wrap.search-active, .search-wrap:focus-within {
  border-color: #9333ea;
  background: #ffffff;
  box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.15);
}
.search-icon {
  color: #64748b;
  font-size: 20px;
  flex-shrink: 0;
}
.search-input {
  flex: 1;
  border: none;
  background: transparent;
  padding: 12px 0;
  color: #0f172a;
  font-size: 0.875rem;
  outline: none;
}
.search-clear-btn {
  background: #e2e8f0;
  border: 1px solid #cbd5e1;
  color: #475569;
  border-radius: 6px;
  padding: 4px 10px;
  font-size: 0.75rem;
  font-weight: 600;
  cursor: pointer;
}
.search-clear-btn:hover {
  background: #cbd5e1;
  color: #0f172a;
}

/* State Boxes */
.state-message-box {
  text-align: center;
  color: #64748b;
  padding: 40px;
}
.empty-state-card {
  text-align: center;
  color: #64748b;
  padding: 40px;
}
.empty-icon {
  font-size: 2.25rem;
  display: block;
  margin-bottom: 12px;
}
.empty-title {
  color: #0f172a;
  font-weight: 800;
  margin-bottom: 8px;
}
.empty-desc {
  font-size: 0.9rem;
}
.plan-link {
  color: #7e22ce;
  font-weight: 600;
  text-decoration: underline;
}

/* Mandates Grid */
.mandate-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 20px;
}
.mandate-card {
  background: #ffffff;
  border-radius: 12px;
  padding: 20px;
  border: 1px solid #e2e8f0;
  display: flex;
  flex-direction: column;
  gap: 16px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
  transition: all 0.25s ease;
}
.mandate-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
  border-color: #cbd5e1;
}

.mandate-info-col {
  display: flex;
  flex-direction: column;
  gap: 12px;
  flex: 1;
}
.mandate-block {
  padding: 12px;
  border-radius: 8px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
}
.block-mandate { border-left: 4px solid #6366f1; }
.block-cause { border-left: 4px solid #8b5cf6; }
.block-activity { border-left: 4px solid #ec4899; }

.block-label {
  font-size: 0.6875rem;
  text-transform: uppercase;
  font-weight: 700;
  margin-bottom: 4px;
  letter-spacing: 0.5px;
}
.block-mandate .block-label { color: #6366f1; }
.block-cause .block-label { color: #8b5cf6; }
.block-activity .block-label { color: #ec4899; }

.block-title {
  font-size: 0.9375rem;
  color: #0f172a;
  font-weight: 600;
  line-height: 1.4;
}
.block-desc {
  font-size: 0.85rem;
  color: #334155;
  line-height: 1.4;
}

.mandate-budget-box {
  background: #f8fafc;
  border-radius: 8px;
  padding: 16px;
  border: 1px solid #e2e8f0;
}
.approved-counts-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 12px;
  padding-bottom: 12px;
  border-bottom: 1px solid #e2e8f0;
}
.approved-box {
  text-align: center;
  padding: 8px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
}
.approved-label {
  font-size: 0.6875rem;
  color: #64748b;
  text-transform: uppercase;
  font-weight: 700;
}
.approved-val {
  font-size: 1.125rem;
  color: #0f172a;
  font-weight: 800;
}

.budget-rows-wrap {
  display: flex;
  flex-direction: column;
  gap: 8px;
  font-size: 0.85rem;
}
.budget-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.b-lbl {
  font-weight: 600;
  color: #475569;
}
.b-val {
  color: #0f172a;
  font-family: monospace;
  font-size: 0.95rem;
  font-weight: 700;
}
.text-green { color: #10b981 !important; }
.text-yellow { color: #d97706 !important; }
.text-red { color: #ef4444 !important; }
.text-blue { color: #2563eb !important; }

.remaining-row {
  font-weight: 700;
  padding-top: 8px;
  border-top: 1px dashed #cbd5e1;
  margin-top: 4px;
}
.remaining-row .b-lbl {
  text-transform: uppercase;
  font-size: 0.75rem;
  color: #0f172a;
}
.remaining-row .b-val {
  font-size: 1.05rem;
}

.budget-breakdown-section {
  margin-top: 16px;
  padding-top: 16px;
  border-top: 1px solid #e2e8f0;
}
.breakdown-title {
  font-size: 0.75rem;
  color: #64748b;
  text-transform: uppercase;
  font-weight: 700;
  margin-bottom: 12px;
  letter-spacing: 0.5px;
}
.breakdown-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.breakdown-item {
  background: #ffffff;
  border-radius: 6px;
  padding: 10px;
  border: 1px solid #e2e8f0;
  font-size: 0.8rem;
}
.bl-label {
  color: #0f172a;
  font-weight: 700;
  margin-bottom: 6px;
}
.bl-row {
  display: flex;
  justify-content: space-between;
  color: #475569;
  margin-bottom: 2px;
}
</style>

<style>
/* ==========================================================================
   Dark Mode Overrides for Budget Distribution by Mandate
   Outer background remains white; ONLY the cards darken!
   ========================================================================== */
html.dark .banner-wrapper,
.dark .banner-wrapper {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border-color: rgba(185, 121, 204, 0.25) !important;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25) !important;
}

html.dark .topbar,
.dark .topbar {
  background: transparent !important;
  border-bottom-color: rgba(185, 121, 204, 0.2) !important;
}

html.dark .topbar-brand,
.dark .topbar-brand {
  border-right-color: rgba(185, 121, 204, 0.2) !important;
}

html.dark .topbar-eyebrow,
.dark .topbar-eyebrow {
  color: #c084fc !important;
}

html.dark .topbar-title,
.dark .topbar-title {
  color: #ffffff !important;
}

html.dark .topbar-btn,
.dark .topbar-btn {
  background: rgba(0, 0, 0, 0.3) !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
  color: #cbd5e1 !important;
}
html.dark .topbar-btn:hover,
.dark .topbar-btn:hover {
  background: rgba(255, 255, 255, 0.08) !important;
  color: #ffffff !important;
}

html.dark .main-card,
.dark .main-card {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border-color: rgba(185, 121, 204, 0.25) !important;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25) !important;
}

html.dark .card-title,
.dark .card-title {
  color: #ffffff !important;
}

html.dark .filter-label,
.dark .filter-label {
  color: #cbd5e1 !important;
}

html.dark .filter-select,
.dark .filter-select {
  background: rgba(0, 0, 0, 0.4) url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%23b979cc' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E") no-repeat right 0.75rem center/1.25rem 1.25rem !important;
  border-color: rgba(185, 121, 204, 0.4) !important;
  color: #ffffff !important;
}
html.dark .filter-select option,
.dark .filter-select option {
  background: #1e293b !important;
  color: #ffffff !important;
}

html.dark .stat-pill,
.dark .stat-pill {
  background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(139, 92, 246, 0.1)) !important;
  border-color: rgba(99, 102, 241, 0.3) !important;
}
html.dark .stat-icon-wrap,
.dark .stat-icon-wrap {
  background: rgba(99, 102, 241, 0.2) !important;
  color: #a5b4fc !important;
  border-color: rgba(99, 102, 241, 0.3) !important;
}
html.dark .stat-title,
.dark .stat-title {
  color: #cbd5e1 !important;
}
html.dark .stat-count,
.dark .stat-count {
  color: #ffffff !important;
}

html.dark .search-wrap,
.dark .search-wrap {
  background: rgba(0, 0, 0, 0.3) !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
}
html.dark .search-wrap.search-active,
.dark .search-wrap.search-active,
html.dark .search-wrap:focus-within,
.dark .search-wrap:focus-within {
  border-color: rgba(185, 121, 204, 0.8) !important;
  background: rgba(0, 0, 0, 0.5) !important;
}
html.dark .search-icon,
.dark .search-icon {
  color: #cbd5e1 !important;
}
html.dark .search-input,
.dark .search-input {
  color: #ffffff !important;
}
html.dark .search-clear-btn,
.dark .search-clear-btn {
  background: rgba(255, 255, 255, 0.08) !important;
  border-color: rgba(255, 255, 255, 0.15) !important;
  color: #cbd5e1 !important;
}

html.dark .state-message-box,
.dark .state-message-box {
  color: #cbd5e1 !important;
}
html.dark .empty-state-card,
.dark .empty-state-card {
  color: #cbd5e1 !important;
}
html.dark .empty-title,
.dark .empty-title {
  color: #ffffff !important;
}
html.dark .plan-link,
.dark .plan-link {
  color: #c084fc !important;
}

html.dark .mandate-card,
.dark .mandate-card {
  background: rgba(0, 0, 0, 0.25) !important;
  border-color: rgba(185, 121, 204, 0.2) !important;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
}
html.dark .mandate-card:hover,
.dark .mandate-card:hover {
  border-color: rgba(185, 121, 204, 0.4) !important;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3) !important;
}

html.dark .mandate-block,
.dark .mandate-block {
  background: rgba(255, 255, 255, 0.03) !important;
  border-color: rgba(185, 121, 204, 0.15) !important;
}
html.dark .block-title,
.dark .block-title {
  color: #ffffff !important;
}
html.dark .block-desc,
.dark .block-desc {
  color: #cbd5e1 !important;
}

html.dark .mandate-budget-box,
.dark .mandate-budget-box {
  background: rgba(0, 0, 0, 0.2) !important;
  border-color: rgba(185, 121, 204, 0.15) !important;
}
html.dark .approved-counts-row,
.dark .approved-counts-row {
  border-bottom-color: rgba(185, 121, 204, 0.15) !important;
}
html.dark .approved-box,
.dark .approved-box {
  background: rgba(255, 255, 255, 0.03) !important;
  border-color: rgba(185, 121, 204, 0.1) !important;
}
html.dark .approved-label,
.dark .approved-label {
  color: #94a3b8 !important;
}
html.dark .approved-val,
.dark .approved-val {
  color: #ffffff !important;
}

html.dark .b-lbl,
.dark .b-lbl {
  color: #cbd5e1 !important;
}
html.dark .b-val,
.dark .b-val {
  color: #ffffff !important;
}
html.dark .remaining-row,
.dark .remaining-row {
  border-top-color: rgba(185, 121, 204, 0.2) !important;
}
html.dark .remaining-row .b-lbl,
.dark .remaining-row .b-lbl {
  color: #ffffff !important;
}

html.dark .budget-breakdown-section,
.dark .budget-breakdown-section {
  border-top-color: rgba(185, 121, 204, 0.2) !important;
}
html.dark .breakdown-title,
.dark .breakdown-title {
  color: #cbd5e1 !important;
}
html.dark .breakdown-item,
.dark .breakdown-item {
  background: rgba(0, 0, 0, 0.3) !important;
  border-color: rgba(185, 121, 204, 0.15) !important;
}
html.dark .bl-label,
.dark .bl-label {
  color: #ffffff !important;
}
html.dark .bl-row,
.dark .bl-row {
  color: #cbd5e1 !important;
}
</style>
