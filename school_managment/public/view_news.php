<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isset($_GET['id'])) {
    redirect('news.php');
}

$news_id = (int)$_GET['id'];
$news = getSingleNews($news_id);

if (!$news) {
    redirect('news.php');
}

$page_title = $news['title'];
require_once '../includes/header.php';
?>

<section class="single-news-section">
    <div class="container">
        <article class="single-news">
            <h2><?php echo $news['title']; ?></h2>
            
            <div class="news-meta">
                <span><i class="fas fa-user"></i> <?php echo $news['author_name']; ?></span>
                <span><i class="fas fa-clock"></i> <?php echo date('Y/m/d', strtotime($news['created_at'])); ?></span>
            </div>
            
            <div class="news-content">
                <?php echo nl2br($news['content']); ?>
            </div>
        </article>
        
        <div class="news-navigation">
            <a href="news.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> العودة إلى الأخبار
            </a>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>