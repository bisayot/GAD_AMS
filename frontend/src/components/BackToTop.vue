<template>
  <button
    v-show="isVisible"
    type="button"
    aria-label="Back to top"
    title="Back to top"
    class="back-to-top fixed bottom-6 right-6 z-[90] flex h-11 w-11 items-center justify-center rounded-full border border-purple-400 text-white shadow-lg shadow-black/20 transition-all duration-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2 focus:ring-offset-[#1a1a2e]"
    @click="scrollToTop"
  >
    <span class="material-symbols-outlined text-2xl !text-white" style="color: #ffffff !important;" aria-hidden="true">arrow_upward</span>
  </button>
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
  background-color: #9333ea !important;
  opacity: 1 !important;
}

.back-to-top:hover {
  background-color: #7e22ce !important;
}
</style>
