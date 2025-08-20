<?php
session_start();
include '../admin/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash password

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM customers WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
        $error = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO customers (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if($stmt->execute()){
            $_SESSION['customer_id'] = $stmt->insert_id;
            $_SESSION['customer_name'] = $name;
            header("Location: menu.php"); // redirect after register
            exit;
        } else {
            $error = "Registration failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Register - Cybot Cafe</title>
</head>
<body>
<h2>Customer Registration</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    <input type="text" name="name" placeholder="Full Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Register</button>
</form>
<p>Already registered? <a href="login.php">Login here</a></p>
</body>
</html>
