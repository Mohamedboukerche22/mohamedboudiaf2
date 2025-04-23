<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($email)) {
        $errors[] = 'البريد الإلكتروني مطلوب';
    }
    
    if (empty($password)) {
        $errors[] = 'كلمة المرور مطلوبة';
    }
    
    // If no errors, try to login
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, full_name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect to appropriate page
                if ($user['role'] === 'admin') {
                    redirect('admin/dashboard.php');
                } else {
                    redirect('index.php');
                }
            } else {
                $errors[] = 'كلمة المرور غير صحيحة';
            }
        } else {
            $errors[] = 'البريد الإلكتروني غير مسجل';
        }
    }
}

$page_title = 'تسجيل الدخول';
require_once '../includes/header.php';
?>

<section class="auth-section">
    <div class="auth-container">
        <h2><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo $_POST['email'] ?? ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password"><i class="fas fa-lock"></i> كلمة المرور</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group remember-forgot">
                <label class="remember-me">
                    <input type="checkbox" name="remember"> تذكرني
                </label>
                <a href="forgot_password.php" class="forgot-password">نسيت كلمة المرور؟</a>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-sign-in-alt"></i> تسجيل الدخول
            </button>
        </form>
        
        <div class="auth-footer">
            <p>ليس لديك حساب؟ <a href="register.php">أنشئ حساب جديد</a></p>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
