<template>
  <footer class="w-full border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
    <div class="flex flex-col md:flex-row justify-between items-center w-full px-12 py-10 gap-6 max-w-screen-2xl mx-auto">
      <div class="flex flex-col gap-2">
        <span class="font-headline font-extrabold text-purple-900 dark:text-purple-100 text-lg uppercase tracking-wider">BSU GAD Office</span>
      </div>
      <div class="flex flex-wrap justify-center gap-8 font-body text-sm leading-relaxed items-center">
        <button @click="showPrivacyModal = true" class="text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 underline transition-all opacity-80 hover:opacity-100">Privacy Policy</button>
        <button @click="showTermsModal = true" class="text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 underline transition-all opacity-80 hover:opacity-100">Terms of Service</button>
        <router-link class="text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 underline transition-all opacity-80 hover:opacity-100" to="/contact">Contact Directory</router-link>
        
        <button @click="isStatusModalOpen = true" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-bold transition-all flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/30">
          <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
          </span>
          System Status
        </button>
      </div>
    </div>
    
    
    <!-- Privacy Policy Modal -->
    <div v-if="showPrivacyModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 animate-fade-in">
      <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-2xl shadow-2xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
          <h2 class="text-2xl font-headline font-bold text-purple-900 dark:text-purple-100">Privacy Policy</h2>
          <button type="button" @click="showPrivacyModal = false" class="text-slate-400 hover:text-purple-700 transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        
        <div class="p-6 overflow-y-auto font-body text-slate-600 dark:text-slate-300 space-y-4 text-left">
          <p class="font-bold text-slate-800 dark:text-slate-100">Data Privacy Act of 2012 (RA 10173) Notice</p>
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
        <div class="p-6 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 rounded-b-2xl flex justify-end">
          <button @click="showPrivacyModal = false" class="bg-purple-700 text-white px-6 py-2 rounded-full font-bold uppercase transition-all shadow-md hover:bg-purple-800">Close</button>
        </div>
      </div>
    </div>

    <!-- Terms of Service Modal -->
    <div v-if="showTermsModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 animate-fade-in">
      <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-2xl shadow-2xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
          <h2 class="text-2xl font-headline font-bold text-purple-900 dark:text-purple-100">Terms of Service</h2>
          <button type="button" @click="showTermsModal = false" class="text-slate-400 hover:text-purple-700 transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        
        <div class="p-6 overflow-y-auto font-body text-slate-600 dark:text-slate-300 space-y-4 text-left">
          <p>Welcome to the Benguet State University Gender and Development (BSU GAD) AMS Portal.</p>
          <ul class="list-disc pl-5 space-y-2">
            <li><strong>Acceptance of Terms:</strong> By accessing and using this system, you accept and agree to be bound by the terms and provisions of this agreement.</li>
            <li><strong>Use of System:</strong> This system is strictly for authorized personnel, students, and faculty of Benguet State University for the purpose of submitting, monitoring, and managing GAD-related documents.</li>
            <li><strong>User Conduct:</strong> You agree to use the portal only for lawful purposes. You are strictly prohibited from compromising the security of the system, tampering with system resources, or accounts.</li>
            <li><strong>Account Security:</strong> You are responsible for maintaining the confidentiality of your login credentials and for all activities that occur under your account.</li>
            <li><strong>Modifications:</strong> BSU GAD Office reserves the right to modify these Terms of Service at any time without prior notice.</li>
          </ul>
        </div>
        <div class="p-6 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 rounded-b-2xl flex justify-end">
          <button @click="showTermsModal = false" class="bg-purple-700 text-white px-6 py-2 rounded-full font-bold uppercase transition-all shadow-md hover:bg-purple-800">Close</button>
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
