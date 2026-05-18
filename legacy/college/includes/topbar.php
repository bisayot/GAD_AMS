<?php
require_once __DIR__ . '/config.php';
if (!isset($topbar_title)) {
    $topbar_title = $gad_portal_label;
}
$search_placeholder = $topbar_search_placeholder ?? 'Search…';
$show_topbar_search = isset($topbar_show_search) ? (bool) $topbar_show_search : true;
?>
<header class="fixed top-0 left-64 right-0 h-20 bg-white/90 backdrop-blur-md z-40 flex items-center justify-between px-10 border-b border-slate-200/40">

  <div>
    <h2 class="text-2xl font-extrabold text-[#002c5c] tracking-tighter leading-none">Gender and Development - Information & Management System</h2>

    <div class="flex items-center gap-2">
      <span class="text-[11px] uppercase tracking-[0.1em] text-[#3b3b3b] font-semibold">Office of Student Services</span>
    </div>
  </div>
      
      <div class="flex items-center gap-10 sm:gap-3">
        <button type="button" class="p-2 text-slate-600 hover:bg-violet-50 rounded-full transition-colors active:scale-95 duration-200" aria-label="Notifications" id="topbarNotificationBtn">
          <span class="material-symbols-outlined">notifications</span>
        </button>
        <button type="button" class="p-2 text-slate-600 hover:bg-violet-50 rounded-full transition-colors active:scale-95 duration-200" aria-label="Settings">
          <span class="material-symbols-outlined">settings</span>
        </button>
        <div class="relative group">
          <div class="h-8 w-8 rounded-full bg-primary-container flex items-center justify-center overflow-hidden ml-1 border border-green-200/50 cursor-pointer" title="Account">
            <span class="text-[10px] font-bold text-white select-none"><?php echo htmlspecialchars($gad_college_abbr); ?></span>
          </div>
          <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border hidden group-hover:block z-50">
            <a href="profile.php" class="flex items-center gap-3 px-4 py-3 hover:bg-violet-50 transition-colors">
              <span class="material-symbols-outlined text-sm">account_circle</span>
              <span class="text-sm">My Profile</span>
            </a>
            <a href="settings.php" class="flex items-center gap-3 px-4 py-3 hover:bg-violet-50 transition-colors">
              <span class="material-symbols-outlined text-sm">settings</span>
              <span class="text-sm">Settings</span>
            </a>
            <hr class="my-1">
            <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 hover:bg-violet-50 text-red-600 transition-colors">
              <span class="material-symbols-outlined text-sm">logout</span>
              <span class="text-sm">Logout</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- Notification Panel -->
<div id="topbarNotificationPanel" class="fixed top-16 right-10 w-80 bg-white rounded-xl shadow-xl border z-50 hidden">
  <div class="p-4 border-b">
    <h3 class="font-bold">Notifications</h3>
  </div>
  <div class="max-h-96 overflow-y-auto">
    <div class="p-4 hover:bg-violet-50 border-b cursor-pointer">
      <p class="text-sm font-medium">Your activity design is under review</p>
      <p class="text-xs text-slate-400">College of Agriculture · 5 min ago</p>
    </div>
    <div class="p-4 hover:bg-violet-50 border-b cursor-pointer bg-amber-50">
      <p class="text-sm font-medium">Revision required: Agri-Extension Program</p>
      <p class="text-xs text-slate-400">Yesterday</p>
    </div>
    <div class="p-4 hover:bg-violet-50 cursor-pointer">
      <p class="text-sm font-medium">Accomplishment report verified</p>
      <p class="text-xs text-slate-400">Mar 25, 2026</p>
    </div>
  </div>
  <div class="p-3 border-t bg-slate-50 text-center">
    <button class="text-xs text-violet-700 font-bold">View all notifications</button>
  </div>
</div>

<script>
  const topbarNotifBtn = document.getElementById('topbarNotificationBtn');
  const topbarNotifPanel = document.getElementById('topbarNotificationPanel');
  
  if (topbarNotifBtn && topbarNotifPanel) {
    topbarNotifBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      topbarNotifPanel.classList.toggle('hidden');
    });
    
    document.addEventListener('click', function(e) {
      if (!topbarNotifPanel.contains(e.target) && !topbarNotifBtn.contains(e.target)) {
        topbarNotifPanel.classList.add('hidden');
      }
    });
  }
</script>