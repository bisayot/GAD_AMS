<template>
  <main class="twg-view-wrapper">
    <div class="max-w-3xl mx-auto py-10 px-6 w-full">
      <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight form-main-title">Submit Activity Design</h1>
        <p class="text-xs text-slate-400 mt-1.5">Fill out the activity design form below. All fields marked with * are required.</p>
      </div>

      <!-- ── SUCCESS STATE (form hidden) ───────────────────────────── -->
      <div v-if="submitted" class="success-state-card">
        <div class="success-icon-ring">
          <span class="material-symbols-outlined success-check-icon">check_circle</span>
        </div>
        <h2 class="success-heading">Submission Successful!</h2>
        <p class="success-body">Activity Design submitted successfully! It will be reviewed by the GAD Director.</p>
        <button class="submit-again-btn" @click="resetForm">
          <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;margin-right:6px;">add_circle</span>
          Submit Another Activity Design
        </button>
      </div>

      <!-- ── FORM STATE ──────────────────────────────────────────────── -->
      <template v-else>
        <!-- Global Error Banner -->
        <div v-if="globalError" class="error-banner mb-6">
          <span class="material-symbols-outlined text-rose-400">error</span>
          <p>{{ globalError }}</p>
        </div>

        <div class="form-container-box">
          <form @submit.prevent="submitActivityDesign" class="space-y-6" novalidate>

            <!-- Office / Unit — GAD Staff only -->
            <div v-if="isGadStaff" class="space-y-2">
              <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Office / Unit *</label>

              <select
                v-model="form.office_unit_selected"
                class="custom-input-field select-arrow-fix"
                :class="{ 'input-error': errors.office_unit }"
                @change="onOfficeUnitChange"
              >
                <option value="" disabled class="dark-option">Select office/unit…</option>
                <option
                  v-for="unit in officeUnits"
                  :key="unit.office_id"
                  :value="unit.unit_name"
                  class="dark-option"
                >{{ unit.unit_name }}</option>
                <option value="Other" class="dark-option">Other (specify below)</option>
              </select>

              <!-- "Other" free-text fallback -->
              <div v-if="form.office_unit_selected === 'Other'" class="mt-2">
                <input
                  type="text"
                  v-model="form.office_unit_other"
                  class="custom-input-field"
                  :class="{ 'input-error': errors.office_unit }"
                  placeholder="Type the office/unit name…"
                  @input="clearError('office_unit')"
                >
              </div>

              <p v-if="errors.office_unit" class="inline-error">{{ errors.office_unit }}</p>
            </div>

            <!-- Form Type -->
            <div class="space-y-2">
              <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Form Type *</label>
              <select
                v-model="form.form_type"
                class="custom-input-field select-arrow-fix"
                :class="{ 'input-error': errors.form_type }"
                @change="clearError('form_type')"
              >
                <option value="" disabled class="dark-option">Select form type...</option>
                <option value="inset" class="dark-option">INSET Training</option>
                <option value="extension" class="dark-option">Extension Program</option>
                <option value="employee" class="dark-option">Employee Training</option>
              </select>
              <p v-if="errors.form_type" class="inline-error">{{ errors.form_type }}</p>
            </div>

            <!-- Activity Title -->
            <div class="space-y-2">
              <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Activity Title *</label>
              <textarea
                v-model="form.activity_title"
                rows="3"
                class="custom-input-field resize-none"
                :class="{ 'input-error': errors.activity_title }"
                placeholder="Enter the complete title of the activity"
                @input="clearError('activity_title')"
              ></textarea>
              <p v-if="errors.activity_title" class="inline-error">{{ errors.activity_title }}</p>
            </div>

            <!-- Start / End Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Start Date of Implementation *</label>
                <input
                  type="date"
                  v-model="form.start_date"
                  class="custom-input-field code-icon-calendar"
                  :class="{ 'input-error': errors.start_date }"
                  @change="clearError('start_date')"
                >
                <p v-if="errors.start_date" class="inline-error">{{ errors.start_date }}</p>
              </div>
              <div class="space-y-2">
                <label class="block text-11px font-bold uppercase tracking-wider label-highlight">End Date of Implementation *</label>
                <input
                  type="date"
                  v-model="form.end_date"
                  class="custom-input-field code-icon-calendar"
                  :class="{ 'input-error': errors.end_date }"
                  @change="clearError('end_date')"
                >
                <p v-if="errors.end_date" class="inline-error">{{ errors.end_date }}</p>
              </div>
            </div>

            <!-- Venue -->
            <div class="space-y-2">
              <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Venue *</label>
              <input
                type="text"
                v-model="form.venue"
                class="custom-input-field"
                :class="{ 'input-error': errors.venue }"
                placeholder="e.g., Convention Center, Main Hall"
                @input="clearError('venue')"
              >
              <p v-if="errors.venue" class="inline-error">{{ errors.venue }}</p>
            </div>

            <!-- Target Participants / Proposed Budget -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Target Participants *</label>
                <input
                  type="number"
                  v-model="form.target_participants"
                  class="custom-input-field"
                  :class="{ 'input-error': errors.target_participants }"
                  placeholder="Enter total participants"
                  min="1"
                  @input="clearError('target_participants')"
                >
                <p v-if="errors.target_participants" class="inline-error">{{ errors.target_participants }}</p>
              </div>
              <div class="space-y-2">
                <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Proposed Budget (PHP) *</label>
                <input
                  type="number"
                  v-model="form.proposed_budget"
                  step="0.01"
                  class="custom-input-field"
                  :class="{ 'input-error': errors.proposed_budget }"
                  placeholder="0.00"
                  min="0"
                  @input="clearError('proposed_budget')"
                >
                <p v-if="errors.proposed_budget" class="inline-error">{{ errors.proposed_budget }}</p>
              </div>
            </div>

            <!-- File Upload — PDF only -->
            <div class="space-y-3">
              <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Upload Activity Design (PDF only) *</label>
              <div
                class="upload-dropzone-box group"
                :class="{ 'dropzone-error': errors.design_file }"
                @click="$refs.fileInput.click()"
              >
                <input
                  ref="fileInput"
                  type="file"
                  @change="handleFileUpload"
                  accept=".pdf"
                  class="hidden"
                >
                <span class="text-3xl mb-2 group-hover:scale-110 transition-transform duration-200">📤</span>
                <p class="text-xs font-semibold text-white group-hover:text-[#b979cc] transition-colors">Upload Activity Design Document</p>
                <p class="text-10px text-slate-400 mt-1">PDF format only (Max 10 MB)</p>

                <div v-if="designFile" class="mt-4 w-full" @click.stop>
                  <div class="uploaded-file-tag">
                    <span class="truncate">📄 {{ designFile.name }}</span>
                    <button type="button" @click="removeFile" class="text-rose-400 font-bold hover:text-rose-500 text-xs ml-2 flex-shrink-0">Remove</button>
                  </div>
                </div>
              </div>
              <p v-if="errors.design_file" class="inline-error">{{ errors.design_file }}</p>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-6">
              <button
                type="button"
                @click="goBack"
                class="px-6 py-3 text-11px font-bold uppercase tracking-widest label-highlight hover:bg-white/5 rounded-xl transition-all"
              >
                ← Back
              </button>
              <button
                type="submit"
                class="submit-action-btn"
                :disabled="submitting"
              >
                <span v-if="submitting">Submitting…</span>
                <span v-else>Submit Design →</span>
              </button>
            </div>

          </form>
        </div>
      </template>

    </div>
  </main>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../api';

const router      = useRouter();
const user        = ref(JSON.parse(localStorage.getItem('user') || '{}'));
const officeUnits = ref([]);

// ─── Role check ──────────────────────────────────────────────────────────────
const isGadStaff = computed(() => ['Staff', 'gad_staff'].includes(user.value.role));

// ─── Fetch office units from DB ───────────────────────────────────────────────
const fetchOfficeUnits = async () => {
  try {
    const res = await api.get('office-units');
    if (res.data.success) officeUnits.value = res.data.data;
  } catch (err) {
    console.warn('Could not load office units:', err);
  }
};

// ─── State ────────────────────────────────────────────────────────────────
const blankForm = () => ({
  form_type:             '',
  activity_title:        '',
  start_date:            '',
  end_date:              '',
  venue:                 '',
  target_participants:   '',
  proposed_budget:       '',

  office_unit_selected:  '',
  office_unit_other:     '',
});

const form           = ref(blankForm());
const designFile     = ref(null);
const fileInput      = ref(null);
const errors         = ref({});
const submitting     = ref(false);
const globalError    = ref('');
const submitted      = ref(false); 

// ─── Computed: final office_unit value ───────────────────────────────────────
const resolvedOfficeUnit = computed(() => {
  if (!isGadStaff.value) return null;
  if (form.value.office_unit_selected === 'Other') return form.value.office_unit_other.trim();
  return form.value.office_unit_selected;
});

// ─── File handling ────────────────────────────────────────────────────────
const handleFileUpload = (event) => {
  clearError('design_file');
  const file = event.target.files[0];
  if (!file) return;

  const ext  = file.name.split('.').pop().toLowerCase();
  const mime = file.type;

  if (ext !== 'pdf' || !mime.includes('pdf')) {
    errors.value.design_file = 'Only PDF files are accepted. Please select a valid PDF document.';
    if (fileInput.value) fileInput.value.value = '';
    designFile.value = null;
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    errors.value.design_file = 'File size exceeds 10 MB. Please upload a smaller PDF.';
    if (fileInput.value) fileInput.value.value = '';
    designFile.value = null;
    return;
  }

  designFile.value = file;
};

const removeFile = () => {
  designFile.value = null;
  if (fileInput.value) fileInput.value.value = '';
  clearError('design_file');
};

// ─── Office/Unit change handler ───────────────────────────────────────────────
const onOfficeUnitChange = () => {
  form.value.office_unit_other = '';
  clearError('office_unit');
};

// ─── Validation ───────────────────────────────────────────────────────────
const validate = () => {
  const e = {};

  // GAD Staff: validate office/unit
  if (isGadStaff.value) {
    if (!form.value.office_unit_selected) {
      e.office_unit = 'Please select an office/unit.';
    } else if (form.value.office_unit_selected === 'Other' && !form.value.office_unit_other.trim()) {
      e.office_unit = 'Please specify the office/unit name.';
    }
  }

  if (!form.value.form_type)             e.form_type           = 'Please select a form type.';
  if (!form.value.activity_title.trim()) e.activity_title      = 'Activity title is required.';
  if (!form.value.start_date)            e.start_date          = 'Start date is required.';
  if (!form.value.end_date)              e.end_date            = 'End date is required.';
  if (form.value.start_date && form.value.end_date && form.value.end_date < form.value.start_date) {
    e.end_date = 'End date must be on or after the start date.';
  }
  if (!form.value.venue.trim())          e.venue               = 'Venue is required.';
  if (!form.value.target_participants || Number(form.value.target_participants) < 1)
                                         e.target_participants = 'Enter a valid number of participants (≥ 1).';
  if (!form.value.proposed_budget && form.value.proposed_budget !== 0)
                                         e.proposed_budget     = 'Proposed budget is required.';
  if (!designFile.value)                 e.design_file         = 'Please upload the activity design PDF document.';
  errors.value = e;
  return Object.keys(e).length === 0;
};

const clearError = (field) => {
  if (errors.value[field]) delete errors.value[field];
  globalError.value = '';
};

// ─── Submit ───────────────────────────────────────────────────────────────
const submitActivityDesign = async () => {
  globalError.value = '';

  if (!validate()) return;

  submitting.value = true;
  try {
    const formData = new FormData();
    formData.append('form_type',           form.value.form_type);
    formData.append('activity_title',      form.value.activity_title);
    formData.append('start_date',          form.value.start_date);
    formData.append('end_date',            form.value.end_date);
    formData.append('venue',               form.value.venue);
    formData.append('target_participants', form.value.target_participants);
    formData.append('proposed_budget',     form.value.proposed_budget);
    formData.append('design_file',         designFile.value);
    formData.append('user_id',             user.value.id);

    // For Staff: send the selected office/unit
    if (isGadStaff.value && resolvedOfficeUnit.value) {
      formData.append('office_unit', resolvedOfficeUnit.value);
    }

    const response = await api.post('submit-activity-design', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    if (response.data.success) {
      submitted.value = true;
    }
  } catch (error) {
    const msg = error.response?.data?.message || error.response?.data?.messages?.error || null;
    globalError.value = msg || 'Submission failed. Please check all fields and try again.';
    console.error('Submission error:', error);
  } finally {
    submitting.value = false;
  }
};

// ─── Reset (Submit Again) ─────────────────────────────────────────────────
const resetForm = () => {
  form.value    = blankForm();
  designFile.value = null;
  if (fileInput.value) fileInput.value.value = '';
  errors.value   = {};
  globalError.value = '';
  submitted.value = false;
};

const goBack = () => router.push('/staff/submit');

onMounted(() => {
  if (!user.value.id || !['Staff', 'gad_staff'].includes(user.value.role)) {
    router.push('/login');
  } else {
    fetchOfficeUnits();
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

.text-11px { font-size: 11px; }
.text-10px { font-size: 10px; }
.text-3xl  { font-size: 26px; }

.form-main-title {
  color: #16213e;
  letter-spacing: -0.02em;
}

/* ── Success state card ───────────────────────────────────────── */
.success-state-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 1.25rem;
  padding: 3.5rem 2.5rem;
  background: linear-gradient(145deg, #1a1a2e 0%, #16213e 100%);
  border: 1px solid rgba(74, 222, 128, 0.25);
  border-radius: 20px;
  box-shadow: 0 20px 40px rgba(10, 10, 20, 0.4), 0 0 0 1px rgba(74, 222, 128, 0.08);
  text-align: center;
  animation: fadeInUp 0.45s ease both;
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}

.success-icon-ring {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  background: rgba(74, 222, 128, 0.12);
  border: 2px solid rgba(74, 222, 128, 0.35);
  display: flex;
  align-items: center;
  justify-content: center;
}

.success-check-icon {
  font-size: 2.25rem;
  color: #4ade80;
}

.success-heading {
  font-size: 1.4rem;
  font-weight: 900;
  letter-spacing: -0.02em;
  color: #f1f5f9;
}

.success-body {
  font-size: 0.875rem;
  color: #94a3b8;
  max-width: 400px;
  line-height: 1.6;
}

.submit-again-btn {
  margin-top: 0.5rem;
  padding: 12px 32px;
  background: linear-gradient(135deg, rgba(74, 222, 128, 0.15) 0%, rgba(74, 222, 128, 0.08) 100%);
  border: 1px solid rgba(74, 222, 128, 0.35);
  border-radius: 12px;
  color: #4ade80;
  font-size: 0.875rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.25s ease;
  display: flex;
  align-items: center;
}

.submit-again-btn:hover {
  background: linear-gradient(135deg, rgba(74, 222, 128, 0.25) 0%, rgba(74, 222, 128, 0.15) 100%);
  border-color: rgba(74, 222, 128, 0.55);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(74, 222, 128, 0.2);
}

/* ── Form ─────────────────────────────────────────────────────── */
.form-container-box {
  background: linear-gradient(145deg, #1a1a2e 0%, #16213e 100%);
  border: 1px solid rgba(185, 121, 204, 0.2);
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0 20px 40px rgba(10, 10, 20, 0.4);
}

.label-highlight {
  color: #b979cc;
  letter-spacing: 0.05em;
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
  color: #64748b;
}

.dark-option {
  background-color: #16213e;
  color: #ffffff;
}

.code-icon-calendar::-webkit-calendar-picker-indicator {
  filter: invert(1);
  cursor: pointer;
  opacity: 0.7;
}

.code-icon-calendar::-webkit-calendar-picker-indicator:hover {
  opacity: 1;
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

.submit-action-btn {
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
  color: #ffffff;
  padding: 14px 40px;
  border-radius: 12px;
  font-weight: 700;
  font-size: 14px;
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

.submit-action-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.resize-none { resize: none; }

.select-arrow-fix {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23b979cc' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 20px center;
  background-size: 16px;
}

/* ── Validation styles ─────────────────────────────────── */
.inline-error {
  font-size: 0.6875rem;
  font-weight: 600;
  color: #f87171;
  margin-top: 0.25rem;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.inline-error::before {
  content: '⚠';
  font-size: 0.6rem;
}

.input-error {
  border-color: rgba(248, 113, 113, 0.6) !important;
  box-shadow: 0 0 0 2px rgba(248, 113, 113, 0.15);
}

.dropzone-error {
  border-color: rgba(248, 113, 113, 0.5) !important;
}

.error-banner {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.875rem 1rem;
  border-radius: 0.875rem;
  background: rgba(248, 113, 113, 0.1);
  border: 1px solid rgba(248, 113, 113, 0.3);
  color: #fca5a5;
  font-size: 0.8rem;
  font-weight: 500;
}
</style>