<?php
/* ----------------------------------------------
- Author: Rick Anderson
- Revision Date: 7/4/2015
-
-
- Filename: auth.php
- Description: Manages all the authentication from the Twitch response code with the help of the twitch php function library written by Elias Ranz-Schleifer.
---------------------------------------------- */
  //Prevent Direct Access
  if (count(get_included_files()) == 1) die("Error");

  if (isset($_GET['code']) && !empty($_GET['code'])) {

    $code = $_GET['code'];
  	$access_token = $twitchtv->get_access_token($code);
    $arrData = $twitchtv->authenticated_user($access_token);

	//var_dump($arrData);

    $check = $q->checkForUser($arrData['_id']);

    if ($check) {

      if ($check['usr_isDisabled'] == 1) {
        $_SESSION['trtv']['error'][] = "Your account has been disabled. If you feel this is an error please contact us using this form.";
        header('Location: '.$vars->siteAddress.'/contact');
        exit();
      }

      $arrSet = array();

      if ($arrData['display_name'] != $check['usr_name']) {
        $arrSet['usr_name'] = $arrData['display_name'];
      }

      if ($arrData['logo'] != $check['usr_logo']) {
        $arrSet['usr_logo'] = $arrData['logo'];
      }

      if ($arrData['email'] != $check['usr_email']) {
        $arrSet['usr_email'] = $arrData['email'];
      }

	  if (empty($arrData['logo']) || $arrData['logo'] == $null) {
        $arrData['logo'] = $arrSet['usr_logo'] = "http://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_300x300.png";
      }

      if (!empty($arrSet)) {
        //function updateUserInDatabase($userID,$arrValues)
        $q->updateUserInDatabase($check['usr_id'],$arrSet);
      }
    } else {
      if (empty($arrData['logo']) || $arrData['logo'] == $null) {
        $arrData['logo'] = "http://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_300x300.png";
      }
    }

    if ((!$arrData['email'] == $null || !empty($arrData['email'])) && (!isset($_SESSION['trtv']['error']) || empty($_SESSION['trtv']['error']) )) {
      //addUserToDatabase($name,$twitchID,$email,$logo,$timestamp,$disabled,$usrLvl)
      $q->addUserToDatabase($arrData['display_name'],$arrData['_id'],$arrData['email'],$arrData['logo'],time(),0,1);
      $_SESSION['trtv'] = array(
                          "display_name" => $arrData['display_name'],
                          "logo" => $arrData['logo'],
                          "member_id" => $q->getMemberID($arrData['_id']),
                          "twitch_id" => $arrData['_id']
                          );
    }
    else {
      // A verified email is required to use TwitchReviews.
      $_SESSION['trtv']['error'][] = 'If you still haven’t verified your email address, go to <a href="http://www.twitch.tv/settings/profile" target="_blank">Twitch Settings/Profile</a>&nbsp;and check the email field.';
      // $_SESSION['trtv']['error'][] = 'If you would like to try another account please log out of Twitch.tv using this: <a href="http://twitch.tv/logout" target="_self" class="button controls blue">Twitch.tv Logout</a>';
    }
    // var_dump($_SESSION['trtv']);

    header('Location: ../home');
    //echo "Welcome ".$arrData['display_name']." to TwitchReviews!";
  }
  else {
    die();
  }

?>