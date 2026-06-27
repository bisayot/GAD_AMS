<template>
  <main class="main-viewport">
    <div v-if="loading" class="loading-wrapper">
      <div class="loading-spinner"></div>
    </div>

    <div v-else-if="error" class="error-view-wrapper">
      <div class="error-card">
        <div class="error-glow"></div>
        <div class="error-icon-container">
          <span class="material-symbols-outlined error-icon" v-if="error.includes('Access Denied')">gpp_bad</span>
          <span class="material-symbols-outlined error-icon" v-else>error</span>
        </div>
        <h2 class="error-heading">
          {{ error.includes('Access Denied') ? 'Access Restricted' : 'Error Loading Data' }}
        </h2>
        <p class="error-text">{{ error }}</p>
        <button @click="router.back()" class="error-btn-red">
          <span class="material-symbols-outlined btn-icon">arrow_back</span>
          Go Back
        </button>
      </div>
    </div>

    <div v-else class="page-container">
      <div class="layout-grid">

        <section class="flex-06 glass-card">
          <div class="report-header">
            <div class="meta-header">
              <div class="status-badge-wrapper">
                <div class="status-badge-revision">
                  <div class="status-dot-pulse"></div>
                  <span class="status-text">Revision Mode</span>
                </div>
                <span class="control-number">{{ report.control_number || 'PENDING ASSIGNMENT' }}</span>
              </div>
              <div class="header-meta-group">
                <div class="meta-tag">
                  <span class="info-label header-label">Category:</span>
                  <span class="info-value-white uppercase">Accomplishment Report</span>
                </div>
              </div>
            </div>

            <div class="form-group-top">
              <label class="form-label">Activity Title</label>
              <textarea 
                v-model="formData.activity_title" 
                type="text" 
                class="modal-input title-input" 
                placeholder="Enter Activity Title"
              ></textarea>
            </div>

            <div class="info-grid">
              <div class="info-item">
                <span class="info-label">Submitted By</span>
                <span class="info-value-white">{{ formData.email || '---' }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Office / Unit</span>
                <span class="info-value-white">{{ formData.office }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Form Type</span>
                <span class="info-value-white uppercase">{{ formatFormType(formData.form_type) }}</span>
              </div>
            </div>
          </div>

          <div class="report-body">

            <div class="section-card">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">event_available</span>
                <h3 class="section-title">Implementation Details</h3>
              </div>
              <div class="grid-2">
                <div>
                  <label class="info-label">Start Date</label>
                  <input v-model="formData.start_date" type="date" class="edit-input">
                </div>
                <div>
                  <label class="info-label">End Date</label>
                  <input v-model="formData.end_date" type="date" class="edit-input">
                </div>
                <div>
                  <label class="info-label">Start Time</label>
                  <input v-model="formData.start_time" type="time" class="edit-input">
                </div>
                <div>
                  <label class="info-label">End Time</label>
                  <input v-model="formData.end_time" type="time" class="edit-input">
                </div>
                <div class="full-width-info">
                  <label class="info-label">Venue</label>
                  <input v-model="formData.venue" type="text" class="edit-input" placeholder="Enter Venue">
                </div>
              </div>

              <div class="participants-summary mt-4 pt-4 border-t border-white/10">
                <label class="info-label mb-3 block">Participation Breakdown</label>
                <div class="grid-3">
                  <div class="metric-box">
                    <input v-model="formData.male" type="number" class="edit-input text-center" min="0">
                    <span class="metric-label">Male</span>
                  </div>
                  <div class="metric-box">
                    <input v-model="formData.female" type="number" class="edit-input text-center" min="0">
                    <span class="metric-label">Female</span>
                  </div>
                  <div class="metric-box total-highlight">
                    <span class="metric-value">{{ formData.attendees }}</span>
                    <span class="metric-label">Total Attendees</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Budget Section -->
            <div class="section-card">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">payments</span>
                <h3 class="section-title">Actual Budgetary Requirements</h3>
              </div>
              <!-- Grouped Budget Divisions -->
              <div class="budget-groups-container">
                
                <!-- Group 1: Catering & Hospitality -->
                <div class="budget-group-card">
                  <div class="budget-group-header">
                    <span class="budget-group-icon">🍽️</span>
                    <span class="budget-group-title">Catering & Hospitality</span>
                  </div>
                  <div class="budget-group-content">
                    <!-- Meals Row -->
                    <div class="budget-row-item">
                      <div class="budget-item-info">
                        <div class="budget-item-title">Meals</div>
                      </div>
                      <div class="budget-item-value">
                        <span class="budget-currency-symbol">₱</span>
                        <input 
                          type="number" 
                          v-model="formData.budget_items[0].total" 
                          class="budget-card-input"
                          placeholder="0.00"
                          min="0"
                          step="0.01"
                        />
                      </div>
                    </div>

                    <!-- Snacks Row -->
                    <div class="budget-row-item">
                      <div class="budget-item-info">
                        <div class="budget-item-title">Snacks</div>
                      </div>
                      <div class="budget-item-value">
                        <span class="budget-currency-symbol">₱</span>
                        <input 
                          type="number" 
                          v-model="formData.budget_items[1].total" 
                          class="budget-card-input"
                          placeholder="0.00"
                          min="0"
                          step="0.01"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Group 2: Venue & Logistics -->
                <div class="budget-group-card">
                  <div class="budget-group-header">
                    <span class="budget-group-icon">🏨</span>
                    <span class="budget-group-title">Venue & Logistics</span>
                  </div>
                  <div class="budget-group-content">
                    <!-- Function Room/Venue -->
                    <div class="budget-row-item">
                      <div class="budget-item-info">
                        <div class="budget-item-title">Function Room/Venue</div>
                      </div>
                      <div class="budget-item-value">
                        <span class="budget-currency-symbol">₱</span>
                        <input 
                          type="number" 
                          v-model="formData.budget_items[2].total" 
                          class="budget-card-input"
                          placeholder="0.00"
                          min="0"
                          step="0.01"
                        />
                      </div>
                    </div>

                    <!-- Accommodation -->
                    <div class="budget-row-item">
                      <div class="budget-item-info">
                        <div class="budget-item-title">Accommodation</div>
                      </div>
                      <div class="budget-item-value">
                        <span class="budget-currency-symbol">₱</span>
                        <input 
                          type="number" 
                          v-model="formData.budget_items[3].total" 
                          class="budget-card-input"
                          placeholder="0.00"
                          min="0"
                          step="0.01"
                        />
                      </div>
                    </div>

                    <!-- Equipment Rental -->
                    <div class="budget-row-item">
                      <div class="budget-item-info">
                        <div class="budget-item-title">Equipment Rental</div>
                      </div>
                      <div class="budget-item-value">
                        <span class="budget-currency-symbol">₱</span>
                        <input 
                          type="number" 
                          v-model="formData.budget_items[4].total" 
                          class="budget-card-input"
                          placeholder="0.00"
                          min="0"
                          step="0.01"
                        />
                      </div>
                    </div>

                    <!-- Transportation -->
                    <div class="budget-row-item">
                      <div class="budget-item-info">
                        <div class="budget-item-title">Transportation</div>
                      </div>
                      <div class="budget-item-value">
                        <span class="budget-currency-symbol">₱</span>
                        <input 
                          type="number" 
                          v-model="formData.budget_items[8].total" 
                          class="budget-card-input"
                          placeholder="0.00"
                          min="0"
                          step="0.01"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Group 3: Program & Speakers -->
                <div class="budget-group-card">
                  <div class="budget-group-header">
                    <span class="budget-group-icon">🎓</span>
                    <span class="budget-group-title">Program & Speakers</span>
                  </div>
                  <div class="budget-group-content">
                    <!-- Professional Fee/Honoraria -->
                    <div class="budget-row-item">
                      <div class="budget-item-info">
                        <div class="budget-item-title">Professional Fee/Honoraria</div>
                      </div>
                      <div class="budget-item-value">
                        <span class="budget-currency-symbol">₱</span>
                        <input 
                          type="number" 
                          v-model="formData.budget_items[5].total" 
                          class="budget-card-input"
                          placeholder="0.00"
                          min="0"
                          step="0.01"
                        />
                      </div>
                    </div>

                    <!-- Token/s -->
                    <div class="budget-row-item">
                      <div class="budget-item-info">
                        <div class="budget-item-title">Token/s</div>
                      </div>
                      <div class="budget-item-value">
                        <span class="budget-currency-symbol">₱</span>
                        <input 
                          type="number" 
                          v-model="formData.budget_items[6].total" 
                          class="budget-card-input"
                          placeholder="0.00"
                          min="0"
                          step="0.01"
                        />
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Group 4: Materials & Miscellaneous -->
                <div class="budget-group-card">
                  <div class="budget-group-header">
                    <span class="budget-group-icon">📦</span>
                    <span class="budget-group-title">Materials & Miscellaneous</span>
                  </div>
                  <div class="budget-group-content">
                    <!-- Materials and Supplies -->
                    <div class="budget-row-item">
                      <div class="budget-item-info">
                        <div class="budget-item-title">Materials and Supplies</div>
                      </div>
                      <div class="budget-item-value">
                        <span class="budget-currency-symbol">₱</span>
                        <input 
                          type="number" 
                          v-model="formData.budget_items[7].total" 
                          class="budget-card-input"
                          placeholder="0.00"
                          min="0"
                          step="0.01"
                        />
                      </div>
                    </div>

                    <!-- Others -->
                    <div class="others-section-wrapper">
                      <div class="budget-row-item others-row-item-header" style="border-bottom: none; padding-bottom: 8px;">
                        <div class="budget-item-info">
                          <div class="budget-item-title">Others</div>
                        </div>
                        <div class="budget-item-value">
                          <span class="others-total-badge">₱{{ Number(formData.budget_items[9].total || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</span>
                        </div>
                      </div>
                      <div class="others-breakdown-container">
                        <div v-for="(o, oIdx) in othersList" :key="oIdx" class="others-breakdown-row">
                          <input type="text" v-model="o.name" placeholder="Item name" class="others-input-name" />
                          <input type="number" v-model.number="o.amount" min="0" placeholder="₱0.00" class="others-input-amount" />
                          <button type="button" @click="removeOtherItem(oIdx)" class="btn-remove-other" title="Remove">×</button>
                        </div>
                        <button type="button" @click="addOtherItem" class="btn-add-other" style="width: 100%; justify-content: center;">
                          <span>+</span> Add Item
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

              <!-- Grand Total Banner Card -->
              <div class="grand-total-banner-card">
                <div class="grand-total-label-banner">Actual Total Expenditures</div>
                <div class="grand-total-value-banner">
                  ₱{{ formatCurrency(actualTotal) }}
                </div>
              </div>
            </div>

            <div class="section-card">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">analytics</span>
                <h3 class="section-title">Evaluation Results</h3>
              </div>
              <div class="budget-table-wrapper">
                <table class="budget-table">
                  <thead class="budget-table-header">
                    <tr>
                      <th class="table-header-cell">Area of Evaluation</th>
                      <th class="table-header-cell text-center">Rating</th>
                      <th class="table-header-cell text-right">Interpretation</th>
                    </tr>
                  </thead>
                  <tbody class="budget-table-body">
                    <tr v-for="(item, idx) in formData.evaluation_items" :key="idx" class="budget-table-row">
                      <td class="budget-item-name">{{ item.area }}</td>
                      <td class="budget-item-value-cell text-center">
                        <input 
                          v-model="item.rating" 
                          type="number" 
                          class="edit-input text-center rating-edit" 
                          step="0.01" 
                          min="1" 
                          max="5"
                        >
                      </td>
                      <td class="budget-item-value-cell text-right">
                        <span :class="getInterpretationClass(item.rating)">{{ getInterpretation(item.rating) }}</span>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot class="budget-table-footer">
                    <tr>
                      <td class="grand-total-label">Total Average Rating</td>
                      <td class="text-center font-black text-white text-lg">{{ formData.rating }}</td>
                      <td class="text-right">
                        <span class="font-bold" :class="getInterpretationClass(formData.rating)">
                          {{ getInterpretation(formData.rating) }}
                        </span>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

            <div class="section-card">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">description</span>
                <h3 class="section-title">Manage Documents</h3>
              </div>
              
              <div class="doc-list-edit">
                <div v-for="(file, index) in existingFiles" :key="'existing-' + index" class="doc-item-edit">
                  <div class="doc-info">
                    <span class="material-symbols-outlined doc-pdf-icon">picture_as_pdf</span>
                    <div>
                      <p class="doc-title">{{ file }}</p>
                      <p class="doc-meta">Current Attachment</p>
                    </div>
                  </div>
                  <button type="button" @click="removeExistingFile(index)" class="remove-btn">Remove</button>
                </div>

                <div v-for="(file, index) in newFiles" :key="'new-' + index" class="doc-item-edit">
                  <div class="doc-info">
                    <span class="material-symbols-outlined doc-pdf-icon">picture_as_pdf</span>
                    <div>
                      <p class="doc-title">{{ file.name }}</p>
                      <p class="doc-meta">New Upload</p>
                    </div>
                  </div>
                  <button type="button" @click="removeNewFile(index)" class="remove-btn">Remove</button>
                </div>

                <label class="preview-btn cursor-pointer">
                  <span>+ Upload File</span>
                  <input type="file" @change="handleFileChange" class="hidden" accept=".pdf" multiple>
                </label>
              </div>
            </div>
          </div>
        </section>

        <section class="flex-04-sidebar">
          <div class="assessment-card-custom">
            <div class="assessment-header">
              <div class="assessment-icon">📋</div>
              <div class="assessment-title">Report Assessment</div>
            </div>

            <div class="assessment-form">
              <div class="info-item assessment-field">
                <span class="info-label">Date of Assessment</span>
                <span class="info-value-white">{{ formatDate(report.assessment_date) || '---' }}</span>
              </div>

              <div class="info-item assessment-field">
                <span class="info-label">Reviewer Remarks</span>
                <div class="read-only-remarks">
                  {{ report.remarks || 'No remarks provided.' }}
                </div>
              </div>

              <!-- Budget Exceeded Warning Card -->
              <div v-if="isExceedingLimit" class="ar-limit-warning-card">
                <span class="warning-icon">⚠️</span>
                <div class="warning-content">
                  <h4 class="warning-title">Budget Limit Exceeded</h4>
                  <p class="warning-desc">
                    The actual spending grand total of <strong>₱{{ Number(actualTotal || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</strong> exceeds the approved proposed budget of <strong>₱{{ Number(selectedProposedBudget || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</strong>.
                  </p>
                  <p class="warning-instruction">
                    Please file an Activity Design Revision first to increase the budget before submitting this report.
                  </p>
                </div>
              </div>

              <div class="action-buttons">
                <button @click="handleUpdate" class="btn-approve" :disabled="submitting || isExceedingLimit" :class="{ 'btn-disabled': isExceedingLimit }">
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
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../api';
import Swal from 'sweetalert2';

const route = useRoute();
const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const report = ref({});
const loading = ref(true);
const submitting = ref(false);
const error = ref(null);
const existingFiles = ref([]);
const newFiles = ref([]);

const formData = ref({
  activity_title: '',
  office: '',
  form_type: '',
  email: '',
  start_date: '',
  end_date: '',
  start_time: '',
  end_time: '',
  venue: '',
  male: 0,
  female: 0,
  attendees: 0,
  budget_items: [ // Added budget_items
    { name: 'Meals', total: 0 },
    { name: 'Snacks', total: 0 },
    { name: 'Function Room/Venue', total: 0 },
    { name: 'Accommodation', total: 0 },
    { name: 'Equipment Rental', total: 0 },
    { name: 'Professional Fee/Honoria', total: 0 },
    { name: 'Token/s', total: 0 },
    { name: 'Materials and Supplies', total: 0 },
    { name: 'Transportation', total: 0 },
    { name: 'Others', total: 0 }
  ],
  evaluation_items: [
    { area: 'Time Management', rating: 0 },
    { area: 'Orderliness and Program Flow', rating: 0 },
    { area: 'Appropriateness of the Venue', rating: 0 },
    { area: 'Sound System and Hall Preparation', rating: 0 },
    { area: 'Restroom/s', rating: 0 },
    { area: 'Food and Drinks', rating: 0 }
  ],
  rating: 0
});

// Reactive Others State
const othersList = ref([]);
const addOtherItem = () => {
  othersList.value.push({ name: '', amount: '' });
};
const removeOtherItem = (index) => {
  othersList.value.splice(index, 1);
};

watch(
  othersList,
  (newList) => {
    const item = formData.value.budget_items.find(i => i.name === 'Others');
    if (item) {
      const sum = newList.reduce((sum, i) => sum + (Number(i.amount) || 0), 0);
      item.total = sum || '';
    }
  },
  { deep: true }
);

// Populate others list on load if others total exists
watch(() => formData.value.budget_items, (newVal) => {
  if (othersList.value.length === 0 && formData.value.budget_items[9]?.total > 0) {
    othersList.value = [{ name: 'Other Items', amount: formData.value.budget_items[9].total }];
  }
}, { once: true });

const selectedProposedBudget = ref(0);

const isExceedingLimit = computed(() => {
  return selectedProposedBudget.value > 0 && actualTotal.value > selectedProposedBudget.value;
});

const fetchReportDetails = async () => {
  loading.value = true;
  try {
    const id = route.params.id;
    const response = await api.get(`activity-report/${id}`);
    if (response.data.success) {
      const fetchedReport = response.data.data;
      const currentUserId = user.value.id || user.value.user_id;

      if (Number(fetchedReport.user_id) !== Number(currentUserId)) {
        error.value = "Access Denied: You can only revise reports that you have submitted.";
        return;
      }

      report.value = fetchedReport;
      selectedProposedBudget.value = Number(fetchedReport.proposed_budget_limit) || 0;
      // Initialize default structures
      othersList.value = [];

      let mealsTotal = 0;
      let snacksTotal = 0;
      let functionRoomVenue = '';
      let accommodation = '';
      let equipmentRental = '';
      let professionalFee = '';
      let tokensVal = '';
      let materialsSupplies = '';
      let transportation = '';
      let othersTotal = 0;

      if (fetchedReport.budget_items && fetchedReport.budget_items.length > 0) {
        fetchedReport.budget_items.forEach(bi => {
          const amt = Number(bi.amount) || 0;
          if (bi.item_name === 'Meals') {
            mealsTotal += amt;
          } else if (bi.item_name === 'Snacks') {
            snacksTotal += amt;
          } else if (bi.item_name === 'Function Room/Venue') {
            functionRoomVenue = amt || '';
          } else if (bi.item_name === 'Accommodation') {
            accommodation = amt || '';
          } else if (bi.item_name === 'Equipment Rental') {
            equipmentRental = amt || '';
          } else if (bi.item_name === 'Professional Fee/Honoria') {
            professionalFee = amt || '';
          } else if (bi.item_name === 'Token/s') {
            tokensVal = amt || '';
          } else if (bi.item_name === 'Materials and Supplies') {
            materialsSupplies = amt || '';
          } else if (bi.item_name === 'Transportation') {
            transportation = amt || '';
          } else if (bi.item_name === 'Others') {
            othersTotal += amt;
            if (bi.sub_item) {
              othersList.value.push({ name: bi.sub_item, amount: amt });
            }
          }
        });
      }

      formData.value = {
        activity_title: fetchedReport.activity_title,
        office: fetchedReport.office,
        form_type: fetchedReport.form_type,
        email: fetchedReport.email,
        start_date: fetchedReport.start_date,
        end_date: fetchedReport.end_date,
        start_time: fetchedReport.start_time,
        end_time: fetchedReport.end_time,
        venue: fetchedReport.venue,
        male: fetchedReport.male,
        female: fetchedReport.female,
        attendees: fetchedReport.attendees,
        budget_items: [
          { name: 'Meals', total: mealsTotal || '' },
          { name: 'Snacks', total: snacksTotal || '' },
          { name: 'Function Room/Venue', total: functionRoomVenue },
          { name: 'Accommodation', total: accommodation },
          { name: 'Equipment Rental', total: equipmentRental },
          { name: 'Professional Fee/Honoria', total: professionalFee },
          { name: 'Token/s', total: tokensVal },
          { name: 'Materials and Supplies', total: materialsSupplies },
          { name: 'Transportation', total: transportation },
          { name: 'Others', total: othersTotal || '' }
        ],
        evaluation_items: [
          { area: 'Time Management', rating: fetchedReport.time_management },
          { area: 'Orderliness and Program Flow', rating: fetchedReport.orderliness_and_program_flow },
          { area: 'Appropriateness of the Venue', rating: fetchedReport.appropriateness_of_venue },
          { area: 'Sound System and Hall Preparation', rating: fetchedReport.sound_system_and_hall_preparation },
          { area: 'Restroom/s', rating: fetchedReport.restrooms },
          { area: 'Food and Drinks', rating: fetchedReport.food_and_drinks || fetchedReport.food_drinks }
        ],
        rating: fetchedReport.rating
      };

      if (fetchedReport.attachment) {
        try {
          const parsed = JSON.parse(fetchedReport.attachment);
          existingFiles.value = Array.isArray(parsed) ? parsed : [fetchedReport.attachment];
        } catch (e) {
          existingFiles.value = [fetchedReport.attachment];
        }
      }
    } else {
      error.value = "Accomplishment report not found.";
    }
  } catch (err) {
    error.value = "Failed to load report details.";
  } finally {
    loading.value = false;
  }
};

const formatDate = (date) => date ? new Date(date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '---';
const formatFormType = (type) => {
  const map = { 
    'employee': 'Employee Training', 
    'inset': 'INSET', 
    'extension': 'Extension Program',
    'student': 'Student Activity'
  };
  return map[type] || type;
};
const formatCurrency = (amt) => amt ? parseFloat(amt).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '0.00';
const formatBudgetName = (name) => name ? name.replace(/(\(.*\))/g, '<span class="budget-item-subtext">$1</span>') : '';

const actualTotal = computed(() => {
  return formData.value.budget_items.reduce((sum, item) => sum + (Number(item.total) || 0), 0);
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
  return 'text-rose-400';
};

watch([() => formData.value.male, () => formData.value.female], ([m, f]) => {
  formData.value.attendees = (parseInt(m) || 0) + (parseInt(f) || 0);
});

watch(() => formData.value.evaluation_items, (items) => {
  const valid = items.filter(i => i.rating && !isNaN(parseFloat(i.rating)));
  if (valid.length === 0) formData.value.rating = 0;
  else {
    const sum = valid.reduce((acc, curr) => acc + parseFloat(curr.rating), 0);
    formData.value.rating = (sum / items.length).toFixed(2);
  }
}, { deep: true });

const removeExistingFile = (index) => {
  existingFiles.value.splice(index, 1);
};

const removeNewFile = (index) => {
  newFiles.value.splice(index, 1);
};

const handleFileChange = (e) => {
  const selectedFiles = Array.from(e.target.files);
  newFiles.value = [...newFiles.value, ...selectedFiles];
};

const handleUpdate = async () => {
  submitting.value = true;
  try {
    const id = route.params.id;
    const submitData = new FormData();
    
    submitData.append('activity_title', formData.value.activity_title);
    submitData.append('form_type', formData.value.form_type);
    submitData.append('start_date', formData.value.start_date);
    submitData.append('end_date', formData.value.end_date);
    submitData.append('start_time', formData.value.start_time);
    submitData.append('end_time', formData.value.end_time);
    submitData.append('venue', formData.value.venue);
    submitData.append('male', formData.value.male);
    submitData.append('female', formData.value.female);
    submitData.append('attendees', formData.value.attendees);
    submitData.append('rating', formData.value.rating);
    // Construct normalized budget rows
    const finalBudgetItems = [];
    const categoryMapping = {
      'Meals': 'Catering & Hospitality',
      'Snacks': 'Catering & Hospitality',
      'Function Room/Venue': 'Venue & Logistics',
      'Accommodation': 'Venue & Logistics',
      'Equipment Rental': 'Venue & Logistics',
      'Professional Fee/Honoria': 'Program & Speakers',
      'Token/s': 'Program & Speakers',
      'Materials and Supplies': 'Materials & Miscellaneous',
      'Transportation': 'Venue & Logistics',
      'Others': 'Materials & Miscellaneous'
    };

    formData.value.budget_items.forEach(item => {
      const totalAmt = Number(item.total) || 0;
      const category = categoryMapping[item.name] || 'Miscellaneous';

      if (item.name === 'Others') {
        if (othersList.value && othersList.value.length > 0) {
          othersList.value.forEach(other => {
            if (Number(other.amount) > 0) {
              finalBudgetItems.push({
                category,
                name: 'Others',
                sub_item: other.name || 'Other Item',
                amount: Number(other.amount)
              });
            }
          });
        } else if (totalAmt > 0) {
          finalBudgetItems.push({
            category,
            name: 'Others',
            sub_item: 'Other Item',
            amount: totalAmt
          });
        }
      } else {
        finalBudgetItems.push({
          category,
          name: item.name,
          sub_item: null,
          amount: totalAmt
        });
      }
    });

    submitData.append('budget_items', JSON.stringify(finalBudgetItems));

    submitData.append('evaluation_items', JSON.stringify(formData.value.evaluation_items));
    submitData.append('existing_attachments', JSON.stringify(existingFiles.value));
    submitData.append('status', 'Pending'); 

    newFiles.value.forEach(file => {
      submitData.append('attachment[]', file);
    });

    const response = await api.post(`update-report/${id}`, submitData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    if (response.data.success) {
      Swal.fire({
        icon: 'success',
        title: 'Resubmitted!',
        text: 'Accomplishment Report updated and resubmitted successfully.',
        confirmButtonColor: '#b979cc'
      }).then(() => {
        router.push('/college/submitted-list');
      });
    } else {
      Swal.fire({ icon: 'error', title: 'Update Failed', text: response.data.message || 'Failed to update report.', confirmButtonColor: '#b979cc' });
    }
  } catch (err) {
    console.error('Error updating report:', err);
    const errorMsg = err.response?.data?.message || 'Failed to update report. Please check your network or server logs.';
    Swal.fire({ icon: 'error', title: 'Update Failed', text: errorMsg, confirmButtonColor: '#b979cc' });
  } finally {
    submitting.value = false;
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
.main-viewport { flex: 1; min-height: 100vh; background: transparent; padding-bottom: 40px; }
.loading-wrapper { display: flex; justify-content: center; align-items: center; min-height: 400px; }

.error-view-wrapper { flex: 1; display: flex; align-items: center; justify-content: center; padding: 24px; min-height: 400px; }
.error-card { position: relative; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(24px); border-radius: 24px; border: 1px solid rgba(239, 68, 68, 0.2); padding: 40px; width: 100%; max-width: 448px; text-align: center; overflow: hidden; display: flex; flex-direction: column; align-items: center; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
.error-glow { position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 256px; height: 256px; background: rgba(239, 68, 68, 0.1); border-radius: 50%; filter: blur(64px); pointer-events: none; }
.error-icon-container { width: 96px; height: 96px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); display: flex; align-items: center; justify-content: center; margin-bottom: 24px; position: relative; z-index: 10; }
.error-icon { font-size: 48px; color: #f87171; }
.error-heading { font-size: 24px; font-weight: 900; color: white; margin-bottom: 12px; position: relative; z-index: 10; letter-spacing: -0.025em; }
.error-text { color: #e2e8f0; font-size: 16px; margin-bottom: 40px; position: relative; z-index: 10; line-height: 1.6; }
.error-btn-red { position: relative; z-index: 10; background: #ef4444; color: white; padding: 12px 32px; border-radius: 9999px; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.2s; display: flex; align-items: center; gap: 8px; border: none; cursor: pointer; }
.error-btn-red:hover { background: #dc2626; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4); }
.btn-icon { font-weight: bold; }

.page-container { min-height: 100vh;}
.glass-card { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); backdrop-filter: blur(24px); border-radius: 24px; border: 1px solid rgba(185, 121, 204, 0.2); }
.layout-grid { display: flex; gap: 15px; max-width: 80rem; margin: 0 auto; }
.flex-06 { flex: 0.65; display: flex; flex-direction: column; overflow: hidden; }
.flex-04-sidebar { flex: 0.35; position: sticky; top: 120px; align-self: flex-start; }
.report-header { padding: 32px; border-bottom: 1px solid rgba(185, 121, 204, 0.15); background: rgba(0, 0, 0, 0.2); }
.meta-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.header-meta-group { display: flex; gap: 20px; align-items: center; }
.meta-tag { display: flex; flex-direction: column; align-items: flex-end; text-align: right; }
.header-label { color: #64748b !important; margin-bottom: 2px; }
.report-title { font-family: 'Times New Roman', serif; font-size: 24px; color: white; line-height: 1.25; margin: 16px 0; }
.status-badge-revision { display: inline-flex; align-items: center; gap: 8px; background-color: rgba(239, 68, 68, 0.15); color: #ef4444; padding: 4px 12px; border-radius: 9999px; border: 1px solid rgba(239, 68, 68, 0.3); font-size: 13px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; }
.status-dot-pulse { width: 8px; height: 8px; background-color: #ef4444; border-radius: 9999px; animation: pulse 2s infinite; }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
.status-badge-wrapper { display: flex; align-items: center; gap: 8px; }
.control-number { font-size: 14px; font-weight: 700; color: #b979cc; text-transform: uppercase; font-family: monospace; }
.info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; border-top: 1px solid rgba(185, 121, 204, 0.15); padding-top: 24px; }
.info-label { font-size: 12px; text-transform: uppercase; color: #b979cc; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 4px; }
.info-value-white { color: white; font-weight: 600; font-size: 14px; display: block; }
.report-body { padding: 32px; display: flex; flex-direction: column; gap: 24px; flex: 1; }
.section-card { background: rgba(0, 0, 0, 0.25); border-radius: 16px; padding: 20px; border: 1px solid rgba(185, 121, 204, 0.12); }
.section-header-row { display: flex; align-items: center; gap: 8px; margin-bottom: 1rem; }
.section-title { font-size: 13px; font-weight: 800; text-transform: uppercase; color: #b979cc; letter-spacing: 0.1em; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
.metric-box { background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255, 255, 255, 0.05); padding: 12px; border-radius: 12px; text-align: center; }
.metric-value { display: block; font-size: 20px; font-weight: 800; color: white; }
.metric-label { font-size: 12px; color: #94a3b8; text-transform: uppercase; }
.total-highlight { border-color: rgba(185, 121, 204, 0.4); background: rgba(185, 121, 204, 0.05); display: flex; flex-direction: column; justify-content: center; }

option { color: #000;}
.budget-table-wrapper { border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.1); overflow: hidden; }
.budget-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.budget-table-header { background: rgba(255, 255, 255, 0.05); color: #b979cc; text-transform: uppercase; font-size: 13px; letter-spacing: 0.05em; }
.table-header-cell { padding: 10px 16px; text-align: left; font-weight: 700; }
.budget-table-row { border-top: 1px solid rgba(255, 255, 255, 0.05); }
.budget-item-name { padding: 12px 16px; color: #cbd5e1; line-height: 1.25; font-size: 13px; }
.budget-item-value-cell { padding: 8px 16px; color: white; font-size: 13px; }
.budget-table-footer { background: rgba(255, 255, 255, 0.05); font-weight: 800; }
.grand-total-label { padding: 12px 16px; text-align: right; color: #b979cc; text-transform: uppercase; font-size: 13px; font-weight: 700; letter-spacing: 0.05em;}
.grand-total-value-white { padding: 12px 16px; color: white; font-weight: 700; font-size: 14px; }
.icon-pink { color: #b979cc; }
.doc-list-edit { display: flex; flex-direction: column; gap: 12px; }
.doc-item-edit { display: flex; justify-content: space-between; align-items: center; background: rgba(0, 0, 0, 0.3); padding: 12px 16px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.1); }
.doc-info { display: flex; align-items: center; gap: 12px; }
.doc-pdf-icon { color: #f87171; font-size: 32px; }
.doc-title { font-size: 13px; font-weight: 700; color: white; }
.doc-meta { color: #94a3b8; font-size: 13px; }
.preview-btn { background: rgba(185, 121, 204, 0.1); border: 1px solid rgba(185, 121, 204, 0.3); color: #b979cc; padding: 6px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: 0.2s; }
.preview-btn:hover { border-color: #b979cc; color: white; background: rgba(185, 121, 204, 0.1); }
.remove-btn { background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s; }
.remove-btn:hover { background: #ef4444; color: white; }
.assessment-card-custom { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 24px; padding: 32px; border: 1px solid rgba(185, 121, 204, 0.2); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); }
.assessment-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid rgba(185, 121, 204, 0.15); }
.assessment-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; }
.assessment-title { font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #b979cc; }
.assessment-form { display: flex; flex-direction: column; }
.assessment-field { margin-bottom: 1.5rem; }
.read-only-remarks { background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(185, 121, 204, 0.2); border-radius: 12px; padding: 15px; color: #cbd5e1; font-size: 13px; min-height: 120px; margin-top: 8px; line-height: 1.5; }
.form-label { display: block; font-size: 12px; font-weight: 800; text-transform: uppercase; color: #b979cc; letter-spacing: 1px; margin-bottom: 8px; }
.form-group-top { margin-bottom: 1.5rem; }
.title-input { font-size: 20px !important; font-family: 'Times New Roman', serif; font-weight: 700; color: #fff !important; }
.edit-input { width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 10px; padding: 10px 14px; color: white; font-size: 13px; transition: all 0.2s; }
.edit-input:focus { outline: none; border-color: #b979cc; background: rgba(255, 255, 255, 0.08); }
.select-input { appearance: none; cursor: pointer; }
.select-arrow-fix {
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23b979cc' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 16px center;
  background-size: 14px;
}
.modal-input { width: 100%; padding: 12px 18px; border: 1px solid rgba(185, 121, 204, 0.2); background: rgba(0, 0, 0, 0.4); color: white; border-radius: 12px; font-size: 10px; }
.budget-edit { border-radius: 6px; padding: 4px 8px; background: rgba(0, 0, 0, 0.2); text-align: right; }
.rating-edit { width: 80px; padding: 4px; }
.action-buttons { display: flex; flex-direction: column; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(185, 121, 204, 0.15); }
.btn-approve { width: 100%; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); color: white; border: none; border-radius: 14px; padding: 14px; font-size: 12px; font-weight: 800; text-transform: uppercase; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; }
.btn-approve:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-approve:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(153, 13, 209, 0.25); }
.btn-back { display: block; width: 100%; padding: 12px; font-size: 13px; font-weight: 800; color: #cbd5e1; text-align: center; border-radius: 12px; background: transparent; border: 1px solid rgba(185, 121, 204, 0.15); margin-top: 8px; cursor: pointer; text-transform: uppercase; }
.btn-back:hover { color: white; border-color: #b979cc; background: rgba(185, 121, 204, 0.05); }
.text-emerald-400 { color: #34d399; }
.text-teal-400 { color: #2dd4bf; }
.text-cyan-400 { color: #22d3ee; }
.text-amber-400 { color: #fbbf24; }
.text-rose-400 { color: #f87171; }
.text-right { padding-right: 20px; font-size: 13px; }
.text-center { text-align: center; }
.budget-item-subtext { display: block; font-size: 11px; color: #94a3b8; font-weight: 400; margin-top: 2px; }

/* Others Breakdown Styles */
.others-breakdown-container {
  margin-top: 10px;
  padding: 12px;
  background-color: rgba(0, 0, 0, 0.25);
  border-radius: 10px;
  border: 1px dashed rgba(185, 121, 204, 0.2);
}

.others-breakdown-row {
  display: flex;
  gap: 8px;
  margin-bottom: 8px;
  align-items: center;
}

.others-input-name {
  flex: 1;
  background-color: rgba(26, 26, 46, 0.6);
  border: 1px solid rgba(185, 121, 204, 0.2);
  border-radius: 8px;
  padding: 6px 10px;
  color: #ffffff;
  font-size: 12px;
  outline: none;
  box-sizing: border-box;
}

.others-input-amount {
  width: 110px;
  background-color: rgba(26, 26, 46, 0.6);
  border: 1px solid rgba(185, 121, 204, 0.2);
  border-radius: 8px;
  padding: 6px 10px;
  color: #ffffff;
  font-size: 12px;
  outline: none;
  box-sizing: border-box;
}

.others-input-name:focus,
.others-input-amount:focus {
  border-color: #b979cc;
  box-shadow: 0 0 0 2px rgba(185, 121, 204, 0.15);
}

.btn-remove-other {
  background: transparent;
  border: none;
  color: #f43f5e;
  cursor: pointer;
  font-size: 18px;
  line-height: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 4px;
  transition: color 0.2s;
}

.btn-remove-other:hover {
  color: #fda4af;
}

.btn-add-other {
  background-color: rgba(185, 121, 204, 0.1);
  border: 1px solid rgba(185, 121, 204, 0.25);
  color: #b979cc;
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 11px;
  font-weight: 700;
  cursor: pointer;
  margin-top: 4px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  transition: all 0.2s ease;
  text-transform: uppercase;
  letter-spacing: 0.02em;
}

.btn-add-other:hover {
  background-color: rgba(185, 121, 204, 0.2);
  transform: translateY(-0.5px);
}

.others-total-badge {
  background-color: rgba(185, 121, 204, 0.15);
  border: 1px solid rgba(185, 121, 204, 0.3);
  color: #b979cc;
  padding: 6px 12px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 13px;
  display: inline-block;
}

.budget-groups-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-top: 10px;
}

.budget-group-card {
  background: rgba(30, 41, 59, 0.45);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 14px;
  padding: 20px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
}

.budget-group-card:hover {
  border-color: rgba(185, 121, 204, 0.3);
  background: rgba(30, 41, 59, 0.6);
  box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}

.budget-group-header {
  display: flex;
  align-items: center;
  gap: 10px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.08);
  padding-bottom: 12px;
  margin-bottom: 16px;
}

.budget-group-icon {
  font-size: 18px;
}

.budget-group-title {
  font-size: 13px;
  font-weight: 700;
  color: #b979cc;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.budget-group-content {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.budget-row-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;
  padding-bottom: 16px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}

.budget-row-item:last-child {
  padding-bottom: 0;
  border-bottom: none;
}

.budget-item-info {
  display: flex;
  flex-direction: column;
  gap: 6px;
  flex-grow: 1;
}

.budget-item-title {
  font-weight: 600;
  color: #f1f5f9;
  font-size: 14px;
}

.budget-item-subtext {
  font-size: 11px;
  color: #64748b;
}

.budget-item-value {
  display: flex;
  align-items: center;
  gap: 6px;
  width: 240px;
  flex-shrink: 0;
  justify-content: flex-end;
}

.budget-currency-symbol {
  color: #64748b;
  font-size: 14px;
  font-weight: 600;
}

.budget-card-input {
  background-color: rgba(15, 23, 42, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 8px;
  color: #ffffff;
  font-size: 14px;
  padding: 8px 12px;
  width: 100%;
  text-align: right;
  transition: all 0.2s ease;
  font-weight: 600;
}

.budget-card-input:focus {
  border-color: #b979cc;
  background-color: rgba(15, 23, 42, 0.5);
  box-shadow: 0 0 0 2px rgba(185, 121, 204, 0.2);
  outline: none;
}

.grand-total-banner-card {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(135deg, rgba(185, 121, 204, 0.1) 0%, rgba(153, 13, 209, 0.1) 100%);
  border: 1px solid rgba(185, 121, 204, 0.3);
  border-radius: 14px;
  padding: 20px;
  margin-top: 20px;
  box-shadow: 0 4px 15px -3px rgba(185, 121, 204, 0.1);
}

.grand-total-label-banner {
  font-size: 13px;
  font-weight: 700;
  color: #ffffff;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.grand-total-value-banner {
  font-size: 20px;
  font-weight: 800;
  color: #b979cc;
  text-shadow: 0 0 10px rgba(185, 121, 204, 0.2);
}
</style>
