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

let cartItems = []; // Array to store cart items

// Add event listener to all "Add to Cart" buttons
let addToCartBtns = document.querySelectorAll('.btn-add-to-cart');
addToCartBtns.forEach(btn => {
  btn.addEventListener('click', function() {
    let productId = parseInt(this.dataset.productId);
    let productName = this.dataset.productName;
    let productPrice = parseFloat(this.dataset.productPrice.replace(/\./g, ''));
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
      quantity: quantity,
    });
  }

  // Update cart display
  updateCartDisplay();
  showSuccessNotification(productName);
}


// Function to update cart display on the page
function updateCartDisplay() {
  // Clear existing cart items from the table
  let cartTable = document.querySelector('.cart-table tbody');
  cartTable.innerHTML = '';

  // Calculate and display total
  let cartTotal = 0;
  cartItems.forEach(item => { 
    let subtotal = item.price * item.quantity;
    cartTotal += subtotal;

    // Create table row for each cart item
    let row = cartTable.insertRow();
    let cellProduct = row.insertCell();
    let cellPrice = row.insertCell();
    let cellQuantity = row.insertCell();
    let cellSubtotal = row.insertCell();
    let cellRemove = row.insertCell();

    cellProduct.textContent = item.name;
    cellPrice.textContent = `${formatNumber(item.price)}₫`;

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
    cellSubtotal.textContent = `${formatNumber(subtotal)}₫`;

    // Remove button
    cellRemove.innerHTML = '<a href="#" onclick="removeFromCart(' + item.id + ' , \'' + item.name + '\')">Remove</a>';
  });

  document.getElementById('cart-total').textContent = `${formatNumber(cartTotal)}₫ `;
}

// Function to format a number with commas
function formatNumber(number) {
  // Convert number to a string
  let numberString = number.toFixed(0);

  // Split the number into an array of digits
  let digits = numberString.split('');

  // Reverse the array to process from right to left
  digits.reverse();

  // Add commas every 3 digits
  for (let i = 3; i < digits.length; i += 4) {
    digits.splice(i, 0, '.');
  }

  // Join the array back into a string
  let formattedNumberString = digits.join('');

  // Reverse the string back to original order
  formattedNumberString = formattedNumberString.split('').reverse().join('');

  return formattedNumberString;
}

// Function to show a success notification (customizable)
function showSuccessNotification(productName) {
  let notificationElement = document.createElement('div');
  notificationElement.classList.add('success-notification'); // Add CSS class for styling
  notificationElement.textContent = `"${productName}" added to cart!`;

  // Append notification to a specific container (customize based on your HTML structure)
  let notificationContainer = document.getElementById('notification-container');
  if (notificationContainer) {
    notificationContainer.appendChild(notificationElement);

    // Add a timeout to automatically remove the notification (optional)
    setTimeout(() => {
      notificationElement.remove();
    }, 2000); // (milliseconds)
  } else {
    console.warn('Notification container not found. Please add an element with ID "notification-container" to your HTML.');
  }
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
function removeFromCart(productId, productName) {
  // Find the index of the item to remove
  let itemIndex = cartItems.findIndex(item => item.id === productId && item.name === productName);

  if (itemIndex !== -1) {
    // Remove the item from the cart array
    cartItems.splice(itemIndex, 1);

    // Update order numbers for remaining items
    for (let i = itemIndex; i < cartItems.length; i++) {
      cartItems[i].order--;
    }

    // Update cart display
    updateCartDisplay();
  }
}

function toggleElements() {
  const elements1 = document.querySelectorAll('.show-cart');
  const elements2 = document.querySelectorAll('.home-page');
  const elements3 = document.querySelectorAll('.product-show');

  elements1.forEach(element => {
    element.style.display = 'block';
  });

  elements2.forEach(element => {
    element.style.display = 'none';
  });

  elements3.forEach(element => {
    element.style.display = 'none';
  });
}