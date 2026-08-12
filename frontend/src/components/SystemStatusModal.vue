<template>
  <div v-if="isOpen" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-md" @click="$emit('close')"></div>
    
    <div class="relative bg-[#1a1a2e] border border-white/10 rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden animate-fade-in-up">
      <!-- Header -->
      <div class="flex justify-between items-center p-6 border-b border-white/10">
        <h2 class="text-2xl font-headline font-bold text-white flex items-center gap-3">
          <span class="material-symbols-outlined text-purple-400">monitor_heart</span>
          System Infrastructure Status
        </h2>
        <button @click="$emit('close')" class="!text-white hover:!text-purple-400 rounded-full w-8 h-8 flex items-center justify-center transition-all" style="background-color: rgba(255, 255, 255, 0.1);">
          <span class="material-symbols-outlined text-sm !text-white">close</span>
        </button>
      </div>

      <!-- Content -->
      <div class="p-8 overflow-y-auto font-body text-slate-300">
        <div class="status-header mb-8 text-center flex flex-col items-center">
          <div class="flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-emerald-400 bg-emerald-500/10 px-5 py-2 rounded-full mb-4 border border-emerald-500/20">
            <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.6)]"></div>
            All Systems Operational
          </div>
          <p class="text-slate-300 max-w-2xl text-base leading-relaxed">
            This platform is proudly hosted using industry-leading cloud services. To keep this community resource free and accessible, we utilize managed free-tier hosting for our infrastructure.
          </p>
        </div>

        <!-- Scheduled Maintenances Notices -->
        <div v-if="maintenances.length > 0" class="mb-10 bg-white/5 p-6 rounded-xl border border-blue-500/30 border-l-4 border-l-blue-500 shadow-sm">
          <h3 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-400">info</span>
            Scheduled Maintenance Notices
          </h3>
          <div class="flex flex-col gap-4">
            <div v-for="(notice, idx) in maintenances" :key="idx" class="flex gap-4 p-4 bg-black/20 rounded-lg border border-white/5">
              <div class="text-blue-400 flex items-center justify-center w-10 h-10 bg-blue-500/10 rounded-full shrink-0">
                <span class="material-symbols-outlined">build</span>
              </div>
              <div>
                <h4 class="font-bold text-white mb-1">{{ notice.service }} - {{ notice.name }}</h4>
                <p class="text-sm text-slate-400 mb-3">
                  <strong>Scheduled For:</strong> {{ new Date(notice.scheduled_for).toLocaleString() }} 
                  to {{ new Date(notice.scheduled_until).toLocaleString() }}
                </p>
                <div class="text-sm text-slate-300 leading-relaxed pl-3 border-l-2 border-slate-600" v-if="notice.incident_updates && notice.incident_updates.length > 0">
                  <p v-html="notice.incident_updates[0].body" class="prose prose-sm prose-invert max-w-none prose-a:text-blue-400"></p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
          <div class="flex gap-4 bg-white/5 p-5 border border-white/10 rounded-xl items-start">
            <div class="w-12 h-12 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center shrink-0">
              <span class="material-symbols-outlined">web</span>
            </div>
            <div>
              <h4 class="font-bold text-white mb-1">Frontend</h4>
              <p class="text-sm text-slate-400 leading-relaxed">Global CDN edge network. Always online with zero cold starts.</p>
            </div>
          </div>
          
          <div class="flex gap-4 bg-white/5 p-5 border border-white/10 rounded-xl items-start">
            <div class="w-12 h-12 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center shrink-0">
              <span class="material-symbols-outlined">dns</span>
            </div>
            <div>
              <h4 class="font-bold text-white mb-1">Database</h4>
              <p class="text-sm text-slate-400 leading-relaxed">Dedicated MySQL instance. Highly available and reliable.</p>
            </div>
          </div>
          
          <div class="flex gap-4 bg-white/5 p-5 border border-white/10 rounded-xl items-start">
            <div class="w-12 h-12 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center shrink-0">
              <span class="material-symbols-outlined">cloud</span>
            </div>
            <div>
              <h4 class="font-bold text-white mb-1">Network</h4>
              <p class="text-sm text-slate-400 leading-relaxed">Advanced routing and DDoS protection active globally.</p>
            </div>
          </div>
          
          <div class="flex gap-4 bg-amber-900/10 p-5 border border-amber-500/30 rounded-xl items-start">
            <div class="w-12 h-12 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
              <span class="material-symbols-outlined">schedule</span>
            </div>
            <div>
              <h4 class="font-bold text-white mb-1">Backend API</h4>
              <p class="text-sm text-slate-400 leading-relaxed">
                <strong>Standby Mode Active:</strong> Server sleeps after 15 mins of inactivity. <span class="text-amber-400 font-semibold">The first login request may take ~50 seconds to wake the server up.</span>
              </p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Footer -->
      <div class="p-6 border-t border-white/10 bg-white/5 flex justify-end">
        <button @click="$emit('close')" class="bg-gradient-to-br from-purple-600 to-purple-800 border border-purple-500/50 !text-white px-6 py-2 rounded-full font-bold uppercase transition-all shadow-md hover:shadow-lg hover:opacity-90">
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close']);

const maintenances = ref([]);

const fetchStatusPages = async () => {
  const services = [
    { name: 'Frontend', url: 'https://www.vercel-status.com/api/v2/summary.json' },
    { name: 'Database', url: 'https://status.aiven.io/api/v2/summary.json' },
    { name: 'Network', url: 'https://www.cloudflarestatus.com/api/v2/summary.json' },
    { name: 'Backend API', url: 'https://status.render.com/api/v2/summary.json' }
  ];

  for (const service of services) {
    try {
      const res = await fetch(service.url);
      const data = await res.json();
      
      if (data.scheduled_maintenances && data.scheduled_maintenances.length > 0) {
        const upcoming = data.scheduled_maintenances.filter(m => ['scheduled', 'in_progress'].includes(m.status));
        upcoming.forEach(m => {
          maintenances.value.push({
            service: service.name,
            name: m.name,
            status: m.status,
            scheduled_for: m.scheduled_for,
            scheduled_until: m.scheduled_until,
            incident_updates: m.incident_updates
          });
        });
      }
    } catch (e) {
      console.error(`Failed to fetch status for ${service.name}`, e);
    }
  }
  
  maintenances.value.sort((a, b) => new Date(a.scheduled_for) - new Date(b.scheduled_for));
};

watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    document.body.style.overflow = 'hidden';
    if (maintenances.value.length === 0) {
      fetchStatusPages();
    }
  } else {
    document.body.style.overflow = 'auto';
  }
});

onUnmounted(() => {
  document.body.style.overflow = 'auto';
});
</script>

<style scoped>
.animate-fade-in-up {
  animation: fadeInUp 0.3s ease-out forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px) scale(0.98);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
</style>
