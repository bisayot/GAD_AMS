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

    <!-- Accomplishment Reports Section -->
    <section class="py-16 px-12">
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



    <!-- Resources Section added here -->
    <section class="py-16 px-12 border-t border-white/10">
      <div class="max-w-7xl mx-auto space-y-12">
        <div class="space-y-4">
          <span class="inline-block px-4 py-1.5 rounded-full bg-white/10 text-white font-label text-xs font-bold uppercase tracking-widest">Legal Frameworks</span>
          <h2 class="text-4xl font-headline font-extrabold text-white tracking-tight">Resources & Mandates</h2>
          <p class="text-slate-300 text-lg max-w-lg leading-relaxed">
            Access the fundamental legal documents, international treaties, and institutional policies that shape the Gender and Development landscape at Benguet State University.
          </p>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white/5 p-6 rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-white/10 flex flex-col md:flex-row gap-4 items-center justify-between">
          <div class="relative w-full md:max-w-md">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input v-model="searchQuery" class="w-full pl-12 pr-4 py-3 bg-white/5 border-none rounded-lg focus:ring-2 focus:ring-purple-500 text-white placeholder:text-slate-500" placeholder="Search laws, policies, or mandates..." type="text"/>
          </div>
          <div class="flex gap-3 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
            <button v-for="cat in categories" :key="cat" @click="activeCategory = cat" :class="activeCategory === cat ? 'bg-primary text-white' : 'bg-surface-container-high text-on-surface/70 hover:bg-primary-fixed'" class="px-5 py-2 rounded-full transition-colors font-label text-sm whitespace-nowrap">
              {{ cat }}
            </button>
          </div>
        </div>

        <!-- Bento Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-20">
          <!-- International Mandates -->
          <div class="md:col-span-12 lg:col-span-8 space-y-8">
            <div class="flex items-center gap-4 mb-2">
              <h3 class="text-2xl font-headline font-bold text-white">International Mandates</h3>
              <div class="h-px flex-grow bg-white/10"></div>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
              <div v-for="mandate in filteredMandates" :key="mandate.title" class="group bg-white/5 p-8 rounded-xl border border-white/10 hover:shadow-xl transition-all duration-300">
                <div class="flex justify-between items-start mb-6">
                  <div class="w-12 h-12 academic-gradient rounded-lg flex items-center justify-center text-white">
                    <span class="material-symbols-outlined">{{ mandate.icon }}</span>
                  </div>
                  <span class="material-symbols-outlined text-slate-400 group-hover:text-purple-400 transition-colors">{{ mandate.fileIcon }}</span>
                </div>
                <h4 class="text-xl font-headline font-bold mb-3 group-hover:text-purple-400 transition-colors">{{ mandate.title }}</h4>
                <p class="text-sm text-slate-300 leading-relaxed mb-6">{{ mandate.description }}</p>
                <div class="flex items-center justify-between mt-auto">
                  <span class="text-xs font-label uppercase tracking-widest font-bold text-purple-400">{{ mandate.type }}</span>
                  <a class="text-purple-400 font-label text-sm font-bold underline underline-offset-4 decoration-2" href="#">{{ mandate.action }}</a>
                </div>
              </div>
            </div>
          </div>

          <!-- Sidebar / National Focus -->
          <div class="md:col-span-12 lg:col-span-4 bg-primary text-white p-8 rounded-xl relative overflow-hidden flex flex-col">
            <div class="relative z-10">
              <h3 class="text-2xl font-headline font-bold mb-6">National Policy Spotlight</h3>
              <ul class="space-y-6">
                <li v-for="policy in nationalPolicies" :key="policy.id" class="group cursor-pointer">
                  <p class="text-primary-fixed font-bold text-xs uppercase tracking-widest mb-1">{{ policy.id }}</p>
                  <p class="font-headline font-semibold text-lg group-hover:translate-x-1 transition-transform">{{ policy.title }}</p>
                </li>
              </ul>
              <button class="mt-12 w-full py-4 rounded-full bg-white text-primary font-bold font-headline text-sm hover:bg-primary-fixed transition-colors">View All National Laws</button>
            </div>
            <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-primary-container rounded-full opacity-30 blur-3xl"></div>
          </div>
        </div>

        <!-- Institutional Policies -->
        <div class="md:col-span-12 mb-20">
          <div class="flex items-center gap-4 mb-8">
            <h3 class="text-2xl font-headline font-bold text-white">Institutional Policies</h3>
            <div class="h-px flex-grow bg-white/10"></div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div v-for="policy in institutionalPolicies" :key="policy.title" class="flex flex-col gap-4 p-1 bg-white/5 rounded-2xl">
              <div class="bg-white/5 p-6 rounded-xl h-full border border-white/10">
                <div class="flex items-center gap-3 mb-4">
                  <span class="material-symbols-outlined text-purple-400">{{ policy.icon }}</span>
                  <span class="font-label text-xs font-bold text-purple-400 uppercase tracking-widest">{{ policy.tag }}</span>
                </div>
                <h4 class="font-headline font-bold text-lg mb-2 text-white">{{ policy.title }}</h4>
                <p class="text-sm text-slate-300 mb-6">{{ policy.description }}</p>
                <div class="flex items-center gap-4 text-xs font-bold text-purple-400">
                  <span class="material-symbols-outlined text-lg">{{ policy.actionIcon }}</span>
                  <span>{{ policy.actionText }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>


      </div>
    </section>

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

import { ref, computed, onMounted } from 'vue';
import api from '../api';
import Swal from 'sweetalert2';
import PdfPreviewModal from '../components/PdfPreviewModal.vue';
import HtmlPreviewModal from '../components/HtmlPreviewModal.vue';

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

onMounted(() => {
  fetchAccomplishmentReports();
  fetchArchivedReports();
});

const viewPdf = (report) => {
  try {
    if (report.attachment) {
      const attachments = JSON.parse(report.attachment);
      if (attachments && attachments.length > 0) {
        const folder = report.is_archived ? 'archived' : 'drafts';
        currentPdfUrl.value = `${import.meta.env.VITE_API_BASE_URL || 'https://gad-ams-2-1.onrender.com/api/'}files/${folder}/${attachments[0]}`;
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

const searchQuery = ref('');
const activeCategory = ref('All Resources');
const categories = ['All Resources', 'International', 'National', 'Institutional'];

const mandates = [
  { 
    title: 'CEDAW', 
    description: 'Convention on the Elimination of All Forms of Discrimination Against Women. Often described as an international bill of rights for women.', 
    icon: 'public', 
    fileIcon: 'picture_as_pdf', 
    type: 'UN Treaty', 
    action: 'Download Document',
    category: 'International'
  },
  { 
    title: 'BPFA', 
    description: "Beijing Platform for Action. An agenda for women's empowerment that aims at accelerating the implementation of the Nairobi Forward-looking Strategies.", 
    icon: 'flag', 
    fileIcon: 'link', 
    type: 'Strategic Agenda', 
    action: 'View Reference',
    category: 'International'
  }
];

const nationalPolicies = [
  { id: 'Republic Act 9710', title: 'Magna Carta of Women' },
  { id: 'Administrative Order', title: 'GAD Budget Guidelines' },
  { id: 'Republic Act 7877', title: 'Anti-Sexual Harassment Act' }
];

const institutionalPolicies = [
  {
    title: 'BSU GAD Guidelines',
    description: 'Internal operational frameworks for gender mainstreaming across all BSU campuses.',
    icon: 'school',
    tag: 'BSU Specific',
    actionIcon: 'download',
    actionText: 'PDF (2.4 MB)'
  },
  {
    title: 'Safe Spaces Act Implementation',
    description: 'Localized implementation protocols for the BSU community regarding safe spaces.',
    icon: 'policy',
    tag: 'Code of Conduct',
    actionIcon: 'open_in_new',
    actionText: 'External Portal'
  },
  {
    title: 'GAD 5-Year Strategic Roadmap',
    description: 'Future objectives and developmental milestones for gender parity at BSU.',
    icon: 'history_edu',
    tag: 'Strategic Plan',
    actionIcon: 'download',
    actionText: 'PDF (5.1 MB)'
  }
];

const filteredMandates = computed(() => {
  return mandates.filter(m => {
    const matchesSearch = m.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                         m.description.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesCategory = activeCategory.value === 'All Resources' || m.category === activeCategory.value;
    return matchesSearch && matchesCategory;
  });
});
</script>

<style scoped>
.academic-gradient {
  background: linear-gradient(135deg, #422b68 0%, #5a4281 100%);
}
</style>
