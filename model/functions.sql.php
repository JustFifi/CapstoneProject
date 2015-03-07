<?php
/*
 * TwitchReviews by JustFifi
 */

class DatabaseQueries {

  public function checkForUser($twitchID)
  {
    global $db;
    $sql = "SELECT * FROM users_usr WHERE usr_twitchID=".$twitchID;
    $r = $db->query($sql);
    $r = $r->fetch();

    if (!$r)
    {
      return false;
    }
    else
    {
      return $r;
    }
  } // END PUBLIC FUNCTION checkForUser

  public function addUserToDatabase($name,$twitchID,$email,$logo,$timestamp,$disabled,$usrLvl)
  {
    global $db;
    $sql = "INSERT INTO users_usr(usr_name,usr_email,usr_logo,usr_twitchID,usr_registeredDate,usr_isDisabled,usr_lvl_level) VALUES ('".$name."','".$email."','".$logo."','".$twitchID."','".$timestamp."',".$disabled.",".$usrLvl.")";
    $db->exec($sql);
    return true;
  }

  public function updateUserInDatabase($userID,$arrValues)
  {
    global $db;

    $package = '';
    foreach ($arrValues as $key => $val)
    {
      $package .= $key."='".$val."', ";
    }

    $sql = "UPDATE users_usr SET ".rtrim($package,", ")." WHERE usr_id=".$userID;

    $db->exec($sql);
  }

  public function getMemberID($id)
  {
    global $db;

    $sql = "SELECT usr_id FROM users_usr WHERE usr_twitchID=".$id;
    $r = $db->query($sql);
    $r = $r->fetch();

    return $r['usr_id'];
  }

  public function adminGetMembers()
  {
    global $db;

    $sql = "SELECT * FROM users_usr";
    $r = $db->query($sql);
    $r = $r->fetchAll();

    if (!$r)
    {
      return false;
    }
    else
    {
      return $r;
    }
  }

  public function checkIfAdmin($id)
  {
    global $db;

    $sql = "SELECT  `lvl_value` as Value ,  `usr_name` as Name FROM users_usr JOIN levels_lvl ON users_usr.usr_lvl_level = levels_lvl.lvl_id WHERE users_usr.usr_twitchID =".$id;
    $sql = $db->query($sql);

    $r = $sql->fetch();

    if (!$r)
    {
      return false;
    }
    else
    {
      return $r;
    }
  }

  public function getAllUsersEmails()
  {
    global $db;

    $sql = "SELECT usr_email as email, usr_name as name FROM users_usr";
    $r = $db->query($sql);
    $r = $r->fetchAll();

    if (!$r)
    {
      return false;
    }
    else
    {
      return $r;
    }
  }

  public function addNewsletter($u, $e, $t)
  {
    global $db;

    $sql = $db->prepare("INSERT INTO newsletters_ltr(date_ltr,message_ltr,by_ltr_usr) VALUES(:t, :e, :u)");
    $sql->bindParam(':t', $t);
    $sql->bindParam(':e', $e);
    $sql->bindParam(':u', $u);
    $sql->execute();

    return true;
  }

  public function addBlogPost($title, $post, $author, $date)
  {
    global $db;

    $sql = $db->prepare('INSERT INTO news_new(new_title,new_body,new_usr_id,new_date) VALUES(:title, :post, :author, :date)');
    $sql->bindParam(':title', $title);
    $sql->bindParam(':post', $post);
    $sql->bindParam(':author', $author);
    $sql->bindParam(':date', $date);
    $sql->execute();

    return true;
  }

  public function adminGetListOfBlogs()
  {
    global $db;

    $sql = "SELECT new_id as ID, new_title as title, usr_name as author FROM news_new JOIN users_usr ON news_new.new_usr_id=users_usr.usr_id ORDER BY new_date DESC";
    $r = $db->query($sql);
    $r = $r->fetchAll();

    if (!$r)
    {
      return false;
    }
    else
    {
      return $r;
    }
  }

  public function getBlogByID($id)
  {
    global $db;

    $sql = "SELECT * FROM news_new WHERE new_id = $id";
    $r = $db->query($sql);
    $r = $r->fetch();

    if (!$r)
    {
      return false;
    }
    else
    {
      return $r;
    }
  }

  public function updateBlogByID($id, $title, $post)
  {
    global $db;

    $sql = "UPDATE news_new SET new_title='".$title."', new_body='".$post."'  WHERE new_id=".$id;
    $db->exec($sql);

    return true;
  }

  public function deleteBlogByID($id)
  {
    global $db;

    $sql = "DELETE FROM news_new WHERE new_id=".$id;
    $db->exec($sql);

    return true;
  }

  public function getCurrentHomepageHtml()
  {
    global $db;

    $r = $db->prepare("SELECT hp_html as body, usr_name as usr FROM homepage_hp JOIN users_usr ON hp_usr_user=usr_id ORDER BY hp_id DESC LIMIT 1");
    $r->execute();
    $r = $r->fetch();

    return $r;
  }

  public function updateHomepage($html, $user)
  {
    $time = time();
    global $db;

    $sql = $db->prepare("INSERT INTO homepage_hp(hp_html, hp_usr_user, hp_date) VALUES(:html, :user, :date)");
    $sql->bindParam(':html', $html);
    $sql->bindParam(':user', $user);
    $sql->bindParam(':date', $time);
    if ($sql->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function getUserAndEmail($id) {
    global $db;

    $r = $db->prepare("SELECT usr_name as name, usr_email as email FROM users_usr WHERE usr_id=:id");
    $r->bindParam(':id', $id);

    $r->execute();
    $r = $r->fetch();

    return $r;
  }

}