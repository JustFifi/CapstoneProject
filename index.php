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
  require("model/lessc.inc.php");

  $twitchtv = new TwitchTV;
  $html = new HTML;
  $vars = new Variables;
  $q = new DatabaseQueries;
  $build = new basicProtocols;
  $m = new Messages;
  $less = new lessc;

  $less->compileFile($vars->lessInput, $vars->lessOutput);

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
  $errors = array();
  $eTemplate = ( (isset($_SESSION['trtv']['error']) && !empty($_SESSION['trtv']['error'])) || (isset($_SESSION['trtv']['success']) && !empty($_SESSION['trtv']['success'])) ? '<p class="[FS]">[eMsg]</p>' : '' );
  $eType = (isset($_SESSION['trtv']['success']) && !empty($_SESSION['trtv']['success']) ? 'success' : 'failure');

  if ($eTemplate) {
    $errors = ( isset($_SESSION['trtv']['error']) && !empty($_SESSION['trtv']['error']) ? $m->errorMessages($_SESSION['trtv']['error']) : ( isset($_SESSION['trtv']['success']) && !empty($_SESSION['trtv']['success']) ? $m->errorMessages($_SESSION['trtv']['success']) : '' ) );

    if (!empty($errors) && isset($errors)) {
      $eList = '';
      $eWrap = preg_replace('/\[FS\]/', $eType, $eTemplate);
      unset($_SESSION['trtv']['error'], $_SESSION['trtv']['tlogout'], $_SESSION['trtv']['success']);
      $errorsHTML = preg_replace('/\[eMsg\]/', $errors, $eWrap);
    }
    else {
      $errorsHTML = $errors;
    }
  }




  // if (isset($_SESSION['trtv']['error']) && !empty($_SESSION['trtv']['error']))
  // {
  //   $tl = (isset($_SESSION['trtv']['tlogout']) && !empty($_SESSION['trtv']['tlogout']) ? $_SESSION['trtv']['tlogout'][0] : '');
  //   $errorsHTML = '<p class="failure">';
  //   foreach ($_SESSION['trtv']['error'] as $e)
  //   {
  //     $errorsHTML .= $e;
  //   }
  //   $errorsHTML .= '</p>'.$tl;
  //   $_SESSION['trtv']['error'] = array();
  //   if ($tl != $null || !empty($tl)) { $_SESSION['trtv']['tlogout'] = ''; }
  // }

  // // CHECKS FOR SUCCESS WITHIN THE SESSION
  // if (isset($_SESSION['trtv']['success']) && !empty($_SESSION['trtv']['success']))
  // {
  //   $errorsHTML = '<p class="success">';
  //   foreach ($_SESSION['trtv']['success'] as $e)
  //   {
  //     $errorsHTML .= $e;
  //   }
  //   $errorsHTML .= '</p>';
  //   $_SESSION['trtv']['error'] = array();
  //   if ($tl != $null || !empty($tl)) { $_SESSION['trtv']['tlogout'] = ''; }
  // }




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
  // $request  = str_replace("/twitchreviews/", "", $_SERVER['REQUEST_URI']);
  $request = substr($_SERVER['REQUEST_URI'], 1);

  #split the path by '/'
  $page     = preg_split("/\//", $request);

  #keeps users from requesting any file they want
  $safe_pages = array("auth", "logout", "home", "admin", "blog", "reviews", "contact", "page", "search", "test");

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
    $_SESSION['trtv']['error'][] = $m->notFound;
    header('Location: '.$vars->siteAddress.'/home');
  }
    // echo "<pre>".var_export($_SESSION['trtv'], true)."</pre>";
    // echo "<pre>".var_export($errorType, true)."</pre>";
    // echo "<pre>".var_export($eTemplate, true)."</pre>";
?>