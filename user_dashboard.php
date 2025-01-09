<?php
session_start();

if(!isset($_SESSION['userid'])) {
    header("location:login.php");
}

if (!isset($_SESSION['username'])) {
  $_SESSION['username'] = 'Guest'; 
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('config.php');
include('./includes/user/user_navbar.php');
include('./includes/user/user_sidebar.php');
// include('adddocuments.php');
// include('deletedocument.php');
// Get the logged-in user's ID
$user_id = $_SESSION['userid'];

// Queries to count document statuses for the current user
$query_pending = "SELECT COUNT(*) as pending_count FROM documents WHERE d_status = 'Pending' AND user_id = ?";
$query_approved = "SELECT COUNT(*) as approved_count FROM documents WHERE d_status = 'Approve' AND user_id = ?";
$query_rejected = "SELECT COUNT(*) as rejected_count FROM documents WHERE d_status = 'Reject' AND user_id = ?";
$query_on_process = "SELECT COUNT(*) as on_process_count FROM documents WHERE d_status IN ('First Reading', 'In Committee', 'Second Reading') AND user_id = ?";

// Prepare and execute the queries
$stmt_pending = $conn->prepare($query_pending);
$stmt_pending->bind_param("i", $user_id);
$stmt_pending->execute();
$result_pending = $stmt_pending->get_result();
$pending_count = $result_pending->fetch_assoc()['pending_count'];

$stmt_approved = $conn->prepare($query_approved);
$stmt_approved->bind_param("i", $user_id);
$stmt_approved->execute();
$result_approved = $stmt_approved->get_result();
$approved_count = $result_approved->fetch_assoc()['approved_count'];

$stmt_rejected = $conn->prepare($query_rejected);
$stmt_rejected->bind_param("i", $user_id);
$stmt_rejected->execute();
$result_rejected = $stmt_rejected->get_result();
$rejected_count = $result_rejected->fetch_assoc()['rejected_count'];

$stmt_on_process = $conn->prepare($query_on_process);
$stmt_on_process->bind_param("i", $user_id);
$stmt_on_process->execute();
$result_on_process = $stmt_on_process->get_result();
$on_process_count = $result_on_process->fetch_assoc()['on_process_count'];

$stmt_pending->close();
$stmt_approved->close();
$stmt_rejected->close();
$stmt_on_process->close();
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
              <!-- <a href="./sent_document_user.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
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
              <!-- <a href="./ordinance_sent_document.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
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
                        $query = "SELECT * FROM documents WHERE user_id = $userId AND isArchive = 0";
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

                                echo "<tr>
                                        <td>{$documentNumber}</td>
                                        <td>{$row['Title']}</td>
                                        <td><span class='{$statusClass}'>{$row['d_status']}</span></td>
                                        <td>{$row['Category']}</td>
                                        <td>{$row['Date Published']}</td>
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