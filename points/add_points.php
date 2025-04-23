<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';
if (!isAdmin() && $_SESSION['role'] !== 'teacher') {
    redirect('index.php');
}

$page_title = 'إضافة النقاط';
require_once '../includes/header.php';
$classes = $conn->query("SELECT DISTINCT class FROM students ORDER BY class")->fetch_all(MYSQLI_ASSOC);
$students = [];
$subjects = ['الرياضيات', 'الفيزياء', 'اللغة العربية', 'اللغة الفرنسية', 'التاريخ والجغرافيا'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = sanitizeInput($_POST['student_id']);
    $subject = sanitizeInput($_POST['subject']);
    $points = (float)$_POST['points'];
    $date = sanitizeInput($_POST['date']);
    $errors = [];
    if (empty($student_id)) $errors[] = 'يجب اختيار الطالب';
    if (empty($subject)) $errors[] = 'يجب اختيار المادة';
    if ($points < 0 || $points > 20) $errors[] = 'النقاط يجب أن تكون بين 0 و 20';
    if (empty($date)) $errors[] = 'يجب إدخال التاريخ';
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO grades (student_id, subject, points, date, teacher_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isdsi", $student_id, $subject, $points, $date, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = ['type' => 'success', 'text' => 'تم إضافة النقاط بنجاح'];
            redirect('add_points.php');
        } else {
            $errors[] = 'حدث خطأ أثناء حفظ النقاط';
        }
    }
}
if (isset($_GET['class'])) {
    $class = sanitizeInput($_GET['class']);
    $students = $conn->query("SELECT id, full_name FROM students WHERE class = '$class' ORDER BY full_name")->fetch_all(MYSQLI_ASSOC);
}
?>

<section class="points-section">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-star"></i> إضافة النقاط</h2>
            <div class="section-divider"></div>
        </div>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message']['type'] ?>">
                <?= $_SESSION['message']['text'] ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="points-form-container">
            <form method="GET" class="filter-form">
                <div class="form-group">
                    <label for="class"><i class="fas fa-users-class"></i> اختر الصف:</label>
                    <select id="class" name="class" required onchange="this.form.submit()">
                        <option value="">-- اختر الصف --</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['class'] ?>" <?= isset($_GET['class']) && $_GET['class'] == $class['class'] ? 'selected' : '' ?>>
                                <?= $class['class'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php if (!empty($students)): ?>
                <form method="POST" class="points-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="student_id"><i class="fas fa-user-graduate"></i> الطالب:</label>
                            <select id="student_id" name="student_id" required>
                                <option value="">-- اختر الطالب --</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?= $student['id'] ?>"><?= $student['full_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="subject"><i class="fas fa-book"></i> المادة:</label>
                            <select id="subject" name="subject" required>
                                <option value="">-- اختر المادة --</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= $subject ?>"><?= $subject ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="points"><i class="fas fa-star"></i> النقاط:</label>
                            <input type="number" id="points" name="points" min="0" max="20" step="0.25" required>
                        </div>

                        <div class="form-group">
                            <label for="date"><i class="fas fa-calendar-day"></i> التاريخ:</label>
                            <input type="date" id="date" name="date" required value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> حفظ النقاط
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    .points-section {
        padding: 40px 0;
    }
    
    .points-form-container {
        background-color: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .filter-form {
        margin-bottom: 30px;
    }
    
    .points-form .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .points-form .form-group {
        flex: 1;
    }
    
    .points-form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }
    
    .points-form select,
    .points-form input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: 'Tajawal', sans-serif;
        font-size: 1rem;
    }
    
    .form-actions {
        text-align: left;
        margin-top: 30px;
    }
    
    @media (max-width: 768px) {
        .points-form .form-row {
            flex-direction: column;
            gap: 15px;
        }
    }
</style>

<?php require_once '../includes/footer.php'; ?>
