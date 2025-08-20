<?php
session_start();
include '../admin/db.php';

// Get selected table from URL or session
$tableNumber = isset($_GET['table']) ? intval($_GET['table']) : ($_SESSION['selectedTable'] ?? 1);
$_SESSION['selectedTable'] = $tableNumber;

// Fetch menu items
$menu_items = [];
if ($conn) {
    $result = $conn->query("SELECT * FROM menu_items ORDER BY id ASC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $menu_items[] = $row;
        }
    }
    $conn->close();
} else {
    die("Database connection failed!");
}

// Show messages
$loginMessage = $_SESSION['login_message'] ?? '';
$orderMessage = $_SESSION['order_message'] ?? '';
unset($_SESSION['login_message'], $_SESSION['order_message']);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Cybot Cafe Menu</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="menu.css">
</head>
<body>

<header>
    <h1>☕ Cybot Cafe</h1>
    <nav>
        <a href="welcome.html">Home</a>
        <a href="menu.php">Menu</a>
        <a href="#">Contact</a>
    </nav>
</header>

<?php if($loginMessage): ?>
    <p style="text-align:center; color:green; margin:10px 0;"><?= $loginMessage ?></p>
<?php endif; ?>

<?php if($orderMessage): ?>
    <p style="text-align:center; color:green; margin:10px 0;"><?= $orderMessage ?></p>
<?php endif; ?>

<section class="filters">
    <input type="text" id="searchBar" placeholder="🔍 Search food...">
    <select id="categoryFilter">
        <option value="all">All Categories</option>
        <option value="drinks">Drinks</option>
        <option value="snacks">Snacks</option>
        <option value="meals">Meals</option>
        <option value="desserts">Desserts</option>
    </select>
</section>

<main>
    <div class="menu-section" id="menuList">
        <?php foreach($menu_items as $item): ?>
            <div class="food-item" data-name="<?= htmlspecialchars($item['name']) ?>" data-category="<?= htmlspecialchars($item['category']) ?>" data-price="<?= $item['price'] ?>">
                <?php if($item['image']): ?>
                    <img src="../admin/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="menu-image">
                <?php endif; ?>
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p>Rs.<?= $item['price'] ?></p>
                <button type="button" onclick="addToCart('<?= htmlspecialchars($item['name']) ?>', <?= $item['price'] ?>)">Add to Cart</button>
            </div>
        <?php endforeach; ?>
    </div>

    <aside class="cart-section">
        <h2>🛒 Your Cart</h2>
        <p style="font-weight: bold; font-size: 18px; margin-bottom: 10px;">
            🪑 Table Number: <?= $tableNumber ?>
        </p>

        <form action="sendOrder.php" method="post" id="orderForm" data-loggedin="<?= isset($_SESSION['customer_id']) ? 1 : 0 ?>">
            <input type="hidden" name="table" value="<?= $tableNumber ?>">
            <input type="hidden" name="food_name" id="foodNames">
            <input type="hidden" name="quantity" id="quantities">
            <input type="hidden" name="price" id="prices">

            <div id="cartItems"></div>
            <p class="total">Total: Rs.<span id="totalPrice">0</span></p>

            <div class="cart-actions">
                <button type="button" class="send" onclick="sendOrder()">Send Order</button>
                <button type="button" class="pay" onclick="payNow()">Pay</button>
            </div>
        </form>
    </aside>
</main>

<script src="menu.js"></script>
</body>
</html>
