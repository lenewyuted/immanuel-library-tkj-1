<?php
require_once __DIR__ . "/../config/database.php";

function getBooks()
{
  global $pdo;

  $query ="select b.id, b.title, b.stock, c.name as category from books b
           left join categories c on b.category_id = c.id"; //isi perintah mysql untuk ambil data
  //nama table yg mau kt join on foreign key yg kt simpan di tabel books = primary key dari table categories
  //prepare statement : fungsi/cara agar kita untuk menghindari yang namanya sql injection (user yg pakai aplikasi kita bisa menginputkan perintah mysql dari form yg kt buat) 
  $stmt = $pdo -> prepare($query); //siapkan perintah mysql
  $stmt -> execute(); //eksekusi perintahnya
  
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

function getBook()
{
  $book = [
    "id" => 5,
    "title" => "Antologi Rasa Nusantara",
    "isbn" => "978-602-1234-56-7",
    "year" => 2021,
    "stock" => 4,
    "category_id" => 1,
    "category" => "Fiksi",
    "description" => "Kumpulan puisi dan cerita pendek dari berbagai penulis Nusantara.",
    "author_ids" => [4, 5],
    "authors" => ["Pramoedya Ananta Toer", "Sapardi Djoko Damono"],
  ];
  return $book;
}