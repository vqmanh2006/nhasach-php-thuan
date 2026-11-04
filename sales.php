<?php
require __DIR__.'/config/config.php';
require_login();
$sales = db()->query('SELECT s.*, c.name customer_name, u.name user_name FROM sales s LEFT JOIN customers c ON c.id=s.customer_id LEFT JOIN users u ON u.id=s.user_id ORDER BY s.id DESC')->fetchAll();
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Bán hàng</title><link rel="stylesheet" href="<?= BASE_URL ?>/style.css"></head><body><header class="topbar"><strong>NHÀ SÁCH TRÍ TUỆ</strong><a href="<?= BASE_URL ?>/index.php">Dashboard</a></header><main class="container"><h1>Lịch sử bán hàng</h1><section class="panel"><table><tr><th>Mã</th><th>Khách hàng</th><th>Nhân viên</th><th>Tổng tiền</th><th>Trạng thái</th><th>Ngày</th></tr><?php foreach ($sales as $sale): ?><tr><td>#<?= $sale['id'] ?></td><td><?= e($sale['customer_name']) ?></td><td><?= e($sale['user_name']) ?></td><td><?= number_format((float) $sale['total_amount'],0,',','.') ?>đ</td><td><?= e($sale['status']) ?></td><td><?= e($sale['created_at']) ?></td></tr><?php endforeach; ?></table></section></main></body></html>
