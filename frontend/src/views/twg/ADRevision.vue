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
        <!-- LEFT SECTION - Edit Form -->
        <section class="flex-06 glass-card">
          <div class="report-header">
            <div class="meta-header">
              <div class="status-badge-revision">
                <div class="status-dot-pulse"></div>
                <span class="status-text">Revision Mode</span>
              </div>
              <span class="control-number">{{ design.control || 'PENDING ASSIGNMENT' }}</span>
            </div>

            <div class="form-group-top">
              <label class="form-label">Activity Title</label>
              <input 
                v-model="formData.activity_title" 
                type="text" 
                class="modal-input title-input" 
                placeholder="Enter Activity Title"
              >
            </div>

            <div class="info-grid">
              <div class="info-item">
                <span class="info-label">Office / Unit</span>
                <span class="info-value-white">{{ formData.office }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Form Type</span>
                <select v-model="formData.form_type" class="modal-input select-input">
                  <option value="employee">Employee Training</option>
                  <option value="inset">INSET Training</option>
                  <option value="extension">Extension Program</option>
                  <option value="student">Student Activity</option>
                </select>
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
                  <label class="form-label">Start Date</label>
                  <input type="date" v-model="formData.start_date" class="modal-input code-icon-calendar" />
                </div>
                <div>
                  <label class="form-label">End Date</label>
                  <input type="date" v-model="formData.end_date" class="modal-input code-icon-calendar" />
                </div>
                <div>
                  <label class="form-label">Start Time</label>
                  <input type="time" v-model="formData.start_time" class="modal-input code-icon-clock" />
                </div>
                <div>
                  <label class="form-label">End Time</label>
                  <input type="time" v-model="formData.end_time" class="modal-input code-icon-clock" />
                </div>
                <div class="full-width-info">
                  <label class="form-label">Venue</label>
                  <input type="text" v-model="formData.venue" class="modal-input" placeholder="Enter venue..." />
                </div>
                <div class="full-width-info participants-info">
                  <label class="form-label">Target Participants</label>
                  <input type="number" v-model="formData.target_participants" class="modal-input" placeholder="Number of participants" />
                </div>
              </div>
            </div>

            <div class="section-card">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">payments</span>
                <h3 class="section-title">Proposed Budgetary Requirements</h3>
              </div>
              <div class="budget-content">
                <div class="budget-table-wrapper">
                  <table class="budget-table">
                    <thead class="budget-table-header">
                      <tr>
                        <th class="table-header-cell">Budget Item</th>
                        <th class="table-header-cell budget-total-header">Total</th>
                      </tr>
                    </thead>
                    <tbody class="budget-table-body">
                      <tr class="budget-table-row"><td class="budget-item-name">Meals and Snacks (AM/PM)</td><td class="budget-item-value-cell budget-value-right"><div class="budget-input-wrapper"><span class="currency-symbol">₱</span><input type="number" v-model="budgetItems.meals_and_snacks" class="budget-input" min="0" step="0.01"></div></td></tr>
                      <tr class="budget-table-row"><td class="budget-item-name">Function Room/Venue</td><td class="budget-item-value-cell budget-value-right"><div class="budget-input-wrapper"><span class="currency-symbol">₱</span><input type="number" v-model="budgetItems.function_room_venue" class="budget-input" min="0" step="0.01"></div></td></tr>
                      <tr class="budget-table-row"><td class="budget-item-name">Accommodation</td><td class="budget-item-value-cell budget-value-right"><div class="budget-input-wrapper"><span class="currency-symbol">₱</span><input type="number" v-model="budgetItems.accommodation" class="budget-input" min="0" step="0.01"></div></td></tr>
                      <tr class="budget-table-row"><td class="budget-item-name">Equipment Rental</td><td class="budget-item-value-cell budget-value-right"><div class="budget-input-wrapper"><span class="currency-symbol">₱</span><input type="number" v-model="budgetItems.equipment_rental" class="budget-input" min="0" step="0.01"></div></td></tr>
                      <tr class="budget-table-row"><td class="budget-item-name">Professional Fee/Honoria</td><td class="budget-item-value-cell budget-value-right"><div class="budget-input-wrapper"><span class="currency-symbol">₱</span><input type="number" v-model="budgetItems.professional_fee_honoria" class="budget-input" min="0" step="0.01"></div></td></tr>
                      <tr class="budget-table-row"><td class="budget-item-name">Token/s</td><td class="budget-item-value-cell budget-value-right"><div class="budget-input-wrapper"><span class="currency-symbol">₱</span><input type="number" v-model="budgetItems.tokens" class="budget-input" min="0" step="0.01"></div></td></tr>
                      <tr class="budget-table-row"><td class="budget-item-name">Materials and Supplies</td><td class="budget-item-value-cell budget-value-right"><div class="budget-input-wrapper"><span class="currency-symbol">₱</span><input type="number" v-model="budgetItems.materials_and_supplies" class="budget-input" min="0" step="0.01"></div></td></tr>
                      <tr class="budget-table-row"><td class="budget-item-name">Transportation</td><td class="budget-item-value-cell budget-value-right"><div class="budget-input-wrapper"><span class="currency-symbol">₱</span><input type="number" v-model="budgetItems.transportation" class="budget-input" min="0" step="0.01"></div></td></tr>
                    </tbody>
                    <tfoot class="budget-table-footer">
                      <tr>
                        <td class="grand-total-label">Grand Total (PHP)</td>
                        <td class="grand-total-value-white budget-value-right">₱{{ formatCurrency(computedTotalBudget) }}</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>

            </div>

            <div class="section-card">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">description</span>
                <h3 class="section-title">Supporting Documents</h3>
              </div>
              <div v-if="design.attachment" class="doc-item mb-4">
                <div class="doc-info">
                  <span class="material-symbols-outlined doc-pdf-icon">picture_as_pdf</span>
                  <div>
                    <p class="doc-title">Current File</p>
                    <p class="doc-meta">Reference: {{ design.attachment }}</p>
                  </div>
                </div>
                <button @click="previewFile(design.attachment)" class="preview-btn">Preview</button>
              </div>
              <div class="doc-item" style="margin-top: 16px;">
                <div class="doc-info" style="width: 100%; align-items: flex-start;">
                  <span class="material-symbols-outlined doc-pdf-icon" style="color: #b979cc; margin-top: 4px;">upload_file</span>
                  <div style="width: 100%;">
                    <p class="doc-title">Upload New Activity Design (PDF)</p>
                    <p class="input-hint" style="margin-top: 4px; color: #ffffff; font-size: 11px;">Uploading a new file will replace the current document.</p>
                    <input type="file" accept="application/pdf" @change="handleFileChange" class="modal-input" style="padding: 10px; margin-top: 12px; background: rgba(0,0,0,0.2);" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- RIGHT SECTION - Feedback & Actions -->
        <section class="flex-04-sidebar">
          <div class="assessment-card-custom">
            <div class="assessment-header">
              <div class="assessment-icon">📋</div>
              <div class="assessment-title">Reviewer Feedback</div>
            </div>

            <div class="assessment-form">
              <div class="info-item mb-4">
                <span class="info-label">Previous Remarks</span>
                <div class="read-only-remarks">
                  {{ design.remarks || 'No remarks provided.' }}
                </div>
              </div>

              <div class="action-buttons">
                <button @click="handleUpdate" class="btn-approve" :disabled="submitting">
                  <span class="material-symbols-outlined">send</span> 
                  {{ submitting ? 'Updating...' : 'Resubmit for Review' }}
                </button>
                <button @click="router.back()" class="btn-back">
                  Cancel Changes
                </button>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </main>
</template>

<script setup>


const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const formatTime = (timeString) => {
  if (!timeString) return '';
  const [hours, minutes] = timeString.split(':');
  const d = new Date();
  d.setHours(hours, minutes, 0);
  return d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
};

const formatCurrency = (amount) => {
  if (!amount) return '0.00';
  return Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const previewFile = (filename) => {
  if (!filename) return;
  const baseUrl = api.defaults.baseURL.replace(/\/$/, '');
  window.open(`${baseUrl}/files/drafts/${filename}`, '_blank');
};

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
import Swal from 'sweetalert2';

const route = useRoute();
const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const design = ref({});
const loading = ref(true);
const submitting = ref(false);
const error = ref(null);
const newFile = ref(null);

const formData = ref({
  activity_title: '',
  office: '',
  form_type: '',
  start_date: '',
  end_date: '',
  start_time: '',
  end_time: '',
  venue: '',
  proposed_budget: 0,
  target_participants: 0
});

const budgetItems = ref({
  meals_and_snacks: 0,
  function_room_venue: 0,
  accommodation: 0,
  equipment_rental: 0,
  professional_fee_honoria: 0,
  tokens: 0,
  materials_and_supplies: 0,
  transportation: 0
});

const computedTotalBudget = computed(() => {
  return Object.values(budgetItems.value).reduce((sum, val) => sum + (parseFloat(val) || 0), 0);
});


const fetchDesignDetails = async () => {
  loading.value = true;
  try {
    const id = route.params.id;
    const response = await api.get(`activity-design/${id}`);
    if (response.data.success) {
      design.value = response.data.data;
      // Map data to formData
      formData.value = {
        activity_title: design.value.activity_title,
        office: design.value.office,
        form_type: design.value.form_type,
        start_date: design.value.start_date,
        end_date: design.value.end_date,
        start_time: design.value.start_time,
        end_time: design.value.end_time,
        venue: design.value.venue,
        proposed_budget: design.value.proposed_budget,
        target_participants: design.value.target_participants
      };
      
      budgetItems.value = {
        meals_and_snacks: design.value.meals_and_snacks || 0,
        function_room_venue: design.value.function_room_venue || 0,
        accommodation: design.value.accommodation || 0,
        equipment_rental: design.value.equipment_rental || 0,
        professional_fee_honoria: design.value.professional_fee_honoria || 0,
        tokens: design.value.tokens || 0,
        materials_and_supplies: design.value.materials_and_supplies || 0,
        transportation: design.value.transportation || 0
      };
    } else {
      error.value = "Activity design not found.";
    }
  } catch (err) {
    error.value = "Failed to load activity design details.";
  } finally {
    loading.value = false;
  }
};

const handleFileChange = (e) => {
  newFile.value = e.target.files[0];
};

const handleUpdate = async () => {
  // Validation removed as requested: user can change 1 or 2 fields freely.
  if (!newFile.value) {
    const confirm = await Swal.fire({
      title: 'No new file selected',
      text: 'Are you sure you want to resubmit without changing the document upload?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#b979cc',
      cancelButtonColor: '#ef4444',
      confirmButtonText: 'Yes, proceed'
    });
    if (!confirm.isConfirmed) return;
  }

  submitting.value = true;
  try {
    const id = route.params.id;
    const submitData = new FormData();
    Object.keys(formData.value).forEach(key => {
      submitData.append(key, formData.value[key]);
    });
    submitData.append('status', 'Pending'); // Reset status so admin can review again
    if (newFile.value) {
      submitData.append('attachment', newFile.value);
    }
    submitData.append('budget_items', JSON.stringify(budgetItems.value));
    submitData.set('proposed_budget', computedTotalBudget.value);

    const response = await api.post(`update-design/${id}`, submitData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    if (response.data.success) {
      Swal.fire({ icon: 'success', title: 'Resubmitted!', text: 'Activity Design updated and resubmitted successfully.', confirmButtonColor: '#b979cc' }).then(() => {
        router.push('/college/submitted-list');
      });
    } else {
      Swal.fire({ icon: 'error', title: 'Update Failed', text: response.data.message || 'Failed to update activity design.', confirmButtonColor: '#b979cc' });
    }
  } catch (err) {
    console.error('Error updating design:', err);
    const errorMsg = err.response?.data?.message || 'Failed to update activity design. Please check your network or server logs.';
    Swal.fire({ icon: 'error', title: 'Update Failed', text: errorMsg, confirmButtonColor: '#b979cc' });
  } finally {
    submitting.value = false;
  }
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'college') {
    router.push('/login');
  } else {
    fetchDesignDetails();
  }
});
</script>

<style scoped>
.main-viewport { flex: 1; overflow-y: auto; background: transparent; }
.loading-wrapper { display: flex; justify-content: center; align-items: center; min-height: 400px; }
.error-container { max-width: 48rem; margin: 0 auto; padding: 2.5rem 1.5rem; }
.error-box { background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 1rem; border-radius: 0 0.75rem 0.75rem 0; }
.error-title { color: #b91c1c; font-weight: 700; }
.error-message { color: #dc2626; font-size: 1.1rem; }
.error-back-btn { margin-top: 1rem; font-size: 1.1rem; font-weight: 700; color: #b91c1c; background: transparent; border: none; cursor: pointer; }

.page-container { min-height: 100vh; }
.layout-grid { display: flex; gap: 32px; padding: 2.5rem; max-width: 80rem; margin: 0 auto; }
.flex-06 { flex: 0.6; display: flex; flex-direction: column; overflow: hidden; }
.flex-04-sidebar { flex: 0.4; position: sticky; top: 20px; align-self: flex-start; }

.glass-card { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); backdrop-filter: blur(24px); border-radius: 1.5rem; border: 1px solid rgba(185, 121, 204, 0.2); }
.report-header { padding: 2rem; border-bottom: 1px solid rgba(185, 121, 204, 0.15); background: rgba(0, 0, 0, 0.2); }
.meta-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; }
.report-body { flex: 1; overflow-y: auto; padding: 2rem; }
.report-body > * + * { margin-top: 1.5rem; }

.status-badge-revision { display: inline-flex; align-items: center; gap: 8px; background-color: rgba(239, 68, 68, 0.15); color: #ef4444; padding: 4px 12px; border-radius: 9999px; border: 1px solid rgba(239, 68, 68, 0.3); }
.status-dot-pulse { width: 8px; height: 8px; background-color: #ef4444; border-radius: 9999px; animation: pulse 2s infinite; }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
.status-text { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; }

.control-number { font-size: 11px; font-weight: 700; color: #b979cc; text-transform: uppercase; margin-left: 12px; font-family: monospace; }
.form-group-top { margin-bottom: 1.5rem; }
.title-input { font-size: 1.5rem !important; font-weight: 700; }

.info-grid { display: flex; flex-wrap: wrap; gap: 24px; padding-top: 16px; border-top: 1px solid rgba(185, 121, 204, 0.1); }
.info-item { display: flex; flex-direction: column; }
.info-label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; color: #cbd5e1; font-weight: 700; margin-bottom: 4px; }
.info-value-white { font-size: 14px; font-weight: 600; color: white; }

.icon-pink { color: #b979cc; }
.full-width-info { grid-column: span 2; margin-top: 1rem; }

.section-card { background-color: rgba(0, 0, 0, 0.2); border-radius: 16px; padding: 24px; border: 1px solid rgba(185, 121, 204, 0.15); }
.section-header-row { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.section-title { font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; color: #b979cc; }

.grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
.metric-box-edit { background-color: rgba(0, 0, 0, 0.3); border-radius: 12px; padding: 16px; border: 1px solid rgba(185, 121, 204, 0.1); }

.doc-item { display: flex; align-items: center; justify-content: space-between; gap: 24px; padding: 16px; background: rgba(0, 0, 0, 0.3); border-radius: 12px; border: 1px solid rgba(185, 121, 204, 0.15); overflow-x: auto; }
.doc-info { display: flex; align-items: center; gap: 12px; }
.doc-pdf-icon { font-size: 1.875rem; color: #ef4444; }
.doc-title { font-size: 13px; font-weight: 700; color: white; white-space: nowrap; }
.doc-meta { font-size: 11px; color: #cbd5e1; margin-top: 2px; white-space: nowrap; }
.preview-btn { color: #b979cc; font-size: 11px; padding: 6px 12px; border-radius: 8px; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(185, 121, 204, 0.15); font-weight: 700; text-align: center; }
.preview-btn:hover { border-color: #b979cc; color: white; background: rgba(185, 121, 204, 0.1); }

.assessment-card-custom { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 1.5rem; padding: 2rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); border: 1px solid rgba(185, 121, 204, 0.2); }
.assessment-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid rgba(185, 121, 204, 0.15); }
.assessment-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; }
.assessment-title { font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #b979cc; }
.assessment-form { display: flex; flex-direction: column; }

.read-only-remarks { width: 100%; border: 1px solid rgba(185, 121, 204, 0.2); border-radius: 12px; padding: 14px 16px; font-size: 13px; background: rgba(0, 0, 0, 0.3); color: #cbd5e1; min-height: 100px; line-height: 1.5; }

.form-label { display: block; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #cbd5e1; letter-spacing: 1px; margin-bottom: 8px; }
.modal-input { width: 100%; padding: 12px 18px; border: 1px solid rgba(185, 121, 204, 0.2); background: rgba(0, 0, 0, 0.4); color: white; border-radius: 12px; font-size: 13px; }
.modal-input:focus { outline: none; border-color: #b979cc; }
.select-input { appearance: none; cursor: pointer; }

.action-buttons { display: flex; flex-direction: column; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(185, 121, 204, 0.15); }
.btn-approve { width: 100%; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); color: white; border: none; border-radius: 14px; padding: 14px; font-size: 12px; font-weight: 800; text-transform: uppercase; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; }
.btn-approve:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-approve:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(153, 13, 209, 0.25); }

.btn-back { display: block; width: 100%; padding: 12px; font-size: 11px; color: #cbd5e1; text-align: center; border-radius: 12px; background: transparent; border: 1px solid rgba(185, 121, 204, 0.15); margin-top: 8px; cursor: pointer; }
.btn-back:hover { color: white; border-color: #b979cc; background: rgba(185, 121, 204, 0.05); }

.loading-spinner { width: 40px; height: 40px; border: 3px solid #f3f3f3; border-top: 3px solid #990dd1; border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

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

.budget-input-wrapper {
  display: flex;
  align-items: center;
  background: rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(185, 121, 204, 0.2);
  border-radius: 8px;
  padding: 4px 12px;
  transition: border-color 0.2s;
}

.budget-input-wrapper:focus-within {
  border-color: #b979cc;
}

.currency-symbol {
  color: #b979cc;
  font-weight: 700;
  margin-right: 6px;
}

.budget-input {
  background: transparent;
  border: none;
  color: #ffffff;
  width: 100%;
  font-family: 'Courier New', Courier, monospace;
  font-size: 14px;
  font-weight: 600;
  outline: none;
  text-align: right;
}

.budget-input:focus {
  outline: none;
}

.code-icon-calendar::-webkit-calendar-picker-indicator,
.code-icon-clock::-webkit-calendar-picker-indicator {
  filter: invert(1);
  cursor: pointer;
  opacity: 0.7;
}

.code-icon-calendar::-webkit-calendar-picker-indicator:hover,
.code-icon-clock::-webkit-calendar-picker-indicator:hover {
  opacity: 1;
}

</style>
