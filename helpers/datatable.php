<?php
require_once(__DIR__ . '/../config/database.php');

if (!function_exists('getTotalRecords')) {
  function getTotalRecords($table, $selectField = 'id') {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT($selectField) FROM $table");
    $result = $stmt->fetchColumn();
    $stmt->closeCursor();
    return $result;
  }
}

if (!function_exists('getFilteredRecords')) {
  function getFilteredRecords($table, $columns, $search, $selectField = 'id') {
    global $pdo;
    $where = buildWhereClause($columns, $search);
    $stmt = $pdo->prepare("SELECT COUNT($selectField) FROM $table $where");
    bindSearchParams($stmt, $columns, $search);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    $stmt->closeCursor();
    return $result;
  }
}

if (!function_exists('fetchData')) {
  function fetchData(
    $table, $columns, $search, $start, $length,
    $selectField = '*', $orderBy = 'created_at', $orderType = 'desc'
  ) {
    global $pdo;
    $where = buildWhereClause($columns, $search);
    if (is_array($selectField)) {
      $selectField = implode(', ', $selectField);
    }
    $sql = "SELECT $selectField FROM $table $where ORDER BY $orderBy $orderType LIMIT :start, :length";
    $stmt = $pdo->prepare($sql);

    bindSearchParams($stmt, $columns, $search);
    $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
    $stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    $stmt->closeCursor();
    return $result;
  }
}

if (!function_exists('buildWhereClause')) {
  function buildWhereClause($columns, $search) {
    if (!empty($search)) {
      $searchClauses = array_map(function ($col) {
        return "$col LIKE :search";
      }, $columns);
      return 'WHERE ' . implode(' OR ', $searchClauses);
    }
    return '';
  }
}

if (!function_exists('bindSearchParams')) {
  function bindSearchParams($stmt, $columns, $search) {
    if (!empty($search)) {
      $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    }
  }
}