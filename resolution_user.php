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

// query para sa mga approved resolutions
$sql = "SELECT * FROM documents WHERE user_id = ? AND Category = 'Resolution' AND d_status = 'Approve'";
$params = [$uid];
$types = "i";

// Date filtering - use custom start_date and end_date if available
if (!empty($startDate) && !empty($endDate)) {
    $startDate = date('Y-m-d', strtotime($startDate)) . ' 00:00:00'; // Set time to 00:00:00 for start date
    $endDate = date('Y-m-d', strtotime($endDate)) . ' 23:59:59'; // Set time to 23:59:59 for end date
    $sql .= " AND `approval_timestamp` BETWEEN ? AND ?";
    $params[] = $startDate;
    $params[] = $endDate;
    $types .= "ss"; // Add two string parameters (start and end date)
}

$sql .= " ORDER BY `approval_timestamp` DESC";

// Prepare and execute the SQL statement
$stmt = $conn->prepare($sql);
if ($stmt) {
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
/* Hide the print header on screen */
#print-header {
    display: none;
}

/* Print Styles */
@media print {
     /* Hide the columns: Approved on and Status */
     th:nth-child(4), td:nth-child(4), /* Approved on column */
     th:nth-child(5), td:nth-child(5)  /* Status column */ {
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
                    <h1 class="m-0">Resolutions List</h1>
                        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                            <div class="alert alert-success" role="alert">
                                Resolution successfully added!
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
        <!-- <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addDocumentModal">
    Add New Resolution
</button> -->
<?php
// Set the timezone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Retrieve and format start and end dates from the GET request
$start_date = isset($_GET['start_date']) ? date('F d, Y', strtotime($_GET['start_date'])) : null;
$end_date = isset($_GET['end_date']) ? date('F d, Y', strtotime($_GET['end_date'])) : null;

// Determine the display date based on the filter
if ($start_date && $end_date) {
    $display_date = "Approved List as of Resolutions $start_date to $end_date";
} elseif ($start_date) {
    $display_date = "Approved List as of Resolutions $start_date";
} elseif ($end_date) {
    $display_date = "Approved List as of Resolutions up to $end_date";
} else {
    $display_date = "Approved List as of Resolutions " . date('F d, Y');
}
?>
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
            <a href="resolution_user.php" class="btn btn-warning w-100">Clear Filter</a>
        </div>

        <!-- Print Button -->
        <div class="col-md-1 mb-1 mb-md-0">
            <button onclick="window.print();" class="btn btn-secondary w-100" id="print-button">Print Report</button>
        </div>
    </div>
</form>


<table class="table table-bordered table-striped" id="resolutionTable">
    <thead>
        <tr>
            
            <th>Resolution No.</th>
            <th>Title</th>
            <th>Authored By</th>
            <th>Approved on</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr data-id='" . htmlspecialchars($row["id"]) . "'>";
                
                echo "<td><a href='user_document_info.php?id=" . urlencode($row["id"]) . "'>" . htmlspecialchars($row["resolution_no"]) . "</a></td>";
                echo "<td class='editable' data-column='Title'>" . htmlspecialchars($row["Title"]) . "</td>";
                echo "<td class='editable' data-column='Author'>" . htmlspecialchars($row["Author"]) . "</td>";
                echo "<td>" . date('F d, Y h:i:s A', strtotime($row["approval_timestamp"])) . "</td>";
                echo "<td>" . htmlspecialchars($row["d_status"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>No documents found</td></tr>";
        }
        ?>
    </tbody>
</table>
<button type="submit" class="btn btn-danger" id="archive-selected-btn" disabled>Archive Selected</button>


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
</div>
<script>

    document.addEventListener('DOMContentLoaded', () => {
        const now = new Date().toISOString().slice(0, 16);
        document.getElementById('date_published').value = now;
    });
</script>
<script>
    function printTable() {

    document.getElementById('print-header').style.display = 'block';


    var printContent = document.getElementById('example1').outerHTML;
    

    var newWindow = window.open('', '', 'height=500,width=800');
    

    newWindow.document.write('<html><head><title>Print Report</title>');

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
  $("#resolutionTable").DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        order: [[3, 'desc']], // Sort by 'Approved on' column
        columnDefs: [
            {
                targets: [3], // 'Approved on' column
                type: "date",
                render: function (data, type, row) {
                    return data; // Return the date
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
<?php
$stmt->close();
$conn->close();
?>