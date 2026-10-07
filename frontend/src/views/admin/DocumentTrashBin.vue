<template>
  <div class="p-6 max-w-7xl mx-auto min-h-screen relative overflow-hidden">
    <!-- Header -->
    <div class="mb-8 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Document Trash Bin</h1>
        <p class="text-slate-500 font-medium mt-1">Items will be automatically permanently deleted after 30 days.</p>
      </div>
      
      <div class="flex gap-3">
        <button 
          v-if="selectedItems.length > 0"
          @click="handleRestoreSelected"
          class="bg-gradient-to-r from-[#990dd1] to-[#b979cc] hover:-translate-y-0.5 hover:shadow-[0_8px_16px_rgba(153,13,209,0.25)] border-none text-white px-5 py-2.5 rounded-xl font-label font-bold text-sm tracking-wide transition-all flex items-center gap-2 cursor-pointer"
        >
          <span class="material-symbols-outlined text-lg">restore</span>
          Restore Selected ({{ selectedItems.length }})
        </button>
        <button 
          v-if="selectedItems.length > 0"
          @click="handlePermanentDeleteSelected"
          class="bg-red-600 hover:bg-red-500 text-white px-5 py-2.5 rounded-xl font-label font-bold text-sm tracking-wide transition-all shadow-lg flex items-center gap-2 cursor-pointer"
        >
          <span class="material-symbols-outlined text-lg">delete_forever</span>
          Permanently Delete ({{ selectedItems.length }})
        </button>
      </div>
    </div>

    <!-- Table -->
    <div class="trash-table-container">
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div class="w-10 h-10 border-4 border-slate-300 dark:border-slate-600 border-t-purple-600 rounded-full animate-spin"></div>
      </div>
      
      <div v-else-if="items.length === 0" class="empty-state">
        <span class="material-symbols-outlined text-6xl mb-2 opacity-50">delete_outline</span>
        <p class="empty-state-text">Trash bin is empty.</p>
      </div>
      
      <div v-else class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="table-header">
            <tr>
              <th class="p-4 w-12 text-center">
                <input type="checkbox" :checked="isAllSelected" @change="toggleSelectAll" class="accent-primary w-4 h-4 cursor-pointer" />
              </th>
              <th class="p-4 font-bold">Document Title</th>
              <th class="p-4 font-bold">Type</th>
              <th class="p-4 font-bold">Deleted By</th>
              <th class="p-4 font-bold">Date Deleted</th>
              <th class="p-4 font-bold text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in items" :key="item.id + '-' + item.doc_type" class="table-row-item group cursor-pointer">
              <td class="p-4 text-center">
                <input 
                  type="checkbox" 
                  :value="item" 
                  v-model="selectedItems" 
                  class="accent-primary w-4 h-4 cursor-pointer"
                />
              </td>
              <td class="p-4">
                <div class="doc-title mb-1">{{ item.title }}</div>
              </td>
              <td class="p-4">
                <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wider"
                  :class="item.doc_type === 'design' ? 'badge-design' : 'badge-report'">
                  {{ item.doc_type === 'design' ? 'Activity Design' : 'Accomplishment Report' }}
                </span>
              </td>
              <td class="p-4 doc-deleted-by">
                {{ item.deleted_by_name || 'System / Unknown' }}
              </td>
              <td class="p-4 doc-date">
                {{ item.deleted_date }}
              </td>
              <td class="p-4 text-right">
                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button 
                    @click="handleRestore(item)"
                    class="action-btn-restore tooltip-trigger"
                    title="Restore"
                  >
                    <span class="material-symbols-outlined text-xl">restore</span>
                  </button>
                  <button 
                    @click="handlePermanentDelete(item)"
                    class="action-btn-delete tooltip-trigger"
                    title="Permanently Delete"
                  >
                    <span class="material-symbols-outlined text-xl">delete_forever</span>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import api from '../../api';

const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));
const items = ref([]);
const loading = ref(true);
const selectedItems = ref([]);

const isAllSelected = computed(() => {
  return items.value.length > 0 && selectedItems.value.length === items.value.length;
});

const toggleSelectAll = (e) => {
  if (e.target.checked) {
    selectedItems.value = [...items.value];
  } else {
    selectedItems.value = [];
  }
};

const fetchTrashedDocuments = async () => {
  loading.value = true;
  try {
    const res = await api.get('/documents/trashed');
    if (res.data.success) {
      items.value = res.data.data;
    }
  } catch (error) {
    console.error('Error fetching trashed documents:', error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to load trashed documents.',
      confirmButtonColor: '#b979cc'
    });
  } finally {
    loading.value = false;
  }
};

const handleRestore = async (item) => {
  await restoreItems([item]);
};

const handleRestoreSelected = async () => {
  if (selectedItems.value.length === 0) return;
  await restoreItems(selectedItems.value);
};

const restoreItems = async (itemsToRestore) => {
  const result = await Swal.fire({
    title: 'Restore Document(s)?',
    text: `Are you sure you want to restore ${itemsToRestore.length} document(s)? They will be moved back to their active lists.`,
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#10b981',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Yes, restore'
  });

  if (!result.isConfirmed) return;

  try {
    const res = await api.post('/documents/restore', { items: itemsToRestore });
    if (res.data.success) {
      Swal.fire({
        icon: 'success',
        title: 'Restored!',
        text: 'Document(s) have been successfully restored.',
        confirmButtonColor: '#b979cc',
        timer: 1500,
        showConfirmButton: false
      });
      selectedItems.value = [];
      fetchTrashedDocuments();
    } else {
      throw new Error(res.data.message || 'Failed to restore');
    }
  } catch (error) {
    console.error('Error restoring documents:', error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to restore documents.',
      confirmButtonColor: '#b979cc'
    });
  }
};

const handlePermanentDelete = async (item) => {
  await permanentlyDeleteItems([item]);
};

const handlePermanentDeleteSelected = async () => {
  if (selectedItems.value.length === 0) return;
  await permanentlyDeleteItems(selectedItems.value);
};

const permanentlyDeleteItems = async (itemsToDelete) => {
  const result = await Swal.fire({
    title: 'Permanently Delete?',
    text: `You are about to permanently delete ${itemsToDelete.length} document(s) from the system, database, and storage. This action cannot be undone!`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Yes, permanently delete',
    iconColor: '#ef4444'
  });

  if (!result.isConfirmed) return;

  try {
    const res = await api.post('/documents/permanently-delete', { items: itemsToDelete });
    if (res.data.success) {
      Swal.fire({
        icon: 'success',
        title: 'Deleted!',
        text: 'Document(s) have been permanently deleted.',
        confirmButtonColor: '#b979cc',
        timer: 1500,
        showConfirmButton: false
      });
      selectedItems.value = [];
      fetchTrashedDocuments();
    } else {
      throw new Error(res.data.message || 'Failed to delete');
    }
  } catch (error) {
    console.error('Error deleting documents:', error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to permanently delete documents.',
      confirmButtonColor: '#b979cc'
    });
  }
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'admin') {
    router.push('/login');
  } else {
    fetchTrashedDocuments();
  }
});
</script>

<style scoped>
.trash-table-container {
  background: var(--color-surface);
  border: 1px solid var(--color-outline-variant);
  border-radius: 1rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
  overflow: hidden;
}

.table-header {
  background: var(--color-surface-variant);
  border-bottom: 1px solid var(--color-outline-variant);
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-size: 0.85rem;
  font-weight: 900;
  color: var(--color-primary-text);
}

.table-row-item {
  border-bottom: 1px solid var(--color-outline-variant);
  transition: background-color 0.2s ease;
}

.table-row-item:hover {
  background-color: rgba(185, 121, 204, 0.08);
}

.doc-title {
  font-weight: 700;
  color: var(--color-on-background);
  transition: color 0.2s ease;
}

.table-row-item:hover .doc-title {
  color: var(--color-primary-text);
}

.doc-deleted-by {
  color: var(--color-on-surface-variant);
  font-weight: 500;
}

.doc-date {
  color: var(--color-on-surface-variant);
  font-family: monospace;
  font-size: 0.875rem;
  font-weight: 600;
}

.badge-design {
  background: rgba(147, 51, 234, 0.12);
  color: #7e22ce;
  border: 1px solid rgba(147, 51, 234, 0.3);
}

:global(.dark) .badge-design {
  background: rgba(147, 51, 234, 0.25);
  color: #d8b4fe;
  border-color: rgba(147, 51, 234, 0.5);
}

.badge-report {
  background: rgba(236, 72, 153, 0.12);
  color: #be185d;
  border: 1px solid rgba(236, 72, 153, 0.3);
}

:global(.dark) .badge-report {
  background: rgba(236, 72, 153, 0.25);
  color: #f472b6;
  border-color: rgba(236, 72, 153, 0.5);
}

.action-btn-restore {
  padding: 0.5rem;
  color: var(--color-primary-text);
  border-radius: 0.5rem;
  transition: all 0.2s;
}

.action-btn-restore:hover {
  background: rgba(185, 121, 204, 0.15);
}

.action-btn-delete {
  padding: 0.5rem;
  color: #ef4444;
  border-radius: 0.5rem;
  transition: all 0.2s;
}

.action-btn-delete:hover {
  background: rgba(239, 68, 68, 0.15);
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 16rem;
  color: var(--color-on-surface-variant);
}

.empty-state-text {
  font-size: 1.125rem;
  font-weight: 600;
  color: var(--color-on-surface-variant);
  margin-top: 0.5rem;
}

.tooltip-trigger {
  position: relative;
}
.tooltip-trigger:hover::after {
  content: attr(title);
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%) translateY(-4px);
  background: rgba(0, 0, 0, 0.85);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 11px;
  white-space: nowrap;
  pointer-events: none;
  z-index: 50;
}
</style>
