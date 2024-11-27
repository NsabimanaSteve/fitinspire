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
    $stmt = $conn->prepare("DELETE FROM guidance WHERE guidance_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: guidance_management.php?message=Guidance+deleted+successfully");
    } else {
        echo "Error deleting guidance: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all guidance entries with user and trainer details
$query = "
    SELECT g.guidance_id, g.nutrition_advice, g.workouts, g.techniques,
           u.fname AS user_fname, u.lname AS user_lname,
           t.fname AS trainer_fname, t.lname AS trainer_lname
    FROM guidance g
    JOIN users u ON g.user_id = u.user_id
    JOIN trainers t ON g.trainer_id = t.trainer_id
    ORDER BY g.guidance_id DESC";
$result = $conn->query($query);

if (!$result) {
    die("Error fetching guidance: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guidance Management</title>
    <link rel="stylesheet" href="css/admin_styles.css">
</head>
<body>
    <div class="main-content">
        <h2>Guidance Management</h2>

        <!-- Display success message -->
        <?php if (isset($_GET['message'])): ?>
            <p class="success-message"><?= htmlspecialchars($_GET['message']) ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Trainer</th>
                    <th>Nutrition Advice</th>
                    <th>Workouts</th>
                    <th>Techniques</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['guidance_id']) ?></td>
                        <td><?= htmlspecialchars($row['user_fname'] . ' ' . $row['user_lname']) ?></td>
                        <td><?= htmlspecialchars($row['trainer_fname'] . ' ' . $row['trainer_lname']) ?></td>
                        <td><?= htmlspecialchars($row['nutrition_advice']) ?></td>
                        <td><?= htmlspecialchars($row['workouts']) ?></td>
                        <td><?= htmlspecialchars($row['techniques']) ?></td>
                        <td>
                            <a href="guidance_management.php?delete=<?= $row['guidance_id'] ?>" 
                               onclick="return confirm('Are you sure you want to delete this guidance?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
