<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?php echo $title ?? 'BSU GAD Office'; ?></title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        // Use the same tailwind config as in index.php
        tailwind.config = {
            darkMode: "class", 
            theme: {
                extend: {
                    colors: {
                        "on-secondary-fixed-variant": "#0e5138", 
                        "on-secondary-container": "#316e52", 
                        "inverse-on-surface": "#eff1f3", 
                        "on-primary": "#ffffff", 
                        "on-background": "#191c1e", 
                        "on-primary-fixed-variant": "#680eac", 
                        "surface-variant": "#e0e3e5", 
                        "primary-fixed-dim": "#deb7ff", 
                        "primary-container": "#7b2cbf", 
                        "inverse-surface": "#2d3133", 
                        background: "#f8f9fb", 
                        "on-primary-container": "#e4c2ff", 
                        "surface-bright": "#f8f9fb", 
                        "secondary-container": "#aeeecb", 
                        "outline-variant": "#cfc2d5", 
                        "on-secondary": "#ffffff", 
                        error: "#ba1a1a", 
                        "surface-container-highest": "#e0e3e5", 
                        "tertiary-container": "#755200", 
                        "primary-fixed": "#f1dbff", 
                        "on-surface": "#191c1e", 
                        "on-error-container": "#93000a", 
                        "tertiary-fixed": "#ffdea9", 
                        surface: "#f8f9fb", 
                        "error-container": "#ffdad6", 
                        "on-tertiary-container": "#ffc65c", 
                        "surface-container-low": "#f2f4f6", 
                        "on-tertiary-fixed-variant": "#5e4100", 
                        "surface-dim": "#d8dadc", 
                        "secondary-fixed-dim": "#95d4b3", 
                        primary: "#6100a4", 
                        "surface-container-high": "#e6e8ea", 
                        "surface-tint": "#8234c6", 
                        "on-surface-variant": "#4c4353", 
                        "on-primary-fixed": "#2d0050", 
                        "tertiary-fixed-dim": "#ffba27", 
                        "surface-container-lowest": "#ffffff", 
                        "on-error": "#ffffff", 
                        tertiary: "#573c00", 
                        secondary: "#2c694e", 
                        "on-tertiary": "#ffffff", 
                        "on-tertiary-fixed": "#271900", 
                        "inverse-primary": "#deb7ff", 
                        outline: "#7e7384", 
                        "on-secondary-fixed": "#002114", 
                        "surface-container": "#eceef0", 
                        "secondary-fixed": "#b1f0ce"
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
      .glass-effect {
        backdrop-filter: blur(16px);
        background: rgba(255, 255, 255, 0.8);
      }
      .ambient-shadow {
        box-shadow: 0 8px 24px rgba(26, 28, 29, 0.04);
      }
      .hero-gradient {
        background: linear-gradient(135deg, rgba(66, 43, 104, 0.95) 0%, rgba(90, 66, 129, 0.8) 100%);
      }
    </style>
    <!-- SweetAlert2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-background text-on-surface font-body selection:bg-primary-fixed selection:text-on-primary-fixed">

    <?php include ROOT_DIR . '/header.php'; ?>

    <main class="<?php echo $mainClass ?? 'pt-16'; ?>">
        <?php echo $content; ?>
    </main>

    <!-- Footer could go here if needed -->

</body>
</html>
