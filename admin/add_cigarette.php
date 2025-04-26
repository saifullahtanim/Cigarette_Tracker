<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $stock = $_POST['stock'];
    $price = !empty($_POST['unit_price']) ? $_POST['unit_price'] : 'NULL';

    // Handle image upload
    $image = $_FILES['image']['name'];
    $temp_name = $_FILES['image']['tmp_name'];
    $upload_path = "../uploads/" . basename($image);

    if (move_uploaded_file($temp_name, $upload_path)) {
        $sql = "INSERT INTO cigarettes (name, stock_quantity, price, image_path) 
                VALUES ('$name', $stock, $price, '$upload_path')";
        if ($con->query($sql)) {
            echo "<script>alert('Cigarette Added Successfully');</script>";
        } else {
            echo "<script>alert('Database Error: " . $con->error . "');</script>";
        }
    } else {
        echo "<script>alert('Failed to upload image');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Cigarette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #e3f2fd;
            font-family: 'Segoe UI', sans-serif;
            padding: 40px 15px;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .form-title {
            font-weight: 600;
            margin-bottom: 25px;
            color: #0d47a1;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }
        .action-buttons a {
            padding: 10px 20px;
            font-weight: 500;
            border-radius: 8px;
            text-decoration: none;
        }
        .btn-back {
            background: #6c757d;
            color: white;
        }
        .btn-view {
            background: #0d6efd;
            color: white;
        }
        .btn-back:hover {
            background: #5a6268;
        }
        .btn-view:hover {
            background: #0b5ed7;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2 class="form-title text-center">Add New Cigarette</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Cigarette Name</label>
            <input type="text" name="name" required class="form-control" placeholder="e.g., Gold Leaf, Hollywood">
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock Quantity</label>
            <input type="number" name="stock" required class="form-control" placeholder="e.g., 120">
        </div>
        <div class="mb-3">
            <label for="unit_price" class="form-label">Price per Piece (৳)</label>
            <input type="number" name="unit_price" class="form-control" placeholder="e.g., 80">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Cigarette Image</label>
            <input type="file" name="image" accept="image/*" required class="form-control">
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary px-5">➕ Add Cigarette</button>
        </div>
    </form>
    <div class="action-buttons">
        <a href="index.php" class="btn btn-back">🔙 Back to Dashboard</a>
        <a href="view_cigarettes.php" class="btn btn-view">📋 View List</a>
    </div>
</div>

</body>
</html>
