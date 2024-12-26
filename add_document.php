<?php
require_once 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userid'])) {
    echo "Error: User not logged in.";
    exit;
}

$uid = $_SESSION['userid']; // Get the user ID from session

// Fetch the logged-in user's first and last name
$sql = "SELECT FirstName, LastName FROM users WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();
$stmt->bind_result($firstName, $lastName);
$stmt->fetch();
$stmt->close();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];  // Add description from the form
    $author = $firstName . " " . $lastName; // Automatically set the author
    $category = $_POST['category'];
    $dStatus = 'Pending'; // Default status is "Pending"
    $datePublished = date('Y-m-d H:i:s', strtotime($_POST['date_published']));

    // Generate the resolution number based on the current year and month
    if ($category == 'Resolution') {
        $current_year = date('Y');
        $current_month = date('m');

        // Get the last resolution number from the database
        $stmt = $conn->prepare("SELECT resolution_no FROM documents WHERE YEAR(`Date Published`) = ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("i", $current_year);
        $stmt->execute();
        $result = $stmt->get_result();
        $last_resolution = $result->fetch_assoc();

        $resolution_no = "RESOLUTION NO. " . $current_year . "-" . $current_month . "-0000001"; // Default if no record found
        if ($last_resolution) {
            preg_match('/(\d+)-(\d+)-(\d+)$/', $last_resolution['resolution_no'], $matches);
            $last_number = isset($matches[3]) ? $matches[3] : 0;
            $next_number = str_pad($last_number + 1, 7, "0", STR_PAD_LEFT); // Increment by 1 and pad to 7 digits
            $resolution_no = "RESOLUTION NO. " . $current_year . "-" . $current_month . "-" . $next_number;
        }

        $stmt->close();
    }

    // Handle file upload
    if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] == 0) {
        $target_file = 'uploads/' . basename($_FILES['file_path']['name']);
        if (!move_uploaded_file($_FILES['file_path']['tmp_name'], $target_file)) {
            echo "Error uploading file!";
            exit;
        }
    } else {
        echo "Error uploading file!";
        exit;
    }

    // Insert the document into the database
    $insertSql = "INSERT INTO documents (user_id, resolution_no, Title, Author, d_status, `Date Published`, Category, file_path, Description) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("issssssss", $uid, $resolution_no, $title, $author, $dStatus, $datePublished, $category, $target_file, $description);

    if ($insertStmt->execute()) {
        $document_id = $conn->insert_id;

        $action = 'Pending';
        $performed_by = $author; 
        $timelineComment = 'Document submitted and awaiting further action.';

        $timelineSql = "INSERT INTO document_timeline (document_id, action, performed_by, comment) 
                        VALUES (?, ?, ?, ?)";
        $timelineStmt = $conn->prepare($timelineSql);
        $timelineStmt->bind_param("isss", $document_id, $action, $performed_by, $timelineComment);
        $timelineStmt->execute();
        $timelineStmt->close();

        header("Location: sent_document_user.php?success=1");
        exit;
    } else {
        echo "Error: " . $conn->error; 
    }
    $insertStmt->close();
}
$conn->close();
?>
