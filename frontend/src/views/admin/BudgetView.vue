<template>
      <main class="main-content">
        <div class="content-wrapper">
          
          <div class="page-header">
            <div class="header-main-flex">
              <div>
                <h1 class="page-title">Budget Utilization Monitoring</h1>
                <p class="page-subtitle">Track GAD budget distribution by mandate, pending activity designs, and remaining balances.</p>
              </div>
            </div>
          </div>

          <div class="stats-grid">
            <div class="stat-card">
              <div class="stat-icon-wrapper blue">
                <span class="material-symbols-outlined">account_balance</span>
              </div>
              <div class="stat-content">
                <h3 class="stat-value">₱{{ formatNum(totalGadBudget) }}</h3>
                <p class="stat-label">Total GAD Budget</p>
              </div>
            </div>

            <div class="stat-card">
              <div class="stat-icon-wrapper green">
                <span class="material-symbols-outlined">trending_up</span>
              </div>
              <div class="stat-content">
                <h3 class="stat-value">₱{{ formatNum(actualCost) }}</h3>
                <p class="stat-label">Actual Cost</p>
              </div>
            </div>

            <div class="stat-card">
              <div class="stat-icon-wrapper amber">
                <span class="material-symbols-outlined">hourglass_empty</span>
              </div>
              <div class="stat-content">
                <h3 class="stat-value">₱{{ formatNum(proposedBudget) }}</h3>
                <p class="stat-label">Proposed Budget</p>
              </div>
            </div>

            <div class="stat-card">
              <div class="stat-icon-wrapper purple">
                <span class="material-symbols-outlined">pie_chart</span>
              </div>
              <div class="stat-content">
                <h3 class="stat-value">{{ overallUtilizationRate }}%</h3>
                <p class="stat-label">% Utilization of Budget</p>
              </div>
            </div>
          </div>

          <div class="table-container">
            <div class="table-wrapper">
              <table class="data-table">
                <thead>
                  <tr class="table-header-row">
                    <th class="table-header-cell col-number">#</th>
                    <th class="table-header-cell col-unit text-left">Gender Issue / Mandate</th>
                    <th class="table-header-cell col-activity text-left">GAD Activity</th>
                    <th class="table-header-cell col-allocated">Budget</th>
                    <th class="table-header-cell col-pending">Pending (ADs)</th>
                    <th class="table-header-cell col-remaining">Remaining</th>
                    <th class="table-header-cell col-actual-cost">Actual Cost / Expenditure</th>
                  </tr>
                </thead>
                <tbody class="table-body">
                  <tr v-if="budgetRows.length === 0">
                    <td colspan="7" class="empty-state">
                      No budget records found in the database.
                    </td>
                  </tr>

                  <tr v-else v-for="(row, index) in budgetRows" :key="row.id" class="table-row">
                    <td class="table-cell cell-number">
                      {{ index + 1 }}
                    </td>
                    
                    <td class="table-cell cell-unit">
                      <div class="unit-name">{{ row.mandate }}</div>
                      <div class="unit-code">{{ row.unit_code }}</div>
                    </td>

                    <td class="table-cell cell-activity">
                      {{ row.activity || 'N/A' }}
                    </td>

                    <td class="table-cell cell-allocated">
                      <div class="cell-value">₱{{ formatNum(row.allocated) }}</div>
                    </td>

                    <td class="table-cell cell-pending">
                      <div class="cell-value">
                        ₱{{ formatNum(row.pending_approved) }}
                      </div>
                    </td>

                    <td class="table-cell cell-remaining" :class="getRemainingClass(row.remaining)">
                      ₱{{ formatNum(row.remaining) }}
                    </td>

                    <td class="table-cell cell-actual-cost">
                      ₱{{ formatNum(row.actual_cost) }}
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
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../../api';

const router = useRouter();
const user = ref(JSON.parse(localStorage.getItem('user') || '{}'));

const budgetRows = ref([]);

const totalGadBudget = ref(0);
const actualCost = ref(0);
const proposedBudget = ref(0);
const overallUtilizationRate = ref('0.0');

const formatNum = (val) => {
  if (val === undefined || val === null) return '0.00';
  return Number(val).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const updateRowCalculations = (row) => {
  row.remaining = Math.max(0, (row.allocated || 0) - (row.utilized || 0) - (row.pending_approved || 0));
};

const getRemainingClass = (remaining) => {
  if (remaining <= 0) return 'remaining-critical';
  if (remaining < 10000) return 'remaining-warning';
  return 'remaining-healthy';
};

const fetchBudgetData = async () => {
  try {
    const [monitoringRes, summaryRes] = await Promise.all([
      api.get('staff/budget-monitoring'),
      api.get('budget/summary')
    ]);
    
    if (monitoringRes.data) {
      budgetRows.value = monitoringRes.data;
      
      // Ensure calculations are correct for each row
      budgetRows.value.forEach(row => {
        updateRowCalculations(row);
      });
    }

    if (summaryRes.data && summaryRes.data.success) {
      const b = summaryRes.data.data;
      totalGadBudget.value = b.total_budget || 0;
      actualCost.value = b.total_utilized || 0;
      proposedBudget.value = b.total_pending_approved || 0;
      overallUtilizationRate.value = Number(b.utilization_rate || 0).toFixed(1);
    }
  } catch (err) { 
    console.error('Error fetching budget data:', err); 
  }
};

const handleLogout = async () => {
  try {
    await api.get('logout');
    localStorage.removeItem('user');
    router.push('/login');
  } catch (err) {
    localStorage.removeItem('user');
    router.push('/login');
  }
};

onMounted(() => {
  if (!user.value.id || user.value.role !== 'admin') { 
    router.push('/login'); 
  } else { 
    fetchBudgetData(); 
  }
});
</script>

<style scoped>
.budget-utilization {
  min-height: 100vh;
  display: flex;
  color: #cbd5e1;
  font-family: system-ui, -apple-system, sans-serif;
}

.budget-utilization ::selection {
  background: rgba(153, 13, 209, 0.3);
  color: white;
}

.main-container {
  flex-grow: 1;
  margin-left: 256px;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

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
  margin-bottom: 0;
}

.header-main-flex {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  color: #16213e;
  margin: 0 0 0.5rem 0;
}

.page-subtitle {
  font-size: 1rem;
  color: #475569;
  margin: 0;
  line-height: 1.4;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
}

.stat-card {
  padding: 1.25rem;
  border-radius: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.15);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(8px);
  transition: all 0.3s;
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
}

.stat-card:hover {
  transform: translateY(-2px);
  border-color: rgba(185, 121, 204, 0.3);
}

.stat-icon-wrapper {
  width: 40px;
  height: 40px;
  border-radius: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-bottom: 0.75rem;
}

.stat-icon-wrapper.blue {
  background: rgba(59, 130, 246, 0.1);
}

.stat-icon-wrapper.blue .material-symbols-outlined {
  color: #60a5fa;
}

.stat-icon-wrapper.green {
  background: rgba(34, 197, 94, 0.1);
}

.stat-icon-wrapper.green .material-symbols-outlined {
  color: #4ade80;
}

.stat-icon-wrapper.amber {
  background: rgba(245, 158, 11, 0.1);
}

.stat-icon-wrapper.amber .material-symbols-outlined {
  color: #fbbf24;
}

.stat-icon-wrapper.purple {
  background: rgba(153, 13, 209, 0.1);
}

.stat-icon-wrapper.purple .material-symbols-outlined {
  color: #b979cc;
}

.material-symbols-outlined {
  font-size: 1.5rem;
}

.stat-content {
  min-width: 0;
}

.stat-value {
  font-size: 1.25rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  color: white;
  line-height: 1.25;
  margin: 0;
}

.stat-label {
  font-size: 0.85rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: rgba(203, 213, 225, 0.7);
  margin-top: 0.25rem;
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
  overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
  touch-action: pan-x;
  scrollbar-color: #b979cc rgba(0, 0, 0, 0.25);
  scrollbar-width: thin;
}

.table-wrapper::-webkit-scrollbar {
  height: 8px;
}

.table-wrapper::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.25);
}

.table-wrapper::-webkit-scrollbar-thumb {
  background: linear-gradient(90deg, #9333ea 0%, #c084fc 100%);
  border-radius: 999px;
}

.table-wrapper::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(90deg, #a855f7 0%, #d8b4fe 100%);
}

.data-table {
  width: 100%;
  text-align: left;
  border-collapse: collapse;
  min-width: 900px;
}

.col-number { width: 60px; text-align: center; }
.col-unit { width: 220px; }
.col-activity { width: 260px; }
.col-allocated { width: 150px; text-align: center; }
.col-pending { width: 150px; text-align: center; }
.col-remaining { width: 150px; text-align: center; }
.col-actual-cost { width: 190px; text-align: center; }

.table-header-row {
  border-bottom: 1px solid rgba(185, 121, 204, 0.1);
  background: rgba(0, 0, 0, 0.4);
}

.table-header-cell {
  padding: 1rem 1rem;
  font-size: 0.85rem;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: #b979cc;
  text-align: center;
}

.text-left {
  text-align: left;
}

.table-body {
  display: table-row-group;
  background: transparent;
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
  padding: 0.875rem 1rem;
  vertical-align: middle;
}

.cell-number {
  text-align: center;
  font-weight: 700;
  color: #cbd5e1;
}

.cell-unit {
  text-align: left;
}

.cell-activity {
  min-width: 260px;
  color: #e2e8f0;
  line-height: 1.4;
}

.unit-name {
  font-weight: 700;
  color: #e2e8f0;
}

.unit-code {
  font-size: 0.85rem;
  color: #b979cc;
  letter-spacing: 0.025em;
  text-transform: uppercase;
  margin-top: 0.125rem;
  font-family: monospace;
}

.cell-value {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: #cbd5e1;
  position: relative;
}

.table-header-cell.col-actual-cost,
.cell-actual-cost {
  color: #fff;
  font-weight: 700;
  text-align: center;
}

/* Remaining Budget Colors */
.remaining-healthy {
  color: #4ade80;
  font-weight: 700;
  text-align: center;
}

.remaining-warning {
  color: #fbbf24;
  font-weight: 700;
  text-align: center;
}

.remaining-critical {
  color: #f87171;
  font-weight: 700;
  text-align: center;
}

/* Progress Bar */
.progress-container {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.progress-bar-wrapper {
  flex: 1;
  height: 0.375rem;
  background: #334155;
  border-radius: 9999px;
  overflow: hidden;
}

.progress-bar-fill {
  height: 100%;
  border-radius: 9999px;
  transition: width 0.3s;
}

.bar-healthy {
  background: #4ade80;
}

.bar-warning {
  background: #fbbf24;
}

.bar-critical {
  background: #f87171;
}

.percent-text {
  font-weight: 700;
  font-size: 1rem;
  min-width: 45px;
  text-align: right;
}

.text-healthy {
  color: #4ade80;
}

.text-warning {
  color: #fbbf24;
}

.text-critical {
  color: #f87171;
}

/* Legend */
.legend-container {
  font-size: 0.85rem;
  color: #94a3b8;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 0.5rem;
}

.legend-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.legend-icon {
  margin-right: 0.25rem;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.legend-dot {
  display: inline-block;
  width: 0.75rem;
  height: 0.75rem;
  border-radius: 9999px;
}

.legend-dot.green {
  background: rgba(74, 222, 128, 0.4);
}

.legend-dot.yellow {
  background: rgba(251, 191, 36, 0.4);
}

.legend-dot.red {
  background: rgba(248, 113, 113, 0.4);
}

.legend-right {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.edit-note {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
}

/* Modal */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(8px);
  z-index: 50;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.modal-container {
  border-radius: 1rem;
  border: 1px solid rgba(185, 121, 204, 0.2);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.25);
  padding: 1.5rem;
  width: 100%;
  max-width: 28rem;
  backdrop-filter: blur(24px);
  background: rgba(26, 26, 46, 0.95);
  animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.modal-title {
  font-size: 1rem;
  font-weight: 900;
  letter-spacing: -0.025em;
  color: white;
  margin-bottom: 0.5rem;
}

.modal-message {
  font-size: 1rem;
  color: #94a3b8;
  line-height: 1.5;
  margin-bottom: 1.25rem;
}

.modal-highlight {
  font-weight: 700;
  color: white;
}

.modal-unit {
  font-weight: 700;
  color: #b979cc;
}

.modal-amount {
  font-family: monospace;
  font-weight: 700;
  color: white;
  background: rgba(255, 255, 255, 0.1);
  padding: 0.125rem 0.375rem;
  border-radius: 0.25rem;
}

.modal-actions {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 0.625rem;
}

.modal-btn {
  padding: 0.5rem 1rem;
  border-radius: 0.75rem;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
}

.modal-btn-cancel {
  color: #94a3b8;
  background: transparent;
}

.modal-btn-cancel:hover {
  color: white;
  background: rgba(255, 255, 255, 0.05);
}

.modal-btn-confirm {
  background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
  color: white;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.modal-btn-confirm:hover {
  opacity: 0.9;
}

/* Scrollbar */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.1);
}

::-webkit-scrollbar-thumb {
  background: rgba(185, 121, 204, 0.3);
  border-radius: 99px;
}

::-webkit-scrollbar-thumb:hover {
  background: rgba(153, 13, 209, 0.5);
}

/* Responsive */
@media (max-width: 1024px) {
  .main-container {
    margin-left: 0;
  }
  
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .legend-container {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
}

@media (max-width: 768px) {
  .content-wrapper {
    padding: 20px;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .header-main-flex {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
}
</style>
