<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitizeInput($data) {
    global $conn;
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($data)));
}

function getNews($limit = null) {
    global $conn;
    
    $sql = "SELECT news.*, users.full_name as author_name 
            FROM news 
            JOIN users ON news.author_id = users.id 
            ORDER BY news.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT $limit";
    }
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getSingleNews($id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT news.*, users.full_name as author_name 
                           FROM news 
                           JOIN users ON news.author_id = users.id 
                           WHERE news.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}
?>