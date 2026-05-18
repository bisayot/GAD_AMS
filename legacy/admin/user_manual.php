<?php
// staff/user_manual.php - User Manual Page
require_once '../auth.php';
require_role(['gad_staff', 'admin']);
$page_title = 'User Manual';
include 'sidebar.php';
// include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Manual | GAD-IMS</title>
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

        .material-symbols-outlined {
            display: inline-block;
            font-size: 24px;
            font-family: monospace;
        }

        .manual-section {
            scroll-margin-top: 80px;
        }

        .sidebar-nav-item {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .sidebar-nav-item:hover {
            background: #990dd1;
            color: white;
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

        .flex {
            display: flex;
        }

        .gap-8 {
            gap: 32px;
        }

        .w-64 {
            width: 256px;
        }

        .flex-shrink-0 {
            flex-shrink: 0;
        }

        .sticky {
            position: sticky;
        }

        .top-20 {
            top: 80px;
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

        .p-4 {
            padding: 16px;
        }

        .text-sm {
            font-size: 14px;
        }

        .font-bold {
            font-weight: 700;
        }

        .text-000 {
            color: #000;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .pb-2 {
            padding-bottom: 8px;
        }

        .border-b {
            border-bottom: 1px solid #e2e8f0;
        }

        .space-y-2>*+* {
            margin-top: 8px;
        }

        .text-11px {
            font-size: 11px;
        }

        .block {
            display: block;
        }

        .px-3 {
            padding-left: 12px;
            padding-right: 12px;
        }

        .py-2 {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .rounded-lg {
            border-radius: 8px;
        }

        .text-3b3b3b {
            color: #3b3b3b;
        }

        .hover\:bg-990dd1:hover {
            background-color: #990dd1;
        }

        .hover\:text-white:hover {
            color: white;
        }

        .flex-1 {
            flex: 1;
        }

        .space-y-8>*+* {
            margin-top: 32px;
        }

        .text-2xl {
            font-size: 24px;
        }

        .leading-relaxed {
            line-height: 1.625;
        }

        .pt-6 {
            padding-top: 24px;
        }

        .border-t {
            border-top: 1px solid #e2e8f0;
        }

        .text-lg {
            font-size: 18px;
        }

        .mb-3 {
            margin-bottom: 12px;
        }

        .space-y-3>*+* {
            margin-top: 12px;
        }

        .text-990dd1 {
            color: #990dd1;
        }

        .mb-1 {
            margin-bottom: 4px;
        }

        .list-disc {
            list-style-type: disc;
        }

        .list-inside {
            list-style-position: inside;
        }

        .ml-4 {
            margin-left: 16px;
        }

        .ml-6 {
            margin-left: 24px;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mt-3 {
            margin-top: 12px;
        }

        .italic {
            font-style: italic;
        }

        .bg-slate-50 {
            background-color: #f8fafc;
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

        .font-medium {
            font-weight: 500;
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
            <h2 class="header-title">User Manual</h2>
            <div class="flex items-center gap-2">
                <span class="header-subtitle">Gender and Development Office - Information Management System (GAD-IMS)</span>
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

    <main class="pl-64 pt-16 min-h-screen">
        <div class="p-8">
            <div class="flex gap-8">
                <div class="w-64 flex-shrink-0">
                    <div class="sticky top-20 bg-white rounded-xl shadow-sm border p-4">
                        <h3 class="text-sm font-bold text-000 mb-4 pb-2 border-b">Table of Contents</h3>
                        <ul class="space-y-2 text-11px">
                            <li><a href="#intro" class="sidebar-nav-item block px-3 py-2 rounded-lg text-3b3b3b hover:bg-990dd1 hover:text-white transition">1. Introduction</a></li>
                            <li><a href="#getting-started" class="sidebar-nav-item block px-3 py-2 rounded-lg text-3b3b3b hover:bg-990dd1 hover:text-white transition">2. Getting Started</a></li>
                            <li><a href="#dashboard" class="sidebar-nav-item block px-3 py-2 rounded-lg text-3b3b3b hover:bg-990dd1 hover:text-white transition">3. Dashboard Overview</a></li>
                            <li><a href="#submissions" class="sidebar-nav-item block px-3 py-2 rounded-lg text-3b3b3b hover:bg-990dd1 hover:text-white transition">4. Submitting Forms</a></li>
                            <li><a href="#viewing" class="sidebar-nav-item block px-3 py-2 rounded-lg text-3b3b3b hover:bg-990dd1 hover:text-white transition">5. Viewing Submissions</a></li>
                            <li><a href="#mandates" class="sidebar-nav-item block px-3 py-2 rounded-lg text-3b3b3b hover:bg-990dd1 hover:text-white transition">6. GAD Mandates Management</a></li>
                            <li><a href="#budget" class="sidebar-nav-item block px-3 py-2 rounded-lg text-3b3b3b hover:bg-990dd1 hover:text-white transition">7. Budget Monitoring</a></li>
                            <li><a href="#reports" class="sidebar-nav-item block px-3 py-2 rounded-lg text-3b3b3b hover:bg-990dd1 hover:text-white transition">8. Reports</a></li>
                            <li><a href="#faq" class="sidebar-nav-item block px-3 py-2 rounded-lg text-3b3b3b hover:bg-990dd1 hover:text-white transition">9. Frequently Asked Questions</a></li>
                            <li><a href="#support" class="sidebar-nav-item block px-3 py-2 rounded-lg text-3b3b3b hover:bg-990dd1 hover:text-white transition">10. Technical Support</a></li>
                        </ul>
                    </div>
                </div>

                <div class="flex-1">
                    <div class="bg-white rounded-xl shadow-sm border p-8 space-y-8">
                        <div id="intro" class="manual-section">
                            <h1 class="text-2xl font-bold text-000 mb-4">User Manual</h1>
                            <p class="text-11px text-3b3b3b leading-relaxed">Welcome to the Benguet State University Gender and Development Information Management System (GAD-IMS) User Manual. This guide will help you navigate and use the system effectively as a GAD Staff user.</p>
                        </div>
                        <div id="getting-started" class="manual-section pt-6 border-t">
                            <h2 class="text-lg font-bold text-000 mb-3">2. Getting Started</h2>
                            <div class="space-y-3">
                                <div>
                                    <h3 class="text-sm font-semibold text-990dd1 mb-1">2.1 Logging In</h3>
                                    <p class="text-11px text-3b3b3b">Access the system via your web browser using the provided URL. Enter your username and password credentials provided by the system administrator.</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-990dd1 mb-1">2.2 Navigating the Dashboard</h3>
                                    <p class="text-11px text-3b3b3b">After logging in, you will be directed to the Staff Dashboard. The left sidebar contains all main navigation menus including Dashboard, GAD Programs, Mandates Management, Budget Monitoring, and Reports.</p>
                                </div>
                            </div>
                        </div>
                        <div id="dashboard" class="manual-section pt-6 border-t">
                            <h2 class="text-lg font-bold text-000 mb-3">3. Dashboard Overview</h2>
                            <p class="text-11px text-3b3b3b mb-3">The dashboard provides a quick overview of key metrics and pending activities:</p>
                            <ul class="list-disc list-inside text-11px text-3b3b3b space-y-1 ml-4">
                                <li>Pending Activities - Shows number of activity designs awaiting review</li>
                                <li>Activity Designs - Count of submitted activity designs</li>
                                <li>Accomplishment Reports - Count of submitted accomplishment reports</li>
                                <li>GAD Budget - Displays current budget allocation and utilization</li>
                                <li>Calendar Widget - Shows upcoming deadlines and scheduled activities</li>
                                <li>Recent Activity Logs - Tracks recent system activities</li>
                            </ul>
                        </div>
                        <div id="submissions" class="manual-section pt-6 border-t">
                            <h2 class="text-lg font-bold text-000 mb-3">4. Submitting Forms</h2>
                            <div class="mb-4">
                                <h3 class="text-sm font-semibold text-990dd1 mb-2">4.1 Activity Design Submission</h3>
                                <ol class="list-decimal list-inside text-11px text-3b3b3b space-y-1 ml-4">
                                    <li>Click the "New Submission" button on the sidebar or the floating action button</li>
                                    <li>Select "Activity Design" from the options</li>
                                    <li>Fill out the form with required information</li>
                                    <li>Click "Submit Activity Design" to complete the submission</li>
                                </ol>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-990dd1 mb-2">4.2 Accomplishment Report Submission</h3>
                                <ol class="list-decimal list-inside text-11px text-3b3b3b space-y-1 ml-4">
                                    <li>Click the "New Submission" button on the sidebar or the floating action button</li>
                                    <li>Select "Accomplishment Report" from the options</li>
                                    <li>Fill out the form with required information</li>
                                    <li>Click "Submit Accomplishment Report" to complete the submission</li>
                                </ol>
                            </div>
                        </div>
                        <div id="viewing" class="manual-section pt-6 border-t">
                            <h2 class="text-lg font-bold text-000 mb-3">5. Viewing Submissions</h2>
                            <p class="text-11px text-3b3b3b mb-3">As a GAD Staff user, you have monitoring access to view all submissions:</p>
                            <ul class="list-disc list-inside text-11px text-3b3b3b space-y-1 ml-4">
                                <li><strong>Activity Designs:</strong> Navigate to "Submitted Activity Designs" from the sidebar</li>
                                <li><strong>Accomplishment Reports:</strong> Navigate to "Submitted Accomplishment Reports" to view all reports</li>
                                <li><strong>Filters:</strong> Use the filter bar to sort by status, form type, office, or search</li>
                            </ul>
                        </div>
                        <div id="mandates" class="manual-section pt-6 border-t">
                            <h2 class="text-lg font-bold text-000 mb-3">6. GAD Mandates Management</h2>
                            <p class="text-11px text-3b3b3b">This section displays all GAD mandates where the GAD Office is the responsible unit. View the complete list of GAD Office mandates, filter by status, search for specific mandates, and access the GAD Plan & Budget document.</p>
                        </div>
                        <div id="budget" class="manual-section pt-6 border-t">
                            <h2 class="text-lg font-bold text-000 mb-3">7. Budget Monitoring</h2>
                            <p class="text-11px text-3b3b3b">The budget monitoring page provides total GAD Budget overview, GAD Office Budget breakdown, balance available, mandate-based budget utilization tracking with progress bars, and recent budget utilization logs.</p>
                        </div>
                        <div id="reports" class="manual-section pt-6 border-t">
                            <h2 class="text-lg font-bold text-000 mb-3">8. Reports</h2>
                            <p class="text-11px text-3b3b3b">Generate and view annual reports including comprehensive GAD accomplishments with sex-disaggregated data, GAD Plan & Budget document in table or PDF format, and export to Excel functionality.</p>
                        </div>
                        <div id="faq" class="manual-section pt-6 border-t">
                            <h2 class="text-lg font-bold text-000 mb-3">9. Frequently Asked Questions</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-sm font-semibold text-990dd1">Q: What should I do if I encounter an error while submitting a form?</h3>
                                    <p class="text-11px text-3b3b3b mt-1">A: Check that all required fields are filled out correctly. Ensure file sizes are within limits. If the issue persists, contact technical support.</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-990dd1">Q: Can I edit a submission after it has been submitted?</h3>
                                    <p class="text-11px text-3b3b3b mt-1">A: As a monitoring user, you cannot edit submissions. Please contact the GAD Office administrator for changes.</p>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-990dd1">Q: How do I view the full content of uploaded files?</h3>
                                    <p class="text-11px text-3b3b3b mt-1">A: Click the "Preview" button on any document to view it in the browser.</p>
                                </div>
                            </div>
                        </div>
                        <div id="support" class="manual-section pt-6 border-t">
                            <h2 class="text-lg font-bold text-000 mb-3">10. Technical Support</h2>
                            <p class="text-11px text-3b3b3b mb-3">For technical assistance, bug reports, or system inquiries, please contact:</p>
                            <div class="p-4 bg-slate-50 rounded-lg">
                                <p class="text-11px font-medium text-000">GAD-IMS System Administrator</p>
                                <p class="text-11px text-3b3b3b">Gender and Development Office, Benguet State University</p>
                                <p class="text-11px text-3b3b3b">Email: gad.ims@bsu.edu.ph</p>
                                <p class="text-11px text-3b3b3b">Tel: (074) 422-2401 loc 123</p>
                            </div>
                            <p class="text-11px text-3b3b3b mt-3">System Version: 1.0 | Last Updated: January 2026</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="fixed bottom-4 left-64 right-0 text-center pointer-events-none z-40">
        <p class="text-10px text-3b3b3b opacity-20 font-medium">Benguet State University - Gender and Development Information Management System | GAD-IMS v1.0</p>
    </div>
    <script>
        document.querySelectorAll('.sidebar-nav-item').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>