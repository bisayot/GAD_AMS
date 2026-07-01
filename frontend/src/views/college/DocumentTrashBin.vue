<template>
  <div class="p-6 max-w-7xl mx-auto min-h-screen relative overflow-hidden">
    <!-- Header -->
    <div class="mb-8 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-[#16213e] tracking-tight">Document Trash Bin</h1>
        <p class="text-[#475569] font-medium mt-1">Items will be automatically permanently deleted after 30 days.</p>
      </div>
      
      <div class="flex gap-3">
        <button 
          v-if="selectedItems.length > 0"
          @click="handleRestoreSelected"
          class="bg-gradient-to-r from-[#990dd1] to-[#b979cc] hover:-translate-y-0.5 hover:shadow-[0_8px_16px_rgba(153,13,209,0.25)] border-none text-white px-5 py-2.5 rounded-xl font-label font-bold text-sm tracking-wide transition-all flex items-center gap-2"
        >
          <span class="material-symbols-outlined text-lg">restore</span>
          Restore Selected ({{ selectedItems.length }})
        </button>
        <button 
          v-if="selectedItems.length > 0"
          @click="handlePermanentDeleteSelected"
          class="bg-red-600 hover:bg-red-500 text-white px-5 py-2.5 rounded-xl font-label font-bold text-sm tracking-wide transition-all shadow-lg flex items-center gap-2"
        >
          <span class="material-symbols-outlined text-lg">delete_forever</span>
          Permanently Delete ({{ selectedItems.length }})
        </button>
      </div>
    </div>

    <!-- Table -->
    <div class="rounded-2xl border border-[rgba(185,121,204,0.15)] shadow-[0_20px_25px_-5px_rgba(0,0,0,0.25)] overflow-hidden" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div class="w-10 h-10 border-4 border-slate-600 border-t-primary rounded-full animate-spin"></div>
      </div>
      
      <div v-else-if="items.length === 0" class="flex flex-col items-center justify-center h-64 text-slate-400">
        <span class="material-symbols-outlined text-6xl mb-4 opacity-50">delete_outline</span>
        <p class="text-lg font-medium">Trash bin is empty.</p>
      </div>
      
      <div v-else class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-[rgba(0,0,0,0.3)] border-b border-[rgba(185,121,204,0.1)] text-[0.85rem] uppercase tracking-[0.1em] text-[#b979cc] font-black">
            <tr>
              <th class="p-4 w-12 text-center">
                <input type="checkbox" :checked="isAllSelected" @change="toggleSelectAll" class="accent-primary w-4 h-4 cursor-pointer" />
              </th>
              <th class="p-4 font-bold">Document Title</th>
              <th class="p-4 font-bold">Type</th>
              <th class="p-4 font-bold">Date Deleted</th>
              <th class="p-4 font-bold text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-[rgba(185,121,204,0.05)]">
            <tr v-for="item in items" :key="item.id + '-' + item.doc_type" class="hover:bg-[rgba(255,255,255,0.05)] transition-colors group cursor-pointer">
              <td class="p-4 text-center">
                <input 
                  type="checkbox" 
                  :value="item" 
                  v-model="selectedItems" 
                  class="accent-primary w-4 h-4 cursor-pointer"
                />
              </td>
              <td class="p-4">
                <div class="font-semibold text-[#e2e8f0] group-hover:text-[#b979cc] transition-colors mb-1">{{ item.title }}</div>
              </td>
              <td class="p-4">
                <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wider"
                  :class="item.doc_type === 'design' ? 'bg-purple-500/30 text-purple-200 border border-purple-400 shadow-[0_0_10px_rgba(168,85,247,0.3)]' : 'bg-pink-500/30 text-pink-200 border border-pink-400 shadow-[0_0_10px_rgba(236,72,153,0.3)]'">
                  {{ item.doc_type === 'design' ? 'Activity Design' : 'Accomplishment Report' }}
                </span>
              </td>
              <td class="p-4 text-[#94a3b8] font-mono text-sm">
                {{ item.deleted_date }}
              </td>
              <td class="p-4 text-right">
                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                  <button 
                    @click="handleRestore(item)"
                    class="p-2 text-[#b979cc] hover:bg-[#b979cc]/10 rounded-lg transition-colors tooltip-trigger"
                    title="Restore"
                  >
                    <span class="material-symbols-outlined text-xl">restore</span>
                  </button>
                  <button 
                    @click="handlePermanentDelete(item)"
                    class="p-2 text-red-400 hover:bg-red-400/10 rounded-lg transition-colors tooltip-trigger"
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

onMounted(() => {
  if (!user.value.id || (user.value.role !== 'twg' && user.value.role !== 'non-twg')) {
    router.push('/login');
  } else {
    fetchTrashedDocuments();
  }
});

const fetchTrashedDocuments = async () => {
  loading.value = true;
  try {
    const res = await api.get('/documents/trashed?user_id=' + user.value.id);
    if (res.data.success) {
      items.value = res.data.data || [];
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
</script>

<style scoped>
.tooltip-trigger {
  position: relative;
}
.tooltip-trigger:hover::after {
  content: attr(title);
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%) translateY(-4px);
  background: rgba(0, 0, 0, 0.8);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 11px;
  white-space: nowrap;
  pointer-events: none;
  z-index: 50;
}
</style>
