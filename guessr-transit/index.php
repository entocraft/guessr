<?php
// index.php — page principale du quiz constellation
// ouverture session et clear le tableau des scores
session_start();
$_SESSION['question_score']=array();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Guessr Constellation — Quiz</title>
  <link rel="stylesheet" href="../commons/css/homepage.css">
  <link rel="stylesheet" href="./assets/css/transit_custom.css">
</head>
<body>
  <header class="header">
    <div class="nav-links">
        <a href="../index.html" class="nav-item">Accueil</a>
        <a href="#" class="nav-item">Transit</a>
        <a href="#" class="nav-item">Tennis</a>
        <a href="#" class="nav-item">Ghibli</a>
    </div>
    <h3 class="logo" aria-label="GUESSR">GUESSR</h3>
  </header>
  <main class="container">
    <div class="play-bento">
        <div class="bento" role="navigation" aria-label="Bento menu">
            <a class="bento-left" href="#transit" aria-label="Transit big card">
                <div>
                    <h2>Transit</h2>
                    <p>Large card — description or CTA for the Transit quiz.</p>
                </div>
            </a>

            <div class="bento-right">
                <a class="bento-card bento-small" href="#transit-1" aria-label="Transit small card 1">
                    <div>
                        <h3>Transit — Round 1</h3>
                        <p>Short description</p>
                    </div>
                </a>

                <a class="bento-card bento-small" href="#transit-2" aria-label="Transit small card 2">
                    <div>
                        <h3>Transit — Round 2</h3>
                        <p>Short description</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
  </main>
  <script src="assets/js/homepage.js"></script>
</body>
</html>
