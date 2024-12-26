<?php
include('config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//print_r($_POST);
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST['id'];
    $uname = $_POST['uname'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $pword = $_POST['pword'];
    $access = $_POST['access'];

    // Update document in the database
    
    $selectWorkSql = "UPDATE users SET 
            Username='$uname', FirstName='$fname', LastName='$lname', Password='$pword', AccessLevel='$access' 
            WHERE ID = $id";

    //print_r($_POST);
    //echo $selectWorkSql;
    $workResult = $conn->query($selectWorkSql);
    
    
    if ($workResult) {
         header("Location:users.php");
    } else {
        echo "Error updating document: " . $conn->error;
    }

    $conn->close();
}
?>
