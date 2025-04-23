<?php
include 'includes/db.php';
include 'includes/functions.php';
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $studentId = filter_input(INPUT_POST, 'studentId', FILTER_SANITIZE_STRING);
    $note = filter_input(INPUT_POST, 'note', FILTER_SANITIZE_STRING);
    $points = filter_input(INPUT_POST, 'points', FILTER_VALIDATE_INT);
    
    // Validate inputs
    if (!$studentId || !$note || $points === false) {
        $message = 'Please fill in all fields correctly.';
        $messageType = 'error';
    } else {
        if (addNote($studentId, $note, $points, $conn)) {
            $message = 'Note added successfully!';
            $messageType = 'success';
        } else {
            $message = 'Failed to add note.';
            $messageType = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Teacher Dashboard</h1>
        </header>
        
        <main>
            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="note-form">
                <div class="form-group">
                    <label for="studentId">Student ID:</label>
                    <input type="text" id="studentId" name="studentId" required>
                </div>
                
                <div class="form-group">
                    <label for="note">Note:</label>
                    <textarea id="note" name="note" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="points">Points:</label>
                    <input type="number" id="points" name="points" required>
                </div>
                
                <div class="form-group">
                    <button type="submit">Add Note</button>
                </div>
            </form>
        </main>
    </div>
	<style>/* Enhanced Teacher Dashboard Styles */
:root {
  --primary-color: #3498db;
  --secondary-color: #2980b9;
  --success-color: #2ecc71;
  --error-color: #e74c3c;
  --light-gray: #f5f5f5;
  --dark-gray: #333;
  --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  margin: 0;
  padding: 0;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.container {
  width: 90%;
  max-width: 800px;
  background-color: white;
  border-radius: 10px;
  box-shadow: var(--shadow);
  overflow: hidden;
}

header {
  background-color: var(--primary-color);
  padding: 20px;
  text-align: center;
}

h1 {
  color: white;
  margin: 0;
  font-size: 28px;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

main {
  padding: 30px;
}

.message {
  padding: 15px;
  border-radius: 5px;
  margin-bottom: 20px;
  font-weight: 500;
  text-align: center;
  transition: opacity 0.5s ease;
}

.success {
  background-color: rgba(46, 204, 113, 0.2);
  border-left: 4px solid var(--success-color);
  color: #27ae60;
}

.error {
  background-color: rgba(231, 76, 60, 0.2);
  border-left: 4px solid var(--error-color);
  color: #c0392b;
}

.note-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

label {
  font-weight: 600;
  color: var(--dark-gray);
  font-size: 14px;
}

input, textarea {
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-family: inherit;
  font-size: 16px;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

input:focus, textarea:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
}

textarea {
  min-height: 120px;
  resize: vertical;
}

button {
  background-color: var(--primary-color);
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 600;
  font-size: 16px;
  transition: background-color 0.3s ease, transform 0.1s ease;
  width: 100%;
}

button:hover {
  background-color: var(--secondary-color);
}

button:active {
  transform: scale(0.98);
}

@media (max-width: 600px) {
  .container {
    width: 95%;
    border-radius: 0;
  }
  
  main {
    padding: 20px;
  }
		}</style>
    
    <script>
        // Auto-hide message after 5 seconds
        const messageElement = document.querySelector('.message');
        if (messageElement) {
            setTimeout(() => {
                messageElement.style.opacity = '0';
                setTimeout(() => {
                    messageElement.style.display = 'none';
                }, 500);
            }, 5000);
        }
    </script>
</body>
</html>