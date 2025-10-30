
Projet : Guessr - Constellations (PHP + JSON + AJAX)
===================================================
Dossier à déposer tel quel sur le serveur Web (Apache/PHP).
Point d'entrée : index.php

Structure
---------
- index.php                             -> page du quiz (wireframe-like)
- assets/css/homepage.css               -> styles
- assets/js/homepage.js                 -> logique front (fetch + AJAX)
- assets/img/constellations/{1..3}.svg  -> images exemples (placeholder, numérotées)
- api/questions.json                    -> base de questions
- api/get-question.php                  -> API : récupérer une question sans la réponse
- api/check-answer.php                  -> API : vérifier la réponse et renvoyer les infos + bonne réponse

Notes
-----
- Les images sont volontairement des SVG simplifiés avec un numéro (#id) pour éviter de donner la réponse visuellement.
- Ajoutez des questions en copiant la structure JSON (attention aux id, images et correct_value).
- Sécurité minimale (projet démo). Pour prod, ajouter rate limiting / CSRF, etc.
