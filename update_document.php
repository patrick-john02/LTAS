<?php
require_once 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    echo "Error: User not logged in.";
    exit;
}

$uid = $_SESSION['userid'];
$doc_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$doc_id) {
    echo "Error: Document ID is required.";
    exit;
}

// Fetch the document details from the database
$sql = "SELECT * FROM documents WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $doc_id, $uid);
$stmt->execute();
$result = $stmt->get_result();
$document = $result->fetch_assoc();

if (!$document) {
    echo "Error: Document not found.";
    exit;
}

// Handle form submission for updating the document
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $status = $_POST['status'];

    // Update the document in the database
    $update_sql = "UPDATE documents SET Title = ?, Author = ?, d_status = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $title, $author, $status, $doc_id);

    if ($update_stmt->execute()) {
        $_SESSION['message'] = "Document updated successfully!";
        header('Location: resolutions.php'); // Redirect to the resolutions list page
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Resolution</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Update Resolution</h2>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($document['Title']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($document['Author']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Pending" <?php echo ($document['d_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="First Reading" <?php echo ($document['d_status'] == 'First Reading') ? 'selected' : ''; ?>>First Reading</option>
                <option value="Second Reading" <?php echo ($document['d_status'] == 'Second Reading') ? 'selected' : ''; ?>>Second Reading</option>
                <option value="Approved" <?php echo ($document['d_status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                <option value="Rejected" <?php echo ($document['d_status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="resolutions.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>
