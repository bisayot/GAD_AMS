<template>
  <div v-if="parsedBudget.length" class="budget-groups-container w-full mt-4">
    <div v-for="(venue, vIdx) in parsedBudget" :key="vIdx" class="venue-budget-container mb-6 bg-slate-900/40 border border-slate-700/50 rounded-2xl overflow-hidden shadow-lg">
      <div @click="toggleVenueBudget(vIdx)" class="venue-budget-header cursor-pointer flex justify-between items-center p-4 bg-gradient-to-r from-slate-800 to-slate-900 hover:from-slate-700 hover:to-slate-800 transition-colors">
        <div class="flex items-center gap-3">
          <span class="material-symbols-outlined text-purple-400">location_on</span>
          <h4 class="text-slate-200 font-bold text-lg m-0">{{ venue.venue_name }}</h4>
        </div>
        <div class="flex items-center gap-4">
          <span class="text-pink-400 font-bold bg-pink-500/10 px-3 py-1 rounded-lg">₱{{ formatCurrency(venue.total) }}</span>
          <span class="material-symbols-outlined text-slate-400 transition-transform duration-300" :class="{ 'rotate-180': venueExpandedState[vIdx] }">expand_more</span>
        </div>
      </div>
      
      <div v-show="venueExpandedState[vIdx] || parsedBudget.length === 1" class="venue-budget-content p-4 flex flex-col gap-4">
        <div v-for="(group, gIdx) in venue.groups" :key="gIdx" class="budget-group-card">
          <div class="budget-group-header" style="background: rgba(185, 121, 204, 0.1); padding: 8px 12px; border-radius: 8px; margin-bottom: 8px; display: flex; gap: 8px; align-items: center; border-bottom: 1px solid rgba(185, 121, 204, 0.2);">
            <span class="budget-group-icon">{{ group.icon }}</span>
            <span class="budget-group-title" style="color: #b979cc; font-weight: bold; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px;">{{ group.name }}</span>
          </div>
          <div class="budget-group-content" style="padding: 0 12px;">
            <div v-for="(child, cIdx) in group.children" :key="cIdx" class="budget-row-item py-2 border-b border-white/5 last:border-0">
              <div class="budget-row-header flex justify-between items-start w-full">
                <div class="budget-item-info flex-1 pr-4">
                  <div class="budget-item-title text-slate-200 text-sm font-medium leading-snug" v-html="formatBudgetName(child.name)"></div>
                  <div v-if="child.formula || child.computation" class="text-xs text-slate-400 font-mono mt-1">
                    {{ child.formula || child.computation }}
                  </div>
                  <div v-else-if="child.sub_item && child.name !== child.sub_item" class="text-xs text-slate-400 mt-1">
                    {{ child.sub_item }}
                  </div>
                </div>
                <div class="budget-item-value flex items-center justify-end min-w-[120px] font-bold text-white text-sm">
                  <span class="budget-currency-symbol mr-1 text-slate-400">₱</span>
                  <div class="budget-card-input-readonly text-right">{{ formatCurrency(child.value) }}</div>
                </div>
              </div>
              <div v-if="child.subOptions" class="budget-sub-options-container mt-2 flex gap-3">
                <label v-for="(opt, oIdx) in child.subOptions" :key="oIdx" class="budget-read-only-checkbox flex items-center gap-2">
                  <input type="checkbox" :checked="opt.checked" disabled class="budget-checkbox-disabled opacity-50" />
                  <span class="budget-checkbox-label-text text-xs text-slate-400">{{ opt.label }}</span>
                </label>
              </div>
              <div v-if="child.pax" class="budget-others-breakdown-container mt-2">
                 <div v-if="child.name === 'Meals'" class="flex gap-2 flex-wrap">
                    <span v-if="child.pax.breakfast" class="text-[11px] bg-slate-800/50 text-slate-300 px-2 py-1 rounded border border-slate-700">Breakfast: {{ child.pax.breakfast }} pax</span>
                    <span v-if="child.pax.lunch" class="text-[11px] bg-slate-800/50 text-slate-300 px-2 py-1 rounded border border-slate-700">Lunch: {{ child.pax.lunch }} pax</span>
                    <span v-if="child.pax.dinner" class="text-[11px] bg-slate-800/50 text-slate-300 px-2 py-1 rounded border border-slate-700">Dinner: {{ child.pax.dinner }} pax</span>
                 </div>
                 <div v-if="child.name === 'Snacks'" class="flex gap-2 flex-wrap">
                    <span v-if="child.pax.am_snack" class="text-[11px] bg-slate-800/50 text-slate-300 px-2 py-1 rounded border border-slate-700">AM Snack: {{ child.pax.am_snack }} pax</span>
                    <span v-if="child.pax.pm_snack" class="text-[11px] bg-slate-800/50 text-slate-300 px-2 py-1 rounded border border-slate-700">PM Snack: {{ child.pax.pm_snack }} pax</span>
                 </div>
              </div>
              <div v-if="child.othersBreakdown && child.othersBreakdown.length" class="budget-others-breakdown-container mt-2">
                <div v-for="(o, oIdx) in child.othersBreakdown" :key="oIdx" class="budget-others-breakdown-row" style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: space-between; padding: 6px 12px; background: rgba(0,0,0,0.2); border-radius: 6px; margin-bottom: 6px; font-size: 12px; border: 1px dashed rgba(255,255,255,0.1);">
                  <span style="color: #94a3b8;">{{ o.name || 'Unnamed Item' }}</span>
                  <span style="color: #f8fafc; font-weight: 600;">₱{{ formatCurrency(o.amount) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="grand-total-banner-card mt-4 p-4 rounded-xl flex justify-between items-center" style="background: linear-gradient(135deg, rgba(219,39,119,0.1) 0%, rgba(147,51,234,0.1) 100%); border: 1px solid rgba(219,39,119,0.3);">
      <div class="grand-total-label-banner font-bold text-purple-300 uppercase tracking-wider text-sm">Grand Total (PHP)</div>
      <div class="grand-total-value-banner font-bold text-2xl text-white">
        ₱{{ formatCurrency(grandTotal) }}
      </div>
    </div>
  </div>
  <div v-else class="empty-budget-notice p-4 text-center text-slate-400 bg-slate-900/30 rounded-xl border border-slate-700/50 mt-4">
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
