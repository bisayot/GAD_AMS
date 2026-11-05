<template>
  <div id="app" class="min-h-screen flex flex-col">
    <Navbar v-if="!isDashboard" />
    <main class="flex-grow flex flex-col">
      <router-view class="flex-grow" />
    </main>
    <Footer v-if="!isDashboard" />
    <BackToTop />
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import BackToTop from './components/BackToTop.vue';
import { useHolidays } from './utils/useHolidays';

const route = useRoute();
const { fetchHolidays } = useHolidays();

onMounted(() => {
  fetchHolidays();
});

const isDashboard = computed(() => {
  const routePath = route && typeof route.path === 'string' ? route.path : '';
  const path = routePath || window.location.pathname || '';
  return path.startsWith('/admin') || path.startsWith('/staff') || path.startsWith('/college');
});
</script>

<style>
/* Global styles can stay here or in style.css */
</style>
