<?php
// staff/annual_report.php - Annual Report Page (Clean White/Purple Design with Clickable Rows)
require_once '../auth.php';
require_role(['gad_staff', 'admin']);
$page_title = 'Annual Report';
include 'sidebar.php';
// include 'header.php';
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
            font-family: 'Segoe UI', Roboto, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .material-symbols-outlined {
            display: inline-block;
            font-size: 24px;
            width: 24px;
            height: 24px;
            line-height: 1;
            font-family: monospace;
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
            font-size: 11px;
        }

        .report-table th {
            background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            color: white;
            text-align: center;
        }

        .section-header {
            background: #b979cc15;
            font-weight: 700;
            color: #990dd1;
        }

        .section-header td {
            font-size: 11px;
            font-weight: 800;
        }

        .hover-row {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .hover-row:hover {
            background-color: #b979cc10;
        }

        .btn-purple {
            background: #990dd1;
        }

        .btn-purple:hover {
            background: #b979cc;
        }

        .export-btn {
            background: #3f6516;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .export-btn:hover {
            background: #2d4a10;
        }

        .year-selector {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 11px;
            font-weight: 500;
            background: white;
            cursor: pointer;
        }

        .year-selector:focus {
            outline: none;
            border-color: #990dd1;
            box-shadow: 0 0 0 3px rgba(153, 13, 209, 0.1);
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .clickable-row {
            cursor: pointer;
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

        .mb-2 {
            margin-bottom: 8px;
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

        .gap-4 {
            gap: 16px;
        }

        .gap-2 {
            gap: 8px;
        }

        .w-10 {
            width: 40px;
        }

        .h-10 {
            height: 40px;
        }

        .text-2xl {
            font-size: 24px;
        }

        .font-bold {
            font-weight: 700;
        }

        .text-white {
            color: white;
        }

        .rounded-xl {
            border-radius: 12px;
        }

        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .bg-\[990dd1\] {
            background-color: #990dd1;
        }

        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .bg-gradient-to-br {
            background-image: linear-gradient(to bottom right, #990dd1, #b979cc);
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

        .border-purple-100 {
            border-color: #f3e8ff;
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

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-11px {
            font-size: 11px;
        }

        .text-10px {
            font-size: 10px;
        }

        .text-9px {
            font-size: 9px;
        }

        .text-xl {
            font-size: 20px;
        }

        .text-3b3b3b {
            color: #3b3b3b;
        }

        .text-000 {
            color: #000;
        }

        .text-990dd1 {
            color: #990dd1;
        }

        .text-5170ff {
            color: #5170ff;
        }

        .opacity-80 {
            opacity: 0.8;
        }

        .mt-2 {
            margin-top: 8px;
        }

        @media (min-width: 768px) {
            .md-grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
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
                <span class="header-subtitle">Gender and Development Office</span>
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

    <div style="padding-left: 256px; padding-top: 64px; min-height: 100vh;">
        <div style="padding: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <select id="yearSelect" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 11px; font-weight: 500; background: white; cursor: pointer;">
                        <option value="2026" selected>2026</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                    </select>
                    <button onclick="exportToExcel()" style="background: #3f6516; color: white; padding: 8px 16px; border-radius: 8px; font-size: 11px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <span>📊</span> Export to Excel
                    </button>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px;">
                <div style="background-image: linear-gradient(to bottom right, #990dd1, #b979cc); border-radius: 12px; padding: 16px; color: white; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); transition: all 0.3s ease;">
                    <p style="font-size: 10px; opacity: 0.8;">Total GAD Budget</p>
                    <p style="font-size: 20px; font-weight: 700;">₱243,541,951</p>
                </div>
                <div style="background-color: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0; transition: all 0.3s ease;">
                    <p style="font-size: 10px; color: #3b3b3b;">Total Beneficiaries</p>
                    <p style="font-size: 20px; font-weight: 700; color: #990dd1;" id="totalBeneficiaries">0</p>
                    <p style="font-size: 9px;"><span style="color: #5170ff;">♂ Male</span> | <span style="color: #990dd1;">♀ Female</span></p>
                </div>
                <div style="background-color: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0; transition: all 0.3s ease;">
                    <p style="font-size: 10px; color: #3b3b3b;">Client-Focused</p>
                    <p style="font-size: 20px; font-weight: 700; color: #000;" id="clientTotalCount">0</p>
                    <p style="font-size: 9px; color: #3b3b3b;">Activities</p>
                </div>
                <div style="background-color: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0; transition: all 0.3s ease;">
                    <p style="font-size: 10px; color: #3b3b3b;">Organization-Focused</p>
                    <p style="font-size: 20px; font-weight: 700; color: #000;" id="orgTotalCount">0</p>
                    <p style="font-size: 9px; color: #3b3b3b;">Activities</p>
                </div>
            </div>

            <div style="background-color: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #f3e8ff; overflow: hidden;">
                <div style="overflow-x: auto;">
                    <table style="border-collapse: collapse; width: 100%;">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 32px; text-align: center; border: 1px solid #e2e8f0; padding: 10px 12px; vertical-align: top; font-size: 11px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; color: white;">#</th>
                                <th rowspan="2" style="width: 18%; border: 1px solid #e2e8f0; padding: 10px 12px; vertical-align: top; font-size: 11px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; color: white;">GENDER ISSUE / GAD MANDATE</th>
                                <th rowspan="2" style="width: 15%; border: 1px solid #e2e8f0; padding: 10px 12px; vertical-align: top; font-size: 11px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; color: white;">CAUSE OF GENDER ISSUE</th>
                                <th rowspan="2" style="width: 15%; border: 1px solid #e2e8f0; padding: 10px 12px; vertical-align: top; font-size: 11px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; color: white;">GAD RESULT STATEMENT / GAD OBJECTIVE</th>
                                <th rowspan="2" style="width: 12%; border: 1px solid #e2e8f0; padding: 10px 12px; vertical-align: top; font-size: 11px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; color: white;">GAD ACTIVITY</th>
                                <th rowspan="2" style="width: 12%; border: 1px solid #e2e8f0; padding: 10px 12px; vertical-align: top; font-size: 11px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; color: white;">PERFORMANCE INDICATORS / TARGETS</th>
                                <th rowspan="2" style="width: 10%; border: 1px solid #e2e8f0; padding: 10px 12px; vertical-align: top; font-size: 11px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; color: white;">TARGET RESULT</th>
                                <th colspan="2" style="width: 10%; border: 1px solid #e2e8f0; padding: 10px 12px; vertical-align: top; font-size: 11px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; color: white;">ATTENDANCE</th>
                            </tr>
                            <tr>
                                <th style="text-align: center; width: 5%; border: 1px solid #e2e8f0; padding: 10px 12px; vertical-align: top; font-size: 11px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; color: white;">MALE</th>
                                <th style="text-align: center; width: 5%; border: 1px solid #e2e8f0; padding: 10px 12px; vertical-align: top; font-size: 11px; background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px; color: white;">FEMALE</th>
                            </tr>
                        </thead>
                        <tbody id="reportTableBody"></tbody>
                        <tfoot>
                            <tr style="background: #b979cc15; font-weight: 700; color: #990dd1;">
                                <td colspan="7" style="font-size: 11px; font-weight: 800; border: 1px solid #e2e8f0; padding: 10px 12px;">CLIENT-FOCUSED MANDATES</td>
                                <td style="text-align: center; font-weight: 700; color: #990dd1; border: 1px solid #e2e8f0; padding: 10px 12px;" id="clientMaleTotal">0</td>
                                <td style="text-align: center; font-weight: 700; color: #990dd1; border: 1px solid #e2e8f0; padding: 10px 12px;" id="clientFemaleTotal">0</td>
                            </tr>
                            <tr style="background: #b979cc15; font-weight: 700; color: #990dd1;">
                                <td colspan="7" style="font-size: 11px; font-weight: 800; border: 1px solid #e2e8f0; padding: 10px 12px;">ORGANIZATION-FOCUSED MANDATES</td>
                                <td style="text-align: center; font-weight: 700; color: #990dd1; border: 1px solid #e2e8f0; padding: 10px 12px;" id="orgMaleTotal">0</td>
                                <td style="text-align: center; font-weight: 700; color: #990dd1; border: 1px solid #e2e8f0; padding: 10px 12px;" id="orgFemaleTotal">0</td>
                            </tr>
                            <tr style="background: #b979cc10;">
                                <td colspan="7" style="font-weight: 700; color: #000; border: 1px solid #e2e8f0; padding: 10px 12px;">GRAND TOTAL</td>
                                <td style="text-align: center; font-weight: 700; color: #990dd1; border: 1px solid #e2e8f0; padding: 10px 12px;" id="grandTotalMale">0</td>
                                <td style="text-align: center; font-weight: 700; color: #990dd1; border: 1px solid #e2e8f0; padding: 10px 12px;" id="grandTotalFemale">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const annualReportData = {
            2026: {
                clientFocused: [{
                    id: 1,
                    mandate: "Universal Access to Quality Tertiary Education (RA 10931)",
                    cause: "Extraordinary life situations due to disasters, calamities, and socio-cultural discrimination",
                    result: "Promote equitable access and participation of both women and men from GIDAs in tertiary education",
                    activity: "Implementation of Affirmative Action Agenda",
                    indicators: "Number of served disadvantaged students",
                    targetResult: "100% disadvantaged students served",
                    male: 1250,
                    female: 2750
                }, {
                    id: 2,
                    mandate: "Free Tuition Fee under RA 10931",
                    cause: "High tuition and miscellaneous fees, compounded by socio-cultural expectations for women to prioritize domestic roles",
                    result: "Promote gender equality in access to tertiary education by eliminating financial barriers",
                    activity: "Provision of free tuition fee to eligible male and female students",
                    indicators: "Percentage of qualified students granted free tuition",
                    targetResult: "100% qualified students granted free tuition",
                    male: 4800,
                    female: 6200
                }, {
                    id: 3,
                    mandate: "CHED CMO No. 01 s.2015 - GAD Orientation",
                    cause: "Limited activities to increase awareness of men and women students to GAD-related information",
                    result: "Increase students' level of awareness and appreciation on GAD",
                    activity: "Conduct GAD orientation/forum/seminar to BSU 1st year/transferees students",
                    indicators: "Number of students oriented on GAD",
                    targetResult: "4,000 students oriented",
                    male: 1250,
                    female: 2750
                }, {
                    id: 4,
                    mandate: "CHED CMO No. 01 s.2015 - Student Leadership",
                    cause: "Student leaders have limited understanding on GAD in the University",
                    result: "Empower student leaders regarding GAD responsive leadership",
                    activity: "Continuous conduct of GAD responsive leadership training for student leaders",
                    indicators: "Number of training sessions conducted",
                    targetResult: "2 training sessions (F:200, M:100)",
                    male: 100,
                    female: 200
                }, {
                    id: 5,
                    mandate: "Gender-Responsive Extension Program (GREP)",
                    cause: "Presence of gender inequality, poverty and GAD-related concerns in the community",
                    result: "Sustain GAD-related extension activities delivering technology transfer, livelihood, and advocacy",
                    activity: "Conduct of extension activities to partner communities",
                    indicators: "Number of extension activities conducted",
                    targetResult: "24 extension activities (F:560, M:500)",
                    male: 500,
                    female: 560
                }, {
                    id: 6,
                    mandate: "Programs for PWDs (DBM-DSWD Circular)",
                    cause: "Limited access of PWDs to gender-responsive programs and services",
                    result: "Improved access of PWDs to empowering programs and services",
                    activity: "Awareness programs for women PWDs",
                    indicators: "Number of PWDs who benefited",
                    targetResult: "Increased beneficiaries from the program",
                    male: 40,
                    female: 60
                }, {
                    id: 7,
                    mandate: "Senior Citizens Program",
                    cause: "Absence of senior citizens access to sustainable programs",
                    result: "Improved access of senior citizens to gender-responsive programs",
                    activity: "BSU Kalinga for women Senior Citizens",
                    indicators: "Programs provided for Senior Citizens",
                    targetResult: "Programs provided for Senior Citizens",
                    male: 23,
                    female: 21
                }],
                orgFocused: [{
                    id: 8,
                    mandate: "GAD Mainstreaming Capability Building",
                    cause: "Low awareness among personnel in the University about GAD mainstreaming",
                    result: "Enhance GAD mainstreaming in Administration, Academic, Research, Extension, and Production",
                    activity: "Conduct GAD related Gender Mainstreaming capability building and training",
                    indicators: "Number of training/workshop/seminars conducted",
                    targetResult: "25 training sessions (F:1500, M:1000)",
                    male: 1000,
                    female: 1500
                }, {
                    id: 9,
                    mandate: "Reproductive Health Care Center",
                    cause: "Inadequate support services to personnel and students with children",
                    result: "Operational reproductive health services for personnel and students",
                    activity: "Operationalize BSU College of Nursing Reproductive Health Center",
                    indicators: "Number of maintained Reproductive Health Centers",
                    targetResult: "1 maintained Reproductive Health Center",
                    male: 0,
                    female: 0
                }, {
                    id: 10,
                    mandate: "Child Minding Center (EO 340 s.1997)",
                    cause: "Problems of parents and students related to child care",
                    result: "Ensure opportunities for personnel and students to have access to agency care services",
                    activity: "Maintenance of Child Minding Centers for working parents",
                    indicators: "Number of fully maintained centers",
                    targetResult: "Fully maintained centers at 3 campuses",
                    male: 0,
                    female: 0
                }, {
                    id: 11,
                    mandate: "GAD Monitoring and Evaluation",
                    cause: "Low integration of gender mainstreaming of BSU",
                    result: "Strengthen the GAD integration in the operations of BSU",
                    activity: "Create a Monitoring Team to evaluate GAD PAPs and ensure effectiveness",
                    indicators: "Number of monitoring meetings conducted",
                    targetResult: "4 monitoring meetings with reports conducted",
                    male: 0,
                    female: 0
                }, {
                    id: 12,
                    mandate: "GFPS Capacity Building",
                    cause: "Low level of capacity of GFPS to develop and implement GAD programs",
                    result: "Capacitated GFPS members to implement GAD PAPs and advance GAD Mainstreaming",
                    activity: "GFPS training and TOT on GAD related trainings",
                    indicators: "Number of trainings per GFPS member",
                    targetResult: "At least 1 training per GFPS member (F:31, M:15)",
                    male: 15,
                    female: 31
                }, {
                    id: 13,
                    mandate: "GAD Office Operations Support",
                    cause: "No plantilla personnel assigned to plan, implement and monitor GAD PAPs",
                    result: "Ensure operations of GAD Office and monitor GM efforts",
                    activity: "Engage support staff to assist in GFPS implementation",
                    indicators: "Number of support staff engaged",
                    targetResult: "2 casual staff + 3 student assistants",
                    male: 0,
                    female: 0
                }, {
                    id: 14,
                    mandate: "Gender Sensitivity Training (GST)",
                    cause: "Lack of regular orientation and refresher training on gender sensitivity",
                    result: "Enhance awareness and understanding of gender concepts among newly hired and current personnel",
                    activity: "Conduct GST for newly hired and current personnel",
                    indicators: "Number of trainings conducted",
                    targetResult: "1 training for new hires + 3 refresher trainings",
                    male: 0,
                    female: 0
                }, {
                    id: 15,
                    mandate: "GAD Library and Learning Materials",
                    cause: "Limited number of GAD library and related learning materials",
                    result: "Increase provision of adequate and accessible GAD learning materials",
                    activity: "Provision of knowledge products and books for gender-responsive curriculum",
                    indicators: "Number of books procured",
                    targetResult: "200 books procured",
                    male: 0,
                    female: 0
                }, {
                    id: 16,
                    mandate: "GAD IEC Materials Development",
                    cause: "Presence of Gender Based Violence issues/reports/cases in the university",
                    result: "Institutionalize GAD mechanisms and sustain awareness campaigns on VAW",
                    activity: "Development and Dissemination of GAD IEC Materials",
                    indicators: "Official publication with GAD articles",
                    targetResult: "Official publication with GAD articles",
                    male: 0,
                    female: 0
                }, {
                    id: 17,
                    mandate: "GAD Database System",
                    cause: "Need for sex-disaggregated data for GAD mainstreaming",
                    result: "Institutionalized GAD database and Sex-Disaggregated Database",
                    activity: "Establish GAD database system",
                    indicators: "Functional GAD database system",
                    targetResult: "Fully functional GAD database system",
                    male: 0,
                    female: 0
                }, {
                    id: 18,
                    mandate: "Crisis Intervention Assistance",
                    cause: "Limited resources of DSWD and LGU to provide for students",
                    result: "Ensure disaster assistance to distressed students are gender-responsive",
                    activity: "Distribution of hygiene kits for both women and men",
                    indicators: "Number of employees/students served",
                    targetResult: "1,000 employees/students served",
                    male: 400,
                    female: 600
                }, {
                    id: 19,
                    mandate: "Gender-Related Leaves",
                    cause: "Employees may require special leaves due to parental obligations",
                    result: "Enhanced support services for employees in need of special leaves",
                    activity: "Provision of gender leaves and seminar on Gender Related Leaves",
                    indicators: "Percentage of availed leaves",
                    targetResult: "100% availed leaves + 1 seminar (M:20, F:50)",
                    male: 20,
                    female: 50
                }, {
                    id: 20,
                    mandate: "VAW Campaigns & Women's Month",
                    cause: "Need to highlight women's rights and protection against VAW",
                    result: "Strengthen awareness on women's rights and role in nation building",
                    activity: "Participation to 18-Day Campaign to end VAW and Women's Month",
                    indicators: "Number of activities per campus",
                    targetResult: "At least 1 activity per campus",
                    male: 0,
                    female: 0
                }, {
                    id: 21,
                    mandate: "Breastfeeding Station Maintenance",
                    cause: "Inadequate support services for personnel and students with children",
                    result: "Support services for personnel and students with children",
                    activity: "Establishment/maintenance of breastfeeding stations",
                    indicators: "Percentage of fully maintained lactation rooms",
                    targetResult: "100% fully maintained lactation rooms at 3 campuses",
                    male: 0,
                    female: 0
                }, {
                    id: 22,
                    mandate: "Gender-Responsive Curricular Programs",
                    cause: "Limited subject for GAD integration in instruction and curriculum",
                    result: "Integration of gender mainstreaming in curriculum across all levels",
                    activity: "Preparation of syllabi and classroom teaching integrating gender perspective",
                    indicators: "Number of faculty integrating gender perspective",
                    targetResult: "567 faculty integrating gender perspective",
                    male: 0,
                    female: 0
                }, {
                    id: 23,
                    mandate: "Sustain Gender Mainstreaming",
                    cause: "Need for sustained operations of GAD Office",
                    result: "Continuous and efficient operation of functional, gender-responsive GAD Office",
                    activity: "Sustaining Gender Mainstreaming and Institutional Support",
                    indicators: "Percentage of fully maintained GAD Office",
                    targetResult: "100% fully maintained GAD Office",
                    male: 0,
                    female: 0
                }]
            },
            2025: {
                clientFocused: [{
                    id: 1,
                    mandate: "Universal Access to Quality Tertiary Education",
                    cause: "Disasters and socio-cultural discrimination",
                    result: "Promote equitable access",
                    activity: "Implementation of Affirmative Action",
                    indicators: "Number of disadvantaged students served",
                    targetResult: "95% disadvantaged students served",
                    male: 1120,
                    female: 2480
                }, {
                    id: 2,
                    mandate: "Free Tuition Fee under RA 10931",
                    cause: "High tuition fees",
                    result: "Eliminate financial barriers",
                    activity: "Provision of free tuition",
                    indicators: "Percentage of qualified students",
                    targetResult: "95% qualified students",
                    male: 4560,
                    female: 5890
                }, {
                    id: 3,
                    mandate: "CHED CMO No. 01 s.2015 - GAD Orientation",
                    cause: "Limited GAD awareness",
                    result: "Increase GAD awareness",
                    activity: "GAD orientation for students",
                    indicators: "Number of students oriented",
                    targetResult: "3,800 students oriented",
                    male: 1180,
                    female: 2620
                }],
                orgFocused: [{
                    id: 4,
                    mandate: "GAD Mainstreaming Capability Building",
                    cause: "Low awareness among personnel",
                    result: "Enhance GAD mainstreaming",
                    activity: "GAD capability building",
                    indicators: "Number of training sessions",
                    targetResult: "20 training sessions",
                    male: 850,
                    female: 1350
                }, {
                    id: 5,
                    mandate: "Gender Sensitivity Training",
                    cause: "Lack of regular orientation",
                    result: "Enhance gender awareness",
                    activity: "GST for personnel",
                    indicators: "Number of training sessions",
                    targetResult: "3 training sessions",
                    male: 120,
                    female: 280
                }]
            },
            2024: {
                clientFocused: [{
                    id: 1,
                    mandate: "Universal Access to Quality Tertiary Education",
                    cause: "Disasters and discrimination",
                    result: "Promote equitable access",
                    activity: "Affirmative Action",
                    indicators: "Percentage of disadvantaged students",
                    targetResult: "90% disadvantaged students",
                    male: 980,
                    female: 2120
                }],
                orgFocused: [{
                    id: 2,
                    mandate: "GAD Mainstreaming Capability Building",
                    cause: "Low awareness",
                    result: "Enhance GAD mainstreaming",
                    activity: "GAD training",
                    indicators: "Number of training sessions",
                    targetResult: "15 training sessions",
                    male: 720,
                    female: 1180
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
                orgFemaleTotal = 0,
                clientCount = 0,
                orgCount = 0;
            let html = '';
            data.clientFocused.forEach((item, idx) => {
                clientMaleTotal += item.male;
                clientFemaleTotal += item.female;
                clientCount++;
                html += `<tr style="cursor: pointer;" onclick="viewDetails(${item.id}, 'client')"><td style="text-align: center; border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${idx + 1}</td><td style="border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;"><strong>${escapeHtml(item.mandate)}</strong></td><td style="border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${escapeHtml(item.cause)}</td><td style="border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${escapeHtml(item.result)}</td><td style="border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${escapeHtml(item.activity)}</td><td style="border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${escapeHtml(item.indicators)}</td><td style="border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${escapeHtml(item.targetResult)}</td><td style="text-align: center; border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${item.male.toLocaleString()}</td><td style="text-align: center; border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${item.female.toLocaleString()}</td></tr>`;
            });
            data.orgFocused.forEach((item, idx) => {
                orgMaleTotal += item.male;
                orgFemaleTotal += item.female;
                orgCount++;
                html += `<tr style="cursor: pointer;" onclick="viewDetails(${item.id}, 'org')"><td style="text-align: center; border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${data.clientFocused.length + idx + 1}</td><td style="border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;"><strong>${escapeHtml(item.mandate)}</strong></td><td style="border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${escapeHtml(item.cause)}</td><td style="border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${escapeHtml(item.result)}</td><td style="border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${escapeHtml(item.activity)}</td><td style="border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${escapeHtml(item.indicators)}</td><td style="border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${escapeHtml(item.targetResult)}</td><td style="text-align: center; border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${item.male.toLocaleString()}</td><td style="text-align: center; border: 1px solid #e2e8f0; padding: 10px 12px; font-size: 11px;">${item.female.toLocaleString()}</td></tr>`;
            });
            tbody.innerHTML = html;
            document.getElementById('clientMaleTotal').textContent = clientMaleTotal.toLocaleString();
            document.getElementById('clientFemaleTotal').textContent = clientFemaleTotal.toLocaleString();
            document.getElementById('orgMaleTotal').textContent = orgMaleTotal.toLocaleString();
            document.getElementById('orgFemaleTotal').textContent = orgFemaleTotal.toLocaleString();
            document.getElementById('grandTotalMale').textContent = (clientMaleTotal + orgMaleTotal).toLocaleString();
            document.getElementById('grandTotalFemale').textContent = (clientFemaleTotal + orgFemaleTotal).toLocaleString();
            document.getElementById('totalBeneficiaries').innerHTML = (clientMaleTotal + clientFemaleTotal + orgMaleTotal + orgFemaleTotal).toLocaleString();
            document.getElementById('clientTotalCount').textContent = clientCount;
            document.getElementById('orgTotalCount').textContent = orgCount;
        }

        function viewDetails(id, type) {
            alert(`Viewing details for ${type === 'client' ? 'Client-Focused' : 'Organization-Focused'} Mandate #${id}`);
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