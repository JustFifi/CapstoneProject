<?php
/* ----------------------------------------------
- Author: Rick Anderson
- Revision Date: 6/5/2015
-
-
- Filename: admin.php
- Description: Controller for the admin section of TR. Handles everything from security to the building of views.
---------------------------------------------- */
  //Prevent Direct Access
  if (count(get_included_files()) == 1) die("Error");

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
        // Newsletter dashboard view
        $html = preg_replace('/\[content\]/', $build->admin('newsletter'), $html);
      }
      elseif ($page[1] == 'send-newsletter') {
        // Sending newsletter to the masses
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
        // Admin Blog Controller
        if ($page[2]) { $p = $page[2]; }
        else { $p = "dashboard"; }

        switch ($p) {
          case "dashboard": {
            // Blog dashboard for admins
            $html = preg_replace('/\[content\]/', $build->admin('blog-dashboard'), $html);
            break;
          } // END CASE DASHBOARD/BLOG
          case "new": {
            // Add blog controller
            if ($page[3]) { $p2 = $page[3]; }
            else { $p2 = "dashboard"; }

            switch ($p2) {
              case "dashboard": {
                // Dashboard for addming a new blog post
                $html = preg_replace('/\[content\]/', $build->admin('blog-new'), $html);
                break;
              } // END CASE DASHBOARD/NEW
              case "add": {
                // controller to add new blog post
                if ($_POST['post_body'] && $_POST['post_title']) {
                  $sendThis = htmlspecialchars($_POST['post_body']);
                  $q->addBlogPost($_POST['post_title'], $_POST['post_body'], $_SESSION['trtv']['member_id'], time());
                  $html = preg_replace('/\[content\]/', $build->admin('blog-add'), $html);
                  $_SESSION['trtv']['adminsuccess'][] = $m->adminBlogAdd;
                }
                else {
                  $_SESSION['trtv']['adminerror'][] = $m->adminBlogAddFail;
                }
                header('Location: '.$vars->siteAddress.'/admin/blog');
                break;
              } //END CASE ADD/NEW
              default: {
                header('Location: '.$vars->siteAddress.'/admin');
                break;
              }
            } // END SWITCH INSIDE CASE NEW/BLOG
              break;
          } // END CASE NEW/BLOG
          case "edit": {
            // Controller for editing blog posts
            if ($page[4]) { $p2 = $page[4]; }
            else { $p2 = ''; }

            switch ($p2) {
              case "update": {
                // Controller to update the blog post
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
                // Default view
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
            // Blog deleting controller
            $q->deleteBlogByID($page[3]);
            $_SESSION['trtv']['adminsuccess'][] = $m->blogDeleted;
            header('Location: '.$vars->siteAddress.'/admin/blog');
            exit();
            break;
          } // END CASE DELETE/BLOG
          default: {
              // If someone decides to be adventurous this prevents them from breaking anything and sends the back to the admin dashboard
              $_SESSION['trtv']['adminerror'][] = $m->adminSectionDoesNotExist;
              header('Location: '.$vars->siteAddress.'/admin');
            break;
          }
        } // END SWITCH INSIDE BLOG
      }
      elseif ($page[1] == 'users') {
        // Admin Users view/controller
        if (isset($page[2])) { $p = $page[2]; }
        else { $p = "dashboard"; }

        switch ($p) {
          case "dashboard": {
            // Main view for all members in the admin section
            $html = preg_replace('/\[content\]/', $build->admin('users'), $html);
            break;
          } // END dashboard
          case "update": {
            // Controller for updating a members access
            if ($page[3]) {
              if (preg_match('/(?P<type>\w+):(?P<uID>\d+):(?P<tID>\d+)/', $page[3], $m)) {
                $check = $q->checkIfAdmin($m['tID']);
                switch ($m['type']) {

                  case "toggleadmin": {
                    // Enables or disables a Users moderation rights
                    if ($check['Value'] > 9000) {
                      $q->toggleUserAdmin($m['uID']);
                    }
                    header('Location: '.$vars->siteAddress.'/admin/users');
                    break;
                  }

                  case "toggledisable": {
                    // Enables or disables a Users access to the site under that Twitch account
                    $uCheck = $q->checkTargetUserLevel($m['uID']);
                    if ($check['Value'] >= 9000) {
                      if ($uCheck['Value'] >= 9000) { break; }
                      else {
                        $q->toggleUserStatus($m['uID']);
                      }
                    }
                    $_SESSION['trtv']['successerror'][] = $m->adminSectionDoesNotExist;
                    header('Location: '.$vars->siteAddress.'/admin/users');
                    break;
                  } // END toggle-disable case

                  exit();
                } // END switch
              } // END preg_match IF
              header('Location: '.$vars->siteAddress.'/admin/users');
              break;
            } // END page[3] IF
            else {
              $_SESSION['trtv']['adminerror'][] = $m->adminSectionDoesNotExist;
              header('Location: '.$vars->siteAddress.'/admin/users');
            }
          } // END update
        } // END page[2] switch


      }
      elseif ($page[1] == 'homepage') {
        // Admin homepage edit controller
        if ($page[2]) { $p = $page[2]; }
        else { $p = "dashboard"; }

        switch ($p) {
          case "update": {
            // controller to post data to the database
            if ($_POST['post_body'] && $q->updateHomepage($_POST['post_body'], $_SESSION['trtv']['member_id'])) {
              $_SESSION['trtv']['adminsuccess'][] = $m->adminHomepageUpdate;
              header('Location: '.$vars->siteAddress.'/admin/homepage');
              break;
            }
            else {
              $_SESSION['trtv']['adminerror'][] = $m->adminHomepageUpdateFail;
              header('Location: '.$vars->siteAddress.'/admin/homepage');
              break;
            }
          }
          case "dashboard": {
            // Main view for homepage edit in the admin dashboard
            $html = preg_replace('/\[content\]/', $build->admin('homepage-edit'), $html);
            break;
          }
        }
      }
      else {
        // If no pages were found in the admin section redirect back with an error message
        $_SESSION['trtv']['adminerror'][] = $m->adminSectionDoesNotExist;
        header('Location: '.$vars->siteAddress.'/admin');
      }

      $html = preg_replace('/\[listBlogs\]/', '', $html);
      $html = preg_replace('/\[editPost\]/', '', $html);

      print($html);
	  }
    else {
      $_SESSION['trtv']['adminerror'][] = $m->adminSectionDoesNotExist;
      header('Location: '.$vars->siteAddress.'/admin');
	  }
	}
	else
	{
		//return to home page with error
		$_SESSION['trtv']['adminerror'][] = $m->adminUnauthorized;
		header('Location: '.$vars->siteAddress.'/admin');
	}



?>