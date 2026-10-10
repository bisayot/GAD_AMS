<template>
  <div v-if="isOpen" class="modal-backdrop" @click.self="$emit('close')">
    <div class="modal-container">
      
      <!-- Modal Header -->
      <div class="modal-header">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">account_circle</span>
          <h3 class="text-lg font-bold modal-title">Proponent Profile</h3>
        </div>
        <button type="button" @click="$emit('close')" class="close-btn" title="Close">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="py-12 flex flex-col items-center justify-center gap-3">
        <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-3xl animate-spin">refresh</span>
        <span class="text-sm text-slate-600 dark:text-purple-200">Loading proponent information...</span>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="py-8 px-4 text-center">
        <span class="material-symbols-outlined text-red-500 dark:text-red-400 text-3xl mb-2">error</span>
        <p class="text-sm text-red-600 dark:text-red-200">{{ error }}</p>
        <button @click="fetchData" class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-white/10 dark:hover:bg-white/20 text-slate-800 dark:text-white rounded-lg text-xs font-semibold">Try Again</button>
      </div>

      <!-- Profile Content -->
      <div v-else-if="profile" class="modal-body">
        
        <!-- User Top Card -->
        <div class="profile-hero">
          <div class="avatar-box">
            <img v-if="profile.profile_picture" :src="getAvatarUrl(profile.profile_picture)" alt="Avatar" class="w-full h-full object-cover" />
            <span v-else>{{ initials }}</span>
          </div>

          <div class="hero-details">
            <h4 class="text-xl font-bold hero-name flex items-center gap-2 flex-wrap">
              {{ profile.full_name || 'N/A' }}
            </h4>
            <div class="flex items-center gap-2 flex-wrap mt-1">
              <span class="badge-role">{{ profile.user_role || (profile.role === 'non-twg' ? 'Proponent' : 'TWG') }}</span>
              <span v-if="profile.position" class="badge-position">{{ profile.position }}</span>
            </div>
            <p v-if="profile.email" class="text-xs text-slate-500 dark:text-purple-200/80 mt-1 flex items-center gap-1">
              <span class="material-symbols-outlined text-xs">mail</span>
              <a :href="'mailto:' + profile.email" class="hover:underline text-purple-600 dark:text-purple-300">{{ profile.email }}</a>
            </p>
          </div>
        </div>

        <!-- Detail Grids -->
        <div class="details-grid">
          
          <div class="detail-item">
            <span class="detail-label">Position / Title</span>
            <span class="detail-val flex items-center gap-1.5" :class="{ 'text-muted-val': !profile.position }">
              <span class="material-symbols-outlined text-xs text-indigo-500">badge</span>
              {{ profile.position || 'N/A' }}
            </span>
          </div>

          <div class="detail-item">
            <span class="detail-label">Student ID / Employee ID</span>
            <span class="detail-val font-mono flex items-center gap-1.5" :class="{ 'text-muted-val': !profile.student_id }">
              <span class="material-symbols-outlined text-xs text-amber-500">pin</span>
              {{ profile.student_id || 'N/A' }}
            </span>
          </div>

          <div class="detail-item">
            <span class="detail-label">Campus Location</span>
            <span class="detail-val flex items-center gap-1.5">
              <span class="material-symbols-outlined text-xs text-purple-600 dark:text-purple-400">location_on</span>
              {{ profile.location || 'La Trinidad Campus' }}
            </span>
          </div>

          <div class="detail-item">
            <span class="detail-label">College / Office</span>
            <span class="detail-val flex items-center gap-1.5" :class="{ 'text-muted-val': !profile.office_name || profile.office_name === 'N/A' }">
              <span class="material-symbols-outlined text-xs text-blue-600 dark:text-blue-400">business</span>
              <span>{{ profile.office_name || 'N/A' }}</span>
              <span v-if="profile.office_acronym" class="text-purple-600 dark:text-purple-300 font-mono text-xs font-bold">({{ profile.office_acronym }})</span>
            </span>
          </div>

          <div class="detail-item">
            <span class="detail-label">Department</span>
            <span class="detail-val flex items-center gap-1.5" :class="{ 'text-muted-val': !profile.department }">
              <span class="material-symbols-outlined text-xs text-teal-600 dark:text-teal-400">apartment</span>
              {{ profile.department || 'N/A' }}
            </span>
          </div>

          <div class="detail-item">
            <span class="detail-label">Year Level</span>
            <span class="detail-val flex items-center gap-1.5" :class="{ 'text-muted-val': !profile.year_level }">
              <span class="material-symbols-outlined text-xs text-emerald-600 dark:text-emerald-400">school</span>
              {{ profile.year_level || 'N/A' }}
            </span>
          </div>

          <div class="detail-item">
            <span class="detail-label">Sex</span>
            <span class="detail-val flex items-center gap-1.5" :class="{ 'text-muted-val': !profile.sex || profile.sex === 'Not specified' }">
              <span class="material-symbols-outlined text-xs text-pink-500">wc</span>
              {{ (profile.sex && profile.sex !== 'Not specified') ? profile.sex : 'N/A' }}
            </span>
          </div>

          <div class="detail-item">
            <span class="detail-label">Membership Type</span>
            <span class="detail-val flex items-center gap-1.5">
              <span class="material-symbols-outlined text-xs text-purple-600 dark:text-purple-400">group</span>
              {{ profile.user_role || (profile.role === 'non-twg' ? 'Proponent' : 'TWG Member') }}
            </span>
          </div>

        </div>

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer">
        <button type="button" @click="$emit('close')" class="close-action-btn">Close</button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import api from '../api';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  userId: {
    type: [Number, String],
    default: null
  }
});

defineEmits(['close']);

const profile = ref(null);
const loading = ref(false);
const error = ref('');

const initials = computed(() => {
  if (!profile.value?.full_name) return 'U';
  return profile.value.full_name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
});

const getAvatarUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  const baseUrl = import.meta.env.VITE_API_BASE_URL?.replace('/api/', '/') || 'http://localhost:8080/';
  return `${baseUrl.replace(/\/$/, '')}/${path.replace(/^\//, '')}`;
};

const fetchData = async () => {
  if (!props.userId) return;
  loading.value = true;
  error.value = '';
  try {
    const res = await api.get(`users/profile/${props.userId}`);
    if (res.data?.success) {
      profile.value = res.data.data;
    } else {
      error.value = res.data?.message || 'Proponent profile not found.';
    }
  } catch (err) {
    console.error('Failed to fetch proponent profile:', err);
    error.value = err.response?.data?.message || err.message || 'Unable to load profile.';
  } finally {
    loading.value = false;
  }
};

watch(() => props.isOpen, (newVal) => {
  if (newVal && props.userId) {
    fetchData();
  } else if (!newVal) {
    profile.value = null;
  }
});

watch(() => props.userId, (newVal) => {
  if (props.isOpen && newVal) {
    fetchData();
  }
});
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  inset: 0;
  z-index: 9999;
  background-color: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(6px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  animation: fadeIn 0.2s ease-out;
}

.modal-container {
  width: 100%;
  max-width: 520px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 1.25rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  overflow: hidden;
  animation: scaleUp 0.2s ease-out;
  transition: all 0.3s ease;
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #f1f5f9;
  background: #ffffff;
  flex-shrink: 0;
}

.modal-title {
  color: #0f172a;
}

.close-btn {
  color: #64748b;
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.25rem;
  border-radius: 0.5rem;
  transition: all 0.2s;
}

.close-btn:hover {
  color: #0f172a;
  background: #f1f5f9;
}

.modal-body {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  overflow-y: auto;
  flex: 1;
  min-height: 0;
}

.profile-hero {
  display: flex;
  align-items: center;
  gap: 1.25rem;
  background: #f8fafc;
  padding: 1.25rem;
  border-radius: 1rem;
  border: 1px solid #e2e8f0;
}

.hero-name {
  color: #0f172a;
}

.avatar-box {
  width: 4.5rem;
  height: 4.5rem;
  border-radius: 1rem;
  background: linear-gradient(135deg, #7e22ce, #9333ea);
  border: 2px solid #e9d5ff;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 800;
  font-size: 1.5rem;
  overflow: hidden;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(126, 34, 206, 0.25);
}

.hero-details {
  flex: 1;
  min-width: 0;
}

.badge-role {
  background: #f3e8ff;
  color: #7e22ce;
  border: 1px solid #e9d5ff;
  font-size: 0.7rem;
  font-weight: 700;
  padding: 0.2rem 0.5rem;
  border-radius: 0.375rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.badge-position {
  background: #eff6ff;
  color: #2563eb;
  border: 1px solid #dbeafe;
  font-size: 0.7rem;
  font-weight: 600;
  padding: 0.2rem 0.5rem;
  border-radius: 0.375rem;
}

.details-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.875rem;
}

@media (max-width: 480px) {
  .details-grid {
    grid-template-columns: 1fr;
  }
}

.detail-item {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.detail-label {
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #64748b;
}

.detail-val {
  font-size: 0.9rem;
  font-weight: 600;
  color: #0f172a;
  word-break: break-word;
}

.text-muted-val {
  color: #94a3b8 !important;
  font-weight: 500 !important;
}

.modal-footer {
  padding: 1rem 1.5rem;
  border-top: 1px solid #f1f5f9;
  display: flex;
  justify-content: flex-end;
  background: #ffffff;
  flex-shrink: 0;
}

.close-action-btn {
  padding: 0.5rem 1.25rem;
  background: #f1f5f9;
  border: 1px solid #cbd5e1;
  color: #334155;
  font-weight: 600;
  font-size: 0.875rem;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.2s;
}

.close-action-btn:hover {
  background: #e2e8f0;
  color: #0f172a;
}

/* ==========================================================================
   Dark Mode Overrides
   ========================================================================== */
:global(.dark) .modal-container,
.dark .modal-container {
  background: linear-gradient(135deg, #131021 0%, #0d0b17 100%);
  border-color: rgba(168, 85, 247, 0.25);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 30px rgba(168, 85, 247, 0.15);
}

:global(.dark) .modal-header,
.dark .modal-header {
  border-bottom-color: rgba(255, 255, 255, 0.08);
  background: rgba(255, 255, 255, 0.02);
}

:global(.dark) .modal-title,
.dark .modal-title {
  color: #ffffff;
}

:global(.dark) .close-btn,
.dark .close-btn {
  color: #94a3b8;
}

:global(.dark) .close-btn:hover,
.dark .close-btn:hover {
  color: #ffffff;
  background: rgba(255, 255, 255, 0.1);
}

:global(.dark) .profile-hero,
.dark .profile-hero {
  background: rgba(255, 255, 255, 0.03);
  border-color: rgba(255, 255, 255, 0.05);
}

:global(.dark) .hero-name,
.dark .hero-name {
  color: #ffffff;
}

:global(.dark) .avatar-box,
.dark .avatar-box {
  background: linear-gradient(135deg, #6b21a8, #3b0764);
  border-color: rgba(192, 132, 252, 0.4);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

:global(.dark) .badge-role,
.dark .badge-role {
  background: rgba(168, 85, 247, 0.2);
  color: #d8b4fe;
  border-color: rgba(168, 85, 247, 0.3);
}

:global(.dark) .badge-position,
.dark .badge-position {
  background: rgba(59, 130, 246, 0.2);
  color: #93c5fd;
  border-color: rgba(59, 130, 246, 0.3);
}

:global(.dark) .detail-item,
.dark .detail-item {
  background: rgba(0, 0, 0, 0.25);
  border-color: rgba(255, 255, 255, 0.05);
}

:global(.dark) .detail-label,
.dark .detail-label {
  color: #94a3b8;
}

:global(.dark) .detail-val,
.dark .detail-val {
  color: #f1f5f9;
}

:global(.dark) .text-muted-val,
.dark .text-muted-val {
  color: #64748b !important;
}

:global(.dark) .modal-footer,
.dark .modal-footer {
  border-top-color: rgba(255, 255, 255, 0.08);
  background: rgba(255, 255, 255, 0.01);
}

:global(.dark) .close-action-btn,
.dark .close-action-btn {
  background: rgba(255, 255, 255, 0.08);
  border-color: rgba(255, 255, 255, 0.1);
  color: #e2e8f0;
}

:global(.dark) .close-action-btn:hover,
.dark .close-action-btn:hover {
  background: rgba(255, 255, 255, 0.15);
  color: #ffffff;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes scaleUp {
  from { transform: scale(0.95); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
</style>
