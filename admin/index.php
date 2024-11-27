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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/admin_styles.css">
</head>
<body>
    <div class="admin-dashboard">
    
        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
                <ul class="app-breadcrumb breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php"><i class="fa fa-home fa-lg"></i></a></li>
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                </ul>
            </div>
            <div class="dashboard-cards">
                <!-- Add your dashboard cards here -->
            </div>
        </div>
    </div>

    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/plugins/pace.min.js"></script>
    <script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('minimized');
            });

            // DataTable initialization
            $('#sampleTable').DataTable();
        });
    </script>
</body>
</html>
