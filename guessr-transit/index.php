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

        <!-- LEFT BIG CARD -->
        <a id="gtc" class="bento-left" href="./guessr.php" aria-label="Transit big card">
          <div class="content-wrapper">
            <div class="text-content">
              <h2>Transit City</h2>
              <p>Devinez la ville selon son système de transport</p>
            </div>
            <span class="play-button" aria-hidden="false">Jouer</span>
          </div>
        </a>

        <!-- RIGHT COLUMN WITH TWO SMALL CARDS -->
        <div class="bento-right">

          <a id="gtl" class="bento-card bento-small" href="#transit-1" aria-label="Transit small card 1">
            <div class="content-wrapper">
              <div class="text-content">
                <h3>Transit Line</h3>
                <p>Devinez la ligne de transport</p>
              </div>
              <span class="play-button">Jouer</span>
            </div>
          </a>

          <a id="gto" class="bento-card bento-small" href="#transit-2" aria-label="Transit small card 2">
            <div class="content-wrapper">
              <div class="text-content">
                <h3>Transit Operator</h3>
                <p>Devinez l'opérateur qui gère le réseau</p>
              </div>
              <span class="play-button">Jouer</span>
            </div>
          </a>

        </div>
      </div>
    </div>
  </main>
</body>
</html>