<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & FAQs</title>
    <link rel="stylesheet" href="styleindex.css"> <!-- Use the same stylesheet for consistent design -->
    <style>
        /* Centering the back button and adding spacing */
        .back-button-container {
            text-align: center; /* Center the button within this div */
            margin-top: 20px; /* Add space above the button container */
        }

        .back-button {
            display: inline-block; /* Allows for margins */
            padding: 10px 20px;
            background-color: #35963a; 
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none; /* Remove underline */
        }

        .back-button:hover {
            background-color: #095021; /* Darker shade on hover */
        }

        /* Align the contact info */
        .contact-info {
            margin-top: 10px; /* Add space above contact info */
            text-align: center; /* Center align contact info */
        }
    </style>
</head>
<body>
    <div class="faq-container">
        <h2>Frequently Asked Questions</h2>

        <div class="faq">
            <h3>1. How do I register as a visitor?</h3>
            <p>You can sign up as a visitor through the <a href="visitor_register.php">visitor registration page</a> where you need to fill out your information, such as name, phone number, and reason for visiting.</p>

            <h3>2. How do I log in as staff?</h3>
            <p>Staff members can log in using their provided credentials on the <a href="index.php">staff login page</a>. Ensure that your username and password are entered correctly.</p>

            <h3>3. What happens if I forget my password?</h3>
            <p>If you've forgotten your password, contact the IT department for assistance in resetting your login credentials.</p>
            
            <div class="contact-info">
                Liyana, Tech Developer (011-1252 6940) <br>
                Zaim, Tech Manager (013-801 7025)
            </div>

            <h3>4. How do I check the status of my visit?</h3>
            <p>You can check the visit status by logging into the system using your visitor credentials, and the system will display your current visit details.</p>

            <h3>5. Can I edit my visitor information?</h3>
            <p>Yes, you can update your visitor information by navigating to the "Manage Visitors" section after logging in as a staff member.</p>
        </div>
        
        <!-- Back Button -->
        <div class="back-button-container">
            <a href="index.php" class="back-button">Back to Login</a>
        </div>
    </div>
</body>
</html>
