<?php
require_once __DIR__ . '/../includes/functions.php';
if (!is_admin_logged_in()) redirect(SITE_URL . '/admin/login.php');
$pageTitle = 'Categories';
if ($_SERVER['REQUEST_METHOD']==='POST') { $n=trim($_POST['name']??''); if($n!==''){ $s=strtolower(preg_replace('/[^A-Za-z0-9-]+/','-',$n)); $pdo->prepare("INSERT IGNORE INTO categories (name,slug) VALUES (?,?)")->execute([$n,$s]); flash('success','Category added.'); } redirect(SITE_URL.'/admin/categories.php'); }
if (isset($_GET['delete'])) { $pdo->prepare("DELETE FROM categories WHERE id=?")->execute([(int)$_GET['delete']]); flash('success','Category deleted.'); redirect(SITE_URL.'/admin/categories.php'); }
$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products WHERE category_id=c.id) AS product_count FROM categories c ORDER BY c.name")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<h1>Categories</h1>
<form method="post" class="inline-form"><input type="text" name="name" placeholder="New category name" required><button class="btn" type="submit">Add Category</button></form>
<div class="panel"><table class="table">
  <thead><tr><th>ID</th><th>Name</th><th>Slug</th><th>Products</th><th>Actions</th></tr></thead>
  <tbody><?php foreach ($categories as $c): ?>
    <tr><td><?= (int)$c['id'] ?></td><td><?= e($c['name']) ?></td><td><?= e($c['slug']) ?></td><td><?= (int)$c['product_count'] ?></td>
    <td><a class="btn btn-sm btn-danger" href="?delete=<?= (int)$c['id'] ?>" onclick="return confirm('Delete?')">Delete</a></td></tr>
  <?php endforeach; ?></tbody>
</table></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
