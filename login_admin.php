<?php
include ('config.php');
session_start();

//print_r($_SESSION);

if($_SERVER["REQUEST_METHOD"] == "POST")
{
  $username = addslashes ($_POST['username']);
  $pword = addslashes($_POST['pword']);

  $sql = mysqli_query($conn, "SELECT * FROM admin WHERE Username='$username' AND Password='$pword'");
  $count = mysqli_num_rows($sql);

  if($count == 1){
      $sql2 = mysqli_query($conn, "SELECT * FROM admin WHERE Username='$username' AND Password='$pword'");
      $brow2 = $sql2->fetch_assoc(); 
      $uid = $brow2['id'];
     
      
    $_SESSION['username'] = $username;
    $_SESSION['userid'] = $uid;
    $_SESSION['userType'] = 'admin';
    header("location:admin_dashboard.php");
  }else{
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
  <title>Admin Login</title>

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
            <input type="text" class="form-control form-control-user" name="username" placeholder="Enter Username..." maxlength="15>
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
       
      </div>
    </div>
  </div>

</body>

</html>
