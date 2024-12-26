<?php
//local
//$conn = mysqli_connect("localhost", "root","","dbkhe");

$servername = "localhost";  // Change if your database is hosted elsewhere
$username = "root";         // Your MySQL username
$password = "";             // Your MySQL password
$dbname = "hisgqmlh_dbkhe_v15";    

//hosted
//$conn = mysqli_connect("localhost", "hisgqmlh_dbkhe","Pass1234word.","hisgqmlh_dbkhe");



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if(mysqli_connect_errno()){
    echo "Failed to connect to MySQL:". mysqli_connect_error();

}



?>