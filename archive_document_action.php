<?php
include('config.php');

if (isset($_POST['archive']) && isset($_POST['id'])) {
    $doc_id = $_POST['id'];

    // Query to update the isArchive status to 1 (archived)
    $sql = "UPDATE documents SET isArchive = 1 WHERE id = $doc_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Document has been archived'); window.location.href='archived_documents.php';</script>";
    } else {
        echo "<script>alert('Error archiving document'); window.location.href='archived_documents.php';</script>";
    }
} else {
    echo "<script>alert('No document selected for archiving'); window.location.href='archived_documents.php';</script>";
}
$conn->close();
?>
