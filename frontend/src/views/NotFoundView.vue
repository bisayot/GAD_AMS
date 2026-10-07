<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
      <div>
        <h1 class="text-9xl font-extrabold text-purple-600 dark:text-purple-400 tracking-widest">404</h1>
        <div class="bg-purple-600 px-2 text-sm rounded rotate-12 absolute shadow-lg">
          Page Not Found
        </div>
      </div>
      
      <div class="mt-8">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
          Oops! You seem to be lost.
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
          The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
        </p>
      </div>

      <div class="flex justify-center gap-4 mt-6">
        <button 
          @click="goBack" 
          class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 shadow-sm flex items-center font-medium"
        >
          <span class="material-symbols-outlined mr-2 text-sm">arrow_back</span>
          Go Back
        </button>
        
        <router-link 
          to="/" 
          class="px-6 py-3 border border-transparent rounded-lg text-white bg-purple-600 hover:bg-purple-700 transition-colors duration-200 shadow-sm flex items-center font-medium"
        >
          <span class="material-symbols-outlined mr-2 text-sm">home</span>
          Return Home
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const goBack = () => {
  router.back()
}
</script>

<style scoped>
/* Optional: Adding a slight animation to the 404 text */
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
  100% { transform: translateY(0px); }
}

h1 {
  animation: float 4s ease-in-out infinite;
}
</style>
