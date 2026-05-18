<?php
// staff/archive_list.php - Archived Records with Clickable Rows
require_once '../auth.php';
require_role(['gad_staff', 'admin']);
$page_title = 'Archive | Approved Records';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Archive | Approved Records | GAD-IMS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f0f2f8; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        
        /* Layout */
        .main-content { margin-left: 260px; margin-top: 70px; padding: 32px; min-height: 100vh; }
        .container { max-width: 1400px; margin: 0 auto; }
        
        /* Typography */
        .page-title { font-size: 28px; font-weight: 700; color: #1a1a2e; letter-spacing: -0.5px; display: flex; align-items: center; gap: 12px; }
        .section-desc { color: #666; font-size: 13px; margin-top: 6px; }
        
        /* Tabs */
        .tabs { display: flex; gap: 24px; border-bottom: 2px solid #eef2f6; margin-bottom: 24px; }
        .tab { padding: 12px 4px; font-size: 14px; font-weight: 600; color: #666; text-decoration: none; transition: all 0.2s; border-bottom: 2px solid transparent; margin-bottom: -2px; }
        .tab:hover { color: #6b21e5; }
        .tab.active { color: #6b21e5; border-bottom-color: #6b21e5; }
        .tab-count { background: #f0f2f5; padding: 2px 8px; border-radius: 20px; font-size: 11px; margin-left: 8px; }
        
        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; margin-bottom: 32px; }
        .stat-card { background: white; border-radius: 20px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.05); }
        .stat-card.purple { background: linear-gradient(135deg, #6b21e5 0%, #8b3fdf 100%); color: white; }
        .stat-card.purple .stat-label, .stat-card.purple .stat-sub { color: rgba(255,255,255,0.8); }
        .stat-label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #888; margin-bottom: 8px; }
        .stat-number { font-size: 42px; font-weight: 800; color: #1a1a2e; line-height: 1; }
        .stat-card.purple .stat-number { color: white; }
        .stat-sub { font-size: 11px; color: #999; margin-top: 8px; }
        .stat-icon { float: right; font-size: 48px; opacity: 0.3; }
        
        /* Filter Bar */
        .filter-bar { background: white; border-radius: 16px; padding: 20px 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #eef2f6; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; }
        .filter-group { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .filter-label { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #666; letter-spacing: 0.5px; }
        .filter-select, .filter-input { padding: 8px 16px; border-radius: 10px; border: 1px solid #e0e4e8; background: #f8f9fc; font-size: 12px; font-family: inherit; }
        .search-box { position: relative; }
        .search-box input { padding: 8px 16px 8px 36px; width: 260px; }
        .search-box span { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 14px; opacity: 0.6; }
        
        /* Buttons */
        .btn { padding: 8px 20px; border-radius: 10px; font-size: 12px; font-weight: 500; cursor: pointer; border: none; font-family: inherit; transition: all 0.2s; }
        .btn-primary { background: #6b21e5; color: white; }
        .btn-primary:hover { background: #5a1bc9; }
        .btn-secondary { background: #f0f2f5; color: #444; border: 1px solid #e0e4e8; }
        .btn-secondary:hover { background: #e6e9ef; }
        
        /* Table */
        .table-wrapper { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #eef2f6; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 16px 20px; background: #fafbfd; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #666; border-bottom: 1px solid #eef2f6; }
        td { padding: 16px 20px; font-size: 13px; border-bottom: 1px solid #f0f2f5; color: #333; }
        tr:last-child td { border-bottom: none; }
        .clickable-row { cursor: pointer; transition: all 0.2s ease; }
        .clickable-row:hover { background: #faf8ff; }
        
        /* Badges */
        .badge { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 4px 12px; border-radius: 30px; font-size: 11px; font-weight: 600; }
        .badge-purple { background: #f0e6ff; color: #6b21e5; }
        .badge-green { background: #e6f7e6; color: #2e7d32; }
        .badge-yellow { background: #fef3c7; color: #d97706; }
        .badge-red { background: #fee2e2; color: #dc2626; }
        .badge-gray { background: #f0f2f5; color: #888; }
        
        .form-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 30px; font-size: 10px; font-weight: 600; }
        .form-badge-employee { background: #e0e7ff; color: #3730a3; }
        .form-badge-inset { background: #fef3c7; color: #b45309; }
        .form-badge-extension { background: #d1fae5; color: #065f46; }
        .form-badge-gad { background: #f3e8ff; color: #6b21e5; }
        
        .type-badge-design { background: #f3e8ff; color: #6b21e5; }
        .type-badge-accomplishment { background: #e6f7e6; color: #2e7d32; }
        
        /* Item Name */
        .item-text { font-weight: 600; color: #1a1a2e; }
        
        /* Pagination */
        .pagination { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; background: white; border-top: 1px solid #eef2f6; }
        .pagination-info { font-size: 12px; color: #888; }
        .pagination-buttons { display: flex; gap: 6px; }
        .page-btn { width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; border-radius: 10px; border: 1px solid #e0e4e8; background: white; font-size: 12px; cursor: pointer; transition: all 0.2s; text-decoration: none; color: #333; }
        .page-btn:hover { background: #f0e6ff; border-color: #6b21e5; }
        .page-btn.active { background: #6b21e5; border-color: #6b21e5; color: white; }
        .page-btn.disabled { opacity: 0.5; cursor: not-allowed; }
        
        /* Header Icon */
        .header-icon { width: 48px; height: 48px; background: linear-gradient(135deg, #6b21e5 0%, #8b3fdf 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; }
        
        @media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) { .main-content { margin-left: 0; padding: 20px; } .stats-grid { grid-template-columns: 1fr; } .filter-bar { flex-direction: column; align-items: stretch; } .search-box input { width: 100%; } th, td { padding: 12px 16px; } }
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
            <h2 class="header-title">Archive</h2>
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
            <!-- Tabs -->
            <div class="tabs">
                <a href="?tab=designs" class="tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'designs') ? 'active' : ''; ?>">
                    Activity Designs <span class="tab-count">8</span>
                </a>
                <a href="?tab=reports" class="tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'reports') ? 'active' : ''; ?>">
                    Accomplishment Reports <span class="tab-count">7</span>
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card purple">
                    <div class="stat-icon">📦</div>
                    <div class="stat-label">Total Archived</div>
                    <div class="stat-number">15</div>
                    <div class="stat-sub">Records in archive</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-label">Approved</div>
                    <div class="stat-number">11</div>
                    <div class="stat-sub">Approved records</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">❌</div>
                    <div class="stat-label">Cancelled</div>
                    <div class="stat-number">4</div>
                    <div class="stat-sub">Cancelled records</div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="filter-group">
                    <span class="filter-label">📋 Filter:</span>
                    <select class="filter-select">
                        <option>All Types</option>
                        <option>Employee Training</option>
                        <option>INSET</option>
                        <option>Extension</option>
                        <option>GAD Office</option>
                    </select>
                    <select class="filter-select">
                        <option>Sort: Newest First</option>
                        <option>Sort: Oldest First</option>
                        <option>Control: A-Z</option>
                        <option>Control: Z-A</option>
                    </select>
                </div>
                <div class="filter-group">
                    <div class="search-box">
                        <span>🔍</span>
                        <input type="text" placeholder="Search by title or control #...">
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
                            <th>Type</th>
                            <th>Control Number</th>
                            <th>Activity Title</th>
                            <th>Office</th>
                            <th>Form Type</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'designs';
                        
                        if ($tab == 'designs'):
                            $records = [
                                ['type' => 'Activity Design', 'typeClass' => 'type-badge-design', 'control' => 'GAD-2026-001', 'title' => 'Gender Orientation for Students', 'office' => 'GAD Office', 'form' => 'gad', 'formLabel' => 'GAD Office', 'formClass' => 'form-badge-gad', 'date' => 'Jan 15, 2026', 'status' => 'Approved', 'statusClass' => 'badge-green', 'link' => 'design_view.php?id=1'],
                                ['type' => 'Activity Design', 'typeClass' => 'type-badge-design', 'control' => 'EMP-2026-001', 'title' => 'Quis nostrud exercitation ullamco', 'office' => 'HR Management Office', 'form' => 'employee', 'formLabel' => 'Employee Training', 'formClass' => 'form-badge-employee', 'date' => 'Jan 20, 2026', 'status' => 'Approved', 'statusClass' => 'badge-green', 'link' => 'design_view.php?id=4'],
                                ['type' => 'Activity Design', 'typeClass' => 'type-badge-design', 'control' => 'INSET-2026-001', 'title' => 'Fugiat nulla pariatur excepteur', 'office' => 'College of Education', 'form' => 'inset', 'formLabel' => 'INSET', 'formClass' => 'form-badge-inset', 'date' => 'Jan 25, 2026', 'status' => 'Approved', 'statusClass' => 'badge-green', 'link' => 'design_view.php?id=8'],
                                ['type' => 'Activity Design', 'typeClass' => 'type-badge-design', 'control' => 'EXT-2026-001', 'title' => 'Lorem ipsum dolor sit amet adipiscing', 'office' => 'Extension Office', 'form' => 'extension', 'formLabel' => 'Extension', 'formClass' => 'form-badge-extension', 'date' => 'Jan 10, 2026', 'status' => 'Approved', 'statusClass' => 'badge-green', 'link' => 'design_view.php?id=12'],
                                ['type' => 'Activity Design', 'typeClass' => 'type-badge-design', 'control' => 'EMP-2025-045', 'title' => 'Voluptate velit esse cillum dolore', 'office' => 'IT Services Division', 'form' => 'employee', 'formLabel' => 'Employee Training', 'formClass' => 'form-badge-employee', 'date' => 'Oct 12, 2025', 'status' => 'Approved', 'statusClass' => 'badge-green', 'link' => 'design_view.php?id=7'],
                                ['type' => 'Activity Design', 'typeClass' => 'type-badge-design', 'control' => 'INSET-2025-089', 'title' => 'Mollit anim id est laborum', 'office' => 'Research Office', 'form' => 'inset', 'formLabel' => 'INSET', 'formClass' => 'form-badge-inset', 'date' => 'Sep 15, 2025', 'status' => 'Approved', 'statusClass' => 'badge-green', 'link' => 'design_view.php?id=11'],
                                ['type' => 'Activity Design', 'typeClass' => 'type-badge-design', 'control' => 'EXT-2025-042', 'title' => 'Nostrud exercitation ullamco laboris', 'office' => 'College of Social Work', 'form' => 'extension', 'formLabel' => 'Extension', 'formClass' => 'form-badge-extension', 'date' => 'Nov 20, 2025', 'status' => 'Approved', 'statusClass' => 'badge-green', 'link' => 'design_view.php?id=15'],
                                ['type' => 'Activity Design', 'typeClass' => 'type-badge-design', 'control' => 'GAD-2025-001', 'title' => 'Cancelled Activity Design Example', 'office' => 'GAD Office', 'form' => 'gad', 'formLabel' => 'GAD Office', 'formClass' => 'form-badge-gad', 'date' => 'Dec 10, 2025', 'status' => 'Cancelled', 'statusClass' => 'badge-gray', 'link' => 'design_view.php?id=19'],
                            ];
                        else:
                            $records = [
                                ['type' => 'Accomplishment Report', 'typeClass' => 'type-badge-accomplishment', 'control' => 'GAD-2026-001', 'title' => 'Lorem ipsum dolor sit amet consectetur', 'office' => 'GAD Office', 'form' => 'gad', 'formLabel' => 'GAD Office', 'formClass' => 'form-badge-gad', 'date' => 'Jan 15, 2026', 'status' => 'Completed', 'statusClass' => 'badge-green', 'link' => 'ar_view.php?id=1'],
                                ['type' => 'Accomplishment Report', 'typeClass' => 'type-badge-accomplishment', 'control' => 'EMP-2026-001', 'title' => 'Quis nostrud exercitation ullamco', 'office' => 'HR Management Office', 'form' => 'employee', 'formLabel' => 'Employee Training', 'formClass' => 'form-badge-employee', 'date' => 'Jan 20, 2026', 'status' => 'Completed', 'statusClass' => 'badge-green', 'link' => 'ar_view.php?id=4'],
                                ['type' => 'Accomplishment Report', 'typeClass' => 'type-badge-accomplishment', 'control' => 'INSET-2026-001', 'title' => 'Fugiat nulla pariatur excepteur', 'office' => 'College of Education', 'form' => 'inset', 'formLabel' => 'INSET', 'formClass' => 'form-badge-inset', 'date' => 'Jan 25, 2026', 'status' => 'Completed', 'statusClass' => 'badge-green', 'link' => 'ar_view.php?id=8'],
                                ['type' => 'Accomplishment Report', 'typeClass' => 'type-badge-accomplishment', 'control' => 'EXT-2026-001', 'title' => 'Lorem ipsum dolor sit amet adipiscing', 'office' => 'Extension Office', 'form' => 'extension', 'formLabel' => 'Extension', 'formClass' => 'form-badge-extension', 'date' => 'Jan 10, 2026', 'status' => 'Completed', 'statusClass' => 'badge-green', 'link' => 'ar_view.php?id=12'],
                                ['type' => 'Accomplishment Report', 'typeClass' => 'type-badge-accomplishment', 'control' => 'EMP-2025-045', 'title' => 'Voluptate velit esse cillum dolore', 'office' => 'IT Services Division', 'form' => 'employee', 'formLabel' => 'Employee Training', 'formClass' => 'form-badge-employee', 'date' => 'Oct 12, 2025', 'status' => 'Approved', 'statusClass' => 'badge-purple', 'link' => 'ar_view.php?id=7'],
                                ['type' => 'Accomplishment Report', 'typeClass' => 'type-badge-accomplishment', 'control' => 'INSET-2025-089', 'title' => 'Mollit anim id est laborum', 'office' => 'Research Office', 'form' => 'inset', 'formLabel' => 'INSET', 'formClass' => 'form-badge-inset', 'date' => 'Sep 15, 2025', 'status' => 'Approved', 'statusClass' => 'badge-purple', 'link' => 'ar_view.php?id=11'],
                                ['type' => 'Accomplishment Report', 'typeClass' => 'type-badge-accomplishment', 'control' => 'GAD-2026-010', 'title' => 'Cancelled Accomplishment Report', 'office' => 'GAD Office', 'form' => 'gad', 'formLabel' => 'GAD Office', 'formClass' => 'form-badge-gad', 'date' => 'Feb 28, 2026', 'status' => 'Cancelled', 'statusClass' => 'badge-gray', 'link' => 'ar_view.php?id=20'],
                            ];
                        endif;
                        
                        foreach ($records as $item):
                        ?>
                        <tr class="clickable-row" onclick="window.location.href='<?php echo $item['link']; ?>'">
                            <td><span class="badge <?php echo $item['typeClass']; ?>"><?php echo $item['type']; ?></span></td>
                            <td><span style="font-family: monospace; font-weight: 600; color: #6b21e5;"><?php echo $item['control']; ?></span></td>
                            <td><span class="item-text"><?php echo htmlspecialchars($item['title']); ?></span></td>
                            <td><?php echo htmlspecialchars($item['office']); ?></td>
                            <td><span class="form-badge <?php echo $item['formClass']; ?>"><?php echo $item['formLabel']; ?></span></td>
                            <td><?php echo $item['date']; ?></td>
                            <td><span class="badge <?php echo $item['statusClass']; ?>"><?php echo $item['status']; ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    <div class="pagination-info">Showing <strong>1</strong> - <strong><?php echo count($records); ?></strong> of <strong><?php echo count($records); ?></strong> records</div>
                    <div class="pagination-buttons">
                        <button class="page-btn disabled">←</button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn disabled">→</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>