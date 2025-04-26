<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Update Paid Amount via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_paid'])) {
    $member_id = $_POST['member_id'];
    $add_amount = $_POST['add_amount'];
    $con->query("UPDATE members SET paid_amount = paid_amount + $add_amount WHERE id = $member_id");
    exit('updated');
}

if (isset($_POST['delete_member'])) {
    $id = intval($_POST['delete_member']);

    // Reset paid amount
    $con->query("UPDATE members SET paid_amount = 0 WHERE id = $id");

    // Log clear time (optional, for summary filtering)
    $con->query("INSERT INTO member_clear_logs (member_id, cleared_at) VALUES ($id, NOW())");

    // ❌ Do NOT delete logs unless you really want
    // $con->query("DELETE FROM cigarette_logs WHERE member_id = $id");

    exit('cleared');
}

// Fetch summary data after last clear only
$summary = $con->query("
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #dceeff, #f8f9fa);
      font-family: 'Segoe UI', sans-serif;
      padding: 50px 15px;
    }
    .container {
      max-width: 1000px;
      background: white;
      border-radius: 30px;
      padding: 40px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      position: relative;
    }
    .top-buttons {
      position: absolute;
      top: 25px;
      right: 30px;
      display: flex;
      gap: 10px;
    }
    .btn-custom {
      font-weight: bold;
      padding: 6px 16px;
      border-radius: 8px;
      text-decoration: none;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      border: none;
      cursor: pointer;
    }
    .btn-user { background: #3498db; color: white; }
    .btn-logout { background: #e74c3c; color: white; }
    .btn-member { background: #28a745; color: white; }
    .btn-user:hover { background: #2980b9; }
    .btn-logout:hover { background: #c0392b; }
    .btn-member:hover { background: #1e7e34; }
    .card-box {
      background: #f4f9ff;
      border-radius: 20px;
      padding: 30px;
      text-align: center;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
      transition: 0.3s ease-in-out;
      cursor: pointer;
    }
    .card-box:hover {
      transform: translateY(-5px);
      background: #eaf4ff;
    }
    .profile-img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #007bff;
    }
    .table th, .table td {
      vertical-align: middle !important;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="top-buttons">
    <button class="btn-custom btn-member" data-bs-toggle="modal" data-bs-target="#memberModal">🧾 Member Summary</button>
    <a href="../user/index.php" class="btn-custom btn-user">👤 User</a>
    <a href="logout.php" class="btn-custom btn-logout">Logout</a>
  </div>

  <h1 class="mb-3">Welcome, Admin!</h1>
  <p>Use the menu below to manage cigarettes, roommates, and view logs.</p>

  <div class="row mt-5">
    <div class="col-md-4">
      <div class="card-box" onclick="window.location.href='add_cigarette.php'">
        <img src="https://cdn-icons-png.flaticon.com/512/600/600383.png" width="40">
        <h5 class="mt-3">Cigarettes</h5>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-box" onclick="window.location.href='members.php'">
        <img src="https://cdn-icons-png.flaticon.com/512/9775/9775776.png" width="40">
        <h5 class="mt-3">Add Member</h5>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-box" onclick="window.location.href='activity.php'">
        <img src="https://cdn-icons-png.flaticon.com/512/9217/9217614.png" width="40">
        <h5 class="mt-3">Logs</h5>
      </div>
    </div>
  </div>
</div>

<!-- Member Summary Modal -->
<div class="modal fade" id="memberModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">🧾 Member Payment Summary</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered align-middle">
          <thead class="table-primary">
            <tr>
              <th>Profile</th>
              <th>Name</th>
              <th>Total Buy</th>
              <th>Paid</th>
              <th>Due</th>
              <th>Pay</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php while ($m = $summary->fetch_assoc()): 
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
              <td>৳ <?= number_format($total, 2) ?><br><small>(<?= $m['total_items'] ?> pcs)</small></td>
              <td>৳ <?= number_format($paid, 2) ?></td>
              <td><strong class="text-<?= $due > 0 ? 'danger' : 'success' ?>">৳ <?= number_format($due, 2) ?></strong></td>
              <td>
                <?php if ($due > 0): ?>
                <form class="d-flex" onsubmit="updatePaid(event, <?= $m['id'] ?>)">
                  <input type="number" name="add_amount" class="form-control form-control-sm me-2" placeholder="৳" min="1" required>
                  <button type="submit" class="btn btn-sm btn-success">Pay</button>
                </form>
                <?php else: ?>
                  <span class="text-muted">Fully Paid</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($due <= 0): ?>
                <button onclick="clearMember(<?= $m['id'] ?>)" class="btn btn-sm btn-danger">Clear</button>
                <?php else: ?>
                <button class="btn btn-sm btn-secondary" disabled>Pending</button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function updatePaid(e, id) {
  e.preventDefault();
  const form = e.target;
  const amount = form.add_amount.value;
  fetch('', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `update_paid=1&member_id=${id}&add_amount=${amount}`
  }).then(() => location.reload());
}

function clearMember(id) {
  if (confirm("Clear payment history for this member?")) {
    fetch('', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: `delete_member=${id}`
    }).then(() => location.reload());
  }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
