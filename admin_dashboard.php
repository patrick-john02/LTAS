<?php
session_start();

if (!isset($_SESSION['userid'])) {
  header("location:login_admin.php");
}

date_default_timezone_set('Asia/Manila');

include('config.php');

include('./includes/navbar.php');
include('./includes/sidebar.php');

// get the admin count overview
$admin_count = 0;
$query = "SELECT COUNT(*) as count FROM admin";
if ($stmt = $conn->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($admin_count);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Error preparing statement for admin count: " . $conn->error;
}
// get user count overview
$user_count = 0;
$query = "SELECT COUNT(*) as count FROM users";
if ($stmt = $conn->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($user_count);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Error preparing statement for user count: " . $conn->error;
}
// get approved document count overview
$approved_count = 0;
$query = "SELECT COUNT(*) as count FROM documents WHERE d_status = 'Approve'";
if ($stmt = $conn->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($approved_count);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Error preparing statement for approved documents: " . $conn->error;
}
// get pending document count overview
$rejected_count = 0;
$query = "SELECT COUNT(*) as count FROM documents WHERE d_status = 'Reject'";
if ($stmt = $conn->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($rejected_count);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Error preparing statement for pending documents: " . $conn->error;
}

$new_documents_today = [];
$today_date = date('Y-m-d'); //time zone here in Pinas

$query = "SELECT doc_no, Title, Description, Author, date_published, Category, d_status, ordinance_no, resolution_no
          FROM documents 
          WHERE DATE(date_published) = ?";





if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("s", $today_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $new_documents_today = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    echo "Error preparing statement for today's documents: " . $conn->error;
}


$approved_data = [];
$rejected_data = [];
$months = [];

$query = "SELECT 
            DATE_FORMAT(`date_published`, '%Y-%m') as month, 
            SUM(CASE WHEN d_status = 'Approve' THEN 1 ELSE 0 END) as approved_count, 
            SUM(CASE WHEN d_status = 'Reject' THEN 1 ELSE 0 END) as rejected_count 
          FROM documents 
          GROUP BY month 
          ORDER BY month ASC";
if ($stmt = $conn->prepare($query)) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $months[] = $row['month'];
        $approved_data[] = (int)$row['approved_count'];
        $rejected_data[] = (int)$row['rejected_count'];
    }
    $stmt->close();
} else {
    echo "Error preparing statement for graph data: " . $conn->error;
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
  <style>
    tr {
  cursor: pointer;
}
tr:hover {
  background-color: #f5f5f5; /* Optional: Change row color on hover */
}
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="image/logo1.png" alt="LTAS lOGO" height="60" width="60">
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
              <li class="breadcrumb-item active">Admin Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
    <div class="inner">
        <h3><?php echo $admin_count; ?></h3>
        <p>Number of Admins</p>
    </div>
    <div class="icon">
        <i class="ion ion-ios-person"></i>
    </div>
    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
</div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-secondary">
    <div class="inner">
        <h3><?php echo $user_count; ?></h3>
        <p>Number of Users</p>
    </div>
    <div class="icon">
    <i class="ion ion-ios-person-add"></i>
    </div>
    <a href="./users.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
</div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
    <div class="inner">
        <h3><?php echo $approved_count; ?></h3>
        <p>Approved Documents</p>
    </div>
    <div class="icon">
        <i class="ion ion-ios-thumbs-up"></i>
    </div>
    <a href="./approved_documents.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
</div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
    <div class="inner">
        <h3><?php echo $rejected_count; ?></h3>
        <p>Rejected Documents</p>
    </div>
    <div class="icon">
        <i class="ion ion-md-thumbs-down"></i>
    </div>
    <a href="./rejected_documents.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
</div>
</div>
</div>

<!--statistic section --> 
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header border-0">
            <h3 class="card-title">New Submitted Documents (Today)</h3>
            
          </div>
          <div class="card-body table-responsive p-0">
            <table class="table table-striped table-valign-middle">
            <thead>
  <tr>
    <th hidden>Category #</th>
    <th hidden>Document Type</th>
    <th hidden>Title</th>
    <th>Authored By</th>
    <th>Date</th>
    <th>Status</th>
  </tr>
</thead>
<tbody>
  <?php if (!empty($new_documents_today)): ?>
    <?php foreach ($new_documents_today as $document): ?>
      <!-- Make the row clickable based on Category -->
      <?php 
        // Determine the URL based on the category
        $url = '';
        if ($document['Category'] === 'Resolution') {
          $url = 'resolution.php?doc_no=' . urlencode($document['doc_no']);
        } elseif ($document['Category'] === 'Ordinance') {
          $url = 'ordinaces.php?doc_no=' . urlencode($document['doc_no']);
        }
      ?>
      <tr onclick="window.location='<?php echo $url; ?>'">
        <!-- Combine ordinance_no and resolution_no for doc_no -->
        <td hidden>
          <?php 
            // Handle potential null values
            $resolution_no = isset($document['resolution_no']) && $document['resolution_no'] !== NULL ? htmlspecialchars($document['resolution_no']) : '';
            $ordinance_no = isset($document['ordinance_no']) && $document['ordinance_no'] !== NULL ? htmlspecialchars($document['ordinance_no']) : '';
            
            // Combine and echo the values, depending on which number is present
            if ($document['Category'] === 'Resolution') {
                // Display only the resolution_no if category is Resolution
                echo $resolution_no;
            } elseif ($document['Category'] === 'Ordinance') {
                // Display only the ordinance_no if category is Ordinance
                echo $ordinance_no;
            }
          ?>
        </td>
        <td hidden><?php echo htmlspecialchars($document['Category']); ?></td>
        <td hidden><?php echo htmlspecialchars($document['title']); ?></td>
        <td><?php echo htmlspecialchars($document['Author']); ?></td>
        <td><?php echo htmlspecialchars($document['date_published']); ?></td>
        <td><?php echo htmlspecialchars($document['d_status']); ?></td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="7" class="text-center">No new documents submitted today.</td>
    </tr>
  <?php endif; ?>
</tbody>



            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header border-0 text-center">
            <h5 class="card-title mb-0">Approved and Rejected Documents</h5>
        </div>
        <div class="card-body">
            <div class="position-relative">
                <canvas id="document-chart" height="150"></canvas>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <span><i class="fas fa-square text-primary"></i> Approved</span>
                <span><i class="fas fa-square text-danger"></i> Rejected</span>
            </div>
        </div>
    </div>
</div>

          </div>
          <script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('document-chart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months, // Dynamic months
                datasets: [
                    {
                        label: 'Approved Documents',
                        data: approvedData, // Approved counts
                        borderColor: 'rgba(0, 123, 255, 0.8)',
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        fill: true,
                        tension: 0.4, // Smooth curve
                    },
                    {
                        label: 'Rejected Documents',
                        data: rejectedData, // Rejected counts
                        borderColor: 'rgba(220, 53, 69, 0.8)',
                        backgroundColor: 'rgba(220, 53, 69, 0.2)',
                        fill: true,
                        tension: 0.4, // Smooth curve
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'bottom' },
                },
                scales: {
                    x: { title: { display: false } },
                    y: { title: { display: false } },
                },
            },
        });
    });
</script>
          <script>
    const months = <?php echo json_encode($months); ?>;
    const approvedData = <?php echo json_encode($approved_data); ?>;
    const rejectedData = <?php echo json_encode($rejected_data); ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
