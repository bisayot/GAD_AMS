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
            <span class="material-symbols-outlined text-purple-400 text-3xl">account_balance</span>
          </div>
          <h1 class="font-headline text-3xl font-extrabold text-white tracking-tight mb-2">Welcome Back</h1>
          <p class="text-slate-300 text-sm max-w-xs mx-auto">Log in to the Benguet State University Gender and Development Office Portal</p>
        </div>

        <!-- Login Card -->
        <div class="rounded-xl shadow-2xl p-8 md:p-10 border border-white/10 relative overflow-hidden" style="background-color: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px);">
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
              <label class="block font-label text-xs font-bold uppercase tracking-widest text-slate-300 px-1" for="identity">Username or Email</label>
              <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-purple-400">person</span>
                <input 
                  v-model="identity"
                  class="w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-lg focus:ring-0 focus:bg-white/10 focus:border-b-2 focus:border-purple-500 transition-all duration-200 text-white placeholder:text-slate-500" 
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
                <label class="block font-label text-xs font-bold uppercase tracking-widest text-slate-300" for="password">Password</label>
                <router-link to="/forgot-password" class="text-purple-400 text-[10px] font-bold uppercase tracking-wider hover:underline underline-offset-4 decoration-2">Forgot Password?</router-link>
              </div>
              <div class="relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-purple-400">lock</span>
                <input 
                  v-model="password"
                  :type="showPassword ? 'text' : 'password'"
                  class="w-full pl-12 pr-12 py-4 bg-white/5 border border-white/10 rounded-lg focus:ring-0 focus:bg-white/10 focus:border-b-2 focus:border-purple-500 transition-all duration-200 text-white placeholder:text-slate-500" 
                  id="password" 
                  placeholder="••••••••" 
                  required 
                />
                <button 
                  @click="showPassword = !showPassword"
                  class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors" 
                  type="button"
                >
                  <span class="material-symbols-outlined text-sm">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
                </button>
              </div>
            </div>

            <div class="flex items-center gap-3 px-1">
              <input class="w-4 h-4 rounded bg-white/10 border-white/20 text-purple-500 focus:ring-purple-500" id="remember" type="checkbox" />
              <label class="text-sm text-slate-300 select-none" for="remember">Remember this device</label>
            </div>

            <!-- Turnstile Widget -->
            <TurnstileWidget ref="turnstileRef" @verify="onTurnstileVerify" />

            <!-- CTA -->
            <button 
              :disabled="loading"
              class="w-full py-4 px-6 bg-gradient-to-br from-purple-600 to-purple-800 text-white font-headline font-bold rounded-full shadow-lg hover:opacity-90 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 border border-purple-500/50" 
              type="submit"
            >
              {{ loading ? 'Signing In...' : 'Sign In to Dashboard' }}
              <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </button>
          </form>

          <div class="mt-8 pt-8 border-t border-white/10 text-center">
            <p class="text-sm text-slate-300 font-body">
              Are you a visitor?
              <router-link class="text-purple-400 font-bold hover:underline underline-offset-4 decoration-2 ml-1" to="/gad-corner">Explore Public Records</router-link>
            </p>
          </div>
        </div>

        <!-- Supporting Information -->
        <div class="mt-12 grid grid-cols-2 gap-4">
          <div class="rounded-xl p-4 flex items-start gap-3 border border-white/10 bg-white/5 backdrop-blur-sm">
            <span class="material-symbols-outlined text-blue-400 text-xl">verified_user</span>
            <div>
              <h4 class="font-headline font-bold text-xs text-white">Secure Access</h4>
              <p class="text-[10px] text-slate-300 leading-relaxed">Encrypted authentication for BSU personnel and students.</p>
            </div>
          </div>
          <div class="rounded-xl p-4 flex items-start gap-3 border border-white/10 bg-white/5 backdrop-blur-sm">
            <span class="material-symbols-outlined text-blue-400 text-xl">help_center</span>
            <div>
              <h4 class="font-headline font-bold text-xs text-white">Need Help?</h4>
              <p class="text-[10px] text-slate-300 leading-relaxed">Contact the ICT Support Desk for login issues.</p>
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
    
    // Store user info in localStorage or a store
    const userData = response.data.user;
    localStorage.setItem('user', JSON.stringify(userData));
    
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
