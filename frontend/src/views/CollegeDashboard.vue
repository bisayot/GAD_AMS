<template>
  <div class="min-h-screen bg-slate-50 flex flex-col">
    <!-- Mobile Sidebar Overlay -->
    <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <DashboardSidebar
      :isOpen="isSidebarOpen"
      @close="isSidebarOpen = false"
      roleLabel="College/Unit"
      :menuItems="collegeMenu"
      :desktopHidden="true"
      @logout="handleLogout"
    />

    <!-- Main Navigation Header -->
    <header class="h-20 bg-[#1a1a2e] border-b border-purple-900/30 flex items-center justify-between px-6 sticky top-0 z-40 transition-transform duration-300">
      <div class="flex items-center gap-3">
        <!-- Mobile Menu Trigger -->
        <button @click="isSidebarOpen = true" class="lg:hidden text-slate-300 hover:text-white transition-colors flex items-center p-1">
          <span class="material-symbols-outlined text-3xl">menu</span>
        </button>

        <!-- Branding Logo & Title -->
        <div class="flex items-center gap-3">
          <img src="/images/logo.png" alt="BSU Logo" class="h-10 w-auto object-contain" />
          <div class="flex flex-col select-none">
            <span class="text-[9px] font-bold text-[#b979cc] tracking-widest leading-none">BSU</span>
            <span class="text-base font-extrabold text-white tracking-tight leading-tight">GAD-AMS</span>
          </div>
        </div>
      </div>

      <!-- Desktop Navigation Menu -->
      <nav class="hidden lg:flex items-center gap-2">
        <!-- New Submission Button -->
        <router-link 
          to="/college/submit" 
          class="flex items-center gap-1.5 px-4 py-2 bg-[#b979cc]/20 border border-[#b979cc]/40 rounded-xl text-[#b979cc] hover:bg-[#b979cc]/30 hover:text-white transition-all text-sm font-bold mr-2 shadow-sm"
        >
          <span class="material-symbols-outlined text-lg">add</span>
          <span>New Submission</span>
        </router-link>

        <!-- Dashboard Link -->
        <router-link 
          to="/college/dashboard" 
          class="nav-btn flex items-center gap-1.5 px-4 py-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all text-sm font-medium"
          :class="{ 'text-white bg-white/5 font-bold': $route.path === '/college/dashboard' }"
        >
          <span class="material-symbols-outlined text-lg">dashboard</span>
          <span>Dashboard</span>
        </router-link>

        <!-- Messages Link -->
        <router-link 
          to="/college/messages" 
          class="nav-btn flex items-center gap-1.5 px-4 py-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all text-sm font-medium"
          :class="{ 'text-white bg-white/5 font-bold': $route.path === '/college/messages' }"
        >
          <span class="material-symbols-outlined text-lg">mail</span>
          <span>Messages</span>
          <span v-if="getBadgeCount('Messages') > 0" class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full ml-1">{{ getBadgeCount('Messages') }}</span>
        </router-link>

        <!-- Documents Dropdown -->
        <div 
          class="relative" 
          @mouseenter="activeDropdown = 'documents'" 
          @mouseleave="activeDropdown = null"
        >
          <button 
            class="nav-btn flex items-center gap-1.5 px-4 py-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all text-sm font-medium"
            :class="{ 'text-white bg-white/5 font-bold': activeDropdown === 'documents' || ['submitted-list', 'plan-and-budget', 'archive'].some(path => $route.path.includes(path)) }"
          >
            <span class="material-symbols-outlined text-lg">folder</span>
            <span>Documents & Plan</span>
            <span class="material-symbols-outlined text-xs transition-transform duration-200" :class="{ 'rotate-180': activeDropdown === 'documents' }">expand_more</span>
          </button>
          <transition name="dropdown">
            <div 
              v-if="activeDropdown === 'documents'" 
              class="dropdown-menu absolute top-full left-0 mt-1 w-60 bg-[#1a1a2e] border border-purple-900/30 rounded-2xl shadow-2xl p-2 flex flex-col gap-1 z-50"
            >
              <router-link 
                to="/college/submitted-list" 
                class="dropdown-item flex items-center gap-2.5 p-3 rounded-xl hover:bg-white/5 text-slate-300 hover:text-white transition-colors text-sm"
                :class="{ 'bg-primary/20 text-white font-bold': $route.path === '/college/submitted-list' }"
              >
                <span class="material-symbols-outlined text-lg">list</span>
                <span>Submitted List</span>
              </router-link>
              <router-link 
                to="/college/plan-and-budget" 
                class="dropdown-item flex items-center gap-2.5 p-3 rounded-xl hover:bg-white/5 text-slate-300 hover:text-white transition-colors text-sm"
                :class="{ 'bg-primary/20 text-white font-bold': $route.path === '/college/plan-and-budget' }"
              >
                <span class="material-symbols-outlined text-lg">gavel</span>
                <span>Plan and Budget</span>
              </router-link>
              <router-link 
                to="/college/archive" 
                class="dropdown-item flex items-center gap-2.5 p-3 rounded-xl hover:bg-white/5 text-slate-300 hover:text-white transition-colors text-sm"
                :class="{ 'bg-primary/20 text-white font-bold': $route.path === '/college/archive' }"
              >
                <span class="material-symbols-outlined text-lg">archive</span>
                <span>Archives</span>
              </router-link>
            </div>
          </transition>
        </div>

        <!-- Controls & Settings Dropdown -->
        <div 
          class="relative" 
          @mouseenter="activeDropdown = 'controls'" 
          @mouseleave="activeDropdown = null"
        >
          <button 
            class="nav-btn flex items-center gap-1.5 px-4 py-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/5 transition-all text-sm font-medium"
            :class="{ 'text-white bg-white/5 font-bold': activeDropdown === 'controls' || ['activity-logs', 'trashbin', 'user-manual', 'data-privacy-policy'].some(path => $route.path.includes(path)) }"
          >
            <span class="material-symbols-outlined text-lg">settings</span>
            <span>Settings & Guides</span>
            <span class="material-symbols-outlined text-xs transition-transform duration-200" :class="{ 'rotate-180': activeDropdown === 'controls' }">expand_more</span>
          </button>
          <transition name="dropdown">
            <div 
              v-if="activeDropdown === 'controls'" 
              class="dropdown-menu absolute top-full right-0 mt-1 w-60 bg-[#1a1a2e] border border-purple-900/30 rounded-2xl shadow-2xl p-2 flex flex-col gap-1 z-50"
            >
              <router-link 
                to="/college/activity-logs" 
                class="dropdown-item flex items-center gap-2.5 p-3 rounded-xl hover:bg-white/5 text-slate-300 hover:text-white transition-colors text-sm"
                :class="{ 'bg-primary/20 text-white font-bold': $route.path === '/college/activity-logs' }"
              >
                <span class="material-symbols-outlined text-lg">history</span>
                <span>Activity Logs</span>
              </router-link>
              <router-link 
                to="/college/trashbin" 
                class="dropdown-item flex items-center gap-2.5 p-3 rounded-xl hover:bg-white/5 text-slate-300 hover:text-white transition-colors text-sm"
                :class="{ 'bg-primary/20 text-white font-bold': $route.path === '/college/trashbin' }"
              >
                <span class="material-symbols-outlined text-lg">delete</span>
                <span>Trash Bin</span>
              </router-link>
              <div class="h-px bg-white/10 my-1"></div>
              <router-link 
                to="/college/user-manual" 
                class="dropdown-item flex items-center gap-2.5 p-3 rounded-xl hover:bg-white/5 text-slate-300 hover:text-white transition-colors text-sm"
                :class="{ 'bg-primary/20 text-white font-bold': $route.path === '/college/user-manual' }"
              >
                <span class="material-symbols-outlined text-lg">menu_book</span>
                <span>User Manual</span>
              </router-link>
              <router-link 
                to="/college/data-privacy-policy" 
                class="dropdown-item flex items-center gap-2.5 p-3 rounded-xl hover:bg-white/5 text-slate-300 hover:text-white transition-colors text-sm"
                :class="{ 'bg-primary/20 text-white font-bold': $route.path === '/college/data-privacy-policy' }"
              >
                <span class="material-symbols-outlined text-lg">privacy_tip</span>
                <span>Privacy Policy</span>
              </router-link>
            </div>
          </transition>
        </div>
      </nav>

      <!-- Right Header Actions (Profile & Logout) -->
      <div class="flex items-center gap-3">
        <!-- Role Badge (Desktop) -->
        <div v-if="user.user_role" class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-primary/25 border border-primary/45 rounded-full shadow-sm">
          <span class="material-symbols-outlined text-primary text-[16px]">badge</span>
          <span class="text-white text-[10px] font-extrabold uppercase tracking-wider">{{ user.user_role }}</span>
        </div>

        <!-- Personal Settings Button -->
        <router-link 
          to="/college/settings" 
          class="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-white/5 transition-colors"
          title="Account Settings"
          :class="{ 'text-white bg-white/5': $route.path.includes('/settings') }"
        >
          <span class="material-symbols-outlined text-2xl">settings</span>
        </router-link>

        <!-- Sign Out Button -->
        <button 
          @click="handleLogout" 
          class="p-2 text-slate-400 hover:text-red-400 rounded-xl hover:bg-white/5 transition-colors"
          title="Sign Out"
        >
          <span class="material-symbols-outlined text-2xl">logout</span>
        </button>
      </div>
    </header>

    <!-- Main Content Slot Panel -->
    <main :class="['flex-grow w-full overflow-x-hidden transition-all duration-200', $route.path.includes('/plan-and-budget') ? 'p-0' : 'p-4 md:p-10']">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import DashboardSidebar from '../components/DashboardSidebar.vue';

const router = useRouter();
const isSidebarOpen = ref(false);
const activeDropdown = ref(null);
const user = ref({});

const collegeMenu = ref([
  { label: 'New Submission', icon: 'add', href: '/college/submit' },
  { label: 'Dashboard', icon: 'dashboard', href: '/college/dashboard' },
  { label: 'Messages', icon: 'mail', href: '/college/messages', badge: 0 },
  { label: 'Submitted List', icon: 'list', href: '/college/submitted-list' },
  { label: 'Archives', icon: 'archive', href: '/college/archive' },
  { label: 'Plan and Budget', icon: 'gavel', href: '/college/plan-and-budget' },
  {
    label: 'System Controls', icon: 'admin_panel_settings',
    children: [
      { label: 'Activity Logs', icon: 'history', href: '/college/activity-logs' },
      { label: 'Document Trash Bin', icon: 'delete', href: '/college/trashbin' }
    ]
  },
  {
    label: 'Legal and Guides', icon: 'policy',
    children: [
      { label: 'User Manual', icon: 'menu_book', href: '/college/user-manual' },
      { label: 'Data Privacy Policy', icon: 'privacy_tip', href: '/college/data-privacy-policy' }
    ]
  }
]);

const getBadgeCount = (label) => {
  const item = collegeMenu.value.find(m => m.label === label);
  if (!item) return 0;
  if (!item.children) return item.badge || 0;
  return item.children.reduce((sum, child) => sum + (child.badge || 0), 0);
};

const fetchUnreadCount = async () => {
  if (user.value?.id) {
    try {
      const res = await api.get(`/messages/unread-count/${user.value.id}`);
      if (res.data.success) {
        const msgItem = collegeMenu.value.find(m => m.label === 'Messages');
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
  user.value = JSON.parse(localStorage.getItem('user') || '{}');
  const role = (user.value.role || user.value.user_role || '').toLowerCase();
  
  if (!user.value.id || !['twg', 'non-twg'].includes(role)) {
    router.push('/login');
  } else {
    fetchUnreadCount();
    unreadInterval = setInterval(fetchUnreadCount, 30000);
  }
});

onUnmounted(() => {
  if (unreadInterval) clearInterval(unreadInterval);
});
</script>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
  transition: opacity 0.15s ease, transform 0.15s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(4px);
}
</style>
