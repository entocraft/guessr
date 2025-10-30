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
    <div class="board">
      <div class="row">
        <section>
          <div id="question" class="section-title"></div>
          <div id="figure" class="figure" aria-live="polite"></div>
          <div id="options" class="options" role="radiogroup" aria-label="Choisissez la bonne constellation"></div>
          <div class="actions">
            <button id="nextBtn" class="next-btn" disabled>Question suivante →</button>
            <a id="endbutton" class="end-button" href="./api/result.php" hidden>Fin du quizz →</a>
          </div>
        </section>
        <aside id="right-panel" class="sidebar" aria-live="polite">
          <div class="sidebar-title"></div>
          <div class="meta">Sélectionnez une réponse puis validez.</div>
        </aside>
  </main>
  <script src="assets/js/homepage.js"></script>
</body>
</html>
