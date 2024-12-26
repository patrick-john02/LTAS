<?php
session_start();
ob_start(); // Output buffering
include('config.php');
include('./includes/navbar.php');
include('./includes/sidebar.php');

// Check if the user is logged in
if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

// Fetch the logged-in user's username
$current_user = $_SESSION['Username'];
?>

<h3 class="card-title">Your Resolutions</h3>
<div class="card-body">
    <!-- Status Filtering -->
    <form method="GET" id="status-filter-form" class="mb-3">
        <label for="status-filter">Filter by Status:</label>
        <select name="status_filter" id="status-filter" class="form-control" style="width: 200px; display: inline-block;">
            <option value="">All</option>
            <option value="Pending" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] === 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="First Reading" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] === 'First Reading') echo 'selected'; ?>>First Reading</option>
            <option value="Second Reading" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] === 'Second Reading') echo 'selected'; ?>>Second Reading</option>
            <option value="Approved" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] === 'Approved') echo 'selected'; ?>>Approved</option>
            <option value="Rejected" <?php if (isset($_GET['status_filter']) && $_GET['status_filter'] === 'Rejected') echo 'selected'; ?>>Rejected</option>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Date Filtering -->
    <form method="GET" id="date-filter-form" class="mb-3">
        <label for="start-date">Filter by Date:</label>
        <input type="date" name="start_date" id="start-date" class="form-control" style="width: 200px; display: inline-block;" 
        value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
        <span>to</span>
        <input type="date" name="end_date" id="end-date" class="form-control" style="width: 200px; display: inline-block;"
        value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Table -->
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Resolution No.</th>
                <th>Title</th>
                <th>Date Published</th>
                <th>Category</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Prepare SQL query to fetch user-specific documents
            $statusFilter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
            $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
            $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';

            $sql = "SELECT resolution_no, Title, `Date Published`, Category, d_status
                    FROM documents 
                    WHERE isArchive = 0 
                    AND Author = ?";

            // Append filters
            $params = [$current_user];
            $types = "s";

            if (!empty($statusFilter)) {
                $sql .= " AND d_status = ?";
                $params[] = $statusFilter;
                $types .= "s";
            }

            if (!empty($startDate) && !empty($endDate)) {
                $sql .= " AND `Date Published` BETWEEN ? AND ?";
                $params[] = $startDate;
                $params[] = $endDate;
                $types .= "ss";
            }

            $sql .= " ORDER BY `Date Published` DESC";

            // Prepare and execute query
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['resolution_no']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
                    echo "<td>" . date('Y-m-d', strtotime($row['Date Published'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Category']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['d_status']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>No documents found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- DataTables & Scripts -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script>
$(function () {
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "order": [[2, 'desc']], // Order by date published
    });
});
</script>
</body>
</html>
