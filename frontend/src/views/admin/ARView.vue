<template>
  <main class="main-viewport">
    <div v-if="loading" class="loading-wrapper">
      <div class="loading-spinner"></div>
    </div>

    <div v-else-if="error" class="error-container">
      <div class="error-box">
        <p class="error-title">Error Loading Data</p>
        <p class="error-message">{{ error }}</p>
        <button @click="router.back()" class="error-back-btn">← Go Back</button>
      </div>
    </div>

    <div v-else class="page-container">
      <div class="layout-vertical">
        <!-- LEFT SECTION -->
        <section class="flex-full glass-card">
          <div class="report-header">
            <div class="meta-header">
              <div class="status-badge-view" :class="getStatusClass(report.status)">
                <span class="status-text">{{ formatStatus(report.status) }}</span>
              </div>
              <span class="control-number">{{ report.control || 'NO CONTROL NUMBER' }}</span>
            </div>

            <h2 class="report-title">{{ report.activity_title }}</h2>

            <div class="info-grid">
              <div class="info-item">
                <span class="info-label">Submitted By</span>
                <span class="info-value-purple">{{ report.submitter_name || '' }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Office</span>
                <span class="info-value-white">{{ report.office }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Date Submitted</span>
                <span class="info-value-white">{{ report.date || '---' }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Category</span>
                <span class="info-value-white">Accomplishment Report</span>
              </div>
            </div>
          </div>

          
          <div class="report-body">
            <div class="ar-horizontal-layout">
<!-- Approved Activity Design Details -->
            <div class="section-card" v-if="report.activity_design">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">info</span>
                <h3 class="section-title">Approved Activity Design Details</h3>
              </div>
              <div class="grid-2">
                <div class="full-width-info">
                  <label class="info-label">Title</label>
                  <p class="text-sm-light mt-1">{{ report.activity_design.activity_title }}</p>
                </div>
                <div>
                  <label class="info-label">Form Type</label>
                  <p class="text-sm-light mt-1">{{ report.activity_design.form_type }}</p>
                </div>
                <div>
                  <label class="info-label">GPB/GAD ID</label>
                  <p class="text-sm-light mt-1">{{ report.activity_design.gpb_id || 'N/A' }}</p>
                </div>
                <div>
                  <label class="info-label">Venue</label>
                  <p class="text-sm-light mt-1">{{ report.activity_design.venue_name || report.activity_design.venue }}</p>
                </div>
                <div>
                  <label class="info-label">Target Participants</label>
                  <p class="text-sm-light mt-1">{{ report.activity_design.target_participants }}</p>
                </div>
                <div>
                  <label class="info-label">Date</label>
                  <p class="text-sm-light mt-1">{{ formatDate(report.activity_design.start_date) }} to {{ formatDate(report.activity_design.end_date) }}</p>
                </div>
                <div>
                  <label class="info-label">Time</label>
                  <p class="text-sm-light mt-1">{{ formatTime(report.activity_design.start_time) }} to {{ formatTime(report.activity_design.end_time) }}</p>
                </div>
                <div>
                  <label class="info-label">Proposed Budget</label>
                  <p class="text-sm-light mt-1">PHP {{ Number(report.activity_design.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</p>
                </div>
                <div>
                  <label class="info-label">Assessment Date</label>
                  <p class="text-sm-light mt-1">{{ report.activity_design.assessment_date ? formatDate(report.activity_design.assessment_date) : 'N/A' }}</p>
                </div>
                <div class="full-width-info" v-if="report.activity_design.remarks">
                  <label class="info-label">Reviewer Remarks</label>
                  <div class="read-only-remarks mt-1">{{ report.activity_design.remarks }}</div>
                </div>
              </div>

              <!-- AD Attachment -->
              <div v-if="report.activity_design.attachment" class="doc-item mt-4">
                <div class="doc-info">
                  <span class="material-symbols-outlined doc-pdf-icon">picture_as_pdf</span>
                  <div>
                    <p class="doc-title">Approved_Design.pdf</p>
                    <p class="doc-meta">Reference: {{ report.activity_design.attachment }}</p>
                  </div>
                </div>
                <div class="doc-actions">
                  <button @click="previewFile(report.activity_design.attachment, 'archived')" class="preview-btn">Preview</button>
                  <button @click="downloadFile(report.activity_design.attachment, 'archived', 'Activity_Design')" class="download-btn-icon">
                    <span class="material-symbols-outlined">download</span>
                  </button>
                </div>
              </div>

              <!-- Approved Budget Breakdown -->
              <div class="full-width-info mt-4" v-if="parsedADBudget && parsedADBudget.length > 0">
                <label class="info-label mb-2">Approved Budget Breakdown</label>
                <div class="table-responsive">
                  <table class="custom-table">
                    <thead>
                      <tr>
                        <th>Budget Item</th>
                        <th class="text-right">Amount (PHP)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in parsedADBudget" :key="index">
                        <td v-html="formatBudgetName(item.name)"></td>
                        <td class="text-right">{{ Number(item.total || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td class="font-bold text-white">Grand Total (PHP)</td>
                        <td class="font-bold text-white text-right">{{ Number(report.activity_design.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>

            <!-- Actual Accomplishment Details -->
            <div class="section-card">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">fact_check</span>
                <h3 class="section-title">Actual Accomplishment Details</h3>
              </div>
              <div class="grid-2">
                <div class="full-width-info">
                  <label class="info-label">Actual Activity Title</label>
                  <p class="text-sm-light mt-1">{{ report.activity_title }}</p>
                </div>
                <div>
                  <label class="info-label">Start Date of Implementation</label>
                  <p class="text-sm-light mt-1">{{ formatDate(report.start_date) }}</p>
                </div>
                <div>
                  <label class="info-label">End Date of Implementation</label>
                  <p class="text-sm-light mt-1">{{ formatDate(report.end_date) }}</p>
                </div>
                <div>
                  <label class="info-label">Start Time</label>
                  <p class="text-sm-light mt-1">{{ formatTime(report.start_time) }}</p>
                </div>
                <div>
                  <label class="info-label">End Time</label>
                  <p class="text-sm-light mt-1">{{ formatTime(report.end_time) }}</p>
                </div>
                <div class="full-width-info">
                  <label class="info-label">Venue</label>
                  <p class="text-sm-light mt-1">{{ report.venue }}</p>
                </div>
                <div>
                  <label class="info-label">Number of Attendees</label>
                  <p class="text-sm-light mt-1">{{ report.attendees }}</p>
                </div>
                <div>
                  <label class="info-label">Male / Female Participants</label>
                  <p class="text-sm-light mt-1"><span class="male-val">{{ report.male }} Male</span> / <span class="female-val">{{ report.female }} Female</span></p>
                </div>
              </div>

              <!-- Actual Budget Expenditure -->
              <div class="full-width-info mt-4" v-if="parsedARBudget && parsedARBudget.length > 0">
                <label class="info-label mb-2">Actual Budget Expenditure</label>
                <div class="table-responsive">
                  <table class="custom-table">
                    <thead>
                      <tr>
                        <th>Budget Item</th>
                        <th class="text-right">Total (PHP)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in parsedARBudget" :key="index">
                        <td v-html="formatBudgetName(item.name)"></td>
                        <td class="text-right">{{ Number(item.total || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td class="font-bold text-white">Grand Total (PHP)</td>
                        <td class="font-bold text-white text-right">{{ Number(arBudgetTotal || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <!-- Evaluation Results -->
              <div class="full-width-info mt-4" v-if="parsedAREval && parsedAREval.length > 0">
                <label class="info-label mb-2">Evaluation Results</label>
                <div class="table-responsive">
                  <table class="custom-table">
                    <thead>
                      <tr>
                        <th>Area of Evaluation</th>
                        <th class="text-center">Average Rating</th>
                        <th>Interpretation</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in parsedAREval" :key="index">
                        <td>{{ item.area }}</td>
                        <td class="text-center">{{ item.rating }}</td>
                        <td>
                          <span :class="`interpretation-tag-ar ${getInterpretationClass(item.rating)}`">
                            {{ getInterpretation(item.rating) }}
                          </span>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td class="font-bold text-white">Total Average Rating</td>
                        <td class="font-bold text-white text-center">{{ report.rating }}</td>
                        <td class="font-bold text-white">
                          <span :class="`interpretation-tag-ar ${getInterpretationClass(report.rating)}`">
                            {{ getInterpretation(report.rating) }}
                          </span>
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

              <!-- AR Attachment -->
              <div v-if="report.attachment" class="doc-item mt-4">
                <div class="doc-info">
                  <span class="material-symbols-outlined doc-pdf-icon">picture_as_pdf</span>
                  <div>
                    <p class="doc-title">Accomplishment_Report.pdf</p>
                    <p class="doc-meta">Reference: {{ report.attachment }}</p>
                  </div>
                </div>
                <div class="doc-actions">
                  <button @click="previewFile(report.attachment, report.is_archived ? 'archived' : 'drafts')" class="preview-btn">Preview</button>
                  <button @click="downloadFile(report.attachment, report.is_archived ? 'archived' : 'drafts', 'Accomplishment_Report')" class="download-btn-icon">
                    <span class="material-symbols-outlined">download</span>
                  </button>
                </div>
</div>
              </div>
            </div>
          </div>

        </section>

        <!-- RIGHT SECTION - Assessment Sidebar -->
        <section class="flex-full">
          <div class="assessment-card-custom">
            <div class="assessment-header">
              <div class="assessment-icon">📋</div>
              <div class="assessment-title">Assessment Record</div>
            </div>

            <div class="assessment-form">
              <div class="info-item">
                <span class="info-label">Assessor Remarks</span>
                <div class="read-only-remarks">
                  {{ report.remarks || 'No remarks recorded for this accomplishment report.' }}
                </div>
              </div>

              <div class="action-buttons">
                <button @click="router.back()" class="btn-back">
                  ← Back to Archive
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
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../api';
import PdfPreviewModal from '../../components/PdfPreviewModal.vue';


const route = useRoute();
const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));
const report = ref({});
const loading = ref(true);
const error = ref(null);

const fetchReportDetails = async () => {
  loading.value = true;
  try {
    const id = route.params.id;
    const response = await api.get(`activity-report/${id}`);
    if (response.data.success) report.value = response.data.data;
    else error.value = "Accomplishment report not found.";
  } catch (err) {
    error.value = "Failed to load report data.";
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

const getStatusClass = (status) => {
  const s = (status || '').toLowerCase();
  if (s === 'pending') return 'pending';
  if (s === 'approved') return 'approved';
  if (s === 'completed' || s === 'archived') return 'completed';
  if (s === 'cancelled') return 'cancelled';
  if (s === 'revision required' || s === 'revision') return 'revision';
  return 'completed';
};

const isPdfModalOpen = ref(false);
const pdfFileUrl = ref('');

const closePdfModal = () => {
  isPdfModalOpen.value = false;
};


import { computed } from 'vue';

const parsedADBudget = computed(() => {
  if (!report.value.activity_design || !report.value.activity_design.budget_items || report.value.activity_design.budget_items.length === 0) return [];
  const b = report.value.activity_design.budget_items[0];
  const items = [
    { name: 'Meals and Snacks (AM/PM)', total: b.meals_and_snacks },
    { name: 'Function Room/Venue', total: b.function_room_venue },
    { name: 'Accommodation', total: b.accommodation },
    { name: 'Equipment Rental', total: b.equipment_rental },
    { name: 'Professional Fee/Honoraria', total: b.professional_fee_honoria },
    { name: 'Tokens', total: b.tokens },
    { name: 'Materials and Supplies', total: b.materials_and_supplies },
    { name: 'Transportation', total: b.transportation }
  ];
  return items.filter(i => parseFloat(i.total) > 0);
});

const parsedARBudget = computed(() => {
  if (!report.value.budget_items || report.value.budget_items.length === 0) return [];
  const b = report.value.budget_items[0];
  const items = [
    { name: 'Meals and Snacks (AM/PM)', total: b.meals_and_snacks },
    { name: 'Function Room/Venue', total: b.function_room_venue },
    { name: 'Accommodation', total: b.accommodation },
    { name: 'Equipment Rental', total: b.equipment_rental },
    { name: 'Professional Fee/Honoraria', total: b.professional_fee_honoria },
    { name: 'Tokens', total: b.tokens },
    { name: 'Materials and Supplies', total: b.materials_and_supplies },
    { name: 'Transportation', total: b.transportation }
  ];
  return items.filter(i => parseFloat(i.total) > 0);
});

const arBudgetTotal = computed(() => {
  return parsedARBudget.value.reduce((sum, item) => sum + (parseFloat(item.total) || 0), 0);
});

const parsedAREval = computed(() => {
  if (!report.value.evaluation_results || report.value.evaluation_results.length === 0) return [];
  const e = report.value.evaluation_results[0];
  return [
    { area: 'Time Management', rating: e.time_management },
    { area: 'Orderliness and Program Flow', rating: e.orderliness_and_program_flow },
    { area: 'Appropriateness of the Venue', rating: e.appropriateness_of_venue },
    { area: 'Sound System and Hall Preparation', rating: e.sound_system_and_hall_preparation },
    { area: 'Restroom/s', rating: e.restrooms },
    { area: 'Food and Drinks', rating: e.food_and_drinks }
  ];
});

const getInterpretation = (rating) => {
  const val = parseFloat(rating);
  if (isNaN(val) || val === 0) return '-';
  if (val >= 4.51) return 'Outstanding';
  if (val >= 4.01) return 'Very Good';
  if (val >= 3.51) return 'Good';
  if (val >= 3.01) return 'Average';
  if (val >= 2.51) return 'Fair';
  if (val >= 2.01) return 'Poor';
  return 'Very Poor';
};

const getInterpretationClass = (rating) => {
  const val = parseFloat(rating);
  if (isNaN(val) || val === 0) return '';
  if (val >= 4.51) return 'text-emerald-400';
  if (val >= 4.01) return 'text-teal-400';
  if (val >= 3.51) return 'text-cyan-400';
  if (val >= 3.01) return 'text-amber-400';
  if (val >= 2.51) return 'text-rose-400';
  if (val >= 2.01) return 'text-rose-500';
  return 'text-rose-600';
};

const formatBudgetName = (name) => {
  if (!name) return '';
  return name.replace(/(\([^\)]+\))/g, '<span style="opacity:0.7;font-size:11px;">$1</span>');
};

const previewFile = (filename, folder) => {
  if (!filename) return;
  const base = (import.meta.env.VITE_API_BASE_URL ? import.meta.env.VITE_API_BASE_URL.replace('/api/', '') : 'https://gad-ams-2-1.onrender.com');
  pdfFileUrl.value = `${base}/api/files/${folder}/${filename}`;
  isPdfModalOpen.value = true;
};

const downloadFile = (filename, folder, prefix) => {
  if (!filename) return;
  const base = (import.meta.env.VITE_API_BASE_URL ? import.meta.env.VITE_API_BASE_URL.replace('/api/', '') : 'https://gad-ams-2-1.onrender.com');
  const url = `${base}/api/files/${folder}/${filename}`;
  window.open(url, '_blank');
};


onMounted(() => {
  if (!user.value.id || user.value.role !== 'admin') router.push('/login');
  else fetchReportDetails();
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
.flex-055 { flex: 0.55; display: flex; flex-direction: column; overflow: hidden; }
.flex-045-sidebar { flex: 0.45; position: sticky; top: 20px; align-self: flex-start; }

.report-header { padding: 2rem; border-bottom: 1px solid rgba(185, 121, 204, 0.15); background: rgba(0, 0, 0, 0.2); }
.meta-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.report-title { font-size: 26px; color: white; line-height: 1.25; margin: 1rem 0; }
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
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.divider { color: rgba(203, 213, 225, 0.4); margin: 0 0.25rem; }
.male-val { color: #60a5fa; }
.female-val { color: #f472b6; }
.metric-split { display: flex; align-items: center; justify-content: center; }
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
.download-btn-icon { background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(185, 121, 204, 0.15); color: #cbd5e1; padding: 6px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.download-btn-icon:hover { border-color: #b979cc; color: white; }

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

.mt-1 { margin-top: 0.25rem; }
.mt-2 { margin-top: 0.5rem; }
.mt-4 { margin-top: 1.5rem; }
.mb-2 { margin-bottom: 0.5rem; }
.text-right { text-align: right; }
.text-center { text-align: center; }
.font-bold { font-weight: 700; }
.text-white { color: white; }

.table-responsive {
  overflow-x: auto;
  border-radius: 12px;
  border: 1px solid rgba(185, 121, 204, 0.15);
  background: rgba(0, 0, 0, 0.2);
}
.custom-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
}
.custom-table th {
  background: rgba(185, 121, 204, 0.1);
  color: #b979cc;
  font-weight: 700;
  text-transform: uppercase;
  padding: 12px 16px;
  text-align: left;
  border-bottom: 1px solid rgba(185, 121, 204, 0.15);
}
.custom-table td {
  padding: 12px 16px;
  color: #cbd5e1;
  border-bottom: 1px solid rgba(185, 121, 204, 0.05);
}
.custom-table tbody tr:last-child td {
  border-bottom: none;
}
.custom-table tfoot td {
  border-top: 1px solid rgba(185, 121, 204, 0.15);
  background: rgba(0, 0, 0, 0.3);
}

.interpretation-tag-ar {
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 700;
  background: rgba(255,255,255,0.05);
  display: inline-block;
}
.text-emerald-400 { color: #34d399; }
.text-teal-400 { color: #2dd4bf; }
.text-cyan-400 { color: #22d3ee; }
.text-amber-400 { color: #fbbf24; }
.text-rose-400 { color: #fb7185; }
.text-rose-500 { color: #f43f5e; }
.text-rose-600 { color: #e11d48; }


.ar-horizontal-layout {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

@media (min-width: 1280px) {
  .ar-horizontal-layout {
    flex-direction: row;
    align-items: flex-start;
  }
  .ar-horizontal-layout > .section-card {
    flex: 1;
    width: 50%;
    margin-bottom: 0;
  }
}


.layout-vertical {
  display: flex;
  flex-direction: column;
  gap: 24px;
}
.flex-full {
  flex: 1;
  width: 100%;
}

</style>
