<?php
session_start();
include '../admin/db.php'; // Your database connection

// Only process login if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Use isset() to avoid undefined index warnings
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password!";
    } else {
        // Check credentials in database
        $stmt = $conn->prepare("SELECT id, name, password FROM customers WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                // Successful login
                $_SESSION['customer_id'] = $id;
                $_SESSION['customer_name'] = $name;

                // Redirect back to menu page with a success message
                header("Location: menu.php?login=success");
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "No account found with this email!";
        }

        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Customer Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="login.php">
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
