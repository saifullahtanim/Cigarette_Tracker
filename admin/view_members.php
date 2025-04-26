<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $con->query("DELETE FROM members WHERE id=$id");
    echo "<script>alert('Member Deleted Successfully');window.location='view_members.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Members</title>
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
            border-radius: 18px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        table img {
            height: 60px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
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
            margin-bottom: 20px;
        }
        .btn-back, .btn-add {
            padding: 8px 20px;
            font-weight: 500;
            border-radius: 10px;
            text-decoration: none;
            color: white;
        }
        .btn-back {
            background: #6c757d;
        }
        .btn-back:hover {
            background: #5a6268;
        }
        .btn-add {
            background: #0d6efd;
        }
        .btn-add:hover {
            background: #0b5ed7;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="top-buttons">
        <a href="index.php" class="btn-back">🏠 Home</a>
        <a href="members.php" class="btn-add">➕ Add New Member</a>
    </div>
    
    <h2>Member List</h2>
    <table class="table table-bordered table-hover">
        <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Photo</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php
            $result = $con->query("SELECT * FROM members ORDER BY id DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['phone']}</td>
                    <td>{$row['email']}</td>
                    <td><img src='{$row['photo']}' alt='Member Photo'></td>
                    <td>
                        <a href='view_members.php?delete={$row['id']}'
                           onclick=\"return confirm('Are you sure?')\"
                           class='btn btn-sm btn-danger'>🗑️ Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
