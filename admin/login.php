<?php
session_start();
if($_SERVER['REQUEST_METHOD']==='POST'){
    $u=$_POST['user']; $p=$_POST['pass'];
    if($u==='aryan' && $p==='aryan123'){
        $_SESSION['admin']=1;
        header('Location: dashboard.php'); exit;
    }else $msg='Wrong admin credentials.';
}
?>
<!doctype html>
<html><head><title>Admin</title><link rel="stylesheet" href="../style.css"></head>
<body>
<h2>Admin Login</h2>
<?php if(isset($msg)) echo "<p class='error'>$msg</p>"; ?>
<form method="post">
    <input name="user" required placeholder="Admin user">
    <input type="password" name="pass" required placeholder="Password">
    <button>Login</button>
</form>
<a href="../index.php">Back to site</a>
</body></html>
