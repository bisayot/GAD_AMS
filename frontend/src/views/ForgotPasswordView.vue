<template>
  <div class="login-page bg-background text-on-surface font-body flex flex-col items-center justify-center px-6 relative overflow-hidden pt-32 pb-16 min-h-screen">
    <!-- Background Decorative Elements -->
    <div class="absolute inset-0 bg-login-texture -z-10"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>
    
    <div class="w-full max-w-md relative z-10">
      <!-- Brand Anchor -->
      <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container mb-6">
          <span class="material-symbols-outlined text-primary text-3xl">lock_reset</span>
        </div>
        <h1 class="font-headline text-3xl font-extrabold text-on-surface tracking-tight mb-2">Forgot Password</h1>
        <p class="text-on-surface-variant text-sm max-w-xs mx-auto">Enter your registered email address to receive a password reset link.</p>
      </div>

      <!-- Forgot Password Card -->
      <div class="bg-surface-container-lowest rounded-xl shadow-[0_8px_32px_rgba(26,28,29,0.04)] p-8 md:p-10 border border-outline-variant/15 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-secondary/40"></div>
        
        <form @submit.prevent="handleForgotPassword" class="space-y-6">
          <div v-if="error" class="rounded-md bg-red-100 border border-red-200 text-red-700 px-3 py-2 text-sm mb-3">
            {{ error }}
          </div>
          <div v-if="success" class="rounded-md bg-green-100 border border-green-200 text-green-700 px-3 py-2 text-sm mb-3">
            {{ success }}
          </div>

          <!-- Email Input -->
          <div class="space-y-2">
            <label class="block font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant px-1" for="email">Email Address</label>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline transition-colors group-focus-within:text-primary">mail</span>
              <input 
                v-model="email"
                class="w-full pl-12 pr-4 py-4 bg-surface-container-low border-none rounded-lg focus:ring-0 focus:bg-surface-bright focus:border-b-2 focus:border-primary transition-all duration-200 text-on-surface placeholder:text-outline/50" 
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
            class="w-full py-4 px-6 bg-gradient-to-br from-primary to-primary-container text-white font-headline font-bold rounded-full shadow-lg shadow-primary/20 hover:opacity-90 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2" 
            type="submit"
          >
            {{ loading ? 'Sending...' : 'Send Reset Link' }}
            <span class="material-symbols-outlined text-sm">send</span>
          </button>
        </form>

        <div class="mt-8 pt-8 border-t border-outline-variant/15 text-center">
          <p class="text-sm text-on-surface-variant font-body">
            Remembered your password?
            <router-link class="text-secondary font-bold hover:underline underline-offset-4 decoration-2 ml-1" to="/login">Back to Login</router-link>
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
  } finally {
    loading.value = false;
  }
};
</script>
