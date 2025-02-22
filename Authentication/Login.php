<?php
include('conn.php');
ini_set('session.use_only_cookies', 1); 
ini_set('session.cookie_httponly', 1);  
ini_set('session.cookie_secure', 1);  
session_start();
 
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 900)) { 
    session_unset();
    session_destroy();
    header("Location: Login.php?error=session_expired");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); 
$message = "";
$flag=true;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    if (empty($password)) {
        $message = "Please provide the password!";
        $flag=false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
        $flag=false;
    } 
    if($flag){
        $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $db_password);
            $stmt->fetch();
            if (password_verify($password, $db_password)) {
                session_regenerate_id(true); 
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;
                $_SESSION['authenticate'] = true;
                $_SESSION['LAST_ACTIVITY'] = time(); 
                header("Location: index.php");
                exit();
            } else {
                $message = "Incorrect password!";
            }
        } else {
            $message = "Email not found!";
        }
        $stmt->close();    
    }
}
$conn->close();
?>
<link rel="stylesheet" href="style.css">
<a href="Register.php">Register</a>
<a href="index.php">Home</a>
<h1>Login Here</h1>
<div class="form-wrapper">
    <form method="post">
        <input type="email" name="email" required placeholder="Email">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit">Login</button>
    </form>

</div>
<p><?php echo $message; ?></p>
