<?php
require __DIR__.'/config/config.php';
require_login();
$customers = db()->query('SELECT * FROM customers ORDER BY id DESC')->fetchAll();
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Khách hàng</title><link rel="stylesheet" href="<?= BASE_URL ?>/style.css"></head><body><header class="topbar"><strong>NHÀ SÁCH TRÍ TUỆ</strong><a href="<?= BASE_URL ?>/index.php">Dashboard</a></header><main class="container"><h1>Khách hàng</h1><section class="panel"><table><tr><th>Họ tên</th><th>Email</th><th>Số điện thoại</th><th>Địa chỉ</th></tr><?php foreach ($customers as $customer): ?><tr><td><?= e($customer['name']) ?></td><td><?= e($customer['email']) ?></td><td><?= e($customer['phone']) ?></td><td><?= e($customer['address']) ?></td></tr><?php endforeach; ?></table></section></main></body></html>
