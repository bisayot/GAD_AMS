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
          <span class="material-symbols-outlined text-purple-400 text-3xl">lock_reset</span>
        </div>
        <h1 class="font-headline text-3xl font-extrabold text-white tracking-tight mb-2">Forgot Password</h1>
        <p class="text-slate-300 text-sm max-w-xs mx-auto">Enter your registered email address to receive a password reset link.</p>
      </div>

      <!-- Forgot Password Card -->
      <div class="rounded-xl shadow-2xl p-8 md:p-10 border border-white/10 relative overflow-hidden" style="background-color: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px);">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-blue-500"></div>
        
        <form @submit.prevent="handleForgotPassword" class="space-y-6">
          <div v-if="error" class="rounded-md bg-red-900/50 border border-red-500/50 text-red-200 px-3 py-2 text-sm mb-3">
            {{ error }}
          </div>
          <div v-if="success" class="rounded-md bg-green-900/50 border border-green-500/50 text-green-200 px-3 py-2 text-sm mb-3">
            {{ success }}
          </div>

          <!-- Email Input -->
          <div class="space-y-2">
            <label class="block font-label text-xs font-bold uppercase tracking-widest text-slate-300 px-1" for="email">Email Address</label>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-purple-400">mail</span>
              <input 
                v-model="email"
                class="w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-lg focus:ring-0 focus:bg-white/10 focus:border-b-2 focus:border-purple-500 transition-all duration-200 text-white placeholder:text-slate-500" 
                id="email" 
                placeholder="e.g. gad.office@bsu.edu.ph" 
                required 
                type="email" 
              />
            </div>
          </div>

          <!-- Turnstile Widget -->
          <TurnstileWidget ref="turnstileRef" @verify="onTurnstileVerify" />

          <!-- CTA -->
          <button 
            :disabled="loading"
            class="w-full py-4 px-6 bg-gradient-to-br from-purple-600 to-purple-800 text-white font-headline font-bold rounded-full shadow-lg hover:opacity-90 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2 border border-purple-500/50" 
            type="submit"
          >
            {{ loading ? 'Sending...' : 'Send Reset Link' }}
            <span class="material-symbols-outlined text-sm">send</span>
          </button>
        </form>

        <div v-if="emailLimitReached" class="mt-6 p-4 bg-purple-500/10 rounded-lg border border-purple-500/20 flex items-start gap-3">
          <span class="material-symbols-outlined text-purple-400 text-[20px]">info</span>
          <p class="text-sm text-slate-300 leading-relaxed">
            Not receiving the email? Our system uses a free email service with daily limits. If it fails, please email us directly at <a href="mailto:gad.office@bsu.edu.ph" class="text-purple-400 font-bold hover:underline">gad.office@bsu.edu.ph</a> for a manual reset.
          </p>
        </div>

        <div class="mt-8 pt-8 border-t border-white/10 text-center">
          <p class="text-sm text-slate-300 font-body">
            Remembered your password?
            <router-link class="text-purple-400 font-bold hover:underline underline-offset-4 decoration-2 ml-1" to="/login">Back to Login</router-link>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '../api'; 
import TurnstileWidget from '../components/TurnstileWidget.vue';

const email = ref('');
const loading = ref(false);
const error = ref('');
const success = ref('');
const turnstileToken = ref('');
const turnstileRef = ref(null);
const emailLimitReached = ref(false);

const onTurnstileVerify = (token) => {
  turnstileToken.value = token;
};

const handleForgotPassword = async () => {
  if (!turnstileToken.value) {
    error.value = 'Please complete the security check.';
    return;
  }

  loading.value = true;
  error.value = '';
  success.value = '';
  
  try {
    const response = await api.post('forgot-password', {
      email: email.value,
      turnstile_token: turnstileToken.value
    });
    
    success.value = response.data?.message || 'If your email is registered, you will receive a reset link shortly.';
    email.value = ''; // clear input
    
  } catch (err) {
    console.error('Forgot password error:', err);
    if (turnstileRef.value) turnstileRef.value.reset();
    turnstileToken.value = '';
    if (err && err.messages) {
      error.value = err.messages.error || 'Failed to send reset link';
    } else if (err && err.message) {
      error.value = err.message;
    } else {
      error.value = 'Connection error. Please try again later.';
    }
    emailLimitReached.value = true;
  } finally {
    loading.value = false;
  }
};
</script>
