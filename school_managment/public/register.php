<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitizeInput($_POST['full_name']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($full_name)) {
        $errors[] = 'الاسم الكامل مطلوب';
    }
    
    if (empty($email)) {
        $errors[] = 'البريد الإلكتروني مطلوب';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'البريد الإلكتروني غير صالح';
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = 'البريد الإلكتروني مسجل بالفعل';
        }
    }
    
    if (empty($password)) {
        $errors[] = 'كلمة المرور مطلوبة';
    } elseif (strlen($password) < 6) {
        $errors[] = 'كلمة المرور يجب أن تكون على الأقل 6 أحرف';
    } elseif ($password !== $confirm_password) {
        $errors[] = 'كلمة المرور غير متطابقة';
    }
    
    // If no errors, register user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $hashed_password);
        
        if ($stmt->execute()) {
            $success = true;
            
            // Log the user in automatically
            $user_id = $stmt->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = 'user';
            
            // Set default theme
            updateTheme($user_id, 'default');
            
            // Redirect to profile
            redirect('profile.php');
        } else {
            $errors[] = 'حدث خطأ أثناء التسجيل. يرجى المحاولة مرة أخرى.';
        }
    }
}

$page_title = 'تسجيل جديد';
require_once '../includes/header.php';
?>

<section class="auth-section">
    <div class="auth-container">
        <h2><i class="fas fa-user-plus"></i> إنشاء حساب جديد</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                تم التسجيل بنجاح! يتم توجيهك الآن إلى صفحة الملف الشخصي.
            </div>
        <?php else: ?>
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="full_name"><i class="fas fa-user"></i> الاسم الكامل</label>
                    <input type="text" id="full_name" name="full_name" required 
                           value="<?php echo $_POST['full_name'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo $_POST['email'] ?? ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> كلمة المرور</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock"></i> تأكيد كلمة المرور</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> تسجيل حساب جديد
                </button>
            </form>
            
            <div class="auth-footer">
                <p>لديك حساب بالفعل؟ <a href="login.php">سجل الدخول الآن</a></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>