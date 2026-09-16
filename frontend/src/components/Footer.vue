<template>
  <footer class="w-full border-t border-white/10" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
    <div class="flex flex-col md:flex-row justify-between items-center w-full px-12 py-10 gap-6 max-w-screen-2xl mx-auto">
      <div class="flex flex-col gap-1">
        <span class="font-headline font-extrabold text-purple-400 text-lg uppercase tracking-wider">BSU GAD-AMS</span>
        <span class="text-white/60 text-xs font-body tracking-wide">Copyright &copy; {{ new Date().getFullYear() }} BSU &ndash; Gender and Development Office</span>
      </div>
      <div class="flex flex-wrap justify-center gap-8 font-body text-sm leading-relaxed items-center">
        <button @click="showPrivacyModal = true" class="!text-white hover:!text-purple-400 transition-all opacity-80 hover:opacity-100 font-bold">Privacy Policy</button>
        <button @click="showTermsModal = true" class="!text-white hover:!text-purple-400 transition-all opacity-80 hover:opacity-100 font-bold">Terms of Service</button>
        <router-link class="!text-white hover:!text-purple-400 transition-all opacity-80 hover:opacity-100 font-bold" to="/contact">Contact</router-link>
        
        <div class="h-4 w-px bg-white/20 hidden md:block"></div>
        <a href="https://www.facebook.com/gad.bsu.1/" target="_blank" rel="noopener noreferrer" class="!text-white hover:!text-[#1877F2] transition-all opacity-80 hover:opacity-100 flex items-center gap-2 font-bold" title="Official Facebook Page">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
          </svg>
          <span class="hidden sm:inline">Facebook</span>
        </a>
      </div>
    </div>
    
    <!-- Privacy Policy Modal -->
    <PrivacyPolicyModal 
      v-if="showPrivacyModal" 
      @close="showPrivacyModal = false" 
    />

    <!-- Terms of Service Modal -->
    <div v-if="showTermsModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-md p-4 animate-fade-in">
      <div class="bg-[#1a1a2e] rounded-2xl w-full max-w-2xl shadow-2xl flex flex-col max-h-[90vh] border border-white/10">
        <div class="p-6 border-b border-white/10 flex justify-between items-center">
          <h2 class="text-2xl font-headline font-bold text-purple-400">Terms of Service</h2>
          <button type="button" @click="showTermsModal = false" class="!text-white hover:!text-purple-400 rounded-full w-8 h-8 flex items-center justify-center transition-all" style="background-color: rgba(255, 255, 255, 0.1);">
            <span class="material-symbols-outlined text-sm !text-white">close</span>
          </button>
        </div>
        
        <div class="p-6 overflow-y-auto font-body text-slate-300 space-y-4 text-left">
          <p>Welcome to the Benguet State University Gender and Development (BSU GAD) AMS Portal.</p>
          <ul class="list-disc pl-5 space-y-2">
            <li><strong>Acceptance of Terms:</strong> By accessing and using this system, you accept and agree to be bound by the terms and provisions of this agreement.</li>
            <li><strong>Use of System:</strong> This system is strictly for authorized personnel, students, and faculty of Benguet State University for the purpose of submitting, monitoring, and managing GAD-related documents.</li>
            <li><strong>User Conduct:</strong> You agree to use the portal only for lawful purposes. You are strictly prohibited from compromising the security of the system, tampering with system resources, or accounts.</li>
            <li><strong>Account Security:</strong> You are responsible for maintaining the confidentiality of your login credentials and for all activities that occur under your account.</li>
            <li><strong>Modifications:</strong> BSU GAD Office reserves the right to modify these Terms of Service at any time without prior notice.</li>
          </ul>
        </div>
        <div class="p-6 border-t border-white/10 bg-white/5 rounded-b-2xl flex justify-end">
          <button @click="showTermsModal = false" class="bg-gradient-to-br from-purple-600 to-purple-800 border border-purple-500/50 !text-white px-6 py-2 rounded-full font-bold uppercase transition-all shadow-md hover:shadow-lg hover:opacity-90">Close</button>
        </div>
      </div>
    </div>

  </footer>
</template>

<script setup>
import { ref } from 'vue';
import PrivacyPolicyModal from './PrivacyPolicyModal.vue';

const showPrivacyModal = ref(false);
const showTermsModal = ref(false);
</script>
