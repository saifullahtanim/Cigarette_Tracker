<?php
session_start();
include('../db/config.php');

// Fetch all cigarettes
$cigarettes = $con->query("SELECT * FROM cigarettes ORDER BY id DESC");
if ($cigarettes === false) {
    die("❌ Error fetching cigarettes data.");
}

// Fetch all members
$members = $con->query("SELECT * FROM members");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cigarette Selection System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-blue: #007bff;
      --secondary-blue: #0056b3;
      --primary-green: #28a745;
      --secondary-green: #218838;
    }
    body {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .header-area {
      background: white;
      padding: 1.5rem;
      margin: 2rem auto;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }
    .header-area h2 {
      font-weight: bold;
      font-size: 1.8rem;
    }
    .action-buttons .btn {
      font-size: 1.2rem;
      font-weight: 600;
      padding: 10px 25px;
      border-radius: 50px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: 0.4s ease;
    }
    .btn-success {
      background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
      border: none;
    }
    .btn-primary {
      background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
      border: none;
    }
    .btn-success:hover, .btn-primary:hover {
      transform: translateY(-3px);
      opacity: 0.9;
    }
    .cigarette-card {
      background: white;
      padding: 1.5rem;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.08);
      margin: 1rem;
      text-align: center;
      transition: 0.3s;
    }
    .cigarette-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 35px rgba(0,0,0,0.15);
    }
    .modal-header {
      background: linear-gradient(135deg, var(--primary-blue), var(--primary-green));
      color: white;
    }
    .summary-table th {
      background-color: #f1f5f9;
      text-align: center;
    }
    .summary-table td {
      vertical-align: middle;
      text-align: center;
    }
    .profile-img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid var(--primary-blue);
    }
    footer {
      margin-top: auto;
      background: #f1f5f9;
      padding: 1.5rem;
      text-align: center;
      font-size: 1rem;
      color: #555;
    }
  </style>
</head>

<body>

<div class="container">
  <div class="header-area">
    <h2><i class="fas fa-smoking"></i> Cigarette Selection</h2>
    <div class="action-buttons d-flex gap-3">
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#memberModal">
        <i class="fas fa-file-invoice-dollar"></i> Member Summary
      </button>
      <a href="../admin/login.php" class="btn btn-primary">
        <i class="fas fa-lock"></i> Admin Login
      </a>
    </div>
  </div>

  <div class="row g-4">
    <?php while ($c = $cigarettes->fetch_assoc()): ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <a href="select_member.php?cigarette_id=<?= $c['id'] ?>" class="text-decoration-none">
          <div class="cigarette-card">
            <img src="<?= htmlspecialchars($c['image_path']) ?>" class="img-fluid" alt="<?= htmlspecialchars($c['name']) ?>">
            <h5 class="mt-3"><?= htmlspecialchars($c['name']) ?></h5>
            <div class="mt-2">৳ <?= number_format($c['price'], 2) ?> / piece</div>
          </div>
        </a>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<!-- Member Summary Modal -->
<div class="modal fade" id="memberModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-file-invoice"></i> Member Payment Summary</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-hover summary-table">
          <thead class="table-primary">
            <tr>
              <th>Profile</th>
              <th>Name</th>
              <th>Total Buy</th>
              <th>Paid</th>
              <th>Due</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $members_summary = $con->query("
            SELECT 
              m.id, m.name, m.photo, m.paid_amount,
              IFNULL(SUM(cl.quantity), 0) as total_items,
              IFNULL(SUM(cl.quantity * c.price), 0) as total_spent
            FROM members m
            LEFT JOIN cigarette_logs cl ON cl.member_id = m.id
            LEFT JOIN cigarettes c ON c.id = cl.cigarette_id
            LEFT JOIN (
              SELECT member_id, MAX(cleared_at) AS last_clear
              FROM member_clear_logs
              GROUP BY member_id
            ) clr ON clr.member_id = m.id
            WHERE cl.id IS NULL OR (clr.last_clear IS NULL OR cl.created_at > clr.last_clear)
            GROUP BY m.id
            HAVING total_spent > 0 OR paid_amount > 0
          ");
          while ($m = $members_summary->fetch_assoc()): 
            $total = $m['total_spent'] ?? 0;
            $paid = $m['paid_amount'] ?? 0;
            $due = $total - $paid;
            $photo = (!empty($m['photo']) && file_exists("../uploads/" . $m['photo'])) 
                     ? "../uploads/" . $m['photo'] 
                     : "https://cdn-icons-png.flaticon.com/512/847/847969.png";
          ?>
            <tr>
              <td><img src="<?= $photo ?>" class="profile-img"></td>
              <td><?= htmlspecialchars($m['name']) ?></td>
              <td>৳ <?= number_format($total, 2) ?> (<?= $m['total_items'] ?> pcs)</td>
              <td>৳ <?= number_format($paid, 2) ?></td>
              <td class="<?= ($due > 0) ? 'text-danger' : 'text-success' ?>">৳ <?= number_format($due, 2) ?></td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<footer class="footer">
  <div class="container">
    Developed with ❤️ by Saifulla Tanim
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
