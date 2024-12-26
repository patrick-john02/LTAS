<?php
session_start();
include('config.php');

if (!isset($_SESSION['email'])) {
    header("Location: forgot-password.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $otp = addslashes($_POST['otp']);
    $pword = addslashes($_POST['pword']); // New password
    $confirm_password = addslashes($_POST['confirm_password']); // Confirm password

    // Validate user email and OTP
    $email = $_SESSION['email'];
    $sql = mysqli_query($conn, "SELECT otp FROM users WHERE email='$email'");
    $row = $sql->fetch_assoc();

    // Check if OTP matches
    if ($otp == $row['otp']) {
        if ($pword === $confirm_password) {
            // Update the password directly (plain text)
            $sql2 = mysqli_query($conn, "UPDATE users SET Password = '$pword', otp = NULL, is_password_reset = 1 WHERE email='$email'");

            // Set the session variable to indicate successful password reset
            $_SESSION['password_reset'] = true;
            session_destroy(); // Optional: Log the user out after password reset

            // Display success message and redirect to the login page
            $success = "Password has been reset successfully. You can now log in.";
            echo "<script type='text/javascript'>
                    alert('$success');
                    window.location.href = 'login.php'; // Redirect to login page after reset
                  </script>";
            exit(); // Make sure the script stops here after redirect
        } else {
            $error = "Passwords do not match.";
        }
    } else {
        $error = "Invalid OTP.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
    <style>
        /* Add styles similar to your login page */
        body {
      font-family: 'Nunito', sans-serif;
      position: relative;
      margin: 0;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: url('image/background.jpg'); /* Change 'background.jpg' to your background image */
      background-size: cover;
      background-position: center;
      filter: blur(5px); /* Apply blur effect */
      z-index: -1;
    }

    .container {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 100%;
      z-index: 1; /* Ensure the content is above the blurred background */
    }

    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      background-color: rgba(255, 255, 255, 0.8); /* Add transparency to the card */
      max-width: 400px;
      width: 100%;
      backdrop-filter: blur(10px); /* Apply blur effect to the card background */
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
      width: 100px; /* Adjust the width of your logo */
      margin: -50px auto 20px; /* Adjust the margin to position the logo */
      display: block;
    }

    .form-group p {
      margin: 0; /* Remove margin to prevent extra spacing */
    }

    .form-control-user {
      border-radius: 2rem;
      padding: 1rem 1.5rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      width: 100%; /* Take up full width */
      box-sizing: border-box; /* Include padding in width calculation */
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
    margin: 15px auto 0; /* Adds space from the top */
}


    .btn-user:hover {
      background-color: #9c8962;
    }

    .custom-control-label::before {
      border-radius: 1.5rem;
    }

    .err {
      font-size: 0.9rem;
      text-align: center;
      color: red;
      font-weight: bold;
      margin-top: 1rem;
    }

    .text-center {
      text-align: center;
    }

        .success {
            color: green;
            font-weight: bold;
        }
        .err {
            color: red;
            font-weight: bold;
        }
        .no-underline {
    text-decoration: none; /* Removes the underline */
    color: inherit; /* Keeps the text color */
}

.no-underline:hover {
    text-decoration: underline; /* Optional: Adds underline on hover */
}

    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <h1 class="card-title mb-4">Reset Password</h1>
                </div>
                <?php if (!empty($success)) { ?>
                    <div class="form-group success">
                        <?php echo $success; ?>
                    </div>
                <?php } ?>
                <?php if (!empty($error)) { ?>
                    <div class="form-group err">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>
                <form class="user" action="reset-password.php" method="POST">
                 <div class="form-group">
               <p>Enter OTP</p>
                 <input type="text" class="form-control form-control-user" name="otp" placeholder="Enter OTP...">
    </div>
    <div class="form-group">
        <p>New Password</p>
        <input type="password" class="form-control form-control-user" name="pword" placeholder="New Password">
    </div>
    <div class="form-group">
        <p>Confirm Password</p>
        <input type="password" class="form-control form-control-user" name="confirm_password" placeholder="Confirm Password">
    </div>
    <input type="submit" class="btn btn-primary btn-user btn-block" value="Reset Password">
</form>

      
                </div>
            </div>
        </div>
    </div>
</body>
</html>
