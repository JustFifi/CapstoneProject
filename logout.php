<?php
/* ----------------------------------------------
- Author: Rick Anderson
- Revision Date: 7/4/2015
-
-
- Filename: logout.php
- Description: Simple destruction of all session data for logout. DOES NOT LOG YOU OUT OF TWITCH.TV
---------------------------------------------- */

  //Prevent Direct Access
  if (count(get_included_files()) == 1) die("Error");

  session_destroy();
  session_start();
  $_SESSION['trtv'] = '';

  header('Location: '.$vars->siteAddress.'/home');

  ?>