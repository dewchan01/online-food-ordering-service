function backToOrder() {
    choice = confirm("Are you sure you want to go back to the order? Current order will be deleted and refreshed!")
    if (choice) {
        window.location.href = "customer_dashboard.php";
    }
}

function requestLogin(username) {
    if (username == "guest") {
        var requestLogin = confirm('You are not logged in! Do you want to proceed to login?');
    }
    if (requestLogin) {
        window.location.href = "../../login.html";
    }
}
