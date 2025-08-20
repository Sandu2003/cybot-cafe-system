<?php
include '../admin/db.php'; // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        echo "All fields are required!";
        exit;
    }

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM customers WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        echo "Email already registered!";
        exit;
    }

    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $sql = "INSERT INTO customers (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "✅ Registration successful! <a href='login.php'>Login Here</a>";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
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
    <form method="POST" action="register.php">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
  </div>
</body>
</html>
