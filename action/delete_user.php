<?php
$time_start = microtime(true);

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../helpers/global.php');

try {
  // Mulai transaksi
  $pdo->beginTransaction();

  // Query
  $stmt = $pdo->prepare("DELETE FROM users where id = :id");

  $code = 200;
  $message = "Hapus user berhasil";

  $stmt->execute([
    ':id' => _xss('id', 'post')
  ]);

  // Commit transaksi
  $pdo->commit();
} catch (PDOException $e) {
  // Rollback jika terjadi error
  if ($pdo->inTransaction()) {
      $pdo->rollBack();
  }

  $code = 0;
  $message = "Terjadi kesalahan: " . $e->getMessage();
}

$time_end = microtime(true);

header('Content-Type: application/json');
echo json_encode([
  'code' => $code,
  'message' => $message,
  'speed' => round(($time_end - $time_start), 4),
]);