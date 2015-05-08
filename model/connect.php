<?php
    $dsn = 'mysql:host=fifitrtv2015c.db.9778630.hostedresource.com;dbname=fifitrtv2015c';
    $username = 'fifitrtv2015c';
    $password = 'r3b3lY3ll!';

  try {
    $db = new PDO($dsn, $username, $password);
  } catch (PDOException $e) {
    $error_message = $e->getMessage();
    $_SESSION['trtv']['error'][] = "Unable to connect to the database.";
    header('Location: twitchreviews.tv/home');
    exit();
  }
?>