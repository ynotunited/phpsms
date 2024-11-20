<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP SMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Nexa:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="asset/css/styles.css"> 
    <link rel="icon" href="asset/favicon.png" type="image/png">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="asset/PHPsms-392x121.png" alt="Logo">
        </div>
        <h1>PHP SMS Messages</h1>
        <p>Enter phone numbers (comma-separated) <br>and your message below.<br>
            Phone number must include country code <br>with no spaces e.g. +2348000000000</p>
        <form action="send_sms.php" method="POST" onsubmit="return validatePhoneNumber()">
            <input type="text" name="phone_numbers" id="phone_numbers" placeholder="Enter phone numbers" required>
            <input type="text" name="message" placeholder="Enter your message" required> <!-- Single line field -->
            <button type="submit">Send SMS</button>
        </form>
        <div id="error_message" class="error">Please enter a valid international phone number (e.g., +1234567890).</div>
    </div>
    
    <!-- Footer -->
    <footer>
        All rights reserved. &copy; <span id="currentYear"></span> Send SMS App. Version 1.0.1 | WebxpressTec MadeIt
    </footer>

    <script>
        function validatePhoneNumber() {
            let phoneNumbers = document.getElementById('phone_numbers').value.trim();
            phoneNumbers = phoneNumbers.replace(/\s+/g, ''); // Remove spaces
            const regex = /^\+(\d{1,4})\d{1,14}(?:,\s*\+\d{1,4}\d{1,14})*$/;

            if (!regex.test(phoneNumbers)) {
                document.getElementById('error_message').style.display = 'block';
                return false;
            }

            document.getElementById('error_message').style.display = 'none';
            return true;
        }

        // Set the current year dynamically
        document.getElementById('currentYear').textContent = new Date().getFullYear();
    </script>
</body>
</html>
