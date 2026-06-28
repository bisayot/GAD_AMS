<template>
      <main class="flex-grow flex flex-col w-full box-border">
        
        <div class="controls-panel mb-3 flex justify-between items-center">
          <div class="panel-left">
            <h2 class="panel-title">Gender and Development Matrix</h2>
            <p class="panel-subtitle">Annual compliance and budget allocations</p>
          </div>
          <div class="panel-right flex items-center gap-3">
            <div class="year-selector-wrapper relative">
              <select 
                v-model="selectedYear" 
                @change="handleYearChange"
                class="year-selector"
              >
                <option value="2026">Year 2026</option>
                <option value="2025">Year 2025</option>
                <option value="2024">Year 2024</option>
              </select>
              <span class="material-symbols-outlined selector-icon">expand_more</span>
            </div>

            <button 
              @click="exportToExcel" 
              class="export-btn"
            >
              <span class="material-symbols-outlined">border_outer</span>
              Export Excel
            </button>
          </div>
        </div>

        <div class="view-toggle mb-3 flex gap-2 p-1 w-fit rounded-xl border border-[#b979cc]/10">
          <button 
            @click="activeView = 'table'" 
            :class="['view-btn', activeView === 'table' && 'view-btn-active']"
          >
            <span class="material-symbols-outlined">table_chart</span>
            Table View
          </button>
          <button 
            @click="activeView = 'pdf'" 
            :class="['view-btn', activeView === 'pdf' && 'view-btn-active']"
          >
            <span class="material-symbols-outlined">picture_as_pdf</span>
            PDF Document
          </button>
        </div>

        <div v-if="activeView === 'table'" class="table-container">
          <table class="gpb-table w-full table-fixed border-collapse">
            <thead>
              <tr class="table-header">
                <th class="th-col th-col-center th-col-num">#</th>
                <th class="th-col th-col-center th-col-mandate">Gender Issue / GAD Mandate</th>
                <th class="th-col th-col-center th-col-cause">Cause of Issue</th>
                <th class="th-col th-col-center th-col-result">GAD Result / Objective</th>
                <th class="th-col th-col-center th-col-mfo">Relevant MFO</th>
                <th class="th-col th-col-center th-col-activity">GAD Activity</th>
                <th class="th-col th-col-center th-col-indicators">Indicators / Targets</th>
                <th class="th-col th-col-center th-col-budget">Budget</th>
                <th class="th-col th-col-center th-col-source">Src</th>
              </tr>
            </thead>
            
             <tbody class="table-body">
              <tr class="section-header-row">
                <td colspan="9" class="section-header-cell client-header">
                  <span class="section-indicator client-indicator"></span>
                  CLIENT-FOCUSED ACTIVITIES
                 </td>
               </tr>
              <tr 
                v-for="(item, idx) in clientFocused" 
                :key="'client-' + item.id" 
                class="data-row"
              >
                <td class="data-cell data-cell-center data-cell-number">{{ idx + 1 }}</td>
                <td class="data-cell data-cell-mandate">{{ item.mandate }}</td>
                <td class="data-cell data-cell-cause">{{ item.cause }}</td>
                <td class="data-cell data-cell-result">{{ item.result }}</td>
                <td class="data-cell data-cell-mfo"><span class="mfo-badge">{{ item.mfo }}</span></td>
                <td class="data-cell data-cell-activity">{{ item.activity }}</td>
                <td class="data-cell data-cell-indicators">{{ item.indicators }}</td>
                <td class="data-cell data-cell-right data-cell-budget">₱{{ formatCurrency(item.budget) }}</td>
                <td class="data-cell data-cell-center data-cell-source">{{ item.source }}</td>
               </tr>

              <tr class="section-header-row">
                <td colspan="9" class="section-header-cell org-header">
                  <span class="section-indicator org-indicator"></span>
                  ORGANIZATION-FOCUSED ACTIVITIES
                 </td>
               </tr>
              <tr 
                v-for="(item, idx) in orgFocused" 
                :key="'org-' + item.id" 
                class="data-row"
              >
                <td class="data-cell data-cell-center data-cell-number">{{ clientFocused.length + idx + 1 }}</td>
                <td class="data-cell data-cell-mandate">{{ item.mandate }}</td>
                <td class="data-cell data-cell-cause">{{ item.cause }}</td>
                <td class="data-cell data-cell-result">{{ item.result }}</td>
                <td class="data-cell data-cell-mfo"><span class="mfo-badge">{{ item.mfo }}</span></td>
                <td class="data-cell data-cell-activity">{{ item.activity }}</td>
                <td class="data-cell data-cell-indicators">{{ item.indicators }}</td>
                <td class="data-cell data-cell-right data-cell-budget">₱{{ formatCurrency(item.budget) }}</td>
                <td class="data-cell data-cell-center data-cell-source">{{ item.source }}</td>
               </tr>

              <tr class="section-header-row">
                <td colspan="9" class="section-header-cell client-header" style="background: linear-gradient(90deg, rgba(16, 185, 129, 0.15) 0%, transparent 100%)">
                  <span class="section-indicator" style="background-color: #10b981"></span>
                  ATTRIBUTED PROGRAMS
                 </td>
               </tr>
              <tr 
                v-for="(item, idx) in attributedPrograms" 
                :key="'attributed-' + item.id" 
                class="data-row"
              >
                <td class="data-cell data-cell-center data-cell-number">{{ clientFocused.length + orgFocused.length + idx + 1 }}</td>
                <td class="data-cell data-cell-mandate">{{ item.mandate || 'N/A' }}</td>
                <td class="data-cell data-cell-cause">{{ item.cause || 'N/A' }}</td>
                <td class="data-cell data-cell-result">{{ item.result || 'N/A' }}</td>
                <td class="data-cell data-cell-mfo"><span class="mfo-badge">{{ item.mfo || 'N/A' }}</span></td>
                <td class="data-cell data-cell-activity">{{ item.activity }}</td>
                <td class="data-cell data-cell-indicators">{{ item.indicators || 'N/A' }}</td>
                <td class="data-cell data-cell-right data-cell-budget">₱{{ formatCurrency(item.budget) }}</td>
                <td class="data-cell data-cell-center data-cell-source">{{ item.source }}</td>
               </tr>
            </tbody>

            <tfoot>
              <tr class="total-row">
                <td colspan="7" class="total-label-cell">Total GAD Budget:</td>
                <td class="total-amount-cell">₱{{ formatCurrency(totalGadBudget) }}</td>
                <td class="total-empty-cell"></td>
               </tr>
            </tfoot>
           </table>
        </div>

        <div v-else class="pdf-viewer-container">
          <div class="pdf-header">
            <div class="pdf-header-left">
              <span class="material-symbols-outlined pdf-icon">description</span>
              <h3 class="pdf-title">{{ selectedYear }} GAD Plan and Budget (GPB) Document</h3>
            </div>
            <div class="pdf-header-right flex gap-2">
              <a 
                :href="`/uploads/${selectedYear}-GPB.pdf`" 
                target="_self" 
                class="pdf-btn"
              >
                <span class="material-symbols-outlined">open_in_new</span>
                Open Full PDF
              </a>
              <a 
                :href="`/uploads/${selectedYear}-GPB.pdf`" 
                :download="`${selectedYear}-GPB.pdf`" 
                class="pdf-btn"
              >
                <span class="material-symbols-outlined">download</span>
                Download
              </a>
            </div>
          </div>
          <div class="pdf-frame-wrapper">
            <iframe 
              :src="`/uploads/${selectedYear}-GPB.pdf`" 
              class="pdf-frame"
            ></iframe>
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
const activeView = ref('table');
const selectedYear = ref('2026');

const handleYearChange = () => {
  activeView.value = 'pdf';
};

const clientFocused = ref([]);
const orgFocused = ref([]);
const attributedPrograms = ref([]);

const totalGadBudget = computed(() => {
  const all = [...clientFocused.value, ...orgFocused.value, ...attributedPrograms.value];
  return all.reduce((sum, item) => sum + (Number(item.budget) || 0), 0);
});

const formatCurrency = (val) => {
  return Number(val || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const exportToExcel = () => {
  alert(`Exporting ${selectedYear.value} GAD Plan & Budget table content to Excel...`);
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

const fetchGadPlan = async () => {
  loading.value = true;
  try {
    const res = await api.get('budget/gad-plan');
    if (res.data.success) {
      const allItems = res.data.data;
      
      clientFocused.value = allItems.filter(item => item.form_type === 'client-focused activity').map(item => ({
        id: item.gpb_id,
        mandate: item.gender_issue_mandate,
        cause: item.cause_of_gender_issue,
        result: item.gad_result_objective,
        mfo: item.relevant_org_mfo_pap,
        activity: item.gad_activity,
        indicators: item.performance_indicators_targets,
        budget: item.gad_budget,
        source: item.source || 'GAA'
      }));

      orgFocused.value = allItems.filter(item => item.form_type === 'organization-focused activity').map(item => ({
        id: item.gpb_id,
        mandate: item.gender_issue_mandate,
        cause: item.cause_of_gender_issue,
        result: item.gad_result_objective,
        mfo: item.relevant_org_mfo_pap,
        activity: item.gad_activity,
        indicators: item.performance_indicators_targets,
        budget: item.gad_budget,
        source: item.source || 'GAA'
      }));

      attributedPrograms.value = allItems.filter(item => item.form_type === 'attributed program').map(item => ({
        id: item.gpb_id,
        mandate: item.gender_issue_mandate,
        cause: item.cause_of_gender_issue,
        result: item.gad_result_objective,
        mfo: item.relevant_org_mfo_pap,
        activity: item.gad_activity,
        indicators: item.performance_indicators_targets,
        budget: item.gad_budget,
        source: item.source || 'GAA'
      }));
    }
  } catch (err) {
    console.error('Error fetching GAD plan:', err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'gad_staff') {
    router.push('/login');
  }
  fetchGadPlan();
});
</script>

<style scoped>
.controls-panel {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border: 1px solid rgba(185, 121, 204, 0.1);
  padding: 1rem;
  border-radius: 1rem;
  backdrop-filter: blur(4px);
}

.panel-title {
  font-size: 1.125rem;
  font-weight: bold;
  color: white;
  letter-spacing: -0.025em;
}

.view-toggle {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border: 1px solid rgba(185, 121, 204, 0.1);
}

.panel-subtitle {
  font-size: 0.9rem;
  color: rgba(203, 213, 225, 0.7);
  margin-top: 0.125rem;
}

.year-selector-wrapper {
  position: relative;
}

.year-selector {
  appearance: none;
  border: 1px solid rgba(185, 121, 204, 0.2);
  border-radius: 0.75rem;
  padding: 0.5rem 2rem 0.5rem 0.75rem;
  font-size: 1rem;
  background-color: #1a1a2e;
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.year-selector:focus {
  outline: none;
  box-shadow: 0 0 0 2px #990dd1;
}

.selector-icon {
  position: absolute;
  right: 0.625rem;
  top: 50%;
  transform: translateY(-50%);
  color: #b979cc;
  font-size: 1rem;
  pointer-events: none;
}

/* Export Button */
.export-btn {
  background: rgba(0, 0, 0, 0.3);
  color: white;
  border: 1px solid rgba(185, 121, 204, 0.15);
  padding: 0.5rem 1rem;
  border-radius: 0.75rem;
  font-size: 1rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  backdrop-filter: blur(4px);
  transition: all 0.2s;
}

.export-btn:hover {
  border-color: rgba(185, 121, 204, 0.4);
  background: rgba(0, 0, 0, 0.5);
}

.export-btn .material-symbols-outlined {
  color: #b979cc;
  font-size: 1rem;
}

.view-btn {
  padding: 0.375rem 1rem;
  border-radius: 0.5rem;
  font-size: 1rem;
  font-weight: bold;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 0.375rem;
  cursor: pointer;
  color: #cbd5e1;
  background: transparent;
  border: none;
}

.view-btn:hover {
  color: white;
  background: rgba(255, 255, 255, 0.05);
}

.view-btn-active {
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
  color: white;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.view-btn .material-symbols-outlined {
  font-size: 1.1rem;
}

.table-container {
  border-radius: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  background: rgba(0, 0, 0, 0.35);
  backdrop-filter: blur(4px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  width: 100%;
}

.gpb-table {
  width: 100%;
  border-collapse: collapse;
}

.table-header {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  color: #b979cc;
  text-transform: uppercase;
  font-size: 0.85rem;
  font-weight: 800;
  letter-spacing: 0.05em;
  border-bottom: 1px solid rgba(185, 121, 204, 0.2);
}

.th-col {
  padding: 0.625rem;
  border-right: 1px solid rgba(185, 121, 204, 0.15);
}

.th-col-left {
  text-align: left;
}

.th-col-right {
  text-align: right;
}

.th-col-center {
  text-align: center;
}

.th-col-num { width: 3%; }
.th-col-mandate { width: 15%; }
.th-col-cause { width: 14%; }
.th-col-result { width: 15%; }
.th-col-mfo { width: 11%; }
.th-col-activity { width: 14%; }
.th-col-indicators { width: 14%; }
.th-col-budget { width: 9%; }
.th-col-source { width: 5%; }

/* Table Body */
.table-body {
  font-size: 0.9rem;
  color: #cbd5e1;
}

.section-header-row {
  background: rgba(26, 26, 46, 0.9);
  border-bottom: 1px solid rgba(185, 121, 204, 0.15);
  font-weight: bold;
  color: white;
  font-size: 0.85rem;
  letter-spacing: 0.025em;
}

.section-header-cell {
  padding: 0.625rem 0.75rem;
}

.client-header {
  background: linear-gradient(90deg, rgba(153, 13, 209, 0.15) 0%, transparent 100%);
}

.org-header {
  background: linear-gradient(90deg, rgba(185, 121, 204, 0.15) 0%, transparent 100%);
}

.section-indicator {
  display: inline-block;
  width: 0.375rem;
  height: 0.375rem;
  border-radius: 9999px;
  margin-right: 0.375rem;
}

.client-indicator {
  background-color: #990dd1;
}

.org-indicator {
  background-color: #b979cc;
}

.data-row {
  border-bottom: 1px solid rgba(185, 121, 204, 0.1);
  transition: background-color 0.15s;
}

.data-row:hover {
  background-color: rgba(153, 13, 209, 0.05);
}

.data-cell {
  background: #cbd5e1;
  padding: 0.625rem;
  border-right: 1px solid rgba(185, 121, 204, 0.1);
  vertical-align: top;
  font-size: 0.70rem;
}

.data-cell-center {
  text-align: center;
}

.data-cell-right {
  text-align: right;
}

.data-cell-number {
  font-weight: bold;
  color: #990dd1;
}

.data-cell-mandate {
  font-weight: 500;
  color: #16213e;
  word-break: break-word;
  white-space: normal;
}

.data-cell-cause,
.data-cell-indicators {
  color: #16213e;
  line-height: 1.5;
  word-break: break-word;
  white-space: normal;
}

.data-cell-result {
  color: #16213e;
  line-height: 1.5;
  font-weight: 500;
  word-break: break-word;
  white-space: normal;
}

.data-cell-activity {
  color: #16213e;
  font-weight: 500;
  word-break: break-word;
  white-space: normal;
}

.data-cell-budget {
  font-weight: bold;
  color: #16213e;
  letter-spacing: -0.025em;
  white-space: nowrap;
}

.data-cell-source {
  font-weight: bold;
  color: #16213e;
}

.mfo-badge {
  display: block;
  background: rgba(0, 0, 0, 0.4);
  padding: 0.125rem 0.375rem;
  border: 1px solid rgba(255, 255, 255, 0.05);
  border-radius: 0.25rem;
  font-size: 0.85rem;
  color: rgba(255, 255, 255, 0.8);
  text-align: center;
  font-weight: 500;
}

/* Total Row */
.total-row {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  font-weight: bold;
  color: white;
  font-size: 1rem;
  border-top: 2px solid rgba(185, 121, 204, 0.3);
}

.total-label-cell {
  padding: 0.75rem;
  border-right: 1px solid rgba(185, 121, 204, 0.15);
  text-align: right;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-weight: 800;
  color: #b979cc;
}

.total-amount-cell {
  padding: 0.75rem;
  border-right: 1px solid rgba(185, 121, 204, 0.15);
  text-align: right;
  font-size: 1.1rem;
  font-weight: 900;
  background: rgba(153, 13, 209, 0.1);
  letter-spacing: 0.025em;
  white-space: nowrap;
}

.total-empty-cell {
  padding: 0.75rem;
}

/* PDF Viewer */
.pdf-viewer-container {
  border-radius: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  background: rgba(0, 0, 0, 0.35);
  backdrop-filter: blur(4px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: all 0.2s;
  width: 100%;
}

.pdf-header {
  padding: 1rem;
  border-bottom: 1px solid rgba(185, 121, 204, 0.15);
  background: rgba(26, 26, 46, 0.6);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.pdf-header-left {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.pdf-icon {
  color: #b979cc;
  font-size: 1.25rem;
}

.pdf-title {
  font-weight: bold;
  color: white;
  font-size: 1rem;
}

.pdf-btn {
  background: rgba(0, 0, 0, 0.3);
  color: white;
  border: 1px solid rgba(185, 121, 204, 0.15);
  padding: 0.375rem 0.75rem;
  border-radius: 0.75rem;
  font-size: 1rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.375rem;
  cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
}

.pdf-btn:hover {
  border-color: rgba(185, 121, 204, 0.4);
  background: rgba(0, 0, 0, 0.5);
}

.pdf-btn .material-symbols-outlined {
  font-size: 1.1rem;
}

.pdf-frame-wrapper {
  padding: 0;
  background: rgba(255, 255, 255, 0.05);
}

.pdf-frame {
  width: 100%;
  border: none;
  min-height: 550px;
  height: 720px;
}

/* Footer */
.app-footer {
  width: 100%;
  text-align: center;
  padding-bottom: 1rem;
  pointer-events: none;
  font-size: 0.85rem;
  color: rgba(255, 255, 255, 0.3);
  font-weight: 500;
  letter-spacing: 0.025em;
}

/* Text Rendering */
.gpb-table th {
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
  word-wrap: break-word;
  hyphens: auto;
}

.gpb-table td {
  word-wrap: break-word;
}

/* Scrollbar */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: #1a1a2e;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: rgba(185, 121, 204, 0.25);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #990dd1;
}
</style>
