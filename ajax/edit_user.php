<?php
$time_start = microtime(true);

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../helpers/global.php');

//Request Input
$id = _xss("id", "post");

//Ambil Data User
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

if ($data) {
  $title = "Ubah User";
  $body = view('edit_user', $data, true);
  $footer = false;
} else {
  $title = "Error";
  $body = "<h3 class='text-center text-danger my-3'>User Tidak Ditemukan</h3>";
  $footer = true;
}

$time_end = microtime(true);

header('Content-Type: application/json');
echo json_encode([
  'code' => 200,
  'data' => [
    'title' => $title,
    'body' => $body,
    'footer' => $footer,
  ],
  'speed' => round(($time_end - $time_start), 4),
]);