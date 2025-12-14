<?php
session_start();

# Redirect if search key is not set or empty
if (!isset($_GET['key']) || empty(trim($_GET['key']))) {
  header("Location: index.php");
  exit;
}

$key = $_GET['key'];

include "db_conn.php";
include "php/func-book.php";
include "php/func-author.php";
include "php/func-category.php";

# Get search results
$books = search_books($conn, $key);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Search Results</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navigation -->
<nav class="navbar">
  <div class="navbar-container">
    <a href="#" class="navbar-brand">Online Book Library</a>
    
    <input type="checkbox" id="menu-toggle">
    <label for="menu-toggle" class="menu-icon">&#9776;</label>

    <ul class="nav-links">
      <li><a href="index.php">Library</a></li>
      <li><a href="#">Contact</a></li>
      <li><a href="#">About</a></li>
      <li>
        <?php if (isset($_SESSION['user_id'])) { ?>
          <a href="admin.php">Admin</a>
        <?php } else { ?>
          <a href="login.php">Login</a>
        <?php } ?>
      </li>
    </ul>
  </div>
</nav>

<!-- Search Form -->
<form action="search.php" method="get" class="search-form">
  <input type="text" name="key" placeholder="Search Book..." value="<?= htmlspecialchars($key) ?>">
  <button type="submit">
    <img src="img/search.png" width="20" alt="Search">
  </button>
</form>

<!-- Search Results -->
<div class="container">
  <p>Search results for <b><?= htmlspecialchars($key) ?></b>:</p>

  <?php if (empty($books)) { ?>
    <div class="alert alert-warning center">
      <img src="img/empty-search.png" width="100"><br>
      There is no book matching your search.
    </div>
  <?php } else { ?>
    <div class="card-grid">
      <?php foreach ($books as $book) { ?>
        <div class="card">
          <img src="uploads/cover/<?= htmlspecialchars($book['cover']) ?>" class="card-img">
          <div class="card-body">
            <h5><?= htmlspecialchars($book['title']) ?></h5>
            <p>
              <?= htmlspecialchars($book['description']) ?><br>
              <strong>By:</strong> <?= htmlspecialchars($book['author_name']) ?><br>
              <strong>Category:</strong> <?= htmlspecialchars($book['category_name']) ?>
            </p>
            <div class="card-actions">
              <a href="payment.php?book_id=<?= $book['id'] ?>" class="btn btn-primary">Purchase</a>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  <?php } ?>
</div>


<!-- Footer -->
<footer class="footer">
  <p>&copy; 2025 Online Book Library. All rights reserved.</p>
</footer>

</body>
</html>
