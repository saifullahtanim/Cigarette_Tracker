<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $con->query("DELETE FROM cigarettes WHERE id = $id");
    echo "<script>alert('Deleted Successfully'); window.location='view_cigarettes.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cigarette Inventory</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #e3f2fd;
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
    }
    .container {
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      max-width: 1000px;
      margin: auto;
    }
    h2 {
      color: #0d47a1;
      font-weight: 700;
      margin-bottom: 25px;
      text-align: center;
    }
    .top-buttons {
      display: flex;
      justify-content: start;
      gap: 15px;
      margin-bottom: 25px;
    }
    .btn-home, .btn-add {
      padding: 10px 20px;
      font-weight: 500;
      border-radius: 10px;
      text-decoration: none;
      color: white;
    }
    .btn-home {
      background: #6c757d;
    }
    .btn-home:hover {
      background: #5a6268;
    }
    .btn-add {
      background: #0d6efd;
    }
    .btn-add:hover {
      background: #0b5ed7;
    }
    .btn-danger {
      font-weight: 500;
      border-radius: 6px;
    }
    table img {
      height: 60px;
      border-radius: 6px;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body>

<div class="container">
  <div class="top-buttons">
    <a href="index.php" class="btn-home">🏠 Home</a>
    <a href="add_cigarette.php" class="btn-add">➕ Add New Cigarette</a>
  </div>

  <h2>Cigarette Stock Overview</h2>

  <table class="table table-bordered table-hover mt-4">
    <thead class="table-dark text-center">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Stock</th>
        <th>Price (৳)</th>
        <th>Image</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody class="text-center">
      <?php
      $result = $con->query("SELECT * FROM cigarettes ORDER BY id DESC");
      while ($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>{$row['id']}</td>";
          echo "<td>{$row['name']}</td>";
          echo "<td>{$row['stock_quantity']}</td>";

          // Price Show Logic
          $price = $row['price'];
          if ($price > 0) {
              echo "<td>৳" . number_format($price, 2) . "</td>";
          } else {
              echo "<td><span class='text-danger'>Not set</span></td>";
          }
          
          echo "<td><img src='{$row['image_path']}' alt='Cigarette'></td>";
          echo "<td>
                  <a href='view_cigarettes.php?delete={$row['id']}'
                     onclick=\"return confirm('Are you sure?')\"
                     class='btn btn-sm btn-danger'>🗑️ Delete</a>
                </td>";
          echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</div>

</body>
</html>
