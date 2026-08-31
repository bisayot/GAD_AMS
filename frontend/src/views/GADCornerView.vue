<template>
  <div class="gad-corner text-white font-body pt-32" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); min-height: 100vh;">
    <!-- Formal Header -->
    <section class="py-20 px-12 text-center">
      <div class="max-w-screen-2xl mx-auto space-y-4">
        <h1 class="text-5xl font-headline font-black text-white tracking-tight">GAD Corner</h1>
        <p class="text-lg text-slate-300 max-w-3xl mx-auto leading-relaxed">
          Stay informed on the latest updates, activities, and achievements of the Gender and Development Office. Explore our public disclosures.
        </p>
      </div>
    </section>

    <!-- News & IEC Materials Section -->
    <section class="py-16 px-12 ">
      <div class="max-w-7xl mx-auto space-y-12">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
          <div class="space-y-4">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-white font-label text-xs font-bold uppercase tracking-widest">Public Information</span>
            <h2 class="text-4xl font-headline font-extrabold text-white tracking-tight">News & IEC Materials</h2>
            <p class="text-slate-300 text-lg max-w-lg leading-relaxed">
              Stay updated with the latest news, announcements, and Information, Education, and Communication (IEC) materials from the GAD Office.
            </p>
          </div>
          <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
            <select v-model="filterNewsCategory" class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-purple-500 outline-none">
              <option value="All" class="bg-[#1a1a2e]">All Categories</option>
              <option value="News" class="bg-[#1a1a2e]">News</option>
              <option value="IEC" class="bg-[#1a1a2e]">IEC Materials</option>
            </select>
            <div class="relative w-full sm:w-64">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
              <input v-model="searchNewsQuery" class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-purple-500 text-white placeholder:text-slate-500 shadow-sm" placeholder="Search news and IEC materials..." type="text"/>
            </div>
          </div>
        </div>

        <div v-if="loadingNewsIec" class="text-center py-8 text-slate-400">Loading updates...</div>
        <div v-else-if="filteredNewsIecItems.length === 0" class="text-center py-8 text-slate-400">No news or IEC materials found.</div>
        
        <div v-else class="mb-20">
          <!-- Massive Tag Header -->
          <div v-if="activeTag" class="mb-10">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-headline font-black text-white mb-6 tracking-tight flex items-center gap-2">
              <span class="text-purple-500">#</span>{{ activeTag }}
            </h1>
            <div class="h-px w-full bg-white/20"></div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="item in filteredNewsIecItems" :key="item.id" @click="openNewsModal(item)" class="group cursor-pointer bg-white/5 rounded-2xl border border-white/10 hover:shadow-xl hover:border-purple-500/50 transition-all duration-300 overflow-hidden flex flex-col h-full">
            <div class="p-6 pb-4">
              <h3 class="font-headline font-bold text-xl mb-3 text-white group-hover:text-purple-400 transition-colors line-clamp-2">{{ item.title }}</h3>
              <span class="flex items-center gap-1 font-label text-xs text-slate-400">
                <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                {{ new Date(item.created_at).toLocaleDateString() }}
              </span>
            </div>
            
            <div class="relative h-48 w-full bg-[#1a1a2e] border-y border-white/10 overflow-hidden shrink-0">
              <template v-if="parseImages(item.image_path).length > 0">
                <img v-for="(img, idx) in parseImages(item.image_path)" :key="idx" 
                     :src="`${apiBaseUrl}files/news-iec/${img}`" 
                     class="absolute inset-0 object-cover w-full h-full group-hover:scale-105 transition-all duration-1000"
                     :class="{'opacity-100 z-10': idx === (globalTick % parseImages(item.image_path).length), 'opacity-0 z-0': idx !== (globalTick % parseImages(item.image_path).length)}" />
              </template>
              <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#1a1a2e] to-[#16213e]">
                <span class="material-symbols-outlined text-4xl text-white/20">newspaper</span>
              </div>
              <div class="absolute top-4 right-4 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest backdrop-blur-md z-20"
                   :class="item.category === 'News' ? 'bg-blue-900/50 text-blue-200 border border-blue-500/30' : 'bg-emerald-900/50 text-emerald-200 border border-emerald-500/30'">
                {{ item.category }}
              </div>
            </div>
            
            <div class="p-6 pt-5 flex flex-col flex-grow">
              <p class="text-sm text-slate-300 leading-relaxed line-clamp-3 mb-4" v-html="linkify(item.description)"></p>
              
              <!-- Tags directly below description -->
              <div v-if="item.tags" class="mt-auto pt-4 border-t border-white/5 flex flex-wrap gap-2">
                <button v-for="tag in item.tags.split(',').filter(t => t.trim())" :key="tag" @click.stop="searchNewsQuery = tag.trim(); document.getElementById('news-section').scrollIntoView({behavior:'smooth'})" class="text-[11px] font-label font-bold text-purple-400 bg-purple-900/20 hover:bg-purple-900/40 px-2.5 py-1 rounded-full transition-colors z-30 relative">
                  #{{ tag.trim() }}
                </button>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </section>


    <!-- Accomplishment Reports Section -->
    <section class="py-16 px-12 border-t border-white/10">
      <div class="max-w-7xl mx-auto space-y-12">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
          <div class="space-y-4">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-white font-label text-xs font-bold uppercase tracking-widest">Public Disclosures</span>
            <h2 class="text-4xl font-headline font-extrabold text-white tracking-tight">Accomplishment Reports</h2>
            <p class="text-slate-300 text-lg max-w-lg leading-relaxed">
              Review the university's verified gender-responsive activities and archived annual reports.
            </p>
          </div>
          <div class="relative w-full md:max-w-xs shrink-0">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input v-model="searchReportsQuery" class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-purple-500 text-white placeholder:text-slate-500 shadow-sm" placeholder="Search reports..." type="text"/>
          </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-16">
          <!-- Verified & Archived Reports -->
          <div>
            <div class="flex items-center gap-4 mb-8">
              <h3 class="text-2xl font-headline font-bold text-white">Verified & Archived Reports</h3>
              <div class="h-px flex-grow bg-white/10"></div>
            </div>
            <div v-if="loadingReports" class="text-center py-8 text-slate-400">Loading reports...</div>
            <div v-else-if="filteredVerifiedReports.length === 0" class="text-center py-8 text-slate-400">No reports found.</div>
            <div v-else class="space-y-4">
              <div v-for="report in filteredVerifiedReports" :key="report.id" class="group bg-white/5 p-6 rounded-xl border border-white/10 hover:shadow-xl transition-all duration-300">
                <div class="flex justify-between items-start mb-4">
                  <div class="w-12 h-12 academic-gradient rounded-lg flex items-center justify-center text-white shadow-md">
                    <span class="material-symbols-outlined">description</span>
                  </div>
                  <span class="material-symbols-outlined text-outline/40 group-hover:text-primary transition-colors">picture_as_pdf</span>
                </div>
                <h4 class="font-headline font-bold text-lg mb-2 text-white group-hover:text-purple-400 transition-colors">{{ report.title }}</h4>
                <div class="flex flex-wrap gap-4 text-xs font-label text-slate-400 mb-6">
                  <span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">tag</span> {{ report.control }}</span>
                  <span v-if="report.office" class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">business</span> {{ report.office }}</span>
                  <span v-if="report.date" class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">calendar_month</span> {{ report.date }}</span>
                </div>
                <div class="flex items-center justify-between mt-auto">
                  <span class="text-xs font-label uppercase tracking-widest font-bold text-secondary">Accomplishment Report</span>
                  <button @click="viewPdf(report)" class="text-primary font-label text-sm font-bold flex items-center gap-1 hover:underline underline-offset-4 decoration-2">
                    <span class="material-symbols-outlined text-sm">visibility</span> View File
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Archived Reports -->
          <div>
            <div class="flex items-center gap-4 mb-8">
              <h3 class="text-2xl font-headline font-bold text-white">Archived Annual Reports</h3>
              <div class="h-px flex-grow bg-white/10"></div>
            </div>
            <div v-if="loadingArchives" class="text-center py-8 text-slate-400">Loading archives...</div>
            <div v-else-if="filteredArchivedReports.length === 0" class="text-center py-8 text-slate-400">No archived reports found.</div>
            <div v-else class="space-y-4">
              <div v-for="archive in filteredArchivedReports" :key="archive.id" class="group bg-white/5 p-6 rounded-xl border border-white/10 hover:shadow-xl transition-all duration-300">
                <div class="flex justify-between items-start mb-4">
                  <div class="w-12 h-12 academic-gradient rounded-lg flex items-center justify-center text-white shadow-md">
                    <span class="material-symbols-outlined">folder_open</span>
                  </div>
                  <span class="material-symbols-outlined text-outline/40 group-hover:text-primary transition-colors">html</span>
                </div>
                <h4 class="font-headline font-bold text-lg mb-1 text-white group-hover:text-purple-400 transition-colors">FY {{ archive.fiscal_year }} Annual GAD Report</h4>
                <p class="text-xs text-slate-400 mb-6">Archived on {{ new Date(archive.created_at).toLocaleDateString() }}</p>
                
                <div class="flex items-center justify-between mt-auto">
                  <span class="text-xs font-label uppercase tracking-widest font-bold text-secondary">Annual Report</span>
                  <button @click="viewHtmlReport(archive)" class="text-primary font-label text-sm font-bold flex items-center gap-1 hover:underline underline-offset-4 decoration-2">
                    <span class="material-symbols-outlined text-sm">visibility</span> View File
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>



    <!-- Modals -->
    <!-- Modals -->
    <PdfPreviewModal :isOpen="isPdfPreviewOpen" :fileUrl="currentPdfUrl" @close="isPdfPreviewOpen = false" />
    <HtmlPreviewModal :isOpen="isHtmlPreviewOpen" :htmlContent="currentHtmlContent" :title="currentHtmlTitle" :loading="isHtmlLoading" @close="isHtmlPreviewOpen = false" />
  </div>
</template>

<script setup>

const socialLinks = [
  { icon: 'public' },
  { icon: 'share' },
  { icon: 'rss_feed' }
];

import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../api';
import Swal from 'sweetalert2';
import PdfPreviewModal from '../components/PdfPreviewModal.vue';
import HtmlPreviewModal from '../components/HtmlPreviewModal.vue';

const route = useRoute();
const router = useRouter();
const isPdfPreviewOpen = ref(false);
const currentPdfUrl = ref('');

const isHtmlPreviewOpen = ref(false);
const isHtmlLoading = ref(false);
const currentHtmlContent = ref('');
const currentHtmlTitle = ref('');

const searchReportsQuery = ref('');
const verifiedReports = ref([]);
const archivedReports = ref([]);
const loadingReports = ref(true);
const loadingArchives = ref(true);

const newsIecItems = ref([]);
const searchNewsQuery = ref('');
const activeTag = ref('');
const filterNewsCategory = ref('All');

watch(searchNewsQuery, (newVal) => {
  if (newVal !== activeTag.value) {
    activeTag.value = '';
  }
});

const parseImages = (val) => {
  if (!val) return [];
  if (Array.isArray(val)) return val;
  if (typeof val === 'string') {
    try {
      let parsed = JSON.parse(val);
      if (typeof parsed === 'string') {
        parsed = JSON.parse(parsed);
      }
      if (Array.isArray(parsed)) return parsed;
    } catch(e) {
      // Fallback below
    }
  }
  return [val];
};

const filteredNewsIecItems = computed(() => {
  let items = newsIecItems.value;
  if (filterNewsCategory.value !== 'All') {
    items = items.filter(item => item.category === filterNewsCategory.value);
  }
  if (searchNewsQuery.value) {
    const q = searchNewsQuery.value.toLowerCase();
    items = items.filter(item => 
      item.title?.toLowerCase().includes(q) || 
      item.description?.toLowerCase().includes(q) ||
      item.tags?.toLowerCase().includes(q)
    );
  }
  return items;
});

const linkify = (text) => {
  if (!text) return '';
  const urlRegex = /(https?:\/\/[^\s]+|(?:www\.)?[a-zA-Z0-9-]+\.(?:com|org|net|edu|gov|ph|io|co|info|me)(?:\/[^\s]*)?)/ig;
  return text.replace(urlRegex, function(url) {
    let href = url;
    if (!/^https?:\/\//i.test(href)) {
      href = 'https://' + href;
    }
    return `<a href="${href}" target="_blank" class="text-blue-400 hover:underline break-all">${url}</a>`;
  });
};

const openNewsModal = (item) => {
  router.push(`/gad-corner/${item.id}`);
};







const globalTick = ref(0);
let tickInterval;

const loadingNewsIec = ref(true);
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL 
  ? (import.meta.env.VITE_API_BASE_URL.endsWith('/') ? import.meta.env.VITE_API_BASE_URL : import.meta.env.VITE_API_BASE_URL + '/') 
  : 'http://localhost:8080/api/';

const filteredVerifiedReports = computed(() => {
  if (!searchReportsQuery.value) return verifiedReports.value;
  const q = searchReportsQuery.value.toLowerCase();
  return verifiedReports.value.filter(r => 
    r.title?.toLowerCase().includes(q) || 
    r.control?.toLowerCase().includes(q) || 
    r.office?.toLowerCase().includes(q)
  );
});

const filteredArchivedReports = computed(() => {
  if (!searchReportsQuery.value) return archivedReports.value;
  const q = searchReportsQuery.value.toLowerCase();
  return archivedReports.value.filter(r => 
    String(r.fiscal_year).includes(q)
  );
});

const fetchAccomplishmentReports = async () => {
  try {
    const [res1, res2] = await Promise.all([
      api.get('activity-reports').catch(() => ({ data: { success: false } })),
      api.get('archives').catch(() => ({ data: { success: false } }))
    ]);
    
    let combined = [];
    if (res1.data && res1.data.success) {
      combined = [...combined, ...res1.data.data.filter(r => r.status === 'Verified').map(r => ({ ...r, is_archived: 0 }))];
    }
    if (res2.data && res2.data.success) {
      combined = [...combined, ...res2.data.data.filter(r => r.type === 'report').map(r => ({ ...r, is_archived: 1 }))];
    }
    
    verifiedReports.value = combined.sort((a, b) => new Date(b.date) - new Date(a.date)).slice(0, 5);
  } catch (err) {
    console.error('Failed to fetch accomplishment reports:', err);
  } finally {
    loadingReports.value = false;
  }
};

const fetchArchivedReports = async () => {
  try {
    const res = await api.get('annual-reports/archive');
    if (res.data && res.data.success) {
      archivedReports.value = res.data.data.slice(0, 5);
    }
  } catch (err) {
    console.error('Failed to fetch archives:', err);
  } finally {
    loadingArchives.value = false;
  }
};

const fetchNewsIec = async () => {
  try {
    const res = await api.get('news-iec');
    if (res.data && res.data.success) {
      newsIecItems.value = res.data.data;
      
      if (route.query.post) {
        router.push(`/gad-corner/${route.query.post}`);
      }
    }
  } catch (err) {
    console.error("Failed to fetch news & iec:", err);
  } finally {
    loadingNewsIec.value = false;
  }
};

onMounted(() => {
  if (route.query.tag) {
    searchNewsQuery.value = route.query.tag;
    activeTag.value = route.query.tag;
  }
  tickInterval = setInterval(() => {
    globalTick.value++;
  }, 3000);
  fetchAccomplishmentReports();
  fetchArchivedReports();
  fetchNewsIec();
});

const viewPdf = (report) => {
  try {
    if (report.attachment) {
      const attachments = JSON.parse(report.attachment);
      if (attachments && attachments.length > 0) {
        const folder = report.is_archived ? 'archived' : 'drafts';
        currentPdfUrl.value = `${import.meta.env.VITE_API_BASE_URL ? (import.meta.env.VITE_API_BASE_URL.endsWith('/') ? import.meta.env.VITE_API_BASE_URL : import.meta.env.VITE_API_BASE_URL + '/') : 'http://localhost:8080/api/'}files/${folder}/${attachments[0]}`;
        isPdfPreviewOpen.value = true;
        return;
      }
    }
    // Fallback if no attachment exists
    Swal.fire({ icon: 'info', title: 'Not Available', text: 'There is no PDF attachment available for this report.' });
  } catch (err) {
    console.error('Failed to parse attachment:', err);
    Swal.fire({ icon: 'error', title: 'Error', text: 'Could not open the file.' });
  }
};

const viewHtmlReport = async (archive) => {
  currentHtmlTitle.value = `FY ${archive.fiscal_year} Annual GAD Report`;
  currentHtmlContent.value = '';
  isHtmlPreviewOpen.value = true;
  isHtmlLoading.value = true;
  
  try {
    const res = await api.get(`annual-reports/archive/${archive.id}`);
    if (res.data && res.data.success) {
      currentHtmlContent.value = res.data.data.html_content;
    } else {
      isHtmlPreviewOpen.value = false;
      Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load the document.' });
    }
  } catch (err) {
    isHtmlPreviewOpen.value = false;
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to fetch the archived report.' });
  } finally {
    isHtmlLoading.value = false;
  }
};

onUnmounted(() => {
  if (tickInterval) clearInterval(tickInterval);
});

</script>

<style scoped>
.academic-gradient {
  background: linear-gradient(135deg, #422b68 0%, #5a4281 100%);
}
</style>
