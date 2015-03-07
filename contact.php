<?php
  $html = $build->mainHTML($errorsHTML, $checkAdmin, $adminLink, $userLink, $page[0]);
  $body = $q->getCurrentHomepageHtml();

  if (!$page[1] || empty($page[1])) {
    $p = "form";
  }
  else {
    $p = $page[1];
  }

  switch ($p) {
    case "form": {
      $form = file_get_contents($vars->pathToStyles.$vars->contactForm);
      $html = preg_replace('/\[content\]/', $form, $html);

      if (isset($_SESSION['trtv']['display_name']) && !empty($_SESSION['trtv']['display_name'])) {
        $u = $q->getUserAndEmail($_SESSION['trtv']['member_id']);

        $html = preg_replace('/\[userName\]/', "value=\"".$u['name']."\"", $html);
        $html = preg_replace('/\[userEmail\]/', "value=\"".$u['email']."\"", $html);

      }
      $html = preg_replace('/\[userName\]/', '', $html);
      $html = preg_replace('/\[userEmail\]/', '', $html);
      $html = preg_replace('/\[formTarget\]/', '[siteAddress]/contact/send', $html);

    break;
    }
    case "send": {
      if (isset($_POST['con_submit']) && !empty($_POST['con_submit'])) {
        if (isset($_POST['requiredField']) && empty($_POST['requiredField'])) {
          $template = file_get_contents($vars->pathToStyles.$vars->contactFormEmailTemplate);
          //send_mail($template, $sender, $recipient, $message)
          $message = array( "body" => htmlspecialchars($_POST['con_message']).'<p>Email: '.$_POST['emailAddress'].'</p><p>Name: '.$_POST['per_name'].'</p>',
                            "subject" => "Contact Form - ".date("F d Y")." @ ".date("G:i:s"));

          $to = array("email" => 'contactafifi@gmail.com',
                      "name" => 'Staff');

          $vars->send_mail($template, $to, $message);
          header('Location: '.$vars->siteAddress.'/contact/thank-you');
        }
        else {
          $_SESSION['trtv']['error'][] = "Something went wrong. It should be fixed soon!";
          header('Location: '.$vars->siteAddress.'/home');
        }
      }
      else {
        header('Location: '.$vars->siteAddress.'/contact');
      }
    break;
    }
    case "thank-you": {
      $html = preg_replace('/\[content\]/', '<p class="success">Thank you for taking the time and contacting us.<br>Someone will get back to you within 24-48 hours.</p>', $html);
    break;
    }
    default: {
      $_SESSION['trtv']['error'][] = "Something went wrong. It should be fixed soon!";
      header('Location: '.$vars->siteAddress.'/home');
    break;
    }
  }

  $html = preg_replace('/\[siteAddress\]/', $vars->siteAddress, $html);
  print($html);