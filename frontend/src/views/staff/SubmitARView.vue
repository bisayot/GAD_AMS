<template>
  <main class="twg-view-wrapper">
    <div class="max-w-3xl mx-auto py-10 px-6 w-full">
      <div class="mb-8">
        <h1 class="text-3xl font-extrabold tracking-tight form-main-title">Submit Accomplishment Report</h1>
        <p class="text-xs text-slate-400 mt-1.5">Fill out the accomplishment report form below. All fields marked with * are required.</p>
      </div>

      <!-- Success Banner -->
      <div v-if="successMessage" class="success-banner mb-6">
        <span class="material-symbols-outlined text-green-400">check_circle</span>
        <p>{{ successMessage }}</p>
      </div>

      <!-- Global Error Banner -->
      <div v-if="globalError" class="error-banner mb-6">
        <span class="material-symbols-outlined text-rose-400">error</span>
        <p>{{ globalError }}</p>
      </div>

      <div class="form-container-box">
        <form @submit.prevent="submitReport" class="space-y-6" novalidate>

          <!-- Control Number (approved AD dropdown) -->
          <div class="space-y-2">
            <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Activity Design Control Number *</label>
            <select
              v-model="form.control_number"
              class="custom-input-field select-arrow-fix"
              :class="{ 'input-error': errors.control_number }"
              @change="clearError('control_number')"
            >
              <option value="" class="dark-option">Select approved activity design...</option>
              <option
                v-for="ad in approvedDesigns"
                :key="ad.control_number"
                :value="ad.control_number"
                class="dark-option"
              >{{ ad.control_number }} — {{ ad.act_title }}</option>
            </select>
            <p v-if="errors.control_number" class="inline-error">{{ errors.control_number }}</p>
            <p v-if="approvedDesigns.length === 0 && !loadingDesigns" class="text-10px text-amber-400 mt-1">
              ⚠ No approved activity designs available yet. An AD must be approved by the GAD Director before you can submit an AR.
            </p>
          </div>

          <!-- Total Participants -->
          <div class="space-y-2">
            <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Total of Participants *</label>
            <input
              type="number"
              v-model="form.total_participants"
              min="0"
              class="custom-input-field"
              :class="{ 'input-error': errors.total_participants }"
              placeholder="0"
              @input="clearError('total_participants')"
            >
            <p v-if="errors.total_participants" class="inline-error">{{ errors.total_participants }}</p>
          </div>

          <!-- Male / Female Participants -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Male Participants *</label>
              <input
                type="number"
                v-model="form.male"
                min="0"
                class="custom-input-field"
                :class="{ 'input-error': errors.male }"
                placeholder="0"
                @input="clearError('male')"
              >
              <p v-if="errors.male" class="inline-error">{{ errors.male }}</p>
            </div>
            <div class="space-y-2">
              <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Female Participants *</label>
              <input
                type="number"
                v-model="form.female"
                min="0"
                class="custom-input-field"
                :class="{ 'input-error': errors.female }"
                placeholder="0"
                @input="clearError('female')"
              >
              <p v-if="errors.female" class="inline-error">{{ errors.female }}</p>
            </div>
          </div>

          <!-- Activity Rating -->
          <div class="space-y-2">
            <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Activity Rating (Percentage) *</label>
            <input
              type="number"
              v-model="form.rating"
              min="0"
              max="100"
              step="1"
              class="custom-input-field"
              :class="{ 'input-error': errors.rating }"
              placeholder="0–100%"
              @input="clearError('rating')"
            >
            <p v-if="errors.rating" class="inline-error">{{ errors.rating }}</p>

            <div class="mt-4 rating-guide-panel">
              <p class="text-11px font-bold text-slate-200 mb-2.5 tracking-wide uppercase">Rating Guide:</p>
              <div class="space-y-2 text-10px line-height-relaxed text-slate-300">
                <p><span class="font-bold text-emerald-400">81%–100% — Excellent:</span> Completed successfully, highly engaging and impactful. Exceeded expectations.</p>
                <p><span class="font-bold text-teal-400">61%–80% — Very Satisfactory:</span> Completed with minor issues, still productive and met most objectives.</p>
                <p><span class="font-bold text-cyan-400">41%–60% — Satisfactory:</span> Some issues or lack of engagement but met basic objectives.</p>
                <p><span class="font-bold text-amber-400">21%–40% — Needs Improvement:</span> Significant challenges, did not fully meet objectives.</p>
                <p><span class="font-bold text-rose-400">1%–20% — Unsatisfactory:</span> Major issues, minimal engagement, failed to meet most objectives.</p>
                <p><span class="font-bold text-rose-500">0% — Canceled:</span> Activity was canceled or did not occur.</p>
              </div>
            </div>
          </div>

          <!-- File Upload — PDF only (multiple) -->
          <div class="space-y-3">
            <label class="block text-11px font-bold uppercase tracking-wider label-highlight">Upload Documents (PDF only — multiple files allowed) *</label>
            <div
              class="upload-dropzone-box group"
              :class="{ 'dropzone-error': errors.report_files }"
              @click="$refs.fileInput.click()"
            >
              <input
                ref="fileInput"
                type="file"
                @change="handleFileUpload"
                accept=".pdf"
                multiple
                class="hidden"
              >
              <span class="text-3xl mb-2 group-hover:scale-110 transition-transform duration-200">📤</span>
              <p class="text-xs font-semibold text-white group-hover:text-[#b979cc] transition-colors">Upload Accomplishment Report &amp; Attachments</p>
              <p class="text-10px text-slate-400 mt-1">PDF format only — multiple files allowed (Max 10 MB each)</p>

              <div v-if="uploadedFiles.length > 0" class="mt-4 w-full space-y-1.5" @click.stop>
                <div v-for="(file, index) in uploadedFiles" :key="index" class="uploaded-file-tag">
                  <span class="truncate">📄 {{ file.name }}</span>
                  <span class="text-10px opacity-60 flex-shrink-0">({{ (file.size / 1024).toFixed(1) }} KB)</span>
                </div>
              </div>
            </div>
            <p v-if="errors.report_files" class="inline-error">{{ errors.report_files }}</p>
          </div>

          <!-- Submit -->
          <div class="flex justify-end pt-6">
            <button
              type="submit"
              class="submit-action-btn"
              :disabled="submitting"
            >
              <span v-if="submitting">Submitting…</span>
              <span v-else>Submit Report →</span>
            </button>
          </div>

        </form>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../api';

const router = useRouter();
const user   = ref(JSON.parse(localStorage.getItem('user') || '{}'));

// ─── State ────────────────────────────────────────────────────────────────
const approvedDesigns = ref([]);
const loadingDesigns  = ref(false);

const form = ref({
  control_number:     '',
  total_participants: '',
  male:               '',
  female:             '',
  rating:             ''
});

const uploadedFiles  = ref([]);
const fileInput      = ref(null);
const errors         = ref({});
const submitting     = ref(false);
const successMessage = ref('');
const globalError    = ref('');

// ─── Load approved designs ────────────────────────────────────────────────
const fetchApprovedDesigns = async () => {
  loadingDesigns.value = true;
  try {
    const response = await api.get('approved-designs', {
      params: { user_id: user.value.id }
    });
    if (response.data.success) {
      approvedDesigns.value = response.data.data;
    }
  } catch (err) {
    console.error('Could not load approved designs:', err);
  } finally {
    loadingDesigns.value = false;
  }
};

// ─── File handling (PDF only) ─────────────────────────────────────────────
const handleFileUpload = (event) => {
  clearError('report_files');
  const files = Array.from(event.target.files);
  const invalid = files.filter(f => {
    const ext  = f.name.split('.').pop().toLowerCase();
    const mime = f.type;
    return ext !== 'pdf' || !mime.includes('pdf');
  });
  const oversized = files.filter(f => f.size > 10 * 1024 * 1024);

  if (invalid.length > 0) {
    errors.value.report_files = `Only PDF files are accepted. Rejected: ${invalid.map(f => f.name).join(', ')}`;
    if (fileInput.value) fileInput.value.value = '';
    uploadedFiles.value = [];
    return;
  }
  if (oversized.length > 0) {
    errors.value.report_files = `File exceeds 10 MB limit: ${oversized.map(f => f.name).join(', ')}`;
    if (fileInput.value) fileInput.value.value = '';
    uploadedFiles.value = [];
    return;
  }

  uploadedFiles.value = files;
};

// ─── Validation ───────────────────────────────────────────────────────────
const validate = () => {
  const e = {};
  if (!form.value.control_number)  e.control_number     = 'Please select an approved activity design.';
  if (form.value.total_participants === '' || Number(form.value.total_participants) < 0)
                                   e.total_participants  = 'Total participants is required (≥ 0).';
  if (form.value.male === '' || Number(form.value.male) < 0)
                                   e.male               = 'Male participants is required (≥ 0).';
  if (form.value.female === '' || Number(form.value.female) < 0)
                                   e.female             = 'Female participants is required (≥ 0).';
  if (form.value.rating === '' || Number(form.value.rating) < 0 || Number(form.value.rating) > 100)
                                   e.rating             = 'Rating must be between 0 and 100.';
  if (uploadedFiles.value.length === 0)
                                   e.report_files       = 'Please upload at least one PDF document.';
  errors.value = e;
  return Object.keys(e).length === 0;
};

const clearError = (field) => {
  if (errors.value[field]) delete errors.value[field];
  globalError.value = '';
};

// ─── Submit ───────────────────────────────────────────────────────────────
const submitReport = async () => {
  successMessage.value = '';
  globalError.value    = '';

  if (!validate()) return;

  submitting.value = true;
  try {
    const formData = new FormData();
    Object.keys(form.value).forEach(key => formData.append(key, form.value[key]));
    uploadedFiles.value.forEach(file => formData.append('report_files[]', file));
    formData.append('user_id', user.value.id);

    const response = await api.post('submit-accomplishment', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    if (response.data.success) {
      successMessage.value = 'Accomplishment Report submitted successfully!';
      form.value = { control_number: '', total_participants: '', male: '', female: '', rating: '' };
      uploadedFiles.value = [];
      if (fileInput.value) fileInput.value.value = '';
    }
  } catch (error) {
    const msg = error.response?.data?.message || error.response?.data?.messages?.error || null;
    globalError.value = msg || 'Submission failed. Please check all fields and try again.';
    console.error('Submission error:', error);
  } finally {
    submitting.value = false;
  }
};

onMounted(() => {
  if (!user.value.id) {
    router.push('/login');
  } else {
    fetchApprovedDesigns();
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

.select-arrow-fix {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23b979cc' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 20px center;
  background-size: 16px;
}

.rating-guide-panel {
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(255, 255, 255, 0.07);
  border-radius: 12px;
  padding: 14px 18px;
}

.line-height-relaxed { line-height: 1.65; }

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

.success-banner {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.875rem 1rem;
  border-radius: 0.875rem;
  background: rgba(74, 222, 128, 0.1);
  border: 1px solid rgba(74, 222, 128, 0.3);
  color: #86efac;
  font-size: 0.8rem;
  font-weight: 500;
}
</style>