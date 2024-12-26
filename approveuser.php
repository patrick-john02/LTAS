<?php
include('config.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//print_r($_POST);
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get form data
    $id = $_GET['id'];


    // Update document in the database
    
    $selectWorkSql = "UPDATE users SET 
            u_status = 'active'  
            WHERE ID = $id";

    //print_r($_POST);
    //echo $selectWorkSql;
    $workResult = $conn->query($selectWorkSql);
    
    //die();
    if ($workResult) {
         header("Location:users.php");
    } else {
        echo "Error updating document: " . $conn->error;
    }

    $conn->close();
}
?>
