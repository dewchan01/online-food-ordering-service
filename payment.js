//confirm payment
function confirmPayment() {
    const confirmPaymentButton = document.getElementById('confirm-payment-btn');
    const loadingOverlay = document.getElementById('loading-overlay');

    // Disable the button to prevent multiple clicks
    confirmPaymentButton.disabled = true;

    // Show the loading overlay
    loadingOverlay.style.display = 'flex';

    // Simulate the payment process and database writing
    setTimeout(() => {
        // Hide the loading overlay after a few seconds
        loadingOverlay.style.display = 'none';

        // Enable the button again
        confirmPaymentButton.disabled = false;

        // Show an alert to indicate payment success
        alert('Payment successful!');

        // Redirect to customer dashboard
        window.location.href = "customer_dashboard.php";
    }, 3000); // Simulate a 3-second payment process and database writing
}
