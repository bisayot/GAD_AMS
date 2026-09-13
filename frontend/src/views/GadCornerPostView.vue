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

    <div v-else class="max-w-4xl mx-auto relative z-10">
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
        <div class="bg-slate-100 rounded-xl overflow-hidden relative h-64 sm:h-96 md:h-[500px] w-full group">
          <img :src="`${apiBaseUrl}files/news-iec/${parsedImages[currentImageIndex]}`" 
               class="object-cover w-full h-full transition-all duration-300" />
          
          <!-- Carousel Arrows -->
          <div v-if="parsedImages.length > 1" class="absolute inset-0 flex items-center justify-between px-4 pointer-events-none z-40">
            <button @click.prevent="prevImage" class="pointer-events-auto w-14 h-14 flex items-center justify-center rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.5)] hover:scale-110 active:scale-95 transition-all" style="background-color: white !important; color: black !important; border: 2px solid rgba(0,0,0,0.1) !important; opacity: 1 !important;">
              <span class="material-symbols-outlined font-black text-3xl" style="color: black !important; font-weight: 900 !important;">chevron_left</span>
            </button>
            <button @click.prevent="nextImage" class="pointer-events-auto w-14 h-14 flex items-center justify-center rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.5)] hover:scale-110 active:scale-95 transition-all" style="background-color: white !important; color: black !important; border: 2px solid rgba(0,0,0,0.1) !important; opacity: 1 !important;">
              <span class="material-symbols-outlined font-black text-3xl" style="color: black !important; font-weight: 900 !important;">chevron_right</span>
            </button>
          </div>
          
          <div v-if="parsedImages.length > 1" class="absolute bottom-6 left-0 right-0 flex justify-center gap-3">
            <div v-for="(_, idx) in parsedImages" :key="idx" 
                 class="w-2.5 h-2.5 rounded-full transition-all shadow-sm cursor-pointer"
                 :class="idx === currentImageIndex ? 'bg-white scale-125' : 'bg-white/50 hover:bg-white/80'"
                 @click="currentImageIndex = idx">
            </div>
          </div>
        </div>
      </div>

      <!-- Description (Outside Card) -->
      <div class="px-6 mb-20">
        <div class="text-slate-800 leading-relaxed whitespace-pre-wrap text-lg md:text-xl font-body mb-12" v-html="linkify(post.description)"></div>
      </div>

      <!-- Related Items (Now All News/IEC) -->
      <div v-if="relatedItems.length > 0" class="px-6">
        <h3 class="text-3xl font-headline font-black text-slate-900 mb-10">
          More News & IEC
        </h3>
        
        <div class="grid md:grid-cols-2 gap-8">
          <router-link :to="`/gad-corner/${item.id}`" v-for="item in relatedItems" :key="item.id" class="group bg-white rounded-3xl border border-slate-100 shadow-lg hover:shadow-xl hover:-translate-y-1 overflow-hidden transition-all duration-300 flex flex-col">
            <div v-if="parseImages(item.image_path).length > 0" class="h-48 overflow-hidden bg-slate-100">
              <img :src="`${apiBaseUrl}files/news-iec/${parseImages(item.image_path)[0]}`" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" />
            </div>
            <div class="p-6 md:p-8 flex flex-col flex-grow">
              <div class="flex gap-2 mb-4">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest" :class="item.category === 'News' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700'">{{ item.category }}</span>
              </div>
              <h4 class="font-headline font-bold text-2xl mb-3 text-slate-800 group-hover:text-purple-600 transition-colors leading-snug">{{ item.title }}</h4>
              <p class="text-base text-slate-600 line-clamp-3 mb-8">{{ item.description }}</p>
              <div class="mt-auto pt-4 border-t border-slate-100 text-xs text-slate-500 font-bold font-label flex items-center justify-between">
                <span>{{ new Date(item.created_at).toLocaleDateString() }}</span>
                <span class="text-purple-600 font-bold group-hover:translate-x-2 transition-transform flex items-center gap-1 text-sm">Read <span class="material-symbols-outlined font-bold text-[18px]">arrow_forward</span></span>
              </div>
            </div>
          </router-link>
        </div>
      </div>
    </div>
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
  return allItems.value
    .filter(item => item.id !== post.value.id);
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
