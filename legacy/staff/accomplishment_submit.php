<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Submit Accomplishment Report | GAD-IMS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f8f9fb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin-top: 80px; }
        
        /* Layout */
        .ml-64 { margin-left: 256px; }
        .min-h-screen { min-height: 100vh; }
        .max-w-3xl { max-width: 48rem; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .py-10 { padding-top: 40px; padding-bottom: 40px; }
        .px-6 { padding-left: 24px; padding-right: 24px; }
        .mb-8 { margin-bottom: 32px; }
        .mb-6 { margin-bottom: 24px; }
        .mt-1 { margin-top: 4px; }
        .mt-3 { margin-top: 12px; }
        
        /* Typography */
        .text-3xl { font-size: 30px; }
        .text-2xl { font-size: 24px; }
        .text-xl { font-size: 20px; }
        .text-sm { font-size: 14px; }
        .text-xs { font-size: 12px; }
        .text-11px { font-size: 11px; }
        .text-10px { font-size: 10px; }
        .font-bold { font-weight: 700; }
        .font-medium { font-weight: 500; }
        .font-black { font-weight: 900; }
        .font-extrabold { font-weight: 800; }
        .tracking-tight { letter-spacing: -0.025em; }
        .tracking-wider { letter-spacing: 0.05em; }
        .tracking-widest { letter-spacing: 0.1em; }
        .uppercase { text-transform: uppercase; }
        .text-000 { color: #000; }
        .text-3b3b3b { color: #3b3b3b; }
        .text-990dd1 { color: #990dd1; }
        .text-3f6516 { color: #3f6516; }
        .text-red-600 { color: #dc2626; }
        
        /* Backgrounds & Borders */
        .bg-white { background-color: white; }
        .bg-slate-50 { background-color: #f8fafc; }
        .bg-990dd1 { background-color: #990dd1; }
        .bg-990dd1\/20 { background-color: rgba(153,13,209,0.2); }
        .rounded-2xl { border-radius: 16px; }
        .rounded-xl { border-radius: 12px; }
        .rounded-lg { border-radius: 8px; }
        .border { border: 1px solid #e2e8f0; }
        .border-slate-100 { border-color: #f1f5f9; }
        .border-slate-200 { border-color: #e2e8f0; }
        .border-slate-300 { border-color: #cbd5e1; }
        .border-dashed { border-style: dashed; }
        .border-2 { border-width: 2px; }
        .shadow-xl { box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        
        /* Flex & Grid */
        .flex { display: flex; }
        .grid { display: grid; }
        .grid-cols-1 { grid-template-columns: repeat(1, minmax(0, 1fr)); }
        .gap-6 { gap: 24px; }
        .gap-4 { gap: 16px; }
        .gap-2 { gap: 8px; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .justify-end { justify-content: flex-end; }
        .flex-col { flex-direction: column; }
        .space-y-2 > * + * { margin-top: 8px; }
        .space-y-6 > * + * { margin-top: 24px; }
        .space-y-3 > * + * { margin-top: 12px; }
        .space-y-1 > * + * { margin-top: 4px; }
        
        /* Form Elements */
        .w-full { width: 100%; }
        .px-5 { padding-left: 20px; padding-right: 20px; }
        .py-3\.5 { padding-top: 14px; padding-bottom: 14px; }
        .py-3 { padding-top: 12px; padding-bottom: 12px; }
        .px-10 { padding-left: 40px; padding-right: 40px; }
        .px-4 { padding-left: 16px; padding-right: 16px; }
        .px-6 { padding-left: 24px; padding-right: 24px; }
        .p-8 { padding: 32px; }
        .p-6 { padding: 24px; }
        .p-4 { padding: 16px; }
        .p-3 { padding: 12px; }
        .pt-6 { padding-top: 24px; }
        .pb-4 { padding-bottom: 16px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .mb-1 { margin-bottom: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-4 { margin-top: 16px; }
        .cursor-pointer { cursor: pointer; }
        .transition { transition: all 0.2s ease; }
        .hover\:bg-990dd1\/10:hover { background-color: rgba(153,13,209,0.1); }
        .hover\:bg-slate-100:hover { background-color: #f1f5f9; }
        .hover\:bg-b979cc:hover { background-color: #b979cc; }
        .hover\:scale-110:hover { transform: scale(1.1); }
        .focus\:ring-2:focus { outline: none; ring: 2px solid; }
        .focus\:ring-990dd1:focus { ring-color: #990dd1; }
        .resize-none { resize: none; }
        .absolute { position: absolute; }
        .relative { position: relative; }
        .inset-0 { top: 0; right: 0; bottom: 0; left: 0; }
        .opacity-0 { opacity: 0; }
        .group:hover .group-hover\:scale-110 { transform: scale(1.1); }
        
        @media (min-width: 768px) {
            .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        
        /* Header Styles */
        .staff-header {
            position: fixed;
            top: 0;
            left: 256px;
            right: 0;
            height: 80px;
            background-color: rgba(255,255,255,0.9);
            backdrop-filter: blur(8px);
            z-index: 40;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            border-bottom: 1px solid rgba(226,232,240,0.4);
        }
        .header-title { font-size: 24px; font-weight: 800; color: #002c5c; letter-spacing: -0.025em; line-height: 1; }
        .header-subtitle { font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 600; }
        .notif-btn { padding: 8px; background: transparent; border: none; border-radius: 9999px; cursor: pointer; transition: background-color 0.2s; }
        .notif-btn:hover { background-color: rgba(185,121,204,0.1); }
        .notif-badge { position: absolute; top: 4px; right: 4px; width: 8px; height: 8px; background-color: #ef4444; border-radius: 9999px; }
        .notif-panel { position: fixed; top: 64px; right: 40px; width: 320px; background-color: white; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; z-index: 50; display: none; }
        .notif-panel.show { display: block; }
        .notif-header { padding: 16px; border-bottom: 1px solid #e2e8f0; }
        .notif-header h3 { font-weight: 700; color: #000; }
        .notif-list { max-height: 384px; overflow-y: auto; }
        .notif-item { padding: 16px; border-bottom: 1px solid #f1f5f9; cursor: pointer; }
        .notif-item:hover { background-color: rgba(185,121,204,0.1); }
        .notif-item.bg-amber { background-color: #fffbeb; }
        .notif-title { font-size: 11px; font-weight: 500; color: #000; }
        .notif-time { font-size: 10px; color: #3b3b3b; }
        .notif-footer { padding: 12px; border-top: 1px solid #e2e8f0; background-color: #f8fafc; text-align: center; }
        .notif-footer button { background: none; border: none; font-size: 10px; font-weight: 700; color: #990dd1; cursor: pointer; }
        .relative { position: relative; }
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
            <h2 class="header-title">Submit Accomplishment Report</h2>
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

    <main class="ml-64 min-h-screen">
        <div class="max-w-3xl mx-auto py-10 px-6">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-000 tracking-tight">Submit Accomplishment Report</h1>
                <p class="text-xs text-3b3b3b mt-1">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 border border-slate-100">
                <form action="process_accomplishment.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-11px font-bold uppercase tracking-wider text-3b3b3b">Activity Title</label>
                        <textarea name="activity_title" required rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-xs focus:ring-2 focus:ring-990dd1 resize-none" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-11px font-bold uppercase tracking-wider text-3b3b3b">Activity Design Control Number</label>
                        <select name="control_number" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-xs focus:ring-2 focus:ring-990dd1">
                            <option value="">Select approved activity design...</option>
                            <option value="OSS-2025-001">OSS-2025-001 - Lorem Ipsum Dolor Sit Amet</option>
                            <option value="OSS-2025-002">OSS-2025-002 - Consectetur Adipiscing Elit</option>
                            <option value="OSS-2025-003">OSS-2025-003 - Sed Do Eiusmod Tempor</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-11px font-bold uppercase tracking-wider text-3b3b3b">Start Date of Implementation</label>
                            <input type="date" name="start_date" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-xs">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-11px font-bold uppercase tracking-wider text-3b3b3b">End Date of Implementation</label>
                            <input type="date" name="end_date" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-11px font-bold uppercase tracking-wider text-3b3b3b">Start Time</label>
                            <input type="time" name="start_time" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-xs">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-11px font-bold uppercase tracking-wider text-3b3b3b">End Time</label>
                            <input type="time" name="end_time" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-xs">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-11px font-bold uppercase tracking-wider text-3b3b3b">Venue</label>
                        <input type="text" name="venue" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-xs" placeholder="Lorem Ipsum Convention Center, Dolor Sit Amet Hall">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-11px font-bold uppercase tracking-wider text-3b3b3b">Number of Attendees</label>
                        <input type="number" name="attendees" required min="6" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-xs">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-11px font-bold uppercase tracking-wider text-3b3b3b">Male Participants</label>
                            <input type="number" name="male" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-xs" placeholder="0">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-11px font-bold uppercase tracking-wider text-3b3b3b">Female Participants</label>
                            <input type="number" name="female" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-xs" placeholder="0">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-11px font-bold uppercase tracking-wider text-3b3b3b">Activity Rating (Percentage)</label>
                        <input type="number" name="rating" min="0" max="100" step="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-xs" placeholder="0-100%">
                        
                        <div class="mt-3 bg-slate-50 rounded-xl p-4 border border-slate-200">
                            <p class="text-11px font-bold text-3b3b3b mb-2">Rating Guide:</p>
                            <div class="space-y-1 text-10px">
                                <p><span class="font-bold text-3f6516">81%-100% - Excellent:</span> Activity was completed successfully, highly engaging, impactful, and well-organized. Exceeded expectations.</p>
                                <p><span class="font-bold text-3f6516">61%-80% - Very Satisfactory:</span> Activity was completed with minor issues but was still productive, engaging, and met most objectives.</p>
                                <p><span class="font-bold text-3f6516">41%-60% - Satisfactory:</span> Activity had some issues or lacked full engagement but met basic objectives.</p>
                                <p><span class="font-bold text-3b3b3b">21%-40% - Needs Improvement:</span> Activity faced significant challenges, lacked engagement or clear outcomes. Did not fully meet objectives.</p>
                                <p><span class="font-bold text-red-600">1%-20% - Unsatisfactory:</span> Activity had major issues, minimal engagement, and failed to meet most objectives.</p>
                                <p><span class="font-bold text-red-600">0% - Canceled:</span> Activity was canceled or did not occur at all.</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-11px font-bold uppercase tracking-wider text-3b3b3b">Upload Documents (PDF/ZIP - Multiple files allowed)</label>
                        <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50 hover:bg-slate-100 transition cursor-pointer group">
                            <input type="file" name="report_files[]" accept=".pdf,.zip" multiple required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <span class="text-4xl text-990dd1 mb-3 group-hover:scale-110 transition">📤</span>
                            <p class="text-xs font-medium text-990dd1">Upload Accomplishment Report & Attachments</p>
                            <p class="text-10px text-3b3b3b mt-1">Multiple files allowed (PDF, ZIP)</p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit" class="bg-990dd1 text-white px-10 py-3.5 rounded-xl font-bold text-xs shadow-lg hover:bg-b979cc transition-all flex items-center gap-2 cursor-pointer">
                            <span>📤</span> Submit Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>