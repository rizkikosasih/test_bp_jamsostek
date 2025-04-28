<?php
$time_start = microtime(true);

require_once(__DIR__ . '/../helpers/datatable.php');
require_once(__DIR__ . '/../helpers/global.php');

// Parameters from DataTables
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$search = $_POST['search']['value'];

// Table & Search Columns
$table = "users";
$searchColumns = ['nama', 'alamat', 'email'];
$selectField = ['id', 'nama', 'no_telp', 'alamat', 'email', 'tanggal_lahir', 'created_at'];

// Reformat Data
$data = [];
$allData = fetchData($table, $searchColumns, $search, $start, $length, $selectField);
foreach ($allData as $row) {
  $data[] = [
    'id' => $row->id,
    'nama' => $row->nama,
    'no_telp' => $row->no_telp,
    'alamat' => html_entity_decode($row->alamat, ENT_QUOTES),
    'email' => $row->email,
    'tanggal_lahir' => tgl_indo($row->tanggal_lahir),
  ];
}

$time_end = microtime(true);

header('Content-Type: application/json');
echo json_encode([
  'code' => 200,
  "draw" => intval($draw),
  "recordsTotal" => getTotalRecords($table),
  "recordsFiltered" => getFilteredRecords($table, $searchColumns, $search),
  "data" => $data,
  'speed' => round(($time_end - $time_start), 4),
]);