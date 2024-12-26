<?php
// file for updating the admin username and password
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

include('config.php'); 
$username = $_SESSION['username'];

$new_username = mysqli_real_escape_string($conn, $_POST['username']);
$new_password = mysqli_real_escape_string($conn, $_POST['password']);
$confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);


if ($new_password !== "" && $new_password !== $confirm_password) {

    echo "Passwords do not match.";
    exit();
}

$update_query = "UPDATE admin SET username = '$new_username'";

if ($new_password !== "") {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_query .= ", password = '$hashed_password'";
}

$update_query .= " WHERE username = '$username'";


if (mysqli_query($conn, $update_query)) {

    $_SESSION['username'] = $new_username;


    header("Location: admin_dashboard.php");
    exit();
} else {
    echo "Error updating profile: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
