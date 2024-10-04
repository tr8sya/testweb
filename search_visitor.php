<?php
include('dbconnect.php'); // Connect to the database

if (isset($_GET['query'])) {
    $search = $_GET['query'];

    // SQL query to search for visitors by name or ID
    $stmt = $conn->prepare("SELECT VisitorID, VisitorName FROM visitors WHERE VisitorName LIKE ? OR VisitorID LIKE ?");
    $likeSearch = '%' . $search . '%';
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
    $stmt->execute();
    $result = $stmt->get_result();

    // Return matching visitors in JSON format
    $visitors = [];
    while ($row = $result->fetch_assoc()) {
        $visitors[] = $row;
    }

    echo json_encode($visitors);
}
?>
