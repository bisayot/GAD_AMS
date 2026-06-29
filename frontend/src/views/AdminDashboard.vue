<template>
  <div class="admin-dashboard bg-transparent">
    <DashboardSidebar 
      roleLabel="Administrator" 
      :menuItems="adminMenu" 
      @logout="handleLogout" 
    />

    <div class="dashboard-main bg-transparent">
      <header class="dashboard-header bg-[#1a1a2e] border-b border-purple-900/30">
        <div class="flex items-center justify-between w-full h-full">
          <div>
            <h2 class="header-title">BSU GAD-AMS</h2>
            <span class="header-subtitle">Activities Management System</span>
          </div>
          <div class="flex items-center gap-3">
          </div>
        </div>
      </header>

      <main class="dashboard-main-content">
        <div class="content-wrapper">
          <router-view /> 
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import DashboardSidebar from '../components/DashboardSidebar.vue';

const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const adminMenu = ref([
  { label: 'Dashboard', icon: 'dashboard', href: '/admin/dashboard' },
  { label: 'Messages', icon: 'mail', href: '/admin/messages', badge: 0 },
  { label: 'Submitted List', icon: 'folder', href: '/admin/submitted-list' },
  { label: 'Activity Design List', icon: 'description', href: '/admin/ad-list' },
  { label: 'Accomplishment Report List', icon: 'description', href: '/admin/ar-list' },
  { label: 'Archives', icon: 'archive', href: '/admin/archive' },
  { label: 'Trash Bin', icon: 'delete', href: '/admin/trash-bin' },
  { label: 'Mandates Management', icon: 'account_balance', href: '/admin/mandates' },
  { label: 'Report Monitoring', icon: 'bar_chart', href: '/admin/reports' },
  { label: 'Budget Monitoring', icon: 'account_balance_wallet', href: '/admin/budget' },
  { label: 'Office/Unit Management', icon: 'domain', href: '/admin/office-management' },
  { label: 'User Management', icon: 'manage_accounts', href: '/admin/user-management' },
  { label: 'Activity Logs', icon: 'history', href: '/admin/activity-logs' },
  { label: 'User Manual', icon: 'help', href: '/admin/user-manual' },
  { label: 'Data Privacy Policy', icon: 'privacy_tip', href: '/admin/data-privacy-policy' }
]);

const fetchUnreadCount = async () => {
  if (user.value?.id) {
    try {
      const res = await api.get(`/messages/unread-count/${user.value.id}`);
      if (res.data.success) {
        const msgItem = adminMenu.value.find(m => m.label === 'Messages');
        if (msgItem) msgItem.badge = res.data.count;
      }
    } catch (err) {
      console.error('Failed to fetch unread count:', err);
    }
  }
};

let unreadInterval;

const handleLogout = () => {
  localStorage.removeItem("user");
  router.push("/login");
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'admin') {
    router.push('/login');
  } else {
    fetchUnreadCount();
    unreadInterval = setInterval(fetchUnreadCount, 30000); // Check every 30 seconds
  }
});

onUnmounted(() => {
  if (unreadInterval) clearInterval(unreadInterval);
});
</script>

<style scoped>
.admin-dashboard { min-height: 100vh; display: flex; background-color: transparent; }
.dashboard-main { 
  flex-grow: 1; 
  margin-left: 256px; 
  display: flex; 
  flex-direction: column; 
  min-height: 100vh; 
  background-color: #faf9fe;
  background-image: linear-gradient(to right, rgba(168, 85, 247, 0.25) 0%, #faf9fe 2%, #faf9fe 98%, rgba(168, 85, 247, 0.25) 100%);
}
.dashboard-header { position: fixed; top: 0; left: 256px; right: 0; height: 80px; z-index: 10; display: flex; align-items: center; padding: 0 40px; background: #1a1a2e; border-bottom: 1px solid rgba(185, 121, 204, 0.3); }
.header-title { font-size: 1.5rem; font-weight: 900; color: white; margin: 0; }
.header-subtitle { font-size: 0.65rem; font-weight: 700; color: #b979cc; text-transform: uppercase; letter-spacing: 0.05em; }

.dashboard-main-content { padding-top: 80px; flex-grow: 1; display: block; width: 100%; }
.content-wrapper { padding: 40px; width: 100%; }
</style>