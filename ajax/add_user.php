<?php
$time_start = microtime(true);

require_once(__DIR__ . '/../helpers/global.php');

$title = "Tambah User";
$body = view('add_user', [], true);
$footer = false;

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