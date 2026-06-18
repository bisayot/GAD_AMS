<template>
  <div class="register-page bg-background text-on-surface font-body pt-32 pb-16 px-4 flex flex-col items-center justify-center">
    <div class="max-w-6xl w-full grid grid-cols-1 md:grid-cols-12 gap-8 items-start relative z-10">
      
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
            Join the Benguet State University Gender and Development portal. All fields are required.
          </p>
        </div>
        <div class="relative w-full aspect-square rounded-xl overflow-hidden shadow-2xl">
          <img alt="Academic Building" class="object-cover w-full h-full grayscale hover:grayscale-0 transition-all duration-700" src="/images/img_16.jpg" />
          <div class="absolute inset-0 bg-gradient-to-t from-primary/60 to-transparent"></div>
          <div class="absolute bottom-6 left-6 right-6 text-white">
            <p class="text-sm font-label uppercase tracking-widest opacity-80 mb-2">Heritage & Excellence</p>
            <p class="font-headline font-bold text-xl">Serving the Highlands since 1916.</p>
          </div>
        </div>
      </div>

      <div class="md:col-span-7 bg-surface-container-lowest rounded-xl p-8 md:p-12 shadow-[0_8px_24px_rgba(26,28,29,0.04)] border border-outline-variant/10">
        <form @submit.prevent="handleRegister" class="space-y-8">
          <div v-if="error" class="rounded-md bg-red-100 border border-red-200 text-red-700 px-4 py-3 text-sm">{{ error }}</div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="flex flex-col gap-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">First Name <span class="text-red-500">*</span></label>
              <input v-model="form.first_name" placeholder="Juan" class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface" required />
            </div>
            <div class="flex flex-col gap-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">Middle Name</label>
              <input v-model="form.middle_name" placeholder="Dela" class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface" />
            </div>
            <div class="flex flex-col gap-2 md:col-span-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">Last Name <span class="text-red-500">*</span></label>
              <input v-model="form.last_name" placeholder="Santos" class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface" required />
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">Role <span class="text-red-500">*</span></label>
              <select v-model="form.user_role" class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface" required>
                <option value="Non-TWG">Non-TWG</option>
                <option value="TWG">TWG</option>
                <option value="Staff">Staff</option>
                <option value="Director">Director</option>
              </select>
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">Department / Office <span class="text-red-500">*</span></label>
              
              <input v-if="form.user_role === 'Staff' || form.user_role === 'Director'" 
                     value="Gender and Development Office" disabled 
                     class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface opacity-70 cursor-not-allowed" />
              
              <div v-else class="space-y-2 relative" ref="dropdownRef">
                <!-- Searchable Combobox -->
                <div class="relative">
                  <input 
                    v-model="officeSearchQuery" 
                    @focus="isDropdownOpen = true"
                    @input="isDropdownOpen = true; handleSearchInput()"
                    placeholder="Search or Select your unit"
                    class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface focus:ring-2 focus:ring-primary outline-none transition-all"
                    :required="!form.office_unit_id && !isAddingNew"
                  />
                  <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
                
                <!-- Dropdown List -->
                <div v-if="isDropdownOpen" class="absolute z-50 w-full mt-1 bg-surface-container-lowest border border-outline-variant/20 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                  <div 
                    v-for="unit in filteredOffices" 
                    :key="unit.unit_id" 
                    @click="selectOffice(unit)"
                    class="px-4 py-3 hover:bg-surface-container-low cursor-pointer text-on-surface transition-colors"
                  >
                    {{ unit.unit_name }}
                  </div>
                  
                  <div v-if="filteredOffices.length === 0" class="px-4 py-3 text-slate-500 italic text-sm">
                    No matching offices found.
                  </div>

                  <div 
                    @click="selectAddNew"
                    class="px-4 py-3 font-bold text-primary hover:bg-primary/10 cursor-pointer border-t border-outline-variant/20 transition-colors flex items-center gap-2"
                  >
                    <span class="material-symbols-outlined text-sm">add_circle</span>
                    Add "{{ officeSearchQuery || 'New Office' }}"
                  </div>
                </div>
                
                <div v-if="isAddingNew" class="mt-2 animate-fade-in">
                  <label class="text-[10px] uppercase tracking-widest font-label font-bold text-primary mb-1 block">New Office Name</label>
                  <input v-model="newOfficeName" placeholder="Enter full new office name" 
                         class="w-full bg-surface-container-low border border-primary/50 focus:border-primary rounded-lg px-4 py-3 text-on-surface outline-none focus:ring-1 focus:ring-primary transition-all" required />
                </div>
              </div>
            </div>

            <div class="flex flex-col gap-2 md:col-span-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">
                {{ form.user_role !== 'Non-TWG' ? 'Institutional Email' : 'Email Address' }} <span class="text-red-500">*</span>
              </label>
              <input v-model="form.email" type="email" :placeholder="form.user_role !== 'Non-TWG' ? 'name@bsu.edu.ph' : 'name@example.com'" class="bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface" required />
            </div>
            <div class="flex flex-col gap-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">Password <span class="text-red-500">*</span></label>
              <div class="relative">
                <input v-model="form.password" :type="showPass ? 'text' : 'password'" placeholder="••••••••" class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface" required />
                <button type="button" @click="showPass = !showPass" class="absolute right-3 top-3 text-slate-400"><span class="material-symbols-outlined">{{ showPass ? 'visibility_off' : 'visibility' }}</span></button>
              </div>
            </div>
            <div class="flex flex-col gap-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500">Confirm Password <span class="text-red-500">*</span></label>
              <div class="relative">
                <input v-model="form.confirm_password" :type="showConfirmPass ? 'text' : 'password'" placeholder="••••••••" class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-on-surface" required />
                <button type="button" @click="showConfirmPass = !showConfirmPass" class="absolute right-3 top-3 text-slate-400"><span class="material-symbols-outlined">{{ showConfirmPass ? 'visibility_off' : 'visibility' }}</span></button>
              </div>
            </div>
          </div>

          <!-- Turnstile Widget -->
          <TurnstileWidget ref="turnstileRef" @verify="onTurnstileVerify" />

          <!-- Privacy Policy Checkbox -->
          <div class="flex items-start gap-3 pt-2">
            <div class="flex items-center h-5">
              <input id="privacy" v-model="form.privacyAccepted" type="checkbox" class="w-4 h-4 bg-surface-container-low border-none rounded text-primary focus:ring-primary" required />
            </div>
            <div class="text-sm">
              <label for="privacy" class="text-on-surface-variant font-medium">
                I agree to the 
                <button type="button" @click="showPrivacyModal = true" class="text-primary hover:underline font-bold">Privacy Policy</button>
              </label>
            </div>
          </div>

          <div class="flex flex-col gap-4 pt-4">
            <button :disabled="loading" class="w-full bg-primary text-white py-4 rounded-full font-bold uppercase transition-all shadow-lg" type="submit">
              {{ loading ? 'Processing...' : 'Register' }}
            </button>
            <button type="button" @click="router.back()" class="w-full border border-outline text-on-surface py-4 rounded-full font-bold uppercase hover:bg-surface-container-low transition-all">
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div v-if="showPrivacyModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 animate-fade-in">
      <div class="bg-surface-container-lowest rounded-2xl w-full max-w-2xl shadow-2xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-outline-variant/20 flex justify-between items-center">
          <h2 class="text-2xl font-headline font-bold text-primary">Privacy Policy</h2>
          <button type="button" @click="showPrivacyModal = false" class="text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">close</span>
          </button>
        </div>
        
        <div class="p-6 overflow-y-auto font-body text-on-surface-variant space-y-4 text-left">
          <p class="font-bold text-on-surface">Data Privacy Act of 2012 (RA 10173) Consent</p>
          <p>
            By registering for an account on the Benguet State University Gender and Development (BSU GAD) Portal, you acknowledge and agree to the following terms regarding the collection, use, and processing of your personal data:
          </p>
          <ul class="list-disc pl-5 space-y-2">
            <li><strong>Purpose of Data Collection:</strong> Your personal information (e.g., name, institutional email, office/department, and role) will be collected and processed solely for the purpose of managing user access, facilitating system functionalities, and maintaining official records within the GAD Portal.</li>
            <li><strong>Data Protection:</strong> We are committed to safeguarding your personal information. Appropriate security measures are in place to prevent unauthorized access, disclosure, modification, or destruction of your data.</li>
            <li><strong>Data Sharing:</strong> Your personal data will only be accessible to authorized personnel of the BSU GAD Office and system administrators. It will not be shared with third parties without your explicit consent, except as required by law.</li>
            <li><strong>User Rights:</strong> You have the right to access, correct, or request the deletion of your personal data stored in the portal. You may also withdraw your consent at any time, which may result in the deactivation of your account.</li>
          </ul>
          <p>
            By clicking "I agree," you signify your understanding of this privacy policy and give your explicit consent to the BSU GAD Office to process your personal data in accordance with the aforementioned terms and the provisions of the Data Privacy Act of 2012.
          </p>
        </div>

        <div class="p-6 border-t border-outline-variant/20 bg-surface-container-low rounded-b-2xl flex justify-end">
          <button type="button" @click="acceptPrivacy" class="bg-primary text-white px-6 py-2 rounded-full font-bold uppercase transition-all shadow-md hover:shadow-lg">
            I Understand & Agree
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import TurnstileWidget from '../components/TurnstileWidget.vue';

const router = useRouter();
const loading = ref(false);
const error = ref('');
const showPass = ref(false);
const showConfirmPass = ref(false);
const turnstileToken = ref('');
const showPrivacyModal = ref(false);
const turnstileRef = ref(null);

const acceptPrivacy = () => {
  form.privacyAccepted = true;
  showPrivacyModal.value = false;
};

const onTurnstileVerify = (token) => {
  turnstileToken.value = token;
};

const officeUnits = ref([]);
const isAddingNew = ref(false);
const newOfficeName = ref('');

// Combobox logic
const dropdownRef = ref(null);
const isDropdownOpen = ref(false);
const officeSearchQuery = ref('');

const filteredOffices = computed(() => {
  if (!officeSearchQuery.value) return officeUnits.value;
  const q = officeSearchQuery.value.toLowerCase();
  return officeUnits.value.filter(u => u.unit_name.toLowerCase().includes(q));
});

const handleSearchInput = () => {
  isAddingNew.value = false;
  form.office_unit_id = ''; 
};

const selectOffice = (unit) => {
  form.office_unit_id = unit.unit_id;
  officeSearchQuery.value = unit.unit_name;
  isAddingNew.value = false;
  isDropdownOpen.value = false;
};

const selectAddNew = () => {
  form.office_unit_id = 'add_new';
  isAddingNew.value = true;
  newOfficeName.value = officeSearchQuery.value;
  isDropdownOpen.value = false;
};

const handleClickOutside = (e) => {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    isDropdownOpen.value = false;
  }
};

const form = reactive({
  first_name: '', middle_name: '', last_name: '',
  user_role: 'Non-TWG', office_unit_id: '',
  email: '', password: '', confirm_password: '',
  privacyAccepted: false
});

const fetchOffices = async () => {
  try {
    const res = await api.get('office_units');
    officeUnits.value = res.data;
  } catch (err) {
    console.error("Fetch error:", err);
  }
};



onMounted(() => {
  fetchOffices();
  document.addEventListener('mousedown', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('mousedown', handleClickOutside);
});

const handleRegister = async () => {
  if (form.user_role !== 'Non-TWG' && !form.email.toLowerCase().endsWith('@bsu.edu.ph')) {
    return error.value = 'This role requires a valid institutional email (@bsu.edu.ph).';
  }
  if (form.password !== form.confirm_password) {
    return error.value = 'Passwords do not match.';
  }
  if (!turnstileToken.value) {
    return error.value = 'Please complete the security check.';
  }
  if (!form.privacyAccepted) {
    return error.value = 'You must agree to the Privacy Policy.';
  }
  
  loading.value = true;
  error.value = null; 

  try {
    let departmentId = form.office_unit_id;

    if (form.user_role === 'Staff' || form.user_role === 'Director') {
      departmentId = 1; // 1 = Gender and Development Office in DB
    } else {
      if (isAddingNew.value && newOfficeName.value) {
        const res = await api.post('add_office', { 
          unit_name: newOfficeName.value 
        });
        departmentId = res.data.new_id;
      }
    }

    const payload = {
      fullname: `${form.first_name} ${form.middle_name} ${form.last_name}`.replace(/\s+/g, ' ').trim(),
      first_name: form.first_name,
      middle_name: form.middle_name,
      last_name: form.last_name,
      department: departmentId, 
      email: form.email,
      password: form.password,
      confirm_password: form.confirm_password,
      user_role: form.user_role,
      turnstile_token: turnstileToken.value
    };

    await api.post('register', payload);
    
    router.push('/login');

  } catch (err) {
    console.error("Registration Error", err);
    if (turnstileRef.value) turnstileRef.value.reset();
    turnstileToken.value = '';
    
    if (err && err.messages) {
      const messages = err.messages;
      error.value = typeof messages === 'string' 
        ? messages 
        : Object.values(messages).join(', ');
    } else if (err && err.message) {
      error.value = err.message;
    } else {
      error.value = 'Registration failed. Please check your input.';
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
</style>