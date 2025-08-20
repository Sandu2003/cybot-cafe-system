<?php
session_start();
include '../admin/db.php';

$table = intval($_GET['table'] ?? 1);

// Fetch orders for this table
$result = $conn->query("SELECT * FROM orders WHERE table_number = $table ORDER BY order_time ASC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Order Slip - Table <?= $table ?></title>
<style>
body { font-family: Arial; background: #f5f5f5; padding: 20px; display: flex; justify-content: center; }
.container { display: flex; gap: 50px; }
.slip, .payment { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.slip { width: 400px; }
.payment { width: 300px; text-align: center; }
h2 { text-align: center; color: #ff9800; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
th { background: #ff9800; color: white; }
.total-row td { font-weight: bold; background: #f0f0f0; }
img.qr { width: 200px; height: 200px; margin-top: 20px; }
</style>
</head>
<body>

<div class="container">
    <!-- Order Slip -->
    <div class="slip">
        <h2>🧾 Order Slip</h2>
        <p><strong>Table: <?= $table ?></strong></p>
        <?php 
        $grandTotal = 0;
        if ($result && $result->num_rows > 0):
        ?>
        <table>
            <tr>
                <th>Food</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <?php while($order = $result->fetch_assoc()):
                $itemTotal = $order['quantity'] * $order['price'];
                $grandTotal += $itemTotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($order['food_name']) ?></td>
                <td><?= $order['quantity'] ?></td>
                <td><?= $order['price'] ?></td>
                <td><?= $itemTotal ?></td>
            </tr>
            <?php endwhile; ?>
            <tr class="total-row">
                <td colspan="3">Grand Total</td>
                <td><?= $grandTotal ?></td>
            </tr>
        </table>
        <?php else: ?>
            <p>No orders found.</p>
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
