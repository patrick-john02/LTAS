<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<ul>
    <li><a href="user_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    <!-- <li class="header">LEGISLATIVE MANAGEMENT</li> -->
    <!-- <li><a href="user_session.php"><i class="fas fa-calendar-alt"></i> SESSIONS</a></li> -->
    <li class="1header">
        <button class="dropdown-btn"><i class="fas fa-check-circle"></i> APPROVED DOCUMENTS &nbsp;&nbsp;&#10225;<i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <a href="documents_resolution_approved.php"><i class="fas fa-scroll"></i> Resolution</a>
            <a href="documents_ordinaces_approved.php"><i class="fas fa-gavel"></i> Ordinances</a>
        </div>
    </li>
    <li class="1header">
        <button class="dropdown-btn"><i class="fas fa-share-square"></i> SENT DOCUMENTS &nbsp;&nbsp;&#10225;<i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-container">
            <a href="documents_resolution_sent.php"><i class="fas fa-scroll"></i> Resolution</a>
            <a href="documents_ordinaces_sent.php"><i class="fas fa-gavel"></i> Ordinances</a>
        </div>
    </li>
    <!-- <li class="1header">
    <button class="dropdown-btn">
        <i class="fas fa-archive"></i> ARCHIVES &nbsp;&nbsp;&#10225;<i class="fa fa-caret-down"></i>
    </button>
    <div class="dropdown-container">
        <a href="archive_resolution.php"><i class="fas fa-file-alt"></i> Archive Resolution</a>
        <a href="archive_ordinance.php"><i class="fas fa-file-contract"></i> Archive Ordinances</a>
    </div>
</li> -->
<li><a href="Userarchive_document.php"><i class="fas fa-archive"></i> Archive</a></li>


<script>
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>
