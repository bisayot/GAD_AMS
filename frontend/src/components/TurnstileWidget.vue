<template>
  <div class="turnstile-container mb-4 w-full">
    <!-- ======================================================== -->
    <!-- MODE 1: Standard Turnstile (Default when connection is good) -->
    <!-- ======================================================== -->
    <div v-if="!isHumanChecker" class="w-full flex flex-col items-center">
      <!-- Visual Skeleton Loader for slow internet -->
      <div v-if="loading" class="turnstile-skeleton">
        <div class="turnstile-spinner"></div>
        <span class="text-xs text-on-surface-variant font-medium">Checking connection security...</span>
      </div>

      <!-- Turnstile DOM Mount -->
      <div ref="turnstileElement" class="cf-turnstile" v-show="!loading"></div>

      <!-- Quick manual fallback hint if user has weak internet -->
      <button
        v-if="loading && showWeakInternetHint"
        type="button"
        @click="activateHumanChecker('Switched by user due to slow connection')"
        class="mt-2 text-[11px] text-purple-500 hover:text-purple-400 dark:text-purple-400 underline transition-colors cursor-pointer"
      >
        Slow internet? Verify with words instead
      </button>
    </div>

    <!-- ======================================================== -->
    <!-- MODE 2: Smart Identifier Custom Human Checker Fallback   -->
    <!-- (Triggered on Turnstile retry, weak internet, or error)   -->
    <!-- ======================================================== -->
    <div 
      v-else 
      class="human-checker-card w-full p-4 rounded-2xl border border-purple-500/30 bg-purple-500/5 dark:bg-purple-950/20 backdrop-blur-sm transition-all duration-300"
    >
      <!-- Header Badge / Reason -->
      <div class="flex items-center justify-between gap-2 mb-3">
        <div class="flex items-center gap-1.5">
          <span class="material-symbols-outlined text-purple-500 dark:text-purple-400 text-base">
            shield_person
          </span>
          <span class="text-xs font-bold uppercase tracking-wider text-purple-700 dark:text-purple-300">
            Smart Identifier
          </span>
        </div>
        <span class="text-[10px] px-2 py-0.5 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-300 font-semibold border border-purple-500/20">
          Human Verification
        </span>
      </div>

      <p class="text-xs text-on-surface-variant mb-3 leading-relaxed">
        <span class="font-semibold text-purple-600 dark:text-purple-400">Connection notice:</span> 
        {{ triggerReason || 'Weak internet or verification retry detected.' }} Please type the words below <strong>strictly as shown</strong>.
      </p>

      <!-- VERIFIED STATE -->
      <div 
        v-if="isVerified" 
        class="flex items-center justify-between p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 transition-all duration-300"
      >
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-emerald-500 text-xl">check_circle</span>
          <div>
            <div class="text-xs font-bold leading-tight">Human Verification Passed</div>
            <div class="text-[11px] opacity-80">Strict word match confirmed</div>
          </div>
        </div>
        <span class="text-xs font-mono font-bold px-2 py-0.5 bg-emerald-500/20 rounded text-emerald-700 dark:text-emerald-200">
          VERIFIED
        </span>
      </div>

      <!-- CHALLENGE ACTIVE STATE -->
      <div v-else class="space-y-3">
        <!-- Visual Word Canvas Display -->
        <div class="relative flex items-center justify-between bg-surface-container/90 dark:bg-zinc-900/90 border border-outline-variant/80 rounded-xl p-2.5 overflow-hidden shadow-inner">
          <div class="canvas-wrapper flex-1 flex justify-center items-center select-none overflow-hidden">
            <canvas 
              ref="wordCanvas" 
              class="rounded pointer-events-none select-none max-w-full"
              height="60"
              width="260"
            ></canvas>
          </div>
          
          <!-- Refresh Button -->
          <button 
            type="button" 
            @click="fetchNewChallenge" 
            :disabled="fetchingWords"
            title="Get new words"
            class="ml-2 shrink-0 p-2 rounded-lg bg-surface-variant hover:bg-purple-500/20 text-on-surface-variant hover:text-purple-500 transition-all cursor-pointer disabled:opacity-50"
          >
            <span class="material-symbols-outlined text-base" :class="{ 'animate-spin': fetchingWords }">
              refresh
            </span>
          </button>
        </div>

        <!-- Strict Input Field -->
        <div>
          <input 
            v-model="userInput" 
            @keydown.enter.prevent="verifyWordsStrictly"
            @input="onInputChange"
            type="text" 
            autocomplete="off"
            autocorrect="off"
            autocapitalize="characters"
            spellcheck="false"
            placeholder="Type words above strictly..."
            :class="[
              'w-full px-3.5 py-2.5 bg-surface-container border text-sm rounded-xl text-on-surface placeholder:text-on-surface-variant/50 outline-none transition-all',
              inputError 
                ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' 
                : 'border-outline-variant focus:border-purple-500 focus:ring-1 focus:ring-purple-500'
            ]"
          />
          
          <!-- Error feedback if mismatch -->
          <div v-if="inputError" class="flex items-center gap-1 text-[11px] text-red-500 dark:text-red-400 font-medium mt-1.5 px-1 animate-shake">
            <span class="material-symbols-outlined text-xs">error</span>
            <span>{{ inputError }}</span>
          </div>
          <div v-else class="text-[10px] text-on-surface-variant/70 mt-1 px-1">
            Exact match required: Case, spelling, and spaces must match strictly.
          </div>

          <!-- Verify Button — separate row so it never overlaps the input -->
          <button 
            type="button" 
            @click="verifyWordsStrictly" 
            class="mt-2 w-full py-2 bg-purple-600 hover:bg-purple-500 text-white font-semibold text-xs rounded-xl transition-all shadow-sm active:scale-95 cursor-pointer"
          >
            Verify
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import api from '../api';

const emit = defineEmits(['verify', 'error', 'expired']);
const turnstileElement = ref(null);
const wordCanvas = ref(null);
let widgetId = null;

const siteKey = import.meta.env.VITE_TURNSTILE_SITE_KEY;

// States
const loading = ref(true);
const isStalled = ref(false);
const hasToken = ref(false);
const isHumanChecker = ref(false);
const triggerReason = ref('');
const isVerified = ref(false);
const fetchingWords = ref(false);
const userInput = ref('');
const inputError = ref('');
const showWeakInternetHint = ref(false);

const challengeWords = ref('');
const challengeKey = ref('');

let tokenWaitResolvers = [];
let watchdogTimer = null;
let weakInternetHintTimer = null;
let renderStartTime = null;

// Local fallback words in case of extreme network partition
const localWordBank = [
  'SUMMIT', 'BREEZE', 'SILVER', 'HARBOR', 'FOREST',
  'PLANET', 'CANYON', 'BRIDGE', 'STREAM', 'CASTLE',
  'GOLDEN', 'SHADOW', 'BRIGHT', 'FALCON', 'MEADOW',
  'VALLEY', 'SPIRIT', 'SHIELD', 'ORANGE', 'PURPLE',
  'BEACON', 'FLOWER', 'SPRING', 'WINTER', 'SUMMER',
  'AUTUMN', 'GARDEN', 'RIVER', 'ISLAND', 'TEMPLE',
  'GALAXY', 'ANCHOR', 'PENCIL', 'WINDOW', 'VOYAGE',
  'MIRROR', 'CLOVER', 'TIGER', 'NATURE', 'DESERT'
];

/**
 * Smart Identifier: Detect weak internet connection
 * Used only to surface the manual hint, NOT to auto-switch preemptively.
 */
const isWeakConnection = () => {
  if (typeof navigator !== 'undefined') {
    if (!navigator.onLine) return true;
    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (conn) {
      if (conn.saveData) return true;
      if (['slow-2g', '2g'].includes(conn.effectiveType)) return true;
      if (conn.rtt && conn.rtt > 2000) return true;
    }
  }
  return false;
};

/**
 * Activates the Custom Human Checker fallback
 */
const activateHumanChecker = (reason = '') => {
  if (isHumanChecker.value) return;

  console.info(`[Smart Identifier] Switching to custom human checker. Reason: ${reason}`);
  triggerReason.value = reason;
  isHumanChecker.value = true;
  loading.value = false;
  isStalled.value = false;

  // Clean up Turnstile widget if it was mounted
  if (widgetId !== null && window.turnstile) {
    try {
      window.turnstile.remove(widgetId);
    } catch (e) {}
    widgetId = null;
  }

  fetchNewChallenge();
};

/**
 * Fetch challenge words from backend API
 */
const fetchNewChallenge = async () => {
  fetchingWords.value = true;
  userInput.value = '';
  inputError.value = '';
  isVerified.value = false;
  hasToken.value = false;

  try {
    const res = await api.get('human-challenge', { timeout: 7000 });
    if (res.data && res.data.words && res.data.challenge_key) {
      challengeWords.value = res.data.words;
      challengeKey.value = res.data.challenge_key;
    } else {
      useLocalFallbackChallenge();
    }
  } catch (err) {
    console.warn('[Smart Identifier] Could not fetch server challenge, using local generator:', err);
    useLocalFallbackChallenge();
  } finally {
    fetchingWords.value = false;
    await nextTick();
    drawWordsOnCanvas();
  }
};

const useLocalFallbackChallenge = () => {
  const k1 = Math.floor(Math.random() * localWordBank.length);
  let k2 = Math.floor(Math.random() * localWordBank.length);
  if (k2 === k1) k2 = (k1 + 1) % localWordBank.length;

  challengeWords.value = `${localWordBank[k1]} ${localWordBank[k2]}`;
  const time = Math.floor(Date.now() / 1000);
  challengeKey.value = btoa(JSON.stringify({ t: time, local: true }));
};

/**
 * Renders anti-bot distorted words onto HTML5 Canvas
 */
const drawWordsOnCanvas = () => {
  const canvas = wordCanvas.value;
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  if (!ctx) return;

  const width = canvas.width;
  const height = canvas.height;

  // Clear
  ctx.clearRect(0, 0, width, height);

  // Background Gradient
  const bgGrad = ctx.createLinearGradient(0, 0, width, height);
  bgGrad.addColorStop(0, '#18181b');
  bgGrad.addColorStop(1, '#09090b');
  ctx.fillStyle = bgGrad;
  ctx.fillRect(0, 0, width, height);

  // Anti-bot background grid & noise lines
  ctx.lineWidth = 1;
  for (let i = 0; i < 4; i++) {
    ctx.strokeStyle = `rgba(168, 85, 247, ${0.15 + Math.random() * 0.15})`;
    ctx.beginPath();
    ctx.moveTo(Math.random() * width, Math.random() * height);
    ctx.bezierCurveTo(
      Math.random() * width, Math.random() * height,
      Math.random() * width, Math.random() * height,
      Math.random() * width, Math.random() * height
    );
    ctx.stroke();
  }

  // Anti-bot dots
  for (let i = 0; i < 35; i++) {
    ctx.fillStyle = `rgba(147, 51, 234, ${0.2 + Math.random() * 0.3})`;
    ctx.beginPath();
    ctx.arc(Math.random() * width, Math.random() * height, Math.random() * 1.8, 0, Math.PI * 2);
    ctx.fill();
  }

  // Draw Words with character-by-character distortion
  const text = challengeWords.value || 'SECURITY CHECK';
  const colors = ['#c084fc', '#818cf8', '#38bdf8', '#f472b6', '#a78bfa', '#e879f9', '#60a5fa'];
  
  ctx.textBaseline = 'middle';
  const charSpacing = width / (text.length + 2);
  let startX = charSpacing * 0.9;

  for (let i = 0; i < text.length; i++) {
    const char = text[i];
    const angle = (Math.random() - 0.5) * 0.28; // -8 to +8 degrees
    const yOffset = (Math.random() - 0.5) * 6;
    const color = colors[i % colors.length];

    ctx.save();
    ctx.translate(startX, height / 2 + yOffset);
    ctx.rotate(angle);

    ctx.font = 'bold 20px "Courier New", Courier, monospace';
    ctx.fillStyle = color;
    ctx.shadowColor = 'rgba(168, 85, 247, 0.4)';
    ctx.shadowBlur = 6;
    ctx.fillText(char, 0, 0);

    ctx.restore();
    startX += charSpacing;
  }
};

/**
 * Strict Verification of user input against challenge words
 */
const verifyWordsStrictly = () => {
  inputError.value = '';
  const trimmedInput = userInput.value.trim();
  const targetWords = challengeWords.value.trim();

  if (!trimmedInput) {
    inputError.value = 'Please type the words shown above.';
    return;
  }

  // Strict exact comparison (case-sensitive, exact characters)
  if (trimmedInput === targetWords) {
    isVerified.value = true;
    hasToken.value = true;
    inputError.value = '';

    // Generate verified fallback token for backend authentication
    const payload = btoa(JSON.stringify({
      key: challengeKey.value,
      answer: trimmedInput
    }));
    const fallbackToken = `hc_fallback:${payload}`;

    emit('verify', fallbackToken);

    // Resolve any pending wait promises
    tokenWaitResolvers.forEach(resolve => resolve(fallbackToken));
    tokenWaitResolvers = [];
  } else {
    inputError.value = 'Words do not match strictly. Please check spelling, spaces, and UPPERCASE letters.';
  }
};

const onInputChange = () => {
  // If user has typed the exact words strictly, auto-verify for smooth UX!
  if (userInput.value.trim() === challengeWords.value.trim()) {
    verifyWordsStrictly();
  } else if (inputError.value) {
    inputError.value = '';
  }
};

/**
 * Cloudflare Turnstile Verification Handler
 */
const onVerify = (token) => {
  if (renderStartTime) {
    const elapsed = Math.round(performance.now() - renderStartTime);
    console.info(`[Turnstile] Challenge completed in ${elapsed}ms`);
  }
  hasToken.value = true;
  loading.value = false;
  isStalled.value = false;
  emit('verify', token);
  tokenWaitResolvers.forEach(resolve => resolve(token));
  tokenWaitResolvers = [];
};

/**
 * Render Turnstile Widget
 */
const renderWidget = () => {
  if (isHumanChecker.value) return;
  if (!window.turnstile || !turnstileElement.value) return;
  renderStartTime = performance.now();

  if (widgetId !== null) {
    try {
      window.turnstile.remove(widgetId);
    } catch (e) {}
    widgetId = null;
  }

  try {
    widgetId = window.turnstile.render(turnstileElement.value, {
      sitekey: siteKey,
      execution: 'render',
      'refresh-expired': 'auto',
      // By setting retry to 'never', any retry condition immediately fires error/timeout,
      // allowing our smart identifier to switch instantly!
      retry: 'never',
      callback: onVerify,
      'error-callback': () => {
        console.warn('[Smart Identifier] Turnstile encountered error or retry condition.');
        activateHumanChecker('Cloudflare verification failed or requested retry');
        emit('error');
      },
      'expired-callback': () => {
        hasToken.value = false;
        emit('expired');
      },
      'timeout-callback': () => {
        console.warn('[Smart Identifier] Turnstile timed out.');
        activateHumanChecker('Cloudflare verification timed out');
      }
    });

    loading.value = false;
  } catch (err) {
    console.warn('[Smart Identifier] Error rendering Turnstile widget:', err);
    activateHumanChecker('Turnstile render failed');
  }
};

/**
 * Watchdog: only shows the manual hint on slow loads.
 * The fallback is NEVER auto-triggered here — only Turnstile's own
 * error-callback / timeout-callback trigger activateHumanChecker().
 */
const startWatchdog = () => {
  if (watchdogTimer) clearTimeout(watchdogTimer);
  if (weakInternetHintTimer) clearTimeout(weakInternetHintTimer);

  // Show "Slow internet?" hint after 5 seconds if still loading
  weakInternetHintTimer = setTimeout(() => {
    if (loading.value && !hasToken.value && !isHumanChecker.value) {
      showWeakInternetHint.value = true;
    }
  }, 5000);
};

/**
 * Grace-period helper for parent submit forms
 */
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

const reset = () => {
  hasToken.value = false;
  isVerified.value = false;
  userInput.value = '';
  inputError.value = '';

  if (isHumanChecker.value) {
    fetchNewChallenge();
  } else if (widgetId !== null && window.turnstile) {
    try {
      window.turnstile.reset(widgetId);
    } catch (e) {}
  }
};

const handleOnlineStatusChange = () => {
  if (!navigator.onLine && !isHumanChecker.value) {
    activateHumanChecker('Network connection is offline');
  }
};

onMounted(() => {
  window.addEventListener('offline', handleOnlineStatusChange);

  startWatchdog();

  if (window.turnstile) {
    renderWidget();
  } else {
    // Hook onerror on the turnstile script tag so a load failure triggers fallback
    const existingScript = document.querySelector('script[src*="turnstile/v0/api.js"]');
    if (existingScript) {
      existingScript.onerror = () => {
        activateHumanChecker('Failed to load Cloudflare security script');
      };
    }

    window.addEventListener('turnstile:ready', () => {
      if (!isHumanChecker.value) {
        renderWidget();
      }
    }, { once: true });
  }
});

onUnmounted(() => {
  if (watchdogTimer) clearTimeout(watchdogTimer);
  if (weakInternetHintTimer) clearTimeout(weakInternetHintTimer);
  window.removeEventListener('offline', handleOnlineStatusChange);
  tokenWaitResolvers = [];
  if (widgetId !== null && window.turnstile) {
    try {
      window.turnstile.remove(widgetId);
    } catch (e) {}
  }
});

defineExpose({ 
  reset, 
  waitForToken, 
  retry: () => isHumanChecker.value ? fetchNewChallenge() : renderWidget(),
  isHumanChecker
});
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

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  20%, 60% { transform: translateX(-4px); }
  40%, 80% { transform: translateX(4px); }
}

.animate-shake {
  animation: shake 0.35s ease-in-out;
}
</style>
