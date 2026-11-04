<?php
require __DIR__.'/config/config.php';
require_customer();
$cart = db()->prepare('SELECT id FROM carts WHERE customer_id=?'); $cart->execute([$_SESSION['customer']['id']]); $cartId = $cart->fetchColumn();
if (!$cartId) { db()->prepare('INSERT INTO carts (customer_id) VALUES (?)')->execute([$_SESSION['customer']['id']]); $cartId = db()->lastInsertId(); }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    if ($_POST['action'] === 'remove') {
        db()->prepare('DELETE FROM cart_items WHERE cart_id=? AND book_id=?')->execute([$cartId, (int) $_POST['book_id']]);
    } else {
        $book = db()->prepare('SELECT stock_quantity FROM books WHERE id=? AND is_active=1'); $book->execute([(int) $_POST['book_id']]); $stock = (int) $book->fetchColumn();
        if ($stock > 0) db()->prepare('INSERT INTO cart_items (cart_id,book_id,quantity) VALUES (?,?,1) ON DUPLICATE KEY UPDATE quantity=LEAST(quantity+1, ?)')->execute([$cartId, (int) $_POST['book_id'], $stock]);
    }
    redirect('/cart.php');
}
$items = db()->prepare('SELECT ci.*, b.title,b.price,b.stock_quantity FROM cart_items ci JOIN books b ON b.id=ci.book_id WHERE ci.cart_id=?'); $items->execute([$cartId]); $items = $items->fetchAll();
$total = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $items));
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Giỏ hàng</title><link rel="stylesheet" href="<?= BASE_URL ?>/style.css"></head><body><header class="topbar"><strong>NHÀ SÁCH TRÍ TUỆ</strong><a href="<?= BASE_URL ?>/shop.php">Tiếp tục mua sắm</a></header><main class="container"><h1>Giỏ hàng</h1><section class="panel"><?php foreach ($items as $item): ?><div class="cart-row"><span><?= e($item['title']) ?> × <?= $item['quantity'] ?></span><b><?= number_format($item['price']*$item['quantity'],0,',','.') ?>đ</b><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="remove"><input type="hidden" name="book_id" value="<?= $item['book_id'] ?>"><button class="danger">Xóa</button></form></div><?php endforeach; ?><h2>Tổng: <?= number_format($total,0,',','.') ?>đ</h2><?php if ($items): ?><a class="button" href="<?= BASE_URL ?>/checkout.php">Đặt hàng</a><?php else: ?><p>Giỏ hàng đang trống.</p><?php endif; ?></section></main></body></html>
