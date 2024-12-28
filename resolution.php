<?php
session_start();
ob_start(); // output buffering
include('config.php');
include('./includes/navbar.php');
include('./includes/sidebar.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_documents'])) {
    $selectedDocs = $_POST['selected_documents'];
    $placeholders = implode(',', array_fill(0, count($selectedDocs), '?'));

    $sql = "UPDATE documents SET isArchive = 1 WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param(str_repeat('i', count($selectedDocs)), ...$selectedDocs);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Selected documents archived successfully!";
    } else {
        $_SESSION['message'] = "Failed to archive selected documents.";
    }

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
    /* Show the print header */
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

    /* Remove table row hover effect */
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

<div id="print-header">
<h3 style="font-family: 'Georgia, serif', Times, serif; font-size: 20px; font-weight: bold; text-align: center;">
    Republic of Philippines
</h3>
<p>Province of Cagayan</p>
<p>Municipality of Solana</p>
    <img src="image/LOGO1.png" alt="Logo" style="width: 100px; height: auto;">
    
<strong>OFFICE OF THE SANGGUNIANG KABATAAN</strong>
</div>

</div>
<div class="card-body">
     <!-- Status Filtering -->
     <!-- Combined Status and Date Filtering Form -->
     <form method="GET" id="filter-form" class="mb-3">
    <div class="form-row">
        <!-- Status Filtering -->
        <div class="col-md-4">
            <label for="status-filter">Filter by Status:</label>
            <select name="status_filter" id="status-filter" class="form-control" style="width: 200px; display: inline-block;">
                <option value="">All</option>
                <option value="Pending" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] === 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="First Reading" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] === 'First Reading') echo 'selected'; ?>>First Reading</option>
                <option value="Second Reading" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] === 'Second Reading') echo 'selected'; ?>>Second Reading</option>
                <option value="In Committee" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] === 'In Committee') echo 'selected'; ?>>In Committee</option>
            </select>
          
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary mt-0">Filter</button>
        </div>
        <!-- Date Filtering -->
        <!-- <div class="col-md-4">
            <label for="start-date">Filter by Date:</label>
            <input type="date" name="start_date" id="start-date" class="form-control" style="width: 200px; display: inline-block;" 
                value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
            <span>to</span>
            <input type="date" name="end_date" id="end-date" class="form-control" style="width: 200px; display: inline-block;"
                value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
        </div> -->
        

        <!-- Submit Button -->
       
        <!-- <button onclick="window.print();" class="btn btn-secondary" id="print-button">Print Report</button> -->
        
    </div>
</form>


<form action="resolution.php" method="POST" id="archive-form" enctype="multipart/form-data">
<div class="table-responsive">
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
        // Get selected status filter
        $statusFilter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

        // Prepare SQL query with approval timestamp
        $sql = "SELECT doc_no, Title, Author, `Date Published`, Category, d_status, id, file_path, resolution_no,
                (SELECT timestamp FROM document_timeline WHERE document_id = documents.id AND action = 'Approve' ORDER BY timestamp DESC LIMIT 1) AS approval_time
                FROM documents 
                WHERE isArchive = 0 AND Category = 'Resolution' AND d_status IN ('Pending', 'First Reading', 'Second Reading', 'In Committee')";

        // Add status filter condition if set
        if (!empty($statusFilter)) {
            $sql .= " AND d_status = ?";
        }

        // Add date filter condition if set
        if (!empty($startDate) && !empty($endDate)) {
            $sql .= " AND `Date Published` BETWEEN ? AND ?";
        } elseif (!empty($startDate)) {
            $sql .= " AND `Date Published` >= ?";
        } elseif (!empty($endDate)) {
            $sql .= " AND `Date Published` <= ?";
        }

        // Order results
        $sql .= " ORDER BY (d_status = 'Pending') DESC, `Date Published` DESC";

        // Prepare and execute query
        $stmt = $conn->prepare($sql);

        // Bind parameters
        if (!empty($statusFilter) && !empty($startDate) && !empty($endDate)) {
            $stmt->bind_param("sss", $statusFilter, $startDate, $endDate);
        } elseif (!empty($statusFilter) && !empty($startDate)) {
            $stmt->bind_param("ss", $statusFilter, $startDate);
        } elseif (!empty($statusFilter) && !empty($endDate)) {
            $stmt->bind_param("ss", $statusFilter, $endDate);
        } elseif (!empty($startDate) && !empty($endDate)) {
            $stmt->bind_param("ss", $startDate, $endDate);
        } elseif (!empty($statusFilter)) {
            $stmt->bind_param("s", $statusFilter);
        } elseif (!empty($startDate)) {
            $stmt->bind_param("s", $startDate);
        } elseif (!empty($endDate)) {
            $stmt->bind_param("s", $endDate);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $isApproved = $row['d_status'] === 'Approved' ? 'disabled' : '';

                echo "<tr>";
                echo "<td><input type='checkbox' name='selected_documents[]' value='" . $row['id'] . "' class='doc-checkbox' $isApproved></td>";
                echo "<td><a href='document_info.php?id=" . urlencode($row["id"]) . "'>" . htmlspecialchars($row["resolution_no"]) . "</a></td>";
                echo "<td>" . htmlspecialchars($row["Title"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["Author"]) . "</td>";
                echo "<td>" . date('Y-m-d', strtotime($row["Date Published"])) . "</td>";
                echo "<td>" . htmlspecialchars($row["Category"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["d_status"]) . "</td>";
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
                    <option value="Approved">Approved</option>
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