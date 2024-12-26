<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['session_name'];
    $start = $_POST['start_datetime'];
    $end = $_POST['end_datetime'];

    // Generate a unique ID for the session
    $id = uniqid('id');

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO sessions (id, session_name, start_datetime, end_datetime) VALUES (?, ?, ?, ?)");
    
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("ssss", $id, $name, $start, $end);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location:session.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close database connection (if not done already)
$conn->close();
?>
