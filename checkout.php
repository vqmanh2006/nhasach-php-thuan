<?php
require __DIR__.'/config/config.php';
require_customer();
$cart = db()->prepare('SELECT id FROM carts WHERE customer_id=?'); $cart->execute([$_SESSION['customer']['id']]); $cartId = $cart->fetchColumn();
$items = db()->prepare('SELECT ci.*, b.title,b.price,b.stock_quantity FROM cart_items ci JOIN books b ON b.id=ci.book_id WHERE ci.cart_id=?'); $items->execute([$cartId]); $items = $items->fetchAll();
if (!$items) redirect('/cart.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf(); db()->beginTransaction();
    try {
        $total = 0;
        foreach ($items as $item) { if ($item['quantity'] > $item['stock_quantity']) throw new RuntimeException('Sách không đủ tồn kho.'); $total += $item['price'] * $item['quantity']; }
        db()->prepare('INSERT INTO sales (customer_id,total_amount,status) VALUES (?,?,?)')->execute([$_SESSION['customer']['id'], $total, 'completed']);
        $saleId = db()->lastInsertId();
        foreach ($items as $item) { db()->prepare('INSERT INTO sale_items (sale_id,book_id,quantity,unit_price) VALUES (?,?,?,?)')->execute([$saleId,$item['book_id'],$item['quantity'],$item['price']]); db()->prepare('UPDATE books SET stock_quantity=stock_quantity-? WHERE id=?')->execute([$item['quantity'],$item['book_id']]); }
        db()->prepare('DELETE FROM cart_items WHERE cart_id=?')->execute([$cartId]); db()->commit(); redirect('/shop.php?ordered=1');
    } catch (Throwable $exception) { db()->rollBack(); $error = $exception->getMessage(); }
}
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Xác nhận đặt hàng</title><link rel="stylesheet" href="<?= BASE_URL ?>/style.css"></head><body><header class="topbar"><strong>NHÀ SÁCH TRÍ TUỆ</strong><a href="<?= BASE_URL ?>/cart.php">Giỏ hàng</a></header><main class="container"><h1>Xác nhận đặt hàng</h1><section class="panel"><?php if (!empty($error)): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><p>Bạn sẽ thanh toán khi nhận hàng.</p><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><button>Xác nhận đặt hàng</button></form></section></main></body></html>
