<?php
session_start();
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
    <style>
      
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
                        <?php include('sidebar.php');?>
            <nav>
                <ul>
                    <li><a href="admin_dashboard.php">Dashboard</a></li>
                    <li class="header">LEGISLATIVE MANAGEMENT</li>
                    <li><a href="user_session.php">SESSIONS</a></li>
                    <li><a href="documents view.php">APPROVED DOCUMENTS</a></li>
                    <li><a href="documents_pending.php">SENT DOCUMENTS</a></li>
                    <!--li><a href="memorandum_user.php">Memorandum</a></li>
                    <li><a href="resolution_user.php">Resolution</a></li>
                    <li><a href="ordinances_user.php">Ordinances</a></li-->
                </ul>
            </nav>
        </aside>
        <main>
            <header>
                <div class="user-name">Welcome <?php echo $_SESSION['username'];?>, <a href='logout.php'>Logout</a></div>
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
                            <th>Description</th>
                            <th>Author</th>
                            <th>Date Published</th>
                            <th>Download</th>
                            <th>Status</th>
                            <th>View History</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    <?php
                    $uid = $_SESSION['userid'];
                    $sql = "SELECT * FROM documents WHERE d_status != 'Approved' AND user_id = $uid";
                    //echo $sql;
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        // Inside your document list loop in index.php
                        while($row = $result->fetch_assoc()) {
                            $did = $row["id"];
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["Category"] . "</td>";
                            echo "<td>" . $row["Title"] . "</td>";
                            echo "<td>" . $row["Description"] . "</td>";
                            echo "<td>" . $row["Author"] . "</td>";
                            echo "<td>" . $row["Date Published"] . "</td>";
                            echo "<td>";
                            if (!empty($row["file_path"])) {
                                echo "<a href='" . $row["file_path"] . "' target=_blank class='done-btn'>Download PDF</a>";
                            }
                            echo "</td>";                            
                            echo "<td>" . $row["d_status"] . "</td>";
                            echo "<td><a href='document_history.php?id=$did'>Track History</a></td>";
                            echo "</tr>";
                        }
                    } 
                    // Close the connection
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