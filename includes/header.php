<?php
require_once __DIR__ . '/config.php';
$pageTitle = $pageTitle ?? SITE_NAME;
$active = $active ?? '';
$navItems = [
    'home' => ['index.php', 'Home'],
    'treks' => ['treks.php', 'Treks'],
    'about' => ['about.php', 'About'],
    'contact' => ['contact.php', 'Contact'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> | <?= e(SITE_NAME) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Space+Mono:wght@400;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<header class="tn-nav">
  <div class="container d-flex align-items-center justify-content-between flex-wrap py-3">
    <a href="index.php" class="tn-brand">
      <span class="tn-brand-mark"><i class="bi bi-triangle-fill"></i></span>
      <span><?= e(SITE_NAME) ?><small>trail journal &amp; booking</small></span>
    </a>
    <nav class="tn-tabs">
      <?php foreach ($navItems as $key => [$href, $label]): ?>
        <a href="<?= $href ?>" class="<?= $active === $key ? 'active' : '' ?>"><?= $label ?></a>
      <?php endforeach; ?>
      <a href="treks.php#wishlist" class="tn-wishlist-link"><i class="bi bi-bookmark-heart"></i> <span id="wishlistCount">0</span></a>
    </nav>
  </div>
</header>
