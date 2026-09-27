<?php
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/json');

if (!is_customer_logged_in()) {
  echo json_encode(['redirect' => SITE_URL . '/login.php?redirect=/wishlist.php']);
  exit;
}

$pid = (int)($_POST['product_id'] ?? 0);
if ($pid <= 0) { echo json_encode(['error' => 'invalid']); exit; }

$cid = (int)$_SESSION['customer_id'];

$stmt = $pdo->prepare("SELECT id FROM wishlists WHERE customer_id = ? AND product_id = ?");
$stmt->execute([$cid, $pid]);
$existing = $stmt->fetchColumn();

if ($existing) {
  $pdo->prepare("DELETE FROM wishlists WHERE id = ?")->execute([$existing]);
  $status = 'removed';
} else {
  // ensure product exists
  $check = $pdo->prepare("SELECT id FROM products WHERE id = ?");
  $check->execute([$pid]);
  if (!$check->fetchColumn()) { echo json_encode(['error' => 'not_found']); exit; }
  $pdo->prepare("INSERT INTO wishlists (customer_id, product_id) VALUES (?, ?)")->execute([$cid, $pid]);
  $status = 'added';
}

echo json_encode(['status' => $status, 'count' => wishlist_count($pdo)]);