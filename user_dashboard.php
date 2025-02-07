<?php
// Ensure the user is logged in
session_start();
if (!isset($_SESSION['userid'])) {
    header("location:login.php");
    exit;
}
include('./includes/user/user_navbar.php');
include('./includes/user/user_sidebar.php');
// Database connection
include('config.php');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$userId = $_SESSION['userid'];

// Initialize count variables
$pending_count = 0;
$on_process_count = 0;
$approved_count = 0;
$rejected_count = 0;

// Query for Pending Documents
$query_pending = "SELECT COUNT(*) AS count FROM documents WHERE user_id = $userId AND d_status = 'Pending' AND isArchive = 0";
$result_pending = mysqli_query($conn, $query_pending);
if ($result_pending) {
    $row = mysqli_fetch_assoc($result_pending);
    $pending_count = $row['count'];
}

// Query for On Process Documents
$query_on_process = "SELECT COUNT(*) AS count FROM documents WHERE user_id = $userId AND d_status = 'On Process' AND isArchive = 0";
$result_on_process = mysqli_query($conn, $query_on_process);
if ($result_on_process) {
    $row = mysqli_fetch_assoc($result_on_process);
    $on_process_count = $row['count'];
}

// Query for Approved Documents
$query_approved = "SELECT COUNT(*) AS count FROM documents WHERE user_id = $userId AND d_status = 'Approved' AND isArchive = 0";
$result_approved = mysqli_query($conn, $query_approved);
if ($result_approved) {
    $row = mysqli_fetch_assoc($result_approved);
    $approved_count = $row['count'];
}

// Query for Rejected Documents
$query_rejected = "SELECT COUNT(*) AS count FROM documents WHERE user_id = $userId AND d_status = 'Rejected' AND isArchive = 0";
$result_rejected = mysqli_query($conn, $query_rejected);
if ($result_rejected) {
    $row = mysqli_fetch_assoc($result_rejected);
    $rejected_count = $row['count'];
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LTAS | Admin Dashboard</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="assets/plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="assets/plugins/summernote/summernote-bs4.min.css">
  <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">


  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="image/logo1.png" alt="LTAS LOGO" height="60" width="60">
  </div>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
              <li class="breadcrumb-item active">User Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
        <!-- Pending Documents -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-info">
        <div class="inner">
            <h3><?php echo $pending_count; ?></h3>
            <p>Pending Documents</p>
        </div>
        <div class="icon">
            <i class="ion ion-ios-timer"></i>
        </div>
    </div>
</div>

<!-- On Process Documents -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-secondary">
        <div class="inner">
            <h3><?php echo $on_process_count; ?></h3>
            <p>On Process Documents</p>
        </div>
        <div class="icon">
            <i class="ion ion-ios-cog"></i>
        </div>
    </div>
</div>

<!-- Approved Documents -->
<div class="col-lg-3 col-6">
    <div class="small-box bg-success">
        <div class="inner">
            <h3><?php echo $approved_count; ?></h3>
            <p>Approved Documents</p>
        </div>
        <div class="icon">
            <i class="ion ion-ios-thumbs-up"></i>
        </div>

              <!-- <a href="./resolution_user.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>

          <!-- Rejected Documents -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $rejected_count; ?></h3>
                <p>Rejected Documents</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-thumbs-down"></i>
              </div>
              <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
          </div>
        </div>
      </div>
    </section>
   
    <div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Recently Submitted Documents Overview</h3>

        <div class="card-tools">
          <div class="input-group input-group-sm" style="width: 150px;">
            <input type="text" name="table_search" id="table_search" class="form-control float-right" placeholder="Search" onkeyup="searchTable()">

            <div class="input-group-append">
              <button type="button" class="btn btn-default">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </div>

      <!-- /.card-header -->

      <div class="card-body table-responsive p-0">
      <table class="table table-hover text-nowrap" id="documentTable">
    <thead>
        <tr>
            <th>Document Number</th>
            <th>Title</th>
            <th>Status</th>
            <th>Category</th>
            <th>Date Submitted</th>
            <th>Attachment</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $userId = $_SESSION['userid'];
        $query = "SELECT d.doc_no, d.title, d.date_published, d.d_status, d.resolution_no, d.ordinance_no, d.file_path, c.name AS Category 
                  FROM documents d 
                  JOIN categories c ON d.category_id = c.id 
                  WHERE d.user_id = $userId AND d.isArchive = 0";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Determine which document number to display
                $documentNumber = '';
                if ($row['Category'] == 'Resolution') {
                    $documentNumber = $row['resolution_no'];
                } elseif ($row['Category'] == 'Ordinance') {
                    $documentNumber = $row['ordinance_no'];
                } else {
                    $documentNumber = $row['doc_no']; // Default fallback
                }

                // Add conditional class for status
                $statusClass = '';
                if ($row['d_status'] == 'Approve') {
                    $statusClass = 'badge badge-success';
                } elseif ($row['d_status'] == 'Denied') {
                    $statusClass = 'badge badge-danger';
                } else {
                    $statusClass = 'badge badge-secondary';
                }

                // Output the table row
                echo "<tr>
                        <td>{$documentNumber}</td>
                        <td>{$row['title']}</td>
                        <td><span class='{$statusClass}'>{$row['d_status']}</span></td>
                        <td>{$row['Category']}</td>
                        <td>{$row['date_published']}</td>
                        <td>
                            <a href='{$row['file_path']}' class='btn btn-sm btn-primary' target='_blank'>View</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>No documents submitted yet.</td></tr>";
        }
        ?>
    </tbody>
</table>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>

<script>
  // Search functionality for table
  function searchTable() {
    let input = document.getElementById("table_search");
    let filter = input.value.toLowerCase();
    let table = document.getElementById("documentTable");
    let rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the table header
      let cells = rows[i].getElementsByTagName("td");
      let rowMatches = false;

      for (let j = 0; j < cells.length; j++) {
        if (cells[j].innerText.toLowerCase().includes(filter)) {
          rowMatches = true;
          break;
        }
      }
      rows[i].style.display = rowMatches ? "" : "none";
    }
  }
</script>

  </div>
  </div>
</div>






   
    <script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
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
<script src="assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
</body>
</html>