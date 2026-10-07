<template>
  <div class="notification-wrapper" ref="notificationWrapper">
    <button type="button" class="action-btn" @click="toggleNotifications">
      <span class="material-symbols-outlined" style="color: #22c55e;">notifications</span>
      <span v-if="unreadCount > 0" class="absolute -top-1 -right-1.5 bg-[#ef4444] text-white text-[10px] font-bold px-1.5 py-[2px] rounded-full border-2 border-white dark:border-[#13111f] min-w-[18px] text-center leading-none shadow-sm">{{ unreadCount > 99 ? '99+' : unreadCount }}</span>
    </button>
    
    <transition name="dropdown-fade">
      <div v-if="showNotifications" class="notification-dropdown">
        <div class="dropdown-header">
          <h3>Notifications</h3>
          <button v-if="unreadCount > 0" @click="markAllAsRead" class="mark-read-btn">Mark all read</button>
        </div>
        <div class="dropdown-body">
          <div v-if="notifications.length === 0" class="empty-state">
            No notifications yet.
          </div>
          <div v-else 
               v-for="notif in notifications" 
               :key="notif.id" 
               class="notification-item"
               :class="{ unread: parseInt(notif.is_read) === 0 }"
               @click="handleNotificationClick(notif)">
            <div class="notif-icon" :class="notif.type || 'info'">
              <span class="material-symbols-outlined">{{ getIcon(notif.type) }}</span>
            </div>
            <div class="notif-content">
              <h4>{{ notif.title }}</h4>
              <p>{{ notif.message }}</p>
              <span class="notif-time">{{ formatTime(notif.created_at) }}</span>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';

const showNotifications = ref(false);
const notifications = ref([]);
const notificationWrapper = ref(null);
const router = useRouter();
let pollInterval = null;

const unreadCount = computed(() => {
  return notifications.value.filter(n => parseInt(n.is_read) === 0).length;
});

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
};

const fetchNotifications = async () => {
  try {
    const res = await api.get('/notifications/all');
    if (res.data && (res.data.success || res.data.status === 'success')) {
      notifications.value = res.data.data;
    }
  } catch (error) {
    console.error('Failed to fetch notifications', error);
  }
};

const markAllAsRead = async () => {
  try {
    await api.post('/notifications/read-all');
    notifications.value.forEach(n => n.is_read = 1);
  } catch (error) {
    console.error('Failed to mark all as read', error);
  }
};

const formatTime = (dateString) => {
  // Append 'Z' to treat the DB time as UTC
  const date = new Date(dateString + 'Z');
  return date.toLocaleString('en-US', { timeZone: 'Asia/Manila', year: 'numeric', month: 'numeric', day: 'numeric', hour: '2-digit', minute: '2-digit' });
};

const handleNotificationClick = async (notif) => {
  if (parseInt(notif.is_read) === 0) {
    try {
      await api.post(`/notifications/read/${notif.id}`);
      notif.is_read = 1;
    } catch (error) {
      console.error('Failed to mark as read', error);
    }
  }
  
  if (notif.link) {
    showNotifications.value = false;
    router.push(notif.link);
  }
};

const getIcon = (type) => {
  switch (type) {
    case 'success': return 'check_circle';
    case 'warning': return 'warning';
    case 'error': return 'error';
    default: return 'info';
  }
};

const closeDropdown = (e) => {
  if (notificationWrapper.value && !notificationWrapper.value.contains(e.target)) {
    showNotifications.value = false;
  }
};

onMounted(() => {
  fetchNotifications();
  document.addEventListener('click', closeDropdown);
  pollInterval = setInterval(fetchNotifications, 60000); // Poll every minute
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdown);
  if (pollInterval) clearInterval(pollInterval);
});
</script>

<style scoped>
.action-btn {
  padding: 0.5rem;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 9999px;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}

.action-btn:hover {
  background: #f1f5f9;
  border-color: #cbd5e1;
  transform: scale(0.95);
}

.action-btn .material-symbols-outlined {
  font-size: 1.25rem;
  color: #22c55e;
}

html.dark .action-btn,
.dark .action-btn {
  background: #1a1a2e;
  border-color: rgba(168, 85, 247, 0.3);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
}

html.dark .action-btn:hover,
.dark .action-btn:hover {
  background: #23233e;
  border-color: rgba(168, 85, 247, 0.5);
}

.notification-wrapper {
  position: relative;
}

/* --------------------------------------------------------------------------
   Notification Dropdown Base (Light Mode Default)
   -------------------------------------------------------------------------- */
.notification-dropdown {
  position: fixed;
  top: 4.5rem;
  left: 50%;
  transform: translateX(-50%);
  width: 92vw;
  background: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(16px);
  border: 1px solid rgba(226, 232, 240, 0.95);
  border-radius: 1rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.03), 0 0 0 1px rgba(0, 0, 0, 0.04);
  z-index: 50;
  overflow: hidden;
  transition: background-color 0.2s, border-color 0.2s;
}

@media (min-width: 640px) {
  .notification-dropdown {
    position: absolute;
    top: calc(100% + 0.5rem);
    left: auto;
    right: -1rem;
    transform: none;
    width: 24rem;
  }
}

.dropdown-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid #f1f5f9;
  background: linear-gradient(to right, #faf5ff, #f8fafc);
}

.dropdown-header h3 {
  margin: 0;
  font-size: 1rem;
  font-weight: 700;
  color: #0f172a;
  letter-spacing: -0.01em;
}

.mark-read-btn {
  background: none;
  border: none;
  color: #7e22ce;
  font-size: 0.8rem;
  font-weight: 600;
  cursor: pointer;
  transition: color 0.2s, background-color 0.2s;
  padding: 4px 8px;
  border-radius: 6px;
}

.mark-read-btn:hover {
  color: #9333ea;
  background: rgba(126, 34, 206, 0.08);
  text-decoration: none;
}

.dropdown-body {
  max-height: 24rem;
  overflow-y: auto;
}

/* Custom Scrollbar for dropdown body */
.dropdown-body::-webkit-scrollbar {
  width: 6px;
}
.dropdown-body::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.02);
}
.dropdown-body::-webkit-scrollbar-thumb {
  background: rgba(185, 121, 204, 0.25);
  border-radius: 10px;
}
.dropdown-body::-webkit-scrollbar-thumb:hover {
  background: rgba(185, 121, 204, 0.45);
}

.empty-state {
  padding: 2.5rem 1rem;
  text-align: center;
  color: #64748b;
  font-size: 0.875rem;
}

.notification-item {
  display: flex;
  gap: 0.875rem;
  padding: 0.875rem 1.25rem;
  border-bottom: 1px solid #f1f5f9;
  cursor: pointer;
  transition: background-color 0.15s ease;
  position: relative;
}

.notification-item:hover {
  background: #f8fafc;
}

.notification-item.unread {
  background: rgba(147, 51, 234, 0.04);
}

.notification-item.unread::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 3.5px;
  background: #9333ea;
  border-top-right-radius: 2px;
  border-bottom-right-radius: 2px;
}

.notification-item.unread:hover {
  background: rgba(147, 51, 234, 0.08);
}

.notif-icon {
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding-top: 0.125rem;
}

.notif-icon .material-symbols-outlined {
  font-size: 1.25rem;
}

.notif-icon.info { color: #2563eb; }
.notif-icon.success { color: #16a34a; }
.notif-icon.warning { color: #d97706; }
.notif-icon.error { color: #dc2626; }

.notif-content {
  flex: 1;
  min-width: 0;
}

.notif-content h4 {
  margin: 0 0 0.25rem 0;
  font-size: 0.875rem;
  font-weight: 600;
  color: #0f172a;
  line-height: 1.35;
}

.notif-content p {
  margin: 0 0 0.375rem 0;
  font-size: 0.8125rem;
  color: #475569;
  line-height: 1.45;
}

.notif-time {
  font-size: 0.75rem;
  color: #94a3b8;
  font-weight: 500;
}

/* --------------------------------------------------------------------------
   Transitions
   -------------------------------------------------------------------------- */
.dropdown-fade-enter-active,
.dropdown-fade-leave-active {
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.dropdown-fade-enter-from,
.dropdown-fade-leave-to {
  opacity: 0;
  transform: translateY(-8px) scale(0.98);
}

@media (max-width: 639px) {
  .dropdown-fade-enter-from,
  .dropdown-fade-leave-to {
    transform: translate(-50%, -8px) scale(0.98);
  }
}

/* --------------------------------------------------------------------------
   Dark Mode Overrides
   -------------------------------------------------------------------------- */
html.dark .notification-dropdown,
.dark .notification-dropdown {
  background: rgba(15, 23, 42, 0.95) !important;
  border: 1px solid rgba(185, 121, 204, 0.2) !important;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6), 0 8px 10px -6px rgba(0, 0, 0, 0.4) !important;
}

html.dark .dropdown-header,
.dark .dropdown-header {
  border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
  background: rgba(255, 255, 255, 0.03) !important;
}

html.dark .dropdown-header h3,
.dark .dropdown-header h3 {
  color: #f8fafc !important;
}

html.dark .mark-read-btn,
.dark .mark-read-btn {
  color: #b979cc !important;
}

html.dark .mark-read-btn:hover,
.dark .mark-read-btn:hover {
  color: #d8b4fe !important;
  background: rgba(185, 121, 204, 0.12) !important;
}

html.dark .dropdown-body::-webkit-scrollbar-track,
.dark .dropdown-body::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.2) !important;
}

html.dark .dropdown-body::-webkit-scrollbar-thumb,
.dark .dropdown-body::-webkit-scrollbar-thumb {
  background: rgba(185, 121, 204, 0.3) !important;
}

html.dark .dropdown-body::-webkit-scrollbar-thumb:hover,
.dark .dropdown-body::-webkit-scrollbar-thumb:hover {
  background: rgba(185, 121, 204, 0.5) !important;
}

html.dark .empty-state,
.dark .empty-state {
  color: #94a3b8 !important;
}

html.dark .notification-item,
.dark .notification-item {
  border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
}

html.dark .notification-item:hover,
.dark .notification-item:hover {
  background: rgba(255, 255, 255, 0.05) !important;
}

html.dark .notification-item.unread,
.dark .notification-item.unread {
  background: rgba(153, 13, 209, 0.1) !important;
}

html.dark .notification-item.unread::before,
.dark .notification-item.unread::before {
  background: #c084fc !important;
}

html.dark .notification-item.unread:hover,
.dark .notification-item.unread:hover {
  background: rgba(153, 13, 209, 0.18) !important;
}

html.dark .notif-content h4,
.dark .notif-content h4 {
  color: #f1f5f9 !important;
}

html.dark .notif-content p,
.dark .notif-content p {
  color: #94a3b8 !important;
}

html.dark .notif-time,
.dark .notif-time {
  color: #64748b !important;
}

html.dark .notif-icon.info, .dark .notif-icon.info { color: #60a5fa !important; }
html.dark .notif-icon.success, .dark .notif-icon.success { color: #4ade80 !important; }
html.dark .notif-icon.warning, .dark .notif-icon.warning { color: #fbbf24 !important; }
html.dark .notif-icon.error, .dark .notif-icon.error { color: #f87171 !important; }
</style>
