<?php
// staff/assign_mandates.php - Assign Mandates to Accomplishment Report
require_once '../auth.php';
require_role(['gad_staff', 'admin']);
$page_title = 'Assign Mandates';
include 'sidebar.php';
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Assign Mandates | GAD-IMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        main {
            margin-left: 30px;
        }

        body {
            background: #f8f9fb;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .material-symbols-outlined {
            display: inline-block;
            font-size: 34px;
            font-family: monospace;
        }

        .checkbox-custom {
            transition: all 0.2s ease;
        }

        .mandate-item:hover {
            background-color: #b979cc10;
        }

        .other-input {
            transition: all 0.2s ease;
        }

        .other-input:focus {
            outline: none;
            border-color: #990dd1;
        }

        /* Two-column layout - Reports on LEFT, Mandates on RIGHT */
        .two-column-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

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
            gap: 10px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .text-4xl {
            font-size: 46px;
        }

        .text-990dd1 {
            color: #990dd1;
        }

        .text-2xl {
            font-size: 30px;
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

        .text-11px {
            font-size: 13px;
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

        .overflow-hidden {
            overflow: hidden;
        }

        .p-5 {
            padding: 20px;
        }

        .border-b {
            border-bottom: 1px solid #e2e8f0;
        }

        .bg-slate-50 {
            background-color: #f8fafc;
        }

        .justify-between {
            justify-content: space-between;
        }

        .gap-2 {
            gap: 8px;
        }

        .text-10px {
            font-size: 13px;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .divide-y>*+* {
            border-top-width: 1px;
            border-color: #e2e8f0;
        }

        .max-h-500 {
            max-height: 500px;
        }

        .overflow-y-auto {
            overflow-y: auto;
        }

        .px-6 {
            padding-left: 24px;
            padding-right: 24px;
        }

        .py-10 {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .text-center {
            text-align: center;
        }

        .p-4 {
            padding: 16px;
        }

        .border-t {
            border-top: 1px solid #e2e8f0;
        }

        .bg-slate-50\/50 {
            background-color: rgba(248, 250, 252, 0.5);
        }

        .w-full {
            width: 100%;
        }

        .py-3 {
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .bg-990dd1 {
            background-color: #990dd1;
        }

        .text-white {
            color: white;
        }

        .rounded-xl {
            border-radius: 12px;
        }

        .transition {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .justify-center {
            justify-content: center;
        }

        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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

        .pointer-events-none {
            pointer-events: none;
        }

        .z-40 {
            z-index: 40;
        }

        .text-9px {
            font-size: 13px;
        }

        .opacity-20 {
            opacity: 0.2;
        }

        .font-medium {
            font-weight: 500;
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

        .hidden {
            display: none;
        }

        .rounded-2xl {
            border-radius: 16px;
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

        .text-xl {
            font-size: 25px;
        }

        .mb-6 {
            margin-bottom: 24px;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .bg-b979cc10 {
            background-color: rgba(185, 121, 204, 0.1);
        }

        .rounded-lg {
            border-radius: 8px;
        }

        .space-y-1>*+* {
            margin-top: 4px;
        }

        .max-h-48 {
            max-height: 192px;
        }

        .bg-slate-50 {
            background-color: #f8fafc;
        }

        .flex-1 {
            flex: 1;
        }

        .border-slate-300 {
            border-color: #cbd5e1;
        }

        .hover\:bg-slate-50:hover {
            background-color: #f8fafc;
        }

        /* Header styles */
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
            font-size: 30px;
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
            <h2 class="header-title">Assign Mandates</h2>
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

    <main style="padding-left: 224px; padding-top: 64px; min-height: 100vh;">
        <div style="padding: 32px;">
            
            <!-- TWO-COLUMN LAYOUT: Reports on LEFT, Mandates on RIGHT -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
                
                <!-- LEFT COLUMN: Accomplishment Reports -->
                <div style="background-color: white; border-radius: 12px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0; overflow: hidden;">
                    <div style="padding: 20px; border-bottom: 1px solid #e2e8f0; background-color: #f8fafc;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h2 style="font-weight: 700; color: #000; display: flex; align-items: center; gap: 8px; font-size: 15px;">
                                <span style="font-size: 24px; color: #990dd1;">📊</span> Accomplishment Reports
                            </h2>
                            <div style="position: relative;">
                                <select id="reportFilter" style="font-size: 13px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 12px; background-color: white;">
                                    <option value="all">All Reports</option>
                                    <option value="unassigned">Unassigned Only</option>
                                    <option value="assigned">Assigned Only</option>
                                </select>
                            </div>
                        </div>
                        <p style="font-size: 13px; color: #3b3b3b; margin-top: 4px;">Select an accomplishment report to assign mandates</p>
                    </div>
                    <div style="max-height: 500px; overflow-y: auto;">
                        <div id="reportsList" style="display: flex; flex-direction: column;">
                            <div style="padding: 24px 40px; text-align: center; color: #3b3b3b; font-size: 13px;">Loading accomplishment reports...</div>
                        </div>
                    </div>
                    <div style="padding: 16px; border-top: 1px solid #e2e8f0; background-color: rgba(248,250,252,0.5); display: flex; justify-content: space-between; align-items: center;">
                        <p id="reportsCount" style="font-size: 13px; color: #3b3b3b;">0 reports found</p>
                        <div style="display: flex; gap: 4px;" id="reportsPagination"></div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: GAD Mandates -->
                <div style="background-color: white; border-radius: 12px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0; overflow: hidden;">
                    <div style="padding: 20px; border-bottom: 1px solid #e2e8f0; background-color: #f8fafc;">
                        <h2 style="font-weight: 700; color: #000; display: flex; align-items: center; gap: 8px; font-size: 13px;">
                            <span style="font-size: 30px; color: #990dd1;">🏛️</span> GAD Mandates
                        </h2>
                        <p style="font-size: 13px; color: #3b3b3b; margin-top: 4px;" id="selectedReportInfo">Select an accomplishment report from the left panel</p>
                    </div>
                    <div style="padding: 20px; max-height: 500px; overflow-y: auto;" id="mandatesContainer">
                        <div style="text-align: center; color: #3b3b3b; padding: 40px 0;">
                            <span style="font-size: 36px; margin-bottom: 8px; display: block;">ℹ️</span>
                            <p style="font-size: 13px;">Select a report to view available mandates</p>
                        </div>
                    </div>
                    <div style="padding: 20px; border-top: 1px solid #e2e8f0; background-color: rgba(248,250,252,0.5);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                    <input type="checkbox" id="selectAllMandates" style="border-radius: 4px; width: 16px; height: 16px;">
                                    <span style="font-size: 13px; font-weight: 500; color: #000;">Select All Mandates</span>
                                </label>
                            </div>
                            <div id="selectedMandatesCount" style="font-size: 13px; color: #990dd1; font-weight: 500;">0 mandates selected</div>
                        </div>
                        <button id="setMandatesBtn" style="width: 100%; background-color: #990dd1; color: white; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 13px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                            <span style="font-size: 13px;">✅</span> Set Mandates
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div style="position: fixed; bottom: 16px; left: 224px; right: 0; text-align: center; pointer-events: none; z-index: 40;">
        <p style="font-size: 13px; color: rgba(59,59,59,0.2); font-weight: 500;">Benguet State University - Gender and Development Information Management System | GAD-IMS v1.0</p>
    </div>

    <div id="confirmationModal" style="position: fixed; top: 0; right: 0; bottom: 0; left: 0; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 200; display: none;">
        <div style="background-color: white; border-radius: 16px; padding: 32px; max-width: 448px; width: 90%; margin: 0 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 20px; font-weight: 700; color: #000;">Confirm Mandate Assignment</h3>
                <button onclick="closeModal()" style="background: none; border: none; font-size: 20px; color: #3b3b3b; cursor: pointer;">✕</button>
            </div>
            <div style="margin-bottom: 24px;">
                <p style="font-size: 13px; color: #3b3b3b; margin-bottom: 12px;">You are about to assign the following mandate(s) to:</p>
                <p style="font-weight: 700; color: #990dd1; background-color: rgba(185,121,204,0.1); padding: 12px; border-radius: 8px;" id="modalReportTitle">-</p>
                <div style="margin-top: 16px;">
                    <p style="font-size: 13px; font-weight: 500; color: #000; margin-bottom: 8px;">Selected Mandates:</p>
                    <div id="modalMandatesList" style="display: flex; flex-direction: column; gap: 4px; max-height: 192px; overflow-y: auto; background-color: #f8fafc; padding: 12px; border-radius: 8px;"></div>
                </div>
            </div>
            <div style="display: flex; gap: 12px;">
                <button onclick="confirmAssignment()" style="flex: 1; background-color: #990dd1; color: white; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 13px; border: none; cursor: pointer;">Confirm</button>
                <button onclick="closeModal()" style="flex: 1; border: 1px solid #cbd5e1; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 13px; background: none; cursor: pointer;">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        let accomplishmentReports = [{
            id: 1,
            control: "GAD-2026-001",
            title: "Lorem ipsum dolor sit amet consectetur",
            office: "GAD Office",
            date: "Jan 15, 2026",
            assignedMandates: [],
            otherMandate: null
        }, {
            id: 2,
            control: "GAD-2026-002",
            title: "Sed do eiusmod tempor incididunt",
            office: "GAD Office",
            date: "Feb 10, 2026",
            assignedMandates: [],
            otherMandate: null
        }, {
            id: 3,
            control: "GAD-2026-003",
            title: "Ut labore et dolore magna aliqua",
            office: "GAD Office",
            date: "Mar 5, 2026",
            assignedMandates: [],
            otherMandate: null
        }, {
            id: 4,
            control: "EMP-2026-001",
            title: "Quis nostrud exercitation ullamco",
            office: "HR Management Office",
            date: "Jan 20, 2026",
            assignedMandates: [],
            otherMandate: null
        }, {
            id: 5,
            control: "EMP-2026-002",
            title: "Duis aute irure dolor in reprehenderit",
            office: "College of Business",
            date: "Feb 15, 2026",
            assignedMandates: [],
            otherMandate: null
        }, {
            id: 6,
            control: "INSET-2026-001",
            title: "Fugiat nulla pariatur excepteur",
            office: "College of Education",
            date: "Jan 25, 2026",
            assignedMandates: [],
            otherMandate: null
        }, {
            id: 7,
            control: "EXT-2026-001",
            title: "Lorem ipsum dolor sit amet adipiscing",
            office: "Extension Office",
            date: "Jan 10, 2026",
            assignedMandates: [],
            otherMandate: null
        }, {
            id: 8,
            control: "EMP-2026-003",
            title: "Excepteur sint occaecat cupidatat",
            office: "College of Nursing",
            date: "Mar 10, 2026",
            assignedMandates: [],
            otherMandate: null
        }];
        
        const gadMandates = [{
            id: 1,
            title: "CHED Memorandum Order No. 01 series 2015 - GAD Orientation for Students"
        }, {
            id: 2,
            title: "CHED Memorandum Order No. 01 series 2015 - GAD Leadership Training for Student Leaders"
        }, {
            id: 3,
            title: "GAD Mainstreaming Capability Building - Gender Mainstreaming training"
        }, {
            id: 4,
            title: "Magna Carta of Women (RA 9710) - Monitoring and Evaluation of GAD PAPs"
        }, {
            id: 5,
            title: "GFPS Capacity Building and TOT on GAD Mandates"
        }, {
            id: 6,
            title: "GAD-GFPS Regular Coordination and Meetings"
        }, {
            id: 7,
            title: "GAD Focal Point System - Support Staff for GFPS Implementation"
        }, {
            id: 8,
            title: "Gender Sensitivity Training for Newly Hired Personnel"
        }, {
            id: 9,
            title: "Development and Dissemination of GAD IEC Materials"
        }, {
            id: 10,
            title: "18-Day Campaign to End VAW and Women's Month Celebration"
        }, {
            id: 11,
            title: "Maintenance of Child Minding Center for Working Parents"
        }, {
            id: 12,
            title: "Gender Sensitivity Orientations for BSU Personnel"
        }, {
            id: 13,
            title: "Gender-Responsive Curricular Programs - Syllabi Integration"
        }, {
            id: 14,
            title: "Sustaining Gender Mainstreaming and Institutional Support"
        }, {
            id: 15,
            title: "GREP Extension Program - Extension Activities to Partner Communities"
        }, {
            id: 16,
            title: "Crisis Intervention - Distribution of Hygiene Kits"
        }, {
            id: 17,
            title: "BSU-PRAISE GAD Recognition Awards"
        }, {
            id: 18,
            title: "Reproductive Health Care Center Operations"
        }, {
            id: 19,
            title: "GAD Database and Sex-Disaggregated Database System"
        }, {
            id: 20,
            title: "Breastfeeding Station Maintenance and Establishment"
        }];
        
        let currentReportPage = 1;
        let reportsPerPage = 5;
        let currentFilter = 'all';
        let selectedReportId = null;
        let selectedMandates = new Set();
        let otherMandateText = '';

        function getFilteredReports() {
            let filtered = [...accomplishmentReports];
            if (currentFilter === 'unassigned') filtered = filtered.filter(r => r.assignedMandates.length === 0 && !r.otherMandate);
            else if (currentFilter === 'assigned') filtered = filtered.filter(r => r.assignedMandates.length > 0 || r.otherMandate);
            return filtered;
        }

        function renderReportsList() {
            const filtered = getFilteredReports();
            const totalItems = filtered.length;
            const totalPages = Math.ceil(totalItems / reportsPerPage);
            const startIndex = (currentReportPage - 1) * reportsPerPage;
            const endIndex = startIndex + reportsPerPage;
            const paginatedItems = filtered.slice(startIndex, endIndex);
            const container = document.getElementById('reportsList');
            const reportsCount = document.getElementById('reportsCount');
            reportsCount.textContent = `${totalItems} report(s) found`;
            if (paginatedItems.length === 0) {
                container.innerHTML = '<div style="padding: 40px 24px; text-align: center; color: #3b3b3b; font-size: 13px;">No accomplishment reports found</div>';
                updateReportsPagination(totalPages);
                return;
            }
            let html = '';
            paginatedItems.forEach(report => {
                const isSelected = selectedReportId === report.id;
                const assignedCount = report.assignedMandates.length;
                const hasOther = report.otherMandate ? true : false;
                const statusBadge = (assignedCount > 0 || hasOther) ? '<span style="font-size: 13px; background-color: rgba(63,101,22,0.2); color: #3f6516; padding: 2px 8px; border-radius: 9999px;">Assigned</span>' : '<span style="font-size: 13px; background-color: rgba(236,210,36,0.2); color: #ecd224; padding: 2px 8px; border-radius: 9999px;">Unassigned</span>';
                html += `<div style="padding: 16px; cursor: pointer; ${isSelected ? 'background-color: rgba(185,121,204,0.2); border-left: 4px solid #990dd1;' : ''}" onclick="selectReport(${report.id})">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                        <span style="font-family: monospace; font-size: 13px; font-weight: 500; color: #990dd1;">${report.control}</span>${statusBadge}
                                    </div>
                                    <p style="font-weight: 500; color: #000; font-size: 13px;">${escapeHtml(report.title)}</p>
                                    <div style="display: flex; align-items: center; gap: 12px; margin-top: 4px;">
                                        <span style="font-size: 13px; color: #3b3b3b;">${escapeHtml(report.office)}</span>
                                        <span style="font-size: 13px; color: #3b3b3b;">•</span>
                                        <span style="font-size: 13px; color: #3b3b3b;">${report.date}</span>
                                    </div>
                                    ${assignedCount > 0 ? `<div style="font-size: 13px; color: #990dd1; margin-top: 4px;">${assignedCount} mandate(s) assigned</div>` : ''}
                                    ${hasOther ? `<div style="font-size: 13px; color: #990dd1; margin-top: 4px;">+ Other: ${escapeHtml(report.otherMandate)}</div>` : ''}
                                </div>
                                <span style="color: #3b3b3b; font-size: 13px;">→</span>
                            </div>
                        </div>`;
            });
            container.innerHTML = html;
            updateReportsPagination(totalPages);
        }

        function updateReportsPagination(totalPages) {
            const controls = document.getElementById('reportsPagination');
            if (totalPages <= 1) {
                controls.innerHTML = '';
                return;
            }
            let buttonsHtml = `<button class="prev-report-btn" style="padding: 4px 8px; font-size: 13px; border: 1px solid #e2e8f0; border-radius: 8px; background: none; cursor: pointer;" ${currentReportPage === 1 ? 'disabled' : ''}>Prev</button>`;
            for (let i = 1; i <= Math.min(totalPages, 3); i++) buttonsHtml += `<button class="report-page-btn" style="padding: 4px 8px; font-size: 13px; border: 1px solid #e2e8f0; border-radius: 8px; ${currentReportPage === i ? 'background-color: #990dd1; color: white;' : 'background: none;'}" data-page="${i}">${i}</button>`;
            if (totalPages > 3) {
                buttonsHtml += `<span style="padding: 0 4px; font-size: 13px;">...</span>`;
                buttonsHtml += `<button class="report-page-btn" style="padding: 4px 8px; font-size: 13px; border: 1px solid #e2e8f0; border-radius: 8px; background: none;" data-page="${totalPages}">${totalPages}</button>`;
            }
            buttonsHtml += `<button class="next-report-btn" style="padding: 4px 8px; font-size: 13px; border: 1px solid #e2e8f0; border-radius: 8px; background: none; cursor: pointer;" ${currentReportPage === totalPages ? 'disabled' : ''}>Next</button>`;
            controls.innerHTML = buttonsHtml;
            
            document.querySelectorAll('.report-page-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    currentReportPage = parseInt(e.target.dataset.page);
                    renderReportsList();
                });
            });
            document.querySelector('.prev-report-btn')?.addEventListener('click', () => {
                if (currentReportPage > 1) {
                    currentReportPage--;
                    renderReportsList();
                }
            });
            document.querySelector('.next-report-btn')?.addEventListener('click', () => {
                if (currentReportPage < totalPages) {
                    currentReportPage++;
                    renderReportsList();
                }
            });
        }

        function selectReport(reportId) {
            selectedReportId = reportId;
            selectedMandates.clear();
            otherMandateText = '';
            const report = accomplishmentReports.find(r => r.id === reportId);
            if (report && report.otherMandate) otherMandateText = report.otherMandate;
            renderReportsList();
            renderMandatesList();
            updateSelectAllCheckbox();
            updateSelectedCount();
            const reportInfo = document.getElementById('selectedReportInfo');
            if (report) reportInfo.innerHTML = `<span style="font-weight: 500;">Selected:</span> ${escapeHtml(report.control)} - ${escapeHtml(report.title)}`;
        }

        function renderMandatesList() {
            const container = document.getElementById('mandatesContainer');
            if (!selectedReportId) {
                container.innerHTML = `<div style="text-align: center; color: #3b3b3b; padding: 40px 0;">
                                            <span style="font-size: 36px; margin-bottom: 8px; display: block;">ℹ️</span>
                                            <p style="font-size: 13px;">Select a report to view available mandates</p>
                                        </div>`;
                return;
            }
            let html = '<div style="display: flex; flex-direction: column; gap: 8px;">';
            gadMandates.forEach(mandate => {
                html += `<label style="display: flex; align-items: flex-start; gap: 12px; padding: 12px; border-radius: 8px; cursor: pointer;">
                            <input type="checkbox" class="mandate-checkbox" style="border-radius: 4px; width: 16px; height: 16px; margin-top: 2px;" data-id="${mandate.id}">
                            <span style="font-size: 13px; color: #3b3b3b; flex: 1;">${escapeHtml(mandate.title)}</span>
                        </label>`;
            });
            const isOtherChecked = selectedMandates.has('other');
            html += `<div style="border-top: 1px solid #e2e8f0; padding-top: 12px; margin-top: 8px;">
                        <label style="display: flex; align-items: flex-start; gap: 12px; padding: 12px; border-radius: 8px; cursor: pointer;">
                            <input type="checkbox" id="otherCheckbox" style="border-radius: 4px; width: 16px; height: 16px; margin-top: 2px;" ${isOtherChecked ? 'checked' : ''}>
                            <span style="font-size: 13px; color: #3b3b3b; flex: 1; font-weight: 500;">Other (Please specify)</span>
                        </label>
                        <div class="ml-9 mt-2 ${isOtherChecked ? '' : 'hidden'}" id="otherTextContainer">
                            <textarea id="otherMandateText" rows="2" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; font-family: inherit;" placeholder="Enter other mandate or special instruction...">${escapeHtml(otherMandateText)}</textarea>
                        </div>
                    </div>`;
            html += '</div>';
            container.innerHTML = html;
            
            document.querySelectorAll('.mandate-checkbox').forEach(cb => {
                cb.addEventListener('change', (e) => {
                    const mandateId = parseInt(e.target.dataset.id);
                    if (e.target.checked) selectedMandates.add(mandateId);
                    else selectedMandates.delete(mandateId);
                    updateSelectAllCheckbox();
                    updateSelectedCount();
                });
            });
            
            const otherCheckbox = document.getElementById('otherCheckbox');
            const otherTextarea = document.getElementById('otherMandateText');
            const otherTextContainer = document.getElementById('otherTextContainer');
            if (otherCheckbox) otherCheckbox.addEventListener('change', (e) => {
                if (e.target.checked) {
                    otherTextContainer.classList.remove('hidden');
                    selectedMandates.add('other');
                    otherTextarea.focus();
                } else {
                    otherTextContainer.classList.add('hidden');
                    otherTextarea.value = '';
                    selectedMandates.delete('other');
                    otherMandateText = '';
                }
                updateSelectAllCheckbox();
                updateSelectedCount();
            });
            if (otherTextarea) otherTextarea.addEventListener('input', (e) => {
                otherMandateText = e.target.value;
            });
        }

        function updateSelectAllCheckbox() {
            const selectAllCheckbox = document.getElementById('selectAllMandates');
            if (!selectAllCheckbox) return;
            const regularMandatesCount = gadMandates.length;
            let selectedRegularCount = 0;
            selectedMandates.forEach(id => {
                if (id !== 'other') selectedRegularCount++;
            });
            if (selectedRegularCount === regularMandatesCount) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (selectedRegularCount > 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            }
        }

        function updateSelectedCount() {
            const countSpan = document.getElementById('selectedMandatesCount');
            if (countSpan) {
                let regularCount = 0;
                selectedMandates.forEach(id => {
                    if (id !== 'other') regularCount++;
                });
                const hasOther = selectedMandates.has('other');
                countSpan.textContent = `${regularCount} mandate(s) selected${hasOther ? ' + Other' : ''}`;
            }
        }
        
        document.getElementById('selectAllMandates')?.addEventListener('change', (e) => {
            if (e.target.checked) gadMandates.forEach(m => selectedMandates.add(m.id));
            else gadMandates.forEach(m => selectedMandates.delete(m.id));
            renderMandatesList();
            updateSelectAllCheckbox();
            updateSelectedCount();
        });
        
        document.getElementById('reportFilter')?.addEventListener('change', (e) => {
            currentFilter = e.target.value;
            currentReportPage = 1;
            selectedReportId = null;
            selectedMandates.clear();
            otherMandateText = '';
            renderReportsList();
            document.getElementById('mandatesContainer').innerHTML = `<div style="text-align: center; color: #3b3b3b; padding: 40px 0;">
                                                                        <span style="font-size: 36px; margin-bottom: 8px; display: block;">ℹ️</span>
                                                                        <p style="font-size: 13px;">Select a report to view available mandates</p>
                                                                    </div>`;
            document.getElementById('selectedReportInfo').innerHTML = 'Select an accomplishment report from the left panel';
            updateSelectedCount();
        });
        
        document.getElementById('setMandatesBtn')?.addEventListener('click', () => {
            if (!selectedReportId) {
                alert('Please select an accomplishment report first.');
                return;
            }
            const regularCount = Array.from(selectedMandates).filter(id => id !== 'other').length;
            if (regularCount === 0 && !selectedMandates.has('other')) {
                alert('Please select at least one mandate or specify an "Other" mandate.');
                return;
            }
            if (selectedMandates.has('other') && (!otherMandateText || otherMandateText.trim() === '')) {
                alert('Please specify the "Other" mandate or uncheck the Other option.');
                return;
            }
            const report = accomplishmentReports.find(r => r.id === selectedReportId);
            const modalReportTitle = document.getElementById('modalReportTitle');
            const modalMandatesList = document.getElementById('modalMandatesList');
            modalReportTitle.textContent = `${report.control} - ${report.title}`;
            let mandatesHtml = '';
            const selectedMandatesList = gadMandates.filter(m => selectedMandates.has(m.id));
            selectedMandatesList.forEach(m => {
                mandatesHtml += `<div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #3b3b3b; padding: 4px 0;">
                                    <span style="font-size: 14px; color: #990dd1;">✅</span> ${escapeHtml(m.title)}
                                </div>`;
            });
            if (selectedMandates.has('other') && otherMandateText.trim() !== '') {
                mandatesHtml += `<div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #3b3b3b; padding: 4px 0;">
                                    <span style="font-size: 14px; color: #990dd1;">✅</span> <strong>Other:</strong> ${escapeHtml(otherMandateText)}
                                </div>`;
            }
            modalMandatesList.innerHTML = mandatesHtml;
            document.getElementById('confirmationModal').style.display = 'flex';
        });

        function confirmAssignment() {
            const reportIndex = accomplishmentReports.findIndex(r => r.id === selectedReportId);
            if (reportIndex !== -1) {
                const regularMandates = Array.from(selectedMandates).filter(id => id !== 'other');
                accomplishmentReports[reportIndex].assignedMandates = regularMandates;
                if (selectedMandates.has('other') && otherMandateText.trim() !== '') accomplishmentReports[reportIndex].otherMandate = otherMandateText.trim();
                else accomplishmentReports[reportIndex].otherMandate = null;
            }
            const regularCount = Array.from(selectedMandates).filter(id => id !== 'other').length;
            const hasOther = selectedMandates.has('other') && otherMandateText.trim() !== '';
            alert(`Successfully assigned ${regularCount} mandate(s)${hasOther ? ' + Other mandate' : ''} to the accomplishment report.`);
            closeModal();
            renderReportsList();
            renderMandatesList();
        }

        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                return m === '&' ? '&amp;' : (m === '<' ? '&lt;' : '&gt;');
            });
        }
        
        renderReportsList();
    </script>
</body>

</html>