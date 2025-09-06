<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="/dashboard">
            <i class="fas fa-building me-2"></i>
            Office Management
        </a>
        
        <!-- Mobile toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link <?= ($_SERVER['REQUEST_URI'] ?? '') === '/dashboard' ? 'active' : '' ?>" 
                       href="/dashboard">
                        <i class="fas fa-tachometer-alt me-1"></i>
                        Dashboard
                    </a>
                </li>
                
                <!-- Employees -->
                <?php if (in_array($user['role'], ['admin', 'hr'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/employees') !== false ? 'active' : '' ?>" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-users me-1"></i>
                        Nhân viên
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/employees">
                            <i class="fas fa-list me-2"></i>Danh sách nhân viên
                        </a></li>
                        <li><a class="dropdown-item" href="/employees/create">
                            <i class="fas fa-user-plus me-2"></i>Thêm nhân viên
                        </a></li>
                    </ul>
                </li>
                <?php endif; ?>
                
                <!-- Leave Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/leaves') !== false ? 'active' : '' ?>" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Nghỉ phép
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/leaves">
                            <i class="fas fa-list me-2"></i>Danh sách đơn nghỉ
                        </a></li>
                        <li><a class="dropdown-item" href="/leaves/create">
                            <i class="fas fa-plus me-2"></i>Tạo đơn nghỉ
                        </a></li>
                        <li><a class="dropdown-item" href="/leaves/calendar">
                            <i class="fas fa-calendar me-2"></i>Lịch nghỉ phép
                        </a></li>
                        <?php if (in_array($user['role'], ['admin', 'hr'])): ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/leaves/approve">
                            <i class="fas fa-check me-2"></i>Duyệt đơn nghỉ
                        </a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                
                <!-- Device Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/devices') !== false ? 'active' : '' ?>" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-laptop me-1"></i>
                        Thiết bị
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/devices">
                            <i class="fas fa-list me-2"></i>Danh sách thiết bị
                        </a></li>
                        <li><a class="dropdown-item" href="/devices/borrow">
                            <i class="fas fa-hand-holding me-2"></i>Mượn thiết bị
                        </a></li>
                        <li><a class="dropdown-item" href="/devices/return">
                            <i class="fas fa-undo me-2"></i>Trả thiết bị
                        </a></li>
                    </ul>
                </li>
                
                <!-- Room Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/rooms') !== false ? 'active' : '' ?>" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-door-open me-1"></i>
                        Phòng họp
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/rooms">
                            <i class="fas fa-list me-2"></i>Danh sách phòng
                        </a></li>
                        <li><a class="dropdown-item" href="/rooms/booking">
                            <i class="fas fa-calendar-plus me-2"></i>Đặt phòng
                        </a></li>
                        <li><a class="dropdown-item" href="/rooms/calendar">
                            <i class="fas fa-calendar me-2"></i>Lịch phòng họp
                        </a></li>
                    </ul>
                </li>
            </ul>
            
            <!-- User Menu -->
            <ul class="navbar-nav">
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <li><h6 class="dropdown-header">Thông báo</h6></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-calendar text-primary me-2"></i>
                            Đơn xin nghỉ mới cần duyệt
                        </a></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-laptop text-warning me-2"></i>
                            Thiết bị sắp hết bảo hành
                        </a></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-door-open text-info me-2"></i>
                            Đặt phòng họp hôm nay
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#">Xem tất cả</a></li>
                    </ul>
                </li>
                
                <!-- User Profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <?= htmlspecialchars($user['username']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header"><?= htmlspecialchars($user['username']) ?></h6></li>
                        <li><a class="dropdown-item" href="/profile">
                            <i class="fas fa-user me-2"></i>Thông tin cá nhân
                        </a></li>
                        <li><a class="dropdown-item" href="/settings">
                            <i class="fas fa-cog me-2"></i>Cài đặt
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
