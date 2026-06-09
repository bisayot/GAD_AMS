<template>
  <div class="min-h-screen bg-slate-50 flex">
    <!-- Mobile Sidebar Overlay -->
    <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <DashboardSidebar
      :isOpen="isSidebarOpen"
      @close="isSidebarOpen = false"
      roleLabel="Administrator"
      :menuItems="adminMenu"
      @logout="handleLogout"
    />

    <div class="flex-grow flex flex-col lg:ml-64 min-h-screen transition-all duration-300 w-full relative">
      <header class="h-20 bg-[#1a1a2e] border-b border-purple-900/30 flex items-center px-6 sticky top-0 z-30">
        <button @click="isSidebarOpen = true" class="lg:hidden text-white hover:text-primary transition-colors flex items-center">
          <span class="material-symbols-outlined text-3xl">menu</span>
        </button>
      </header>

      <main class="flex-grow p-4 md:p-10 w-full overflow-x-hidden">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import DashboardSidebar from '../components/DashboardSidebar.vue';

const router = useRouter();
const isSidebarOpen = ref(false);

const adminMenu = [
  { label: 'Dashboard', icon: 'dashboard', href: '/admin/dashboard' },
  { label: 'Annual Reports', icon: 'description', href: '/admin/annual-report' },
  { label: 'Submitted List', icon: 'folder', href: '/admin/submitted-list' },
  { label: 'Activity Design List', icon: 'description', href: '/admin/ad-list' },
  { label: 'Accomplishment Report List', icon: 'description', href: '/admin/ar-list' },
  { label: 'Plan & Budget', icon: 'account_balance_wallet', href: '/admin/gad-plan-budget' },
  { label: 'Mandates Management', icon: 'account_balance', href: '/admin/mandates' },
  { label: 'Archive', icon: 'archive', href: '/admin/archive' },
  { label: 'Report Monitoring', icon: 'bar_chart', href: '/admin/reports' },
  { label: 'Budget Monitoring', icon: 'account_balance_wallet', href: '/admin/budget' },
  { label: 'User Manual', icon: 'help', href: '/admin/user-manual' },
  { label: 'Data Privacy Policy', icon: 'privacy_tip', href: '/admin/data-privacy-policy' }
];

const handleLogout = async () => {
  try {
    await api.get('logout');
  } catch (err) {
    console.error('Logout failed:', err);
  } finally {
    localStorage.removeItem('user');
    localStorage.removeItem('authToken');
    router.push('/login');
  }
};

onMounted(() => {
  const user = JSON.parse(localStorage.getItem('user') || '{}');
  if (!user.id || user.role !== 'admin') {
    router.push('/login');
  }
});
</script>

<style scoped>
/* No custom layout styles needed; handled by Tailwind */
</style>
