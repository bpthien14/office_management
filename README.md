# Office Management System

Hệ thống quản lý nhân sự được xây dựng theo mô hình MVC sử dụng PHP, HTML, CSS, JavaScript, jQuery và MySQL.

## Tính năng chính

- **Quản lý nhân viên**: CRUD thông tin nhân viên, upload avatar
- **Quản lý nghỉ phép**: Tạo đơn xin nghỉ, duyệt đơn, lịch nghỉ phép
- **Quản lý thiết bị**: Mượn/trả thiết bị văn phòng, theo dõi trạng thái
- **Quản lý phòng họp**: Đặt phòng họp, lịch sử sử dụng
- **Hệ thống phân quyền**: Admin, HR, Nhân viên
- **Dashboard**: Thống kê tổng quan, hoạt động gần đây

## Yêu cầu hệ thống

- PHP 8.0+
- MySQL 8.0+
- Apache/Nginx
- Composer (khuyến nghị)

## Cài đặt

### 1. Clone repository
```bash
git clone <repository-url>
cd office_management
```

### 2. Cấu hình database
- Tạo database `office_management`
- Import file `OfficeManagementDB.sql` vào database
- Cập nhật thông tin database trong `config/database.php`

### 3. Cấu hình ứng dụng
Chỉnh sửa file `config/config.php`:
```php
define('APP_URL', 'http://localhost/office_management/public');
define('SECRET_KEY', 'your-secret-key-here');
```

### 4. Cấu hình web server

#### Apache
Đảm bảo mod_rewrite được bật và cấu hình DocumentRoot trỏ đến thư mục `public/`.

#### Nginx
```nginx
server {
    listen 80;
    server_name office-management.local;
    root /path/to/office_management/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 5. Phân quyền thư mục
```bash
chmod -R 755 office_management/
chmod -R 777 office_management/uploads/
```

## Cấu trúc thư mục

```
office_management/
├── config/                 # Cấu hình
│   ├── database.php       # Cấu hình database
│   ├── config.php         # Cấu hình ứng dụng
│   └── routes.php         # Định nghĩa routes
├── controllers/           # Controllers
│   ├── BaseController.php
│   ├── AuthController.php
│   └── DashboardController.php
├── models/                # Models
│   ├── BaseModel.php
│   ├── User.php
│   ├── Employee.php
│   ├── LeaveRequest.php
│   ├── Device.php
│   ├── DeviceBorrow.php
│   ├── Room.php
│   └── RoomBooking.php
├── views/                 # Views
│   ├── layouts/          # Layout chung
│   ├── auth/             # Views đăng nhập
│   ├── dashboard/        # Views dashboard
│   └── ...
├── core/                  # Core classes
│   ├── Router.php
│   ├── Database.php
│   ├── Session.php
│   └── Validator.php
├── assets/               # Static assets
│   ├── css/
│   ├── js/
│   └── images/
├── public/               # Public directory
│   ├── index.php
│   └── .htaccess
└── uploads/              # Uploaded files
    └── avatars/
```

## Sử dụng

### 1. Truy cập ứng dụng
Mở trình duyệt và truy cập: `http://localhost/office_management/public`

### 2. Tài khoản demo
- **Admin**: admin / admin123
- **HR**: hr / hr123
- **Employee**: employee / employee123

### 3. Các chức năng chính

#### Quản lý nhân viên
- Xem danh sách nhân viên
- Thêm/sửa/xóa nhân viên
- Upload avatar
- Tìm kiếm nhân viên

#### Quản lý nghỉ phép
- Tạo đơn xin nghỉ
- Duyệt/từ chối đơn nghỉ
- Xem lịch nghỉ phép
- Báo cáo thống kê

#### Quản lý thiết bị
- Danh sách thiết bị
- Mượn/trả thiết bị
- Theo dõi trạng thái
- Lịch sử mượn

#### Quản lý phòng họp
- Danh sách phòng họp
- Đặt phòng họp
- Lịch sử đặt phòng
- Quản lý xung đột

## API Endpoints

### Authentication
- `POST /api/login` - Đăng nhập
- `POST /api/logout` - Đăng xuất
- `GET /api/check-auth` - Kiểm tra trạng thái đăng nhập

### Dashboard
- `GET /api/stats` - Thống kê dashboard
- `GET /api/recent-activities` - Hoạt động gần đây
- `GET /api/upcoming-events` - Sự kiện sắp tới

### Employees
- `GET /api/employees` - Danh sách nhân viên
- `POST /api/employees` - Tạo nhân viên
- `PUT /api/employees/{id}` - Cập nhật nhân viên
- `DELETE /api/employees/{id}` - Xóa nhân viên

## Bảo mật

- Password hashing với bcrypt
- CSRF token protection
- Input validation và sanitization
- SQL injection prevention
- XSS protection
- Session management

## Phát triển

### Thêm Controller mới
1. Tạo file trong `controllers/`
2. Extend từ `BaseController`
3. Thêm routes trong `config/routes.php`

### Thêm Model mới
1. Tạo file trong `models/`
2. Extend từ `BaseModel`
3. Định nghĩa table và fillable fields

### Thêm View mới
1. Tạo file trong `views/`
2. Sử dụng layout chung
3. Include CSS/JS cần thiết

## Troubleshooting

### Lỗi 404
- Kiểm tra cấu hình mod_rewrite
- Đảm bảo .htaccess được load
- Kiểm tra DocumentRoot

### Lỗi database
- Kiểm tra thông tin kết nối
- Đảm bảo database đã được tạo
- Kiểm tra quyền truy cập

### Lỗi upload
- Kiểm tra quyền thư mục uploads/
- Kiểm tra cấu hình PHP upload
- Kiểm tra kích thước file

## Đóng góp

1. Fork repository
2. Tạo feature branch
3. Commit changes
4. Push to branch
5. Tạo Pull Request

## License

MIT License

## Hỗ trợ

Nếu gặp vấn đề, vui lòng tạo issue trên GitHub hoặc liên hệ qua email.
