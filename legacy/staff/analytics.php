<?php
// staff/analytics.php - Dedicated Analytics Dashboard for GAD-IMS
require_once '../auth.php';
require_role(['gad_staff', 'admin']);
$page_title = 'Analytics Dashboard | GAD-IMS';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Analytics Dashboard | GAD-IMS</title>
  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: #f8f9fb;
      font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Sidebar offset */
    .pl-64 { padding-left: 256px; }
    .pt-20 { padding-top: 80px; }
    .min-h-screen { min-height: 100vh; }
    .p-8 { padding: 32px; }

    /* Header */
    .analytics-header {
      margin-bottom: 32px;
    }
    .analytics-header h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 28px;
      color: #1e293b;
    }
    .analytics-header p {
      font-size: 14px;
      color: #64748b;
      margin-top: 4px;
    }

    /* Stats row */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 32px;
    }
    .stat-big {
      background: white;
      border-radius: 20px;
      padding: 20px 24px;
      border: 1px solid #eef2ff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .stat-big-label {
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: #94a3b8;
    }
    .stat-big-value {
      font-family: 'DM Serif Display', serif;
      font-size: 36px;
      color: #1e293b;
      margin-top: 8px;
    }
    .stat-big-sub {
      font-size: 11px;
      color: #64748b;
      margin-top: 4px;
    }

    /* Graph grid */
    .graph-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 28px;
      margin-bottom: 32px;
    }
    .graph-card {
      background: white;
      border-radius: 24px;
      padding: 24px 28px;
      border: 1px solid #eef2ff;
      box-shadow: 0 4px 14px rgba(0,0,0,0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .graph-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 16px 30px rgba(75,31,168,.1);
    }
    .graph-title {
      font-family: 'DM Serif Display', serif;
      font-size: 18px;
      color: #1e293b;
      margin-bottom: 6px;
    }
    .graph-subtitle {
      font-size: 12px;
      color: #94a3b8;
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 1px solid #f1f5f9;
    }
    .graph-container {
      position: relative;
      height: 320px;
      width: 100%;
    }
    .legend {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
      margin-top: 20px;
      padding-top: 16px;
      border-top: 1px solid #f1f5f9;
      font-size: 12px;
      font-weight: 500;
      color: #475569;
    }
    .legend span {
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .legend-dot {
      width: 12px;
      height: 12px;
      border-radius: 4px;
    }
    .pie-legend {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-left: 20px;
      flex: 1;
    }
    .pie-legend-item {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 13px;
    }
    .pie-legend-color {
      width: 14px;
      height: 14px;
      border-radius: 4px;
    }
    .flex-between {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .total-badge {
      margin-top: 16px;
      padding: 12px 16px;
      background: #f8fafc;
      border-radius: 12px;
      text-align: center;
      font-size: 13px;
      font-weight: 600;
      color: #1e293b;
    }
    .total-badge strong {
      font-family: 'DM Serif Display', serif;
      font-size: 20px;
      color: #4B1FA8;
      margin-left: 8px;
    }

    /* Back button */
    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: white;
      border: 1px solid #e2e8f0;
      border-radius: 40px;
      padding: 8px 18px;
      font-size: 13px;
      font-weight: 500;
      color: #4B1FA8;
      text-decoration: none;
      margin-bottom: 20px;
      transition: all 0.2s;
    }
    .back-btn:hover {
      background: #f3e8ff;
      border-color: #4B1FA8;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 24px;
      font-size: 11px;
      color: #94a3b8;
      border-top: 1px solid #eef2ff;
      margin-top: 20px;
    }

    @media (max-width: 1200px) {
      .graph-grid { grid-template-columns: 1fr; }
      .stats-row { grid-template-columns: repeat(2, 1fr); }
    }
  </style>
</head>
<body>
  <?php include 'sidebar.php'; ?>
  
  <main class="pl-64 pt-20 min-h-screen">
    <div class="p-8">
      <!-- Back Button -->
      <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
      
      <!-- Header -->
      <div class="analytics-header">
        <h1>GAD Analytics Dashboard</h1>
        <p>Comprehensive visualization of budget utilization, enrollment data, compliance metrics, and expense tracking</p>
      </div>

      <!-- Key Stats Row -->
      <div class="stats-row">
        <div class="stat-big">
          <div class="stat-big-label">Total GAD Activities</div>
          <div class="stat-big-value">38</div>
          <div class="stat-big-sub">Across all colleges & offices</div>
        </div>
        <div class="stat-big">
          <div class="stat-big-label">Approved Budget</div>
          <div class="stat-big-value">₱2.5M</div>
          <div class="stat-big-sub">FY 2025–2026 GPB</div>
        </div>
        <div class="stat-big">
          <div class="stat-big-label">Total Participants</div>
          <div class="stat-big-value">1,842</div>
          <div class="stat-big-sub">Students & employees</div>
        </div>
        <div class="stat-big">
          <div class="stat-big-label">Offices Compliant</div>
          <div class="stat-big-value">9/12</div>
          <div class="stat-big-sub">Colleges & admin units</div>
        </div>
      </div>

      <!-- Graph Grid -->
      <div class="graph-grid">
        <!-- Graph 1: Budget Utilization -->
        <div class="graph-card">
          <h3 class="graph-title">💰 Budget Utilization per Office</h3>
          <p class="graph-subtitle">Approved Budget vs. Actual Cost — variance at a glance</p>
          <div class="graph-container"><canvas id="chart-budget"></canvas></div>
          <div class="legend">
            <span><div class="legend-dot" style="background:#6B3FD4"></div> Approved Budget</span>
            <span><div class="legend-dot" style="background:#F5C843"></div> Actual Cost</span>
          </div>
        </div>

        <!-- Graph 2: Compliance Leaderboard -->
        <div class="graph-card">
          <h3 class="graph-title">✅ Compliance Leaderboard</h3>
          <p class="graph-subtitle">Activities conducted per college / office</p>
          <div class="graph-container" style="height: 280px;"><canvas id="chart-compliance"></canvas></div>
          <div class="legend">
            <span><div class="legend-dot" style="background:#4B1FA8"></div> Number of Activities</span>
          </div>
        </div>

        <!-- Graph 3: SDD Enrollment by College -->
        <div class="graph-card">
          <h3 class="graph-title">👥 2nd Semester Enrollment by College</h3>
          <p class="graph-subtitle">Sex-disaggregated data — Male vs. Female students enrolled</p>
          <div class="graph-container" style="height: 300px;"><canvas id="chart-enrollment"></canvas></div>
          <div class="legend">
            <span><div class="legend-dot" style="background:#6B3FD4"></div> Male (5,469)</span>
            <span><div class="legend-dot" style="background:#F5C843"></div> Female (10,858)</span>
          </div>
          <div class="total-badge">Total Enrolled Students: <strong>16,327</strong></div>
        </div>

        <!-- Graph 4: Budget by Expense Category -->
        <div class="graph-card">
          <h3 class="graph-title">💵 Budget by Expense Category</h3>
          <p class="graph-subtitle">Distribution of ₱2.5M GAD budget across expense types</p>
          <div class="flex-between" style="gap: 30px;">
            <div class="graph-container" style="height: 200px; width: 200px; flex-shrink: 0;"><canvas id="chart-expense"></canvas></div>
            <div class="pie-legend">
              <div class="pie-legend-item"><div class="pie-legend-color" style="background:#4B1FA8"></div> Supplies & Materials <span style="margin-left: auto; font-weight: 700;">₱480K (19%)</span></div>
              <div class="pie-legend-item"><div class="pie-legend-color" style="background:#F5C843"></div> Meals & Snacks <span style="margin-left: auto; font-weight: 700;">₱725K (29%)</span></div>
              <div class="pie-legend-item"><div class="pie-legend-color" style="background:#2ECFB3"></div> Tokens / Honoraria <span style="margin-left: auto; font-weight: 700;">₱340K (14%)</span></div>
              <div class="pie-legend-item"><div class="pie-legend-color" style="background:#F26B8A"></div> Travel & Training <span style="margin-left: auto; font-weight: 700;">₱955K (38%)</span></div>
            </div>
          </div>
          <div class="total-badge">Total GAD Budget: <strong>₱2.5M</strong></div>
        </div>
      </div>

      <!-- Additional Activity Categories Section -->
      <div class="graph-card" style="margin-top: 0;">
        <h3 class="graph-title">📊 Activity Category Distribution</h3>
        <p class="graph-subtitle">Breakdown across four mandatory GAD categories</p>
        <div class="flex-between" style="gap: 40px;">
          <div class="graph-container" style="height: 220px; width: 220px; flex-shrink: 0;"><canvas id="chart-category"></canvas></div>
          <div class="pie-legend">
            <div class="pie-legend-item"><div class="pie-legend-color" style="background:#4B1FA8"></div> INSET <span style="margin-left: auto; font-weight: 700;">11 activities (29%)</span></div>
            <div class="pie-legend-item"><div class="pie-legend-color" style="background:#2ECFB3"></div> Employees' Activity <span style="margin-left: auto; font-weight: 700;">9 activities (24%)</span></div>
            <div class="pie-legend-item"><div class="pie-legend-color" style="background:#5BC4F5"></div> Extension <span style="margin-left: auto; font-weight: 700;">8 activities (21%)</span></div>
            <div class="pie-legend-item"><div class="pie-legend-color" style="background:#F26B8A"></div> Student Orientations <span style="margin-left: auto; font-weight: 700;">10 activities (26%)</span></div>
          </div>
        </div>
        <div class="total-badge">Total GAD Activities: <strong>38</strong></div>
      </div>

      <footer>
        BSU GAD-IMS · Analytics Dashboard · Data represents FY 2025–2026 · Updated in real-time
      </footer>
    </div>
  </main>

  <script>
    // Chart.js configuration
    Chart.defaults.font.family = "'DM Sans', sans-serif";
    Chart.defaults.font.size = 11;
    Chart.defaults.color = '#4A3870';
    
    const V = '#4B1FA8';
    const VM = '#6B3FD4';
    const VL = '#9B72EF';
    const GLD = '#F5C843';
    const TEAL = '#2ECFB3';
    const ROSE = '#F26B8A';
    const SKY = '#5BC4F5';

    // Budget bar chart
    new Chart(document.getElementById('chart-budget'), {
      type: 'bar', 
      data: { 
        labels: ['CNS', 'CPAS', 'CSS', 'CTE', 'CVM', 'OU'], 
        datasets: [
          { label: 'Approved (₱K)', data: [320, 280, 250, 300, 190, 420], backgroundColor: VM, borderRadius: 8, barPercentage: 0.65 },
          { label: 'Actual (₱K)', data: [295, 240, 230, 310, 160, 380], backgroundColor: GLD, borderRadius: 8, barPercentage: 0.65 }
        ]
      }, 
      options: { 
        responsive: true, 
        maintainAspectRatio: true, 
        plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } }, 
        scales: { y: { ticks: { callback: v => '₱' + v + 'K', font: { size: 11 } } }, x: { ticks: { font: { size: 11 } } } }
      }
    });
    
    // Compliance horizontal bar
    new Chart(document.getElementById('chart-compliance'), { 
      type: 'bar', 
      data: { 
        labels: ['CNS', 'CPAS', 'CSS', 'CTE', 'CVM', 'OU'], 
        datasets: [{ label: 'Activities', data: [7, 6, 5, 5, 4, 3], backgroundColor: VM, borderRadius: 8 }] 
      }, 
      options: { 
        indexAxis: 'y', 
        responsive: true, 
        maintainAspectRatio: true, 
        plugins: { legend: { display: false } }, 
        scales: { x: { ticks: { stepSize: 1, font: { size: 11 } } }, y: { ticks: { font: { size: 11, weight: '500' } } } }
      } 
    });
    
    // SDD Enrollment per College (Horizontal Bar)
    const colleges = ['CA', 'CAH', 'CE', 'CF', 'CHET', 'CHK', 'CIS', 'CN', 'CNAS', 'CNS', 'CPAS', 'CSS', 'CTE', 'CVM', 'OU'];
    const maleData = [961, 422, 698, 245, 443, 474, 451, 182, 160, 342, 263, 155, 433, 135, 105];
    const femaleData = [1050, 1159, 657, 245, 1426, 371, 546, 886, 234, 749, 543, 512, 1855, 429, 196];
    
    new Chart(document.getElementById('chart-enrollment'), {
      type: 'bar',
      data: { 
        labels: colleges, 
        datasets: [
          { label: 'Male', data: maleData, backgroundColor: '#6B3FD4', borderRadius: 6, barPercentage: 0.7 },
          { label: 'Female', data: femaleData, backgroundColor: '#F5C843', borderRadius: 6, barPercentage: 0.7 }
        ]
      },
      options: { 
        indexAxis: 'y', 
        responsive: true, 
        maintainAspectRatio: true, 
        plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } }, 
        scales: { x: { ticks: { stepSize: 500, font: { size: 10 } } }, y: { ticks: { font: { size: 10, weight: '500' } } } }
      }
    });
    
    // Expense doughnut
    new Chart(document.getElementById('chart-expense'), { 
      type: 'doughnut', 
      data: { 
        labels: ['Supplies', 'Meals', 'Tokens', 'Travel'], 
        datasets: [{ data: [480, 725, 340, 955], backgroundColor: [V, GLD, TEAL, ROSE], borderWidth: 0 }] 
      }, 
      options: { 
        responsive: true, 
        maintainAspectRatio: true, 
        cutout: '55%', 
        plugins: { legend: { display: false } },
        layout: { padding: 10 }
      } 
    });
    
    // Activity Category donut
    new Chart(document.getElementById('chart-category'), { 
      type: 'doughnut', 
      data: { 
        labels: ['INSET', "Employees' Activity", 'Extension', 'Student Orientations'], 
        datasets: [{ data: [11, 9, 8, 10], backgroundColor: [V, TEAL, SKY, ROSE], borderWidth: 0 }] 
      }, 
      options: { 
        responsive: true, 
        maintainAspectRatio: true, 
        cutout: '55%', 
        plugins: { legend: { display: false } },
        layout: { padding: 10 }
      } 
    });
  </script>
</body>
</html>