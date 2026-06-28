<template>
      <main class="twg-view-wrapper">
        <div class="main-content-container">
          <div class="form-header">
            <h1 class="form-main-title">Submit Activity Design</h1>
            <p class="form-description">Fill out the activity design form below. All fields marked with * are required.</p>
          </div>

          <div class="form-container-box">
            <form @submit.prevent="submitActivityDesign" class="form-main-layout">
              <div class="form-grid-main">
                <div class="form-column-left">
                  <div class="form-sub-grid">
                    <div class="input-group">
                      <label class="form-label">Form Type *</label>
                      <select 
                        v-model="form.form_type" 
                        required 
                        class="custom-input-field select-arrow-fix"
                      >
                        <option value="" disabled class="dark-option">Select form type...</option>
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

                    <div class="input-group">
                      <label class="form-label">Activity Classification *</label>
                      <select
                        v-model="form.activity_classification_id"
                        required
                        class="custom-input-field select-arrow-fix"
                      >
                        <option value="" disabled class="dark-option">Select Classification</option>
                        <option
                          v-for="classification in ActClassification"
                          :key="classification.id"
                          :value="classification.id"
                          class="dark-option"
                        >
                          {{ classification.classification_name }}
                        </option>
                      </select>
                    </div>
                  </div>

                  <div class="input-group">
                    <label class="form-label">Activity Title *</label>
                    <textarea 
                      v-model="form.activity_title" 
                      required 
                      rows="2" 
                      class="custom-input-field textarea-no-resize"
                      placeholder="Enter the complete title of the activity"
                    ></textarea>
                  </div>

                  <div class="input-group">
                    <label class="form-label">GAD Mandate *</label>
                    <select v-model="form.gad_mandate_id" required class="custom-input-field select-arrow-fix">
                      <option value="" disabled class="dark-option">Select Mandate</option>
                      <option v-for="mandate in GADMandates" :key="mandate.id" :value="mandate.id" class="dark-option">
                        {{ mandate.code }} - {{ mandate.title }}
                      </option>
                      <option value="Other" class="dark-option">+ New Mandate</option>
                    </select>
                    <input v-if="form.gad_mandate_id === 'Other'" 
                          v-model="customMandate" 
                          type="text" 
                          placeholder="Enter new mandate name..." 
                          class="custom-input-field" 
                          style="margin-top: 10px;" />
                  </div>

                  <div class="input-group">
                    <label class="form-label">Gender Issues *</label>
                    <select v-model="form.gender_issue_id" required class="custom-input-field select-arrow-fix">
                      <option value="" disabled class="dark-option">
                        {{ form.gad_mandate_id ? 'Select Gender Issue' : 'Select Mandate first' }}
                      </option>
                      <option v-for="issue in genderIssues" :key="issue.id" :value="issue.id" class="dark-option">
                        {{ issue.title }}
                      </option>
                      <option value="Other" class="dark-option">+ New Gender Issue</option>
                    </select>
                    <input v-if="form.gender_issue_id === 'Other'" 
                          v-model="customGenderIssue" 
                          type="text" 
                          placeholder="Enter new gender issue..." 
                          class="custom-input-field" 
                          style="margin-top: 10px;" />
                  </div>

                  <div class="input-group">
                    <label class="form-label">Venue *</label>
                    <select 
                      v-model="form.venue" 
                      required 
                      class="custom-input-field select-arrow-fix"
                    >
                      <option value="" disabled class="dark-option">Select venue...</option>
                      <option 
                        v-for="v in venues" 
                        :key="v.venue_id" 
                        :value="v.venue_id" 
                        class="dark-option"
                      >
                        {{ v.venue_name }}
                      </option>
                      <option value="Other" class="dark-option">Other</option>
                    </select>
                  </div>

                  <div v-if="form.venue === 'Other'" class="input-group">
                    <label class="form-label">Specify Other Venue *</label>
                    <input 
                      type="text" 
                      v-model="customVenue" 
                      required 
                      class="custom-input-field"
                      placeholder="Enter the complete venue name"
                    >
                  </div>

                  <div class="form-sub-grid">
                    <div class="input-group">
                      <div class="label-container">
                        <label class="form-label">Start Date *</label>
                        <div class="info-btn-wrapper">
                          <button type="button" class="info-btn" @click.stop="toggleHelp('startDate')">
                            i
                          </button>
                          <transition name="fade-pop">
                            <div v-if="helpState.startDate" class="simple-popup">
                              Must be scheduled on working days from Monday to Thursday.
                            </div>
                          </transition>
                        </div>
                      </div>
                      <input 
                        type="date" 
                        v-model="form.start_date" 
                        :min="todayDate"
                        required 
                        class="custom-input-field code-icon-calendar"
                      >
                    </div>
                    <div class="input-group">
                      <div class="label-container">
                        <label class="form-label">End Date *</label>
                        <div class="info-btn-wrapper">
                          <button type="button" class="info-btn" @click.stop="toggleHelp('endDate')">
                            i
                          </button>
                          <transition name="fade-pop">
                            <div v-if="helpState.endDate" class="simple-popup">
                              Date must not exceed a week.
                            </div>
                          </transition>
                        </div>
                      </div>
                      <input 
                        type="date" 
                        v-model="form.end_date" 
                        :min="todayDate"
                        required 
                        class="custom-input-field code-icon-calendar"
                      >
                    </div>
                  </div>

                  <div class="form-sub-grid">
                    <div class="input-group">
                      <div class="label-container">
                        <label class="form-label">Start Time *</label>
                        <div class="info-btn-wrapper">
                          <button type="button" class="info-btn" @click.stop="toggleHelp('startTime')">
                            i
                          </button>
                          <transition name="fade-pop">
                            <div v-if="helpState.startTime" class="simple-popup">
                              Must be set between 04:00 AM and 08:00 PM.
                            </div>
                          </transition>
                        </div>
                      </div>
                      <input 
                        type="time" 
                        v-model="form.start_time" 
                        min="04:00"
                        max="20:00"
                        required 
                        class="custom-input-field code-icon-clock"
                      >
                    </div>
                    <div class="input-group">
                      <div class="label-container">
                        <label class="form-label">End Time *</label>
                        <div class="info-btn-wrapper">
                          <button type="button" class="info-btn" @click.stop="toggleHelp('endTime')">
                            i
                          </button>
                          <transition name="fade-pop">
                            <div v-if="helpState.endTime" class="simple-popup">
                              Must be after the start time and set between 04:00 AM and 08:00 PM.
                            </div>
                          </transition>
                        </div>
                      </div>
                      <input 
                        type="time" 
                        v-model="form.end_time" 
                        min="04:00"
                        max="20:00"
                        required 
                        class="custom-input-field code-icon-clock"
                      >
                    </div>
                  </div>

                  <div class="input-group">
                    <div class="label-container">
                      <label class="form-label" for="target_participants">Target Participants *</label>
                      <div class="info-btn-wrapper">
                        <button type="button" class="info-btn" @click.stop="toggleHelp('targetParticipants')">
                          i
                        </button>
                        <transition name="fade-pop">
                          <div v-if="helpState.targetParticipants" class="simple-popup">
                            Participants must be 50 and above for approval of activity design.
                          </div>
                        </transition>
                      </div>
                    </div>
                    <input
                      id="target_participants"
                      type="number"
                      v-model="form.target_participants"
                      required
                      class="custom-input-field"
                      placeholder="Enter total participants"
                      min="50"
                    >
                  </div>

                  <!-- Upload Activity Design (Moved inside left column) -->
                  <div class="attachment-section-container">
                    <label class="form-label">Upload Activity Design (PDF) *</label>
                    <div class="attachment-display-grid">
                      <div class="attachment-upload-column">
                        <div class="upload-dropzone" @click="$refs.fileInput.click()">
                          <input ref="fileInput" type="file" @change="handleFileUpload" accept=".pdf" required class="file-input-hidden" />
                          <span class="upload-icon">📤</span>
                          <p class="upload-text">Upload Activity Design Document</p>
                          <p class="upload-hint">PDF format (Max 10MB)</p>
                        </div>
                      </div>
                      <div class="attachment-preview-column">
                        <div v-if="designFile" class="uploaded-file-display">
                          <div class="uploaded-file-tag">
                            <span class="uploaded-file-name">📄 {{ designFile.name }}</span>
                            <button type="button" @click="removeFile" class="remove-file-btn">Remove</button>
                          </div>
                        </div>
                        <p v-else class="no-file-uploaded-text">No file uploaded yet.</p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-column-right">
                  <div class="budget-section">
                    <label class="form-label">Proposed Budgetary Requirements *</label>
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
                              <div v-if="form.target_participants < 50 && form.target_participants !== ''" class="budget-warning-inline">
                                ⚠️ Participants &lt; 50. GAD cannot fund meals.
                              </div>
                            </div>
                            <div class="budget-item-value">
                              <span class="budget-currency-symbol">₱</span>
                              <input 
                                type="number" 
                                v-model="form.budget_items[0].total" 
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
                              <div v-if="form.target_participants < 50 && form.target_participants !== ''" class="budget-warning-inline">
                                ⚠️ Participants &lt; 50. GAD cannot fund snacks.
                              </div>
                            </div>
                            <div class="budget-item-value">
                              <span class="budget-currency-symbol">₱</span>
                              <input 
                                type="number" 
                                v-model="form.budget_items[1].total" 
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
                                v-model="form.budget_items[2].total" 
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
                                v-model="form.budget_items[3].total" 
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
                                v-model="form.budget_items[4].total" 
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
                              <div v-if="form.budget_items[8].total > 20000" class="budget-error-inline">
                                ⚠️ Cannot exceed ₱20,000 limit.
                              </div>
                            </div>
                            <div class="budget-item-value">
                              <span class="budget-currency-symbol">₱</span>
                              <input 
                                type="number" 
                                v-model="form.budget_items[8].total" 
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
                                v-model="form.budget_items[5].total" 
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
                                v-model="form.budget_items[6].total" 
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
                                v-model="form.budget_items[7].total" 
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
                                <span class="others-total-badge">₱{{ Number(form.budget_items[9].total || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</span>
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
                        ₱{{ Number(form.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>



              <div class="form-actions">
                <button 
                  type="button"
                  @click="goBack" 
                  class="back-button"
                >
                  ← Back
                </button>
                <button 
                  type="submit" 
                  class="submit-action-btn"
                >
                  Submit Design →
                </button>
              </div>
            </form>
          </div>
        </div>
      </main>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Swal from 'sweetalert2';
import api from '../../api';

const router = useRouter();
const route = useRoute();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const getTodayDate = () => {
  const d = new Date();
  d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
  return d.toISOString().split('T')[0];
};
const todayDate = ref(getTodayDate());

const helpState = ref({
  startDate: false,
  endDate: false,
  startTime: false,
  endTime: false,
  targetParticipants: false
});

const toggleHelp = (field) => {
  const currentVal = helpState.value[field];
  Object.keys(helpState.value).forEach(key => {
    helpState.value[key] = false;
  });
  helpState.value[field] = !currentVal;
};

const closeAllHelp = () => {
  Object.keys(helpState.value).forEach(key => {
    helpState.value[key] = false;
  });
};

const philippineHolidays = ref([]);

const fetchHolidays = async () => {
  try {
    const year = new Date().getFullYear();
    const response = await fetch(`https://date.nager.at/api/v3/PublicHolidays/${year}/PH`);
    const data = await response.json();
    philippineHolidays.value = data.map(h => h.date);
  } catch (error) {
    console.error('Failed to fetch holidays:', error);
  }
};

const holidays = philippineHolidays;

const isWeekend = (dateString) => {
  const date = new Date(dateString + 'T00:00:00');
  const dayOfWeek = date.getDay();
  return dayOfWeek === 0 || dayOfWeek === 5 || dayOfWeek === 6;
};

const isHoliday = (dateString) => {
  return holidays.value.includes(dateString);
};

const isCurrentYear = (dateString) => {
  const date = new Date(dateString + 'T00:00:00');
  const currentYear = new Date().getFullYear();
  return date.getFullYear() === currentYear;
};

const isValidActivityDate = (dateString) => {
  if (!isCurrentYear(dateString)) {
    const currentYear = new Date().getFullYear();
    return { valid: false, reason: `Activities can only be scheduled in ${currentYear}. Please select a date within the current year.` };
  }
  if (isWeekend(dateString)) {
    return { valid: false, reason: 'Activities cannot be scheduled on Friday, Saturday, or Sunday.' };
  }
  if (isHoliday(dateString)) {
    return { valid: false, reason: 'This date is a holiday. Please select another date.' };
  }
  return { valid: true, reason: '' };
};

const isValidActivityDuration = (startDateString, endDateString) => {
  if (!startDateString || !endDateString) {
    return { valid: true, reason: '' };
  }
  const startDate = new Date(startDateString + 'T00:00:00');
  const endDate = new Date(endDateString + 'T00:00:00');
  
  if (endDate < startDate) {
    return { valid: false, reason: 'End date cannot be before start date.' };
  }
  
  const diffTime = endDate - startDate;
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  
  if (diffDays > 7) {
    return { valid: false, reason: 'The duration of the activity must not exceed a week (maximum of 7 calendar days).' };
  }
  
  return { valid: true, reason: '' };
};

const venues = ref([]);
const customVenue = ref('');
const formTypes = ref([]);
const GADMandates = ref([]);
const genderIssues = ref([]);
const ActClassification = ref([]); 
const customMandate = ref('');
const customGenderIssue = ref('');

const form = ref({
  form_type: '',
  nature: '',
  activity_classification_id: '',
  gad_mandate_id: '',
  gender_issue_id: '',
  activity_title: '',
  start_date: '',
  end_date: '',
  start_time: '',
  end_time: '',
  venue: '',
  target_participants: '',
  proposed_budget: 0,
  budget_items: [
    { name: 'Meals', total: '' },
    { name: 'Snacks', total: '' },
    { name: 'Function Room/Venue', total: '' },
    { name: 'Accommodation', total: '' },
    { name: 'Equipment Rental', total: '' },
    { name: 'Professional Fee/Honoria', total: '' },
    { name: 'Token/s', total: '' },
    { name: 'Materials and Supplies', total: '' },
    { name: 'Transportation', total: '' },
    { name: 'Others', total: '' }
  ]
});

const designFile = ref(null);
const fileInput = ref(null);

const handleFileUpload = (event) => {
  if (event.target.files.length > 0) {
    designFile.value = event.target.files[0];
  }
};

const removeFile = () => {
  designFile.value = null;
  if (fileInput.value) fileInput.value.value = '';
};

const formatBudgetName = (name) => {
  if (!name) return '';
  return name.replace(/(\(.*\))/g, '<span class="budget-item-subtext">$1</span>');
};

const fetchVenues = async () => {
  try {
    const response = await api.get('venues');
    if (response.data && response.data.success) {
      venues.value = response.data.data || [];
    }
  } catch (error) {
    console.error('Error fetching venues:', error);
  }
};

const fetchFormTypes = async () => {
  try {
    const res = await api.get('get-form-types');
    formTypes.value = res.data;
  } catch (error) {
    console.error('Error fetching form types:', error);
  }
};

const fetchActivityClassifications = async () => {
  try {
    const res = await api.get('get-activity-classifications');
    ActClassification.value = res.data;
  } catch (error) {
    console.error('Error fetching activity classifications:', error);
  }
};

const fetchGADMandates = async () => {
  try {
    const res = await api.get('get-gad-mandates');
    GADMandates.value = res.data;
  } catch (error) {
    console.error('Error fetching GAD mandates:', error);
  }
};

const fetchGenderIssues = async (mandateId) => {
  if (!mandateId || mandateId === 'Other') {
    genderIssues.value = [];
    return;
  }
  try {
    const res = await api.get(`get-gender-issues/${mandateId}`);
    genderIssues.value = res.data;
  } catch (error) {
    console.error('Error fetching gender issues:', error);
  }
};

watch(() => form.value.gad_mandate_id, (newVal) => {
  form.value.gender_issue_id = '';
  fetchGenderIssues(newVal);
});

watch(() => form.value.budget_items, (newItems) => {
  const total = newItems.reduce((sum, item) => sum + (Number(item.total) || 0), 0);
  form.value.proposed_budget = total;
}, { deep: true });

watch(() => form.value.start_date, (newDate) => {
  if (newDate) {
    const validation = isValidActivityDate(newDate);
    if (!validation.valid) {
      Swal.fire({
        icon: 'warning',
        title: 'Invalid Date',
        text: validation.reason,
        confirmButtonColor: '#b979cc'
      });
      form.value.start_date = '';
      return;
    }
    if (form.value.end_date) {
      const durationValidation = isValidActivityDuration(newDate, form.value.end_date);
      if (!durationValidation.valid) {
        Swal.fire({
          icon: 'warning',
          title: 'Invalid Duration',
          text: durationValidation.reason,
          confirmButtonColor: '#b979cc'
        });
        form.value.start_date = '';
      }
    }
  }
});

watch(() => form.value.end_date, (newDate) => {
  if (newDate) {
    const validation = isValidActivityDate(newDate);
    if (!validation.valid) {
      Swal.fire({
        icon: 'warning',
        title: 'Invalid Date',
        text: validation.reason,
        confirmButtonColor: '#b979cc'
      });
      form.value.end_date = '';
      return;
    }
    if (form.value.start_date) {
      const durationValidation = isValidActivityDuration(form.value.start_date, newDate);
      if (!durationValidation.valid) {
        Swal.fire({
          icon: 'warning',
          title: 'Invalid Duration',
          text: durationValidation.reason,
          confirmButtonColor: '#b979cc'
        });
        form.value.end_date = '';
      }
    }
  }
});

// Computed Properties for Auto-calculation
const computedDays = computed(() => {
  if (!form.value.start_date || !form.value.end_date) return 1;
  const start = new Date(form.value.start_date);
  const end = new Date(form.value.end_date);
  if (isNaN(start.getTime()) || isNaN(end.getTime())) return 1;
  const diffTime = end.getTime() - start.getTime();
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
  return diffDays > 0 ? diffDays : 1;
});

const isOutsideBsu = computed(() => {
  return form.value.venue === 'Other';
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
  [mealsSelected, () => form.value.target_participants, computedDays, isOutsideBsu],
  () => {
    const item = form.value.budget_items.find(i => i.name === 'Meals');
    if (item) {
      const mealsCount = (mealsSelected.value.breakfast ? 1 : 0) + (mealsSelected.value.lunch ? 1 : 0) + (mealsSelected.value.dinner ? 1 : 0);
      const mealsRate = isOutsideBsu.value ? 350 : 220;
      const pax = Number(form.value.target_participants) || 0;
      const calculated = pax >= 50 ? (mealsCount * mealsRate * pax * computedDays.value) : 0;
      item.total = calculated || '';
    }
  },
  { deep: true }
);

watch(
  [snacksSelected, () => form.value.target_participants, computedDays, isOutsideBsu],
  () => {
    const item = form.value.budget_items.find(i => i.name === 'Snacks');
    if (item) {
      const snacksCount = (snacksSelected.value.am ? 1 : 0) + (snacksSelected.value.pm ? 1 : 0);
      const snacksRate = isOutsideBsu.value ? 150 : 80;
      const pax = Number(form.value.target_participants) || 0;
      const calculated = pax >= 50 ? (snacksCount * snacksRate * pax * computedDays.value) : 0;
      item.total = calculated || '';
    }
  },
  { deep: true }
);

watch(pfPax, (newPax) => {
  const item = form.value.budget_items.find(i => i.name === 'Professional Fee/Honoria');
  if (item) {
    item.total = (Number(newPax) * 2258.25) || '';
  }
});

watch(tokensPax, (newPax) => {
  const item = form.value.budget_items.find(i => i.name === 'Token/s');
  if (item) {
    item.total = (Number(newPax) * 1000) || '';
  }
});

watch(() => form.value.target_participants, (newPax) => {
  const item = form.value.budget_items.find(i => i.name === 'Materials and Supplies');
  if (item) {
    item.total = (Number(newPax) * 1000) || '';
  }
});

watch(
  othersList,
  (newList) => {
    const item = form.value.budget_items.find(i => i.name === 'Others');
    if (item) {
      const sum = newList.reduce((sum, i) => sum + (Number(i.amount) || 0), 0);
      item.total = sum || '';
    }
  },
  { deep: true }
);

const submitActivityDesign = async () => {
  // Validate start date
  const startValidation = isValidActivityDate(form.value.start_date);
  if (!startValidation.valid) {
    Swal.fire({
      icon: 'warning',
      title: 'Invalid Start Date',
      text: startValidation.reason,
      confirmButtonColor: '#b979cc'
    });
    return;
  }

  // Validate end date
  const endValidation = isValidActivityDate(form.value.end_date);
  if (!endValidation.valid) {
    Swal.fire({
      icon: 'warning',
      title: 'Invalid End Date',
      text: endValidation.reason,
      confirmButtonColor: '#b979cc'
    });
    return;
  }

  // Validate activity duration (max 7 days)
  const durationValidation = isValidActivityDuration(form.value.start_date, form.value.end_date);
  if (!durationValidation.valid) {
    Swal.fire({
      icon: 'warning',
      title: 'Invalid Duration',
      text: durationValidation.reason,
      confirmButtonColor: '#b979cc'
    });
    return;
  }

  try {
    const formData = new FormData();
    
    formData.append('form_type', form.value.form_type || form.value.nature);
    formData.append('activity_classification_id', form.value.activity_classification_id);
    formData.append('gad_mandate_id', form.value.gad_mandate_id);
    formData.append('gender_issue_id', form.value.gender_issue_id);
    
    if (form.value.gad_mandate_id === 'Other') {
      formData.append('custom_gad_mandate', customMandate.value);
    }
    if (form.value.gender_issue_id === 'Other') {
      formData.append('custom_gender_issue', customGenderIssue.value);
    }

    formData.append('activity_title', form.value.activity_title);
    formData.append('start_date', form.value.start_date);
    formData.append('end_date', form.value.end_date);
    formData.append('start_time', form.value.start_time);
    formData.append('end_time', form.value.end_time);
    formData.append('user_id', user.value.id || user.value.user_id);
    if (form.value.venue === 'Other') {
      formData.append('venue_id', 'Other');
      formData.append('custom_venue', customVenue.value);
    } else {
      formData.append('venue_id', form.value.venue);
    }
    formData.append('target_participants', form.value.target_participants);
    formData.append('proposed_budget', form.value.proposed_budget);
    
    const transItem = form.value.budget_items.find(i => i.name === 'Transportation');
    if (transItem && Number(transItem.total) > 20000) {
      Swal.fire({
        icon: 'warning',
        title: 'Limit Exceeded',
        text: 'Transportation budget cannot exceed the maximum limit of ₱20,000.',
        confirmButtonColor: '#b979cc'
      });
      return;
    }

    const budgetObj = {
      meals_and_snacks: (Number(form.value.budget_items.find(i => i.name === 'Meals')?.total || 0) + Number(form.value.budget_items.find(i => i.name === 'Snacks')?.total || 0)),
      function_room_venue: Number(form.value.budget_items.find(i => i.name === 'Function Room/Venue')?.total || 0),
      accommodation: Number(form.value.budget_items.find(i => i.name === 'Accommodation')?.total || 0),
      equipment_rental: Number(form.value.budget_items.find(i => i.name === 'Equipment Rental')?.total || 0),
      professional_fee_honoria: Number(form.value.budget_items.find(i => i.name === 'Professional Fee/Honoria')?.total || 0),
      tokens: Number(form.value.budget_items.find(i => i.name === 'Token/s')?.total || 0),
      materials_and_supplies: Number(form.value.budget_items.find(i => i.name === 'Materials and Supplies')?.total || 0) + Number(form.value.budget_items.find(i => i.name === 'Others')?.total || 0),
      transportation: Number(form.value.budget_items.find(i => i.name === 'Transportation')?.total || 0)
    };

    formData.append('budget_items', JSON.stringify(budgetObj));

    if (designFile.value) {
      formData.append('design_file', designFile.value);
    }

    const response = await api.post('submit-activity-design', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    if (response.data.success) {
      Swal.fire({
        icon: 'success',
        title: 'Submitted Successfully!',
        text: 'Activity Design submitted successfully!',
        confirmButtonColor: '#b979cc'
      }).then(() => {
        router.push('/staff/ad-list');
      });
    }
  } catch (error) {
    console.error('Submission error:', error);
    Swal.fire({
      icon: 'error',
      title: 'Submission Failed',
      text: 'Failed to submit activity design. Please double check all details.',
      confirmButtonColor: '#b979cc'
    });
  }
};

const goBack = () => {
  router.push('/staff/submit');
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

onMounted(() => {
  if (!user.value.id || user.value.role !== 'gad_staff') {
    router.push('/login');
  }
  fetchFormTypes();
  fetchActivityClassifications();
  fetchGADMandates();
  fetchVenues();
  fetchHolidays();
  document.addEventListener('click', closeAllHelp);
});

onUnmounted(() => {
  document.removeEventListener('click', closeAllHelp);
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

.text-sm { font-size: 14px; }
.text-3xl { font-size: 26px; }

.form-main-layout {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.main-content-container {
  max-width: 1280px;
  margin-left: auto;
  margin-right: auto;
  width: 100%;
}

.form-header {
  margin-bottom: 32px;
}

.form-main-title {
  font-size: 26px;
  font-weight: 800;
  letter-spacing: -0.025em;
  color: #16213e;
  letter-spacing: -0.02em;
}

.form-description {
  font-size: 14px;
  color: #64748b;
  margin-top: 6px;
}

.form-grid-main {
  display: grid;
  grid-template-columns: repeat(1, minmax(0, 1fr));
  gap: 30px;
}
@media (min-width: 1024px) {
  .form-grid-main {
    grid-template-columns: 1fr 1fr;
  }
}

.form-column-left, .form-column-right {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-column-left {
  border-right: 1px solid rgba(185, 121, 204, 0.2);
  padding-right: 20px;
}
  
.form-section-spacing {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-sub-grid {
  display: grid;
  grid-template-columns: repeat(1, minmax(0, 1fr));
  gap: 20px;
}
@media (min-width: 768px) {
  .form-sub-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}

.input-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #b979cc;
}

.form-container-box {
  background: linear-gradient(145deg, #1a1a2e 0%, #16213e 100%);
  border: 1px solid rgba(185, 121, 204, 0.2);
  border-radius: 20px;
  padding: 32px;
  box-shadow: 0 20px 40px rgba(10, 10, 20, 0.4);
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
  font-size: 12px;
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

.upload-dropzone {
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

.upload-dropzone:hover {
  border-color: #b979cc;
  background: rgba(185, 121, 204, 0.06);
}

.upload-icon {
  font-size: 26px;
  margin-bottom: 8px;
  transition: transform 0.2s ease;
}
.upload-dropzone:hover .upload-icon {
  transform: scale(1.1);
}

.upload-text {
  font-size: 14px;
  font-weight: 600;
  color: #ffffff;
  text-align: center;
  transition: color 0.2s ease;
}
.upload-dropzone:hover .upload-text {
  color: #b979cc;
}

.upload-hint {
  font-size: 12px;
  color: #64748b;
  margin-top: 4px;
}

.uploaded-file-display {
  margin-top: 16px;
  width: 100%;
}

.attachment-section-container {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 10px;
}

.attachment-display-grid {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.attachment-preview-column {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 60px;
  border: 1px dashed rgba(185, 121, 204, 0.15);
  border-radius: 12px;
  padding: 12px;
  background: rgba(185, 121, 204, 0.02);
}

.no-file-uploaded-text {
  color: #94a3b8;
  font-size: 14px;
  text-align: center;
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

.uploaded-file-name {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.remove-file-btn {
  color: #f472b6;
  font-weight: 700;
  font-size: 14px;
  margin-left: 8px;
  flex-shrink: 0;
}
.remove-file-btn:hover {
  color: #f43f5e;
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

.label-container {
  display: flex;
  align-items: center;
  gap: 6px;
}

.info-btn {
  background: rgba(185, 121, 204, 0.08);
  border: 1px solid rgba(185, 121, 204, 0.35);
  color: #b979cc;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  font-size: 10px;
  font-weight: bold;
  font-family: serif;
  line-height: 1;
  transition: all 0.25s ease;
}

.info-btn:hover {
  background: #b979cc;
  color: #16213e;
  border-color: #b979cc;
  transform: scale(1.15);
  box-shadow: 0 0 8px rgba(185, 121, 204, 0.4);
}

.info-btn-wrapper {
  position: relative;
  display: inline-flex;
  align-items: center;
}

.simple-popup {
  position: absolute;
  bottom: calc(100% + 10px);
  left: 50%;
  transform: translateX(-50%);
  z-index: 1000;
  background: #1a1a2e;
  border: 1px solid #b979cc;
  border-radius: 8px;
  padding: 10px 14px;
  color: #ffffff;
  font-size: 12px;
  width: 240px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
  line-height: 1.45;
  pointer-events: auto;
  text-transform: none;
  white-space: normal;
}

.simple-popup::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  border-width: 6px;
  border-style: solid;
  border-color: #1a1a2e transparent transparent transparent;
}

.simple-popup::before {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  border-width: 7px;
  border-style: solid;
  border-color: #b979cc transparent transparent transparent;
  z-index: -1;
}

.fade-pop-enter-active,
.fade-pop-leave-active {
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.fade-pop-enter-from,
.fade-pop-leave-to {
  opacity: 0;
  transform: translate(-50%, 8px) scale(0.95);
}

.fade-pop-enter-to,
.fade-pop-leave-from {
  opacity: 1;
  transform: translate(-50%, 0) scale(1);
}
</style>