<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireAuth();

$_SESSION = [];
session_destroy();

header('location: ' . LOGIN_URL);
exit;
?>