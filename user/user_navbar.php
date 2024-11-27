
<style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #8e44ad;
            --background-color: #f3f4f6;
            --text-color: #2c3e50;
            --hover-color: #3498db;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            line-height: 1.6;
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 30px 20px;
            color: white;
            position: relative;
            transition: width 0.3s ease;
            overflow: hidden;
        }

        .sidebar-toggle {
            position: absolute;
            top: 15px;
            right: 15px;
            cursor: pointer;
            color: white;
            font-size: 24px;
            z-index: 10;
        }

        .sidebar-nav ul {
            list-style: none;
            margin-top: 30px;
        }

        .sidebar-nav li {
            margin-bottom: 15px;
        }

        .sidebar-nav a {
            text-decoration: none;
            color: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            font-weight: 300;
            transition: all 0.3s ease;
        }

        .sidebar-nav a:hover {
            color: white;
            transform: translateX(10px);
        }

        .sidebar-nav .icon {
            margin-right: 15px; /* Adds consistent spacing between the icon and text */
            font-size: 20px;
            width: 30px;
            text-align: center;
            flex-shrink: 0; /* Ensures the icon maintains its width and doesn't shrink */
        }

        .sidebar-nav a {
            text-decoration: none;
            color: rgba(255,255,255,0.8);
            display: flex;
            align-items: center;
            font-weight: 300;
            gap: 10px; /* Adds spacing between flex children (icon and text) */
            transition: all 0.3s ease;
        }

        .membership-upgrade {
            background-color: rgba(255,255,255,0.2);
            border-radius: 10px;
            padding: 15px;
            margin-top: 30px;
            text-align: center;
        }

        .upgrade-toggle {
            background-color: white;
            color: var(--secondary-color);
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .upgrade-toggle:hover {
            background-color: #f1f1f1;
        }

        .dashboard-main {
            flex: 1;
            padding: 30px;
            background-color: var(--background-color);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .dashboard-content {
            background-color: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 15px;
            padding: 40px;
            width: 100%;
            max-width: 600px;
        }

        .dashboard-content h1 {
            color: var(--text-color);
            margin-bottom: 20px;
            text-align: center;
        }

        .dashboard-content p {
            color: var(--text-color);
            text-align: center;
        }

        /* Minimized Sidebar Styles */
        .sidebar.minimized {
            width: 80px;
        }

        .sidebar.minimized .sidebar-content > *:not(.sidebar-toggle) {
            display: none;
        }
    </style>
<div class="dashboard-container">
        <div class="sidebar" id="sidebar">
                <div class="sidebar-toggle" id="sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </div>
                
                <div class="sidebar-content">
                    <nav class="sidebar-nav">
                        <ul>
                            <li><a href="../index.php"><i class="fas fa-home"></i> Home</a></li>
                            <li><a href="user_index.php"><i class="fas fa-home"></i> Dashboard</a></li>
                            <li><a href="user_profile.php"><i class="fas fa-user"></i> Profile</a></li>
                            <li><a href="user_memberships.php"><i class="fas fa-dumbbell"></i> Memberships</a></li>
                            <li><a href="user_bookings.php"><i class="fas fa-calendar-alt"></i> Booking</a></li>
                            <li><a href="user_feedback.php"><i class="fas fa-comments"></i> Feedback</a></li>
                            <li><a href="user_guidance.php"><i class="fas fa-trophy"></i> Fitness Guidance</a></li>
                            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </nav>
    
                    
                    <div class="membership-upgrade">
                        <p>You are currently a <?php echo ($_SESSION['membership_type'] == 1) ? 'Basic' : ($_SESSION['membership_type'] == 2 ? 'Standard' : 'Premium'); ?> user</p>
                        <div class="upgrade-toggle" id="upgrade-btn">
                            Upgrade Membership
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        
    
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const sidebar = document.getElementById('sidebar');
                const sidebarToggle = document.getElementById('sidebar-toggle');
                const upgradeBtn = document.getElementById('upgrade-btn');
    
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('minimized');
                });
    
                upgradeBtn.addEventListener('click', () => {
                    alert('Upgrade to Premium Membership');
                });
            });
        </script>


        

