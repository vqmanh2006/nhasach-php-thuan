<?php
require __DIR__.'/config/config.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { check_csrf(); $statement = db()->prepare('UPDATE customers SET is_active = 1 - is_active WHERE id=?'); $statement->execute([(int) $_POST['id']]); redirect('/customers.php'); }
$customers = db()->query('SELECT * FROM customers ORDER BY id DESC')->fetchAll();
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Khách hàng</title><link rel="stylesheet" href="<?= BASE_URL ?>/style.css"></head><body><header class="topbar"><strong>NHÀ SÁCH TRÍ TUỆ</strong><a href="<?= BASE_URL ?>/index.php">Dashboard</a></header><main class="container"><h1>Khách hàng</h1><section class="panel"><table><tr><th>Họ tên</th><th>Email</th><th>Điện thoại</th><th>Trạng thái</th><th></th></tr><?php foreach ($customers as $customer): ?><tr><td><?= e($customer['name']) ?></td><td><?= e($customer['email']) ?></td><td><?= e($customer['phone']) ?></td><td><?= $customer['is_active'] ? 'Đang hoạt động' : 'Đã khóa' ?></td><td><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= $customer['id'] ?>"><button class="<?= $customer['is_active'] ? 'danger' : '' ?>"><?= $customer['is_active'] ? 'Khóa' : 'Mở khóa' ?></button></form></td></tr><?php endforeach; ?></table></section></main></body></html>
