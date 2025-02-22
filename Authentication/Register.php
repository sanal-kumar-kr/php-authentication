<?php
include('conn.php');
$message='';
$flag=true;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
   
    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,20}$/", $password)) {
         $message="Password must be 6-20 chars long, include at least 1 number & 1 special character.";
         $flag=false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message= "Invalid email format!";
        $flag=false;
    }
    if (!preg_match("/^[a-zA-Z0-9]{3,20}$/", $username)) {
        $message="Invalid username! Only letters and numbers allowed (3-20 chars)";
        $flag=false;
      }
      
    if($flag){
        $isUniqueEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $isUniqueEmail->bind_param("s", $email);
        $isUniqueEmail->execute();
        $isUniqueEmail->store_result();
        if ($isUniqueEmail->num_rows > 0) {
            $message = "Email ID already exists";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $hashed_password=password_hash($password,PASSWORD_BCRYPT);
            $stmt->bind_param("sss", $username, $email,$hashed_password);
            if ($stmt->execute()) {
                $message = "Account created successfully";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
        $isUniqueEmail->close();
        $conn->close();
    }
}
?>
<link rel="stylesheet" href="style.css">
<a href="Login.php">Login</a>
<a href="index.php">Home</a>


<div class="form-wrapper">
    <form method="post">
        <label for="">Username</label>
        <input type="text" name="username"  placeholder="Username">
        <label for="">email</label>
        <input type="email" name="email"  placeholder="Email">
        <label for="">password</label>
        <input type="password" name="password"  placeholder="Password">
        <button type="submit">Register</button>
    </form>
</div>

<p><?php echo $message; ?></p>