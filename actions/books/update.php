<?php
require_once __DIR__ . "/../../config/database.php";

if (isset($_POST['update']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $pdo->beginTransaction();


        $id = htmlspecialchars($_POST['id']);
        $title = htmlspecialchars($_POST['title']);
        $isbn = htmlspecialchars($_POST['isbn']);
        $year = htmlspecialchars($_POST['year']);
        $stock = htmlspecialchars($_POST['stock']);
        $categoryId = htmlspecialchars($_POST['category_id']);
        $description = htmlspecialchars($_POST['description']);

        //Update main data
        $query = "update books set title = :title, isbn = :isbn, year = :year, stock = :stock, category_id = :category_id, description = :description where id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'title' => $title,
            'isbn' => $isbn,
            'year' => $year,
            'stock' => $stock,
            'category_id' => $categoryId,
            'description' => $description,
            'id' => $id,
        ]);

        //Delete book author
        $query = "delete from book_author where book_id = :book_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'book_id' => $id
        ]);

        //Insert new book author
        $query = "insert into book_author (book_id, author_id) values (:book_id, :author_id)";
        $stmt = $pdo->prepare($query);
        $authorIds = isset($_POST['author_ids']) ? $_POST['author_ids'] : [];
        foreach($authorIds as $authorId) { //karena hasilnya bakal banyak jadi pakai foreach
            $stmt->execute([
                'book_id' => $id,
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
