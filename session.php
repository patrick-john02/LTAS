<?php
session_start();
include('config.php');
include('add_session.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesions</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css">
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

    </style>
</head>
<body>
<div class="container"  style="margin-left: 200px;">
        <aside class="sidebar">
            <?php include('sidebar.php');?>
            <nav>
                <?php include('admin_menu.php');?>
            </nav>
        </aside>
        <main style="margin-left: 130px; margin-right: 100; padding: 10px 10px; display: inline-block;">
            <div id="addform" class="popup-form" style="display:none">
                    <div class="form-content">
                        <span class="close">&times;</span>
                        <h2>Add Legislative Sesions</h2>
                        <form action="add_session.php" method="post">
                            <div class="form-group" style="display:none">
                                <label for="id">ID</label>
                                <input type="text" id="id" name="id" value="AUTO_GENERATED_ID" readonly>
                            </div>
                            <div class="form-group">
                                <label for="session_name">Session Name</label>
                                <input type="text" id="session_name" name="session_name" required>
                            </div>
                            <div class="form-group">
                                <label for="start_datetime">Start Date and Time</label>
                                <input type="datetime-local" id="start_datetime" name="start_datetime" required>
                            </div>
                            <div class="form-group">
                                <label for="end_datetime">End Date and Time</label>
                                <input type="datetime-local" id="end_datetime" name="end_datetime" required>
                            </div>
                            <button type="submit"  class="submit-button">Submit</button>
                        </form>
                    </div>
                </div>
                
                <div id="editform" class="popup-form" style="display:none">
                    <div class="form-content">
                        <span class="close">&times;</span>
                        <h2>Edit Legislative Sesions</h2>
                        <form action="updatesession.php" method="post">
                            <div class="form-group" style="display:none">
                                <label for="id">ID</label>
                                <input type="text" id="edit_id" name="id" value="AUTO_GENERATED_ID" readonly>
                            </div>
                            <div class="form-group">
                                <label for="session_name">Session Name</label>
                                <input type="text" id="edit_session_name" name="session_name" required>
                            </div>
                            <div class="form-group">
                                <label for="start_datetime">Start Date and Time</label>
                                <input type="datetime-local" id="edit_start_datetime" name="start_datetime" required>
                            </div>
                            <div class="form-group">
                                <label for="end_datetime">End Date and Time</label>
                                <input type="datetime-local" id="edit_end_datetime" name="end_datetime" required>
                            </div>
                            <button type="submit"  class="submit-button">Submit</button>
                        </form>
                    </div>
                </div>
                
                
           
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
                <li><a class="dropdown-item" href="adminEditprofile.php"><i class="fa fa-edit me-2"></i> Edit Profile</a></li>
                <li><a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</header>
            <section class="Documents-management">
                
                <div class="document-list">
                     <br>
                    <button id="add-button" class="add-button">Add New Session</button>
                    <center> <h3>SESSION VIEW</h3></center>
                    <table id="doc-table">
                    <thead>
                        <tr>
                            
                            
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th style="text-align:center !important">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    <?php
                    include('config.php');
                    
                    // Your SQL query
                    $sql = "SELECT * FROM sessions WHERE isArchive = 0";
                    $result = $conn->query($sql);
                    
                    if ($result === false) {
                        echo "<p>Query error: " . $conn->error . "</p>";
                    } else {
                        if ($result->num_rows > 0) {
                            // Inside your document list loop in index.php
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["session_name"] . "</td>";
                                echo "<td>" . $row["start_datetime"] . "</td>";
                                echo "<td>" . $row["end_datetime"] . "</td>";
                                echo "<td>";
                                //echo "<a href='editsession.php?id=" . $row['id'] . "' class='edit-button'>Edit</a>";
                                echo "<button class='edit-button' 
                                    data-session-id='". $row['id'] . "' 
                                    data-session-name='". $row['session_name'] . "'
                                    data-session-start='". $row['start_datetime'] . "'
                                    data-session-end='". $row['end_datetime'] . "'
                                    >Edit</button>";
                                
                                
                                
                                $docName = $row["session_name"];                            
                                $workOrderId = $row['id'];
                                echo "<form action='deletesession.php' method='GET' style='display:inline' id='frm-$workOrderId' onSubmit='return confirm(\"Are you sure to archive: $docName?\")'>";
                                echo "<input type='hidden' name='id' value='". $workOrderId . "'>";
                                echo "<input type='submit' name='delete' class='delete-button' value='Archive'></form>";
                            
                                //echo "<a href='deletesession.php?id=" . $row['id'] . "' class='delete-button'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<p>No documents found</p>";
                        }
                    }
                    
                    // Close the connection
                    $conn->close();
                    ?>
                </tbody>
                </table>
        </div>
                    
            </div>
        </main>
    </div>
    <script>
        $(document).ready(function() {
            new DataTable('#doc-table', {
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options

                info: true,
                ordering: true,
                paging: true,
                responsive: true,
                "order":[3, 'desc'],
                  
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
            var updateWorkOrderForm = document.getElementById("editform");
            var editcloseButton = updateWorkOrderForm.querySelector(".close");
            updateButtons.forEach(function(updateButton) {
                updateButton.addEventListener("click", function(event) {
                    event.preventDefault();
                    updateWorkOrderForm.style.display = "block";
                
                    var data_doc_id = this.getAttribute("data-session-id");
                    var data_doc_name = this.getAttribute("data-session-name");
                    var data_doc_start = this.getAttribute("data-session-start");
                    var data_doc_end = this.getAttribute("data-session-end");
                    
                    document.getElementById("edit_id").value = data_doc_id;
                    document.getElementById("edit_session_name").value = data_doc_name;
                    document.getElementById("edit_start_datetime").value = data_doc_start;
                    document.getElementById("edit_end_datetime").value = data_doc_end;

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

