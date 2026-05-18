<?php
// staff/data_privacy.php - Data Privacy Page
// require_once '../auth.php';
// require_role(['gad_staff', 'admin']);
// $page_title = 'Data Privacy';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Privacy | GAD-IMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8f9fb;
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

        .max-w-4xl {
            max-width: 56rem;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .mb-8 {
            margin-bottom: 32px;
        }

        .flex {
            display: flex;
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

        .text-4xl {
            font-size: 36px;
        }

        .text-990dd1 {
            color: #990dd1;
        }

        .text-2xl {
            font-size: 24px;
        }

        .font-bold {
            font-weight: 700;
        }

        .text-000 {
            color: #000;
        }

        .text-3b3b3b {
            color: #3b3b3b;
        }

        .text-sm {
            font-size: 14px;
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

        .border {
            border: 1px solid #e2e8f0;
        }

        .space-y-6>*+* {
            margin-top: 24px;
        }

        .border-b {
            border-bottom: 1px solid #e2e8f0;
        }

        .pb-4 {
            padding-bottom: 16px;
        }

        .text-lg {
            font-size: 18px;
        }

        .text-11px {
            font-size: 13px;
        }

        .leading-relaxed {
            line-height: 1.625;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .list-disc {
            list-style-type: disc;
        }

        .list-inside {
            list-style-position: inside;
        }

        .space-y-1>*+* {
            margin-top: 4px;
        }

        .ml-4 {
            margin-left: 16px;
        }

        .mt-3 {
            margin-top: 12px;
        }

        .p-4 {
            padding: 16px;
        }

        .bg-slate-50 {
            background-color: #f8fafc;
        }

        .rounded-lg {
            border-radius: 8px;
        }

        .font-medium {
            font-weight: 500;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .italic {
            font-style: italic;
        }

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
            font-size: 13px;
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
            font-size: 13px;
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
            font-size: 13px;
            font-weight: 500;
            color: #000;
        }

        .notif-time {
            font-size: 13px;
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
            font-size: 13px;
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
    <header class="staff-header">
        <div>
            <h2 class="header-title">Data Privacy</h2>
            <div class="flex items-center gap-2"><span class="header-subtitle">Office of Student Services</span></div>
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
    <?php include 'includes/sidebar_twg.php'; ?>
    <main class="pl-64 pt-16 min-h-screen">
        <div class="p-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-2"><span class="text-4xl text-990dd1">🔒</span>
                        <h1 class="text-2xl font-bold text-000">Data Privacy Statement</h1>
                    </div>
                    <p class="text-3b3b3b text-sm">Benguet State University - Gender and Development Information Management System</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border p-8 space-y-6">
                    <div class="border-b pb-4">
                        <h2 class="text-lg font-bold text-000 mb-2">1. Introduction</h2>
                        <p class="text-11px text-3b3b3b leading-relaxed">Benguet State University (BSU) is committed to protecting the privacy and confidentiality of personal information collected, stored, and processed through the Gender and Development Information Management System (GAD-IMS). This Data Privacy Statement outlines our practices regarding the collection, use, and protection of your personal data in compliance with the Data Privacy Act of 2012 (Republic Act No. 10173).</p>
                    </div>
                    <div class="border-b pb-4">
                        <h2 class="text-lg font-bold text-000 mb-2">2. Information We Collect</h2>
                        <p class="text-11px text-3b3b3b leading-relaxed mb-2">The GAD-IMS collects the following types of personal information:</p>
                        <ul class="list-disc list-inside text-11px text-3b3b3b space-y-1 ml-4">
                            <li>Full name and contact information (email address, office/department)</li>
                            <li>Activity design and accomplishment report submissions</li>
                            <li>Budget utilization data and financial information</li>
                            <li>Attendance records and participant demographics (sex-disaggregated data)</li>
                            <li>User activity logs and system access records</li>
                        </ul>
                    </div>
                    <div class="border-b pb-4">
                        <h2 class="text-lg font-bold text-000 mb-2">3. Purpose of Collection</h2>
                        <p class="text-11px text-3b3b3b leading-relaxed mb-2">Your personal information is collected and processed for the following purposes:</p>
                        <ul class="list-disc list-inside text-11px text-3b3b3b space-y-1 ml-4">
                            <li>Processing and evaluation of GAD activity designs and accomplishment reports</li>
                            <li>Generation of GAD Plan and Budget (GPB) reports</li>
                            <li>Monitoring and evaluation of GAD program implementation</li>
                            <li>Compliance with government reporting requirements (PCW, CHED, DBM)</li>
                            <li>Research and statistical analysis for GAD program improvement</li>
                        </ul>
                    </div>
                    <div class="border-b pb-4">
                        <h2 class="text-lg font-bold text-000 mb-2">4. Data Sharing and Disclosure</h2>
                        <p class="text-11px text-3b3b3b leading-relaxed">BSU may share aggregated, anonymized data with government agencies such as the Philippine Commission on Women (PCW), Commission on Higher Education (CHED), and Department of Budget and Management (DBM) for reporting purposes. Personal information is not shared with third parties without your explicit consent, unless required by law.</p>
                    </div>
                    <div class="border-b pb-4">
                        <h2 class="text-lg font-bold text-000 mb-2">5. Data Security</h2>
                        <p class="text-11px text-3b3b3b leading-relaxed">BSU implements appropriate organizational, physical, and technical security measures to protect personal information from unauthorized access, alteration, disclosure, or destruction. These measures include access controls, encryption, regular security audits, and staff training on data protection.</p>
                    </div>
                    <div class="border-b pb-4">
                        <h2 class="text-lg font-bold text-000 mb-2">6. Data Retention</h2>
                        <p class="text-11px text-3b3b3b leading-relaxed">Personal information is retained only for as long as necessary to fulfill the purposes for which it was collected, or as required by applicable laws and regulations. GAD records are typically retained for a period of ten (10) years in accordance with government archival requirements.</p>
                    </div>
                    <div class="border-b pb-4">
                        <h2 class="text-lg font-bold text-000 mb-2">7. Your Rights</h2>
                        <p class="text-11px text-3b3b3b leading-relaxed mb-2">Under the Data Privacy Act, you have the right to:</p>
                        <ul class="list-disc list-inside text-11px text-3b3b3b space-y-1 ml-4">
                            <li>Access your personal information</li>
                            <li>Rectify inaccurate or incomplete data</li>
                            <li>Request deletion or blocking of your data</li>
                            <li>Object to the processing of your data</li>
                            <li>Data portability</li>
                        </ul>
                    </div>
                    <div class="border-b pb-4">
                        <h2 class="text-lg font-bold text-000 mb-2">8. Contact Information</h2>
                        <p class="text-11px text-3b3b3b leading-relaxed">For questions, concerns, or requests regarding your personal data, please contact:</p>
                        <div class="mt-3 p-4 bg-slate-50 rounded-lg">
                            <p class="text-11px font-medium text-000">Data Protection Officer</p>
                            <p class="text-11px text-3b3b3b">Benguet State University</p>
                            <p class="text-11px text-3b3b3b">La Trinidad, Benguet 2601</p>
                            <p class="text-11px text-3b3b3b">Email: dpo@bsu.edu.ph</p>
                            <p class="text-11px text-3b3b3b">Tel: (074) 422-2401</p>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-000 mb-2">9. Updates to this Statement</h2>
                        <p class="text-11px text-3b3b3b leading-relaxed">This Data Privacy Statement may be updated periodically. Users will be notified of significant changes through the system or via email. The effective date of the current version is displayed below.</p>
                        <p class="text-11px text-3b3b3b mt-4 italic">Effective Date: January 1, 2026</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="fixed bottom-4 left-64 right-0 text-center pointer-events-none z-40">
        <p class="text-10px text-3b3b3b/20 font-medium">Benguet State University - Gender and Development Information Management System | GAD-IMS v1.0</p>
    </div>
</body>

</html>