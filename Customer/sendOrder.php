<?php
session_start();
include '../admin/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $table = intval($_POST['table'] ?? 1);
    $food_names = explode(',', $_POST['food_name'] ?? '');
    $quantities = explode(',', $_POST['quantity'] ?? '');
    $prices = explode(',', $_POST['price'] ?? '');

    if (empty($food_names) || empty($quantities) || empty($prices)) {
        die("Order data is missing!");
    }

    $stmt = $conn->prepare("INSERT INTO orders (table_number, food_name, quantity, price) VALUES (?, ?, ?, ?)");
    $lastOrderId = 0;

    for ($i = 0; $i < count($food_names); $i++) {
        $food = trim($food_names[$i]);
        $qty = intval($quantities[$i] ?? 1);
        $price = floatval($prices[$i] ?? 0);

        $stmt->bind_param("isis", $table, $food, $qty, $price);
        $stmt->execute();
        $lastOrderId = $conn->insert_id; // store last inserted ID
    }

    $stmt->close();
    $conn->close();

    // Redirect to order slip page with table number
    header("Location: order_slip.php?table=$table");
    exit;

} else {
    die("Invalid request!");
}
