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
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM bookings WHERE id = $id";
    mysqli_query($conn, $deleteQuery);
    header("Location: booking_management.php");
}

// Handle update action
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE bookings SET status = '$status' WHERE id = $id";
    mysqli_query($conn, $updateQuery);
    header("Location: booking_management.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management</title>
    <link rel="stylesheet" href="css/admin_styles.css">
</head>
<body>
    <div class="main-content">
        <h2>Booking Management</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Trainer</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['user_id'] ?></td>
                        <td><?= $row['trainer_id'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td>
                            <form method="POST" action="booking_management.php">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <select name="status">
                                    <option value="pending" <?= ($row['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                    <option value="confirmed" <?= ($row['status'] == 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
                                    <option value="completed" <?= ($row['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                                </select>
                                <button type="submit" name="update">Update</button>
                            </form>
                            <a href="booking_management.php?delete=<?= $row['id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
