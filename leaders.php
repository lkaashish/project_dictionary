<?php
require 'config.php';
$leaders = $conn->query(
  "SELECT added_by, COUNT(*) as c
   FROM words
   WHERE added_by != 'admin'
   GROUP BY added_by
   ORDER BY c DESC
   LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Top Submitters – Mini Dictionary</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="top-nav">
  <a href="index.php">Home</a>
  <a href="games.php">Games</a>
  <a href="leaders.php" class="active">Leaders</a>
</nav>
<main>
  <h2>Top Submitters</h2>
  <table>
    <tr><th>#</th><th>User</th><th>Words added</th></tr>
    <?php foreach($leaders as $idx=>$row): ?>
    <tr>
      <td><?=$idx+1?></td>
      <td><?=htmlspecialchars($row['added_by'])?></td>
      <td><?=$row['c']?></td>
    </tr>
    <?php endforeach; ?>
  </table>
</main>
</body>
</html>