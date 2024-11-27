<?php
// Start the session and check if the trainer is logged in
session_start();
if (!isset($_SESSION['trainer_id'])) {
    header('Location: ../login.php'); // Redirect if not logged in
    exit();
}

$trainer_id = $_SESSION['trainer_id'];
include '../db_connect.php'; // Database connection

// Fetch feedback for the logged-in trainer
$sql = "SELECT f.feedback_id, f.feedback_text, f.created_at, u.fname, u.lname 
        FROM feedback f 
        JOIN users u ON f.user_id = u.user_id 
        WHERE f.trainer_id = ? 
        ORDER BY f.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $trainer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
        }
        header {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }
        header .nav-buttons a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
            padding: 5px 10px;
            background: #444;
            border-radius: 4px;
        }
        header .nav-buttons a:hover {
            background: #555;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 50px;
            width: 250px;
            height: 100%;
            background-color: #222;
            padding: 20px 0;
            overflow-y: auto;
            z-index: 500;
        }
        .sidebar a {
            color: #ddd;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            border-bottom: 1px solid #333;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #444;
            color: #fff;
        }
        .content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
        }
        .content h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #fff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #f4f4f4;
        }
        .btn {
            padding: 8px 12px;
            background: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-delete {
            background: #dc3545;
        }
        .btn-delete:hover {
            background: #b02a37;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <h1>FitInspire Hub</h1>
        </div>
    </header>
    <?php
        include 'trainer_navbar.php';
    ?>
    <div class="content">
        <h2>Feedback</h2>
        <table>
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Feedback</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                        <td><?php echo htmlspecialchars($row['feedback_text']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <!--
                            <td>
                            <button class="btn">Mark Resolved</button>
                            <button class="btn btn-delete">Delete</button>
                            </td>
                        -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
