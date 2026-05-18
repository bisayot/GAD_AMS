<?php
// staff/mandates.php - OSS Mandates List (From GPB 2026 - OSS as Responsible Unit)
// require_once '../auth.php';
// require_role(['gad_staff', 'admin']);
// $page_title = 'OSS Mandates';
include 'includes/sidebar_twg.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OSS Mandates | GAD-IMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fc 0%, #eef2f8 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin-top: 80px;
        }

        /* @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap'); */

        /* Layout */
        .pl-64 { padding-left: 256px; }
        .pt-16 { padding-top: 0; }
        .min-h-screen { min-height: 100vh; }
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header Styles */
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

        .flex { display: flex; }
        .items-center { align-items: center; }
        .gap-2 { gap: 0.5rem; }
        .gap-4 { gap: 1rem; }
        .relative { position: relative; }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
            border-radius: 1.75rem;
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
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
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
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
            gap: 1.5rem;
        }
        .hero-text {
            flex: 1;
        }
        .hero-text h1 {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }
        .hero-text p {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.85);
            max-width: 550px;
            line-height: 1.5;
        }
        
        /* GAD Plan & Budget Card inside Hero */
        .gad-plan-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 1.25rem;
            padding: 1.25rem 1.5rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            min-width: 180px;
        }
        .gad-plan-card:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        .gad-plan-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .gad-plan-card h3 {
            font-size: 1rem;
            font-weight: 700;
            color: white;
            margin: 0;
        }
        .gad-plan-card p {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }
        .gad-plan-arrow {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .hero-stats { 
            display: flex; 
            gap: 1rem; 
            margin-top: 1rem;
        }
        .stat-badge {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 0.75rem 1.25rem;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .stat-badge .number { font-size: 1.75rem; font-weight: 800; color: white; }
        .stat-badge .label {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.7);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-top: 0.25rem;
        }

        /* Filter Bar */
        .filter-bar {
            background: white;
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.75rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border: 1px solid rgba(153, 13, 209, 0.08);
        }
        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .filter-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #6c757d;
            letter-spacing: 0.03em;
        }
        .filter-select {
            padding: 0.5rem 1rem;
            border-radius: 0.6rem;
            border: 1px solid #e9ecef;
            background: #f8f9fc;
            font-size: 0.75rem;
            font-family: inherit;
            cursor: pointer;
        }
        .filter-select:focus {
            outline: none;
            border-color: #990dd1;
            box-shadow: 0 0 0 2px rgba(153, 13, 209, 0.1);
        }
        .search-box {
            position: relative;
        }
        .search-box input {
            padding: 0.5rem 1rem 0.5rem 2.25rem;
            width: 260px;
            border-radius: 0.6rem;
            border: 1px solid #e9ecef;
            background: #f8f9fc;
            font-size: 0.75rem;
            font-family: inherit;
        }
        .search-box input:focus {
            outline: none;
            border-color: #990dd1;
            box-shadow: 0 0 0 2px rgba(153, 13, 209, 0.1);
            background: white;
        }
        .search-box span {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.85rem;
            opacity: 0.6;
        }
        .btn-reset {
            padding: 0.5rem 1.25rem;
            border-radius: 0.6rem;
            border: 1px solid #e9ecef;
            background: white;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6c757d;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-reset:hover { background: #f8fafc; border-color: #cbd5e1; }
        .btn-primary-custom {
            background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
            color: white;
            border: none;
        }
        .btn-primary-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(153, 13, 209, 0.3);
        }

        /* Table */
        .table-wrapper {
            background: white;
            border-radius: 1.25rem;
            overflow: hidden;
            border: 1px solid rgba(153, 13, 209, 0.08);
        }
        .table-header {
            display: grid;
            grid-template-columns: 60px 1fr 180px;
            background: linear-gradient(135deg, #faf5ff 0%, #f8f9fc 100%);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(153, 13, 209, 0.1);
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #6c757d;
            letter-spacing: 0.03em;
        }
        .mandate-row {
            display: grid;
            grid-template-columns: 60px 1fr 180px;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f0f2f5;
            transition: all 0.2s;
            cursor: pointer;
            text-decoration: none;
        }
        .mandate-row:hover {
            background: linear-gradient(135deg, rgba(185, 121, 204, 0.04) 0%, rgba(153, 13, 209, 0.02) 100%);
        }
        .mandate-number { font-weight: 700; color: #990dd1; font-size: 0.875rem; }
        .mandate-title { font-weight: 500; color: #1e293b; font-size: 0.875rem; line-height: 1.4; }
        .mandate-office { font-size: 0.8rem; color: #64748b; font-weight: 500; }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
        .status-active { background: #fff8e1; color: #f57c00; }
        .status-completed { background: #e8f5e9; color: #2e7d32; }
        .status-upcoming { background: #e3f2fd; color: #1565c0; }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            background: white;
            border-top: 1px solid #f0f2f5;
        }
        .pagination-info { font-size: 0.75rem; color: #64748b; }
        .pagination-buttons { display: flex; gap: 0.5rem; }
        .page-btn {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background: white;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: #475569;
        }
        .page-btn:hover {
            background: #faf5ff;
            border-color: #990dd1;
            color: #990dd1;
        }
        .page-btn.active {
            background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
            border-color: #990dd1;
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
            margin-top: 1.5rem;
            font-size: 0.7rem;
            color: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .hero-content {
                flex-direction: column;
                text-align: center;
            }
            .hero-text p {
                max-width: 100%;
            }
            .gad-plan-card {
                width: 100%;
                max-width: 250px;
                margin: 0 auto;
            }
        }
        @media (max-width: 768px) {
            .pl-64 { padding-left: 0; }
            .staff-header { left: 0; padding: 0 1rem; }
            .main-container { padding: 1rem; }
            .hero-section { padding: 1.5rem; }
            .hero-text h1 { font-size: 1.5rem; }
            .table-header, .mandate-row { grid-template-columns: 50px 1fr 120px; padding: 1rem; }
            .filter-bar { flex-direction: column; align-items: stretch; }
            .search-box input { width: 100%; }
            .pagination { flex-direction: column; gap: 1rem; }
            .hero-stats { flex-wrap: wrap; justify-content: center; }
        }

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
            <h2 class="header-title">Mandates</h2>
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
        <div class="main-container">
            <!-- Hero Section with GAD Plan Card inside -->
            <div class="hero-section">
                <div class="hero-content">
                    <div class="hero-text">
                        <h1>Office of Student Services - GAD Mandates</h1>
                        <p>Gender and Development mandates where the Office of Student Services (OSS) is the responsible unit, as outlined in the BSU GAD Plan and Budget (GPB) 2026.</p>
                        <div class="hero-stats">
                            <div class="stat-badge">
                                <div class="number">5</div>
                                <div class="label">Total Mandates</div>
                            </div>
                            <div class="stat-badge">
                                <div class="number">5</div>
                                <div class="label">Active</div>
                            </div>
                            <div class="stat-badge">
                                <div class="number">₱132.4M</div>
                                <div class="label">Total Budget</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- GAD Plan & Budget Card inside Hero - Right Corner -->
                    <a href="gad_plan_budget.php" class="gad-plan-card">
                        <div class="gad-plan-icon">📜</div>
                        <h3>GAD Plan & Budget</h3>
                        <p>View GPB 2026 document</p>
                        <span class="gad-plan-arrow">→</span>
                    </a>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-group">
                    <span class="filter-label">📋 Filter:</span>
                    <form method="GET" action="" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <select name="status" class="filter-select">
                            <option value="all">All Mandates</option>
                            <!-- <option value="active" <?php echo (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : ''; ?>>In Progress</option> -->
                            <option value="completed" <?php echo (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="upcoming" <?php echo (isset($_GET['status']) && $_GET['status'] == 'upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                        </select>
                </div>
                <div class="filter-group">
                    <div class="search-box">
                        <span>🔍</span>
                        <input type="text" name="search" placeholder="Search mandates..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                    <button type="submit" class="btn-reset btn-primary-custom">Apply</button>
                    <a href="mandates.php" class="btn-reset">Reset</a>
                    </form>
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
                // OSS Mandates Data - Based on GPB 2026 where OSS is Responsible Unit
                $mandates = [
                    [
                        'id' => 1, 
                        'title' => "Republic Act No. 10931, Universal Access to Quality Tertiary Education Act Section 8 on Affirmative Action Program - Implementation of Affirmative Action Agenda for disadvantaged students", 
                        'responsible' => "OSS", 
                        'status' => "active",
                        'budget' => "₱700,000",
                        'gad_activity' => "Implementation of Affirmative Action Agenda",
                        'target' => "Number of served disadvantaged students - 100% disadvantaged students"
                    ],
                    [
                        'id' => 2, 
                        'title' => "Republic Act No. 10931 - Provision of free tuition fee under RA 10931 to eligible male and female students", 
                        'responsible' => "OSS, OUR, UHS", 
                        'status' => "active",
                        'budget' => "₱131,100,000",
                        'gad_activity' => "Provision of free tuition fee under RA 10931",
                        'target' => "100% of qualified students granted free tuition"
                    ],
                    [
                        'id' => 3, 
                        'title' => "CHED Memorandum Order No. 01 series 2015 - Conduct GAD orientation/forum/seminar to BSU 1st year/transferees students", 
                        'responsible' => "OSS, GAD Office, 3 Campuses", 
                        'status' => "active",
                        'budget' => "₱453,363",
                        'gad_activity' => "GAD orientation for first year/transferee students",
                        'target' => "4,000 students oriented (F:2,750 M:1,250)"
                    ],
                    [
                        'id' => 4, 
                        'title' => "CHED Memorandum Order No. 01 series 2015 - Continuous conduct of GAD responsive leadership training for student leaders", 
                        'responsible' => "OSS", 
                        'status' => "active",
                        'budget' => "₱150,000",
                        'gad_activity' => "GAD responsive leadership training for student leaders",
                        'target' => "2 training sessions (Female:200 Male:100)"
                    ],
                    [
                        'id' => 5, 
                        'title' => "Circular No. 2003-01 - Awareness of women PWDs who benefited from program/project/activity", 
                        'responsible' => "HRMO, OSS", 
                        'status' => "active",
                        'budget' => "₱350,000",
                        'gad_activity' => "Programs for women PWDs",
                        'target' => "Number of women PWDs who benefited"
                    ]
                ];

                // Filter to only show OSS as primary responsible (contains OSS)
                $filteredMandates = array_filter($mandates, function($m) {
                    return strpos($m['responsible'], 'OSS') !== false;
                });
                $filteredMandates = array_values($filteredMandates);

                // Apply Status Filter
                $statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
                $searchFilter = isset($_GET['search']) ? trim($_GET['search']) : '';
                
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
                    <div style="padding: 3rem; text-align: center; color: #94a3b8;">
                        <span style="font-size: 3rem; display: block; margin-bottom: 1rem;">📭</span>
                        <p>No OSS mandates found matching your criteria</p>
                        <a href="mandates.php" style="color: #990dd1; margin-top: 0.75rem; display: inline-block; text-decoration: none;">Clear all filters</a>
                    </div>
                <?php else: foreach ($paginatedItems as $idx => $item): 
                    $statusClass = 'status-active';
                    // $statusText = 'In Progress';
                ?>
                    <a href="mandate_details.php?id=<?php echo $item['id']; ?>" class="mandate-row">
                        <div class="mandate-number"><?php echo str_pad($startIndex + $idx + 1, 2, '01', STR_PAD_LEFT); ?></div>
                        <div class="mandate-title">
                            <?php echo htmlspecialchars($item['title']); ?>
                            <div style="margin-top: 0.5rem;">
                                <!-- <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span> -->
                                <span style="margin-left: 0.5rem; font-size: 0.7rem; color: #6c757d;">Budget: <?php echo $item['budget']; ?></span>
                            </div>
                        </div>
                        <div class="mandate-office"><?php echo htmlspecialchars($item['responsible']); ?></div>
                    </a>
                <?php endforeach; endif; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <div class="pagination-info">
                    Showing <?php echo $startIndex + 1; ?> - <?php echo min($startIndex + $itemsPerPage, $totalItems); ?> of <?php echo $totalItems; ?> OSS mandates
                </div>
                <div class="pagination-buttons">
                    <?php if ($currentPage > 1): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $currentPage - 1])); ?>" class="page-btn">←</a>
                    <?php else: ?>
                        <span class="page-btn disabled">←</span>
                    <?php endif; ?>
                    
                    <?php
                    $maxVisible = 5;
                    $startPage = max(1, $currentPage - floor($maxVisible / 2));
                    $endPage = min($totalPages, $startPage + $maxVisible - 1);
                    if ($endPage - $startPage + 1 < $maxVisible) $startPage = max(1, $endPage - $maxVisible + 1);
                    
                    if ($startPage > 1): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>" class="page-btn">1</a>
                        <?php if ($startPage > 2): ?><span class="page-btn disabled">...</span><?php endif; ?>
                    <?php endif;
                    
                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="page-btn active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" class="page-btn"><?php echo $i; ?></a>
                        <?php endif;
                    endfor;
                    
                    if ($endPage < $totalPages): 
                        if ($endPage < $totalPages - 1): ?><span class="page-btn disabled">...</span><?php endif; ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $totalPages])); ?>" class="page-btn"><?php echo $totalPages; ?></a>
                    <?php endif; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $currentPage + 1])); ?>" class="page-btn">→</a>
                    <?php else: ?>
                        <span class="page-btn disabled">→</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="footer-note">
                <p>📋 Showing OSS (Office of Student Services) mandates as outlined in the BSU GAD Plan and Budget (GPB) 2026 | Last updated: <?php echo date('F j, Y'); ?></p>
            </div>
        </div>
    </main>
    
    <div class="watermark">Benguet State University - Gender and Development Information Management System | GAD-IMS v1.0</div>
</body>
</html>