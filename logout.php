<?php
session_start();

// Ensure the session variable 'userType' exists to avoid undefined index errors
if (isset($_SESSION['userType'])) {
    // Check userType and set the redirect URL accordingly
    if ($_SESSION['userType'] === 'user') {
        $redirect = 'login.php'; // Redirect for regular users
    } elseif ($_SESSION['userType'] === 'admin') {
        $redirect = 'login_admin.php'; // Redirect for admin users
    } else {
        $redirect = 'login.php'; // Default redirect if userType is invalid or undefined
    }
} else {
    // Default redirect if no userType is set in the session
    $redirect = 'login.php';
}

// Destroy the session
session_unset();
session_destroy();

// Redirect the user to the appropriate login page
header("Location: $redirect");
exit(); // Terminate the script to prevent further execution
?>
