<template>
  <div class="settings-container">
    <!-- Header with Profile Overview (Supports Light & Dark mode) -->
    <div class="settings-header">
      <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 relative z-10">
        <!-- Avatar Preview / Upload Trigger -->
        <div class="relative group">
          <div class="w-24 h-24 rounded-2xl overflow-hidden avatar-container flex items-center justify-center text-3xl font-extrabold uppercase select-none shadow-md">
            <img v-if="avatarPreview || user.profile_picture" :src="avatarPreview || getAvatarUrl(user.profile_picture)" alt="Avatar" class="w-full h-full object-cover" />
            <span v-else>{{ userInitials }}</span>
          </div>
          <label class="absolute -bottom-2 -right-2 bg-purple-600 hover:bg-purple-500 text-white p-2 rounded-xl cursor-pointer shadow-lg transition-transform group-hover:scale-110 flex items-center justify-center border border-white dark:border-purple-400/30" title="Change profile picture">
            <span class="material-symbols-outlined text-sm">photo_camera</span>
            <input type="file" accept="image/*" class="hidden" @change="handleAvatarSelected" />
          </label>
          <button 
            v-if="user.profile_picture" 
            type="button" 
            @click="handleRemoveAvatar" 
            class="absolute -top-2 -right-2 bg-red-600 hover:bg-red-500 text-white w-6 h-6 rounded-full cursor-pointer shadow-lg transition-transform group-hover:scale-110 flex items-center justify-center border border-white dark:border-red-400/40" 
            title="Remove profile picture"
          >
            <span class="material-symbols-outlined text-[13px]">close</span>
          </button>
        </div>

        <div class="flex-1 text-center sm:text-left">
          <div class="role-badge mb-2">
            <span>{{ user.user_role || (user.role === 'non-twg' ? 'Proponent' : 'User') }}</span>
            <span v-if="user.office_acronym" class="font-mono">({{ user.office_acronym }})</span>
          </div>
          <h1 class="settings-title">
            {{ user.full_name || user.username || 'Account Settings' }}
          </h1>
          <p class="settings-subtitle flex flex-wrap items-center justify-center sm:justify-start gap-3 mt-1.5">
            <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-sm text-purple-600 dark:text-purple-400">business</span> {{ user.office_name || 'No Office Assigned' }}</span>
            <span class="opacity-40">•</span>
            <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-sm text-purple-600 dark:text-purple-400">location_on</span> {{ user.location || 'La Trinidad Campus' }}</span>
          </p>
        </div>
      </div>
    </div>

    <div class="settings-content mt-6">
      
      <!-- 1. PERSONAL INFORMATION -->
      <div class="settings-card">
        <div class="card-header">
          <div class="card-icon-pill icon-purple">
            <span class="material-symbols-outlined text-lg">badge</span>
          </div>
          <h2 class="card-title">Personal Information</h2>
        </div>
        
        <form @submit.prevent="savePersonalInfo" class="form-group">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="input-wrapper">
              <label class="input-label">First Name <span class="text-red-500">*</span></label>
              <input type="text" v-model="personalForm.first_name" class="custom-input" required placeholder="First name" />
            </div>
            <div class="input-wrapper">
              <label class="input-label">Middle Name</label>
              <input type="text" v-model="personalForm.middle_name" class="custom-input" placeholder="Middle name (optional)" />
            </div>
            <div class="input-wrapper">
              <label class="input-label">Last Name <span class="text-red-500">*</span></label>
              <input type="text" v-model="personalForm.last_name" class="custom-input" required placeholder="Last name" />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="input-wrapper">
              <label class="input-label">Sex</label>
              <select v-model="personalForm.sex" class="custom-input">
                <option value="">Prefer not to say</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="input-wrapper">
              <label class="input-label">Current Full Name</label>
              <div class="readonly-field">
                <span>{{ computedFullName }}</span>
                <span class="material-symbols-outlined text-sm opacity-60">lock</span>
              </div>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn-primary" :disabled="isSavingPersonal">
              <span v-if="isSavingPersonal" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isSavingPersonal ? 'Saving...' : 'Save Personal Information' }}
            </button>
          </div>
          <div v-if="personalSuccess" class="success-msg">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <span>{{ personalSuccess }}</span>
          </div>
          <div v-if="personalError" class="error-msg">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ personalError }}</span>
          </div>
        </form>
      </div>

      <!-- 2. DESIGNATION & AFFILIATION -->
      <div class="settings-card">
        <div class="card-header">
          <div class="card-icon-pill icon-blue">
            <span class="material-symbols-outlined text-lg">domain</span>
          </div>
          <h2 class="card-title">Designation & Affiliation</h2>
        </div>
        
        <form @submit.prevent="saveDesignation" class="form-group min-w-0">
          <!-- Guideline Notice Banner (Full-Width Responsive Alert) -->
          <transition 
            enter-active-class="transition duration-200 ease-out" 
            enter-from-class="opacity-0 -translate-y-1" 
            enter-to-class="opacity-100 translate-y-0" 
            leave-active-class="transition duration-150 ease-in" 
            leave-from-class="opacity-100 translate-y-0" 
            leave-to-class="opacity-0 -translate-y-1"
          >
            <div v-if="showOfficeNotice" class="p-3.5 bg-amber-500/10 border border-amber-500/30 rounded-xl flex items-start gap-2.5 text-xs shadow-sm min-w-0">
              <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-base shrink-0 mt-0.5">campaign</span>
              <div class="flex-1 min-w-0">
                <div class="font-bold text-amber-800 dark:text-amber-300 flex items-center justify-between gap-2">
                  <span>Office Selection Guideline</span>
                  <button type="button" @click="showOfficeNotice = false" class="text-on-surface-variant hover:text-on-surface text-xs font-bold p-1 cursor-pointer">✕</button>
                </div>
                <p class="text-on-surface-variant mt-1 leading-relaxed break-words">
                  Please select or enter the <strong>full official name</strong> of your College or Office (e.g., <em>College of Agriculture</em>, <em>Accounting Office</em>).
                  <strong>Never use abbreviations or acronyms</strong> (such as <em>CA</em>, <em>CTE</em>, <em>CIS</em>, or <em>Dept</em>).
                </p>
              </div>
            </div>
          </transition>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
            <div class="input-wrapper min-w-0">
              <label class="input-label">Campus Location</label>
              <select 
                v-model="designationForm.campus_location" 
                @change="handleCampusChange" 
                class="custom-input cursor-pointer"
              >
                <option value="La Trinidad Campus">La Trinidad Campus</option>
                <option value="Buguias Campus">Buguias Campus</option>
                <option value="Bokod Campus">Bokod Campus</option>
              </select>
            </div>
            <div class="input-wrapper min-w-0" ref="officeDropdownRef">
              <div class="flex items-center justify-between gap-2 flex-wrap">
                <label class="input-label !mb-0">College / Office</label>
                <button 
                  type="button" 
                  @click="showOfficeNotice = !showOfficeNotice" 
                  class="text-xs text-purple-600 dark:text-purple-400 hover:underline flex items-center gap-1 font-medium cursor-pointer shrink-0"
                >
                  <span class="material-symbols-outlined text-sm">info</span>
                  Guideline
                </button>
              </div>

              <!-- Searchable Custom Combobox (Zero horizontal overflow) -->
              <div class="relative w-full min-w-0">
                <input 
                  type="text"
                  v-model="officeSearchQuery" 
                  @focus="handleOfficeFocus"
                  @input="handleOfficeSearchInput"
                  placeholder="Search or Select College / Office"
                  class="custom-input cursor-pointer pr-10"
                  :required="!designationForm.office_id && designationForm.office_id !== 'add_new'"
                />
                <span 
                  class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none transition-transform duration-200"
                  :class="{ 'rotate-180': isOfficeDropdownOpen }"
                >
                  expand_more
                </span>

                <!-- Dropdown List: Strictly Width-Bound to parent container -->
                <div 
                  v-if="isOfficeDropdownOpen" 
                  class="office-dropdown-menu absolute z-50 left-0 right-0 w-full mt-1.5 border rounded-xl shadow-2xl max-h-60 overflow-y-auto"
                >
                  <div class="p-2.5 bg-amber-500/10 border-b border-outline-variant/40 flex items-center gap-2 text-[11px] text-amber-800 dark:text-amber-300">
                    <span class="material-symbols-outlined text-xs text-amber-500">info</span>
                    <span>Use the <strong>full office name</strong> (no abbreviations).</span>
                  </div>

                  <div 
                    v-for="unit in filteredOffices" 
                    :key="unit.unit_id" 
                    @click="selectOffice(unit)"
                    class="office-dropdown-item px-3.5 py-2.5 cursor-pointer flex items-center justify-between text-xs transition-colors"
                    :class="{ 'is-selected': String(designationForm.office_id) === String(unit.unit_id) }"
                  >
                    <span class="font-medium truncate mr-2">{{ unit.unit_name }}</span>
                    <span v-if="unit.office_acronym" class="text-[10px] text-purple-600 dark:text-purple-400 font-mono bg-purple-500/15 px-1.5 py-0.5 rounded font-bold shrink-0">
                      {{ unit.office_acronym }}
                    </span>
                  </div>

                  <div v-if="filteredOffices.length === 0" class="px-3.5 py-3 text-slate-500 dark:text-slate-400 italic text-xs">
                    No matching colleges/offices in {{ designationForm.campus_location }}.
                  </div>

                  <div 
                    @click="selectAddNew"
                    class="office-dropdown-item add-new-item px-3.5 py-2.5 font-bold text-purple-600 dark:text-purple-400 cursor-pointer border-t border-outline-variant/40 transition-colors flex items-center gap-2 text-xs"
                  >
                    <span class="material-symbols-outlined text-sm">add_circle</span>
                    <span>Not in the list? Add new office</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Add New Office Box with Smart Office Checker -->
          <div v-if="designationForm.office_id === 'add_new'" class="p-4 bg-purple-50/50 dark:bg-purple-950/20 border border-purple-200 dark:border-purple-800/40 rounded-xl space-y-3 animate-fade-in min-w-0 overflow-hidden">
            <div class="flex items-center justify-between gap-2 flex-wrap">
              <label class="input-label text-purple-700 dark:text-purple-300 !mb-0 flex items-center gap-1.5 font-bold">
                <span class="material-symbols-outlined text-sm">domain_add</span>
                New College / Office Name
              </label>
              <button 
                type="button" 
                @click="cancelAddNewOffice" 
                class="text-xs text-on-surface-variant hover:text-red-500 transition-colors font-medium flex items-center gap-1 cursor-pointer shrink-0"
              >
                <span class="material-symbols-outlined text-xs">close</span>
                Cancel / Back to list
              </button>
            </div>

            <input 
              type="text" 
              v-model="designationForm.new_office_name" 
              class="custom-input"
              :class="{
                '!border-amber-500 ring-1 ring-amber-500/30': newOfficeCheckResult.hasMatch,
                '!border-red-500 ring-1 ring-red-500/30': !newOfficeCheckResult.hasMatch && newOfficeCheckResult.isAbbreviation,
                '!border-emerald-500 ring-1 ring-emerald-500/30': newOfficeCheckResult.isValid,
                '!border-purple-500': newOfficeCheckResult.isEmpty
              }" 
              placeholder="Enter full new college or office name (e.g. College of Veterinary Medicine)" 
              required
            />

            <!-- Smart Validation Feedback: Match Found -->
            <div v-if="newOfficeCheckResult.hasMatch" class="p-3.5 rounded-lg bg-amber-500/15 border border-amber-500/40 text-xs space-y-2.5 animate-fade-in min-w-0">
              <div class="flex items-start gap-2.5">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-base shrink-0 mt-0.5">help</span>
                <div class="flex-1 min-w-0">
                  <div class="font-bold text-amber-900 dark:text-amber-200 break-words leading-snug">
                    Office Already Exists: Did you mean "{{ newOfficeCheckResult.matchedOffice.unit_name }}"?
                  </div>
                  <p class="text-on-surface-variant mt-1 leading-relaxed break-words">
                    This office already exists in the system directory. Please select it from the list instead of creating a duplicate.
                  </p>
                </div>
              </div>
              <div class="flex items-center gap-2 pt-1">
                <button 
                  type="button" 
                  @click="useMatchedOfficeInSettings(newOfficeCheckResult.matchedOffice)"
                  class="w-full sm:w-auto px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg shadow-sm transition-all flex items-center justify-center gap-1.5 text-xs active:scale-95 cursor-pointer max-w-full text-left"
                >
                  <span class="material-symbols-outlined text-xs shrink-0">check_circle</span>
                  <span class="break-words">Select "{{ newOfficeCheckResult.matchedOffice.unit_name }}"</span>
                </button>
              </div>
            </div>

            <!-- Smart Validation Feedback: Abbreviation Warning -->
            <div v-else-if="newOfficeCheckResult.isAbbreviation" class="p-3.5 rounded-lg bg-red-500/10 border border-red-500/30 text-xs flex items-start gap-2.5 animate-fade-in min-w-0">
              <span class="material-symbols-outlined text-red-500 text-base shrink-0 mt-0.5">error</span>
              <div class="flex-1 min-w-0">
                <div class="font-bold text-red-700 dark:text-red-400">Please Do Not Use Abbreviations</div>
                <p class="text-on-surface-variant mt-1 leading-relaxed break-words">
                  "{{ designationForm.new_office_name }}" looks like an abbreviation or acronym. Please type the full, formal name (e.g. <em>College of Agriculture</em> instead of <em>CA</em>).
                </p>
              </div>
            </div>

            <!-- Smart Validation Feedback: Valid Unique Name -->
            <div v-else-if="newOfficeCheckResult.isValid" class="p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-xs flex items-center gap-2 text-emerald-700 dark:text-emerald-300 animate-fade-in min-w-0">
              <span class="material-symbols-outlined text-emerald-500 text-base shrink-0">verified</span>
              <span class="break-words"><strong>Unique Office Name:</strong> Ready to register.</span>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="input-wrapper">
              <label class="input-label">Department</label>
              <input type="text" v-model="designationForm.department" class="custom-input" placeholder="e.g. Department of Information Technology (optional)" />
              <span class="field-helper">Academic unit or subdivision within your office/college</span>
            </div>
            <div class="input-wrapper">
              <label class="input-label">Position / Title</label>
              <input type="text" v-model="designationForm.position" class="custom-input" placeholder="e.g. Instructor, Associate Professor, Admin Staff (optional)" />
              <span class="field-helper">Your job title / designation in the university</span>
            </div>
          </div>

          <!-- Student ID / Employee ID and Year Level (Available for all members) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="input-wrapper">
              <label class="input-label">Student ID / Employee ID</label>
              <input type="text" v-model="designationForm.student_id" class="custom-input" placeholder="e.g. 2022-12345 (optional)" />
              <span class="field-helper">University identification number</span>
            </div>
            <div class="input-wrapper">
              <label class="input-label">Year Level (For Students)</label>
              <select v-model="designationForm.year_level" class="custom-input">
                <option value="">None / Faculty / Staff</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
                <option value="Graduate">Graduate Student</option>
              </select>
              <span class="field-helper">Academic year standing if student</span>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn-primary" :disabled="isSavingDesignation">
              <span v-if="isSavingDesignation" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isSavingDesignation ? 'Saving...' : 'Save Designation' }}
            </button>
          </div>
          <div v-if="designationSuccess" class="success-msg">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <span>{{ designationSuccess }}</span>
          </div>
          <div v-if="designationError" class="error-msg">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ designationError }}</span>
          </div>
        </form>
      </div>

      <!-- 3. SECURITY & PASSWORD -->
      <div class="settings-card">
        <div class="card-header">
          <div class="card-icon-pill icon-pink">
            <span class="material-symbols-outlined text-lg">lock</span>
          </div>
          <h2 class="card-title">Security & Password</h2>
        </div>
        
        <!-- Email Form -->
        <form @submit.prevent="updateEmail" class="form-group mb-8 pb-8 form-divider">
          <div class="input-wrapper mb-2">
            <label class="input-label">Current Email</label>
            <div class="readonly-field">
              <span>{{ user.email || 'Loading...' }}</span>
              <span class="material-symbols-outlined text-sm opacity-60">lock</span>
            </div>
          </div>
          
          <div class="input-wrapper">
            <label class="input-label">New Email Address</label>
            <input 
              type="email" 
              v-model="emailForm.email" 
              class="custom-input" 
              required
              placeholder="Enter your new email"
            />
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn-primary" :disabled="isUpdatingEmail">
              <span v-if="isUpdatingEmail" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isUpdatingEmail ? 'Updating...' : 'Update Email' }}
            </button>
          </div>
          <div v-if="emailSuccess" class="success-msg">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <span>{{ emailSuccess }}</span>
          </div>
          <div v-if="emailError" class="error-msg">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ emailError }}</span>
          </div>
        </form>

        <!-- Password Form with Eye Toggles -->
        <form @submit.prevent="updatePassword" class="form-group">
          <div class="input-wrapper">
            <label class="input-label">Current Password</label>
            <div class="relative">
              <input 
                :type="showCurrentPassword ? 'text' : 'password'" 
                v-model="passwordForm.currentPassword" 
                class="custom-input pr-10 w-full" 
                required
                placeholder="Enter current password"
              />
              <button 
                type="button" 
                @click="showCurrentPassword = !showCurrentPassword" 
                class="password-toggle-btn"
                tabindex="-1"
                aria-label="Toggle password visibility"
              >
                <span class="material-symbols-outlined text-lg">{{ showCurrentPassword ? 'visibility_off' : 'visibility' }}</span>
              </button>
            </div>
          </div>

          <div class="input-wrapper">
            <label class="input-label">New Password</label>
            <div class="relative">
              <input 
                :type="showNewPassword ? 'text' : 'password'" 
                v-model="passwordForm.newPassword" 
                class="custom-input pr-10 w-full" 
                required
                placeholder="Enter new password (min. 8 characters)"
              />
              <button 
                type="button" 
                @click="showNewPassword = !showNewPassword" 
                class="password-toggle-btn"
                tabindex="-1"
                aria-label="Toggle password visibility"
              >
                <span class="material-symbols-outlined text-lg">{{ showNewPassword ? 'visibility_off' : 'visibility' }}</span>
              </button>
            </div>
          </div>

          <div class="input-wrapper">
            <label class="input-label">Confirm New Password</label>
            <div class="relative">
              <input 
                :type="showConfirmPassword ? 'text' : 'password'" 
                v-model="passwordForm.confirmPassword" 
                class="custom-input pr-10 w-full" 
                required
                placeholder="Confirm new password"
              />
              <button 
                type="button" 
                @click="showConfirmPassword = !showConfirmPassword" 
                class="password-toggle-btn"
                tabindex="-1"
                aria-label="Toggle password visibility"
              >
                <span class="material-symbols-outlined text-lg">{{ showConfirmPassword ? 'visibility_off' : 'visibility' }}</span>
              </button>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="submit" class="btn-primary" :disabled="isUpdatingPassword">
              <span v-if="isUpdatingPassword" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isUpdatingPassword ? 'Updating...' : 'Update Password' }}
            </button>
          </div>
          <div v-if="passwordSuccess" class="success-msg">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <span>{{ passwordSuccess }}</span>
          </div>
          <div v-if="passwordError" class="error-msg">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ passwordError }}</span>
          </div>
        </form>
      </div>

      <!-- 4. DATA RETENTION POLICIES (Admin / Staff Only - All 6 Policies Restored) -->
      <div v-if="isAdminOrStaff" class="settings-card">
        <div class="card-header">
          <div class="card-icon-pill icon-blue">
            <span class="material-symbols-outlined text-lg">auto_delete</span>
          </div>
          <h2 class="card-title">Data Retention Policies</h2>
        </div>
        
        <p class="section-description mb-4">
          Configure how long deleted items and historical data are kept before being permanently purged. Set to 0 to disable automated deletion.
        </p>
        
        <form @submit.prevent="updateRetentionSettings" class="form-group">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="input-wrapper">
              <label class="input-label">Trash Bin TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.trash_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="field-helper">All items in the trashbin</span>
            </div>
            
            <div class="input-wrapper">
              <label class="input-label">Messages TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.messages_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="field-helper">Auto-move to trash after period</span>
            </div>
            
            <div class="input-wrapper">
              <label class="input-label">Main Logs TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.activity_logs_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="field-helper">General system activity logs</span>
            </div>

            <div class="input-wrapper">
              <label class="input-label">Operational Logs TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.operational_logs_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="field-helper">Logins, logouts, user management activities</span>
            </div>

            <div class="input-wrapper">
              <label class="input-label">Archived Documents TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.archived_documents_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="field-helper">Applies to Activity Designs (Accomplishment Reports are permanent)</span>
            </div>

            <div class="input-wrapper">
              <label class="input-label">Drafts TTL (Days)</label>
              <input 
                type="number" 
                v-model.number="retentionForm.drafts_ttl_days" 
                class="custom-input" 
                min="0"
                required
              />
              <span class="field-helper">Pending, revision, or disapproved draft documents</span>
            </div>
          </div>
          
          <div class="form-actions mt-4">
            <button type="submit" class="btn-primary" :disabled="isUpdatingRetention">
              <span v-if="isUpdatingRetention" class="material-symbols-outlined animate-spin text-sm mr-2">refresh</span>
              {{ isUpdatingRetention ? 'Saving...' : 'Save Retention Policies' }}
            </button>
          </div>
          <div v-if="retentionSuccess" class="success-msg">
            <span class="material-symbols-outlined text-base">check_circle</span>
            <span>{{ retentionSuccess }}</span>
          </div>
          <div v-if="retentionError" class="error-msg">
            <span class="material-symbols-outlined text-base">error</span>
            <span>{{ retentionError }}</span>
          </div>
        </form>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import api from '../api';
import Swal from 'sweetalert2';
import { checkOfficeName, generateAcronyms, stringSimilarity } from '../utils/officeChecker';

const user = ref({});

const isAdminOrStaff = computed(() => {
  const role = user.value.role ? user.value.role.toLowerCase() : '';
  return role === 'admin' || role === 'gad_staff' || role === 'superadmin' || role === 'director';
});

// Helper for Swal to match theme
const getSwalTheme = () => {
  const isDark = typeof document !== 'undefined' && document.documentElement.classList.contains('dark');
  return {
    background: isDark ? '#1e1b4b' : '#ffffff',
    color: isDark ? '#f8fafc' : '#0f172a'
  };
};

// Personal Form State
const personalForm = ref({
  first_name: '',
  middle_name: '',
  last_name: '',
  sex: ''
});
const isSavingPersonal = ref(false);
const personalSuccess = ref('');
const personalError = ref('');

// Designation Form State
const designationForm = ref({
  campus_location: 'La Trinidad Campus',
  office_id: '',
  department: '',
  position: '',
  student_id: '',
  year_level: '',
  new_office_name: ''
});
const isSavingDesignation = ref(false);
const designationSuccess = ref('');
const designationError = ref('');
const showOfficeNotice = ref(false);

// Combobox State
const isOfficeDropdownOpen = ref(false);
const officeSearchQuery = ref('');
const officeDropdownRef = ref(null);

// Offices & Campus Affiliation
const officeUnits = ref([]);
const fetchOffices = async () => {
  try {
    const res = await api.get('office_units');
    officeUnits.value = Array.isArray(res.data) ? res.data : (res.data?.data || []);
  } catch (err) {
    console.error("Fetch offices error:", err);
  }
};

const officesForSelectedCampus = computed(() => {
  if (!designationForm.value.campus_location) return officeUnits.value;
  return officeUnits.value.filter(u => !u.location || u.location === designationForm.value.campus_location);
});

// Smart Filter for Offices
const filteredOffices = computed(() => {
  const base = officesForSelectedCampus.value;
  if (!officeSearchQuery.value) return base;

  const currentOffice = base.find(u => String(u.unit_id) === String(designationForm.value.office_id));
  const currentName = currentOffice ? `${currentOffice.unit_name}${currentOffice.office_acronym ? ' (' + currentOffice.office_acronym + ')' : ''}` : '';
  if (currentName && officeSearchQuery.value === currentName) {
    return base;
  }

  const q = officeSearchQuery.value.trim().toLowerCase();
  const cleanQ = q.replace(/[^a-z0-9]/g, '');

  return base.filter(u => {
    const uName = u.unit_name.toLowerCase();
    const uAcronym = (u.office_acronym || '').toLowerCase().replace(/[^a-z0-9]/g, '');

    if (uName.includes(q)) return true;
    if (uAcronym && (uAcronym.includes(cleanQ) || cleanQ.includes(uAcronym))) return true;

    const generated = generateAcronyms(u.unit_name);
    if (generated.some(ac => ac.toLowerCase() === cleanQ)) return true;

    if (q.length >= 4 && stringSimilarity(u.unit_name, q) > 0.6) return true;

    return false;
  });
});

const syncOfficeSearchQuery = () => {
  if (designationForm.value.office_id === 'add_new') {
    officeSearchQuery.value = designationForm.value.new_office_name ? designationForm.value.new_office_name : '';
  } else if (designationForm.value.office_id) {
    const found = officeUnits.value.find(u => String(u.unit_id) === String(designationForm.value.office_id));
    if (found) {
      officeSearchQuery.value = found.unit_name + (found.office_acronym ? ` (${found.office_acronym})` : '');
    }
  } else {
    officeSearchQuery.value = '';
  }
};

const handleOfficeFocus = () => {
  isOfficeDropdownOpen.value = true;
};

const handleOfficeSearchInput = () => {
  isOfficeDropdownOpen.value = true;
  if (designationForm.value.office_id !== 'add_new') {
    designationForm.value.office_id = '';
  }
};

const selectOffice = (unit) => {
  designationForm.value.office_id = unit.unit_id;
  officeSearchQuery.value = unit.unit_name + (unit.office_acronym ? ` (${unit.office_acronym})` : '');
  designationForm.value.new_office_name = '';
  isOfficeDropdownOpen.value = false;
};

const selectAddNew = () => {
  designationForm.value.office_id = 'add_new';
  designationForm.value.new_office_name = officeSearchQuery.value.trim();
  officeSearchQuery.value = 'Add New Office';
  isOfficeDropdownOpen.value = false;
};

const cancelAddNewOffice = () => {
  const fallback = officesForSelectedCampus.value[0];
  if (fallback) {
    selectOffice(fallback);
  } else {
    designationForm.value.office_id = '';
    officeSearchQuery.value = '';
  }
  designationForm.value.new_office_name = '';
};

const useMatchedOfficeInSettings = (unit) => {
  selectOffice(unit);
};

const handleCampusChange = () => {
  const currentOfficeBelongs = officesForSelectedCampus.value.some(
    u => String(u.unit_id) === String(designationForm.value.office_id)
  );
  if (!currentOfficeBelongs && officesForSelectedCampus.value.length > 0) {
    selectOffice(officesForSelectedCampus.value[0]);
  }
};

const handleClickOutside = (e) => {
  if (officeDropdownRef.value && !officeDropdownRef.value.contains(e.target)) {
    isOfficeDropdownOpen.value = false;
    syncOfficeSearchQuery();
  }
};

// Smart Office Checker for UserSettings
const newOfficeCheckResult = computed(() => {
  return checkOfficeName(
    designationForm.value.new_office_name, 
    officeUnits.value, 
    designationForm.value.campus_location
  );
});

// Avatar Upload
const avatarPreview = ref('');

// Email & Password State
const emailForm = ref({ email: '' });
const isUpdatingEmail = ref(false);
const emailSuccess = ref('');
const emailError = ref('');

const passwordForm = ref({
  currentPassword: '',
  newPassword: '',
  confirmPassword: ''
});
const isUpdatingPassword = ref(false);
const passwordSuccess = ref('');
const passwordError = ref('');

const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

// Retention State (All 6 policies)
const retentionForm = ref({
  trash_ttl_days: 30,
  messages_ttl_days: 365,
  activity_logs_ttl_days: 365,
  operational_logs_ttl_days: 90,
  archived_documents_ttl_days: 1825,
  drafts_ttl_days: 365
});
const isUpdatingRetention = ref(false);
const retentionSuccess = ref('');
const retentionError = ref('');

const computedFullName = computed(() => {
  return [personalForm.value.first_name, personalForm.value.middle_name, personalForm.value.last_name]
    .filter(Boolean)
    .join(' ')
    .trim() || user.value.full_name || 'N/A';
});

const userInitials = computed(() => {
  const name = user.value.full_name || user.value.username || 'U';
  return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
});

const getAvatarUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  const baseUrl = import.meta.env.VITE_API_BASE_URL?.replace('/api/', '/') || 'http://localhost:8080/';
  return `${baseUrl.replace(/\/$/, '')}/${path.replace(/^\//, '')}`;
};

const handleAvatarSelected = async (e) => {
  const file = e.target.files[0];
  if (!file) return;

  if (file.size > 2 * 1024 * 1024) {
    Swal.fire({ icon: 'error', title: 'File Too Large', text: 'Profile picture must be under 2MB.', ...getSwalTheme() });
    return;
  }

  avatarPreview.value = URL.createObjectURL(file);

  const formData = new FormData();
  formData.append('profile_picture', file);

  try {
    const res = await api.post('/users/profile/update', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    if (res.data.success) {
      user.value.profile_picture = res.data.avatar_url;
      const stored = JSON.parse(localStorage.getItem('user') || '{}');
      stored.profile_picture = res.data.avatar_url;
      localStorage.setItem('user', JSON.stringify(stored));
      window.dispatchEvent(new CustomEvent('user-updated', { detail: stored }));

      Swal.fire({ icon: 'success', title: 'Updated!', text: 'Profile picture updated successfully.', timer: 2000, showConfirmButton: false, ...getSwalTheme() });
    }
  } catch (err) {
    console.error('Failed to upload avatar:', err);
    Swal.fire({ icon: 'error', title: 'Upload Failed', text: err.response?.data?.message || 'Failed to upload image.', ...getSwalTheme() });
  }
};

const handleRemoveAvatar = async () => {
  const result = await Swal.fire({
    title: 'Remove Profile Picture?',
    text: 'Your current profile picture will be removed.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Yes, remove it',
    ...getSwalTheme()
  });

  if (!result.isConfirmed) return;

  try {
    const res = await api.post('/users/profile/update', { remove_avatar: true });
    if (res.data.success) {
      user.value.profile_picture = '';
      avatarPreview.value = '';
      const stored = JSON.parse(localStorage.getItem('user') || '{}');
      stored.profile_picture = '';
      localStorage.setItem('user', JSON.stringify(stored));
      window.dispatchEvent(new CustomEvent('user-updated', { detail: stored }));

      Swal.fire({ icon: 'success', title: 'Removed', text: 'Profile picture removed successfully.', timer: 2000, showConfirmButton: false, ...getSwalTheme() });
    }
  } catch (err) {
    console.error('Failed to remove avatar:', err);
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to remove profile picture.', ...getSwalTheme() });
  }
};

const fetchProfile = async () => {
  try {
    const res = await api.get('/users/profile');
    if (res.data.success) {
      const u = res.data.user;
      user.value = { ...user.value, ...u };

      personalForm.value = {
        first_name: u.first_name || '',
        middle_name: u.middle_name || '',
        last_name: u.last_name || '',
        sex: u.sex || ''
      };

      designationForm.value = {
        campus_location: u.location || 'La Trinidad Campus',
        office_id: u.office_id || '',
        department: u.department || '',
        position: u.position || '',
        student_id: u.student_id || '',
        year_level: u.year_level || '',
        new_office_name: ''
      };

      emailForm.value.email = u.email || '';

      const storedUser = JSON.parse(localStorage.getItem('user') || '{}');
      const updatedUser = { ...storedUser, ...u };
      localStorage.setItem('user', JSON.stringify(updatedUser));
      window.dispatchEvent(new CustomEvent('user-updated', { detail: updatedUser }));
      syncOfficeSearchQuery();
    }
  } catch (error) {
    console.error("Failed to fetch profile", error);
  }
};

onMounted(async () => {
  const storedUser = JSON.parse(localStorage.getItem('user') || '{}');
  user.value = storedUser;
  
  await fetchOffices();
  await fetchProfile();
  syncOfficeSearchQuery();
  document.addEventListener('mousedown', handleClickOutside);

  if (isAdminOrStaff.value) {
    try {
      const res = await api.get('/settings/system');
      if (res.data) {
        retentionForm.value = {
          trash_ttl_days: res.data.trash_ttl_days ?? 30,
          messages_ttl_days: res.data.messages_ttl_days ?? 365,
          activity_logs_ttl_days: res.data.activity_logs_ttl_days ?? 365,
          operational_logs_ttl_days: res.data.operational_logs_ttl_days ?? 90,
          archived_documents_ttl_days: res.data.archived_documents_ttl_days ?? 1825,
          drafts_ttl_days: res.data.drafts_ttl_days ?? 365
        };
      }
    } catch (error) {
      console.error("Failed to fetch system settings", error);
    }
  }
});

onUnmounted(() => {
  document.removeEventListener('mousedown', handleClickOutside);
});

const savePersonalInfo = async () => {
  isSavingPersonal.value = true;
  personalSuccess.value = '';
  personalError.value = '';

  try {
    const res = await api.post('/users/profile/update', personalForm.value);
    if (res.data.success) {
      personalSuccess.value = 'Personal information saved successfully.';
      await fetchProfile();
    } else {
      personalError.value = res.data.message || 'Failed to save personal info.';
    }
  } catch (err) {
    personalError.value = err.response?.data?.message || 'Error saving personal info.';
  } finally {
    isSavingPersonal.value = false;
  }
};

const saveDesignation = async () => {
  isSavingDesignation.value = true;
  designationSuccess.value = '';
  designationError.value = '';

  try {
    const payload = { ...designationForm.value };
    if (payload.office_id === 'add_new') {
      if (!payload.new_office_name || !payload.new_office_name.trim()) {
        designationError.value = 'Please enter the full new college or office name.';
        isSavingDesignation.value = false;
        return;
      }
      const check = newOfficeCheckResult.value;
      if (check.hasMatch) {
        designationError.value = `Office already exists: Please select "${check.matchedOffice.unit_name}" from the list instead of adding a duplicate.`;
        isSavingDesignation.value = false;
        return;
      }
      if (check.isAbbreviation) {
        designationError.value = `Abbreviations are not allowed. Please enter the full official office name without acronyms (e.g. "College of Agriculture" instead of "${payload.new_office_name.trim()}").`;
        isSavingDesignation.value = false;
        return;
      }
    }
    const res = await api.post('/users/profile/update', payload);
    if (res.data.success) {
      designationSuccess.value = 'Designation and office affiliation saved successfully.';
      await fetchOffices();
      await fetchProfile();
    } else {
      designationError.value = res.data.message || 'Failed to save designation.';
    }
  } catch (err) {
    designationError.value = err.response?.data?.message || 'Error saving designation.';
  } finally {
    isSavingDesignation.value = false;
  }
};

const updateEmail = async () => {
  if (emailForm.value.email === user.value.email) {
    emailError.value = 'New email is the same as the current email.';
    return;
  }

  const result = await Swal.fire({
    title: 'Are you sure?',
    text: "Do you want to update your email address?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#9333ea',
    cancelButtonColor: '#64748b',
    confirmButtonText: 'Yes, update it!',
    ...getSwalTheme()
  });

  if (!result.isConfirmed) return;

  isUpdatingEmail.value = true;
  emailSuccess.value = '';
  emailError.value = '';

  try {
    const res = await api.post('/users/profile/update', { email: emailForm.value.email });
    if (res.data.success) {
      emailSuccess.value = 'Email address updated successfully.';
      await fetchProfile();
    } else {
      emailError.value = res.data.message || 'Failed to update email.';
    }
  } catch (err) {
    emailError.value = err.response?.data?.message || 'Error updating email.';
  } finally {
    isUpdatingEmail.value = false;
  }
};

const updatePassword = async () => {
  if (passwordForm.value.newPassword !== passwordForm.value.confirmPassword) {
    passwordError.value = 'New passwords do not match.';
    return;
  }

  isUpdatingPassword.value = true;
  passwordSuccess.value = '';
  passwordError.value = '';

  try {
    const res = await api.post('/users/profile/update', {
      current_password: passwordForm.value.currentPassword,
      new_password: passwordForm.value.newPassword
    });

    if (res.data.success) {
      passwordSuccess.value = 'Password updated successfully.';
      passwordForm.value = { currentPassword: '', newPassword: '', confirmPassword: '' };
    } else {
      passwordError.value = res.data.message || 'Failed to update password.';
    }
  } catch (err) {
    passwordError.value = err.response?.data?.message || 'Error updating password.';
  } finally {
    isUpdatingPassword.value = false;
  }
};

const updateRetentionSettings = async () => {
  isUpdatingRetention.value = true;
  retentionSuccess.value = '';
  retentionError.value = '';
  
  try {
    const res = await api.post('/settings/system', retentionForm.value);
    if (res.status === 200 || res.status === 201 || (res.data && res.data.message)) {
      retentionSuccess.value = 'Data retention policies updated successfully.';
      
      try {
        api.post('/settings/trigger-cleanup').catch(e => console.log('Silent cleanup notice', e));
      } catch (e) {}
      
    } else {
      retentionError.value = 'Failed to update retention policies.';
    }
  } catch (err) {
    retentionError.value = err.response?.data?.message || 'An error occurred while updating policies.';
  } finally {
    isUpdatingRetention.value = false;
  }
};
</script>

<style scoped>
.settings-container {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-bottom: 2.5rem;
}

/* ==========================================================================
   Header Banner (Clean Light & Dark Themes)
   ========================================================================== */
.settings-header {
  width: 100%;
  max-width: 800px;
  background: linear-gradient(135deg, #ffffff 0%, #faf5ff 55%, #f3e8ff 100%);
  padding: 1.75rem 2rem;
  border-radius: 1.25rem;
  border: 1px solid #e9d5ff;
  box-shadow: 0 10px 25px -5px rgba(147, 51, 234, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
}

.settings-header::before {
  content: '';
  position: absolute;
  top: -50px;
  right: -50px;
  width: 150px;
  height: 150px;
  background: rgba(147, 51, 234, 0.08);
  border-radius: 50%;
  filter: blur(25px);
  pointer-events: none;
}

.avatar-container {
  background: #f3e8ff;
  color: #7e22ce;
  border: 2px solid #e9d5ff;
}

.role-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: #f3e8ff;
  color: #7e22ce;
  border: 1px solid #e9d5ff;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.settings-title {
  font-size: 1.75rem;
  font-weight: 800;
  color: #1e1b4b;
  margin: 0;
  letter-spacing: -0.02em;
  transition: color 0.3s ease;
}

.settings-subtitle {
  font-size: 0.95rem;
  color: #64748b;
  margin-top: 0.25rem;
  font-weight: 500;
  transition: color 0.3s ease;
}

/* ==========================================================================
   Content & Cards
   ========================================================================== */
.settings-content {
  width: 100%;
  max-width: 800px;
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
}

.settings-card {
  border-radius: 1.25rem;
  border: 1px solid #e2e8f0;
  background: #ffffff;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.04), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
  padding: 2rem;
  transition: all 0.3s ease;
}

.card-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
  border-bottom: 1px solid #f1f5f9;
  padding-bottom: 1rem;
  transition: border-color 0.3s ease;
}

.card-icon-pill {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.icon-purple {
  background: #f3e8ff;
  color: #7e22ce;
  border: 1px solid #e9d5ff;
}

.icon-pink {
  background: #fdf2f8;
  color: #db2777;
  border: 1px solid #fbcfe8;
}

.icon-blue {
  background: #eff6ff;
  color: #2563eb;
  border: 1px solid #dbeafe;
}

.card-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
  transition: color 0.3s ease;
}

.section-description {
  color: #64748b;
  font-size: 0.875rem;
  line-height: 1.5;
  transition: color 0.3s ease;
}

/* ==========================================================================
   Form Elements
   ========================================================================== */
.form-group {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.form-divider {
  border-bottom: 1px solid #f1f5f9;
  padding-bottom: 1.5rem;
  margin-bottom: 1.5rem;
  transition: border-color 0.3s ease;
}

.input-wrapper {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
  min-width: 0;
  width: 100%;
}

.input-label {
  font-size: 0.8rem;
  font-weight: 700;
  color: #475569;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  transition: color 0.3s ease;
}

.readonly-field {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #f8fafc;
  color: #1e293b;
  border: 1px solid #e2e8f0;
  padding: 0.75rem 1rem;
  border-radius: 0.625rem;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  min-width: 0;
  max-width: 100%;
}

.custom-input {
  width: 100%;
  max-width: 100%;
  min-width: 0;
  box-sizing: border-box;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  color: #0f172a;
  padding: 0.75rem 1rem;
  border-radius: 0.625rem;
  font-size: 0.95rem;
  transition: all 0.2s ease;
  outline: none;
}

select.custom-input {
  max-width: 100%;
  min-width: 0;
  text-overflow: ellipsis;
  overflow: hidden;
  white-space: nowrap;
}

.custom-input::placeholder {
  color: #94a3b8;
}

.custom-input:focus {
  border-color: #9333ea;
  box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.12);
  background: #ffffff;
}

.password-toggle-btn {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: #94a3b8;
  padding: 0.25rem;
  background: none;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s ease;
}

.password-toggle-btn:hover {
  color: #475569;
}

.field-helper {
  font-size: 0.75rem;
  color: #64748b;
  margin-top: 0.25rem;
  transition: color 0.3s ease;
}

.form-actions {
  margin-top: 0.5rem;
}

/* ==========================================================================
   Buttons
   ========================================================================== */
.btn-primary {
  background: linear-gradient(135deg, #9333ea, #7e22ce);
  color: white;
  font-weight: 600;
  font-size: 0.925rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0.625rem;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(147, 51, 234, 0.25);
  transition: all 0.2s ease;
}

.btn-primary:hover:not(:disabled) {
  background: linear-gradient(135deg, #a855f7, #9333ea);
  transform: translateY(-1px);
  box-shadow: 0 6px 16px rgba(147, 51, 234, 0.35);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

/* ==========================================================================
   Alert Messages
   ========================================================================== */
.success-msg {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: #dcfce7;
  color: #15803d;
  border: 1px solid #bbf7d0;
  padding: 0.625rem 0.875rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  margin-top: 0.5rem;
}

.error-msg {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: #fee2e2;
  color: #b91c1c;
  border: 1px solid #fecaca;
  padding: 0.625rem 0.875rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  margin-top: 0.5rem;
}

/* ==========================================================================
   Dark Mode Overrides
   ========================================================================== */
:global(.dark) .settings-header,
.dark .settings-header {
  background: linear-gradient(135deg, #2e1065, #1e1b4b);
  border-color: rgba(168, 85, 247, 0.25);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4), 0 8px 10px -6px rgba(168, 85, 247, 0.15);
}

:global(.dark) .settings-header::before,
.dark .settings-header::before {
  background: rgba(168, 85, 247, 0.15);
}

:global(.dark) .avatar-container,
.dark .avatar-container {
  background: rgba(88, 28, 135, 0.5);
  color: #e9d5ff;
  border-color: rgba(168, 85, 247, 0.4);
}

:global(.dark) .role-badge,
.dark .role-badge {
  background: rgba(147, 51, 234, 0.25);
  color: #d8b4fe;
  border-color: rgba(168, 85, 247, 0.35);
}

:global(.dark) .settings-title,
.dark .settings-title {
  color: #ffffff;
}

:global(.dark) .settings-subtitle,
.dark .settings-subtitle {
  color: #d8b4fe;
}

:global(.dark) .settings-card,
.dark .settings-card {
  background: linear-gradient(135deg, #0f172a, #020617);
  border-color: rgba(147, 51, 234, 0.2);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

:global(.dark) .card-header,
.dark .card-header {
  border-bottom-color: rgba(255, 255, 255, 0.08);
}

:global(.dark) .card-title,
.dark .card-title {
  color: #f8fafc;
}

:global(.dark) .icon-purple,
.dark .icon-purple {
  background: rgba(147, 51, 234, 0.25);
  color: #d8b4fe;
  border-color: rgba(168, 85, 247, 0.35);
}

:global(.dark) .icon-pink,
.dark .icon-pink {
  background: rgba(236, 72, 153, 0.25);
  color: #f472b6;
  border-color: rgba(236, 72, 153, 0.35);
}

:global(.dark) .icon-blue,
.dark .icon-blue {
  background: rgba(37, 99, 235, 0.25);
  color: #93c5fd;
  border-color: rgba(37, 99, 235, 0.35);
}

:global(.dark) .section-description,
.dark .section-description {
  color: #94a3b8;
}

:global(.dark) .form-divider,
.dark .form-divider {
  border-color: rgba(51, 65, 85, 0.6);
}

:global(.dark) .input-label,
.dark .input-label {
  color: #94a3b8;
}

:global(.dark) .readonly-field,
.dark .readonly-field {
  background: rgba(30, 41, 59, 0.5);
  border-color: rgba(51, 65, 85, 0.6);
  color: #f8fafc;
}

:global(.dark) .custom-input,
.dark .custom-input {
  background: rgba(15, 23, 42, 0.6);
  border-color: rgba(147, 51, 234, 0.25);
  color: #ffffff;
}

:global(.dark) .custom-input::placeholder,
.dark .custom-input::placeholder {
  color: #64748b;
}

:global(.dark) .custom-input:focus,
.dark .custom-input:focus {
  border-color: #c084fc;
  background: rgba(15, 23, 42, 0.85);
  box-shadow: 0 0 0 3px rgba(192, 132, 252, 0.2);
}

:global(.dark) .password-toggle-btn,
.dark .password-toggle-btn {
  color: #64748b;
}

:global(.dark) .password-toggle-btn:hover,
.dark .password-toggle-btn:hover {
  color: #cbd5e1;
}

:global(.dark) .field-helper,
.dark .field-helper {
  color: #94a3b8;
}

:global(.dark) .success-msg,
.dark .success-msg {
  background: rgba(34, 197, 94, 0.12);
  color: #4ade80;
  border-color: rgba(34, 197, 94, 0.25);
}

:global(.dark) .error-msg,
.dark .error-msg {
  background: rgba(239, 68, 68, 0.12);
  color: #f87171;
  border-color: rgba(239, 68, 68, 0.25);
}

/* ==========================================================================
   Custom Office Combobox Dropdown (Strictly Contained, Zero Overflow)
   ========================================================================== */
.office-dropdown-menu {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
  overflow-x: hidden;
}

:global(.dark) .office-dropdown-menu,
.dark .office-dropdown-menu {
  background: #141026;
  border-color: rgba(168, 85, 247, 0.35);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6);
}

.office-dropdown-item {
  color: #334155;
  transition: all 0.15s ease;
}

.office-dropdown-item:hover {
  background: #f8fafc;
  color: #7e22ce;
}

.office-dropdown-item.is-selected {
  background: rgba(147, 51, 234, 0.08);
  color: #7e22ce;
  font-weight: 700;
}

:global(.dark) .office-dropdown-item,
.dark .office-dropdown-item {
  color: #cbd5e1;
}

:global(.dark) .office-dropdown-item:hover,
.dark .office-dropdown-item:hover {
  background: rgba(147, 51, 234, 0.2);
  color: #e9d5ff;
}

:global(.dark) .office-dropdown-item.is-selected,
.dark .office-dropdown-item.is-selected {
  background: rgba(147, 51, 234, 0.3);
  color: #f3e8ff;
}

.add-new-item:hover {
  background: rgba(147, 51, 234, 0.12) !important;
}

/* ==========================================================================
   Mobile Responsiveness
   ========================================================================== */
@media (max-width: 640px) {
  .settings-container {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    padding-bottom: 2rem;
  }

  .settings-header {
    padding: 1.25rem 1rem;
    border-radius: 1rem;
  }

  .settings-title {
    font-size: 1.35rem;
  }

  .settings-card {
    padding: 1.25rem 1rem;
    border-radius: 1rem;
  }

  .card-title {
    font-size: 1.15rem;
  }

  .custom-input {
    font-size: 0.9rem;
    padding: 0.65rem 0.875rem;
  }
}
</style>
