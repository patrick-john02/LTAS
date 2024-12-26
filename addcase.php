<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $doc_no = $_POST['doc_no'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author = $_POST['Author'];
    $date_published = $_POST['date-stamp'];
    $category = $_POST['Documents-type'];

    // Check if file is uploaded
    // Prepare and bind SQL statement
                $stmt = $conn->prepare("INSERT INTO cases (doc_no, Title, Description, Author, DatePublished, Category) 
                VALUES (?, ?, ?, ?, ?, ?)");
                
                if ($stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }

                // Bind the parameters
                $stmt->bind_param("ssssss", $doc_no,$title, $description, $author, $date_published, $category);

                // Execute the statement
                if ($stmt->execute()) {
                    header("Location: cases.php");
                } else {
                    echo "Error: " . $stmt->error;
                }

                // Close the statement
                $stmt->close();
            
}

// Retrieve documents from the database


?>
