<?php
// staff/budget_utilization.php - Edit and Update Budget Utilization for TWGs (All Columns Editable)
require_once '../auth.php';
require_role(['gad_staff', 'admin']);
$page_title = 'Budget Utilization';
include 'sidebar.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Budget Utilization | GAD-IMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        main {
            margin-left: 35px;
        }

        body {
            background: #f8f9fb;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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

        .mb-6 {
            margin-bottom: 24px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 8px;
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

        .gap-3 {
            gap: 12px;
        }

        .justify-between {
            justify-content: space-between;
        }

        .justify-center {
            justify-content: center;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        /* Typography */
        .text-4xl {
            font-size: 36px;
        }

        .text-2xl {
            font-size: 24px;
        }

        .text-xl {
            font-size: 20px;
        }

        .text-lg {
            font-size: 18px;
        }

        .text-sm {
            font-size: 14px;
        }

        .text-xs {
            font-size: 12px;
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

        .font-bold {
            font-weight: 700;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-black {
            font-weight: 900;
        }

        .font-semibold {
            font-weight: 600;
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

        .text-3b3b3b {
            color: #3b3b3b;
        }

        .text-000 {
            color: #000;
        }

        .text-3f6516 {
            color: #3f6516;
        }

        .text-ecd224 {
            color: #ecd224;
        }

        .text-white {
            color: white;
        }

        .text-slate-400 {
            color: #94a3b8;
        }

        /* Background & Borders */
        .bg-white {
            background-color: white;
        }

        .bg-slate-50 {
            background-color: #f8fafc;
        }

        .bg-slate-100 {
            background-color: #f1f5f9;
        }

        .bg-990dd1 {
            background-color: #990dd1;
        }

        .bg-3f6516 {
            background-color: #3f6516;
        }

        .bg-ecd224\/20 {
            background-color: rgba(236, 210, 36, 0.2);
        }

        .bg-3f6516\/20 {
            background-color: rgba(63, 101, 22, 0.2);
        }

        .bg-red-500 {
            background-color: #ef4444;
        }

        .bg-black\/50 {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .rounded-xl {
            border-radius: 12px;
        }

        .rounded-2xl {
            border-radius: 16px;
        }

        .rounded-lg {
            border-radius: 8px;
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .border {
            border: 1px solid #e2e8f0;
        }

        .border-slate-100 {
            border-color: #f1f5f9;
        }

        .border-slate-300 {
            border-color: #cbd5e1;
        }

        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .shadow-2xl {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* Grid */
        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .md\:grid-cols-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .gap-4 {
            gap: 16px;
        }

        .gap-1 {
            gap: 4px;
        }

        .flex-1 {
            flex: 1;
        }

        .w-full {
            width: 100%;
        }

        .w-10 {
            width: 40px;
        }

        .h-10 {
            height: 40px;
        }

        .h-2 {
            height: 8px;
        }

        .h-1\.5 {
            height: 6px;
        }

        /* Padding & Margin */
        .p-8 {
            padding: 32px;
        }

        .p-6 {
            padding: 24px;
        }

        .p-5 {
            padding: 20px;
        }

        .p-4 {
            padding: 16px;
        }

        .p-3 {
            padding: 12px;
        }

        .px-6 {
            padding-left: 24px;
            padding-right: 24px;
        }

        .px-4 {
            padding-left: 16px;
            padding-right: 16px;
        }

        .px-3 {
            padding-left: 12px;
            padding-right: 12px;
        }

        .px-2 {
            padding-left: 8px;
            padding-right: 8px;
        }

        .py-4 {
            padding-top: 16px;
            padding-bottom: 16px;
        }

        .py-3 {
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .py-2\.5 {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .py-2 {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .py-1\.5 {
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .py-1 {
            padding-top: 4px;
            padding-bottom: 4px;
        }

        .py-0\.5 {
            padding-top: 2px;
            padding-bottom: 2px;
        }

        /* Table */
        .overflow-hidden {
            overflow: hidden;
        }

        .overflow-x-auto {
            overflow-x: auto;
        }

        .divide-y>*+* {
            border-top-width: 1px;
            border-color: #e2e8f0;
        }

        .divide-slate-100>*+* {
            border-color: #f1f5f9;
        }

        .border-t {
            border-top: 1px solid #e2e8f0;
        }

        .border-b {
            border-bottom: 1px solid #e2e8f0;
        }

        .hover\:bg-slate-50:hover {
            background-color: #f8fafc;
        }

        .transition-colors {
            transition: background-color 0.2s;
        }

        .transition {
            transition: all 0.2s ease;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        /* Progress Bar */
        .progress-bar {
            transition: width 0.5s ease;
        }

        /* Editable Cell */
        .editable-cell {
            cursor: pointer;
            position: relative;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: inline-block;
            min-width: 120px;
        }

        .editable-cell:hover {
            background-color: #f3e8ff;
            outline: 1px solid #990dd1;
        }

        .editable-cell.editing {
            background-color: #fff;
            outline: 2px solid #990dd1;
            padding: 0;
        }

        .editable-cell.editing input {
            width: 100%;
            padding: 8px 12px;
            border: none;
            outline: none;
            background: transparent;
            font-size: 14px;
            font-weight: 500;
        }

        .budget-cell {
            font-weight: 600;
            color: #000;
        }

        .utilized-cell {
            font-weight: 600;
            color: #3f6516;
        }

        .remaining-cell {
            font-weight: 600;
            color: #990dd1;
        }

        /* Modal - Hidden by default */
        .modal-hidden {
            display: none;
        }

        .modal-visible {
            display: flex;
        }

        .confirm-modal {
            animation: fadeIn 0.2s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
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

        .bottom-4 {
            bottom: 16px;
        }

        .left-56 {
            left: 224px;
        }

        .right-0 {
            right: 0;
        }

        .z-40 {
            z-index: 40;
        }

        .z-50 {
            z-index: 50;
        }

        .z-100 {
            z-index: 100;
        }

        .max-w-sm {
            max-width: 384px;
        }

        .mx-4 {
            margin-left: 16px;
            margin-right: 16px;
        }

        .pointer-events-none {
            pointer-events: none;
        }

        .opacity-20 {
            opacity: 0.2;
        }

        .bg-3f6516 {
            background-color: #3f6516;
        }

        .hover\:bg-355212:hover {
            background-color: #355212;
        }

        .border-slate-300 {
            border-color: #cbd5e1;
        }

        .hover\:bg-slate-50:hover {
            background-color: #f8fafc;
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

        @media (min-width: 768px) {
            .md\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }.staff-header {
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
            <h2 class="header-title">TWG Budget Utilization</h2>
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

    <main class="pl-56 pt-16 min-h-screen">
        <div class="p-8">

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl p-4 shadow-sm border">
                    <p class="text-11px text-3b3b3b uppercase tracking-wider">Total GAD Budget</p>
                    <p class="text-2xl font-bold text-000" id="totalBudgetDisplay">₱0</p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border">
                    <p class="text-11px text-3b3b3b uppercase tracking-wider">Total Utilized</p>
                    <p class="text-2xl font-bold text-3f6516" id="totalUtilizedDisplay">₱0</p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border">
                    <p class="text-11px text-3b3b3b uppercase tracking-wider">Remaining Balance</p>
                    <p class="text-2xl font-bold text-990dd1" id="totalRemainingDisplay">₱0</p>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm border">
                    <p class="text-11px text-3b3b3b uppercase tracking-wider">Overall Utilization</p>
                    <div class="flex items-center gap-2 mt-1">
                        <p class="text-2xl font-bold text-000" id="totalPercentageDisplay">0%</p>
                        <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-990dd1 rounded-full progress-bar" id="totalProgressBar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Utilization Table -->
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b">
                                <th class="px-6 py-4 text-11px font-black uppercase tracking-wider text-3b3b3b">#</th>
                                <th class="px-6 py-4 text-11px font-black uppercase tracking-wider text-3b3b3b">Technical Working Group (TWG)</th>
                                <th class="px-6 py-4 text-11px font-black uppercase tracking-wider text-3b3b3b">Budget (₱)</th>
                                <th class="px-6 py-4 text-11px font-black uppercase tracking-wider text-3b3b3b">Utilized Amount (₱)</th>
                                <th class="px-6 py-4 text-11px font-black uppercase tracking-wider text-3b3b3b">Remaining (₱)</th>
                                <th class="px-6 py-4 text-11px font-black uppercase tracking-wider text-3b3b3b">Utilization %</th>
                            </tr>
                        </thead>
                        <tbody id="budgetTableBody" class="divide-y divide-slate-100">
                            <!-- Dynamic content loaded via JavaScript -->
                        </tbody>
                        <tfoot class="bg-slate-50">
                            <tr class="border-t font-bold">
                                <td colspan="2" class="px-6 py-4 text-000">TOTAL</td>
                                <td class="px-6 py-4 text-000" id="totalBudget">₱0</td>
                                <td class="px-6 py-4 text-3f6516" id="totalUtilized">₱0</td>
                                <td class="px-6 py-4 text-990dd1" id="totalRemaining">₱0</td>
                                <td class="px-6 py-4 text-000" id="totalPercent">0%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Mini Instruction -->
            <div class="mt-4 text-center">
                <p class="text-11px text-3b3b3b">
                    <span class="text-sm align-middle">ℹ️</span>
                    Double-click on any Budget, Utilized, or Remaining cell to edit. Changes auto-sync across all fields.
                </p>
            </div>
        </div>
    </main>

    <!-- Watermark -->
    <div class="fixed bottom-4 left-56 right-0 text-center pointer-events-none z-40">
        <p class="text-9px text-3b3b3b/20 font-medium">Benguet State University - Gender and Development Information Management System | GAD-IMS v1.0</p>
    </div>

    <!-- Confirmation Modal - Hidden by default, only appears when editing -->
    <div id="confirmationModal" class="fixed inset-0 bg-black/50 items-center justify-center z-100 transition-all modal-hidden">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-2xl confirm-modal">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-ecd224/20 flex items-center justify-center">
                    <span class="text-ecd224 text-xl">✏️</span>
                </div>
                <h3 class="text-lg font-bold text-000">Confirm Update</h3>
            </div>
            <p class="text-3b3b3b text-sm mb-2">Are you sure you want to update the <span id="modalFieldName" class="font-bold">Budget</span> for:</p>
            <p class="font-bold text-000 bg-slate-50 p-3 rounded-lg text-sm mb-4" id="modalTWGName">-</p>
            <div class="flex justify-between items-center mb-4 p-3 bg-slate-50 rounded-lg">
                <span class="text-sm text-3b3b3b">Current Value:</span>
                <span class="font-bold text-990dd1" id="modalOldValue">₱0</span>
            </div>
            <div class="flex justify-between items-center mb-5 p-3 bg-3f6516/20 rounded-lg">
                <span class="text-sm text-3b3b3b">New Value:</span>
                <span class="font-bold text-3f6516 text-lg" id="modalNewValue">₱0</span>
            </div><br>
            <div class="flex gap-3">
                <button id="confirmUpdateBtn" class="flex-1 bg-3f6516 text-white py-2.5 rounded-lg font-bold text-sm hover:bg-355212 transition cursor-pointer">Confirm Update</button>
                <button id="cancelUpdateBtn" class="flex-1 border border-slate-300 py-2.5 rounded-lg font-bold text-sm hover:bg-slate-50 transition cursor-pointer">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // Budget Data for each TWG
        let budgetData = [{
                id: 1,
                twg: "Gender and Development Office",
                budget: 74521951,
                utilized: 28500000,
                remaining: 46021951,
                percentage: 38.3
            },
            {
                id: 2,
                twg: "College of Agriculture",
                budget: 3500000,
                utilized: 1250000,
                remaining: 2250000,
                percentage: 35.7
            },
            {
                id: 3,
                twg: "College of Education",
                budget: 2500000,
                utilized: 980000,
                remaining: 1520000,
                percentage: 39.2
            },
            {
                id: 4,
                twg: "College of Engineering",
                budget: 1800000,
                utilized: 450000,
                remaining: 1350000,
                percentage: 25.0
            },
            {
                id: 5,
                twg: "College of Nursing",
                budget: 1200000,
                utilized: 720000,
                remaining: 480000,
                percentage: 60.0
            },
            {
                id: 6,
                twg: "College of Arts and Sciences",
                budget: 2100000,
                utilized: 890000,
                remaining: 1210000,
                percentage: 42.4
            },
            {
                id: 7,
                twg: "College of Veterinary Medicine",
                budget: 950000,
                utilized: 310000,
                remaining: 640000,
                percentage: 32.6
            },
            {
                id: 8,
                twg: "College of Business Administration",
                budget: 1100000,
                utilized: 440000,
                remaining: 660000,
                percentage: 40.0
            },
            {
                id: 9,
                twg: "Human Resources Management Office",
                budget: 1500000,
                utilized: 620000,
                remaining: 880000,
                percentage: 41.3
            },
            {
                id: 10,
                twg: "Registrar's Office",
                budget: 800000,
                utilized: 250000,
                remaining: 550000,
                percentage: 31.3
            },
            {
                id: 11,
                twg: "Extension Office",
                budget: 4200000,
                utilized: 1850000,
                remaining: 2350000,
                percentage: 44.0
            },
            {
                id: 12,
                twg: "Research and Development Office",
                budget: 2800000,
                utilized: 1100000,
                remaining: 1700000,
                percentage: 39.3
            },
            {
                id: 13,
                twg: "Information Technology Office",
                budget: 650000,
                utilized: 280000,
                remaining: 370000,
                percentage: 43.1
            },
            {
                id: 14,
                twg: "Budget Office",
                budget: 550000,
                utilized: 200000,
                remaining: 350000,
                percentage: 36.4
            },
            {
                id: 15,
                twg: "Guidance and Counseling Office",
                budget: 480000,
                utilized: 195000,
                remaining: 285000,
                percentage: 40.6
            },
            {
                id: 16,
                twg: "University Library",
                budget: 420000,
                utilized: 150000,
                remaining: 270000,
                percentage: 35.7
            },
            {
                id: 17,
                twg: "Health Services Office",
                budget: 580000,
                utilized: 320000,
                remaining: 260000,
                percentage: 55.2
            },
            {
                id: 18,
                twg: "Student Affairs Office",
                budget: 720000,
                utilized: 310000,
                remaining: 410000,
                percentage: 43.1
            },
            {
                id: 19,
                twg: "General Services Office",
                budget: 460000,
                utilized: 180000,
                remaining: 280000,
                percentage: 39.1
            },
            {
                id: 20,
                twg: "Legal Office",
                budget: 350000,
                utilized: 95000,
                remaining: 255000,
                percentage: 27.1
            },
            {
                id: 21,
                twg: "Internal Audit Office",
                budget: 320000,
                utilized: 85000,
                remaining: 235000,
                percentage: 26.6
            },
            {
                id: 22,
                twg: "Public Affairs Office",
                budget: 380000,
                utilized: 140000,
                remaining: 240000,
                percentage: 36.8
            }
        ];

        let currentEditingCell = null;
        let currentEditingId = null;
        let currentEditingField = null;
        let pendingValue = null;

        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        }

        function calculateAndUpdateTotals() {
            let totalBudget = 0;
            let totalUtilized = 0;
            budgetData.forEach(item => {
                totalBudget += item.budget;
                totalUtilized += item.utilized;
            });
            let totalRemaining = totalBudget - totalUtilized;
            let totalPercentage = totalBudget > 0 ? (totalUtilized / totalBudget * 100).toFixed(1) : 0;

            document.getElementById('totalBudgetDisplay').innerHTML = formatCurrency(totalBudget);
            document.getElementById('totalUtilizedDisplay').innerHTML = formatCurrency(totalUtilized);
            document.getElementById('totalRemainingDisplay').innerHTML = formatCurrency(totalRemaining);
            document.getElementById('totalPercentageDisplay').innerHTML = `${totalPercentage}%`;
            document.getElementById('totalProgressBar').style.width = `${totalPercentage}%`;

            document.getElementById('totalBudget').innerHTML = formatCurrency(totalBudget);
            document.getElementById('totalUtilized').innerHTML = formatCurrency(totalUtilized);
            document.getElementById('totalRemaining').innerHTML = formatCurrency(totalRemaining);
            document.getElementById('totalPercent').innerHTML = `${totalPercentage}%`;
        }

        function updateItemAndRow(item) {
            item.remaining = item.budget - item.utilized;
            item.percentage = (item.utilized / item.budget * 100).toFixed(1);

            const row = document.querySelector(`tr[data-id="${item.id}"]`);
            if (row) {
                const budgetCell = row.querySelector('.budget-display');
                const utilizedCell = row.querySelector('.utilized-display');
                const remainingCell = row.querySelector('.remaining-display');
                const percentageCell = row.querySelector('.percentage-display');
                const progressBar = row.querySelector('.progress-fill');

                if (budgetCell) {
                    budgetCell.textContent = formatCurrency(item.budget);
                    budgetCell.dataset.value = item.budget;
                }
                if (utilizedCell) {
                    utilizedCell.textContent = formatCurrency(item.utilized);
                    utilizedCell.dataset.value = item.utilized;
                }
                if (remainingCell) {
                    remainingCell.textContent = formatCurrency(item.remaining);
                    remainingCell.dataset.value = item.remaining;
                }
                if (percentageCell) percentageCell.textContent = `${item.percentage}%`;
                if (progressBar) {
                    progressBar.style.width = `${item.percentage}%`;
                    const progressColor = item.percentage >= 80 ? '#3f6516' : (item.percentage >= 50 ? '#ecd224' : '#990dd1');
                    progressBar.style.backgroundColor = progressColor;
                }
            }
        }

        function updateBudgetValue(id, newBudget) {
            const item = budgetData.find(i => i.id === id);
            if (item) {
                let budgetValue = parseFloat(newBudget);
                if (isNaN(budgetValue)) budgetValue = 0;
                if (budgetValue < 0) budgetValue = 0;

                if (item.utilized > budgetValue) item.utilized = budgetValue;
                item.budget = budgetValue;
                updateItemAndRow(item);
                calculateAndUpdateTotals();
                showNotification(`Updated budget for ${item.twg}`, 'success');
            }
        }

        function updateUtilizedValue(id, newUtilized) {
            const item = budgetData.find(i => i.id === id);
            if (item) {
                let utilizedValue = parseFloat(newUtilized);
                if (isNaN(utilizedValue)) utilizedValue = 0;
                if (utilizedValue > item.budget) utilizedValue = item.budget;
                if (utilizedValue < 0) utilizedValue = 0;

                item.utilized = utilizedValue;
                updateItemAndRow(item);
                calculateAndUpdateTotals();
                showNotification(`Updated utilized amount for ${item.twg}`, 'success');
            }
        }

        function updateRemainingValue(id, newRemaining) {
            const item = budgetData.find(i => i.id === id);
            if (item) {
                let remainingValue = parseFloat(newRemaining);
                if (isNaN(remainingValue)) remainingValue = 0;
                if (remainingValue < 0) remainingValue = 0;

                let newUtilized = item.budget - remainingValue;
                if (newUtilized < 0) newUtilized = 0;
                if (newUtilized > item.budget) newUtilized = item.budget;

                item.utilized = newUtilized;
                updateItemAndRow(item);
                calculateAndUpdateTotals();
                showNotification(`Updated remaining amount for ${item.twg}`, 'success');
            }
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-20 right-8 z-50 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium ${type === 'success' ? 'bg-3f6516' : 'bg-red-500'} transition-all transform translate-x-0`;
            notification.innerHTML = `<div class="flex items-center gap-2"><span class="text-sm">${type === 'success' ? '✅' : '❌'}</span>${message}</div>`;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 2500);
        }

        function renderTable() {
            const tbody = document.getElementById('budgetTableBody');
            let html = '';

            budgetData.forEach((item, index) => {
                const percentageClass = item.percentage >= 80 ? 'text-3f6516' : (item.percentage >= 50 ? 'text-ecd224' : 'text-990dd1');
                const progressColor = item.percentage >= 80 ? '#3f6516' : (item.percentage >= 50 ? '#ecd224' : '#990dd1');

                html += `
                    <tr data-id="${item.id}" class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-3b3b3b">${index + 1}</td>
                        <td class="px-6 py-4"><div class="font-medium text-000 text-sm">${escapeHtml(item.twg)}</div></td>
                        <td class="px-6 py-4"><div class="editable-cell budget-display budget-cell" data-id="${item.id}" data-field="budget" data-value="${item.budget}" data-twg="${escapeHtml(item.twg)}">${formatCurrency(item.budget)}</div></td>
                        <td class="px-6 py-4"><div class="editable-cell utilized-display utilized-cell" data-id="${item.id}" data-field="utilized" data-value="${item.utilized}" data-twg="${escapeHtml(item.twg)}">${formatCurrency(item.utilized)}</div></td>
                        <td class="px-6 py-4"><div class="editable-cell remaining-display remaining-cell" data-id="${item.id}" data-field="remaining" data-value="${item.remaining}" data-twg="${escapeHtml(item.twg)}">${formatCurrency(item.remaining)}</div></td>
                        <td class="px-6 py-4"><div class="flex items-center gap-2"><span class="percentage-display text-sm font-bold ${percentageClass}">${item.percentage}%</span><div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden"><div class="progress-fill h-full rounded-full" style="width: ${item.percentage}%; background-color: ${progressColor}"></div></div></div></td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
            calculateAndUpdateTotals();

            document.querySelectorAll('.editable-cell').forEach(cell => {
                cell.addEventListener('dblclick', function(e) {
                    e.stopPropagation();
                    startEditing(this);
                });
            });
        }

        function getFieldDisplayName(field) {
            const names = {
                'budget': 'Budget',
                'utilized': 'Utilized Amount',
                'remaining': 'Remaining Amount'
            };
            return names[field] || field;
        }

        function startEditing(cell) {
            if (currentEditingCell) {
                cancelEditing();
            }

            const id = parseInt(cell.dataset.id);
            const field = cell.dataset.field;
            const currentValue = parseFloat(cell.dataset.value);
            const twgName = cell.dataset.twg;

            currentEditingCell = cell;
            currentEditingId = id;
            currentEditingField = field;

            cell.classList.add('editing');
            cell.innerHTML = '';
            const input = document.createElement('input');
            input.type = 'number';
            input.value = currentValue;
            input.step = "1000";
            input.min = "0";
            input.className = 'w-full p-2 border-0 outline-none bg-transparent text-sm font-medium';

            cell.appendChild(input);
            input.focus();
            input.select();

            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    finishEditing(cell, input.value, id, field, twgName);
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    e.preventDefault();
                    cancelEditing();
                }
            });

            input.addEventListener('blur', function() {
                setTimeout(() => {
                    if (currentEditingCell === cell) {
                        finishEditing(cell, input.value, id, field, twgName);
                    }
                }, 200);
            });
        }

        function finishEditing(cell, newValue, id, field, twgName) {
            if (!currentEditingCell) return;

            const item = budgetData.find(i => i.id === id);
            if (!item) {
                cancelEditing();
                return;
            }

            let newNumericValue = parseFloat(newValue);
            if (isNaN(newNumericValue)) newNumericValue = 0;
            if (newNumericValue < 0) newNumericValue = 0;

            let currentValue = item[field];
            if (field === 'remaining') {
                currentValue = item.remaining;
            }

            // ONLY SHOW MODAL IF VALUE ACTUALLY CHANGED
            if (newNumericValue !== currentValue) {
                pendingValue = newNumericValue;

                document.getElementById('modalFieldName').innerHTML = getFieldDisplayName(field);
                document.getElementById('modalTWGName').innerHTML = twgName;
                document.getElementById('modalOldValue').innerHTML = formatCurrency(currentValue);
                document.getElementById('modalNewValue').innerHTML = formatCurrency(newNumericValue);

                window.pendingUpdateId = id;
                window.pendingUpdateValue = newNumericValue;
                window.pendingUpdateField = field;
                window.pendingUpdateCell = cell;

                // Show modal - change class from modal-hidden to modal-visible
                const modal = document.getElementById('confirmationModal');
                modal.classList.remove('modal-hidden');
                modal.classList.add('modal-visible');
            } else {
                cancelEditing();
            }
        }

        function confirmUpdate() {
            if (window.pendingUpdateId !== undefined && window.pendingUpdateValue !== undefined && window.pendingUpdateField !== undefined) {
                const field = window.pendingUpdateField;
                const value = window.pendingUpdateValue;
                const id = window.pendingUpdateId;

                if (field === 'budget') {
                    updateBudgetValue(id, value);
                } else if (field === 'utilized') {
                    updateUtilizedValue(id, value);
                } else if (field === 'remaining') {
                    updateRemainingValue(id, value);
                }

                const item = budgetData.find(i => i.id === id);
                if (window.pendingUpdateCell && item) {
                    if (field === 'budget') {
                        window.pendingUpdateCell.dataset.value = item.budget;
                        window.pendingUpdateCell.innerHTML = formatCurrency(item.budget);
                    } else if (field === 'utilized') {
                        window.pendingUpdateCell.dataset.value = item.utilized;
                        window.pendingUpdateCell.innerHTML = formatCurrency(item.utilized);
                    } else if (field === 'remaining') {
                        window.pendingUpdateCell.dataset.value = item.remaining;
                        window.pendingUpdateCell.innerHTML = formatCurrency(item.remaining);
                    }
                    window.pendingUpdateCell.classList.remove('editing');
                }
            }
            closeModalAndCancel();
        }

        function cancelEditing() {
            if (currentEditingCell) {
                const item = budgetData.find(i => i.id === currentEditingId);
                const field = currentEditingField;
                if (item) {
                    let displayValue = '';
                    if (field === 'budget') displayValue = formatCurrency(item.budget);
                    else if (field === 'utilized') displayValue = formatCurrency(item.utilized);
                    else if (field === 'remaining') displayValue = formatCurrency(item.remaining);
                    else displayValue = formatCurrency(0);

                    currentEditingCell.innerHTML = displayValue;
                    currentEditingCell.classList.remove('editing');
                } else {
                    currentEditingCell.innerHTML = formatCurrency(0);
                    currentEditingCell.classList.remove('editing');
                }
                currentEditingCell = null;
                currentEditingId = null;
                currentEditingField = null;
            }
        }

        function closeModalAndCancel() {
            const modal = document.getElementById('confirmationModal');
            modal.classList.remove('modal-visible');
            modal.classList.add('modal-hidden');

            window.pendingUpdateId = undefined;
            window.pendingUpdateValue = undefined;
            window.pendingUpdateField = undefined;
            window.pendingUpdateCell = undefined;
            cancelEditing();
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                return m === '&' ? '&amp;' : (m === '<' ? '&lt;' : '&gt;');
            });
        }

        // Event Listeners
        document.getElementById('confirmUpdateBtn').addEventListener('click', confirmUpdate);
        document.getElementById('cancelUpdateBtn').addEventListener('click', closeModalAndCancel);

        document.getElementById('confirmationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModalAndCancel();
            }
        });

        renderTable();
    </script>
</body>

</html>