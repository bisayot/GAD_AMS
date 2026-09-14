<template>
  <div class="gad-corner-post text-slate-900 font-body pt-32 pb-20 bg-slate-50 min-h-screen relative overflow-hidden">
    <!-- Optional background accent -->
    <div class="absolute top-0 inset-x-0 h-96 bg-gradient-to-b from-slate-200/50 to-transparent pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-6 relative z-10" v-if="loading">
      <div class="text-center py-20 text-slate-500 font-bold animate-pulse">Loading post...</div>
    </div>
    
    <div class="max-w-4xl mx-auto px-6 relative z-10" v-else-if="!post">
      <div class="text-center py-20 text-slate-500 font-bold">Post not found.</div>
      <div class="text-center">
        <router-link to="/gad-corner" class="text-black hover:text-purple-700 transition-colors font-black flex items-center justify-center gap-2">
          <span class="material-symbols-outlined">arrow_back</span> Back to GAD Corner
        </router-link>
      </div>
    </div>

    <div v-else>
      <div class="max-w-4xl mx-auto relative z-10">
      <!-- Tags (At the very top) -->
      <div class="px-6 mb-6 flex flex-wrap gap-3" v-if="post.tags">
        <router-link 
          :to="`/gad-corner/tags/${tag.trim()}`" 
          v-for="tag in post.tags.split(',').filter(t => t.trim())" 
          :key="tag" 
          class="text-sm font-body text-slate-600 bg-white hover:bg-slate-50 px-4 py-1.5 rounded-full border border-slate-200 transition-all flex items-center gap-2"
        >
          {{ tag.trim() }} <span class="material-symbols-outlined text-[14px] text-slate-400">arrow_outward</span>
        </router-link>
      </div>

      <!-- Title -->
      <div class="px-6 mb-6">
        <h1 class="text-4xl md:text-5xl lg:text-[56px] font-headline font-black text-slate-900 leading-[1.1] tracking-tight">{{ post.title }}</h1>
      </div>

      <!-- Meta Info & Actions -->
      <div class="px-6 mb-12 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-sm text-slate-600 font-body">
        <div class="flex items-center gap-3">
          <img src="/images/logo.png" class="w-10 h-10 rounded-full object-contain bg-white border border-slate-100 shadow-sm" alt="Author" />
          <div class="flex items-center flex-wrap gap-x-2">
            <span class="font-medium text-slate-900">BSU GAD Office</span>
            <span class="px-3 py-0.5 rounded-full border border-slate-200 text-xs font-medium">{{ post.category }}</span>
            <span>&middot;</span>
            <span>{{ new Date(post.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) }}</span>
          </div>
        </div>
        
        <div class="flex items-center">
          <button @click="copyShareLink" class="flex items-center gap-2 hover:text-slate-900 transition-colors text-slate-500 font-medium bg-slate-50 border border-slate-200 hover:bg-slate-100 px-4 py-1.5 rounded-full" title="Copy Link">
            <span class="material-symbols-outlined text-[18px]">ios_share</span>
            Copy Link
          </button>
        </div>
      </div>

      <!-- Carousel (The Card) -->
      <div v-if="parsedImages.length > 0" class="px-6 mb-12">
        <!-- Image -->
        <div class="bg-slate-100 rounded-xl overflow-hidden w-full flex items-center justify-center min-h-[300px]">
          <img :src="`${apiBaseUrl}files/news-iec/${parsedImages[currentImageIndex]}`" 
               class="w-full max-h-[80vh] object-contain transition-all duration-300" />
        </div>

        <!-- Controls: Arrows + Dots (outside the image) -->
        <div v-if="parsedImages.length > 1" class="flex items-center justify-center gap-4 mt-4">
          <!-- Prev Arrow -->
          <button @click.prevent="prevImage"
            class="w-9 h-12 sm:w-12 sm:h-12 flex items-center justify-center rounded-lg sm:rounded-full shadow-md hover:scale-110 active:scale-95 transition-all shrink-0"
            style="background-color: white; border: 2px solid rgba(0,0,0,0.12);">
            <span class="material-symbols-outlined font-black text-xl" style="color: black; font-weight: 900;">chevron_left</span>
          </button>

          <!-- Dots -->
          <div class="flex justify-center gap-2.5 flex-wrap">
            <div v-for="(_, idx) in parsedImages" :key="idx"
                 class="w-2.5 h-2.5 rounded-full transition-all cursor-pointer shadow-sm"
                 :class="idx === currentImageIndex ? 'bg-slate-700 scale-125' : 'bg-slate-300 hover:bg-slate-500'"
                 @click="currentImageIndex = idx">
            </div>
          </div>

          <!-- Next Arrow -->
          <button @click.prevent="nextImage"
            class="w-9 h-12 sm:w-12 sm:h-12 flex items-center justify-center rounded-lg sm:rounded-full shadow-md hover:scale-110 active:scale-95 transition-all shrink-0"
            style="background-color: white; border: 2px solid rgba(0,0,0,0.12);">
            <span class="material-symbols-outlined font-black text-xl" style="color: black; font-weight: 900;">chevron_right</span>
          </button>
        </div>
      </div>

      <!-- Description (Outside Card) -->
      <div class="px-6 mb-20">
        <div class="text-slate-800 leading-relaxed whitespace-pre-wrap text-lg md:text-xl font-body mb-12" v-html="linkify(post.description)"></div>
      </div>

      </div><!-- end max-w-4xl article wrapper -->

      <!-- Related Items — split by category with horizontal sliders (full width) -->
      <div v-if="relatedItems.length > 0" class="mt-12 pt-10 border-t border-slate-200">
        <h3 class="px-6 text-3xl font-headline font-black text-slate-900 mb-10">More from the Bulletin</h3>

        <!-- NEWS ROW -->
        <div v-if="newsPosts.length > 0" class="mb-14">
          <div class="px-6 flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
              <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>
              <h4 class="text-xl font-headline font-bold text-slate-800">News</h4>
              <span class="text-xs text-slate-400 font-label">{{ newsPosts.length }} post{{ newsPosts.length !== 1 ? 's' : '' }}</span>
            </div>
            <div class="flex gap-2">
              <button @click="scrollRow('news', -1)" class="w-9 h-9 flex items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:bg-slate-50 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-base text-slate-600">chevron_left</span>
              </button>
              <button @click="scrollRow('news', 1)" class="w-9 h-9 flex items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:bg-slate-50 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-base text-slate-600">chevron_right</span>
              </button>
            </div>
          </div>
          <div ref="newsRow" class="flex gap-5 overflow-x-hidden scroll-smooth px-6">
            <router-link :to="`/gad-corner/${item.id}`" v-for="item in newsPosts" :key="item.id"
              class="group bg-white rounded-3xl border border-slate-100 shadow-lg hover:shadow-xl hover:-translate-y-1 overflow-hidden transition-all duration-300 flex flex-col shrink-0 w-72">
              <div v-if="parseImages(item.image_path).length > 0" class="h-44 overflow-hidden bg-slate-100">
                <img :src="`${apiBaseUrl}files/news-iec/${parseImages(item.image_path)[0]}`" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" />
              </div>
              <div v-else class="h-24 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-blue-300">newspaper</span>
              </div>
              <div class="p-5 flex flex-col flex-grow">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-blue-50 text-blue-700 mb-3 self-start">News</span>
                <h5 class="font-headline font-bold text-lg mb-2 text-slate-800 group-hover:text-purple-600 transition-colors leading-snug line-clamp-2">{{ item.title }}</h5>
                <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ item.description }}</p>
                <div class="mt-auto pt-3 border-t border-slate-100 text-xs text-slate-400 font-label flex items-center justify-between">
                  <span>{{ new Date(item.created_at).toLocaleDateString() }}</span>
                  <span class="text-purple-600 font-bold group-hover:translate-x-1.5 transition-transform flex items-center gap-1">Read <span class="material-symbols-outlined text-[16px]">arrow_forward</span></span>
                </div>
              </div>
            </router-link>
          </div>
        </div>

        <!-- IEC ROW -->
        <div v-if="iecPosts.length > 0" class="mb-14">
          <div class="px-6 flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
              <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
              <h4 class="text-xl font-headline font-bold text-slate-800">IEC Materials</h4>
              <span class="text-xs text-slate-400 font-label">{{ iecPosts.length }} post{{ iecPosts.length !== 1 ? 's' : '' }}</span>
            </div>
            <div class="flex gap-2">
              <button @click="scrollRow('iec', -1)" class="w-9 h-9 flex items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:bg-slate-50 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-base text-slate-600">chevron_left</span>
              </button>
              <button @click="scrollRow('iec', 1)" class="w-9 h-9 flex items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:bg-slate-50 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-base text-slate-600">chevron_right</span>
              </button>
            </div>
          </div>
          <div ref="iecRow" class="flex gap-5 overflow-x-hidden scroll-smooth px-6">
            <router-link :to="`/gad-corner/${item.id}`" v-for="item in iecPosts" :key="item.id"
              class="group bg-white rounded-3xl border border-slate-100 shadow-lg hover:shadow-xl hover:-translate-y-1 overflow-hidden transition-all duration-300 flex flex-col shrink-0 w-72">
              <div v-if="parseImages(item.image_path).length > 0" class="h-44 overflow-hidden bg-slate-100">
                <img :src="`${apiBaseUrl}files/news-iec/${parseImages(item.image_path)[0]}`" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" />
              </div>
              <div v-else class="h-24 bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-emerald-300">campaign</span>
              </div>
              <div class="p-5 flex flex-col flex-grow">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-emerald-50 text-emerald-700 mb-3 self-start">IEC</span>
                <h5 class="font-headline font-bold text-lg mb-2 text-slate-800 group-hover:text-purple-600 transition-colors leading-snug line-clamp-2">{{ item.title }}</h5>
                <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ item.description }}</p>
                <div class="mt-auto pt-3 border-t border-slate-100 text-xs text-slate-400 font-label flex items-center justify-between">
                  <span>{{ new Date(item.created_at).toLocaleDateString() }}</span>
                  <span class="text-purple-600 font-bold group-hover:translate-x-1.5 transition-transform flex items-center gap-1">Read <span class="material-symbols-outlined text-[16px]">arrow_forward</span></span>
                </div>
              </div>
            </router-link>
          </div>
        </div>

        <!-- ANNOUNCEMENT ROW -->
        <div v-if="announcementPosts.length > 0" class="mb-14">
          <div class="px-6 flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
              <span class="w-3 h-3 rounded-full bg-orange-500 inline-block"></span>
              <h4 class="text-xl font-headline font-bold text-slate-800">Announcements</h4>
              <span class="text-xs text-slate-400 font-label">{{ announcementPosts.length }} post{{ announcementPosts.length !== 1 ? 's' : '' }}</span>
            </div>
            <div class="flex gap-2">
              <button @click="scrollRow('announcement', -1)" class="w-9 h-9 flex items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:bg-slate-50 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-base text-slate-600">chevron_left</span>
              </button>
              <button @click="scrollRow('announcement', 1)" class="w-9 h-9 flex items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:bg-slate-50 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-base text-slate-600">chevron_right</span>
              </button>
            </div>
          </div>
          <div ref="announcementRow" class="flex gap-5 overflow-x-hidden scroll-smooth px-6">
            <router-link :to="`/gad-corner/${item.id}`" v-for="item in announcementPosts" :key="item.id"
              class="group bg-white rounded-3xl border border-slate-100 shadow-lg hover:shadow-xl hover:-translate-y-1 overflow-hidden transition-all duration-300 flex flex-col shrink-0 w-72">
              <div v-if="parseImages(item.image_path).length > 0" class="h-44 overflow-hidden bg-slate-100">
                <img :src="`${apiBaseUrl}files/news-iec/${parseImages(item.image_path)[0]}`" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" />
              </div>
              <div v-else class="h-24 bg-gradient-to-br from-orange-50 to-orange-100 flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-orange-300">notifications</span>
              </div>
              <div class="p-5 flex flex-col flex-grow">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-widest bg-orange-50 text-orange-600 mb-3 self-start">Announcement</span>
                <h5 class="font-headline font-bold text-lg mb-2 text-slate-800 group-hover:text-purple-600 transition-colors leading-snug line-clamp-2">{{ item.title }}</h5>
                <p class="text-sm text-slate-500 line-clamp-2 mb-4">{{ item.description }}</p>
                <div class="mt-auto pt-3 border-t border-slate-100 text-xs text-slate-400 font-label flex items-center justify-between">
                  <span>{{ new Date(item.created_at).toLocaleDateString() }}</span>
                  <span class="text-purple-600 font-bold group-hover:translate-x-1.5 transition-transform flex items-center gap-1">Read <span class="material-symbols-outlined text-[16px]">arrow_forward</span></span>
                </div>
              </div>
            </router-link>
          </div>
        </div>
      </div><!-- end bulletin section -->
    </div><!-- end v-else -->
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../api';
import Swal from 'sweetalert2';

const route = useRoute();
const router = useRouter();

const searchTag = (tag) => {
  router.push({
    path: '/gad-corner',
    query: { search: tag.trim() }
  });
};
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL 
  ? (import.meta.env.VITE_API_BASE_URL.endsWith('/') ? import.meta.env.VITE_API_BASE_URL : import.meta.env.VITE_API_BASE_URL + '/') 
  : 'http://localhost:8080/api/';

const loading = ref(true);
const post = ref(null);
const allItems = ref([]);
const currentImageIndex = ref(0);

const parsedImages = computed(() => {
  if (!post.value) return [];
  return parseImages(post.value.image_path);
});

const relatedItems = computed(() => {
  if (!post.value || allItems.value.length === 0) return [];
  return allItems.value.filter(item => item.id !== post.value.id);
});

const newsPosts = computed(() => relatedItems.value.filter(i => i.category === 'News'));
const iecPosts = computed(() => relatedItems.value.filter(i => i.category === 'IEC'));
const announcementPosts = computed(() => relatedItems.value.filter(i => i.category === 'Announcement'));

// Row scroll refs
const newsRow = ref(null);
const iecRow = ref(null);
const announcementRow = ref(null);

const SCROLL_AMOUNT = 320; // px per click (approx. one card width + gap)

const scrollRow = (category, direction) => {
  const map = { news: newsRow, iec: iecRow, announcement: announcementRow };
  const el = map[category]?.value;
  if (el) el.scrollBy({ left: direction * SCROLL_AMOUNT, behavior: 'smooth' });
};

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

const nextImage = () => {
  if (parsedImages.value.length > 0) {
    currentImageIndex.value = (currentImageIndex.value + 1) % parsedImages.value.length;
  }
};

const prevImage = () => {
  if (parsedImages.value.length > 0) {
    currentImageIndex.value = (currentImageIndex.value - 1 + parsedImages.value.length) % parsedImages.value.length;
  }
};

const copyShareLink = () => {
  const link = `${window.location.origin}/gad-corner/${post.value.id}`;
  navigator.clipboard.writeText(link).then(() => {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: 'Link copied to clipboard!',
      showConfirmButton: false,
      timer: 2000,
      background: '#1e293b',
      color: '#fff'
    });
  });
};

const fetchPost = async (id) => {
  loading.value = true;
  try {
    const [postRes, allRes] = await Promise.all([
      api.get(`news-iec/${id}`),
      api.get('news-iec')
    ]);

    if (postRes.data && postRes.data.success) {
      post.value = postRes.data.data;
      currentImageIndex.value = 0;
    } else {
      post.value = null;
    }

    if (allRes.data && allRes.data.success) {
      allItems.value = allRes.data.data;
    }
    
    window.scrollTo(0, 0);
  } catch (err) {
    console.error("Failed to fetch post:", err);
    post.value = null;
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchPost(route.params.id);
});

watch(() => route.params.id, (newId) => {
  if (newId) {
    fetchPost(newId);
  }
});
</script>
