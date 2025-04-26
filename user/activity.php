<?php
include('../db/config.php');

// Get last log
$last_log = $con->query("SELECT cl.*, m.name as member_name, c.name as cigarette_name, c.price 
                         FROM cigarette_logs cl
                         JOIN members m ON cl.member_id = m.id
                         JOIN cigarettes c ON cl.cigarette_id = c.id
                         ORDER BY cl.id DESC LIMIT 1")->fetch_assoc();

$total_cost = $last_log['quantity'] * $last_log['price'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Summary</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8fcff;
      font-family: 'Segoe UI', sans-serif;
      padding: 50px;
    }
    .summary-box {
      max-width: 600px;
      margin: auto;
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      padding: 30px;
      text-align: center;
    }
    .summary-box h2 {
      color: #0d6efd;
      font-weight: 700;
      margin-bottom: 25px;
    }
    .info-text {
      font-size: 1.1rem;
      margin: 10px 0;
    }
    .warning {
      margin-top: 30px;
      background: #ffe5e5;
      color: #b30000;
      font-weight: bold;
      padding: 15px;
      border-radius: 10px;
    }
    .back-btn {
      margin-top: 25px;
    }
  </style>
</head>
<body>

<div class="summary-box">
  <h2>🧾 Order Summary</h2>

  <p class="info-text"><strong>Member:</strong> <?php echo $last_log['member_name']; ?></p>
  <p class="info-text"><strong>Cigarette:</strong> <?php echo $last_log['cigarette_name']; ?></p>
  <p class="info-text"><strong>Price per piece:</strong> ৳<?php echo $last_log['price']; ?></p>
  <p class="info-text"><strong>Quantity:</strong> <?php echo $last_log['quantity']; ?> pcs</p>
  <p class="info-text"><strong>Total:</strong> <span class="text-success fw-bold">৳<?php echo $total_cost; ?></span></p>

  <div class="warning">
    ⚠️ ধূমপান মৃত্যুর কারণ
  </div>

  <div class="back-btn">
    <a href="index.php" class="btn btn-outline-primary mt-3">⬅️ Back to Home</a>
  </div>
</div>

</body>
</html>
