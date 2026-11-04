<?php
require __DIR__.'/config/config.php';
require_customer();
$query = db()->prepare('SELECT * FROM sales WHERE customer_id=? ORDER BY id DESC');
$query->execute([$_SESSION['customer']['id']]); $orders = $query->fetchAll();
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Tài khoản</title><link rel="stylesheet" href="<?= BASE_URL ?>/style.css"></head><body><header class="topbar"><strong>NHÀ SÁCH TRÍ TUỆ</strong><a href="<?= BASE_URL ?>/shop.php">Cửa hàng</a></header><main class="container"><h1>Tài khoản của tôi</h1><section class="panel"><p><b>Họ tên:</b> <?= e($_SESSION['customer']['name']) ?></p><p><b>Email:</b> <?= e($_SESSION['customer']['email']) ?></p></section><section class="panel"><h2>Lịch sử đơn hàng</h2><table><tr><th>Mã đơn</th><th>Tổng tiền</th><th>Trạng thái</th><th>Ngày đặt</th></tr><?php foreach ($orders as $order): ?><tr><td>#<?= $order['id'] ?></td><td><?= number_format((float) $order['total_amount'],0,',','.') ?>đ</td><td><?= e($order['status']) ?></td><td><?= e($order['created_at']) ?></td></tr><?php endforeach; ?></table><?php if (!$orders): ?><p>Chưa có đơn hàng.</p><?php endif; ?></section></main></body></html>
