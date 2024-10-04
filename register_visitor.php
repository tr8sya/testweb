<?php
include('dbconnect.php');
session_start();

// Set the default timezone to Malaysia (UTC+8)
date_default_timezone_set('Asia/Kuala_Lumpur');

if (!isset($_SESSION['UserID'])) {
    header("Location: index.php");
    exit();
}

$error = ''; // Variable to hold error messages
$successMessage = ''; // Variable to hold success message

if (isset($_POST['register'])) {
    $visitorName = $_POST['visitorname'];
    $visitorPhone = $_POST['visitorphone'];
    $patientID = $_POST['patientid'];
    $visitingTime = date('Y-m-d H:i:s'); // Get the current Malaysian time

    // Validate the visitor name
    if (empty($visitorName)) {
        $error = "Visitor name is required.";
    }

    // Validate the visitor phone number
    if (empty($visitorPhone)) {
        $error .= "<br>Visitor phone number is required.";
    } elseif (!preg_match('/^01\d{10}$/', $visitorPhone)) {
        $error .= "<br>Phone number must start with '01' and be exactly 12 digits long.";
    } else {
        // Check if the phone number already exists in the visitors table
        $checkPhone = $conn->query("SELECT * FROM visitors WHERE VisitorPhone = '$visitorPhone'");
        if ($checkPhone->num_rows > 0) {
            $error .= "<br>Visitor with this phone number has already registered.";
        }
    }

    // Proceed if there are no errors
    if (empty($error)) {
        $checkPatient = $conn->query("SELECT * FROM patients WHERE PatientID = '$patientID'");
        if ($checkPatient->num_rows > 0) {
            $sql = "INSERT INTO visitors (VisitorName, VisitorPhone, PatientID, VisitingTime) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssis", $visitorName, $visitorPhone, $patientID, $visitingTime);
            if ($stmt->execute()) {
                $successMessage = "Visitor registered successfully."; // Set success message
            } else {
                $error .= "<br>Error: " . $stmt->error;
            }
        } else {
            $error .= "<br>Invalid Patient ID.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitor Management System</title>
    <link rel="stylesheet" href="styleregister.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap">
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>Visitor Management System</h1>
    </header>

    <div class="container">
        <h2>Register Visitor</h2>

        <?php
        // Display error messages if any
        if (!empty($error)) {
            echo "<div class='error-message' style='color: red;'>$error</div>";
        }

        // Display success message if set
        if (!empty($successMessage)) {
            echo "<div class='success-message' style='color: green;'>$successMessage</div>";
        }
        ?>

        <form action="register_visitor.php" method="POST" onsubmit="return validateForm()">
            <label for="visitorname">Visitor Name:</label>
            <input type="text" id="visitorname" name="visitorname" required><br>
            
            <label for="visitorphone">Visitor Phone:</label>
            <input type="text" id="visitorphone" name="visitorphone" required><br>
            
            <label for="patientSearch">Search Patient:</label>
            <input type="text" id="patientSearch" placeholder="Search by name or ID"><br>
            
            <div class="dropdown" id="patientDropdown"></div> <!-- Dropdown for patient selection -->
            
            <label for="patientID">Patient ID:</label>
            <input type="number" id="patientID" name="patientid" readonly required><br>
            
            <button type="submit" name="register">Register Visitor</button>
            
            <div class="nav-button-container">
                <a href="dashboard.php" class="button">Back to Dashboard</a>
                <a href="logout.php" class="button btn-logout">Logout</a>
            </div>
        </form>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>Hospital Visitor Management System by LZ</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const MAX_PHONE_LENGTH = 12; // Set your desired maximum length here

        function validateForm() {
            const phoneInput = document.getElementById('visitorphone');
            if (phoneInput.value.length > MAX_PHONE_LENGTH) {
                alert(`Visitor phone number cannot exceed ${MAX_PHONE_LENGTH} characters.`);
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }

        // Show alert for successful registration if set in PHP
        <?php if (!empty($successMessage)) { ?>
            alert("<?php echo $successMessage; ?>");
        <?php } ?>

        $(document).ready(function() {
            $('#patientSearch').on('input', function() {
                var query = $(this).val();
                if (query.length > 0) {
                    $.ajax({
                        url: "search_patient.php",
                        method: "GET",
                        data: { query: query },
                        success: function(data) {
                            var patients = JSON.parse(data);
                            var dropdownContent = $('#patientDropdown');
                            dropdownContent.empty(); // Clear previous results

                            if (patients.length > 0) {
                                patients.forEach(function(patient) {
                                    dropdownContent.append(
                                        `<div onclick="selectPatient(${patient.PatientID}, '${patient.PatientName}')">
                                            ${patient.PatientName} (ID: ${patient.PatientID})
                                        </div>`
                                    );
                                });
                                dropdownContent.show(); // Show the dropdown
                            } else {
                                dropdownContent.hide(); // Hide dropdown if no patients found
                            }
                        }
                    });
                } else {
                    $('#patientDropdown').hide(); // Hide dropdown if input is empty
                }
            });
        });

        // Function to handle patient selection
        function selectPatient(patientID, patientName) {
            $('#patientID').val(patientID); // Set Patient ID
            $('#patientSearch').val(patientName); // Set Patient Name
            $('#patientDropdown').hide(); // Hide the dropdown
        }
    </script>
</body>
</html>
