<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>View Accomplishment Report | OSS | GAD-IMS</title>
<link href="https://fonts.googleapis.com" rel="preconnect" />
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "primary": "#990dd1",
          "primary-container": "#b979cc",
          "secondary": "#3f6516",
          "surface-container-low": "#f8f9fb",
          "surface-container-lowest": "#ffffff",
          "on-surface": "#000",
          "outline-variant": "#cfc2d5",
          "on-surface-variant": "#3b3b3b"
        },
        fontFamily: { "headline": ["Manrope", "sans-serif"] },
        borderRadius: { "xl": "0.75rem" }
      }
    }
  }
</script>
<style>
  .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
  body { font-family: 'Manrope', sans-serif; background-color: #f8f9fb; }
  .read-only-dim { opacity: 0.75; }
</style>
</head>
<body class="text-on-surface bg-background" style="margin-top: 80px;">

<?php include 'includes/sidebar_twg.php'; ?>

<main class="ml-64 min-h-screen">
  <header class="w-full sticky top-0 z-40 bg-white/80 backdrop-blur-md shadow-sm">
    <div class="flex items-center justify-between px-10 py-3 w-full">
      <span class="text-xl font-bold tracking-tight text-[#990dd1]">OSS | View Accomplishment Report</span>
    </div>
  </header>

  <div class="max-w-3xl mx-auto py-10 px-6">
    <div class="mb-6 bg-[#3f6516]/20 border-l-4 border-secondary p-4 rounded-r-xl">
      <div class="flex items-center gap-3">
        <span class="material-symbols-outlined text-green-700">verified</span>
        <div>
          <p class="font-bold text-on-surface">Report Verified</p>
          <p class="text-sm text-on-surface-variant">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-8 border border-slate-100 read-only-dim">
      <div class="space-y-7">
        <div class="space-y-2">
          <label class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Activity Title</label>
          <div class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-on-surface">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
        </div>

        <div class="space-y-2">
          <label class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Report Reference</label>
          <div class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-primary font-mono font-bold">ACC-OSS-2026-001</div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Date Conducted</label>
            <div class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-on-surface">March 15-30, 2026</div>
          </div>
          <div>
            <label class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Final Reach</label>
            <div class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-on-surface">3,850 Students (F:2,670 M:1,180)</div>
          </div>
        </div>

        <div class="space-y-2">
          <label class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Actual Expenditure (PHP)</label>
          <div class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-on-surface">₱421,728.50</div>
          <p class="text-[10px] text-slate-400">Approved Budget: ₱453,363</p>
        </div>

        <div class="space-y-2">
          <label class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Key Outcomes</label>
          <div class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-on-surface italic">
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
          </div>
        </div>

        <div class="space-y-2">
          <label class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Verified Attachments</label>
          <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
            <div class="flex items-center gap-3"><span class="material-symbols-outlined text-primary">folder_zip</span><span class="text-on-surface">lorem_ipsum_documents.zip</span></div>
            <button class="text-primary text-sm flex items-center gap-1 font-bold"><span class="material-symbols-outlined text-base">visibility</span> Preview</button>
          </div>
          <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200 mt-2">
            <div class="flex items-center gap-3"><span class="material-symbols-outlined text-primary">description</span><span class="text-on-surface">lorem_ipsum_attendance.pdf</span></div>
            <button class="text-primary text-sm flex items-center gap-1 font-bold"><span class="material-symbols-outlined text-base">visibility</span> Preview</button>
          </div>
        </div>

        <div class="pt-4">
          <a href="twg_submitted_list.php" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-primary font-semibold text-sm hover:bg-violet-50 transition-all">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Submitted List
          </a>
        </div>
      </div>
    </div>
  </div>
</main>
</body>
</html>