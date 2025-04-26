<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $image = $_FILES['photo']['name'];
    $temp_name = $_FILES['photo']['tmp_name'];
    $upload_path = "../uploads/members/" . basename($image);

    if (move_uploaded_file($temp_name, $upload_path)) {
        $sql = "INSERT INTO members (name, phone, email, photo) 
                VALUES ('$name', '$phone', '$email', '$upload_path')";
        $con->query($sql);
        echo "<script>alert('Member Added Successfully');</script>";
    } else {
        echo "<script>alert('Failed to upload photo');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Member</title>
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
    <h2 class="form-title text-center">Add New Member</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" required class="form-control" placeholder="Full Name">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" required class="form-control" placeholder="e.g., 017xxxxxxxx">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" required class="form-control" placeholder="example@mail.com">
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Upload Photo</label>
            <input type="file" name="photo" accept="image/*" required class="form-control">
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary px-5">➕ Add Member</button>
        </div>
    </form>
    <div class="action-buttons">
        <a href="index.php" class="btn btn-back">🔙 Back to Dashboard</a>
        <a href="view_members.php" class="btn btn-view">📋 View Members</a>
    </div>
</div>

</body>
</html>