<template>
  <div class="floating-feedback-container">
    <transition name="fade-bounce">
      <div v-if="showTooltip" class="feedback-tooltip">
        Share your feedback
        <div class="tooltip-arrow"></div>
      </div>
    </transition>
    
    <button 
      class="floating-feedback-btn"
      @click="openTally"
    >
      <span class="material-symbols-outlined">chat</span>
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const showTooltip = ref(false);
let intervalId = null;

const openTally = () => {
  window.open('https://tally.so/r/NpaddQ', '_blank');
};

onMounted(() => {
  // Show tooltip initially after a brief delay
  setTimeout(() => {
    showTooltip.value = true;
    setTimeout(() => {
      showTooltip.value = false;
    }, 4000); // Hide after 4s
  }, 2000);

  // Every 5 minutes (300,000 ms), show it again for 4 seconds
  intervalId = setInterval(() => {
    showTooltip.value = true;
    setTimeout(() => {
      showTooltip.value = false;
    }, 4000);
  }, 300000);
});

onUnmounted(() => {
  if (intervalId) clearInterval(intervalId);
});
</script>

<style scoped>
.floating-feedback-container {
  position: fixed;
  bottom: 30px;
  right: 30px;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  z-index: 9999;
}

.floating-feedback-btn {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
  color: white;
  border: none;
  box-shadow: 0 4px 15px rgba(147, 51, 234, 0.4);
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.floating-feedback-btn:hover {
  transform: scale(1.05) translateY(-2px);
  box-shadow: 0 6px 20px rgba(147, 51, 234, 0.6);
}

.floating-feedback-btn .material-symbols-outlined {
  font-size: 28px;
}

.feedback-tooltip {
  background-color: #1e293b;
  color: #f8fafc;
  padding: 10px 16px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 12px;
  margin-right: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  position: relative;
  white-space: nowrap;
  border: 1px solid rgba(185, 121, 204, 0.3);
}

.tooltip-arrow {
  position: absolute;
  bottom: -6px;
  right: 20px;
  width: 12px;
  height: 12px;
  background-color: #1e293b;
  transform: rotate(45deg);
  border-right: 1px solid rgba(185, 121, 204, 0.3);
  border-bottom: 1px solid rgba(185, 121, 204, 0.3);
}

/* Animations */
.fade-bounce-enter-active {
  animation: bounce-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.fade-bounce-leave-active {
  animation: bounce-in 0.3s cubic-bezier(0.6, -0.28, 0.735, 0.045) reverse;
}
@keyframes bounce-in {
  0% {
    transform: scale(0.8) translateY(10px);
    opacity: 0;
  }
  50% {
    transform: scale(1.05) translateY(-2px);
    opacity: 1;
  }
  100% {
    transform: scale(1) translateY(0);
    opacity: 1;
  }
}

@media (max-width: 768px) {
  .floating-feedback-container {
    bottom: 20px;
    right: 20px;
  }
  .floating-feedback-btn {
    width: 50px;
    height: 50px;
  }
  .floating-feedback-btn .material-symbols-outlined {
    font-size: 24px;
  }
}
</style>
