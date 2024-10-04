<?php
include('dbconnect.php');

// Check if the search query is set
if (isset($_GET['query'])) {
    $searchTerm = $_GET['query'];

    // Prepare the SQL statement to prevent SQL injection
    $sql = "SELECT PatientID, PatientName, PatientRoom FROM patients WHERE PatientName LIKE ? OR PatientID LIKE ?";
    $stmt = $conn->prepare($sql);
    
    // Create a wildcard search term
    $likeTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("ss", $likeTerm, $likeTerm);
    
    // Execute the prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    $patients = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $patients[] = array(
                'PatientID' => $row['PatientID'],
                'PatientName' => $row['PatientName'],
                'PatientRoom' => $row['PatientRoom']
            );
        }
    }

    // Return data in JSON format
    echo json_encode($patients);
    exit; // Exit to prevent the HTML from being rendered after JSON response
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Patient</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script>
        function searchPatients() {
            const query = document.getElementById("searchBox").value;
            const dropdown = document.getElementById("dropdown");

            // Clear the previous results
            dropdown.innerHTML = "";

            // Make sure the query is not empty
            if (query.length === 0) {
                dropdown.style.display = "none";
                return;
            }

            // Create an AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "?query=" + encodeURIComponent(query), true);
            xhr.onload = function () {
                if (this.status === 200) {
                    const patients = JSON.parse(this.responseText);

                    // If there are patients, display them in the dropdown
                    if (patients.length > 0) {
                        dropdown.style.display = "block";
                        patients.forEach(patient => {
                            const div = document.createElement("div");
                            div.innerText = `${patient.PatientName} (ID: ${patient.PatientID}, Room: ${patient.PatientRoom})`;
                            div.onclick = function () {
                                document.getElementById("searchBox").value = patient.PatientName; // Set selected patient name
                                dropdown.style.display = "none"; // Hide dropdown
                            };
                            dropdown.appendChild(div);
                        });
                    } else {
                        dropdown.style.display = "none"; // Hide dropdown if no results
                    }
                }
            };
            xhr.send();
        }
    </script>
    <style>
        /* Simple styling for the dropdown */
        .dropdown {
            display: none; /* Hidden by default */
            border: 1px solid #ccc; /* Light gray border */
            position: absolute; /* Position below input */
            background-color: white; /* White background */
            z-index: 1; /* Stack above other elements */
            max-height: 150px; /* Max height for dropdown */
            overflow-y: auto; /* Scrollable if too many items */
            width: 100%; /* Full width */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow for depth */
            border-radius: 5px; /* Rounded corners */
        }

        /* Individual dropdown item styling */
        .dropdown div {
            padding: 10px; /* Inner padding */
            cursor: pointer; /* Pointer cursor */
            color: #333; /* Dark text color */
            transition: background-color 0.3s; /* Smooth transition */
        }

        /* Hover effect for dropdown items */
        .dropdown div:hover {
            background-color: #007bff; /* Match button color */
            color: white; /* White text on hover */
        }

        /* Input box styling */
        #searchBox {
            width: 80%; /* Full width */
            padding: 10px; /* Inner padding */
            border: 1px solid #ccc; /* Light gray border */
            border-radius: 5px; /* Rounded corners */
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Search Patient</h1>
        <input type="text" id="searchBox" class="form-control" placeholder="Enter Patient Name or ID" onkeyup="searchPatients()">
        <div id="dropdown" class="dropdown"></div>
    </div>
</body>
</html>
