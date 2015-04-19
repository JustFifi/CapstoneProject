<?php
  //Prevent Direct Access
  if (count(get_included_files()) == 1) die("Error");

  $html = $build->mainHTML($errorsHTML, $checkAdmin, $adminLink, $userLink, $page[0]);
  $body = $q->getCurrentHomepageHtml();
  $html = preg_replace('/\[content\]/', file_get_contents($vars->pathToStyles.$vars->homepage), $html);
  $html = preg_replace('/\[homepage\]/', $body['body'], $html);

  print($html);
?>