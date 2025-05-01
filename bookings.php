<?php
if (isset($_SESSION['success_message'])) {
    echo "<div class='success'>{$_SESSION['success_message']}</div>";
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo "<div class='error'>{$_SESSION['error_message']}</div>";
    unset($_SESSION['error_message']);
}