<?php
/* ----------------------------------------------
- Author: Rick Anderson
- Revision Date: 30/4/2015
-
-
- Filename: search.php
- Description: Handles the searching of reviews. Basic for now, plan to add advanced search soon.
---------------------------------------------- */
  //Prevent Direct Access
  if (count(get_included_files()) == 1) die("Error");

  $html = $build->mainHTML($errorsHTML, $checkAdmin, $adminLink, $userLink, $page[0]);
  $html = preg_replace('/\[content\]/', file_get_contents($vars->pathToStyles.$vars->search), $html);

  $safe_search_pages = array("dashboard", "twitch-user", "reviews", "included");

  $p = (isset($_GET['setting']) && !empty($_GET['setting']) && $_GET['setting'] == 'included' ? $p = $_GET['setting'] : (isset($page[1]) && !empty($page[1]) && in_array($page[1], $safe_search_pages) ? $page[1] : 'dashboard'));

  switch ($p) {
    case "dashboard": {

      break;
    }
    case "twitch-user": {
      $user = (isset($page[2]) && !empty($page[2]) ? $page[2] : exit());
      $verify = $twitchtv->validate_stream($user);
      echo json_encode($verify);
      break;
    }
    case "reviews": {
      $term = (isset($page[2]) && !empty($page[2]) ? $page[2] : exit());
      $reviewTPL = file_get_contents($vars->pathToStyles.$vars->reviewShort);
      $searchTarget = $q->searchReviewsFor($term);
      $searchLike = $q->searchLike($term);

      if (!empty($searchTarget)) {

        $reviewArrConverted = '';
        foreach ($searchTarget as $t) {

        $tmp = $vars->reviewTblTemplate;

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

        $html = preg_replace('/\[reviewSmall\]/', $reviewTPL, $html);
        $html = preg_replace('/\[shortReview\]/', $reviewArrConverted, $html);

      }

      if (empty($searchTarget) && !empty($searchLike)) {
        $insert = '<p id="suggestions">Did you mean: ';

        foreach ($searchLike as $s) {
          $insert .= '<a href="'.$vars->siteAddress.'/search/reviews/'.$s['rev_target'].'">'.$s['rev_target'].'</a>';
        }

        $insert .= '</p>';

        $html = preg_replace('/\[didYouMean\]/', $insert, $html);
      }

      $html = preg_replace('/\[didYouMean\]/', '', $html);
      $html = (empty($searchTarget) && empty($searchLike) ? preg_replace('/\[reviewSmall\]/', '<p class="failure">No results found. Please try again.</p>', $html) : preg_replace('/\[reviewSmall\]/', '', $html));
      $html = preg_replace('/\[pageTitle\]/', 'Search Results', $html);
      print($html);
      break;
    }
  }