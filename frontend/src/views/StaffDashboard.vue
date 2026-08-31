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

    <div class="flex-grow flex flex-col lg:ml-64 min-h-screen transition-all duration-300 relative">
      <header 
        :class="[
          'h-20 bg-[#1a1a2e] border-b border-purple-900/30 flex items-center justify-between px-6 sticky top-0 z-30 transition-transform duration-300',
          isHeaderHidden ? '-translate-y-full' : 'translate-y-0'
        ]"
      >
        <div class="flex items-center">
          <button @click="isSidebarOpen = true" class="lg:hidden hover:text-primary transition-colors flex items-center" style="color: white !important;">
            <span class="material-symbols-outlined text-3xl" style="color: white !important;">menu</span>
          </button>
        </div>
        
        <div v-if="user.user_role" class="flex items-center gap-4">
          <NotificationDropdown />
          <div class="px-4 py-1.5 bg-primary/20 border border-primary/50 rounded-full flex items-center gap-2 shadow-sm backdrop-blur-md">
            <span class="material-symbols-outlined text-primary text-[18px]">badge</span>
            <span class="text-white text-xs font-bold uppercase tracking-wider">{{ user.user_role }}</span>
          </div>
        </div>
      </header>

      <main :class="['flex-grow w-full overflow-x-hidden', $route.path.includes('/plan-and-budget') ? 'p-0' : 'p-4 md:p-10']">
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
import NotificationDropdown from '../components/NotificationDropdown.vue';

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
  {
    label: 'Communications', icon: 'forum',
    children: [
      { label: 'Messages', icon: 'mail', href: '/staff/messages', badge: 0 },
      { label: 'Inquiries', icon: 'contact_mail', href: '/staff/contact-inquiries', badge: 0 },
      { label: 'Publish News & IEC', icon: 'post_add', href: '/staff/publish-news-iec' }
    ]
  },
  { label: 'Submitted List', icon: 'list', href: '/staff/submitted-list' },
  { label: 'Activity Design List', icon: 'list', href: '/staff/ad-list' },
  { label: 'Accomplishment Report List', icon: 'list', href: '/staff/ar-list' },
  { label: 'Archives', icon: 'archive', href: '/staff/archive' },
  { label: 'Plan and Budget', icon: 'gavel', href: '/staff/plan-and-budget' },
  { label: 'Report Monitoring', icon: 'description', href: '/staff/reports' },
  { label: 'Budget Monitoring', icon: 'payments', href: '/staff/budget' },
  {
    label: 'System Controls', icon: 'admin_panel_settings',
    children: [
      { label: 'Campus Resources', icon: 'business_center', href: '/staff/campus-resources' },
      { label: 'User Management', icon: 'manage_accounts', href: '/staff/user-management' },
      { label: 'Activity Logs', icon: 'history', href: '/staff/activity-logs' },
      { label: 'Document Trash Bin', icon: 'delete', href: '/staff/trashbin' }
    ]
  },
  {
    label: 'Legal and Guides', icon: 'policy',
    children: [
      { label: 'User Manual', icon: 'menu_book', href: '/staff/user-manual' },
      { label: 'Data Privacy Policy', icon: 'privacy_tip', href: '/staff/data-privacy-policy' }
    ]
  }
]);

const fetchUnreadCount = async () => {
  if (user.value?.id) {
    try {
      // Fetch Messages unread count
      const msgRes = await api.get(`/messages/unread-count/${user.value.id}`);
      const commItem = staffMenu.value.find(m => m.label === 'Communications');
      
      if (commItem) {
        const msgChild = commItem.children.find(c => c.label === 'Messages');
        if (msgChild && msgRes.data.success) {
          msgChild.badge = msgRes.data.count;
        }

        // Fetch Contact Inquiries unread count
        const inqRes = await api.get(`/contact-inquiries/unread-count`);
        const inqChild = commItem.children.find(c => c.label === 'Inquiries');
        if (inqChild && inqRes.data.success) {
          inqChild.badge = inqRes.data.count;
        }
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
    unreadInterval = setInterval(fetchUnreadCount, 10000); // Check every 10 seconds
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
