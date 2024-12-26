<?php
include('config.php');

// Fetch document data based on the ID passed via GET request
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM documents WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);  // Updated bind_param type to string (since document IDs are strings)
    $stmt->execute();
    $result = $stmt->get_result();
    $document = $result->fetch_assoc();
    $stmt->close();
} else {
    die("ID not provided");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <main>
            <header>
                <div class="user-name">Profile</div>
            </header>
            <section class="documents-management">
                <div class="add-document">
                    <h2>Edit Document</h2>
                    <form method="POST" action="updatedocument.php" enctype="multipart/form-data">
                        <input type="hidden" id="id" name="id" value="<?php echo $document['id']; ?>">
                        
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" value="<?php echo $document['Title']; ?>" required>
                        
                        <label for="description">Description</label>
                        <input type="text" id="description" name="description" value="<?php echo $document['Description']; ?>" required>
                        
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" value="<?php echo $document['Author']; ?>" required>
                        
                        <label for="date-stamp">Date Published</label>
                        <input type="datetime-local" id="date-stamp" name="date-stamp" value="<?php echo date('Y-m-d\TH:i', strtotime($document['Date Published'])); ?>" required>
                        
                        <label for="documents-type">Category</label>
                        <select id="documents-type" name="documents-type" required>
                            <option value="Memorandum" <?php if ($document['Category'] == 'Memorandum') echo 'selected'; ?>>Memorandum</option>
                            <option value="Resolution" <?php if ($document['Category'] == 'Resolution') echo 'selected'; ?>>Resolution</option>
                            <option value="Executive Order" <?php if ($document['Category'] == 'Ordinances') echo 'selected'; ?>>Executive Order</option>
                        </select>

                        <!-- PDF Upload Section -->
                        <label for="pdf-file">Replace PDF (optional)</label>
                        <input type="file" id="pdf-file" name="pdf-file" accept=".pdf">

                        <?php if (!empty($document['file_path'])): ?>
                            <p>Current PDF: <a href="<?php echo $document['file_path']; ?>" download>Download Current PDF</a></p>
                        <?php endif; ?>

                        <button type="submit" name="submit">Update</button>
                    </form>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
