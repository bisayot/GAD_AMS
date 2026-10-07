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
            <div class="policy-card">
              
              <div id="introduction" class="policy-section">
                <div class="policy-header">
                  <div>
                    <h1 class="policy-title">Data Privacy Statement</h1>
                    <p class="policy-subtitle">
                      Benguet State University - Gender and Development Activities Management System (GAD-AMS)
                    </p>
                  </div>
                </div>
              </div>

              <div id="intro" class="policy-section">
                <h2 class="section-heading">1. Introduction</h2>
                <p class="section-text">
                  Benguet State University (BSU) is committed to protecting the privacy and confidentiality of personal information collected, stored, and processed through the Gender and Development Activities Management System (GAD-AMS). This Data Privacy Statement outlines our practices regarding the collection, use, and protection of your personal data in compliance with the Data Privacy Act of 2012 (Republic Act No. 10173).
                </p>
              </div>

              <div id="information-collect" class="policy-section">
                <h2 class="section-heading">2. Information We Collect</h2>
                <p class="section-text">
                  The GAD-AMS collects the following types of personal information:
                </p>
                <ul class="styled-list">
                  <li>Full name and contact information (email address, office/department)</li>
                  <li>Activity design and accomplishment report submissions</li>
                  <li>Budget utilization data and financial information</li>
                  <li>Attendance records and participant demographics (sex-disaggregated data)</li>
                  <li>User activity logs and system access records</li>
                </ul>
              </div>

              <div id="purpose" class="policy-section">
                <h2 class="section-heading">3. Purpose of Collection</h2>
                <p class="section-text">
                  Your personal information is collected and processed for the following purposes:
                </p>
                <ul class="styled-list">
                  <li>Processing and evaluation of GAD activity designs and accomplishment reports</li>
                  <li>Generation of GAD Plan and Budget Distribution reports</li>
                  <li>Monitoring and evaluation of GAD program implementation</li>
                  <li>Compliance with government reporting requirements to the Philippine Commission on Women (PCW), Commission on Higher Education (CHED), and Department of Budget and Management (DBM)</li>
                  <li>Research and statistical analysis for GAD program improvement</li>
                </ul>
                <p class="section-text" style="margin-top: 1rem;">
                  Providing this information is necessary to use GAD-AMS. Without it, you will not be able to submit, process, or monitor Activity Designs, Accomplishment Reports, or related GAD records.
                </p>
              </div>

              <div id="data-sharing" class="policy-section">
                <h2 class="section-heading">4. Data Sharing and Disclosure</h2>
                <p class="section-text">
                  BSU may share aggregated, anonymized data with government agencies such as PCW, CHED, and DBM for reporting purposes. Personal information is not shared with third parties without your explicit consent, unless required by law.
                </p>
              </div>

              <div id="data-security" class="policy-section">
                <h2 class="section-heading">5. Data Security</h2>
                <p class="section-text">
                  BSU implements appropriate organizational, physical, and technical security measures to protect personal information from unauthorized access, alteration, disclosure, or destruction. These measures include access controls, encryption, regular security audits, and staff training on data protection.
                </p>
              </div>

              <div id="data-retention" class="policy-section">
                <h2 class="section-heading">6. Data Retention</h2>
                <p class="section-text">
                  Personal information is retained only for as long as necessary to fulfill the purposes for which it was collected, or as required by applicable laws and regulations. Retention periods for system records (including trash bin data, messages, activity logs, and archived documents) are governed by configurable retention settings maintained by the GAD-AMS Administrator. Approved Accomplishment Reports are retained permanently in accordance with government archival requirements; other record types are retained only for their configured retention period before deletion.
                </p>
              </div>

              <div id="contact" class="policy-section">
                <h2 class="section-heading">7. Contact Information</h2>
                <p class="section-text">For questions, concerns, or requests regarding your personal data, please contact:</p>
                
                <div class="contact-box">
                  <p class="contact-title">Data Protection Officer</p>
                  <p class="contact-text">Benguet State University</p>
                  <p class="contact-text">La Trinidad, Benguet 2601</p>
                  <p class="contact-email">Email: dpo@bsu.edu.ph</p>
                  <p class="contact-text">Tel: (074) 422-2401</p>
                </div>
              </div>

              <div id="updates" class="policy-section">
                <h2 class="section-heading">8. Updates to this Statement</h2>
                <p class="section-text">
                  This Data Privacy Statement may be updated periodically. Users will be notified of significant changes through the system or via email. The effective date of the current version is displayed below.
                </p>
                <p class="effective-date">Effective Date: September 1, 2026</p>
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
import api from '../../api';

const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));
const activeSection = ref('intro');

const tocSections = [
  { id: 'intro', label: '1. Introduction' },
  { id: 'information-collect', label: '2. Information We Collect' },
  { id: 'purpose', label: '3. Purpose of Collection' },
  { id: 'data-sharing', label: '4. Data Sharing and Disclosure' },
  { id: 'data-security', label: '5. Data Security' },
  { id: 'data-retention', label: '6. Data Retention' },
  { id: 'contact', label: '7. Contact Information' },
  { id: 'updates', label: '8. Updates to this Statement' }
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

const handleLogout = async () => {
  try {
    await api.get('logout');
    localStorage.removeItem('user');
    router.push('/login');
  } catch (err) {
    localStorage.removeItem('user');
    router.push('/login');
  }
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'gad_staff') {
    router.push('/login');
  }
});
</script>

<style scoped>
.content-main {
  min-height: 100vh;
  background: transparent;
}

.content-wrapper {
  display: flex;
  gap: 2rem;
}

/* Sidebar Table of Contents */
.sidebar-container {
  width: 256px;
  flex-shrink: 0;
}

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

/* Content Area */
.content-area {
  flex: 1;
  min-width: 0;
}

/* Policy Card */
.policy-card {
  background: #ffffff;
  border-radius: 1.5rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
  padding: 2.25rem;
  transition: all 0.3s ease;
  animation: fadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

@media (min-width: 768px) {
  .policy-card {
    padding: 2.5rem;
  }
}

/* Policy Sections */
.policy-section {
  scroll-margin-top: 96px;
  padding-top: 2rem;
  border-top: 1px solid #f1f5f9;
  transition: border-color 0.3s ease;
}

.policy-section:first-of-type {
  border-top: none;
  padding-top: 0;
}

/* Policy Header */
.policy-header {
  border-bottom: 1px solid #f1f5f9;
  padding-bottom: 1.5rem;
  margin-bottom: 0;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  transition: border-color 0.3s ease;
}

.policy-title {
  font-size: 2rem;
  font-weight: 800;
  background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  letter-spacing: -0.025em;
  margin-bottom: 0.5rem;
}

@media (min-width: 768px) {
  .policy-title {
    font-size: 2.25rem;
  }
}

.policy-subtitle {
  font-size: 1rem;
  color: #64748b;
  font-weight: 500;
  transition: color 0.3s ease;
}

/* Typography */
.section-heading {
  font-size: 1.15rem;
  font-weight: 700;
  background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  letter-spacing: 0.03em;
  text-transform: uppercase;
  margin-bottom: 0.75rem;
}

.section-text {
  font-size: 1.05rem;
  color: #334155;
  line-height: 1.75;
  transition: color 0.3s ease;
}

/* Lists */
.styled-list {
  list-style: disc;
  padding-left: 1.25rem;
  margin-left: 0.5rem;
  margin-top: 0.75rem;
  margin-bottom: 0.75rem;
}

.styled-list li {
  font-size: 1.05rem;
  color: #334155;
  margin-bottom: 0.625rem;
  line-height: 1.6;
  transition: color 0.3s ease;
}

.styled-list li::marker {
  color: #9333ea;
}

.highlight-text {
  color: #0f172a;
  font-weight: 600;
}

/* Contact Box */
.contact-box {
  margin-top: 1rem;
  padding: 1.5rem;
  border-radius: 1rem;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  max-width: 30rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
  transition: all 0.3s ease;
}

.contact-title {
  font-size: 1.1rem;
  font-weight: 700;
  background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
  margin-bottom: 0.35rem;
}

.contact-text {
  font-size: 0.95rem;
  color: #475569;
  margin-bottom: 0.25rem;
  transition: color 0.3s ease;
}

.contact-email {
  font-size: 0.95rem;
  color: #7e22ce;
  font-weight: 600;
  margin-top: 0.5rem;
  margin-bottom: 0.25rem;
}

/* Effective Date */
.effective-date {
  font-size: 0.95rem;
  color: #7e22ce;
  font-weight: 600;
  font-style: italic;
  margin-top: 1.25rem;
  transition: color 0.3s ease;
}

/* Footer */
.footer-watermark {
  padding: 1.5rem 1rem;
  text-align: center;
  border-top: 1px solid #e2e8f0;
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

/* Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(6px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive */
@media (max-width: 1024px) {
  .content-wrapper {
    flex-direction: column;
  }
  
  .sidebar-container {
    width: 100%;
  }

  .sticky-toc {
    position: static;
  }
  
  .policy-card {
    padding: 1.5rem;
  }
  
  .policy-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .policy-title {
    font-size: 1.5rem;
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

:global(.dark) .policy-card,
.dark .policy-card {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border-color: rgba(185, 121, 204, 0.2);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
}

:global(.dark) .policy-section,
.dark .policy-section {
  border-top-color: rgba(185, 121, 204, 0.15);
}

:global(.dark) .policy-header,
.dark .policy-header {
  border-bottom-color: rgba(185, 121, 204, 0.15);
}

:global(.dark) .policy-title,
.dark .policy-title {
  background: linear-gradient(135deg, #deb7ff 0%, #c084fc 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

:global(.dark) .policy-subtitle,
.dark .policy-subtitle {
  color: #cbd5e1;
}

:global(.dark) .section-heading,
.dark .section-heading {
  background: linear-gradient(135deg, #deb7ff 0%, #c084fc 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

:global(.dark) .section-text,
.dark .section-text {
  color: #cbd5e1;
}

:global(.dark) .styled-list li,
.dark .styled-list li {
  color: #cbd5e1;
}

:global(.dark) .styled-list li::marker,
.dark .styled-list li::marker {
  color: #b979cc;
}

:global(.dark) .highlight-text,
.dark .highlight-text {
  color: #deb7ff;
}

:global(.dark) .contact-box,
.dark .contact-box {
  background: rgba(0, 0, 0, 0.3);
  border-color: rgba(185, 121, 204, 0.15);
  box-shadow: none;
}

:global(.dark) .contact-title,
.dark .contact-title {
  background: linear-gradient(135deg, #deb7ff 0%, #c084fc 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

:global(.dark) .contact-text,
.dark .contact-text {
  color: #cbd5e1;
}

:global(.dark) .contact-email,
.dark .contact-email {
  color: #b979cc;
}

:global(.dark) .effective-date,
.dark .effective-date {
  color: #b979cc;
}

:global(.dark) .footer-watermark,
.dark .footer-watermark {
  border-top-color: rgba(185, 121, 204, 0.1);
}

:global(.dark) .watermark-text,
.dark .watermark-text {
  color: #cbd5e1;
}
</style>
