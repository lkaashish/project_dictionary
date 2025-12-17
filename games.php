<?php
require 'config.php';
/* grab 50 approved words for the JS pool */
$stmt = $conn->query("SELECT word, definition FROM words WHERE approved = 1 ORDER BY RAND() LIMIT 50");
$pool = json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE | JSON_HEX_APOS);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Word Puzzle – Mini Dictionary</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* ---------- nav bar (same look as header.inc.php but inline) ---------- */
    .top-nav{
      text-align:center;
      margin-bottom:1.5rem;
      border-bottom:1px solid var(--rule);
      padding-bottom:.8rem;
    }
    .top-nav a{
      color:var(--burgundy);
      margin:0 .8rem;
      text-decoration:none;
      font-weight:600;
    }
    .top-nav a.active{text-decoration:underline;}

    /* ---------- puzzle area ---------- */
    .puzzle-box{
      max-width:500px;
      margin:2rem auto;
      text-align:center;
      background:#fffdf6;
      border:1px solid var(--rule);
      padding:2rem;
    }
    .scrambled{
      font-size:2rem;
      letter-spacing:.2rem;
      margin:1.2rem 0;
      color:var(--burgundy);
    }
    .puzzle-box input{
      width:60%;
      display:inline-block;
    }
    .puzzle-box button{
      width:30%;
      margin-left:.5rem;
    }
    .hint{
      font-size:.9rem;
      color:var(--light);
      margin-top:.8rem;
    }
    .outcome{
      margin-top:1rem;
      font-weight:600;
    }
    .outcome.ok{color:#2a9d8f;}
    .outcome.error{color:#e63946;}
  </style>
</head>
<body>

<!-- simple nav bar -->
<nav class="top-nav">
  <a href="index.php">Home</a>
  <a href="games.php" class="active">Games</a>
</nav>

<main>
  <h2>Word Puzzle</h2>

  <div class="puzzle-box">
    <p>Unscramble the letters and find the English word.</p>

    <div id="scramble" class="scrambled">⋯</div>

    <input id="guess" type="text" placeholder="Your guess">
    <button id="checkBtn">Check</button>

    <div id="hint" class="hint"></div>
    <div id="outcome" class="outcome"></div>

    <button id="nextBtn" style="display:none;">Next word →</button>
  </div>
</main>

<script>
/* ---------- word pool from PHP ---------- */
const pool = <?= $pool ?>;

/* ---------- helpers ---------- */
function shuffle(str){
  return str.split('').sort(()=>Math.random()-.5).join('');
}

function pick(){
  const {word, definition} = pool[Math.floor(Math.random()*pool.length)];
  return {word, definition, scrambled:shuffle(word)};
}

let current = pick();

/* ---------- display first word ---------- */
function show(){
  document.getElementById('scramble').textContent = current.scrambled;
  document.getElementById('hint').textContent = 'Hint: ' + current.definition;
  document.getElementById('guess').value='';
  document.getElementById('outcome').textContent='';
  document.getElementById('outcome').className='outcome';
  document.getElementById('checkBtn').style.display='inline-block';
  document.getElementById('nextBtn').style.display='none';
}
show();

/* ---------- check answer ---------- */
document.getElementById('checkBtn').addEventListener('click',()=>{
  const guess = document.getElementById('guess').value.trim().toLowerCase();
  const ok = guess === current.word.toLowerCase();
  const out = document.getElementById('outcome');
  if(ok){
    out.textContent = '✅ Correct! Well done.';
    out.classList.add('ok');
  }else{
    out.textContent = '❌ Not quite – try again.';
    out.classList.add('error');
  }
  document.getElementById('checkBtn').style.display='none';
  document.getElementById('nextBtn').style.display='inline-block';
});

/* ---------- next word ---------- */
document.getElementById('nextBtn').addEventListener('click',()=>{
  current = pick();
  show();
});
</script>

</body>
</html>