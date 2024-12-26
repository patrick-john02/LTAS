
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Register</title>

  <!-- Custom fonts for this template -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700&display=swap" rel="stylesheet">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

  <!-- Custom styles for this template -->
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
    text-decoration: none; /* Removes the underline */
    color: inherit; /* Keeps the text color */
}

.no-underline:hover {
    text-decoration: underline; /* Optional: Adds underline on hover */
}

    /* New styles for grid layout */
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
</head>

<body>

  <div class="container">
    <div class="card">
      <div class="card-body">
        <div class="text-center">
          <br>
          <img src="image/LOGO1.png" alt="Logo" class="logo">
          <h1 class="card-title mb-4">Create an Account!</h1>
        </div>
        <form class="user" action="adduser.php" method="POST" enctype="multipart/form-data" onsubmit="return checkForm(this);">
          <!-- New Form Row for Desktop View -->
          <div class="form-row">
            <div class="form-column">
              <div class="form-group">
                <p>First Name</p>
                <input type="text" class="form-control form-control-user" id="exampleFirstName" placeholder="First Name" name="fname" required>
              </div>
            </div>
            <div class="form-column">
              <div class="form-group">
                <p>Last Name</p>
                <input type="text" class="form-control form-control-user" id="exampleLastName" placeholder="Last Name" name="lname" required>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-column">
              <div class="form-group">
                <p>Position</p>
                <input type="text" class="form-control form-control-user" id="examplePosition" placeholder="Position" name="position" required>
              </div>
            </div>
            <div class="form-column">
              <div class="form-group">
                <p>Department</p>
                <input type="text" class="form-control form-control-user" id="exampleDept" placeholder="Department" name="dept" required>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-column">
              <div class="form-group">
                <p>Email</p>
                <input type="text" class="form-control form-control-user" id="exampleEmail" placeholder="Email" name="email" required>
              </div>
            </div>
            <div class="form-column">
              <div class="form-group">
                <p>Username</p>
                <input type="text" class="form-control form-control-user" id="exampleUsername" placeholder="Username" name="uname" required>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-column">
              <div class="form-group">
                <p>Password</p>
                <input class="form-control form-control-user" type="password" name="pword" id="password" oninput="validatePassword(this.value)" required>
              </div>
            </div>
            <div class="form-column">
              <div class="form-group">
                <p>Repeat Password</p>
                <input type="password" class="form-control form-control-user" id="exampleRepeatPassword" placeholder="Repeat Password" name="repeatpword">
              </div>
            </div>
          </div>

          <!-- Password Strength Validation -->
          <div class="form-group">
            <ul>
              <li id="minLength"><i class="fas fa-times text-danger"></i> Minimum 8 characters</li>
              <li id="uppercase"><i class="fas fa-times text-danger"></i> At least one uppercase letter</li>
              <li id="lowercase"><i class="fas fa-times text-danger"></i> At least one lowercase letter</li>
              <li id="symbol"><i class="fas fa-times text-danger"></i> At least one symbol (@$!%*?&)</li>
            </ul>
          </div>
          <span id="errorMessage" class="font-weight-bold text-danger"></span>

          <input type="submit" class="btn btn-primary btn-user btn-block" id="btnReg" value="Register Account" name="submit">
        </form>
        <hr>
        <div class="text-center">
        <!-- <a class="small" href="http://localhost/LTAS_v15/LTAS_v15/login.php">Already have an account? Login!</a> -->
        <a class="small  no-underline" href="login.php">Already have an account? Login!</a>

          <!-- <a class="small" href="forgot-password.html">Forgot Password?</a> -->
        </div>
        <!-- <div class="text-center">
          <a class="small" href="login.php">Already have an account? Login!</a>
        </div> -->
      </div>
    </div>
  </div>

  <script type="text/javascript">
    isValidPass = false;
    function checkForm(form) {
        
      if(isValidPass) {
          return true;
      } else {
          alert("Error: Passwords minimum requirement not statisfied");
          return false;
      }
        
      if (form.pword.value !== "" && form.repeatpword.value !== form.pword.value) {
        alert("Error: Passwords do not match");
        form.pword.focus();
        return false;
      }
      return true;
    }
    
        // Function to toggle password visibility
        document.getElementById('togglePassword').addEventListener('click',
                      function () {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        function validatePassword(password) {
            const strongPasswordRegex = 
                  /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            const errorMessage = document.getElementById('errorMessage');

            // Check each condition and update the corresponding label
            document.getElementById('minLength').innerHTML = 
                  password.length >= 8 ?
                '<i class="fas fa-check text-success"></i> Minimum 8 characters' :
                '<i class="fas fa-times text-danger"></i> Minimum 8 characters';
            document.getElementById('uppercase').innerHTML = 
                  /[A-Z]/.test(password) ?
                '<i class="fas fa-check text-success"></i> At least one uppercase letter' :
                '<i class="fas fa-times text-danger"></i> At least one uppercase letter';
            document.getElementById('lowercase').innerHTML = 
                  /[a-z]/.test(password) ?
                '<i class="fas fa-check text-success"></i> At least one lowercase letter' :
                '<i class="fas fa-times text-danger"></i> At least one lowercase letter';
            document.getElementById('symbol').innerHTML = 
                  /[@$!%*?&]/.test(password) ?
                '<i class="fas fa-check text-success"></i> At least one symbol (@$!%*?&)' :
                '<i class="fas fa-times text-danger"></i> At least one symbol (@$!%*?&)';

            // Check overall validity and update the error message
            if (strongPasswordRegex.test(password)) {
                //errorMessage.textContent = 'Strong Password';
                errorMessage.classList.remove('text-danger');
                errorMessage.classList.add('text-success');
                //document.getElementById('btnReg').enabled = true;
                isValidPass = true;
            } else {
                //errorMessage.textContent = 'Weak Password';
                errorMessage.classList.remove('text-success');
                errorMessage.classList.add('text-danger');
                //alert('Password requirement not statisfied. Please check again')
                //document.getElementById('btnReg').enabled = false;
                isValidPass = false;
            }
        }
    </script>
    
</body>

</html>
