<?php
session_start();

if(!isset($_SESSION['userid'])) {
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script></body>
    
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
/* For the dashboard charts */
.chart-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap; /* Ensures responsive design */
    gap: 20px; /* Adds space between elements */
}

/* Ensure sidebar and dashboard alignment */
.sidebar {
    height: 100vh;
    width: 260px;
    background-color: #2c3e50;
    color: #ffffff;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
    padding: 10px;
}

.dashboard {
    margin-left: 260px; /* Matches the width of the sidebar */
    padding: 20px;
    flex: 1;
}

.profile-icon-color {
    color: black;  /* Changes the color to black */
    font-size: 30px;  /* Adjust the size of the icon (can change 40px to any size you prefer) */
   
}


.dropdown-item i {
    font-size: 18px;  /* Adjust the icon size */
}

      
    </style>
</head>
<body>
    <div class="container" style="margin-left: 100px; padding-top: 1px;">
        <aside class="sidebar">
                        <?php include('sidebar.php');?>
            <nav>
                <?php include('admin_menu.php');?>
            </nav>
        </aside>
        <main style="margin-left: 110px; margin-right:70px;">
           
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
                <div class="document-list">
                    <center><h3>ALL DOCUMENTS LIST</h3></center>
                    <table id="doc-table">
                    <thead>
                        <tr>
                            <th>Doc ID</th>
                            <th>Document Type</th>
                            <th>Title</th>
                            <!-- <th>Description</th> -->
                            <!-- <th>Author</th> -->
                            <!-- <th>Date Published</th> -->
                            <th>Download</th>
                            <th>Added By</th>
                            <th>Status</th>
                            <th>View History</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    <?php
$sql = "SELECT * FROM documents WHERE isArchive = 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Inside your document list loop in index.php
    while ($row = $result->fetch_assoc()) {
        $did = $row["id"];
        echo "<tr>";
        echo "<td>" . $row["doc_no"] . "</td>";
        echo "<td>" . $row["Category"] . "</td>";
        echo "<td>" . $row["Title"] . "</td>";
        echo "<td>";
        if (!empty($row["file_path"])) {
            echo "<a href='" . $row["file_path"] . "' target=_blank class='done-btn'>Download PDF</a>";
        }
        echo "</td>";

        // Handle fetching the user who uploaded the document
        if (!empty($row['user_id'])) {
            $sql2 = mysqli_query($conn, "SELECT * FROM users WHERE ID= " . intval($row['user_id']));
            if ($sql2 && $sql2->num_rows > 0) {
                $brow2 = $sql2->fetch_assoc();
                $uid = $brow2['Username'];
            } else {
                $uid = 'Admin';
            }
        } else {
            $uid = 'Admin';
        }

        echo "<td>" . $uid . "</td>";
        echo "<td>" . $row["d_status"] . "</td>";
        echo "<td><a href='document_history_admin.php?id=$did'>Track History</a></td>";
        echo "<td>";
        echo "<button class='edit-button' 
            data-doc-cat='" . $row['Category'] . "' 
            data-doc-id='" . $row['id'] . "' 
            data-doc-title='" . $row['Title'] . "'
            data-doc-desc='" . $row['Description'] . "'
            data-doc-auth='" . $row['Author'] . "'
            data-doc-pub='" . $row['Date Published'] . "'
            data-doc-file='" . $row['file_path'] . "'
            >Edit</button>";
        
        $docName = $row['Title'];
        $workOrderId = $row['id'];
        echo "<form action='deletedocument.php' method='GET' style='display:inline' id='frm-$workOrderId' onSubmit='return confirm(\"Are you sure to archive: $docName?\")'>";
        echo "<input type='hidden' name='id' value='" . $workOrderId . "'>";
        echo "<input type='submit' name='delete' class='delete-button' value='Archive'></form>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No documents found.</td></tr>";
}

// Close the database connection
$conn->close();
?>

                 </tbody>
                </table>
            </section>
        </main>
        </div>
    
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
                <form method="POST" name="edit_form" id="edit_form" action="updatedocument.php" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label for="Documents-type">Category</label>
                        <select id="edit_Documents-type" name="documents-type" required>
                            <option value="Memorandum">Memorandum</option>
                            <option value="Resolution">Resolution</option>
                            <option value="Ordinances">Ordinances</option>
                        </select>
                    </div>
                     <div class="form-group">
                        <label for="Documents-type">Status</label>
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