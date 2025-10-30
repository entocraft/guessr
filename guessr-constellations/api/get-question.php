<?php
header('Content-Type: application/json; charset=utf-8');
$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
// fichier json
$path = __DIR__ . '/questions.json';
// vérifie si le fichier existe et est lisible
if(!file_exists($path)){
  echo json_encode(['success'=>false,'error'=>'questions.json manquant']); exit;
}
// Décodage du JSON
$data = json_decode(file_get_contents($path), true);
// Recherche de la question
$index = array_search($id, array_column($data, 'id'));
if($index === false){ $index = 0; }
$q = $data[$index];
// Détermine la question suivante
$next = ($index + 1) < count($data) ? $data[$index+1]['id'] : null;
// On ne renvoie pas la valeur correcte ici.
$out = [
  'success' => true,
  'id' => $q['id'],
  'name' => $q['name'],
  'image' => $q['image'],
  'options' => $q['options'],
  'next_id' => $next
];
echo json_encode($out, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
