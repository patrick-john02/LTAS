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

    // Generate the ordinance number based on the current year
    if ($category == 'Ordinance') {
        $current_year = date('Y');

        // Get the last ordinance number from the database
        $stmt = $conn->prepare("SELECT ordinance_no FROM documents WHERE YEAR(`Date Published`) = ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("i", $current_year);
        $stmt->execute();
        $result = $stmt->get_result();
        $last_ordinance = $result->fetch_assoc();

        $ordinance_no = "ORDINANCE NO. 1 SERIES OF " . $current_year; // Default if no record found
        if ($last_ordinance) {
            preg_match('/ORDINANCE NO. (\d+) SERIES OF (\d{4})/', $last_ordinance['ordinance_no'], $matches);
            $last_number = isset($matches[1]) ? $matches[1] : 0;
            $next_number = $last_number + 1; // Increment by 1
            $ordinance_no = "ORDINANCE NO. " . $next_number . " SERIES OF " . $current_year;
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
    $insertSql = "INSERT INTO documents (user_id, ordinance_no, Title, Author, d_status, `Date Published`, Category, file_path, Description) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("issssssss", $uid, $ordinance_no, $title, $author, $dStatus, $datePublished, $category, $target_file, $description);

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

        header("Location: ordinance_sent_document.php?success=1");
        exit;
    } else {
        echo "Error: " . $conn->error; 
    }
    $insertStmt->close();
}
$conn->close();
?>
