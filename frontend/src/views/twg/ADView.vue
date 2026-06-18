<template>
  <main class="main-viewport">
    <div v-if="loading" class="loading-wrapper">
      <div class="loading-spinner"></div>
    </div>

    <div v-else-if="error" class="min-h-[60vh] flex items-center justify-center p-6">
      <div class="bg-black/80 backdrop-blur-3xl rounded-3xl border-2 border-red-500/40 max-w-md w-full text-center p-10 relative overflow-hidden flex flex-col items-center shadow-2xl shadow-red-900/20">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-64 h-64 bg-red-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="w-24 h-24 rounded-full bg-red-500/20 border-2 border-red-500/50 flex items-center justify-center mb-6 relative z-10 shadow-lg shadow-red-500/20">
          <span class="material-symbols-outlined text-5xl text-red-400 drop-shadow-md" v-if="error.includes('Access Denied')">gpp_bad</span>
          <span class="material-symbols-outlined text-5xl text-red-400 drop-shadow-md" v-else>error</span>
        </div>
        <h2 class="text-3xl font-headline font-black text-white mb-3 relative z-10 tracking-tight drop-shadow-md">
          {{ error.includes('Access Denied') ? 'Access Restricted' : 'Error Loading Data' }}
        </h2>
        <p class="text-slate-200 font-body text-base font-medium mb-10 relative z-10 leading-relaxed px-2">
          {{ error }}
        </p>
        <button @click="router.back()" class="relative z-10 bg-red-600 hover:bg-red-500 text-white shadow-lg shadow-red-900/50 px-10 py-4 rounded-full font-label text-sm font-extrabold tracking-widest uppercase transition-all hover:-translate-y-1 active:translate-y-0 flex items-center gap-3 group">
          <span class="material-symbols-outlined text-base group-hover:-translate-x-1 transition-transform font-bold">arrow_back</span>
          Go Back
        </button>
      </div>
    </div>

    <div v-else class="page-container">
      <div class="layout-grid">
        <!-- LEFT SECTION - Design Preview -->
        <section class="flex-06 glass-card">
          <div class="report-header">
            <div class="meta-header">
              <div class="status-badge-view" :class="getStatusClass(design.status)">
                <span class="status-text">{{ formatStatus(design.status) }}</span>
              </div>
              <span class="control-number">{{ design.control || 'PENDING ASSIGNMENT' }}</span>
            </div>

            <h2 class="report-title">{{ design.activity_title }}</h2>

            <div class="info-grid">
              <div class="info-item" style="grid-column: span 2;">
                <span class="info-label">Activity Title</span>
                <span class="info-value-white">{{ design.activity_title }}</span>
              </div>
              <div class="info-item">
              <span class="info-label">Submitted By</span>
              <span class="info-value-purple">{{ design.submitter_name || '' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Office / Unit</span>
                <span class="info-value-purple">{{ design.office }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Date Submitted</span>
                <span class="info-value-white">{{ design.date || '---' }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Category</span>
                <span class="info-value-white">Activity Design</span>
              </div>
              <div class="info-item">
                <span class="info-label">Form Type</span>
                <span class="info-value-white uppercase">{{ formatFormType(design.form_type) }}</span>
              </div>
            </div>
          </div>

          <div class="report-body">
            <div class="section-card">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">calendar_month</span>
                <h3 class="section-title">Schedule & Venue</h3>
              </div>
              <div class="grid-2">
                <div>
                  <label class="info-label">Start Date</label>
                  <p class="info-value-white">{{ formatDate(design.start_date) }}</p>
                </div>
                <div>
                  <label class="info-label">End Date</label>
                  <p class="info-value-white">{{ formatDate(design.end_date) }}</p>
                </div>
                <div>
                  <label class="info-label">Start Time</label>
                  <p class="info-value-white">{{ formatTime(design.start_time) }}</p>
                </div>
                <div>
                  <label class="info-label">End Time</label>
                  <p class="info-value-white">{{ formatTime(design.end_time) }}</p>
                </div>
                <div class="full-width-info">
                  <label class="info-label">Venue</label>
                  <p class="info-value-white">{{ design.venue }}</p>
                </div>
                <div class="full-width-info participants-info">
                  <label class="info-label">Target Participants</label>
                  <p class="info-value-white">{{ design.target_participants }} individuals</p>
                </div>
              </div>
            </div>

            <div class="section-card">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">payments</span>
                <h3 class="section-title">Proposed Budgetary Requirements</h3>
              </div>
              <div v-if="parsedBudget.length" class="budget-content">
                <div class="budget-table-wrapper">
                  <table class="budget-table">
                    <thead class="budget-table-header">
                      <tr>
                        <th class="table-header-cell">Budget Item</th>
                        <th class="table-header-cell budget-total-header">Total</th>
                      </tr>
                    </thead>
                    <tbody class="budget-table-body">
                      <tr v-for="(item, idx) in parsedBudget" :key="idx" class="budget-table-row">
                        <td class="budget-item-name" v-html="formatBudgetName(item.name)"></td>
                        <td class="budget-item-value-cell budget-value-right">
                          <span class="budget-item-value">₱{{ formatCurrency(item.total) }}</span>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot class="budget-table-footer">
                      <tr>
                        <td class="grand-total-label">Grand Total (PHP)</td>
                        <td class="grand-total-value-white budget-value-right">₱{{ formatCurrency(design.proposed_budget) }}</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
              <div v-else class="empty-budget-notice">
                No budgetary requirements were specified for this design.
              </div>
            </div>

            <div v-if="design.attachment" class="section-card">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">description</span>
                <h3 class="section-title">Supporting Documents</h3>
              </div>
              <div class="doc-item">
                <div class="doc-info">
                  <span class="material-symbols-outlined doc-pdf-icon">picture_as_pdf</span>
                  <div>
                    <p class="doc-title">Activity_Design_Framework.pdf</p>
                    <p class="doc-meta">Reference: {{ design.attachment }}</p>
                  </div>
                </div>
                <button @click="previewFile(design.attachment)" class="preview-btn">Preview</button>
              </div>
            </div>
          </div>
        </section>

        <!-- RIGHT SECTION - Assessment Sidebar -->
        <section class="flex-04-sidebar">
          <div class="assessment-card-custom">
            <div class="assessment-header">
              <div class="assessment-icon">📋</div>
              <div class="assessment-title">Assessment Record</div>
            </div>

            <div class="assessment-form">
              <div class="info-item mb-4">
                <span class="info-label">Assessment Date</span>
                <span class="info-value-white">{{ formatDate(design.assessment_date) || '---' }}</span>
              </div>

              <div class="info-item mb-4">
                <span class="info-label">Accomplishment Deadline</span>
                <span class="info-value-white">{{ formatDate(design.accomplishment_deadline) || '---' }}</span>
              </div>

              <div class="info-item">
                <span class="info-label">Reviewer Remarks</span>
                <div class="read-only-remarks">
                  {{ design.remarks || 'No remarks provided for this design.' }}
                </div>
              </div>

              <div class="action-buttons">
                <button @click="router.back()" class="btn-back">
                  ← Back
                </button>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>

    <!-- PDF Preview Modal -->
    <PdfPreviewModal :isOpen="isPdfModalOpen" :fileUrl="pdfFileUrl" @close="closePdfModal" />
  </main>
</template>

<script setup>

const formatBudgetName = (name) => {
  if (!name) return '';
  return name.replace(/(\([^)]+\))/g, '<span class="budget-item-subtext">$1</span>');
};

const parsedBudget = computed(() => {
  const d = design.value;
  if (!d || !d.act_design_id) return [];
  const items = [
    { name: 'Meals and Snacks (AM/PM)', total: d.meals_and_snacks },
    { name: 'Function Room/Venue', total: d.function_room_venue },
    { name: 'Accommodation', total: d.accommodation },
    { name: 'Equipment Rental', total: d.equipment_rental },
    { name: 'Professional Fee/Honoria', total: d.professional_fee_honoria },
    { name: 'Token/s', total: d.tokens },
    { name: 'Materials and Supplies', total: d.materials_and_supplies },
    { name: 'Transportation', total: d.transportation }
  ];
  return items.filter(item => Number(item.total) > 0);
});

import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../api';
import PdfPreviewModal from '../../components/PdfPreviewModal.vue';

const route = useRoute();
const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));
const design = ref({});
const loading = ref(true);
const error = ref(null);

const fetchDesignDetails = async () => {
  loading.value = true;
  try {
    const id = route.params.id;
    const response = await api.get(`activity-design/${id}`);
    if (response.data.success) design.value = response.data.data;
    else error.value = "Activity design not found.";
  } catch (err) {
    error.value = "Failed to load activity design.";
  } finally {
    loading.value = false;
  }
};

const formatDate = (date) => date ? new Date(date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '---';
const formatTime = (time) => {
  if (!time) return '---';
  const [h, m] = time.split(':');
  return `${h % 12 || 12}:${m} ${h >= 12 ? 'PM' : 'AM'}`;
};

const formatStatus = (status) => {
  if (!status) return 'Unknown';
  if (status.toLowerCase() === 'revision required') return 'For Revision';
  return status.charAt(0).toUpperCase() + status.slice(1);
};


const formatFormType = (type) => {
  if (!type) return '---';
  const map = {
    'employee': 'Employee Training',
    'inset': 'INSET Training',
    'extension': 'Extension Program',
    'student': 'Student Activity'
  };
  return map[type] || type;
};

const getStatusClass = (status) => {
  const s = (status || '').toLowerCase();
  if (s === 'pending') return 'pending';
  if (s === 'approved') return 'approved';
  if (s === 'completed' || s === 'archived') return 'completed';
  if (s === 'cancelled') return 'cancelled';
  if (s === 'revision required' || s === 'revision') return 'revision';
  return 'completed';
};

const formatCurrency = (amt) => amt ? parseFloat(amt).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '0.00';

const isPdfModalOpen = ref(false);
const pdfFileUrl = ref('');

const previewFile = (fileName) => {
  if (!fileName) return;
  const base = (import.meta.env.VITE_API_BASE_URL ? import.meta.env.VITE_API_BASE_URL.replace('/api/', '') : 'https://gad-ams-2-1.onrender.com');
  const folder = design.value.is_archived ? 'archived' : 'drafts';
  pdfFileUrl.value = `${base}/api/files/${folder}/${fileName}`;
  isPdfModalOpen.value = true;
};

const closePdfModal = () => {
  isPdfModalOpen.value = false;
  pdfFileUrl.value = '';
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'college') router.push('/login');
  else fetchDesignDetails();
});
</script>

<style scoped>
.main-viewport { flex: 1; overflow-y: auto; background: transparent; }
.loading-wrapper { display: flex; justify-content: center; align-items: center; min-height: 400px; }
.error-container { max-width: 48rem; margin: 0 auto; padding: 2.5rem; }
.error-box { background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; padding: 1rem; border-radius: 0.75rem; }
.error-title { color: #ef4444; font-weight: 700; }
.error-message { color: #cbd5e1; font-size: 1.1rem; }
.error-back-btn { margin-top: 1rem; font-size: 1.1rem; font-weight: 700; color: #ef4444; background: transparent; border: none; cursor: pointer; }
.page-container { min-height: 100vh;  }
.glass-card { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); backdrop-filter: blur(24px); border-radius: 1.5rem; border: 1px solid rgba(185, 121, 204, 0.2); }

.layout-grid { display: flex; gap: 32px; padding: 2.5rem; max-width: 80rem; margin: 0 auto; }
.flex-06 { flex: 0.6; display: flex; flex-direction: column; overflow: hidden; }
.flex-04-sidebar { flex: 0.4; position: sticky; top: 20px; align-self: flex-start; }

.report-header { padding: 2rem; border-bottom: 1px solid rgba(185, 121, 204, 0.15); background: rgba(0, 0, 0, 0.2); }
.meta-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.report-title { font-family: 'Times New Roman', serif; font-size: 26px; color: white; line-height: 1.25; margin: 1rem 0; }
.status-badge-view { padding: 4px 12px; border-radius: 9999px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; }
.status-badge-view.completed { background: rgba(34, 197, 94, 0.15); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); }
.status-badge-view.cancelled { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
.status-badge-view.pending { background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); }
.status-badge-view.approved { background: rgba(59, 130, 246, 0.15); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.3); }
.status-badge-view.revision { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
.control-number { font-size: 11px; font-weight: 700; color: #b979cc; text-transform: uppercase; margin-left: 12px; font-family: monospace; }
.info-grid { display: flex; flex-wrap: wrap; gap: 24px; padding-top: 16px; border-top: 1px solid rgba(185, 121, 204, 0.1); }
.info-item { display: flex; flex-direction: column; }
.info-label { font-size: 10px; text-transform: uppercase; color: #cbd5e1; font-weight: 700; margin-bottom: 4px; }
.info-value-white { font-size: 14px; font-weight: 600; color: white; }
.info-value-purple { font-size: 14px; font-weight: 600; color: #b979cc; }
.report-body { padding: 2rem; }
.report-body > * + * { margin-top: 1.5rem; }
.section-card { background: rgba(0, 0, 0, 0.2); border-radius: 16px; padding: 24px; border: 1px solid rgba(185, 121, 204, 0.15); }
.section-header-row { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.section-title { font-weight: 800; font-size: 13px; text-transform: uppercase; color: #b979cc; }
.icon-pink { color: #b979cc; }
.text-sm-light { font-size: 1.1rem; color: #cbd5e1; font-weight: 500; }
.grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
.metric-box { background: rgba(0, 0, 0, 0.3); border-radius: 12px; padding: 16px; text-align: center; border: 1px solid rgba(185, 121, 204, 0.1); }
.metric-value { font-size: 24px; font-weight: 700; color: white; }
.metric-label { font-size: 10px; color: #cbd5e1; text-transform: uppercase; margin-top: 4px; }
.doc-item { display: flex; align-items: center; justify-content: space-between; gap: 24px; padding: 16px; background: rgba(0, 0, 0, 0.3); border-radius: 12px; border: 1px solid rgba(185, 121, 204, 0.15); overflow-x: auto; }
.doc-info { display: flex; align-items: center; gap: 12px; }
.doc-pdf-icon { font-size: 1.875rem; color: #ef4444; }
.doc-title { font-size: 13px; font-weight: 700; color: white; white-space: nowrap; }
.doc-meta { font-size: 11px; color: #cbd5e1; margin-top: 2px; white-space: nowrap; }
.preview-btn { color: #b979cc; font-size: 11px; padding: 6px 16px; border-radius: 8px; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(185, 121, 204, 0.15); font-weight: 700; cursor: pointer; transition: all 0.2s; }
.preview-btn:hover { border-color: #b979cc; color: white; background: rgba(185, 121, 204, 0.1); }

.assessment-card-custom {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border-radius: 1.5rem;
  padding: 2rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(185, 121, 204, 0.2);
}
.assessment-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid rgba(185, 121, 204, 0.15); }
.assessment-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; }
.assessment-title { font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #b979cc; }
.assessment-form { display: flex; flex-direction: column; }

.read-only-remarks {
  width: 100%;
  border: 1px solid rgba(185, 121, 204, 0.2);
  border-radius: 12px;
  padding: 14px 16px;
  font-size: 13px;
  background: rgba(0, 0, 0, 0.3);
  color: #cbd5e1;
  min-height: 100px;
  line-height: 1.5;
}

.action-buttons { margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(185, 121, 204, 0.15); }
.btn-back { width: 100%; padding: 12px; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #cbd5e1; border-radius: 12px; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(185, 121, 204, 0.15); cursor: pointer; transition: all 0.2s; }
.btn-back:hover { color: white; border-color: #b979cc; background: rgba(185, 121, 204, 0.05); }

@media (max-width: 1024px) {
  .layout-grid { flex-direction: column; padding: 1rem; }
  .flex-06, .flex-055, .flex-04-sidebar, .flex-045-sidebar { flex: 1 !important; width: 100% !important; max-width: 100% !important; position: relative !important; top: 0 !important; }
}

@media (max-width: 768px) {
  .grid-2, .grid-3 { grid-template-columns: 1fr !important; }
  .info-grid { flex-direction: column !important; gap: 12px !important; }
}


/* CREATIVE BUDGET TABLE STYLES */
.budget-table-wrapper {
  overflow: hidden;
  border-radius: 16px;
  background: linear-gradient(145deg, rgba(30, 41, 59, 0.4) 0%, rgba(15, 23, 42, 0.6) 100%);
  border: 1px solid rgba(185, 121, 204, 0.25);
  box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(10px);
}

.budget-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  text-align: left;
}

.budget-table-header {
  background: linear-gradient(90deg, rgba(185, 121, 204, 0.2) 0%, rgba(185, 121, 204, 0.05) 100%);
}

.table-header-cell {
  padding: 16px 20px;
  font-size: 11px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: #e2e8f0;
  border-bottom: 2px solid rgba(185, 121, 204, 0.4);
}

.budget-total-header {
  text-align: right;
}

.budget-table-row {
  transition: all 0.3s ease;
}

.budget-table-row:hover {
  background: rgba(185, 121, 204, 0.1);
  transform: scale(1.002);
}

.budget-table-row td {
  border-bottom: 1px solid rgba(185, 121, 204, 0.1);
}

.budget-table-row:last-child td {
  border-bottom: none;
}

.budget-item-name {
  padding: 16px 20px;
  font-size: 14px;
  font-weight: 600;
  color: #f8fafc;
}

.budget-item-subtext {
  display: inline-block;
  font-size: 11px;
  color: #94a3b8;
  margin-left: 8px;
  font-weight: 400;
  background: rgba(0,0,0,0.2);
  padding: 2px 8px;
  border-radius: 12px;
}

.budget-item-value-cell {
  padding: 16px 20px;
  text-align: right;
}

.budget-item-value {
  font-family: 'Courier New', Courier, monospace;
  font-size: 15px;
  font-weight: 800;
  color: #fff;
  background: linear-gradient(135deg, rgba(185, 121, 204, 0.2) 0%, rgba(153, 13, 209, 0.2) 100%);
  padding: 6px 12px;
  border-radius: 8px;
  border: 1px solid rgba(185, 121, 204, 0.3);
  box-shadow: inset 0 2px 4px rgba(0,0,0,0.2);
}

.budget-table-footer {
  background: linear-gradient(90deg, rgba(0,0,0,0.4) 0%, rgba(185, 121, 204, 0.15) 100%);
}

.budget-table-footer td {
  border-top: 2px solid rgba(185, 121, 204, 0.4);
}

.grand-total-label {
  padding: 20px;
  font-size: 13px;
  font-weight: 900;
  color: #b979cc;
  text-align: right;
  text-transform: uppercase;
  letter-spacing: 0.1em;
}

.grand-total-value-white {
  padding: 20px;
  font-family: 'Courier New', Courier, monospace;
  font-size: 18px;
  font-weight: 900;
  color: #fff;
  text-align: right;
  text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}

</style>