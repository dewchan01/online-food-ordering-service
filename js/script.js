// JavaScript for interactive elements and form handling
// Use JavaScript to update order status, handle user interactions, and fetch menu items from a database

const productsList = document.getElementById('products-list');
const cart = document.getElementById('cart');
const vendorDropdown = document.getElementById('vendor');
const address = document.getElementById('addressField');


let selectedVendor;

if (vendorDropdown) {
    vendorDropdown.addEventListener('change', () => {
        selectedVendor = vendorDropdown.value; // Assign the selected vendor value
        // Fetch products based on the selected vendor
        fetch(`get_products.php?vendor=${selectedVendor}`)
            .then(response => response.json()) // Parse the JSON response
            .then(products => {
                productsList.innerHTML = ''; // Clear previous products
                products.forEach(product => {
                    const productCard = document.createElement('div');
                    productCard.className = 'product-card';
                    productCard.innerHTML = `
                <h3>${product.product_name}</h3>
                <img src="${product.image_url}" alt="${product.product_name}" width="100">
                <p>Price: $${product.price}</p>
                <button class="add-to-cart" data-product-name="${product.product_name}" data-product-id="${product.product_id}">Add to Cart</button>
            `;
                    productsList.appendChild(productCard);
                });
            })
            .catch(error => {
                console.error('Error fetching products:', error);
            });
    });
}

// Cart
const cartIcon = document.getElementById('cart-icon');
const cartCount = document.getElementById('cart-count');
const cartDropdown = document.getElementById('cart-dropdown');
// Track cart items
let cartItems = {};
if (cartIcon) {
    cartIcon.addEventListener('click', () => {
        cartDropdown.style.display = cartDropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Listen for add-to-cart button clicks
    document.addEventListener('click', event => {
        if (event.target.classList.contains('add-to-cart')) {
            const productName = event.target.getAttribute('data-product-name');

            if (cartItems[productName]) {
                cartItems[productName]++;
            } else {
                cartItems[productName] = 1;
            }
            updateCartDropdown();
            updateCartCount();
        }
        // display cart items in the cart
        if (event.target.id === 'checkout-btn') {
            const cartItemsQuery = JSON.stringify(cartItems);
            const checkoutURL = `place_order.php?vendor=${selectedVendor}&cart=${cartItemsQuery}`;

            // Redirect to the checkout page
            window.location.href = checkoutURL;
        }
    });

    function updateCartDropdown() {
        cartDropdown.innerHTML = '';

        for (const productName in cartItems) {
            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item';

            const quantityContainer = document.createElement('div');
            quantityContainer.className = 'quantity-container';

            const increaseBtn = document.createElement('button');
            increaseBtn.textContent = '+';
            increaseBtn.className = 'quantity-btn';
            increaseBtn.addEventListener('click', () => {
                cartItems[productName]++;
                updateCartDropdown();
            });

            const decreaseBtn = document.createElement('button');
            decreaseBtn.textContent = '-';
            decreaseBtn.className = 'quantity-btn';
            decreaseBtn.addEventListener('click', () => {
                if (cartItems[productName] > 1) {
                    cartItems[productName]--;
                    updateCartDropdown();
                }
            });
            const quantityText = document.createElement('span');
            quantityText.textContent = cartItems[productName];

            quantityContainer.appendChild(decreaseBtn);
            quantityContainer.appendChild(quantityText);
            quantityContainer.appendChild(increaseBtn);

            cartItem.innerHTML = `${productName} x `;
            cartItem.appendChild(quantityContainer);

            cartDropdown.appendChild(cartItem);
        }
        updateCheckOutBtn();
        updateCartCount();
    }



    function updateCartCount() {
        let totalItems = 0;
        for (const itemName in cartItems) {
            totalItems += cartItems[itemName];
        }
        cartCount.textContent = totalItems;
    }

    function updateCheckOutBtn() {
        const checkoutButton = document.createElement('button');
        checkoutButton.id = 'checkout-btn';
        checkoutButton.textContent = 'Checkout';
        cartDropdown.appendChild(checkoutButton);
    }
}
