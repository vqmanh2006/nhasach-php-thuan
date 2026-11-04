<?php
require __DIR__.'/config/config.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { check_csrf(); $statement = db()->prepare('INSERT INTO categories (name,description) VALUES (?,?)'); $statement->execute([trim($_POST['name']), trim($_POST['description'] ?: null)]); redirect('/categories.php'); }
$categories = db()->query('SELECT * FROM categories ORDER BY id DESC')->fetchAll();
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Danh mục</title><link rel="stylesheet" href="/style.css"></head><body><header class="topbar"><strong>NHÀ SÁCH TRÍ TUỆ</strong><a href="/index.php">Dashboard</a></header><main class="container"><h1>Danh mục</h1><section class="panel"><form method="post" class="form-grid"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Tên danh mục<input name="name" required></label><label>Mô tả<input name="description"></label><button>Thêm danh mục</button></form></section><section class="panel"><table><tr><th>Tên</th><th>Mô tả</th></tr><?php foreach ($categories as $category): ?><tr><td><?= e($category['name']) ?></td><td><?= e($category['description']) ?></td></tr><?php endforeach; ?></table></section></main></body></html>
