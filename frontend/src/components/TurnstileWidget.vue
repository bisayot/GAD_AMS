<template>
  <div class="turnstile-container mb-4">
    <!-- Visual Skeleton Loader for slow internet -->
    <div v-if="loading && !isStalled" class="turnstile-skeleton">
      <div class="turnstile-spinner"></div>
      <span class="text-xs text-on-surface-variant font-medium">Checking connection security...</span>
    </div>

    <!-- Fallback / Retry if network is stalled on slow connection -->
    <div v-if="isStalled && !hasToken" class="turnstile-fallback p-3 rounded-xl border border-amber-500/30 bg-amber-500/10 text-center w-full max-w-sm">
      <p class="text-xs text-amber-600 dark:text-amber-400 font-medium mb-1.5">
        Security verification is taking longer than usual on your connection.
      </p>
      <button 
        type="button" 
        @click="retryLoad" 
        class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/20 hover:bg-amber-500/30 text-amber-700 dark:text-amber-300 font-semibold text-xs rounded-lg transition-colors cursor-pointer"
      >
        <span class="material-symbols-outlined text-sm">refresh</span>
        <span>Retry Verification</span>
      </button>
    </div>

    <!-- Turnstile DOM Mount -->
    <div ref="turnstileElement" class="cf-turnstile" v-show="!loading && !isStalled"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const emit = defineEmits(['verify', 'error', 'expired']);
const turnstileElement = ref(null);
let widgetId = null;

const siteKey = import.meta.env.VITE_TURNSTILE_SITE_KEY;

const loading = ref(true);
const isStalled = ref(false);
const hasToken = ref(false);
let tokenWaitResolvers = [];
let watchdogTimer = null;
let renderStartTime = null;

const onVerify = (token) => {
  if (renderStartTime) {
    const elapsed = Math.round(performance.now() - renderStartTime);
    console.info(`[Turnstile] Challenge completed in ${elapsed}ms`);
  }
  hasToken.value = true;
  loading.value = false;
  isStalled.value = false;
  emit('verify', token);
  // Resolve any pending wait promises
  tokenWaitResolvers.forEach(resolve => resolve(token));
  tokenWaitResolvers = [];
};

const renderWidget = () => {
  if (!window.turnstile || !turnstileElement.value) return;
  renderStartTime = performance.now();

  // Clear previous widget if retrying
  if (widgetId !== null) {
    try {
      window.turnstile.remove(widgetId);
    } catch (e) {
      // Ignore
    }
    widgetId = null;
  }

  widgetId = window.turnstile.render(turnstileElement.value, {
    sitekey: siteKey,
    execution: 'render',
    'refresh-expired': 'auto',
    retry: 'auto',
    'retry-interval': 4000,
    callback: onVerify,
    'error-callback': () => {
      loading.value = false;
      emit('error');
    },
    'expired-callback': () => {
      hasToken.value = false;
      emit('expired');
    },
    'timeout-callback': () => {
      if (widgetId !== null && window.turnstile) {
        window.turnstile.reset(widgetId);
      }
    }
  });

  // Render completed; Turnstile's iframe is mounted
  loading.value = false;
};

const startWatchdog = () => {
  if (watchdogTimer) clearTimeout(watchdogTimer);
  watchdogTimer = setTimeout(() => {
    if (!hasToken.value) {
      isStalled.value = true;
      loading.value = false;
    }
  }, 10000); // 10 seconds timeout for slow networks
};

const retryLoad = () => {
  isStalled.value = false;
  loading.value = true;
  startWatchdog();

  if (window.turnstile) {
    renderWidget();
  } else {
    // If api.js failed to load originally, re-inject the script tag
    const existingScript = document.querySelector('script[src*="turnstile/v0/api.js"]');
    if (existingScript) existingScript.remove();

    const script = document.createElement('script');
    script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit&onload=__onTurnstileLoaded';
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
  }
};

// Grace-period helper for parent submit forms
const waitForToken = (timeoutMs = 3500) => {
  if (hasToken.value) {
    return Promise.resolve(true);
  }

  return new Promise((resolve) => {
    tokenWaitResolvers.push(() => resolve(true));
    setTimeout(() => {
      resolve(hasToken.value);
    }, timeoutMs);
  });
};

onMounted(() => {
  startWatchdog();

  if (window.turnstile) {
    renderWidget();
  } else {
    window.addEventListener('turnstile:ready', () => {
      renderWidget();
    }, { once: true });
  }
});

onUnmounted(() => {
  if (watchdogTimer) clearTimeout(watchdogTimer);
  tokenWaitResolvers = [];
  window.removeEventListener('turnstile:ready', renderWidget);
  if (widgetId !== null && window.turnstile) {
    try {
      window.turnstile.remove(widgetId);
    } catch (e) {
      // Ignore
    }
  }
});

const reset = () => {
  hasToken.value = false;
  if (widgetId !== null && window.turnstile) {
    window.turnstile.reset(widgetId);
  }
};

defineExpose({ reset, waitForToken, retry: retryLoad });
</script>

<style scoped>
.turnstile-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  min-height: 65px;
}

.turnstile-skeleton {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  border: 1px dashed rgba(168, 85, 247, 0.3);
  background: rgba(168, 85, 247, 0.05);
}

.turnstile-spinner {
  width: 14px;
  height: 14px;
  border: 2px solid rgba(168, 85, 247, 0.25);
  border-top-color: rgb(168, 85, 247);
  border-radius: 50%;
  animation: turnstile-spin 0.8s linear infinite;
}

@keyframes turnstile-spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
