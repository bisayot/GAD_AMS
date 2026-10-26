<template>
  <div class="approval-page font-body min-h-screen flex justify-center px-4 pt-36 pb-12 relative overflow-y-auto">
    <div class="w-full max-w-xl relative z-10 h-fit mb-auto">

      <!-- Final state: decision made, page closes automatically -->
      <div v-if="decided" class="auth-card rounded-2xl p-8 md:p-10 shadow-2xl border text-center">
        <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-5"
             :class="decided === 'approved' ? 'bg-emerald-500/15' : 'bg-red-500/15'">
          <span class="material-symbols-outlined text-4xl"
                :class="decided === 'approved' ? 'text-emerald-600' : 'text-red-600'">
            {{ decided === 'approved' ? 'task_alt' : 'cancel' }}
          </span>
        </div>
        <h1 class="text-2xl font-extrabold mb-2">
          {{ decided === 'approved' ? 'Account Approved' : 'Registration Declined' }}
        </h1>
        <p class="text-sm text-on-surface-variant">
          The applicant has been notified by email.
          You can now safely close this window.
        </p>
        <button @click="closePage" class="btn-primary mt-6 px-6 py-2.5 rounded-full font-semibold flex items-center gap-2 mx-auto">
          <span class="material-symbols-outlined">close</span>
          Close Window
        </button>
      </div>

      <!-- Review state -->
      <div v-else class="auth-card rounded-2xl shadow-2xl border relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-blue-500"></div>

        <div class="p-6 md:p-8 border-b border-outline-variant">
          <div class="flex items-center gap-2 text-purple-600 dark:text-purple-400 mb-1">
            <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
            <span class="text-xs font-bold uppercase tracking-[0.2em] font-label">Account Approval</span>
          </div>
          <h1 class="text-2xl font-extrabold font-headline tracking-tight">Review Registration</h1>
          <p class="text-sm text-on-surface-variant mt-1">
            Confirm the sign-up details below, then approve or decline this account.
          </p>
        </div>

        <div class="p-6 md:p-8 space-y-5">
          <div v-if="loading" class="text-center py-10 text-on-surface-variant">
            <span class="material-symbols-outlined animate-spin">progress_activity</span>
            <p class="mt-2 text-sm">Loading registration details…</p>
          </div>

          <div v-else-if="error" class="rounded-xl bg-red-600 border border-red-700 text-white px-4 py-3 text-sm flex items-start gap-3 font-bold shadow-lg shadow-red-500/20 mb-6">
            <span class="material-symbols-outlined text-white text-lg shrink-0 mt-0.5">error</span>
            <span>{{ error }}</span>
          </div>

          <template v-else-if="detail">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
              <div class="sm:col-span-2">
                <div class="detail-label">Full Name</div>
                <div class="detail-value">{{ detail.full_name || 'N/A' }}</div>
              </div>
              <div>
                <div class="detail-label">Email</div>
                <div class="detail-value break-all">{{ detail.email || 'N/A' }}</div>
              </div>
              <div>
                <div class="detail-label">Role</div>
                <div class="detail-value">{{ detail.user_role || 'N/A' }}</div>
              </div>
              <div>
                <div class="detail-label">College / Office</div>
                <div class="detail-value">{{ detail.office_name || 'None / Unspecified' }}</div>
              </div>
              <div>
                <div class="detail-label">Campus</div>
                <div class="detail-value">{{ detail.location || 'N/A' }}</div>
              </div>
              <div v-if="detail.department">
                <div class="detail-label">Department</div>
                <div class="detail-value">{{ detail.department }}</div>
              </div>
              <div v-if="detail.position">
                <div class="detail-label">Position / Title</div>
                <div class="detail-value">{{ detail.position }}</div>
              </div>
              <div v-if="detail.student_id">
                <div class="detail-label">Student / ID Number</div>
                <div class="detail-value">{{ detail.student_id }}</div>
              </div>
              <div v-if="detail.year_level">
                <div class="detail-label">Year Level</div>
                <div class="detail-value">{{ detail.year_level }}</div>
              </div>
              <div>
                <div class="detail-label">Registered On</div>
                <div class="detail-value">{{ formatDate(detail.created_at) }}</div>
              </div>
              <div>
                <div class="detail-label">Status</div>
                <div class="detail-value capitalize">{{ detail.approval_status }}</div>
              </div>
            </div>

            <div v-if="detail.approval_status !== 'pending'" class="rounded-xl bg-amber-600 border border-amber-700 text-white px-4 py-3 text-sm mb-6 font-bold shadow-lg shadow-amber-500/20 flex items-start gap-3">
              <span class="material-symbols-outlined text-white text-lg shrink-0 mt-0.5">info</span>
              <div>
                A decision has already been recorded for this account
                (<strong class="capitalize">{{ detail.approval_status }}</strong>).
              </div>
            </div>

            <div v-else>
              <label class="detail-label mb-1 block">Reason for declining (optional)</label>
              <textarea v-model="reason" rows="2"
                        placeholder="e.g. Incomplete details / not a bonafide member"
                        class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-3 text-sm text-on-surface outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500"></textarea>
            </div>
          </template>
        </div>

        <div v-if="detail && detail.approval_status === 'pending'" class="p-6 md:p-8 pt-0 flex flex-col sm:flex-row gap-3">
          <button @click="decide('approved')" :disabled="deciding"
                  class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-full font-bold uppercase tracking-wide text-white bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-500 hover:to-green-500 shadow-lg transition-all disabled:opacity-50">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            {{ deciding === 'approved' ? 'Approving…' : 'Approve' }}
          </button>
          <button @click="decide('declined')" :disabled="deciding"
                  class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-full font-bold uppercase tracking-wide text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 shadow-lg transition-all disabled:opacity-50">
            <span class="material-symbols-outlined text-[20px]">cancel</span>
            {{ deciding === 'declined' ? 'Declining…' : 'Decline' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../api';

const route = useRoute();
const router = useRouter();

const detail = ref(null);
const loading = ref(true);
const error = ref('');
const deciding = ref(null);   // 'approved' | 'declined' | null
const decided = ref(null);    // final decision result
const reason = ref('');

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const d = new Date(dateString.endsWith('Z') ? dateString : dateString + 'Z');
  return d.toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
};

const fetchDetail = async () => {
  loading.value = true;
  error.value = '';
  try {
    const res = await api.get(`users/approval/${route.params.id}`);
    detail.value = res.data.data;
  } catch (err) {
    error.value = err?.message || 'Unable to load registration details.';
  } finally {
    loading.value = false;
  }
};

const decide = async (decision) => {
  deciding.value = decision;
  error.value = '';
  try {
    const url = decision === 'approved'
      ? `users/approve/${route.params.id}`
      : `users/decline/${route.params.id}`;
    await api.post(url, decision === 'declined' ? { reason: reason.value } : {});
    decided.value = decision;
  } catch (err) {
    error.value = err?.message || 'Failed to record the decision. Please try again.';
  } finally {
    deciding.value = null;
  }
};

const closePage = () => {
  window.close();
  // If the browser refuses to close a tab that was not opened by script,
  // fall back to the user management page.
  setTimeout(() => {
    let role = '';
    try {
      role = JSON.parse(localStorage.getItem('user') || '{}').role || '';
    } catch (e) { /* ignore */ }
    router.replace(role === 'admin' ? '/admin/user-management' : '/staff/user-management');
  }, 400);
};

onMounted(() => {
  const user = JSON.parse(localStorage.getItem('user') || '{}');
  if (!user.id || (user.role !== 'admin' && user.role !== 'gad_staff')) {
    router.replace('/login');
    return;
  }
  fetchDetail();
});
</script>

<style scoped>
.approval-page {
  background-color: var(--color-background, #f8fafc);
  color: var(--color-on-background, #0f172a);
}
.auth-card {
  background-color: #ffffff;
  border-color: #e2e8f0;
}
.detail-label {
  font-size: 0.7rem;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  font-weight: 700;
  color: #64748b;
}
.detail-value {
  font-size: 0.95rem;
  font-weight: 600;
  color: #0f172a;
  margin-top: 2px;
}
.btn-primary {
  background: linear-gradient(to right, #9333ea, #6366f1);
  color: #fff;
}

:global(.dark) .approval-page,
.dark .approval-page {
  background-color: #121316 !important;
  color: #f3f4f6 !important;
}
:global(.dark) .auth-card,
.dark .auth-card {
  background-color: #1e2026 !important;
  border-color: #2f333d !important;
}
:global(.dark) .detail-value,
.dark .detail-value {
  color: #f3f4f6 !important;
}
:global(.dark) .detail-label,
.dark .detail-label {
  color: #9ca3af !important;
}
</style>
