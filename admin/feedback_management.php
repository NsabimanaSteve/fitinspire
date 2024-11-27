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
    $stmt = $conn->prepare("DELETE FROM feedback WHERE feedback_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: feedback_management.php?message=Feedback+deleted+successfully");
    } else {
        echo "Error deleting feedback: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all feedback with user details
$query = "
    SELECT f.feedback_id, f.feedback_text, u.fname, u.lname 
    FROM feedback f
    JOIN users u ON f.user_id = u.user_id
    ORDER BY f.feedback_id DESC";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching feedback: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
    <link rel="stylesheet" href="css/admin_styles.css">
</head>
<body>
    <div class="main-content">
        <h2>Feedback Management</h2>

        <!-- Display success or error messages -->
        <?php if (isset($_GET['message'])): ?>
            <p class="success-message"><?= htmlspecialchars($_GET['message']) ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Feedback</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['feedback_id']) ?></td>
                        <td><?= htmlspecialchars($row['fname'] . ' ' . $row['lname']) ?></td>
                        <td><?= htmlspecialchars($row['feedback_text']) ?></td>
                        <td>
                            <a href="feedback_management.php?delete=<?= $row['feedback_id'] ?>" onclick="return confirm('Are you sure you want to delete this feedback?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
