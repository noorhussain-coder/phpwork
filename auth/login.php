<?php
session_start();
include('../configs/database.php');

if(isset($_POST['login'])){
    
extract($_POST);
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
echo 'prev';
print_r($result ,'hi');
    // if($result->num_rows > 0){
    if(mysqli_num_rows($result)){
        $user = $result->fetch_assoc();
         $passwordCheck=password_verify($password,$user['password']);
        echo $user['username'] ;
         if($passwordCheck){
            $_SESSION['user_id']=$user['id'];
            $_SESSION['username']=$user['username'];
            $_SESSION['role']=$user['role'];
           header("Location: ../index.php");
//             if($user['role']==='admin'){
//                 header('Location :admin_dashboard.php');
//             }else{
//  header('Location: index.php');
//     exit;
//            \ }
         }  else{echo 'username and password incorrect';} 

    } else {
        $_SESSION['error'] = "User not found!";
        echo 'user not found';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title text-center mb-3">Login</h3>

                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST"   action="login.php">
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="email" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button name="login" class="btn btn-primary w-100">Login</button>
                    </form>
                    <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
