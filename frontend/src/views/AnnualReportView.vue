<template>
  <div class="h-full flex flex-col bg-slate-900">
    <div class="bg-slate-800 shadow-sm border-b border-slate-700 px-6 py-4 flex justify-between items-center z-10">
      <div class="flex items-center gap-4">
        <button @click="$router.back()" class="text-slate-400 hover:text-slate-200 transition-colors">
          <span class="text-2xl">&larr;</span>
        </button>
        <h1 class="text-xl font-bold text-slate-100">Archived Annual Report <span v-if="report" class="text-purple-400">#{{ report.id }} (FY {{ report.fiscal_year }})</span></h1>
      </div>
      <div>
      </div>
    </div>

    <div v-if="loading" class="flex-1 flex items-center justify-center">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500"></div>
    </div>

    <div v-else-if="error" class="flex-1 flex items-center justify-center">
      <div class="bg-red-900/50 text-red-400 p-6 rounded-lg max-w-md text-center border border-red-800">
        <p class="text-lg font-semibold">{{ error }}</p>
        <button @click="$router.back()" class="mt-4 px-4 py-2 bg-red-800/50 rounded hover:bg-red-700/50 transition-colors">Go Back</button>
      </div>
    </div>

    <div v-else class="flex-1 overflow-auto p-4 md:p-8 flex justify-center bg-slate-900">
      <!-- The inner HTML of the archived report is rendered here. 
           We use an iframe to isolate its styles from the admin dashboard -->
      <iframe ref="reportFrame" class="w-full max-w-7xl bg-slate-900 shadow-2xl min-h-screen border-0" @load="resizeIframe"></iframe>
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
      
      doc.write(`
        <!DOCTYPE html>
        <html>
          <head>
            ${styles}
            <style>
              .toolbar { display: none !important; }
            </style>
          </head>
          <body style="background: #0f172a; padding: 2rem;">
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
