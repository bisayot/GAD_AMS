<template>
  <div v-if="parsedBudget.length" class="w-full mt-4 overflow-x-auto">
    <div class="budget-groups-container min-w-[500px]">
    <div v-for="(venue, vIdx) in parsedBudget" :key="vIdx" class="venue-budget-container mb-6">
      <div @click="toggleVenueBudget(vIdx)" class="venue-budget-header">
        <div class="flex items-center gap-3">
          <span class="material-symbols-outlined venue-location-icon">location_on</span>
          <h4 class="venue-title">{{ venue.venue_name }}</h4>
        </div>
        <div class="flex items-center gap-4">
          <span class="venue-total-pill">₱{{ formatCurrency(venue.total) }}</span>
          <span class="material-symbols-outlined venue-expand-icon" :class="{ 'rotate-180': venueExpandedState[vIdx] }">expand_more</span>
        </div>
      </div>
      
      <div v-show="venueExpandedState[vIdx] || parsedBudget.length === 1" class="venue-budget-content p-4 flex flex-col gap-4">
        <div v-for="(group, gIdx) in venue.groups" :key="gIdx" class="budget-group-card">
          <div class="budget-group-header" style="background: rgba(185, 121, 204, 0.1); padding: 8px 12px; border-radius: 8px; margin-bottom: 8px; display: flex; gap: 8px; align-items: center; border-bottom: 1px solid rgba(185, 121, 204, 0.2);">
            <span class="budget-group-icon">{{ group.icon }}</span>
            <span class="budget-group-title" style="color: #7e22ce; font-weight: bold; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px;">{{ group.name }}</span>
          </div>
          <div class="budget-group-content" style="padding: 0 12px;">
            <div v-for="(child, cIdx) in group.children" :key="cIdx" class="budget-row-item py-2 border-b border-slate-100 dark:border-white/5 last:border-0">
              <div class="budget-row-header flex justify-between items-start w-full">
                <div class="budget-item-info flex-1 pr-4">
                  <div class="budget-item-title text-slate-800 dark:text-slate-200 text-sm font-medium leading-snug" v-html="formatBudgetName(child.name)"></div>
                  <div v-if="child.formula || child.computation" class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-1">
                    {{ child.formula || child.computation }}
                  </div>
                  <div v-else-if="child.sub_item && child.name !== child.sub_item" class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    {{ child.sub_item }}
                  </div>
                </div>
                <div class="budget-item-value flex items-center justify-end min-w-[120px] font-bold text-slate-900 dark:text-white text-sm">
                  <span class="budget-currency-symbol mr-1 text-slate-400 dark:text-slate-500">₱</span>
                  <div class="budget-card-input-readonly text-right">{{ formatCurrency(child.value) }}</div>
                </div>
              </div>
              <div v-if="child.subOptions" class="budget-sub-options-container mt-2 flex gap-3">
                <label v-for="(opt, oIdx) in child.subOptions" :key="oIdx" class="budget-read-only-checkbox flex items-center gap-2">
                  <input type="checkbox" :checked="opt.checked" disabled class="budget-checkbox-disabled opacity-50" />
                  <span class="budget-checkbox-label-text text-xs text-slate-500 dark:text-slate-400">{{ opt.label }}</span>
                </label>
              </div>
              <div v-if="child.pax" class="budget-others-breakdown-container mt-2">
                 <div v-if="child.name === 'Meals'" class="flex gap-2 flex-wrap">
                    <span v-if="child.pax.breakfast" class="budget-pax-tag text-[11px] px-2 py-1 rounded">Breakfast: {{ child.pax.breakfast }} pax</span>
                    <span v-if="child.pax.lunch" class="budget-pax-tag text-[11px] px-2 py-1 rounded">Lunch: {{ child.pax.lunch }} pax</span>
                    <span v-if="child.pax.dinner" class="budget-pax-tag text-[11px] px-2 py-1 rounded">Dinner: {{ child.pax.dinner }} pax</span>
                 </div>
                 <div v-if="child.name === 'Snacks'" class="flex gap-2 flex-wrap">
                    <span v-if="child.pax.am_snack" class="budget-pax-tag text-[11px] px-2 py-1 rounded">AM Snack: {{ child.pax.am_snack }} pax</span>
                    <span v-if="child.pax.pm_snack" class="budget-pax-tag text-[11px] px-2 py-1 rounded">PM Snack: {{ child.pax.pm_snack }} pax</span>
                 </div>
              </div>
              <div v-if="child.othersBreakdown && child.othersBreakdown.length" class="budget-others-breakdown-container mt-2">
                <div v-for="(o, oIdx) in child.othersBreakdown" :key="oIdx" class="budget-others-breakdown-row" style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: space-between; padding: 6px 12px; border-radius: 6px; margin-bottom: 6px; font-size: 12px; border: 1px dashed #cbd5e1;">
                  <span class="text-slate-600 dark:text-slate-400">{{ o.name || 'Unnamed Item' }}</span>
                  <span class="text-slate-900 dark:text-slate-100 font-semibold">₱{{ formatCurrency(o.amount) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="grand-total-banner-card mt-4 p-4 rounded-xl flex justify-between items-center">
      <div class="grand-total-label-banner font-bold uppercase tracking-wider text-sm">Grand Total (PHP)</div>
      <div class="grand-total-value-banner font-bold text-2xl">
        ₱{{ formatCurrency(grandTotal) }}
      </div>
    </div>
    </div>
  </div>
  <div v-else class="empty-budget-notice p-4 text-center text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-900/30 rounded-xl border border-slate-200 dark:border-slate-700/50 mt-4">
    No budgetary requirements were specified for this design.
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
  design: {
    type: Object,
    required: true
  },
  budgetItems: {
    type: Array,
    default: () => []
  },
  report: {
    type: Object,
    default: () => ({})
  }
});

const formatCurrency = (val) => {
  if (!val) return '0.00';
  return Number(val).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const formatBudgetName = (name) => {
  if (!name) return '';
  return name.replace(/(\([^)]+\))/g, '<span style="color: #94a3b8; font-size: 11px; margin-left: 6px;">$1</span>');
};

const parsedBudget = computed(() => {
  const d = props.design;
  if (!d || !d.act_design_id) return [];

  // AR expenditures are passed explicitly. Fall back to the approved design
  // rows only when this is the approved budget view.
  const sourceItems = props.budgetItems.length > 0
    ? props.budgetItems
    : (Array.isArray(d.budget_items_raw) ? d.budget_items_raw : []);

  if (sourceItems.length > 0) {
    const venuesMap = {};
    
    sourceItems.forEach(item => {
      const vid = item.venue_id || 'Legacy';
      if (!venuesMap[vid]) {
        let vName = vid === 'Other' ? 'Other Venue' : `Venue ${vid}`;
        if (vid === 'Legacy') vName = 'General Budget';
        
        venuesMap[vid] = {
          venue_id: vid,
          venue_name: vName,
          items: [],
          totals: {
            meals: 0, snacks: 0, 
            venue: 0, accommodation: 0, equipment: 0, transportation: 0,
            pf: 0, tokens: 0, pf_pax: 0, tokens_pax: 0,
            materials: 0, others: 0
          },
          othersBreakdown: []
        };
        venuesMap[vid].computations = {};
        venuesMap[vid].cateringItems = [];
      }
      
      const vData = venuesMap[vid];
      const amt = Number(item.amount) || 0;
      
      const itemName = String(item.item_name || '');
      const isMeal = itemName === 'Meals' || ['Breakfast', 'Lunch', 'Dinner'].includes(itemName);
      const isSnack = itemName === 'Snacks' || ['AM Snack', 'PM Snack'].includes(itemName);
      if (item.formula || (typeof item.sub_item === 'string' && !item.sub_item.startsWith('{'))) {
        venuesMap[vid].computations[itemName] = item.formula || item.sub_item;
      }

      if (isMeal) {
        vData.totals.meals += amt;
        if (['Breakfast', 'Lunch', 'Dinner'].includes(itemName)) {
          vData.cateringItems.push({
            name: itemName,
            value: amt,
            pax: Number(item.pax) || 0,
            computation: vData.computations[itemName],
            sub_item: item.sub_item
          });
        }
        if (!vData.mealsPax) vData.mealsPax = {};
        if (item.pax != null && ['Breakfast', 'Lunch', 'Dinner'].includes(itemName)) {
          vData.mealsPax[itemName.toLowerCase()] = Number(item.pax) || 0;
        }
        try {
          const parsed = JSON.parse(item.sub_item);
          if (parsed && typeof parsed === 'object') vData.mealsPax = { ...vData.mealsPax, ...parsed };
        } catch(e) {}
      }
      else if (isSnack) {
        vData.totals.snacks += amt;
        if (['AM Snack', 'PM Snack'].includes(itemName)) {
          vData.cateringItems.push({
            name: itemName,
            value: amt,
            pax: Number(item.pax) || 0,
            computation: vData.computations[itemName],
            sub_item: item.sub_item
          });
        }
        if (!vData.snacksPax) vData.snacksPax = {};
        if (item.pax != null && ['AM Snack', 'PM Snack'].includes(itemName)) {
          vData.snacksPax[itemName === 'AM Snack' ? 'am_snack' : 'pm_snack'] = Number(item.pax) || 0;
        }
        try {
          const parsed = JSON.parse(item.sub_item);
          if (parsed && typeof parsed === 'object') vData.snacksPax = { ...vData.snacksPax, ...parsed };
        } catch(e) {}
      }
      else if (itemName === 'Function Room/Venue') vData.totals.venue += amt;
      else if (itemName === 'Accommodation') vData.totals.accommodation += amt;
      else if (itemName === 'Equipment Rental') vData.totals.equipment += amt;
      else if (itemName === 'Transportation') vData.totals.transportation += amt;
      else if (itemName === 'Professional Fee/Honoraria' || itemName === 'Professional Fee/Honoria') {
        vData.totals.pf += amt;
        vData.totals.pf_pax += Number(item.pax || 0);
      }
      else if (itemName === 'Token/s') {
        vData.totals.tokens += amt;
        vData.totals.tokens_pax += Number(item.pax || 0);
      }
      else if (itemName === 'Materials and Supplies') vData.totals.materials += amt;
      else {
        vData.totals.others += amt;
        const displayName = (itemName === 'Others' && item.sub_item) ? item.sub_item : itemName;
        vData.othersBreakdown.push({ name: displayName, amount: amt });
      }
    });

    const result = [];
    for (const vid in venuesMap) {
      const v = venuesMap[vid];
      
      const groups = [
        {
          name: 'Catering & Hospitality', icon: '🍽️',
          total: v.totals.meals + v.totals.snacks,
          children: v.cateringItems.length ? v.cateringItems : [
            { name: 'Meals', value: v.totals.meals, pax: v.mealsPax },
            { name: 'Snacks', value: v.totals.snacks, pax: v.snacksPax }
          ]
        },
        {
          name: 'Venue & Logistics', icon: '🏛️',
          total: v.totals.venue + v.totals.accommodation + v.totals.equipment + v.totals.transportation,
          children: [
            { name: 'Function Room/Venue', value: v.totals.venue, computation: v.computations['Function Room/Venue'] },
            { name: 'Accommodation', value: v.totals.accommodation, computation: v.computations.Accommodation },
            { name: 'Equipment Rental', value: v.totals.equipment, computation: v.computations['Equipment Rental'] },
            { name: 'Transportation', value: v.totals.transportation, computation: v.computations.Transportation }
          ]
        },
        {
          name: 'Program & Speakers', icon: '🎤',
          total: v.totals.pf + v.totals.tokens,
          children: [
            { name: `Professional Fee/Honoraria ${v.totals.pf > 0 ? `(Number of Speakers: ${v.totals.pf_pax})` : ''}`, value: v.totals.pf, computation: v.computations['Professional Fee/Honoraria'] || v.computations['Professional Fee/Honoria'] },
            { name: `Token/s ${v.totals.tokens > 0 ? `(Number of Recipients: ${v.totals.tokens_pax})` : ''}`, value: v.totals.tokens, computation: v.computations['Token/s'] }
          ]
        },
        {
          name: 'Materials & Miscellaneous', icon: '📦',
          total: v.totals.materials + v.totals.others,
          children: [
            { name: 'Materials and Supplies', value: v.totals.materials, computation: v.computations['Materials and Supplies'] },
            { name: 'Others', value: v.totals.others, othersBreakdown: v.othersBreakdown }
          ]
        }
      ];
      
      const venueTotal = groups.reduce((sum, g) => sum + g.total, 0);
      
      result.push({
        venue_id: v.venue_id,
        venue_name: v.venue_id === 'Legacy' ? 'General Budget (Legacy Format)' : `Venue: ${v.venue_id}`,
        isExpanded: false,
        groups: groups,
        total: venueTotal
      });
    }
    
    if (d.venues || d.venues_list || props.report.ar_venues_list) {
      try {
        result.forEach(r => {
            if (r.venue_id !== 'Legacy' && r.venue_id !== 'Other') {
               r.venue_name = `Venue ID: ${r.venue_id}`;
               const venuesToSearch = props.report.ar_venues_list?.length
                 ? props.report.ar_venues_list
                 : d.venues_list;
               if (venuesToSearch && Array.isArray(venuesToSearch)) {
                 const vMatch = venuesToSearch.find(x => x.venue_id == r.venue_id);
                 if (vMatch) r.venue_name = vMatch.venue_name;
               }
            }
        });
      } catch(e){}
    }
    
    return result;
  }

  // LEGACY FORMAT FALLBACK
  const b = (d.budget_items && d.budget_items[0]) || {};
  const dbMeals = Number(b.meals_total || 0);
  const dbSnacks = Number(b.snacks_total || 0);
  const legacyMealsSnacks = Number(b.meals_and_snacks || 0);

  let mealsVal = 0;
  let snacksVal = 0;
  if (dbMeals === 0 && dbSnacks === 0 && legacyMealsSnacks > 0) {
      mealsVal = legacyMealsSnacks;
  } else {
      mealsVal = dbMeals;
      snacksVal = dbSnacks;
  }

  const dbMat = Number(b.materials_and_supplies || 0);
  let ob = [];
  if (b.materials_others_breakdown) {
    try { ob = JSON.parse(b.materials_others_breakdown); } catch(e){}
  }
  const dbOthers = Number(b.others_total) || ob.reduce((s, o) => s + Number(o.amount || 0), 0);

  const items = [
    {
      name: 'Catering & Hospitality', icon: '🍽️',
      total: mealsVal + snacksVal,
      children: [
        { name: 'Meals', value: mealsVal },
        { name: 'Snacks', value: snacksVal }
      ]
    },
    {
      name: 'Venue & Logistics', icon: '🏛️',
      total: Number(b.function_room_venue || 0) + Number(b.accommodation || 0) + Number(b.equipment_rental || 0) + Number(b.transportation || 0),
      children: [
        { name: 'Function Room/Venue', value: Number(b.function_room_venue || 0) },
        { name: 'Accommodation', value: Number(b.accommodation || 0) },
        { name: 'Equipment Rental', value: Number(b.equipment_rental || 0) },
        { name: 'Transportation', value: Number(b.transportation || 0) }
      ]
    },
    {
      name: 'Program & Speakers', icon: '🎤',
      total: Number(b.professional_fee_honoria || 0) + Number(b.tokens || 0),
      children: [
        { name: `Professional Fee/Honoraria ${Number(b.professional_fee_honoria || 0) > 0 ? `(Number of Speakers: ${b.pf_pax || 0})` : ''}`, value: Number(b.professional_fee_honoria || 0) },
        { name: `Token/s ${Number(b.tokens || 0) > 0 ? `(Number of Recipients: ${b.tokens_pax || 0})` : ''}`, value: Number(b.tokens || 0) }
      ]
    },
    {
      name: 'Materials & Miscellaneous', icon: '📦',
      total: dbMat + dbOthers,
      children: [
        { name: 'Materials and Supplies', value: dbMat },
        { name: 'Others', value: dbOthers, othersBreakdown: ob }
      ]
    }
  ];
  
  return [
    {
      venue_id: 'Legacy',
      venue_name: 'General Budget',
      isExpanded: true,
      groups: items,
      total: items.reduce((s, g) => s + g.total, 0)
    }
  ];
});

const grandTotal = computed(() => {
  return parsedBudget.value.reduce((sum, venue) => sum + venue.total, 0);
});

const venueExpandedState = ref({});
const toggleVenueBudget = (venueIndex) => {
  venueExpandedState.value[venueIndex] = !venueExpandedState.value[venueIndex];
};
</script>

<style>
/* ActivityDesignBudget Component Theming */
.venue-budget-container {
  background-color: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
  margin-bottom: 24px;
}

html.dark .venue-budget-container,
.dark .venue-budget-container {
  background-color: rgba(15, 23, 42, 0.6) !important;
  border: 1px solid rgba(51, 65, 85, 0.5) !important;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3) !important;
}

.venue-budget-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px;
  background: linear-gradient(to right, #f8fafc, #f1f5f9);
  border-bottom: 1px solid #e2e8f0;
  cursor: pointer;
  transition: background 0.2s;
}

.venue-budget-header:hover {
  background: #f1f5f9;
}

html.dark .venue-budget-header,
.dark .venue-budget-header {
  background: linear-gradient(to right, #1e293b, #0f172a) !important;
  border-bottom: 1px solid rgba(51, 65, 85, 0.5) !important;
}

html.dark .venue-budget-header:hover,
.dark .venue-budget-header:hover {
  background: linear-gradient(to right, #334155, #1e293b) !important;
}

.venue-location-icon {
  color: #7e22ce;
}

html.dark .venue-location-icon,
.dark .venue-location-icon {
  color: #c084fc !important;
}

.venue-title {
  color: #1e293b;
  font-weight: 700;
  font-size: 1.125rem;
  margin: 0;
}

html.dark .venue-title,
.dark .venue-title {
  color: #f1f5f9 !important;
}

.venue-total-pill {
  color: #7e22ce;
  font-weight: 700;
  background-color: #f3e8ff;
  padding: 4px 12px;
  border-radius: 8px;
  font-size: 14px;
}

html.dark .venue-total-pill,
.dark .venue-total-pill {
  color: #f472b6 !important;
  background-color: rgba(236, 72, 153, 0.15) !important;
}

.venue-expand-icon {
  color: #64748b;
  transition: transform 0.3s;
}

html.dark .venue-expand-icon,
.dark .venue-expand-icon {
  color: #94a3b8 !important;
}

.grand-total-banner-card {
  margin-top: 16px;
  padding: 16px;
  border-radius: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(135deg, rgba(219, 39, 119, 0.08) 0%, rgba(147, 51, 234, 0.08) 100%);
  border: 1px solid rgba(219, 39, 119, 0.25);
}

html.dark .grand-total-banner-card,
.dark .grand-total-banner-card {
  background: linear-gradient(135deg, rgba(219, 39, 119, 0.18) 0%, rgba(147, 51, 234, 0.18) 100%) !important;
  border: 1px solid rgba(219, 39, 119, 0.4) !important;
}

.grand-total-label-banner {
  font-weight: 700;
  color: #7e22ce;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  font-size: 14px;
}

html.dark .grand-total-label-banner,
.dark .grand-total-label-banner {
  color: #e9d5ff !important;
}

.grand-total-value-banner {
  font-weight: 800;
  font-size: 24px;
  color: #0f172a;
}

html.dark .grand-total-value-banner,
.dark .grand-total-value-banner {
  color: #ffffff !important;
}

.budget-group-header {
  background: rgba(185, 121, 204, 0.1);
  padding: 8px 12px;
  border-radius: 8px;
  margin-bottom: 8px;
  display: flex;
  gap: 8px;
  align-items: center;
  border-bottom: 1px solid rgba(185, 121, 204, 0.2);
}

html.dark .budget-group-header,
.dark .budget-group-header {
  background: rgba(185, 121, 204, 0.15) !important;
  border-bottom: 1px solid rgba(185, 121, 204, 0.25) !important;
}

.budget-group-title {
  color: #7e22ce;
  font-weight: bold;
  text-transform: uppercase;
  font-size: 13px;
  letter-spacing: 0.5px;
}

html.dark .budget-group-title,
.dark .budget-group-title {
  color: #d8b4fe !important;
}

.budget-item-title {
  color: #1e293b;
  font-size: 14px;
  font-weight: 500;
  line-height: 1.4;
}

html.dark .budget-item-title,
.dark .budget-item-title {
  color: #e2e8f0 !important;
}

.budget-card-input-readonly {
  color: #0f172a;
}

html.dark .budget-card-input-readonly,
.dark .budget-card-input-readonly {
  color: #ffffff !important;
}

.budget-currency-symbol {
  color: #94a3b8;
}

html.dark .budget-currency-symbol,
.dark .budget-currency-symbol {
  color: #94a3b8 !important;
}

.budget-pax-tag {
  background-color: #f1f5f9;
  color: #334155;
  border: 1px solid #e2e8f0;
}

html.dark .budget-pax-tag,
.dark .budget-pax-tag {
  background-color: rgba(30, 41, 59, 0.7) !important;
  color: #cbd5e1 !important;
  border-color: rgba(71, 85, 105, 0.5) !important;
}

.budget-others-breakdown-row {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  justify-content: space-between;
  padding: 6px 12px;
  border-radius: 6px;
  margin-bottom: 6px;
  font-size: 12px;
  border: 1px dashed #cbd5e1;
}

html.dark .budget-others-breakdown-row,
.dark .budget-others-breakdown-row {
  border-color: rgba(255, 255, 255, 0.15) !important;
  background-color: rgba(0, 0, 0, 0.2) !important;
}

.empty-budget-notice {
  padding: 16px;
  text-align: center;
  color: #64748b;
  background: #f8fafc;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  margin-top: 16px;
}

html.dark .empty-budget-notice,
.dark .empty-budget-notice {
  color: #94a3b8 !important;
  background: rgba(15, 23, 42, 0.3) !important;
  border-color: rgba(51, 65, 85, 0.5) !important;
}
</style>
