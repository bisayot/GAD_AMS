<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login | BSU GAD Portal</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-container": "#eceef0",
                        "on-tertiary-fixed-variant": "#5e4100",
                        "error-container": "#ffdad6",
                        primary: "#6100a4",
                        "secondary-fixed": "#b1f0ce",
                        "on-error": "#ffffff",
                        "secondary-container": "#aeeecb",
                        "on-tertiary-container": "#ffc65c",
                        "on-background": "#191c1e",
                        outline: "#7e7384",
                        "surface-container-low": "#f2f4f6",
                        "on-tertiary-fixed": "#271900",
                        "tertiary-container": "#755200",
                        "secondary-fixed-dim": "#95d4b3",
                        "primary-container": "#7b2cbf",
                        "surface-container-high": "#e6e8ea",
                        "surface-bright": "#f8f9fb",
                        "on-secondary-fixed-variant": "#0e5138",
                        "surface-variant": "#e0e3e5",
                        "on-error-container": "#93000a",
                        "on-tertiary": "#ffffff",
                        "on-secondary-container": "#316e52",
                        "inverse-on-surface": "#eff1f3",
                        "surface-container-highest": "#e0e3e5",
                        "on-primary-fixed-variant": "#680eac",
                        "surface-tint": "#8234c6",
                        "on-primary-fixed": "#2d0050",
                        "on-primary": "#ffffff",
                        surface: "#f8f9fb",
                        "surface-dim": "#d8dadc",
                        "outline-variant": "#cfc2d5",
                        "primary-fixed-dim": "#deb7ff",
                        "on-surface-variant": "#4c4353",
                        "on-secondary": "#ffffff",
                        secondary: "#2c694e",
                        "primary-fixed": "#f1dbff",
                        "on-primary-container": "#e4c2ff",
                        error: "#ba1a1a",
                        tertiary: "#573c00",
                        "on-secondary-fixed": "#002114",
                        "tertiary-fixed-dim": "#ffba27",
                        background: "#f8f9fb",
                        "surface-container-lowest": "#ffffff",
                        "inverse-primary": "#deb7ff",
                        "tertiary-fixed": "#ffdea9",
                        "inverse-surface": "#2d3133",
                        "on-surface": "#191c1e"
                    },
                    fontFamily: {
                        headline: ["Manrope"],
                        body: ["Manrope"],
                        label: ["Manrope"],
                        display: "Manrope"
                    },
                    borderRadius: {
                        DEFAULT: "0.125rem",
                        lg: "0.25rem",
                        xl: "0.5rem",
                        full: "0.75rem"
                    }
                }
            }
        };
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .bg-login-texture {
            background-color: #f9f9fb;
            background-image: radial-gradient(#422b68 0.5px, transparent 0.5px), radial-gradient(#422b68 0.5px, #f9f9fb 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.03;
        }
    </style>
</head>

<body class="bg-background text-on-surface font-body min-h-screen flex flex-col selection:bg-primary-fixed selection:text-on-primary-fixed">

    <?php include ROOT_DIR . '/header.php'; ?>

    <!-- Main Content Canvas -->
    <main class="flex-grow flex items-center justify-center pt-24 pb-16 px-6 relative overflow-hidden">
        <!-- Background Decorative Elements (Asymmetry) -->
        <div class="absolute inset-0 bg-login-texture -z-10"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary/5 rounded-full blur-3xl"></div>
        <div class="w-full max-w-md">
            <!-- Brand Anchor -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container mb-6">
                    <span class="material-symbols-outlined text-primary text-3xl">account_balance</span>
                </div>
                <h1 class="font-headline text-3xl font-extrabold text-on-surface tracking-tight mb-2">Welcome Back</h1>
                <p class="text-on-surface-variant text-sm max-w-xs mx-auto">Log in to the Benguet State University Gender and Development Office Portal</p>
            </div>
            <!-- Login Card (Surface Hierarchy) -->
            <div class="bg-surface-container-lowest rounded-xl shadow-[0_8px_32px_rgba(26,28,29,0.04)] p-8 md:p-10 border border-outline-variant/15 relative overflow-hidden">
                <!-- Scholarly Accent -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-secondary/40"></div>
                <form action="/GAD_v12/login" class="space-y-6" method="POST">
                    <?php if (!empty($loginError)): ?>
                        <div class="rounded-md bg-red-100 border border-red-200 text-red-700 px-3 py-2 text-sm mb-3"><?php echo htmlspecialchars($loginError); ?></div>
                    <?php endif; ?>
                    <!-- Input Group -->
                    <div class="space-y-2">
                        <label class="block font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant px-1" for="identity">Username or Email</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline transition-colors group-focus-within:text-primary">person</span>
                            <input class="w-full pl-12 pr-4 py-4 bg-surface-container-low border-none rounded-lg focus:ring-0 focus:bg-surface-bright focus:border-b-2 focus:border-primary transition-all duration-200 text-on-surface placeholder:text-outline/50" id="identity" name="identity" placeholder="e.g. j.doe@bsu.edu.ph" required="" type="text" />
                        </div>
                    </div>
                    <!-- Input Group -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-end px-1">
                            <label class="block font-label text-xs font-bold uppercase tracking-widest text-on-surface-variant" for="password">Password</label>
                            <a class="text-primary text-[10px] font-bold uppercase tracking-wider hover:underline underline-offset-4 decoration-2" href="#">Forgot Password?</a>
                        </div>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline transition-colors group-focus-within:text-primary">lock</span>
                            <input class="w-full pl-12 pr-12 py-4 bg-surface-container-low border-none rounded-lg focus:ring-0 focus:bg-surface-bright focus:border-b-2 focus:border-primary transition-all duration-200 text-on-surface placeholder:text-outline/50" id="password" name="password" placeholder="••••••••" required="" type="password" />
                            <button class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface-variant transition-colors" type="button">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-1">
                        <input class="w-4 h-4 rounded border-outline text-primary focus:ring-primary" id="remember" name="remember" type="checkbox" />
                        <label class="text-sm text-on-surface-variant select-none" for="remember">Remember this device</label>
                    </div>
                    <!-- CTA -->
                    <button class="w-full py-4 px-6 bg-gradient-to-br from-primary to-primary-container text-white font-headline font-bold rounded-full shadow-lg shadow-primary/20 hover:opacity-90 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2" type="submit">
                        Sign In to Dashboard
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </form>
                <div class="mt-8 pt-8 border-t border-outline-variant/15 text-center">
                    <p class="text-sm text-on-surface-variant font-body">
                        Are you a visitor?
                        <a class="text-secondary font-bold hover:underline underline-offset-4 decoration-2 ml-1" href="#">Explore Public Records</a>
                    </p>
                </div>
            </div>
            <!-- Supporting Information -->
            <div class="mt-12 grid grid-cols-2 gap-4">
                <div class="bg-surface-container rounded-xl p-4 flex items-start gap-3">
                    <span class="material-symbols-outlined text-secondary text-xl">verified_user</span>
                    <div>
                        <h4 class="font-headline font-bold text-xs text-on-surface">Secure Access</h4>
                        <p class="text-[10px] text-on-surface-variant leading-relaxed">Encrypted authentication for BSU personnel and students.</p>
                    </div>
                </div>
                <div class="bg-surface-container rounded-xl p-4 flex items-start gap-3">
                    <span class="material-symbols-outlined text-secondary text-xl">help_center</span>
                    <div>
                        <h4 class="font-headline font-bold text-xs text-on-surface">Need Help?</h4>
                        <p class="text-[10px] text-on-surface-variant leading-relaxed">Contact the ICT Support Desk for login issues.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Footer (Suppressed for transactional flow - Minimalist version) -->
    <footer class="w-full py-8 bg-slate-50 dark:bg-slate-900 border-t border-slate-200/10">
        <div class="flex flex-col items-center justify-center gap-4 px-4">
            <div class="flex gap-8">
                <a class="font-label text-xs uppercase tracking-widest text-slate-400 hover:text-[#422b68] transition-colors" href="#">Privacy Policy</a>
                <a class="font-label text-xs uppercase tracking-widest text-slate-400 hover:text-[#422b68] transition-colors" href="#">Terms of Service</a>
            </div>
            <p class="font-label text-[10px] uppercase tracking-[0.2em] text-slate-400">
                © 2024 Benguet State University Gender and Development Office
            </p>
        </div>
    </footer>
    <!-- Decorative Corner Image (Asymmetric Layout) -->
    <div class="hidden lg:block fixed bottom-0 right-0 w-1/4 h-1/2 opacity-20 pointer-events-none">
        <img class="w-full h-full object-cover object-left-top grayscale brightness-50 contrast-125" data-alt="Modern academic building architecture with clean lines and large glass windows under a clear blue sky" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBHAf74I2_AiHYkET_IzjoR62qXkGLNbbGMpSOfmjFzA7UNHjAbgtzf2kJCafDimWo7lHCFHlk_2L4hBnqLqbqP4HtPzyzPF2cDVbVC4WgkNLTtVQ5eJRHX1vx-MP2aOU3krKgG1-Us3PlWS35qrDg_VNN4NWaWnQp1rrIFmTUi_Zb3elsk5OMlWLjtenFVqDRVoMwv1gRjAMEWaDmBMRl2mG9kQ8lwuclGRJz3PNqSrk_DAii2baLoIA60Jto68VVcylJJXb_BUP3G" />
    </div>
</body>

</html>
