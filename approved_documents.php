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

    header("Location: approved_documents.php");
    exit();
}
ob_end_flush(); // Flush output

// Date filtering logic
$dateFilter = isset($_GET['date_filter']) ? $_GET['date_filter'] : '';
$currentDate = new DateTime();

$startDate = isset($_GET['start_date']) ? $_GET['start_date'] . ' 00:00:00' : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] . ' 23:59:59' : '';

$sql = "SELECT doc_no, Title, Author, `date_published`, Category, d_status, id, file_path, resolution_no, ordinance_no,
    (SELECT timestamp FROM document_timeline WHERE document_id = documents.id AND action = 'Approve' ORDER BY timestamp DESC LIMIT 1) AS timeline_approval_timestamp, approval_timestamp
    FROM documents 
    WHERE (isArchive = 0 OR isArchive = 2) AND Category IN ('Resolution', 'Ordinance') AND d_status = 'Approve'";

// Add date filter condition if both start and end dates are provided
$params = [];
$types = '';

if (!empty($startDate) && !empty($endDate)) {
    $sql .= " AND approval_timestamp BETWEEN ? AND ?";
    $params[] = $startDate;
    $params[] = $endDate;
    $types .= 'ss';
}

// Order results by approval_timestamp descending
$sql .= " ORDER BY approval_timestamp DESC";

// Prepare and execute the query
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Documents</title>
    <script src="assets/jquery-3.7.1.js"></script>
    <script src="assets/dataTables.js"></script>
    <link rel="stylesheet" href="assets/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
#print-header,
#print-footer,
#row-count,
#print-date {
    display: none; 
}

@media print {

    #print-header,
    #print-footer {
        display: block;
    }

    /* Hide the columns: Category, Status, and Approved At */
    th:nth-child(5), td:nth-child(5), /* Category column */
    th:nth-child(6), td:nth-child(6), /* Status column */
    th:nth-child(7), td:nth-child(7)  /* Approved At column */ {
        display: none;
    }

    /* Row count and print date styles */
    #row-count {
        display: block;
        margin-top: 10px;
        text-align: left;
        font-weight: bold;
        float: left; /* Align to the left under the table */
    }

    #print-date {
        display: block;
        position: absolute;
        bottom: 20px; /* Adjust for spacing from the bottom of the paper */
        right: 20px; /* Adjust for spacing from the right edge of the paper */
        font-weight: bold;
        font-size: 15px; /* Adjust size as needed */
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

    #filter-form,
    #print-button,
    .dataTables_filter,
    .dataTables_length,
    .dataTables_info,
    .dataTables_paginate,
    .btn,
    input[type="checkbox"] {
        display: none !important;
    }

    /* Hide the checkbox column */
    th:first-child,
    td:first-child {
        display: none !important;
    }

    /* Ensure the footer does not overlap the table */
    #print-footer {
        clear: both;
        margin-top: 20px; /* Add space between the table and footer */
        width: 100%;
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
                        
                        <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info">
        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Approved Documents</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">

                    <?php
// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Retrieve and format start and end dates from the GET request
$start_date = isset($_GET['start_date']) ? date('F d, Y', strtotime($_GET['start_date'])) : null;
$end_date = isset($_GET['end_date']) ? date('F d, Y', strtotime($_GET['end_date'])) : null;

// Determine the display date based on the filter
if ($start_date && $end_date) {
    $display_date = "Approved List as of $start_date to $end_date";
} elseif ($start_date) {
    $display_date = "Approved List as of $start_date";
} elseif ($end_date) {
    $display_date = "Approved List as of up to $end_date";
} else {
    $display_date = "Approved List as of " . date('F d, Y');
}
?>
<center>
<div id="print-header">
    <h2 style="font-family: 'Georgia, serif', Times, serif; font-size: 20px; font-weight: bold; text-align: center;"><strong>
        Republic of Philippines
    </strong></h2>
    <h3>Province of Cagayan</h3>
    <p>Municipality of Solana</p>
    <br>
    <img src="image/LOGO1.png" alt="Logo" style="width: 100px; height: auto;">
    <br><br>
    <h3><strong>OFFICE OF THE SANGGUNIANG KABATAAN</strong></h3>
    <br>
    <p><strong><?php echo $display_date; ?></strong></p>
</div>
</center>
<h3 class="card-title">Approved Documents</h3>
</div>
<div class="card-body">
<form method="GET" id="filter-form" class="mb-3">
    <div class="form-row align-items-center d-flex">
        <!-- Date Range Filter -->
        <div class="col-md-2 mb-2 mb-md-0">
            <input type="date" name="start_date" id="start-date" class="form-control" value="<?php echo htmlspecialchars($_GET['start_date'] ?? ''); ?>">
        </div>
        <div class="col-md-2 mb-2 mb-md-0">
            <input type="date" name="end_date" id="end-date" class="form-control" value="<?php echo htmlspecialchars($_GET['end_date'] ?? ''); ?>">
        </div>

        <!-- Filter Button -->
        <div class="col-md-1 mb-2 mb-md-0">
            <button type="submit" class="btn btn-primary w-100">Filter Date</button>
        </div>

        <!-- Reset Button -->
        <div class="col-md-1 mb-2 mb-md-0">
            <a href="Approved_documents.php" class="btn btn-warning w-100">Clear Filter</a>
        </div>

        <!-- Print Button -->
        <div class="col-md-1 mb-1 mb-md-0">
            <button onclick="window.print();" class="btn btn-secondary w-100" id="print-button">Print Report</button>
        </div>
    </div>
</form>
    <!-- Table Form -->
    <form action="approved_documents.php" method="POST" id="archive-form" enctype="multipart/form-data">
  <table class="table table-bordered table-striped" id="resolutionTable">
    <thead>
      <tr>
        <th><input type="checkbox" id="select-all"></th>
        <th>Document No.</th>
        <th>Title</th>
        <th>Author</th>
        <th>Category</th>
        <th>Status</th>
        <th>Approved at</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td><input type='checkbox' name='selected_documents[]' value='" . $row['id'] . "' class='doc-checkbox'></td>";

              $docNumber = '';
              if ($row["Category"] == 'Resolution' && !empty($row["resolution_no"])) {
                  $docNumber = htmlspecialchars($row["resolution_no"]);
              } elseif ($row["Category"] == 'Ordinance' && !empty($row["ordinance_no"])) {
                  $docNumber = htmlspecialchars($row["ordinance_no"]);
              }

              echo "<td><a href='document_info.php?id=" . urlencode($row["id"]) . "'>" . $docNumber . "</a></td>";
              echo "<td>" . htmlspecialchars($row["Title"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["Author"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["Category"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["d_status"]) . "</td>";
              echo "<td>" . ($row['approval_timestamp'] ? date('F d, Y H:i:s A', strtotime($row['approval_timestamp'])) : 'Not Approved Yet') . "</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='7' class='text-center'>No approved documents found</td></tr>";
      }
      ?>
    </tbody>
  </table>
</form>
<button type="submit" class="btn btn-danger" id="archive-selected-btn" disabled>Archive Selected</button>
<div id="print-footer">
    <p id="row-count">Total rows: <?php echo $result->num_rows; ?></p>
    
</div>

</div>
</div>
    </div>
        </div>
            </section>
                    </div>
                        </div>
                        <div id="print-footer">
   
    <p id="print-date">Printed on: <?php echo date('F d, Y H:i:s A'); ?></p>
</div>

                        <script>
document.addEventListener('DOMContentLoaded', function () {
    // Get the table element
    const table = document.querySelector('#resolutionTable');
    const rowCountElement = document.querySelector('#row-count');
    const printDateElement = document.querySelector('#print-date');

    // Get the number of rows in the table body
    const rowCount = table.querySelectorAll('tbody tr').length;

    // Set the row count text
    if (rowCountElement) {
        rowCountElement.textContent = Total Rows: ${rowCount};
    }

    // Set the current print date and time
    if (printDateElement) {
        const currentDate = new Date();
        const formattedDate = currentDate.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
        const formattedTime = currentDate.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        });
        printDateElement.textContent = Printed on: ${formattedDate} at ${formattedTime};
    }
});
</script>

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
</script>
       
<script>
  
document.getElementById("add-button").addEventListener("click", function() {
    document.getElementById("addform").style.display = "flex";  
});

document.querySelector("#addform .close").addEventListener("click", function() {
    document.getElementById("addform").style.display = "none";
});
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
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

  $("#resolutionTable").DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        order: [[3, 'desc']], // Sort by 'Approved at' column
        columnDefs: [
            {
                targets: [3], // 'Approved at' column
                type: "date",
                render: function (data, type, row) {
                    return data; // Return the date as it is
                }
            }
        ],
        buttons: ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#resolutionTable_wrapper .col-md-6:eq(0)');

    // Customize DataTables search bar placement
    $('#resolutionTable_wrapper .dataTables_filter').css({
        'float': 'right',
        'margin-top': '-50px', // Align with filters
        'text-align': 'right'
    });

    $('#resolutionTable_wrapper .dataTables_filter input').css({
        'width': '300px',
        'margin-left': '5px'
    });
});
</script>
</body>
</html>