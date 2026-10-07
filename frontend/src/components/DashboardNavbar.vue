<template>
  <nav class="dashboard-navbar">
    <div class="navbar-inner">
      <!-- Left: Brand -->
      <div class="navbar-brand flex items-center">
        <!-- Mobile Menu Toggle (Left side for mobile) -->
        <button class="xl:hidden mr-3 p-1 flex items-center !text-white active:scale-95 transition-transform" @click.stop.prevent="$emit('toggle-mobile-menu')" style="touch-action: manipulation;">
          <span class="material-symbols-outlined text-3xl font-bold !text-white pointer-events-none">menu</span>
        </button>

        <router-link to="/" class="brand-container flex-shrink-0">
          <div class="brand-logos">
            <img src="/images/bsulogo.webp" alt="BSU Logo" class="brand-logo" />
            <img src="/images/gad_logo_enhanced.png" alt="GAD Logo" class="brand-logo" />
          </div>
          <div class="brand-text flex flex-col justify-center leading-tight">
            <span class="brand-subtitle hidden xl:block">BENGUET STATE UNIVERSITY</span>
            <span class="brand-title">GAD-AMS</span>
          </div>
        </router-link>
      </div>

      <!-- Center: Navigation -->
      <div class="navbar-center hidden xl:flex items-center gap-1">
        <div 
          v-for="(item, index) in menuItems" 
          :key="index"
          class="relative nav-item-wrapper"
          @mouseenter="openDropdown(index)"
          @mouseleave="closeDropdown(index)"
        >
          <!-- Direct Link -->
          <router-link 
            v-if="!item.children"
            :to="item.href"
            class="nav-item"
            :class="{ 'active': isRouteActive(item.href) }"
          >
            <span class="material-symbols-outlined nav-icon">{{ item.icon }}</span>
            <span class="nav-label">{{ item.label }}</span>
          </router-link>

          <!-- Dropdown Trigger -->
          <button 
            v-else
            class="nav-item"
            :class="{ 'active': isChildRouteActive(item.children) }"
          >
            <span class="material-symbols-outlined nav-icon">{{ item.icon }}</span>
            <span class="nav-label">{{ item.label }}</span>
          </button>

          <!-- Dropdown Menu -->
          <transition name="dropdown">
            <div 
              v-if="item.children && activeDropdown === index" 
              class="dropdown-menu"
            >
              <div class="dropdown-header">{{ item.label }}</div>
              <router-link 
                v-for="(child, childIdx) in item.children"
                :key="childIdx"
                :to="child.href"
                class="dropdown-item"
                :class="{ 'active': isRouteActive(child.href) }"
                @click="activeDropdown = null"
              >
                <span class="material-symbols-outlined">{{ child.icon }}</span>
                <span>{{ child.label }}</span>
              </router-link>
            </div>
          </transition>
        </div>
      </div>

      <!-- Right: Actions -->
      <div class="navbar-right">
        <!-- Theme Toggle -->
        <button class="action-btn" @click="toggleTheme" title="Toggle Theme">
          <span class="material-symbols-outlined">{{ themeIcon }}</span>
        </button>

        <!-- Messages -->
        <router-link :to="messagesLink" class="action-btn" title="Messages">
          <span class="material-symbols-outlined">chat</span>
          <span v-if="unreadMessages > 0" class="absolute -top-1 -right-1.5 bg-[#ef4444] text-white text-[10px] font-bold px-1.5 py-[2px] rounded-full border-2 border-white dark:border-[#13111f] min-w-[18px] text-center leading-none shadow-sm">{{ unreadMessages > 99 ? '99+' : unreadMessages }}</span>
        </router-link>

        <!-- Notifications -->
        <NotificationDropdown class="action-btn-wrapper" />

        <!-- User Profile Dropdown -->
        <div class="relative profile-wrapper" ref="profileDropdownRef">
          <button class="profile-btn" @click="isProfileOpen = !isProfileOpen" :title="user?.name || 'User'">
            <span class="user-initial">{{ userInitial }}</span>
          </button>
          
          <transition name="dropdown">
            <div v-if="isProfileOpen" class="dropdown-menu profile-menu !p-2.5 !bg-white/98 dark:!bg-[#13111f]/95 backdrop-blur-xl !border !border-slate-200 dark:!border-purple-500/20 !rounded-2xl shadow-xl dark:shadow-2xl">
              <div class="bg-slate-50 dark:bg-slate-800/60 rounded-[14px] p-3.5 flex items-center gap-3.5 mb-2 border border-slate-200/90 dark:border-white/10 shadow-sm">
                <div :class="['w-[52px] h-[52px] rounded-full flex items-center justify-center flex-shrink-0 shadow-lg', avatarStyle]">
                  <span class="text-xl font-bold text-white">{{ userInitial }}</span>
                </div>
                <div class="flex flex-col overflow-hidden">
                  <div class="text-[15px] font-bold text-slate-900 dark:text-white truncate leading-tight">{{ user?.full_name || user?.name || user?.username || 'User Name' }}</div>
                  <div class="text-[13px] text-slate-600 dark:text-purple-200/70 font-medium truncate mb-2 mt-0.5">{{ user?.email || 'user@bsu.edu.ph' }}</div>
                  <div :class="['inline-flex items-center gap-1.5 border rounded-full px-3 py-1 w-fit shadow-sm', roleStyle.bgClass, roleStyle.borderClass]">
                    <span :class="['material-symbols-outlined text-[14px]', roleStyle.textClass]">{{ roleStyle.icon }}</span>
                    <span :class="['text-[10px] font-black tracking-[0.05em] uppercase leading-none mt-[1px]', roleStyle.textClass]">{{ user?.user_role || user?.role || 'Role' }}</span>
                  </div>
                </div>
              </div>
              
              <div class="flex flex-col px-1 pb-1 gap-0.5">
                <router-link :to="settingsLink" class="group flex items-center gap-3.5 px-3 py-2.5 rounded-xl hover:bg-purple-50 dark:hover:bg-white/5 transition-colors text-slate-700 dark:text-slate-200 hover:text-purple-900 dark:hover:text-white text-[14px] font-semibold no-underline" @click="isProfileOpen = false">
                  <span class="material-symbols-outlined text-[22px] text-purple-600 dark:text-[#d8b4fe] group-hover:scale-110 transition-transform">settings</span>
                  <span>Account Settings</span>
                </router-link>
                
                <router-link :to="manualLink" class="group flex items-center gap-3.5 px-3 py-2.5 rounded-xl hover:bg-purple-50 dark:hover:bg-white/5 transition-colors text-slate-700 dark:text-slate-200 hover:text-purple-900 dark:hover:text-white text-[14px] font-semibold no-underline" @click="isProfileOpen = false">
                  <span class="material-symbols-outlined text-[22px] text-purple-600 dark:text-[#d8b4fe] group-hover:scale-110 transition-transform">help</span>
                  <span>User Manual</span>
                </router-link>
                
                <router-link :to="privacyLink" class="group flex items-center gap-3.5 px-3 py-2.5 rounded-xl hover:bg-purple-50 dark:hover:bg-white/5 transition-colors text-slate-700 dark:text-slate-200 hover:text-purple-900 dark:hover:text-white text-[14px] font-semibold no-underline" @click="isProfileOpen = false">
                  <span class="material-symbols-outlined text-[22px] text-purple-600 dark:text-[#d8b4fe] group-hover:scale-110 transition-transform">policy</span>
                  <span>Privacy Policy</span>
                </router-link>
                
                <div class="h-px bg-slate-200 dark:bg-white/10 mx-2 my-1.5"></div>
                
                <button @click="handleLogout" class="group flex items-center gap-3.5 px-3 py-2.5 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors text-slate-700 dark:text-slate-200 hover:text-rose-600 dark:hover:text-rose-300 text-[14px] font-semibold bg-transparent border-none cursor-pointer w-full text-left">
                  <span class="material-symbols-outlined text-[22px] text-rose-500 dark:text-rose-400 group-hover:translate-x-0.5 transition-transform">logout</span>
                  <span>Sign Out</span>
                </button>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../api';
import NotificationDropdown from './NotificationDropdown.vue';

const props = defineProps({
  menuItems: { type: Array, required: true },
  user: { type: Object, default: () => ({}) }
});

const emit = defineEmits(['toggle-mobile-menu']);

const route = useRoute();
const router = useRouter();

const activeDropdown = ref(null);
let dropdownTimeout = null;

const isProfileOpen = ref(false);
const profileDropdownRef = ref(null);
const unreadMessages = ref(0);

const currentTheme = ref('light');
const themeIcon = computed(() => {
  return currentTheme.value === 'light' ? 'light_mode' : 'dark_mode';
});

const toggleTheme = () => {
  const newTheme = currentTheme.value === 'light' ? 'dark' : 'light';
  document.documentElement.classList.remove(currentTheme.value);
  document.documentElement.classList.add(newTheme);
  localStorage.setItem('theme', newTheme);
  currentTheme.value = newTheme;
};

// Computed base route for dynamic links
const baseRoute = computed(() => '/' + (route.path.split('/')[1] || 'dashboard'));
const messagesLink = computed(() => `${baseRoute.value}/messages`);
const settingsLink = computed(() => `${baseRoute.value}/settings`);
const manualLink = computed(() => `${baseRoute.value}/user-manual`);
const privacyLink = computed(() => `${baseRoute.value}/data-privacy-policy`);

const userInitial = computed(() => {
  const name = props.user?.full_name || props.user?.name || props.user?.username || 'U';
  return name.charAt(0).toUpperCase();
});

const roleStyle = computed(() => {
  const role = (props.user?.user_role || props.user?.role || '').toLowerCase();
  
  if (role.includes('admin') || role.includes('director')) {
    return {
      bgClass: 'bg-purple-100 dark:bg-[#401f71]',
      borderClass: 'border-purple-300 dark:border-[#6b32b8]',
      textClass: 'text-purple-800 dark:text-purple-100',
      icon: 'local_police'
    };
  } else if (role.includes('staff')) {
    return {
      bgClass: 'bg-emerald-100 dark:bg-emerald-900/80',
      borderClass: 'border-emerald-300 dark:border-emerald-500/50',
      textClass: 'text-emerald-800 dark:text-emerald-300',
      icon: 'support_agent'
    };
  } else if (role.includes('twg')) {
    return {
      bgClass: 'bg-blue-100 dark:bg-blue-900/80',
      borderClass: 'border-blue-300 dark:border-blue-500/50',
      textClass: 'text-blue-800 dark:text-blue-300',
      icon: 'school'
    };
  }
  
  return {
    bgClass: 'bg-slate-100 dark:bg-slate-800',
    borderClass: 'border-slate-300 dark:border-slate-600',
    textClass: 'text-slate-800 dark:text-slate-300',
    icon: 'badge'
  };
});

const avatarStyle = computed(() => {
  const role = (props.user?.user_role || props.user?.role || '').toLowerCase();
  
  if (role.includes('admin') || role.includes('director')) {
    return 'bg-gradient-to-br from-[#d946ef] to-[#9333ea] shadow-purple-500/20';
  } else if (role.includes('staff')) {
    return 'bg-gradient-to-br from-emerald-400 to-teal-600 shadow-emerald-500/20';
  } else if (role.includes('twg')) {
    return 'bg-gradient-to-br from-blue-400 to-indigo-600 shadow-blue-500/20';
  }
  
  return 'bg-gradient-to-br from-slate-400 to-slate-600 shadow-slate-500/20';
});

const openDropdown = (index) => {
  clearTimeout(dropdownTimeout);
  activeDropdown.value = index;
};

const closeDropdown = () => {
  dropdownTimeout = setTimeout(() => {
    activeDropdown.value = null;
  }, 100);
};

const isRouteActive = (href) => route.path === href;
const isChildRouteActive = (children) => children?.some(c => route.path === c.href);

const closeProfileOnClickOutside = (e) => {
  if (profileDropdownRef.value && !profileDropdownRef.value.contains(e.target)) {
    isProfileOpen.value = false;
  }
};

const fetchUnreadMessages = async () => {
  if (props.user?.id) {
    try {
      const res = await api.get(`/messages/unread-count/${props.user.id}`);
      if (res.data.success) {
        unreadMessages.value = res.data.count;
      }
    } catch (err) {
      // silent fail
    }
  }
};

const handleLogout = async () => {
  try {
    await api.get('logout');
  } catch (err) {
    // proceed anyway
  } finally {
    localStorage.removeItem('user');
    localStorage.removeItem('authToken');
    router.push('/login');
  }
};

let msgInterval;

onMounted(() => {
  const savedTheme = localStorage.getItem('theme') || 'light';
  currentTheme.value = savedTheme;
  document.documentElement.classList.add(savedTheme);

  document.addEventListener('click', closeProfileOnClickOutside);
  fetchUnreadMessages();
  msgInterval = setInterval(fetchUnreadMessages, 10000);
});

onUnmounted(() => {
  document.removeEventListener('click', closeProfileOnClickOutside);
  if (msgInterval) clearInterval(msgInterval);
});
</script>

<style scoped>
.dashboard-navbar {
  width: 100%;
  position: sticky;
  top: 0;
  z-index: 50;
  background: #240b3b !important;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
  border-bottom: 1px solid rgba(192, 132, 252, 0.3);
  font-family: system-ui, -apple-system, sans-serif;
}

.navbar-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 24px;
  height: 72px;
  max-width: 100%;
  gap: 24px;
}

/* Brand Styles */
.brand-container {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
}
.brand-logos {
  display: flex;
  align-items: center;
}
.brand-logo {
  height: 36px;
  width: auto;
  object-fit: contain;
}
.brand-logo:nth-child(2) {
  margin-left: -6px;
  z-index: 1;
  height: 38px;
}
.brand-text {
  line-height: 1.1;
}
.brand-subtitle {
  font-size: 9px;
  font-weight: 700;
  color: var(--color-on-background);
  letter-spacing: 0.05em;
  text-transform: uppercase;
}
.brand-title {
  font-size: 18px;
  font-weight: 900;
  color: var(--color-primary);
  letter-spacing: -0.02em;
}

/* Center Navigation Styles */
.navbar-center {
  flex: 1;
  justify-content: center;
}

.nav-item-wrapper {
  height: 100%;
  display: flex;
  align-items: center;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 6px;
  background: transparent;
  border: none;
  color: var(--color-on-surface-variant);
  font-size: 13px;
  font-weight: 600;
  padding: 6px 12px;
  border-radius: 8px;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.nav-item:hover {
  background: rgba(168, 85, 247, 0.1);
  color: var(--color-on-background);
}

.nav-item.active {
  background: rgba(168, 85, 247, 0.15);
  color: var(--color-on-background);
  position: relative;
}

.nav-item.active::after {
  content: '';
  position: absolute;
  bottom: -4px; /* Moved higher to sit right under the nav item */
  left: 10%;
  width: 80%;
  height: 3px;
  background: linear-gradient(90deg, #c026d3, #ec4899);
  border-radius: 3px 3px 0 0;
  box-shadow: 0 -2px 10px rgba(236, 72, 153, 0.4);
}

.nav-icon {
  font-size: 20px;
}

/* Dropdown Menu Styles */
.dropdown-menu {
  position: absolute;
  top: calc(100% + 4px);
  left: 0;
  min-width: 240px;
  background: var(--color-surface);
  border: 1px solid var(--color-outline-variant);
  border-radius: 12px;
  padding: 8px 0;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  z-index: 100;
}

/* For Profile dropdown specifically */
.profile-menu {
  right: 0;
  left: auto;
  min-width: 280px;
}

.dropdown-header {
  padding: 8px 16px;
  font-size: 10px;
  font-weight: 800;
  color: #7e22ce;
  text-transform: uppercase;
  letter-spacing: 0.1em;
}

html.dark .dropdown-header,
.dark .dropdown-header {
  color: #c084fc;
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 16px;
  color: var(--color-on-surface-variant);
  text-decoration: none;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.2s;
  background: transparent;
  border: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
}

.dropdown-item:hover, .dropdown-item.active {
  background: rgba(168, 85, 247, 0.1);
  color: var(--color-on-background);
}

.dropdown-item.logout {
  color: #f87171;
}

.dropdown-item.logout:hover {
  background: rgba(248, 113, 113, 0.1);
}

.dropdown-divider {
  height: 1px;
  background: var(--color-outline-variant);
  margin: 8px 0;
}

/* Profile dropdown is styled entirely with Tailwind utilities in the template */

/* Right Actions Styles */
.navbar-right {
  display: flex;
  align-items: center;
  gap: 16px;
}

.action-btn {
  position: relative;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--color-surface-variant);
  border: 1px solid var(--color-outline-variant);
  border-radius: 50%;
  color: var(--color-on-surface-variant);
  text-decoration: none;
  transition: all 0.2s;
  cursor: pointer;
}

.action-btn:hover {
  background: rgba(168, 85, 247, 0.1);
  color: var(--color-on-background);
}

.badge {
  position: absolute;
  top: -2px;
  right: -2px;
  background: #ef4444;
  color: white;
  font-size: 10px;
  font-weight: bold;
  padding: 2px 4px;
  border-radius: 10px;
  min-width: 16px;
  text-align: center;
  border: 2px solid var(--color-surface);
}

.action-btn-wrapper :deep(.action-btn) {
  /* Ensure NotificationDropdown action button inherits same styles */
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--color-surface-variant);
  border: 1px solid var(--color-outline-variant);
  border-radius: 50%;
  color: var(--color-on-surface-variant);
  cursor: pointer;
}
.action-btn-wrapper :deep(.action-btn:hover) {
  background: rgba(168, 85, 247, 0.1);
  color: var(--color-on-background);
}
.action-btn-wrapper :deep(.notification-badge) {
  position: absolute;
  top: -2px;
  right: -2px;
  background: #ef4444;
  color: white;
  font-size: 10px;
  font-weight: bold;
  padding: 2px 4px;
  border-radius: 10px;
  min-width: 16px;
  text-align: center;
  border: 2px solid var(--color-surface);
}

.profile-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #a855f7, #ec4899);
  border: 2px solid transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  padding: 0;
}

.profile-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 0 15px rgba(236, 72, 153, 0.4);
}

.user-initial {
  color: white;
  font-weight: 700;
  font-size: 16px;
}

/* Transitions */
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 0.2s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

/* Responsive Mobile Scaling */
@media (max-width: 640px) {
  .navbar-inner {
    padding: 0 12px;
  }
  .navbar-right {
    gap: 8px;
  }
  .action-btn,
  .action-btn-wrapper :deep(.action-btn),
  .profile-btn {
    width: 32px !important;
    height: 32px !important;
  }
  .action-btn .material-symbols-outlined,
  .action-btn-wrapper :deep(.material-symbols-outlined) {
    font-size: 20px;
  }
  .user-initial {
    font-size: 14px;
  }
  .brand-logo {
    height: 28px;
  }
  .brand-logo:nth-child(2) {
    height: 30px;
  }
  .brand-title {
    font-size: 14px;
  }
  .badge, .action-btn-wrapper :deep(.notification-badge) {
    transform: scale(0.85);
    top: -4px;
    right: -4px;
  }
}

/* Dark Mode Topbar (remains rich dark purple #240b3b) */
:global(.dark) .dashboard-navbar,
:global(html.dark) .dashboard-navbar {
  background: #240b3b !important;
  border-bottom: 1px solid rgba(192, 132, 252, 0.3) !important;
}

:global(.dark) .brand-title,
:global(html.dark) .brand-title,
.brand-title {
  color: #ffffff !important;
}

:global(.dark) .brand-subtitle,
:global(html.dark) .brand-subtitle,
.brand-subtitle {
  color: #d8b4fe !important;
}

:global(.dark) .nav-item,
:global(html.dark) .nav-item,
.nav-item {
  color: #e9d5ff !important;
}

:global(.dark) .nav-item:hover,
:global(html.dark) .nav-item:hover,
.nav-item:hover {
  background: rgba(192, 132, 252, 0.2) !important;
  color: #ffffff !important;
}

:global(.dark) .nav-item.active,
:global(html.dark) .nav-item.active,
.nav-item.active {
  background: rgba(192, 132, 252, 0.25) !important;
  color: #ffffff !important;
}

:global(.dark) .dropdown-menu,
:global(html.dark) .dropdown-menu,
.dropdown-menu {
  background: #31104e !important;
  border-color: rgba(192, 132, 252, 0.3) !important;
}

:global(.dark) .dropdown-item,
:global(html.dark) .dropdown-item,
.dropdown-item {
  color: #e9d5ff !important;
}

:global(.dark) .dropdown-item:hover,
:global(html.dark) .dropdown-item:hover,
.dropdown-item:hover {
  background: rgba(192, 132, 252, 0.2) !important;
  color: #ffffff !important;
}

:global(.dark) .action-btn,
:global(html.dark) .action-btn,
:global(.dark) .action-btn-wrapper .action-btn,
:global(html.dark) .action-btn-wrapper .action-btn,
.action-btn,
.action-btn-wrapper :deep(.action-btn) {
  background: rgba(255, 255, 255, 0.1) !important;
  border-color: rgba(192, 132, 252, 0.3) !important;
  color: #f3e8ff !important;
}

:global(.dark) .action-btn:hover,
:global(html.dark) .action-btn:hover,
:global(.dark) .action-btn-wrapper .action-btn:hover,
:global(html.dark) .action-btn-wrapper .action-btn:hover,
.action-btn:hover,
.action-btn-wrapper :deep(.action-btn:hover) {
  background: rgba(192, 132, 252, 0.25) !important;
  color: #ffffff !important;
}

:global(.dark) .badge,
:global(html.dark) .badge,
:global(.dark) .action-btn-wrapper .notification-badge,
:global(html.dark) .action-btn-wrapper .notification-badge,
.badge,
.action-btn-wrapper :deep(.notification-badge) {
  border-color: #240b3b !important;
}
</style>
