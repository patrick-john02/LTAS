<?php
include('config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userid']) || $_SESSION['userType'] !== 'admin') {
    header("location:login.php");
    exit();
}

// $error = '';
// $success = '';

// Fetch user data for pre-filling the form
$userid = $_SESSION['userid'];
$sql = mysqli_query($conn, "SELECT * FROM admin WHERE id='$userid'");
$user = $sql->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = addslashes($_POST['uname']);
    $current_password = addslashes($_POST['pword']);
    $new_password = isset($_POST['new_password']) ? addslashes($_POST['new_password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? addslashes($_POST['confirm_password']) : '';

    // Validate current password
    $sql = mysqli_query($conn, "SELECT * FROM admin WHERE id='$userid' AND Password='$current_password'");
    if (mysqli_num_rows($sql) == 1) {
        // Update username
        $update_sql = "UPDATE admin SET Username='$new_username'";

        // Update password if provided
        if (!empty($new_password)) {
            if ($new_password === $confirm_password) {
                $update_sql .= ", Password='$new_password'";
            } else {
                $error = "New passwords do not match.";
            }
        }

       // Execute the update query if no errors
if (empty($error)) {
    $update_sql .= " WHERE id='$userid'";
    if (mysqli_query($conn, $update_sql)) {
        $success = "Profile updated successfully.";
        $_SESSION['username'] = $new_username; // Update session username

        // Use JavaScript to show a message and redirect
        echo "<script>
            alert('Profile updated successfully.');
            window.location.href = 'admin_dashboard.php';
        </script>";
        exit();
            } else {
                $error = "Failed to update profile. Please try again.";
            }
        }
    } else {
        $error = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
  body {
      font-family: 'Nunito', sans-serif;
      position: relative;
      margin: 0;
      height: 100vh;
      align-items: center;
      justify-content: center;
  }

  body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: url('image/background.jpg'); 
      background-size: cover;
      background-position: center;
      filter: blur(5px);
      z-index: -1;
  }

  .container {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
  }

  .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      background-color: rgba(255, 255, 255, 0.8);
      max-width: 800px;
      width: 100%;
      backdrop-filter: blur(10px);
  }

  .card-body {
      padding: 2rem;
  }

  .card-title {
      font-size: 1.5rem;
      font-weight: bold;
      color: #333;
      position: relative;
      text-align: center;
  }

  .logo {
      width: 100px;
      margin: -50px auto 20px;
      display: block;
  }

  .form-group p {
      margin: 0;
  }

  .form-control-user {
      border-radius: 2rem;
      padding: 1rem 1.5rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      width: 100%;
      box-sizing: border-box;
  }

  .btn-user {
      border-radius: 2rem;
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      font-weight: bold;
      background-color: #AD976D;
      color: #fff;
      border: none;
      transition: background-color 0.3s ease;
      display: block;
      margin: 0 auto;
  }

  .btn-user:hover {
      background-color: #9c8962;
  }

  .text-center {
      text-align: center;
  }

  .small {
      font-size: 0.85rem;
      color: #666;
  }

  .no-underline {
      text-decoration: none;
      color: inherit;
  }

  .no-underline:hover {
      text-decoration: underline;
  }

  /* Styles for grid layout */
  .form-row {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
  }

  .form-column {
      width: 48%;
  }

  @media (max-width: 768px) {
      .form-column {
          width: 100%;
      }
  }
</style>

<body>
<div class="container">
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">Edit Profile</h2>
            <?php if (isset($error)) { echo '<div class="alert alert-danger">' . $error . '</div>'; } ?>
            <?php if (isset($success)) { echo '<div class="alert alert-success">' . $success . '</div>'; } ?>

            <form action="" method="POST">
                <div class="form-row">
                    <div class="form-column mb-3">
                        <label for="uname" class="form-label">Username</label>
                        <input type="text" class="form-control form-control-user" id="uname" name="uname" value="<?php echo isset($user['username']) ? $user['username'] : ''; ?>" required>
                    </div>
                    <div class="form-column mb-3">
                        <label for="pword" class="form-label">Current Password</label>
                        <input type="password" class="form-control form-control-user" id="pword" name="pword" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-column mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control form-control-user" id="new_password" name="new_password">
                    </div>
                    <div class="form-column mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control form-control-user" id="confirm_password" name="confirm_password">
                    </div>
                </div>
                <button type="submit" class="btn btn-user">Update Profile</button>
            </form>
            <hr>
            <div class="text-center">
                <a class="small no-underline" href="admin_dashboard.php">Cancel</a>
            </div>
        </div>
    </div>
</div>

</body>

</html>
