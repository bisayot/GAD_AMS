<?php
// edit_design.php - Edit Activity Design (TWG Revision)
require_once '../auth.php';
require_once 'includes/config.php';
require_role(['college', 'gad_staff']);
$page_title = 'Edit Activity Design';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Activity Design | GAD-IMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f8f9fb;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin-top: 80px;
        }

        @import url('https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap');

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cfc2d544;
            border-radius: 10px;
        }

        /* Layout */
        .ml-64 {
            margin-left: 256px;
        }
        .min-h-screen {
            min-height: 100vh;
        }
        .p-10 {
            padding: 40px;
        }
        .flex {
            display: flex;
        }
        .gap-8 {
            gap: 32px;
        }
        .flex-06 {
            flex: 0.6;
        }
        .flex-04 {
            flex: 0.4;
        }

        /* Card Styles */
        .bg-white {
            background-color: white;
        }
        .rounded-xl {
            border-radius: 12px;
        }
        .flex-col {
            flex-direction: column;
        }
        .overflow-hidden {
            overflow: hidden;
        }
        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .border {
            border: 1px solid #e2e8f0;
        }

        /* Title Block */
        .title-block {
            padding: 32px 32px 20px 32px;
            border-bottom: 1px solid #e2e8f0;
            background-color: rgba(63, 101, 22, 0.05);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: rgba(81, 112, 255, 0.1);
            color: #5170ff;
            padding: 4px 12px;
            border-radius: 9999px;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            background-color: #5170ff;
            border-radius: 9999px;
        }
        .status-text {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .control-number {
            font-size: 11px;
            font-weight: 700;
            color: #3b3b3b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-left: 12px;
        }

        .report-title {
            font-family: 'Inter', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: #1a1a2e;
            line-height: 1.25;
            margin-bottom: 16px;
            margin-top: 16px;
        }

        .info-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #3b3b3b;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
        }

        /* Section Styles */
        .section-card {
            background-color: #f8fafc;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .section-title h3 {
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #3b3b3b;
        }

        /* Grid */
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        /* Form Elements */
        input, textarea, select {
            font-family: 'Inter', sans-serif;
            width: 100%;
            padding: 12px 16px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            color: #1a1a2e;
            transition: all 0.2s ease;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #990dd1;
            box-shadow: 0 0 0 3px rgba(153, 13, 209, 0.1);
        }
        label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #3b3b3b;
            margin-bottom: 6px;
        }

        /* Revision Alert */
        .revision-alert {
            background-color: #fffbeb;
            border-left: 4px solid #f57c00;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .revision-icon {
            font-size: 22px;
        }
        .revision-title {
            font-weight: 700;
            color: #1a1a2e;
            font-size: 14px;
            margin-bottom: 4px;
        }
        .revision-message {
            font-size: 13px;
            color: #6c757d;
        }
        .revision-feedback {
            background-color: #fff3e0;
            padding: 10px 12px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 13px;
            color: #f57c00;
            font-weight: 500;
        }

        /* Budget Breakdown Table */
        .budget-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .budget-input {
            width: 150px;
            padding: 6px 10px;
            font-size: 13px;
            text-align: right;
        }
        .budget-total {
            display: flex;
            justify-content: space-between;
            padding: 16px 0 8px 0;
        }
        .budget-label {
            font-size: 14px;
            color: #475569;
        }
        .budget-amount {
            font-size: 14px;
            font-weight: 500;
            color: #000;
        }
        .budget-total-label {
            font-size: 14px;
            font-weight: 700;
            color: #000;
        }
        .budget-total-amount {
            font-size: 14px;
            font-weight: 700;
            color: #3f6516;
        }

        /* Document Upload */
        .doc-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background-color: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .upload-area {
            border: 2px dashed #cfc2d5;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .upload-area:hover {
            border-color: #990dd1;
            background-color: rgba(153, 13, 209, 0.05);
        }

        /* Right Sidebar */
        .sidebar-card {
            background-color: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }
        .sticky-sidebar {
            position: sticky;
            top: 80px;
            align-self: flex-start;
            max-height: calc(100vh - 96px);
            overflow-y: auto;
            padding-bottom: 16px;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(153, 13, 209, 0.3);
        }
        .btn-secondary {
            background-color: #f1f5f9;
            color: #475569;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-secondary:hover {
            background-color: #e2e8f0;
        }
        .btn-outline {
            background: transparent;
            border: 1px solid #e2e8f0;
            color: #6c757d;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-outline:hover {
            border-color: #990dd1;
            color: #990dd1;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .items-center {
            align-items: center;
        }
        .gap-3 {
            gap: 12px;
        }
        .mb-4 {
            margin-bottom: 16px;
        }
        .mt-1 {
            margin-top: 4px;
        }
        .mt-2 {
            margin-top: 8px;
        }
        .mt-4 {
            margin-top: 16px;
        }
        .mb-2 {
            margin-bottom: 8px;
        }
        .space-y-4 > * + * {
            margin-top: 16px;
        }
        .text-sm {
            font-size: 14px;
        }
        .text-xl {
            font-size: 20px;
        }
        .font-bold {
            font-weight: 700;
        }
        .font-medium {
            font-weight: 500;
        }
        .font-semibold {
            font-weight: 600;
        }
        .text-000 {
            color: #000;
        }
        .text-3b3b3b {
            color: #3b3b3b;
        }
        .text-990dd1 {
            color: #990dd1;
        }
        .w-full {
            width: 100%;
        }
        .cursor-pointer {
            cursor: pointer;
        }

        /* Header Styles */
        .staff-header {
            position: fixed;
            top: 0;
            left: 256px;
            right: 0;
            height: 80px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            z-index: 40;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            border-bottom: 1px solid rgba(153, 13, 209, 0.1);
        }
        .header-title {
            font-size: 1.6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1a1a2e 0%, #990dd1 100%);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .header-subtitle {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #6c757d;
            font-weight: 600;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            bottom: 16px;
            left: 256px;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: rgba(59, 59, 59, 0.2);
            font-weight: 500;
            pointer-events: none;
            z-index: 40;
        }

        .relative { position: relative; }
        .absolute { position: absolute; }
        .inset-0 { top: 0; left: 0; right: 0; bottom: 0; }
        .opacity-0 { opacity: 0; }

        @media (max-width: 768px) {
            .ml-64 { margin-left: 0; }
            .staff-header { left: 0; padding: 0 1rem; }
            .p-10 { padding: 1rem; }
            .flex { flex-direction: column; }
        }
    </style>
</head>
<body>
    <header class="staff-header">
        <div>
            <h2 class="header-title">Edit Activity Design</h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="header-subtitle">Gender and Development - Information Management System (GAD-IMS)</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative">
                <div class="w-10 h-10 bg-gradient-to-r from-[#990dd1]/10 to-[#b979cc]/10 rounded-full flex items-center justify-center">
                    <span style="font-size: 1.2rem;">👤</span>
                </div>
            </div>
        </div>
    </header>

    <?php include 'includes/sidebar_twg.php'; ?>

    <main class="ml-64 min-h-screen">
        <div class="p-10 flex gap-8">
            <!-- Main Edit Form Column -->
            <section class="flex-06 bg-white rounded-xl flex flex-col overflow-hidden shadow-sm border">
                <div class="title-block">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="status-badge">
                            <div class="status-dot"></div>
                            <span class="status-text">For Revision</span>
                        </div>
                        <span class="control-number">BSU-GAD-2026-003</span>
                    </div>
                    <textarea rows="2" class="report-title w-full border-0 bg-transparent resize-none font-bold text-2xl p-0 focus:ring-0" style="font-family: 'Inter', sans-serif;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</textarea>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Date Submitted</span>
                            <span class="info-value">Jan 15, 2026</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Category</span>
                            <span class="info-value">Activity Design</span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar p-8 space-y-6">
                    <!-- Revision Alert -->
                    <div class="revision-alert">
                        <div class="revision-icon">✏️</div>
                        <div style="flex: 1;">
                            <p class="revision-title">Revision Required</p>
                            <p class="revision-message">Please address the following feedback from the director to proceed with approval.</p>
                            <div class="revision-feedback">
                                "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
                            </div>
                        </div>
                    </div>

                    <form action="twg_submitted_list.php" method="POST" enctype="multipart/form-data">
                        <!-- Activity Description -->
                        <div class="section-card">
                            <div class="section-title">
                                <span class="text-xl">📄</span>
                                <h3>Activity Description</h3>
                            </div>
                            <textarea name="description" rows="4" class="w-full">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</textarea>
                        </div>

                        <!-- Schedule & Venue -->
                        <div class="section-card">
                            <div class="section-title">
                                <span class="text-xl">📅</span>
                                <h3>Schedule & Venue</h3>
                            </div>
                            <div class="grid-2">
                                <div>
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" value="2026-02-20">
                                </div>
                                <div>
                                    <label>End Date</label>
                                    <input type="date" name="end_date" value="2026-02-22">
                                </div>
                                <div>
                                    <label>Start Time</label>
                                    <input type="time" name="start_time" value="08:00">
                                </div>
                                <div>
                                    <label>End Time</label>
                                    <input type="time" name="end_time" value="17:00">
                                </div>
                                <div class="col-span-2">
                                    <label>Venue</label>
                                    <input type="text" name="venue" value="Lorem ipsum Convention Center, Dolor Sit Amet Hall">
                                </div>
                            </div>
                        </div>

                        <!-- Budget & Participants -->
                        <div class="section-card">
                            <div class="section-title">
                                <span class="text-xl">💰</span>
                                <h3>Budget & Participants</h3>
                            </div>
                            <div class="grid-2">
                                <div>
                                    <label>Total Proposed Budget (PHP)</label>
                                    <input type="number" name="proposed_budget" step="0.01" value="97500">
                                </div>
                                <div>
                                    <label>Target Participants</label>
                                    <input type="number" name="target_participants" value="35">
                                </div>
                                <div>
                                    <label>Male Target</label>
                                    <input type="number" name="male_target" value="15">
                                </div>
                                <div>
                                    <label>Female Target</label>
                                    <input type="number" name="female_target" value="20">
                                </div>
                            </div>
                        </div>

                        <!-- Budget Breakdown -->
                        <div class="section-card">
                            <div class="section-title">
                                <span class="text-xl">🧾</span>
                                <h3>Budget Breakdown</h3>
                            </div>
                            <div class="budget-row">
                                <span class="budget-label">Training Materials</span>
                                <input type="number" class="budget-input" value="25000" step="0.01">
                            </div>
                            <div class="budget-row">
                                <span class="budget-label">Honorarium for Resource Speakers (2 persons)</span>
                                <input type="number" class="budget-input" value="20000" step="0.01">
                            </div>
                            <div class="budget-row">
                                <span class="budget-label">Meals & Snacks (35 pax x 3 days)</span>
                                <input type="number" class="budget-input" value="31500" step="0.01">
                            </div>
                            <div class="budget-row">
                                <span class="budget-label">Certificates & Printing</span>
                                <input type="number" class="budget-input" value="5000" step="0.01">
                            </div>
                            <div class="budget-row">
                                <span class="budget-label">Miscellaneous / Contingency</span>
                                <input type="number" class="budget-input" value="16000" step="0.01">
                            </div>
                            <div class="budget-total">
                                <span class="budget-total-label">TOTAL</span>
                                <span class="budget-total-amount">₱97,500.00</span>
                            </div>
                        </div>

                        <!-- Upload Revised Document -->
                        <div class="section-card">
                            <div class="section-title">
                                <span class="text-xl">📎</span>
                                <h3>Upload Revised Document</h3>
                            </div>
                            <div class="doc-item">
                                <div class="flex items-center gap-3">
                                    <span style="font-size: 24px;">📄</span>
                                    <div>
                                        <p class="font-medium text-000">GST_Activity_Design_v1.pdf</p>
                                        <p class="text-xs text-3b3b3b">1.2 MB • Uploaded Jan 15, 2026</p>
                                    </div>
                                </div>
                                <button class="btn-outline">Preview</button>
                            </div>
                            <div class="upload-area mt-4 relative">
                                <input type="file" name="revised_design" accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <span style="font-size: 32px;">☁️</span>
                                <p class="font-medium text-990dd1 mt-2">Upload Revised Version</p>
                                <p class="text-xs text-3b3b3b mt-1">Click or drag to upload new PDF file</p>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex-between pt-4">
                            <a href="twg_submitted_list.php" class="btn-secondary">
                                ← Cancel
                            </a>
                            <button type="submit" class="btn-primary">
                                ✏️ Update Design
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Right Sidebar - Status & Info -->
            <section class="flex-04 sticky-sidebar">
                <div class="sidebar-card">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-[#5170ff]/10 rounded-full flex items-center justify-center">
                            <span style="font-size: 20px;">✏️</span>
                        </div>
                        <div>
                            <p class="font-bold text-[#5170ff] text-sm">For Revision</p>
                            <p class="text-xs text-3b3b3b">Updated on Feb 10, 2026</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="info-label">Control Number</label>
                            <p class="text-base font-mono font-bold text-990dd1 mt-1">BSU-GAD-2026-003</p>
                        </div>
                        <div>
                            <label class="info-label">Accomplishment Report Deadline</label>
                            <input type="date" class="w-full mt-1" value="2026-03-12">
                        </div>
                    </div>
                </div>

                <div class="sidebar-card">
                    <h3 class="font-bold text-sm uppercase tracking-widest text-3b3b3b mb-4">Submission History</h3>
                    <div class="relative flex flex-col gap-4">
                        <div class="relative pl-6">
                            <div class="absolute left-0 top-1 w-3 h-3 bg-[#990dd1] rounded-full"></div>
                            <p class="font-bold text-sm text-000">Submitted for Review</p>
                            <p class="text-xs text-3b3b3b mt-1">Jan 15, 2026 • 10:23 AM</p>
                        </div>
                        <div class="relative pl-6">
                            <div class="absolute left-0 top-1 w-3 h-3 bg-[#f57c00] rounded-full"></div>
                            <p class="font-bold text-sm text-[#f57c00]">Revision Requested</p>
                            <p class="text-xs text-3b3b3b mt-1">Feb 10, 2026 • 09:45 AM</p>
                        </div>
                        <div class="relative pl-6 opacity-50">
                            <div class="absolute left-0 top-1 w-3 h-3 bg-gray-300 rounded-full"></div>
                            <p class="font-bold text-sm text-3b3b3b">Resubmitted for Review</p>
                            <p class="text-xs text-3b3b3b mt-1">Pending</p>
                        </div>
                    </div>
                </div>

                <div class="bg-[#e3f2fd] p-4 rounded-xl border border-[#5170ff]/20 flex gap-3">
                    <span style="font-size: 22px;">ℹ️</span>
                    <div>
                        <p class="font-bold text-[#1565c0] text-xs uppercase tracking-wider">Revision Guidelines</p>
                        <p class="text-xs text-3b3b3b mt-1">Please address all feedback before resubmitting. Attach the revised PDF document above.</p>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <div class="watermark">Benguet State University - Gender and Development Information Management System | GAD-IMS v1.0</div>
</body>
</html>