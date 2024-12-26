<?php
require_once 'config.php'; // Ensure you have a proper database connection

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete']) && isset($_POST['id'])) {
    $documentId = $_POST['id'];

    // Prepare a delete SQL query
    $sql = "DELETE FROM documents WHERE id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("i", $documentId);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect to the previous page with a success message
            $_SESSION['message'] = "Resolution successfully deleted!";
            header("Location: resolution_user.php"); // Change this URL to your resolution list page
            exit();
        } else {
            // Handle failure
            $_SESSION['message'] = "Error deleting resolution. Please try again.";
            header("Location: resolution_user.php"); // Change this URL to your resolution list page
            exit();
        }
    } else {
        die("SQL Error: " . $conn->error);
    }
} else {
    // If the form wasn't submitted properly, redirect to the resolution list page
    $_SESSION['message'] = "Invalid request.";
    header("Location: resolution_user.php"); // Change this URL to your resolution list page
    exit();
}
?>
