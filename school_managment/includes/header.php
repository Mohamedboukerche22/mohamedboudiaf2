<?php
$current_theme = getThemeColor($_SESSION['user_id'] ?? null);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة المدرسة - <?php echo $page_title ?? 'الرئيسية'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/themes/<?php echo $current_theme; ?>.css">
</head>
<body data-theme="<?php echo $current_theme; ?>">
    <div class="loading-screen">
        <div class="spinner"></div>
    </div>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="../assets/images/school-logo.png" alt="شعار المدرسة" class="school-logo">
                    <div>
                        <h1>نظام إدارة المدرسة</h1>
                        <p class="tagline">نحو تعليم متميز وبناء أجيال واعية</p>
                    </div>
                </div>
                
                <div class="header-actions">
                    <?php if (isLoggedIn()): ?>
                        <div class="theme-toggle" id="darkModeToggle">
                            <i class="fas fa-moon"></i>
                            <i class="fas fa-sun"></i>
                        </div>
                        <a href="../profile.php" class="user-profile">
                            <i class="fas fa-user"></i>
                            <?php echo $_SESSION['full_name']; ?>
                        </a>
                        <a href="../logout.php" class="logout-btn">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    <?php endif; ?>
                    <button class="mobile-menu-btn" id="mobileMenuBtn">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <nav id="mainNav">
                <ul>
                    <li><a href="../index.php"><i class="fas fa-home"></i> الرئيسية</a></li>
                    <li><a href="../news.php"><i class="fas fa-newspaper"></i> الأخبار</a></li>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li><a href="../admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a></li>
                        <?php endif; ?>
                        <li><a href="../profile.php"><i class="fas fa-user"></i> الملف الشخصي</a></li>
                    <?php else: ?>
                        <li><a href="../login.php"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a></li>
                        <li><a href="../register.php"><i class="fas fa-user-plus"></i> تسجيل جديد</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">