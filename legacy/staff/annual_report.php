<?php
// staff/annual_report.php - Annual Report Page (Clean White/Purple Design)
require_once '../auth.php';
require_role(['gad_staff', 'staff']);
$page_title = 'Annual Report';
include 'sidebar.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Annual Report | GAD-IMS</title>
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

        .overflow-hidden {
            overflow: hidden;
        }

        .overflow-x-auto {
            overflow-x: auto;
        }

        .w-full {
            width: 100%;
        }

        .report-table {
            border-collapse: collapse;
            width: 100%;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #e2e8f0;
            padding: 10px 12px;
            vertical-align: top;
            font-size: 12px;
        }

        .report-table th {
            background: #6b21e5;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
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
        }

        .text-center {
            text-align: center;
        }

        .bg-purple-50 {
            background-color: #faf5ff;
        }

        .text-purple-800 {
            color: #5b21b6;
        }

        .text-purple-600 {
            color: #7c3aed;
        }

        .text-xs {
            font-size: 12px;
        }

        .font-medium {
            font-weight: 500;
        }

        .hover\:text-purple-800:hover {
            color: #5b21b6;
        }

        .cursor-pointer {
            cursor: pointer;
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
            <h2 class="header-title">Report Monitoring</h2>
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
    <?php include 'sidebar.php'; ?>
    <main class="pl-64 pt-16 min-h-screen">
        <div class="p-8">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 bg-purple-700 rounded-xl flex items-center justify-center shadow-md"><span class="text-white text-2xl">📄</span></div>
                        <h1 class="text-2xl font-bold text-slate-800">Annual Report</h1>
                    </div>
                    <p class="text-slate-500 text-sm">GAD Accomplishment Report for Fiscal Year 2026</p>
                </div>
                <div class="flex items-center gap-3">
                    <select id="yearSelect" class="border border-purple-200 rounded-lg px-3 py-2 text-sm bg-white">
                        <option value="2026">2026</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                    </select>
                    <button onclick="exportToExcel()" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center gap-2 shadow-md">📊 Export to Excel</button>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-md border border-purple-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="report-table w-full">
                        <thead>
                            <tr>
                                <th rowspan="2" class="w-8 text-center">#</th>
                                <th rowspan="2">GENDER ISSUE / GAD MANDATE</th>
                                <th rowspan="2">CAUSE OF GENDER ISSUE</th>
                                <th rowspan="2">GAD RESULT STATEMENT / GAD OBJECTIVE</th>
                                <th rowspan="2">GAD ACTIVITY</th>
                                <th rowspan="2">PERFORMANCE INDICATORS / TARGETS</th>
                                <th rowspan="2">TARGET RESULT</th>
                                <th colspan="2">ATTENDANCE</th>
                                <th rowspan="2">ACTIONS</th>
                            </tr>
                            <tr>
                                <th class="text-center">MALE</th>
                                <th class="text-center">FEMALE</th>
                            </tr>
                        </thead>
                        <tbody id="reportTableBody"></tbody>
                        <tfoot>
                            <tr class="section-header">
                                <td colspan="7" class="font-bold">CLIENT-FOCUSED MANDATES</td>
                                <td class="text-center font-bold" id="clientMaleTotal">0</td>
                                <td class="text-center font-bold" id="clientFemaleTotal">0</td>
                                <td></td>
                            </tr>
                            <tr class="section-header">
                                <td colspan="7" class="font-bold">ORGANIZATION-FOCUSED MANDATES</td>
                                <td class="text-center font-bold" id="orgMaleTotal">0</td>
                                <td class="text-center font-bold" id="orgFemaleTotal">0</td>
                                <td></td>
                            </tr>
                            <tr class="bg-purple-50">
                                <td colspan="7" class="font-bold text-purple-800">SUB-TOTAL CLIENT-FOCUSED + ORGANIZATION-FOCUSED ACTIVITIES</td>
                                <td class="text-center font-bold text-purple-800" id="subtotalMale">0</td>
                                <td class="text-center font-bold text-purple-800" id="subtotalFemale">0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <script>
        const annualReportData = {
            2026: {
                clientFocused: [{
                    id: 1,
                    mandate: "RA 10931 - Affirmative Action Program",
                    cause: "Extraordinary life situations due to disasters, calamities, and socio-cultural discrimination",
                    result: "To promote equitable access and participation of both women and men from GIDAs in tertiary education",
                    activity: "Implementation of Affirmative Action Agenda",
                    indicators: "Number of served disadvantaged students - 100%",
                    targetResult: "100% of disadvantaged students served",
                    male: 45,
                    female: 55
                }, {
                    id: 2,
                    mandate: "RA 10931 - Free Tuition Provision",
                    cause: "High tuition and miscellaneous fees, compounded by socio-cultural expectations for women",
                    result: "To promote gender equality in access to tertiary education",
                    activity: "Provision of free tuition fee under RA 10931",
                    indicators: "Percentage of qualified students granted free tuition - 100%",
                    targetResult: "100% of qualified students",
                    male: 1250,
                    female: 2750
                }, {
                    id: 3,
                    mandate: "CHED Memo 01 s.2015 - GAD Orientation",
                    cause: "Limited activities to increase awareness of men and women students",
                    result: "To increase the students level of awareness and appreciation on GAD",
                    activity: "Conduct GAD orientation/forum/seminar to students",
                    indicators: "No. of students oriented on GAD - 4,000 students",
                    targetResult: "4,000 students oriented",
                    male: 1250,
                    female: 2750
                }, {
                    id: 4,
                    mandate: "CHED Memo 01 s.2015 - Student Leadership Training",
                    cause: "Student leaders have limited understanding on GAD",
                    result: "To empower student leaders regarding GAD responsive leadership",
                    activity: "Continuous conduct of GAD responsive leadership training",
                    indicators: "2 training conducted (F:200 M:100)",
                    targetResult: "300 student leaders trained",
                    male: 100,
                    female: 200
                }, {
                    id: 5,
                    mandate: "GREP Extension Program",
                    cause: "Presence of gender inequality, poverty and GAD-related concerns",
                    result: "To sustain GAD-related extension activities",
                    activity: "Conduct of Extension project/activities",
                    indicators: "24 extension activities conducted",
                    targetResult: "24 activities completed",
                    male: 500,
                    female: 560
                }, {
                    id: 6,
                    mandate: "PWD Programs",
                    cause: "Limited access of PWDs to gender-responsive programs",
                    result: "Improved access of PWDs to gender-responsive programs",
                    activity: "Gender-responsive programs for PWDs",
                    indicators: "Number of PWDs who benefited",
                    targetResult: "100 PWDs served",
                    male: 40,
                    female: 60
                }, {
                    id: 7,
                    mandate: "Senior Citizens Program",
                    cause: "Absence of senior citizens access to sustainable programs",
                    result: "Improved access of senior citizens to gender-responsive programs",
                    activity: "BSU Kalinga for women Senior Citizens",
                    indicators: "Programs provided for Senior Citizens",
                    targetResult: "1 program implemented",
                    male: 23,
                    female: 21
                }],
                orgFocused: [{
                    id: 8,
                    mandate: "GAD Mainstreaming Capability Building",
                    cause: "Low awareness among personnel about GAD mainstreaming",
                    result: "To enhance GAD mainstreaming in Administration, Academic, Research and Extension",
                    activity: "Conduct GAD related Gender Mainstreaming capability building",
                    indicators: "25 training/workshop/seminars conducted",
                    targetResult: "2,500 personnel trained",
                    male: 1000,
                    female: 1500
                }, {
                    id: 9,
                    mandate: "GFPS Capacity Building",
                    cause: "Low level of capacity of GFPS members",
                    result: "Capacitated GFPS members to implement GAD PAPs",
                    activity: "GMFE/HGDG/GPB/GAD Deepening Session",
                    indicators: "At least 1 training per GFPS member",
                    targetResult: "46 GFPS members trained",
                    male: 15,
                    female: 31
                }, {
                    id: 10,
                    mandate: "Gender Sensitivity Training",
                    cause: "Lack of regular orientation on gender sensitivity",
                    result: "To enhance awareness of gender concepts among personnel",
                    activity: "Conduct Gender Sensitivity Training",
                    indicators: "1 training for newly hired, 3 refresher trainings",
                    targetResult: "100% newly hired trained",
                    male: 20,
                    female: 50
                }, {
                    id: 11,
                    mandate: "Child Minding Center",
                    cause: "Problems of parents and students related to child care",
                    result: "Ensure access to agency care services for children",
                    activity: "Maintenance of Child Minding Center",
                    indicators: "Fully maintained child minding centers",
                    targetResult: "3 centers maintained",
                    male: 0,
                    female: 0
                }, {
                    id: 12,
                    mandate: "Breastfeeding Station",
                    cause: "Inadequate support for breastfeeding mothers",
                    result: "Adequate support services for personnel with children",
                    activity: "Establishment/maintenance of breastfeeding stations",
                    indicators: "100% fully maintained lactation rooms",
                    targetResult: "3 stations maintained",
                    male: 0,
                    female: 0
                }, {
                    id: 13,
                    mandate: "GAD Database System",
                    cause: "No centralized GAD database",
                    result: "Institutionalized GAD database system",
                    activity: "Institutionalize GAD database system",
                    indicators: "Fully functional GAD database",
                    targetResult: "1 system implemented",
                    male: 0,
                    female: 0
                }]
            },
            2025: {
                clientFocused: [{
                    id: 1,
                    mandate: "RA 10931 - Affirmative Action Program",
                    cause: "Extraordinary life situations due to disasters",
                    result: "To promote equitable access to tertiary education",
                    activity: "Implementation of Affirmative Action Agenda",
                    indicators: "Number of served disadvantaged students",
                    targetResult: "95% of disadvantaged students served",
                    male: 38,
                    female: 52
                }, {
                    id: 2,
                    mandate: "RA 10931 - Free Tuition Provision",
                    cause: "High tuition fees",
                    result: "To promote gender equality in access to education",
                    activity: "Provision of free tuition fee",
                    indicators: "Percentage of qualified students granted free tuition",
                    targetResult: "98% of qualified students",
                    male: 1200,
                    female: 2600
                }],
                orgFocused: [{
                    id: 3,
                    mandate: "GAD Mainstreaming Capability Building",
                    cause: "Low awareness among personnel",
                    result: "To enhance GAD mainstreaming",
                    activity: "Conduct Gender Mainstreaming capability building",
                    indicators: "20 training/workshops conducted",
                    targetResult: "2,000 personnel trained",
                    male: 800,
                    female: 1200
                }]
            }
        };

        function renderReport() {
            const year = document.getElementById('yearSelect').value;
            const data = annualReportData[year] || annualReportData[2026];
            const tbody = document.getElementById('reportTableBody');
            let clientMaleTotal = 0,
                clientFemaleTotal = 0,
                orgMaleTotal = 0,
                orgFemaleTotal = 0;
            let html = '';
            data.clientFocused.forEach((item, idx) => {
                clientMaleTotal += item.male;
                clientFemaleTotal += item.female;
                html += `<tr class="hover-row"><td class="text-center">${idx + 1}</td><td>${escapeHtml(item.mandate)}</td><td>${escapeHtml(item.cause)}</td><td>${escapeHtml(item.result)}</td><td>${escapeHtml(item.activity)}</td><td>${escapeHtml(item.indicators)}</td><td>${escapeHtml(item.targetResult)}</td><td class="text-center">${item.male.toLocaleString()}</td><td class="text-center">${item.female.toLocaleString()}</td><td class="text-center"><button onclick="viewDetails(${item.id})" class="text-purple-600 text-xs font-medium hover:text-purple-800 transition cursor-pointer">View</button></td></tr>`;
            });
            data.orgFocused.forEach((item, idx) => {
                orgMaleTotal += item.male;
                orgFemaleTotal += item.female;
                html += `<tr class="hover-row"><td class="text-center">${data.clientFocused.length + idx + 1}</td><td>${escapeHtml(item.mandate)}</td><td>${escapeHtml(item.cause)}</td><td>${escapeHtml(item.result)}</td><td>${escapeHtml(item.activity)}</td><td>${escapeHtml(item.indicators)}</td><td>${escapeHtml(item.targetResult)}</td><td class="text-center">${item.male.toLocaleString()}</td><td class="text-center">${item.female.toLocaleString()}</td><td class="text-center"><button onclick="viewDetails(${item.id})" class="text-purple-600 text-xs font-medium hover:text-purple-800 transition cursor-pointer">View</button></td></tr>`;
            });
            tbody.innerHTML = html;
            document.getElementById('clientMaleTotal').textContent = clientMaleTotal.toLocaleString();
            document.getElementById('clientFemaleTotal').textContent = clientFemaleTotal.toLocaleString();
            document.getElementById('orgMaleTotal').textContent = orgMaleTotal.toLocaleString();
            document.getElementById('orgFemaleTotal').textContent = orgFemaleTotal.toLocaleString();
            document.getElementById('subtotalMale').textContent = (clientMaleTotal + orgMaleTotal).toLocaleString();
            document.getElementById('subtotalFemale').textContent = (clientFemaleTotal + orgFemaleTotal).toLocaleString();
        }

        function viewDetails(id) {
            alert(`View details for mandate ID: ${id}`);
        }

        function exportToExcel() {
            alert(`Exporting Annual Report for ${document.getElementById('yearSelect').value} to Excel...`);
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                return m === '&' ? '&amp;' : (m === '<' ? '&lt;' : '&gt;');
            });
        }
        document.getElementById('yearSelect').addEventListener('change', renderReport);
        renderReport();
    </script>
</body>

</html>