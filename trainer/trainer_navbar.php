<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2ecc71;
        --background-color: #f4f7f6;
        --text-color: #2c3e50;
        --sidebar-bg: #2c3e50;
        --hover-color: #34495e;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: var(--background-color);
        line-height: 1.6;
        color: var(--text-color);
    }

    header {
        position: fixed;
        top: 0;
        width: 100%;
        background-color: var(--sidebar-bg);
        color: #fff;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    header h1 {
        font-size: 1.5rem;
        display: flex;
        align-items: center;
    }

    header h1 i {
        margin-right: 10px;
        color: var(--secondary-color);
    }

    header .nav-buttons {
        display: flex;
        align-items: center;
    }

    .sidebar-toggle {
        color: #fff;
        margin-right: 15px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .sidebar-toggle:hover {
        transform: scale(1.1);
    }

    header .nav-buttons a {
        color: #fff;
        text-decoration: none;
        margin: 0 10px;
        padding: 8px 15px;
        background: var(--hover-color);
        border-radius: 4px;
        transition: background 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    header .nav-buttons a i {
        margin-right: 5px;
    }

    header .nav-buttons a:hover {
        background: var(--primary-color);
    }

    .sidebar {
        position: fixed;
        left: 0;
        top: 60px;
        width: 280px;
        height: calc(100% - 60px);
        background-color: var(--sidebar-bg);
        padding: 20px 0;
        overflow-y: auto;
        z-index: 500;
        transition: width 0.3s ease, transform 0.3s ease;
    }

    .sidebar.minimized {
        width: 60px;
        overflow: hidden;
    }

    .sidebar.minimized a span:not(.icon-only),
    .sidebar.minimized .dropdown-content,
    .sidebar.minimized .dropdown-trigger .dropdown-icon {
        display: none;
    }

    .sidebar.minimized a {
        justify-content: center;
        padding: 12px 0;
    }

    .sidebar a {
        color: #ecf0f1;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 12px 20px;
        border-left: 4px solid transparent;
        transition: all 0.3s ease;
    }

    .sidebar a i {
        margin-right: 15px;
        width: 25px;
        text-align: center;
        color: var(--secondary-color);
    }

    .sidebar a:hover, .sidebar a.active {
        background-color: var(--hover-color);
        border-left-color: var(--primary-color);
    }

    /* Dropdown Styles */
    .sidebar .dropdown {
        position: relative;
    }

    .sidebar .dropdown-content {
        display: none;
        background-color: #1c2833;
        padding-left: 40px;
    }

    .sidebar .dropdown:hover .dropdown-content {
        display: block;
    }

    .sidebar .dropdown-content a {
        padding: 10px 20px;
        border-left: none;
    }

    .dropdown-trigger {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dropdown-trigger .dropdown-icon {
        transition: transform 0.3s ease;
    }

    .dropdown:hover .dropdown-trigger .dropdown-icon {
        transform: rotate(180deg);
    }

    .content {
        margin-left: 280px;
        margin-top: 80px;
        padding: 20px;
        transition: margin-left 0.3s ease;
    }

    .content.full-width {
        margin-left: 60px;
    }

    .card {
        background: #fff;
        margin: 15px 0;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card h3 {
        color: var(--primary-color);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .card h3 i {
        margin-right: 10px;
    }

    .card ul {
        list-style-type: none;
        padding: 0;
    }

    .card ul li {
        margin: 10px 0;
    }

    .card ul li a {
        color: var(--text-color);
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: color 0.3s ease;
    }

    .card ul li a i {
        margin-right: 10px;
        color: var(--secondary-color);
    }

    .card ul li a:hover {
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            position: static;
            height: auto;
        }

        .content {
            margin-left: 0;
            margin-top: 120px;
        }
    }
</style>

<header>
        <div>
            <h1>
                <i class="fas fa-dumbbell"></i>
                FitInspire Hub
            </h1>
        </div>
        <div class="nav-buttons">
            <div class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </div>
            <a href="../index.php"><i class="fas fa-home"></i>Home</a>
        </div>
    </header>

    <div class="sidebar" id="sidebar">
        <a href="trainer_index.php" class="active">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a href="../index.php">
            <i class="fas fa-clipboard-list"></i>
            <span>Home</span>
        </a>
        <a href="trainer_profile.php">
            <i class="fas fa-user-circle"></i>
            <span>Profile</span>
        </a>
        
        <div class="dropdown">
            <a href="#" class="dropdown-trigger">
                <span>
                    <i class="fas fa-book-open"></i>
                    Bookings
                </span>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </a>
            <div class="dropdown-content">
                <a href="trainer_view_bookings.php">
                    <i class="fas fa-eye"></i>
                    View Bookings
                </a>
                <a href="trainer_view_feedback.php">
                    <i class="fas fa-comment-dots"></i>
                    View Feedback
                </a>
            </div>
        </div>

        <a href="trainer_guidance.php">
            <i class="fas fa-life-ring"></i>
            <span>Give advice</span>
        </a>
        <a href="../logout.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>