<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireRole(['admin']);
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../validations/book-validation.php";

if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $pdo->beginTransaction();

        //ketika panggil function ini, function ini akan menerima inputan dari pengguna
        $request = getBookRequest($_POST);//berisi inputan dari pengguna
        $errors = validateBook($request, $request['id']);//untuk menampung pesan terakit dgn inputan yg mau kt validasi
        if(count($errors) > 0) {
            $_SESSION['errors'] = $errors; //simpan pesan error ke session (tempat penyimpanan sementara yg berada di server)
            $_SESSION['old'] = $request; //biasa ketika ada validasi error, kebiasaan form akan mereset semua inputannya. Jdi dgn ini bs mempertahankan semua inputannya

            header('location: ../../pages/books/edit.php?id=' . urlencode($request['id']));
            exit;
        }


        //Update main data
        $query = "update books set title = :title, isbn = :isbn, year = :year, stock = :stock, category_id = :category_id, description = :description where id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'title' => $request['title'],
            'isbn' => $request['isbn'],
            'year' => $request['year'],
            'stock' => $request['stock'],
            'category_id' => $request['category_id'],
            'description' => $request['description'],
            'id' => $request['id'],
        ]);

        //Delete book author
        $query = "delete from book_author where book_id = :book_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'book_id' => $request['id']
        ]);

        //Insert new book author
        $query = "insert into book_author (book_id, author_id) values (:book_id, :author_id)";
        $stmt = $pdo->prepare($query);
        $authorIds = isset($request['author_ids']) ? $request['author_ids'] : [];
        foreach($authorIds as $authorId) { //karena hasilnya bakal banyak jadi pakai foreach
            $stmt->execute([
                'book_id' => $request['id'],
                'author_id' => htmlspecialchars($authorId)
            ]);
        };


        $pdo->commit();

        header('location: ../../pages/books/index.php');
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();
        throw $e;
    }


   

  
}

?>
