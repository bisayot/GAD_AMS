<template>
  <div class="budget-section">
    <label class="form-label" v-if="label">{{ label }}</label>
    <datalist id="bl-units">
      <option v-for="u in unitSuggestions" :key="u" :value="u"></option>
    </datalist>
    
    <div v-for="vId in (venues && venues.length ? venues : [])" :key="vId" class="venue-budget-wrapper">
      <h4 class="venue-budget-title">Budget for Venue: {{ getVenueName(vId) }}</h4>
      <div v-if="venueInsideMap !== null" class="venue-rate-status-container">
        <span :class="venueIsOutside(vId) ? 'venue-rate-outside' : 'venue-rate-inside'">
          {{ venueIsOutside(vId) ? '🏙️ Outside BSU — using outside rates' : '🏫 Inside BSU — using inside rates' }}
        </span>
      </div>
      <div class="budget-groups-container">
        <div v-for="g in budgetGroups" :key="g.key" class="budget-group-card">
          <div class="budget-group-header" style="justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
              <span class="budget-group-icon">{{ g.icon }}</span>
              <span class="budget-group-title">{{ g.title }}</span>
            </div>
            <div class="budget-group-total">{{ peso(groupTotal(vId, g.key)) }}</div>
          </div>
          <div class="budget-group-content">
            <div v-for="item in groupItems(vId, g.key)" :key="item.id" class="budget-row-item" style="align-items: flex-start;">
              <div class="budget-item-info">
                <input v-if="item.custom" type="text" v-model="item.name" class="others-input-name" placeholder="Item name (e.g. Coffee)" />
                <div v-else class="budget-item-title">{{ item.name }}</div>
                <span v-if="item.hint" class="budget-item-subtext">({{ item.hint }})</span>
                <div class="bl-ctl">
                  <span class="budget-currency-symbol">₱</span>
                  <input type="number" min="0" step="0.01" v-model.number="item.rate" class="bl-rate" :placeholder="item.mult.length ? 'Rate' : 'Amount'" />
                  <template v-for="(m, mi) in item.mult" :key="mi">
                    <span class="bl-x">×</span>
                    <span class="bl-mult">
                      <input type="number" min="0" step="any" v-model.number="m.q" class="bl-q" />
                      <input type="text" list="bl-units" v-model="m.u" class="bl-u" placeholder="unit" />
                      <button type="button" class="bl-rm" title="Remove multiplier" @click="item.mult.splice(mi, 1)">✕</button>
                    </span>
                  </template>
                  <button type="button" class="btn-add-other" @click="item.mult.push({ q: 1, u: '' })">+ multiplier</button>
                </div>
                <div v-if="item.baseKey" class="bl-note">Baseline rate {{ peso(item.base) }}<template v-if="Number(item.rate) !== Number(item.base)"> · <button type="button" class="bl-link" @click="item.rate = item.base">reset</button></template></div>
                <div v-if="item.capKey && lineTotal(item) > Number(baselineSettings[item.capKey])" class="budget-error-inline">Exceeds the {{ peso(baselineSettings[item.capKey]) }} limit for this item.</div>
              </div>
              <div class="budget-item-value" style="width: 150px; flex-direction: column; align-items: flex-end;">
                <span class="others-total-badge">{{ peso(lineTotal(item)) }}</span>
                <div class="bl-acts">
                  <button type="button" class="bl-clear" title="Remove multipliers and set this line to ₱0.00" @click="clearLine(item)">Clear</button>
                  <button v-if="item.custom" type="button" class="btn-remove-other" style="font-size: 11px;" @click="removeLine(vId, item)">Remove</button>
                </div>
              </div>
            </div>
            <button v-if="g.addLabel" type="button" class="btn-add-other" style="width: 100%; justify-content: center;" @click="addLine(vId, g.key)"><span>+</span> Add {{ g.addLabel }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, watch, ref, onMounted } from 'vue';

const props = defineProps({
  label: { type: String, default: 'Proposed Budgetary Requirements *' },
  venues: { type: Array, required: true },
  venueBudgets: { type: Object, required: true }, // The main state model passed from parent
  baselineSettings: { type: Object, required: true },
  isOutsideBsu: { type: Boolean, required: true },
  computedDays: { type: Number, required: true },
  filteredVenues: { type: Array, default: () => [] },
  customVenuesList: { type: Array, default: () => [] },
  // Per-venue inside/outside map: { [venueId]: true (inside) | false (outside) }
  // When provided (mixed mode), overrides isOutsideBsu per venue
  venueInsideMap: { type: Object, default: null }
});

const emit = defineEmits(['update:venueBudgets']);

const unitSuggestions = ['pax', 'day', 'hr', 'night', 'pc', 'set', 'trip', 'speaker', 'snack', 'meal'];
const budgetGroups = [
  { key: 'catering', icon: '🍽️', title: 'Catering & Hospitality (Meals/Snacks)', addLabel: 'meal/snack' },
  { key: 'logistics', icon: '🏨', title: 'Venue & Logistics' },
  { key: 'program', icon: '🎓', title: 'Program & Speakers' },
  { key: 'materials', icon: '📦', title: 'Materials & Miscellaneous', addLabel: 'item' }
];

let lineSeq = 1;
const peso = n => '₱' + (Number(n) || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
const lineTotal = it => (Number(it.rate) || 0) * it.mult.reduce((p, m) => p * (Number(m.q) || 0), 1);
const paxOf = it => Number((it.mult.find(m => /^(pax|person|persons|head|heads)$/i.test(String(m.u || '').trim())) || {}).q) || 0;
const lineFormula = it => [peso(it.rate), ...it.mult.map(m => `${Number(m.q) || 0} ${String(m.u || '').trim()}`.trim())].join(' × ');

// Determine if a specific venue is outside BSU
const venueIsOutside = (vId) => {
  if (props.venueInsideMap !== null && props.venueInsideMap !== undefined) {
    // Mixed mode: use per-venue map. true = inside, false = outside
    return !(props.venueInsideMap[String(vId)] ?? true);
  }
  return props.isOutsideBsu;
};

const baseFor = (l, vId) => {
  const b = props.baselineSettings;
  const out = vId !== undefined ? venueIsOutside(vId) : props.isOutsideBsu;
  if (!b) return 0;
  if (l.baseKey === 'meals') return out ? b.meals_outside : b.meals_inside;
  if (l.baseKey === 'snacks') return out ? b.snacks_outside : b.snacks_inside;
  return b[l.baseKey] || 0;
};

const bl = (group, name, mult = [], extra = {}, vId = undefined) => {
  const l = { id: lineSeq++, group, name, rate: '', mult, ...extra };
  if (l.baseKey) { 
    l.base = baseFor(l, vId); 
    l.rate = l.base; 
  }
  return l;
};

const cateringMult = () => [{ q: 0, u: 'pax' }, { q: props.computedDays, u: 'days' }];

const defaultBudgetLines = (vId = undefined) => [
  bl('catering', 'Breakfast', cateringMult(), { baseKey: 'meals', custom: true }, vId),
  bl('catering', 'Lunch', cateringMult(), { baseKey: 'meals', custom: true }, vId),
  bl('catering', 'Dinner', cateringMult(), { baseKey: 'meals', custom: true }, vId),
  bl('catering', 'AM Snack', cateringMult(), { baseKey: 'snacks', custom: true }, vId),
  bl('catering', 'PM Snack', cateringMult(), { baseKey: 'snacks', custom: true }, vId),
  bl('logistics', 'Function Room/Venue', [], { hint: 'Leave blank/zero for Attribution' }),
  bl('logistics', 'Accommodation'),
  bl('logistics', 'Equipment Rental'),
  bl('logistics', 'Transportation', [], { capKey: 'transportation_limit' }),
  bl('program', 'Professional Fee/Honoraria', [{ q: 0, u: 'speakers' }], { baseKey: 'pf_honoraria' }),
  bl('program', 'Token/s', [{ q: 0, u: 'recipients' }], { baseKey: 'tokens' }),
  bl('materials', 'Materials and Supplies')
];

const groupItems = (vId, g) => (props.venueBudgets[vId] || []).filter(i => i.group === g);
const groupTotal = (vId, g) => groupItems(vId, g).reduce((s, i) => s + lineTotal(i), 0);
const addLine = (vId, g) => {
  if (!props.venueBudgets[vId]) return;
  props.venueBudgets[vId].push(bl(g, '', g === 'catering' ? cateringMult() : [], { custom: true }, vId));
};
const removeLine = (vId, item) => {
  if (!props.venueBudgets[vId]) return;
  const arr = props.venueBudgets[vId];
  arr.splice(arr.indexOf(item), 1);
};
const clearLine = item => { item.mult = []; item.rate = ''; };

const allLines = () => Object.values(props.venueBudgets).flat();
const allLinesWithVenue = () => {
  const result = [];
  Object.entries(props.venueBudgets).forEach(([vId, lines]) => {
    (lines || []).forEach(line => result.push({ line, vId }));
  });
  return result;
};

watch(() => props.venues, (newVenues) => {
  if (!newVenues) return;
  let changed = false;
  newVenues.forEach(vid => {
    if (!Array.isArray(props.venueBudgets[vid]) || props.venueBudgets[vid].length === 0) {
      props.venueBudgets[vid] = defaultBudgetLines(vid);
      changed = true;
    }
  });
  if (changed) {
    emit('update:venueBudgets', props.venueBudgets);
  }
}, { deep: true, immediate: true });

// Untouched rates follow the baseline (and the Inside/Outside BSU switch); edited rates are left alone
watch([() => props.isOutsideBsu, () => props.baselineSettings, () => props.venueInsideMap], () => {
  allLinesWithVenue().forEach(({ line: l, vId }) => {
    if (!l.baseKey) return;
    const nb = baseFor(l, vId);
    if (Number(l.rate) === Number(l.base)) l.rate = nb;
    l.base = nb;
  });
}, { deep: true });

// The schedule count can change after the venue budget is initialized.
// Keep the automatically supplied catering day multiplier in sync so
// non-consecutive schedules are included in the proposed total.
watch(() => props.computedDays, (days) => {
  allLines().forEach(line => {
    if (line.group !== 'catering' || !Array.isArray(line.mult)) return;
    line.mult.forEach(multiplier => {
      if (String(multiplier.u || '').trim().toLowerCase() === 'days') {
        multiplier.q = days;
      }
    });
  });
}, { immediate: true });

// Expose these helpers if parents need to compute totals
const getGrandTotal = () => {
  let grandTotal = 0;
  Object.values(props.venueBudgets).forEach(items => {
    grandTotal += items.reduce((sum, i) => sum + lineTotal(i), 0);
  });
  return grandTotal;
};

const getMaxOverallPax = () => {
  let maxOverallPax = 0;
  Object.values(props.venueBudgets).forEach(items => {
    maxOverallPax += Math.max(0, ...items.filter(i => i.group === 'catering').map(paxOf));
  });
  return maxOverallPax;
};

defineExpose({
  getGrandTotal,
  getMaxOverallPax,
  lineTotal,
  paxOf,
  lineFormula,
  defaultBudgetLines,
  bl
});

const getVenueName = (id) => {
  if (String(id).startsWith('temp_')) {
    const custom = props.customVenuesList.find(x => String(x.venue_id) === String(id));
    return custom ? custom.venue_name : 'Custom Venue';
  }
  const v = props.filteredVenues.find(x => String(x.venue_id) === String(id));
  return v ? v.venue_name : 'Unknown Venue';
};
</script>

<style scoped>
/* Reusing the CSS from the main form */
.form-label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #7e22ce;
}

.venue-budget-wrapper {
  margin-bottom: 2rem;
  border-radius: 14px;
  padding: 1.25rem;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  transition: all 0.3s ease;
}

.venue-budget-title {
  color: #7e22ce;
  margin-bottom: 8px;
  border-left: 4px solid #7e22ce;
  padding-left: 10px;
  font-weight: 700;
  font-size: 15px;
}

.venue-rate-status-container {
  margin-bottom: 12px;
  padding-left: 14px;
}

.venue-rate-outside {
  color: #be185d;
  background: #fdf2f8;
  border: 1px solid #fbcfe8;
  padding: 3px 8px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
}

.venue-rate-inside {
  color: #15803d;
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  padding: 3px 8px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
}

.budget-groups-container {
  display: flex;
  flex-direction: column;
  gap: 16px;
  overflow-x: auto;
  padding-bottom: 8px;
}

.budget-group-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  overflow: hidden;
  min-width: 600px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.budget-group-header {
  background: #f1f5f9;
  padding: 12px 16px;
  display: flex;
  align-items: center;
  border-bottom: 1px solid #e2e8f0;
}

.budget-group-icon {
  font-size: 20px;
}

.budget-group-title {
  color: #0f172a;
  font-weight: 700;
  font-size: 14px;
}

.budget-group-total {
  color: #7e22ce;
  font-weight: 700;
  font-size: 15px;
  background: #f3e8ff;
  padding: 4px 10px;
  border-radius: 6px;
}

.budget-group-content {
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.budget-row-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  transition: all 0.2s ease;
}

.budget-item-title {
  color: #1e293b;
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 6px;
}

.others-input-name {
  background: #ffffff;
  border: 1px solid #cbd5e1;
  color: #0f172a;
  padding: 6px 10px;
  border-radius: 6px;
  font-size: 13px;
  width: 100%;
  max-width: 200px;
  margin-bottom: 6px;
}

.others-input-name:focus {
  outline: none;
  border-color: #7e22ce;
  box-shadow: 0 0 0 2px rgba(126, 34, 206, 0.15);
}

.budget-item-subtext {
  color: #64748b;
  font-size: 12px;
  margin-left: 6px;
}

.bl-ctl {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 4px;
}

.budget-currency-symbol {
  color: #64748b;
  font-weight: 600;
}

.bl-rate, .bl-q {
  appearance: auto;
  -webkit-appearance: auto;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  color: #0f172a;
  padding: 6px 8px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
}

.bl-rate { width: 90px; }
.bl-q { width: 60px; }

.bl-rate:focus, .bl-q:focus, .bl-u:focus {
  outline: none;
  border-color: #7e22ce;
  box-shadow: 0 0 0 2px rgba(126, 34, 206, 0.15);
}

.bl-x {
  color: #64748b;
  font-size: 14px;
  font-weight: 600;
}

.bl-mult {
  display: flex;
  align-items: center;
  background: #ffffff;
  border-radius: 6px;
  border: 1px solid #cbd5e1;
  padding: 2px;
  gap: 4px;
}

.bl-u {
  background: transparent;
  border: none;
  color: #6b21a8;
  width: 60px;
  font-size: 13px;
  font-weight: 500;
  padding: 2px 4px;
}

.bl-rm {
  background: none;
  border: none;
  color: #ef4444;
  cursor: pointer;
  padding: 0 4px;
  font-size: 12px;
}

.bl-rm:hover {
  color: #dc2626;
}

.btn-add-other {
  background: #faf5ff;
  color: #7e22ce;
  border: 1px dashed #d8b4fe;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 4px;
  transition: all 0.2s;
}

.btn-add-other:hover {
  background: #f3e8ff;
  border-color: #7e22ce;
}

.bl-note {
  font-size: 11px;
  color: #64748b;
  margin-top: 4px;
}

.bl-link {
  background: none;
  border: none;
  color: #7e22ce;
  cursor: pointer;
  padding: 0;
  text-decoration: underline;
  font-size: 11px;
  font-weight: 600;
}

.budget-error-inline {
  color: #ef4444;
  font-size: 11px;
  margin-top: 4px;
}

.others-total-badge {
  background: #f3e8ff;
  padding: 4px 12px;
  border-radius: 6px;
  color: #7e22ce;
  font-weight: 700;
  font-size: 14px;
  border: 1px solid #e9d5ff;
}

.bl-acts {
  display: flex;
  gap: 8px;
  margin-top: 8px;
}

.bl-clear, .btn-remove-other {
  background: none;
  border: none;
  color: #64748b;
  cursor: pointer;
  font-size: 11px;
  text-decoration: underline;
}

.bl-clear:hover {
  color: #0f172a;
}

.btn-remove-other {
  color: #ef4444;
}

.btn-remove-other:hover {
  color: #dc2626;
}

/* ==========================================================================
   Dark Mode Overrides
   ========================================================================== */
html.dark .form-label, .dark .form-label {
  color: #b979cc;
}

html.dark .venue-budget-wrapper, .dark .venue-budget-wrapper {
  background: transparent;
  border-color: rgba(185, 121, 204, 0.3);
}

html.dark .venue-budget-title, .dark .venue-budget-title {
  color: #e9d5ff;
  border-left-color: #b979cc;
}

html.dark .venue-rate-outside, .dark .venue-rate-outside {
  color: #f9a8d4;
  background: rgba(244, 63, 94, 0.1);
  border-color: rgba(244, 63, 94, 0.3);
}

html.dark .venue-rate-inside, .dark .venue-rate-inside {
  color: #86efac;
  background: rgba(16, 185, 129, 0.1);
  border-color: rgba(16, 185, 129, 0.3);
}

html.dark .budget-group-card, .dark .budget-group-card {
  background: rgba(30, 41, 59, 0.4);
  border-color: rgba(148, 163, 184, 0.2);
}

html.dark .budget-group-header, .dark .budget-group-header {
  background: rgba(15, 23, 42, 0.6);
  border-bottom-color: rgba(148, 163, 184, 0.2);
}

html.dark .budget-group-title, .dark .budget-group-title {
  color: #f8fafc;
}

html.dark .budget-group-total, .dark .budget-group-total {
  color: #b979cc;
  background: rgba(185, 121, 204, 0.15);
}

html.dark .budget-row-item, .dark .budget-row-item {
  background: rgba(15, 23, 42, 0.4);
  border-color: rgba(148, 163, 184, 0.1);
}

html.dark .budget-item-title, .dark .budget-item-title {
  color: #e2e8f0;
}

html.dark .others-input-name, .dark .others-input-name {
  background: rgba(15, 23, 42, 0.8);
  border-color: rgba(148, 163, 184, 0.3);
  color: #ffffff;
}

html.dark .others-input-name:focus, .dark .others-input-name:focus {
  border-color: #b979cc;
  box-shadow: 0 0 0 2px rgba(185, 121, 204, 0.2);
}

html.dark .budget-item-subtext, .dark .budget-item-subtext {
  color: #94a3b8;
}

html.dark .budget-currency-symbol, .dark .budget-currency-symbol {
  color: #cbd5e1;
}

html.dark .bl-rate, html.dark .bl-q,
.dark .bl-rate, .dark .bl-q {
  background: rgba(15, 23, 42, 0.8);
  border-color: rgba(148, 163, 184, 0.3);
  color: #ffffff;
}

html.dark .bl-rate:focus, html.dark .bl-q:focus, html.dark .bl-u:focus,
.dark .bl-rate:focus, .dark .bl-q:focus, .dark .bl-u:focus {
  border-color: #b979cc;
  box-shadow: 0 0 0 2px rgba(185, 121, 204, 0.2);
}

html.dark .bl-x, .dark .bl-x {
  color: #94a3b8;
}

html.dark .bl-mult, .dark .bl-mult {
  background: rgba(30, 41, 59, 0.5);
  border-color: rgba(148, 163, 184, 0.2);
}

html.dark .bl-u, .dark .bl-u {
  color: #e2e8f0;
}

html.dark .btn-add-other, .dark .btn-add-other {
  background: rgba(185, 121, 204, 0.1);
  color: #d8b4e2;
  border-color: rgba(185, 121, 204, 0.4);
}

html.dark .btn-add-other:hover, .dark .btn-add-other:hover {
  background: rgba(185, 121, 204, 0.2);
  border-color: rgba(185, 121, 204, 0.6);
}

html.dark .bl-link, .dark .bl-link {
  color: #b979cc;
}

html.dark .others-total-badge, .dark .others-total-badge {
  background: rgba(15, 23, 42, 0.6);
  border-color: rgba(148, 163, 184, 0.2);
  color: #e2e8f0;
}

html.dark .bl-clear, .dark .bl-clear {
  color: #94a3b8;
}

html.dark .bl-clear:hover, .dark .bl-clear:hover {
  color: #cbd5e1;
}
</style>
