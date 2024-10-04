<?php
include('dbconnect.php');

if (isset($_GET['q'])) {
    $searchTerm = $_GET['q'];

    // Search by PatientID, PatientName, or PatientRoom
    $sql = "SELECT * FROM patients WHERE PatientID LIKE ? OR PatientName LIKE ? OR PatientRoom LIKE ?";
    $stmt = $conn->prepare($sql);
    $likeTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("sss", $likeTerm, $likeTerm, $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $patientName = htmlspecialchars($row['PatientName']);
            $patientID = htmlspecialchars($row['PatientID']);
            $patientRoom = htmlspecialchars($row['PatientRoom']);
            echo "<div class='search-result-item' onclick=\"selectResult('$patientID')\">$patientID - $patientName (Room $patientRoom)</div>";
        }
    } else {
        echo "<div class='search-result-item'>No matches found</div>";
    }
}
?>
