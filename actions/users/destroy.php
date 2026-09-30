<?php
if (isset($_POST['destroy']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "ID pengguna: " . $_POST['id'];
    echo "<br>";
    echo "Data berhasil dihapus";
}

?>