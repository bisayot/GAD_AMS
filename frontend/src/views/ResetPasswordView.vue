<template>
  <div class="login-page font-body flex flex-col items-center justify-center px-6 relative overflow-hidden pt-32 pb-16 min-h-screen" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: #ffffff;">
    <!-- Background Decorative Elements -->
    <div class="absolute inset-0 bg-login-texture -z-10 opacity-20"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
    
    <div class="w-full max-w-md relative z-10">
      <!-- Brand Anchor -->
      <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/10 mb-6 backdrop-blur-sm border border-white/10">
          <span class="material-symbols-outlined text-purple-400 text-3xl">password</span>
        </div>
        <h1 class="font-headline text-3xl font-extrabold text-white tracking-tight mb-2">Reset Password</h1>
        <p class="text-slate-300 text-sm max-w-xs mx-auto">Create a new secure password for your account.</p>
      </div>

      <!-- Reset Password Card -->
      <div class="rounded-xl shadow-2xl p-8 md:p-10 border border-white/10 relative overflow-hidden" style="background-color: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px);">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-blue-500"></div>
        
        <form @submit.prevent="handleResetPassword" class="space-y-6">
          <div v-if="error" class="rounded-md bg-red-900/50 border border-red-500/50 text-red-200 px-3 py-2 text-sm mb-3">
            {{ error }}
          </div>
          <div v-if="success" class="rounded-md bg-green-900/50 border border-green-500/50 text-green-200 px-3 py-2 text-sm mb-3">
            {{ success }}
          </div>

          <!-- New Password Input -->
          <div class="space-y-2">
            <label class="block font-label text-xs font-bold uppercase tracking-widest text-slate-300 px-1" for="password">New Password</label>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-purple-400">lock</span>
              <input 
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                class="w-full pl-12 pr-12 py-4 bg-white/5 border border-white/10 rounded-lg focus:ring-0 focus:bg-white/10 focus:border-b-2 focus:border-purple-500 transition-all duration-200 text-white placeholder:text-slate-500" 
                id="password" 
                placeholder="••••••••" 
                required 
                minlength="8"
              />
              <button 
                @click="showPassword = !showPassword"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors" 
                type="button"
              >
                <span class="material-symbols-outlined text-sm">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
              </button>
            </div>
            <ul class="text-xs mt-2 space-y-1 font-medium transition-colors">
              <li class="flex items-center gap-1 transition-colors" :class="password.length >= 8 ? 'text-green-400' : 'text-slate-500'">
                <span class="material-symbols-outlined text-[14px]">{{ password.length >= 8 ? 'check_circle' : 'cancel' }}</span>
                At least 8 characters
              </li>
              <li class="flex items-center gap-1 transition-colors" :class="/[A-Z]/.test(password || '') ? 'text-green-400' : 'text-slate-500'">
                <span class="material-symbols-outlined text-[14px]">{{ /[A-Z]/.test(password || '') ? 'check_circle' : 'cancel' }}</span>
                One uppercase letter
              </li>
              <li class="flex items-center gap-1 transition-colors" :class="/[a-z]/.test(password || '') ? 'text-green-400' : 'text-slate-500'">
                <span class="material-symbols-outlined text-[14px]">{{ /[a-z]/.test(password || '') ? 'check_circle' : 'cancel' }}</span>
                One lowercase letter
              </li>
              <li class="flex items-center gap-1 transition-colors" :class="/[0-9]/.test(password || '') ? 'text-green-400' : 'text-slate-500'">
                <span class="material-symbols-outlined text-[14px]">{{ /[0-9]/.test(password || '') ? 'check_circle' : 'cancel' }}</span>
                One number
              </li>
              <li class="flex items-center gap-1 transition-colors" :class="/[^A-Za-z0-9]/.test(password || '') ? 'text-green-400' : 'text-slate-500'">
                <span class="material-symbols-outlined text-[14px]">{{ /[^A-Za-z0-9]/.test(password || '') ? 'check_circle' : 'cancel' }}</span>
                One special character
              </li>
            </ul>
          </div>

          <!-- Confirm Password Input -->
          <div class="space-y-2">
            <label class="block font-label text-xs font-bold uppercase tracking-widest text-slate-300 px-1" for="confirmPassword">Confirm Password</label>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-purple-400">lock_clock</span>
              <input 
                v-model="confirmPassword"
                :type="showPassword ? 'text' : 'password'"
                class="w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-lg focus:ring-0 focus:bg-white/10 focus:border-b-2 focus:border-purple-500 transition-all duration-200 text-white placeholder:text-slate-500" 
                id="confirmPassword" 
                placeholder="••••••••" 
                required 
                minlength="8"
              />
            </div>
          </div>

          <!-- CTA -->
          <button 
            :disabled="loading || !canSubmit"
            class="w-full py-4 px-6 bg-gradient-to-br from-purple-600 to-purple-800 text-white font-headline font-bold rounded-full shadow-lg hover:opacity-90 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 border border-purple-500/50" 
            type="submit"
          >
            {{ loading ? 'Resetting...' : 'Reset Password' }}
            <span class="material-symbols-outlined text-sm">check_circle</span>
          </button>
        </form>

        <div class="mt-8 pt-8 border-t border-white/10 text-center">
          <p class="text-sm text-slate-300 font-body">
            <router-link class="text-purple-400 font-bold hover:underline underline-offset-4 decoration-2 ml-1" to="/login">Return to Login</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../api'; 

const route = useRoute();
const router = useRouter();
const password = ref('');
const confirmPassword = ref('');
const loading = ref(false);
const error = ref('');
const success = ref('');
const showPassword = ref(false);

const token = computed(() => route.query.token);

const canSubmit = computed(() => {
  return password.value.length >= 8 && /[A-Z]/.test(password.value) && /[a-z]/.test(password.value) && /[0-9]/.test(password.value) && /[^A-Za-z0-9]/.test(password.value) && password.value === confirmPassword.value && token.value;
});

const handleResetPassword = async () => {
  if (password.value.length < 8) {
    error.value = 'Password must be at least 8 characters long.';
    return;
  }
  if (!/[A-Z]/.test(password.value)) {
    error.value = 'Password must contain at least 1 uppercase letter.';
    return;
  }
  if (!/[a-z]/.test(password.value)) {
    error.value = 'Password must contain at least 1 lowercase letter.';
    return;
  }
  if (!/[0-9]/.test(password.value)) {
    error.value = 'Password must contain at least 1 number.';
    return;
  }
  if (!/[^A-Za-z0-9]/.test(password.value)) {
    error.value = 'Password must contain at least 1 special character.';
    return;
  }
  if (password.value !== confirmPassword.value) {
    error.value = "Passwords do not match";
    return;
  }
  
  if (!token.value) {
    error.value = "Invalid or missing reset token.";
    return;
  }

  loading.value = true;
  error.value = '';
  success.value = '';
  
  try {
    const response = await api.post('reset-password', {
      token: token.value,
      password: password.value
    });
    
    success.value = response.data?.message || 'Password reset successfully.';
    
    // Redirect to login after a few seconds
    setTimeout(() => {
      router.push('/login');
    }, 3000);
    
  } catch (err) {
    console.error('Reset password error:', err);
    if (err && err.messages) {
      error.value = err.messages.error || 'Failed to reset password';
    } else if (err && err.message) {
      error.value = err.message;
    } else {
      error.value = 'Connection error. Please try again later.';
    }
  } finally {
    loading.value = false;
  }
};
</script>
