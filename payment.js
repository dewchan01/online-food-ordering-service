function backToOrder() {
    choice = confirm("Are you sure you want to go back to the order? Current order will be deleted and refreshed!")
    if(choice){
        window.location.href = "customer_dashboard.php";
    }
}