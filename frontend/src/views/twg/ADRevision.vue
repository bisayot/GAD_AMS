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
        <p class="error-text">
          {{ error }}
        </p>
        <button @click="router.back()" class="error-btn-red">
          <span class="material-symbols-outlined btn-icon">arrow_back</span>
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
                  <option value="" disabled>Select form type...</option>
                  <option 
                    v-for="ft in formTypes" 
                    :key="ft.id" 
                    :value="ft.id" 
                    class="dark-option"
                  >
                    {{ ft.name }}
                  </option>
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
                  <input v-model="formData.start_date" type="date" class="modal-input">
                </div>
                <div>
                  <label class="form-label">End Date</label>
                  <input v-model="formData.end_date" type="date" class="modal-input">
                </div>
                <div>
                  <label class="form-label">Start Time</label>
                  <input v-model="formData.start_time" type="time" class="modal-input">
                </div>
                <div>
                  <label class="form-label">End Time</label>
                  <input v-model="formData.end_time" type="time" class="modal-input">
                </div>
              </div>
              <div class="venue-participants-row">
                <div class="venue-col">
                  <label class="form-label">Venue</label>
                  <select v-model="formData.venue" class="modal-input select-input select-arrow-fix">
                    <option value="" disabled>Select venue...</option>
                    <option v-for="v in venues" :key="v.venue_id" :value="v.venue_id" class="dark-option">
                      {{ v.venue_name }}
                    </option>
                    <option value="Other" class="dark-option">Other</option>
                  </select>
                </div>
                <div class="participants-col">
                  <label class="form-label">Participants</label>
                  <input v-model="formData.target_participants" type="number" class="modal-input modal-input-center">
                </div>
              </div>

              <div v-if="formData.venue === 'Other'" class="custom-venue-wrapper">
                <label class="form-label">Specify Other Venue</label>
                <input v-model="customVenue" type="text" class="modal-input" placeholder="Enter the complete venue name">
              </div>
            </div>

            <div class="section-card">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">payments</span>
                <h3 class="section-title">Proposed Budgetary Requirements</h3>
              </div>

              <!-- Proposed Budgetary Requirements Table (Refactored Inline) -->
              <div v-if="formData.budget_items.length" class="budget-content">
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
                          <div class="budget-sub-controls">
                            <label class="budget-checkbox-label">
                              <input type="checkbox" v-model="mealsSelected.breakfast" class="budget-checkbox" /> Breakfast
                            </label>
                            <label class="budget-checkbox-label">
                              <input type="checkbox" v-model="mealsSelected.lunch" class="budget-checkbox" /> Lunch
                            </label>
                            <label class="budget-checkbox-label">
                              <input type="checkbox" v-model="mealsSelected.dinner" class="budget-checkbox" /> Dinner
                            </label>
                          </div>
                          <div v-if="formData.target_participants < 50 && formData.target_participants !== ''" class="budget-warning-inline">
                            ⚠️ Participants &lt; 50. GAD cannot fund meals.
                          </div>
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
                          <div class="budget-sub-controls">
                            <label class="budget-checkbox-label">
                              <input type="checkbox" v-model="snacksSelected.am" class="budget-checkbox" /> AM Snack
                            </label>
                            <label class="budget-checkbox-label">
                              <input type="checkbox" v-model="snacksSelected.pm" class="budget-checkbox" /> PM Snack
                            </label>
                          </div>
                          <div v-if="formData.target_participants < 50 && formData.target_participants !== ''" class="budget-warning-inline">
                            ⚠️ Participants &lt; 50. GAD cannot fund snacks.
                          </div>
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
                          <span class="budget-item-subtext">(Leave blank/zero for Attribution)</span>
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
                          <span class="budget-item-subtext">(Leave blank/zero)</span>
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
                          <span class="budget-item-subtext">(Leave blank/zero)</span>
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
                          <div v-if="formData.budget_items[8].total > 20000" class="budget-error-inline">
                            ⚠️ Cannot exceed ₱20,000 limit.
                          </div>
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
                          <div class="budget-sub-controls">
                            <label class="budget-number-input-label">
                              Number of Speakers:
                              <input type="number" v-model.number="pfPax" min="0" class="budget-sub-number-input" placeholder="0" />
                            </label>
                          </div>
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
                          <div class="budget-sub-controls">
                            <label class="budget-number-input-label">
                              Number of Recipients:
                              <input type="number" v-model.number="tokensPax" min="0" class="budget-sub-number-input" placeholder="0" />
                            </label>
                          </div>
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
                          <span class="budget-item-subtext">(Auto-computed: participants * ₱1,000)</span>
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
                            <input type="text" v-model="o.name" placeholder="Item name (e.g. Coffee)" class="others-input-name" />
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
                  <div class="grand-total-label-banner">Grand Total (PHP)</div>
                  <div class="grand-total-value-banner">
                    ₱{{ formatCurrency(formData.proposed_budget) }}
                  </div>
                </div>
              </div>
              <div v-else class="empty-budget-notice">
                No budgetary requirements were specified for this design.
                </div>
              </div>
            </div>

            <div class="section-card">
              <div class="section-header-row">
                <span class="material-symbols-outlined icon-pink">description</span>
                <h3 class="section-title">Supporting Documents</h3>
              </div>
              <div class="doc-item">
                <div class="doc-info">
                  <span class="material-symbols-outlined doc-pdf-icon">picture_as_pdf</span>
                  <div>
                    <p class="doc-title" v-if="!newFile">{{ design.attachment || 'No file uploaded' }}</p>
                    <p class="doc-title" v-else>{{ newFile.name }}</p>
                    <p class="doc-meta" v-if="design.attachment && !newFile">Current File</p>
                  </div>
                </div>
                <label class="preview-btn">
                  <span>Change File</span>
                  <input type="file" @change="handleFileChange" class="file-input-hidden" accept=".pdf,.doc,.docx">
                </label>
              </div>
            </div>
        </section>

        <section class="flex-04-sidebar">
          <div class="assessment-card-custom">
            <div class="assessment-header">
              <div class="assessment-icon">📋</div>
              <div class="assessment-title">Reviewer Feedback</div>
            </div>

            <div class="assessment-form">
              <div v-if="design.accomplishment_deadline" class="info-item assessment-date-display">
                <span class="info-label">Accomplishment Deadline</span>
                <span class="info-value-white">{{ formatDate(design.accomplishment_deadline) }}</span>
              </div>

              <div class="info-item assessment-date-display">
                <span class="info-label">Date of Assessment</span>
                <span class="info-value-white">{{ formatDate(design.assessment_date) }}</span>
              </div>

              <div class="info-item feedback-remarks">
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
import { ref, onMounted, computed, watch } from 'vue';
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
const venues = ref([]);
const formTypes = ref([]);
const customVenue = ref('');
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
  target_participants: 0,
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
  ]
});

// Watch for changes in budget_items to update proposed_budget
watch(() => formData.value.budget_items, (newItems) => {
  const total = newItems.reduce((sum, item) => sum + (Number(item.total) || 0), 0);
  formData.value.proposed_budget = total;
}, { deep: true });

// Computed Properties for Auto-calculation
const computedDays = computed(() => {
  if (!formData.value.start_date || !formData.value.end_date) return 1;
  const start = new Date(formData.value.start_date);
  const end = new Date(formData.value.end_date);
  if (isNaN(start.getTime()) || isNaN(end.getTime())) return 1;
  const diffTime = end.getTime() - start.getTime();
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
  return diffDays > 0 ? diffDays : 1;
});

const isOutsideBsu = computed(() => {
  return formData.value.venue === 'Other';
});

// Reactive Sub-controls State
const mealsSelected = ref({ breakfast: false, lunch: false, dinner: false });
const snacksSelected = ref({ am: false, pm: false });
const pfPax = ref('');
const tokensPax = ref('');
const othersList = ref([]);

const addOtherItem = () => {
  othersList.value.push({ name: '', amount: '' });
};

const removeOtherItem = (index) => {
  othersList.value.splice(index, 1);
};

// Reactive Auto-computation Watchers
watch(
  [mealsSelected, () => formData.value.target_participants, computedDays, isOutsideBsu],
  () => {
    const item = formData.value.budget_items.find(i => i.name === 'Meals');
    if (item) {
      const mealsCount = (mealsSelected.value.breakfast ? 1 : 0) + (mealsSelected.value.lunch ? 1 : 0) + (mealsSelected.value.dinner ? 1 : 0);
      const mealsRate = isOutsideBsu.value ? 350 : 220;
      const pax = Number(formData.value.target_participants) || 0;
      const calculated = pax >= 50 ? (mealsCount * mealsRate * pax * computedDays.value) : 0;
      item.total = calculated || '';
    }
  },
  { deep: true }
);

watch(
  [snacksSelected, () => formData.value.target_participants, computedDays, isOutsideBsu],
  () => {
    const item = formData.value.budget_items.find(i => i.name === 'Snacks');
    if (item) {
      const snacksCount = (snacksSelected.value.am ? 1 : 0) + (snacksSelected.value.pm ? 1 : 0);
      const snacksRate = isOutsideBsu.value ? 150 : 80;
      const pax = Number(formData.value.target_participants) || 0;
      const calculated = pax >= 50 ? (snacksCount * snacksRate * pax * computedDays.value) : 0;
      item.total = calculated || '';
    }
  },
  { deep: true }
);

watch(pfPax, (newPax) => {
  const item = formData.value.budget_items.find(i => i.name === 'Professional Fee/Honoria');
  if (item) {
    item.total = (Number(newPax) * 2258.25) || '';
  }
});

watch(tokensPax, (newPax) => {
  const item = formData.value.budget_items.find(i => i.name === 'Token/s');
  if (item) {
    item.total = (Number(newPax) * 1000) || '';
  }
});

watch(() => formData.value.target_participants, (newPax) => {
  const item = formData.value.budget_items.find(i => i.name === 'Materials and Supplies');
  if (item) {
    item.total = (Number(newPax) * 1000) || '';
  }
});

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

const fetchVenues = async () => {
  try {
    const response = await api.get('get-venues');
    if (response.data && response.data.success) {
      venues.value = response.data.data || [];
    }
  } catch (err) {
    console.error('Error fetching venues:', err);
  }
};

const fetchFormTypes = async () => {
  try {
    const response = await api.get('get-form-types');
    formTypes.value = response.data || [];
  } catch (err) {
    console.error('Error fetching form types:', err);
  }
};

const fetchDesignDetails = async () => {
  loading.value = true;
  try {
    const id = route.params.id;
    const response = await api.get(`activity-design/${id}`);
    if (response.data.success) {
      design.value = response.data.data;
      // Initialize default structures
      mealsSelected.value = { breakfast: false, lunch: false, dinner: false };
      snacksSelected.value = { am: false, pm: false };
      pfPax.value = '';
      tokensPax.value = '';
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

      if (design.value.budget_items && design.value.budget_items.length > 0) {
        design.value.budget_items.forEach(bi => {
          const amt = Number(bi.amount) || 0;
          if (bi.item_name === 'Meals') {
            mealsTotal += amt;
            if (bi.sub_item) {
              mealsSelected.value[bi.sub_item.toLowerCase()] = true;
            }
          } else if (bi.item_name === 'Snacks') {
            snacksTotal += amt;
            if (bi.sub_item) {
              const subKey = bi.sub_item.startsWith('AM') ? 'am' : 'pm';
              snacksSelected.value[subKey] = true;
            }
          } else if (bi.item_name === 'Function Room/Venue') {
            functionRoomVenue = amt || '';
          } else if (bi.item_name === 'Accommodation') {
            accommodation = amt || '';
          } else if (bi.item_name === 'Equipment Rental') {
            equipmentRental = amt || '';
          } else if (bi.item_name === 'Professional Fee/Honoria') {
            professionalFee = amt || '';
            if (bi.pax) pfPax.value = bi.pax;
          } else if (bi.item_name === 'Token/s') {
            tokensVal = amt || '';
            if (bi.pax) tokensPax.value = bi.pax;
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
        activity_title: design.value.activity_title,
        office: design.value.office,
        form_type: String(design.value.form_type || ''),
        start_date: design.value.start_date,
        end_date: design.value.end_date,
        start_time: design.value.start_time,
        end_time: design.value.end_time,
        venue: (design.value.venue_id && design.value.venue_id !== 'Other') ? design.value.venue_id : 'Other',
        proposed_budget: design.value.proposed_budget,
        target_participants: design.value.target_participants,
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
        ]
      };

      if (!design.value.venue_id || design.value.venue_id === 'Other') {
        customVenue.value = design.value.venue;
      }
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

const formatDate = (date) => date ? new Date(date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : '---';

const formatBudgetName = (name) => { // Added formatBudgetName
  if (!name) return '';
  return name.replace(/(\(.*\))/g, '<span class="budget-item-subtext">$1</span>');
};

const formatCurrency = (amt) => amt ? parseFloat(amt).toLocaleString(undefined, { minimumFractionDigits: 2 }) : '0.00'; // Added formatCurrency

const handleUpdate = async () => {
  submitting.value = true;
  try {
    const id = route.params.id;
    const submitData = new FormData();
    
    // Aligning keys with ActivityDesignController expectations
    submitData.append('activity_title', formData.value.activity_title);
    submitData.append('form_type', formData.value.form_type);
    submitData.append('start_date', formData.value.start_date);
    submitData.append('end_date', formData.value.end_date);
    submitData.append('start_time', formData.value.start_time);
    submitData.append('end_time', formData.value.end_time);
    submitData.append('proposed_budget', formData.value.proposed_budget);
    submitData.append('target_participants', formData.value.target_participants);

    // Venue Logic
    if (formData.value.venue && formData.value.venue !== 'Other') {
      submitData.append('venue_id', formData.value.venue);
      // Use the venue name from the venues array or fall back to the existing design name
      const v = venues.value.find(v => v.venue_id == formData.value.venue);
      submitData.append('venue_name', v ? v.venue_name : formData.value.venue);
    } else if (formData.value.venue === 'Other') {
      submitData.append('venue_id', '');
      submitData.append('venue_name', customVenue.value || '');
    }

    const transItem = formData.value.budget_items.find(i => i.name === 'Transportation');
    if (transItem && Number(transItem.total) > 20000) {
      Swal.fire({
        icon: 'warning',
        title: 'Limit Exceeded',
        text: 'Transportation budget cannot exceed the maximum limit of ₱20,000.',
        confirmButtonColor: '#b979cc'
      });
      return;
    }

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

    // Meals
    const mealsSelectedKeys = Object.keys(mealsSelected.value).filter(k => mealsSelected.value[k]);
    if (mealsSelectedKeys.length > 0) {
      const baseMeals = formData.value.budget_items.find(i => i.name === 'Meals');
      const baseMealsTotal = Number(baseMeals?.total) || 0;
      if (baseMealsTotal > 0) {
        const amountPerMeal = baseMealsTotal / mealsSelectedKeys.length;
        mealsSelectedKeys.forEach(key => {
          const capitalized = key.charAt(0).toUpperCase() + key.slice(1);
          finalBudgetItems.push({
            category: 'Catering & Hospitality',
            name: 'Meals',
            sub_item: capitalized,
            amount: amountPerMeal
          });
        });
      }
    }

    // Snacks
    const snacksSelectedKeys = Object.keys(snacksSelected.value).filter(k => snacksSelected.value[k]);
    if (snacksSelectedKeys.length > 0) {
      const baseSnacks = formData.value.budget_items.find(i => i.name === 'Snacks');
      const baseSnacksTotal = Number(baseSnacks?.total) || 0;
      if (baseSnacksTotal > 0) {
        const amountPerSnack = baseSnacksTotal / snacksSelectedKeys.length;
        snacksSelectedKeys.forEach(key => {
          const capitalized = key === 'am' ? 'AM Snack' : 'PM Snack';
          finalBudgetItems.push({
            category: 'Catering & Hospitality',
            name: 'Snacks',
            sub_item: capitalized,
            amount: amountPerSnack
          });
        });
      }
    }

    formData.value.budget_items.forEach(item => {
      if (item.name === 'Meals' || item.name === 'Snacks') return;

      const category = categoryMapping[item.name] || 'Miscellaneous';
      const totalAmt = Number(item.total) || 0;

      if (item.name === 'Professional Fee/Honoria') {
        finalBudgetItems.push({
          category,
          name: item.name,
          sub_item: null,
          pax: Number(pfPax.value) || null,
          amount: totalAmt
        });
      } else if (item.name === 'Token/s') {
        finalBudgetItems.push({
          category,
          name: item.name,
          sub_item: null,
          pax: Number(tokensPax.value) || null,
          amount: totalAmt
        });
      } else if (item.name === 'Others') {
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

    submitData.append('status', 'Pending'); // Reset status so admin can review again
    
    if (newFile.value) {
      submitData.append('attachment', newFile.value);
    }

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

onMounted(async () => {
  if (!user.value.id || user.value.role !== 'college') {
    router.push('/login');
  } else {
    try {
      await fetchFormTypes();
      await fetchVenues();
      await fetchDesignDetails();
    } catch (err) {
      console.error(err);
    }
  }
});
</script>

<style scoped>
.main-viewport { flex: 1; height: 100vh; background: transparent; }
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

.page-container { min-height: 100vh; }
.layout-grid { display: flex; gap: 15px; max-width: 80rem; margin: 0 auto; }
.flex-06 { flex: 0.65; display: flex; flex-direction: column; overflow: hidden; }
.flex-04-sidebar { flex: 0.35; position: sticky; top: 120px; align-self: flex-start; }

.glass-card { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); backdrop-filter: blur(24px); border-radius: 1.5rem; border: 1px solid rgba(185, 121, 204, 0.2); }
.report-header { padding: 2rem; border-bottom: 1px solid rgba(185, 121, 204, 0.15); background: rgba(0, 0, 0, 0.2); }
.meta-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem; }
.report-body { flex: 1; overflow-y: auto; padding: 2rem; }
.report-body > * + * { margin-top: 1.5rem; }

.status-badge-revision { display: inline-flex; align-items: center; gap: 8px; background-color: rgba(239, 68, 68, 0.15); color: #ef4444; padding: 4px 12px; border-radius: 9999px; border: 1px solid rgba(239, 68, 68, 0.3); }
.status-dot-pulse { width: 8px; height: 8px; background-color: #ef4444; border-radius: 9999px; animation: pulse 2s infinite; }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
.status-text { font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; }

.control-number { font-size: 13px; font-weight: 700; color: #b979cc; text-transform: uppercase; margin-left: 12px; font-family: monospace; }
.form-group-top { margin-bottom: 1.5rem; }
.title-input { font-size: 20px !important; font-family: 'Times New Roman', serif; font-weight: 700; }

.info-grid { display: flex; flex-wrap: wrap; gap: 24px; padding-top: 16px; border-top: 1px solid rgba(185, 121, 204, 0.1); }
.info-item { display: flex; flex-direction: column; }
.info-label { font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; color: #b979cc; font-weight: 700; margin-bottom: 4px; }
.info-value-white { font-size: 14px; font-weight: 600; color: white; }

.assessment-date-display { margin-bottom: 1.5rem; }
.icon-pink { color: #b979cc; }
.full-width-info { grid-column: span 2; margin-top: 1rem; }

.section-card { background-color: rgba(0, 0, 0, 0.2); border-radius: 16px; padding: 24px; border: 1px solid rgba(185, 121, 204, 0.15); }
.section-header-row { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.section-title { font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; color: #b979cc; }

.grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
.venue-participants-row {
  display: grid;
  grid-template-columns: 2.25fr 1fr;
  gap: 16px;
  margin-top: 16px;
  padding-top: 16px;
  border-top: 1px solid rgba(185, 121, 204, 0.1);
}

.doc-item { display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: rgba(0, 0, 0, 0.3); border-radius: 12px; border: 1px solid rgba(185, 121, 204, 0.15); }
.doc-info { display: flex; align-items: center; gap: 12px; color: white; font-size: 12px;}
.doc-pdf-icon { font-size: 1.875rem; color: #ef4444; }
.doc-title { font-size: 13px; font-weight: 700; color: b979cc; }
.doc-meta { font-size: 11px; color: #cbd5e1; margin-top: 2px; }
.preview-btn { color: #b979cc; font-size: 11px; padding: 6px 12px; border-radius: 8px; background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(185, 121, 204, 0.15); font-weight: 700; text-align: center; cursor: pointer; }
.preview-btn:hover { border-color: #b979cc; color: white; background: rgba(185, 121, 204, 0.1); }

.assessment-card-custom { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 1.5rem; padding: 2rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); border: 1px solid rgba(185, 121, 204, 0.2); }
.assessment-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid rgba(185, 121, 204, 0.15); }
.assessment-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: white; font-size: 22px; }
.assessment-title { font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #b979cc; }
.assessment-form { display: flex; flex-direction: column; }

.read-only-remarks { width: 100%; border: 1px solid rgba(185, 121, 204, 0.2); border-radius: 12px; padding: 14px 16px; font-size: 13px; background: rgba(0, 0, 0, 0.3); color: #cbd5e1; min-height: 100px; line-height: 1.5; }
.feedback-remarks { margin-bottom: 1rem; }

.form-label { display: block; font-size: 12px; font-weight: 800; text-transform: uppercase; color: #b979cc; letter-spacing: 1px; margin-bottom: 8px; }
.modal-input { width: 100%; padding: 12px 18px; border: 1px solid rgba(185, 121, 204, 0.2); background: rgba(0, 0, 0, 0.4); color: white; border-radius: 12px; font-size: 13px; }
.modal-input:focus { outline: none; border-color: #b979cc; }
.modal-input-center { text-align: center; }
.select-input { appearance: none; cursor: pointer; }
.select-arrow-fix {
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23b979cc' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 16px center;
  background-size: 14px;
}
.dark-option { background-color: #16213e; color: #ffffff; }
.custom-venue-wrapper { margin-top: 1rem; }
.file-input-hidden { display: none; }

.action-buttons { display: flex; flex-direction: column; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(185, 121, 204, 0.15); }
.btn-approve { width: 100%; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); color: white; border: none; border-radius: 14px; padding: 14px; font-size: 12px; font-weight: 800; text-transform: uppercase; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; }
.btn-approve:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-approve:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(153, 13, 209, 0.25); }

.btn-back { display: block; width: 100%; padding: 12px; font-size: 11px; color: #cbd5e1; text-align: center; border-radius: 12px; background: transparent; border: 1px solid rgba(185, 121, 204, 0.15); margin-top: 8px; cursor: pointer; }
.btn-back:hover { color: white; border-color: #b979cc; background: rgba(185, 121, 204, 0.05); }

.loading-spinner { width: 40px; height: 40px; border: 3px solid #f3f3f3; border-top: 3px solid #990dd1; border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

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
  font-weight: 700;
}

.budget-col-total {
  width: 128px; /* Adjust as needed */
}

.budget-table-body {
  border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.budget-table-row {
  border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.budget-item-name {
  padding: 12px 16px;
  color: #b979cc;
  line-height: 1.25;
  font-size: 13px;
}

.budget-item-subtext {
  display: block;
  font-size: 12px;
  color: #64748b;
  font-weight: 400;
  margin-top: 2px;
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
  text-align: right; /* Align budget input to the right */
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
  font-size: 12px;
  font-weight: 700;
  color: #b979cc;
  text-align: right;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.grand-total-value-white {
  padding: 12px 16px;
  font-size: 14px;
  font-weight: 700;
  color: white;
  text-align: right; /* Align grand total value to the right */
}

.empty-budget-notice {
  color: #64748b;
  font-size: 13px;
  font-style: italic;
  padding: 16px;
  text-align: center;
}

/* GAD Budget Table Inline Styles */
.budget-sub-controls {
  margin-top: 8px;
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  font-size: 11px;
  color: #94a3b8;
}

.budget-checkbox-label {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  user-select: none;
  color: #cbd5e1;
  font-size: 13px;
  font-weight: 500;
  transition: color 0.2s ease;
}

.budget-checkbox-label:hover {
  color: #ffffff;
}

.budget-checkbox {
  appearance: none;
  -webkit-appearance: none;
  width: 18px;
  height: 18px;
  background-color: rgba(15, 23, 42, 0.3);
  border: 2px solid rgba(185, 121, 204, 0.4);
  border-radius: 5px;
  display: inline-grid;
  place-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  margin: 0;
  position: relative;
}

.budget-checkbox:hover {
  border-color: #b979cc;
  background-color: rgba(185, 121, 204, 0.1);
  box-shadow: 0 0 0 2px rgba(185, 121, 204, 0.2);
}

.budget-checkbox:checked {
  background-color: #b979cc;
  border-color: #b979cc;
  box-shadow: 0 0 8px rgba(185, 121, 204, 0.4);
}

.budget-checkbox:checked::before {
  content: "";
  width: 10px;
  height: 10px;
  background-color: #ffffff;
  clip-path: polygon(14% 44%, 0 58%, 38% 95%, 100% 23%, 86% 9%, 38% 68%);
}

.budget-number-input-label {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #cbd5e1;
  font-size: 13px;
  font-weight: 500;
}

.budget-sub-number-input {
  background-color: rgba(15, 23, 42, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 8px;
  padding: 8px 12px;
  width: 90px;
  color: #ffffff;
  font-size: 14px;
  outline: none;
  box-sizing: border-box;
  text-align: center;
  transition: all 0.2s ease;
  font-weight: 600;
}

.budget-sub-number-input:focus {
  border-color: #b979cc;
  background-color: rgba(15, 23, 42, 0.5);
  box-shadow: 0 0 0 2px rgba(185, 121, 204, 0.2);
}

.budget-warning-inline {
  margin-top: 6px;
  font-size: 11px;
  color: #fbbf24;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 4px;
}

.budget-error-inline {
  margin-top: 6px;
  font-size: 11px;
  color: #f43f5e;
  font-weight: bold;
  display: flex;
  align-items: center;
  gap: 4px;
}

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

.resize-none {
  resize: none;
}

.select-arrow-fix {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23b979cc' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 20px center;
  background-size: 16px;
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