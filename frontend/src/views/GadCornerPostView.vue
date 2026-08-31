<template>
  <div class="gad-corner-post text-white font-body pt-32 pb-20" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); min-height: 100vh;">
    <div class="max-w-4xl mx-auto px-6" v-if="loading">
      <div class="text-center py-20 text-slate-400">Loading post...</div>
    </div>
    
    <div class="max-w-4xl mx-auto px-6" v-else-if="!post">
      <div class="text-center py-20 text-slate-400">Post not found.</div>
      <div class="text-center">
        <router-link to="/gad-corner" class="text-purple-400 hover:text-purple-300 transition-colors font-bold flex items-center justify-center gap-2">
          <span class="material-symbols-outlined">arrow_back</span> Back to GAD Corner
        </router-link>
      </div>
    </div>

    <div v-else class="max-w-4xl mx-auto">
      <!-- Navigation & Actions -->
      <div class="flex justify-between items-center px-6 mb-8">
        <router-link to="/gad-corner" class="flex items-center gap-2 text-slate-400 hover:text-white transition-colors font-bold font-label">
          <span class="material-symbols-outlined">arrow_back</span> Back
        </router-link>
        
        <div class="flex items-center gap-4">
          <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest"
                 :class="post.category === 'News' ? 'bg-blue-900/50 text-blue-200 border border-blue-500/30' : 'bg-emerald-900/50 text-emerald-200 border border-emerald-500/30'">
            {{ post.category }}
          </span>
          <button @click="copyShareLink" class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-purple-400 hover:text-purple-300 transition-colors bg-purple-500/10 hover:bg-purple-500/20 px-3 py-1.5 rounded-full border border-purple-500/20">
            <span class="material-symbols-outlined text-[16px]">share</span>
            Copy Link
          </button>
        </div>
      </div>

      <!-- Main Content Card -->
      <div class="bg-[#16213e] rounded-2xl border border-white/10 shadow-2xl overflow-hidden mb-16">
        <div class="p-8 md:p-12 pb-6">
          <h1 class="text-3xl md:text-5xl font-headline font-extrabold text-white mb-6 leading-tight">{{ post.title }}</h1>
          
          <div class="flex items-center gap-4 text-sm text-slate-400 mb-6">
            <span class="flex items-center gap-1 font-label">
              <span class="material-symbols-outlined text-[16px]">calendar_today</span>
              {{ new Date(post.created_at).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' }) }}
            </span>
          </div>
        </div>

        <!-- Carousel -->
        <div v-if="parsedImages.length > 0" class="relative h-64 sm:h-96 md:h-[500px] w-full bg-black/50">
          <img :src="`${apiBaseUrl}files/news-iec/${parsedImages[currentImageIndex]}`" 
               class="object-contain w-full h-full transition-all duration-300" />
          
          <div v-if="parsedImages.length > 1" class="absolute inset-0 flex items-center justify-between px-4">
            <button @click.prevent="prevImage" class="bg-black/50 text-white p-2 rounded-full hover:bg-black/80 transition-colors backdrop-blur-sm shadow-md">
              <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button @click.prevent="nextImage" class="bg-black/50 text-white p-2 rounded-full hover:bg-black/80 transition-colors backdrop-blur-sm shadow-md">
              <span class="material-symbols-outlined">chevron_right</span>
            </button>
          </div>
          
          <div v-if="parsedImages.length > 1" class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
            <div v-for="(_, idx) in parsedImages" :key="idx" 
                 class="w-2 h-2 rounded-full transition-colors shadow-sm cursor-pointer"
                 :class="idx === currentImageIndex ? 'bg-white' : 'bg-white/40'"
                 @click="currentImageIndex = idx">
            </div>
          </div>
        </div>

        <div class="p-8 md:p-12 pt-8">
          <div class="text-slate-200 leading-relaxed whitespace-pre-wrap text-lg md:text-xl font-light mb-10" v-html="linkify(post.description)"></div>

          <!-- Tags -->
          <div v-if="post.tags" class="flex flex-wrap gap-2 pt-6 border-t border-white/10">
            <router-link 
              :to="`/gad-corner?tag=${tag.trim()}`" 
              v-for="tag in post.tags.split(',').filter(t => t.trim())" 
              :key="tag" 
              class="text-xs font-label font-bold text-purple-400 hover:text-white bg-purple-900/20 hover:bg-purple-600 px-3 py-1.5 rounded-full border border-purple-500/20 transition-colors cursor-pointer"
            >
              #{{ tag.trim() }}
            </router-link>
          </div>
        </div>
      </div>

      <!-- Related Items -->
      <div v-if="relatedItems.length > 0" class="px-6">
        <h3 class="text-2xl font-headline font-bold text-white mb-8 flex items-center gap-2">
          <span class="material-symbols-outlined text-purple-400">auto_awesome</span>
          More {{ post.category }}
        </h3>
        
        <div class="grid md:grid-cols-2 gap-8">
          <router-link :to="`/gad-corner/${item.id}`" v-for="item in relatedItems" :key="item.id" class="group bg-white/5 rounded-2xl border border-white/10 overflow-hidden hover:shadow-2xl transition-all duration-300 flex flex-col hover:border-purple-500/30">
            <div v-if="parseImages(item.image_path).length > 0" class="h-48 overflow-hidden bg-black/40">
              <img :src="`${apiBaseUrl}files/news-iec/${parseImages(item.image_path)[0]}`" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" />
            </div>
            <div class="p-6 flex flex-col flex-grow">
              <div class="flex gap-2 mb-3">
                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-widest" :class="item.category === 'News' ? 'bg-blue-900/50 text-blue-200' : 'bg-emerald-900/50 text-emerald-200'">{{ item.category }}</span>
              </div>
              <h4 class="font-headline font-bold text-xl mb-3 text-white group-hover:text-purple-400 transition-colors">{{ item.title }}</h4>
              <p class="text-sm text-slate-400 line-clamp-3 mb-6">{{ item.description }}</p>
              <div class="mt-auto text-xs text-slate-500 font-label flex items-center justify-between">
                <span>{{ new Date(item.created_at).toLocaleDateString() }}</span>
                <span class="text-purple-400 font-bold group-hover:translate-x-1 transition-transform flex items-center gap-1">Read <span class="material-symbols-outlined text-[14px]">arrow_forward</span></span>
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
    .filter(item => item.category === post.value.category && item.id !== post.value.id)
    .slice(0, 4); // Show up to 4 related items
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
