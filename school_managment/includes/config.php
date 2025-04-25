<?php
session_start();
define('DB_HOST', 'localhost');
define('DB_USER', 'mohamedboukerche');
define('DB_PASS', 'admin12345');
define('DB_NAME', 'school_management');



$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
function getThemeColor($user_id = null) {
    global $conn;
    
    if ($user_id) {
        $stmt = $conn->prepare("SELECT theme_color FROM themes WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $theme = $result->fetch_assoc();
            return $theme['theme_color'];
        }
    }
    
    return 'default'; 
}

function updateTheme($user_id, $theme_color) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO themes (user_id, theme_color) VALUES (?, ?) 
                           ON DUPLICATE KEY UPDATE theme_color = ?");
    $stmt->bind_param("iss", $user_id, $theme_color, $theme_color);
    return $stmt->execute();
}
?>
