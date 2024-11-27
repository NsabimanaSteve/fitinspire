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
    $id = intval($_GET['delete']);
    $deleteQuery = "DELETE FROM membershiptype WHERE membershiptype_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: membership_management.php");
    exit();
}

// Handle update action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['membershiptype_id']);
    $name = trim($_POST['name']);
    $price = floatval($_POST['membershiptype_price']);

    $updateQuery = "UPDATE membershiptype SET name = ?, membershiptype_price = ? WHERE membershiptype_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sdi", $name, $price, $id);
    $stmt->execute();
    header("Location: membership_management.php");
    exit();
}

// Fetch all membership types
$query = "SELECT membershiptype_id, name, membershiptype_price FROM membershiptype";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Type Management</title>
    <link rel="stylesheet" href="css/admin_styles.css">
</head>
<body>
    <div class="main-content">
        <h2>Membership Type Management</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['membershiptype_id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td>$<?= number_format($row['membershiptype_price'], 2) ?></td>
                            <td>
                                <form method="POST" action="membership_management.php" style="display:inline-block;">
                                    <input type="hidden" name="membershiptype_id" value="<?= htmlspecialchars($row['membershiptype_id']) ?>">
                                    <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
                                    <input type="number" step="0.01" name="membershiptype_price" value="<?= htmlspecialchars($row['membershiptype_price']) ?>" required>
                                    <button type="submit" name="update">Update</button>
                                </form>
                                <a href="membership_management.php?delete=<?= htmlspecialchars($row['membershiptype_id']) ?>" onclick="return confirm('Are you sure you want to delete this membership type?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No membership types found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
