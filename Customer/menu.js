let cart = [];

window.addEventListener('load', () => {
    const saved = sessionStorage.getItem('cart');
    if(saved){ cart = JSON.parse(saved); updateCart(); }
});

function saveCart(){ sessionStorage.setItem('cart', JSON.stringify(cart)); }

function addToCart(name, price){
    let existing = cart.find(i => i.name===name);
    if(existing) existing.quantity++;
    else cart.push({name, price, quantity:1});
    updateCart(); saveCart();
}

function updateCart(){
    const cartItems = document.getElementById("cartItems");
    const totalPrice = document.getElementById("totalPrice");
    cartItems.innerHTML=""; let total=0;
    cart.forEach((item,index)=>{
        let itemTotal = item.price * item.quantity;
        total += itemTotal;
        cartItems.innerHTML += `<div class="cart-item">
            <span>${item.name}</span>
            <span>Rs.${itemTotal}</span>
            <input type="number" min="1" value="${item.quantity}" onchange="changeQuantity(${index},this.value)">
            <button class="remove-btn" onclick="removeItem(${index})">Remove</button>
        </div>`;
    });
    totalPrice.textContent = total.toFixed(2);
}

function changeQuantity(i,val){ let qty = parseInt(val); if(qty<1) qty=1; cart[i].quantity=qty; updateCart(); saveCart(); }
function removeItem(i){ cart.splice(i,1); updateCart(); saveCart(); }

function sendOrder(){
    if(cart.length===0){ alert("Cart empty!"); return; }
    const f = document.getElementById('orderForm');
    f.food_name.value = cart.map(i=>i.name).join(',');
    f.quantity.value = cart.map(i=>i.quantity).join(',');
    f.price.value = cart.map(i=>i.price).join(',');
    if(f.dataset.loggedin==="0"){ sessionStorage.setItem('cart',JSON.stringify(cart)); f.action="login.php?redirect=menu.php&order=1"; f.submit(); return; }
    f.action="sendOrder.php"; f.submit();
    alert("✅ Order sent!"); cart=[]; updateCart(); sessionStorage.removeItem('cart');
}

function payNow(){ sendOrder(); }

document.getElementById("searchBar").addEventListener("input", function(){
    const val = this.value.toLowerCase();
    document.querySelectorAll(".food-item").forEach(item=>{
        item.style.display = item.dataset.name.toLowerCase().includes(val)?"block":"none";
    });
});

document.getElementById("categoryFilter").addEventListener("change", function(){
    const cat = this.value;
    document.querySelectorAll(".food-item").forEach(item=>{
        item.style.display = (cat==="all" || item.dataset.category===cat)?"block":"none";
    });
});
