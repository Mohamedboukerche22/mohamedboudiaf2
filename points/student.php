<?php
include 'includes/db.php';
include 'includes/functions.php';

// Check if form was submitted
$displayResults = false;
$studentId = '';
$notes = [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = isset($_POST['studentId']) ? trim($_POST['studentId']) : '';
    
    if (empty($studentId)) {
        $error = 'الرجاء إدخال رقم الطالب';
    } else {
        $notes = getStudentNotes($studentId, $conn);
        $displayResults = true;
        
        if (empty($notes)) {
            $error = 'لم يتم العثور على نتائج لهذا الطالب';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة معلومات الطالب - ثانوية محمد بوضياف</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Additional styles to enhance the page */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #1abc9c;
            --light-bg: #f5f5f5;
            --border-color: #ddd;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
            color: #333;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        header {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
            text-align: center;
        }
        
        h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .school-info {
            font-size: 14px;
            opacity: 0.8;
        }
        
        .search-form {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
        }
        
        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: var(--secondary-color);
        }
        
        .error-message {
            color: #e74c3c;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #fadbd8;
            border-radius: 4px;
            border-left: 4px solid #e74c3c;
        }
        
        .notes-container {
            margin-top: 20px;
        }
        
        .note-card {
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        
        .note-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .note-detail {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .note-detail:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .points {
            font-size: 18px;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .no-results {
            text-align: center;
            padding: 30px;
            color: #7f8c8d;
        }
        
        footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
            color: #7f8c8d;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            header {
                padding: 15px;
                margin: -15px -15px 15px -15px;
            }
            
            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>لوحة معلومات الطالب</h1>
            <div class="school-info">ثانوية محمد بوضياف - ولاية سيدي بلعباس</div>
        </header>
        
        <div class="search-form">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-group">
                    <label for="studentId">أدخل رقم الطالب:</label>
                    <input type="text" id="studentId" name="studentId" value="<?php echo htmlspecialchars($studentId); ?>" required>
                </div>
                <button type="submit">عرض النتائج</button>
            </form>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($displayResults && !empty($notes)): ?>
            <div class="notes-container">
                <h2>نتائج الطالب</h2>
                <?php foreach ($notes as $note): ?>
                    <div class="note-card">
                        <div class="note-detail">
                            <strong>المادة:</strong> <?php echo htmlspecialchars($note['note']); ?>
                        </div>
                        <div class="note-detail">
                            <strong>النقاط:</strong> <span class="points"><?php echo htmlspecialchars($note['points']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif ($displayResults): ?>
            <div class="no-results">
                <p>لا توجد معلومات متاحة لهذا الطالب</p>
            </div>
        <?php endif; ?>
        
        <footer>
            <p>© <?php echo date('Y'); ?> ثانوية محمد بوضياف - جميع الحقوق محفوظة</p>
        </footer>
    </div>
    
    <script>
        // Simple form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const studentIdInput = document.getElementById('studentId');
            if (!studentIdInput.value.trim()) {
                e.preventDefault();
                alert('الرجاء إدخال رقم الطالب');
            }
        });
        
        // Add subtle animation to note cards
        const noteCards = document.querySelectorAll('.note-card');
        noteCards.forEach(card => {
            card.addEventListener('mouseover', function() {
                this.style.backgroundColor = '#f9f9f9';
            });
            
            card.addEventListener('mouseout', function() {
                this.style.backgroundColor = 'white';
            });
        });
    </script>
</body>
</html>