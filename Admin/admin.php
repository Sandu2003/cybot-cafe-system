<?php
session_start();
include 'db.php'; // DB connection

$orders = [];
$result = $conn->query("SELECT * FROM orders ORDER BY order_time DESC");
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin - Orders</title>
<style>
table{width:90%;margin:auto;border-collapse:collapse;}
th, td{border:1px solid #ddd;padding:10px;text-align:center;}
th{background:#333;color:white;}
</style>
</head>
<body>
<h2 style="text-align:center;">All Orders</h2>
<table>
<tr>
    <th>Order ID</th>
    <th>Table Number</th>
    <th>Food Item</th>
    <th>Quantity</th>
    <th>Price</th>
    <th>Order Time</th>
</tr>
<?php foreach($orders as $order): ?>
<tr>
    <td><?= $order['order_id'] ?></td>
    <td><?= $order['table_number'] ?></td>
    <td><?= htmlspecialchars($order['food_name']) ?></td>
    <td><?= $order['quantity'] ?></td>
    <td>Rs.<?= $order['price'] ?></td>
    <td><?= $order['order_time'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
