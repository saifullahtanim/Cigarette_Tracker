<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// ✅ Delete Log if needed
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $con->query("DELETE FROM cigarette_logs WHERE id = $delete_id");
    echo "<script>alert('✅ Log Deleted Successfully'); window.location='activity.php';</script>";
    exit();
}

// ✅ Fetch Logs
$sql = "SELECT 
            cl.id,
            m.name AS member_name,
            c.name AS cigarette_name,
            c.price AS price_per_piece,
            cl.quantity,
            (cl.quantity * c.price) AS total_price,
            cl.created_at
        FROM cigarette_logs cl
        JOIN members m ON cl.member_id = m.id
        JOIN cigarettes c ON c.id = cl.cigarette_id
        ORDER BY cl.created_at DESC";

$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Activity Logs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f4f6f8;
    }
    .container {
      margin-top: 30px;
    }
    .delete-btn {
      color: red;
      text-decoration: none;
      font-weight: bold;
    }
    .delete-btn:hover {
      text-decoration: underline;
    }
    .icon-trash {
      font-size: 16px;
      margin-right: 5px;
    }
  </style>
</head>
<body>
<div class="container">

  <!-- Back Button and Title -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="index.php" class="btn btn-primary">🏠 Back to Home</a>
    <h2 class="text-center flex-grow-1">📊 All Cigarette Purchase Logs</h2>
    <div style="width: 120px;"></div>
  </div>

  <!-- Logs Table -->
  <table class="table table-bordered table-hover text-center">
    <thead class="table-dark">
      <tr>
        <th>Member</th>
        <th>Cigarette</th>
        <th>Price (৳)</th>
        <th>Quantity</th>
        <th>Total (৳)</th>
        <th>Time</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['member_name'] ?></td>
          <td><?= $row['cigarette_name'] ?></td>
          <td>৳<?= number_format($row['price_per_piece'], 2) ?></td>
          <td><?= $row['quantity'] ?></td>
          <td>৳<?= number_format($row['total_price'], 2) ?></td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <a class="delete-btn" href="activity.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure to delete this log?')">
              🗑️ Delete
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</div>
</body>
</html>
