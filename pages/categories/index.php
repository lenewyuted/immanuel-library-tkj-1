<?php 
require_once __DIR__ . "/../../repositories/category-repository.php";

$categories = getCategories();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Kategori - Perpustakaan Digital</title>
  <link rel="stylesheet" href="../../styles/categories/index.css">
</head>
<body>
  
  <div class="app-shell">
    <?php require_once __DIR__ . "/../../components/admin/sidebar.php"?>

    <main class="app-main">
      <?php
      $pageTitle = "Manajemen Kategori";
      $pageSubtitle = "Kelola kategori untuk mengelompokkan buku";
      require_once __DIR__ . "/../../components/admin/topbar.php"
      ?>

      <div class="app-content">
        <div class="toolbar">
          <form method="" action="" class="toolbar-filters">
            <div class="search-box">
              <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
              <input type="text" name="search" class="search-input" placeholder="Cari nama kategori...">
            </div>
            <button type="submit" class="btn btn-outline btn-sm">Cari</button>
          </form>
          <a href="create.php" class="btn btn-primary">+ Tambah Kategori</a>
        </div>

        <div class="data-card">
          <table class="data-table">
            <thead>
              <tr>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Jumlah Buku</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $index => $category): ?>
              <tr>
                <td>
                  <div class="cell-primary">
                    <span class="cell-thumb"><svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/></svg></span>
                    <?= $category['name'] ?>
                  </div>
                </td>
                <td><?= $category['description'] ?></td>
                <td><span class="badge badge-muted"><?= $category['total_books'] ?> buku</span></td>
                <td>
                  <div class="cell-actions">
                    <a href="edit.php?id=<?= $category['id'] ?>" class="btn btn-outline btn-sm">Edit</a>
                    <form method="post" action="../../actions/categories/destroy.php" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                        <input type="hidden" name="id" value="<?= $category['id'] ?>">
                        <button type="submit" name="destroy" class="btn btn-danger btn-sm">Hapus</button>
                      </form>
                  </div>
                </td>
              </tr>
              <?php endforeach?>
              <?php if(count($categories) < 1):?>
                <tr>
                  <td style="text-align: center;" colspan="5">Tidak ada data kategori yang ditemukan</td>
                </tr>
              <?php endif?>
            </tbody>
          </table>
        </div>

        <div class="pagination">
          <span class="pagination-btn is-disabled">&lt;</span>
          <span class="pagination-btn is-disabled">&gt;</span>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
