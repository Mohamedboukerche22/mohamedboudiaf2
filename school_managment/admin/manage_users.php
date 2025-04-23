<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

// Only admin can access this page
if (!isAdmin()) {
    redirect('../index.php');
}

// Pagination
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $per_page) - $per_page : 0;

// Get total users
$total = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$pages = ceil($total / $per_page);

// Get users with pagination
$users = $conn->query("SELECT id, full_name, email, role, created_at 
                      FROM users 
                      ORDER BY created_at DESC 
                      LIMIT $start, $per_page")->fetch_all(MYSQLI_ASSOC);

$page_title = 'إدارة المستخدمين';
require_once '../../includes/header.php';
?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h2><i class="fas fa-users-cog"></i> إدارة المستخدمين</h2>
            <div class="admin-actions">
                <a href="add_user.php" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> إضافة مستخدم جديد
                </a>
            </div>
        </div>
        
        <div class="admin-content">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>الاسم الكامل</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['full_name']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td>
                                    <span class="badge <?php echo $user['role'] === 'admin' ? 'badge-primary' : 'badge-secondary'; ?>">
                                        <?php echo $user['role'] === 'admin' ? 'مدير' : 'مستخدم'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('Y/m/d', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                                        <i class="fas fa-trash"></i> حذف
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>" class="page-link">
                            <i class="fas fa-arrow-right"></i> السابق
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="page-link">
                            التالي <i class="fas fa-arrow-left"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once '../../includes/footer.php'; ?>