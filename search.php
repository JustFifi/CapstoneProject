<?php
  //Prevent Direct Access
  if (count(get_included_files()) == 1) die("Error");

  $html = $build->mainHTML($errorsHTML, $checkAdmin, $adminLink, $userLink, $page[0]);
  $html = preg_replace('/\[content\]/', file_get_contents($vars->pathToStyles.$vars->reviewHomepage), $html);

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
      $searchTarget = $q->searchReviewsFor($term);

      echo '<pre>'.var_export($searchTarget).'</pre><br><br>';

      if (empty($searchTarget)) {
        $searchLike = $q->searchLike($term);
        $insert = '<p class="';
      }
      break;
    }
  }