<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['userid'])) {
    echo "Error: User not logged in.";
    exit;
}

// Check if the user is authorized to delete the document
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $documentId = $_POST['id'];
    $userId = $_SESSION['userid'];

    // Check if the document exists and the user has permission to delete it
    $sql = "SELECT * FROM documents WHERE id = ? AND user_id = ? AND d_status = 'Pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $documentId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Document exists and belongs to the logged-in user, proceed to delete
        $sql = "DELETE FROM documents WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $documentId, $userId);
        if ($stmt->execute()) {
            header("Location: sent_document_user.php?success=1"); // Redirect back with success message
        } else {
            echo "Error deleting document: " . $conn->error;
        }
    } else {
        echo "Error: Document not found or not authorized to delete.";
    }
} else {
    echo "Invalid request.";
}

?>
