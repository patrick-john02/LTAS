<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['userid'])) {
    echo "Error: User not logged in.";
    exit;
}

include('./includes/user/user_navbar.php');
include('./includes/user/user_sidebar.php');

$uid = $_SESSION['userid'];
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

// Base query: Select all resolutions (no filtering by status)
$sql = "SELECT * FROM documents WHERE user_id = ? AND Category = 'Resolution'";
$params = [$uid];
$types = "i";

// Date range filtering
if (!empty($startDate) && !empty($endDate)) {
    $sql .= " AND `Date Published` BETWEEN ? AND ?";
    $params[] = $startDate;
    $params[] = $endDate;
    $types .= "ss";
}

$sql .= " ORDER BY `Date Published` DESC"; // Sort by date

$stmt = $conn->prepare($sql);
if ($stmt) {
    // Bind parameters dynamically
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("SQL Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resolution Lists</title>
    <script src="assets/jquery-3.7.1.js"></script>
    <script src="assets/dataTables.js"></script>
    <link rel="stylesheet" href="assets/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
#print-header {
    display: none;
}

@media print {
  /* Hide the columns: Approved on and Status */
   /* Approved on column */
     th:nth-child(6), td:nth-child(6)  /* Status column */ {
        display: none;
    }

    #print-header {
        display: block;
        text-align: center;
        margin-bottom: 20px;
    }
    #print-header img {
        width: 100px;
        height: auto;
    }
    #print-header h3,
    #print-header p {
        margin: 0;
        font-size: 16px;
    }
    #filter-form,
    #print-button,
    .dataTables_filter, /* Search bar */
    .dataTables_length, /* Entries dropdown */
    .dataTables_info,   /* Showing entries info */
    .dataTables_paginate, /* Pagination controls */
    .btn,

    input[type="checkbox"] {
        display: none !important;
    }
    th:first-child, td:first-child, th:sixth-child, {
        display: none;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 8px;
        text-align: left;
        border: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }
    tr:hover {
        background: none !important;
    }
}
</style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                        <!-- Success Message will appear here if success=1 -->
                        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                            <div class="alert alert-success" role="alert">
                                Resolution successfully added!
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="user_dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Resolutions</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addDocumentModal">
    Add New Resolution
</button>
<?php
// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');
?>
<div id="print-header">
    <h2 style="font-family: 'Georgia, serif', Times, serif; font-size: 20px; font-weight: bold; text-align: center;"><strong>
        Republic of Philippines
        </strong>
    </h2>
    <h3>Province of Cagayan</h3>
    <p>Municipality of Solana</p>
    <br>
    <img src="image/LOGO1.png" alt="Logo" style="width: 100px; height: auto; " >
    <br><br>
    <h3><strong>OFFICE OF THE SANGGUNIANG KABATAAN</strong></h3>
    <br>
    <p>Approved List of Resolutions as of <strong><?php echo date('F d, Y H:i:s A'); ?></strong></p>
</div>
<div class="card-body">

<button onclick="window.print();" class="btn btn-secondary" id="print-button">Print Report</button>
<br><br>
<!-- Table -->
<table class="table table-bordered table-striped" id="resolutionTable">
    <thead>
        <tr>
            <th>Resolution No.</th>
            <th>Title</th>
            <th>Authored By</th>
            <th>Date Published</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
   if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Skip rows where the document status is "Approve" or "Reject"
        if ($row["d_status"] === 'Approve' || $row["d_status"] === 'Reject') {
            continue;
        }
        $status = '';
        switch ($row["d_status"]) {
            case 'Pending':
                $status = 'Pending';
                break;
            case 'First Reading':
                $status = 'First Reading';
                break;
            case 'Second Reading':
                $status = 'Second Reading';
                break;
            case 'In Committee':
                $status = 'In Committee';
                break;
            default:
                $status = 'Unknown';
                break;
        }

        echo "<tr data-id='" . htmlspecialchars($row["id"]) . "'>";
        echo "<td><a href='user_document_info.php?id=" . urlencode($row["id"]) . "'>" . htmlspecialchars($row["resolution_no"]) . "</a></td>";
        echo "<td class='editable' data-column='Title'>" . htmlspecialchars($row["Title"]) . "</td>";
        echo "<td class='editable' data-column='Author'>" . htmlspecialchars($row["Author"]) . "</td>";
        echo "<td>" . date('Y-m-d', strtotime($row["Date Published"])) . "</td>";
        echo "<td data-column='Status'>" . htmlspecialchars($status) . "</td>";

        // Only show the Delete button if the document status is 'Pending'
        if ($row["d_status"] === 'Pending') {
            echo "<td><form action='delete_document.php' method='POST'>
                <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                <button type='submit' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this document?\");'>Delete</button>
                </form></td>";
        } else {
            echo "<td></td>"; // No action button if the status is not 'Pending'
        }

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>No documents found</td></tr>";
}
?>
</tbody>
</table>

</div>
</div>
</div>

<!-- Modal for Adding New Resolution -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" role="dialog" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDocumentModalLabel">Add New Resolution</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="add_document.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>

                    <!-- Hidden fields for automated category and date -->
                    <input type="hidden" name="category" value="Resolution">
                    <input type="hidden" name="date_published" id="date_published">

                    <div class="form-group">
                        <label for="file_path">File: (valid file pdf, img, docx)</label>
                        <input type="file" class="form-control" id="file_path" name="file_path" accept=".pdf, .docx, image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Resolution</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Automatically set the current date and time in ISO format for the hidden date field
    document.addEventListener('DOMContentLoaded', () => {
        const now = new Date().toISOString().slice(0, 16);
        document.getElementById('date_published').value = now;
    });
</script>
<script>
    function printTable() {
    // Show the print header before printing
    document.getElementById('print-header').style.display = 'block';

    // Create a clone of the table to print
    var printContent = document.getElementById('example1').outerHTML;
    
    // Create a new window for printing
    var newWindow = window.open('', '', 'height=500,width=800');
    
    // Add a print header and the cloned table to the new window
    newWindow.document.write('<html><head><title>Print Report</title>');
    newWindow.document.write('<style>/* Additional

</script>
    </script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/chart.js/Chart.min.js"></script>
<script src="assets/plugins/sparklines/sparkline.js"></script>
<script src="assets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="assets/plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="assets/plugins/summernote/summernote-bs4.min.js"></script>
<script src="assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="assets/dist/js/adminlte.js"></script>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/dist/js/adminlte.js"></script>
<script src="assets/plugins/chart.js/Chart.min.js"></script>
<script src="assets/dist/js/pages/dashboard3.js"></script>
<script src="assets/dist/js/pages/dashboard.js"></script>
<script src="assets/dist/js/adminlte.min.js"></script>
<!--Data tables imports -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="assets/plugins/jszip/jszip.min.js"></script>
<script src="assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>
    $(document).ready(function() {
        // Enable inline editing
        $(".editable").click(function() {
            var currentValue = $(this).text();
            var columnName = $(this).data("column");
            var inputField = $("<input>").val(currentValue).addClass("form-control");

            $(this).html(inputField);
            inputField.focus();

            inputField.blur(function() {
                var newValue = inputField.val();
                $(this).parent().text(newValue);  // Replace the input with the new value

                // Send the updated value to the server
                var rowId = $(this).closest("tr").data("id");

                $.ajax({
                    url: 'update_document.php', // Create an update script
                    type: 'POST',
                    data: {
                        id: rowId,
                        column: columnName,
                        value: newValue
                    },
                    success: function(response) {
                        // Handle response (optional)
                        toastr.success("Updated successfully!");
                    },
                    error: function() {
                        toastr.error("Failed to update!");
                    }
                });
            });
        });
    });
</script>

<script>
$(function () {
  // check box features 
  $('#select-all').click(function () {
    $('input[name="selected_documents[]"]').prop('checked', this.checked);
    toggleArchiveButton();
  });

  // archive enable and disabled button
  $('input[name="selected_documents[]"]').click(function () {
    toggleArchiveButton();
  });

  function toggleArchiveButton() {
    var selectedDocs = $('input[name="selected_documents[]"]:checked').length;
    if (selectedDocs > 0) {
      $('#archive-selected-btn').prop('disabled', false);
    } else {
      $('#archive-selected-btn').prop('disabled', true);
    }
  }
  $("#example1").DataTable({
    "responsive": true,
    "lengthChange": false,
    "autoWidth": false,
    "order": [[4, 'desc']], 
    "columnDefs": [
      {
        "targets": [4], 
        "type": "date", 
        "render": function(data, type, row) {
          
          return data; 
        }
      }
    ],
    "buttons": ["copy", "csv", "excel", "pdf", "print"]
  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  $('#example1_wrapper .dataTables_filter').css({
    'float': 'right',
    'text-align': 'right'
  });

  $('#example1_wrapper .dataTables_filter input').css('width', '300px');
});
</script>
</body>
</html>
<?php
// Close the database connection
$stmt->close();
$conn->close();
?>