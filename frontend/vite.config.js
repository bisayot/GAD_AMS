import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

import fs from 'fs'

const generateIndexPhpPlugin = () => ({
  name: 'generate-index-php',
  closeBundle() {
    const htmlPath = 'dist/index.html';
    const phpPath = 'dist/index.php';
    if (fs.existsSync(htmlPath)) {
      const htmlContent = fs.readFileSync(htmlPath, 'utf-8');
      const phpCode = `<?php
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$isBot = preg_match('/(facebookexternalhit|twitterbot|linkedinbot|whatsapp|skype|telegrambot|viber|slackbot|discordbot|applebot)/i', $userAgent);

if ($isBot && preg_match('/^\\/gad-corner\\/([0-9]+)/', $_SERVER['REQUEST_URI'], $matches)) {
    $id = $matches[1];
    $apiUrl = "https://bsugad.com/api/news-iec/" . $id;
    
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    $post = null;
    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['success']) && $data['success'] && isset($data['data'])) {
            $post = $data['data'];
        }
    }

    if ($post) {
        $title = htmlspecialchars(trim(preg_replace('/\\s+/', ' ', $post['title'] ?? 'GAD Corner Post')));
        $description = htmlspecialchars(trim(preg_replace('/\\s+/', ' ', strip_tags($post['description'] ?? ''))));
        
        $imageUrl = '';
        if (!empty($post['image_path'])) {
            $images = json_decode($post['image_path'], true);
            if (is_array($images) && count($images) > 0) {
                $imageUrl = "https://bsugad.com/api/files/news-iec/" . urlencode($images[0]);
            } else if (is_string($images)) {
                $imageUrl = "https://bsugad.com/api/files/news-iec/" . urlencode($images);
            }
        }
        
        $currentUrl = "https://bsugad.com/gad-corner/" . $id;
        
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>'.$title.'</title>
    <meta name="description" content="'.$description.'">
    <meta property="og:type" content="article">
    <meta property="og:url" content="'.$currentUrl.'">
    <meta property="og:title" content="'.$title.'">
    <meta property="og:description" content="'.$description.'">';
        if ($imageUrl) {
            echo '
    <meta property="og:image" content="'.$imageUrl.'">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="twitter:image" content="'.$imageUrl.'">';
        }
        echo '
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="'.$currentUrl.'">
    <meta property="twitter:title" content="'.$title.'">
    <meta property="twitter:description" content="'.$description.'">
    <script>window.location.replace("'.$currentUrl.'");</script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>';
        exit;
    }
}
?>
` + htmlContent;

      fs.writeFileSync(phpPath, phpCode);
      fs.unlinkSync(htmlPath); // Remove html to force PHP execution
      console.log('✅ Generated index.php for bulletproof Facebook sharing!');
    }
  }
});

export default defineConfig({
  plugins: [vue(), generateIndexPhpPlugin()],
  optimizeDeps: {
    exclude: ['canvas', 'path2d']
  }
})
