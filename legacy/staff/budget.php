<?php
// budget.php - Budget Realignment & Utilization (Mandate-Based GAD Plan Alignment)
require_once '../auth.php';
require_role(['gad_staff', 'admin']);
$page_title = 'Budget Monitoring';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Budget Monitoring | GAD-IMS</title>
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

        main {
            margin-left: 35px;

        }

        .material-symbols-outlined {
            display: inline-block;
            font-size: 24px;
            font-family: monospace;
        }

        .progress-bar {
            transition: width 0.5s ease;
        }

        /* Layout */
        .pl-56 {
            padding-left: 224px;
        }

        .pt-16 {
            padding-top: 64px;
        }

        .min-h-screen {
            min-height: 100vh;
        }

        .p-8 {
            padding: 32px;
        }

        .space-y-8>*+* {
            margin-top: 32px;
        }

        .justify-between {
            justify-content: space-between;
        }

        .items-center {
            align-items: center;
        }

        .flex {
            display: flex;
        }

        .bg-990dd1 {
            background-color: #990dd1;
        }

        .text-white {
            color: white;
        }

        .px-5 {
            padding-left: 20px;
            padding-right: 20px;
        }

        .py-2\.5 {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .rounded-xl {
            border-radius: 12px;
        }

        .text-sm {
            font-size: 14px;
        }

        .font-bold {
            font-weight: 700;
        }

        .transition {
            transition: all 0.2s ease;
        }

        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .md\:grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .gap-6 {
            gap: 24px;
        }

        .bg-gradient-to-br {
            background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
        }

        .from-990dd1 {
            --tw-gradient-from: #990dd1;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(153, 13, 209, 0));
        }

        .to-b979cc {
            --tw-gradient-to: #b979cc;
        }

        .rounded-2xl {
            border-radius: 16px;
        }

        .p-6 {
            padding: 24px;
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .text-4xl {
            font-size: 36px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .opacity-80 {
            opacity: 0.8;
        }

        .text-2xl {
            font-size: 24px;
        }

        .border-t {
            border-top: 1px solid #e2e8f0;
        }

        .border-white\/30 {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .text-10px {
            font-size: 10px;
        }

        .from-5170ff {
            --tw-gradient-from: #5170ff;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(81, 112, 255, 0));
        }

        .to-002c5c {
            --tw-gradient-to: #002c5c;
        }

        .lg\:grid-cols-10 {
            grid-template-columns: repeat(10, minmax(0, 1fr));
        }

        .lg\:col-span-7 {
            grid-column: span 7 / span 7;
        }

        .bg-white {
            background-color: white;
        }

        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .border {
            border: 1px solid #e2e8f0;
        }

        .border-slate-100 {
            border-color: #f1f5f9;
        }

        .overflow-hidden {
            overflow: hidden;
        }

        .divide-y>*+* {
            border-top-width: 1px;
        }

        .divide-slate-100>*+* {
            border-color: #f1f5f9;
        }

        .hover\:bg-slate-50\/30:hover {
            background-color: rgba(248, 250, 252, 0.3);
        }

        .text-3b3b3b {
            color: #3b3b3b;
        }

        .text-000 {
            color: #000;
        }

        .text-base {
            font-size: 16px;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .text-right {
            text-align: right;
        }

        .font-medium {
            font-weight: 500;
        }

        .text-3f6516 {
            color: #3f6516;
        }

        .text-lg {
            font-size: 18px;
        }

        .gap-3 {
            gap: 12px;
        }

        .h-2 {
            height: 8px;
        }

        .bg-slate-100 {
            background-color: #f1f5f9;
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .w-full {
            width: 100%;
        }

        .text-xs {
            font-size: 12px;
        }

        .font-semibold {
            font-weight: 600;
        }

        .bg-slate-50\/20 {
            background-color: rgba(248, 250, 252, 0.2);
        }

        .lg\:col-span-3 {
            grid-column: span 3 / span 3;
        }

        .space-y-6>*+* {
            margin-top: 24px;
        }

        .p-5 {
            padding: 20px;
        }

        .border-b {
            border-bottom: 1px solid #e2e8f0;
        }

        .bg-slate-50\/50 {
            background-color: rgba(248, 250, 252, 0.5);
        }

        .font-black {
            font-weight: 900;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .tracking-wider {
            letter-spacing: 0.05em;
        }

        .text-990dd1 {
            color: #990dd1;
        }

        .p-4 {
            padding: 16px;
        }

        .text-11px {
            font-size: 11px;
        }

        .font-mono {
            font-family: monospace;
        }

        .space-y-3>*+* {
            margin-top: 12px;
        }

        .pt-2 {
            padding-top: 8px;
        }

        .border-t {
            border-top: 1px solid #e2e8f0;
        }

        .text-xl {
            font-size: 20px;
        }

        .text-purple-200 {
            color: #d8b4fe;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .pt-3 {
            padding-top: 12px;
        }

        .fixed {
            position: fixed;
        }

        .bottom-4 {
            bottom: 16px;
        }

        .left-56 {
            left: 224px;
        }

        .right-0 {
            right: 0;
        }

        .text-center {
            text-align: center;
        }

        .pointer-events-none {
            pointer-events: none;
        }

        .z-40 {
            z-index: 40;
        }

        .text-9px {
            font-size: 9px;
        }

        .opacity-20 {
            opacity: 0.2;
        }

        .bg-gradient-to-r {
            background-image: linear-gradient(to right, var(--tw-gradient-stops));
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .ml-4 {
            margin-left: 16px;
        }

        .mr-2 {
            margin-right: 8px;
        }

        .mb-6 {
            margin-bottom: 24px;
        }

        .mt-3 {
            margin-top: 12px;
        }

        /* Progress Bar Colors */
        .progress-green {
            background-color: #3f6516;
        }

        .progress-yellow {
            background-color: #ecd224;
        }

        .progress-purple {
            background-color: #990dd1;
        }

        /* Header Styles */
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

        .gap-2 {
            gap: 8px;
        }

        .gap-4 {
            gap: 16px;
        }

        .relative {
            position: relative;
        }

        /* Middle Card Special Colors - Emerald/Gold Theme */
        .middle-card {
            background: linear-gradient(135deg, #990dd1 0%, #b979cc 50%, #5170ff 100%);
            position: relative;
            overflow: hidden;
        }

        .middle-card::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .middle-card::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        @media (min-width: 768px) {
            .md\:grid-cols-3 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .lg\:grid-cols-10 {
                grid-template-columns: repeat(10, minmax(0, 1fr));
            }

            .lg\:col-span-7 {
                grid-column: span 7 / span 7;
            }

            .lg\:col-span-3 {
                grid-column: span 3 / span 3;
            }
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <header class="staff-header">
        <div>
            <h2 class="header-title">Budget Monitoring</h2>
            <div class="flex items-center gap-2"><span class="header-subtitle">Gender and Development - Information Management System (GAD-IMS)</span></div>
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
    <main class="pl-56 pt-16 min-h-screen">
        <div class="p-8 space-y-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-000 tracking-tight">Budget Monitoring</h1>
                    <p class="text-3b3b3b text-sm mt-1">GAD Plan & Budget Alignment monitoring by mandate</p>
                </div><a href="budget_utilization.php" class="bg-990dd1 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition flex items-center gap-2 shadow-md cursor-pointer">✏️ Edit TWG Budget Utilization</a>
            </div>
            <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card 1: Total GAD Budget -->
                <div class="bg-gradient-to-br from-990dd1 to-b979cc rounded-2xl p-6 text-white shadow-lg">
                    <p class="text-sm font-medium uppercase tracking-wider opacity-80">Total GAD Budget</p>
                    <p class="text-4xl font-bold mt-2">₱243,541,951</p>
                    <p class="text-sm opacity-80 mt-2">FY 2026 Approved GAD Allocation</p>
                </div>
                <!-- Card 2: GAD Office Budget - NEW COLORFUL DESIGN -->
                <div class="middle-card rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium uppercase tracking-wider opacity-90">GAD Office Budget</p>
                        <span class="text-2xl opacity-80">🏛️</span>
                    </div>
                    <p class="text-4xl font-bold mt-2">₱74,521,951</p>
                    <p class="text-sm opacity-90 mt-2">22 GAD Office-led mandates</p>
                    <div class="mt-3 pt-2 border-t border-white/30">
                        <p class="text-10px opacity-80">✨ 30.6% of total GAD budget</p>
                    </div>
                </div>
                <!-- Card 3: Balance Available -->
                <div class="bg-gradient-to-br from-5170ff to-002c5c rounded-2xl p-6 text-white shadow-lg">
                    <p class="text-sm font-medium uppercase tracking-wider opacity-80">Balance Available</p>
                    <p class="text-4xl font-bold mt-2">₱175,326,951</p>
                    <p class="text-sm opacity-80 mt-2">Remaining for Q2-Q4 implementation</p>
                </div>
            </section>
            <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">
                <div class="lg:col-span-7 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="divide-y divide-slate-100">
                        <!-- Mandate 1 -->
                        <div class="p-6 hover:bg-slate-50/30 transition">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-000 text-base">MANDATE 1: AFFIRMATIVE ACTION / FREE TUITION (RA 10931)</h5>
                                    <p class="text-sm text-3b3b3b mt-1">Free tuition, disadvantaged student assistance</p>
                                </div>
                                <div class="text-right"><span class="text-sm font-medium text-3b3b3b">Utilized: </span><span class="text-lg font-bold text-3f6516">₱45,800,000</span><span class="text-sm text-3b3b3b"> / ₱131,800,000</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-990dd1 rounded-full progress-bar" style="width:34.7%"></div>
                                </div><span class="text-xs font-semibold text-3b3b3b">34.7% utilized</span>
                            </div>
                        </div>
                        <!-- Mandate 2 -->
                        <div class="p-6 hover:bg-slate-50/30 transition bg-slate-50/20">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-000 text-base">MANDATE 2: GAD ORIENTATION & STUDENT LEADERSHIP TRAINING</h5>
                                    <p class="text-sm text-3b3b3b mt-1">GAD orientation (4,000 students), leadership training for student leaders</p>
                                </div>
                                <div class="text-right"><span class="text-sm font-medium text-3b3b3b">Utilized: </span><span class="text-lg font-bold text-3f6516">₱210,000</span><span class="text-sm text-3b3b3b"> / ₱453,363</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-ecd224 rounded-full progress-bar" style="width:46.3%"></div>
                                </div><span class="text-xs font-semibold text-3b3b3b">46.3% utilized</span>
                            </div>
                        </div>
                        <!-- Mandate 3 -->
                        <div class="p-6 hover:bg-slate-50/30 transition">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-000 text-base">MANDATE 3: GREP EXTENSION / COMMUNITY PROGRAMS</h5>
                                    <p class="text-sm text-3b3b3b mt-1">24 extension activities, technology transfer, livelihood programs</p>
                                </div>
                                <div class="text-right"><span class="text-sm font-medium text-3b3b3b">Utilized: </span><span class="text-lg font-bold text-3f6516">₱1,250,000</span><span class="text-sm text-3b3b3b"> / ₱3,500,000</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-3f6516 rounded-full progress-bar" style="width:35.7%"></div>
                                </div><span class="text-xs font-semibold text-3b3b3b">35.7% utilized</span>
                            </div>
                        </div>
                        <!-- Mandate 4 -->
                        <div class="p-6 hover:bg-slate-50/30 transition bg-slate-50/20">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-000 text-base">MANDATE 4: CHILD MINDING CENTER / BREASTFEEDING STATIONS</h5>
                                    <p class="text-sm text-3b3b3b mt-1">Maintenance of child minding centers, lactation rooms across campuses</p>
                                </div>
                                <div class="text-right"><span class="text-sm font-medium text-3b3b3b">Utilized: </span><span class="text-lg font-bold text-3f6516">₱180,000</span><span class="text-sm text-3b3b3b"> / ₱450,000</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-5170ff rounded-full progress-bar" style="width:40%"></div>
                                </div><span class="text-xs font-semibold text-3b3b3b">40% utilized</span>
                            </div>
                        </div>
                        <!-- Mandate 5 -->
                        <div class="p-6 hover:bg-slate-50/30 transition">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-000 text-base">MANDATE 5: GFPS CAPACITY BUILDING & GAD TRAININGS</h5>
                                    <p class="text-sm text-3b3b3b mt-1">Gender Mainstreaming capability building, GAD seminars for personnel</p>
                                </div>
                                <div class="text-right"><span class="text-sm font-medium text-3b3b3b">Utilized: </span><span class="text-lg font-bold text-3f6516">₱396,000</span><span class="text-sm text-3b3b3b"> / ₱3,896,000</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-990dd1 rounded-full progress-bar" style="width:10.2%"></div>
                                </div><span class="text-xs font-semibold text-3b3b3b">10.2% utilized</span>
                            </div>
                        </div>
                        <!-- Mandate 6 -->
                        <div class="p-6 hover:bg-slate-50/30 transition bg-slate-50/20">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-000 text-base">MANDATE 6: CRISIS INTERVENTION / HYGIENE KITS</h5>
                                    <p class="text-sm text-3b3b3b mt-1">Gender-responsive services to students/employees in crisis</p>
                                </div>
                                <div class="text-right"><span class="text-sm font-medium text-3b3b3b">Utilized: </span><span class="text-lg font-bold text-3f6516">₱200,000</span><span class="text-sm text-3b3b3b"> / ₱210,000</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-ecd224 rounded-full progress-bar" style="width:95.2%"></div>
                                </div><span class="text-xs font-semibold text-3b3b3b">95.2% utilized</span>
                            </div>
                        </div>
                        <!-- Mandate 7 -->
                        <div class="p-6 hover:bg-slate-50/30 transition">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-000 text-base">MANDATE 7: GENDER-RESPONSIVE CURRICULAR PROGRAMS</h5>
                                    <p class="text-sm text-3b3b3b mt-1">Integration of gender perspective in syllabi and classroom teaching</p>
                                </div>
                                <div class="text-right"><span class="text-sm font-medium text-3b3b3b">Utilized:</span><span class="text-lg font-bold text-3f6516">₱51,294,973</span><span class="text-sm text-3b3b3b"> / ₱58,294,973</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-3f6516 rounded-full progress-bar" style="width:88%"></div>
                                </div><span class="text-xs font-semibold text-3b3b3b">88% utilized</span>
                            </div>
                        </div>
                    </div>
                </div>
                <aside class="lg:col-span-3 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                            <h5 class="text-sm font-black uppercase tracking-wider text-3b3b3b flex items-center gap-2"><span class="text-base">📜</span> Recent Budget Utilization Logs</h5><a href="budget_utilization.php" class="text-990dd1 text-xs font-medium transition cursor-pointer">Edit →</a>
                        </div>
                        <div class="divide-y divide-slate-100">
                            <div class="p-4 hover:bg-slate-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-xs font-bold text-990dd1">MAR 25, 2026</p>
                                        <p class="text-sm font-bold text-000 mt-1">GAD Orientation for First Year Students</p>
                                        <p class="text-xs text-3b3b3b mt-0.5">Mandate: GAD Orientation & Student Leadership</p>
                                    </div>
                                    <p class="text-sm font-bold text-3f6516">₱453,363</p>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-slate-50 transition bg-slate-50/20">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-xs font-bold text-990dd1">MAR 22, 2026</p>
                                        <p class="text-sm font-bold text-000 mt-1">Affirmative Action Program - Student Assistance</p>
                                        <p class="text-xs text-3b3b3b mt-0.5">Mandate: Affirmative Action / Free Tuition</p>
                                        <p class="text-10px text-3b3b3b font-mono mt-0.5">Control: GAD-2026-076</p>
                                    </div>
                                    <p class="text-sm font-bold text-3f6516">₱700,000</p>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-slate-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-xs font-bold text-990dd1">MAR 18, 2026</p>
                                        <p class="text-sm font-bold text-000 mt-1">Distribution of Hygiene Kits (Crisis Intervention)</p>
                                        <p class="text-xs text-3b3b3b mt-0.5">Mandate: Crisis Intervention / Hygiene Kits</p>
                                        <p class="text-10px text-3b3b3b font-mono mt-0.5">Control: GAD-2026-054</p>
                                    </div>
                                    <p class="text-sm font-bold text-3f6516">₱200,000</p>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-slate-50 transition bg-slate-50/20">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-xs font-bold text-990dd1">MAR 15, 2026</p>
                                        <p class="text-sm font-bold text-000 mt-1">GFPS Capacity Building Training</p>
                                        <p class="text-xs text-3b3b3b mt-0.5">Mandate: GFPS Capacity Building & GAD Trainings</p>
                                        <p class="text-10px text-3b3b3b font-mono mt-0.5">Control: GAD-2026-042</p>
                                    </div>
                                    <p class="text-sm font-bold text-3f6516">₱396,000</p>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-slate-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-xs font-bold text-990dd1">MAR 10, 2026</p>
                                        <p class="text-sm font-bold text-000 mt-1">GREP Extension: Community Technology Transfer</p>
                                        <p class="text-xs text-3b3b3b mt-0.5">Mandate: GREP Extension / Community Programs</p>
                                        <p class="text-10px text-3b3b3b font-mono mt-0.5">Control: GAD-2026-038</p>
                                    </div>
                                    <p class="text-sm font-bold text-3f6516">₱1,250,000</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-990dd1 to-b979cc rounded-xl p-5 text-white">
                        <div class="flex items-center gap-2 mb-3"><span class="text-2xl">💰</span><span class="text-xs font-bold uppercase tracking-wider">Budget Status</span></div>
                        <p class="text-2xl font-bold">₱175.33M</p>
                        <p class="text-sm text-purple-200 mt-1">Remaining for Q2-Q4</p>
                        <div class="mt-4 pt-3 border-t border-white/30">
                            <p class="text-xs">22 GAD Office mandates | 28 total mandates tracked</p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
    <div class="fixed bottom-4 left-56 right-0 text-center pointer-events-none z-40">
        <p class="text-9px text-3b3b3b/20 font-medium">Benguet State University - Gender and Development Information Management System | GAD-IMS v1.0</p>
    </div>
</body>

</html>