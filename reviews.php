<?php
  //Prevent Direct Access
  if (count(get_included_files()) == 1) die("Error");

  $html = $build->mainHTML($errorsHTML, $checkAdmin, $adminLink, $userLink, $page[0]);
  $html = preg_replace('/\[content\]/', file_get_contents($vars->pathToStyles.$vars->reviewHomepage), $html);

  $p = (isset($page[1]) && !empty($page[1]) ? $page[1] : "dashboard");

  switch ($p) {
  	case "dashboard": {
  		//Home view for the reviews page
      $html = preg_replace('/\[pageTitle\]/', 'Recent Reviews', $html);
      if (isset($_SESSION['trtv']['member_id']) && !empty($_SESSION['trtv']['member_id'])) {
        $html = preg_replace('/\[addReviewLink\]/', $build->makeLink('Add Review', $vars->siteAddress.'/reviews/new', '_self', 'button green right add-review'), $html );
      }

      $lastXreviews = $tmp = $q->getLastXreviews(15);

      $revTemp = file_get_contents($vars->pathToStyles.$vars->reviewShort);

      $reviewArrConverted = '';
      foreach ($tmp as $t) {

      $tmp = '
        <tr id="small-review-[reviewID]">
          <td><span>[targetIMG]</span><span>[targetName]</span></td>
          <td><span><a href="[articleURL]">[reviewTitle]</a></span><span>[reviewExcerpt]</span></td>
          <td class="stars">[ratingNumber]</td>
          <td><span>[reviewerIMG]</span><span>[reviewerName]</span></td>
          <td>[reviewDate]</td>
        </tr>';

        //http://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_300x300.png
        $tIMG = $q->getTargetImage($t['rev_target']);
        $targetImg = ($tIMG ? '<img src="'.$tIMG['usr_logo'].'" class="logo-image" alt="' : '<img src="http://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_300x300.png" class="logo-image" alt="');

        $articleURL = $vars->siteAddress.'/reviews/view/'.$t['rev_id'];

        $title = (strlen($t['rev_title']) > 30 ? substr($t['rev_title'],0,30).'...' : $t['rev_title']);
        $post = (strlen($t['rev_body']) > 40 ? substr($t['rev_body'],0,40).'...' : $t['rev_body']);

        $tt = preg_replace('/\[reviewID\]/', $t['rev_id'], $tmp);
        $tt = preg_replace('/\[articleURL\]/', $articleURL, $tt);
        $tt = preg_replace('/\[reviewTitle\]/', $title, $tt);
        $tt = preg_replace('/\[reviewerName\]/', $t['usr_name'], $tt);
        $tt = preg_replace('/\[reviewerIMG\]/', '<img src="'.$t['usr_logo'].'" class="logo-image" alt="'.$t['usr_name'].' Logo">', $tt);
        $tt = preg_replace('/\[targetName\]/', $t['rev_target'], $tt);
        $tt = preg_replace('/\[ratingNumber\]/', $t['rev_rating'], $tt);
        $tt = preg_replace('/\[reviewExcerpt\]/', $post, $tt);
        $tt = preg_replace('/\[targetIMG\]/', $targetImg.$t['rev_target'].' Logo">', $tt);
        $tt = preg_replace('/\[reviewDate\]/', date("M d, Y", $t['rev_date']), $tt);

        $reviewArrConverted .= $tt;
      }

      $reviewArrConverted = (empty($reviewArrConverted) ? "No reviews are present, be the first to submit a review" : $reviewArrConverted);
      $tmp = preg_replace('/\[shortReview\]/', $reviewArrConverted, $revTemp);
      $html = preg_replace('/\[reviews\]/', $tmp, $html);
      break;
  	}

  	case "view": {
  		//Viewing each review separately

       $p2 = (isset($page[2]) && !empty($page[2]) && is_numeric($page[2]) ? $page[2] : "exit");
       $rev = $q->getReviewData($p2);

       $p2 = (!$rev ? "exit" : $p2);
       switch($p2) {
        case "exit": {
          // Invalid pages return to reviews index
          $_SESSION['trtv']['error'][] = $m->notFound;
          header('Location: '.$vars->siteAddress.'/reviews');
          break;
        }
        default: {
          // The actual view to see the entire review

          // Var Dump to see if data is correct.
          // echo '<pre>'.var_export($rev, true).'</pre>';

          $rTemplate = file_get_contents($vars->pathToStyles.$vars->reviewLong);
          $tTemplate = file_get_contents($vars->pathToStyles.$vars->reviewSimilarTarget);
          $aTemplate = file_get_contents($vars->pathToStyles.$vars->reviewOthersByAuthor);

          $qtOther = $q->CheckForReviewsByTarget($rev['rev_target'], $rev['rev_id']);
          $qaOther = $q->CheckForReviewsByAuthor($rev['rev_usr_id'], $rev['rev_id']);

          $ratingAverage = $q->targetAverageRating($rev['rev_target']);

          $html = preg_replace('/\[pageTitle\]/', 'Review for '.$rev['rev_target'], $html);

          $tmp = (!empty($qtOther) ? preg_replace('/\[similarToTarget\]/', $tTemplate, $rTemplate) : preg_replace('/\[similarToTarget\]/', '', $rTemplate));
            if (!empty($qtOther)) {
              $tt = '';
              foreach ($qtOther AS $t) {
                $tt .= '<li><a href="'.$vars->siteAddress.'/reviews/view/'.$t['rev_id'].'">'.$t['rev_title'].'</a> - '.$t['usr_name'].'</li>';
              }

              $tmp = preg_replace('/\[list\]/', $tt, $tmp);
            }

          $tmp = (!empty($qaOther) ? preg_replace('/\[otherReviewsByAuthor\]/', $aTemplate, $tmp) : preg_replace('/\[otherReviewsByAuthor\]/', '', $tmp));
            if (!empty($qaOther)) {
              $tt = '';
              foreach ($qaOther AS $t) {
                $tt .= '<li><a href="'.$vars->siteAddress.'/reviews/view/'.$t['rev_id'].'">'.$t['rev_title'].'</a></li>';
              }

              $tmp = preg_replace('/\[list\]/', $tt, $tmp);
            }

          $tmp = preg_replace('/\[reviewTitle\]/', $rev['rev_title'], $tmp);

          if (!empty($checkAdmin) && isset($checkAdmin)) {
            if ($checkAdmin['Name'] == $userInfo['display_name'] && $checkAdmin['Value'] >= 9000)
            {
              $tmp = preg_replace('/\[adminDelete\]/', '<a href="'.$vars->siteAddress.'/reviews/delete/'.$rev['rev_id'].'" class="button controls red">Delete Review</a>', $tmp);
            } else {
              $tmp = preg_replace('/\[adminDelete\]/', '', $tmp);
            }
          } else {
            $tmp = preg_replace('/\[adminDelete\]/', '', $tmp);
          }
          $tmp = preg_replace('/\[reviewRating\]/', $rev['rev_rating'], $tmp);
          $tmp = preg_replace('/\[reviewAuthor\]/', '<span class="twitch_username">'.$rev['usr_name'].'</span>', $tmp);
          $tmp = preg_replace('/\[reviewTarget\]/', '<span class="twitch_username">'.$rev['rev_target'].'</span>', $tmp);
          $tmp = preg_replace('/\[reviewDate\]/', ' '.date("M d, Y", $rev['rev_date']), $tmp);
          $tmp = preg_replace('/\[overallRating\]/', $ratingAverage['avg'], $tmp);
          $tmp = preg_replace('/\[reviewPost\]/', $rev['rev_body'], $tmp);
          $tmp = preg_replace('/\[reviewID\]/', $rev['rev_id'], $tmp);

          $html = preg_replace('/\[reviews\]/', $tmp, $html);
        }
       }

      break;
  	}

    case "delete": {
      if (!isset($page[2]) && empty($page[2])) {
        $_SESSION['trtv']['error'][] = $m->notFound;
        header('Location: '.$vars->siteAddress.'/reviews');
        exit();
      }

      if (!empty($checkAdmin) && isset($checkAdmin)) {
        if ($checkAdmin['Name'] == $userInfo['display_name'] && $checkAdmin['Value'] >= 9000)
        {
          //querry to delete review and redirect back to reviews home
          $q->deleteReview($page[2]);
          $_SESSION['trtv']['success'][] = $m->deleteReview;
          header('Location: '.$vars->siteAddress.'/reviews');
          exit();
        }
      } else {
        //Redirect for non-admins, preventing unauthorized access
        $_SESSION['trtv']['error'][] = $m->adminUnauthorized;
        header('Location: '.$vars->siteAddress.'/reviews/view/'.$page[2]);
        exit();
      }
      break;
    }

    case "new": {
      // View for adding a Review
      if (!isset($_SESSION['trtv']['member_id']) && empty($_SESSION['trtv']['member_id'])) {
        $_SESSION['trtv']['error'][] = $m->unauthorizedAccess;
        header('Location: '.$vars->siteAddress.'/reviews');
        exit();
      }

      $form = file_get_contents($vars->pathToStyles.$vars->reviewForm);

      $html = preg_replace('/\[pageTitle\]/', 'Adding Review', $html);
      $html = preg_replace('/\[reviews\]/', $form, $html);
      $html = preg_replace('/\[reviewerName\]/', $_SESSION['trtv']['display_name'], $html);
      break;
    }

    case "add": {
      // Don't do anything if user isn't logged in.
      if (!isset($_SESSION['trtv']) && empty($_SESSION['trtv'])) {
        $_SESSION['trtv']['error'][] = $m->unauthorizedAccess;
        header('Location: '.$vars->siteAddress.'/home');
        exit();
      }

      // Backbone for mySQL additions
      if ($_POST['submit'] && $_POST['review_body'] && $_POST['review_title'] && $_POST['review_target']) {
        // insert-sql calls here
        $q->addNewReview($_SESSION['trtv']['member_id'], $_POST['review_title'], $_POST['review_target'], $_POST['review_body'], $_POST['rating']);

        $_SESSION['trtv']['success'][] = $m->reviewAddNew;
        $newPostID = $q->reviewNewestIDreturn($_SESSION['trtv']['member_id']);
        header('Location: '.$vars->siteAddress.'/reviews/view/'.$newPostID['rev_id']);
      }
      else {
        $_SESSION['trtv']['error'][] = $m->generalError;
        header('Location: '.$vars->siteAddress.'/reviews/new');
      }
      break;
    }

    case "vote": {
      //AJAX Call for upvoting and downvoting
      $type = (isset($page[2]) && !empty($page[2]) && ($page[2] == "up" || $page[2] == "down") ? $page[2] : '');

      switch ($type) {
        case "up": {
          
          break;
        }
        case "down": {

          break;
        }
        default: {
          break;
        }
      } // END AJAX SWITCH FOR UP/DOWN VOTES

      break;
    }

  	default: {
  		//Return error back to reviews with error
  		$_SESSION['trtv']['error'][] = $m->notFound;
      header('Location: '.$vars->siteAddress.'/home');
      break;
  	}
  }

  $html = preg_replace('/\[addReviewLink\]/', '', $html);
  $html = preg_replace('/\[reviewPost\]/', '', $html);
  $html = preg_replace('/\[reviews\]/', '', $html);
  $html = preg_replace('/\[siteAddress\]/', $vars->siteAddress, $html);
  print($html);
?>