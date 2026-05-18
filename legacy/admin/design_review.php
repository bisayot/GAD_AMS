<?php
// design_review.php - Review Activity Design (with assessment form and cancel option)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Review Activity Design | GAD-IMS</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background-color: #f8f9fb;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .material-symbols-outlined {
        display: inline-block;
        font-size: 24px;
        font-family: monospace;
    }

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

    .pl-64 {
        padding-left: 256px;
    }

    .pt-16 {
        padding-top: 64px;
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

    .p-8 {
        padding: 32px;
    }

    .pb-4 {
        padding-bottom: 16px;
    }

    .border-b {
        border-bottom: 1px solid #e2e8f0;
    }

    .items-center {
        align-items: center;
    }

    .gap-2 {
        gap: 8px;
    }

    .bg-ecd224\/20 {
        background-color: rgba(236, 210, 36, 0.2);
    }

    .text-ecd224 {
        color: #ecd224;
    }

    .px-3 {
        padding-left: 12px;
        padding-right: 12px;
    }

    .py-1 {
        padding-top: 4px;
        padding-bottom: 4px;
    }

    .rounded-full {
        border-radius: 9999px;
    }

    .w-2 {
        width: 8px;
    }

    .h-2 {
        height: 8px;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: .5;
        }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .text-9px {
        font-size: 9px;
    }

    .font-black {
        font-weight: 900;
    }

    .uppercase {
        text-transform: uppercase;
    }

    .tracking-widest {
        letter-spacing: 0.1em;
    }

    .text-3b3b3b {
        color: #3b3b3b;
    }

    .font-bold {
        font-weight: 700;
    }

    .font-serif {
        font-family: 'Times New Roman', serif;
    }

    .text-28px {
        font-size: 28px;
    }

    .text-000 {
        color: #000;
    }

    .leading-tight {
        line-height: 1.25;
    }

    .mb-4 {
        margin-bottom: 16px;
    }

    .flex-wrap {
        flex-wrap: wrap;
    }

    .gap-6 {
        gap: 24px;
    }

    .pt-4 {
        padding-top: 16px;
    }

    .border-t {
        border-top: 1px solid #e2e8f0;
    }

    .border-slate-100 {
        border-color: #f1f5f9;
    }

    .flex-col {
        flex-direction: column;
    }

    .mb-1 {
        margin-bottom: 4px;
    }

    .text-11px {
        font-size: 11px;
    }

    .font-semibold {
        font-weight: 600;
    }

    .text-990dd1 {
        color: #990dd1;
    }

    .font-medium {
        font-weight: 500;
    }

    .flex-1 {
        flex: 1;
    }

    .overflow-y-auto {
        overflow-y: auto;
    }

    .space-y-6>*+* {
        margin-top: 24px;
    }

    .bg-slate-50 {
        background-color: #f8fafc;
    }

    .p-5 {
        padding: 20px;
    }

    .opacity-75 {
        opacity: 0.75;
    }

    .grid {
        display: grid;
    }

    .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .gap-4 {
        gap: 16px;
    }

    .text-xl {
        font-size: 20px;
    }

    .text-lg {
        font-size: 18px;
    }

    .mt-1 {
        margin-top: 4px;
    }

    .mb-2 {
        margin-bottom: 8px;
    }

    .justify-between {
        justify-content: space-between;
    }

    .gap-3 {
        gap: 12px;
    }

    .text-red-500 {
        color: #ef4444;
    }

    .sticky {
        position: sticky;
    }

    .top-20 {
        top: 80px;
    }

    .self-start {
        align-self: flex-start;
    }

    .max-h-calc {
        max-height: calc(100vh - 96px);
    }

    .pb-4 {
        padding-bottom: 16px;
    }

    .w-full {
        width: 100%;
    }

    .py-3 {
        padding-top: 12px;
        padding-bottom: 12px;
    }

    .bg-3f6516 {
        background-color: #3f6516;
    }

    .text-white {
        color: white;
    }

    .transition {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }

    .justify-center {
        justify-content: center;
    }

    .shadow-md {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .border-2 {
        border-width: 2px;
    }

    .border-red-500 {
        border-color: #ef4444;
    }

    .text-red-500 {
        color: #ef4444;
    }

    .hover\:bg-red-50:hover {
        background-color: #fef2f2;
    }

    .block {
        display: block;
    }

    .text-center {
        text-align: center;
    }

    .relative {
        position: relative;
    }

    .absolute {
        position: absolute;
    }

    .left-0 {
        left: 0;
    }

    .top-0\.5 {
        top: 2px;
    }

    .w-4 {
        width: 16px;
    }

    .h-4 {
        height: 16px;
    }

    .pl-7 {
        padding-left: 28px;
    }

    .bg-slate-300 {
        background-color: #cbd5e1;
    }

    .bg-blue-50 {
        background-color: #eff6ff;
    }

    .border-blue-200 {
        border-color: #bfdbfe;
    }

    .text-blue-600 {
        color: #2563eb;
    }

    .text-blue-700 {
        color: #1d4ed8;
    }

    .text-10px {
        font-size: 10px;
    }

    .hidden {
        display: none;
    }

    .fixed {
        position: fixed;
    }

    .inset-0 {
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }

    .bg-black\/50 {
        background-color: rgba(0, 0, 0, 0.5);
    }

    .z-200 {
        z-index: 200;
    }

    .max-w-md {
        max-width: 448px;
    }

    .mx-4 {
        margin-left: 16px;
        margin-right: 16px;
    }

    .shadow-2xl {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .rounded-2xl {
        border-radius: 16px;
    }

    .text-xl {
        font-size: 20px;
    }

    .text-red-600 {
        color: #dc2626;
    }

    .bg-red-600 {
        background-color: #dc2626;
    }

    .hover\:bg-red-700:hover {
        background-color: #b91c1c;
    }

    .border-slate-300 {
        border-color: #cbd5e1;
    }

    .hover\:bg-slate-50:hover {
        background-color: #f8fafc;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .rounded-lg {
        border-radius: 8px;
    }

    .p-6 {
        padding: 24px;
    }

    .space-y-5>*+* {
        margin-top: 20px;
    }

    .space-y-2>*+* {
        margin-top: 8px;
    }

    .space-y-3>*+* {
        margin-top: 12px;
    }

    .font-mono {
        font-family: monospace;
    }

    .focus\:ring-2:focus {
        outline: none;
        ring: 2px solid #990dd1;
    }

    .px-4 {
        padding-left: 16px;
        padding-right: 16px;
    }

    .py-2\.5 {
        padding-top: 10px;
        padding-bottom: 10px;
    }

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

    /* Revision Modal Styles */
    .revision-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        display: none;
    }

    .revision-modal.show {
        display: flex;
    }

    .revision-modal-content {
        background: white;
        border-radius: 24px;
        max-width: 600px;
        width: 90%;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }

    .revision-modal-header {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        padding: 20px 24px;
        color: white;
    }

    .revision-modal-header h3 {
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .revision-modal-body {
        padding: 24px;
    }

    .revision-modal-footer {
        padding: 16px 24px;
        background: #f8fafc;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        border-top: 1px solid #eef2f6;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #990dd1;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 13px;
        font-family: inherit;
        transition: all 0.2s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #990dd1;
        box-shadow: 0 0 0 3px rgba(153, 13, 209, 0.1);
    }

    .activity-title-preview {
        background: #f8fafc;
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .activity-title-preview p {
        font-size: 11px;
        color: #666;
        margin-bottom: 4px;
    }

    .activity-title-preview h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
    }

    .btn-send {
        background: #ecd224;
        color: #000;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-send:hover {
        background: #d4b91e;
    }

    .btn-cancel {
        background: #f0f2f5;
        color: #444;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #e6e9ef;
    }
    </style>
</head>

<body>
    <header class="staff-header">
        <div>
            <h2 class="header-title">Lorem ipsum Review</h2>
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
        <div class="notif-footer"><button>View all notifications</button></div>
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
            if (!notifPanel.contains(e.target) && !notifBtn.contains(e.target)) notifPanel.classList.remove(
                'show');
        });
    }
    </script>

    <?php include 'sidebar.php'; ?>

    <main style="padding-left: 256px; padding-top: 64px; min-height: 100vh;">
        <div style="padding: 40px; display: flex; gap: 32px;">

            <section
                style="flex: 0.6; background-color: white; border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                <div style="padding: 32px; padding-bottom: 16px; border-bottom: 1px solid #e2e8f0;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                        <div
                            style="display: flex; align-items: center; gap: 8px; background-color: rgba(236,210,36,0.2); color: #ecd224; padding: 4px 12px; border-radius: 9999px;">
                            <div
                                style="width: 8px; height: 8px; border-radius: 9999px; background-color: #ecd224; animation: pulse 2s cubic-bezier(0.4,0,0.6,1) infinite;">
                            </div>
                            <span
                                style="font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em;">Under
                                Review</span>
                        </div>
                        <span
                            style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase; letter-spacing: 0.1em;">GAD-2024-0812</span>
                    </div>
                    <h2
                        style="font-family: 'Times New Roman', serif; font-size: 28px; color: #000; line-height: 1.25; margin-bottom: 16px;">
                        Lorem Ipsum Dolor Sit Amet Consectetur Adipiscing Elit</h2>
                    <div
                        style="display: flex; flex-wrap: wrap; gap: 24px; padding-top: 16px; border-top: 1px solid #f1f5f9;">
                        <div style="display: flex; flex-direction: column;"><span
                                style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Submitted
                                By</span><span style="font-size: 11px; font-weight: 600; color: #990dd1;">Dr. Lorem
                                Ipsum</span></div>
                        <div style="display: flex; flex-direction: column;"><span
                                style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Office</span><span
                                style="font-size: 11px; font-weight: 500; color: #3b3b3b;">Lorem Ipsum Office</span>
                        </div>
                        <div style="display: flex; flex-direction: column;"><span
                                style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Email</span><span
                                style="font-size: 11px; font-weight: 500; color: #3b3b3b;">lorem.ipsum@bsu.edu.ph</span>
                        </div>
                        <div style="display: flex; flex-direction: column;"><span
                                style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Date
                                Submitted</span><span style="font-size: 11px; font-weight: 500; color: #3b3b3b;">Jan 15,
                                2026</span></div>
                        <div style="display: flex; flex-direction: column;"><span
                                style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; font-weight: 700; margin-bottom: 4px;">Category</span><span
                                style="font-size: 11px; font-weight: 500; color: #3b3b3b;">Activity Design</span></div>
                    </div>
                </div>

                <div
                    style="flex: 1; overflow-y: auto; padding: 32px; display: flex; flex-direction: column; gap: 24px;">
                    <!-- <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px; opacity: 0.75;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;"><span style="font-size: 24px;">📝</span><h3 style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b;">Activity Title</h3></div>
                        <p style="font-size: 11px; color: #3b3b3b;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div> -->

                    <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px; opacity: 0.75;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;"><span
                                style="font-size: 24px;">📅</span>
                            <h3
                                style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b;">
                                Proposed Schedule & Venue</h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;">
                            <div><label
                                    style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">Proposed
                                    Dates</label>
                                <p style="font-size: 11px; color: #3b3b3b; margin-top: 4px;">Feb 20 – Feb 22, 2026</p>
                            </div>
                            <div><label
                                    style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">Venue</label>
                                <p style="font-size: 11px; color: #3b3b3b; margin-top: 4px;">Lorem Ipsum Convention
                                    Center, Dolor Sit Amet Hall</p>
                            </div>
                        </div>
                    </div>

                    <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px; opacity: 0.75;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;"><span
                                style="font-size: 24px;">💰</span>
                            <h3
                                style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b;">
                                Proposed Budget</h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;">
                            <div><label
                                    style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">Proposed
                                    Budget</label>
                                <p style="font-size: 20px; font-weight: 700; color: #3b3b3b;">₱125,000.00</p>
                            </div>
                            <div><label
                                    style="font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase;">Expected
                                    Participants</label>
                                <p style="font-size: 18px; font-weight: 700; color: #3b3b3b; margin-top: 4px;">50
                                    participants</p>
                            </div>
                        </div>
                    </div>

                    <div style="background-color: #f8fafc; border-radius: 12px; padding: 20px;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;"><span
                                style="font-size: 24px;">📎</span>
                            <h3
                                style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #990dd1;">
                                Uploaded Documents</h3>
                        </div>
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background-color: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <div style="display: flex; align-items: center; gap: 12px;"><span
                                    style="font-size: 24px; color: #ef4444;">📄</span>
                                <div>
                                    <p style="font-size: 11px; font-weight: 500; color: #000;">
                                        Lorem_Ipsum_Activity_Design.pdf</p>
                                    <p style="font-size: 9px; color: #3b3b3b;">2.4 MB</p>
                                </div>
                            </div>
                            <button
                                style="color: #990dd1; font-size: 11px; padding: 4px 8px; border-radius: 8px; background: none; border: none; cursor: pointer;">👁️
                                Preview</button>
                        </div>
                    </div>
                </div>
            </section>

            <section
                style="flex: 0.4; display: flex; flex-direction: column; gap: 20px; overflow-y: auto; padding-bottom: 16px; position: sticky; top: 80px; align-self: flex-start; max-height: calc(100vh - 96px);">
                <div
                    style="background-color: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    <h3
                        style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #990dd1; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 11px;">📋</span> Assessment & Approval</h3>

                    <form action="list_and_filters.php" method="POST"
                        style="display: flex; flex-direction: column; gap: 20px;">
                        <div><label
                                style="display: block; font-size: 9px; font-weight: 700; color: #990dd1; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Control
                                Number</label><input type="text" name="control_number"
                                style="width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 16px; font-size: 11px; font-family: monospace;"
                                placeholder="GAD-2026-XXX">
                            <p style="font-size: 9px; color: #3b3b3b; margin-top: 4px;">Assign a unique control number
                                for this activity</p>
                        </div>
                        <div><label
                                style="display: block; font-size: 9px; font-weight: 700; color: #990dd1; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Date
                                of Assessment</label><input type="date" name="assessment_date"
                                style="width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 16px; font-size: 11px;"
                                value="<?php echo date('Y-m-d'); ?>"></div>
                        <!-- <div> -->
                            <!-- <label
                                style="display: block; font-size: 9px; font-weight: 700; color: #990dd1; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">HGDG Score
                            </label>
                            <input type="number" step="0.01" name="hgdg_score"
                                style="width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 16px; font-size: 20px; font-weight: 700;"
                                value="0.0"></div> -->
                        <!-- <div>
                            <label
                                style="display: block; font-size: 9px; font-weight: 700; color: #990dd1; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Alignment
                                Verification</label>
                            <div style="display: flex; flex-direction: column; gap: 8px;"><label
                                    style="display: flex; align-items: center; gap: 12px; padding: 8px; border-radius: 8px; cursor: pointer;"><input
                                        type="checkbox" style="border-radius: 4px; width: 16px; height: 16px;"> <span
                                        style="font-size: 11px; color: #3b3b3b;">Lorem ipsum dolor sit
                                        amet</span></label><label
                                    style="display: flex; align-items: center; gap: 12px; padding: 8px; border-radius: 8px; cursor: pointer;"><input
                                        type="checkbox" style="border-radius: 4px; width: 16px; height: 16px;"> <span
                                        style="font-size: 11px; color: #3b3b3b;">Consectetur adipiscing
                                        elit</span></label><label
                                    style="display: flex; align-items: center; gap: 12px; padding: 8px; border-radius: 8px; cursor: pointer;"><input
                                        type="checkbox" style="border-radius: 4px; width: 16px; height: 16px;"> <span
                                        style="font-size: 11px; color: #3b3b3b;">Sed do eiusmod tempor
                                        incididunt</span></label></div>
                        </div> -->
                        <!-- <div><label
                                style="display: block; font-size: 9px; font-weight: 700; color: #990dd1; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Reviewer's
                                Remarks / Comments</label><textarea name="remarks" rows="3"
                                style="width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 16px; font-size: 11px; font-family: inherit;"
                                placeholder="Add your comments, suggestions, or observations..."></textarea>
                            <p style="font-size: 9px; color: #3b3b3b; margin-top: 4px;">These remarks will be shared
                                with the proponent</p>
                        </div> -->
                        <div>
                            <br><label
                                style="display: block; font-size: 9px; font-weight: 700; color: #990dd1; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Deadline
                                for Accomplishment Report</label><input type="date" name="accomplishment_deadline"
                                style="width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 16px; font-size: 11px;">
                            <p style="font-size: 9px; color: #3b3b3b; margin-top: 4px;">Proponent must submit
                                accomplishment report by this date</p>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 12px; padding-top: 16px;">
                            <button type="submit" name="action" value="approve"
                                style="width: 100%; padding: 12px; background-color: #3f6516; color: white; border-radius: 12px; font-weight: 700; font-size: 11px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);"><span
                                    style="font-size: 11px;">✅</span> Approve & Send to Proponent</button>
                            <button type="button" onclick="openRevisionModal()"
                                style="width: 100%; padding: 12px; background-color: white; border: 2px solid #ecd224; color: #ecd224; border-radius: 12px; font-weight: 700; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;"><span
                                    style="font-size: 11px;">✏️</span> Request Revision</button>
                            <button type="button" onclick="openCancelModal()"
                                style="width: 100%; padding: 12px; background-color: white; border: 2px solid #ef4444; color: #ef4444; border-radius: 12px; font-weight: 700; font-size: 11px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;"><span
                                    style="font-size: 11px;">❌</span> Cancel Request</button>
                            <a href="list_and_filters.php"
                                style="display: block; width: 100%; padding: 8px; font-size: 11px; color: #3b3b3b; text-align: center; text-decoration: none;">←
                                Back to List</a>
                        </div>
                    </form>
                </div>

                <div
                    style="background-color: white; padding: 24px; border-radius: 12px; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    <h3
                        style="font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #3b3b3b; margin-bottom: 16px;">
                        Submission History</h3>
                    <div style="display: flex; flex-direction: column; gap: 16px; position: relative;">
                        <div style="position: relative; padding-left: 28px;">
                            <div
                                style="position: absolute; left: 0; top: 2px; width: 16px; height: 16px; border-radius: 9999px; background-color: #990dd1;">
                            </div>
                            <p style="font-size: 11px; font-weight: 700; color: #000;">Submitted for Review</p>
                            <p style="font-size: 9px; color: #3b3b3b;">Jan 15, 2026 • 09:14 AM</p>
                        </div>
                        <div style="position: relative; padding-left: 28px;">
                            <div
                                style="position: absolute; left: 0; top: 2px; width: 16px; height: 16px; border-radius: 9999px; background-color: #cbd5e1;">
                            </div>
                            <p style="font-size: 11px; font-weight: 500; color: #3b3b3b;">Technical Review Started</p>
                            <p style="font-size: 9px; color: #3b3b3b;">Jan 15, 2026 • 02:30 PM</p>
                        </div>
                    </div>
                </div>

                <div style="background-color: #eff6ff; padding: 16px; border-radius: 12px; border: 1px solid #bfdbfe;">
                    <div style="display: flex; gap: 12px;"><span style="font-size: 24px; color: #2563eb;">ℹ️</span>
                        <div>
                            <p style="font-size: 11px; font-weight: 700; color: #1d4ed8;">Upon Approval</p>
                            <p style="font-size: 9px; color: #2563eb;">Lorem ipsum dolor sit amet, consectetur
                                adipiscing elit. Proponent will receive email notification.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Revision Modal (Popup) -->
    <div id="revisionModal" class="revision-modal">
        <div class="revision-modal-content">
            <div class="revision-modal-header">
                <h3>
                    <span>✏️</span>
                    Request Revision
                </h3>
            </div>
            <div class="revision-modal-body">
                <div class="activity-title-preview">
                    <h4>Lorem Ipsum Dolor Sit Amet Consectetur Adipiscing Elit</h4>
                </div>

                <div class="form-group">
                    <label><img src="document.png"> Revision Remarks / Comments</label>
                    <textarea id="revisionRemarks" rows="4"
                        placeholder="Please provide detailed comments on what needs to be revised..."></textarea>
                </div>

                <div class="form-group">
                    <label><img src="calendar.png"> Revision Deadline</label>
                    <input type="date" id="revisionDeadline">
                    <p style="font-size: 9px; color: #999; margin-top: 4px;">Proponent must resubmit by this date</p>
                </div>
            </div>
            <div class="revision-modal-footer">
                <button type="button" class="btn-cancel" onclick="closeRevisionModal()">Cancel</button>
                <button type="button" class="btn-send" onclick="sendRevision()">
                    <span>📤</span> Send Revision Request
                </button>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal"
        style="position: fixed; top: 0; right: 0; bottom: 0; left: 0; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 200; display: none;">
        <div
            style="background-color: white; border-radius: 16px; padding: 32px; max-width: 448px; width: 90%; margin: 0 16px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="font-size: 20px; font-weight: 700; color: #000;">Cancel Activity Design Request</h3>
                <button onclick="closeCancelModal()"
                    style="background: none; border: none; font-size: 20px; color: #3b3b3b; cursor: pointer;">✕</button>
            </div>
            <div style="margin-bottom: 24px;">
                <p style="font-size: 11px; color: #3b3b3b; margin-bottom: 12px;">Are you sure you want to cancel this
                    activity design request?</p>
                <p style="font-size: 11px; color: #dc2626;">This action cannot be undone. The request will be moved to
                    archive with "Cancelled" status.</p>
                <div style="margin-top: 16px;">
                    <label
                        style="display: block; font-size: 9px; font-weight: 700; color: #3b3b3b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Cancellation
                        Reason</label>
                    <textarea id="cancelReason" rows="2"
                        style="width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px; font-size: 11px; font-family: inherit;"
                        placeholder="Provide reason for cancellation..."></textarea>
                </div>
            </div>
            <div style="display: flex; gap: 12px;">
                <button onclick="confirmCancellation()"
                    style="flex: 1; background-color: #dc2626; color: white; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 11px; border: none; cursor: pointer;">Confirm
                    Cancellation</button>
                <button onclick="closeCancelModal()"
                    style="flex: 1; border: 1px solid #cbd5e1; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 11px; background: none; cursor: pointer;">Go
                    Back</button>
            </div>
        </div>
    </div>

    <script>
    // Revision Modal Functions
    function openRevisionModal() {
        document.getElementById('revisionModal').classList.add('show');
    }

    function closeRevisionModal() {
        document.getElementById('revisionModal').classList.remove('show');
    }

    function sendRevision() {
        const remarks = document.getElementById('revisionRemarks').value;
        const deadline = document.getElementById('revisionDeadline').value;

        if (!remarks.trim()) {
            alert('Please provide revision remarks/comments.');
            return;
        }

        if (!deadline) {
            alert('Please select a revision deadline.');
            return;
        }

        // Format the date for display
        const formattedDate = new Date(deadline).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Show confirmation
        alert(
            `Revision request sent successfully!\n\nActivity: Lorem Ipsum Dolor Sit Amet Consectetur Adipiscing Elit\nRemarks: ${remarks}\nDeadline: ${formattedDate}\n\nThe proponent will be notified to make the required revisions.`);

        // Close modal and redirect
        closeRevisionModal();
        window.location.href = 'list_and_filters.php';
    }

    // Cancel Modal Functions
    function openCancelModal() {
        document.getElementById('cancelModal').style.display = 'flex';
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').style.display = 'none';
    }

    function confirmCancellation() {
        const reason = document.getElementById('cancelReason').value;
        alert(`Cancellation confirmed. Reason: ${reason || 'No reason provided'}`);
        closeCancelModal();
        window.location.href = 'list_and_filters.php';
    }
    </script>
</body>

</html>