<?php

include 'config.php'; 

// Fetch distinct "Date Published" values for the dropdown
$dateQuery = "SELECT DISTINCT `Date Published` FROM `documents` WHERE `CATEGORY` = 'Ordinances' AND `d_status` = 'Approved'";
$dateResult = $conn->query($dateQuery);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected date from the form
    $selectedDate = isset($_POST['date_published']) ? $_POST['date_published'] : '';  // Fixed line

    // Define the query to fetch rows based on the selected date
    $query = "
        SELECT `Date Published`
        FROM `documents`
        WHERE `CATEGORY` = 'Ordinances'
        AND `d_status` = 'Approved'
        AND `Date Published` = ?
    ";

    // Prepare and execute the query
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $selectedDate);
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Check if results exist
        if ($result->num_rows > 0) {
            $tableContent = "<table class='table table-bordered'>";
            $tableContent .= "<thead><tr><th>Date Published</th></tr></thead><tbody>";

            // Fetch and display each row
            while ($row = $result->fetch_assoc()) {
                $tableContent .= "<tr><td>" . htmlspecialchars($row['Date Published']) . "</td></tr>";
            }

            $tableContent .= "</tbody></table>";
        } else {
            $tableContent = "<p>No records found for the selected date.</p>";
        }

        $stmt->close();
    } else {
        $tableContent = "Error preparing query: " . $conn->error;
    }
} else {
    $tableContent = "<p>Please select a date and submit the form to fetch results.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Select Date Published</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Select Date for Approved Generate Reports</h2>

        <!-- Form for selecting date -->
        <form method="POST" action="ListOrdinancesReport.php">
            <div class="mb-3">
                <label for="date_published" class="form-label">Date Published:</label>
                <select id="date_published" name="date_published" class="form-select" required>
                    <option value="" selected disabled>Select a date</option>
                    <?php
                    // Populate dropdown with distinct dates
                    if ($dateResult->num_rows > 0) {
                        while ($row = $dateResult->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['Date Published']) . "'>" . htmlspecialchars($row['Date Published']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Next</button>
            <button onclick="window.location.href='ordinaces.php';" class="btn btn-danger">Cancel</button>
        </form>

        <!-- Display table content or message -->
        <div class="mt-4">
            <?php echo $tableContent; ?>
        </div>
    </div>
</body>
</html>
