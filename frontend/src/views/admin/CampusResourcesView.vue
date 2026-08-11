<template>
  <div class="page-container">
    <div class="header-section">
      <h1 class="page-title">Campus Resources</h1>
      <p class="page-subtitle">Manage organizational units and physical venues across the campus.</p>
    </div>

    <!-- Tabs -->
    <div class="flex gap-4 border-b border-white/10 mb-6 px-4 sm:px-0">
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
    </div>

    <!-- Content Area -->
    <div class="tab-content">
      <OfficeManagementView v-if="activeTab === 'offices'" :is-nested="true" />
      <VenuesManagementView v-if="activeTab === 'venues'" :is-nested="true" />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import OfficeManagementView from './OfficeManagementView.vue';
import VenuesManagementView from './VenuesManagementView.vue';

const activeTab = ref('offices');
</script>

<style scoped>
.page-container {
  padding: 32px;
  max-width: 1400px;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 24px;
}

.page-title {
  font-size: 36px;
  font-weight: 800;
  color: #1e293b;
  margin: 0 0 8px 0;
}

.page-subtitle {
  color: #64748b;
  font-size: 18px;
  margin: 0;
}

.tab-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 16px 24px;
  background: transparent;
  border: none;
  border-bottom: 3px solid transparent;
  color: #64748b;
  font-weight: 700;
  font-size: 18px;
  cursor: pointer;
  transition: all 0.2s;
  margin-bottom: -1px;
}

.tab-btn:hover {
  color: #990dd1;
  background: rgba(153, 13, 209, 0.05);
  border-radius: 8px 8px 0 0;
}

.tab-btn.active-tab {
  color: #990dd1;
  border-bottom-color: #990dd1;
}

.tab-btn .material-symbols-outlined {
  font-size: 20px;
}
</style>
