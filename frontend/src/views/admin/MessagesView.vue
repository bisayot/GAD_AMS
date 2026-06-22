<template>
  <main class="messages-main-content">
    <div class="messages-content-wrapper">
      <div class="messages-header">
        <div>
          <h1 class="messages-title">Messages</h1>
          <p class="messages-subtitle">View and manage your messages here.</p>
        </div>
      </div>





      <div class="messages-container">
        <div class="messages-section">
          <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
              <span class="title-indicator"></span>
              <h4 class="section-title" style="margin: 0;">Messages</h4>
            </div>
            <button @click="showCreateModal = !showCreateModal" class="btn-primary" style="padding: 0.5rem 1rem; border-radius: 0.5rem; background: linear-gradient(135deg, #9333ea, #c084fc); border: none; color: white; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; font-size: 0.95rem; transition: transform 0.3s, box-shadow 0.3s; box-shadow: 0 4px 6px rgba(147, 51, 234, 0.2);">
              <span class="material-symbols-outlined" style="font-size: 1.2rem;">{{ showCreateModal ? 'close' : 'add' }}</span>
              {{ showCreateModal ? 'Cancel' : 'Create New Message' }}
            </button>
          </div>

          <!-- Create Message Panel (Top) -->
          <transition name="slide-down">
            <div v-if="showCreateModal" class="create-message-panel">
              <div class="panel-header">
                <h2 class="panel-title">Create New Message</h2>
                <button @click="showCreateModal = false" class="close-btn">
                  <span class="material-symbols-outlined">close</span>
                </button>
              </div>
              
              <div class="panel-body">
                <!-- To: Field -->
                <div class="form-group">
                  <label class="form-label">To:</label>
                  
                  <!-- Role Filter -->
                  <div class="filter-group">
                    <label class="sub-label">Select Role:</label>
                    <div class="role-buttons">
                      <button 
                        v-for="role in roles" 
                        :key="role.value"
                        @click="selectRole(role.value)"
                        :class="['role-btn', { active: selectedRole === role.value }]"
                      >
                        {{ role.label }}
                      </button>
                    </div>
                  </div>

                  <!-- Office Filter (for TWG and Non-TWG) -->
                  <div v-if="selectedRole && selectedRole !== 'Staff'" class="filter-group">
                    <label class="sub-label">Select Office:</label>
                    <div class="office-buttons">
                      <button 
                        v-for="office in offices" 
                        :key="office.value"
                        @click="selectOffice(office.value)"
                        :class="['office-btn', { active: selectedOffice === office.value }]"
                      >
                        {{ office.label }}
                      </button>
                    </div>
                  </div>

                  <!-- User Selection -->
                  <div v-if="selectedRole && (selectedRole === 'Staff' || selectedOffice)" class="filter-group">
                    <label class="sub-label">Select User(s):</label>
                    <div class="user-list">
                      <label v-for="user in filteredUsers" :key="user.id" class="user-item">
                        <input type="checkbox" :value="user.id" v-model="selectedUsers">
                        <span>{{ user.full_name }} <span class="user-email" v-if="user.email">({{ user.email }})</span></span>
                      </label>
                      <div v-if="filteredUsers.length === 0" class="no-users-msg">
                        No users found for this selection.
                      </div>
                    </div>
                  </div>
                </div>



                <!-- Title Field -->
                <div v-if="selectedUsers.length > 0" class="form-group">
                  <label class="form-label">Title:</label>
                  <input v-model="messageTitle" type="text" class="form-input" placeholder="Enter message title">
                </div>

                <!-- Message Field -->
                <div v-if="messageTitle" class="form-group">
                  <label class="form-label">Message:</label>
                  <textarea v-model="messageText" class="form-textarea" rows="6" placeholder="Enter your message"></textarea>
                </div>
              </div>

              <div v-if="messageText" class="panel-footer">
                <button @click="showCreateModal = false" class="btn-secondary">Cancel</button>
                <button @click="sendMessage" class="btn-primary">Send Message</button>
              </div>
            </div>
          </transition>

          <div class="messages-layout" style="display: flex; gap: 2rem; align-items: flex-start; margin-top: 1rem;">
            <div class="messages-sidebar" style="flex: 0 0 150px; display: flex; flex-direction: column; gap: 0.5rem;">
               <div class="sidebar-item" @click="activeTab = 'inbox'; fetchMessages()" style="padding: 0.75rem 1rem; cursor: pointer; border-radius: 0.5rem; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; color: #cbd5e1;" :style="activeTab === 'inbox' ? 'background: rgba(147, 51, 234, 0.2); color: #c084fc; border: 1px solid rgba(147, 51, 234, 0.5);' : 'border: 1px solid transparent;'">
                 <span class="material-symbols-outlined">inbox</span> Inbox
               </div>
               <div class="sidebar-item" @click="activeTab = 'sent'; fetchMessages()" style="padding: 0.75rem 1rem; cursor: pointer; border-radius: 0.5rem; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; color: #cbd5e1;" :style="activeTab === 'sent' ? 'background: rgba(147, 51, 234, 0.2); color: #c084fc; border: 1px solid rgba(147, 51, 234, 0.5);' : 'border: 1px solid transparent;'">
                 <span class="material-symbols-outlined">send</span> Sent
               </div>
               <div class="sidebar-item" @click="activeTab = 'trash'; fetchMessages()" style="padding: 0.75rem 1rem; cursor: pointer; border-radius: 0.5rem; display: flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease; color: #cbd5e1;" :style="activeTab === 'trash' ? 'background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.5);' : 'border: 1px solid transparent;'">
                 <span class="material-symbols-outlined">delete</span> Trash
               </div>
            </div>

            <div class="messages-list-wrapper" style="flex: 1;">
              <div class="messages-list">
                <div v-if="messages.length === 0" class="empty-state">
                  <div class="empty-icon">
                    <span class="material-symbols-outlined">{{ activeTab === 'trash' ? 'delete' : (activeTab === 'inbox' ? 'mail' : 'send') }}</span>
                  </div>
                  <h5 class="empty-title">No {{ activeTab === 'inbox' ? 'Inbox' : (activeTab === 'sent' ? 'Sent' : 'Trashed') }} Messages</h5>
                  <p class="empty-text">You have no messages here.</p>
                </div>
                
                <div v-if="activeTab === 'trash' && messages.length > 0" style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                  <label style="color: #cbd5e1; display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                    <input type="checkbox" @change="e => selectedTrashIds = e.target.checked ? messages.map(m => m.id) : []" :checked="selectedTrashIds.length === messages.length && messages.length > 0" style="accent-color: #ef4444;">
                    Select All
                  </label>
                  <button @click="permanentlyDeleteSelected" :disabled="selectedTrashIds.length === 0" class="btn-primary" style="background: #ef4444; padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; color: white; cursor: pointer; font-size: 0.85rem;" :style="{ opacity: selectedTrashIds.length === 0 ? 0.5 : 1 }">
                    Permanently Delete Selected
                  </button>
                </div>

                <div v-for="message in messages" :key="message.id" class="message-card" @click="toggleMessageExpand(message)" style="cursor: pointer; position: relative;">
                  <div v-if="activeTab === 'trash'" style="position: absolute; left: 1rem; top: 1.5rem;" @click.stop>
                    <input type="checkbox" :value="message.id" v-model="selectedTrashIds" style="accent-color: #ef4444;">
                  </div>
                  <div :style="{ paddingLeft: activeTab === 'trash' ? '2.5rem' : '0' }">
                    <div class="message-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
                      <div class="message-sender" style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                        <span class="badge" style="background: rgba(147, 51, 234, 0.2); color: #c084fc; padding: 0.2rem 0.5rem; border-radius: 0.25rem; font-size: 0.8rem; border: 1px solid rgba(147, 51, 234, 0.3);">{{ message.role || 'Unknown' }}</span>
                        <span class="sender-office" style="color: #e2e8f0; font-size: 0.95rem; font-weight: 500;">{{ getOfficeName(message.office_id) }}</span>
                        <span class="sender-email" style="color: #94a3b8; font-size: 0.9rem;">&lt;{{ message.email || 'No email' }}&gt;</span>
                        <span v-if="activeTab === 'inbox' && message.is_read == 0" class="badge" style="background: rgba(59, 130, 246, 0.2); color: #60a5fa; padding: 0.2rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; border: 1px solid rgba(59, 130, 246, 0.3);">New</span>
                      </div>
                      <div class="message-actions" style="display: flex; align-items: center; gap: 1rem;">
                        <span class="message-date" style="color: #64748b; font-size: 0.875rem;">{{ activeTab === 'trash' ? 'Deleted: ' + message.deleted_date : message.date }}</span>
                        <button v-if="activeTab === 'inbox' || activeTab === 'sent'" @click.stop="moveToTrash(message.id)" title="Move to Trash" style="background: transparent; border: none; color: #64748b; cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center;">
                          <span class="material-symbols-outlined" style="font-size: 1.2rem;">delete</span>
                        </button>
                        <button v-if="activeTab === 'trash'" @click.stop="restoreMessage(message.id)" title="Restore Message" style="background: transparent; border: none; color: #10b981; cursor: pointer; padding: 0; display: flex; align-items: center; justify-content: center;">
                          <span class="material-symbols-outlined" style="font-size: 1.2rem;">restore_from_trash</span>
                        </button>
                      </div>
                    </div>
                    
                    <h5 class="message-title" style="margin-top: 0.5rem;" :style="{ fontWeight: (activeTab === 'inbox' && message.is_read == 0) ? '700' : '500', color: (activeTab === 'inbox' && message.is_read == 0) ? '#f8fafc' : '#e2e8f0' }">{{ message.title }}</h5>
                  
                  <transition name="expand">
                    <div v-if="expandedMessageId === message.id" class="message-expanded-content" style="margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
                      <p class="message-preview" style="color: #cbd5e1; line-height: 1.6; margin: 0; white-space: pre-wrap;">{{ message.preview }}</p>
                      
                      <!-- Document Attachment Badge -->
                      <div v-if="message.document_type && message.document_id" class="message-attachment" style="margin-top: 1.5rem; padding: 0.75rem 1rem; background: rgba(147, 51, 234, 0.1); border-radius: 0.5rem; display: inline-flex; align-items: center; gap: 0.75rem; border: 1px solid rgba(185, 121, 204, 0.2);">
                        <span class="material-symbols-outlined" style="color: #c084fc; font-size: 1.2rem;">attachment</span>
                        <span style="color: #cbd5e1; font-size: 0.95rem;">
                          <strong>Attached:</strong> {{ message.document_type }}
                        </span>
                        <div style="display: flex; gap: 0.5rem;">
                          <button v-for="docId in (message.document_id ? message.document_id.split(',') : [])" :key="docId" @click.stop="(message.document_type === 'Activity Design' || message.document_type === 'design') ? viewAttachedDesign(docId) : viewAttachedReport(docId)" class="btn-view-doc" style="background: #9333ea; color: white; border: none; padding: 0.3rem 0.75rem; border-radius: 0.25rem; cursor: pointer; font-size: 0.85rem; transition: background 0.3s;">
                            View {{ (message.document_type === 'Activity Design' || message.document_type === 'design') ? 'Design' : 'Report' }}
                          </button>
                        </div>
                      </div>

                      <!-- Thread History -->
                      <div v-if="threadHistory.length > 1" class="message-thread" style="margin-bottom: 1.5rem; background: rgba(0,0,0,0.15); border-radius: 0.5rem; padding: 1rem; border: 1px solid rgba(255,255,255,0.05);">
                        <h6 style="color: #94a3b8; margin: 0 0 1rem 0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Thread History</h6>
                        <div v-for="(threadMsg, idx) in threadHistory" :key="threadMsg.id" style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.05);">
                          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <span style="color: #cbd5e1; font-weight: 500; font-size: 1rem;">{{ threadMsg.sender_name }} <span style="color: #64748b; font-size: 0.9rem;">({{ threadMsg.sender_role }})</span></span>
                            <span style="color: #64748b; font-size: 0.85rem;">{{ threadMsg.date }}</span>
                          </div>
                          <p :style="{ color: '#94a3b8', margin: threadMsg.document_id ? '0 0 0.5rem 0' : '0', fontSize: '1rem', whiteSpace: 'pre-wrap' }">{{ threadMsg.message }}</p>
                          <div v-if="threadMsg.document_id" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <button v-for="docId in threadMsg.document_id.split(',')" :key="docId" @click.stop="(threadMsg.document_type === 'Activity Design' || threadMsg.document_type === 'design') ? viewAttachedDesign(docId) : viewAttachedReport(docId)" class="btn-view-doc" style="background: rgba(147, 51, 234, 0.2); color: #c084fc; border: 1px solid rgba(147, 51, 234, 0.3); padding: 0.2rem 0.6rem; border-radius: 0.25rem; cursor: pointer; font-size: 0.75rem; transition: background 0.3s;">
                              View {{ (threadMsg.document_type === 'Activity Design' || threadMsg.document_type === 'design') ? 'Design' : 'Report' }}
                            </button>
                          </div>
                        </div>
                      </div>

                      <div style="margin-top: 1.5rem;" v-if="activeTab !== 'trash' && replyingToId !== message.id">
                        <button @click.stop="replyToMessage(message)" class="btn-reply" style="background: rgba(147, 51, 234, 0.2); color: #c084fc; border: 1px solid rgba(147, 51, 234, 0.5); padding: 0.5rem 1.25rem; border-radius: 0.5rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500; transition: all 0.3s ease;">
                          <span class="material-symbols-outlined" style="font-size: 1.1rem;">reply</span> Reply
                        </button>
                      </div>

                      <!-- Inline Reply Form -->
                      <transition name="slide-down">
                        <div v-if="replyingToId === message.id" class="inline-reply-form" @click.stop style="margin-top: 1.5rem; background: rgba(0,0,0,0.3); border-radius: 0.5rem; border: 1px solid rgba(147,51,234,0.3); padding: 1.5rem;">
                          <h5 style="margin: 0 0 1rem 0; color: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
                            Reply to {{ replyMessageContext?.sender }}
                            <button @click="cancelReply" class="btn-close" style="background: transparent; border: none; color: #94a3b8; cursor: pointer;">
                              <span class="material-symbols-outlined">close</span>
                            </button>
                          </h5>
                          

                          
                          <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; color: #cbd5e1; font-size: 0.9rem;">Message:</label>
                            <textarea 
                              v-model="replyText" 
                              class="form-control" 
                              rows="5" 
                              style="width: 100%; border-radius: 0.5rem; padding: 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.1); color: #f8fafc; resize: vertical;"
                              placeholder="Type your reply here..."
                            ></textarea>
                          </div>
                          
                          <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                            <button @click="cancelReply" class="btn-secondary" style="padding: 0.5rem 1rem; border-radius: 0.25rem; background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #f8fafc; cursor: pointer;">Cancel</button>
                            <button @click="sendReply" class="btn-primary" style="padding: 0.5rem 1rem; border-radius: 0.25rem; background: #9333ea; border: none; color: white; cursor: pointer; font-weight: 500;">Send Reply</button>
                          </div>
                        </div>
                      </transition>
                    </div>
                  </transition>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </main>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import api from '../../api';

const router = useRouter();

const messages = ref([]);
const showCreateModal = ref(false);
const selectedRole = ref('');
const selectedOffice = ref('');
const selectedUsers = ref([]);
const selectedDocumentType = ref('');
const selectedDocuments = ref([]);
const pendingDesigns = ref([]);
const pendingReports = ref([]);
const messageTitle = ref('');
const messageText = ref('');
const allUsers = ref([]);
const offices = ref([]);
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const activeTab = ref('inbox');
const expandedMessageId = ref(null);
const threadHistory = ref([]);
const selectedTrashIds = ref([]);
const showReplyModal = ref(false); // kept for safety
const replyingToId = ref(null);
const replyMessageContext = ref(null);
const replyText = ref('');
const replyDocumentType = ref('');
const replyDocuments = ref([]);
const replyPendingDesigns = ref([]);
const replyPendingReports = ref([]);

const getOfficeName = (id) => {
  if (!id) return 'Unknown Office';
  const office = offices.value.find(o => String(o.value) === String(id));
  return office ? office.label : 'Unknown Office';
};

const fetchMessages = async () => {
  if (user.value.id) {
    try {
      const endpoint = activeTab.value === 'inbox' ? `messages/inbox/${user.value.id}` 
                     : activeTab.value === 'sent' ? `messages/sent/${user.value.id}`
                     : `messages/trashed/${user.value.id}`;
      const response = await api.get(endpoint);
      if (response.data.success) {
        messages.value = response.data.data;
        selectedTrashIds.value = [];
        expandedMessageId.value = null;
      }
    } catch (err) {
      console.error('Error fetching messages:', err);
    }
  }
};

const toggleMessageExpand = async (message) => {
  if (expandedMessageId.value === message.id) {
    expandedMessageId.value = null;
  } else {
    expandedMessageId.value = message.id;
    // Mark as read
    if (activeTab.value === 'inbox' && message.is_read == 0) {
      api.post(`/messages/read/${message.id}`).then(() => {
        message.is_read = 1;
      }).catch(err => console.error("Error marking as read", err));
    }
    // Fetch thread
    threadHistory.value = [];
    api.get(`/messages/thread/${message.id}`).then(res => {
      if (res.data.success) {
        threadHistory.value = res.data.data;
      }
    }).catch(err => console.error("Error fetching thread", err));
  }
};

const moveToTrash = async (messageId) => {
  try {
    const res = await api.post(`/messages/trash/${messageId}`, { user_id: user.value.id });
    if (res.data.success) {
      Swal.fire({ icon: 'success', title: 'Trashed', text: 'Message moved to trash.', timer: 1500, showConfirmButton: false });
      fetchMessages();
    }
  } catch (err) {
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to trash message' });
  }
};

const restoreMessage = async (messageId) => {
  try {
    const res = await api.post(`/messages/restore/${messageId}`, { user_id: user.value.id });
    if (res.data.success) {
      Swal.fire({ icon: 'success', title: 'Restored', text: 'Message restored.', timer: 1500, showConfirmButton: false });
      fetchMessages();
    }
  } catch (err) {
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to restore message' });
  }
};

const permanentlyDeleteSelected = async () => {
  if (selectedTrashIds.value.length === 0) return;
  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Yes, delete permanently!'
  });
  if (result.isConfirmed) {
    try {
      const res = await api.post('/messages/permanently-delete', { 
        user_id: user.value.id, 
        message_ids: selectedTrashIds.value 
      });
      if (res.data.success) {
        Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Messages have been permanently deleted.', timer: 1500, showConfirmButton: false });
        fetchMessages();
      }
    } catch (err) {
      Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to delete messages permanently' });
    }
  }
};

const selectRole = (roleValue) => {
  selectedRole.value = roleValue;
  selectedOffice.value = '';
  selectedUsers.value = [];
  selectedDocumentType.value = '';
  selectedDocuments.value = [];
  messageTitle.value = '';
  messageText.value = '';
};

const selectOffice = (officeValue) => {
  selectedOffice.value = officeValue;
  selectedUsers.value = [];
  selectedDocumentType.value = '';
  selectedDocuments.value = [];
  messageTitle.value = '';
  messageText.value = '';
};

watch(selectedUsers, async (newVal) => {
  if (newVal.length > 0) {
    try {
      const fetchPromises = newVal.map(async (userId) => {
        try {
          const [designsRes, reportsRes] = await Promise.all([
            api.get(`activity-designs/${userId}`),
            api.get(`activity-reports/${userId}`)
          ]);
          
          const designsList = designsRes.data.data || [];
          const designs = designsList
            .filter(d => d.status === 'Pending')
            .map(d => ({
              id: `design_${d.act_design_id}`,
              title: d.title,
              type: 'Activity Design'
            }));
            
          const reportsList = reportsRes.data.data || [];
          const reports = reportsList
            .filter(r => r.status === 'Pending')
            .map(r => ({
              id: `report_${r.id}`,
              title: r.title,
              type: 'Accomplishment Report'
            }));
            
          return { designs, reports };
        } catch (e) {
          console.error(`Error fetching docs for user ${userId}`, e);
          return { designs: [], reports: [] };
        }
      });
      
      const allResults = await Promise.all(fetchPromises);
      pendingDesigns.value = allResults.flatMap(r => r.designs);
      pendingReports.value = allResults.flatMap(r => r.reports);
      
      const currentList = selectedDocumentType.value === 'design' ? pendingDesigns.value : (selectedDocumentType.value === 'report' ? pendingReports.value : []);
      if (selectedDocuments.value.length > 0) {
        selectedDocuments.value = selectedDocuments.value.filter(id => currentList.find(d => d.id === id));
      }
    } catch (err) {
      console.error('Error fetching user documents:', err);
    }
  } else {
    pendingDesigns.value = [];
    pendingReports.value = [];
    selectedDocumentType.value = '';
    selectedDocuments.value = [];
  }
});

const roles = [
  { value: 'Staff', label: 'Staff' },
  { value: 'TWG', label: 'TWG' },
  { value: 'Non-TWG', label: 'Non-TWG' }
];

const filteredUsers = computed(() => {
  let users = allUsers.value.filter(u => u.user_role === selectedRole.value);
  if (selectedOffice.value) {
    users = users.filter(u => String(u.office_id) === String(selectedOffice.value));
  }
  return users;
});

const fetchUsers = async () => {
    try {
      const [usersResponse, officesResponse] = await Promise.all([
        api.get('users'),
        api.get('office_units')
      ]);
      allUsers.value = usersResponse.data;
      offices.value = officesResponse.data.map(office => ({
        value: office.unit_id || office.office_id,
        label: office.unit_name || office.office_name
      }));
    } catch (err) {
      console.error('Error fetching users/offices:', err);
    }
};

const sendMessage = async () => {
  if (!user.value.id) return;
  try {
    const payload = {
      sender_id: user.value.id,
      to: selectedUsers.value,
      title: messageTitle.value,
      message: messageText.value,
      document_type: selectedDocumentType.value || null,
      document_id: selectedDocuments.value.length > 0 ? selectedDocuments.value : null
    };
    
    const response = await api.post('messages/send', payload);
    if (response.data.success) {
      showCreateModal.value = false;
      selectRole('');
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Message sent successfully.',
        confirmButtonColor: '#9333ea'
      });
      if (activeTab.value === 'sent') fetchMessages();
    }
  } catch (err) {
    console.error('Error sending message:', err);
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Failed to send message.',
      confirmButtonColor: '#ef4444'
    });
  }
};

const replyToMessage = async (message) => {
  replyMessageContext.value = message;
  replyText.value = '';
  replyDocumentType.value = '';
  replyDocuments.value = [];
  replyingToId.value = message.id;

  try {
    const designsRes = await api.get('/activity-designs');
    replyPendingDesigns.value = designsRes.data.filter(d => d.status === 'Pending');
    const reportsRes = await api.get('/accomplishment-reports');
    replyPendingReports.value = reportsRes.data.filter(r => r.status === 'Pending');
  } catch (error) {
    console.error('Error fetching documents for reply:', error);
  }
};

const cancelReply = () => {
  replyingToId.value = null;
  replyMessageContext.value = null;
  replyText.value = '';
  replyDocumentType.value = '';
  replyDocuments.value = [];
};

const sendReply = async () => {
  if (!replyText.value) {
    Swal.fire({ icon: 'error', title: 'Error', text: 'Message cannot be empty.' });
    return;
  }

  try {
    const payload = {
      sender_id: user.value.id,
      to: [replyMessageContext.value.sender_id],
      parent_id: replyMessageContext.value.parent_id || replyMessageContext.value.id,
      title: replyMessageContext.value.title.startsWith('Re:') ? replyMessageContext.value.title : `Re: ${replyMessageContext.value.title}`,
      message: replyText.value,
      document_type: replyDocumentType.value || null,
      document_id: replyDocuments.value.length > 0 ? replyDocuments.value : null
    };

    const response = await api.post('/messages/send', payload);
    if (response.data.success) {
      Swal.fire({
        icon: 'success',
        title: 'Sent!',
        text: 'Reply sent successfully.',
        timer: 1500,
        showConfirmButton: false
      });
      cancelReply();
      if (activeTab.value === 'inbox') fetchMessages();
    } else {
      Swal.fire({ icon: 'error', title: 'Error', text: response.data.message || 'Failed to send reply.' });
    }
  } catch (error) {
    console.error('Error sending reply:', error);
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to send reply.' });
  }
};

const viewAttachedDesign = (docId) => {
  if (!docId) return;
  const id = docId.replace('design_', '');
  router.push({ name: 'admin-ad-review', params: { id } });
};

const viewAttachedReport = (docId) => {
  if (!docId) return;
  const id = docId.replace('report_', '');
  router.push({ name: 'admin-ar-review', params: { id } });
};

onMounted(() => {
  fetchUsers();
  fetchMessages();
});
</script>

<style scoped>
.messages-main-content {
  padding-left: 0;
  flex-grow: 1;
}

.messages-content-wrapper {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.messages-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 0.25rem;
}

.messages-title {
  font-size: 1.5rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  color: #1a1a2e;
}

.messages-subtitle {
  font-size: 1rem;
  color: #475569;
  margin-top: 0.25rem;
}

.create-message-btn {
  background: linear-gradient(135deg, #9333ea 0%, #c084fc 100%);
  border: none;
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 0.75rem;
  font-size: 0.95rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.create-message-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
}

.stats-section {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
}

.stat-card {
  padding: 1rem;
  border-radius: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(8px);
  transition: all 0.3s;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
}

.stat-card:hover {
  transform: translateY(-4px);
}

.stat-card-inner {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.stat-icon {
  width: 40px;
  height: 40px;
  border-radius: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.stat-icon-symbol {
  font-weight: 500;
  font-size: 1.125rem;
}

/* Icon Colors */
.text-purple-400 { color: #c084fc; }
.text-blue-400 { color: #60a5fa; }
.text-amber-400 { color: #fbbf24; }
.text-green-400 { color: #4ade80; }

/* Background Classes */
.bg-purple-500\/10 { background: rgba(168, 85, 247, 0.1); }
.bg-blue-500\/10 { background: rgba(59, 130, 246, 0.1); }
.bg-amber-500\/10 { background: rgba(245, 158, 11, 0.1); }
.bg-green-500\/10 { background: rgba(34, 197, 94, 0.1); }

.stat-info {
  min-width: 0;
}

.stat-value {
  font-size: 1.25rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  color: white;
  line-height: 1.25;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.stat-label {
  font-size: 0.85rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: rgba(203, 213, 225, 0.7);
  margin-top: 0.125rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.messages-section {
  border-radius: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25);
  backdrop-filter: blur(8px);
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  padding: 1.5rem;
}

.section-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1.25rem;
}

.title-indicator {
  width: 0.375rem;
  height: 1rem;
  background: linear-gradient(to bottom, #9333ea, #c084fc);
  border-radius: 9999px;
}

.section-title {
  font-weight: 700;
  color: #ffffff;
  font-size: 1.125rem;
  margin: 0;
}

.messages-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.empty-state {
  border: 1px solid rgba(147, 51, 234, 0.15);
  background: transparent;
  padding: 3rem;
  border-radius: 0.75rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  margin-top: 0.5rem;
}

.empty-icon {
  width: 64px;
  height: 64px;
  background: rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(147, 51, 234, 0.1);
  border-radius: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1rem;
}

.empty-icon .material-symbols-outlined {
  font-size: 2rem;
  color: #c084fc;
}

.empty-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #c084fc;
  margin-bottom: 0.5rem;
}

.empty-text {
  font-size: 1rem;
  color: #94a3b8;
}

.message-card {
  border: 1px solid rgba(185, 121, 204, 0.1);
  border-radius: 0.75rem;
  padding: 1.25rem;
  background: rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
}

.message-card:hover {
  background: rgba(185, 121, 204, 0.05);
  border-color: rgba(185, 121, 204, 0.3);
  transform: translateX(4px);
}

.message-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.message-sender {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.sender-icon {
  color: #c084fc;
  font-size: 1.25rem;
}

.sender-name {
  color: #f1f5f9;
  font-weight: 600;
  font-size: 1rem;
}

.message-date {
  color: #94a3b8;
  font-size: 0.85rem;
  font-family: monospace;
}

.message-title {
  color: #ffffff;
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.message-preview {
  color: #cbd5e1;
  font-size: 1rem;
  line-height: 1.5;
}

/* Panel Styles */
.create-message-panel {
  border-radius: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25);
  backdrop-filter: blur(8px);
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  color: white;
}

/* Transitions */
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.3s ease-out;
  transform-origin: top;
}
.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-20px) scaleY(0.95);
}

.panel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid rgba(185, 121, 204, 0.15);
}

.panel-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #ffffff;
  margin: 0;
}

.close-btn {
  background: rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(185, 121, 204, 0.1);
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 0.5rem;
  transition: background 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-btn:hover {
  background: rgba(185, 121, 204, 0.1);
}

.close-btn .material-symbols-outlined {
  font-size: 1.5rem;
  color: #c084fc;
}

.panel-body {
  padding: 1.5rem 0;
}

.panel-footer {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  padding-top: 1.5rem;
  border-top: 1px solid rgba(185, 121, 204, 0.15);
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  font-size: 1rem;
  font-weight: 600;
  color: #ffffff;
  margin-bottom: 0.75rem;
}

.sub-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: #cbd5e1;
  margin-bottom: 0.5rem;
}

.filter-group {
  margin-bottom: 1.25rem;
}

.role-buttons,
.office-buttons {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.role-btn,
.office-btn {
  padding: 0.625rem 1.25rem;
  border: 1px solid rgba(185, 121, 204, 0.2);
  background: rgba(0, 0, 0, 0.2);
  border-radius: 0.75rem;
  font-size: 0.9rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  color: #cbd5e1;
}

.role-btn:hover,
.office-btn:hover {
  border-color: #c084fc;
  color: #c084fc;
  background: rgba(185, 121, 204, 0.05);
}

.role-btn.active,
.office-btn.active {
  background: linear-gradient(135deg, #9333ea 0%, #c084fc 100%);
  border-color: transparent;
  color: white;
}

.user-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  max-height: 200px;
  overflow-y: auto;
  border: 1px solid rgba(185, 121, 204, 0.15);
  background: rgba(0, 0, 0, 0.2);
  border-radius: 0.75rem;
  padding: 0.75rem;
}

.user-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: background 0.3s ease;
  color: #f1f5f9;
}

.user-item:hover {
  background: rgba(185, 121, 204, 0.1);
}

.user-item input[type="checkbox"] {
  width: 1.25rem;
  height: 1.25rem;
  accent-color: #c084fc;
}

.user-email {
  color: #94a3b8;
  font-size: 0.85em;
  margin-left: 0.25rem;
}

.no-users-msg {
  color: #94a3b8;
  font-style: italic;
  padding: 0.5rem;
}

.form-input {
  width: 100%;
  padding: 0.875rem 1rem;
  border: 1px solid rgba(185, 121, 204, 0.2);
  background: rgba(0, 0, 0, 0.2);
  color: white;
  border-radius: 0.75rem;
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

.form-input::placeholder {
  color: #64748b;
}

.form-input:focus {
  outline: none;
  border-color: #c084fc;
}

.form-textarea {
  width: 100%;
  padding: 0.875rem 1rem;
  border: 1px solid rgba(185, 121, 204, 0.2);
  background: rgba(0, 0, 0, 0.2);
  color: white;
  border-radius: 0.75rem;
  font-size: 1rem;
  font-family: inherit;
  transition: border-color 0.3s ease;
  resize: vertical;
}

.form-textarea::placeholder {
  color: #64748b;
}

.form-textarea:focus {
  outline: none;
  border-color: #c084fc;
}

.btn-primary,
.btn-secondary {
  padding: 0.75rem 1.5rem;
  border-radius: 0.75rem;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-primary {
  background: linear-gradient(135deg, #9333ea 0%, #c084fc 100%);
  border: none;
  color: white;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px -5px rgba(147, 51, 234, 0.4);
}

.btn-secondary {
  background: rgba(0, 0, 0, 0.2);
  border: 1px solid rgba(185, 121, 204, 0.2);
  color: #cbd5e1;
}

.btn-secondary:hover {
  border-color: #c084fc;
  color: white;
  background: rgba(185, 121, 204, 0.1);
}

/* Scrollbar */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.1);
}

::-webkit-scrollbar-thumb {
  background: rgba(185, 121, 204, 0.3);
  border-radius: 99px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(153, 13, 209, 0.5);
}

/* Responsive */
@media (max-width: 768px) {
  .messages-content-wrapper {
    padding: 20px;
  }
}
</style>
