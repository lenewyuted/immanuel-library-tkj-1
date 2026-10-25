<?php
require_once __DIR__ . "/../config/database.php";

function getBooks($search, $categoryId, $currentPage, $perPage)
{
  global $pdo;

  $query ="select b.id, b.title, b.stock, c.name as category from books b
           left join categories c on b.category_id = c.id
           where b.title like :search
           and(:category_filter = '' or b.category_id = :category_id)
           limit :limit offset :offset"; //isi perintah mysql untuk ambil data
  //nama table yg mau kt join on foreign key yg kt simpan di tabel books = primary key dari table categories
  //prepare statement : fungsi/cara agar kita untuk menghindari yang namanya sql injection (user yg pakai aplikasi kita bisa menginputkan perintah mysql dari form yg kt buat) 
  $stmt = $pdo -> prepare($query); //siapkan perintah mysql
  $stmt -> execute([
    'search' => "%$search%",
    'category_filter' => $categoryId,
    'category_id' => $categoryId,
    'limit' => $perPage,
    'offset' => ($currentPage - 1) * $perPage,
  ]); //eksekusi perintahnya
  
  $books = $stmt -> fetchAll(); //$books akan berisi data data buku yang berasal dari database 
  //print_r($books); //akan mengembalikan array asosiatif yang isinya dari database
  //exit;

  $query ="select ba.book_id, a.name from book_author ba
           left join authors a on ba.author_id = a.id";
  $stmt = $pdo -> prepare($query);
  $stmt -> execute();

  $bookAuthors = $stmt -> fetchAll();
  // print_r($bookAuthors);
  // exit;

  $booksByAuthors = [];
  foreach ($bookAuthors as $bookAuthor) { //$bookAuthor sebagai key
    $booksByAuthors[$bookAuthor['book_id']][] = $bookAuthor['name'];
  }
  // print_r($booksByAuthors);
  // exit;

  foreach ($books as $index => $book) {
    $books[$index]['authors'] = $booksByAuthors[$book['id']] ?? []; //tambahkan key berupa authors ke variabel books ini
  }
  // print_r($books);
  // exit;
  return $books;
}

function getBook($id)
{
  global $pdo;

  $query = "select id, title, isbn, year, stock, category_id, description from books where id = :id";
  //Ga pakai join seperti video read karena kt butuh category_id, itu sebenarnya kt udh ambil dari getCategories yg ada di edit.php. Jdi tinggal cocokin aja apakah id nya sama dgn yg diambil di database dgn yg kt ambil dari table buku. Kalau sama baru dia akan terselect

  $stmt = $pdo -> prepare($query);
  $stmt -> execute([
    'id' => $id,
  ]);

  $book = $stmt -> fetch();
  //fetch untuk menampilkan 1 data aja
  

  if(!$book) {
    header('location: ../books/index.php');
    exit;
  }

  $query = "select a.id from book_author ba
            left join authors a on ba.author_id = a.id
            where ba.book_id = :book_id"; 
  //tambahkan key ke dlm book, yg akan mengembalikan id dan juga name dari author"nya
  //ambilkan saya id dan nama dari author dari book_author kemudian joinkan dengan table authors dan direferensikan, dia cuman akan ambil berdasarkan book_id yaitu berdasarkan inputan kita 

  $stmt = $pdo -> prepare($query);
  $stmt -> execute([
    'book_id' => $book['id'],
  ]); //karena ada inputan jadi kt buka kurung siku

  $authorIds = $stmt->fetchAll();

  $book['author_ids'] = array_column($authorIds, 'id');
  
  
  

  return $book;
}


function isIsbnTaken($isbn, $ignoreBookId) 
{
  global $pdo;

  $bookId = intval($ignoreBookId);

  $query = "select count(id) from books where isbn = :isbn && id != :book_id"; //count(id) menghitung jumlah data yg ada di tabel book

  $stmt = $pdo -> prepare($query);
  $stmt -> execute([
    'isbn' => $isbn,
    'book_id' => $bookId,
  ]);

  $isIsbnTaken = $stmt -> fetchColumn() > 0;

  return $isIsbnTaken;
}


function countOfBooks($search, $categoryId)
{
  global $pdo;

  $query = "select count(id) from books 
            where title like :title and
            (:category_filter = '' or category_id = :category_id)";

  $stmt = $pdo -> prepare($query);
  $stmt->execute([
    'title' => "%$search%",
    'category_filter' => $categoryId,
    'category_id' => $categoryId,
  ]);

  $totalBooks = $stmt -> fetchColumn();

  return $totalBooks;
}

