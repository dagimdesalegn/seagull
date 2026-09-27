<?php
require_once __DIR__ . '/includes/functions.php';
if (is_customer_logged_in()) redirect(SITE_URL . '/account.php');
$pageTitle = 'Login';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("SELECT * FROM customers WHERE email=?");
  $stmt->execute([trim($_POST['email'] ?? '')]);
  $c = $stmt->fetch();
  if ($c && password_verify($_POST['password'] ?? '', $c['password'])) {
    $_SESSION['customer_id'] = $c['id'];
    $_SESSION['customer_name'] = $c['name'];
    flash('success', 'Welcome back, ' . $c['name'] . '!');
    $r = $_POST['redirect'] ?? '';
    if ($r && strpos($r, 'http') !== 0) redirect(SITE_URL . $r);
    redirect(SITE_URL . '/account.php');
  } else { $error = 'Invalid email or password.'; }
}
include __DIR__ . '/includes/header.php';
?>
<div class="auth-wrap">
  <div class="auth-page">
    <aside class="auth-page-visual">
      <div class="visual-brand">
        <div class="visual-brand-mark">ST</div>
        <div class="visual-brand-text">
          <strong>SEAGULL</strong>
          <small>Trading PLC</small>
        </div>
      </div>
      <div>
        <h1>Good to see you <em>again</em>.</h1>
        <p class="lead">Sign in to track orders, save your favourites, and check out faster next time.</p>
        <ul class="perks">
          <li><span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Order history & tracking</li>
          <li><span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Saved delivery addresses</li>
          <li><span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Faster one-click checkout</li>
        </ul>
      </div>
      <div class="visual-foot">Trusted by businesses across Ethiopia</div>
    </aside>

    <div class="auth-page-form">
      <div class="form-head">
        <h2>Welcome back</h2>
        <p>Enter your credentials to sign in.</p>
      </div>
      <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
      <form method="post">
        <input type="hidden" name="redirect" value="<?= e($_GET['redirect'] ?? '') ?>">
        <div class="form-field">
          <label>Email Address</label>
          <input type="email" name="email" placeholder="you@example.com" required autofocus>
        </div>
        <div class="form-field">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        <p class="form-foot">New to Seagull? <a href="<?= SITE_URL ?>/register.php">Create an account</a></p>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>