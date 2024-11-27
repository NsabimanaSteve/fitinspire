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
// Fetch all trainers
$query = "SELECT * FROM trainers";
$result = mysqli_query($conn, $query);

// Handle delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM trainers WHERE id = $id";
    mysqli_query($conn, $deleteQuery);
    header("Location: trainer_management.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Management</title>
    <link rel="stylesheet" href="css/admin_styles.css">
</head>
<body>
    <div class="main-content">
        <h2>Trainer Management</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['trainer_id'] ?></td>
                        <td><?= $row['fname'] ?></td>
                        <td><?= $row['email'] ?></xtd>
                        <td>
                            <a href="trainer_management.php?delete=<?= $row['trainer_id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
