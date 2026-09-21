<template>
  <nav class="navbar" :class="{ 'navbar-hidden': isHidden }">
    <div class="navbar-inner">
      <!-- Mobile Toggle Button -->
      <button class="mobile-toggle" @click="isMenuOpen = !isMenuOpen">
        <span class="material-symbols-outlined">menu</span>
      </button>

      <div class="navbar-brand">
        <router-link to="/" class="brand-container">
          <div class="brand-logos">
            <img src="/images/bsulogo.webp" alt="BSU Logo" class="brand-logo" />
            <img src="/images/gad_logo_enhanced.png" alt="GAD Logo" class="brand-logo" />
          </div>
          <div class="brand-text">
            <span class="brand-subtitle">BENGUET STATE UNIVERSITY</span>
            <span class="brand-title">GAD-AMS</span>
            <span class="brand-desc">GENDER & DEVELOPMENT OFFICE</span>
          </div>
        </router-link>
      </div>

      <!-- Desktop & Mobile Links -->
      <div class="navbar-links" :class="{ 'mobile-menu-open': isMenuOpen }">
        <div class="mobile-menu-header" v-if="isMenuOpen">
          <div class="mobile-menu-brand">
            <div class="brand-logos">
              <img src="/images/bsulogo.webp" alt="BSU Logo" class="brand-logo" />
              <img src="/images/gad_logo_enhanced.png" alt="GAD Logo" class="brand-logo" />
            </div>
            <span class="brand-title">GAD-AMS</span>
          </div>
          <button class="close-menu-btn" @click="isMenuOpen = false">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        
        <div class="mobile-links-container">
          <router-link 
            v-for="item in navItems" 
            :key="item.label"
            :to="item.href"
            class="nav-link"
            :class="$route.path === item.href ? 'nav-link-active' : 'nav-link-inactive'"
            @click="isMenuOpen = false"
          >
            <span class="material-symbols-outlined nav-icon">{{ item.icon }}</span>
            {{ item.label }}
          </router-link>
        </div>

        <div class="mobile-menu-footer" v-if="isMenuOpen">
          <router-link to="/login" class="btn-portal mobile-portal-btn" @click="isMenuOpen = false">
            <span class="material-symbols-outlined">login</span>
            Portal Login
          </router-link>
        </div>
      </div>

      <div class="navbar-actions">
        <template v-if="$route.path === '/login'">
          <span class="already-text hidden md:inline">New to GAD-AMS?</span>
          <router-link to="/register" class="btn-portal">
            <span class="material-symbols-outlined">login</span>
            Sign Up
          </router-link>
        </template>
        <template v-else-if="$route.path === '/register'">
          <span class="already-text hidden md:inline">Already have an account?</span>
          <router-link to="/login" class="btn-portal">
            <span class="material-symbols-outlined">login</span>
            Portal Login
          </router-link>
        </template>
        <template v-else>
          <div class="hidden md:flex items-center gap-4">
            <span class="already-text">Already have an account?</span>
            <router-link to="/login" class="btn-portal">
              <span class="material-symbols-outlined">login</span>
              Portal Login
            </router-link>
          </div>
          <!-- Show only one on very small screens to save space -->
          <div class="md:hidden block">
            <router-link to="/login" class="btn-portal">
              <span class="material-symbols-outlined">login</span>
              Portal Login
            </router-link>
          </div>
        </template>
      </div>
    </div>
    
    <!-- Mobile Backdrop -->
    <div class="mobile-backdrop" v-if="isMenuOpen" @click="isMenuOpen = false"></div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const isMenuOpen = ref(false);
const isHidden = ref(false);

let lastScrollY = 0;
const handleScroll = () => {
  const currentScrollY = window.scrollY;
  // Always show navbar when near the top
  if (currentScrollY < 60) {
    isHidden.value = false;
  } else if (currentScrollY > lastScrollY + 5) {
    // Scrolling down — hide
    isHidden.value = true;
    isMenuOpen.value = false; // close mobile menu too
  } else if (currentScrollY < lastScrollY - 5) {
    // Scrolling up — show
    isHidden.value = false;
  }
  lastScrollY = currentScrollY;
};

onMounted(() => window.addEventListener('scroll', handleScroll, { passive: true }));
onUnmounted(() => window.removeEventListener('scroll', handleScroll));

const navItems = [
  { href: '/', label: 'Home', icon: 'home' },
  { href: '/gad-corner', label: 'GAD Corner', icon: 'campaign' },
  { href: '/contact', label: 'Contact', icon: 'support_agent' }
];
</script>

<style scoped>
.navbar { position: fixed; top: 0; width: 100%; z-index: 50; background: #1a1625; backdrop-filter: blur(12px); box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-bottom: 1px solid rgba(139, 92, 246, 0.08); transition: transform 0.3s ease; }
.navbar-hidden { transform: translateY(-100%); }
.navbar-inner { display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 12px 32px; max-width: 1400px; margin: 0 auto; gap: 32px; }

/* Brand styling */
.navbar-brand { z-index: 52; }
.brand-container { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.brand-logos { display: flex; align-items: center; }
.brand-logo { height: 42px; width: auto; object-fit: contain; }
.brand-logo:nth-child(2) { margin-left: -8px; z-index: 1; height: 44px; }
.brand-text { display: flex; flex-direction: column; justify-content: center; line-height: 1.2; }
.brand-subtitle { font-size: 10px; font-weight: 700; color: #c084fc; letter-spacing: 0.05em; text-transform: uppercase; }
.brand-title { font-size: 20px; font-weight: 900; color: #ffffff; letter-spacing: -0.02em; line-height: 1; }
.brand-desc { font-size: 9px; font-weight: 500; color: #94a3b8; letter-spacing: 0.05em; text-transform: uppercase; margin-top: 2px; }

/* Link styling */
.navbar-links { display: flex; align-items: center; font-weight: 500; }
.mobile-links-container { display: flex; align-items: center; gap: 16px; }
.nav-link { display: flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.2s; font-size: 15px; padding: 10px 16px; border-radius: 8px; }
.nav-icon { font-size: 20px; font-weight: 300; }
.nav-link-active { color: #fff; font-weight: 600; background: rgba(255, 255, 255, 0.08); position: relative; }
.nav-link-active::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 10%;
  width: 80%;
  height: 3px;
  background: linear-gradient(90deg, #c026d3, #ec4899);
  border-radius: 3px 3px 0 0;
  box-shadow: 0 -2px 10px rgba(236, 72, 153, 0.4);
}
.nav-link-inactive { color: #cbd5e1; }
.nav-link-inactive:hover { color: #fff; background: rgba(255, 255, 255, 0.04); }

/* Actions */
.navbar-actions { display: flex; align-items: center; gap: 16px; z-index: 52; }
.already-text { font-size: 13px; color: #94a3b8; font-weight: 400; text-transform: none; letter-spacing: normal; white-space: nowrap; }
.btn-portal { display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #a855f7 0%, #ec4899 100%); color: white; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; transition: all 0.2s; box-shadow: 0 4px 15px rgba(236, 72, 153, 0.2); }
.btn-portal:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(236, 72, 153, 0.3); filter: brightness(1.1); }
.btn-portal:active { transform: translateY(1px); }
.btn-portal .material-symbols-outlined { font-size: 20px; }

/* Mobile Menu Additions */
.mobile-toggle { display: none; background: transparent; border: none; color: #fff; font-size: 24px; cursor: pointer; padding: 4px; }
.mobile-menu-header { display: none; }
.mobile-backdrop { display: none; }

@media (max-width: 1024px) { 
  .already-text { display: none; }
  .brand-subtitle, .brand-desc { display: none; }
  .navbar-inner { padding: 12px 24px; }
}

@media (max-width: 768px) { 
  .navbar-links { 
    position: fixed; top: 0; left: -100%; width: 280px; height: 100vh; 
    background: #1a1625; flex-direction: column; align-items: flex-start; 
    padding: 20px 24px 24px; transition: left 0.3s ease; box-shadow: 4px 0 15px rgba(0,0,0,0.5); z-index: 53;
  }
  .navbar-links.mobile-menu-open { left: 0; }
  
  .mobile-links-container { display: flex; flex-direction: column; gap: 8px; width: 100%; margin-top: 16px; }
  .nav-link { width: 100%; padding: 14px 20px; border-radius: 8px; gap: 16px; font-size: 16px; }
  .nav-link-active::after { display: none; }
  .nav-link-active { background: rgba(168, 85, 247, 0.15); border: 1px solid rgba(236, 72, 153, 0.4); }
  
  .mobile-menu-header { display: flex; justify-content: space-between; align-items: center; width: 100%; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px; }
  .mobile-menu-brand { display: flex; align-items: center; gap: 12px; }
  .close-menu-btn { background: transparent; border: none; color: #fff; font-size: 24px; cursor: pointer; padding: 0; font-weight: bold; }
  .mobile-backdrop { display: block; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 51; backdrop-filter: blur(4px); }
  
  .mobile-menu-footer { margin-top: auto; width: 100%; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 24px; }
  .mobile-portal-btn { width: 100%; justify-content: center; }
  
  .mobile-toggle { display: flex; align-items: center; justify-content: center; font-size: 32px; padding: 0 4px 0 0; }
  
  .navbar-inner { padding: 12px 16px; justify-content: flex-start; gap: 12px; }
  .navbar-brand { margin-right: auto; }
  .brand-logo { height: 32px; }
  .brand-logo:nth-child(2) { height: 34px; margin-left: -6px; }
  .brand-title { font-size: 16px; }
}
@media (max-width: 480px) {
  .btn-portal { padding: 6px 12px; font-size: 12px; gap: 4px; }
  .btn-portal .material-symbols-outlined { font-size: 16px; }
  .navbar-inner { padding: 8px 12px; gap: 8px; }
  .brand-container { gap: 8px; }
}
</style>
