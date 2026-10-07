<template>
  <div class="login-page font-body flex flex-col items-center justify-center px-6 relative z-0 overflow-hidden pt-32 pb-16 min-h-screen">
    <!-- Background Decorative Elements -->
    <div class="auth-bg-blob auth-bg-blob-1"></div>
    <div class="auth-bg-blob auth-bg-blob-2"></div>
    
    <div class="w-full max-w-md relative z-10">
      <!-- Brand Anchor -->
        <div class="text-center mb-10">
          <div class="auth-icon-wrap inline-flex items-center justify-center w-16 h-16 rounded-full mb-6 backdrop-blur-sm border">
            <span class="material-symbols-outlined text-purple-400 text-3xl">account_balance</span>
          </div>
          <h1 class="font-headline text-3xl font-extrabold tracking-tight mb-2 auth-title">Welcome Back</h1>
          <p class="text-sm max-w-xs mx-auto auth-subtitle">Log in to the Benguet State University Gender and Development Office Portal</p>
        </div>

        <!-- Login Card -->
        <div class="auth-card rounded-2xl shadow-2xl p-8 md:p-10 border relative overflow-hidden">
          <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-blue-500"></div>
          
          <form @submit.prevent="handleLogin" class="space-y-6">
            <div v-if="error" class="rounded-md bg-red-900/50 border border-red-500/50 text-red-200 px-3 py-2 text-sm mb-3">
              {{ error }}
            </div>
            <div v-if="successMsg" class="rounded-md bg-emerald-900/50 border border-emerald-500/50 text-emerald-200 px-3 py-2 text-sm mb-3 text-center">
              {{ successMsg }}
            </div>

            <!-- Identity Input -->
            <div class="space-y-2">
              <label class="block font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant px-1" for="identity">Email Address</label>
              <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant transition-colors group-focus-within:text-purple-400">person</span>
                <input 
                  v-model="identity"
                  class="w-full pl-12 pr-4 py-4 bg-surface-container border border-outline-variant rounded-lg focus:ring-0 focus:bg-surface-variant focus:border-b-2 focus:border-purple-500 transition-all duration-200 text-on-surface placeholder:text-on-surface-variant" 
                  id="identity" 
                  placeholder="e.g. gad.office@bsu.edu.ph" 
                  required 
                  type="text" 
                />
              </div>
            </div>

            <!-- Password Input -->
            <div class="space-y-2">
              <div class="flex justify-between items-end px-1">
                <label class="block font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant" for="password">Password</label>
                <router-link to="/forgot-password" class="text-purple-400 text-[10px] font-bold uppercase tracking-wider hover:underline underline-offset-4 decoration-2">Forgot Password?</router-link>
              </div>
              <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant transition-colors group-focus-within:text-purple-400">lock</span>
                <input 
                  v-model="password"
                  :type="showPassword ? 'text' : 'password'"
                  class="w-full pl-12 pr-12 py-4 bg-surface-container border border-outline-variant rounded-lg focus:ring-0 focus:bg-surface-variant focus:border-b-2 focus:border-purple-500 transition-all duration-200 text-on-surface placeholder:text-on-surface-variant" 
                  id="password" 
                  placeholder="••••••••" 
                  required 
                />
                <button 
                  @click="showPassword = !showPassword"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-on-surface transition-colors" 
                  type="button"
                >
                  <span class="material-symbols-outlined text-sm">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
                </button>
              </div>
            </div>

            <div class="flex items-center gap-3 px-1">
              <input class="w-4 h-4 rounded bg-surface-variant border-outline-variant text-purple-500 focus:ring-purple-500" id="remember" type="checkbox" />
              <label class="text-sm text-on-surface-variant select-none" for="remember">Remember this device</label>
            </div>

            <!-- Turnstile Widget -->
            <TurnstileWidget ref="turnstileRef" @verify="onTurnstileVerify" />

            <!-- CTA -->
            <button 
              :disabled="loading"
              class="w-full py-4 px-6 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-400 hover:to-purple-400 text-white font-headline font-bold rounded-full shadow-[0_0_20px_rgba(168,85,247,0.4)] hover:shadow-[0_0_30px_rgba(168,85,247,0.7)] hover:-translate-y-1 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2"
              type="submit"
            >
              {{ loading ? 'Signing In...' : 'Sign In to Dashboard' }}
              <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </button>
          </form>

          <div class="mt-8 pt-8 border-t border-outline-variant text-center">
            <p class="text-sm text-on-surface-variant font-body">
              Are you a visitor?
              <router-link class="text-purple-400 font-bold hover:underline underline-offset-4 decoration-2 ml-1" to="/gad-corner">Explore GAD CORNER</router-link>
            </p>
          </div>
        </div>

        <!-- Supporting Information -->
        <div class="mt-12 grid grid-cols-2 gap-4">
          <div class="rounded-xl p-4 flex items-start gap-3 border border-outline-variant bg-surface-container backdrop-blur-sm">
            <span class="material-symbols-outlined text-blue-400 text-xl">verified_user</span>
            <div>
              <h4 class="font-headline font-bold text-xs text-on-background">Secure Access</h4>
              <p class="text-[10px] text-on-surface-variant leading-relaxed">Encrypted authentication for BSU personnel and students.</p>
            </div>
          </div>
          <div class="rounded-xl p-4 flex items-start gap-3 border border-outline-variant bg-surface-container backdrop-blur-sm">
            <span class="material-symbols-outlined text-blue-400 text-xl">help_center</span>
            <div>
              <h4 class="font-headline font-bold text-xs text-on-background">Need Help?</h4>
              <p class="text-[10px] text-on-surface-variant leading-relaxed">Contact the ICT Support Desk for login issues.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
// Use the relative path to step up out of the 'views' folder and find api.js
import api from '../api'; 
import TurnstileWidget from '../components/TurnstileWidget.vue';

const router = useRouter();
const route = useRoute();
const identity = ref('');
const password = ref('');
const loading = ref(false);
const error = ref('');
const successMsg = ref('');
const showPassword = ref(false);
const turnstileToken = ref('');
const turnstileRef = ref(null);

onMounted(() => {
  if (route.query.registered === 'true') {
    successMsg.value = 'Registration successful! You can now log in.';
    // Clean up the URL query parameter
    const newQuery = { ...route.query };
    delete newQuery.registered;
    router.replace({ path: '/login', query: newQuery });
  }
});

const onTurnstileVerify = (token) => {
  turnstileToken.value = token;
};

const handleLogin = async () => {
  if (!turnstileToken.value) {
    error.value = 'Please complete the security check.';
    return;
  }

  loading.value = true;
  error.value = '';
  
  try {
    const response = await api.post('login', {
      identity: identity.value,
      password: password.value,
      turnstile_token: turnstileToken.value
    });
    
    // Store user info and JWT token in localStorage
    const userData = response.data.user;
    const token    = response.data.token;
    localStorage.setItem('user', JSON.stringify(userData));
    localStorage.setItem('authToken', token);
    
    const role = userData.role;
    const redirectTo = typeof route.query.redirect === 'string' ? route.query.redirect : null;
    
    console.log("Login successful. Detected role:", role, "redirectTo:", redirectTo);

    const getTargetRoute = () => {
      if (redirectTo) {
        return { path: redirectTo };
      }

      switch(role) {
        case 'admin':
          return { path: '/admin/dashboard' };
        case 'gad_staff':
          return { path: '/staff/dashboard' };
        case 'twg':
        case 'non-twg':
          return { path: '/college/dashboard' };
        default:
          return { path: '/' };
      }
    };

    router.replace(getTargetRoute());
    
  } catch (err) {
    console.error('Login error:', err);
    if (turnstileRef.value) turnstileRef.value.reset();
    turnstileToken.value = '';
    
    if (err && err.messages) {
      error.value = err.messages.error || 'Login failed';
    } else if (err && err.message) {
      error.value = err.message;
    } else {
      error.value = 'Connection error. Please check if the backend is running.';
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
  color: #deb7ff !important;
}

:global(.dark) .auth-card,
.dark .auth-card {
  background-color: #210c3b !important;
  border-color: #532385 !important;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.75), 0 0 0 1px rgba(168, 85, 247, 0.2) !important;
}

:global(.dark) .login-page input,
.dark .login-page input {
  background-color: #2e1250 !important;
  border-color: #532385 !important;
  color: #f5efff !important;
}

:global(.dark) .login-page input:focus,
.dark .login-page input:focus {
  background-color: #381660 !important;
  border-color: #c084fc !important;
}

:global(.dark) .login-page input::placeholder,
.dark .login-page input::placeholder {
  color: #a88bc4 !important;
}

:global(.dark) .login-page label,
.dark .login-page label {
  color: #deb7ff !important;
}

:global(.dark) .login-page .border-outline-variant,
.dark .login-page .border-outline-variant {
  border-color: #532385 !important;
}

:global(.dark) .login-page .text-on-surface-variant,
.dark .login-page .text-on-surface-variant {
  color: #deb7ff !important;
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
