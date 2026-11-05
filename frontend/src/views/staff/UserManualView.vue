<template>
  <main class="content-main">
    <div class="manual-page-container">
      <!-- Header Section -->
      <div class="manual-header-card">
        <div class="header-left">
          <div class="header-badge">
            <span class="material-symbols-outlined text-sm">menu_book</span>
            <span>Staff Documentation &amp; User Guides</span>
          </div>
          <h1 class="page-title">GAD Staff User Manual</h1>
          <p class="page-subtitle">
            Welcome to the Benguet State University Gender and Development Activities Management System (GAD-AMS) User Manual for GAD Staff.
            Explore the interactive step-by-step walkthrough or watch the video demonstration below.
          </p>
        </div>

        <!-- Mode Switcher Tabs -->
        <div class="tab-controls-wrapper">
          <div class="mode-tabs">
            <button
              type="button"
              class="tab-btn"
              :class="{ active: activeMode === 'slide' }"
              @click="activeMode = 'slide'"
            >
              <span class="material-symbols-outlined text-[18px]">auto_stories</span>
              <span>Interactive Slides</span>
            </button>
            <button
              type="button"
              class="tab-btn"
              :class="{ active: activeMode === 'video' }"
              @click="activeMode = 'video'"
            >
              <span class="material-symbols-outlined text-[18px]">smart_display</span>
              <span>Video Walkthrough</span>
            </button>
            <button
              type="button"
              class="tab-btn"
              :class="{ active: activeMode === 'both' }"
              @click="activeMode = 'both'"
            >
              <span class="material-symbols-outlined text-[18px]">view_agenda</span>
              <span>View Both</span>
            </button>
          </div>

          <a
            :href="activeMode === 'video' ? videoSrc : slideSrc"
            target="_blank"
            rel="noopener noreferrer"
            class="open-external-btn"
            title="Open in new window"
          >
            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
            <span class="hidden sm:inline">Fullscreen</span>
          </a>
        </div>
      </div>

      <!-- Main Viewer Area -->
      <div class="manual-content-wrapper">
        <!-- Slide Mode -->
        <div v-show="activeMode === 'slide' || activeMode === 'both'" class="viewer-section">
          <div v-if="activeMode === 'both'" class="section-subheading">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">auto_stories</span>
              <h2 class="text-lg font-bold text-slate-800 dark:text-white">Interactive Slide Guide</h2>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Step through GAD staff workflows, document reviews, and administrative tools.</p>
          </div>
          <div class="iframe-container slide-container">
            <iframe
              :src="slideSrc"
              width="800"
              height="679"
              allow="fullscreen"
              class="manual-iframe"
              style="aspect-ratio: 1 / 1; border: 0; min-height: 480px; width: 100%; max-width: 900px;"
            ></iframe>
          </div>
        </div>

        <!-- Video Mode -->
        <div v-show="activeMode === 'video' || activeMode === 'both'" class="viewer-section" :class="{ 'mt-10 pt-10 border-t border-slate-200 dark:border-white/10': activeMode === 'both' }">
          <div v-if="activeMode === 'both'" class="section-subheading">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">smart_display</span>
              <h2 class="text-lg font-bold text-slate-800 dark:text-white">Video Walkthrough</h2>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Watch the complete video demonstration of the GAD Staff features and processes.</p>
          </div>
          <div class="iframe-container video-container">
            <iframe
              :src="videoSrc"
              width="100%"
              height="800"
              allow="fullscreen"
              class="manual-iframe"
              style="aspect-ratio: 16 / 12; border: 0; min-height: 480px; width: 100%; max-width: 1100px;"
            ></iframe>
          </div>
        </div>
      </div>

      <!-- Support Footer Box -->
      <div class="support-card">
        <div class="support-icon">
          <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-2xl">support_agent</span>
        </div>
        <div class="support-info">
          <h3 class="support-heading">Technical Support &amp; Administrator Assistance</h3>
          <p class="support-desc">
            For technical assistance, bug reports, or system permissions inquiries, please contact the GAD-AMS System Administrator.
          </p>
          <div class="support-meta">
            <span><strong>Email:</strong> <a href="mailto:gad.office@bsu.edu.ph" class="text-purple-600 dark:text-purple-400 hover:underline">gad.office@bsu.edu.ph</a></span>
            <span class="hidden sm:inline">•</span>
            <span><strong>Telephone:</strong> (074) 422-2401 loc 123</span>
            <span class="hidden sm:inline">•</span>
            <span>Benguet State University</span>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const activeMode = ref('slide');

const slideSrc = 'https://scribehow.com/embed/GAD_Staff_User_Manual__SElm2OgoTmCz099KmkPGQw';
const videoSrc = 'https://scribehow.com/embed/GAD_Staff_User_Manual__SElm2OgoTmCz099KmkPGQw?as=video';

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
  width: 100%;
}

.manual-page-container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

/* Header Card */
.manual-header-card {
  background: #ffffff;
  border-radius: 1.25rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
  padding: 1.75rem 2rem;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  transition: all 0.3s ease;
}

.header-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.35rem 0.75rem;
  border-radius: 9999px;
  background: rgba(147, 51, 234, 0.08);
  color: #7e22ce;
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  margin-bottom: 0.5rem;
}

.page-title {
  font-size: 1.75rem;
  font-weight: 800;
  color: #0f172a;
  letter-spacing: -0.025em;
  margin: 0 0 0.5rem 0;
}

.page-subtitle {
  color: #64748b;
  font-size: 0.95rem;
  line-height: 1.5;
  margin: 0;
  max-width: 850px;
}

/* Mode Switcher Tabs */
.tab-controls-wrapper {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #f1f5f9;
}

.mode-tabs {
  display: inline-flex;
  background: #f1f5f9;
  padding: 0.25rem;
  border-radius: 0.75rem;
  gap: 0.25rem;
}

.tab-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 600;
  color: #64748b;
  background: transparent;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.tab-btn:hover {
  color: #0f172a;
}

.tab-btn.active {
  background: #ffffff;
  color: #7e22ce;
  box-shadow: 0 2px 8px -1px rgba(0, 0, 0, 0.08);
}

.open-external-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.5rem 0.875rem;
  border-radius: 0.5rem;
  font-size: 0.8125rem;
  font-weight: 600;
  color: #475569;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  text-decoration: none;
  transition: all 0.2s ease;
}

.open-external-btn:hover {
  background: #f8fafc;
  color: #7e22ce;
  border-color: #cbd5e1;
}

/* Viewer Content Wrapper */
.manual-content-wrapper {
  background: #ffffff;
  border-radius: 1.25rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
  padding: 2rem;
  transition: all 0.3s ease;
}

.viewer-section {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.section-subheading {
  width: 100%;
  margin-bottom: 1.25rem;
}

.iframe-container {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.manual-iframe {
  display: block;
  border-radius: 0.75rem;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
  border: 1px solid #e2e8f0;
  background: #ffffff;
  transition: box-shadow 0.2s ease;
}

/* Support Card */
.support-card {
  background: #ffffff;
  border-radius: 1rem;
  border: 1px solid #e2e8f0;
  padding: 1.5rem;
  display: flex;
  gap: 1.25rem;
  align-items: flex-start;
  box-shadow: 0 2px 8px -1px rgba(0, 0, 0, 0.03);
}

.support-icon {
  width: 44px;
  height: 44px;
  border-radius: 0.75rem;
  background: rgba(147, 51, 234, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.support-info {
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
}

.support-heading {
  font-size: 1rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

.support-desc {
  font-size: 0.875rem;
  color: #64748b;
  margin: 0;
  line-height: 1.5;
}

.support-meta {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.8125rem;
  color: #64748b;
  margin-top: 0.25rem;
}

/* Dark Mode Overrides */
:global(.dark) .manual-header-card,
.dark .manual-header-card {
  background: #1e1b2e;
  border-color: rgba(255, 255, 255, 0.08);
}

:global(.dark) .header-badge,
.dark .header-badge {
  background: rgba(192, 132, 252, 0.12);
  color: #d8b4fe;
}

:global(.dark) .page-title,
.dark .page-title {
  color: #f8fafc;
}

:global(.dark) .page-subtitle,
.dark .page-subtitle {
  color: #94a3b8;
}

:global(.dark) .tab-controls-wrapper,
.dark .tab-controls-wrapper {
  border-top-color: rgba(255, 255, 255, 0.08);
}

:global(.dark) .mode-tabs,
.dark .mode-tabs {
  background: rgba(255, 255, 255, 0.05);
}

:global(.dark) .tab-btn,
.dark .tab-btn {
  color: #94a3b8;
}

:global(.dark) .tab-btn:hover,
.dark .tab-btn:hover {
  color: #f8fafc;
}

:global(.dark) .tab-btn.active,
.dark .tab-btn.active {
  background: #2d2640;
  color: #d8b4fe;
  box-shadow: 0 2px 8px -1px rgba(0, 0, 0, 0.3);
}

:global(.dark) .open-external-btn,
.dark .open-external-btn {
  background: #2d2640;
  color: #cbd5e1;
  border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .open-external-btn:hover,
.dark .open-external-btn:hover {
  background: #38304f;
  color: #ffffff;
}

:global(.dark) .manual-content-wrapper,
.dark .manual-content-wrapper {
  background: #1e1b2e;
  border-color: rgba(255, 255, 255, 0.08);
}

:global(.dark) .manual-iframe,
.dark .manual-iframe {
  border-color: rgba(255, 255, 255, 0.08);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
  background: #12101b;
}

:global(.dark) .support-card,
.dark .support-card {
  background: #1e1b2e;
  border-color: rgba(255, 255, 255, 0.08);
}

:global(.dark) .support-heading,
.dark .support-heading {
  color: #f8fafc;
}

:global(.dark) .support-desc,
.dark .support-desc {
  color: #94a3b8;
}

:global(.dark) .support-meta,
.dark .support-meta {
  color: #94a3b8;
}
</style>
