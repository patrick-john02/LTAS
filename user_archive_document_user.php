<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['userid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document_ids'])) {
    $documentIds = $_POST['document_ids'];

    if (empty($documentIds)) {
        echo json_encode(['success' => false, 'message' => 'No documents selected.']);
        exit;
    }

    $placeholders = implode(',', array_fill(0, count($documentIds), '?'));
    $sql = "UPDATE documents SET isArchive = 1 WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $types = str_repeat('i', count($documentIds)); // Assuming IDs are integers
        $stmt->bind_param($types, ...$documentIds);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Documents archived successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No documents were updated.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'SQL Error: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
