<template>
  <div id="app" class="min-h-screen flex flex-col">
    <Navbar v-if="!isDashboard" />
    <router-view class="flex-grow" />
    <Footer v-if="!isDashboard" />
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import { useHolidays } from './utils/useHolidays';

const route = useRoute();
const { fetchHolidays } = useHolidays();

onMounted(() => {
  fetchHolidays();
});

const isDashboard = computed(() => {
  const path = route.path;
  return path.startsWith('/admin') || path.startsWith('/staff') || path.startsWith('/college');
});
</script>

<style>
/* Global styles can stay here or in style.css */
</style>
