<?php
  //Prevent Direct Access
  if (count(get_included_files()) == 1) die("Error");

  $html = $build->mainHTML($errorsHTML, $checkAdmin, $adminLink, $userLink, $page[0]);

  if ($page[1] != $null || !empty($page[1])) {
    $safe_pages2 = array("about", "cookie-policy", "frequently-asked-questions");

    if(in_array($page[1], $safe_pages2))
    {
      switch ( $page[1] ) {
        case "about":
          $x = file_get_contents($vars->pathToPages.$vars->pageAboutUs);
          $html = preg_replace('/\[content\]/', $x, $html);
          break;
        case "cookie-policy":
          $x = file_get_contents($vars->pathToPages.$vars->pageCookiePolicy);
          $html = preg_replace('/\[content\]/', $x, $html);
          break;
        case "frequently-asked-questions":
          $x = file_get_contents($vars->pathToPages.$vars->pageFAQ);
          $html = preg_replace('/\[content\]/', $x, $html);
          break;
      }
    }
    else {
exit();
    }

  }

  print($html);