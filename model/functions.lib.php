<?php
class Variables
{
  public $siteCreator = "Rick Anderson";
  public $siteAddress = "//twitchreviews.tv";
  public $siteName = "TwitchReviews";
  public $noReplyEmail =  'TwitchReviews <no-reply@twitchreviews.tv>';
  public $pathToStyles = "style/";
  public $headerTemplate = "siteHeader.tpl.html";
  public $footerTemplate = "siteFooter.tpl.html";
  public $homepage = 'siteHomepage.tpl.html';
  public $textEditor = "editor.tpl.html";
  public $siteTemplate = "site.tpl.html";
  public $lessInput = 'style/css/LESS/input.less';
  public $lessOutput ='style/css/style.less.css';

  public $adminTemplate = "adminTemplate.tpl.html";
  public $adminDashboardBlog = "adminDashboardBlog.tpl.html";
  public $adminDashboard = "adminDashboardHome.tpl.html";
  public $adminLinks = "adminLinks.tpl.html";
  public $adminViewMembers = "adminViewMembers.tpl.html";
  public $adminNewsletter = "adminNewsletter.tpl.html";

  public $newsletterTemplate = "email/newsletter.tpl.html";
  public $contactFormEmailTemplate = "email/contactForm.tpl.html";
  public $contactForm = "contactForm.tpl.html";

  public $pathToPages = 'style/pages/';
  public $pageCookiePolicy = "cookie-policy.tpl.html";
  public $pageAboutUs = "about-us.tpl.html";
  public $pageFAQ = "faq.tpl.html";

  public $blogHomepage = "blogHome.tpl.html";
  public $blogShort = "blogTemplate.tpl.html";

  public $reviewHomepage = "reviewTemplate.tpl.html";
  public $reviewShort = "reviewSmall.tpl.html";
  public $reviewLong = "reviewLong.tpl.html";
  public $reviewForm = "reviewForm.tpl.html";
  public $reviewSimilarTarget = "reviewSimilarToTarget.tpl.html";
  public $reviewOthersByAuthor = "reviewOthersByAuthor.tpl.html";

  public $searchFormSimple = "searchSimple.tpl.html";


  public function send_mail($template, $recipient, $message) {
      $to = $recipient['email'];
      $name = $recipient['name'];

      $message['body'] = preg_replace('/\[emailMessage\]/', $message['body'], $template);

      // Always set content-type when sending HTML email
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= 'From: '.$this->noReplyEmail.'' . "\r\n";

      mail($to,$message['subject'],$message['body'],$headers);
  }

  function random_lipsum($amount = 1, $what = 'paras', $start = 0) {
    return simplexml_load_file("http://www.lipsum.com/feed/xml?amount=$amount&what=$what&start=$start")->lipsum;
  }
}

class HTML
{
  public function getLoginLink( $auth )
  {
    $var = '<a href="'.$auth.'">Login with Twitch</a>';
    return $var;
  }

  public function getLogoutLink( $var )
  {
    $var = '<a href="[siteAddress]/logout"><img src="'.$var['logo'].'" class="logo-image" alt="User Image">'.$var['display_name'].' Logout</a>';
    return $var;
  }

}

class Messages
{
  public $blogAdded = 'Blog was successfully added.';
  public $blogUpdated = 'Blog was successfully updated.';
  public $blogDeleted = 'Blog post was successfully deleted';
  public $blogLost = 'Something went wrong, blog was lost!';
  public $adminSectionDoesNotExist = 'That section in the administrator area does not exist.';
  public $adminHomepageUpdated = 'Homepage has been updated.';
  public $adminHomepageUpdateFail = 'Homepage has NOT been updated.';
  public $adminUnauthorized = 'Un-Authorized Access. If this is an error please contact the site administrator.';
  public $adminBlogAdd = 'Blog was successfully added.';
  public $adminBlogAddFail = 'Blog was not saved, something went wrong. Go back and try again.';
  public $contactThankYou = 'Thank you for taking the time and contacting us. Someone will get back to you within 24-48 hours.';
  public $generalError = 'Something went wrong. It should be fixed soon!';
  public $unauthorizedAccess = 'This section is reserved to the users of TwitchReviews. Please login to this site using your Twitch account.';
  public $notFound = 'The page you are looking for is not found on TwitchReviews. If you feel this is an error, please contact the site administrators using our <a href="./contact">contact form</a>.';
  public $reviewAddNew = "Thank you for your review. The review submitted is located below.";
  public $deleteReview = "Review has been removed";

  public function errorMessages($arr) {
    $tmp = '';
    foreach ($arr as $e) {
      $tmp .= '<span>'.$e.'</span>';
    }
    return $tmp;
  }
}

class basicProtocols
{
  public function mainHTML( $errorsHTML, $checkAdmin, $adminLink, $userLink, $currentPage )
  {
    $vars = new Variables;
    $html = file_get_contents( $vars->pathToStyles . $vars->siteTemplate );
    $header = file_get_contents( $vars->pathToStyles . $vars->headerTemplate );
    $footer = file_get_contents( $vars->pathToStyles . $vars->footerTemplate );

    if (isset($errorsHTML) && !empty($errorsHTML))
    {
      $pErrors = $errorsHTML;
    } else {
      $pErrors = '';
    }

    $header = preg_replace( '/\[adminLink\]/', $adminLink, $header );
    $header = preg_replace( '/\[userLink\]/', $userLink, $header );
    $header = preg_replace( '/\[siteAddress\]/', $vars->siteAddress, $header );

    $html = preg_replace( '/\[errors\]/', $pErrors, $html );
    $html = preg_replace( '/\[currentPage\]/', $currentPage, $html );
    $html = preg_replace( '/\[header\]/', $header, $html );
    $html = preg_replace( '/\[footer\]/', $footer, $html );
    $html = preg_replace( '/\[siteAddress\]/', $vars->siteAddress, $html );
    $html = preg_replace( '/\[currentYear\]/', date('Y'), $html );

    return $html;
  }

  public function makeLink($text, $link, $target, $classes)
  {
    $tmp = '<a href="'.$link.'" target="'.$target.'" class="'.$classes.'">'.$text.'</a>';
    return $tmp;
  }

  public function blogHome($ba, $type) {
    $vars = new Variables;
    $tmp = file_get_contents($vars->pathToStyles.$vars->blogShort);

    $cReading = ($type == 'mini' ? '<p><a href="'.$vars->siteAddress.'/blog/'.$ba['ID'].'" title="View '.ucwords($ba['Title']).'" class="button controls blue">Continue Reading</a></p>' : '');
    $title = ($type == 'mini' ? '<a href="'.$vars->siteAddress.'/blog/'.$ba['ID'].'" title="View '.ucwords($ba['Title']).'">'.ucwords($ba['Title']).'</a>' : ucwords($ba['Title']));
    $post = ($type == 'mini' ? substr($ba['Post'], 0, 500).' ...' : $ba['Post']);

    $tmp = preg_replace('/\[blogTitle\]/', $title, $tmp);
    $tmp = preg_replace('/\[blogAuthor\]/', '<span class="twitch_username">'.$ba['Author']."</span>", $tmp);
    $tmp = preg_replace('/\[blogDate\]/', date("M d, Y @ H:i:s", $ba['Date']), $tmp);
    $tmp = preg_replace('/\[blogPost\]/', $post, $tmp);
    $tmp = preg_replace('/\[blogID\]/', $ba['ID'], $tmp);
    $tmp = preg_replace('/\[continueReading\]/', $cReading, $tmp);

    return $tmp;
  }

  private function getErrors( $errors )
  {
    $msg = '';

    foreach ( $errors as $e )
    {
      $msg .= '<span>'.$e.'</span>';
    }

    return $msg;
  }

  public function admin( $page )
    {
      $vars = new Variables;
      $q = new DatabaseQueries;
      $m = new Messages;
      $html = file_get_contents($vars->pathToStyles.$vars->adminTemplate);
      $adminLinks = file_get_contents($vars->pathToStyles.$vars->adminLinks);

      $html = preg_replace('/\[adminLinks\]/', $adminLinks, $html);
      $html = preg_replace( '/\[pageHeader\]/', '<h1 id="page-header">PAGEHEADER</h1>', $html );

      if ( (isset($_SESSION['trtv']['adminsuccess']) && !empty($_SESSION['trtv']['adminsuccess'])) || (isset($_SESSION['trtv']['adminerror']) && !empty($_SESSION['trtv']['adminerror'])) ) {
        $container = (isset($_SESSION['trtv']['adminsuccess']) && !empty($_SESSION['trtv']['adminsuccess']) ? '<p class="success">'.$m->errorMessages($_SESSION['trtv']['adminsuccess']).'</p>' : '<p class="failure">'.$m->errorMessages($_SESSION['trtv']['adminerror']).'</p>' );

        unset($_SESSION['trtv']['adminsuccess'], $_SESSION['trtv']['adminerror']);
      }

      $html = (isset($container) && !empty($container) ? preg_replace('/\[adminErrors\]/', $container, $html) : preg_replace('/\[adminErrors\]/', '', $html));

      if ( $page == 'dashboard' )
        {

        }
      elseif ( $page == 'newsletter' )
        {
          $grabThis = file_get_contents($vars->pathToStyles.$vars->adminNewsletter);
          $editor = file_get_contents($vars->pathToStyles.$vars->textEditor);

          $html = preg_replace('/\[adminContent\]/', $grabThis, $html);
          $html = preg_replace('/\[editor\]/', $editor, $html);
          $html = preg_replace('/\[postTitle\]/', '', $html);
          $html = preg_replace('/\[formTarget\]/', $vars->siteAddress.'/admin/newsletter/send', $html);
        }
      elseif ( $page == 'newsletter-sent' )
        {
          $grabThis = file_get_contents($vars->pathToStyles.$vars->adminNewsletter);

          $html = preg_replace('/\[adminContent\]/', $grabThis, $html);
          $html = preg_replace('/\[editor\]/', '<p class="success">Newsletter was sent successfully.</p>', $html);
        }
      elseif ( $page == 'newsletter-failed' )
        {
          $grabThis = file_get_contents($vars->pathToStyles.$vars->adminNewsletter);

          $html = preg_replace('/\[adminContent\]/', $grabThis, $html);
          $html = preg_replace('/\[editor\]/', '<p class="failure">Newsletter is missing something. Please try again.</p>', $html);
        }
      elseif ( $page == 'blog-dashboard' )
        {
          $file = file_get_contents($vars->pathToStyles.$vars->adminDashboardBlog);

          $html = preg_replace('/\[adminContent\]/', $file, $html);

          $blogs = $q->adminGetListOfBlogs();
          if ($blogs) {
            $useThis = $listBlogs = '';
            $i = 0;
            foreach ($blogs as $blog) {
              $listTemplate = '<p class="blog-item"><span class="controls">[controls]</span><span class="title">[title]</span><span class="author">[author]</span></p>';
              $controlsTemplate = '<a href="[siteAddress]/admin/blog/edit/[blogID]" title="Edit Blog Post" class="button controls blue left-space">Edit</a><a href="[siteAddress]/admin/blog/delete/[blogID]" title="Delete Blog Post" class="button controls red left-space">Delete</a>';

              $listBlogs = preg_replace('/\[title\]/', $blog['title'], $listTemplate);
              $listBlogs = preg_replace('/\[author\]/', $blog['author'], $listBlogs);
              $listBlogs = preg_replace('/\[blogID\]/', $blog['ID'], $listBlogs);
              $listBlogs = preg_replace('/\[blogID\]/', $blog['ID'], $listBlogs);

              $controls = preg_replace('/\[blogID\]/', $blog['ID'], $controlsTemplate);

              $listBlogs = preg_replace('/\[controls\]/', $controls, $listBlogs);

              $useThis .= $listBlogs;
              $i++;
            }

            $html = preg_replace('/\[listBlogs\]/', $useThis, $html);
          }
        }
      elseif ( $page == 'blog-new' )
        {
          $editor = file_get_contents($vars->pathToStyles.$vars->textEditor);
          $element['title'] = '<input type="text" name="post_title" id="post-title" cols="60" placeholder="Enter a blog title..." required>';

          $html = preg_replace('/\[adminContent\]/', $editor, $html);
          $html = preg_replace('/\[postTitle\]/', $element['title'], $html);
          $html = preg_replace('/\[formTarget\]/', $vars->siteAddress.'/admin/blog/new/add', $html);
        }
      elseif ( $page == 'blog-edit' )
        {
          $editor = file_get_contents($vars->pathToStyles.$vars->textEditor);
          $element['title'] = '<input type="text" name="post_title" id="post-title" cols="60" placeholder="Enter a blog title..." value="[blogTitle]" required>';
          $element['blogID'] = '<input type="hidden" name="blog_id" value="[blogID]">';

          $html = preg_replace('/\[adminContent\]/', $editor, $html);
          $html = preg_replace('/\[postTitle\]/', $element['title'].$element['blogID'], $html);
          $html = preg_replace('/\[formTarget\]/', $vars->siteAddress.'/admin/blog/edit/[blogID]/update', $html);
        }
      elseif ( $page == 'users' )
        {
          $q = new DatabaseQueries;
          $tmp = '<div id="admin-members" class="page-content">';
          $users = $q->adminGetMembers();

          // echo '<pre>'.var_export($_SESSION['trtv'], true).'</pre>';

          $aCheck = $q->checkIfAdmin($_SESSION['trtv']['twitch_id']);

          foreach ($users as $u) {
            $uID = $u['usr_id']; //
            $name = $u['usr_name']; //
            $email = $u['usr_email'];
            $logo = $u['usr_logo']; //
            $rDate = $u['usr_registeredDate']; //
            $iDis = $u['usr_isDisabled']; //
            $uLvl = $u['lvl_name']; //
            $lvlV = $u['lvl_value']; //
            $aID = ($lvlV >= 9000 ? 1 : 0);

            $hAd = ($lvlV <= 9000 && $aCheck['Value'] == 9999 && $lvlV != 9999 && $iDis < 1 ? '' : ' hideElement');
            $hDi = ($lvlV == 9999 || $lvlV == $aCheck['Value'] ? ' hideElement' : '');

            $tmp .= '<div id="user-'.$uID.'" class="user disabled-'.$iDis.'">';
            $tmp .= '<p class="admin-controls">';
            $tmp .= '<span><a href="'.$vars->siteAddress.'/admin/users/update/toggledisable:'.$uID.':'.$_SESSION['trtv']['twitch_id'].'" class="button controls red usr-disabled-'.$iDis.$hDi.'">Enable JavaScript</a></span>';
            $tmp .= '<span><a href="'.$vars->siteAddress.'/admin/users/update/toggleadmin:'.$uID.':'.$_SESSION['trtv']['twitch_id'].'" class="button controls usr-admin-'.$aID.$hAd.'">Enable JavaScript</a></span>';
            $tmp .= '</p>';
            $tmp .= '<img src="'.$logo.'" alt="User Image">';
            $tmp .= '<p class="username">'.$name.'</p>';
            $tmp .= '<p class="registered-date"><span>Registered On:</span> <span>'.date('M d, Y', $rDate).'</span></p>';
            $tmp .= '<p class="user-type"><span>User Type:</span> <span class="type-of-user">'.$uLvl.'</span></p>';
            $tmp .= '</div>';
          }

          $tmp .= '</div>';

          $html = preg_replace('/\[adminContent\]/', $tmp, $html);
          // print($tmp);
        }
      elseif ( $page == 'homepage-edit' )
        {
          $editor = file_get_contents($vars->pathToStyles.$vars->textEditor);
          $getCurrent = $q->getCurrentHomepageHtml();

          $html = preg_replace('/\[adminContent\]/', $editor, $html);

          if (!$getCurrent || empty($getCurrent)) {
            $getCurrent['usr'] = "Nobody";
            $getCurrent['body'] = "";
          }
            $html = preg_replace('/\[postTitle\]/', '<p><span class="bold">Last Edited by:</span> '.$getCurrent['usr'].'</p>', $html);
            $html = preg_replace('/\[editPost\]/', $getCurrent['body'], $html);
            $html = preg_replace('/\[formTarget\]/', $vars->siteAddress.'/admin/homepage/update', $html);
        }

        $html = preg_replace( '/\[siteAddress\]/', $vars->siteAddress, $html );
        return $html;
    }

  public function rip_tags($string) {

    // ----- remove HTML TAGs -----
    $string = preg_replace ('/<[^>]*>/', ' ', $string);

    // ----- remove control characters -----
    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", ' ', $string);   // --- replace with space
    $string = str_replace("\t", ' ', $string);   // --- replace with space

    // ----- remove multiple spaces -----
    $string = trim(preg_replace('/ {2,}/', ' ', $string));

    return $string;
  }

  public function reviews( $page )
  {
    switch ($page) {
      case "new-review": {
        
        break;
      } // END ADD REVIEW CASE
    }
  }

}