

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="./user_dashboard.php" class="nav-link">Home</a>
    </li>
  </ul>
  
 <!-- Right navbar links -->
 <ul class="navbar-nav ml-auto">
    <!-- User Info -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-user-circle"></i> Welcome, 
        <?php 
        // Use a default value if the session key is not set
        echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; 
        ?>

      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <!-- <a href="./user_profile.php" class="dropdown-item" >
          <i class="fas fa-user"></i> Profile
        </a> -->
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

<!-- Modal for User Profile
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">Update User Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        // checking the user is logged in
        if (!isset($_SESSION['username'])) {
            header("Location: login.php"); 
            exit();
        }

        // getting the User details in the database 
        include('config.php'); 
        $username = $_SESSION['username']; 
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // get the User details
            $user = mysqli_fetch_assoc($result);
        } else {
            header("Location: error.php");
            exit();
        }
        ?>
        <form method="POST" action="update_profile.php">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
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
<script>
  $(document).ready(function(){
    // matic opening of the modal function
    $('[data-toggle="modal"]').click(function(){
      $('#profileModal').modal('show');
    });
  });
</script>
 -->