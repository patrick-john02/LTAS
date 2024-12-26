<?php
session_start();
include('config.php'); 

if(!isset($_SESSION['userid'])) {
    header("location:login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Ensure the ID is an integer

    // Update the document status to 'Active' or the desired status
    $sql = "UPDATE documents SET d_status = 'Approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
