import { ref } from 'vue';
import Swal from 'sweetalert2';
import api from '../api';

const TOAST_THROTTLE_MS = 15000;

/**
 * Server-backed autosave for the Activity Design submission form.
 * Persists a JSON snapshot per user (ad_drafts table) with a debounce,
 * restores it on revisit, and never interrupts the user on failure.
 */
export function useAdDraft({ userId, getData, setData, hasInput, debounceMs = 1500 }) {
  const isRestoring = ref(false);
  let saveTimer = null;
  let suppressUntil = 0;
  let lastToastAt = 0;

  const showSavedToast = () => {
    const now = Date.now();
    if (now - lastToastAt < TOAST_THROTTLE_MS) return;
    lastToastAt = now;
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'success',
      title: 'Draft autosaved',
      showConfirmButton: false,
      timer: 2000,
      timerProgressBar: true,
    });
  };

  const saveDraft = async () => {
    if (!userId || isRestoring.value || Date.now() < suppressUntil) return;
    const data = getData();
    if (typeof hasInput === 'function' && !hasInput(data)) return;
    try {
      await api.post('save-ad-draft', { user_id: userId, payload: JSON.stringify(data) });
      showSavedToast();
    } catch (e) {
      // Autosave must never interrupt the user.
    }
  };

  const scheduleSave = () => {
    if (!userId || isRestoring.value || Date.now() < suppressUntil) return;
    clearTimeout(saveTimer);
    saveTimer = setTimeout(saveDraft, debounceMs);
  };

  const cancelPendingSave = () => clearTimeout(saveTimer);

  const restoreDraft = async () => {
    cancelPendingSave();
    if (!userId) return false;

    let draft = null;
    try {
      const res = await api.get(`get-ad-draft/${userId}`);
      draft = res.data?.draft || null;
    } catch (e) {
      return false;
    }
    if (!draft || !draft.payload) return false;

    let parsed;
    try {
      parsed = JSON.parse(draft.payload);
    } catch (e) {
      return false;
    }

    const savedAt = draft.updated_at ? new Date(String(draft.updated_at).replace(' ', 'T')) : null;
    const timeStr = savedAt && !isNaN(savedAt.getTime()) ? savedAt.toLocaleString() : 'an earlier session';

    const result = await Swal.fire({
      icon: 'question',
      title: 'Restore unsaved draft?',
      html: `You have an unsaved Activity Design draft from <b>${timeStr}</b>.<br><br><small>Note: the uploaded Activity Design file (PDF) is not autosaved and must be re-attached.</small>`,
      showCancelButton: true,
      confirmButtonText: 'Restore draft',
      cancelButtonText: 'Discard',
      confirmButtonColor: '#b979cc',
      cancelButtonColor: '#64748b',
      reverseButtons: true,
    });

    if (!result.isConfirmed) {
      try { await api.delete(`delete-ad-draft/${userId}`); } catch (e) {}
      return false;
    }

    isRestoring.value = true;
    suppressUntil = Date.now() + 1000;
    setData(parsed);
    setTimeout(() => { isRestoring.value = false; }, 800);
    return true;
  };

  const clearDraft = async () => {
    cancelPendingSave();
    suppressUntil = Date.now() + 1000;
    if (!userId) return;
    try { await api.delete(`delete-ad-draft/${userId}`); } catch (e) {}
  };

  return { isRestoring, saveDraft, scheduleSave, cancelPendingSave, restoreDraft, clearDraft };
}
