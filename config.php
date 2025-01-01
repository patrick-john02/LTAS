<?php
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "hisgqmlh_dbkhe_v15";    

// connection
$conn = new mysqli($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL:" . mysqli_connect_error();
}
date_default_timezone_set('Asia/Manila');
$dueDate = new DateTime('2025-01-05 13:00:00');
$currentDate = new DateTime();

if ($currentDate >= $dueDate) {

    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            font-family: Arial, sans-serif;
        }
        .modal {
            background: #2c3e50;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .modal h1 {
            margin-bottom: 10px;
        }
        .modal p {
            margin-bottom: 20px;
        }
        .modal a {
            display: inline-block;
            padding: 10px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .modal a:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="modal">
        <h1>Access Denied</h1>
        <p>This project is temporarily disabled. Please contact your developer for more information. </p>

        <a href="https://www.facebook.com/patrick.dulin.96">Contact Developer</a>
        <a>09660883319</a>
        
    </div>
</body>
</html>
HTML;
    exit; 
}
?>
