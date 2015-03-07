<?php
//Database Broswer Access
//https://p3nlmysqladm002.secureserver.net/grid55/197/index.php

  // Start session management with a persistent cookie
  $lifetime = 60 * 60 * 24 * 14;    // 2 weeks in seconds
  session_set_cookie_params($lifetime, '/');
  session_start();

  include('model/functions.lib.php');
  include('model/function.twitchtv.php');
  include_once('model/functions.sql.php');
  include('model/connect.php');

  $twitchtv = new TwitchTV;
  $html = new HTML;
  $vars = new Variables;
  $q = new DatabaseQueries;
  $build = new basicProtocols;

  /*
  - $userInfo
  - array(3) {
  -	  ["display_name"]=> string(9) "Just_Fifi"
  -	  ["logo"]=> string(99) "http://static-cdn.jtvnw.net/jtv_user_pictures/just_fifi-profile_image-0c95469b01468242-300x300.jpeg"
  -	  ["member_id"]=> string(1) "1"
  - }
  */

  // CHECKS SESSION FOR EXISTENCE
  if (isset($_SESSION['trtv']['display_name']) && !empty($_SESSION['trtv']['display_name'])) {
    if (!isset($userInfo) && empty($userInfo)) {
      $userInfo = $_SESSION['trtv'];
      $userLink = $html->getLogoutLink($userInfo);
      $checkAdmin = $q->checkIfAdmin($userInfo['twitch_id']);
    }
  } else {
      $userInfo = array();
      $userLink = $html->getLoginLink($twitchtv->authenticate());
      $checkAdmin = '';
  }

  // CHECKS FOR ERRORS WITHIN THE SESSION
  if (isset($_SESSION['trtv']['error']) && !empty($_SESSION['trtv']['error']))
  {
    $errorsHTML = '<div id="errors"><ul>';
    foreach ($_SESSION['trtv']['error'] as $e)
    {
      $errorsHTML .= '<li>'.$e.'</li>';
    }
    $errorsHTML .= '</ul></div>';
    $_SESSION['trtv']['error'] = array();
  }

  //CHECKS IF ADMIN
  if (isset($checkAdmin) && !empty($checkAdmin))
    {
      if ($checkAdmin['Name'] == $userInfo['display_name'] && $checkAdmin['Value'] >= 9000)
      {
        $adminLink = '<li><a href="'.$vars->siteAddress.'/admin">admin</a></li>';
      } else {
        $adminLink = '';
      }
  }

  #remove the directory path we don't want
  $request  = str_replace("/twitchreviews/", "", $_SERVER['REQUEST_URI']);

  #split the path by '/'
  $page     = preg_split("/\//", $request);

  #keeps users from requesting any file they want
  $safe_pages = array("auth", "logout", "home", "admin", "blog", "reviews", "contact", "page", "test");

  if(in_array($page[0], $safe_pages))
  {
    include($page[0].".php");
  }
  elseif (!$page[0])
  {
      include('home.php');
  }
  else
  {
    include("404.php");
  }
?>