<template>
  <div class="min-h-screen bg-slate-50 flex">
    <!-- Mobile Sidebar Overlay -->
    <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <DashboardSidebar
      :isOpen="isSidebarOpen"
      @close="isSidebarOpen = false"
      roleLabel="College/Unit"
      :menuItems="collegeMenu"
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

const collegeMenu = [
  { label: 'New Submission', icon: 'add', href: '/college/submit' },
  { label: 'Dashboard', icon: 'dashboard', href: '/college/dashboard' },
  { label: 'Submitted List', icon: 'list', href: '/college/submitted-list' },
  { label: 'Archives', icon: 'archive', href: '/college/archive' },
  { label: 'Mandates', icon: 'gavel', href: '/college/mandates' },
  { label: 'User Manual', icon: 'menu_book', href: '/college/user-manual' },
  { label: 'Data Privacy Policy', icon: 'privacy_tip', href: '/college/data-privacy-policy' }
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
  window.addEventListener('scroll', handleScroll);
  user.value = JSON.parse(localStorage.getItem('user') || '{}');
  if (!user.value.id || user.value.role !== 'college') {
    router.push('/login');
  }
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
/* No custom layout styles needed; handled by Tailwind */
</style>
