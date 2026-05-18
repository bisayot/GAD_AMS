<template>
  <div class="staff-dashboard bg-background min-h-screen flex">
    <DashboardSidebar 
      roleLabel="GAD Staff" 
      :menuItems="staffMenu" 
      @logout="handleLogout" 
    />

    <div class="flex-grow ml-64 flex flex-col">
      <DashboardHeader 
        title="Staff Overview" 
        context="GAD Operations" 
        :username="user?.username" 
      />

      <main class="p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Main Content -->
          <div class="lg:col-span-2 space-y-8">
            <header class="mb-8">
              <h1 class="text-3xl font-headline font-extrabold text-primary">Operations Center</h1>
              <p class="text-on-surface-variant text-sm mt-1">Review submissions and monitor institutional progress.</p>
            </header>

            <!-- Monitoring Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/15">
                <div class="flex justify-between items-center mb-6">
                  <h3 class="font-headline font-bold text-lg text-primary">Pending Reviews</h3>
                  <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">12 New</span>
                </div>
                <div class="space-y-4">
                  <div v-for="n in 3" :key="n" class="flex items-center justify-between p-3 rounded-xl hover:bg-surface-container-low transition-colors cursor-pointer border border-transparent hover:border-outline-variant/10">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-blue-600 text-sm">assignment</span>
                      </div>
                      <div>
                        <h4 class="text-xs font-bold text-on-surface">Plan Revision #{{ 2025 + n }}</h4>
                        <p class="text-[10px] text-on-surface-variant">College of Engineering</p>
                      </div>
                    </div>
                    <span class="text-[10px] text-on-surface-variant">2h ago</span>
                  </div>
                </div>
              </div>

              <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/15">
                <div class="flex justify-between items-center mb-6">
                  <h3 class="font-headline font-bold text-lg text-primary">Technical Assistance</h3>
                  <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">5 Open</span>
                </div>
                <div class="space-y-4">
                  <div v-for="n in 3" :key="n" class="flex items-center justify-between p-3 rounded-xl hover:bg-surface-container-low transition-colors cursor-pointer border border-transparent hover:border-outline-variant/10">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-600 text-sm">support_agent</span>
                      </div>
                      <div>
                        <h4 class="text-xs font-bold text-on-surface">Budget Consultation</h4>
                        <p class="text-[10px] text-on-surface-variant">Admin Office</p>
                      </div>
                    </div>
                    <span class="text-[10px] text-on-surface-variant">4h ago</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Task Board Placeholder -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/15">
              <h3 class="font-headline font-bold text-lg text-primary mb-6">Recent Reports Processing</h3>
              <div class="space-y-6">
                <div v-for="report in reports" :key="report.id" class="relative pl-6 border-l-2 border-outline-variant/20 pb-6 last:pb-0">
                  <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-white border-2 border-primary"></div>
                  <div class="flex justify-between items-start">
                    <div>
                      <h4 class="text-sm font-bold text-on-surface">{{ report.title }}</h4>
                      <p class="text-xs text-on-surface-variant mt-1">{{ report.desc }}</p>
                    </div>
                    <span class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">{{ report.time }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="space-y-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/15">
              <h3 class="font-headline font-bold text-lg text-primary mb-6">Quick Filters</h3>
              <div class="space-y-3">
                <button v-for="filter in filters" :key="filter" class="w-full text-left px-4 py-3 rounded-xl text-xs font-bold text-on-surface-variant hover:bg-primary/5 hover:text-primary transition-all border border-transparent hover:border-primary/10">
                  {{ filter }}
                </button>
              </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/15">
              <h3 class="font-headline font-bold text-lg text-primary mb-6">System Health</h3>
              <div class="space-y-4">
                <div class="flex justify-between items-center text-xs">
                  <span class="text-on-surface-variant">Server Status</span>
                  <span class="text-green-600 font-bold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-green-600 rounded-full animate-pulse"></span>
                    Operational
                  </span>
                </div>
                <div class="flex justify-between items-center text-xs">
                  <span class="text-on-surface-variant">Database Sync</span>
                  <span class="text-blue-600 font-bold">100%</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import DashboardSidebar from '../components/DashboardSidebar.vue';
import DashboardHeader from '../components/DashboardHeader.vue';

const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const staffMenu = [
  { label: 'Dashboard', icon: 'dashboard', href: '/staff/dashboard' },
  { label: 'Review Submissions', icon: 'rate_review', href: '/staff/reviews' },
  { label: 'Budget Utilization', icon: 'payments', href: '/staff/budget' },
  { label: 'Annual Reports', icon: 'description', href: '/staff/reports' },
  { label: 'Mandates', icon: 'gavel', href: '/staff/mandates' },
  { label: 'User Manual', icon: 'menu_book', href: '/staff/user-manual' }
];

const reports = [
  { id: 1, title: 'Consolidated GPB 2025', desc: 'Final review stage for board approval', time: '10:30 AM' },
  { id: 2, title: 'Compliance Check: CAS', desc: 'Minor revisions pending from department', time: '09:15 AM' },
  { id: 3, title: 'Accomplishment Verification', desc: 'Verifying evidence for Q3 activities', time: 'Yesterday' }
];

const filters = [
  'All Submissions',
  'Awaiting My Review',
  'Approved this Week',
  'Revision Overdue',
  'Archived Reports'
];

const handleLogout = async () => {
  try {
    await axios.get('http://localhost/GAD_v12/backend/public/api/logout');
    localStorage.removeItem('user');
    router.push('/login');
  } catch (err) {
    localStorage.removeItem('user');
    router.push('/login');
  }
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'gad_staff') {
    router.push('/login');
  }
});
</script>

<style scoped>
</style>
