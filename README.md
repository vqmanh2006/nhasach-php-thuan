# Nha Sach Tri Tue - PHP thuan

Project PHP thuần + PDO + MySQL, không dùng Laravel hay Composer.

## Cài đặt

1. Import `database.sql` vào MySQL/phpMyAdmin.
2. Sửa thông tin MySQL ở `config/config.php`.
3. Đặt thư mục vào `C:\xamppp\htdocs\nhasach-php-thuan` (hoặc thư mục `htdocs` của XAMPP).
4. Trang quản trị: `http://localhost/nhasach-php-thuan/login.php`.
5. Trang khách hàng: `http://localhost/nhasach-php-thuan/shop.php`.

Tài khoản demo:

- Email: `admin@nhasach.local`
- Mật khẩu: `1234`

Có dashboard, quản lý sách, xóa sách, danh mục, khách hàng, lịch sử bán hàng, đăng ký/đăng nhập khách hàng, giỏ hàng và đặt hàng.

Nếu database đã được import trước đó, hãy import lại `database.sql` sau khi xóa database `nhasach`, vì bản mới có thêm cột mật khẩu khách hàng và bảng giỏ hàng.
