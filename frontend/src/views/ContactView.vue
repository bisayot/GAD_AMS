<template>
  <div class="contact-page bg-background text-on-surface font-body selection:bg-primary-fixed selection:text-on-primary-fixed pt-20 pb-24 max-w-7xl mx-auto px-8">
    <!-- Hero Section -->
      <section class="mb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-end">
          <div class="lg:col-span-7">
            <span class="inline-block px-4 py-1 rounded-full bg-secondary-container text-on-secondary-container text-xs font-label font-bold uppercase tracking-widest mb-6">Connect With Us</span>
            <h1 class="text-5xl md:text-7xl font-headline font-extrabold tracking-tighter text-primary leading-tight mb-8">
              University Support Hub
            </h1>
            <p class="text-xl text-on-surface-variant max-w-2xl leading-relaxed">
              The Gender and Development office is committed to providing accessible support. Compose a message below or visit the main office.
            </p>
          </div>
          <div class="lg:col-span-5 hidden lg:block">
            <div class="h-64 rounded-xl overflow-hidden ambient-shadow relative border border-outline-variant/10">
              <img alt="BSU GAD Office Environment" class="w-full h-full object-cover grayscale opacity-40 hover:grayscale-0 transition-all duration-700" src="/images/img_3.jpg"/>
              <div class="absolute inset-0 bg-primary/10 mix-blend-multiply"></div>
            </div>
          </div>
        </div>
      </section>

      <!-- Main Office & Form Section -->
      <section class="mb-28 grid grid-cols-1 lg:grid-cols-2 gap-16">
        <!-- Contact Info -->
        <div class="space-y-12">
          <div class="p-10 rounded-xl bg-surface-container-low border border-outline-variant/15">
            <h2 class="text-3xl font-headline font-bold text-primary mb-8 tracking-tight">Main GAD Office</h2>
            <div class="space-y-8">
              <div class="flex items-start gap-6">
                <div class="w-12 h-12 rounded-full bg-primary/5 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-primary">location_on</span>
                </div>
                <div>
                  <p class="text-sm font-label uppercase tracking-widest text-secondary font-bold mb-1">Campus Address</p>
                  <p class="text-lg text-on-surface font-medium leading-snug">Behind LANDBANK - La Trinidad<br/>Benguet State University Compound Km5, Magsaysay Avenue,<br/>Baguio - La Trinidad - Bontoc Rd, La Trinidad, 2601 Benguet</p>
                </div>
              </div>
              <div class="flex items-start gap-6">
                <div class="w-12 h-12 rounded-full bg-primary/5 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-primary">phone_in_talk</span>
                </div>
                <div>
                  <p class="text-sm font-label uppercase tracking-widest text-secondary font-bold mb-1">Contact Numbers</p>
                  <p class="text-lg text-on-surface font-medium leading-snug">(074) 422-2127 / +63 9XX XXX XXXX</p>
                </div>
              </div>
              <div class="flex items-start gap-6">
                <div class="w-12 h-12 rounded-full bg-primary/5 flex items-center justify-center shrink-0">
                  <span class="material-symbols-outlined text-primary">mail</span>
                </div>
                <div>
                  <p class="text-sm font-label uppercase tracking-widest text-secondary font-bold mb-1">Email Inquiry</p>
                  <p class="text-lg text-on-surface font-medium leading-snug">gad.office@bsu.edu.ph</p>
                </div>
              </div>
            </div>
            <div class="mt-12 pt-8 border-t border-outline-variant/20 flex items-center gap-4 text-primary">
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
        <div class="bg-surface-container-lowest p-10 rounded-xl ambient-shadow border border-outline-variant/10 h-fit">
          <h3 class="text-2xl font-headline font-bold text-on-surface mb-2">Send an Inquiry</h3>
          <p class="text-on-surface-variant mb-10 leading-relaxed">Have a question regarding GAD initiatives or need assistance? Fill out the form below and our staff will get back to you.</p>
          <form @submit.prevent="submitForm" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-xs font-label uppercase tracking-widest text-on-surface-variant font-bold mb-2">Full Name</label>
                <input v-model="form.name" class="w-full bg-surface-container-low border-none rounded-lg focus:ring-0 focus:border-b-2 focus:border-primary transition-all p-4 text-on-surface" placeholder="Juan Dela Cruz" type="text" required />
              </div>
              <div>
                <label class="block text-xs font-label uppercase tracking-widest text-on-surface-variant font-bold mb-2">Email Address</label>
                <input v-model="form.email" class="w-full bg-surface-container-low border-none rounded-lg focus:ring-0 focus:border-b-2 focus:border-primary transition-all p-4 text-on-surface" placeholder="juan@example.edu.ph" type="email" required />
              </div>
            </div>
            <div>
              <label class="block text-xs font-label uppercase tracking-widest text-on-surface-variant font-bold mb-2">Subject</label>
              <input v-model="form.subject" class="w-full bg-surface-container-low border-none rounded-lg focus:ring-0 focus:border-b-2 focus:border-primary transition-all p-4 text-on-surface" placeholder="What is this regarding?" type="text" required />
            </div>
            <div>
              <label class="block text-xs font-label uppercase tracking-widest text-on-surface-variant font-bold mb-2">Message</label>
              <textarea v-model="form.message" class="w-full bg-surface-container-low border-none rounded-lg focus:ring-0 focus:border-b-2 focus:border-primary transition-all p-4 text-on-surface" placeholder="How can we help you today?" rows="5" required></textarea>
            </div>
            <button :disabled="isSubmitting" class="w-full py-5 rounded-full bg-primary text-white font-headline font-bold tracking-tight hover:opacity-95 shadow-lg active:scale-[0.98] transition-all disabled:opacity-50" type="submit">
              {{ isSubmitting ? 'Dispatching...' : 'Dispatch Message' }}
            </button>
            
            <div v-if="emailLimitReached" class="mt-4 p-4 bg-primary/5 rounded-lg border border-primary/10 flex items-start gap-3">
              <span class="material-symbols-outlined text-primary text-[20px]">info</span>
              <p class="text-sm text-on-surface-variant leading-relaxed">
                Our system uses a free email service with daily limits. If you don't hear back from us, please email us directly at <a href="mailto:gad.office@bsu.edu.ph" class="text-primary font-bold hover:underline">gad.office@bsu.edu.ph</a>.
              </p>
            </div>
          </form>
        </div>
      </section>
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
</style>
