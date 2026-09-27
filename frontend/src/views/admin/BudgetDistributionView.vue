<template>
  <div class="app-wrapper app" style="background: #ffffff; width: 100%; overflow-x: auto;">
    <div style="min-width: 1200px; margin: 32px 32px 0 32px; border-radius: 16px; overflow: hidden; border: 1px solid var(--border); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); background: var(--surface); display: flex; flex-direction: column;">
      <header class="topbar">
        <div class="topbar-brand">
          <span class="topbar-eyebrow">GAD Budget Distribution</span>
          <h1 class="topbar-title">Budget Distribution by Mandate</h1>
        </div>
        <div class="topbar-actions">
          <router-link to="/admin/budget">
            <button class="topbar-btn outline">📊 Budget Monitoring</button>
          </router-link>
          <router-link to="/admin/plan-and-budget">
            <button class="topbar-btn outline">← Back to Plan & Budget</button>
          </router-link>
        </div>
      </header>
    </div>

    <div id="mandate-statistics-section" class="card" style="min-width: 1200px; margin: 24px 32px 32px 32px; padding: 24px; border-top: 1px solid var(--border); border-radius: 16px;">
      <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
          <h2 style="display: flex; align-items: center; gap: 8px; color: var(--text-primary); font-size: 1.25rem; margin: 0; font-weight: 600;">
            GAD Budget Distribution by Mandate
          </h2>
          <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
             <label style="color: var(--text-muted); font-size: 0.85rem;">Filter by Classification:</label>
             <select v-model="mandateStatsFilter" style="background: rgba(0,0,0,0.3); border: 1px solid var(--border); color: white; padding: 6px 12px; border-radius: 6px; outline: none; font-size: 0.9rem;">
               <option value="all" style="background: #1e293b; color: #fff;">All Classifications</option>
               <option value="client" style="background: #1e293b; color: #fff;">Client-Focused</option>
               <option value="org" style="background: #1e293b; color: #fff;">Organization-Focused</option>
               <option value="attributed" style="background: #1e293b; color: #fff;">Attributed Program</option>
             </select>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 16px; align-items: stretch;">
          <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(139, 92, 246, 0.1)); border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 12px; padding: 16px; display: flex; align-items: center; gap: 12px;">
            <div style="background: rgba(99, 102, 241, 0.2); width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
              <span class="material-symbols-outlined" style="font-size: 24px; color: #a5b4fc; font-variation-settings: 'FILL' 1;">pie_chart</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 2px; min-width: 0;">
              <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Total Mandates</div>
              <div style="font-size: 1.6rem; font-weight: 800; color: var(--text-primary); line-height: 1.1;">{{ loadingStats ? '—' : mandateStats.length }}</div>
            </div>
          </div>

          <div style="display: flex; align-items: center; gap: 10px; padding: 0 12px; background: rgba(0,0,0,0.3); border: 1px solid var(--border); border-radius: 10px; transition: border-color 0.15s, box-shadow 0.15s;" :style="searchQuery ? 'border-color: var(--primary); box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.18);' : ''">
            <span class="material-symbols-outlined" style="color: var(--text-muted); font-size: 20px; flex-shrink: 0;">search</span>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search mandate, cause, activity..."
              style="flex: 1; min-width: 0; padding: 12px 4px; background: transparent; border: none; color: white; border-radius: 0; outline: none; font-size: 0.9rem;"
            />
            <button v-if="searchQuery" @click="searchQuery = ''" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: var(--text-secondary); cursor: pointer; padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; white-space: nowrap; flex-shrink: 0;">Clear</button>
          </div>
        </div>
      </div>

      <div v-if="loadingStats" style="text-align: center; color: var(--text-muted); padding: 40px;">
        Loading statistics...
      </div>
      <div v-else-if="mandateStats.length === 0" style="text-align: center; color: var(--text-muted); padding: 40px;">
        <span style="font-size: 2rem; display: block; margin-bottom: 12px;">📭</span>
        <h3 style="color: var(--text); margin-bottom: 8px;">No Mandate Data Available</h3>
        <p style="font-size: 0.9rem;">The statistics are generated from your saved GAD Plan.<br>Please go to <router-link to="/admin/plan-and-budget" style="color: #93c5fd; text-decoration: underline;">Plan & Budget</router-link> and click <b>"Save Plan"</b> first to generate statistics.</p>
      </div>
      <div v-else>
         <div v-if="filteredMandateStats.length === 0" style="text-align: center; color: var(--text-muted); padding: 24px;">
           <span style="font-size: 1.5rem; display: block; margin-bottom: 8px;">🔍</span>
           No mandates match your search or filter criteria.
         </div>
         <div v-else style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
           <div v-for="(stat, idx) in filteredMandateStats" :key="idx" style="background: rgba(0,0,0,0.25); border-radius: 12px; padding: 20px; border: 1px solid rgba(255,255,255,0.1); display: flex; flex-direction: column; gap: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.2), 0 4px 6px -2px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)';">
             
             <div style="display: flex; flex-direction: column; gap: 12px; flex: 1;">
               <div style="background: rgba(255,255,255,0.03); padding: 12px; border-radius: 8px; border-left: 3px solid #6366f1;">
                 <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 4px; letter-spacing: 0.5px;">Gender Issue / Mandate</div>
                 <div style="font-size: 0.95rem; color: var(--text-primary); font-weight: 500; line-height: 1.4;">{{ stat.mandate || 'N/A' }}</div>
               </div>
               
               <div style="background: rgba(255,255,255,0.03); padding: 12px; border-radius: 8px; border-left: 3px solid #8b5cf6;">
                 <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 4px; letter-spacing: 0.5px;">Cause of Gender Issue</div>
                 <div style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4;">{{ stat.cause || 'N/A' }}</div>
               </div>
               
               <div style="background: rgba(255,255,255,0.03); padding: 12px; border-radius: 8px; border-left: 3px solid #ec4899;">
                 <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 4px; letter-spacing: 0.5px;">GAD Activity</div>
                 <div style="font-size: 0.85rem; color: var(--text-secondary); line-height: 1.4;">{{ stat.activity || 'N/A' }}</div>
               </div>
             </div>
             
             <div style="background: rgba(0,0,0,0.15); border-radius: 8px; padding: 16px; border: 1px solid rgba(255,255,255,0.03);">
               <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                 <div style="text-align: center; padding: 8px; background: rgba(255,255,255,0.02); border-radius: 6px;">
                   <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase;">Approved ADs</div>
                   <div style="font-size: 1.1rem; color: var(--text-primary); font-weight: 700;">{{ stat.approved_ad_count }}</div>
                 </div>
                 <div style="text-align: center; padding: 8px; background: rgba(255,255,255,0.02); border-radius: 6px;">
                   <div style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase;">Approved ARs</div>
                   <div style="font-size: 1.1rem; color: var(--text-primary); font-weight: 700;">{{ stat.approved_ar_count }}</div>
                 </div>
               </div>
               
               <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.85rem; color: var(--text-secondary);">
                 <div style="display: flex; justify-content: space-between; align-items: center;">
                   <span style="font-weight: 500;">Budget:</span>
                   <span style="color: var(--text-primary); font-family: monospace; font-size: 0.95rem;">₱{{ Number(stat.budget).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                 </div>
                 <div style="display: flex; justify-content: space-between; align-items: center;">
                   <span style="font-weight: 500;">Utilized:</span>
                   <span style="color: #10b981; font-family: monospace; font-size: 0.95rem;">₱{{ Number(stat.utilized_budget).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                 </div>
                 <div style="display: flex; justify-content: space-between; align-items: center;">
                   <span style="font-weight: 500;">Pending (ADs):</span>
                   <span style="color: #f59e0b; font-family: monospace; font-size: 0.95rem;">₱{{ Number(stat.pending_budget).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                 </div>
                 <div style="display: flex; justify-content: space-between; align-items: center; font-weight: 700; padding-top: 8px; border-top: 1px dashed rgba(255,255,255,0.1); margin-top: 4px;">
                    <span style="text-transform: uppercase; font-size: 0.75rem;">Remaining:</span>
                    <span :style="{ color: stat.remaining_budget < 0 ? '#ef4444' : '#3b82f6' }" style="font-family: monospace; font-size: 1.05rem;">₱{{ Number(stat.remaining_budget).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                  </div>
                </div>

                <div v-if="stat.budget_lines && stat.budget_lines.length > 0" style="margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.1);">
                  <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 12px; letter-spacing: 0.5px;">Budget Lines Breakdown</div>
                  <div style="display: flex; flex-direction: column; gap: 8px;">
                    <div v-for="bl in stat.budget_lines" :key="bl.id" style="background: rgba(0,0,0,0.2); border-radius: 6px; padding: 10px; border: 1px solid rgba(255,255,255,0.05); font-size: 0.8rem;">
                       <div style="color: var(--text-primary); font-weight: 600; margin-bottom: 6px;">{{ bl.label || 'Unnamed Line' }}</div>
                       <div style="display: flex; justify-content: space-between; color: var(--text-secondary); margin-bottom: 2px;">
                          <span>Original:</span> <span style="font-family: monospace;">₱{{ Number(bl.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                       </div>
                       <div style="display: flex; justify-content: space-between; color: #10b981; margin-bottom: 2px;">
                          <span>Utilized:</span> <span style="font-family: monospace;">₱{{ Number(bl.utilized_budget || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                       </div>
                       <div style="display: flex; justify-content: space-between; color: #f59e0b;">
                          <span>Pending (AD):</span> <span style="font-family: monospace;">₱{{ Number(bl.pending_budget || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</span>
                       </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <button @click="openAllocationModal(stat)" style="width: 100%; padding: 10px; background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.4); color: #93c5fd; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='rgba(59, 130, 246, 0.25)'; this.style.color='#bfdbfe';" onmouseout="this.style.background='rgba(59, 130, 246, 0.15)'; this.style.color='#93c5fd';">
                Manage Allocations
              </button>
            </div>
          </div>
       </div>
    </div>

    <div v-if="showAllocationModal" class="modal-backdrop" @click.self="closeAllocationModal" style="z-index: 1000; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center;">
      <div class="card" style="width: 100%; max-width: 800px; max-height: 90vh; overflow-y: auto; background: #1e293b; padding: 24px; border-radius: 12px; border: 1px solid var(--border);">
        <h2 style="margin-bottom: 8px; color: var(--text-primary);">Budget Allocations</h2>
        <p style="color: var(--text-secondary); margin-bottom: 24px; font-size: 0.9rem;">
          Assign specific Activity Design and Accomplishment Report budgets to this mandate.
        </p>

        <div v-if="loadingAllocations" style="padding: 20px; text-align: center; color: var(--text-muted);">Loading...</div>
        <div v-else>
          <div v-if="currentAllocationStat && currentAllocationStat.budget_lines && currentAllocationStat.budget_lines.length > 0" style="margin-bottom: 24px;">
             <h3 style="color: var(--text-primary); font-size: 1rem; margin-bottom: 12px;">Planned Budget Lines</h3>
             <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; border: 1px solid var(--border); border-radius: 8px; overflow: hidden;">
               <thead>
                 <tr style="background: rgba(0,0,0,0.2); text-align: left; color: var(--text-muted);">
                   <th style="padding: 10px; font-weight: 600;">Budget Line</th>
                   <th style="padding: 10px; font-weight: 600;">Original Amount</th>
                   <th style="padding: 10px; font-weight: 600;">Pending (AD)</th>
                   <th style="padding: 10px; font-weight: 600;">Utilized (AR)</th>
                 </tr>
               </thead>
               <tbody>
                 <tr v-for="bl in currentAllocationStat.budget_lines" :key="bl.id" style="border-top: 1px solid rgba(255,255,255,0.05);">
                   <td style="padding: 10px; color: var(--text-primary);">{{ bl.label || 'Unnamed Line' }}</td>
                   <td style="padding: 10px; color: var(--text-primary);">₱{{ Number(bl.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</td>
                   <td style="padding: 10px; color: #f59e0b;">₱{{ Number(bl.pending_budget || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</td>
                   <td style="padding: 10px; color: #10b981;">₱{{ Number(bl.utilized_budget || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</td>
                 </tr>
               </tbody>
             </table>
          </div>

          <div v-if="arVerifiedTotals && arVerifiedTotals.length > 0" style="margin-bottom: 24px; margin-top: 16px;">
            <div style="font-weight: 600; font-size: 0.95rem; margin-bottom: 12px; color: var(--text-primary); border-bottom: 1px solid var(--border); padding-bottom: 8px;">
               Actual Expenditures Breakdown (Verified ARs)
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; border: 1px solid var(--border); border-radius: 8px; overflow: hidden;">
               <thead>
                 <tr style="background: rgba(0,0,0,0.2); text-align: left; color: var(--text-muted);">
                   <th style="padding: 10px; font-weight: 600;">Expenditure Item</th>
                   <th style="padding: 10px; font-weight: 600;">Total Cost</th>
                 </tr>
               </thead>
               <tbody>
                 <tr v-for="(tv, idx) in arVerifiedTotals" :key="idx" style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 10px; color: var(--text-primary);">{{ tv.name }}</td>
                    <td style="padding: 10px; color: #10b981; font-family: monospace; font-weight: 600;">₱{{ Number(tv.amount).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</td>
                 </tr>
               </tbody>
            </table>
          </div>

          <div v-if="allocationsData.length === 0" style="padding: 20px; text-align: center; color: var(--text-muted);">
            No approved Activity Designs or Accomplishment Reports found for this mandate.
          </div>
          <div v-else>
          <div v-for="doc in allocationsData" :key="doc.type + doc.id" style="margin-bottom: 16px; border: 1px solid var(--border); border-radius: 8px; overflow: hidden;">
            <div style="background: rgba(0,0,0,0.2); padding: 12px 16px; font-weight: 600; display: flex; justify-content: space-between; align-items: center; cursor: pointer;" @click="doc._expanded = !doc._expanded">
              <div style="color: var(--text-primary); display: flex; align-items: center; gap: 8px;">
                 <span :style="{ color: doc.type === 'AR' ? '#10b981' : '#f59e0b' }">[{{ doc.type }}]</span>
                 {{ doc.title || doc.control_number }}
                 <button v-if="doc.attachment" @click.stop="openDocumentPreview(doc.attachment, doc.type)" style="background: transparent; border: none; color: #3b82f6; cursor: pointer; text-decoration: underline; font-size: 0.85rem; padding: 0 4px;" title="Preview Document">
                   Click here to preview document
                 </button>
              </div>
              <span style="color: var(--text-muted);">{{ doc._expanded ? '▼' : '▶' }}</span>
            </div>
            
            <div v-if="doc._expanded" style="padding: 16px; background: rgba(255,255,255,0.02);">
              <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead>
                  <tr style="border-bottom: 1px solid var(--border); text-align: left; color: var(--text-muted);">
                    <th style="padding: 8px; font-weight: 600;">Item Name</th>
                    <th style="padding: 8px; font-weight: 600;">Total Cost</th>
                    <th style="padding: 8px; font-weight: 600;">Allocated To (Budget Line)</th>
                    <th style="padding: 8px; font-weight: 600;">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in doc.items" :key="item.id" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 12px 8px; color: var(--text-primary);">{{ item.item_name }} <span v-if="item.sub_item" style="color: var(--text-muted); font-size: 0.8rem;">- {{ item.sub_item }}</span></td>
                    <td style="padding: 12px 8px; color: var(--text-primary);">₱{{ Number(item.amount).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</td>
                    <td style="padding: 12px 8px;">
                      <select v-if="item.amount > 0" v-model="item.gpb_budget_line_id" style="width: 200px; padding: 6px; background: #1e293b; border: 1px solid var(--border); color: #f8fafc; border-radius: 4px; outline: none;" @change="markAllocationsDirty">
                         <option :value="null">-- Not Allocated --</option>
                         <option v-for="bl in (currentAllocationStat?.budget_lines || [])" :key="bl.id" :value="bl.id">
                            {{ bl.label }} (₱{{ Number(bl.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2}) }})
                         </option>
                      </select>
                      <span v-else style="color: var(--text-muted); font-size: 0.8rem;">N/A</span>
                    </td>
                    <td style="padding: 12px 8px; font-size: 0.8rem;">
                       <span v-if="item.amount <= 0" style="color: var(--text-muted);" title="This item has no cost to allocate.">No Cost</span>
                       <span v-else-if="item.gpb_budget_line_id" style="color: #10b981; font-weight: 600;">Assigned</span>
                       <span v-else-if="getAllocatedElsewhere(item) >= item.amount" style="color: #ef4444; font-weight: 600;" title="This budget item has been fully assigned to other mandates. It cannot be assigned here unless it is removed from the other mandate first.">🔒 Locked</span>
                       <span v-else style="color: var(--text-muted);">Unassigned</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border); padding-top: 16px;">
           <button @click="closeAllocationModal" style="padding: 8px 16px; background: transparent; border: 1px solid var(--border); color: var(--text-primary); border-radius: 4px; cursor: pointer;">Cancel</button>
           <button @click="saveAllocations" :disabled="savingAllocations || !allocationsDirty" :style="{ padding: '8px 16px', background: '#3b82f6', color: 'white', border: 'none', borderRadius: '4px', cursor: 'pointer', opacity: allocationsDirty ? 1 : 0.5 }">
             {{ savingAllocations ? 'Saving...' : 'Save Allocations' }}
           </button>
        </div>
      </div>
    </div>

    <PdfPreviewModal :isOpen="isPdfModalOpen" :fileUrl="pdfFileUrl" @close="closePdfModal" />
  </div>
</template>

<script>
import { ref, computed, onMounted, nextTick } from 'vue';
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
           allocationsData.value = res.data.data.map(d => ({ ...d, _expanded: true }));
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

.app-wrapper {
  --primary:           #c084fc;
  --primary-dim:       rgba(147, 51, 234, 0.2);
  --primary-bright:    #d8b4fe;
  --primary-container: rgba(147, 51, 234, 0.15);
  --primary-on:        #ffffff;

  --secondary:         #4ade80;
  --secondary-dim:     #16a34a;
  --secondary-on:      #052e16;

  --bg:                linear-gradient(135deg, #0f172a, #020617);
  --surface:           linear-gradient(135deg, #0f172a, #020617);
  --surface-2:         rgba(0, 0, 0, 0.2);
  --surface-3:         rgba(0, 0, 0, 0.3);
  --surface-4:         rgba(147, 51, 234, 0.1);

  --text:              #ffffff;
  --text-muted:        #cbd5e1;
  --text-dim:          #94a3b8;

  --border:            rgba(147, 51, 234, 0.15);
  --border-bright:     rgba(147, 51, 234, 0.3);

  --error:             #ba1a1a;
  --error-bg:          #ffdad6;
  --success:           #4ade80;
  --warn:              #fbbf24;

  --shadow:        0 2px 8px rgba(0,0,0,0.5), 0 8px 24px rgba(0,0,0,0.35);
  --shadow-strong: 0 4px 24px rgba(0,0,0,0.7), 0 16px 48px rgba(0,0,0,0.5);
  --topbar-h:      64px;
}

*, *::before, *::after { box-sizing: border-box; }
.app-wrapper { margin: 0; padding: 0; background: var(--bg); }
.app-wrapper {
  color: var(--text);
  font-size: 15.5px;
  line-height: 1.6;
  min-height: calc(100vh - 80px);
}
.mono { font-family: 'IBM Plex Mono', monospace; }
h1, h2, h3 {  margin: 0; font-weight: 800; }
button { font-family: inherit; cursor: pointer; }
input, textarea, select { font-family: inherit; font-size: inherit; color: #ffffff; background: rgba(0,0,0,0.25);
  border: 1px solid var(--border);
  border-radius: 8px;
  transition: border-color 0.15s, box-shadow 0.15s;
}
input:focus, textarea:focus, select:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.18);
}
select option { background: var(--surface-2); color: var(--text); }

.app { display: flex; flex-direction: column; min-height: calc(100vh - 80px); }

.topbar { position: sticky; top: 0; z-index: 10; display: flex; align-items: center; justify-content: space-between; padding: 12px 24px; background: linear-gradient(135deg, #0f172a, #020617); border-bottom: 1px solid var(--border); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); }

.topbar-brand {
  display: flex;
  flex-direction: column;
  justify-content: center;
  min-width: 180px;
  margin-right: 6px;
  border-right: 1px solid var(--border);
  padding-right: 18px;
}
.topbar-eyebrow {
  font-size: 9.5px;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  color: var(--primary-bright);
  font-weight: 700;
  line-height: 1;
  margin-bottom: 3px;
}
.topbar-title {
  font-size: 14.5px;
  font-weight: 800;
  color: var(--text);
  line-height: 1.2;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 400px;
}

.topbar-actions {
  display: flex;
  gap: 8px;
  align-items: center;
  flex-shrink: 0;
}
.topbar-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  border-radius: 8px;
  padding: 7px 14px;
  font-size: 13px;
  font-weight: 700;
  border: 1px solid var(--border);
  transition: all 0.18s;
  white-space: nowrap;
}
.topbar-btn.outline {
  background: transparent;
  color: var(--text-muted);
}
.topbar-btn.outline:hover {
  background: var(--surface-3);
  border-color: var(--border-bright);
  color: var(--text);
}
.topbar-btn.primary {
  background: var(--primary-dim);
  border-color: var(--primary);
  color: var(--text);
  box-shadow: 0 2px 8px rgba(168,85,247,0.3);
}
.topbar-btn.primary:hover:not(:disabled) {
  background: var(--primary);
  transform: translateY(-1px);
  box-shadow: 0 4px 16px rgba(168,85,247,0.4);
}
.topbar-btn.primary:disabled { opacity: 0.5; cursor: not-allowed; }

.card { background: linear-gradient(135deg, #0f172a, #020617); border: 1px solid var(--border); box-shadow: var(--shadow); }
</style>
