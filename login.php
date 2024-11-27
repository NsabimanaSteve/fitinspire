<?php
session_start();
require 'db_connect.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error_message = "Please fill in all fields.";
    } else {
        $role_id = null;

        // Determine role by email pattern
        if (stripos($email, 'FIHadmin') !== false) {
            $role_id = 3; // Admin
            $stmt = $conn->prepare("SELECT admin_id, email, password FROM admin WHERE email = ?");
        } elseif (stripos($email, 'FIHtrainer') !== false) {
            $role_id = 2; // Trainer
            $stmt = $conn->prepare("SELECT trainer_id, email, password FROM trainers WHERE email = ?");
        } else {
            $role_id = 1; // users
            $stmt = $conn->prepare("SELECT user_id, email, password FROM users WHERE email = ?");
        }

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                $error_message = "Invalid email or password.";
            } else {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {

                    session_start();

                    // Redirect based on role
                    if ($role_id == 1) {
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['membership_type'] = $user['membershiptype_id'];
                        header("Location: ./user/user_index.php");
                    } elseif ($role_id == 2) {
                        $_SESSION['trainer_id'] = $user['trainer_id'];
                        header("Location: ./trainer/trainer_index.php");
                    } elseif ($role_id == 3) {
                        $_SESSION['admin_id'] = $user['admin_id'];
                        header("Location: ./admin/index.php");
                    }
                    exit();
                } else {
                    $error_message = "Invalid email or password.";
                }
            }
            $stmt->close();
        } else {
            $error_message = "Error preparing login query.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | FitInspire Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Lato', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #333333 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 15px 30px;
            position: fixed;
            width: 100%;
            box-sizing: border-box;
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        .nav-links li a {
            text-decoration: none;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .nav-links li a:hover {
            background-color: #BC1E4A;
            transform: translateY(-2px);
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 100px 20px 40px;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
        }

        h1 {
            color: #BC1E4A;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus {
            border-color: #BC1E4A;
            outline: none;
            box-shadow: 0 0 0 3px rgba(188, 30, 74, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 1rem;
            background: #BC1E4A;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-btn:hover {
            background: #8A1435;
        }

        .error-message {
            background: #fee;
            color: #c00;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }

        .signup-link a {
            color: #BC1E4A;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: white;
            padding: 60px 20px 20px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        .footer-section {
            padding: 0 20px;
        }

        .footer-section h3 {
            color: #BC1E4A;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .footer-section p {
            margin-bottom: 15px;
            color: #ccc;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section ul li a:hover {
            color: #BC1E4A;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-links a {
            color: #ccc;
            font-size: 1.2rem;
            transition: color 0.3s;
        }

        .social-links a:hover {
            color: #BC1E4A;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #ccc;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 10px 15px;
            }

            .nav-links {
                display: none;
            }

            .form-container {
                margin: 0 10px;
            }

            .footer-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="../index.php" class="logo">FitInspire Hub</a>
        <ul class="nav-links">
            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="index.php#classes"><i class="fas fa-dumbbell"></i> Classes</a></li>
            <li><a href="index.php#trainers"><i class="fas fa-user"></i> Trainers</a></li>
            <li><a href="index.php#membership"><i class="fas fa-dollar-sign"></i> Membership</a></li>
            <li><a href="signup.php"><i class="fas fa-user-plus"></i> Sign Up</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="form-container">
            <h1><i class="fas fa-dumbbell"></i> FitInspire Login</h1>
            <?php if ($error_message): ?>
                <div class="error-message">
                    <?= htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            <div class="signup-link">
                Don't have an account? <a href="signup.php">Sign up here</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>FitInspire Hub is dedicated to helping you achieve your fitness goals with state-of-the-art facilities and expert guidance.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="../index.php#classes">Classes</a></li>
                    <li><a href="../index.php#trainers">Trainers</a></li>
                    <li><a href="../index.php#membership">Membership</a></li>
                    <li><a href="../index.php#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Info</h3>
                <ul>
                    <li><i class="fas fa-phone"></i> (233) 59-176-5158</li>
                    <li><i class="fas fa-envelope"></i> info@fitinspirehub.com</li>
                    <li><i class="fas fa-map-marker-alt"></i> Berekuso Fitness Street, Hill City</li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 FitInspire Hub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>