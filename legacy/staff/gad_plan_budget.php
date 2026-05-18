<?php
// staff/gad_plan_budget.php - GAD Plan & Budget Viewer (Clean White/Purple Design)
require_once '../auth.php';
require_role(['gad_staff', 'admin']);
$page_title = 'GAD Plan & Budget';
include 'sidebar.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GAD Plan & Budget | GAD-IMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Layout */
        .pl-64 {
            padding-left: 256px;
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

        .mb-6 {
            margin-bottom: 24px;
        }

        .flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between;
        }

        .items-center {
            align-items: center;
        }

        .gap-3 {
            gap: 12px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .w-10 {
            width: 40px;
        }

        .h-10 {
            height: 40px;
        }

        .bg-purple-700 {
            background-color: #6b21e5;
        }

        .rounded-xl {
            border-radius: 12px;
        }

        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .text-white {
            color: white;
        }

        .text-2xl {
            font-size: 24px;
        }

        .font-bold {
            font-weight: 700;
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

        .border {
            border: 1px solid #e2e8f0;
        }

        .border-purple-200 {
            border-color: #e9d5ff;
        }

        .rounded-lg {
            border-radius: 8px;
        }

        .px-3 {
            padding-left: 12px;
            padding-right: 12px;
        }

        .py-2 {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .bg-white {
            background-color: white;
        }

        .focus\:ring-2:focus {
            outline: none;
            ring: 2px solid;
        }

        .focus\:ring-purple-500:focus {
            ring-color: #8b5cf6;
        }

        .focus\:border-purple-500:focus {
            border-color: #8b5cf6;
        }

        .bg-emerald-600 {
            background-color: #059669;
        }

        .hover\:bg-emerald-700:hover {
            background-color: #047857;
        }

        .transition {
            transition: all 0.2s ease;
        }

        .gap-2 {
            gap: 8px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .gap-2 {
            gap: 8px;
        }

        .px-5 {
            padding-left: 20px;
            padding-right: 20px;
        }

        .py-2 {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .tab-active {
            background: #7c3aed;
            color: white;
        }

        .tab-inactive {
            background: #e2e8f0;
            color: #475569;
        }

        .tab-inactive:hover {
            background: #cbd5e1;
            cursor: pointer;
        }

        .hidden {
            display: none;
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

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .pdf-viewer {
            background: #f8fafc;
            border-radius: 12px;
            overflow: hidden;
        }

        .bg-purple-50 {
            background-color: #faf5ff;
        }

        .text-purple-700 {
            color: #6d28d9;
        }

        .text-purple-800 {
            color: #5b21b6;
        }

        .text-xs {
            font-size: 12px;
        }

        .font-medium {
            font-weight: 500;
        }

        .bg-slate-200 {
            background-color: #e2e8f0;
        }

        .hover\:bg-slate-300:hover {
            background-color: #cbd5e1;
        }

        .border-0 {
            border: 0;
        }

        .h-800px {
            height: 800px;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .inline-block {
            display: inline-block;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mb-1 {
            margin-bottom: 4px;
        }

        /* Table Styles */
        .gpb-table {
            border-collapse: collapse;
            width: 100%;
        }

        .gpb-table th,
        .gpb-table td {
            border: 1px solid #e2e8f0;
            padding: 10px 12px;
            vertical-align: top;
            font-size: 12px;
        }

        .gpb-table th {
            background: #6b21e5;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            color: white;
            text-align: center;
        }

        .section-header {
            background: #f3e8ff;
            font-weight: 700;
            color: #5b21b6;
        }

        .hover-row:hover {
            background-color: #faf5ff;
            cursor: pointer;
        }

        .text-purple-700 {
            color: #6d28d9;
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

        .relative {
            position: relative;
        }

        .gap-2 {
            gap: 8px;
        }

        .gap-4 {
            gap: 16px;
        }

        /* Watermark */
        .fixed {
            position: fixed;
        }

        .bottom-4 {
            bottom: 16px;
        }

        .left-64 {
            left: 256px;
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

        .text-10px {
            font-size: 10px;
        }

        .opacity-20 {
            opacity: 0.2;
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
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
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
    </style>
</head>

<body>
    <header class="staff-header">
        <div>
            <h2 class="header-title">GAD Plan and Budget</h2>
            <div class="flex items-center gap-2">
                <span class="header-subtitle">Gender and Development - Information Management System (GAD-IMS)</span>
            </div>
        </div>
        <div class="flex items-center gap-4" style="color: #3b3b3b;">
            <div class="relative">
                <button id="headerNotificationBtn" class="notif-btn">
                    <span style="font-family: monospace; font-size: 24px;">🔔</span>
                </button>
                <span class="notif-badge"></span>
            </div>
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
        <div class="notif-footer">
            <button>View all notifications</button>
        </div>
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
<body>
    <header class="staff-header">
        <div>
            <h2 class="header-title">GAD Plan & Budget</h2>
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
    <?php include 'sidebar.php'; ?>
    <main class="pl-64 pt-16 min-h-screen">
        <div class="p-8">
            <div class="mb-6 flex justify-between items-center">
                <div>
                </div>
                <div class="flex items-center gap-3">
                    <form method="GET" action="" class="inline-block"><select name="year" id="yearSelect" class="border border-purple-200 rounded-lg px-3 py-2 text-sm bg-white">
                            <option value="2026" <?php echo (!isset($_GET['year']) || $_GET['year'] == '2026') ? 'selected' : ''; ?>>2026</option>
                            <option value="2025" <?php echo (isset($_GET['year']) && $_GET['year'] == '2025') ? 'selected' : ''; ?>>2025</option>
                            <option value="2024" <?php echo (isset($_GET['year']) && $_GET['year'] == '2024') ? 'selected' : ''; ?>>2024</option>
                        </select></form><button onclick="exportToExcel()" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center gap-2 shadow-md cursor-pointer">📊 Export to Excel</button>
                </div>
            </div>
            <div class="mb-4 flex gap-2"><a href="?view=table<?php echo isset($_GET['year']) ? '&year=' . $_GET['year'] : ''; ?>" class="px-5 py-2 rounded-lg text-sm font-medium transition-all shadow-sm <?php echo (!isset($_GET['view']) || $_GET['view'] == 'table') ? 'tab-active' : 'tab-inactive'; ?>">📋 Table View</a><a href="?view=pdf<?php echo isset($_GET['year']) ? '&year=' . $_GET['year'] : ''; ?>" class="px-5 py-2 rounded-lg text-sm font-medium transition-all <?php echo (isset($_GET['view']) && $_GET['view'] == 'pdf') ? 'tab-active' : 'tab-inactive'; ?>">📄 PDF Document</a></div>
            <?php if (!isset($_GET['view']) || $_GET['view'] == 'table'): ?>
                <div class="bg-white rounded-xl shadow-md border border-purple-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="gpb-table w-full">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>GENDER ISSUE / GAD MANDATE</th>
                                    <th>CAUSE OF GENDER ISSUE</th>
                                    <th>GAD RESULT STATEMENT / GAD OBJECTIVE</th>
                                    <th>RELEVANT ORGANIZATION MFO/PAP OR PPA</th>
                                    <th>GAD ACTIVITY</th>
                                    <th>PERFORMANCE INDICATORS / TARGETS</th>
                                    <th>GAD BUDGET</th>
                                    <th>SOURCE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $year = isset($_GET['year']) ? $_GET['year'] : '2026';
                                $clientFocused = [
                                    ['id' => 1, 'mandate' => "RA 10931 - Affirmative Action Program", 'cause' => "Extraordinary life situations due to disasters, calamities, and socio-cultural discrimination", 'result' => "To promote equitable access and participation of both women and men from GIDAs in tertiary education", 'mfo' => "Higher Education Program", 'activity' => "Implementation of Affirmative Action Agenda", 'indicators' => "Number of served disadvantaged students - 100% disadvantaged students", 'budget' => "₱700,000", 'source' => "GAA"],
                                    ['id' => 2, 'mandate' => "RA 10931 - Free Tuition Provision", 'cause' => "High tuition and miscellaneous fees, compounded by socio-cultural expectations for women", 'result' => "To promote gender equality in access to tertiary education", 'mfo' => "Higher Education Program", 'activity' => "Provision of free tuition fee under RA 10931", 'indicators' => "100% of qualified students granted free tuition", 'budget' => "₱131,100,000", 'source' => "GAA"],
                                    ['id' => 3, 'mandate' => "CHED Memo 01 s.2015 - GAD Orientation", 'cause' => "Limited activities to increase awareness of men and women students", 'result' => "To increase the students level of awareness and appreciation on GAD", 'mfo' => "Higher Education Program", 'activity' => "Conduct GAD orientation/forum/seminar to students", 'indicators' => "4,000 students oriented on GAD (F-2750 M-1250)", 'budget' => "₱453,363", 'source' => "GAA"],
                                    ['id' => 4, 'mandate' => "CHED Memo 01 s.2015 - Student Leadership Training", 'cause' => "Student leaders have limited understanding on GAD", 'result' => "To empower student leaders regarding GAD responsive leadership", 'mfo' => "Higher Education Program", 'activity' => "Continuous conduct of GAD responsive leadership training", 'indicators' => "2 training conducted (F:200 M:100)", 'budget' => "₱150,000", 'source' => "GAA"],
                                    ['id' => 5, 'mandate' => "GREP Extension Program", 'cause' => "Presence of gender inequality, poverty and GAD-related concerns", 'result' => "To sustain GAD-related extension activities", 'mfo' => "Extension Services", 'activity' => "Conduct of Extension project/activities", 'indicators' => "24 extension activities conducted (F:560 M:500)", 'budget' => "₱3,500,000", 'source' => "GAA"],
                                    ['id' => 6, 'mandate' => "PWD Programs", 'cause' => "Limited access of PWDs to gender-responsive programs", 'result' => "Improved access of PWDs to gender-responsive programs", 'mfo' => "Research Services", 'activity' => "Gender-responsive programs for PWDs", 'indicators' => "Number of PWDs who benefited", 'budget' => "₱350,000", 'source' => "GAA"],
                                    ['id' => 7, 'mandate' => "Senior Citizens Program", 'cause' => "Absence of senior citizens access to sustainable programs", 'result' => "Improved access of senior citizens to gender-responsive programs", 'mfo' => "Extension Services", 'activity' => "BSU Kalinga for women Senior Citizens", 'indicators' => "Programs provided for Senior Citizens", 'budget' => "₱250,000", 'source' => "GAA"]
                                ];
                                $orgFocused = [
                                    ['id' => 8, 'mandate' => "GAD Mainstreaming Capability Building", 'cause' => "Low awareness among personnel about GAD mainstreaming", 'result' => "To enhance GAD mainstreaming in Administration, Academic, Research and Extension", 'mfo' => "Research Services", 'activity' => "Conduct GAD related Gender Mainstreaming capability building", 'indicators' => "25 training/workshop/seminars conducted (F:1500 M:1000)", 'budget' => "₱4,000,000", 'source' => "GAA"],
                                    ['id' => 9, 'mandate' => "GFPS Capacity Building", 'cause' => "Low level of capacity of GFPS members", 'result' => "Capacitated GFPS members to implement GAD PAPs", 'mfo' => "Research Services", 'activity' => "GMFE/HGDG/GPB/GAD Deepening Session", 'indicators' => "At least 1 training per GFPS member (F:31 M:15)", 'budget' => "₱396,000", 'source' => "GAA"],
                                    ['id' => 10, 'mandate' => "Gender Sensitivity Training", 'cause' => "Lack of regular orientation on gender sensitivity", 'result' => "To enhance awareness of gender concepts among personnel", 'mfo' => "Higher Education Program", 'activity' => "Conduct Gender Sensitivity Training", 'indicators' => "1 training for newly hired, 3 refresher trainings (F:50 M:20)", 'budget' => "₱421,728", 'source' => "GAA"],
                                    ['id' => 11, 'mandate' => "Child Minding Center", 'cause' => "Problems of parents and students related to child care", 'result' => "Ensure access to agency care services for children", 'mfo' => "Research Services", 'activity' => "Maintenance of Child Minding Center", 'indicators' => "Fully maintained child minding centers", 'budget' => "₱230,000", 'source' => "GAA"],
                                    ['id' => 12, 'mandate' => "Breastfeeding Station", 'cause' => "Inadequate support for breastfeeding mothers", 'result' => "Adequate support services for personnel with children", 'mfo' => "Research Services", 'activity' => "Establishment/maintenance of breastfeeding stations", 'indicators' => "100% fully maintained lactation rooms", 'budget' => "₱220,000", 'source' => "GAA"],
                                    ['id' => 13, 'mandate' => "GAD Database System", 'cause' => "No centralized GAD database", 'result' => "Institutionalized GAD database system", 'mfo' => "Higher Education Program", 'activity' => "Institutionalize GAD database and Sex-Disaggregated Database", 'indicators' => "Fully functional GAD database", 'budget' => "₱500,000", 'source' => "GAA"],
                                    ['id' => 14, 'mandate' => "VAW Campaigns", 'cause' => "Need to highlight women's rights and provide platform to invoke protection", 'result' => "To strengthen awareness on women's rights", 'mfo' => "Research Services", 'activity' => "Participation to 18-Day Campaign to end VAW and Women's Month", 'indicators' => "At least one activity conducted per campus", 'budget' => "₱450,000", 'source' => "GAA"],
                                    ['id' => 15, 'mandate' => "IEC Materials Development", 'cause' => "Presence of Gender Based Violence issues", 'result' => "Institutionalize GAD mechanisms and sustain awareness campaigns", 'mfo' => "Research Services", 'activity' => "Development and Dissemination of GAD IEC Materials", 'indicators' => "Official Publication of BSU with GAD articles", 'budget' => "₱296,000", 'source' => "GAA"]
                                ];
                                ?>
                                <tr class="section-header">
                                    <td colspan="9" class="font-bold">CLIENT-FOCUSED ACTIVITIES</td>
                                </tr>
                                <?php foreach ($clientFocused as $idx => $item): ?>
                                    <tr class="hover-row">
                                        <td class="text-center"><?php echo $idx + 1; ?></td>
                                        <td><?php echo htmlspecialchars($item['mandate']); ?></td>
                                        <td><?php echo htmlspecialchars($item['cause']); ?></td>
                                        <td><?php echo htmlspecialchars($item['result']); ?></td>
                                        <td><?php echo htmlspecialchars($item['mfo']); ?></td>
                                        <td><?php echo htmlspecialchars($item['activity']); ?></td>
                                        <td><?php echo htmlspecialchars($item['indicators']); ?></td>
                                        <td class="text-right font-medium text-purple-700"><?php echo $item['budget']; ?></td>
                                        <td class="text-center"><?php echo $item['source']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="section-header">
                                    <td colspan="9" class="font-bold">ORGANIZATION-FOCUSED ACTIVITIES</td>
                                </tr>
                                <?php foreach ($orgFocused as $idx => $item): ?>
                                    <tr class="hover-row">
                                        <td class="text-center"><?php echo count($clientFocused) + $idx + 1; ?></td>
                                        <td><?php echo htmlspecialchars($item['mandate']); ?></td>
                                        <td><?php echo htmlspecialchars($item['cause']); ?></td>
                                        <td><?php echo htmlspecialchars($item['result']); ?></td>
                                        <td><?php echo htmlspecialchars($item['mfo']); ?></td>
                                        <td><?php echo htmlspecialchars($item['activity']); ?></td>
                                        <td><?php echo htmlspecialchars($item['indicators']); ?></td>
                                        <td class="text-right font-medium text-purple-700"><?php echo $item['budget']; ?></td>
                                        <td class="text-center"><?php echo $item['source']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="section-header">
                                    <td colspan="7" class="font-bold text-right">TOTAL GAD BUDGET:</td>
                                    <td class="font-bold">₱243,541,951</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="pdf-viewer">
                    <div class="bg-white rounded-xl shadow-md border border-purple-100 overflow-hidden">
                        <div class="p-4 border-b bg-purple-50 flex justify-between items-center">
                            <div class="flex items-center gap-2"><span class="text-purple-700 text-2xl">📄</span>
                                <h3 class="font-bold text-purple-800">2026 GAD Plan and Budget (GPB) Document</h3>
                            </div>
                            <div class="flex gap-2"><a href="uploads/2026-GPB.pdf" target="_blank" class="bg-purple-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-purple-800 transition flex items-center gap-1 shadow-sm cursor-pointer">🔗 Open Full PDF</a><a href="uploads/2026-GPB.pdf" download class="bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-slate-300 transition flex items-center gap-1 cursor-pointer">⬇️ Download</a></div>
                        </div>
                        <div class="p-0"><iframe src="uploads/2026-GPB.pdf" class="w-full border-0" style="min-height: 600px; height: 800px; background: white;"></iframe></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <div class="fixed bottom-4 left-64 right-0 text-center pointer-events-none z-40">
        <p class="text-10px text-3b3b3b/20 font-medium">Benguet State University - Gender and Development Information Management System | GAD-IMS v1.0</p>
    </div>
    <script>
        function exportToExcel() {
            alert('Exporting GAD Plan & Budget to Excel...');
        }
        document.getElementById('yearSelect')?.addEventListener('change', function() {
            window.location.href = '?year=' + this.value + '<?php echo isset($_GET['view']) ? '&view=' . $_GET['view'] : ''; ?>';
        });
    </script>
</body>

</html>