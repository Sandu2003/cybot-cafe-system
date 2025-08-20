let cart = [];

// Add item to cart
function addToCart(name, price) {
    let existing = cart.find(item => item.name === name);
    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({ name, price, quantity: 1 });
    }
    updateCart();
}

// Update cart display
function updateCart() {
    const cartItems = document.getElementById("cartItems");
    const totalPrice = document.getElementById("totalPrice");
    cartItems.innerHTML = "";
    let total = 0;

    cart.forEach((item, index) => {
        let itemTotal = item.price * item.quantity;
        total += itemTotal;

        cartItems.innerHTML += `
            <div class="cart-item">
                <span>${item.name}</span>
                <span>Rs.${itemTotal}</span>
                <input type="number" min="1" value="${item.quantity}" onchange="changeQuantity(${index}, this.value)">
                <button class="remove-btn" onclick="removeItem(${index})">Remove</button>
            </div>
        `;
    });

    totalPrice.textContent = total;
}

// Change quantity
function changeQuantity(index, value) {
    let qty = parseInt(value);
    if (qty < 1) qty = 1;
    cart[index].quantity = qty;
    updateCart();
}

// Remove item
function removeItem(index) {
    cart.splice(index, 1);
    updateCart();
}

// Send Order button
function sendOrder() {
    if(cart.length === 0) {
        alert("Cart is empty!");
        return;
    }

    // Save cart data in hidden inputs
    document.getElementById('foodNames').value = cart.map(item => item.name).join(',');
    document.getElementById('quantities').value = cart.map(item => item.quantity).join(',');
    document.getElementById('prices').value = cart.map(item => item.price).join(',');

    const orderForm = document.getElementById('orderForm');

    // Check if customer is logged in via session
    const isLoggedIn = document.getElementById('orderForm').dataset.loggedin === "1";

    if (!isLoggedIn) {
        // Redirect to login page if not logged in
        orderForm.action = "login.php";
        orderForm.submit();
    } else {
        // Already logged in → save order directly via sendOrder.php
        orderForm.action = "sendOrder.php";
        orderForm.submit();

        // Show success message
        alert("✅ Order sent successfully!");

        // Clear cart after sending
        cart = [];
        updateCart();
    }
}

// Pay button: redirect to order_slip.php
function payNow() {
    if(cart.length === 0) {
        alert("Add items before paying!");
        return;
    }

    document.getElementById('foodNames').value = cart.map(item => item.name).join(',');
    document.getElementById('quantities').value = cart.map(item => item.quantity).join(',');
    document.getElementById('prices').value = cart.map(item => item.price).join(',');

    const orderForm = document.getElementById('orderForm');
    orderForm.action = "sendOrder.php"; 
    orderForm.submit();

    // Redirect to order slip after short delay
    setTimeout(() => {
        const tableNumber = document.querySelector('input[name="table"]').value;
        window.location.href = "order_slip.php?table=" + tableNumber;
    }, 200);
}

// Search filter
document.getElementById("searchBar").addEventListener("input", function() {
    const searchText = this.value.toLowerCase();
    document.querySelectorAll(".food-item").forEach(item => {
        const name = item.dataset.name.toLowerCase();
        item.style.display = name.includes(searchText) ? "block" : "none";
    });
});

// Category filter
document.getElementById("categoryFilter").addEventListener("change", function() {
    const category = this.value;
    document.querySelectorAll(".food-item").forEach(item => {
        item.style.display = (category === "all" || item.dataset.category === category) ? "block" : "none";
    });
});
