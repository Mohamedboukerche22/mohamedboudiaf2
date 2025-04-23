<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

$user_id = $_SESSION['user_id'];
$success = false;
$errors = [];

// Get user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = sanitizeInput($_POST['full_name']);
    $email = sanitizeInput($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($full_name)) {
        $errors[] = 'الاسم الكامل مطلوب';
    }
    
    if (empty($email)) {
        $errors[] = 'البريد الإلكتروني مطلوب';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'البريد الإلكتروني غير صالح';
    } elseif ($email != $user['email']) {
        // Check if new email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = 'البريد الإلكتروني مسجل بالفعل';
        }
    }
    
    // If password change requested
    if (!empty($new_password)) {
        if (empty($current_password)) {
            $errors[] = 'كلمة المرور الحالية مطلوبة لتغيير كلمة المرور';
        } elseif (!password_verify($current_password, $user['password'])) {
            $errors[] = 'كلمة المرور الحالية غير صحيحة';
        } elseif (strlen($new_password) < 6) {
            $errors[] = 'كلمة المرور الجديدة يجب أن تكون على الأقل 6 أحرف';
        } elseif ($new_password !== $confirm_password) {
            $errors[] = 'كلمة المرور الجديدة غير متطابقة';
        }
    }
    
    // If no errors, update profile
    if (empty($errors)) {
        // Prepare update query
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $full_name, $email, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $full_name, $email, $user_id);
        }
        
        if ($stmt->execute()) {
            // Update session data
            $_SESSION['full_name'] = $full_name;
            $_SESSION['email'] = $email;
            
            $success = true;
            $user['full_name'] = $full_name;
            $user['email'] = $email;
        } else {
            $errors[] = 'حدث خطأ أثناء تحديث الملف الشخصي. يرجى المحاولة مرة أخرى.';
        }
    }
}

// Handle theme change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_theme'])) {
    $theme_color = sanitizeInput($_POST['theme_color']);
    
    if (updateTheme($user_id, $theme_color)) {
        $success = true;
        $_SESSION['theme_updated'] = true;
        header("Refresh:0"); // Refresh to apply theme
    } else {
        $errors[] = 'حدث خطأ أثناء تغيير السمة. يرجى المحاولة مرة أخرى.';
    }
}

$page_title = 'الملف الشخصي';
require_once '../includes/header.php';
?>

<section class="profile-section">
    <div class="container">
        <div class="profile-header">
            <h2><i class="fas fa-user"></i> الملف الشخصي</h2>
            <p>إدارة معلومات حسابك وإعدادات السمة</p>
        </div>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                تم تحديث الملف الشخصي بنجاح!
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="profile-content">
            <div class="profile-card">
                <h3><i class="fas fa-user-edit"></i> معلومات الحساب</h3>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="full_name"><i class="fas fa-user"></i> الاسم الكامل</label>
                        <input type="text" id="full_name" name="full_name" 
                               value="<?php echo $user['full_name']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo $user['email']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="current_password"><i class="fas fa-lock"></i> كلمة المرور الحالية (للتغيير فقط)</label>
                        <input type="password" id="current_password" name="current_password">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password"><i class="fas fa-lock"></i> كلمة المرور الجديدة</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password"><i class="fas fa-lock"></i> تأكيد كلمة المرور الجديدة</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>
                    
                    <button type="submit" name="update_profile" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ التغييرات
                    </button>
                </form>
            </div>
            
            <div class="profile-card">
                <h3><i class="fas fa-palette"></i> إعدادات السمة</h3>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="theme_color"><i class="fas fa-moon"></i> اختر سمة الموقع</label>
                        <select id="theme_color" name="theme_color" class="form-control">
                            <option value="default" <?php echo getThemeColor($user_id) == 'default' ? 'selected' : ''; ?>>الافتراضي</option>
                            <option value="dark" <?php echo getThemeColor($user_id) == 'dark' ? 'selected' : ''; ?>>الوضع المظلم</option>
                            <option value="blue" <?php echo getThemeColor($user_id) == 'blue' ? 'selected' : ''; ?>>الازرق</option>
                            <option value="green" <?php echo getThemeColor($user_id) == 'green' ? 'selected' : ''; ?>>الاخضر</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="change_theme" class="btn btn-primary">
                        <i class="fas fa-save"></i> حفظ السمة
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>