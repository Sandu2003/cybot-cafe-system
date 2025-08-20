let cart = [];

// Load cart from sessionStorage on page load
window.addEventListener('load', () => {
    const savedCart = sessionStorage.getItem('cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
        updateCart();
    }
});

// Save cart to sessionStorage
function saveCart() {
    sessionStorage.setItem('cart', JSON.stringify(cart));
}

// Add item
function addToCart(name, price) {
    let existing = cart.find(item => item.name === name);
    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({ name, price, quantity: 1 });
    }
    updateCart();
    saveCart();
}

// Update cart
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
    saveCart();
}

// Remove item
function removeItem(index) {
    cart.splice(index, 1);
    updateCart();
    saveCart();
}

// Send order
function sendOrder() {
    if(cart.length === 0) {
        alert("Cart is empty!");
        return;
    }

    const orderForm = document.getElementById('orderForm');
    const isLoggedIn = orderForm.dataset.loggedin === "1";

    document.getElementById('foodNames').value = cart.map(i => i.name).join(',');
    document.getElementById('quantities').value = cart.map(i => i.quantity).join(',');
    document.getElementById('prices').value = cart.map(i => i.price).join(',');

    if(!isLoggedIn) {
        sessionStorage.setItem('cart', JSON.stringify(cart));
        orderForm.action = "login.php?redirect=menu.php&order=1";
        orderForm.submit();
        return;
    }

    orderForm.action = "sendOrder.php";
    orderForm.submit();
    alert("✅ Order sent successfully!");
    cart = [];
    updateCart();
    sessionStorage.removeItem('cart');
}

// Pay button
function payNow() {
    if(cart.length === 0) {
        alert("Add items before paying!");
        return;
    }
    document.getElementById('foodNames').value = cart.map(i => i.name).join(',');
    document.getElementById('quantities').value = cart.map(i => i.quantity).join(',');
    document.getElementById('prices').value = cart.map(i => i.price).join(',');

    const orderForm = document.getElementById('orderForm');
    orderForm.action = "sendOrder.php";
    orderForm.submit();
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
