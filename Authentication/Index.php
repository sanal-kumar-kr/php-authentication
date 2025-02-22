<link rel="stylesheet" href="style.css">
<?php
session_start();
if (isset($_SESSION['authenticate']) && $_SESSION['authenticate']) {
?>
    <a href="Logout.php">Logout</a>
    <h1>Welcome To the Dashoard</h1>
<?php
} else {
?>
    <a href="Register.php">Register</a>
    <a href="Login.php">Login</a>
    <div>
        <h1>PHP Authentication</h1>
    </div>
<?php
}
?>
