<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("location:login_admin.php");
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('config.php');
include('adddocuments.php');
include('deletedocument.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legislative Document</title>
    <link rel="stylesheet" href="style.css">
    <script src="assets/jquery-3.7.1.js"></script>
    <script src="assets/dataTables.js"></script>
    <link rel="stylesheet" href="assets/dataTables.dataTables.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            background-color: #f5f5f5;
        }

        .sidebar {
            height: 100vh;
            width: 300px;
            background-color: #2c3e50;
            color: #ffffff;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding: 10px;
        }

        .profile-icon-color {
            color: black;
            font-size: 30px;
        }

        .dropdown-item i {
            font-size: 18px;
        }

        .archive-btn {
            color: #fff;
            background-color: green;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .archive-btn:hover {
            background-color: #e67e22;
        }

        .search-container {
            margin-bottom: 20px;
            text-align: right;
            padding-right: 20px;
        }

        #searchInput {
            padding: 8px;
            width: 25%;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container" style="margin-left: 95px; padding-top:1px;">
        <aside class="sidebar">
            <?php include('sidebar.php'); ?>
            <nav>
                <?php include('admin_menu.php'); ?>
            </nav>
        </aside>
        <main style="margin-left: 179px; ">
                 
        <header>
    <div class="user-name d-flex align-items-center">
        <!-- Display Admin Username -->
        <span>Welcome, <?php echo $_SESSION['username']; ?></span>

        <!-- Profile Icon Button with Dropdown -->
        <div class="dropdown ms-3">
            <button 
                class="btn btn-link profile-icon dropdown-toggle" 
                id="profile-icon-btn" 
                data-bs-toggle="dropdown" 
                aria-expanded="false">
                <i class="fa fa-user-circle profile-icon-color"></i>
            </button>

            <!-- Dropdown Menu -->
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile-icon-btn">
                <li><a class="dropdown-item" href="adminEditprofile.php"><i class="fa fa-edit me-2"></i> Edit Profile</a></li>
                <li><a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</header>
            <section class="documents-management">
                <br>
                <div class="document-list">
                    <center><h3>ALL ARCHIVED DOCUMENTS LIST</h3></center>
                    <table id="doc-table">
                        <thead>
                            <tr>
                                <th>Doc No.</th>
                                <th>Document Type</th>
                                <th>Title</th>
                                <!-- <th>Description</th> -->
                                <th>Author</th>
                                <th>Date Published</th>
                                <th>Download</th>
                                <th>Added By</th>
                                <th>Status</th>
                                <th>View History</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "SELECT * FROM documents WHERE isArchive = 1";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $did = $row["id"];
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars(isset($row["doc_no"]) ? $row["doc_no"] : '') . "</td>";  // Ensure a string is passed
                                        echo "<td>" . htmlspecialchars(isset($row["Category"]) ? $row["Category"] : '') . "</td>";
                                        echo "<td>" . htmlspecialchars(isset($row["Title"]) ? $row["Title"] : '') . "</td>";
                                        // echo "<td>" . htmlspecialchars($row["Description"] ?? '') . "</td>";
                                        echo "<td>" . htmlspecialchars(isset($row["Author"]) ? $row["Author"] : '') . "</td>";
                                        echo "<td>" . htmlspecialchars(isset($row["Date Published"]) ? $row["Date Published"] : '') . "</td>";
                                        echo "<td>";
                                        if (!empty($row["file_path"])) {
                                            echo "<a href='" . htmlspecialchars($row["file_path"]) . "' target='_blank' class='done-btn'>Download PDF</a>";
                                        }
                                        echo "</td>";

                                        // Get user who added the document
                                        $sql2 = mysqli_query($conn, "SELECT * FROM users WHERE ID = " . intval(isset($row['user_id']) ? $row['user_id'] : 0));
                                        if ($sql2 && $sql2->num_rows > 0) {
                                            $brow2 = $sql2->fetch_assoc();
                                            $uid = isset($brow2['Username']) ? $brow2['Username'] : 'Unknown';
                                        } else {
                                            $uid = 'Admin';
                                        }

                                        echo "<td>" . htmlspecialchars($uid) . "</td>";
                                        echo "<td>" . htmlspecialchars(isset($row["d_status"]) ? $row["d_status"] : '') . "</td>";
                                        echo "<td><a href='document_history_admin.php?id=$did'>Track History</a></td>";
                                        echo "<td>";
                                        $docName = htmlspecialchars(isset($row['Title']) ? $row['Title'] : '');
                                        $workOrderId = intval(isset($row['id']) ? $row['id'] : 0);
                                        echo "<form action='restoredocument.php' method='GET' style='display:inline' id='frm-$workOrderId' onSubmit='return confirm(\"Are you sure to restore: $docName?\")'>";
                                        echo "<input type='hidden' name='id' value='" . $workOrderId . "'>";
                                        echo "<button type='submit' name='delete' class='btn btn-link delete-button' title='Restore'>
                                                <i class='fas fa-undo'></i> <!-- Font Awesome Restore Icon -->
                                            </button>";
                                        echo "</form>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                }

                                $conn->close();
                                ?>


                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            new DataTable('#doc-table', {
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                info: true,
                ordering: true,
                paging: true,
                responsive: true,
                "order": [0, 'desc']
            });
        });
    </script>
</body>
</html>
