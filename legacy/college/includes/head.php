<?php
require_once __DIR__ . '/config.php';
if (!isset($page_title)) {
    $page_title = $gad_portal_label;
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title><?php echo htmlspecialchars($page_title); ?> — <?php echo htmlspecialchars($gad_college_name); ?></title>
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&amp;family=Playfair+Display:ital,wght@0,400..900;1,400..900&amp;display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            // Office of Student Services color scheme
            "primary": "#7c3aed",
            "primary-container": "#8b5cf6",
            "primary-light": "#ede9fe",
            "secondary": "#16a34a",
            "secondary-container": "#f97316",
            "on-primary": "#ffffff",
            "on-primary-container": "#ede9fe",
            "on-secondary": "#ffffff",
            "on-secondary-container": "#ffffff",
            "surface": "#f5f3ff",
            "surface-container-low": "#ede9fe",
            "surface-container-lowest": "#ffffff",
            "surface-container-high": "#e6e8ea",
            "on-surface": "#191c1e",
            "on-surface-variant": "#4c4353",
            "outline": "#7e7384",
            "outline-variant": "#cfc2d5",
            "error": "#ba1a1a",
            "error-container": "#ffdad6",
            "tertiary": "#573c00",
            "tertiary-container": "#755200",
            "tertiary-fixed": "#ffdea9",
          },
          fontFamily: {
            "headline": ["Manrope", "sans-serif"],
            "body": ["Manrope", "sans-serif"],
            "label": ["Manrope", "sans-serif"],
            "serif": ["Playfair Display", "serif"]
          },
          borderRadius: {
            "DEFAULT": "0.125rem",
            "lg": "0.25rem",
            "xl": "0.75rem",
            "full": "9999px"
          },
        },
      },
    }
  </script>
  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    body {
      font-family: 'Manrope', sans-serif;
      background-color: #f5f3ff;
    }
    .no-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .oss-gradient {
      background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 60%, #8b5cf6 100%);
    }
  </style>
<?php if (!empty($include_sweetalert)): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      <?php
      if (isset($_SESSION['alert']) && !empty($_SESSION['alert'])):
        $alert = $_SESSION['alert'];
        $type = $alert['type'] ?? 'info';
        $title = $alert['title'] ?? '';
        $message = $alert['message'] ?? '';
      ?>
        Swal.fire({
          icon: '<?php echo htmlspecialchars($type); ?>',
          title: '<?php echo htmlspecialchars($title); ?>',
          text: '<?php echo htmlspecialchars($message); ?>',
          timer: 2000,
          timerProgressBar: true,
          showConfirmButton: false,
          allowOutsideClick: true,
          allowEscapeKey: true
        });
      <?php
        unset($_SESSION['alert']);
      endif;
      ?>
    });
  </script>
<?php endif; ?>
</head>