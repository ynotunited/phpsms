<?php
// Include database connection
require 'asset/inc/db.php';

// Define message status variables
$statusMessage = '';
$statusClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phoneNumbers = $_POST['phone_numbers'];
    $message = $_POST['message'];

    // Infobip API credentials
    $apiUrl = "https://e5dpd2.api.infobip.com/sms/2/text/advanced";
    $apiKey = "4d3923b49bf0f7581a413af5025c998b-e816e39f-d5c0-4feb-9ecf-7748903123c5";

    // Prepare phone numbers as an array
    $recipients = explode(',', $phoneNumbers);

    // Initialize status
    $status = false;

    // Make API request
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_HTTPHEADER => [
            "Authorization: App $apiKey",
            "Content-Type: application/json"
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([ 
            "messages" => array_map(function ($recipient) use ($message) {
                return [
                    "from" => "WebxpressTec",
                    "destinations" => [["to" => trim($recipient)]],
                    "text" => $message
                ];
            }, $recipients)
        ])
    ]);

    $response = curl_exec($curl);
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Handle the response
    if ($statusCode === 200) {
        $responseData = json_decode($response, true);

        // Extract message sending status for each recipient
        $successMessages = [];
        foreach ($responseData['messages'] as $msg) {
            if ($msg['status']['name'] === 'PENDING_ACCEPTED') {
                $successMessages[] = "Message sent to " . $msg['to'];
            } else {
                $successMessages[] = "Failed to send message to " . $msg['to'];
            }
        }

        // Log the message in the database
        $recipientsString = implode(',', $recipients);
        $stmt = $conn->prepare("INSERT INTO messages (recipients, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $recipientsString, $message);

        if ($stmt->execute()) {
            $statusMessage = "SMS successfully sent to " . implode(', ', $successMessages) . " and logged in the database!";
            $statusClass = 'success'; // Success class for styling
        } else {
            $statusMessage = "SMS sent, but failed to log in the database.";
            $statusClass = 'error'; // Error class for styling
        }

        $stmt->close();
    } else {
        $statusMessage = "Failed to send SMS. Please try again.";
        $statusClass = 'error'; // Error class for styling
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP SMS Response</title>
    <link href="https://fonts.googleapis.com/css2?family=Nexa:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="asset/favicon.png" type="image/png"> <!-- Favicon -->
    <style>
        body {
            font-family: 'Nexa', sans-serif;
            background: linear-gradient(to right, #2d5727, #96bc33);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            flex-direction: column; /* Align items in a column */
        }

        .logo {
            width: 360px;
            height: auto;
            margin-bottom: 30px;
        }

        .logo img {
            width: 70%;
        }

        h2 {
            font-size: 1.8rem;
            color: white; /* Set text color to white for better contrast */
            margin-bottom: 20px;
        }

        p {
            font-size: 1rem;
            color: white; /* White color for the paragraph text */
        }

        a {
            font-size: 1.2rem;
            color: #96bc33;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            color: #2d5727;
        }

        footer {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #fff;
            text-align: center;
        }

        .success {
            color: white; /* Change success message color to white */
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .error {
            color: white; /* Change error message color to white */
            font-size: 1rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="logo">
        <img src="asset/PHPsms-392x121.png" alt="Logo">
    </div>

    <!-- Display the success or error message -->
    <?php if ($statusMessage): ?>
        <h2 class="<?= $statusClass ?>"><?= $statusMessage ?></h2>
    <?php endif; ?>

    <!-- Return to home link -->
    <p><a href="index.php">Return to Home</a></p>

</body>
</html>
