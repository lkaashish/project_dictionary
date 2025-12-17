<?php
require 'config.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $user=$_POST['username']??''; $pass=$_POST['password']??'';
    if($user=='' || $pass=='') $msg='Both fields required.';
    else{
        $hash=password_hash($pass,PASSWORD_DEFAULT);
        try{
            $stmt=$conn->prepare("INSERT INTO users (username,password) VALUES (?,?)");
            $stmt->execute([$user,$hash]);
            header('Location: login.php'); exit;
        }catch(PDOException $e){
            $msg='Username already taken.';
        }
    }
}
?>
<!doctype html>
<html><head><title>Register</title><link rel="stylesheet" href="style.css"></head>
<body>
<h2>Register</h2>
<?php if($msg) echo "<p class=error>$msg</p>"; ?>
<form method="post">
    <input name="username" required placeholder="Username">
    <input type="password" name="password" required placeholder="Password">
    <button>Register</button>
</form>
<a href="login.php">Already have account? Login</a>
</body></html>