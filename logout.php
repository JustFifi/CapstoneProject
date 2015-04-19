<?php

  //Prevent Direct Access
  if (count(get_included_files()) == 1) die("Error");

  session_destroy();
  session_start();
  $_SESSION['trtv'] = '';

  header('Location: '.$vars->siteAddress.'/home');

  ?>