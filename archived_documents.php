<?php
ob_start(); // Start output buffering
session_start();

include('config.php');
include('./includes/navbar.php');
include('./includes/sidebar.php');


if (isset($_POST['restore']) && isset($_POST['resolution_no'])) {
    $resolution_no = trim($_POST['resolution_no']);
    
    if (!empty($resolution_no)) {
        $stmt = $conn->prepare("UPDATE documents SET isArchive = 0 WHERE resolution_no = ?");
        $stmt->bind_param('s', $resolution_no);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Document restored successfully!";
        } else {
            $_SESSION['message'] = "Failed to restore document.";
        }
    } else {
        $_SESSION['message'] = "Invalid resolution number.";
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
                    <table id="archivedDocs" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Resolution No</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Date Published</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch archived documents
                            $sql = "SELECT resolution_no, Title, Author, `Date Published`, Category, d_status 
                                    FROM documents 
                                    WHERE isArchive = 1 
                                    ORDER BY `Date Published` DESC";

                            $result = mysqli_query($conn, $sql);

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row["resolution_no"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["Title"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["Author"]) . "</td>";
                                    echo "<td>" . date('Y-m-d', strtotime($row["Date Published"])) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["Category"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["d_status"]) . "</td>";
                                    echo "<td>
    <form action='archived_documents.php' method='POST'>
        <input type='hidden' name='resolution_no' value='" . htmlspecialchars($row['resolution_no']) . "'>
        <button type='submit' name='restore' class='btn btn-success btn-sm'>Restore</button>
    </form>
</td>";

                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No archived documents found</td></tr>";
                            }

                            mysqli_close($conn);
                            ?>
                        </tbody>
                    </table>
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
