<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Pagination
$per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $per_page) - $per_page : 0;

// Get total news
$total = $conn->query("SELECT COUNT(*) as total FROM news")->fetch_assoc()['total'];
$pages = ceil($total / $per_page);

// Get news with pagination
$news = $conn->query("SELECT news.id, news.title, news.content, users.full_name as author, news.created_at 
                      FROM news 
                      JOIN users ON news.author_id = users.id 
                      ORDER BY news.created_at DESC 
                      LIMIT $start, $per_page")->fetch_all(MYSQLI_ASSOC);

$page_title = 'الأخبار';
require_once '../includes/header.php';
?>

<section class="news-section">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-newspaper"></i> الأخبار</h2>
            <div class="section-divider"></div>
        </div>
        
        <div class="news-list">
            <?php foreach ($news as $item): ?>
                <article class="news-item">
                    <div class="news-date">
                        <span class="day"><?php echo date('d', strtotime($item['created_at'])); ?></span>
                        <span class="month"><?php echo date('M', strtotime($item['created_at'])); ?></span>
                    </div>
                    
                    <div class="news-content">
                        <h3><?php echo $item['title']; ?></h3>
                        <p class="news-meta">
                            <i class="fas fa-user"></i> <?php echo $item['author']; ?> | 
                            <i class="fas fa-clock"></i> <?php echo date('Y/m/d', strtotime($item['created_at'])); ?>
                        </p>
                        <p><?php echo substr($item['content'], 0, 200); ?>...</p>
                        <a href="view_news.php?id=<?php echo $item['id']; ?>" class="read-more">
                            قراءة المزيد <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
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
</section>

<?php require_once '../includes/footer.php'; ?>