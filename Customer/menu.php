<?php
session_start();
include 'db.php'; // DB connection  
      
// ===== Table selection =====
$tableNumber = isset($_GET['table']) ? intval($_GET['table']) : ($_SESSION['selectedTable'] ?? 1);
$_SESSION['selectedTable'] = $tableNumber;

// ===== Fetch menu items =====
$menu_items = [];
$result = $conn->query("SELECT id, name, category, price, image FROM menu_items ORDER BY id ASC");
if($result){
    while($row = $result->fetch_assoc()){
        $menu_items[] = $row;
    }
}
if(empty($menu_items)){
    // Dummy items if no records
    $menu_items = [
        ['name'=>'Cappuccino','category'=>'drinks','price'=>350,'image'=>''],
        ['name'=>'Chocolate Cake','category'=>'desserts','price'=>400,'image'=>''],
        ['name'=>'Cheese Sandwich','category'=>'snacks','price'=>250,'image'=>''],
        ['name'=>'Grilled Chicken','category'=>'meals','price'=>700,'image'=>'']
    ];
}
$conn->close();

// ===== Show messages =====
$loginMessage = $_SESSION['login_message'] ?? '';
$orderMessage = $_SESSION['order_message'] ?? '';
$contactMessage = $_SESSION['contact_message'] ?? '';
unset($_SESSION['login_message'], $_SESSION['order_message'], $_SESSION['contact_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cybot Cafe Menu</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- ===== Link External CSS ===== -->
<link rel="stylesheet" href="menu.css">
</head>
<body>

<header>
    <h1>☕ Cybot Cafe</h1>
    <nav>
        <a href="../index.html">Home</a>
        <a href="menu.php">Menu</a>
        <a href="#contact-kitchen">Contact</a>
    </nav>
</header>

<?php if($loginMessage): ?><p class="message"><?= htmlspecialchars($loginMessage) ?></p><?php endif; ?>
<?php if($orderMessage): ?><p class="message"><?= htmlspecialchars($orderMessage) ?></p><?php endif; ?>
<?php if($contactMessage): ?><p class="message"><?= htmlspecialchars($contactMessage) ?></p><?php endif; ?>

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
            <div class="food-item" data-name="<?= htmlspecialchars($item['name']) ?>" data-category="<?= htmlspecialchars($item['category']) ?>" data-price="<?= htmlspecialchars($item['price']) ?>">
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p>Rs.<?= number_format($item['price'], 2) ?></p>
                <button type="button" onclick="addToCart('<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>', <?= $item['price'] ?>)">Add to Cart</button>
            </div>
        <?php endforeach; ?>
    </div>

    <aside class="cart-section">
        <h2>🛒 Your Cart</h2>
        <p style="font-weight:bold;">🪑 Table Number: <?= $tableNumber ?></p>
        <form action="sendOrder.php" method="post" id="orderForm" data-loggedin="<?= isset($_SESSION['customer_id']) ? 1 : 0 ?>">
            <input type="hidden" name="table" value="<?= $tableNumber ?>">
            <input type="hidden" name="food_name" id="foodNames">
            <input type="hidden" name="quantity" id="quantities">
            <input type="hidden" name="price" id="prices">
            <div id="cartItems"></div>
            <p class="total">Total: Rs.<span id="totalPrice">0.00</span></p>
            <div class="cart-actions">
                <button type="button" class="send" onclick="sendOrder()">Send Order</button>
                <button type="button" class="pay" onclick="payNow()">Pay</button>
            </div>
        </form>
    </aside>
</main>

<section class="contact-kitchen">
    <h2>📩 Contact Kitchen</h2>
    <p>Have a special request or message for the kitchen? Let them know!</p>
    <form id="contactForm" method="POST" action="contact_kitchen.php">
        <input type="hidden" name="table" value="<?= $tableNumber ?>">
        <textarea name="message" placeholder="Write your message here..." required></textarea>
        <button type="submit">Send Message</button>
    </form>
</section>

<!-- ===== Link External JS ===== -->
<script src="menu.js"></script>
</body>
</html>
