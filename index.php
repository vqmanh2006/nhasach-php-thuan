<?php
require __DIR__.'/config/config.php';
require_login();

$stats = [
    'books' => (int) db()->query('SELECT COUNT(*) FROM books WHERE is_active = 1')->fetchColumn(),
    'categories' => (int) db()->query('SELECT COUNT(*) FROM categories')->fetchColumn(),
    'customers' => (int) db()->query('SELECT COUNT(*) FROM customers')->fetchColumn(),
    'sales' => (int) db()->query('SELECT COUNT(*) FROM sales')->fetchColumn(),
];
$books = db()->query('SELECT b.*, c.name category_name FROM books b LEFT JOIN categories c ON c.id = b.category_id ORDER BY b.id DESC LIMIT 10')->fetchAll();
?>
<!doctype html>
<html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Quản trị - <?= e(APP_NAME) ?></title><link rel="stylesheet" href="<?= BASE_URL ?>/style.css"></head>
<body><header class="topbar"><strong>NHÀ SÁCH TRÍ TUỆ</strong><span><?= e($_SESSION['user']['name']) ?> · <a href="<?= BASE_URL ?>/logout.php">Đăng xuất</a></span></header><main class="container"><h1>Bảng điều khiển</h1><div class="stats"><?php foreach ($stats as $label => $value): ?><div class="stat"><span><?= e(ucfirst($label)) ?></span><b><?= $value ?></b></div><?php endforeach; ?></div><nav class="tabs"><a href="<?= BASE_URL ?>/books.php">Sách</a><a href="<?= BASE_URL ?>/categories.php">Danh mục</a><a href="<?= BASE_URL ?>/customers.php">Khách hàng</a><a href="<?= BASE_URL ?>/sales.php">Bán hàng</a></nav><section class="panel"><h2>Sách mới cập nhật</h2><table><tr><th>Tên sách</th><th>Tác giả</th><th>Danh mục</th><th>Giá</th><th>Tồn kho</th></tr><?php foreach ($books as $book): ?><tr><td><?= e($book['title']) ?></td><td><?= e($book['author']) ?></td><td><?= e($book['category_name']) ?></td><td><?= number_format((float) $book['price'], 0, ',', '.') ?>đ</td><td><?= (int) $book['stock_quantity'] ?></td></tr><?php endforeach; ?></table></section></main></body></html>
