# Kế hoạch Xây dựng Website Quản lý Nhân sự

## 1. Tổng quan Dự án

### Mục tiêu
Xây dựng hệ thống quản lý nhân sự theo mô hình MVC với các chức năng:
- Quản lý thông tin nhân viên
- Quản lý nghỉ phép
- Quản lý thiết bị văn phòng
- Quản lý phòng họp
- Hệ thống phân quyền người dùng

### Công nghệ sử dụng
- **Backend**: PHP 8.0+
- **Frontend**: HTML5, CSS3, JavaScript ES6, jQuery
- **Database**: MySQL 8.0
- **Pattern**: MVC (Model-View-Controller)

## 2. Cấu trúc Thư mục Dự án

```
office_management/
├── config/
│   ├── database.php
│   ├── config.php
│   └── routes.php
├── controllers/
│   ├── BaseController.php
│   ├── AuthController.php
│   ├── UserController.php
│   ├── EmployeeController.php
│   ├── LeaveController.php
│   ├── DeviceController.php
│   └── RoomController.php
├── models/
│   ├── BaseModel.php
│   ├── User.php
│   ├── Employee.php
│   ├── LeaveRequest.php
│   ├── Device.php
│   ├── DeviceBorrow.php
│   ├── Room.php
│   └── RoomBooking.php
├── views/
│   ├── layouts/
│   │   ├── header.php
│   │   ├── footer.php
│   │   ├── sidebar.php
│   │   └── main.php
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── dashboard/
│   │   └── index.php
│   ├── employees/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── view.php
│   ├── leaves/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── approve.php
│   │   └── calendar.php
│   ├── devices/
│   │   ├── index.php
│   │   ├── borrow.php
│   │   └── return.php
│   └── rooms/
│       ├── index.php
│       ├── booking.php
│       └── calendar.php
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   ├── responsive.css
│   │   └── admin.css
│   ├── js/
│   │   ├── main.js
│   │   ├── calendar.js
│   │   └── form-validation.js
│   └── images/
├── core/
│   ├── Router.php
│   ├── Database.php
│   ├── Session.php
│   └── Validator.php
├── public/
│   ├── index.php
│   └── .htaccess
└── uploads/
    └── avatars/
```

## 3. Thiết kế Database

### Các bảng chính (dựa trên tài liệu):

1. **USERS** - Quản lý tài khoản đăng nhập
2. **EMPLOYEES** - Thông tin chi tiết nhân viên
3. **LEAVE_REQUESTS** - Đơn xin nghỉ phép
4. **DEVICES** - Thiết bị văn phòng
5. **DEVICE_BORROW** - Lịch sử mượn thiết bị
6. **ROOMS** - Phòng họp
7. **ROOM_BOOKING** - Đặt phòng họp

## 4. Phân tích Chức năng

### 4.1 Hệ thống Đăng nhập/Phân quyền
- **Vai trò**: Admin, HR, Nhân viên
- **Chức năng**: Đăng nhập, đăng xuất, quản lý session
- **Bảo mật**: Password hashing, CSRF protection

### 4.2 Quản lý Nhân viên
- **Admin/HR**: CRUD nhân viên, upload ảnh đại diện
- **Nhân viên**: Xem thông tin cá nhân, cập nhật một số thông tin

### 4.3 Quản lý Nghỉ phép
- **Nhân viên**: Tạo đơn xin nghỉ, xem lịch sử
- **HR/Admin**: Duyệt/từ chối đơn, báo cáo thống kê
- **Calendar**: Hiển thị lịch nghỉ phép

### 4.4 Quản lý Thiết bị
- **Nhân viên**: Đăng ký mượn thiết bị
- **Admin**: Quản lý thiết bị, duyệt đơn mượn
- **Tracking**: Theo dõi trạng thái thiết bị

### 4.5 Quản lý Phòng họp
- **Nhân viên**: Đặt phòng họp
- **Admin**: Quản lý phòng, duyệt đặt phòng
- **Calendar**: Lịch sử dụng phòng

## 5. Lộ trình Phát triển (8-10 tuần)

### Tuần 1-2: Thiết lập Cơ sở
- [ ] Thiết lập cấu trúc thư mục MVC
- [ ] Cấu hình database connection
- [ ] Tạo Router và Base classes
- [ ] Import và test database script

### Tuần 3: Hệ thống Đăng nhập
- [ ] Model User
- [ ] AuthController (login/logout)
- [ ] Session management
- [ ] Views đăng nhập/đăng ký

### Tuần 4: Quản lý Nhân viên
- [ ] Employee Model và Controller
- [ ] CRUD operations
- [ ] Upload avatar functionality
- [ ] Employee views

### Tuần 5: Quản lý Nghỉ phép
- [ ] LeaveRequest Model
- [ ] LeaveController
- [ ] Form tạo đơn nghỉ phép
- [ ] Workflow duyệt đơn

### Tuần 6: Quản lý Thiết bị
- [ ] Device và DeviceBorrow Models
- [ ] DeviceController
- [ ] Workflow mượn/trả thiết bị
- [ ] Device management views

### Tuần 7: Quản lý Phòng họp
- [ ] Room và RoomBooking Models
- [ ] RoomController
- [ ] Booking system
- [ ] Calendar integration

### Tuần 8: Giao diện và UX
- [ ] Responsive design
- [ ] Dashboard tổng quan
- [ ] Navigation và menu
- [ ] Form validation (client-side)

### Tuần 9: Tính năng nâng cao
- [ ] Calendar views (FullCalendar.js)
- [ ] Export/Import data
- [ ] Email notifications
- [ ] Reports và analytics

### Tuần 10: Testing và Deploy
- [ ] Unit testing
- [ ] Integration testing
- [ ] Performance optimization
- [ ] Documentation

## 6. Technical Specifications

### 6.1 MVC Architecture

**Model**: Xử lý business logic và database operations
```php
class BaseModel {
    protected $db;
    protected $table;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function find($id) { /* ... */ }
    public function create($data) { /* ... */ }
    public function update($id, $data) { /* ... */ }
    public function delete($id) { /* ... */ }
}
```

**Controller**: Xử lý HTTP requests và responses
```php
class BaseController {
    protected function view($view, $data = []) { /* ... */ }
    protected function redirect($url) { /* ... */ }
    protected function json($data) { /* ... */ }
}
```

**View**: Render HTML templates

### 6.2 Security Features
- Password hashing (bcrypt)
- CSRF token protection
- Input validation và sanitization
- SQL injection prevention (PDO prepared statements)
- XSS protection

### 6.3 Database Design Patterns
- Active Record pattern cho Models
- Repository pattern cho complex queries
- Database migrations

## 7. Features Implementation

### 7.1 Dashboard
- Overview statistics
- Recent activities
- Quick actions
- Notifications

### 7.2 Calendar Integration
- FullCalendar.js cho leave calendar
- Room booking calendar
- Color-coded events

### 7.3 File Upload
- Avatar upload cho employees
- Document attachments cho leave requests
- File type validation

### 7.4 Responsive Design
- Mobile-first approach
- Bootstrap 5 hoặc custom CSS Grid
- Touch-friendly interfaces

## 8. Development Guidelines

### 8.1 Coding Standards
- PSR-4 autoloading
- PSR-12 coding style
- Meaningful variable và function names
- Comprehensive comments

### 8.2 Database Guidelines
- Proper indexing
- Foreign key constraints
- Data validation at DB level
- Backup strategies

### 8.3 Frontend Guidelines
- Progressive enhancement
- Accessibility (WCAG 2.1)
- Cross-browser compatibility
- Performance optimization

## 9. Testing Strategy

### 9.1 Unit Testing
- PHPUnit cho backend logic
- Jest cho JavaScript functions

### 9.2 Integration Testing
- Database operations
- API endpoints
- User workflows

### 9.3 User Acceptance Testing
- Role-based testing
- Cross-browser testing
- Mobile device testing

## 10. Deployment Plan

### 10.1 Server Requirements
- PHP 8.0+
- MySQL 8.0+
- Apache/Nginx
- SSL certificate

### 10.2 Environment Setup
- Development environment
- Staging environment
- Production environment

### 10.3 Maintenance
- Regular backups
- Security updates
- Performance monitoring
- Error logging

---

**Lưu ý**: Kế hoạch này có thể điều chỉnh tùy theo yêu cầu cụ thể và timeline của dự án. Ưu tiên phát triển MVP (Minimum Viable Product) trước, sau đó mở rộng các tính năng nâng cao.