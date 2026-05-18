<?php
// staff/mandates.php - GAD Office Mandates List (No JavaScript - Pure HTML/CSS)
require_once '../auth.php';
require_role(['gad_staff', 'admin']);
$page_title = 'GAD Office Mandates';
include 'sidebar.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GAD Office Mandates | GAD-IMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f0f2f8;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px;
        }

        /* Header Section */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 28px;
            padding: 40px;
            margin-bottom: 12px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 24px;
        }

        .hero-text h1 {
            font-size: 36px;
            font-weight: 800;
            color: white;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .hero-text p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.85);
            max-width: 500px;
            line-height: 1.5;
        }

        .hero-stats {
            display: flex;
            gap: 20px;
        }

        .stat-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 16px 24px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-badge .number {
            font-size: 32px;
            font-weight: 800;
            color: white;
        }

        .stat-badge .label {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        /* Action Cards */
        .action-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        .action-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s ease;
            border: 1px solid #eef2f6;
            text-decoration: none;
            display: block;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -15px rgba(0, 0, 0, 0.1);
            border-color: #e9d5ff;
        }

        .action-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 28px;
        }

        .icon-purple {
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
            color: #6b21e5;
        }

        .icon-emerald {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
        }

        .action-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .action-card p {
            font-size: 12px;
            color: #94a3b8;
        }

        /* Filter Bar */
        .filter-bar {
            background: white;
            border-radius: 20px;
            padding: 20px 24px;
            margin-bottom: 28px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f6;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .filter-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
        }

        .filter-select {
            padding: 10px 16px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            font-size: 13px;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-select:focus {
            outline: none;
            border-color: #6b21e5;
            box-shadow: 0 0 0 3px rgba(107, 33, 229, 0.1);
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 16px 10px 40px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            width: 260px;
            font-size: 13px;
            font-family: inherit;
            transition: all 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #6b21e5;
            box-shadow: 0 0 0 3px rgba(107, 33, 229, 0.1);
            background: white;
        }

        .search-box span {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #94a3b8;
        }

        .btn-reset {
            padding: 10px 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: white;
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-reset:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* Table */
        .table-wrapper {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #eef2f6;
        }

        .table-header {
            display: grid;
            grid-template-columns: 60px 1fr 180px;
            background: #f8fafc;
            padding: 16px 24px;
            border-bottom: 1px solid #eef2f6;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        .mandate-row {
            display: grid;
            grid-template-columns: 60px 1fr 180px;
            padding: 20px 24px;
            border-bottom: 1px solid #f0f2f5;
            transition: all 0.2s;
            cursor: pointer;
            text-decoration: none;
        }

        .mandate-row:hover {
            background: #faf8ff;
        }

        .mandate-number {
            font-weight: 700;
            color: #6b21e5;
            font-size: 14px;
        }

        .mandate-title {
            font-weight: 500;
            color: #1e293b;
            font-size: 14px;
            line-height: 1.4;
        }

        .mandate-office {
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-active {
            background: #fef3c7;
            color: #b45309;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-upcoming {
            background: #e0e7ff;
            color: #3730a3;
        }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            background: white;
            border-top: 1px solid #eef2f6;
        }

        .pagination-info {
            font-size: 13px;
            color: #64748b;
        }

        .pagination-buttons {
            display: flex;
            gap: 8px;
        }

        .page-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: white;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: #475569;
        }

        .page-btn:hover {
            background: #f3e8ff;
            border-color: #6b21e5;
            color: #6b21e5;
        }

        .page-btn.active {
            background: #6b21e5;
            border-color: #6b21e5;
            color: white;
        }

        .page-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Footer */
        .footer-note {
            text-align: center;
            margin-top: 24px;
            font-size: 10px;
            color: #94a3b8;
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 20px;
            }

            .hero-section {
                padding: 28px;
            }

            .hero-content {
                flex-direction: column;
                text-align: center;
            }

            .hero-text h1 {
                font-size: 28px;
            }

            .action-cards {
                grid-template-columns: 1fr;
            }

            .table-header,
            .mandate-row {
                grid-template-columns: 50px 1fr 140px;
                padding: 16px;
            }

            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box input {
                width: 100%;
            }

            .pagination {
                flex-direction: column;
                gap: 16px;
            }
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
    </style>
</head>

<body>
    <header class="staff-header">
        <div>
            <h2 class="header-title">GAD Office Mandates</h2>
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
        <div class="main-container">
            <!-- Hero Section -->
            <div class="hero-section">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1>GAD Office Mandates</h1>
                        <p>Gender and Development mandates where the GAD Office is the responsible unit, as outlined in the BSU GAD Plan and Budget (GPB) 2026.</p>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-badge">
                            <div class="number">22</div>
                            <div class="label">Total Mandates</div>
                        </div>
                        <div class="stat-badge">
                            <div class="number">15+</div>
                            <div class="label">Active</div>
                        </div>
                        <div class="stat-badge">
                            <div class="number">100%</div>
                            <div class="label">Compliance</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="action-cards"><a href="gad_plan_budget.php" class="action-card">
                    <div class="action-icon icon-purple">📜</div>
                    <h3>GAD Plan & Budget</h3>
                    <p>View GPB 2026 document</p>
                </a><a href="assign_mandates.php" class="action-card">
                    <div class="action-icon icon-emerald">👤</div>
                    <h3>Assign Mandates</h3>
                    <p>Link mandates to reports</p>
                </a><a href="#" class="action-card">
                    <div class="action-icon icon-purple">📊</div>
                    <h3>Mandate Reports</h3>
                    <p>Generate compliance reports</p>
                </a></div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-group"><span class="filter-label">📋 Filter:</span>
                    <form method="GET" action="" style="display: flex; gap: 12px; flex-wrap: wrap;"><select name="status" class="filter-select">
                            <option value="all">All Mandates</option>
                            <option value="active" <?php echo (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : ''; ?>>In Progress</option>
                            <option value="completed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="upcoming" <?php echo (isset($_GET['status']) && $_GET['status'] == 'upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                        </select>
                </div>
                <div class="filter-group">
                    <div class="search-box"><span>🔍</span><input type="text" name="search" placeholder="Search mandates..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"></div><button type="submit" class="btn-reset" style="background: #6b21e5; color: white; border: none;">Apply</button><a href="mandates.php" class="btn-reset">Reset</a></form>
                </div>
            </div>

            <!-- Table -->
            <div class="table-wrapper">
                <div class="table-header">
                    <div>#</div>
                    <div>Gender Issue / GAD Mandate</div>
                    <div>Responsible Unit</div>
                </div>
                <?php
                // Mandates Data
                $mandates = [
                    ['id' => 1, 'title' => "CHED Memorandum Order No. 01 series 2015 - Conduct GAD orientation/forum/seminar to BSU 1st year/transferees students", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 2, 'title' => "CHED Memorandum Order No. 01 series 2015 - Continuous conduct of GAD responsive leadership training for student leaders", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 3, 'title' => "Limited application of GAD Mainstreaming in Instruction, Research, Extension and Production - Conduct GAD related Gender Mainstreaming capability building", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 4, 'title' => "Magna Carta of Women (RA 9710) - Create Monitoring Team to evaluate GAD PAPs", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 5, 'title' => "Magna Carta of Women IRR Section 37 C - GFPS capacity building and TOT on GAD mandates", 'responsible' => "GAD Office", 'status' => "completed"],
                    ['id' => 6, 'title' => "Section 37-C2 Rule VI - Regular coordination and meetings of GAD-GFPS", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 7, 'title' => "Duties of GAD Focal Point System/CHED Memo 2015-1 - Engage support staff for GFPS implementation", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 8, 'title' => "Low awareness on Gender Mainstreaming among newly hired personnel - Gender Sensitivity Training", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 9, 'title' => "Development of GAD IEC Materials - Development and Dissemination of GAD Information Materials", 'responsible' => "GAD Office", 'status' => "completed"],
                    ['id' => 10, 'title' => "Compliance to VAW Campaigns - Participation to 18-Day Campaign to end VAW and Women's Month", 'responsible' => "GAD Office", 'status' => "completed"],
                    ['id' => 11, 'title' => "Executive Order No. 340 s. 1997 - Maintenance of Child Minding Center for working parents", 'responsible' => "GAD Office", 'status' => "completed"],
                    ['id' => 12, 'title' => "Low employee understanding of gender issues (RA 9710) - Gender sensitivity orientations for BSU Personnel", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 13, 'title' => "Establishment of Gender-Responsive Curricular Programs - Preparation of syllabi integrating gender perspective", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 14, 'title' => "Sustain functional GAD Focal Point System - Sustaining Gender Mainstreaming and Institutional Support", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 15, 'title' => "CHED Memorandum Order No. 01 s.2015 - GAD orientation for 1st year/transferees (4,000 students)", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 16, 'title' => "CMO 01 s.2015 - Student Leadership Training - GAD responsive leadership training", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 17, 'title' => "RA 10121 - Crisis Intervention - Distribution of hygiene kits for crisis situations", 'responsible' => "GAD Office", 'status' => "completed"],
                    ['id' => 18, 'title' => "GREP Extension Program - Conduct of Extension activities to partner communities", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 19, 'title' => "BSU-PRAISE GAD Recognition - Provide recognition awards to GAD implementers", 'responsible' => "GAD Office", 'status' => "upcoming"],
                    ['id' => 20, 'title' => "Reproductive Health Care Center - Operationalize BSU College of Nursing RH Center", 'responsible' => "GAD Office", 'status' => "active"],
                    ['id' => 21, 'title' => "GAD Database and Sex-Disaggregated Database - Institutionalize GAD database system", 'responsible' => "GAD Office", 'status' => "upcoming"],
                    ['id' => 22, 'title' => "Establishment/ maintenance of breastfeeding station - Fully maintained Lactation rooms", 'responsible' => "GAD Office", 'status' => "active"]
                ];

                // Apply Filters
                $statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
                $searchFilter = isset($_GET['search']) ? trim($_GET['search']) : '';
                $filteredMandates = $mandates;
                if ($statusFilter !== 'all') {
                    $filteredMandates = array_filter($filteredMandates, function ($m) use ($statusFilter) {
                        return $m['status'] === $statusFilter;
                    });
                }
                if (!empty($searchFilter)) {
                    $filteredMandates = array_filter($filteredMandates, function ($m) use ($searchFilter) {
                        return stripos($m['title'], $searchFilter) !== false;
                    });
                }
                $filteredMandates = array_values($filteredMandates);
                $totalItems = count($filteredMandates);

                // Pagination
                $itemsPerPage = 10;
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $totalPages = ceil($totalItems / $itemsPerPage);
                if ($currentPage < 1) $currentPage = 1;
                if ($currentPage > $totalPages && $totalPages > 0) $currentPage = $totalPages;
                $startIndex = ($currentPage - 1) * $itemsPerPage;
                $paginatedItems = array_slice($filteredMandates, $startIndex, $itemsPerPage);

                if (count($paginatedItems) === 0): ?>
                    <div style="padding: 60px; text-align: center; color: #94a3b8;"><span style="font-size: 48px; display: block; margin-bottom: 16px;">📭</span>
                        <p>No mandates found matching your criteria</p><a href="mandates.php" style="color: #6b21e5; margin-top: 12px; display: inline-block;">Clear all filters</a>
                    </div>
                    <?php else: foreach ($paginatedItems as $idx => $item): $statusClass = $item['status'] === 'active' ? 'status-active' : ($item['status'] === 'completed' ? 'status-completed' : 'status-upcoming');
                        $statusText = $item['status'] === 'active' ? 'In Progress' : ($item['status'] === 'completed' ? 'Completed' : 'Upcoming'); ?>
                        <a href="mandate_details.php?id=<?php echo $item['id']; ?>" class="mandate-row">
                            <div class="mandate-number"><?php echo str_pad($startIndex + $idx + 1, 2, '0', STR_PAD_LEFT); ?></div>
                            <div class="mandate-title"><?php echo htmlspecialchars($item['title']); ?><div style="margin-top: 8px;"><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></div>
                            </div>
                            <div class="mandate-office"><?php echo htmlspecialchars($item['responsible']); ?></div>
                        </a>
                <?php endforeach;
                endif; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <div class="pagination-info">Showing <?php echo $startIndex + 1; ?> - <?php echo min($startIndex + $itemsPerPage, $totalItems); ?> of <?php echo $totalItems; ?> mandates</div>
                <div class="pagination-buttons">
                    <?php if ($currentPage > 1): ?><a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $currentPage - 1])); ?>" class="page-btn">←</a><?php else: ?><span class="page-btn disabled">←</span><?php endif; ?>
                    <?php
                    $maxVisible = 5;
                    $startPage = max(1, $currentPage - floor($maxVisible / 2));
                    $endPage = min($totalPages, $startPage + $maxVisible - 1);
                    if ($endPage - $startPage + 1 < $maxVisible) {
                        $startPage = max(1, $endPage - $maxVisible + 1);
                    }
                    if ($startPage > 1): ?><a href="?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>" class="page-btn">1</a><?php if ($startPage > 2): ?><span class="page-btn disabled">...</span><?php endif;
                                                                                                                                                                                                                endif;
                                                                                                                                                                                                                for ($i = $startPage; $i <= $endPage; $i++): if ($i == $currentPage): ?><span class="page-btn active"><?php echo $i; ?></span><?php else: ?><a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" class="page-btn"><?php echo $i; ?></a><?php endif;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                            endfor;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                            if ($endPage < $totalPages): if ($endPage < $totalPages - 1): ?><span class="page-btn disabled">...</span><?php endif; ?><a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $totalPages])); ?>" class="page-btn"><?php echo $totalPages; ?></a><?php endif; ?>
                    <?php if ($currentPage < $totalPages): ?><a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $currentPage + 1])); ?>" class="page-btn">→</a><?php else: ?><span class="page-btn disabled">→</span><?php endif; ?>
                </div>
            </div>
            <div class="footer-note">
                <p>📋 Showing GAD Office mandates as outlined in the BSU GAD Plan and Budget (GPB) 2026 | Last updated: <?php echo date('F j, Y'); ?></p>
            </div>
        </div>
    </main>
</body>

</html>