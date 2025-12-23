<?php
// session_start();

include('../configs/database.php');
if(isset($_POST['register'])){
extract($_POST);
$user="SELECT * FROM users ";
$alluser=$conn->query($user);
$password=password_hash($password,PASSWORD_DEFAULT);
if($alluser->num_rows>0){

$sql="INSERT INTO users(username,email,password) VALUES('$username','$email','$password')";
$result=$conn->query($sql);
if($result){
$_SESSION['success']='Sucessfuly Register user';
header("Location: login.php");
}else{
    $_SESSION['error']='error already Access';
}
}else{
 
$sql="INSERT INTO users(username,email,password,role) VALUES('$username','$email','$password','admin')";
$result=$conn->query($sql);
if($result){
$_SESSION['success']='Sucessfuly Register user';
header("Location: login.php");
}else{
    $_SESSION['error']='error already Access';
}   
}

// $password=password_hash($password,PASSWORD_DEFAULT);






    // $username = $_POST['username'];
    // $email = $_POST['email'];
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // $sql = "INSERT INTO users (username,email,password) VALUES ('$username','$email','$password')";
    // if($conn->query($sql)){
    //     $_SESSION['success'] = "Registration successful! Login now.";
    //     header("Location: login.php");
    //     exit;
    // } else {
    //     $_SESSION['error'] = "Error: " . $conn->error;
    // }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title text-center mb-3">Register</h3>

                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button name="register" class="btn btn-primary w-100">Register</button>
                    </form>
                    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
