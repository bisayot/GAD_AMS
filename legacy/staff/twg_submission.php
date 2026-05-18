<?php
// staff/twg_submissions.php - TWG Submissions Overview (Static HTML/CSS Version)
require_once '../auth.php';
require_role(['gad_staff', 'admin']);
$page_title = 'Submitted List';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Submitted List | GAD-IMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f0f2f8;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Layout */
        .main-content {
            margin-left: 260px;
            margin-top: 70px;
            padding: 32px;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Typography */
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            letter-spacing: -0.5px;
        }

        .section-desc {
            color: #666;
            font-size: 13px;
            margin-top: 6px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        .stat-card.purple {
            background: linear-gradient(135deg, #6b21e5 0%, #8b3fdf 100%);
            color: white;
        }

        .stat-card.purple .stat-label,
        .stat-card.purple .stat-sub {
            color: rgba(255, 255, 255, 0.8);
        }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            margin-bottom: 8px;
        }

        .stat-number {
            font-size: 42px;
            font-weight: 800;
            color: #1a1a2e;
            line-height: 1;
        }

        .stat-card.purple .stat-number {
            color: white;
        }

        .stat-sub {
            font-size: 11px;
            color: #999;
            margin-top: 8px;
        }

        .stat-icon {
            float: right;
            font-size: 48px;
            opacity: 0.3;
        }

        /* Filter Bar */
        .filter-bar {
            background: white;
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f6;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: #666;
            letter-spacing: 0.5px;
        }

        .filter-select {
            padding: 8px 16px;
            border-radius: 10px;
            border: 1px solid #e0e4e8;
            background: #f8f9fc;
            font-size: 12px;
            cursor: pointer;
            font-family: inherit;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 8px 16px 8px 36px;
            border-radius: 10px;
            border: 1px solid #e0e4e8;
            background: #f8f9fc;
            width: 260px;
            font-size: 12px;
            font-family: inherit;
        }

        .search-box span {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            opacity: 0.6;
        }

        .btn {
            padding: 8px 20px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            font-family: inherit;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #6b21e5;
            color: white;
        }

        .btn-primary:hover {
            background: #5a1bc9;
        }

        .btn-secondary {
            background: #f0f2f5;
            color: #444;
            border: 1px solid #e0e4e8;
        }

        .btn-secondary:hover {
            background: #e6e9ef;
        }

        /* Table */
        .table-wrapper {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f6;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 16px 20px;
            background: #fafbfd;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            border-bottom: 1px solid #eef2f6;
        }

        td {
            padding: 16px 20px;
            font-size: 13px;
            border-bottom: 1px solid #f0f2f5;
            color: #333;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #faf8ff;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-purple {
            background: #f0e6ff;
            color: #6b21e5;
        }

        .badge-green {
            background: #e6f7e6;
            color: #2e7d32;
        }

        .badge-gray {
            background: #f0f2f5;
            color: #888;
        }

        .count-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
        }

        .count-purple {
            background: #f0e6ff;
            color: #6b21e5;
        }

        .count-green {
            background: #e6f7e6;
            color: #2e7d32;
        }

        /* TWG Name with Icon */
        .twg-name {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .twg-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #f0e6ff 0%, #e8d9ff 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .twg-text {
            font-weight: 600;
            color: #1a1a2e;
        }

        /* Action Link */
        .action-link {
            color: #6b21e5;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .action-link:hover {
            background: #f0e6ff;
        }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background: white;
            border-top: 1px solid #eef2f6;
        }

        .pagination-info {
            font-size: 12px;
            color: #888;
        }

        .pagination-buttons {
            display: flex;
            gap: 6px;
        }

        .page-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid #e0e4e8;
            background: white;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .page-btn:hover {
            background: #f0e6ff;
            border-color: #6b21e5;
        }

        .page-btn.active {
            background: #6b21e5;
            border-color: #6b21e5;
            color: white;
        }

        .page-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Header Section */
        .page-header {
            margin-bottom: 28px;
        }

        .page-header h1 {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #6b21e5 0%, #8b3fdf 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .search-box input {
                width: 100%;
            }
            th, td {
                padding: 12px 16px;
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
            <h2 class="header-title">Submitted List</h2>
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

    <div class="main-content">
        <div class="container">

            <!-- Page Header -->
            <div class="page-header">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <div>
                        
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <button class="btn btn-secondary">📊 Export Report</button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card purple">
                    <div class="stat-icon">👥</div>
                    <div class="stat-label">Total TWGs</div>
                    <div class="stat-number">22</div>
                    <div class="stat-sub">Active Technical Working Groups</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📄</div>
                    <div class="stat-label">Total Activity Designs</div>
                    <div class="stat-number">12</div>
                    <div class="stat-sub">Across all TWGs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-label">Total Accomplishment Reports</div>
                    <div class="stat-number">8</div>
                    <div class="stat-sub">Submitted to date</div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-group">
                    <span class="filter-label">📋 Filter:</span>
                    <select class="filter-select">
                        <option>All TWGs</option>
                        <option>With Submissions</option>
                        <option>No Submissions</option>
                    </select>
                </div>
                <div class="filter-group">
                    <div class="search-box">
                        <span>🔍</span>
                        <input type="text" placeholder="Search TWG by name...">
                    </div>
                    <button class="btn btn-primary">Apply</button>
                    <button class="btn btn-secondary">Reset</button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>TWG / Office</th>
                            <th style="text-align: center">📄 Activity Designs</th>
                            <th style="text-align: center">📊 Accomplishment Reports</th>
                            <th style="text-align: center">📈 Total</th>
                            <th style="text-align: right">⚡ Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row 1 - GAD Office -->
                        <tr>
                            <td style="width: 50px; font-weight: 600; color: #999;">01</td>
                            <td>
                                <div class="twg-name">
                                    <div class="twg-icon">🏛️</div>
                                    <span class="twg-text">Gender And Development</span>
                                </div>
                            </td>
                            <td style="text-align: center">
                                <div class="count-circle count-purple" style="margin: 0 auto;">3</div>
                            </td>
                            <td style="text-align: center">
                                <div class="count-circle count-green" style="margin: 0 auto;">2</div>
                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-purple">5 total</span>
                            </td>
                            <td style="text-align: right">
                                <a href="#" class="action-link">👁️ View Details →</a>
                            </td>
                        </tr>
                        <!-- Row 2 - College of Agriculture -->
                        <tr>
                            <td style="font-weight: 600; color: #999;">02</td>
                            <td>
                                <div class="twg-name">
                                    <div class="twg-icon">🌾</div>
                                    <span class="twg-text">College of Agriculture</span>
                                </div>
                            </td>
                            <td style="text-align: center">
                                <div class="count-circle count-purple" style="margin: 0 auto;">1</div>
                            </td>
                            <td style="text-align: center">
                                <div class="count-circle count-green" style="margin: 0 auto;">1</div>
                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-purple">2 total</span>
                            </td>
                            <td style="text-align: right">
                                <a href="#" class="action-link">👁️ View Details →</a>
                            </td>
                        </tr>
                        <!-- Row 3 - Registrar's Office -->
                        <tr>
                            <td style="font-weight: 600; color: #999;">03</td>
                            <td>
                                <div class="twg-name">
                                    <div class="twg-icon">📋</div>
                                    <span class="twg-text">Registrar's Office BSU Buguias Campus</span>
                                </div>
                            </td>
                            <td style="text-align: center">
                                <span style="color: #bbb;">—</span>
                            </td>
                            <td style="text-align: center">
                                <span style="color: #bbb;">—</span>
                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-gray">0 submissions</span>
                            </td>
                            <td style="text-align: right">
                                <a href="#" class="action-link">👁️ View Details →</a>
                            </td>
                        </tr>
                        <!-- Row 4 - HRMO -->
                        <tr>
                            <td style="font-weight: 600; color: #999;">04</td>
                            <td>
                                <div class="twg-name">
                                    <div class="twg-icon">👥</div>
                                    <span class="twg-text">Human Resources and Management Office BSU Bokod Campus</span>
                                </div>
                            </td>
                            <td style="text-align: center">
                                <div class="count-circle count-purple" style="margin: 0 auto;">1</div>
                            </td>
                            <td style="text-align: center">
                                <span style="color: #bbb;">—</span>
                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-purple">1 total</span>
                            </td>
                            <td style="text-align: right">
                                <a href="#" class="action-link">👁️ View Details →</a>
                            </td>
                        </tr>
                        <!-- Row 5 - International Relations -->
                        <tr>
                            <td style="font-weight: 600; color: #999;">05</td>
                            <td>
                                <div class="twg-name">
                                    <div class="twg-icon">🌍</div>
                                    <span class="twg-text">International Relations Office</span>
                                </div>
                            </td>
                            <td style="text-align: center">
                                <span style="color: #bbb;">—</span>
                            </td>
                            <td style="text-align: center">
                                <div class="count-circle count-green" style="margin: 0 auto;">1</div>
                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-purple">1 total</span>
                            </td>
                            <td style="text-align: right">
                                <a href="#" class="action-link">👁️ View Details →</a>
                            </td>
                        </tr>
                        <!-- Row 6 - DRRM -->
                        <tr>
                            <td style="font-weight: 600; color: #999;">06</td>
                            <td>
                                <div class="twg-name">
                                    <div class="twg-icon">⚠️</div>
                                    <span class="twg-text">Disaster Risk Reduction Management</span>
                                </div>
                            </td>
                            <td style="text-align: center">
                                <span style="color: #bbb;">—</span>
                            </td>
                            <td style="text-align: center">
                                <span style="color: #bbb;">—</span>
                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-gray">0 submissions</span>
                            </td>
                            <td style="text-align: right">
                                <a href="#" class="action-link">👁️ View Details →</a>
                            </td>
                        </tr>
                        <!-- Row 7 - College of Social Science -->
                        <tr>
                            <td style="font-weight: 600; color: #999;">07</td>
                            <td>
                                <div class="twg-name">
                                    <div class="twg-icon">📚</div>
                                    <span class="twg-text">College of Social Science</span>
                                </div>
                            </td>
                            <td style="text-align: center">
                                <div class="count-circle count-purple" style="margin: 0 auto;">2</div>
                            </td>
                            <td style="text-align: center">
                                <div class="count-circle count-green" style="margin: 0 auto;">1</div>
                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-purple">3 total</span>
                            </td>
                            <td style="text-align: right">
                                <a href="#" class="action-link">👁️ View Details →</a>
                            </td>
                        </tr>
                        <!-- Row 8 - College of Applied Technology -->
                        <tr>
                            <td style="font-weight: 600; color: #999;">08</td>
                            <td>
                                <div class="twg-name">
                                    <div class="twg-icon">🔧</div>
                                    <span class="twg-text">College of Applied Technology BSU Bokod Campus</span>
                                </div>
                            </td>
                            <td style="text-align: center">
                                <span style="color: #bbb;">—</span>
                            </td>
                            <td style="text-align: center">
                                <span style="color: #bbb;">—</span>
                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-gray">0 submissions</span>
                            </td>
                            <td style="text-align: right">
                                <a href="#" class="action-link">👁️ View Details →</a>
                            </td>
                        </tr>
                        <!-- Row 9 - University Business Affairs -->
                        <tr>
                            <td style="font-weight: 600; color: #999;">09</td>
                            <td>
                                <div class="twg-name">
                                    <div class="twg-icon">💼</div>
                                    <span class="twg-text">University Business Affairs Office</span>
                                </div>
                            </td>
                            <td style="text-align: center">
                                <span style="color: #bbb;">—</span>
                            </td>
                            <td style="text-align: center">
                                <span style="color: #bbb;">—</span>
                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-gray">0 submissions</span>
                            </td>
                            <td style="text-align: right">
                                <a href="#" class="action-link">👁️ View Details →</a>
                            </td>
                        </tr>
                        <!-- Row 10 - University Library -->
                        <tr>
                            <td style="font-weight: 600; color: #999;">10</td>
                            <td>
                                <div class="twg-name">
                                    <div class="twg-icon">📖</div>
                                    <span class="twg-text">University Library and Information Service BSU Buguias Campus</span>
                                </div>
                            </td>
                            <td style="text-align: center">
                                <span style="color: #bbb;">—</span>
                            </td>
                            <td style="text-align: center">
                                <span style="color: #bbb;">—</span>
                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-gray">0 submissions</span>
                            </td>
                            <td style="text-align: right">
                                <a href="#" class="action-link">👁️ View Details →</a>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    <div class="pagination-info">
                        Showing <strong>1</strong> - <strong>10</strong> of <strong>22</strong> TWGs
                    </div>
                    <div class="pagination-buttons">
                        <button class="page-btn disabled">←</button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-btn">→</button>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div style="margin-top: 24px; text-align: center;">
                <p style="font-size: 10px; color: #aaa;">
                    📋 Showing submission counts for all Technical Working Groups | Last updated: <?php echo date('F j, Y'); ?>
                </p>
            </div>

        </div>
    </div>
</body>
</html>