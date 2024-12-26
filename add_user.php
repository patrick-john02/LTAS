<?php
include('config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer-master_new/src/PHPMailer.php';
require 'PHPMailer-master_new/src/Exception.php';
require 'PHPMailer-master_new/src/SMTP.php';

function generateRandomPassword($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

function generateOTP($length = 6) {
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= rand(0, 9);
    }
    return $otp;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $accessLevel = $_POST['accessLevel'];
    $position = $_POST['position'] ?? null;
    $dept = $_POST['dept'] ?? null;

    // Generate random password and OTP
    $randomPassword = generateRandomPassword(8);
    $plainPassword = $randomPassword;
    $otp = generateOTP(6);

    $username = mysqli_real_escape_string($conn, $username);
    $email = mysqli_real_escape_string($conn, $email);
    $firstName = mysqli_real_escape_string($conn, $firstName);
    $lastName = mysqli_real_escape_string($conn, $lastName);
    $accessLevel = mysqli_real_escape_string($conn, $accessLevel);
    $position = mysqli_real_escape_string($conn, $position);
    $dept = mysqli_real_escape_string($conn, $dept);

    // Insert user with OTP
    $sql = "INSERT INTO users (Username, email, FirstName, LastName, AccessLevel, position, dept, Password, u_status, otp)
            VALUES ('$username', '$email', '$firstName', '$lastName', '$accessLevel', '$position', '$dept', '$plainPassword', 'Inactive', '$otp')";

    if (mysqli_query($conn, $sql)) {
        $mail = new PHPMailer(true);

        try {
            // Email setup
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ityourboiaki@gmail.com';
            $mail->Password = 'jfrn azmo ggtu tcwu';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('ityourboiaki@gmail.com', 'LTAS-ADMIN');
            $mail->addAddress($email, $firstName . ' ' . $lastName);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Account Created - Your Login Details';
            $mail->Body = "Hello $firstName $lastName,<br><br>Your account has been created successfully. Here are your login details:<br><br>
                           <strong>Username:</strong> $username<br>
                           <strong>Password:</strong> $randomPassword<br>
                           <strong>OTP:</strong> $otp<br><br>
                           Please use the OTP to activate your account.<br><br>
                           Thank you.";

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        header("Location: users.php?success=User added successfully");
    } else {
        header("Location: users.php?error=Error adding user: " . mysqli_error($conn));
    }

    mysqli_close($conn);
}
?>
