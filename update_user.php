<?php
include 'config.php'; // Include your DB connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    // Sanitize input values (you can use prepared statements instead of raw queries)
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $field = mysqli_real_escape_string($conn, $field);
    $value = mysqli_real_escape_string($conn, $value);

    // Prepare the SQL update query
    $sql = "UPDATE users SET $field = '$value' WHERE ID = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
