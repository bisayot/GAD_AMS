<template>
  <div class="gad-corner bg-background text-on-surface font-body pt-20">
    <!-- Formal Header -->
    <section class="py-20 border-b border-outline-variant/10 bg-surface-container-lowest px-12 text-center">
      <div class="max-w-screen-2xl mx-auto space-y-4">
        <span class="text-secondary font-label font-bold uppercase text-xs tracking-[0.3em]">News &Updates</span>
        <h1 class="text-5xl font-headline font-black text-primary tracking-tight">GAD Corner</h1>
        <p class="text-lg text-on-surface-variant max-w-3xl mx-auto leading-relaxed">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        </p>
      </div>
    </section>

    <section class="py-16 px-12 bg-background">
      <div class="max-w-screen-2xl mx-auto grid lg:grid-cols-12 gap-16">

        <div class="lg:col-span-8">
          <div class="space-y-12">
            <article v-for="post in posts" :key="post.id" class="grid md:grid-cols-12 gap-8 items-start pb-12 border-b border-outline-variant/10 last:border-0">
              <div class="md:col-span-4 aspect-video bg-surface-container rounded overflow-hidden border border-outline-variant/5">
                <img :src="post.image" class="w-full h-full object-cover" :alt="post.title" />
              </div>
              <div class="md:col-span-8 space-y-4">
                <div class="flex items-center gap-3">
                  <span class="text-[10px] font-black uppercase tracking-widest text-secondary">{{ post.category }}</span>
                  <span class="text-xs text-outline">{{ post.date }}</span>
                </div>
                <h3 class="text-2xl font-headline font-extrabold text-on-surface leading-tight hover:text-primary transition-colors cursor-pointer">
                  {{ post.title }}
                </h3>
                <p class="text-on-surface-variant leading-relaxed text-sm line-clamp-3">
                  {{ post.excerpt }}
                </p>
                <button class="text-xs font-bold text-primary uppercase tracking-widest flex items-center gap-2 hover:gap-3 transition-all">
                  Read Full Article <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
              </div>
            </article>
          </div>
        </div>

        <!-- Sidebar -->
        <aside class="lg:col-span-4 space-y-12">
          <!-- Popular Topics -->
          <div class="p-8 bg-surface-container-low rounded border border-outline-variant/10">
            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-primary mb-6">Topic Explorer</h4>
            <div class="flex flex-wrap gap-2">
              <span v-for="topic in topics" :key="topic" class="px-3 py-1 bg-surface-container-lowest border border-outline-variant/30 rounded text-xs font-medium text-on-surface-variant hover:border-primary cursor-pointer transition-all">#{{ topic }}</span>
            </div>
          </div>

          <!-- Newsletter -->
          <div class="p-8 bg-primary text-on-primary rounded">
            <h4 class="text-xs font-black uppercase tracking-[0.2em] opacity-80 mb-2">Stay Updated</h4>
            <p class="text-sm leading-relaxed mb-6">Join our official mailing list for institutional announcements and reports.</p>
            <div class="space-y-3">
              <input class="w-full bg-white/10 border border-white/20 rounded px-4 py-2 text-xs placeholder:text-white/50" placeholder="Email Address" />
              <button class="w-full bg-secondary text-on-secondary py-2 rounded font-bold text-xs uppercase tracking-widest hover:opacity-90 transition-opacity">
                Join List
              </button>
            </div>
          </div>

          <!-- Archives -->
          <div class="p-8 bg-surface-container-low rounded border border-outline-variant/10">
            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-primary mb-6">Archive Repository</h4>
            <ul class="space-y-3">
              <li v-for="archive in archives" :key="archive.month">
                <a class="flex justify-between items-center group" href="#">
                  <span class="text-sm text-on-surface-variant group-hover:text-primary transition-colors">{{ archive.month }}</span>
                  <span class="text-[10px] font-bold text-outline">{{ archive.count }} items</span>
                </a>
              </li>
            </ul>
          </div>
        </aside>
      </div>
    </section>

    <!-- Resources Section added here -->
    <section class="py-16 px-12 bg-background border-t border-outline-variant/10">
      <div class="max-w-7xl mx-auto space-y-12">
        <div class="space-y-4">
          <span class="inline-block px-4 py-1.5 rounded-full bg-secondary-container text-on-secondary-container font-label text-xs font-bold uppercase tracking-widest">Legal Frameworks</span>
          <h2 class="text-4xl font-headline font-extrabold text-primary tracking-tight">Resources & Mandates</h2>
          <p class="text-on-surface-variant text-lg max-w-lg leading-relaxed">
            Access the fundamental legal documents, international treaties, and institutional policies that shape the Gender and Development landscape at Benguet State University.
          </p>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-outline-variant/15 flex flex-col md:flex-row gap-4 items-center justify-between">
          <div class="relative w-full md:max-w-md">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input v-model="searchQuery" class="w-full pl-12 pr-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary text-on-surface placeholder:text-outline/60" placeholder="Search laws, policies, or mandates..." type="text"/>
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
              <h3 class="text-2xl font-headline font-bold text-primary">International Mandates</h3>
              <div class="h-px flex-grow bg-outline-variant/20"></div>
            </div>
            <div class="grid md:grid-cols-2 gap-6">
              <div v-for="mandate in filteredMandates" :key="mandate.title" class="group bg-surface-container-lowest p-8 rounded-xl border border-outline-variant/10 hover:shadow-xl transition-all duration-300">
                <div class="flex justify-between items-start mb-6">
                  <div class="w-12 h-12 academic-gradient rounded-lg flex items-center justify-center text-white">
                    <span class="material-symbols-outlined">{{ mandate.icon }}</span>
                  </div>
                  <span class="material-symbols-outlined text-outline/40 group-hover:text-primary transition-colors">{{ mandate.fileIcon }}</span>
                </div>
                <h4 class="text-xl font-headline font-bold mb-3 group-hover:text-primary transition-colors">{{ mandate.title }}</h4>
                <p class="text-sm text-on-surface-variant leading-relaxed mb-6">{{ mandate.description }}</p>
                <div class="flex items-center justify-between mt-auto">
                  <span class="text-xs font-label uppercase tracking-widest font-bold text-secondary">{{ mandate.type }}</span>
                  <a class="text-primary font-label text-sm font-bold underline underline-offset-4 decoration-2" href="#">{{ mandate.action }}</a>
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
            <h3 class="text-2xl font-headline font-bold text-primary">Institutional Policies</h3>
            <div class="h-px flex-grow bg-outline-variant/20"></div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div v-for="policy in institutionalPolicies" :key="policy.title" class="flex flex-col gap-4 p-1 bg-surface-container rounded-2xl">
              <div class="bg-surface-container-lowest p-6 rounded-xl h-full">
                <div class="flex items-center gap-3 mb-4">
                  <span class="material-symbols-outlined text-secondary">{{ policy.icon }}</span>
                  <span class="font-label text-xs font-bold text-secondary uppercase tracking-widest">{{ policy.tag }}</span>
                </div>
                <h4 class="font-headline font-bold text-lg mb-2">{{ policy.title }}</h4>
                <p class="text-sm text-on-surface-variant mb-6">{{ policy.description }}</p>
                <div class="flex items-center gap-4 text-xs font-bold text-primary">
                  <span class="material-symbols-outlined text-lg">{{ policy.actionIcon }}</span>
                  <span>{{ policy.actionText }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Help Section -->
        <div class="mt-24 bg-surface-container-low rounded-3xl p-12 text-center max-w-4xl mx-auto">
          <h3 class="text-3xl font-headline font-extrabold text-primary mb-4">Cannot find a specific document?</h3>
          <p class="text-on-surface-variant mb-8 text-lg">Our office maintains an extensive physical archive of gender-related legislations and university memos. Please reach out if you need assistance.</p>
          <div class="flex flex-col sm:flex-row justify-center gap-4">
            <button class="px-8 py-3 rounded-full bg-primary text-white font-bold font-headline hover:opacity-90 transition-all">Request Document</button>
            <button class="px-8 py-3 rounded-full border border-primary text-primary font-bold font-headline hover:bg-primary/5 transition-all">Contact GAD Office</button>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
const posts = [
  {
    id: 1,
    category: 'Event',
    date: 'Oct 22, 2024',
    readTime: '5 min read',
    title: 'Safe Spaces Workshop: Fostering Inclusivity in the Classroom',
    excerpt: 'Faculty members from all colleges gathered to discuss pedagogical shifts towards a more gender-neutral learning environment.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuA_G-T1Vpm2ZjLuRu755YEaAGpt3V7Ler9sk6h4sKNar4xApm8zyfHC3kF-6wSYQ4Lz83Ie_T35icsIgans2YFzQ0i42u_duHDfyoZ71YsjYtgNt5qVqmzVsjPofsx_HzCADKPMnYVZt6rm1pYAs2g5OOQj-EXH9NAfsld8Aa1StDuxzNi5Cg_QMdBRmzl6H_BZ_jPfuSs8hn9MnxN69ymwycoiQim49fZbjH04lU0YU6vtc4LYqsy7VArmW0kR148hQFKtZcvDqjCR'
  },
  {
    id: 2,
    category: 'Story',
    date: 'Oct 19, 2024',
    readTime: '8 min read',
    title: 'Voices of BSU: A Retrospective on 20 Years of GAD',
    excerpt: 'Interviewing the pioneers who established the first gender desk in the Cordillera administrative region.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBOd2mv2tzbeM8AuSFeoIXT0Z7VujFAuRd2-yjZVBXA2B9cPLJcDyMhOmoCLSCOa0xJQJu-63XnHgX8zR5wPNZpAbT6IqF1Y_HmVn3rvH6bRnEHgvNFP-ZrivtscjVp1ELzYQPAl68gUtDeqDBaDCszyqcICbZwJFtjyOhiiTE4kq5ko_NNMM4IbvRT6cx2KVdNu6ujkgZCK7ojvA0tGxMW1riRisFxi5RNeotfZYAi1NbTg76eEZ1STRpH6v-Y0TX1C8wNBg3wE8zh'
  },
  {
    id: 3,
    category: 'Announcement',
    date: 'Oct 15, 2024',
    readTime: '2 min read',
    title: 'Upcoming Seminar: Digital Citizenship and Online Gender Safety',
    excerpt: 'Join us this coming Friday for a webinar focusing on protecting students from cyber-harassment.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBS0v8kIFq1kNUaEUBVOnyek8ZRC8JZCXewDZT21S4xx5zhb_Re2o9fCwDvRI-ku437S4s985HvU48O0oOD0rU5gv242ae8U9L3L8smwKuuL5MO_-u6jPy7mW06xLoRuCw6U-8TM8CstK0fO0okIEsywIMLAoi4JKPiZDxOebJNP40kCr3EMY5MZZBAus5A0IznTHMqMwADeBs73wesw4ZkGpaWmqnMxW5BuDIXNLx6dpnYs11LpiMKcB1hF775-BDfuqHxdxj82ywx'
  },
  {
    id: 4,
    category: 'Event',
    date: 'Oct 12, 2024',
    readTime: '4 min read',
    title: 'Call for Proposals: 2025 Gender Equity Research Grants',
    excerpt: 'The GAD Council is now accepting research proposals that address urban-rural gender disparities in Cordillera.',
    image: 'https://lh3.googleusercontent.com/aida-public/AB6AXuBx0lc2Au-DLX0yKlhzfrMCQ_ggrg9nKcSi5wE0pSJ-2v8XsUYzcao1XYAzPXh5ZUaBknyQrVi6BDkppqnqpxs7QfwB5w5YkhKQIl4c7u58bAcrSoKelhFDGjQafAPyn9LTAvXS9BCpg77kPeuM7ytEb_pXMPrYakaDfXndimEwy6cKBQEVacgFmEM79oXGyiRoQCCFDtahmox_95wDjnGT-YJbB7OUojAaQD7cQ8Pa921avcVGYY9XRImAv8CwdZQ03IkuMXxni_BS'
  }
];

const topics = ['GenderEquality', 'IndigenousRights', 'WomenInSTEM', 'SafeSpaces', 'InstitutionalPolicies', 'Training'];

const archives = [
  { month: 'October 2024', count: 12 },
  { month: 'September 2024', count: 8 },
  { month: 'August 2024', count: 15 },
  { month: 'July 2024', count: 5 }
];

const socialLinks = [
  { icon: 'public' },
  { icon: 'share' },
  { icon: 'rss_feed' }
];

import { ref, computed } from 'vue';

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
