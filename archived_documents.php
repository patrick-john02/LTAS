<?php
ob_start(); // Start output buffering
session_start();

include('config.php');
include('./includes/navbar.php');
include('./includes/sidebar.php');

if (isset($_POST['restore']) && (isset($_POST['resolution_no']) || isset($_POST['ordinance_no']))) {
    // Check if resolution_no or ordinance_no is set for restoration
    $resolution_no = isset($_POST['resolution_no']) ? trim($_POST['resolution_no']) : null;
    $ordinance_no = isset($_POST['ordinance_no']) ? trim($_POST['ordinance_no']) : null;

    // If resolution_no is provided, restore it
    if ($resolution_no) {
        if (!empty($resolution_no)) {
            $stmt = $conn->prepare("UPDATE documents SET isArchive = 0 WHERE resolution_no = ?");
            $stmt->bind_param('s', $resolution_no);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Document with Resolution No. $resolution_no restored successfully!";
            } else {
                $_SESSION['message'] = "Failed to restore document with Resolution No. $resolution_no.";
            }
        } else {
            $_SESSION['message'] = "Invalid Resolution number.";
        }
    }

    // If ordinance_no is provided, restore it
    if ($ordinance_no) {
        if (!empty($ordinance_no)) {
            $stmt = $conn->prepare("UPDATE documents SET isArchive = 0 WHERE ordinance_no = ?");
            $stmt->bind_param('s', $ordinance_no);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Document with Ordinance No. $ordinance_no restored successfully!";
            } else {
                $_SESSION['message'] = "Failed to restore document with Ordinance No. $ordinance_no.";
            }
        } else {
            $_SESSION['message'] = "Invalid Ordinance number.";
        }
    }

    header("Location: archived_documents.php");
    exit();
}

ob_end_flush(); // Send output to browser
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Document Lists</title>
    <script src="assets/jquery-3.7.1.js"></script>
    <script src="assets/dataTables.js"></script>
    <link rel="stylesheet" href="assets/dataTables.dataTables.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
    <!-- Custom Modal Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Archived Document Lists</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Archives</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card">
             
                <div class="card-body">
                <div class="table-responsive">
                <table id="archivedDocs" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Document No</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Date Published</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Approval Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch archived documents
                        $sql = "SELECT resolution_no, ordinance_no, Title, Author, `Date Published`, Category, d_status, approval_timestamp 
                                FROM documents 
                                WHERE isArchive = 1
                                ORDER BY `Date Published` DESC";

                        $result = mysqli_query($conn, $sql);

                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' name='selected_documents[]' value='" . $row['resolution_no'] . "' class='doc-checkbox'></td>";
                                
                                // Combine resolution_no and ordinance_no based on the category
                                $docNumber = '';
                                if ($row["Category"] == 'Resolution' && !empty($row["resolution_no"])) {
                                    $docNumber = htmlspecialchars($row["resolution_no"]);
                                } elseif ($row["Category"] == 'Ordinance' && !empty($row["ordinance_no"])) {
                                    $docNumber = htmlspecialchars($row["ordinance_no"]);
                                }

                                echo "<td><a href='document_info.php?id=" . urlencode($row["resolution_no"]) . "'>" . $docNumber . "</a></td>";
                                echo "<td>" . htmlspecialchars($row["Title"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["Author"]) . "</td>";
                                echo "<td>" . date('Y-m-d', strtotime($row["Date Published"])) . "</td>";
                                echo "<td>" . htmlspecialchars($row["Category"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["d_status"]) . "</td>";
                                
                                // Display approval timestamp
                                echo "<td>" . ($row['approval_timestamp'] ? date('F d, Y H:i:s A', strtotime($row['approval_timestamp'])) : 'Not Approved Yet') . "</td>";
                                
                               // Restore action
echo "<td>";
echo "<form action='archived_documents.php' method='POST'>";

// Check if resolution_no exists, use it if available, otherwise check for ordinance_no
if (!empty($row['resolution_no'])) {
    echo "<input type='hidden' name='resolution_no' value='" . htmlspecialchars($row['resolution_no']) . "'>";
} elseif (!empty($row['ordinance_no'])) {
    echo "<input type='hidden' name='ordinance_no' value='" . htmlspecialchars($row['ordinance_no']) . "'>";
}

echo "<button type='submit' name='restore' class='btn btn-success btn-sm'>Restore</button>";
echo "</form>";
echo "</td>";
echo "</tr>";

                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>No archived documents found</td></tr>";
                        }

                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        </div>
    </section>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Include Toastr JS -->
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
    <!-- Scripts -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/plugins/jszip/jszip.min.js"></script>
    <script src="assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    
    <script>
$(document).ready(function() {
    $("#example1, #archivedDocs").DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[3, 'desc']] // Sort by 'Date Published'
    });
    // Enable Archive button based on selections
    $('#select-all').on('click', function() {
        $('.doc-checkbox').prop('checked', this.checked);
        toggleArchiveButton();
    });

    $('.doc-checkbox').on('change', function() {
        toggleArchiveButton();
    });

    function toggleArchiveButton() {
        const hasSelection = $('.doc-checkbox:checked').length > 0;
        $('#archive-selected-btn').prop('disabled', !hasSelection);
    }
});
</script>
</body>
</html>
