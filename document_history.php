<?php
session_start();


include('config.php');

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
      .archive-btn {
    color: #fff;
    background-color: red; /* Or any color you prefer */
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 5px;
}
.archive-btn:hover {
    background-color: #e67e22;
}

.modal-dialog {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh; /* Ensure the modal stays vertically centered */
}
body {
    margin: 0;
    padding: 0;
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    background-color: #f5f5f5;
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

header {
    display: flex;
    justify-content: flex-end; /* Align content to the right */
    align-items: center; /* Vertically center the content */
    padding: 10px 20px; /* Add some padding for spacing */
    background-color: #ECF0F1; /* Optional: Background color for the header */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: Add a subtle shadow */
}

.user-name {
    display: flex;
    align-items: center; /* Vertically align username and dropdown */
    gap: 10px; /* Add space between username and profile dropdown */
}

.profile-icon {
    cursor: pointer;
    font-size: 1.5rem; /* Adjust size of the profile icon */
}

.profile-icon-color {
    color: black; /* Optional: Profile icon color */
}

.dropdown-menu {
    text-align: left; /* Align dropdown items */
}

    </style>
</head>
<body>
    <div class="container" style="margin-left: 150px;">
        <aside class="sidebar">
            <?php include('sidebar.php');?>
            <nav>
            <nav>
                <?php include('user_menu.php');?>

            </nav>
        </aside>
        <main style="margin-left: 170px; margin-right: 50; padding: 10px 10px; display: inline-block;">
        <header>
    <div class="user-name d-flex align-items-center">
        <span>Welcome <?php echo $_SESSION['username']; ?></span>

        <!-- Profile Icon Button -->
        <div class="dropdown">
            <button 
                class="btn btn-link profile-icon dropdown-toggle" 
                id="profile-icon-btn" 
                data-bs-toggle="dropdown" 
                aria-expanded="false">
                <i class="fa fa-user-circle profile-icon-color"></i>
            </button>
            
            <!-- Dropdown Menu -->
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile-icon-btn">
                <li><a class="dropdown-item" href="userEditprofile.php"><i class="fa fa-edit me-2"></i> Edit Profile</a></li>
                <li><a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</header>
            <section class="documents-management">
                <br>
                <div class="document-list">
                    <center><h3>DOCUMENT HISTORY </h3></center>
                    <table id="doc-table">
                    <thead>
                <tr>
                    <th>Document Type</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Author</th>
                    <th>Date Published</th>
                    <th>Status</th>
                    <!-- <th>Location</th> -->
                    <th>Updated By</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $uid = $_SESSION['userid'];
                $dic = $_GET['id'];

                $sql = "SELECT * FROM documents_history WHERE doc_id = $dic";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row2 = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row2["Category"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row2["Title"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row2["Description"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row2["Author"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row2["Date Published"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row2["d_status"]) . "</td>";
                        // echo "<td>" . htmlspecialchars($row2["Location"]) . "</td>";

                        // Fetch user details
                        $usql = "SELECT * FROM admin WHERE ID = " . $row2["user_id"];
                        $uresult = $conn->query($usql);

                        if ($uresult->num_rows > 0) {
                            $urow = $uresult->fetch_assoc();
                            $user = $urow['username'];
                            echo "<td>" . htmlspecialchars($user) . "</td>";
                        } else {
                            echo "<td>Unknown</td>";
                        }

                        echo "<td>" . htmlspecialchars($row2["date_updated"]) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No records found</td></tr>";
                }

                // Close the connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</section>
                 </tbody>
                </table>
            </section>
        </main>
        </div>
    
        <div id="addform" class="popup-form" style="display:none">
            <div class="form-content">
                <span class="close">&times;</span>
                <h2>Add Document</h2>
                <form method="POST" action="adddocuments_user.php" enctype="multipart/form-data">
                    <div class="form-group"  style="display:none">
                        <label for="id">Document ID</label>
                        <input type="text" id="id" name="id" value="<?= $_SESSION['userid']; ?>" readonly>
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