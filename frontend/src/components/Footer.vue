<template>
  <footer class="w-full border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
    <div class="flex flex-col md:flex-row justify-between items-center w-full px-12 py-10 gap-6 max-w-screen-2xl mx-auto">
      <div class="flex flex-col gap-2">
        <span class="font-headline font-extrabold text-purple-900 dark:text-purple-100 text-lg uppercase tracking-wider">BSU GAD Office</span>
      </div>
      <div class="flex flex-wrap justify-center gap-8 font-body text-sm leading-relaxed items-center">
        <a class="text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 underline transition-all opacity-80 hover:opacity-100" href="#">Privacy Policy</a>
        <a class="text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 underline transition-all opacity-80 hover:opacity-100" href="#">Terms of Service</a>
        <a class="text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 underline transition-all opacity-80 hover:opacity-100" href="#">University Portal</a>
        <a class="text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 underline transition-all opacity-80 hover:opacity-100" href="#">Contact Directory</a>
        
        <button @click="isStatusModalOpen = true" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-bold transition-all flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
          </span>
          System Status
        </button>
      </div>
      <div class="flex gap-4">
        <a class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-purple-900 hover:bg-primary hover:text-white transition-all" href="#">
          <span class="material-symbols-outlined text-xl">social_leaderboard</span>
        </a>
        <a class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-purple-900 hover:bg-primary hover:text-white transition-all" href="#">
          <span class="material-symbols-outlined text-xl">mail</span>
        </a>
      </div>
    </div>
    
    <SystemStatusModal :isOpen="isStatusModalOpen" @close="isStatusModalOpen = false" />
  </footer>
</template>

<script setup>
import { ref } from 'vue';
import SystemStatusModal from './SystemStatusModal.vue';

const isStatusModalOpen = ref(false);
</script>
