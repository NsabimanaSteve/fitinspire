<?php
session_start();
require_once '../db_connect.php'; // Include your database connection

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('location: user_index.php');
    exit();
}

// Initialize messages
$error_message = '';
$success_message = '';

// Handle profile update logic
if (isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id'];
    $first_name = trim($_POST['fname']);
    $last_name = trim($_POST['lname']);
    $password = trim($_POST['password']);

    // Prepare SQL components for password update if provided
    $password_query = '';
    $password_param = [];
    if (!empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $password_query = ", password = ?";
        $password_param = [$password_hashed];
    }

    // Construct and execute the update query
    $sql = "UPDATE users SET fname = ?, lname = ? {$password_query} WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $params = array_merge([$first_name, $last_name], $password_param, [$user_id]);
    $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

    if ($stmt->execute()) {
        $success_message = 'Profile updated successfully!';
    } else {
        $error_message = 'Failed to update profile. Please try again.';
    }
}

// Fetch the current user profile data
$user_id = $_SESSION['user_id'];
$sql = "SELECT fname, lname, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile | FitInspire Hub</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    
</head>
<body>
<div class = "dashboard-container"> 
        <?php
            include 'user_navbar.php';
        ?>

        <div class="dashboard-main">
            <div class="dashboard-content">
                <h2>Update Your Profile</h2>
                    <?php if ($success_message): ?>
                        <div class="message success"><?= htmlspecialchars($success_message); ?></div>
                    <?php endif; ?>
                    <?php if ($error_message): ?>
                        <div class="message error"><?= htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>
                        <form method="POST" action="user_profile.php">
                            <div class="mb-3">
                                <label for="fname" class="form-label">First Name</label>
                                <input type="text" name="fname" id="fname" class="form-control" value="<?= htmlspecialchars($user['fname']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="lname" class="form-label">Last Name</label>
                                <input type="text" name="lname" id="lname" class="form-control" value="<?= htmlspecialchars($user['lname']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email (Read-only)</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password (Leave empty to retain old password)</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary w-100">Update Profile</button>
                        </form>
            </div>
        </div>

</div>
    

</body>
</html>
