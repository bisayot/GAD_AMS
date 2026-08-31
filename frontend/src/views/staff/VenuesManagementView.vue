<template>
  <div class="page-container">
    <div v-if="!isNested" class="header-section">
      <div class="flex justify-between items-center w-full">
        <div>
          <h1 class="page-title">Venues Management</h1>
          <p class="page-subtitle">Add, edit, or remove venues across the campus.</p>
        </div>
        <button class="btn-primary" @click="openAddModal">
          <span class="material-symbols-outlined">add</span>
          Add Venue
        </button>
      </div>
    </div>

    <!-- Action Bar for nested view -->
    <div v-if="isNested" class="flex justify-end mb-6">
      <button class="btn-primary" @click="openAddModal">
        <span class="material-symbols-outlined">add</span>
        Add Venue
      </button>
    </div>

    <!-- Error/Loading states -->
    <div v-if="loading" class="flex justify-center items-center py-12 glass-card">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
    </div>
    <div v-else-if="error" class="glass-card p-8 text-center text-red-400">
      {{ error }}
    </div>
    
    <!-- Venues Table -->
    <div v-else class="glass-card table-container overflow-x-auto">
      <table class="w-full text-left border-collapse custom-table">
        <thead>
          <tr>
            <th class="p-4 font-semibold border-b border-purple-900/30 text-white">Venue Name</th>
            <th class="p-4 font-semibold border-b border-purple-900/30 text-white">Location Type</th>
            <th class="p-4 font-semibold border-b border-purple-900/30 text-white text-right w-40">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="venues.length === 0">
            <td colspan="3" class="p-8 text-center text-slate-400">No venues found.</td>
          </tr>
          <tr v-for="venue in venues" :key="venue.venue_id" class="hover:bg-white/5 transition-colors border-b border-white/5">
            <td class="p-4 text-white font-medium">{{ venue.venue_name }}</td>
            <td class="p-4">
              <span class="badge" :class="venue.is_inside_bsu ? 'badge-inside' : 'badge-outside'">
                {{ venue.is_inside_bsu ? 'Inside BSU' : 'Outside BSU' }}
              </span>
            </td>
            <td class="p-4">
              <div class="flex justify-end items-center gap-2">
                <button class="btn-icon text-white hover:text-blue-400 hover:bg-blue-500/20" @click="openEditModal(venue)" title="Edit">
                  <span class="material-symbols-outlined text-[1.2rem] text-white">edit</span>
                </button>
                <button class="btn-icon text-white hover:text-red-400 hover:bg-red-500/20" @click="confirmDelete(venue.venue_id)" title="Delete">
                  <span class="material-symbols-outlined text-[1.2rem] text-white">delete</span>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal"></div>
      
      <div class="relative glass-card w-full max-w-md p-6 rounded-2xl shadow-2xl animate-fade-in">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <span class="material-symbols-outlined text-purple-400">{{ editingVenue ? 'edit' : 'add_circle' }}</span>
            {{ editingVenue ? 'Edit Venue' : 'Add New Venue' }}
          </h2>
          <button @click="closeModal" class="text-slate-400 hover:text-white transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        <form @submit.prevent="saveVenue" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-300 mb-1">Venue Name *</label>
            <input 
              type="text" 
              v-model="form.venue_name" 
              required 
              placeholder="e.g. BSU Gymnasium" 
              class="w-full bg-black/20 border border-purple-500/30 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-purple-500 transition-colors placeholder-slate-500"
            />
          </div>
          
          <div>
            <label class="flex items-center gap-3 cursor-pointer text-slate-300 hover:text-white transition-colors mt-4">
              <input type="checkbox" v-model="form.is_inside_bsu" class="w-4 h-4 rounded border-purple-500/30 text-purple-600 focus:ring-purple-500 bg-black/20" />
              <span>This venue is inside BSU campus</span>
            </label>
          </div>

          <div v-if="formError" class="p-3 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-sm mt-4">
            {{ formError }}
          </div>

          <div class="flex justify-end gap-3 mt-8">
            <button type="button" @click="closeModal" class="px-4 py-2 rounded-lg text-slate-300 hover:bg-white/5 transition-colors font-medium" :disabled="saving">Cancel</button>
            <button type="submit" class="btn-primary" :disabled="saving">
              <span v-if="saving" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isEditing ? 'Save Changes' : 'Save Venue' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../../api';

const props = defineProps({
  isNested: {
    type: Boolean,
    default: false
  }
});

const venues = ref([]);
const loading = ref(true);
const error = ref('');
const showModal = ref(false);
const editingVenue = ref(null);
const saving = ref(false);
const formError = ref('');

const form = ref({
  venue_name: '',
  is_inside_bsu: true
});

const fetchVenues = async () => {
  loading.value = true;
  error.value = '';
  try {
    const res = await api.get('/venues');
    venues.value = res.data;
  } catch (err) {
    error.value = 'Failed to load venues. Please try again.';
    console.error(err);
  } finally {
    loading.value = false;
  }
};

const openAddModal = () => {
  editingVenue.value = null;
  form.value = { venue_name: '', is_inside_bsu: true };
  formError.value = '';
  showModal.value = true;
};

const openEditModal = (venue) => {
  editingVenue.value = venue;
  form.value = { 
    venue_name: venue.venue_name, 
    is_inside_bsu: venue.is_inside_bsu 
  };
  formError.value = '';
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveVenue = async () => {
  saving.value = true;
  formError.value = '';
  try {
    if (editingVenue.value) {
      // Update
      const res = await api.put(`/venues/${editingVenue.value.venue_id}`, form.value);
      const index = venues.value.findIndex(v => v.venue_id === editingVenue.value.venue_id);
      if (index !== -1) {
        venues.value[index] = res.data;
      }
    } else {
      // Create
      const res = await api.post('/venues', form.value);
      venues.value.push(res.data);
    }
    closeModal();
  } catch (err) {
    formError.value = err.response?.data?.messages?.error || 'Failed to save venue. It might already exist.';
    console.error(err);
  } finally {
    saving.value = false;
  }
};

const confirmDelete = async (id) => {
  if (confirm('Are you sure you want to delete this venue? It may break existing reports if they rely on it.')) {
    try {
      await api.delete(`/venues/${id}`);
      venues.value = venues.value.filter(v => v.venue_id !== id);
    } catch (err) {
      alert('Failed to delete venue. It might be in use.');
      console.error(err);
    }
  }
};

onMounted(() => {
  fetchVenues();
});
</script>

<style scoped>
.page-container {
  padding: v-bind('isNested ? "0" : "32px"');
  max-width: 1400px;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 2rem;
}

.page-title {
  font-size: 2rem;
  color: #16213e;
  font-weight: 900;
  letter-spacing: -0.025em;
  margin-bottom: 0.5rem;
}

.page-subtitle {
  color: #475569;
  font-size: 0.95rem;
}

.glass-card {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  backdrop-filter: blur(12px);
  border-radius: 1.25rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
  overflow: hidden;
}

.btn-primary {
  background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
  color: white;
  padding: 0.6rem 1.25rem;
  border-radius: 0.75rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s;
  border: 1px solid rgba(255,255,255,0.1);
  box-shadow: 0 4px 12px rgba(147, 51, 234, 0.3);
}

.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(147, 51, 234, 0.4);
}

.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-icon {
  width: 2rem;
  height: 2rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 0.5rem;
  transition: all 0.2s;
}

.animate-fade-in {
  animation: fadeIn 0.2s ease-out forwards;
}

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}

.badge {
  padding: 4px 12px;
  border-radius: 9999px;
  font-size: 12px;
  font-weight: 600;
}
.badge-inside {
  background: rgba(220, 252, 231, 0.15);
  color: #86efac;
  border: 1px solid rgba(134, 239, 172, 0.3);
}
.badge-outside {
  background: rgba(254, 243, 199, 0.15);
  color: #fde047;
  border: 1px solid rgba(253, 224, 71, 0.3);
}
</style>
