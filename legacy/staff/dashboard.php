<?php
// staff/dashboard.php - Staff Dashboard with Calendar and Metrics (No JS)
require_once '../auth.php';
require_role(['gad_staff', 'admin']);
$page_title = 'Staff Operations Hub';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Staff Dashboard | GAD-IMS</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background-color: #f8f9fb;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .pl-64 {
      padding-left: 256px;
    }

    .pt-16 {
      padding-top: 64px;
    }

    .min-h-screen {
      min-height: 100vh;
    }

    .p-10 {
      padding: 40px;
    }

    .mb-8 {
      margin-bottom: 32px;
    }

    .mt-4 {
      margin-top: 16px;
    }

    .text-2rem {
      font-size: 2rem;
    }

    .font-extrabold {
      font-weight: 800;
    }

    .tracking-tighter {
      letter-spacing: -0.05em;
    }

    .leading-tight {
      line-height: 1.25;
    }

    .mb-1 {
      margin-bottom: 4px;
    }

    .text-slate-800 {
      color: #1e293b;
    }

    .text-slate-500 {
      color: #64748b;
    }

    .text-sm {
      font-size: 14px;
    }

    .font-light {
      font-weight: 300;
    }

    .grid {
      display: grid;
    }

    .grid-cols-5 {
      grid-template-columns: repeat(5, minmax(0, 1fr));
    }

    .gap-4 {
      gap: 16px;
    }

    .mb-10 {
      margin-bottom: 5px;
    }

    .p-4 {
      padding: 16px;
    }

    .bg-slate-100 {
      background-color: #f1f5f9;
    }

    .rounded-2xl {
      border-radius: 16px;
    }

    .stat-card {
      transition: all 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-4px);
    }

    .items-center {
      align-items: center;
    }

    .justify-between {
      justify-content: space-between;
    }

    .min-h-90 {
      min-height: 90px;
    }

    .text-07rem {
      font-size: 0.7rem;
    }

    .font-bold {
      font-weight: 700;
    }

    .uppercase {
      text-transform: uppercase;
    }

    .tracking-wider {
      letter-spacing: 0.05em;
    }

    .text-slate-400 {
      color: #94a3b8;
    }

    .text-3xl {
      font-size: 30px;
    }

    .text-slate-800 {
      color: #1e293b;
    }

    .tracking-tighter {
      letter-spacing: -0.05em;
    }

    .mt-1 {
      margin-top: 4px;
    }

    .text-10px {
      font-size: 10px;
    }

    .font-medium {
      font-weight: 500;
    }

    .text-amber-500 {
      color: #f59e0b;
    }

    .text-purple-500 {
      color: #8b5cf6;
    }

    .text-emerald-500 {
      color: #10b981;
    }

    .text-blue-500 {
      color: #3b82f6;
    }

    .text-xl {
      font-size: 20px;
    }

    .bg-gradient-to-br {
      background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
    }

    .from-purple-50 {
      --tw-gradient-from: #faf5ff;
      --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(250, 245, 255, 0));
    }

    .to-indigo-50 {
      --tw-gradient-to: #eef2ff;
    }

    .border {
      border: 1px solid #e2e8f0;
    }

    .border-purple-100 {
      border-color: #f3e8ff;
    }

    .text-purple-700 {
      color: #6d28d9;
    }

    .text-2xl {
      font-size: 24px;
    }

    .text-purple-800 {
      color: #5b21b6;
    }

    .w-16 {
      width: 64px;
    }

    .bg-purple-200 {
      background-color: #e9d5ff;
    }

    .rounded-full {
      border-radius: 9999px;
    }

    .h-1 {
      height: 4px;
    }

    .bg-purple-600 {
      background-color: #7c3aed;
    }

    .text-purple-600 {
      color: #7c3aed;
    }

    .text-3xl {
      font-size: 30px;
    }

    .grid-cols-10 {
      grid-template-columns: repeat(10, minmax(0, 1fr));
    }

    .gap-10 {
      gap: 40px;
    }

    .col-span-10 {
      grid-column: span 10 / span 10;
    }

    .lg\:col-span-7 {
      grid-column: span 7 / span 7;
    }


    .text-2xl {
      font-size: 24px;
    }

    .bg-white {
      background-color: white;
    }

    .rounded-xl {
      border-radius: 12px;
    }

    .shadow-sm {
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .overflow-hidden {
      overflow: hidden;
    }

    .overflow-x-auto {
      overflow-x: auto;
    }

    .w-full {
      width: 100%;
    }

    .text-left {
      text-align: left;
    }

    .bg-slate-50 {
      background-color: #f8fafc;
    }

    .border-b {
      border-bottom: 1px solid #e2e8f0;
    }

    .px-6 {
      padding-left: 24px;
      padding-right: 24px;
    }

    .py-4 {
      padding-top: 16px;
      padding-bottom: 16px;
    }

    .font-black {
      font-weight: 900;
    }

    .text-slate-400 {
      color: #94a3b8;
    }

    .text-slate-600 {
      color: #475569;
    }

    .divide-y>*+* {
      border-top-width: 1px;
      border-color: #e2e8f0;
    }

    .divide-slate-100>*+* {
      border-color: #f1f5f9;
    }

    .clickable-row {
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .clickable-row:hover {
      background-color: #f3e8ff;
    }

    .inline-block {
      display: inline-block;
    }

    .px-3 {
      padding-left: 12px;
      padding-right: 12px;
    }

    .py-1 {
      padding-top: 4px;
      padding-bottom: 4px;
    }

    .bg-purple-100 {
      background-color: #f3e8ff;
    }

    .text-purple-700 {
      color: #6d28d9;
    }

    .bg-emerald-100 {
      background-color: #d1fae5;
    }

    .text-emerald-700 {
      color: #047857;
    }

    .text-xs {
      font-size: 12px;
    }

    .border-t {
      border-top: 1px solid #e2e8f0;
    }

    .bg-slate-50\/50 {
      background-color: rgba(248, 250, 252, 0.5);
    }

    .text-purple-600 {
      color: #7c3aed;
    }

    .hover\:underline:hover {
      text-decoration: underline;
    }

    .lg\:col-span-3 {
      grid-column: span 3 / span 3;
    }

    .space-y-6 {
      display: flex;
      flex-direction: column;
      gap: 24px;
    }

    .p-6 {
      padding: 24px;
    }

    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 4px;
      text-align: center;
      margin-bottom: 8px;
    }

    .calendar-weekday {
      font-size: 10px;
      font-weight: 700;
      color: #94a3b8;
    }

    .calendar-dates {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 4px;
      text-align: center;
      font-size: 14px;
    }

    .calendar-day {
      padding: 8px;
    }

    .calendar-day-text-slate-300 {
      color: #cbd5e1;
    }

    .deadline-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      padding: 8px;
      border-radius: 8px;
    }

    .deadline-dot {
      width: 6px;
      height: 6px;
      border-radius: 9999px;
      margin-top: 8px;
    }

    .bg-red-500 {
      background-color: #ef4444;
    }

    .bg-amber-500 {
      background-color: #f59e0b;
    }

    .bg-emerald-500 {
      background-color: #10b981;
    }

    .deadline-title {
      font-size: 12px;
      font-weight: 700;
      color: #1e293b;
    }

    .deadline-date {
      font-size: 10px;
      color: #94a3b8;
    }

    .deadline-badge {
      font-size: 10px;
      font-weight: 700;
      padding: 2px 8px;
      border-radius: 9999px;
    }

    .deadline-badge-urgent {
      background: #fee2e2;
      color: #991b1b;
    }

    .deadline-badge-warning {
      background: #fef3c7;
      color: #b45309;
    }

    .deadline-badge-normal {
      background: #d1fae5;
      color: #065f46;
    }

    .activity-log-item {
      display: flex;
      gap: 12px;
    }

    .log-icon {
      width: 24px;
      height: 24px;
      border-radius: 9999px;
      background-color: #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .log-action {
      font-size: 12px;
      font-weight: 500;
    }

    .log-time {
      font-size: 10px;
      color: #94a3b8;
    }

    .fixed {
      position: fixed;
    }

    .inset-0 {
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
    }

    .bg-black\/50 {
      background-color: rgba(0, 0, 0, 0.5);
    }

    .z-100 {
      z-index: 100;
    }

    .hidden {
      display: none;
    }

    .flex {
      display: flex;
    }

    .max-w-md {
      max-width: 448px;
    }

    .mx-4 {
      margin-left: 16px;
      margin-right: 16px;
    }

    .shadow-2xl {
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .rounded-2xl {
      border-radius: 16px;
    }

    .text-xl {
      font-size: 20px;
    }

    .text-slate-800 {
      color: #1e293b;
    }

    .mb-6 {
      margin-bottom: 24px;
    }

    .text-slate-400 {
      color: #94a3b8;
    }

    .hover:text-slate-600:hover {
      color: #475569;
    }

    .space-y-3>*+* {
      margin-top: 12px;
    }

    .w-12 {
      width: 48px;
    }

    .h-12 {
      height: 48px;
    }

    .bg-purple-100 {
      background-color: #f3e8ff;
    }

    .group-hover\:bg-purple-200:hover {
      background-color: #e9d5ff;
    }

    .text-purple-600 {
      color: #7c3aed;
    }

    .text-2xl {
      font-size: 24px;
    }

    .flex-1 {
      flex: 1;
    }

    .text-slate-800 {
      color: #1e293b;
    }

    .text-slate-500 {
      color: #64748b;
    }

    .group-hover\:text-purple-600:hover {
      color: #7c3aed;
    }

    .bg-emerald-100 {
      background-color: #d1fae5;
    }

    .group-hover\:bg-emerald-200:hover {
      background-color: #a7f3d0;
    }

    .text-emerald-600 {
      color: #059669;
    }

    .group-hover\:text-emerald-600:hover {
      color: #059669;
    }

    .cursor-pointer {
      cursor: pointer;
    }

    @media (min-width: 1024px) {
      .lg\:col-span-7 {
        grid-column: span 7 / span 7;
      }

      .lg\:col-span-3 {
        grid-column: span 3 / span 3;
      }
    }

    .staff-header {
      position: fixed;
      top: 0;
      left: 256px;
      right: 0;
      height: 80px;
      background-color: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(8px);
      z-index: 40;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 40px;
      border-bottom: 1px solid rgba(226, 232, 240, 0.4);
    }

    .header-title {
      font-size: 24px;
      font-weight: 800;
      color: #002c5c;
      letter-spacing: -0.025em;
      line-height: 1;
    }

    .header-subtitle {
      font-size: 9px;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: #3b3b3b;
      font-weight: 600;
    }

    .notif-btn {
      padding: 8px;
      background: transparent;
      border: none;
      border-radius: 9999px;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .notif-btn:hover {
      background-color: rgba(185, 121, 204, 0.1);
    }

    .notif-badge {
      position: absolute;
      top: 4px;
      right: 4px;
      width: 8px;
      height: 8px;
      background-color: #ef4444;
      border-radius: 9999px;
    }

    .notif-panel {
      position: fixed;
      top: 64px;
      right: 40px;
      width: 320px;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
      border: 1px solid #e2e8f0;
      z-index: 50;
      display: none;
    }

    .notif-panel.show {
      display: block;
    }

    .notif-header {
      padding: 16px;
      border-bottom: 1px solid #e2e8f0;
    }

    .notif-header h3 {
      font-weight: 700;
      color: #000;
    }

    .notif-list {
      max-height: 384px;
      overflow-y: auto;
    }

    .notif-item {
      padding: 16px;
      border-bottom: 1px solid #f1f5f9;
      cursor: pointer;
    }

    .notif-item:hover {
      background-color: rgba(185, 121, 204, 0.1);
    }

    .notif-item.bg-amber {
      background-color: #fffbeb;
    }

    .notif-title {
      font-size: 11px;
      font-weight: 500;
      color: #000;
    }

    .notif-time {
      font-size: 10px;
      color: #3b3b3b;
    }

    .notif-footer {
      padding: 12px;
      border-top: 1px solid #e2e8f0;
      background-color: #f8fafc;
      text-align: center;
    }

    .notif-footer button {
      background: none;
      border: none;
      font-size: 10px;
      font-weight: 700;
      color: #990dd1;
      cursor: pointer;
    }

    .relative {
      position: relative;
    }

    .gap-2 {
      gap: 8px;
    }

    .gap-4 {
      gap: 16px;
    }
  </style>
</head>

<body>
  <?php include 'sidebar.php'; ?>
  <header class="staff-header">
    <div>
      <h2 class="header-title">Gender and Development - Information Management System (GAD-IMS)</h2>
      <div class="flex items-center gap-2"><span class="header-subtitle">Gender and Development Office - Staff</span></div>
    </div>
    <div class="flex items-center gap-4" style="color:#3b3b3b;">
      <div class="relative"><button id="headerNotificationBtn" class="notif-btn"><span style="font-family:monospace;font-size:24px;">🔔</span></button><span class="notif-badge"></span></div>
    </div>
  </header>
  <div id="headerNotificationPanel" class="notif-panel">
    <div class="notif-header">
      <h3>Notifications</h3>
    </div>
    <div class="notif-list">
      <div class="notif-item">
        <p class="notif-title">New activity design submitted</p>
        <p class="notif-time">College of Nursing · 5 min ago</p>
      </div>
      <div class="notif-item bg-amber">
        <p class="notif-title">Budget routing requires attention</p>
        <p class="notif-time">Extension Office · 1 hour ago</p>
      </div>
      <div class="notif-item">
        <p class="notif-title">Accomplishment report verified</p>
        <p class="notif-time">HR Office · 3 hours ago</p>
      </div>
    </div>
    <div class="notif-footer"><button>View all notifications</button></div>
  </div>
  <script>
    const notifBtn = document.getElementById('headerNotificationBtn');
    const notifPanel = document.getElementById('headerNotificationPanel');
    if (notifBtn && notifPanel) {
      notifBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        notifPanel.classList.toggle('show');
      });
      document.addEventListener('click', function(e) {
        if (!notifPanel.contains(e.target) && !notifBtn.contains(e.target)) notifPanel.classList.remove('show');
      });
    }
  </script>
  <main class="pl-64 pt-16 min-h-screen">
    <div class="p-10">
      <section class="grid grid-cols-5 gap-4 mb-10">
        <div class="p-4 bg-slate-100 rounded-2xl flex items-center justify-between stat-card min-h-90">
          <div><span class="text-07rem font-bold text-slate-500 uppercase tracking-wider">Pending Activities</span>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tighter mt-1">12</div>
            <p class="text-10px text-slate-500 font-medium">Awaiting review</p>
          </div><span class="text-amber-500 text-3xl">⏳</span>
        </div>
        <div class="p-4 bg-slate-100 rounded-2xl flex items-center justify-between stat-card min-h-90">
          <div><span class="text-07rem font-bold text-slate-500 uppercase tracking-wider">Activity Designs</span>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tighter mt-1">08</div>
            <p class="text-10px text-slate-500 font-medium">Pending approval</p>
          </div><span class="text-purple-500 text-3xl">📄</span>
        </div>
        <div class="p-4 bg-slate-100 rounded-2xl flex items-center justify-between stat-card min-h-90">
          <div><span class="text-07rem font-bold text-slate-500 uppercase tracking-wider">Accomplishment</span>
            <div class="text-3xl font-extrabold text-slate-800 tracking-tighter mt-1">04</div>
            <p class="text-10px text-slate-500 font-medium">Unverified</p>
          </div><span class="text-emerald-500 text-3xl">📊</span>
        </div>
        <div class="p-4 bg-slate-100 rounded-2xl flex items-center justify-between stat-card min-h-90">
          <div><span class="text-07rem font-bold text-slate-500 uppercase tracking-wider">GAD Budget</span>
            <div class="text-xl font-extrabold text-slate-800 tracking-tighter mt-1">₱74.5M</div>
            <p class="text-10px text-slate-500 font-medium">FY 2026 Allocation</p>
          </div><span class="text-blue-500 text-3xl">💰</span>
        </div>
        <div class="p-4 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-2xl flex items-center justify-between stat-card border border-purple-100 min-h-90">
          <div><span class="text-07rem font-bold text-purple-700 uppercase tracking-wider">% of GAD Allocation</span>
            <div class="text-2xl font-extrabold text-purple-800 tracking-tighter mt-1">22.92%</div>
            <div class="mt-1 w-16 bg-purple-200 rounded-full h-1">
              <div class="bg-purple-600 h-1 rounded-full" style="width:22.92%"></div>
            </div>
          </div><span class="text-purple-600 text-3xl">📊</span>
        </div>
      </section>
      <section class="grid grid-cols-10 gap-10">
        <div class="col-span-10 lg:col-span-7 space-y-6">
          <div class="flex justify-between items-center">
            <div><br>
              <h4 class="text-2xl font-bold text-slate-800 tracking-tight">Recent Pending Activities</h4>
            </div>
          </div>
          <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
              <table class="w-full text-left">
                <thead>
                  <tr class="bg-slate-50 border-b">
                    <th class="px-6 py-4 text-10px font-black uppercase tracking-wider text-slate-400">Activity Title</th>
                    <th class="px-6 py-4 text-10px font-black uppercase tracking-wider text-slate-400">Office / Unit</th>
                    <th class="px-6 py-4 text-10px font-black uppercase tracking-wider text-slate-400">Type</th>
                    <th class="px-6 py-4 text-10px font-black uppercase tracking-wider text-slate-400">Date Submitted</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr class="clickable-row" data-type="design" data-id="1" onclick="window.location.href='design_view.php?id=1'">
                    <td class="px-6 py-4 font-medium text-slate-800">Lorem ipsum dolor sit amet</td>
                    <td class="px-6 py-4 text-slate-600">Office of Student Services</td>
                    <td class="px-6 py-4"><span class="inline-block px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-10px font-black uppercase">Activity Design</span></td>
                    <td class="px-6 py-4 text-slate-500">2025-03-15</td>
                  </tr>
                  <tr class="clickable-row" data-type="accomplishment" data-id="2" onclick="window.location.href='ar_view.php?id=2'">
                    <td class="px-6 py-4 font-medium text-slate-800">Lorem ipsum dolor sit amet</td>
                    <td class="px-6 py-4 text-slate-600">College of Human Kinetics</td>
                    <td class="px-6 py-4"><span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-10px font-black uppercase">Accomplishment Report</span></td>
                    <td class="px-6 py-4 text-slate-500">2025-03-20</td>
                  </tr>
                  <tr class="clickable-row" data-type="design" data-id="3" onclick="window.location.href='design_view.php?id=3'">
                    <td class="px-6 py-4 font-medium text-slate-800">Lorem ipsum dolor sit amet</td>
                    <td class="px-6 py-4 text-slate-600">Research and Extension Office</td>
                    <td class="px-6 py-4"><span class="inline-block px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-10px font-black uppercase">Activity Design</span></td>
                    <td class="px-6 py-4 text-slate-500">2025-03-18</td>
                  </tr>
                  <tr class="clickable-row" data-type="accomplishment" data-id="4" onclick="window.location.href='ar_view.php?id=4'">
                    <td class="px-6 py-4 font-medium text-slate-800">Lorem ipsum dolor sit amet</td>
                    <td class="px-6 py-4 text-slate-600">Information Communication Technology Office</td>
                    <td class="px-6 py-4"><span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-10px font-black uppercase">Accomplishment Report</span></td>
                    <td class="px-6 py-4 text-slate-500">2025-03-22</td>
                  </tr>
                  <tr class="clickable-row" data-type="design" data-id="5" onclick="window.location.href='design_view.php?id=5'">
                    <td class="px-6 py-4 font-medium text-slate-800">Lorem ipsum dolor sit amet</td>
                    <td class="px-6 py-4 text-slate-600">Office of University Registrar</td>
                    <td class="px-6 py-4"><span class="inline-block px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-10px font-black uppercase">Activity Design</span></td>
                    <td class="px-6 py-4 text-slate-500">2025-03-10</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center">
              <p class="text-xs text-slate-500">Showing 5 of 12 pending activities</p><a href="employee_submissions.php?filter=pending" class="text-purple-600 text-xs font-bold uppercase tracking-wider hover:underline">View All →</a>
            </div>
          </div>
        </div>
        <div class="col-span-10 lg:col-span-3 space-y-6">
          <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <div class="flex items-center justify-between mb-6">
              <h4 class="font-bold text-slate-800 flex items-center gap-2"><span class="text-purple-600">📅</span> Deadlines & Schedule</h4>
              <div class="flex gap-1"><span class="p-1 rounded text-sm">←</span><span class="text-sm font-medium">Mar 2026</span><span class="p-1 rounded text-sm">→</span></div>
            </div>
            <div class="calendar-grid"><span class="calendar-weekday">S</span><span class="calendar-weekday">M</span><span class="calendar-weekday">T</span><span class="calendar-weekday">W</span><span class="calendar-weekday">T</span><span class="calendar-weekday">F</span><span class="calendar-weekday">S</span></div>
            <div class="calendar-dates"><span class="calendar-day calendar-day-text-slate-300">1</span><span class="calendar-day">2</span><span class="calendar-day">3</span><span class="calendar-day">4</span><span class="calendar-day">5</span><span class="calendar-day">6</span><span class="calendar-day">7</span><span class="calendar-day">8</span><span class="calendar-day">9</span><span class="calendar-day">10</span><span class="calendar-day">11</span><span class="calendar-day">12</span><span class="calendar-day">13</span><span class="calendar-day">14</span><span class="calendar-day">15</span><span class="calendar-day">16</span><span class="calendar-day">17</span><span class="calendar-day">18</span><span class="calendar-day">19</span><span class="calendar-day">20</span><span class="calendar-day">21</span><span class="calendar-day">22</span><span class="calendar-day">23</span><span class="calendar-day">24</span><span class="calendar-day calendar-day-text-slate-300">25</span><span class="calendar-day calendar-day-text-slate-300">26</span><span class="calendar-day calendar-day-text-slate-300">27</span><span class="calendar-day calendar-day-text-slate-300">28</span><span class="calendar-day calendar-day-text-slate-300">29</span><span class="calendar-day calendar-day-text-slate-300">30</span><span class="calendar-day calendar-day-text-slate-300">31</span></div>
            <div class="border-t pt-4 mt-4"><br>
              <h5 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Upcoming Deadlines</h5>
              <div class="space-y-3">
                <div class="deadline-item">
                  <div class="deadline-dot bg-red-500"></div>
                  <div class="flex-1">
                    <p class="deadline-title">Lorem ipsum dolor sit amet</p>
                    <p class="deadline-date">3/25/2026</p>
                  </div><span class="deadline-badge deadline-badge-urgent">Due in 2 days</span>
                </div>
                <div class="deadline-item">
                  <div class="deadline-dot bg-amber-500"></div>
                  <div class="flex-1">
                    <p class="deadline-title">Lorem ipsum dolor sit amet</p>
                    <p class="deadline-date">3/27/2026</p>
                  </div><span class="deadline-badge deadline-badge-warning">Due in 4 days</span>
                </div>
                <div class="deadline-item">
                  <div class="deadline-dot bg-emerald-500"></div>
                  <div class="flex-1">
                    <p class="deadline-title">Lorem ipsum dolor sit amet</p>
                    <p class="deadline-date">3/30/2026</p>
                  </div><span class="deadline-badge deadline-badge-normal">Due in 7 days</span>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h5 class="text-10px font-black uppercase tracking-wider text-slate-400 mb-4 flex items-center gap-2"><span class="text-base">📜</span> Recent Activity Logs</h5>
            <div class="space-y-3">
              <div class="activity-log-item">
                <div class="log-icon">✅</div>
                <div>
                  <p class="log-action">BSU-GAD-2025-042 verified</p>
                  <p class="log-time">2 hours ago</p>
                </div>
              </div>
              <div class="activity-log-item">
                <div class="log-icon">⏳</div>
                <div>
                  <p class="log-action">New intake from College of Nursing</p>
                  <p class="log-time">Yesterday</p>
                </div>
              </div>
              <div class="activity-log-item">
                <div class="log-icon">📤</div>
                <div>
                  <p class="log-action">Budget routing completed</p>
                  <p class="log-time">Mar 25, 2026</p>
                </div>
              </div>
              <div class="activity-log-item">
                <div class="log-icon">✅</div>
                <div>
                  <p class="log-action">Activity Design #AD-004 approved</p>
                  <p class="log-time">Mar 24, 2026</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </main>
  
</body>

</html>