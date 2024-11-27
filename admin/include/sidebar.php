<aside class="app-sidebar" id="app-sidebar">
    <!-- Mobile Toggle Button -->
    <button class="mobile-sidebar-toggle" id="mobile-sidebar-toggle">
        <i class="fa fa-bars"></i>
    </button>

    <ul class="app-menu">
        <li>
            <a class="app-menu__item" href="index.php">
                <i class="app-menu__icon fa fa-dashboard"></i>
                <span class="app-menu__label">Dashboard</span>
            </a>
        </li>
        
        <li>
            <a class="app-menu__item" href="manage-membership.php">
                <i class="app-menu__icon fa fa-id-card"></i>
                <span class="app-menu__label">Membership Type Management</span>
            </a>
        </li>
        
        <li>
            <a class="app-menu__item" href="manage-trainer.php">
                <i class="app-menu__icon fa fa-dumbbell"></i>
                <span class="app-menu__label">Trainer Management</span>
            </a>
        </li>
        
        <li>
            <a class="app-menu__item" href="manage-user.php">
                <i class="app-menu__icon fa fa-users"></i>
                <span class="app-menu__label">User Management</span>
            </a>
        </li>
        
        <!-- Dropdown for Booking Management -->
        <li class="treeview">
            <a class="app-menu__item dropdown-trigger" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-book"></i>
                <span class="app-menu__label">Booking Management</span>
                <i class="treeview-indicator fa fa-angle-right dropdown-icon"></i>
            </a>
            <ul class="treeview-menu dropdown-content">
                <li>
                    <a class="treeview-item" href="booking-history.php">
                        <i class="icon fa fa-circle-o"></i> All Bookings
                    </a>
                </li>
            </ul>
        </li>
        
        <li>
            <a class="app-menu__item" href="manage-feedback.php">
                <i class="app-menu__icon fa fa-comment"></i>
                <span class="app-menu__label">Feedback Management</span>
            </a>
        </li>
        
        <li>
            <a class="app-menu__item" href="manage-guidance.php">
                <i class="app-menu__icon fa fa-graduation-cap"></i>
                <span class="app-menu__label">Guidance Management</span>
            </a>
        </li>
        
        <!-- Dropdown for Reports -->
        <li class="treeview">
            <a class="app-menu__item dropdown-trigger" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-file-text"></i>
                <span class="app-menu__label">Report</span>
                <i class="treeview-indicator fa fa-angle-right dropdown-icon"></i>
            </a>
            <ul class="treeview-menu dropdown-content">
                <li>
                    <a class="treeview-item" href="report-booking.php">
                        <i class="icon fa fa-circle-o"></i>Booking Report
                    </a>
                </li>
                <li>
                    <a class="treeview-item" href="report-membership.php">
                        <i class="icon fa fa-circle-o"></i>Membership Report
                    </a>
                </li>
                <li>
                    <a class="treeview-item" href="report-feedback.php">
                        <i class="icon fa fa-circle-o"></i>Feedback Report
                    </a>
                </li>
            </ul>
        </li>
        
        <li>
            <a class="app-menu__item" href="logout.php">
                <i class="app-menu__icon fa fa-sign-out"></i>
                <span class="app-menu__label">Logout</span>
            </a>
        </li>
    </ul>
</aside>

<style>
/* Responsive Styles */
@media (max-width: 768px) {
    .app-sidebar {
        width: 250px;
        position: fixed;
        left: -250px;
        top: 0;
        height: 100%;
        z-index: 1000;
        transition: left 0.3s ease;
        background-color: #2c3e50;
    }

    .app-sidebar.open {
        left: 0;
    }

    .mobile-sidebar-toggle {
        display: block;
        position: fixed;
        top: 10px;
        left: 10px;
        z-index: 1001;
        background: #3c8dbc;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
    }

    .page-content {
        margin-left: 0 !important;
    }

    .treeview-menu {
        position: static;
        display: none;
        background-color: rgba(0,0,0,0.1);
    }

    .treeview.active .treeview-menu {
        display: block;
    }
}

.mobile-sidebar-toggle {
    display: none;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobile-sidebar-toggle');
    const sidebar = document.getElementById('app-sidebar');
    const dropdownTriggers = document.querySelectorAll('.dropdown-trigger');

    // Mobile sidebar toggle
    mobileToggle.addEventListener('click', function() {
        sidebar.classList.toggle('open');
    });

    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768) {
            if (!event.target.closest('.app-sidebar, .mobile-sidebar-toggle')) {
                sidebar.classList.remove('open');
            }
        }
    });

    // Dropdown functionality
    dropdownTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const parentTreeview = this.closest('.treeview');
            parentTreeview.classList.toggle('active');
        });
    });
});
</script>