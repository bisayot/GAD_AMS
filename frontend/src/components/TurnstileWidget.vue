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

onMounted(() => {
  // Wait for the Turnstile API to be ready
  const renderWidget = () => {
    if (window.turnstile && turnstileElement.value) {
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
    } else {
      setTimeout(renderWidget, 100);
    }
  };
  
  renderWidget();
});

onUnmounted(() => {
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
