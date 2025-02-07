<?php
session_start();
ob_start(); // Output buffering
include('config.php');
include('./includes/navbar.php');
include('./includes/sidebar.php');

// Get selected status filter
$statusFilter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';

// Prepare the base SQL query
$sql = "SELECT doc_no, Title, Author, `date_published`, Category, d_status, id, file_path, resolution_no, approval_timestamp
FROM documents 
WHERE (isArchive = 0 OR isArchive = 2) AND Category = 'Resolution'";

// Add a condition for the status filter if provided
$params = [];
$types = ""; // To track parameter types for prepared statement
if (!empty($statusFilter)) {
    $sql .= " AND d_status = ?";
    $params[] = $statusFilter;
    $types .= "s"; // Assuming d_status is a string. Use "i" if it's an integer.
}

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Bind parameters if needed
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// Execute the statement and fetch the results
$stmt->execute();
$result = $stmt->get_result();

// Handle the archiving logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_documents'])) {
    $selectedDocs = $_POST['selected_documents'];
    $placeholders = implode(',', array_fill(0, count($selectedDocs), '?'));

    // Update query for archiving selected documents
    $updateSql = "UPDATE documents SET isArchive = 1 WHERE id IN ($placeholders)";
    $updateStmt = $conn->prepare($updateSql);

    // Bind the parameters for the update query
    $updateStmt->bind_param(str_repeat('i', count($selectedDocs)), ...$selectedDocs);

    // Execute the update query
    if ($updateStmt->execute()) {
        $_SESSION['message'] = "Selected documents archived successfully!";
    } else {
        $_SESSION['message'] = "Failed to archive selected documents.";
    }

    // Redirect to avoid form resubmission
    header("Location: resolution.php");
    exit();
}

ob_end_flush(); // Flush output
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
/* Hide the print header on screen */
#print-header {
    display: none;
}

/* Print Styles */
@media print {
     /* Hide the columns: Category, Status, and Approved At */
     th:nth-child(5), td:nth-child(5), /* Category column */
    th:nth-child(6), td:nth-child(6), /* Status column */
    th:nth-child(7), td:nth-child(7)  /* Approved At column */ {
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

    /* Hide interactive elements and unnecessary parts */
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


    /* Hide the checkbox column */
    th:first-child, td:first-child {
        display: none;
    }

    /* Table styling for print */
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
                        <h1 class="m-0">Resolution Lists</h1>
                        <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info">
        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
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
<br>
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
    <p>Approved List of Resolution as of <strong><?php echo date('F d, Y H:i:s A'); ?></strong></p>
</div>
<form method="GET" action="resolution.php" id="filter-form">
    <div class="form-group">
        <label for="status_filter">Filter by Status:</label>
        <select id="status_filter" name="status_filter" class="form-control" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="Pending" <?php if ($statusFilter == "Pending") echo "selected"; ?>>Pending</option>
            <option value="First Reading" <?php if ($statusFilter == "First Reading") echo "selected"; ?>>First Reading</option>
            <option value="Second Reading" <?php if ($statusFilter == "Second Reading") echo "selected"; ?>>Second Reading</option>
            <option value="In Committee" <?php if ($statusFilter == "In Committee") echo "selected"; ?>>In Committee</option>
        </select>
    </div>
</form>
</div>


<div class="card-body">
        <div class="col-md-4"><button onclick="window.print();" class="btn btn-secondary" id="print-button">Print Report</button></div>
    <br>
<form action="resolution.php" method="POST" id="archive-form" enctype="multipart/form-data">
<table id="example1" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>Resolution No.</th>
            <th>Title</th>
            <th>Authored By</th>
            <th>Date Published</th>
            <th>Category</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql .= " ORDER BY (d_status = 'Pending') DESC, `date_published` DESC";

        // Prepare and execute query
        $stmt = $conn->prepare($sql);
        if (!empty($statusFilter)) {
            $stmt->bind_param("s", $statusFilter);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Skip rows where status is 'Approve' or 'Reject'
                if ($row['d_status'] === 'Approve' || $row['d_status'] === 'Reject') {
                    continue; // Skip this iteration (do not display this row)
                }
                echo "<tr>";
                echo "<td><input type='checkbox' name='selected_documents[]' value='" . $row['id'] . "' class='doc-checkbox'></td>";
                echo "<td><a href='document_info.php?id=" . urlencode($row["id"]) . "'>" . htmlspecialchars($row["resolution_no"]) . "</a></td>";
                echo "<td>" . htmlspecialchars($row["Title"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["Author"]) . "</td>";
                echo "<td>" . date('Y-m-d', strtotime($row["date_published"])) . "</td>";
                echo "<td>" . htmlspecialchars($row["Category"]) . "</td>";
                echo "<td>" . htmlspecialchars($row['d_status']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' class='text-center'>No documents found</td></tr>";
        }
        ?>
    </tbody>
</table>



    <button type="submit" class="btn btn-danger" id="archive-selected-btn" disabled>Archive Selected</button>
</form>

</div>
    </div>
        </div>
            </section>
                    </div>
                        </div>
                        </div>

<div class="modal fade" id="addDocumentModal" tabindex="-1" role="dialog" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-default">
            <div class="modal-header">
                <h5 class="modal-title" id="addDocumentModalLabel">Add New Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form action="adddocuments.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" id="description" name="description" required></textarea>
    </div>
    <div class="form-group">
        <label for="author">Author:</label>
        <input type="text" class="form-control" id="author" name="author" required>
    </div>

    <!-- Automatically set the current date and time -->
    <input type="hidden" name="date_published" value="<?php echo date('Y-m-d H:i:s'); ?>">

    <!-- Automatically set category to 'Resolution' -->
    <input type="hidden" name="category" value="Resolution">

    <div class="form-group">
        <label for="file_path">File: (valid file pdf, img, docx)</label>
        <input type="file" class="form-control" id="file_path" name="file_path" accept=".pdf, .docx, image/*" required>
    </div>
    <input type="hidden" name="user_id" value="1"> <!-- Set this dynamically based on the logged-in user -->
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add Document</button>
    </div>
</form>

            </div>
        </div>
    </div>
</div>


        <div id="edit-workorder-form" class="popup-form" style="display:none">
    <div class="form-content">
        <span class="close">&times;</span>
        <h2>Edit Document</h2>
        <form method="POST" name="edit_form" id="edit_form" action="updatedocuments_incoming.php" enctype="multipart/form-data">
            <div class="form-group" style="display:none">
                <label for="id">Document ID</label>
                <input type="text" id="edit_id" name="id" value="" readonly>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="edit_title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="edit_description" name="description" required>
            </div>
            <div class="form-group">
                <label for="Author">Author</label>
                <input type="text" id="edit_Author" name="author" required>
            </div>
            <div class="form-group">
                <label for="date-stamp">Date Published</label>
                <input type="datetime-local" id="edit_date-stamp" name="date-stamp" required>
            </div>
            <!-- Hidden input for Category -->
            <input type="hidden" id="edit_Documents-type" name="documents-type">
            
            <div class="form-group">
                <label for="Documents-status">Status</label>
                <select id="edit_Documents-status" name="documents-status" required>
                    <option value="First Reading">First Reading</option>
                    <option value="Second Reading">Second Reading</option>
                    <option value="In Committee">In Committee</option>
                    <option value="Approve">Approved</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="pdf-file">Attach PDF</label>
                <input type="file" id="edit_pdf-file" name="pdf-file" accept=".pdf">
            </div>
            <button type="submit" name="submit" class="submit-button">Submit</button>
        </form>
    </div>
</div> 
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
       
<script>
    // Show the Add Document if the button is clicked
document.getElementById("add-button").addEventListener("click", function() {
    document.getElementById("addform").style.display = "flex";  
});

document.querySelector("#addform .close").addEventListener("click", function() {
    document.getElementById("addform").style.display = "none";
});
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
    "buttons": ["copy", "csv", "excel"]
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