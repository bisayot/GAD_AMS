<?php
// twg/submitted_list.php - Combined Submitted List for TWG (Pending & Revision Only)
require_once '../auth.php';
require_once 'includes/config.php';
require_role(['college', 'gad_staff']);
$page_title = 'Submitted List';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Submitted List | GAD-IMS</title>
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
        
        /* Flexbox */
        .flex { display: flex; }
        .flex-wrap { flex-wrap: wrap; }
        .items-center { align-items: center; }
        .items-end { align-items: flex-end; }
        .justify-between { justify-content: space-between; }
        .gap-1 { gap: 0.25rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .gap-4 { gap: 1rem; }
        
        /* Grid */
        .grid { display: grid; }
        .grid-cols-1 { grid-template-columns: repeat(1, 1fr); }
        .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }
        .gap-4 { gap: 1rem; }
        
        /* Colors */
        .bg-white { background-color: #ffffff; }
        .text-\[\#1a1a2e\] { color: #1a1a2e; }
        .text-\[\#6c757d\] { color: #6c757d; }
        .text-\[\#990dd1\] { color: #990dd1; }
        .text-white { color: #ffffff; }
        
        .text-2xl { font-size: 1.5rem; }
        .text-xl { font-size: 1.25rem; }
        .text-\[14px\] { font-size: 14px; }
        .text-\[13px\] { font-size: 13px; }
        .text-\[12px\] { font-size: 12px; }
        .text-\[11px\] { font-size: 11px; }
        .text-\[10px\] { font-size: 10px; }
        
        .font-bold { font-weight: 700; }
        .font-semibold { font-weight: 600; }
        .font-medium { font-weight: 500; }
        .font-black { font-weight: 900; }
        .font-mono { font-family: 'SF Mono', Monaco, monospace; }
        
        .uppercase { text-transform: uppercase; }
        .tracking-wider { letter-spacing: 0.05em; }
        
        /* Borders */
        .border { border: 1px solid #e9ecef; }
        .border-b { border-bottom: 1px solid #e9ecef; }
        .border-t { border-top: 1px solid #e9ecef; }
        .rounded-xl { border-radius: 1rem; }
        .rounded-lg { border-radius: 0.75rem; }
        .rounded-full { border-radius: 9999px; }
        
        .shadow-sm { box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); }
        .shadow-md { box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06); }
        
        /* Status Badges */
        .status-pending { background: #fff8e1; color: #f57c00; }
        .status-revision { background: #ffebee; color: #c62828; }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.8rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        
        /* Type Badges - No Icons */
        .type-design { background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%); color: #6b21e5; }
        .type-report { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #059669; }
        .type-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.8rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        
        /* Form Badges */
        .form-gad { background: #f3e8ff; color: #6b21e5; }
        .form-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.6rem;
            border-radius: 30px;
            font-size: 0.65rem;
            font-weight: 600;
        }
        
        /* Tabs */
        .tab-active {
            border-bottom: 3px solid #990dd1;
            color: #990dd1;
            background: linear-gradient(135deg, rgba(153, 13, 209, 0.05) 0%, rgba(185, 121, 204, 0.08) 100%);
        }
        .tab-inactive {
            border-bottom: 3px solid transparent;
            color: #6c757d;
        }
        .tab-inactive:hover {
            border-bottom: 3px solid #b979cc;
            color: #990dd1;
            background: rgba(185, 121, 204, 0.04);
        }
        .tab-btn {
            transition: all 0.25s ease;
            border-radius: 12px 12px 0 0;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Table */
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
        .clickable-row {
            cursor: pointer;
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f2f5;
        }
        .clickable-row:hover {
            background: linear-gradient(135deg, rgba(185, 121, 204, 0.04) 0%, rgba(153, 13, 209, 0.02) 100%);
        }
        .overflow-x-auto { overflow-x: auto; }
        .w-full { width: 100%; }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        
        .px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        
        /* Filter Card */
        .filter-card {
            background: white;
            border-radius: 1.25rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(153, 13, 209, 0.08);
        }
        .filter-inline { display: flex; flex-wrap: wrap; align-items: flex-end; gap: 1rem; }
        .filter-item { flex: 1; min-width: 140px; }
        .filter-search { flex: 2; min-width: 200px; }
        .filter-actions { display: flex; gap: 0.5rem; }
        
        select, input {
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
        }
        select:focus, input:focus {
            outline: none;
            border-color: #990dd1;
            box-shadow: 0 0 0 3px rgba(153, 13, 209, 0.1);
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(153, 13, 209, 0.3);
        }
        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            padding: 0.5rem 1.25rem;
            border-radius: 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-secondary:hover { background: #e2e8f0; }
        
        /* Pagination */
        .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.4rem 0.9rem;
            border: 1px solid #e9ecef;
            border-radius: 0.6rem;
            background: white;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            color: #495057;
            transition: all 0.2s ease;
        }
        .page-btn:hover:not(:disabled) {
            background-color: #f8f9fa;
            border-color: #990dd1;
            color: #990dd1;
        }
        .page-btn.active {
            background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
            color: white;
            border-color: #990dd1;
        }
        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Stats Cards - Archive Style */
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1rem;
            border: 1px solid rgba(153, 13, 209, 0.08);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(153, 13, 209, 0.12);
        }
        .stat-card.purple {
            background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
            color: white;
        }
        .stat-card.purple .stat-label,
        .stat-card.purple .stat-sub { color: rgba(255, 255, 255, 0.8); }
        .stat-card.purple .stat-number { color: white; }
        .stat-icon {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            font-size: 2rem;
            opacity: 0.15;
        }
        .stat-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: #1a1a2e;
            line-height: 1;
        }
        .stat-card.purple .stat-number { color: white; }
        .stat-sub {
            font-size: 0.65rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }
        .stat-card.purple .stat-sub { color: rgba(255, 255, 255, 0.7); }
        
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
        
        .relative { position: relative; }
        .absolute { position: absolute; }
        .top-1\/2 { top: 50%; }
        .left-3 { left: 0.75rem; }
        .transform { transform: translateY(-50%); }
        .pl-9 { padding-left: 2.25rem; }
        .text-decoration-none { text-decoration: none; }
        .cursor-pointer { cursor: pointer; }
        
        @media (max-width: 768px) {
            .ml-64 { margin-left: 0; }
            .staff-header { left: 0; padding: 0 1rem; }
            .p-8 { padding: 1rem; }
            .grid-cols-4 { grid-template-columns: repeat(2, 1fr); }
            .tab-btn { padding: 0.5rem 1rem; font-size: 0.75rem; }
            .filter-inline { flex-direction: column; align-items: stretch; }
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
            <h2 class="header-title">TWG Submitted List</h2>
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

    <?php include 'includes/sidebar_twg.php'; ?>

    <main class="ml-64 min-h-screen">
        <div class="p-8">
            
            <!-- Stats Summary Row - Archive Style Cards -->
            <div class="grid grid-cols-4 gap-4 mb-8">
                <div class="stat-card purple">
                    <div class="stat-icon">📋</div>
                    <div class="stat-label">Total Active</div>
                    <div class="stat-number">5</div>
                    <div class="stat-sub">Pending & Revision</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📄</div>
                    <div class="stat-label">Activity Designs</div>
                    <div class="stat-number">3</div>
                    <div class="stat-sub">Total designs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-label">Accomplishment Reports</div>
                    <div class="stat-number">2</div>
                    <div class="stat-sub">Total reports</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-label">Pending Review</div>
                    <div class="stat-number">2</div>
                    <div class="stat-sub">Awaiting approval</div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mb-6 border-b border-slate-200">
                <div class="flex gap-2">
                    <a href="?tab=designs" class="tab-btn <?php echo (!isset($_GET['tab']) || $_GET['tab'] === 'designs') ? 'tab-active' : 'tab-inactive'; ?>">
                        Activity Designs
                        <span class="ml-2 text-[10px] bg-gradient-to-r from-[#990dd1]/20 to-[#b979cc]/20 text-[#990dd1] px-2 py-0.5 rounded-full">3</span>
                    </a>
                    <a href="?tab=reports" class="tab-btn <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'reports') ? 'tab-active' : 'tab-inactive'; ?>">
                        Accomplishment Reports
                        <span class="ml-2 text-[10px] bg-gradient-to-r from-[#990dd1]/20 to-[#b979cc]/20 text-[#990dd1] px-2 py-0.5 rounded-full">2</span>
                    </a>
                </div>
            </div>

            <!-- Filter Bar -->
            <form method="GET" action="" class="filter-card">
                <input type="hidden" name="tab" value="<?php echo isset($_GET['tab']) ? htmlspecialchars($_GET['tab']) : 'designs'; ?>">
                <div class="filter-inline">
                    <div class="filter-item">
                        <label class="block text-[10px] font-bold text-[#6c757d] uppercase tracking-wider mb-1.5">Status</label>
                        <select name="status" class="w-full border rounded-xl px-3 py-2 text-[13px] bg-white">
                            <option value="all">All Status</option>
                            <option value="pending" <?php echo (isset($_GET['status']) && $_GET['status'] === 'pending') ? 'selected' : ''; ?>>Pending Review</option>
                            <option value="revision" <?php echo (isset($_GET['status']) && $_GET['status'] === 'revision') ? 'selected' : ''; ?>>For Revision</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label class="block text-[10px] font-bold text-[#6c757d] uppercase tracking-wider mb-1.5">Sort By</label>
                        <select name="sort" class="w-full border rounded-xl px-3 py-2 text-[13px] bg-white">
                            <option value="date_desc" <?php echo (!isset($_GET['sort']) || $_GET['sort'] === 'date_desc') ? 'selected' : ''; ?>>Newest First</option>
                            <option value="date_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'date_asc') ? 'selected' : ''; ?>>Oldest First</option>
                            <option value="control_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'control_asc') ? 'selected' : ''; ?>>Control A-Z</option>
                            <option value="control_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'control_desc') ? 'selected' : ''; ?>>Control Z-A</option>
                        </select>
                    </div>

                    <div class="filter-search">
                        <label class="block text-[10px] font-bold text-[#6c757d] uppercase tracking-wider mb-1.5">Search</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-[#adb5bd]">🔍</span>
                            <input type="text" name="search" placeholder="Search by title or control number..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" class="w-full border rounded-xl pl-9 pr-3 py-2 text-[13px] bg-white">
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-primary">Apply Filters</button>
                        <a href="?tab=<?php echo isset($_GET['tab']) ? htmlspecialchars($_GET['tab']) : 'designs'; ?>" class="btn-secondary">Clear</a>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-4 pt-3 border-t border-dashed border-slate-200">
                    <div class="text-[12px] text-[#6c757d]">
                        <span class="font-semibold text-[#990dd1]"><?php echo $totalItems ?? 0; ?></span> record(s) found
                    </div>
                </div>
            </form>

            <!-- Results Table - No Icons, Clickable Rows -->
            <div class="data-table">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="table-header-row">
                                <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#6c757d]">Type</th>
                                <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#6c757d]">Control Number</th>
                                <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#6c757d]">Activity Title</th>
                                <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#6c757d]">Form Type</th>
                                <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#6c757d]">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Activity Designs Data
                            $activityDesigns = [
                                ['id' => 4, 'title' => "Lorem ipsum dolor sit amet", 'control' => "OSS-2025-004", 'date' => "April 05, 2025", 'dateRaw' => "2025-04-05", 'status' => "revision", 'statusText' => "For Revision", 'statusClass' => "status-revision", 'form' => "gad", 'formLabel' => "GAD Office", 'formClass' => "form-gad", 'type' => "design", 'link' => "edit_design.php?id=4"],
                                ['id' => 3, 'title' => "Lorem ipsum dolor sit amet", 'control' => "OSS-2025-003", 'date' => "March 24, 2025", 'dateRaw' => "2025-03-24", 'status' => "pending", 'statusText' => "Pending Review", 'statusClass' => "status-pending", 'form' => "gad", 'formLabel' => "GAD Office", 'formClass' => "form-gad", 'type' => "design", 'link' => "design_view.php?id=3"],
                                ['id' => 5, 'title' => "Lorem ipsum dolor sit amet", 'control' => "OSS-2025-005", 'date' => "March 10, 2025", 'dateRaw' => "2025-03-10", 'status' => "pending", 'statusText' => "Pending Review", 'statusClass' => "status-pending", 'form' => "employee", 'formLabel' => "Employee Training", 'formClass' => "form-gad", 'type' => "design", 'link' => "design_view.php?id=5"]
                            ];

                            // Accomplishment Reports Data
                            $accomplishmentReports = [
                                ['id' => 4, 'title' => "Lorem ipsum dolor sit amet - Accomplishment Report", 'control' => "ACC-OSS-2025-004", 'date' => "April 15, 2025", 'dateRaw' => "2025-04-15", 'status' => "revision", 'statusText' => "For Revision", 'statusClass' => "status-revision", 'form' => "gad", 'formLabel' => "GAD Office", 'formClass' => "form-gad", 'type' => "report", 'link' => "edit_accomplishment.php?id=4"],
                                ['id' => 3, 'title' => "Lorem ipsum dolor sit amet - Accomplishment Report", 'control' => "ACC-OSS-2025-003", 'date' => "April 10, 2025", 'dateRaw' => "2025-04-10", 'status' => "pending", 'statusText' => "Pending Review", 'statusClass' => "status-pending", 'form' => "gad", 'formLabel' => "GAD Office", 'formClass' => "form-gad", 'type' => "report", 'link' => "ar_view.php?id=3"]
                            ];

                            $currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'designs';
                            $statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
                            $searchFilter = isset($_GET['search']) ? trim($_GET['search']) : '';
                            $sortFilter = isset($_GET['sort']) ? $_GET['sort'] : 'date_desc';
                            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $itemsPerPage = 10;

                            $sourceData = ($currentTab === 'designs') ? $activityDesigns : $accomplishmentReports;

                            // Apply filters
                            $filteredData = array_filter($sourceData, function($item) use ($statusFilter, $searchFilter) {
                                if ($statusFilter !== 'all' && $item['status'] !== $statusFilter) return false;
                                if (!empty($searchFilter)) {
                                    if (stripos($item['title'], $searchFilter) === false && stripos($item['control'], $searchFilter) === false) return false;
                                }
                                return true;
                            });

                            // Apply sorting
                            usort($filteredData, function($a, $b) use ($sortFilter) {
                                if ($sortFilter === 'control_asc') return strcmp($a['control'], $b['control']);
                                if ($sortFilter === 'control_desc') return strcmp($b['control'], $a['control']);
                                if ($sortFilter === 'date_asc') return strtotime($a['dateRaw']) - strtotime($b['dateRaw']);
                                return strtotime($b['dateRaw']) - strtotime($a['dateRaw']);
                            });

                            $totalItems = count($filteredData);
                            $totalPages = ceil($totalItems / $itemsPerPage);
                            if ($currentPage < 1) $currentPage = 1;
                            if ($currentPage > $totalPages && $totalPages > 0) $currentPage = $totalPages;
                            $startIndex = ($currentPage - 1) * $itemsPerPage;
                            $paginatedItems = array_slice($filteredData, $startIndex, $itemsPerPage);

                            if (count($paginatedItems) === 0): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="text-4xl opacity-30">📭</span>
                                            <p class="text-[#6c757d] text-[13px]">No records found matching your criteria</p>
                                            <a href="?tab=<?php echo $currentTab; ?>" class="btn-secondary mt-2" style="text-decoration: none;">Clear Filters</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: foreach ($paginatedItems as $item): 
                                $typeBadgeClass = ($item['type'] === 'design') ? 'type-design' : 'type-report';
                                $typeText = ($item['type'] === 'design') ? 'Activity Design' : 'Accomplishment Report';
                            ?>
                                <tr class="clickable-row" onclick="window.location.href='<?php echo $item['link']; ?>'">
                                    <td class="px-6 py-4">
                                        <span class="type-badge <?php echo $typeBadgeClass; ?>">
                                            <?php echo $typeText; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-mono text-[13px] font-bold text-[#990dd1] tracking-wide"><?php echo htmlspecialchars($item['control']); ?></div>
                                        <div class="text-[10px] text-[#6c757d] mt-1"><?php echo htmlspecialchars($item['date']); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-[#1a1a2e] text-[14px] leading-relaxed"><?php echo htmlspecialchars($item['title']); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="form-badge <?php echo $item['formClass']; ?>">
                                            <?php echo $item['formLabel']; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge <?php echo $item['statusClass']; ?>">
                                            <?php echo $item['statusText']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-100 bg-gradient-to-r from-slate-50/50 to-white flex justify-between items-center flex-wrap gap-3">
                    <p class="text-[12px] text-[#6c757d]">
                        Showing <span class="font-semibold text-[#990dd1]"><?php echo $totalItems > 0 ? $startIndex + 1 : 0; ?></span> - 
                        <span class="font-semibold text-[#990dd1]"><?php echo min($startIndex + $itemsPerPage, $totalItems); ?></span> 
                        of <span class="font-semibold"><?php echo $totalItems; ?></span> records
                    </p>
                    <div class="flex gap-1.5">
                        <?php if ($currentPage > 1): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $currentPage - 1])); ?>" class="page-btn">← Prev</a>
                        <?php else: ?>
                            <span class="page-btn" style="opacity:0.4; cursor:not-allowed;">← Prev</span>
                        <?php endif; ?>
                        
                        <?php
                        $maxVisible = 5;
                        $startPage = max(1, $currentPage - floor($maxVisible / 2));
                        $endPage = min($totalPages, $startPage + $maxVisible - 1);
                        if ($endPage - $startPage + 1 < $maxVisible) $startPage = max(1, $endPage - $maxVisible + 1);
                        
                        if ($startPage > 1): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>" class="page-btn">1</a>
                            <?php if ($startPage > 2): ?><span class="page-btn" style="opacity:0.5; cursor:default;">...</span><?php endif; ?>
                        <?php endif;
                        
                        for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <?php if ($i == $currentPage): ?>
                                <span class="page-btn active"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" class="page-btn"><?php echo $i; ?></a>
                            <?php endif;
                        endfor;
                        
                        if ($endPage < $totalPages): 
                            if ($endPage < $totalPages - 1): ?><span class="page-btn" style="opacity:0.5; cursor:default;">...</span><?php endif; ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $totalPages])); ?>" class="page-btn"><?php echo $totalPages; ?></a>
                        <?php endif; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $currentPage + 1])); ?>" class="page-btn">Next →</a>
                        <?php else: ?>
                            <span class="page-btn" style="opacity:0.4; cursor:not-allowed;">Next →</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <div class="watermark">Benguet State University - Gender and Development Information Management System | GAD-IMS v1.0</div>
</body>
</html>