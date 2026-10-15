<?php
require_once __DIR__ . "/../../config/database.php";
if (isset($_POST['store']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $pdo -> beginTransaction();
        //database transaction : suatu mekanisme yg membantu kita untuk memproses, mengirim, menghapus, mengubah data , dll yg menipulasi data dari database
        //jika terjadi 1 error pada rangkaian prosesnya, maka dia akan mengembalikan prosesnya itu kembali ke semula sebelum data itu diubah , jadi bs membuat data tetap konsisten agar tidak ada data yang nyangkut ketika terjadi error  


        $title = htmlspecialchars($_POST['title']); // membuat inputan pengguna diubah menjadi spesial character dari html 
        $isbn = htmlspecialchars($_POST['isbn']);
        $year = htmlspecialchars($_POST['year']);
        $stock = htmlspecialchars($_POST['stock']);
        $description = htmlspecialchars($_POST['description']);
        $categoryId = htmlspecialchars($_POST['category_id']);



        $query = "insert into books (title, isbn, year, stock, description, category_id) 
                 values (:title, :isbn, :year, :stock, :description, :category_id)"; 

        $stmt = $pdo->prepare($query);
        $stmt -> execute([
            //menambah array lalu kt lanjutkan dgn eksekusi nilai nilainya, lalu kt akan ganti placeholder nya dgn nilai aslinya (inputan yg berasal dari form pengguna(dari $POST))
            'title' => $title,
            'isbn' => $isbn,
            'year' => $year,
            'stock' => $stock,
            'description' => $description,
            'category_id' => $categoryId,
        ]);
        //kt pakai prepare statement yg dmn query itu disusun terlebih dahulu tanpa ada nilainya, yg dmn nilainya akan kt ganti dgn placeholder(mencegah sql injection, agar pengguna ga bs langsung masukin perintah mysql yg berasal form)
        //perintah sql tidak akan dieksekusi, tapi disusun dulu, sehingga nanti baru akan dieksekusi dgn perintah execute


        $bookId = $pdo->lastInsertId(); //mengembalikan data id dari record yg terakhir kt buat 

        $query = "insert into book_author(book_id, author_id) values (:book_id, :author_id)"; 
        //perintah untuk masukin data ke dalam tabel book_author
        $stmt = $pdo->prepare($query);


        $authorIds = $_POST['author_ids'];
        foreach($authorIds as $authorId) {
            $stmt -> execute([
                'book_id' => $bookId,
                'author_id' => htmlspecialchars($authorId),
            ]);
        }


        $pdo->commit(); //ketika sudah slesai, kt akan memastikan bahwa data ini udah bnr jdi ini akan dieksekusi dan dikirim ke databasenya

        header('location: ../../pages/books/index.php');
        exit;

        
    } catch (PDOException $e) {
        $pdo->rollBack(); //ketika terjadi error, maka dia akan membatalkan ekseskusi yg udh terjadi sebelumnya, kalau ga error maka akan dieksekusi kembali
        throw $e; //kalau error maka akan langsung lempar errornya keluar agar sistemnya langsung dierrorkan aja
    }

    //print_r($pdo); //memanggil pdo
}

?>