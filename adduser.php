<?php
include('config.php');

if(isset($_POST['submit'])){

    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['uname'];
    $pword = $_POST['pword'];
    $pos = $_POST['position'];
    $dept = $_POST['dept'];
    $email = $_POST['email'];
    

    $sql = mysqli_query($conn,"INSERT INTO users
        (Username,Password,AccessLevel,FirstName,LastName, position, dept, email) 
        VALUES ('$username','$pword','user','$fname','$lname','$pos','$dept','$email')
        ");

    if($sql){
        echo "<script>alert('Registration Successful, Waitfor admin to approve your request');location.href='login.php'</script>";
        //header("Location: ");
    }else{
        echo "ERROR: Registration not successful.";
    }
    }

?>