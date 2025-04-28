<?php
$time_start = microtime(true);

require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../helpers/global.php');

try {
  // Mulai transaksi
  $pdo->beginTransaction();

  // Query
  $stmt = $pdo->prepare(
    "UPDATE users SET
      nama = :nama, email = :email, alamat = :alamat, no_telp = :no_telp,
      tanggal_lahir = :tanggal_lahir, updated_at = NOW()
    WHERE id = :id"
  );

  // Cek Valid Email
  if (!validate_email($_POST['email'])) {
    $code = 0;
    $message = "Email Tidak Valid";
  } else {
    $code = 200;
    $message = "Ubah user berhasil";

    $stmt->execute([
      ':id' => _xss('id', 'post'),
      ':nama' => _xss('nama', 'post'),
      ':no_telp' => _xss('no_telp', 'post'),
      ':alamat' => _xss('alamat', 'post', false),
      ':email' => $_POST['email'],
      ':tanggal_lahir' => $_POST['tanggal_lahir'],
    ]);
  }

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