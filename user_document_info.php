<?php
session_start();
include('config.php');
include('./includes/user/user_navbar.php');
include('./includes/user/user_sidebar.php');

// Check if the document ID is valid
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    $sql = "SELECT doc_no, Title, Description, Author, `Date Published`, Category, file_path, d_status, resolution_no 
            FROM documents 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $document = $result->fetch_assoc();
    } else {
        echo "No document found!";
        exit;
    }
} else {
    echo "Invalid document ID!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Details</title>
    <script src="assets/jquery-3.7.1.js"></script>
    <link rel="stylesheet" href="assets/dataTables.dataTables.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="assets/jquery-3.7.1.js"></script>
    <script src="assets/dataTables.js"></script>
    <link rel="stylesheet" href="assets/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    /* Styling for Timeline */
    .timeline-steps {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }
    .timeline-steps .timeline-step {
        align-items: center;
        display: flex;
        flex-direction: column;
        position: relative;
        margin: 1rem;
    }
    @media (min-width:768px) {
        .timeline-steps .timeline-step:not(:last-child):after {
            content: "";
            display: block;
            border-top: .25rem dotted #3b82f6;
            width: 3.46rem;
            position: absolute;
            left: 7.5rem;
            top: .3125rem;
        }
        .timeline-steps .timeline-step:not(:first-child):before {
            content: "";
            display: block;
            border-top: .25rem dotted #3b82f6;
            width: 3.8125rem;
            position: absolute;
            right: 7.5rem;
            top: .3125rem;
        }
    }
    .timeline-steps .timeline-content {
        width: 10rem;
        text-align: center;
    }
    .timeline-steps .timeline-content .inner-circle {
        border-radius: 1.5rem;
        height: 1rem;
        width: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #3b82f6;
    }
    .timeline-steps .timeline-content .inner-circle:before {
        content: "";
        background-color: #3b82f6;
        display: inline-block;
        height: 3rem;
        width: 3rem;
        min-width: 3rem;
        border-radius: 6.25rem;
        opacity: .5;
    }
</style>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Document Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="user_dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Document Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container my-4">
            <a href="sent_document_user.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>

            <!-- Document Details -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Document Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Resolution No.</th>
                            <td><?php echo htmlspecialchars($document['resolution_no']); ?></td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td><?php echo htmlspecialchars($document['Title']); ?></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><?php echo htmlspecialchars($document['Description']); ?></td>
                        </tr>
                        <tr>
                            <th>Author</th>
                            <td><?php echo htmlspecialchars($document['Author']); ?></td>
                        </tr>
                        <tr>
                            <th>Date Published</th>
                            <td><?php echo htmlspecialchars($document['Date Published']); ?></td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td><?php echo htmlspecialchars($document['Category']); ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><?php echo htmlspecialchars($document['d_status']); ?></td>
                        </tr>
                        <tr>
                            <th>File</th>
                            <td>
                                <?php if (!empty($document['file_path'])): ?>
                                    <a href="<?php echo htmlspecialchars($document['file_path']); ?>" target="_blank">View File</a>
                                <?php else: ?>
                                    No file attached
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Document Timeline -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Document Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline-steps">
                        <?php
                        $sql_timeline = "SELECT action, timestamp, comment 
                                         FROM document_timeline 
                                         WHERE document_id = ? 
                                         ORDER BY timestamp DESC";
                        $stmt_timeline = $conn->prepare($sql_timeline);
                        $stmt_timeline->bind_param('i', $id);
                        $stmt_timeline->execute();
                        $result_timeline = $stmt_timeline->get_result();

                        if ($result_timeline->num_rows > 0):
                            while ($row = $result_timeline->fetch_assoc()):
                                $action = htmlspecialchars($row['action']);
                                $comment = htmlspecialchars($row['comment']);
                        ?>
                        <div class="timeline-step mb-3">
                            <div class="timeline-content">
                                <div class="inner-circle"></div>
                                <p class="h6 mt-3 mb-1"><?php echo htmlspecialchars($row['timestamp']); ?></p>
                                <p class="text-muted mb-1"><?php echo $action; ?></p>
                                <?php if ($comment): ?>
                                    <p class="small text-muted"><strong>Admin Comment:</strong> <?php echo $comment; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted text-center mb-0">No timeline actions recorded yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<!-- Data Tables and Assets -->
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
<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/dist/js/adminlte.js"></script>
<script src="assets/plugins/chart.js/Chart.min.js"></script>
<script src="assets/dist/js/pages/dashboard3.js"></script>
<script src="assets/dist/js/pages/dashboard.js"></script>
</body>
</html>