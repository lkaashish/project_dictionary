<?php
require 'config.php';

/* refresh word-of-the-day */
if (isset($_GET['new'])) {
    $wod = $conn->query("SELECT * FROM words WHERE approved = 1 ORDER BY RAND() LIMIT 1")->fetch();
    $redirect = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: " . $redirect);
    exit;
}

/* search */
$search = '';
$results = [];
if (isset($_GET['q'])) {
    $search = trim($_GET['q']);
    $stmt = $conn->prepare("SELECT * FROM words WHERE word LIKE ? AND approved = 1");
    $stmt->execute(["%$search%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* word of the day */
if (!isset($wod)) {
    $wod = $conn->query("SELECT * FROM words WHERE approved = 1 ORDER BY RAND() LIMIT 1")->fetch();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title> Dictionary</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
  <div class="header-inner">
    <h1> Dictionary</h1>
    <nav class="top-nav">
      <a href="index.php" class="<?= basename($_SERVER['PHP_SELF'])=='index.php'?'active':'' ?>">Home</a>
      <a href="games.php">Games</a>
      <a href="leaders.php">Leaders</a>
    </nav>
    <nav class="user-nav">
      <?php if (isset($_SESSION['user'])): ?>
        Welcome <?= htmlspecialchars($_SESSION['user']) ?> |
        <a href="request.php">Request word</a> |
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a> | <a href="register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main>
  <form method="get" action="" class="search-row">
    <input type="text" name="q" placeholder="Search word…" value="<?= htmlspecialchars($search) ?>">
    <button>Search</button>
  </form>
  <?php if ($search !== ''): ?>
<h2>Results for <?= htmlspecialchars($search) ?></h2>
<?php if ($results): ?>
  <?php foreach ($results as $r): ?>
    <div class="card">
      <strong><?= htmlspecialchars($r['word']) ?></strong>
      <?php if ($r['part_of_speech']): ?>
        <em>(<?= htmlspecialchars($r['part_of_speech']) ?>)</em>
      <?php endif; ?>
      <p><?= nl2br(htmlspecialchars($r['definition'])) ?></p>
      <?php if ($r['synonyms']): ?>
        <p><strong>Synonyms:</strong> <?= htmlspecialchars($r['synonyms']) ?></p>
      <?php endif; ?>
      <?php if ($r['antonyms']): ?>
        <p><strong>Antonyms:</strong> <?= htmlspecialchars($r['antonyms']) ?></p>
      <?php endif; ?>
      <?php if ($r['example_sent']): ?>
        <p><strong>Example:</strong> <q><?= htmlspecialchars($r['example_sent']) ?></q></p>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <p>No words found.</p>
<?php endif; ?>
  <?php endif; ?>
  <h2>
    Word of the day
    <a href="?new=1" title="Change word" style="margin-left:.5em;border:none;background:none;color:var(--burgundy);font-size:1.1em;text-decoration:none;">⟳</a>
  </h2>
  <div class="card wod">
    <strong><?= htmlspecialchars($wod['word']) ?></strong>
    <?php if ($wod['part_of_speech']): ?>
      <em>(<?= htmlspecialchars($wod['part_of_speech']) ?>)</em>
    <?php endif; ?>
    <p><?= nl2br(htmlspecialchars($wod['definition'])) ?></p>
    <?php if ($wod['example_sent']): ?>
      <p><strong>Example:</strong> <q><?= htmlspecialchars($wod['example_sent']) ?></q></p>
    <?php endif; ?>
  </div>
</main>
</body>
</html>