<?php
defined('PROJECT_NAME') or define('PROJECT_NAME', 'test_bp_jamsostek');

$bulan = array(
  1 => "Januari",
  "Februari",
  "Maret",
  "April",
  "Mei",
  "Juni",
  "Juli",
  "Agustus",
  "September",
  "Oktober",
  "November",
  "Desember"
);

if (!function_exists("tgl_indo")) {
  function tgl_indo($tanggal) {
    global $bulan;
    $tanggal = substr($tanggal, 0, 10);
    $pisah = explode('-', $tanggal);
    return $pisah[2] . ' ' . $bulan[intval($pisah[1])] . ' ' . $pisah[0];
  }
}

if (!function_exists("base_url")) {
  function base_url($page = "")
  {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    return $protocol . "://" . $_SERVER['HTTP_HOST'] . "/" . PROJECT_NAME ."/" . ltrim($page, '/');
  }
}

if (!function_exists('validate_email')) {
  function validate_email($email) {
    // Bersihkan dari karakter ilegal
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      return false;
    }

    return true;
  }
}

if (!function_exists('xss_clean')) {
  function xss_clean($data, $remove = true) {
    // Hilangkan tag HTML & PHP
    $data = strip_tags($data);

    if ($remove) {
      // Hapus karakter spesial yang berpotensi disalahgunakan
      $data = preg_replace('/[^\w\s]/u', '', $data); // hanya huruf, angka, dan spasi
    } else {
      // Konversi karakter khusus menjadi entitas HTML
      $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    // Hapus spasi di awal/akhir
    $data = trim($data);

    return $data;
  }
}

if (!function_exists("_xss")) {
  function _xss($field, $method = "post", $remove = true) {
    if ($method == "post") {
      $string = $_POST[$field];
    } elseif ($method == "get") {
      $string = $_GET[$field];
    } else {
      $string = $field;
    }
    return xss_clean($string, $remove);
  }
}

if (!function_exists('view')) {
  function view($path, $data = [], $buffer=false)
  {
    $viewPath = __DIR__ . '/../views/' . rtrim(ltrim($path, '/'), '.php') . '.php';
    if (file_exists($viewPath)) {
      if ($buffer) ob_start();
      extract($data);
      include $viewPath;
      if ($buffer) return ob_get_clean();
    } else {
      echo "View '{$path}' not found!";
    }
  }
}