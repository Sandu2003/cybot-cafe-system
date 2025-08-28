<?php
session_start();
include '../admin/db.php';

// Get table number safely
$table = intval($_GET['table'] ?? 1);

// Fetch orders for this table using prepared statement for safety
$grandTotal = 0;
$orders = [];

if ($conn) {
    $stmt = $conn->prepare("SELECT food_name, quantity, price FROM orders WHERE table_number = ? ORDER BY order_time ASC");
    if ($stmt) {
        $stmt->bind_param("i", $table);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
                $grandTotal += $row['quantity'] * $row['price'];
            }
        }
        $stmt->close();
    }
    $conn->close();
} else {
    die("Database connection failed!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Slip - Table <?= $table ?></title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; display: flex; justify-content: center; }
.container { display: flex; gap: 50px; flex-wrap: wrap; }
.slip, .payment { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.slip { width: 400px; }
.payment { width: 300px; text-align: center; }
h2 { text-align: center; color: #ff9800; margin-bottom: 15px; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
th { background: #ff9800; color: white; }
.total-row td { font-weight: bold; background: #f0f0f0; }
img.qr { width: 200px; height: 200px; margin-top: 20px; }
@media (max-width: 800px) {
    .container { flex-direction: column; align-items: center; }
}
</style>
</head>
<body>

<div class="container">
    <!-- Order Slip -->
    <div class="slip">
        <h2>🧾 Order Slip</h2>
        <p><strong>Table: <?= $table ?></strong></p>

        <?php if(count($orders) > 0): ?>
        <table>
            <tr>
                <th>Food</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <?php foreach($orders as $order): 
                $itemTotal = $order['quantity'] * $order['price'];
            ?>
            <tr>
                <td><?= htmlspecialchars($order['food_name']) ?></td>
                <td><?= intval($order['quantity']) ?></td>
                <td>Rs.<?= number_format($order['price'], 2) ?></td>
                <td>Rs.<?= number_format($itemTotal, 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="3">Grand Total</td>
                <td>Rs.<?= number_format($grandTotal, 2) ?></td>
            </tr>
        </table>
        <?php else: ?>
            <p style="text-align:center; margin-top: 20px;">No orders found.</p>
        <?php endif; ?>
    </div>

    <!-- Payment QR -->
    <div class="payment">
        <h2>💳 Payment</h2>
        <p>Scan to Pay</p>
        <img class="qr" src="qr_code.png" alt="Payment QR">
    </div>
</div>

</body>
</html>
