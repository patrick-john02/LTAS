<?php
session_start();

if(!isset($_SESSION['userType'])) {
    header("Location: login.php");
}


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
    <script src="assets/jquery-3.7.1.js"></script>
    <script src="assets/dataTables.js"></script>
    <link rel="stylesheet" href="assets/dataTables.dataTables.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
                <?php include('sidebar.php');?>
            <nav>
                <?php include('user_menu.php');?>
            </nav>

        </aside>
        <main>
            <header>
                <div class="user-name">Welcome <?php echo $_SESSION['username'];?>, <a href='logout.php'>Logout</a></div>
            </header>
            <section class="Documents-management">
                <div class="add-document">
                    <br>
                    <center> <h3>SESSION VIEW</h3></center>
                    <table id="doc-table">
                    <thead>
                        <tr>
                            <th>Session ID</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    <?php
                    include('config.php');
                    
                    // Your SQL query
                    $sql = "SELECT * FROM sessions";
                    $result = $conn->query($sql);
                    
                    if ($result === false) {
                        echo "<p>Query error: " . $conn->error . "</p>";
                    } else {
                        if ($result->num_rows > 0) {
                            // Inside your document list loop in index.php
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["session_name"] . "</td>";
                                echo "<td>" . $row["start_datetime"] . "</td>";
                                echo "<td>" . $row["end_datetime"] . "</td>";
                                echo "</tr>";
                            }
                        } 
                    }
                    
                    // Close the connection
                    $conn->close();
                    ?>
                </tbody>
                </table>
                </div>


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
        });

       
    
    
    </script>
</body>
</html>

