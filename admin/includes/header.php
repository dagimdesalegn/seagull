<?php
require_once __DIR__ . '/../../includes/functions.php';
if (!is_admin_logged_in()) redirect(SITE_URL . '/admin/login.php');
$__counts = [
  'products'  => (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
  'orders'    => (int)$pdo->query("SELECT COUNT(*) FROM orders WHERE status='pending'")->fetchColumn(),
  'customers' => (int)$pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn(),
];
$__cur = basename($_SERVER['PHP_SELF']);
function nav_active($file, $cur){ return $file === $cur ? 'active' : ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= isset($pageTitle) ? e($pageTitle) . ' | Admin' : 'Admin' ?> â€” <?= SITE_NAME ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body class="admin">
<div class="admin-shell">

  <aside class="admin-side" id="adminSide">
    <div class="admin-brand">
      <div class="brand-mark">ST</div>
      <div>
        <strong>SEAGULL</strong>
        <small>Admin Panel</small>
      </div>
    </div>

    <div class="admin-nav-section">
      <h5>Overview</h5>
      <ul class="admin-nav">
        <li><a href="<?= SITE_URL ?>/admin/dashboard.php" class="<?= nav_active('dashboard.php', $__cur) ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
          Dashboard
        </a></li>
      </ul>
    </div>

    <div class="admin-nav-section">
      <h5>Catalog</h5>
      <ul class="admin-nav">
        <li><a href="<?= SITE_URL ?>/admin/products.php" class="<?= nav_active('products.php', $__cur) ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
          Products
          <span class="pill-count"><?= $__counts['products'] ?></span>
        </a></li>
        <li><a href="<?= SITE_URL ?>/admin/categories.php" class="<?= nav_active('categories.php', $__cur) ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          Categories
        </a></li>
      </ul>
    </div>

    <div class="admin-nav-section">
      <h5>Sales</h5>
      <ul class="admin-nav">
        <li><a href="<?= SITE_URL ?>/admin/orders.php" class="<?= nav_active('orders.php', $__cur) ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
          Orders
          <?php if ($__counts['orders']): ?>
            <span class="pill-count"><?= $__counts['orders'] ?></span>
          <?php endif; ?>
        </a></li>
      </ul>
    </div>

    <div class="admin-nav-section">
      <h5>People</h5>
      <ul class="admin-nav">
        <li><a href="<?= SITE_URL ?>/admin/customers.php" class="<?= nav_active('customers.php', $__cur) ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          Customers
          <span class="pill-count"><?= $__counts['customers'] ?></span>
        </a></li>
      </ul>
    </div>

    <div class="admin-side-foot">
      <a href="<?= SITE_URL ?>/index.php" target="_blank">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
        View Site
      </a>
      <a href="<?= SITE_URL ?>/admin/logout.php">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </a>
    </div>
  </aside>

  <div class="admin-side-overlay" id="adminSideOverlay"></div>

  <div class="admin-main-wrap">
    <header class="admin-topbar">
      <div class="admin-topbar-left">
        <button class="admin-menu-toggle" onclick="document.body.classList.toggle('side-open'); document.getElementById('adminSideOverlay').classList.toggle('open')" aria-label="Menu">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <div>
          <h1 class="admin-page-title"><?= e($pageTitle ?? 'Admin') ?></h1>
          <p class="admin-page-sub"><?= date('l, F j, Y') ?></p>
        </div>
      </div>
      <div class="admin-topbar-right">
        <div class="admin-search-mini">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" placeholder="Quick searchâ€¦">
        </div>
        <div class="admin-user-chip">
          <span class="user-name">Admin</span>
          <span class="user-avatar">A</span>
        </div>
      </div>
    </header>

    <div class="admin-content">