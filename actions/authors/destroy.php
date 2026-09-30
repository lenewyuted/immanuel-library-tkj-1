<?php
if (isset($_POST['destroy']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "ID penulis: " . $_POST['id'];
    echo "<br>";
    echo "Data berhasil dihapus";
}

?>