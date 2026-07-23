<template>
  <nav class="navbar">
    <div class="navbar-inner">
      <div class="navbar-brand">
        <router-link to="/">BSU GAD Corner</router-link>
      </div>

      <!-- Desktop & Mobile Links -->
      <div class="navbar-links" :class="{ 'mobile-menu-open': isMenuOpen }">
        <div class="mobile-menu-header" v-if="isMenuOpen">
          <span class="mobile-menu-title">Menu</span>
          <button class="close-menu-btn" @click="isMenuOpen = false">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        <router-link 
          v-for="item in navItems" 
          :key="item.label"
          :to="item.href"
          class="nav-link"
          :class="$route.path === item.href ? 'nav-link-active' : 'nav-link-inactive'"
          @click="isMenuOpen = false"
        >
          {{ item.label }}
        </router-link>
      </div>

      <div class="navbar-actions">
        <div class="search-wrapper">
          <span class="material-symbols-outlined search-icon">search</span>
          <input class="search-input" placeholder="Search resources..." type="text"/>
        </div>

        <template v-if="$route.path === '/login'">
          <router-link to="/register" class="btn-signup">Sign Up</router-link>
        </template>
        <template v-else>
          <span class="already-text">Already have an account?</span>
          <router-link to="/login" class="btn-login">Portal Login</router-link>
        </template>
        
        <!-- Mobile Toggle Button -->
        <button class="mobile-toggle" @click="isMenuOpen = !isMenuOpen">
          <span class="material-symbols-outlined">menu</span>
        </button>
      </div>
    </div>
    
    <!-- Mobile Backdrop -->
    <div class="mobile-backdrop" v-if="isMenuOpen" @click="isMenuOpen = false"></div>
  </nav>
</template>

<script setup>
import { ref } from 'vue';

const isMenuOpen = ref(false);

const navItems = [
  { href: '/', label: 'Home' },
  // { href: '/about', label: 'About Us' },
  { href: '/gad-corner', label: 'GAD Corner' },
  { href: '/contact', label: 'Contact' }
];
</script>

<style scoped>
.navbar { position: fixed; top: 0; width: 100%; z-index: 50; background: #1a1a2e; backdrop-filter: blur(12px); box-shadow: 0 1px 3px rgba(0,0,0,0.08); border-bottom: 1px solid rgba(139, 92, 246, 0.08); }
.navbar-inner { display: flex; justify-content: space-between; align-items: center; width: 100%; padding: 16px 32px; max-width: 1400px; margin: 0 auto; gap: 32px; }
.navbar-brand { font-size: 24px; font-weight: 900; letter-spacing: -0.03em; color: #990dd1; text-transform: uppercase; white-space: nowrap; z-index: 52; }
.navbar-brand a { color: inherit; text-decoration: none; }
.navbar-links { display: flex; align-items: center; gap: 32px; font-weight: 600; letter-spacing: -0.01em; }
.nav-link { text-decoration: none; transition: color 0.2s; font-size: 18px; }
.nav-link-active { color: #fff; font-weight: 700; border-bottom: 2px solid #9333ea; padding-bottom: 4px; }
.nav-link-inactive { color: #475569; }
.nav-link-inactive:hover { color: #fff; }
.navbar-actions { display: flex; align-items: center; gap: 16px; z-index: 52; }
.search-wrapper { position: relative; display: block; }
.search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 16px; color: #94a3b8; }
.search-input { background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 16px 8px 40px; font-size: 13px; width: 240px; color: #1e293b; transition: all 0.2s; outline: none; }
.search-input::placeholder { color: #94a3b8; }
.search-input:focus { border-color: #9333ea; box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.15); background: #fff; }
.already-text { font-size: 10px; text-transform: uppercase; letter-spacing: 0.15em; color: #94a3b8; white-space: nowrap; }
.btn-signup { background: #422b68; color: white; padding: 8px 20px; border-radius: 9999px; font-size: 13px; font-weight: 600; letter-spacing: 0.05em; text-decoration: none; display: inline-block; text-align: center; transition: all 0.2s; }
.btn-signup:hover { opacity: 0.9; }
.btn-signup:active { transform: scale(0.95); }
.btn-login { background: linear-gradient(135deg, #7e22ce 0%, #a855f7 100%); color: white; padding: 8px 24px; border-radius: 9999px; font-size: 13px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; text-decoration: none; display: inline-block; text-align: center; transition: all 0.2s; }
.btn-login:hover { transform: scale(0.97); box-shadow: 0 4px 12px rgba(126, 34, 206, 0.3); }

/* Mobile Menu Additions */
.mobile-toggle { display: none; background: transparent; border: none; color: #fff; font-size: 24px; cursor: pointer; padding: 4px; }
.mobile-menu-header { display: none; }
.mobile-backdrop { display: none; }

@media (max-width: 1024px) { .search-wrapper { display: none; } .already-text { display: none; } }
@media (max-width: 768px) { 
  .navbar-links { 
    position: fixed; top: 0; right: -100%; width: 250px; height: 100vh; 
    background: #1a1a2e; flex-direction: column; align-items: flex-start; 
    padding: 24px; gap: 24px; transition: right 0.3s ease; box-shadow: -4px 0 15px rgba(0,0,0,0.5); z-index: 53;
  }
  .navbar-links.mobile-menu-open { right: 0; }
  .mobile-menu-header { display: flex; justify-content: space-between; align-items: center; width: 100%; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 16px; margin-bottom: 8px; }
  .mobile-menu-title { color: #990dd1; font-weight: 800; font-size: 16px; text-transform: uppercase; letter-spacing: 0.05em; }
  .close-menu-btn { background: transparent; border: none; color: #94a3b8; font-size: 24px; cursor: pointer; padding: 0; }
  .mobile-backdrop { display: block; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 51; backdrop-filter: blur(4px); }
  
  .mobile-toggle { display: flex; align-items: center; justify-content: center; }
  .navbar-inner { padding: 12px 20px; } 
}
@media (max-width: 480px) {
  .navbar-brand { font-size: 18px; }
  .btn-login { padding: 6px 16px; font-size: 11px; }
  .navbar-inner { padding: 10px 12px; gap: 12px; }
}
</style>
