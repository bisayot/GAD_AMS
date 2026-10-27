<template>
  <div class="turnstile-container mb-4">
    <div ref="turnstileElement" class="cf-turnstile"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const emit = defineEmits(['verify', 'error', 'expired']);
const turnstileElement = ref(null);
let widgetId = null;

const siteKey = import.meta.env.VITE_TURNSTILE_SITE_KEY;

const renderWidget = () => {
  if (!window.turnstile || !turnstileElement.value) return;
  widgetId = window.turnstile.render(turnstileElement.value, {
    sitekey: siteKey,
    callback: (token) => {
      emit('verify', token);
    },
    'error-callback': () => {
      emit('error');
    },
    'expired-callback': () => {
      emit('expired');
    }
  });
};

onMounted(() => {
  if (window.turnstile) {
    // API already loaded (e.g. SPA navigation) - render immediately.
    renderWidget();
  } else {
    // index.html's onload handler fires this the moment api.js is ready.
    window.addEventListener('turnstile:ready', renderWidget, { once: true });
  }
});

onUnmounted(() => {
  window.removeEventListener('turnstile:ready', renderWidget);
  if (widgetId !== null && window.turnstile) {
    window.turnstile.remove(widgetId);
  }
});

const reset = () => {
  if (widgetId !== null && window.turnstile) {
    window.turnstile.reset(widgetId);
  }
};

defineExpose({ reset });
</script>

<style scoped>
.turnstile-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 65px;
}
</style>
