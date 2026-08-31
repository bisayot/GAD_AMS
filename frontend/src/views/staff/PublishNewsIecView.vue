<template>
  <main class="main-content">
    <div class="content-wrapper">
      
      <div class="page-header">
        <h1 class="page-title">Publish News & IEC</h1>
        <p class="page-subtitle">Publish new News updates or IEC materials for the public GAD Corner.</p>
      </div>

      <div class="form-container">
        <form @submit.prevent="confirmPublish" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="input-group md:col-span-2">
              <label class="input-label">Title <span class="text-red-400">*</span></label>
              <textarea v-model="form.title" class="custom-input resize-none overflow-hidden" rows="1" placeholder="Enter title" required @input="autoResize" style="min-height: 48px;"></textarea>
            </div>

            <div class="input-group md:col-span-2">
              <label class="input-label">Category <span class="text-red-400">*</span></label>
              <div class="select-wrapper">
                <select v-model="form.category" class="custom-select" required>
                  <option value="News">News</option>
                  <option value="IEC">IEC Material</option>
                </select>
                <span class="select-arrow">▼</span>
              </div>
            </div>

            <div class="input-group md:col-span-2">
              <label class="input-label">Description (Optional)</label>
              
              <div class="relative w-full">
                <!-- Highlighted Text Overlay -->
                <div class="absolute inset-0 custom-input pointer-events-none whitespace-pre-wrap break-words overflow-hidden" style="color: white; border-color: transparent; background: transparent;" v-html="highlightedDescription"></div>
                <!-- Actual Textarea -->
                <textarea 
                  v-model="form.description" 
                  rows="4" 
                  class="custom-input relative z-10 w-full bg-transparent resize-none overflow-hidden" 
                  style="color: transparent; caret-color: white;"
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
                class="border-2 border-dashed border-white/20 rounded-xl p-8 flex flex-col items-center justify-center hover:border-purple-500/50 transition-colors cursor-pointer bg-black/20 relative"
                @dragover.prevent
                @drop.prevent="handleFileDrop"
                @click="$refs.fileInput.click()"
              >
                <input ref="fileInput" @change="handleFileChange" type="file" multiple accept="image/*" class="hidden" />
                <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">cloud_upload</span>
                <p class="text-slate-300 font-bold text-center">Click or drag and drop images here</p>
                <p class="text-xs text-slate-500 mt-1 text-center">Supports JPG, PNG, WEBP</p>
              </div>
              
              <!-- Small Previews inside the form -->
              <div v-if="previewImageUrls.length > 0" class="flex flex-wrap gap-4 mt-2 p-4 bg-black/20 rounded-xl border border-white/10">
                <div v-for="(url, idx) in previewImageUrls" :key="idx" class="relative w-20 h-20 rounded-lg overflow-hidden border border-white/20 group shadow-lg">
                  <img :src="url" class="object-cover w-full h-full" />
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
              <div class="flex gap-2 mb-2 flex-wrap">
                <span v-for="(tag, index) in tagsList" :key="index" class="bg-purple-900/40 text-purple-200 border border-purple-500/30 px-3 py-1 rounded-full text-xs font-bold flex items-center gap-2">
                  #{{ tag }}
                  <button @click.prevent="removeTag(index)" class="hover:text-red-400">&times;</button>
                </span>
              </div>
              <div class="relative flex items-center w-full">
                <input v-model="currentTagInput" @keydown.enter.prevent="addTag" type="text" class="custom-input w-full pr-24" placeholder="Add a tag..." />
                <button 
                  @click.prevent="addTag" 
                  :disabled="!currentTagInput.trim()" 
                  class="absolute right-2 px-4 py-1.5 rounded-lg font-bold text-sm shadow-md"
                  :style="currentTagInput.trim() ? 'background-color: #9333ea !important; color: white !important;' : 'background-color: #475569 !important; color: white !important; opacity: 0.5;'"
                >Add</button>
              </div>
            </div>
          </div>

          <div class="pt-6 flex justify-end gap-4">
            <button type="button" @click="openPreview" class="preview-btn">
              <span class="material-symbols-outlined text-sm">visibility</span>
              Preview
            </button>
            <button type="submit" :disabled="loading" class="publish-btn">
              <span class="material-symbols-outlined text-sm">publish</span>
              {{ loading ? 'Publishing...' : 'Publish' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Live Preview Modal -->
      <div v-if="showPreview" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" style="margin-left: 0;">
        <div class="form-container max-w-4xl w-full shadow-2xl relative max-h-[90vh] overflow-y-auto" style="padding: 2rem;">
          <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-headline font-bold text-white">Live Preview</h3>
            <button @click="showPreview = false" class="text-slate-400 hover:text-white transition-colors">
              <span class="material-symbols-outlined">close</span>
            </button>
          </div>
          
          <!-- Full Post Preview -->
          <div class="bg-[#16213e] rounded-2xl border border-white/10 shadow-2xl overflow-hidden mb-8">
            <!-- Header: Title and Date -->
            <div class="p-8 md:p-12 pb-8 relative">
              <div class="absolute top-8 right-8 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest backdrop-blur-md z-20"
                   :class="form.category === 'News' ? 'bg-blue-900/50 text-blue-200 border border-blue-500/30' : 'bg-emerald-900/50 text-emerald-200 border border-emerald-500/30'">
                {{ form.category }}
              </div>
              <h1 class="text-3xl md:text-5xl font-headline font-extrabold text-white mb-6 leading-tight pr-24">{{ form.title || 'Untitled Material' }}</h1>
              
              <div class="flex items-center gap-4 text-sm text-slate-400">
                <span class="flex items-center gap-1 font-label">
                  <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                  {{ new Date().toLocaleDateString() }}
                </span>
              </div>
            </div>

            <!-- Image Carousel -->
            <div class="relative h-64 sm:h-96 md:h-[500px] w-full bg-black/50 border-y border-white/10">
              <img v-if="previewImageUrls.length > 0" :src="previewImageUrls[currentPreviewIndex]" class="object-contain w-full h-full transition-all duration-300" />
              <div v-else class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#1a1a2e] to-[#16213e]">
                <span class="material-symbols-outlined text-6xl text-white/20">newspaper</span>
              </div>
              
              <button v-if="previewImageUrls.length > 0" @click.prevent="removeImage(currentPreviewIndex)" class="absolute top-4 left-4 bg-red-600/80 text-white p-2 rounded-full hover:bg-red-500 transition-colors backdrop-blur-sm shadow-md z-30 flex items-center justify-center">
                <span class="material-symbols-outlined">delete</span>
              </button>
              
              <!-- Carousel Arrows -->
              <div v-if="previewImageUrls.length > 1" class="absolute inset-0 flex items-center justify-between px-4">
                <button @click.prevent="prevImage" class="bg-black/50 text-white p-2 rounded-full hover:bg-black/80 transition-colors backdrop-blur-sm shadow-md">
                  <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button @click.prevent="nextImage" class="bg-black/50 text-white p-2 rounded-full hover:bg-black/80 transition-colors backdrop-blur-sm shadow-md">
                  <span class="material-symbols-outlined">chevron_right</span>
                </button>
              </div>
              <!-- Dots indicator -->
              <div v-if="previewImageUrls.length > 1" class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                <div v-for="(_, idx) in previewImageUrls" :key="idx" 
                     class="w-2 h-2 rounded-full transition-colors shadow-sm cursor-pointer"
                     :class="idx === currentPreviewIndex ? 'bg-white' : 'bg-white/40'"
                     @click="currentPreviewIndex = idx">
                </div>
              </div>
            </div>

            <!-- Body: Description and Tags -->
            <div class="p-8 md:p-12 pt-8">
              <div class="text-slate-200 leading-relaxed whitespace-pre-wrap text-lg md:text-xl font-light" v-html="linkify(form.description) || '<span class=\'text-slate-500\'>No description provided.</span>'"></div>

              <!-- Tags -->
              <div v-if="tagsList.length > 0" class="flex flex-wrap gap-2 mt-10 pt-6 border-t border-white/10">
                <span v-for="tag in tagsList" :key="tag" class="text-xs font-label font-bold text-purple-400 bg-purple-900/20 px-3 py-1.5 rounded-full border border-purple-500/20">
                  #{{ tag }}
                </span>
              </div>
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
        <div class="p-6 border-b border-white/10">
          <h2 class="text-xl font-headline font-bold text-white">Published Items</h2>
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
                  <span class="category-badge">
                    {{ item.category }}
                  </span>
                </td>
                <td class="table-cell title-cell">{{ item.title }}</td>
                <td class="table-cell date-cell">{{ new Date(item.created_at).toLocaleDateString() }}</td>
                <td class="table-cell text-right">
                  <button @click="deleteItem(item.id)" class="transition-colors" title="Delete">
                    <span class="material-symbols-outlined text-sm" style="color: white !important;">delete</span>
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
    return '<span class="text-slate-500">Enter description...</span>';
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
  return text.replace(urlRegex, (url) => `<span class="text-purple-400 underline">${url}</span>`);
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

.page-title {
  font-size: 1.5rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  color: #16213e;
}

.page-subtitle {
  font-size: 1rem;
  color: #475569;
  margin-top: 0.25rem;
}

.form-container {
  padding: 2rem;
  border-radius: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25);
  backdrop-filter: blur(8px);
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
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
  color: rgba(203, 213, 225, 0.7);
}

.custom-input {
  width: 100%;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  background: rgba(0, 0, 0, 0.4);
  border: 1px solid rgba(185, 121, 204, 0.2);
  font-size: 1rem;
  font-weight: 600;
  color: white;
  transition: all 0.3s;
}

.custom-input:focus {
  outline: none;
  border-color: rgba(185, 121, 204, 0.5);
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
  padding: 0.75rem 2rem 0.75rem 1rem;
  border-radius: 0.75rem;
  background: rgba(0, 0, 0, 0.4);
  border: 1px solid rgba(185, 121, 204, 0.2);
  font-size: 1rem;
  font-weight: 600;
  color: white;
  appearance: none;
  cursor: pointer;
  transition: all 0.3s;
}

.custom-select:focus {
  outline: none;
  border-color: rgba(185, 121, 204, 0.5);
}

.custom-select option {
  background-color: #1a1a2e;
  color: #ffffff;
}

.select-arrow {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: #b979cc;
  font-size: 0.85rem;
  pointer-events: none;
}

.custom-file-input {
  width: 100%;
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  background: rgba(0, 0, 0, 0.4);
  border: 1px solid rgba(185, 121, 204, 0.2);
  color: #94a3b8;
  transition: all 0.3s;
}

.publish-btn {
  background: linear-gradient(135deg, #9333ea, #6b21a8);
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
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
  transition: all 0.3s;
  cursor: pointer;
}

.publish-btn:hover:not(:disabled) {
  opacity: 0.9;
  transform: translateY(-2px);
}

.preview-btn {
  background: linear-gradient(135deg, #1e293b, #0f172a);
  border: 1px solid rgba(185, 121, 204, 0.3);
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
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
  transition: all 0.3s;
  cursor: pointer;
}

.preview-btn:hover {
  background: linear-gradient(135deg, #334155, #1e293b);
  transform: translateY(-2px);
}

.table-container {
  border-radius: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25);
  overflow: hidden;
  backdrop-filter: blur(8px);
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
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
  border-bottom: 1px solid rgba(185, 121, 204, 0.1);
  background: rgba(0, 0, 0, 0.3);
}

.table-header-cell {
  padding: 1rem 1.5rem;
  font-size: 0.85rem;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: #b979cc;
}

.table-body {
  display: table-row-group;
}

.empty-state {
  padding: 3rem 1.5rem;
  text-align: center;
  font-size: 1rem;
  color: #94a3b8;
  font-weight: 500;
}

.table-row {
  transition: all 0.3s;
  border-bottom: 1px solid rgba(185, 121, 204, 0.05);
}

.table-row:hover {
  background: rgba(255, 255, 255, 0.05);
}

.table-cell {
  padding: 1rem 1.5rem;
}

.title-cell {
  font-weight: 600;
  color: #e2e8f0;
}

.date-cell {
  color: #94a3b8;
  font-family: monospace;
  font-size: 1rem;
}

.category-badge {
  padding: 0.25rem 0.625rem;
  border-radius: 0.5rem;
  font-size: 0.8rem;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: #cbd5e1;
}
</style>
