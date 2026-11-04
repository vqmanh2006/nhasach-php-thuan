<?php
require __DIR__.'/config/config.php';

if (!empty($_SESSION['user'])) {
    redirect('/index.php');
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $statement = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $statement->execute([$email]);
    $user = $statement->fetch();
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = $user;
        redirect('/index.php');
    }
    $error = 'Email hoặc mật khẩu không chính xác.';
}
?>
<!doctype html>
<html lang="vi"><head><meta charset="utf-8"><title>Đăng nhập - <?= e(APP_NAME) ?></title><link rel="stylesheet" href="<?= BASE_URL ?>/style.css"></head>
<body class="auth"><main class="auth-card"><div class="logo">NHÀ SÁCH TRÍ TUỆ</div><h1>Đăng nhập quản trị</h1><?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Email<input type="email" name="email" required autofocus></label><label>Mật khẩu<input type="password" name="password" required></label><button>Đăng nhập</button></form><p class="hint">Demo: admin@nhasach.local / 1234</p></main></body></html>
