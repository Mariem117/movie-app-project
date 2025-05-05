<?php
session_start();
?>
<header>
    <nav>
        <a href="../index.php">Home</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Log Out</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="dashboard.php">Admin Dashboard</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="login.php">Log In</a>
        <?php endif; ?>
    </nav>
</header>