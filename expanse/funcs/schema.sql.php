<?php 
$default_install_values = array();
$default_install_values['your_name'] = isset($yourname) ? $Database->Escape($yourname) : '';
$default_install_values['admin_email'] = isset($adminemail) ? $Database->Escape($adminemail) : '';
$default_install_values['site_name'] = isset($sitename) ? $Database->Escape($sitename) : '';
$default_install_values['site_url'] = $Database->Escape(preg_replace('|/expanse/*('.basename($_SERVER['PHP_SELF']).'.*)+$|i', '', PROTOCOL.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));
$default_install_values['time'] = time();
$default_install_values['random_password'] = random_string();
$default_install_values['random_password_md5'] = md5($default_install_values['random_password']);
$default_install_values['version'] = isset($cms_version) ? $cms_version : '';
$default_install_values['permissions'] = serialize(range(1, 1));
$default_install_values['active_plugins'] = serialize(array("alter_menu.php", "typogrify/php-typogrify.php"));


$schema = array(
			'prepare' => '',
			'structure' => '',
			'populate'
			);
$schema['prepare'] =
"DROP TABLE IF EXISTS `{$Database->Prefix}items`;
DROP TABLE IF EXISTS `{$Database->Prefix}images`;
DROP TABLE IF EXISTS `{$Database->Prefix}comments`;
DROP TABLE IF EXISTS `{$Database->Prefix}customfields`;
DROP TABLE IF EXISTS `{$Database->Prefix}hackattempts`;
DROP TABLE IF EXISTS `{$Database->Prefix}prefs`;
DROP TABLE IF EXISTS `{$Database->Prefix}sections`;
DROP TABLE IF EXISTS `{$Database->Prefix}users`;
";

$schema['structure'] = 
"CREATE TABLE `items` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `online` tinyint(1) NOT NULL default '0',
  `order_rank` int(11) NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `materials` varchar(255) NOT NULL default '',
  `rating` tinyint(4) NOT NULL default '0',
  `image` varchar(255) NOT NULL default '',
  `thumbnail` varchar(255) NOT NULL default '',
  `height` varchar(10) NOT NULL default '',
  `width` varchar(10) NOT NULL default '',
  `crop_x` int(5) NOT NULL default '0',
  `crop_y` int(5) NOT NULL default '0',
  `thumb_w` int(5) NOT NULL default '0',
  `thumb_h` int(5) NOT NULL default '0',
  `thumb_max` int(5) NOT NULL default '50',
  `use_default_thumbsize` tinyint(1) NOT NULL default '1',
  `descr` longtext NOT NULL,
  `created` int(10) default NULL,
  `dirtitle` varchar(255) NOT NULL default '',
  `event_date` varchar(45) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `comments` tinyint(1) NOT NULL default '0',
  `smilies` tinyint(1) NOT NULL default '0',
  `type` varchar(255) NOT NULL default '',
  `menu_order` int(5) NOT NULL default '1',
  `aid` int(11) NOT NULL default '0',
  `cid` int(11) NOT NULL default '0',
  `pid` int(11) NOT NULL default '0',
  `autothumb` tinyint(1) NOT NULL default '0',
  `extraoptions` longtext NOT NULL,
  `for_sale` tinyint(1) NOT NULL default '0',
  `paypal_amount` varchar(255) NOT NULL default '',
  `paypal_item_number` int(255) NOT NULL default '0',
  `paypal_handling` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  FULLTEXT(`title`, `descr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `images` (
  `id` int(11) NOT NULL auto_increment,
  `image` varchar(255) NOT NULL default '',
  `thumbnail` varchar(255) NOT NULL default '',
  `height` varchar(10) NOT NULL default '',
  `width` varchar(10) NOT NULL default '',
  `itemid` int(11) NOT NULL default '0',
  `caption` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `comments` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `online` tinyint(1) NOT NULL default '0',
  `itemid` int(11) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `email` varchar(150) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `created` int(10) default NULL,
  `cid` int(2) NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `sec_id` (`itemid`),
  KEY `cid` (`cid`),
  KEY `itemid` (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `customfields` (
  `id` int(11) NOT NULL auto_increment,
  `itemid` int(11) NOT NULL default '0',
  `field` varchar(255) default NULL,
  `value` longtext,
  PRIMARY KEY  (`id`),
  KEY `post_id` (`itemid`),
  KEY `meta_key` (`field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `prefs` (
  `id` int(11) NOT NULL auto_increment,
  `opt_name` varchar(255) NOT NULL default '',
  `opt_value` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `sections` (
  `id` int(11) NOT NULL auto_increment,
  `sectionname` varchar(255) NOT NULL default '',
  `descr` longtext NOT NULL default '',
  `order_rank` int(11) NOT NULL,
  `pid` int(11) default NULL,
  `public` tinyint(1) NOT NULL default '0',
  `dirtitle` varchar(255) NOT NULL default '',
  `cat_type` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(50) NOT NULL default '',
  `displayname` varchar(255) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `email` varchar(150) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `permissions` varchar(255) NOT NULL default '',
  `created` int(10) NOT NULL default '0',
  `primary_user` tinyint(1) NOT NULL default '0',
  `admin` tinyint(1) NOT NULL default '0',
  `disabled` tinyint(1) NOT NULL default '0',
  `reset_key` varchar(255) NOT NULL default '',
  `section_admin` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE `sessions` (
 `id` varchar(255) binary NOT NULL default '',
 `expires` int(10) unsigned NOT NULL default '0',
 `data` text,
 PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

$schema['populate'] =
"INSERT INTO `items` ( `online` , `order_rank` , `title` , `materials` , `rating` , `image` , `thumbnail` , `height` , `width` , `crop_x` , `crop_y` , `thumb_w` , `thumb_h` , `thumb_max` , `use_default_thumbsize` , `descr` , `created` , `dirtitle` , `event_date` , `url` , `comments` , `smilies` , `type` , `menu_order` , `aid` , `cid` , `pid` , `autothumb` , `extraoptions` , `for_sale` , `paypal_amount` , `paypal_item_number` , `paypal_handling` ) 
VALUES (
'1', '1', 'Contact', '', '', '0', '', '', '', '0', '0', '0', '0', '50', '1', '<p>Put any content you would like above your contact form here.</p>', '$default_install_values[time]', 'contact', '', '', '0', '1', 'static', '7', '1', '0', '0', '0', '', '0', '', '0', '');

INSERT INTO `prefs` VALUES (NULL,'yourname', '$default_install_values[your_name]');
INSERT INTO `prefs` VALUES (NULL,'adminemail', '$default_install_values[admin_email]');
INSERT INTO `prefs` VALUES (NULL,'sitename', '$default_install_values[site_name]');
INSERT INTO `prefs` VALUES (NULL,'sitedescr', 'a site for really awesome stuff');
INSERT INTO `prefs` VALUES (NULL,'yoursite', '$default_install_values[site_url]');
INSERT INTO `prefs` VALUES (NULL,'expanseversion', '$default_install_values[version]');
INSERT INTO `prefs` VALUES (NULL,'bannedwords', 'poker, vicodin, texas hold-em, texas holdem, casino, gambling, blackjack, penis, pharmacy, mortgage, loan, consolidation, consolidate, equity, viagra, levitra, credit card, keno, holdem, slots, texas-hold-em, texas hold''em, roulette, baccarat , gambling, craps, black jack, black-jack, content-type');
INSERT INTO `prefs` VALUES (NULL,'bannedips', '');
INSERT INTO `prefs` VALUES (NULL,'startcategory', '2');
INSERT INTO `prefs` VALUES (NULL,'floodcontrol', '20');
INSERT INTO `prefs` VALUES (NULL,'sortcats', 'order_rank');
INSERT INTO `prefs` VALUES (NULL,'howmany', '10');
INSERT INTO `prefs` VALUES (NULL,'howmany_edit', '20');
INSERT INTO `prefs` VALUES (NULL,'thumbsize', '100');
INSERT INTO `prefs` VALUES (NULL,'sortdirection', 'DESC');
INSERT INTO `prefs` VALUES (NULL,'theme', 'The_Neue_Standard');
INSERT INTO `prefs` VALUES (NULL,'dateformat', 'l, F jS, Y');
INSERT INTO `prefs` VALUES (NULL,'timeformat', 'g:i a ');
INSERT INTO `prefs` VALUES (NULL,'timeoffset', '0');
INSERT INTO `prefs` VALUES (NULL,'use_clean_urls', '0');
INSERT INTO `prefs` VALUES (NULL,'index_file', 'index.php');
INSERT INTO `prefs` VALUES (NULL,'paypal_email', '$default_install_values[admin_email]');
INSERT INTO `prefs` VALUES (NULL,'paypal_logo', '');
INSERT INTO `prefs` VALUES (NULL,'paypal_currency_code', 'USD');
INSERT INTO `prefs` VALUES (NULL,'paypal_shipping', '');
INSERT INTO `prefs` VALUES (NULL,'paypal_shipping2', '');
INSERT INTO `prefs` VALUES (NULL,'paypal_tax', '');
INSERT INTO `prefs` VALUES (NULL,'paypal_handling_cart', '');
INSERT INTO `prefs` VALUES (NULL,'commentsmilies', '1');
INSERT INTO `prefs` VALUES (NULL,'active_plugins', '$default_install_values[active_plugins]');
INSERT INTO `prefs` VALUES (NULL,'language', 'en-us');

INSERT INTO `sections` VALUES (1, 'Pages','', 0, 0, 1, 'pages', 'pages');
INSERT INTO `sections` VALUES (2, 'Gallery','', 2, 0, 1, 'gallery', 'gallery');
INSERT INTO `sections` VALUES (3, 'Events','', 3, 0, 1, 'events', 'events');
INSERT INTO `sections` VALUES (4, 'Press','', 4, 0, 1, 'press', 'press');
INSERT INTO `sections` VALUES (5, 'Links','', 5, 0, 1, 'links', 'links');
INSERT INTO `sections` VALUES (6, 'Blog', '', 6, 0, 1, 'blog', 'blog');


INSERT INTO `users` VALUES ('1', 'admin', '$default_install_values[your_name]', '$default_install_values[random_password_md5]', '$default_install_values[admin_email]', '$default_install_values[site_url]', '$default_install_values[permissions]', '$default_install_values[time]' , '1', '1', '0', '', '1');
";
?>