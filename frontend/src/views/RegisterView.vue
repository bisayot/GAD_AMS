<template>
  <div class="register-page font-body pt-32 pb-16 px-4 flex flex-col items-center justify-center min-h-screen relative z-0 overflow-hidden">
    <!-- Background Decorative Elements -->
    <div class="auth-bg-blob auth-bg-blob-1"></div>
    <div class="auth-bg-blob auth-bg-blob-2"></div>

    <div class="w-full max-w-4xl relative z-10">
      
      <div class="text-center mb-10 flex flex-col items-center">
        <div class="inline-flex items-center gap-2 auth-badge px-4 py-1.5 rounded-full border mb-6">
          <span class="material-symbols-outlined text-[18px]">account_circle</span>
          <span class="text-xs font-bold uppercase tracking-[0.2em] font-label">Create Account</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold font-headline tracking-tighter auth-title leading-tight">
          Welcome to <span class="text-purple-600 dark:text-purple-400">GAD-AMS Portal.</span>
        </h1>
      </div>

      <div class="auth-card rounded-2xl p-8 md:p-12 shadow-2xl border relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-blue-500"></div>
        <form @submit.prevent="handleRegister" class="space-y-8">
          <div v-if="error" class="rounded-lg bg-red-50 dark:bg-red-950/60 border border-red-300 dark:border-red-800 text-red-900 dark:text-red-200 px-4 py-3 text-sm flex items-start gap-2.5 font-medium shadow-sm">
            <span class="material-symbols-outlined text-red-700 dark:text-red-400 text-base shrink-0 mt-0.5">error</span>
            <span>{{ error }}</span>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="flex flex-col gap-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-on-surface-variant">First Name <span class="text-red-500">*</span></label>
              <input v-model="form.first_name" placeholder="Juan" class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-3 text-on-surface focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all placeholder:text-on-surface-variant/60" required />
            </div>
            <div class="flex flex-col gap-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-on-surface-variant">Middle Name</label>
              <input v-model="form.middle_name" placeholder="Dela" class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-3 text-on-surface focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all placeholder:text-on-surface-variant/60" />
            </div>
            <div class="flex flex-col gap-2 md:col-span-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-on-surface-variant">Last Name <span class="text-red-500">*</span></label>
              <input v-model="form.last_name" placeholder="Santos" class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-3 text-on-surface focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all placeholder:text-on-surface-variant/60" required />
            </div>

            <div class="flex flex-col gap-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-on-surface-variant">Role <span class="text-red-500">*</span></label>
              <div class="relative">
                <select v-model="form.user_role" class="w-full bg-surface-container border border-outline-variant rounded-lg pl-4 pr-12 py-3 text-on-surface focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all appearance-none cursor-pointer">
                  <option value="Non-TWG">Proponent</option>
                  <option value="TWG">TWG</option>
                </select>
                <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">expand_more</span>
              </div>
            </div>

            <!-- Campus Location -->
            <div class="flex flex-col gap-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-on-surface-variant">Campus Location <span class="text-red-500">*</span></label>
              <div class="relative">
                <select v-model="form.campus_location" @change="handleCampusChange" class="w-full bg-surface-container border border-outline-variant rounded-lg pl-4 pr-12 py-3 text-on-surface focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all appearance-none cursor-pointer">
                  <option value="La Trinidad Campus">La Trinidad Campus</option>
                  <option value="Buguias Campus">Buguias Campus</option>
                  <option value="Bokod Campus">Bokod Campus</option>
                </select>
                <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">expand_more</span>
              </div>
            </div>

            <!-- College / Office -->
            <div class="flex flex-col gap-2 md:col-span-2">
              <div class="flex items-center justify-between">
                <label class="text-xs uppercase tracking-widest font-label font-bold text-on-surface-variant">College / Office <span class="opacity-70 font-normal lowercase">(optional)</span></label>
                <button 
                  type="button" 
                  @click="showOfficeNotice = !showOfficeNotice" 
                  class="text-[11px] text-purple-600 dark:text-purple-400 hover:underline flex items-center gap-1 font-medium"
                >
                  <span class="material-symbols-outlined text-[14px]">info</span>
                  Guideline
                </button>
              </div>

              <!-- Guideline Notice Banner / Popover -->
              <transition 
                enter-active-class="transition duration-200 ease-out" 
                enter-from-class="opacity-0 -translate-y-1" 
                enter-to-class="opacity-100 translate-y-0" 
                leave-active-class="transition duration-150 ease-in" 
                leave-from-class="opacity-100 translate-y-0" 
                leave-to-class="opacity-0 -translate-y-1"
              >
                <div v-if="showOfficeNotice" class="p-3 bg-amber-500/10 border border-amber-500/30 rounded-lg flex items-start gap-2.5 text-xs shadow-sm">
                  <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-base shrink-0 mt-0.5">campaign</span>
                  <div class="flex-1">
                    <div class="font-bold text-amber-800 dark:text-amber-300 flex items-center justify-between">
                      <span>Office Selection Guideline</span>
                      <button type="button" @click="showOfficeNotice = false" class="text-on-surface-variant hover:text-on-surface text-xs font-bold ml-2">✕</button>
                    </div>
                    <p class="text-on-surface-variant mt-0.5 leading-relaxed">
                      Please select or enter the <strong>full official name</strong> of your College or Office (e.g., <em>College of Agriculture</em>, <em>Accounting Office</em>).
                      <strong>Never use abbreviations or acronyms</strong> (such as <em>CA</em>, <em>CTE</em>, <em>CIS</em>, or <em>Dept</em>).
                    </p>
                  </div>
                </div>
              </transition>
              
              <div class="space-y-2 relative" ref="dropdownRef">
                <!-- Searchable Combobox -->
                <div class="relative">
                  <input 
                    v-model="officeSearchQuery" 
                    @focus="handleOfficeFocus"
                    @input="handleSearchInput"
                    placeholder="Search or Select college / office"
                    class="w-full bg-surface-container border border-outline-variant rounded-lg pl-4 pr-12 py-3 text-on-surface focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all placeholder:text-on-surface-variant/60 truncate cursor-pointer text-sm sm:text-base"
                  />
                  <span 
                    class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none transition-transform duration-200"
                    :class="{ 'rotate-180': isDropdownOpen }"
                  >
                    expand_more
                  </span>
                </div>
                
                <!-- Dropdown List -->
                <div v-if="isDropdownOpen" class="dropdown-list absolute z-50 w-full mt-1 border rounded-lg shadow-xl max-h-60 overflow-y-auto">
                  <div class="px-3.5 py-2 bg-amber-500/10 border-b border-outline-variant/60 flex items-center gap-2 text-[11px] text-amber-800 dark:text-amber-300">
                    <span class="material-symbols-outlined text-xs text-amber-500">info</span>
                    <span>Use the <strong>full office name</strong> &mdash; avoid abbreviations.</span>
                  </div>

                  <div 
                    v-for="unit in filteredOffices" 
                    :key="unit.unit_id" 
                    @click="selectOffice(unit)"
                    class="dropdown-item px-4 py-3 cursor-pointer transition-colors flex items-center justify-between"
                  >
                    <span>{{ unit.unit_name }}</span>
                    <span v-if="unit.office_acronym" class="text-xs text-purple-600 dark:text-purple-400 font-mono bg-purple-500/10 px-2 py-0.5 rounded">{{ unit.office_acronym }}</span>
                  </div>
                  
                  <div v-if="filteredOffices.length === 0" class="px-4 py-3 text-on-surface-variant/70 italic text-sm">
                    No matching colleges/offices in {{ form.campus_location }}.
                  </div>

                  <div 
                    @click="selectAddNew"
                    class="dropdown-item px-4 py-3 font-bold text-purple-600 dark:text-purple-400 cursor-pointer border-t border-outline-variant transition-colors flex items-center gap-2"
                  >
                    <span class="material-symbols-outlined text-sm">add_circle</span>
                    Not in the list? Add new office
                  </div>
                </div>
                
                <!-- Add New Office Box with Smart Office Checker -->
                <div v-if="isAddingNew" class="mt-2 p-3.5 bg-purple-50/40 dark:bg-purple-950/20 border border-purple-200 dark:border-purple-800/40 rounded-xl space-y-2.5 animate-fade-in">
                  <div class="flex items-center justify-between">
                    <label class="text-[11px] uppercase tracking-wider font-label font-bold text-purple-700 dark:text-purple-300 flex items-center gap-1.5">
                      <span class="material-symbols-outlined text-sm">domain_add</span>
                      New College / Office Name
                    </label>
                    <button 
                      type="button" 
                      @click="cancelAddNew" 
                      class="text-xs text-on-surface-variant hover:text-red-500 transition-colors font-medium flex items-center gap-1"
                    >
                      <span class="material-symbols-outlined text-xs">close</span>
                      Cancel / Back to list
                    </button>
                  </div>

                  <input 
                    v-model="newOfficeName" 
                    placeholder="Enter full official office name (e.g. College of Veterinary Medicine)" 
                    class="w-full bg-surface-container border rounded-lg px-4 py-3 text-on-surface outline-none transition-all placeholder:text-on-surface-variant/60"
                    :class="{
                      'border-amber-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500': officeCheckResult.hasMatch,
                      'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500': !officeCheckResult.hasMatch && officeCheckResult.isAbbreviation,
                      'border-emerald-500 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500': officeCheckResult.isValid,
                      'border-purple-500 focus:border-purple-500 focus:ring-1 focus:ring-purple-500': officeCheckResult.isEmpty
                    }"
                    required 
                  />

                  <!-- Smart Validation: Matched Existing Office -->
                  <div v-if="officeCheckResult.hasMatch" class="p-3 rounded-lg bg-amber-500/15 border border-amber-500/40 text-xs space-y-2 animate-fade-in">
                    <div class="flex items-start gap-2">
                      <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-base shrink-0 mt-0.5">help</span>
                      <div class="flex-1">
                        <div class="font-bold text-amber-900 dark:text-amber-200">
                          Office Already Exists: Did you mean "{{ officeCheckResult.matchedOffice.unit_name }}"?
                        </div>
                        <p class="text-on-surface-variant mt-0.5 leading-relaxed">
                          This office is already in the official university directory. Selecting it ensures records are unified and prevents duplicate entries.
                        </p>
                      </div>
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                      <button 
                        type="button" 
                        @click="useMatchedOffice(officeCheckResult.matchedOffice)"
                        class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg shadow-sm transition-all flex items-center gap-1.5 text-xs active:scale-95"
                      >
                        <span class="material-symbols-outlined text-xs">check_circle</span>
                        Select "{{ officeCheckResult.matchedOffice.unit_name }}"
                      </button>
                    </div>
                  </div>

                  <!-- Smart Validation: Abbreviation Pattern Warning -->
                  <div v-else-if="officeCheckResult.isAbbreviation" class="p-3 rounded-lg bg-red-50 dark:bg-red-950/40 border border-red-300 dark:border-red-800/50 text-xs flex items-start gap-2 animate-fade-in shadow-sm">
                    <span class="material-symbols-outlined text-red-700 dark:text-red-400 text-base shrink-0 mt-0.5">error</span>
                    <div>
                      <div class="font-bold text-red-900 dark:text-red-300">Please Do Not Use Abbreviations</div>
                      <p class="text-red-950 dark:text-on-surface-variant mt-0.5 leading-relaxed">
                        "{{ newOfficeName }}" looks like an abbreviation or acronym. Please type the full, formal name (e.g. <em>College of Agriculture</em> instead of <em>CA</em>).
                      </p>
                    </div>
                  </div>

                  <!-- Smart Validation: Verified Unique Office Name -->
                  <div v-else-if="officeCheckResult.isValid" class="p-2.5 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-xs flex items-center gap-2 text-emerald-700 dark:text-emerald-300 animate-fade-in">
                    <span class="material-symbols-outlined text-emerald-500 text-sm">verified</span>
                    <span><strong>Unique Office Name:</strong> Ready to register.</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Department (Optional) -->
            <div class="flex flex-col gap-2 md:col-span-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-on-surface-variant flex items-center gap-2">
                <span>Department</span>
                <span class="opacity-70 font-normal lowercase">(optional - for academic departments within colleges)</span>
              </label>
              <input v-model="form.department" placeholder="e.g. Department of Information Technology" class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-3 text-on-surface focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all placeholder:text-on-surface-variant/60" />
            </div>

            <!-- Student ID & Year Level (For Proponents) -->
            <template v-if="form.user_role === 'Non-TWG'">
              <div class="flex flex-col gap-2">
                <label class="text-xs uppercase tracking-widest font-label font-bold text-on-surface-variant flex items-center gap-2">
                  <span>Student ID / ID Number</span>
                  <span class="opacity-70 font-normal lowercase">(optional)</span>
                </label>
                <input v-model="form.student_id" placeholder="e.g. 2022-12345" class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-3 text-on-surface focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all placeholder:text-on-surface-variant/60" />
              </div>
              <div class="flex flex-col gap-2">
                <label class="text-xs uppercase tracking-widest font-label font-bold text-on-surface-variant flex items-center gap-2">
                  <span>Year Level</span>
                  <span class="opacity-70 font-normal lowercase">(optional)</span>
                </label>
                <div class="relative">
                  <select v-model="form.year_level" class="w-full bg-surface-container border border-outline-variant rounded-lg pl-4 pr-12 py-3 text-on-surface focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all appearance-none cursor-pointer">
                    <option value="">None / Faculty / Staff</option>
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year">3rd Year</option>
                    <option value="4th Year">4th Year</option>
                    <option value="Graduate">Graduate Student</option>
                  </select>
                  <span class="material-symbols-outlined absolute right-5 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">expand_more</span>
                </div>
              </div>
            </template>

            <div class="flex flex-col gap-2 md:col-span-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-on-surface-variant flex items-center justify-between">
                <span>{{ form.user_role === 'TWG' ? 'Institutional Email' : 'Email Address' }} <span class="text-red-500">*</span></span>
                <span v-if="form.user_role === 'TWG'" class="text-[11px] text-purple-600 dark:text-purple-400 font-semibold tracking-normal lowercase">(@bsu.edu.ph required)</span>
              </label>
              <input v-model="form.email" type="email" :placeholder="form.user_role === 'TWG' ? 'name@bsu.edu.ph' : 'name@example.com'" class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-3 text-on-surface focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all placeholder:text-on-surface-variant/60" required />
              <p v-if="form.user_role === 'TWG'" class="text-xs text-purple-600 dark:text-purple-400 font-medium flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">info</span>
                TWG members must use their Benguet State University email (@bsu.edu.ph)
              </p>
            </div>
            <div class="flex flex-col gap-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-on-surface-variant">Password <span class="text-red-500">*</span></label>
              <div class="relative">
                <input v-model="form.password" :type="showPass ? 'text' : 'password'" placeholder="••••••••" class="w-full bg-surface-container border border-outline-variant rounded-lg pl-4 pr-11 py-3 text-on-surface focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all placeholder:text-on-surface-variant/60" required />
                <button type="button" @click="showPass = !showPass" class="absolute right-3 top-3 text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">{{ showPass ? 'visibility_off' : 'visibility' }}</span></button>
              </div>
              <ul class="text-xs mt-2 space-y-1 font-medium transition-colors">
                <li class="flex items-center gap-1 transition-colors" :class="form.password.length >= 8 ? 'text-emerald-500 dark:text-emerald-400' : 'text-on-surface-variant/60'">
                  <span class="material-symbols-outlined text-[14px]">{{ form.password.length >= 8 ? 'check_circle' : 'cancel' }}</span>
                  At least 8 characters
                </li>
                <li class="flex items-center gap-1 transition-colors" :class="/[A-Z]/.test(form.password || '') ? 'text-emerald-500 dark:text-emerald-400' : 'text-on-surface-variant/60'">
                  <span class="material-symbols-outlined text-[14px]">{{ /[A-Z]/.test(form.password || '') ? 'check_circle' : 'cancel' }}</span>
                  One uppercase letter
                </li>
                <li class="flex items-center gap-1 transition-colors" :class="/[a-z]/.test(form.password || '') ? 'text-emerald-500 dark:text-emerald-400' : 'text-on-surface-variant/60'">
                  <span class="material-symbols-outlined text-[14px]">{{ /[a-z]/.test(form.password || '') ? 'check_circle' : 'cancel' }}</span>
                  One lowercase letter
                </li>
                <li class="flex items-center gap-1 transition-colors" :class="/[0-9]/.test(form.password || '') ? 'text-emerald-500 dark:text-emerald-400' : 'text-on-surface-variant/60'">
                  <span class="material-symbols-outlined text-[14px]">{{ /[0-9]/.test(form.password || '') ? 'check_circle' : 'cancel' }}</span>
                  One number
                </li>
                <li class="flex items-center gap-1 transition-colors" :class="/[^A-Za-z0-9]/.test(form.password || '') ? 'text-emerald-500 dark:text-emerald-400' : 'text-on-surface-variant/60'">
                  <span class="material-symbols-outlined text-[14px]">{{ /[^A-Za-z0-9]/.test(form.password || '') ? 'check_circle' : 'cancel' }}</span>
                  One special character
                </li>
              </ul>
            </div>
            <div class="flex flex-col gap-2">
              <label class="text-xs uppercase tracking-widest font-label font-bold text-on-surface-variant">Confirm Password <span class="text-red-500">*</span></label>
              <div class="relative">
                <input v-model="form.confirm_password" :type="showConfirmPass ? 'text' : 'password'" placeholder="••••••••" class="w-full bg-surface-container border border-outline-variant rounded-lg pl-4 pr-11 py-3 text-on-surface focus:ring-1 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all placeholder:text-on-surface-variant/60" required />
                <button type="button" @click="showConfirmPass = !showConfirmPass" class="absolute right-3 top-3 text-on-surface-variant hover:text-on-surface transition-colors"><span class="material-symbols-outlined">{{ showConfirmPass ? 'visibility_off' : 'visibility' }}</span></button>
              </div>
            </div>
          </div>

          <!-- Turnstile Widget -->
          <TurnstileWidget ref="turnstileRef" @verify="onTurnstileVerify" />

          <!-- Privacy Policy Checkbox -->
          <div class="flex items-center gap-3 pt-2">
            <input id="privacy" v-model="form.privacyAccepted" type="checkbox" class="w-5 h-5 rounded text-purple-600 focus:ring-purple-500 flex-shrink-0 cursor-pointer" required />
            <label for="privacy" class="text-sm text-on-surface-variant font-medium cursor-pointer flex items-center gap-1.5 flex-wrap">
              <span>I agree to the</span>
              <button type="button" @click.stop.prevent="showPrivacyModal = true" class="text-purple-600 dark:text-purple-400 hover:underline font-bold">Privacy Policy</button>
            </label>
          </div>

          <div class="flex flex-col gap-4 pt-4">
            <button :disabled="loading" class="w-full py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white rounded-full font-bold uppercase shadow-[0_0_20px_rgba(168,85,247,0.4)] hover:shadow-[0_0_30px_rgba(168,85,247,0.7)] hover:-translate-y-1 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 cursor-pointer disabled:opacity-50" type="submit">
              {{ loading ? 'Processing...' : 'Register' }}
            </button>
            <button type="button" @click="router.back()" class="w-full border border-outline-variant text-on-surface py-4 rounded-full font-bold uppercase hover:bg-surface-variant transition-all cursor-pointer">
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Privacy Policy Modal -->
    <PrivacyPolicyModal 
      v-if="showPrivacyModal" 
      :show-accept="true" 
      @close="showPrivacyModal = false" 
      @accept="acceptPrivacy" 
    />
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import TurnstileWidget from '../components/TurnstileWidget.vue';
import PrivacyPolicyModal from '../components/PrivacyPolicyModal.vue';
import { checkOfficeName, generateAcronyms, stringSimilarity, isAbbreviationPattern } from '../utils/officeChecker';

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
const showOfficeNotice = ref(false);

// Combobox logic
const dropdownRef = ref(null);
const isDropdownOpen = ref(false);
const officeSearchQuery = ref('');

const officesByCampus = computed(() => {
  if (!form.campus_location) return officeUnits.value;
  return officeUnits.value.filter(u => !u.location || u.location === form.campus_location);
});

const filteredOffices = computed(() => {
  const base = officesByCampus.value;
  if (!officeSearchQuery.value) return base;
  
  const selectedOffice = base.find(u => u.unit_id === form.office_unit_id);
  if (selectedOffice && officeSearchQuery.value === selectedOffice.unit_name) {
    return base;
  }

  const q = officeSearchQuery.value.trim().toLowerCase();
  const cleanQ = q.replace(/[^a-z0-9]/g, '');

  return base.filter(u => {
    const uName = u.unit_name.toLowerCase();
    const uAcronym = (u.office_acronym || '').toLowerCase().replace(/[^a-z0-9]/g, '');
    
    // Direct substring in name or acronym
    if (uName.includes(q)) return true;
    if (uAcronym && (uAcronym.includes(cleanQ) || cleanQ.includes(uAcronym))) return true;

    // Generated acronyms (e.g. CVM for College of Veterinary Medicine)
    const generated = generateAcronyms(u.unit_name);
    if (generated.some(ac => ac.toLowerCase() === cleanQ)) return true;

    // Small typo similarity
    if (q.length >= 4 && stringSimilarity(u.unit_name, q) > 0.6) return true;

    return false;
  });
});

const exactMatchExists = computed(() => {
  if (!officeSearchQuery.value) return false;
  return officesByCampus.value.some(u => u.unit_name.toLowerCase() === officeSearchQuery.value.trim().toLowerCase());
});

// Smart validation for custom office name
const officeCheckResult = computed(() => {
  return checkOfficeName(newOfficeName.value, officeUnits.value, form.campus_location);
});

const handleOfficeFocus = () => {
  isDropdownOpen.value = true;
  showOfficeNotice.value = true;
};

const handleCampusChange = () => {
  form.office_unit_id = '';
  officeSearchQuery.value = '';
  isAddingNew.value = false;
  newOfficeName.value = '';
};

const handleSearchInput = () => {
  isAddingNew.value = false;
  form.office_unit_id = ''; 
  isDropdownOpen.value = true;
};

const selectOffice = (unit) => {
  form.office_unit_id = unit.unit_id;
  officeSearchQuery.value = unit.unit_name;
  isAddingNew.value = false;
  newOfficeName.value = '';
  isDropdownOpen.value = false;
};

const useMatchedOffice = (unit) => {
  selectOffice(unit);
};

const selectAddNew = () => {
  form.office_unit_id = 'add_new';
  isAddingNew.value = true;
  if (exactMatchExists.value) {
    newOfficeName.value = '';
  } else {
    newOfficeName.value = officeSearchQuery.value;
  }
  officeSearchQuery.value = '';
  isDropdownOpen.value = false;
};

const cancelAddNew = () => {
  isAddingNew.value = false;
  newOfficeName.value = '';
  form.office_unit_id = '';
  officeSearchQuery.value = '';
};

const handleClickOutside = (e) => {
  if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
    isDropdownOpen.value = false;
  }
};

const form = reactive({
  first_name: '', middle_name: '', last_name: '',
  user_role: 'Non-TWG', 
  campus_location: 'La Trinidad Campus',
  office_unit_id: '',
  department: '',
  student_id: '',
  year_level: '',
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
  if (form.user_role === 'TWG' && !form.email.trim().toLowerCase().endsWith('@bsu.edu.ph')) {
    return error.value = 'TWG accounts require a valid institutional email (@bsu.edu.ph).';
  }
  if (form.password.length < 8) {
    return error.value = 'Password must be at least 8 characters long.';
  }
  if (!/[A-Z]/.test(form.password)) {
    return error.value = 'Password must contain at least 1 uppercase letter.';
  }
  if (!/[a-z]/.test(form.password)) {
    return error.value = 'Password must contain at least 1 lowercase letter.';
  }
  if (!/[0-9]/.test(form.password)) {
    return error.value = 'Password must contain at least 1 number.';
  }
  if (!/[^A-Za-z0-9]/.test(form.password)) {
    return error.value = 'Password must contain at least 1 special character.';
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

    if (isAddingNew.value) {
      if (!newOfficeName.value || !newOfficeName.value.trim()) {
        loading.value = false;
        return error.value = 'Please enter the full name for the new college or office.';
      }
      const check = officeCheckResult.value;
      if (check.hasMatch) {
        loading.value = false;
        return error.value = `Office already exists: Please select "${check.matchedOffice.unit_name}" from the list instead of adding a duplicate.`;
      }
      if (check.isAbbreviation) {
        loading.value = false;
        return error.value = `Abbreviations are not allowed. Please enter the full official name without acronyms (e.g., "College of Agriculture" instead of "${newOfficeName.value.trim()}").`;
      }

      const res = await api.post('add_office', { 
        unit_name: newOfficeName.value.trim(),
        location: form.campus_location
      });
      departmentId = res.data.new_id;
    } else if (!departmentId && officeSearchQuery.value && officeSearchQuery.value.trim()) {
      const q = officeSearchQuery.value.trim();
      const check = checkOfficeName(q, officeUnits.value, form.campus_location);

      if (check.isAbbreviation) {
        loading.value = false;
        if (check.matchedOffice) {
          return error.value = `"${q}" is an abbreviation. Abbreviations are not allowed. Please select "${check.matchedOffice.unit_name}" from the list.`;
        }
        return error.value = `"${q}" is an abbreviation. Abbreviations are not allowed. Please select the full official College / Office name from the list.`;
      } else if (check.matchedOffice) {
        departmentId = check.matchedOffice.unit_id;
        officeSearchQuery.value = check.matchedOffice.unit_name;
      } else {
        loading.value = false;
        return error.value = `"${q}" was not recognized. Please select an existing office from the list or click "Not in the list? Add new office".`;
      }
    }

    if (form.department && isAbbreviationPattern(form.department.trim())) {
      loading.value = false;
      return error.value = `Department "${form.department.trim()}" looks like an abbreviation. Please type the full academic department name (e.g. "Department of Information Technology").`;
    }

    const payload = {
      fullname: `${form.first_name} ${form.middle_name} ${form.last_name}`.replace(/\s+/g, ' ').trim(),
      first_name: form.first_name,
      middle_name: form.middle_name,
      last_name: form.last_name,
      department: departmentId || null, 
      department_name: form.department ? form.department.trim() : null,
      university_id: form.student_id || null,
      year_level: form.year_level || null,
      email: form.email.trim(),
      password: form.password,
      confirm_password: form.confirm_password,
      user_role: form.user_role,
      turnstile_token: turnstileToken.value
    };

    await api.post('register', payload);
    
    router.push('/login?registered=true');

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
/* ==========================================================================
   Base / Light Mode (Default)
   ========================================================================== */
.register-page {
  background-color: var(--color-background, #f8fafc);
  color: var(--color-on-background, #0f172a);
  transition: background-color 0.3s ease, color 0.3s ease;
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
  width: 26rem;
  height: 26rem;
  background-color: rgba(168, 85, 247, 0.12);
}

.auth-bg-blob-2 {
  bottom: -6rem;
  left: -6rem;
  width: 26rem;
  height: 26rem;
  background-color: rgba(59, 130, 246, 0.1);
}

.auth-badge {
  background-color: rgba(147, 51, 234, 0.08);
  border-color: rgba(147, 51, 234, 0.2);
  color: #7e22ce;
  transition: all 0.3s ease;
}

.auth-title {
  color: #0f172a;
  transition: color 0.3s ease;
}

.auth-card {
  background-color: #ffffff;
  border-color: #e2e8f0;
  box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.07), 0 0 0 1px rgba(0, 0, 0, 0.02);
  transition: all 0.3s ease;
}

.dropdown-list {
  background-color: #ffffff;
  border-color: #e2e8f0;
  color: #0f172a;
}

.dropdown-item:hover {
  background-color: #f1f5f9;
}

/* ==========================================================================
   Dark Mode Overrides - Charcoal Theme
   ========================================================================== */
:global(.dark) .register-page,
.dark .register-page {
  background-color: #121316 !important;
  color: #f3f4f6 !important;
}

:global(.dark) .auth-bg-blob-1,
.dark .auth-bg-blob-1 {
  background-color: rgba(99, 102, 241, 0.12) !important;
}

:global(.dark) .auth-bg-blob-2,
.dark .auth-bg-blob-2 {
  background-color: rgba(168, 85, 247, 0.12) !important;
}

:global(.dark) .auth-badge,
.dark .auth-badge {
  background-color: #242730 !important;
  border-color: #383d49 !important;
  color: #d1d5db !important;
}

:global(.dark) .auth-title,
.dark .auth-title {
  color: #ffffff !important;
}

:global(.dark) .auth-card,
.dark .auth-card {
  background-color: #1e2026 !important;
  border-color: #2f333d !important;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(255, 255, 255, 0.07) !important;
}

:global(.dark) .register-page input,
.dark .register-page input,
:global(.dark) .register-page select,
.dark .register-page select {
  background-color: #16181d !important;
  border-color: #2f333d !important;
  color: #f3f4f6 !important;
}

:global(.dark) .register-page input:focus,
.dark .register-page input:focus,
:global(.dark) .register-page select:focus,
.dark .register-page select:focus {
  background-color: #1a1c22 !important;
  border-color: #a855f7 !important;
}

:global(.dark) .register-page select option,
.dark .register-page select option {
  background-color: #1e2026 !important;
  color: #f3f4f6 !important;
}

:global(.dark) .register-page input::placeholder,
.dark .register-page input::placeholder {
  color: #6b7280 !important;
}

:global(.dark) .register-page label,
.dark .register-page label {
  color: #d1d5db !important;
}

:global(.dark) .dropdown-list,
.dark .dropdown-list {
  background-color: #1e2026 !important;
  border-color: #2f333d !important;
  color: #f3f4f6 !important;
}

:global(.dark) .dropdown-item:hover,
.dark .dropdown-item:hover {
  background-color: #282b34 !important;
}

:global(.dark) .register-page .border-outline-variant,
.dark .register-page .border-outline-variant {
  border-color: #2f333d !important;
}

:global(.dark) .register-page .text-on-surface-variant,
.dark .register-page .text-on-surface-variant {
  color: #9ca3af !important;
}

:global(.dark) .register-page .hover\:bg-surface-variant:hover,
.dark .register-page .hover\:bg-surface-variant:hover {
  background-color: #282b34 !important;
}
</style>
