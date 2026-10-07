<template>
  <div class="page-container">
    <div class="resources-header-card">
      <div class="header-section">
        <h1 class="page-title">Campus Resources</h1>
        <p class="page-subtitle">Manage organizational units and physical venues across the campus.</p>
      </div>

      <!-- Tabs -->
      <div class="tabs-container flex flex-wrap overflow-x-auto gap-4 px-1">
        <button 
          class="tab-btn" 
          :class="{ 'active-tab': activeTab === 'offices' }"
          @click="activeTab = 'offices'"
        >
          <span class="material-symbols-outlined">domain</span>
          Offices & Units
        </button>
        <button 
          class="tab-btn" 
          :class="{ 'active-tab': activeTab === 'venues' }"
          @click="activeTab = 'venues'"
        >
          <span class="material-symbols-outlined">location_on</span>
          Venues
        </button>
        <button 
          class="tab-btn" 
          :class="{ 'active-tab': activeTab === 'holidays' }"
          @click="activeTab = 'holidays'"
        >
          <span class="material-symbols-outlined">event_busy</span>
          Holidays
        </button>
      </div>
    </div>

    <!-- Content Area -->
    <div class="tab-content overflow-x-auto">
      <div class="min-w-[1024px]">
        <OfficeManagementView v-if="activeTab === 'offices'" :is-nested="true" />
        <VenuesManagementView v-if="activeTab === 'venues'" :is-nested="true" />
        <HolidaysManagementView v-if="activeTab === 'holidays'" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import OfficeManagementView from './OfficeManagementView.vue';
import VenuesManagementView from './VenuesManagementView.vue';
import HolidaysManagementView from './HolidaysManagementView.vue';

const activeTab = ref('offices');
</script>

<style scoped>
.page-container {
  padding: 32px;
  max-width: 1400px;
  margin: 0 auto;
}

.resources-header-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 1.5rem;
  padding: 1.75rem 2rem 1.25rem 2rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
  transition: background 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

.header-section {
  margin-bottom: 1.25rem;
}

.page-title {
  font-size: 1.75rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  color: #0f172a;
  margin: 0;
}

.page-subtitle {
  font-size: 1rem;
  color: #64748b;
  margin-top: 0.35rem;
  margin-bottom: 0;
}

.tabs-container {
  border-bottom: 2px solid #e2e8f0;
}

.tab-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  background: transparent;
  border: none;
  border-bottom: 3px solid transparent;
  color: #475569;
  font-weight: 700;
  font-size: 1.05rem;
  cursor: pointer;
  transition: all 0.2s;
  margin-bottom: -2px;
}

.tab-btn .material-symbols-outlined {
  font-size: 20px;
  color: #64748b;
  transition: color 0.2s;
}

.tab-btn:hover {
  color: #0f172a;
  background: rgba(0, 0, 0, 0.04);
  border-radius: 8px 8px 0 0;
}
.tab-btn:hover .material-symbols-outlined {
  color: #0f172a;
}

.tab-btn.active-tab {
  color: #7e22ce;
  border-bottom-color: #7e22ce;
  font-weight: 800;
}
.tab-btn.active-tab .material-symbols-outlined {
  color: #7e22ce;
}

/* ================= DARK MODE OVERRIDES ================= */
.dark .resources-header-card,
html.dark .resources-header-card {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border-color: rgba(185, 121, 204, 0.2) !important;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3) !important;
}

.dark .page-title,
html.dark .page-title {
  color: #ffffff !important;
}

.dark .page-subtitle,
html.dark .page-subtitle {
  color: #cbd5e1 !important;
}

.dark .tabs-container,
html.dark .tabs-container {
  border-bottom: 2px solid rgba(185, 121, 204, 0.2) !important;
}

.dark .tab-btn,
html.dark .tab-btn {
  color: #94a3b8 !important;
}

.dark .tab-btn .material-symbols-outlined,
html.dark .tab-btn .material-symbols-outlined {
  color: #94a3b8 !important;
}

.dark .tab-btn:hover,
html.dark .tab-btn:hover {
  color: #ffffff !important;
  background: rgba(255, 255, 255, 0.06) !important;
}

.dark .tab-btn:hover .material-symbols-outlined,
html.dark .tab-btn:hover .material-symbols-outlined {
  color: #ffffff !important;
}

.dark .tab-btn.active-tab,
html.dark .tab-btn.active-tab {
  color: #c084fc !important;
  border-bottom-color: #c084fc !important;
}

.dark .tab-btn.active-tab .material-symbols-outlined,
html.dark .tab-btn.active-tab .material-symbols-outlined {
  color: #c084fc !important;
}
</style>
