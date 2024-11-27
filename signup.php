<?php
// Start session and database connection
session_start();
require 'db_connect.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = trim($_POST['role']);

    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        handleError("All fields are required.");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        handleError("Invalid email format.");
    } elseif (strlen($password) < 8) {
        handleError("Password must be at least 8 characters long.");
    } elseif ($password !== $confirm_password) {
        handleError("Passwords do not match.");
    } else {
        // Determine role and validate email patterns
        $role_id = null;

        if (stripos($email, 'FIHadmin') !== false) {
            handleError("Admin account creation is not allowed.");
        } elseif (stripos($email, 'FIHtrainer') !== false) {
            if ($role !== 'trainer') {
                handleError("Invalid role selection. Use the correct email pattern for users.");
            } else {
                $role_id = 2; // Trainer
            }
        } else {
            if ($role !== 'member') {
                handleError("Invalid role selection. Use the correct email pattern for trainers.");
            } else {
                $role_id = 1; // Member
            }
        }

        // Proceed if no validation errors
        if (empty($error_message)) {
            try {
                // Check if email exists
                $check_email = $conn->prepare("SELECT email FROM users WHERE email = ? UNION SELECT email FROM trainers WHERE email = ?");
                if (!$check_email) {
                    throw new Exception("Error preparing statement: " . $conn->error);
                }
                $check_email->bind_param("ss", $email, $email);
                $check_email->execute();
                $result = $check_email->get_result();
                if ($result->num_rows > 0) {
                    handleError("Email already exists.");
                } else {
                    // Insert user data
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $role_id == 1
                        ? $conn->prepare("INSERT INTO users (fname, lname, email, password) VALUES (?, ?, ?, ?)")
                        : $conn->prepare("INSERT INTO trainers (fname, lname, email, password) VALUES (?, ?, ?, ?)");
                    if (!$stmt) {
                        throw new Exception("Error preparing insert query: " . $conn->error);
                    }
                    $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
                    if (!$stmt->execute()) {
                        throw new Exception("Error executing query: " . $stmt->error);
                    }
                    $success_message = "Registration successful! Please log in.";
                    header("Location: login.php");
                    exit;
                }
            } catch (Exception $e) {
                handleError("An unexpected error occurred: " . $e->getMessage());
            } finally {
                $check_email->close();
                if (isset($stmt)) {
                    $stmt->close();
                }
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | FitInspire Hub</title>
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

        .navbar .logo {
            font-size: 1.5rem;
            font-weight: bold;
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
        .signup-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 6rem 2rem 2rem 2rem;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
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

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #BC1E4A;
            outline: none;
            box-shadow: 0 0 0 3px rgba(188, 30, 74, 0.1);
        }

        .signup-btn {
            width: 100%;
            padding: 1rem;
            background: #BC1E4A;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .signup-btn:hover {
            background: #8A1435;
        }

        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: center;
        }

        .error-message {
            background: #fee;
            color: #c00;
        }

        .success-message {
            background: #efe;
            color: #070;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }

        .login-link a {
            color: #BC1E4A;
            text-decoration: none;
            font-weight: 600;
        }

        /* Footer Styles */
        footer {
            background-color: #333;
            color: white;
            padding: 60px 20px 20px;
            margin-top: auto;
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
            .nav-links {
                display: none;
            }
            
            .signup-container {
                padding: 5rem 1rem 1rem 1rem;
            }

            .form-container {
                padding: 1.5rem;
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
            <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
        </ul>
    </nav>


    <div class="signup-container">
        <div class="form-container">
            <h1><i class="fas fa-dumbbell"></i> Join FitInspire Hub</h1>
            
            <?php if ($error_message): ?>
                <div class="message error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($success_message): ?>
                <div class="message success-message">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (!$success_message): ?>
                <form method="POST" action="signup.php">
                    <div class="form-group">
                        <label for="first_name"><i class="fas fa-user"></i> First Name</label>
                        <input type="text" name="first_name" id="first_name" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name"><i class="fas fa-user"></i> Last Name</label>
                        <input type="text" name="last_name" id="last_name" required>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" name="password" id="password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password"><i class="fas fa-lock"></i> Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>

                    <div class="form-group">
                        <label for="role"><i class="fas fa-users"></i> Role</label>
                        <select name="role" id="role" required>
                            <option value="" disabled selected>Sign up as:</option>
                            <option value="member">Member</option>
                            <option value="trainer">Trainer</option>
                            
                        </select>
                    </div>

                    <button type="submit" class="signup-btn">
                        <i class="fas fa-user-plus"></i> Sign Up
                    </button>
                </form>
            <?php endif; ?>

            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
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