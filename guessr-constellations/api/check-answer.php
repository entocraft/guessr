<?php
if (session_status() === PHP_SESSION_NONE){
  session_start();
}

header('Content-Type: application/json; charset=utf-8');
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
$path = __DIR__ . '/questions.json';
if(!$id || $answer===''){ echo json_encode(['success'=>false,'error'=>'Paramètres manquants']); exit; }
if(!file_exists($path)){
  echo json_encode(['success'=>false,'error'=>'questions.json manquant']); exit;
}
$data = json_decode(file_get_contents($path), true);
$index = array_search($id, array_column($data, 'id'));
if($index === false){ echo json_encode(['success'=>false,'error'=>'Question inconnue']); exit; }
$q = $data[$index];
$is_correct = ($answer === $q['correct_value']);
$next = ($index + 1) < count($data) ? $data[$index+1]['id'] : null;
$_SESSION['question_score']['question'][$q['id']] = $is_correct; // noter le resultat 
echo json_encode([
  'success' => true,
  'id' => $q['id'],
  'is_correct' => $is_correct,
  'correct_value' => $q['correct_value'],
  'name' => $q['name'],
    'wiki' => $q['wiki'],
  'namer' => $q['namer'],
  'description' => $q['description'],
  'next_id' => $next
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
