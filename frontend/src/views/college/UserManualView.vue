<template>
  <main class="content-main">
    <div class="content-wrapper">
      
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

      <div class="content-area">
        <div class="manual-container">
          
          <!-- 1. Introduction -->
          <div id="intro" class="manual-section">
            <h1 class="page-title">User Manual</h1>
            <p class="intro-text">
              Welcome to the Benguet State University Gender and Development Activities Management System (GAD-AMS) User Manual. This guide will help you navigate and use the system effectively as a GAD-AMS user.
            </p>
          </div>

          <!-- 2. Getting Started -->
          <div id="getting-started" class="manual-section">
            <h2 class="section-title">2. Getting Started</h2>
            
            <div class="subsection">
              <h3 class="subsection-title">2.1 Account Registration (Sign-Up)</h3>
              <p class="subsection-text">To register for a new user account:</p>
              <ol class="styled-list numeric">
                <li>Access the system via your web browser using the provided URL (bsugad.com) and click <strong>Portal Login</strong>.</li>
                <li>Click <strong>Sign Up</strong>.</li>
                <li>Fill in your <strong>Name</strong>, <strong>Role</strong>, <strong>Department</strong>, <strong>Email</strong>, and <strong>Password</strong>.
                  <ul class="nested-list">
                    <li>Password must have at least 8 characters, one uppercase, one lowercase, one number, and one special character.</li>
                  </ul>
                </li>
                <li>Check <strong>“I agree to the Privacy Policy”</strong>.</li>
                <li>Click <strong>Register</strong> to finish.</li>
              </ol>
            </div>

            <div class="subsection">
              <h3 class="subsection-title">2.2 Logging In</h3>
              <p class="subsection-text">
                Navigate the Portal Login, and enter your email and password credentials provided by the system administrator or used in registering.
              </p>
            </div>

            <div class="subsection">
              <h3 class="subsection-title">2.3 Navigating the Dashboard</h3>
              <p class="subsection-text">
                After logging in, you will be directed to your respective Dashboard. The top navigation bar contains main dropdown menus: <strong>Dashboard</strong>, <strong>Documents</strong>, <strong>Plan & Budget Distribution</strong>, and <strong>Activity Logs</strong>.
              </p>
              <ul class="styled-list">
                <li><strong>Dashboard:</strong> Gives you a quick summary and updates.</li>
                <li><strong>Documents:</strong> Allows you to access and manage all files and reports. Its menu includes New Submission, Submitted List, Archives, and Document Trash Bin.</li>
                <li><strong>Plan & Budget Distribution:</strong> Allows you to view financial plans and budget.</li>
                <li><strong>Activity Logs:</strong> Allows you to view user activity logs.</li>
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
              <li><strong>Pending Reviews:</strong> Shows number of activity designs and accomplishment reports awaiting review.</li>
              <li><strong>Total Activity Designs:</strong> Count of submitted activity designs.</li>
              <li><strong>Total Accomplishment Reports:</strong> Count of submitted accomplishment reports.</li>
              <li><strong>Gender-Disaggregated Data (GDD):</strong> Displays total participants, total male, and total female counts based on the submitted accomplishment reports.</li>
              <li><strong>Calendar Widget:</strong> Shows upcoming deadlines and scheduled activities.</li>
            </ul>
          </div>

          <!-- 4. Submitting Forms -->
          <div id="submissions" class="manual-section">
            <h2 class="section-title">4. Submitting Forms</h2>
            
            <div class="subsection">
              <h3 class="subsection-title">4.1 Activity Design Submission</h3>
              <ol class="styled-list numeric">
                <li><strong>Navigate to Form:</strong> Go to <em>Documents > New Submission</em> from the navigation menu.</li>
                <li><strong>Select Form Type:</strong> Choose <em>Activity Design</em> from the available submission options.</li>
                <li><strong>Complete the Form Fields:</strong>
                  <ul class="nested-list">
                    <li><strong>Form Type:</strong> Select Employee, INSET, Extension, or External Training.</li>
                    <li><strong>Activity Classification:</strong> Choose client, organization, or attributed.</li>
                    <li><strong>Activity Title:</strong> Enter the title of the activity.</li>
                    <li><strong>Gender Issue / GAD Mandate:</strong> Specify the targeted gender concern or mandate.</li>
                    <li><strong>Cause of Gender Issue:</strong> Select the root cause being addressed.</li>
                    <li><strong>Calculated Start and End Dates:</strong> Specify activity schedule.</li>
                    <li><strong>Venue Location:</strong> Specify where the activity will take place.</li>
                    <li><strong>Target Participants:</strong> Indicate the expected number of attendees.</li>
                    <li><strong>Proposed Budgetary Requirements:</strong> Input the financial breakdown needed.</li>
                    <li><strong>Upload File:</strong> Attach the complete activity design document in PDF format.</li>
                  </ul>
                </li>
                <li><strong>Submit:</strong> Click <em>Submit Activity Design</em> to complete the submission.</li>
              </ol>
            </div>

            <div class="subsection">
              <h3 class="subsection-title">4.2 Accomplishment Report Submission</h3>
              <ol class="styled-list numeric">
                <li><strong>Navigate to Form:</strong> Go to <em>Documents > New Submission</em> from the navigation menu.</li>
                <li><strong>Select Form Type:</strong> Choose <em>Accomplishment Report</em> from the available submission options.</li>
                <li><strong>Complete the Form Fields:</strong>
                  <ul class="nested-list">
                    <li><strong>Activity Design Control Number:</strong> Select your previously approved activity design. Doing this will automatically populate the corresponding fields below:
                      <ul class="nested-list">
                        <li>Activity Title</li>
                        <li>Form Type</li>
                        <li>Activity Classification</li>
                        <li>Gender issue/GAD mandate</li>
                        <li>Cause of gender issue</li>
                        <li>Target Participants</li>
                        <li>Calculated Start and End Dates</li>
                        <li>Venue Location</li>
                      </ul>
                    </li>
                    <li><strong>Number of Attendees:</strong> Enter the actual total headcount.</li>
                    <li><strong>Male/Female Participant:</strong> Break down the attendance by gender.</li>
                    <li><strong>Activity Rating:</strong> Evaluate the activity based on the evaluation criteria.</li>
                    <li><strong>Upload Report & Attachments:</strong> Attach your report and supporting documents, such as attendance sheets (multiple files are accepted).</li>
                    <li><strong>Actual Budgetary Expenditure:</strong> Record the final financial expenses incurred.</li>
                  </ul>
                </li>
                <li><strong>Submit:</strong> Click <em>Submit Accomplishment Report</em> to complete the submission.</li>
              </ol>
            </div>
          </div>

          <!-- 5. Viewing Submissions -->
          <div id="viewing" class="manual-section">
            <h2 class="section-title">5. Viewing Submissions</h2>
            <p class="subsection-text">Monitor submissions from the top navigation bar:</p>
            <ul class="styled-list">
              <li><strong>Activity Designs:</strong> From the top navigation bar, navigate to <em>Documents > Activity Design List</em> to view all submitted designs. Click on any activity design to view its complete details.</li>
              <li><strong>Accomplishment Reports:</strong> Navigate to <em>Documents > Accomplishment Reports</em> to view all reports. Click on any accomplishment report to view the complete report with ratings and attachments.</li>
              <li><strong>Submitted List:</strong> From the top navigation bar, navigate to <em>Documents > Submitted List</em> to open the submission tracking interface:
                <ul class="nested-list">
                  <li><strong>Summary Metric Cards:</strong> View overall document statistics at the top of the page, including Totally Active, Activity Designs, Accomplishment Reports, Pending Review, and Disapproved.</li>
                  <li><strong>Submitted List Table:</strong> View detailed records of your submissions, which display the document Type, Control Number, Activity Title, Format Type, and Status.</li>
                  <li><strong>Filter and Search Options:</strong> Use the filter bar to sort records by status, form type, or office, or search directly by title or control number.</li>
                </ul>
              </li>
            </ul>
          </div>

          <!-- 6. Plan and Budget Distribution -->
          <div id="plan-budget" class="manual-section">
            <h2 class="section-title">6. Plan and Budget Distribution</h2>
            <p class="subsection-text">
              This section displays the full GAD plan and budget where it can be exported to Excel for reference, and users can also view budget distribution per mandate.
            </p>
          </div>

          <!-- 7. System Controls -->
          <div id="system-controls" class="manual-section">
            <h2 class="section-title">7. System Controls</h2>
            <p class="subsection-text">The system controls page provides access to:</p>
            <ul class="styled-list">
              <li><strong>Activity Logs:</strong> Track system activities and user actions.</li>
              <li><strong>Document Trash Bin:</strong> Manage deleted or archived files.</li>
            </ul>
          </div>

          <!-- 8. Legal and Guides -->
          <div id="legal-guides" class="manual-section">
            <h2 class="section-title">8. Legal and Guides</h2>
            <p class="subsection-text">
              <strong>Data Privacy Policy & User Manual:</strong> View and read the system's data privacy guidelines and the user manual instructions.
            </p>
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
              <p class="support-text">Gender and Development Office</p>
              <p class="support-text">Benguet State University</p>
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
  { id: 'submissions', label: '4. Submitting Forms' },
  { id: 'viewing', label: '5. Viewing Submissions' },
  { id: 'plan-budget', label: '6. Plan & Budget Distribution' },
  { id: 'system-controls', label: '7. System Controls' },
  { id: 'legal-guides', label: '8. Legal and Guides' },
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
  if (!user.value.id || !['twg', 'non-twg'].includes(user.value.role)) {
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
