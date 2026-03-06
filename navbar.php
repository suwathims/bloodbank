<nav class="navbar">
    <div class="container">
        <a class="navbar-brand" href="/bloodbank/index.php">
            <img src="/bloodbank/assets/images/logo.svg" alt="Blood Bank Logo" class="logo-img">
        </a>
        <div class="nav-actions">
            <a class="btn secondary" href="/bloodbank/about.php">About</a>
            <a class="btn secondary" href="/bloodbank/register.php">Donate</a>
            <a class="btn secondary" href="/bloodbank/request.php">Request</a>
            <?php if (!empty(
                session_status() === PHP_SESSION_ACTIVE ? 
                    ($_SESSION['admin'] ?? false) : false
            )): ?>
                <a class="btn secondary" href="/bloodbank/admin/dashboard.php">Dashboard</a>
                <a class="btn" href="/bloodbank/admin/logout.php">Logout</a>
            <?php else: ?>
                <a class="btn" href="/bloodbank/login.php">Admin</a>
            <?php endif; ?>
        </div>
    </div>
</nav>