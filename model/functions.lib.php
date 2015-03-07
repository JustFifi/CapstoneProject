<?php
class Variables
{
  public $siteCreator = "Rick Anderson";
  public $siteAddress = "//justfifi.com/twitchreviews";
  public $noReplyEmail =  'no-reply@justfifi.com';
  public $pathToStyles = "style/";
  public $headerTemplate = "header.tpl.html";
  public $footerTemplate = "footer.tpl.html";
  public $homepage = 'homepage.tpl.html';
  public $textEditor = "editor.tpl.html";
  public $siteTemplate = "site.tpl.html";

  public $adminTemplate = "adminTemplate.tpl.html";
  public $adminDashboard = "adminDashboard.tpl.html";
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
  public $pageTerms = "terms.tpl.html";


  public function send_mail($template, $recipient, $message) {
      $to = $recipient['email'];
      $name = $recipient['name'];

      $message['body'] = preg_replace('/\[emailMessage\]/', $message['body'], $template);

      // Always set content-type when sending HTML email
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= 'From: <'.$this->noReplyEmail.'>' . "\r\n";

      mail($to,$message['subject'],$message['body'],$headers);
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

  public function admin( $page )
    {
      $vars = new Variables;
      $q = new DatabaseQueries;
      $html = file_get_contents($vars->pathToStyles.$vars->adminTemplate);
      $adminLinks = file_get_contents($vars->pathToStyles.$vars->adminLinks);

      $html = preg_replace('/\[adminLinks\]/', $adminLinks, $html);
      $html = preg_replace( '/\[pageHeader\]/', '<h1 id="page-header">PAGEHEADER</h1>', $html );

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
          $html = preg_replace('/\[formTarget\]/', $vars->siteAddress.'/admin/send-newsletter', $html);
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
          $file = file_get_contents($vars->pathToStyles.$vars->adminDashboard);

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
      elseif ( $page == 'blog-add' )
        {
          $html = preg_replace('/\[adminContent\]/', '<p class="success">Blog was successfully added.</p>', $html);
        }
      elseif ( $page == 'blog-update' )
        {
          $html = preg_replace('/\[adminContent\]/', '<p class="success">Blog was successfully updated.</p>', $html);
        }
      elseif ( $page == 'blog-delete' )
        {
          $html = preg_replace('/\[adminContent\]/', '<p class="success">Blog post was successfully deleted.</p>', $html);
        }
      elseif ( $page == 'blog-f' )
        {
          $html = preg_replace('/\[adminContent\]/', '<p class="failure">Something went wrong, blog was lost!</p>', $html);
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
      elseif ( $page == 'homepage-update-s' )
        {
          $html = preg_replace('/\[adminContent\]/', '<p class="success">Homepage has been updated.</p>', $html);
        }
      elseif ( $page == 'homepage-update-f' )
        {
          $html = preg_replace('/\[adminContent\]/', '<p class="success">Homepage has been updated.</p>', $html);
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
}