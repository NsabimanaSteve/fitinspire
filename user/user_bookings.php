<?php
session_start();
require_once '../db_connect.php'; // Adjusted path for project structure

$user_id = $_SESSION['user_id'];
$membership_type = $_SESSION['membership_type'];
/*
if ($membership_type == 1) {
    // Basic User: Can only access home, profile, and memberships
    $restricted_pages = ['user_bookings.php', 'user_feedback.php', 'user_guidance.php'];
} elseif ($membership_type == 2) {
    // Standard User: Can access bookings, home, profile, and memberships
    $restricted_pages = ['user_feedback.php', 'user_guidance.php'];
} else {
    // Premium User: No restrictions
    $restricted_pages = [];
}*/

if ($membership_type == 1) {
    header('location: user_memberships.php'); // Redirect to upgrade membership page if membership is not high enough for booking
    echo "Your membership is not currently high enough for trainer booking.";
    exit();
} else {

    include "user_navbar.php";
    //$user_id = $_SESSION['user_id'];
    //$membership_type = $_SESSION['membership_type'];
}

// Fetch active trainers (status = 1)
$trainer_sql = "SELECT * FROM trainers WHERE status = 1";
$trainer_result = mysqli_query($conn, $trainer_sql);

// Handle booking submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['trainer_id']) && isset($_POST['booking_date'])) {
    $trainer_id = $_POST['trainer_id'];
    $booking_date = $_POST['booking_date'];

    // Insert the booking into the bookings table
    $insert_booking_sql = "INSERT INTO bookings (user_id, trainer_id, booking_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_booking_sql);
    $stmt->bind_param("iis", $user_id, $trainer_id, $booking_date);
    $stmt->execute();

    // After successful booking, fetch the user's booking history
    $booking_history_sql = "SELECT b.booking_date, t.fname, t.lname, t.specialization FROM bookings b JOIN trainers t ON b.trainer_id = t.trainer_id WHERE b.user_id = ? ORDER BY b.booking_date DESC";
    $stmt = $conn->prepare($booking_history_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $booking_history_result = $stmt->get_result();
} else {
    // Fetch the user's booking history if no new booking is made
    $booking_history_sql = "SELECT b.booking_date, t.fname, t.lname, t.specialization FROM bookings b JOIN trainers t ON b.trainer_id = t.trainer_id WHERE b.user_id = ? ORDER BY b.booking_date DESC";
    $stmt = $conn->prepare($booking_history_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $booking_history_result = $stmt->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking | FitInspire Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Enhanced Trainers Section */
        .trainers {
            padding: 80px 20px;
            background-color: #fff;
            text-align: center;
        }

        .trainers-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 20px;
        }

        .trainer-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .trainer-card:hover {
            transform: translateY(-10px);
        }


        .trainer-info {
            padding: 25px;
            text-align: center;
        }

        .trainer-info h3 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .trainer-info p {
            color: #666;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-links a {
            color: #BC1E4A;
            font-size: 1.2rem;
            transition: color 0.3s;
            padding: 8px;
        }

        .social-links a:hover {
            color: #8A1435;
        }

        .booking-form {
            text-align: center;
            margin-top: 30px;
        }

        .booking-form input[type="date"] {
            padding: 10px;
            font-size: 1rem;
            margin: 10px 0;
            border: 1px solid #BC1E4A;
            border-radius: 5px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #BC1E4A;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #8A1435;
        }

        .booking-history {
            margin-top: 50px;
            text-align: center;
        }

        .booking-history table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .booking-history th, .booking-history td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .booking-history th {
            background-color: #BC1E4A;
            color: white;
        }

        .booking-history td {
            background-color: #f9f9f9;
        }

        .booking-history td a {
            color: #BC1E4A;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php  ?>

        <div class="dashboard-main">
            <div class="dashboard-content">
                <!-- Trainers Section -->
                <section id="trainers" class="trainers">
                    <h2 class="section-title">Book a session with Our Trainers</h2>
                    <div class="trainers-container">
                        <?php while ($trainer = mysqli_fetch_assoc($trainer_result)): ?>
                            <div class="trainer-card">
                                <img src="https://images.unsplash.com/photo-1517841905240-8b11d1d3c5e6" alt="Trainer 1">
                                <div class="trainer-info">
                                    <h3><?php echo $trainer['fname'] . ' ' . $trainer['lname']; ?></h3>
                                    <p>Specializes in: <?php echo $trainer['specialization']; ?></p>
                                    <form method="POST" class="booking-form">
                                        <input type="hidden" name="trainer_id" value="<?php echo $trainer['trainer_id']; ?>">
                                        <input type="date" name="booking_date" required>
                                        <button type="submit" class="btn">Book Now</button>
                                    </form>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </section>

                <!-- Booking History Section -->
                <section class="booking-history">
                    <h2>Your Booking History</h2>
                    <?php if ($booking_history_result->num_rows > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Trainer Name</th>
                                    <th>Specialization</th>
                                    <th>Booking Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($booking = $booking_history_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $booking['fname'] . ' ' . $booking['lname']; ?></td>
                                        <td><?php echo $booking['specialization']; ?></td>
                                        <td><?php echo date("Y-m-d", strtotime($booking['booking_date'])); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No bookings found. Please make a booking above.</p>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>
</body>
</html>
