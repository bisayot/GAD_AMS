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
                <span class="info-label">Assessor Remarks</span>
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
                    <label class="info-label">GAD Mandate</label>
                    <div v-if="existingReport.activity_design.gad_mandate" class="mandate-boxes">
                      <span v-for="(mandate, index) in existingReport.activity_design.gad_mandate.split(',')" :key="'m'+index" class="mandate-box">
                        {{ mandate.trim() }}
                      </span>
                    </div>
                    <p v-else class="text-sm-light mt-1">---</p>
                  </div>
                  <div class="full-width-info">
                    <label class="info-label">Gender Issues</label>
                    <div v-if="existingReport.activity_design.gender_issue" class="mandate-boxes">
                      <span v-for="(issue, index) in existingReport.activity_design.gender_issue.split(',')" :key="'gi'+index" class="mandate-box">
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
                    <p class="text-sm-light mt-1">PHP {{ Number(existingReport.activity_design.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</p>
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
                          <td class="font-bold text-white text-right">{{ Number(existingReport.activity_design.proposed_budget || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) }}</td>
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
                    <select v-model="form.activity_classification_id" class="custom-input-field select-arrow-fix mt-1">
                      <option value="" disabled class="dark-option">Select Classification</option>
                      <option v-for="cls in ActClassification" :key="cls.id" :value="cls.id" class="dark-option">
                        {{ cls.classification_name }}
                      </option>
                    </select>
                  </div>
                  <div>
                    <label class="info-label">Form Type</label>
                    <input type="text" v-model="form.form_type" class="custom-input-field mt-1" readonly>
                  </div>
                  <div class="full-width-info">
                    <label class="info-label">GAD Mandate</label>
                    <div class="mandate-boxes mt-1">
                      <label v-for="mandate in GADMandates" :key="mandate.id" class="mandate-checkbox-label">
                        <input type="checkbox" :value="mandate.id.toString()" v-model="form.gad_mandate_id" class="mandate-checkbox" />
                        <span>{{ mandate.code }}</span>
                      </label>
                    </div>
                  </div>
                  <div class="full-width-info">
                    <label class="info-label">Gender Issues</label>
                    <div class="mandate-boxes mt-1">
                      <label v-for="issue in genderIssues" :key="issue.id" class="mandate-checkbox-label">
                        <input type="checkbox" :value="issue.id.toString()" v-model="form.gender_issue_id" class="mandate-checkbox" />
                        <span>{{ issue.title }}</span>
                      </label>
                      <p v-if="!form.gad_mandate_id || form.gad_mandate_id.length === 0" class="text-sm-light mt-1">Select a mandate first.</p>
                    </div>
                  </div>
                  <div>
                    <label class="info-label">Target Participants</label>
                    <p class="text-sm-light mt-1">{{ existingReport?.activity_design?.target_participants || '---' }}</p>
                  </div>
                  <div>
                    <label class="info-label">Start Date of Implementation *</label>
                    <input type="date" v-model="form.start_date" class="custom-input-field code-icon-calendar mt-1">
                  </div>
                  <div>
                    <label class="info-label">End Date of Implementation *</label>
                    <input type="date" v-model="form.end_date" class="custom-input-field code-icon-calendar mt-1">
                  </div>
                  <div>
                    <label class="info-label">Start Time *</label>
                    <input type="time" v-model="form.start_time" class="custom-input-field code-icon-clock mt-1">
                  </div>
                  <div>
                    <label class="info-label">End Time *</label>
                    <input type="time" v-model="form.end_time" class="custom-input-field code-icon-clock mt-1">
                  </div>
                  <div class="full-width-info">
                    <label class="info-label">Venue *</label>
                    <input type="text" v-model="form.venue" class="custom-input-field mt-1" placeholder="e.g., Convention Center, Main Hall">
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

                <!-- Actual Budgetary Requirements (editable) -->
                <div class="budget-section mt-4">
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
                        <button type="button" @click="previewFile(file, existingReport.is_archived ? 'archived' : 'drafts')" class="preview-btn">Preview</button>
                        <button type="button" @click="downloadFile(file, existingReport.is_archived ? 'archived' : 'drafts', 'Accomplishment_Report')" class="download-btn-icon">
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
                    <p class="upload-hint">PDF, images accepted (Max: 5MB per file)</p>
                    <input type="file" ref="fileInput" multiple @change="handleFileUpload" style="display:none" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
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
import { useRoute, useRouter } from 'vue-router';
import api from '../../api';
import Swal from 'sweetalert2';

const route = useRoute();
const router = useRouter();

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

const approvedControls = ref([]);
const loadingControls = ref(false);

const ActClassification = ref([]);
const GADMandates = ref([]);
const genderIssues = ref([]);

const fetchData = async () => {
  try {
    const [actRes, gadRes] = await Promise.all([
      api.get('get-activity-classifications'),
      api.get('get-gad-mandates')
    ]);
    ActClassification.value = actRes.data;
    GADMandates.value = gadRes.data;
  isSubmitting.value = false;
    } catch (error) {
      isSubmitting.value = false;
    console.error('Error fetching dropdown data:', error);
  }
};

const fetchGenderIssues = async (mandateIds) => {
    const ids = mandateIds || form.value?.gad_mandate_id;
    if (!ids || !Array.isArray(ids) || ids.length === 0) {
      genderIssues.value = [];
      return;
    }
  try {
    const allIssues = [];
    for (const mandateId of ids) {
       if (mandateId !== 'Other') {
           if (mandateId !== 'Other') {
             const res = await api.get(`get-gender-issues/${mandateId}`);
             allIssues.push(...res.data);
         }
       }
    }
    genderIssues.value = allIssues;
  isSubmitting.value = false;
    } catch (error) {
      isSubmitting.value = false;
    console.error('Error fetching gender issues:', error);
  }
};

const onMandateChange = () => {
  form.value.gender_issue_id = '';
  fetchGenderIssues(form.value.gad_mandate_id);
};

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

fetchVenues();

const loadingData = ref(false);

watch(() => form.value.gad_mandate_id, (newVal) => {
    if (loadingData.value) return; // Don't reset during initial data load
    form.value.gender_issue_id = [];
    fetchGenderIssues(newVal);
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

  try {
    const formData = new FormData();
    
    formData.append('venue_id', form.value.venue);

    const budgetObj = {
        meals_and_snacks: (Number(form.value.budget_items.find(i => i.name === 'Meals')?.total || 0) + Number(form.value.budget_items.find(i => i.name === 'Snacks')?.total || 0)),
        function_room_venue: Number(form.value.budget_items.find(i => i.name === 'Function Room/Venue')?.total || 0),
        accommodation: Number(form.value.budget_items.find(i => i.name === 'Accommodation')?.total || 0),
        equipment_rental: Number(form.value.budget_items.find(i => i.name === 'Equipment Rental')?.total || 0),
        professional_fee_honoria: Number(form.value.budget_items.find(i => i.name === 'Professional Fee/Honoraria')?.total || 0),
        tokens: Number(form.value.budget_items.find(i => i.name === 'Token/s')?.total || 0),
        materials_and_supplies: Number(form.value.budget_items.find(i => i.name === 'Materials and Supplies')?.total || 0),
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
      const dbKey = evalMap[item.area];
      if (dbKey) {
        evalObj[dbKey] = Number(item.rating) || 0;
      }
    });
    formData.append('evaluation_results', JSON.stringify(evalObj));

    if (form.value.activity_classification_id) formData.append('activity_classification_id', form.value.activity_classification_id);
    if (form.value.gad_mandate_id) formData.append('gad_mandate_id', form.value.gad_mandate_id);
    if (form.value.gender_issue_id) formData.append('gender_issue_id', form.value.gender_issue_id);

    Object.keys(form.value).forEach(key => {
      if (key !== 'budget_items' && key !== 'evaluation_items' && key !== 'venue') {
        formData.append(key, form.value[key]);
      }
    });
    
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
        router.push('/staff/submitted-list');
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
      loadingData.value = true; // Suppress watchers during data population
      existingReport.value = response.data.data;
      
      if (Number(existingReport.value.user_id) !== Number(user.value.id)) {
        Swal.fire({
          icon: 'error',
          title: 'Access Denied',
          text: 'You are not authorized to view or edit this document.',
          confirmButtonColor: '#b979cc'
        }).then(() => {
          router.push('/staff/ar-list');
        });
        loadingData.value = false;
        return;
      }
      const r = response.data.data;
      
      if (r.activity_design) {
        form.value.activity_classification_id = r.activity_design.classification_id || '';
        form.value.form_type = r.activity_design.form_type_name || r.activity_design.form_type || '';
        form.value.gad_mandate_id = r.activity_design.gad_mandate_id ? r.activity_design.gad_mandate_id.split(',').map(s=>s.trim()) : [];
          if (form.value.gad_mandate_id.length > 0) {
            await fetchGenderIssues(form.value.gad_mandate_id);
          }
          form.value.gender_issue_id = r.activity_design.gender_issue_id ? r.activity_design.gender_issue_id.split(',').map(s=>s.trim()) : [];
      }

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
      if (r.budget_items && Array.isArray(r.budget_items) && r.budget_items.length > 0) {
        const b = r.budget_items[0];
        form.value.budget_items.forEach(item => {
          switch (item.name) {
              case 'Meals': item.total = Number(b.meals_and_snacks) || ''; break; // Split meals_and_snacks to meals here, or adjust as needed
              case 'Snacks': item.total = ''; break; // No separate snacks in DB
              case 'Function Room/Venue': item.total = Number(b.function_room_venue) || ''; break;
              case 'Accommodation': item.total = Number(b.accommodation) || ''; break;
              case 'Equipment Rental': item.total = Number(b.equipment_rental) || ''; break;
              case 'Professional Fee/Honoraria': item.total = Number(b.professional_fee_honoria) || ''; break;
              case 'Token/s': item.total = Number(b.tokens) || ''; break;
              case 'Materials and Supplies': item.total = Number(b.materials_and_supplies) || ''; break;
              case 'Transportation': item.total = Number(b.transportation) || ''; break;
              case 'Others': item.total = ''; break; // Others is handled via othersList
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
      loadingData.value = false; // Re-enable watchers
    }
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
    loadingData.value = false;
  }
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'gad_staff') {
    router.push('/login');
  } else {
    fetchData();
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
