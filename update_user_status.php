<?php

include('config.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];
    $newStatus = $_POST['new_status'];

    
    $sql = "UPDATE users SET u_status = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $newStatus, $userId);
    
    if ($stmt->execute()) {
       
        header("Location: users.php");
    } else {
        echo "Error updating status: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
