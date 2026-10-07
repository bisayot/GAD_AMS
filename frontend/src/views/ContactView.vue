<template>
  <div class="contact-page text-on-background font-body bg-background" style="min-height: 100vh;">
    <div class="pt-20 pb-24 max-w-7xl mx-auto px-8">
      <!-- Hero Section -->
      <section class="mb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-end">
          <div class="lg:col-span-7">
            <span class="inline-block px-4 py-1 rounded-full bg-primary/20 text-primary text-xs font-label font-bold uppercase tracking-widest mb-6">Connect With Us</span>
            <h1 class="text-5xl md:text-7xl font-headline font-extrabold tracking-tighter text-on-background leading-tight mb-8">
              University Support Hub
            </h1>
            <p class="text-xl text-on-background max-w-2xl leading-relaxed">
              The Gender and Development office is committed to providing accessible support. Compose a message below or visit the main office.
            </p>
          </div>
          <div class="lg:col-span-5 hidden lg:flex items-center justify-center">
            <div class="loading-container">
              <div class="ground"></div>
              <div class="skeleton">
                <div class="head">
                  <div class="eye left"></div>
                  <div class="eye right"></div>
                  <div class="mouth"></div>
                </div>
                <div class="body"></div>
                <div class="arm left"></div>
                <div class="arm right"></div>
                <div class="leg left"></div>
                <div class="leg right"></div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Main Office & Form Section -->
      <section class="mb-28 grid grid-cols-1 lg:grid-cols-2 gap-16">
        <!-- Contact Info -->
        <div class="space-y-12">
          <div class="p-10 rounded-xl bg-surface border border-outline-variant">
            <h2 class="text-3xl font-headline font-bold text-on-surface mb-8 tracking-tight">Main GAD Office</h2>
            <div class="space-y-8">
              <div class="flex items-start gap-6">
                <div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-primary">location_on</span>
                </div>
                <div>
                  <p class="text-sm font-label uppercase tracking-widest text-primary font-bold mb-1">Campus Address</p>
                  <p class="text-lg text-on-surface font-medium leading-snug">Behind LANDBANK, BSU Compound, Km. 5, La Trinidad, 2601 Benguet, Philippines</p>
                </div>
              </div>
              <div class="flex items-start gap-6">
                <div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-primary">phone_in_talk</span>
                </div>
                <div>
                  <p class="text-sm font-label uppercase tracking-widest text-primary font-bold mb-1">Contact Numbers</p>
                  <p class="text-lg text-on-surface font-medium leading-snug">(074) 422-2127</p>
                </div>
              </div>
              <div class="flex items-start gap-6">
                <div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-primary">mail</span>
                </div>
                <div>
                  <p class="text-sm font-label uppercase tracking-widest text-primary font-bold mb-1">Email Inquiry</p>
                  <p class="text-lg text-on-surface font-medium leading-snug">gad.office@bsu.edu.ph</p>
                </div>
              </div>
            </div>
            <div class="mt-12 pt-8 border-t border-outline-variant flex items-center gap-4 text-primary">
              <span class="material-symbols-outlined fill-icon">schedule</span>
              <p class="text-sm font-medium">Monday — Friday: 8:00 AM - 5:00 PM</p>
            </div>
          </div>
          <div class="rounded-xl overflow-hidden h-64 ambient-shadow border border-outline-variant/15">
            <iframe 
              src="https://maps.google.com/maps?q=Benguet%20State%20University,%20La%20Trinidad&t=&z=16&ie=UTF8&iwloc=&output=embed" 
              width="100%" 
              height="100%" 
              frameborder="0" 
              style="border:0;" 
              allowfullscreen="" 
              aria-hidden="false" 
              tabindex="0"
              class="w-full h-full"
            ></iframe>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-surface p-10 rounded-xl ambient-shadow border border-outline-variant h-fit">
          <h3 class="text-2xl font-headline font-bold text-on-surface mb-2">Send an Inquiry</h3>
          <p class="text-on-surface-variant mb-10 leading-relaxed">Have a question regarding GAD initiatives or need assistance? Fill out the form below and our staff will get back to you.</p>
          <form @submit.prevent="submitForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-xs font-label uppercase tracking-widest text-primary font-bold mb-2">Full Name</label>
                <input v-model="form.name" class="w-full bg-surface-variant border-none rounded-lg focus:ring-0 focus:border-b-2 focus:border-primary transition-all p-4 text-on-surface placeholder-on-surface-variant" placeholder="Juan Dela Cruz" type="text" required />
              </div>
              <div>
                <label class="block text-xs font-label uppercase tracking-widest text-primary font-bold mb-2">Email Address</label>
                <input v-model="form.email" class="w-full bg-surface-variant border-none rounded-lg focus:ring-0 focus:border-b-2 focus:border-primary transition-all p-4 text-on-surface placeholder-on-surface-variant" placeholder="juan@example.edu.ph" type="email" required />
              </div>
            </div>
            <div>
              <label class="block text-xs font-label uppercase tracking-widest text-primary font-bold mb-2">Subject</label>
              <input v-model="form.subject" class="w-full bg-surface-variant border-none rounded-lg focus:ring-0 focus:border-b-2 focus:border-primary transition-all p-4 text-on-surface placeholder-on-surface-variant" placeholder="What is this regarding?" type="text" required />
            </div>
            <div>
              <label class="block text-xs font-label uppercase tracking-widest text-primary font-bold mb-2">Message</label>
              <textarea v-model="form.message" class="w-full bg-surface-variant border-none rounded-lg focus:ring-0 focus:border-b-2 focus:border-primary transition-all p-4 text-on-surface placeholder-on-surface-variant" placeholder="How can we help you today?" rows="5" required></textarea>
            </div>
            <button :disabled="isSubmitting" class="w-full py-5 rounded-full bg-primary text-on-primary font-headline font-bold tracking-tight hover:opacity-90 shadow-lg active:scale-[0.98] transition-all disabled:opacity-50" type="submit">
              {{ isSubmitting ? 'Dispatching...' : 'Dispatch Message' }}
            </button>
            
            <div v-if="emailLimitReached" class="mt-4 p-4 bg-primary-container rounded-lg border border-primary flex items-start gap-3">
              <span class="material-symbols-outlined text-primary text-[20px]">info</span>
              <p class="text-sm text-on-surface-variant leading-relaxed">
                If you don't hear back from us, please email us directly at <a href="mailto:gad.office@bsu.edu.ph" class="text-primary font-bold hover:underline">gad.office@bsu.edu.ph</a>.
              </p>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import api from '../api';
import Swal from 'sweetalert2';

const form = reactive({
  name: '',
  email: '',
  subject: '',
  message: ''
});

const isSubmitting = ref(false);
const emailLimitReached = ref(false);

const submitForm = async () => {
  if (isSubmitting.value) return;
  isSubmitting.value = true;
  
  try {
    const response = await api.post('/contact', form);
    if (response.data && response.data.status === 201) {
      Swal.fire({
        icon: 'success',
        title: 'Sent Successfully!',
        text: 'Thank you for your inquiry! We will get back to you soon.',
        confirmButtonColor: '#6100a4'
      });
      form.name = '';
      form.email = '';
      form.subject = '';
      form.message = '';
      if (response.data.email_sent === false) {
        emailLimitReached.value = true;
      } else {
        emailLimitReached.value = false;
      }
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Something went wrong. Please try again.',
        confirmButtonColor: '#6100a4'
      });
    }
  } catch (error) {
    console.error('Error submitting form:', error);
    Swal.fire({
      icon: 'error',
      title: 'Connection Error',
      text: 'Failed to send inquiry. Please check your connection and try again.',
      confirmButtonColor: '#6100a4'
    });
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
.ambient-shadow {
  box-shadow: 0 8px 24px rgba(26, 28, 29, 0.04);
}
.fill-icon {
  font-variation-settings: 'FILL' 1;
}

/* Sliding Person Animation */
.loading-container {
  width: 300px;
  height: 150px;
  position: relative;
  overflow: hidden;
}

.ground {
  position: absolute;
  bottom: 0;
  width: 100%;
  height: 5px;
  background: linear-gradient(90deg, transparent, #555, transparent);
  animation: ground-move 2s linear infinite;
}

@keyframes ground-move {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

.skeleton {
  position: absolute;
  bottom: 12px;
  left: 0;
  width: 100px;
  height: 80px;
  animation: crawl 2s linear infinite;
}

@keyframes crawl {
  0% { transform: translateX(-100px); }
  100% { transform: translateX(300px); }
}

.head {
  position: absolute;
  top: 0;
  left: 30px;
  width: 30px;
  height: 30px;
  background-color: #fff;
  border-radius: 50%;
  animation: head-bob 0.5s ease-in-out infinite alternate;
  box-shadow: inset 0 -2px 0 #ccc;
}

@keyframes head-bob {
  0% { transform: translateY(0); }
  100% { transform: translateY(-5px); }
}

.eye {
  position: absolute;
  width: 5px;
  height: 5px;
  background-color: #222;
  border-radius: 50%;
  top: 11px;
}

.eye.left { left: 10px; }
.eye.right { left: 20px; }

.mouth {
  position: absolute;
  width: 12px;
  height: 5px;
  background-color: #222;
  border-radius: 0 0 5px 5px;
  top: 18px;
  left: 11px;
  animation: mouth-talk 0.5s ease-in-out infinite alternate;
}

@keyframes mouth-talk {
  0% { height: 3px; }
  100% { height: 6px; }
}

.body {
  position: absolute;
  top: 30px;
  left: 35px;
  width: 20px;
  height: 35px;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: inset 0 -2px 0 #ccc;
}

.arm {
  position: absolute;
  width: 10px;
  height: 35px;
  background-color: #fff;
  top: 30px;
  border-radius: 50px;
  box-shadow: inset 0 -2px 0 #ccc;
}

.arm.left {
  left: 25px;
  transform-origin: top center;
  animation: arm-left 1s ease-in-out infinite;
}

.arm.right {
  left: 55px;
  transform-origin: top center;
  animation: arm-right 1s ease-in-out infinite;
}

@keyframes arm-left {
  0%, 100% { transform: rotate(30deg); }
  50% { transform: rotate(-20deg); }
}

@keyframes arm-right {
  0%, 100% { transform: rotate(-20deg); }
  50% { transform: rotate(30deg); }
}

.leg {
  position: absolute;
  width: 10px;
  height: 40px;
  background-color: #fff;
  top: 60px;
  border-radius: 50px;
  box-shadow: inset 0 -2px 0 #ccc;
}

.leg.left {
  left: 35px;
  transform-origin: top center;
  animation: leg-left 1s ease-in-out infinite;
}

.leg.right {
  left: 45px;
  transform-origin: top center;
  animation: leg-right 1s ease-in-out infinite;
}

@keyframes leg-left {
  0%, 100% { transform: rotate(10deg); }
  50% { transform: rotate(-30deg); }
}

@keyframes leg-right {
  0%, 100% { transform: rotate(-30deg); }
  50% { transform: rotate(10deg); }
}

/* ==========================================================================
   DARK PURPLE MODE FOR CONTACT PAGE
   ========================================================================== */
:global(.dark) .contact-page,
.dark .contact-page {
  background-color: #1f0b35 !important;
  color: #f5efff !important;
}

:global(.dark) .contact-page .bg-surface,
.dark .contact-page .bg-surface {
  background-color: #2b1147 !important;
  border-color: #532385 !important;
}

:global(.dark) .contact-page .bg-surface-variant,
.dark .contact-page .bg-surface-variant {
  background-color: #38165c !important;
  color: #f5efff !important;
}

:global(.dark) .contact-page .bg-primary-container,
.dark .contact-page .bg-primary-container {
  background-color: #38165c !important;
}

:global(.dark) .contact-page .text-primary,
.dark .contact-page .text-primary {
  color: #deb7ff !important;
}

:global(.dark) .contact-page .text-on-background,
.dark .contact-page .text-on-background,
:global(.dark) .contact-page .text-on-surface,
.dark .contact-page .text-on-surface {
  color: #f5efff !important;
}

:global(.dark) .contact-page .text-on-surface-variant,
.dark .contact-page .text-on-surface-variant {
  color: #deb7ff !important;
}

:global(.dark) .contact-page .border-outline-variant,
.dark .contact-page .border-outline-variant {
  border-color: #532385 !important;
}

:global(.dark) .contact-page input,
.dark .contact-page input,
:global(.dark) .contact-page textarea,
.dark .contact-page textarea {
  background-color: #38165c !important;
  color: #f5efff !important;
  border: 1px solid #532385 !important;
}

:global(.dark) .contact-page input::placeholder,
.dark .contact-page input::placeholder,
:global(.dark) .contact-page textarea::placeholder,
.dark .contact-page textarea::placeholder {
  color: #b592db !important;
}
</style>
