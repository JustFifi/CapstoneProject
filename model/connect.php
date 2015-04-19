<?php
    $dsn = 'mysql:host=;dbname=';
    $username = '';
    $password = '';

  try {
    $db = new PDO($dsn, $username, $password);
  } catch (PDOException $e) {
    $error_message = $e->getMessage();
    include('../errors/database_error.php');
    exit();
  }
?>