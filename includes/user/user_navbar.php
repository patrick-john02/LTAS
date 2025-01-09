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
        <!-- Open Modal Button -->
        <!-- <a href="#" class="dropdown-item" data-toggle="modal" data-target="#profileModal">
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

<!-- Modal for Profile Edit -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">Edit Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Profile Edit Form -->
        <form action="update_profile.php" method="POST">
        <div class="form-group">
  <label for="username">Username</label>
  <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required readonly>
</div>

          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" value="" placeholder="Leave empty to keep current password">
          </div>
          <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name']) : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo isset($_SESSION['last_name']) ? htmlspecialchars($_SESSION['last_name']) : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="position">Position</label>
            <input type="text" class="form-control" id="position" name="position" value="<?php echo isset($_SESSION['position']) ? htmlspecialchars($_SESSION['position']) : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" required>
          </div>
          <div class="form-group">
            <label for="dept">Department</label>
            <input type="text" class="form-control" id="dept" name="dept" value="<?php echo isset($_SESSION['dept']) ? htmlspecialchars($_SESSION['dept']) : ''; ?>" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

