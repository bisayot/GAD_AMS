<template>
  <div class="admin-review-page">
    <main class="main-content">
      <div class="container">
        
        <div class="review-grid">
          
          <section class="report-preview">
            <div class="header-section">
              <div class="status-pill">
                <div class="pulse-dot"></div>
                <span>Under Review</span>
              </div>
              <h2 class="report-title">Lorem Ipsum Dolor Sit Amet Consectetur Adipiscing Elit</h2>
            </div>

            <div class="content-scroll">
              <div class="metric-grid">
                <div class="metric-card">
                  <p class="text-2xl font-bold">84</p>
                  <p class="text-xs">Total Attendees</p>
                </div>
                </div>
              
              <div class="doc-list">
                <h3>Uploaded Documents</h3>
                <div v-for="doc in docs" :key="doc.name" class="doc-item">
                  <span>{{ doc.icon }} {{ doc.name }}</span>
                  <button class="preview-btn">👁️ Preview</button>
                </div>
              </div>
            </div>
          </section>

          <aside class="assessment-sidebar">
            <div class="assessment-card">
              <h3>Assessment & Approval</h3>
              <textarea v-model="remarks" placeholder="Add your comments..."></textarea>
              
              <div class="actions">
                <button class="btn-approve" @click="approveReport">✅ Approve Report</button>
                <button class="btn-revision" @click="showRevisionModal = true">✏️ Request Revision</button>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </main>

    <div v-if="showRevisionModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Request Revision</h3>
        <textarea v-model="revisionRemarks" placeholder="Detailed comments..."></textarea>
        <input type="date" v-model="revisionDeadline">
        <div class="modal-actions">
          <button @click="showRevisionModal = false">Cancel</button>
          <button @click="sendRevision">Send Request</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const showRevisionModal = ref(false);
const remarks = ref('');
const revisionRemarks = ref('');
const revisionDeadline = ref('');

const docs = ref([
  { name: 'Accomplishment_Report.pdf', icon: '📄' },
  { name: 'Attendance_Sheet.pdf', icon: '📋' }
]);

const approveReport = () => {
  alert('Report Approved!');
  router.push('/admin/acc-reports');
};

const sendRevision = () => {
  if (!revisionRemarks.value || !revisionDeadline.value) return alert('Fill all fields');
  alert('Revision request sent!');
  showRevisionModal.value = false;
  router.push('/admin/acc-reports');
};
</script>

<style scoped>
.review-grid { display: grid; grid-template-columns: 1fr 0.4fr; gap: 32px; padding: 40px; }
.report-preview { background: white; border-radius: 12px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.assessment-sidebar { position: sticky; top: 80px; }
.assessment-card { background: #fff; padding: 24px; border-radius: 20px; border: 1px solid #f0e6ff; }
textarea { width: 100%; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; margin-top: 10px; }
.btn-approve { width: 100%; padding: 14px; background: #3f6516; color: white; border-radius: 12px; border: none; cursor: pointer; }
.btn-revision { width: 100%; padding: 14px; background: white; border: 2px solid #ecd224; margin-top: 10px; border-radius: 12px; cursor: pointer; }

/* Modal Styles */
.modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; }
.modal-content { background: white; padding: 30px; border-radius: 20px; width: 400px; }
</style>