<?php
include('config.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST['id'];
    $name = $_POST['session_name'];
    $start = $_POST['start_datetime'];
    $end = $_POST['end_datetime'];
    

    // Update document in the database
    $sql = "UPDATE sessions SET session_name=?, start_datetime=?, end_datetime=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $start, $end, $id);
    
    if ($stmt->execute()) {
         header("Location:session.php");
    } else {
        echo "Error updating document: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
