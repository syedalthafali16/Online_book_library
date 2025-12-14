<?php  
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    include "db_conn.php";
    include "php/func-book.php";
    include "php/func-author.php";
    include "php/func-category.php";

    $books = get_all_books($conn);
    $authors = get_all_authors($conn);
    $categories = get_all_categories($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Online Book Library</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="admin.php" class="navbar-brand">Admin</a>
            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle" class="menu-icon">&#9776;</label>
            <ul class="nav-links">
                <li><a href="admin.php">Library</a></li>
                <li><a href="add-book.php">Add Book</a></li>
                <li><a href="add-category.php">Add Category</a></li>
                <li><a href="add-author.php">Add Author</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Alerts -->
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>

    <!-- Books Section -->
    <h4>All Books</h4>
    <?php if (!$books): ?>
        <div class="alert alert-warning center">
            <img src="img/empty.png" width="100"><br>There is no book in the database
        </div>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($books as $book): ?>
                <div class="card">
                    <img src="uploads/cover/<?= $book['cover'] ?>" class="card-img" alt="Cover">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($book['title']) ?></h5>
                        <p>
                          <strong>By:</strong> <?= htmlspecialchars($book['author_name']) ?><br>
                          <strong>Category:</strong> <?= htmlspecialchars($book['category_name']) ?>
                        </p>
                        <p><?= htmlspecialchars($book['description']) ?></p>
                        <div class="card-actions">
                            <a href="edit-book.php?id=<?= $book['id'] ?>" class="btn btn-warning">Edit</a>
                            <a href="php/delete-book.php?id=<?= $book['id'] ?>" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Categories Section -->
    <h4>All Categories</h4>
    <?php if (!$categories): ?>
        <div class="alert alert-warning center">
            <img src="img/empty.png" width="100"><br>There is no category in the database
        </div>
    <?php else: ?>
        <div class="simple-list">
            <?php foreach ($categories as $category): ?>
                <div class="list-item">
                    <span><?= htmlspecialchars($category['name']) ?></span>
                    <div>
                        <a href="edit-category.php?id=<?= $category['id'] ?>" class="btn btn-warning">Edit</a>
                        <a href="php/delete-category.php?id=<?= $category['id'] ?>" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Authors Section -->
    <h4>All Authors</h4>
    <?php if (!$authors): ?>
        <div class="alert alert-warning center">
            <img src="img/empty.png" width="100"><br>There is no author in the database
        </div>
    <?php else: ?>
        <div class="simple-list">
            <?php foreach ($authors as $author): ?>
                <div class="list-item">
                    <span><?= htmlspecialchars($author['name']) ?></span>
                    <div>
                        <a href="edit-author.php?id=<?= $author['id'] ?>" class="btn btn-warning">Edit</a>
                        <a href="php/delete-author.php?id=<?= $author['id'] ?>" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

<?php 
} else {
    header("Location: login.php");
    exit;
}
?>
