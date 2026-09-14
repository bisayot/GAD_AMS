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

    <!-- Bulletin Section -->
    <section class="py-16 px-12 ">
      <div class="max-w-7xl mx-auto space-y-12">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
          <div class="space-y-4">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-white font-label text-xs font-bold uppercase tracking-widest">Public Information</span>
            <h2 class="text-4xl font-headline font-extrabold text-white tracking-tight">Bulletin</h2>
            <p class="text-slate-300 text-lg max-w-lg leading-relaxed">
              Stay updated with the latest news, announcements, and Information, Education, and Communication (IEC) materials from the GAD Office.
            </p>
          </div>
          <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
            <div class="relative w-full sm:w-48 shrink-0">
              <select v-model="filterNewsCategory" class="w-full appearance-none bg-white/5 border border-white/10 rounded-xl px-4 py-3 pr-10 text-white focus:ring-2 focus:ring-purple-500 outline-none cursor-pointer">
                <option value="All" class="bg-[#1a1a2e]">All Categories</option>
                <option value="News" class="bg-[#1a1a2e]">News</option>
                <option value="IEC" class="bg-[#1a1a2e]">IEC Materials</option>
                <option value="Announcement" class="bg-[#1a1a2e]">Announcements</option>
              </select>
              <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-white/70 pointer-events-none text-xl">keyboard_arrow_down</span>
            </div>
            <div class="relative w-full sm:w-64">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
              <input v-model="searchNewsQuery" class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-purple-500 text-white placeholder:text-slate-500 shadow-sm" placeholder="Search bulletin..." type="text"/>
            </div>
          </div>
        </div>

        <div v-if="loadingNewsIec" class="text-center py-8 text-slate-400">Loading updates...</div>
        <div v-else-if="filteredNewsIecItems.length === 0" class="text-center py-8 text-slate-400">No bulletin items found.</div>
        
        <div v-else class="mb-8">
          <!-- Massive Tag Header -->
          <div v-if="activeTag" class="mb-10">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-headline font-black text-white mb-6 tracking-tight flex items-center gap-2">
              <span class="text-purple-500">#</span>{{ activeTag }}
            </h1>
            <div class="h-px w-full bg-white/20"></div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div v-for="item in filteredNewsIecItems" :key="item.id" @click="openNewsModal(item)" class="group cursor-pointer bg-white rounded-2xl border border-slate-100 hover:shadow-xl hover:border-slate-200 transition-all duration-300 overflow-hidden flex flex-col h-full">
              <div class="relative h-64 w-full bg-slate-50 overflow-hidden shrink-0">
                <template v-if="parseImages(item.image_path).length > 0">
                  <img v-for="(img, idx) in parseImages(item.image_path)" :key="idx" 
                       :src="`${apiBaseUrl}files/news-iec/${img}`" 
                       class="absolute inset-0 object-cover w-full h-full group-hover:scale-105 transition-transform duration-700 ease-out"
                       :class="{'opacity-100 z-10': idx === (globalTick % parseImages(item.image_path).length), 'opacity-0 z-0': idx !== (globalTick % parseImages(item.image_path).length)}" />
                </template>
                <div v-else class="w-full h-full flex items-center justify-center bg-slate-50">
                  <span class="material-symbols-outlined text-5xl text-slate-300">image</span>
                </div>
                <div class="absolute top-4 right-4 px-3 py-1 rounded bg-white/90 backdrop-blur-sm text-[10px] font-bold uppercase tracking-widest shadow-sm z-20"
                     :class="item.category === 'News' ? 'text-blue-600' : item.category === 'IEC' ? 'text-emerald-600' : 'text-orange-500'">
                  {{ item.category }}
                </div>
              </div>
              
              <div class="p-6 md:px-8 md:py-6 flex flex-col flex-grow items-center text-center">
                <h3 class="font-headline font-bold text-xl md:text-2xl text-slate-800 group-hover:text-purple-700 transition-colors line-clamp-2 leading-snug mb-1">
                  {{ item.title }}
                </h3>
                
                <span class="font-label text-sm text-slate-500 mb-4">
                  {{ new Date(item.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                </span>
                
                <p class="text-sm md:text-base text-slate-600 leading-relaxed line-clamp-3 mb-6" v-html="linkify(item.description)"></p>
                
                <div v-if="item.tags" class="mt-auto pt-4 flex flex-wrap justify-center gap-2 w-full">
                  <button v-for="tag in item.tags.split(',').filter(t => t.trim())" :key="tag" @click.stop="searchNewsQuery = tag.trim(); document.getElementById('news-section').scrollIntoView({behavior:'smooth'})" class="text-xs font-label font-medium text-slate-500 hover:text-purple-600 bg-slate-50 hover:bg-purple-50 px-3 py-1 rounded-full transition-colors z-30 relative">
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
    <section class="pt-8 pb-16 px-12 border-t border-transparent">
      <div class="max-w-7xl mx-auto space-y-12">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
          <div class="space-y-4">
            <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-white font-label text-xs font-bold uppercase tracking-widest">Public Disclosures</span>
            <h2 class="text-4xl font-headline font-extrabold text-white tracking-tight">Accomplishment Reports</h2>
            <p class="text-slate-300 text-lg max-w-lg leading-relaxed">
              Review the university's verified gender-responsive activities and archived annual reports.
            </p>
          </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-16">
          <!-- Verified Accomplishment Reports -->
          <div>
            <div class="flex items-center gap-4 mb-8">
              <h3 class="text-2xl font-headline font-bold text-white">Verified Accomplishment Reports</h3>
              <div class="h-px flex-grow bg-white/10"></div>
            </div>
            
            <div v-if="loadingReports" class="text-center py-8 text-slate-400">Loading reports...</div>
            <div v-else-if="reportsByYear.length === 0" class="text-center py-8 text-slate-400">No reports found.</div>
            <div v-else class="space-y-8">
              <div v-for="group in reportsByYear" :key="'ver_'+group.year" class="book-container group relative w-full h-[450px]" :class="{ 'is-open': openBooks['ver_' + group.year] }">
                <div class="book relative w-full h-full pointer-events-none">
                  <!-- Cover -->
                  <div class="book-cover absolute inset-0 bg-white rounded-r-xl border-2 border-black shadow-[4px_0_15px_rgba(0,0,0,0.05)] flex flex-col items-center justify-center p-8 z-20 pointer-events-auto cursor-pointer" @click="toggleBook('ver_' + group.year)" style="border: 2px solid black !important;">
                    <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-slate-300 to-transparent rounded-l-xl" style="border-right: 2px solid black;"></div>
                    <img src="/images/logo.png" alt="Logo" class="w-28 mb-8 object-contain drop-shadow-md ml-4 group-hover:scale-105 transition-transform" />
                    <h4 class="font-headline font-black text-center text-slate-800 text-2xl mb-2 ml-4 leading-snug">{{ group.year }} Accomplishment Reports</h4>
                    <div class="mt-4 px-4 py-1.5 bg-emerald-50 rounded-full text-xs font-label uppercase tracking-widest font-bold text-emerald-700 border border-emerald-100 ml-4 mb-8">
                      Verified Collection
                    </div>
                    <div class="mt-auto ml-4 text-[11px] font-label font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5 opacity-70 group-hover:opacity-100 transition-opacity">
                      <span class="material-symbols-outlined text-[14px]">touch_app</span> Click to view
                    </div>
                  </div>
                  <!-- Inside Page -->
                  <div class="book-page absolute inset-0 bg-slate-50 rounded-r-xl border-2 border-black shadow-inner p-6 flex flex-col z-10 pointer-events-auto" style="border: 2px solid black !important;">
                    <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-slate-300 to-transparent rounded-l-xl" style="border-right: 2px solid black;"></div>
                    <div class="pl-6 flex flex-col h-full">
                      <div class="flex items-center gap-3 mb-4 shrink-0">
                        <div class="relative flex-grow">
                          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                          <input type="text" v-model="verifiedFilters[group.year]" placeholder="Search records..." class="w-full pl-9 pr-14 py-2.5 bg-white border-2 border-black rounded-xl text-sm font-medium focus:ring-2 focus:ring-purple-500 outline-none placeholder:text-slate-500 shadow-[0_2px_10px_rgba(0,0,0,0.02)] transition-shadow" style="border: 2px solid black !important; color: black !important;" />
                          <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-md" style="color: black !important;">{{ group.reports.length }}</span>
                        </div>
                        <button @click="toggleBook('ver_' + group.year)" class="w-10 h-10 shrink-0 rounded-xl bg-black hover:bg-slate-800 border-2 border-black flex items-center justify-center text-white shadow-md transition-all" title="Close Book" style="background-color: black !important; color: white !important; border: 2px solid black !important;">
                          <span class="material-symbols-outlined text-[20px] font-bold" style="color: white !important;">close</span>
                        </button>
                      </div>
                      <div class="overflow-y-auto flex-grow pr-3 space-y-3 custom-scrollbar pb-4">
                        <div v-if="filterGroup(group.reports, verifiedFilters[group.year]).length === 0" class="text-slate-400 text-sm py-8 text-center flex flex-col items-center gap-2"><span class="material-symbols-outlined text-3xl opacity-50">search_off</span> No matches found.</div>
                        <div v-else v-for="report in filterGroup(group.reports, verifiedFilters[group.year])" :key="report.id" @click.stop="viewPdf(report)" class="bg-white p-3 rounded-xl border-2 border-black shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-md transition-all cursor-pointer group/item flex items-center gap-3" style="border: 2px solid black !important;">
                          <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center shrink-0 group-hover/item:bg-purple-100 transition-colors">
                            <span class="material-symbols-outlined text-purple-600 text-[20px]">description</span>
                          </div>
                          <div class="flex-grow min-w-0 flex flex-col">
                            <h5 class="font-bold text-sm text-black group-hover/item:text-purple-700 truncate transition-colors">{{ report.title }}</h5>
                            <div class="flex items-center gap-3 mt-0.5">
                              <span class="text-[10px] font-label font-bold tracking-wide text-slate-700 flex items-center gap-1 uppercase truncate"><span class="material-symbols-outlined text-[13px]">business</span>{{ report.office || 'N/A' }}</span>
                              <span class="text-[10px] font-label font-bold text-slate-700 flex items-center gap-1 uppercase shrink-0"><span class="material-symbols-outlined text-[13px]">tag</span>{{ report.control }}</span>
                            </div>
                          </div>
                          <div class="shrink-0 w-8 h-8 rounded-full bg-slate-50 group-hover/item:bg-purple-600 flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover/item:text-white transition-colors">arrow_forward</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Archived Annual Reports -->
          <div>
            <div class="flex items-center gap-4 mb-8">
              <h3 class="text-2xl font-headline font-bold text-white">Archived Annual Reports</h3>
              <div class="h-px flex-grow bg-white/10"></div>
            </div>
            
            <div v-if="loadingArchives" class="text-center py-8 text-slate-400">Loading archives...</div>
            <div v-else-if="archivesByYear.length === 0" class="text-center py-8 text-slate-400">No archives found.</div>
            <div v-else class="space-y-8">
              <div v-for="group in archivesByYear" :key="'arch_'+group.year" class="book-container group relative w-full h-[450px]" :class="{ 'is-open': openBooks['arch_' + group.year] }">
                <div class="book relative w-full h-full pointer-events-none">
                  <!-- Cover -->
                  <div class="book-cover absolute inset-0 bg-white rounded-r-xl border-2 border-black shadow-[4px_0_15px_rgba(0,0,0,0.05)] flex flex-col items-center justify-center p-8 z-20 pointer-events-auto cursor-pointer" @click="toggleBook('arch_' + group.year)" style="border: 2px solid black !important;">
                    <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-slate-300 to-transparent rounded-l-xl" style="border-right: 2px solid black;"></div>
                    <img src="/images/logo.png" alt="Logo" class="w-28 mb-8 object-contain drop-shadow-md ml-4 group-hover:scale-105 transition-transform" />
                    <h4 class="font-headline font-black text-center text-slate-800 text-2xl mb-2 ml-4 leading-snug">Archived Annual Reports</h4>
                    <div class="mt-4 px-4 py-1.5 bg-blue-50 rounded-full text-xs font-label uppercase tracking-widest font-bold text-blue-700 border border-blue-100 ml-4 mb-8">
                      Archive Collection
                    </div>
                    <div class="mt-auto ml-4 text-[11px] font-label font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5 opacity-70 group-hover:opacity-100 transition-opacity">
                      <span class="material-symbols-outlined text-[14px]">touch_app</span> Click to view
                    </div>
                  </div>
                  <!-- Inside Page -->
                  <div class="book-page absolute inset-0 bg-slate-50 rounded-r-xl border-2 border-black shadow-inner p-6 flex flex-col z-10 pointer-events-auto" style="border: 2px solid black !important;">
                    <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-slate-300 to-transparent rounded-l-xl" style="border-right: 2px solid black;"></div>
                    <div class="pl-6 flex flex-col h-full">
                      <div class="flex items-center gap-3 mb-4 shrink-0">
                        <div class="relative flex-grow">
                          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                          <input type="text" v-model="archiveFilters[group.year]" placeholder="Search records..." class="w-full pl-9 pr-14 py-2.5 bg-white border-2 border-black rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 outline-none placeholder:text-slate-500 shadow-[0_2px_10px_rgba(0,0,0,0.02)] transition-shadow" style="border: 2px solid black !important; color: black !important;" />
                          <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-md" style="color: black !important;">{{ group.reports.length }}</span>
                        </div>
                        <button @click="toggleBook('arch_' + group.year)" class="w-10 h-10 shrink-0 rounded-xl bg-black hover:bg-slate-800 border-2 border-black flex items-center justify-center text-white shadow-md transition-all" title="Close Book" style="background-color: black !important; color: white !important; border: 2px solid black !important;">
                          <span class="material-symbols-outlined text-[20px] font-bold" style="color: white !important;">close</span>
                        </button>
                      </div>
                      <div class="overflow-y-auto flex-grow pr-3 space-y-3 custom-scrollbar pb-4">
                        <div v-if="filterGroup(group.reports, archiveFilters[group.year]).length === 0" class="text-slate-400 text-sm py-8 text-center flex flex-col items-center gap-2"><span class="material-symbols-outlined text-3xl opacity-50">search_off</span> No matches found.</div>
                        <div v-else v-for="archive in filterGroup(group.reports, archiveFilters[group.year])" :key="archive.id" @click.stop="viewHtmlReport(archive)" class="bg-white p-3 rounded-xl border-2 border-black shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-md transition-all cursor-pointer group/item flex items-center gap-3" style="border: 2px solid black !important;">
                          <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center shrink-0 group-hover/item:bg-blue-100 transition-colors">
                            <span class="material-symbols-outlined text-blue-600 text-[20px]">history_edu</span>
                          </div>
                          <div class="flex-grow min-w-0 flex flex-col">
                            <h5 class="font-bold text-sm text-black group-hover/item:text-blue-700 truncate transition-colors">FY {{ archive.fiscal_year }} Annual GAD Report</h5>
                            <div class="flex items-center gap-3 mt-0.5">
                              <span class="text-[10px] font-label font-bold tracking-wide text-slate-700 flex items-center gap-1 uppercase truncate"><span class="material-symbols-outlined text-[13px]">schedule</span>Archived {{ new Date(archive.created_at).toLocaleDateString() }}</span>
                            </div>
                          </div>
                          <div class="shrink-0 w-8 h-8 rounded-full bg-slate-50 group-hover/item:bg-blue-600 flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover/item:text-white transition-colors">arrow_forward</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>



    <!-- Modals -->
    <!-- Modals -->
    <PdfPreviewModal :isOpen="isPdfPreviewOpen" :fileUrl="currentPdfUrl" @close="handlePdfClose" />
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
const currentReportWithMultipleFiles = ref(null);

const handlePdfClose = () => {
  isPdfPreviewOpen.value = false;
  if (currentReportWithMultipleFiles.value) {
    viewPdf(currentReportWithMultipleFiles.value);
  }
};

const isHtmlPreviewOpen = ref(false);
const isHtmlLoading = ref(false);
const currentHtmlContent = ref('');
const currentHtmlTitle = ref('');

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

const verifiedFilters = ref({});
const archiveFilters = ref({});
const openBooks = ref({});

const toggleBook = (id) => {
  openBooks.value[id] = !openBooks.value[id];
};

const reportsByYear = computed(() => {
  let baseReports = verifiedReports.value;

  const grouped = {};
  baseReports.forEach(report => {
    const dateStr = report.date || report.created_at || '';
    let year = 'Unknown';
    if (dateStr) {
       const dt = new Date(dateStr);
       if (!isNaN(dt.getTime())) {
          year = dt.getFullYear().toString();
       }
    }
    
    if (!grouped[year]) {
      grouped[year] = { year, reports: [] };
      if (verifiedFilters.value[year] === undefined) {
        verifiedFilters.value[year] = '';
      }
    }
    grouped[year].reports.push(report);
  });
  
  return Object.values(grouped).sort((a, b) => b.year.localeCompare(a.year));
});

const archivesByYear = computed(() => {
  let baseArchives = archivedReports.value;

  if (archiveFilters.value['all'] === undefined) {
    archiveFilters.value['all'] = '';
  }

  // Return a single group containing all archives so there is only one book
  return [{
    year: 'all',
    reports: baseArchives.sort((a, b) => b.fiscal_year - a.fiscal_year)
  }];
});

const filterGroup = (reports, query) => {
  if (!query) return reports;
  const q = query.toLowerCase();
  return reports.filter(r => 
    r.title?.toLowerCase().includes(q) || 
    r.office?.toLowerCase().includes(q) || 
    r.control?.toLowerCase().includes(q) ||
    String(r.fiscal_year || '').toLowerCase().includes(q)
  );
};

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
    
    verifiedReports.value = combined.sort((a, b) => new Date(b.date) - new Date(a.date));
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
      archivedReports.value = res.data.data;
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
      let attachments = report.attachment;
      try {
        if (typeof attachments === 'string') attachments = JSON.parse(attachments);
        if (typeof attachments === 'string') attachments = JSON.parse(attachments);
      } catch (e) {
        console.warn('Could not parse attachment JSON', e);
      }

      if (Array.isArray(attachments) && attachments.length > 0) {
        const folder = report.is_archived ? 'archived' : 'drafts';
        const getUrl = (filename) => `${import.meta.env.VITE_API_BASE_URL ? (import.meta.env.VITE_API_BASE_URL.endsWith('/') ? import.meta.env.VITE_API_BASE_URL : import.meta.env.VITE_API_BASE_URL + '/') : 'http://localhost:8080/api/'}files/${folder}/${filename}`;
        
        if (attachments.length === 1) {
          currentPdfUrl.value = getUrl(attachments[0]);
          isPdfPreviewOpen.value = true;
          currentReportWithMultipleFiles.value = null;
          return;
        } else {
          currentReportWithMultipleFiles.value = report;
          let html = `
            <div class="flex flex-col items-center mb-6 mt-2">
              <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mb-4 shadow-sm">
                <span class="material-symbols-outlined text-3xl">folder_open</span>
              </div>
              <h3 class="font-headline font-black text-2xl text-slate-800 tracking-tight">Multiple Documents</h3>
              <p class="text-sm text-slate-500 mt-2 font-label">This report contains multiple attachments. Please select one to view.</p>
            </div>
            <div class="flex flex-col gap-3 text-left">
          `;
          attachments.forEach((att, idx) => {
            html += `
              <button id="btn-att-${idx}" class="w-full text-left bg-white border border-slate-200 hover:border-purple-300 hover:shadow-md hover:bg-purple-50/50 text-slate-700 p-4 rounded-xl flex items-center gap-4 transition-all duration-300 group relative overflow-hidden">
                <div class="w-10 h-10 rounded-lg bg-slate-100 group-hover:bg-purple-100 flex items-center justify-center shrink-0 transition-colors">
                  <span class="material-symbols-outlined text-slate-400 group-hover:text-purple-600 transition-colors">description</span>
                </div>
                <div class="flex-grow min-w-0 flex flex-col">
                  <span class="font-headline font-bold text-slate-800 text-sm group-hover:text-purple-700 transition-colors">Attachment ${idx + 1}</span>
                  <span class="truncate text-xs text-slate-400 font-label mt-0.5">${att}</span>
                </div>
                <div class="w-8 h-8 rounded-full bg-white shadow-sm border border-slate-100 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                  <span class="material-symbols-outlined text-sm text-purple-600">visibility</span>
                </div>
              </button>`;
          });
          html += '</div>';

          Swal.fire({
            html: html,
            showConfirmButton: false,
            showCloseButton: true,
            customClass: { popup: 'rounded-[2rem] p-4 border border-slate-100 shadow-2xl bg-white' },
            didOpen: () => {
              attachments.forEach((att, idx) => {
                const btn = document.getElementById(`btn-att-${idx}`);
                if (btn) {
                  btn.addEventListener('click', () => {
                    currentPdfUrl.value = getUrl(att);
                    isPdfPreviewOpen.value = true;
                    Swal.close();
                  });
                }
              });
            },
            willClose: () => {
              // If we are closing Swal but NOT opening the PDF preview, clear the reference
              if (!isPdfPreviewOpen.value) {
                currentReportWithMultipleFiles.value = null;
              }
            }
          });
          return;
        }
      } else if (typeof attachments === 'string' && attachments.length > 0) {
        // Fallback if it's just a raw string filename
        const folder = report.is_archived ? 'archived' : 'drafts';
        currentPdfUrl.value = `${import.meta.env.VITE_API_BASE_URL ? (import.meta.env.VITE_API_BASE_URL.endsWith('/') ? import.meta.env.VITE_API_BASE_URL : import.meta.env.VITE_API_BASE_URL + '/') : 'http://localhost:8080/api/'}files/${folder}/${attachments}`;
        isPdfPreviewOpen.value = true;
        currentReportWithMultipleFiles.value = null;
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

.book-container {
  perspective: 1500px;
}
.book {
  transform-style: preserve-3d;
  transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  width: 100%;
  height: 100%;
}
.book-cover {
  transform-origin: left center;
  transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
  backface-visibility: hidden;
  z-index: 20;
}
.book-container.is-open .book-cover {
  transform: rotateY(-145deg);
  box-shadow: 10px 10px 20px rgba(0, 0, 0, 0.15);
  pointer-events: none;
}
.book-page {
  z-index: 10;
}
</style>
