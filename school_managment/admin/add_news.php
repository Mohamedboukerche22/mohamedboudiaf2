<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

// Only admin can access this page
if (!isAdmin()) {
    redirect('../index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitizeInput($_POST['title']);
    $content = sanitizeInput($_POST['content']);
    
    // Validate inputs
    if (empty($title)) {
        $errors[] = 'عنوان الخبر مطلوب';
    }
    
    if (empty($content)) {
        $errors[] = 'محتوى الخبر مطلوب';
    }
    
    // If no errors, add news
    if (empty($errors)) {
        $author_id = $_SESSION['user_id'];
        
        $stmt = $conn->prepare("INSERT INTO news (title, content, author_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $content, $author_id);
        
        if ($stmt->execute()) {
            $_SESSION['news_message'] = [
                'type' => 'success',
                'text' => 'تم إضافة الخبر بنجاح'
            ];
            redirect('manage_news.php');
        } else {
            $errors[] = 'حدث خطأ أثناء إضافة الخبر. يرجى المحاولة مرة أخرى.';
        }
    }
}

$page_title = 'إضافة خبر جديد';
require_once '../../includes/header.php';
?>

<section class="admin-form-section">
    <div class="container">
        <h2><i class="fas fa-plus"></i> إضافة خبر جديد</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="admin-form">
            <div class="form-group">
                <label for="title">عنوان الخبر</label>
                <input type="text" id="title" name="title" required 
                       value="<?php echo $_POST['title'] ?? ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="content">محتوى الخبر</label>
                <textarea id="content" name="content" rows="10" required><?php echo $_POST['content'] ?? ''; ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> حفظ الخبر
                </button>
                <a href="manage_news.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</section>

<?php require_once '../../includes/footer.php'; ?>