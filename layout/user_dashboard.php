<?php
// session_start();
// if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user'){
//     // header("Location: login.php");
//     exit;
// }
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">User Dashboard</a>
        <div class="ms-auto">
            <span class="navbar-text me-3">Hello, <?= $_SESSION['username']; ?></span>
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1>Welcome to your dashboard, <?= $_SESSION['username']; ?>!</h1>
    <p>This is your user page.</p>
</div>
</body>
</html>
