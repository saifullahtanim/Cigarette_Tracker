<?php
session_start();
include('../db/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
    $result = $con->query($sql);

    if ($result->num_rows === 1) {
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .login-box {
            background: #ffffff;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 420px;
            transition: all 0.3s ease;
        }
        .login-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 35px;
            color: #1e3c72;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #1e3c72;
            border: none;
        }
        .btn-custom:hover {
            background-color: #2a5298;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger text-center'>$error</div>"; ?>
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-custom text-white w-100">Login</button>
        </form>
    </div>
</body>
</html>