<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
include "admin_navbar.php";
include '../db_connect.php'; // Database connection

// Handle delete action
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // Securely cast to integer
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: user_management.php?message=User+deleted+successfully");
    } else {
        echo "Error deleting user: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all users
$query = "SELECT user_id, fname, lname, email FROM users";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching users: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="css/admin_styles.css">
</head>
<body>
    <div class="main-content">
        <h2>User Management</h2>

        <!-- Display success or error messages -->
        <?php if (isset($_GET['message'])): ?>
            <p class="success-message"><?= htmlspecialchars($_GET['message']) ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['user_id']) ?></td>
                        <td><?= htmlspecialchars($row['fname']) ?></td>
                        <td><?= htmlspecialchars($row['lname']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <a href="user_management.php?delete=<?= $row['user_id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
