<template>
  <div class="login-page font-body flex flex-col items-center justify-center px-6 relative z-0 overflow-hidden pt-32 pb-16 min-h-screen">
    <!-- Background Decorative Elements -->
    <div class="auth-bg-blob auth-bg-blob-1"></div>
    <div class="auth-bg-blob auth-bg-blob-2"></div>
    
    <div class="w-full max-w-md relative z-10">
      <!-- Brand Anchor -->
      <div class="text-center mb-10">
        <div class="auth-icon-wrap inline-flex items-center justify-center w-16 h-16 rounded-full mb-6 backdrop-blur-sm border">
          <span class="material-symbols-outlined text-purple-400 text-3xl">password</span>
        </div>
        <h1 class="font-headline text-3xl font-extrabold tracking-tight mb-2 auth-title">Reset Password</h1>
        <p class="text-sm max-w-xs mx-auto auth-subtitle">Create a new secure password for your account.</p>
      </div>

      <!-- Reset Password Card -->
      <div class="auth-card rounded-2xl shadow-2xl p-8 md:p-10 border relative overflow-hidden">
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
            <label class="block font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant px-1" for="password">New Password</label>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant transition-colors group-focus-within:text-purple-400">lock</span>
              <input 
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                class="w-full pl-12 pr-12 py-4 bg-surface-container border border-outline-variant rounded-lg focus:ring-0 focus:bg-surface-variant focus:border-b-2 focus:border-purple-500 transition-all duration-200 text-on-surface placeholder:text-on-surface-variant" 
                id="password" 
                placeholder="••••••••" 
                required 
                minlength="8"
              />
              <button 
                @click="showPassword = !showPassword"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors" 
                type="button"
              >
                <span class="material-symbols-outlined text-sm">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
              </button>
            </div>
            <ul class="text-xs mt-2 space-y-1 font-medium transition-colors">
              <li class="flex items-center gap-1 transition-colors" :class="password.length >= 8 ? 'text-green-400' : 'text-on-surface-variant'">
                <span class="material-symbols-outlined text-[14px]">{{ password.length >= 8 ? 'check_circle' : 'cancel' }}</span>
                At least 8 characters
              </li>
              <li class="flex items-center gap-1 transition-colors" :class="/[A-Z]/.test(password || '') ? 'text-green-400' : 'text-on-surface-variant'">
                <span class="material-symbols-outlined text-[14px]">{{ /[A-Z]/.test(password || '') ? 'check_circle' : 'cancel' }}</span>
                One uppercase letter
              </li>
              <li class="flex items-center gap-1 transition-colors" :class="/[a-z]/.test(password || '') ? 'text-green-400' : 'text-on-surface-variant'">
                <span class="material-symbols-outlined text-[14px]">{{ /[a-z]/.test(password || '') ? 'check_circle' : 'cancel' }}</span>
                One lowercase letter
              </li>
              <li class="flex items-center gap-1 transition-colors" :class="/[0-9]/.test(password || '') ? 'text-green-400' : 'text-on-surface-variant'">
                <span class="material-symbols-outlined text-[14px]">{{ /[0-9]/.test(password || '') ? 'check_circle' : 'cancel' }}</span>
                One number
              </li>
              <li class="flex items-center gap-1 transition-colors" :class="/[^A-Za-z0-9]/.test(password || '') ? 'text-green-400' : 'text-on-surface-variant'">
                <span class="material-symbols-outlined text-[14px]">{{ /[^A-Za-z0-9]/.test(password || '') ? 'check_circle' : 'cancel' }}</span>
                One special character
              </li>
            </ul>
          </div>

          <!-- Confirm Password Input -->
          <div class="space-y-2">
            <label class="block font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant px-1" for="confirmPassword">Confirm Password</label>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant transition-colors group-focus-within:text-purple-400">lock_clock</span>
              <input 
                v-model="confirmPassword"
                :type="showPassword ? 'text' : 'password'"
                class="w-full pl-12 pr-4 py-4 bg-surface-container border border-outline-variant rounded-lg focus:ring-0 focus:bg-surface-variant focus:border-b-2 focus:border-purple-500 transition-all duration-200 text-on-surface placeholder:text-on-surface-variant" 
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
            class="w-full py-4 px-6 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-400 hover:to-purple-400 text-white font-headline font-bold rounded-full shadow-[0_0_20px_rgba(168,85,247,0.4)] hover:shadow-[0_0_30px_rgba(168,85,247,0.7)] hover:-translate-y-1 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-50 disabled:hover:translate-y-0 disabled:hover:scale-100"
            type="submit"
          >
            {{ loading ? 'Resetting...' : 'Reset Password' }}
            <span class="material-symbols-outlined text-sm">check_circle</span>
          </button>
        </form>

        <div class="mt-8 pt-8 border-t border-outline-variant text-center">
          <p class="text-sm text-on-surface-variant font-body">
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

<style scoped>
/* Base / Light mode */
.login-page {
  background-color: var(--color-background, #f8fafc);
  color: var(--color-on-background, #002200);
}
.auth-bg-blob {
  position: absolute;
  border-radius: 9999px;
  filter: blur(64px);
  pointer-events: none;
  z-index: -10;
}
.auth-bg-blob-1 {
  top: -6rem;
  right: -6rem;
  width: 24rem;
  height: 24rem;
  background-color: rgba(168, 85, 247, 0.15);
}
.auth-bg-blob-2 {
  bottom: -6rem;
  left: -6rem;
  width: 24rem;
  height: 24rem;
  background-color: rgba(234, 179, 8, 0.12);
}
.auth-icon-wrap {
  background-color: var(--color-surface-variant);
  border-color: var(--color-outline-variant);
}
.auth-title {
  color: var(--color-on-background);
}
.auth-subtitle {
  color: var(--color-on-surface-variant);
}
.auth-card {
  background-color: var(--color-surface, #ffffff);
  border-color: var(--color-outline-variant);
  box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
}

/* Dark mode - Rich deep dark purple with elevated card */
:global(.dark) .login-page,
.dark .login-page {
  background-color: #120722 !important;
  color: #f5efff !important;
}

:global(.dark) .auth-bg-blob-1,
.dark .auth-bg-blob-1 {
  background-color: rgba(168, 85, 247, 0.18) !important;
}

:global(.dark) .auth-bg-blob-2,
.dark .auth-bg-blob-2 {
  background-color: rgba(107, 33, 168, 0.22) !important;
}

:global(.dark) .auth-icon-wrap,
.dark .auth-icon-wrap {
  background-color: #2b1147 !important;
  border-color: #532385 !important;
}

:global(.dark) .auth-title,
.dark .auth-title {
  color: #ffffff !important;
}

:global(.dark) .auth-subtitle,
.dark .auth-subtitle {
  color: #9ca3af !important;
}

:global(.dark) .auth-card,
.dark .auth-card {
  background-color: #1e2026 !important;
  border-color: #2f333d !important;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(255, 255, 255, 0.07) !important;
}

:global(.dark) .login-page input,
.dark .login-page input {
  background-color: #16181d !important;
  border-color: #2f333d !important;
  color: #f3f4f6 !important;
}

:global(.dark) .login-page input:focus,
.dark .login-page input:focus {
  background-color: #1a1c22 !important;
  border-color: #a855f7 !important;
}

:global(.dark) .login-page input::placeholder,
.dark .login-page input::placeholder {
  color: #6b7280 !important;
}

:global(.dark) .login-page label,
.dark .login-page label {
  color: #d1d5db !important;
}

:global(.dark) .login-page .border-outline-variant,
.dark .login-page .border-outline-variant {
  border-color: #2f333d !important;
}

:global(.dark) .login-page .text-on-surface-variant,
.dark .login-page .text-on-surface-variant {
  color: #9ca3af !important;
}

:global(.dark) .login-page .bg-surface-variant,
.dark .login-page .bg-surface-variant {
  background-color: #2e1250 !important;
}

:global(.dark) .login-page .bg-surface-container,
.dark .login-page .bg-surface-container {
  background-color: #2e1250 !important;
  border-color: #532385 !important;
}
</style>
