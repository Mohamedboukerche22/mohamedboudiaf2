<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المدير - ثانوية محمد بوضياف</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: #f0f2f5;
    height: 100vh;
    display: flex;
    flex-direction: column;
}

.admin-header {
    background-color: #2c3e50;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.admin-title {
    font-size: 22px;
    font-weight: bold;
}

.admin-controls {
    display: flex;
    gap: 15px;
    align-items: center;
}

.admin-user {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #1abc9c;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-weight: bold;
}

.logout-btn {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.main-container {
    display: flex;
    flex: 1;
    overflow: hidden;
}

.sidebar {
    width: 240px;
    background-color: #34495e;
    color: white;
    padding: 20px 0;
    overflow-y: auto;
}

.sidebar-menu {
    list-style: none;
}

.sidebar-menu li {
    padding: 12px 20px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.sidebar-menu li:hover {
    background-color: #2c3e50;
}

.sidebar-menu li.active {
    background-color: #2c3e50;
    border-right: 4px solid #1abc9c;
}

.sidebar-menu li i {
    margin-left: 10px;
}

.content-area {
    flex: 1;
    padding: 30px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #ecf0f1;
}

.admin-panel-title {
    font-size: 24px;
    margin-bottom: 40px;
    color: #2c3e50;
    text-align: center;
}

.admin-buttons {
    display: flex;
    flex-direction: column;
    gap: 25px;
    width: 100%;
    max-width: 400px;
}

.admin-btn {
    background-color: #222;
    color: white;
    border: none;
    padding: 16px 20px;
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
    text-align: center;
}

.admin-btn:hover {
    background-color: #333;
    transform: translateY(-2px);
}

.admin-btn:active {
    transform: translateY(0);
}

.login-form {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #2c3e50;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.form-submit {
    background-color: #2c3e50;
    color: white;
    border: none;
    padding: 12px;
    width: 100%;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.form-submit:hover {
    background-color: #1abc9c;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.modal-content {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}

.modal-title {
    font-size: 22px;
    color: #2c3e50;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #7f8c8d;
}

.results-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.results-table th, .results-table td {
    padding: 12px;
    text-align: right;
    border: 1px solid #ddd;
}

.results-table th {
    background-color: #f2f2f2;
    font-weight: bold;
}

.results-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.results-table tr:hover {
    background-color: #f1f1f1;
}

.grade-input {
    width: 60px;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
}

.save-btn {
    background-color: #27ae60;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .main-container {
        flex-direction: column;
    }
    
    .sidebar {
        width: 100%;
        max-height: 200px;
    }
    
    .admin-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .admin-controls {
        width: 100%;
        justify-content: center;
    }
}
    </style>
</head>
<body>
    <div class="main-container">
        <div class="sidebar">
            <ul class="sidebar-menu">
                <li class="active"><i>📊</i> لوحة التحكم</li>
                <li><i>👨‍🎓</i> الطلاب</li>
                <li><i>👨‍🏫</i> الأساتذة</li>
                <li><i>📝</i> النتائج</li>
                <li><i>📅</i> الجداول الزمنية</li>
                <li><i>🏫</i> الفصول</li>
                <li><i>📚</i> المواد</li>
                <li><i>⚙️</i> الإعدادات</li>
            </ul>
        </div>
        
        <div class="content-area">
            <h2 class="admin-panel-title">لوحة تحكم المدير</h2>
            
            <div class="admin-buttons">
                <a href="student.php"><button class="admin-btn" id="viewResultsBtn" href="student.php">الاطلاع على النتيجة (التلميذ)</button></a>
                 <a href="teacher.php"><button class="admin-btn" id="enterResultsBtn" href="teacher.php">كتابة النتائج (الأستاذ)</button></a>
                
                
            </div>
        </div>
    </div>
    
    <!-- View Results Modal -->
    <div class="modal" id="viewResultsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">الاطلاع على نتائج التلاميذ</h3>
                <button class="modal-close">&times;</button>
            </div>
        </div>
    </div>
</body>
</html>


            
                        