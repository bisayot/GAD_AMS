<template>
  <div class="notification-wrapper" ref="notificationWrapper">
    <button type="button" class="action-btn" @click="toggleNotifications">
      <span class="material-symbols-outlined">notifications</span>
      <span v-if="unreadCount > 0" class="ml-1 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ unreadCount > 99 ? '99+' : unreadCount }}</span>
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
  // Dropdown stays open or closed based on user preference, but no routing occurs.
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
</style>
