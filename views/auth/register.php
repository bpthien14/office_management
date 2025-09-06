<?php
$content = '
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <i class="fas fa-building fa-3x text-primary mb-3"></i>
                            <h3 class="fw-bold">Office Management</h3>
                            <p class="text-muted">Tạo tài khoản mới</p>
                        </div>
                        
                        <!-- Register Form -->
                        <form method="POST" action="/register" id="registerForm">
                            <input type="hidden" name="_token" value="' . $csrf_token . '">
                            
                            <div class="row">
                                <!-- Username -->
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">
                                        <i class="fas fa-user me-1"></i>
                                        Tên đăng nhập *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="username" 
                                           name="username" 
                                           value="' . (isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '') . '"
                                           required>
                                    <div class="form-text">Tối thiểu 3 ký tự</div>
                                </div>
                                
                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>
                                        Email *
                                    </label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email" 
                                           value="' . (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '') . '"
                                           required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <!-- Password -->
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-1"></i>
                                        Mật khẩu *
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
                                    <div class="form-text">Tối thiểu 6 ký tự</div>
                                </div>
                                
                                <!-- Confirm Password -->
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="fas fa-lock me-1"></i>
                                        Xác nhận mật khẩu *
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               required>
                                        <button class="btn btn-outline-secondary" 
                                                type="button" 
                                                id="togglePasswordConfirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Terms and Conditions -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="terms" 
                                       name="terms" 
                                       required>
                                <label class="form-check-label" for="terms">
                                    Tôi đồng ý với 
                                    <a href="#" class="text-decoration-none">Điều khoản sử dụng</a>
                                    và 
                                    <a href="#" class="text-decoration-none">Chính sách bảo mật</a>
                                </label>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Đăng ký
                                </button>
                            </div>
                        </form>
                        
                        <!-- Login Link -->
                        <div class="text-center mt-4">
                            <p class="mb-0">
                                Đã có tài khoản? 
                                <a href="/login" class="text-decoration-none">
                                    Đăng nhập ngay
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
    
    // Toggle password confirmation visibility
    $("#togglePasswordConfirmation").click(function() {
        const passwordField = $("#password_confirmation");
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
    $("#registerForm").on("submit", function(e) {
        const username = $("#username").val().trim();
        const email = $("#email").val().trim();
        const password = $("#password").val();
        const passwordConfirmation = $("#password_confirmation").val();
        const terms = $("#terms").is(":checked");
        
        // Clear previous errors
        $(".is-invalid").removeClass("is-invalid");
        $(".invalid-feedback").remove();
        
        let hasErrors = false;
        
        // Validate username
        if (username.length < 3) {
            $("#username").addClass("is-invalid");
            $("#username").after("<div class=\"invalid-feedback\">Tên đăng nhập phải có ít nhất 3 ký tự</div>");
            hasErrors = true;
        }
        
        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            $("#email").addClass("is-invalid");
            $("#email").after("<div class=\"invalid-feedback\">Email không hợp lệ</div>");
            hasErrors = true;
        }
        
        // Validate password
        if (password.length < 6) {
            $("#password").addClass("is-invalid");
            $("#password").after("<div class=\"invalid-feedback\">Mật khẩu phải có ít nhất 6 ký tự</div>");
            hasErrors = true;
        }
        
        // Validate password confirmation
        if (password !== passwordConfirmation) {
            $("#password_confirmation").addClass("is-invalid");
            $("#password_confirmation").after("<div class=\"invalid-feedback\">Mật khẩu xác nhận không khớp</div>");
            hasErrors = true;
        }
        
        // Validate terms
        if (!terms) {
            $("#terms").addClass("is-invalid");
            $("#terms").after("<div class=\"invalid-feedback\">Bạn phải đồng ý với điều khoản sử dụng</div>");
            hasErrors = true;
        }
        
        if (hasErrors) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        const submitBtn = $(this).find("button[type=submit]");
        submitBtn.prop("disabled", true);
        submitBtn.html("<i class=\"fas fa-spinner fa-spin me-2\"></i>Đang đăng ký...");
    });
});
</script>
';

// Include main layout
include VIEWS_PATH . '/layouts/main.php';
?>
