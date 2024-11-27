<?php
// Start the session and check if the trainer is logged in
session_start();
if (!isset($_SESSION['trainer_id'])) {
    header('Location: login.php'); // Redirect if not logged in
    exit();
}

$trainer_id = $_SESSION['trainer_id'];
include '../db_connect.php'; // Database connection

// Fetch trainer data from the database
$sql = "SELECT * FROM trainers WHERE trainer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $trainer_id);
$stmt->execute();
$result = $stmt->get_result();
$trainer = $result->fetch_assoc();

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $password = $_POST['password'];
    $password = password_hash($password, PASSWORD_DEFAULT);

    $specialization = $_POST['specialization'];
    $fun_fact = $_POST['fun_fact'];
    $status = $_POST['status'] ? 1 : 0;  // Convert status to 1 or 0

    // Update the trainer's profile in the database
    $update_sql = "UPDATE trainers SET fname = ?, lname = ?, password = ?, specialization = ?, fun_fact = ?, status = ? WHERE trainer_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssii", $fname, $lname, $password, $specialization, $fun_fact, $status, $trainer_id);
    $update_stmt->execute();

    // Redirect to the same page to show the updated information
    header('Location: trainer_profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            color: #333;
        }

        header {
            background-color: #4a90e2;
            padding: 20px;
            color: white;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        .nav-buttons {
            text-align: right;
            margin-top: 10px;
        }

        .nav-buttons a {
            text-decoration: none;
            color: #fff;
            background-color: #3498db;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .nav-buttons a:hover {
            background-color: #2980b9;
        }

        .content {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-card {
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .profile-card h3 {
            font-size: 1.8em;
            margin-bottom: 10px;
        }

        .profile-card p {
            margin-bottom: 10px;
        }

        .profile-card label {
            font-weight: bold;
        }

        .profile-card input, .profile-card select, .profile-card textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        .profile-card textarea {
            height: 100px;
            resize: vertical;
        }

        .status-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .status-toggle input {
            width: 50px;
            height: 30px;
            border-radius: 15px;
            position: relative;
            appearance: none;
            background-color: #ccc;
            transition: background-color 0.3s;
        }

        .status-toggle input:checked {
            background-color: #4CAF50;
        }

        .status-toggle input:checked::before {
            left: 25px;
        }

        .status-toggle input::before {
            content: '';
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: white;
            position: absolute;
            top: 5px;
            left: 5px;
            transition: left 0.3s;
        }

        .btn-save {
            background-color: #4a90e2;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-save:hover {
            background-color: #3498db;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <h1>FitInspire Hub</h1>
        </div>
        <div class="nav-buttons">
            <a href="../index.php">Home</a>
        </div>
    </header>
    
    <?php include 'trainer_navbar.php'; ?> 
    
    <div class="content">
        <h2>Trainer Profile</h2>
        <div class="profile-card">
            <form method="POST">
                <h3><?php echo $trainer['fname'] . ' ' . $trainer['lname']; ?></h3>
                <p><strong>Email:</strong> <?php echo $trainer['email']; ?></p>

                <label for="fname">First Name:</label>
                <input type="text" name="fname" id="fname" value="<?php echo $trainer['fname']; ?>" required>

                <label for="lname">Last Name:</label>
                <input type="text" name="lname" id="lname" value="<?php echo $trainer['lname']; ?>" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" value="<?php echo $trainer['password']; ?>" required>

                <label for="specialization">Specialization:</label>
                <input type="text" name="specialization" id="specialization" value="<?php echo $trainer['specialization']; ?>" required>

                <label for="fun_fact">Fun Fact:</label>
                <textarea name="fun_fact" id="fun_fact" required><?php echo $trainer['fun_fact']; ?></textarea>

                <div class="status-toggle">
                    <label for="status">Status:</label>
                    <input type="checkbox" name="status" id="status" <?php echo ($trainer['status'] == 1) ? 'checked' : ''; ?>>
                    <span><?php echo ($trainer['status'] == 1) ? 'Available' : 'Unavailable'; ?></span>
                </div>

                <button type="submit" class="btn-save">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>
