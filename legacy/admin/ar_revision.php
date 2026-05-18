<?php
// ar_revision.php - Request Revision for Accomplishment Report (with read-only preview)
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Request Revision | Accomplishment Report | GAD-IMS</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { background-color: #f8f9fb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
    .material-symbols-outlined { display: inline-block; font-size: 24px; font-family: monospace; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cfc2d544; border-radius: 10px; }
    .read-only-dim { opacity: 0.65; }
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
    .bg-slate-50 { background-color: #f8fafc; }
    .items-center { align-items: center; }
    .gap-2 { gap: 8px; }
    .bg-slate-200 { background-color: #e2e8f0; }
    .text-slate-500 { color: #64748b; }
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
    .leading-tight { line-height: 1.25; }
    .mb-4 { margin-bottom: 16px; }
    .flex-wrap { flex-wrap: wrap; }
    .gap-6 { gap: 24px; }
    .pt-4 { padding-top: 16px; }
    .border-t { border-top: 1px solid #e2e8f0; }
    .border-slate-200 { border-color: #e2e8f0; }
    .text-11px { font-size: 11px; }
    .flex-1 { flex: 1; }
    .overflow-y-auto { overflow-y: auto; }
    .space-y-6 > * + * { margin-top: 24px; }
    .grid { display: grid; }
    .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .gap-4 { gap: 16px; }
    .text-xl { font-size: 20px; }
    .text-990dd1 { color: #990dd1; }
    .bg-white { background-color: white; }
    .rounded-lg { border-radius: 8px; }
    .text-center { text-align: center; }
    .mt-1 { margin-top: 4px; }
    .mb-2 { margin-bottom: 8px; }
    .justify-between { justify-content: space-between; }
    .gap-3 { gap: 12px; }
    .text-red-500 { color: #ef4444; }
    .text-blue-500 { color: #3b82f6; }
    .text-emerald-500 { color: #10b981; }
    .text-amber-500 { color: #f59e0b; }
    .sticky { position: sticky; }
    .top-20 { top: 80px; }
    .self-start { align-self: flex-start; }
    .max-h-calc { max-height: calc(100vh - 96px); }
    .pb-4 { padding-bottom: 16px; }
    .w-full { width: 100%; }
    .py-3 { padding-top: 12px; padding-bottom: 12px; }
    .bg-ecd224 { background-color: #ecd224; }
    .text-000 { color: #000; }
    .transition { transition-property: all; transition-timing-function: cubic-bezier(0.4,0,0.2,1); transition-duration: 150ms; }
    .justify-center { justify-content: center; }
    .block { display: block; }
    .text-center { text-align: center; }
    .bg-red-100 { background-color: #fee2e2; }
    .text-red-700 { color: #b91c1c; }
    .text-10px { font-size: 10px; }
    .rounded-xl { border-radius: 12px; }
    .p-6 { padding: 24px; }
    .mb-3 { margin-bottom: 12px; }
    .mb-1 { margin-bottom: 4px; }
    .space-y-5 > * + * { margin-top: 20px; }
    .cursor-pointer { cursor: pointer; }
    .flex-062 { flex: 0.62; }
    .flex-038 { flex: 0.38; }.staff-header {
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
            <h2 class="header-title">Accomplishment Report Revision</h2>
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

<main style="padding-left: 256px; padding-top: 64px; min-height: 100vh;">
    <div style="padding: 40px; display: flex; gap: 32px;">

        <section style="flex: 0.62; background-color: white; border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0; opacity: 0.65;">
            <div style="padding: 32px; padding-bottom: 16px; border-bottom: 1px solid #e2e8f0; background-color: #f8fafc;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px; background-color: #e2e8f0; color: #64748b; padding: 4px 12px; border-radius: 9999px;">
                        <div style="width: 8px; height: 8px; border-radius: 9999px; background-color: #94a3b8;"></div><span style="font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;">Read Only - Pending Revision</span>
                    </div><span style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase; letter-spacing: 0.1em;">BSU-GAD-2026-015</span>
                </div>
                <h2 style="font-family: 'Times New Roman', serif; font-size: 28px; color: #3b3b3b; line-height: 1.25; margin-bottom: 16px;">Lorem Ipsum Dolor Sit Amet Consectetur Adipiscing Elit</h2>
                <div style="display: flex; flex-wrap: wrap; gap: 24px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                    <div style="display: flex; flex-direction: column;"><span style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Submitted By</span><span style="font-size: 11px; color: #3b3b3b;">Dr. Lorem Ipsum</span></div>
                    <div style="display: flex; flex-direction: column;"><span style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Office</span><span style="font-size: 11px; color: #3b3b3b;">Lorem Ipsum Office</span></div>
                    <div style="display: flex; flex-direction: column;"><span style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Email</span><span style="font-size: 11px; color: #3b3b3b;">lorem.ipsum@bsu.edu.ph</span></div>
                    <div style="display: flex; flex-direction: column;"><span style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Date Submitted</span><span style="font-size: 11px; color: #3b3b3b;">Mar 15, 2026</span></div>
                </div>
            </div>

            <div style="flex: 1; overflow-y: auto; padding: 32px; display: flex; flex-direction: column; gap: 24px; background-color: white;">
                <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;"><span style="font-size: 24px;">ℹ️</span><h3 style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b;">Report Information</h3></div>
                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;">
                        <div><label style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">Control Number</label><p style="font-size: 11px; color: #3b3b3b; margin-top: 4px;">BSU-GAD-2026-015</p></div>
                        <div><label style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">Activity Title</label><p style="font-size: 11px; color: #3b3b3b; margin-top: 4px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p></div>
                    </div>
                </div>

                <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;"><span style="font-size: 24px;">📊</span><h3 style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b;">Activity Metrics</h3></div>
                    <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;">
                        <div style="background-color: white; border-radius: 8px; padding: 12px; text-align: center; border: 1px solid #e2e8f0;"><span style="font-size: 24px;">👥</span><p style="font-size: 20px; font-weight: 700; color: #3b3b3b;">84</p><p style="font-size: 9px; color: #3b3b3b; text-transform: uppercase;">Total Attendees</p></div>
                        <div style="background-color: white; border-radius: 8px; padding: 12px; text-align: center; border: 1px solid #e2e8f0;"><span style="font-size: 24px;">⭐</span><p style="font-size: 20px; font-weight: 700; color: #3b3b3b;">96%</p><p style="font-size: 9px; color: #3b3b3b; text-transform: uppercase;">100%</p></div>
                        <div style="background-color: white; border-radius: 8px; padding: 12px; text-align: center; border: 1px solid #e2e8f0;"><span style="font-size: 24px;">♂️</span><p style="font-size: 20px; font-weight: 700; color: #3b3b3b;">28</p><p style="font-size: 9px; color: #3b3b3b; text-transform: uppercase;">Male</p></div>
                        <div style="background-color: white; border-radius: 8px; padding: 12px; text-align: center; border: 1px solid #e2e8f0;"><span style="font-size: 24px;">♀️</span><p style="font-size: 20px; font-weight: 700; color: #3b3b3b;">56</p><p style="font-size: 9px; color: #3b3b3b; text-transform: uppercase;">Female</p></div>
                    </div>
                </div>

                <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;"><span style="font-size: 24px;">📎</span><h3 style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #990dd1;">Uploaded Documents</h3></div>
                    <div style="margin-bottom: 16px;">
                        <p style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Accomplishment Report (Filled Template)</p>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background-color: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 12px;"><span style="font-size: 24px; color: #ef4444;">📄</span><div><p style="font-size: 11px; font-weight: 500; color: #000;">Lorem_Ipsum_Accomplishment_Report.pdf</p><p style="font-size: 9px; color: #3b3b3b;">2.4 MB</p></div></div><button style="color: #990dd1; font-size: 11px; padding: 4px 8px; border-radius: 8px; background: none; border: none; cursor: pointer;">👁️ Preview</button>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background-color: white; border-radius: 8px; border: 1px solid #e2e8f0;"><div style="display: flex; align-items: center; gap: 12px;"><span style="font-size: 24px; color: #3b82f6;">📋</span><div><p style="font-size: 11px; font-weight: 500; color: #000;">Lorem_Ipsum_Attendance_Sheet.pdf</p><p style="font-size: 9px; color: #3b3b3b;">1.2 MB</p></div></div><button style="color: #990dd1; font-size: 11px; background: none; border: none; cursor: pointer;">Preview</button></div>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background-color: white; border-radius: 8px; border: 1px solid #e2e8f0;"><div style="display: flex; align-items: center; gap: 12px;"><span style="font-size: 24px; color: #10b981;">🖼️</span><div><p style="font-size: 11px; font-weight: 500; color: #000;">Lorem_Ipsum_Photo_Documentation.zip</p><p style="font-size: 9px; color: #3b3b3b;">18.5 MB</p></div></div><button style="color: #990dd1; font-size: 11px; background: none; border: none; cursor: pointer;">Preview</button></div>
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background-color: white; border-radius: 8px; border: 1px solid #e2e8f0;"><div style="display: flex; align-items: center; gap: 12px;"><span style="font-size: 24px; color: #f59e0b;">🧾</span><div><p style="font-size: 11px; font-weight: 500; color: #000;">Lorem_Ipsum_Financial_Report.pdf</p><p style="font-size: 9px; color: #3b3b3b;">0.8 MB</p></div></div><button style="color: #990dd1; font-size: 11px; background: none; border: none; cursor: pointer;">Preview</button></div>
                    </div>
                </div>
            </div>
        </section>

        <section style="flex: 0.38; display: flex; flex-direction: column; gap: 20px; overflow-y: auto; padding-bottom: 16px; position: sticky; top: 80px; align-self: flex-start; max-height: calc(100vh - 96px);">
            <div style="background-color: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                    <div style="display: flex; align-items: center; gap: 8px; background-color: #fee2e2; color: #b91c1c; padding: 4px 12px; border-radius: 9999px;"><span style="font-size: 11px;">✏️</span><span style="font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;">Revision Mode</span></div>
                </div>
                <h2 style="font-size: 20px; font-weight: 700; color: #000; margin-bottom: 4px;">Request Revision</h2>
                <p style="font-size: 10px; color: #3b3b3b;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>

            <div style="background-color: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <h3 style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #990dd1; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;"><span style="font-size: 11px;">💬</span> Revision Feedback</h3>
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div><label style="display: block; font-size: 9px; font-weight: 700; color: #990dd1; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">Items to Revise (select all that apply)</label>
                        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; background-color: #f8fafc; padding: 12px; border-radius: 12px;"><label style="display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 8px; cursor: pointer;"><input type="checkbox" style="border-radius: 4px; width: 16px; height: 16px;"> <span style="font-size: 10px;">Lorem Ipsum</span></label><label style="display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 8px; cursor: pointer;"><input type="checkbox" style="border-radius: 4px; width: 16px; height: 16px;"> <span style="font-size: 10px;">Lorem Ipsum</span></label><label style="display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 8px; cursor: pointer;"><input type="checkbox" style="border-radius: 4px; width: 16px; height: 16px;"> <span style="font-size: 10px;">Lorem Ipsum</span></label><label style="display: flex; align-items: center; gap: 8px; padding: 8px; border-radius: 8px; cursor: pointer;"><input type="checkbox" style="border-radius: 4px; width: 16px; height: 16px;"> <span style="font-size: 10px;">Lorem Ipsum</span></label></div>
                    </div>
                    <div><label style="display: block; font-size: 9px; font-weight: 700; color: #990dd1; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">General Remarks / Comments</label><textarea rows="4" style="width: 100%; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; font-size: 11px; font-family: inherit;" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit..."></textarea></div>
                    <div><label style="display: block; font-size: 9px; font-weight: 700; color: #990dd1; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Revision Deadline</label><input type="date" style="width: 100%; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; font-size: 11px; font-family: inherit;"><p style="font-size: 9px; color: #3b3b3b; margin-top: 4px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></div>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;"><button style="width: 100%; padding: 12px; background-color: #ecd224; color: #000; border-radius: 12px; font-weight: 700; font-size: 11px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;"><span style="font-size: 11px;">📤</span> Send Revision Request</button><a href="ar_review.php" style="display: block; width: 100%; padding: 8px; font-size: 11px; color: #3b3b3b; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 4px;"><span style="font-size: 11px;">←</span> Back to Review</a></div>
        </section>
    </div>
</main>
</body>
</html>