<?php
    $dsn = 'mysql:host=DBHOST;dbname=DBNAME';
    $username = 'USERNAME';
    $password = 'PASSWORD!';

  try {
    $db = new PDO($dsn, $username, $password);
  } catch (PDOException $e) {
    $error_message = $e->getMessage();
    $_SESSION['trtv']['error'][] = "Unable to connect to the database.";
    header('Location: twitchreviews.tv/home');
    exit();
  }
?>