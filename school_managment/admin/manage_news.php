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

// Get total news
$total = $conn->query("SELECT COUNT(*) as total FROM news")->fetch_assoc()['total'];
$pages = ceil($total / $per_page);

// Get news with pagination
$news = $conn->query("SELECT news.id, news.title, users.full_name as author, news.created_at, news.updated_at 
                      FROM news 
                      JOIN users ON news.author_id = users.id 
                      ORDER BY news.created_at DESC 
                      LIMIT $start, $per_page")->fetch_all(MYSQLI_ASSOC);

$page_title = 'إدارة الأخبار';
require_once '../../includes/header.php';
?>

<section class="admin-section">
    <div class="container">
        <div class="admin-header">
            <h2><i class="fas fa-newspaper"></i> إدارة الأخبار</h2>
            <div class="admin-actions">
                <a href="add_news.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> إضافة خبر جديد
                </a>
            </div>
        </div>
        
        <?php if (isset($_SESSION['news_message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['news_message']['type']; ?>">
                <?php echo $_SESSION['news_message']['text']; ?>
            </div>
            <?php unset($_SESSION['news_message']); ?>
        <?php endif; ?>
        
        <div class="admin-content">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>العنوان</th>
                            <th>المؤلف</th>
                            <th>تاريخ النشر</th>
                            <th>تاريخ التعديل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($news as $item): ?>
                            <tr>
                                <td><?php echo $item['title']; ?></td>
                                <td><?php echo $item['author']; ?></td>
                                <td><?php echo date('Y/m/d', strtotime($item['created_at'])); ?></td>
                                <td>
                                    <?php if ($item['updated_at'] != $item['created_at']): ?>
                                        <?php echo date('Y/m/d', strtotime($item['updated_at'])); ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit_news.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                    <a href="delete_news.php?id=<?php echo $item['id']; ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('هل أنت متأكد من حذف هذا الخبر؟');">
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