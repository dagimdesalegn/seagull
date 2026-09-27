<?php require_once __DIR__ . '/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= isset($pageTitle) ? e($pageTitle) . ' | ' . SITE_NAME : SITE_NAME ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>

<div class="site-top">
  <div class="topbar">
    <div class="topbar-inner">
      <span>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        <?= e(ADDRESS) ?>
      </span>
      <span class="topbar-right">
        <a href="mailto:<?= e(ADMIN_EMAIL) ?>" class="topbar-email">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          <?= e(ADMIN_EMAIL) ?>
        </a>
        <a href="tel:<?= e(PHONE) ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          <?= e(PHONE) ?>
        </a>
      </span>
    </div>
  </div>

  <header class="site-header">
    <div class="header-main">
      <button class="menu-toggle" aria-label="Menu" onclick="openDrawer()"><span></span></button>
      <a class="logo" href="<?= SITE_URL ?>/index.php">
        <img class="logo-img" src="<?= SITE_URL ?>/assets/img/logo.jpg" alt="<?= SITE_NAME ?>">
        <span class="logo-text">
          <strong>SEAGULL</strong>
          <small>Trading PLC</small>
        </span>
      </a>
      <form class="search" action="<?= SITE_URL ?>/shop.php" method="get">
        <input type="text" name="q" placeholder="Search products..." value="<?= e($_GET['q'] ?? '') ?>">
        <button type="submit">Search</button>
      </form>
      <div class="header-actions">
        <a href="<?= SITE_URL ?>/wishlist.php" class="wishlist-link">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
          <span class="text">Wishlist</span>
          <span class="badge" id="wishlistCount" style="<?= wishlist_count($pdo) > 0 ? '' : 'display:none' ?>"><?= wishlist_count($pdo) ?></span>
        </a>
        <a href="<?= SITE_URL ?>/cart.php" class="cart-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
          <span class="cart-text">Cart</span>
          <span class="badge"><?= cart_count() ?></span>
        </a>
        <?php if (is_customer_logged_in()): ?>
          <a href="<?= SITE_URL ?>/account.php" class="btn-get-started">My Account</a>
        <?php else: ?>
          <button class="btn-get-started" onclick="openAuthModal('register')">Get Started</button>
        <?php endif; ?>
      </div>
    </div>

    <div class="mobile-search">
      <form action="<?= SITE_URL ?>/shop.php" method="get">
        <input type="text" name="q" placeholder="Search products..." value="<?= e($_GET['q'] ?? '') ?>">
        <button type="submit">Search</button>
      </form>
    </div>

    <nav class="category-bar">
      <div class="category-bar-inner">
        <a href="<?= SITE_URL ?>/index.php" class="<?= basename($_SERVER['PHP_SELF'])==='index.php'?'active':'' ?>"><span class="cat-dot"></span> Home</a>
        <a href="<?= SITE_URL ?>/shop.php" class="<?= basename($_SERVER['PHP_SELF'])==='shop.php'?'active':'' ?>"><span class="cat-dot"></span> All Products</a>
        <?php
          $navCats = get_categories($pdo);
          foreach ($navCats as $nc):
            $active = (basename($_SERVER['PHP_SELF'])==='category.php' && ($_GET['slug'] ?? '') === $nc['slug']) ? 'active' : '';
        ?>
          <a href="<?= SITE_URL ?>/category.php?slug=<?= e($nc['slug']) ?>" class="<?= $active ?>"><span class="cat-dot"></span> <?= e($nc['name']) ?></a>
        <?php endforeach; ?>
      </div>
    </nav>
  </header>
</div>

<div class="drawer-overlay" id="drawerOverlay"></div>
<aside class="drawer" id="drawer">
  <div class="drawer-head">
    <button class="drawer-close" aria-label="Close" onclick="closeDrawer()">&times;</button>
    <div class="drawer-brand">
      <img class="logo-img" src="<?= SITE_URL ?>/assets/img/logo.jpg" alt="<?= SITE_NAME ?>">
      <div>
        <strong>SEAGULL</strong>
        <small>Trading PLC</small>
      </div>
    </div>
    <?php if (is_customer_logged_in()): ?>
      <div class="drawer-user">Signed in as <strong><?= e($_SESSION['customer_name'] ?? 'Customer') ?></strong></div>
    <?php else: ?>
      <div class="drawer-user">Welcome! Sign in to shop faster.</div>
    <?php endif; ?>
  </div>
  <div class="drawer-body">
    <div class="drawer-section">
      <h4>Menu</h4>
      <ul class="drawer-nav">
        <li><a href="<?= SITE_URL ?>/index.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg> Home</a></li>
        <li><a href="<?= SITE_URL ?>/shop.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg> Shop All</a></li>
        <li><a href="<?= SITE_URL ?>/wishlist.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> Wishlist</a></li>
        <li><a href="<?= SITE_URL ?>/cart.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg> Cart (<?= cart_count() ?>)</a></li>
        <?php if (is_customer_logged_in()): ?>
          <li><a href="<?= SITE_URL ?>/account.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> My Account</a></li>
          <li><a href="<?= SITE_URL ?>/logout.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
    <div class="drawer-section">
      <h4>Shop by Category</h4>
      <ul class="drawer-nav">
        <?php foreach ($navCats as $nc): ?>
          <li><a href="<?= SITE_URL ?>/category.php?slug=<?= e($nc['slug']) ?>"><span class="cat-dot" style="background:var(--coral)"></span> <?= e($nc['name']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php if (!is_customer_logged_in()): ?>
    <div class="drawer-cta">
      <button class="btn btn-primary btn-block" onclick="closeDrawer(); setTimeout(function(){openAuthModal('register')}, 220)">Get Started</button>
      <button class="btn btn-block" onclick="closeDrawer(); setTimeout(function(){openAuthModal('login')}, 220)">Sign In</button>
    </div>
  <?php endif; ?>
</aside>

<div class="modal-overlay" id="authModal">
  <div class="modal modal-auth">
    <button class="modal-close" aria-label="Close">&times;</button>
    <aside class="modal-side"><img class="modal-logo" src="<?= SITE_URL ?>/assets/img/logo.jpg" alt="<?= SITE_NAME ?>"></aside>
    <div class="modal-form-side">
      <div class="modal-form-head">
        <h2>Welcome</h2>
        <p>Sign in or create an account to continue</p>
      </div>
      <div class="modal-tabs">
        <button data-tab="register" class="active">Create Account</button>
        <button data-tab="login">Sign In</button>
      </div>
      <form class="tab-pane active" data-pane="register" action="<?= SITE_URL ?>/register.php" method="post">
        <div class="form-group"><label>Full Name</label><input type="text" name="name" placeholder="Your full name" required></div>
        <div class="form-group"><label>Email Address</label><input type="email" name="email" placeholder="you@example.com" required></div>
        <div class="form-row">
          <div class="form-group"><label>Phone</label><input type="text" name="phone" placeholder="+251 9XX XXX XXX"></div>
          <div class="form-group"><label>City</label><input type="text" name="address" placeholder="Addis Ababa"></div>
        </div>
        <div class="form-row">
          <div class="form-group"><label>Password</label><input type="password" name="password" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢" required></div>
          <div class="form-group"><label>Confirm</label><input type="password" name="confirm" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢" required></div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Create My Account</button>
        <p class="modal-foot">Already have an account? <a onclick="switchAuthTab('login')">Sign in</a></p>
      </form>
      <form class="tab-pane" data-pane="login" action="<?= SITE_URL ?>/login.php" method="post">
        <div class="form-group"><label>Email Address</label><input type="email" name="email" placeholder="you@example.com" required></div>
        <div class="form-group"><label>Password</label><input type="password" name="password" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢" required></div>
        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        <p class="modal-foot">New to Seagull? <a onclick="switchAuthTab('register')">Create an account</a></p>
      </form>
    </div>
  </div>
</div>

<main class="container">
<?php if ($msg = flash('success')): ?><div class="alert alert-success"><?= e($msg) ?></div><?php endif; ?>
<?php if ($msg = flash('error')): ?><div class="alert alert-error"><?= e($msg) ?></div><?php endif; ?>