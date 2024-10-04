<?php
include('dbconnect.php'); // Connect to the database
session_start();

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: index.php"); // Redirect to login if not logged in
    exit();
}

// Prepare SQL query based on search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT dv.VisitorName, dv.VisitorPhone, p.PatientName, p.PatientIC, p.PatientRoom, 
        dv.VisitingTime, dv.ExitTime 
        FROM deleted_visitors dv 
        JOIN patients p ON dv.PatientID = p.PatientID 
        WHERE p.PatientID LIKE ? 
        OR p.PatientName LIKE ? 
        OR p.PatientRoom LIKE ?";

$stmt = $conn->prepare($sql);
$likeQuery = "%" . $searchQuery . "%";
$stmt->bind_param("sss", $likeQuery, $likeQuery, $likeQuery);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Visitor History</title>
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
        }
        
        function goBack() {
            window.history.back(); // Go back to the previous page
        }
    </script>
</head>
<body>

<header>
    <h1>Visitor History</h1>
</header>

<div class="container">
    <div class="content">
        <!-- Search Form -->
        <div class="search-container">
            <form action="view_history.php" method="get">
                <input type="text" id="searchInput" name="search" class="search-box" placeholder="Search by Patient ID, Name, or Room" onkeyup="filterSuggestions()">
                <button type="submit" class="btn">Search</button>

                <!-- Search Suggestions (dynamically populated) -->
                <ul class="search-suggestions" id="suggestions">
                    <?php
                    // Fetch all patients for suggestion dropdown
                    $sqlPatients = "SELECT PatientID, PatientName, PatientRoom FROM patients";
                    $resultPatients = $conn->query($sqlPatients);
                    
                    if ($resultPatients && $resultPatients->num_rows > 0) {
                        while ($patient = $resultPatients->fetch_assoc()) {
                            echo "<li onclick=\"selectSuggestion('" . htmlspecialchars($patient['PatientName']) . "')\">" . 
                                 htmlspecialchars($patient['PatientName']) . " - Room: " . htmlspecialchars($patient['PatientRoom']) . 
                                 "</li>";
                        }
                    }
                    ?>
                </ul>
            </form>
            <!-- Back Button -->
            <button type="button" class="btn" onclick="goBack()" id="backButton" style="display:none;">Back</button>
        </div>

        <h2>Deleted Visitors</h2>
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
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['VisitorName']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['VisitorPhone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['PatientName'] ?? 'N/A') . "</td>";
                        echo "<td>" . htmlspecialchars($row['PatientIC'] ?? 'N/A') . "</td>";
                        echo "<td>" . htmlspecialchars($row['PatientRoom'] ?? 'N/A') . "</td>";
                        echo "<td>" . htmlspecialchars($row['VisitingTime']) . "</td>";
                        echo "<td>" . ($row['ExitTime'] ? htmlspecialchars($row['ExitTime']) : 'N/A') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No deleted visitors found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="links">
            <a href="dashboard.php" class="btn">Back to Dashboard</a>
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
