<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Director Overview | GAD-IMS</title>
    <!-- Chart.js CDN (kept as it's a functional library, not a styling dependency) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .clickable-row {
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .clickable-row:hover {
            background-color: #b979cc20;
        }

        .deadline-badge-urgent {
            background: #fee2e2;
            color: #991b1b;
        }

        .deadline-badge-warning {
            background: #fef3c7;
            color: #b45309;
        }

        .deadline-badge-normal {
            background: #d1fae5;
            color: #065f46;
        }

        .chart-container {
            background: white;
            border-radius: 1.5rem;
            padding: 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03), 0 4px 8px rgba(0, 0, 0, 0.05);
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

        .top-0 {
            top: 0;
        }

        .top-1 {
            top: 4px;
        }

        .top-16 {
            top: 64px;
        }

        .top-20 {
            top: 80px;
        }

        .right-0 {
            right: 0;
        }

        .right-1 {
            right: 4px;
        }

        .right-10 {
            right: 40px;
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

        .bottom-6 {
            bottom: 24px;
        }

        .inset-0 {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .z-40 {
            z-index: 40;
        }

        .z-50 {
            z-index: 50;
        }

        .z-\[200\] {
            z-index: 200;
        }

        .col-span-10 {
            grid-column: span 10 / span 10;
        }

        .col-span-12 {
            grid-column: span 12 / span 12;
        }

        .float-right {
            float: right;
        }

        .m-1 {
            margin: 4px;
        }

        .m-4 {
            margin: 16px;
        }

        .-m-1 {
            margin: -4px;
        }

        .my-12 {
            margin-top: 48px;
            margin-bottom: 48px;
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

        .mb-10 {
            margin-bottom: 40px;
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

        .mt-6 {
            margin-top: 24px;
        }

        .mt-auto {
            margin-top: auto;
        }

        .ml-2 {
            margin-left: 8px;
        }

        .ml-4 {
            margin-left: 16px;
        }

        .ml-9 {
            margin-left: 36px;
        }

        .mr-1 {
            margin-right: 4px;
        }

        .block {
            display: block;
        }

        .inline-block {
            display: inline-block;
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

        .max-h-48 {
            max-height: 192px;
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

        .min-h-\[180px\] {
            min-height: 1, 0px;
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

        .border-slate-200\/40 {
            border-color: rgba(226, 232, 240, 0.4);
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
            padding-top: 8px;
            padding-bottom: 8px;
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

        /* Card Color Themes */
        .card-pending-act {
            background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%);
            border-left: 4px solid #f59e0b;
        }

        .card-pending-reports {
            background: linear-gradient(135deg, #f3e8ff 0%, #faf5ff 100%);
            border-left: 4px solid #8b5cf6;
        }

        .card-total-budget {
            background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
            border-left: 4px solid #3b82f6;
        }

        .card-remaining {
            background: linear-gradient(135deg, #d1fae5 0%, #ecfdf5 100%);
            border-left: 4px solid #10b981;
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

        .gap-10 {
            gap: 40px;
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
    <?php include 'sidebar.php'; ?>
    <header class="staff-header">
        <div>
            <h2 class="header-title">Gender and Development - Information Management System (GAD-IMS)</h2>
            <div class="flex items-center gap-2">
                <span class="header-subtitle">Gender and Development Office</span>
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

    <main class="pl-64 pt-16 min-h-screen">
        <div class="p-10">
            <!-- Welcome section -->
            <section class="mb-10 mt-6">
                <h3 class="text-[2.6rem] font-extrabold text-[#000] tracking-tighter leading-tight mb-2">Welcome, (Director) to your Dashboard!</h3>
                <p class="text-[#3b3b3b] max-w-2xl text-[0.9rem] font-light">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
            </section>

            <!-- Stats Cards - Colorful -->
            <section class="grid grid-cols-12 gap-8 mb-10">
                <!-- Card 1: Pending Act Designs - Yellow Theme -->
                <div class="col-span-12 lg:col-span-3 p-8 card-pending-act rounded-2xl flex flex-col justify-between min-h-[180px] stat-card shadow-sm">
                    <div class="flex justify-between items-start">
                        <span class="text-[0.85rem] font-bold text-amber-600 uppercase tracking-widest">Pending Act Designs</span>
                        <span class="material-symbols-outlined text-amber-500 text-3xl">📄</span>
                    </div>
                    <div>
                        <span class="text-4xl font-extrabold text-[#000] tracking-tighter">03</span>
                    </div>
                </div>
                <!-- Card 2: Pending Acc Reports - Purple Theme -->
                <div class="col-span-12 lg:col-span-3 p-8 card-pending-reports rounded-2xl flex flex-col justify-between min-h-[180px] stat-card shadow-sm">
                    <div class="flex justify-between items-start">
                        <span class="text-[0.85rem] font-bold text-purple-600 uppercase tracking-widest">Pending Acc Reports</span>
                        <span class="material-symbols-outlined text-purple-500 text-3xl">📊</span>
                    </div>
                    <div>
                        <span class="text-4xl font-extrabold text-[#000] tracking-tighter">02</span>
                    </div>
                </div>
                <!-- Card 3: Total GAD Budget - Blue Theme -->
                <div class="col-span-12 lg:col-span-3 p-8 card-total-budget rounded-2xl flex flex-col justify-between min-h-[180px] stat-card shadow-sm">
                    <div class="flex justify-between items-start">
                        <span class="text-[0.85rem] font-bold text-blue-600 uppercase tracking-widest">Total GAD Budget</span>
                        <span class="material-symbols-outlined text-blue-500 text-3xl">💰</span>
                    </div>
                    <div>
                        <span class="text-2xl font-extrabold text-[#000] tracking-tighter">₱ 243.54M</span>
                    </div>
                </div>
                <!-- Card 4: Remaining Balance - Green Theme -->
                <div class="col-span-12 lg:col-span-3 p-8 card-remaining rounded-2xl flex flex-col justify-between min-h-[180px] stat-card shadow-sm">
                    <div class="flex justify-between items-start">
                        <span class="text-[0.85rem] font-bold text-emerald-600 uppercase tracking-widest">Remaining Balance</span>
                        <span class="material-symbols-outlined text-emerald-500 text-3xl">💵</span>
                    </div>
                    <div>
                        <span class="text-2xl font-extrabold text-[#000] tracking-tighter">₱ 102.41M</span>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="grid grid-cols-10 gap-10">
                <div class="col-span-10 lg:col-span-7 space-y-8">
                    <!-- Charts Section: Budget Utilization & SDD Enrollment -->
                    <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="chart-container">
                            <h4 class="text-sm font-bold text-[#000] mb-3 flex items-center gap-2"><span class="material-symbols-outlined text-[#990dd1]">🍩</span> Budget Utilization by Category</h4>
                            <canvas id="budgetDonutChart" width="400" height="300" style="max-height: 220px; width:100%"></canvas>
                        </div>
                        <div class="chart-container">
                            <h4 class="text-sm font-bold text-[#000] mb-3 flex items-center gap-2"><span class="material-symbols-outlined text-[#5170ff]">👥</span> SDD: BSU Enrollment by Year (Male vs Female)</h4>
                            <canvas id="sddEnrollmentChart" width="400" height="300" style="max-height: 220px; width:100%"></canvas>
                        </div>
                    </div> -->

                    <!-- Pending Director Action Cards -->
                    <!-- <div>
                        <div class="flex justify-between">
                            <h4 class="text-2xl font-bold text-[#000]">Pending Director Action</h4>
                            <button class="text-[#990dd1] font-bold text-[12px] hover:underline">View All Queue →</button>
                        </div>
                        <div class="space-y-4 mt-4">
                            <div class="p-6 bg-white hover:bg-[#b979cc]/10 transition flex items-center gap-8 rounded-3xl shadow-sm border">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center"><span class="material-symbols-outlined text-[#990dd1] text-3xl">📄</span></div>
                                <div class="flex-1 grid grid-cols-3 gap-4">
                                    <div><p class="text-[11px] text-[#3b3b3b] font-bold uppercase">Control No.</p><p class="font-bold text-[#000] text-[14px]">BSU-GAD-2024-082</p></div>
                                    <div><p class="text-[11px] text-[#3b3b3b] font-bold uppercase">Proponent</p><p class="font-medium text-[#3b3b3b] text-[13px]">College of Arts & Sciences</p></div>
                                    <div><p class="text-[11px] text-[#3b3b3b] font-bold uppercase">Budget</p><p class="font-bold text-[#000] text-[14px]">₱245,000.00</p></div>
                                </div>
                                <button class="bg-[#990dd1] text-white px-6 py-3 rounded-xl font-bold text-[12px] shadow-md hover:bg-[#b979cc]">Verify & Sign</button>
                            </div>
                            <div class="p-6 bg-white hover:bg-[#b979cc]/10 transition flex items-center gap-8 rounded-3xl shadow-sm border">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center"><span class="material-symbols-outlined text-[#990dd1] text-3xl">📈</span></div>
                                <div class="flex-1 grid grid-cols-3 gap-4">
                                    <div><p class="text-[11px] text-[#3b3b3b] font-bold uppercase">Control No.</p><p class="font-bold text-[#000] text-[14px]">BSU-GAD-2024-095</p></div>
                                    <div><p class="text-[11px] text-[#3b3b3b] font-bold uppercase">Proponent</p><p class="font-medium text-[#3b3b3b] text-[13px]">Human Resource Dept.</p></div>
                                    <div><p class="text-[11px] text-[#3b3b3b] font-bold uppercase">Budget</p><p class="font-bold text-[#000] text-[14px]">₱89,500.00</p></div>
                                </div>
                                <button class="bg-[#990dd1] text-white px-6 py-3 rounded-xl font-bold text-[12px] shadow-md hover:bg-[#b979cc]">Verify & Sign</button>
                            </div>
                            <div class="p-6 bg-white hover:bg-[#b979cc]/10 transition flex items-center gap-8 rounded-3xl shadow-sm border">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center"><span class="material-symbols-outlined text-[#990dd1] text-3xl">🏛️</span></div>
                                <div class="flex-1 grid grid-cols-3 gap-4">
                                    <div><p class="text-[11px] text-[#3b3b3b] font-bold uppercase">Control No.</p><p class="font-bold text-[#000] text-[14px]">BSU-GAD-2024-101</p></div>
                                    <div><p class="text-[11px] text-[#3b3b3b] font-bold uppercase">Proponent</p><p class="font-medium text-[#3b3b3b] text-[13px]">Extension Office</p></div>
                                    <div><p class="text-[11px] text-[#3b3b3b] font-bold uppercase">Budget</p><p class="font-bold text-[#000] text-[14px]">₱1,200,000.00</p></div>
                                </div>
                                <button class="bg-[#990dd1] text-white px-6 py-3 rounded-xl font-bold text-[12px] shadow-md hover:bg-[#b979cc]">Verify & Sign</button>
                            </div>
                        </div>
                    </div> -->

                    <!-- Needs Director Review Table -->
                    <div>
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="text-2xl font-bold text-[#000] tracking-tight">Needs Director Review</h4>
                                <p class="text-[#3b3b3b] text-[12px] mt-1">Recently submitted by TWG requiring your assessment</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border overflow-hidden mt-4">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="bg-slate-50 border-b">
                                            <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#3b3b3b]">Office / Unit</th>
                                            <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#3b3b3b]">Activity Title</th>
                                            <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#3b3b3b]">Type</th>
                                            <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#3b3b3b]">Submission Date</th>
                                            <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#3b3b3b]">Status</th>
                                            <!-- <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-[#3b3b3b]">Action</th> -->
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <tr class="clickable-row hover:bg-[#b979cc]/10 transition">
                                            <td class="px-6 py-4 text-[13px] text-[#3b3b3b]">College of Education</td>
                                            <td class="px-6 py-4 font-medium text-[#000] text-[13px]">Lorem ipsum dolor sit amet.</td>
                                            <td class="px-6 py-4"><span class="inline-block px-3 py-1 rounded-full bg-[#b979cc]/20 text-[#990dd1] text-[11px] font-black uppercase">Activity Design</span></td>
                                            <td class="px-6 py-4 text-[13px] text-[#3b3b3b]">2025-03-24</td>
                                            <td class="px-6 py-4"><span class="inline-block px-3 py-1 rounded-full bg-[#ecd224]/20 text-[#ecd224] text-[11px] font-black uppercase">For Review</span></td>
                                            <!-- <td class="px-6 py-4"><button class="bg-[#990dd1] text-white px-4 py-2 rounded-lg text-[11px] font-bold hover:bg-[#b979cc]">Review</button></td> -->
                                        </tr>
                                        <tr class="clickable-row hover:bg-[#b979cc]/10 transition">
                                            <td class="px-6 py-4 text-[13px] text-[#3b3b3b]">Office of Student Affairs</td>
                                            <td class="px-6 py-4 font-medium text-[#000] text-[13px]">Lorem ipsum dolor sit amet.</td>
                                            <td class="px-6 py-4"><span class="inline-block px-3 py-1 rounded-full bg-[#3f6516]/20 text-[#3f6516] text-[11px] font-black uppercase">Accomplishment Report</span></td>
                                            <td class="px-6 py-4 text-[13px] text-[#3b3b3b]">2025-03-22</td>
                                            <td class="px-6 py-4"><span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-700 text-[11px] font-black uppercase">For Revision</span></td>
                                            <!-- <td class="px-6 py-4"><button class="bg-[#990dd1] text-white px-4 py-2 rounded-lg text-[11px] font-bold hover:bg-[#b979cc]">Review</button></td> -->
                                        </tr>
                                        <tr class="clickable-row hover:bg-[#b979cc]/10 transition">
                                            <td class="px-6 py-4 text-[13px] text-[#3b3b3b]">Research & Extension</td>
                                            <td class="px-6 py-4 font-medium text-[#000] text-[13px]">Lorem ipsum dolor sit amet.</td>
                                            <td class="px-6 py-4"><span class="inline-block px-3 py-1 rounded-full bg-[#b979cc]/20 text-[#990dd1] text-[11px] font-black uppercase">Activity Design</span></td>
                                            <td class="px-6 py-4 text-[13px] text-[#3b3b3b]">2025-03-20</td>
                                            <td class="px-6 py-4"><span class="inline-block px-3 py-1 rounded-full bg-[#ecd224]/20 text-[#ecd224] text-[11px] font-black uppercase">For Review</span></td>
                                            <!-- <td class="px-6 py-4"><button class="bg-[#990dd1] text-white px-4 py-2 rounded-lg text-[11px] font-bold hover:bg-[#b979cc]">Review</button></td> -->
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                <p class="text-[11px] text-[#3b3b3b]">Showing 3 of 7 items needing review</p><a href="#" class="text-[#990dd1] text-[11px] font-bold uppercase tracking-wider hover:underline"> View All →</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Calendar & Deadlines -->
                <div class="col-span-10 lg:col-span-3 space-y-6">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="font-bold text-[#000] flex items-center gap-2 text-[14px]"><span class="material-symbols-outlined text-[#990dd1]">📅</span> Deadlines & Schedule</h4>
                            <div class="flex gap-1"><button id="prevMonthBtn" class="p-1 rounded hover:bg-slate-100">←</button><span id="currentMonthYear" class="text-[13px] font-medium">Mar 2026</span><button id="nextMonthBtn" class="p-1 rounded hover:bg-slate-100">→</button></div>
                        </div>
                        <div id="calendarGrid"></div>
                        <div class="border-t pt-4 mt-4">
                            <h5 class="text-[11px] font-bold text-[#3b3b3b] uppercase tracking-wider mb-3">Upcoming Deadlines</h5>
                            <div id="upcomingDeadlinesList" class="space-y-3"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script>
        // DEADLINES & CALENDAR
        const deadlines = [{
            title: "Gender Sensitivity Training",
            dueDate: "2026-03-27",
            status: "warning"
        }, {
            title: "Digital Literacy for Women",
            dueDate: "2026-03-30",
            status: "normal"
        }, {
            title: "Solo Parent Support Seminar",
            dueDate: "2026-03-25",
            status: "urgent"
        }, {
            title: "Agri-Extension for Women Farmers",
            dueDate: "2026-04-05",
            status: "normal"
        }, {
            title: "Gender-Responsive Pedagogy Training",
            dueDate: "2026-04-10",
            status: "normal"
        }];
        let currentDate = new Date(2026, 2);

        function renderCalendar() {
            const year = currentDate.getFullYear(),
                month = currentDate.getMonth();
            const firstDayOfMonth = new Date(year, month, 1);
            const startDayOfWeek = firstDayOfMonth.getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            document.getElementById('currentMonthYear').innerText = `${monthNames[month]} ${year}`;
            let calendarHtml = `<div class="grid grid-cols-7 gap-1 text-center mb-2"><span class="text-[10px] font-bold text-[#3b3b3b]">S</span><span class="text-[10px] font-bold text-[#3b3b3b]">M</span><span class="text-[10px] font-bold text-[#3b3b3b]">T</span><span class="text-[10px] font-bold text-[#3b3b3b]">W</span><span class="text-[10px] font-bold text-[#3b3b3b]">T</span><span class="text-[10px] font-bold text-[#3b3b3b]">F</span><span class="text-[10px] font-bold text-[#3b3b3b]">S</span></div><div class="grid grid-cols-7 gap-1 text-center text-[13px]">`;
            let dayCounter = 1;
            for (let i = 0; i < 42; i++) {
                if (i < startDayOfWeek || dayCounter > daysInMonth) calendarHtml += `<span class="py-2 text-slate-300"></span>`;
                else {
                    const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(dayCounter).padStart(2,'0')}`;
                    const hasDeadline = deadlines.some(d => d.dueDate === dateStr);
                    const isUrgent = deadlines.some(d => d.dueDate === dateStr && d.status === 'urgent');
                    let badgeClass = '';
                    if (isUrgent) badgeClass = 'bg-red-100 text-red-700 rounded-full font-bold';
                    else if (hasDeadline) badgeClass = 'bg-[#b979cc]/20 text-[#990dd1] rounded-full font-bold';
                    calendarHtml += `<span class="py-2 ${badgeClass}">${dayCounter}</span>`;
                    dayCounter++;
                }
            }
            calendarHtml += `</div>`;
            document.getElementById('calendarGrid').innerHTML = calendarHtml;
        }

        function updateUpcomingDeadlines() {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const upcoming = deadlines.filter(d => new Date(d.dueDate) >= today).sort((a, b) => new Date(a.dueDate) - new Date(b.dueDate));
            let html = '';
            upcoming.forEach(deadline => {
                const dueDateObj = new Date(deadline.dueDate);
                const daysDiff = Math.ceil((dueDateObj - today) / (1000 * 60 * 60 * 24));
                let statusClass = '',
                    statusText = '';
                if (daysDiff <= 0) {
                    statusText = 'Due today';
                    statusClass = 'deadline-badge-urgent';
                } else if (daysDiff <= 3) {
                    statusText = `Due in ${daysDiff} days`;
                    statusClass = 'deadline-badge-urgent';
                } else if (daysDiff <= 7) {
                    statusText = `Due in ${daysDiff} days`;
                    statusClass = 'deadline-badge-warning';
                } else {
                    statusText = `Due in ${daysDiff} days`;
                    statusClass = 'deadline-badge-normal';
                }
                html += `<div class="flex items-start gap-3 p-2 hover:bg-slate-50 rounded-lg"><div class="w-1.5 h-1.5 rounded-full ${deadline.status==='urgent'?'bg-red-500':(deadline.status==='warning'?'bg-amber-500':'bg-emerald-500')} mt-2"></div><div class="flex-1"><p class="text-[13px] font-bold text-[#000]">${deadline.title}</p><p class="text-[10px] text-[#3b3b3b]">${dueDateObj.toLocaleDateString()}</p></div><span class="text-[10px] font-bold ${statusClass} px-2 py-0.5 rounded-full">${statusText}</span></div>`;
            });
            document.getElementById('upcomingDeadlinesList').innerHTML = html || '<p class="text-[11px] text-[#3b3b3b]">No upcoming deadlines</p>';
        }

        document.getElementById('prevMonthBtn').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });
        document.getElementById('nextMonthBtn').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });
        renderCalendar();
        updateUpcomingDeadlines();

        // CHART 1: Budget Donut
        const ctxDonut = document.getElementById('budgetDonutChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Client-Focused Programs', 'Organization-Focused', 'GAD Office Ops', 'Training & Capability'],
                datasets: [{
                    data: [131800000, 58000000, 25000000, 28741951],
                    backgroundColor: ['#990dd1', '#b979cc', '#5170ff', '#3f6516'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });

        // CHART 2: SDD Enrollment - Male vs Female by Year (2022-2026)
        const ctxSDD = document.getElementById('sddEnrollmentChart').getContext('2d');
        new Chart(ctxSDD, {
            type: 'bar',
            data: {
                labels: ['2022', '2023', '2024', '2025', '2026'],
                datasets: [{
                        label: 'Male Enrollment',
                        data: [14250, 14890, 15234, 15876, 16234],
                        backgroundColor: '#5170ff',
                        borderRadius: 8,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Female Enrollment',
                        data: [18420, 19123, 19876, 20543, 21345],
                        backgroundColor: '#990dd1',
                        borderRadius: 8,
                        barPercentage: 0.7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.dataset.label}: ${ctx.raw.toLocaleString()} students`
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Number of Students',
                            font: {
                                size: 10
                            }
                        },
                        ticks: {
                            stepSize: 5000,
                            callback: (val) => val.toLocaleString()
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Academic Year',
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    </script>

    <!-- Notification panel script -->
    <script>
        const notifBtn = document.getElementById('headerNotificationBtn');
        const panel = document.createElement('div');
        panel.id = 'notifPanel';
        panel.className = 'fixed top-16 right-10 w-80 bg-white rounded-xl shadow-xl border z-50 hidden';
        panel.innerHTML = `<div class="p-4 border-b"><h3 class="font-bold text-[#000]">Notifications</h3></div><div class="max-h-96 overflow-y-auto"><div class="p-4 hover:bg-[#b979cc]/10 border-b"><p class="text-[13px] font-medium">New activity design submitted</p><p class="text-[11px] text-[#3b3b3b]">College of Nursing · 5 min ago</p></div><div class="p-4 hover:bg-[#b979cc]/10 border-b bg-amber-50"><p class="text-[13px] font-medium">Budget routing requires attention</p><p class="text-[11px] text-[#3b3b3b]">Extension Office · 1 hour ago</p></div></div><div class="p-3 border-t bg-slate-50 text-center"><button class="text-[11px] font-bold text-[#990dd1]">View all</button></div>`;
        document.body.appendChild(panel);
        notifBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            panel.classList.toggle('hidden');
        });
        document.addEventListener('click', (e) => {
            if (!panel.contains(e.target) && !notifBtn.contains(e.target)) panel.classList.add('hidden');
        });
    </script>
</body>

</html>