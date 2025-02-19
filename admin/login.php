<?php
session_start();
include "includes/functions.php";
login();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexora Admin Login</title>
    <link rel="icon" href="../images/logo.png" type="image/icon type">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            max-width: 400px;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004999;
        }

        .text-center a {
            color: #007bff;
            font-size: 0.9rem;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Admin Sign In</h2>
        <?php message(); ?>
        <form id="loginform" class="row g-3" method="post" action="login.php">
            <div class="col-12">
                <label for="login-username" class="form-label">Email</label>
                <input type="email" class="form-control" id="login-username" name="adminEmail" placeholder="Enter your email" required>
            </div>
            <div class="col-12">
                <label for="login-password" class="form-label">Password</label>
                <input type="password" class="form-control" id="login-password" name="adminPassword" placeholder="Enter your password" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary w-100" name="login">Login</button>
            </div>
            <div class="col-12 text-center mt-3">
                Regular user?
                <a href="../login.php">Sign In Here</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>