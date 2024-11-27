<?php
session_start();
$user_id = $_SESSION['trainer_id'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
    <?php
        include 'trainer_navbar.php';
    ?>
    <div class="content" id="content">
        <div class="card">
            <h3><i class="fas fa-hand-peace"></i>Welcome, Trainer!</h3>
            <p>Feel free to manage your bookings and interact with your clients.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-directions"></i>Navigate through your dashboard here:</h3>
            <ul>
                <li><a href="schedule.php"><i class="fas fa-calendar-check"></i>Update Schedules</a></li>
                <li><a href="trainer_feedback.php"><i class="fas fa-comment-alt"></i>Check Feedback</a></li>
            </ul>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('minimized');
                content.classList.toggle('full-width');
            });
        });
    </script>
</body>
</html>