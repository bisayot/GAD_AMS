<template>
  <div class="login-page font-body flex flex-col items-center justify-center px-6 relative z-0 overflow-hidden pt-32 pb-16 min-h-screen">
    <!-- Background Decorative Elements -->
    <div class="auth-bg-blob auth-bg-blob-1"></div>
    <div class="auth-bg-blob auth-bg-blob-2"></div>
    
    <div class="w-full max-w-md relative z-10">
      <!-- Brand Anchor -->
      <div class="text-center mb-10">
        <div class="auth-icon-wrap inline-flex items-center justify-center w-16 h-16 rounded-full mb-6 backdrop-blur-sm border">
          <span class="material-symbols-outlined text-purple-400 text-3xl">lock_reset</span>
        </div>
        <h1 class="font-headline text-3xl font-extrabold tracking-tight mb-2 auth-title">Forgot Password</h1>
        <p class="text-sm max-w-xs mx-auto auth-subtitle">Enter your registered email address to receive a password reset link.</p>
      </div>

      <!-- Forgot Password Card -->
      <div class="auth-card rounded-2xl shadow-2xl p-8 md:p-10 border relative overflow-hidden">
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
            <label class="block font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant px-1" for="email">Email Address</label>
            <div class="relative group">
              <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant transition-colors group-focus-within:text-purple-400">mail</span>
              <input 
                v-model="email"
                class="w-full pl-12 pr-4 py-4 bg-surface-container border border-outline-variant rounded-lg focus:ring-0 focus:bg-surface-variant focus:border-b-2 focus:border-purple-500 transition-all duration-200 text-on-surface placeholder:text-on-surface-variant" 
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
            class="w-full py-4 px-6 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-400 hover:to-purple-400 text-white font-headline font-bold rounded-full shadow-[0_0_20px_rgba(168,85,247,0.4)] hover:shadow-[0_0_30px_rgba(168,85,247,0.7)] hover:-translate-y-1 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2"
            type="submit"
          >
            {{ loading ? 'Sending...' : 'Send Reset Link' }}
            <span class="material-symbols-outlined text-sm">send</span>
          </button>
        </form>

        <div v-if="emailLimitReached" class="mt-6 p-4 bg-purple-500/10 rounded-lg border border-purple-500/20 flex items-start gap-3">
          <span class="material-symbols-outlined text-purple-400 text-[20px]">info</span>
          <p class="text-sm text-on-surface-variant leading-relaxed">
            Not receiving the email? Please email us directly at <a href="mailto:gad.office@bsu.edu.ph" class="text-purple-400 font-bold hover:underline">gad.office@bsu.edu.ph</a> for a manual reset.
          </p>
        </div>

        <div class="mt-8 pt-8 border-t border-outline-variant text-center">
          <p class="text-sm text-on-surface-variant font-body">
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
