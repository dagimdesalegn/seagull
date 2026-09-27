<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Cart';
if (isset($_GET['add'])) { $_SESSION['cart'][(int)$_GET['add']] = ($_SESSION['cart'][(int)$_GET['add']] ?? 0) + 1; flash('success','Added to cart.'); redirect(SITE_URL . '/cart.php'); }
if (isset($_GET['remove'])) { unset($_SESSION['cart'][(int)$_GET['remove']]); redirect(SITE_URL . '/cart.php'); }
$cart = $_SESSION['cart'] ?? [];
$items = []; $total = 0;
if ($cart) {
  $ids = implode(',', array_keys($cart));
  $items = $pdo->query("SELECT * FROM products WHERE id IN ($ids)")->fetchAll();
  foreach ($items as $i) $total += $i['price'] * $cart[$i['id']];
}
include __DIR__ . '/includes/header.php';
?>
<h1>Your Cart</h1>
<?php if (!$items): ?>
  <p>Your cart is empty. <a href="<?= SITE_URL ?>/shop.php">Continue shopping</a>.</p>
<?php else: ?>
  <table class="table">
    <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($items as $i): ?>
        <tr>
          <td><?= e($i['name']) ?></td>
          <td><?= number_format($i['price'],2) ?> ETB</td>
          <td><?= (int)$cart[$i['id']] ?></td>
          <td><?= number_format($i['price']*$cart[$i['id']],2) ?> ETB</td>
          <td><a class="btn btn-sm btn-danger" href="?remove=<?= (int)$i['id'] ?>">Remove</a></td>
        </tr>
      <?php endforeach; ?>
      <tr><th colspan="3" style="text-align:right">Total</th><th colspan="2"><?= number_format($total,2) ?> ETB</th></tr>
    </tbody>
  </table>
  <a class="btn" href="<?= SITE_URL ?>/checkout.php">Proceed to Checkout</a>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>
