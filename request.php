<?php
require 'config.php';
if(!isset($_SESSION['user'])){ header('Location: login.php'); exit; }

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
           "INSERT INTO requests
            (word,definition,part_of_speech,synonyms,antonyms,example_sent,requested_by)
            VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$w,$d,$ps,$syn,$ant,$ex,$_SESSION['user']]);
        $msg='Request sent! Admin will review it.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Request word – Mini Dictionary</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="top-nav">
  <a href="index.php">Home</a>
  <a href="games.php">Games</a>
  <a href="leaders.php">Leaders</a>
</nav>
<main>
  <h2>Request a new word</h2>
  <?php if($msg) echo "<p class='ok'>$msg</p>"; ?>
  <form method="post">
    <input name="word" required placeholder="Word *">
    <input name="part_of_speech" placeholder="Part of speech (noun/verb/adjective…)">
    <textarea name="definition" required placeholder="Definition *"></textarea>
    <input name="synonyms" placeholder="Synonyms (comma separated)">
    <input name="antonyms" placeholder="Antonyms (comma separated)">
    <textarea name="example_sent" placeholder="Example sentence"></textarea>
    <button>Submit request</button>
  </form>
  <a href="index.php">Back</a>
</main>
</body>
</html>
