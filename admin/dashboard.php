<?php
require '../config.php';
session_start();
if(!isset($_SESSION['admin'])){ header('Location: login.php'); exit; }

/* -------- approve -------- */
if(isset($_GET['approve'])){
    $id = (int)$_GET['approve'];
    $req= $conn->query("SELECT * FROM requests WHERE id=$id")->fetch();
    if($req){
        $conn->prepare(
           "INSERT INTO words
            (word,definition,part_of_speech,synonyms,antonyms,example_sent,added_by)
            VALUES (?,?,?,?,?,?,?)")
        ->execute([
            $req['word'],
            $req['definition'],
            $req['part_of_speech'],
            $req['synonyms'],
            $req['antonyms'],
            $req['example_sent'],
            $req['requested_by']
        ]);
        $conn->query("UPDATE requests SET status='approved' WHERE id=$id");
    }
}

/* -------- reject -------- */
if(isset($_GET['reject'])){
    $id=(int)$_GET['reject'];
    $conn->query("UPDATE requests SET status='rejected' WHERE id=$id");
}

$pending = $conn->query("SELECT * FROM requests WHERE status='pending' ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<header>
  <div class="header-inner">
    <h1>Admin Panel</h1>
    <nav>
      <a href="../index.php">Back to site</a> |
      <a href="add.php">Direct add</a> |
      <a href="logout.php">Logout</a>
    </nav>
  </div>
</header>
<main>
  <h2>Pending requests</h2>
  <table>
    <tr>
      <th>Word</th>
      <th>Part</th>
      <th>Definition</th>
      <th>Synonyms</th>
      <th>Antonyms</th>
      <th>Example</th>
      <th>By</th>
      <th></th>
    </tr>
    <?php foreach($pending as $r): ?>
    <tr>
      <td><?=htmlspecialchars($r['word'])?></td>
      <td><?=htmlspecialchars($r['part_of_speech'])?></td>
      <td><?=nl2br(htmlspecialchars($r['definition']))?></td>
      <td><?=htmlspecialchars($r['synonyms'])?></td>
      <td><?=htmlspecialchars($r['antonyms'])?></td>
      <td><q><?=htmlspecialchars($r['example_sent'])?></q></td>
      <td><?=htmlspecialchars($r['requested_by'])?></td>
      <td>
        <a href="?approve=<?=$r['id']?>">Approve</a> |
        <a href="?reject=<?=$r['id']?>">Reject</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</main>
</body>
</html>