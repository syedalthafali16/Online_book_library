<?php  
session_start();

if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

  if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
  }

  $id = $_GET['id'];

  include "db_conn.php";
  include "php/func-book.php";
  $book = get_book($conn, $id);

  if ($book == 0) {
    header("Location: admin.php");
    exit;
  }

  include "php/func-category.php";
  $categories = get_all_categories($conn);

  include "php/func-author.php";
  $authors = get_all_authors($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Book</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      padding: 20px;
    }

    /* Navbar */
    .navbar {
      background: #fff;
      border-bottom: 1px solid #ddd;
      border-radius: 10px;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar-brand {
      font-size: 1.5rem;
      font-weight: bold;
      text-decoration: none;
      color: #000;
    }

    .nav-menu {
      list-style: none;
      display: flex;
      gap: 20px;
      margin: 0;
      padding: 0;
    }

    .nav-menu li a {
      text-decoration: none;
      color: #000;
      padding: 8px 12px;
      border-radius: 6px;
      transition: background 0.3s, color 0.3s;
    }

    .nav-menu li a:hover {
      background: #4F46E5;
      color: white;
    }

    /* Responsive menu toggle */
    @media (max-width: 768px) {
      .nav-menu {
        flex-direction: column;
        background: white;
        border-top: 1px solid #ddd;
        display: none;
        position: absolute;
        width: 100%;
        left: 0;
        top: 60px;
        border-radius: 0 0 10px 10px;
      }
      .nav-menu.show {
        display: flex;
      }
    }

    /* Hamburger */
    .menu-icon {
      display: none;
      font-size: 1.8rem;
      cursor: pointer;
      user-select: none;
    }
    @media (max-width: 768px) {
      .menu-icon {
        display: block;
      }
    }

    /* Form */
    form {
      background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      padding: 30px;
      border-radius: 10px;
      max-width: 700px;
      margin: 40px auto;
    }

    h1 {
      text-align: center;
      font-weight: 700;
      margin-bottom: 30px;
      font-size: 1.8rem;
    }

    label {
      display: block;
      font-weight: 600;
      margin-bottom: 6px;
      color: #555;
    }

    input[type="text"],
    select,
    input[type="file"] {
      width: 100%;
      padding: 10px 14px;
      font-size: 1rem;
      border-radius: 10px;
      border: 1px solid #ccc;
      margin-bottom: 20px;
      box-sizing: border-box;
      outline-offset: 2px;
      transition: border-color 0.3s ease;
    }

    input[type="text"]:focus,
    select:focus,
    input[type="file"]:focus {
      border-color: #4F46E5;
      outline: none;
      box-shadow: 0 0 8px #4F46E5aa;
    }

    button {
      background: #6366F1;
      color: white;
      border: none;
      border-radius: 20px;
      font-size: 1rem;
      padding: 12px 24px;
      cursor: pointer;
      display: block;
      margin: 0 auto;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #4F46E5;
    }

    a.link-dark {
      color: #333;
      font-size: 0.9rem;
      text-decoration: underline;
      margin-bottom: 20px;
      display: inline-block;
    }
    a.link-dark:hover {
      color: #4F46E5;
    }

    /* Alerts */
    .alert {
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 600;
    }

    .alert-danger {
      background: #f8d7da;
      color: #842029;
      border: 1px solid #f5c2c7;
    }

    .alert-success {
      background: #d1e7dd;
      color: #0f5132;
      border: 1px solid #badbcc;
    }
  </style>
  <script>
    // For responsive nav toggle
    function toggleMenu() {
      const menu = document.querySelector('.nav-menu');
      menu.classList.toggle('show');
    }
  </script>
</head>
<body>

  <div class="container">

    <nav class="navbar">
      <a href="admin.php" class="navbar-brand">Admin</a>

      <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>

      <ul class="nav-menu">
        <li><a href="admin.php">Library</a></li>
        <li><a href="add-book.php">Add Book</a></li>
        <li><a href="add-category.php">Add Category</a></li>
        <li><a href="add-author.php">Add Author</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>

    <form action="php/edit-book.php" method="post" enctype="multipart/form-data">

      <h1>Edit Book</h1>

      <?php if (isset($_GET['error'])) { ?>
      <div class="alert alert-danger" role="alert">
        <?=htmlspecialchars($_GET['error']); ?>
      </div>
      <?php } ?>

      <?php if (isset($_GET['success'])) { ?>
      <div class="alert alert-success" role="alert">
        <?=htmlspecialchars($_GET['success']); ?>
      </div>
      <?php } ?>

      <input type="hidden" name="book_id" value="<?=htmlspecialchars($book['id'])?>" />

      <label for="book_title">Book Title</label>
      <input id="book_title" type="text" name="book_title" value="<?=htmlspecialchars($book['title'])?>" required />

      <label for="book_description">Book Description</label>
      <input id="book_description" type="text" name="book_description" value="<?=htmlspecialchars($book['description'])?>" required />

      <label for="book_author">Book Author</label>
      <select id="book_author" name="book_author" required>
        <option value="">Select author</option>
        <?php 
        if ($authors != 0) {
          foreach ($authors as $author) { 
            $selected = ($book['author_id'] == $author['id']) ? 'selected' : '';
            echo "<option value=\"" . htmlspecialchars($author['id']) . "\" $selected>" . htmlspecialchars($author['name']) . "</option>";
          }
        }
        ?>
      </select>

      <label for="book_category">Book Category</label>
      <select id="book_category" name="book_category" required>
        <option value="">Select category</option>
        <?php 
        if ($categories != 0) {
          foreach ($categories as $category) { 
            $selected = ($book['category_id'] == $category['id']) ? 'selected' : '';
            echo "<option value=\"" . htmlspecialchars($category['id']) . "\" $selected>" . htmlspecialchars($category['name']) . "</option>";
          }
        }
        ?>
      </select>

      <label for="book_cover">Book Cover</label>
      <input id="book_cover" type="file" name="book_cover" />
      <input type="hidden" name="current_cover" value="<?=htmlspecialchars($book['cover'])?>" />
      <a href="uploads/cover/<?=htmlspecialchars($book['cover'])?>" class="link-dark" target="_blank">Current Cover</a>

      <label for="file">File</label>
      <input id="file" type="file" name="file" />
      <input type="hidden" name="current_file" value="<?=htmlspecialchars($book['file'])?>" />
      <a href="uploads/files/<?=htmlspecialchars($book['file'])?>" class="link-dark" target="_blank">Current File</a>

      <button type="submit">Update</button>
    </form>

  </div>

</body>
</html>

<?php 
} else {
  header("Location: login.php");
  exit;
} 
?>
