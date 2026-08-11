<template>
  <main class="main-viewport flex-1 overflow-y-auto" style="background: linear-gradient(135deg, #0f172a 0%, #1e1e2f 100%); min-height: 100vh; padding: 2rem;">
    <div class="page-container">
      <div class="header-section mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="page-title">Contact Inquiries</h1>
          <p class="page-subtitle">Review and manage inquiries submitted from the public contact form.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Search inquiries..." 
            class="form-input w-full sm:w-64"
          />
          <button @click="fetchInquiries" class="btn-secondary flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px]">refresh</span>
            Refresh
          </button>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="glass-card p-6 flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-blue-900/50 text-blue-400 flex items-center justify-center">
            <span class="material-symbols-outlined">inbox</span>
          </div>
          <div>
            <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Total Inquiries</p>
            <p class="text-2xl font-bold text-white">{{ inquiries.length }}</p>
          </div>
        </div>
        
        <div class="glass-card p-6 flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-amber-900/50 text-amber-400 flex items-center justify-center">
            <span class="material-symbols-outlined">mark_email_unread</span>
          </div>
          <div>
            <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Unread</p>
            <p class="text-2xl font-bold text-white">{{ unreadCount }}</p>
          </div>
        </div>
      </div>

    <!-- Main Content -->
    <div class="glass-card overflow-hidden rounded-[2rem]">
      <!-- Loading State -->
      <div v-if="loading" class="p-12 flex flex-col items-center justify-center text-slate-400">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500 mb-4"></div>
        <p>Loading inquiries...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredInquiries.length === 0" class="p-12 flex flex-col items-center justify-center text-slate-400">
        <span class="material-symbols-outlined text-5xl mb-4 opacity-50">mail</span>
        <h3 class="text-lg font-medium text-white mb-1">No Inquiries Found</h3>
        <p>Try adjusting your search criteria.</p>
      </div>

      <!-- Inquiries List -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-2 2xl:grid-cols-3 gap-6 p-6">
        <div 
          v-for="inquiry in filteredInquiries" 
          :key="inquiry.id"
          class="p-6 transition-all duration-300 hover:bg-white/5 flex flex-col gap-4 group rounded-xl border border-purple-500/20"
          :class="{ 'bg-purple-900/20 shadow-[0_0_15px_rgba(168,85,247,0.15)]': inquiry.status === 'new', 'bg-black/20': inquiry.status !== 'new' }"
        >
          <!-- Header: Status, Date, Name -->
          <div class="flex flex-col gap-4">
            <div class="flex items-start gap-3">
              <span v-if="inquiry.status === 'new'" class="w-2.5 h-2.5 rounded-full bg-purple-500 mt-1 shadow-[0_0_8px_rgba(168,85,247,0.8)]"></span>
              <span v-else-if="inquiry.status === 'replied_staff'" class="w-2.5 h-2.5 rounded-full bg-green-500 mt-1" title="Replied by Staff"></span>
              <span v-else-if="inquiry.status === 'replied_director'" class="w-2.5 h-2.5 rounded-full bg-purple-500 mt-1" title="Replied by Director"></span>
              <div>
                <div class="flex items-center gap-2">
                  <h4 class="text-lg font-semibold text-white" :class="{'font-bold': inquiry.status === 'new'}">
                    {{ inquiry.name }}
                  </h4>
                  <span class="text-xs font-bold text-purple-300 bg-purple-900/40 px-2 py-0.5 rounded border border-purple-500/30">
                    {{ formatTicketNumber(inquiry.id) }}
                  </span>
                </div>
                <a :href="'mailto:' + inquiry.email" class="text-sm text-purple-300 hover:text-purple-200 hover:underline flex items-center gap-1 mt-0.5">
                  <span class="material-symbols-outlined text-[14px]">mail</span>
                  {{ inquiry.email }}
                </a>
              </div>
            </div>
            
            <div class="flex items-center flex-wrap gap-2 mt-2">
              <span class="text-sm text-slate-300 bg-black/20 px-3 py-1 rounded-full whitespace-nowrap border border-white/5">
                {{ formatDate(inquiry.created_at) }}
              </span>
              
              <div v-if="inquiry.status === 'replied_staff'" class="text-xs font-bold px-2 py-1 bg-green-900/30 text-green-400 rounded-full border border-green-800">
                Replied by Staff
              </div>
              <div v-if="inquiry.status === 'replied_director'" class="text-xs font-bold px-2 py-1 bg-purple-900/30 text-purple-400 rounded-full border border-purple-800">
                Replied by Director
              </div>

              <button 
                v-if="!inquiry.status.startsWith('replied')"
                @click="openReplyModal(inquiry)"
                class="text-sm px-3 py-1 btn-primary flex items-center gap-1"
                title="Reply"
              >
                <span class="material-symbols-outlined text-[16px]">reply</span> Reply
              </button>
              
              <button 
                v-if="inquiry.status === 'new'"
                @click="markAsRead(inquiry)"
                class="text-sm px-3 py-1.5 bg-slate-600 rounded text-white hover:bg-slate-500 transition-colors flex items-center gap-1 shadow-sm"
                title="Mark as Read"
              >
                <span class="material-symbols-outlined text-[16px]">done</span> Mark Read
              </button>

              <button 
                @click="deleteInquiry(inquiry)"
                class="text-sm px-3 py-1.5 bg-red-600 rounded text-white hover:bg-red-500 transition-colors flex items-center gap-1 shadow-sm"
                title="Delete Inquiry"
              >
                <span class="material-symbols-outlined text-[16px]">delete</span> Delete
              </button>
            </div>
          </div>

          <!-- Content: Subject & Message -->
          <div class="mt-4 flex-grow flex flex-col">
            <h5 class="font-medium text-white mb-3">Subject: {{ inquiry.subject }}</h5>
            <div class="bg-black/30 rounded-lg p-4 border border-white/5 text-slate-300 text-sm whitespace-pre-wrap leading-relaxed shadow-inner flex-grow">
              {{ inquiry.message }}
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>

    <!-- Reply Modal -->
    <div v-if="showReplyModal" class="modal-overlay">
      <div class="glass-card w-full max-w-lg flex flex-col" style="max-height: 90vh;">
        <div class="p-6 border-b border-purple-500/20 flex justify-between items-center bg-black/20 rounded-t-[1.5rem]">
          <h3 class="text-xl font-bold text-white">Reply to {{ activeInquiry?.name }}</h3>
          <button @click="closeReplyModal" class="text-slate-400 hover:text-white transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        
        <div class="p-6 flex-grow overflow-y-auto custom-scrollbar">
          <div class="mb-4 p-4 bg-amber-500/10 border border-amber-500/30 rounded-lg text-sm text-amber-200 flex items-start gap-3">
            <span class="material-symbols-outlined text-amber-400 text-[20px]">warning</span>
            <p class="leading-relaxed">
              <strong>Note:</strong> Our system uses a free email service with daily limits. If sending fails, please close this and reply manually using your own email client.
            </p>
          </div>
          
          <div class="form-group">
            <label class="form-label">Message</label>
            <textarea 
              v-model="replyMessage" 
              rows="6" 
              class="form-input" 
              placeholder="Type your reply here..."
            ></textarea>
          </div>
        </div>
        
        <div class="p-6 border-t border-purple-500/20 bg-black/20 flex justify-end gap-3 rounded-b-[1.5rem]">
          <button @click="closeReplyModal" class="btn-secondary">
            Cancel
          </button>
          <button @click="submitReply" :disabled="replying || !replyMessage.trim()" class="btn-primary flex items-center gap-2">
            <span v-if="replying" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
            {{ replying ? 'Sending...' : 'Send Reply' }}
          </button>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '../../api';
import Swal from 'sweetalert2';

const inquiries = ref([]);
const loading = ref(true);
const searchQuery = ref('');

const showReplyModal = ref(false);
const activeInquiry = ref(null);
const replyMessage = ref('');
const replying = ref(false);

const unreadCount = computed(() => {
  return inquiries.value.filter(i => i.status === 'new').length;
});

const filteredInquiries = computed(() => {
  if (!searchQuery.value) return inquiries.value;
  const q = searchQuery.value.toLowerCase();
  return inquiries.value.filter(i => 
    i.name.toLowerCase().includes(q) || 
    i.email.toLowerCase().includes(q) || 
    i.subject.toLowerCase().includes(q) ||
    formatTicketNumber(i.id).toLowerCase().includes(q)
  );
});

const formatTicketNumber = (id) => {
  return 'INQ-' + String(id).padStart(5, '0');
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const fetchInquiries = async () => {
  loading.value = true;
  try {
    const res = await api.get('/contact-inquiries');
    if (res.data && res.data.inquiries) {
      inquiries.value = res.data.inquiries;
    }
  } catch (error) {
    console.error('Error fetching inquiries:', error);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to load inquiries.'
    });
  } finally {
    loading.value = false;
  }
};

const markAsRead = async (inquiry) => {
  try {
    const res = await api.post(`/contact-inquiries/${inquiry.id}/read`);
    if (res.status === 200 || res.data.message) {
      inquiry.status = 'read';
    }
  } catch (error) {
    console.error('Error marking as read:', error);
  }
};

const openReplyModal = (inquiry) => {
  activeInquiry.value = inquiry;
  replyMessage.value = '';
  showReplyModal.value = true;
};

const closeReplyModal = () => {
  showReplyModal.value = false;
  activeInquiry.value = null;
  replyMessage.value = '';
};

const submitReply = async () => {
  if (!activeInquiry.value || !replyMessage.value.trim()) return;
  
  replying.value = true;
  try {
    const res = await api.post(`/contact-inquiries/${activeInquiry.value.id}/reply`, {
      reply_message: replyMessage.value
    });
    
    if (res.status === 200 || res.data.message) {
      Swal.fire({
        icon: 'success',
        title: 'Reply Sent',
        text: 'Your reply was successfully emailed.',
        timer: 2000,
        showConfirmButton: false
      });
      activeInquiry.value.status = res.data.status || 'replied_staff';
      closeReplyModal();
    }
  } catch (error) {
    console.error('Error sending reply:', error);
    Swal.fire({
      icon: 'error',
      title: 'Failed to Send',
      text: error?.messages?.error || error?.message || 'The email could not be sent. You might have reached your daily limit. Please try manually.'
    });
  } finally {
    replying.value = false;
  }
};

const deleteInquiry = async (inquiry) => {
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "This inquiry will be permanently deleted from the database. This action cannot be undone.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
  });

  if (result.isConfirmed) {
    try {
      const res = await api.delete(`/contact-inquiries/${inquiry.id}`);
      if (res.status === 200) {
        Swal.fire(
          'Deleted!',
          'The inquiry has been deleted.',
          'success'
        );
        inquiries.value = inquiries.value.filter(i => i.id !== inquiry.id);
      }
    } catch (error) {
      console.error('Error deleting inquiry:', error);
      Swal.fire(
        'Error!',
        'Failed to delete the inquiry.',
        'error'
      );
    }
  }
};

onMounted(() => {
  fetchInquiries();
});
</script>

<style scoped>
.page-container { max-width: 1200px; margin: 0 auto; width: 100%; }
.page-title {
  font-size: 2rem;
  color: white;
  font-weight: 900;
  letter-spacing: -0.025em;
  margin-bottom: 0.5rem;
}
.page-subtitle {
  color: #94a3b8;
  font-size: 0.95rem;
}
.glass-card { 
  background: linear-gradient(135deg, rgba(30, 41, 59, 0.7) 0%, rgba(15, 23, 42, 0.8) 100%); 
  backdrop-filter: blur(12px); 
  border-radius: 1.5rem; 
  border: 1px solid rgba(147, 51, 234, 0.2); 
  box-shadow: 0 10px 30px rgba(0,0,0,0.2), inset 0 1px 0 rgba(255,255,255,0.05); 
}
.btn-secondary {
  background: rgba(255, 255, 255, 0.05);
  color: #e2e8f0;
  padding: 0.6rem 1.25rem;
  border-radius: 0.75rem;
  font-weight: 600;
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: all 0.2s;
  cursor: pointer;
}
.btn-secondary:hover {
  background: rgba(255, 255, 255, 0.1);
}
.btn-primary {
  background: linear-gradient(135deg, #b979cc 0%, #9b59b6 100%);
  color: white;
  padding: 0.6rem 1.25rem;
  border-radius: 0.75rem;
  font-weight: 600;
  border: none;
  transition: all 0.2s;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(185, 121, 204, 0.3);
}
.btn-primary:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(185, 121, 204, 0.4);
}
.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(4px);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}
.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.form-label {
  color: #cbd5e1;
  font-size: 0.875rem;
  font-weight: 500;
}
.form-input {
  background: rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(185, 121, 204, 0.3);
  color: white;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  font-size: 0.95rem;
  outline: none;
  transition: all 0.2s;
}
.form-input:focus {
  border-color: #b979cc;
  background: rgba(0, 0, 0, 0.5);
}
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(255,255,255,0.02);
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(185, 121, 204, 0.3);
  border-radius: 10px;
}
</style>
