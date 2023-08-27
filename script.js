// JavaScript for interactive elements and form handling
// Use JavaScript to update order status, handle user interactions, and fetch menu items from a database

const productsList = document.getElementById('products-list');
const cart = document.getElementById('cart');
const checkoutBtn = document.getElementById('checkout-btn');
const vendorDropdown = document.getElementById('vendor');
// Assuming you have a vendor dropdown

let selectedVendor;

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
                <button class="add-to-cart" data-product-id="${product.product_id}">Add to Cart</button>
            `;
            productsList.appendChild(productCard);
        });
    })
    .catch(error => {
        console.error('Error fetching products:', error);
    });
});

// ... Rest of the code ...
