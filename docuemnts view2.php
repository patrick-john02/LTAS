<?php
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
     <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <center><div class="sidebar-header">LTAS</div></center>
            <nav>
                <ul>
                    <li><a href="#">Dashboard</a></li>
                    <center><li class="header">LEGISLATIVE MANAGEMENT</li></center>
                    <li><a href="user_session.php">Sessions</a></li>
                    <li><a href="documents view.php">Documents </a></li>
                    <li><a href="#">Memorandum</a></li>
                    <li><a href="#">Resolution</a></li>
                    <li><a href="#">Ordinances</a></li>
                </ul>
            </nav>
        </aside>
        <main>
            <header>
                <div class="user-name">Profile</div>
            </header>
            <section class="documents-management">
                <div class="document-list">
                    <br>
                    <center><h3>DOCUMENTS LIST</h3></center>
                    <br>
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
                            
                        </tr>
                    </thead>
                    <tbody>
                        
                    <?php
                    if ($result->num_rows > 0) {
                        // Inside your document list loop in index.php
                        while($row = $result->fetch_assoc()) {
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
                            echo "</tr>";
                        }
                    } 
                    // Close the connection
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
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options

                info: true,
                ordering: true,
                paging: true,
                responsive: true,
                "order":[0, 'desc'],
                  
            });
           
            
        });

    </script>
    
</body>
</html>
