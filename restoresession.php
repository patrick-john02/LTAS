<?php
include('config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the delete statement
    //$sql = "DELETE FROM sessions WHERE id=?";
    $sql = "UPDATE sessions SET isArchive = 0 WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Document deleted successfully";
    } else {
        echo "Error deleting document: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the document list
    header("Location: session_archive.php");
    exit();
}
?>
