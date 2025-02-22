<?php
session_start();
if (!isset($_SESSION['authenticate']) || $_SESSION['authenticate'] !== true) {
    header("Location: Login.php?error=unauthorized_access");
    exit();
}
$_SESSION = [];
session_unset();
session_destroy();
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, "/");
}
header("Location: index.php?message=logged_out");
exit();
?>
