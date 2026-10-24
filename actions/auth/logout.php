<?php
session_start();

$_SESSION = [];
session_destroy();

header('location: ../../pages/auth/login.php');
exit;
?>