// JavaScript for interactive elements and form handling
// Use JavaScript to update order status, handle user interactions, and fetch menu items from a database

const productsList = document.getElementById('products-list');
const cart = document.getElementById('cart');
const address = document.getElementById('addressField');
const arrow = document.querySelector('.arrow-down');

let selectedVendor = 'vendora';
if (productsList) {
    fetch(`get_products.php?vendor=${selectedVendor}`)
        .then(response => response.json()) // Parse the JSON response
        .then(products => {
            productsList.innerHTML = ''; // Clear previous products
            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.innerHTML = `
                <div class="product-container">
                <img src="../../images/${product.image_url}" alt="${product.product_name}" class="product-image">
                <div class="product-details-container">
                <h3>${product.product_name}</h3>
                <p>From $${product.price}</p>
                <p>${product.description}</p>
                </div>
                </div>
                <div class="product-selector">
                <select class="quantity-dropdown" id="quantity-${product.product_id}">
                        ${generateQuantityOptions()}
                </select>
                <button class="add-to-cart" data-product-name="${product.product_name}" data-product-id="${product.product_id}" data-product-price="${product.price}">Add</button>
                </div>    
            `;
                productsList.appendChild(productCard);
            });
        })
        .catch(error => {
            console.error('Error fetching products:', error);
        });


    // Function to generate quantity options from 1 to 10
    function generateQuantityOptions() {
        let options = '';
        for (let i = 1; i <= 10; i++) {
            options += `<option value="${i}">${i}</option>`;
        }
        return options;
    }
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
        arrow.classList.toggle('active');
    
    });

    cartDropdown.style.display = 'block'

    // Listen for add-to-cart button clicks
    document.addEventListener('click', event => {
        if (event.target.classList.contains('add-to-cart')) {
            const button = event.target;
            const productName = button.getAttribute('data-product-name');
            const productId = button.getAttribute('data-product-id');
            const productPrice = button.getAttribute('data-product-price');
            // Find the corresponding quantity dropdown based on the productId
            const quantityDropdown = document.getElementById(`quantity-${productId}`);
            const selectedQuantity = parseInt(quantityDropdown.value);
            if (cartItems[productName]) {
                cartItems[productName] += selectedQuantity;
            } else {
                cartItems[productName] = selectedQuantity;
            }
            updateCartDropdown(productPrice);
            // updateCartCount(productPrice);
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

        let totalCartPrice = 0; // Initialize the total cart price

        for (const itemName in cartItems) {
            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item';

            const quantityContainer = document.createElement('div');
            quantityContainer.className = 'quantity-container';

            const removeBtn = document.createElement('button');
            removeBtn.style = 'color:#006E9A;font-weight:1000;';
            removeBtn.textContent = 'Remove One';
            removeBtn.className = 'quantity-btn';
            removeBtn.addEventListener('click', () => {
                if (cartItems[itemName] === 1){
                    delete cartItems[itemName]; // Remove item from cartItems when count is 0
                }else{
                cartItems[itemName]--;
                }
                updateCartDropdown();
            });

            const removeAllBtn = document.createElement('button');
            removeAllBtn.style = 'color:red;font-weight:1000;';
            removeAllBtn.textContent = 'Remove All';
            removeAllBtn.className = 'quantity-btn';
            removeAllBtn.addEventListener('click', () => {
                if (confirm("Are you sure you want to remove all?")){
                if (cartItems[itemName] > 0) {
                    cartItems[itemName] = 0;
                    delete cartItems[itemName]; // Remove item from cartItems when count is 0
                    updateCartDropdown();
                }
            }
                // console.log(cartItems)

            });

            const quantityText = cartItems[itemName];
            const itemPrice = parseFloat(document.querySelector(`[data-product-name="${itemName}"]`).getAttribute('data-product-price'));
            const itemTotalPrice = itemPrice * cartItems[itemName];

            // Add the individual item price to the total cart price
            totalCartPrice += itemTotalPrice;

            quantityContainer.appendChild(removeAllBtn);
            quantityContainer.appendChild(removeBtn);

            cartItem.innerHTML = `<div class="cart-item-container"><div>${quantityText} x ${itemName} \t</div> <p style="font-weight:700;margin:0;">$${itemTotalPrice.toFixed(2)}</p></div>`;
            cartItem.appendChild(quantityContainer);

            cartDropdown.appendChild(cartItem);
        }

        updateCheckOutBtn(totalCartPrice); // Pass the total cart price to updateCheckOutBtn
        // if (totalCartPrice) {
        //     updateCartCount(totalCartPrice + 5); // Update total price here
        // } else {
        //     updateCartCount(0);
        // }

    }

    // function updateCartCount(productPrice) {

    //     let totalItems = 0;
    //     for (const itemName in cartItems) {
    //         totalItems += cartItems[itemName];
    //     }
    //     let itemsText = "items";
    //     if (totalItems === 1) {
    //         itemsText = "item";
    //     }
    //     // console.log(cartItems)
    //     // console.log(productPrice)
    //     if (productPrice){
    //         cartCount.textContent = `Delivery Service Fee: $5.00\nTotal: $${(productPrice).toFixed(2)}\n${totalItems} ${itemsText}`;
    //     }else{
    //         cartCount.textContent = 'Total: $0.00';
    //     }
    // }
    function updateCheckOutBtn(totalCartPrice) {
        let itemsText = "items";
        let totalItems = 0;
        for (const itemName in cartItems) {
            totalItems += cartItems[itemName];
        }
        if (totalItems <= 1) {
            itemsText = "item";
        }
        if (totalCartPrice > 0) {
            const summary = document.createElement('p');
            summary.class='cart-item-container'
            summary.innerHTML = `
            <div class="cart-item-container" style="font-weight: 700;">
                <p>Delivery Service Fee</p> 
                <p>$5.00</p>
            </div> 
            <div class="cart-item-container" style="font-weight: 700;">
                <p>Total Price</p>  
                <p>$` + (totalCartPrice + 5).toFixed(2)+'</p></div>';
            
            const checkoutButton = document.createElement('button');
            checkoutButton.id = 'checkout-btn';
            checkoutButton.class= 'confirm-btn';
            checkoutButton.textContent = totalItems + ' ' + itemsText + ' | Total Price: $' + (totalCartPrice + 5).toFixed(2) + ' | Finish Order';
            cartDropdown.appendChild(summary);
            cartDropdown.appendChild(checkoutButton);
        } else {
            const summary = document.createElement('h5');
            const summaryText = document.createElement('p');
            summaryText.textContent = 'Add some items from the menu to get started!';

            summary.innerHTML = 'Total Price: $' + (totalCartPrice).toFixed(2);
            const checkoutButton = document.createElement('button');
            checkoutButton.id = 'checkout-btn';
            checkoutButton.class= 'confirm-btn';
            checkoutButton.disabled = true;
            checkoutButton.textContent = totalItems + ' ' + itemsText + ' | Total Price: $' + (totalCartPrice).toFixed(2) + ' | Finish Order';
            cartDropdown.appendChild(summaryText);
            cartDropdown.appendChild(summary);
            cartDropdown.appendChild(checkoutButton);
        }

    }

}

// My Account
const myAccount = document.getElementById('my-account-container');
const myAccountNav = document.getElementById('my-account-nav');
if(myAccount){    
    myAccount.addEventListener('click', () =>{
    console.log('Clicked on my account');
    myAccountNav.style.display = myAccountNav.style.display ==='block' ? 'none' : 'block';
    
    });
}

//Menu Nav
let navs = document.querySelectorAll(".categories-nav");

navs.forEach(function(nav){
    nav.addEventListener("click",function(event){
        event.preventDefault();

        nav.style.color="white";
        nav.style.background="#4A4A4A";
        nav.style.borderTopRightRadius = "9px";
        nav.style.borderTopLeftRadius= "9px";
        if(nav.id==="values-meal"){
            nav.style.width="150px";
        }
        else{
            nav.style.width="80px";
        }

        navs.forEach(function(otherNav){
            if(otherNav !== nav){
                otherNav.style.color="black";
                otherNav.style.background="white";
                otherNav.style.borderTopRightRadius = "0px";
                otherNav.style.borderTopLeftRadius= "0px";
            }
        });

    });
});

//