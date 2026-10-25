<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireRole(['admin']);

if (isset($_POST['store']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    $name = $_POST['name'];
    $bio = $_POST['bio'];

    print_r($_POST);
}

?>
