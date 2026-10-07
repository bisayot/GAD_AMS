<template>
  <div class="communications-header-card group">
    <!-- Ambient Glow Backgrounds -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>

    <div class="flex items-center gap-4 relative z-10">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-fuchsia-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30">
        <span class="material-symbols-outlined text-3xl">forum</span>
      </div>
      <div>
        <h1 class="header-title">Communications</h1>
        <p class="header-subtitle">View and manage your conversations, announcements, and direct messages.</p>
      </div>
    </div>
    
    <div class="header-nav-tabs" v-if="showInquiries">
      <router-link 
        :to="messagesRoute"
        class="nav-tab-btn"
        :class="{ active: activeTab === 'messages' }"
      >
        <span class="material-symbols-outlined text-lg">mail</span>
        Messages
      </router-link>
      <router-link 
        :to="inquiriesRoute"
        class="nav-tab-btn"
        :class="{ active: activeTab === 'inquiries' }"
      >
        <span class="material-symbols-outlined text-lg">contact_mail</span>
        Inquiries
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const props = defineProps({
  activeTab: {
    type: String,
    required: true // 'messages' or 'inquiries'
  }
});

const route = useRoute();

// Determine base role from route path (e.g., /admin/messages -> admin)
const baseRole = computed(() => {
  const pathParts = route.path.split('/');
  return pathParts[1] || 'admin';
});

const messagesRoute = computed(() => `/${baseRole.value}/messages`);
const inquiriesRoute = computed(() => `/${baseRole.value}/contact-inquiries`);

// Only Admin and Staff have Inquiries functionality
const showInquiries = computed(() => {
  return ['admin', 'staff'].includes(baseRole.value);
});
</script>

<style scoped>
.communications-header-card {
  position: relative;
  overflow: hidden;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 2rem;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: flex-start;
  gap: 1rem;
  transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

@media (min-width: 768px) {
  .communications-header-card {
    flex-direction: row;
    align-items: center;
  }
}

.ambient-glow {
  position: absolute;
  border-radius: 9999px;
  filter: blur(48px);
  pointer-events: none;
}

.glow-1 {
  top: 0;
  right: 0;
  margin-top: -4rem;
  margin-right: -4rem;
  width: 16rem;
  height: 16rem;
  background: rgba(168, 85, 247, 0.08);
}

.glow-2 {
  bottom: 0;
  left: 0;
  margin-bottom: -4rem;
  margin-left: -4rem;
  width: 12rem;
  height: 12rem;
  background: rgba(59, 130, 246, 0.08);
}

.header-title {
  font-size: 1.875rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  margin: 0;
  color: #0f172a;
}

.header-subtitle {
  font-size: 0.875rem;
  margin: 0.25rem 0 0 0;
  color: #64748b;
}

.header-nav-tabs {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: #f1f5f9;
  padding: 0.375rem;
  border-radius: 0.75rem;
  border: 1px solid #e2e8f0;
  position: relative;
  z-index: 10;
}

.nav-tab-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.625rem 1.5rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 700;
  text-decoration: none;
  transition: all 0.2s ease;
  color: #64748b;
}

.nav-tab-btn:hover {
  color: #7e22ce;
  background: rgba(255, 255, 255, 0.8);
}

.nav-tab-btn.active {
  background: linear-gradient(to right, #d946ef, #9333ea);
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(147, 51, 234, 0.3);
}

/* Dark mode styles */
.dark .communications-header-card,
html.dark .communications-header-card {
  background: #0f172a;
  border-color: rgba(168, 85, 247, 0.2);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
}

.dark .glow-1,
html.dark .glow-1 {
  background: rgba(168, 85, 247, 0.05);
}

.dark .glow-2,
html.dark .glow-2 {
  background: rgba(59, 130, 246, 0.05);
}

.dark .header-title,
html.dark .header-title {
  color: #ffffff;
}

.dark .header-subtitle,
html.dark .header-subtitle {
  color: rgba(233, 213, 255, 0.6);
}

.dark .header-nav-tabs,
html.dark .header-nav-tabs {
  background: rgba(0, 0, 0, 0.4);
  border-color: rgba(168, 85, 247, 0.2);
}

.dark .nav-tab-btn,
html.dark .nav-tab-btn {
  color: #94a3b8;
}

.dark .nav-tab-btn:hover,
html.dark .nav-tab-btn:hover {
  color: #ffffff;
  background: transparent;
}

.dark .nav-tab-btn.active,
html.dark .nav-tab-btn.active {
  background: linear-gradient(to right, #d946ef, #9333ea);
  color: #ffffff;
}
</style>
