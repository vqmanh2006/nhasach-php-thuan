<?php
require __DIR__.'/config/config.php';
if (!empty($_SESSION['customer'])) redirect('/shop.php');
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    if ($_POST['password'] !== $_POST['password_confirmation']) {
        $error = 'Mật khẩu nhập lại không khớp.';
    } else {
        try {
            $query = db()->prepare('INSERT INTO customers (name, phone, email, address, password) VALUES (?,?,?,?,?)');
            $query->execute([trim($_POST['name']), trim($_POST['phone'] ?: null), trim($_POST['email']), trim($_POST['address'] ?: null), password_hash($_POST['password'], PASSWORD_DEFAULT)]);
            $_SESSION['customer'] = ['id' => (int) db()->lastInsertId(), 'name' => trim($_POST['name']), 'email' => trim($_POST['email'])];
            redirect('/shop.php');
        } catch (PDOException $exception) {
            $error = 'Email đã tồn tại hoặc dữ liệu không hợp lệ.';
        }
    }
}
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Đăng ký khách hàng</title><link rel="stylesheet" href="<?= BASE_URL ?>/style.css"></head><body class="auth"><main class="auth-card"><div class="logo">NHÀ SÁCH TRÍ TUỆ</div><h1>Tạo tài khoản</h1><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Họ tên<input name="name" required></label><label>Email<input type="email" name="email" required></label><label>Số điện thoại<input name="phone"></label><label>Địa chỉ<input name="address"></label><label>Mật khẩu<input type="password" name="password" minlength="6" required></label><label>Nhập lại mật khẩu<input type="password" name="password_confirmation" required></label><button>Đăng ký</button></form><p class="hint">Đã có tài khoản? <a href="<?= BASE_URL ?>/customer-login.php">Đăng nhập</a></p></main></body></html>
