<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

// Only admin can access this page
if (!isAdmin()) {
    redirect('../index.php');
}

// Get stats for dashboard
$users_count = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$news_count = $conn->query("SELECT COUNT(*) as count FROM news")->fetch_assoc()['count'];
$latest_users = $conn->query("SELECT full_name, email, created_at FROM users ORDER BY created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
$latest_news = $conn->query("SELECT news.title, users.full_name as author, news.created_at 
                            FROM news 
                            JOIN users ON news.author_id = users.id 
                            ORDER BY news.created_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

$page_title = 'لوحة التحكم';
require_once '../../includes/header.php';
?>

<section class="admin-dashboard">
    <div class="container">
        <h2><i class="fas fa-tachometer-alt"></i> لوحة التحكم</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>إجمالي المستخدمين</h3>
                    <p><?php echo $users_count; ?></p>
                </div>
                <a href="manage_users.php" class="stat-link">عرض الكل <i class="fas fa-arrow-left"></i></a>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="stat-info">
                    <h3>إجمالي الأخبار</h3>
                    <p><?php echo $news_count; ?></p>
                </div>
                <a href="manage_news.php" class="stat-link">عرض الكل <i class="fas fa-arrow-left"></i></a>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="dashboard-card">
                <h3><i class="fas fa-user-clock"></i> أحدث المستخدمين</h3>
                
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>تاريخ التسجيل</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latest_users as $user): ?>
                                <tr>
                                    <td><?php echo $user['full_name']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo date('Y/m/d', strtotime($user['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="dashboard-card">
                <h3><i class="fas fa-newspaper"></i> أحدث الأخبار</h3>
                
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>العنوان</th>
                                <th>المؤلف</th>
                                <th>تاريخ النشر</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($latest_news as $news): ?>
                                <tr>
                                    <td><?php echo $news['title']; ?></td>
                                    <td><?php echo $news['author']; ?></td>
                                    <td><?php echo date('Y/m/d', strtotime($news['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once '../../includes/footer.php'; ?>
