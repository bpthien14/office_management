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
                <a class="nav-link <?= $_SERVER['REQUEST_URI'] === '/office_management/public/dashboard' ? 'active' : '' ?>" 
                   href="/office_management/public/dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            
            <!-- Employees -->
            <?php if (in_array($user['role'], ['admin', 'hr'])): ?>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/employees') !== false ? 'active' : '' ?>" 
                   href="/office_management/public/employees">
                    <i class="fas fa-users me-2"></i>
                    Quản lý nhân viên
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Leave Management -->
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/leaves') !== false ? 'active' : '' ?>" 
                   href="/office_management/public/leaves">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Nghỉ phép
                </a>
            </li>
            
            <!-- Device Management -->
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/devices') !== false ? 'active' : '' ?>" 
                   href="/office_management/public/devices">
                    <i class="fas fa-laptop me-2"></i>
                    Thiết bị
                </a>
            </li>
            
            <!-- Room Management -->
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/rooms') !== false ? 'active' : '' ?>" 
                   href="/office_management/public/rooms">
                    <i class="fas fa-door-open me-2"></i>
                    Phòng họp
                </a>
            </li>
            
            <!-- Reports -->
            <?php if (in_array($user['role'], ['admin', 'hr'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="/office_management/public/reports">
                    <i class="fas fa-chart-bar me-2"></i>
                    Báo cáo
                </a>
            </li>
            <?php endif; ?>
            
            <!-- Settings -->
            <li class="nav-item">
                <a class="nav-link" href="/office_management/public/settings">
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
