const swiper = new Swiper(".swiper", {
    autoplay: {
        depaly: 5000,
        disableOnInteraction: false,
    },
    grapCursor: true,
    loop: true,

    pagination: {
        el: ".swiper-pagination",
        clickable:true,
    },

    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
})

function closeShowPopup() {
    var element = document.getElementById("popup-ads");
    element.style.display = "none";
}

document.getElementById("btn-phone").onclick = function () {
    document.querySelectorAll('.product-phone').forEach(function(el) {
        el.style.display = 'block';
     });
    document.querySelectorAll('.product-tv').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-washing').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-fridge').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-accessory').forEach(function(el) {
        el.style.display = 'none';
     });
};
document.getElementById("btn-tv").onclick = function () {
    document.querySelectorAll('.product-phone').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-tv').forEach(function(el) {
        el.style.display = 'block';
     });
    document.querySelectorAll('.product-washing').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-fridge').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-accessory').forEach(function(el) {
        el.style.display = 'none';
     });
};
document.getElementById("btn-washing").onclick = function () {
    document.querySelectorAll('.product-phone').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-tv').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-washing').forEach(function(el) {
        el.style.display = 'block';
     });
    document.querySelectorAll('.product-fridge').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-accessory').forEach(function(el) {
        el.style.display = 'none';
     });
};
document.getElementById("btn-fridge").onclick = function () {
    document.querySelectorAll('.product-phone').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-tv').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-washing').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-fridge').forEach(function(el) {
        el.style.display = 'block';
     });
    document.querySelectorAll('.product-accessory').forEach(function(el) {
        el.style.display = 'none';
     });
};
document.getElementById("btn-accessory").onclick = function () {
    document.querySelectorAll('.product-phone').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-tv').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-washing').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-fridge').forEach(function(el) {
        el.style.display = 'none';
     });
    document.querySelectorAll('.product-accessory').forEach(function(el) {
        el.style.display = 'block';
     });
};

var toggleButton = document.getElementById('show_cart');
var element1 = document.getElementsByClassName('home-page');
var element2 = document.getElementsByClassName('show-cart');

toggleButton.addEventListener('click', function() {
  if (element1.style.display === 'block') {
    element1.style.display = 'none';
    element2.style.display = 'block';
  } else {
    element1.style.display = 'block';
    element2.style.display = 'none';
  }
});

// Sample JavaScript code to manage cart items (replace with your actual cart data and logic)

let cartItems = []; // Array to store cart items

// Add event listener to all "Add to Cart" buttons
let addToCartBtns = document.querySelectorAll('.btn-add-to-cart');
addToCartBtns.forEach(btn => {
  btn.addEventListener('click', function() {
    let productId = parseInt(this.dataset.productId);
    let productName = this.dataset.productName;
    let productPrice = this.dataset.productPrice.replace(/,/g, '');
    let quantity = 1; // Default quantity

    addToCart(productId, productName, productPrice, quantity);
  });
});

// Function to add an item to the cart
function addToCart(productId, productName, price, quantity) {
  // Check if item already exists in cart
  let existingItem = cartItems.find(item => item.id === productId && item.name === productName);

  if (existingItem) {
    // Update quantity of existing item
    existingItem.quantity += quantity;
  } else {
    // Add new item to cart
    cartItems.push({
      id: productId,
      name: productName,
      price: price,
      quantity: quantity
    });
  }

  // Update cart display
  updateCartDisplay();
}


// Function to update cart display on the page
function updateCartDisplay() {
  // Clear existing cart items from the table
  let cartTable = document.querySelector('.cart-table tbody');
  cartTable.innerHTML = '';

  // Calculate and display total
  let cartTotal = 0;
  cartItems.forEach(item => { 
    let parsedPrice = parseFloat(item.price); 
    let subtotal = parsedPrice * item.quantity;
    cartTotal += subtotal;

    // Create table row for each cart item
    let row = cartTable.insertRow();
    let cellProduct = row.insertCell();
    let cellPrice = row.insertCell();
    let cellQuantity = row.insertCell();
    let cellSubtotal = row.insertCell();
    let cellRemove = row.insertCell();

    cellProduct.textContent = item.name;
    cellPrice.textContent = `${item.price}₫`;

    // Quantity input field and buttons
    let quantityInput = document.createElement('input');
    quantityInput.type = 'number';
    quantityInput.min = 1;
    quantityInput.value = item.quantity;
    quantityInput.classList.add('quantity'); // Add 'quantity' class
    quantityInput.addEventListener('change', function() {
      updateQuantity(item.id, parseInt(this.value));
    });
    cellQuantity.appendChild(quantityInput);

    // Update subtotal and total
    cellSubtotal.textContent = `${subtotal.toFixed(6)}₫`;
    // cellSubtotal.textContent = productPrice(subtotal);

    // Remove button
    cellRemove.innerHTML = '<a href="#" onclick="removeFromCart(' + item.id + ')">Remove</a>';
  });

  document.getElementById('cart-total').textContent = `${cartTotal.toFixed(6)}₫ `;
}

// Function to update quantity of an item
function updateQuantity(productId, newQuantity) {
  // Find the item in the cart
  let itemIndex = cartItems.findIndex(item => item.id === productId);
  if (itemIndex !== -1) {
    cartItems[itemIndex].quantity = newQuantity;

    // Update cart display and total
    updateCartDisplay();
  }
}

// Function to remove an item from the cart
function removeFromCart(productId) {
  cartItems = cartItems.filter(item => item.id !== productId);
  updateCartDisplay();
}
