<template>
  <div class="app-wrapper app" style="background: #ffffff; width: 100%; overflow-x: auto;">
    <div class="banner-wrapper">
      <header class="topbar">
        <div class="topbar-brand">
          <span class="topbar-eyebrow">GAD Budget Distribution</span>
          <h1 class="topbar-title">Budget Distribution by Mandate</h1>
        </div>
        <div class="topbar-actions">
          <router-link to="/staff/budget">
            <button class="topbar-btn outline">📊 Budget Monitoring</button>
          </router-link>
          <router-link to="/staff/plan-and-budget">
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
        <p class="empty-desc">The statistics are generated from your saved GAD Plan.<br>Please go to <router-link to="/staff/plan-and-budget" class="plan-link">Plan & Budget</router-link> and click <b>"Save Plan"</b> first to generate statistics.</p>
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
                   </div>
                 </div>
               </div>
             </div>
             
             <button @click="openAllocationModal(stat)" class="manage-alloc-btn">
               Manage Allocations
             </button>
           </div>
         </div>
      </div>
    </div>

    <!-- Allocation Modal -->
    <div v-if="showAllocationModal" class="modal-backdrop" @click.self="closeAllocationModal">
      <div class="allocation-modal-card">
        <h2 class="modal-title">Budget Allocations</h2>
        <p class="modal-subtitle">
          Assign specific Accomplishment Report budgets to this mandate.
        </p>

        <div v-if="loadingAllocations" class="modal-loading-box">Loading...</div>
        <div v-else>
          <div v-if="currentAllocationStat && currentAllocationStat.budget_lines && currentAllocationStat.budget_lines.length > 0" class="modal-section-mb">
             <h3 class="modal-subheading">Planned Budget Lines</h3>
             <table class="modal-table">
               <thead>
                 <tr>
                   <th>Budget Line</th>
                   <th>Original Amount</th>
                   <th>Utilized (AR)</th>
                 </tr>
               </thead>
               <tbody>
                 <tr v-for="bl in currentAllocationStat.budget_lines" :key="bl.id">
                   <td class="modal-td-bold">{{ bl.label || 'Unnamed Line' }}</td>
                   <td>₱{{ Number(bl.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</td>
                   <td class="text-green">₱{{ Number(bl.utilized_budget || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</td>
                 </tr>
               </tbody>
             </table>
          </div>

          <div v-if="arVerifiedTotals && arVerifiedTotals.length > 0" class="modal-section-mb">
            <div class="modal-subheading-border">
               Actual Expenditures Breakdown (Verified ARs)
            </div>
            <table class="modal-table">
               <thead>
                 <tr>
                   <th>Expenditure Item</th>
                   <th>Total Cost</th>
                 </tr>
               </thead>
               <tbody>
                 <tr v-for="(tv, idx) in arVerifiedTotals" :key="idx">
                    <td class="modal-td-bold">{{ tv.name }}</td>
                    <td class="text-green font-mono font-bold">₱{{ Number(tv.amount).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</td>
                 </tr>
               </tbody>
            </table>
          </div>

          <div v-if="allocationsData.length === 0" class="modal-empty-box">
            No approved Accomplishment Reports found for this mandate.
          </div>
          <div v-else>
            <div v-for="doc in allocationsData" :key="doc.type + doc.id" class="doc-accordion-box">
              <div class="doc-header" @click="doc._expanded = !doc._expanded">
                <div class="doc-title-row">
                   <span :class="doc.type === 'AR' ? 'badge-ar' : 'badge-ad'">[{{ doc.type }}]</span>
                   <span class="doc-name">{{ doc.title || doc.control_number }}</span>
                   <button v-if="doc.attachment" @click.stop="openDocumentPreview(doc.attachment, doc.type)" class="preview-doc-btn" title="Preview Document">
                     Click here to preview document
                   </button>
                </div>
                <span class="accordion-arrow">{{ doc._expanded ? '▼' : '▶' }}</span>
              </div>
              
              <div v-if="doc._expanded" class="doc-items-container">
                <table class="items-table">
                  <thead>
                    <tr>
                      <th>Item Name</th>
                      <th>Total Cost</th>
                      <th>Allocated To (Budget Line)</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="item in doc.items" :key="item.id">
                      <td>{{ item.item_name }} <span v-if="item.sub_item" class="sub-item-text">- {{ item.sub_item }}</span></td>
                      <td class="font-mono">₱{{ Number(item.amount).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</td>
                      <td>
                        <select v-if="item.amount > 0" v-model="item.gpb_budget_line_id" class="item-alloc-select" @change="markAllocationsDirty">
                           <option :value="null">-- Not Allocated --</option>
                           <option v-for="bl in (currentAllocationStat?.budget_lines || [])" :key="bl.id" :value="bl.id">
                              {{ bl.label }} (₱{{ Number(bl.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }})
                           </option>
                        </select>
                        <span v-else class="text-muted text-xs">N/A</span>
                      </td>
                      <td class="text-xs">
                         <span v-if="item.amount <= 0" class="text-muted" title="This item has no cost to allocate.">No Cost</span>
                         <span v-else-if="item.gpb_budget_line_id" class="status-assigned">Assigned</span>
                         <span v-else-if="getAllocatedElsewhere(item) >= item.amount" class="status-locked" title="This budget item has been fully assigned to other mandates. It cannot be assigned here unless it is removed from the other mandate first.">🔒 Locked</span>
                         <span v-else class="text-muted">Unassigned</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
           <button @click="closeAllocationModal" class="btn-cancel">Cancel</button>
           <button @click="saveAllocations" :disabled="savingAllocations || !allocationsDirty" class="btn-save" :style="{ opacity: allocationsDirty ? 1 : 0.5 }">
             {{ savingAllocations ? 'Saving...' : 'Save Allocations' }}
           </button>
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

    const showAllocationModal = ref(false);
    const loadingAllocations = ref(false);
    const savingAllocations = ref(false);
    const allocationsData = ref([]);
    const currentAllocationStat = ref(null);
    const allocationsDirty = ref(false);

    const arVerifiedTotals = computed(() => {
      const totals = {};
      for (const doc of allocationsData.value) {
        if (doc.type === 'AR') {
          for (const item of doc.items) {
            const name = item.item_name || 'Unspecified Item';
            if (!totals[name]) {
              totals[name] = 0;
            }
            totals[name] += parseFloat(item.amount) || 0;
          }
        }
      }
      return Object.entries(totals).map(([name, amount]) => ({ name, amount }));
    });

    const openAllocationModal = async (stat) => {
      currentAllocationStat.value = stat;
      showAllocationModal.value = true;
      loadingAllocations.value = true;
      allocationsDirty.value = false;
      allocationsData.value = [];
      
      try {
        const res = await api.get(`/plan/mandate-allocations?gpb_ids=${stat.gpb_ids.join(',')}`);
        if (res.data.success) {
           allocationsData.value = res.data.data.filter(d => d.type === 'AR').map(d => ({ ...d, _expanded: true }));
        } else {
           Swal.fire('Error', res.data.message || 'Failed to load allocations.', 'error');
        }
      } catch (err) {
        Swal.fire('Error', 'Network error while loading allocations.', 'error');
      } finally {
        loadingAllocations.value = false;
      }
    };

    const closeAllocationModal = () => {
       showAllocationModal.value = false;
       currentAllocationStat.value = null;
    };

    const markAllocationsDirty = () => { allocationsDirty.value = true; };

    const getAllocatedElsewhere = (item) => {
       if (!item.allocations || !currentAllocationStat.value) return 0;
       return item.allocations.reduce((sum, al) => {
          if (!currentAllocationStat.value.gpb_ids.includes(parseInt(al.mandate_id))) {
              return sum + parseFloat(al.allocated_amount);
          }
          return sum;
       }, 0);
    };

    const saveAllocations = async () => {
       savingAllocations.value = true;
       
       const flatAllocs = [];
       for (const doc of allocationsData.value) {
           for (const item of doc.items) {
               let val = 0;
               let gpbLineId = item.gpb_budget_line_id;
               
               if (gpbLineId) {
                   val = parseFloat(item.amount) || 0;
               }

               flatAllocs.push({
                   budget_item_id: item.id,
                   item_type: doc.type,
                   allocated_amount: val,
                   gpb_budget_line_id: gpbLineId
               });
           }
       }

       try {
          const res = await api.post('/plan/mandate-allocations', {
             gpb_ids: currentAllocationStat.value.gpb_ids,
             allocations: flatAllocs
          });
          if (res.data.success) {
             allocationsDirty.value = false;
             closeAllocationModal();
             await fetchMandateStats();
             Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Allocations saved successfully', showConfirmButton: false, timer: 3000 });
          } else {
             Swal.fire('Error', res.data.message || 'Failed to save allocations.', 'error');
          }
       } catch (err) {
          Swal.fire('Error', 'Network error while saving allocations.', 'error');
       } finally {
          savingAllocations.value = false;
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
      showAllocationModal, loadingAllocations, savingAllocations, allocationsData, currentAllocationStat,
      allocationsDirty, openAllocationModal, closeAllocationModal, markAllocationsDirty,
      getAllocatedElsewhere, saveAllocations, arVerifiedTotals,
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

.manage-alloc-btn {
  width: 100%;
  padding: 10px;
  background: rgba(59, 130, 246, 0.1);
  border: 1px solid rgba(59, 130, 246, 0.3);
  color: #2563eb;
  font-weight: 700;
  font-size: 0.8125rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
}
.manage-alloc-btn:hover {
  background: rgba(59, 130, 246, 0.2);
  color: #1d4ed8;
}

/* Modal */
.modal-backdrop {
  z-index: 1000;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
}
.allocation-modal-card {
  width: 100%;
  max-width: 840px;
  max-height: 90vh;
  overflow-y: auto;
  background: #ffffff;
  padding: 28px;
  border-radius: 16px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
.modal-title {
  margin-bottom: 8px;
  color: #0f172a;
  font-weight: 800;
  font-size: 1.35rem;
}
.modal-subtitle {
  color: #64748b;
  margin-bottom: 24px;
  font-size: 0.9rem;
}
.modal-loading-box {
  padding: 20px;
  text-align: center;
  color: #64748b;
}
.modal-section-mb {
  margin-bottom: 24px;
}
.modal-subheading {
  color: #0f172a;
  font-size: 1rem;
  font-weight: 700;
  margin-bottom: 12px;
}
.modal-subheading-border {
  font-weight: 700;
  font-size: 0.95rem;
  margin-bottom: 12px;
  color: #0f172a;
  border-bottom: 1px solid #e2e8f0;
  padding-bottom: 8px;
}
.modal-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.9rem;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  overflow: hidden;
}
.modal-table thead tr {
  background: #f8fafc;
  text-align: left;
  color: #475569;
}
.modal-table th {
  padding: 10px;
  font-weight: 700;
}
.modal-table tbody tr {
  border-top: 1px solid #e2e8f0;
}
.modal-table td {
  padding: 10px;
  color: #334155;
}
.modal-td-bold {
  color: #0f172a !important;
  font-weight: 600;
}
.modal-empty-box {
  padding: 20px;
  text-align: center;
  color: #64748b;
}

.doc-accordion-box {
  margin-bottom: 16px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  overflow: hidden;
}
.doc-header {
  background: #f8fafc;
  padding: 12px 16px;
  font-weight: 600;
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
}
.doc-title-row {
  color: #0f172a;
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}
.badge-ar { color: #10b981; font-weight: 700; }
.badge-ad { color: #d97706; font-weight: 700; }
.doc-name { font-weight: 600; }
.preview-doc-btn {
  background: transparent;
  border: none;
  color: #2563eb;
  cursor: pointer;
  text-decoration: underline;
  font-size: 0.85rem;
  padding: 0 4px;
}
.accordion-arrow {
  color: #64748b;
  font-size: 0.75rem;
}
.doc-items-container {
  padding: 16px;
  background: #ffffff;
}
.items-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.9rem;
}
.items-table thead tr {
  border-bottom: 1px solid #e2e8f0;
  text-align: left;
  color: #64748b;
}
.items-table th {
  padding: 8px;
  font-weight: 600;
}
.items-table tbody tr {
  border-bottom: 1px solid #f1f5f9;
}
.items-table td {
  padding: 12px 8px;
  color: #0f172a;
}
.sub-item-text {
  color: #64748b;
  font-size: 0.8rem;
}
.item-alloc-select {
  width: 200px;
  padding: 6px;
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  color: #0f172a;
  border-radius: 6px;
  outline: none;
  font-size: 0.85rem;
}
.status-assigned { color: #10b981; font-weight: 600; }
.status-locked { color: #ef4444; font-weight: 600; }

.modal-footer {
  margin-top: 24px;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  border-top: 1px solid #e2e8f0;
  padding-top: 16px;
}
.btn-cancel {
  padding: 8px 16px;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  color: #475569;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
}
.btn-cancel:hover {
  background: #f8fafc;
  color: #0f172a;
}
.btn-save {
  padding: 8px 18px;
  background: #2563eb;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s;
}
.btn-save:hover:not(:disabled) {
  background: #1d4ed8;
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

html.dark .manage-alloc-btn,
.dark .manage-alloc-btn {
  background: rgba(59, 130, 246, 0.15) !important;
  border-color: rgba(59, 130, 246, 0.4) !important;
  color: #93c5fd !important;
}

/* Modal dark overrides */
html.dark .allocation-modal-card,
.dark .allocation-modal-card {
  background: #1e293b !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6) !important;
}
html.dark .modal-title,
.dark .modal-title {
  color: #ffffff !important;
}
html.dark .modal-subtitle,
.dark .modal-subtitle {
  color: #94a3b8 !important;
}
html.dark .modal-subheading,
.dark .modal-subheading,
html.dark .modal-subheading-border,
.dark .modal-subheading-border {
  color: #ffffff !important;
  border-bottom-color: rgba(185, 121, 204, 0.2) !important;
}
html.dark .modal-table,
.dark .modal-table {
  border-color: rgba(185, 121, 204, 0.2) !important;
}
html.dark .modal-table thead tr,
.dark .modal-table thead tr {
  background: rgba(0, 0, 0, 0.25) !important;
  color: #cbd5e1 !important;
}
html.dark .modal-table tbody tr,
.dark .modal-table tbody tr {
  border-top-color: rgba(255, 255, 255, 0.05) !important;
}
html.dark .modal-table td,
.dark .modal-table td {
  color: #cbd5e1 !important;
}
html.dark .modal-td-bold,
.dark .modal-td-bold {
  color: #ffffff !important;
}

html.dark .doc-accordion-box,
.dark .doc-accordion-box {
  border-color: rgba(185, 121, 204, 0.2) !important;
}
html.dark .doc-header,
.dark .doc-header {
  background: rgba(0, 0, 0, 0.25) !important;
}
html.dark .doc-title-row,
.dark .doc-title-row {
  color: #ffffff !important;
}
html.dark .doc-items-container,
.dark .doc-items-container {
  background: rgba(255, 255, 255, 0.02) !important;
}
html.dark .items-table thead tr,
.dark .items-table thead tr {
  border-bottom-color: rgba(185, 121, 204, 0.2) !important;
  color: #94a3b8 !important;
}
html.dark .items-table tbody tr,
.dark .items-table tbody tr {
  border-bottom-color: rgba(255, 255, 255, 0.05) !important;
}
html.dark .items-table td,
.dark .items-table td {
  color: #ffffff !important;
}
html.dark .sub-item-text,
.dark .sub-item-text {
  color: #94a3b8 !important;
}
html.dark .item-alloc-select,
.dark .item-alloc-select {
  background: #1e293b !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
  color: #ffffff !important;
}
html.dark .modal-footer,
.dark .modal-footer {
  border-top-color: rgba(185, 121, 204, 0.2) !important;
}
html.dark .btn-cancel,
.dark .btn-cancel {
  background: transparent !important;
  border-color: rgba(185, 121, 204, 0.3) !important;
  color: #cbd5e1 !important;
}
html.dark .btn-cancel:hover,
.dark .btn-cancel:hover {
  background: rgba(255, 255, 255, 0.05) !important;
  color: #ffffff !important;
}
</style>
