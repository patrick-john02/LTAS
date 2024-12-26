<?php
session_start();
include('config.php'); 

if(!isset($_SESSION['userid'])) {
    header("location:login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Update the document status to 'Archived'
    $sql = "UPDATE documents SET d_status = 'Archived' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}



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

        .modal-dialog-centered {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 1rem); /* Adjusts to account for modal spacing */
  }

  .modal-content {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5); /* Optional for better visibility */
  }
    </style>
</head>
<body>
    <div class="container" style="margin-left: 110px;">
        <aside class="sidebar">
            <?php include('sidebar.php');?>
            <nav>
                <?php include('user_menu.php');?>
            </nav>
        </aside>
        <main style="margin-left: 190px; margin-right: 50; padding: 10px 10px; display: inline-block;">
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
                <!-- Place the search bar aligned to the right -->
                <div class="search-container">
                    <input type="text" id="searchInput" placeholder="Search for documents..." onkeyup="searchTable()">
                </div>
                <div class="document-list">
                    <center><h3>ARCHIVES DOCUMENTS</h3></center>
                    <table id="doc-table">
                        <thead>
                            <tr>
                                <th>Resolution No.</th>
                                <th>Document Type</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Author</th>
                                <th>Date Published</th>
                                <th>Download</th>
                                <th>Status</th>
                                <th>View History</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                         // Fetch documents based on user ID and archive status
                       $sql = "SELECT * FROM documents WHERE d_status = 'Archived' AND user_id = ?";
                       $stmt = $conn->prepare($sql);
                       $stmt->bind_param("i", $_SESSION['userid']);
                       $stmt->execute();
                       $result = $stmt->get_result();

                       if ($result->num_rows > 0) {
                       while ($row = $result->fetch_assoc()) {
                       echo "<tr>";
                       echo "<td>" . $row["doc_no"] . "</td>";
                       echo "<td>" . $row["Category"] . "</td>";
                       echo "<td>" . $row["Title"] . "</td>";
                       echo "<td>" . $row["Description"] . "</td>";
                       echo "<td>" . $row["Author"] . "</td>";
                       echo "<td>" . $row["Date Published"] . "</td>";
                       echo "<td><a href='" . $row["file_path"] . "' target='_blank' class='done-btn'>Download PDF</a></td>";
                      echo "<td>" . $row["d_status"] . "</td>";
                     echo "<td><a href='document_history.php?id=" . $row['id'] . "'>Track History</a></td>";
                     echo "<td><button class='btn btn-success archive-btn restore-btn' data-id='" . $row['id'] . "'><i class='fas fa-undo'></i></button></td>";

                     echo "</tr>";
                      }
                } else {
                     echo "<tr><td colspan='10'>No archived documents found for you.</td></tr>";
                       }
                    $stmt->close();
                          ?>

                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <div class="modal fade" id="confirmRestoreModal" tabindex="-1" aria-labelledby="confirmRestoreLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmRestoreLabel">Confirm Restore</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to restore this document?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmRestoreBtn">Restore</button>
      </div>
    </div>
  </div>
</div>



    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            table = document.getElementById('doc-table');
            tr = table.getElementsByTagName('tr');

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName('td');
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }
        }

      
  document.addEventListener('DOMContentLoaded', function () {
    let documentId;

    // Show modal on Restore button click
    document.querySelectorAll('.restore-btn').forEach(button => {
      button.addEventListener('click', function () {
        documentId = this.getAttribute('data-id');
        const restoreModal = new bootstrap.Modal(document.getElementById('confirmRestoreModal'));
        restoreModal.show();
      });
    });

    // Handle the confirmation
    document.getElementById('confirmRestoreBtn').addEventListener('click', function () {
      // Make an AJAX request to restore the document
      fetch('UserRestore_document.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${documentId}`
      })
        .then(response => response.text())
        .then(data => {
          if (data.trim() === 'Success') {
            alert('Document restored successfully!');
            location.reload();
          } else {
            alert('Error: ' + data);
          }
        })
        .catch(error => console.error('Error:', error));
    });
  });


    </script>
</body>
</html>
