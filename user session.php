<?php
session_start();

if(isset($_SESSION['userType'])) {
    if($_SESSION['userType'] == "admin") {
         header("Location: index.php");
    } 
    if($_SESSION['userType'] == "user") {
         header("Location: user_session.php");
    } 
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
    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- FontAwesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Ensure the body fills the viewport */
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
    padding: 20px 20px;
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
    width: 250px;
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
    <div class="container">
        <aside class="sidebar">
                        <?php include('sidebar.php');?>
            <nav>
                <ul>
                    <li><a href="#">Dashboard</a></li>
                  <center><li class="header">LEGISLATIVE MANAGEMENT</li></center>  
                    <li><a href="user session.php">Sessions</a></li>
                    <li><a href="documents view.php">Documents</a></li>
                    <li><a href="#">Memorandum</a></li>
                    <li><a href="#">Resolution</a></li>
                    <li><a href="#">Ordinances</a></li>
                </ul>
            </nav>
        </aside>
        <main>
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

            <section class="Documents-management">
                <div class="add-document">
                <center> <h3>Session View</h3></center>
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
            echo "<div class='sessions-item'>";
            echo "<p><strong>Session Name:</strong> " . htmlspecialchars($row["session_name"]) . "</p>";
            echo "<p><strong>Start date and time:</strong> " . htmlspecialchars($row["start_datetime"]) . "</p>";
            echo "<p><strong>End date and time:</strong> " . htmlspecialchars($row["end_datetime"]) . "</p>";

            echo "</div>";
        }
    } else {
        echo "<p>No documents found</p>";
    }
}

// Close the connection
$conn->close();
?>

                </div>


        </div>
                    
            </div>
        </main>
    </div>
</body>
</html>

