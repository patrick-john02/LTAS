<?php
session_start();

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

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> -->
    
       <style>
      
body {
    margin: 0;
    padding: 0;
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    background-color: #f5f5f5; /* Optional background for better readability */
}

/* Fix container alignment and spacing */
.container {
    margin: 0 auto;
    padding: 20px;
    max-width: 2200px; /* Adjust as needed */
    background-color: #ffffff; /* Optional, makes content stand out */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Add slight shadow */
}



/* Ensure sidebar and dashboard alignment */
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
    color: black;  /* Changes the color to black */
    font-size: 30px;  /* Adjust the size of the icon (can change 40px to any size you prefer) */
   
}


.dropdown-item i {
    font-size: 18px;  /* Adjust the icon size */
}

.edit-button, .delete-button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin: 0 5px;
        }

        .edit-button {
            color: #007bff; /* Edit button color */
        }

        .edit-button:hover {
            color: #0056b3; /* Darker blue on hover */
        }

        .delete-button {
            color: red; /* Red color for archive icon */
        }

        .delete-button:hover {
            color: darkred; /* Darker red on hover */
        }

       

        .archive-btn {
    color: white;
    background-color: red;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    text-transform: uppercase;
    font-size: 14px;
}

.archive-btn:hover {
    background-color: darkred;
}

      
    </style>
</head>
<body>
    <div class="container" style="margin-left: 80px; padding-top: 1px;">
        <aside class="sidebar">
                    <?php include('sidebar.php');?>

            <nav>
                <?php include('admin_menu.php');?>
            </nav>
        </aside>
        <main style="margin-left: 180px; margin-right:20px; ">
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
                <button id="add-button" class="add-button">Add New Document</button>
                <br><br>
                <!-- Tabset Code -->
<div class="tabset">
    <input type="radio" name="tabset" id="tab1" aria-controls="marzen" checked>
    <label for="tab1">Resolution</label>
    <input type="radio" name="tabset" id="tab2" aria-controls="rauchbier">
    <label for="tab2">Ordinance</label>

    <div class="tab-panels">
        <!-- Resolution Section -->
        <section id="resolution" class="tab-panel">
            <div class="document-list">
                <center><h3>OUTGOING RESOLUTION DOCUMENTS LIST</h3></center>
                <table id="doc-table-ord">
                    <thead>
                        <tr>
                            <th>Resolution No</th>
                            <th>Document Type</th>
                            <th>Title</th>
                            <th>Authored By</th>
                            <th>Date</th>
                            <th>Download</th>
                            <th>Added By</th>
                            <th>Status</th>
                            <th>View History</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // PHP Code for Resolution Section
                        $sql = "SELECT * FROM documents 
                                WHERE isArchive = 0 
                                  AND Category = 'Resolution' 
                                  AND d_status != 'Pending' 
                                  AND d_status != 'Approved'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["doc_no"] . "</td>";
                                echo "<td>" . $row["Category"] . "</td>";
                                echo "<td>" . $row["Title"] . "</td>";
                                echo "<td>" . $row["Author"] . "</td>";
                                echo "<td>" . $row["Date Published"] . "</td>";
                                echo "<td>";
                                if (!empty($row["file_path"])) {
                                    echo "<a href='" . $row["file_path"] . "' target='_blank' class='done-btn'>Download PDF</a>";
                                }
                                echo "</td>";

                                $userId = isset($row['user_id']) ? intval($row['user_id']) : 0;
                                if ($userId > 0) {
                                    $sql2 = $conn->query("SELECT * FROM users WHERE ID = $userId");
                                    $brow2 = $sql2 ? $sql2->fetch_assoc() : null;
                                    $uid = $brow2 ? $brow2['Username'] : "Unknown User";
                                } else {
                                    $uid = "Unknown User";
                                }

                                echo "<td>" . $uid . "</td>";
                                echo "<td>" . $row["d_status"] . "</td>";
                                echo "<td><a href=''>Track History</a></td>";
                                echo "<td>";

                                // Edit Icon
                                echo "<button class='edit-button' 
                                        data-doc-cat='" . $row['Category'] . "' 
                                        data-doc-id='" . $row['id'] . "' 
                                        data-doc-title='" . $row['Title'] . "' 
                                        data-doc-desc='" . $row['Description'] . "' 
                                        data-doc-auth='" . $row['Author'] . "' 
                                        data-doc-pub='" . $row['Date Published'] . "' 
                                        data-doc-file='" . $row['file_path'] . "' 
                                        title='Edit Document'>
                                        <i class='fas fa-edit'></i>
                                    </button>";

                                // Archive Icon
                                $docName = htmlspecialchars($row['Title'], ENT_QUOTES, 'UTF-8');
                                $workOrderId = $row['id'];
                                echo "<form action='deletedocument.php' method='GET' style='display:inline' id='frm-$workOrderId' 
                                        onSubmit='return confirm(\"Are you sure to archive: $docName?\")'>
                                    <input type='hidden' name='id' value='" . $workOrderId . "'>
                                    <button type='submit' class='delete-button' title='Archive Document'>
                                        <i class='fas fa-archive'></i>
                                    </button>
                                </form>";

                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

       <!-- Ordinance Section -->
<section id="ordinance" class="tab-panel">
    <div class="document-list">
        <center><h3>OUTGOING ORDINANCE DOCUMENTS LIST</h3></center>
        <table id="doc-table">
            <thead>
                <tr>
                    <th>Ordinance No</th>
                    <th>Document Type</th>
                    <th>Title</th>
                    <th>Authored By</th>
                    <th>Date</th>
                    <th>Download</th>
                    <th>Added By</th>
                    <th>Status</th>
                    <th>View History</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM documents 
                        WHERE isArchive = 0 
                          AND Category = 'Ordinances' 
                          AND d_status != 'Pending' 
                          AND d_status != 'Approved'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["doc_no"] . "</td>";
                        echo "<td>" . $row["Category"] . "</td>";
                        echo "<td>" . $row["Title"] . "</td>";
                        echo "<td>" . $row["Author"] . "</td>";
                        echo "<td>" . $row["Date Published"] . "</td>";
                        echo "<td>";
                        if (!empty($row["file_path"])) {
                            echo "<a href='" . $row["file_path"] . "' target='_blank' class='done-btn'>Download PDF</a>";
                        }
                        echo "</td>";

                        // Fetch user details safely
                        if (!empty($row['user_id'])) {
                            $sql2 = $conn->prepare("SELECT * FROM users WHERE ID = ?");
                            $sql2->bind_param("i", $row['user_id']);
                            $sql2->execute();
                            $result2 = $sql2->get_result();

                            if ($result2->num_rows > 0) {
                                $brow2 = $result2->fetch_assoc();
                                $uid = $brow2['Username'];
                            } else {
                                $uid = "Unknown User";
                            }

                            $sql2->close();
                        } else {
                            $uid = "Unknown User";
                        }

                        echo "<td>" . $uid . "</td>";
                        echo "<td>" . $row["d_status"] . "</td>";
                        echo "<td><a href=''>Track History</a></td>";
                        echo "<td>";

                        // Edit button (matching the Resolution section style)
                        echo "<button class='btn btn-primary btn-sm rounded-circle edit-button' 
                                data-doc-cat='" . $row['Category'] . "' 
                                data-doc-id='" . $row['id'] . "' 
                                data-doc-title='" . $row['Title'] . "' 
                                data-doc-desc='" . $row['Description'] . "' 
                                data-doc-auth='" . $row['Author'] . "' 
                                data-doc-pub='" . $row['Date Published'] . "' 
                                data-doc-file='" . $row['file_path'] . "' 
                                title='Edit Document'>
                                <i class='fas fa-edit'></i>
                            </button>";

                        // Archive form with icon (matching the Resolution section style)
                        $docName = htmlspecialchars($row['Title'], ENT_QUOTES, 'UTF-8');
                        $workOrderId = $row['id'];

                        // Ensure correct form submission for archiving
                        echo "<form action='deletedocument.php' method='GET' style='display:inline' id='frm-$workOrderId' 
                                onSubmit='return confirm(\"Are you sure to archive: $docName?\")'>";
                        echo "<input type='hidden' name='id' value='" . $workOrderId . "'>";
                        echo "<button type='submit' name='delete' class='delete-button red-archive' title='Archive Document'>
                                <i class='fas fa-archive'></i>
                            </button>
                        </form>";

                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</section>


    </div>
</div>
</section>
</main>
        
    
        <div id="addform" class="popup-form" style="display:none">
            <div class="form-content">
                <span class="close">&times;</span>
                <h2>Add Document</h2>
                <form method="POST" action="adddocuments.php" enctype="multipart/form-data">
                    <div class="form-group"  style="display:none">
                        <label for="id">Document ID</label>
                        <input type="text" id="id" name="id" value="AUTO_GENERATED_ID" readonly>
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" id="description" name="description" required>
                    </div>
                    <div class="form-group">
                        <label for="Author">Author</label>
                        <input type="text" id="Author" name="Author" required>
                    </div>
                    <div class="form-group">
                        <label for="date-stamp">Date Published</label>
                        <input type="datetime-local" id="date-stamp" name="date-stamp" required>
                    </div>
                    <div class="form-group">
                        <label for="Documents-type">Category</label>
                        <select id="Documents-type" name="Documents-type" required>
                            <option value="Memorandum">Memorandum</option>
                            <option value="Resolution">Resolution</option>
                            <option value="Ordinances">Ordinances</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pdf-file">Attach PDF</label>
                        <input type="file" id="pdf-file" name="pdf-file" accept=".pdf" required>
                    </div>
                    <button type="submit" name="submit" class="submit-button">Submit</button>
                </form>
            </div>
        </div>
        
        <div id="edit-workorder-form" class="popup-form" style="display:none">
    <div class="form-content">
        <span class="close">&times;</span>
        <h2>Edit Document</h2>
        <form method="POST" name="edit_form" id="edit_form" action="updatedocuments_incoming.php" enctype="multipart/form-data">
            <div class="form-group" style="display:none">
                <label for="id">Document ID</label>
                <input type="text" id="edit_id" name="id" value="" readonly>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="edit_title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="edit_description" name="description" required>
            </div>
            <div class="form-group">
                <label for="Author">Author</label>
                <input type="text" id="edit_Author" name="author" required>
            </div>
            <div class="form-group">
                <label for="date-stamp">Date Published</label>
                <input type="datetime-local" id="edit_date-stamp" name="date-stamp" required>
            </div>
            <!-- Hidden input for Category -->
            <input type="hidden" id="edit_Documents-type" name="documents-type">
            
            <div class="form-group">
                <label for="Documents-status">Status</label>
                <select id="edit_Documents-status" name="documents-status" required>
                    <option value="First Reading">First Reading</option>
                    <option value="Second Reading">Second Reading</option>
                    <option value="In Committee">In Committee</option>
                    <option value="Approved">Approved</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="pdf-file">Attach PDF</label>
                <input type="file" id="edit_pdf-file" name="pdf-file" accept=".pdf">
            </div>
            <button type="submit" name="submit" class="submit-button">Submit</button>
        </form>
    </div>
</div>

                
    <script>
        $(document).ready(function() {
            new DataTable('#doc-table', {
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options

                info: true,
                ordering: true,
                paging: true,
                responsive: true,
                "order":[0, 'desc'],
                  
            });
            
            new DataTable('#doc-table-ord', {
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options

                info: true,
                ordering: true,
                paging: true,
                responsive: true,
                "order":[0, 'desc'],
                  
            });
            
            var addButton = document.getElementById("add-button");
            var addBusForm = document.getElementById("addform");
            var closeButton = addBusForm.querySelector(".close");
            addButton.addEventListener("click", function() {
                addBusForm.style.display = "block";
            });
            closeButton.addEventListener("click", function() {
                addBusForm.style.display = "none";
            });
            
            var updateButtons = document.querySelectorAll(".edit-button");
            var updateWorkOrderForm = document.getElementById("edit-workorder-form");
            var editcloseButton = updateWorkOrderForm.querySelector(".close");
            updateButtons.forEach(function(updateButton) {
                updateButton.addEventListener("click", function(event) {
                    event.preventDefault();
                    updateWorkOrderForm.style.display = "block";
                    
                    var data_doc_cat = this.getAttribute("data-doc-cat");
                    var data_doc_id = this.getAttribute("data-doc-id");
                    var data_doc_title = this.getAttribute("data-doc-title");
                    var data_doc_desc = this.getAttribute("data-doc-desc");
                    var data_doc_auth = this.getAttribute("data-doc-auth");
                    var data_doc_pub = this.getAttribute("data-doc-pub");
                    var data_doc_file = this.getAttribute("data-doc-file");
                    
                    
                    document.getElementById("edit_Documents-type").value = data_doc_cat;
                    document.getElementById("edit_id").value = data_doc_id;
                    document.getElementById("edit_title").value = data_doc_title;
                    document.getElementById("edit_description").value = data_doc_desc;
                    document.getElementById("edit_Author").value = data_doc_auth;
                    document.getElementById("edit_date-stamp").value = data_doc_pub;
                    document.getElementById("edit_pdf-file").value = data_doc_file;

                });
            });

            // When the close button is clicked, hide the update work order form
            editcloseButton.addEventListener("click", function() {
                updateWorkOrderForm.style.display = "none";
            });
            
        });

    </script>
    
</body>
</html>