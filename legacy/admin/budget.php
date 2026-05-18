<?php
// budget.php - Budget Realignment & Utilization (Mandate-Based GAD Plan Alignment)
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Budget Realignment | GAD-IMS</title>
    <style>
        /* ===== INTERNAL CSS - Replaces Tailwind, Google Fonts, Material Icons ===== */
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

        .progress-bar {
            transition: width 0.5s ease;
        }

        /* Utility Classes - matching Tailwind behavior */
        .fixed {
            position: fixed;
        }

        .absolute {
            position: absolute;
        }

        .relative {
            position: relative;
        }

        .sticky {
            position: sticky;
        }

        .inset-0 {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .bottom-4 {
            bottom: 16px;
        }

        .left-0 {
            left: 0;
        }

        .left-56 {
            left: 224px;
        }

        .left-64 {
            left: 256px;
        }

        .right-0 {
            right: 0;
        }

        .top-0 {
            top: 0;
        }

        .top-1\/2 {
            top: 50%;
        }

        .top-20 {
            top: 80px;
        }

        .z-40 {
            z-index: 40;
        }

        .z-50 {
            z-index: 50;
        }

        .col-span-1 {
            grid-column: span 1 / span 1;
        }

        .float-right {
            float: right;
        }

        .m-1 {
            margin: 4px;
        }

        .-m-1 {
            margin: -4px;
        }

        .mb-1 {
            margin-bottom: 4px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .mb-3 {
            margin-bottom: 12px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .mb-6 {
            margin-bottom: 24px;
        }

        .mb-8 {
            margin-bottom: 32px;
        }

        .ml-2 {
            margin-left: 8px;
        }

        .mr-1 {
            margin-right: 4px;
        }

        .mt-1 {
            margin-top: 4px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mt-3 {
            margin-top: 12px;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .mt-auto {
            margin-top: auto;
        }

        .block {
            display: block;
        }

        .flex {
            display: flex;
        }

        .inline-flex {
            display: inline-flex;
        }

        .grid {
            display: grid;
        }

        .hidden {
            display: none;
        }

        .h-1\.5 {
            height: 6px;
        }

        .h-2 {
            height: 8px;
        }

        .h-4 {
            height: 16px;
        }

        .h-10 {
            height: 40px;
        }

        .h-12 {
            height: 48px;
        }

        .h-14 {
            height: 56px;
        }

        .h-16 {
            height: 64px;
        }

        .h-20 {
            height: 80px;
        }

        .h-\[1px\] {
            height: 1px;
        }

        .h-auto {
            height: auto;
        }

        .h-full {
            height: 100%;
        }

        .max-h-96 {
            max-height: 384px;
        }

        .max-h-\[500px\] {
            max-height: 500px;
        }

        .max-h-\[calc\(100vh-6rem\)\] {
            max-height: calc(100vh - 96px);
        }

        .min-h-screen {
            min-height: 100vh;
        }

        .w-1\.5 {
            width: 6px;
        }

        .w-2 {
            width: 8px;
        }

        .w-4 {
            width: 16px;
        }

        .w-10 {
            width: 40px;
        }

        .w-16 {
            width: 64px;
        }

        .w-56 {
            width: 224px;
        }

        .w-64 {
            width: 256px;
        }

        .w-80 {
            width: 320px;
        }

        .w-\[1px\] {
            width: 1px;
        }

        .w-auto {
            width: auto;
        }

        .w-full {
            width: 100%;
        }

        .flex-1 {
            flex: 1;
        }

        .flex-shrink-0 {
            flex-shrink: 0;
        }

        .-translate-y-1\/2 {
            transform: translateY(-50%);
        }

        .border {
            border: 1px solid #e2e8f0;
        }

        .border-0 {
            border: 0;
        }

        .border-2 {
            border-width: 2px;
        }

        .border-b {
            border-bottom-width: 1px;
        }

        .border-l-4 {
            border-left-width: 4px;
        }

        .border-r {
            border-right-width: 1px;
        }

        .border-t {
            border-top-width: 1px;
        }

        .border-\[\#990dd1\] {
            border-color: #990dd1;
        }

        .border-\[\#ecd224\] {
            border-color: #ecd224;
        }

        .border-amber-500 {
            border-color: #f59e0b;
        }

        .border-blue-200 {
            border-color: #bfdbfe;
        }

        .border-emerald-500 {
            border-color: #10b981;
        }

        .border-purple-100 {
            border-color: #f3e8ff;
        }

        .border-purple-200\/40 {
            border-color: rgba(233, 213, 255, 0.4);
        }

        .border-purple-200\/60 {
            border-color: rgba(233, 213, 255, 0.6);
        }

        .border-red-500 {
            border-color: #ef4444;
        }

        .border-slate-100 {
            border-color: #f1f5f9;
        }

        .border-slate-200 {
            border-color: #e2e8f0;
        }

        .border-slate-300 {
            border-color: #cbd5e1;
        }

        .border-transparent {
            border-color: transparent;
        }

        .border-white\/20 {
            border-color: rgba(255, 255, 255, 0.2);
        }

        .border-white\/30 {
            border-color: rgba(255, 255, 255, 0.3);
        }

        .rounded {
            border-radius: 4px;
        }

        .rounded-2xl {
            border-radius: 1rem;
        }

        .rounded-3xl {
            border-radius: 1.5rem;
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .rounded-lg {
            border-radius: 8px;
        }

        .rounded-xl {
            border-radius: 12px;
        }

        .bg-\[\#3f6516\] {
            background-color: #3f6516;
        }

        .bg-\[\#990dd1\] {
            background-color: #990dd1;
        }

        .bg-\[\#b979cc\]\/10 {
            background-color: rgba(185, 121, 204, 0.1);
        }

        .bg-\[\#ecd224\]\/20 {
            background-color: rgba(236, 210, 36, 0.2);
        }

        .bg-amber-50 {
            background-color: #fffbeb;
        }

        .bg-black\/50 {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .bg-blue-50 {
            background-color: #eff6ff;
        }

        .bg-emerald-50\/30 {
            background-color: rgba(209, 250, 229, 0.3);
        }

        .bg-green-200 {
            background-color: #bbf7d0;
        }

        .bg-purple-200 {
            background-color: #e9d5ff;
        }

        .bg-red-100 {
            background-color: #fee2e2;
        }

        .bg-red-500 {
            background-color: #ef4444;
        }

        .bg-red-600 {
            background-color: #dc2626;
        }

        .bg-slate-100 {
            background-color: #f1f5f9;
        }

        .bg-slate-200 {
            background-color: #e2e8f0;
        }

        .bg-slate-300 {
            background-color: #cbd5e1;
        }

        .bg-slate-50 {
            background-color: #f8fafc;
        }

        .bg-slate-50\/50 {
            background-color: rgba(248, 250, 252, 0.5);
        }

        .bg-white {
            background-color: white;
        }

        .bg-white\/10 {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .bg-white\/20 {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .bg-white\/90 {
            background-color: rgba(255, 255, 255, 0.9);
        }

        .bg-yellow-200 {
            background-color: #fef08a;
        }

        .bg-gradient-to-br {
            background-image: linear-gradient(to bottom right, var(--tw-gradient-stops));
        }

        .bg-gradient-to-r {
            background-image: linear-gradient(to right, var(--tw-gradient-stops));
        }

        .from-\[\#3f6516\] {
            --tw-gradient-from: #3f6516;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(63, 101, 22, 0));
        }

        .from-\[\#5170ff\] {
            --tw-gradient-from: #5170ff;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(81, 112, 255, 0));
        }

        .from-\[\#990dd1\] {
            --tw-gradient-from: #990dd1;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(153, 13, 209, 0));
        }

        .to-\[\#002c5c\] {
            --tw-gradient-to: #002c5c;
        }

        .to-\[\#2d4a10\] {
            --tw-gradient-to: #2d4a10;
        }

        .to-\[\#b979cc\] {
            --tw-gradient-to: #b979cc;
        }

        .p-0 {
            padding: 0;
        }

        .p-1 {
            padding: 4px;
        }

        .p-2 {
            padding: 8px;
        }

        .p-3 {
            padding: 12px;
        }

        .p-4 {
            padding: 16px;
        }

        .p-5 {
            padding: 20px;
        }

        .p-6 {
            padding: 24px;
        }

        .p-8 {
            padding: 32px;
        }

        .p-10 {
            padding: 40px;
        }

        .px-0 {
            padding-left: 0;
            padding-right: 0;
        }

        .px-1 {
            padding-left: 4px;
            padding-right: 4px;
        }

        .px-2 {
            padding-left: 8px;
            padding-right: 8px;
        }

        .px-3 {
            padding-left: 12px;
            padding-right: 12px;
        }

        .px-4 {
            padding-left: 16px;
            padding-right: 16px;
        }

        .px-5 {
            padding-left: 20px;
            padding-right: 20px;
        }

        .px-6 {
            padding-left: 24px;
            padding-right: 24px;
        }

        .px-8 {
            padding-left: 32px;
            padding-right: 32px;
        }

        .px-10 {
            padding-left: 40px;
            padding-right: 40px;
        }

        .py-0\.5 {
            padding-top: 2px;
            padding-bottom: 2px;
        }

        .py-1 {
            padding-top: 4px;
            padding-bottom: 4px;
        }

        .py-1\.5 {
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .py-2 {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .py-2\.5 {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .py-3 {
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .py-4 {
            padding-top: 16px;
            padding-bottom: 16px;
        }

        .py-6 {
            padding-top: 24px;
            padding-bottom: 24px;
        }

        .py-8 {
            padding-top: 32px;
            padding-bottom: 32px;
        }

        .py-10 {
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .pb-4 {
            padding-bottom: 16px;
        }

        .pl-7 {
            padding-left: 28px;
        }

        .pl-56 {
            padding-left: 224px;
        }

        .pl-64 {
            padding-left: 256px;
        }

        .pr-4 {
            padding-right: 16px;
        }

        .pt-2 {
            padding-top: 8px;
        }

        .pt-3 {
            padding-top: 12px;
        }

        .pt-4 {
            padding-top: 16px;
        }

        .pt-16 {
            padding-top: 64px;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .align-middle {
            vertical-align: middle;
        }

        .text-2xl {
            font-size: 24px;
        }

        .text-3xl {
            font-size: 30px;
        }

        .text-4xl {
            font-size: 36px;
        }

        .text-5xl {
            font-size: 48px;
        }

        .text-6xl {
            font-size: 60px;
        }

        .text-\[0\.85rem\] {
            font-size: 0.85rem;
        }

        .text-\[0\.9rem\] {
            font-size: 0.9rem;
        }

        .text-\[2\.6rem\] {
            font-size: 2.6rem;
        }

        .text-\[8px\] {
            font-size: 8px;
        }

        .text-\[9px\] {
            font-size: 9px;
        }

        .text-\[10px\] {
            font-size: 10px;
        }

        .text-\[11px\] {
            font-size: 11px;
        }

        .text-\[12px\] {
            font-size: 12px;
        }

        .text-\[13px\] {
            font-size: 13px;
        }

        .text-\[14px\] {
            font-size: 14px;
        }

        .text-\[28px\] {
            font-size: 28px;
        }

        .text-base {
            font-size: 16px;
        }

        .text-lg {
            font-size: 18px;
        }

        .text-sm {
            font-size: 14px;
        }

        .text-xl {
            font-size: 20px;
        }

        .text-xs {
            font-size: 12px;
        }

        .font-black {
            font-weight: 900;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-extrabold {
            font-weight: 800;
        }

        .font-light {
            font-weight: 300;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-semibold {
            font-weight: 600;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .italic {
            font-style: italic;
        }

        .leading-none {
            line-height: 1;
        }

        .leading-tight {
            line-height: 1.25;
        }

        .tracking-\[0\.1em\] {
            letter-spacing: 0.1em;
        }

        .tracking-tight {
            letter-spacing: -0.025em;
        }

        .tracking-tighter {
            letter-spacing: -0.05em;
        }

        .tracking-wider {
            letter-spacing: 0.05em;
        }

        .tracking-widest {
            letter-spacing: 0.1em;
        }

        .text-\[\#000\] {
            color: #000;
        }

        .text-\[\#002c5c\] {
            color: #002c5c;
        }

        .text-\[\#3b3b3b\] {
            color: #3b3b3b;
        }

        .text-\[\#3f6516\] {
            color: #3f6516;
        }

        .text-\[\#5170ff\] {
            color: #5170ff;
        }

        .text-\[\#990dd1\] {
            color: #990dd1;
        }

        .text-\[\#ecd224\] {
            color: #ecd224;
        }

        .text-amber-600 {
            color: #d97706;
        }

        .text-blue-600 {
            color: #2563eb;
        }

        .text-blue-700 {
            color: #1d4ed8;
        }

        .text-red-600 {
            color: #dc2626;
        }

        .text-red-700 {
            color: #b91c1c;
        }

        .text-slate-300 {
            color: #cbd5e1;
        }

        .text-white {
            color: white;
        }

        .text-yellow-700 {
            color: #a16207;
        }

        .opacity-80 {
            opacity: 0.8;
        }

        .opacity-90 {
            opacity: 0.9;
        }

        .shadow-2xl {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .shadow-xl {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .backdrop-blur-md {
            backdrop-filter: blur(12px);
        }

        .backdrop-blur-xl {
            backdrop-filter: blur(24px);
        }

        .transition {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        .transition-colors {
            transition-property: color, background-color, border-color;
            transition-duration: 150ms;
        }

        .duration-200 {
            transition-duration: 200ms;
        }

        .ease-in-out {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover\:bg-\[\#3f6516\]\/80:hover {
            background-color: rgba(63, 101, 22, 0.8);
        }

        .hover\:bg-\[\#990dd1\]:hover {
            background-color: #990dd1;
        }

        .hover\:bg-\[\#b979cc\]:hover {
            background-color: #b979cc;
        }

        .hover\:bg-\[\#b979cc\]\/10:hover {
            background-color: rgba(185, 121, 204, 0.1);
        }

        .hover\:bg-\[\#b979cc\]\/20:hover {
            background-color: rgba(185, 121, 204, 0.2);
        }

        .hover\:bg-\[\#ecd224\]\/10:hover {
            background-color: rgba(236, 210, 36, 0.1);
        }

        .hover\:bg-indigo-50:hover {
            background-color: #eef2ff;
        }

        .hover\:bg-red-50:hover {
            background-color: #fef2f2;
        }

        .hover\:bg-red-700:hover {
            background-color: #b91c1c;
        }

        .hover\:bg-slate-100:hover {
            background-color: #f1f5f9;
        }

        .hover\:bg-slate-200:hover {
            background-color: #e2e8f0;
        }

        .hover\:bg-slate-50:hover {
            background-color: #f8fafc;
        }

        .hover\:bg-white:hover {
            background-color: white;
        }

        .hover\:text-\[\#990dd1\]:hover {
            color: #990dd1;
        }

        .hover\:text-white:hover {
            color: white;
        }

        .hover\:underline:hover {
            text-decoration: underline;
        }

        .focus\:ring-2:focus {
            ring: 2px solid;
        }

        .focus\:ring-\[\#990dd1\]:focus {
            ring-color: #990dd1;
        }

        .group:hover .group-hover\:text-\[\#990dd1\] {
            color: #990dd1;
        }

        .pointer-events-none {
            pointer-events: none;
        }

        /* Grid layouts */
        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .grid-cols-7 {
            grid-template-columns: repeat(7, minmax(0, 1fr));
        }

        .grid-cols-10 {
            grid-template-columns: repeat(10, minmax(0, 1fr));
        }

        .grid-cols-12 {
            grid-template-columns: repeat(12, minmax(0, 1fr));
        }

        .gap-1 {
            gap: 4px;
        }

        .gap-2 {
            gap: 8px;
        }

        .gap-3 {
            gap: 12px;
        }

        .gap-4 {
            gap: 16px;
        }

        .gap-5 {
            gap: 20px;
        }

        .gap-6 {
            gap: 24px;
        }

        .gap-8 {
            gap: 32px;
        }

        .space-x-4>*+* {
            margin-left: 16px;
        }

        .space-y-1>*+* {
            margin-top: 4px;
        }

        .space-y-2>*+* {
            margin-top: 8px;
        }

        .space-y-3>*+* {
            margin-top: 12px;
        }

        .space-y-4>*+* {
            margin-top: 16px;
        }

        .space-y-5>*+* {
            margin-top: 20px;
        }

        .space-y-6>*+* {
            margin-top: 24px;
        }

        .space-y-8>*+* {
            margin-top: 32px;
        }

        .divide-y>*+* {
            border-top-width: 1px;
            border-color: #e2e8f0;
        }

        .divide-slate-100>*+* {
            border-color: #f1f5f9;
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

        /* Desktop styles - 3 cards in a row */
        .cards-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 32px;
        }

        @media (max-width: 768px) {
            .cards-row {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        @media (min-width: 1024px) {
            .lg\:col-span-3 {
                grid-column: span 3 / span 3;
            }

            .lg\:col-span-6 {
                grid-column: span 6 / span 6;
            }

            .lg\:col-span-7 {
                grid-column: span 7 / span 7;
            }

            .lg\:flex {
                display: flex;
            }

            .lg\:w-auto {
                width: auto;
            }

            .lg\:flex-none {
                flex: none;
            }

            .lg\:text-left {
                text-align: left;
            }

            .lg\:grid-cols-10 {
                grid-template-columns: repeat(10, minmax(0, 1fr));
            }
        }.staff-header {
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

<body class="antialiased">
    <header class="staff-header">
        <div>
            <h2 class="header-title">Budget Monitoring</h2>
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
        <div class="p-10 space-y-8">


            <!-- 3 CARDS IN A ROW - FIXED -->
            <div class="cards-row">
                <div class="bg-gradient-to-br from-[#990dd1] to-[#b979cc] rounded-2xl p-6 text-white shadow-lg">
                    <p class="text-[11px] font-medium uppercase tracking-wider opacity-80">Total GAD Budget</p>
                    <p class="text-4xl font-bold mt-2">₱243,541,951</p>
                    <p class="text-[11px] opacity-80 mt-2">FY 2026 Approved GAD Allocation</p>
                </div>

                <div class="bg-gradient-to-br from-[#5170ff] to-[#002c5c] rounded-2xl p-6 text-white shadow-lg">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] font-medium uppercase tracking-wider opacity-80">GAD Office Budget</p>
                        <span class="material-symbols-outlined text-2xl opacity-80">🏛️</span>
                    </div>
                    <p class="text-4xl font-bold mt-2">₱74,521,951</p>
                    <p class="text-[11px] opacity-80 mt-2">22 GAD Office-led mandates</p>
                    <div class="mt-3 pt-2 border-t border-white/30">
                        <p class="text-[9px] opacity-70">30.6% of total GAD budget</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-[#3f6516] to-[#2d4a10] rounded-2xl p-6 text-white shadow-lg">
                    <p class="text-[11px] font-medium uppercase tracking-wider opacity-80">Balance Available</p>
                    <p class="text-4xl font-bold mt-2">₱175,326,951</p>
                    <p class="text-[11px] opacity-80 mt-2">Remaining for Q2-Q4 implementation</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">
                <div class="lg:col-span-7 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h4 class="text-xl font-bold text-[#000] tracking-tight">GAD Plan & Budget Alignment</h4>
                        <p class="text-[11px] text-[#3b3b3b] mt-1">Mandate-based budget utilization tracking</p>
                    </div>
                    <div class="divide-y divide-slate-100">
                        <div class="p-6 hover:bg-[#b979cc]/5 transition">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-[#000] text-[11px]">MANDATE 1: AFFIRMATIVE ACTION / FREE TUITION (RA 10931)</h5>
                                    <p class="text-[11px] text-[#3b3b3b] mt-1">Free tuition, disadvantaged student assistance</p>
                                </div>
                                <div class="text-right"><span class="text-[11px] font-medium text-[#3b3b3b]">Utilized:</span><span class="text-lg font-bold text-[#990dd1]">₱45,800,000</span><span class="text-[11px] text-[#3b3b3b]"> / ₱131,800,000</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#990dd1] rounded-full progress-bar" style="width: 34.7%"></div>
                                </div><span class="text-[10px] font-semibold text-[#3b3b3b]">34.7% utilized</span>
                            </div>
                        </div>

                        <div class="p-6 hover:bg-[#b979cc]/5 transition bg-slate-50/20">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-[#000] text-[11px]">MANDATE 2: GAD ORIENTATION & STUDENT LEADERSHIP TRAINING</h5>
                                    <p class="text-[11px] text-[#3b3b3b] mt-1">GAD orientation (4,000 students), leadership training for student leaders</p>
                                </div>
                                <div class="text-right"><span class="text-[11px] font-medium text-[#3b3b3b]">Utilized:</span><span class="text-lg font-bold text-[#990dd1]">₱210,000</span><span class="text-[11px] text-[#3b3b3b]"> / ₱453,363</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#ecd224] rounded-full progress-bar" style="width: 46.3%"></div>
                                </div><span class="text-[10px] font-semibold text-[#3b3b3b]">46.3% utilized</span>
                            </div>
                        </div>

                        <div class="p-6 hover:bg-[#b979cc]/5 transition">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-[#000] text-[11px]">MANDATE 3: GREP EXTENSION / COMMUNITY PROGRAMS</h5>
                                    <p class="text-[11px] text-[#3b3b3b] mt-1">24 extension activities, technology transfer, livelihood programs</p>
                                </div>
                                <div class="text-right"><span class="text-[11px] font-medium text-[#3b3b3b]">Utilized:</span><span class="text-lg font-bold text-[#990dd1]">₱1,250,000</span><span class="text-[11px] text-[#3b3b3b]"> / ₱3,500,000</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#3f6516] rounded-full progress-bar" style="width: 35.7%"></div>
                                </div><span class="text-[10px] font-semibold text-[#3b3b3b]">35.7% utilized</span>
                            </div>
                        </div>

                        <div class="p-6 hover:bg-[#b979cc]/5 transition bg-slate-50/20">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-[#000] text-[11px]">MANDATE 4: CHILD MINDING CENTER / BREASTFEEDING STATIONS</h5>
                                    <p class="text-[11px] text-[#3b3b3b] mt-1">Maintenance of child minding centers, lactation rooms across campuses</p>
                                </div>
                                <div class="text-right"><span class="text-[11px] font-medium text-[#3b3b3b]">Utilized:</span><span class="text-lg font-bold text-[#990dd1]">₱180,000</span><span class="text-[11px] text-[#3b3b3b]"> / ₱450,000</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#5170ff] rounded-full progress-bar" style="width: 40%"></div>
                                </div><span class="text-[10px] font-semibold text-[#3b3b3b]">40% utilized</span>
                            </div>
                        </div>

                        <div class="p-6 hover:bg-[#b979cc]/5 transition">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-[#000] text-[11px]">MANDATE 5: GFPS CAPACITY BUILDING & GAD TRAININGS</h5>
                                    <p class="text-[11px] text-[#3b3b3b] mt-1">Gender Mainstreaming capability building, GAD seminars for personnel</p>
                                </div>
                                <div class="text-right"><span class="text-[11px] font-medium text-[#3b3b3b]">Utilized:</span><span class="text-lg font-bold text-[#990dd1]">₱396,000</span><span class="text-[11px] text-[#3b3b3b]"> / ₱3,896,000</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#990dd1] rounded-full progress-bar" style="width: 10.2%"></div>
                                </div><span class="text-[10px] font-semibold text-[#3b3b3b]">10.2% utilized</span>
                            </div>
                        </div>

                        <div class="p-6 hover:bg-[#b979cc]/5 transition bg-slate-50/20">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-[#000] text-[11px]">MANDATE 6: CRISIS INTERVENTION / HYGIENE KITS</h5>
                                    <p class="text-[11px] text-[#3b3b3b] mt-1">Gender-responsive services to students/employees in crisis</p>
                                </div>
                                <div class="text-right"><span class="text-[11px] font-medium text-[#3b3b3b]">Utilized:</span><span class="text-lg font-bold text-[#990dd1]">₱200,000</span><span class="text-[11px] text-[#3b3b3b]"> / ₱210,000</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#ecd224] rounded-full progress-bar" style="width: 95.2%"></div>
                                </div><span class="text-[10px] font-semibold text-[#3b3b3b]">95.2% utilized</span>
                            </div>
                        </div>

                        <div class="p-6 hover:bg-[#b979cc]/5 transition">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h5 class="font-bold text-[#000] text-[11px]">MANDATE 7: GENDER-RESPONSIVE CURRICULAR PROGRAMS</h5>
                                    <p class="text-[11px] text-[#3b3b3b] mt-1">Integration of gender perspective in syllabi and classroom teaching</p>
                                </div>
                                <div class="text-right"><span class="text-[11px] font-medium text-[#3b3b3b]">Utilized:</span><span class="text-lg font-bold text-[#990dd1]">₱51,294,973</span><span class="text-[11px] text-[#3b3b3b]"> / ₱58,294,973</span></div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#3f6516] rounded-full progress-bar" style="width: 88%"></div>
                                </div><span class="text-[10px] font-semibold text-[#3b3b3b]">88% utilized</span>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="lg:col-span-3 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                            <h5 class="text-[11px] font-black uppercase tracking-wider text-[#3b3b3b] flex items-center gap-2"><span class="material-symbols-outlined text-[11px]">📜</span> Recent Budget Utilization Logs</h5>
                        </div>
                        <div class="divide-y divide-slate-100">
                            <div class="p-4 hover:bg-[#b979cc]/5 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-[10px] font-bold text-[#990dd1]">MAR 25, 2026</p>
                                        <p class="text-[11px] font-bold text-[#000] mt-1">GAD Orientation for First Year Students</p>
                                        <p class="text-[10px] text-[#3b3b3b] mt-0.5">Mandate: GAD Orientation & Student Leadership</p>
                                    </div>
                                    <p class="text-[11px] font-bold text-[#3f6516]">₱453,363</p>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-[#b979cc]/5 transition bg-slate-50/20">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-[10px] font-bold text-[#990dd1]">MAR 22, 2026</p>
                                        <p class="text-[11px] font-bold text-[#000] mt-1">Affirmative Action Program - Student Assistance</p>
                                        <p class="text-[10px] text-[#3b3b3b] mt-0.5">Mandate: Affirmative Action / Free Tuition</p>
                                        <p class="text-[9px] text-[#3b3b3b] font-mono mt-0.5">Control: GAD-2026-076</p>
                                    </div>
                                    <p class="text-[11px] font-bold text-[#3f6516]">₱700,000</p>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-[#b979cc]/5 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-[10px] font-bold text-[#990dd1]">MAR 18, 2026</p>
                                        <p class="text-[11px] font-bold text-[#000] mt-1">Distribution of Hygiene Kits (Crisis Intervention)</p>
                                        <p class="text-[10px] text-[#3b3b3b] mt-0.5">Mandate: Crisis Intervention / Hygiene Kits</p>
                                        <p class="text-[9px] text-[#3b3b3b] font-mono mt-0.5">Control: GAD-2026-054</p>
                                    </div>
                                    <p class="text-[11px] font-bold text-[#3f6516]">₱200,000</p>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-[#b979cc]/5 transition bg-slate-50/20">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-[10px] font-bold text-[#990dd1]">MAR 15, 2026</p>
                                        <p class="text-[11px] font-bold text-[#000] mt-1">GFPS Capacity Building Training</p>
                                        <p class="text-[10px] text-[#3b3b3b] mt-0.5">Mandate: GFPS Capacity Building & GAD Trainings</p>
                                        <p class="text-[9px] text-[#3b3b3b] font-mono mt-0.5">Control: GAD-2026-042</p>
                                    </div>
                                    <p class="text-[11px] font-bold text-[#3f6516]">₱396,000</p>
                                </div>
                            </div>
                            <div class="p-4 hover:bg-[#b979cc]/5 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-[10px] font-bold text-[#990dd1]">MAR 10, 2026</p>
                                        <p class="text-[11px] font-bold text-[#000] mt-1">GREP Extension: Community Technology Transfer</p>
                                        <p class="text-[10px] text-[#3b3b3b] mt-0.5">Mandate: GREP Extension / Community Programs</p>
                                        <p class="text-[9px] text-[#3b3b3b] font-mono mt-0.5">Control: GAD-2026-038</p>
                                    </div>
                                    <p class="text-[11px] font-bold text-[#3f6516]">₱1,250,000</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 border-t border-slate-100 bg-slate-50/50"><button class="w-full py-2 text-[10px] font-black uppercase tracking-wider text-[#990dd1] hover:bg-[#b979cc]/10 rounded-lg transition">View Full History Log →</button></div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
                        <h5 class="text-[10px] font-black uppercase tracking-wider text-[#3b3b3b] mb-4 flex items-center gap-2"><span class="material-symbols-outlined text-[11px]">🏛️</span> GAD Office Budget Breakdown</h5>
                        <ul class="space-y-3">
                            <li class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-[#000] text-[11px]">Curriculum Development</p>
                                    <p class="text-[9px] text-[#3b3b3b]">Gender-responsive programs</p>
                                </div><span class="font-bold text-[#990dd1]">₱58,294,973</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-[#000] text-[11px]">Institutional Support</p>
                                    <p class="text-[9px] text-[#3b3b3b]">GAD Office & GFPS operations</p>
                                </div><span class="font-bold text-[#990dd1]">₱9,542,090</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-[#000] text-[11px]">Training & Capacity Building</p>
                                    <p class="text-[9px] text-[#3b3b3b]">GST, GM capability building</p>
                                </div><span class="font-bold text-[#990dd1]">₱5,278,888</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-[#000] text-[11px]">Community Programs</p>
                                    <p class="text-[9px] text-[#3b3b3b]">Senior citizens, extension</p>
                                </div><span class="font-bold text-[#990dd1]">₱4,250,000</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-[#000] text-[11px]">Advocacy & IEC</p>
                                    <p class="text-[9px] text-[#3b3b3b]">VAW campaigns, materials</p>
                                </div><span class="font-bold text-[#990dd1]">₱746,000</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-[#000] text-[11px]">Facilities</p>
                                    <p class="text-[9px] text-[#3b3b3b]">Child minding, breastfeeding</p>
                                </div><span class="font-bold text-[#990dd1]">₱450,000</span>
                            </li>
                            <li class="flex justify-between items-center pt-2 border-t">
                                <div>
                                    <p class="font-bold text-[#000] text-[11px]">Total GAD Office Budget</p>
                                </div><span class="font-bold text-[#990dd1] text-lg">₱74,521,951</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-gradient-to-r from-[#990dd1] to-[#b979cc] rounded-xl p-5 text-white">
                        <div class="flex items-center gap-2 mb-3"><span class="material-symbols-outlined">💰</span><span class="text-[10px] font-bold uppercase tracking-wider">Budget Status</span></div>
                        <p class="text-2xl font-bold">₱175.33M</p>
                        <p class="text-[11px] text-purple-200 mt-1">Remaining for Q2-Q4</p>
                        <div class="mt-4 pt-3 border-t border-purple-400/30">
                            <p class="text-[10px]">22 GAD Office mandates | 28 total mandates tracked</p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
</body>

</html>