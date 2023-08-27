//confirm payment

const confirmPaymentButton = document.getElementById('confirm-payment-btn');
const loadingOverlay = document.getElementById('loading-overlay');

confirmPaymentButton.addEventListener('click', () => {
    // Show the loading overlay
    loadingOverlay.style.display = 'flex';
    
    // Simulate the payment process (replace this with actual payment logic if available)
    setTimeout(() => {
        // Hide the loading overlay after a few seconds
        loadingOverlay.style.display = 'none';

        // Prompt the user to continue the purchase or log out
        alert('Payment successful!');
        window.location.href = "customer_dashboard.php";
    }, 3000); // Simulate a 3-second payment process
});
