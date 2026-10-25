<?php
require_once __DIR__ . "/../config/database.php";

function getCategories()
{
  global $pdo;

  $query = "select c.*, count(b.id) as total_books
            from categories c
            left join books b on b.category_id = c.id
            group by c.id";
  $stmt = $pdo->prepare($query);
  $stmt -> execute();

  $categories = $stmt -> fetchAll();

  return $categories;
}

// function getCategories()
// {
//   global $pdo;

//   $query = "select * from categories";
//   $stmt = $pdo->prepare($query);
//   $stmt -> execute();

//   $categories = $stmt -> fetchAll();

//   return $categories;
// }

function getCategory()
{
  $category = [
    "id"          => 1,
    "name"        => "Fiksi",
    "description" => "Novel dan cerita rekaan",
  ];

  return $category;
}


function isCategoryExists($categoryId) 
{
  global $pdo;

  $query = "select count(id) from categories where id = :id"; //count(id) menghitung jumlah data yg ada di tabel book

  $stmt = $pdo -> prepare($query);
  $stmt -> execute([
    'id' => $categoryId   // sebelumnya 'category_id', harus sama dengan :id di query
  ]);

  $isCategoryExists = $stmt -> fetchColumn() > 0;

  return $isCategoryExists;
}