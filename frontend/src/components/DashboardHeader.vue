<template>
  <header class="dashboard-header">
    <div class="header-container">
      <div class="header-left">
        <div class="header-text">
          <span class="header-title">{{ title }}</span>
          <span class="header-context">{{ context }}</span>
        </div>
      </div>
      <div class="header-right">
        <div v-if="showSearch" class="search-container">
          <span class="search-icon">
            <span class="material-symbols-outlined">search</span>
          </span>
          <input
            class="search-input"
            :placeholder="searchPlaceholder"
            type="search"
          />
        </div>
        <div class="action-buttons">
          <div class="notification-wrapper" ref="notificationWrapper">
            <button type="button" class="action-btn" @click="toggleNotifications">
              <span class="material-symbols-outlined">notifications</span>
              <span v-if="unreadCount > 0" class="notification-badge">{{ unreadCount > 99 ? '99+' : unreadCount }}</span>
            </button>
            
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
                     :class="{ unread: !notif.is_read }"
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
          </div>
          <button type="button" class="action-btn">
            <span class="material-symbols-outlined">settings</span>
          </button>
          <div class="user-avatar" :title="username">
            <span class="user-initial">{{ userInitial }}</span>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';

const props = defineProps({
  title: { type: String, default: 'Dashboard' },
  context: { type: String, default: '' },
  showSearch: { type: Boolean, default: true },
  searchPlaceholder: { type: String, default: 'Search...' },
  username: { type: String, default: 'User' }
});

const userInitial = computed(() => props.username.charAt(0).toUpperCase());

const showNotifications = ref(false);
const notifications = ref([]);
const notificationWrapper = ref(null);
const router = useRouter();
let pollInterval = null;

const unreadCount = computed(() => {
  return notifications.value.filter(n => !n.is_read).length;
});

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
};

const fetchNotifications = async () => {
  try {
    const res = await api.get('/notifications');
    if (res.data && res.data.success) {
      notifications.value = res.data.data;
    }
  } catch (error) {
    console.error('Failed to fetch notifications', error);
  }
};

const markAllAsRead = async () => {
  try {
    await api.post('/notifications/mark-all-read');
    notifications.value.forEach(n => n.is_read = 1);
  } catch (error) {
    console.error('Failed to mark all as read', error);
  }
};

const handleNotificationClick = async (notif) => {
  if (!notif.is_read) {
    try {
      await api.put(`/notifications/${notif.id}/read`);
      notif.is_read = 1;
    } catch (error) {
      console.error('Failed to mark as read', error);
    }
  }
  showNotifications.value = false;
  if (notif.link) {
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

const formatTime = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
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
.dashboard-header {
  width: 100%;
  position: sticky;
  top: 0;
  z-index: 40;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(185, 121, 204, 0.15);
  font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
}

.header-container {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 0.75rem 2.5rem 0.75rem 1rem;
  width: 100%;
  max-width: 1600px;
  margin: 0 auto;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 1rem;
  min-width: 0;
}

.header-text {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

@media (min-width: 640px) {
  .header-text {
    flex-direction: row;
    align-items: baseline;
    gap: 1rem;
  }
}

.header-title {
  font-size: 1.25rem;
  font-weight: bold;
  letter-spacing: -0.025em;
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

@media (min-width: 640px) {
  .header-title {
    font-size: 1.5rem;
  }
}

.header-context {
  font-size: 0.875rem;
  font-weight: 500;
  color: #cbd5e1;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  opacity: 0.8;
}

.header-right {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-shrink: 0;
}

@media (min-width: 640px) {
  .header-right {
    gap: 1.5rem;
  }
}

.search-container {
  position: relative;
  display: none;
}

@media (min-width: 768px) {
  .search-container {
    display: block;
  }
}

.search-icon {
  position: absolute;
  inset-y: 0;
  left: 0;
  display: flex;
  align-items: center;
  padding-left: 0.75rem;
  color: #cbd5e1;
}

.search-icon .material-symbols-outlined {
  font-size: 1rem;
}

.search-input {
  padding: 0.5rem 0.75rem 0.5rem 2.25rem;
  background: rgba(0, 0, 0, 0.3);
  backdrop-filter: blur(5px);
  border: 1px solid rgba(185, 121, 204, 0.15);
  border-radius: 0.75rem;
  font-size: 0.875rem;
  color: #cbd5e1;
  width: 14rem;
  transition: all 0.2s ease;
}

@media (min-width: 1024px) {
  .search-input {
    width: 16rem;
  }
}

.search-input::placeholder {
  color: rgba(203, 213, 225, 0.5);
}

.search-input:focus {
  outline: none;
  border-color: rgba(185, 121, 204, 0.4);
  background: rgba(0, 0, 0, 0.4);
  box-shadow: 0 0 0 2px rgba(153, 13, 209, 0.2);
}

.action-buttons {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

@media (min-width: 640px) {
  .action-buttons {
    gap: 0.75rem;
  }
}

.action-btn {
  padding: 0.5rem;
  background: rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(185, 121, 204, 0.15);
  border-radius: 9999px;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.action-btn:hover {
  background: rgba(0, 0, 0, 0.5);
  border-color: rgba(185, 121, 204, 0.3);
  transform: scale(0.95);
}

.action-btn .material-symbols-outlined {
  font-size: 1.25rem;
  color: #cbd5e1;
}

.notification-wrapper {
  position: relative;
}

.notification-badge {
  position: absolute;
  top: -2px;
  right: -2px;
  background-color: #ef4444;
  color: white;
  font-size: 0.65rem;
  font-weight: bold;
  padding: 0.15rem 0.3rem;
  border-radius: 9999px;
  min-width: 1.2rem;
  text-align: center;
  box-shadow: 0 0 0 2px #16213e;
}

.notification-dropdown {
  position: absolute;
  top: calc(100% + 0.5rem);
  right: -3rem;
  width: 20rem;
  background: rgba(15, 23, 42, 0.95);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(185, 121, 204, 0.2);
  border-radius: 0.75rem;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
  z-index: 50;
  overflow: hidden;
}

@media (min-width: 640px) {
  .notification-dropdown {
    width: 24rem;
    right: -1rem;
  }
}

.dropdown-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  background: rgba(255, 255, 255, 0.02);
}

.dropdown-header h3 {
  margin: 0;
  font-size: 1rem;
  font-weight: 600;
  color: #f8fafc;
}

.mark-read-btn {
  background: none;
  border: none;
  color: #990dd1;
  font-size: 0.8rem;
  cursor: pointer;
  transition: color 0.2s;
}

.mark-read-btn:hover {
  color: #b979cc;
  text-decoration: underline;
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
  background: rgba(0, 0, 0, 0.1);
}
.dropdown-body::-webkit-scrollbar-thumb {
  background: rgba(185, 121, 204, 0.3);
  border-radius: 10px;
}
.dropdown-body::-webkit-scrollbar-thumb:hover {
  background: rgba(185, 121, 204, 0.5);
}

.empty-state {
  padding: 2rem 1rem;
  text-align: center;
  color: #94a3b8;
  font-size: 0.9rem;
}

.notification-item {
  display: flex;
  gap: 1rem;
  padding: 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  cursor: pointer;
  transition: background-color 0.2s;
}

.notification-item:hover {
  background: rgba(255, 255, 255, 0.05);
}

.notification-item.unread {
  background: rgba(153, 13, 209, 0.05);
}

.notification-item.unread:hover {
  background: rgba(153, 13, 209, 0.1);
}

.notif-icon {
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding-top: 0.25rem;
}

.notif-icon .material-symbols-outlined {
  font-size: 1.25rem;
}

.notif-icon.info { color: #3b82f6; }
.notif-icon.success { color: #10b981; }
.notif-icon.warning { color: #f59e0b; }
.notif-icon.error { color: #ef4444; }

.notif-content {
  flex: 1;
  min-width: 0;
}

.notif-content h4 {
  margin: 0 0 0.25rem 0;
  font-size: 0.9rem;
  font-weight: 600;
  color: #f1f5f9;
}

.notif-content p {
  margin: 0 0 0.5rem 0;
  font-size: 0.85rem;
  color: #cbd5e1;
  line-height: 1.4;
}

.notif-time {
  font-size: 0.75rem;
  color: #64748b;
}

.user-avatar {
  width: 2rem;
  height: 2rem;
  border-radius: 9999px;
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  border: 1px solid rgba(185, 121, 204, 0.5);
  cursor: pointer;
  transition: transform 0.2s ease;
}

.user-avatar:hover {
  transform: scale(1.05);
}

.user-initial {
  font-size: 0.75rem;
  font-weight: bold;
  color: white;
  user-select: none;
}

@media (max-width: 768px) {
  .header-container {
    padding: 0.75rem 1rem;
  }

  .header-title {
    font-size: 1rem;
  }

  .header-context {
    font-size: 0.75rem;
  }

  .action-btn .material-symbols-outlined {
    font-size: 1rem;
  }

  .user-avatar {
    width: 1.75rem;
    height: 1.75rem;
  }

  .user-initial {
    font-size: 0.625rem;
  }
}

@media (max-width: 640px) {
  .header-left {
    gap: 0.5rem;
  }

  .header-text {
    gap: 0.25rem;
  }
}

@media (max-width: 480px) {
  .header-container {
    gap: 0.5rem;
    padding: 0.5rem 1rem;
  }

  .action-buttons {
    gap: 0.25rem;
  }

  .search-input {
    width: 10rem;
    padding: 0.375rem 0.5rem 0.375rem 2rem;
  }
}
</style>
