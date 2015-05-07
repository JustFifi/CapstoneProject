-- -----------------------------------------
--            Account Information
-- Admin: trtv111 -- Password: 11122233
--
-- User: trtv222 -- Password: 11122233
-- -----------------------------------------

CREATE DATABASE IF NOT EXISTS fifitrtv2015c;

USE fifitrtv2015c;

DROP TABLE IF EXISTS `homepage_hp`;
CREATE TABLE IF NOT EXISTS `homepage_hp` (
  `hp_id` int(11) NOT NULL AUTO_INCREMENT,
  `hp_html` longtext NOT NULL,
  `hp_usr_user` int(11) NOT NULL,
  `hp_date` int(64) NOT NULL,
  PRIMARY KEY (`hp_id`),
  KEY `hp_usr_user` (`hp_usr_user`,`hp_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `homepage_hp`
--

INSERT INTO `homepage_hp` (`hp_id`, `hp_html`, `hp_usr_user`, `hp_date`) VALUES
(1, '<h1>OMG</h1>This is just a test!<br>', 1, 1425580644),
(2, '<h1>Welcome to StreamReviews</h1>Here at StreamReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them.&nbsp;<br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><h1>Not sure if your email is validated?</h1>Just try and log into StreamReview and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time.<br>', 1, 1425583793),
(3, '<h1>Welcome to StreamReviews</h1>Here at StreamReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them.&nbsp;<br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><h1>Not sure if your email is validated?</h1>Just try and log into StreamReview and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time.<br>&nbsp;<br>&nbsp;<br>&nbsp;<br><h1>&nbsp;Mrs. FIFI IS THE BEST</h1>I loves her!', 1, 1425584149),
(4, '<h1>Welcome to StreamReviews</h1>Here at StreamReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them.&nbsp;<br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><h1>Not sure if your email is validated?</h1>Just try and log into StreamReview and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time.<br>', 1, 1425584184),
(5, '<h1>Welcome to StreamReviews</h1>Here at StreamReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them. I am editing the homepage.<br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><h1>Not sure if your email is validated?</h1>Just try and log into StreamReview and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time.<br>', 95, 1425995485),
(6, '<h1>Welcome to StreamReviews</h1>Here at StreamReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them.<br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><h1>Not sure if your email is validated?</h1>Just try and log into StreamReview and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time.<br>', 95, 1425995500),
(7, '<ol><li>Welcome to StreamReviews</li></ol>Here at StreamReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them.<br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><h1>Not sure if your email is validated?</h1>Just try and log into StreamReview and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time. WHaT<br>', 95, 1425995904),
(8, '<h1>Welcome to TwitchReviews<br></h1>Here at TwitchReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them.<br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><h1>Not sure if your email is validated?</h1>Just try and log into TwitchReviews and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time.<br>', 1, 1426159218),
(9, '<h1>Welcome to TwitchReviews<br></h1>Here at TwitchReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them.<br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><h1>Not sure if your email is validated?</h1>Just try and log into TwitchReviews and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time.<br>', 1, 1426159432),
(10, '<h1>Welcome to TwitchReviews<br></h1>Here at TwitchReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them.<br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><h1>Not sure if your email is validated?</h1>Just try and log into TwitchReviews and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time.<br>', 1, 1426165342),
(11, '<h1>Welcome to TwitchReviews<br></h1>Here at TwitchReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them.<br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><h1>Not sure if your email is validated?</h1>Just try and log into TwitchReviews and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time.<br>', 1, 1426166009),
(12, '<h1>Welcome to TwitchReviews<br></h1>Here at TwitchReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them.<br><br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><br><h1>Not sure if your email is validated?</h1>Just try and log into TwitchReviews and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time.<br>', 1, 1426166027),
(13, '<h1>Welcome to TwitchReviews<br></h1>Here at TwitchReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them.<br><br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><br><h1>Not sure if your email is validated?</h1>Just try and log into TwitchReviews and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time.<br>', 1, 1426168170),
(14, '<h1>Welcome to TwitchReviews<br></h1>Here at TwitchReviews we offer you the chance to review your favorite streamer publicly. This also gives the chance for the streamer themselves to come and check out what people are saying about them.<br><br><h1>How Do I get started?</h1>As of right now, you can only log in with a Twitch account. You must have a valid email through Twitch in order to login. We only look at your email, profile picture and display name from Twitch. If at any time new features are added that require more access, a newsletter will be sent out to you explaining the new features and how we will use this data.<br><br><h1>Not sure if your email is validated?</h1>Just try and log into TwitchReviews and an error message will appear if you have no validated your email with Twitch. This is a security measure to prevent spam from occuring on this site. If spam does occur, precautions will be put in place at that time.<br>', 1, 1426168203);

-- --------------------------------------------------------

--
-- Table structure for table `levels_lvl`
--

DROP TABLE IF EXISTS `levels_lvl`;
CREATE TABLE IF NOT EXISTS `levels_lvl` (
  `lvl_id` int(11) NOT NULL AUTO_INCREMENT,
  `lvl_name` varchar(45) DEFAULT NULL,
  `lvl_value` int(11) DEFAULT NULL,
  PRIMARY KEY (`lvl_id`),
  KEY `lvl_name` (`lvl_name`),
  KEY `lvl_value` (`lvl_value`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `levels_lvl`
--

INSERT INTO `levels_lvl` (`lvl_id`, `lvl_name`, `lvl_value`) VALUES
(1, 'Reviewer', 2),
(2, 'Site Admin', 9999),
(3, 'Moderator', 9000);

-- --------------------------------------------------------

--
-- Table structure for table `newsletters_ltr`
--

DROP TABLE IF EXISTS `newsletters_ltr`;
CREATE TABLE IF NOT EXISTS `newsletters_ltr` (
  `id_ltr` int(11) NOT NULL AUTO_INCREMENT,
  `date_ltr` int(64) NOT NULL,
  `message_ltr` longtext NOT NULL,
  `by_ltr_usr` int(11) NOT NULL,
  PRIMARY KEY (`id_ltr`),
  KEY `date_ltr` (`date_ltr`,`by_ltr_usr`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `newsletters_ltr`
--

INSERT INTO `newsletters_ltr` (`id_ltr`, `date_ltr`, `message_ltr`, `by_ltr_usr`) VALUES
(7, 1425489928, 'Final test of newsletter system. Testing sql insert and message output in the admin side. Sorry for the email spam today. It will stop for now if this works completely.<br>&nbsp;<br>-Fifi', 1),
(8, 1425490869, 'The last test did not output any data on the website but it did save data to the database. Just have to fix this output issue now. Only a couple more tests Ladies and Gentlebots.', 1),
(9, 1425507641, 'Testing output to website one last time, I think I figured it out. So this might be the last one you guys recieve here! FUCKING CAPITAL LETTERS!', 1),
(10, 1425596208, 'Final unit testing for the Newsletter feature in the adminstration side. I love coding things and then realizing I needed to move the function to a different class. YAY CODE!', 1);

-- --------------------------------------------------------

--
-- Table structure for table `news_new`
--

DROP TABLE IF EXISTS `news_new`;
CREATE TABLE IF NOT EXISTS `news_new` (
  `new_id` int(11) NOT NULL AUTO_INCREMENT,
  `new_title` varchar(150) DEFAULT NULL,
  `new_body` longtext,
  `new_usr_id` int(11) DEFAULT NULL,
  `new_date` int(150) DEFAULT NULL,
  PRIMARY KEY (`new_id`),
  KEY `news_title` (`new_title`),
  KEY `news_usr_id` (`new_usr_id`),
  KEY `news_date` (`new_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

--
-- Dumping data for table `news_new`
--

INSERT INTO `news_new` (`new_id`, `new_title`, `new_body`, `new_usr_id`, `new_date`) VALUES
(3, 'First blog posting test', 'This is my very first blog posting test since I finished coding this in PHP. Hope all is <b>well</b>!\r\n        <br>i hate this thing', 1, 1425541029),
(13, 'added functionality', 'Nothing has been done with the guest/member views but a lot of functionality was added to the admin section. Once the entire administation side is completed, the rest of the site will pull together in no time.<br>&nbsp;<br>Admin features that were added:<br><br><h2>Newsletter page</h2><ul><li>Ability to write and email everyone that uses StreamReviews</li></ul><h2>Blog page</h2><ul><li>Ability to view all blogs</li><li>Add/Edit/Delete the blogs</li><li>Fixed SQL injection issues that occurred during tests.</li></ul>The amount of code that has been going into this is astounding to me. I knew this was going to be a lot of work but, WOW, am I surprised!<br><br>', 1, 1425576047);

-- --------------------------------------------------------

--
-- Table structure for table `reviews_rev`
--

DROP TABLE IF EXISTS `reviews_rev`;
CREATE TABLE IF NOT EXISTS `reviews_rev` (
  `rev_id` int(11) NOT NULL AUTO_INCREMENT,
  `rev_usr_id` int(11) DEFAULT NULL,
  `rev_title` varchar(50) DEFAULT NULL,
  `rev_body` longtext,
  `rev_target` varchar(45) DEFAULT NULL,
  `rev_rating` decimal(2,1) DEFAULT NULL,
  `rev_date` varchar(64) NOT NULL,
  PRIMARY KEY (`rev_id`),
  KEY `fk_rev_usr_id` (`rev_usr_id`),
  KEY `rev_title` (`rev_title`),
  KEY `rev_target` (`rev_target`),
  KEY `rev_rating` (`rev_rating`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='     ' AUTO_INCREMENT=361 ;

--
-- Dumping data for table `reviews_rev`
--

INSERT INTO `reviews_rev` (`rev_id`, `rev_usr_id`, `rev_title`, `rev_body`, `rev_target`, `rev_rating`, `rev_date`) VALUES
(339, 6, 'Love watching people sleep on steam?', 'This streamer is a&nbsp;<b>GREAT&nbsp;</b>person to watch, if you want to watch people sleep! All though he is playing for half of the time, the rest of the stream he is usually half asleep, or fully asleep! All playing aside, Sycorange is a great streamer, and a funny person all around. #DWERK', 'Sycorange', 4.5, '1428529592'),
(340, 6, 'Great, consistent, and friendly streamer', 'Having a great laugh, watching good game play, and amazing interaction with the streamer, and sometimes even his wife! Positive vibes and great content, overall great streamer!', 'Just_Fifi', 5.0, '1428529840'),
(360, 1, 'Test', 'I don''t know, he''s an fajit', 'Just_A_Feller', 3.5, '1430152363');

-- --------------------------------------------------------

--
-- Table structure for table `users_usr`
--

DROP TABLE IF EXISTS `users_usr`;
CREATE TABLE IF NOT EXISTS `users_usr` (
  `usr_id` int(11) NOT NULL AUTO_INCREMENT,
  `usr_name` varchar(45) DEFAULT NULL,
  `usr_email` varchar(254) NOT NULL,
  `usr_logo` text NOT NULL,
  `usr_twitchID` int(50) NOT NULL,
  `usr_registeredDate` int(64) DEFAULT NULL,
  `usr_isDisabled` tinyint(1) DEFAULT NULL,
  `usr_lvl_level` int(11) NOT NULL,
  PRIMARY KEY (`usr_id`),
  UNIQUE KEY `usr_name_UNIQUE` (`usr_name`),
  KEY `fk_usr_lvl_level` (`usr_lvl_level`),
  KEY `usr_registerdDate` (`usr_registeredDate`),
  KEY `usr_isDisabled` (`usr_isDisabled`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=183 ;

--
-- Dumping data for table `users_usr`
--

INSERT INTO `users_usr` (`usr_id`, `usr_name`, `usr_email`, `usr_logo`, `usr_twitchID`, `usr_registeredDate`, `usr_isDisabled`, `usr_lvl_level`) VALUES
(1, 'Just_Fifi', 'contactafifi@gmail.com', 'http://static-cdn.jtvnw.net/jtv_user_pictures/just_fifi-profile_image-0c95469b01468242-300x300.jpeg', 54516014, 1423146447, 0, 2),
(3, 'Just_A_Feller', 'bradleyweavil@yahoo.com', 'http://static-cdn.jtvnw.net/jtv_user_pictures/just_a_feller-profile_image-85eee20e0c887773-300x300.png', 70302212, 1423609628, 0, 1),
(4, 'GR1FFINDOOR', 'GR1FFINDOOR@yahoo.com', 'http://static-cdn.jtvnw.net/jtv_user_pictures/gr1ffindoor-profile_image-c439177f4809a183-300x300.jpeg', 55346581, 1423614937, 0, 1),
(6, 'Wyatt_Cantu', 'i_am_rapidz@yahoo.com', 'http://static-cdn.jtvnw.net/jtv_user_pictures/wyatt_cantu-profile_image-479a9434f264525b-300x300.jpeg', 34806355, 1423630891, 0, 1),
(8, 'thisaccountisusedfortesti', 'williamranderson14@students.abtech.edu', 'http://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_300x300.png', 83746641, 1424886312, 1, 1),
(14, 'born2bagamer', 'janix2011@yahoo.com', 'http://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_300x300.png', 30807406, 1425434413, 0, 3),
(46, 'FIFIBOT_', 'a1533078@trbvm.com', 'http://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_300x300.png', 69968741, 1425773539, 1, 1),
(95, 'trtv111', 'dsgfifi@gmail.com', 'http://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_300x300.png', 84230638, 1425986153, 0, 3),
(96, 'trtv222', 'dsgfifi@gmail.com', 'http://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_300x300.png', 84230687, 1425986194, 0, 1),
(97, 'trtv444', 'dsgfifi@gmail.com', 'http://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_300x300.png', 84860353, 1425986773, 1, 1),
(127, 'sycorange', 'sycorangemen@gmail.com', 'http://static-cdn.jtvnw.net/jtv_user_pictures/sycorange-profile_image-a906ed27e9744b5b-300x300.jpeg', 140035, 1426226806, 0, 1),
(153, 'trtv333', '', 'http://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_300x300.png', 84860056, 1428513196, 0, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reviews_rev`
--
ALTER TABLE `reviews_rev`
  ADD CONSTRAINT `reviews_rev_ibfk_1` FOREIGN KEY (`rev_usr_id`) REFERENCES `users_usr` (`usr_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_usr`
--
ALTER TABLE `users_usr`
  ADD CONSTRAINT `users_usr_ibfk_1` FOREIGN KEY (`usr_lvl_level`) REFERENCES `levels_lvl` (`lvl_id`) ON DELETE CASCADE ON UPDATE CASCADE;
