<?php
/* ----------------------------------------------
- Author: Rick Anderson
- Revision Date: 29/4/2015
-
-
- Filename: blog.php
- Description: Handles all blog views, security, and pagination for blogs.
---------------------------------------------- */

  //Prevent Direct Access
  if (count(get_included_files()) == 1) die("Error");

  if (isset($page[1]) && (is_numeric($page[1]) || ($page[1] == "page" && is_numeric($page[2])))) { $s = $page[1]; }
  else { $s = "dashboard"; }

  $html = $build->mainHTML($errorsHTML, $checkAdmin, $adminLink, $userLink, $page[0]);
  $html = preg_replace('/\[content\]/', file_get_contents($vars->pathToStyles.$vars->blogHomepage), $html);

  $limit = 10;
  $totalBlogs = $q->blogsTotalNoLimit();

  switch ($s) {
    case "dashboard": {
      // Main Blog page /blog
      $startAt = 0;
      $blogs = $q->blogsGet10($startAt, $limit);
      $replace = '';


      if ($blogs) {
        foreach ($blogs as $b) {
          // echo "<pre>".var_export($b, ture)."</pre>";
            $tmp = $build->blogHome($b, 'mini');

            $replace .= $tmp;
        }

        if ($totalBlogs['Total'] > $limit) {
          $replace .= '<a href="'.$vars->siteAddress.'/blog/page/2">Next 10 blogs</a>';
        }

        $html = preg_replace('/\[blog\]/', $replace, $html);
      }
      break;
    }
    case "page": {
      // Page Navigation for short blog posts
      $startAt = $limit * ($page[2] - 1);
      $blogs = $q->blogsGet10($startAt, $limit);
      $replace = '';


      if ($blogs) {
        foreach ($blogs as $b) {
          // echo "<pre>".var_export($b, ture)."</pre>";
            $tmp = $build->blogHome($b, 'mini');

            $replace .= $tmp;
        }

        $prevPage = $page[2] - 1;

        if ($prevPage != 0 || $prevPage >= 1) {
          $replace .= '<a href="'.$vars->siteAddress.'/blog/page/'.$prevPage.'">Previous 10 blogs</a>';
        }

        if (($totalBlogs['Total'] - $startAt) > $limit) {
          $nextPage = $page[2] + 1;
          $replace .= '<a href="'.$vars->siteAddress.'/blog/page/'.$nextPage.'">Next 10 blogs</a>';
        }

        $html = preg_replace('/\[blog\]/', $replace, $html);
      }


      break;

    }
    default: {
      // Individual blogs /blog/132 (blog id)
      $blog = $q->blogGetSingle($s);
      $single = file_get_contents($vars->pathToStyles.$vars->blogShort);

      if ($blog) {
        $replace = $build->blogHome($blog, 'page');
      } else {
        $_SESSION['trtv']['error'][] = $m->notFound;
        header('Location: '.$vars->siteAddress.'/blog');
      }

      // echo "<pre>".var_export($blog, true)."</pre>";
      $html = preg_replace('/\[blog\]/', $replace, $html);
      break;
    }
  }

  $html = preg_replace('/\[blog\]/', '', $html);
  print($html);