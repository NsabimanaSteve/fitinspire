<?php
// Start the session and check if the trainer is logged in
session_start();
if (!isset($_SESSION['trainer_id'])) {
    header('Location: ../login.php'); // Redirect if not logged in
    exit();
}

$trainer_id = $_SESSION['trainer_id'];
include '../db_connect.php'; // Database connection

// Fetch all premium users who have a booking with the trainer
$sql = "SELECT DISTINCT u.user_id, u.fname, u.lname 
        FROM bookings b 
        JOIN users u ON b.user_id = u.user_id 
        WHERE b.trainer_id = ? AND u.membershiptype_id = '3' 
        ORDER BY u.lname, u.fname";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $trainer_id);
$stmt->execute();
$users_result = $stmt->get_result();

// Handle form submission for new guidance entry
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_guidance'])) {
    $user_id = $_POST['user_id'];
    $nutrition_advice = $_POST['nutrition_advice'];
    $workouts = $_POST['workouts'];
    $techniques = $_POST['techniques'];

    // Insert new guidance into the guidance table
    $sql_insert = "INSERT INTO guidance (user_id, trainer_id, nutrition_advice, workouts, techniques) 
                   VALUES (?, ?, ?, ?, ?)";

    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iisss", $user_id, $trainer_id, $nutrition_advice, $workouts, $techniques);
    $stmt_insert->execute();

    // Redirect to the same page to show the newly added guidance
    header("Location: trainer_guidance.php");
    exit();
}

// Fetch all guidance entries made by the trainer
$sql_guidance = "SELECT g.guidance_id, g.nutrition_advice, g.workouts, g.techniques, u.fname, u.lname 
                 FROM guidance g 
                 JOIN users u ON g.user_id = u.user_id 
                 WHERE g.trainer_id = ? 
                 ORDER BY g.guidance_id DESC"; // No created_at field here
$stmt_guidance = $conn->prepare($sql_guidance);
$stmt_guidance->bind_param("i", $trainer_id);
$stmt_guidance->execute();
$guidance_result = $stmt_guidance->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Guidance</title>
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
        .content form input, .content form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
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
        <h2>Send Guidance to Premium Users</h2>

        <form action="trainer_guidance.php" method="POST">
            <label for="user_id">Select User</label>
            <select name="user_id" id="user_id" required>
                <option value="">Select a Premium User</option>
                <?php while ($user = $users_result->fetch_assoc()) { ?>
                    <option value="<?php echo $user['user_id']; ?>"><?php echo $user['fname'] . ' ' . $user['lname']; ?></option>
                <?php } ?>
            </select>

            <label for="nutrition_advice">Nutrition Advice</label>
            <textarea name="nutrition_advice" id="nutrition_advice" rows="4" required></textarea>

            <label for="workouts">Workouts</label>
            <textarea name="workouts" id="workouts" rows="4" required></textarea>

            <label for="techniques">Techniques</label>
            <textarea name="techniques" id="techniques" rows="4" required></textarea>

            <button type="submit" name="send_guidance" class="btn">Send Guidance</button>
        </form>

        <h2>Past Guidance Entries</h2>
        <table>
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Nutrition Advice</th>
                    <th>Workouts</th>
                    <th>Techniques</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $guidance_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['fname'] . ' ' . $row['lname']; ?></td>
                        <td><?php echo htmlspecialchars($row['nutrition_advice']); ?></td>
                        <td><?php echo htmlspecialchars($row['workouts']); ?></td>
                        <td><?php echo htmlspecialchars($row['techniques']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
