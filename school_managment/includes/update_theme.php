<?php
require_once 'config.php';
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['theme_color']) && isLoggedIn()) {
    $theme_color = sanitizeInput($_POST['theme_color']);
    $user_id = $_SESSION['user_id'];
    
    if (updateTheme($user_id, $theme_color)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update theme']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>