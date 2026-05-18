<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Submit Activity Design | GAD-IMS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f8f9fb; margin-top: 80px; }
        
        /* Layout */
        .ml-64 { margin-left: 256px; }
        .min-h-screen { min-height: 100vh; }
        .max-w-3xl { max-width: 48rem; }
        .mx-auto { margin-left: auto; margin-right: auto; }
        .py-12 { padding-top: 48px; padding-bottom: 48px; }
        .px-6 { padding-left: 24px; padding-right: 24px; }
        .mb-10 { margin-bottom: 40px; }
        .mb-2 { margin-bottom: 8px; }
        .mt-1 { margin-top: 4px; }
        
        /* Typography */
        .text-3xl { font-size: 30px; }
        .text-xl { font-size: 20px; }
        .text-sm { font-size: 14px; }
        .text-xs { font-size: 12px; }
        .text-11px { font-size: 11px; }
        .text-10px { font-size: 10px; }
        .font-bold { font-weight: 700; }
        .font-black { font-weight: 900; }
        .font-extrabold { font-weight: 800; }
        .tracking-tight { letter-spacing: -0.025em; }
        .tracking-widest { letter-spacing: 0.1em; }
        .tracking-wider { letter-spacing: 0.05em; }
        .uppercase { text-transform: uppercase; }
        .text-000 { color: #000; }
        .text-3b3b3b { color: #3b3b3b; }
        .text-990dd1 { color: #990dd1; }
        
        /* Backgrounds & Borders */
        .bg-white { background-color: white; }
        .bg-slate-50 { background-color: #f8fafc; }
        .bg-990dd1 { background-color: #990dd1; }
        .rounded-2xl { border-radius: 16px; }
        .rounded-xl { border-radius: 12px; }
        .border { border: 1px solid #e2e8f0; }
        .border-slate-200 { border-color: #e2e8f0; }
        .border-dashed { border-style: dashed; }
        .border-2 { border-width: 2px; }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        
        /* Flex & Grid */
        .flex { display: flex; }
        .grid { display: grid; }
        .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .gap-4 { gap: 16px; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .justify-center { justify-content: center; }
        .flex-col { flex-direction: column; }
        .space-y-6 > * + * { margin-top: 24px; }
        .space-y-1\.5 > * + * { margin-top: 6px; }
        .space-y-2 > * + * { margin-top: 8px; }
        .space-y-1 > * + * { margin-top: 4px; }
        
        /* Form Elements */
        .w-full { width: 100%; }
        .px-4 { padding-left: 16px; padding-right: 16px; }
        .px-8 { padding-left: 32px; padding-right: 32px; }
        .px-6 { padding-left: 24px; padding-right: 24px; }
        .py-3 { padding-top: 12px; padding-bottom: 12px; }
        .py-2 { padding-top: 8px; padding-bottom: 8px; }
        .p-8 { padding: 32px; }
        .pt-4 { padding-top: 16px; }
        .mb-2 { margin-bottom: 8px; }
        .mt-1 { margin-top: 4px; }
        .cursor-pointer { cursor: pointer; }
        .transition { transition: all 0.2s ease; }
        .hover\:bg-slate-100:hover { background-color: #f1f5f9; }
        .hover\:bg-b979cc:hover { background-color: #b979cc; }
        .hover\:scale-110:hover { transform: scale(1.1); }
        .focus\:ring-990dd1:focus { outline: none; ring-color: #990dd1; }
        .focus\:border-990dd1:focus { border-color: #990dd1; }
        .resize-none { resize: none; }
        .absolute { position: absolute; }
        .relative { position: relative; }
        .inset-0 { top: 0; right: 0; bottom: 0; left: 0; }
        .opacity-0 { opacity: 0; }
        .opacity-60 { opacity: 0.6; }
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
        .sticky { position: sticky; }
        .top-0 { top: 0; }
        .z-40 { z-index: 40; }
        .backdrop-blur-md { backdrop-filter: blur(12px); }
        .bg-white\/80 { background-color: rgba(255,255,255,0.8); }
        .shadow-md { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .h-8 { height: 32px; }
        .w-8 { width: 32px; }
        .rounded-full { border-radius: 9999px; }
        .text-sm { font-size: 14px; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
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
            <h2 class="header-title">Submit Activity Design</h2>
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
    <?php include 'includes/sidebar_twg.php'; ?>

    <main class="ml-64 min-h-screen">
        <div class="max-w-3xl mx-auto py-12 px-6">
            <div class="mb-10">
                <h1 class="text-3xl font-bold text-000 mb-2">Submit Activity Design</h1>
                <p class="text-xs text-3b3b3b">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <form id="formStep2" action="dashboard.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="full_name" id="hidden_full_name">
                    <input type="hidden" name="office" id="hidden_office">
                    <input type="hidden" name="email" id="hidden_email">
                    
                    <div class="space-y-1.5">
                        <label class="text-11px font-black uppercase tracking-widest text-3b3b3b">Nature of Transaction</label>
                        <select name="nature" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-xs focus:ring-990dd1 focus:border-990dd1">
                            <option value="">Select transaction type...</option>
                            <option value="inset">INSET Training</option>
                            <option value="extension">Extension Program</option>
                            <option value="employee">Employee Training</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-11px font-black uppercase tracking-widest text-3b3b3b">Activity Title</label>
                        <textarea name="activity_title" required rows="2" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-xs focus:ring-990dd1 focus:border-990dd1 resize-none" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-11px font-black uppercase tracking-widest text-3b3b3b">Start Date</label>
                            <input type="date" name="start_date" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-xs">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-11px font-black uppercase tracking-widest text-3b3b3b">End Date</label>
                            <input type="date" name="end_date" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-11px font-black uppercase tracking-widest text-3b3b3b">Start Time</label>
                            <input type="time" name="start_time" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-xs">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-11px font-black uppercase tracking-widest text-3b3b3b">End Time</label>
                            <input type="time" name="end_time" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-xs">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-11px font-black uppercase tracking-widest text-3b3b3b">Venue</label>
                        <input type="text" name="venue" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-xs" placeholder="Lorem ipsum Convention Center, Dolor Sit Amet Hall">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-11px font-black uppercase tracking-widest text-3b3b3b">Target Participants</label>
                        <input type="number" name="target_participants" required min="6" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-xs">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-11px font-black uppercase tracking-widest text-3b3b3b">Proposed Budget (PHP)</label>
                        <input type="number" name="proposed_budget" step="0.01" required class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-xs" placeholder="0.00">
                    </div>

                    <div class="space-y-2">
                        <label class="text-11px font-black uppercase tracking-widest text-3b3b3b">Upload Activity Design (PDF)</label>
                        <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-8 flex flex-col items-center justify-center bg-slate-50 hover:bg-slate-50 transition cursor-pointer group">
                            <input type="file" name="design_file" accept=".pdf" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <span class="text-4xl text-990dd1 mb-2 opacity-60 group-hover:scale-110 transition">📤</span>
                            <p class="text-xs font-bold text-990dd1 uppercase tracking-widest">Select File</p>
                            <p class="text-10px text-3b3b3b mt-1">PDF</p>
                        </div>
                    </div>

                    <div class="flex justify-between pt-4">
                        <a href="dashboard.php" class="px-6 py-3 text-990dd1 font-bold text-xs uppercase tracking-widest hover:bg-slate-100 rounded-xl transition-all flex items-center gap-2">
                            <span>←</span> Back
                        </a>
                        <button type="submit" class="bg-990dd1 text-white px-8 py-3 rounded-xl font-bold text-xs uppercase tracking-widest shadow-lg hover:bg-b979cc transition-all flex items-center gap-2 cursor-pointer">
                            Submit Design <span>✅</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>