<?php
session_start();
require_once '../db_connect.php'; // Adjusted path for project structure

$user_id = $_SESSION['user_id'];
$membership_type = $_SESSION['membership_type'];

if ($membership_type == 1 || $membership_type == 2) {
    header('location: user_memberships.php'); // Redirect to upgrade membership page if membership is not high enough for feedback
    exit();
} else {
    include "user_navbar.php";
}

// Fetch user's past bookings to display relevant trainers
$sql = "SELECT DISTINCT t.trainer_id, t.fname, t.lname, t.specialization
        FROM bookings b
        JOIN trainers t ON b.trainer_id = t.trainer_id
        WHERE b.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$trainers = [];
while ($row = $result->fetch_assoc()) {
    $trainers[] = $row;
}

// Handle feedback submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $feedback = $_POST["feedback_text"];
    $trainer_id = $_POST["trainer_name"];

    // Insert the feedback into the database
    $sql = "INSERT INTO feedback (user_id, trainer_id, feedback_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $trainer_id, $feedback);
    if ($stmt->execute()) {
        $feedback_message = "Thank you for your feedback!";
    } else {
        $feedback_message = "There was an error submitting your feedback. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback | FitInspire Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .dashboard-main {
            background-color: white;
            margin: 20px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            font-size: 1.1rem;
            margin-top: 10px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background-color: #BC1E4A;
            color: white;
            font-size: 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #8A1435;
        }

        .feedback-message {
            margin-top: 20px;
            text-align: center;
            font-size: 1.2rem;
            color: #4CAF50;
        }

        .trainer-selection {
            margin-top: 20px;
        }

        .trainer-selection select {
            width: 100%;
            font-size: 1rem;
        }

        .section-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php  ?>

        <div class="dashboard-main">
            <h1>Submit Feedback</h1>
            <form method="post" action="user_feedback.php">
                <div class="trainer-selection">
                    <label for="trainer_name">Select the trainer you want to give feedback on:</label>
                    <select name="trainer_name" id="trainer_name" required>
                        <option value="" disabled selected>Select a Trainer</option>
                        <?php foreach ($trainers as $trainer): ?>
                            <option value="<?= $trainer['trainer_id'] ?>"><?= $trainer['fname'] . ' ' . $trainer['lname'] ?> - <?= $trainer['specialization'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <label for="feedback_text">Enter your feedback here:</label>
                <textarea name="feedback_text" rows="6" placeholder="Your thoughts, comments, and expectations..." required></textarea>

                <button type="submit" class="btn">Submit Feedback</button>
            </form>

            <?php if (isset($feedback_message)): ?>
                <div class="feedback-message"><?= $feedback_message ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
