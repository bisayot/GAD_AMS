<template>
  <footer class="w-full border-t border-white/10" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
    <div class="flex flex-col md:flex-row justify-between items-center w-full px-12 py-10 gap-6 max-w-screen-2xl mx-auto">
      <div class="flex flex-col gap-2">
        <span class="font-headline font-extrabold text-purple-400 text-lg uppercase tracking-wider">BSU GAD Office</span>
      </div>
      <div class="flex flex-wrap justify-center gap-8 font-body text-sm leading-relaxed items-center">
        <button @click="showPrivacyModal = true" class="!text-white hover:!text-purple-400 transition-all opacity-80 hover:opacity-100 font-bold">Privacy Policy</button>
        <button @click="showTermsModal = true" class="!text-white hover:!text-purple-400 transition-all opacity-80 hover:opacity-100 font-bold">Terms of Service</button>
        <router-link class="!text-white hover:!text-purple-400 transition-all opacity-80 hover:opacity-100 font-bold" to="/contact">Contact Directory</router-link>
        
        <button @click="isStatusModalOpen = true" class="!text-white hover:!text-emerald-300 font-bold transition-all flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-900/30 border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.1)]">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
          </span>
          System Status
        </button>
      </div>
    </div>
    
    <!-- Privacy Policy Modal -->
    <div v-if="showPrivacyModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-md p-4 animate-fade-in">
      <div class="bg-[#1a1a2e] rounded-2xl w-full max-w-2xl shadow-2xl flex flex-col max-h-[90vh] border border-white/10">
        <div class="p-6 border-b border-white/10 flex justify-between items-center">
          <h2 class="text-2xl font-headline font-bold text-purple-400">Privacy Policy</h2>
          <button type="button" @click="showPrivacyModal = false" class="!text-white hover:!text-purple-400 rounded-full w-8 h-8 flex items-center justify-center transition-all" style="background-color: rgba(255, 255, 255, 0.1);">
            <span class="material-symbols-outlined text-sm !text-white">close</span>
          </button>
        </div>
        
        <div class="p-6 overflow-y-auto font-body text-slate-300 space-y-4 text-left">
          <p class="font-bold text-white">Data Privacy Act of 2012 (RA 10173) Notice</p>
          <p>
            By using the Benguet State University Gender and Development (BSU GAD) Portal, you acknowledge the following terms regarding the collection, use, and processing of your personal data:
          </p>
          <ul class="list-disc pl-5 space-y-2">
            <li><strong>Purpose of Data Collection:</strong> Your personal information (e.g., name, institutional email, office/department, and role) will be collected and processed solely for the purpose of managing user access, facilitating system functionalities, and maintaining official records within the GAD Portal.</li>
            <li><strong>Data Protection:</strong> We are committed to safeguarding your personal information. Appropriate security measures are in place to prevent unauthorized access, disclosure, modification, or destruction of your data.</li>
            <li><strong>Data Sharing:</strong> Your personal data will only be accessible to authorized personnel of the BSU GAD Office and system administrators. It will not be shared with third parties without your explicit consent, except as required by law.</li>
            <li><strong>User Rights:</strong> You have the right to access, correct, or request the deletion of your personal data stored in the portal.</li>
          </ul>
        </div>
        <div class="p-6 border-t border-white/10 bg-white/5 rounded-b-2xl flex justify-end">
          <button @click="showPrivacyModal = false" class="bg-gradient-to-br from-purple-600 to-purple-800 border border-purple-500/50 !text-white px-6 py-2 rounded-full font-bold uppercase transition-all shadow-md hover:shadow-lg hover:opacity-90">Close</button>
        </div>
      </div>
    </div>

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

    <SystemStatusModal :isOpen="isStatusModalOpen" @close="isStatusModalOpen = false" />
  </footer>
</template>

<script setup>
import { ref } from 'vue';
import SystemStatusModal from './SystemStatusModal.vue';

const isStatusModalOpen = ref(false);
const showPrivacyModal = ref(false);
const showTermsModal = ref(false);
</script>
