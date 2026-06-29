<template>
      <main class="twg-view-wrapper">
        <div class="main-content-container-ar">
          <div class="form-header-ar">
            <h1 class="form-main-title">Submit Accomplishment Report</h1>
            <p class="form-description-ar">Fill out the accomplishment report form below. All fields marked with * are required.</p>
          </div>

          <div class="form-container-box">
            <form @submit.prevent="submitReport" class="form-main-layout-ar">
              <div class="form-grid-main-ar">
                <div class="form-column-left-ar">
                  <div class="input-group-ar">
                    <label class="form-label-ar">Activity Design Control Number *</label>
                    <select 
                      v-model="form.control_number" 
                      required 
                      class="custom-input-field select-arrow-fix"
                    >
                      <option value="" class="dark-option">Select approved activity design...</option>
                      <option v-if="loadingControls" value="" disabled class="dark-option">Loading...</option>
                      <option v-for="control in approvedControls" :key="control.control_number" :value="control.control_number" class="dark-option">
                        {{ control.control_number }} - {{ control.activity_title }}
                      </option>
                      <option v-if="!loadingControls && approvedControls.length === 0" value="" disabled class="dark-option">No approved control numbers found</option>
                    </select>
                  </div>

                  <div class="input-group-ar">
                    <label class="form-label-ar">Activity Title *</label>
                    <textarea 
                      v-model="form.activity_title" 
                      required 
                      rows="2" 
                      class="custom-input-field textarea-no-resize"
                      placeholder="Enter the complete title of the activity"
                    ></textarea>
                  </div>

                  <div class="input-group-ar">
                    <label class="form-label-ar">Activity Classification *</label>
                    <select
                      v-model="form.classification_id"
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

                  <div class="input-group-ar">
                    <label class="form-label-ar">GAD Mandate *</label>
                    <select 
                      v-model="form.gad_mandate_id" 
                      required 
                      class="custom-input-field select-arrow-fix"
                    >
                      <option value="" disabled class="dark-option">Select Mandate</option>
                      <option 
                        v-for="mandate in GADMandates" 
                        :key="mandate.id" 
                        :value="mandate.id" 
                        class="dark-option"
                      >
                        {{ mandate.code }} - {{ mandate.title }}
                      </option>
                      <option value="Other" class="dark-option">+ New Mandate</option>
                    </select>
                    <input 
                      v-if="form.gad_mandate_id === 'Other'" 
                      v-model="customMandate" 
                      type="text" 
                      placeholder="Enter new mandate name..." 
                      class="custom-input-field" 
                      style="margin-top: 10px;" 
                      required
                    />
                  </div>

                  <div class="input-group-ar">
                    <label class="form-label-ar">Gender Issue *</label>
                    <select 
                      v-model="form.gender_issue_id" 
                      required 
                      class="custom-input-field select-arrow-fix"
                    >
                      <option value="" disabled class="dark-option">
                        {{ form.gad_mandate_id ? 'Select Gender Issue' : 'Select Mandate first' }}
                      </option>
                      <option 
                        v-for="issue in genderIssues" 
                        :key="issue.id" 
                        :value="issue.id" 
                        class="dark-option"
                      >
                        {{ issue.title }}
                      </option>
                      <option value="Other" class="dark-option">+ New Gender Issue</option>
                    </select>
                    <input 
                      v-if="form.gender_issue_id === 'Other'" 
                      v-model="customGenderIssue" 
                      type="text" 
                      placeholder="Enter new gender issue..." 
                      class="custom-input-field" 
                      style="margin-top: 10px;" 
                      required
                    />
                  </div>

                  <div class="form-sub-grid-ar">
                    <div class="input-group-ar">
                      <div class="label-container">
                        <label class="form-label-ar">Start Date of Implementation *</label>
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
                    <div class="input-group-ar">
                      <div class="label-container">
                        <label class="form-label-ar">End Date of Implementation *</label>
                        <div class="info-btn-wrapper">
                          <button type="button" class="info-btn" @click.stop="toggleHelp('endDate')">
                            i
                          </button>
                          <transition name="fade-pop">
                            <div v-if="helpState.endDate" class="simple-popup">
                              End date must not exceed a week after the start date.
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

                  <div class="form-sub-grid-ar">
                    <div class="input-group-ar">
                      <div class="label-container">
                        <label class="form-label-ar">Start Time *</label>
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
                    <div class="input-group-ar">
                      <div class="label-container">
                        <label class="form-label-ar">End Time *</label>
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

                  <div class="input-group-ar">
                    <label class="form-label-ar">Venue *</label>
                    <select 
                      v-model="form.venue_id" 
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

                  <div v-if="form.venue_id === 'Other'" class="input-group-ar">
                    <label class="form-label-ar">Specify Other Venue *</label>
                    <input 
                      type="text" 
                      v-model="customVenue" 
                      required 
                      class="custom-input-field"
                      placeholder="Enter the complete venue name"
                    >
                  </div>

                  <div class="input-group-ar">
                    <div class="label-container">
                      <label class="form-label-ar">Number of Attendees *</label>
                    </div>
                    <input 
                      type="number" 
                      v-model="form.attendees" 
                      required 
                      min="50"
                      class="custom-input-field"
                      placeholder="0"
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

                  <!-- Upload Documents (Moved inside left column) -->
                  <div class="attachment-section-container-ar" style="margin-top: 1.5rem;">
                    <label class="form-label-ar">Upload Documents (PDF/ZIP - Multiple files allowed) *</label>
                    <div class="attachment-display-grid-ar">
                      <div class="attachment-upload-column-ar">
                        <div class="upload-dropzone-box" @click="$refs.fileInput.click()">
                          <input 
                            ref="fileInput" 
                            type="file" 
                            @change="handleFileUpload" 
                            accept=".pdf,.zip" 
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
                </div>

                <div class="form-column-right-ar">
                  <div class="budget-section">
                    <label class="form-label-ar">Actual Budgetary Requirements *</label>
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
                               <div v-if="proposedBudgetItems.meals !== undefined" class="budget-item-proposed">
                                 Proposed: ₱{{ formatCurrency(proposedBudgetItems.meals) }}
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
                               <div v-if="proposedBudgetItems.snacks !== undefined" class="budget-item-proposed">
                                 Proposed: ₱{{ formatCurrency(proposedBudgetItems.snacks) }}
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
                               <div v-if="proposedBudgetItems.venue !== undefined" class="budget-item-proposed">
                                 Proposed: ₱{{ formatCurrency(proposedBudgetItems.venue) }}
                               </div>
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
                               <div v-if="proposedBudgetItems.accommodation !== undefined" class="budget-item-proposed">
                                 Proposed: ₱{{ formatCurrency(proposedBudgetItems.accommodation) }}
                               </div>
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
                               <div v-if="proposedBudgetItems.rental !== undefined" class="budget-item-proposed">
                                 Proposed: ₱{{ formatCurrency(proposedBudgetItems.rental) }}
                               </div>
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
                               <div v-if="proposedBudgetItems.transportation !== undefined" class="budget-item-proposed">
                                 Proposed: ₱{{ formatCurrency(proposedBudgetItems.transportation) }}
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
                               <div v-if="proposedBudgetItems.honoraria !== undefined" class="budget-item-proposed">
                                 Proposed: ₱{{ formatCurrency(proposedBudgetItems.honoraria) }}
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
                               <div v-if="proposedBudgetItems.tokens !== undefined" class="budget-item-proposed">
                                 Proposed: ₱{{ formatCurrency(proposedBudgetItems.tokens) }}
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
                               <div v-if="proposedBudgetItems.supplies !== undefined" class="budget-item-proposed">
                                 Proposed: ₱{{ formatCurrency(proposedBudgetItems.supplies) }}
                               </div>
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
                                 <div v-if="proposedBudgetItems.others !== undefined" class="budget-item-proposed">
                                   Proposed: ₱{{ formatCurrency(proposedBudgetItems.others) }}
                                 </div>
                               </div>
                               <div class="budget-item-value">
                                 <span class="others-total-badge">₱{{ Number(form.budget_items[9].total || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</span>
                               </div>
                             </div>
                             <div class="others-breakdown-container">
                             <div v-for="(o, oIdx) in othersList" :key="oIdx" class="others-breakdown-row-wrapper" style="margin-bottom: 12px;">
                               <div class="others-breakdown-row" style="margin-bottom: 2px;">
                                 <input type="text" v-model="o.name" placeholder="Item name" class="others-input-name" />
                                 <input type="number" v-model.number="o.amount" min="0" placeholder="₱0.00" class="others-input-amount" />
                                 <button type="button" @click="removeOtherItem(oIdx)" class="btn-remove-other" title="Remove">×</button>
                               </div>
                               <div v-if="o.proposed_amount !== undefined && o.proposed_amount > 0" class="others-item-proposed" style="font-size: 0.75rem; color: #fbbf24; font-weight: 600; padding-left: 12px; margin-top: 2px;">
                                 Proposed: ₱{{ formatCurrency(o.proposed_amount) }}
                               </div>
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
                        ₱{{ Number(form.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                      </div>
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


              <!-- Budget Exceeded Warning Card -->
              <div v-if="isExceedingLimit" class="ar-limit-warning-card">
                <span class="warning-icon">⚠️</span>
                <div class="warning-content">
                  <h4 class="warning-title">Budget Limit Exceeded</h4>
                  <p class="warning-desc">
                    The actual spending grand total of <strong>₱{{ Number(form.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</strong> exceeds the approved proposed budget of <strong>₱{{ Number(selectedProposedBudget || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</strong>.
                  </p>
                  <p class="warning-instruction">
                    Please file an Activity Design Revision to increase the budget before submitting this report, or adjust the actual spending inputs.
                  </p>
                </div>
              </div>

              <div class="form-actions-ar">
                <button 
                  type="button"
                  @click="router.back()" 
                  class="back-button"
                >
                  ← Back
                </button>
                <button 
                  type="submit" 
                  class="submit-action-btn"
                  :disabled="isExceedingLimit"
                  :class="{ 'btn-disabled': isExceedingLimit }"
                >
                  Submit Report →
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

const menuItems = computed(() => {
  if (route.path.includes('/twg')) return twgMenu;
  return [];
});

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
    philippineHolidays.value = data.map(h => h.date)
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

const form = ref({
  activity_title: '',
  control_number: '',
  activity_classification: '',
  classification_id: '',
  gad_mandate: '',
  gad_mandate_id: '',
  gender_issue: '',
  gender_issue_id: '',
  act_design_id: null,
  start_date: '',
  end_date: '',
  start_time: '',
  end_time: '',
  venue: '',
  venue_id: '',
  attendees: '',
  male: '',
  female: '', 
  proposed_budget: 0,
  budget_items: [
    { name: 'Meals', total: '' },
    { name: 'Snacks', total: '' },
    { name: 'Function Room/Venue', total: '' },
    { name: 'Accommodation', total: '' },
    { name: 'Equipment Rental', total: '' },
    { name: 'Professional Fee/Honoraria', total: '' },
    { name: 'Token/s', total: '' },
    { name: 'Materials and Supplies', total: '' },
    { name: 'Transportation', total: '' },
    { name: 'Others', total: '' }
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
    const res = await api.get(`get-control-numbers/${user.value.id}`);
    if (res.data.success) {
      approvedControls.value = res.data.data;
    }
  } catch (error) {
    console.error('Error fetching approved controls:', error);
  } finally {
    loadingControls.value = false;
  }
};

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
    const item = form.value.budget_items.find(i => i.name === 'Others');
    if (item) {
      const sum = newList.reduce((sum, i) => sum + (Number(i.amount) || 0), 0);
      item.total = sum || '';
    }
  },
  { deep: true }
);

const selectedProposedBudget = ref(0);
const proposedBudgetItems = ref({});
const venues = ref([]);
const ActClassification = ref([]);
const GADMandates = ref([]);
const genderIssues = ref([]);
const customVenue = ref('');
const customMandate = ref('');
const customGenderIssue = ref('');

const isExceedingLimit = computed(() => {
  return selectedProposedBudget.value > 0 && form.value.proposed_budget > selectedProposedBudget.value;
});

const formatCurrency = (val) => {
  if (val === undefined || val === null) return '0.00';
  return Number(val).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const getBudgetItemAmount = (items, name) => {
  if (!items) return 0;
  return items
    .filter(i => i.item_name === name)
    .reduce((sum, i) => sum + (Number(i.amount) || 0), 0);
};

const fetchVenues = async () => {
  try {
    const response = await api.get('get-venues');
    if (response.data && response.data.success) {
      venues.value = response.data.data || [];
    }
  } catch (error) {
    console.error('Error fetching venues:', error);
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
  if (newVal && newVal !== 'Other') {
    fetchGenderIssues(newVal);
  } else {
    genderIssues.value = [];
  }
});

watch(() => form.value.control_number, async (newVal) => {
  const selected = approvedControls.value.find(c => c.control_number === newVal);
  if (selected) {
    form.value.act_design_id = selected.act_design_id;
    form.value.activity_title = selected.activity_title;
    form.value.start_date = selected.start_date;
    form.value.end_date = selected.end_date;
    form.value.start_time = selected.start_time;
    form.value.end_time = selected.end_time;
    form.value.venue = selected.venue_name || selected.venue; // Fallback to venue if venue_name is not available
    form.value.venue_id = selected.venue_id || 'Other';
    customVenue.value = selected.venue_id ? '' : (selected.venue || '');
    form.value.activity_classification = selected.activity_classification || 'N/A';
    form.value.classification_id = selected.classification_id || '';
    form.value.gad_mandate = selected.gad_mandate || 'N/A';
    form.value.gad_mandate_id = selected.gad_mandate_id || '';
    form.value.gender_issue = selected.gender_issue || 'N/A';
    form.value.gender_issue_id = selected.gender_issue_id || '';
    selectedProposedBudget.value = Number(selected.proposed_budget) || 0;

    try {
      const res = await api.get(`activity-design/${selected.act_design_id}`);
      if (res.data && res.data.success) {
        const fullDesign = res.data.data;
        proposedBudgetItems.value = {
          meals: getBudgetItemAmount(fullDesign.budget_items, 'Meals'),
          snacks: getBudgetItemAmount(fullDesign.budget_items, 'Snacks'),
          venue: getBudgetItemAmount(fullDesign.budget_items, 'Function Room/Venue'),
          accommodation: getBudgetItemAmount(fullDesign.budget_items, 'Accommodation'),
          rental: getBudgetItemAmount(fullDesign.budget_items, 'Equipment Rental'),
          transportation: getBudgetItemAmount(fullDesign.budget_items, 'Transportation'),
          honoraria: getBudgetItemAmount(fullDesign.budget_items, 'Professional Fee/Honoria'),
          tokens: getBudgetItemAmount(fullDesign.budget_items, 'Token/s'),
          supplies: getBudgetItemAmount(fullDesign.budget_items, 'Materials and Supplies'),
          others: getBudgetItemAmount(fullDesign.budget_items, 'Others')
        };

        // Auto-populate othersList from the AD's others items
        othersList.value = (fullDesign.budget_items || [])
          .filter(i => i.item_name === 'Others')
          .map(i => ({
            name: i.sub_item || '',
            amount: '',
            proposed_amount: Number(i.amount) || 0
          }));
      }
    } catch (err) {
      console.error('Error fetching full design budget items:', err);
    }
  } else {
    selectedProposedBudget.value = 0;
    form.value.activity_classification = '';
    form.value.classification_id = '';
    form.value.gad_mandate = '';
    form.value.gad_mandate_id = '';
    form.value.gender_issue = '';
    form.value.gender_issue_id = '';
    form.value.venue_id = '';
    customVenue.value = '';
    proposedBudgetItems.value = {};
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
  if (!form.value.control_number) {
    Swal.fire({
      icon: 'warning',
      title: 'Missing Field',
      text: 'Please select an Activity Design Control Number before proceeding.',
      confirmButtonColor: '#b979cc'
    });
    return;
  }

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

  // Validate number of attendees (min 50)
  if (form.value.attendees < 50) {
    Swal.fire({
      icon: 'warning',
      title: 'Invalid Participants',
      text: 'Participants must be 50 and above.',
      confirmButtonColor: '#b979cc'
    });
    return;
  }

  try {
    const formData = new FormData();
    
    // Append standard fields
    formData.append('control_number', form.value.control_number);
    formData.append('act_design_id', form.value.act_design_id);
    formData.append('activity_title', form.value.activity_title);
    formData.append('start_date', form.value.start_date);
    formData.append('end_date', form.value.end_date);
    formData.append('start_time', form.value.start_time);
    formData.append('end_time', form.value.end_time);
    formData.append('attendees', form.value.attendees);
    formData.append('male', form.value.male);
    formData.append('female', form.value.female);
    formData.append('rating', form.value.rating);
    formData.append('user_id', user.value.id);
    formData.append('status', form.value.status || 'Pending');

    // Append GAD alignment fields
    formData.append('classification_id', form.value.classification_id || '');
    formData.append('gad_mandate_id', form.value.gad_mandate_id || '');
    formData.append('gender_issue_id', form.value.gender_issue_id || '');
    formData.append('custom_gad_mandate', customMandate.value);
    formData.append('custom_gender_issue', customGenderIssue.value);

    // Append Venue fields
    if (form.value.venue_id === 'Other') {
      formData.append('venue_id', 'Other');
      formData.append('venue', customVenue.value);
    } else {
      formData.append('venue_id', form.value.venue_id || '');
      const selectedVenue = venues.value.find(v => v.venue_id === form.value.venue_id);
      formData.append('venue', selectedVenue ? selectedVenue.venue_name : '');
    }

    // Append budget items
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

    form.value.budget_items.forEach(item => {
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

    formData.append('budget_items', JSON.stringify(finalBudgetItems));
    formData.append('evaluation_items', JSON.stringify(form.value.evaluation_items));
    
    uploadedFiles.value.forEach(file => {
      formData.append('attachment[]', file);
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

onMounted(() => {
  if (!user.value.id) {
    router.push('/login');
  } else {
    fetchApprovedControls();
    fetchHolidays();
    fetchVenues();
    fetchActivityClassifications();
    fetchGADMandates();
  }
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

.back-button {
  padding: 12px 24px;
  font-size: 14px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #b979cc;
  border-radius: 12px;
  background: transparent;
  border: none;
  cursor: pointer;
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

.form-actions-ar {
  display: flex;
  justify-content: flex-end;
  padding-top: 24px;
}

/* Budget Limit Warning Card */
.ar-limit-warning-card {
  display: flex;
  gap: 16px;
  background: rgba(244, 63, 94, 0.1);
  border: 1px solid rgba(244, 63, 94, 0.3);
  border-radius: 12px;
  padding: 16px 20px;
  margin-top: 20px;
  margin-bottom: 10px;
  box-shadow: 0 4px 12px rgba(244, 63, 94, 0.15);
  animation: fadeIn 0.3s ease;
}

.warning-icon {
  font-size: 24px;
  flex-shrink: 0;
}

.warning-content {
  display: flex;
  flex-direction: column;
  gap: 6px;
  text-align: left;
}

.warning-title {
  font-size: 14px;
  font-weight: 800;
  color: #f43f5e;
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.warning-desc {
  font-size: 13px;
  color: #cbd5e1;
  margin: 0;
  line-height: 1.4;
}

.warning-instruction {
  font-size: 12px;
  color: #fb7185;
  margin: 0;
  font-weight: 600;
}

.btn-disabled {
  background: #475569 !important;
  color: #94a3b8 !important;
  box-shadow: none !important;
  cursor: not-allowed !important;
  transform: none !important;
  opacity: 0.6;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(5px); }
  to { opacity: 1; transform: translateY(0); }
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

.budget-item-proposed {
  font-size: 0.8125rem;
  color: #fbbf24;
  font-weight: 600;
  margin-top: 0.25rem;
}

.select-arrow-fix {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23b979cc' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 20px center;
  background-size: 16px;
}
</style>