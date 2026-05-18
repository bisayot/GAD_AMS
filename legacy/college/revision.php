<?php
require_once '../auth.php';
require_once '../database.php';
require_once 'includes/config.php';
require_role(['college']);

$page_title = 'Revise activity design';
include 'includes/head.php';
?>
<body class="bg-surface text-on-surface selection:bg-primary-fixed" style="margin-top: 80px;">
  <?php include 'includes/sidebar_twg.php'; ?>

  <main class="ml-64 min-h-screen">
    <?php
    $topbar_title = 'Revise activity design';
    $topbar_search_placeholder = 'Search this form…';
    include 'includes/topbar.php';
    ?>
    <div class="px-10 py-8 max-w-[1600px] mx-auto space-y-8">
      <nav class="flex flex-wrap items-center gap-2 text-sm text-on-surface-variant">
        <a class="font-medium text-primary hover:underline" href="submissions.php">My submissions</a>
        <span class="material-symbols-outlined text-base text-slate-400">chevron_right</span>
        <span class="text-on-surface font-medium">Revision requested</span>
        <span class="font-mono text-xs text-slate-500 ml-1">OSS-2025-008</span>
      </nav>

      <div class="bg-surface-container-low rounded-xl p-8 md:p-10 relative overflow-hidden border border-slate-200/60">
        <div class="absolute top-0 right-0 p-8 opacity-10 pointer-events-none">
          <span class="material-symbols-outlined text-[120px]">rate_review</span>
        </div>
        <div class="relative z-10 max-w-2xl">
          <div class="flex items-center gap-3 mb-4">
            <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-[10px] font-bold tracking-widest uppercase">Revision required</span>
          </div>
          <h1 class="text-3xl md:text-[2rem] font-extrabold leading-tight text-on-surface mb-4 font-headline tracking-tight">GAD Director feedback</h1>
          <p class="text-lg text-on-surface-variant font-medium leading-relaxed">
            Adjust the sex-disaggregated indicators for participants to reflect actual college distribution and ensure budget lines align with the BSU GAD plan for OSS.
          </p>
        </div>
      </div>

      <form class="space-y-10" action="#" method="post">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
          <div class="md:col-span-1">
            <h3 class="text-xl font-bold text-primary mb-2">General information</h3>
            <p class="text-sm text-on-surface-variant leading-relaxed">Update administrative details for this activity design.</p>
          </div>
          <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="col-span-2">
              <label class="block text-[10px] font-bold uppercase tracking-widest text-outline mb-2">Project title</label>
              <input class="w-full bg-surface-container-lowest border border-slate-200/60 rounded-xl p-4 text-on-surface focus:ring-2 focus:ring-primary shadow-sm transition-all" type="text" value="Continuous conduct of GAD responsive leadership training for student FY 2026" />
            </div>
            <div>
              <label class="block text-[10px] font-bold uppercase tracking-widest text-outline mb-2">Target date</label>
              <input class="w-full bg-surface-container-lowest border border-slate-200/60 rounded-xl p-4 text-on-surface focus:ring-2 focus:ring-primary shadow-sm transition-all" type="date" value="2026-03-15" />
            </div>
            <div>
              <label class="block text-[10px] font-bold uppercase tracking-widest text-outline mb-2">Venue</label>
              <input class="w-full bg-surface-container-lowest border border-slate-200/60 rounded-xl p-4 text-on-surface focus:ring-2 focus:ring-primary shadow-sm transition-all" type="text" value="BSU Main Campus, OSS Conference Hall" />
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
          <div class="md:col-span-1">
            <h3 class="text-xl font-bold text-primary mb-2">HGDG scoreboard</h3>
            <p class="text-sm text-on-surface-variant leading-relaxed">Harmonized Gender and Development Guidelines metrics.</p>
          </div>
          <div class="md:col-span-2">
            <div class="bg-surface-container-lowest rounded-xl p-8 shadow-sm max-w-md border border-slate-200/60">
              <label class="block text-[10px] font-bold uppercase tracking-widest text-outline mb-4">Self-assessment HGDG score</label>
              <div class="flex items-center gap-6">
                <input class="w-32 bg-surface-container-low border border-slate-200/60 rounded-xl p-6 text-3xl font-black text-primary text-center focus:ring-2 focus:ring-primary transition-all" step="0.1" type="number" value="18.5" />
                <div>
                  <p class="text-xs font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full inline-block mb-1">Promising GAD</p>
                  <p class="text-[11px] text-on-surface-variant">Indicates solid gender responsiveness for this student-focused proposal.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
          <div class="md:col-span-1">
            <h3 class="text-xl font-bold text-primary mb-2">Supporting file</h3>
            <p class="text-sm text-on-surface-variant leading-relaxed">Upload the corrected PDF for this activity design.</p>
          </div>
          <div class="md:col-span-2">
            <div class="relative group">
              <input class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" type="file" accept=".pdf" />
              <div class="border-2 border-dashed border-outline-variant/30 rounded-xl p-16 flex flex-col items-center justify-center bg-surface-container-low group-hover:bg-surface-container-high transition-all duration-300">
                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                  <span class="material-symbols-outlined text-primary text-4xl">cloud_upload</span>
                </div>
                <h4 class="text-lg font-bold text-on-surface mb-2">Upload revised PDF</h4>
                <p class="text-sm text-outline mb-6">Drag and drop or click to browse</p>
                <span class="px-4 py-2 bg-white rounded-lg text-xs font-semibold shadow-sm flex items-center gap-2 border border-slate-100">
                  <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                  OSS_GAD_Orientation_Proposal_v2.pdf
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="pt-10 border-t border-outline-variant/10 flex flex-col sm:flex-row items-center justify-between gap-6">
          <a class="text-sm font-bold text-on-surface-variant hover:text-primary" href="submissions.php">← Back to submissions</a>
          <div class="flex flex-col sm:flex-row items-center gap-6">
            <button class="px-8 py-3 text-primary font-bold hover:bg-primary/5 rounded-xl transition-all active:scale-95" type="button">Save draft</button>
            <button class="px-10 py-4 oss-gradient text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95 flex items-center gap-3" type="submit">
              <span class="material-symbols-outlined">send</span>
              Resubmit for approval
            </button>
          </div>
        </div>
      </form>
    </div>
  </main>
</body>
</html>

<style>
  .oss-gradient { background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 60%, #8b5cf6 100%); }
</style>