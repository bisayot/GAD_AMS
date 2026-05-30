<template>
  <div class="min-h-screen bg-[#f8f9fb] antialiased">
    <main class="pl-64 pt-16">
      <div class="p-10 flex gap-8">
        <section class="flex-[0.6] bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="p-8 border-b border-slate-100">
            <div class="flex items-center gap-2 mb-4">
              <div class="flex items-center gap-2 bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full border border-emerald-100">
                <div class="w-2 h-2 rounded-full bg-emerald-600"></div>
                <span class="text-[9px] font-black uppercase tracking-widest">Completed</span>
              </div>
              <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">{{ report.controlNumber }}</span>
            </div>
            <h2 class="font-serif text-[28px] font-bold leading-tight mb-4 text-black">{{ report.title }}</h2>
          </div>

          <div class="p-8 space-y-6">
            <div class="bg-slate-50 p-5 rounded-xl border border-slate-100">
              <h3 class="font-bold text-[11px] uppercase tracking-widest text-slate-500 mb-4">Report Details</h3>
              <p class="text-[11px] text-slate-700 leading-relaxed">{{ report.summary }}</p>
            </div>
          </div>
        </section>

        <section class="flex-[0.4] space-y-6 sticky top-20 self-start">
          <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
            <h3 class="text-[11px] font-bold uppercase text-slate-800 mb-6">Status History</h3>
            <div class="space-y-6">
              <div v-for="step in statusHistory" :key="step.label" class="relative pl-7">
                <div class="absolute left-0 top-0.5 w-4 h-4 rounded-full" :style="{ backgroundColor: step.color }"></div>
                <p class="text-[11px] font-bold" :style="{ color: step.color }">{{ step.label }}</p>
                <p class="text-[9px] text-slate-500">{{ step.date }}</p>
              </div>
            </div>
          </div>

          <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
            <div class="flex gap-3">
              <span class="text-2xl">ℹ️</span>
              <div>
                <p class="text-[11px] font-bold text-blue-700">Archived Report</p>
                <p class="text-[9px] text-blue-600">This report has been moved to the system archives.</p>
              </div>
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const report = ref({
  controlNumber: 'AR-2026-088',
  title: 'Lorem Ipsum Dolor Sit Amet Consectetur Adipiscing Elit',
  summary: 'Detailed summary of the accomplishment report content goes here...'
});

const statusHistory = ref([
  { label: 'Submitted for Review', date: 'Mar 6, 2026 • 09:00 AM', color: '#990dd1' },
  { label: 'Completed', date: 'Mar 25, 2026 • 10:15 AM', color: '#3f6516' }
]);
</script>

<style scoped>
/* Scoped styles maintain the clean look from the PHP file */
</style>