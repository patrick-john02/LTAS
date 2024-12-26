<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['userid'])) {
    header("location:login.php");
    exit(); // Ensure no further code is executed if user is not logged in
}

include('config.php'); // Include your database connection file

// Fetch user data from database using mysqli
$userId = $_SESSION['userid'];  // Ensure session variable is properly set

// Prepare the query to fetch user data
$query = "SELECT id, username, email, FirstName, password FROM users WHERE id = ?";
$stmt = $conn->prepare($query);

// Check for errors in query preparation
if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}

// Bind the parameter (userId) to the placeholder (?)
// Execute the query
$stmt->bind_param("i", $userId); // "i" denotes an integer parameter
if (!$stmt->execute()) {
    die('Error executing query: ' . $stmt->error);
}

$result = $stmt->get_result();

// Fetch user data if available
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();  // Store user data in $user array
} else {
    echo "No user found!";
    exit();  // Exit if no user data is returned
}

// Update user profile (handling POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['uname'];
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $pword = $_POST['pword']; // Current password
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the new password and confirm password
    if ($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match!";
    } else {
        // Check if the old password is correct
        if ($pword === $user['password']) { // Direct comparison of password (no hashing)
            // Update the user's profile
            if (!empty($new_password)) {
                // Use the new password if provided
                $updated_password = $new_password;
            } else {
                // Use the current password if no new password is provided
                $updated_password = $user['password'];
            }

            // Update query to change user details
            $updateQuery = "UPDATE users SET username = ?, email = ?, FirstName = ?, password = ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);

            // Check for errors in update query preparation
            if ($updateStmt === false) {
                die('Error preparing update query: ' . $conn->error);
            }

            // Bind parameters for the update query
            $updateStmt->bind_param("ssssi", $username, $email, $fname, $updated_password, $userId);

            // Execute the update query
            if ($updateStmt->execute()) {
                $success = "Profile updated successfully!";
                // Use JavaScript to show a message and redirect
        echo "<script>
        alert('Profile updated successfully.');
        window.location.href = 'user_dashboard.php';
    </script>";
            } else {
                $error = "Error updating profile: " . $updateStmt->error;
            }
        } else {
            $error = "Incorrect current password!";
        }
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
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control form-control-user" id="email" name="email" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column mb-3">
                            <label for="fname" class="form-label">Account Name</label>
                            <input type="text" class="form-control form-control-user" id="fname" name="fname" value="<?php echo isset($user['FirstName']) ? $user['FirstName'] : ''; ?>" required>
                        </div>
                        <div class="form-column mb-3">
                            <label for="pword" class="form-label">Current Password</label>
                            <input type="password" class="form-control form-control-user" id="pword" name="pword" required> <!-- Current password input -->
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
        <!-- <a class="small" href="http://localhost/LTAS_v15/LTAS_v15/login.php">Already have an account? Login!</a> -->
        <a class="small  no-underline" href="user_dashboard.php">Cancel</a>

          <!-- <a class="small" href="forgot-password.html">Forgot Password?</a> -->
        </div>
            </div>
        </div>
    </div>
</body>

</html>
