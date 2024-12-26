<?php
session_start();



include('config.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $id = $_POST['id'];
    if(isset($_SESSION['userid'])) {
        $uid = $_SESSION['userid'];
    }
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author = $_POST['author'];
    $date_stamp = $_POST['date-stamp'];
    $document_type = $_POST['documents-type'];
    $document_status = $_POST['documents-status'];
    

    // Update document in the database
    $sql = "UPDATE documents SET Title=?, Description=?, Author=?, `Date Published`=?, Category=?, d_status = ?  WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $title, $description, $author, $date_stamp, $document_type,$document_status , $id);
    
    if ($stmt->execute()) {
        //add to history table
        $s = "INSERT INTO documents_history
        (Title,Description,Author,`Date Published`,Category, d_status, user_id, doc_id) 
        VALUES ('$title','$description','$author','$date_stamp','$document_type','$document_status',$uid,$id)
        ";
        //echo $s;
         $sql = mysqli_query($conn,$s);
        
         header("Location: documents_ordinaces_sent.php");
    } else {
        echo "Error updating document: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
