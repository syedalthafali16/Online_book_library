<?php  
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    
    if (!isset($_GET['id'])) {
        header("Location: admin.php");
        exit;
    }

    $id = $_GET['id'];
    include "db_conn.php";
    include "php/func-author.php";
    $author = get_author($conn, $id);
    
    if ($author == 0) {
        header("Location: admin.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Author</title>
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
      position: relative;
    }

    .navbar-brand {
      font-size: 1.5rem;
      font-weight: bold;
      text-decoration: none;
      color: #000;
    }

    .menu-icon {
      display: none;
      font-size: 1.8rem;
      cursor: pointer;
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

    @media (max-width: 768px) {
      .menu-icon {
        display: block;
      }

      .nav-menu {
        flex-direction: column;
        background: white;
        border-top: 1px solid #ddd;
        display: none;
        position: absolute;
        width: 100%;
        left: 0;
        top: 60px;
        padding: 10px 0;
        border-radius: 0 0 10px 10px;
        z-index: 100;
      }

      .nav-menu.show {
        display: flex;
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

    input[type="text"] {
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

    input[type="text"]:focus {
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
</head>
<body>

  <div class="container">
    <nav class="navbar">
      <a class="navbar-brand" href="admin.php">Admin</a>
      <span class="menu-icon" onclick="toggleMenu()">☰</span>
      <ul class="nav-menu" id="navMenu">
        <li><a href="admin.php">Library</a></li>
        <li><a href="add-book.php">Add Book</a></li>
        <li><a href="add-category.php">Add Category</a></li>
        <li><a href="add-author.php">Add Author</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>

    <form action="php/edit-author.php" method="post">
      <h1>Edit Author</h1>

      <?php if (isset($_GET['error'])) { ?>
        <div class="alert alert-danger">
          <?=htmlspecialchars($_GET['error']); ?>
        </div>
      <?php } ?>

      <?php if (isset($_GET['success'])) { ?>
        <div class="alert alert-success">
          <?=htmlspecialchars($_GET['success']); ?>
        </div>
      <?php } ?>

      <input type="text" name="author_id" value="<?= $author['id'] ?>" hidden>

      <label for="author_name">Author Name</label>
      <input type="text" name="author_name" id="author_name" value="<?= $author['name'] ?>" required>

      <button type="submit">Update</button>
    </form>
  </div>

  <script>
    function toggleMenu() {
      const navMenu = document.getElementById("navMenu");
      navMenu.classList.toggle("show");
    }
  </script>

</body>
</html>

<?php 
} else {
  header("Location: login.php");
  exit;
} 
?>
