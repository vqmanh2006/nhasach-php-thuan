<?php
require __DIR__.'/config/config.php';
$query = db()->prepare('SELECT b.*, c.name category_name FROM books b LEFT JOIN categories c ON c.id=b.category_id WHERE b.id=? AND b.is_active=1');
$query->execute([(int) ($_GET['id'] ?? 0)]);
$book = $query->fetch();
if (!$book) { http_response_code(404); exit('Không tìm thấy sách.'); }
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title><?= e($book['title']) ?></title><link rel="stylesheet" href="<?= BASE_URL ?>/style.css"></head><body><header class="topbar"><strong>NHÀ SÁCH TRÍ TUỆ</strong><a href="<?= BASE_URL ?>/shop.php">← Cửa hàng</a></header><main class="container"><section class="detail panel"><div class="book-cover">📖</div><div><h1><?= e($book['title']) ?></h1><p><b>Tác giả:</b> <?= e($book['author']) ?></p><p><b>Danh mục:</b> <?= e($book['category_name']) ?></p><p><?= nl2br(e($book['description'])) ?></p><h2 class="price"><?= number_format((float) $book['price'],0,',','.') ?>đ</h2><p>Còn lại: <?= (int) $book['stock_quantity'] ?></p><form method="post" action="<?= BASE_URL ?>/cart.php"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="book_id" value="<?= $book['id'] ?>"><input type="hidden" name="action" value="add"><button <?= $book['stock_quantity'] < 1 ? 'disabled' : '' ?>>Thêm vào giỏ</button></form></div></section></main></body></html>
