<template>
  <footer class="w-full border-t border-white/10" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 items-start w-full px-4 sm:px-6 md:px-8 lg:px-12 py-8 gap-8 lg:gap-10 max-w-screen-2xl mx-auto">

      <!-- Brand & Social -->
      <div class="flex flex-col gap-4 min-w-0 sm:col-span-2 lg:col-span-1">
        <div class="flex items-center gap-0 mb-2">
          <img src="/images/bsulogo.webp" alt="BSU Logo" class="h-12 w-auto" />
          <img src="/images/gad_logo_enhanced.png" alt="GAD Logo" class="h-12 w-auto" />
        </div>
        <div>
          <h3 class="font-headline font-bold text-purple-400 text-sm uppercase tracking-widest mb-1 break-words">Benguet State University</h3>
          <h2 class="font-headline font-bold text-white text-xl leading-tight break-words">Gender and Development Office</h2>
          <p class="text-white/60 text-xs font-body tracking-wide mt-1">GAD Activities Management System (GAD-AMS)</p>
        </div>

        <a href="https://www.facebook.com/gad.bsu.1/" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex w-fit max-w-full items-center px-5 py-2.5 bg-[#1877F2]/10 hover:bg-[#1877F2]/20 border border-[#1877F2]/30 rounded-lg text-white transition-all shadow-sm shadow-[#1877F2]/10">
          <span class="font-bold text-sm tracking-wide break-words">Official Facebook Page</span>
        </a>
      </div>

      <!-- Footer Links -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 lg:gap-x-8 gap-y-8 min-w-0 sm:col-span-2 lg:col-span-2">
        <div class="flex min-w-0 flex-col gap-4">
          <h4 class="font-headline font-bold text-purple-400 uppercase tracking-widest text-sm">Website Links</h4>
          <nav class="flex min-w-0 flex-col gap-3" aria-label="Website links">
            <router-link to="/gad-corner#lactation-rooms" class="text-left !text-white/80 hover:!text-purple-400 transition-all font-medium break-words">
              Breastfeeding / Lactation Rooms
            </router-link>
            <router-link to="/gad-corner#childminding-center" class="text-left !text-white/80 hover:!text-purple-400 transition-all font-medium break-words">
              Childminding Center
            </router-link>
            <router-link to="/gad-corner#bulletin" class="text-left !text-white/80 hover:!text-purple-400 transition-all font-medium break-words">
              Bulletin
            </router-link>
            <router-link to="/gad-corner#accomplishment-reports" class="text-left !text-white/80 hover:!text-purple-400 transition-all font-medium break-words">
              Accomplishment Reports
            </router-link>
            <router-link to="/#platform-background" class="text-left !text-white/80 hover:!text-purple-400 transition-all font-medium break-words">
              Platform Background
            </router-link>
          </nav>
        </div>

        <div class="flex min-w-0 flex-col gap-4">
          <h4 class="font-headline font-bold text-purple-400 uppercase tracking-widest text-sm">System Policies</h4>
          <nav class="flex min-w-0 flex-col gap-3" aria-label="System policies">
            <button @click="showPrivacyModal = true" class="text-left !text-white/80 hover:!text-purple-400 transition-all font-medium break-words">
              Privacy Policy
            </button>
            <button @click="showTermsModal = true" class="text-left !text-white/80 hover:!text-purple-400 transition-all font-medium break-words">
              Terms of Service
            </button>
          </nav>
        </div>

        <div class="flex min-w-0 flex-col gap-4">
          <h4 class="font-headline font-bold text-purple-400 uppercase tracking-widest text-sm">External Links</h4>
          <nav class="flex min-w-0 flex-col gap-3" aria-label="External links">
            <a href="https://privacy.gov.ph/data-privacy-act/" target="_blank" rel="noopener noreferrer" class="text-left !text-white/80 hover:!text-purple-400 transition-all font-medium break-words">
              RA 10173 (DPA 2012)
            </a>
            <a href="https://pcw.gov.ph/" target="_blank" rel="noopener noreferrer" class="text-left !text-white/80 hover:!text-purple-400 transition-all font-medium break-words">
              Philippine Commission on Women
            </a>
            <a href="https://bsu.edu.ph/" target="_blank" rel="noopener noreferrer" class="text-left !text-white/80 hover:!text-purple-400 transition-all font-medium break-words">
              Benguet State University
            </a>
          </nav>
        </div>
      </div>

      <!-- Office Location -->
      <div class="flex min-w-0 flex-col gap-4 sm:col-span-2 lg:col-span-1">
        <h4 class="font-headline font-bold text-purple-400 uppercase tracking-widest text-sm flex items-center gap-2 border-l-2 border-purple-400 pl-3 mb-2">Office Location</h4>
        <div class="flex gap-3 text-white/80 text-sm leading-relaxed">
          <span class="break-words">Behind LANDBANK, BSU Compound, Km. 5, La Trinidad, 2601 Benguet, Philippines</span>
        </div>
        <div class="flex gap-3 text-white/80 text-sm leading-relaxed">
          <span>(074) 422-2127</span>
        </div>
        <div class="flex gap-3 text-white/80 text-sm leading-relaxed">
          <span>gad.office@bsu.edu.ph</span>
        </div>
        <div class="flex gap-3 text-white/80 text-sm leading-relaxed">
          <span>Mon - Fri: 8:00 AM - 5:00 PM</span>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="w-full border-t border-white/5 py-6">
      <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 md:px-8 lg:px-12 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-xs font-body text-white/40">
        <div class="flex flex-wrap items-center gap-3">
          <span class="break-words">&copy; {{ new Date().getFullYear() }} Benguet State University Gender and Development Office.</span>
          <span class="w-1 h-1 rounded-full bg-white/20 hidden md:block"></span>
          <span class="hidden md:block">All rights reserved.</span>
        </div>
        <div class="flex items-center gap-6">
          <span>Republic of the Philippines &bull; SUC GFPS</span>
        </div>
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
