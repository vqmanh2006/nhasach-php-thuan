<?php
require __DIR__.'/config/config.php';
if (!empty($_SESSION['customer'])) redirect('/shop.php');
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $query = db()->prepare('SELECT * FROM customers WHERE email = ? AND is_active = 1 LIMIT 1');
    $query->execute([trim($_POST['email'])]);
    $customer = $query->fetch();
    if ($customer && $customer['password'] && password_verify($_POST['password'], $customer['password'])) {
        session_regenerate_id(true);
        $_SESSION['customer'] = $customer;
        redirect('/shop.php');
    }
    $error = 'Email hoặc mật khẩu không chính xác.';
}
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Đăng nhập khách hàng</title><link rel="stylesheet" href="<?= BASE_URL ?>/style.css"></head><body class="auth"><main class="auth-card"><div class="logo">NHÀ SÁCH TRÍ TUỆ</div><h1>Đăng nhập khách hàng</h1><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Email<input type="email" name="email" required></label><label>Mật khẩu<input type="password" name="password" required></label><button>Đăng nhập</button></form><p class="hint">Chưa có tài khoản? <a href="<?= BASE_URL ?>/customer-register.php">Đăng ký</a></p></main></body></html>
