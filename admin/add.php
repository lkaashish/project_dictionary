<?php
require '../config.php';
session_start();
if(!isset($_SESSION['admin'])){ header('Location: login.php'); exit; }
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $w  = trim($_POST['word']          ?? '');
    $d  = trim($_POST['definition']    ?? '');
    $ps = trim($_POST['part_of_speech']?? '');
    $syn= trim($_POST['synonyms']      ?? '');
    $ant= trim($_POST['antonyms']      ?? '');
    $ex = trim($_POST['example_sent']  ?? '');

    if($w==='' || $d==='') $msg='Word and definition are required.';
    else{
        $stmt=$conn->prepare(
           "INSERT INTO words
            (word,definition,part_of_speech,synonyms,antonyms,example_sent,added_by)
            VALUES (?,?,?,?,?,?,'admin')");
        $stmt->execute([$w,$d,$ps,$syn,$ant,$ex]);
        $msg='Word added.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add word – Admin</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<header>
  <div class="header-inner">
    <h1>Direct add (admin)</h1>
    <nav><a href="dashboard.php">Back</a></nav>
  </div>
</header>
<main>
  <h2>Add word</h2>
  <?php if($msg) echo "<p class='ok'>$msg</p>"; ?>
  <form method="post">
    <input name="word" required placeholder="Word *">
    <input name="part_of_speech" placeholder="Part of speech (noun/verb/adjective…)">
    <textarea name="definition" required placeholder="Definition *"></textarea>
    <input name="synonyms" placeholder="Synonyms (comma separated)">
    <input name="antonyms" placeholder="Antonyms (comma separated)">
    <textarea name="example_sent" placeholder="Example sentence"></textarea>
    <button>Add</button>
  </form>
</main>
</body>
</html>