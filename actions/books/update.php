<?php
if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "Data yang diterima:";
    print_r($_POST);
} else {
    echo "Akses tidak valid. Halaman ini hanya dapat diakses lewat pengiriman form (POST).";
}
?>