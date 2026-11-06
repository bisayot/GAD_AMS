<template>
  <main class="content-main">
    <div class="manual-page-container">
      <!-- Header Section -->
      <div class="manual-header-card">
        <div class="header-left">
          <div class="header-badge">
            <span class="material-symbols-outlined text-sm">menu_book</span>
            <span>User Guide</span>
          </div>
          <h1 class="page-title">GAD Director User Manual</h1>
          <p class="page-subtitle">
            Welcome to the Benguet State University Gender and Development Activities Management System (GAD-AMS) User Manual for the GAD Director.
            Explore the video walkthrough or browse the interactive step-by-step slides below.
          </p>
        </div>

        <!-- Mode Switcher Tabs & Fast Direct Links -->
        <div class="tab-controls-wrapper">
          <div class="mode-tabs">
            <button
              type="button"
              class="tab-btn"
              :class="{ active: activeMode === 'video' }"
              @click="switchMode('video')"
            >
              <span class="material-symbols-outlined text-[18px]">smart_display</span>
              <span>Video Walkthrough</span>
            </button>
            <button
              type="button"
              class="tab-btn"
              :class="{ active: activeMode === 'slide' }"
              @click="switchMode('slide')"
            >
              <span class="material-symbols-outlined text-[18px]">auto_stories</span>
              <span>Interactive Slides</span>
            </button>
          </div>

          <!-- Direct Link for Instant Loading -->
          <a
            :href="activeMode === 'video' ? videoSrc : slideSrc"
            target="_blank"
            rel="noopener noreferrer"
            class="open-external-btn"
            title="Open directly in Scribe for full-screen view"
          >
            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
            <span>Open in Scribe</span>
          </a>
        </div>
      </div>

      <!-- Main Viewer Area with Skeleton Loader -->
      <div class="manual-content-wrapper">
        <!-- Video Mode -->
        <div v-if="activeMode === 'video'" class="viewer-section">
          <div class="iframe-container video-container relative">
            <!-- Loading Skeleton -->
            <div v-if="isVideoLoading" class="iframe-skeleton video-skeleton">
              <div class="spinner-wrapper">
                <span class="material-symbols-outlined animate-spin text-purple-600 text-3xl">progress_activity</span>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-2">Loading video walkthrough...</p>
                <p class="text-xs text-slate-400">Streaming media from Scribe</p>
              </div>
            </div>

            <iframe
              :src="videoSrc"
              width="100%"
              height="800"
              allow="fullscreen"
              loading="lazy"
              class="manual-iframe"
              :class="{ 'opacity-0': isVideoLoading, 'opacity-100': !isVideoLoading }"
              style="aspect-ratio: 16 / 12; border: 0; min-height: 480px; width: 100%; max-width: 1100px;"
              @load="isVideoLoading = false"
            ></iframe>
          </div>
        </div>

        <!-- Interactive Slide Mode -->
        <div v-if="activeMode === 'slide'" class="viewer-section">
          <div class="iframe-container slide-container relative">
            <!-- Loading Skeleton -->
            <div v-if="isSlideLoading" class="iframe-skeleton">
              <div class="spinner-wrapper">
                <span class="material-symbols-outlined animate-spin text-purple-600 text-3xl">progress_activity</span>
                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-2">Connecting to Scribe guide...</p>
                <p class="text-xs text-slate-400">Loading interactive screenshots and instructions</p>
              </div>
            </div>

            <iframe
              :src="slideSrc"
              width="800"
              height="679"
              allow="fullscreen"
              loading="lazy"
              class="manual-iframe"
              :class="{ 'opacity-0': isSlideLoading, 'opacity-100': !isSlideLoading }"
              style="aspect-ratio: 1 / 1; border: 0; min-height: 480px; width: 100%; max-width: 900px;"
              @load="isSlideLoading = false"
            ></iframe>
          </div>
        </div>
      </div>

      <!-- Quick Tips Card: Embed vs Direct Link -->
      <div class="tips-card">
        <div class="flex items-start gap-3">
          <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-xl flex-shrink-0 mt-0.5">tips_and_updates</span>
          <div class="tips-text">
            <strong>Tip:</strong> If the embedded guide is slow due to network conditions, click <strong>“Open in Scribe”</strong> above to launch Scribe directly in a dedicated tab with native caching.
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

const activeMode = ref('video');
const isVideoLoading = ref(true);
const attendsToSlide = ref(false);
const isSlideLoading = ref(true);

const slideSrc = 'https://scribehow.com/embed/GAD_Director_User_Manual__9lmKBKycSe63_-OF8VEAzw';
const videoSrc = 'https://scribehow.com/embed/GAD_Director_User_Manual__9lmKBKycSe63_-OF8VEAzw?as=video';

const switchMode = (mode) => {
  activeMode.value = mode;
  if (mode === 'slide' && !attendsToSlide.value) {
    attendsToSlide.value = true;
    isSlideLoading.value = true;
  }
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'admin') {
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
  gap: 1.25rem;
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
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  border-radius: 0.625rem;
  font-size: 0.8125rem;
  font-weight: 600;
  color: #ffffff;
  background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
  border: none;
  text-decoration: none;
  box-shadow: 0 2px 8px rgba(109, 40, 217, 0.25);
  transition: all 0.2s ease;
}

.open-external-btn:hover {
  background: linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%);
  box-shadow: 0 4px 14px rgba(109, 40, 217, 0.35);
  transform: translateY(-1px);
  color: #ffffff;
}

/* Viewer Content Wrapper */
.manual-content-wrapper {
  background: #ffffff;
  border-radius: 1.25rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
  padding: 1.5rem;
  min-height: 520px;
  position: relative;
  transition: all 0.3s ease;
}

.viewer-section {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.iframe-container {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
}

/* Skeleton Loading State */
.iframe-skeleton {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  min-height: 480px;
  max-width: 900px;
  margin: 0 auto;
  border-radius: 0.75rem;
  background: linear-gradient(110deg, #f8fafc 8%, #f1f5f9 18%, #f8fafc 33%);
  background-size: 200% 100%;
  animation: shimmer 1.5s linear infinite;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #e2e8f0;
  z-index: 10;
}

.video-skeleton {
  max-width: 1100px;
}

.spinner-wrapper {
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;
}

@keyframes shimmer {
  to {
    background-position-x: -200%;
  }
}

.manual-iframe {
  display: block;
  border-radius: 0.75rem;
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
  border: 1px solid #e2e8f0;
  background: #ffffff;
  transition: opacity 0.3s ease, box-shadow 0.2s ease;
}

/* Tips Card */
.tips-card {
  background: rgba(147, 51, 234, 0.08);
  border: 1px solid rgba(147, 51, 234, 0.25);
  border-radius: 0.75rem;
  padding: 0.875rem 1.25rem;
}

.tips-text {
  font-size: 0.8125rem;
  line-height: 1.5;
  color: #1e293b;
  font-weight: 500;
}

.tips-text strong {
  color: #7e22ce;
  font-weight: 700;
}

/* Support Card */
.support-card {
  background: #ffffff;
  border-radius: 1rem;
  border: 1px solid #e2e8f0;
  padding: 1.25rem 1.5rem;
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

:global(.dark) .manual-content-wrapper,
.dark .manual-content-wrapper {
  background: #1e1b2e;
  border-color: rgba(255, 255, 255, 0.08);
}

:global(.dark) .iframe-skeleton,
.dark .iframe-skeleton {
  background: linear-gradient(110deg, #181524 8%, #252037 18%, #181524 33%);
  background-size: 200% 100%;
  border-color: rgba(255, 255, 255, 0.08);
}

:global(.dark) .manual-iframe,
.dark .manual-iframe {
  border-color: rgba(255, 255, 255, 0.08);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
  background: #12101b;
}

:global(.dark) .tips-card,
.dark .tips-card {
  background: rgba(192, 132, 252, 0.12);
  border-color: rgba(192, 132, 252, 0.3);
}

:global(.dark) .tips-text,
.dark .tips-text {
  color: #f1f5f9;
}

:global(.dark) .tips-text strong,
.dark .tips-text strong {
  color: #d8b4fe;
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
