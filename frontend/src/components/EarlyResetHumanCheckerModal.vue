<template>
  <div v-if="isOpen" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
    <div class="relative w-full max-w-md p-6 bg-surface dark:bg-zinc-900 border border-purple-500/30 rounded-2xl shadow-2xl overflow-hidden transition-all duration-300">
      
      <!-- Top Header -->
      <div class="flex items-center justify-between pb-3 mb-4 border-b border-outline-variant/50">
        <div class="flex items-center gap-2">
          <span class="material-symbols-outlined text-purple-600 dark:text-purple-400 text-2xl">
            security
          </span>
          <div>
            <h3 class="text-sm font-bold text-on-surface dark:text-zinc-100">Smart Identifier Verification</h3>
            <p class="text-[11px] text-on-surface-variant dark:text-zinc-400">Early Reset Security Authorization</p>
          </div>
        </div>

        <button 
          type="button" 
          @click="$emit('close')"
          class="p-1 rounded-lg text-on-surface-variant hover:text-on-surface dark:text-zinc-400 dark:hover:text-zinc-200 hover:bg-surface-variant/50 transition-colors"
          title="Cancel"
        >
          <span class="material-symbols-outlined text-lg">close</span>
        </button>
      </div>

      <!-- Challenge Steps Indicator -->
      <div class="flex items-center justify-between mb-4 px-2">
        <div 
          v-for="step in 3" 
          :key="step" 
          class="flex items-center gap-2 flex-1"
        >
          <div 
            :class="[
              'flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold transition-all',
              currentRound === step 
                ? 'bg-purple-600 text-white ring-4 ring-purple-500/20 shadow-md' 
                : currentRound > step 
                  ? 'bg-emerald-500 text-white' 
                  : 'bg-surface-variant dark:bg-zinc-800 text-on-surface-variant/60 dark:text-zinc-500'
            ]"
          >
            <span v-if="currentRound > step" class="material-symbols-outlined text-sm font-bold">check</span>
            <span v-else>{{ step }}</span>
          </div>
          <div 
            v-if="step < 3" 
            :class="[
              'h-1 flex-1 rounded-full transition-all',
              currentRound > step ? 'bg-emerald-500' : 'bg-surface-variant dark:bg-zinc-800'
            ]"
          ></div>
        </div>
      </div>

      <!-- Notice Banner -->
      <div class="p-3 mb-4 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-700 dark:text-purple-300 text-xs leading-relaxed">
        <strong>Challenge {{ currentRound }} of 3:</strong> You are requesting an early reset for active Fiscal Year <strong>{{ fiscalYear }}</strong>. Type the words below <strong>strictly as shown</strong>.
      </div>

      <!-- Canvas Area -->
      <div class="relative flex items-center justify-between bg-zinc-950 border border-purple-500/30 rounded-xl p-3 mb-3 overflow-hidden shadow-inner">
        <div class="canvas-wrapper flex-1 flex justify-center items-center select-none overflow-hidden">
          <canvas 
            ref="wordCanvas" 
            class="rounded pointer-events-none select-none max-w-full"
            height="65"
            width="280"
          ></canvas>
        </div>
        
        <!-- Refresh Button -->
        <button 
          type="button" 
          @click="fetchNewChallenge" 
          :disabled="fetchingWords"
          title="Get new words for this challenge"
          class="ml-2 shrink-0 p-2.5 rounded-lg bg-zinc-800 hover:bg-purple-600/30 text-zinc-300 hover:text-purple-300 transition-all cursor-pointer disabled:opacity-50"
        >
          <span class="material-symbols-outlined text-lg" :class="{ 'animate-spin': fetchingWords }">
            refresh
          </span>
        </button>
      </div>

      <!-- Input Field -->
      <div class="space-y-3">
        <div>
          <input 
            ref="inputField"
            v-model="userInput" 
            @keydown.enter.prevent="verifyCurrentChallenge"
            type="text" 
            autocomplete="off"
            autocorrect="off"
            autocapitalize="characters"
            spellcheck="false"
            placeholder="Type words strictly (case-sensitive)..."
            :class="[
              'w-full px-4 py-2.5 bg-surface-container dark:bg-zinc-800 border text-sm rounded-xl text-on-surface dark:text-zinc-100 placeholder:text-on-surface-variant/50 outline-none transition-all',
              inputError 
                ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500' 
                : 'border-outline-variant dark:border-zinc-700 focus:border-purple-500 focus:ring-1 focus:ring-purple-500'
            ]"
          />
          
          <!-- Error / Info Feedback -->
          <div v-if="inputError" class="flex items-center gap-1.5 text-xs text-red-500 dark:text-red-400 font-medium mt-1.5 px-1 animate-shake">
            <span class="material-symbols-outlined text-sm">error</span>
            <span>{{ inputError }}</span>
          </div>
          <div v-else class="text-[11px] text-on-surface-variant/70 dark:text-zinc-400 mt-1 px-1">
            Exact match required: Letters, spaces, and UPPERCASE must match.
          </div>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-3 pt-2">
          <button 
            type="button" 
            @click="$emit('close')"
            class="flex-1 py-2.5 px-4 rounded-xl border border-outline-variant dark:border-zinc-700 text-xs font-semibold text-on-surface dark:text-zinc-300 hover:bg-surface-variant/50 transition-colors cursor-pointer"
          >
            Cancel
          </button>
          <button 
            type="button" 
            @click="verifyCurrentChallenge"
            class="flex-1 py-2.5 px-4 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-semibold shadow-md active:scale-95 transition-all cursor-pointer flex items-center justify-center gap-1"
          >
            <span>Verify ({{ currentRound }}/3)</span>
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue';
import api from '../api';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  fiscalYear: {
    type: [Number, String],
    default: new Date().getFullYear()
  }
});

const emit = defineEmits(['close', 'verified']);

const currentRound = ref(1);
const challengeWords = ref('');
const userInput = ref('');
const inputError = ref('');
const fetchingWords = ref(false);
const wordCanvas = ref(null);
const inputField = ref(null);

const localWordBank = [
  'SUMMIT', 'BREEZE', 'SILVER', 'HARBOR', 'FOREST',
  'PLANET', 'CANYON', 'BRIDGE', 'STREAM', 'CASTLE',
  'GOLDEN', 'SHADOW', 'BRIGHT', 'FALCON', 'MEADOW',
  'VALLEY', 'SPIRIT', 'SHIELD', 'ORANGE', 'PURPLE',
  'BEACON', 'FLOWER', 'SPRING', 'WINTER', 'SUMMER',
  'AUTUMN', 'GARDEN', 'RIVER', 'ISLAND', 'TEMPLE',
  'GALAXY', 'ANCHOR', 'PENCIL', 'WINDOW', 'VOYAGE',
  'MIRROR', 'CLOVER', 'TIGER', 'NATURE', 'DESERT',
  'SUNSET', 'CRYSTAL', 'CORAL', 'COSMOS', 'HARMONY',
  'THUNDER', 'VELVET', 'ZENITH', 'AURORA', 'COMPASS'
];

watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    currentRound.value = 1;
    userInput.value = '';
    inputError.value = '';
    fetchNewChallenge();
  }
});

const fetchNewChallenge = async () => {
  fetchingWords.value = true;
  userInput.value = '';
  inputError.value = '';

  try {
    const res = await api.get('human-challenge', { timeout: 6000 });
    if (res.data && res.data.words) {
      challengeWords.value = res.data.words;
    } else {
      useFallbackWords();
    }
  } catch (err) {
    useFallbackWords();
  } finally {
    fetchingWords.value = false;
    await nextTick();
    drawWords();
    if (inputField.value) {
      inputField.value.focus();
    }
  }
};

const useFallbackWords = () => {
  const k1 = Math.floor(Math.random() * localWordBank.length);
  let k2 = Math.floor(Math.random() * localWordBank.length);
  if (k2 === k1) k2 = (k1 + 1) % localWordBank.length;
  challengeWords.value = `${localWordBank[k1]} ${localWordBank[k2]}`;
};

const drawWords = () => {
  const canvas = wordCanvas.value;
  if (!canvas) return;

  const ctx = canvas.getContext('2d');
  if (!ctx) return;

  const width = canvas.width;
  const height = canvas.height;

  // Background
  const grad = ctx.createLinearGradient(0, 0, width, height);
  grad.addColorStop(0, '#111827');
  grad.addColorStop(1, '#030712');
  ctx.fillStyle = grad;
  ctx.fillRect(0, 0, width, height);

  // Anti-bot curves & noise
  ctx.lineWidth = 1;
  for (let i = 0; i < 4; i++) {
    ctx.strokeStyle = `rgba(168, 85, 247, ${0.15 + Math.random() * 0.18})`;
    ctx.beginPath();
    ctx.moveTo(Math.random() * width, Math.random() * height);
    ctx.bezierCurveTo(
      Math.random() * width, Math.random() * height,
      Math.random() * width, Math.random() * height,
      Math.random() * width, Math.random() * height
    );
    ctx.stroke();
  }

  // Dots
  for (let i = 0; i < 35; i++) {
    ctx.fillStyle = `rgba(192, 132, 252, ${0.2 + Math.random() * 0.25})`;
    ctx.beginPath();
    ctx.arc(Math.random() * width, Math.random() * height, Math.random() * 1.6, 0, Math.PI * 2);
    ctx.fill();
  }

  // Draw characters
  const text = challengeWords.value || 'SECURITY CHECK';
  const colors = ['#c084fc', '#818cf8', '#38bdf8', '#f472b6', '#a78bfa', '#e879f9', '#60a5fa'];
  
  ctx.textBaseline = 'middle';
  const charSpacing = width / (text.length + 2);
  let startX = charSpacing * 0.9;

  for (let i = 0; i < text.length; i++) {
    const char = text[i];
    const angle = (Math.random() - 0.5) * 0.28;
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

const verifyCurrentChallenge = async () => {
  inputError.value = '';
  const trimmed = (userInput.value || '').trim();
  const target = (challengeWords.value || '').trim();

  if (!trimmed) {
    inputError.value = 'Please type the words shown above.';
    return;
  }

  if (trimmed === target) {
    // Challenge Passed!
    if (currentRound.value < 3) {
      currentRound.value++;
      await fetchNewChallenge();
    } else {
      // Completed Round 3 of 3!
      emit('verified');
      emit('close');
    }
  } else {
    // Option A: Mistyped — display error and refresh words for this round without resetting round
    inputError.value = 'Strict match failed. Words have been refreshed for this round.';
    await fetchNewChallenge();
  }
};
</script>

<style scoped>
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  20%, 60% { transform: translateX(-4px); }
  40%, 80% { transform: translateX(4px); }
}
.animate-shake {
  animation: shake 0.3s ease-in-out;
}
.animate-fade-in {
  animation: fadeIn 0.2s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.98); }
  to { opacity: 1; transform: scale(1); }
}
</style>

