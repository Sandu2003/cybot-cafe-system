<?php
session_start();
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Simple test credentials
    if($username === "admin" && $password === "1234"){
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_orders.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<form method="post">
    <h2>Admin Login</h2>
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="login">Login</button>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</form>
