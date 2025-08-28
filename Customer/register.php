<?php
session_start();
include 'db.php'; // DB connection

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $message = "⚠️ All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "⚠️ Invalid email format!";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM customers WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $checkResult = $check->get_result();

        if ($checkResult->num_rows > 0) {
            $message = "⚠️ Email already registered!";
        } else {
            // Hash password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO customers (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashedPassword);

            if ($stmt->execute()) {
                $message = "✅ Registration successful! <a href='login.php'>Login Here</a>";
            } else {
                $message = "⚠️ Error: " . $conn->error;
            }

            $stmt->close();
        }

        $check->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register Page</title>
  <link rel="stylesheet" href="register.css">
</head>
<body>
  <!-- Navbar -->
  <nav>
      <div class="navbar-logo">
          <span>☕ Cybot Cafe</span>
      </div>
  </nav>

  <div class="register-container">
    <h2>Create Account</h2>
    
    <?php if($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
  </div>
</body>
</html>
