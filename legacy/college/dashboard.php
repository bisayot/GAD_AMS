<?php
// twg/dashboard.php - Office of Student Services TWG Dashboard with Calendar & Deadlines Sidebar
require_once '../auth.php';
require_once 'includes/config.php';
require_role(['college', 'gad_staff']);
$page_title = 'OSS Dashboard';

// Calculate days remaining function
function getDaysRemaining($dateStr) {
    $dueDate = new DateTime($dateStr);
    $today = new DateTime();
    $interval = $today->diff($dueDate);
    $days = (int)$interval->format('%r%a');
    return $days;
}

function getDueText($daysRemaining) {
    if ($daysRemaining < 0) return 'Overdue';
    if ($daysRemaining == 0) return 'Due today';
    if ($daysRemaining == 1) return 'Due in 1 day';
    return 'Due in ' . $daysRemaining . ' days';
}

// Deadline data
$deadlines = [
    ['id' => 1, 'title' => 'Lorem ipsum dolor sit amet', 'control' => 'ACC-OSS-2025-003', 'due_date' => '2025-04-20', 'type' => 'revision'],
    ['id' => 2, 'title' => 'Lorem ipsum dolor sit amet', 'control' => 'ACC-OSS-2025-004', 'due_date' => '2025-04-22', 'type' => 'revision'],
    ['id' => 3, 'title' => 'Lorem ipsum dolor sit amet', 'control' => 'ACC-OSS-2025-005', 'due_date' => '2025-04-25', 'type' => 'submission'],
];

// Sort deadlines by due date
usort($deadlines, function($a, $b) {
    return strtotime($a['due_date']) - strtotime($b['due_date']);
});

// Get current month calendar data
$today = new DateTime();
$currentMonth = $today->format('F');
$currentYear = $today->format('Y');
$firstDayOfMonth = new DateTime("first day of $currentMonth $currentYear");
$lastDayOfMonth = new DateTime("last day of $currentMonth $currentYear");
$startOfCalendar = clone $firstDayOfMonth;
$startOfCalendar->modify('-' . ($firstDayOfMonth->format('w')) . ' days');
$endOfCalendar = clone $lastDayOfMonth;
$endOfCalendar->modify('+' . (6 - $lastDayOfMonth->format('w')) . ' days');

$calendarWeeks = [];
$week = [];
$currentDay = clone $startOfCalendar;
while ($currentDay <= $endOfCalendar) {
    $dayStr = $currentDay->format('Y-m-d');
    $hasDeadline = false;
    foreach ($deadlines as $deadline) {
        if ($deadline['due_date'] === $dayStr) {
            $hasDeadline = true;
            break;
        }
    }
    $week[] = [
        'dayNum' => $currentDay->format('j'),
        'isCurrentMonth' => ($currentDay->format('n') == $today->format('n')),
        'isToday' => ($currentDay->format('Y-m-d') == $today->format('Y-m-d')),
        'hasDeadline' => $hasDeadline
    ];
    if (count($week) == 7) {
        $calendarWeeks[] = $week;
        $week = [];
    }
    $currentDay->modify('+1 day');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>OSS Dashboard | GAD-IMS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f5f7fc 0%, #eef2f8 100%);
            margin-top: 80px;
        }
        
        @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap');
        
        /* Layout */
        .ml-64 { margin-left: 256px; }
        .min-h-screen { min-height: 100vh; }
        .p-8 { padding: 2rem; }
        .mb-8 { margin-bottom: 2rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-4 { margin-top: 1rem; }
        
        /* Grid */
        .grid { display: grid; }
        .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-cols-7 { grid-template-columns: repeat(7, 1fr); }
        .grid-cols-12 { grid-template-columns: repeat(12, 1fr); }
        .gap-4 { gap: 1rem; }
        .gap-6 { gap: 1.5rem; }
        
        /* Flexbox */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .items-start { align-items: flex-start; }
        .justify-between { justify-content: space-between; }
        .justify-center { justify-content: center; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        
        /* Colors */
        .bg-white { background-color: #ffffff; }
        .bg-slate-50 { background-color: #f8fafc; }
        .rounded-xl { border-radius: 1rem; }
        .rounded-lg { border-radius: 0.75rem; }
        .rounded-full { border-radius: 9999px; }
        
        .shadow-sm { box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); }
        .shadow-md { box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06); }
        .border { border: 1px solid #e9ecef; }
        .border-b { border-bottom: 1px solid #e9ecef; }
        .border-t { border-top: 1px solid #e9ecef; }
        
        .text-2xl { font-size: 1.5rem; }
        .text-xl { font-size: 1.25rem; }
        .text-lg { font-size: 1.125rem; }
        .text-base { font-size: 1rem; }
        .text-\[14px\] { font-size: 14px; }
        .text-\[13px\] { font-size: 13px; }
        .text-\[12px\] { font-size: 12px; }
        .text-\[11px\] { font-size: 11px; }
        .text-\[10px\] { font-size: 10px; }
        
        .font-bold { font-weight: 700; }
        .font-medium { font-weight: 500; }
        .font-semibold { font-weight: 600; }
        .font-black { font-weight: 900; }
        .font-mono { font-family: 'SF Mono', Monaco, monospace; }
        
        .text-\[\#000\] { color: #000000; }
        .text-\[\#1a1a2e\] { color: #1a1a2e; }
        .text-\[\#3b3b3b\] { color: #3b3b3b; }
        .text-\[\#6c757d\] { color: #6c757d; }
        .text-\[\#990dd1\] { color: #990dd1; }
        .text-white { color: #ffffff; }
        .text-\[\#2e7d32\] { color: #2e7d32; }
        .text-\[\#1565c0\] { color: #1565c0; }
        .text-\[\#f57c00\] { color: #f57c00; }
        .text-\[\#c62828\] { color: #c62828; }
        
        .uppercase { text-transform: uppercase; }
        .tracking-wider { letter-spacing: 0.05em; }
        
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        
        .w-full { width: 100%; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .overflow-x-auto { overflow-x: auto; }
        
        /* Card styles */
        .stat-card {
            background: white;
            border-radius: 1.25rem;
            padding: 1.25rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            transition: all 0.25s ease;
            border: 1px solid rgba(153, 13, 209, 0.08);
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(153, 13, 209, 0.12);
        }
        
        .gradient-card {
            background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
            border-radius: 1.25rem;
            padding: 1.25rem;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        /* Calendar Styles */
        .calendar-container {
            background: white;
            border-radius: 1.25rem;
            border: 1px solid rgba(153, 13, 209, 0.1);
            overflow: hidden;
        }
        .calendar-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e9ecef;
            background: #fafbfc;
        }
        .calendar-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #1a1a2e;
        }
        .calendar-weekday {
            padding: 0.6rem 0.25rem;
            text-align: center;
            font-size: 0.7rem;
            font-weight: 600;
            color: #6c757d;
            background: #ffffff;
        }
        .calendar-day {
            aspect-ratio: 1;
            padding: 0.4rem;
            text-align: center;
            border-top: 1px solid #f0f2f5;
            border-right: 1px solid #f0f2f5;
            transition: all 0.2s ease;
        }
        .calendar-day:nth-child(7n) {
            border-right: none;
        }
        .calendar-day:hover {
            background: #faf5ff;
        }
        .calendar-day.other-month {
            background-color: #fafbfc;
            color: #cbd5e1;
        }
        .calendar-day.today {
            background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
        }
        .day-number {
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
        }
        .today .day-number {
            background: #990dd1;
            color: white;
            font-weight: 700;
        }
        .has-deadline .day-number {
            background: #990dd1;
            color: white;
            font-weight: 700;
        }
        .deadline-dot {
            width: 5px;
            height: 5px;
            background: #f57c00;
            border-radius: 50%;
            margin: 2px auto 0;
        }
        
        /* Table styles - No Icons */
        .data-table {
            background: white;
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(153, 13, 209, 0.08);
        }
        .table-header-row {
            background: linear-gradient(135deg, #faf5ff 0%, #f8f9fc 100%);
            border-bottom: 1px solid rgba(153, 13, 209, 0.1);
        }
        .record-row {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f2f5;
        }
        .record-row:hover {
            background: linear-gradient(135deg, rgba(185, 121, 204, 0.04) 0%, rgba(153, 13, 209, 0.02) 100%);
        }
        
        /* Status badges - No Icons */
        .status-approved { background: #e8f5e9; color: #2e7d32; }
        .status-pending { background: #fff8e1; color: #f57c00; }
        .status-revision { background: #e3f2fd; color: #1565c0; }
        .status-cancelled { background: #ffebee; color: #c62828; }
        .status-completed { background: #e0f2f1; color: #00695c; }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.8rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        
        /* Type badges - No Icons */
        .type-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.8rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        .type-design {
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
            color: #6b21e5;
        }
        .type-report {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
        }
        
        /* Header */
        .staff-header {
            position: fixed;
            top: 0;
            left: 256px;
            right: 0;
            height: 80px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            z-index: 40;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            border-bottom: 1px solid rgba(153, 13, 209, 0.1);
        }
        .header-title {
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1a1a2e 0%, #990dd1 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .header-subtitle {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #6c757d;
            font-weight: 600;
        }
        
        /* Deadline list styles */
        .deadlines-section {
            background: white;
            border-radius: 1.25rem;
            border: 1px solid rgba(153, 13, 209, 0.1);
            overflow: hidden;
        }
        .deadlines-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e9ecef;
            background: #fafbfc;
        }
        .deadlines-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #1a1a2e;
        }
        .deadline-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f0f2f5;
            transition: all 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .deadline-item:last-child {
            border-bottom: none;
        }
        .deadline-item:hover {
            background: #faf5ff;
        }
        .deadline-info {
            flex: 1;
        }
        .deadline-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 0.2rem;
        }
        .deadline-control {
            font-size: 0.65rem;
            color: #6c757d;
            font-family: monospace;
        }
        .deadline-due {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 30px;
            white-space: nowrap;
        }
        .deadline-due.urgent {
            background: #ffebee;
            color: #c62828;
        }
        .deadline-due.warning {
            background: #fff8e1;
            color: #f57c00;
        }
        .deadline-due.normal {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        /* Button */
        .btn-link {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            color: #495057;
            transition: all 0.2s;
        }
        .btn-link:hover {
            background: #e9ecef;
        }
        
        .text-decoration-none { text-decoration: none; }
        .cursor-pointer { cursor: pointer; }
        .overflow-hidden { overflow: hidden; }
        .relative { position: relative; }
        .absolute { position: absolute; }
        .top-0 { top: 0; }
        .right-0 { right: 0; }
        .opacity-10 { opacity: 0.1; }
        .z-10 { z-index: 10; }
        .col-span-9 { grid-column: span 9; }
        .col-span-3 { grid-column: span 3; }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%); border-radius: 10px; }
        
        /* Watermark */
        .watermark {
            position: fixed;
            bottom: 16px;
            left: 256px;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: rgba(59, 59, 59, 0.2);
            font-weight: 500;
            pointer-events: none;
            z-index: 40;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .col-span-9, .col-span-3 { grid-column: span 12; }
        }
        @media (max-width: 768px) {
            .ml-64 { margin-left: 0; }
            .staff-header { left: 0; padding: 0 1rem; }
            .p-8 { padding: 1rem; }
            .grid-cols-3 { grid-template-columns: repeat(1, 1fr); }
        }
        
        .space-y-2 > * + * { margin-top: 0.5rem; }
    </style>
</head>

<body>
    <header class="staff-header">
        <div>
            <h2 class="header-title">OSS Dashboard</h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="header-subtitle">Office of Student Services - Gender and Development Information Management System</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative">
                <div class="w-10 h-10 bg-gradient-to-r from-[#990dd1]/10 to-[#b979cc]/10 rounded-full flex items-center justify-center">
                    <span style="font-size: 1.2rem;">👤</span>
                </div>
            </div>
        </div>
    </header>

    <?php include 'includes/sidebar_twg.php'; ?>

    <main class="ml-64 min-h-screen">
        <div class="p-8">
            
            <!-- Stats Row -->
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="gradient-card">
                    <div class="absolute top-0 right-0 p-3 opacity-10">
                        <span style="font-size: 3rem;">💰</span>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-1">
                                <span>📈</span>
                                <span class="text-[11px] font-medium uppercase tracking-wider opacity-80">Budget Utilization</span>
                            </div>
                            <span class="text-xl font-bold">99.5%</span>
                        </div>
                        <div class="w-full h-1.5 bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full bg-[#ecd224] rounded-full" style="width: 99.5%"></div>
                        </div>
                        <div class="flex justify-between text-[11px] opacity-70 mt-1">
                            <span>₱132.4M / ₱133.0M</span>
                            <span>₱603K left</span>
                        </div>
                    </div>
                </div>

                <div class="gradient-card" style="background: linear-gradient(135deg, #b979cc 0%, #990dd1 100%);">
                    <div class="absolute top-0 right-0 p-3 opacity-10">
                        <span style="font-size: 3rem;">💵</span>
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-1">
                                <span>🏦</span>
                                <span class="text-[11px] font-medium uppercase tracking-wider opacity-80">Remaining Budget</span>
                            </div>
                            <span class="text-xl font-bold">₱603K</span>
                        </div>
                        <div class="w-full h-1.5 bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full bg-[#ecd224] rounded-full" style="width: 0.45%"></div>
                        </div>
                        <div class="flex justify-between text-[11px] opacity-70 mt-1">
                            <span>₱132.4M utilized</span>
                            <span>0.45% left</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-[#f57c00]/20 to-[#f57c00]/10 rounded-xl flex items-center justify-center">
                                <span class="text-[#f57c00] text-xl">⏰</span>
                            </div>
                            <div>
                                <p class="text-[12px] text-[#6c757d]">Pending Tasks</p>
                                <p class="text-2xl font-bold text-[#1a1a2e]">5</p>
                            </div>
                        </div>
                        <div class="flex gap-2 text-[10px] font-bold">
                            <span class="text-[#1565c0]">3 Revision</span>
                            <span class="text-[#f57c00]">2 Pending</span>
                        </div>
                    </div>
                    <p class="text-[10px] text-[#6c757d] mt-2">Requires your attention</p>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-12 gap-6">
                <!-- Main Content Column -->
                <div class="col-span-9">
                    <!-- Transaction History -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-bold text-[#1a1a2e] uppercase tracking-wider opacity-60">Transaction History</h3>
                            <div class="flex gap-2">
                                <span class="text-[10px] px-2 py-0.5 bg-gradient-to-r from-[#b979cc]/20 to-[#990dd1]/10 text-[#990dd1] rounded-full">Activity Design</span>
                                <span class="text-[10px] px-2 py-0.5 bg-gradient-to-r from-[#3f6516]/20 to-[#3f6516]/10 text-[#3f6516] rounded-full">Accomplishment Report</span>
                            </div>
                        </div>
                        
                        <div class="data-table">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="table-header-row">
                                            <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#6c757d]">Type</th>
                                            <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#6c757d]">Control No. / Activity</th>
                                            <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#6c757d]">Date</th>
                                            <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#6c757d]">Status</th>
                                            <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#6c757d] text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <tr class="record-row">
                                            <td class="px-6 py-4">
                                                <span class="type-badge type-report">Accomplishment Report</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div>
                                                    <span class="font-mono text-[11px] text-[#6c757d]">ACC-OSS-2025-004</span>
                                                    <p class="font-medium text-[#1a1a2e] text-[12px]">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-[12px] text-[#6c757d]">Apr 15, 2025</td>
                                            <td class="px-6 py-4">
                                                <span class="status-badge status-revision">For Revision</span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="edit_accomplishment.php?id=4" class="btn-link">Update</a>
                                            </td>
                                        </tr>
                                        <tr class="record-row">
                                            <td class="px-6 py-4">
                                                <span class="type-badge type-design">Activity Design</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div>
                                                    <span class="font-mono text-[11px] text-[#6c757d]">OSS-2025-004</span>
                                                    <p class="font-medium text-[#1a1a2e] text-[12px]">Sed do eiusmod tempor incididunt ut labore</p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-[12px] text-[#6c757d]">Apr 05, 2025</td>
                                            <td class="px-6 py-4">
                                                <span class="status-badge status-cancelled">Cancelled</span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="view_design.php?id=4" class="btn-link">View</a>
                                            </td>
                                        </tr>
                                        <tr class="record-row">
                                            <td class="px-6 py-4">
                                                <span class="type-badge type-report">Accomplishment Report</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div>
                                                    <span class="font-mono text-[11px] text-[#6c757d]">ACC-OSS-2025-003</span>
                                                    <p class="font-medium text-[#1a1a2e] text-[12px]">Ut enim ad minim veniam, quis nostrud exercitation</p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-[12px] text-[#6c757d]">Apr 10, 2025</td>
                                            <td class="px-6 py-4">
                                                <span class="status-badge status-pending">Pending Review</span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="view_accomplishment.php?id=3" class="btn-link">View</a>
                                                                                        </td>
                                        </tr>
                                        <tr class="record-row">
                                            <td class="px-6 py-4">
                                                <span class="type-badge type-design">Activity Design</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div>
                                                    <span class="font-mono text-[11px] text-[#6c757d]">OSS-2025-003</span>
                                                    <p class="font-medium text-[#1a1a2e] text-[12px]">Duis aute irure dolor in reprehenderit in voluptate</p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-[12px] text-[#6c757d]">Mar 24, 2025</td>
                                            <td class="px-6 py-4">
                                                <span class="status-badge status-pending">Pending Review</span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="view_design.php?id=3" class="btn-link">View</a>
                                            </td>
                                        </tr>
                                        <tr class="record-row">
                                            <td class="px-6 py-4">
                                                <span class="type-badge type-report">Accomplishment Report</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div>
                                                    <span class="font-mono text-[11px] text-[#6c757d]">ACC-OSS-2024-002</span>
                                                    <p class="font-medium text-[#1a1a2e] text-[12px]">Excepteur sint occaecat cupidatat non proident</p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-[12px] text-[#6c757d]">Dec 20, 2024</td>
                                            <td class="px-6 py-4">
                                                <span class="status-badge status-completed">Completed</span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="view_accomplishment.php?id=2" class="btn-link">View</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-6 py-4 border-t border-slate-100 bg-gradient-to-r from-slate-50/50 to-white flex justify-between items-center">
                                <p class="text-[11px] text-[#6c757d]">Showing 5 of 8 total transactions</p>
                                <div class="flex gap-4">
                                    <a href="twg_submitted_list.php?tab=designs" class="text-[11px] font-bold text-[#990dd1] hover:underline text-decoration-none">All Activity Designs →</a>
                                    <a href="twg_submitted_list.php?tab=reports" class="text-[11px] font-bold text-[#990dd1] hover:underline text-decoration-none">All Accomplishment Reports →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar - Calendar & Deadlines -->
                <div class="col-span-3">
                    <!-- Calendar Section -->
                    <div class="calendar-container mb-6">
                        <div class="calendar-header">
                            <h3><?php echo $currentMonth . ' ' . $currentYear; ?></h3>
                        </div>
                        
                        <!-- Weekday Headers -->
                        <div class="grid grid-cols-7">
                            <div class="calendar-weekday">S</div>
                            <div class="calendar-weekday">M</div>
                            <div class="calendar-weekday">T</div>
                            <div class="calendar-weekday">W</div>
                            <div class="calendar-weekday">T</div>
                            <div class="calendar-weekday">F</div>
                            <div class="calendar-weekday">S</div>
                        </div>
                        
                        <!-- Calendar Days -->
                        <?php foreach ($calendarWeeks as $week): ?>
                            <div class="grid grid-cols-7">
                                <?php foreach ($week as $day): ?>
                                    <div class="calendar-day <?php echo !$day['isCurrentMonth'] ? 'other-month' : ''; ?> <?php echo $day['isToday'] ? 'today' : ''; ?> <?php echo $day['hasDeadline'] ? 'has-deadline' : ''; ?>">
                                        <div class="day-number"><?php echo $day['dayNum']; ?></div>
                                        <?php if ($day['hasDeadline'] && $day['isCurrentMonth']): ?>
                                            <div class="deadline-dot"></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Upcoming Deadlines Section -->
                    <div class="deadlines-section">
                        <div class="deadlines-header">
                            <h3>UPCOMING DEADLINES</h3>
                        </div>
                        
                        <div>
                            <?php 
                            $displayedDeadlines = array_slice($deadlines, 0, 5);
                            foreach ($displayedDeadlines as $deadline): 
                                $daysRemaining = getDaysRemaining($deadline['due_date']);
                                $dueText = getDueText($daysRemaining);
                                $dueClass = '';
                                if ($daysRemaining <= 2 && $daysRemaining >= 0) $dueClass = 'urgent';
                                elseif ($daysRemaining <= 5) $dueClass = 'warning';
                                else $dueClass = 'normal';
                            ?>
                                <div class="deadline-item">
                                    <div class="deadline-info">
                                        <div class="deadline-title"><?php echo htmlspecialchars($deadline['title']); ?></div>
                                        <div class="deadline-control"><?php echo $deadline['control']; ?></div>
                                    </div>
                                    <div class="deadline-due <?php echo $dueClass; ?>">
                                        <?php echo $dueText; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="p-3 border-t border-slate-100 bg-gradient-to-r from-slate-50/50 to-white text-center">
                            <a href="twg_submitted_list.php?tab=reports&status=revision" class="text-[11px] font-semibold text-[#990dd1] hover:underline text-decoration-none flex items-center justify-center gap-1">
                                View All Deadlines →
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </main>
    
    <div class="watermark">Benguet State University - Gender and Development Information Management System | GAD-IMS v1.0</div>
</body>
</html>