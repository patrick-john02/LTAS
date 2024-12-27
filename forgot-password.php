<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = addslashes($_POST['email']);
    
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND u_status = 'active'");
    $count = mysqli_num_rows($sql);

    if ($count == 1) {
        // Generate OTP
        $otp = rand(111111, 999999);
        $sql2 = mysqli_query($conn, "UPDATE users SET otp = '$otp' WHERE email='$email'");
        
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'ityourboiaki@gmail.com'; // Default email for sending
            $mail->Password = 'jfrn azmo ggtu tcwu'; // app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('ityourboiaki@gmail.com', 'Password Reset');
            $mail->addAddress($email); // Recipient's email

            // Email Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = 'Your OTP for password reset is: <strong>' . $otp . '</strong>';
            $mail->send();

            $_SESSION['email'] = $email;
            header("Location: reset-password.php");
        } catch (Exception $e) {
            $error = "Error sending OTP. Try again.";
        }
    } else {
        $error = "Email does not exist or is inactive.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
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

    .small {
      font-size: 0.85rem;
      color: #666;
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
                    <h1 class="card-title mb-4">Forgot Password</h1>
                </div>
                <form class="user" action="#" method="POST">
                    <div class="form-group">
                        <p>Email Address</p>
                        <input type="email" class="form-control form-control-user" name="email" placeholder="Enter your email...">
                    </div>
                    <?php if (isset($error)) { ?>
                        <div class="form-group err">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>
                    <input type="submit" class="btn btn-primary btn-user btn-block" value="Submit">
                </form>
                <hr>
                <div class="text-center">
                    <a class="small no-underline" href="login.php">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
