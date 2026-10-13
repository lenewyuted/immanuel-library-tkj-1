<?php
$host = "localhost"; //daftarkan ip server laptop kita
$dbname = "immanuel_library";
$username = "root";
$password = "";
//Charset itu aturan encode karakter. Agar ketika kita simpan, maka dia akan tetap simpan sesuai dengan nilainya. Kalau kita ga pakai utf8mb4, maka dia akan simpan dalam karakter random yang ga terbaca oleh database.
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  //munculkan error nya itu karna apa
    //kita mau kalau ada error, dia munculnya itu semua error yang ada
    //fungsinya untuk kita atur kalau CRUD nya error, dia maunya ga muncul/muncul/muncul dikit
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //data yg kt kembalikan berupa array asosiatif
    //untuk bantu kita secara default ketika kita menarik data, maka datanya akan dikembalikan dalam bentuk array asosiatif
    PDO::ATTR_EMULATE_PREPARES => false, //kita akan mengatur dia jadi strict ketika kita mengirimkan sebuah data maka dia akan kirim apa adanya. kalau false kita kirim angka maka akan tetap angka, tapi kalau tur kita kirim angka dia akan jadi string
    //secara default, ketika kita kirim 10 maka yg masuk ke php itu "10" (string), tapi kita mau hasilnya itu 10 (int)
    //kalau yg dikirim itu "testing" maka yg masuk harus "testing"
];

//Sebuah cara untuk mengeksekusi sebuah blok kode yang di mana jika terjadi error, maka errornya akan langsung dilempar ke bagian blok catch untuk menghandle error itu
try {
    $pdo = new PDO($dsn, $username, $password, $options); //ini namanya constructor. variabel ini akan kita pakai untuk jika kita ingin berinteraksi dengan database kita
} catch (PDOException $error) { //Setiap error akan masuk ke parameter error ini
    die("Koneksi ke database gagal: " . $error -> getMessage()); //Jika terjadi error, maka aplikasinya akan langsung dihentikan menggunakan die dan memunculkan pesan dan alasan kenapa gagal koneksi
}
?>
