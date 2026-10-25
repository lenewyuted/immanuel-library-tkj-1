<?php
//gerbang pintu utama sebelum masuk ke sebuah halaman
//mekanisme untuk menghandle sesuatu kepada pengguna yg dmn sebelum dia akan mengakses sebuah halaman atau action.
//sebelum masuk ke halaman daftar buku, kt bs cek dlu status user dan tangkap aksi yg dilakukan

const LOGIN_URL = "/pages/auth/login.php";//simpan lokasi halaman login
const BOOK_INDEX_URL = "/pages/books/index.php";//menyimpan lokasi halaman daftar buku
const FORBIDDEN_URL = "/pages/errors/403.php"; //memberitahu tindakan yg dilarang

//Mengecek apakah sebuah session itu sudah dimulai atau belum, jika belum maka session akan dimulai
function sessionStart()
{
    if(session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

//Mengecek apaakah seorang pengguna sudah login atau belum
function isLoggedIn()
{
    sessionStart();
    $isLoggedIn = isset($_SESSION['user']);

    return $isLoggedIn;
}


//Mendapatkan data pengguna yang sudah login 
function getAuthUser()
{
    sessionStart();
    $user = $_SESSION['user'] ?? ''; //dapatin data pengguna dari mana

    return $user;
}


//Mengecek apakah role pengguna itu sesuai atau tidak
function hasRole(array $roles)
{
    $user = getAuthUser();
    $isUserHasAuthorizeRole = $user != null && in_array($user['role'], $roles);

    return $isUserHasAuthorizeRole;
}

//Menghandle pengguna yg belum login
function requireAuth()
{
    if(!isLoggedIn()) {
        $_SESSION['errors'] = [
            'login' => "Silakan login terlebih dahulu sebelum mengakses halaman ini"
        ];

        header('location: ' . LOGIN_URL);
        exit;
    }
}


//Menghandle pengguna yg tidak memiliki role yg sesuai atau tidak terautorisasi
function requireRole(array $roles) 
{
    requireAuth();
    
    if(!hasRole($roles)) {
        header('location: ' . FORBIDDEN_URL);
        exit;
    }
}


//Menghandle pengguna yg sudah login sebelumnya
function requireGuest()
{
    if(isLoggedIn()) {
        header('location: ' . BOOK_INDEX_URL);
        exit;
    }
}

?>
