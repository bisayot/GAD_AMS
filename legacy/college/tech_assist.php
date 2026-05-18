<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Technical Assistance & Consultation | OSS Portal</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script>
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          "primary": "#990dd1",
          "primary-light": "#b979cc",
          "secondary": "#3f6516",
          "tertiary": "#5170ff",
          "dark-blue": "#002c5c",
          "surface": "#f8f9fb",
          "surface-container-low": "#f8f9fb",
          "surface-container-lowest": "#ffffff",
          "on-surface": "#000",
          "on-surface-variant": "#3b3b3b",
          "outline": "#7e7384",
          "outline-variant": "#cfc2d5",
        },
        fontFamily: { "headline": ["Manrope", "sans-serif"] },
        borderRadius: { "DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem" },
      },
    },
  }
</script>
<style>
  .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
  body { font-family: 'Manrope', sans-serif; background-color: #f8f9fb; }
</style>
</head>
<body class="bg-background text-on-surface antialiased" style="margin-top: 80px;">
<?php include 'includes/sidebar_twg.php'; ?>

<header class="fixed top-0 right-0 left-64 z-40 bg-white/80 backdrop-blur-md shadow-sm flex justify-between items-center px-8 py-4">
  <div class="flex items-center gap-4">
    <span class="text-xl font-bold tracking-tight text-[#990dd1]">OSS Consultation Request</span>
  </div>
  <div class="flex items-center gap-6">
    <div class="relative group">
      <span class="material-symbols-outlined text-slate-600 hover:text-[#990dd1] cursor-pointer transition-colors">notifications</span>
      <div class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></div>
    </div>
    <span class="material-symbols-outlined text-slate-600 hover:text-[#990dd1] cursor-pointer transition-colors">settings</span>
    <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
      <div class="w-8 h-8 rounded-full bg-[#b979cc]/20 flex items-center justify-center">
        <span class="material-symbols-outlined text-[#990dd1] text-sm">school</span>
      </div>
      <div class="text-right">
        <p class="text-[11px] font-bold text-[#000]">Dr. Maria Santos</p>
        <p class="text-[9px] text-slate-500 font-medium tracking-tight">Office of Student Services</p>
      </div>
    </div>
  </div>
</header>

<main class="ml-64 mt-[72px] p-10 max-w-4xl mx-auto">
  <header class="mb-10">
    <h2 class="text-3xl font-extrabold tracking-tight text-[#000] font-headline mb-2">Request for Technical Assistance</h2>
    <p class="text-[12px] text-[#3b3b3b] max-w-2xl">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
  </header>

  <form class="space-y-8">
    <div class="bg-white p-8 rounded-xl shadow-sm border">
      <div class="flex items-center gap-3 mb-6">
        <span class="text-[10px] font-bold tracking-[0.1em] uppercase bg-[#b979cc]/20 text-[#990dd1] px-2 py-1 rounded">Step 01</span>
        <h4 class="text-[11px] font-bold tracking-[0.1em] uppercase text-[#3b3b3b]">Consultation Details</h4>
      </div>
      <div class="space-y-6">
        <div class="space-y-2">
          <label class="text-[10px] font-bold tracking-[0.1em] uppercase text-[#3b3b3b]">Nature of Request</label>
          <select class="w-full bg-slate-50 border border-slate-200 focus:border-b-2 focus:border-[#990dd1] focus:ring-0 rounded-xl px-4 py-3 text-[12px] text-[#3b3b3b] appearance-none">
            <option value="">Select Category...</option>
            <option>GAD Orientation for Students</option>
            <option>Student Leadership Training</option>
            <option>Gender Sensitivity Training for OSS Personnel</option>
            <option>VAW Campaign Support</option>
            <option>Women's Month Celebration</option>
          </select>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-2">
            <label class="text-[10px] font-bold tracking-[0.1em] uppercase text-[#3b3b3b]">Proposed Date</label>
            <input class="w-full bg-slate-50 border border-slate-200 focus:border-b-2 focus:border-[#990dd1] focus:ring-0 rounded-xl px-4 py-3 text-[12px]" type="date"/>
          </div>
          <div class="space-y-2">
            <label class="text-[10px] font-bold tracking-[0.1em] uppercase text-[#3b3b3b]">Proposed Time</label>
            <input class="w-full bg-slate-50 border border-slate-200 focus:border-b-2 focus:border-[#990dd1] focus:ring-0 rounded-xl px-4 py-3 text-[12px]" type="time"/>
          </div>
        </div>
        <div class="space-y-2">
          <label class="text-[10px] font-bold tracking-[0.1em] uppercase text-[#3b3b3b]">Narrative Description</label>
          <textarea class="w-full bg-slate-50 border border-slate-200 focus:border-b-2 focus:border-[#990dd1] focus:ring-0 rounded-xl px-4 py-3 text-[12px] text-[#3b3b3b] resize-none" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit..." rows="6"></textarea>
        </div>
      </div>
    </div>

    <div class="bg-white p-8 rounded-xl shadow-sm border">
      <div class="flex items-center gap-3 mb-6">
        <span class="text-[10px] font-bold tracking-[0.1em] uppercase bg-[#b979cc]/20 text-[#990dd1] px-2 py-1 rounded">Step 02</span>
        <h4 class="text-[11px] font-bold tracking-[0.1em] uppercase text-[#3b3b3b]">Supporting Documents</h4>
      </div>
      <div class="border-2 border-dashed border-slate-200 rounded-xl p-12 text-center hover:border-[#990dd1]/50 transition-colors cursor-pointer group">
        <span class="material-symbols-outlined text-5xl text-slate-300 group-hover:text-[#990dd1] transition-colors mb-4">cloud_upload</span>
        <p class="text-[#000] font-semibold text-[12px]">Drop your files here or <span class="text-[#990dd1] underline">browse</span></p>
        <p class="text-[10px] text-[#3b3b3b] mt-2">PDF</p>
      </div>
    </div>

    <div class="flex justify-end pt-4">
      <button class="bg-[#990dd1] text-white px-10 py-4 rounded-xl font-bold text-[12px] flex items-center gap-3 hover:bg-[#b979cc] transition-all active:scale-95 shadow-md" type="submit">
        <span>Submit Request</span>
        <span class="material-symbols-outlined text-sm">send</span>
      </button>
    </div>
  </form>
</main>
</body>
</html>