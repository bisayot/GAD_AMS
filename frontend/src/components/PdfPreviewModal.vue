<template>
  <div v-if="isOpen" class="pdf-modal-overlay" @click.self="close">
    <div class="pdf-modal-content">
      <!-- Glow effect behind modal -->
      <div class="modal-glow-effect"></div>
      
      <div class="pdf-modal-header">
        <div class="header-left">
          <div class="icon-wrapper">
            <span class="material-symbols-outlined">description</span>
          </div>
          <div class="title-wrapper">
            <h3 class="pdf-modal-title">Document Preview</h3>
            <p class="pdf-modal-subtitle">Secure PDF Viewer</p>
          </div>
        </div>
        <div class="pdf-modal-actions">
          <button class="pdf-btn action-expand" @click="expandToNewTab" title="Open in new tab">
            <span class="material-symbols-outlined">open_in_new</span>
            <span class="btn-tooltip">Open in Tab</span>
          </button>
          <div class="divider"></div>
          <button class="pdf-btn action-close" @click="close" title="Close">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
      </div>
      <div class="pdf-modal-body">
        <div class="loading-state">
          <div class="spinner"></div>
          <p>Loading document...</p>
        </div>
        <iframe :src="fileUrl" class="pdf-iframe" title="PDF Preview" @load="onIframeLoad"></iframe>
      </div>
    </div>
  </div>
</template>

<script setup>


import { ref, watch } from 'vue';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  fileUrl: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['close']);
const isLoaded = ref(false);

watch(() => props.fileUrl, () => {
  isLoaded.value = false;
});

const onIframeLoad = () => {
  isLoaded.value = true;
};

const close = () => {
  emit('close');
};

const expandToNewTab = () => {
  if (props.fileUrl) {
    window.open(props.fileUrl, '_blank');
  }
};
</script>

<style scoped>
.pdf-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at center, rgba(15, 23, 42, 0.8) 0%, rgba(0, 0, 0, 0.95) 100%);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  perspective: 1000px;
}

.pdf-modal-content {
  position: relative;
  background: rgba(15, 23, 42, 0.6);
  border: 1px solid rgba(185, 121, 204, 0.4);
  border-radius: 24px;
  width: 95%;
  max-width: 1000px;
  height: 85vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 0 40px rgba(185, 121, 204, 0.15), 0 30px 60px -20px rgba(0, 0, 0, 0.8);
  overflow: visible;
  animation: modal-float-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  backdrop-filter: blur(20px);
}

.modal-glow-effect {
  position: absolute;
  top: -2px; left: -2px; right: -2px; bottom: -2px;
  background: linear-gradient(45deg, #990dd1, #b979cc, #3b82f6, #990dd1);
  border-radius: 26px;
  z-index: -1;
  opacity: 0.3;
  filter: blur(15px);
  animation: glow-pulse 4s infinite alternate;
}

@keyframes glow-pulse {
  0% { opacity: 0.2; transform: scale(0.99); filter: blur(10px); }
  100% { opacity: 0.5; transform: scale(1.01); filter: blur(20px); }
}

@keyframes modal-float-in {
  0% { opacity: 0; transform: translateY(30px) scale(0.95) rotateX(5deg); }
  100% { opacity: 1; transform: translateY(0) scale(1) rotateX(0); }
}

.pdf-modal-header {
  background: linear-gradient(90deg, rgba(20, 30, 50, 0.8) 0%, rgba(30, 20, 50, 0.8) 100%);
  padding: 16px 24px;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top-left-radius: 24px;
  border-top-right-radius: 24px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.05);
  z-index: 10;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 16px;
}

.icon-wrapper {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 15px rgba(153, 13, 209, 0.4);
}

.icon-wrapper span {
  font-size: 20px;
  color: white;
}

.title-wrapper {
  display: flex;
  flex-direction: column;
}

.pdf-modal-title {
  font-size: 16px;
  font-weight: 800;
  margin: 0;
  letter-spacing: 0.5px;
  background: linear-gradient(to right, #ffffff, #e2e8f0);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.pdf-modal-subtitle {
  margin: 0;
  font-size: 11px;
  color: #94a3b8;
  font-weight: 500;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}

.pdf-modal-actions {
  display: flex;
  align-items: center;
  gap: 12px;
  background: rgba(0, 0, 0, 0.3);
  padding: 6px 12px;
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.05);
}

.divider {
  width: 1px;
  height: 16px;
  background: rgba(255, 255, 255, 0.1);
}

.pdf-btn {
  background: transparent;
  border: none;
  color: #cbd5e1;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
}

.pdf-btn span.material-symbols-outlined {
  font-size: 18px;
}

.pdf-btn:hover {
  background: rgba(255, 255, 255, 0.1);
  color: white;
  transform: translateY(-2px);
}

.action-expand:hover {
  color: #b979cc;
  background: rgba(185, 121, 204, 0.15);
}

.action-close:hover {
  color: #ef4444;
  background: rgba(239, 68, 68, 0.15);
}

.btn-tooltip {
  position: absolute;
  top: -30px;
  background: rgba(0, 0, 0, 0.8);
  color: white;
  font-size: 10px;
  padding: 4px 8px;
  border-radius: 4px;
  pointer-events: none;
  opacity: 0;
  transform: translateY(5px);
  transition: all 0.2s ease;
  white-space: nowrap;
}

.pdf-btn:hover .btn-tooltip {
  opacity: 1;
  transform: translateY(0);
}

.pdf-modal-body {
  flex: 1;
  background: #0f172a;
  overflow: hidden;
  position: relative;
  border-bottom-left-radius: 24px;
  border-bottom-right-radius: 24px;
}

.loading-state {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 16px;
  color: #94a3b8;
  z-index: 1;
  pointer-events: none;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 3px solid rgba(185, 121, 204, 0.2);
  border-top-color: #b979cc;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

.pdf-iframe {
  width: 100%;
  height: 100%;
  border: none;
  position: relative;
  z-index: 2;
  opacity: 0;
  animation: fade-in 0.5s ease forwards;
}

@keyframes fade-in {
  to { opacity: 1; }
}
</style>
