<?php
include('../db/config.php');

if (!isset($_GET['cigarette_id'])) {
    echo "❌ No cigarette selected!";
    exit;
}

$cigarette_id = intval($_GET['cigarette_id']);
$cigarette = $con->query("SELECT * FROM cigarettes WHERE id = $cigarette_id")->fetch_assoc();
$members = $con->query("SELECT * FROM members");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_POST['member_id'];
    $quantity = $_POST['quantity'];

    // Insert into cigarette_logs table
    $con->query("INSERT INTO cigarette_logs (member_id, quantity, cigarette_id, logged_at) 
                 VALUES ($member_id, $quantity, $cigarette_id, NOW())");

    // Redirect to activity page after inserting data
    header("Location: activity.php?log_id=" . $con->insert_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Select Member</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #e3f2fd, #fce4ec);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .container {
      flex: 1;
      padding: 40px 20px;
    }
    .section-title {
      text-align: center;
      font-size: 2.4rem;
      font-weight: 700;
      color: #0d47a1;
      margin-bottom: 40px;
    }
    .member-box {
      background: #ffffff;
      border-radius: 20px;
      padding: 20px;
      text-align: center;
      cursor: pointer;
      transition: 0.4s ease-in-out;
      box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }
    .member-box:hover {
      background: #e3f2fd;
      transform: scale(1.07);
      box-shadow: 0 12px 26px rgba(0,0,0,0.1);
    }
    .member-box img {
      height: 120px;
      width: 120px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 10px;
      border: 4px solid #64b5f6;
    }
    .form-section {
      display: none;
      margin-top: 50px;
      max-width: 500px;
      margin-left: auto;
      margin-right: auto;
      animation: fadeIn 0.5s ease-in-out;
    }
    .footer {
      text-align: center;
      padding: 15px 0;
      font-size: 0.9rem;
      color: #555;
      background: #f1f1f1;
      border-top: 1px solid #ccc;
    }
    .btn-back {
      display: inline-block;
      margin-top: 50px;
      text-align: center;
    }
    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(20px);}
      to {opacity: 1; transform: translateY(0);}
    }
    .btn-outline-info {
      border: 1px solid #0d47a1;
      color: #0d47a1;
    }
    .btn-outline-info:hover {
      background: #0d47a1;
      color: white;
    }
    .btn-success {
      background-color: #198754;
      color: white;
      font-weight: 500;
      transition: 0.3s ease;
    }
    .btn-success:hover {
      background-color: #0d6efd;
      color: white;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="section-title">Select Member for <strong><?php echo $cigarette['name']; ?></strong></div>

  <div class="row justify-content-center">
    <?php while ($member = $members->fetch_assoc()) { ?>
      <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="member-box" onclick="showForm(<?php echo $member['id']; ?>, '<?php echo $member['name']; ?>')">
          <img src="<?php echo !empty($member['photo']) ? $member['photo'] : 'https://cdn-icons-png.flaticon.com/512/847/847969.png'; ?>" alt="Member">
          <h5 class="mt-2 fw-bold text-dark"><?php echo htmlspecialchars($member['name']); ?></h5>
        </div>
      </div>
    <?php } ?>
  </div>

  <div class="form-section" id="orderForm">
    <div class="card border-0 shadow-lg">
      <div class="card-body">
        <h5 class="card-title text-center text-primary">Confirm Quantity</h5>
        <form method="POST">
          <input type="hidden" name="member_id" id="member_id">
          <div class="mb-3">
            <label class="form-label">Selected Member</label>
            <input type="text" class="form-control" id="member_name" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantity (pcs)</label>
            <input type="number" name="quantity" class="form-control" placeholder="How many pieces?" required>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-success px-4">✅ Confirm Order</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="text-center btn-back">
    <a href="index.php" class="btn btn-outline-info px-4">⬅️ Back to Cigarettes</a>
  </div>
</div>

<div class="footer">
  Developed by <strong>Saifulla Tanim</strong>
</div>

<script>
  function showForm(id, name) {
    document.getElementById('member_id').value = id;
    document.getElementById('member_name').value = name;
    document.getElementById('orderForm').style.display = 'block';
    document.getElementById('orderForm').scrollIntoView({ behavior: 'smooth' });
  }
</script>

</body>
</html>
