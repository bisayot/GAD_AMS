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
                    <label class="form-label-ar">Form Type *</label>
                    <input
                      type="text"
                      v-model="form.form_type"
                      class="custom-input-field"
                      placeholder="Form Type"
                    >
                  </div>

                  <div class="input-group-ar">
                    <label class="form-label-ar">Activity Classification *</label>
                    <input
                      type="text"
                      v-model="form.activity_classification"
                      class="custom-input-field"
                      placeholder="Activity Classification"
                    >
                  </div>

                  <div class="input-group-ar">
                    <label class="form-label-ar">GAD Mandate *</label>
                    <div class="checkbox-group-container custom-input-field" style="min-height: 120px; max-height: 250px; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 10px;">
                      <label v-for="mandate in GADMandates" :key="mandate.id" class="checkbox-label" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; color: #ffffff;">
                        <input type="checkbox" v-model="form.gad_mandate_id" :value="mandate.id.toString()" style="margin-top: 2px; accent-color: #b979cc; transform: scale(1.1);" />
                        <span style="font-size: 14px; line-height: 1.4;">{{ mandate.code }} - {{ mandate.title }}</span>
                      </label>
                      <label class="checkbox-label" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; color: #ffffff;">
                        <input type="checkbox" v-model="form.gad_mandate_id" value="Other" style="margin-top: 2px; accent-color: #b979cc; transform: scale(1.1);" />
                        <span style="font-size: 14px; line-height: 1.4; font-style: italic;">+ New Mandate</span>
                      </label>
                    </div>
                    <input v-if="form.gad_mandate_id && form.gad_mandate_id.includes('Other')" 
                          v-model="customMandate" 
                          type="text" 
                          placeholder="Enter new mandate name..." 
                          class="custom-input-field" 
                          style="margin-top: 10px;" />
                  </div>

                  <div class="input-group-ar">
                    <label class="form-label-ar">Gender Issue *</label>
                    <div class="checkbox-group-container custom-input-field" style="min-height: 120px; max-height: 250px; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 10px;">
                      <label v-for="issue in genderIssues" :key="issue.id" class="checkbox-label" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; color: #ffffff;">
                        <input type="checkbox" v-model="form.gender_issue_id" :value="issue.id.toString()" style="margin-top: 2px; accent-color: #b979cc; transform: scale(1.1);" />
                        <span style="font-size: 14px; line-height: 1.4;">{{ issue.title }}</span>
                      </label>
                      <label class="checkbox-label" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; color: #ffffff;">
                        <input type="checkbox" v-model="form.gender_issue_id" value="Other" style="margin-top: 2px; accent-color: #b979cc; transform: scale(1.1);" />
                        <span style="font-size: 14px; line-height: 1.4; font-style: italic;">+ New Gender Issue</span>
                      </label>
                      <p v-if="!form.gad_mandate_id || form.gad_mandate_id.length === 0" style="color: #94a3b8; font-size: 13px; font-style: italic; margin: 0;">Select a mandate first to see gender issues.</p>
                    </div>
                    <input v-if="form.gender_issue_id && form.gender_issue_id.includes('Other')" 
                          v-model="customGenderIssue" 
                          type="text" 
                          placeholder="Enter new gender issue..." 
                          class="custom-input-field" 
                          style="margin-top: 10px;" />
                  </div>

                  <div class="input-group-ar">
                    <label class="form-label-ar">Target Participants *</label>
                    <input
                      type="number"
                      v-model="form.target_participants"
                      class="custom-input-field"
                      placeholder="0"
                    >
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
                    <input 
                      type="text" 
                      v-model="form.venue" 
                      required 
                      class="custom-input-field"
                      placeholder="e.g., Convention Center, Main Hall"
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

              <div class="attachment-section-container-ar">
                <label class="form-label-ar">Attachments (PDF) *</label>
                <div class="attachment-display-grid-ar">
                  <div class="attachment-upload-column-ar">
                    <div class="upload-zone-ar" 
                         @click="$refs.fileInput.click()"
                         @dragover.prevent
                         @dragenter.prevent
                         @drop.prevent="handleDrop"
                         style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; border: 2px dashed #b979cc; border-radius: 12px; background: rgba(30, 41, 59, 0.4); cursor: pointer; transition: all 0.3s ease; text-align: center;">
                      <input 
                        ref="fileInput" 
                        type="file" 
                        @change="handleFileUpload" 
                        accept=".pdf" 
                        class="file-input-hidden" 
                        multiple 
                      />
                      <span class="upload-icon-ar" style="font-size: 48px; margin-bottom: 16px; display: block;">📤</span>
                      <h4 style="color: #ffffff; font-size: 16px; margin: 0 0 8px 0; font-weight: 600;">Drag & drop your files here</h4>
                      <p style="color: #94a3b8; font-size: 14px; margin: 0 0 12px 0;">or click to browse from your computer</p>
                      <span style="background: rgba(185, 121, 204, 0.2); color: #e9d5ff; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">Max 10MB per file</span>
                    </div>
                  </div>
                  <div class="attachment-preview-column-ar">
                    <div v-if="uploadedFiles.length > 0" class="uploaded-files-container-ar" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                      <div v-for="(file, index) in uploadedFiles" :key="index" style="display: flex; flex-direction: column; gap: 10px; background: rgba(30, 41, 59, 0.4); padding: 15px; border-radius: 10px; border: 1px solid rgba(185, 121, 204, 0.2);">
                        <div class="uploaded-file-tag" style="width: 100%; background: transparent; padding: 0; border: none; flex-direction: column; align-items: flex-start; gap: 8px;">
                          <span class="uploaded-file-name" style="word-break: break-all;">📄 {{ file.name }}</span>
                          <div class="uploaded-file-actions-ar" style="width: 100%; display: flex; justify-content: space-between; align-items: center;">
                            <span class="uploaded-file-size-ar">({{ (file.size / 1024).toFixed(2) }} KB)</span>
                            <button type="button" @click.stop="removeFile(index)" class="remove-file-btn">Remove</button>
                          </div>
                        </div>
                        
                        <div v-if="file.previewUrl" class="document-previews" style="width: 100%;">
                           <iframe :src="file.previewUrl" width="100%" height="300px" style="border: 1px solid #b979cc; border-radius: 8px; background: white;"></iframe>
                        </div>
                      </div>
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
  if (route.path.includes('/staff')) return staffMenu;
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

// Validations are minimal since AD is already approved
const form = ref({
  activity_title: '',
  control_number: '',
  form_type: '',
  activity_classification: '',
  gad_mandate_id: [],
  gender_issue_id: [],
  target_participants: '',
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

const GADMandates = ref([]);
const genderIssues = ref([]);
const customMandate = ref('');
const customGenderIssue = ref('');

const fetchMandates = async () => {
  try {
    const res = await api.get('get-gad-mandates');
    GADMandates.value = res.data;
  } catch (error) {
    console.error('Error fetching GAD mandates:', error);
  }
};

const fetchGenderIssues = async (mandateIds) => {
  const ids = mandateIds || form.value?.gad_mandate_id;
  if (!ids || !Array.isArray(ids) || ids.length === 0 || ids.includes('Other')) {
    genderIssues.value = [];
    return;
  }
  try {
    const allIssues = [];
    for (const mandateId of ids) {
       if (mandateId !== 'Other') {
           const res = await api.get(`get-gender-issues/${mandateId}`);
           allIssues.push(...res.data);
       }
    }
    const uniqueIssues = [];
    const map = new Map();
    for (const item of allIssues) {
        if(!map.has(item.id)){
            map.set(item.id, true);
            uniqueIssues.push(item);
        }
    }
    genderIssues.value = uniqueIssues;
  } catch (error) {
    console.error('Error fetching gender issues:', error);
  }
};

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

const isExceedingLimit = computed(() => {
  return selectedProposedBudget.value > 0 && form.value.proposed_budget > selectedProposedBudget.value;
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
    form.value.venue = selected.venue_name || selected.venue; 
    form.value.activity_classification = selected.activity_classification || 'N/A';
    form.value.form_type = selected.form_type_name || selected.form_type || 'N/A';
    form.value.target_participants = selected.target_participants || '0';
    form.value.gad_mandate_id = selected.gad_mandate_ids ? selected.gad_mandate_ids.split(',').map(s=>s.trim()) : [];
    
    await fetchGenderIssues(form.value.gad_mandate_id);
    
    form.value.gender_issue_id = selected.gender_issue_ids ? selected.gender_issue_ids.split(',').map(s=>s.trim()) : [];
    selectedProposedBudget.value = Number(selected.proposed_budget) || 0;

    if (selected.budget_items && selected.budget_items.length > 0) {
      const dbBudget = selected.budget_items[0];
      form.value.budget_items.forEach(item => {
        switch (item.name) {
          case 'Meals': item.total = Number(dbBudget.meals_and_snacks) || ''; break;
          case 'Snacks': item.total = ''; break;
          case 'Function Room/Venue': item.total = Number(dbBudget.function_room_venue) || ''; break;
          case 'Accommodation': item.total = Number(dbBudget.accommodation) || ''; break;
          case 'Equipment Rental': item.total = Number(dbBudget.equipment_rental) || ''; break;
          case 'Professional Fee/Honoraria': item.total = Number(dbBudget.professional_fee_honoria) || ''; break;
          case 'Token/s': item.total = Number(dbBudget.tokens) || ''; break;
          case 'Materials and Supplies': item.total = Number(dbBudget.materials_and_supplies) || ''; break;
          case 'Transportation': item.total = Number(dbBudget.transportation) || ''; break;
        }
      });
    }
  } else {
    selectedProposedBudget.value = 0;
    form.value.form_type = '';
    form.value.target_participants = '';
    form.value.activity_classification = '';
    form.value.gad_mandate_id = [];
    form.value.gender_issue_id = [];
  }
});

const formatBudgetName = (name) => {
  if (!name) return '';
  return name.replace(/(\(.*\))/g, '<span class="budget-item-subtext">$1</span>');
};

watch(() => form.value.gad_mandate_id, (newVal) => {
  form.value.gender_issue_id = [];
  fetchGenderIssues(newVal);
}, { deep: true });

watch(() => form.value.budget_items, (newItems) => {
  const total = newItems.reduce((sum, item) => sum + (Number(item.total) || 0), 0);
  form.value.proposed_budget = total;
}, { deep: true });

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
const activePreviewIndex = ref(0);

const handleDrop = (event) => {
  if (event.dataTransfer.files.length > 0) {
    processFiles(event.dataTransfer.files);
  }
};

const handleFileUpload = (event) => {
  if (event.target.files.length > 0) {
    processFiles(event.target.files);
  }
};

const processFiles = (fileList) => {
  const newFiles = Array.from(fileList);
  const validFiles = [];
  newFiles.forEach(file => {
      if (file.size > 10 * 1024 * 1024) {
        Swal.fire({
          icon: 'error',
          title: 'File Too Large',
          text: `File "${file.name}" exceeds the 10MB limit.`,
          confirmButtonColor: '#b979cc'
        });
        return;
      }
      if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
        Swal.fire({
          icon: 'error',
          title: 'Invalid File Type',
          text: `File "${file.name}" is not a PDF. Only PDF files are allowed.`,
          confirmButtonColor: '#b979cc'
        });
        return;
      }
    file.previewUrl = URL.createObjectURL(file);
    validFiles.push(file);
  });
  uploadedFiles.value = [...uploadedFiles.value, ...validFiles];
  if (uploadedFiles.value.length > 0 && activePreviewIndex.value >= uploadedFiles.value.length) {
    activePreviewIndex.value = 0;
  }
};

const removeFile = (index) => {
  uploadedFiles.value.splice(index, 1);
  if (uploadedFiles.value.length === 0 && fileInput.value) {
    fileInput.value.value = '';
    activePreviewIndex.value = 0;
  } else if (activePreviewIndex.value >= index && activePreviewIndex.value > 0) {
    activePreviewIndex.value--;
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

  if (form.value.start_date && form.value.end_date) {
    const startDate = new Date(form.value.start_date + 'T00:00:00');
    const endDate = new Date(form.value.end_date + 'T00:00:00');
    if (endDate < startDate) {
      Swal.fire({
        icon: 'warning',
        title: 'Invalid Duration',
        text: 'End date cannot be before start date.',
        confirmButtonColor: '#b979cc'
      });
      return;
    }
  }

  if (uploadedFiles.value.length === 0) {
    Swal.fire({
      icon: 'warning',
      title: 'Missing Document',
      text: 'Please upload the Accomplishment Report and any attachments.',
      confirmButtonColor: '#b979cc'
    });
    return;
  }

  try {
    const formData = new FormData();
    
    uploadedFiles.value.forEach(file => {
      formData.append('attachments[]', file);
    });
    
    const budgetObj = {
      meals_and_snacks: (Number(form.value.budget_items.find(i => i.name === 'Meals')?.total || 0) + Number(form.value.budget_items.find(i => i.name === 'Snacks')?.total || 0)),
      function_room_venue: Number(form.value.budget_items.find(i => i.name === 'Function Room/Venue')?.total || 0),
      accommodation: Number(form.value.budget_items.find(i => i.name === 'Accommodation')?.total || 0),
      equipment_rental: Number(form.value.budget_items.find(i => i.name === 'Equipment Rental')?.total || 0),
      professional_fee_honoria: Number(form.value.budget_items.find(i => i.name === 'Professional Fee/Honoraria')?.total || 0),
      tokens: Number(form.value.budget_items.find(i => i.name === 'Token/s')?.total || 0),
      materials_and_supplies: Number(form.value.budget_items.find(i => i.name === 'Materials and Supplies')?.total || 0) + Number(form.value.budget_items.find(i => i.name === 'Others')?.total || 0),
      transportation: Number(form.value.budget_items.find(i => i.name === 'Transportation')?.total || 0)
    };
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
      if (key !== 'budget_items' && key !== 'evaluation_items' && key !== 'gad_mandate_id' && key !== 'gender_issue_id') {
        formData.append(key, form.value[key]);
      }
    });

    formData.append('venue', form.value.venue);
    formData.append('attendees', form.value.attendees);
    formData.append('male', form.value.male);
    formData.append('female', form.value.female);
    formData.append('rating', form.value.rating);
    formData.append('user_id', user.value.id);

    if (form.value.gad_mandate_id) {
      formData.append('gad_mandate_id', Array.isArray(form.value.gad_mandate_id) ? form.value.gad_mandate_id.join(',') : form.value.gad_mandate_id);
    }
    if (Array.isArray(form.value.gad_mandate_id) && form.value.gad_mandate_id.includes('Other')) {
      formData.append('custom_gad_mandate', customMandate.value);
    }
    
    if (form.value.gender_issue_id) {
      formData.append('gender_issue_id', Array.isArray(form.value.gender_issue_id) ? form.value.gender_issue_id.join(',') : form.value.gender_issue_id);
    }
    if (Array.isArray(form.value.gender_issue_id) && form.value.gender_issue_id.includes('Other')) {
      formData.append('custom_gender_issue', customGenderIssue.value);
    }
    
    uploadedFiles.value.forEach(file => {
      formData.append('attachment[]', file);
    });
    
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
        router.push('/staff/ar-list');
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
    fetchMandates();
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
</style>