<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = $_POST['id'];
    $doc_no = $_POST['doc_no'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author = $_POST['Author'];
    $date_published = $_POST['date-stamp'];
    $category = $_POST['Documents-type'];

    // Check if file is uploaded
    if (isset($_FILES['pdf-file']) && $_FILES['pdf-file']['error'] == 0) {
        $pdf_file = $_FILES['pdf-file'];

        // Handle file upload
        $target_dir = "uploads/";
        
        // Check if the uploads directory exists, if not, create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Create directory if it doesn't exist with write permissions
        }

        $target_file = $target_dir . basename($pdf_file["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is a PDF
        if ($fileType != "pdf") {
            echo "Sorry, only PDF files are allowed.";
            $uploadOk = 0;
        }

        // Check if file upload is OK
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // Move uploaded file to the uploads directory
            if (move_uploaded_file($pdf_file["tmp_name"], $target_file)) {
                // Generate a unique ID for the document
                $document_id = uniqid('doc_');
                

                // Prepare and bind SQL statement
                $stmt = $conn->prepare("INSERT INTO documents (id, doc_no, title, description, author, `Date Published`, category, file_path, user_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)");
                
                if ($stmt === false) {
                    die('Prepare failed: ' . htmlspecialchars($conn->error));
                }

                // Bind the parameters
                $stmt->bind_param("ssssssssi", $document_id, $doc_no, $title, $description, $author, $date_published, $category, $target_file,$uid);

                // Execute the statement
                if ($stmt->execute()) {
                    header("Location: documents_resolution_sent.php");
                } else {
                    echo "Error: " . $stmt->error;
                }

                // Close the statement
                $stmt->close();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "No file uploaded or there was an error in the upload process.";
    }
}

// Retrieve documents from the database


?>
