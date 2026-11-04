<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Primary Meta Tags -->
    <title><?= esc($post['title']) ?></title>
    <meta name="description" content="<?= esc(strip_tags($post['description'])) ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?= esc($currentUrl, 'attr') ?>">
    <meta property="og:title" content="<?= esc($post['title']) ?>">
    <meta property="og:description" content="<?= esc(strip_tags($post['description'])) ?>">
    <?php if ($imageUrl): ?>
    <meta property="og:image" content="<?= esc($imageUrl, 'attr') ?>">
    <!-- Provide dimensions if needed, FB recommends large images -->
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <?php endif; ?>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= esc($currentUrl, 'attr') ?>">
    <meta property="twitter:title" content="<?= esc($post['title']) ?>">
    <meta property="twitter:description" content="<?= esc(strip_tags($post['description'])) ?>">
    <?php if ($imageUrl): ?>
    <meta property="twitter:image" content="<?= esc($imageUrl, 'attr') ?>">
    <?php endif; ?>

    <script>
        // Redirect normal browsers to the Vue frontend immediately
        window.location.href = <?= json_encode($frontendUrl . '/gad-corner/' . (int)$post['id']) ?>;
    </script>
</head>
<body>
    <p>Redirecting you to the post... If you are not redirected, <a href="<?= esc($frontendUrl . '/gad-corner/' . (int)$post['id'], 'attr') ?>">click here</a>.</p>
</body>
</html>
