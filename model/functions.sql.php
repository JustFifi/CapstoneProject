<?php
/* ----------------------------------------------
- Author: Rick Anderson
- Revision Date: 29/4/2015
-
-
- Filename: index.php
- Description: All the SQL related functions for the site.
---------------------------------------------- */

/*
 * TwitchReviews by JustFifi
 */

class DatabaseQueries {

  /*
  - Name: checkForUser
  - Arguments: Twitch ID from session
  - Returned Data: Array of the user from the database
  */
  public function checkForUser($twitchID)
  {
    global $db;
    $r = $db->prepare("SELECT * FROM users_usr WHERE usr_twitchID=:id");
    $r->bindParam(':id', $twitchID);
    $r->execute();
    $r = $r->fetch();

    return $r;
  } // END PUBLIC FUNCTION checkForUser


  /*
  - Name: addUserToDatabase
  - Arguments: Twitch name, twitch id, email address, profile picture, local timestamp, disabled (default: 0), user level (default: 1)
  */
  public function addUserToDatabase($name,$twitchID,$email,$logo,$timestamp,$disabled,$usrLvl)
  {
    global $db;
    $sql = "INSERT INTO users_usr(usr_name, usr_email, usr_logo, usr_twitchID, usr_registeredDate, usr_isDisabled, usr_lvl_level) ";
    $sql .= "VALUES (:name, :email, :logo, :twitchID, :tstamp, :disabled, :usrLvl)";

    $r = $db->prepare($sql);
    $r->bindParam(':name', $name);
    $r->bindParam(':email', $email);
    $r->bindParam(':logo', $logo);
    $r->bindParam(':twitchID', $twitchID);
    $r->bindParam(':tstamp', $timestamp);
    $r->bindParam(':disabled', $disabled);
    $r->bindParam(':usrLvl', $usrLvl);
    $r->execute();
  }


  /*
  - Name: updateUserInDatabase
  - arguments: user id from database, an array of changed data
  */
  public function updateUserInDatabase($userID,$arrValues)
  {
    global $db;

    foreach ($arrValues as $k => $v) {
      switch ($k) {
        case "usr_name": {
          $sql = $db->prepare("UPDATE users_usr SET `usr_name`=:data WHERE usr_id=:uID");
          break;
        }
        case "usr_email": {
          $sql = $db->prepare("UPDATE users_usr SET `usr_email`= :data WHERE usr_id=:uID");
          break;
        }
        case "usr_logo": {
          $sql = $db->prepare("UPDATE users_usr SET `usr_logo`= :data WHERE usr_id=:uID");
          break;
        }
      }

      $sql->bindParam(':data', $v);
      $sql->bindParam(':uID', $userID);
      $sql = $sql->execute();
    }
  }

  public function getMemberID($id)
  {
    global $db;

    $sql = $db->prepare("SELECT usr_id FROM users_usr WHERE usr_twitchID=:id");
    $sql->bindParam(':id', $id);
    $sql->execute();
    $sql = $sql->fetch();

    return $sql['usr_id'];
  }

  public function adminGetMembers()
  {
    // Grabs all members from the database
    // Returns an array of data to be parsed in admin/users/
    global $db;

    $sql = $db->prepare("SELECT usr_id, usr_name, usr_email, usr_logo, usr_registeredDate, usr_isDisabled, lvl_name, lvl_value FROM users_usr JOIN levels_lvl ON usr_lvl_level = lvl_id ORDER BY usr_name ASC");
    $sql->execute();
    // $sql = $sql->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);

    return $sql;
  }

  public function checkIfAdmin($id)
  {
    // returns a row of information to be parsed elsewhere for security
    global $db;

    $sql = $db->prepare("SELECT  `lvl_value` as Value ,  `usr_name` as Name FROM users_usr JOIN levels_lvl ON users_usr.usr_lvl_level = levels_lvl.lvl_id WHERE users_usr.usr_twitchID =:id");
    $sql->bindParam(':id', $id);
    $sql->execute();

    $sql = $sql->fetch();

    return $sql;
  }

  public function getAllUsersEmails()
  {
    // used in the send_mail to send the newsletter
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
    // Adds the newsletter to the database to referral later
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
    // Adds a blog post to the database
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
    // retrieves all blogs from the database
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
    // Used to display one blog post anywhere on the site
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
    // Updates the blog in the database
    global $db;

    $sql = "UPDATE news_new SET new_title='".$title."', new_body='".$post."'  WHERE new_id=".$id;
    $db->exec($sql);

    return true;
  }

  public function deleteBlogByID($id)
  {
    // Removes the blog from the database completely
    global $db;

    $sql = "DELETE FROM news_new WHERE new_id=".$id;
    $db->exec($sql);

    return true;
  }

  public function getCurrentHomepageHtml()
  {
    // Grabs the current version of the homepage to be displayed
    global $db;

    $r = $db->prepare("SELECT hp_html as body, usr_name as usr FROM homepage_hp JOIN users_usr ON hp_usr_user=usr_id ORDER BY hp_id DESC LIMIT 1");
    $r->execute();
    $r = $r->fetch();

    return $r;
  }

  public function updateHomepage($html, $user)
  {
    // Used to add a new row into the homepage table to replace the old version of the site
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

  public function getUserAndEmail($id)
  {
    // used when a user is logged in to autocomplete the contact form with their information
    global $db;

    $r = $db->prepare("SELECT usr_name as name, usr_email as email FROM users_usr WHERE usr_id=:id");
    $r->bindParam(':id', $id);

    $r->execute();
    $r = $r->fetch();

    return $r;
  }

  public function toggleUserAdmin($uID)
  {
    // Toggles the users moderation by an admin
    global $db;

    $sql = $db->prepare("SELECT usr_lvl_level as level FROM users_usr WHERE usr_id=:id");
    $sql->bindParam(':id', $uID);
    $sql->execute();

    $result = $sql->fetch();

    if ($result['level'] == 1) { $nLvl = 3; }
    else { $nLvl = 1; }

    $sql = $db->prepare("UPDATE `users_usr` SET usr_lvl_level = :nLvl WHERE usr_id=:id");
    $sql->bindParam(':nLvl', $nLvl);
    $sql->bindParam(':id', $uID);
    $sql->execute();
  }

  public function toggleUserStatus($uID)
  {
    // Toggles the users access to the site
    global $db;

    $sql = $db->prepare("SELECT usr_isDisabled as disabled FROM users_usr WHERE usr_id=:id");
    $sql->bindParam(':id', $uID);
    $sql->execute();

    $result = $sql->fetch();

    if ($result['disabled'] == 1) { $nLvl = 0; }
    else { $nLvl = 1; }

    $sql = $db->prepare("UPDATE `users_usr` SET usr_isDisabled=:nLvl WHERE usr_id=:id");
    $sql->bindParam(':nLvl', $nLvl);
    $sql->bindParam(':id', $uID);
    $sql->execute();
  }

  public function checkTargetUserLevel($uID)
  {
    // grabs the users level from the database for security in the site
    global $db;

    $sql = $db->prepare("SELECT lvl_value as Value FROM users_usr JOIN levels_lvl ON usr_lvl_level=lvl_id WHERE usr_id=:id");
    $sql->bindParam(':id', $uID);
    $sql->execute();

    $sql = $sql->fetch();

    return $sql;
  }

  public function blogsGet10($startAt, $limit)
  {
    // Grabs the 10 blog posts to be displayed at /blogs
    global $db;

    $query = $db->prepare("SELECT new_id as ID, new_title as Title, new_body as Post, new_date as Date, usr_name as Author FROM news_new FULL JOIN users_usr ON new_usr_id=usr_id ORDER BY new_id DESC LIMIT :s,:l");
    $query->bindParam(':s', $startAt, PDO::PARAM_INT);
    $query->bindParam(':l', $limit, PDO::PARAM_INT);
    $query->execute();

    $query = $query->fetchAll();

    return $query;
  }

  public function blogsTotalNoLimit()
  {
    // Grabs all blogs for the administation side
    global $db;

    $sql = $db->prepare("SELECT count(*) as Total FROM news_new");
    $sql->execute();

    $results = $sql->fetch();

    return $results;
  }

  public function blogGetSingle($id)
  {
    // Grabs a single blog for viewing at /blog/:id
    global $db;

    $sql = $db->prepare("SELECT new_title as Title, new_body as Post, new_date as Date, usr_name as Author FROM news_new FULL JOIN users_usr ON new_usr_id=usr_id WHERE new_id=:id");
    $sql->bindParam(':id', $id);
    $sql->execute();

    $result = $sql->fetch();

    return $result;
  }

  public function getLastXreviews($val)
  {
    // Expandable command if it's ever required.
    // Grabs x amount of reviews to be displayed at /reviews
    global $db;

    $sql = $db->prepare("SELECT * FROM reviews_rev JOIN users_usr ON rev_usr_id=usr_id ORDER BY rev_date DESC LIMIT 0 , :l");
    $sql->bindParam(':l', $val, PDO::PARAM_INT);
    $sql->execute();

    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    return $result;
  }

  public function getReviewData($val)
  {
    // Gets the data for :id of the review to display at /reviews/view/:id
    global $db;

    $sql = $db->prepare("SELECT * FROM reviews_rev JOIN users_usr ON rev_usr_id=usr_id WHERE rev_id=:id");
    $sql->bindParam(':id', $val, PDO::PARAM_INT);
    $sql->execute();

    $result = $sql->fetch(PDO::FETCH_ASSOC);

    return $result;
  }

  public function addNewReview($who, $title, $target, $post, $rating)
  {
    // Adds a review to the database
    global $db;

    $time = time();

    $sql = $db->prepare("INSERT INTO reviews_rev(rev_usr_id, rev_title, rev_body, rev_target, rev_rating, rev_date) VALUES(:who, :title, :post, :target, :rating, :time)");
    $sql->bindParam(':who', $who, PDO::PARAM_INT);
    $sql->bindParam(':title', $title);
    $sql->bindParam(':post', $post);
    $sql->bindParam(':target', $target);
    $sql->bindParam(':rating', $rating);
    $sql->bindParam(':time', $time);

    $sql->execute();
  }

  public function deleteReview($revID)
  {
    // Removes a review from the database completely
    global $db;

    $sql = $db->prepare("DELETE FROM reviews_rev WHERE rev_id=:id LIMIT 1");
    $sql->bindParam(':id', $revID);
    $sql->execute();
  }

  public function getTargetImage($tname)
  {
    // Retrieves the profile picture of the review's target if they exist in the database.
    // This is to prevent API callbacks and API ban
    global $db;

    $sql = $db->prepare("SELECT usr_logo FROM users_usr WHERE usr_name=:tn LIMIT 1");
    $sql->bindParam(':tn', $tname);
    $sql->execute();

    $result = $sql->fetch();

    return $result;
  }

  public function reviewNewestIDreturn($who)
  {
    // Loop-back search to redirect the user to the review they just posted
    global $db;

    $sql = $db->prepare("SELECT * FROM reviews_rev WHERE rev_usr_id=:id ORDER BY rev_date DESC LIMIT 1");
    $sql->bindParam(':id', $who, PDO::PARAM_INT);
    $sql->execute();

    $result = $sql->fetch(PDO::FETCH_ASSOC);

    return $result;
  }

  public function CheckForReviewsByTarget($who, $exclude)
  {
    // Grabs 10 reviews related to the target
    global $db;

    $sql = $db->prepare("SELECT * FROM reviews_rev JOIN users_usr ON rev_usr_id=usr_id WHERE rev_target=:who AND rev_id!=:ex ORDER BY rev_date DESC LIMIT 10");
    $sql->bindParam(':who', $who);
    $sql->bindParam(':ex', $exclude);
    $sql->execute();

    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    return $result;
  }

  public function CheckForReviewsByAuthor($who, $exclude)
  {
    // Grabs 10 other reviews the Author has done
    global $db;

    $sql = $db->prepare("SELECT * FROM reviews_rev JOIN users_usr ON rev_usr_id=usr_id WHERE rev_usr_id=:who AND rev_id!=:ex ORDER BY rev_date DESC LIMIT 10");
    $sql->bindParam(':who', $who, PDO::PARAM_INT);
    $sql->bindParam(':ex', $exclude);
    $sql->execute();

    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    return $result;
  }

  public function targetAverageRating($who)
  {
    // Averages the rating of the target
    global $db;

    $sql = $db->prepare("SELECT AVG(rev_rating) AS avg FROM reviews_rev WHERE rev_target=:who");
    $sql->bindParam(':who', $who);
    $sql->execute();

    $result = $sql->fetch();

    return $result;
  }

  public function searchReviewsFor($term)
  {
    // Search feature for exact match
    global $db;

    $sql = $db->prepare("SELECT * FROM reviews_rev JOIN users_usr ON rev_usr_id=usr_id WHERE rev_target=:term");
    $sql->bindParam(':term', $term);
    $sql->execute();

    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    return $result;
  }

  public function searchLike($term)
  {
    // Search feature to try and fix the search terms to find something
    global $db;
    $term = substr($term,1,-1);

    $sql = $db->prepare("SELECT rev_target FROM  reviews_rev WHERE rev_target LIKE :term GROUP BY rev_target");
    $sql->bindValue(':term', '%'.$term.'%');
    $sql->execute();

    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    return $result;
  }

  public function getLast7DaysUsers($value)
  {
    // Grabs a count of Users for a specified day
    $start = strtotime("midnight", $value);
    $end = strtotime('tomorrow', $start) -1;

    global $db;
    $sql = $db->prepare("SELECT count(usr_registeredDate) as value FROM users_usr WHERE usr_registeredDate >= :start AND usr_registeredDate <= :end");
    $sql->bindValue(':start', $start);
    $sql->bindValue(':end', $end);
    $sql->execute();

    $result = $sql->fetch(PDO::FETCH_ASSOC);

    return $result;
  }

  public function getLast7DaysReviews($value)
  {
    // Grabs a count of Reviews for a specified day
    $start = strtotime("midnight", $value);
    $end = strtotime('tomorrow', $start) -1;

    global $db;
    $sql = $db->prepare("SELECT count(rev_date) as value FROM reviews_rev WHERE rev_date >= :start AND rev_date <= :end");
    $sql->bindValue(':start', $start);
    $sql->bindValue(':end', $end);
    $sql->execute();

    $result = $sql->fetch(PDO::FETCH_ASSOC);

    return $result;
  }

}