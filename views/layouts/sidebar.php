<!-- Sidebar -->
<div class="sidebar bg-dark text-white">
    <div class="sidebar-header p-3">
        <h5 class="mb-0">
            <i class="fas fa-building me-2"></i>
            Office Management
        </h5>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link <?= $_SERVER['REQUEST_URI'] === '/dashboard' ? 'active' : '' ?>" 
                   href="/dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            
            <!-- Employees -->
            <?php if (in_array($user['role'], ['admin', 'hr'])): ?>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/employees') !== false ? 'active' : '' ?>" 
                   href="/employees">
                    <i class="fas fa-users me-2"></i>
                    Quản lý nhân viên
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Leave Management -->
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/leaves') !== false ? 'active' : '' ?>" 
                   href="/leaves">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Nghỉ phép
                </a>
            </li>
            
            <!-- Device Management -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/devices') !== false ? 'active' : '' ?>" 
                   href="#" data-bs-toggle="dropdown">
                    <i class="fas fa-laptop me-2"></i>
                    Thiết bị
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/devices">Danh sách thiết bị</a></li>
                    <li><a class="dropdown-item" href="/devices/borrow">Mượn thiết bị</a></li>
                    <li><a class="dropdown-item" href="/devices/return">Trả thiết bị</a></li>
                    <li><a class="dropdown-item" href="/devices/approve">Duyệt mượn thiết bị</a></li>
                </ul>
            </li>
            
            <!-- Room Management -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle <?= strpos($_SERVER['REQUEST_URI'] ?? '', '/rooms') !== false ? 'active' : '' ?>"
                   href="#" data-bs-toggle="dropdown">
                    <i class="fas fa-door-open me-2"></i>
                    Phòng họp
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/rooms">Danh sách phòng họp</a></li>
                    <li><a class="dropdown-item" href="/rooms/booking">Đặt phòng họp</a></li>
                    <li><a class="dropdown-item" href="/rooms/calendar">Lịch phòng họp</a></li>
                    <li><a class="dropdown-item" href="/rooms/approve">Duyệt đặt phòng họp</a></li>
                </ul>
            </li>
            
            <!-- Reports -->
            <?php if (in_array($user['role'], ['admin', 'hr'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="/reports">
                    <i class="fas fa-chart-bar me-2"></i>
                    Báo cáo
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link" href="/settings">
                    <i class="fas fa-cog me-2"></i>
                    Cài đặt
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- User Info -->
    <div class="sidebar-footer p-3 mt-auto">
        <div class="d-flex align-items-center">
            <div class="avatar me-3">
                <i class="fas fa-user-circle fa-2x"></i>
            </div>
            <div class="user-info">
                <div class="fw-bold"><?= htmlspecialchars($user['username']) ?></div>
                <small class="text-muted"><?= ucfirst($user['role']) ?></small>
            </div>
        </div>
    </div>
</div>
