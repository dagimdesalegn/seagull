<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Register';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $addr = trim($_POST['address'] ?? '');
  $pass = $_POST['password'] ?? '';
  $confirm = $_POST['confirm'] ?? '';
  if ($name === '') $errors[] = 'Name is required.';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
  if (strlen($pass) < 6) $errors[] = 'Password must be at least 6 characters.';
  if ($pass !== $confirm) $errors[] = 'Passwords do not match.';
  if (!$errors) {
    $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
      $errors[] = 'Email already registered.';
    } else {
      $hash = password_hash($pass, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO customers (name,email,password,phone,address) VALUES (?,?,?,?,?)");
      $stmt->execute([$name,$email,$hash,$phone,$addr]);
      $_SESSION['customer_id'] = $pdo->lastInsertId();
      $_SESSION['customer_name'] = $name;
      flash('success', 'Welcome, ' . $name . '!');
      $r = $_POST['redirect'] ?? '';
      if ($r && strpos($r, 'http') !== 0) redirect(SITE_URL . $r);
      redirect(SITE_URL . '/account.php');
    }
  }
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
        <h1>Join Seagull <em>today</em>.</h1>
        <p class="lead">Create your free account and start shopping quality furniture, equipment, and essentials delivered across Ethiopia.</p>
        <ul class="perks">
          <li><span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Free to create, no card required</li>
          <li><span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Track orders in real time</li>
          <li><span class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Member-only offers and pricing</li>
        </ul>
      </div>
      <div class="visual-foot">Trusted by businesses across Ethiopia</div>
    </aside>

    <div class="auth-page-form">
      <div class="form-head">
        <h2>Create your account</h2>
        <p>It only takes a minute.</p>
      </div>
      <?php if ($errors): ?>
        <div class="alert alert-error">
          <?php foreach ($errors as $e) echo '<div>&bull; ' . e($e) . '</div>'; ?>
        </div>
      <?php endif; ?>
      <form method="post">
        <input type="hidden" name="redirect" value="<?= e($_GET['redirect'] ?? '') ?>">
        <div class="form-field">
          <label>Full Name</label>
          <input type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>" placeholder="Your full name" required autofocus>
        </div>
        <div class="form-field">
          <label>Email Address</label>
          <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" placeholder="you@example.com" required>
        </div>
        <div class="form-row">
          <div class="form-field">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= e($_POST['phone'] ?? '') ?>" placeholder="+251 9XX XXX XXX">
          </div>
          <div class="form-field">
            <label>City</label>
            <input type="text" name="address" value="<?= e($_POST['address'] ?? '') ?>" placeholder="Addis Ababa">
          </div>
        </div>
        <div class="form-row">
          <div class="form-field">
            <label>Password</label>
            <input type="password" name="password" placeholder="Min 6 characters" required>
          </div>
          <div class="form-field">
            <label>Confirm Password</label>
            <input type="password" name="confirm" placeholder="Repeat password" required>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Create My Account</button>
        <p class="form-foot">Already registered? <a href="<?= SITE_URL ?>/login.php">Sign in</a></p>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>