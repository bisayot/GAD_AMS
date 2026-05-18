<?php
// design_view.php - Read-only View for Approved Activity Design (Staff - Monitoring Only)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Activity Design | GAD-IMS</title>
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

        .flex {
            display: flex;
        }

        .gap-8 {
            gap: 32px;
        }

        .flex-06 {
            flex: 0.6;
        }

        .flex-04 {
            flex: 0.4;
        }

        /* Card Styles */
        .bg-white {
            background-color: white;
        }

        .rounded-xl {
            border-radius: 12px;
        }

        .flex-col {
            flex-direction: column;
        }

        .overflow-hidden {
            overflow: hidden;
        }

        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .border {
            border: 1px solid #e2e8f0;
        }

        /* Title Block */
        .title-block {
            padding: 32px 32px 20px 32px;
            border-bottom: 1px solid #e2e8f0;
            background-color: rgba(63, 101, 22, 0.05);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: rgba(63, 101, 22, 0.1);
            color: #3f6516;
            padding: 4px 12px;
            border-radius: 9999px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: #3f6516;
            border-radius: 9999px;
        }

        .status-text {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .control-number {
            font-size: 11px;
            font-weight: 700;
            color: #3b3b3b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-left: 12px;
        }

        .report-title {
            font-family: 'Times New Roman', serif;
            font-size: 28px;
            color: #000;
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
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #3b3b3b;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
        }

        .info-value-emerald {
            color: #3f6516;
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
            color: #3b3b3b;
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

        /* Budget Breakdown */
        .budget-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .budget-total {
            display: flex;
            justify-content: space-between;
            padding: 16px 0 8px 0;
        }

        .budget-label {
            font-size: 14px;
            color: #475569;
        }

        .budget-amount {
            font-size: 14px;
            font-weight: 500;
            color: #000;
        }

        .budget-total-label {
            font-size: 14px;
            font-weight: 700;
            color: #000;
        }

        .budget-total-amount {
            font-size: 14px;
            font-weight: 700;
            color: #3f6516;
        }

        /* Document Item */
        .doc-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background-color: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
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
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .preview-btn {
            color: #990dd1;
            font-size: 13px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 8px;
        }

        .preview-btn:hover {
            background-color: rgba(153, 13, 209, 0.1);
        }

        /* Right Sidebar */
        .sidebar-card {
            background-color: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }

        .status-icon {
            width: 40px;
            height: 40px;
            border-radius: 9999px;
            background-color: rgba(63, 101, 22, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-icon span {
            color: #3f6516;
            font-size: 24px;
        }

        .status-title {
            font-size: 14px;
            font-weight: 700;
            color: #3f6516;
        }

        .status-date {
            font-size: 11px;
            color: #3b3b3b;
        }

        .info-row {
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .info-row-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #3b3b3b;
        }

        .info-row-value {
            font-size: 16px;
            font-family: monospace;
            font-weight: 700;
            color: #990dd1;
            margin-top: 4px;
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
            background-color: #990dd1;
        }

        .history-dot-emerald {
            background-color: #3f6516;
        }

        .history-title {
            font-size: 11px;
            font-weight: 700;
            color: #000;
        }

        .history-title-emerald {
            color: #3f6516;
        }

        .history-date {
            font-size: 11px;
            color: #3b3b3b;
            margin-top: 2px;
        }

        /* Info Card */
        .info-card {
            background-color: rgba(81, 112, 255, 0.1);
            padding: 16px;
            border-radius: 12px;
            border: 1px solid rgba(81, 112, 255, 0.2);
            display: flex;
            gap: 12px;
        }

        .info-card span {
            color: #5170ff;
            font-size: 24px;
        }

        .info-card-title {
            font-size: 11px;
            font-weight: 700;
            color: #5170ff;
        }

        .info-card-text {
            font-size: 11px;
            color: #3b3b3b;
        }

        /* Back Button */
        .back-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #f1f5f9;
            color: #334155;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .back-btn:hover {
            background-color: #e2e8f0;
        }

        /* Sticky Sidebar */
        .sticky-sidebar {
            position: sticky;
            top: 80px;
            align-self: flex-start;
            max-height: calc(100vh - 96px);
            overflow-y: auto;
            padding-bottom: 16px;
        }

        .items-center {
            align-items: center;
        }

        .gap-3 {
            gap: 12px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .space-y-4>*+* {
            margin-top: 16px;
        }

        .space-y-2>*+* {
            margin-top: 8px;
        }

        .text-sm {
            font-size: 14px;
        }

        .text-base {
            font-size: 16px;
        }

        .text-xl {
            font-size: 20px;
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

        .text-000 {
            color: #000;
        }

        .text-3b3b3b {
            color: #3b3b3b;
        }

        .text-990dd1 {
            color: #990dd1;
        }

        .text-3f6516 {
            color: #3f6516;
        }

        .text-5170ff {
            color: #5170ff;
        }

        .text-slate-400 {
            color: #94a3b8;
        }

        .w-full {
            width: 100%;
        }

        .cursor-pointer {
            cursor: pointer;
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
            font-size: 10px;
        }

        .opacity-20 {
            opacity: 0.2;
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
    </style>
</head>

<body>
    <header class="staff-header">
        <div>
            <h2 class="header-title">Lorem ipsum dolor sit amet, consectetur adipiscing elit - Activity Design</h2>
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
    <?php include 'sidebar.php'; ?>
    <main class="pl-64 pt-16 min-h-screen">
        <div class="p-10 flex gap-8">
            <section class="flex-06 bg-white rounded-xl flex flex-col overflow-hidden shadow-sm border">
                <div class="title-block">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="status-badge">
                            <div class="status-dot"></div><span class="status-text">Approved</span>
                        </div><span class="control-number">BSU-GAD-2026-003</span>
                    </div>
                    <h2 class="report-title">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h2>
                    <div class="info-grid">
                        <div class="info-item"><span class="info-label">Submitted By</span><span class="info-value info-value-emerald">Dr. Maria Santos</span></div>
                        <div class="info-item"><span class="info-label">Office</span><span class="info-value">GAD Office</span></div>
                        <div class="info-item"><span class="info-label">Email</span><span class="info-value">maria.santos@bsu.edu.ph</span></div>
                        <div class="info-item"><span class="info-label">Date Submitted</span><span class="info-value">Jan 15, 2026</span></div>
                        <div class="info-item"><span class="info-label">Category</span><span class="info-value">Activity Design</span></div>
                    </div>
                </div>
                <div class="flex-1 overflow-y-auto custom-scrollbar p-8 space-y-6">
                    <div class="section-card">
                        <div class="section-title"><span class="text-2xl">📄</span>
                            <h3>Activity Description</h3>
                        </div>
                        <p class="text-sm text-3b3b3b leading-relaxed">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    </div>
                    <div class="section-card">
                        <div class="section-title"><span class="text-2xl">📅</span>
                            <h3>Schedule & Venue</h3>
                        </div>
                        <div class="grid-2">
                            <div><label class="info-label">Start Date</label>
                                <p class="text-sm text-3b3b3b mt-1">Feb 20, 2026</p>
                            </div>
                            <div><label class="info-label">End Date</label>
                                <p class="text-sm text-3b3b3b mt-1">Feb 22, 2026</p>
                            </div>
                            <div><label class="info-label">Start Time</label>
                                <p class="text-sm text-3b3b3b mt-1">8:00 AM</p>
                            </div>
                            <div><label class="info-label">End Time</label>
                                <p class="text-sm text-3b3b3b mt-1">5:00 PM</p>
                            </div>
                            <div class="col-span-2"><label class="info-label">Venue</label>
                                <p class="text-sm text-3b3b3b mt-1">Lorem ipsum dolor sit amet</p>
                            </div>
                        </div>
                    </div>
                    <div class="section-card">
                        <div class="section-title"><span class="text-2xl">💰</span>
                            <h3>Budget & Participants</h3>
                        </div>
                        <div class="grid-2">
                            <div><label class="info-label">Total Proposed Budget</label>
                                <p class="text-xl font-bold text-000">₱97,500.00</p>
                            </div>
                            <div><label class="info-label">Target Participants</label>
                                <p class="text-lg font-bold text-000 mt-1">35 participants</p>
                            </div>
                            <div><label class="info-label">Male Target</label>
                                <p class="text-sm text-3b3b3b mt-1">15 participants</p>
                            </div>
                            <div><label class="info-label">Female Target</label>
                                <p class="text-sm text-3b3b3b mt-1">20 participants</p>
                            </div>
                        </div>
                    </div>
                    <div class="section-card">
                        <div class="section-title"><span class="text-2xl">🧾</span>
                            <h3>Budget Breakdown</h3>
                        </div>
                        <div class="budget-row"><span class="budget-label">Training Materials</span><span class="budget-amount">₱25,000.00</span></div>
                        <div class="budget-row"><span class="budget-label">Honorarium for Resource Speakers (2 persons)</span><span class="budget-amount">₱20,000.00</span></div>
                        <div class="budget-row"><span class="budget-label">Meals & Snacks (35 pax x 3 days)</span><span class="budget-amount">₱31,500.00</span></div>
                        <div class="budget-row"><span class="budget-label">Certificates & Printing</span><span class="budget-amount">₱5,000.00</span></div>
                        <div class="budget-row"><span class="budget-label">Miscellaneous / Contingency</span><span class="budget-amount">₱16,000.00</span></div>
                        <div class="budget-total"><span class="budget-total-label">TOTAL</span><span class="budget-total-amount">₱97,500.00</span></div>
                    </div>
                    <div class="section-card">
                        <div class="section-title"><span class="text-2xl">📎</span>
                            <h3>Uploaded Documents</h3>
                        </div>
                        <div class="doc-item">
                            <div class="doc-info"><span class="text-2xl text-red-500">📄</span>
                                <div>
                                    <p class="doc-title">GST_Activity_Design.pdf</p>
                                    <p class="doc-meta">1.2 MB • Uploaded Jan 15, 2026</p>
                                </div>
                            </div><button class="preview-btn">👁️ Preview</button>
                        </div>
                    </div>
                </div>
            </section>
            <section class="flex-04 sticky-sidebar">
                <div class="sidebar-card">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="status-icon"><span>✅</span></div>
                        <div>
                            <p class="status-title">Approved</p>
                            <p class="status-date">Approved on Feb 10, 2026</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="info-row"><label class="info-row-label">Control Number</label>
                            <p class="info-row-value">BSU-GAD-2026-003</p>
                        </div>
                        <div><label class="info-row-label">Date of Assessment</label>
                            <p class="text-sm text-3b3b3b mt-1">February 10, 2026</p>
                        </div>
                        <div><label class="info-row-label">Accomplishment Report Deadline</label>
                            <p class="text-sm text-3b3b3b mt-1">March 12, 2026</p>
                        </div>
                    </div>
                </div>
                <div class="sidebar-card">
                    <h3 class="font-bold text-sm uppercase tracking-widest text-3b3b3b mb-4">Submission History</h3>
                    <div class="history-container">
                        <div class="history-item">
                            <div class="history-dot history-dot-purple"></div>
                            <p class="history-title">Submitted for Review</p>
                            <p class="history-date">Jan 15, 2026 • 10:23 AM</p>
                        </div>
                        <div class="history-item">
                            <div class="history-dot history-dot-emerald"></div>
                            <p class="history-title history-title-emerald">Approved</p>
                            <p class="history-date">Feb 10, 2026 • 09:45 AM</p>
                        </div>
                    </div>
                </div>
                <div class="info-card"><span>ℹ️</span>
                    <div>
                        <p class="info-card-title">Archived Document</p>
                        <p class="info-card-text">This activity design has been approved and archived. You can only view the document.</p>
                    </div>
                </div>
                <a href="list_and_filters.php" class="back-btn">← Back to Submissions</a>
            </section>
        </div>
    </main>
    <div class="fixed bottom-4 left-64 right-0 text-center pointer-events-none z-40">
        <p class="text-10px text-3b3b3b/20 font-medium">Benguet State University - Gender and Development Information Management System | GAD-IMS v1.0</p>
    </div>
</body>

</html>