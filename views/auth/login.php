<?php
$content = '
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <i class="fas fa-building fa-3x text-primary mb-3"></i>
                            <h3 class="fw-bold">Office Management</h3>
                            <p class="text-muted">Đăng nhập vào hệ thống</p>
                        </div>
                        
                        <!-- Login Form -->
                        <form method="POST" action="/login" id="loginForm">
                            <input type="hidden" name="_token" value="' . $csrf_token . '">
                            
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email đăng nhập
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="username" 
                                       name="username" 
                                       value="' . (isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '') . '"
                                       placeholder="Nhập email của bạn"
                                       required 
                                       autofocus>
                            </div>
                            
                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    Mật khẩu
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Remember Me -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="remember" 
                                       name="remember">
                                <label class="form-check-label" for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Đăng nhập
                                </button>
                            </div>
                        </form>
                        
                        <!-- Register Link -->
                        <div class="text-center mt-4">
                            <p class="mb-0">
                                Chưa có tài khoản? 
                                <a href="/register" class="text-decoration-none">
                                    Đăng ký ngay
                                </a>
                            </p>
                        </div>
                        
                        <!-- Demo Accounts -->
                        <div class="mt-4">
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-center mb-3">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Tài khoản demo
                                    </h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Admin:</small><br>
                                            <code>ngo.phuc@example.com / 123456</code>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">HR:</small><br>
                                            <code>tran.nam@example.com / 123456</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';

// Additional JavaScript
$additionalJS = ['<script>
// Wait for jQuery to be loaded
function initLoginPage() {
    if (typeof $ === "undefined") {
        // jQuery not loaded yet, wait and try again
        setTimeout(initLoginPage, 100);
        return;
    }
    
    $(document).ready(function() {
        // Toggle password visibility
        $("#togglePassword").click(function() {
            const passwordField = $("#password");
            const icon = $(this).find("i");
            
            if (passwordField.attr("type") === "password") {
                passwordField.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                passwordField.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });
        
        // Form validation
        $("#loginForm").on("submit", function(e) {
            const username = $("#username").val().trim();
            const password = $("#password").val();
            
            if (!username || !password) {
                e.preventDefault();
                alert("Vui lòng nhập đầy đủ thông tin đăng nhập");
                return false;
            }
            
            // Show loading state
            const submitBtn = $(this).find("button[type=submit]");
            submitBtn.prop("disabled", true);
            submitBtn.html("<i class=\"fas fa-spinner fa-spin me-2\"></i>Đang đăng nhập...");
        });
    });
}

// Start initialization
initLoginPage();
</script>'];

// Include main layout
include VIEWS_PATH . '/layouts/main.php';
?>
