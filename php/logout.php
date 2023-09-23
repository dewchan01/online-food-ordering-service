<?php
// Start or resume the current session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect back to the login page
header("Location: ../order.php");
exit();
?>
