<?php
// ar_view.php - Read-only View for Approved Accomplishment Report
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Accomplishment Report | GAD-IMS</title>
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

        .p-10 {
            padding: 40px;
        }

        .main-flex {
            display: flex;
            gap: 32px;
        }

        .flex-062 {
            flex: 0.62;
        }

        .flex-038 {
            flex: 0.38;
        }

        /* Card Styles */
        .card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .card-header {
            padding: 24px 28px 16px 28px;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-body {
            padding: 24px 28px;
        }

        .card-footer {
            padding: 16px 28px;
            border-top: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        /* Title Block */
        .title-block {
            padding: 32px 32px 20px 32px;
            border-bottom: 1px solid #e2e8f0;
            background-color: rgba(209, 250, 229, 0.3);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #d1fae5;
            color: #047857;
            padding: 4px 12px;
            border-radius: 9999px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: #059669;
            border-radius: 9999px;
        }

        .status-text {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .control-number {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-left: 12px;
        }

        .report-title {
            font-family: 'Times New Roman', serif;
            font-size: 28px;
            color: #1e293b;
            line-height: 1.25;
            margin-bottom: 16px;
            margin-top: 16px;
        }

        .info-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
        }

        .info-value-emerald {
            color: #047857;
            font-weight: 600;
        }

        /* Section Styles */
        .section-card {
            background-color: #f8fafc;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .section-title h3 {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #6d28d9;
        }

        /* Grid */
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .grid-2-gap-5 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        /* Metrics Cards */
        .metric-card {
            background-color: white;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .metric-number {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 8px;
        }

        .metric-label {
            font-size: 10px;
            color: #94a3b8;
            text-transform: uppercase;
            margin-top: 4px;
        }

        /* Document List */
        .doc-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background-color: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 8px;
        }

        .doc-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .doc-title {
            font-size: 13px;
            font-weight: 500;
            color: #000;
        }

        .doc-meta {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .preview-btn {
            color: #7c3aed;
            font-size: 13px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 8px;
        }

        .preview-btn:hover {
            background-color: rgba(124, 58, 237, 0.1);
        }

        /* List Styles */
        .feedback-list {
            list-style-type: disc;
            list-style-position: inside;
            font-size: 13px;
            color: #475569;
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        /* Submission History */
        .history-container {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .history-item {
            position: relative;
            padding-left: 28px;
        }

        .history-dot {
            position: absolute;
            left: 0;
            top: 2px;
            width: 14px;
            height: 14px;
            border-radius: 9999px;
        }

        .history-dot-purple {
            background-color: #7c3aed;
        }

        .history-dot-emerald {
            background-color: #059669;
        }

        .history-title {
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
        }

        .history-title-emerald {
            color: #059669;
        }

        .history-date {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* Right Sidebar */
        .sidebar-card {
            background-color: white;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }

        .sidebar-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .sidebar-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-card-icon-purple {
            background-color: #ede9fe;
            color: #6d28d9;
        }

        .sidebar-card-icon-emerald {
            background-color: #d1fae5;
            color: #059669;
        }

        .sidebar-card-title {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6d28d9;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
        }

        .info-row-border {
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row-label {
            font-size: 12px;
            color: #64748b;
        }

        .info-row-value {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }

        .status-badge-small {
            background-color: #fef3c7;
            color: #d97706;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .progress-bar-container {
            background-color: #e2e8f0;
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
            margin-top: 16px;
        }

        .progress-bar-fill {
            width: 40%;
            height: 100%;
            background: linear-gradient(90deg, #7c3aed, #a855f7);
            border-radius: 10px;
        }

        .progress-text {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 8px;
        }

        /* Back Button */
        .back-btn {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: #f1f5f9;
            color: #334155;
            border-radius: 14px;
            font-weight: 700;
            font-size: 13px;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #e2e8f0;
        }

        .sticky-sidebar {
            position: sticky;
            top: 80px;
            align-self: flex-start;
            max-height: calc(100vh - 96px);
            overflow-y: auto;
            padding-bottom: 16px;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cfc2d544;
            border-radius: 10px;
        }

        .space-y-6>*+* {
            margin-top: 24px;
        }

        .space-y-3>*+* {
            margin-top: 12px;
        }

        .space-y-2>*+* {
            margin-top: 8px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mt-3 {
            margin-top: 12px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .text-center {
            text-align: center;
        }

        .text-sm {
            font-size: 14px;
        }

        .text-xs {
            font-size: 12px;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-semibold {
            font-weight: 600;
        }

        .text-purple-600 {
            color: #7c3aed;
        }

        .text-emerald-700 {
            color: #047857;
        }

        .text-slate-400 {
            color: #94a3b8;
        }

        .text-slate-500 {
            color: #64748b;
        }

        .text-slate-600 {
            color: #475569;
        }

        .text-slate-800 {
            color: #1e293b;
        }

        .text-amber-600 {
            color: #d97706;
        }

        .text-blue-600 {
            color: #2563eb;
        }

        .text-red-500 {
            color: #ef4444;
        }

        .text-emerald-500 {
            color: #10b981;
        }

        .bg-blue-50 {
            background-color: #eff6ff;
        }

        .border-blue-200 {
            border-color: #bfdbfe;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .rounded-lg {
            border-radius: 8px;
        }

        .rounded-xl {
            border-radius: 12px;
        }

        .inline-block {
            display: inline-block;
        }

        .w-full {
            width: 100%;
        }

        .py-3 {
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .px-2 {
            padding-left: 8px;
            padding-right: 8px;
        }

        .px-3 {
            padding-left: 12px;
            padding-right: 12px;
        }

        .px-4 {
            padding-left: 16px;
            padding-right: 16px;
        }

        .py-1\.5 {
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .overflow-y-auto {
            overflow-y: auto;
        }

        .hidden {
            display: none;
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

        .z-200 {
            z-index: 200;
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

        .text-red-600 {
            color: #dc2626;
        }

        .bg-red-600 {
            background-color: #dc2626;
        }

        .border-slate-300 {
            border-color: #cbd5e1;
        }

        .hover\:bg-red-700:hover {
            background-color: #b91c1c;
        }

        .hover\:bg-slate-50:hover {
            background-color: #f8fafc;
        }

        .hover\:bg-purple-50:hover {
            background-color: #faf5ff;
        }
    </style>
</head>

<body>

    <header class="staff-header">
        <div>
            <h2 class="header-title">Lorem Ipsum Dolor - Accomplishment Report</h2>
            <div class="flex items-center gap-2">
                <span class="header-subtitle">Office of Student Services</span>
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
        <div class="p-10 main-flex">

            <!-- LEFT SECTION - Report Details -->
            <section class="flex-[0.38]">

                <!-- Title Block -->
                <div class="title-block">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="status-badge">
                            <div class="status-dot"></div>
                            <span class="status-text">Approved</span>
                        </div>
                        <span class="control-number">BSU-GAD-2026-012</span>
                    </div>
                    <h2 class="report-title">Lorem Ipsum Dolor Sit Amet Consectetur Adipiscing Elit</h2>
                    <div class="info-grid">
                        <div class="info-item"><span class="info-label">Submitted By</span><span class="info-value info-value-emerald">Dr. Lorem Ipsum</span></div>
                        <div class="info-item"><span class="info-label">Office</span><span class="info-value">Lorem Ipsum Office</span></div>
                        <div class="info-item"><span class="info-label">Email</span><span class="info-value">lorem.ipsum@bsu.edu.ph</span></div>
                        <div class="info-item"><span class="info-label">Date Submitted</span><span class="info-value">Mar 2, 2026</span></div>
                    </div>
                </div>

                <!-- Report Information Section -->
                <div class="section-card">
                    <div class="section-title">
                        <span class="text-purple-600 text-xl">ℹ️</span>
                        <h3>Report Information</h3>
                    </div>
                    <div class="grid-2">
                        <div><label class="info-label">Control Number</label>
                            <p class="text-sm font-bold text-purple-600 mt-1">BSU-GAD-2026-012</p>
                        </div>
                        <div><label class="info-label">Activity Title</label>
                            <p class="text-sm font-medium mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                        </div>
                        <div><label class="info-label">Date of Activity</label>
                            <p class="text-sm text-slate-600 mt-1">Feb 20-22, 2026</p>
                        </div>
                        <div><label class="info-label">Venue</label>
                            <p class="text-sm text-slate-600 mt-1">Lorem Ipsum Convention Center</p>
                        </div>
                    </div>
                </div>

                <!-- Activity Metrics Section -->
                <div class="section-card">
                    <div class="section-title">
                        <span class="text-purple-600 text-xl">📊</span>
                        <h3>Activity Metrics</h3>
                    </div>
                    <div class="grid-2-gap-5">
                        <div class="metric-card"><span class="text-purple-500 text-2xl">👥</span>
                            <p class="metric-number">62</p>
                            <p class="metric-label">Total Attendees</p>
                        </div>
                        <div class="metric-card"><span class="text-blue-500 text-2xl">♂️</span>
                            <p class="metric-number">8</p>
                            <p class="metric-label">Male</p>
                        </div>
                        <div class="metric-card"><span class="text-pink-500 text-2xl">♀️</span>
                            <p class="metric-number">54</p>
                            <p class="metric-label">Female</p>
                        </div>
                    </div>
                </div>

                <!-- Evaluation Results Section -->
                <div class="section-card">
                    <div class="section-title">
                        <span class="text-purple-600 text-xl">📋</span>
                        <h3>Activity Evaluation Results</h3>
                    </div>
                    <div><label class="info-label">Activity Rating</label>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-xl font-bold text-amber-600">96%</p>
                            <p class="text-xs text-slate-400">/ 100%</p>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-200"><label class="info-label">Key Feedback from Participants</label>
                        <ul class="feedback-list">
                            <li>Lorem ipsum dolor sit amet consectetur</li>
                            <li>Sed do eiusmod tempor incididunt</li>
                            <li>Ut labore et dolore magna aliqua</li>
                            <li>Quis nostrud exercitation ullamco</li>
                        </ul>
                    </div>
                </div>

                <!-- Uploaded Documents Section -->
                <div class="section-card">
                    <div class="section-title">
                        <span class="text-purple-600 text-xl">📎</span>
                        <h3>Uploaded Documents</h3>
                    </div>
                    <div class="mb-4">
                        <p class="info-label mb-2">Accomplishment Report (Filled Template)</p>
                        <div class="doc-item">
                            <div class="doc-info"><span class="text-red-500 text-2xl">📄</span>
                                <div>
                                    <p class="doc-title">Lorem_Ipsum_Accomplishment_Report.pdf</p>
                                    <p class="doc-meta">2.1 MB • Uploaded Mar 2, 2026</p>
                                </div>
                            </div>
                            <div class="flex gap-2"><button class="preview-btn">👁️ Preview</button><button class="preview-btn">⬇️</button></div>
                        </div>
                    </div>
                    <div>
                        <p class="info-label mb-2">Mode of Verification & Attachments</p>
                        <div class="space-y-2">
                            <div class="doc-item">
                                <div class="doc-info"><span class="text-blue-500 text-2xl">📋</span>
                                    <div>
                                        <p class="doc-title">Lorem_Ipsum_Attendance_Sheet.pdf</p>
                                        <p class="doc-meta">0.7 MB • Signed attendance</p>
                                    </div>
                                </div><button class="preview-btn">Preview</button>
                            </div>
                            <div class="doc-item">
                                <div class="doc-info"><span class="text-emerald-500 text-2xl">🖼️</span>
                                    <div>
                                        <p class="doc-title">Lorem_Ipsum_Photos.zip</p>
                                        <p class="doc-meta">8.5 MB • 32 photos</p>
                                    </div>
                                </div><button class="preview-btn">Preview</button>
                            </div>
                            <div class="doc-item">
                                <div class="doc-info"><span class="text-purple-500 text-2xl">📊</span>
                                    <div>
                                        <p class="doc-title">Lorem_Ipsum_Evaluation_Summary.xlsx</p>
                                        <p class="doc-meta">0.4 MB • 62 respondents</p>
                                    </div>
                                </div><button class="preview-btn">Preview</button>
                            </div>
                            <div class="doc-item">
                                <div class="doc-info"><span class="text-amber-500 text-2xl">🧾</span>
                                    <div>
                                        <p class="doc-title">Lorem_Ipsum_Financial_Report.pdf</p>
                                        <p class="doc-meta">0.3 MB • Signed by budget officer</p>
                                    </div>
                                </div><button class="preview-btn">Preview</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- RIGHT SECTION - Sidebar -->
            <section class="flex-[0.62] sticky-sidebar custom-scrollbar">

                <!-- Submission History Card -->
                <div class="sidebar-card">
                    <div class="sidebar-card-header">
                        <div class="sidebar-card-icon sidebar-card-icon-purple"><span>📜</span></div>
                        <div class="sidebar-card-title">Submission History</div>
                    </div>
                    <div class="history-container">
                        <div class="history-item">
                            <div class="history-dot history-dot-purple"></div>
                            <p class="history-title">Report Submitted</p>
                            <p class="history-date">Mar 2, 2026 • 11:45 AM</p>
                        </div>
                        <div class="history-item">
                            <div class="history-dot history-dot-purple"></div>
                            <p class="history-title">Documents Verified by Staff</p>
                            <p class="history-date">Mar 5, 2026 • 02:30 PM</p>
                        </div>
                        <div class="history-item">
                            <div class="history-dot history-dot-purple"></div>
                            <p class="history-title">Forwarded for Director Review</p>
                            <p class="history-date">Mar 6, 2026 • 09:00 AM</p>
                        </div>
                        <div class="history-item">
                            <div class="history-dot history-dot-emerald"></div>
                            <p class="history-title history-title-emerald">Approved</p>
                            <p class="history-date">Mar 25, 2026 • 10:15 AM</p>
                        </div>
                    </div>
                </div>

                <!-- Archived Report Card -->
                <div class="sidebar-card" style="background-color: #eff6ff; border-color: #bfdbfe;">
                    <div class="flex gap-3">
                        <span class="text-blue-600 text-2xl">ℹ️</span>
                        <div>
                            <p class="text-sm font-bold text-blue-700">Archived Report</p>
                            <p class="text-11px text-blue-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. This report is now part of the official records.</p>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <a href="twg_submitted_list.php" class="back-btn flex items-center justify-center gap-2">← Back to Submissions</a>

            </section>
        </div>
    </main>

</body>

</html>