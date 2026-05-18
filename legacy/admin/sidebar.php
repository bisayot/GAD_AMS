<?php
// includes/sidebar_staff.php - Unified Staff Sidebar with New Menu Structure
?>
<style>
  /* ========== LOCAL CSS (No external dependencies) ========== */
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  /* Sidebar base with gradient background */
  .gad-sidebar {
    position: fixed;
    left: 0;
    top: 0;
    height: 100%;
    width: 256px;
    background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    display: flex;
    flex-direction: column;
    padding: 24px 0;
    z-index: 50;
    box-shadow: 4px 0 20px rgba(0,0,0,0.15);
  }
  
  /* Logo section */
  .sidebar-logo {
    padding: 0 12px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border-bottom: 1px solid rgba(255,255,255,0.15);
    padding-bottom: 16px;
    flex-shrink: 0;
  }
  .sidebar-logo img {
    height: 48px;
    width: auto;
    object-fit: contain;
  }
  .logo-text {
    text-align: left;
  }
  .logo-subtitle {
    font-size: 10px;
    font-weight: 700;
    color: #b979cc;
    letter-spacing: -0.025em;
    line-height: 1;
  }
  .logo-title {
    font-size: 20px;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.05em;
    line-height: 1;
  }
  .logo-dept {
    font-size: 7px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #94a3b8;
    font-weight: 500;
    margin-top: 2px;
  }
  
  /* Navigation */
  .sidebar-nav {
    flex: 1;
    overflow-y: auto;
    padding: 0 12px;
  }
  .sidebar-nav::-webkit-scrollbar {
    width: 4px;
  }
  .sidebar-nav::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.05);
    border-radius: 10px;
  }
  .sidebar-nav::-webkit-scrollbar-thumb {
    background: #b979cc;
    border-radius: 10px;
  }
  .sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: #990dd1;
  }
  
  .nav-section {
    margin-bottom: 16px;
  }
  .nav-section-title {
    padding: 0 12px;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #b979cc;
  }
  .nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 12px;
    color: #cbd5e1;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
    margin-bottom: 4px;
  }
  .nav-link:hover {
    background-color: rgba(185,121,204,0.2);
    color: #ffffff;
  }
  .nav-link-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .nav-link-icon img {
    width: 18px;
    height: 18px;
    filter: brightness(0) invert(0.7);
    transition: filter 0.2s ease;
  }
  .nav-link:hover .nav-link-icon img {
    filter: brightness(0) invert(1);
  }
  
  .subnav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 12px;
    border-radius: 8px;
    color: #cbd5e1;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.2s ease;
    margin-left: 8px;
  }
  .subnav-link:hover {
    background-color: rgba(185,121,204,0.15);
    color: #ffffff;
  }
  .subnav-icon {
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .subnav-icon img {
    width: 14px;
    height: 14px;
    filter: brightness(0) invert(0.5);
    transition: filter 0.2s ease;
  }
  .subnav-link:hover .subnav-icon img {
    filter: brightness(0) invert(1);
  }
  
  /* Footer section */
  .sidebar-footer {
    padding: 0 16px;
    margin-top: auto;
    padding-top: 16px;
    border-top: 1px solid rgba(255,255,255,0.1);
    flex-shrink: 0;
  }
  .footer-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 12px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s ease;
    margin-bottom: 8px;
  }
  .footer-link:hover {
    background-color: rgba(185,121,204,0.15);
    color: #ffffff;
  }
  .footer-link:hover .nav-link-icon img {
    filter: brightness(0) invert(1);
  }
  
  /* Modal */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 100;
    display: none;
  }
  .modal-overlay.show {
    display: flex;
  }
  .modal-container {
    background-color: white;
    border-radius: 16px;
    padding: 32px;
    max-width: 448px;
    width: 90%;
    margin: 0 16px;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
  }
  .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
  }
  .modal-title {
    font-size: 20px;
    font-weight: 700;
    color: #000;
  }
  .modal-close {
    background: none;
    border: none;
    font-size: 20px;
    color: #3b3b3b;
    cursor: pointer;
  }
  .modal-close:hover {
    color: #990dd1;
  }
  .modal-text {
    color: #3b3b3b;
    margin-bottom: 24px;
  }
  .modal-option {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s ease;
    margin-bottom: 12px;
  }
  .modal-option:hover {
    background-color: rgba(185,121,204,0.1);
  }
  .modal-option-icon {
    width: 48px;
    height: 48px;
    background-color: rgba(185,121,204,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
  }
  .modal-option-content {
    flex: 1;
  }
  .modal-option-title {
    font-weight: 700;
    color: #000;
  }
  .modal-option-desc {
    font-size: 11px;
    color: #3b3b3b;
  }
  .modal-option-arrow {
    color: #3b3b3b;
    font-size: 20px;
  }
  .modal-option:hover .modal-option-arrow {
    color: #990dd1;
  }
  
  /* Active link styling */
  .nav-link.active {
    background-color: #990dd1;
    color: #ffffff;
  }
  .nav-link.active .nav-link-icon img {
    filter: brightness(0) invert(1);
  }
  .subnav-link.active {
    background-color: rgba(185,121,204,0.25);
    color: #ffffff;
  }
  .subnav-link.active .subnav-icon img {
    filter: brightness(0) invert(1);
  }
  
  /* Body font */
  body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background-color: #f1f5f9;
  }
</style>

<aside class="gad-sidebar">
  <div class="sidebar-logo">
    <img src="bsulogo.png" alt="BSU Logo" />
    <div class="logo-text">
      <div class="logo-subtitle">Benguet State University</div>
      <div class="logo-title">GAD-IMS</div>
      <div class="logo-dept">Gender and Development Office</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <a class="nav-link" href="dashboard.php">
      <span class="nav-link-icon"><img src="dashboard.png" alt="Dashboard" /></span>
      <span>Dashboard</span>
    </a>

    <div class="nav-section">
      <div class="nav-section-title">GAD PROGRAMS, ACTIVITIES, PROJECTS</div>
      <div>
        <a class="subnav-link" href="twg_submission.php">
          <span class="subnav-icon"><img src="folder.png" alt="Folder" /></span>
          <span>Submitted List</span>
        </a>
        <a class="subnav-link" href="list_and_filters.php">
          <span class="subnav-icon"><img src="document.png" alt="Document" /></span>
          <span>Activity Design List</span>
        </a>
        <a class="subnav-link" href="list_acc_filters.php">
          <span class="subnav-icon"><img src="document.png" alt="Document" /></span>
          <span>Accomplishment Report List</span>
        </a>
        <a class="subnav-link" href="archive_list.php">
          <span class="subnav-icon"><img src="archive.png" alt="Archive" /></span>
          <span>Archive</span>
        </a>
      </div>
    </div>

    <a class="nav-link" href="mandates.php">
      <span class="nav-link-icon"><img src="government.png" alt="Government" /></span>
      <span>Mandates Management</span>
    </a>

    <a class="nav-link" href="annual_report.php">
      <span class="nav-link-icon"><img src="graph.png" alt="Graph" /></span>
      <span>Report Monitoring</span>
    </a>

    <a class="nav-link" href="budget.php">
      <span class="nav-link-icon"><img src="wallet.png" alt="Wallet" /></span>
      <span>Budget Monitoring</span>
    </a>

    

    <a class="nav-link" href="user_manual.php">
      <span class="nav-link-icon"><img src="chat.png" alt="Help" /></span>
      <span>User Manual</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <a class="footer-link" href="#">
      <span class="nav-link-icon"><img src="chat.png" alt="Help" /></span>
      <span>Help & Support</span>
    </a>
    <a class="footer-link" href="../logout.php">
      <span class="nav-link-icon"><img src="exit.png" alt="Logout" /></span>
      <span>Log Out</span>
    </a>
  </div>
</aside>

<!-- Modal -->
<div id="staffSelectionModal" class="modal-overlay">
  <div class="modal-container">
    <div class="modal-header">
      <h3 class="modal-title">New Submission</h3>
      <button onclick="closeStaffModal()" class="modal-close">✕</button>
    </div>
    <p class="modal-text">What would you like to submit?</p>
    <div>
      <a href="act_design_submit_step1.php" class="modal-option">
        <div class="modal-option-icon">📄</div>
        <div class="modal-option-content">
          <p class="modal-option-title">Activity Design</p>
          <p class="modal-option-desc">Submit a new activity design for approval</p>
        </div>
        <span class="modal-option-arrow">→</span>
      </a>
      <a href="accomplishment_submit.php" class="modal-option">
        <div class="modal-option-icon">📊</div>
        <div class="modal-option-content">
          <p class="modal-option-title">Accomplishment Report</p>
          <p class="modal-option-desc">Submit accomplishment report for approved activity</p>
        </div>
        <span class="modal-option-arrow">→</span>
      </a>
    </div>
  </div>
</div>

<script>
  function openStaffModal() {
    const modal = document.getElementById('staffSelectionModal');
    if (modal) {
      modal.classList.add('show');
    }
  }

  function closeStaffModal() {
    const modal = document.getElementById('staffSelectionModal');
    if (modal) {
      modal.classList.remove('show');
    }
  }

  const staffAddBtn = document.getElementById('staffSidebarAddBtn');
  if (staffAddBtn) {
    staffAddBtn.addEventListener('click', function(e) {
      e.preventDefault();
      openStaffModal();
    });
  }

  const staffModal = document.getElementById('staffSelectionModal');
  if (staffModal) {
    staffModal.addEventListener('click', function(e) {
      if (e.target === this) closeStaffModal();
    });
  }

  // Set active link based on current page
  const currentPage = window.location.pathname.split('/').pop();
  document.querySelectorAll('.nav-link, .subnav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPage) {
      link.classList.add('active');
    }
  });
</script>