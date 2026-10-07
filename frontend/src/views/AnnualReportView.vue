<template>
  <div class="h-full flex flex-col viewer-container min-h-screen">
    <div class="viewer-header shadow-sm border-b px-6 py-4 flex justify-between items-center z-10">
      <div class="flex items-center gap-4">
        <button @click="$router.back()" class="viewer-back-btn transition-colors">
          <span class="text-2xl leading-none">&larr;</span>
        </button>
        <h1 class="text-xl font-bold viewer-title">
          Archived Annual Report 
          <span v-if="report" class="text-purple-600 dark:text-purple-400">#{{ report.id }} (FY {{ report.fiscal_year }})</span>
        </h1>
      </div>
      <div>
      </div>
    </div>

    <div v-if="loading" class="flex-1 flex items-center justify-center">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500"></div>
    </div>

    <div v-else-if="error" class="flex-1 flex items-center justify-center">
      <div class="bg-red-50 dark:bg-red-900/50 text-red-600 dark:text-red-400 p-6 rounded-lg max-w-md text-center border border-red-200 dark:border-red-800">
        <p class="text-lg font-semibold">{{ error }}</p>
        <button @click="$router.back()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">Go Back</button>
      </div>
    </div>

    <div v-else class="flex-1 overflow-auto p-4 md:p-8 flex justify-center viewer-frame-wrapper">
      <!-- The inner HTML of the archived report is rendered here. 
           We use an iframe to isolate its styles from the admin dashboard -->
      <iframe ref="reportFrame" class="w-full max-w-7xl shadow-xl min-h-screen border-0 rounded-xl" @load="resizeIframe"></iframe>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../api';

const route = useRoute();
const loading = ref(true);
const error = ref(null);
const report = ref(null);
const reportFrame = ref(null);

const fetchReport = async () => {
  try {
    const response = await api.get(`annual-reports/archive/${route.params.id}`);
    if (response.data && response.data.success) {
      report.value = response.data.data;
      injectHtml();
    } else {
      error.value = 'Report not found.';
    }
  } catch (err) {
    console.error('Error fetching report:', err);
    error.value = 'Failed to load report.';
  } finally {
    loading.value = false;
  }
};

const injectHtml = () => {
  setTimeout(() => {
    if (reportFrame.value && report.value) {
      const doc = reportFrame.value.contentWindow.document;
      doc.open();
      
      let styles = '';
      document.querySelectorAll('style, link[rel="stylesheet"]').forEach(el => {
        styles += el.outerHTML;
      });
      
      const isDark = document.documentElement.classList.contains('dark');
      const bodyBg = isDark ? '#1a1a2e' : '#ffffff';
      const htmlClass = isDark ? 'class="dark"' : '';

      doc.write(`
        <!DOCTYPE html>
        <html ${htmlClass}>
          <head>
            ${styles}
            <style>
              .toolbar { display: none !important; }
            </style>
          </head>
          <body style="background: ${bodyBg}; padding: 2rem;">
            ${report.value.html_content}
          </body>
        </html>
      `);
      doc.close();
    }
  }, 100);
};

const resizeIframe = () => {
  if (reportFrame.value) {
    try {
      reportFrame.value.style.height = reportFrame.value.contentWindow.document.documentElement.scrollHeight + 'px';
    } catch(e){}
  }
};

onMounted(() => {
  fetchReport();
});
</script>

<style scoped>
.viewer-container {
  background: #ffffff;
}
.viewer-header {
  background: #ffffff;
  border-color: #e2e8f0;
}
.viewer-title {
  color: #0f172a;
}
.viewer-back-btn {
  color: #475569;
}
.viewer-back-btn:hover {
  color: #0f172a;
}
.viewer-frame-wrapper {
  background: #f8fafc;
}
</style>

<style>
html.dark .viewer-container,
.dark .viewer-container {
  background: #ffffff !important;
}
html.dark .viewer-header,
.dark .viewer-header {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%) !important;
  border-color: rgba(185, 121, 204, 0.25) !important;
}
html.dark .viewer-title,
.dark .viewer-title {
  color: #ffffff !important;
}
html.dark .viewer-back-btn,
.dark .viewer-back-btn {
  color: #cbd5e1 !important;
}
html.dark .viewer-back-btn:hover,
.dark .viewer-back-btn:hover {
  color: #ffffff !important;
}
html.dark .viewer-frame-wrapper,
.dark .viewer-frame-wrapper {
  background: rgba(0, 0, 0, 0.4) !important;
}
</style>
