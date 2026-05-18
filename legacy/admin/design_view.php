<?php
// design_view.php - Read-only View for Approved Activity Design (No Download)
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>View Activity Design | GAD-IMS</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; display: inline-block; line-height: 1; }
    body { background-color: #f8f9fb; font-family: 'Manrope', sans-serif; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cfc2d544; border-radius: 10px; }
</style>
</head>
<body class="antialiased">

<?php include 'sidebar.php'; ?>
<?php include 'header.php'; ?>

<main class="pl-64 pt-16 min-h-screen">
    <div class="p-10 flex gap-8">

        <section class="flex-[0.6] bg-white rounded-xl flex flex-col overflow-hidden shadow-sm border">
            <div class="p-8 pb-4 border-b bg-emerald-50/30">
                <div class="flex items-center gap-2 mb-4">
                    <div class="flex items-center gap-2 bg-[#3f6516]/20 text-[#3f6516] px-3 py-1 rounded-full">
                        <div class="w-2 h-2 rounded-full bg-[#3f6516]"></div>
                        <span class="text-[9px] font-black uppercase tracking-widest">Approved</span>
                    </div>
                    <span class="text-[9px] font-bold text-[#3b3b3b] uppercase tracking-widest">BSU-GAD-2026-003</span>
                </div>

                <h2 class="font-serif text-[28px] text-[#000] leading-tight mb-4">
                    Lorem Ipsum Dolor Sit Amet Consectetur Adipiscing Elit
                </h2>

                <div class="flex flex-wrap gap-6 pt-4 border-t border-slate-100">
                    <div class="flex flex-col"><span class="text-[9px] uppercase tracking-widest text-[#3b3b3b] font-bold mb-1">Submitted By</span><span class="text-[11px] font-semibold text-[#3f6516]">Dr. Lorem Ipsum</span></div>
                    <div class="flex flex-col"><span class="text-[9px] uppercase tracking-widest text-[#3b3b3b] font-bold mb-1">Office</span><span class="text-[11px] font-medium text-[#3b3b3b]">Lorem Ipsum Office</span></div>
                    <div class="flex flex-col"><span class="text-[9px] uppercase tracking-widest text-[#3b3b3b] font-bold mb-1">Email</span><span class="text-[11px] font-medium text-[#3b3b3b]">lorem.ipsum@bsu.edu.ph</span></div>
                    <div class="flex flex-col"><span class="text-[9px] uppercase tracking-widest text-[#3b3b3b] font-bold mb-1">Date Submitted</span><span class="text-[11px] font-medium text-[#3b3b3b]">Jan 15, 2026</span></div>
                    <div class="flex flex-col"><span class="text-[9px] uppercase tracking-widest text-[#3b3b3b] font-bold mb-1">Category</span><span class="text-[11px] font-medium text-[#3b3b3b]">Activity Design</span></div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar p-8 space-y-6">
                <div class="bg-slate-50 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-4"><span class="material-symbols-outlined text-[#3b3b3b]">description</span><h3 class="font-bold text-[11px] uppercase tracking-widest text-[#3b3b3b]">Activity Description</h3></div>
                    <p class="text-[11px] text-[#3b3b3b] leading-relaxed">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>

                <div class="bg-slate-50 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-4"><span class="material-symbols-outlined text-[#3b3b3b]">event_note</span><h3 class="font-bold text-[11px] uppercase tracking-widest text-[#3b3b3b]">Proposed Schedule & Venue</h3></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase">Proposed Dates</label><p class="text-[11px] text-[#3b3b3b] mt-1">Feb 20-22, 2026</p></div>
                        <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase">Venue</label><p class="text-[11px] text-[#3b3b3b] mt-1">Lorem Ipsum Convention Center, Dolor Sit Amet Hall</p></div>
                        <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase">Start Time</label><p class="text-[11px] text-[#3b3b3b] mt-1">8:00 AM</p></div>
                        <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase">End Time</label><p class="text-[11px] text-[#3b3b3b] mt-1">5:00 PM</p></div>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-4"><span class="material-symbols-outlined text-[#3b3b3b]">payments</span><h3 class="font-bold text-[11px] uppercase tracking-widest text-[#3b3b3b]">Budget & Participants</h3></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase">Total Proposed Budget</label><p class="text-xl font-bold text-[#000]">₱97,500.00</p></div>
                        <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase">Target Participants</label><p class="text-lg font-bold text-[#000] mt-1">35 participants (min. 6)</p></div>
                        <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase">Male Target</label><p class="text-[11px] text-[#3b3b3b] mt-1">15 participants</p></div>
                        <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase">Female Target</label><p class="text-[11px] text-[#3b3b3b] mt-1">20 participants</p></div>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-4"><span class="material-symbols-outlined text-[#3b3b3b]">receipt</span><h3 class="font-bold text-[11px] uppercase tracking-widest text-[#3b3b3b]">Budget Breakdown</h3></div>
                    <div class="space-y-2">
                        <div class="flex justify-between py-2 border-b border-slate-200"><span class="text-[11px] text-[#3b3b3b]">Lorem Ipsum Materials</span><span class="text-[11px] font-medium text-[#000]">₱25,000.00</span></div>
                        <div class="flex justify-between py-2 border-b border-slate-200"><span class="text-[11px] text-[#3b3b3b]">Honorarium for Lorem Ipsum (2 persons)</span><span class="text-[11px] font-medium text-[#000]">₱20,000.00</span></div>
                        <div class="flex justify-between py-2 border-b border-slate-200"><span class="text-[11px] text-[#3b3b3b]">Meals & Snacks (35 pax x 3 days)</span><span class="text-[11px] font-medium text-[#000]">₱31,500.00</span></div>
                        <div class="flex justify-between py-2 border-b border-slate-200"><span class="text-[11px] text-[#3b3b3b]">Certificates & Printing</span><span class="text-[11px] font-medium text-[#000]">₱5,000.00</span></div>
                        <div class="flex justify-between py-2 border-b border-slate-200"><span class="text-[11px] text-[#3b3b3b]">Miscellaneous / Contingency</span><span class="text-[11px] font-medium text-[#000]">₱16,000.00</span></div>
                        <div class="flex justify-between py-2 pt-3"><span class="text-[11px] font-bold text-[#000]">TOTAL</span><span class="text-[11px] font-bold text-[#3f6516]">₱97,500.00</span></div>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-4"><span class="material-symbols-outlined text-[#990dd1]">attach_file</span><h3 class="font-bold text-[11px] uppercase tracking-widest text-[#990dd1]">Uploaded Documents</h3></div>
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                        <div class="flex items-center gap-3"><span class="material-symbols-outlined text-red-500 text-2xl">description</span><div><p class="text-[11px] font-medium text-[#000]">Lorem_Ipsum_Activity_Design.pdf</p><p class="text-[9px] text-[#3b3b3b]">1.2 MB • Uploaded Jan 15, 2026</p></div></div>
                        <button class="text-[#990dd1] text-[11px] px-2 py-1 rounded hover:bg-[#b979cc]/10"><span class="material-symbols-outlined text-[11px] align-middle">visibility</span> Preview</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex-[0.4] flex flex-col gap-5 overflow-y-auto custom-scrollbar pb-4 sticky top-20 self-start max-h-[calc(100vh-6rem)]">
            <div class="bg-white rounded-xl p-6 shadow-sm border">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-[#3f6516]/20 flex items-center justify-center"><span class="material-symbols-outlined text-[#3f6516]">verified</span></div>
                    <div><p class="text-[11px] font-bold text-[#3f6516]">Approved</p><p class="text-[9px] text-[#3b3b3b]">Approved on Feb 10, 2026</p></div>
                </div>
                
                <div class="space-y-4">
                    <div class="border-t pt-3"><label class="text-[9px] font-bold text-[#3b3b3b] uppercase tracking-wider">Control Number</label><p class="text-[11px] font-mono font-bold text-[#990dd1] mt-1">BSU-GAD-2026-003</p></div>
                    <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase tracking-wider">Date of Assessment</label><p class="text-[11px] text-[#3b3b3b] mt-1">February 10, 2026</p></div>
                    <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase tracking-wider">HGDG Score</label><div class="flex items-center gap-2 mt-1"><p class="text-2xl font-bold text-[#3f6516]">4.3</p><p class="text-[10px] text-[#3b3b3b]">/ 5.0</p><div class="flex ml-2"><span class="material-symbols-outlined text-[#ecd224] text-sm">star</span><span class="material-symbols-outlined text-[#ecd224] text-sm">star</span><span class="material-symbols-outlined text-[#ecd224] text-sm">star</span><span class="material-symbols-outlined text-[#ecd224] text-sm">star</span><span class="material-symbols-outlined text-slate-300 text-sm">star</span></div></div><p class="text-[9px] text-[#3b3b3b] mt-1">Minimum requirement met (≥ 4.0)</p></div>
                    <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase tracking-wider">Alignment Verification</label><div class="space-y-1 mt-2"><div class="flex items-center gap-2"><span class="material-symbols-outlined text-[#3f6516] text-sm">check_circle</span><span class="text-[10px] text-[#3b3b3b]">Lorem ipsum dolor sit amet</span></div><div class="flex items-center gap-2"><span class="material-symbols-outlined text-[#3f6516] text-sm">check_circle</span><span class="text-[10px] text-[#3b3b3b]">Consectetur adipiscing elit</span></div><div class="flex items-center gap-2"><span class="material-symbols-outlined text-[#3f6516] text-sm">check_circle</span><span class="text-[10px] text-[#3b3b3b]">Sed do eiusmod tempor incididunt</span></div></div></div>
                    <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase tracking-wider">Reviewer's Remarks</label><div class="bg-slate-50 p-3 rounded-lg mt-1"><p class="text-[11px] text-[#3b3b3b] italic">"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam."</p><p class="text-[9px] text-[#3b3b3b] mt-2">— Maria Santos, GAD Staff</p></div></div>
                    <div><label class="text-[9px] font-bold text-[#3b3b3b] uppercase tracking-wider">Accomplishment Report Deadline</label><p class="text-[11px] text-[#3b3b3b] mt-1">March 12, 2026</p><div class="flex items-center gap-1 mt-1"><span class="material-symbols-outlined text-amber-500 text-sm">info</span><p class="text-[9px] text-amber-600">Accomplishment report has been submitted</p></div></div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border">
                <h3 class="font-bold text-[11px] uppercase tracking-widest text-[#3b3b3b] mb-4">Submission History</h3>
                <div class="space-y-4 relative before:absolute before:left-2 before:top-6 before:bottom-6 before:w-[1px] before:bg-slate-200">
                    <div class="relative pl-7"><div class="absolute left-0 top-0.5 w-4 h-4 rounded-full bg-[#990dd1]"></div><p class="text-[11px] font-bold text-[#000]">Submitted for Review</p><p class="text-[9px] text-[#3b3b3b]">Jan 15, 2026 • 10:23 AM</p></div>
                    <div class="relative pl-7"><div class="absolute left-0 top-0.5 w-4 h-4 rounded-full bg-[#990dd1]"></div><p class="text-[11px] font-medium text-[#3b3b3b]">Technical Review</p><p class="text-[9px] text-[#3b3b3b]">Jan 20, 2026 • 02:15 PM</p></div>
                    <div class="relative pl-7"><div class="absolute left-0 top-0.5 w-4 h-4 rounded-full bg-[#3f6516]"></div><p class="text-[11px] font-bold text-[#3f6516]">Approved</p><p class="text-[9px] text-[#3b3b3b]">Feb 10, 2026 • 09:45 AM</p></div>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                <div class="flex gap-3"><span class="material-symbols-outlined text-blue-600">info</span><div><p class="text-[11px] font-bold text-blue-700">Archived Document</p><p class="text-[9px] text-blue-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. This activity design has been approved and archived.</p></div></div>
            </div>

            <div class="pt-2"><a href="employee_submissions.php" class="block w-full py-3 bg-slate-100 text-[#3b3b3b] rounded-xl font-bold text-[11px] hover:bg-slate-200 transition flex items-center justify-center gap-2"><span class="material-symbols-outlined text-[11px]">arrow_back</span> Back to Submissions</a></div>
        </section>
    </div>
</main>
</body>
</html>