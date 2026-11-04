<?php
require __DIR__.'/config/config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $action = $_POST['action'] ?? '';
    if ($action === 'delete') {
        $statement = db()->prepare('DELETE FROM books WHERE id = ?');
        $statement->execute([(int) $_POST['id']]);
    } elseif ($action === 'update') {
        $statement = db()->prepare('UPDATE books SET category_id=?,title=?,isbn=?,author=?,publisher=?,price=?,stock_quantity=?,description=? WHERE id=?');
        $statement->execute([(int) $_POST['category_id'], trim($_POST['title']), trim($_POST['isbn'] ?: null), trim($_POST['author']), trim($_POST['publisher'] ?: null), (float) $_POST['price'], (int) $_POST['stock_quantity'], trim($_POST['description'] ?: null), (int) $_POST['id']]);
    } else {
        $statement = db()->prepare('INSERT INTO books (category_id,title,isbn,author,publisher,price,stock_quantity,description) VALUES (?,?,?,?,?,?,?,?)');
        $statement->execute([(int) $_POST['category_id'], trim($_POST['title']), trim($_POST['isbn'] ?: null), trim($_POST['author']), trim($_POST['publisher'] ?: null), (float) $_POST['price'], (int) $_POST['stock_quantity'], trim($_POST['description'] ?: null)]);
    }
    redirect('/books.php');
}
$categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$books = db()->query('SELECT b.*, c.name category_name FROM books b LEFT JOIN categories c ON c.id = b.category_id ORDER BY b.id DESC')->fetchAll();
$editBook = null;
if (isset($_GET['edit'])) {
    $edit = db()->prepare('SELECT * FROM books WHERE id=?');
    $edit->execute([(int) $_GET['edit']]);
    $editBook = $edit->fetch();
}
?>
<!doctype html><html lang="vi"><head><meta charset="utf-8"><title>Quản lý sách</title><link rel="stylesheet" href="<?= BASE_URL ?>/style.css"></head><body><header class="topbar"><strong>NHÀ SÁCH TRÍ TUỆ</strong><a href="<?= BASE_URL ?>/index.php">Dashboard</a></header><main class="container"><h1>Quản lý sách</h1><section class="panel"><h2>Thêm sách</h2><form method="post" class="form-grid"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="create"><label>Tên sách<input name="title" required></label><label>Tác giả<input name="author" required></label><label>ISBN<input name="isbn"></label><label>Danh mục<select name="category_id"><?php foreach ($categories as $category): ?><option value="<?= $category['id'] ?>"><?= e($category['name']) ?></option><?php endforeach; ?></select></label><label>Nhà xuất bản<input name="publisher"></label><label>Giá<input type="number" name="price" min="0" required></label><label>Tồn kho<input type="number" name="stock_quantity" min="0" required></label><label>Mô tả<textarea name="description"></textarea></label><button>Thêm sách</button></form></section><?php if ($editBook): ?><section class="panel"><h2>Chỉnh sửa sách</h2><form method="post" class="form-grid"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="update"><input type="hidden" name="id" value="<?= $editBook['id'] ?>"><label>Tên sách<input name="title" value="<?= e($editBook['title']) ?>" required></label><label>Tác giả<input name="author" value="<?= e($editBook['author']) ?>" required></label><label>ISBN<input name="isbn" value="<?= e($editBook['isbn']) ?>"></label><label>Danh mục<select name="category_id"><?php foreach ($categories as $category): ?><option value="<?= $category['id'] ?>" <?= (int) $editBook['category_id'] === (int) $category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option><?php endforeach; ?></select></label><label>Nhà xuất bản<input name="publisher" value="<?= e($editBook['publisher']) ?>"></label><label>Giá<input type="number" name="price" value="<?= e((string) $editBook['price']) ?>" min="0" required></label><label>Tồn kho<input type="number" name="stock_quantity" value="<?= $editBook['stock_quantity'] ?>" min="0" required></label><label>Mô tả<textarea name="description"><?= e($editBook['description']) ?></textarea></label><button>Lưu thay đổi</button></form></section><?php endif; ?><section class="panel"><h2>Danh sách sách</h2><table><tr><th>Tên</th><th>Tác giả</th><th>Giá</th><th>Tồn</th><th></th></tr><?php foreach ($books as $book): ?><tr><td><?= e($book['title']) ?></td><td><?= e($book['author']) ?></td><td><?= number_format((float) $book['price'],0,',','.') ?>đ</td><td><?= (int) $book['stock_quantity'] ?></td><td><a class="button small" href="<?= BASE_URL ?>/books.php?edit=<?= $book['id'] ?>">Sửa</a> <form class="inline" method="post" onsubmit="return confirm('Xóa sách này?')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $book['id'] ?>"><button class="danger">Xóa</button></form></td></tr><?php endforeach; ?></table></section></main></body></html>
