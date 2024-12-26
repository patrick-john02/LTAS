<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include('config.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST['id'];
    if(isset($_SESSION['userid'])) {
        $uid = $_SESSION['userid'];
    }
    $doc_no = $_POST['doc_no'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author = $_POST['author'];
    $date_stamp = $_POST['date-stamp'];
    $document_type = $_POST['documents-type'];

    print_r($_POST);
    // Update document in the database
    $sql = "UPDATE cases SET doc_no=?, Title=?, Description=?, Author=?, `DatePublished`=?, Category=?, WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $doc_no,$title, $description, $author, $date_stamp, $document_type,$id);
    
    if ($stmt->execute()) {

         header("Location:cases.php");
    } else {
        echo "Error updating document: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
