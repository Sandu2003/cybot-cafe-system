<?php
session_start();
include 'db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $table = intval($_POST['table'] ?? 1);
    $message = trim($_POST['message'] ?? '');
    if(empty($message)){
        $_SESSION['contact_message'] = "❌ Message cannot be empty!";
    } else {
        $stmt = $conn->prepare("INSERT INTO kitchen_notifications (table_number, message) VALUES (?, ?)");
        $stmt->bind_param("is", $table, $message);
        $stmt->execute();
        $stmt->close();
        $_SESSION['contact_message'] = "✅ Message sent to kitchen!";
    }
    $conn->close();
    header("Location: menu.php?table=$table");
    exit;
}
