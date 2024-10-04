<?php
// Include database connection file
include('dbconnect.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: index.php");
    exit();
}

// Check if a search query is submitted
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    // SQL query to search visitors or past visitors by PatientID, PatientName, or Room
    $sql = "SELECT visitors.*, patients.PatientName, patients.PatientIC, patients.PatientRoom 
            FROM visitors 
            JOIN patients ON visitors.PatientID = patients.PatientID
            WHERE patients.PatientID LIKE '%$searchQuery%' 
            OR patients.PatientName LIKE '%$searchQuery%' 
            OR patients.PatientRoom LIKE '%$searchQuery%'";
} else {
    // Default query when there's no search
    $sql = "SELECT visitors.*, patients.PatientName, patients.PatientIC, patients.PatientRoom 
            FROM visitors 
            JOIN patients ON visitors.PatientID = patients.PatientID";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Management Dashboard</title>
    <link rel="stylesheet" href="styledash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-box {
            width: 50%;
            padding: 10px;
            font-size: 16px;
        }
        .search-suggestions {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            display: none;
        }
        .search-suggestions li {
            padding: 10px;
            cursor: pointer;
            list-style-type: none; /* Remove bullet points */
        }
        .search-suggestions li:hover {
            background-color: #f0f0f0;
        }
    </style>

    <script>
        // JavaScript to dynamically filter suggestions while typing
        function filterSuggestions() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const suggestionsBox = document.getElementById('suggestions');
            const suggestions = document.querySelectorAll('.search-suggestions li');
            
            let hasMatches = false;
            suggestions.forEach((suggestion) => {
                if (suggestion.textContent.toLowerCase().includes(input)) {
                    suggestion.style.display = '';
                    hasMatches = true;
                } else {
                    suggestion.style.display = 'none';
                }
            });

            suggestionsBox.style.display = hasMatches && input !== '' ? 'block' : 'none';
        }

        function selectSuggestion(value) {
            document.getElementById('searchInput').value = value;
            document.getElementById('suggestions').style.display = 'none';
            document.getElementById('backButton').style.display = 'inline-block'; // Show back button
        }

        function goBack() {
            window.history.back(); // Go back to the previous page
        }
    </script>

</head>
<body>

<header>
    <h1>Visitor Management Dashboard</h1>
</header>

<div class="container">
    <div class="content">

        <!-- Search Form -->
        <div class="search-container">
            <form action="dashboard.php" method="get">
                <input type="text" id="searchInput" name="search" class="search-box" placeholder="Search by Patient ID, Name, or Room" onkeyup="filterSuggestions()">
                <button type="submit" class="btn">Search</button>

                <!-- Search Suggestions (this will be dynamically populated) -->
                <ul class="search-suggestions" id="suggestions">
                    <?php
                    // Fetch all patients for suggestion dropdown
                    $sqlPatients = "SELECT PatientID, PatientName, PatientRoom FROM patients";
                    $resultPatients = $conn->query($sqlPatients);
                    
                    if ($resultPatients && $resultPatients->num_rows > 0) {
                        while ($patient = $resultPatients->fetch_assoc()) {
                            echo "<li onclick=\"selectSuggestion('" . $patient['PatientName'] . "')\">" . 
                                 $patient['PatientName'] . " - Room: " . $patient['PatientRoom'] . 
                                 "</li>";
                        }
                    }
                    ?>
                </ul>
            </form>
            <!-- Back Button -->
            <button type="button" class="btn" onclick="goBack()" id="backButton" style="display:none;">Back</button>
        </div>

        <h2>Visitor Management</h2>
        <table class="visitor-table">
            <thead>
                <tr>
                    <th>Visitor Name</th>
                    <th>Phone</th>
                    <th>Patient</th>
                    <th>Patient IC</th>
                    <th>Room</th>
                    <th>Visiting Time</th>
                    <th>Exit Time</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($result) && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['VisitorName']."</td>";
                        echo "<td>".$row['VisitorPhone']."</td>";

                        // Safely check if Patient details exist
                        $patientName = isset($row['PatientName']) ? $row['PatientName'] : 'N/A';
                        $patientIC = isset($row['PatientIC']) ? $row['PatientIC'] : 'N/A';
                        $patientRoom = isset($row['PatientRoom']) ? $row['PatientRoom'] : 'N/A';

                        echo "<td>".$patientName."</td>";
                        echo "<td>".$patientIC."</td>";
                        echo "<td>".$patientRoom."</td>";
                        echo "<td>".$row['VisitingTime']."</td>";
                        echo "<td>".($row['ExitTime'] ?: 'N/A')."</td>";
                        echo "<td>";

                        // Calculate duration
                        $visitingTime = new DateTime($row['VisitingTime']);
                        $exitTime = $row['ExitTime'] ? new DateTime($row['ExitTime']) : null;
                        if ($exitTime) {
                            $duration = $visitingTime->diff($exitTime);
                            echo $duration->format('%h hours<br>%i minutes');
                        } else {
                            echo 'Still Visiting';
                        }

                        echo "</td>";
                        echo "<td>
                                <a href='update_visitor.php?id=".$row['VisitorID']."' title='Update'><i class='fas fa-edit'></i></a> | 
                                <a href='delete_visitor.php?id=".$row['VisitorID']."' title='Delete'><i class='fas fa-trash'></i></a> | 
                                <a href='exit_visitor.php?id=".$row['VisitorID']."' title='Exit'><i class='fas fa-sign-out-alt'></i></a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No visitors found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="links">
            <a href="register_visitor.php" class="btn">Register New Visitor</a>
            <a href="view_history.php" class="btn">View Deleted Visitors</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>
</div>

<footer>
    <p>Hospital Visitor Management System by LZ</p>
</footer>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
