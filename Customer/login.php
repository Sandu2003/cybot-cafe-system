<?php
session_start();
include '../admin/db.php';

// Sanitize redirect to prevent header injection
$redirect = isset($_GET['redirect']) ? basename($_GET['redirect']) : 'menu.php';
$orderFlag = isset($_GET['order']) ? 1 : 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password FROM customers WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['customer_id'] = $row['id'];
                    $_SESSION['customer_name'] = $row['name'];
                    $_SESSION['login_message'] = "✅ Successfully logged in!";
                    if ($orderFlag) {
                        $_SESSION['order_message'] = "✅ Order added successfully!";
                    }
                    header("Location: $redirect");
                    exit;
                } else {
                    $error = "Invalid password!";
                }
            } else {
                $error = "No account found with this email!";
            }
            $stmt->close();
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<nav>
    <div class="navbar-logo">☕ Cybot Cafe</div>
</nav>
<div class="login-container">
    <h2>Customer Login</h2>
    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Email</label>
        <input type="email" name="email" required>
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <button type="submit">Login</button>
    </form>
    <p>Don’t have an account? <a href="register.php">Register</a></p>
</div>
</body>
</html>
