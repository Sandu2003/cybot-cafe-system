<?php
include 'db.php';

$result = $conn->query("SELECT * FROM orders ORDER BY order_time DESC, table_number ASC");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin Orders - Cybot Cafe</title>
<style>
body { font-family: Arial; background: #f5f5f5; padding: 20px; }
h1 { text-align: center; color: #333; }
.order-slip { background: #fff; padding: 15px 20px; margin-bottom: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.order-slip h3 { margin: 0 0 10px 0; color: #ff9800; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
th { background: #ff9800; color: white; }
.total-row td { font-weight: bold; background: #f0f0f0; }
</style>
</head>
<body>

<h1>🧾 Orders - Cybot Cafe</h1>

<?php 
$currentTable = 0;
$grandTotal = 0;
if ($result && $result->num_rows > 0):
    while($order = $result->fetch_assoc()):
        if ($currentTable != $order['table_number']):
            if ($currentTable != 0):
                // Show previous table grand total
                echo "<tr class='total-row'><td colspan='3'>Grand Total</td><td>{$grandTotal}</td></tr></table></div>";
            endif;
            $currentTable = $order['table_number'];
            $grandTotal = 0;
            echo "<div class='order-slip'>";
            echo "<h3>Table: {$currentTable} | Time: {$order['order_time']}</h3>";
            echo "<table><tr><th>Food Item</th><th>Quantity</th><th>Price (Rs.)</th><th>Total (Rs.)</th></tr>";
        endif;

        $itemTotal = $order['quantity'] * $order['price'];
        $grandTotal += $itemTotal;
        echo "<tr>
                <td>{$order['food_name']}</td>
                <td>{$order['quantity']}</td>
                <td>{$order['price']}</td>
                <td>{$itemTotal}</td>
              </tr>";
    endwhile;
    // Show last table total
    echo "<tr class='total-row'><td colspan='3'>Grand Total</td><td>{$grandTotal}</td></tr></table></div>";
else:
    echo "<p style='text-align:center;'>No orders yet.</p>";
endif;
?>

</body>
</html>
