<?php
// ar_review.php - Review Accomplishment Report (with cancel option and revision modal)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Review Accomplishment Report | GAD-IMS</title>
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

        .flex-055 {
            flex: 0.55;
        }

        .flex-045 {
            flex: 0.45;
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
            margin-top: 10px;
        }

        .bg-slate-50 {
            background-color: #f8fafc;
        }

        .p-5 {
            padding: 20px;
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

        .text-2xl {
            font-size: 24px;
        }

        .rounded-lg {
            border-radius: 8px;
        }

        .text-center {
            text-align: center;
        }

        .text-5170ff {
            color: #5170ff;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 8px;
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

        .text-blue-500 {
            color: #3b82f6;
        }

        .text-emerald-500 {
            color: #10b981;
        }

        .text-amber-500 {
            color: #f59e0b;
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

        .border-ecd224 {
            border-color: #ecd224;
        }

        .block {
            display: block;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .rounded-lg {
            border-radius: 8px;
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

        .relative {
            position: relative;
        }

        /* ===== BEAUTIFUL RIGHT SIDEBAR STYLES ===== */
        .assessment-card {
            background: linear-gradient(135deg, #ffffff 0%, #fef9ff 100%);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(153, 13, 209, 0.12);
        }

        .assessment-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f0e6ff;
        }

        .assessment-header-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #990dd1 0%, #b979cc 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
        }

        .assessment-header-title {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #990dd1;
        }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #6b21e5;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .form-label span {
            margin-right: 6px;
        }

        .form-textarea {
            width: 100%;
            border: 1.5px solid #e9e5f0;
            border-radius: 16px;
            padding: 14px 16px;
            font-size: 13px;
            font-family: inherit;
            transition: all 0.2s;
            background: #fefefe;
            resize: vertical;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #990dd1;
            box-shadow: 0 0 0 3px rgba(153, 13, 209, 0.08);
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #f0e6ff;
        }

        .btn-approve {
            width: 100%;
            background: linear-gradient(135deg, #3f6516 0%, #4a7a1a 100%);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 16px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
            box-shadow: 0 2px 6px rgba(63, 101, 22, 0.2);
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(63, 101, 22, 0.25);
            background: linear-gradient(135deg, #4a7a1a 0%, #3f6516 100%);
        }

        .btn-revision {
            width: 100%;
            background: white;
            border: 2px solid #ecd224;
            color: #b45309;
            border-radius: 16px;
            padding: 16px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .btn-revision:hover {
            background: #fffbeb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(236, 210, 36, 0.2);
        }

        .btn-back {
            display: block;
            width: 100%;
            padding: 14px;
            font-size: 12px;
            color: #888;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
            border-radius: 14px;
        }

        .btn-back:hover {
            color: #990dd1;
            background: #faf5ff;
        }

        /* Info Card - Tips */
        .info-card {
            background: linear-gradient(135deg, #fef9ff 0%, #ffffff 100%);
            border-radius: 20px;
            padding: 28px;
            border: 1px solid rgba(153, 13, 209, 0.12);
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .info-card-icon {
            width: 40px;
            height: 40px;
            background: rgba(153, 13, 209, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-card-icon span {
            font-size: 20px;
        }

        .info-card-title {
            font-size: 12px;
            font-weight: 800;
            color: #990dd1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tips-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .tips-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            font-size: 12px;
            color: #555;
            border-bottom: 1px solid #f0e6ff;
        }

        .tips-list li:last-child {
            border-bottom: none;
        }

        .tips-list li span:first-child {
            color: #990dd1;
            font-weight: 700;
        }

        /* Status Card */
        .status-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
            border-radius: 20px;
            padding: 28px;
            border: 1px solid rgba(81, 112, 255, 0.12);
        }

        .status-card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .status-card-icon {
            width: 40px;
            height: 40px;
            background: rgba(81, 112, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-card-icon span {
            font-size: 20px;
        }

        .status-card-title {
            font-size: 12px;
            font-weight: 800;
            color: #5170ff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
        }

        .status-label {
            font-size: 12px;
            color: #555;
        }

        .status-badge {
            background: #fef3c7;
            color: #d97706;
            padding: 6px 14px;
            border-radius: 24px;
            font-size: 11px;
            font-weight: 700;
        }

        .progress-bar-container {
            background: #e9e5f0;
            border-radius: 12px;
            height: 8px;
            overflow: hidden;
            margin-top: 16px;
        }

        .progress-bar-fill {
            width: 40%;
            height: 100%;
            background: linear-gradient(90deg, #990dd1, #b979cc);
            border-radius: 12px;
        }

        .progress-text {
            font-size: 10px;
            color: #999;
            margin-top: 10px;
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
            border-radius: 28px;
            max-width: 520px;
            width: 90%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .revision-modal-header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            padding: 24px 28px;
            color: white;
        }

        .revision-modal-header h3 {
            font-size: 22px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .revision-modal-body {
            padding: 28px;
        }

        .revision-modal-footer {
            padding: 20px 28px;
            background: #f8fafc;
            display: flex;
            gap: 14px;
            justify-content: flex-end;
            border-top: 1px solid #eef2f6;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #990dd1;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 14px 18px;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
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
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 24px;
        }

        .activity-title-preview p {
            font-size: 11px;
            color: #666;
            margin-bottom: 6px;
        }

        .activity-title-preview h4 {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .btn-send {
            background: #ecd224;
            color: #000;
            border: none;
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-send:hover {
            background: #d4b91e;
            transform: translateY(-1px);
        }

        .btn-cancel-modal {
            background: #f0f2f5;
            color: #444;
            border: none;
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel-modal:hover {
            background: #e6e9ef;
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

        .border-slate-300 {
            border-color: #cbd5e1;
        }

        .hover\:bg-red-700:hover {
            background-color: #b91c1c;
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

    <main class="pl-64 pt-16 min-h-screen">
        <div class="p-10 flex gap-8">

            <!-- LEFT SECTION - Report Preview (55%) -->
            <section class="flex-[0.55] bg-white rounded-xl flex flex-col overflow-hidden shadow-sm border">
                <div class="p-8 pb-4 border-b">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center gap-2 bg-[#ecd224]/20 text-[#ecd224] px-3 py-1 rounded-full">
                            <div class="w-2 h-2 rounded-full bg-[#ecd224] animate-pulse"></div>
                            <span class="text-9px font-black uppercase tracking-widest">Under Review</span>
                        </div>
                        <span class="text-9px font-bold text-3b3b3b uppercase tracking-widest">BSU-GAD-2026-015</span>
                    </div>

                    <h2 class="font-serif text-28px text-000 leading-tight mb-4">
                        Lorem Ipsum Dolor Sit Amet Consectetur Adipiscing Elit
                    </h2>

                    <div class="flex flex-wrap gap-6 pt-4 border-t border-slate-100">
                        <div class="flex flex-col"><span class="text-9px uppercase tracking-widest text-3b3b3b font-bold mb-1">Submitted By</span><span class="text-11px font-semibold text-990dd1">Dr. Lorem Ipsum</span></div>
                        <div class="flex flex-col"><span class="text-9px uppercase tracking-widest text-3b3b3b font-bold mb-1">Office</span><span class="text-11px font-medium text-3b3b3b">Lorem Ipsum Office</span></div>
                        <div class="flex flex-col"><span class="text-9px uppercase tracking-widest text-3b3b3b font-bold mb-1">Email</span><span class="text-11px font-medium text-3b3b3b">lorem.ipsum@bsu.edu.ph</span></div>
                        <div class="flex flex-col"><span class="text-9px uppercase tracking-widest text-3b3b3b font-bold mb-1">Date Submitted</span><span class="text-11px font-medium text-3b3b3b">Mar 15, 2026</span></div>
                        <div class="flex flex-col"><span class="text-9px uppercase tracking-widest text-3b3b3b font-bold mb-1">Category</span><span class="text-11px font-medium text-3b3b3b">Accomplishment Report</span></div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar p-8 space-y-6">
                    <div class="bg-slate-50 rounded-xl p-5">
                        <!-- <div class="flex items-center gap-2 mb-4"><span class="text-990dd1">ℹ️</span>
                            <h3 class="font-bold text-11px uppercase tracking-widest text-990dd1">Report Information</h3>
                        </div> -->
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="text-9px font-bold text-3b3b3b uppercase">Control Number</label>
                                <p class="text-11px font-bold text-990dd1 mt-1">BSU-GAD-2026-015</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-5">
                        <div class="flex items-center gap-2 mb-4"><span class="text-990dd1">📊</span>
                            <h3 class="font-bold text-11px uppercase tracking-widest text-990dd1">Activity Metrics</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <div class="bg-white rounded-lg p-3 text-center border"><span class="text-990dd1">👥</span>
                                <p class="text-2xl font-bold text-000">84</p>
                                <p class="text-9px text-3b3b3b uppercase">Total Attendees</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 text-center border"><span class="text-ecd224">⭐</span>
                                <p class="text-2xl font-bold text-000">96</p>
                                <p class="text-9px text-3b3b3b uppercase">/ 100% Rating</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 text-center border"><span class="text-5170ff">♂️</span>
                                <p class="text-2xl font-bold text-000">28</p>
                                <p class="text-9px text-3b3b3b uppercase">Male</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 text-center border"><span class="text-990dd1">♀️</span>
                                <p class="text-2xl font-bold text-000">56</p>
                                <p class="text-9px text-3b3b3b uppercase">Female</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-xl p-5">
                        <div class="flex items-center gap-2 mb-4"><span class="text-990dd1">📎</span>
                            <h3 class="font-bold text-11px uppercase tracking-widest text-990dd1">Uploaded Documents</h3>
                        </div>

                        <div class="mb-4">
                            <p class="text-9px font-bold text-3b3b3b uppercase tracking-wider mb-2">Accomplishment Report (Filled Template)</p>
                            <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                <div class="flex items-center gap-3"><span class="text-red-500 text-2xl">📄</span>
                                    <div>
                                        <p class="text-11px font-medium text-000">Lorem_Ipsum_Accomplishment_Report.pdf</p>
                                        <p class="text-9px text-3b3b3b">2.4 MB • Uploaded Mar 15, 2026</p>
                                    </div>
                                </div>
                                <button class="text-990dd1 text-11px px-2 py-1 rounded cursor-pointer">👁️ Preview</button>
                            </div>
                            <br>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                    <div class="flex items-center gap-3"><span class="text-blue-500">📋</span>
                                        <div>
                                            <p class="text-11px font-medium text-000">Lorem_Ipsum_Attendance_Sheet.pdf</p>
                                            <p class="text-9px text-3b3b3b">1.2 MB • Signed attendance</p>
                                        </div>
                                    </div><button class="text-990dd1 text-11px cursor-pointer">👁️ Preview</button>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                    <div class="flex items-center gap-3"><span class="text-emerald-500">🖼️</span>
                                        <div>
                                            <p class="text-11px font-medium text-000">Lorem_Ipsum_Photo_Documentation.zip</p>
                                            <p class="text-9px text-3b3b3b">18.5 MB • 62 photos</p>
                                        </div>
                                    </div><button class="text-990dd1 text-11px cursor-pointer">👁️ Preview</button>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                    <div class="flex items-center gap-3"><span class="text-amber-500">🧾</span>
                                        <div>
                                            <p class="text-11px font-medium text-000">Lorem_Ipsum_Financial_Report.pdf</p>
                                            <p class="text-9px text-3b3b3b">0.8 MB • Signed by budget officer</p>
                                        </div>
                                    </div><button class="text-990dd1 text-11px cursor-pointer">👁️ Preview</button>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                                    <div class="flex items-center gap-3"><span class="text-990dd1">📊</span>
                                        <div>
                                            <p class="text-11px font-medium text-000">Lorem_Ipsum_Evaluation_Summary.xlsx</p>
                                            <p class="text-9px text-3b3b3b">0.5 MB • Post-activity evaluation</p>
                                        </div>
                                    </div><button class="text-990dd1 text-11px cursor-pointer">👁️ Preview</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- RIGHT SECTION - Beautiful Sidebar (45% - Wider) -->
            <section class="flex-[0.45] flex flex-col gap-6 overflow-y-auto custom-scrollbar pb-4 sticky top-20 self-start max-h-[calc(100vh-6rem)]">

                <!-- Assessment Card -->
                <div class="assessment-card">
                    <div class="assessment-header">
                        <div class="assessment-header-icon">📋</div>
                        <div class="assessment-header-title">Assessment & Approval</div>
                    </div>

                    <form action="list_acc_filters.php" method="POST">
                        <div>
                            <label class="form-label"><span>✏️</span> Reviewer's Remarks</label>
                            <textarea name="remarks" rows="4" class="form-textarea" placeholder="Add your comments, suggestions, or observations about this accomplishment report..."></textarea>
                            <p style="font-size: 9px; color: #999; margin-top: 8px;">These remarks will be shared with the proponent</p>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" onclick="window.location.href='list_acc_filters.php';" name="action" value="approve" class="btn-approve">
                                <span>✅</span> Approve Report
                            </button>
                            <button type="button" onclick="openRevisionModal()" class="btn-revision">
                                <span>✏️</span> Request Revision
                            </button>
                            <a href="list_acc_filters.php" class="btn-back">
                                ← Back to List
                            </a>
                        </div>
                    </form>
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
                    <textarea id="revisionRemarks" rows="4" placeholder="Please provide detailed comments on what needs to be revised..."></textarea>
                </div>

                <div class="form-group">
                    <label><img src="calendar.png"> Revision Deadline</label>
                    <input type="date" id="revisionDeadline">
                    <p style="font-size: 9px; color: #999; margin-top: 6px;">Proponent must resubmit by this date</p>
                </div>
            </div>
            <div class="revision-modal-footer">
                <button type="button" class="btn-cancel-modal" onclick="closeRevisionModal()">Cancel</button>
                <button type="button" class="btn-send" onclick="window.location.href='list_acc_filters.php';">
                    <span>📤</span> Send Revision Request
                </button>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-200 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-000">Cancel Activity Design Request</h3>
                <button onclick="closeCancelModal()" class="text-3b3b3b cursor-pointer text-xl">✕</button>
            </div>
            <div class="mb-6">
                <p class="text-11px text-3b3b3b mb-3">Are you sure you want to cancel this activity design request?</p>
                <p class="text-11px text-red-600">This action cannot be undone. The request will be moved to archive with "Cancelled" status.</p>
                <div class="mt-4">
                    <label class="block text-9px font-bold text-3b3b3b uppercase tracking-wider mb-1">Cancellation Reason</label>
                    <textarea id="cancelReason" rows="2" class="w-full border rounded-lg px-3 py-2 text-11px" placeholder="Provide reason for cancellation..."></textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="confirmCancellation()" class="flex-1 bg-red-600 text-white py-3 rounded-xl font-bold text-11px hover:bg-red-700 transition cursor-pointer">Confirm Cancellation</button>
                <button onclick="closeCancelModal()" class="flex-1 border border-slate-300 py-3 rounded-xl font-bold text-11px hover:bg-slate-50 transition cursor-pointer">Go Back</button>
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

            const formattedDate = new Date(deadline).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            alert(`Revision request sent successfully!\n\nAccomplishment Report: Lorem Ipsum Dolor Sit Amet Consectetur Adipiscing Elit\nRemarks: ${remarks}\nDeadline: ${formattedDate}\n\nThe proponent will be notified to make the required revisions.`);

            closeRevisionModal();
            window.location.href = 'list_acc_filters.php';
        }

        // Cancel Modal Functions
        function openCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.getElementById('cancelModal').classList.add('flex');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.getElementById('cancelModal').classList.remove('flex');
        }

        function confirmCancellation() {
            const reason = document.getElementById('cancelReason').value;
            alert(`Cancellation confirmed. Reason: ${reason || 'No reason provided'}`);
            closeCancelModal();
            window.location.href = 'list_acc_filters.php';
        }
    </script>
</body>

</html>