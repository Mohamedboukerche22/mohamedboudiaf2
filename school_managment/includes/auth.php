<?php
require_once 'config.php';
require_once 'functions.php';
if (!isLoggedIn() && basename($_SERVER['PHP_SELF']) != 'login.php' && basename($_SERVER['PHP_SELF']) != 'register.php') {
    redirect('login.php');
}
if (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false && !isAdmin()) {
    redirect('../index.php');
}
?>
