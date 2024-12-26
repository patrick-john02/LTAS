<?php
include 'config.php';

// Check if the selected date is posted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date_published'])) {
    $selectedDate = $_POST['date_published'];

    // Query to fetch data based on the selected date
    $query = "
        SELECT `Date Published`, `Title`, `Description`, `Author`, `Category`
        FROM `documents`
        WHERE `CATEGORY` = 'Resolution'
        AND `d_status` = 'Approved'
        AND `Date Published` = ?
    ";

    // Prepare and execute the query
    $tableContent = "";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("s", $selectedDate);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are rows
        if ($result->num_rows > 0) {
            $tableContent = "<table class='table table-bordered'>";
            $tableContent .= "<thead><tr>
                                <th>Date Published</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Author</th>
                                <th>Category</th>
                              </tr></thead><tbody>";

            while ($row = $result->fetch_assoc()) {
                $tableContent .= "<tr>
                                    <td>" . htmlspecialchars($row['Date Published']) . "</td>
                                    <td>" . htmlspecialchars($row['Title']) . "</td>
                                    <td>" . htmlspecialchars($row['Description']) . "</td>
                                    <td>" . htmlspecialchars($row['Author']) . "</td>
                                    <td>" . htmlspecialchars($row['Category']) . "</td>
                                  </tr>";
            }

            $tableContent .= "</tbody></table>";
        } else {
            $tableContent = "<p>No records found for the selected date.</p>";
        }

        $stmt->close();
    } else {
        $tableContent = "Error preparing query: " . $conn->error;
    }

    $conn->close();
} else {
    header("Location: index.php"); // Redirect to the selection page if accessed directly
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> <!-- jsPDF library -->
    <title>Generate Report</title>

    <style>
         /* CSS to hide print and back-to-dashboard buttons during printing */
         @media print {
            #buttons {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
    <div style=" align-items: center;">
    <h2>List of Report</h2>
</div>


        <!-- Display the table content -->
        <?php echo $tableContent; ?>

        <div id="buttons" class="mt-4 d-flex gap-2">
            <button onclick="window.print();" class="btn btn-secondary">Print</button>
            <button onclick="window.location.href='resolution.php';" class="btn btn-danger">Cancel</button>
        </div>
    </div>


    </div>

    <script>
        // Check if jsPDF is available and log to console
        if (typeof jsPDF === 'undefined') {
            console.error('jsPDF library not found!');
        } else {
            console.log('jsPDF library loaded successfully');
        }

        // Function to generate PDF
        document.getElementById('downloadPdf').addEventListener('click', function() {
            console.log('Download PDF button clicked');

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Add title to PDF
            doc.text("Documents for Date Published: <?php echo addslashes($selectedDate); ?>", 10, 10);

            // Extract the table rows data manually
            const rows = [];
            const tableRows = document.querySelectorAll('table tbody tr');
            tableRows.forEach((row) => {
                const rowData = [];
                row.querySelectorAll('td').forEach((cell) => {
                    rowData.push(cell.innerText.trim()); // Collect each cell's text
                });
                rows.push(rowData); // Add row data to the array
            });

            // Log rows data to the console for debugging
            console.log('Extracted table rows:', rows);

            // Define the table headers
            const headers = ['Date Published', 'Title', 'Description', 'Author', 'Category'];

            // Add table headers and data to the PDF
            doc.autoTable({
                head: [headers], // Table headers
                body: rows,      // Table rows data
                startY: 20,      // Set the start Y position after the title
                margin: { left: 10, top: 30, right: 10 }, // Adjust margins as needed
            });

            // Save the PDF with the name 'documents.pdf'
            doc.save('documents.pdf');
        });
    </script>
</body>
</html>
