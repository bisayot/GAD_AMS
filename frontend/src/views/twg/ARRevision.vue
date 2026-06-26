<template>
  <main class="main-viewport">
    <div v-if="loading" class="loading-wrapper">
      <div class="loading-spinner"></div>
      <p class="loading-text">Loading revision details...</p>
    </div>
    <div v-else class="page-container">
      <div class="layout-vertical">
        <!-- LEFT SECTION -->
        <section class="flex-full glass-card">
                    <div class="report-header">
            <div class="meta-header">
              <div class="status-badge-view" :class="getStatusClass(existingReport?.status)">
                <span class="status-text">{{ formatStatus(existingReport?.status) }}</span>
              </div>
              <span class="control-number">{{ existingReport?.control || 'NO CONTROL NUMBER' }}</span>
            </div>

            <h2 class="report-title">{{ existingReport?.activity_title || 'Revise Accomplishment Report' }}</h2>

            <div class="info-grid">
              <div class="info-item">
                <span class="info-label">Submitted By</span>
                <span class="info-value-purple">{{ existingReport?.submitter_name || '' }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Office</span>
                <span class="info-value-white">{{ existingReport?.office }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Date Submitted</span>
                <span class="info-value-white">{{ existingReport?.date || '---' }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Category</span>
                <span class="info-value-white">Accomplishment Report</span>
              </div>
            </div>
            <p class="text-sm-light mt-4">See the remarks below and apply the said changes.</p>
          </div>

          <div class="report-body">
            <form @submit.prevent="submitReport" class="ar-horizontal-layout w-full">
              
              <!-- Approved Activity Design Details -->
              <div class="section-card" v-if="existingReport && existingReport.activity_design">
                <div class="section-header-row">
                  <span class="material-symbols-outlined icon-pink">info</span>
                  <h3 class="section-title">Approved Activity Design Details</h3>
                </div>
                <div class="grid-2">
                  <div class="full-width-info">
                    <label class="info-label">Title</label>
                    <p class="text-sm-light mt-1">{{ existingReport.activity_design.activity_title }}</p>
                  </div>
                  <div>
                    <label class="info-label">Form Type</label>
                    <p class="text-sm-light mt-1">{{ existingReport.activity_design.form_type }}</p>
                  </div>
                  <div>
                    <label class="info-label">GPB/GAD ID</label>
                    <p class="text-sm-light mt-1">{{ existingReport.activity_design.gpb_id || 'N/A' }}</p>
                  </div>
                  <div>
                    <label class="info-label">Venue</label>
                    <p class="text-sm-light mt-1">{{ existingReport.activity_design.venue_name || existingReport.activity_design.venue }}</p>
                  </div>
                  <div>
                    <label class="info-label">Target Participants</label>
                    <p class="text-sm-light mt-1">{{ existingReport.activity_design.target_participants }}</p>
                  </div>
                  <div>
                    <label class="info-label">Date</label>
                    <p class="text-sm-light mt-1">{{ formatDate(existingReport.activity_design.start_date) }} to {{ formatDate(existingReport.activity_design.end_date) }}</p>
                  </div>
                  <div>
                    <label class="info-label">Time</label>
                    <p class="text-sm-light mt-1">{{ formatTime(existingReport.activity_design.start_time) }} to {{ formatTime(existingReport.activity_design.end_time) }}</p>
                  </div>
                  <div>
                    <label class="info-label">Proposed Budget</label>
                    <p class="text-sm-light mt-1">PHP {{ Number(existingReport.activity_design.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</p>
                  </div>
                  <div>
                    <label class="info-label">Assessment Date</label>
                    <p class="text-sm-light mt-1">{{ existingReport.activity_design.assessment_date ? formatDate(existingReport.activity_design.assessment_date) : 'N/A' }}</p>
                  </div>
                  <div class="full-width-info" v-if="existingReport.activity_design.remarks">
                    <label class="info-label">Reviewer Remarks</label>
                    <div class="read-only-remarks mt-1">{{ existingReport.activity_design.remarks }}</div>
                  </div>
                </div>

                <!-- AD Attachment -->
                <div v-if="existingReport.activity_design.attachment" class="doc-item mt-4">
                  <div class="doc-info">
                    <span class="material-symbols-outlined doc-pdf-icon">picture_as_pdf</span>
                    <div>
                      <p class="doc-title">Approved_Design.pdf</p>
                      <p class="doc-meta">Reference: {{ existingReport.activity_design.attachment }}</p>
                    </div>
                  </div>
                  <div class="doc-actions">
                    <button type="button" @click="previewFile(existingReport.activity_design.attachment, 'archived')" class="preview-btn">Preview</button>
                    <button type="button" @click="downloadFile(existingReport.activity_design.attachment, 'archived', 'Activity_Design')" class="download-btn-icon">
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
                          <td class="font-bold text-white text-right">{{ Number(existingReport.activity_design.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Actual Accomplishment Details (Editable) -->
              <div class="section-card">
                <div class="section-header-row">
                  <span class="material-symbols-outlined icon-pink">edit_document</span>
                  <h3 class="section-title">Actual Accomplishment Details</h3>
                </div>
                
                <div class="grid-2">
                  <div class="full-width-info input-group-ar">
                    <label class="form-label-ar">Actual Activity Title *</label>
                    <textarea 
                      v-model="form.activity_title" 
                      rows="2" 
                      class="custom-input-field textarea-no-resize"
                      placeholder="Enter the complete title of the activity"
                    ></textarea>
                  </div>

                  <div class="full-width-info input-group-ar" style="display: none;">
                    <label class="form-label-ar">Activity Design Control Number *</label>
                    <select v-model="form.control_number" class="custom-input-field select-arrow-fix">
                      <option value="" class="dark-option">Select approved activity design...</option>
                      <option v-for="control in approvedControls" :key="control.control_number" :value="control.control_number" class="dark-option">
                        {{ control.control_number }} - {{ control.activity_title }}
                      </option>
                    </select>
                  </div>

                  <div class="input-group-ar">
                    <label class="form-label-ar">Start Date of Implementation *</label>
                    <input type="date" v-model="form.start_date" class="custom-input-field code-icon-calendar">
                  </div>
                  <div class="input-group-ar">
                    <label class="form-label-ar">End Date of Implementation *</label>
                    <input type="date" v-model="form.end_date" class="custom-input-field code-icon-calendar">
                  </div>

                  <div class="input-group-ar">
                    <label class="form-label-ar">Start Time *</label>
                    <input type="time" v-model="form.start_time" class="custom-input-field code-icon-clock">
                  </div>
                  <div class="input-group-ar">
                    <label class="form-label-ar">End Time *</label>
                    <input type="time" v-model="form.end_time" class="custom-input-field code-icon-clock">
                  </div>

                  <div class="full-width-info input-group-ar">
                    <label class="form-label-ar">Venue *</label>
                    <input type="text" v-model="form.venue" class="custom-input-field" placeholder="e.g., Convention Center, Main Hall">
                  </div>

                  <div class="full-width-info input-group-ar">
                    <label class="form-label-ar">Number of Attendees *</label>
                    <input type="number" v-model="form.attendees" min="0" class="custom-input-field input-disabled-ar" placeholder="0" readonly>
                  </div>

                  <div class="input-group-ar">
                    <label class="form-label-ar">Male Participants *</label>
                    <input type="number" v-model="form.male" min="0" class="custom-input-field" placeholder="0">
                  </div>
                  <div class="input-group-ar">
                    <label class="form-label-ar">Female Participants *</label>
                    <input type="number" v-model="form.female" min="0" class="custom-input-field" placeholder="0">
                  </div>

                  <!-- Actual Budget Expenditure -->
                  <div class="full-width-info budget-section mt-4">
                    <label class="form-label-ar mb-2">Actual Budget Expenditure *</label>
                    <div class="budget-table-wrapper">
                      <table class="budget-table">
                        <thead class="budget-table-header">
                          <tr>
                            <th class="table-header-cell">Budget Item</th>
                            <th class="table-header-cell budget-col-total">Total</th>
                          </tr>
                        </thead>
                        <tbody class="budget-table-body">
                          <tr v-for="(item, index) in form.budget_items" :key="index" class="budget-table-row">
                            <td class="budget-item-name" v-html="formatBudgetName(item.name)"></td>
                            <td class="budget-item-input-cell">
                              <input type="number" v-model="item.total" class="budget-input-field budget-total-field" placeholder="0.00" min="0" step="0.01" />
                            </td>
                          </tr>
                        </tbody>
                        <tfoot class="budget-table-footer">
                          <tr>
                            <td colspan="1" class="grand-total-label">Grand Total (PHP)</td>
                            <td class="grand-total-value">{{ Number(form.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>

                  <!-- Evaluation Results -->
                  <div class="full-width-info evaluation-section-ar mt-4">
                    <label class="form-label-ar mb-2">Evaluation Results *</label>
                    <div class="evaluation-table-wrapper-ar">
                      <table class="evaluation-table-ar">
                        <thead class="evaluation-table-header-ar">
                          <tr>
                            <th class="table-header-cell">Area of Evaluation</th>
                            <th class="table-header-cell rating-col-ar">Average Rating</th>
                            <th class="table-header-cell interpretation-col-ar">Interpretation</th>
                          </tr>
                        </thead>
                        <tbody class="evaluation-table-body-ar">
                          <tr v-for="(item, index) in form.evaluation_items" :key="index">
                            <td class="evaluation-item-name-ar">{{ item.area }}</td>
                            <td class="evaluation-item-input-cell-ar">
                              <input type="number" v-model="item.rating" min="1" max="5" step="0.01" class="evaluation-input-field-ar" placeholder="0.00" />
                            </td>
                            <td class="evaluation-interpretation-cell-ar">
                              <span :class="['interpretation-tag-ar', getInterpretationClass(item.rating)]">{{ getInterpretation(item.rating) }}</span>
                            </td>
                          </tr>
                        </tbody>
                        <tfoot class="evaluation-table-footer-ar">
                          <tr>
                            <td class="total-avg-label-ar">Total Average Rating</td>
                            <td class="total-avg-value-ar">{{ form.rating }}</td>
                            <td class="total-avg-interpretation-ar">
                              <span :class="['interpretation-tag-ar', getInterpretationClass(form.rating)]">{{ getInterpretation(form.rating) }}</span>
                            </td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>

                  <!-- Attachments -->
                  <div class="full-width-info attachment-section-container-ar mt-4">
                    <label class="form-label-ar mb-2">Upload Documents (PDF/ZIP - Multiple files allowed) *</label>
                    <div class="attachment-display-grid-ar">
                      <div class="attachment-upload-column-ar">
                        <div class="upload-dropzone-box" @click="$refs.fileInput.click()">
                          <input ref="fileInput" type="file" @change="handleFileUpload" accept=".pdf" class="file-input-hidden" multiple />
                          <span class="upload-icon-ar">📤</span>
                          <p class="upload-text-ar">Upload Accomplishment Report & Attachments</p>
                          <p class="upload-hint-ar">Multiple files allowed (PDF, ZIP)</p>
                        </div>
                      </div>
                      <div class="attachment-preview-column-ar">
                        <div v-if="uploadedFiles.length > 0" class="uploaded-files-container-ar">
                          <div v-for="(file, index) in uploadedFiles" :key="index" class="uploaded-file-tag">
                            <span class="uploaded-file-name">📄 {{ file.name }}</span>
                            <div class="uploaded-file-actions-ar">
                              <span class="uploaded-file-size-ar">({{ (file.size / 1024).toFixed(2) }} KB)</span>
                              <button type="button" @click="removeFile(index)" class="remove-file-btn">Remove</button>
                            </div>
                          </div>
                        </div>
                        <div v-else-if="existingReport && existingReport.attachment" class="uploaded-files-container-ar">
                          <div class="uploaded-file-tag">
                            <span class="uploaded-file-name">📄 {{ existingReport.attachment }}</span>
                            <div class="uploaded-file-actions-ar">
                              <span class="uploaded-file-size-ar text-slate-400">Current Attachment</span>
                            </div>
                          </div>
                        </div>
                        <p v-else class="no-file-uploaded-text">No files uploaded yet.</p>
                      </div>
                    </div>
                  </div>

                  <!-- Submit button inside Actual Accomplishment Details right below attachments -->
                  <div class="full-width-info form-actions mt-4">
                    <button type="submit" class="submit-action-btn w-full">Resubmit Report &rarr;</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </section>

    <!-- Assessment Record Card -->
        <section class="flex-full mt-6" v-if="existingReport">
          <div class="assessment-card-custom">
            <div class="assessment-header">
              <div class="assessment-icon">📋</div>
              <div class="assessment-title">Assessment Record</div>
            </div>

            <div class="assessment-form">
              <div class="info-item">
                <span class="info-label">Assessor Remarks</span>
                <div class="read-only-remarks">
                  {{ existingReport.remarks || 'No remarks recorded for this accomplishment report.' }}
                </div>
              </div>

              <div class="action-buttons mt-4">
                <button @click="goBack" class="btn-back">
                  ← Back
                </button>
              </div>
            </div>
          </div>
        </section>

      </div>
    </div>
    
    <PdfPreviewModal :isOpen="isPdfModalOpen" :fileUrl="pdfFileUrl" @close="closePdfModal" />
  </main>
</template>


<script setup>
import PdfPreviewModal from '../../components/PdfPreviewModal.vue';
import { ref, onMounted, computed, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Swal from 'sweetalert2';
import api from '../../api';

const goBack = () => {
  router.back();
};

const router = useRouter();
const route = useRoute();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const menuItems = computed(() => {
  if (route.path.includes('/college')) return collegeMenu;
  return [];
});

const venues = ref([]);
const customVenue = ref('');

const loading = ref(true);
const form = ref({
  activity_title: '',
  control_number: '',
  act_design_id: null,
  start_date: '',
  end_date: '',
  start_time: '',
  end_time: '',
  venue: '',
  attendees: '',
  male: '',
  female: '', 
  proposed_budget: 0,
  budget_items: [
    { name: 'Meals and Snacks (AM/PM)', total: '' },
    { name: 'Function Room/Venue', total: '' },
    { name: 'Accommodation', total: '' },
    { name: 'Equipment Rental', total: '' },
    { name: 'Professional Fee/Honoria', total: '' },
    { name: 'Token/s', total: '' },
    { name: 'Materials and Supplies', total: '' },
    { name: 'Transportation', total: '' }
  ],
  evaluation_items: [
    { area: 'Time Management', rating: '' },
    { area: 'Orderliness and Program Flow', rating: '' },
    { area: 'Appropriateness of the Venue', rating: '' },
    { area: 'Sound System and Hall Preparation', rating: '' },
    { area: 'Restroom/s', rating: '' },
    { area: 'Food and Drinks', rating: '' }
  ],
  rating: 0
});

const approvedControls = ref([]);
const loadingControls = ref(false);

const fetchApprovedControls = async () => {
  loadingControls.value = true;
  try {
    // Fetch all values from the control_number table
    const res = await api.get(`approved-controls/${user.value.id}`);
    if (res.data.success) {
      approvedControls.value = res.data.data;
    }
  } catch (error) {
    console.error('Error fetching approved controls:', error);
        loading.value = false;
  } finally {
    loadingControls.value = false;
  }
};

watch(() => form.value.control_number, (newVal) => {
  const selected = approvedControls.value.find(c => c.control_number === newVal);
  if (selected) {
    form.value.act_design_id = selected.act_design_id;
    form.value.activity_title = selected.activity_title;
    form.value.start_date = selected.start_date;
    form.value.end_date = selected.end_date;
    form.value.start_time = selected.start_time;
    form.value.end_time = selected.end_time;
    form.value.venue = selected.venue;
  }
});

const formatBudgetName = (name) => {
  if (!name) return '';
  return name.replace(/(\(.*\))/g, '<span class="budget-item-subtext">$1</span>');
};

watch(() => form.value.budget_items, (newItems) => {
  const total = newItems.reduce((sum, item) => sum + (Number(item.total) || 0), 0);
  form.value.proposed_budget = total;
}, { deep: true });

const fetchVenues = async () => {
  try {
    const response = await api.get('venues');
    if (response.data && response.data.status === 'success') {
      venues.value = response.data.data || [];
    }
  } catch (error) {
    console.error('Error fetching venues:', error);
        loading.value = false;
  }
};

fetchVenues();

watch([() => form.value.male, () => form.value.female], ([newMale, newFemale]) => {
  const m = parseInt(newMale) || 0;
  const f = parseInt(newFemale) || 0;
  form.value.attendees = m + f;
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

watch(() => form.value.evaluation_items, (items) => {
  const valid = items.filter(i => i.rating !== '' && !isNaN(parseFloat(i.rating)));
  if (valid.length === 0) {
    form.value.rating = 0;
  } else {
    const sum = valid.reduce((acc, curr) => acc + parseFloat(curr.rating), 0);
    form.value.rating = (sum / items.length).toFixed(2);
  }
}, { deep: true });

const uploadedFiles = ref([]);
const fileInput = ref(null);

const handleFileUpload = (event) => {
  if (event.target.files.length > 0) {
    uploadedFiles.value = [...uploadedFiles.value, ...Array.from(event.target.files)];
  }
};

const removeFile = (index) => {
  uploadedFiles.value.splice(index, 1);
  if (uploadedFiles.value.length === 0 && fileInput.value) {
    fileInput.value.value = '';
  }
};

const submitReport = async () => {
  if (uploadedFiles.value.length === 0) {
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

  try {
    const formData = new FormData();
    
    formData.append('venue_id', form.value.venue);

    const budgetMap = {
      "Meals and Snacks (AM/PM)": "meals_and_snacks",
      "Function Room/Venue": "function_room_venue",
      "Accommodation": "accommodation",
      "Equipment Rental": "equipment_rental",
      "Professional Fee/Honoria": "professional_fee_honoria",
      "Token/s": "tokens",
      "Materials and Supplies": "materials_and_supplies",
      "Transportation": "transportation"
    };
    const budgetObj = {};
    form.value.budget_items.forEach(item => {
      const dbKey = budgetMap[item.name];
      if (dbKey) {
        budgetObj[dbKey] = Number(item.total) || 0;
      }
    });
    formData.append('budget_items', JSON.stringify(budgetObj));

    const evalMap = {
      "Time Management": "time_management",
      "Orderliness and Program Flow": "orderliness_and_program_flow",
      "Appropriateness of the Venue": "appropriateness_of_venue",
      "Sound System and Hall Preparation": "sound_system_and_hall_preparation",
      "Restroom/s": "restrooms",
      "Food and Drinks": "food_and_drinks"
    };
    const evalObj = {};
    form.value.evaluation_items.forEach(item => {
      const dbKey = evalMap[item.area];
      if (dbKey) {
        evalObj[dbKey] = Number(item.rating) || 0;
      }
    });
    formData.append('evaluation_results', JSON.stringify(evalObj));

    Object.keys(form.value).forEach(key => {
      if (key !== 'budget_items' && key !== 'evaluation_items' && key !== 'venue') {
        formData.append(key, form.value[key]);
      }
    });
    
    uploadedFiles.value.forEach(file => {
      formData.append('attachment', file);
    });
    
    formData.append('user_id', user.value.id);
    formData.append('status', 'Pending');
    
    const id = route.params.id;
    const response = await api.post(`update-report/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    
    if (response.data.success) {
      Swal.fire({
        icon: 'success',
        title: 'Submitted Successfully!',
        text: 'Accomplishment report submitted successfully!',
        confirmButtonColor: '#b979cc'
      }).then(() => {
        router.push('/college/submitted-list');
      });
      form.value = {
        activity_title: '',
        control_number: '',
        act_design_id: null,
        start_date: '',
        end_date: '',
        start_time: '',
        end_time: '',
        venue: '',
        attendees: '',
        male: '',
        female: '', 
        proposed_budget: 0,
        budget_items: [
          { name: 'Meals and Snacks (AM/PM)', total: '' },
          { name: 'Function Room/Venue', total: '' },
          { name: 'Accommodation', total: '' },
          { name: 'Equipment Rental', total: '' },
          { name: 'Professional Fee/Honoria', total: '' },
          { name: 'Token/s', total: '' },
          { name: 'Materials and Supplies', total: '' },
          { name: 'Transportation', total: '' }
        ],
        evaluation_items: [
          { area: 'Time Management', rating: '' },
          { area: 'Orderliness and Program Flow', rating: '' },
          { area: 'Appropriateness of the Venue', rating: '' },
          { area: 'Sound System and Hall Preparation', rating: '' },
          { area: 'Restroom/s', rating: '' },
          { area: 'Food and Drinks', rating: '' }
        ],
        rating: 0
      };
      uploadedFiles.value = [];
      if (fileInput.value) fileInput.value.value = '';
    }
  } catch (error) {
    console.error('Submission error:', error);
        loading.value = false;
    alert('Failed to submit report. Please try again.');
  }
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



const existingReport = ref(null);

const isPdfModalOpen = ref(false);
const pdfFileUrl = ref('');

const closePdfModal = () => {
  isPdfModalOpen.value = false;
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



const formatDate = (date) => date ? new Date(date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '---';

const formatTime = (time) => {
  if (!time) return '---';
  const [h, m] = time.split(':');
  return `${h % 12 || 12}:${m} ${h >= 12 ? 'PM' : 'AM'}`;
};

const parsedADBudget = computed(() => {
  if (!existingReport.value?.activity_design || !existingReport.value.activity_design.budget_items || existingReport.value.activity_design.budget_items.length === 0) return [];
  const b = existingReport.value.activity_design.budget_items[0];
  const items = [
    { name: 'Meals and Snacks (AM/PM)', total: b.meals_and_snacks },
    { name: 'Function Room/Venue', total: b.function_room_venue },
    { name: 'Accommodation', total: b.accommodation },
    { name: 'Equipment Rental', total: b.equipment_rental },
    { name: 'Professional Fee/Honoria', total: b.professional_fee_honoria },
    { name: 'Token/s', total: b.tokens },
    { name: 'Materials and Supplies', total: b.materials_and_supplies },
    { name: 'Transportation', total: b.transportation }
  ];
  return items.filter(i => parseFloat(i.total) > 0);
});

const fetchReportDetails = async () => {
  try {
    const id = route.params.id;
    const response = await api.get(`activity-report/${id}`);
    if (response.data.success) {
      existingReport.value = response.data.data;
      const r = response.data.data;
      
      form.value.activity_title = r.activity_title || '';
      form.value.start_date = r.start_date || '';
      form.value.end_date = r.end_date || '';
      form.value.start_time = r.start_time || '';
      form.value.end_time = r.end_time || '';
      form.value.venue = r.venue || '';

      form.value.attendees = r.number_of_attendees || r.attendees || '';
      form.value.male = r.male_participants || r.male || '';
      form.value.female = r.female_participants || r.female || '';
      
      // Populate budget_items from nested array
      const budgetMapping = {
        'Meals and Snacks (AM/PM)': 'meals_and_snacks',
        'Function Room/Venue': 'function_room_venue',
        'Accommodation': 'accommodation',
        'Equipment Rental': 'equipment_rental',
        'Professional Fee/Honoria': 'professional_fee_honoria',
        'Token/s': 'tokens',
        'Materials and Supplies': 'materials_and_supplies',
        'Transportation': 'transportation'
      };
      
      if (r.budget_items && Array.isArray(r.budget_items) && r.budget_items.length > 0) {
        const b = r.budget_items[0];
        form.value.budget_items.forEach(item => {
           if (budgetMapping[item.name]) {
              item.total = b[budgetMapping[item.name]] || '';
           }
        });
      }

      // Populate evaluation_items from nested array
      const evalMapping = {
        'Time Management': 'time_management',
        'Orderliness and Program Flow': 'orderliness_and_program_flow',
        'Appropriateness of the Venue': 'appropriateness_of_venue',
        'Sound System and Hall Preparation': 'sound_system_and_hall_preparation',
        'Restroom/s': 'restrooms',
        'Food and Drinks': 'food_and_drinks'
      };
      
      if (r.evaluation_results && Array.isArray(r.evaluation_results) && r.evaluation_results.length > 0) {
        const e = r.evaluation_results[0];
        form.value.evaluation_items.forEach(item => {
           if (evalMapping[item.area]) {
              item.rating = e[evalMapping[item.area]] || '';
           }
        });
        
        // Compute average rating
        const valid = form.value.evaluation_items.filter(i => i.rating !== '' && !isNaN(parseFloat(i.rating)));
        if (valid.length > 0) {
          const sum = valid.reduce((acc, curr) => acc + parseFloat(curr.rating), 0);
          form.value.rating = (sum / form.value.evaluation_items.length).toFixed(2);
        } else {
          form.value.rating = 0;
        }
      }
    }
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'college') {
    router.push('/login');
  } else {
    fetchReportDetails();
  }
});

</script>

<style scoped>
.twg-view-wrapper {
  flex: 1;
  overflow-y: auto;
  display: flex;
  background: transparent;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}

.main-content-container-ar {
  max-width: 1280px;
  margin-left: auto;
  margin-right: auto;
  width: 100%;
}

.form-header-ar {
  margin-bottom: 32px;
}

.form-main-title {
  font-size: 26px;
  font-weight: 800;
  letter-spacing: -0.025em;
  color: #16213e;
  letter-spacing: -0.02em;
}

.form-description-ar {
  font-size: 14px;
  color: #64748b;
  margin-top: 6px;
}

.form-container-box {
  background: linear-gradient(145deg, #1a1a2e 0%, #16213e 100%);
  border: 1px solid rgba(185, 121, 204, 0.2);
  border-radius: 20px;
  padding: 32px;
  box-shadow: 0 20px 40px rgba(10, 10, 20, 0.4);
}

.form-main-layout-ar {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.form-grid-main-ar {
  display: grid;
  grid-template-columns: repeat(1, minmax(0, 1fr));
  gap: 30px;
}
@media (min-width: 1024px) {
  .form-grid-main-ar {
    grid-template-columns: 1fr 1fr;
  }
}

.form-column-left-ar, .form-column-right-ar {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-column-left-ar {
    border-right: 1px solid rgba(185, 121, 204, 0.2);
    padding-right: 20px;  
}

.input-group-ar {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-label-ar {
  display: block;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #b979cc;
}

.custom-input-field {
  width: 100%;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 12px;
  padding: 14px 20px;
  font-size: 14px;
  color: #ffffff;
  transition: all 0.2s ease;
}

.custom-input-field:focus {
  background: rgba(255, 255, 255, 0.05);
  border-color: #b979cc;
  outline: none;
  box-shadow: 0 0 0 2px rgba(153, 13, 209, 0.2);
}

.custom-input-field::placeholder {
  color: #94a3b8;
}

.dark-option {
  background-color: #16213e;
  color: #ffffff;
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

.form-sub-grid-ar {
  display: grid;
  grid-template-columns: repeat(1, minmax(0, 1fr));
  gap: 24px;
}
@media (min-width: 768px) {
  .form-sub-grid-ar {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

.textarea-no-resize {
  resize: none;
}

.input-disabled-ar {
  cursor: not-allowed;
  opacity: 0.7;
}

.evaluation-section-ar {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.evaluation-table-wrapper-ar {
  overflow-x: auto;
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  background-color: rgba(0, 0, 0, 0.2);
}

.evaluation-table-ar {
  width: 100%;
  text-align: left;
  border-collapse: collapse;
}

.evaluation-table-header-ar {
  background-color: rgba(255, 255, 255, 0.05);
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #b979cc;
}

.evaluation-item-name-ar {
  padding: 12px 16px;
  color: #cbd5e1;
  font-size: 13px;
  line-height: 1.25;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.evaluation-item-input-cell-ar {
  padding: 8px 16px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.evaluation-input-field-ar {
  background-color: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 6px;
  outline: none;
  width: 80px;
  color: #ffffff;
  font-size: 14px;
  padding: 4px 8px;
  text-align: center;
}

.evaluation-input-field-ar:focus {
  border-color: #b979cc;
  background: rgba(255, 255, 255, 0.07);
}

.evaluation-interpretation-cell-ar {
  padding: 8px 16px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  font-size: 12px;
  font-weight: 600;
}

.evaluation-table-footer-ar {
  background-color: rgba(255, 255, 255, 0.05);
}

.total-avg-label-ar {
  padding: 12px 16px;
  font-size: 11px;
  font-weight: 700;
  color: #ffffff;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.total-avg-value-ar {
  padding: 12px 16px;
  font-size: 16px;
  font-weight: 800;
  color: #b979cc;
  text-align: center;
}

.total-avg-interpretation-ar {
  padding: 12px 16px;
  font-size: 13px;
  font-weight: 700;
}

.interpretation-tag-ar {
  padding: 2px 0;
}

.text-emerald-400 { color: #34d399; }
.text-teal-400 { color: #2dd4bf; }
.text-cyan-400 { color: #22d3ee; }
.text-amber-400 { color: #fbbf24; }
.text-rose-400 { color: #f472b6; }
.text-rose-500 { color: #f43f5e; }
.text-rose-600 { color: #e11d48; }

.budget-section {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.budget-table-wrapper {
  overflow-x: auto;
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  background-color: rgba(0, 0, 0, 0.2);
}

.budget-table {
  width: 100%;
  text-align: left;
  border-collapse: collapse;
}

.budget-table-header {
  background-color: rgba(255, 255, 255, 0.05);
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #b979cc;
}

.table-header-cell {
  padding: 10px 16px;
  font-weight: 600;
}

.budget-col-total {
  width: 128px;
}

.budget-table-body {
  border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.budget-item-name {
  padding: 12px 16px;
  color: #cbd5e1;
  line-height: 1.25;
}

.budget-item-input-cell {
  padding: 8px 16px;
}

.budget-input-field {
  background-color: transparent;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  outline: none;
  width: 100%;
  color: #ffffff;
  font-size: 14px;
  padding-top: 4px;
  padding-bottom: 4px;
}
.budget-input-field:focus {
  border-color: #b979cc;
}

.budget-total-field {
  font-weight: 600;
}

.budget-table-footer {
  background-color: rgba(255, 255, 255, 0.05);
}

.grand-total-label {
  padding: 12px 16px;
  font-size: 10px;
  font-weight: 700;
  color: #ffffff;
  text-align: right;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.grand-total-value {
  padding: 12px 16px;
  font-size: 14px;
  font-weight: 700;
  color: #b979cc;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.file-input-hidden {
  display: none;
}

.upload-dropzone-box {
  border: 2px dashed rgba(185, 121, 204, 0.3);
  background: rgba(185, 121, 204, 0.02);
  border-radius: 14px;
  padding: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.upload-icon-ar {
  font-size: 26px;
  margin-bottom: 8px;
  transition: transform 0.2s ease;
}
.upload-dropzone-box:hover .upload-icon-ar {
  transform: scale(1.1);
}

.upload-text-ar {
  font-size: 14px;
  font-weight: 600;
  color: #ffffff;
  text-align: center;
  transition: color 0.2s ease;
}
.upload-dropzone-box:hover .upload-text-ar {
  color: #b979cc;
}

.upload-hint-ar {
  font-size: 14px;
  color: #64748b;
  margin-top: 4px;
}

.uploaded-files-container-ar {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.upload-dropzone-box:hover {
  border-color: #b979cc;
  background: rgba(185, 121, 204, 0.06);
}

.uploaded-file-tag {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.08);
  padding: 8px 14px;
  border-radius: 8px;
  color: #cbd5e1;
  font-size: 12px;
  width: 100%;
}

.uploaded-file-size-ar {
  font-size: 14px;
  opacity: 0.6;
  flex-shrink: 0;
}

.submit-action-btn {
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
  color: #ffffff;
  padding: 14px 40px;
  border-radius: 12px;
  font-weight: 700;
  font-size: 16px;
  cursor: pointer;
  border: none;
  box-shadow: 0 4px 14px rgba(153, 13, 209, 0.3);
  transition: all 0.3s ease;
}

.submit-action-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(153, 13, 209, 0.45);
  background: linear-gradient(135deg, #b979cc 0%, #990dd1 100%);
}

.form-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 24px;
}

.back-button {
  padding: 12px 24px;
  font-size: 14px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #b979cc;
  border-radius: 12px;
  transition: all 0.2s ease;
}
.back-button:hover {
  background-color: rgba(255, 255, 255, 0.05);
}


.revision-remarks-glass-banner {
  position: relative;
  background: #ffffff;
  border: 2px solid #7e22ce;
  border-radius: 16px;
  padding: 24px;
  margin-bottom: 28px;
  display: flex;
  align-items: flex-start;
  gap: 20px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(126, 34, 206, 0.15);
}
.remarks-glass-glow {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: radial-gradient(circle at top right, rgba(126, 34, 206, 0.1), transparent 60%);
  pointer-events: none;
}
.remarks-glass-icon-wrapper {
  background: linear-gradient(135deg, #7e22ce 0%, #4c1d95 100%);
  border-radius: 12px;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(126, 34, 206, 0.3);
  flex-shrink: 0;
  z-index: 1;
}
.remarks-glass-icon { color: white; font-size: 28px; font-weight: 800; }
.remarks-glass-content { z-index: 1; flex: 1; }
.remarks-glass-title { color: #4c1d95; font-size: 14px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; }
.remarks-glass-text { color: #000000; font-size: 15px; line-height: 1.6; font-weight: 600; }




.main-viewport { flex: 1; overflow-y: auto; background: transparent; }
.page-container { min-height: 100vh; padding: 2rem; max-width: 80rem; margin: 0 auto; }
.glass-card { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); backdrop-filter: blur(24px); border-radius: 1.5rem; border: 1px solid rgba(185, 121, 204, 0.2); }
.layout-vertical { display: flex; flex-direction: column; gap: 24px; }
.flex-full { flex: 1; width: 100%; }

.report-header { padding: 2rem; border-bottom: 1px solid rgba(185, 121, 204, 0.15); background: rgba(0, 0, 0, 0.2); border-radius: 1.5rem 1.5rem 0 0; }
.report-title { font-size: 26px; color: white; line-height: 1.25; margin: 0; }
.text-sm-light { font-size: 1.1rem; color: #cbd5e1; font-weight: 500; }

.report-body { padding: 2rem; }
.section-card { background: rgba(0, 0, 0, 0.2); border-radius: 16px; padding: 24px; border: 1px solid rgba(185, 121, 204, 0.15); }
.section-header-row { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.section-title { font-weight: 800; font-size: 13px; text-transform: uppercase; color: #b979cc; }
.icon-pink { color: #b979cc; }

.grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
.full-width-info { grid-column: 1 / -1; }
.info-label { font-size: 10px; text-transform: uppercase; color: #cbd5e1; font-weight: 700; margin-bottom: 4px; display: block; }
.mt-1 { margin-top: 0.25rem; }
.mt-4 { margin-top: 1.5rem; }
.mb-2 { margin-bottom: 0.5rem; }
.w-full { width: 100%; }

.table-responsive { overflow-x: auto; border-radius: 12px; border: 1px solid rgba(185, 121, 204, 0.15); background: rgba(0, 0, 0, 0.2); }
.custom-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.custom-table th { background: rgba(185, 121, 204, 0.1); color: #b979cc; font-weight: 700; text-transform: uppercase; padding: 12px 16px; text-align: left; border-bottom: 1px solid rgba(185, 121, 204, 0.15); }
.custom-table td { padding: 12px 16px; color: #cbd5e1; border-bottom: 1px solid rgba(185, 121, 204, 0.05); }
.custom-table tbody tr:last-child td { border-bottom: none; }
.custom-table tfoot td { border-top: 1px solid rgba(185, 121, 204, 0.15); background: rgba(0, 0, 0, 0.3); }
.text-right { text-align: right; }
.font-bold { font-weight: 700; }
.text-white { color: white; }

.doc-item { display: flex; align-items: center; justify-content: space-between; gap: 24px; padding: 16px; background: rgba(0, 0, 0, 0.3); border-radius: 12px; border: 1px solid rgba(185, 121, 204, 0.15); overflow-x: auto; }
.doc-info { display: flex; align-items: center; gap: 12px; }
.doc-pdf-icon { font-size: 1.875rem; color: #ef4444; }
.doc-title { font-size: 13px; font-weight: 700; color: white; white-space: nowrap; }
.doc-meta { font-size: 11px; color: #cbd5e1; margin-top: 2px; white-space: nowrap; }
.preview-btn { color: #b979cc; font-size: 11px; padding: 6px 16px; border-radius: 8px; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(185, 121, 204, 0.15); font-weight: 700; cursor: pointer; transition: all 0.2s; }
.preview-btn:hover { border-color: #b979cc; color: white; background: rgba(185, 121, 204, 0.1); }
.download-btn-icon { background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(185, 121, 204, 0.15); color: #cbd5e1; padding: 6px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.download-btn-icon:hover { border-color: #b979cc; color: white; }

.ar-horizontal-layout { display: flex; flex-direction: column; gap: 24px; }
@media (min-width: 1280px) {
  .ar-horizontal-layout { flex-direction: row; align-items: flex-start; }
  .ar-horizontal-layout > .section-card { flex: 1; width: 50%; margin-bottom: 0; }
}
@media (max-width: 768px) {
  .grid-2 { grid-template-columns: 1fr !important; }
}

.assessment-card-custom { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 1.5rem; padding: 2rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); border: 1px solid rgba(185, 121, 204, 0.2); }
.assessment-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid rgba(185, 121, 204, 0.15); }
.assessment-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; }
.assessment-title { font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #b979cc; }
.assessment-form { display: flex; flex-direction: column; }

.read-only-remarks { width: 100%; border: 1px solid rgba(185, 121, 204, 0.2); border-radius: 12px; padding: 14px 16px; font-size: 13px; background: rgba(0, 0, 0, 0.3); color: #cbd5e1; min-height: 100px; line-height: 1.5; }
.action-buttons { margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(185, 121, 204, 0.15); }
.btn-back { width: 100%; padding: 12px; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #cbd5e1; border-radius: 12px; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(185, 121, 204, 0.15); cursor: pointer; transition: all 0.2s; }
.btn-back:hover { color: white; border-color: #b979cc; background: rgba(185, 121, 204, 0.05); }

/* Overriding original button styling inside right card to match */
.submit-action-btn { background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); color: white; font-weight: 700; font-size: 13px; padding: 14px 24px; border-radius: 12px; border: none; cursor: pointer; transition: opacity 0.2s; text-align: center; }
.submit-action-btn:hover { opacity: 0.9; }



.status-badge-view { padding: 4px 12px; border-radius: 9999px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; }
.status-badge-view.completed { background: rgba(34, 197, 94, 0.15); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); }
.status-badge-view.cancelled { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
.status-badge-view.pending { background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); }
.status-badge-view.approved { background: rgba(59, 130, 246, 0.15); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.3); }
.status-badge-view.revision { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
.control-number { font-size: 11px; font-weight: 700; color: #b979cc; text-transform: uppercase; margin-left: 12px; font-family: monospace; }
.meta-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.info-grid { display: flex; flex-wrap: wrap; gap: 24px; padding-top: 16px; border-top: 1px solid rgba(185, 121, 204, 0.1); }
.info-item { display: flex; flex-direction: column; }
.info-value-white { font-size: 14px; font-weight: 600; color: white; }
.info-value-purple { font-size: 14px; font-weight: 600; color: #b979cc; }

.loading-wrapper { display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 400px; }
.loading-spinner { border: 4px solid rgba(255, 255, 255, 0.1); border-left-color: var(--primary-pink); border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin-bottom: 1rem; }
.loading-text { color: var(--text-light); font-size: 1rem; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>
