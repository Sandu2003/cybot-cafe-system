<?php
session_start();
include '../admin/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $table = intval($_POST['table'] ?? 1);
    $food_names = array_filter(array_map('trim', explode(',', $_POST['food_name'] ?? '')));
    $quantities = array_map('intval', explode(',', $_POST['quantity'] ?? ''));
    $prices = array_map('floatval', explode(',', $_POST['price'] ?? ''));

    // Basic validation
    if (empty($food_names) || empty($quantities) || empty($prices) || 
        count($food_names) !== count($quantities) || count($food_names) !== count($prices)) {
        die("⚠️ Order data is missing or invalid!");
    }

    // Prepare statement once
    $stmt = $conn->prepare("INSERT INTO orders (table_number, food_name, quantity, price) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    // Insert each item
    for ($i = 0; $i < count($food_names); $i++) {
        $food = $food_names[$i];
        $qty = $quantities[$i] ?? 1;
        $price = $prices[$i] ?? 0.0;

        $stmt->bind_param("isis", $table, $food, $qty, $price);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    // Redirect to order slip page with table number
    header("Location: order_slip.php?table=$table");
    exit;

} else {
    die("⚠️ Invalid request method!");
}
