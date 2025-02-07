<?php
session_start();
ob_start();

if (!isset($_SESSION['userid'])) {
    header("location:login.php");
    exit();
}
if (!isset($_SESSION['username'])) {
    $_SESSION['username'] = 'Guest';
}

include('config.php');
include('./includes/user/user_navbar.php');
include('./includes/user/user_sidebar.php');

if (isset($_POST['restore']) && (!empty($_POST['resolution_no']) || !empty($_POST['ordinance_no']))) {
    // Check if resolution_no or ordinance_no is set
    $resolution_no = !empty($_POST['resolution_no']) ? trim($_POST['resolution_no']) : null;
    $ordinance_no = !empty($_POST['ordinance_no']) ? trim($_POST['ordinance_no']) : null;

    try {
        // Determine the type of document to restore
        $stmt = null;
        if ($resolution_no) {
            $stmt = $conn->prepare("UPDATE documents SET isArchive = 0 WHERE resolution_no = ?");
            $stmt->bind_param('s', $resolution_no);
        } elseif ($ordinance_no) {
            $stmt = $conn->prepare("UPDATE documents SET isArchive = 0 WHERE ordinance_no = ?");
            $stmt->bind_param('s', $ordinance_no);
        }

        if ($stmt && $stmt->execute()) {
            $_SESSION['message'] = "Document restored successfully!";
        } else {
            $_SESSION['message'] = "Failed to restore the document.";
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "Error: " . $e->getMessage();
    }

    header("Location: user_archive_documents.php");
    exit();
}

$sql = "SELECT * FROM documents WHERE isArchive = 1 ORDER BY date_published DESC"; // Correct column for date_published
$result = mysqli_query($conn, $sql);
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
                            <li class="breadcrumb-item"><a href="user_dashboard.php">Home</a></li>
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
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                $docNumber = $row['Category'] == 'Resolution' ? $row['resolution_no'] : $row['ordinance_no'];
                echo "<td>" . htmlspecialchars($docNumber) . "</td>";
                echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Author']) . "</td>";
                echo "<td>" . date('Y-m-d', strtotime($row['date_published'])) . "</td>";  // Updated column name
                echo "<td>" . htmlspecialchars($row['Category']) . "</td>";
                echo "<td>" . htmlspecialchars($row['d_status']) . "</td>";
                echo "<td>" . ($row['approval_timestamp'] ? date('Y-m-d H:i', strtotime($row['approval_timestamp'])) : 'N/A') . "</td>";
                echo "<td>
                    <form action='user_archive_documents.php' method='POST'>
                        <input type='hidden' name='" . ($row['resolution_no'] ? 'resolution_no' : 'ordinance_no') . "' value='" . htmlspecialchars($docNumber) . "'>
                        <button type='submit' name='restore' class='btn btn-success btn-sm'>Restore</button>
                    </form>
                  </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='9' class='text-center'>No archived documents found.</td></tr>";
        }
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
    $("#archivedDocs").DataTable({
        responsive: true,
        autoWidth: false,
        order: [[3, 'desc']]
    });
    $('#select-all').click(function() {
        $('.doc-checkbox').prop('checked', this.checked);
    });
});
</script>
</body>
</html>
