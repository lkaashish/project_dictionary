<?php
require 'config.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $user=$_POST['username']; $pass=$_POST['password'];
    $stmt=$conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$user]); $row=$stmt->fetch();
    if($row && password_verify($pass,$row['password'])){
        $_SESSION['user']=$row['username'];
        header('Location: index.php'); exit;
    }else $msg='Invalid credentials.';
}
?>
<!doctype html>
<html><head><title>Login</title><link rel="stylesheet" href="style.css"></head>
<body>
<h2>Login</h2>
<?php if($msg) echo "<p class=error>$msg</p>"; ?>
<form method="post">
    <input name="username" required placeholder="Username">
    <input type="password" name="password" required placeholder="Password">
    <button>Login</button>
</form>
<a href="register.php">Create account</a>
</body></html>