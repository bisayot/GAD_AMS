<?php
// ar_view.php - Read-only View for Approved Accomplishment Report (No Download)
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>View Accomplishment Report | GAD-IMS</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { background-color: #f8f9fb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    .material-symbols-outlined { display: inline-block; font-size: 24px; font-family: monospace; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cfc2d544; border-radius: 10px; }
    .pl-64 { padding-left: 256px; }
    .pt-16 { padding-top: 64px; }
    .min-h-screen { min-height: 100vh; }
    .p-10 { padding: 40px; }
    .flex { display: flex; }
    .gap-8 { gap: 32px; }
    .bg-white { background-color: white; }
    .rounded-xl { border-radius: 12px; }
    .overflow-hidden { overflow: hidden; }
    .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
    .border { border: 1px solid #e2e8f0; }
    .flex-col { flex-direction: column; }
    .p-8 { padding: 32px; }
    .pb-4 { padding-bottom: 16px; }
    .border-b { border-bottom: 1px solid #e2e8f0; }
    .bg-emerald-50\/30 { background-color: rgba(209,250,229,0.3); }
    .items-center { align-items: center; }
    .gap-2 { gap: 8px; }
    .bg-3f6516\/20 { background-color: rgba(63,101,22,0.2); }
    .text-3f6516 { color: #3f6516; }
    .px-3 { padding-left: 12px; padding-right: 12px; }
    .py-1 { padding-top: 4px; padding-bottom: 4px; }
    .rounded-full { border-radius: 9999px; }
    .w-2 { width: 8px; }
    .h-2 { height: 8px; }
    .text-9px { font-size: 9px; }
    .font-black { font-weight: 900; }
    .uppercase { text-transform: uppercase; }
    .tracking-widest { letter-spacing: 0.1em; }
    .text-3b3b3b { color: #3b3b3b; }
    .font-bold { font-weight: 700; }
    .font-serif { font-family: 'Times New Roman', serif; }
    .text-28px { font-size: 28px; }
    .text-000 { color: #000; }
    .leading-tight { line-height: 1.25; }
    .mb-4 { margin-bottom: 16px; }
    .flex-wrap { flex-wrap: wrap; }
    .gap-6 { gap: 24px; }
    .pt-4 { padding-top: 16px; }
    .border-t { border-top: 1px solid #e2e8f0; }
    .border-slate-100 { border-color: #f1f5f9; }
    .flex-col { flex-direction: column; }
    .mb-1 { margin-bottom: 4px; }
    .text-11px { font-size: 11px; }
    .font-semibold { font-weight: 600; }
    .font-medium { font-weight: 500; }
    .flex-1 { flex: 1; }
    .overflow-y-auto { overflow-y: auto; }
    .space-y-6 > * + * { margin-top: 24px; }
    .bg-slate-50 { background-color: #f8fafc; }
    .p-5 { padding: 20px; }
    .grid { display: grid; }
    .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .gap-4 { gap: 16px; }
    .text-2xl { font-size: 24px; }
    .rounded-lg { border-radius: 8px; }
    .text-center { text-align: center; }
    .text-990dd1 { color: #990dd1; }
    .text-ecd224 { color: #ecd224; }
    .text-5170ff { color: #5170ff; }
    .mt-1 { margin-top: 4px; }
    .mt-2 { margin-top: 8px; }
    .mt-3 { margin-top: 12px; }
    .mb-2 { margin-bottom: 8px; }
    .justify-between { justify-content: space-between; }
    .gap-3 { gap: 12px; }
    .text-red-500 { color: #ef4444; }
    .text-blue-500 { color: #3b82f6; }
    .text-emerald-500 { color: #10b981; }
    .text-amber-500 { color: #f59e0b; }
    .list-disc { list-style-type: disc; }
    .list-inside { list-style-position: inside; }
    .space-y-1 > * + * { margin-top: 4px; }
    .ml-2 { margin-left: 8px; }
    .sticky { position: sticky; }
    .top-20 { top: 80px; }
    .self-start { align-self: flex-start; }
    .max-h-calc { max-height: calc(100vh - 96px); }
    .pb-4 { padding-bottom: 16px; }
    .w-full { width: 100%; }
    .py-3 { padding-top: 12px; padding-bottom: 12px; }
    .transition { transition-property: all; transition-timing-function: cubic-bezier(0.4,0,0.2,1); transition-duration: 150ms; }
    .justify-center { justify-content: center; }
    .block { display: block; }
    .text-center { text-align: center; }
    .relative { position: relative; }
    .absolute { position: absolute; }
    .left-0 { left: 0; }
    .top-0\.5 { top: 2px; }
    .w-4 { width: 16px; }
    .h-4 { height: 16px; }
    .pl-7 { padding-left: 28px; }
    .bg-slate-300 { background-color: #cbd5e1; }
    .bg-blue-50 { background-color: #eff6ff; }
    .border-blue-200 { border-color: #bfdbfe; }
    .text-blue-600 { color: #2563eb; }
    .text-blue-700 { color: #1d4ed8; }
    .text-10px { font-size: 10px; }
    .italic { font-style: italic; }
    .cursor-pointer { cursor: pointer; }
    .flex-062 { flex: 0.62; }
    .flex-038 { flex: 0.38; }
    .w-10 { width: 40px; }
    .h-10 { height: 40px; }.staff-header {
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
            <h2 class="header-title">Accomplishment Report View</h2>
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
<!-- <?php include 'header.php'; ?> -->

<main style="padding-left: 256px; padding-top: 64px; min-height: 100vh;">
    <div style="padding: 40px; display: flex; gap: 32px;">

        <section style="flex: 0.62; background-color: white; border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
            <div style="padding: 32px; padding-bottom: 16px; border-bottom: 1px solid #e2e8f0; background-color: rgba(209,250,229,0.3);">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px; background-color: rgba(63,101,22,0.2); color: #3f6516; padding: 4px 12px; border-radius: 9999px;">
                        <div style="width: 8px; height: 8px; border-radius: 9999px; background-color: #3f6516;"></div>
                        <span style="font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;">Completed</span>
                    </div>
                    <span style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase; letter-spacing: 0.1em;">BSU-GAD-2026-012</span>
                </div>

                <h2 style="font-family: 'Times New Roman', serif; font-size: 28px; color: #000; line-height: 1.25; margin-bottom: 16px;">
                    Lorem Ipsum Dolor Sit Amet Consectetur Adipiscing Elit
                </h2>

                <div style="display: flex; flex-wrap: wrap; gap: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
                    <div style="display: flex; flex-direction: column;"><span style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Submitted By</span><span style="font-size: 11px; font-weight: 600; color: #3f6516;">Dr. Lorem Ipsum</span></div>
                    <div style="display: flex; flex-direction: column;"><span style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Office</span><span style="font-size: 11px; font-weight: 500; color: #3b3b3b;">Lorem Ipsum Office</span></div>
                    <div style="display: flex; flex-direction: column;"><span style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Email</span><span style="font-size: 11px; font-weight: 500; color: #3b3b3b;">lorem.ipsum@bsu.edu.ph</span></div>
                    <div style="display: flex; flex-direction: column;"><span style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Date Submitted</span><span style="font-size: 11px; font-weight: 500; color: #3b3b3b;">Mar 2, 2026</span></div>
                </div>
            </div>

            <div style="flex: 1; overflow-y: auto; padding: 32px; display: flex; flex-direction: column; gap: 24px;">
                <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;"><span style="font-size: 24px;">ℹ️</span><h3 style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #990dd1;">Report Information</h3></div>
                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;"><div><label style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">Control Number</label><p style="font-size: 11px; font-weight: 700; color: #990dd1; margin-top: 4px;">BSU-GAD-2026-012</p></div><div><label style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">Activity Title</label><p style="font-size: 11px; font-weight: 500; color: #3b3b3b; margin-top: 4px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p></div><div><label style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">Date of Activity</label><p style="font-size: 11px; color: #3b3b3b; margin-top: 4px;">Feb 20-22, 2026</p></div><div><label style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">Venue</label><p style="font-size: 11px; color: #3b3b3b; margin-top: 4px;">Lorem Ipsum Convention Center</p></div></div>
                </div>

                <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;"><span style="font-size: 24px;">📊</span><h3 style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #990dd1;">Activity Metrics</h3></div>
                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px;">
                        <div style="background-color: white; border-radius: 8px; padding: 12px; text-align: center; border: 1px solid #e2e8f0;"><span style="font-size: 24px;">👥</span><p style="font-size: 24px; font-weight: 700; color: #000;">62</p><p style="font-size: 9px; color: #3b3b3b; text-transform: uppercase;">Total Attendees</p></div>
                        <div style="background-color: white; border-radius: 8px; padding: 12px; text-align: center; border: 1px solid #e2e8f0;"><span style="font-size: 24px;">⭐</span><p style="font-size: 24px; font-weight: 700; color: #000;">4.3</p><p style="font-size: 9px; color: #3b3b3b; text-transform: uppercase;">/ 5.0 Rating</p></div>
                        <div style="background-color: white; border-radius: 8px; padding: 12px; text-align: center; border: 1px solid #e2e8f0;"><span style="font-size: 24px;">♂️</span><p style="font-size: 24px; font-weight: 700; color: #000;">8</p><p style="font-size: 9px; color: #3b3b3b; text-transform: uppercase;">Male</p></div>
                        <div style="background-color: white; border-radius: 8px; padding: 12px; text-align: center; border: 1px solid #e2e8f0;"><span style="font-size: 24px;">♀️</span><p style="font-size: 24px; font-weight: 700; color: #000;">54</p><p style="font-size: 9px; color: #3b3b3b; text-transform: uppercase;">Female</p></div>
                    </div>
                </div>

                <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;"><span style="font-size: 24px;">📋</span><h3 style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #990dd1;">Evaluation Results</h3></div>
                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;"><div><label style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">HGDG Score</label><div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;"><p style="font-size: 20px; font-weight: 700; color: #3f6516;">4.3</p><p style="font-size: 10px; color: #3b3b3b;">/ 5.0</p></div></div><div><label style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">Participant Rating</label><div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;"><p style="font-size: 20px; font-weight: 700; color: #ecd224;">4.3</p><p style="font-size: 10px; color: #3b3b3b;">/ 5.0</p></div></div></div>
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e2e8f0;"><label style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">Key Feedback from Participants</label><ul style="list-style-type: disc; list-style-position: inside; font-size: 11px; color: #3b3b3b; margin-top: 8px; display: flex; flex-direction: column; gap: 4px;"><li>Lorem ipsum dolor sit amet consectetur</li><li>Sed do eiusmod tempor incididunt</li><li>Ut labore et dolore magna aliqua</li><li>Quis nostrud exercitation ullamco</li></ul></div>
                </div>

                <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;"><span style="font-size: 24px;">📎</span><h3 style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #990dd1;">Uploaded Documents</h3></div>
                    
                    <div style="margin-bottom: 16px;"><p style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Accomplishment Report (Filled Template)</p><div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background-color: white; border-radius: 8px; border: 1px solid #e2e8f0;"><div style="display: flex; align-items: center; gap: 12px;"><span style="font-size: 24px; color: #ef4444;">📄</span><div><p style="font-size: 11px; font-weight: 500; color: #000;">Lorem_Ipsum_Accomplishment_Report.pdf</p><p style="font-size: 9px; color: #3b3b3b;">2.1 MB • Uploaded Mar 2, 2026</p></div></div><button style="color: #990dd1; font-size: 11px; padding: 4px 8px; border-radius: 8px; background: none; border: none; cursor: pointer;">👁️ Preview</button></div></div>

                    <div><p style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Mode of Verification & Attachments</p><div style="display: flex; flex-direction: column; gap: 8px;"><div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background-color: white; border-radius: 8px; border: 1px solid #e2e8f0;"><div style="display: flex; align-items: center; gap: 12px;"><span style="font-size: 24px; color: #3b82f6;">📋</span><div><p style="font-size: 11px; font-weight: 500; color: #000;">Lorem_Ipsum_Attendance_Sheet.pdf</p><p style="font-size: 9px; color: #3b3b3b;">0.7 MB • Signed attendance</p></div></div><button style="color: #990dd1; font-size: 11px; background: none; border: none; cursor: pointer;">Preview</button></div><div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background-color: white; border-radius: 8px; border: 1px solid #e2e8f0;"><div style="display: flex; align-items: center; gap: 12px;"><span style="font-size: 24px; color: #10b981;">🖼️</span><div><p style="font-size: 11px; font-weight: 500; color: #000;">Lorem_Ipsum_Photos.zip</p><p style="font-size: 9px; color: #3b3b3b;">8.5 MB • 32 photos</p></div></div><button style="color: #990dd1; font-size: 11px; background: none; border: none; cursor: pointer;">Preview</button></div><div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background-color: white; border-radius: 8px; border: 1px solid #e2e8f0;"><div style="display: flex; align-items: center; gap: 12px;"><span style="font-size: 24px;">📊</span><div><p style="font-size: 11px; font-weight: 500; color: #000;">Lorem_Ipsum_Evaluation_Summary.xlsx</p><p style="font-size: 9px; color: #3b3b3b;">0.4 MB • 62 respondents</p></div></div><button style="color: #990dd1; font-size: 11px; background: none; border: none; cursor: pointer;">Preview</button></div><div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background-color: white; border-radius: 8px; border: 1px solid #e2e8f0;"><div style="display: flex; align-items: center; gap: 12px;"><span style="font-size: 24px; color: #f59e0b;">🧾</span><div><p style="font-size: 11px; font-weight: 500; color: #000;">Lorem_Ipsum_Financial_Report.pdf</p><p style="font-size: 9px; color: #3b3b3b;">0.3 MB • Signed by budget officer</p></div></div><button style="color: #990dd1; font-size: 11px; background: none; border: none; cursor: pointer;">Preview</button></div></div></div>
                </div>
            </div>
        </section>

        <section style="flex: 0.38; display: flex; flex-direction: column; gap: 20px; overflow-y: auto; padding-bottom: 16px; position: sticky; top: 80px; align-self: flex-start; max-height: calc(100vh - 96px);">
            <div style="background-color: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;"><div style="width: 40px; height: 40px; border-radius: 9999px; background-color: rgba(63,101,22,0.2); display: flex; align-items: center; justify-content: center;"><span style="font-size: 24px; color: #3f6516;">✅</span></div><div><p style="font-size: 11px; font-weight: 700; color: #3f6516;">Completed</p><p style="font-size: 9px; color: #3b3b3b;">Completed on Mar 25, 2026</p></div></div>
                <div style="display: flex; flex-direction: column; gap: 16px;"><div><label style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase; letter-spacing: 0.05em;">Director's Evaluation Rating</label><div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;"><div style="display: flex;"><span style="font-size: 24px; color: #ecd224;">⭐</span><span style="font-size: 24px; color: #ecd224;">⭐</span><span style="font-size: 24px; color: #ecd224;">⭐</span><span style="font-size: 24px; color: #ecd224;">⭐</span><span style="font-size: 24px; color: #cbd5e1;">⭐</span></div><p style="font-size: 20px; font-weight: 700; color: #3f6516;">4.0</p><p style="font-size: 10px; color: #3b3b3b;">/ 5.0</p></div></div><div><label style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase; letter-spacing: 0.05em;">Director's Remarks</label><div style="background-color: #f8fafc; padding: 12px; border-radius: 8px; margin-top: 4px;"><p style="font-size: 11px; color: #3b3b3b; font-style: italic;">"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."</p><p style="font-size: 9px; color: #3b3b3b; margin-top: 8px;">— Dr. Maria Santos, GAD Director</p></div></div><div style="border-top: 1px solid #e2e8f0; padding-top: 12px;"><label style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase; letter-spacing: 0.05em;">Assigned Reviewers</label><div style="display: flex; flex-direction: column; gap: 8px; margin-top: 8px;"><div style="display: flex; align-items: center; gap: 8px;"><span style="font-size: 14px; color: #3f6516;">✅</span><span style="font-size: 11px; color: #3b3b3b;">Maria Santos (Lead Compliance Officer)</span></div><div style="display: flex; align-items: center; gap: 8px;"><span style="font-size: 14px; color: #3f6516;">✅</span><span style="font-size: 11px; color: #3b3b3b;">James Rivera (Budget Specialist)</span></div></div></div></div>
            </div>

            <div style="background-color: white; padding: 24px; border-radius: 12px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0;"><h3 style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; margin-bottom: 16px;">Submission History</h3><div style="display: flex; flex-direction: column; gap: 16px; position: relative;"><div style="position: relative; padding-left: 28px;"><div style="position: absolute; left: 0; top: 2px; width: 16px; height: 16px; border-radius: 9999px; background-color: #990dd1;"></div><p style="font-size: 11px; font-weight: 700; color: #000;">Report Submitted</p><p style="font-size: 9px; color: #3b3b3b;">Mar 2, 2026 • 11:45 AM</p></div><div style="position: relative; padding-left: 28px;"><div style="position: absolute; left: 0; top: 2px; width: 16px; height: 16px; border-radius: 9999px; background-color: #990dd1;"></div><p style="font-size: 11px; font-weight: 500; color: #3b3b3b;">Documents Verified by Staff</p><p style="font-size: 9px; color: #3b3b3b;">Mar 5, 2026 • 02:30 PM</p></div><div style="position: relative; padding-left: 28px;"><div style="position: absolute; left: 0; top: 2px; width: 16px; height: 16px; border-radius: 9999px; background-color: #990dd1;"></div><p style="font-size: 11px; font-weight: 500; color: #3b3b3b;">Forwarded for Director Review</p><p style="font-size: 9px; color: #3b3b3b;">Mar 6, 2026 • 09:00 AM</p></div><div style="position: relative; padding-left: 28px;"><div style="position: absolute; left: 0; top: 2px; width: 16px; height: 16px; border-radius: 9999px; background-color: #3f6516;"></div><p style="font-size: 11px; font-weight: 700; color: #3f6516;">Completed</p><p style="font-size: 9px; color: #3b3b3b;">Mar 25, 2026 • 10:15 AM</p></div></div></div>

            <div style="background-color: #eff6ff; padding: 16px; border-radius: 12px; border: 1px solid #bfdbfe;"><div style="display: flex; gap: 12px;"><span style="font-size: 24px; color: #2563eb;">ℹ️</span><div><p style="font-size: 11px; font-weight: 700; color: #1d4ed8;">Archived Report</p><p style="font-size: 9px; color: #2563eb;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. This report is now part of the official records.</p></div></div></div>

            <div style="padding-top: 8px;"><a href="employee_submissions.php" style="display: block; width: 100%; padding: 12px; background-color: #f1f5f9; color: #3b3b3b; border-radius: 12px; font-weight: 700; font-size: 11px; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;"><span style="font-size: 11px;">←</span> Back to Submissions</a></div>
        </section>
    </div>
</main>
</body>
</html>