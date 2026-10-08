<template>
  <main class="main-content">
    <div class="content-wrapper">
      
      <div class="page-header">
        <h1 class="page-title">Post a Bulletin</h1>
        <p class="page-subtitle">Post new News updates, IEC materials, or Announcements for the public GAD Corner.</p>
      </div>

      <div class="form-container">
        <form @submit.prevent="confirmPublish" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="input-group md:col-span-2">
              <label class="input-label">Title <span class="text-red-500 dark:text-red-400">*</span></label>
              <textarea v-model="form.title" class="custom-input resize-none overflow-hidden" rows="1" placeholder="Enter title" required @input="autoResize" style="min-height: 48px;"></textarea>
            </div>

            <div class="input-group md:col-span-2">
              <label class="input-label">Category <span class="text-red-500 dark:text-red-400">*</span></label>
              <div class="select-wrapper">
                <select v-model="form.category" class="custom-select" required>
                  <option value="News">News</option>
                  <option value="IEC">IEC Material</option>
                  <option value="Announcement">Announcement</option>
                </select>
                <span class="select-arrow">▼</span>
              </div>
            </div>

            <div class="input-group md:col-span-2">
              <label class="input-label">Description (Optional)</label>
              
              <div class="relative w-full">
                <!-- Highlighted Text Overlay -->
                <div class="desc-overlay absolute inset-0 custom-input pointer-events-none whitespace-pre-wrap break-words overflow-hidden" v-html="highlightedDescription"></div>
                <!-- Actual Textarea -->
                <textarea 
                  v-model="form.description" 
                  rows="4" 
                  class="desc-textarea custom-input relative z-10 w-full bg-transparent resize-none overflow-hidden" 
                  @scroll="syncScroll"
                  @input="autoResize"
                  ref="descTextarea"
                  placeholder="Enter description..."
                ></textarea>
              </div>
            </div>

            <div class="input-group md:col-span-2">
              <label class="input-label">Images (Optional)</label>
              <div 
                class="upload-dropzone"
                @dragover.prevent
                @drop.prevent="handleFileDrop"
                @click="$refs.fileInput.click()"
              >
                <input ref="fileInput" @change="handleFileChange" type="file" multiple accept="image/*" class="hidden" />
                <span class="material-symbols-outlined dropzone-icon">cloud_upload</span>
                <p class="dropzone-text">Click or drag and drop images here</p>
                <p class="dropzone-subtext">Supports JPG, PNG, WEBP</p>
              </div>
              
              <!-- Small Previews inside the form -->
              <div v-if="previewImageUrls.length > 0" class="preview-images-container flex flex-wrap gap-4 mt-3 p-4 rounded-xl">
                <div v-for="(url, idx) in previewImageUrls" :key="idx" class="preview-image-item relative w-20 h-20 rounded-lg overflow-hidden group shadow-md">
                  <img alt="Preview thumbnail" :src="url" class="object-cover w-full h-full" />
                  <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <button @click.prevent="removeImage(idx)" class="text-red-400 hover:text-red-300 bg-white/10 p-1.5 rounded-full backdrop-blur-md shadow-sm">
                      <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="input-group md:col-span-2">
              <label class="input-label">Tags (Optional)</label>
              <div class="flex gap-2 mb-2 flex-wrap" v-if="tagsList.length > 0">
                <span v-for="(tag, index) in tagsList" :key="index" class="tag-chip">
                  #{{ tag }}
                  <button @click.prevent="removeTag(index)" class="tag-remove-btn">&times;</button>
                </span>
              </div>
              <div class="relative flex items-center w-full">
                <input v-model="currentTagInput" @keydown.enter.prevent="addTag" type="text" class="custom-input w-full pr-24" placeholder="Add a tag..." />
                <button 
                  @click.prevent="addTag" 
                  :disabled="!currentTagInput.trim()" 
                  class="tag-add-btn"
                >Add</button>
              </div>
            </div>
          </div>

          <div class="pt-6 flex flex-col sm:flex-row justify-end gap-4">
            <button type="button" @click="openPreview" class="preview-btn w-full sm:w-auto justify-center">
              <span class="material-symbols-outlined text-sm">visibility</span>
              Preview
            </button>
            <button type="submit" :disabled="loading" class="publish-btn w-full sm:w-auto justify-center">
              <span class="material-symbols-outlined text-sm">publish</span>
              {{ loading ? 'Publishing...' : 'Publish' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Live Preview Modal -->
      <div v-if="showPreview" class="preview-modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4" style="margin-left: 0;">
        <div class="preview-modal-container form-container max-w-4xl w-full shadow-2xl relative max-h-[90vh] overflow-y-auto p-6 md:p-8">
          <div class="flex justify-between items-center mb-6">
            <h3 class="preview-modal-title text-xl font-headline font-bold">Live Preview</h3>
            <button @click="showPreview = false" class="preview-modal-close transition-colors">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>
          
          <!-- Full Post Preview -->
          <div class="bg-slate-50 dark:bg-slate-900 rounded-2xl shadow-xl overflow-hidden mb-8 max-w-4xl mx-auto border border-slate-200 dark:border-slate-800">
            <div class="p-8 md:p-12 pb-12 text-left">
              
              <!-- Tags (At the very top) -->
              <div class="mb-6 flex flex-wrap gap-3" v-if="tagsList.length > 0">
                <span v-for="tag in tagsList" :key="tag" class="text-sm font-body text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 px-4 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 flex items-center gap-2">
                  {{ tag.trim() }} <span class="material-symbols-outlined text-[14px] text-slate-400">arrow_outward</span>
                </span>
              </div>

              <!-- Title -->
              <div class="mb-6">
                <h1 class="text-4xl md:text-5xl lg:text-[56px] font-headline font-black text-slate-900 dark:text-white leading-[1.1] tracking-tight">{{ form.title || 'Untitled Material' }}</h1>
              </div>

              <!-- Meta Info -->
              <div class="mb-12 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-sm text-slate-600 dark:text-slate-400 font-body">
                <div class="flex items-center gap-3">
                  <img src="/images/logo.png" class="w-10 h-10 rounded-full object-contain bg-white border border-slate-100 shadow-sm" alt="Author" />
                  <div class="flex items-center flex-wrap gap-x-2">
                    <span class="font-medium text-slate-900 dark:text-slate-200">BSU GAD Office</span>
                    <span class="px-3 py-0.5 rounded-full border border-slate-200 dark:border-slate-700 text-xs font-medium">{{ form.category }}</span>
                    <span>&middot;</span>
                    <span>{{ new Date().toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) }}</span>
                  </div>
                </div>
              </div>

              <!-- Image Carousel -->
              <div class="relative min-h-[300px] w-full bg-slate-100 dark:bg-slate-800 rounded-xl overflow-hidden mb-12 flex items-center justify-center">
                <img alt="Full preview" v-if="previewImageUrls.length > 0" :src="previewImageUrls[currentPreviewIndex]" class="w-full max-h-[80vh] object-contain transition-all duration-300" />
                <div v-else class="w-full h-full flex items-center justify-center bg-slate-200 dark:bg-slate-700">
                  <span class="material-symbols-outlined text-6xl text-slate-400">newspaper</span>
                </div>
                
                <button v-if="previewImageUrls.length > 0" @click.prevent="removeImage(currentPreviewIndex)" class="absolute top-4 left-4 bg-red-600/90 text-white p-2 rounded-full hover:bg-red-500 transition-colors backdrop-blur-sm shadow-md z-30 flex items-center justify-center">
                  <span class="material-symbols-outlined">delete</span>
                </button>
                
                <!-- Carousel Arrows -->
                <div v-if="previewImageUrls.length > 1" class="absolute inset-0 flex items-center justify-between px-4 pointer-events-none z-40">
                  <button @click.prevent="prevImage" class="pointer-events-auto w-14 h-14 flex items-center justify-center rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.5)] hover:scale-110 active:scale-95 transition-all" style="background-color: white !important; color: black !important; border: 2px solid rgba(0,0,0,0.1) !important; opacity: 1 !important;">
                    <span class="material-symbols-outlined font-black text-3xl" style="color: black !important; font-weight: 900 !important;">chevron_left</span>
                  </button>
                  <button @click.prevent="nextImage" class="pointer-events-auto w-14 h-14 flex items-center justify-center rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.5)] hover:scale-110 active:scale-95 transition-all" style="background-color: white !important; color: black !important; border: 2px solid rgba(0,0,0,0.1) !important; opacity: 1 !important;">
                    <span class="material-symbols-outlined font-black text-3xl" style="color: black !important; font-weight: 900 !important;">chevron_right</span>
                  </button>
                </div>
                <!-- Dots indicator -->
                <div v-if="previewImageUrls.length > 1" class="absolute bottom-6 left-0 right-0 flex justify-center gap-3">
                  <div v-for="(_, idx) in previewImageUrls" :key="idx" 
                       class="w-2.5 h-2.5 rounded-full transition-all shadow-sm cursor-pointer"
                       :class="idx === currentPreviewIndex ? 'bg-white scale-125' : 'bg-white/50 hover:bg-white/80'"
                       @click="currentPreviewIndex = idx">
                  </div>
                </div>
              </div>

              <!-- Description -->
              <div class="text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-wrap text-lg md:text-xl font-body" v-html="linkify(form.description) || '<span class=\'text-slate-400\'>No description provided.</span>'"></div>
            </div>
          </div>

          <div class="mt-8 flex justify-end gap-4">
            <button @click="showPreview = false" class="preview-btn text-sm">Edit</button>
            <button @click="publishFromPreview" :disabled="loading" class="publish-btn text-sm">
              <span class="material-symbols-outlined text-sm">publish</span>
              Publish Now
            </button>
          </div>
        </div>
      </div>

      <div class="table-container mt-8">
        <div class="table-card-header">
          <h2 class="table-card-title text-xl font-headline font-bold">Published Bulletin Items</h2>
        </div>
        
        <div v-if="loadingItems" class="empty-state">Loading...</div>
        <div v-else-if="items.length === 0" class="empty-state">No items published yet.</div>
        
        <div v-else class="table-wrapper">
          <table class="data-table">
            <thead>
              <tr class="table-header-row">
                <th class="table-header-cell">Category</th>
                <th class="table-header-cell">Title</th>
                <th class="table-header-cell">Date Published</th>
                <th class="table-header-cell text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="table-body">
              <tr v-for="item in items" :key="item.id" class="table-row">
                <td class="table-cell">
                  <span :class="[
                    'category-badge',
                    item.category === 'News' ? 'badge-news' :
                    item.category === 'IEC' ? 'badge-iec' : 'badge-announcement'
                  ]">
                    {{ item.category }}
                  </span>
                </td>
                <td class="table-cell title-cell">{{ item.title }}</td>
                <td class="table-cell date-cell">{{ new Date(item.created_at).toLocaleDateString() }}</td>
                <td class="table-cell text-right">
                  <button @click="deleteItem(item.id)" class="btn-delete-item transition-colors" title="Delete">
                    <span class="material-symbols-outlined text-sm">delete</span>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '../../api';
import Swal from 'sweetalert2';

const loading = ref(false);
const loadingItems = ref(true);
const items = ref([]);
const showPreview = ref(false);
const previewImageUrls = ref([]);
const currentPreviewIndex = ref(0);
const tagsList = ref([]);
const currentTagInput = ref('');

const addTag = () => {
  const val = currentTagInput.value.trim();
  if (val && !tagsList.value.includes(val)) {
    tagsList.value.push(val);
  }
  currentTagInput.value = '';
};

const removeTag = (idx) => {
  tagsList.value.splice(idx, 1);
};

const descTextarea = ref(null);
const autoResize = (event) => {
  const el = event.target;
  el.style.height = 'auto';
  el.style.height = el.scrollHeight + 'px';
};

const syncScroll = (e) => {
  const overlay = e.target.previousElementSibling;
  if (overlay) {
    overlay.scrollTop = e.target.scrollTop;
    overlay.scrollLeft = e.target.scrollLeft;
  }
};
const highlightedDescription = computed(() => {
  let text = form.value.description || '';
  if (!text) {
    // Return placeholder formatting if empty
    return '<span class="text-slate-400">Enter description...</span>';
  }
  // Escape HTML first to prevent XSS and formatting issues
  const escapeHTML = (str) => str.replace(/[&<>'"]/g, 
    tag => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        "'": '&#39;',
        '"': '&quot;'
    }[tag] || tag)
  );
  text = escapeHTML(text);
  
  // Highlight links
  const urlRegex = /(https?:\/\/[^\s]+|(?:www\.)?[a-zA-Z0-9-]+\.(?:com|org|net|edu|gov|ph|io|co|info|me)(?:\/[^\s]*)?)/ig;
  return text.replace(urlRegex, (url) => `<span class="text-purple-600 dark:text-purple-400 underline font-semibold">${url}</span>`);
});

const linkify = (text) => {
  if (!text) return '';
  const urlRegex = /(https?:\/\/[^\s]+|(?:www\.)?[a-zA-Z0-9-]+\.(?:com|org|net|edu|gov|ph|io|co|info|me)(?:\/[^\s]*)?)/ig;
  return text.replace(urlRegex, function(url) {
    let href = url;
    if (!/^https?:\/\//i.test(href)) {
      href = 'https://' + href;
    }
    return `<a href="${href}" target="_blank" class="text-blue-400 hover:underline break-all">${url}</a>`;
  });
};

const form = ref({
  title: '',
  category: 'News',
  tags: '',
  description: '',
  images: []
});

const handleFileChange = (e) => {
  if (e.target.files.length > 0) {
    const files = Array.from(e.target.files);
    // Append instead of replace
    form.value.images = [...form.value.images, ...files];
    
    // Regenerate object URLs
    previewImageUrls.value.forEach(url => URL.revokeObjectURL(url));
    previewImageUrls.value = form.value.images.map(file => URL.createObjectURL(file));
  }
  // Clear the input so selecting the same file again triggers change event
  e.target.value = '';
};

const handleFileDrop = (e) => {
  if (e.dataTransfer.files.length > 0) {
    const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
    if (files.length > 0) {
      // Append instead of replace
      form.value.images = [...form.value.images, ...files];
      
      // Regenerate object URLs
      previewImageUrls.value.forEach(url => URL.revokeObjectURL(url));
      previewImageUrls.value = form.value.images.map(file => URL.createObjectURL(file));
    }
  }
};

const removeImage = (idx) => {
  form.value.images.splice(idx, 1);
  previewImageUrls.value.forEach(url => URL.revokeObjectURL(url));
  previewImageUrls.value = form.value.images.map(file => URL.createObjectURL(file));
  if(currentPreviewIndex.value >= previewImageUrls.value.length) {
    currentPreviewIndex.value = Math.max(0, previewImageUrls.value.length - 1);
  }
};

const prevImage = () => {
  if (currentPreviewIndex.value > 0) {
    currentPreviewIndex.value--;
  } else {
    currentPreviewIndex.value = previewImageUrls.value.length - 1;
  }
};

const nextImage = () => {
  if (currentPreviewIndex.value < previewImageUrls.value.length - 1) {
    currentPreviewIndex.value++;
  } else {
    currentPreviewIndex.value = 0;
  }
};

const openPreview = () => {
  if (!form.value.title) {
    Swal.fire({ icon: 'warning', title: 'Missing Title', text: 'Please enter a title before previewing.' });
    return;
  }
  showPreview.value = true;
};

const publishFromPreview = () => {
  showPreview.value = false;
  confirmPublish();
};

const fetchItems = async () => {
  loadingItems.value = true;
  try {
    const res = await api.get('news-iec');
    if (res.data && res.data.success) {
      items.value = res.data.data;
    }
  } catch (err) {
    console.error("Failed to fetch news/iec:", err);
  } finally {
    loadingItems.value = false;
  }
};

onMounted(() => {
  fetchItems();
});

const confirmPublish = () => {
  if (form.value.title.length > 255) {
    Swal.fire({
      icon: 'error',
      title: 'Title Limit Reached',
      text: `The title is ${form.value.title.length} characters long. The maximum allowed limit is 255 characters. Please decrease it.`
    });
    return;
  }

  Swal.fire({
    title: 'Are you sure?',
    text: `You are about to publish this ${form.value.category}. It will be visible to the public.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#9333ea',
    cancelButtonColor: '#475569',
    confirmButtonText: 'Yes, publish it!'
  }).then((result) => {
    if (result.isConfirmed) {
      submitPublish();
    }
  });
};

const submitPublish = async () => {
  loading.value = true;
  try {
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('category', form.value.category);
    formData.append('tags', tagsList.value.join(','));
    formData.append('description', form.value.description);
    if (form.value.images && form.value.images.length > 0) {
      form.value.images.forEach(file => {
        formData.append('images[]', file);
      });
    }

    const res = await api.post('news-iec', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    if (res.data && res.data.success) {
      const apiBaseUrl = api.defaults.baseURL.endsWith('/') ? api.defaults.baseURL : api.defaults.baseURL + '/';
      const shareLink = `${window.location.origin}/gad-corner/${res.data.id}`;
      Swal.fire({ 
        icon: 'success', 
        title: 'Published!', 
        html: `
          <p class="mb-4 text-sm text-slate-600">Your material has been published successfully.</p>
          <div class="text-left mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Share Link</div>
          <div class="bg-black/5 p-2 rounded-lg border border-slate-200 flex items-center justify-between gap-2 overflow-hidden shadow-inner">
            <span class="text-sm truncate font-mono text-slate-700 select-all">${shareLink}</span>
            <button id="copy-share-link" class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shrink-0 shadow-md">
              Copy Link
            </button>
          </div>
        `,
        confirmButtonColor: '#9333ea',
        didOpen: () => {
          const copyBtn = Swal.getPopup().querySelector('#copy-share-link');
          if (copyBtn) {
            copyBtn.addEventListener('click', () => {
              navigator.clipboard.writeText(shareLink).then(() => {
                copyBtn.innerText = 'Copied!';
                copyBtn.classList.remove('bg-purple-600', 'hover:bg-purple-500');
                copyBtn.classList.add('bg-emerald-500', 'hover:bg-emerald-400');
                setTimeout(() => {
                  copyBtn.innerText = 'Copy Link';
                  copyBtn.classList.remove('bg-emerald-500', 'hover:bg-emerald-400');
                  copyBtn.classList.add('bg-purple-600', 'hover:bg-purple-500');
                }, 2000);
              });
            });
          }
        }
      });
      form.value = { title: '', category: 'News', description: '', images: [] };
      tagsList.value = [];
      currentTagInput.value = '';
      previewImageUrls.value.forEach(url => URL.revokeObjectURL(url));
      previewImageUrls.value = [];
      document.querySelector('input[type="file"]').value = '';
      fetchItems();
    }
  } catch (err) {
    console.error('Publish error:', err);
    let msg = 'Failed to publish material.';
    if (err && err.messages) {
      msg = typeof err.messages === 'string' ? err.messages : Object.values(err.messages).join(', ');
    }
    Swal.fire({ icon: 'error', title: 'Error', text: msg });
  } finally {
    loading.value = false;
  }
};

const deleteItem = (id) => {
  Swal.fire({
    title: 'Delete this item?',
    text: "This action cannot be undone.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#ef4444', 
    cancelButtonColor: '#475569',
    confirmButtonText: 'Delete'
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        await api.delete(`news-iec/${id}`);
        Swal.fire('Deleted!', 'The item has been removed.', 'success');
        fetchItems();
      } catch (err) {
        Swal.fire('Error!', 'Failed to delete the item.', 'error');
      }
    }
  });
};
</script>

<style scoped>
.main-content {
  padding-left: 0;
  flex-grow: 1;
}

.content-wrapper {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.page-header {
  padding: 0 0.25rem;
}

/* ==========================================================================
   Page Header
   ========================================================================== */
.page-title {
  font-size: 1.75rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  background: linear-gradient(135deg, #7e22ce 0%, #9333ea 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

.page-subtitle {
  font-size: 1rem;
  color: #475569;
  margin-top: 0.25rem;
  transition: color 0.3s;
}

/* ==========================================================================
   Form Card Container
   ========================================================================== */
.form-container {
  padding: 2rem;
  border-radius: 1rem;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
  transition: all 0.3s ease;
}

.input-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.input-label {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-weight: 800;
  color: #475569;
  transition: color 0.3s;
}

/* ==========================================================================
   Inputs & Selects (Light Mode Default)
   ========================================================================== */
.custom-input {
  width: 100%;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  font-size: 1rem;
  font-weight: 500;
  color: #0f172a;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.04);
  transition: all 0.3s;
}

.custom-input:focus {
  outline: none;
  border-color: #9333ea;
  box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.15);
}

.custom-input::placeholder {
  color: #94a3b8;
}

.select-wrapper {
  position: relative;
  width: 100%;
}

.custom-select {
  width: 100%;
  padding: 0.75rem 2.25rem 0.75rem 1rem;
  border-radius: 0.75rem;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  font-size: 1rem;
  font-weight: 500;
  color: #0f172a;
  appearance: none;
  cursor: pointer;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.04);
  transition: all 0.3s;
}

.custom-select:focus {
  outline: none;
  border-color: #9333ea;
  box-shadow: 0 0 0 3px rgba(147, 51, 234, 0.15);
}

.custom-select option {
  background-color: #ffffff;
  color: #0f172a;
}

.select-arrow {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: #7e22ce;
  font-size: 0.85rem;
  pointer-events: none;
  transition: color 0.3s;
}

/* ==========================================================================
   Description Field (Overlay & Caret)
   ========================================================================== */
.desc-overlay {
  color: #0f172a;
  border-color: transparent !important;
  background: transparent !important;
  box-shadow: none !important;
}

.desc-textarea {
  color: transparent !important;
  caret-color: #0f172a !important;
  background: transparent !important;
}

/* ==========================================================================
   Upload Dropzone
   ========================================================================== */
.upload-dropzone {
  border: 2px dashed #cbd5e1;
  border-radius: 0.75rem;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: #f8fafc;
  cursor: pointer;
  position: relative;
  transition: all 0.3s;
}

.upload-dropzone:hover {
  border-color: #9333ea;
  background: #faf5ff;
}

.upload-dropzone .dropzone-icon {
  font-size: 2.25rem;
  color: #9333ea;
  margin-bottom: 0.5rem;
  transition: color 0.3s;
}

.upload-dropzone .dropzone-text {
  color: #1e293b;
  font-weight: 700;
  text-align: center;
  font-size: 0.95rem;
  transition: color 0.3s;
}

.upload-dropzone .dropzone-subtext {
  color: #64748b;
  font-size: 0.75rem;
  margin-top: 0.25rem;
  text-align: center;
  transition: color 0.3s;
}

.preview-images-container {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  transition: all 0.3s;
}

.preview-image-item {
  border: 1px solid #cbd5e1;
}

/* ==========================================================================
   Tags
   ========================================================================== */
.tag-chip {
  background: #f3e8ff;
  color: #7e22ce;
  border: 1px solid #d8b4fe;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 700;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.2s;
}

.tag-remove-btn {
  color: #9333ea;
  background: transparent;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  line-height: 1;
  display: flex;
  align-items: center;
  transition: color 0.2s;
}

.tag-remove-btn:hover {
  color: #dc2626;
}

.tag-add-btn {
  position: absolute;
  right: 0.5rem;
  padding: 0.375rem 1rem;
  border-radius: 0.5rem;
  font-weight: 700;
  font-size: 0.875rem;
  background-color: #9333ea;
  color: #ffffff;
  border: none;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(147, 51, 234, 0.3);
  transition: all 0.2s;
}

.tag-add-btn:hover:not(:disabled) {
  background-color: #7e22ce;
}

.tag-add-btn:disabled {
  background-color: #e2e8f0;
  color: #94a3b8;
  box-shadow: none;
  cursor: not-allowed;
}

/* ==========================================================================
   Action Buttons
   ========================================================================== */
.preview-btn {
  background: #f1f5f9;
  border: 1px solid #cbd5e1;
  color: #334155;
  padding: 0.75rem 2rem;
  border-radius: 9999px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  transition: all 0.3s;
  cursor: pointer;
}

.preview-btn:hover {
  background: #e2e8f0;
  color: #0f172a;
  transform: translateY(-2px);
}

.publish-btn {
  background: linear-gradient(135deg, #9333ea, #7e22ce);
  border: 1px solid rgba(185, 121, 204, 0.5);
  color: white;
  padding: 0.75rem 2rem;
  border-radius: 9999px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 4px 14px rgba(147, 51, 234, 0.35);
  transition: all 0.3s;
  cursor: pointer;
}

.publish-btn:hover:not(:disabled) {
  opacity: 0.95;
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(147, 51, 234, 0.45);
}

/* ==========================================================================
   Modal Header & Backdrop
   ========================================================================== */
.preview-modal-backdrop {
  background: rgba(15, 23, 42, 0.65);
  backdrop-filter: blur(4px);
}

.preview-modal-title {
  color: #0f172a;
  transition: color 0.3s;
}

.preview-modal-close {
  color: #64748b;
}

.preview-modal-close:hover {
  color: #0f172a;
}

/* ==========================================================================
   Table Container & Table (Light Mode Default)
   ========================================================================== */
.table-container {
  border-radius: 1rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
  overflow: hidden;
  background: #ffffff;
  transition: all 0.3s;
}

.table-card-header {
  padding: 1.5rem;
  border-bottom: 1px solid #f1f5f9;
  transition: border-color 0.3s;
}

.table-card-title {
  color: #0f172a;
  transition: color 0.3s;
}

.table-wrapper {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  text-align: left;
  border-collapse: collapse;
}

.table-header-row {
  border-bottom: 1px solid #e2e8f0;
  background: #f8fafc;
  transition: all 0.3s;
}

.table-header-cell {
  padding: 1rem 1.5rem;
  font-size: 0.85rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: #7e22ce;
  transition: color 0.3s;
}

.table-body {
  display: table-row-group;
}

.empty-state {
  padding: 3rem 1.5rem;
  text-align: center;
  font-size: 1rem;
  color: #64748b;
  font-weight: 500;
  transition: color 0.3s;
}

.table-row {
  transition: all 0.2s;
  border-bottom: 1px solid #f1f5f9;
}

.table-row:hover {
  background: #f8fafc;
}

.table-cell {
  padding: 1rem 1.5rem;
}

.title-cell {
  font-weight: 600;
  color: #0f172a;
  transition: color 0.3s;
}

.date-cell {
  color: #64748b;
  font-size: 0.95rem;
  font-weight: 500;
  transition: color 0.3s;
}

.btn-delete-item {
  color: #ef4444;
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0.375rem;
  border-radius: 0.375rem;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.btn-delete-item:hover {
  color: #dc2626;
  background: #fee2e2;
}

.category-badge {
  padding: 0.25rem 0.625rem;
  border-radius: 0.5rem;
  font-size: 0.8rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  transition: all 0.3s;
}

.badge-news {
  background: #eff6ff;
  border: 1px solid #bfdbfe;
  color: #1d4ed8;
}

.badge-iec {
  background: #ecfdf5;
  border: 1px solid #a7f3d0;
  color: #047857;
}

.badge-announcement {
  background: #fff7ed;
  border: 1px solid #fed7aa;
  color: #c2410c;
}

/* ==========================================================================
   Dark Mode Overrides
   ========================================================================== */
:global(.dark) .page-title,
.dark .page-title {
  background: linear-gradient(135deg, #6b21a8 0%, #9333ea 100%);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

:global(.dark) .page-subtitle,
.dark .page-subtitle {
  color: #475569;
}

:global(.dark) .form-container,
.dark .form-container {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
  border-color: rgba(185, 121, 204, 0.15);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25);
}

:global(.dark) .input-label,
.dark .input-label {
  color: rgba(203, 213, 225, 0.7);
}

:global(.dark) .custom-input,
.dark .custom-input {
  background: rgba(0, 0, 0, 0.4);
  border-color: rgba(185, 121, 204, 0.2);
  color: #ffffff;
  box-shadow: none;
}

:global(.dark) .custom-input:focus,
.dark .custom-input:focus {
  border-color: rgba(185, 121, 204, 0.5);
  box-shadow: 0 0 0 3px rgba(185, 121, 204, 0.2);
}

:global(.dark) .custom-input::placeholder,
.dark .custom-input::placeholder {
  color: #94a3b8;
}

:global(.dark) .custom-select,
.dark .custom-select {
  background: rgba(0, 0, 0, 0.4);
  border-color: rgba(185, 121, 204, 0.2);
  color: #ffffff;
  box-shadow: none;
}

:global(.dark) .custom-select:focus,
.dark .custom-select:focus {
  border-color: rgba(185, 121, 204, 0.5);
  box-shadow: 0 0 0 3px rgba(185, 121, 204, 0.2);
}

:global(.dark) .custom-select option,
.dark .custom-select option {
  background-color: #1a1a2e;
  color: #ffffff;
}

:global(.dark) .select-arrow,
.dark .select-arrow {
  color: #b979cc;
}

:global(.dark) .desc-overlay,
.dark .desc-overlay {
  color: #ffffff;
  border-color: transparent !important;
  background: transparent !important;
  box-shadow: none !important;
}

:global(.dark) .desc-textarea,
.dark .desc-textarea {
  color: transparent !important;
  caret-color: #ffffff !important;
  background: transparent !important;
}

:global(.dark) .upload-dropzone,
.dark .upload-dropzone {
  border-color: rgba(255, 255, 255, 0.2);
  background: rgba(0, 0, 0, 0.2);
}

:global(.dark) .upload-dropzone:hover,
.dark .upload-dropzone:hover {
  border-color: rgba(168, 85, 247, 0.5);
  background: rgba(0, 0, 0, 0.3);
}

:global(.dark) .upload-dropzone .dropzone-icon,
.dark .upload-dropzone .dropzone-icon {
  color: #94a3b8;
}

:global(.dark) .upload-dropzone .dropzone-text,
.dark .upload-dropzone .dropzone-text {
  color: #cbd5e1;
}

:global(.dark) .upload-dropzone .dropzone-subtext,
.dark .upload-dropzone .dropzone-subtext {
  color: #64748b;
}

:global(.dark) .preview-images-container,
.dark .preview-images-container {
  background: rgba(0, 0, 0, 0.2);
  border-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .preview-image-item,
.dark .preview-image-item {
  border-color: rgba(255, 255, 255, 0.2);
}

:global(.dark) .tag-chip,
.dark .tag-chip {
  background: rgba(88, 28, 135, 0.4);
  color: #e9d5ff;
  border-color: rgba(168, 85, 247, 0.3);
}

:global(.dark) .tag-remove-btn,
.dark .tag-remove-btn {
  color: #e9d5ff;
}

:global(.dark) .tag-remove-btn:hover,
.dark .tag-remove-btn:hover {
  color: #f87171;
}

:global(.dark) .tag-add-btn:disabled,
.dark .tag-add-btn:disabled {
  background-color: rgba(255, 255, 255, 0.1);
  color: #64748b;
}

:global(.dark) .preview-btn,
.dark .preview-btn {
  background: linear-gradient(135deg, #1e293b, #0f172a);
  border-color: rgba(185, 121, 204, 0.3);
  color: #ffffff;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
}

:global(.dark) .preview-btn:hover,
.dark .preview-btn:hover {
  background: linear-gradient(135deg, #334155, #1e293b);
}

:global(.dark) .preview-modal-backdrop,
.dark .preview-modal-backdrop {
  background: rgba(0, 0, 0, 0.8);
}

:global(.dark) .preview-modal-title,
.dark .preview-modal-title {
  color: #ffffff;
}

:global(.dark) .preview-modal-close,
.dark .preview-modal-close {
  color: #94a3b8;
}

:global(.dark) .preview-modal-close:hover,
.dark .preview-modal-close:hover {
  color: #ffffff;
}

:global(.dark) .table-container,
.dark .table-container {
  border-color: rgba(185, 121, 204, 0.15);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25);
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
}

:global(.dark) .table-card-header,
.dark .table-card-header {
  border-bottom-color: rgba(255, 255, 255, 0.1);
}

:global(.dark) .table-card-title,
.dark .table-card-title {
  color: #ffffff;
}

:global(.dark) .table-header-row,
.dark .table-header-row {
  border-bottom-color: rgba(185, 121, 204, 0.1);
  background: rgba(0, 0, 0, 0.3);
}

:global(.dark) .table-header-cell,
.dark .table-header-cell {
  color: #b979cc;
}

:global(.dark) .table-row,
.dark .table-row {
  border-bottom-color: rgba(185, 121, 204, 0.05);
}

:global(.dark) .table-row:hover,
.dark .table-row:hover {
  background: rgba(255, 255, 255, 0.05);
}

:global(.dark) .title-cell,
.dark .title-cell {
  color: #e2e8f0;
}

:global(.dark) .date-cell,
.dark .date-cell {
  color: #94a3b8;
}

:global(.dark) .btn-delete-item,
.dark .btn-delete-item {
  color: #f87171;
}

:global(.dark) .btn-delete-item:hover,
.dark .btn-delete-item:hover {
  color: #ef4444;
  background: rgba(239, 68, 68, 0.15);
}

:global(.dark) .empty-state,
.dark .empty-state {
  color: #94a3b8;
}

:global(.dark) .badge-news,
.dark .badge-news {
  background: rgba(59, 130, 246, 0.15);
  border-color: rgba(59, 130, 246, 0.4);
  color: #93c5fd;
}

:global(.dark) .badge-iec,
.dark .badge-iec {
  background: rgba(16, 185, 129, 0.15);
  border-color: rgba(16, 185, 129, 0.4);
  color: #6ee7b7;
}

:global(.dark) .badge-announcement,
.dark .badge-announcement {
  background: rgba(249, 115, 22, 0.15);
  border-color: rgba(249, 115, 22, 0.4);
  color: #fdba74;
}
</style>
