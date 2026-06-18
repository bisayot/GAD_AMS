<template>
      <main class="twg-view-wrapper">
        <div class="main-content-container-ar">
          <div class="form-header-ar">
            <h1 class="form-main-title">Submit Accomplishment Report</h1>
            <p class="form-description-ar">See the remarks below and apply the said changes.</p>
          </div>

          <div class="form-container-box">
            <form @submit.prevent="submitReport" class="form-main-layout-ar">
              
              <div class="ad-selection-wrapper">
                <div class="input-group-ar control-select-group">
                  <label class="form-label-ar">Activity Design Control Number *</label>
                  <select 
                    v-model="form.control_number" 
                    required 
                    class="custom-input-field select-arrow-fix control-select-large"
                  >
                    <option value="" class="dark-option">Select approved activity design...</option>
                    <option v-if="loadingControls" value="" disabled class="dark-option">Loading...</option>
                    <option v-for="control in approvedControls" :key="control.control_number" :value="control.control_number" class="dark-option">
                      {{ control.control_number }} - {{ control.activity_title }}
                    </option>
                    <option v-if="!loadingControls && approvedControls.length === 0" value="" disabled class="dark-option">No approved control numbers found</option>
                  </select>
                </div>

                <transition name="fade">
                  <div v-if="selectedAD" class="ad-details-card horizontal">
                    <h3 class="ad-details-title">Approved Activity Design Details</h3>
                    <div class="ad-details-grid horizontal-grid">
                      <div class="ad-detail-item span-full">
                        <span class="ad-detail-label">Title</span>
                        <span class="ad-detail-value">{{ selectedAD.activity_title }}</span>
                      </div>
                      <div class="ad-detail-item">
                        <span class="ad-detail-label">Form Type</span>
                        <span class="ad-detail-value">{{ selectedAD.form_type }}</span>
                      </div>
                      <div class="ad-detail-item">
                        <span class="ad-detail-label">GPB/GAD ID</span>
                        <span class="ad-detail-value">{{ selectedAD.gpb_id || 'N/A' }}</span>
                      </div>
                      <div class="ad-detail-item">
                        <span class="ad-detail-label">Venue</span>
                        <span class="ad-detail-value">{{ selectedAD.venue_name || selectedAD.venue }}</span>
                      </div>
                      <div class="ad-detail-item">
                        <span class="ad-detail-label">Target Participants</span>
                        <span class="ad-detail-value">{{ selectedAD.target_participants }}</span>
                      </div>
                      <div class="ad-detail-item">
                        <span class="ad-detail-label">Date</span>
                        <span class="ad-detail-value">{{ selectedAD.start_date }} to {{ selectedAD.end_date }}</span>
                      </div>
                      <div class="ad-detail-item">
                        <span class="ad-detail-label">Time</span>
                        <span class="ad-detail-value">{{ selectedAD.start_time }} to {{ selectedAD.end_time }}</span>
                      </div>
                      <div class="ad-detail-item">
                        <span class="ad-detail-label">Proposed Budget</span>
                        <span class="ad-detail-value">PHP {{ Number(selectedAD.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</span>
                      </div>
                      <div class="ad-detail-item">
                        <span class="ad-detail-label">Assessment Date</span>
                        <span class="ad-detail-value">{{ selectedAD.assessment_date || 'N/A' }}</span>
                      </div>
                      <div class="ad-detail-item span-full" v-if="selectedAD.remarks">
                        <span class="ad-detail-label">Reviewer Remarks</span>
                        <div class="ad-remarks-box">{{ selectedAD.remarks }}</div>
                      </div>
                      <div class="ad-detail-item span-full" v-if="selectedAD.attachment">
                        <span class="ad-detail-label">Uploaded File</span>
                        <a :href="getFileUrl(selectedAD.attachment)" target="_blank" class="ad-attachment-link">
                          📄 {{ selectedAD.attachment }}
                        </a>
                      </div>

                      <div class="ad-detail-item span-full" v-if="parsedBudget.length > 0">
                        <span class="ad-detail-label" style="margin-bottom: 0.5rem; display: block;">Approved Budget Breakdown</span>
                        <div class="budget-table-wrapper" style="margin: 0;">
                          <table class="budget-table">
                            <thead class="budget-table-header">
                              <tr>
                                <th class="table-header-cell">Budget Item</th>
                                <th class="table-header-cell budget-col-total">Amount (PHP)</th>
                              </tr>
                            </thead>
                            <tbody class="budget-table-body">
                              <tr v-for="(item, index) in parsedBudget" :key="index" class="budget-table-row">
                                <td class="budget-item-name" style="color: #e2e8f0; font-weight: 500;" v-html="formatBudgetName(item.name)"></td>
                                <td class="budget-item-input-cell" style="text-align: right; padding-right: 1.5rem; color: #e2e8f0; font-weight: 500;">
                                  {{ Number(item.total || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}
                                </td>
                              </tr>
                            </tbody>
                            <tfoot class="budget-table-footer">
                              <tr>
                                <td class="grand-total-label">Grand Total (PHP)</td>
                                <td class="grand-total-value">
                                  {{ Number(selectedAD.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}
                                </td>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>

                    </div>
                  </div>
                </transition>
              </div>

              <hr class="section-divider-ar" v-if="selectedAD" />

              <div class="ar-submission-body" v-show="selectedAD">
                <div class="form-grid-main-ar">
                <div class="form-column-left-ar">
                  <h3 class="ar-section-title">Actual Accomplishment Details</h3>
                  <div class="input-group-ar">
                    <label class="form-label-ar">Actual Activity Title *</label>
                    <textarea 
                      v-model="form.activity_title" 
                      required 
                      rows="2" 
                      class="custom-input-field textarea-no-resize"
                      placeholder="Enter the complete actual title of the activity"
                    ></textarea>
                  </div>

                  <div class="form-sub-grid-ar">
                    <div class="input-group-ar">
                      <label class="form-label-ar">Start Date of Implementation *</label>
                      <input 
                        type="date" 
                        v-model="form.start_date" 
                        :min="todayDate"
                        required 
                        class="custom-input-field code-icon-calendar"
                      >
                    </div>
                    <div class="input-group-ar">
                      <label class="form-label-ar">End Date of Implementation *</label>
                      <input 
                        type="date" 
                        v-model="form.end_date" 
                        :min="todayDate"
                        required 
                        class="custom-input-field code-icon-calendar"
                      >
                    </div>
                  </div>

                  <div class="form-sub-grid-ar">
                    <div class="input-group-ar">
                      <label class="form-label-ar">Start Time *</label>
                      <input 
                        type="time" 
                        v-model="form.start_time" 
                        required 
                        class="custom-input-field code-icon-clock"
                      >
                    </div>
                    <div class="input-group-ar">
                      <label class="form-label-ar">End Time *</label>
                      <input 
                        type="time" 
                        v-model="form.end_time" 
                        required 
                        class="custom-input-field code-icon-clock"
                      >
                    </div>
                  </div>

                  <div class="input-group-ar">
                    <label class="form-label-ar">Venue *</label>
                    <input 
                      type="text" 
                      v-model="form.venue" 
                      required 
                      class="custom-input-field"
                      placeholder="e.g., Convention Center, Main Hall"
                    >
                  </div>

                  <div class="input-group-ar">
                    <label class="form-label-ar">Number of Attendees *</label>
                    <input 
                      type="number" 
                      v-model="form.attendees" 
                      required 
                      min="0"
                      class="custom-input-field input-disabled-ar"
                      placeholder="0"
                      readonly
                    >
                  </div>

                  <div class="form-sub-grid-ar">
                    <div class="input-group-ar">
                      <label class="form-label-ar">Male Participants *</label>
                      <input 
                        type="number" 
                        v-model="form.male" 
                        required 
                        min="0"
                        class="custom-input-field"
                        placeholder="0"
                      >
                    </div>
                    <div class="input-group-ar">
                      <label class="form-label-ar">Female Participants *</label>
                      <input 
                        type="number" 
                        v-model="form.female" 
                        required 
                        min="0"
                        class="custom-input-field"
                        placeholder="0"
                      >
                    </div>
                  </div>
                </div>

                <div class="form-column-right-ar">
                  <div class="budget-section">
                    <label class="form-label-ar">Actual Budget Expenditure *</label>
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
                      <input 
                            type="number" 
                            v-model="item.total" 
                            class="budget-input-field budget-total-field"
                            placeholder="0.00"
                            min="0"
                            step="0.01"
                          />
                            </td>
                          </tr>
                        </tbody>
                        <tfoot class="budget-table-footer">
                          <tr>
                            <td colspan="1" class="grand-total-label">Grand Total (PHP)</td>
                            <td class="grand-total-value">
                              {{ Number(form.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                            </td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>

                  <div class="evaluation-section-ar">
                    <label class="form-label-ar">Evaluation Results *</label>
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
                              <input 
                                type="number" 
                                v-model="item.rating" 
                                min="1" 
                                max="5" 
                                step="0.01" 
                                required
                                class="evaluation-input-field-ar"
                                placeholder="0.00"
                              />
                            </td>
                            <td class="evaluation-interpretation-cell-ar">
                              <span :class="['interpretation-tag-ar', getInterpretationClass(item.rating)]">
                                {{ getInterpretation(item.rating) }}
                              </span>
                            </td>
                          </tr>
                        </tbody>
                        <tfoot class="evaluation-table-footer-ar">
                          <tr>
                            <td class="total-avg-label-ar">Total Average Rating</td>
                            <td class="total-avg-value-ar">{{ form.rating }}</td>
                            <td class="total-avg-interpretation-ar">
                              <span :class="['interpretation-tag-ar', getInterpretationClass(form.rating)]">
                                {{ getInterpretation(form.rating) }}
                              </span>
                            </td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <div class="attachment-section-container-ar">
                <label class="form-label-ar">Upload Documents (PDF/ZIP - Multiple files allowed) *</label>
                <div class="attachment-display-grid-ar">
                  <div class="attachment-upload-column-ar">
                    <div class="upload-dropzone-box" @click="$refs.fileInput.click()">
                      <input 
                        ref="fileInput" 
                        type="file" 
                        @change="handleFileUpload" 
                        accept=".pdf" 
                        required 
                        class="file-input-hidden" 
                        multiple 
                      />
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
                    <p v-else class="no-file-uploaded-text">No files uploaded yet.</p>
                  </div>
                </div>
              </div>

              </div><!-- End ar-submission-body -->

              <div class="form-actions" v-show="selectedAD">
                <button 
                  type="button"
                  @click="goBack" 
                  class="back-button"
                >
                  &larr; Back
                </button>
                <button 
                  type="submit" 
                  class="submit-action-btn"
                >
                  Submit Report &rarr;
                </button>
              </div>
            </form>
          </div>
        </div>
      </main>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Swal from 'sweetalert2';
import api from '../../api';

const goBack = () => {
  router.push('/college/submitted-list');
};

const router = useRouter();
const route = useRoute();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const menuItems = computed(() => {
  if (route.path.includes('/college')) return collegeMenu;
  return [];
});

const getTodayDate = () => {
  const d = new Date();
  d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
  return d.toISOString().split('T')[0];
};
const todayDate = ref(getTodayDate());

const venues = ref([]);
const customVenue = ref('');

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
const selectedAD = ref(null);

const parsedBudget = computed(() => {
  if (!selectedAD.value || !selectedAD.value.budget_items || selectedAD.value.budget_items.length === 0) return [];
  const b = selectedAD.value.budget_items[0];
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

const getFileUrl = (filename) => {
  if (!filename) return '#';
  const base = (import.meta.env.VITE_API_BASE_URL ? import.meta.env.VITE_API_BASE_URL.replace(/\/$/, '') : 'http://localhost:8080/api');
  return `${base}/files/archived/${filename}`;
};

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
  } finally {
    loadingControls.value = false;
  }
};

watch(() => form.value.control_number, (newVal) => {
  const selected = approvedControls.value.find(c => c.control_number === newVal);
  selectedAD.value = selected || null;
  if (selected) {
    form.value.act_design_id = selected.act_design_id;
    form.value.activity_title = selected.activity_title;
    form.value.start_date = selected.start_date;
    form.value.end_date = selected.end_date;
    form.value.start_time = selected.start_time;
    form.value.end_time = selected.end_time;
    form.value.venue = selected.venue_name || selected.venue || '';
    
    // Auto-fill budget items from the approved design
    if (selected.budget_items && selected.budget_items.length > 0) {
      const b = selected.budget_items[0];
      form.value.budget_items = [
        { name: 'Meals and Snacks (AM/PM)', total: b.meals_and_snacks || '' },
        { name: 'Function Room/Venue', total: b.function_room_venue || '' },
        { name: 'Accommodation', total: b.accommodation || '' },
        { name: 'Equipment Rental', total: b.equipment_rental || '' },
        { name: 'Professional Fee/Honoraria', total: b.professional_fee_honoria || '' },
        { name: 'Tokens', total: b.tokens || '' },
        { name: 'Materials and Supplies', total: b.materials_and_supplies || '' },
        { name: 'Transportation', total: b.transportation || '' }
      ];
    }
  } else {
    form.value.act_design_id = null;
    form.value.activity_title = '';
    form.value.start_date = '';
    form.value.end_date = '';
    form.value.start_time = '';
    form.value.end_time = '';
    form.value.venue = '';
    // Reset budget items
    form.value.budget_items = [
      { name: 'Meals and Snacks (AM/PM)', total: '' },
      { name: 'Function Room/Venue', total: '' },
      { name: 'Accommodation', total: '' },
      { name: 'Equipment Rental', total: '' },
      { name: 'Professional Fee/Honoraria', total: '' },
      { name: 'Tokens', total: '' },
      { name: 'Materials and Supplies', total: '' },
      { name: 'Transportation', total: '' }
    ];
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
  try {
    const formData = new FormData();
    
    
      formData.append('venue', form.value.venue);

      const budgetMap = {"Meals and Snacks (AM/PM)":"meals_and_snacks","Function Room/Venue":"function_room_venue","Accommodation":"accommodation","Equipment Rental":"equipment_rental","Professional Fee/Honoria":"professional_fee_honoria","Token/s":"tokens","Materials and Supplies":"materials_and_supplies","Transportation":"transportation"};
      const budgetObj = {};
      form.value.budget_items.forEach(item => {
        budgetObj[budgetMap[item.name]] = item.total || 0;
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
        evalObj[evalMap[item.area]] = item.rating || 0;
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
    
    const response = await api.post('submit-activity-report', formData, {
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
    console.error('Error submitting report:', error);
    let errorMsg = 'Failed to submit report. Please try again.';
    if (error.response?.data?.errors) {
      errorMsg = Object.values(error.response.data.errors).join('\n');
    } else if (error.response?.data?.message) {
      errorMsg = error.response.data.message;
    }
    alert(errorMsg);
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

onMounted(() => {
  if (!user.value.id) {
    router.push('/login');
  } else {
    fetchApprovedControls();
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
  border-radius: 6px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.5);
  text-align: center;
}

.ad-details-card {
  background: rgba(153, 13, 209, 0.05);
  border: 1px solid rgba(185, 121, 204, 0.2);
  border-radius: 12px;
  padding: 1.25rem;
  margin-top: 0.5rem;
  margin-bottom: 1.5rem;
}

.ad-details-title {
  color: #b979cc;
  font-size: 0.95rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 1rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid rgba(185, 121, 204, 0.1);
}

.ad-selection-wrapper {
  margin-bottom: 0.5rem;
}

.control-select-large {
  font-size: 1.1rem;
  padding: 14px 16px;
  background: rgba(0, 0, 0, 0.4);
  border: 1px solid rgba(185, 121, 204, 0.4);
}

.section-divider-ar {
  border: 0;
  height: 1px;
  background: linear-gradient(90deg, rgba(185, 121, 204, 0), rgba(185, 121, 204, 0.5), rgba(185, 121, 204, 0));
  margin: 1.5rem 0;
}

.ar-section-title {
  color: #ffffff;
  font-size: 1.2rem;
  font-weight: 800;
  letter-spacing: -0.025em;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
}

.ar-section-title::before {
  content: '';
  display: inline-block;
  width: 4px;
  height: 18px;
  background: #990dd1;
  margin-right: 10px;
  border-radius: 2px;
}

.ad-details-card.horizontal {
  background: rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(153, 13, 209, 0.3);
}

.ad-details-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 0.75rem;
}

.horizontal-grid {
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}

.span-full {
  grid-column: 1 / -1;
}

@media (max-width: 1024px) {
  .horizontal-grid {
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 640px) {
  .horizontal-grid {
    grid-template-columns: 1fr;
  }
}

.ad-detail-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.ad-detail-label {
  font-size: 0.75rem;
  color: #94a3b8;
  font-weight: 600;
  text-transform: uppercase;
}

.ad-detail-value {
  font-size: 0.9rem;
  color: #e2e8f0;
  font-weight: 500;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
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


.ad-remarks-box {
  background: rgba(255, 255, 255, 0.05);
  border-left: 3px solid #b979cc;
  padding: 0.75rem 1rem;
  margin-top: 0.25rem;
  border-radius: 4px;
  font-size: 0.9rem;
  color: #e2e8f0;
  line-height: 1.5;
}

.ad-attachment-link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1rem;
  background: rgba(153, 13, 209, 0.15);
  border: 1px solid rgba(185, 121, 204, 0.3);
  border-radius: 8px;
  color: #b979cc;
  font-weight: 600;
  text-decoration: none;
  margin-top: 0.25rem;
  transition: all 0.2s ease;
  width: fit-content;
}

.ad-attachment-link:hover {
  background: rgba(153, 13, 209, 0.3);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(153, 13, 209, 0.2);
}

</style>