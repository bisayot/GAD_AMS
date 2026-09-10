<template>
  <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-md p-4 animate-fade-in" style="z-index: 10000;">
    <div class="bg-[#1a1a2e] rounded-2xl w-full max-w-4xl shadow-2xl flex flex-col max-h-[90vh] border border-white/10">
      <div class="p-6 border-b border-white/10 flex justify-between items-center bg-[#16213e] rounded-t-2xl">
        <h2 class="text-2xl font-headline font-bold text-purple-400">Data Privacy Statement</h2>
        <button type="button" @click="$emit('close')" class="rounded-full w-8 h-8 flex items-center justify-center transition-all hover:bg-white/20" style="background-color: rgba(255, 255, 255, 0.1);">
          <span class="material-symbols-outlined text-sm" style="color: #ffffff !important;">close</span>
        </button>
      </div>
      
      <div class="p-8 overflow-y-auto font-body text-slate-300 space-y-6 text-left" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
        
        <div>
          <h3 class="text-xl font-bold text-purple-400 mb-2">1. Introduction</h3>
          <p class="leading-relaxed">
            Benguet State University (BSU) is committed to protecting the privacy and confidentiality of personal information collected, stored, and processed through the Gender and Development Activities Management System (GAD-AMS). This Data Privacy Statement outlines our practices regarding the collection, use, and protection of your personal data in compliance with the Data Privacy Act of 2012 (Republic Act No. 10173).
          </p>
        </div>

        <div>
          <h3 class="text-xl font-bold text-purple-400 mb-2">2. Information We Collect</h3>
          <p class="mb-2">The GAD-AMS collects the following types of personal information:</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Full name and contact information (email address, office/department)</li>
            <li>Activity design and accomplishment report submissions</li>
            <li>Budget utilization data and financial information</li>
            <li>Attendance records and participant demographics (sex-disaggregated data)</li>
            <li>User activity logs and system access records</li>
          </ul>
        </div>

        <div>
          <h3 class="text-xl font-bold text-purple-400 mb-2">3. Purpose of Collection</h3>
          <p class="mb-2">Your personal information is collected and processed for the following purposes:</p>
          <ul class="list-disc pl-5 space-y-1">
            <li>Processing and evaluation of GAD activity designs and accomplishment reports</li>
            <li>Generation of GAD Plan and Budget reports</li>
            <li>Monitoring and evaluation of GAD program implementation</li>
            <li>Compliance with government reporting requirements to the Philippine Commission on Women (PCW), Commission on Higher Education (CHED), and Department of Budget and Management (DBM)</li>
            <li>Research and statistical analysis for GAD program improvement</li>
          </ul>
          <p class="mt-4 leading-relaxed">
            Providing this information is necessary to use GAD-AMS. Without it, you will not be able to submit, process, or monitor Activity Designs, Accomplishment Reports, or related GAD records.
          </p>
        </div>

        <div>
          <h3 class="text-xl font-bold text-purple-400 mb-2">4. Data Sharing and Disclosure</h3>
          <p class="leading-relaxed">
            BSU may share aggregated, anonymized data with government agencies such as PCW, CHED, and DBM for reporting purposes. Personal information is not shared with third parties without your explicit consent, unless required by law.
          </p>
        </div>

        <div>
          <h3 class="text-xl font-bold text-purple-400 mb-2">5. Data Security</h3>
          <p class="leading-relaxed">
            BSU implements appropriate organizational, physical, and technical security measures to protect personal information from unauthorized access, alteration, disclosure, or destruction. These measures include access controls, encryption, regular security audits, and staff training on data protection.
          </p>
        </div>

        <div>
          <h3 class="text-xl font-bold text-purple-400 mb-2">6. Data Retention</h3>
          <p class="leading-relaxed">
            Personal information is retained only for as long as necessary to fulfill the purposes for which it was collected, or as required by applicable laws and regulations. Retention periods for system records (including trash bin data, messages, activity logs, and archived documents) are governed by configurable retention settings maintained by the GAD-AMS Administrator. Approved Accomplishment Reports are retained permanently in accordance with government archival requirements; other record types are retained only for their configured retention period before deletion.
          </p>
        </div>

        <div>
          <h3 class="text-xl font-bold text-purple-400 mb-2">7. Contact Information</h3>
          <p class="mb-3">For questions, concerns, or requests regarding your personal data, please contact:</p>
          <div class="bg-black/30 p-4 rounded-lg border border-purple-500/20 inline-block">
            <p class="font-bold text-purple-300">Data Protection Officer</p>
            <p>Benguet State University</p>
            <p>La Trinidad, Benguet 2601</p>
            <p class="text-purple-400 mt-2 font-semibold">Email: dpo@bsu.edu.ph</p>
            <p>Tel: (074) 422-2401</p>
          </div>
        </div>

        <div>
          <h3 class="text-xl font-bold text-purple-400 mb-2">8. Updates to this Statement</h3>
          <p class="leading-relaxed mb-2">
            This Data Privacy Statement may be updated periodically. Users will be notified of significant changes through the system or via email. The effective date of the current version is displayed below.
          </p>
          <p class="text-purple-400 font-bold italic">Effective Date: September 1, 2026</p>
        </div>

      </div>

      <div class="p-6 border-t border-white/10 bg-[#16213e] rounded-b-2xl flex justify-end gap-4">
        <button v-if="showAccept" type="button" @click="$emit('accept')" class="border border-purple-500/50 px-6 py-2 rounded-full font-bold uppercase transition-all shadow-md hover:shadow-lg hover:opacity-90" style="background: linear-gradient(to bottom right, #9333ea, #6b21a8); color: #ffffff !important;">
          I Understand & Agree
        </button>
        <button v-else type="button" @click="$emit('close')" class="border border-slate-500/50 px-6 py-2 rounded-full font-bold uppercase transition-all shadow-md hover:shadow-lg hover:opacity-90" style="background: linear-gradient(to bottom right, #475569, #1e293b); color: #ffffff !important;">
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  showAccept: {
    type: Boolean,
    default: false
  }
});
defineEmits(['close', 'accept']);
</script>
