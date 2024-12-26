<?php
include('config.php');

// Fetch document data based on the ID passed via GET request
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM sessions WHERE id=?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $document = $result->fetch_assoc();
    $stmt->close();
    if (!$document) {
        die("Session not found");
    }
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
            <section class="session-management">
                <div class="add-document">
                    <h2>Edit Session</h2>
                    <form method="POST" action="updatesession.php">
                        <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($document['id']); ?>">
                        <label for="session_name">Session Name</label>
                        <input type="text" id="session_name" name="session_name" value="<?php echo htmlspecialchars($document['session_name']); ?>" required>
                        <label for="start_datetime">Start Date and Time</label>
                        <input type="datetime-local" id="start_datetime" name="start_datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($document['start_datetime'])); ?>" required>
                        <label for="end_datetime">End Date and Time</label>
                        <input type="datetime-local" id="end_datetime" name="end_datetime" value="<?php echo date('Y-m-d\TH:i', strtotime($document['end_datetime'])); ?>" required>
                        <button type="submit" name="submit">Update</button>
                    </form>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
