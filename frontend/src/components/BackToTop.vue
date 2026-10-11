<template>
  <transition name="fade-slide">
    <button
      v-show="isVisible"
      type="button"
      aria-label="Back to top"
      title="Back to top"
      class="back-to-top fixed bottom-6 right-6 z-[90] flex h-11 w-11 items-center justify-center rounded-xl border text-white shadow-lg focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 focus:ring-offset-transparent"
      @click="scrollToTop"
    >
      <span class="material-symbols-outlined icon text-2xl !text-white" aria-hidden="true">arrow_upward</span>
    </button>
  </transition>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

const isVisible = ref(false);
const visibilityThreshold = 300;

const updateVisibility = () => {
  isVisible.value = window.scrollY > visibilityThreshold;
};

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
  updateVisibility();
  window.addEventListener('scroll', updateVisibility, { passive: true });
});

onUnmounted(() => {
  window.removeEventListener('scroll', updateVisibility);
});
</script>

<style scoped>
.back-to-top {
  /* Frosted Glass Base */
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  cursor: pointer;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);

  /* Light mode (Default): Translucent purple frosted glass with idle opacity */
  background: rgba(147, 51, 234, 0.65) !important;
  border-color: rgba(168, 85, 247, 0.45) !important;
  box-shadow: 0 4px 16px rgba(147, 51, 234, 0.25), 0 2px 6px rgba(0, 0, 0, 0.08);
  opacity: 0.65 !important;
}

.back-to-top .icon {
  color: #ffffff !important;
  transition: transform 0.2s ease;
}

/* Light Mode Hover / Focus: fully opaque, elevated glow */
.back-to-top:hover,
.back-to-top:focus-visible {
  opacity: 1 !important;
  background: rgba(126, 34, 206, 0.95) !important;
  border-color: rgba(192, 132, 252, 0.8) !important;
  transform: translateY(-2px) scale(1.05);
  box-shadow: 0 8px 22px rgba(147, 51, 234, 0.42), 0 4px 10px rgba(0, 0, 0, 0.12);
}

.back-to-top:hover .icon {
  transform: translateY(-2px);
}

.back-to-top:active {
  transform: translateY(0) scale(0.98);
}

/* Dark Mode Support */
:global(.dark) .back-to-top,
:global(html.dark) .back-to-top {
  background: rgba(126, 34, 206, 0.55) !important;
  border-color: rgba(192, 132, 252, 0.35) !important;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.45), 0 0 10px rgba(147, 51, 234, 0.2);
  opacity: 0.65 !important;
}

:global(.dark) .back-to-top:hover,
:global(.dark) .back-to-top:focus-visible,
:global(html.dark) .back-to-top:hover,
:global(html.dark) .back-to-top:focus-visible {
  opacity: 1 !important;
  background: rgba(147, 51, 234, 0.9) !important;
  border-color: rgba(216, 180, 254, 0.75) !important;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6), 0 0 16px rgba(168, 85, 247, 0.45);
}

/* Smooth Entrance / Exit animation */
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: opacity 0.3s ease, transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0 !important;
  transform: translateY(12px) scale(0.9);
}
</style>
