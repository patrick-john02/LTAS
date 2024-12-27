<?php
ob_start(); // output buff
session_start();
include('config.php');
include('./includes/navbar.php');
include('./includes/sidebar.php');

// parameter
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']); 

    // fetch doc details
    $sql = "SELECT doc_no, Title, Description, Author, `Date Published`, Category, file_path, d_status, resolution_no 
            FROM documents 
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $document = $result->fetch_assoc();

        // Check if status is not set, then set it to 'Pending'
        if (empty($document['d_status'])) {
            $document['d_status'] = 'Pending';
            
            // Update the document status to Pending if it was empty
            $sql_update = "UPDATE documents SET d_status = 'Pending' WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param('i', $id);
            $stmt_update->execute();
        }
    } else {
        echo "No document found!";
        exit;
    }
} else {
    echo "Invalid document ID!";
    exit;
}

function logDocumentAction($conn, $document_id, $action, $performed_by, $comment = null) {
    $valid_actions = ['Pending', 'First Reading', 'Second Reading', 'In Committee', 'Approved', 'Reject'];
    if (in_array($action, $valid_actions)) {
        $sql = "INSERT INTO document_timeline (document_id, action, performed_by, comment) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error in statement preparation: " . $conn->error);
        }
        $stmt->bind_param('isss', $document_id, $action, $performed_by, $comment);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "Action logged successfully!";
        } else {
            echo "Error logging the action!";
        }
    }
}
//backend comment for the timeline 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['d_status'])) {
    $old_status = $document['d_status'];
    $new_status = $_POST['d_status'];
    $comment = isset($_POST['comment']) ? $_POST['comment'] : null;

    if ($old_status !== $new_status) {
        $sql_update = "UPDATE documents SET d_status = ?, approval_timestamp = CASE WHEN ? = 'Approved' THEN NOW() ELSE approval_timestamp END WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param('ssi', $new_status, $new_status, $id);
        $stmt_update->execute();

        logDocumentAction($conn, $id, $new_status, $_SESSION['username'], $comment);

        // Redirect to refresh the page
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id);
        exit;
    }
}
ob_end_flush(); // Flush output
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
    button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
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
<body>
<div class="container mt-3">
<a href="<?php 
    // Check the category and set the redirection accordingly
    if ($document['Category'] === 'Resolution') {
        echo 'resolution.php';
    } elseif ($document['Category'] === 'Ordinance') {
        echo 'ordinaces.php';
    } else {
        echo 'default_page.php'; // In case the category is not "Resolution" or "Ordinance"
    }
?>" class="btn btn-primary">Back to List</a>


<!--displaying of full detail of documents-->
        <h2>Document Details</h2>
        
        <table class="table table-bordered">
            <!-- <tr>
                <th>Resolution No.</th>
                <td><?php echo htmlspecialchars($document['resolution_no']); ?></td>
            </tr> -->
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
    <td>
    <form method="POST" action="">
    <select id="d_status" name="d_status" class="form-control" data-current-status="<?php echo htmlspecialchars($document['d_status']); ?>">
        <option value="Pending" <?php if ($document['d_status'] === 'Pending') echo 'selected'; ?>>Pending</option>
        <option value="First Reading" <?php if ($document['d_status'] === 'First Reading') echo 'selected'; ?>>First Reading</option>
        <option value="Second Reading" <?php if ($document['d_status'] === 'Second Reading') echo 'selected'; ?>>Second Reading</option>
        <option value="In Committee" <?php if ($document['d_status'] === 'In Committee') echo 'selected'; ?>>In Committee</option>
        <option value="Approved" <?php if ($document['d_status'] === 'Approve') echo 'selected'; ?>>Approved</option>
        <option value="Reject" <?php if ($document['d_status'] === 'Reject') echo 'selected'; ?>>Reject</option>
    </select>
    <textarea name="comment" class="form-control mt-2" placeholder="Enter admin comment..."></textarea>
    <div id="validationMessage" class="text-danger mt-1"></div>
    <button type="submit" id="updateButton" class="btn btn-success mt-2" style="display: none;">Update Status</button> <!-- Button hidden by default -->
</form>


    </td>
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
<!-- this is the timeline -->
    <div class="container mt-5">
    <h3>Document Timeline</h3>
    <div class="row">
        <div class="col">
        <div class="timeline-steps">
    <?php
    // Fetch timeline records 
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
    <div class="timeline-step">
        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top"
             title="" data-content="<?php echo $action; ?>"
             data-original-title="<?php echo htmlspecialchars($row['timestamp']); ?>">
            <div class="inner-circle"></div>
            <p class="h6 mt-3 mb-1"><?php echo htmlspecialchars($row['timestamp']); ?></p>
            <p class="h6 text-muted mb-0 mb-lg-0"><?php echo $action; ?></p>
            <?php if ($comment): ?>
                <p class="mt-2"><strong>Admin Comment:</strong> <?php echo $comment; ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php endwhile; ?>
    <?php else: ?>
        <p>No timeline actions recorded yet.</p>
    <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const dStatus = document.getElementById('d_status');
    const updateButton = document.getElementById('updateButton');
    const validationMessage = document.getElementById('validationMessage');
    const currentStatus = dStatus.getAttribute('data-current-status');

    const validTransitions = {
        'Pending': ['Reject', 'First Reading'],
        'First Reading': ['Second Reading', 'Reject'],
        'Second Reading': ['In Committee', 'Reject'],
        'In Committee': ['Approved', 'Reject'],
        'Approved': [], 
        'Reject': [] 
    };

    function updateDropdownOptions() {
        const options = dStatus.querySelectorAll('option');
        
        options.forEach(option => {
            option.disabled = true;
        });

        validTransitions[currentStatus]?.forEach(status => {
            const option = dStatus.querySelector(`option[value="${status}"]`);
            if (option) {
                option.disabled = false;
            }
        });

        if (validTransitions[currentStatus]?.length > 0) {
            updateButton.style.display = 'inline-block';  
            validationMessage.textContent = ""; 
        } else {
            updateButton.style.display = 'none';  
            validationMessage.textContent = "No valid transitions available."; 
        }
    }


    updateDropdownOptions();


    dStatus.addEventListener('change', function () {
        updateDropdownOptions();
    });
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusDropdown = document.getElementById('d_status');
        const updateButton = document.getElementById('updateButton');
        const currentStatus = statusDropdown.getAttribute('data-current-status');

        updateButton.disabled = true;

        statusDropdown.addEventListener('change', function () {
            updateButton.disabled = (statusDropdown.value === currentStatus);
        });

        // Show validation message if the status is the same
        const validationMessage = document.getElementById('validationMessage');
        statusDropdown.addEventListener('change', function () {
            if (statusDropdown.value === currentStatus) {
                validationMessage.textContent = "Please select a different status to update.";
            } else {
                validationMessage.textContent = "";
            }
        });
    });
</script>

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