<header>
    <nav>
        <a href="front_pg.php">Home</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Log Out</a>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <a href="dashboard.php">Admin Dashboard</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="sign_up.php">Sign Up</a>
            <a href="login.php">Log In</a>
        <?php endif; ?>
    </nav>
</header>