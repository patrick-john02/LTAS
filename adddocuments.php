<?php
include('config.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author = $_POST['author'];
    $date_published = $_POST['date_published']; 
    $category = $_POST['category'];

    if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] == 0) {
        $target_file = 'uploads/' . basename($_FILES['file_path']['name']);
        move_uploaded_file($_FILES['file_path']['tmp_name'], $target_file);  // Ensure the file is moved to the appropriate directory
    } else {
        echo "Error uploading file!";
        exit();
    }
    $uid = $_POST['user_id']; 
    $doc_no = "DOC-" . uniqid();
    $current_year = date('Y');
    $current_month = date('m');
    $resolution_no = NULL;
    $ordinance_no = NULL;

    if ($category == 'Resolution') {
        $stmt = $conn->prepare("SELECT resolution_no FROM documents WHERE YEAR(`Date Published`) = ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("i", $current_year);
        $stmt->execute();
        $result = $stmt->get_result();
        $last_resolution = $result->fetch_assoc();

        if ($last_resolution) {
            $last_resolution_no = $last_resolution['resolution_no'];
            preg_match('/(\d+)-(\d+)-(\d+)$/', $last_resolution_no, $matches);
            $last_number = isset($matches[3]) ? $matches[3] : 0;
            $next_number = str_pad($last_number + 1, 7, "0", STR_PAD_LEFT); // Increment by 1 and pad to 7 digits
            $resolution_no = "RESOLUTION NO. " . $current_year . "-" . $current_month . "-" . $next_number; // Format resolution number
        } else {
            $resolution_no = "RESOLUTION NO. " . $current_year . "-" . $current_month . "-0000001";
        }
    } elseif ($category == 'Ordinance') {
        $stmt = $conn->prepare("SELECT COUNT(*) AS total_documents FROM documents WHERE YEAR(`Date Published`) = ?");
        $stmt->bind_param("i", $current_year);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_documents = $result->fetch_assoc()['total_documents'];

        $ordinance_no = "ORDINANCE NO. " . ($total_documents + 1) . " SERIES OF " . $current_year;
    }

    $stmt = $conn->prepare("INSERT INTO documents (title, description, author, `Date Published`, category, file_path, user_id, resolution_no, ordinance_no) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ssssssiss", $title, $description, $author, $date_published, $category, $target_file, $uid, $resolution_no, $ordinance_no);
    if ($stmt->execute()) {
        $last_id = $stmt->insert_id;
        $doc_no = "DOC-" . $last_id;
        $update_stmt = $conn->prepare("UPDATE documents SET doc_no = ? WHERE id = ?");
        $update_stmt->bind_param("si", $doc_no, $last_id);
        $update_stmt->execute();

        // Conditional redirect based on category
        if ($category == 'Resolution') {
            header("Location: resolution.php");
        } elseif ($category == 'Ordinance') {
            header("Location: ordinaces.php");
        }
        exit(); // Make sure to exit after the redirect
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $update_stmt->close();
}
$conn->close();
?>
