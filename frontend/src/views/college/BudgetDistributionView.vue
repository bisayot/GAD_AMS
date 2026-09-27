<template>
  <div class="app-wrapper app" style="background: #ffffff; width: 100%; overflow-x: auto;">
    <div style="min-width: 1200px; margin: 32px 32px 0 32px; border-radius: 16px; overflow: hidden; border: 1px solid var(--border); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); background: var(--surface); display: flex; flex-direction: column;">
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
        <p style="font-size: 0.9rem;">The statistics are generated from your saved GAD Plan.<br>Please go to <router-link to="/college/plan-and-budget" style="color: #93c5fd; text-decoration: underline;">Plan & Budget</router-link> and click <b>"Save Plan"</b> first to generate statistics.</p>
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
            </div>
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
