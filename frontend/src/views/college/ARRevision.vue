<template>
  <main class="main-viewport">
    <div v-if="loading" class="loading-wrapper">
      <div class="loading-spinner"></div>
    </div>

    <div v-else class="page-container">
      <div class="layout-vertical">
        <!-- TOP: Revision Remarks -->
        <section class="flex-full">
          <div class="assessment-card-custom">
            <div class="assessment-header">
              <div class="assessment-icon">📋</div>
              <div class="assessment-title">Revision Remarks / Comments</div>
            </div>

            <div class="assessment-form">
              <div class="info-item">
                <span class="info-label">Revision Remarks / Comments</span>
                <div class="read-only-remarks">
                  {{ existingReport?.remarks || 'No remarks recorded for this accomplishment report.' }}
                </div>
              </div>

              <div class="action-buttons">
                <button type="button" @click="router.back()" class="btn-back">
                  ← Back
                </button>
              </div>
            </div>
          </div>
        </section>
        
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

          </div>

          <form @submit.prevent="submitReport" class="report-body">
            <div class="ar-horizontal-layout">

              <!-- LEFT: Approved Activity Design Details (read-only) -->
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
                  <div class="full-width-info">
                    <label class="info-label">Activity Classification</label>
                    <p class="text-sm-light mt-1">{{ existingReport.activity_design.activity_classification || '---' }}</p>
                  </div>
                  <div>
                    <label class="info-label">Form Type</label>
                    <p class="text-sm-light mt-1 uppercase">{{ existingReport.activity_design.form_type_name || existingReport.activity_design.form_type || '---' }}</p>
                  </div>
                  <div class="full-width-info">
                    <label class="info-label">Gender Issue / GAD Mandate</label>
                    <div v-if="existingReport.activity_design.gad_mandate" class="mandate-boxes">
                      <span v-for="(mandate, index) in existingReport.activity_design.gad_mandate.split(';;;')" :key="'m'+index" class="mandate-box">
                        {{ mandate.trim() }}
                      </span>
                    </div>
                    <p v-else class="text-sm-light mt-1">---</p>
                  </div>
                  <div class="full-width-info">
                    <label class="info-label">Cause of Gender Issue</label>
                    <div v-if="existingReport.activity_design.gender_issue" class="mandate-boxes">
                      <span v-for="(issue, index) in existingReport.activity_design.gender_issue.split(';;;')" :key="'gi'+index" class="mandate-box">
                        {{ issue.trim() }}
                      </span>
                    </div>
                    <p v-else class="text-sm-light mt-1">---</p>
                  </div>
                  <div>
                    <label class="info-label">Venue</label>
                    <p class="text-sm-light mt-1">{{ existingReport.activity_design.venue_name || existingReport.activity_design.venue }}</p>
                  </div>
                  <div>
                    <label class="info-label">Start Date</label>
                    <p class="text-sm-light mt-1">{{ formatDate(existingReport.activity_design.start_date) }}</p>
                  </div>
                  <div>
                    <label class="info-label">End Date</label>
                    <p class="text-sm-light mt-1">{{ formatDate(existingReport.activity_design.end_date) }}</p>
                  </div>
                  <div>
                    <label class="info-label">Start Time</label>
                    <p class="text-sm-light mt-1">{{ formatTime(existingReport.activity_design.start_time) }}</p>
                  </div>
                  <div>
                    <label class="info-label">End Time</label>
                    <p class="text-sm-light mt-1">{{ formatTime(existingReport.activity_design.end_time) }}</p>
                  </div>
                  <div>
                    <label class="info-label">Proposed Budget</label>
                    <p class="text-sm-light mt-1">PHP {{ Number(aDBudget?.grand_total || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</p>
                  </div>
                  <div>
                    <label class="info-label">Assessment Date</label>
                    <p class="text-sm-light mt-1">{{ existingReport.activity_design.assessment_date ? formatDate(existingReport.activity_design.assessment_date) : 'N/A' }}</p>
                  </div>
                  <div class="full-width-info" v-if="existingReport.activity_design?.remarks">
                    <label class="info-label">Reviewer Remarks</label>
                    <div class="read-only-remarks mt-1">{{ existingReport.activity_design.remarks }}</div>
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
                          <td class="font-bold text-white text-right">{{ Number(aDBudget?.grand_total || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>

                <!-- AD Attachments -->
                <div v-if="existingReport.activity_design && existingReport.activity_design.attachment && parseAttachments(existingReport.activity_design.attachment).length > 0" class="attachments-list mt-4" style="width:100%;">
                  <label class="info-label mb-2">Approved Design Attachments</label>
                  <div v-for="(file, index) in parseAttachments(existingReport.activity_design.attachment)" :key="'ad-'+index" class="doc-item mb-2">
                    <div class="doc-info">
                      <span class="material-symbols-outlined doc-pdf-icon">picture_as_pdf</span>
                      <div>
                        <p class="doc-title">{{ file.split('_').slice(1).join('_') || file }}</p>
                        <p class="doc-meta">Reference: {{ file }}</p>
                      </div>
                    </div>
                    <div class="doc-actions">
                      <button type="button" @click="previewFile(file, 'archived')" class="preview-btn">Preview</button>
                      <button type="button" @click="downloadFile(file, 'archived', 'Activity_Design')" class="download-btn-icon">
                        <span class="material-symbols-outlined">download</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- RIGHT: Actual Accomplishment Details (editable) -->
              <div class="section-card">
                <div class="section-header-row">
                  <span class="material-symbols-outlined icon-pink">edit_document</span>
                  <h3 class="section-title">Actual Accomplishment Details</h3>
                </div>
                <div class="grid-2">
                  <div class="full-width-info">
                    <label class="info-label">Actual Activity Title *</label>
                    <textarea v-model="form.activity_title" rows="2" class="custom-input-field textarea-no-resize mt-1" placeholder="Enter the actual title of the activity"></textarea>
                  </div>
                  <div class="full-width-info">
                    <label class="info-label">Activity Classification</label>
                    <select v-model="form.activity_classification_id" @change="handleClassificationChange" class="custom-input-field select-arrow-fix mt-1">
                      <option value="" disabled class="dark-option">Select Classification</option>
                      <option v-for="cls in ActClassification" :key="cls.id" :value="cls.id" class="dark-option">
                        {{ cls.classification_name }}
                      </option>
                    </select>
                  </div>
                  <div>
                    <label class="info-label">Form Type</label>
                    <select v-model="form.form_type" class="custom-input-field select-arrow-fix mt-1">
                      <option value="" disabled class="dark-option">Select Form Type</option>
                      <option v-for="ft in formTypes" :key="ft.id" :value="ft.id" class="dark-option">
                        {{ ft.name }}
                      </option>
                    </select>
                  </div>
                  <div class="full-width-info">
                    <label class="info-label">Gender Issue / GAD Mandate</label>
                    <div class="checkbox-group-container custom-input-field mt-1" style="max-height: 200px; overflow-y: auto; padding: 8px; display: flex; flex-direction: column; gap: 8px;">
                      <label v-for="mandate in GADMandates" :key="mandate.id" class="mandate-checkbox-label" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; color: #ffffff;">
                        <input type="checkbox" @change="handleMandateChange" v-model="form.gad_mandate_id" :value="mandate.id.toString()" style="margin-top: 2px; accent-color: #b979cc; transform: scale(1.1);" />
                        <span style="font-size: 13px; color: #fff; line-height: 1.4;">{{ mandate.code }} - {{ mandate.title }}</span>
                      </label>
                      </div>
                  </div>
                  <div class="full-width-info">
                    <label class="info-label">Cause of Gender Issue</label>
                    <div class="checkbox-group-container custom-input-field mt-1" style="max-height: 200px; overflow-y: auto; padding: 8px; display: flex; flex-direction: column; gap: 8px;">
                      <label v-for="issue in genderIssues" :key="issue.id" class="mandate-checkbox-label" style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; color: #ffffff;">
                        <input type="checkbox" v-model="form.gender_issue_id" :value="issue.id.toString()" style="margin-top: 2px; accent-color: #b979cc; transform: scale(1.1);" />
                        <span style="font-size: 13px; color: #fff; line-height: 1.4;">{{ issue.title }}</span>
                      </label>
                      <p v-if="!form.gad_mandate_id || form.gad_mandate_id.length === 0" style="color: #94a3b8; font-size: 13px; font-style: italic; margin: 0;">Select a mandate first to see gender issues.</p>
                    </div>
                  </div>
                  <div>
                    <label class="info-label">Target Participants</label>
                    <p class="text-sm-light mt-1">{{ existingReport?.activity_design?.target_participants || '---' }}</p>
                  </div>
                  <div>
                    <label class="info-label">Start Date of Implementation *</label>
                    <input type="date" v-model="form.start_date" :min="minDate" :max="maxDate" required class="custom-input-field code-icon-calendar mt-1">
                  </div>
                  <div>
                    <label class="info-label">End Date of Implementation *</label>
                    <input type="date" v-model="form.end_date" :min="minDate" :max="maxDate" required class="custom-input-field code-icon-calendar mt-1">
                  </div>
                  <div>
                    <label class="info-label">Start Time *</label>
                    <input type="time" v-model="form.start_time" min="04:00" max="20:00" required class="custom-input-field code-icon-clock mt-1">
                  </div>
                  <div>
                    <label class="info-label">End Time *</label>
                    <input type="time" v-model="form.end_time" min="04:00" max="20:00" required class="custom-input-field code-icon-clock mt-1">
                  </div>
                  <div class="full-width-info">
                    <label class="info-label">Venue *</label>
                    <select 
                      v-model="form.venue" 
                      required 
                      class="custom-input-field select-arrow-fix mt-1"
                    >
                      <option value="" disabled class="dark-option">Select venue...</option>
                      <option 
                        v-for="v in filteredVenues" 
                        :key="v.venue_id" 
                        :value="v.venue_name" 
                        class="dark-option"
                      >
                        {{ v.venue_name }}
                      </option>
                      <option value="Other" class="dark-option">Other</option>
                    </select>
                  </div>
                  <div v-if="form.venue === 'Other'" class="full-width-info mt-3">
                    <label class="info-label">Specify Other Venue *</label>
                    <input 
                      type="text" 
                      v-model="customVenue" 
                      required 
                      class="custom-input-field mt-1"
                      placeholder="Enter the complete venue name"
                    >
                  </div>
                  <div class="full-width-info">
                    <label class="info-label">Venue Location *</label>
                    <div class="toggle-container" style="display: flex; gap: 1rem; align-items: center; height: 42px;">
                      <label style="color: #cbd5e1; font-size: 14px; cursor: pointer;">
                        <input type="radio" :value="true" v-model="form.is_inside_bsu" style="accent-color: #b979cc; transform: scale(1.1); margin-right: 5px;" /> Inside BSU
                      </label>
                      <label style="color: #cbd5e1; font-size: 14px; cursor: pointer;">
                        <input type="radio" :value="false" v-model="form.is_inside_bsu" style="accent-color: #b979cc; transform: scale(1.1); margin-right: 5px;" /> Outside BSU
                      </label>
                    </div>
                  </div>
                  <div>
                    <label class="info-label">Number of Attendees</label>
                    <input type="number" v-model="form.attendees" min="0" class="custom-input-field mt-1" readonly style="opacity:0.6;cursor:not-allowed;">
                  </div>
                  <div>
                    <label class="info-label">Male Participants *</label>
                    <input type="number" v-model="form.male" min="0" class="custom-input-field mt-1" placeholder="0">
                  </div>
                  <div>
                    <label class="info-label">Female Participants *</label>
                    <input type="number" v-model="form.female" min="0" class="custom-input-field mt-1" placeholder="0">
                  </div>
                </div>

                                                <!-- Actual Budgetary Expenditure (editable) -->
                <div class="budget-section mt-4">
                  <label class="form-label-ar">Actual Budgetary Expenditure *</label>
                  
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
                              v-model="form.budget_items[5].total" 
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
                              v-model="form.budget_items[6].total" 
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
                              v-model="form.budget_items[7].total" 
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
                              v-model="form.budget_items[8].total" 
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

                <!-- Evaluation (editable) -->
                <div class="full-width-info mt-4">
                  <label class="info-label mb-2">Activity Assessment Evaluators *</label>
                  <div class="table-responsive">
                    <table class="custom-table">
                      <thead>
                        <tr>
                          <th>Core Area</th>
                          <th class="text-center" style="width:140px;">Evaluators</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(item, index) in form.evaluation_items" :key="index">
                          <td>{{ item.area }}</td>
                          <td class="text-center">
                            <input type="number" v-model="item.rating" class="custom-input-field text-center mx-auto" placeholder="0" min="0" style="width:80px;">
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <!-- Rating -->
                <div class="full-width-info mt-4" style="display:flex;align-items:center;gap:12px;">
                  <label class="info-label">General Assessment Rating *</label>
                  <input type="number" v-model="form.rating" class="custom-input-field" placeholder="0.00" min="0" max="5" step="0.01" style="width:120px;">
                </div>

                <!-- File Upload -->
                <div class="full-width-info mt-4">
                  <label class="info-label mb-2">Proof of Activity / Attachments *</label>
                  <!-- Existing attachments from previously submitted report -->
                  <div v-if="existingReport && existingReport.attachment && filteredExistingAttachments.length > 0" class="mt-4">
                    <p class="info-label mb-2">Current Submitted Attachments:</p>
                    <div v-for="(file, index) in filteredExistingAttachments" :key="'existing-'+index" class="doc-item mb-2">
                      <div class="doc-info">
                        <span class="material-symbols-outlined doc-pdf-icon">picture_as_pdf</span>
                        <div>
                          <p class="doc-title">{{ file.split('_').slice(1).join('_') || file }}</p>
                          <p class="doc-meta">Previously submitted</p>
                        </div>
                      </div>
                      <div class="doc-actions">
                        <button type="button" @click="previewFile(file, Number(existingReport.is_archived) === 1 ? 'archived' : 'drafts')" class="preview-btn">Preview</button>
                        <button type="button" @click="downloadFile(file, Number(existingReport.is_archived) === 1 ? 'archived' : 'drafts', 'Accomplishment_Report')" class="download-btn-icon">
                          <span class="material-symbols-outlined">download</span>
                        </button>
                        <button type="button" @click="removeExistingAttachment(file)" class="download-btn-icon text-rose-400 hover:text-rose-300" title="Remove file">
                          <span class="material-symbols-outlined">delete</span>
                        </button>
                      </div>
                    </div>
                    <!-- Inline iframe preview of existing file -->
                    <div class="document-previews" style="margin-top:15px;">
                      <div v-for="(file, index) in filteredExistingAttachments" :key="'prev-'+index" style="margin-bottom:20px;">
                        <p style="color:#b979cc;font-size:13px;font-weight:700;margin-bottom:8px;">Current File Preview:</p>
                        <iframe :src="getExistingFileURL(file)" width="100%" height="400px" style="border:1px solid rgba(185,121,204,0.3);border-radius:8px;"></iframe>
                      </div>
                    </div>
                  </div>

                  <div class="doc-upload-area" @dragover.prevent @drop.prevent="handleFileDrop" @click="triggerFileInput">
                    <span class="material-symbols-outlined upload-icon">cloud_upload</span>
                    <p class="upload-text">Click or drag to upload new files</p>
                    <p class="upload-hint">PDF files only (Max: 10MB per file)</p>
                    <input type="file" ref="fileInput" multiple @change="handleFileUpload" style="display:none" accept=".pdf">
                  </div>
                  <div v-if="uploadedFiles.length > 0" class="mt-4">
                    <p class="info-label mb-2">New Files to Upload:</p>
                    <div v-for="(file, index) in uploadedFiles" :key="index" class="doc-item mb-2">
                      <div class="doc-info">
                        <span class="material-symbols-outlined doc-pdf-icon" :class="{'text-blue-400': file.type && file.type.includes('image')}">{{ file.type && file.type.includes('image') ? 'image' : 'description' }}</span>
                        <div>
                          <p class="doc-title">{{ file.name }}</p>
                          <p class="doc-meta">{{ formatFileSize(file.size) }}</p>
                        </div>
                      </div>
                      <button type="button" @click.prevent="removeFile(index)" class="download-btn-icon text-rose-400 hover:text-rose-300" title="Remove file">
                        <span class="material-symbols-outlined">delete</span>
                      </button>
                    </div>
                    <!-- Inline preview of newly selected files -->
                    <div class="document-previews" style="margin-top:10px;">
                      <div v-for="(file, index) in uploadedFiles" :key="'newprev-'+index" style="margin-bottom:16px;">
                        <p style="color:#b979cc;font-size:13px;font-weight:700;margin-bottom:6px;">New File Preview: {{ file.name }}</p>
                        <iframe :src="getFileURL(file)" width="100%" height="400px" style="border:1px solid #b979cc;border-radius:8px;"></iframe>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Submit -->
                <div class="full-width-info mt-4">
                  <button type="submit" class="submit-action-btn w-full" :disabled="isSubmitting">
                    {{ isSubmitting ? 'Resubmitting...' : 'Resubmit Report' }}
                  </button>
                </div>
              </div>

            </div>
          </form>
        </section>
      </div>
    </div>

    <!-- PDF Preview Modal -->
    <PdfPreviewModal :isOpen="isPdfModalOpen" :fileUrl="pdfFileUrl" @close="closePdfModal" />
  </main>
</template>

<script setup>
import PdfPreviewModal from '../../components/PdfPreviewModal.vue';
import { ref, onMounted, computed, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Swal from 'sweetalert2';
import api from '../../api';

const router = useRouter();
const route = useRoute();

const goBack = () => {
  router.back();
};
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const menuItems = computed(() => {
  if (route.path.includes('/college')) return collegeMenu;
  return [];
});

const venues = ref([]);
const customVenue = ref('');

const loading = ref(true);
const snacksSelected = ref({ am: false, pm: false });
const mealsSelected = ref({ breakfast: false, amSnack: false, lunch: false, pmSnack: false, dinner: false });
const mealsPax = ref(0);
const snacksPax = ref(0);
const pfPax = ref(0);
const tokensPax = ref(0);
const othersList = ref([]);
const addOtherItem = () => { othersList.value.push({ name: '', amount: 0 }); };
const removeOtherItem = (index) => { othersList.value.splice(index, 1); };

const customMandate = ref('');
const customGenderIssue = ref('');
const form = ref({
  activity_classification_id: '',
    form_type: '',
  gad_mandate_id: [],
  gender_issue_id: [],
  activity_title: '',
  control_number: '',
  act_design_id: null,
  start_date: '',
  end_date: '',
  start_time: '',
  end_time: '',
  venue: '',
  is_inside_bsu: true,
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

const approvedControls = ref([]);
const loadingControls = ref(false);

const ActClassification = ref([]);
const formTypes = ref([]);
const GADMandates = ref([]);
const genderIssues = ref([]);


const fetchGADMandates = async () => {
  try {
    let url = 'get-gad-mandates';
    if (form.value && form.value.activity_classification_id) {
        url += '?classification=' + form.value.activity_classification_id;
    }
    const res = await api.get(url);
    GADMandates.value = res.data;
  } catch (error) {
    console.error('Error fetching GAD mandates:', error);
  }
};

const handleClassificationChange = async () => {
  form.value.gad_mandate_id = [];
  form.value.gender_issue_id = [];
  await fetchGADMandates();
  await fetchGenderIssues();
};

const handleMandateChange = async () => {
  form.value.gender_issue_id = [];
  await fetchGenderIssues(form.value.gad_mandate_id);
};


const fetchData = async () => {
  try {
    const [actRes, formRes] = await Promise.all([
      api.get('get-activity-classifications'),
      api.get('get-form-types')
    ]);
    ActClassification.value = actRes.data;
    formTypes.value = formRes.data;
    await fetchGADMandates();
  } catch (error) {
    console.error('Error fetching dropdown data:', error);
  }
};

const fetchGenderIssues = async (mandateIds) => {
    const ids = mandateIds || form.value?.gad_mandate_id || [];
    if (!ids || !Array.isArray(ids) || ids.length === 0 ) {
      genderIssues.value = [];
      return;
    }
    try {
      const idString = ids.join(',');
      let url = `get-gender-issues?mandates=${idString}`;
      if (form.value && form.value.activity_classification_id) {
          url += '&classification=' + form.value.activity_classification_id;
      }
      const res = await api.get(url);
      genderIssues.value = res.data;
    } catch (error) {
      console.error('Error fetching gender issues:', error);
    }
};

// watcher removed in favor of @change

// watcher removed in favor of @change

const fetchApprovedControls = async () => {
  loadingControls.value = true;
  try {
    // Fetch all values from the control_number table
    const res = await api.get(`approved-controls/${user.value.id}`);
    if (res.data.success) {
      approvedControls.value = res.data.data;
    }
  isSubmitting.value = false;
    } catch (error) {
      isSubmitting.value = false;
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

watch(pfPax, (newPax) => {
  const item = form.value.budget_items.find(i => i.name === 'Professional Fee/Honoraria' || i.name === 'Professional Fee/Honoraria');
  if (item) item.total = (Number(newPax) * 2258.25) || '';
});

watch(tokensPax, (newPax) => {
  const item = form.value.budget_items.find(i => i.name === 'Token/s');
  if (item) item.total = (Number(newPax) * 1000) || '';
});

const fetchVenues = async () => {
  try {
    const response = await api.get('venues');
    if (response.data && response.data.status === 'success') {
      venues.value = response.data.data || [];
    }
  isSubmitting.value = false;
    } catch (error) {
      isSubmitting.value = false;
    console.error('Error fetching venues:', error);
        loading.value = false;
  }
};

const filteredVenues = computed(() => {
  return venues.value.filter(v => (v.is_inside_bsu == 1 || v.is_inside_bsu === true) === form.value.is_inside_bsu);
});

watch(() => form.value.is_inside_bsu, () => {
  if (loadingData.value) return;
  if (form.value.venue && form.value.venue !== 'Other') {
    const isValid = filteredVenues.value.some(v => v.venue_name === form.value.venue);
    if (!isValid) {
      form.value.venue = '';
    }
  }
});

fetchVenues();

const loadingData = ref(false);

// watcher removed in favor of @change

  watch([() => form.value.male, () => form.value.female], ([newMale, newFemale]) => {
  const m = parseInt(newMale) || 0;
  const f = parseInt(newFemale) || 0;
  form.value.attendees = m + f;
});


const minDate = computed(() => {
  if (existingReport.value?.activity_design?.start_date) {
    return existingReport.value.activity_design.start_date;
  }
  const currentYear = new Date().getFullYear();
  return `${currentYear}-01-01`;
});
const maxDate = computed(() => {
  const currentYear = new Date().getFullYear();
  return `${currentYear}-12-31`;
});

const isCurrentYear = (dateString) => {
  const date = new Date(dateString + 'T00:00:00');
  const manilaTime = new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" });
  const currentYear = new Date(manilaTime).getFullYear();
  return date.getFullYear() === currentYear;
};

const isValidTime = (timeStr) => {
  if (!timeStr) return true;
  return timeStr >= "04:00" && timeStr <= "20:00";
};

watch(() => form.value.start_date, (newDate) => {
  if (newDate) {
    if (newDate < minDate.value) {
      document.activeElement?.blur();
      Swal.fire({ icon: 'warning', title: 'Invalid Date', text: 'Start date cannot be earlier than the approved Activity Design start date.', confirmButtonColor: '#b979cc' });
      form.value.start_date = '';
      return;
    }
    if (!isCurrentYear(newDate)) {
      document.activeElement?.blur();
      Swal.fire({ icon: 'warning', title: 'Invalid Date', text: 'Activity must be within the current year.', confirmButtonColor: '#b979cc' });
      form.value.start_date = '';
      return;
    }
    if (form.value.end_date && form.value.end_date < newDate) {
        document.activeElement?.blur();
        Swal.fire({ icon: 'warning', title: 'Invalid Duration', text: 'End date cannot be before start date.', confirmButtonColor: '#b979cc' });
        form.value.start_date = '';
    }
  }
});

watch(() => form.value.end_date, (newDate) => {
  if (newDate) {
    if (newDate < minDate.value) {
      document.activeElement?.blur();
      Swal.fire({ icon: 'warning', title: 'Invalid Date', text: 'End date cannot be earlier than the approved Activity Design start date.', confirmButtonColor: '#b979cc' });
      form.value.end_date = '';
      return;
    }
    if (!isCurrentYear(newDate)) {
      document.activeElement?.blur();
      Swal.fire({ icon: 'warning', title: 'Invalid Date', text: 'Activity must be within the current year.', confirmButtonColor: '#b979cc' });
      form.value.end_date = '';
      return;
    }
    if (form.value.start_date && newDate < form.value.start_date) {
        document.activeElement?.blur();
        Swal.fire({ icon: 'warning', title: 'Invalid Duration', text: 'End date cannot be before start date.', confirmButtonColor: '#b979cc' });
        form.value.end_date = '';
    }
  }
});

watch(() => form.value.start_time, (newTime) => {
  if (newTime && !isValidTime(newTime)) {
    document.activeElement?.blur();
    Swal.fire({ icon: 'warning', title: 'Invalid Time', text: 'Must be set between 04:00 AM and 08:00 PM.', confirmButtonColor: '#b979cc' });
    form.value.start_time = '';
  }
  if (form.value.start_time && form.value.end_time && form.value.start_time >= form.value.end_time) {
      document.activeElement?.blur();
      Swal.fire({ icon: 'warning', title: 'Invalid Time Range', text: 'End time must be after start time.', confirmButtonColor: '#b979cc' });
      form.value.start_time = '';
  }
});

watch(() => form.value.end_time, (newTime) => {
  if (newTime && !isValidTime(newTime)) {
    document.activeElement?.blur();
    Swal.fire({ icon: 'warning', title: 'Invalid Time', text: 'Must be set between 04:00 AM and 08:00 PM.', confirmButtonColor: '#b979cc' });
    form.value.end_time = '';
  }
  if (form.value.start_time && form.value.end_time && form.value.start_time >= form.value.end_time) {
      document.activeElement?.blur();
      Swal.fire({ icon: 'warning', title: 'Invalid Time Range', text: 'End time must be after start time.', confirmButtonColor: '#b979cc' });
      form.value.end_time = '';
  }
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
const removedAttachments = ref([]);
const fileInput = ref(null);

const removeExistingAttachment = (file) => {
  removedAttachments.value.push(file);
};

const handleFileUpload = (event) => {
  if (event.target.files.length > 0) {
    uploadedFiles.value = [...uploadedFiles.value, ...Array.from(event.target.files)];
  }
};

const isSubmitting = ref(false);

const triggerFileInput = () => {
  if (fileInput.value) fileInput.value.click();
};

const handleFileDrop = (event) => {
  if (event.dataTransfer.files.length > 0) {
    uploadedFiles.value = [...uploadedFiles.value, ...Array.from(event.dataTransfer.files)];
  }
};

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const filteredExistingAttachments = computed(() => {
  if (!existingReport.value || !existingReport.value.attachment) return [];
  const allAttachments = parseAttachments(existingReport.value.attachment);
  return allAttachments.filter(file => !removedAttachments.value.includes(file));
});

const removeFile = (index) => {
  uploadedFiles.value.splice(index, 1);
  if (uploadedFiles.value.length === 0 && fileInput.value) {
    fileInput.value.value = '';
  }
};

const submitReport = async () => {
  isSubmitting.value = true;
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
    if (!confirm.isConfirmed) { isSubmitting.value = false; return; }
  }

  // Validate Cause of Gender Issue
  if (!form.value.gender_issue_id || form.value.gender_issue_id.length === 0) {
    isSubmitting.value = false;
    Swal.fire({
      icon: 'warning',
      title: 'Missing Field',
      text: 'Please select at least one Cause of Gender Issue before submitting.',
      confirmButtonColor: '#b979cc'
    });
    return;
  }

  try {
    const formData = new FormData();
    
    formData.append('venue', form.value.venue);

            const normalizedBudgetItems = [];

    form.value.budget_items.forEach(item => {
      if (item.name !== 'Others') {
        normalizedBudgetItems.push({
          category_id: null,
          item_name: item.name,
          sub_item: null,
          pax: (item.name === 'Professional Fee/Honoraria') ? (typeof pfPax !== 'undefined' ? pfPax?.value : null) : (item.name === 'Token/s') ? (typeof tokensPax !== 'undefined' ? tokensPax?.value : null) : null,
          amount: Number(item.total) || 0
        });
      }
    });

    if (typeof othersList !== 'undefined') {
      othersList.value.forEach(o => {
        if (o.name && Number(o.amount) > 0) {
          normalizedBudgetItems.push({
            category_id: null,
            item_name: 'Others',
            sub_item: o.name,
            pax: null,
            amount: Number(o.amount) || 0
          });
        }
      });
    }

    if (typeof mealsSelected !== 'undefined') {
        if (mealsSelected.value.breakfast || mealsSelected.value.lunch || mealsSelected.value.dinner) {
          const selected = [];
          if (mealsSelected.value.breakfast) selected.push('Breakfast');
          if (mealsSelected.value.lunch) selected.push('Lunch');
          if (mealsSelected.value.dinner) selected.push('Dinner');
          const mealsItem = normalizedBudgetItems.find(i => i.item_name === 'Meals');
          if (mealsItem) mealsItem.sub_item = selected.join(', ');
        }
    }

    if (typeof snacksSelected !== 'undefined') {
        if (snacksSelected.value.am || snacksSelected.value.pm) {
          const selected = [];
          if (snacksSelected.value.am) selected.push('AM');
          if (snacksSelected.value.pm) selected.push('PM');
          const snacksItem = normalizedBudgetItems.find(i => i.item_name === 'Snacks');
          if (snacksItem) snacksItem.sub_item = selected.join(', ');
        }
    }

    formData.append('budget_items', JSON.stringify(normalizedBudgetItems));

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

        if (form.value.activity_classification_id) formData.append('activity_classification_id', form.value.activity_classification_id);
    if (form.value.form_type) formData.append('form_type', form.value.form_type);
    
    formData.append('gad_mandate_id', Array.isArray(form.value.gad_mandate_id) ? form.value.gad_mandate_id.join(',') : form.value.gad_mandate_id);
    formData.append('custom_gad_mandate', customMandate.value);
    
    formData.append('gender_issue_id', Array.isArray(form.value.gender_issue_id) ? form.value.gender_issue_id.join(',') : form.value.gender_issue_id);
    formData.append('custom_gender_issue', customGenderIssue.value);

    Object.keys(form.value).forEach(key => {
      if (key !== 'budget_items' && key !== 'evaluation_items' && key !== 'venue' && key !== 'is_inside_bsu') {
        formData.append(key, form.value[key]);
      }
    });
    formData.append('is_inside_bsu', form.value.is_inside_bsu ? 1 : 0);
    
    uploadedFiles.value.forEach(file => {
        formData.append('attachments[]', file);
      });
      formData.append('removed_attachments', JSON.stringify(removedAttachments.value));
    
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
      };
      uploadedFiles.value = [];
      removedAttachments.value = [];
      if (fileInput.value) fileInput.value.value = '';
    }
  isSubmitting.value = false;
    } catch (error) {
      isSubmitting.value = false;
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

const parseAttachments = (attachmentData) => {
  if (!attachmentData) return [];
  try {
    const parsed = JSON.parse(attachmentData);
    return Array.isArray(parsed) ? parsed : [attachmentData];
  } catch (e) {
    return [attachmentData];
  }
};

const parsedARAttachments = computed(() => {
  if (!existingReport.value || !existingReport.value.attachment) return [];
  return parseAttachments(existingReport.value.attachment);
});


const isPdfModalOpen = ref(false);
const pdfFileUrl = ref('');

const closePdfModal = () => {
  isPdfModalOpen.value = false;
};

const getFileURL = (file) => {
  if (!file) return '';
  return URL.createObjectURL(file);
};

const getExistingFileURL = (filename) => {
  if (!filename) return '';
  const base = (import.meta.env.VITE_API_BASE_URL ? import.meta.env.VITE_API_BASE_URL.replace('/api/', '') : 'https://gad-ams-2-1.onrender.com');
  // Ensuring no double slashes before api
  const formattedBase = base.endsWith('/') ? base.slice(0, -1) : base;
  return `${formattedBase}/api/files/drafts/${filename}`;
};

const previewFile = (filename, folder) => {
  if (!filename) return;
  const base = (import.meta.env.VITE_API_BASE_URL ? import.meta.env.VITE_API_BASE_URL.replace('/api/', '') : 'https://gad-ams-2-1.onrender.com');
  pdfFileUrl.value = `${base}/api/files/${folder}/${filename}`;
  isPdfModalOpen.value = true;
};

const previewNewFile = (file) => {
  if (!file) return;
  pdfFileUrl.value = URL.createObjectURL(file);
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


const aDBudget = computed(() => {
  if (!existingReport.value?.activity_design || !existingReport.value.activity_design.budget_items || existingReport.value.activity_design.budget_items.length === 0) return null;
  const b = existingReport.value.activity_design.budget_items[0];
  let ob = [];
  if (b.materials_others_breakdown) { try { ob = JSON.parse(b.materials_others_breakdown); } catch(e){} }
  const mealsT = Number(b.meals_total) || 0;
  const snacksT = Number(b.snacks_total) || 0;
  const combined = Number(b.meals_and_snacks) || 0;
  const othersTotal = Number(b.others_total) || ob.reduce((s, o) => s + Number(o.amount || 0), 0);
  const grandTotal = (mealsT === 0 && snacksT === 0 && combined > 0 ? combined : mealsT) + snacksT +
    Number(b.function_room_venue || 0) + Number(b.accommodation || 0) + Number(b.equipment_rental || 0) +
    Number(b.transportation || 0) + Number(b.professional_fee_honoria || 0) + Number(b.tokens || 0) +
    Number(b.materials_and_supplies || 0) + othersTotal;
  return {
    ...b,
    grand_total: grandTotal
  };
});

const parsedADBudget = computed(() => {
  if (!existingReport.value?.activity_design || !existingReport.value.activity_design.budget_items || existingReport.value.activity_design.budget_items.length === 0) return [];
  const b = existingReport.value.activity_design.budget_items[0];
  const items = [
    { name: 'Meals and Snacks (AM/PM)', total: b.meals_and_snacks },
    { name: 'Function Room/Venue', total: b.function_room_venue },
    { name: 'Accommodation', total: b.accommodation },
    { name: 'Equipment Rental', total: b.equipment_rental },
    { name: 'Professional Fee/Honoraria', total: b.professional_fee_honoria },
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
      loadingData.value = true; // Suppress watchers during data population
      existingReport.value = response.data.data;
      
      if (Number(existingReport.value.user_id) !== Number(user.value.id)) {
        Swal.fire({
          icon: 'error',
          title: 'Access Denied',
          text: 'You are not authorized to view or edit this document.',
          confirmButtonColor: '#b979cc'
        }).then(() => {
          router.push('/college/ar-list');
        });
        loadingData.value = false;
        return;
      }
      
      const r = response.data.data;
      
      if (r.activity_design) {
        form.value.activity_classification_id = r.activity_design.classification_id || '';
        await fetchGADMandates();
        
        let ftype = r.activity_design.form_type || '';
        if (ftype && isNaN(ftype)) {
          const found = formTypes.value.find(ft => ft.name === ftype);
          if (found) ftype = found.id;
        }
        form.value.form_type = ftype;
        
        const savedMandates = r.activity_design.gad_mandate_id ? String(r.activity_design.gad_mandate_id).split(',').map(s=>s.trim()) : [];
        form.value.gad_mandate_id = GADMandates.value.filter(m => {
           const mIds = String(m.id).split(',');
           return mIds.every(id => savedMandates.includes(id));
        }).map(m => String(m.id));
        if (savedMandates.includes('Other') && !form.value.gad_mandate_id.includes('Other')) {
            form.value.gad_mandate_id.push('Other');
        }

        if (form.value.gad_mandate_id.length > 0) {
          await fetchGenderIssues(form.value.gad_mandate_id);
        }
        
        const savedIssues = r.activity_design.gender_issue_id ? String(r.activity_design.gender_issue_id).split(',').map(s=>s.trim()) : [];
        form.value.gender_issue_id = genderIssues.value.filter(m => {
           const mIds = String(m.id).split(',');
           return mIds.every(id => savedIssues.includes(id));
        }).map(m => String(m.id));
        if (savedIssues.includes('Other') && !form.value.gender_issue_id.includes('Other')) {
            form.value.gender_issue_id.push('Other');
        }
      }

      form.value.activity_title = r.activity_title || '';
      form.value.start_date = r.start_date || '';
      form.value.end_date = r.end_date || '';
      form.value.start_time = r.start_time || '';
      form.value.end_time = r.end_time || '';
      form.value.venue = r.venue || '';
      form.value.is_inside_bsu = r.is_inside_bsu == 1 || r.is_inside_bsu === true;

      form.value.attendees = r.number_of_attendees || r.attendees || '';
      form.value.male = r.male_participants || r.male || '';
      form.value.female = r.female_participants || r.female || '';
      
      // Populate budget_items from nested array
      if (r.budget_items && Array.isArray(r.budget_items) && r.budget_items.length > 0) {
        const b = r.budget_items[0];
        let ob = [];
        if (b.materials_others_breakdown) {
          try { ob = JSON.parse(b.materials_others_breakdown); } catch(e){}
        }
        
        // Fall back to meals_and_snacks if meals_total/snacks_total not stored separately (older records)
        const mealsTotal = Number(b.meals_total) || 0;
        const snacksTotal = Number(b.snacks_total) || 0;
        const mealsAndSnacks = Number(b.meals_and_snacks) || 0;
        const fallbackMeals = (mealsTotal === 0 && snacksTotal === 0 && mealsAndSnacks > 0) ? mealsAndSnacks : mealsTotal;
        form.value.budget_items = [
          { name: 'Meals', total: fallbackMeals, breakdown: null },
          { name: 'Snacks', total: snacksTotal, breakdown: null },
          { name: 'Function Room/Venue', total: b.function_room_venue || 0, breakdown: null },
          { name: 'Accommodation', total: b.accommodation || 0, breakdown: null },
          { name: 'Equipment Rental', total: b.equipment_rental || 0, breakdown: null },
          { name: 'Transportation', total: b.transportation || 0, breakdown: null },
          { name: 'Professional Fee/Honoraria', total: b.professional_fee_honoria || 0, breakdown: null },
          { name: 'Token/s', total: b.tokens || 0, breakdown: null },
          { name: 'Materials and Supplies', total: b.materials_and_supplies || 0, breakdown: null },
          { name: 'Others', total: b.others_total || 0, breakdown: null }
        ];
        
        othersList.value = ob;
        
        mealsSelected.value = {
          breakfast: Number(b.breakfast_selected) === 1,
          lunch: Number(b.lunch_selected) === 1,
          dinner: Number(b.dinner_selected) === 1
        };
        snacksSelected.value = {
          am: Number(b.am_snack_selected) === 1,
          pm: Number(b.pm_snack_selected) === 1
        };
        
        pfPax.value = Number(b.pf_pax) || '';
        tokensPax.value = Number(b.tokens_pax) || '';
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
      loadingData.value = false; // Re-enable watchers
    }
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
    loadingData.value = false;
  }
};

onMounted(async () => {
  if (!user.value.id || !['twg', 'non-twg'].includes(user.value.role)) {
    router.push('/login');
  } else {
    await fetchData();
    fetchReportDetails();
  }
});

</script>

<style scoped>
.main-viewport { flex: 1; overflow-y: auto; background: transparent; }
.loading-wrapper { display: flex; justify-content: center; align-items: center; min-height: 400px; }
.loading-spinner { border: 4px solid rgba(255,255,255,0.1); border-left-color: #b979cc; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

.page-container { min-height: 100vh; padding: 2rem; max-width: 80rem; margin: 0 auto; color: #cbd5e1; }
.layout-vertical { display: flex; flex-direction: column; gap: 24px; }
.flex-full { flex: 1; width: 100%; }

.glass-card { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); backdrop-filter: blur(24px); border-radius: 1.5rem; border: 1px solid rgba(185,121,204,0.2); color: #cbd5e1; }

.report-header { padding: 2rem; border-bottom: 1px solid rgba(185,121,204,0.15); background: rgba(0,0,0,0.2); border-radius: 1.5rem 1.5rem 0 0; color: #cbd5e1; }
.meta-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
.report-title { font-size: 26px; color: white; line-height: 1.25; margin: 1rem 0; }
.control-number { font-size: 11px; font-weight: 700; color: #b979cc; text-transform: uppercase; margin-left: 12px; font-family: monospace; }

.status-badge-view { padding: 4px 12px; border-radius: 9999px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; }
.status-badge-view.pending { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
.status-badge-view.approved { background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3); }
.status-badge-view.completed { background: rgba(34,197,94,0.15); color: #22c55e; border: 1px solid rgba(34,197,94,0.3); }
.status-badge-view.cancelled { background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }
.status-badge-view.revision { background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }

.info-grid { display: flex; flex-wrap: wrap; gap: 24px; padding-top: 16px; border-top: 1px solid rgba(185,121,204,0.1); }
.info-item { display: flex; flex-direction: column; }
.info-label { font-size: 10px; text-transform: uppercase; color: #cbd5e1; font-weight: 700; margin-bottom: 4px; display: block; }
.info-value-white { font-size: 14px; font-weight: 600; color: white; }
.info-value-purple { font-size: 14px; font-weight: 600; color: #b979cc; }

/* Revision remarks banner */
.revision-remarks-banner { display: flex; align-items: flex-start; gap: 12px; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); border-radius: 12px; padding: 14px 18px; }
.remarks-icon { color: #f87171; font-size: 22px; margin-top: 2px; flex-shrink: 0; }
.remarks-label { font-size: 10px; font-weight: 800; text-transform: uppercase; color: #f87171; margin-bottom: 4px; }
.remarks-text { font-size: 14px; color: #fca5a5; line-height: 1.5; }

.report-body { padding: 2rem; }

/* Side by side layout */
.ar-horizontal-layout { display: flex; flex-direction: column; gap: 24px; }
@media (min-width: 1280px) {
  .ar-horizontal-layout { flex-direction: row; align-items: flex-start; }
  .ar-horizontal-layout > .section-card { flex: 1; width: 50%; }
}

.section-card { background: rgba(0,0,0,0.2); border-radius: 16px; padding: 24px; border: 1px solid rgba(185,121,204,0.15); margin-bottom: 24px; }
.section-header-row { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem; }
.section-title { font-weight: 800; font-size: 13px; text-transform: uppercase; color: #b979cc; }
.icon-pink { color: #b979cc; }

.grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
@media (max-width: 768px) { .grid-2 { grid-template-columns: 1fr; } }
.full-width-info { grid-column: 1 / -1; }
.text-sm-light { font-size: 14px; color: #cbd5e1; font-weight: 500; }

/* Mandate badge boxes */
.mandate-boxes { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 5px; }
.mandate-box { background: rgba(185,121,204,0.15); border: 1px solid rgba(185,121,204,0.3); color: #f1f5f9; padding: 5px 12px; border-radius: 6px; font-size: 12px; }

/* Editable mandate checkboxes */
.mandate-checkbox-label { display: flex; align-items: center; gap: 6px; cursor: pointer; background: rgba(185,121,204,0.1); border: 1px solid rgba(185,121,204,0.25); padding: 5px 12px; border-radius: 6px; font-size: 12px; color: #f1f5f9; transition: background 0.15s; }
.mandate-checkbox-label:hover { background: rgba(185,121,204,0.2); }
.mandate-checkbox { accent-color: #b979cc; }

/* Input fields */
.custom-input-field { width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 12px 16px; font-size: 14px; color: #ffffff; transition: all 0.2s ease; }
.custom-input-field:focus { background: rgba(255,255,255,0.05); border-color: #b979cc; outline: none; box-shadow: 0 0 0 3px rgba(185,121,204,0.15); }
.custom-input-field::placeholder { color: #64748b; }
.textarea-no-resize { resize: none; }
.select-arrow-fix { appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23cbd5e1' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; }
.dark-option { background: #1e293b; color: #fff; }
.code-icon-calendar::-webkit-calendar-picker-indicator,
.code-icon-clock::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; opacity: 0.7; }

/* Tables */
.table-responsive { overflow-x: auto; border-radius: 12px; border: 1px solid rgba(185,121,204,0.15); background: rgba(0,0,0,0.2); }
.custom-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.custom-table th { background: rgba(185,121,204,0.1); color: #b979cc; font-weight: 700; text-transform: uppercase; padding: 12px 16px; text-align: left; border-bottom: 1px solid rgba(185,121,204,0.15); }
.custom-table td { padding: 12px 16px; color: #cbd5e1; border-bottom: 1px solid rgba(185,121,204,0.05); }
.custom-table tbody tr:last-child td { border-bottom: none; }
.custom-table tfoot td { border-top: 1px solid rgba(185,121,204,0.2); background: rgba(185,121,204,0.08); padding: 12px 16px; }

/* Docs */
.doc-item { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 14px; background: rgba(0,0,0,0.3); border-radius: 10px; border: 1px solid rgba(185,121,204,0.12); overflow-x: auto; }
.doc-info { display: flex; align-items: center; gap: 10px; }
.doc-pdf-icon { font-size: 28px; color: #ef4444; }
.doc-title { font-size: 13px; font-weight: 700; color: white; word-break: break-all; }
.doc-meta { font-size: 11px; color: #64748b; margin-top: 2px; }
.doc-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
.preview-btn { color: #b979cc; font-size: 11px; padding: 6px 14px; border-radius: 8px; background: rgba(0,0,0,0.3); border: 1px solid rgba(185,121,204,0.2); font-weight: 700; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
.preview-btn:hover { border-color: #b979cc; color: white; }
.download-btn-icon { background: rgba(0,0,0,0.3); border: 1px solid rgba(185,121,204,0.15); color: #cbd5e1; padding: 6px 8px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.download-btn-icon:hover { border-color: #b979cc; color: white; }

/* File upload zone */
.doc-upload-area { border: 2px dashed rgba(185,121,204,0.4); border-radius: 12px; padding: 28px 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; cursor: pointer; transition: all 0.2s; background: rgba(185,121,204,0.03); }
.doc-upload-area:hover { border-color: #b979cc; background: rgba(185,121,204,0.07); }
.upload-icon { font-size: 36px; color: #b979cc; }
.upload-text { color: #cbd5e1; font-size: 14px; font-weight: 600; }
.upload-hint { color: #64748b; font-size: 12px; }

/* Submit */
.submit-action-btn { display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); color: #fff; border: none; border-radius: 12px; padding: 14px 24px; font-size: 15px; font-weight: 700; cursor: pointer; transition: all 0.2s; width: 100%; }
.submit-action-btn:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(153,13,209,0.35); }
.submit-action-btn:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

/* Assessment sidebar */
.assessment-card-custom { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 1.5rem; padding: 2rem; border: 1px solid rgba(185,121,204,0.2); }
.read-only-remarks { width: 100%; border: 1px solid rgba(185,121,204,0.2); border-radius: 12px; padding: 14px 16px; font-size: 13px; background: rgba(0,0,0,0.3); color: #cbd5e1; min-height: 80px; line-height: 1.5; }
.action-buttons { margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(185,121,204,0.15); display: flex; flex-direction: column; gap: 8px; }
.btn-back { width: 100%; padding: 12px; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #cbd5e1; border-radius: 12px; background: rgba(0,0,0,0.3); border: 1px solid rgba(185,121,204,0.15); cursor: pointer; transition: all 0.2s; }
.btn-back:hover { color: white; border-color: #b979cc; }

/* Helpers */
.mt-1 { margin-top: 4px; }
.mt-4 { margin-top: 16px; }
.mb-2 { margin-bottom: 8px; }
.mb-4 { margin-bottom: 16px; }
.w-full { width: 100%; }
.mx-auto { margin-left: auto; margin-right: auto; }
.text-right { text-align: right; }
.text-center { text-align: center; }
.font-bold { font-weight: 700; }
.text-white { color: white; }
.text-rose-400 { color: #f472b6; }
.hover\:text-rose-300:hover { color: #fda4af; }
.text-blue-400 { color: #60a5fa; }
.uppercase { text-transform: uppercase; }
.interpretation-tag-ar { font-weight: 600; }
.text-emerald-400 { color: #34d399; }
.text-teal-400 { color: #2dd4bf; }
.text-cyan-400 { color: #22d3ee; }
.text-amber-400 { color: #fbbf24; }
.text-rose-500 { color: #f43f5e; }
.text-rose-600 { color: #e11d48; }
.attachments-list { display: flex; flex-direction: column; gap: 8px; }

/* Budget section styles from SubmitARView */
.budget-section {
  display: flex;
  flex-direction: column;
  gap: 16px;
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
/* Others breakdown styles */
.others-section-wrapper {
  width: 100%;
}
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
/* Form label for budget */
.form-label-ar {
  display: block;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #b979cc;
}
</style>
