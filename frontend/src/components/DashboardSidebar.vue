<template>
  <aside class="w-64 bg-[#1a1a2e] text-white fixed h-full flex flex-col p-6 shadow-xl z-50">
    <div class="mb-10 flex items-center gap-3 border-b border-white/10 pb-6">
      <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
        <span class="material-symbols-outlined text-white">account_balance</span>
      </div>
      <div>
        <h2 class="font-headline font-bold text-lg leading-none">GAD-IMS</h2>
        <span class="text-[10px] text-purple-400 font-bold uppercase tracking-widest">{{ roleLabel }}</span>
      </div>
    </div>

    <nav class="flex-grow space-y-2 overflow-y-auto custom-scrollbar">
      <router-link 
        v-for="item in menuItems" 
        :key="item.label"
        :to="item.href"
        class="flex items-center gap-3 p-3 rounded-xl transition-all duration-200"
        :class="$route.path === item.href ? 'bg-primary/20 text-white font-bold' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
      >
        <span class="material-symbols-outlined text-xl">{{ item.icon }}</span>
        <span class="text-sm">{{ item.label }}</span>
      </router-link>
    </nav>

    <div class="mt-auto pt-6 border-t border-white/10">
      <button @click="$emit('logout')" class="flex items-center gap-3 p-3 text-slate-400 hover:text-red-400 transition-colors w-full text-left">
        <span class="material-symbols-outlined text-xl">logout</span>
        <span class="text-sm font-bold">Sign Out</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
defineProps({
  roleLabel: { type: String, default: 'User' },
  menuItems: { type: Array, required: true }
});

defineEmits(['logout']);
</script>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(255,255,255,0.05);
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #b979cc;
  border-radius: 10px;
}
</style>
