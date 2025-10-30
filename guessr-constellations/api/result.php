<?php
if (session_status() === PHP_SESSION_NONE){
  session_start();
}
$total = 0;
foreach ($_SESSION['question_score']['question'] as $score){
    if ($score == true){
        $total++;
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Guessr Constellation — Quiz</title>
  <link rel="stylesheet" href="../assets/css/homepage.css">
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
          <div id="resultat" class="section-title">Votre résultat : <?php echo $total."/".count($_SESSION['question_score']['question']); ?> </div>
          <div id="figure" class="figure" aria-live="polite"></div>
          <div class="actions">
            <a id="restartbutton" class="end-button" href="../index.php">Refaire le quizz</a>
            <a id="leavebutton" class="end-button" href="../../index.html">Autre guessr</a>
          </div>
        </section>
  </main>
  <script src="assets/js/homepage.js"></script>
</body>
</html>