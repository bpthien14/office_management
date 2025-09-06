# Hướng Dẫn Sử Dụng Hệ Thống Quản Lý Văn Phòng

## 🚀 Cài Đặt và Chạy Hệ Thống

### 1. Yêu Cầu Hệ Thống
- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Web server (Apache/Nginx) hoặc PHP built-in server

### 2. Cài Đặt Database
```bash
# 1. Tạo database
mysql -u root -p -e "CREATE DATABASE OfficeManagementDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Import dữ liệu
mysql -u root -p OfficeManagementDB < OfficeManagementDB.sql
```

### 3. Cấu Hình Database
Cập nhật file `config/database.php`:
```php
return [
    'host' => 'localhost',
    'dbname' => 'OfficeManagementDB',
    'username' => 'root',
    'password' => 'your_mysql_password', // Thay đổi mật khẩu MySQL của bạn
    'charset' => 'utf8mb4',
    // ...
];
```

### 4. Chạy Hệ Thống

#### Sử dụng PHP Built-in Server:
```bash
php -S localhost:8000 -t public
```

#### Sử dụng Apache/Nginx:
- Đặt thư mục `public` làm document root
- Cấu hình URL rewriting cho file `.htaccess`

### 5. Truy Cập Hệ Thống
Mở trình duyệt và truy cập: `http://localhost:8000`

**Lưu ý**: Nếu gặp lỗi 404 hoặc constants warnings, hãy dừng server và khởi động lại:
```bash
# Dừng server hiện tại
pkill -f "php -S localhost:8000"

# Khởi động lại
php -S localhost:8000 -t public
```

## 📊 Thông Tin Database

### Các Bảng Chính:
- **USERS**: 15 tài khoản người dùng
- **EMPLOYEES**: 10 nhân viên
- **DEVICES**: 15 loại thiết bị
- **ROOMS**: 15 phòng họp
- **LEAVE_REQUESTS**: 20 đơn xin nghỉ phép
- **DEVICE_BORROW**: 10 yêu cầu mượn thiết bị
- **ROOM_BOOKING**: 0 đặt phòng (chưa có dữ liệu)

### Tài Khoản Mẫu:
- **Admin**: `ngo.phuc@example.com` / `123456` (Phó phòng Kỹ thuật)
- **HR**: `tran.nam@example.com` / `123456` (Trưởng phòng Nhân sự)
- **Nhân viên**: `nguyen.hoa@example.com` / `123456` (Nhân viên Kế toán)

## 🔧 Chức Năng Hệ Thống

### 1. Quản Lý Nhân Viên
- Xem danh sách nhân viên
- Thêm/sửa/xóa thông tin nhân viên
- Quản lý lịch sử làm việc
- Upload ảnh đại diện

### 2. Quản Lý Thiết Bị
- Danh sách thiết bị có sẵn
- Yêu cầu mượn thiết bị
- Phê duyệt/từ chối yêu cầu
- Theo dõi trạng thái mượn/trả

### 3. Quản Lý Phòng Họp
- Xem danh sách phòng họp
- Đặt phòng họp
- Phê duyệt đặt phòng
- Kiểm tra lịch sử đặt phòng

### 4. Quản Lý Nghỉ Phép
- Tạo đơn xin nghỉ phép
- Phê duyệt/từ chối đơn nghỉ
- Theo dõi lịch sử nghỉ phép
- Báo cáo thống kê

## 🛠️ Kiểm Tra Hệ Thống

### Test Database:
```bash
php test_db.php
```

### Test Kết Nối:
```bash
php -r "
require_once 'config/database.php';
\$config = require 'config/database.php';
\$pdo = new PDO('mysql:host='.\$config['host'].';dbname='.\$config['dbname'], \$config['username'], \$config['password']);
echo 'Kết nối thành công!';
"
```

## 📝 Ghi Chú Quan Trọng

1. **Bảo Mật**: Thay đổi mật khẩu mặc định trong production
2. **Backup**: Thường xuyên backup database
3. **Logs**: Kiểm tra file log trong thư mục `logs/`
4. **Upload**: Thư mục `uploads/` cần quyền ghi

## 🆘 Xử Lý Lỗi Thường Gặp

### Lỗi kết nối database:
- Kiểm tra MySQL đang chạy
- Kiểm tra thông tin cấu hình trong `config/database.php`
- Kiểm tra database `OfficeManagementDB` đã được tạo

### Lỗi 404:
- Kiểm tra URL rewriting
- Kiểm tra file `.htaccess` trong thư mục `public`

### Lỗi quyền truy cập:
- Kiểm tra quyền ghi cho thư mục `uploads/`
- Kiểm tra quyền đọc cho thư mục `logs/`

## 📞 Hỗ Trợ

Nếu gặp vấn đề, vui lòng kiểm tra:
1. File log trong thư mục `logs/`
2. Cấu hình database
3. Quyền truy cập file và thư mục
4. Phiên bản PHP và MySQL
