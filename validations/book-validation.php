<?php
require_once __DIR__ . "/../repositories/book-repository.php";
require_once __DIR__ . "/../repositories/category-repository.php";
require_once __DIR__ . "/../repositories/author-repository.php";
function getBookRequest($request) //menerima sebuah parameter
{
    return [
        'id' => trim(htmlspecialchars($request['id'])),
        'title' => trim(htmlspecialchars($request['title'])), // membuat inputan pengguna diubah menjadi spesial character dari html 
        'isbn' => trim(htmlspecialchars($request['isbn'])),
        'year' => trim(htmlspecialchars($request['year'])),
        'stock' => trim(htmlspecialchars($request['stock'])),
        'description' => trim(htmlspecialchars($request['description'])),
        'category_id' => trim(htmlspecialchars($request['category_id'])),
        'author_ids' => $request['author_ids'] ?? [],
    ];
    
}

//mengeluarkan pesan pesan error

function validateBook($request, $ignoreBookId = 0) //sebuah validasi akan dilewati untuk khusus buku dengan id tertentu. Ketika dikirim $ignoreBookId dgn id 1, maka buku dengan id 1 bs dipakai untuk exclude beberapa aturan  
{
    $errors = [];

    //Validate title : required and max 100 character
    if ($request['title'] === '') {
        $errors['title'] = "Judul buku wajib diisi";
    }
    elseif (mb_strlen($request['title']) > 100) { //mb_strlen untuk cek panjang karakternya
        $errors['title'] = "Judul buku tidak boleh melebihi 100 karakter";
    }

    //Validate isbn: required, isTaken?
    if($request['isbn'] === '') {
        $errors['isbn'] = "ISBN wajib diisi";
    }
    elseif (isIsbnTaken($request['isbn'], $ignoreBookId)) {
        $errors['isbn'] = "ISBN sudah terdaftar sebelumnya";
    }

    //Validate year : required, isNumber
    if($request['year'] === '') {
        $errors['year'] = "Tahun terbit wajib diisi";
    }
    elseif(!is_int(intval($request['year']))) {
        $errors['year'] = "Tahun terbit wajib diisi dengan angka";
    }


    //Validate stock : required, isNumber
    if($request['stock'] === '') {
        $errors['stock'] = "Stok wajib diisi";
    }
    elseif(!is_int(intval($request['stock']))) {
        $errors['stock'] = "Stok wajib diisi dengan angka";
    }


    //Validate category: required, isExits
    if($request['category_id'] === '') {
        $errors['category_id'] = "Kategori wajib dipilih";
    }
    elseif (!isCategoryExists($request['category_id'])) {
        $errors['stock'] = "Kategori yang dipilih tidak valid";
    }



    //Validate description : required and max 200 character
    if ($request['description'] === '') {
        $errors['description'] = "Deskripsi buku wajib diisi";
    }
    elseif (mb_strlen($request['description']) > 100) { //mb_strlen untuk cek panjang karakternya
        $errors['description'] = "Deskripsi buku tidak boleh melebihi 200 karakter";
    }


    //Validate authors: required, isExists
    if(count($request['author_ids']) < 1) {
        $errors['author_ids'] = "Penulis buku wajib dipilih";
    }
    else {
        $authors = getAuthors();
        $validAuthorIds = array_column($authors, 'id');
        foreach($request['author_ids'] as $authorId) {
            if(!in_array($authorId, $validAuthorIds)) {
                $errors['author_ids'] = "Ada penulis buku yang dipilih tidak valid";
            }
        }
    }


    return $errors;
}
?>