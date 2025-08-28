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
        cart.push({ name, price: parseFloat(price), quantity: 1 });
    }
    updateCart();
    saveCart();
}

// Update cart
function updateCart() {
    const cartItems = document.getElementById("cartItems");
    const totalPrice = document.getElementById("totalPrice");

    if (!cartItems || !totalPrice) return;

    cartItems.innerHTML = "";
    let total = 0;

    cart.forEach((item, index) => {
        let itemTotal = item.price * item.quantity;
        total += itemTotal;

        const div = document.createElement("div");
        div.className = "cart-item";
        div.innerHTML = `
            <span>${item.name}</span>
            <span>Rs.${itemTotal.toFixed(2)}</span>
            <input type="number" min="1" value="${item.quantity}">
            <button class="remove-btn">Remove</button>
        `;

        // Event for quantity change
        div.querySelector("input").addEventListener("change", (e) => {
            changeQuantity(index, e.target.value);
        });

        // Event for remove
        div.querySelector(".remove-btn").addEventListener("click", () => {
            removeItem(index);
        });

        cartItems.appendChild(div);
    });

    totalPrice.textContent = "Rs." + total.toFixed(2);
}

// Change quantity
function changeQuantity(index, value) {
    let qty = parseInt(value);
    if (isNaN(qty) || qty < 1) qty = 1;
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
    if (cart.length === 0) {
        alert("Cart is empty!");
        return;
    }

    const orderForm = document.getElementById('orderForm');
    if (!orderForm) return;

    const isLoggedIn = orderForm.dataset.loggedin === "1";

    document.getElementById('foodNames').value = cart.map(i => i.name).join(',');
    document.getElementById('quantities').value = cart.map(i => i.quantity).join(',');
    document.getElementById('prices').value = cart.map(i => i.price).join(',');

    if (!isLoggedIn) {
        sessionStorage.setItem('cart', JSON.stringify(cart));
        orderForm.action = "login.php?redirect=menu.php&order=1";
        orderForm.submit();
        return;
    }

    orderForm.action = "sendOrder.php";
    orderForm.submit();

    // clear cart after order
    cart = [];
    updateCart();
    sessionStorage.removeItem('cart');
}

// Pay button
function payNow() {
    if (cart.length === 0) {
        alert("Add items before paying!");
        return;
    }

    document.getElementById('foodNames').value = cart.map(i => i.name).join(',');
    document.getElementById('quantities').value = cart.map(i => i.quantity).join(',');
    document.getElementById('prices').value = cart.map(i => i.price).join(',');

    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
        orderForm.action = "sendOrder.php";
        orderForm.submit();
    }
}

// Search filter (check element exists)
const searchBar = document.getElementById("searchBar");
if (searchBar) {
    searchBar.addEventListener("input", function () {
        const searchText = this.value.toLowerCase();
        document.querySelectorAll(".food-item").forEach(item => {
            const name = item.dataset.name.toLowerCase();
            item.style.display = name.includes(searchText) ? "block" : "none";
        });
    });
}

// Category filter (check element exists)
const categoryFilter = document.getElementById("categoryFilter");
if (categoryFilter) {
    categoryFilter.addEventListener("change", function () {
        const category = this.value;
        document.querySelectorAll(".food-item").forEach(item => {
            item.style.display =
                (category === "all" || item.dataset.category === category) ? "block" : "none";
        });
    });
}
