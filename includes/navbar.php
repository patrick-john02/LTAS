<?php
// Include the config file to connect to the database
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit();
}

// Get the username from session
$username = $_SESSION['username'];

// Query to get admin details by joining the admin table with the users table
$query = "SELECT a.id, u.username, u.password, a.AccessLevel 
          FROM admin a
          JOIN users u ON a.user_id = u.ID
          WHERE u.username = '$username'";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if a matching admin was found
if (mysqli_num_rows($result) > 0) {
    // Fetch the admin details
    $admin = mysqli_fetch_assoc($result);
} else {
    // If no matching admin found, redirect to an error page
    header("Location: error.php");
    exit();
}
?>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="./admin_dashboard.php" class="nav-link">Home</a>
    </li>
  </ul>
  
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- User Info -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-user-circle"></i> Welcome, <?php echo $_SESSION['username']; ?>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#profileModal">
          <i class="fas fa-user"></i> Profile
        </a>
        <a href="logout.php" class="dropdown-item">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </div>
    </li>
    
    <!-- Fullscreen button -->
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
  </ul>
</nav>

<!-- Modal for Admin Profile -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">Update Admin Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="update_profile.php">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $admin['username']; ?>" maxlength="15" required>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" value="" placeholder="Leave blank to keep current password">
          </div>

          <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="" placeholder="Leave blank to keep current password">
          </div>

          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
