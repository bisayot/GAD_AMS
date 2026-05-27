<template>
  <div class="register-page bg-background text-on-surface font-body pt-32 pb-16 px-4 flex flex-col items-center justify-center">
    <div class="max-w-6xl w-full grid grid-cols-1 md:grid-cols-12 gap-8 items-start relative z-10">
        <!-- Branding & Info Column -->
        <div class="md:col-span-5 flex flex-col gap-10 pr-0 md:pr-12">
          <div class="space-y-4">
            <div class="inline-flex items-center gap-2 bg-secondary-container/20 text-on-secondary-container px-3 py-1 rounded-full">
              <span class="material-symbols-outlined text-[18px]">verified_user</span>
              <span class="text-[10px] font-bold uppercase tracking-[0.2em] font-label">Official Registration</span>
            </div>
            <h1 class="text-5xl font-extrabold font-headline tracking-tighter text-primary leading-tight">
              Empowering Equality through <span class="text-secondary italic">Scholarly Action.</span>
            </h1>
            <p class="text-on-surface-variant text-lg leading-relaxed max-w-md">
              Join the Benguet State University Gender and Development portal. Register to access academic resources and contribute to our inclusive community.
            </p>
          </div>
          <div class="relative w-full aspect-square rounded-xl overflow-hidden shadow-2xl">
            <img alt="Academic Building" class="object-cover w-full h-full grayscale hover:grayscale-0 transition-all duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD8nYYL7wB7f7aO6xD9YmqF4a5EEvWxuG3gjap1VnrmGH6QoUtPdArd-LTbR6PAmLIf1R6P4DDC-2s_ZM1ncYyMn1i5wbipjXlYbmzfUM9NGZ8sEh9affvurdentdsie0DQNMXroezFpxyGlz_a0TkDVXF4F7T-9Y5dOjyOWhwggU1Wd2aUR3QcpIg4azf8r6vcJ-8yZCqmyID0XMuhcJ7iibjgZO2qVBoKJ_IBmUSgxB7wsD_3wjdtCnZxOfIEJQouT_McUGz5E8v3" />
            <div class="absolute inset-0 bg-gradient-to-t from-primary/60 to-transparent"></div>
            <div class="absolute bottom-6 left-6 right-6 text-white">
              <p class="text-sm font-label uppercase tracking-widest opacity-80 mb-2">Heritage &amp; Excellence</p>
              <p class="font-headline font-bold text-xl">Serving the Highlands since 1916.</p>
            </div>
          </div>
        </div>

        <!-- Registration Form Canvas -->
        <div class="md:col-span-7 bg-surface-container-lowest rounded-xl p-8 md:p-12 shadow-[0_8px_24px_rgba(26,28,29,0.04)] border border-outline-variant/10">
          <div class="mb-10">
            <h2 class="text-2xl font-bold font-headline text-on-surface mb-2">Create Academic Profile</h2>
            <p class="text-on-surface-variant text-sm">Please provide your institutional credentials to begin.</p>
          </div>

          <form @submit.prevent="handleRegister" class="space-y-8">
            <div v-if="error" class="rounded-md bg-red-100 border border-red-200 text-red-700 px-4 py-3 text-sm">{{ error }}</div>
            <div v-if="success" class="rounded-md bg-green-100 border border-green-200 text-green-700 px-4 py-3 text-sm">{{ success }}</div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

              <!-- First Name -->
              <div class="flex flex-col gap-2">
                <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">First Name</label>
                <input
                  v-model="form.first_name"
                  class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary transition-all"
                  placeholder="Juan"
                  type="text"
                  required
                />
              </div>

              <!-- Last Name -->
              <div class="flex flex-col gap-2">
                <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">Last Name</label>
                <input
                  v-model="form.last_name"
                  class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary transition-all"
                  placeholder="Dela Cruz"
                  type="text"
                  required
                />
              </div>

              <!-- Middle Name (optional) -->
              <div class="flex flex-col gap-2 md:col-span-2">
                <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">
                  Middle Name <span class="normal-case font-normal text-slate-400">(optional)</span>
                </label>
                <input
                  v-model="form.middle_name"
                  class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary transition-all"
                  placeholder="Santos"
                  type="text"
                />
              </div>

              <!-- Role -->
              <div class="flex flex-col gap-2 md:col-span-2">
                <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">Role / Position</label>
                <select
                  v-model="form.user_role"
                  class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary transition-all appearance-none"
                  required
                >
                  <option value="" disabled>Select your role</option>
                  <option value="Director">Director</option>
                  <option value="Staff">Staff</option>
                  <option value="TWG">TWG Member</option>
                  <option value="Non-TWG">Non-TWG</option>
                </select>
              </div>

              <!-- Institutional Email -->
              <div class="flex flex-col gap-2 md:col-span-2">
                <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">Institutional Email</label>
                <input
                  v-model="form.email"
                  class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary transition-all"
                  placeholder="username@bsu.edu.ph"
                  type="email"
                  required
                />
              </div>

              <!-- Password -->
              <div class="flex flex-col gap-2 md:col-span-2">
                <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">Password</label>
                <input
                  v-model="form.password"
                  class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary transition-all"
                  type="password"
                  placeholder="At least 6 characters"
                  required
                />
              </div>

              <!-- Confirm Password -->
              <div class="flex flex-col gap-2 md:col-span-2">
                <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">Confirm Password</label>
                <input
                  v-model="form.confirm_password"
                  class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary transition-all"
                  type="password"
                  placeholder="Repeat your password"
                  required
                />
              </div>

            </div>

            <div class="flex items-start gap-3 pt-4">
              <input v-model="form.terms" class="h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary/20" type="checkbox" required />
              <div class="text-xs text-on-surface-variant leading-relaxed">
                I agree to the <a class="text-primary font-bold hover:underline" href="#">Data Privacy Policy</a> of Benguet State University.
              </div>
            </div>

            <div class="flex flex-col gap-4 pt-4">
              <button :disabled="loading" class="w-full bg-primary text-white py-4 rounded-full font-headline font-bold text-sm tracking-widest uppercase hover:opacity-90 transition-all shadow-lg flex items-center justify-center gap-2" type="submit">
                {{ loading ? 'Processing...' : 'Initialize Registration' }}
                <span class="material-symbols-outlined text-[18px]">arrow_right_alt</span>
              </button>
            </div>
          </form>
        </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';

const router = useRouter();
const loading = ref(false);
const error   = ref('');
const success = ref('');

const form = reactive({
  first_name:       '',
  middle_name:      '',
  last_name:        '',
  user_role:        '',
  email:            '',
  password:         '',
  confirm_password: '',
  terms:            false,
});

const handleRegister = async () => {
  if (form.password !== form.confirm_password) {
    error.value = 'Passwords do not match.';
    return;
  }

  loading.value = true;
  error.value   = '';

  try {
    const response = await api.post('register', {
      first_name:       form.first_name,
      middle_name:      form.middle_name || null,
      last_name:        form.last_name,
      user_role:        form.user_role,
      email:            form.email,
      password:         form.password,
      confirm_password: form.confirm_password,
    });

    success.value = response.data.message;
    setTimeout(() => router.push('/login'), 2000);
  } catch (err) {
    console.error('Registration error:', err);

    if (err && err.messages) {
      error.value = err.messages.error || 'Registration failed';
    } else if (err && err.message) {
      error.value = err.message;
    } else {
      error.value = 'Registration failed. Please try again.';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
</style>