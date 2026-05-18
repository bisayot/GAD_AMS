import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import AboutView from '../views/AboutView.vue'
import ResourcesView from '../views/ResourcesView.vue'
import GADCornerView from '../views/GADCornerView.vue'
import ContactView from '../views/ContactView.vue'
import AdminDashboard from '../views/AdminDashboard.vue'
import AdminAnnualReport from '../views/AdminAnnualReport.vue'
import CollegeDashboard from '../views/CollegeDashboard.vue'
import StaffDashboard from '../views/StaffDashboard.vue'
import PlaceholderView from '../views/PlaceholderView.vue'

const routes = [
  { path: '/', name: 'home', component: HomeView },
  { path: '/login', name: 'login', component: LoginView },
  { path: '/register', name: 'register', component: RegisterView },
  { path: '/about', name: 'about', component: AboutView },
  { path: '/resources', name: 'resources', component: ResourcesView },
  { path: '/gad-corner', name: 'gad-corner', component: GADCornerView },
  { path: '/contact', name: 'contact', component: ContactView },
  
  // Admin Routes
  { path: '/admin/dashboard', name: 'admin-dashboard', component: AdminDashboard },
  { path: '/admin/annual-report', name: 'admin-annual-report', component: AdminAnnualReport },
  { path: '/admin/gad-plan-budget', name: 'admin-gad-plan-budget', component: PlaceholderView },
  { path: '/admin/mandates', name: 'admin-mandates', component: PlaceholderView },
  { path: '/admin/archive', name: 'admin-archive', component: PlaceholderView },
  { path: '/admin/user-manual', name: 'admin-user-manual', component: PlaceholderView },

  // College Routes
  { path: '/college/dashboard', name: 'college-dashboard', component: CollegeDashboard },
  { path: '/college/submit-design', name: 'college-submit-design', component: PlaceholderView },
  { path: '/college/submit-accomplishment', name: 'college-submit-accomplishment', component: PlaceholderView },
  { path: '/college/tech-assist', name: 'college-tech-assist', component: PlaceholderView },
  { path: '/college/mandates', name: 'college-mandates', component: PlaceholderView },
  { path: '/college/archive', name: 'college-archive', component: PlaceholderView },

  // Staff Routes
  { path: '/staff/dashboard', name: 'staff-dashboard', component: StaffDashboard },
  { path: '/staff/reviews', name: 'staff-reviews', component: PlaceholderView },
  { path: '/staff/budget', name: 'staff-budget', component: PlaceholderView },
  { path: '/staff/reports', name: 'staff-reports', component: PlaceholderView },
  { path: '/staff/mandates', name: 'staff-mandates', component: PlaceholderView },
  { path: '/staff/user-manual', name: 'staff-user-manual', component: PlaceholderView },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
