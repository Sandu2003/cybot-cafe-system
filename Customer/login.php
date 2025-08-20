<?php
session_start();
include '../admin/db.php';

$redirect = $_GET['redirect'] ?? 'menu.php';
$orderFlag = isset($_GET['order']) ? 1 : 0;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if(empty($email) || empty($password)){
        $error = "Please enter both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM customers WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows==1){
            $row = $result->fetch_assoc();
            if(password_verify($password,$row['password'])){
                $_SESSION['customer_id']=$row['id'];
                $_SESSION['customer_name']=$row['name'];
                $_SESSION['login_message']="✅ Successfully logged in!";
                if($orderFlag) $_SESSION['order_message']="✅ Order added successfully!";
                header("Location:$redirect");
                exit;
            } else {
                $error="Invalid password!";
            }
        } else {
            $error="No account found with this email!";
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<title>Customer Login</title>
<link rel="stylesheet" href="login.css">
</head>
<body>
<nav>
    <div class="navbar-logo">☕ Cybot Cafe</div>
</nav>
<div class="login-container">
<h2>Customer Login</h2>
<?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
<form method="POST" action="">
<label>Email</label>
<input type="text" name="email" required>
<label>Password</label>
<input type="password" name="password" required>
<button type="submit">Login</button>
</form>
<p>Don’t have an account? <a href="register.php">Register</a></p>
</div>
</body>
</html>
