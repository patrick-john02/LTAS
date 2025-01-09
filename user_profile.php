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

// Profile update logic (existing)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
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

// Password update logic (plain text)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    // Get current and new passwords from the form
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if current password matches the one in the database
    if ($current_password === $user['Password']) {
        // Check if new password and confirm password match
        if ($new_password === $confirm_password) {
            // Update the password in plain text in the database
            $update_password_sql = "UPDATE users SET Password = ? WHERE ID = ?";
            $update_password_stmt = $conn->prepare($update_password_sql);
            $update_password_stmt->bind_param("si", $new_password, $user_id);

            if ($update_password_stmt->execute()) {
                $_SESSION['message'] = "Password updated successfully!";
            } else {
                $error = "Failed to update password.";
            }
        } else {
            $error = "New passwords do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="assets/jquery-3.7.1.js"></script>
    <script src="assets/dataTables.js"></script>
    <link rel="stylesheet" href="assets/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">User Profile</h1>
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-info">
                                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-5">
            <div class="row">
                <!-- Profile Section (Sidebar Part) -->
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
                                <li class="list-group-item">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        Change pass
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Form (Main Content Part) -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Edit Profile</h3>
                        </div>
                        <div class="card-body">
                            <form action="user_profile.php" method="POST">
                                <div class="form-group mb-3">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['Username']); ?>" maxlength="8" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['FirstName']); ?>" maxlength="8 required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['LastName']); ?>" maxlength="8 required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"maxlength="8 required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="position">Position</label>
                                    <input type="text" class="form-control" id="position" name="position" value="<?php echo htmlspecialchars($user['position']); ?>"maxlength="8 required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="dept">Department</label>
                                    <input type="text" class="form-control" id="dept" name="dept" value="<?php echo htmlspecialchars($user['dept']); ?>"maxlength="8 required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Change Password -->
        <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="user_profile.php" method="POST">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-danger">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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