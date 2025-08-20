<?php
include 'db.php'; // Database connection

// Directory for uploaded images
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle Add Item
if(isset($_POST['add_item'])){
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];

    $image_name = null;

    if ($_FILES['image']['name']) {
        $image_name = time() . "_" . preg_replace("/[^A-Za-z0-9.\-]/", "_", $_FILES['image']['name']);
        $temp_name = $_FILES['image']['tmp_name'];
        if (!move_uploaded_file($temp_name, $uploadDir . $image_name)) {
            echo "<p style='color:red;'>Image upload failed!</p>";
        }
    }

    $stmt = $conn->prepare("INSERT INTO menu_items (name, category, price, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $name, $category, $price, $image_name);
    $stmt->execute();
    $stmt->close();
}

// Handle Delete Item
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    // Optional: Delete the image file
    $result = $conn->query("SELECT image FROM menu_items WHERE id = $id");
    $row = $result->fetch_assoc();
    if($row && $row['image'] && file_exists($uploadDir . $row['image'])){
        unlink($uploadDir . $row['image']);
    }

    $conn->query("DELETE FROM menu_items WHERE id = $id");
}

// Get all menu items
$menu_items = [];
$result = $conn->query("SELECT * FROM menu_items ORDER BY id ASC");
while($row = $result->fetch_assoc()){
    $menu_items[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Admin - Menu Management</title>
<style>
body{font-family: Arial; background:#f8f8f8; padding:20px;}
h1{color:#3e2723;}
form{background:white; padding:15px; border-radius:10px; margin-bottom:20px;}
form input, form select{padding:8px; margin:5px; border-radius:5px; border:1px solid #ccc;}
form button{padding:8px 12px; border:none; border-radius:5px; background:#6d4c41; color:white; cursor:pointer;}
form button:hover{background:#4e342e;}
table{width:100%; border-collapse: collapse; margin-top:20px;}
table, th, td{border:1px solid #ddd;}
th, td{padding:10px; text-align:center;}
a.delete{color:#e53935; text-decoration:none; font-weight:bold;}
a.delete:hover{opacity:0.7;}
img{width:80px; border-radius:5px;}
</style>
</head>
<body>

<h1>Admin - Manage Menu</h1>

<h2>Add New Food Item</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Food Name" required>
    <select name="category" required>
        <option value="">Select Category</option>
        <option value="drinks">Drinks</option>
        <option value="snacks">Snacks</option>
        <option value="meals">Meals</option>
        <option value="desserts">Desserts</option>
    </select>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="file" name="image" accept="image/*">
    <button type="submit" name="add_item">Add Item</button>
</form>

<h2>Existing Menu Items</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Category</th>
        <th>Price</th>
        <th>Image</th>
        <th>Action</th>
    </tr>
    <?php foreach($menu_items as $item): ?>
    <tr>
        <td><?= $item['id'] ?></td>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= htmlspecialchars($item['category']) ?></td>
        <td>Rs.<?= number_format($item['price'],2) ?></td>
        <td>
            <?php if($item['image']): ?>
            <img src="uploads/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
            <?php endif; ?>
        </td>
        <td><a href="?delete=<?= $item['id'] ?>" class="delete" onclick="return confirm('Are you sure?')">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
