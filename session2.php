<?php
include('config.php');
include('add_session.php');

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesions</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <center><div class="sidebar-header">LTAS</div></center>
            <nav>
                <ul>
                    <li><a href="#">Dashboard</a></li>
                  <center><li class="header">LEGISLATIVE MANAGEMENT</li></center>  
                    <li><a href="sesions.php">Sesions</a></li>
                    <li><a href="index.php">Documents Management</a></li>
                    <li><a href="#">Memorandum</a></li>
                    <li><a href="#">Resolution</a></li>
                    <li><a href="#">Ordinances</a></li>
                </ul>
            </nav>
        </aside>
        <main>
            <header>
                <div class="user-name">Profile</div>
            </header>
            <section class="Documents-management">
                <div class="add-document">
                    <h2>Legislative Sesions</h2>
                    <form action="add_session.php" method="post">
    <label for="id">ID</label>
    <input type="text" id="id" name="id" value="AUTO_GENERATED_ID" readonly>
    <label for="session_name">Session Name</label>
    <input type="text" id="session_name" name="session_name" required>
    
    <label for="start_datetime">Start Date and Time</label>
    <input type="datetime-local" id="start_datetime" name="start_datetime" required>
    
    <label for="end_datetime">End Date and Time</label>
    <input type="datetime-local" id="end_datetime" name="end_datetime" required>
    
    <button type="submit">Submit</button>
</form>
                </div>
                <div class="document-list">
                    <div class="document-item">
<center> <h3>Session View</h3></center>
<?php
include('config.php');

// Your SQL query
$sql = "SELECT * FROM sessions";
$result = $conn->query($sql);

if ($result === false) {
    echo "<p>Query error: " . $conn->error . "</p>";
} else {
    if ($result->num_rows > 0) {
        // Inside your document list loop in index.php
        while ($row = $result->fetch_assoc()) {
            echo "<div class='sessions-item'>";
            echo "<p><strong>Session Name:</strong> " . htmlspecialchars($row["session_name"]) . "</p>";
            echo "<p><strong>Start date and time:</strong> " . htmlspecialchars($row["start_datetime"]) . "</p>";
            echo "<p><strong>End date and time:</strong> " . htmlspecialchars($row["end_datetime"]) . "</p>";
            echo "<a href='editsession.php?id=" . urlencode($row['id']) . "' class='view-items'>Edit</a>";
        
            echo "<a href='deletesession.php?id=" . urlencode($row['id']) . "' class='view-items'>Delete</a>";
            echo "</div>";
        }
    } else {
        echo "<p>No documents found</p>";
    }
}

// Close the connection
$conn->close();
?>

</div>

        </div>
                    
            </div>
        </main>
    </div>
</body>
</html>

