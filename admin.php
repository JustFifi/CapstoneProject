<?php
	//Admin Section Code
	/* Debug Code	print_r($userInfo); */
	if (!empty($checkAdmin) && isset($checkAdmin)) {
	  if ($checkAdmin['Name'] == $userInfo['display_name'] && $checkAdmin['Value'] >= 9000)
	  {
		  //public function mainHTML($errorsHTML, $checkAdmin, $adminLink, $userLink)
		  $html = $build->mainHTML($errorsHTML, $checkAdmin, $adminLink, $userLink, $page[0]);
      if (!$page[1] || $page[1] == 'dashboard') {
        $grabThis = $build->admin('dashboard');
        $html = preg_replace('/\[content\]/', $grabThis, $html);

        //Dashboard Elements for JQ

      }
      elseif ($page[1] == 'newsletter') {
        $html = preg_replace('/\[content\]/', $build->admin('newsletter'), $html);
      }
      elseif ($page[1] == 'send-newsletter') {
        if ($_POST['post_body']) {
          $i = 0;
          $users = $q->getAllUsersEmails();
          $sentBy = $_SESSION['trtv']['member_id'];
          $message['body'] = $_POST['post_body'];
          $message['subject'] = date("F") . " Newsletter";

          $list = array();

          while ($i < count($users)) {
            $list[$users[$i]['email']] = $users[$i]['name'];
            $i++;
          }

          foreach ($list as $e => $n) {
            $template = file_get_contents($vars->pathToStyles.$vars->newsletterTemplate);
            $template = preg_replace('/\[siteAddress\]/', $vars->siteAddress, $template);
            $template = preg_replace('/\[month\]/', date("F"), $template);

            $recipient = array(
            					"email" => $e,
            					"name" => $n);
            //function send_mail($template, $sender, $recipient, $message)
            $vars->send_mail($template, $recipient, $message);
          }

        	if ($q->addNewsletter($sentBy, $message['body'], time())) {
          	$html = preg_replace('/\[content\]/', $build->admin('newsletter-sent'), $html);
          }
        }
        else {
            $html = preg_replace('/\[content\]/', $build->admin('newsletter-failed'), $html);
        }
      }
      elseif ($page[1] == 'blog') {
        if ($page[2]) { $p = $page[2]; }
        else { $p = "dashboard"; }

                  // var_dump($_POST);
        switch ($p) {
          case "dashboard": {
            $html = preg_replace('/\[content\]/', $build->admin('blog-dashboard'), $html);
            break;
          } // END CASE DASHBOARD/BLOG
          case "new": {
            if ($page[3]) { $p2 = $page[3]; }
            else { $p2 = "dashboard"; }

            switch ($p2) {
              case "dashboard": {
                $html = preg_replace('/\[content\]/', $build->admin('blog-new'), $html);
                break;
              } // END CASE DASHBOARD/NEW
              case "add": {
                if ($_POST['post_body'] && $_POST['post_title']) {
                  $sendThis = htmlspecialchars($_POST['post_body']);
                  $q->addBlogPost($_POST['post_title'], $_POST['post_body'], $_SESSION['trtv']['member_id'], time());
                  $html = preg_replace('/\[content\]/', $build->admin('blog-add'), $html);
                }
                else {
                  $html = preg_replace('/\[content\]/', $build->admin('blog-f'), $html);
                }
                break;
              } //END CASE ADD/NEW
              default: {
                header('Location: ../../home');
                break;
              }
            } // END SWITCH INSIDE CASE NEW/BLOG
              break;
          } // END CASE NEW/BLOG
          case "edit": {
            if ($page[4]) { $p2 = $page[4]; }
            else { $p2 = ''; }

            switch ($p2) {
              case "update": {
                if ($_POST) {
                  $q->updateBlogByID($_POST['blog_id'], $_POST['post_title'], $_POST['post_body']);
                  $html = preg_replace('/\[content\]/', $build->admin('blog-update'), $html);
                }
                else {
                  $html = preg_replace('/\[content\]/', $build->admin('blog-f'), $html);
                }
                break;
              }
              default: {
                $blog = $q->getBlogByID($page[3]);

                if ($blog) {

                  $html = preg_replace('/\[content\]/', $build->admin('blog-edit'), $html);

                  $html = preg_replace('/\[blogTitle\]/', $blog['new_title'], $html);
                  $html = preg_replace('/\[editPost\]/', $blog['new_body'], $html);
                  $html = preg_replace('/\[blogID\]/', $blog['new_id'], $html);
                }
                break;
              } // END CASE DEFAULT/EDIT
            }
            break;
          } // END CASE EDIT/BLOG
          case "delete": {
            $q->deleteBlogByID($page[3]);
            $html = preg_replace('/\[content\]/', $build->admin('blog-delete'), $html);
            break;
          } // END CASE DELETE/BLOG
          default: {
              $_SESSION['trtv']['error'][] = "That section in the administrator area does not exist.";
              header('Location: http://justfifi.com/twitchreviews/home');
            break;
          }
        } // END SWITCH INSIDE BLOG
      }
      elseif ($page[1] == 'users') {
        $html = preg_replace('/\[content\]/', $build->admin('users'), $html);

      }
      elseif ($page[1] == 'homepage') {
        if ($page[2]) { $p = $page[2]; }
        else { $p = "dashboard"; }

        switch ($p) {
          case "update": {
            if ($_POST['post_body'] && $q->updateHomepage($_POST['post_body'], $_SESSION['trtv']['member_id'])) {
              $html = preg_replace('/\[content\]/', $build->admin('homepage-update-s'), $html);
            }
            else {
              $html = preg_replace('/\[content\]/', $build->admin('homepage-update-f'), $html);
            }
          }
          case "dashboard": {
            $html = preg_replace('/\[content\]/', $build->admin('homepage-edit'), $html);
          }
        }
      }
      else {
        $_SESSION['trtv']['error'][] = "That section in the administrator area does not exist.";
        header('Location: http://justfifi.com/twitchreviews/home');
      }

      $html = preg_replace('/\[listBlogs\]/', '', $html);
      $html = preg_replace('/\[editPost\]/', '', $html);

      print($html);
	  }
    else {
      $_SESSION['trtv']['error'][] = "That section in the administrator area does not exist.";
      header('Location: http://justfifi.com/twitchreviews/home');
	  }
	}
	else
	{
		//return to home page with error
		$_SESSION['trtv']['error'][] = "Un-Authorized Access. If this is an error please contact the site administrator.";
    returnHome:
		header('Location: http://justfifi.com/twitchreviews/home');
	}



?>