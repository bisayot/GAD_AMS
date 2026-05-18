<?php
// src/Core/Controller.php

namespace App\Core;

abstract class Controller {
    protected function view($view, $data = [], $layout = 'main') {
        // Extract data to make variables available in the view
        extract($data);
        
        $viewFile = ROOT_DIR . '/src/Views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            // If a layout is specified, capture the view content and include the layout
            if ($layout) {
                ob_start();
                require_once $viewFile;
                $content = ob_get_clean();
                
                $layoutFile = ROOT_DIR . '/src/Views/layouts/' . $layout . '.php';
                if (file_exists($layoutFile)) {
                    require_once $layoutFile;
                } else {
                    die("Layout $layout not found.");
                }
            } else {
                require_once $viewFile;
            }
        } else {
            die("View $view not found.");
        }
    }

    protected function redirect($url) {
        header("Location: " . APP_URL . $url);
        exit;
    }
}
