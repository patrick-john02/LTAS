<?php
// Start the session and check if the user is logged in
ob_start();
session_start();

// Include database connection
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    echo "Error: User not logged in.";
    exit;
}

// Fetch the user data
$user_id = $_SESSION['userid']; // Assuming the user ID is stored in the session

// Include other necessary files before any output (including header or echo statements)
include('./includes/user/user_navbar.php');
include('./includes/user/user_sidebar.php');

// Fetch user data from the database
$sql = "SELECT * FROM users WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission for updating user info
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $dept = $_POST['dept'];

    $update_sql = "UPDATE users SET Username = ?, FirstName = ?, LastName = ?, email = ?, position = ?, dept = ? WHERE ID = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssi", $username, $first_name, $last_name, $email, $position, $dept, $user_id);
    
    if ($update_stmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully!";
        header("Location: user_profile.php"); // Redirect to the same page to display success message
        exit;
    } else {
        $error = "Failed to update profile.";
    }
}
ob_end_flush();
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
</head>

<body>
<div class="container mt-5">
<h1>User Profile</h1>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
    if (isset($error)) {
        echo "<div class='alert alert-danger'>" . $error . "</div>";
    }
    ?>
    
   <!-- Profile Image and Info -->
   <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    
                    <h3 class="profile-username text-center"><?php echo htmlspecialchars($user['FirstName']) . " " . htmlspecialchars($user['LastName']); ?></h3>
                    <p class="text-muted text-center"><?php echo htmlspecialchars($user['position']); ?></p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Username</b> <a class="float-right"><?php echo htmlspecialchars($user['Username']); ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>First Name</b> <a class="float-right"><?php echo htmlspecialchars($user['FirstName']); ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Last Name</b> <a class="float-right"><?php echo htmlspecialchars($user['LastName']); ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right"><?php echo htmlspecialchars($user['email']); ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Position</b> <a class="float-right"><?php echo htmlspecialchars($user['position']); ?></a>
                        </li>
                        <li class="list-group-item">
                            <b>Department</b> <a class="float-right"><?php echo htmlspecialchars($user['dept']); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

           <!-- Edit Form -->
           <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Profile</h3>
                </div>
                <div class="card-body">
                    <form action="user_profile.php" method="POST">
                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['Username']); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['FirstName']); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['LastName']); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="position">Position</label>
                            <input type="text" class="form-control" id="position" name="position" value="<?php echo htmlspecialchars($user['position']); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="dept">Department</label>
                            <input type="text" class="form-control" id="dept" name="dept" value="<?php echo htmlspecialchars($user['dept']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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
</body>
</html>
