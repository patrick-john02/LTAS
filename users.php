<?php
include('config.php');
session_start();
include('./includes/navbar.php');
include('./includes/sidebar.php');

// Handle both Add and Update user logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id'])) {
        // Update User
        $userId = intval($_POST['user_id']);
        $username = htmlspecialchars(trim($_POST['username']));
        $firstname = htmlspecialchars(trim($_POST['firstname']));
        $lastname = htmlspecialchars(trim($_POST['lastname']));
        $accessLevel = htmlspecialchars(trim($_POST['accessLevel']));
        $position = htmlspecialchars(trim($_POST['position']));
        $dept = htmlspecialchars(trim($_POST['dept']));

        $updatePassword = "";
        if (!empty(trim($_POST['password']))) {
            $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
            $updatePassword = ", Password = ?";
        }

        $sql = "UPDATE users SET 
                    Username = ?, 
                    FirstName = ?, 
                    LastName = ?, 
                    AccessLevel = ?, 
                    position = ?, 
                    dept = ? 
                    $updatePassword
                WHERE ID = ?";
        $stmt = $conn->prepare($sql);

        if ($updatePassword) {
            $stmt->bind_param("sssssssi", $username, $firstname, $lastname, $accessLevel, $position, $dept, $password, $userId);
        } else {
            $stmt->bind_param("ssssssi", $username, $firstname, $lastname, $accessLevel, $position, $dept, $userId);
        }

        if ($stmt->execute()) {
            header("Location: users.php?success=User updated successfully");
        } else {
            header("Location: users.php?error=Error updating user: " . $conn->error);
        }
        $stmt->close();
    } 
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Lists</title>
    <script src="assets/jquery-3.7.1.js"></script>
    <script src="assets/dataTables.js"></script>
    <link rel="stylesheet" href="assets/dataTables.dataTables.css">
      <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="assets/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="assets/plugins/summernote/summernote-bs4.min.css">
  <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">

<!-- <style>
     .table-responsive {
            overflow-x: auto;
        }
        @media (max-width: 768px) {
            .table th, .table td {
                padding: 8px;
            }
            .btn {
                font-size: 12px;
            }
            .card-header, .card-body {
                padding: 15px;
            }
        }
</style> -->


</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="image/logo1.png" alt="AdminLTELogo" height="60" width="60">
    </div>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">User Lists</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="admin_dashboard.php">Home</a></li>
                            <li class="breadcrumb-item active">User List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                    <div class="card-body">
                        
                        <button id="add-button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addUserModal">Add User</button>
                        </div>
                    
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Position</th>
            <th>Department</th>
            <th>Access Level</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // Fetch users, ensuring that the status is set to "Inactive" by default
    $sql = "SELECT * FROM users ORDER BY u_status ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $uid = $row["ID"];
            $docName = $row["Username"];
            $status = $row["u_status"] == "Active" ? "Active" : "Inactive";  // Default to Inactive if not Active
            
            echo "<tr>";
            echo "<td contenteditable='true' class='editable' data-field='Username' data-id='" . $uid . "'>" . htmlspecialchars($row["Username"]) . "</td>";
            echo "<td contenteditable='true' class='editable' data-field='email' data-id='" . $uid . "'>" . htmlspecialchars($row["email"]) . "</td>";
            echo "<td contenteditable='true' class='editable' data-field='FirstName' data-id='" . $uid . "'>" . htmlspecialchars($row["FirstName"]) . "</td>";
            echo "<td contenteditable='true' class='editable' data-field='LastName' data-id='" . $uid . "'>" . htmlspecialchars($row["LastName"]) . "</td>";
            echo "<td contenteditable='true' class='editable' data-field='position' data-id='" . $uid . "'>" . htmlspecialchars($row["position"]) . "</td>";
            echo "<td contenteditable='true' class='editable' data-field='dept' data-id='" . $uid . "'>" . htmlspecialchars($row["dept"]) . "</td>";
            echo "<td contenteditable='true' class='editable' data-field='AccessLevel' data-id='" . $uid . "'>" . htmlspecialchars($row["AccessLevel"]) . "</td>";
            echo "<td>";
            
            // Display current status and add the toggle button for status change
            if ($status == "Inactive") {
                echo "<form action='update_user_status.php' method='POST' style='display:inline' id='frm-$uid'>";
                echo "<input type='hidden' name='id' value='" . $uid . "'>";
                echo "<input type='hidden' name='new_status' value='Active'>"; // To change to Active
                echo "<input type='submit' class='btn btn-sm btn-success' value='Activate' onclick='return confirm(\"Are you sure you want to activate this user?\")'>";
                echo "</form>";
            } else {
                echo "<form action='update_user_status.php' method='POST' style='display:inline' id='frm-$uid'>";
                echo "<input type='hidden' name='id' value='" . $uid . "'>";
                echo "<input type='hidden' name='new_status' value='Inactive'>"; // To change to Inactive
                echo "<input type='submit' class='btn btn-sm btn-danger' value='inactive' onclick='return confirm(\"Are you sure you want to deactivate this user?\")'>";
                echo "</form>";
            }
            
            echo "</td>"; // End of status column
            echo "<td>";
            echo "<div style='display: flex; gap: 10px; align-items: center;'>";
            echo "<form action='deleteuser.php' method='GET' style='display:inline' id='frm-$uid' onSubmit='return confirm(\"Are you sure to delete: $docName?\")'>";
            echo "<input type='hidden' name='id' value='" . $uid . "'>";
            echo "<button type='submit' class='btn btn-link delete-button' title='Delete' style='color: red;'>";
            echo "<i class='fas fa-trash-alt'></i>";
            echo "</button>";
            echo "</form>";
            echo "</div>";
            echo "</td>";
            echo "</tr>";
        }
    }
    $conn->close();
    ?>
        </tbody>
    </table>

        </div>
    </div>

 <!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addUserForm">
            <div class="modal-body">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" maxlength="8" required>
    </div>
    <div class="modal-body">
        <label for="username">Email</label>
        <input type="email" class="form-control" id="email" name="email" maxlength="15" required>
    </div>
    <div class="modal-body">
        <label for="firstName">First Name</label>
        <input type="text" class="form-control" id="firstName" name="firstName" maxlength="15" required>
    </div>
    <div class="modal-body">
        <label for="lastName">Last Name</label>
        <input type="text" class="form-control" id="lastName" name="lastName" maxlength="15" required>
    </div>
    <div class="modal-body">
        <label for="accessLevel">Access Level</label>
        <select class="form-control" id="accessLevel" name="accessLevel" required>
            <option value="Admin">Admin</option>
            <option value="User">User</option>
        </select>
    </div>

    <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Add User</button>
</form>
        </div>
    </div>
</div> 


<script>
    $(document).on('submit', '#addUserForm', function(e) {
        e.preventDefault(); 
        const formData = $(this).serialize(); 

        $.ajax({
            type: 'POST',
            url: 'add_user.php', 
            data: formData,
            success: function(response) {
                
                alert('User added successfully!');
                $('#addUserModal').modal('hide');
                location.reload(); 
            },
            error: function(xhr, status, error) {
                // Handle error
                alert('Error: ' + error);
            }
        });
    });
</script>
<script>
$(document).ready(function() {
    let isUpdateInProgress = false; 


    $(".editable").on("click", function() {
        $(this).attr("contenteditable", "true").focus();
    });


    $(".editable").on("blur", function() {
        var updatedValue = $(this).text();  
        var field = $(this).data("field");  
        var userId = $(this).data("id");    


        if (isUpdateInProgress) return;
        isUpdateInProgress = true;

        $.ajax({
            url: "update_user.php", 
            type: "POST",
            data: {
                user_id: userId,
                field: field,
                value: updatedValue
            },
            success: function(response) {
                // Only trigger toastr if it's the first update
                if (!isUpdateInProgress) return;

                toastr.success('User updated successfully!');

                // Reset the flag after success
                isUpdateInProgress = false;

                // this reload the page
                location.reload();  
            },
            error: function(xhr, status, error) {
                alert("There was an error updating the user.");
                isUpdateInProgress = false;  // Reset the flag in case of error
            }
        });
    });

    // Optional: Trigger the blur event when the user clicks away (focusout)
    $(".editable").on("focusout", function() {
        $(this).trigger("blur");
    });
});
</script>


<!-- <script>

    $(document).on('click', '.edit-button', function () {
        const userId = $(this).data('doc-id');
        const username = $(this).data('doc-username');
        const firstName = $(this).data('doc-fname');
        const lastName = $(this).data('doc-lname');
        const accessLevel = $(this).data('doc-access');
        const position = $(this).data('doc-position');
        const dept = $(this).data('doc-dept');

        // Populate modal fields
        $('#edit-user-id').val(userId);
        $('#edit-username').val(username);
        $('#edit-firstname').val(firstName);
        $('#edit-lastname').val(lastName);
        $('#edit-accessLevel').val(accessLevel);
        $('#edit-position').val(position);
        $('#edit-dept').val(dept);

        // Show the modal
        $('#editUserModal').modal('show');
    });
</script> -->

<!-- Include Toastr CSS -->
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


<!--Data tables -->
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
$(function () {
  $("#example1").DataTable({
    "responsive": true,
    "lengthChange": false,
    "autoWidth": false,
    "buttons": ["copy", "csv", "excel", "pdf", "print"]
  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  $('#example1_wrapper .dataTables_filter').css({
    'float': 'right',
    'text-align': 'right'
  });

  $('#example1_wrapper .dataTables_filter input').css('width', '300px');
});
</script>
</body>
</html>