<template>
  <div class="tag-results-view text-slate-900 font-body pt-32 pb-20 bg-slate-50 min-h-screen relative overflow-hidden">
    <!-- Optional background accent -->
    <div class="absolute top-0 inset-x-0 h-96 bg-gradient-to-b from-slate-200/50 to-transparent pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-6 relative z-10" v-if="loading">
      <div class="text-center py-20 text-slate-500 font-bold animate-pulse">Loading results...</div>
    </div>
    
    <div v-else class="max-w-4xl mx-auto relative z-10">
      <!-- Header -->
      <div class="mb-12 flex items-center justify-between">
        <button @click="$router.back()" class="flex items-center gap-2 text-slate-500 hover:text-black transition-colors font-black font-label">
          <span class="material-symbols-outlined">arrow_back</span> Back
        </button>
      </div>

      <div class="mb-12">
        <div class="flex items-center gap-3 mb-4">
          <span class="material-symbols-outlined text-purple-600 text-3xl">label</span>
          <span class="text-lg font-bold text-slate-500 uppercase tracking-widest">Tag Search</span>
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-[56px] font-headline font-black text-slate-900 leading-[1.1] tracking-tight mb-4">
          Results for "{{ tag }}"
        </h1>
        <p class="text-slate-600 text-lg">Found {{ filteredItems.length }} {{ filteredItems.length === 1 ? 'post' : 'posts' }}</p>
      </div>

      <!-- Results Grid -->
      <div v-if="filteredItems.length === 0" class="py-20 text-center">
        <span class="material-symbols-outlined text-6xl text-slate-300 mb-4 block">search_off</span>
        <h3 class="text-2xl font-bold text-slate-400">No posts found with this tag.</h3>
      </div>
      
      <div v-else>
        <div class="grid md:grid-cols-2 gap-8">
          <router-link :to="`/gad-corner/${item.id}`" v-for="item in filteredItems" :key="item.id" class="group bg-white rounded-3xl border border-slate-100 shadow-lg hover:shadow-xl hover:-translate-y-1 overflow-hidden transition-all duration-300 flex flex-col">
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
import { useRoute } from 'vue-router';
import api from '../api';

const route = useRoute();
const tag = computed(() => route.params.tag);
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL 
  ? (import.meta.env.VITE_API_BASE_URL.endsWith('/') ? import.meta.env.VITE_API_BASE_URL : import.meta.env.VITE_API_BASE_URL + '/') 
  : 'http://localhost:8080/api/';

const loading = ref(true);
const allItems = ref([]);

const filteredItems = computed(() => {
  if (!tag.value || allItems.value.length === 0) return [];
  const searchTag = tag.value.toLowerCase().trim();
  return allItems.value.filter(item => {
    if (!item.tags) return false;
    const itemTags = item.tags.toLowerCase().split(',').map(t => t.trim());
    return itemTags.includes(searchTag);
  });
});

const parseImages = (val) => {
  if (!val) return [];
  if (Array.isArray(val)) return val;
  try {
    const parsed = JSON.parse(val);
    return Array.isArray(parsed) ? parsed : [];
  } catch(e) {
    return val ? [val] : [];
  }
};

const fetchNews = async () => {
  loading.value = true;
  try {
    const res = await api.get('/news-iec');
    if (res.data && res.data.success) {
      allItems.value = res.data.data || [];
    } else {
      allItems.value = Array.isArray(res.data) ? res.data : [];
    }
  } catch (error) {
    console.error("Error fetching news:", error);
    allItems.value = [];
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchNews();
});

watch(tag, () => {
  window.scrollTo(0,0);
});
</script>
