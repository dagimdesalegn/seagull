<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Checkout';

// allow ?add=ID (Buy Now) to add then jump to cart
if (isset($_GET['add'])) {
  $id = (int)$_GET['add'];
  $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
  redirect(SITE_URL . '/checkout.php');
}

$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
  flash('error', 'Your cart is empty.');
  redirect(SITE_URL . '/shop.php');
}

$ids = implode(',', array_keys($cart));
$items = $pdo->query("SELECT * FROM products WHERE id IN ($ids)")->fetchAll();
$subtotal = 0;
foreach ($items as $i) $subtotal += $i['price'] * $cart[$i['id']];
$delivery = $subtotal > 5000 ? 0 : 150;
$total = $subtotal + $delivery;

$prefill = ['name'=>'','email'=>'','phone'=>'','address'=>''];
if (is_customer_logged_in()) {
  $stmt = $pdo->prepare("SELECT * FROM customers WHERE id=?");
  $stmt->execute([$_SESSION['customer_id']]);
  $c = $stmt->fetch();
  if ($c) $prefill = ['name'=>$c['name'],'email'=>$c['email'],'phone'=>$c['phone'],'address'=>$c['address']];
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $addr = trim($_POST['address'] ?? '');
  $pay = $_POST['pay'] ?? 'cod';
  if ($name && $email && $addr) {
    try {
      $pdo->beginTransaction();
      $cid = $_SESSION['customer_id'] ?? null;
      $pdo->prepare("INSERT INTO orders (customer_id,total,status) VALUES (?,?,'pending')")->execute([$cid, $total]);
      $oid = $pdo->lastInsertId();
      $ins = $pdo->prepare("INSERT INTO order_items (order_id,product_id,quantity,price) VALUES (?,?,?,?)");
      foreach ($items as $i) $ins->execute([$oid, $i['id'], $cart[$i['id']], $i['price']]);
      // decrement stock
      $dec = $pdo->prepare("UPDATE products SET stock = GREATEST(stock - ?, 0) WHERE id = ?");
      foreach ($items as $i) $dec->execute([$cart[$i['id']], $i['id']]);
      $pdo->commit();

      $_SESSION['cart'] = [];
      $_SESSION['last_order_id'] = $oid;
      flash('success', 'Order placed successfully!');
      redirect(SITE_URL . '/order_success.php?id=' . $oid);
    } catch (Exception $ex) {
      $pdo->rollBack();
      $error = 'Could not place order. Please try again.';
    }
  } else {
    $error = 'Please fill in name, email, and delivery address.';
  }
}

include __DIR__ . '/includes/header.php';
?>
<h1>Checkout</h1>
<?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>

<div class="step-indicator" id="stepIndicator">
  <div class="step-dot active" data-step="1">
    <span class="num">1</span>
    <span class="label">Shipping</span>
  </div>
  <div class="step-dot" data-step="2">
    <span class="num">2</span>
    <span class="label">Payment &amp; Review</span>
  </div>
</div>

<div class="checkout-grid">
  <form method="post" class="checkout-form" id="checkoutForm">
    <!-- STEP 1 -->
    <div class="step-section active" data-step-section="1">
      <h2>Shipping Information</h2>
      <label>Full Name *</label>
      <input type="text" name="name" value="<?= e($prefill['name']) ?>" required>
      <label>Email *</label>
      <input type="email" name="email" value="<?= e($prefill['email']) ?>" required>
      <label>Phone</label>
      <input type="text" name="phone" value="<?= e($prefill['phone']) ?>">
      <label>Delivery Address *</label>
      <textarea name="address" rows="3" required><?= e($prefill['address']) ?></textarea>

      <div class="step-actions" style="justify-content:flex-end">
        <button type="button" class="btn btn-primary" onclick="nextStep()">Continue â†’ Payment</button>
      </div>
    </div>

    <!-- STEP 2 -->
    <div class="step-section" data-step-section="2">
      <h2>Payment Method</h2>
      <label class="radio-row"><input type="radio" name="pay" value="cod" checked> Cash on Delivery</label>
      <label class="radio-row"><input type="radio" name="pay" value="bank"> Bank Transfer (CBE)</label>
      <label class="radio-row muted"><input type="radio" disabled> Telebirr (coming soon)</label>

      <h2>Review Your Order</h2>
      <div class="review-block">
        <h4>Shipping To</h4>
        <p><strong id="rvName">â€”</strong></p>
        <p id="rvAddr">â€”</p>
        <p id="rvContact">â€”</p>
      </div>

      <div class="step-actions">
        <button type="button" class="btn btn-ghost" onclick="prevStep()">â† Back</button>
        <button type="submit" class="btn btn-primary">Place Order</button>
      </div>
    </div>
  </form>

  <aside class="checkout-summary">
    <h2>Order Summary</h2>
    <?php foreach ($items as $i): ?>
      <div class="summary-line"><span><?= e($i['name']) ?> Ã— <?= (int)$cart[$i['id']] ?></span><span><?= number_format($i['price']*$cart[$i['id']], 2) ?> ETB</span></div>
    <?php endforeach; ?>
    <div class="summary-line" style="border-top:1px solid var(--line-2);margin-top:8px;padding-top:12px"><span>Subtotal</span><span><?= number_format($subtotal,2) ?> ETB</span></div>
    <div class="summary-line"><span>Delivery</span><span><?= $delivery ? number_format($delivery,2).' ETB' : 'FREE' ?></span></div>
    <div class="summary-line total"><span>Total</span><span><?= number_format($total,2) ?> ETB</span></div>
  </aside>
</div>

<script>
var currentStep = 1;
function showStep(n){
  currentStep = n;
  document.querySelectorAll('.step-section').forEach(function(s){
    s.classList.toggle('active', +s.getAttribute('data-step-section') === n);
  });
  document.querySelectorAll('.step-dot').forEach(function(d){
    var num = +d.getAttribute('data-step');
    d.classList.toggle('active', num === n);
    d.classList.toggle('done', num < n);
    if (num < n) {
      d.querySelector('.num').innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
    } else {
      d.querySelector('.num').textContent = num;
    }
  });
  window.scrollTo({top: document.getElementById('stepIndicator').offsetTop - 100, behavior:'smooth'});
}
function nextStep(){
  var form = document.getElementById('checkoutForm');
  var step1 = form.querySelector('[data-step-section="1"]');
  var req = step1.querySelectorAll('input[required], textarea[required]');
  var ok = true;
  req.forEach(function(el){
    if (!el.value.trim()) { el.style.borderColor = '#EF4444'; el.focus(); ok = false; }
    else el.style.borderColor = '';
  });
  if (!ok) return;
  // fill review
  document.getElementById('rvName').textContent = form.name.value;
  document.getElementById('rvAddr').textContent = form.address.value;
  document.getElementById('rvContact').textContent = form.email.value + (form.phone.value ? ' â€¢ ' + form.phone.value : '');
  showStep(2);
}
function prevStep(){ showStep(1); }
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>