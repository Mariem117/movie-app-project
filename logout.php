<?php
session_start();
session_destroy();
header('Location: front_pg.php');
exit();
?>