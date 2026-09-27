<?php
require_once __DIR__ . '/../includes/functions.php';
if (is_admin_logged_in()) redirect(SITE_URL . '/admin/dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
  $stmt->execute([trim($_POST['username'] ?? '')]);
  $admin = $stmt->fetch();
  if ($admin && password_verify($_POST['password'] ?? '', $admin['password'])) {
    $_SESSION['admin_id'] = $admin['id'];
    redirect(SITE_URL . '/admin/dashboard.php');
  } else {
    $error = 'Invalid username or password.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login â€” <?= SITE_NAME ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body class="admin-login">
<form method="post" autocomplete="off">
  <div class="login-brand">
    <div class="brand-mark">ST</div>
  </div>
  <h1>Admin Portal</h1>
  <p class="sub">Sign in to manage <?= SITE_NAME ?></p>

  <?php if ($error): ?>
    <div class="alert alert-error"><?= e($error) ?></div>
  <?php endif; ?>

  <label>Username</label>
  <input type="text" name="username" required autofocus autocomplete="username">

  <label>Password</label>
  <input type="password" name="password" required autocomplete="current-password">

  <button type="submit" class="btn btn-primary btn-block">Sign In</button>

  <p class="form-foot" style="margin-top:18px">
    <a href="<?= SITE_URL ?>/index.php">â† Back to site</a>
  </p>
</form>
</body>
</html>