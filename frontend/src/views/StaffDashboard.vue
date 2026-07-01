<template>
  <div class="min-h-screen bg-slate-50 flex">
    <!-- Mobile Sidebar Overlay -->
    <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <DashboardSidebar
      :isOpen="isSidebarOpen"
      @close="isSidebarOpen = false"
      roleLabel="GAD Staff"
      :menuItems="staffMenu"
      @logout="handleLogout"
    />

    <div class="flex-grow flex flex-col lg:ml-64 min-h-screen transition-all duration-300 w-full relative">
      <header 
        :class="[
          'h-20 bg-[#1a1a2e] border-b border-purple-900/30 flex items-center justify-between px-6 sticky top-0 z-30 transition-transform duration-300',
          isHeaderHidden ? '-translate-y-full' : 'translate-y-0'
        ]"
      >
        <div class="flex items-center">
          <button @click="isSidebarOpen = true" class="lg:hidden text-white hover:text-primary transition-colors flex items-center">
            <span class="material-symbols-outlined text-3xl">menu</span>
          </button>
        </div>
        
        <div v-if="user.user_role" class="flex items-center gap-3">
          <div class="px-4 py-1.5 bg-primary/20 border border-primary/50 rounded-full flex items-center gap-2 shadow-sm backdrop-blur-md">
            <span class="material-symbols-outlined text-primary text-[18px]">badge</span>
            <span class="text-white text-xs font-bold uppercase tracking-wider">{{ user.user_role }}</span>
          </div>
        </div>
      </header>

      <main class="flex-grow p-4 md:p-10 w-full overflow-x-hidden">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import DashboardSidebar from '../components/DashboardSidebar.vue';

const router = useRouter();
const isSidebarOpen = ref(false);
const isHeaderHidden = ref(false);
const lastScrollY = ref(0);
const user = ref({});

const handleScroll = () => {
  const currentScrollY = window.scrollY;
  if (currentScrollY > lastScrollY.value && currentScrollY > 50) {
    isHeaderHidden.value = true;
  } else {
    isHeaderHidden.value = false;
  }
  lastScrollY.value = currentScrollY;
};

const staffMenu = ref([
  { label: 'New Submission', icon: 'add', href: '/staff/submit' },
  { label: 'Dashboard', icon: 'dashboard', href: '/staff/dashboard' },
  { label: 'Messages', icon: 'mail', href: '/staff/messages', badge: 0 },
  { label: 'Submitted List', icon: 'list', href: '/staff/submitted-list' },
  { label: 'Activity Design List', icon: 'list', href: '/staff/ad-list' },
  { label: 'Accomplishment Report List', icon: 'list', href: '/staff/ar-list' },
  { label: 'Archives', icon: 'archive', href: '/staff/archive' },
  { label: 'Document Trash Bin', icon: 'delete', href: '/staff/trashbin' },
  { label: 'Mandates', icon: 'gavel', href: '/staff/mandates' },
  { label: 'Report Monitoring', icon: 'description', href: '/staff/reports' },
  { label: 'Budget Monitoring', icon: 'payments', href: '/staff/budget' },
  { label: 'Office/Unit Management', icon: 'domain', href: '/staff/office-management' },
  { label: 'User Management', icon: 'manage_accounts', href: '/staff/user-management' },
  { label: 'Activity Logs', icon: 'history', href: '/staff/activity-logs' },
  { label: 'User Manual', icon: 'menu_book', href: '/staff/user-manual' },
  { label: 'Data Privacy Policy', icon: 'privacy_tip', href: '/staff/data-privacy-policy' }
]);

const fetchUnreadCount = async () => {
  if (user.value?.id) {
    try {
      const res = await api.get(`/messages/unread-count/${user.value.id}`);
      if (res.data.success) {
        const msgItem = staffMenu.value.find(m => m.label === 'Messages');
        if (msgItem) msgItem.badge = res.data.count;
      }
    } catch (err) {
      console.error('Failed to fetch unread count:', err);
    }
  }
};

let unreadInterval;

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
  window.addEventListener('scroll', handleScroll);
  user.value = JSON.parse(localStorage.getItem('user') || '{}');
  if (!user.value.id || user.value.role !== 'gad_staff') {
    router.push('/login');
  } else {
    fetchUnreadCount();
    unreadInterval = setInterval(fetchUnreadCount, 30000); // Check every 30 seconds
  }
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
  if (unreadInterval) clearInterval(unreadInterval);
});
</script>

<style scoped>
/* No custom layout styles needed; handled by Tailwind */
</style>
