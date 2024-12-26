<?php
session_start();
include('config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer-master_new/src/PHPMailer.php';
require 'PHPMailer-master_new/src/Exception.php';
require 'PHPMailer-master_new/src/SMTP.php';
    

if (isset($_SESSION['userType'])) {
    if ($_SESSION['userType'] == "user") {
        header("Location: index.php");
        exit();
    }
    if ($_SESSION['userType'] == "user" && isset($_SESSION['otp_login'])) {
        header("Location: user_dashboard.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = addslashes($_POST['username']);
    $pword = addslashes($_POST['pword']);

    // Check if user exists with matching credentials
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE Username='$username' AND Password='$pword' AND u_status='active'");
    $count = mysqli_num_rows($sql);

    if ($count == 1) {
        $userData = $sql->fetch_assoc();
        $uid = $userData['ID'];
        $email = $userData['email'];
        $otp = $userData['otp'];
        $isPasswordReset = $userData['is_password_reset'];

        $_SESSION['userid'] = $uid;
        $_SESSION['username'] = $username;
        $_SESSION['userType'] = "user";

        if ((is_null($otp) || $otp === '') && $isPasswordReset == 0) {
            // New User: Generate OTP and send email
            $newOtp = rand(11111, 99999);
            mysqli_query($conn, "UPDATE users SET otp = '$newOtp' WHERE ID='$uid'");

            // Send OTP to user's email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'ityourboiaki@gmail.com'; // Your email
                $mail->Password   = 'jfrn azmo ggtu tcwu'; // Your app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('jan.jamero32@gmail.com', 'Login Notification');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Login Notification - OTP';
                $mail->Body = 'To continue, please use the OTP: <strong>' . $newOtp . '</strong>';

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                die();
            }

            // Redirect new users to OTP page
            header("Location: login_otp.php");
            exit();
        } elseif ($isPasswordReset == 1) {
            // User has recently reset their password
            // Reset is_password_reset to 0 to allow normal login next time
            mysqli_query($conn, "UPDATE users SET is_password_reset = 0 WHERE ID='$uid'");

            // Redirect directly to the dashboard
            header("Location: user_dashboard.php");
            exit();
        } else {
            // Existing user with verified OTP
            header("Location: user_dashboard.php");
            exit();
        }
    } else {
        $error = "Invalid username/password credentials. Try again!";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>User Login</title>

  <!-- Custom fonts for this template -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template -->
  <style>
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
      margin: 0 auto; /* Center the button */
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

    .small {
      font-size: 0.85rem;
      color: #666;
    }
    .no-underline {
    text-decoration: none;
  }

  .no-underline:hover {
    text-decoration: underline; /* Optional: underline on hover */
  }
  </style>
</head>

<body>

  <div class="container">
    <div class="card">
      <div class="card-body">
        <div class="text-center">
          <br>
          <img src="image/LOGO1.png" alt="Logo" class="logo"> <!-- Add your logo here -->
          <h1 class="card-title mb-4">Welcome Back!</h1>
        </div>
        <form class="user" action="#" method="POST">
          <div class="form-group">
            <p>Username</p>
            <input type="text" class="form-control form-control-user" name="username" placeholder="Enter Username...">
          </div>
          <div class="form-group">
            <p>Password</p>
            <input type="password" class="form-control form-control-user" name="pword" placeholder="Password">
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox small">
              <input type="checkbox" class="custom-control-input" id="customCheck">
              <label class="custom-control-label" for="customCheck">Remember Me</label>
            </div>
          </div>
          <?php if(isset($error)) { ?>
          <div class="form-group err">
            <?php echo $error; ?>
          </div>
          <?php } ?>
          <input type="submit" class="btn btn-primary btn-user btn-block" value="Login" name="submit">
        </form>
        <hr>
        <div class="text-center">
  <a class="small no-underline" href="forgot-password.php">Forgot Password?</a>
</div>
<!-- <div class="text-center">
  <a class="small no-underline" href="register.php">Create an Account!</a>
</div> -->
        </div>
      </div>
    </div>
  </div>

</body>

</html>
