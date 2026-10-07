<template>
  <main class="content-main">
    <div class="content-wrapper">
      
      <!-- Sticky Table of Contents -->
      <div class="sidebar-container">
        <div class="sticky-toc">
          <h3 class="toc-title">
            Table of Contents
          </h3>
          <ul class="toc-list">
            <li v-for="section in tocSections" :key="section.id">
              <button 
                @click="scrollToSection(section.id)"
                class="sidebar-nav-item"
                :class="{ 'active-nav-item': activeSection === section.id }"
              >
                {{ section.label }}
              </button>
            </li>
          </ul>
        </div>
      </div>

      <!-- Main Documentation Content -->
      <div class="content-area">
        <div class="manual-container">
          
          <!-- 1. Introduction -->
          <div id="intro" class="manual-section">
            <h1 class="page-title">GAD Staff User Manual</h1>
            <p class="intro-text">
              Welcome to the Benguet State University Gender and Development Activities Management System (GAD-AMS) User Manual. This guide will help you navigate and use the system effectively as a GAD Staff user.
            </p>
          </div>

          <!-- 2. Getting Started -->
          <div id="getting-started" class="manual-section">
            <h2 class="section-title">2. Getting Started</h2>
            
            <div class="subsection">
              <h3 class="subsection-title">2.1 Logging In</h3>
              <p class="subsection-text">
                Access the system via your web browser using the provided URL (bsugad.com). Enter your email and password credentials provided by the system administrator.
              </p>
            </div>

            <div class="subsection">
              <h3 class="subsection-title">2.2 Navigating the Dashboard</h3>
              <p class="subsection-text">
                After logging in, you will be directed to the Staff Dashboard. The top navigation bar contains main dropdown menus: <strong>Dashboard</strong>, <strong>Documents</strong>, <strong>Plan & Budget</strong>, and <strong>System & Controls</strong>.
              </p>
              <ul class="styled-list">
                <li><strong>Dashboard:</strong> Gives you a quick summary and updates.</li>
                <li><strong>Documents:</strong> Allows you to access and manage all files and reports. Its menu includes the Submitted List, Activity Design List, Accomplishment Report List, Archives, and Document Trash Bin.</li>
                <li><strong>Plan & Budget Distribution:</strong> Allows you to manage financial plans, budgets, and reports. Its menu includes Plan and Budget Distribution, Report Monitoring, and Budget Monitoring.</li>
                <li><strong>System & Controls:</strong> Allows you to manage system settings and user accounts. Its menu includes User Management, Campus Resources, and Activity Logs.</li>
              </ul>
              <div class="note-box">
                <strong>Note:</strong> On mobile devices or smaller viewports, the navigation bar collapses into a hamburger menu icon (three horizontal lines) on the left side of the header, which opens a side navigation panel when tapped.
              </div>
              <p class="subsection-text mt-3">Additionally, the top-right header includes utility icons for quick access:</p>
              <ul class="styled-list">
                <li><strong>Feedback/Chat Icon:</strong> A speech bubble for handling messages and inquiries.</li>
                <li><strong>Notification Bell:</strong> Displays active system notifications and alert counts.</li>
                <li><strong>User Profile Icon:</strong> Displays your profile avatar on the far right providing quick access to account management and sign-out when tapped.</li>
              </ul>
            </div>
          </div>

          <!-- 3. Dashboard Overview -->
          <div id="dashboard" class="manual-section">
            <h2 class="section-title">3. Dashboard Overview</h2>
            <p class="subsection-text">
              The dashboard provides a quick overview of key metrics and pending activities:
            </p>
            <ul class="styled-list">
              <li><strong>Pending Activity Designs</strong> - Shows number of activity designs awaiting review.</li>
              <li><strong>Pending Accomplishment Reports</strong> - Shows number of accomplishment reports awaiting review.</li>
              <li><strong>Total GAD Budget</strong> - Displays current total budget.</li>
              <li><strong>Remaining Balance</strong> - Displays the remaining budget after an accomplishment report is verified.</li>
              <li><strong>GAD Allocation</strong> - Displays the allocation based on the GAD plan and budget.</li>
              <li><strong>Calendar Widget</strong> - Shows upcoming deadlines and scheduled activities.</li>
              <li><strong>Recent Activity Logs</strong> - Tracks recent system activities.</li>
            </ul>
          </div>

          <!-- 4. Viewing And Approving of Submissions -->
          <div id="submissions" class="manual-section">
            <h2 class="section-title">4. Viewing And Approving of Submissions</h2>
            <p class="subsection-text">As a GAD Staff user, you have monitoring and approval access to all submissions:</p>
            
            <div class="subsection">
              <h3 class="subsection-title">1. Submitted List</h3>
              <p class="subsection-text">From the top navigation bar, navigate to <em>Documents > Submitted List</em> to open the submission tracking interface.</p>
              <ul class="styled-list">
                <li><strong>Summary Metrics:</strong> At the top of the page, view real-time overview cards displaying the total counts for TWG, Non-TWG, Activity Designs, and Accomplishment Reports.</li>
                <li><strong>Submitted List Details:</strong> The table displays total submissions across all users, showing the count of users from each college, office, or unit along with their respective user roles.</li>
                <li><strong>Filter and Search Options:</strong> Use the filter bar to sort records by status, form type, or office, or search directly by title or control number.</li>
              </ul>
            </div>

            <div class="subsection">
              <h3 class="subsection-title">2. Activity Designs</h3>
              <p class="subsection-text">From the top navigation bar, navigate to <em>Documents > Activity Design List</em> to view all submitted designs. Click on any activity design to view its complete details.</p>
              <ul class="styled-list">
                <li><strong>Design Assessment Area:</strong> Inside the submission view, you will find the following fields:
                  <ul class="nested-list">
                    <li><strong>Control Number:</strong> Already filled out automatically, but you can change it if needed.</li>
                    <li><strong>Assessment Date:</strong> The date when the review is being conducted.</li>
                    <li><strong>Accomplishment Deadline:</strong> The target date set for the completion of the report.</li>
                    <li><strong>Remarks/Comments:</strong> An optional space for any notes or feedback you want to add.</li>
                  </ul>
                </li>
                <li><strong>Action Buttons:</strong> Found just below the assessment area, these allow you to <strong>Approve and Archive</strong>, request a <strong>Revision</strong>, <strong>Disapprove</strong>, or <strong>Move to Trash</strong> the document.</li>
                <li><strong>Processing Revisions:</strong> When requesting a revision, you can fill out the Revision Remarks/Comments field or add your feedback directly into the PDF file before sending it back. There are two ways to do this:
                  <ol class="styled-list numeric">
                    <li>To write directly on the PDF, click the PDF highlight tool.</li>
                    <li>To use the preview instead, go back to the submission view and click Preview.</li>
                    <li>In the preview, write your notes in the small window, or click the open in tab button (near the X button) to write in the full window instead.</li>
                    <li>After writing the revision notes, click Save (next to the print icon).</li>
                    <li>Once saved, the submitter will see your notes and know what needs to be revised.</li>
                  </ol>
                </li>
              </ul>
              <div class="note-box">
                <strong>Note:</strong> There is also a modification request modal in activity design submission for the approval on approved activity design modification.
              </div>
            </div>

            <div class="subsection">
              <h3 class="subsection-title">3. Accomplishment Reports</h3>
              <p class="subsection-text">Navigate to <em>Documents > Accomplishment Reports</em> to view all reports. Click on any accomplishment report to view the complete report with ratings and attachments.</p>
              <ul class="styled-list">
                <li><strong>Action Buttons:</strong> Found just below the assessment area, these allow you to <strong>Approve and Archive</strong>, request a <strong>Revision</strong>, <strong>Disapprove</strong>, or <strong>Move to Trash</strong> the document.</li>
                <li><strong>Processing Revisions:</strong> When requesting a revision, you can fill out the Revision Remarks/Comments field or add your feedback directly into the PDF file before sending it back. There are two ways to do this:
                  <ol class="styled-list numeric">
                    <li>To write directly on the PDF, click the PDF highlight tool.</li>
                    <li>To use the preview instead, go back to the submission view and click Preview.</li>
                    <li>In the preview, write your notes in the small window, or click the open in tab button (near the X button) to write in the full window instead.</li>
                    <li>After writing the revision notes, click Save (next to the print icon).</li>
                    <li>Once saved, the submitter will see your notes and know what needs to be revised.</li>
                  </ol>
                </li>
              </ul>
              <div class="note-box">
                <strong>Note:</strong> If user decided to click the pdf highlight in the revision modal and there is more than one (1) PDF file submitted, it is better to go back to the main area and click Preview on each PDF submission.
              </div>
            </div>
          </div>

          <!-- 5. Plan and Budget Distribution -->
          <div id="plan-budget" class="manual-section">
            <h2 class="section-title">5. Plan and Budget Distribution (Editable)</h2>
            <p class="subsection-text">This section displays the full GAD plan and budget distribution:</p>
            <ul class="styled-list">
              <li><strong>Budget Distribution:</strong> An editable budget distribution tool for tracking expenses per GAD mandates. In this section, staff can also set the budget line classification for every field in budgetary requirements.</li>
              <li><strong>Baseline Amounts Modal:</strong> Used for setting specific budget limits or budget averages for budgetary requirements.</li>
              <li><strong>Excel Import:</strong> Features an option to import approved GAD plans and budget items easily (conversion instructions from PDF to Excel are also shown in the modal).</li>
            </ul>
          </div>

          <!-- 6. Report Monitoring -->
          <div id="report-monitoring" class="manual-section">
            <h2 class="section-title">6. Report Monitoring</h2>
            <p class="subsection-text">The report monitoring follows the GAD accomplishment report form:</p>
            <ul class="styled-list">
              <li><strong>Automatic Integration:</strong> Every GAD plan and budget item added in the GAD plan and budget distribution page will automatically appear in this section.</li>
              <li><strong>Expense Calculation:</strong> Actual expenses are calculated and populated automatically based on the entries in the GAD plan and budget distribution section.</li>
              <li><strong>Participant Demographics:</strong> The gender of participants is automatically inputted based on the approved accomplishment reports.</li>
              <li><strong>Archiving Reports:</strong> Once report is verified, you can archive the annual accomplishment report.</li>
            </ul>
          </div>
          
          <!-- 7. Communications -->
          <div id="communications" class="manual-section">
            <h2 class="section-title">7. Communications</h2>
            <ul class="styled-list">
              <li><strong>Messages:</strong> Use this feature to message director, staff, TWG, or non-TWG members, or to broadcast announcements.</li>
              <li><strong>Inquiries:</strong> Use this to reply to inquiries coming from outside users.</li>
            </ul>
          </div>
          
          <!-- 8. System Controls and Legal and Guides -->
          <div id="system-controls" class="manual-section">
            <h2 class="section-title">8. System Controls and Legal and Guides</h2>
            <ul class="styled-list">
              <li><strong>Campus Resources Page:</strong> An editable section for managing offices, units, venues, and holidays.</li>
              <li><strong>User Management:</strong> Allows you to view, edit, suspend, or add users, and includes a toggle switch for controlling activity design submissions from Monday through Friday.</li>
              <li><strong>Document Trash Bin:</strong> Stores soft-deleted documents.</li>
              <li><strong>User Manual and Data Privacy:</strong> Contains legal notes and user guides.</li>
            </ul>
            <div class="note-box">
              <p><strong>Note:</strong> On the settings part there is a Data Retention Policies area where the user can modify:</p>
              <ul class="nested-list">
                <li><strong>Trashbin TTL (Days):</strong> Time before data in the trash bin is permanently handled.</li>
                <li><strong>Messages TTL (Days):</strong> Time before messages automatically move to the trash.</li>
                <li><strong>Main Logs TTL (Days):</strong> Retention period for system activity logs.</li>
                <li><strong>Operational Logs TTL (Days):</strong> Retention period for logins, logouts, and user management logs.</li>
                <li><strong>Archived Documents TTL (Days):</strong> Applies specifically to Activity Designs (accomplishment reports remain permanent).</li>
                <li><strong>Drafts TTL (Days):</strong> Retention period for pending, revision, and disapproved documents.</li>
              </ul>
            </div>
          </div>

          <!-- 9. FAQs -->
          <div id="faq" class="manual-section">
            <h2 class="section-title">9. Frequently Asked Questions</h2>
            <div class="faq-list">
              <div class="faq-item" v-for="(faq, idx) in faqs" :key="idx">
                <h3 class="faq-question">Q: {{ faq.q }}</h3>
                <p class="faq-answer">A: {{ faq.a }}</p>
              </div>
            </div>
          </div>

          <!-- 10. Technical Support -->
          <div id="support" class="manual-section">
            <h2 class="section-title">10. Technical Support</h2>
            <p class="subsection-text">For technical assistance, bug reports, or system inquiries, please contact:</p>
            <div class="support-box">
              <p class="support-title">GAD-AMS System Administrator</p>
              <p class="support-text">Office: Gender and Development Office, Benguet State University</p>
              <p class="support-text">Administrator: GAD-AMS System Administrator</p>
              <p class="support-email">Email: gad.office@bsu.edu.ph</p>
              <p class="support-text">Telephone: (074) 422-2401 loc 123</p>
            </div>
            <p class="version-info">System Version: 1.0 | Last Updated: September 2026</p>
          </div>

        </div>
      </div>
    </div>
  </main>

  <footer class="footer-watermark">
    <p class="watermark-text">
      
    </p>
  </footer>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));
const activeSection = ref('intro');

const tocSections = [
  { id: 'intro', label: '1. Introduction' },
  { id: 'getting-started', label: '2. Getting Started' },
  { id: 'dashboard', label: '3. Dashboard Overview' },
  { id: 'submissions', label: '4. Viewing And Approving' },
  { id: 'plan-budget', label: '5. Plan & Budget Distribution' },
  { id: 'report-monitoring', label: '6. Report Monitoring' },
  { id: 'communications', label: '7. Communications' },
  { id: 'system-controls', label: '8. System Controls & Legal' },
  { id: 'faq', label: '9. FAQs' },
  { id: 'support', label: '10. Technical Support' }
];

const faqs = [
  { 
    q: 'What should I do if I encounter an error while submitting a form?', 
    a: 'Check that all required fields are filled out correctly. If the issue persists, contact gad.office@bsu.edu.ph.' 
  },
  { 
    q: 'Can I edit a submission after it has been submitted?', 
    a: 'Only if an activity design or accomplishment report is in revision mode or an approved activity design needs some modification.' 
  },
  { 
    q: 'How do I view the full content of uploaded files?', 
    a: 'Click on any document to view it in the browser.' 
  },
  { 
    q: 'What does the "Archive" section contain?', 
    a: 'The archive contains approved and verified activity designs and accomplishment reports. You can filter by status and type.' 
  },
  { 
    q: 'How do I interpret the Activity Rating?', 
    a: 'The activity rating uses a 0-100% scale with corresponding descriptors (Excellent, Very Satisfactory, Satisfactory, Needs Improvement, Unsatisfactory, Canceled). Refer to the rating table in the accomplishment report form.' 
  }
];

const scrollToSection = (id) => {
  activeSection.value = id;
  const element = document.getElementById(id);
  if (element) {
    const yOffset = -96; 
    const y = element.getBoundingClientRect().top + window.scrollY + yOffset;
    window.scrollTo({ top: y, behavior: 'smooth' });
  }
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'gad_staff') {
    router.push('/login');
  }
});
</script>

<style scoped>
.user-manual {
  min-height: 100vh;
  display: flex;
}

.main-content {
  flex-grow: 1;
  margin-left: 256px;
  display: flex;
  flex-direction: column;
}

.content-main {
  min-height: 100vh;
  background: transparent;
}

.content-wrapper {
  display: flex;
  gap: 2rem;
}

.sidebar-container {
  width: 256px;
  flex-shrink: 0;
}

/* ==========================================================================
   Sticky Table of Contents
   ========================================================================== */
.sticky-toc {
  position: sticky;
  top: 6rem;
  background: #ffffff;
  border-radius: 1.5rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
  padding: 1.25rem 1rem;
  transition: all 0.3s ease;
}

.toc-title {
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 0.875rem;
  padding-bottom: 0.625rem;
  border-bottom: 1px solid #f1f5f9;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  transition: all 0.3s ease;
}

.toc-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.toc-list li {
  margin-bottom: 0;
}

.sidebar-nav-item {
  width: 100%;
  text-align: left;
  padding: 0.6rem 0.85rem;
  border-radius: 0.5rem;
  font-weight: 500;
  color: #475569;
  background: transparent;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.85rem;
  line-height: 1.35;
}

.sidebar-nav-item:hover {
  background: #faf5ff;
  color: #7e22ce;
  transform: translateX(2px);
}

.active-nav-item {
  background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%) !important;
  color: #ffffff !important;
  font-weight: 600 !important;
  box-shadow: 0 4px 12px rgba(147, 51, 234, 0.25);
}

.active-nav-item:hover {
  transform: none;
  color: #ffffff !important;
}

/* ==========================================================================
   Content Area & Manual Container
   ========================================================================== */
.content-area {
  flex: 1;
  min-width: 0;
  background: transparent;
}

.manual-container {
  background: #ffffff;
  border-radius: 1.5rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
  padding: 2.25rem;
  transition: all 0.3s ease;
}

.manual-section {
  scroll-margin-top: 96px;
  padding-top: 2rem;
  border-top: 1px solid #f1f5f9;
  transition: border-color 0.3s ease;
}

.manual-section:first-of-type {
  border-top: none;
  padding-top: 0;
}

/* Typography */
.page-title {
  font-size: 2rem;
  font-weight: 800;
  background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  margin-bottom: 1rem;
  letter-spacing: -0.025em;
}

.intro-text {
  font-size: 1.05rem;
  color: #334155;
  line-height: 1.7;
  transition: color 0.3s ease;
}

.section-title {
  font-size: 1.35rem;
  font-weight: 700;
  color: #6b21a8;
  margin-bottom: 1.25rem;
  transition: color 0.3s ease;
}

.subsection {
  margin-bottom: 1.5rem;
}

.subsection-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #7e22ce;
  margin-bottom: 0.5rem;
  transition: color 0.3s ease;
}

.subsection-text {
  font-size: 0.975rem;
  color: #334155;
  margin-bottom: 1rem;
  line-height: 1.65;
  transition: color 0.3s ease;
}

.bold-text {
  font-weight: 600;
  color: #7e22ce;
  transition: color 0.3s ease;
}

/* Lists */
.styled-list {
  list-style: disc;
  padding-left: 1.25rem;
  margin: 0 0 1rem 0.5rem;
}

.styled-list li {
  font-size: 0.975rem;
  color: #334155;
  margin-bottom: 0.5rem;
  line-height: 1.6;
  transition: color 0.3s ease;
}

.styled-list li strong {
  color: #6b21a8;
  font-weight: 700;
  transition: color 0.3s ease;
}

.styled-list.numeric {
  list-style: decimal;
}

.nested-list {
  list-style: disc;
  padding-left: 1.5rem;
  margin-top: 0.5rem;
  margin-bottom: 0.5rem;
}

.nested-list li {
  font-size: 0.95rem;
  color: #475569;
  margin-bottom: 0.35rem;
  transition: color 0.3s ease;
}

/* FAQ Section */
.faq-list {
  margin-top: 0.5rem;
}

.faq-item {
  padding-top: 1.25rem;
  padding-bottom: 0.5rem;
  border-top: 1px solid #f1f5f9;
  transition: border-color 0.3s ease;
}

.faq-item:first-child {
  padding-top: 0;
  border-top: none;
}

.faq-question {
  font-size: 1.05rem;
  font-weight: 700;
  color: #7e22ce;
  margin-bottom: 0.5rem;
  transition: color 0.3s ease;
}

.faq-answer {
  font-size: 0.975rem;
  color: #334155;
  line-height: 1.65;
  transition: color 0.3s ease;
}

/* Note Box */
.note-box {
  margin-top: 1rem;
  padding: 0.875rem 1.125rem;
  border-radius: 0.625rem;
  border: 1px solid #e9d5ff;
  border-left: 4px solid #9333ea;
  background: #faf5ff;
  color: #334155;
  font-size: 0.875rem;
  line-height: 1.6;
  transition: all 0.3s ease;
}

/* Support Box */
.support-box {
  padding: 1.25rem 1.5rem;
  border-radius: 0.75rem;
  border: 1px solid #e9d5ff;
  background: linear-gradient(135deg, #ffffff 0%, #faf5ff 100%);
  margin-top: 0.75rem;
  transition: all 0.3s ease;
}

.support-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: #7e22ce;
  margin-bottom: 0.35rem;
  transition: color 0.3s ease;
}

.support-text {
  font-size: 0.875rem;
  color: #475569;
  margin-bottom: 0.35rem;
  line-height: 1.5;
  transition: color 0.3s ease;
}

.support-email {
  font-size: 0.875rem;
  color: #7e22ce;
  font-weight: 600;
  margin-top: 0.5rem;
  margin-bottom: 0.25rem;
  transition: color 0.3s ease;
}

/* Version Info */
.version-info {
  font-size: 0.75rem;
  color: #94a3b8;
  margin-top: 1.25rem;
  transition: color 0.3s ease;
}

/* Footer */
.footer-watermark {
  padding: 1.25rem 1rem;
  text-align: center;
  border-top: 1px solid #f1f5f9;
  pointer-events: none;
  background: transparent;
  transition: border-color 0.3s ease;
}

.watermark-text {
  font-size: 0.75rem;
  font-weight: 400;
  color: #94a3b8;
  transition: color 0.3s ease;
}

/* Responsive */
@media (max-width: 768px) {
  .content-wrapper {
    flex-direction: column;
  }
  
  .sidebar-container {
    width: 100%;
  }
  
  .main-content {
    margin-left: 0;
  }
  
  .content-main {
    padding: 1rem;
  }
  
  .manual-container {
    padding: 1.25rem;
  }
}

/* ==========================================================================
   Dark Mode Overrides
   ========================================================================== */
:global(.dark) .sticky-toc,
.dark .sticky-toc {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border-color: rgba(185, 121, 204, 0.2);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
}

:global(.dark) .toc-title,
.dark .toc-title {
  color: #cbd5e1;
  border-bottom-color: rgba(185, 121, 204, 0.15);
}

:global(.dark) .sidebar-nav-item,
.dark .sidebar-nav-item {
  color: #cbd5e1;
}

:global(.dark) .sidebar-nav-item:hover,
.dark .sidebar-nav-item:hover {
  background: rgba(0, 0, 0, 0.3);
  color: #deb7ff;
}

:global(.dark) .active-nav-item,
.dark .active-nav-item {
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%) !important;
  color: #ffffff !important;
}

:global(.dark) .manual-container,
.dark .manual-container {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border-color: rgba(185, 121, 204, 0.2);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
}

:global(.dark) .manual-section,
.dark .manual-section {
  border-top-color: rgba(185, 121, 204, 0.15);
}

:global(.dark) .page-title,
.dark .page-title {
  background: linear-gradient(135deg, #deb7ff 0%, #c084fc 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

:global(.dark) .intro-text,
.dark .intro-text {
  color: #cbd5e1;
}

:global(.dark) .section-title,
.dark .section-title {
  background: linear-gradient(135deg, #deb7ff 0%, #c084fc 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

:global(.dark) .subsection-title,
.dark .subsection-title {
  color: #deb7ff;
}

:global(.dark) .subsection-text,
.dark .subsection-text {
  color: #cbd5e1;
}

:global(.dark) .bold-text,
.dark .bold-text {
  color: #deb7ff;
}

:global(.dark) .styled-list li,
.dark .styled-list li {
  color: #cbd5e1;
}

:global(.dark) .styled-list li strong,
.dark .styled-list li strong {
  color: #deb7ff;
}

:global(.dark) .nested-list li,
.dark .nested-list li {
  color: #94a3b8;
}

:global(.dark) .faq-item,
.dark .faq-item {
  border-top-color: rgba(185, 121, 204, 0.1);
}

:global(.dark) .faq-question,
.dark .faq-question {
  color: #deb7ff;
}

:global(.dark) .faq-answer,
.dark .faq-answer {
  color: #cbd5e1;
}

:global(.dark) .note-box,
.dark .note-box {
  border-color: rgba(185, 121, 204, 0.2);
  border-left: 4px solid #c084fc;
  background: rgba(0, 0, 0, 0.3);
  color: #cbd5e1;
}

:global(.dark) .support-box,
.dark .support-box {
  border-color: rgba(185, 121, 204, 0.2);
  background: rgba(0, 0, 0, 0.25);
}

:global(.dark) .support-title,
.dark .support-title {
  color: #deb7ff;
}

:global(.dark) .support-text,
.dark .support-text {
  color: #cbd5e1;
}

:global(.dark) .support-email,
.dark .support-email {
  color: #deb7ff;
}

:global(.dark) .version-info,
.dark .version-info {
  color: #64748b;
}

:global(.dark) .footer-watermark,
.dark .footer-watermark {
  border-top-color: rgba(185, 121, 204, 0.1);
}

:global(.dark) .watermark-text,
.dark .watermark-text {
  color: #64748b;
}
</style>
